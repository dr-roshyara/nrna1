<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\ObjectionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\StoredObjection;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;

/** Objections (G-6b): operational records, outside the knowledge stream (v3.11 §15). */
interface ObjectionRepository
{
    /** @throws \RuntimeException if an objection with this id exists */
    public function add(Objection $objection): void;

    /** @throws \OutOfBoundsException if there is none */
    public function get(ObjectionId $id): StoredObjection;

    /** @throws ObjectionChangedConcurrently if the row changed since it was loaded */
    public function save(Objection $objection, int $expectedRowVersion): int;

    /** @return list<string> every evidence reference already cited by an objection against this claim */
    public function evidenceCitedAgainst(ClaimId $target): array;
}
