# Work Plan — WP-4: APM wiring (Adjudication consumes `ChallengeRouted`)

**Created:** 2026-07-31 · **Status:** **GREEN COMPLETE + GATES + TRIPLE QUALIFICATION — AWAITING ARB SLICE ACCEPTANCE.** *(Header corrected 2026-08-02: it read "OPEN — G-2 DECIDED; next step is RED" for two days after GREEN landed, and a governance commission was convened on that stale line. See `engineering/verification/reports/2026-08-02-wp4-state-correction.md`.)*
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

> **No translator is introduced, because no application-owned outcome-translation responsibility exists within the currently authorized scope.** A defended absence (ASP), not an omission.
>
> *(Rationale tightened at ARB review: this is the durable form. The chain-head property is **not** the justification — it is the **explanation of why** no such business condition currently exists. Stated as "responsibility does not exist", the decision survives refactoring; stated as "it is the chain head", it would need re-deriving every time the topology changed.)*
>
> Every condition maps to **success**, to a **platform-owned seat** (dedupe, retry), or to the **already-existing loud validation failure**. Building `ChallengeReactionOutcomeTranslator`'s counterpart now would be a class with **no case to handle** — a component without authority.

**Why the PM needs no change (WP-2 stays accepted, untouched):** F-1 is satisfied *trivially and structurally* — the PM's entry points are `void` and name nothing; there is no messaging vocabulary to remove, and none is added. The readiness review's concern was that the PM might *acquire* such vocabulary; this decision guarantees it does not.

**Reversal condition (recorded, armed):** the translator becomes **necessary** the moment Adjudication consumes a message with a genuine causal predecessor — specifically **the authority's decision** (which must not be honoured before a process is `AwaitingDecision`) or **evidence admissions** (which must not precede the process opening). Both are later slices. **When either lands, G-2 reopens and the translator is built then**, with real cases to handle. Precedent to follow at that point: `ChallengeReactionOutcomeTranslator` + its marker exceptions.

*This mirrors PB-005's own history in reverse: Contestation needed parking because its reactions had predecessors; the loop head has none.*

### The positive rule behind the absence (ARB refinement)

> **Application-layer components are introduced only when they own a business decision or translation responsibility not already owned by the platform or the domain.**
>
> Future work packages then ask exactly one question: *does this component own unique business responsibility?* Yes → introduce it. No → do not create it.

**This is not a new principle — it is a sharper formulation of the protocol's existing Phase 6**, which already binds: *"Every class, enum, port, mapper, event, migration and test answers 'which authority requires this?' No authority → do not create it."* The refinement adds the **ownership test** as the operational way to answer that question.

**Disposition:** recorded here as the reasoning for G-2, **not** minted as a new rule — the protocol is FROZEN and amendments require operational evidence from a *completed* work package. **Candidate refinement to Phase 6's wording, proposable at WP-4's closure**, with this slice as its first evidence. *(Also compare: the Responsibility Traceability Rule — "responsibilities are discovered, not invented" — is the same instinct applied to aggregate duties.)*

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

---

## WP-4 RED WRITTEN + CONFIRMED (2026-07-31) — STOP at the RED boundary

**6 tests · 5 failing for the expected reasons · 1 passing vacuously (declared as such).**

| Keystone | Test | RED reason |
|---|---|---|
| 1 — the loop head fires | `…opens_exactly_one_adjudication_process` | `0 !== 1` — no consumer exists |
| 2 — redelivery inert (dedupe) | `test_redelivery_opens_no_second_process` | same |
| 2b — PM-1 idempotency across distinct messages | `…second_routing…opens_no_second_process` | same |
| 3 — registration is consumer-side | `…registers_itself_as_a_consumer…` | `has('Adjudication','ChallengeRouted')` is false |
| 4 — audit continuity | `…continues_the_incoming_conversation` | no inbox row exists |
| 6 — only Adjudication reacts | `…no_contestation_side_effect` | **passes vacuously** — no consumer exists at all yet, so it is true for the wrong reason today; it becomes meaningful after GREEN |

