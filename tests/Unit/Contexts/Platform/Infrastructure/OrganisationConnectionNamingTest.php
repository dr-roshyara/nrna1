<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationRoutingRefused;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 3 (C-1, D-222), database-free:
 * - one connection NAME per organisation, derived from its id only (never a re-pointed shared name);
 * - a database login is a REFERENCE: "env:NAME" reads NAME_USERNAME and NAME_PASSWORD from the process environment;
 *   anything else, or a missing value, is refused, and the refusal never contains a secret.
 */
final class OrganisationConnectionNamingTest extends TestCase
{
    public function test_the_connection_name_is_derived_from_the_organisation_id_only(): void
    {
        $a = OrganisationConnectionFactory::connectionName(TenantId::fromString('0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60'));

        $this->assertSame('org_0b9e7a526c1d4f7e9a3b2d5c8e1f4a60', $a);
        $this->assertNotSame($a, OrganisationConnectionFactory::connectionName(TenantId::fromString('1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71')));
        $this->assertNotContains($a, ['tenant', 'pgsql', 'mysql', 'default'], 'never a shared, re-pointable name');
    }

    public function test_an_environment_reference_resolves_to_its_username_and_password(): void
    {
        $login = (new EnvironmentDatabaseLogin(['ORG_A_DB_USERNAME' => 'org_a_user', 'ORG_A_DB_PASSWORD' => 's3cret']))->resolve('env:ORG_A_DB');

        $this->assertSame(['username' => 'org_a_user', 'password' => 's3cret'], $login);
    }

    /** @return array<string, array{string, array<string, string>}> */
    public static function refusals(): array
    {
        return [
            'unknown scheme' => ['vault:org/a', ['ORG_A_DB_USERNAME' => 'u', 'ORG_A_DB_PASSWORD' => 's3cret']],
            'missing username' => ['env:ORG_A_DB', ['ORG_A_DB_PASSWORD' => 's3cret']],
            'missing password' => ['env:ORG_A_DB', ['ORG_A_DB_USERNAME' => 'u']],
            'malformed name' => ['env:org a; rm', ['ORG_A_DB_USERNAME' => 'u', 'ORG_A_DB_PASSWORD' => 's3cret']],
        ];
    }

    /** @param array<string, string> $env */
    #[DataProvider('refusals')]
    public function test_an_unusable_reference_is_refused_without_revealing_a_secret(string $reference, array $env): void
    {
        try {
            (new EnvironmentDatabaseLogin($env))->resolve($reference);
            $this->fail('an unusable login reference was accepted');
        } catch (OrganisationRoutingRefused $e) {
            $this->assertStringNotContainsString('s3cret', $e->getMessage());
        }
    }
}
