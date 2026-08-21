<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;

/**
 * PORT — how the capability learns a document's section identifiers (S1).
 *
 * The document-local register's read side. Infrastructure is REPLACEABLE behind this
 * interface (markdown today, a pre-extracted index later) with the domain unchanged.
 *
 * ⛔ Implementations must read the DOCUMENT ITSELF (DR-1 / AP-2: never a projection).
 * ⛔ AP-4 forbids a central registry: an implementation OBSERVES, it does not OWN.
 */
interface DocumentContentsReader
{
    /**
     * @throws \RuntimeException when the document cannot be read — the caller must
     *                           fail closed rather than infer availability.
     */
    public function read(string $path): DocumentSectionSequence;
}
