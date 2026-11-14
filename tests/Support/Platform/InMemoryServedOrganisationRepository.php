<?php

declare(strict_types=1);

namespace Tests\Support\Platform;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TEST ONLY (PBDIGIT-345 task 5): the registry port in memory, through snapshots (as storage does). `failNextSave`
 * simulates a crash: the save is lost, exactly as if the process died before committing it.
 */
final class InMemoryServedOrganisationRepository implements ServedOrganisationRepository
{
    /** @var array<string, \App\Contexts\Platform\Domain\ServedOrganisationSnapshot> */
    private array $rows = [];
    public bool $failNextSave = false;

    public function find(TenantId $id): ?ServedOrganisation
    {
        return isset($this->rows[$id->value()]) ? ServedOrganisation::fromSnapshot($this->rows[$id->value()]) : null;
    }

    public function add(ServedOrganisation $organisation): void
    {
        if (isset($this->rows[$organisation->id()->value()])) {
            throw new \RuntimeException('duplicate registry entry');
        }
        $this->store($organisation);
    }

    public function save(ServedOrganisation $organisation): void
    {
        $this->store($organisation);
    }

    private function store(ServedOrganisation $organisation): void
    {
        if ($this->failNextSave) {
            $this->failNextSave = false;
            throw new \RuntimeException('simulated crash before commit');
        }
        $this->rows[$organisation->id()->value()] = $organisation->snapshot();
    }
}
