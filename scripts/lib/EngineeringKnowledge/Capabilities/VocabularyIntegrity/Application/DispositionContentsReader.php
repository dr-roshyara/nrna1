<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionContents;

/**
 * The read-side port for S5 (same role as DocumentVocabularyReader in S3).
 *
 * A reader OBSERVES a document and returns what it observed as a DispositionContents
 * value object. It applies NO policy: whether an unlabelled single-remedy row competes
 * with a declared split is the domain service's call under §0.4.4 / DP-3.
 *
 * Fail-closed contract: a document that cannot be read must THROW, never return a partial
 * or empty observation — the application service converts any failure into INCONCLUSIVE
 * (D-2: absence of evidence is not PASS).
 */
interface DispositionContentsReader
{
    /**
     * Read the document at $path and return its disposition observations.
     *
     * @throws \RuntimeException when the document is unreadable, missing, or cannot be parsed.
     */
    public function read(string $path): DispositionContents;
}
