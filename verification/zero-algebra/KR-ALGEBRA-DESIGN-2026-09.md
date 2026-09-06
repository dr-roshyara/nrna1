# `KR-ALGEBRA-2026-09` — PHASE 3 EXPERIMENTAL DESIGN
## Do the operations have stable algebraic properties?

**DESIGN ONLY. No code, no dataset, no implementation.**
**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · nothing adopted.**
**Inherits `§0` of `KR-REP-REDUCTION-DESIGN-2026-09.md` unchanged** — adequacy, no-excess, the
`H(T)≥H(Q)` decomposition, `Q-equivalent` (never `Q-isomorphic`), adequacy≠realization, and the
source/representation type discipline.

---

# 1. Research question

> **Do `T`, `E_S`, `Π`, `Zero`, `Adeq` and `Real` satisfy stable algebraic laws — and does anything
> survive when preservation is APPROXIMATE rather than exact?**

**The second clause is the experiment.** Everything demonstrated so far assumed *exact* preservation
(`Ĥ(Q|R) = 0`, `N_viol = 0`). **Real knowledge work never gets exact preservation.** It gets *small*
loss — and the moment a threshold appears, the relation becomes a **tolerance relation**.

# 2. ⚠️ The central hypothesis — and it predicts the algebra FAILS

`FR-001` is **frozen**: *pairwise distinguishability cannot carry family-level complexity*, established
by two independent mechanisms, the first of which is that **tolerance relations are not transitive**
(a sorites chain: `a ~ b`, `b ~ c`, `a ≁ c`).

Define **ε-adequacy**: `R ≈_ε R'` iff both preserve `Q` to within loss `ε`.

> ## `H-ALG-0` — the make-or-break hypothesis
> ```
> ≈_ε  is NOT transitive.
> Therefore there is NO QUOTIENT of representations by "preserves the same knowledge".
> Therefore the Knowledge Algebra CANNOT take the standard quotient-algebra form.
> ```
>
> **If this holds, most of classical algebra is unavailable**, and Phase 3's real output is *which
> weaker structure remains* — not a set of laws.
>
> **If it fails — if `≈_ε` is transitive on the tested carriers — that is a genuine surprise** and a
> much stronger positive result than any individual law.

**This is the one hypothesis whose refutation would be more valuable than its confirmation.**

# 3. Carriers — three, and the third satisfies the Phase 2→3 gate

> **The gate (programme status §4): the algebra must be tested on a carrier we did not design.**

| | carrier | origin | why included |
|---|---|---|---|
| `X₁` | token sequences with metadata | **ours** — KR-ZERO | prior evidence; relational transforms available |
| `X₂` | measurement records `(v,s,t)` | **ours** — KR-REP-REDUCTION | the boundary is already located here |
| **`X₃`** | **the PublicDigit election schema** | **NOT ours, and not designed for this research** | **satisfies the gate** — see §3.1 |

## 3.1 `X₃` — the election carrier, and why it is the right gate carrier

The repository contains a production election domain whose structure was fixed for **electoral
integrity**, years before this research existed:

```
votes    ( id , organisation_id , election_id , vote_hash ,
           candidate_01 … candidate_60 , no_vote_posts , cast_at , metadata )
                                                    ← NO user_id, verified structurally
results  ( id , organisation_id , vote_id , election_id , candidacy_id , post_id ,
           position_order )
voter_slugs ( … user_id , current_step , status … )   ← the IDENTIFIED side
```

**It carries a real, externally-motivated preservation contract with two independent components:**

```
Q  (utility)    the TALLY must be recoverable      — what the election is FOR
C  (anonymity)  no representation may permit linking a vote to a voter
```

> ### ⚠️ And this repairs the exact defect Phase 2 exposed.
>
> `KR-REP-REDUCTION` reported `A_n = 1.000` at **every** level — the contract never failed, so
> **contract-only failure was never generated and `H-B` could not be tested.**
>
> **Anonymity is a `C` that genuinely fails.** A reduction that keeps `cast_at` at fine granularity in
> a small electorate makes votes re-identifiable — **the contract breaks while the tally is perfectly
> recoverable.** That is **contract-only failure**, observable at last.
>
> **`C` is operationalized by k-anonymity on the quasi-identifiers** — an externally established
> criterion, **not one invented here**: `C_k(R) = 1` iff every equivalence class on
> `(election, cast_at bucket, no_vote_posts pattern, …)` has **≥ k** members.

