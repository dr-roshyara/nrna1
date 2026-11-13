<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A subject holding an identifier value in a namespace over an interval in which the subject is LIVE.
 * The validator (G-2) derives it from IDENTIFIER and EXISTENCE claims; the kernel only judges I-1 over it.
 */
final readonly class IdentifierAssignment
{
    public function __construct(
        public ReferenceUnitId $subject,
        public string $namespace,
        public string $value,
        public ValidInterval $interval,
    ) {
        if (trim($namespace) === '' || trim($value) === '') {
            throw new \InvalidArgumentException('An identifier assignment needs its namespace and value.');
        }
    }
}
