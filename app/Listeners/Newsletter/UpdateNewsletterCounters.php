<?php

namespace App\Listeners\Newsletter;

use App\Events\Newsletter\NewsletterEmailFailed;
use App\Events\Newsletter\NewsletterEmailSent;
use App\Models\NewsletterAuditLog;
use App\Models\OrganisationNewsletter;

class UpdateNewsletterCounters
{
    public function handleSent(NewsletterEmailSent $event): void
    {
        // Counters are incremented directly in SendNewsletterBatchJob.
        // This listener is reserved for future hooks (notifications, webhooks, etc.).
    }

    public function handleFailed(NewsletterEmailFailed $event): void
    {
        $newsletterId = $event->recipient->organisation_newsletter_id;

        // Check kill switch — counters already incremented in job.
        $newsletter = OrganisationNewsletter::find($newsletterId);

        if ($newsletter && $newsletter->status === 'processing' && $newsletter->isKillSwitchTriggered()) {
            $newsletter->update(['status' => 'failed']);

            NewsletterAuditLog::create([
                'organisation_newsletter_id' => $newsletter->id,
                'organisation_id'            => $newsletter->organisation_id,
                'actor_user_id'              => $newsletter->created_by,
                'action'                     => 'failed',
                'metadata'                   => [
                    'failure_rate' => round($newsletter->failureRate() * 100, 1) . '%',
                    'sent_count'   => $newsletter->sent_count,
                    'failed_count' => $newsletter->failed_count,
                    'reason'       => 'kill_switch_triggered',
                ],
            ]);
        }
    }
}
