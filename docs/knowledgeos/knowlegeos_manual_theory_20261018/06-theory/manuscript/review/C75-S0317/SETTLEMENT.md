# Window C75 (S0317): settlement

*2026-11-02. The Professor. Object: §8.68 draft (`7771e1165`, additions `3839beb35`). Reviewer W (Opus): step-1 inventory with sha256 (in `3839beb35`); report `755bda279` (`SHA256-REVIEW.txt`). 14 findings: 2 high, 12 normal, none critical. All accepted. Result: §8.68 v2, filename kept and title restated. The v2 edits were confirmed by script asserts before this commit.*

## Theory first

**Held (VALIDATED by W):**
- C1: the decision rule is not a function, and the status rule ignores non-collapse;
- C2: structural distance is not semantic distance, and there are three uncertainties;
- C4: detection by construction; a run without output confirms nothing;
- C5: Zero as preserving the unresolved state; the boundaries.

**Corrected:**
- **C3 (W-01).** The hard gate is a prohibition, and the trivial abstainer passes it. The source counts UNKNOWN as abstention and states convergence only as a question. §8.65 C3's gap stays open.
- **C3 (W-02).** "Collision worse than divergence" holds only when a divergence is reported as "not shown same". A false F is not ranked below a false T.
- **C3 (W-04, W-05).** The §5.4 quote is restored ("in general"; a lexicographic order is not an average). The source's repaired rule has no shown totality.
- **C1 (W-10).** "Consistent estimator" holds under i.i.d. sampling. The defects are the estimand (it depends on the representation) and m = 2 for pairs.
- **Homes and readings (W-03, W-06, W-07, W-13, W-14):** §8.67.3 for the fail-safe; reading (ii) of the lens; entropy and false certainty by analogy.
- **Lineage (W-09):** the gate and the three-way structure restate S0311.
- **Placement (W-12):** THEORY-MAP §7 is annotated; the fork stays open.

## Dispositions

| W | Disposition | Where |
|---|---|---|
| W-01 | accepted; title restated | §8.68 title, PL3, C3; index |
| W-02 | accepted | §8.68 PL3, C3 |
| W-03 | accepted | §8.68 header, homes, C3 |
| W-04 | accepted | §8.68 homes, C3 |
| W-05 | accepted | §8.68 C3 |
| W-06 | accepted | §8.68 C5 |
| W-07 | accepted | §8.68 C2 |
| W-08 | accepted | §8.68 C4 |
| W-09 | accepted | §8.68 lineage, C4, §8.68.4 |
| W-10 | accepted | §8.68 C1 |
| W-11 | accepted | §8.68.3 |
| W-12 | accepted; map annotated | §8.68.3; THEORY-MAP §7 |
| W-13 | accepted | §8.68 C1 |
| W-14 | accepted | §8.68 C5 |

**Minor coverage items** (the U_semantic naming; "UNCERTAIN"; T_snf without SNF(E); subtle cases; the URDNA2015 and "calibrated" glosses) are added to §8.68.3. W's note that "A1 is used in its recognized reading" stands in a section where no claim uses A1 is kept as the chapter's standard line.

## R3

STEP8 L17's stale pointer "§8.67 C3" is corrected below. No inbound citations to §8.68. No register change.

## Process

**Effort** (phase.py; waiting logged apart):

| Category | Time (UTC) |
|---|---|
| Reading | 01:35:24–01:36:04 |
| Drafting | 01:36:04–01:38:40 |
| Waiting | 01:38:40–01:45:23 |
| Rework | 01:45:23 to this file |
| Settlement | logged at commit |
