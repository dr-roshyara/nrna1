# KOS — Theory Integration Gate: Cohesion/`L3` empirical track vs. the theory-construction programme

**Date:** 2026-09-28. **Phase:** research integration only — no implementation, no tests, no
commits, no resumption of the stopped theory-construction programme, no execution of any
experiment (including "Zero Closure").

> ⛔ Read-only. `git status --short scripts/ tests/ .claude/runtime` unchanged. The
> theory-construction programme's own `⛔⛔ RESEARCH STOPPED` state is not reinterpreted,
> not lifted, not touched.

---

## 1 · Executive research conclusion

**Hypothesis B, with one precise, narrow instance of Hypothesis C.** The Cohesion track is not
a separate research programme accidentally running in parallel — several of its concrete
findings are unlabelled instances of formal structures the theory programme has already
built (the `λ`-relative equivalence/refinement order; the evidence-vs-negation distinction
under three-valued `Sat`). One finding (`D-1`) sits precisely at a seam the existing
missingness taxonomy does not cleanly resolve — a real, narrow, identified gap, not a
sweeping one. **Recommendation: another experiment has higher information value than "Zero
Closure"** — see §12.

## 2 · What the Cohesion track established

Re-cited, not re-argued (full evidence in the cited reports): 11 semantic mechanisms checked
at the raw `L3` fact level, 9 byte-identical, 2 legitimate language-specific surface
differences with every decisional field identical, 0 semantic divergences. `D-1`:
`IndeterminateBehaviourReference`, a zero-field fact kind for an observed-but-unnameable
target, implemented, real-corpus-validated, formally incorporated. `D-4`/`D-5`: zero genuine
corpus occurrences, explicitly not built, explicitly distinguished from "impossible."

## 3 · What the theory programme established (read this pass, not assumed)

- **`Sat : 𝒦 × ℛ → {⊤, ⊥, U}`**, three-valued, over eight requirement classes (content,
  evidence, provenance, status, consistency, governance, temporal, operational) —
  `B-model-and-experiments.md`.
