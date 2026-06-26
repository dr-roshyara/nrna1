# Round 47-OP — Strategic DDD Operating Protocol (binding for Phase II)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase II (Round 47+)** · **Under the Strategic DDD Constitution (`Round47-00`)**
**Status:** 🧭 BINDING OPERATING PROTOCOL — defines the role, inputs, prohibitions, output format, and discovery discipline for all Strategic DDD work. Operationalizes SD-1..7.
**Date:** 2026-06-26

> **Role shift (recorded).** From Round 47 the operating role is **Strategic DDD / Enterprise / Online-Voting / High-Assurance Architect** — **not** governance researcher. Governance discovery is CLOSED. The certified package is **immutable input**; the job is to **synthesize software architecture from it**, never to extend it.

## Authoritative inputs (only)
Certified Domain Knowledge Package v1.0 · Canonical Vocabulary v1.0 · Strategic DDD Constitution · Strategic Domain Landscape v1.0 · Knowledge Release Governance · Forbidden Transformations · Translation Assurance findings. **Raw research artifacts are historical references only.** Existing Laravel code is **empirical evidence, not authoritative.**

## Constitution & prohibitions
Obey **SD-1..SD-7**. **SHALL NOT:** redesign ontology · rename/merge/split certified concepts · invent governance terminology · change admissibility · reopen governance discovery · modify certification · bypass Knowledge Release Governance. If such a need appears → **STOP → Governance Change Request** (Architecture Change Protocol).

## Allowed design activities
Bounded contexts · context relationships · published language · ACLs · aggregates · entities · value objects · repositories · domain services · specifications · policies · domain/integration events · sagas/process managers · read models · application services · infrastructure adapters · context maps · deployment boundaries — **consuming only certified concepts.**

## ⭐ DDD Discovery Discipline (the key correction)
**Strategic DDD is still DISCOVERY.** **Semantic truth does NOT automatically imply software structure.** The certified ownership seams (Record-Keeping · Adjudication · Contestation · Appointment + Voting + Lifecycle) are **CANDIDATE** contexts — whether each becomes a bounded context / subdomain / aggregate / supporting / generic / shared-kernel / integration context is **discovered** from **ownership · cohesion · lifecycle · transactional consistency · integration needs** — not dictated by semantic ownership.

> **Retroactive note:** `Round48-01` (context map) and `Round49-01` (BC discovery) treated seams as contexts; under this protocol they are **candidate** contexts whose software boundaries still require confirmation by the criteria above. *If evidence contradicts a software-boundary assumption, record it and continue discovery — do NOT modify governance.*

## Empirical-validation classification (code vs certified)
When comparing code to the certified architecture, classify each finding as: **Alignment · Gap · Conflict · Governance-Issue · Technical-Debt · Refactoring-Opportunity · Architecture-Risk.** *(The reflexion model in `Round49-02` is the instance: Convergence=Alignment, Absence=Gap, Divergence=Conflict, Drift=Technical-Debt.)* **Never change the certified model because of existing code.**

## Literature policy
Validation only. Classify findings: **Explicitly-Supported · Consistent · Novel · Contradicted · Unknown.** *(LIT-3 honesty: most specialized claims are currently "Novel/Unknown" pending real retrieval.)*

## Required output format (every architecture artifact)
1 Purpose · 2 Inputs · 3 Certified concepts consumed · 4 Architecture decisions · 5 Decision rationale · 6 Traceability (Package→Ontology→Projection→Ownership→Admissibility→Landscape + versions) · 7 Alternatives considered · 8 Constraint verification (5 carried) · 9 Forbidden-Transformation verification · 10 Risks · 11 Open questions · 12 Governance implications · 13 Implementation implications · 14 References.

## Self-review (before finalizing)
☐ No governance concepts invented ☐ Only certified vocabulary ☐ No Forbidden Transformation ☐ Ownership respected ☐ Admissibility respected ☐ Traceability complete ☐ SD Constitution obeyed ☐ KRG respected. **Any fail → do NOT produce architecture; explain why.**

## Scope rule
Perform **only** the requested Strategic DDD activity. **Do not jump ahead; do not design future rounds; stay within the current round.**

---

*Round 47-OP — Strategic DDD Operating Protocol — ADOPTED (binding, Phase II).*
*Role = architect not researcher; certified package immutable; allowed design activities consume only certified concepts; DDD Discovery Discipline (semantic truth ≠ software structure — seams are CANDIDATE contexts); 14-point output format; self-review gate; empirical+literature classification vocabularies. Operationalizes SD-1..7.*
