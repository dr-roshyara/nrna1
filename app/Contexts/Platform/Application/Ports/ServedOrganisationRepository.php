<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** The Platform registry (central database): one entry per served organisation (PBDIGIT-345 task 2; D-193 Part 4). */
interface ServedOrganisationRepository
{
    public function find(TenantId $id): ?ServedOrganisation;

    /** @throws \RuntimeException if an entry with this id already exists */
    public function add(ServedOrganisation $organisation): void;

    /** @throws \RuntimeException if no entry with this id exists */
    public function save(ServedOrganisation $organisation): void;
}
