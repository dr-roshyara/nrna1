# Independent review: coding manual r3 and inter-coder reliability (Gate 1, RESERVE)

**Sources used:** `ADOPTION-AND-PREREG.md`, `VERIFY.json`, `GATE1/MANUAL-r3.md`, `GATE1/ROWS.md`, `GATE1/CODING-C1.json`, `GATE1/CODING-C2.json`, `GATE1/NOTES-C1.md`, `GATE1/NOTES-C2.md`, both `AGREEMENT-*.json` files for each bundle, and `RESERVE/CODING-C1.json` / `CODING-C2.json`. No code was run.

**Limit on evidence scope:** I could not find the B1 and B2 coding files. I tried `CODING-B1.json`, `B1.json`, `CODING-BLIND-B1.json`, `B1/CODING.json` and several other names, in both bundles. My tools can only open files at known paths; they cannot list a directory. So B1–B2 is checked only for internal consistency (each agreement value against the number of listed disagreements). It is **not** recomputed from the codings. Wherever this review says what B1/B2 coded on a row, that comes from the scorer's disagreement list.

---

## 1. Recomputation (Gate 1, n = 28)

| Judgement | B1–B2 (scorer) | B1–B2 check | C1–C2 (scorer) | C1–C2 recomputed from codings |
|---|---|---|---|---|
| V1 | 0.393 | 17 disagreements → 11/28 = 0.393 ✓ | 0.857 | 24/28 = **0.857** ✓ |
| V2 | 0.964 | 1 → 27/28 ✓ | 0.821 | 23/28 = **0.821** ✓ |
| V3 | 0.964 (κ 0.891) | 1 → 27/28 ✓ | 0.857 (κ 0.273) | 24/28 = **0.857** ✓; κ = (0.857 − 0.804)/(1 − 0.804) = **0.273** ✓ |
| V4 | 0.750 | 7 → 21/28 ✓ | 0.929 | 26/28 = **0.929** ✓ |

I also checked V5 = 1.0, V6 = 1.0 and V7 = 0.964 (R-30) for C1–C2. All match.

**Disagreeing rows**

- **B1–B2 V1 (17 rows, all SUPPORTED vs UNTESTABLE):** R-30, 32, 35, 38, 45, 46, 49, 55, 56, 58, 61, 62, 63, 65, 67, 68, 93. B1–B2 V2: R-67. V3: R-97. V4: R-50, 52, 55, 61, 63, 67, 93.
- **C1–C2**, shown as C1 / C2:
  - V1: R-36 A/S, R-40 A/V, R-64 A/U, R-97 S/U
  - V2: R-35 S/U, R-49 S/U, R-56 S/A, R-63 U/S, R-68 S/U
  - V3: R-36 A/S, R-40 S/A, R-61 S/A, R-97 A/S
  - V4: R-49 S/U, R-92 S/U
  - V7: R-30 U/A

  (A = AMBIGUOUS, S = SUPPORTED, U = UNTESTABLE, V = VIOLATED.)

**V3 kappa.** The collapse from 0.891 to 0.273 is mostly a prevalence effect. Each coder has 25 SUPPORTED, 2 AMBIGUOUS and 1 NOT-MODELLED, so chance agreement is 0.804. The telling detail is that the two coders' AMBIGUOUS codes do not overlap at all (C1: R-36, R-97; C2: R-40, R-61). That points to each coder flagging ambiguity in its own way, not to a rule being applied differently.

**Recomputation matches:** yes for C1–C2. B1–B2 is only checked for internal consistency.

---

## 2. Diagnosis of the V2 and V3 drops

### V2: all five new disagreements come from G7

R-67, the only B1–B2 V2 disagreement, is now agreed. The rule at issue is G7: *"V2 applies only if the record mentions permission, authorization, commissioning or execution; separating approval or acceptance from execution is outside V2."*

G7 leaves three things open, and the coders split on each of them:

- **(a) What counts as a mention:** the literal word, or the concept?
- **(b) The exclusion:** does "approval or acceptance" also cover adoption, and does the exclusion rule out the whole record or only that pair of terms?
- **(c) One term only:** what if just one of the four terms appears? "Applies only if" is a necessary condition, not a sufficient one.

My position on (c) is that V2 is vacuous when fewer than two of the four terms are present, so it should be UNTESTABLE. That follows the pattern of G1 and §E ("does not apply").

