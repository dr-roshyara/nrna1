<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — the declared role of a method, independent of any language's spelling for it.
 * CLOSED VOCABULARY (mirrors `QualifierKind`'s own discipline: a fact captured once at
 * extraction, never a name re-inspected downstream).
 *
 * `Lifecycle` covers exactly what the pinned decision (`expected.json`, `constructors`)
 * already treats as one undifferentiated exclusion bucket — a method that runs on every
 * instantiation/destruction and would mask real splits if counted as a node. Each
 * language's adapter maps its own spelling to this vocabulary (PHP: `__construct`/
 * `__destruct`; Python: `__init__`/`__del__`); `Domain` code must never inspect a name to
 * make this determination.
 */
enum MethodRole
{
    case Ordinary;
    case Lifecycle;
}
