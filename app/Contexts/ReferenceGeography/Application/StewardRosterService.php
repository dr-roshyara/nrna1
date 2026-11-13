<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * Roster writes by people (v3.11 §8; D-84, D-84a). The acting person comes from Identity (ActingPerson), never from
 * the caller. Appointments, ends and matter-reviewer appointments need the platform-owner boundary; a conflict
 * declaration is the acting person's own. Every write runs under the publication lock (the store).
 */
final class StewardRosterService
{
    public function __construct(
        private readonly StewardRosterStore $store,
        private readonly ActingPerson $acting,
        private readonly PlatformOwnerAuthority $platformOwner,
    ) {
    }

    public function appointSteward(PersonReference $steward): int
    {
        $by = $this->platformOwner();

        return $this->store->writeUnderLock(static fn (StewardRoster $r): int => $r->appointSteward($steward, $by));
    }

    public function endSteward(PersonReference $steward): int
    {
        $by = $this->platformOwner();

        return $this->store->writeUnderLock(static fn (StewardRoster $r): int => $r->endSteward($steward, $by));
    }

    public function appointMatterReviewer(PersonReference $reviewer, RevisionId $matter): int
    {
        $by = $this->platformOwner();

        return $this->store->writeUnderLock(static fn (StewardRoster $r): int => $r->appointMatterReviewer($reviewer, $matter, $by));
    }

    /** The acting person's own declaration for one matter (D-84a: for a matter reviewer, the declaration of independence). */
    public function declareConflict(RevisionId $matter, bool $interest): int
    {
        $person = $this->acting->current();

        return $this->store->writeUnderLock(static fn (StewardRoster $r): int => $r->declareConflict($person, $matter, $interest));
    }

    private function platformOwner(): PersonReference
    {
        $person = $this->acting->current();
        if (!$this->platformOwner->isPlatformOwner($person)) {
            throw new PlatformOwnerRequired();
        }

        return $person;
    }
}
