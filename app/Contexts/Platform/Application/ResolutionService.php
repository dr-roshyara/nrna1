<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The ONLY way to learn where an organisation's data lives (PBDIGIT-345 task 2; T-2; ISO-2; D-193 Part 4).
 * An organisation id goes in; the locator comes only from the registry, never from a request. Read at every call:
 * nothing is cached, so a registry change (a failed migration) takes effect at the next request or job (Q-5).
 */
final class ResolutionService
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    public function __construct(private readonly ServedOrganisationRepository $registry)
    {
    }

    /** @throws OrganisationNotRoutable */
    public function resolve(string $organisationId): ResolvedOrganisation
    {
        $id = strtolower(trim($organisationId));
        if (preg_match(self::UUID, $id) !== 1) {
            throw new OrganisationNotRoutable(RoutingRefusal::Unknown);   // never queried: registry ids are UUIDs
        }
        $organisation = $this->registry->find(TenantId::fromString($id));
        if ($organisation === null) {
            throw new OrganisationNotRoutable(RoutingRefusal::Unknown);
        }
        if ($organisation->status() !== PlatformStatus::Operational) {
            throw new OrganisationNotRoutable(RoutingRefusal::NotOperational);
        }
        if (!$organisation->isRoutable() || $organisation->locator() === null) {
            throw new OrganisationNotRoutable(RoutingRefusal::SchemaNotCurrent);
        }

        return new ResolvedOrganisation($organisation->id(), $organisation->locator());
    }
}
