<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\RevisionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * Where revisions are kept (completion §4; G-4 interpretation §2): the aggregate is saved whole, in one transaction,
 * with optimistic concurrency by row version; it is rebuilt only through Revision::fromSnapshot.
 */
interface RevisionRepository extends RevisionCatalogue
{
    /** @throws RevisionChangedConcurrently if a revision with this id already exists */
    public function add(Revision $revision): void;

    public function get(RevisionId $id): StoredRevision;

    /**
     * @return int the new row version
     * @throws RevisionChangedConcurrently if the stored row version is no longer $expectedRowVersion (nothing written)
     */
    public function save(Revision $revision, int $expectedRowVersion): int;
}
