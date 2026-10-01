# Review of SCHEMA-r3 consolidation (r3c)

**Files used (all in this directory):** REVIEW-TASK.md, TASK.md, SCHEMA-r3.md, SCHEMA-r3-CODER.md, EQUIVALENCE.md, KNOWN-AMBIGUITIES.md, CHANGELOG-r3c.md, REVIEW-r2.md, RECORDS.json, SOURCES.md.

**Not in the bundle:** THEORY-CHOICES.md, SCHEMA-r2.md, CHANGELOG-r2.md and the pre-r3c SCHEMA-r3. So I could not check three things directly:
- the TC-1 1c erratum against the file it amends;
- EQUIVALENCE rows 27 and 110, which rest on what SCHEMA-r2 says;
- whether r3c made any edits that it did not tag. I relied on the struck-through r2 text in SCHEMA-r3.md.

I also had no commands available, so every word count below is a hand estimate.

## Verdict: **FAIL**

There are two blocking issues. Both are small to fix:

1. **Non-equivalence (B1).** The coder text drops the rule that a proposal must be for X **on the target**.
2. **Contamination and anchoring (B2).** One worked example is built from source wording taken from the very section that two records come from (R-60). Several other examples are near-copies of specific records' expected answers.

Everything else passes or is non-blocking:
- All eight drafting fixes are done, with no substantive change beyond them.
- KNOWN-AMBIGUITIES now has every item REVIEW-r2 found missing.
- The coder text says nothing about hypotheses, earlier codings, the reliability study or expected answers.

If B1 and B2 are fixed, I expect **PASS_WITH_LIMITATIONS**.

---

## 1. Drafting fixes

| Item | Fixed? | Beyond the item? |
|---|---|---|
| SS: shared-span ground acts (R06/R07) | **Yes.** §1 sub-bullet, R-A5 step 1 exception and R-S1 now agree: *rule* gets its own record and ground_candidates stays empty on both. | No. It removes only the literal step-1 reading, which was the point of the item. "Taken" stays undefined when there are more acts than records, and B14 discloses this. |
| PR: PROVENANCE vs PV | **Yes.** PROVENANCE is removed from the ground-label words. A label whose first word is PROVENANCE is not a ground label but still ends a segment. | No. "Ends a segment" is already what r2's "next label that ends in a colon" says. |
| MR: mechanism response needs a stated non-effect; intended failures | **Yes.** "returns nothing" is removed. Empty or finding results are excluded. Intended failures are not non-effect markers. | No. It changes only rule tests outside RECORDS (the R-83 grep, the RED commit). But see §5 on R11. |
| S2: make step 2 conditional | **Yes.** It now matches step 4 and the R18/R20 answers. | No. |
| TC1c: align the 1c table and recipe | **Fixed by a binding erratum** (CHANGELOG-r3c §2, additive). THEORY-CHOICES.md itself was not changed (TASK does not list it as an output). KA B13 discloses this. The file is not in the bundle, so I could not check it. | No. |
| GC: `act.ground_candidates` | **Yes.** It is defined, required on every record, and consistent with the shared-span exception. It records no state. | No. |
| NW: normative word of a class label | **Yes.** It is the label noun phrase, not the copula, preposition or colon. | No. CHANGELOG checks every current class label: token false either way. |
| R03 `mechanism_refusal` | **Yes:** false. "NOT ADOPTED" is D5 (i) and comes from a named body. It is recorded in CHANGELOG §3 and KA A4. | No. |

## 2. Equivalence (EQUIVALENCE.md checked row by row, 1–164 plus §7–§8)

Most rows hold. These do not:

- **Rows 43, 44, 117: wrongly marked "Same". BLOCKING.**
  - SCHEMA-r3 D4 says a proposal states that performing X **on the target** was put forward. The rank-1 cell says "a PROPOSAL or attempt **for X**". R-A2 requires a refusal expression "that disposes of **that** proposal".
  - The coder text keeps none of these. D4 says "doing X was put forward", and the rank-1 cell says "if a proposal or attempt is identified". The tie to the proposal survives only in D5 (ii), for negations, and not for D5 (i) refusal verbs.
  - *Failure case:* the section says "Motion: supersede ADR-Q1" and the span says "The Board declines to supersede ADR-Q2". SCHEMA-r3 gives UNKNOWN (no proposal for this target). The coder text gives ACT_REFUSED.
  - I found no current record whose value changes, but the rule as written is different.
  - *Fix:* restore "on the target" in D4, "for X" in rank 1, and "that disposes of that proposal" in D5 (i) or rank 1.
