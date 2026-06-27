<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class GovernanceDecisionProduced extends AbstractDomainEvent
{
    public readonly int $decisionVersion;

    public function __construct(
        public readonly string $decisionId,
        public readonly string $tenantId,
        public readonly string $capabilityType,
        public readonly string $resolutionType,
        public readonly ?string $winningAuthorityId,
    ) {
        parent::__construct();
        $this->decisionVersion = 1;
    }

    public static function eventName(): string
    {
        return 'governance.decision.produced';
    }

    public function metadata(): array
    {
        return [
            'decision' => [
                'id'      => $this->decisionId,
                'version' => $this->decisionVersion,
            ],
            'capability'  => ['type' => $this->capabilityType],
            'resolution'  => [
                'type'                 => $this->resolutionType,
                'winning_authority_id' => $this->winningAuthorityId,
            ],
            'tenant' => ['id' => $this->tenantId],
        ];
    }
}
