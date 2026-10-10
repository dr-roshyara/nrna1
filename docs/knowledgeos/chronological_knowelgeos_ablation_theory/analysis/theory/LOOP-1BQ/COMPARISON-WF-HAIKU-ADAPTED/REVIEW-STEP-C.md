# Step-C review: blind slice, fresh Haiku 4.5 subagent (F-LOG-0245)

**Reviewer:** an independent read-only subagent (Claude family). It read sealed values and ran scripts with `python3 -I`.

| Group | Class | To the human |
|---|---|---|
| D0, U frame n (952,640 vs 665,920) | **(a)**: the F2 filter (enumerate_v3.py:78-84) drops the "(ρ,a) ∈ Ref" conjunct of Def 1.8 F2. Least witness: σ=(p,p), a1 absent, a2 iss d0 v=p prep0 f0, Cap ∅, w1 absent, Ref ∅, con(a2)=ρ1, r=0, c1. Restoring the conjunct gives exactly 665,920 | no |
| R0 class sizes in U | **(a)**: the frame error, plus B-rec implemented as D-rec ∧ (B-recW ∨ w1 absent) (line 132; Def 3.1 says D-rec ∧ B-recW), plus Adopted using iss-capability and omitting the adoption-act branch (lines 107-110; Def 1.10). **With all three fixes, Haiku's code reproduces the reference exactly:** R_B 221,184; H-D 132,040; H-B 93,888; H-D&H-B 26,408; H-D&R_B 52,816 | no |
| R0 (i)-S in U | (a), downstream of B-rec | no |
| (ii)-S and (iii)-L witness results | **UNTESTABLE** (NOTES l.121 "simplified"; code hard-codes n_cex = 0 and holds = True) | no |
| (ii)-S and (iii)-L scope sizes | (a), inherited | no |
| Non-R0 readings, both frames | **UNTESTABLE** (NOTES l.13-15 and l.106: "placeholder … R0 classification") | no |
| least_counterexample | **INCOMPLETE** (NOTES l.119, l.134) | no |
| U_P2 R0 values | (a), **inferred, not re-run** | no |
| Adapter `adapt_haiku_v1.py` | syntax-only and faithful (n is the same across readings; no value changed) | no |

**OVERALL:** (a) plus UNTESTABLE/INCOMPLETE only. **No (b), (c) or (d): no evidence that the Opus, Sonnet or V1b reference is wrong.**

**Analyst note:** the "with fixes, exact match" result is a *reviewer-corrected* run of Haiku's code. It is supporting information, not an independent reproduction. **The Haiku run does not count as the fresh-subagent reproduction authorized by F-LOG-0245.** The valid fresh-subagent result remains Sonnet 5.5, 67/67 (F-LOG-0243).
