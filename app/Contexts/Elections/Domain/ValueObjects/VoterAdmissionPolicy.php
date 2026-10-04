<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * VoterAdmissionPolicy — WHAT a registered voter of this election must satisfy
 * before being admitted (PBDIGIT-213, ADR_20261004_1549 §2.2 inv. 5).
 *
 * - Election-owned; the default and any missing/empty value mean NO additional
 *   requirement (C2-D1: OFF).
 * - Semantics: ALL-of. A later any-of clause is added inside this value object (G7).
 * - Persistence form {"requires": [...]} is deterministic (sorted, unique), so equal
 *   policies serialise identically.
 *
 * This policy never creates or changes an ElectionMembership (ADR §4): it describes
 * a post-registration evaluation, nothing more.
 */
final readonly class VoterAdmissionPolicy
{
    /** @param list<AdmissionRequirement> $requirements sorted, unique */
    private function __construct(private array $requirements) {}

    public static function none(): self
    {
        return new self([]);
    }

    public static function requiring(AdmissionRequirement ...$requirements): self
    {
        $unique = [];
        foreach ($requirements as $requirement) {
            $unique[$requirement->value] = $requirement;
        }
        ksort($unique);

        return new self(array_values($unique));
    }

    /**
     * @param array{requires?: list<string>}|null $persisted
     * @throws InvalidArgumentException for an unknown requirement code
     */
    public static function fromPersistence(?array $persisted): self
    {
        $codes = $persisted['requires'] ?? [];

        if (! is_array($codes)) {
            throw new InvalidArgumentException('Voter admission policy "requires" must be a list.');
        }

        $requirements = array_map(
            static fn (mixed $code) => AdmissionRequirement::tryFrom((string) $code)
                ?? throw new InvalidArgumentException("Unknown admission requirement: {$code}"),
            $codes,
        );

        return self::requiring(...$requirements);
    }

    /** @return array{requires: list<string>} */
    public function toPersistence(): array
    {
        return ['requires' => array_map(static fn (AdmissionRequirement $r) => $r->value, $this->requirements)];
    }

    /** @return list<AdmissionRequirement> */
    public function requirements(): array
    {
        return $this->requirements;
    }

    public function requires(AdmissionRequirement $requirement): bool
    {
        return in_array($requirement, $this->requirements, true);
    }

    public function isEmpty(): bool
    {
        return $this->requirements === [];
    }

    public function equals(self $other): bool
    {
        return $this->toPersistence() === $other->toPersistence();
    }

    public function isSatisfiedBy(AdmissionEvidence $evidence): AdmissionDecision
    {
        return AdmissionDecision::fromMissing(array_values(array_filter(
            $this->requirements,
            static fn (AdmissionRequirement $r) => ! $evidence->satisfies($r),
        )));
    }
}
