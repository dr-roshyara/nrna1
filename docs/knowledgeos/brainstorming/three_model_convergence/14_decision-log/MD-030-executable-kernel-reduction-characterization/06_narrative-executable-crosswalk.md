# Narrative ↔ Executable Crosswalk

Relationship classes used (per authorization, narrowed to what evidence actually supports):
**EXPLICITLY LINKED** / **EXPLICITLY GENERATED** / **CORROBORATED** / **RECONSTRUCTED PROVENANCE** /
**POSSIBLE RELATIONSHIP** / **NO DEMONSTRATED RELATIONSHIP**.

| Narrative artifact (admitted) | Executable artifact | Relationship | Evidence |
|---|---|---|---|
| `04-operator-contracts.md` — `Validate` row, atom `warrant-assessment` | `kr/atoms.py:35` `A_WARRANT = "warrant-assessment"`; `kr/operators.py:33` `Op("Validate", {A_WARRANT}, ...)` | **CORROBORATED** (exact string match, both sides source-read) | `04`'s table (MD-029 `02`) vs. this study's `03`/`04` |
| `03-capability-model.md` — row `C10`, `"validate a claim/model"` → `Verdict` | `kr/capabilities.py:30` `cap("C10", "validate a claim/model", [VERDICT])`, plus its own docstring line 4: *"Justified modifications are recorded in docs .../03-capability-model.md"* | **EXPLICITLY LINKED** (the code names the admitted document as its own tracking target, not merely a resemblance) | this study's `03` |
| `04-operator-contracts.md`'s stated Input/Output convention ("carriers required by some derivation rule" / "the derived carrier") | `kr/carriers.py`'s `DERIVATION_RULES` table (general mechanism) | **CORROBORATED at the mechanism level** — the admitted document's own generic description ("some derivation rule") matches, structurally, exactly what `carriers.py` implements (a general derivation-rule table, not per-operator input lists) | `02` of MD-029 vs. this study's `03` |
| `06-composition-rules.md` (unadmitted, named by MD-029 as the likely location of Validate's concrete input rule) | `kr/carriers.py:65–66`, the two-input derivation rules for `Verdict` | **POSSIBLE RELATIONSHIP, NOT DEMONSTRATED** — this study did not read `06-composition-rules.md` (out of scope: this study stays inside `nrna1/research/kernel-reduction/` vs. the two *admitted* files only). Whether `06`'s content matches, differs from, or is silent on the code's own rule is **unknown** from this study alone. | scope boundary, `00` |
| MD-029's own constructed analytical inference (`02_validate-source-contract.md`, lines 32–34: *"Any input list this study might propose (e.g. `Claim` + `Evidence`)... a mapping constructed for analysis"*) | `kr/carriers.py:65`, `({CLAIM, EVIDENCE}, {A_WARRANT}, VERDICT)` | **CORROBORATED, WITH A CAVEAT ON INDEPENDENCE** — MD-029 proposed exactly this pair from the admitted capability-table semantics alone (C7→`Claim`, C25→`Evidence`), *before* this study read the executable code. The code encodes the identical pair as its baseline rule. This is a genuine, notable convergence — but both readings plausibly derive from the same "obvious" reading of the same two admitted capability rows, so it is **not** independent statistical confirmation; it is one plausible inference path corroborated by one implementation choice, both traceable to the same underlying capability semantics. | this study's `04`; MD-029 `02` |

## What the crosswalk does NOT show

No row in this table reaches **EXPLICITLY GENERATED** (i.e., no admitted narrative document states
"this code produced this text" or vice versa) — the strongest relationship found is
**EXPLICITLY LINKED** (the code's own docstring names the admitted document by filename as its
tracking target) and **CORROBORATED** (independently-arrived-at content matches). No contradiction
was found between the executable lane and the two admitted files anywhere this study looked.

## Direct answer to the authorization's central crosswalk question

Does the executable implementation "implement the documented contract, extend it, contradict it,
merely resemble it, or [is it] incomparable"? Answer: **it extends it, in a documented, self-
declared way, for the two admitted rows checked (atom, capability), and supplies additional,
concrete information (the two-input derivation rule; the C0-vs-C0+ reachability result) that goes
beyond what the two admitted documents state — without contradicting either of them anywhere
checked.** It does not "merely resemble" — the docstring-level naming (`variants.py:25`,
`capabilities.py:4`) is an explicit authorial claim of correspondence, not a coincidence of
vocabulary.
