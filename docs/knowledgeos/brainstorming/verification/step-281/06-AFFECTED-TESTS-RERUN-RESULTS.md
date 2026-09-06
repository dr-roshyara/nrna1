# 06 — Affected Falsification Tests Re-Run
**`exec/test_affected_falsification.py` → `OUT-AFFECTED-TESTS.txt`**

| Test | Why affected | Observed | Verdict |
|---|---|---|---|
| **F1** | policy-free must not read as absence-of-inquiry | `UNKNOWN(NoApplicablePolicy)`; inquiry `('Asked','Absent')` | **PASS** |
| **F3** | conflict must not collapse into unknown | `CONFLICT(incompatible)` — and `≠ UNKNOWN` | **PASS** |
| **F5** | missing policy must stay distinct from no inquiry | `missing=UNKNOWN` vs `never-asked=('NotAsked','-')` — **distinct** | **PASS** |
| **F6** | expired must not read as nonexistent | `Applicable(t>t_e)=False` **but still in history** | **PASS** |
| **F10** | replay must preserve historical state | `old=v1; new=v2` | **PASS** |

**5/5 PASS.**

> **F1 and F5 are the two that genuinely exercise the repair.** *"No applicable policy"* (asked, absent) is
> now distinguishable from *"never enquired"* (not asked). **Before the repair these produced the same
> observation** — which is why the Step 280 defect reached into the policy layer at all.
