<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A snapshot of the authority check behind a decision (RA-7; review R12).
 *
 * Carried on the fact and never interpreted by Membership. Competence is
 * Governance's (DS-4); `competenceVerified = false` marks a technical
 * application-role check only.
 */
final readonly class AuthorityEvidence
{
    private function __construct(
        private string $actor,
        private string $capacity,
        private string $ruleRef,
        private string $policyVersion,
        private bool $result,
        private Instant $checkedAt,
        private bool $competenceVerified,
    ) {
        foreach (['actor' => $actor, 'capacity' => $capacity, 'ruleRef' => $ruleRef, 'policyVersion' => $policyVersion] as $field => $value) {
            if (trim($value) === '') {
                throw new \InvalidArgumentException("AuthorityEvidence.$field cannot be empty.");
            }
        }
    }

    public static function of(string $actor, string $capacity, string $ruleRef, string $policyVersion, bool $result, Instant $checkedAt, bool $competenceVerified): self
    {
        return new self($actor, $capacity, $ruleRef, $policyVersion, $result, $checkedAt, $competenceVerified);
    }

    public function actor(): string
    {
        return $this->actor;
    }

    public function capacity(): string
    {
        return $this->capacity;
    }

    public function ruleRef(): string
    {
        return $this->ruleRef;
    }

    public function policyVersion(): string
    {
        return $this->policyVersion;
    }

    public function result(): bool
    {
        return $this->result;
    }

    public function checkedAt(): Instant
    {
        return $this->checkedAt;
    }

    public function competenceVerified(): bool
    {
        return $this->competenceVerified;
    }
}
