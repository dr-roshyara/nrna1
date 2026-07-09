<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Repositories;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Exception\ChallengeNotFound;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Contestation\Infrastructure\Models\ChallengeModel;
use App\Contexts\Contestation\Infrastructure\Persistence\ChallengeMapper;
use Illuminate\Support\Str;

/**
 * Single-source Eloquent persistence of the Challenge aggregate (Contestation OWNS it —
 * no ACL, no multi-source reconstruction; contrast PB-004). It reconstructs aggregates and
 * makes NO business decisions. Tenant scope is applied by the model's BelongsToTenant
 * trait (queries here ask a purely intra-tenant question). The mapper is the sole
 * translation point.
 */
final class EloquentChallengeRepository implements ChallengeRepository
{
    public function __construct(
        private readonly ChallengeModel $model,
        private readonly ChallengeMapper $mapper,
    ) {
    }

    public function nextIdentity(): ChallengeId
    {
        return ChallengeId::fromString((string) Str::uuid());
    }

    public function save(Challenge $challenge): void
    {
        $this->model->newQuery()->updateOrCreate(
            ['id' => $challenge->id()->toString()],
            $this->mapper->toRow($challenge),
        );
    }

    public function get(ChallengeId $id): Challenge
    {
        return $this->find($id) ?? throw ChallengeNotFound::withId($id);
    }

    public function find(ChallengeId $id): ?Challenge
    {
        $model = $this->model->newQuery()->whereKey($id->toString())->first();

        return $model instanceof ChallengeModel ? $this->mapper->toAggregate($model) : null;
    }

    public function findByDeterminationId(DeterminationId $id): ?Challenge
    {
        $model = $this->model->newQuery()->where('determination_id', $id->toString())->first();

        return $model instanceof ChallengeModel ? $this->mapper->toAggregate($model) : null;
    }
}
