# Work Plan — WP-4: APM wiring (Adjudication consumes `ChallengeRouted`)

**Created:** 2026-07-31 · **Status:** OPEN — **G-2 DECIDED** (below); next step is RED
**Executed under:** `.claude/IMPLEMENTATION_PROTOCOL.md` (OPERATIONAL, frozen)
**Governing map for the crossing:** `docs/architecture/Cross_Context_Integration_Contract.md` (navigation) → canonical rules in ADR-T16 · ADR-T3/T4/T5 · ADR-MP-06 · PB-006

> **Implementation objective:** realize the approved business model through disciplined DDD — implementation subordinate to architecture, architecture subordinate to the business domain.

## Phase 1 — Commission Reset

**Closed, not reopened:** WP-1 · WP-2 · WP-3A · F-2 · the governance/protocol commissions · the cross-context contract commissions (contract, strategic validation, layer classification — all concluded). Carried forward: approved ADRs, rulings and standards only.

## Phase 2 — Authority Register

| Authority | Governs |
|---|---|
| Roadmap §WP-4 | Scope: APM wiring · the crash-safe conclude→issue seam · `FailureDeclared` publication · keystones |
| **ADR-T21** | `ChallengeRouted` is the loop head, consumed by the APM (WP-3A published it) |
| **ADR-T8** | The APM is the loop's HEAD, never its coordinator — no saga, no compensation |
| **ADR-T16** (+ its R-2 extension, pending assignment) | No cross-context Domain **or Infrastructure** import; identity crosses as strings; consumer reconstructs local VOs |
| **ADR-T3/T4** | At-least-once delivery ⇒ consumer idempotency; inbox dedupe `(event_id, consumer_context)` |
| **ADR-MP-06** | Provenance on the envelope; `fromConsumed` in reactions; consumer isolation; audit continuity |
| **PB-005 F-1** | **The Domain never names messaging outcomes** — business conditions translate to inbox markers at one Application boundary |
| **PB-006** | *Registration ≠ Delivery* — registration is consumer-side |
| **EPIC-004K** §3 (PM-1), §12 | The APM's responsibilities and failure handling |

## Phase 3–5 (condensed — the crossing was fully analysed by the readiness review and the contract)

**Business capability:** the loop's head actually fires — a routed challenge causes an adjudication to open, in production, not only in a test.
**Ownership:** Adjudication decides that it consumes (registration is consumer-side); Contestation must never learn of it.
**Fidelity:** no new architectural concept — this is the **fourth** instance of an established, machine-enforced consumer pattern (`InboxMessage` → local VOs → own PM).
**Boundary (stated, not assumed):** WP-3B (Correlation Origin Relocation) stays deferred — it depends on the existence of a routing application service. WP-4 therefore proves itself with **test-seeded routed rows** (the `CorrectionLoopIntegrationTest` pattern), exactly as the roadmap anticipated.

## G-2 — DECIDED: the business-condition → inbox-marker seam

**The prerequisite the readiness review raised, now decided by analysis of the actual conditions.**

**Question.** How do the APM's outcomes reach the inbox as `IdempotentReplay` / `CausalPreconditionMissing` / `PermanentInboxFailure` **without the PM naming any of them** (F-1)?

**Marker semantics (Shared, verbatim in substance):** `IdempotentReplay` = *the work was ALREADY DONE* · `CausalPreconditionMissing` = *the causal precondition has not been processed yet (out-of-order) → park* · `PermanentInboxFailure` = *retrying can never succeed*.

**Enumerating every condition a `ChallengeRouted` consumption can actually produce:**

| Condition | Correct inbox outcome | Why |
|---|---|---|
| First delivery, no active process | **Success** | A process opens; nothing to signal |
| Redelivery of the *same* message | **Never reaches the handler** — the inbox dedupes on `(event_id, consumer_context)` before the handler runs (ADR-T4) | The platform owns this seat |
| An active process already exists (e.g. seeded, or a prior delivery under a different event id) | **Success** — `openFor()` returns the existing id and writes nothing | PM-1's idempotency; the work *is* already done, and the handler has nothing to report that the platform doesn't already handle |
| A *terminal* process exists for the challenge | **Success** — a new process may open (F-T1: uniqueness binds **active** processes) | Decided in WP-2 |
| **Out-of-order arrival** | **CANNOT OCCUR** | `ChallengeRouted` is the **chain head** (ADR-T21). Nothing must be processed before it — there is no causal precondition to be missing |
| Malformed payload / unresolvable required field | **PermanentInboxFailure** | Already produced by the loud `required()`-style field validation; retrying cannot fix a malformed row |
| Transient infrastructure failure | **rethrow** ⇒ rollback leaves no inbox row ⇒ relay retry | Platform-owned (ADR-T3) |

