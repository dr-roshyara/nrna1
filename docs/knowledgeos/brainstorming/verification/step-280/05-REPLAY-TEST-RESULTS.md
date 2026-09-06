# 05 — Replay Test Results

| Property | Observed | Verdict |
|---|---|---|
| Deterministic replay | `Replay(H) == Replay(H)` | **PASS** |
| Replay = fold(T,∅,H) | `\|𝒜\|=2` as constructed | **PASS** |
| Historical policy selection | `PolicyAt(2026-03)=v1`, `PolicyAt(2026-08)=v2` | **PASS** |
| Replay uses historical, not current, policy | old verdict computed under **v1** | **PASS** |
| Policy version preservation | duplicate PID rejected; store 1→1 | **PASS** |
| Authority history preservation | `t0=Permit`, `t1=Deny` after revocation | **PASS** |
| Provenance survives replay at t=0 | `Π=vendor-sbom` with empty history | **PASS** |
| Real-system replay | **NOT OBSERVABLE** — git holds history; the platform does not model replay | **L1** |

> **Replay is the strongest computational result in Step 280 and has no real-environment counterpart.**