- **`Zero` has (at least) three non-equivalent readings** (`Zero_strict`, `Zero_weak`,
  `Zero_kleene`) that disagree even on a fully-determined case; of seven candidate readings
  for what `Zero` *is*, two are refuted (state; **missingness representation** — "all four
  unknown kinds collapse to `U`; `Zero` is coarser than the missingness taxonomy").
- **A four-kind missingness/observability taxonomy**: `UNOBSERVED`, `UNINTERPRETED`,
  `UNDERDETERMINED` (agent-remediable — *"content can be undetermined because rivals are
  live"*), and `UNOBSERVABLE` (world-blocked — *"nobody"* can remediate it)
  — `K-typed-facet-vocabulary.md`, `G-rerun-with-evaluators.md`.
- **Five relations forming a `λ`-relative refinement order**, not a blanket
  non-substitutability rule: `struct_eq(=) ⟹ prov_equiv(≅_λ) ⟹ sem_equiv(≡) ⟹ obs_equiv(≈)`,
  legitimate in 7 of 20 directions (the implications), illegitimate in the other 13 — and the
  order itself moves with which fields `λ` includes.

## 4 · Crosswalk

| Empirical phenomenon | Existing theory concept | Formal relation | Evidence | Status |
|---|---|---|---|---|
| `L3` fields differ (`qualifierKind`) but decisional fields + `L4`/`L5` converge | the five-relation refinement order, `λ`-relative | this is `obs_equiv(≈)` under a `λ` that **excludes** `qualifierKind` and includes `{targetUnitRelation, determinability, referenceMode, accessMode}` — not `struct_eq`, since the raw facts literally differ | `2026-09-28-KOS-L3-parity-spot-check-5-rules.md` §5/§10 (round 2); `B-model-and-experiments.md` E2 | **SUPPORTED** — precise, field-named correspondence, confirmed 3 independent times empirically; **reflexivity/symmetry** hold trivially on the tested pairs, **transitivity untested** (only 2-language pairs were ever compared, never a 3-way chain) |
| `D-4`/`D-5`: zero corpus occurrences ≠ impossible | `Sat`'s `evidence` class: `⊨ E_min` (⊤) vs. `⊨ ¬E_min` (⊥) vs. otherwise (`U` — *"existence ≠ sufficiency"*) | my closure's own language ("no current evidence justifies building it," explicitly not "cannot occur") is exactly `U` under the evidence class, not `⊥` | `2026-09-28-KOS-D4-characterization-closure.md` §7 (verbatim conclusion); `B-model-and-experiments.md` `Sat` table | **ESTABLISHED** — this is not an analogy; the closure report already reasons in exactly this distinction, independently arrived at, before this crosswalk was ever written |
| `D-1`: relationship observed, target unnameable | the four-kind missingness/observability taxonomy | see §5 below — does not cleanly fit any single kind | `2026-09-28-KOS-D1-implementation-results.md`; `K-typed-facet-vocabulary.md` | **PLAUSIBLE, with an identified seam** — not a clean match to any of the four kinds as read; see §5 |
| Canonicalization (`L3` as a common representation for two source languages) | an explicit "canonicalization" primitive in the theory | none found in the material read this pass | searched; not located in `A`/`B`-executive/model files, `EPISTEMIC-STATUS-VOCABULARY.md`, `K-typed-facet-vocabulary.md` | **UNKNOWN** — absence-of-evidence under this pass's own bounded search, not a claim the theory lacks the concept |
| Provenance (PHP/Python adapters keep no source-level provenance in `L3`, by `INV-4`/`INV-L3-7`) | the theory's `provenance` `Sat` class and `prov_equiv(≅_λ)` relation | superficially similar naming; not investigated for a substantive match this pass | — | **UNKNOWN** — flagged, not investigated (research economics: lower expected information gain than `D-1`'s seam, given the bounded scope of this gate) |

## 5 · Where `D-1` actually sits — the one precise gap

`$this->$m()`: the call site is **observed** (the source line is read, unambiguously — this
rules out `UNOBSERVED`). The target is not merely **uninterpreted** (`UNINTERPRETED` implies
more analytical effort on the *same* evidence could resolve it — it cannot: no amount of
static re-reading determines a runtime value). It is not quite the taxonomy's ordinary
`UNDERDETERMINED` either: that kind's own worked cases (`B2-revision`, `B3-no-source` in
`F-satc-semantic-closure-experiment.md`) describe **a small, enumerable set of live rivals**
narrowing over more evidence. `D-1`'s "rivals" are **every method name the receiver's class
could legally have** — unbounded and, critically, **not narrowable by more static evidence at
all**, only by executing the program. It is closest to `UNOBSERVABLE` (*"world-blocked...
nobody [can act]"*) — except it is not absolutely unobservable: a *different* agent (a runtime
tracer, a debugger) resolves it trivially. **The taxonomy's `UNOBSERVABLE` kind does not
appear to distinguish "unobservable in principle" from "unobservable by this specific analysis
method, observable by another."** That distinction is exactly what `D-1`'s own `expected.json`
incorporation text already states in different words (*"a real dependency may exist, but the
analysis cannot determine the target reliably"* — method-relative, not world-relative).

**This is Case 2** (§10): the theory explains most of what Cohesion observed, but this one
construct sits at a seam the four-kind taxonomy, as read, does not resolve. It is a narrow,
precisely-locatable gap — not a claim that the taxonomy is wrong, and not evidence it needs a
fifth kind rather than a clarified reading of `UNOBSERVABLE` itself.

## 6 · Mathematical correspondences

- **The `λ`-relative refinement order (§4 row 1)** is the strongest, most precisely-evidenced
  correspondence — a genuine formal structure (a preorder under implication, not a blanket
  equivalence), with a concrete, field-named witness from real execution, not argument.
- **No new structure is proposed here.** Per the instruction's own discipline: a structure is
  only worth proposing when grounded in evidence that an *existing* one cannot represent — and
  §4's other rows either fit an existing structure (`Sat`'s evidence class) or are flagged
  `UNKNOWN`, not forced into a new one.

## 7 · Logic/epistemic correspondences

`D-4`/`D-5`'s "not observed ≠ impossible" is, precisely, the `Sat` evidence class's `U` vs.
`⊥` distinction — not a new logical category KnowledgeOS needs, but a confirmed instance of
one it already has. `D-1` is where the logic gets interesting: it is a case the eight `Sat`
classes' own list does not obviously contain a home for — "identity of an otherwise-confirmed
relationship's target" is not quite `content` (existence is confirmed), not quite `evidence`
(sufficiency is not the axis — no amount of evidence-gathering within the method resolves it),
and not cleanly any of the other six. This is reported as a genuine open logical question, not
resolved here.

## 8 · Statistical considerations

