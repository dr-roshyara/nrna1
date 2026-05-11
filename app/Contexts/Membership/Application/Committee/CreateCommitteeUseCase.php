<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;

/**
 * CreateCommitteeUseCase
 *
 * Contract for transactionally-safe committee creation.
 * Guarantees:
 * - G-007: Governance epoch snapshot is transactionally stable
 * - G-008: Committee creation is atomic (all-or-nothing)
 * - G-009: Deadlock-safe lock acquisition ordering
 *
 * Always resolve via container: app(CreateCommitteeUseCase::class)
 * Never instantiate the concrete implementation directly.
 */
interface CreateCommitteeUseCase
{
    /**
     * Create a committee under the current active governance epoch.
     *
     * @param array $command {
     *     @var string $tenantId
     *     @var int $levelIndex
     *     @var string $name
     *     @var string $code
     *     @var string|null $operationalGeoReference
     * }
     *
     * @return Committee — with governance snapshot captured at commit time
     * @throws \DomainException if no active structure, invalid level, or geo validation fails
     * @throws \Exception if persistence fails (transaction auto-rolls back)
     */
    public function execute(array $command): Committee;
}
