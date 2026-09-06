# 04 — Falsification Test Results (F1–F13, +F4b)

| ID | Expected | Observed | Verdict | Lvl |
|---|---|---|---|---:|
| F1 | `Unknown(NoApplicablePolicy)` | `UNKNOWN(NoApplicablePolicy)` | **PASS** | 4 |
| F2 | `Deny(NoAuthority)` | `DENY(NoAuthority)` | **PASS** | 4 |
| F3 | `CONFLICT`, no silent selection | `CONFLICT(incompatible)` | **PASS** | 4 |
| F4 | `Deny` absent precedence | `DENY(AuthorityConflict)` | **PASS** | 4 |
| F4b | resolved with precedence | `PASS(PrecedenceResolved:A1)` | **PASS** | 4 |
| F5 | `Unknown`, no implicit default | `UNKNOWN(NoApplicablePolicy)` | **PASS** | 4 |
| F6 | not applicable at `t ≥ t_e` | `Applicable(t_e)=False` | **PASS** | 4 |
| F7 | historical reconstructable; current denies | `t0=PASS(Permit:AR); t1=DENY(NoAuthority)` | **PASS** | 4 |
| F8 | deny; active policy unchanged | mutation raised `FrozenInstanceError`; rules still `['r1']` | **PASS** | 4 |
| F9 | duplicate version rejected; **stored state** unchanged | `error='duplicate version identity'`; stored 1→1 | **PASS** | 4 |
| F10 | `PolicyAt(t_old)=v1` | `2026-03→v1; 2026-08→v2` | **PASS** | 4 |
| F11 | CONFLICT or safe failure | `classification=CONFLICT; PolicyAt=CONFLICT` | **PASS** | 4 |
| F12 | `∃τ_c: ∀i L_i=0` | `τ_c=5` | **PASS** | **3** |
| F13 | r2 unchanged | `r2(x=1)=PASS; r2(x=999)=PASS` | **PASS** | 4 |

**14/14 PASS.** Level ≥4: **13/14** (F12 simulated).

> **F9 was tested against STORED state, not the returned status, as the specification demands.**
> **F8 could not even be attempted by mutation** — the policy object is frozen, so unauthorized mutation
> fails at the type level rather than the governance level. **Recorded as a stronger-than-required
> result, and as a caveat: a frozen dataclass is not evidence that a real system would refuse.**

## The load-bearing statistic
**FP = 0.** Across every negative control — no authority, revoked authority, expired policy, missing
policy, policy conflict, authority conflict, unauthorized mutation — **not one was wrongly Permitted.**
