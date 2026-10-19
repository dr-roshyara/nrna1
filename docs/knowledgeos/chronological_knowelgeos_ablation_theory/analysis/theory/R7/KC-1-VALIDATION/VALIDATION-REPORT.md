# R7 · KC-1 bounded validation report (F-LOG-0309)

**PROPOSED / NOT RATIFIED. Analysis only; decides nothing. Draft r0, 2026-10-17. Not itself independently reviewed.**

**Commissioned by:** the human's KC-1 deferral act F-LOG-0309, which commissioned a bounded validation and kept downstream work blocked.

**Sources:**
- `KC-1-READINESS-AUDIT.md` r2.1 (F-LOG-0308);
- `KC-1-DECISION-MATRIX.md`, `KC-GUARD-PURPOSE-ANALYSIS.md`, `KC-MKC-REASSESSMENT.md`, `KC-CONVENTIONS-PROPOSAL.md`;
- `T3-CANDIDATE-MODEL.md`, `E3-ARCHITECTURE-PROPOSAL.md`, `E2b-ADOPTION-TEXT.md`;
- `F-GOVERNANCE-LOG.md`.

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable". **Λ7:** every analyst, reviewer and encoder here is Claude-family.

**Labels used throughout:**
- **[EST]** established by the available evidence;
- **[REP]** reproduced, but not independently validated;
- **[COND]** conditional on an assumption or interpretation;
- **[REC]** research recommendation;
- **[HDR]** human decision required;
- **[NI]** not yet investigated.

---

## 1. What was done

| work package | what | output | status |
|---|---|---|---|
| A | fresh independent re-review of the r1 fixes (M1–M9) and r2.1 fixes (N1–N10); claim-to-evidence table; evidence-quality audit | reviewer report (summarised in §2–§3) | PASS WITH LIMITATIONS; 9 defects, none numeric |
| B | independent reference implementation, written from the source definitions without using the existing code, compared only afterwards | `refimpl/ref.py`, `ref.out`, `REFIMPL.md` | all figures reproduced; 0 disagreements |
| C | protocol and materials for a human or non-Claude blind replication | `external-protocol/` (PROTOCOL, packets, score.py, analysis plan, adjudication, private key, tools) | prepared only, **not executed** |
| D, E | decision analysis, recommendation, stopping rule, backlog | this document | — |

---

## 2. Claims checked, and what survived

| # | claim | result | label |
|---|---|---|---|
| 1 | K1 at L = {R11, R14–R17, R21} is the decisive choice for E3-REL | Partly by construction (K1 overrides only L). "Only mover" holds within the enumerated space plus `kc.ALT`, given K18 = statement cell. Of the rows in L, only R14, R15, R16 and R21 can contribute; R11 and R17 are never X | [REP] + [COND] |
| 2 | K18 dependence; 6/3 under composition I | Re-derived by hand (A) and in code (B). Composition II gives 8/5 with (a). The choice of composition is itself a stipulation | [COND] |
| 3 | K2 moves the hosted row R12; E3-REL unaffected | Re-derived (A, B) | [EST] |
| 4 | E3-REL figures by K1 value | Re-derived (A, B), with 0 disagreements. The guard vectors per parse are analyst stipulations (KC §2.4) | arithmetic [EST]; parses [COND] |
| 5 | T3 is not self-contradictory as written | A consistent reading exists: FZ is a model of it under N-scope or lex specialis. The literal "Negations … are not read" also admits N_gen | [COND] |
| 6 | The clarifying clause changes no verdict | True by construction relative to FZ, including T3's row-specific entries (e.g. R80) | [COND] |
| 7 | Blind replications | Descriptive counts confirmed by an independent classifier. They show how the packet texts are read, not which reading is correct | descriptive [EST]; as evidence [COND] |
| 8 | Branch S needs an amendment to E3:111 | Valid. E3-REL_assert is a proper subset of hosted-on-both, because R16 and R21 never couple. "Relabels" is an interpretive judgement | [EST] / [COND] |
| 9 | R80 needs its own box | The facts hold: E3-REL is defined over T-A rows only, and "none" rests on T3:122 and T3:265. Making it a separate box is normative. R80 still affects T-B host cells | facts [EST]; box [HDR] |
| 10 | C-γ stays returned; other items stay open | C-γ confirmed. The audit's lists omit R11 (the §1b grouping guard list, F-LOG-0273) and CV9/§3b | [EST]; lists incomplete |

**The E3-REL notation.** "x/y" is two counts, not a ratio:
- **x** counts the E3-REL rows under reading S; **y** counts them under S′ (which equals S″).
- **Eligible rows:** T-A minus the hosted set H = {R12, R13, R23, R24}. That gives 20 rows under D12 (a); under D12 (b), R24 joins, giving 21.
- **Non-guard members:** 9 under S (R2, R3, R5, R6, R8, R9, R10, R19, R22) and 4 under S′ (R2, R3, R9, R22).
- So (a) = 9+4 / 4+4 = 13/8. SH and K1c = 9/4. R15ref and C-Σ = 10/5. R15ref-R14 = 11/6. [EST]

