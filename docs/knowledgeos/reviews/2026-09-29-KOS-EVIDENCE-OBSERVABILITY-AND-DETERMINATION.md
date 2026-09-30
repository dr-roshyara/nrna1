# Evidence Observability and Determination — from Document Consolidation to Empirical Theory

**Date:** 2026-09-29. Corpus is evidence, not authority. This document does not resolve `OQ-11`
(EKS/PKS boundary), does not evaluate the externally-referenced `K5=(S,A,R)` candidate (no source
material for it exists anywhere in this repository — confirmed independently three times now: this
session directly, the adversarial review, and again here), and starts no ML training. All numbers
below are computed directly from real repository data (22 work-item ledgers, 144 grants, 274
transitions, 379 `KOS`-citing commits) via scripts run this session; none are estimated.

---

## 0 · Correction — the "1.2%" claim, reframed

The prior report (`2026-09-29-KOS-GOVERNANCE-CONSTRAINT-OBSERVABILITY.md` §5) stated: *"243 clauses
extracted, 3 (1.2%) `DERIVABLY_OBSERVABLE`, 240 (98.8%) `SEMANTICALLY_STATED_NOT_OBSERVABLE`"* and
characterized this as *"a strong, corpus-wide result."*

**This was an overclaim, correctly identified and corrected here rather than defended.** The
experiment tested exactly one extraction procedure: scanning prose following 8 marker phrases
(`does not authorize`, `must not`, …) for one representation of concreteness — a filename, session
id, grant reference, or date token appearing literally in the clause. That procedure measures:

> *How many governance constraints are machine-extractable **in this one chosen textual
> representation**?*

It does not measure, and was wrongly presented as measuring:

> *How many governance constraints are observable in principle, under any representation?*

§4–§5 below re-run the observability question against a second, structurally different
representation — the ledgers' own schema fields, rather than prose-mined tokens — and find a
sharply different number for a dimension the first experiment never isolated. The corrected
reading, stated precisely, is in §4.4.

---

## 1 · Research question (reframed, per instruction)

> What minimum set of observable evidence is required to deterministically determine
> authorization, temporal validity, attribution, or underdetermination of a governed engineering
> action in this repository — and how does the answer depend on which representation of "observable"
> is chosen?

