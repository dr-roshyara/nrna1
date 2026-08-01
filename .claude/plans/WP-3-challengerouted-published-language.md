# Work Plan — WP-3: `ChallengeRouted` as published language + correlation-mint relocation

**Created:** 2026-07-30 · **Status:** **WP-3A: GREEN COMPLETE + GATES + TRIPLE QUALIFICATION — AWAITING ARB SLICE ACCEPTANCE. WP-3B: DEFERRED** (needs a routing application service). *(Header corrected 2026-08-02 — it still read "next step is RED".)*
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

---

## Phase 10 opened — the flagged assumption resolved by evidence, and it triggered the recorded STOP condition

**RED was authorized. The plan's own first act was to settle Phase 8's flagged assumption — *where does the mint seat without pre-building WP-5's raise path?* — with the recorded instruction: "if no non-WP-5 seam exists, STOP and report rather than invent one." It does not exist.**

### Evidence

| Check | Result |
|---|---|
| `Challenge::route(string $routedTo, DateTimeImmutable $at)` exists on the aggregate and records `ChallengeRouted` | ✔ (`Challenge.php:94`, `:98`) |
| **Any Application-layer caller of `->route(`** | **NONE.** The only matches repo-wide are Laravel's unrelated `$request->route('tenant')` in Membership |
| Production mint sites (`EventProvenance::start()`) | Exactly one — `CoordinatesAdjudication`, the sole `CHAIN_ORIGIN_ALLOWLIST` entry |

**No application service, handler, command or coordinator routes a challenge.** The routing *entry point* does not exist — and building it **is** WP-5's raise path.

### Consequence — WP-3 does not divide the way the roadmap's order assumes

| Half | Status |
|---|---|
| **WP-3A — Published Language Infrastructure**: `ChallengeRoutedHydrator` · Canonical Event Catalog entry · `ChallengeOutboxAdapter` mapping · payload schema v1 · hydrator registration | **APPROVED — executable now** (ARB 2026-07-30). The event class exists; publication and hydration are provable from a constructed event without any caller. *Establishes infrastructure capability* |
| **WP-3B — Correlation Origin Relocation**: move `start()` from `CoordinatesAdjudication` to the routing act + update `CHAIN_ORIGIN_ALLOWLIST` | **DEFERRED** (ARB 2026-07-30). *Changes business behaviour* |

**The deferral's dependency, stated conceptually rather than chronologically (DA refinement 2026-07-30):**

> **Correlation Origin Relocation depends on the existence of a routing application service.**
> *Under the current roadmap, that service belongs to WP-5.*

Phrased this way the dependency stays correct if the roadmap is re-ordered — the blocker is the **absent seam**, not the number of a work package. Relocating the mint before that service exists would leave **zero** production mint sites: the correlation chain breaks and `CorrelationIdMintingTest` guards an empty allowlist.

*The two slices are separated by their **reason to change**: WP-3A establishes infrastructure capability; WP-3B changes business behaviour.*

**Risk R-2 was recorded as a caution; the evidence upgrades it to a hard blocker.** This is the sharper form of the roadmap's own dependency finding: it flagged that *ADR-T21 publishes an event nothing emits until WP-5*; what is now established is that **the mint-relocation half of WP-3 also cannot execute before WP-5** — the roadmap's `WP-3 → WP-4 → WP-5` order assumes WP-3 is wholly executable, and it is not.

### Recommendation (ARB decision — re-slicing the roadmap is not mine)

