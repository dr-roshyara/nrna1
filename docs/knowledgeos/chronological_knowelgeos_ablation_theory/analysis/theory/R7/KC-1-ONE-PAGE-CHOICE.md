# KC-1 — the choice on one page

**PROPOSED / decides nothing. 2026-10-30, F-LOG-0311.** Plain-language rendering of `KC-1-READINESS-AUDIT.md` r2.1 §7 and §14 and `KC-1-VALIDATION/APPROVAL-PACKAGE.md` Approval 5. Under your direction "B", KC-1 is decided on the existing evidence; the external check is waived, not done.

## The question

Our design splits the theory into two modules. A key figure is **how many rules tie the two modules together** (E3-REL). The design was accepted with the figure **13** (8 under the stricter count).

Six rules are of a special kind: they **deny** a link. Example (R17): *"A rule's status does not dictate what work must happen."* The question is:

> **Does a rule that says "A is not tied to B" count as a rule about A and B?**

Four of those six (R14, R15, R16, R21) decide whether the figure is 13 or lower.

## Your options

| | Option | In plain words | Figure | What changes | Follow-up work |
|---|---|---|---|---|---|
| **1** | **Count them** (a) | A rule that denies a link is still *about* the things it mentions. | **13** (8) | Nothing. The accepted design stands as is. | Record the rule (KC-4) |
| **2** | **Don't count them** (b) | A pure "no link" rule imposes nothing on anything, so it ties nothing together. | **9** (4) | 6 entries change; the design figure must be re-accepted. | KC-2, KC-3, KC-4 |
| **3** | **Count only what they assert** (c) | Split each such rule into what it asserts and what it denies; count only the assertion. **You must also pick the exact splitting rule:** strict → 9 (4) · R15 counted → 10 (5) · R14 + R15 counted → 11 (6). | **9–11** | 3–5 entries change; re-acceptance. | KC-2, KC-3, KC-4 |
| **4** | **Keep both answers** (split) | Keep option 1 for which module hosts what, and report a separate "real coupling" figure by option 3's logic. | 13 for hosting; 9–11 for coupling | No entries change, but the definition of the figure in the design (E3:111) must be formally amended. | KC-3 + amendment, KC-4 |

"(c)" without a named splitting rule is **not** an option: the figure is then anywhere from 9 to 13.

## How to choose — the two guiding questions

- **Should the rule work from its wording alone, without a hand-made list of exceptions?** If yes, option 3 is weakest; options 1 and 2 are equal.
- **Should a rule count as linking the modules only if it actually constrains one side by the other?** If yes, option 1 fails; options 2, 3 and 4 remain.

Neither question has a right answer in the evidence. That is why this is your decision. A smaller figure is not "better", and agreement between test readers does not make a reading correct.

**Facts to weigh:** option 1 is the only one that changes nothing already accepted. Claude test readers given the general form of option 1 reproduced its verdicts 5 of 5 times. Options 2 and 3 were never tested blind as written.

## Three small boxes in the same act

1. **R80** ("H-B carries no record requirement"): decide separately whether it hosts nothing (as written) or is read like its neighbours. It does not affect the figure.
2. **Scope note, carried in the act text:** it holds when the "statement" column is what is read (K18), with a caveat for R12 (K2), and it was computed on Claude-only encodings.
3. **If option 1:** add the clarifying sentence, worded positively: *"The negation sentence (T3:68) applies only to contrast clauses inside positive statements."*

## Stays open whatever you choose

The external non-Claude check is **waived, not satisfied**. Also still open: C-γ, D12, the spanning rule, C6, K15–K19, R11, (g), (h), H13, A1, A5 and CV9/§3b. Canonicalization (E4/R8) and implementation (E5) each need their own authorization.

**Standing qualifier:** one corpus; within-family (Claude) evidence; cross-family waived; H-D vs H-B non-identifiable.

## How to reply

> **"KC-1: option N"**, plus for option 3 the splitting rule (strict / R15 / R14+R15), plus **R80: as written / like its neighbours**.

---

## Correction note (appended 2026-10-30; the text above is unchanged and is the page on which KC-1 was decided)

Three passages above are imprecise under the definitions clarified after the decision
(F-LOG-0313 to F-LOG-0316):

- Line 7 describes the figure 13 as "how many rules tie the two modules together".
- Option 4 (line 22) speaks of "a separate 'real coupling' figure" and says "the definition of the
  figure in the design (E3:111) must be formally amended".
- The second guiding question (line 29) speaks of a rule that "actually constrains one side by the other".

Under the clarified definitions:

- **13 / 8 (E3-REL, cross-module rows)** keeps its existing, accepted meaning: the rows hosted on both
  modules (E3:111). It counts hosting, not rows that tie the modules together. Under the "Add" form of
  option 4 that was chosen (F-LOG-0315), its definition in E3:111 is **not** amended.
- **9 / 4 (E3-REL-AC, cross-module asserted-content rows; proposed, subject to verification)** counts
  those of the 13 rows whose rule text asserts content involving both item sets, under the strict K1c
  classification. It counts rule-text rows; it is not a "real coupling" figure and does not measure
  real-world module dependency.

This distinction was drawn after the decision, in `KC-1-OPTION4-AMENDMENT-PROPOSAL.md`. It is not
contained in the text above and does not change what was decided.

## Addendum to the correction note (appended 2026-10-30; the correction note above is unchanged)

1. **"Those of the 13 rows" is imprecise.** The figure 9 / 4 has two sides. Under reading S, **9 of the 13** cross-module rows qualify. Under readings S′ / S″, **4 of the 8** cross-module rows qualify. Under the other D12 reading the counts are 10 of 14 and 5 of 9.
2. **"Under the strict K1c classification" applies only to the "no link" rules** (the guard rows R11, R14–R17, R21). Every other row of the 13 / 8 is classified by the general test: "Does the rule's asserted content itself state a relation, constraint, typing condition, definition, or proposition involving both item sets?" (F-LOG-0316).
3. **Citation.** The note cites "F-LOG-0313 to F-LOG-0316". The classifications of R22 (qualifies) and R24 (qualifies as an assertion) were decided in **F-LOG-0317**, and their rationale was re-confirmed in F-LOG-0319. R22 qualifies under either interpretation, so 9 / 4 does not depend on it. R24 matters only under the other D12 reading.

**Clarification to addendum item 3 (appended 2026-10-30, F-LOG-0322; the addendum above is unchanged).** "So 9 / 4 does not depend on it" means that 9 / 4 does not depend on **which** of the two recorded interpretations of R22 is applied. It does not mean that 9 / 4 is independent of R22 itself: without R22 the figure would be 8 / 3.
