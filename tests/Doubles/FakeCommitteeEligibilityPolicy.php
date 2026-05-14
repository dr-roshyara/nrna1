<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Domain\Committee\Policies\EligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

final class FakeCommitteeEligibilityPolicy implements EligibilityPolicy
{
    private bool $shouldBeEligible = true;

    private bool $wasEligibilityCalled = false;

    public function isEligible(GeoPathChain $committee, GeoPathChain $member): bool
    {
        $this->wasEligibilityCalled = true;
        return $this->shouldBeEligible;
    }

    public function setEligible(bool $eligible): void
    {
        $this->shouldBeEligible = $eligible;
    }

    public function wasEligibilityCalled(): bool
    {
        return $this->wasEligibilityCalled;
    }

    public function reset(): void
    {
        $this->wasEligibilityCalled = false;
    }
}
