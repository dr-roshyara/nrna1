# Formal Logical Analysis of the Seven Kernel Candidates

**Date:** 2026-09-30. Track A (theory), explicitly not gated by Track B (empirical validation, still
blocked on cross-author material this repository cannot supply). No candidate is added, removed, or
frozen. Governing principle throughout, per explicit instruction: **empirical status ≠ logical
primitiveness** — a candidate can be well-evidenced and still not be a kernel primitive, if it is
definable in terms of the others.

**Independently reviewed (`kos-theory-reviewer`) — verdict `FAIL` on the central conclusion.** The
first version claimed a reduction to 2–3 primitives. The review found this collapses under scrutiny:
most "`DEFINITIONAL`" dependency tags were artifacts of how the definitions were *worded*, not
independent logical discoveries (circular by construction), a direct real counterexample exists in
this corpus's own prior work against the `Provenance` reduction specifically
(`EKS-PKS-Relationship.md` §4: *"Provenance: Supported in EKS (144/144 grants, strong)"* — grants are
canonical records, not derived artifacts, so `Provenance`'s meaning is not confined to tracing derived
artifacts back to sources, contrary to what the reduction required), the `Authorization`/`Governed
lifecycle` "co-definition" claim contradicted its own stated tags (one direction was already labeled
weak/empirical, which cannot support a claim needing both directions to be definitional), the `Rule/
classification` reduction silently dropped a component (a decision procedure) its own definition
required, and an `H4`/`H5` citation was inherited-and-wrong (neither is actually about
authorization-vs-execution). **All corrected below. The honest headline finding of this document
changed as a result**: the reduction attempt mostly failed, and *why* it failed — definitions that
presuppose their own conclusion — is itself the most defensible finding here, not the reduction
itself.

## Method

For each candidate: `Definition → Necessary conditions → Sufficient conditions → Counterexample
search → Dependencies (logical/definitional, not merely empirical co-occurrence) → Reducibility`.
Every dependency claim is tagged either **`DEFINITIONAL`** (candidate B's own definition cannot be
stated without candidate A's concepts) or **`EMPIRICAL_COOCCURRENCE`** (B and A have so far always
appeared together in evidence, a weaker claim) — this distinction was specifically missing from
Track A's first draft and is applied deliberately here from the start.

## 1 · Observation

- **Definition**: the act of recording a fact from primary evidence, prior to any classification.
- **Necessary conditions**: a fact and a recording act. Nothing else.
- **Sufficient conditions**: does Observation alone establish anything about authorization,
  provenance, or canonical status? **No** — it is a precondition for all of them, not a discriminator
  between them.
- **Counterexample search**: not run this round (already withdrawn as kernel-level in the prior
  assessment; not re-opened here).
- **Dependencies**: none — this is the logically *first* concept. **Corrected**: the original claim
  that "every other candidate's definition below refers to 'recorded facts' or 'evidence'" is false —
  checked directly against §§2, 4, 5 below, `Canonical representation`, `Authorization`, and `Governed
  lifecycle` are defined without mentioning facts or evidence at all; only `Provenance` and
  `Uncertainty` do. The weaker, defensible claim: an artifact or act cannot exist to be classified by
  *any* of the seven candidates unless it was first observed to exist — a precondition on the whole
  domain of discourse, not a premise built into each individual definition's wording.
- **Reducibility**: **not reducible to the others** (it is prior to them), but **not governance-
  discriminating** either — every governance mechanism and every non-governance mechanism alike
  requires observation to function at all. **Status unchanged: logically primitive, but not a useful
  kernel primitive** — a primitive that fails to distinguish governed from ungoverned systems doesn't
  earn kernel status on that basis alone. This is the same reasoning the prior withdrawal used,
  restated more precisely here, not re-derived from new evidence.

## 2 · Canonical representation

- **Definition**: the identification of which artifact, among several related ones, is the source of
  truth — with all others in that relation classified as derived (projections, views, records).
