<?php

namespace App\Jobs;

use App\Events\Newsletter\NewsletterEmailFailed;
use App\Events\Newsletter\NewsletterEmailSent;
use App\Mail\OrganisationNewsletterMail;
use App\Models\NewsletterAuditLog;
use App\Models\NewsletterRecipient;
use App\Models\OrganisationNewsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendNewsletterBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public array $backoff = [60, 300, 900];

    public function __construct(
        public readonly int $newsletterId,
        public readonly array $recipientIds
    ) {}

    public function handle(): void
    {
        $newsletter = OrganisationNewsletter::with('attachments')->find($this->newsletterId);

        if (! $newsletter || in_array($newsletter->status, ['cancelled', 'failed'])) {
            return;
        }

        if ($newsletter->isKillSwitchTriggered()) {
            $newsletter->update(['status' => 'failed']);

            NewsletterAuditLog::create([
                'organisation_newsletter_id' => $newsletter->id,
                'organisation_id'            => $newsletter->organisation_id,
                'actor_user_id'              => $newsletter->created_by,
                'action'                     => 'failed',
                'metadata'                   => [
                    'failure_rate' => round($newsletter->failureRate() * 100, 1) . '%',
                    'reason'       => 'kill_switch_triggered',
                ],
            ]);
            return;
        }

        $recipients = NewsletterRecipient::whereIn('id', $this->recipientIds)
            ->where('status', 'pending')
            ->get();

        foreach ($recipients as $recipient) {
            $lock = Cache::lock("newsletter:recipient:{$recipient->id}", 30);

            if (! $lock->get()) {
                continue; // Another process is handling this recipient
            }

            try {
                // Double-check under lock
                $recipient->refresh();
                if ($recipient->status !== 'pending') {
                    continue;
                }

                $recipient->update(['status' => 'sending']);

                Mail::to($recipient->email)->send(new OrganisationNewsletterMail($newsletter, $recipient));

                $recipient->update(['status' => 'sent', 'sent_at' => now()]);
                OrganisationNewsletter::where('id', $this->newsletterId)->increment('sent_count');
                event(new NewsletterEmailSent($recipient));

            } catch (\Throwable $e) {
                $recipient->update([
                    'status'        => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                OrganisationNewsletter::where('id', $this->newsletterId)->increment('failed_count');
                event(new NewsletterEmailFailed($recipient, $e->getMessage()));
            } finally {
                $lock->release();
            }
        }

        // Check if all recipients are done
        $pendingCount = NewsletterRecipient::where('organisation_newsletter_id', $this->newsletterId)
            ->whereIn('status', ['pending', 'sending'])
            ->count();

        if ($pendingCount === 0) {
            $newsletter->refresh();
            if ($newsletter->status === 'processing') {
                $newsletter->update(['status' => 'completed', 'completed_at' => now()]);

                NewsletterAuditLog::create([
                    'organisation_newsletter_id' => $newsletter->id,
                    'organisation_id'            => $newsletter->organisation_id,
                    'actor_user_id'              => $newsletter->created_by,
                    'action'                     => 'completed',
                    'metadata'                   => [
                        'sent_count'   => $newsletter->sent_count,
                        'failed_count' => $newsletter->failed_count,
                    ],
                ]);
            }
        }
    }
}
