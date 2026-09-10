# MD-076 §05 — Evidence/Falsification Register

Per the mission's §9 — actively searched for evidence that would falsify the apparent computation.
All items below are drawn from `MD-073`/`074`'s own corpus-wide, reproducible checks (re-cited here,
not re-run, since re-running identical greps against an unchanged corpus would not produce new
evidence).

| Search target | Result | Source |
|---|---|---|
| Competing `Det_r` definitions | **None found.** Exactly one `Det_r(` occurrence in the entire corpus outside this reconstruction's own artifacts — the definitional statement itself | `MD-073`/`074`, corpus-wide grep |
| Competing `EvalReq` definitions | **None found.** Exactly one `EvalReq(` occurrence — the definitional statement | same |
| Contradictory domains/codomains | **`EvalReq` has no codomain to contradict** — it is the only function in the chain never given one. `Det_r`'s own codomain `𝕊_sat` is asserted but never defined as a set | `MD-074`, Stage B/C |
| Requirements whose standard cannot be evaluated | Not directly tested (no requirement's `Det_r` was ever attempted, so this question is moot for the whole corpus, not just one case) | `MD-073` |
| Examples where the result is explicitly computed | **None found**, for any requirement, anywhere | `MD-073`/`074` |
| Examples where `Sat` is evaluated | **None found via the typed chain.** Every `Sat` value in the corpus is a **stipulation**: `Sat(K,r_i)=Satisfied` (worked example, §21A.17); `Sat(K,r,EC_1)=Satisfied` (`[PVI]` 712); `Sat(K,r_{a_i})=Satisfied` (`[PVI]` 801); `Sat(K,r_conflict)=Satisfied` (`[PVI]` 2698) — each asserted, none derived | `MD-074`, Stage D |
| Counterexamples (to computability) | The `2-arg`/`3-arg` `Sat` mismatch itself functions as a structural counterexample to any claim that the typed chain is operationally connected | `MD-074`, Stage D "version mismatch" |
| Executable implementations | **None found** for this specific chain. (`KR-SIM-2026-09-02`, T8, operationalizes the earlier T5/T7 formulas — a different, simpler stage of the theory's own history, not the T21 `EvalReq`/`Det_r` apparatus) | `MD-069` T8; `MD-073`/`074` |
| Test cases | **None found** — no test harness, unit test, or worked numeric example exists for `EvalReq`/`Det_r` anywhere | `MD-073`/`074` |
| Failure cases | The theory's own worked example is itself the closest thing to a "failure case" — it sets up the full apparatus and then bypasses it (`MD-074`, "the `Eval/EvalReq/Det_r/Sat` chain is bypassed") |
| Explicit statements that a component remains undefined | **`Γ` — yes, explicitly absent, not even a schema** (`EKS-47`). `Det_r`'s own body is disclosed, at birth, as an intentionally externally-supplied per-contract parameter (`[05-40]`'s own text: *"the exact policy belongs to the epistemic contract"*) — the clearest first-party acknowledgment that this component was never meant to be corpus-supplied in the first place | `MD-068` GAP-001; `EKS-47` |

## Statistical discipline applied

Every "zero occurrences" claim above rests on a **reproducible corpus-wide grep**, run independently
twice (`MD-073`, then `MD-074` as a cross-check over the same case) — not a sampled or partial search.
The two runs are **same-lineage, not independent replication** in the statistical sense (both are
Claude-authored investigations of the identical question, run in immediate succession, one explicitly
verifying the other) — recorded honestly as **corroboration within one investigative lineage**, not as
two independent confirmations, per this reconstruction's own standing evidence-weighting discipline.
