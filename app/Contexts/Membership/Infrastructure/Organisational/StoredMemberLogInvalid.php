<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

/**
 * COR-4 (PBDIGIT-329, Q-6): a stored Member history could not be decoded, or is not a history the kernel could have
 * decided (Member::fromLog load validation; roadmap V-1, V-14, V-15). An infrastructure failure (rule 8: not a user
 * refusal): the Member is not loaded, so no damaged history is ever resolved into a membership truth (fail closed).
 *
 * Review condition C-2 (F-2; PO 2026-11-03): the message names the Member, the organisation and the class of the
 * cause only. The cause's own message can echo stored payload (provenance values, dates, ids), so it is never copied
 * into this message; the cause is kept as the previous exception for diagnosis.
 */
final class StoredMemberLogInvalid extends \RuntimeException
{
    public static function because(string $tenant, string $member, \Throwable $cause): self
    {
        return new self("Stored history of Member {$member} (organisation {$tenant}) is invalid (" . $cause::class . ').', 0, $cause);
    }
}
