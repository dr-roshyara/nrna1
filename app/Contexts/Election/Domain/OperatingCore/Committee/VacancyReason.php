<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Committee;

use InvalidArgumentException;

/**
 * A stated vacancy reason (EM-GOV-064: "resignation with reason"). Free-text reason
 * fields are CONSTRAINED SURFACES under ADR-T11 (EM-ARCH-001 §5e; EM-OPEN-091②):
 * bounded length, never blank, checked at the boundary. @immutable
 */
final readonly class VacancyReason
{
    private const MAX_LENGTH = 500;

    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('A vacancy reason cannot be blank.');
        }
        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('A vacancy reason is a constrained surface (ADR-T11): at most %d characters.', self::MAX_LENGTH)
            );
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
