# Review of addendum r8 (independent subagent, read-only; Claude family)

**Verdict: FAIL.** The cause is a large undisclosed derivable class. No silent false agreement was found.

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
- **Closure rules: correct.** R-b ≡ R0; U → U5 for c1 named histories; SUPP c1; R-c/R-f (ii); R-h/U (i) and (ii).
- **R-h/U (iii)-L is over-exposed.** Its holds is derivable only via K8.7 and U7. Its least counterexample is a computed value.
- **Parser:** r7's "safe" verdicts still hold, and no new path was found. Minor: a dict adp with null iss attributes was tolerated.
- **Still BLIND but derivable:**
  - K4b: 192 units (K4b ⇔ H ∈ R_B);
  - iS: 79;
  - member(R_B): 154 (syntactic).
- **Prose:** "all forms" for R-h overstates its justification.

## Reconciliation (main analyst)
Every finding is accepted. Three successive rounds each found a further derivable class among the named-history fields. **The conclusive, conservative fix is to make every named-history, extra and SUPP field EXPOSED.** BLIND is left with computed quantities only: 87 V1b form verdicts, 60 least counterexamples and 38 R5 units (185 in P0).

The R-h (iii)-L over-exposure is disclosed and kept, because it is conservative. A dict adp with iss attributes is now rejected.

- Comparator `e79f9434…`; selftest `c195b5a9…` PASS (Y5 added).
