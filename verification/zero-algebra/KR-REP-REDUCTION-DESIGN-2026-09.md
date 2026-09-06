# `KR-REP-REDUCTION-2026-09` — EXPERIMENTAL DESIGN

**DESIGN ONLY. No code written, no dataset generated, no implementation modified.**
**REVISED IN PLACE 2026-09-03**, twice, against the newer KR-ZERO mechanism findings.
**Status remains `DESIGN ONLY`.**

| revision | changed |
|---|---|
| **R1** — 15 review points | §2 (`H-A` neutral, `H-E` scope-bound) · **§5.1 decile reference closed analytically** · §9.1 (reduction ≠ semantic subordination) · §20 (relationship to the mechanism theory) · verdict made conditional |
| **R3** — formal foundation | **new §0** — adequacy · no-excess · the `H(T)≥H(Q)` decomposition · `Q-equivalent` not `Q-isomorphic` · adequacy≠realization · source-vs-representation typing · **and §0.4, what none of it establishes** |
| **R2** — 4 pre-implementation corrections | **§20.4a** — `H-RR1`/`H-RR2`/`H-RR3` named, with **`H-RR2` weakened from implication to ASSOCIATION** · **§20.4b** — epistemic separation as a standing rule · `H-RR3` promoted to conceptual centrepiece |

> **Correction 2 of R2 ("make the decile reference explicit") was already closed in R1 at §5.1** —
> `TOTALS(V,n)` is enumerated analytically, 34 elements, no sample dependence.
**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTABLE · nothing adopted.**

**Prior:** `CORPUS-THEORY-AUDIT.md` — the old KR-ZERO `R1..R4` are **parallel representation classes**,
proven twice, and **must not be reused**. This hierarchy is defined from first principles below.

---

# 0. Formal foundation — **theoretical results, adopted as definitions**

**Added in revision R3.** These are the **mathematical** foundation of the experiment. They are
**definitions and theorems under stated assumptions** — *not* evidence that the `R⁵→R²` chain, the
carrier, the boundary, or the role of `Zero` are validated. §0.4 keeps that separation explicit.

## 0.1 Adequacy, excess, and the decomposition

```
ADEQUACY                    H( Q(D) | T(D) ) = 0
        the representation contains enough to recover the inquiry answer

NO REPRESENTATIONAL EXCESS  H( T(D) | Q(D) ) = 0
        the representation contains nothing beyond Q

DECOMPOSITION (deterministic T, adequacy holding)
        H( T(D) )  =  H( Q(D) )  +  H( T(D) | Q(D) )
                   ≥  H( Q(D) )
```

**`H(T(D)|Q(D))` is the *inquiry-extraneous* content** — extraneous **relative to `Q`**, never
"superfluous" in any absolute sense.

## 0.2 Terminology rule — **`Q-equivalent`, never `Q-isomorphic`**

Where `Q` factors through `T` as `Q|_{admissible} = O* ∘ T`, the correct terms are
**`Q-equivalent`** or **`mutually recoverable`**.

> ⚠️ **`Q-isomorphic` is FORBIDDEN unless an actual bijection has been established on an explicitly
> defined image or quotient.** *(Checked: the term appears nowhere in this document. The rule exists
> so it cannot creep into the report.)*

## 0.2b Terminology rule — **`Zero` is a PREDICATE, never "an equivalence relation"**

> ⚠️ **`Zero` must NOT be described as "an equivalence relation relative to `Q` and `Π`"** unless
> reflexivity, symmetry and transitivity are **proved on a precisely defined domain**.

**As it stands `Zero_{T,Π}(S;D)` is an eliminability PREDICATE / relation — nothing more.** Calling it
an equivalence relation would import a quotient structure that has not been established, and
**`FR-001` gives positive reason to doubt it**: tolerance relations are non-transitive, and any
ε-relaxation of preservation is a tolerance relation.

