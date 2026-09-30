# C11 — Hypothesis testing · v1.2 (delta)

**Base:** `contracts-v1.1/C11-hypothesis-testing.md`, sha256 `762b3b454fab69aabe5c6061ab5459cd2793f73230a8192725e8e608ceb483b8`.

## Changes
1. **Blocked (HDR-2, HDR-3).** `f_checkpoint.py run` refuses (HUMAN DECISION REQUIRED) while the decisions are null. Test execution is specified here and **not implemented in v1.2**; it is implemented and independently reviewed before the first confirmatory checkpoint.
2. **One confirmatory look (F-15).** Each hypothesis is tested confirmatorily once, at its pre-registered `confirmatory_checkpoint`. All other checkpoints are monitoring only: reported, never "confirmed", and never cumulative. A growing corpus never re-confirms a hypothesis. A new look is a new, separately pre-registered hypothesis.
3. **Which result governs.** The result at `confirmatory_checkpoint`, under the pre-registered comparison, stopping and multiplicity rules. Later monitoring may prompt a *new* hypothesis; it never revises the governing result.
4. **Hold-out labelling (HDR-2).** A result states its hold-out basis: processing-order or historical-time. The bare term "out-of-sample" is never used.
5. **Immutability check.** Before testing, the hypothesis record's sha256 must equal its RESEARCHED freeze (`checkpoints/CP-##/HYPOTHESES.json`: `immutable: true`).
