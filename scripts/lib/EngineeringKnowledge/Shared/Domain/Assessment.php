<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Domain;

use InvalidArgumentException;

/**
 * The outcome of one capability's assessment: a verdict plus the evidence supporting it.
 *
 * SHARED across Engineering Knowledge capabilities, because the shape is governed and
 * capability-independent:
 *   AP-1  a verdict is an OUTPUT, never a decision — this object confers no authority
 *   AP-8  only the closed verdict vocabulary crosses a boundary
 *   DP-5  an assessment states what was checked — hence evidence is required
 *
 * ⛔ Shared MUST NOT depend on any capability. The subject is therefore carried as an
 *    OPAQUE STRING, never as a capability type.
 *    (Discovered by the type system when this class was extracted while still typed
 *    against IdentifierIntegrity's `Identifier`. Recorded because it is the reason
 *    the signature looks like this.)
 *
 * Named `Assessment` after CBC-1's governed vocabulary — "Knowledge Assessment",
 * "derived assessments" — rather than after a technical action.
 */
final readonly class Assessment
{
    private function __construct(
        private Verdict $verdict,
        private string $evidence,
        private ?string $subject = null,
    ) {
    }

    public static function of(Verdict $verdict, string $evidence, ?string $subject = null): self
    {
        if (! $verdict->isEmittable()) {
            throw new InvalidArgumentException(
                "Verdict '{$verdict->value}' belongs to a review or certification act and "
                .'may not be emitted by a mechanical assessment (AP-8).'
            );
        }

        if (trim($evidence) === '') {
            throw new InvalidArgumentException('An assessment must state its evidence (DP-5).');
        }

        return new self($verdict, $evidence, $subject);
    }

    public function verdict(): Verdict
    {
        return $this->verdict;
    }

    public function evidence(): string
    {
        return $this->evidence;
    }

    /** The thing assessed, as an opaque string — Shared knows no capability types. */
    public function subject(): ?string
    {
        return $this->subject;
    }

    /** Only PASS is clean; anything else is something an author must look at. */
    public function isClean(): bool
    {
        return $this->verdict === Verdict::PASS;
    }
}
