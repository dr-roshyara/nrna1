<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\SeriesContents;

/**
 * PORT — how the capability learns what a register(ns) contains.
 *
 * Infrastructure is REPLACEABLE behind this interface: markdown today, YAML or a
 * graph store later, with the domain unchanged (PA direction; hexagonal).
 *
 * ⛔ Implementations must read the GOVERNED REGISTERS themselves.
 *    DR-1 / AP-2 forbid consuming a projection as evidence for a decision, so a
 *    cached index must never back this port.
 * ⛔ AP-4 forbids a central registry: an implementation OBSERVES, it does not OWN.
 */
interface SeriesContentsReader
{
    /**
     * @throws \RuntimeException when the register cannot be read — the caller must
     *                           fail closed rather than infer availability.
     */
    public function read(IdentifierSeries $series): SeriesContents;
}
