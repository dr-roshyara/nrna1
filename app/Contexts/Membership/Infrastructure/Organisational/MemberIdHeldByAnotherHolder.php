<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\MemberId;

/**
 * PBDIGIT-264 F-3 (Principal Reviewer disposition 2026-10-09; Slice 1a contract clarification): creating a Member
 * with a MemberId that already belongs to another holder in the tenant is a caller defect, surfaced as an
 * infrastructure failure with no state change. It is not a port outcome.
 *
 * Raised explicitly since step 4a (plan revision R1, 2026-11-02): create() no longer lets the primary-key violation
 * escape, so it can absorb a concurrent identical creation.
 */
final class MemberIdHeldByAnotherHolder extends \RuntimeException
{
    public static function of(MemberId $id): self
    {
        return new self("MemberId '{$id->value()}' already belongs to another holder in this organisation (F-3); nothing was changed.");
    }
}
