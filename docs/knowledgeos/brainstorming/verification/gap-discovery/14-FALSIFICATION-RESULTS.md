# 14 — Falsification Results

**Mandate §19.** For every major candidate definition: *what observation would prove this wrong?* —
then run the strongest available test.

**Evidence classes used:** `REFUTED` (a counterexample was produced), `SURVIVED` (the strongest test
available was run and the claim held), `UNTESTABLE` (the claim cannot be falsified as stated —
itself a finding), `NOT TESTED` (no test was available to this session; recorded honestly).

---

## 1. The falsification table

| # | Claim under test | Falsifier | Test run | Result |
|---|---|---|---|---|
| 1 | `K = (𝒜,ℛ)` is the **minimal sufficient** state | two histories with identical `K` distinguished by a required operation | `exp_congruence` EXP-3 | **REFUTED (conditionally)** — sufficiency flips with one corpus-plausible operation; the claim is conditional on an unstated `𝒪` |
| 2 | Content-only abstraction suffices | a distinguishing operation | EXP-1 | **REFUTED** — `explain` |
| 3 | In-assertion provenance restores sufficiency | two states agreeing on all content **and** all provenance, distinguished by an operation | EXP-2 | **REFUTED** — `withdraw`; `ℛ_der` is what does the work |
| 4 | `A ∈ K` is well-defined | two equalities disagreeing | EXP-8 | **REFUTED** — 4 predicates, disagree on 2/3 probes |
| 5 | `K₁ = K₂` is well-defined | different partitions | EXP-9 | **REFUTED** — 2/3/5/5 classes |
| 6 | `≡` is a decidable congruence | show `𝒯` is open | Step 266 + EXP-3 | **REFUTED as *constructed*** — quantifier ranges over an unenumerated set (`12` IE-4) |
| 7 | Merge converges (Step 025l) | a non-associative triple | EXP-10b **exhaustive**, 64 triples | **REFUTED for one rule, SURVIVED for another** — latest-wins: 4 counterexamples; conflict-marking: 0. The claim never names its rule |
| 8 | Averaging `σ` is meaningful | decision flips under an admissible re-encoding | EXP-4 | **REFUTED** — flips across 3 admissible encodings |
| 9 | `AggregateSupport = Σs/(1+log n)` measures support | unboundedness / non-idempotence | EXP-5 | **REFUTED** — 17.84 for 100 unit items; +18.1 % for a duplicate |
| 10 | `IndependenceFactor = 1/(1+depth)` measures independence | equal weight for a copy and an independent source | EXP-6 | **REFUTED** |
| 11 | Per-assertion confidence is a probability | two disjoint values summing > 1 | EXP-7 | **REFUTED** — no shared `(Ω,𝓕,P)` |
| 12 | `Evidence(O,P,C,R)` is the qualification rule | find an observation that qualifies without relevance | — | **SURVIVED** — the rule is sound; its predicate is non-computable (`08` EG-2), which is a different defect |
| 13 | `Unknown` is representable | show it fits neither value space nor absence | `exp_assertion` EXP-19 | **REFUTED** — category error either way (`05` CS-5) |
| 14 | `P = (E,D,V)` expresses what the theory needs | a required proposition it cannot express | EXP-19, 10 cases | **REFUTED** — 3 inexpressible, 2 lossy, 2 cleanly expressible |
| 15 | Provenance must be carried, not derived | find a `t=0` case where lineage supplies it | `exp_provenance` T1 | **SURVIVED** — Step 265's base case confirmed |
| 16 | Lineage is computable | — | T3 | **SURVIVED** — `O(n+m)`, total |
| 17 | `𝒦 = (K, H)` is necessary | find a history question answerable from `K` alone | T6 | **SURVIVED** — 4 audit questions unanswerable from `K` |
| 18 | Σ is one axis | a decision needing two facts at once | `exp_sigma` EXP-22/23 | **REFUTED** — ≥5 orthogonal facts; a single enum needs 96 values |
| 19 | `EpistemicStatus ≠ GovernanceStatus` is the right cut | show more axes are needed | EXP-23 | **SURVIVED but INSUFFICIENT** — the cut is correct and separates 2 of ≥5 |
| 20 | The dependency graph is acyclic | find an SCC | `exp_ontology` EXP-16 | **REFUTED** — one 7-node cycle |
| 21 | Authority is exogenous (Step 187) | show the running system internalizes it | GR-4/GR-5 | **REFUTED in the implementation** — the constitution self-amends; the schema vocabulary is ungoverned |
| 22 | `K` is implemented nowhere (Step 267) | find an instance | `exp_ekp_bridge` EXP-11 | **REFUTED** — 40 assertions, 59 relations, running |
| 23 | Provenance/lineage is implemented + 47 tests | reproduce and inspect the filter | `--filter=Lineage` | **PARTIALLY REFUTED** — figure reproduces; 4 of 47 tests are on-topic |
| 24 | "No scalar operator suffices" for evidence | rerun the operator matrix | `exp01_recheck.py` (pre-existing) | **SURVIVED** — confirmed, and provable, though not from the 7-column matrix alone |
| 25 | `Zero(K,EC)` is computable | run it | `zero_reference.py` (pre-existing) | **SURVIVED** — total, terminating, `O(\|R\|·cost)`, **relative to unspecified evaluators** |
| 26 | Missingness: `never-asked ≠ asked-and-empty` | collapse them and see if a decision changes | EXP-21/23 | **SURVIVED** — dropping the `asked` axis produces a harmful collision |
| 27 | The EKP's knowledge-graph invariants hold | run them | EXP-15 | **SURVIVED (1) / VACUOUS (4)** |
| 28 | Replay is deterministic | build and run it | `kos_kernel.replay` | **SURVIVED for a frozen `𝒪`** — and `12` IE-1 shows it is unguaranteed without operation versioning |
| 29 | `T` is invertible | attempt reversal after `withdraw` | `kos_kernel` | **REFUTED** — destructive; matches Step 266 §266.15 |
| 30 | The theory's founding object `Determination` is present in the terminal model | grep the six terminal steps | direct | **REFUTED** — 0 occurrences in all six |

