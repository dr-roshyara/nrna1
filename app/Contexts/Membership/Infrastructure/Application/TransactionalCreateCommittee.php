<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Application;

use App\Contexts\Membership\Application\Committee\CreateCommitteeUseCase;
use App\Contexts\Membership\Application\Committee\InternalCreateCommittee;
use App\Contexts\Membership\Domain\Committee\Committee;
use Illuminate\Support\Facades\DB;

/**
 * TransactionalCreateCommittee
 *
 * Mandatory transactional boundary for committee creation.
 *
 * Ensures:
 * - G-007: Governance epoch snapshot is transactionally stable
 * - G-008: Committee creation is atomic
 * - G-009: Lock acquisition follows global ordering (inside transaction)
 *
 * This decorator is ALWAYS applied via DI. It is STRUCTURALLY IMPOSSIBLE to
 * instantiate InternalCreateCommittee without this wrapper via the container.
 *
 * Pattern: Implements same interface as inner class, injects concrete class
 * (not interface), delegates all work to inner class within transaction boundary.
 *
 * Phase C — Transactional Hardening
 */
final class TransactionalCreateCommittee implements CreateCommitteeUseCase
{
    public function __construct(
        private InternalCreateCommittee $innerUseCase
    ) {}

    /**
     * Execute committee creation inside an atomic transaction with deadlock retry.
     *
     * Lock on active structure is acquired INSIDE the transaction boundary,
     * ensuring FOR UPDATE provides actual protection (not silently released).
     *
     * Deadlock retries (attempts: 3) provide operational resilience without
     * sacrificing correctness.
     */
    public function execute(array $command): Committee
    {
        return DB::transaction(
            fn () => $this->innerUseCase->execute($command),
            attempts: 3
        );
    }
}
