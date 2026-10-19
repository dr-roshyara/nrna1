# Decision brief for the Principal: rule changes after the whole-book review S0100

*2026-10-19. By the Professor. The proposals are **not in force**; RULEBOOK-v1.3 governs. Details: `RULEBOOK-REVISION-PROPOSAL-S0100.md`; test outputs: `PROFESSOR/checks/`.*

**Question.** Should five checks be added to the method, and in which form?

**Evidence.** The stage-1 review found 4 of 8 planted items (`88b6e3890`), including the critical one. It missed only what is **absent**: two undefined terms (RX-3, "watermark"), a removed result (CON-0149), and a stale citation elsewhere (§9.8 → §5.7 C3). It under-rated a lemma with a missing premise. Run read-only on the frozen copy, the proposed checks found every one of these known cases. On the working book, the register check also found three real register defects that stage 1 did not report (CON-0138, CON-0140, CON-0143).

| ID | Decision | Recommendation | Uncertainty | What would change it |
|---|---|---|---|---|
| D-R1 | Require the **register-home check** before every review and settlement | **Approve** | low. Exact on the known case. Its non-status-tag flags are information | many flags without a defect behind them |
| D-R2 | Require the **narrowed undefined-term check** (tier A book-wide; tiers B/C per new or changed section) | **Approve, with a review after stage 2** | medium. All four known cases are found, but the false-positive rate is unknown (40/26/9 candidates in the working book, unclassified). "lane"/"BC-7" were tested on a constructed fixture, not the original | stage 2's classification giving mostly false positives |
| D-R3 | Require the **inbound-citation check for every accepted correction**, listed and reassessed in the settlement row | **Approve** | low. It found the missed P6b. It misses uncited paraphrase | — |
| D-R4 | Require a **proof-step table** behind every "adequate" judgement of a derivation | **Approve** | medium. Costly for reviewers. The helper covers only formatted lemmas | reviewer time doubling without new findings |
| D-R5 | **Severity:** a missing premise in a foundational derivation is critical; resolution needs the statement, the proof and the downstream results corrected, not the register only | **Approve** | low. Applies v1.3's own definition explicitly | — |
| D-R6 | **Form:** a v1.4 amendment now, or fold into v2 | **v1.4 amendment** (smallest change; v2 stays a separate decision) | — | a decision to adopt v2 as a whole |
| D-Q2 | Permit reading the manuscript's git history (`adbe16c5c`) to re-run the original "lane"/"BC-7" case | **Optional:** permit once, read-only, for this test only | — | — |

**Implications.**
- Each approved check adds a short mechanical step to step 8 and the settlement. Tier A takes about 10 s and a few minutes of disposition.
- No completed result changes. Stage 1 is not re-scored.
- Stage 2 can start under v1.3 without these decisions (`STAGE2-READINESS.md`).

**Not decided here** (reserved): whether stage 2 begins; the settlement's decision briefs (A1's reading, independence, K4, Ch. 8's scope, the bearer of the dimensions); Chapter 7; TARGET-TOC.
