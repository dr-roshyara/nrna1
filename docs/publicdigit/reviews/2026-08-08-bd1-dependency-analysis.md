# BD-1 dependency analysis — which tests actually depend on the meaning of `election_state_transitions`?

**Commission:** Principal Architect, `PBDIGIT-48` — a **subset investigation** explicitly authorised as independent of `SD-1`/`SD-2` · **Date:** 2026-08-08
**Status:** COMPLETE — **no production code, test, fixture or schema changed; no test executed**
**Purpose:** discharge the correction that `BD-1` was recorded as a programme blocker without demonstrated dependency

> **`BD-1`:** *Is `election_state_transitions` constitutional audit evidence (Branch A) or implementation history (Branch B)?*

---

## 1 · The candidate set was incomplete

Relationship 5 named **three** files. **The actual reference set is eight.**

| File | Test-shaped methods |
|---|---:|
| `Feature/Admin/ElectionApprovalTest` | 8 |
| `Feature/Election/CapacityApprovalTest` | 8 |
| `Feature/Election/ElectionStateTransitionMigrationTest` | 4 |
| `Feature/Election/ElectionStateTransitionModelTest` | 10 |
| `Feature/Election/ElectionTransitionToMethodTest` | 15 |
| `Feature/Election/StateMachineTransitionAuditTest` | 5 |
| `Feature/Election/VotingButtonsStateMachineIntegrationTest` | 9 |
| `Feature/Election/VotingButtonsStateMachineTest` | 10 |
| **Total** | **69** |

**Denominator, preserved:** 8 files and 69 test-shaped methods **out of ~491 files / ~3,688 test methods referencing Election**. **These are file totals, not counts of BD-1-dependent methods** — a file's other tests may not touch the transition record at all. **Per-method dependency was not established** and would require reading all 69.

⚠️ **Method correction made mid-analysis:** a first count returned **0** for two files and would have understated the set. Those files use PHPUnit's `#[Test]` attribute rather than a `test_` prefix. **The corrected pattern raised the total from 49 to 69.** Recorded because the same undercount would recur in any matrix built with a `test_`-prefix scan.

**OBSERVED FACT.** All eight files **assert on** the record — none merely imports it. Referencing and depending coincide here, which was not assumable in advance.

## 2 · The decisive question, and it is narrower than expected

**`BD-1` changes no test's pass/fail outcome.** Every one of these tests passes or fails identically under Branch A and Branch B — the record is written either way, with the same fields.

> **What `BD-1` changes is what a passing test *means*.**

Concretely, it determines two Master Matrix columns and no others:

* **#5 Business invariant** — *"every governed transition is attributable"* (Branch A) versus **None identified** (Branch B);
* **#26 Verification strength** — **business invariant** (A) versus **technical / implementation** (B).

**CONCLUSION.** **`BD-1` blocks *classification* of a bounded subset. It blocks no execution, no other row, and no other column.**

## 3 · Classification of the eight

| File | Depends on `BD-1`? | Why |
|---|---|---|
| `StateMachineTransitionAuditTest` | ✅ **yes** | asserts a record **is created** for `complete_administration` with correct `from_state`/`to_state`. Branch A → accountability invariant; Branch B → implementation detail |
| `ElectionTransitionToMethodTest` | ✅ **yes** | asserts `transitionTo()` returns an `ElectionStateTransition` and the row is findable |
| `VotingButtonsStateMachineTest` | ✅ **yes** | asserts count `0` before, instance after — *"transition produces a record"* |
| `VotingButtonsStateMachineIntegrationTest` | ✅ **yes** | `assertDatabaseHas('election_state_transitions', …)` ×2 |
| `CapacityApprovalTest` | ✅ **yes** | queries transitions for an election and asserts on them |
| `ElectionApprovalTest` | ✅ **yes** | same shape |
| `ElectionStateTransitionMigrationTest` | ⚠️ **one test only** | table/columns/index are **schema mechanics, BD-1-independent**. But `test_has_no_updated_at_column` asserts immutability — *"should NOT have an updated_at column (immutable)"*. **Under A immutability is a business invariant (evidence must not be alterable); under B it is a persistence choice** |
| `ElectionStateTransitionModelTest` | 🔴 **no** | UUID primary key · `timestamps` flag · `metadata` array cast. **Persistence mechanics under either branch** |

**Summary:** **6 files fully dependent · 1 file dependent in one test · 1 file independent.**

## 4 · What this establishes about the blocker's class

| | |
|---|---|
| **`BD-1` is a SUBSET BLOCKER** | **Demonstrated, not assumed.** Its subset is *"tests asserting that a transition produces a record"* — 7 of 8 referencing files |
| **It is not a programme blocker** | **No evidence found that any row outside this subset depends on it.** The transition record has **no production reader** (Relationship 5), so no capability, authorization, lifecycle or persistence row consults it |
| **It does not block matrix construction** | Rows for these files can be built now with **#5 and #26 marked `Pending BD-1`**. **Nothing else in the schema is affected** |

> **The earlier framing — *"BD-1 should precede Step 2"* — overstated a two-column dependency in one subset as a gate on the whole step.** The corrected statement: **BD-1 must be resolved before those two columns can be filled for these seven files.**

## 5 · What is NOT established

* **Per-method dependency.** 69 is a **file-total**, not a count of dependent methods. Reading all 69 was not authorised or required to answer the blocker-class question.
* **Whether any of the eight currently pass.** **No test was executed** (`SD-2` unresolved).
* **Whether tests outside the reference set depend on the record indirectly** — e.g. via a fixture that asserts transition counts through another helper. **Searched by class and table name only.**
* **`BD-1` itself.** Untouched — it remains the Product Owner's.

## 6 · Self-audit

| Check | ✓ |
|---|---|
| Dependency demonstrated rather than assumed | ✅ eight files read for assertion shape |
| Denominator preserved on every count | ✅ 8/491 files · 69/~3,688 methods, with the file-total caveat |
| A counting error corrected rather than published | ✅ 49 → 69, `#[Test]` style |
| Blocker classified `PROGRAMME` vs `SUBSET` | ✅ **SUBSET**, with its subset named |
| No business decision taken | ✅ `BD-1` untouched |
| No implementation change · no test executed | ✅ |
| Parallel workstream untouched | ✅ `PBDIGIT-63` files not staged |
| Conclusion no stronger than evidence | ✅ per-method dependency explicitly not established |

---

**SUBSET INVESTIGATION COMPLETE — `BD-1` CONFIRMED A SUBSET BLOCKER OVER 7 OF 8 REFERENCING FILES, AFFECTING TWO MATRIX COLUMNS — NO IMPLEMENTATION CHANGES — AWAITING PRODUCT OWNER REVIEW**

**Traceability:** Relationship 5 report §7 (no production reader; the three-file candidate set this analysis corrects) · `tests/Feature/Election/{StateMachineTransitionAuditTest,ElectionTransitionToMethodTest,VotingButtonsStateMachineTest,VotingButtonsStateMachineIntegrationTest,CapacityApprovalTest,ElectionStateTransitionMigrationTest:36,ElectionStateTransitionModelTest}.php` · `tests/Feature/Admin/ElectionApprovalTest.php:137` · Master Matrix schema columns #5, #26 · plan §"Blockers are PROGRAMME or SUBSET"