- **Necessary conditions**: at least two related artifacts and a directional relation ("derived
  from") between them.
- **Sufficient conditions**: does knowing "X is canonical, Y is derived" establish whether an act
  involving Y was *authorized*? **No** — `GOLD-001` shows a canonical grant record can exist and still
  not cover a specific later act; canonicality and authorization are answers to different questions.
- **Counterexample search**: this candidate's core claim (`Derivation → ¬Authority-inheritance`) is
  the same claim already tested at length in `2026-09-29-KOS-CROSS-ARTIFACT-MINIMAL-THEORY.md`,
  where it was downgraded to `HYPOTHESIS, not yet falsifiable as stated` after two real
  counterexamples were found (`R-61`, `RULE-H6-04`'s practical reliance) and absorbed only via an
  undefined "automatic" qualifier. **That status is inherited here unchanged, not re-tested** — this
  candidate's logical primitiveness is being assessed independently of whether its strongest current
  formulation is falsifiable.
- **Dependencies**: none identified that this candidate depends on.
- **Reducibility**: **not obviously reducible to any other candidate** — it is the only one of the
  seven that is fundamentally about a *relation between two artifacts* rather than about an *act* or
  a *state*. Tentatively primitive.

## 3 · Provenance

- **Definition, corrected and broadened**: a traceable link from an artifact or act back to the
  specific act that produced or authorized it (who, when, under what authority-context) — **not
  confined to *derived* artifacts**, as the first version wrongly assumed.
- **Necessary conditions**: an artifact/act and a prior act it can be traced to.
- **Sufficient conditions**: does provenance alone establish authorization? **No** — `R-43`'s own
  case shows a fully-cited, fully-traceable record whose underlying execution evidence was later
  found `UNDETERMINED`; tracing to a source doesn't establish the source's own truth-value.
- **Counterexample search — a real one was found, and it's decisive.** `EKS-PKS-Relationship.md` §4
  rates `Provenance` *"Supported in EKS (144/144 grants, strong)."* Grants are **canonical records**,
  not derived artifacts (per this same document's own §2 definition of `Canonical representation`) —
  yet every one of them is required to carry provenance (`humanActRef`). **This directly refutes the
  claim that provenance only applies to the derived/non-canonical side of the canonical-representation
  relation.** Provenance is required of canonical acts themselves, not only of things derived from
  them.
- **Dependency on `Canonical representation`: downgraded from `DEFINITIONAL` to withdrawn.** The
  original claim was circular: it followed from a *definition of provenance* that had been narrowed,
  specifically and without independent justification, to "derived artifacts" — once that narrowing is
  removed (per the correction above, forced by the 144-grants evidence — `R-61` is unrelated to
  provenance and was wrongly cited here in an earlier draft), the definitional
  dependency evaporates. Whether some weaker, genuinely independent dependency exists is untested.
- **Reducibility: withdrawn.** `Provenance` is not shown to reduce to `Canonical representation`.
  It remains a candidate primitive in its own right, unchanged from its prior empirical status.

## 4 · Authorization / commitment-gate

- **Definition**: an act, attributable to a non-derived authority, that transitions a governed
  artifact from not-yet-approved to approved for some specific scope.
- **Necessary conditions**: an authority act that is itself not merely a derived representation, or
  the regress never terminates. **Corrected**: the original wording cited this as established "per
  `Canonical representation`'s own finding" — but that finding is itself only `HYPOTHESIS, not yet
  falsifiable as stated` (§2), and `R-61` is a direct real case of a derived artifact (an assessment)
  gaining authority. Stated here as a working requirement of this definition, not as something proven
  elsewhere and imported.
- **Sufficient conditions**: does authorization alone establish that the authorized work was
  *executed*? **No.** **Corrected citation**: the original version cited `H4`/`H5` for this, which is
  wrong — `H4` (`¬CitesGrant → Unauthorized`, `INVALID`) is about citation, and `H5`
  (`¬DiscoverableByModel(E) → ¬Exists(E)`, `INVALID`) is about discoverability; neither is about
  execution. The real support is narrower: `R-51` (WP: *"authorization permits execution... it does
  not itself perform it"*) alone.
- **Counterexample search**: extensively performed already (`H1`–`H8` in
  `2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md`); not repeated here.
- **Dependencies**: **on `Governed lifecycle`: `HYPOTHESIS`, `EMPIRICAL_COOCCURRENCE` only, per
  Track A's own downgrade** (a one-off, single-stage gate with no prior lifecycle stages was never
  ruled out). **On the non-derivation requirement itself: `DEFINITIONAL`** — "authorization" as
  defined above *requires* the authority act to be non-derived; this is part of the definition, not
  an empirical finding about which cases happen to satisfy it.
- **Reducibility**: **not reducible to `Canonical representation` alone** — canonicality tells you
  what *isn't* derived, but not that any specific non-derived act actually occurred and conferred
  approval. Requires an additional primitive: an *act* concept, not just a *representation* concept.
  Tentatively primitive, pending the lifecycle-dependency question below.

## 5 · Governed lifecycle

- **Definition**: an ordered (or partially ordered) sequence of stages a governed artifact or act
  passes through, each potentially requiring its own gate.
- **Necessary conditions**: at least two distinguishable stages and a transition relation between
  them.
- **Sufficient conditions**: does a lifecycle alone establish that any given stage's gate was
  actually satisfied for a specific instance? **No** — the stages *existing* (`WP`'s
  `PLAN→EXECUTION→ACCEPTANCE`) is separate from whether a *specific case* actually passed each gate.
- **Counterexample search**: `RULE-H6-02` (eventual consistency, no discrete gate at all, just
  tolerance for staleness) shows a lifecycle-shaped process without a governance gate — already used
  in Track A as a counterexample to "Authorization depends on lifecycle."
- **Dependencies**: **on `Authorization`, to distinguish a *governed* lifecycle from a mere *state
  machine*: `HYPOTHESIS`, stipulative rather than a discovered necessity** — this is a word choice
  (this corpus happens to call gated sequences "governed"), not a demonstrated logical requirement.
  `RULE-H6-02` remains an unresolved test case: is its ungated, merely-tolerant-of-staleness sequence
  "governed" or not? The document that names it never says.
- **Reducibility — corrected, the "co-defining" claim withdrawn.** The first version of this
  document claimed `Authorization` and `Governed lifecycle` are co-defining, needing both directions
  to be definitional. But its own tags only supported one direction as `EMPIRICAL_COOCCURRENCE`
  (weak) and the other as stipulative (also weak) — neither direction was ever shown definitional, so
  "co-defining" was not earned by the evidence presented, and is withdrawn. **Reverting to Track A's
  original, more modest framing**: a plausible but undemonstrated one-way dependency
  (`Authorization` on `Governed lifecycle`), `HYPOTHESIS`, with a live counterexample
  (`RULE-H6-02`) still unresolved either way.

## 6 · Rule / classification

- **Definition**: a governing statement that sorts acts/artifacts into a closed set of categories
  (e.g. `AUTHORIZED`/`NOT_APPLICABLE`/`UNDERDETERMINED`).
- **Necessary conditions**: a classification vocabulary (the categories) and a decision procedure.
- **Sufficient conditions**: does a rule, on its own, authorize anything? **No** — `H1` (`CitesWorkItem
  → Authorized` is `INVALID`) shows a classification rule can exist and still fail to establish
  authorization by itself.
- **Counterexample search**: `H1`–`H8` cover this extensively already.
- **Dependencies**: **on `Canonical representation`, for the category-space component only:
  `HYPOTHESIS`, not `DEFINITIONAL`** — the original version called this definitional by equivocation:
  §2 defines `Canonical representation` as a relation *between two or more related artifacts, one
  derived from another*; a closed verdict vocabulary (a list of category names) has no derived
  artifacts in play at all, so it doesn't obviously fit that definition without stretching it. On
  `Governed lifecycle`: `EMPIRICAL_COOCCURRENCE` only, unchanged.
- **Reducibility — corrected, the reduction withdrawn.** The first version proposed `Rule/
  classification = Canonical representation + Governed lifecycle`, but this drops the **decision
  procedure** this same section's own "Necessary conditions" line requires — a category vocabulary
  plus a lifecycle stage does not, by itself, supply the *rule* that maps evidence to a category (an
  inference like `H1`'s `CitesWorkItem(x,w) → Authorized(x)` is a procedure, not a representation or a
  stage). A reduction that omits a component its own analysis called necessary is not a reduction.
  **Withdrawn.** `Rule/classification` remains a candidate primitive, unchanged from its prior
  (already comparatively weak) empirical status — that weakness stands on its own evidentiary
  grounds, not on this failed reduction attempt.

## 7 · Uncertainty / indeterminacy

**Corrected framing, per explicit instruction**: dropping "strongest candidate" from working
vocabulary. Restated: *Uncertainty/indeterminacy currently has substantial evidence in
governance-determination artifacts, but its domain and logical status remain unresolved* — and this
round's logical analysis adds a specific reason that status is unresolved:

- **Definition**: a state in which available evidence is insufficient to resolve a classification
  attempt (an `UNDERDETERMINED` verdict; `R-53`'s undissolved `FALSE`-or-`UNSUPPORTED` disjunction).
- **Necessary conditions**: a classification *attempt* must already exist to be "insufficient" for —
  you cannot be uncertain about a classification that was never attempted.
- **Sufficient conditions**: does uncertainty, on its own, tell you anything about authorization,
  provenance, or canonical status? **Mixed, corrected** — `R-53`'s own case is not purely a report
  *about a classification attempt*: it states plainly that whether the underlying gate was *actually
  executed* is itself unknown — a first-order fact about the governed act, not only a second-order fact
  about the record's epistemic status. The definition above needs to accommodate both: uncertainty
  can attach to the record's own classification *and* to first-order facts the record depends on.
- **Counterexample search**: `R-53`, above, is itself a partial counterexample to a purely
  second-order reading.
- **Dependencies on `Rule/classification`: downgraded to `HYPOTHESIS`, not `DEFINITIONAL`, given the
  `R-53` counterexample just found.** The second-order framing below is **not novel to this
  document** — `Track A` (`2026-09-30-KOS-KERNEL-THEORY-TRACK-A.md`) had already observed that
  needing an "unresolved" bucket for edge cases "is a common feature of most classification schemes"
  (quoted exactly), and `M4`'s own 4-state vocabulary already
  implements exactly this in practice; crediting that prior observation rather than re-claiming it as
  new. **`Uncertainty/indeterminacy` may be *partly* second-order** (a property of classification
  attempts using the other concepts) **and partly first-order** (per `R-53`) — a cleaner single
  classification was not achieved this round.
- **Reducibility**: **`HYPOTHESIS`, weakened by the `R-53` counterexample above** — a purely
  second-order reading ("not a first-order governance fact itself") is not fully supported; `R-53`
  shows uncertainty can attach directly to a first-order fact (whether an act occurred) as well as to
  a classification attempt about it. If a partial second-order reading survives further testing, the
  practical consequence (every first-order classification scheme needs an explicit undetermined
  state) is, as originally noted, already implemented by `M4`'s 4-state vocabulary — but this
  document did not discover that principle; `Track A` had already stated the closely related "generic
  to classification schemes" observation.

## Candidate minimal primitive set — corrected: the reduction attempt mostly failed, and that is the real finding

**The first version of this section claimed a reduction to 2–3 primitives. Three of its four
reduction arguments did not survive review**: `Provenance→Canonical representation` had a direct
counterexample (`EKS-PKS-Relationship.md`'s own 144/144-grant rating) and was withdrawn;
`Authorization`/`Governed lifecycle` "co-definition" contradicted its own stated tags and was reverted
to a plain, weaker one-way `HYPOTHESIS`; `Rule/classification`'s reduction silently dropped a
component (a decision procedure) its own analysis called necessary, and was withdrawn. Only
`Uncertainty/indeterminacy`'s second-order framing survives in weakened form, and even that framing
was not original to this document.

```
STATUS AFTER CORRECTION — all seven candidates remain candidates; NO reduction was
successfully demonstrated this round:

  Canonical representation   -- candidate, not reduced (tentatively primitive;
                                  reducibility not tested against it this round)
  Authorization               -- candidate, not reduced (tentatively primitive; one
                                  dependency on Governed lifecycle remains a weak,
                                  undemonstrated HYPOTHESIS; its own non-derivation
                                  requirement is itself a wording-dependent
                                  DEFINITIONAL tag of the same kind flagged below --
                                  not exempted from that caution)
  Provenance                  -- candidate, not reduced (reduction attempt withdrawn,
                                  refuted by a real counterexample)
  Rule/classification         -- candidate, not reduced (reduction attempt withdrawn,
                                  its own necessary conditions weren't met by it)
  Governed lifecycle          -- candidate, not reduced (never itself tested for
                                  reducibility this round)
  Uncertainty/indeterminacy   -- possibly partly second-order, partly first-order;
                                  not cleanly resolved
  Observation                 -- unchanged: logically prior, not governance-
                                  discriminating
```

**The genuinely valuable finding of this pass is not a reduction — it's three distinct, real errors
caught in how the reduction attempts were made, not one single failure mode.** Corrected, precisely:
the `Provenance` reduction was **circular by construction** — its "derived artifacts only" narrowing
directly produced the dependency it then claimed to discover (this one genuinely fits "a definition
worded to presuppose its conclusion," and `Authorization`'s own non-derivation tag, flagged above, is
the same kind of case, not yet corrected). The `Rule/classification` reduction was an **incompleteness
error** — it dropped a component (a decision procedure) its own stated necessary conditions required,
unrelated to circular wording. The `Authorization`/`Governed lifecycle` "co-definition" claim was an
**internal-consistency error** — it asserted a conclusion its own stated tags didn't support, a
bookkeeping mistake, not a definitional trap. Lumping these three together as one pattern would itself
be an overclaim; each is recorded separately as what it actually was. This is
here as the actual result of this research cycle, not a failure to reach one.

## What this does NOT establish

This is a logical-structure analysis, not new empirical evidence, and — after correction — not a
successful reduction either. Nothing here strengthens or weakens any candidate's prior evidentiary
support except where explicitly flagged above (`Uncertainty/indeterminacy`'s ranking narrowed). A
reduction claim that fails under scrutiny does not retroactively strengthen the candidate it tried to
reduce — it only leaves that candidate's original empirical status (`Provenance`'s "strong" `EKS`
rating, unchanged) intact rather than diminished.

## Next smallest decisive experiment

All three reduction attempts this round were withdrawn under scrutiny rather than needing a further corpus
search — `Provenance`'s already had a direct real counterexample found by inspection
(`EKS-PKS-Relationship.md`'s own prior rating), and `Rule/classification`'s failed on its own stated
necessary conditions without needing new evidence. The smallest decisive next step is therefore not
another reduction attempt, but the narrower, still-open question this round left genuinely
unresolved: does `RULE-H6-02` (an ungated, merely-tolerant-of-staleness sequence) count as a
"`governed` lifecycle" or not? Answering that one real case directly would settle the
`Authorization`/`Governed lifecycle` dependency question's direction, which this round could only
downgrade to `HYPOTHESIS`, not resolve.

---

**Traceability:** `2026-09-29-KOS-EKS-PKS-Relationship.md` §4 (prior candidate status) ·
`2026-09-30-KOS-KERNEL-THEORY-TRACK-A.md` (source of the Authorization/Governed-lifecycle dependency
question; this document's attempted "co-definition" upgrade of that question was withdrawn, reverting
to Track A's original one-way `HYPOTHESIS`) · `2026-09-29-KOS-CROSS-ARTIFACT-MINIMAL-THEORY.md`
(source of `Canonical representation`'s inherited falsifiability status) ·
`2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md` (`H1`–`H8`, reused without re-deriving).
