<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Committee Formed Domain Event
 *
 * Raised when a new committee is created in the system.
 * Contains all necessary data for committee lifecycle tracking.
 *
 * Properties:
 * - committeeId: The ID of the committee that was formed
 * - tenantId: The tenant to which the committee belongs
 * - type: The type of committee (central, geographic, youth, etc.)
 * - name: The name of the committee
 * - operationalGeo: Optional geography reference where committee operates
 */
final class CommitteeFormed extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly string $tenantId,
        public readonly string $type,
        public readonly string $name,
        public readonly ?string $operationalGeo
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'committee.formed';
    }

    /**
     * Get event metadata for auditing
     */
    public function metadata(): array
    {
        return [
            'committee_id' => $this->committeeId,
            'tenant_id' => $this->tenantId,
            'type' => $this->type,
            'name' => $this->name,
            'operational_geo' => $this->operationalGeo,
            'event_type' => self::eventName(),
        ];
    }
}