---

## 3. Defects and caveats (none changes a number)

1. **The audit's status wording is stale** (§12.1, §14.C, §14.F). It still says "r2 not reviewed". It should read "r2 PWL; r2.1 not re-reviewed".
2. **The pre-registration has no anchor beyond the file mtimes, which the agents themselves set.** The git commit (19:40) came after the outcomes (from 19:04). The transcripts are not archived. What is shown: the mtime order is *consistent with* the prereg coming first; the self-hash shows internal consistency only. What is not proven: the order itself.
3. **"Packets of equal specificity" (F-LOG-0308, prereg §1) overclaims.** The packets were about equal in length but unequal on the decisive content.
   - **B′ omitted KC §2.4's parse**, which is exactly what defines C's guard verdicts.
   - **The glossary-vs-unfolding tension burdened B′ only.**
   - **Consequence:** blind2 shows that C's text *without its parse* is read as (a)-like. It **cannot distinguish C-with-parse from (a)**.
   - A correction is recorded as F-LOG-0310; the log is append-only.
4. **Stale classification of blind1 B.** §0, §9 and §5.2 of the audit still treat blind1 B as principle C and its outputs as "points of C's range". §12.4 reclassified that packet as (b)-like.
5. **The K18 "collapse of (c) into the column" under composition I is an analyst stipulation**, but it is labelled as computed. Report the K18 effect as 6/3 or 8/5, depending on the composition.
6. **"No verdict changes"** holds only relative to T3's row-specific entries. The A′ encoders applied T3's general rules and read R80 as CB23.
7. **The open-item lists omit R11 and CV9/§3b.**
8. **"(c) ranges over at least 9/4 to 13/8" mixes two sources.** The lower end comes from KC's stipulated parses; blind B′ produced only 12/7 and 13/8. Report the two separately.
9. **`blind2/key_sources.md` gives B′ as 1782 words.** `wc -w` gives 1830.

These defects are recorded here. The audit (r2.1) has **not** been edited, because a revision needs authorization under the current commit discipline.

---

## 4. Blind replication: what it establishes, and what it cannot

- **Established [EST], descriptively:**
  - General-form (a) packets gave T3's six guard verdicts in 5 of 5 Claude encoders.
  - C's principle text without KC's parse gave (a)-like verdicts, splitting only at R21.
  - An explicit "denials contribute no items" packet gave two different coupling-family vectors (10/5 and 9/4).
  - Every encoder read R80 differently from T3's row-specific "none".
- **Cannot establish:**
  - which convention is correct (that is normative);
  - population reliability (n = 2–3; no sampling model);
  - cross-family validity (Λ7);
  - the determinacy of (c) **with** a named parse, or of T3 **with** its enumerated list. Neither was tested.
- **What the experiments mainly show:** the outputs are **sensitive to stipulations**. They do not show that one interpretation is better than another.
- **The external protocol (`external-protocol/`) targets exactly these gaps.** It tests T3-with-list, SH and (c)+K1c-parse at equal specificity, with human or non-Claude encoders.
  - **Design:** n ≥ 5 per arm, 10 recommended, from at least 3 encoder clusters.
  - **Decision rule, fixed in advance:** an arm is REPRODUCED if at least ⌈0.8n⌉ encoders agree on each guard verdict.
  - **Statistics:** no significance tests.
  - **Freezing:** by hash and commit, before any encoding.
  - **Remaining limits:**
    - the texts are still Claude-authored;
    - the parse is written in general form by an analyst;
    - it covers 11 rows of one corpus.

---

## 5. KC-1 alternatives in plain language (D)

| option | meaning | supporting evidence | evidence against / uncertainty | Rq1 (text alone fixes the answer) | Rq7c (crossing only through a coupling constraint) | E3-REL | changes to accepted artifacts | triggers | reversibility |
|---|---|---|---|---|---|---|---|---|---|
| **(a) T3** | A guard row "touches" both modules if the tie it rules out links them. Counts what a rule is *about*, including what it forbids | T3 rule 1 verbatim; T3's own table; every blind (a) encoder agreed; reference recomputation | Needs T3's enumerated list (or a T2 label) plus a positive scope clause; the coupling-proxy rationale argues against it | ~: deterministic given the list | fails | 13/8 | none; the F-LOG-0297 E3-REL box stands | KC-4 (recording) | cheap: no records change |
| **(b) SH, "read nothing"** | A rule that only forbids a link touches nothing | Coupling rationale (VP14 proxy, T2 "non-constraint") | Contradicts T3 rule 1's text; the blind "denials contribute nothing" packet did not reproduce SH (0/2); D1 needs C's content sense of "reads" | ~: one uniform rule; blind evidence weak | passes | 9/4 | new E3-REL acceptance; six host cells → none | KC-2, KC-3, KC-4 | moderate: several records restated |
| **(c) + named parse** (K1c, R15ref / C-Σ, R15ref-R14) | Count only what the rule asserts, with an exact rule for which parts count | KC/MKC analysis; C-Σ fully specified | The parse is an analyst stipulation; the text without the parse reads as (a); untested blind | ✗ without the parse; with the parse, untested | passes | 9/4, 10/5 or 11/6 | as (b), plus the parse in T3 | KC-2, KC-3, KC-4 (+KC-5 under K rest) | moderate to high |
| **Branch S (split)** | Keep the host lists as they are; define E3-REL as a separate coupling measure | Serves both purposes explicitly; no host change | Amends E3:111 (a definition change); still needs a parse | as (c) | passes for E3-REL | 9/4–11/6 by parse | amend E3:111; new acceptance | KC-3 + E3:111 amendment, KC-4 | moderate; adds a second concept |
| **Defer** (current, F-LOG-0309) | Keep everything as it is; gather the external evidence | Coherent state (audit §14.F) | Blocks KC-2–KC-5 and E4 | — | — | 13/8 stays accepted under (α) | none | none | fully reversible |