This subsumes rather than replaces the prior formulation ("what fraction of constraints are
mechanically extractable") — that was one instance of the general question, evaluated against one
representation. This document treats representation as a variable, not a constant.

## 2 · Corpus (unchanged from prior work, re-used, not re-gathered)

- 22 real work-item ledgers, `.claude/runtime/workflow/*.json`.
- 144 grants across those ledgers, 144/144 with a `humanActRef` field populated.
- 274 transitions across those ledgers.
- 379 commits matching `git log --grep="KOS-" -i --all` (the citation-filtered population used by
  the S1–S4 ablation in `evidence-fusion-model.md` §11).
- The one independently-verified ground-truth-labeled case: commit `9f83a369c` (real, disclosed
  authorization violation, found by a citation-independent file sweep, not by `Fuse`).

No new corpus was gathered. This phase re-examines the same corpus through a wider observation
lens.

## 3 · Observation model

An observation is **DIRECTLY_OBSERVABLE** when it is a structured field present by schema
guarantee (no extraction, no interpretation — reading the field *is* the observation).
**DERIVABLY_OBSERVABLE** when a deterministic extraction rule (regex, exact-match, path lookup)
recovers it from unstructured content, with a disclosed, non-zero failure/ambiguity rate.
**SEMANTICALLY_STATED_NOT_OBSERVABLE** when the content exists in prose but no extraction rule
achieves it deterministically — a human reader can understand it, a machine cannot recover it
without judgment. **UNKNOWN** when insufficient data exists to classify at all.

This is the same four-state vocabulary as the prior experiment; what changes here is testing it
against more than one representation per dimension.

## 4 · Evidence dimensions — observability matrix

Two representations are tested per dimension where both apply: **(schema)** = reading a structured
field directly; **(prose)** = extracting from free-text content (`humanActRef`, `scope`, commit
messages). Where only one representation exists in this corpus, only that row is given.

| Dimension | Representation | Observability | Basis |
|---|---|---|---|
| IDENTITY (work item) | schema | `DIRECTLY_OBSERVABLE`, 22/22 | `workItem` field, always present |
| WORK_ITEM (which item a grant belongs to) | schema | `DIRECTLY_OBSERVABLE`, 144/144 | ledger structure itself |
| GRANT (existence, id, status, authority) | schema | `DIRECTLY_OBSERVABLE`, 144/144 | `grants[]` array fields |
| SCOPE (what a grant permits) | schema | `DIRECTLY_OBSERVABLE` (field exists) but **content is prose** | `scope` field present 144/144, but is free text |
| SCOPE (specific negative/forbidden content) | prose | `DERIVABLY_OBSERVABLE`, 3/243 clauses (1.2%) tested representation; see §0 | `GOVERNANCE-CONSTRAINT-OBSERVABILITY.md` §5, now correctly scoped to this one dimension×representation pair, not generalized |
| ARTIFACT (file touched by a commit) | schema (git) | `DIRECTLY_OBSERVABLE`, 100% | `git diff --name-only`, always deterministic |
| PROTECTED-ARTIFACT (is a file under a scope restriction) | prose-derived rule | `DERIVABLY_OBSERVABLE`, 1/22 work items only | `PROTECTED-ARTIFACT-SWEEP.md` §4 — only `KOS-CONTRACT-NEUTRALITY-001` has a file-level derivable rule |
| COMMIT (hash, date, author, message) | schema (git) | `DIRECTLY_OBSERVABLE`, 100% | git object model itself |
| BRANCH | schema (git) | `DIRECTLY_OBSERVABLE`, 100% | git ref, though historical branch-at-commit-time is lossy after rebase/merge — `DERIVABLY_OBSERVABLE` for *historical* branch, not `DIRECTLY_OBSERVABLE` |
| ACTOR (git author identity) | schema (git) | `DIRECTLY_OBSERVABLE` as a string, but **not attestable** — INV-ATTR-1/2 apply; the field exists, its truth-value as "who really acted" does not follow from its presence | `git log --format=%an/%ae` |
| SESSION (which AI session recorded a transition) | schema | `DIRECTLY_OBSERVABLE` where populated: 200/274 transitions (73.0%) carry a `session` value; 74/274 (27.0%) do not | computed this session, §below |
| ROLE (declared role of an actor in a transition) | schema | `DIRECTLY_OBSERVABLE` where populated: 75/274 (27.4%); absent in 199/274 (72.6%) | computed this session, §below |
| TIME (grant/transition date) | schema | `DIRECTLY_OBSERVABLE` for transitions (timestamp field); for grants, date is usually embedded in `humanActRef` prose, so `DERIVABLY_OBSERVABLE`, not `DIRECTLY_OBSERVABLE` | field inspection |
| TRANSITION (type, sequence) | schema | `DIRECTLY_OBSERVABLE`, 274/274 | `transitions[]` array |
| CITATION (does a commit cite a work item/grant) | prose (commit message) | `DERIVABLY_OBSERVABLE`, exact-string match; real, already used at scale (S1 of the ablation) | `evidence-fusion-model.md` §11 |
| PROVENANCE (humanActRef present) | schema | `DIRECTLY_OBSERVABLE`, 144/144 (field always populated) — but its **content's verifiability** (does the referenced act actually exist/say what's claimed) is `SEMANTICALLY_STATED_NOT_OBSERVABLE` in general | distinguishing field-presence from content-verifiability, a distinction the prior report conflated |
| TEST (does a PHPUnit/pytest file exist for a capability) | schema (filesystem) | `DIRECTLY_OBSERVABLE`, 100% (file exists or not) | direct `find`, used for the Cohesion `Tests`-placement finding |
| RUNTIME_EVENT (Observation Runtime JSONL entries) | schema | `DIRECTLY_OBSERVABLE` where the pipeline ran; `UNKNOWN` coverage outside its exercised capabilities | not re-measured this pass, inherited `OBSERVED` status from prior work |
| DECISION (verdict recorded) | schema | `DIRECTLY_OBSERVABLE`, closed vocabulary, both EKS and PKS sides | prior work, re-used |
| VERIFICATION (was a claim independently checked) | — | `SEMANTICALLY_STATED_NOT_OBSERVABLE` in general — this whole research programme's own verification steps are themselves not machine-checkable without a human/adversarial-reviewer pass, as this session's own subagent-review episode demonstrates | reflexive finding — verification of verification has no deterministic representation found in this corpus |

### 4.1 · SESSION and ROLE — new real measurements, this pass

```
transitions[].session populated: 200/274 = 73.0%   DIRECTLY_OBSERVABLE where populated
transitions[].role    populated:  75/274 = 27.4%   DIRECTLY_OBSERVABLE where populated
```

