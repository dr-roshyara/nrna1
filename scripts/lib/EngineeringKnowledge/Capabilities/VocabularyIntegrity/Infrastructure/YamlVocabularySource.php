<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\VocabularySource;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * YAML adapter for the declared vocabulary (S3, D-5).
 *
 * Reads the D-5 config shape through the SAME parser the governed doc-placement
 * tooling uses (`Symfony\Component\Yaml\Yaml` — ES-005.4: reuse the existing parser,
 * never a second one). For Phase 0 the back-test supplies a temporary config; the
 * governed `docs/knowledge/schema/` copy is a later adoption act this run must not do.
 *
 * ⛔ CONFIGURATION ONLY — stores WHAT the declared vocabulary is, never WHY.
 * ⛔ Read-only (AP-7 / DR-4): this adapter never writes the config.
 */
final readonly class YamlVocabularySource implements VocabularySource
{
    public function __construct(
        private string $path,
    ) {
    }

    public function read(): DeclaredVocabulary
    {
        if (! is_readable($this->path)) {
            throw new RuntimeException("vocabulary config not readable at {$this->path}");
        }

        $config = Yaml::parseFile($this->path) ?? [];

        if (! is_array($config)) {
            throw new RuntimeException("vocabulary config '{$this->path}' is not a mapping");
        }

        $retiredTerms = $config['retired_terms'] ?? [];

        if (! is_array($retiredTerms)) {
            throw new RuntimeException(
                "vocabulary config '{$this->path}': 'retired_terms' must be a list",
            );
        }

        $label = $config['confusable_declaration_label'] ?? '';

        try {
            return DeclaredVocabulary::of(
                array_map(static fn ($term): string => (string) $term, $retiredTerms),
                is_string($label) ? $label : '',
            );
        } catch (InvalidArgumentException $e) {
            // The port's contract is RuntimeException for a malformed config; the domain
            // VO speaks InvalidArgumentException. The boundary translates, never hides.
            throw new RuntimeException(
                "vocabulary config '{$this->path}' is malformed: {$e->getMessage()}",
                0,
                $e,
            );
        }
    }
}
