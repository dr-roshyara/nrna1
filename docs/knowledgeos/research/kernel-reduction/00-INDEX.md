# KnowledgeOS Kernel Reduction & Minimality Experiment — Index

**Experiment ID** `KR-2026-09-01` · **Model** `kr-model-1.0` · 2026-09-01
**Status** `[EXP]` research experiment — **NOT canonical, NOT architecture, NOT governance.**
This lane was **not authorized to canonize a KnowledgeOS kernel** and does not.

**Start here:** [`19-directive-adoption-and-research-restructure.md`](19-directive-adoption-and-research-restructure.md) — it governs how everything below is read. Then [`FINAL-kernel-reduction-report.md`](FINAL-kernel-reduction-report.md).

| # | Document | Contains |
|---|---|---|
| 01 | [research question](01-research-question.md) | question, scope, what this is *not*, prior constraints, three notions of necessity |
| 02 | [evidence matrix](02-evidence-matrix.md) | corpus reconstruction; **MR-1** `C0` has almost no corpus provenance; **MR-3** corpus already classifies `DetectGap` as a derived evaluation; **MR-4** `C0` omits `Qualify` |
| 03 | [capability model](03-capability-model.md) | five notions of minimality; C1–C25; every modification justified, every rejection recorded |
| 04 | [operator contracts](04-operator-contracts.md) | the atom vocabulary; the anti-circularity device; shared atoms |
| 05 | [operator × capability matrix](05-operator-capability-matrix.md) | computed matrix; why LOO alone gives a false answer |
| 06 | [composition rules](06-composition-rules.md) | carriers, derivation table, `Reach(S)` |
| 07 | [ablation design](07-ablation-design.md) | procedure, failure classes, smuggling test, reproducibility metadata |
| 08 | [scenario suite](08-scenario-suite.md) | the 16 scenarios and their design logic |
| 09 | [simulation design](09-simulation-design.md) | what was built; function classification; known limits |
| 10 | [ablation results](10-ablation-results.md) | baseline failure; LOO; the two reconstructions; **atom-level** results; smuggling controls |
| 11 | [pairwise results](11-pairwise-results.md) | 4 interacting pairs; the operator bundle; no third-order effects |
| 12 | [randomized results](12-randomized-results.md) | 150 000 trials; **vacuity audit**; 8 robustness variants; information theory; causal check |
| 13 | [DDD analysis](13-ddd-analysis.md) | 10 questions × 14 operators; the three disagreements; Zero/Ideal/Inquiry/Governance tested |
| 14 | [alternative kernels](14-alternative-kernels.md) | substitution graph; exhaustive minimal-kernel search; Pareto without a weighted score; the shape hypothesis |
| 15 | [falsification](15-falsification.md) | 16 falsifiers, one per claim |
| 16 | [negative results](16-negative-results.md) | 14 negative results — mandatory section |
| 17 | [open questions](17-open-questions.md) | 4 blocking, 10 substantive, 4 method-level |
| — | [**FINAL report**](FINAL-kernel-reduction-report.md) | 16 sections + assumptions + sensitivity + Part XXIX table + Q1–Q12 |
| 19 | [**directive adoption & research restructure**](19-directive-adoption-and-research-restructure.md) | **SUPERSEDES THE HEADLINE.** Paired-design correction · `Reachability ≠ Epistemic adequacy` · 7-level programme · shape `(𝒫,ℛ,δ,ℐ,𝒰)` · Semantic Kernel Equivalence spec · standing no-more-simulation constraint |
| 18 | [**audit response & protocol audit**](18-audit-response-and-protocol-audit.md) | **ADDENDUM 2026-09-01.** Provenance ledger · corrections accepted and disputed · corpus-boundary contamination finding · audit concerns tested against the code · **audit of the protocol itself (P-1…P-8)** · 12-representation derivability search · revised classification |

> ## ⚑ The headline result has been superseded — read [§19](19-directive-adoption-and-research-restructure.md) first
>
> `[EXP]` **The experiment falsified the assumption that kernel minimality can be determined
> independently of semantic representation.** `|K_min| = 13` under the protocol's algebra and
> `|K_min| = 8` under the extended one: not conflicting measurements, but solutions to two different
> optimization problems. The programme sequence is now
> **Representation → Capability → Reachability → Minimality**, and the kernel question moves from
> first to *seventh* in a seven-level hierarchy (§19 §5).
>
> The results below stand as measured; their *interpretation* is governed by §19.

