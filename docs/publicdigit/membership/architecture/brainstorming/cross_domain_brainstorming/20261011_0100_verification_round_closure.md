# Cross-Domain Architecture: Verification Round Closure

**Date:** 2026-10-11 · **Author:** Claude (architecture discovery; **no production code, no route enabled, no data changed**)
**Closes the round opened by:** `20261011_0035_implementation_readiness_review_v1.5.md` (the readiness review).
**Baseline:** `20261011_0008_cross_domain_discovery_package_v1.5.md` (v1.5).
**Status:** CANDIDATE. Authority: generated. Not ratified. Election is out of scope.

**Labels:**
- **[SRC]** verified in the repository this round;
- **[RUN]** executed this round, with conditions stated;
- **[INF]** inference;
- **[REC]** recommendation;
- **[OPEN]** needs authorised decision.

All model results are **bounded-model evidence**. None is real-application verification.


> **Errata (2026-10-11):** several statements here are corrected by `docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md` §9 (E-1 `valid_from` / `valid_to` are live in the registered **Landlord** geography migration; E-2 the Membership kernel persists only Admit / End; E-3 token creation must cover the separate `create()` statement; E-4 VS-1 re-scoped to `IsMember` / End; E-5 deciding inside the transaction needs a repository-port change). The independent gap reviews behind them are summarised there in §2.

---

## 1. Experiments closed

### 1.1 g11 E6–E8 (run 3)

**Execution conditions:**
- private throwaway PostgreSQL 18.6 cluster (`initdb` in the session scratch directory, TCP disabled, Unix socket only, `fsync=off`), stopped afterwards;
- PHP 8.5.11; READ COMMITTED; `lock_timeout` 60 ms, which stands in for waiting;
- started 00:17, finished in **21 min 50 s**, **exit 0**, complete JSON output;
- **the executed script** was the version before this round's edits: SHA-256 `23e63c81f392e546015927242aa03987f279475ce25cc851d42b99676c56bd49`. The hash printed at the end of that run (`fd1c29d0…`) is of the file as edited **during** the run, not of the code that ran;
- the earlier prediction (that the run would time out with empty output) was **wrong** and is recorded as such.

| Exp. | Question | Protocol | Schedules | Unauthorised | Deadlocks | Reading |
|---|---|---|---|---|---|---|
| E1–E5 | regression | as before | as before | **identical** to runs 1–2 | identical | reproducible |
| **E6** | three sessions, two context token tables (Membership + Governance), post vs suspension vs revocation | plain read in the transaction | 4,200 | **2,300** | 0 | unsafe |
| | | canonical order, `FOR SHARE` | 4,200 | **0** | **0** | safe within bounds |
| **E7** | decide **inside** the transaction vs annulment (WB-10) | inside, plain read | 35 | **16** | 0 | deciding inside is not enough without locks |
| | | inside, after the acquisition phase | 35 | **0** | 0 | safe within bounds |
| **E8** | footprint discovered through a delegation (actor a, delegator b) vs revocation of b's tenure | lock `deleg:a` only, read b unlocked | 35 | **16** | 0 | unsafe |
| | | extend the lock set after discovery (in canonical order) | 35 | **0** | 0 | safe within bounds |

**Limitations:**
- E6 has one writer per token table, so **cross-table deadlock under opposite orders was not exercised**; it is argued from row-lock semantics only [INF].
- E8 has a **one-level** chain and only the "discovered token sorts after the held ones" case. The *restart-with-union* branch and multi-level chains were **not** run.
- The real schema, Laravel and command handlers were not involved.

### 1.2 g11 run 4 (refactored harness: E1–E9)

**Change:**
- tables are created once and reset with `TRUNCATE` per schedule, instead of `DROP` / `CREATE`;
- experiments can be selected (`WB_ONLY`) and emit results incrementally;
- **E9** is new.

**Executed script SHA-256:** `fd1c29d0011f5ef51a0cd3db82d0928b372eb9dc168d4a2a174c9ea6ae8418bd`.

