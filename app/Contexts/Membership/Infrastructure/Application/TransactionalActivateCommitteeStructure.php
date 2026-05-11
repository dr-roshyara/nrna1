<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Application;

use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructureUseCase;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use Illuminate\Support\Facades\DB;

/**
 * Transactional Decorator: Enforces atomicity at infrastructure level.
 *
 * CRITICAL: This ensures that ActivateCommitteeStructure ALWAYS runs inside a database transaction.
 * This moves transaction responsibility from application code to dependency injection.
 *
 * Result: It becomes STRUCTURALLY IMPOSSIBLE to call the use case without transaction boundary.
 */
final class TransactionalActivateCommitteeStructure implements ActivateCommitteeStructureUseCase
{
    public function __construct(
        private ActivateCommitteeStructure $innerUseCase
    ) {}

    public function execute(array $input): CommitteeStructure
    {
        return DB::transaction(fn () => $this->innerUseCase->execute($input));
    }
}
