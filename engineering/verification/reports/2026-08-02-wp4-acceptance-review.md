# WP-4A — Acceptance Review

*(Filed as the WP-4 acceptance review; §1 establishes that the roadmap-consistent unit is **WP-4A**, not WP-4.)*

**Date:** 2026-08-02 · **Prepared by:** Principal Architect / DDD Steward / Recording Architect
**Purpose:** determine whether WP-4's implementation satisfies its authorized scope and preserves the approved architecture.
**Status:** **RECOMMENDATION ONLY — no ruling issued. Acceptance is the ARB's act (R-34).**

> **This review exists because `2026-08-02-wp4-state-correction.md` established that WP-4 is not awaiting authorization — it is awaiting acceptance.** **Triple qualification was performed 2026-08-02** (`.claude/plans/WP-4-apm-wiring.md` §Triple Qualification).

---

## 1. What WP-4 delivered — and what it deliberately did not

**Delivered (`c3165ee88`, 2026-07-31):** `ChallengeRoutedReactionHandler` in `app/Contexts/Adjudication/Application/` · registration by `(Adjudication, ChallengeRouted)` in `AdjudicationServiceProvider::boot()`. **The correction loop's head fires.**

**`handle()` is one statement:** reconstruct Adjudication's own `ChallengeRef`, call `openFor()`. **Two components, no more.**

### ⚠️ The executed scope is narrower than roadmap §WP-4 — by decision, recorded before RED

| Roadmap §WP-4 item | State |
|---|---|
| `(Adjudication, ChallengeRouted)` inbox handler + registry | ✅ **delivered** |
| crash-safe conclude→issue seam | ⏸️ **deferred to its own slice** |
| `AdjudicationFailureDeclared` + counterpart + hydrator + catalog entry | ⏸️ **deferred** — no such file exists |
| authority-decision intake port + interim adapter | ⏸️ **the recorded external the other two depend on** |

> **The plan flagged this before RED — *"flagged now rather than discovered mid-slice"*. It is disciplined slicing, not scope silently dropped.**
>
> **It has one consequence: accepting "WP-4" would otherwise accept one of four roadmap items under that name.**

**The Board should not have to assemble the scope statement. Architecture supplies it; the Board approves, defers or rejects it:**

### ⚖️ Governance-consistency check: does the roadmap permit accepting part of a work package?

**Verified against the roadmap. The answer is no — under the parent name.**

**The roadmap uses *slice* and *work package* as the same unit.** *"Triple qualification per slice"* · *"the one-slice-one-review cadence"* · *"STOP for slice acceptance"* · *"each subsequent WP opens only on its predecessor's ARB slice acceptance."* **§8's Phase DoD is *"all eight WPs accepted."*** **No rule anywhere permits a WP to be partially accepted under its own name.**

**But the mechanism exists, has ARB sanction, and has been used twice:**

| Precedent | What the ARB did | Where it left the parent |
|---|---|---|
| **WP-3** (2026-07-30) | split into **WP-3A** (publication) and **WP-3B** (correlation-origin relocation) | **WP-3A accepted by R-67; WP-3B deferred; §WP-3 is NOT closed** |
| **WP-7** | split into **7A · 7B · 7C** (R-59, R-65, R-66) | **all three disposed of → WP-7 CLOSED by R-66** |

> **Both times the split was an explicit ARB act naming the sub-slices — never an implicit narrowing of the parent.** **A parent closes only when every sub-slice is disposed of.**

**Therefore the roadmap-consistent instrument is the WP-3 precedent, not an acceptance of "WP-4":**

> ### Proposed scope of acceptance — for approval as written
>
> **Split roadmap §WP-4 into named sub-slices, and accept the first:**
>
> | | |
> |---|---|
> | **WP-4A — APM inbox wiring** | **ACCEPT.** `ChallengeRoutedReactionHandler` and its consumer-side registration by `(consumerContext='Adjudication', eventType='ChallengeRouted')` — implemented, gated, triple-qualified |
> | **WP-4B — crash-safe conclude→issue seam** | **not accepted; not built.** Depends on the authority-decision intake |
> | **WP-4C — `AdjudicationFailureDeclared`** + integration counterpart · hydrator · catalog entry | **not accepted; not built.** Same dependency |
> | **WP-4D — authority-decision intake port** + interim administrative adapter | **not accepted; not built.** The recorded external the other two depend on |
>
> **§WP-4 remains OPEN and closes only when 4B, 4C and 4D are disposed of — exactly as WP-7 closed and WP-3 has not.**

**Approving that statement settles the question in one act. Rejecting or amending it is equally available. Accepting "WP-4" unqualified is the one option the roadmap does not support** — it would record eight-WP Phase DoD progress that the implementation has not made.

*(This supersedes an earlier draft of this section that proposed accepting "WP-4 as executed". That wording would have narrowed the parent implicitly — the defect both precedents avoided.)*

## 2. Strategic DDD Verification

