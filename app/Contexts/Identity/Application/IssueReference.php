<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §12 C-I1: `IssueReference(O, E) → R`. Called in-process by the organisation's own application layer, in
 * its organisation context (ISO-1), when it records a person. On failure the entry waits: no R is ever invented.
 */
final class IssueReference
{
    public function __construct(private readonly IssuedReferences $references)
    {
    }

    /** @throws IdentityUnavailable */
    public function issue(TenantId $organisation, LocalEntryRef $entry): OrganisationPersonRef
    {
        return $this->references->issue($organisation, $entry);
    }
}