- **Rq1 is an [E] stipulation.** Rq7c is [E-s] (MKC:172). Neither one binds unless the human decides it does.
- No weights or scores are used here, because no justified decision model exists.

---

## 6. Recommendation to the human ARB (E) [REC]

1. **Sufficiency.** The current evidence is **ready only after a specified, bounded validation**. The arithmetic and the decision structure are settled. Two questions remain open, and both bear on which branch is responsible:
   - external reproducibility of the guard verdicts;
   - the determinacy of (c) with its parse.
2. **Smallest next step.** Ratify (or amend) and freeze the external protocol, then run it with human or non-Claude encoders. That means at least 5 per arm and 3 clusters, with T3-with-list, SH and (c)+K1c as mandatory arms.
3. **What could change the recommendation:**
   - If **T3 is REPRODUCED and (c)+parse is INDETERMINATE**, evidence of determinacy favours (a), conditional on Rq1.
   - If **(c)+parse is REPRODUCED**, both families are text-determinate. KC-1 then reduces cleanly to the normative purpose question: Rq1 against Rq7c.
   - If **T3 is not reproduced**, T3's list-plus-clause needs re-examination before ratification.
4. **Stopping rule.** One external replication round. Whatever its outcome, the human decides KC-1 afterwards. No further replication unless the round is INCONCLUSIVE (n ≤ 3 per arm) or FAMILY-DEPENDENT. **The normative choice (Rq1 against Rq7c, hosting against coupling) cannot be settled by more data.**
5. **If the human prefers to decide without external validation:** (a), (b), (c)+parse and S are all defensible **under their declared requirement**. Choose by the requirement the theory must meet, never by the number it produces.

---

## 7. Minimum next steps after the KC-1 decision

1. Record the act. For T3, add the positive scope clause, and state K18 = statement cell and the K2/R12 caveat.
2. Run only the triggered acts: KC-4 → KC-2 → KC-3 → KC-5.
3. Independently review the act and every recomputed record.
4. Then, and only then, a separate E4/R8 authorization package.

---

## 8. Remaining backlog by dependency [REC]

| item | blocks KC-1? | blocks E4/R8? | blocks E5? | note |
|---|---|---|---|---|
| External replication (Λ7) | no (informs it) | recommended before | no | protocol ready; human act needed |
| Audit r2.1 defects 1–9 (§3) | no | yes (records must be correct) | — | small text fixes; need authorization |
| KC-1 + triggered KC-2–KC-5 | — | **yes** | yes | F-LOG-0300: "E4 follows on a settled basis" |
| C6 (freeze encoding or downgrade the lists to "report") | no | **yes**: canonical host lists need a frozen encoding or the "report" status | yes | decide after KC-1 |
| K18 (source of ρ) | partly (scope proviso in the act) | yes | — | state K18 = statement in KC-1, or decide separately |
| Spanning rule (F-LOG-0271) | no | **yes**: P-c and E3-REL differ under S | yes | the canonical model needs one rule, or must carry all three |
| D12 | no | yes (E3-REL ± R24) | — | small |
| C-γ | no (kept separate) | yes (grouping labels) | — | entangled with K1; decide after KC-1 |
| R11 (§1b grouping guard list), (g), (h), H13, A1, A5, CV9/§3b | no | review each for E4 relevance | — | some may be documented as open limits in R8 |
| K15–K17, K19 | no | no (shown verdict-neutral or S″-only) | — | document as bounded |
| Bounded-context claim | no | E4 must keep "module cut" ≠ bounded context | **yes**: E5 needs domain evidence beyond a low-crossing cut | — |
| E5 codebase / owning context | — | — | — | after R8 adoption |

---

## 9. Readiness

- **E4/R8: not ready.** [EST] Blocked by:
  - KC-1 and its triggered acts;
  - the C6 decision;
  - a single spanning rule (or an explicit carriage of S, S′ and S″);
  - correct records (defects 1–9).
  
  Canonicalizing now would freeze an unratified counting method into the canonical theory.
- **E5: not ready.** [EST] It needs an adopted R8 and a conformance criterion. The P-c module cut is not evidence of bounded contexts; that needs domain evidence beyond a low-crossing cut.