**Outcome: TIMED OUT** (exit 124 after the 3,000 s budget) during E9.
- E1–E8 completed and **reproduced run 3 exactly** with the refactored harness, emitted incrementally.
- **E9 produced no result**, because results were emitted only after both protocols and both variants had finished.
- **Diagnosis:** the three-session E9b (~8,470 schedules) is slower than E6, because the creators block each other on `FOR UPDATE`.
- **Remedy:** run 5 (E9 only, `WB_ONLY=E9`, 5,400 s budget, per-protocol emission; script SHA-256 `c66b1e55…`).
- **E9 is not closed until run 5 reports.**

### 1.3 g12 verified against its artefacts [RUN]

- **Re-run:** script SHA-256 `ada92fb2…` and vectors SHA-256 `745fc839…`, both unchanged.
- **Counts reproduced exactly:** 1,920 vectors; DO and PO 0 unsound PERMIT and 0 unsound DENY; completeness gap 20 (DO) / 22 (PO); **12** DO / PO disagreements.

---

## 2. Corrected ScopeToken ownership contract (Task 2)

### 2.1 The inconsistency

- **readiness review TC-1:** "only the owner increments";
- **TC-5:** a *foreign* guard performs `INSERT … ON CONFLICT DO NOTHING` to bootstrap a missing token. That is a foreign write into the owner's table.
- The bootstrap works (g11 E4), but it violates the ownership rule, and for absent **aggregates** (e.g. "p is not a member of o2") there is no natural per-scope row at all.

### 2.2 Chosen rule: hierarchical scope tokens owned by one context, with an always-present root [REC]

| Question | Rule |
|---|---|
| **Who creates a token** | Only the owning context, inside the transaction that creates the scope's first fact. The child token **is** the aggregate's version row where one exists (Membership: `org_memberships.version` [SRC]). Parent tokens (e.g. `admissions:o` = "a membership was ever created in o") are created by the owner, under its parent lock (below). The **root token** of each context is created at installation (migration seed) and never deleted |
| **Who increments** | Only the owning context, in every fact-changing transaction, including **creation of a child scope**: creating Membership(p, o) bumps `admissions:o` (and creating the first `admissions:o` bumps the context root) |
| **Who may acquire for a decision** | Any context, through the owner's `ScopeTokenPort::acquire(…, SHARED)`: lock only, **no insert, no update** |
| **Guarding an absent scope** | Resolve to the **nearest existing ancestor**: child row absent → parent `admissions:o`; parent absent → context root. Acquire it SHARED; then **re-read absence after the lock**. The decision's read set records `(ancestor, version)` + "absent" |
| **Existing aggregate rows vs absent aggregates** | Present: the child token (aggregate version) is the footprint. Absent: the ancestor token is the footprint. A creator must lock the ancestor `FOR UPDATE` and bump it **before** inserting the child. So a guard holding the ancestor SHARED blocks creation, and a creation that committed first changes the ancestor version → the guard's version check fails → re-decide |
| **Ordering** | Ancestor keys sort before descendant keys (key scheme `<rank>/<level>/<key>`, e.g. `3/0/root` < `3/1/admissions/o2` < `3/2/membership/o2/p`, `COLLATE "C"`). Creators therefore take the ancestor before the child in canonical order; guards take whichever single token they need |
| **Concurrent first creation** | Two creators of Membership(p, o2) both lock `admissions:o2` FOR UPDATE, so they are serialised. The second re-checks under the lock, finds the row, and refuses (INV-M). The existing unique constraint remains as defence in depth |
| **Infrastructure exception** | **None needed.** The guard-upsert of TC-5 (E4) is **withdrawn as the default**; it stays documented as an alternative only if an owner cannot provide an ancestor token |
| **Cost** | every creation of a child scope in o serialises on `admissions:o` (Membership admissions: low frequency); guards take only shared locks, which do not block each other |