1. **Authorize WP-3a now** as the slice: publication machinery only, keystone tests = hydrator round-trip · catalog completeness · adapter writes schema v1 · unsupported version rejected · anonymity. `CoordinatesAdjudication` keeps minting untouched, so the chain stays intact and no existing loop test breaks.
2. **Move WP-3b to WP-5** (or make it WP-5's first act), where the raise path supplies the seam ADR-T21 names. ADR-T21 is unchanged and unviolated — only the *sequencing* of its two consequences changes.
3. **Alternative, if the ARB prefers WP-3 whole:** WP-5 must precede WP-3 — a roadmap re-order, which is an explicit ARB act, not an implementation convenience.

**No production code, no tests written. No seam invented.**

---

## WP-3A RED WRITTEN + CONFIRMED (2026-07-30) — STOP at the RED boundary

**11 tests · 10 failing for the expected reasons · 1 passing by construction.**

| File | Tests | Result |
|---|---|---|
| `tests/Unit/Contexts/Contestation/Infrastructure/Outbox/ChallengeRoutedHydratorTest.php` | 7 | 4 errors + 2 failures = **`ChallengeRoutedHydrator` not found** (intentionally unimplemented). **1 passes by construction:** the payload-shape anonymity check asserts the wire contract carries no voter/vote key — true of the contract as designed, so it is a *pinned* contract rather than a pending one |
| `tests/Feature/Contexts/Contestation/ChallengeRoutedPublicationTest.php` | 4 | 4 errors = **`LogicException: No outbox mapping for event …ChallengeRouted`** — the adapter's `match(true)` default, i.e. exactly the missing publication |

**Coverage:** hydrator round-trip fidelity · **domain event stays minimal** after hydration (the PB-005 F-2 ruling pinned as a test: exactly the three recorded facts, nothing enriched) · unsupported `schema_version` rejected · absent marker = v1 · missing field fails loudly · payload-shape anonymity · **published to the outbox at schema v1** with `aggregate_type=Challenge` · **supplied provenance stamped unchanged** · **registration resolves the booted hydrator** (`registry->has()` + `hydratorFor()`) · real-wire anonymity.

**Two boundaries the tests enforce structurally, not by comment:**
1. **No mint site is added.** Every test *supplies* provenance explicitly; nothing calls `EventProvenance::start()` in Contestation. WP-3B's relocation stays absent, so `CorrelationIdMintingTest` remains green with its single existing allowlist entry and the correlation chain is untouched.
2. **No consumer registration.** Nothing registers an inbox handler for `ChallengeRouted` — that is WP-4.

**One API correction during RED (recorded, not silent):** the registry's lookup methods are `has()` / `hydratorFor()`, not `for()`. Corrected in the test before confirming RED — a test-authoring fix, not a design change.

**Progress:** ✔ Phases 1–9 · ✔ flagged assumption resolved (→ the WP-3A/WP-3B split) · ✔ **WP-3A RED confirmed** · ⏳ GREEN (awaiting report acceptance) · ⏳ gates · ⏳ dev guide · ⏳ ARB slice acceptance. **WP-3B deferred** — depends on the existence of a routing application service.


---

## Published language — the precise relationship (ARB refinement, 2026-07-30)

The earlier shorthand *"publication is registration"* was **too strong**; they are distinct responsibilities:

```
Published Language
        ├── Publication    — the event travels over the wire (outbox row, schema v1)
        └── Registration   — the event can be reconstructed from that wire representation (hydrator)
```

> **Published language requires BOTH publication and registration. An event is not part of the published language unless it can both travel over the wire and be reconstructed from that wire representation.**

Either alone is incomplete: publication without registration produces an unreconstructable row; registration without publication produces a reader for something nothing sends.

## WP-3A GREEN — acceptance criteria (objective boundary)

| Capability | Acceptance evidence |
|---|---|
| **Hydration** | Round-trip test passes (payload → domain event, full fidelity) |
| **Publication** | Outbox row written: `event_type=ChallengeRouted`, `aggregate_type=Challenge`, `status=pending` |
| **Registration** | `EventHydratorRegistry` resolves the booted hydrator (`has()` + `hydratorFor()`) |
| **Schema** | Version-handling tests pass: v1 accepted · absent marker = v1 · v2 rejected · missing field loud |
| **Anonymity** | Privacy tests pass at both the payload-shape and real-wire levels |
| **Minimality** | The reconstructed domain event carries exactly its three recorded facts (PB-005 F-2 ruling) |
| **Boundary integrity** | No mint site added (`CorrelationIdMintingTest` green, allowlist unchanged) · no consumer registered (WP-4 untouched) |
| **Gates** | `composer merge-gate` PASS · triple qualification · dev guide shipped |

GREEN is complete only when every row above has its evidence.

---

## WP-3A GREEN DONE + GATES PASS (2026-07-30) — STOP for ARB slice acceptance

**11/11 tests GREEN. `composer merge-gate` PASS:** Architecture fitness 146✔/626 · **Deptrac 0 violations** · greenfield PHPStan **No errors** · widened regression 222✔/584/0 failed (66 risky = the recorded F-7C-6 artifact).

**Acceptance criteria — every row has its evidence:**

| Capability | Evidence |
|---|---|
| Hydration | round-trip test passes (payload → minimal domain event, full fidelity) |
| Publication | outbox row written: `event_type=ChallengeRouted` · `aggregate_type=Challenge` · `status=pending` |
| Registration | `EventHydratorRegistry::has()` + `hydratorFor()` resolve the booted `ChallengeRoutedHydrator` |
| Schema | v1 accepted · absent marker = v1 · v2 rejected · missing field loud |
| Anonymity | payload-shape and real-wire privacy tests pass |
| Minimality | reconstructed event carries exactly its three recorded facts (PB-005 F-2) |
| Boundary integrity | **no mint site added** (`CorrelationIdMintingTest` green, allowlist unchanged at one entry) · **no consumer registered** (WP-4 untouched) |
| Gates | merge-gate PASS · dev guide `developer_guide/contestation/05_*` + index row |

**Implemented:** `ChallengeRoutedHydrator` (registration half) · `ChallengeOutboxAdapter::writeRouted()` (publication half, schema v1) · hydrator registration in `ContestationServiceProvider`. **`ChallengeRouted` itself unchanged** — WP-3A publishes an event that already existed.

**Three findings recorded, not absorbed:**
1. **Phase-15 diagnosis ×2, both returning "incorrect test."** (a) `correlation_id`/`causation_id` are **UUID columns**; my test data (`'conversation-1'`) was invalid — test fixed. (b) I asserted `causation_id === correlation_id` for a chain start; `EventProvenance::start()` returns `(id, **null**)`, and **null is correct** — ADR-MP-06's invariant is *causation = immediate parent*, and a chain start has no parent. The assertion now pins the real invariant, and the corrected test is **stronger** than the draft.
2. **The catalog entry did not need creating.** Phase 6 listed "Catalog entry for `ChallengeRouted`" as a component; the entry **already exists**. What is stale is its `visibility` marking (`internal`).
3. **Frozen-artifact finding, referred out:** `Canonical_Event_Catalog_v1.0.md` is **🧊 FROZEN** (*"Changes follow ADR-T5 — version, never mutate"*) and marks **three** now-published events as `internal` — `ChallengeRouted`, `ChallengeAdjudicated`, `ChallengeResolved`. Correcting the markings requires a **v1.1 catalog**, an ARB act. This slice implements against the ADR and records the staleness, following the precedent PB-005 set for two of these same events. **Not edited here.**

> **⛔ ANNOTATION (2026-08-02 — added, not rewritten): FINDING 3 IS WITHDRAWN.** **`Canonical_Event_Catalog_v1.0.md` has no `visibility` column.** Its columns are `Event | Producer (only) | Classification | Stability | Security | SchemaVer`, and **`internal` is a value of the SECURITY column** — the other value is `restricted`, carried by `VoteAccepted`, `EvidenceRecorded` and `DeterminationIssued`. **It is a data-sensitivity classification, not a publication marking.** The proof: **`ElectionCorrectionApplied` is classified `Integration` — the legend's own *“cross-context reaction”* — and is marked `internal`.** **Round50-05, the authoritative contract, goes further: for `ChallengeRaised…Resolved` it lists Security `internal` AND Consumers (allowed) `AdjudicationService · Audit · NOT Voting`, with payload `challengeId · routedTo · at` — exactly what WP-3A publishes and exactly the consumer WP-4 built.** **The frozen artifact does not contradict the implementation; it specifies it. No v1.1 catalog is required on this ground.** *(What does remain: the catalog records no published-language status for ANY event, so ADR-T21's declaration has no counterpart in the registry — a registry gap, not a discrepancy about three events.)* See `engineering/verification/reports/2026-08-02-wp3a-acceptance-review.md` §5.