`D-4`/`D-5`'s corpus counts (0 genuine PHP occurrences, 0 genuine Python occurrences of the
literal-name analogue) are population counts over a **specific, bounded, named corpus** (this
project's own `app/`, and the 153-file top-level CPython 3.13.2 stdlib) — not a claim about
PHP/Python code in general. `P(observed | mechanism exists in this corpus)` was measured
directly (exhaustive grep + AST scan, not sampling); `P(mechanism exists | not observed)` was
never computed and is not claimed — the closure report's own wording ("no evidence justifies
building it") was already careful not to conflate the two. No statistical significance is
claimed or manufacturable from a population this small; none is asserted.

## 9 · Potential ML-assisted investigations (not performed)

Two candidates, both discovery-only, neither executed:
1. **Embedding/keyword similarity search** across the theory-construction corpus
   (`docs/knowledgeos/knowledgeos_theory_chronological_extraction/`, `brainstorming/`) for
   "canonicalization"-adjacent terms — would directly resolve §4's `UNKNOWN` row, cheaply,
   without reading the corpus exhaustively by hand.
2. **Clustering of the theory programme's 46 theory objects** against the Cohesion track's own
   findings, to surface other candidate correspondences this bounded, hand-driven crosswalk
   missed. Neither was run — the bounded manual search already answered the highest-value
   questions (§4); ML search is flagged as a cheap next step if the crosswalk is extended, not
   performed now, consistent with "ML must not become the semantic authority" and with not
   building an instrument the current question doesn't yet need.

## 10 · Confirmed gaps

Exactly one: `D-1`'s target-indeterminacy does not cleanly fit the four-kind missingness
taxonomy's `UNOBSERVABLE` kind as currently worded (§5). Not confirmed as requiring a new
primitive — confirmed as requiring the taxonomy's own authors to clarify whether
`UNOBSERVABLE` is agent-relative or absolute.

## 11 · Contradictions

None found. `D-1` and `D-4`/`D-5` are consistent with every theory structure checked against
them (§4); the one gap (§5) is an underspecification, not a contradiction — nothing in the
Cohesion evidence asserts something the theory denies.

## 12 · Theoretical implications

`Sat`'s evidence-class distinction (`U` ≠ `⊥`) is now independently corroborated by a
completely separate empirical track that was never designed with `Sat` in mind — this is
exactly the kind of independent-adopter evidence `ES-006.1`-style promotion criteria (seen
elsewhere in this repo's own governance) would treat as meaningful, though promoting anything
is explicitly out of this gate's authority. The `λ`-relative refinement order likewise gains a
second, independent empirical witness beyond the original simulation's synthetic cases.

## 13 · Candidate next experiment

**Not the Zero Closure Decision Experiment.** That experiment resolves which `Zero` reading
is normative — a question §4-§5 never touched, and not the seam the Cohesion evidence
actually points at. The higher-information-value candidate:

> **Is `D-1`'s target-indeterminacy an instance of `UNOBSERVABLE` under an agent-relative
> reading, or does the missingness taxonomy need a fifth kind ("method-relative
> unobservable")?**

## 14 · Why this experiment has high information value

It is **directly motivated by real, already-collected evidence** (`D-1`'s implementation and
corpus validation, done, not hypothetical), **cheap** (a definitional/classification
question, not a new simulation build), and **decisive**: either the taxonomy's authors
confirm `UNOBSERVABLE` was always meant agent-relatively (closing the gap with zero new
structure), or they confirm it wasn't, in which case a fifth kind is a small, well-evidenced,
minimal addition — not speculative machinery.

## 15 · Falsification criteria

**H0** (agent-relative reading already covers `D-1`): falsified if the taxonomy's own
worked examples elsewhere in the corpus already show `UNOBSERVABLE` applied to an
agent-relative case (i.e., something observable by a different method) — this pass did not
find such a case, but did not search exhaustively for one either (bounded search, §9).
**H1** (a fifth kind is genuinely needed): falsified if H0's search succeeds.

## 16 · Stop condition

This gate stops here. It does not choose between H0/H1 — that is exactly the next experiment's
job, and this gate's own governance boundary forbids resolving it by fiat.

## 17 · Explicitly deferred work

The `Sat`↔provenance correspondence (§4, `UNKNOWN`); the ML-assisted corpus search (§9); any
work on `Zero` itself, including its own three-reading disagreement — genuinely interesting,
but not connected to the Cohesion evidence by anything found this pass, and therefore not
this gate's business to advance.

---

**STOP after this report.** The theory-construction programme's `⛔⛔ RESEARCH STOPPED` state
is unchanged. No experiment executed. Not committed.

**Traceability:** `docs/knowledgeos/research/theory-v1.2-simulation/{A-executive-result,
B-model-and-experiments,FINAL-VERDICT}.md` · `docs/knowledgeos/research/theory-v1.2-
simulation/{F-satc-semantic-closure-experiment,G-rerun-with-evaluators,K-typed-facet-
vocabulary}.md` · `docs/knowledgeos/governance/EPISTEMIC-STATUS-VOCABULARY.md` ·
`docs/knowledgeos/knowledgeos_theory_chronological_extraction/KNOWLEDGEOS-RESEARCH-STATE.md`
(the STOP state) · `2026-09-28-KOS-L3-language-neutrality-research-closure.md` ·
`2026-09-28-KOS-D1-implementation-results.md` · `2026-09-28-KOS-D4-characterization-
closure.md`.
