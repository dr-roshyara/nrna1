# Formalization Register — Step 3

Per structure: **A** definitions · **B** types · **C** assumptions · **D** derivation · **E** formal representation · **F** falsification · **G** input · **H** output · **I** procedure · **J** status.

---

## `STR-0001` — Promotion  ⛔ `FORMALIZATION_INCOMPLETE`

**A** ⛔ **The formalization was wrong-shaped.** `bar` does not exist: ES-003.2 forbids persisted numeric scores. Corrected candidate: `promote(k) ⟺ qualification_verdict(k) = Accepted ∧ ∃a: grants(a,k)`.
**B** `qualification_verdict: Knowledge → {Accepted, Rejected, Deferred, Research question, Evidence required, Stable}` (closed, 6 values, ES-003.2). `grants: Act × Knowledge → Bool`.
**C** ⚠️ **Unsupported and load-bearing:** that the two conjuncts have *independent* causal engines. ⛔ **`OT-0001`** — if the verdict is assigned by DA/ARB, both conjuncts are governance acts and *"evidence EARNS"* has no independent formal content.
**D** ⛔ Incomplete. The corrected shape is *proposed from* ES-003.2/ES-006.1; the corpus never writes it.
**E** ⚠️ Expressible as a predicate **only after `OT-0001` is settled.**
**F** ✅ exhibit a promotion with one conjunct only.
**G/H/I** ⛔ no promotion instance exists in the window to run against — the arrow is empty or contested.
**J** ⛔ **`FORMALIZATION_INCOMPLETE`** → `WAITING_FOR_CORPUS` *(search: is qualification a DA act?)*

---

## `STR-0002` — Authority as `P × S`  ⭐ `LAB_READY` (narrow)

**A** ✅ `P = {generated, derived}`, `S = {authoritative, provisional, historical}` — partition verified against the artifact's own descriptions.
**B** ✅ `authority: type: enum` (single-valued), `source: authorities.yaml`, each value carrying `rank: 1..5`.
**C** ✅ Explicit: the partition follows the descriptions; single-valuedness follows `type: enum`; no other provenance field exists in `knowledge-schema.yaml`.
**D** ✅ Complete: `|P × S| = 6` states required; one single-valued 5-enum supplies at most one value per document; therefore the conjunction is unsatisfiable.
**E** ✅ Fully formal — a cardinality and satisfiability argument over a finite enum.
**F** ✅ **exhibit one document carrying both a P and an S authority value.** One instance refutes it.
**G** ✅ every governed doc's frontmatter + the three schema files.
**H** ✅ a count: documents satisfying both dimensions. **Predicted: 0.**
**I** ✅ unambiguous — parse frontmatter, partition by value, count.
**J** ⭐ **`LAB_READY`.** ⚠️ **Scope is narrow and must stay narrow:** this validates a *schema defect*, ⛔ **not** the theory of authority. Whether `P × S` is the right algebra remains `PM-1`, open.

---

## `STR-0003` — check-before-admit  ⛔ `SEMANTICALLY_INCOMPLETE`

**A** ⛔ `ALREADY_IN_M` collapses three senses: *derivable* · *distributed* · *permitted-by-axiom*.
**B** ⛔ codomain ill-defined; outcomes **not mutually exclusive** (`PD-3` is new *and* splitting).
**C** ⛔ totality assumed from 5 same-author observations across 3 days.
**D** ⛔ no decision procedure is stated by any file.
**E** ⛔ not expressible as a function until the codomain is partitioned.
**F** ✅ an admission without the check, or a fourth outcome.
**J** ⛔ **`SEMANTICALLY_INCOMPLETE`** → `WAITING_FOR_CORPUS`. **Demoted from *total function* to *observed pattern*.**

---

## `STR-0004` — Append-only with supersession  ⭐ `LAB_READY` (as category 2)

**A** ✅ correction is additive; superseded text preserved; supersession is an edge.
**B** ✅ a DAG over records with `supersedes` edges.
**C** ⚠️ transitivity **untested**; supersession **branches** (`LG-1 → {PM-1, PM-2}`), so it is one-to-many.
**D** ✅ complete for the historical claim.
**E** ✅ `record' = record ∪ {new} ∪ {supersedes: new → old}`.
**F** ✅ a second destructive correction.
**G** ✅ the 15 `IFR-*` records + Step-1 registries. **H** ✅ count of destructive vs additive corrections. **Observed: 1 vs 4.**
**I** ✅ unambiguous.
**J** ⭐ **`LAB_READY` — but only as category 2 (a historical property of the corpus).** ⛔ **Not** a logical invariant: `IFR-0010` is a counterexample inside the window.

---

## `STR-0005` — Monotone counter as a logical clock  ⭐ `LAB_READY`

**A** ✅ `c: Event → ℕ`, strictly increasing. **B** ✅ ℕ, total order.
**C** ⚠️ **global, unforked** — the load-bearing assumption, and the thing the experiment tests.
**D** ✅ complete. **E** ✅ formal.
**F** ✅ **a repeated or decreasing counter value anywhere in the corpus.**
**G** ✅ full-corpus sweep for occurrence-counter mentions. **H** ✅ the value sequence; **predicted: strictly increasing, no repeats.**
**I** ✅ unambiguous. **J** ⭐ **`LAB_READY`** — ⭐ *and its necessity is measured: 45 % of event pairs are date-unorderable.*

---

## `STR-0006` — Recursive containment  ⛔ `INCOMPLETE`

**A** ⛔ "Knowledge Space" undefined in the window. **B** ⛔ unknown. **C** ⛔ n=1.
**D/E/G/H/I** ⛔ nothing to execute. **F** ⚠️ a deployment that is not a Knowledge Space.
**J** ⛔ **`INCOMPLETE`** → `WAITING_FOR_CORPUS`.