**Delivery runs the REAL path** — `ChallengeEventOutbox` (WP-3A's publication) → `OutboxEventProcessor::handle()` → dispatcher → inbox → handler. Nothing hand-stitched.

### Findings recorded during RED authoring (three Phase-15 diagnoses, all "incorrect test")

1. **`InboxHandlerRegistry` is `Shared\Infrastructure\Inbox`, not `…\Application\Inbox`** — wrong import.
2. **`outbox_events` carries no Eloquent timestamps** — a hand-built row set `updated_at`; the schema guess was wrong.
3. **The hand-built row also guessed `id`** (DB-generated). **Root fix rather than another patch:** the test now publishes through the real adapter and *corrupts the payload afterwards*, so it stops guessing the outbox schema entirely.

### KEYSTONE 5 REMOVED — with the reason recorded, not silently dropped

*"A malformed payload must never open a process"* is **not** tested at Feature level:
- **(a) Its business content is already proven** by WP-3A's hydrator tests (`test_missing_required_field_fails_loudly`, `test_unsupported_schema_version_is_rejected`). A Feature repeat re-proves one rule through more machinery.
- **(b) It is not cleanly provable under this harness.** On a malformed payload the relay's defensive `try/catch` around its **own** status update swallows a SQL error; PostgreSQL then refuses every later statement in the same transaction, so any post-relay assertion fails for a reason unrelated to the behaviour under test. **In production each statement autocommits, so nothing is poisoned — this is a test-harness interaction (the recorded F-7C-6 class), not a production defect.**

**Progress:** ✔ Phases 1–9 · ✔ **G-2 decided** · ✔ **RED confirmed** · ⏳ GREEN (awaiting report acceptance) · ⏳ gates · ⏳ dev guide · ⏳ slice acceptance.

---

## WP-4 GREEN COMPLETE (2026-07-31) — STOP for slice acceptance

**RED accepted by ARB 2026-07-31**, with one precision correction adopted verbatim: the claim is *"no new architectural uncertainty **within the authorized WP-4 scope** was exposed"* — a technical observation **was** made (relay failure path × single-transaction harness) and is now recorded as **AD-007** in `docs/implementation/Architecture_Debt_Backlog.md` rather than left to be forgotten. It is environment-specific, **not** architectural, and production code was correctly left unchanged.

**Pre-GREEN traceability check (ARB-requested) — every RED failure maps to exactly one missing responsibility:**

| RED failure | Missing production responsibility |
|---|---|
| No adjudication process opened | `ChallengeRoutedReactionHandler` |
| Registration does not resolve | Consumer-side registration in `AdjudicationServiceProvider::boot()` |
| No inbox row (audit continuity) | **Consequence** of the missing handler |
| Redelivery / second-routing dedupe | **Consequence** of the same handler + PM-1's existing idempotency |
| "Only Adjudication reacts" | Was vacuous; becomes meaningful once the handler exists |

**No RED failure required an unrelated change** — confirmed *before* implementing. Two components, no more.

### GREEN result — first run, no iteration

`Tests: 6, Assertions: 10, OK.` Assertions rose 6 → 10 because the previously vacuous keystone now asserts real state.

| Component | Lines of behaviour | Authority |
|---|---|---|
| `ChallengeRoutedReactionHandler` | `handle()` is one statement: reconstruct `ChallengeRef`, call `openFor()` | ADR-T21 · EPIC-004K §3 PM-1 |
| Registration in `AdjudicationServiceProvider::boot()` | `(Adjudication, ChallengeRouted)` | PB-006 *Registration ≠ Delivery* |

**Five defended absences, each recorded in the class docblock and the dev guide:** no outcome translator (G-2) · no `RoutedTo` VO · no import of Contestation's Domain (R-1) · no import of its hydrator (R-2/R-3 — the near-miss) · no provenance minting (R-6).

**A sixth absence decided during GREEN:** no bespoke `PermanentInboxFailure` exception. The platform's exception-classification contract would require one *if the condition were reachable* — but a structurally invalid payload **cannot reach the handler**: the relay hydrates producer-side **before** dispatch, and `ChallengeRoutedHydrator` (WP-3A) already rejects a missing `challengeId` loudly. Confirmed by RED's own evidence (that hydration failure is exactly what withdrew keystone 5). `ChallengeRef` still refuses an empty identity, so the fallback is loud rather than a meaningless adjudication. Same enumeration discipline as G-2 — the absence is defended, not assumed.

### Gates

| Gate | Result |
|---|---|
| PHPStan max (`phpstan-greenfield.neon`) | ✅ **No errors** |
| Deptrac (fail mode) | ✅ **0 violations** · 571 allowed — the cross-context Infrastructure import was avoided by design |
| Architecture suite | ✅ 146 tests, 626 assertions green (5 PHPUnit deprecations, 1 legitimate skip) |
| `tests/Feature/Contexts` | ⚠️ 17 Membership failures — **pre-existing, git-stash-verified** (identical counts with WP-4 stashed) → **AD-008** |
| Developer guide (DoD) | ✅ `developer_guide/adjudication/04_challenge_routed_consumption.md` + index updated |

**Progress:** ✔ Phases 1–9 · ✔ G-2 · ✔ RED accepted · ✔ **GREEN** · ✔ gates · ✔ dev guide · ⏳ **slice acceptance (STOP)**.

---

## Triple Qualification — WP-4 (2026-08-02) — all three categories PASS

**The same Definition-of-Done gate, performed on the same day for both packages awaiting acceptance. Evidence re-verified today.**

**Current evidence:** `composer merge-gate` → **PASS** (266 · 665 · 101 pre-existing risky) · the six WP-4 tests re-run → **PASS**.

### 1. Architecture — PASS

| Check | Evidence |
|---|---|
| Gates | Deptrac **0 violations** · greenfield PHPStan **no errors** · architecture fitness suite green |
| **The near-miss is the load-bearing result** | importing Contestation's hydrator would have been **the codebase's first cross-context Infrastructure dependency**. Deptrac enforces contract **R-2**; **no ADR states it** — the recorded gap, not a defect of this slice |
| No new architectural concept | one `InboxHandler` + its registration — **the fourth instance of an established, machine-enforced pattern** |
| Composition root | registration in `AdjudicationServiceProvider::boot()` |

### 2. DDD — PASS

| Check | Evidence |
|---|---|
| Ownership | **PM-1 (`AdjudicationProcessManager`) owns the rule, including its own idempotency.** The handler owns **only the wiring** |
| ADR-T16 | Adjudication reconstructs its **own** `ChallengeRef` from an opaque identity string. **No import of Contestation's Domain or Infrastructure** |
| PB-006 *Registration ≠ Delivery* · contract R-7 | **the consumer declares that it consumes**; Contestation never names Adjudication |
| **Six defended absences, each recorded in the docblock and the dev guide** | no outcome translator (G-2) · no `RoutedTo` VO · no Domain import · no hydrator import · no provenance minting · no bespoke `PermanentInboxFailure` |
| Ubiquitous Language | **unchanged** |

### 3. Trustworthiness — PASS

| Property | Evidence |
|---|---|
| **Correlation continuity** (ADR-MP-06) | `test_the_consumption_continues_the_incoming_conversation`; **nothing is minted** — `CorrelationIdMintingTest` stays green |
| **Idempotency / replay safety** | `test_redelivery_opens_no_second_process` · `test_a_second_routing_of_the_same_challenge_opens_no_second_process` — **both seats exercised**: platform inbox dedupe and PM-1's own rule |
| **Consumer isolation** | `test_routing_produces_no_contestation_side_effect` · `test_adjudication_registers_itself_as_a_consumer_of_challenge_routed` |
| **Anonymity** (ADR-T11) | **only `challengeId` crosses.** No voter or vote identity enters Adjudication |
| **Loud failure** | `ChallengeRef` refuses an empty identity rather than opening a meaningless adjudication |

### Two debts carried, neither introduced by this slice

**AD-007** — relay failure path × single-transaction harness; **environment-specific, not architectural**; production code correctly unchanged. **AD-008** — 17 pre-existing Membership feature failures, **git-stash-verified** as identical with WP-4 stashed.

**Progress:** ✔ G-2 · ✔ RED · ✔ **GREEN** · ✔ gates · ✔ dev guide · ✔ **triple qualification** · ⏳ ARB slice acceptance.
