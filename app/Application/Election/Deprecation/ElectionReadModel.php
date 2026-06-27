<?php

namespace App\Application\Election\Deprecation;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;

/**
 * ElectionReadModel: Deprecation-Aware Access Wrapper
 *
 * This wrapper enforces that all access to Election data goes through controlled paths.
 * Legacy field access is logged/warned/blocked based on deprecation mode.
 * SSOT paths (lifecycle, state, permissions) are transparent and encouraged.
 *
 * This is the primary way to read election data in Phase 2+.
 */
final class ElectionReadModel
{
    private DeprecationAccessGuard $guard;

    private string $mode;

    public function __construct(
        private readonly Election $election,
        ?string $mode = null,
    ) {
        $this->guard = app(DeprecationAccessGuard::class);
        $this->mode = $mode ?? DeprecationPolicy::MODE;
    }

    /**
     * Factory method: wrap an election instance.
     *
     * @param Election $election
     * @param string|null $mode Override deprecation mode
     * @return self
     */
    public static function wrap(Election $election, ?string $mode = null): self
    {
        return new self($election, $mode);
    }

    /**
     * Get the wrapped election ID.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->election->id;
    }

    /**
     * ============================================================================
     * SSOT PATHS (Encouraged)
     * ============================================================================
     */

    /**
     * Get the authoritative lifecycle snapshot.
     *
     * @return ElectionLifecycleSnapshot
     */
    public function lifecycle(): ElectionLifecycleSnapshot
    {
        return (new ElectionLifecycleEngineImpl())->compute($this->election);
    }

    /**
     * Get the current lifecycle state.
     *
     * @return ElectionLifecycleState
     */
    public function state(): ElectionLifecycleState
    {
        return $this->lifecycle()->state;
    }

    /**
     * Check if voting is currently allowed.
     *
     * @return bool
     */
    public function canVote(): bool
    {
        return $this->lifecycle()->canVote;
    }

    /**
     * Check if editing is currently allowed.
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return $this->lifecycle()->canEdit;
    }

    /**
     * Get allowed next actions.
     *
     * @return array
     */
    public function allowedActions(): array
    {
        return $this->lifecycle()->allowedActions;
    }

    /**
     * ============================================================================
     * LEGACY PATHS (Deprecated — triggers warnings/blocks)
     * ============================================================================
     */

    /**
     * Get the legacy status field (DEPRECATED).
     *
     * @deprecated Use lifecycle()->state->value instead
     * @return string|null
     */
    public function statusLegacy(): ?string
    {
        $this->guard->checkFieldAccess('status', self::class . '::statusLegacy', $this->mode);

        return $this->election->status;
    }

    /**
     * Get the legacy is_active field (DEPRECATED).
     *
     * @deprecated Use lifecycle()->isActive() instead
     * @return bool
     */
    public function isActiveLegacy(): bool
    {
        $this->guard->checkFieldAccess('is_active', self::class . '::isActiveLegacy', $this->mode);

        return $this->election->is_active ?? false;
    }

    /**
     * Proxy access to underlying election model for other properties.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->election->{$name};
    }
}
