<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TEST ONLY (D-239: "tests and development use an explicit test calendar"). Every organisation has the same fixed UTC
 * offset, in minutes (e.g. 345 = Nepal, UTC+5:45). No daylight saving; never a production calendar (Organisation S4).
 */
final class FixedOffsetOrganisationCalendar implements OrganisationCalendar
{
    public function __construct(private readonly int $offsetMinutes)
    {
    }

    public function localDateAt(TenantId $organisation, Instant $t): TimePoint
    {
        $localSeconds = intdiv($t->ordinal() - (($t->ordinal() % 1_000_000 + 1_000_000) % 1_000_000), 1_000_000) + $this->offsetMinutes * 60;

        return TimePoint::of(intdiv($localSeconds - ((($localSeconds % 86_400) + 86_400) % 86_400), 86_400));
    }
}
