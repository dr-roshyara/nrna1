<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class InvalidateMembershipDashboardCache
{
    public function handle($event): void
    {
        $organisationId = $this->extractOrganisationId($event);

        if (! $organisationId) {
            return;
        }

        foreach (['owner', 'admin', 'commission', 'member'] as $role) {
            Cache::forget("membership_dashboard_{$organisationId}_{$role}");
        }
    }

    private function extractOrganisationId($event): ?string
    {
        if (property_exists($event, 'organisation') && $event->organisation) {
            return (string) $event->organisation->id;
        }
        if (property_exists($event, 'application') && $event->application) {
            return (string) $event->application->organisation_id;
        }
        if (property_exists($event, 'fee') && $event->fee) {
            return (string) $event->fee->organisation_id;
        }
        if (property_exists($event, 'renewal') && $event->renewal) {
            return (string) $event->renewal->organisation_id;
        }
        if (property_exists($event, 'member') && $event->member) {
            return (string) $event->member->organisation_id;
        }
        return null;
    }
}
