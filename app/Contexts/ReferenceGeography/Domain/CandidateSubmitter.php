<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Who submitted a Candidate (strategic baseline: "Submit: Organisations (Candidate); steward (imports)").
 * An organisation is named only by its opaque id: Geography holds no organisation person reference and reads nothing
 * of Organisation (v3.11 §16; D-147 defers the integration through which organisations will submit).
 */
final readonly class CandidateSubmitter
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    private function __construct(public string $kind, public string $reference)
    {
    }

    public static function organisation(string $organisationId): self
    {
        if (preg_match(self::UUID, $organisationId) !== 1) {
            throw new \InvalidArgumentException('An organisation submitter is named by its organisation id (a lower-case UUID).');
        }

        return new self('organisation', $organisationId);
    }

    public static function steward(PersonReference $steward): self
    {
        return new self('steward', $steward->value());
    }

    /** Rebuild from storage. */
    public static function of(string $kind, string $reference): self
    {
        return match ($kind) {
            'organisation' => self::organisation($reference),
            'steward' => self::steward(PersonReference::fromString($reference)),
            default => throw new \InvalidArgumentException("Unknown submitter kind '{$kind}'."),
        };
    }
}
