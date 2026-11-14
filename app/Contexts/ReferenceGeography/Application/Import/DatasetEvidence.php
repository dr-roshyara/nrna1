<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Import;

use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\ValidDate;

/**
 * G-8: one PROPOSED evidence document of a dataset, with everything a reviewer needs to trace it: the issuing
 * authority, the source URL, the publication (title, version or date), when it was retrieved, the file kept and its
 * SHA-256, and the reuse or copyright terms that apply. Proposed, never accepted by being imported: acceptance is the
 * assessing steward's act (D-84), and for the initial dataset the Principal's (instruction 2026-11-14).
 */
final readonly class DatasetEvidence
{
    public function __construct(
        public string $id,
        public string $authority,
        public string $issuedOn,
        public string $url,
        public string $publication,
        public string $retrievedAt,
        public string $file,
        public string $sha256,
        public string $reuseTerms,
    ) {
    }

    /** The evidence version a revision cites: its fingerprint IS the file's SHA-256 (D-227: issuer with its date). */
    public function toEvidenceVersion(): EvidenceVersion
    {
        return new EvidenceVersion($this->id, $this->sha256, $this->authority, ValidDate::fromString($this->issuedOn));
    }
}
