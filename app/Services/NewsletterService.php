<?php

namespace App\Services;

use App\Exceptions\InvalidNewsletterStateException;
use App\Jobs\DispatchNewsletterBatchJob;
use App\Models\Member;
use App\Models\NewsletterAuditLog;
use App\Models\NewsletterRecipient;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsletterService
{
    private const ALLOWED_TAGS = '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><img>';

    public function sanitiseHtml(string $html): string
    {
        return strip_tags($html, self::ALLOWED_TAGS);
    }

    public function createDraft(
        Organisation $org,
        User $creator,
        array $data,
        Request $request
    ): OrganisationNewsletter {
        $html = strip_tags($data['html_content'], self::ALLOWED_TAGS);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $org->id,
            'created_by'      => $creator->id,
            'subject'         => $data['subject'],
            'html_content'    => $html,
            'plain_text'      => $data['plain_text'] ?? null,
            'status'          => 'draft',
        ]);

        NewsletterAuditLog::create([
            'organisation_newsletter_id' => $newsletter->id,
            'organisation_id'            => $org->id,
            'actor_user_id'              => $creator->id,
            'action'                     => 'created',
            'metadata'                   => ['subject' => $data['subject']],
            'ip_address'                 => $request->ip(),
        ]);

        return $newsletter;
    }

    public function previewRecipientCount(OrganisationNewsletter $newsletter): int
    {
        return Member::withoutGlobalScopes()
            ->where('organisation_id', $newsletter->organisation_id)
            ->where('status', 'active')
            ->whereNull('newsletter_unsubscribed_at')
            ->whereNull('newsletter_bounced_at')
            ->count();
    }

    public function dispatch(
        OrganisationNewsletter $newsletter,
        Organisation $org,
        User $actor,
        Request $request
    ): void {
        if ($newsletter->status !== 'draft') {
            throw new InvalidNewsletterStateException(
                "Newsletter cannot be dispatched from status [{$newsletter->status}]."
            );
        }

        DB::transaction(function () use ($newsletter, $org, $actor, $request) {
            // Assign unsubscribe tokens to members that don't have one
            Member::withoutGlobalScopes()
                ->where('organisation_id', $org->id)
                ->whereNull('newsletter_unsubscribe_token')
                ->each(function (Member $member) {
                    $member->update(['newsletter_unsubscribe_token' => Str::random(64)]);
                });

            // Build recipient list
            $members = Member::withoutGlobalScopes()
                ->where('organisation_id', $org->id)
                ->where('status', 'active')
                ->whereNull('newsletter_unsubscribed_at')
                ->whereNull('newsletter_bounced_at')
                ->with('organisationUser.user')
                ->get();

            $recipientRows = $members->map(function (Member $member) use ($newsletter) {
                $user = $member->organisationUser->user ?? null;
                return [
                    'organisation_newsletter_id' => $newsletter->id,
                    'member_id'                  => $member->id,
                    'email'                      => $user?->email ?? '',
                    'name'                       => $user?->name ?? null,
                    'status'                     => 'pending',
                    'idempotency_key'            => hash('sha256', $newsletter->id . ':' . $member->id),
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ];
            })->filter(fn($r) => $r['email'] !== '')->values();

            foreach ($recipientRows->chunk(500) as $chunk) {
                NewsletterRecipient::insert($chunk->toArray());
            }

            $newsletter->update([
                'status'           => 'queued',
                'idempotency_key'  => hash('sha256', $org->id . ':' . $newsletter->id . ':' . now()->timestamp),
                'queued_at'        => now(),
                'total_recipients' => $recipientRows->count(),
            ]);

            NewsletterAuditLog::create([
                'organisation_newsletter_id' => $newsletter->id,
                'organisation_id'            => $org->id,
                'actor_user_id'              => $actor->id,
                'action'                     => 'dispatched',
                'metadata'                   => ['recipient_count' => $recipientRows->count()],
                'ip_address'                 => $request->ip(),
            ]);

            DispatchNewsletterBatchJob::dispatch($newsletter->id)->onQueue('emails-normal');
        });
    }

    public function cancel(
        OrganisationNewsletter $newsletter,
        User $actor,
        Request $request
    ): void {
        if (! in_array($newsletter->status, ['draft', 'processing'])) {
            throw new InvalidNewsletterStateException(
                "Newsletter cannot be cancelled from status [{$newsletter->status}]."
            );
        }

        $newsletter->update(['status' => 'cancelled']);

        NewsletterAuditLog::create([
            'organisation_newsletter_id' => $newsletter->id,
            'organisation_id'            => $newsletter->organisation_id,
            'actor_user_id'              => $actor->id,
            'action'                     => 'cancelled',
            'ip_address'                 => $request->ip(),
        ]);
    }
}
