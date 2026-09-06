# 05 — E4 Re-Run Results
**`exec/test_e4_rerun.py` → `OUT-E4-RERUN.txt` / `OUT-E4-RERUN.json`**

| Test | Setup | Expected | Observed | Verdict |
|---|---|---|---|---|
| E4-R1 | no query | `I=NotAsked` | `('NotAsked','-')` | **PASS** |
| E4-R2 | query, no assertion | `I=Asked, E=Absent` | `('Asked','Absent')` | **PASS** |
| E4-R3 | query, insufficient evidence | `E=Unknown` | `('Asked','Unknown')` | **PASS** |
| E4-R4 | query, supporting | `E=Supported` | `('Asked','Supported')` | **PASS** |
| E4-R5 | query, refuting | `E=Refuted` | `('Asked','Refuted')` | **PASS** |
| E4-R6 | query, conflicting | `E=Conflicted` | `('Asked','Conflicted')` | **PASS** |
| E4-R7 | orphan document | `Orphan=True`, `E(p)` unchanged | `orphan True→False`; `E` unchanged | **PASS** |

**7/7 PASS.**

## Evidence level — stated plainly
**Level 4** (controlled empirical test against the reference implementation).
**NOT Level 5.** The real EKP has **no inquiry register**, so `Ask(p)` cannot be observed there.
> **The repair is verified computationally and is untested against the real system.** Claiming Level 5
> here would repeat exactly the error Step 280 was commissioned to eliminate.