| Row | C1 / C2 | Record | Cause | Right under r3 |
|---|---|---|---|---|
| R-35 | S / U | "Rename ADOPTED (execution deferred)" | G7 (b)(c): only *execution* appears; the separation is adoption vs execution | **C2**. Its reason is that adoption is like approval, which is an analogy G7 does not state. The better reason is (c): nothing among the four terms can be conflated. The manual does not settle this |
| R-49 | S / U | "keeps evidence collection separate from repair"; "No remediation work package is opened" | G7 (a): none of the four words appears. C1 admits "the literal G7 words are absent" and reads *opening* as commissioning | **C2**. Opening is OPEN-WORK, not commissioning, and nothing is mentioned |
| R-56 | S / A | "Execution of 7B is a SEPARATE act and has NOT been issued"; Effect "execution still NOT authorized" | G7 (b), with G6 letting AMBIGUOUS through | **C1**. Authorization and execution are both present and kept apart. The exclusion removes the approval–execution pair only; it does not remove the authorization–execution separation |
| R-63 | U / S | "every supported lifecycle transition — opened · authorized · accepted & closed · design decided" | G7 (a)(c): *authorized* appears as a list item. C2: "SUPPORTED only because 'authorized' appears … Nothing is conflated" | **C1**. The gate is met, but there is only one term and no act on which V2 could be tested |
| R-68 | S / U | "acceptance of the executed work requires this subdivision first" | G7 (b): *executed* appears, and the only separation is acceptance vs execution | **C2**. That pair is exactly what G7 excludes |

**Class:** all five are *wording*: an undefined "mentions", plus an exclusion clause whose scope is unclear. None traces to G6 alone, to G2, or to anything missing from section F.

### V3: none of the four disagreements comes from a G rule

G6 (*"code AMBIGUOUS only if the competing readings give different codes for that judgement"*) is stricter than the old definition of AMBIGUOUS. It did not cause these disagreements, but it did not stop them either. G6 does not require a competing reading to be consistent with the coder's own list of operations, or to bear on the judgement being coded. All four disagreements are AMBIGUOUS vs SUPPORTED.

| Row | C1 / C2 | Record | Cause | Right under r3 |
|---|---|---|---|---|
| R-36 | A / S | Effect "AST-013 amended (+6 lines)" | Something else: V3 does not say how to treat changes made by acts that are not modelled. G6 lets AMBIGUOUS through | **C2**. Both coders list AMEND(ED) under `not_modelled`. §D-V3 checks a change "for its operation", and a not-modelled act has no §B frame. C1 contradicts its own R-45 practice ("attributed it to the not-modelled … act … V3 SUPPORTED") |
| R-40 | S / A | "A4 candidate + OQ-ENG-004 protocol commissioned" | G6: C2's two readings ("authorization treated as commissioning" vs "loose description") are V2 readings carried over into V3 | **C1**. Under either reading the change is derived or belongs to the not-modelled COMMISSION act, so it is SUPPORTED either way. G6's "for that judgement" rules out AMBIGUOUS here |
| R-61 | S / A | "accepted as the **authoritative assessment** of the current governance model" | Source interpretation, with G6 admitting the reading | **C1** (moderate confidence). §A's ACCEPT explicitly covers "a completed assessment", and the assessment becoming authoritative is the derived consequence of accepting it |
| R-97 | A / S | "STANDING SCOPE STATEMENT, replacing any broader claim" | Same gap as R-36. This row also disagreed under B1–B2, so it is not new | **C2**. Both coders list REPLACE(ING) under `not_modelled` |

**Summary of the drops.** The V2 drop is a real rule effect: G7 introduced a gate and an exclusion that nobody can apply consistently. The V3 drop (3 net rows) is not caused by any G rule. It is AMBIGUOUS-flagging that G6 fails to discipline, together with a gap in V3 that predates r3. At n = 28 the standard error of a difference in agreement is about 0.08, so the V3 drop is around 1.3 SE. That is weak evidence on its own. The V2 pattern is systematic.

---

## 3. Are the V1 and V4 gains real?

**V1.** All 17 B1–B2 disagreements now agree as UNTESTABLE, which is the value G1 prescribes. Spot-checks:

