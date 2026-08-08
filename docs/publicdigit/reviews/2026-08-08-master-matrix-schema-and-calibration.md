# Slice 1 Step 2 — Master Matrix: schema, calibration, and the scope gate

**Commission:** Principal Architect, Election Verification Programme (`PBDIGIT-48`, Slice 1 Step 2) · **Date:** 2026-08-08
**Status:** 🔴 **STEP 2 NOT STARTED — BLOCKED ON A PRODUCT OWNER SCOPE DECISION** that the programme itself set as a prerequisite
**Produced instead:** the matrix **schema** (scope-independent) and a **calibration** measuring what one row actually costs

---

## 1 · Why no matrix rows were produced

**Step 1 closed with an explicit gate, recorded by this programme, not invented here:**

> **"SLICE 1 STEP 1 COMPLETE — SCOPE DECISION REQUIRED BEFORE MATRIX CONSTRUCTION."**
> *"A scope decision is required from the Product Owner before matrix construction."* — plan lines 174, 182

**OBSERVED FACT.** No such decision exists. The plan, `CONTEXT.md` and the session logs contain no record of one being taken.

**The three options the programme itself put forward** (plan line 174):

| | Option | Bounded set |
|---|---|---|
| **(a)** | the constitutional core already measured | **826 tests** in 180 files |
| **(b)** | every file referencing Election | **~3,688 tests** in 491 files |
| **(c)** | capability-first cut | matrix the constitutional invariants and journeys; inventory the rest by file, without per-test mapping |

**Choosing among these is a programme decision about depth versus breadth — not an engineering one.** Picking one silently would take a decision the commission's §17 forbids, and would produce exactly the *"partial matrix called complete"* outcome the plan prohibits.

**So this session produced the two things that are useful under *every* option and presuppose *none*.**

---

## 2 · The Master Matrix schema — 30 dimensions

**Scope-independent.** Whichever option is chosen, a row has this shape. **The `Evidence rule` column is the important one** — it states what must be true before a value may be written, and is the defence against Rule 20 (*"the matrix is an evidence artifact, not a confidence-performance artifact"*).

| # | Dimension | Allowed values | Evidence rule |
|---|---|---|---|
| 1 | Test file | path | — |
| 2 | Test name | method | — |
| 3 | **Test intent** | prose | **Read the test and its docblock. Never inferred from filename or directory** |
| 4 | Business capability | capability name · `None` | must map to a Level-0 journey step or a named capability |
| 5 | Business invariant | prose · **`None identified`** | `None identified` is a legitimate and common answer |
| 6 | Domain concept | concept · `n/a` | domain language, not class names |
| 7 | Bounded context | Election · Membership · Security · Shared · `n/a` | |
| 8 | Lifecycle state | one of 12 · `n/a` · `Unknown` | **from `ElectionLifecycle`, never from `state`/`status`/`is_active`** |
| 9 | Capability required | capability · `n/a` | |
| 10 | Actor / role | role · `none` · `system` | |
| 11 | Authorization requirement | prose · `none` | |
| 12 | Entry point | route · command · direct call | |
| 13 | Application use case | handler/service · **`none — HTTP calls domain directly`** | R4: no application layer mediates lifecycle transitions |
| 14 | Decision authority | artifact that owns the rule | R4: **rule content ≠ enforcing mechanism** |
| 15 | **Business Decision Ownership** | Domain · Application · Policy/Authorization · Infrastructure · Interface/Projection · **Unknown** | **Never from the test's directory.** R4 proved `Unit/Application/…` can verify Domain-owned content |
| 16 | Persistence involved | table(s) · `none` | |
| 17 | Persistence role | Authoritative · Derived · Cache · Historical · Audit · Projection · Legacy · **Unknown** | R5 classification; categories **must not** be collapsed |
| 18 | Expected business behaviour | prose | |
| 19 | Actual behaviour | prose · **`Not executed`** | |
| 20 | Test result | pass · fail · error · incomplete · risky · **`Not executed`** | |
| 21 | Failure classification | test/fixture defect · harness · environment · production defect · architectural defect · business decision required · **`Mechanism not established`** · intent unclear · **intentional migration signal** | **`Mechanism not established` is mandatory unless the rejecting boundary was traced** |
| 22 | Fixture validity | valid · **arranges a non-authoritative representation** · Unknown | **not** a defect by itself — see §4 |
| 23 | Authoritative source actually consulted | artifact · **Unknown** | requires tracing execution, not reading the fixture |
| 24 | Legacy representation involved | one of the **six** legacy categories · `none` | categories must stay separate |
| 25 | Existing evidence | link to prior report/cluster | **carry forward; do not re-derive** |
| 26 | Verification strength | business invariant · application capability · authorization · persistence · projection · **technical/implementation** | |
| 27 | Coverage status | prose — *what is exercised* | **descriptive only** |
| 28 | Completeness status | **`Completeness not established`** unless a business contract is named | **normative — requires a named contract** |
| 29 | Architectural concern | prose · `none` | must trace to evidence |
| 30 | Open question | prose · `none` | |

