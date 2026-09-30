<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — D-1: an observed method invocation whose receiver denotes the analysed unit but
 * whose method name is computed (`$this->$m()` / `getattr(self, name)()`), not a literal.
 * A behaviour was referenced; which one is unknown, and no legal name exists to record
 * (INV-L3-5 forbids a sentinel or absent-as-null field on `BehaviourReference`; INV-4/
 * INV-L3-7 forbid falling back to source text) — hence a distinct fact kind rather than a
 * relaxed `BehaviourReference`.
 *
 * Carries no fields: determinability is fixed by construction (this kind exists only for
 * the not-determinable case — EdgeRules excludes every instance unconditionally), so no
 * field is needed to store it. Minimal schema, independently re-derived
 * (2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md, Candidate S1).
 */
final readonly class IndeterminateBehaviourReference
{
}
