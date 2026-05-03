<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\MemberStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Eloquent Member Repository
 *
 * This is the ONLY class allowed to perform database queries on Member.
 *
 * Architectural Rule (ADR-001):
 * - All queries MUST go through this repository
 * - Transaction handling for aggregate consistency
 * - Domain event dispatching on save
 * - Member::where() is ALLOWED here (infrastructure layer)
 *
 * @see docs/adr/001-eloquent-aggregate-pattern.md
 */
class EloquentMemberRepository implements MemberRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * ADR-002: New "ForTenant" method (wraps old save() method for backward compatibility)
     */
    public function saveForTenant(TenantId $tenantId, Member $member): void
    {
        // Verify tenant isolation
        if (!$member->belongsToTenant($tenantId)) {
            throw new \DomainException('Member does not belong to tenant');
        }
        $this->save($member);
    }

    /**
     * Legacy method - kept for backward compatibility
     *
     * Implementation notes:
     * - Wraps in transaction for consistency
     * - Dispatches recorded domain events after successful save
     * - Events are dispatched automatically via RecordsEvents trait + booted() hook
     *
     * @todo ADR-002: Remove this method after all code migrated to saveForTenant() (Week 6)
     * @deprecated Use saveForTenant() instead
     */
    private function save(Member $member): void
    {
        DB::connection('tenant')->transaction(function () use ($member) {
            $member->save();
            // Events are automatically dispatched via Member::booted() hook
            // which calls dispatchRecordedEvents() after save
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findById(string $id): ?Member
    {
        return Member::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByMemberId(string $tenantId, string $memberId): ?Member
    {
        return Member::where('tenant_id', $tenantId)
            ->where('member_id', $memberId)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findByTenantUserId(string $tenantId, string $tenantUserId): ?Member
    {
        return Member::where('tenant_id', $tenantId)
            ->where('tenant_user_id', $tenantUserId)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function existsByMemberId(string $tenantId, string $memberId): bool
    {
        return Member::where('tenant_id', $tenantId)
            ->where('member_id', $memberId)
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function existsByTenantUserId(string $tenantId, string $tenantUserId): bool
    {
        return Member::where('tenant_id', $tenantId)
            ->where('tenant_user_id', $tenantUserId)
            ->exists();
    }

    /**
     * {@inheritdoc}
     *
     * @todo ADR-002: Refactor to match interface signature pattern (Week 5)
     */
    public function existsByEmailForTenant(TenantId $tenantId, string $email): bool
    {
        return Member::where('tenant_id', $tenantId->toString())
            ->where('email', $email)
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Member $member): void
    {
        DB::connection('tenant')->transaction(function () use ($member) {
            $member->delete();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findByTenant(string $tenantId, int $page = 1, int $perPage = 50): Collection
    {
        return Member::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findByStatus(string $tenantId, MemberStatus $status): Collection
    {
        return Member::where('tenant_id', $tenantId)
            ->where('status', $status->value())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * {@inheritdoc}
     *
     * Implementation notes:
     * - Uses LIKE for prefix matching when includeDescendants = true
     * - Geography is decoupled, no joins to geography tables
     */
    public function findByGeography(
        string $tenantId,
        string $geoReference,
        bool $includeDescendants = false
    ): Collection {
        $query = Member::where('tenant_id', $tenantId);

        if ($includeDescendants) {
            // Match "np.3.15" and "np.3.15.234" and "np.3.15.234.1"
            $query->where('residence_geo_reference', 'LIKE', $geoReference . '%');
        } else {
            // Exact match only
            $query->where('residence_geo_reference', $geoReference);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function countByStatus(string $tenantId, MemberStatus $status): int
    {
        return Member::where('tenant_id', $tenantId)
            ->where('status', $status->value())
            ->count();
    }

    /**
     * {@inheritdoc}
     */
    public function countByTenant(string $tenantId): int
    {
        return Member::where('tenant_id', $tenantId)
            ->whereNotIn('status', ['archived']) // Exclude archived
            ->count();
    }

    // ========================================================================
    // ADR-002: "ForTenant" Alias Methods (Backward Compatibility Layer)
    // These methods wrap legacy methods to satisfy interface contract
    // Will be refactored in Week 5 to replace legacy methods entirely
    // ========================================================================

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findByIdForTenant(TenantId $tenantId, string $id): ?Member
    {
        $member = $this->findById($id);

        // Verify tenant ownership
        if ($member && !$member->belongsToTenant($tenantId)) {
            return null; // Member exists but belongs to different tenant
        }

        return $member;
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findByMemberIdForTenant(TenantId $tenantId, string $memberId): ?Member
    {
        return $this->findByMemberId($tenantId->toString(), $memberId);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findByTenantUserIdForTenant(TenantId $tenantId, string $tenantUserId): ?Member
    {
        return $this->findByTenantUserId($tenantId->toString(), $tenantUserId);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function existsByMemberIdForTenant(TenantId $tenantId, string $memberId): bool
    {
        return $this->existsByMemberId($tenantId->toString(), $memberId);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function existsByTenantUserIdForTenant(TenantId $tenantId, string $tenantUserId): bool
    {
        return $this->existsByTenantUserId($tenantId->toString(), $tenantUserId);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function deleteForTenant(TenantId $tenantId, Member $member): void
    {
        // Verify tenant isolation
        if (!$member->belongsToTenant($tenantId)) {
            throw new \DomainException('Member does not belong to tenant');
        }
        $this->delete($member);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findAllForTenant(TenantId $tenantId, int $page = 1, int $perPage = 50): Collection
    {
        return $this->findByTenant($tenantId->toString(), $page, $perPage);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findByStatusForTenant(TenantId $tenantId, MemberStatus $status): Collection
    {
        return $this->findByStatus($tenantId->toString(), $status);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function findByGeographyForTenant(
        TenantId $tenantId,
        string $geoReference,
        bool $includeDescendants = false
    ): Collection {
        return $this->findByGeography($tenantId->toString(), $geoReference, $includeDescendants);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function countByStatusForTenant(TenantId $tenantId, MemberStatus $status): int
    {
        return $this->countByStatus($tenantId->toString(), $status);
    }

    /**
     * {@inheritdoc}
     * @see ADR-002
     */
    public function countForTenant(TenantId $tenantId): int
    {
        return $this->countByTenant($tenantId->toString());
    }
}
