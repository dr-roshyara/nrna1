<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

/**
 * Resolves a locator's login REFERENCE (task 3; delta §3.3: the registry never holds a password).
 * "env:NAME" reads NAME_USERNAME and NAME_PASSWORD from the given environment (the process environment in
 * production: real environment variables, which survive config:cache, unlike .env values read with env()).
 * Any other scheme, or a missing value, is refused; a refusal never contains a secret.
 */
final class EnvironmentDatabaseLogin
{
    /** @param array<string, string> $environment */
    public function __construct(private readonly array $environment)
    {
    }

    /** @return array{username: string, password: string} */
    public function resolve(string $reference): array
    {
        if (preg_match('/^env:([A-Z][A-Z0-9_]{0,63})$/', $reference, $m) !== 1) {
            throw new OrganisationRoutingRefused('Unsupported database login reference (expected env:NAME).');
        }
        $username = $this->environment[$m[1] . '_USERNAME'] ?? '';
        $password = $this->environment[$m[1] . '_PASSWORD'] ?? '';
        if (trim($username) === '' || $password === '') {
            throw new OrganisationRoutingRefused("The database login {$m[1]} is not configured.");
        }

        return ['username' => $username, 'password' => $password];
    }
}
