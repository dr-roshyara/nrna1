<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Contexts\Membership\Domain\ValueObjects\MemberStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * MemberRegistered Domain Event
 *
 * Published when a new member is registered in the system.
 * Other contexts (Committee, Geography, DigitalCard) can listen to this event.
 */
class MemberRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $memberId,
        public readonly string $tenantUserId,
        public readonly string $tenantId,
        public readonly MemberStatus $status,
        public readonly array $personalInfo
    ) {
    }

    /**
     * Get event data as array (for logging/debugging)
     */
    public function toArray(): array
    {
        return [
            'member_id' => $this->memberId,
            'tenant_user_id' => $this->tenantUserId,
            'tenant_id' => $this->tenantId,
            'status' => $this->status->value(),
            'personal_info' => $this->personalInfo,
        ];
    }
}