| Row | Check | Verdict |
|---|---|---|
| R-30 | SEAL + ANNOTATE; no guard applies and no refusal | **True agreement** |
| R-46 | APPROVE a plan; unguarded | **True agreement** |
| R-55 | ACCEPT; the F-WP6R-1 remark is not a refusal that cites a rule | **True agreement** |
| R-65 | AUTHORIZE 7C. The record never names 7B as predecessor, so by G4 the successor-slice guard does not apply | **True agreement under the rule.** Validity caveat: 7C is plainly a successor in substance, so G4 turns a substantive guard into a textual trigger |
| R-58 | Both code UNTESTABLE, by different routes. C2: successor slice, acceptance of 7A unstated, so silence. C1: "either way". "Guards verified" does not include 7A's acceptance | **True but fragile**: C2's route depends on the per-act F1 default, which is absent from Gate 1 |
| R-45 | C2: "the act performed is RATIFY, which is not guarded". But §C's evidence bars depend on content, not on the operation. Naming "two responsibilities where it had been described as one" may be new vocabulary | **Possibly convergent.** Both coders share an operation-bound reading of the guards that the manual does not require |
| R-35 | Adopting a new name ("PublicDigit Engineering Platform") could be new vocabulary with only a purpose given, which is VIOLATED under G4. Both coders treat it as unguarded | **Possibly convergent**, for the same reason |
| R-31 (control; agreed under B too) | Both code NOT-MODELLED. But G1 is mandatory: if the rename is unguarded, V1 is UNTESTABLE; if it is guarded, it is VIOLATED. G2's "NOT-MODELLED only if every act is not modelled" is necessary, not sufficient (VERIFY says the same). On RESERVE, C1 and C2 **split** on exactly this point (R-57: NM vs U) | **Convergent error on the letter of G1** |

**V1 verdict.** The gain is mostly true agreement. G1 does resolve the vacuous case on clearly unguarded records, which is about 13–15 of the 17 rows. A minority of rows rest on a shared reading that the manual does not mandate. These are the evidence bars treated as operation-bound (R-45, R-35) and G2 treated as overriding G1 (R-31). Those rows are convergent, or at least shared priors.

**V4.** The seven B1–B2 disagreements all agree now: R-50 U/U, and R-52, 55, 61, 63, 67, 93 all S/S.

| Row | Check | Verdict |
|---|---|---|
| R-55 | "DELIVERED -> ACCEPTED & CLOSED" with stated evidence, by the ARB | **True** |
| R-93 | Closed on the evidence file, by the ARB CHIEF | **True** |
| R-67 | Accepted and closed, with evidence re-verified, by the ARB | **True** |
| R-52 | DEFERRED → OPEN on the reproduced RED, by the ARB | **True** |
| R-63 | Reclassified on the operational validation, by the ARB | **True**, provided "status" includes classification (the term is undefined) |
| R-61 | Standing change ("authoritative") on evidence, by the ARB | **True**, provided "status change" includes standing (VERIFY flags this as undefined) |

**V4 verdict.** This is true agreement under G5, but part of it is mechanical. On Gate 1, V4 becomes almost "authority named AND evidence present". The two new disagreements, R-49 and R-92, trace to G5 having no definition of "evidence", which is the missing F4 dependency VERIFY predicted.

- **R-49:** "F-7A-1 evidences a current inconsistency" and "DEFERRED … BLOCKED". **C1 is right** on the letter of G5.
- **R-92:** "Constitutional review closed". It is unclear whether an adopted review counts as evidence. This is **indeterminate**, leaning C1 on the letter.

---

## 4. Correlated priors

- **Operation choice.** Ops Jaccard is 1.0 for C1–C2 on both bundles. On RESERVE it rose from 0.821 (B1–B2) to 1.0, even though **section G contains no rule about operations**. That rise cannot be an effect of r3. It shows the coders' shared priors, or a shared session setup, deciding the choice of operation. Contestable choices come out identical, for example R-73 and R-92 (ADOPT-DECISION rather than ADOPT) and R-75 (ANNOTATE rather than CONTRA).
- **Guards.** Both coders read the guards as attached to operations (R-45, R-35), and both let G2 override G1 on R-31.
- **Counter-evidence.** The coders' AMBIGUOUS choices do not overlap, so they are not copies of each other.

**What this design can establish:**