**Three columns carry the programme's hard-won distinctions and must never be merged:** **15** (ownership ≠ location), **17** (persistence role), **27/28** (coverage ≠ completeness).

---

## 3 · Calibration — two rows, fully worked

**Chosen because Relationship 4 named this file as the sharpest ownership case.** Both tests sit in `tests/Unit/Application/Election/ConstitutionalTransitionGuardPreconditionsTest.php`.

### Row 1 — `test_complete_administration_preconditions_use_cached_columns`

| # | Dimension | Value |
|---|---|---|
| 3 | **Test intent** | **Performance.** The docblock says *"RED: Preconditions should use cached count columns, not query DB … This runs 3 EXISTS queries."* It counts SQL queries and asserts **0** |
| 4 | Business capability | `complete_administration` — **referenced, not verified** |
| 5 | **Business invariant** | 🔑 **None identified.** No business truth is asserted |
| 8 | Lifecycle state | `SetupAdministration` — **hand-constructed `ElectionLifecycleSnapshot`, not derived** |
| 14 | Decision authority | `ElectionConstitution` (rule content) · `ConstitutionalTransitionGuard` (mechanism) — R4 |
| 15 | **Business Decision Ownership** | **Domain** (the precondition content) — **though the test lives under `Unit/Application`** |
| 19/20 | Actual behaviour / result | **Not executed** — no suite was run this session |
| 21 | Failure classification | **`Mechanism not established`** — and if it fails, the docblock's `RED:` marks it a **deliberate future-state assertion**, the same family as Cluster 5 |
| 22 | Fixture validity | **arranges a non-authoritative representation** — the snapshot is constructed directly, bypassing derivation. **Legitimate here**: a unit test of the guard needs a controlled snapshot |
| 23 | Authoritative source consulted | the guard, with an **injected** snapshot — **derivation never runs** |
| 26 | **Verification strength** | 🔑 **Technical / implementation** |
| 28 | Completeness status | **Completeness not established** |
| 29 | Architectural concern | Asserts the guard should read **counter columns** — the same divergence R4 Finding 4b recorded (guard **queries**, model reads **counters**). **This test takes a side in an unresolved question** |
| 30 | Open question | Does making the guard use counters conflict with 4b's staleness risk? **Not investigated** |

**Two observations this row makes that a filename-based classification would have inverted:**

1. **By location and name it reads as a constitutional authorization test. By intent it is an N+1 performance probe.**
2. **It explicitly discards the business outcome** — `catch (\Exception $e) { // May fail for role check, but we're testing precondition queries }`. **A test that swallows the authorization result cannot be evidence about authorization.**

### Row 2 — `test_has_committee_members_checks_correct_table`

| # | Dimension | Value |
|---|---|---|
| 3 | **Test intent** | **Data-source correctness.** *"RED: `has_committee_members` should check `election_officers`, not `election_memberships`"* |
| 5 | **Business invariant** | **Candidate invariant:** *committee membership is defined by `election_officers`.* **Whether that is the business rule is NOT established** — the test asserts it; no constitutional artifact was found stating it |
| 15 | Business Decision Ownership | **Unknown** — which store defines committee membership is exactly the open question |
| 17 | Persistence role | `election_officers` vs `election_memberships` — **Unknown which is authoritative** |
| 24 | Legacy representation | **Undetermined** — one of the two may be legacy; not established |
| 26 | Verification strength | **business invariant — candidate**, conditional on the store question |
| 29 | Architectural concern | 🔑 **Same shape as `PBDIGIT-49`** (*voter eligibility has two homes*): a business fact with two plausible stores and no recorded authority |
| 30 | Open question | **Which store defines committee membership?** Recorded as a candidate business decision |