- **Row 46: a qualifier is dropped. Non-blocking.**
  - SCHEMA-r3 codes a reason against an unchosen option NONE only "with no refusal expression". The A3 note in the coder text says flatly "A reason against an unchosen option is NONE".
  - The rank-1 table still catches a refusal verb in the same span, so I read this as harmless. Even so, the qualifier should go back.
- **Row 127: compressed. Non-blocking.**
  - SCHEMA-r3 says explicitly that "the intended failure is not a non-effect marker" and that the run is coded "rank 3 if it occurred". The coder text has only "whose run is coded like any act". The "Intended failure" example covers the gap.
- **§7 "none adds a rule": not true. Non-blocking.**
  - The "Shared" example says "Ra *close*, Rb *merge* (**by lineage**)". This brings in a rule for assigning acts to records that SCHEMA-r3 does not state. SCHEMA-r3 only labels `v1_event_ids` as "lineage".
  - The rule also cannot be applied here, because RECORDS.json has no `v1_event_ids`.
  - It is assignment-only (KA A5). Remove it or state it in both texts.
- **Row 138 and §7 "every span is invented": not true.** Covered under contamination, §3.
- **Minor compressions.** They change nothing on any plausible input:
  - "guard" is dropped from D8's facts sentence;
  - the "no RED begins" form is dropped from D8 (ii);
  - D4's "question … of whether to do X" is shortened.
- **Rows 27, 110 and §8 word count: not verifiable here.**
  - Rows 27 and 110 need SCHEMA-r2, which is not in the bundle.
  - The coder text is about **3,600 words** by my hand estimate (range about 3,400–3,800). EQUIVALENCE says about 3,400. The 3,500 target may be slightly exceeded. Non-blocking.

Nothing else is added, dropped or changed in meaning. The rule order, the step-4 tie-breaks, the gate, the shared-span exception, the D15 closed list and the N1–N7 order all match.

## 3. Contamination

- **Mentions: clean.** There is no reference to hypotheses, earlier or expected codings, the reliability study, THEORY-CHOICES, records or revisions.
- **RECORDS.json spans: none used verbatim.**
- **Source-derived material inside the "invented" examples. BLOCKING, together with the next point.**
  - The class-label example is "The panel ruled the memo was a **planning act**, not a **ruling**."
    - "a PLANNING act" is one of the five source labels that EQUIVALENCE row 138 says were removed. It comes from the R-60 annotation ("Opening/approving a work package is a PLANNING act").
    - "a ruling" is the class label of **R09** ("filed as a ruling"), which is in the same section, R-60.
    - The sentence also copies the structure of R06/R07: "ruled it was operational acceptance rather than a constitutional decision" → `status: <label>`, token false.
  - "RULING AS ISSUED, VERBATIM" is R-86's wording, the section of R02. It is used in the "Reproduced" span, and "(Chief Engineer instruction)" copies R23's "(Principal Architect instruction)".
- **Anchoring: several examples are one-to-one templates of particular records' answers.**
  - **R12:** "D MAY BE REVIEWED AND MUST NOT BE SENT" has the same all-caps syntax and the same result as "WP-8 MAY BE PLANNED AND MUST NOT BE STARTED".
  - **R01:** "Gated negative": a register row, "declines to <unlisted verb>", no proposal → NONE / OUTSIDE, ext UNKNOWN rank 1. This is REVIEW-r2's R01 answer.
  - **R05:** "RELEASE 4 AUTHORIZED — Chief Steward (PREPARED)" and "ORCA AUTHORIZED TO MIGRATE AND DECOMMISSION" map onto R05's "AUTHORIZED TO PLAN AND IMPLEMENT — ARB CHIEF (PREPARED)".
  - **R26:** "ADR-Q2 is not superseded" matches "ADR-T14 is not superseded".
  - **R06/R07:** "T-5 was closed because …".
  - **R18/R20:** "as already recorded in Charter §4" matches "already ARB-adopted in Handover §9" and "documented in PB-004 retro §9".
  - **R10:** "Rollout … does not begin".
  - **R22:** "P-2 forbids approving …".
  - **R01:** "No contractor may approve …" matches "no self-issued act may adopt".
  - **R15–R21:** GRADUATE / "Stays experimental".

  Most of these records are determinate by rule, so the example only teaches the rule. **Two examples lean on open ambiguities:**
  - "Gated: [## R-420] Dataset Q is classified as restricted" (register row, present passive, "as <NP>") pushes R09 toward reading (a) of KA A13.
  - "(by lineage)" pushes the KA A5 assignment.

  TASK item 3.5 asks for invented spans "to avoid anchoring the coders". These examples are invented in their words but not in their structure.
