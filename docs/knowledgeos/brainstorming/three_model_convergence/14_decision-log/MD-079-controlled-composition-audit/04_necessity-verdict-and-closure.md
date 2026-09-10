# MD-079 §04 — Necessity Verdict, Decision Gate, Verification, Closure

## Step 10 — is `Det_r`/`EvalReq` genuinely necessary?

**Conditionally yes, precisely scoped.** Two distinct questions, kept separate per this reconstruction's
own standing discipline:

1. **Is *some* computation of `Sat` necessary to make the theory operational at all?** No — the corpus
   already contains a fully alternative route that never needs `Sat` computed: treat `Sat(K,r)`'s value
   as an externally-supplied fact (exactly as it has been used, unbroken, from its own birth at
   `step-023` through `T22`'s own worked example), and rely on the already-complete, twice-proven
   aggregation layer (`Det_{PartIII}`/`Det_{Part21}`, `Δ`/`Zero`) to do everything downstream. This route
   requires no construction at all — it is already fully corpus-native and stable (Classification A).

2. **Is `Det_r`/`EvalReq` *specifically* necessary if the goal is to make `Sat` itself computable, using
   `T21`'s own chosen route (`Eval→EvalReq→Det_r` composition) rather than a stipulated or
   externally-supplied value?** Yes — §02/§03 show no existing corpus-native or executable-research
   material closes `T21`'s own two failure points (`EC_B.Rules`'s own content; `Det_r`'s own body)
   without an invented mapping, and no alternative construction offers a demonstrated substitute for
   *that specific route*.

**The decision this reconstruction actually faces is therefore not "is `Det_r` necessary" in the
abstract, but "does this project want `Sat` to be *computed* (requiring new construction, since no
demonstrated corpus-native or executable route exists) or is it content for `Sat` to remain a
*stipulated input*, as the theory itself has used it, unbroken, since its own first appearance?"** That
is a governance/scope decision, not a further research question — consistent with `EKS-48`'s own
already-standing framing, now sharpened by this audit into a precise, binary choice rather than an
open-ended "shall we invent semantics" question.

## Decision gate

- **Composition succeeded** for the aggregation layer (`Req`/`Det_{PartIII}`/`Δ`/`Zero`, given `Sat`
  values) — already known (MD-078 Classification A), reconfirmed here by direct attempt.
- **Composition failed** for the base-case `Sat(K,r,Γ)` computation itself, at two precisely-located
  points (`EC_B.Rules`'s own content; `Det_r`'s own body), neither closeable without an invented mapping.
- **No demonstrated mapping** exists from either executable alternative (`kos/inquiry.py`, `Sat_c`/
  `Eval_c`) to `T21`'s own objects — both remain `RELATED CONSTRUCTION` only.
- **`Det_r`/`EvalReq` construction is therefore not yet authorized** by this audit — the audit's own
  purpose (per the mission) was to determine whether construction is *necessary*, not to perform it.
  The answer is: necessary *if and only if* `T21`'s own specific computed-`Sat` route is the chosen
  path — a choice this phase does not make.

## What this phase adds to MD-078, precisely

MD-078 established *what exists* across the corpus. This phase establishes *that it does not compose*
into `T21`'s own equation without invention, and *exactly where* it fails — a materially stronger and
more falsifiable result than "the pieces exist elsewhere." It also surfaces one new, disclosable
finding not present in MD-078: **`Det_r` is very likely an `UNRELATED_HOMONYM` to the much more stable,
twice-proven `Det(K,p,EC,Γ)` family (Part III/21)** — sharing a name-root but a genuinely different
argument structure and role (a per-requirement policy function vs. a universally-quantified proposition-
level predicate). This distinction was implicit in MD-076's own prior work but not stated this
explicitly; recorded here forward.

## Verification

- No new source file read this phase — pure adjudication over MD-078's own already-verified evidence,
  confirmed by re-checking this file's own citations against MD-078 §01 before writing.
- No mapping invented anywhere in this document — every composition-failure point is stated as a
  failure, not patched.
- No frozen artifact (MD-024–078) modified.
- `resume.py`/`resume_mathematical.py`: run below, both must report `CONSISTENT`.
- No backlog ticket filed — this phase's own findings sharpen `EKS-48`'s existing framing rather than
  surfacing a new, untracked problem.

## MD-079 status: EXECUTED. HARD STOP.

No construction performed. No mapping invented. No canonicalization. `SAT-OPERATIONAL-CLOSURE-v1`
remains unauthorized — this audit sharpens, rather than resolves, the decision `EKS-48` names: the
choice is now precisely between (a) accepting `Sat` as a permanently stipulated input, requiring no
further construction, and (b) authorizing new construction specifically to close the two named failure
points, with no existing corpus-native or executable material available to shortcut that construction.
Next action, named, not authorized: a human governance decision between (a) and (b) — not a further
research phase, since this audit has exhausted what composition-testing alone can determine.