**The contrast is the calibration's real output:** two tests, same file, same class under test — one is a **performance probe with no business invariant**, the other is a **candidate business-invariant test blocked on an unresolved data-authority question**. **No file-level or directory-level rule could have separated them.**

---

## 4 · What the calibration establishes about cost and method

| | |
|---|---|
| **Rows produced** | 2, from one 136-line file |
| **Reading required** | the full test body, its docblock, the class under test, and two prior relationship reports |
| **Columns confidently populated** | ~14 of 30 |
| **Columns honestly `Unknown` / `Not established`** | ~9 |
| **Columns needing execution** | ~5 (19, 20, 21, 23 in part) — **no suite was run this session** |

**INTERPRETATION.** A defensible row is **not** a spreadsheet cell exercise; it requires reading the test, its subject, and the relevant prior evidence. **Option (b) — ~3,688 rows — is therefore a multi-session programme, and the plan already said so.**

**A finding that matters more than the cost:** **≈⅓ of columns were legitimately `Unknown`, and several require *executing* the test to fill.** A matrix built without running the tests will carry `Not executed` in columns 19–21 for every row. **Whether the matrix is built from static reading, from execution, or both, is part of the scope decision** — it was not among the three options and should be.

---

## 5 · What was NOT done, and why

* **No matrix rows beyond the two calibration rows** — the scope gate is unmet.
* **No test suites executed.** Execution is legitimate evidence-gathering under the commission, but running 826–3,688 tests to populate columns 19–21 presupposes the scope decision.
* **No test, fixture or production code changed.**
* **No business decision taken** — BD-1…BD-6 untouched; two *candidate* decisions surfaced (committee-membership store; static-vs-executed matrix).
* **`tests/Feature/Demo`'s 18 tests were not classified** — Relationship 6 requires them read individually, which is matrix work and therefore gated.

## 6 · Business decisions required

| # | Decision |
|---|---|
| **SD-1** | **Which scope: (a) 826 constitutional core · (b) all ~3,688 · (c) capability-first cut?** The programme's own prerequisite, unmet |
| **SD-2** | **Static reading, executed, or both?** Columns 19–21 and part of 23 cannot be filled without running the tests. **Not previously surfaced as an option** |
| **BD-7 (candidate)** | **Which store defines committee membership — `election_officers` or `election_memberships`?** Same shape as `PBDIGIT-49`. Surfaced by calibration Row 2; **not investigated** |

## 7 · Self-audit

| Check | ✓ |
|---|---|
| Test intent established before failure interpretation | ✅ both rows read in full |
| Ownership not inferred from directory | ✅ Row 1: `Unit/Application` file, Domain-owned content |
| Lifecycle state not inferred from legacy fields | ✅ Row 1 records that the snapshot is injected, not derived |
| Coverage not confused with completeness | ✅ column 28 `Completeness not established` in both |
| No hypothesis promoted to conclusion | ✅ Row 2's invariant marked **candidate** |
| Closed evidence carried forward | ✅ R4, R5, R6, `PBDIGIT-49` referenced, not re-derived |
| Demo tests not classified as a group | ✅ not classified at all — gated |
| No business decision taken | ✅ SD-1, SD-2, BD-7 recorded |
| No implementation change | ✅ |
| No scope silently opened **or chosen** | ✅ **this is the report's central point** |
| Unknowns explicitly marked | ✅ ~9 of 30 columns per row |

---

**SLICE 1 — STEP 2 NOT COMPLETE. MASTER MATRIX NOT BUILT — BLOCKED ON SD-1 (SCOPE) AND SD-2 (STATIC VS EXECUTED). SCHEMA AND CALIBRATION DELIVERED. NO IMPLEMENTATION CHANGES — AWAITING PRODUCT OWNER DECISION.**

**Traceability:** plan lines 154–229 (Step 1 scope determination and its gate) · `tests/Unit/Application/Election/ConstitutionalTransitionGuardPreconditionsTest.php:33,102` · Relationship 4 (rule content ≠ mechanism; Finding 4b counters vs queries) · Relationship 5 (persistence-role classification) · Relationship 6 (Demo tests require individual reading) · `PBDIGIT-49` (two-homes shape) · commission §17 (no business decisions), §20 (evidence not confidence)
