# Election-Only — Implementation Readiness Gate (Session 3)

**Type:** Implementation-stream readiness reconciliation · **Date:** 2026-08-13 · **Author:** Session 3
**⛔ Analysis only. No production, test, fixture, schema, lifecycle, Constitution, or policy change. No decision resolved. No implementation performed or authorized by this document.**

> **Document status (PO, 2026-08-13): ACCEPTED AS READINESS / RECONCILIATION EVIDENCE — NOT accepted architecture, NOT an accepted domain model, NOT a business decision, NOT authorization for implementation.**

**Streams consumed as evidence (kept separate):** Session 1 = verification (`2026-08-13-election-only-independent-verification.md`, incl. its §10 self-corrections) · Session 2 = governance/authority (`…-ticket-authority-matrix.md`, `…-domain-ownership-analysis.md`, Manifesto) · Session 3 = implementation (`f2c2cc4e`, `db5ec7a8`, boundary audit).

---

## 1 · EM-VOT-002 — final status

| | |
|---|---|
| Business rule | **ADOPTED** (Manifesto §4a, SD-14 = YES) |
| Implementation | **IMPLEMENTED** (`f2c2cc4e`; docs `db5ec7a8`) — both paths, one predicate, no new state |
| Implementation tests | **GREEN** (8/8) |
| Independent verification | ✅ **VERIFIED BY SESSION 1 on both paths** (P0, predicates compared side-by-side; approved-vs-pending distinction confirmed) |

