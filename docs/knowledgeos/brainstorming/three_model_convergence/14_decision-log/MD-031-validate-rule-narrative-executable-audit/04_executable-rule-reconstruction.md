# Executable Rule Reconstruction — `Validate_E`

Restated from MD-030's own already-completed characterization (`03`/`04` of that study) — **not
re-read from scratch**, per this study's own frozen-artifact discipline for prior MD-0xx work. No new
code inspection was performed for this section.

## The rule, as MD-030 found it in `kr/carriers.py` (lines 65–66)

```
({CLAIM, EVIDENCE},      {A_WARRANT}, VERDICT)
({HYPOTHESIS, EVIDENCE}, {A_WARRANT}, VERDICT)
```

Machine representation: two tuples in a Python list `DERIVATION_RULES`, each `(required-input-kind-
set, required-introduced-atom-set, produced-kind)`, consumed generically by `reach.py`'s fixpoint
engine via `derive_kinds(available_kinds, step_atoms)`.

Explicit comments/docstrings (MD-030 `03`): the module's own docstring calls this "the DERIVATION
RULES" and frames it as a "second anti-circularity device" — a carrier's kind is derived structurally,
never stamped by the producing operator's identity.

## Runtime behavior already characterized by MD-030

`baseline.json` (MD-030 `05`): under the 13-operator baseline (`C0`, no `Qualify`), capability `C10`
("validate a claim/model," requiring `Verdict` reachable) is **lost** (absent from `Reach(C0)`)
because `Evidence` itself requires `Qualify`'s atom, and `Qualify` is not in `C0`. Under `C0_plus`
(`C0` + `Qualify`), `C10` is achieved. `minimal_kernels.json`: both minimal covering operator sets
found include `Validate` and `Qualify` together.

## Alternative variant already discovered by MD-030

`variants.py`'s `V6` (MD-030 `03`): an alternative derivation rule for the same step — `{Claim,
Evidence, Defeater}` or `{Hypothesis, Evidence, Defeater}` → `Verdict` — tested as a robustness
variant, described "a Verdict REQUIRES a surviving-defeater step (validation presupposes challenge)."

## Unresolved implementation ambiguity, as already recorded

MD-030 (`08`) already found: the two-input rule is the code's own declared **baseline** (`V0`,
explicitly labeled `"baseline model as declared in 04-operator-contracts"`), not the only rule the
same codebase tests. No new ambiguity is introduced by this study; none is resolved by it either —
this restatement exists only so `05`'s comparison table has both sides stated side by side.

## Explicit note per this study's own §6 instruction

This section does not infer that machine encoding equals mathematical correctness, and does not
infer that an implementation choice is a normative requirement — both cautions already governed
MD-030's own findings and are carried forward unchanged here, not re-derived.
