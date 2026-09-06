# 16 — Master Gap Register

**Mandate §21.** Every finding from documents 00–15, consolidated.

**Severity** — CRITICAL: prevents the theory from being well-defined · HIGH: prevents computation or
empirical validation · MEDIUM: prevents architectural/DDD consistency · LOW: documentation.

**Evidence** — `EXECUTED` (a program or command produced it) · `EMPIRICALLY OBSERVED` (read from the
running system) · `DERIVED` (formal consequence of admitted premises) · `CORPUS ESTABLISHES` ·
`UNRESOLVED` · `REFUTED` · `PROPOSED`.

Reproduce everything with `python3 exec/exp_*.py` and the commands in `00-CORPUS-INVENTORY.md` §9.

---

## A. CRITICAL — the theory is not well-defined until these close

| ID | Gap | Domain | Evidence | Test available? | Test result | Resolution path |
|---|---|---|---|---|---|---|
| **G-01** | **`𝒪`, the mandatory operation set, is never enumerated.** `≡` is defined by `∀T ∈ 𝒯`, so the quantifier ranges over an open collection: `≡` is not undecidable, it is *unconstructed*. `K*`, `A∈K`, `K₁=K₂`, Step 259 sufficiency and Step 266 minimality all inherit this. | foundations | `DERIVED` + `EXECUTED` (`12` IE-4, `04` KG-4) | yes | `exp_congruence` EXP-3: sufficiency **flips** when one corpus-plausible operation is added | **HUMAN DECISION D-1** — then everything below it becomes derivable |
| **G-02** | **`K=(𝒜,ℛ)` minimality is conditional on `𝒪`, not proven.** Two histories reach a byte-identical `K` and are separated by `ever_contested`. | K | `EXECUTED` | yes | REFUTED as unconditional | closes with G-01 |
| **G-03** | **`A ∈ K` and `K₁ = K₂` are not well-defined.** Four corpus-sourced equalities disagree on 2/3 membership probes and give 2/3/5/5 partitions of five states. Two of the four are not congruences. | identity | `EXECUTED` | yes | REFUTED | closes with G-01 (a congruence for a fixed `𝒪` is unique up to iso) |
| **G-04** | **The definitional dependency graph is cyclic** — one 7-node SCC `Assertion → Evidence → Rule → Policy → Authority → Assertion`. Broken only by Step 187's normative stipulation, whose own `B` (recorded basis) re-enters the cycle. | ontology | `EXECUTED` (`exp_ontology` EXP-16) | yes | CONFIRMED cyclic | **HUMAN DECISION D-2** |
| **G-05** | **11 of 26 objects are transitively non-computable**, `K`, `T`, `≡`, `K*`, `History` among them. Step 266 states the propagation principle and never applies it. | computability | `EXECUTED` (EXP-17) | yes | CONFIRMED | split `A` into `A_struct` + qualification layer (`03` ON-5) |
| **G-06** | **Σ is ≥5 orthogonal axes in one word.** A single enum needs 96 values. `EpistemicStatus ≠ GovernanceStatus` is correct and separates 2 of ≥5. | Σ | `EXECUTED` (`exp_sigma` EXP-22/23) | yes | REFUTED as one axis | derivable — no human decision needed |
| **G-07** | **Averaging over the ordinal status ladder is meaningless.** Decision flips across three admissible re-encodings. Any average / percentage / weighted-threshold rule over `σ` is invalid. | measurement | `EXECUTED` (EXP-4) | yes | REFUTED | audit and relabel every such rule |
| **G-08** | **`Determination` — the founding object — is absent from all six terminal steps (0 occurrences)**, and the conditional structure it carried is inexpressible in `P=(E,D,V)`. | scope | `EXECUTED` (grep + EXP-19/20) | yes | REFUTED | **HUMAN DECISION D-3** |
| **G-09** | **The 28 `K` definitions span 7 mathematical kinds with no morphism between kinds**; 11 are incomparable, 3 are different ontologies, 1 (`K=(A,J,T_A)`) is a category error. | K | `DERIVED` | yes | CONFIRMED | reframe as sufficient statistics for different `𝒪` (`04` KG-8); closes with G-01 |
| **G-10** | **The schema vocabulary files are ungoverned.** All ten carry zero knowledge cards. Editing `statuses.yaml` silently changes the meaning of every governed document — no ADR, no owner, no review, no lint. | governance | `EMPIRICALLY OBSERVED` | yes | CONFIRMED | engineering fix; relates to D-2 |
| **G-11** | **The evidence layer has no inputs.** `e` is a set of opaque ids; evidence identity, provenance, validity and independence are all unanswerable from `K`. The five-step evidence algebra has nothing to operate on. | evidence | `UNRESOLVED` | partially | — | model evidence as a first-class object, as Closure-04 §4 already proposed |
| **G-12** | **No empirical relational structure for any epistemic quantity.** Roberts' representation stage — *is this measurable at all?* — was imported as vocabulary and never executed. | measurement | `UNRESOLVED` | yes, not yet run | — | specify `a ≿ b` and test the weak-order axioms |
| **G-13** | **Primary and secondary corpora are interleaved and mutually citing after ~Step 258**, within minutes; the corpus grew during this session (Steps 269, 270, 271). Agreement between them is not independent corroboration. | method | `EXECUTED` (mtimes + the documents' own opening sentences) | yes | CONFIRMED | freeze one tree before the next verification pass |

---

## B. HIGH — computation or empirical validation blocked

| ID | Gap | Domain | Evidence | Test | Result | Resolution |
|---|---|---|---|---|---|---|
| **G-14** | `Relevant` — the load-bearing predicate of the qualification rule — is class C. Closure-04 and Step 266 never reference each other. | evidence | `DERIVED` | — | — | give a procedure, or relocate across the judgement boundary |
| **G-15** | `Context` (`c` in `A`, `C` in `Evidence`) has **no type, domain or equality** anywhere; no row in Step 266's audit. | ontology | `UNRESOLVED` | — | — | type it; decide whether it is the DDD bounded context |
| **G-16** | Transition signature unsettled: 45 RHS strings, 11 named functions, arity 1–6; `ρ` and `Ω` untyped; `Ω` overloaded 3 ways. | transformation | `EXECUTED` | yes | CONFIRMED | closes with G-01 |
| **G-17** | No preconditions, postconditions or failure semantics for `T`. Refusals are unrecordable, so replay cannot reproduce them. | transformation | `UNRESOLVED` | — | — | specify; needed before any audit claim |
| **G-18** | No transformation identity or operation versioning — Step 266 §266.14 says deterministic replay requires it. | identity | `UNRESOLVED` | — | — | version the operation registry |
| **G-19** | No transition provenance, merge provenance, withdrawal record or contestation record. 6 of 8 audit concepts unanswerable from `K`. | provenance | `EXECUTED` (`exp_provenance`) | yes | CONFIRMED | restore `H` to the model (`𝒦=(K,H)`) |
| **G-20** | Merge is an algebra only relative to a rule. Exhaustive search: latest-wins has **4 associativity counterexamples**; conflict-marking has 0. Step 025l's convergence claim names no rule. | merge | `EXECUTED` (EXP-10b) | yes | REFUTED for one rule | name the rule |
| **G-21** | `AggregateSupport` unbounded and non-idempotent (+18.1 % for a duplicate); `IndependenceFactor` gives a copy the same weight as an independent source. | measurement | `REFUTED` | yes | REFUTED | relabel as declared heuristics |
| **G-22** | No `(Ω,𝓕,P)` anywhere; per-assertion confidences over disjoint values can sum > 1 and nothing forbids it. | measurement | `EXECUTED` | yes | REFUTED | declare a probability model or drop the numbers |
| **G-23** | The assertion has one `t` while the corpus establishes `T_valid ≠ T_known`; Step 187's own example needs a validity interval the type cannot hold. | assertion | `EXECUTED` | yes | CONFIRMED | bitemporal assertion |
| **G-24** | Two carriers of propositional content (`P` and `ℛ`) with no stated relation; `ℛ` edges have no id, evidence, provenance or status — and `ℛ_der` is the component that makes the state sufficient. | assertion / DDD | `EXECUTED` | yes | CONFIRMED | make `ℛ` edges first-class |
| **G-25** | `Unknown` fits neither the value space (Step 264's own category error) nor absence (destroys Zero); `σ` is not a field of `A` in the terminal type. | Σ | `EXECUTED` | yes | REFUTED | closes with G-06 |
| **G-26** | Negation, conditionals and quantification are inexpressible in `P=(E,D,V)`; units and validity intervals are lossy. 2 of 10 required propositions cleanly expressible. | assertion | `EXECUTED` | yes | REFUTED | closes with G-08 |
| **G-27** | Evidential **sufficiency relative to a bar** (`Insufficient`) survives all five Σ axes and is missing from the ratified status set. Converges with `zero_reference.py`'s PF-1. | Σ | `EXECUTED` | yes | CONFIRMED | closes with G-06 |
| **G-28** | `Authority` names both a permission relation and a trust rank; Step 267 maps the theory's permission concept onto `authorities.yaml`'s trust concept and reports IMPLEMENTED. | UL | `DERIVED` | yes | REFUTED as correspondence | separate the terms |
| **G-29** | `Status` overloaded across ≥5 facts went uncaught for 270 steps because UL audits looked for inconsistent usage, not dimensional overload. | UL / method | `DERIVED` | — | — | add a dimensionality check to UL audits |
| **G-30** | `Regime` — the corpus's best strategic concept — vanished after 2026-08-26 and was reinvented without the word on 2026-08-30. No mechanism notices a concept ceasing to be used. | UL / method | `EXECUTED` | yes | CONFIRMED | reinstate; add a term-mortality check |
| **G-31** | The aggregate boundary is never fixed: `K` argued as both Aggregate and projection, with opposite consequences for concurrency, merge and invariants. | DDD | `DERIVED` | — | — | adopt the projection reading (`04` KG-8) |
| **G-32** | `order` in `statuses.yaml` conflates progression and retirement; the order-rule allows `draft → frozen` and forbids un-supersession; no transition legality is checked anywhere. | implementation | `EXECUTED` | yes | CONFIRMED | add a covering relation |
| **G-33** | The structural profile's vocabulary-integrity slice is **132/132 INCONCLUSIVE** — `schema/vocabulary-integrity.yaml` does not exist; the profile is warn-only, exit 0. | implementation | `EXECUTED` | yes | CONFIRMED | create the config; wire the gate |
| **G-34** | Four of five EKP invariant checks pass **vacuously**; the whole supersession/implementation relation family is unexercised (`adr, depends_on, implements, reviewed_by, superseded_by, supersedes, verified_by` — 0 in use). | implementation | `EMPIRICALLY OBSERVED` | yes | VACUOUS | exercise or remove |
| **G-35** | "47 tests" is a `--filter=Lineage` name-match over 18 unrelated classes; **4** exercise the provenance graph. Figure reproduces exactly; weight ≈12× overstated across 14 artifacts. | evidence hygiene | `EXECUTED` | yes | PARTIALLY REFUTED | correct the 14 artifacts |
| **G-36** | The corpus's own nominated strongest empirical test — selection precision/recall — was specified on day 1 and never run. | method | `DERIVED` | yes, still runnable | not run | run it |
| **G-37** | Two of five required identity counterexamples cannot be **constructed** (no Policy identity, no version identity): unfalsifiable regions, not confirmations. | identity | `UNRESOLVED` | no | — | define both |
| **G-38** | The theory's stipulation that authority is exogenous is contradicted by the running system, which internalizes constitutional self-amendment (correctly) and externalizes the schema vocabulary (incorrectly). | governance | `EMPIRICALLY OBSERVED` | yes | CONFIRMED | **HUMAN DECISION D-2** |

---

## C. MEDIUM

| ID | Gap | Evidence |
|---|---|---|
| **G-39** | `Claim` and `Verdict` used throughout, never typed. | `UNRESOLVED` |
| **G-40** | `Candidate` conflates evidence-insufficiency with pre-authority. | `EXECUTED` |
| **G-41** | `Contested` is a process, not a status; forcing it into the enum produces the demonstrated PF-6 inexpressibility. | `DERIVED` |
| **G-42** | Independence between sources is unrepresentable; absence of a `derives` edge is not evidence of independence. | `EXECUTED` |
| **G-43** | `𝒦` overloaded: a measurable space in `20260825-233107`, a meta-structure in Step 258. | `EXECUTED` |
| **G-44** | `025c` and `025n` are two non-identical documents titled "Evidence Aggregation Algebra". | `EXECUTED` |
| **G-45** | Eleven names for one transition, none distinguished. | `EXECUTED` |
| **G-46** | `GovernanceLineageGraph` is an election-platform class — Type 3 analogy by Step 267's own taxonomy, reported as Type 1. | `EXECUTED` |
| **G-47** | The code named "KnowledgeOS" is a husky/git-hook diagnostic; 0 files in `app/` mention `KnowledgeState`/`epistemic`. | `EXECUTED` |
| **G-48** | `T` is not invertible (`withdraw` destructive) — matches Step 266 §266.15. | `EXECUTED` |

---

## D. LOW

| ID | Gap | Evidence |
|---|---|---|
| **G-49** | Step numbers are unreliable identifiers: 217 and 229 absent; at least one filename number disagrees with its heading. | `EXECUTED` |
| **G-50** | 34 of 550 primary files are exact byte duplicates; one duplicate pair has unrelated filenames. | `EXECUTED` |
| **G-51** | Q14 ("the complete formal definition") contains three non-identical definitions of `K`; `20260826-161551` contains three more. | `EXECUTED` |
| **G-52** | 20 structural S2 failures, all in `docs/knowledge/archive/`. | `EXECUTED` |
| **G-53** | Near-duplicates (same title, different bytes) not exhaustively enumerated. | `UNRESOLVED` |

---

## E. What SURVIVED — the load-bearing positives

These are findings too, and a successor theory that discards them goes backwards.

| ID | Result | Evidence |
|---|---|---|
| **S-01** | **Provenance must be carried, not derived** — Step 265's `t=0` base case. | `EXECUTED` |
| **S-02** | **Lineage is computable** in `O(n+m)` — the one unambiguously decidable relation in the theory. | `EXECUTED` |
| **S-03** | **`𝒦 = (K, H)` is necessary** — 4 audit questions unanswerable from `K` alone. | `EXECUTED` |
| **S-04** | **`Evidence(O,P,C,R)`** — evidence is a relation, not a substance. Sound argument. | `CORPUS ESTABLISHES` |
| **S-05** | **Admission ≠ truth** (Closure-03) — the most important semantic decision in the corpus. | `CORPUS ESTABLISHES` |
| **S-06** | **`P ≠ A`** — independent corroboration becomes naturally representable. | `CORPUS ESTABLISHES` |
| **S-07** | **`EpistemicStatus ≠ GovernanceStatus`** — correct, though it separates 2 of ≥5 axes. | `CORPUS ESTABLISHES` |
| **S-08** | **"No scalar operator suffices"** for evidence aggregation — independently confirmed by execution. | `EXECUTED` |
| **S-09** | **`Zero(K,EC)` is computable**, total and terminating, relative to its evaluators. | `EXECUTED` |
| **S-10** | **Hash identity ≠ semantic identity** — correctly separates integrity from identity. | `CORPUS ESTABLISHES` |
| **S-11** | **Domain evolution ≠ knowledge evolution** — the most robust result about `T`. | `CORPUS ESTABLISHES` |
| **S-12** | **`K=(𝒜,ℛ)` is instantiated and running** — 40 assertions, 59 typed relations, referential integrity holding, 37 documents linted green. | `EXECUTED` |
| **S-13** | **Two orthogonal status axes are running and schema-enforced**, orthogonality visible in the data (7 distinct pairs). | `EMPIRICALLY OBSERVED` |
| **S-14** | **A real constitutional self-amendment rule exists** — ADR + supersession + ARB, with `frozen` machine-enforced. | `IMPLEMENTED` |
| **S-15** | **The deepest assurance mechanism is fail-closed by construction** — *"Absence of evidence is not PASS"*. | `EXECUTED` |
| **S-16** | **Semantic minimality ≠ syntactic compactness**, and `≡` must be a **congruence**, not merely an equivalence. | `CORPUS ESTABLISHES` |

---

## F. Counts

| Severity | Count |
|---|---:|
| CRITICAL | **13** |
| HIGH | **25** |
| MEDIUM | 10 |
| LOW | 5 |
| **Total gaps** | **53** |
| Survived / established positives | **16** |

| Closure type (mandate §22) | State |
|---|---|
| **Semantic closure** | **NOT achieved** — `Context`, `Claim`, `Verdict` undefined; `Status`, `Authority`, `Evidence`, `Provenance`/`Lineage`, `𝒦` overloaded |
| **Mathematical / computational closure** | **NOT achieved** — `𝒪` unenumerated ⟹ `≡` unconstructed ⟹ `K*`, membership, equality, sufficiency, minimality all inherit; 11 of 26 objects transitively non-computable |
| **Governance closure** | **PARTIALLY achieved** — the theory stipulates exogenous authority (normative, not proven); the implementation self-amends its constitution correctly and leaves its defining vocabulary ungoverned |

**And the mandate's own warning is confirmed empirically:** the dependency graph is *not* acyclic, so
even the weakest surrogate for closure is unavailable — but note that acyclicity would not have
implied semantic completeness anyway.

---

**Next:** `17-INDEPENDENT-GAP-DISCOVERY-VERDICT.md`.
