<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableContents;

/**
 * PORT — how the capability learns a document's table rows (S4).
 *
 * Infrastructure is REPLACEABLE behind this interface (markdown today, a pre-extracted
 * index later) with the domain unchanged.
 *
 * ⛔ Implementations must read the DOCUMENT ITSELF (DR-1 / AP-2: never a projection).
 * ⛔ AP-4 forbids a central registry: an implementation OBSERVES, it does not OWN.
 */
interface DocumentTableReader
{
    /**
     * @throws \RuntimeException when the document cannot be read — the caller must
     *                           fail closed rather than infer availability.
     */
    public function read(string $path): TableContents;
}
