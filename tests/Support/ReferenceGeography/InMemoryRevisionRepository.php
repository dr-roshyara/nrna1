<?php

declare(strict_types=1);

namespace Tests\Support\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\RevisionAlreadyPublished;
use App\Contexts\ReferenceGeography\Application\RevisionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionSnapshot;

/**
 * TEST ONLY (G-4f): the revision repository port in memory, with the same contract as the PostgreSQL one: snapshots,
 * optimistic row versions, and a revision with a published version can never be saved again.
 */
final class InMemoryRevisionRepository implements RevisionRepository
{
    /** @var array<string, array{RevisionSnapshot, int}> */
    private array $rows = [];

    /** @var array<string, true> */
    private array $published = [];

    public function add(Revision $revision): void
    {
        $id = $revision->id()->value();
        if (isset($this->rows[$id])) {
            throw new RevisionChangedConcurrently($id);
        }
        $this->rows[$id] = [$revision->snapshot(), 1];
    }

    public function get(RevisionId $id): StoredRevision
    {
        [$snapshot, $version] = $this->rows[$id->value()] ?? throw new \OutOfBoundsException("No revision {$id->value()}.");

        return new StoredRevision(Revision::fromSnapshot($snapshot), $version, isset($this->published[$id->value()]));
    }

    public function save(Revision $revision, int $expectedRowVersion): int
    {
        $id = $revision->id()->value();
        if (isset($this->published[$id])) {
            throw new RevisionAlreadyPublished($id);
        }
        if (($this->rows[$id][1] ?? null) !== $expectedRowVersion) {
            throw new RevisionChangedConcurrently($id);
        }
        $this->rows[$id] = [$revision->snapshot(), $expectedRowVersion + 1];

        return $expectedRowVersion + 1;
    }

    /** Test hook: the ledger published a version of this revision. */
    public function markPublished(RevisionId $id): void
    {
        $this->published[$id->value()] = true;
    }
}