**⚠️ Production boundary, absolute:** the experiment uses the **schema shape** and **synthetic
instances only**. **No production data is read, no production code is modified, no real vote is
touched.** `app/` and the live database are out of scope, as in every prior experiment.

# 4. The operations under test

```
T        : X → Y                    transformation
E_S^{(n)}: R_n → R_n                TYPED elimination — never  D ∖ S
Π = (Q,C,O)                         preservation contract
Zero_{T,Π}(S;D)                     eliminability   [PROP], not [DEF]
Adeq(R,Q)      Ĥ(Q|R) = 0           a property of the REPRESENTATION
Real(R,Q,O)    O(R) = Q(D)          a property of the OPERATOR
```

# 5. Candidate laws — each with its falsifier, each tested on all three carriers

| id | law | falsified by | prior expectation |
|---|---|---|---|
| **L1** | `E_∅(D) = D` and `Zero(∅;D)` always | any counterexample | should hold; a **sanity check on the implementation**, not a discovery |
| **L2** | `E_S(E_S(D)) = E_S(D)` — elimination idempotent | re-indexing changes the result | plausible |
| **L3** | `E_S ∘ E_{S'} = E_{S'} ∘ E_S` — elimination commutes | order matters | **expected to FAIL** — `KR-ZERO-ALGEBRA` found 11 non-commuting witnesses |
| **L4** | `Adeq(T₂(T₁(D))) ⟹ Adeq(T₁(D))` | a counterexample | **holds by DPI on a sequential chain — a consistency check, NOT a discovery** (Phase 2 §2) |
| **L5** | `Zero(S;D) ∧ Zero(S';D) ⟹ Zero(S∪S';D)` | case-I structure | **expected to FAIL** — refuted, 131 witnesses |
| **L6** | `Zero(S∪S';D) ⟹ Zero(S;D) ∧ Zero(S';D)` | case-J structure | **expected to FAIL** under cancelling contracts only |
| **L7** | `Adeq` closed under composition of *preserving* transforms | a preserving pair whose composite is not | unknown |
| **L8** | `Remainder = D − Eliminated` | the three constructions diverge | **expected to FAIL** — `A ≡ C ≠ B`, 1182/1182 |
| **L9** | `Real(R) ⟹ Adeq(R)` — realization implies adequacy | a decoder that is right by luck | should hold |
| **L10** | `Adeq(R) ⟹ Real(R)` | a recoding that defeats a fixed `O` | **expected to FAIL** — Phase 2 §5, 54-point swing |

> **Six of ten are expected to fail, and their prior refutations are named.** This is deliberate:
> **an experiment whose laws are all expected to hold is a demonstration, not a test.** The
> informative outcomes are `L2`, `L7`, `L9`, and any *unexpected* result among the six.

# 6. The ε-tolerance protocol — the centrepiece

For `ε ∈ {0, 0.01, 0.05, 0.1, 0.25}` bits of `Ĥ(Q|R)`:

1. build the ε-relation `≈_ε` over representations within each carrier
2. **search for sorites chains**: `R₁ ≈_ε R₂ ≈_ε … ≈_ε R_m` with `R₁ ≉_ε R_m`
3. report the **shortest** such chain per `(carrier, ε)`
4. re-test `L1..L10` **under `≈_ε` instead of equality**

> **Report shape:** a table of `ε` × carrier × *"does each law survive?"*. **The expected finding is a
> threshold `ε*` above which laws that hold exactly begin to fail** — and the location of `ε*` is the
> quantitative result Phase 3 can deliver even if `H-ALG-0` holds.

# 7. Metrics

Per `(carrier, operation, law, ε)`: **holds / fails / not-applicable**, with the **minimal witness**
persisted for every failure. Plus, per carrier: `Ĥ(Q)`, `Ĥ(Q|R)` with Miller–Madow correction and
bootstrap CI, `N_viol`, `A_k` (k-anonymity, `X₃` only), `F`.

# 8. Cross-carrier protocol — how a law earns a verdict

```
holds on 3/3 carriers  →  [EXP] holds across the TESTED carriers          (never "universally")
holds on 2/3           →  [EXP] CARRIER-DEPENDENT — and the divergence is the finding
holds on ≤1/3          →  [NEG] refuted
```

