# Targeted re-review of SCHEMA-r3 r2

Files used: REVIEW-TASK.md, CHANGELOG-r2.md, KNOWN-AMBIGUITIES.md, SCHEMA-r3.md (r2), THEORY-CHOICES.md, RATIONALE.md, TASK.md (the r2 task, which REVIEW-TASK calls TASK-r2), REVIEW-r1.md / .json, GAPS.md, RECORDS.json, SOURCES.md, HYPOTHESES.md. CHANGELOG-r1.md is not in the bundle, so r1 values were taken from REVIEW-r1 and CHANGELOG-r2.

## Verdict: **PASS_WITH_LIMITATIONS**

- **N-a and N-d are fixed.** `state_if_extended` is defined for both polarities and is recorded for every gated candidate. Mechanism refusals are coded and tagged, and the choice is disclosed (TC-7).
- **R18 and R20 now each have a single answer.**
- **TASK items 4–7 are fixed.**
- **Neutrality:** I found no rule that builds H-D or H-B into the default observations. Every theory choice in THEORY-CHOICES can be computed from the tags.
- **No blocking issues.**

It is not a plain PASS for four reasons:
1. A few new drafting conflicts came in with GM / PV / Nd (§6).
2. KNOWN-AMBIGUITIES is honest but **not complete**. It misses six items (§4).
3. TC-1 1c's table and its recipe describe two different computations (§5).
4. The codability risk is now **high** (§7).

---

## 1. N-a and N-d

### N-a: fixed

- **Rank 3** now reads "REPORTED (D3), or a SELF-PERFORMANCE (D3)" (SCHEMA-r3.md:95). The gate (step 2, :87) alone enforces R-A3.
- **Default coding is unchanged.** Every self-performance that reaches rank 3 has already passed the gate (:98).
- **`state_if_extended` covers both polarities:**
  - a gated positive act → rank 3 → ACT_OCCURRED (or rank 2 ATTEMPTED where a non-effect marker is present: R27);
  - a gated negative act → rank 1 → ACT_REFUSED / ACT_WITHDRAWN / ACT_REVERSED, or UNKNOWN when no proposal or attempt is stated.
- **"Never NONE" (:26) holds.** A bare negation with no proposal is not a disposition (D5 (ii)). It is therefore not a self-performance and is never gated.
- **`act.gated_candidates` (:27, :88, :102)** records every gated candidate, step-4 losers included, with `ext_rank` and `coded`. Step 4 now has a stated order among gated candidates (`ext_rank`, then span order). That closes r1 C8 side effect (b).

One residual is inherited and disclosed: a negative gated act with no stated proposal gets UNKNOWN, while a positive one always gets ACT_OCCURRED. This comes from R-A2 (r2 text), not from the gate. It is the same for actor refusals everywhere.

### N-d: fixed and disclosed

- **D6 MECHANISM RESPONSE (:200–203), R-A2 bullet (:61), D5 last bullet (:194), and rank-1 / rank-2 cells (:93–94).** A program, tool or check output is never a rank-1 refusal. It is the FAILED marker:
  - with a stated attempt: ACT_ATTEMPTED / FAILED;
  - without one: UNKNOWN.
  - Both carry `act.mechanism_refusal = true`.
- **Actor refusals stay at rank 1.** With "proposal **or attempt**", an actor's refusal of a stated attempt is ACT_REFUSED rather than UNKNOWN. So refusals by mechanisms and by actors both now reach a support-counted state when their attempt or proposal is stated, and both go to UNKNOWN when it is not. **That is symmetric.**
- **Disclosure.** The repair does **both** (codes and tags). This is stated in CHANGELOG-r2 §1, RATIONALE Nd and TC-7. 7b is computable from `mechanism_refusal` + `state`.
- **Rule test (S0815 CLOSE probe):** ACT_ATTEMPTED / FAILED *append*, REPORTED ("Probed"), `mechanism_refusal = true`. Confirmed.

