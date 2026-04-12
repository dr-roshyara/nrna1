<?php

namespace App\Jobs;

use App\Models\NewsletterAuditLog;
use App\Models\NewsletterRecipient;
use App\Models\OrganisationNewsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchNewsletterBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries   = 1;

    public function __construct(
        public readonly int $newsletterId
    ) {}

    public function handle(): void
    {
        $newsletter = OrganisationNewsletter::find($this->newsletterId);

        if (! $newsletter || $newsletter->status !== 'queued') {
            return;
        }

        $newsletter->update(['status' => 'processing']);

        $recipientIds = NewsletterRecipient::where('organisation_newsletter_id', $this->newsletterId)
            ->where('status', 'pending')
            ->pluck('id')
            ->toArray();

        if (empty($recipientIds)) {
            $newsletter->update(['status' => 'completed', 'completed_at' => now()]);

            NewsletterAuditLog::create([
                'organisation_newsletter_id' => $newsletter->id,
                'organisation_id'            => $newsletter->organisation_id,
                'actor_user_id'              => $newsletter->created_by,
                'action'                     => 'completed',
                'metadata'                   => ['recipient_count' => 0],
            ]);
            return;
        }

        foreach (array_chunk($recipientIds, 50) as $chunk) {
            SendNewsletterBatchJob::dispatch($this->newsletterId, $chunk)->onQueue('emails-normal');
        }
    }
}
