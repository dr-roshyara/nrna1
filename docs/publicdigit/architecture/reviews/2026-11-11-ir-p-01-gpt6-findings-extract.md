# IR-P-01 (GPT-6): findings extract

| | |
|---|---|
| **What this is** | **An extract**, recorded by Claude, of the independent review **IR-P-01** of the Platform (tenancy) design **revision 2**, written by **GPT-6 in ChatGPT** and pasted into the session by the Principal on 2026-11-11. **The full report was not saved to the repository**; this extract preserves its verdict and its four findings for provenance. If the full text is later supplied, it supersedes this extract |
| **Reviewer** | GPT-6 (OpenAI), ChatGPT; not Claude, not DeepSeek, not in Claude Code. Prior involvement disclosed: had seen an earlier summary of the Platform design |
| **Integrity it reported** | package SHA256SUMS 14 / 14; manifest 10 / 10; subject `2dd9cec3…`; revision-2 body `6908c15d…` reproduced by removing the final status paragraph; decision record `15b8d477…` |
| **Not to be confused with** | `2026-11-11-platform-rev3-deepseek-review-not-independent.md`, a **deepseek-chat** review of revision 3 (**excluded reviewer, not independent**) that was first saved under this review's filename and has been renamed |

## Verdict

**NOT READY.**
- The context's positioning (register and switchboard), the ownership boundaries and **INV-ACCESS-1 at design level** were confirmed.
- Q1, Q3, Q4, Q5, Q6, Q8: partial; Q2 and Q9: contradictory; Q7: confirmed.

## Findings (as reported)

| ID | Severity | Finding | Required correction (as reported) | Principal decision? (as reported) |
|---|---|---|---|---|
| **F-01** | **High** | **D-176 contact-change evidence and counterparty boundary:** direct contact changes can be submitted through the verified service counterparty, but the design does not establish how the underlying organisation decision is authenticated and validated. A person verified only for commercial dealings could become a route to changing emergency content-access authority | Define the counterparty as **messenger only**; specify the evidence and validation for the organisation decision, incl. challengeability and refusal of insufficient evidence | No, if D-176 means the counterparty only transmits an independently verifiable decision; yes, if the counterparty is meant to have independent authority (would conflict with D-175) |
| **F-02** | Medium-high | **Service-operation grant sequence:** provisioning began before the grant was recorded, although the design requires the grant first (D-166) | Record and verify the grant before provisioning; align sequence, invariant and commands | No |
| **F-03** | Medium | **Founding registrar bootstrap order** differed from the accepted multi-database design §3 (claim invitation before `operational`) | Align with D-178; describe the registrar's identity, claim and first-record transition | No |
| **F-04** | Medium-high | **Concurrent contact removal and emergency grant:** no atomic ordering between contact-list updates and grant creation across the two aggregates | Specify the consistency mechanism and linearisation point; add a concurrent proof scenario | No |

**Recommended disposition (as reported):**
- keep D-173 … D-176 and D-178;
- correct F-01 … F-04;
- no implementation;
- focused independent re-review;
- legal questions kept separate.

**Disposition record:**
- F-01 … F-04 were addressed in **revision 3**.
- Revision 3 was then reviewed by deepseek-chat (not independent).
- Its findings led to **revision 4** and decisions **D-179 and D-180**.
- **No independent reviewer has yet examined revisions 3 or 4.**
