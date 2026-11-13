<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The outcome of validating a revision's claims at p₀ (v3.11 §5, §5.1, §14): violations, the recorded dependency
 * set (with p₀), each claim's before-image, and the derived structural classification.
 */
final readonly class ValidationResult
{
    /**
     * @param list<Violation> $violations
     * @param array<string, BeforeImage> $beforeImages by claim id
     */
    public function __construct(
        public array $violations,
        public DependencySet $dependencies,
        public array $beforeImages,
        public bool $structural,
    ) {
    }

    public function isValid(): bool
    {
        return $this->violations === [];
    }

    public function beforeImageOf(ClaimId $claim): BeforeImage
    {
        return $this->beforeImages[$claim->value()] ?? throw new \InvalidArgumentException('No before-image for that claim.');
    }

    /** A canonical form for the content-version digest (§5.3). */
    public function describe(): array
    {
        $images = [];
        foreach ($this->beforeImages as $claimId => $image) {
            $images[$claimId] = array_map(static fn (array $s): array => [$s[0]->from->value(), $s[0]->to?->value(), $s[1]->describe()], $image->segments());
        }
        ksort($images);

        return ['p0' => $this->dependencies->p0, 'dependencies' => $this->dependencies->describe(), 'before_images' => $images, 'structural' => $this->structural];
    }
}
