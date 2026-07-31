# WP-5 — Contestation Raise Path (raise → admit → route)

**Status:** 📋 **PLAN — awaiting EP-01 approval. No code written, no RED yet.**
**Slice:** WP-5 (EPIC-004 roadmap) · **Context:** Contestation only · **Protocol:** `.claude/IMPLEMENTATION_PROTOCOL.md` (Phases 1–17, FROZEN)
**Architecture status:** CLOSED for correction-loop integration (reopens only on contradictory implementation evidence). This plan therefore **consumes** architecture; it does not produce any.

---

## Phase 1 — Commission Reset

**Closed:** every architecture commission of this milestone (contract audit · strategic validation · layer classification · topology review · post-implementation validation). **Architecture Phase CLOSED.**

**Carried forward (authorities only):** the frozen Cross-Context Integration Contract · ADR-T21 · TP-2 · roadmap §WP-5 · ADR-MP-06 · ADR-T1 · ADR-T16 · PB-006 · the Implementation Protocol.

**NOT carried forward:** any mandate to discover, refine or reclassify architecture. If this slice meets an architectural uncertainty, the protocol's response is **stop and report**, not analyse.

## Phase 2 — Authority Register

| Authority | What it governs here |
|---|---|
| **TP-2** | *Contestation **requests**, never **creates** an adjudication* — the boundary this slice must not cross |
| **ADR-T21** | `ChallengeRouted` is the correction loop's head trigger (published language — already built, WP-3A/WP-4) |
| **Roadmap §WP-5** | Scope: *raise→admit→route application services (aggregate methods exist, test-pinned) + chain-start provenance mint at raise* |
| **ADR-MP-06** | One mint per constitutional conversation; extending the mint allowlist is an **ARB decision** |
| **ADR-T1** | One transaction = one aggregate root + its outbox row(s) |
| **Contract (frozen)** | Publication + registration are producer-owned; only primitives cross |
| **EPIC-004K §3** | PM-1 is Adjudication's; Contestation must not reach into it |

## Phase 3 — Business Understanding

**Capability:** a standing-holder contests an outcome, and the contest is brought before the constitutional authority.

**Objective:** turn a raised grievance into a *routed request for adjudication* — the act that starts the correction loop.

