# DDD Tactical Governance Principles (Engineering Platform — canonical)

**Class:** Engineering Platform methodology module · **Owner:** Decision Authority
**Status:** ADOPTED (explicit Decision Authority ruling, 2026-07-26). **Early promotion is a recorded governance exception — see ruling R-39** in the rulings register (`../../architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`): rationale, evidence base (one context), and expected validation live there, not embedded here — the exception is traceable governance, never part of the methodology itself.
**Nature:** a **methodology module the platform enforces, never platform architecture** — the Engineering Platform remains methodology-agnostic; these bind only work that declares tactical-DDD governance ("VS Code doesn't know DDD; the DDD extension does").
**Scope:** tactical DDD design only — aggregates, responsibilities, invariants, value objects, events, commands, repositories, domain services. NOT: Strategic DDD, Bounded-Context discovery, Context Mapping, team organization, Event Storming, Ubiquitous-Language discovery.
**Bindings:** projects bind this module; they do not fork it. PublicDigit binding: `docs/architecture/governance/DDD_PRINCIPLES.md` (hosts the project's demonstrations and EPIC-004 provenance). Runtime enforcement: `.claude/scripts/ddd-principles-reminder.sh` (AST-014, non-blocking reminder).

---

## 1. Methodological Fitness Rule
> **A criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed ceremonial until evidence shows otherwise.**

Every evaluation criterion must demonstrate discriminative power — including the acceptance criteria themselves.
**Companion — the canonical acceptance chain:** `business evidence → obligation → decision → consistency → alternatives eliminated → removal test → protected domain truth → responsibility accepted` — a closed epistemic loop: the protection target is the positive form of the removal-test sentence; a Business Truth is *implemented by* Business Invariant(s).

## 2. Aggregate Protection Principle (APP)
> **An aggregate exists to protect domain properties (finality, uniqueness, attribution, authenticity, integrity), not merely to encapsulate domain objects — objects are the vehicle; protected properties are the purpose.**

Kills "we have an entity, therefore we need an aggregate."
**Companion litmus (invariants):** *would a domain expert recognize the rule as business, even if the software didn't exist?* — implementation constraints are not domain invariants.

## 3. Value Object Derivation Principle (VODP)
> **A Value Object is justified only when it strengthens the expression, validation, or protection of one or more accepted business invariants. Convenience grouping or data packaging alone is insufficient.**

Derivation direction: truth → invariant → concepts needed — never fields-that-travel-together.
**Companion (Domain Events entry condition):** *a Domain Event must trace to accepted invariants AND represent a business-significant occurrence* — litmus: would a domain expert describe it as something that happened in the business, regardless of implementation? Never method-executed → event.

## 4. Architectural Silence Principle (ASP)
> **The absence of an architectural element is a decision, not a default. Rejected candidates and deliberate non-events shall be recorded together with their rationale and reversal conditions when appropriate.**

"Why isn't there an X?" must have an answer already on the record.

## 5. Artifact Derivation Principle (ADP)
> **Every tactical artifact derives from the immediately preceding frozen artifact. New concepts may not bypass the derivation chain without explicit ARB authorization.**

**Canonical Tactical Derivation Chain** *(this platform's tactical methodology — not a claim about universal DDD process)*: `Aggregate → Responsibilities → Protected Domain Truths → Business Invariants → Value Objects → Domain Events → Commands → Repositories → Domain Services.`
**Companion (commands):** *business occurrence → business intention → command; never public-method → command.*
**Companion vocabulary — Emergent Design Cluster:** deferrals that cluster are symptoms of one missing concept — open them together; distinct centers are cross-referenced, never conflated.

## 6. Dormant Mechanism Trichotomy (DMT)
> **Dormant implementation (no caller, no expressed intention) must be classified through evidence, never intuition or default labels ("tech debt", "future work"): (1) implementation convenience meant to remain internal → evidence · (2) dead/obsolete capability → evidence · (3) missing business intention never modeled → evidence → recorded conclusion (a hypothesis, preserved as such).**

**Companion insight:** a missing intention may prove to be a **temporal business policy** (window-expiry → policy → action), in which case the corresponding COMMAND may never exist — the realization shape is decided when the owning question resolves, not presupposed by the reversal condition.

## 7. Repository Minimal Surface Principle (RMSP)
> **A repository exposes only the operations required to preserve, reconstitute, or enforce accepted aggregate invariants. Query convenience belongs to read models, not aggregate repositories.**

A repository exists because protected truths must survive time and process boundaries — persistence is the mechanism, not the reason.
**Companions:** *reconstitution is not an occurrence* (loading state is never a business event) · Domain-Service entry condition: *a Domain Service exists only when a business operation cannot naturally belong to a single Aggregate while preserving the participating aggregates' truths.*

---

## Application (when tactical-DDD governance is active)

1. Before creating any tactical artifact — confirm the derivation chain (ADP).
2. Before accepting any candidate — apply the relevant principle(s); criteria that never reject are suspect (Fitness Rule).
3. Before rejecting any candidate — record rationale and reversal conditions (ASP).
4. Before evaluating existing implementation — classify dormant mechanisms through evidence (DMT).
5. Before designing a repository — minimal surface only (RMSP).

---
*Traceability: minted in PublicDigit EPIC-004D..K (first adoption evidence — demonstrations and per-principle provenance live in the PublicDigit binding); ratified as permanent governance by the ARB 2026-07-26; promoted to the Engineering Platform by explicit Decision Authority ruling the same day ("the ai architecture is given in ./engineering folder — if you want to create a generalized rule then put it in that folder"). Registered in ES-006. Rules live once: bindings and runtime reminders point here and never restate.*
