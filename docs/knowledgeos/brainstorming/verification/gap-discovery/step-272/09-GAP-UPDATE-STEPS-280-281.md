# 09 — Gap Update from Step 280 (executed) and Step 281

**Tasks: (1) files renamed; (2) gaps updated.** Reviewed as senior mathematician · statistician ·
DDD architect.

---

## PART 1 — Rename complete

**3 files**, content MD5-verified byte-identical 3/3. `phase_measure_theory` now has **zero**
un-normalized files (three directories aside).

| New name | bytes | note |
|---|---:|---|
| `20260830-231840_step_280_end-to-end-empirical-closure-test-extended.md` | 37,340 | supersedes the 23:16 edition, which it contains as a prefix |
| `20260830-233940_step_281_supervisory-commission-missingness-revision-and-gap-closure-verification.md` | 24,466 | HPA commission |
| `20260830-234402_step_281_correction-and-missing-part.md` | 30,764 | the repair specification |

---

## PART 2 — The headline: **Step 280 was executed, and it failed**

$$\boxed{\textbf{EC = NOT ACHIEVED}}$$

Executed by a verification session (`verification/step-280/`, 23:24–23:26), against **both** a
reference implementation and the **running EKP**. Two independent reasons, both observed.

### 2.1 Critical Failure #7 — and it is the gap I raised

```
unknown (no evidence) -> Σ = (Neutral, None)     distinguishable
absent                -> a ∉ 𝒜                    distinguishable
not-asked             -> INDISTINGUISHABLE from absent    *** FAILURE ***
orphan (EKP)          -> representable in EKP, NOT in K   *** no K representation ***
```

**Error category: `T` — theory defect.** Not implementation, not data.

**This is `06` A6 / G-60, empirically confirmed.** I found it by construction on 2026-08-30 22:5x
(*"all three collapse into (0,0); 272B excludes missingness from Σ by design and names no carrier"*);
Step 280 reproduced it deterministically against the real system and classified it. **Independent
convergence by different methods** — mine analytic, theirs empirical.

### 2.2 A fourth kind of missingness the theory cannot represent

> **`orphan_document`** — asserted but unconnected. It **exists in the EKP** and has **no
> representation in `K` at all.**

**The implementation is richer than the theory.** And `orphan_document` is one of the sixteen lint
rules I enumerated in first-order `exec/exp_ekp_bridge.py` EXP-14 — at the time a bare inventory
item; it is now the source of a theory gap.

### 2.3 Only 8 of 24 tests carry real-environment evidence

| Real-environment executability | Count |
|---|---:|
| YES — Level 5 | **5** (E1, E2, E10, E22, E23) |
| PARTIAL — Level 5 | **4** (E4, E8, E12, E14) |
| **NO — NOT OBSERVABLE** | **15** |

And the verdict states the discipline explicitly:

> *"A test that passes against my own implementation of the specification cannot validate the
> specification against the world."*

**This is first-order INV-9 / EV-F2 restated from the other side.** I found that five corpus steps
titled *executable / execute / simulation* contain no program; Step 280 finds that 16 of 24 passes
are Level-4 (against its own reference implementation) and refuses to report them as empirical.
**Same discipline, arrived at independently.**

---

## PART 3 — Gap register movements

### CONFIRMED — my findings, now with empirical evidence

| Gap | My status | Step 280 evidence | New status |
|---|---|---|---|
| **A6 / G-60** missingness carrier | flagged, analytic | **E4 FAIL, CF#7 fired, deterministic** | **CONFIRMED THEORY DEFECT (`T`)** — repair commissioned (Step 281), **not executed** |
| **G-22 / MT-1** no `(Ω,𝓕,P)` | STANDS | **E20 BLOCKED** — calibration inexecutable | **CONFIRMED BLOCKED (`T`)** |
| **G-12 / MT-5** no empirical relational structure | STANDS | same — no measurement construct to exercise | **CONFIRMED** |
| **IR-1** `K` is implemented in the EKP | EXECUTED | Step 280 tested against it: `knowledge-lint` exit 0, 37 docs; `knowledge-graph` 39 nodes / 70 edges, **byte-identical on re-run** | **CONFIRMED + reproducibility added** |
| **EXP-14** the 16 lint rules | inventory | `orphan_document` and `circular_dependency` both now load-bearing | **PROMOTED to evidence** |

### NEW

| ID | Gap | Class |
|---|---|---|
| **G-64** | **Orphan** — asserted-but-unconnected exists in the EKP and has no representation in `K`. The implementation is richer than the theory. | ① theory hole |
| **G-65** | **15 of 24 constructs are NOT OBSERVABLE** in the only running instance — quantified, not suspected | ⑥ evidence gap |
| **G-66** | `authorities.yaml` is **an enum, not an evaluator** — there is no `Authorize()` runtime (E14) | ④ implementation gap |

### STANDING — unchanged by 280/281

`G-02` `K` minimality · `G-03` identity/equality · **`G-56` congruence ≠ sufficiency (still 0
adoption)** · `G-55`/`D-5` `ℛ` 3-field · `G-57` `AuthorityAct` untyped · `G-15` `Context` untyped ·
`G-61` strength cardinality · `G-62` `Compare` undefined · `G-63` citation drift in Step 276's matrix.

### Measurement reconciliation *(minor, but worth recording)*

