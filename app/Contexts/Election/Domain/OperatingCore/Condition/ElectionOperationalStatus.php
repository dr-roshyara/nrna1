<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

/**
 * The representable combination EM-GOV-059(b) requires: `HALTED ∧ INOPERATIVE` —
 * both facts retained. Inoperative is DOMINANT (an additional operational condition
 * preventing progression) but never a replacement for the halt's business meaning.
 * A single collapsed state value could not represent this; two orthogonal facts do.
 * @immutable
 */
final readonly class ElectionOperationalStatus
{
    private function __construct(
        private ?HaltedAtGate $haltedAtGate,
        private OperationalCondition $condition,
    ) {
    }

    public static function operative(): self
    {
        return new self(null, OperationalCondition::Operative);
    }

    public static function operativeHalted(HaltedAtGate $haltedAtGate): self
    {
        return new self($haltedAtGate, OperationalCondition::Operative);
    }

    /** EM-GOV-059(b): becoming Inoperative RETAINS the halt. */
    public function becameInoperative(): self
    {
        return new self($this->haltedAtGate, OperationalCondition::Inoperative);
    }

    /** EM-GOV-059(c): restoration returns to the prior condition — the halt, where one exists, remains. */
    public function restored(): self
    {
        return new self($this->haltedAtGate, OperationalCondition::Operative);
    }

    public function condition(): OperationalCondition
    {
        return $this->condition;
    }

    public function isHalted(): bool
    {
        return $this->haltedAtGate !== null;
    }

    public function haltedAtGate(): ?HaltedAtGate
    {
        return $this->haltedAtGate;
    }
}
