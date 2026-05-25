<?php

namespace Tests\Support\Fixtures;

use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\VoterSourceStrategy;

/**
 * EligibilityFixtureBuilder — Express eligibility test intent, not ORM mechanics
 *
 * Purpose: Build minimal eligibility context for policy decision tests
 * without coupling tests to persistence layer.
 *
 * Usage:
 *   $context = EligibilityFixtureBuilder::electionOnly()
 *       ->activeUser($userId)
 *       ->build();
 */
final class EligibilityFixtureBuilder
{
    private string $userId = 'test-user-id';
    private string $organisationId = 'test-org-id';
    private VoterSourceStrategy $mode;
    private bool $isActive = true;
    private bool $isDeleted = false;
    private ?string $membershipStatus = null;
    private ?string $feesStatus = null;

    private function __construct(VoterSourceStrategy $mode)
    {
        $this->mode = $mode;
    }

    public static function electionOnly(): self
    {
        return new self(VoterSourceStrategy::ImportedVoterRegistry);
    }

    public static function fullMembership(): self
    {
        return new self(VoterSourceStrategy::MembershipRegistry);
    }

    public function userId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function organisationId(string $organisationId): self
    {
        $this->organisationId = $organisationId;
        return $this;
    }

    public function active(): self
    {
        $this->isActive = true;
        return $this;
    }

    public function inactive(): self
    {
        $this->isActive = false;
        return $this;
    }

    public function deleted(): self
    {
        $this->isDeleted = true;
        return $this;
    }

    public function membershipStatus(string $status): self
    {
        $this->membershipStatus = $status;
        return $this;
    }

    public function feesStatus(string $status): self
    {
        $this->feesStatus = $status;
        return $this;
    }

    public function build(): EligibilityContext
    {
        return new EligibilityContext(
            userId: $this->userId,
            organisationId: $this->organisationId,
            mode: $this->mode,
            isActive: $this->isActive,
            isDeleted: $this->isDeleted,
            membershipStatus: $this->membershipStatus,
            feesStatus: $this->feesStatus,
        );
    }
}
