# External scientific review (D6, D4): instructions

| Field | Value |
|---|---|
| Authorization | D6 and D4 (Principal, 2026-10-17): external review of the mathematical claims, the proofs and the proposed corrections |
| Prepared | Session B, 2026-10-17 (clock 19:31) |
| Reviewer | **Preferred (Principal decision record, 2026-10-17):** a qualified **human** mathematician or theoretical computer scientist (automata, logic, order theory, foundations) who did not take part in this research cycle. A review by another AI model family **may supplement, but does not replace**, the human review. The Principal names and supplies the reviewer. No claim of independent validation is accepted until the findings have been received and assessed. |
| Restricted material | **None** is included or needed. The packet is theory text only. |
| Internal reviews so far | Five rounds by same-family fresh-context sub-agents. They are labelled INTERNALLY REVIEWED and are **not** independent. The external reviewer should not rely on them. |

## Protocol (two stages; please keep the order)

### Stage 1: falsify first

Read only `STAGE1-CLAIMS-core.md`. Note: the core claims (first part of that file) are deliberately stated as they were **before** the internal corrections; for example, C-6's uniqueness clause has no trimness condition. Judge them **as written**. The internal corrections appear only in the stage-2 documents, so stage 1 also tests whether you find the same defects independently. For each claim, reconstruct the definitions and try to **prove or refute it yourself**. Record your stage-1 verdict before reading any argument.

### Stage 2: check the arguments

Then read:
- `../validation_reports/20261017_1805_research-state-and-mathematical-review.md` §3
- `../validation_reports/20261017_1830_O1-O2-update-and-encoding-minimality.md`
- `../validation_reports/20261017_1850_reviewed-mathematical-core.md`
- `../validation_reports/20261017_1849_D6-review-D7-checks-O7-and-reviewer-nominations.md` §1–3
- `../validation_reports/20261017_1900_O5-O6-evidence-map-cycle-report.md` §2–3 and **§9, which overrides §2–3**
- the synthetic scripts in `../synthetic-checks/` (optional to re-run; synthetic data only)

### Stage 3: the D4 correction proposal

`../D4-CORRECTION-PROPOSAL-20261017_1859.md`, including its r2 amendments (r2 governs). Check each item against the manuscript `../S-SERIES-THEORY-MANUSCRIPT.md` §15 (v0.4, sha256 `28d8bba2…a0`).
- Is the correction right?
- Is it complete?
- Does it overstate anything?

Please also adjudicate the recorded disagreement **U-1** (the R2 binary-join sentence).

### Literature to verify (currently UNVERIFIED)

Pfleeger 1973 · Paull–Unger · Skowron–Rauszer 1992 · Sénizergues (DPDA equivalence) · Larsen–Skou 1991 · Eilenberg (pseudovarieties) · Paige–Tarjan · Schützenberger / Carlyle–Paz · NFA equivalence PSPACE-completeness · information-bottleneck predictive variants · Clopper–Pearson.

## Response format

One row per claim:

| Claim | Stage-1 verdict | Stage-2 verdict | Reasoning / counterexample | Corrections |
|---|---|---|---|---|

Allowed verdicts: CONFIRMED · CONFIRMED WITH CORRECTIONS · REFUTED · INCONCLUSIVE · NOT REVIEWED.

Then give an overall verdict, the open questions, and any claim you think is **missing** from the theory.

## What this review can and cannot establish

- **It can:** independently challenge the mathematics.
- **It cannot:** establish that KnowledgeOS satisfies the theory's assumptions. That needs the safety gate and the five primary sources (D2, D1, D5).
