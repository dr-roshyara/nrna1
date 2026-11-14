<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Application\Ports\BindingsUnavailable;
use App\Contexts\Platform\Application\Ports\IdentityBindings;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Entering an organisation over HTTP (PBDIGIT-345 task 6; tactical design E-1; D-219 (2) shared hostname).
 *
 * `select` turns a signed-in person's choice into a SELECTION: a request to work in that organisation, never proof.
 * `enter` runs on EVERY organisation request and checks that selection again in full:
 *   signed in → selection present and well-formed → no conflicting id carried by the request (ISO-3) → not legacy
 *   (D-160, L-2: the legacy and registry paths never share an organisation) → CURRENTLY bound (ISO-3) → routable
 *   through the registry (ISO-2: the locator comes from the registry only).
 * Nothing is cached between requests, so a binding revoked after selection refuses the very next request (T-15).
 */
final class EnterOrganisation
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    public function __construct(
        private readonly IdentityBindings $bindings,
        private readonly LegacyOrganisationRegister $legacy,
        private readonly ResolutionService $resolution,
    ) {
    }

    /**
     * @throws OrganisationEntryRefused
     * @throws BindingsUnavailable
     */
    public function select(?string $accountId, string $requested): TenantId
    {
        $account = self::signedIn($accountId);
        $id = self::wellFormed($requested);
        $this->check($account, $id);

        return $id;
    }

    /**
     * @param list<string> $carriedIds organisation ids the request itself carries (route, body, header)
     * @throws OrganisationEntryRefused
     * @throws BindingsUnavailable
     */
    public function enter(?string $accountId, ?string $selection, array $carriedIds): ResolvedOrganisation
    {
        $account = self::signedIn($accountId);
        if ($selection === null || $selection === '') {
            throw new OrganisationEntryRefused(EntryRefusal::NoSelection);
        }
        $id = self::wellFormed($selection);
        foreach ($carriedIds as $carried) {
            if (strtolower(trim($carried)) !== $id->value()) {
                throw new OrganisationEntryRefused(EntryRefusal::ConflictingId);
            }
        }

        return $this->check($account, $id);
    }

    private function check(string $account, TenantId $id): ResolvedOrganisation
    {
        if ($this->legacy->contains($id)) {
            throw new OrganisationEntryRefused(EntryRefusal::LegacyOrganisation);
        }
        if (!$this->bindings->isBound($account, $id)) {
            throw new OrganisationEntryRefused(EntryRefusal::NotBound);
        }
        try {
            return $this->resolution->resolve($id->value());
        } catch (OrganisationNotRoutable $e) {
            throw new OrganisationEntryRefused(EntryRefusal::fromRouting($e->reason));
        }
    }

    private static function signedIn(?string $accountId): string
    {
        if ($accountId === null || $accountId === '') {
            throw new OrganisationEntryRefused(EntryRefusal::NotSignedIn);
        }

        return $accountId;
    }

    private static function wellFormed(string $value): TenantId
    {
        $id = strtolower(trim($value));
        if (preg_match(self::UUID, $id) !== 1) {
            throw new OrganisationEntryRefused(EntryRefusal::MalformedId);
        }

        return TenantId::fromString($id);
    }
}