**Progress:** ✔ Phases 1–9 · ✔ split finding · ✔ RED · ✔ **GREEN** · ✔ **merge-gate** · ✔ dev guide · ⏳ triple qualification + ARB slice acceptance. **WP-3B (Correlation Origin Relocation) deferred** — depends on the existence of a routing application service.

---

## Triple Qualification — WP-3A (2026-08-02) — all three categories PASS

**Performed as the Definition-of-Done gate WP-1 and WP-2 both cleared before acceptance (WP-2 plan §15). Evidence re-verified today, not quoted from July.**

**Current evidence:** `composer merge-gate` → **PASS** (266 tests · 665 assertions · 101 pre-existing risky notices) · the eleven WP-3A tests re-run → **PASS** (part of 17 passed / 47 assertions with WP-4's six).

### 1. Architecture — PASS

| Check | Evidence |
|---|---|
| Gates | Deptrac **0 violations** · greenfield PHPStan **no errors** · architecture fitness suite green |
| **No fitness rule or Deptrac rule was changed** to make the slice pass | the gate configuration is untouched by this slice |
| Dependency direction | `ChallengeRoutedHydrator` is `Contestation/Infrastructure/Outbox` implementing the shared `EventHydrator`; `ChallengeOutboxAdapter` implements the Application port `ChallengeEventOutbox`. **Infrastructure → Application → Domain, never reversed** |
| Composition root | registration lives in `ContestationServiceProvider::boot()` — the correct seat, not in the domain |

### 2. DDD — PASS

| Check | Evidence |
|---|---|
| Ownership | **unchanged.** Contestation owns `ChallengeRouted`; the hydrator is **context-owned Infrastructure** — contract R-2/R-3 forbids a consumer importing it, and WP-4 later honoured that |
| Aggregates / entities / repositories / domain services | **none added.** `ChallengeRouted` itself is **unmodified** — WP-3A publishes an event that already existed |
| Published Language | **established, both halves:** publication (`writeRouted`) + registration (the hydrator). Either alone would be incomplete |
| Minimality (PB-005 F-2) | the reconstructed event carries **exactly the three facts routing records**; publication-time enrichment stays in the Application layer — `ChallengeResolvedIntegration` is the counter-example that proves the rule |
| Ubiquitous Language | **unchanged** — no term renamed, introduced or redefined |
| ADR-T16 | only primitives cross the wire; identity travels as a string |

### 3. Trustworthiness — PASS

| Property | Evidence |
|---|---|
| **Anonymity** (ADR-T11 · AT-Q7) | **two tests, contract and wire:** `test_the_wire_contract_carries_no_voter_or_vote_identifier` · `test_the_published_row_contains_no_voter_or_vote_identifier`. The payload is `schema_version` · `challengeId` · `routedTo` · `occurredAt` — **no voter, no vote** |
| **Immutability** | `final readonly class ChallengeRouted` |
| **Versioning / replay safety** (ADR-T5) | v1 accepted · **absent marker = v1** · v2 **rejected** · missing required field **fails loudly**. Reconstruction is deterministic |
| **Provenance** (ADR-MP-06) | provenance is **supplied, never minted here** — `test_supplied_chain_start_provenance_is_stamped_unchanged`; `CorrelationIdMintingTest` stays green with the allowlist **unchanged at one entry** |
| **Tenant isolation** | `organisation_id` via `TenantContext::require()` — **fails loudly rather than writing an unscoped row** |
| **Forward-only** | an append-only outbox row at `status=pending`; nothing is mutated or removed |

### Referred out, not resolved here

**`Canonical_Event_Catalog_v1.0.md` is 🧊 FROZEN and still marks `ChallengeRouted` — with `ChallengeAdjudicated` and `ChallengeResolved` — as `internal`.** Correcting it requires a **v1.1 catalog, an ARB act**. WP-3A recorded the staleness and correctly did not edit a frozen artifact. **Whether it is material to acceptance is a governance question, not an engineering one.**

**Progress:** ✔ RED · ✔ **GREEN** · ✔ **merge-gate** · ✔ dev guide · ✔ **triple qualification** · ⏳ ARB slice acceptance.
