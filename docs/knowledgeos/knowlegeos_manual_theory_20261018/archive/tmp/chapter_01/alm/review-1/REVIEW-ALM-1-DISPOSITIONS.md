# Review 1 of ALM: the author's reproduction, classification and dispositions (2026-10-18)

The reviewer's text is in `REVIEW-ALM-1-REPORT-verbatim.md` and is **not** edited here. This file holds only the author's side. **This review is separate from the earlier M2 review (`../../1.14.1-repair/REVIEW-2-RECORD-M2.md`).** Its conclusions are not merged with that one.

**Reproduction:**
- **Counterexamples CE1–CE8 and CE10–CE12:** re-run on the project-local baseline (byte-identical to the reviewed files). Output: `ce_reproduction_output_20261018.txt`. All reproduce.
- **Findings without a script:** reproduced by hand on the same baseline:
  - **P-5:** λ=0 gives `[AUTHORIZED, NOT_AUTHORIZED]`; λ=∞ gives `[UNDETERMINED, UNDETERMINED]`.
  - **E-1:** `ARCHITECTURE_DECISION_RECORDS.md` L68 and L293 supersede ADR-001; L589 says "0 Superseded".
  - **E-4:** the R-86 row mentions neither R-72 nor a proviso.
  - **E-8:** ALM L108 and L201 put IND-1 in B₀; checkpoint L57 and L72 say IND-1 "is authority, not eligibility".

**Classes (as instructed):**
- **MD** mathematical defect;
- **ID** implementation defect;
- **UPr** unsupported premise;
- **SM** semantic mismatch, either with r1.1, the sources or v1;
- **MS** missing scenario or coverage;
- **PQ** policy question (decided silently, or under-parameterized).

## Dispositions

