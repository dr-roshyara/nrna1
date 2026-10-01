# Targeted re-review of SCHEMA-r3 r1

Files used: REVIEW-TASK.md, CHANGELOG-r1.md, SCHEMA-r3.md (r1), THEORY-CHOICES.md, RATIONALE.md, REVIEW-r0.md / .json, SCHEMA-r2.md, GAPS.md, RECORDS.json, SOURCES.md, HYPOTHESES.md, TASK.md.

## Verdict: **PASS_WITH_LIMITATIONS**

- **B1 is fixed.** Positive and negative self-performance now go through the same scope gate (R-A3 (b+)/(b−), R-A5 step 2). Both are tagged with the same basis value. TC-1 describes the negative case correctly.
- **B2 is fixed.** R01 now has a single answer.
- **Minimality:** all six reverts are done or moved to THEORY-CHOICES.
- **Conflicts:** all 8 are resolved, C2 with a small residual.
- **Neutrality:** I found no rule that builds H-D or H-B into the default observations.

There are no blocking issues. The verdict is not a plain PASS for four reasons:
- Two G1 records (R18, R20) can still get two different act values, through a new interaction with C8.
- A drafting slip in rank 3 means that, read literally, the TC-1 1c tag (`state_if_extended`) cannot be computed for *positive* gated acts.
- Two theory-choice computations (TC-2 2b and 2c) cannot be fully computed from the tags.
- The new r1 text adds a few small internal inconsistencies (§7).

---

## 1. B1: symmetry of self-performed dispositions

The gate (R-A5 step 2, SCHEMA-r3.md:79) applies before ranking. It applies to every SELF-PERFORMANCE whatever its polarity, and REPORTED acts are never gated. The scope list (R-A3 (b+)/(b−), :57–66) is the same five lemmas for both directions. D3 now defines self-performance by textual form alone (:150).

| Case | Section / basis | Result | Symmetric with the positive counterpart? |
|---|---|---|---|
| **R01** "The Chief declines to resolve …" | `## R-81` register row; present-tense refusal verb, so a self-performance | X = *resolve* (the complement), which is not listed, so (b−) fails. Gated: NONE / `SELF_PERFORMED_OUTSIDE_RA3`, `state_if_extended = UNKNOWN` | Yes. An unlisted positive act ("resolves …") would also be NONE / OUTSIDE |
| **R26** "ADR-T14 is not superseded." | `## R-77`; reproduced operative text with no other source named (D3 N6), so a self-performance; `attributed_to` = the Board | X = *supersede*, which is listed, so (b−) holds. The proposal ("a supersession question", option text) is stated, so rank 1 gives **ACT_REFUSED** | Yes. "ADR-T14 is superseded" would be ACT_OCCURRED. The norm is PERFORMATIVE_ONLY either way (D8 (iii)) |
| **R06/R07** "Then R-90 itself withdrawn …" | Session log; narrative participle, so REPORTED (D3 (i)) | Not gated: **ACT_WITHDRAWN** *withdraw* R-90. The proposal is stated in the section. The second record is ACT_OCCURRED *rule* (REPORTED) | Yes. Reported acts of either polarity are acts in any section |
| **R15–R21** (matrix rows) | Not a register row (D1) | Every self-performed classify/recommend is gated. None of the rows contains a self-performed negative disposition: "Stays in", "Remains Candidate" are continuative, and "did NOT reuse" is in the evidence column | Yes, but see §5 (R18, R20) |
| R-36 "Promoted into AST-013 …" / "**Expressly NOT promoted:** …" | `## R-36`; both are self-performances; *promote* is not listed | Both NONE / OUTSIDE. `state_if_extended` is ACT_OCCURRED for the first, and ACT_REFUSED **or** UNKNOWN for the second (see below) | Yes in `act.state` |
| R-90 "it is deliberately not promoted to methodology" | `## R-90` | A bare negation with no stated proposal is not a disposition (D5 (ii)). So it is not a self-performance: NONE, `basis = NONE` | Yes. The asymmetry comes from r2's R-A2, not from the scope gate |
| R22 "NOT promoted to methodology — …" (S0815) | Session log | A bare negation with no deciding verb and no register row is not a disposition: NONE / NONE. This holds whether the participle is read as a headline or as narration | Yes |

