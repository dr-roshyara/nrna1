# Work Plan — WP-3: `ChallengeRouted` as published language + correlation-mint relocation

**Created:** 2026-07-30 · **Status:** AUTHORIZED (ARB, on WP-2 slice acceptance) — pre-implementation assessment COMPLETE; next step is RED
**Executed under:** `.claude/IMPLEMENTATION_PROTOCOL.md` (**OPERATIONAL, frozen**) — first work package to run start-to-finish under a written, frozen protocol.

## Phase 1 — Commission Reset

**Closed, not to be reopened:** WP-1 · WP-2 · F-2 · CONTEXT Runtime Simplification · Architecture-to-Implementation Fidelity Verification · the governance/protocol commission (frozen; amendments need operational evidence from a completed WP). **Carried forward: approved ADRs, decisions, rulings and standards only — previous reasoning is history, not authority.**

## Phase 2 — Authority Register

| Authority | Governs | Implementation impact |
|---|---|---|
| **ADR-T21** (issued 2026-07-26; **authorized as WP-3** 2026-07-30) | `ChallengeRouted` becomes **published language**; the correlation mint **relocates to the true chain head**; the minting allowlist follows | The whole slice |
| Roadmap §WP-3 | Scope + keystones: mint-at-route · `CorrelationIdMintingTest` green with the relocated allowlist · catalog completeness green | Acceptance criteria |
| **ADR-MP-06** + `EventProvenance` docblock | **Constitutional Audit Invariant**: one mint per conversation · propagate unchanged · causation = immediate parent · correlation = conversation, not event | What "relocation" must preserve |
| **ADR-T5** + `Event_Registry.md` | Event versioning; hydrator window vCurrent+vPrevious | The new hydrator's shape |
| `Canonical_Event_Catalog_v1.0.md` | Published-language registry | Catalog entry + completeness test |
| **ADR-T16** | Cross-context identity crosses as strings; each context reconstructs local VOs | Adjudication must not import Contestation's types |
| **ADR-T20** | Challenge lifecycle `…→Routed→Adjudicated→Resolved` | `ChallengeRouted` is emitted at `Routed`, by Contestation |
| **ADR-T8** · EPIC-004K §9 | The loop head is **event consumption**, not a cross-context command (rejected, with a recorded return condition) | Forbids inventing a command here |
| **ADR-T11 / AT-Q7** | Anonymity | No voter↔vote linkage in the new published payload |

## Phase 3 — Business Understanding

**Business capability.** Make "this challenge has been routed for adjudication" a fact the **rest of the system may hear**, not just a fact Contestation records privately.

**Business objective.** TP-2: *Contestation requests, never creates.* The request must cross the context boundary as an announcement the Adjudication head can react to — closing the loop's entrance that WP-2 built the head for.

**Business policies.** TP-2 (request, never create) · one mint per **constitutional conversation** (the conversation begins where the business act begins — at routing, not at determination) · choreography over command (ADR-T8).

**Business invariants (candidates for RED).**
1. Routing a challenge **starts exactly one conversation** — one correlation mint, at the chain head.
2. Every downstream row in that conversation carries **the same correlation id**, with causation = its immediate parent.
3. Only allowlisted chain-origin producers may mint; **reacting handlers never mint** (`fromConsumed`).
4. The published payload carries **no voter↔vote linkage**.
5. A published event is **registered**: catalog entry + hydrator, or the completeness test fails.

**Ubiquitous language.** *routed* · *published language* · *conversation* · *mint* · *propagate*. **Rejected:** "trigger command", "dispatch request" (would smuggle in the rejected cross-context command).

**Business ownership.** Contestation owns the Challenge and therefore **owns and publishes** `ChallengeRouted`. Adjudication **consumes** it. The Messaging platform owns provenance mechanics (ADR-MP-06). Nobody else may mint for this conversation.

## Phase 4 — Strategic DDD

- **Contexts touched:** Contestation (producer — publishes) · Shared/Messaging (allowlist ownership) · Adjudication (**not** in this slice — consumer registration is WP-4).
- **Relationship pattern:** **Published Language**, exactly as ADR-T21 rules; no ACL, no shared kernel.
- **Crossing shape:** integration event over the existing outbox→relay→dispatcher path; identity crosses as strings (ADR-T16).
- **The mint moves upstream:** today Adjudication's `CoordinatesAdjudication` mints via `EventProvenance::start()`; ADR-T21 relocates the mint to the routing act, and Adjudication becomes a **reactor** using `fromConsumed()`.

## Phase 5 — Business Model Fidelity

| Dimension | Preserved? | How |
|---|---|---|
| Ubiquitous language | Yes | "Routed" is existing lifecycle language (ADR-T20); nothing renamed |
| Business ownership | Yes | Contestation publishes its own event; Adjudication gains nothing to own here |
| Aggregate boundaries | Yes | `Challenge::route()` already exists; no aggregate gains a responsibility |
| Published Language | **Changes by design** — that IS the slice (ADR-T21), an addition, never a modification of an existing contract |
| Invariants | Yes | The audit invariant is *strengthened*: the mint moves to the true chain head |
| Policies | Yes | TP-2 honoured; the rejected command stays rejected |
| Context autonomy | Yes | Adjudication is untouched this slice |

