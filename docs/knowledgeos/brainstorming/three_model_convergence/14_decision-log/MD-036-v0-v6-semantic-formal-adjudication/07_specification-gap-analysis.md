# Specification-Gap Analysis

MD-033's own named gaps for the baseline (`V0`) `Validate` contract, checked against everything
newly admitted (`12`) and reconstructed in this study.

| MD-033 gap | Closed by V6/12? | Reasoning |
|---|---|---|
| Preconditions (for `V0`) | **No** | `12` adds no precondition to `V0`'s own rule; every gap-free field in `02` remains exactly as MD-033 left it |
| Postconditions (for `V0`) | **No** | same |
| Failure/error semantics (for `V0`) | **No** | `12`'s own extensive "failure" language concerns *experimental property failure* (P1–P10 under ablation), not `Validate`'s own operational failure semantics — re-confirmed here, not merely inherited from MD-034 |
| Determinism (for `V0`) | **No** | not addressed |

## Is "surviving defeater required" a precondition, a derivation step, an invariant, or something
else — the specifically-required classification

**A derivation-step input requirement, in the same grammatical category as every other entry in
`06`'s own derivation table — not a separately-stated precondition predicate, and not a formally
declared invariant.** The source expresses it exactly the way it expresses every other rule in the
table: `(required inputs, required atom) → produced kind`. It is *precondition-shaped* in the
ordinary-language sense (something that must hold before `Verdict` can be produced), but the source
does not use, or distinguish, a separate "precondition" category anywhere in its own vocabulary —
`04`'s own "Common to all" contract names only Input/Output/State effects/Information effects/
Dependencies as categories, with no separate "precondition" or "invariant" slot. Classifying it as
a formal Hoare-logic-style precondition would import a category the source itself does not use;
classifying it merely as "one more required input" is the more source-faithful reading.

## Net effect on MD-033's own verdict (B — partially sufficient, material gaps remain)

**Unchanged for `V0` itself.** MD-033's verdict concerned the baseline rule specifically, and no
field of that rule's own specification gap is closed by anything found in `12`. What has changed is
the *broader* picture (V6 is now a second, admissible, source-grounded candidate with its own
partially-specified contract, per `03`) — not a closure of `V0`'s own remaining gaps.

## Formal answer, restated per the authorization's own required framing

**Baseline `Validate` specification gaps: UNCHANGED.** Not closed, not further partially closed —
exactly as MD-033 left them, confirmed by direct re-examination in this study rather than assumed.