*(Checked: the phrase appears nowhere in the current artifacts. **The rule is prophylactic** — like
§0.2's `Q-isomorphic` rule — because the place it would do damage is a future report.)*

## 0.3 Two type disciplines that must not be relaxed

**(a) Adequacy ≠ Contract Realization.**

```
adequacy      ∃ O* recovering Q                     — a statement about INFORMATION
realization   the FIXED, committed O recovers Q     — a statement about an OPERATOR
```

`H(Q|T)=0` **does not** imply `F(T)=1`. §7 makes the gap observable by design.

**(b) Source space vs representation space.**

```
D , E_S(D)  ∈  X            the SOURCE space
T(D) , Rⁿ   ∈  Y_n          the REPRESENTATION space at level n
Q(D)        ∈  Answers
```

> The fiber statistic is typed accordingly: `N_viol` compares **`Rⁿ(D_a)` in `Y_n`** against
> **`Q(D_a)` in `Answers`**, indexed by source cases in `X`. **It never equates an object of `X` with
> an object of `Y_n`.** *(Checked: §11's definition already respects this.)* And `E_S` acts **within
> `X`, or within `Y_n` at its own level (§14) — never across the boundary.**

## 0.4 ⚠️ What §0 does NOT establish

> **These results are mathematics. They say nothing about whether the `R⁵→R²` chain is the right
> chain, whether the carrier is the right carrier, where the boundary falls, or what `Zero` does
> there.** Those are **experimental questions**, and §0 must never be cited as having settled any of
> them.

**The three-register discipline for this experiment:**

| register | content | grade |
|---|---|---|
| **mathematical** | §0 — adequacy, excess, decomposition, the two type disciplines | **theoretical definitions/results under stated assumptions** |
| **empirical, prior** | KR-ZERO — `Zero` is context/`T`/`Π`-relative; relational structure implicated | **`[EXP]`, constraints and hypotheses only** |
| **to be tested here** | the boundary · the role of `Zero` · the carrier · monotonicity · the validity of the chain | **`[OPEN]` — this experiment's job** |

> **KR-ZERO informs KR-REP-REDUCTION; it does not define it. §0 grounds it; it does not validate it.**

---

# 1. Research question

> **At which stage of an explicit sequential reduction `D → R5 → R4 → R3 → R2` does the
> representation cease to preserve the inquiry `Q` under the contract `Π = (Q, C, O)`?**

And, separately measured: **which of the three failure modes occurs first** — representation, contract,
or operator.

# 2. Hypotheses — stated so they can fail

| | hypothesis | how it dies |
|---|---|---|
| **H-A** *(revised — neutral)* | **`∃ n ∈ {5,4,3,2} : Rⁿ is adequate and Rⁿ⁻¹ is not.`** The design does **not** predict *where* | **all four adequate**, or **none adequate** — either is an informative result, not a failure of the experiment |
| **H-B** | realization `F_n=1` fails **before** adequacy — the fixed `O` breaks while information survives | `F_n` and `A_n` fail together at every stage |
| **H-C** | adequacy is **non-monotone** along the chain | it is monotone in every run |
| **H-D** | the reduction dimensions (bytes, cardinality, fields, `Ĥ(Rn)`) **do not move together** | they correlate ≈ 1 |
| **H-E** *(revised — scope-bound)* | `Ĥ(Rn) ≥ Ĥ(Q)` at every adequate stage | ⚠️ **an observed violation triggers an AUDIT, not a falsification of Theorem 3.** Theorem 3 concerns `H` under stated assumptions; **a finite-sample biased estimator cannot falsify it.** A violation must first be checked against estimator bias, the CI, and whether the theorem's assumptions hold here |

**Three further hypotheses — `H-RR1`, `H-RR2`, `H-RR3` — bridge to the KR-ZERO findings and are stated
with their grades in §20.4a.** `H-RR2` is an **association**, deliberately not an implication.

**H-C is the one worth the experiment.** §10 of the mandate forbids assuming monotonicity; the design
below makes non-monotonicity *possible* rather than assuming it away — see §8.

# 3. Carrier `X`

```
X  =  finite sequences of measurement records

D  =  ( r₁, …, r_n )        r_i = ( v_i , s_i , t_i )

v_i ∈ V     a measured magnitude, written base-10, ≤ 5 digits
s_i ∈ S     a source label
t_i ∈ T     a timestamp
```

**Why suitable:** it has **three separable kinds of structure** — magnitude, provenance, time — so
reduction can destroy them **independently and in a chosen order**, and an inquiry can be built that
depends on some but not all.

**⚠️ `E_S(D) = D ∖ S` is NOT assumed.** Where elimination is needed (§14) an explicit typed operator
is given.

# 4. Source representation `D` — concrete, not abstract

```
n = 3 records per case
V = 12 distinct 5-digit integers          (calibrated — §12.1)
S = { A , B , C }
T = 8 discrete timestamps
```

A case is an **ordered triple** of records. `|X| = (12 × 3 × 8)³ = 288³ = 23 887 872` before
timestamp collapse; the value-and-source projection has `(12×3)³ = 46 656` states — **the number that
matters, and it is calibrated in §12.1.**

# 5. Inquiry `Q` — **defined before any transformation, computable from `D` alone**

```
Q(D)  =  ( argmax_source(D) , decile(total(D)) )

argmax_source(D) = the source s of the record with the largest v      (ties → "TIE")
total(D)         = Σ v_i
decile(x)        = the decile of x within TOTALS(V, n)              ← see below
```

## 5.1 ⚠️ The decile reference population — **fixed analytically, NOT from the sample**

The first draft left `rank(x)` under-specified, which would have made `Q` depend on the generated
dataset — **destroying the requirement that `Q` be a function of `D` alone.** Corrected:

```
TOTALS(V, n)  =  { Σ c : c ∈ multisets of size n drawn from V }     ENUMERATED ANALYTICALLY
decile(x)     =  min(9, ⌊10 · |{ t ∈ TOTALS : t < x }| / |TOTALS| ⌋)
```

**`TOTALS` is computed from `(V, n)` alone** — no sample, no population, no split. With `|V| = 12`,
`n = 3` it has **34 elements**, and decile occupancy is `{0:4, 1:3, 2:4, 3:3, 4:3, 5:4, 6:3, 7:4,
8:3, 9:3}` — near-uniform by construction.

> **Consequence: `Q` is a deterministic function of `D`, identical on `TRAIN` and `TEST`, and fixed
> before any transformation exists.** `FT-1` asserts it.

**What it asks for:** *who reported the largest value*, and *how large the case is overall*.

**Two components on purpose.** The first needs **relative order + provenance**; the second needs
**magnitude**. A reduction can destroy one and keep the other — which is what makes a *partial*
boundary observable instead of a single all-or-nothing flip.

> **`Q` reads only `D`. It never references `T5..T2`, `R5..R2`, `C` or `O`.** Factor-fidelity test
> `FT-1` (§16) asserts this mechanically.

# 6. Contract `C` — admissibility

```
C(R) = 1  iff   (i)   R is well-typed at its declared level schema
                (ii)  every source present in D is still represented in R
                (iii) |R| = n                      (no records silently dropped)
```

**`C` is evaluated on `R` alone.** A level that drops a source, changes arity, or emits a field its
schema does not declare is **inadmissible** — independently of whether `Q` survives.

# 7. Fixed decoder `O` — and why it is not `O*`

```
O(R) = ( source of the record with the largest NUMERIC FIELD present in R ,
         decile( Σ numeric field ) )
```

**`O` is fixed in advance and is deliberately naive**: it always sums the numeric field and always
takes an argmax of it.

> **`O` ≠ `O*`.** `O*` is *any* decoder that recovers `Q`; its existence is what adequacy
> (`Ĥ(Q|R)=0`) asserts. **`O` is a specific committed operator.** At `R2` the numeric field is a
> **rank**, so `Σ rank` is not a magnitude and `O`'s decile component must fail — **while the argmax
> component still succeeds, and while `O*` may still exist.**
>
> **This is the design's mechanism for making the OPERATOR BOUNDARY observable separately from the
> representation boundary** (mandate §23).

# 8. Transformations — a genuine chain, each consuming the previous output

```
R5 = T5(D)     T5 : drop timestamps                     ( t_i removed )
R4 = T4(R5)    T4 : round v to 3 significant digits
R3 = T3(R4)    T3 : round v to 2 significant digits
R2 = T2(R3)    T2 : replace v by its within-case RANK ∈ {1,2,3}
```

**Every `Tn` takes `R_{n+1}` as input. None is defined on `D` directly except `T5`.** `FT-2` asserts
this by recomputation.

## 8.1 Why this chain can produce non-monotone adequacy — `H-C`

**Rounding creates ties; ranks cannot.** At `R3` two values may round to the same 2-significant-digit
value, making `argmax_source` ambiguous → **`Q`'s first component can fail at `R3`**. At `R2` the rank
is a total order by construction → **`argmax_source` is recoverable again.**

> **So the argmax component may fail at `R3` and RECOVER at `R2`, while the decile component
> monotonically degrades.** **The design admits non-monotonicity; it does not guarantee it.** If
> adequacy turns out monotone, `H-C` dies — which is the point.

# 9. What "reduction" means — **four dimensions, kept separate**

| dimension | definition | why separate |
|---|---|---|
| **encoded size** | bytes of the canonical serialization | `T5` cuts bytes without touching magnitude |
| **cardinality** | `|image(Rn)|` over the population | collapses fastest under rounding |
| **structural complexity** | number of declared fields per record | only `T5` changes it |
| **Shannon entropy** | `Ĥ(Rn)` | **not** a proxy for any of the above |

## 9.1 ⚠️ Reduction is NOT semantic subordination

> ## `Rⁿ⁻¹` need not be semantically subordinate to `Rⁿ`.

`T2` replaces a magnitude by a **within-case rank**. That is not "the same information with less
resolution" — it is **a different representation language**. `R2` may *preserve* `argmax_source`
exactly while *destroying* magnitude semantics entirely.

**So a stage can be simultaneously more adequate on one component of `Q` and less on another.** The
chain is an ordering of *transformations applied*, **not** an ordering of *informativeness*. Any
reading of `R5 → R2` as monotone degradation is a category error, and §12 refuses to assume it.

> **`H-D` predicts these will diverge.** Mandate §19/§25: *do not equate fewer digits with less
> information*. **A rank field has 3 states; a 2-significant-digit field may have 6 — so `R2` can have
> LOWER cardinality but a DIFFERENT entropy profile than `R3`.** Reported as four columns, never
> summed into one "reduction" score.

# 10. Metrics — the full vector at every stage

```
M(Rn) = ( A_n , F_n , Ĥ(Q|Rn) , Ĥ(Rn|Q) , Ĥ(Rn) , N_viol(Rn) , n )
```

| quantity | computation |
|---|---|
| `A_n` | fraction of cases with `C(Rn)=1` |
| `F_n` | fraction with `C(Rn)=1` **and** `O(Rn) = Q(D)` |
| `Ĥ(Q\|Rn)` | plug-in conditional entropy over observed fibers, **Miller–Madow bias-corrected**, with a bootstrap CI |
| `Ĥ(Rn\|Q)` | same, transposed — *inquiry-extraneous representation relative to `Q`* |
| `Ĥ(Rn)` | plug-in marginal entropy, bias-corrected |
| `N_viol(Rn)` | §11 |

**Never** reported as `H(·)`. **Always** `Ĥ(·)` with an interval (mandate §24).

# 11. Fiber analysis

```
N_viol(Rn) = #{ (a,b) : Rn(D_a) = Rn(D_b)  ∧  Q(D_a) ≠ Q(D_b) }
```

A fiber is an equivalence class of cases sharing an `Rn` value. **Two cases in one fiber with
different `Q` prove that no decoder whatsoever can separate them** — that is representation failure,
independent of `O`. `N_viol` is the **structural audit**; `Ĥ(Q|Rn)` is its information-theoretic
summary. **Both are reported**, and they must agree: `N_viol = 0 ⟺ Ĥ(Q|Rn) = 0`. **Disagreement is an
implementation defect, and is a stop condition.**

# 12. Boundary detection — **no monotonicity assumed**

Report the **full adequacy profile** `(A_n, F_n, N_viol(Rn))` for `n = 5,4,3,2`. Then identify:

```
last adequate stage      max{ n : A_n=1 ∧ N_viol(Rn)=0 }
first inadequate stage   the stage after it IN CHAIN ORDER
crossing transformation  B_n : R_n → R_{n-1}
```

> **If adequacy is non-monotone, no single `n*` is reported.** The profile is reported instead, and
> **every** adequate→inadequate crossing is named. Mandate §21/§22.

## 12.1 ⚠️ A design constraint the mandate does not state, and without which the experiment is blind

**If the representation space is large relative to `N`, every fiber is a singleton, `N_viol = 0` at
every level vacuously, and no boundary can ever be detected.** Calibrated at design time:

| parameterisation | `\|space\|` | expected colliding draws at `N=40 000` |
|---|---|---|
| 100 values × 3 records | 27 000 000 | **~30 — BLIND** |
| 20 values × 3 records | 216 000 | ~3 485 |
| **12 values × 3 records** | **46 656** | **~13 139 — adopted** |

And the space collapses further down the chain — `R3` ≈ 5 832 states, `R2` ≈ 729 — **so fibers become
heavily populated exactly where failure is expected.**

> **`V = 12` is not arbitrary. It is the smallest calibration at which fibers collide densely enough
> for `N_viol` to have power, and it is a precondition for the experiment to be able to fail.**

# 13. Held-out methodology

Two **independently seeded** populations: `TRAIN` (seed `A`) and `TEST` (seed `B`), 40 000 cases each,
**generated by the same generator and never mixed**.

- All metrics computed on `TRAIN`.
- **Every adequacy claim re-checked on `TEST`.**
- **`Ĥ(Q|Rn)=0` on `TRAIN` is reported as an empirical observation.** It is promoted to a claim about
  `H(Q|Rn)` **never** — and if `TEST` shows `N_viol > 0` where `TRAIN` showed `0`, that is recorded as
  **direct evidence against generalization**, which is the outcome the split exists to catch.

# 14. `Zero` and the chain — **explicit, not assumed**

If eliminability is measured, the operator is **typed per level**, because the levels have different
record types:

```
E_S^{(n)} : R_n → R_n        removes the records at index set S and RE-INDEXES
```

**`E_S(D) = D ∖ S` is NOT assumed at any level.** At `R2` the numeric field is a **within-case rank**,
so removing a record **changes the ranks of the survivors** — set subtraction is *invalid* there, and
`E_S^{(2)}` must recompute ranks. **This is exactly the case the mandate §10 and §14 warn about, and
it is real in this design, not hypothetical.**

Zero is then `Zero_{Tn,Π}(S; R_n) ⟺ Π-preservation between `R_n` and `E_S^{(n)}(R_n)` — **subset-level,
never assumed element-wise** (KR-ZERO established both failure directions).

# 15. Dataset schema and grain

**Three grains, explicitly distinguished** (mandate §34):

```
cases.jsonl      one row per CASE      case_id, seed, split, D (records), Q(D), provenance
levels.jsonl     one row per (CASE,n)  case_id, n, Rn, A_n, F_n, O(Rn), C(Rn), sizes
fibers.jsonl     one row per (n,fiber) n, fiber_key, member_case_ids, Q-values, conflict flag
subsets.jsonl    optional, §14         case_id, n, S, E_S^{(n)} result, Zero verdict
```

**The FULL population is persisted — both splits, every level, every case.** Aggregates are derived
from these files and are **never** the primary record. *(The KR-ZERO failure was persisting only the
tail; it is not repeated.)*

# 16. Factor-fidelity tests — **the KR-ZERO defect must not recur**

The old generator declared `R1 = token-only` and violated it in **40.2 %** of `R1` cases because three
shapes bypassed the class constructor. **These tests are written BEFORE the generator and must pass on
100 % of rows.**

| id | assertion |
|---|---|
| **FT-1** | `Q` is a function of `D` alone — recomputing `Q` after any chain leaves it unchanged |
| **FT-2** | `R_n = T_n(R_{n+1})` **by recomputation**, for every case and every `n` |
| **FT-3** | **every record at level `n` populates exactly the fields its schema declares — no more, no fewer.** *This is the test that would have caught the KR-ZERO defect* |
| **FT-4** | `C` and `O` read **only** `R` — assert no reference to `D` or `Q` in their inputs |
| **FT-5** | `N_viol(Rn) = 0 ⟺ Ĥ(Q\|Rn) = 0`, per level — the two computations must agree |
| **FT-6** | value alphabet actually has `\|V\| = 12`; source and timestamp alphabets as declared |
| **FT-7** | `TRAIN ∩ TEST = ∅` by case identity and by seed |

**Any FT failure halts the experiment.** No result is reported from a run with a failing fidelity test.

# 17. Falsification criteria — declared in advance

| | claim | falsified by |
|---|---|---|
| **A** | adequacy | `N_viol(R5) > 0` — failure at the very first stage means the chain is mis-specified, not that reduction fails |
| **B** | realization | `F_n = A_n` at every stage — then `O` never fails independently and §7's operator boundary is unobservable, so the design's claim to separate three failure modes is **wrong** |
| **C** | minimal reduction | no stage is adequate, or **all** are — either way there is no boundary to find |
| **D** | information-theoretic optimality | `Ĥ(Rn) < Ĥ(Q)` at an adequate stage — would contradict Theorem 3's empirical shadow |
| **E** | monotone reduction | `H-D`: the four reduction dimensions correlate ≈ 1, making "reduction" one-dimensional after all |

# 18. Threats to validity

| threat | mitigation |
|---|---|
| **entropy estimator bias** — plug-in `Ĥ` is downward-biased on sparse fibers | Miller–Madow correction **and** bootstrap CIs; `N_viol` reported alongside as a **bias-free structural check** |
| **fiber sparsity makes `N_viol=0` vacuous** | §12.1 calibration; **and a positive control: verify at least one level has `N_viol > 0`, else the design is blind** |
| `Ĥ=0` mistaken for `H=0` | §13 held-out; `Ĥ` notation enforced throughout |
| **`Q` chosen to make the boundary fall where wanted** | `Q` is fixed in §5 **before** §8's transformations, and `FT-1` asserts independence |
| **generator does not realize declared factors** | §16, the direct lesson of the KR-ZERO defect |
| `V=12` too small to be representative | **acknowledged and unresolved** — this is a *calibrated toy*, and results transfer to richer alphabets **only by argument, not by this experiment** |
| the chain's order is one of many | only **one** ordering is tested. A different `T` order could give a different boundary; **not tested, and not claimed** |

# 19. Open questions

- Whether the boundary is a property of the **chain order** or of the representation levels. **This
  design tests one order.**
- Whether `Ĥ(Rn|Q)` — *inquiry-extraneous representation* — is monotone. **Not assumed.**
- The carrier question (handoff §26) — **this design commits to one carrier for one experiment and
  does not settle it.**
- Whether `Zero` at reduced levels relates to `Zero` at `D`. **§14 defines the operator; the relation
  is untested.**
- Whether `V=12` results survive at `V=100+`.

---

# 20. Relationship to the new KR-ZERO mechanism theory

**Added in revision.** This section states exactly what is inherited, what is only hypothesized, and
what stays open — so that no KR-ZERO result is smuggled in as an assumption of this experiment.

## 20.1 KR-ZERO is FROZEN EVIDENCE, not the theory of representation reduction

`KR-ZERO-ALGEBRA` / `-GROUP` / `-ORDER` are **prior empirical constraints**. They tested `Zero` under
a *fixed* representation. **They did not test a reduction chain, and none of their aggregates is
evidence for anything in §1–§19.** *(`CORPUS-THEORY-AUDIT.md`: 10 of 18 constructs of the new theory
are **ABSENT** from that corpus.)*

## 20.2 What is INHERITED as established

| | status |
|---|---|
| `Zero` is **context-, transformation- and preservation-relative** | `[EXP]` |
| `Zero(x;D) ∧ Zero(y;D) ⇏ Zero({x,y};D)` | `[EXP]` — robust, 267/746 without the cancelling contract |
| `k` is **not globally monotone** in `\|S\|` | `[EXP]` |
| **`Zero` is predominantly locally determined but NOT universally singleton-determined** | `[EXP]` — the current strongest statement |
| ~~element-wise `T` ⟹ `k=1`~~ | **`[NEG]` FALSIFIED** by 7 witnesses |

**Therefore `Zero` is not treated here as a unary predicate `Zero : D → {0,1}`.**

## 20.3 What is only HYPOTHESIZED — and the exclusion/sufficiency distinction

The mechanism 2×2 found **0 / 33 840** in the `element-wise T × non-cancelling Π` cell.

> ## ⚠️ That is an **EXCLUSION result, not a sufficiency theorem.**
>
> It says: *in the tested family, irreducibility was **not observed** where both `T` and `Π` were
> element-wise.* It does **NOT** say relational structure **causes** higher order, and it does **not**
> license predicting irreducibility from relationality.
>
> **Relationality of `(T, Π)` enters this design as an experimental factor and an interpretive
> hypothesis — never as an established law.**

## 20.4 `Zero ⇒ Adequacy` is a BRIDGE HYPOTHESIS, not an assumption

**Point of fact:** the first draft did **not** state `Zero_{Tn,Π}(S;Rn) ⇒ Adeq(Rⁿ⁻¹)` as a
hypothesis — §14 defined the elimination operator only. **But the placement invited that reading, and
the reading is wrong**, so it is now excluded explicitly:

```
NOT ASSUMED:   Zero  ⇒  Adequacy
TESTED AS:     a bridge hypothesis, measured at the boundary, able to fail
```

**`Zero` is demoted from *predictor of the boundary* to *one measured mechanism at the boundary*.**
The current object of study is:

```
(T, Π, D, S)  ⟶  ℐ_{T,Π}(D,S)  ⟶  Zero
```

with the interaction structure `ℐ` **an open research object**, not an input to this design.

## 20.4a The three bridge hypotheses, named — `H-RR1` · `H-RR2` · `H-RR3`

**Added in revision.** §20.4's content is now carried as three explicitly labelled hypotheses so the
strength of each is visible and none can be cited above its grade.

### `H-RR1` — Boundary existence *(the experiment's own question)*

```
∃ n ∈ {5,4,3,2} :  Rⁿ adequate  ∧  Rⁿ⁻¹ not adequate
```

`[PROP]`. **Neutral as to where.** "All adequate" and "none adequate" are informative outcomes.

### `H-RR2` — **Zero / Boundary ASSOCIATION** *(weakened, per review)*

> ```
> Zero_{Tn,Π}(Sn; Rⁿ)   is ASSOCIATED WITH   Adeq(Rⁿ⁻¹, Q, Π)
>
>                   ...but does NOT by itself ENTAIL it.
> ```

`[PROP]` — **an association hypothesis, not an implication.**

**Why the weakening is not cosmetic.** Written as `Zero ⇒ Adequacy`, the hypothesis **quietly makes
`Zero` the causal mechanism of the boundary** — before any experiment has established that it is one.
KR-ZERO showed `Zero` is itself context-, transformation- and preservation-relative; **a relative
quantity cannot be promoted to the explanation of a boundary it is measured alongside.**

**Measured as:** co-occurrence of `Zero` with the adequate→inadequate crossing, reported as an
association with its rate — **never as a predictor, and never as a mechanism.**

### `H-RR3` — **`T`–`Π` interaction** *(the conceptual centrepiece)*

> ```
> The effect of a reduction transformation on preservation depends on the INTERACTION
> between transformation structure and preservation structure — not on either alone.
> ```

`[PROP]` — **the strongest question inherited from KR-ZERO**, and the bridge between the two
experiments:

```
KR-ZERO  ──discovered/constrained──▶  eliminability depends on T, Π, context, relational structure
                                                    │
                                        KR-REP-REDUCTION independently tests
                                                    │
                                     Q · C · O · Zero · T–Π interaction
                                                    ▼
                                          preservation boundary
```

> **`H-RR3` is a QUESTION this design can pose and only partially answer.** It is **not** a general
> theory of representation transformation, and must not be reported as one. **The controlled test of
> it is the deferred 2×2 (§20.5), not this experiment.**

## 20.4b Epistemic separation — a standing rule for this experiment

> **KR-ZERO and `R⁵→R²` are kept epistemically separate.**
>
> | | |
> |---|---|
> | KR-ZERO results | **prior constraints and interpretive hypotheses** |
> | KR-REP-REDUCTION results | **independent evidence about a reduction chain** |
>
> **No KR-ZERO aggregate may be cited as evidence for a reduction-chain claim, and no
> reduction-chain result may be cited as confirming a KR-ZERO law.** The two share the `Zero`
> predicate and nothing else.

## 20.5 The mechanism 2×2 is DEFERRED — deliberately

This experiment does **not** cross `T_E/T_R × Π_E/Π_R`. **That is a decision, not an omission.**

> **This is experiment (A): a first representation-reduction experiment.** Adding the mechanism axis
> would make it answer two questions at once and **destroy the causal interpretation of both.** The
> 2×2 is preserved as the **next controlled mechanism experiment**.

**Primary question:** *where is the preservation boundary in an explicit reduction chain?*
**Secondary, and only as interpretation:** does `Zero` occur at the transition · does it predict
preservation · does the transition involve relational structure · does the boundary depend on `T` or
`Π` · is adequacy monotone.

## 20.6 Independence from the old `R1..R4` — reaffirmed

`R5..R2` here are **sequential transformation outputs**, defined in §8 from first principles. The old
`R1..R4` are **parallel categorical classes**, proven unordered **and** proven contaminated (40.2 %).
**No adapter exists, none may be written, and no old aggregate transfers.**

---

## Stop-condition review

| condition | status |
|---|---|
| `Q` definable independently of `T` | **satisfied** — §5, `FT-1` |
| carrier ambiguous | **no** — §3 |
| `R5..R2` genuine sequential transformations | **satisfied** — §8, `FT-2` |
| "reduction" operationalizable | **satisfied** — §9, four separate dimensions |
| `C` evaluable | **satisfied** — §6 |
| `O` fixable independently | **satisfied** — §7 |
| relies on old `R1..R4` | **no** — defined from first principles |
| declared factor not faithfully generated | **addressed** — §16 |
| requires assuming `E_S(D)=D∖S` | **no** — §14 defines a typed per-level operator and shows set subtraction is *invalid* at `R2` |

# VERDICT

> ## **READY FOR IMPLEMENTATION — CONDITIONALLY**
>
> **Revised.** The first draft's unconditional verdict was too strong: it was written before §20
> reconciled the design with the newer KR-ZERO mechanism findings. **Status remains `DESIGN ONLY`;
> nothing has been implemented.**

**Three conditions, part of the design rather than caveats on it:**

1. **`FT-1`…`FT-7` must be written and passing before any result is reported.**
2. **The §18 positive control must hold** — at least one level must exhibit `N_viol > 0`. **If no level
   does, the experiment is blind and its `N_viol = 0` findings are vacuous**, exactly as the
   `T7 × P9` pairing was in `KR-ZERO-ALGEBRA`.
3. **§20 must be honoured at reporting time**: `Zero ⇒ Adequacy` reported as a bridge hypothesis that
   was tested, relationality reported as a factor and not a law, and the 2×2 left to its own
   experiment. **A report that quietly reinstates any of these fails the design.**
