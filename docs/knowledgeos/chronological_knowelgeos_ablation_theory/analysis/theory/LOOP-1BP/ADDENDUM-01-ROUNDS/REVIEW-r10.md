# Review of addendum r10 (independent subagent, read-only; Claude family)

**Verdict: FAIL.** The cause is a derivable BLIND class that §2 does not disclose. **No silent false agreement.**

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
- **The r10 edits change tier labels only.** `classify`, the counterexample gating and the licence are untouched. The selftest passes, and P0 matches §7.
- **The EXPOSED rules match the r7–r9 derivations.** No harmful over-exposure.
- **Derivable BLIND counterexamples: about 16–18.**
  - In U5 at c2, σ ≡ q, and F1 forbids iss v = p with f < ∞. So the least R_B c2 history with w1 is H* (a2 iss d0 v p prep0 f∞, w1 at t = 0, Ref {a2, w1}).
  - H* violates every form, while c1 histories keep their exposed U verdicts. So H* is the least counterexample for: R0, R-b, R-h and R-e (i)-S/(ii)-S/(ii)-O, and R-c and R-f (ii)-S/(ii)-O.
  - The a1 analogue gives (iii)-L for R0 and R-b.
  - Optionally also: R-i′ (i)-S (the bottom history H_∅); and R-d U (iii)-L (one line: the scope shrinks under D-st⁺).
- **Prose:** "computed objects" and "rests mainly on the 64 counterexamples" are false.

## Reconciliation (main analyst)
All findings are accepted, conservatively. **Every** U5 least counterexample becomes EXPOSED, along with R-i′ (i)-S's counterexample and R-d/U (iii)-L. The BLIND tier falls to 42 in P0: 25 U verdicts, 16 U counterexamples and n.

- Comparator `d8da904a…`; selftest `cf332f1b…` PASS (Y8 added).

**Not re-reviewed, by decision.** The parser and classes have been stable since r7, and this change only grows EXPOSED. The human accepted this cross-check's small blind content; the independent test moves to the blind slice (LOOP-1BQ).
