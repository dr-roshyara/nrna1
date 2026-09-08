# B `Validate` — Exact Source-Stated Contract

## From `04-operator-contracts.md` (admitted)

| Field | Value | Source |
|---|---|---|
| Atom | `warrant-assessment` | Table, `04` |
| Non-reducible responsibility (claimed) | "assign warrant given evidence + assumptions" | Table, `04` |
| Corpus support | STRONG | Table, `04` |
| Input (general convention, not Validate-specific) | "carriers required by some derivation rule" | `04`'s own common-contract preamble |
| Output (general convention) | "the derived carrier" | same |
| State effects | "none, except `Revise`" | same — Validate is explicitly NOT a state-mutating operator |
| Information effects | "governed by the data processing inequality" | same |
| Preconditions / postconditions (Validate-specific) | **NOT SPECIFIED BY SOURCE** — `04` gives no per-operator precondition/postcondition text | — |
| Error/failure behavior | **NOT SPECIFIED BY SOURCE** | — |
| Identity assumptions | **NOT SPECIFIED BY SOURCE** | — |

## From `03-capability-model.md` (admitted) — the genuinely new information

Capability table, row **C10**: *"validate a claim/model"* → realized by carrier **`Verdict`**, kind
**artifact**. This is Validate's own **output type**, source-stated, not inferred.

Adjacent, relevant rows (context for the input side, **not** a stated input list for C10 specifically):
C7 *"perform inference"* → `Claim` (warrant kind DEDUCTIVE); C25 *"admit an observation as evidence
under a policy"* → `Evidence`.

## What this study concludes about Validate's own contract

**Output**: `Verdict` (artifact) — **source-stated**, from `03`'s own capability table.
**Input**: **NOT SPECIFIED BY SOURCE within the admitted scope** — the concrete derivation rule
mapping which carrier(s) satisfy C10's own precondition lives in `06-composition-rules.md`
("derivation table"), which is not admitted. Any input list this study might propose (e.g. `Claim` +
`Evidence`) would be a **mapping constructed for analysis**, reasoned from the capability names'
own semantics, not a source-stated fact — explicitly labeled as such wherever used in `04`.
**Preconditions/postconditions/failure behavior**: NOT SPECIFIED BY SOURCE, in either admitted file.