| Concern | Result |
|---|---|
| Bounded-context ownership | **unchanged** — Adjudication owns its process manager and its own `ChallengeRef` |
| Capability ownership | **unchanged** — PM-1 owns opening an adjudication; the handler owns only wiring |
| Context-map relationships | **the Published Language consumption is completed** — Contestation publishes, Adjudication consumes. **Contestation never names Adjudication** (contract R-7) |
| Ubiquitous Language | **unchanged** |
| Strategic invariants | **preserved** — one mint per conversation (nothing minted here) · anonymity (only `challengeId` crosses) |

## 3. Tactical DDD Verification

| Evidence | Architectural conclusion |
|---|---|
| **Deptrac 0 violations · 571 allowed** | **the near-miss held:** importing Contestation's hydrator would have been the codebase's **first cross-context Infrastructure dependency**. Contract **R-2** is machine-enforced and **stated in no ADR** — a recorded gap, not this slice's defect |
| No aggregate, entity, VO, repository or port added | **the tactical model is consumed, not extended** |
| **Six defended absences**, each in the docblock and dev guide | no outcome translator (G-2) · no `RoutedTo` VO · no Domain import · no hydrator import · no provenance minting · no bespoke `PermanentInboxFailure` |
| Fourth instance of `InboxHandler` + registry | **no new architectural concept** |

**G-2 was independently confirmed by evidence, not only by argument:** the house translator's signature classifies *business conditions* into inbox markers, and this handler raises none. **A translator here would have been a class with no case to handle.**

## 4. Engineering Verification — accepted as reported, and re-verified

| | |
|---|---|
| Tests | **6 tests / 10 assertions GREEN — first run, no iteration**; re-run 2026-08-02, still PASS |
| `composer merge-gate` | **PASS** — 266 · 665 · 101 pre-existing risky notices |
| Developer guide (DoD) | `developer_guide/adjudication/04_challenge_routed_consumption.md` + index |
| **Triple qualification** | ✅ **Architecture · DDD · Trustworthiness — all PASS** (2026-08-02) |
| Post-implementation contract validation | run 2026-07-31; produced **D-1** |

**Keystone 5 was removed with its reason recorded rather than silently dropped** — the relay hydrates producer-side before dispatch, so a malformed payload cannot reach the handler. **That is a withdrawn test with a defended reason, which is the correct handling.**

**D-1 is worth the ARB's attention:** the integration contract §5 specified provenance via `fromConsumed()`; **observed: no provenance call at all**, because WP-4 publishes nothing. **The invariant (R-6) is unharmed; an extrapolated spec row over-reached.** *Derived rules survived first contact; extrapolated rows did not.*

## 5. Classification

| Finding | Classification |
|---|---|
| Handler + registration | **Engineering** |
| **Executed scope ≠ roadmap §WP-4 scope** (§1) | **Governance** — the acceptance must name what it accepts |
| Contract §5's over-reaching row (**D-1**) | **Documentation** — the contract, not the code |
| **AD-007** relay failure path × single-transaction harness | **Engineering / environment** — recorded debt, production code correctly unchanged |
| **AD-008** 17 pre-existing Membership failures, git-stash-verified | **Engineering** — pre-existing, not introduced |
| Authorization recorded in plan + session log, **not in the register** | **Governance — recording gap** (R-62 class). **Session-log evidence is corroborating, never constitutive** |

**No finding is classified as Architecture — none was observed.**

## 6. Decision Matrix

| Decision question | Result | Evidence |
|---|---|---|
| Executed scope implemented? | ✅ | plan §GREEN, every RED failure mapped to exactly one missing responsibility |
| Architecture preserved? | ✅ | §2–3 |
| Definition of Done complete? | ✅ | triple qualification 2026-08-02 |
| Unauthorized changes introduced? | ❌ | none observed |
| Outstanding items? | ⚠️ | **the scope-naming question (§1)** · **D-1's contract correction** |

## 7. Recommendation

> **The ARB is recommended to adopt the §1 split as written and to accept WP-4A** — the `(Adjudication, ChallengeRouted)` inbox handler and its consumer-side registration — **leaving §WP-4 open until WP-4B, WP-4C and WP-4D are disposed of.**
>
> **Dependency order matters: WP-3A's published language is what WP-4 consumes. Accept WP-3A first.**
>
> **Engineering supplied the evidence; architecture supplies this recommendation; the ARB decides.**

**Identifier:** **not assigned here.** **R-67 was reserved for *WP-4 authorization*, and authorization is moot.** **Rulings are not minted by inference (R-34).**

---

**Traceability:** `c3165ee88` · `.claude/plans/WP-4-apm-wiring.md` §G-2 · §RED · §GREEN · §Triple Qualification · `2026-08-02-wp4-state-correction.md` · `docs/architecture/Cross_Context_Integration_Contract.md` §5 (**D-1**) · `docs/implementation/Architecture_Debt_Backlog.md` (**AD-007 · AD-008**) · **ADR-T21 · ADR-T16 · ADR-T3/T4 · ADR-MP-06 · PB-006 · EPIC-004K §3 PM-1** · **R-34 · R-62** · `composer merge-gate` PASS 2026-08-02. **No architecture redesigned · no ruling issued · no implementation changed.**