**Two Session 1 criticisms of Session 3, acknowledged without defence:**
1. **Correct:** Session 3 did not run the in-universe five-path regression before committing. My regression evidence (17-name baseline set, factory consumers, A/B-revert attribution) was real but *self-chosen*; the in-universe surface (19 rows, §2) was found by Session 1. Process lesson recorded for the next commission: the frozen-universe paths are part of the regression definition, not optional.
2. **Authorship clarified:** the untracked `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` (6 rows, all ERROR in Session 1's run) **is Session 3's** — the Slice-1 entitlement-pin draft, EP-01-approved-with-conditions but **gated on Manifesto rule IDs and never executed** (stopped before its first RED run). Its 6 ERRORs are unvetted draft state, not evidence about production. It stays untracked, outside the frozen universe, awaiting its gate.

## 2 · Session 1 regression surface — Session 3's disposition view (classification only, nothing fixed)

Surface per Session 1 §10 (corrected): **19 newly non-passing rows; attributable to EM-VOT-002 ≤ 10.** **Terminology rule (PO): Session 3 may classify the implementation consequences; Session 1 remains the source of truth for regression MEASUREMENT.** The table below is Session 3's disposition view over Session 1's numbers — it does not re-measure anything:

| Class | Rows | Session 3 disposition view |
|---|---:|---|
| **A — legitimate consequence of EM-VOT-002** (fixture/rule premise superseded) | **7** | `ElectionLifecycleStateConsistencyTest` + `ElectionStateMachineConsistencyTest` (assert the REPEALED "window ⇒ VotingActive" rule) · `ElectionDashboardAccessTest` · `ElectionPolicyStateAwareTest` ×2 · `CurrentBehaviorTest` ×2 (`can_vote`, `allowed_actions`). These encoded the state EM-VOT-002 declares illegitimate. **Repair = Phase-2 disposition (PO), not Session 3 initiative** |
| **B — genuine regression caused by EM-VOT-002** | **0 identified** | Session 1's strongest B-candidates (the two "architecture" tests) were reclassified by Session 1 itself as superseded-rule tests |
| **C — pre-existing failure** | 0 of the 19 | all 19 were baseline-PASSED (the 17-name feature baseline + 50 unit/arch pre-existing failures are a separate, already-classified population) |
| **D — governance/architecture decision required** | **3** | the three `InvalidElectionStateException` rows (`CurrentBehaviorTest` ×2 incl. `close_voting`; `VoterImportStateGateTest` — an HTTP consumer). **Blocked on EM-OPEN-021; no expected state exists to assert** |
| **E — fixture premise correction** | (contained in A) | per Session 1: category-A rows are not to be "fixed by adding data until green" ahead of disposition |
| **F — unrelated** | **6** | Security-cluster enum/structural rows (`D2_5…`, `D5Resolver…`, `D6…`, `CapabilityPolicyLayer` ×2, `DeviceBindingPolicy`) — mechanism NOT ESTABLISHED, consistent with documented isolation fragility |
| **NOT ESTABLISHED** | **3** | `VoterStrategySnapshotTest` ×3 — not read by anyone; assigning by name is forbidden |

**Session 3 will not repair any of these without an explicit disposition ruling.**

## 3 · EM-OPEN-021 — current evidence (unresolved; not neutral)

- **Observed technical behaviour** (Session 1 trace, §8): window-open + zero approved candidates + completion flags ⇒ **no derivable state** ⇒ `InvalidElectionStateException` on every read; `close_voting` and `suspend` unreachable *from inside* (rule-1 asymmetry); trap **time-bounded** to `[voting_starts_at, voting_ends_at)`; exit 2 (approve a candidacy) conditional, NOT ESTABLISHED.
- **Unresolved business semantics:** what such an election *means* — existing state / holding state / soft-warn (rule-4 precedent) / administrative intervention. **Owner: PO/ARB. Urgent: deferral is no longer neutral** (a live election in this shape is unmanageable for its whole window).
- **Session 3 position:** will not choose, code, or pin any fallback. The 3 category-D rows above wait on this ruling.

## 4 · Election-Only readiness matrix

GREEN = implemented + independently verified · YELLOW = implemented, verification incomplete · RED = known failure/blocker · BLUE = governance/architecture decision required · GREY = outside scope.

**Reading rule (PO):** this matrix answers *"can Election-Only safely proceed through this capability?"* — programme-level readiness. It does **NOT** answer *"which bounded context owns this capability?"*; ownership statements live only in Session 2's accepted ownership analysis, and no row here is a DDD bounded-context conclusion.

| # | Capability | Status | Basis (evidence · authority · open item) |
|---|---|---|---|
| 1 | Election creation / admission workflow | 🟡 + 🔵 | creation + `voter_source_strategy` snapshot work (estate); import executes E2E but silently swallows row errors, non-atomic, no `assigned_by` audit (Session 3 boundary plan §C-open); **admission STATE gated by `BR-1.12`** (PO desk) · `VoterStrategySnapshotTest` ×3 newly failing, unread — NOT ESTABLISHED |
| 2 | Election configuration (windows/timezone) | 🔵 | **59** (which timestamps are constitutional — clock sets disagree 4/4) + **67** (input read as UTC; windows measurably 60–120 min wrong) — both PO decisions (`D-1…D-4`, `EM-OPEN-018`) |
| 3 | Nomination | 🟡 | `complete_nomination` guard verified correct (PBDIGIT-64 evidence) · `EM-OPEN-019` (30 vs 40; implemented threshold observably 40) open |
| 4 | Candidate approval | 🟡 | approval drives EM-VOT-002 fact; whether the approval path itself derives state (EM-OPEN-021 exit 2) NOT ESTABLISHED |
| 5 | **Voting activation (EM-VOT-002)** | ✅ **GREEN** | implemented + **independently verified both paths** — the only GREEN row |
| 6 | Voting lifecycle derivation | 🔴 + 🔵 | derivation not total: 3 throwing rows, HTTP consumer included · **EM-OPEN-021** (urgent, PO) |
| 7 | Voting-time voter eligibility | 🔴 + 🔵 | **65/69**: valid entitlement denied by ambient context (measured A/B); tenant-blind 300s cache; **the one authoritative unaddressed blocker** (Session 2 matrix) · ownership formalisation **AD-2** + `BelongsToTenant` repair-scope — both D-decisions |
| 8 | Election-Only entitlement record | 🟡 + 🔵 | `ElectionMembership` authoritative per adopted `EM-ENT-*`; suspension enforcement incidental (`status` overload, G-2 load-bearing) · **Q3** (exercisability representation) open; Slice-1 pins gated on Manifesto IDs |
| 9 | Voter assignment (admission-time eligibility) | 🟡 | **mechanically enforced** — interface binding routes around the stubs (Session 2 §3.1 correction); mode-aware; estate coverage exists |
| 10 | Voting access (credential/gates) | 🟡 + 🔵 | ballot gate enforced (403/redirect, verified G-1); suspended voter still issued a fresh credential · **Q-E1/Q-E2** (PO + security) |
| 11 | Vote submission / one-vote | 🟡 | enforced on `codes.has_voted` + slug state (estate); **first vote never runtime-verified** (IERVP paused pre-vote); `election_memberships.has_voted` write-never (recorded) |
| 12 | Voting closure | 🔴 | `close_voting` THROWS in the anomalous state (measured) — EM-OPEN-021 blast radius; normal-path closure otherwise in estate |
| 13 | Results / publication | 🔵 | PO ruling on record (publication immutable · visibility hide/show — answers `EM-OPEN-013`); visibility capability unimplemented; **60 `D-2`** integrity defect (deputy can fabricate `results_published_at`) — authorization needs PO re-confirmation |
| 14 | Audit / security | 🟡 | 4 of 6 governance acts write no audit; none reaches the election audit trail; `BR-1.5/1.6` defaultable · Security-cluster test debt (Session 1 F-rows) |
| 15 | Authorization (officer authority) | 🟡 | `manageVoters` election-scoped, server-side (verified); authority-vs-consequence mismatch recorded (BR-1.x); `SD-15` (`has_chief` vs `has_committee_members`) awaiting ratification |
| 16 | Tenant isolation vs election scoping | 🔴 + 🔵 | `BelongsToTenant` platform-fallback carries domain meaning it does not own (Session 2 ownership analysis); 62/65/69 = one root mechanism; repair-scope decision open |

**No row is manufactured GREEN. Exactly one row is GREEN.**

## 5 · Architecture ownership for the next candidate capability (voting-time eligibility, 65/69)

Per Session 2's accepted ownership analysis: the **Election context owns voting-time eligibility resolution by adopted language** (`EM-GOV-001`, `EM-ENT-007`); ambient organisation context is a **forbidden dependency**; infrastructure (`BelongsToTenant` fallback + tenant-blind cache) currently intrudes; the runtime has **two eligibility authorities for two moments** (admission-time: Contexts/Elections chain, live; voting-time: legacy `app/Models` gate, mode-blind) — and three production sites already derive tenant identity *from the election* (precedent, not decision). **Formal ownership (`AD-2`) and repair scope are open D-decisions → per this commission's rule: architecture evidence conflicts ⇒ STOP on this capability.** No folder-name inference used or endorsed.

## 6 · Blocked items

| Item | Blocked by | Owner |
|---|---|---|
| EM-OPEN-021 fallback (and the 3 category-D rows) | business ruling — **urgent** | PO/ARB |
| 65/69 repair (voting-time eligibility) | `AD-2` ownership + `BelongsToTenant` repair-scope (both D) + explicit repair authorization | ARB then PO |
| 68 admission slice | `BR-1.12` | PO |
| Suspension/removal/restore slices | `Q3` · `BR-1.13` · `BR-1.1/1.2` · `BR-1.8` · `Q-E1` · `Q-E2` | PO/ARB |
| Slice-1 entitlement pins (Session 3 draft) | Manifesto rule IDs for the D-ENT clauses | Session 2 |
| 59 / 67 time semantics | timestamp-authority + `D-1…D-4` | PO |
| 60 `D-2` results integrity | PO re-confirmation of authorization | PO |
| Category-A row disposition (7 superseded-rule tests) | Phase-2 disposition ruling | PO / Session 1 |
| Full Membership (everything) | frozen | — |

## 7 · Authorized implementation items

**None currently carry an unambiguous, standing grant.** The EM-VOT-002 grant is consumed (verified, closed). The boundary-plan Slices 2–4 (import integrity · route/org coherence · invitation observability) hold an **EP-01 approval-with-conditions** whose condition 4 requires each invariant to be "already authorised and independent of unresolved governance questions" — see §8.

## 8 · Next-ticket gate (six conditions)

| Candidate | 1 rule adopted | 2 authority known | 3 location known | 4 scope authorized | 5 testable AC | 6 no blocking architecture decision | Verdict |
|---|---|---|---|---|---|---|---|
| 65/69 repair | ✅ | 🟡 AD-2 open | ❌ | ❌ | ✅ | ❌ | **NOT READY** |
| 68 admission | ✅ chain | ✅ | ✅ | ❌ BR-1.12 | ✅ | ✅ | **NOT READY** |
| 60 D-2 | ✅ ruling | ✅ | ✅ | ❌ re-confirm | ✅ | ✅ | **NOT READY** |
| EM-OPEN-021 | ❌ undecided | — | — | — | — | — | not implementable |
| **Boundary-plan Slice 2 (import integrity: surface row errors · atomic per-row writes · `assigned_by/at`)** | 🟡 *no dedicated EM rule; basis = adopted admission workflow (`EM-EO-*`) whose integrity it protects + BR-1.5/1.6 safe default* | ✅ Election context | ✅ `VoterImportService`/`VoterImportController` | 🟡 EP-01 approved **with conditions**; PO re-confirmation required post-EM-VOT-002 | ✅ | ✅ (BR-1.12 not touched — status value unchanged) | **NEAREST-READY, pending one PO confirmation** |

> **Gate result: NO item satisfies all six conditions unambiguously today → per the commission, Session 3 STOPS and does not choose a ticket.**
>
> **EM-OPEN-021 is the most immediate lifecycle-SAFETY decision** (a live election in the anomalous shape is unmanageable for its window). **AD-2 + BR-1.12 is the largest ARCHITECTURAL/READINESS dependency** (together they gate capabilities 1, 7 and 16 — the Election-Only eligibility/admission model itself). These are different kinds of urgency and neither is ranked above the other here. **Slice 2's status is recorded as a fact, not a recommendation (PO, 2026-08-13):** its EP-01 grant is conditional and its rule basis is an interpretation this document may not settle; whether and when to confirm it is entirely the PO's, and this gate does not propose a decision path.

---

**READINESS GATE COMPLETE — nothing implemented, nothing repaired, nothing decided. Session 3 idle pending: EM-OPEN-021 ruling · Slice-2 confirmation · or a new authorized commission.**
