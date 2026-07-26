# DDD Tactical Governance — PublicDigit Binding

**Status:** ACTIVE (ARB, 2026-07-26).

## Scope

This document is a **binding only**. It declares PublicDigit's adoption of the DDD Tactical Governance methodology and hosts what is genuinely this project's: demonstrations, provenance, deviations. It contains **no rule text**.

## Canonical methodology

> **`engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`**

The seven principles, all companion guidance, the Canonical Tactical Derivation Chain, and the application guidance exist **exactly once — there**. Anyone editing governance text in this file instead of the canonical home is in the wrong file.

## Adoption declaration

PublicDigit adopts the DDD Tactical Governance Principles module **without modification**. Projects bind; projects never fork.

## Applicability

Tactical-DDD governance is ACTIVE for all tactical DDD work in this repository (aggregates, responsibilities, invariants, value objects, domain events, commands, repositories, domain services). Non-DDD work is unaffected — the platform stays methodology-agnostic.

## Runtime activation

`.claude/scripts/ddd-principles-reminder.sh` (AST-014) — non-blocking reminder when tactical-DDD surface files are touched.

## Deviations

**None.** A future deviation, if ever required, is recorded here explicitly (rule · deviation · rationale · reversal condition) — the canonical methodology is never modified or restated to accommodate it.

## Project-specific demonstrations and provenance

*(PublicDigit's own evidence — the material that minted each principle. Principle names appear below as row labels only, never as rule text.)*

| Principle | Demonstrated in this project | Provenance (historical records — never edited) |
|---|---|---|
| Methodological Fitness Rule | ownership criterion rejected R-3 out of the Determination aggregate; the Q-1 binding constraint cut R-5's validity half; classification pre-excluded coordination; Criterion 7 rejected `IssuedAt` and `RulingContent` | EPIC-004D..K (the "healthy-criterion rule") |
| Aggregate Protection Principle | Determination accepted (protects finality + uniqueness); "AdjudicationProceeding" resolved to Process Manager — no protected truth demanded an aggregate | EPIC-004E |
| Value Object Derivation Principle | `DeterminationState` accepted (strengthens invariants); `IssuedAt` rejected (timestamp wrapper, nothing strengthened) | EPIC-004F, EPIC-004G |
| Architectural Silence Principle | `DeterminationFinalized` rejected with a recorded, ARMED reversal condition (activation evidence: a *designed* retention consumer); R-3's set-level invariant recorded out of the aggregate with its owner named | EPIC-004G; Q-2 Resolution Package |
| Artifact Derivation Principle | worked Emergent-Design-Cluster example (resolved 2026-07-26): FailureDeclared + DeclareFailure + EvidenceSet + R-4-expanded + loop-head request converged on the Adjudication Process Manager; the Q-2/finality center cross-referenced, never conflated | EPIC-004G, EPIC-004H, EPIC-004K |
| Dormant Mechanism Trichotomy | the `finalize()` case: the missing intention proved to be a temporal business policy (window closure) — no `FinalizeDeterminationCommand` exists, by evidence | EPIC-004H; Q-2 Resolution Package |
| Repository Minimal Surface Principle | `save()`, `findByChallengeRef()` accepted (invariant enforcement); `findByAuthority()`, `listByOutcome()` rejected to read models | EPIC-004I, EPIC-004J |

## References

- Canonical methodology: `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`
- Early-promotion governance exception: ruling **R-39**, `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`
- Related, deliberately NOT part of this binding (each has its own home): ARB Discriminator Principle · the frozen Tactical DDD methodology sequence · the four constitutional policies · Responsibility Traceability Rule

---
*Traceability: integration commission 2026-07-26 → DA placement ruling (generalized rules → `engineering/`) → ARB "rules live once" corrections (three review rounds) → this binding-only form, written as a full-file replacement.*
