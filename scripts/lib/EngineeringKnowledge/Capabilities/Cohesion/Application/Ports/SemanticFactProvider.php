<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Application\Ports;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;

/**
 * Application-owned port — the smallest capability the Cohesion use case needs from any
 * source-language adapter (Architecture Gate, 2026-09-27, §4/§6/§19). `FactSet` and its
 * constituent types remain Domain-owned; this interface is owned here because Application
 * is the sole consumer — Domain has no coupling to how a `DeclaredUnit` comes to exist.
 *
 * An implementation is a SEMANTIC INTERPRETER, not a parser: it must resolve source-language
 * constructs into this closed vocabulary correctly, not merely transcribe syntax (the
 * `@property` finding — a syntax-only reading can silently produce a wrong canonical fact
 * with no error raised).
 */
interface SemanticFactProvider
{
    public function extract(string $source): FactSet;
}