Residual: the MECHANISM RESPONSE boundary is too wide (§6.3). It is tagged, so it stays separable.

## 2. R18 / R20: single answers

- **R18.** ACT_OCCURRED *adopt*, `basis = REPORTED`.
  - Why REPORTED: D3 (ii), because Handover §9 is named as where the act was done.
  - Fields: actor ARB; target Epistemic labeling; quote "already ARB-adopted in Handover §9".
  - Why it is a candidate: it sits in the recommendation cell, and neither the dash nor "already" is a D15 marker (D15's closing line).
  - The evidence-column "ARB-adopted" is excluded by D15 (iii).
  - `SPLIT_REQUIRED: classify, recommend`. `gated_candidates`: classify (A) and recommend (PROMOTE), both ext ACT_OCCURRED, rank 3, `coded = false`.
  - Norm: ELIGIBLE, VERDICT, *promote*.
- **R20.** ACT_OCCURRED *document*, `basis = REPORTED`.
  - Why REPORTED: D3 (ii), because PB-004 retro §9 and Handover §8 are named.
  - Fields: actor NOT_EVIDENCED; target Strangler reconstitution.
  - The evidence-column "did NOT reuse" is excluded by D15 (iii).
  - `SPLIT_REQUIRED: classify, recommend`. `gated_candidates`: classify (D) and recommend ("Remains Candidate"), rank 3.
  - Norm: STATUS_CONSTRAINT, `status: category D`; "Remains Candidate" in `other_clauses`.

Both agree with CHANGELOG-r2 §3 and KNOWN-AMBIGUITIES §C.

## 3. TASK items 4–7

| Item | Fixed? | Note |
|---|---|---|
| 4 Rank-1 "proposal or attempt" (RF) | **Yes** | Rank-1 cell, D6 last bullet and R-A1 agree with R-A2 |
| 5 D7 vs D12 "do not …" (DN) | **Yes** | The imperative of the verdict's own verb is a verdict in both polarities. Other imperatives stay D7. It still depends on B9 ("meta-operation under decision"), which is disclosed |
| 6 Primary clause (PC) | **Yes** | Class labels are D8 (i) status predicates. R-N3 step 4 then decides R06/R07: `status: operational acceptance`, target R-90. "The register holds …" goes to `other_clauses` |
| 7a TC-2 2c recipe | **Yes** | Primary STATUS_PREDICATE only, no VERDICT clause, stated set L, polarity from the legend. R15–R18's "A" is no longer flipped |
| 7b TC-2 2b join tag | **Yes** | `norm.adopted_via` comes from the heading. The join fails in this dataset (no `## R-36` record). That is disclosed, and the recipe says to report it |
| 7c `performative_token` (PT) | **Yes** | It is defined by the text and recorded per `other_clauses` entry, and 4c now excludes clauses. Minor: "the clause's normative word" is not defined for class-label clauses (is it the copula or the label?). No current record changes |
| 7d Missing choices | **Yes** | The r1 review named none missing. TC-7 is added for Nd |

## 4. G1–G6 re-tests under r2

| Gap | Record | r2 answer | Determinate? |
|---|---|---|---|
| G1 | R15, R16, R17 | NONE, OUTSIDE, *classify* (category A, first in span). `SPLIT_REQUIRED: recommend`. `gated_candidates`: classify (coded), recommend; both ext ACT_OCCURRED, rank 3 | Yes in `act.state`. `basis` / `operation` are open under KA B1 (D2 vs the D3 examples) |
| G1 | R18 | §2 | Yes |
| G1 | R19 | NONE, OUTSIDE, *classify* (C). `SPLIT_REQUIRED: recommend` | `act.state` is open only under KA A6 (c) ("ONE demonstration" read as a reported act) |
| G1 | R20 | §2 | Yes |
| G1 | R21 | NONE, OUTSIDE, *classify* (D). "explicitly ruled" sits in the evidence column (D15 (iii)). "replaced only on repeated evidence (ER-02)" is neither an occurred act nor a provenance mention, so it goes to `indicative` | Yes |
| G1 | R27 | NONE, OUTSIDE, *apply* §12. `gated_candidates`: apply (ext ACT_ATTEMPTED / PREPARED, rank 2, coded). `later_disposition` R-86. Norm NONE; indicative "it APPLIES §12" | Yes |
| G2 | R15–R18 | ELIGIBLE, VERDICT, *promote*, target = the row's behaviour. Category A in `other_clauses` | Yes (R18 `other_clauses`: KA A8, tag only) |
| G2 | R19 | STATUS_CONSTRAINT, `status: category C`. `other_clauses`: "→ D", "Stays in ADR-MP-06", "→ Candidate Pattern", "permanent design principle" (`cited_as_ground = true`) | Value yes. Membership of `other_clauses` is open at B4 |
| G2 | R20, R21 | STATUS_CONSTRAINT, `status: category D` | Yes (R21 `other_clauses`: KA A7) |
| G3 | R01 | NONE, OUTSIDE, *resolve*; gated ext UNKNOWN, rank 1; `mechanism_refusal = false`. Norm PROHIBITED *adopt*, OPERATOR | Yes, except `cited_as_ground` (KA A3, tag only) |
| G3 | R26 | ACT_REFUSED *supersede* ADR-T14, SELF_PERFORMED. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY, `performative_token = true` | Yes. Strictly, `mechanism_refusal` is not *required* here: "is not superseded" is D5 (ii), not (i). CHANGELOG fills it as false, which is harmless |
| G3 | R29 | NONE / NONE; norm NONE | Yes |
| G4 | R05 | ACT_ATTEMPTED / PREPARED *authorize* WP-4C-1, SELF_PERFORMED. Norm AUTHORIZED, OPERATOR, `plan + implement`, `stated_by = ARB CHIEF (PREPARED)` | Yes |
| G5 | R10 | NONE / NONE. Norm STATUS_CONSTRAINT (D8 (ii)) *begin implementation* ("so" is not a D15 marker) | Yes |
| G5 | R23 | ACT_OCCURRED *adopt*, SELF_PERFORMED, `attributed_to` PA. Norm STATUS_CONSTRAINT, `status: permanent documentation standard`; "adopted" (PERFORMATIVE_ONLY) in `other_clauses` | Yes |
| G6 | R02 | ACT_OCCURRED *adopt* R-81..R-85, SELF_PERFORMED. Norm STATUS_CONSTRAINT, PERFORMATIVE_ONLY | Yes |
| G6 | R30 | ACT_OCCURRED *adopt*, target ANNOTATE. Norm PERFORMATIVE_ONLY, `about_operation = adopt` | Yes |
| G6 | R06/R07 | Norm: `status: operational acceptance`, target R-90, `cited_as_ground = true`, identical on both records. Acts: ACT_WITHDRAWN *withdraw* (REPORTED) and ACT_OCCURRED *rule* (REPORTED) | Norm yes. **Act on the *rule* record: see below** |

### Still ambiguous

**A. In KNOWN-AMBIGUITIES (confirmed):**
- A1/A2 R22;
- A3 R01 `cited_as_ground`;
- A4 R03;
- A5 R06/R07 assignment;
- A6 R19;
- A7 R21;
- A8 R18;
- A16 R-36;
- B1 D2 vs D3 examples (R15–R21 `basis` / `gated_candidates`);
- B2–B4, B9–B11.

**B. Not in KNOWN-AMBIGUITIES:**
1. **R06/R07, the *rule* record.**
   - R-A5 step 1 says an act in a ground-marked segment is "not a candidate for **this record**". "because the Authority ruled …" is ground-marked by D15 (i), and both R06 and R07 have that same span. Read literally, *rule* is a candidate for neither record, and the second record's act is NONE.
   - §1 [r3-G6] and D10 ("withdrawn … because the Authority ruled …" is DISTINCT, so one record per (operation, target) on the shared span) give ACT_OCCURRED *rule*.
   - The D10 example points to the CHANGELOG answer, but the two rules conflict as written. The conflict predates r2. r2's "closed" wording makes the step-1 exclusion harder to read around.
   - Value-level on one record.
2. **D15 (ii) PROVENANCE vs PV.**
   - PV says provenance mentions are REPORTED candidates. RATIONALE PV cites "(adoption act: R-86)" as a principal report.
   - D15 (ii) lists *PROVENANCE* as a ground-label head word. So text after "PROVENANCE: ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED" (R-89/R-90/R-91) is ground-marked, and its issuance act is not a candidate.
   - It is also open whether "PROVENANCE ANNOTATION …:" (R-81/R-83) has the head word PROVENANCE or ANNOTATION.
   - No current record is affected, because those labels fall outside the spans. Spans coded later are exposed.
3. **MECHANISM RESPONSE over-inclusion.**
   - D6 lists "returns nothing" as an example, and it counts test or check output.
   - The FAILED bullet does not re-check D6's "did not take effect" condition. So a successful probe with an empty result (R-83 "grep … returns nothing") or an intended RED test failure (git-6a67da5d7 "4 failed") is coded ACT_ATTEMPTED / FAILED.
   - B5 covers governance checks only.
   - No current record is affected, and the tag makes these separable.
4. **R-A5 step 2 vs step 4 / PV.**
   - Step 2 says that when a candidate is gated, "the record is coded NONE / OUTSIDE / X". It does not say "unless an ungated candidate wins at step 4".
   - On R18/R20, step 4 overrides this. Read alone, step 2 gives NONE / *classify*, which was r1's answer.
   - Drafting only.
5. **TC-1 1c: table vs recipe** (§5).
6. **KNOWN-AMBIGUITIES header.** It lists R15–R17 as determinate in `act.basis` / `act.operation`, but its own B1 leaves exactly those fields open. The disclosure is there (B1), but the header contradicts it.

**Is KNOWN-AMBIGUITIES complete and honest? Honest: yes. Complete: no.**
- Every item it lists checks out.
- Its §C predictions hold (R18, R20, R06/R07 norm, `state_if_extended`, S0815).
- It is candid about value-level cases outside the gaps (R13, R14, R33, R08, R11).
- It misses items B.1–B.6 above. Only B.1 is value-level on a current record.

## 5. Neutrality

**Does any rule now build H-D or H-B into the observations? No blocking leak found.**

- **Na** removes an asymmetry inside 1c. Default values are unchanged.
- **Nd** removes a loss that fell on H-B "failure" material. The other reading (7b) is computable.
- **DN** removes a polarity asymmetry.
- **PV.** It adds REPORTED provenance acts of any kind: *adopt* (norm-making, R18) and *document* (R20). That is lexical, and the row's own gated acts are kept.
- **GM.** It is lexical, and it excludes governance and work acts alike: R18's evidence "ARB-adopted", R21's "explicitly ruled", R15–R17's work evidence. **Non-blocking note:**
  - acts excluded as grounds or evidence are not tagged anywhere on the record;
  - in this corpus those segments carry most of the "work → norm" coupling evidence H-B needs ("demonstrated platform insufficiency", "did NOT reuse it");
  - "It may be its own record" helps only if such records are individuated.
  - Recommendation: add `act.ground_candidates` so that the coupling can be computed.
- **PC.** Class labels are restricted to kinds of act / decision / ruling / instrument / document, plus legend categories. So governance-object classifications become STATUS_CONSTRAINT norms, while work-artefact labels ("temporary carrier, not a pattern") may not.
  - This is disclosed as B4 / A7.
  - In current records it affects `other_clauses` only, and "kind of act" covers work acts too ("Execution of 7B is a SEPARATE act").
  - **Non-blocking.** A `label_kind` tag would make it computable.
- **D6 over-inclusion (§4 B.3).** It would inflate failure observations (H-B material) on future log and commit spans. It is tagged and separable, so it is non-blocking.

**Is every theory choice listed and computable from tags? Yes, with one specification defect.**
- TC-1 to TC-7 are each listed, and each can be computed from the tags named.
- TC-1 1c's table says "1a + every entry of `act.gated_candidates`", which is additive. The 1c recipe instead re-applies step 4, which is competitive and gives one act per record.
- Under the recipe, R18's REPORTED *adopt* ties at rank 3 with gated *classify* "A", which comes earlier in the span. *Classify* wins, so R18 **loses** an act that 1a and 1b both count. 1c is therefore not a superset of 1a, as the table claims.
- R15–R17's *recommend* is counted under the table reading but not under the recipe.
- The recipe is deterministic, so the choice is computable, but the analysis must say which reading it uses. Fix by aligning the two, e.g. make 1c additive per (operation, target).

## 6. Regression

New conflicts are listed in §4 B.1–B.4. They are all drafting-level, and none changes a default value on a current record except the literal R07 reading (B.1). Other minor points:
- **§2 `mechanism_refusal`** is required on D5 (i) or FAILED spans only. CHANGELOG fills it on R26 (D5 (ii)) but does not mention R03, which contains D5 (i) "NOT ADOPTED" and so requires it.
- **The PT "normative word"** is undefined for class-label clauses.

**Minimality.** The changes stay inside the seven TASK items, and each is tagged `[r2-fix-*]`. Additions beyond the strict minimum, each justified in RATIONALE and none changing a value outside its item:
- three new fields (`gated_candidates`, `mechanism_refusal`, `adopted_via`);
- D15's extra label words (REASON, RATIONALE, BASIS, PROVENANCE);
- TC-7.

PROVENANCE is the one addition that is not justified by the sources' use of the word (§4 B.2).

## 7. Codability

The schema can still be applied within the bundle, but the **codability risk is high**:
- **Rule surface.** 15 definitions, a 4-step R-A5 with gate, ranks and tie-breaks, and a 7-step R-N3.
- **Field volume.** Roughly 25 fields per record, including three nested lists: `gated_candidates` with 7 sub-fields, `other_clauses` with 6, and `indicative`. Several conditional-required rules apply.
- **Layered markup.** Four revision layers of tags and struck text are interleaved in the normative text.
- **Ambiguities.** Sixteen record-level and eleven rule-level ambiguities were predicted before coding, and I add six more.
- **What a fresh coder needs.** Correct results on R18/R20, R06/R07 and the matrix rows require cross-reading §1, D3, D10, D15, R-A5 steps 1–4 and R-S1 together.

Recommended before coding:
- a clean consolidated text without the revision markup;
- one worked example per R-A5 path.

## Blocking

None.

## Non-blocking

1. Reconcile R-A5 step 1's "not a candidate for this record" with §1 / D10 for shared-span ground acts (R06/R07).
2. Remove PROVENANCE from D15 (ii), or reconcile it with PV. Also settle "PROVENANCE ANNOTATION".
3. Make MECHANISM RESPONSE require a stated non-effect. Drop "returns nothing" or qualify it, and say whether intended test failures count.
4. Make R-A5 step 2's "the record is coded NONE …" conditional on no ungated candidate winning at step 4.
5. Align TC-1 1c's table (additive) with its recipe (competitive).
6. Correct the KNOWN-AMBIGUITIES header for R15–R17, and add §4 B.1–B.5.
7. Add `act.ground_candidates` (acts excluded by D15), so the work → norm coupling in grounds and evidence can be computed.
8. Consider a `label_kind` tag on class-label clauses (PC boundary B4).
9. Define the "normative word" of a class-label clause for `performative_token`. Fill `mechanism_refusal` on R03.
10. Publish a consolidated, markup-free schema with worked examples, to reduce codability risk.