### Decision

> **No translator is introduced in WP-4 — and this is a defended absence (ASP), not an omission.**
>
> Every condition a loop-head consumption can produce maps to **success**, to a **platform-owned seat** (dedupe, retry), or to the **already-existing loud validation failure**. There is no business condition left for a translator to translate. Building `ChallengeReactionOutcomeTranslator`'s counterpart now would be a class with **no case to handle** — a component without authority.

**Why the PM needs no change (WP-2 stays accepted, untouched):** F-1 is satisfied *trivially and structurally* — the PM's entry points are `void` and name nothing; there is no messaging vocabulary to remove, and none is added. The readiness review's concern was that the PM might *acquire* such vocabulary; this decision guarantees it does not.

**Reversal condition (recorded, armed):** the translator becomes **necessary** the moment Adjudication consumes a message with a genuine causal predecessor — specifically **the authority's decision** (which must not be honoured before a process is `AwaitingDecision`) or **evidence admissions** (which must not precede the process opening). Both are later slices. **When either lands, G-2 reopens and the translator is built then**, with real cases to handle. Precedent to follow at that point: `ChallengeReactionOutcomeTranslator` + its marker exceptions.

*This mirrors PB-005's own history in reverse: Contestation needed parking because its reactions had predecessors; the loop head has none.*

## Phase 6 — Architectural Traceability

| Component | Authority |
|---|---|
| `ChallengeRoutedReactionHandler` (Adjudication Application; `handle(InboxMessage)`) | ADR-T21 · ADR-T16 · the contract's consumer pattern |
| Registration by `(Adjudication, ChallengeRouted)` in `AdjudicationServiceProvider` | PB-006 *Registration ≠ Delivery* |
| Local `ChallengeRef` reconstruction from `payload['challengeId']` | ADR-T16/R-4 · already exists |
| **No** translator, **no** new marker | **G-2 above** (defended absence) |
| **No** `RoutedTo` VO | No consumer need (readiness review §4) |

## Phase 7 — Simplification Review

Nothing new but the handler and its registration. `ChallengeRef` · the PM · the store · `fromConsumed` all exist. Deletable? The handler *is* the slice; the registration is what makes it a consumer.

## Phase 8 — Business Assumption Review

| Interpretation | Classification |
|---|---|
| Adjudication consumes `InboxMessage`, never Contestation's event class or hydrator | **Explicit authority** (contract R-1/R-2; Deptrac-enforced) |
| Handler reconstructs `ChallengeRef` from the payload | **Explicit authority** (ADR-T16) |
| The handler opens a process via PM-1 | **Explicit authority** (EPIC-004K §3) |
| No translator needed **yet** | **Derived implication** — from the marker semantics + chain-head position; with the reversal condition recorded above |
| Provenance: nothing minted; `fromConsumed` only when this context *publishes* | **Explicit authority** (ADR-MP-06; minting allowlist) |

## Phase 10 — RED plan (next step)

**Keystones (roadmap §WP-4, restricted to what WP-4 can prove without WP-3B):**
1. A delivered `ChallengeRouted` **opens exactly one adjudication process** for the challenge (the loop head fires).
2. **Redelivery is inert** — dedupe prevents a second consumption; a second *distinct* delivery for a challenge with an active process opens no second process.
3. **Consumer isolation + registration** — the registry resolves `(Adjudication, ChallengeRouted)`; Contestation's own consumers are unaffected.
4. **Correlation continuity** — the process is opened within the incoming conversation; nothing new is minted (`CorrelationIdMintingTest` stays green).
5. **Malformed payload → permanent failure**, not retry.
6. **No cross-context import** — architecture/fitness + Deptrac stay green (the machine-enforced boundary).

**Deferred to their own slices (not WP-4's RED):** the crash-safe conclude→issue seam and `FailureDeclared` publication — both listed in roadmap §WP-4 but each depends on the authority-decision intake, which is a *recorded external* outside this authorization. **Flagged now rather than discovered mid-slice.**

## Next action (exactly one)

**Write the Phase-10 RED tests and confirm they fail for the expected reasons. Report at the RED boundary before any production code.**
