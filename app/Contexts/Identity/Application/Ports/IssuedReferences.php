<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §4 IssuedReference (P-I2 = L1: stored, Principal 2026-11-15). Central (D-164).
 */
interface IssuedReferences
{
    /**
     * The organisation's reference for its local entry: issued on the first call, the same on every later call
     * (INV-REF-2), also under concurrent calls. Issued to this organisation only (INV-REF-1).
     *
     * @throws IdentityUnavailable
     */
    public function issue(TenantId $organisation, LocalEntryRef $entry): OrganisationPersonRef;

    /** @throws IdentityUnavailable */
    public function isIssuedTo(OrganisationPersonRef $ref, TenantId $organisation): bool;
}
