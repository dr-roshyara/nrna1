<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\ContextPersonRef;

/** Granted with a reference, or refused with a reason. A refused answer has no reference to read. */
final readonly class ActingContextRefAnswer
{
    private function __construct(private ?ContextPersonRef $ref, private ?ActingContextRefRefusal $refusal)
    {
    }

    public static function granted(ContextPersonRef $ref): self
    {
        return new self($ref, null);
    }

    public static function refused(ActingContextRefRefusal $refusal): self
    {
        return new self(null, $refusal);
    }

    public function isGranted(): bool
    {
        return $this->ref !== null;
    }

    public function ref(): ContextPersonRef
    {
        return $this->ref ?? throw new \LogicException('A refused answer has no reference.');
    }

    public function refusal(): ?ActingContextRefRefusal
    {
        return $this->refusal;
    }
}