**Consequence for the ratified Membership kernel [INF]:**
- today's creation path relies on a unique constraint (Slice 1a creation race) [RPT];
- adopting this rule adds "lock + bump `admissions:o`" to the **creation** command;
- that is an infrastructure change inside Membership, so it needs the **Membership owner's approval** (it changes no domain rule).

### 2.3 Bounded concurrency model: g11 E9 (run 4)

| Exp. | Sessions | Protocol | Schedules | Unauthorised | Duplicate memberships | Deadlocks |
|---|---|---|---|---|---|---|
| E9a absent aggregate ("p not in o2") vs first admission | 2 | ROW_ONLY (lock matching rows; none exist) | 35 | **16** | — | 0 |
| | | PARENT_TOKEN (owner ancestor token) | 35 | **0** | — | 0 |
| E9b guard vs **two concurrent first creations** | 3 | ROW_ONLY | 4,200 | **2,300** | **2,520** | 0 |
| | | PARENT_TOKEN | 4,200 | **0** | **0** | 0 |


**Measured in run 5** (2026-10-11; E9 only; private PostgreSQL 18.6 cluster; script SHA-256 `c66b1e55…`; results emitted per protocol):
- E9a: ROW_ONLY 16 / 35 unauthorised; PARENT_TOKEN **0 / 35**.
- E9b ROW_ONLY: 2,300 / 4,200 unauthorised **and 2,520 duplicate memberships** (INV-M broken without a unique key).
- **E9b PARENT_TOKEN: 0 / 4,200 unauthorised, 0 duplicate memberships, 0 deadlocks.** Run 5 finished with exit 0. Its wall-clock time of 418 min includes a machine suspend (system resume logged 08:11:29).
- **E9 is CLOSED as bounded-model evidence** (two and three sessions; private cluster). Not verified on the real schema or handlers.

**Not modelled:** a missing *parent* token (the root fallback). It follows by the same argument one level up [INF] → FV-12.

---

## 3. XD-13: DO vs PO on the 12 disagreement vectors (Task 3)

**Common rule shape** (`models/g12_test_vectors.json`):
- grant **G2** (priority 2, guard `f1`);
- grant **G1** (priority 1, guard `f2`);
- in #3–#12, an ordinary deny **D1** (priority 1, guard `f2`);
- in #8–#12, a mandatory condition `f1`.

**Organisational reading:** `f1` = "actor is chair of the committee that owns the forum"; `f2` = "the topic is locked". **G2** = "the chair may post"; **D1** = "posting in a locked topic is denied"; **G1** is an incidental lower grant.

| # | Facts (f1, f2) | DO | PO | Group | C / I / A effect | Example |
|---|---|---|---|---|---|---|
| 1 | U(open), T | PERMIT | INDETERMINATE | A | Availability (PO only) | chair status unknown; any member may post anyway. Both completions permit, so **PO is merely incomplete** |
| 2 | U(unavail.), T | PERMIT | DEPENDENCY_FAILURE | A | Availability | same; Governance source down |
| 3 | T, T | **DENY** | **PERMIT** | B | **Integrity**: PO lets a write through that DO forbids | chair posts in a locked topic: PO allows (higher-priority grant overrides the lock), DO refuses |
| 4 | T, U(open) | INDETERMINATE | PERMIT | C | **Integrity under PO** (decides without knowing the lock); Availability under DO | chair posts while the lock status is unknown |
| 5 | T, U(unavail.) | DEPENDENCY_FAILURE | PERMIT | C | as #4 | lock service down |
| 6 | U(open), T | **DENY** | INDETERMINATE | D | Availability under PO | topic locked, chair status unknown: DO is decisive (locked → no one); PO cannot decide |
| 7 | U(unavail.), T | DENY | DEPENDENCY_FAILURE | D | Availability | as #6 |
| 8 | T, T (+ condition f1) | DENY | PERMIT | B | Integrity | as #3 with "only the chair may post here" |
| 9 | T, U(open) (+ cond.) | INDETERMINATE | PERMIT | C | Integrity / Availability | as #4 |
| 10 | T, U(unavail.) (+ cond.) | DEPENDENCY_FAILURE | PERMIT | C | as #5 | |
| 11 | U(open), T (+ cond.) | DENY | INDETERMINATE | D | Availability | as #6 |
| 12 | U(unavail.), T (+ cond.) | DENY | DEPENDENCY_FAILURE | D | Availability | as #7 |

