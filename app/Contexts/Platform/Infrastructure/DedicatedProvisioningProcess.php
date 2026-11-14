<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 5b (task 4 limit): marks THIS process as the dedicated provisioning process of ONE organisation.
 * Only `platform:prepare-organisation-database` enters it (architecture test), once, for the organisation it was
 * started for; the preparer refuses to run anywhere else. A request process never enters it, so the migrator's
 * temporary switch of the default connection can never happen while a request is served.
 */
final class DedicatedProvisioningProcess
{
    private static ?string $organisation = null;

    public static function enter(TenantId $organisation): void
    {
        if (self::$organisation !== null) {
            throw new OrganisationProvisioningRefused('This process is already the dedicated provisioning process of an organisation.');
        }
        self::$organisation = $organisation->value();
    }

    public static function requireFor(TenantId $organisation): void
    {
        if (self::$organisation === null) {
            throw new OrganisationProvisioningRefused('An organisation database is prepared only in its dedicated provisioning process (platform:prepare-organisation-database).');
        }
        if (self::$organisation !== $organisation->value()) {
            throw new OrganisationProvisioningRefused('This provisioning process is dedicated to another organisation.');
        }
    }
}