TC-1 (THEORY-CHOICES.md:13–26) now states the negative case for 1a, 1b and 1c, and adds 1d. The choice between the two symmetric options is disclosed and argued from minimal change to r2 (CHANGELOG §1, RATIONALE G1). **B1 fixed.**

Residuals (not blocking):
- **Rank 3 drafting slip** (:86). Rank 3 reads "REPORTED, or R-A3 (a)–(c) hold". `state_if_extended` is defined as the result of step 3 "if the gate were not applied". For a *positive* gated act, R-A3 (b) still fails, so the literal result is rank 4 NONE. NONE is not an allowed value. For a negative gated act, rank 1 has no R-A3 condition and yields a value. Read literally, TC-1 1c therefore re-creates the r0 asymmetry inside the alternative computation. The intended value (ACT_OCCURRED) is clear from the Symmetry paragraph, TC-1 1c and the CHANGELOG, and default `act.state` is unaffected. Fix: in rank 3, "or a SELF-PERFORMANCE (D3)", with the gate alone enforcing R-A3.
- **R-36 "Expressly NOT promoted".** `state_if_extended` is ACT_REFUSED or UNKNOWN depending on whether the R-36 row itself states a proposal. "Promotion Review" and "candidates" are in the row, but the matrix is in another section. The CHANGELOG itself gives both values. This is a tag-only ambiguity.
- **Withdrawals and reversals of named rulings never fall in scope.** When the complement is a noun, X is *withdraw* or *reverse*, which are never listed (:78). So a register row's own "R-90 … WITHDRAWN" is gated, while a log's report of the same withdrawal (R06/R07) is an act. This is consistent with the closed list and visible through `state_if_extended`, but TC-1 could say so.

## 2. B2: R01

