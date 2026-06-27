<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

/**
 * CommitteeMembersResponseDTO
 *
 * API contract for committee members list response.
 *
 * This DTO is the stable API boundary for UI consumption.
 * Changes to this DTO should be version-managed for API stability.
 */
final readonly class CommitteeMembersResponseDTO
{
    public function __construct(
        public string $committeeId,
        public array $members,
    ) {}

    public static function fromArray(string $committeeId, array $members): self
    {
        return new self(
            committeeId: $committeeId,
            members: $members
        );
    }

    public function toArray(): array
    {
        return [
            'committeeId' => $this->committeeId,
            'members' => $this->members,
        ];
    }
}
