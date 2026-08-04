<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\AdjudicationFailureDeclared;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Registration half of `AdjudicationFailureDeclared`'s published-language status — publication
 * alone is not enough (the WP-3A rule: published language requires BOTH publication and
 * registration).
 *
 * Version window: **v1 only, because v1 is all that exists.** A payload of any other version is
 * rejected loudly rather than guessed at (ADR-T5). The mirror of `AdjudicationExpiredHydrator`,
 * deliberately: a second shape for the same job would drift from the first.
 *
 * **An unsupported version and an incomplete payload are DIFFERENT failures**, and both throw —
 * a hydrator that tolerated either would put a malformed event into a consumer's hands.
 *
 * ⚠️ **Note on `eventType()`:** the `EventHydrator` contract documents it as *"Canonical event
 * type name exactly as in the Event Catalog v1.0."* **`AdjudicationFailureDeclared` is not in that
 * catalog** — nor is `AdjudicationExpired`, which WP-6 shipped and had accepted. The catalog is
 * FROZEN and its disposition is the open escalation **E1** in this slice's plan. **This class
 * follows the WP-6 precedent and touches no catalog.**
 *
 * Traceability: EPIC-004K §10 · **R-88 · R-89** · ADR-T5 · ADR-T3 · WP-3A rule · WP-6 precedent ·
 * plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §6/E1, §13.
 */
final class AdjudicationFailureDeclaredHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'AdjudicationFailureDeclared';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $payload['schema_version'] ?? 1;
        $version = is_numeric($version) ? (int) $version : null;
        if ($version !== 1) {
            throw new InvalidArgumentException(
                'AdjudicationFailureDeclared payload schema version is not supported (window: v1).',
            );
        }

        return new AdjudicationFailureDeclared(
            ChallengeRef::fromString($this->required($payload, 'challengeRef')),
            Reason::fromString($this->required($payload, 'reason')),
            IssuedByAuthority::fromString($this->required($payload, 'declaredByAuthority')),
            new DateTimeImmutable($this->required($payload, 'declaredAt')),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;
        if (!is_scalar($value) || (string) $value === '') {
            throw new InvalidArgumentException(
                "AdjudicationFailureDeclared payload is missing required field '{$key}'.",
            );
        }

        return (string) $value;
    }
}
