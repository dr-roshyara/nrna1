<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence;

use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;

final class EloquentCommitteeRepository implements CommitteeRepositoryPort
{
    public function save(ConstitutionalCommittee $committee): void
    {
        CommitteeModel::create([
            'id' => $committee->getId()->value(),
            'organisation_id' => $committee->getTenantId()->value(),
            'code' => $committee->getCode(),
            'name' => $committee->getName(),
            'level' => $committee->getAssignment()->governanceLevel,
            'operational_geo' => $committee->getAssignment()->geoUnitId->value(),
            'slug' => \Illuminate\Support\Str::slug($committee->getName()),
        ]);
    }
}
