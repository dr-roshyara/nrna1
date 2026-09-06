# 07 — Ablation Design (Parts VII, VIII, IX, XX, XXVI)

## Procedure

For each operator `o`:

1. **Disable** `o` — remove it from the set. Nothing else changes.
2. **Do NOT rewrite** the remaining operators to absorb `o`. (Enforced structurally: absorption is a
   change to another operator's atom set, which the engine cannot perform during a reach search.)
3. **Attempt reconstruction** — the reach fixpoint *is* an exhaustive search over all valid typed
   compositions of the remaining operators. There is no "reasonable attempts exhausted" judgment
   call: the search is complete over the declared rule set.
4. Run all 16 scenarios and 10 randomized properties.
5. Record the exact failed capability and its failure class.
6. Determine whether the failure is fundamental or a simulator artifact (F19/F20).

Then pairwise (all 91 pairs), then selected triples on the clusters the pair results implicate,
then **atom-level** leave-one-out (a level the protocol does not require but which the shared-atom
structure makes necessary).

## Classification vocabulary

`A` apparently irreducible · `B` derivable/composable · `C` redundant with another operator ·
`D` ambiguous semantics · `E` unsupported by current evidence · `F` unresolved.
Operators are **not** forced into A/B.

## Failure classes (Part XX)

F1 representation · F2 semantic interpretation · F3 relation · F4 discrimination ·
F5 hypothesis generation · F6 inference · F7 gap detection · F8 challenge · F9 validation ·
F10 revision · F11 determination · F12 action selection · F13 context · F14 temporal ·
F15 provenance · F16 alternative preservation · F17 non-identifiability ·
F18 governance/authorization confusion · **F19 implementation artifact** · **F20 simulator artifact**.

F19/F20 are never counted as kernel failures. §16 lists every result classified F19/F20.

## The semantic-smuggling test (Part VII)

The forbidden move — `Y := X-in-disguise` — is run **deliberately**, as a control:

```
smuggling_probe(base, removed, absorber):
    give `absorber` the atoms of `removed`, then re-evaluate
```

Every probe is reported with `SMUGGLING = TRUE` and `verdict = REJECTED`. A probe that restores full
coverage is *not* evidence of reducibility; it is evidence that the test is necessary. Results: §10.

## Baseline discipline

> *"The baseline must succeed before ablation results are interpreted."*

The `C0` baseline **does not succeed** (§10). The experiment therefore runs on a **repaired
baseline** `C0+ = C0 ∪ {Qualify}` and reports both arms, with the raw `C0` arm retained as
robustness variant **V7** so nothing is hidden.

## Reproducibility metadata (Part XXVI)

Recorded in every `results/*.json` under `_meta`:

```
experiment_id KR-2026-09-01 · model_version kr-model-1.0 · date 2026-09-01
python 3.x · seeds [1,7,13,101,2718] · trials_per_seed 2000
capability_model "C1..C24 + C25(added)" · operator_sets ["C0(13)","C0+(14)"]
```

Every arm is deterministic given the seed. The reach fixpoint itself is deterministic and
seed-independent; only the randomized property worlds consume the seed.

**Code:** `research/kernel-reduction/` — `kr/atoms.py`, `kr/carriers.py`, `kr/operators.py`,
`kr/reach.py`, `kr/capabilities.py`, `kr/scenarios.py`, `kr/properties.py`, `kr/variants.py`,
`kr/ablate.py`, `kr/infotheory.py`, `run_all.py`. Reproduce with `python3 run_all.py`.