Both fields are schema-guaranteed slots (always *present* in the structure), but frequently
`null`/absent in *value*. This is a distinct and important sub-case not captured by the prior
binary DIRECTLY/SEMANTICALLY split: **a field can be `DIRECTLY_OBSERVABLE` in structure while being
`UNKNOWN` in value for a majority of records.** ROLE is the sharper case: 72.6% of transitions carry
no role at all, meaning "who, in what capacity, made this transition" is unanswerable from the
ledger alone for roughly three in four transitions — independent of and prior to any prose-mining
question.

### 4.2 · Historical branch — a representation collapse, disclosed

BRANCH is nominally `DIRECTLY_OBSERVABLE` (current `git branch` is a direct read), but *which
branch a historical commit was made on* is not preserved by git's object model once branches are
merged, rebased, or deleted — it must be reconstructed from reflogs or CI metadata, neither of
which this repository's git history preserves for commits made outside the current session's
visibility. Downgraded to `DERIVABLY_OBSERVABLE`-at-best, `UNKNOWN` in the common case. Not
previously tested; found only by attempting to fill this dimension's matrix row.

### 4.3 · PROVENANCE — presence vs. content, a conflation this fixes

The prior architecture documents' claim *"144/144 grants have `humanActRef`"* is true and
`DIRECTLY_OBSERVABLE` — but it was, in places, read as if it meant the referenced human act is
itself verified. It is not: `humanActRef` is a free-text pointer, and whether the pointed-to act
occurred as described is `SEMANTICALLY_STATED_NOT_OBSERVABLE` absent a second, independent source.
This is the same shape of error as the withdrawn "third independent witness" claim in
`EKS-PKS-Relationship.md` §1.1 — recorded here so it isn't repeated in this document.

### 4.4 · The corrected general finding (replaces the withdrawn §5 framing)

> **Schema-structural facts about governance records (identity, grant existence, transition
> existence, artifact touched, commit identity) are close to 100% DIRECTLY_OBSERVABLE.
> Schema-structural facts about *actors and capacities* (session, role) are DIRECTLY_OBSERVABLE
> only where populated — 73.0% and 27.4% of transitions respectively. The *specific permitted/
> forbidden content* of a scope or authorization boundary remains, in the one tested prose
> representation, only 1.2% DERIVABLY_OBSERVABLE, with the overwhelming remainder
> SEMANTICALLY_STATED_NOT_OBSERVABLE.**

This is three different numbers for three different questions, not one number for "governance
constraints" as a whole. That collapse was the error; it is not repeated here.

## 5 · Predicate definitions

Each predicate lists: source, extraction rule, observation mechanism, direct-vs-derived, and a
disclosed limitation.

- **`WorkItem(w)`** — source: ledger filename/`workItem` field. Direct. Limitation: none found.
- **`Grant(g, w)`** — source: `grants[]` entry. Direct. Limitation: none found.
- **`GrantStatus(g) = AUTHORIZED`** — source: `status` field. Direct. Limitation: status vocabulary
  itself is closed and verified (`OBSERVED`), but a grant's *status* is not the same as the grant's
  *content having been fulfilled as described* (§4.3).
- **`Transition(t, w, session, role, seq)`** — source: `transitions[]` entry. Direct for structure;
  derived-or-unknown for `session`/`role` value, per §4.1.
- **`CitesWorkItem(commit, w)`**, **`CitesGrant(commit, g)`** — source: commit message exact-string
  match. Derived, real, used at scale (S1/S2 of the ablation). Limitation: exact-match only; misses
  paraphrase, and — critically — misses commits that cite *nothing* at all, which is exactly the
  `9f83a369c` blind spot already disclosed in `evidence-fusion-model.md` §11.1.