**Findings:**
- **Only group B (#3, #8) is a genuine semantic difference:** a higher-priority grant defeats an equal-or-lower deny. Groups C and D are that same difference seen under unknown facts. Group A is PO's Kleene **incompleteness**, not a semantic difference.
- **No disagreement concerns confidentiality specifically.** The forum write examples are integrity effects. A read-permission analogue of #3 would be a confidentiality effect, e.g. a chair reading an archived restricted topic.
- **Expressiveness:** every PO policy can be expressed in DO by moving the exception into the deny's guard. In the example: D1′ = "locked ∧ ¬chair". DO with compound guards is therefore not less expressive [INF: D is a propositional formula; priorities compile into guards].

**Proposed XD-13 [REC, not approved]:** **DO (deny-overrides) as the platform default; priorities not supported in the first version.**

**Trade-offs:**

| Pro | Con |
|---|---|
| no rule can silently override a deny | exceptions must be written into deny guards, which policy authors must understand |
| one fewer concept for policy authors and auditors | more complex guards |
| decisive in group D | INDETERMINATE in group C, where PO would decide |

Policy lint should flag deny guards that contradict a same-module grant.

**Approval required:** Human PO / ARB.

---

## 4. Transaction contract (TC-1…TC-12): findings (Task 4)

| Area | Bounded-model evidence | Real-application status | Gap / required test |
|---|---|---|---|
| Token ownership and creation | E4 (bootstrap upsert, superseded), **E9** (owner ancestor tokens) | Membership child token = aggregate version row with CAS [SRC] | Membership creation path must lock / bump `admissions:o` (owner approval); VS-1 test DB-T5 |
| Canonical order across context tables | E6: 0 deadlocks, 0 unauthorised (3 sessions, 2 tables, readers vs single-table writers); E3 / E5 single table | none | **cross-table opposite-order writers not run** → FV-11; the port must enforce the order (TC-2), testable as a unit invariant |
| Dynamic footprint expansion | E8 extend-in-order: 0 | none | **restart-with-union branch not run** → FV-13 |
| Delegation-chain revocation | E8 one level | none | multi-level chains → FV-4; bounded depth needs **BQ-DEL** |
| Lock-timeout classification and bounded retry | probe simulates waiting via 55P03 | **Laravel does not classify 55P03; default 1 attempt** [SRC] | application retry wrapper; VS-1 test DB-T7 |
| Atomic domain write + decision evidence | not modelled | none | **contract added (TC-13):** a DecisionRecord for a PERMIT that leads to a write is inserted **in the same transaction** as the write, so both commit or neither does. A DENY / INDETERMINATE / DEPENDENCY_FAILURE record is written in a **separate short transaction after rollback** (PostgreSQL has no autonomous transactions). Test DB-T6 |
| ≥ 3 sessions | E6 (4,200 schedules), E9b | none | real handlers in VS-1 (DB-T4) |
| Real-time revocation (single DB) | E1, E2, E6, E7, E8, E9: 0 under the protocol; proof sketch (v1.5 §5.5) | none | VS-1 DB-T2 / DB-T3 on the real schema |

---

## 5. Replay correctness (Task 5)

### 5.1 Evidence: g13 [RUN]

- **Script:** `models/g13_replay_as_decided.py`, SHA-256 `b8304ce5…`; seeded random; 20,000 histories, 39,933 decisions.
- **Model:** overlapping transactions; `recordedAt` taken at transaction begin; per-scope `seq` assigned at commit.

| Replay strategy | Mismatches with the recorded outcome |
|---|---|
| **per-scope seq ≤ read set + pinned policy version** | **0 / 39,933** |
| `recordedAt ≤ t` + pinned policy | **7,742** |
| hindsight (all facts) | 10,647 differ (expected; not an error) |
| pruned history (policy version or log prefix missing; 8,118 cases) | 5,191 reported RECONSTRUCTION_INCOMPLETE; **0** silent wrong answers. This is **by construction** of the rule in the model, so it illustrates the contract and is not independent evidence |

### 5.2 What AS_DECIDED needs (all must be retained)

1. the **read set**: `{scope → version}`, including ancestor tokens for absent scopes (§2.2);
2. the facts of each scope with `seq ≤ version`: **append-only, never deleted** (Membership INV-6 / INV-14 [RPT]);
3. pinned **PolicyModule** versions (forward-only, TR-2), the **PolicySemantics** version and the **algorithm** id (DO);
4. closed / open-world declaration versions per predicate;
5. `t` (evaluation time) and `v` (valid time used);
6. the **witness** (the rules that fixed the outcome) for comparison;
7. the geography snapshot id when a jurisdiction predicate was used.

### 5.3 Missing today [SRC / INF]

- **No DecisionRecord store exists.**
- No per-scope seq outside Membership. Membership has `seq` and `recorded_at` [SRC `PostgresMemberRepository.php:67,178`].
- No PolicyModule versions, no geography snapshots.
- The live member data is legacy and not cut over [RPT].

### 5.4 Ordering assumptions

| Assumption | Status |
|---|---|
| per-scope seq is assigned under the scope's row lock (commit-consistent) | **holds for Membership** [SRC CAS]; must be required of every owner (TC-7) |
| the read set is a consistent cut across scopes | holds for **guarded** decisions, because all footprint tokens are locked at evaluation; **cached READ decisions** read without locks and so need a single REPEATABLE READ snapshot (TC-14, new) |
| the evaluation clock `t` | only replay-relevant through TR-2 activation times; never used as a fact cutoff |

### 5.5 Erasure

- Legally required erasure of personal data (privacy) can make a replay impossible.
- In that case the result is **RECONSTRUCTION_INCOMPLETE**, never a decision recomputed from current facts.
- Retention and erasure policy: XD-8 [OPEN].

---

## 6. Updated severity-ranked readiness findings

| ID | Sev. | Status after this round |
|---|---|---|
| IR-1 DR-07 (ADR-0001 vs N-6) | P0 | **open:** decision-ready (readiness review §8) |
| IR-2 precedence | P0 | **resolved as a proposal:** XD-13 = DO (§3); approval open |
| IR-3 Laravel retry / 55P03 | P0 | contract TC-9 / TC-10; **untested in the application** (VS-1 DB-T7) |
| IR-4 WB-10 discovery | P0 | extend-in-order verified within bounds (E8); restart branch open (FV-13) |
| IR-5 timestamp replay | P0 | **confirmed** (g13: 7,742 vs 0); contract TR-4 stands |
| **IR-12 (new)** token ownership vs bootstrap | P0 | **resolved by contract:** hierarchical owner tokens (§2), modelled in E9; needs Membership owner approval for the creation-path bump |
| **IR-13 (new)** decision evidence atomicity | P1 | contract TC-13 (§4); untested |
| **IR-14 (new)** cached READ needs a snapshot cut | P1 | contract TC-14 (§5.4) |
| IR-6…IR-11 | P1–P2 | unchanged (readiness review §3) |

---

## 7. VS-1 implementation plan (precise; a plan only)

**Name:** protected forum contribution, test-only.

**Entry decisions (all Human PO / ARB, recorded):**
- **XD-4:** Authorization ownership;
- **XD-7:** K2 / Kleene semantics;
- **XD-11:** outcome taxonomy;
- **XD-12:** ScopeToken contract incl. §2 and TC-1…TC-14;
- **XD-13:** DO;
- **XD-14:** create the Communication context (minimal);
- **XD-15:** a single shared database for this horizon;
- **Membership owner:** approval of the `admissions:o` bump in the creation path, **or** VS-1 restricted to *present-aggregate* guards only (then DB-T5 is dropped and recorded as deferred).

**Entry prerequisites:**
- the isolated test database (same as `PBDIGIT-266`), with provisioning evidence reviewed;
- an EP-01 plan approved;
- the baseline suite green.

**Scope (and nothing else):**
- **command:** `ContributePost`;
- **policy:** one fixture PolicyModule ("non-suspended members may post"), marked as a fixture;
- **PDP:** minimal DO PDP passing the **g12 vectors** for the implemented rule kinds;
- **ScopeTokenPort:** Membership adapter over `org_memberships.version` (+ `admissions:o` if approved); Communication tokens `forum:*`;
- **storage:** a DecisionRecord table;
- **route:** none enabled (feature flag off; no production route).

| Test (all database-backed on the isolated DB, real handlers) | Pass criterion |
|---|---|
| DB-T1 positive | active member → PERMIT; post + DecisionRecord committed together |
| DB-T2 revocation first | suspension commits first → post refused, no post row |
| DB-T3 post first | post holds its locks → suspension waits and commits after the post (A-6) |
| DB-T4 three sessions | post vs suspension vs revocation: no unauthorised post, no deadlock |
| DB-T5 absent scope | first admission racing a guard over "not a member": ancestor-token rule holds (if approved) |
| DB-T6 evidence atomicity | forced failure after the decision → no post, no PERMIT record; the denial record is written separately |
| DB-T7 lock timeout | 55P03 classified → bounded retry → `DEPENDENCY_FAILURE(RETRY_EXHAUSTED)` |
| DB-T8 vectors | the PHP PDP reproduces `expected_DO` for every implemented vector |
| DB-T9 replay | `AS_DECIDED` reproduces 100 % of recorded decisions; an artificially pruned fact → RECONSTRUCTION_INCOMPLETE |
| DB-T10 g11 on the isolated DB | identical counts to the private-cluster runs |

**Rollback criteria:**
- any DB-T failure blocks the exit gate;
- VS-1 code lives behind a disabled feature flag with no route, so rollback = revert the slice commits;
- **no migration that alters existing tables**: new tables only;
- any change to Membership's creation path is a separate, separately approved commit with its own revert.

**Release gate:**
- **no production route until:** (a) DB-T1…DB-T10 green; (b) independent review; (c) Human PO / ARB acceptance; (d) the Membership cutover (ARB-MEM-01) accepted, because live member data is legacy.
- **VS-2** (Governance tenure in the PDP) starts only after DR-07.

---

## 8. Maturity

| Dimension | State |
|---|---|
| Architecture | v1.5 baseline + normative contracts (readiness review §4–§6) + the corrected token contract (§2); one blocker (DR-07) |
| Bounded-model evidence | g7–g13; this round closed E6–E9, g12 and g13. All within stated bounds |
| Real-application verification | **none** for the cross-domain contracts |
| Decisions | **none approved** |
| Implementation | unchanged (Membership kernel, Geography) |
| Evidence gates | **7 / 30**; no gate reached its target level this round (targets require implementation or approval) |

---

## 9. Remaining TODOs

### Security
- Isolated test DB → `PBDIGIT-266` S-0 (committee write paths: items 2–3).
- `PBDIGIT-270` on its own path.

### Decisions
- DR-07.
- **Round 1:** XD-1, XD-4, XD-7, XD-11, XD-12, XD-13, XD-14, XD-15.
- Membership owner: `admissions:o` bump.
- Then RD-1…RD-5 and the remaining XD items.

### Business questions
- BQ-P1, BQ-P2, BQ-P3, BQ-INC, BQ-DEL, BQ-P8, BQ-J1, BQ-U1, BQ-P9, BQ-F1, BQ-F2, BQ-F3.

### Contracts
- TC-13 / TC-14 into the ScopeToken contract.
- Query-port signatures with ancestor footprints.
- DecisionRecord schema incl. the §5.2 retained items.
- PolicyModule activation + lint (incl. the deny-vs-grant contradiction check).

### Formal verification
- **FV-11** cross-table opposite-order writers.
- **FV-12** root fallback for absent parents.
- **FV-13** restart-with-union discovery.
- **FV-4** multi-level delegation (after BQ-DEL).
- **FV-3 / FV-5** after BQ-P1 / BQ-J1.
- **FV-7** g3 after C-1.

### Implementation handover
- VS-1 per §7 after its entry decisions and prerequisites.
- Then VS-2 (after DR-07) and the slices in v1.5 §9.

### Acceptance
- DB-T1…DB-T10 for VS-1.
- Then the v1.5 end-to-end scenarios on the isolated DB and real routes.

---

## 10. Ready / blocked / next action

**Ready now:**
- the S-0 security work (needs only the database);
- review of the contracts in this document and the readiness review;
- the PO / ARB decision round 1, which is fully prepared.

**Blocked:**
- VS-1, by decision round 1 + the isolated DB;
- every Governance slice, by DR-07 (+ RD / BQ);
- any production route, by the Membership cutover and the release gate;
- real-application verification of every contract, by VS-1.

**The single next action with the highest priority:** **provision the isolated test database with reviewed provisioning evidence** (infrastructure owner). It is the common prerequisite of the open security investigation (`PBDIGIT-266`, including two committee write paths in this scope) and of VS-1. Unlike the architecture decisions, it carries a present, live risk. The PO / ARB decision round 1 can run in parallel.

---

## 11. Evidence-gate re-score (2026-10-11 01:20, per the principal review's P2 item)

**Rule (v1.1 §8 and the five status dimensions):** a gate passes only if its target level is reached **and** the evidence was re-run or re-read today. Gate definitions are unchanged (v1.1 §8), so the score stays comparable.

**Re-run today:**
- at HEAD `bc68fae44`: Membership kernel suites `vendor/bin/phpunit tests/Unit/Contexts/Membership/{Domain,Application}/Organisational` → **OK (169 tests, 112,747 assertions)**; the count grew from 144 because other sessions added tests;
- `g3_g4_access_vacancy.py` and `g5_committee_authority.py` exit 0;
- `g8` P3 and `g12` run this round.

| # | Gate | Target | Evidence today | Pass |
|---|---|---|---|---|
| 1 | cross-domain concepts identified | Proposed | v1.5 | ✅ |
| 3 | Membership architecture | Approved | ratification act 2026-10-08 | ✅ |
| 4 | Membership kernel (Slices 0, 1a, 1b) | Implemented + Verified | 169 tests OK today. **Scope: Admit / End only** (erratum E-2) | ✅ |
| 7 | participation / committee research models | Verified | g3, g5 exit 0 today | ✅ |
| 8 | temporal pattern verified for Membership | Verified | kernel suite (exhaustive differential, golden oracle) | ✅ |
| 9 | authorization semantics sound | Verified (bounded) | g8 P1 / P3, g12 (0 unsound) | ✅ |
| **10** | **Geography reference model** | Implemented + Verified | **downgraded:** independent review G-01 / G-01a / G-02 / G-03 show a P0 isolation defect, an isolation test passing for the wrong reason, inconsistent paths and destructive deletes. Passing unit tests do not establish fitness as the jurisdiction reference | ❌ |
| 2, 5, 6, 11–30 | (unchanged definitions) | — | no change in evidence (no decisions approved; nothing implemented) | ❌ |

**Score: 6 / 30** (previously 7 / 30). The drop reflects **stronger evidence about an existing gap**, not a regression in the code.

**Proposed gate additions** (approval needed; not counted):
- G-31: security containment items A-1…A-6 (decision sheet Part A) closed with negative tests;
- G-32: legacy integrity items I-1…I-6 closed with regression tests.
