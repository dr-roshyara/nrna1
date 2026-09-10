# MD-081 §01 — `EC.Rules` Successor Search and Responsibility Disposition

Ten candidate terms searched across all seven designated lanes (`threshold`, `qualification`,
`admissibility`, `evidence standard`, `decision rule`, `evaluation rule`, `contract rule`,
`epistemic rule`, `criterion`, `policy`). Full hit-count table and quoted evidence in the search
agent's own report (reused verbatim below where load-bearing); genuine formal-object hits — not
incidental prose — are what follows.

## The single most important new finding: `Policy_Det`, same file as `Det_r`/`EvalReq`

**T21 Part VI §6.43 "Determination Policies"** (`theory-part-06-evidence-evaluation-determination-
calculus.md`, the exact same file that introduces `Det_r`/`EvalReq`, immediately preceding §6.44
"Thresholds"):

```
A contract may define a policy:  Policy_Det.

For example:

Policy_Det(p) =
  Established  if sufficient independent support exists
  Rejected     if sufficient challenge exists
  Conflicted   if unresolved conflict remains
  Unknown      otherwise.

The exact policy belongs to the epistemic contract.
This prevents KnowledgeOS from encoding one universal philosophy of evidence.
```

**This is the closest candidate found anywhere in this investigation for `EC.Rules`'s own missing
content** — same document, same Part, explicitly framed as "the exact policy belongs to the epistemic
contract" (i.e., `EC.Rules`'s own job, in the source's own words). But it does **not** close the gap:
it is introduced with "for example" (a template, not a commitment), and its own case conditions
("sufficient independent support," "sufficient challenge") are exactly the kind of undefined threshold
§6.44 (the very next section, also verified this phase, already partially known from MD-079) explicitly
disowns: **"A threshold without semantics is not a mathematical epistemic rule... it becomes meaningful
only through a declared calibration or decision framework"** — a framework the source discloses, in the
same breath, that it does not supply.

**Object identity**: `RELATED OBJECT` to `EC.Rules` — same document, thematically adjacent, explicitly
self-described as belonging to the same slot. **Responsibility relation**: `SAME RESPONSIBILITY,
ILLUSTRATED BUT NOT DISCHARGED` — a new, precise category this phase adds: the source shows the *shape*
the responsibility would take without actually completing it, which is stronger evidence than a bare
gap but weaker than a genuine successor.

## Other genuine formal-object candidates found, all independently blocked

- **`χ_EC:𝕊_sat→{0,1}`** (Part V §5.7, already known from MD-078, re-confirmed by this search as "the
  only place in the entire search where a 'contract'-subscripted object gets both a type signature AND
  a worked instantiation") — but it **projects an already-known `𝕊_sat` value**, it does not determine
  one from evidence. `RELATED RESPONSIBILITY`, `SUBDIVIDED` (handles only the Boolean-collapse half of
  the job).
- **`Admissible = Pre∧Inv∧Assurance∧Auth`** (`verification/spec/STEP-VERIFY-041-055.md` §42.9/§42.41) —
  a genuine formal definition, but the verification lane's own prior adversarial work
  (`gap-discovery/gap-update-2026-09-02/05-NEW-GAPS.md`, NG-1) already found it **"undecidable"**:
  "`Admissible(o,K)`... depends on `Assurance`, which the corpus has REFUTED as definable... the
  invariant schema's antecedent cannot be decided. Every candidate inherits this. Class: BLOCKED —
  REQUIRES DERIVATION." A genuine, real object, independently confirmed non-computable by the corpus's
  own governed audit work — the strongest possible negative evidence, since this is not this
  reconstruction's own finding but a reused, already-adversarially-tested one.
- **The "qualification rule"** (`verification/gap-discovery/08-EVIDENCE-GAP.md`, EG-2) — "well-formed
  and non-computable... its load-bearing predicate — relevance under a rule — is in the class the
  corpus itself marks as having no decision procedure." Same pattern: real object, independently
  confirmed blocked.
- **"The evaluation rule `R`"** (`Evidence(O,P,C,R)`, Closure-04, 2026-08-27, cited across ≥6
  verification-lane documents) — named consistently, given a role, **never given internal content
  anywhere in any of the citing documents**.
- **`Threshold(d)`** (`verification/spec/STEP-VERIFY-041-055.md`) — explicitly catalogued by the
  verification lane's own audit as one of several "NOT_DEFINED" components feeding an undecidable
  `Assurance`/`Admissible` chain.

## A genuinely complete, different-responsibility positive finding: the verification lane's own `Policy`/`Apply`

`verification/POLICY-TYPE-RECONSTRUCTION.md` and ≥12 corroborating verification-lane documents
(`THEORY-OBJECT-DEPENDENCY-GRAPH.md`, `CANONICAL-KNOWLEDGEOS-THEORY.md`, etc.) converge on:

```
Policy = (id, version, Gates, ValidityInterval, ResolutionBehavior)
  Gates : SET of three-valued predicates  g : Decision → {True, False, Unknown}
Apply(p,d) = False                       if ∃g∈p.Gates: g(d)=False
           = p.ResolutionBehavior(Unknown) if ∃g: g(d)=Unknown
           = True                        otherwise
```

— tagged, in the source's own words, **"ACCEPTED — corpus step 57.47, all three components executed"**
and cross-referenced against a real script (`ladder_dc_reference.py`). This is a genuine, complete,
*executed* decision procedure — the strongest single piece of evidence found anywhere in this whole
`EC.Rules` search. **Object identity**: `RELATED OBJECT` to `EC.Rules` at best — this `Policy` governs
whether an already-proposed *action/decision* is authorized (gates over a `Decision`, feeding
`Authorize: Decision_Proposed×AuthorizationContext→Decision_Authorized`), a **different bounded
context** (Part III's own §3.58 candidate "Decision Context: Policy, Authority, Decision, Action")
than `EC.Rules`'s own job (whether a *knowledge state satisfies a requirement*, Part III's own
"Determination Context: Requirement, Contract, Satisfaction, Determination"). **Responsibility
relation**: `RELATED RESPONSIBILITY` — a genuine, working policy-gate mechanism exists in the corpus,
just for a neighboring, not the same, question.

## `EC.Rules`'s Responsibility Disposition entry (final)

| Responsibility | Origin | Historical carriers | Successor | Status | Evidence |
|---|---|---|---|---|---|
| contract-level acceptance/policy rule (`EC.Rules`/`standard`) | `[00-51]`'s own `standard` field | `Policy_Det` (T21 Part VI §6.43, same document, illustrative only); `χ_EC` (Part V, projects but doesn't compute); `Admissible`/`Threshold`/"the evaluation rule `R`"/the "qualification rule" (all real objects, all independently confirmed blocked/non-computable by the corpus's own prior adversarial audits); the verification lane's own executed `Policy`/`Apply` (complete, but a different bounded-context responsibility — action-authorization, not requirement-satisfaction) | none demonstrated | **GENUINE GAP, with the richest and most precisely bounded negative evidence found in this entire investigation** — the responsibility is not merely unattempted, it is *repeatedly and independently attempted*, by multiple threads across multiple lanes, and *each attempt is independently confirmed blocked or scoped to a neighboring question* | this phase §01, MD-080 §02 |

This upgrades MD-080's own `D — genuine corpus gap` verdict for `EC.Rules`/`standard`/
`AcceptanceCondition` from "nothing found" to **"multiple genuine attempts found, each independently and
adversarially confirmed non-computable or scoped to a different responsibility"** — a materially
stronger and more precise `D` than before, not a change of classification.
