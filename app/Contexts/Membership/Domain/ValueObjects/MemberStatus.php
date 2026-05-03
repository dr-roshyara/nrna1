<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * MemberStatus Value Object
 *
 * Represents the lifecycle status of a member.
 * Immutable and self-validating with state transition rules.
 */
final class MemberStatus
{
    private const DRAFT = 'draft';
    private const PENDING = 'pending';
    private const APPROVED = 'approved';
    private const REJECTED = 'rejected';
    private const ACTIVE = 'active';
    private const SUSPENDED = 'suspended';
    private const INACTIVE = 'inactive';
    private const ARCHIVED = 'archived';

    private const VALID_STATUSES = [
        self::DRAFT,
        self::PENDING,
        self::APPROVED,
        self::REJECTED,
        self::ACTIVE,
        self::SUSPENDED,
        self::INACTIVE,
        self::ARCHIVED,
    ];

    private string $value;

    private function __construct(string $status)
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                "Invalid member status: {$status}. " .
                "Valid statuses: " . implode(', ', self::VALID_STATUSES)
            );
        }

        $this->value = $status;
    }

    // ==========================================
    // Static Factory Methods
    // ==========================================

    public static function draft(): self
    {
        return new self(self::DRAFT);
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function approved(): self
    {
        return new self(self::APPROVED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function suspended(): self
    {
        return new self(self::SUSPENDED);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function archived(): self
    {
        return new self(self::ARCHIVED);
    }

    public static function fromString(string $status): self
    {
        return new self($status);
    }

    // ==========================================
    // Value Object Methods
    // ==========================================

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(MemberStatus $other): bool
    {
        return $this->value === $other->value;
    }

    // ==========================================
    // Status Check Methods
    // ==========================================

    public function isDraft(): bool
    {
        return $this->value === self::DRAFT;
    }

    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->value === self::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->value === self::REJECTED;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->value === self::SUSPENDED;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }

    public function isArchived(): bool
    {
        return $this->value === self::ARCHIVED;
    }

    // ==========================================
    // Business Rule Methods (Political Party)
    // ==========================================

    /**
     * Can this status be approved? (Business rule)
     * Only pending members can be approved by committee
     */
    public function canBeApproved(): bool
    {
        return $this->isPending();
    }

    /**
     * Can this status be activated? (After payment/verification)
     * Only approved members can be activated
     */
    public function canBeActivated(): bool
    {
        return $this->isApproved();
    }

    /**
     * Is this status eligible for voting?
     * Only active members can vote in elections
     */
    public function canVote(): bool
    {
        return $this->isActive();
    }

    /**
     * Is this status eligible for committee roles?
     * Approved and active members can hold committee positions
     */
    public function canHoldCommitteeRole(): bool
    {
        return in_array($this->value, [self::APPROVED, self::ACTIVE], true);
    }

    /**
     * Can receive digital membership card?
     * Active members get digital cards
     */
    public function canReceiveDigitalCard(): bool
    {
        return $this->isActive();
    }

    /**
     * Can participate in forums/discussions?
     * Approved and active members can participate
     */
    public function canParticipateInForums(): bool
    {
        return in_array($this->value, [self::APPROVED, self::ACTIVE], true);
    }

    // ==========================================
    // State Transition Validation
    // ==========================================

    /**
     * State transition validation
     *
     * @param MemberStatus $newStatus
     * @return bool
     */
    public function canTransitionTo(MemberStatus $newStatus): bool
    {
        $transitions = [
            self::DRAFT => [self::PENDING, self::ARCHIVED],
            self::PENDING => [self::APPROVED, self::REJECTED, self::ARCHIVED],
            self::APPROVED => [self::ACTIVE, self::ARCHIVED],
            self::REJECTED => [self::ARCHIVED], // Rejected members can only be archived
            self::ACTIVE => [self::SUSPENDED, self::INACTIVE, self::ARCHIVED],
            self::SUSPENDED => [self::ACTIVE, self::INACTIVE, self::ARCHIVED],
            self::INACTIVE => [self::ACTIVE, self::ARCHIVED],
            self::ARCHIVED => [], // Terminal state
        ];

        return in_array($newStatus->value, $transitions[$this->value] ?? [], true);
    }

    // ==========================================
    // Transition Methods with Validation
    // ==========================================

    /**
     * Approve a pending member
     * @throws InvalidArgumentException if status cannot be approved
     */
    public function approve(): self
    {
        if (!$this->canBeApproved()) {
            throw new InvalidArgumentException(
                "Cannot approve a member with status: {$this->value}. " .
                "Only 'pending' members can be approved."
            );
        }

        return self::approved();
    }

    /**
     * Activate an approved member
     * @throws InvalidArgumentException if status cannot be activated
     */
    public function activate(): self
    {
        if (!$this->canBeActivated()) {
            throw new InvalidArgumentException(
                "Cannot activate a member with status: {$this->value}. " .
                "Only 'approved' members can be activated."
            );
        }

        return self::active();
    }

    /**
     * Suspend an active member
     * @throws InvalidArgumentException if status cannot be suspended
     */
    public function suspend(): self
    {
        if (!$this->isActive()) {
            throw new InvalidArgumentException(
                "Cannot suspend a member with status: {$this->value}. " .
                "Only 'active' members can be suspended."
            );
        }

        return self::suspended();
    }

    /**
     * Archive a member (terminal state)
     * @throws InvalidArgumentException if invalid transition
     */
    public function archive(): self
    {
        if (!$this->canTransitionTo(self::archived())) {
            throw new InvalidArgumentException(
                "Cannot archive a member with status: {$this->value}"
            );
        }

        return self::archived();
    }
}