**Business policies (from the aggregate's existing state machine):** a challenge is **raised** before it can be **admitted**; only an admitted (or investigated) challenge may be **routed**; a dismissed challenge is never routed. Illegal transitions throw **without mutation**.

**Invariants:** the challenge's state machine is sovereign · **TP-2** — routing *requests*; Adjudication decides whether an adjudication opens · anonymity is untouched (no voter↔vote linkage anywhere in this slice).

**Ubiquitous language (already in code — nothing renamed):** `raise` · `admit` · `dismiss` · `beginInvestigation` · `route(routedTo)` · `RaiserStandingRef` · `SubmittedContent` · `ContestedOutcomeRef`.

**Ownership:** Contestation owns the challenge lifecycle **up to and including routing**. Everything after routing is Adjudication's.

## Phase 4 — Strategic DDD

Single context: **Contestation**. Its only outward edge in this slice is the **already-built** published-language edge (`ChallengeRouted` → Adjudication). **No Adjudication file is touched.** The relationship pattern is unchanged (Published Language); no new crossing is created, so the frozen contract needs nothing from this slice beyond obedience.

## Phase 5 — Business Model Fidelity

| Dimension | Preserved how |
|---|---|
| Language | Application methods take the aggregate's names — `raise`, `admit`, `route`. No synonyms invented |
| Ownership | Contestation writes only `Challenge`; PM-1 stays Adjudication's |
| Boundaries | No cross-context import; routing publishes, it does not call |
| Invariants | The aggregate keeps every guard; the application service adds none |
| Lifecycle | Application services orchestrate; the state machine decides |
| Authority | Routing is a **request**, per TP-2 |
| Anonymity | Untouched — no voter or vote data enters this path |

## Phase 6 — Architectural Traceability

| Component | Which authority requires it? |
|---|---|
| Raise / admit / route **application services** | Roadmap §WP-5 (explicit); WP-3B's blocker was *"no routing application service exists"* |
| Transactional boundary | ADR-T1 |
| Provenance at the first published act | ADR-MP-06 + roadmap §WP-5 |
| Mint-allowlist update | ADR-MP-06 (extending it is an ARB decision — **requested below**) |
| **Nothing else** | No new VO, no new event, no new port, no new table — see Phase 7 |

## Phase 7 — Simplification Review (reuse before create)

**Everything the slice needs already exists.** Verified by reading the context:

| Need | Existing asset | Create? |
|---|---|---|
| Aggregate behaviour | `Challenge::raise/admit/route` (+ `dismiss`, `beginInvestigation`, `lapse`) | ❌ |
| Value objects | `ChallengeId` · `RaiserStandingRef` · `SubmittedContent` · `ContestedOutcomeRef` · `ElectionId` · `TargetId` · `TargetType` | ❌ |
| Domain events | `ChallengeRaised` · `ChallengeAdmitted` · `ChallengeRouted` | ❌ |
| Persistence | `ChallengeRepository` (+ `nextIdentity`, `save`, `get`, `find`) · `EloquentChallengeRepository` | ❌ |
| Publication | `ChallengeEventOutbox` · `ChallengeOutboxAdapter::writeRouted()` · `ChallengeRoutedHydrator` | ❌ |
| Transaction pattern | Adjudication's `TransactionManager` + `TransactionalAdjudicationService` decorator — the house precedent | ♻️ mirror |

**Shape recommendation:** **one** coordinating application service with three methods (`raise`, `admit`, `route`), mirroring Adjudication's `CoordinatesAdjudication` + transactional decorator — rather than three separate command classes. Grounds: *reuse-before-create* and *ownership-determines-reuse* (promoted behaviours, R-36); the three acts share one aggregate, one repository and one transactional policy.

## Phase 8 — Business Assumption Review

| Interpretation | Class |
|---|---|
| Contestation routes; Adjudication decides | **Explicit authority** (TP-2) |
| Only an admitted/investigated challenge may be routed | **Explicit authority** (the aggregate's guards) |
| **`ChallengeRaised` and `ChallengeAdmitted` are NOT published in this slice** | **Derived implication** — the outbox adapter has no mapping for them (`default => LogicException`), no hydrator is registered, and **no consumer exists**. Publishing them would be a component without authority. *Consequence to handle in Phase 9: raise/admit must persist without enqueueing.* |
| The mint belongs to the **first published act** | **Architectural assumption — and the plan's decision point (below)** |
| **Is the constitutional conversation's origin the RAISE (a citizen's act) or the ROUTE (the request to the authority)?** | **OPEN BUSINESS QUESTION — for the ARB.** Not a technical matter: it decides what the audit trail claims a conversation *is* |

## Phase 9 — Tactical DDD

```
ContestationService (Application, one class, three methods)
  ├─ raise(RaiseChallengeCommand) : ChallengeId
  │     repository->nextIdentity() → Challenge::raise(...) → repository->save()
  │     NO outbox write (Phase 8 derived implication)
  ├─ admit(ChallengeId, at) : void
  │     repository->get() → admit() → save()          NO outbox write
  └─ route(ChallengeId, routedTo, at) : void
        repository->get() → route() → save()
        outbox->enqueue(<provenance>, ChallengeRouted)   ← the ONLY publication
```

Wrapped by a transactional decorator (ADR-T1: one aggregate + its outbox rows per transaction), mirroring `TransactionalAdjudicationService`.

**Open design detail to settle during RED, not now:** the aggregate records `ChallengeRaised`/`ChallengeAdmitted` internally, so `pullEvents()` returns events the adapter cannot map. The slice must therefore **not** blanket-enqueue `pullEvents()` on the raise/admit paths. Which of *drop-unpublished* vs *enqueue-only-mapped* is correct is an implementation choice inside the frozen model; RED will pin the observable behaviour (no `LogicException`, no stray outbox row).

---

## ⚠️ THE DECISION THIS PLAN NEEDS — WP-3B execution model

The ARB asked which execution model applies. **Evidence gathered while planning shows the two options are not equivalent in cost, and that the roadmap's wording hides a schema consequence.**

**The finding:** the roadmap says *"chain-start provenance mint **at raise**"*. But **raise publishes nothing** (Phase 8), and `challenges` has **no correlation column** (verified: `2026_07_09_000001_create_challenges_table.php` has `id`, `organisation_id`, `state`, `determination_id`, `raiser_standing_ref`, `contested_*`, `submitted_content`, `timestamps` — no provenance field).

Therefore:

| Where the mint happens | Consequence |
|---|---|
| **At raise** (roadmap's literal wording) | The correlation must be **persisted on the challenge** to survive until routing → **a schema migration + a new persisted concept** (provenance becomes challenge state). That is a new concept, which the frozen architecture does not authorize |
| **At route** (the first published act) | **No schema change, no new concept.** The mint accompanies the first message that exists |

| Option | Description | Assessment |
|---|---|---|
| **A** | WP-5 builds the raise path only; the mint stays in Adjudication's `CoordinatesAdjudication`; WP-3B follows as its own slice | Defensible, but leaves the loop head minting **downstream of** its own trigger for another slice — the very inversion WP-3B exists to fix |
| **B — RECOMMENDED** | **WP-3B becomes WP-5's final act:** the routing service mints the chain start, `CoordinatesAdjudication`'s mint is removed, and `CorrelationIdMintingTest::CHAIN_ORIGIN_ALLOWLIST` moves from Adjudication's coordinator to Contestation's routing service | WP-3B's stated blocker was *"the existence of a routing application service"* — this slice creates exactly that. Relocating in the same slice keeps the allowlist truthful at every commit, and needs **no schema change** |

**Recommendation: Option B, with the mint at ROUTE rather than at raise** — and the business reading recorded explicitly: *the **business** chain head is the raise (a standing-holder's act); the **conversation** — a messaging concept — begins when the first message is published, which is the routing.*

**If the ARB instead requires the mint at raise, that is a schema change and a new persisted concept, and needs explicit authorization** — I will not introduce it under an implementation commission.

**Also requires ARB approval either way:** extending/moving `CHAIN_ORIGIN_ALLOWLIST` is *"an ARB decision"* by the test's own docblock.

---

## RED plan (Phase 10) — written only after approval

| # | Keystone | Proves |
|---|---|---|
| 1 | A challenge can be **raised** through the application service and is persisted `Raised` | The capability exists |
| 2 | An **admitted** challenge reaches `Admitted`; admitting a dismissed one throws **without mutation** | The aggregate stays sovereign |
| 3 | **Routing** an admitted challenge writes exactly **one** `ChallengeRouted` outbox row with `schema_version: 1` | ADR-T21 + the frozen payload contract |
| 4 | Routing a **non-admitted** challenge throws and writes **no** outbox row | ADR-T1 atomicity + the guards |
| 5 | Raise and admit write **no** outbox row and raise **no** `LogicException` | Phase 8's derived implication |
| 6 | The routed row's **correlation is minted here** and `causation_id` is **null** (chain start, per `EventProvenance::start()`) | ADR-MP-06 — *Option B only* |
| 7 | `CorrelationIdMintingTest` passes with the relocated allowlist, and Adjudication's coordinator **no longer mints** | The one-mint rule stays machine-enforced — *Option B only* |
| 8 | **End-to-end:** route → relay → Adjudication opens exactly one process | The loop head fires from a **production** mint (today WP-4 test-seeds it) |

Keystone 8 is the slice's real prize: it replaces WP-4's test-seeded provenance with the production path.

## Gates (Phase 13–17)

`composer merge-gate` · PHPStan max · Deptrac 0 · Architecture suite (incl. the relocated minting guard) · triple qualification · developer guide `developer_guide/contestation/` + index · **STOP for ARB slice acceptance.**

## Risks

| Risk | Mitigation |
|---|---|
| The mint relocation breaks Adjudication's existing chains | Keystone 7 + the full Architecture suite; Adjudication's *code* is untouched apart from removing a mint, which is WP-3B's ratified purpose |
| `pullEvents()` returns unmappable events | Keystone 5 pins it as observable behaviour |
| Scope creep into a controller/HTTP entry point | Explicitly out of scope — no authority requires it |

## Open questions for the ARB

1. **WP-3B execution model: A or B?** (Recommendation: **B**.)
2. **Mint at ROUTE (recommended, no schema change) or at RAISE (schema change + new persisted concept, needs explicit authorization)?**
3. **Approve moving `CHAIN_ORIGIN_ALLOWLIST`** from `CoordinatesAdjudication` to Contestation's routing service (ADR-MP-06 requires an ARB decision).

---

**Traceability:** roadmap `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-5 · TP-2 · ADR-T21 · ADR-MP-06 · ADR-T1 · frozen contract `docs/architecture/Cross_Context_Integration_Contract.md` · baseline report `engineering/verification/reports/2026-07-31-wp4-post-implementation-contract-validation.md` (ACCEPTED; architecture CLOSED) · WP-4 plan `.claude/plans/WP-4-apm-wiring.md` (WP-3B deferral recorded there) · protocol `.claude/IMPLEMENTATION_PROTOCOL.md`.

---

## ✅ EP-01 APPROVED (ARB, 2026-07-31) — decisions recorded, RED authorized

| Question | Decision |
|---|---|
| WP-3B execution model | **Option B** — WP-3B is WP-5's final act |
| Mint location | **ROUTE** (the first published act); no schema change |
| `CHAIN_ORIGIN_ALLOWLIST` | **Approved** to move to Contestation's routing service in this slice |

### The clarification, recorded verbatim as required

> **Business process origin and integration conversation origin are intentionally different concepts.** The business process begins at `raise`; the integration conversation begins at `route`, when the first published event is emitted.

**Terminology tightened at ARB direction:** the plan no longer says *"business chain head"* / *"conversation head"* — "chain" is ambiguous between business flow and message flow. The two terms are now **Business process origin** (`raise`) and **Integration conversation origin** (`route`).

### Keystone strengthened at ARB direction

Keystone 5 no longer asserts *"no `LogicException`"*. It asserts the **invariant**:

> **Only integration events are published to the outbox.**

Expressed as: after a full `raise → admit → route` sequence, **exactly one** outbox row exists and it is `ChallengeRouted`. This makes `ChallengeRaised`/`ChallengeAdmitted` **intentionally unpublished**, not merely *currently unmapped* — so a future engineer cannot silence an exception by adding a mapping without failing a test that states the intent.

### Plan amendment — two pattern-mirrors the approved decisions require (Phase 6/7 correction)

The plan claimed *"no new port"*. **The approved mint relocation makes that claim wrong, and the correction is recorded rather than quietly absorbed:**

| Addition | Why authority requires it | Why not reuse |
|---|---|---|
| `Contestation\Application\Port\IdentityGenerator` + `Infrastructure\Identity\UuidIdentityGenerator` | The mint needs a correlation id **inside the Application layer**, where facades are banned (house Rule 2) | Adjudication's port cannot be imported — contract **R-1/R-2**. The *pattern* is reused; the class cannot be |
| `Contestation\Application\Port\TransactionManager` + Laravel impl + `TransactionalContestationService` decorator | **ADR-T1** — `route()` saves the aggregate **and** writes its outbox row; they must be atomic. `EloquentChallengeRepository::save()` has no transaction of its own | Same reason |

**Neither is a new architectural *concept*** — both are exact mirrors of Adjudication's existing ports (`IdentityGenerator`, `TransactionManager`, `TransactionalAdjudicationService`), which is what conformance to the frozen contract *requires* here.

### Finding to report at the RED boundary (discovered while reading the mint site)

`CoordinatesAdjudication:65` carries its own condition: `// When ChallengeRouted consumption lands, this becomes EventProvenance::fromConsumed.` WP-4 landed that consumption — **but WP-4's handler calls PM-1 (`openFor`), not `issueDetermination`.** So `issueDetermination` still has **no incoming message** to derive provenance from; the authority decision arrives on a path not yet wired (WP-6). **Consequence:** WP-5 can *add* Contestation's routing service to the allowlist, but **removing** Adjudication's entry would leave `issueDetermination` with no provenance source. Recorded as an implementation-evidence finding for the ARB; **not** acted on unilaterally.

---

## WP-5 RED WRITTEN + CONFIRMED (2026-07-31) — STOP at the RED boundary

**`Tests: 9, Assertions: 2, Errors: 8, Failures: 1.`** Every failure is the *expected* one — the capability does not exist:

- 8 errors: `Target class [App\Contexts\Contestation\Application\Service\ContestationService] does not exist.`
- 1 failure: the mint allowlist does not name `CoordinatesContestation` (keystone 9).

**No test needed correcting this time** — the WP-4 lesson (three Phase-15 "incorrect test" diagnoses from guessed APIs) was applied by reading every signature first: `Challenge::raise/admit/route`, `ChallengeState` cases, `RaiserStandingRef|SubmittedContent|ElectionId|TargetId::fromString`, `ContestedOutcomeRef::of`, `TargetType::ElectionResult`, `EventProvenance::start`, and the org/tenant setup copied from `ContestationReactionMessagingTest`.

| # | Keystone | RED reason |
|---|---|---|
| 1 | raise persists `Raised` | service missing |
| 2 | admit reaches `Admitted` | service missing |
| 3 | illegal admit throws **without mutation** | service missing |
| 4 | route publishes exactly one `ChallengeRouted` (v1 payload) | service missing |
| 5 | refused route publishes **nothing** (ADR-T1 atomicity) | service missing |
| 6 | **only integration events are published** (ARB-strengthened) | service missing |
| 7 | route mints the **integration conversation origin**; `causation_id` null | service missing |
| 8 | **production raise path opens an adjudication** (replaces WP-4's test-seeded mint) | service missing |
| 9 | the allowlist names Contestation's routing service | guard not yet updated |

**Progress:** ✔ Phases 1–9 · ✔ EP-01 approval · ✔ **RED confirmed** · ⏳ GREEN (awaiting report acceptance) · ⏳ gates · ⏳ dev guide · ⏳ slice acceptance.
