<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Repositories;

use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationNotFound;
use App\Contexts\Adjudication\Domain\Repository\DeterminationRepository;
use App\Contexts\Adjudication\Infrastructure\Models\DeterminationModel;
use App\Contexts\Adjudication\Infrastructure\Persistence\DeterminationMapper;

/**
 * Eloquent adapter for DeterminationRepository. Tenant scoping is applied by the
 * BelongsToTenant global scope (current TenantContext) — the frozen repository
 * contract is tenant-agnostic. Identity comes from the injected generator.
 */
final class EloquentDeterminationRepository implements DeterminationRepository
{
    public function __construct(
        private readonly DeterminationModel $model,
        private readonly DeterminationMapper $mapper,
        private readonly IdentityGenerator $identities,
    ) {
    }

    public function nextIdentity(): DeterminationId
    {
        return DeterminationId::fromString($this->identities->next());
    }

    public function save(Determination $determination): void
    {
        $this->model->newQuery()->updateOrCreate(
            ['id' => $determination->id()->toString()],
            $this->mapper->toRow($determination),
        );
    }

    public function get(DeterminationId $id): Determination
    {
        $row = $this->model->newQuery()->whereKey($id->toString())->first()
            ?? throw DeterminationNotFound::withId($id);

        return $this->mapper->toAggregate($row);
    }

    public function find(DeterminationId $id): ?Determination
    {
        $row = $this->model->newQuery()->whereKey($id->toString())->first();

        return $row === null ? null : $this->mapper->toAggregate($row);
    }

    public function findByChallengeRef(ChallengeRef $challengeRef): ?Determination
    {
        $row = $this->model->newQuery()
            ->where('challenge_ref', $challengeRef->toString())
            ->first();

        return $row === null ? null : $this->mapper->toAggregate($row);
    }
}
