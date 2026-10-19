# Decision brief for the Principal: rule changes after the whole-book review S0100

*2026-10-19. By the Professor. The proposals are **not in force**; RULEBOOK-v1.3 governs. Details: `RULEBOOK-REVISION-PROPOSAL-S0100.md`; test outputs: `PROFESSOR/checks/`.*

**Question.** Should five checks be added to the method, and in which form?

**Evidence.** The stage-1 review found 4 of 8 planted items (`88b6e3890`), including the critical one. It missed only what is **absent**: two undefined terms (RX-3, "watermark"), a removed result (CON-0149), and a stale citation elsewhere (§9.8 → §5.7 C3). It under-rated a lemma with a missing premise. Run read-only on the frozen copy, the proposed checks found every one of these known cases. **Exception:** R2 misses the earlier lane/BC-7 case on the book at `adbe16c5c` (a known case, not validation). On the working book, the register check also found three real register defects that stage 1 did not report (CON-0138, CON-0140, CON-0143).

| ID | Decision | Recommendation | Uncertainty | What would change it |
|---|---|---|---|---|
| D-R1 | Require the **register-home check** before every review and settlement | **Approve** | low. Exact on the known case. Its non-status-tag flags are information | many flags without a defect behind them |
| D-R2 | Require the **narrowed undefined-term check** (tier A book-wide; tiers B/C per new or changed section), with a recorded disposition for every candidate | **Approve as a reading list, with its known limits stated** | medium-high. On the working book, 64 of 75 candidates are false positives and 11 are actionable (7 true, 4 review labels). **The original lane/BC-7 case at `adbe16c5c` is missed at the drafted thresholds** (a known case, not validation; Q6) | the next planted-gap test showing that R2 misses fresh cases too |
| D-R3 | Require the **inbound-citation check for every accepted correction**, listed and reassessed in the settlement row | **Approve** | low. It found the missed P6b. It misses uncited paraphrase | — |
| D-R4 | Require a **proof-step table** behind every "adequate" judgement of a derivation | **Approve** | medium. Costly for reviewers. The helper covers only formatted lemmas | reviewer time doubling without new findings |
| D-R5 | **Severity:** a missing premise is critical if any of F1–F4 holds (results rest on it; derivations rest on it; it decides a kernel or boundary question; the missing premise is one the book states is false or undefined); normal only under N1; resolution needs (i)–(iv), not the register only | **Approve** | low. Explicit criteria; P1 classifies critical by F1 and F4 | criteria that over-rate many ordinary Ded claims |
| D-R6 | **Form** | **Decided by the Principal (2026-10-19):** `RULEBOOK-REVISION-PROPOSAL-S0100.md` is the v1.4 amendment, not in force; on approval, the Rule Reviewer records it in v1.3 (entry 56) and in the v2 proposal | — | — |
| D-Q2 | Reading git history at `adbe16c5c` | **Permitted and done (2026-10-19, ACCESS-RECORD):** R2 as drafted misses the original case. Q6 asks whether to widen tiers B/C | — | — |

**Implications.**
- Each approved check adds a short mechanical step to step 8 and the settlement. Tier A takes about 10 s and a few minutes of disposition.
- No completed result changes. Stage 1 is not re-scored.
- Stage 2 can start under v1.3 without these decisions (`STAGE2-READINESS.md`).

**Not decided here** (reserved): whether stage 2 begins; the settlement's decision briefs (A1's reading, independence, K4, Ch. 8's scope, the bearer of the dimensions); Chapter 7; TARGET-TOC.
