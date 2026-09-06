# H — No-Smuggling Register (§23)

## SMUG-0 — Oracle independence: **CLEAN**

Static audit greps every agent-side module (`kos/transitions.py`, `kos/state.py`) for reads of
`World.truth`.

```
forbidden_reads : NONE
```

No agent transition can read latent truth. This is the load-bearing guarantee of the whole
experiment — and it is precisely what makes CE-1 a real result rather than an artifact.

## SMUG-1 — Scenario-level observation-token override `[NEG]` **instrumentation artifact**

The refined audit (assignments only, not reads) finds **4 sites**:

| Function | Statement |
|---|---|
| `scenarios.scenario_C` | `E.observations[oid]["token"] = …` |
| `scenarios.scenario_F` | `E.observations[oid]["token"] = …` |
| `scenarios.scenario_G` | `E.observations[o1]["token"] = …` |
| `scenarios.scenario_G` | `E.observations[o2]["token"] = …` |

**Finding.** Scenarios C, F and G *construct* the observation token after `Acquire` rather than
having the world produce it through a channel.

**Assessment.** Classified **instrumentation artifact, not capability smuggling**: the overridden
value is an *input* the scenario is entitled to specify (which conflicting report each source
gives), and the properties those scenarios test — `P8` dependence, `P13` underdetermination, conflict
preservation — concern the **downstream** pipeline, which is untouched. But it does mean **those
three scenarios do not exercise the acquisition channel**, and that must not be claimed of them.

**Fix for the next run:** dedicated channels with fixed tokens, so the world remains the sole source.

## SMUG-2 — Where each capability is introduced

A capability must be introduced by exactly one transition. Verified assignment:

| Semantic capability | Sole introducer |
|---|---|
| world-contact | `Acquire` |
| evidential admission | `Qualify` |
| meaning assignment | `Interpret` |
| hypothesis space | `OpenHypothesisSpace` |
| target-relative weight | `Assess` |
| set-valued selection | `Determine` |
| elimination | `Reject` |
| attribution | `knowledge_attribution` (Γ) |
| state commit | `Revise` / `Supersede` |

The **lexicon** and the **admission policy** are *declared inputs*, not capabilities: they are data
passed into `Interpret` and `Qualify` and recorded in provenance as assumptions. A different lexicon
changes the readings; it cannot create a capability no transition owns.

## SMUG-3 — What the audit could NOT check `[OPEN]`

* whether the **hypothesis space** handed to `OpenHypothesisSpace` smuggles the answer. In every
  scenario `H_Q` is the full domain, so the truth is always a member — but a narrower `H_Q` that
  excluded the truth would make `cannot-determine` the *correct* answer, and this experiment never
  tested that case.
* whether the **requirement kinds** (`determined / unique / corroborated / causal / provenanced`)
  are exhaustive. They were chosen by the experimenter.