> **Addendum (2026-09-01).** An external audit of this experiment has been received and answered in
> **[§18](18-audit-response-and-protocol-audit.md)**. Three corrections were accepted and applied at
> source; two audit claims were tested and one refuted; the protocol itself was audited; and the
> representation search was extended from 8 to 12 models. **Read §18 alongside the headline results
> below — four operators moved to `UNRESOLVED` and one to `UNSUPPORTED`, while the `DetectGap` and
> never-derivable findings strengthened.**

## Headline results

1. **`C0` (13 operators) fails its own baseline** — 21/25 capabilities, 9/16 scenarios. It holds no
   power to turn an observation into evidence, so it cannot validate anything. The missing power is
   `Qualify`, which the corpus already carried as `G1`.
2. **The function called `DetectGap` is representable as a derived evaluation across every tested
   algebra** — `Gap := difference-decision(norm-comparison(K_t, I_t))`, no smuggling, **12/12**
   representations (§18), agreeing with the corpus's own `𝒪?` classification. Supports prior
   constraint 4: **`Zero` behaves as a predicate, not a primitive.** `[EXP]` strong derivability
   evidence within the tested representation family — not a universal theorem.
3. **14 powers irreducible *relative to the tested capability model and semantic algebra*; 12 of 14
   operators.** Exactly two minimal kernels, **both of
   cardinality 13**, differing only in packaging. Cardinality cannot choose between them.
4. **Reduction beyond `DetectGap` is STOPPED.** Three further verdicts flip under a single
   defensible change of semantic granularity. The instability is in the granularity, not the count.
5. **Quantified in §18:** across **12 admissible representations**, `DetectGap` is derivable in
   **12/12**; `Discriminate` in 11/12; and five operators — `Observe`, `Qualify`, `Relate`, `Infer`,
   `Revise` — in **0/12**. Granting every audit reduction at once takes the minimal kernel from
   **13 to 8**, with four minimal kernels sharing a six-operator core.
6. **The protocol's own capability list C1–C13 is a positional bijection onto the 13 operators**,
   violating the instruction stated in the same section. Every non-trivial result exists only because
   the execution broke that bijection (§18 P-1).
7. **The randomized design was paired, not independent** (§19 §3, verified): 10 000 worlds × 15
   paired arms, not 150 000 observations. Re-analysed correctly, the one surviving property result is
   **deterministic** — `P(fail │ guard active) = 1.0000`, `c = 0` — and the only quantity with
   sampling error is the generator's guard-activation rate. The corrected statement is *stronger*
   than the published one.
8. **`Reachability ≠ Epistemic adequacy`** — the single most important limitation of the instrument,
   and the reason no further ablation can settle the kernel question (§19 §4).

## ⚠ Not part of this lane

`Yes. I read the full attached document,` (6 580 bytes, written **2026-09-01 22:56**, i.e. *during*
this experiment) appeared in this directory but was **not produced by this lane**. Its subject is a
different research thread — the missing derivation `𝓜 : (O, E_{≤t}, A, G, EC) → K_t(O)`. It is
left in place, unmodified and unattributed to this experiment.

It is the **third instance in this session** of the pattern documented in
[§18 §2](18-audit-response-and-protocol-audit.md): concurrent writers placing artifacts in
directories that later determine how those artifacts are classified. Here the misfiling would have
attributed another lane's work to the kernel-reduction experiment. **Directory location is being used
as provenance, and it is not reliable provenance.**

## Code

`research/kernel-reduction/` — `kr/{atoms,carriers,operators,reach,capabilities,scenarios,properties,variants,ablate,infotheory}.py`, `run_all.py`.
Results: `research/kernel-reduction/results/*.json`. Reproduce: `python3 run_all.py`.

## Reading discipline

Tags are load-bearing: `[CORPUS]` `[EXT]` `[INF]` `[PROP]` `[EXP]` `[NEG]` `[OPEN]`.
**No `[PROP]`, `[INF]` or `[EXP]` item in this directory may be converted into an architecture
decision, governance rule, invariant, theorem or canonical KnowledgeOS law without a separate,
authorized act.**