Single answer:
- **ACT:** `state = NONE`; `basis = SELF_PERFORMED_OUTSIDE_RA3`; operation *resolve*; target = "an ambiguity about the Chief's own authority" (in the Chief's own favour); actor = the Chief; `state_if_extended = UNKNOWN`. The refusal verb is explicit (D5 (i)), and no proposal to resolve is stated in the span or in the R-81 section (§3, R-A2).
- **NORM:** PROHIBITED, `basis = OPERATOR` ("no self-issued act may adopt", D7 NEG); `about_operation = adopt`; `about_actor` = a self-issued act; `about_target` = that reading; `performative_token = false`; `other_clauses` and `indicative` empty.

The only residue is the `cited_as_ground` tag. The clause after the dash works as the reason for the refusal, but it carries none of the listed markers. The schema does not say whether the marker list (:39) is closed. If it is closed, the value is false. **B2 fixed.**

## 3. Minimality

| Item | Status |
|---|---|
| M1 D7 lexicon | Reverted to prohibition operators plus the R01 negation rule. Minor: the eligibility words kept in D12 and the extra NEG forms ("nothing may", "never may") go slightly beyond what G2 and R01 strictly need. Both are disclosed |
| M2 R-X1 | Reverted to two prohibitions |
| M3 R-A5 | Narrowed to the G3/G4 competitions plus the gate. ACT_REVERSED and reported ACT_OCCURRED have their r2 meaning again. One residue: the rank-1 refusal cell says "if a PROPOSAL … is identified", while R-A2 allows "proposal **or attempt**". That is an added condition, despite :73 ("adds no condition") |
| M4 D11 | Limited to verdict spans. TC-5 added |
| M5 D8 (ii) | Narrowed to non-commencement statements. TC-6 and `norm.indicative` added |
| M6 R-S1 count | Removed |

**minimality_ok = true.**

## 4. Conflicts

| Id | Resolved? | Note |
|---|---|---|
| C1 D3 circularity | Yes | Self-performance is defined by form. R-A3 (c) refers to it, and the basis values refer to R-A3 (a)/(b) |
| C2 D11 / R-N1 / D8 (i) | Yes for R06/R07; small residual | It depends on R22 **not** being a D12 verdict span. D12 lists "NOT promoted" as a verdict form. If R22 ("NOT promoted to methodology") is read as a verdict, D11 makes "ES-006.1 forbids promoting from one" MENTIONED. That contradicts R-N1's example and D11's own "So …" sentence (:216) |
| C3 D12 vs no re-levelling | Yes | R19–R21 are STATUS_CONSTRAINT on the behaviour. Reading 2c is listed (but see §6) |
| C4 R-A1 vs R-A2 | Yes | The new rank-1 wording ("proposal" only) re-creates a smaller version (§7) |
| C5 D4/D6 | Yes | Explicit (operation, target) split |
| C6 merge vs closed list | Yes | A listed lemma is never replaced |
| C7 NORM tie | Yes | R12 → PERMITTED *plan*; R34 → AUTHORIZED (bare), with PERMITTED *begin RED* in `other_clauses` |
| C8 operation tie / "highest" | Yes | Rank 1 is highest; same rank goes to span order. Two side effects: (a) "gated ranks below rank 3" lets a REPORTED subsidiary act take a G1 record's act field (§5, R18/R20); (b) the order among gated candidates "by `state_if_extended`" is not stated (e.g. where UNKNOWN sits) |

## 5. G1–G6 re-tests

| Gap | Record | r1 answer | Determinate? |
|---|---|---|---|
| G1 | R15, R16, R17 | NONE, OUTSIDE, *classify* (the category letter comes first in the span), `SPLIT_REQUIRED: recommend`, `state_if_extended = ACT_OCCURRED` (intended; see the rank-3 slip) | Yes |
| G1 | R18 | As R15, **if** "already ARB-adopted in Handover §9" is a ground (R-A5 step 1). Otherwise it is a REPORTED adoption (D3 (ii): another document is named), not gated, at rank 3. It then outranks the gated candidates (C8): **ACT_OCCURRED *adopt***, `SPLIT_REQUIRED: classify` | **No** |
| G1 | R19 | NONE, OUTSIDE, *classify* (category C). `SPLIT_REQUIRED: recommend` is missing from the CHANGELOG | Yes |
| G1 | R20 | CHANGELOG: NONE / *classify*. Literal rules: "documented in PB-004 retro §9 + Handover §8" names other documents as where the act was constituted (REPORTED). It is not in the evidence column and is not a ground, so it is a rank-3 candidate that beats the gated *classify*: **ACT_OCCURRED *document***, REPORTED | **No** |
| G1 | R21 | NONE, OUTSIDE, *classify* (category D). "explicitly ruled" is evidence and excluded. "replaced only on repeated evidence" goes in `norm.indicative` | Yes |
| G1 | R27 | NONE, OUTSIDE, *apply* §12. `state_if_extended = ACT_ATTEMPTED` / PREPARED (R-83 section). `later_disposition` = R-86. "rather than superseding" is a contrastive. Norm NONE; indicative "it APPLIES §12" | Yes |
| G2 | R15–R18 | ELIGIBLE, VERDICT, *promote*, target = the row's behaviour. The category letter A is in `other_clauses` | Yes |
| G2 | R19 | STATUS_CONSTRAINT, STATUS_PREDICATE, `status: category C`, target Registration ≠ Delivery. D11 no longer applies here, so "→ D" and the evidence column's "permanent design principle" also belong in `other_clauses`; the CHANGELOG list leaves them out | Yes |
| G2 | R20, R21 | STATUS_CONSTRAINT, `status: category D` | Yes |
| G3 | R01 | See §2 | Yes |
| G3 | R26 | ACT_REFUSED *supersede* ADR-T14, SELF_PERFORMED, `attributed_to` = the Board. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, `performative_token = true` | Yes |
| G3 | R29 | Act NONE / NONE; norm NONE ("would" is a modal) | Yes |
| G4 | R05 | ACT_ATTEMPTED / PREPARED, *authorize* WP-4C-1, SELF_PERFORMED. Norm AUTHORIZED, OPERATOR, `plan + implement`, `performative_token = true` (not bare), `stated_by = ARB CHIEF (PREPARED)` | Yes |
| G5 | R10 | Act NONE / NONE. Norm STATUS_CONSTRAINT, STATUS_PREDICATE (D8 (ii)), *begin implementation*, WP-4B | Yes |
| G5 | R23 | ACT_OCCURRED *adopt*, SELF_PERFORMED (decided by the D3 example), `attributed_to` = Principal Architect. Norm STATUS_CONSTRAINT, `status: permanent documentation standard`. "adopted" is in `other_clauses` | Yes |
| G6 | R02 | ACT_OCCURRED *adopt* R-81..R-85, SELF_PERFORMED, `attributed_to` = Decision Authority. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, *adopt*, `performative_token = true` | Yes |
| G6 | R30 | ACT_OCCURRED *adopt*, target ANNOTATE. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, `about_operation = adopt`, `about_target = ANNOTATE` (D13 (iii)) | Yes |
| G6 | R06/R07 | Acts: ACT_WITHDRAWN *withdraw* R-90 (REPORTED) and ACT_OCCURRED *rule* R-90 (REPORTED); which record_id gets which follows lineage. Norm: present, identical on both, STATUS_CONSTRAINT, `cited_as_ground = true`, `shared_with` cross-listed | Value yes. **Primary clause not determinate** |
| test | R12 / R34 | See C7 | Yes |

**Still ambiguous:**
1. **R20 act:** NONE / *classify* (CHANGELOG) vs ACT_OCCURRED *document* (REPORTED, by the literal rules).
2. **R18 act:** NONE / *classify* vs ACT_OCCURRED *adopt* (REPORTED). It turns on whether "already ARB-adopted in Handover §9" is a ground; R-A5 step 1's ground list is "e.g.".
3. **R06/R07 primary norm clause.** "it was operational acceptance rather than a constitutional decision" is a class label on R-90 and comes first in the span. If it counts as a D8 (i) status predicate, it is primary ahead of "the register holds constitutional decisions". The value (STATUS_CONSTRAINT) is the same either way, but `about_target` and `about_operation` differ.
4. **R-36 "Expressly NOT promoted", `state_if_extended`:** ACT_REFUSED vs UNKNOWN (tag only).
5. **R22 norm:** PROHIBITED *promote* (not a verdict span) vs INELIGIBLE *promote* (verdict span, with the ground MENTIONED). This is the C2 residual.
6. **R01 `cited_as_ground`:** false if the marker list is closed (tag only).
7. **`state_if_extended` for positive gated acts (R15–R21, R27-type):** read literally, rank 3 gives no allowed value. The intended value is ACT_OCCURRED.

## 6. Neutrality and theory choices

**Leaks into the default observations: none found.**
- `act.state` treats both polarities alike.
- Literal attachment, span-order tie-breaks and source-marked PREPARED status are observable and neutral in direction.
- TC-5 and TC-6 now list the D11 and D8 (ii) choices r0 had built in.

Non-blocking neutrality notes:
- **N-a.** The rank-3 slip (§1) would make TC-1 1c asymmetric if applied literally.
- **N-b.** After C3, only the *positive* matrix rows carry verdicts, so D11 skips evidence-column content only on positive rows. R17's "deferred, NOT skipped" is MENTIONED, while R19's "permanent design principle" is collected. This affects only `other_clauses` and TC-5 counts, and the effect is small.
- **N-c.** D7 makes imperative "do not …" a prohibition operator, while D12 lists "do not promote" as a verdict. OPERATOR wins, so a negative recommendation becomes PROHIBITED *promote*, and D12's example is unreachable. INELIGIBLE then needs "NOT promoted" or "not eligible". No gap record is affected (matrix row 7, "do not duplicate it", is).
- **N-d.** D6 FAILED vs rank 1 (disclosed in CHANGELOG §7). An attempt refused by a mechanism ("refused: unknown transition type 'CLOSE'", S0815) reaches rank 1. Because rank 1 needs a *proposal*, the result is UNKNOWN, not ACT_ATTEMPTED / FAILED. This drops work-failure observations, which are H-B material, from support counts. It is narrow and disclosed, but should be fixed before coding logs.

**Theory choices: listed, but not all computable from the tags.**
- **Listed.** Every choice the r0 review found missing is now listed: TC-1's negative case, TC-5 and TC-6.
- **TC-1 1c** cannot be computed for a gated candidate that loses on its record (e.g. *classify* on R18/R20 under the literal reading). The loser exists only as a `SPLIT_REQUIRED` note, with no `state_if_extended`.
- **TC-2 2c's recipe** (THEORY-CHOICES.md:42) recodes every STATUS_PREDICATE "primary clause or in `other_clauses`" as INELIGIBLE. That would flip R15–R18's category "A" (a promote outcome), and it needs a mapping from label to polarity (A/B vs C/D/already) that no tag carries.
- **TC-2 2b** still has no join key (disclosed as not repaired).
- TC-3, TC-4 (with 4b/4c), TC-5 and TC-6 are computable.

**theory_choices_complete = false** (not blocking).

## 7. Regression: new conflicts in r1 text

1. **Rank 3 vs the `state_if_extended` definition.** See §1.
2. **Rank 1 vs R-A2.** The rank-1 refusal cell needs a PROPOSAL, while R-A2 needs a "proposal or attempt". As a result, a refused attempt with no proposal is UNKNOWN, which feeds N-d.
3. **C8 "gated below rank 3" vs the CHANGELOG's own G1 results** (R18, R20).
4. **`performative_token` (:38).** The field is true when the token performs "the record's **R-A3** act", and the definition also says it "covers every PERFORMATIVE_ONLY clause". These disagree for PERFORMATIVE_ONLY clauses on records with no R-A3 act: R18's "already ARB-adopted", R03's "NOT ADOPTED", reported adoptions. This affects TC-4 4c.
5. **Pre-existing, newly found:** D7/D12 "do not promote" (N-c). Also, D2 requires that the performative verb *name* the act, but D3's examples treat category letters and an imperative "PROMOTE" as performing *classify* / *recommend*. The examples decide the answer, but D2 should say so.
6. **Headline vs narrative participles in summaries and logs.** Cases are "duplication refused" (R-36) and "Adoption registered" (S0815). The REPORTED vs self-performed boundary is not decidable for them, so the same text can be gated (NONE) or not gated (ACT_OCCURRED / UNKNOWN). This applies to both polarities equally, so it is not a symmetry leak.

## Blocking

None.

## Nonblocking (fix next round)

1. Rank 3: replace "R-A3 (a)–(c) hold" with "a SELF-PERFORMANCE (D3)", so that the gate alone enforces R-A3 and `state_if_extended` is defined for positive gated acts.
2. R-A5 step 1 / C8: decide whether REPORTED acts mentioned only as provenance or location ("documented in …", "already ARB-adopted in …") are candidates. Also close the ground-marker list. This settles R18 and R20.
3. Make the rank-1 refusal cell say "proposal or attempt" (R-A2), and settle D6 FAILED vs rank 1 for mechanism refusals.
4. Record `state_if_extended` for gated candidates that lose under step 4 (not only in `SPLIT_REQUIRED`), so TC-1 1c can be computed.
5. Fix TC-2 2c's recipe: primary STATUS_PREDICATE only, records with no VERDICT clause, and a stated label set. Give TC-2 2b a join tag.
6. Fix the `performative_token` definition for clauses on records with no R-A3 act.
7. Settle D7 "do not …" vs D12 "do not promote".
8. Say whether a negative disposition in a log ("NOT promoted to methodology") can be a D12 verdict (R22, C2 residual).
9. Give a primary-clause rule, or state that class labels like "operational acceptance" are status predicates (R06/R07 `about_target`).
10. Define the order among gated candidates by `state_if_extended`, and the headline vs narrative participle boundary.
11. Correct the CHANGELOG re-run details: the missing `SPLIT_REQUIRED: recommend` on R19–R21, and the incomplete `other_clauses` on R19.