| ID | Sev (reviewer) | Reproduced? | Class | Author's response | Disposition |
|---|---|---|---|---|---|
| **P-1** | BLOCKER | **yes (CE7)** | MD + ID | Correct. END_CLAIM's coverage reads the target claim's subject, which may lie later or be unrecorded. Lemma 2 ("by inspection") is false, and so is Theorem 3 | **ACCEPTED.** Theorem 3 and Lemma 2 are **withdrawn as stated**. CE7 becomes a regression test |
| **P-2** | MAJOR | yes (CE8) | UPr | Correct. Theorem 3 needs `≺` known to the recognizer. Records carry `pos` only; BC-7 has no clock | **ACCEPTED.** It is a premise to state (A0 must be *recorded*), or the theorem must be restricted to distinct act times |
| **P-3** | MAJOR | yes (CE11) | MD | Correct. Under open world, *no* negative is sound, not only absence-based ones. "Recorded bar" was a category error | **ACCEPTED.** The Theorem 6 consequence is withdrawn. The surviving statement: under open world, every records-based NOT_AUTHORIZED is defeasible |
| **P-4** | MAJOR | by reasoning: agree | MD | Correct, and it **also corrects my Phase 1 / Addendum 3 claim** that the store "cannot reconstruct" the records as they stood. Under append-only, prefixes are recoverable. The gaps are (i) **unverifiable** append-only (C-3) and (ii) no date→position map μ | **ACCEPTED.** "ADR-C6 load-bearing" is withdrawn. **Correction recorded against the checkpoint and the TD-001 Addendum 3** (see "Contradictions with earlier records" below) |
| **P-5** | MAJOR | yes | MD + ID | Correct. A premise is missing. The cited test name does not exist; the printed example differs | **ACCEPTED** |
| P-6 | MINOR | by reasoning: agree | MD | Correct. The sentence was wrong; a strict well-founded order suffices | **ACCEPTED** |
| P-7 | MINOR | by reasoning: agree | MD | The hypothetical act at *t* is undefined in V1 | **ACCEPTED** |
| P-8 | MINOR | by reasoning: agree | MD | Bullet 3 also fails without any false record (late revocation) | **ACCEPTED** |
| P-9 | MINOR | by reasoning: agree | MD | Proposition 8 is vacuous as stated | **ACCEPTED.** Withdrawn |
| P-10 | MINOR | yes (CE10) | ID + MD | Correct. Well-formedness is stated but not enforced, and every exhaustive grant violates it | **ACCEPTED** |
| **C-1** | MAJOR | yes (CE1) | ID + PQ | Correct | **ACCEPTED** |
| **C-2** | MAJOR | yes (CE3) | PQ | Correct. **Independently found by the author** in `tests_v1cases.py` case 11c: ALM fixes RD 1's δ = no silently | **ACCEPTED** |
| **C-3** | MAJOR | yes (CE4) | ID + MD | Correct. The signature is inconsistent | **ACCEPTED** |
| **C-4** | MAJOR | yes (CE2) | SM + ID | Correct. **Related author finding:** v1 case 12 diverges (`tests_v1cases.py`) for the same root cause | **ACCEPTED** |
| **C-5** | MAJOR | yes (CE5) | PQ + ID | Correct. "All grants must bar" lets a second grant defeat a PO/ARB bar | **ACCEPTED** |
| C-6 | MAJOR | by inspection: agree | ID | The R2 first branch is not implemented | **ACCEPTED** |
| C-7 | MINOR | by inspection: agree | ID | — | **ACCEPTED** |
| **C-8** | MAJOR | yes (CE6) | MD + SM | Correct. One λ gates positives and negatives | **ACCEPTED** (see F-1) |
| C-9 | MINOR | by inspection: agree | ID | `procedural_ok` is never computed | **ACCEPTED** |
| C-10 | MINOR | by inspection: agree | PQ | Perpetual root (v1's C12 "can the root change?") | **ACCEPTED.** Map to C12 |
| C-11 | MINOR | yes (CE12) | PQ | One parameter covers two conventions (C14 grants vs claim periods) | **ACCEPTED** |
| C-12 | NOTE | — | — | — | noted (P-2) |
| **T-1** | MAJOR | yes (count verified: 7458) | MS | Correct | **ACCEPTED** |
| **T-2** | MAJOR | by inspection: agree | MS | Correct | **ACCEPTED** |
| **T-3** | MAJOR | by inspection: agree | MS + ID | Correct. The test never uses positions | **ACCEPTED.** Withdrawn as support |
| T-4 | MINOR | agree | MS | Self-consistency, not conformance | **ACCEPTED** |
| T-5 | MINOR | agree | MS | — | **ACCEPTED** |
| T-6 | MINOR | agree | MS | — | **ACCEPTED** |
| T-7 | MINOR | agree | MS | — | **ACCEPTED** |
| T-8 | MAJOR | agree in part | SM | **Partly disputed (see "Unresolved disagreement" below)** | ACCEPTED as a labelling and fidelity defect |
| A-1…A-5 | MAJOR | agree | SM/PQ | They follow from C-1…C-5 | **ACCEPTED** |
| **A-6** | MAJOR | agree | SM | ALM's Actor conflates authority holder (a human or role) with performing process. INV-ATTR-1 forbids process identity as an authority input | **ACCEPTED.** It needs the holder/performer split; not a policy question |
| A-7 | MINOR | agree | ID | — | **ACCEPTED** |
| **F-1** | BLOCKER | yes (with P-5, CE6) | SM + MD | Correct. **The author had independently derived the two-horizon reading (λ⁺, λ⁻)** in a draft written before this review arrived. That draft is parked, unused and unauthorized (`../parked/alm_v2_DRAFT_not-authorized_20261018.py`) | **ACCEPTED.** §7's fidelity claim is withdrawn |
| **F-2** | MAJOR | agree | SM + PQ | Correct; κ may be per-type. This matches v1's own **RD 1 "per kind of claim and kind of ending"**, which ALM collapsed | **ACCEPTED** |
| **F-3** | MAJOR | agree | PQ | Correct. ALM decided C1 for the validity layer. The strict C1-a form ("recorded at the time of the act", via μ) cannot be represented | **ACCEPTED.** MODEL-COMPARISON's I3 is withdrawn as a faithful rendering of C1-a |
| F-4 | MINOR | agree | PQ | — | **ACCEPTED** |
| **E-1** | MAJOR | yes | UPr | Correct. ADR-001 is partly superseded, and supports act-time validity rather than κ = persist | **ACCEPTED.** The κ evidence is downgraded |
| E-2 | MAJOR | agree (the author read the same text) | UPr | Correct. Stress Test 8 favours per-type behaviour | **ACCEPTED** |
| E-3 | MAJOR | agree | UPr | §10.1 "its authority lapsed" is cascade-type counter-evidence. **The v1 baseline had already recorded this ("Reading 1 is the more natural one")**, and ALM omitted it | **ACCEPTED** |
| **E-4** | OK / MINOR | yes | UPr | **R-72 → R-86 was an inference in the Phase 1 checkpoint (Q4), presented as evidence** | **ACCEPTED.** Checkpoint correction below. R-41 misattribution for U accepted. R-60 as an R3 failure accepted as a stretch |
| E-5 | OK / MAJOR | agree | SM | IND-2 is relational (the same work); ALM's bars are not | **ACCEPTED** |
| E-6 | OK / MAJOR | agree | SM | = A-6 | **ACCEPTED** |
| E-7 | OK / MAJOR | agree | UPr + SM | T-3 is *Proposed* in S0071; START does not consult grants; O1 is a target, not observed behaviour | **ACCEPTED** |
| **E-8** | MAJOR | yes | SM + UPr | REVOKE has no governance-side instance. **IND-1 contradiction confirmed** (ALM L108 vs checkpoint L57). n ≥ 6 vs 5 sources: confirmed | **ACCEPTED** |

## Unresolved disagreement (recorded, not hidden)

**D-1, on T-8, first half.** `S0103_before_transcription_records_say_not_authorized_under_closed_world` was written to show **the consequence of λ = 0**, not r1.1 behaviour. Its name says "closed_world".
- **Author's position:** as a test of a stated non-r1.1 policy it is legitimate.
- **Reviewer's position:** it passes while encoding behaviour r1.1 forbids, and the suite presents itself as r1.1-faithful.
- **Resolution:** both hold. The test stays, relabelled as a *policy-consequence* test. The suite must separate r1.1-conformance tests from policy-exploration tests. Not further disputed.

**No other disagreement.** Every other finding is accepted as stated.

## Contradictions with earlier records (reported, not silently corrected)

| Earlier record | Statement | Now |
|---|---|---|
| `PHASE-1-CHECKPOINT-20261018.md` Q4 | conditional acts become operative through an explicit later act "in both cases" (S0052; R-72 → R-86) | **Overstated.** R-86 does not state that R-72's proviso was satisfied (E-4). Direct evidence: **n = 1 (S0052)**. R-72 → R-86 is an inference |
| `PHASE-1-CHECKPOINT-20261018.md` §2 | "New finding: Approved ≠ Currently authoritative" | **Not new.** TD-001 r1 already cited it as "V3 L313–323". What is new is only the verification of status and scope (author's own finding, 2026-10-18) |
| `TD-001-RESEARCH-REPORT.md` Addendum 3 and the checkpoint | "as the records stood on that date" cannot be reconstructed: no clock, no history | **Imprecise.** Positional prefixes *are* recoverable **if** the store was append-only, which is unverifiable. What is missing is (i) integrity evidence and (ii) a date→position map μ (P-4) |
| `ALM-FORMAL-MODEL.md` V4 / §6 | IND-1 is in B₀ (eligibility) | **Contradicts** the checkpoint's "IND-1 is authority" |
| `ALM-FORMAL-MODEL.md` §6 | parameter names κ, ι, λ | **Collide with the reserved-decision register**, as shown below |

**Symbol collisions with the register** (`../s11411-repair/M2-TRACEABILITY-AND-DECISIONS.md` L56–80; v1 L599–612):

| ALM symbol | Its meaning in ALM | Register symbol and meaning | Collision |
|---|---|---|---|
| κ | carry-over of endings | **v1 κ** = unknown citation (C2) | the ALM κ is register **RD 1 (P, including δ)** |
| ι | interval convention | **RD 4 (ι)** = the reason condition of I-2 | the ALM ι is register **C14** |
| λ | closure horizon for absence | **C11 (λ)** = a grant recorded after the act | the ALM λ is not C11; it is closest to **C1/C2** ("missing record") |
| U | per-subject uniqueness | — | the ALM U is register **C6** |
| A0 | tie-break | v1 case 12 uses **record position** as the tie-break; C10 covers cycles | the ALM A0 *rejects* positions. **This diverges from v1** |

**ALM must adopt the register's IDs**, and its own κ, ι and λ must be renamed. Until then, citations of "κ", "ι" or "λ" are ambiguous.

## Is the current ALM sound enough to be the next candidate?

**No.**
- Two BLOCKERs (P-1, F-1) and 20+ MAJOR findings are accepted.
- The traceability matrix (`../TRACEABILITY-TD001-V1-M2-ALM.md`) shows that 7 of the 16 v1 case rows **cannot be expressed** in ALM; v1 case 12 diverges; and one hidden policy (δ) is present.
- The validity-layer recursion (Theorem 1) and the layering framework survive.

**Minimum corrective work.** This is listed, not started; **authorization is requested** in the consolidated report.

1. **Theory**:
   - make act order and claim identity explicit fields; END_CLAIM must follow its adoption;
   - restate Lemma 2, Theorem 3 (premise: ≺ recorded), Theorem 4 (λ⁻ < ∞), Theorem 6 (every negative is defeasible); withdraw Proposition 8 and Theorem 5's consequence.
2. **r1.1 fidelity**: two horizons (λ⁺, λ⁻) or an explicit projection. Show r1.1 = (λ⁺ = 0, λ⁻ = ∞).
3. **Parameters, not silent choices**:
   - δ and per-kind P (RD 1);
   - authorizing-grant-specific cascade, with transitivity as a parameter;
   - bars any/all;
   - target-specific REVOKE/GRANT scopes;
   - root (C12);
   - separate interval conventions for grants and claims (C14).
4. **Holder/performer split** (A-6, INV-ATTR-1); relational IND-2; IND-1 as authority.
5. **Register alignment**: use RD/C IDs; no new symbols.
6. **Tests**:
   - an exhaustive alphabet including END_CLAIM, CONFIRM, finite periods, bars, κ-cascade and λ > 0;
   - conformance tests separated from policy-exploration tests;
   - CE1–CE12 + v1 cases as regression tests;
   - an **independent** test oracle (not reusing `_valid_given`).
7. **Scope decision for ALM:** either extend it to the v1 cases it cannot express (citations / C2, suspension, supersession-with-successor / C5, annulment with effective time, consumption / C13, υ / RD 6, joint bases / C4), **or** state explicitly that ALM covers only the TD-001 core and that §1.14.1 remains governed by v1/M2. **This scope choice should be made before revising.** It is a research-planning choice, not policy.