> **A law that holds only on `X₁` and `X₂` — the two carriers we designed — and fails on `X₃` is the
> most informative single outcome available**, because it would show the earlier results were
> artefacts of our own construction. **The design is built to detect that.**

# 9. Fidelity tests — `AT-1..AT-7`, before any result

| id | assertion |
|---|---|
| `AT-1` | `Q` and `C` computable from the carrier alone, no reference to `T` or `R` |
| `AT-2` | `E_S` re-establishes each carrier's invariants (ranks, k-anonymity classes, tallies) — **`D∖S` never used** |
| `AT-3` | every representation populates exactly its declared schema *(the KR-ZERO 40.2 % defect)* |
| `AT-4` | `N_viol = 0 ⟺ Ĥ(Q\|R) = 0` per carrier |
| `AT-5` | **`X₃` uses synthetic instances only** — assert no production table is read |
| `AT-6` | each law's falsifier is *reachable*: a positive control exhibits at least one failing instance for every law expected to fail |
| `AT-7` | `≈_0` is exactly equality-of-adequacy — the ε-machinery degenerates correctly at `ε=0` |

**`AT-6` is the anti-vacuity guard**: a law cannot be reported as "holds" if the design could not have
seen it fail.

# 10. Falsification criteria

| | claim | falsified by |
|---|---|---|
| **A** | `H-ALG-0` — `≈_ε` non-transitive | no sorites chain found at any `ε > 0` on any carrier |
| **B** | the gate is met | `X₃` produces no result distinguishable from `X₁`/`X₂`, i.e. the "independent" carrier behaves identically — then it was not independent in any relevant sense |
| **C** | contract-only failure observable | `A_k = 1` at every level of `X₃`, repeating Phase 2's `H-B` failure |
| **D** | laws are carrier-stable | ≥3 laws come out carrier-dependent — then there is no single algebra to find |

# 11. Threats to validity

| threat | mitigation |
|---|---|
| **`X₃` is only schema-shaped, not real data** | acknowledged and **unresolved** — synthetic instances over a real schema is weaker than real data, and the design says so rather than implying otherwise |
| ε-thresholds chosen post hoc | the ε-grid is **fixed in this document, before implementation** |
| six laws expected to fail → confirmation bias | `AT-6` requires a reachable falsifier for each; and the *unexpected* outcomes are pre-registered as the informative ones (§5) |
| `k` in k-anonymity is a free parameter | swept, `k ∈ {2,3,5,10}`, and reported as a sensitivity — **not tuned to produce a failure** |
| three carriers is a small sample of "carriers" | **stated: this cannot establish universality**, only carrier-dependence or its absence across three |

# 12. Open questions

- Whether a **non-quotient** structure (tolerance space, closure system on a preorder, category with
  non-invertible morphisms) fits if `H-ALG-0` holds. **Phase 3 measures; it does not choose.**
- Whether `ε*` is a property of the carrier, the contract, or the transformation family.
- The carrier question itself remains `[OPEN]` — three carriers do not settle it.

---

# VERDICT

> ## **DESIGN COMPLETE — NOT READY FOR IMPLEMENTATION**
>
> **One blocker, stated rather than worked around:**
>
> **`X₃` requires a synthetic generator for the election schema that is faithful to its real
> invariants** — tallies must be consistent with `results`, `vote_hash` unique, `no_vote_posts`
> coherent with `posts`, and the anonymity structure intact. **That generator does not exist and is
> not trivial.** Writing it is a prerequisite, and getting it wrong would make `X₃` a third carrier we
> designed — **destroying the gate that is the entire point of Phase 3.**
>
> **Recommended next step: specify and validate the `X₃` generator against the real schema
> constraints, as its own reviewed sub-task, before any law is tested.**
>
> ### ▶ STATUS UPDATE — the specification now exists
> **[`KR-ALGEBRA-X3-GENERATOR-SPEC-2026-09.md`](KR-ALGEBRA-X3-GENERATOR-SPEC-2026-09.md)** — every
> constraint traced to a migration or to `CLAUDE.md`; five open decisions **declared as sweeps rather
> than resolved**; validation gate `VG-1..VG-8` with **`VG-8` (a demonstrated `C_k` failure) as the
> condition for `X₃` counting as the gate carrier.**
>
> **The blocker is now: implement the generator and run `VG-1..VG-8`.** Phase 3 remains NOT READY
> until `VG-8` passes.

**Nothing implemented. `app/` untouched. No production data in scope.**
