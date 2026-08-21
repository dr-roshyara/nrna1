<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;

/**
 * PORT — how the capability learns the DECLARED vocabulary (S3, D-5).
 *
 * The config format is REPLACEABLE behind this interface (YAML today under the
 * doc-placement schema family, a register or CLI later) with the domain unchanged.
 *
 * ⛔ A source OBSERVES the declared vocabulary; it never authors one (DR-1 / AP-7).
 */
interface VocabularySource
{
    /**
     * @throws \RuntimeException when the config cannot be read or is malformed — the
     *                           caller must fail closed rather than infer a vocabulary.
     */
    public function read(): DeclaredVocabulary;
}
