<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use InvalidArgumentException;

/**
 * The DECLARED vocabulary a configuration supplies (S3, D-5).
 *
 *   • retiredTerms — terms the current vocabulary has retired; a LIVE occurrence is
 *     an overload of the past against the present (DI-2: `Phase 2b` → `2c-commit`).
 *   • confusableDeclarationLabel — the integrity-finding label whose presence in a
 *     document declares a confusable-identifier collision QUALIFIED (DI-7: `DI-7`).
 *
 * What a term IS belongs to the governed vocabulary; this VO only carries it. It
 * creates no term and no finding (DR-1 / AP-7).
 */
final readonly class DeclaredVocabulary
{
    /**
     * @param  list<string>  $retiredTerms
     */
    private function __construct(
        private array $retiredTerms,
        private string $confusableDeclarationLabel,
    ) {
    }

    /**
     * @param  list<string>  $retiredTerms
     */
    public static function of(array $retiredTerms, string $confusableDeclarationLabel): self
    {
        $retiredTerms = array_values(array_unique(array_filter(
            array_map(static fn (string $term): string => trim($term), $retiredTerms),
            static fn (string $term): bool => $term !== '',
        )));

        $confusableDeclarationLabel = trim($confusableDeclarationLabel);

        if ($confusableDeclarationLabel === '') {
            throw new InvalidArgumentException(
                'A confusable-declaration label is required (DP-3: where overloaded, '
                .'it shall be qualified — the label IS the qualification).',
            );
        }

        return new self($retiredTerms, $confusableDeclarationLabel);
    }

    /** @return list<string> */
    public function retiredTerms(): array
    {
        return $this->retiredTerms;
    }

    public function confusableDeclarationLabel(): string
    {
        return $this->confusableDeclarationLabel;
    }
}
