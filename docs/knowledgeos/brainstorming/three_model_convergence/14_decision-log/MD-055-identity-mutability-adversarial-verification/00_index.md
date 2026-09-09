# MD-055 — Independent Adversarial Verification of the `id`/Mutable-`e.state` Contradiction

## Trigger and correction carried forward from MD-054

The user reviewed MD-054, agreed with its central result, and made one correction, recorded here (not
by editing MD-054's frozen text): MD-054's characterization of the "ChatGPT" comparison stream
(`CLAUDE-CHATGPT-RECONCILIATION.md`) as *"a genuine, contamination-checked second independent research
stream"* overstates what fingerprint-checking alone establishes. **Corrected classification, binding
from this phase forward**: that material is **a separately attributed comparison/research stream whose
independence requires its own provenance audit** — fingerprint-checking rules out *this programme*
having produced it, but does not by itself establish who or what did, or that it is independent in the
stronger sense this reconstruction's own evidentiary ladder requires. Not chased further this phase;
named so the label is not reused uncorrected.

The user then authorized this phase directly, narrowing MD-054's own named "smallest next action" from
a broad live-code verification to a single, tightly bounded claim: **is the `id`/mutable-`e.state`
contradiction real, tested independently rather than trusted from the source programme's own
self-report?**

## What this phase does

A clean-room, independent computational re-derivation of exactly the claim MD-054 §02 recorded from
the source material's own second (adversarial re-verification) pass:

> `id = H(P, e, c, t, Π)` and `Evidence.state` (active/withdrawn/invalidated) being mutable cannot both
> hold — withdrawing one evidence item re-keys the assertion's own identity hash and leaves any
> existing `ℛ` edge referencing the old id dangling, violating `StructuralValid(K)`.

**Method**: implement only the definitions as stated in the admitted narrative material (already
narrow-scope admitted in MD-054 — no new admission needed), independently, without reading or reusing
any verifier scratchpad script (none was available — the source material's own "kaudit.py"/
"construct.py" are described as ephemeral session scratch work, not checked into the corpus). Run the
implementation now, for the first time by this reconstruction, and report the actual result — not the
source programme's self-reported result.

**Scope, deliberately narrow, per the user's own instruction**: this one claim only. Not a broad
live-code verification of the whole `K=(𝒜,ℛ)` theory. Not a test against `docs/knowledge/`'s own
real schema (confirmed in MD-054 §02 to implement none of `e`/`t`/`Π` at all — there is no live
implementation of this specific theory to test against; the verification is against the *stated
formulas themselves*, computed independently).

## Artifacts

- `00_index.md` — this file.
- `verify_id_mutability.py` — the independent re-derivation of the unrepaired formula; **executed**,
  output recorded in `01_findings.md`.
- `verify_tg06_repair.py` — an independent test of the source material's own proposed repair (TG-06:
  project the mutable `state` field out of the identity hash, keep only the evidence reference set);
  **executed**, output recorded in `01_findings.md`.
- `01_findings.md` — results, classification, and what this phase does and does not establish.
- `02_verification-and-completion.md` — verification suite, completion statement.

## Scope boundary

No modification of MD-024–054. No admission of new material (the claim under test is already
narrow-scope admitted). No adoption of the TG-06 repair into any candidate inventory — its soundness
for the one failure mode tested is reported, not promoted into a design decision. No resolution of
GA-001/GA-038. No composition test. No candidate label assigned or changed.
