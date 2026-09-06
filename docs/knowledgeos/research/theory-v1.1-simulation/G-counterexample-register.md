# G — Counterexample Register

Counterexamples are preserved, never repaired by adjusting the generator (§28).

---

## CE-1 — FACTIVITY VIOLATION `[NEG]` **theory failure**

| | |
|---|---|
| **ID** | CE-1 |
| **Scenario** | AD-1 "highly probable but false claim from a confident source" (+ 420 randomized instances) |
| **Input** | `World.truth = {os: RHEL8.6}`; a channel whose source the admission policy rates reliability 1.0 reports `RHEL9.8`; standard `S-lenient` |
| **Expected** | `Knows(a,p,c,t) → True(p,c,t)` (DEF-1) |
| **Actual** | `A={RHEL9.8}`, status `unique`, `standard_met=true`, `attributed=true`, **value `RHEL9.8` ≠ truth `RHEL8.6`** |
| **Root cause** | `Γ` is a total function of `(E,Q,C,EC)`; `E` contains no truth. Two worlds with different truths produce a **bit-identical** `E` (witness verified by fingerprint), hence an identical `K`. |
| **Impact** | `DEF-1` (factivity) and the v1.1 attribution equation are **jointly unsatisfiable** for any `Γ` that attributes anything. Policy sweep: only `attribution_policy = "none"` escapes. |
| **Classification** | **THEORY FAILURE** — not implementation, not oracle, not generator. Reproduced at 0.1237 conditional rate over 10 000 trials. |
| **Theory implication** | See H for the three available repairs. |

---

## CE-2 — UNSUPPORTED CONCLUSION DETECTED `[EXP]` **positive control**

| | |
|---|---|
| **ID** | CE-2 |
| **Scenario** | AD-18 "conclusion generated without supporting evidence" |
| **Input** | A determination injected directly with `A=[RHEL9.8]`, `weight=9.9`, `provenance=()` |
| **Expected** | The information-conservation audit flags it |
| **Actual** | `P10` FAIL — `"determination with no supporting assessment"` |
| **Root cause** | Deliberate injection by the test |
| **Classification** | **TEST ORACLE WORKS.** This is a *positive control*, not a theory failure: it demonstrates that P10 can fail, which is what makes its passes elsewhere meaningful. |
| **Theory implication** | None. But note that `Γ` **still attributed** the injected conclusion — attribution does not itself check provenance. `[OPEN]` should `Γ` require non-empty provenance? |

---

## CE-3 — REVISION WITHOUT RETRACTION → PERMANENT UNDERDETERMINATION `[NEG]` **theory gap**

| | |
|---|---|
| **ID** | CE-3 |
| **Scenario** | I (retrospective revision) |
| **Input** | `os=RHEL9.8` observed at `t0`; reality changes; `os=RHEL8.6` observed at `t1`; `Revise` |
| **Expected** | `K_2` reflects the new reality |
| **Actual** | `A = {RHEL9.8, RHEL8.6}`, status `underdetermined`, **nothing attributed at `t1`** — because the `t0` assessment is never retracted and still carries full weight |
| **Root cause** | `DEF-23` / `AX-4` give the theory `Revise`, but **no operation retires superseded evidence**. The corpus carries seven revision verbs — `Update, Revise, Supersede, Correct, Invalidate, Retract, Expire` — and the theory models only one. |
| **Impact** | Any world that changes over time drives the agent monotonically toward underdetermination. `P19` still passes (`K_1 ≠ K_2`, identity stable), so a leave-it-there reading would have missed this entirely. |
| **Classification** | **THEORY GAP** (G2 semantic / G3 epistemological). Not an implementation bug: the implementation does exactly what the theory specifies. |
| **Theory implication** | The theory needs a temporal-relevance or evidence-expiry relation. This is the same `Q-4` the previous lane left open — now with a concrete failing case attached. |
