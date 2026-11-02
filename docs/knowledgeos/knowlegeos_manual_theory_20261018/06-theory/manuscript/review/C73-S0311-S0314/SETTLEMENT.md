# Window C73 (S0311–S0314): settlement

*2026-11-02. The Professor. Object: §8.66 draft (`9df358e53`). Reviewer W (Opus): step-1 inventory with sha256 (in `9df358e53`), report `bd3f076f5` (`SHA256-REVIEW.txt`). 12 findings: 4 high, 8 normal, none critical. All accepted. Result: §8.66 v2 (filename kept, title restated). The write of v2 was confirmed before this commit.*

## Theory first

- **Held:**
  - standing is absent from the core lists;
  - "KIR exists ≠ knowledge exists";
  - a toy simulation cannot confirm the decomposition it was scored against (C3, VALIDATED within scope);
  - V-3, a computed fact no reader can obtain;
  - "verify" in two senses;
  - S17 does not compare providers.
- **Corrected:**
  - **C4 restated (W-01).** The resolver answered AMBIGUOUS, which the book counts as a success (§1.14.2-5). The cause is an observer gap. The exit is a recorded governance act made legible, not a kernel must-do. "Correct under the rules" means under INV-A3 as recorded, which is not adopted (§1.14.5-6). One bounded, authorised exception was added.
  - **C5: the three premises of byte-identity (W-02).**
  - **C1: replaceability homed** in §10.1's generic class and the stability test, not delegability (W-03).
  - **C2: the degenerate solution of convergence (W-04).** The abstainer gap stays open in part. Zero has two readings in the source (W-05).
  - **C3: the premise re-cited** and the scoring premise added (W-06). The conflict definition is tied to the non-rule fork (W-07).
  - **C1: the drift starts at S0310** (W-09). The fork is now used (W-10). The placement in §8.66.3 is open, not settled (W-11).
  - **K5 is qualified** by the declared label (W-12).
- **Map:** the THEORY-MAP §7 interpreter-fork line is annotated with S0312's "Not proven" and "can be replaced" (a generic-class reading).

## Dispositions

| W | Disposition | Where |
|---|---|---|
| W-01 | accepted; title restated | §8.66 title, PL4, C4, §8.66.4; index |
| W-02 | accepted | §8.66 PL5, C5, §8.66.4 |
| W-03 | accepted | §8.66 PL1, C1; index |
| W-04 | accepted | §8.66 PL2, C2; index |
| W-05 | accepted | §8.66 C2 |
| W-06 | accepted | §8.66 PL3, C3 |
| W-07 | accepted | §8.66 C3 |
| W-08 | accepted | §8.66 C4, C5 |
| W-09 | accepted | §8.66 PL1, C1 |
| W-10 | accepted; map annotated | §8.66 header, C1; THEORY-MAP §7 |
| W-11 | accepted | §8.66.3 |
| W-12 | accepted | §8.66 C5 |

**Coverage items marked MISSING by W:** all added to §8.66.3 or to the claims. They are S0312's partial identity, S0311's mixed success properties, the core drift inside S0311, the "better than an LLM" claim, abstention 80% against 100%, the evidence/Constitution asymmetry, and the scope rule.

## R3

§8.66 C1–C5 are not cited outside §8.66. The 08.00 row and the THEORY-MAP §7 fork line are updated. No register change.

## Process

- Effort (phase.py; waiting apart):

  | Category | Time (UTC) |
  |---|---|
  | Reading | 01:02:13–01:03:45 |
  | Drafting | 01:03:45–01:06:44 |
  | Waiting (W step 2) | 01:06:44–01:15:04 |
  | Rework | 01:15:04 to the settlement file |
  | Settlement | logged at commit |