- **Fix:**
  - Replace the class-label example with a neutral one, e.g. "The auditor found the note was a status report, not a decision".
  - Remove "RULING AS ISSUED, VERBATIM" and the "(… instruction)" pattern from §5.
  - Break the surface templates for R12, R05 and R01: change capitalization, syntax and wording, but keep the decision path.
  - Drop "(by lineage)".

## 4. Codability

**Residual risk: MEDIUM-HIGH**, down from HIGH.
- The markup-free text and one example per path help a lot.
- **What still makes it risky:**
  - The text is dense and compressed and full of cross-references ("A2 (b)", "OUTSIDE_RA3", "G2").
  - There are about 25 fields per record, plus four nested lists.
  - About 32 ambiguities are disclosed (16 in Part A, 16 in Part B).
- **A fresh coder cannot do these from SCHEMA-r3-CODER.md plus RECORDS.json alone:**
  - (a) Section text, the matrix header (D15 (iii) "Evidence" column), proposals (D4) and `later_disposition` all need SOURCES.md.
  - (b) `source` (file + blob) and `v1_event_ids` are not in RECORDS. So the R06/R07 assignment is arbitrary.
  - (c) The "v1 operation list" that `operation` should prefer is not given.
  - (d) No rule covers the three "NO SOURCE SPAN AVAILABLE" records (R24, R28, R35). Rank 4 NONE and G2 UNKNOWN both fit, and SOURCES.md says "UNCLEAR", which is not a schema value.
  - (e) The value-level ambiguities that are not repaired remain: R03, R13, R14, R22, R33, R08, R11.
- With SOURCES.md available, all 36 records can be coded, but only with the disclosed spread.

## 5. KNOWN-AMBIGUITIES

**OK on the r2 review's missing items.**
- Part D covers REVIEW-r2 §4 B.1–B.6, each with its status and residual.
- The R15–R17 header is corrected, and R19/R21/R18/R20 are qualified.
- All 36 records are accounted for: 17 determinate, 2 + 3 qualified, the rest in Part A.
- New rows B12–B16 cover what the r3c fixes left open.

**Two inaccuracies, non-blocking:**
1. **B5 says "No RECORDS span (R11 contains no test output)".**
   - That is true of R11's span. But D6 reads "span or section", and R11's section (git-6a67da5d7) contains "4 failed" and "Interface … not found" at a RED boundary.
   - CHANGELOG-r3c §3 itself names this commit.
   - So R11 is exposed to B5 (b) and (c): FAILED vs OCCURRED, and whether `mechanism_refusal` is required. A14 does not list the ATTEMPTED/FAILED reading.
2. **R24, R28 and R35 are listed as determinate, but no rule decides what to code when no span exists** (see §4 d). This should be added as an item.

## Blocking
1. **B1.** The coder text drops "on the target" (D4), "for X" (rank 1) and "disposes of that proposal" (R-A2). This is non-equivalent: ACT_REFUSED vs UNKNOWN on constructible inputs. EQUIVALENCE rows 43, 44 and 117 wrongly say "Same".
2. **B2.** The §5 examples are not fully invented and they anchor coders:
   - the class label "a planning act, not a ruling" is R-60 source wording, from the section of R08/R09, and mirrors R06/R07;
   - "RULING AS ISSUED, VERBATIM" is R-86 wording;
   - there are one-to-one templates of R12, R05 and R01;
   - "Gated" and "(by lineage)" lean on open items A13 and A5.

## Non-blocking
1. Row 46: restore "with no refusal expression".
2. Row 127: restore "the intended failure is not a non-effect marker; rank 3 if it occurred".
3. "(by lineage)" adds an assignment rule that does not exist and cannot be applied (no `v1_event_ids`). Remove it or state it.
4. Coder word count is about 3,600 by hand estimate, possibly over the 3,500 target. EQUIVALENCE's figure of about 3,400 is also unverified.
5. EQUIVALENCE rows 27 and 110 and the TC-1 erratum cannot be checked in this bundle (SCHEMA-r2 and THEORY-CHOICES are absent).
6. KA B5: R11's section has test-failure output, so R11 is exposed to B5 (b) and (c).
7. KA: R24, R28 and R35 (no span) are listed as determinate without a deciding rule. Add a rule or an item.
8. Codability: the coder text does not give the v1 operation list, the section texts or the lineage. Ship SOURCES.md and say in the coder text that it is the section source.
9. Minor compressions: "guard", "no RED begins", D4 question wording.
