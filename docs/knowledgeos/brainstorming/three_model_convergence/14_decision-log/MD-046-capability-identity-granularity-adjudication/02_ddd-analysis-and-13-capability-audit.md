# MD-046 §2 — DDD Analysis (Phase C) and 13-Capability Boundary Audit (Phase F)

## Phase C — does the corpus provide a domain meaning for "capability"?

| DDD dimension | Corpus-grounded? | Evidence |
|---|---|---|
| Identity | **NO** — this is precisely the missing piece | Three unreconciled positions on Identity generally exist (`S1-F037`: primitive-indefinable / self-so / administrative), none specific to capability identity |
| Responsibility | **Partially** — a shape exists, uninstantiated | MinKer chain's own `ρ:𝒞_KOS→ℬ` responsibility-projection map (file 8, MD-045) — SOURCE-GROUNDED SHAPE, not filled in |
| Inputs/Outputs | **Proposed, not adopted** | MinKer chain's own candidate `c=(I_c,O_c,Γ_c,ℐ_c)` (file 9, MD-045), explicitly held back from adoption |
| Invariant | **Proposed, not adopted** | Same source, `ℐ_c` component |
| Lifecycle | **NO** — not addressed for capabilities specifically anywhere found | Lifecycle discussion in the corpus concerns epistemic states (`K_t`), not capabilities as such |
| Contract | **Circular given current state** | Capabilities are defined relative to `𝔠_KOS`, itself unspecified (MD-044/045) |
| Observable behaviour | **Shape proposed, uninstantiated** | `Beh_𝔠(K)` (MD-045's own dependency table) |
| Realization boundary | **Shape proposed, uninstantiated** | `K⊨c` (capability realization) |
| Ownership/bounded-context | **Addressed conceptually** | `ρ` mapping plus the "no capability laundering" conservation principle (MD-045) |

**Distinguishing capability / operation / responsibility / function / command / policy /
implementation mechanism**: the corpus does not maintain this distinction consistently across its two
separate vocabularies. The MinKer chain's own 13 names mix what look like DDD-flavored responsibility
statements (`Validate`, `Challenge`, `Revise`) with what look like mechanism-level operations
(`Observe`, `Represent`). `reviews/kernel/`'s own 9-item "existing law" map (`S1-F008`) is explicitly
framed as gate/enforcement/assignment/recording functions — closer to command/policy language than to
capability language. **Neither vocabulary states, for any of its own items, which DDD category
(capability vs. operation vs. responsibility vs. command) it intends** — this is itself part of the
unresolved granularity problem, not incidental.

## Phase F — 13-capability boundary audit

| Capability | Identity evidence | Boundary evidence | Atomicity evidence | Decomposition risk | Status |
|---|---|---|---|---|---|
| Observe | None found outside the MinKer chain's own use | None | None | Unassessed — no cross-reference exists | **NO CORPUS-GROUNDED BOUNDARY, ANYWHERE OUTSIDE THE CHAIN ITSELF** |
| Interpret | None found | None | Used in file 8's own worked example as a candidate that could "absorb" `Determine`'s function — a risk example, not evidence of actual boundary | Explicitly used as the chain's own illustration of decomposition risk | Same as above |
| Represent | None found outside the chain | Chain's own discussion (representation ≠ Kernel primitive) addresses representation generally, not this specific capability's own boundary | None | Unassessed | Same |
| Relate | None found | None | None | Unassessed | Same |
| Discriminate | None found | None | None | Unassessed | Same |
| Qualify | None found outside the chain's own toy execution | The toy 3-capability Python test (MD-044) exercises `Qualify` concretely — the only capability with any executed evidence at all | Tested irreducible only within a 3-capability toy universe, not the real 13 | Real-universe status unassessed | Same |
| Hypothesize | None found | None | None | Unassessed | Same |
| DetectGap | None found outside the chain | Chain's own finding: derivable across "eight tested variants" (MD-044) | Same toy/experimental caveat as `Qualify` | **Explicitly flagged by the chain's own later self-critique as granularity-dependent** (MD-045 §3) | Same |
| Challenge | None found | None | None | Unassessed | Same |
| Validate | None found | None | None | Unassessed | Same |
| Revise | None found | None | None | Unassessed | Same |
| Determine | None found outside the chain | Used as the chain's own illustration of the granularity risk (could be absorbed by a redefined `Interpret`) | None | Explicitly named as the chain's own worked risk example | Same |
| Select | Split into `Select_epi`/`Select_act` within the chain itself (MD-044) — the chain's own clearest internal boundary work | Only capability with an internal DDD-style split | None | Lower risk than most, but still chain-internal only, no cross-check | Best-evidenced of the 13, still **NOT CORPUS-CROSS-CHECKED** |

**Conclusion of the audit**: not one of the 13 names has boundary or atomicity evidence from outside
the MinKer chain's own self-contained discussion. The two names the chain itself uses as its own
illustrative risk examples (`Interpret`/`Determine`) are exactly the ones with the least independent
grounding. **The 13-capability universe is not currently sufficiently specified to serve as a formal
universe** — this is a factual census result, not a decision about which decomposition is correct.