1. It can establish reproducibility within one model family.
2. It supports a narrow causal claim: G1 removed one specific disagreement pattern (17 SUPPORTED/UNTESTABLE splits, all resolved to G1's value), and G7 created another. These links are tied to the text of the rules.

**What it cannot establish:**

- the manual's reliability across coders in general, whether human or from another model family;
- validity (whether the codes are correct);
- run-to-run noise, since each coder ran once and there is no test–retest.

Same-family agreement is an **upper bound** on reliability. The non-Claude coder named in the adoption record is the real test.

---

## 5. Pre-registration outcome

| Condition | Result |
|---|---|
| V1 above 0.393 | 0.857 ✓ |
| V4 above 0.75 | 0.929 ✓ |
| No judgement drops by more than 0.1 | ✗: V2 falls 0.143 (27→23 of 28) and V3 falls 0.107 (27→24 of 28) |

**The applicable outcome is row 3: "V1 improves but another judgement drops by > 0.1". This is a trade-off in which the G rules interact, and a review is needed.** Row 1 ("improves reliability") does **not** apply.

The sealed main-analyst expectation got V1 right (≥ 0.75) but was wrong that "the other judgements are stable". V4 also rose more than "modestly" (+0.179).

**Derived prediction: falsified.** The prediction was that r3's gain would be larger on RESERVE than on Gate 1.

| Measure | Gate 1 change | RESERVE change |
|---|---|---|
| V1 | +0.464 | 0.000 |
| Mean over V1–V7 | +0.071 | +0.011 |
| V2 / V3 / V4 | −0.143 / −0.107 / +0.179 | +0.077 / +0.077 / −0.077 |

A levels version fails too: mean C1–C2 agreement is 0.918 on Gate 1 and 0.912 on RESERVE. The only measure that improves more on RESERVE is ops Jaccard, which G does not target.

**Caveats:**

- The prediction named no metric.
- It was close to unfalsifiable in its own favour on V1, because of a ceiling: RESERVE V1 started at 0.923, so its maximum possible gain was 0.077.
- The mechanism behind it gets only partial support. Some Gate 1 residual disagreements do trace to the missing F1, F2 and F4 rules (R-40 V1, R-36 V1, R-49/R-92 V4). But RESERVE V4 fell despite having F4.

---

## 6. Smallest r4 fixes (RECOMMENDATION ONLY)

1. **G7 (causes all five V2 regressions).**
   - (a) *Clarification:* "'Mentions' means the words permission, authorization, commissioning or execution, in any word form (e.g. 'authorized', 'executed'). A concept expressed in other words does not count."
   - (b) *Substantive:* "V2 is UNTESTABLE unless at least two of the four are mentioned. The approval/acceptance exclusion removes only that pair."
   - With these, R-35, R-49, R-63 and R-68 would all be U and R-56 would be S.
2. **V3 and not-modelled acts (causes R-36 and R-97).** *Clarification*, since it follows from "for its operation": "A direct change attributed to an act listed under `not_modelled` is outside V3."
3. **G6 (causes R-40 V3 and makes the rest possible).** *Clarification:* "A competing reading counts only if it is consistent with the operations and `not_modelled` acts you listed, and it changes the code of *this* judgement. A reading that concerns another judgement does not count."
4. **G1 over G2 (causes R-31, and the RESERVE R-57 split).** *Clarification:* "G1 is applied first. NOT-MODELLED is used only when at least one unmodelled act would be §C-guarded."
5. **§C evidence bars (causes R-45 and R-35 convergence).** *Clarification:* "The evidence bars attach to the content of an act (new vocabulary, categories, methodology), whatever its operation."
6. **G5 "evidence" and the per-act default for unstated guards (causes R-49 and R-92 V4, and R-40 V1).** On Gate 1 this means importing F4 and F1. That is **substantive**, and it is the protocol change the adoption record says needs human authorization. The alternative is to accept that the two r3 manuals are not equivalent and to stop comparing their gains.

---

## Disagreements with the record, classified

| # | Item | Class |
|---|---|---|
| D1 | The V2 drop is caused by G7's undefined "mentions" and the unclear scope of its exclusion (5 rows) | wording |
| D2 | The V3 drop is not a G-rule effect. It comes from G6's unconstrained "competing readings" plus a gap in V3 for not-modelled acts (R-36, R-40, R-97 are *coding*; R-61 is *source interpretation*) | coding |
| D3 | Remaining V1 splits: R-36 (G2's "act" is undefined; *wording*), R-40 ("the problem" is undefined in G4; *wording*), R-64 (C1's VIOLATED reading is not formally available for a refusal; *formal reasoning*), R-97 (reads "no evidence requests reopening" as a rule citation; *source interpretation*) | mixed |
| D4 | V4 splits R-49 and R-92: "evidence" undefined without F4 | evidence scope |
| D5 | V7 R-30 persists from B. No supersession verb, so C1 (UNTESTABLE) is right | source interpretation |
| D6 | R-31: both C coders apply G2 over G1; this is convergent error on the letter of G1 | formal reasoning |
| D7 | Ops Jaccard of 1.0 (and 0.821 → 1.0 on RESERVE with no G rule on operations) is evidence of shared priors. "Improves reliability" should read "within-family reproducibility" | independence |
| D8 | The sealed expectation that "the other judgements are stable" was wrong | substantive |
| D9 | The derived prediction is falsified, but it was posed without a metric and against a V1 ceiling | formal reasoning |
| D10 | B1–B2 could not be recomputed because its codings were not found; only internal consistency was checked | evidence scope |
