# 03 — End-to-End Test Results (E1–E24)

| ID | Domain | Verdict | Lvl | Observed |
|---|---|---|---:|---|
| E1 | K | **PASS** | 5 | `\|𝒜\|=6; SemanticallyValid=(True,'ok')` |
| E2 | Identity | **PASS** | 4 | `equal=True; reflexive=True` |
| E3 | Σ | **PASS** | 4 | 3×Supporting then 3×Refuting, as predicted |
| **E4** | **Missingness** | **FAIL** | 4 | `not-asked = INDISTINGUISHABLE from absent`; orphan has no K representation |
| E5 | Evidence | **PASS** | 4 | `api→Evidence; blog→None(UnqualifiedSource)` |
| E6 | Evidence | **PASS** | 4 | `Σ=(Contested,Weak); conflictsWith=True; \|e\|=2` |
| E7 | T | **PASS** | 4 | `good='ok'; bad='REJECTED(structure): V not in V_D'` |
| E8 | History | **PASS** | 4 | `Replay(H)==Replay(H)` |
| E9 | Provenance | **PASS** | 4 | `Π=vendor-sbom` recovered with empty history |
| E10 | Lineage | **PASS** | 4 | `Lineage(b2)={c81184e084}` |
| E11 | History | **PASS** | 4 | `states equal=True; histories equal=False` |
| E12 | Policy | **PASS** | 4 | `allPass=PASS; withDeny=DENY` |
| E13 | Policy | **PASS** | 4 | `missing=['y','z']; strategy=BLOCK` |
| E14 | Authority | **PASS** | 4 | `Permit:A1 / DENY(NoAuthority) / DENY(ScopeOrJurisdictionExceeded)` |
| E15 | Governance | **PASS** | 4 | `\|history\|=2; old PID retained` |
| E16 | Temporal | **PASS** | 4 | `t_s=True; t_e−ε=True; t_e=False` |
| E17 | Governance | **PASS** | **3** | `τ=5` — **SIMULATED, no real multi-node system** |
| E18 | Rules | **PASS** | 4 | r2 unchanged when r1's exclusive input changes |
| E19 | Measurement | **PASS** | **2** | admissibility **declared** in the type; no executor |
| **E20** | **Statistics** | **BLOCKED** | **0** | no `(Ω,ℱ,P)` anywhere; `str` is ORDINAL |
| E21 | Contradiction | **PASS** | 4 | `contradicts=True; \|𝒜\|=2` — both retained |
| E22 | Supersession | **PASS** | 4 | old retained; cycle `REJECTED`; contradiction explained by ℛ |
| E23 | Explanation | **PASS** | 4 | `overall=DENY; per-rule={r1:PASS, r2:DENY}` |
| E24 | End-to-End | **PASS** | 4 | 11/11 chain components OK |

**22 PASS · 1 FAIL · 1 BLOCKED.** Level ≥4: **21/24**. Level 5: **8/24**.
