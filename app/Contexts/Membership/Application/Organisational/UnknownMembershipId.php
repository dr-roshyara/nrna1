<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/** C2 spec §6: Organisation only knows ids Membership gave it, so an unknown membershipId is a caller error. */
final class UnknownMembershipId extends \InvalidArgumentException
{
}