| Measure | Value | What it counts |
|---|---:|---|
| Step 280 | 39 nodes / 70 edges | `knowledge-graph.php` rendered graph |
| My EXP-11 | 40 cards / 59 relations (52 id-typed) | frontmatter relation entries |

**Not a conflict — different measurements.** The graph tool emits derived/inverse edges and excludes
one node the linter special-cases. Both re-run cleanly. Recording it because "same name, different
measurement" is how the 47-tests figure went wrong.

---

## PART 4 — Three lenses on Step 281's proposed repair

Step 281 requires seven mandatory states:

| | | |
|---|---|---|
| M1 Not Asked | M2 Asked + Absent | M3 Asked + Unknown |
| M4 Supported | M5 Refuted | M6 Conflicted |
| M7 **Orphan** | | |

with three candidate repairs (A: explicit bottom assertion · B: inquiry register · C: third option),
**EXECUTION-READY, not executed**.

### Mathematician

**M4–M6 are exactly `Σ₀ = P({Support, Refute})` minus `(0,0)`.** M1–M3 are the three ways `(0,0)`
arises. So the seven states are **not a new Σ** — they are `Σ₀` with its zero element resolved into
three, plus a structural condition.

$$\text{M1--M3} = \text{three refinements of } (0,0) \qquad \text{M4--M6} = (1,0),(0,1),(1,1) \qquad \text{M7} \notin \Sigma$$

**This preserves my `07` resolution and 272B's derivation rather than overturning them** — and Step
281 says so itself: *"The seventh is **not automatically an epistemic status**."*

**The minimality question it must answer:** is M1/M2/M3 a **three-valued refinement of `(0,0)`**, or
an **independent inquiry-state axis** crossed with `Σ₀`? Candidate B (inquiry register) implies the
latter and would make `Σ` a product again. **Candidate A is the minimal repair; B is the expressive
one.** The corpus's own minimality criterion should decide it, and my `sigma0_check.py` §C already
shows `(0,0)` is where the collapse occurs — so **the repair belongs at `(0,0)`, not across `Σ`.**

### Statistician

**M7 Orphan is not an epistemic state and must not be given one.** It is a **structural property of
the graph** — degree-zero in `ℛ` — and is computable, total and cheap: `orphan(a) ⟺ deg_ℛ(a) = 0`.
Putting it in `Σ` would repeat the category error §272A.7 and §275.12–15 spent four sections removing.

**And the repair does not touch the two blocked items.** E20 is blocked for lack of `(Ω,𝓕,P)`;
seven missingness states do not supply one. **`G-22`/`G-12` are untouched by Step 281 and should not
be reported as advanced by it.**

### DDD architect

**M7 is the strongest signal in this update.** `orphan_document` is an **EKP lint rule** — an
implementation invariant with no counterpart in the domain model. That is a bounded-context leak in
the informative direction: the running context knows something the model does not.

**Recommended framing:** M7 belongs to a **structural-integrity** concern alongside
`circular_dependency` and `relationship_targets_exist` — the same family — not to the epistemic
context. Step 281's own instinct (*"a structural condition of an assertion/document"*) is right;
what it lacks is the **context assignment**.

**And `G-66` sharpens `D-2`:** `authorities.yaml` being *"an enum, not an evaluator"* is precisely why
`AuthorityAct` is the one place innovation is required. Three passes have now said so; Step 280 adds
the executed evidence.

---

## PART 5 — Net position

| | |
|---|---:|
| Gaps **empirically confirmed** (analytic → observed) | **5** |
| Gaps **added** | **3** (G-64, G-65, G-66) |
| Gaps **closed** | **0** — Step 281 is a specification; the repair is not executed |
| Gaps **standing** | 9 |
| **Empirical closure** | **NOT ACHIEVED** |

**The most important change is not in the register.** It is that **the corpus has, for the first
time, run a test against the real system and reported a failure it did not want.** The verdict's own
words:

> *"Governing principle honoured: do not make the empirical data fit the theory. The data did not
> fit, and the theory is reported as answering to it."*

Measured against my first-order finding that the corpus was **99.8 % prose with three executable
artifacts**, and that five steps titled *executable* contained no program: **that has now changed.**
This is the first genuine `EMPIRICALLY OBSERVED` verdict in the programme, and it is negative — which
is what makes it credible.

---

## PART 6 — What I would put to Step 281's execution

1. **Repair at `(0,0)`, not across `Σ`.** M1–M3 refine the zero element; that is where the collapse
   was measured. Candidate A unless a mandatory operation needs the inquiry register's extra
   expressiveness — and that test has not been run.
2. **Do not give M7 an epistemic status.** `orphan(a) ⟺ deg_ℛ(a) = 0` — structural, derived, and it
   belongs with `circular_dependency`, not with `Support`/`Refute`.
3. **Re-run E4 *and* the 15 NOT OBSERVABLE.** A repair verified only at Level 4 reproduces the defect
   the verdict just refused to accept.
4. **Do not report `G-22`/`G-12` as advanced.** Step 281 does not supply a probability space.
5. **Adopt `Sufficient(K, 𝒪, ℐ)` before claiming the repair preserves minimality.** Step 281's
   mandate item 3 asks for invariant preservation; **congruence alone cannot establish it**
   (`second-order` SO-2, still at 0 adoption). Adding M1–M3 changes what `K` must express — which is
   exactly the criterion congruence cannot see.