**Uncertainty check → one boundary stated, not assumed.** ADR-T21 publishes an event that **nothing emits in production until the raise path exists (WP-5)** — the roadmap's own recorded dependency finding. **WP-3 therefore delivers the published-language machinery and the relocated mint; end-to-end emission from a real raise is WP-5, and consumer registration is WP-4.** Existing tests seed routed challenges directly (the `CorrectionLoopIntegrationTest` pattern), which is how WP-3 is provable without WP-5.

## Phase 6 — Architectural Traceability

| Component | Authority | Rationale |
|---|---|---|
| `ChallengeRoutedHydrator` (Contestation Infra) | ADR-T21 · ADR-T5 · Event Registry | Published events must be reconstructable; the registry-completeness test enforces it |
| Catalog entry for `ChallengeRouted` | ADR-T21 · Canonical Event Catalog | Publication is a registry act, not just a code act |
| Outbox write for `ChallengeRouted` in `ChallengeOutboxAdapter` | ADR-T21 · ADR-T3 | Publication travels the existing transactional outbox |
| **Mint relocation** — routing path uses `EventProvenance::start()`; `CoordinatesAdjudication` moves to `fromConsumed()` | ADR-T21 · ADR-MP-06 | One mint per conversation, at the true chain head |
| `CorrelationIdMintingTest` allowlist update | ADR-T21 (*"allowlist updates accordingly"*) · PB-007 7B | Extending the allowlist is an ARB-gated act; this slice carries its authorization |
| Payload schema v1 + `schema_version` | ADR-T5 | New published event starts at v1 |

**No component lacks authority.** Nothing else is created.

## Phase 7 — Simplification Review

- **No new event class** — `ChallengeRouted` already exists as an internal domain event (`app/Contexts/Contestation/Domain/Events/ChallengeRouted.php`); WP-3 *publishes* it. Reuse, not creation.
- **No new adapter** — `ChallengeOutboxAdapter` exists and already publishes two Contestation events; this is a third mapping, not a new seam.
- **No new provenance type** — `EventProvenance::start()`/`fromConsumed()` already express exactly what relocation needs.
- **Removable?** Nothing planned survives deletion: hydrator (registry-required) · catalog entry (publication *is* registration) · adapter mapping (the publication itself) · mint relocation (the slice's second half) · allowlist update (or the fitness test fails).

## Phase 8 — Business Assumption Review

| Interpretation | Classification | Evidence / action |
|---|---|---|
| `ChallengeRouted` is published as an **integration event over the existing outbox** | **Explicit authority** | ADR-T21 + ADR-T3 |
| The mint belongs to the **routing act** | **Explicit authority** | ADR-T21: *"the correlation mint relocates to the true chain head (Contestation's raise path)"* |
| `CoordinatesAdjudication` becomes `fromConsumed` | **Explicit authority** | ADR-T21, verbatim; its own frozen comment anticipated it |
| Payload starts at **schema_version 1** | Derived implication | ADR-T5 — a newly published event has no prior version |
| **Which routing method mints** (raise-path vs `Challenge::route()` call site) | **Architectural assumption — must be settled by evidence, not preference** | ADR-T21 says "Contestation's raise path", which **WP-5 owns**. WP-3 must place the mint where routing is *published from* without pre-building WP-5. **Action: resolve during RED by reading the existing route/publish path; if no non-WP-5 seam exists, STOP and report rather than inventing one.** |
| Whether `ChallengeRouted` needs a **v2** for provenance fields | Not applicable | Provenance rides the envelope, never the domain event (ADR-MP-06 invariant) |

## Phase 9 — Tactical DDD

No aggregate changes. No new VO. `ChallengeRouted` stays a **minimal domain event**; publication enriches nothing (the F-2/PB-005 ruling: minimal domain event, Application supplies publication-time data). Infrastructure gains one hydrator + one adapter mapping. The mint relocation touches an Application coordinator and a fitness allowlist.

## Phase 10 — RED plan (next step)

**Keystones (roadmap-named):**
1. **Mint-at-route** — routing starts exactly one conversation; the published row carries a minted correlation id with causation = its own event id (chain start).
2. **`CorrelationIdMintingTest` green with the relocated allowlist** — the routing producer is allowlisted; `CoordinatesAdjudication` no longer mints and must fail the guard if it tries.
3. **Catalog completeness green** — `ChallengeRouted` has a catalog entry and a registered hydrator.

**Supporting:** hydrator round-trip (payload ↔ minimal domain event) · unsupported `schema_version` rejected · anonymity (no linkage in the published payload) · **the chain continues**: a determination issued in reaction carries the *routing* conversation's correlation, not a fresh mint.

## Risks

| # | Risk | Mitigation |
|---|---|---|
| R-1 | Building WP-5's raise path while placing the mint | Phase 8's flagged assumption: find the existing seam, or **STOP and report** |
| R-2 | Two mints in one conversation during the transition (old + new) | Keystone 2 asserts `CoordinatesAdjudication` no longer mints; the fitness guard is the backstop |
| R-3 | Breaking the existing loop tests that rely on Adjudication minting | Expected and informative — they encode the *old* chain head; updating them is the slice's evidence, recorded not silent |

## Next action (exactly one)

**Write the Phase 10 RED tests and confirm they fail for the expected reasons. Report at the RED boundary before any production code.**
