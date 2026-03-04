<?php

namespace App\Enums;

/**
 * VotingStep Enum
 *
 * Represents the discrete steps in the voting workflow.
 * Each step corresponds to a database timestamp column on the Code model.
 *
 * Provides type-safe step tracking and prevents string-based step confusion.
 */
enum VotingStep: int
{
    /**
     * WAITING = User has received codes but hasn't started voting
     * Timestamp: voting_started_at = NULL
     */
    case WAITING = 1;

    /**
     * CODE_VERIFIED = User has entered their code (code1)
     * Timestamp: code1_used_at = TIMESTAMP
     */
    case CODE_VERIFIED = 2;

    /**
     * AGREEMENT_ACCEPTED = User has accepted terms/conditions
     * Timestamp: has_agreed_to_vote_at = TIMESTAMP
     */
    case AGREEMENT_ACCEPTED = 3;

    /**
     * VOTE_CAST = User has submitted their vote
     * Timestamp: vote_submitted_at = TIMESTAMP
     */
    case VOTE_CAST = 4;

    /**
     * VERIFIED = User has completed vote verification
     * Timestamp: vote_completed_at = TIMESTAMP
     */
    case VERIFIED = 5;

    /**
     * Get the human-readable label for this step
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::WAITING => 'Waiting to Vote',
            self::CODE_VERIFIED => 'Code Verified',
            self::AGREEMENT_ACCEPTED => 'Agreement Accepted',
            self::VOTE_CAST => 'Vote Cast',
            self::VERIFIED => 'Vote Verified',
        };
    }

    /**
     * Get the database timestamp column name for this step
     *
     * Used to check when a user reached this step
     *
     * @return string
     */
    public function timestampColumn(): string
    {
        return match($this) {
            self::WAITING => 'voting_started_at',
            self::CODE_VERIFIED => 'code1_used_at',
            self::AGREEMENT_ACCEPTED => 'has_agreed_to_vote_at',
            self::VOTE_CAST => 'vote_submitted_at',
            self::VERIFIED => 'vote_completed_at',
        };
    }

    /**
     * Get the previous step in the workflow
     *
     * @return self|null
     */
    public function previous(): ?self
    {
        return match($this) {
            self::WAITING => null,
            self::CODE_VERIFIED => self::WAITING,
            self::AGREEMENT_ACCEPTED => self::CODE_VERIFIED,
            self::VOTE_CAST => self::AGREEMENT_ACCEPTED,
            self::VERIFIED => self::VOTE_CAST,
        };
    }

    /**
     * Get the next step in the workflow
     *
     * @return self|null
     */
    public function next(): ?self
    {
        return match($this) {
            self::WAITING => self::CODE_VERIFIED,
            self::CODE_VERIFIED => self::AGREEMENT_ACCEPTED,
            self::AGREEMENT_ACCEPTED => self::VOTE_CAST,
            self::VOTE_CAST => self::VERIFIED,
            self::VERIFIED => null,
        };
    }

    /**
     * Check if this step is before another step in the workflow
     *
     * @param self $other
     * @return bool
     */
    public function isBefore(self $other): bool
    {
        return $this->value < $other->value;
    }

    /**
     * Check if this step is after another step in the workflow
     *
     * @param self $other
     * @return bool
     */
    public function isAfter(self $other): bool
    {
        return $this->value > $other->value;
    }

    /**
     * Determine the current step for a voter based on Code model timestamps
     *
     * Checks which timestamp columns are populated and returns the most advanced step
     *
     * @param \App\Models\Code $code
     * @return self
     */
    public static function fromCode($code): self
    {
        // Work backwards from the most advanced step
        if ($code->vote_completed_at !== null) {
            return self::VERIFIED;
        }

        if ($code->vote_submitted_at !== null) {
            return self::VOTE_CAST;
        }

        if ($code->has_agreed_to_vote_at !== null) {
            return self::AGREEMENT_ACCEPTED;
        }

        if ($code->code1_used_at !== null) {
            return self::CODE_VERIFIED;
        }

        // Default: code exists but hasn't started voting
        return self::WAITING;
    }

    /**
     * Get all steps in order
     *
     * @return array<self>
     */
    public static function allOrdered(): array
    {
        return [
            self::WAITING,
            self::CODE_VERIFIED,
            self::AGREEMENT_ACCEPTED,
            self::VOTE_CAST,
            self::VERIFIED,
        ];
    }

    /**
     * Get steps up to and including a given step
     *
     * Useful for progress indicators
     *
     * @param self $step
     * @return array<self>
     */
    public static function through(self $step): array
    {
        $steps = [];
        foreach (self::allOrdered() as $s) {
            $steps[] = $s;
            if ($s->value === $step->value) {
                break;
            }
        }
        return $steps;
    }

    /**
     * Calculate progress percentage
     *
     * @param self $currentStep
     * @return int (0-100)
     */
    public static function progressPercentage(self $currentStep): int
    {
        $allSteps = count(self::allOrdered());
        $completedSteps = count(self::through($currentStep));
        return (int) (($completedSteps / $allSteps) * 100);
    }
}
