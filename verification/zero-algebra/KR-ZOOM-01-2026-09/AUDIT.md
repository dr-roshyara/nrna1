# `KR-ZOOM-01` — AUDIT

**Run BEFORE results were interpreted** (`code/audit.py` → `data/audit.json`).
**14/14 gates PASS · 7 DEGENERATE METRICS FOUND.**

> A metric that cannot take more than one value is not evidence. Seven of this experiment's
> measurements are in that condition. They are named here, and the conclusions that rested on
> them are downgraded rather than explained away.

---

## 1. Gates A–P

| gate | result |
|---|---|
| **A** schema validity | PASS |
| **B/C** deterministic replay + seeds | **PASS** — re-execution reproduces the ledger byte-for-byte (`4b93d8f2…`) |
| **D/E** no hidden information, parent/child traceability | PASS — zoom is lookup into generated structure; every row cites its root |
| **F** resolution numbering | PASS |
| **G** traversal typing | PASS |
| **H** **Projection ≠ Zero** | PASS — exclusion recorded as `excluded_dimensions`; every Zero verdict comes from `zero_test()` |
| **I** visibility ≠ influence | PASS — `newly_exposed_dimensions` is descriptive only |
| **J** typed elimination actually applied | PASS — every verdict used $E^-$ and compared the contract observable |
| **K** no scalar determination score | PASS — none anywhere |
| **L** $Q$ fixed where required | PASS — primary arm holds $Q$ **and** the focus set fixed (D1) |
| **M** no post-hoc generator tuning | PASS — the one generator constraint (roots observable under $Q$) was declared in DESIGN §5 before execution |
| **N** controls behave as expected | PASS — A: 356 terminal · B: 844 structured · C: 67 outward-only |
| **O** metrics reproduce from raw ledger | PASS |
| **P** no historical KnowledgeOS files modified | PASS |

### `[DEFECT]` in the audit itself, found and repaired

Gate **P** initially FAILED. The check tested `git status --porcelain` for *any* output. The
prior experiment directories return `??` — **untracked, not modified.** The whole zero-algebra
corpus is untracked (never committed), so the test failed on a clean tree. Corrected to test for
modification/deletion codes only. **Recorded rather than silently fixed**: a gate that cannot
distinguish "untracked" from "modified" would have failed on every future run.

---

## 2. The seven degenerate metrics

### `AUDIT-1` — H1's success criterion never binds

`zoom_admissible == zoom_nontrivial` **exactly**, in both splits (844/844, 850/850). The
generator never emits a child state with fewer than 2 dimensions or 2 dtypes, so the
non-triviality threshold **could not have failed**.

> **Consequence:** H1 is *SUPPORTED IN TESTED REGIME*, but it tested only "does a generated
> child exist", not "is the exposed state non-trivial". The stronger reading is not available.

### `AUDIT-2` — the counterfactual's structural half is DEFINITIONAL

`zoom()` resolves the **host node** containing the anchor dimension and returns that node's
generated children. Eliminating a **non-anchor** dimension changes `K.dims` but **not the host**,
so the exposed children are necessarily identical.

$$\text{exposed\_structure\_differs} = 0/4368 \text{ (train)},\ 0/4428 \text{ (test)} \quad\textbf{is FORCED, not observed.}$$

> **Consequence: the structural half of §13 is AUDIT INVALIDATED.** It cannot be reported as
> evidence that silent dimensions do not affect next-resolution structure. **The observable half
> survives** — eliminating a non-anchor dimension changed the observable in 4.3 % / 3.4 % of cases.
>
> **The operator is too weak to test the counterfactual.** A zoom anchored to a *set* of
> contributing dimensions, rather than one, would be required.

### `AUDIT-3` — `intermediate_state_equal` can only be 0

For $\tau_i \ne \tau_j$ the intermediate state **is** the first zoom's output, which differs
between the two orders by construction. `0/247` and `0/271` are forced; the metric carries no
information. *(It was still reported separately rather than pooled, per D3 — which is what
allowed it to be identified as degenerate.)*

### `AUDIT-4` — H6 is not independent of H4

H6 (`C_Q(K_{r+1}) == x_r`) and H4's `same_observable` class are **the same comparison**,
$x_1 = x_0$, and return identical counts in both splits (238/238, 261/261). **H6 added no
independent measurement as implemented.**

### `AUDIT-5` — H3 is not testable as posed

H3 asks whether the **same** dimension $d_i$ that is Zero at $r$ becomes non-Zero at $r+1$. Zoom
replaces the dimension set wholesale — **no $d_i$ survives into $K_{r+1}$**. What was measured is
the Zero rate of *different* dimensions at $r+1$ (0.777 → 0.847), which is not H3.

> **Consequence: H3 is INCONCLUSIVE.** Testing it requires a dimension identity that persists
> across resolution — a design feature this experiment does not have.

### `AUDIT-6` — CONTROL E cannot exhibit its target

Excluded dtypes are, by construction, a subset of the dtypes $Q$ does not read, so removing them
cannot change the observable: **780/780 and 800/800 excluded dimensions tested Zero, with zero
exceptions.** The design cannot produce an excluded-but-non-Zero dimension.

> This is the **safe** direction — it does not violate *Projection ≠ Zero* — but the control is
> uninformative. The informative case would be an **active** dimension that $Q$ does not read
> (`CONTEXT` here), which was not separately reported.

### `AUDIT-7` — H2 is representable, not discovered

Per-direction termination is drawn independently by the generator, so atomicity transitions are a
property the generator **can produce**. H2 shows the framework can *represent and detect*
resolution-relative atomicity. **It is not evidence that the phenomenon occurs outside the
synthetic domain.**

---

## 3. What survives the audit as genuine measurement

| finding | why it is not definitional |
|---|---|
| **Traversal order never yields the same final state (0/518)** while the observable agrees ~30 % | nothing in the generator forces the observable to agree; the buckets could have disagreed everywhere |
| **Zoom changes the contract observable in ~70 %, loses determination in ~7 %, gains it in 0 %** | the score/bucket rule is fixed and blind to depth; the asymmetry was not designed |
| **Re-basing succeeds ~55 %, with the contract determinate in ~94 % of those** | depends on generated depth structure, not on a rule |
| **Non-anchor elimination changes the observable ~4 %** | a real intervention effect |
| **Contrast arm: 72 % observable change when $Q$ varies** | this is what D1 was protecting against, and it quantifies the protection |

---

## 4. Audit verdict

> **The experiment is reproducible and its gates pass. Its conclusions are materially narrower
> than its hypothesis list suggests: two hypotheses (H1, H2) were tested by criteria that could
> not fail, one (H3) could not be tested at all, one (H6) duplicated another, and half of the
> counterfactual was forced by the operator.**
>
> **The result that survives intact is the order-dependence measurement (§3 of RESULTS).**

Per spec §19: where an audit failure affects a conclusion, the conclusion is marked unresolved
and **not repaired by reinterpretation.** H3 is INCONCLUSIVE; the counterfactual's structural half
is AUDIT INVALIDATED; H1 and H2 carry explicit criterion warnings.
