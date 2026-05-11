<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Context;

use App\Contexts\Membership\Domain\Committee\Actor\ActorPosition;
use App\Contexts\Membership\Domain\Committee\Services\GeographicScopeRelationService;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class ActorContext
{
    public function __construct(
        public string $userId,
        public TenantId $tenantId,
        public ActorPosition $position,
        public GeographicScope $geographicScope,
        public ?string $committeeId = null,
        public bool $isSystemActor = false,
    ) {}

    public function isOwner(): bool
    {
        return $this->position === ActorPosition::OWNER;
    }

    public function hasGovernanceAuthority(): bool
    {
        return $this->position->hasGovernanceAuthority();
    }

    public function canOperateIn(GeographicScope $scope): bool
    {
        return GeographicScopeRelationService::canOperate(
            $this->geographicScope,
            $scope
        );
    }

    public static function system(TenantId $tenantId): self
    {
        return new self(
            userId: 'system',
            tenantId: $tenantId,
            position: ActorPosition::OWNER,
            geographicScope: new GeographicScope('national', null),
            isSystemActor: true,
        );
    }
}
