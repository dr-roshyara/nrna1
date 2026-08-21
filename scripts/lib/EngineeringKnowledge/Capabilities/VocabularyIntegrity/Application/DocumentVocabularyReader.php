<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\VocabularyContents;

/**
 * PORT — how the capability learns a document's vocabulary observations (S3).
 *
 * The reader CLASSES each retired-term occurrence cited/live and extracts the
 * confusable identifiers; whether any of that is a DEFECT is the domain service's call.
 *
 * Infrastructure is REPLACEABLE behind this interface (markdown today, a pre-extracted
 * index later) with the domain unchanged.
 *
 * ⛔ Implementations must read the DOCUMENT ITSELF (DR-1 / AP-2: never a projection).
 * ⛔ Read-only (AP-7 / DR-4): nothing here authors or edits.
 */
interface DocumentVocabularyReader
{
    /**
     * @throws \RuntimeException when the document cannot be read — the caller must
     *                           fail closed rather than infer availability.
     */
    public function read(string $path, DeclaredVocabulary $vocabulary): VocabularyContents;
}