- **`TouchesArtifact(commit, file)`** — source: `git diff --name-only`. Direct, 100%, no disclosed
  limitation (git's object model is authoritative here).
- **`ProtectedArtifact(file, g)`** — source: manually-derived rule from a grant's own "does NOT
  authorize" text. Derived, and — per §4 — only exists for 1 of 22 work items. Limitation: the rule
  itself had to be hand-derived; no general extraction procedure produces it automatically.
- **`GrantActive(g, t)`** — source: date comparison between a transition's timestamp and a grant's
  (usually prose-embedded) date. Derived where a parseable date exists in `humanActRef`; `UNKNOWN`
  otherwise.
- **`Authorized(g, actor, w, scope, t)`** — **not itself observable as a unit.** It is a composite
  the corpus never records directly; every attempt to test it (§6) has to assemble it from the
  predicates above, each with its own disclosed observability level. This is the central practical
  finding of this section: authorization is not a fact the ledger stores, it is a conjunction the
  ledger's *other*, more directly observable facts must be combined to approximate.
- **`Produced(actor, artifact)`** — source: a commit touching that artifact, attributed to that
  actor's session/role if known. Direct for the touch; `UNKNOWN`/derived for the actor-to-session
  link per INV-ATTR-1/2 (self-declared, not attestable).

## 6 · Valid / invalid logical implications — tested against real data

| # | Implication | Status | Evidence |
|---|---|---|---|
| I1 | `CitesWorkItem(c,w) → Produced(actor,x)` for some `x` under `w` | **INVALID** | citing is textual, producing requires a real diff; many commits cite without touching anything new (docs-only commits, status updates) |
| I2 | `Transition(t,w,r) exists → Produced(actor,x)` | **INVALID** | a transition records a *workflow-state* change, not necessarily accompanied by any file production — e.g. a `REGISTER` transition with no code |
| I3 | `Authorized(g,actor,w,s,t) → Produced(actor,x)` | **INVALID, empirically confirmed this pass** | of 144 grants, only 69 are ever cited by any commit at all (exact-match); **75/144 (52.1%) are authorized grants with zero citing commit, i.e. no observable production traceable to them.** See §6.1 for a caveat on this number. |
| I4 | `TouchesArtifact(c,x) ∧ ProtectedArtifact(x,g) → Unauthorized(c)` | **INVALID as stated** | real counterexample already on record: commit `286cad1e1` touches a protected file but is validly authorized under a *different* work item's grant (`KOS-LCOM4-CONTRACT-001`) — protection is grant-relative, not artifact-absolute |
| I5 | `TouchesArtifact(c,x) ∧ ProtectedArtifact(x,g) ∧ GrantActive(g,t) ∧ ¬CitesWorkItem(c,w_g) → candidate unauthorized event` | **VALID as a flag, not a proof** | this is exactly `Fuse`'s own logic; validated against the one real case (`9f83a369c`) after correction — it correctly *flags a candidate requiring human review*, and was never claimed to prove violation unassisted |
| I6 | `SessionPopulated(t) → ActorAttestable(t)` | **INVALID** | per INV-ATTR-1/2, a self-declared session identifier is evidential, not attestable, regardless of whether the field is populated — populated ≠ verified |

### 6.1 · Caveat on I3's number — representation sensitivity, disclosed rather than hidden

This pass's exact-grantId-string match found 75/144 (52.1%) grants never cited by any commit. An
earlier pass in this same research chain (`evidence-fusion-model.md`, informal count during ablation
construction) used a looser match (exact + partial/fuzzy) and reported 82/144 matched, i.e. 62/144
(43.1%) uncited. **The two methods disagree by 9 percentage points on the same underlying question.**
This is not a contradiction to paper over — it is itself a direct, real illustration of this
document's central thesis: *even "was this grant ever cited" is representation-sensitive,* not a
single fixed fact waiting to be read off. Both numbers are reported; neither is asserted as final
without specifying its matching method.

## 7 · Evidence-ablation experiment

Reusing the real 379-commit population from `evidence-fusion-model.md` §11, extended with the
dimension ordering the corpus itself makes available (not an assumed S0–S8 staging):

| Stage | Evidence added | Output states (count) | H(output) | H/H_max |
|---|---|---|---|---|
| S1 | `CitesWorkItem` only (2-way: cited / not) | 159 cited / 220 not | 0.9812 bits | 98.1% (max 1 bit) |
| S4 | + `CitesGrant` + `TemporalMatch` + `ScopeConsistent` (4-way: `CONFIRMED`/`UNCITED`/`UNDERDETERMINED`/`CONTRADICTED`, counts 179/159/35/6) | — | 1.4489 bits | 72.4% (max 2 bits) |

No new stage was added this pass beyond what was already validated (SESSION/ROLE evidence is too
sparsely populated — 73.0%/27.4% — to support a reliable new ablation stage without first disclosing
that most transitions would fall into an `UNKNOWN`-evidence bucket rather than a resolved one; adding
it now would manufacture apparent precision the data doesn't support). This is stated as a limitation
in §10, not worked around.

## 8 · Information-theoretic results — with explicit ground-truth caveat

`H(S1 output)=0.9812` bits and `H(S4 output)=1.4489` bits, as computed above. **These measure the
entropy of the classifier's own output distribution — how concentrated or spread its verdicts are —
not accuracy, and not mutual information with ground truth.**

Computing `I(E;Y)` (mutual information between evidence and true correctness) or `H(Y|E)`
(conditional entropy of truth given evidence) requires labeled ground truth `Y` for a population of
cases. **This corpus provides exactly one independently-verified ground-truth label** (`9f83a369c`,
confirmed `CONTRADICTED`/violation by an independent file sweep). One label is mathematically
insufficient to estimate any distribution over `Y`, let alone a conditional or joint one — the
estimator would have zero degrees of freedom. **No mutual-information or conditional-entropy number
is reported here**, because none can be honestly computed from this corpus. This is a hard limit,
stated as such, not approximated around.

What *can* honestly be said: the drop from 98.1% to 72.4% of max entropy as more evidence dimensions
are added shows the classifier's output becomes *more concentrated* (less uniform) as evidence
accumulates — consistent with, but not proof of, evidence genuinely reducing indeterminacy. Whether
that concentration tracks *correctness* is exactly what one ground-truth label cannot establish.

## 9 · Minimal sufficient evidence candidates

Given §6–§8, no formally minimal sufficient set `E*` can be *proven* sufficient (that would require
the same ground-truth population §8 lacks). What can be stated, as a `HYPOTHESIS`:

- `{CitesWorkItem, TouchesArtifact}` resolves `CONFIRMED`/`UNCITED` for the large majority of the
  379-commit population (338/379, 89.2%) without needing `ProtectedArtifact` or `GrantActive` at
  all — those two additional predicates only matter for the 41 commits (10.8%) landing in
  `UNDERDETERMINED`/`CONTRADICTED`.
- `ProtectedArtifact` is the highest-leverage *additional* predicate precisely because it is also
  the *rarest* — derivable for only 1/22 work items — meaning most of the corpus cannot use this
  refinement at all. It is necessary for the one confirmed real violation case, not for the bulk of
  classification.
- `SessionPopulated`/`RoleAssigned` are **not** part of any currently-defensible minimal set: at
  27–73% population, including them as required evidence would reclassify large fractions of
  otherwise-resolvable cases as `UNKNOWN`-evidence, which is a cost with no demonstrated
  classification benefit in this corpus (no case so far has changed status when session/role was
  added — this is a `HYPOTHESIS`, not tested at scale, since no such ablation stage was run per §7).

## 10 · Ground-truth limitations (restated plainly, as instructed)

One labeled case. All entropy figures in §8 are output-distribution entropy, not accuracy. All
"minimal sufficient set" language in §9 is `HYPOTHESIS`-tagged, never `SUPPORTED`, because
sufficiency cannot be tested without a labeled population this corpus does not have. Any future
document that reports an accuracy percentage, a precision/recall figure, or a mutual-information
number for this evidence model should be treated as suspect unless it also discloses how it obtained
more than one ground-truth label.

## 11 · ML readiness assessment

Per the required discipline: report either "ML NOT JUSTIFIED YET" or "ML PROVIDES NO NECESSARY
ADDITIONAL VALUE" as a valid result, not a gap.

**Finding: ML NOT JUSTIFIED YET**, for reasons independent of and in addition to the one-label
problem in §10:

- **Feature representation candidates exist** (the predicates in §5 are already feature-shaped:
  citation match, temporal match, protection flag, session/role presence) — this is not the
  blocker.
- **Leakage risk is severe and specific**: `CitesWorkItem` and `WorkItemMatch` are derived from the
  same commit-message text that would also be the most obvious feature source — a model trained on
  commit-message features to predict "is this authorized" would likely just relearn the citation
  regex, not discover anything the deterministic predicate didn't already give directly.
- **Temporal leakage**: grant/transition dates and commit dates share the same governance process
  that produced both — a model would need strict train/test splits by *work item*, not by commit,
  to avoid learning "this work item's commits are usually fine" as a spurious shortcut; this
  corpus's 22 work items is too few to support such a split with any statistical power.
- **Class imbalance is extreme and, worse, largely unmeasured**: 1 confirmed positive (violation)
  case in the entire corpus. No classifier can be meaningfully trained, validated, or even sanity-
  checked against a single positive example.
- **Abstention** (the `UNDERDETERMINED` state) is already handled deterministically and well by the
  existing rule-based `Fuse` model — there is no demonstrated case where a learned model would add
  abstention capability the deterministic model lacks.

**Conclusion:** the deterministic predicate/implication approach in §5–§6 already achieves what this
corpus can support evidentially. ML would not "provide no value" in some abstract sense — it might,
with a much larger and independently labeled corpus — but nothing in the *current* evidence
justifies starting it, and starting it now would risk producing a model that looks rigorous while
actually only re-deriving the citation regex under leakage. This is reported as a genuine result,
not a deferral.

## 12 · Kernel implications

No re-minimization of the seven kernel candidates is warranted by this pass's findings — none of §4–
§11 above produced new evidence bearing on Observation, Rule/classification, Authorization/
commitment-gate, Canonical representation, Provenance, Uncertainty/indeterminacy, or Governed
lifecycle beyond what `EKS-PKS-Relationship.md` §4 already states. One point is worth flagging
explicitly rather than silently, because it touches Provenance directly:

**Provenance's status in `EKS-PKS-Relationship.md` §4** ("`Supported in EKS (144/144 grants,
strong)`") is accurate for *field presence* (144/144 `humanActRef` populated, confirmed again this
pass) but should be read through §4.3's distinction above: presence is `DIRECTLY_OBSERVABLE`;
content-verification is not. This does not change the candidate's status tag (still `Supported`,
since field-presence is itself real, disclosed evidence) — it sharpens what "supported" means. No
edit to that document is made here, since this is a clarification of an existing tag's scope, not a
falsification of it; flagged here for traceability, actionable only if a future pass needs it.

## 13 · Falsification results

- **Falsified this pass**: the implicit claim (never stated as cleanly as this, but present in the
  prior report's "1.2%... corpus-wide" framing) that a single representation's extractability rate
  characterizes "governance constraints" as a category. §4.4 falsifies this by showing three
  different numbers for three different dimension×representation pairs within the same corpus.
- **Confirmed, not falsified**: `Authorized ⇏ Produced` (I3), now with two independently-measured
  supporting figures (52.1% exact-match, 43.1% loose-match) rather than logical argument alone.
- **Confirmed, not falsified**: `Touches ∧ Protected ⇏ Unauthorized` (I4), same real counterexample
  as before, re-tested, unchanged.
- **New, not previously tested**: SESSION/ROLE field-population rates (73.0%/27.4%) — neither
  confirms nor falsifies any prior claim, since no prior document made a population-rate claim about
  these fields; recorded as new baseline data.

## 14 · Remaining unknowns

- Whether a larger, independently-labeled ground-truth population could be assembled at all in this
  repository's history (candidate source: manually re-auditing more of the 6-hit protected-artifact
  sweep, or the full 274-transition set, for further independently-verifiable cases) — not attempted
  this pass.
- Whether SESSION/ROLE sparsity (27–73%) is a recording-discipline gap that could be closed
  going forward (a process question, not a research one) versus an inherent limit of how these
  ledgers are populated.
- Whether the exact-vs-loose grant-citation match discrepancy (§6.1, 52.1% vs 43.1%) reflects a
  systematic pattern (e.g. informal citations cluster in particular work items) or is noise — not
  tested.
- `OQ-11`, `K5=(S,A,R)` — unchanged, out of scope, not addressed.

## 15 · Smallest next decisive experiment

Manually audit a bounded, small set of additional real cases (candidates: the 6-commit
Cohesion-directory population already fully classified in `evidence-fusion-model.md` §10, re-examined
specifically for whether each can be *independently* ground-truthed the way `9f83a369c` was — i.e.
verified by a mechanism other than `Fuse` itself) to grow the ground-truth label count from 1 toward
a number (even 4–5) that would make a first, honest conditional-entropy estimate possible — with the
estimate's own confidence interval reported, not just a point value, given how small the resulting
sample would still be. This is smaller in scope than assembling a general-purpose labeled dataset,
directly extends work already done, and would be the first test of whether this corpus can support
any information-theoretic claim about *correctness* at all, rather than only about output-
distribution shape.

---

**Traceability:** `2026-09-29-KOS-GOVERNANCE-CONSTRAINT-OBSERVABILITY.md` (§5's claim, corrected
here in §0/§4.4) · `2026-09-29-KOS-evidence-fusion-model.md` §§10–11 (S1/S4 ablation, reused) ·
`2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md` §4 (`ProtectedArtifact` derivability) ·
`2026-09-29-KOS-EKS-PKS-Relationship.md` §4 (kernel candidate table, §12 cross-reference) ·
`2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` (methodological discipline this document follows) ·
INV-ATTR-1/INV-ATTR-2 (self-declared identity is evidential, not attestable — load-bearing in §5–§6).