**Score: 17 REFUTED (2 partially/conditionally) · 11 SURVIVED · 2 mixed.**

---

## 2. The claims that survived, and why that matters

Falsification is only informative if some things survive. Six did, and they are the load-bearing
positives of the whole corpus:

1. **Provenance must be carried, not derived** (Step 265's `t=0` base case) — survived by construction.
2. **Lineage is computable** in `O(n+m)` — the one unambiguously decidable relation in the theory.
3. **`𝒦 = (K, H)`** — history does not reduce to state; four audit questions prove it.
4. **`Evidence(O,P,C,R)`** as the qualification rule — the argument is sound.
5. **`EpistemicStatus ≠ GovernanceStatus`** — correct, though it separates 2 of ≥5 axes.
6. **"No scalar operator suffices"** for evidence aggregation — independently confirmed by execution.

Any successor theory that discards these is going backwards.

---

## 3. The three refutations that matter most

### F-1 — The minimality claim is conditional, not false

`K = (𝒜,ℛ)` is not *wrong*. It is **underdetermined**. `exp_congruence` EXP-3 produces two histories
reaching a byte-identical `K`, distinguishable by `ever_contested` — an ordinary governance predicate
the corpus's own Steps 155/179/181 discuss. Whether `K=(𝒜,ℛ)` suffices depends entirely on whether
`𝒪` contains such a predicate, and `𝒪` has never been written down.

**The corpus's terminal claim is true relative to a premise it never states.** That is a different
and more repairable failure than being wrong.

### F-2 — Four quantitative claims are refuted outright

Averaging over `σ`; `AggregateSupport`; `IndependenceFactor`; confidence-as-probability. All four
fail an executed admissibility check. None is salvageable *as a measurement*; all four are usable as
**declared engineering heuristics** once relabelled. This is a naming discipline problem with a
one-line fix per formula, and Step 270 was already heading there.

### F-3 — The founding problem is absent from the terminal model

`Determination`, named on 2026-08-25 as *"the missing mathematical object"*, occurs **zero times** in
Steps 262, 263, 264, 265, 266 and 267. The conditional structure it was introduced to carry is
inexpressible in `P = (E,D,V)`.

This is the only refutation in the table that is about **what the theory is for** rather than about
whether a formula is well-typed. It is recorded here rather than as a criticism of any step: no
single step dropped it. It was lost across a regime change (`01` ARC F), and nothing in the corpus's
own machinery — not the contradiction registry, not the concept genealogy, not the gap registers —
is designed to notice a question that stops being asked.

---

## 4. Two claims that could not be falsified, and that is the finding

| Claim | Why untestable |
|---|---|
| "Two states have different **policies**" | Policy has no identity (`12` IE-1). The counterexample the mandate requires cannot be constructed. |
| "Same id, different **version**" | The theory has no version identity. The EKP has `version`; the theory does not. |

**FR-1 (`UNRESOLVED`, HIGH).** When a required falsification test cannot be *constructed*, the theory
is not thereby confirmed — it is **unfalsifiable in that region**, which is worse. Two of the
mandate's five identity counterexamples fall here.

---

## 5. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **FR-1** | Two of five required identity counterexamples cannot be constructed (no Policy identity, no version identity) — unfalsifiable regions, not confirmations. | `UNRESOLVED` | HIGH |
| **FR-2** | 17 of 30 major claims refuted by executed counterexample; 11 survived their strongest available test. | `EXECUTED` | — |
| **FR-3** | The minimality claim is **conditional on an unstated `𝒪`**, not false — a repairable failure. | `EXECUTED` | **CRITICAL** |
| **FR-4** | All four quantitative claims fail admissibility; all four are recoverable as declared heuristics. | `REFUTED` | HIGH |
| **FR-5** | `Determination` — the founding object — is absent from all six terminal steps, and no corpus mechanism is designed to detect a question that stops being asked. | `EXECUTED` | **CRITICAL** |
| **FR-6** | Six load-bearing results survived falsification and should be preserved by any successor theory. | `SURVIVED` | — |

---

**Next:** `15-UBIQUITOUS-LANGUAGE-GAP.md`.
