<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/**
 * COR-4 (PBDIGIT-329, Q-6): a stored Member history could not be decoded, or is not a history the kernel could have
 * decided (Member::fromLog load validation; roadmap V-1, V-14, V-15). An infrastructure failure (rule 8: not a user
 * refusal): the Member is not loaded, so no damaged history is ever resolved into a membership truth (fail closed).
 * The message names the Member and the cause; it never contains provenance or other stored payload.
 */
final class StoredMemberLogInvalid extends \RuntimeException
{
    public static function because(string $tenant, string $member, \Throwable $cause): self
    {
        return new self("Stored history of Member {$member} (organisation {$tenant}) is invalid: " . $cause->getMessage(), 0, $cause);
    }
}
