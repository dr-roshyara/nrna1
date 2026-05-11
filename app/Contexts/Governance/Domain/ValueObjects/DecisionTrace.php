<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use DomainException;

final readonly class DecisionTrace
{
    /** @param string[] $evaluatedRules */
    /** @param string[] $matchedClauses */
    /** @param string[] $rejectedConstraints */
    private function __construct(
        private array $evaluatedRules,
        private array $matchedClauses,
        private array $rejectedConstraints,
        private AuthorityPath $authorityPath,
        private MemberId $adjudicatedBy,
        private DateTimeImmutable $adjudicatedAt,
    ) {}

    /**
     * @param string[] $evaluatedRules
     * @param string[] $matchedClauses
     * @param string[] $rejectedConstraints
     */
    public static function from(
        array $evaluatedRules,
        array $matchedClauses,
        array $rejectedConstraints,
        AuthorityPath $authorityPath,
        MemberId $adjudicatedBy,
        DateTimeImmutable $adjudicatedAt,
    ): self {
        if (empty($evaluatedRules)) {
            throw new DomainException('At least one rule must be evaluated');
        }

        return new self(
            $evaluatedRules,
            $matchedClauses,
            $rejectedConstraints,
            $authorityPath,
            $adjudicatedBy,
            $adjudicatedAt
        );
    }

    /** @return string[] */
    public function evaluatedRules(): array
    {
        return $this->evaluatedRules;
    }

    /** @return string[] */
    public function matchedClauses(): array
    {
        return $this->matchedClauses;
    }

    /** @return string[] */
    public function rejectedConstraints(): array
    {
        return $this->rejectedConstraints;
    }

    public function authorityPath(): AuthorityPath
    {
        return $this->authorityPath;
    }

    public function adjudicatedBy(): MemberId
    {
        return $this->adjudicatedBy;
    }

    public function adjudicatedAt(): DateTimeImmutable
    {
        return $this->adjudicatedAt;
    }

    public function equals(self $other): bool
    {
        return $this->evaluatedRules === $other->evaluatedRules
            && $this->matchedClauses === $other->matchedClauses
            && $this->rejectedConstraints === $other->rejectedConstraints
            && $this->authorityPath->equals($other->authorityPath)
            && $this->adjudicatedBy->equals($other->adjudicatedBy)
            && $this->adjudicatedAt == $other->adjudicatedAt;
    }
}
