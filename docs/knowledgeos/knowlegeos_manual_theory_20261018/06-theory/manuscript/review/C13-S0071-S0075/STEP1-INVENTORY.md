*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind). Harness: commissioned 17:11:26Z, received at this save.*

# STEP 1 INVENTORY — C13

Mode: transition review, step 1 (blind). I read only REVIEWER-INSTRUCTIONS.md and S0071–S0075, each in full. I opened nothing under 06-theory/ and did not open RESEARCH-PURPOSE.md. I wrote no files. On independence: this reviewer is independent of the Professor's process. That is process independence only; model separation is not attestable. Points marked [I] are my own judgments. Arithmetic I checked by hand: S0071 type census 34+33+32+16+1+1 = 117 and recordedBy 83+33+1 = 117. S0072 O-1 gives 121. S0073 cross-tab 37+37+16+1+1+38 = 130. All consistent.

## S0071 — BC-7 domain model proposal (the base source of the lineage)

1. **Derived state is a fold projection and is never stored.** `mutationOwner` is stored 0 times but derived everywhere. The fold is the only source of machine-truth. [S0071 L47–50, L71–72, L176–179, L208]
2. **T-1 rule:** no derived projection may be stored, set or accepted as input. The reason given is that a stored copy would be a second truth that can drift (I-10). Stated in absolute form; class `Proposed`. [L208, L240, L279]
3. **Aggregate-root test:** an invariant that spans several instances of a candidate disqualifies that candidate as root. Applied to four candidates. [L80, L92–104]
4. **Negative spanning invariant.** No positive invariant joins transitions[] and grants[]. One root is kept because I-4 ("the two parts never merge") needs a holder that can see both parts, and because there is one write boundary. RA-4 (split into two aggregates) is "the strongest rejected option"; OQ-9 stays open. [L130–145, L219, L353] [I] A disjointness invariant holds by construction when the parts are split, so the "holder" argument may be circular. Test this against the trivial case.
5. **Kernel/application boundary.** BC-7 holds "Recorded Occurrence": that an act happened, its order and its declared attribution, never whether it was right or authorized. "BC-7 can refuse a *malformed* act. It can never refuse an *unauthorized* one." [L73, L293, L312]
6. **Domain policy ("what must always be true of the record?") vs governance policy ("who may decide?").** The first is owned, the second only preserved. All BC-7 decisions are record-consistency rules. [L268, L282]
7. **Owns vs preserves** as the axis of invariant ownership. I-5, I-7 and I-8 are preserved; I-5 is enforced mechanically on only 2 of 5 transition types. [L227–239]
8. **T-2:** reference values (`tokenRef`, `humanActRef`, `GrantScope`) are never dereferenced. `GrantScope` is an opaque string matched by equality, and parsing it would import authority semantics. [L241, L307, L320]
9. **Attribution is a claim, never an attestation** (`recordedBy`, `executionContext`; T-4, INV-ATTR-2). The seam is that BC-7 records the claim and the attribution model adjudicates it. [L74, L198, L243, L330]
10. **Two truth disciplines:** knowledge truth is adjudicated, BC-7 truth is folded, and neither may be put under the other. [L322]
11. **A concept with no observed instance is a claim, not a fact.** CONTINUATION and FAIL have 0 instances in 117, so the exit path of I-3 (sticky STOPPED) has never run (OQ-5). [L42, L154, L221, L349]
12. **Grant lifecycle is unknown.** All 41 grants are AUTHORIZED, and no change of status was ever observed. Whether revocation exists as a concept is open (OQ-6). [L46, L187, L350] [I] The theory has no contraction/retraction case for authority.
13. **Human Act is a value object, not a root.** It has no identity or lifecycle in BC-7. "Importance is not ownership." [L117–126, L218]
14. **Handoff is defined as "moves mutation ownership"**, and Workflow is a classifier value object ("types are not aggregates"). [L66, L254, L115] S0073 L151 later contradicts the Handoff definition.
15. **Measured asymmetry:** 33 REGISTER / 32 START / 16 COMPLETE. Whether it is a bookkeeping gap or an accurate record is open (OQ-8). Open set: OQ-4 (session-id scope), OQ-7 (naming is a governance act), OQ-10 (recorder classes), OQ-11 (name fitness). [L262, L348–356]

## S0072 — Verification #1

1. **F-1:** "`authorized` never consults the session" is false. Session existence is a refusal precondition, but it is not used in the verdict. [S0072 L32]
2. **C-1:** F-1 undercuts S0071 §4.5. A grants-side query that depends on the registry is a cross-part dependency of the existence kind, not the consistency kind. [L33]
3. **C-2: "absence in practice ≠ refusal by contract".** Only the storage half of T-1 is measured; the "accepted as input" half is not demonstrated. [L34]
4. **C-3:** I-5 has a live counter-example (`recordedBy: architecture`) that S0071 carried as a note, not as a finding. [L35]
5. **F-1 is said to strengthen one-root** (the parts are less separable), so RA-4 is weakened. [L66] [I] This is a convenient reversal and needs its own check: an existence check across parts is a different matter from a joint invariant.
6. **Eleven of twelve load-bearing claims re-derived from source**, including T-2 (emptiness checks only), T-3 (contiguous seq, no removal paths), handoff atomicity and the non-owner refusal ("Inv C"). [L39–53]
7. **Census drift 117 → 121**, the verifier's own transition included. Proportions hold and CONTINUATION/FAIL stay at 0. [L36]
8. **Ubiquitous-language flag:** `Grant` does double duty across contexts, a legibility risk. [L80]
9. **Independence:** product separation is Observed; process separation is Unknown. [L14–15]
10. **Source defect / contradiction:** "all 13 live workflow records" [L26, L43, L98] against 15 records in S0071 L39, S0073 L62 and S0075 L33.

## S0073 — Verification #2 (of the refinement document, which is not in this source set)

1. **Three-way distinction:** answerability precondition vs verdict function vs consistency invariant. Session existence is only the first; the third does not exist. [S0073 L98–108]
2. **Four enforcement modes:** refusal (type, fields, ownership, stickiness, writer class), overwrite (only `seq` and `registeredBy`), non-consultation (the fold's definition) and writer obligation (practice). [L122] [I] This is a candidate primitive taxonomy for "how an invariant is guaranteed".
3. **T-1 restated as T-1a/b/c:** the submitter never supplies the state it is judged against, because the fold runs first (structural); a derived key has no standing (non-consultation); no derived key is written (a census of 0 in 130). The invariant holds by non-consultation, not by refusal. [L117–124]
4. **Original T-1 false as an absolute.** `append` has no key whitelist, so a derived key would be stored. [L77, L124]
5. **Falsified measurement:** "0 non-canonical keys" is actually 20 (`note` ×16, `humanAct` ×3, `reason` ×1). This is the empirical proof of T-1b ("stored and inert"). [L128–136]
6. **OQ-12 (from the refinement):** invariants should carry an enforcement class. `note` is the specimen of "stored but never consulted". [L136]
7. **C-3 → (D): mis-scoped invariant statement, not a violation.** I-5 governs the grant register only; measuring it on lifecycle transitions is the conflation I-4 forbids. The four classes are A violation, B missing enforcement, C missing distinction, D mis-scoped statement. [L144–151]
8. **HANDOFF confers nothing (`$owner = null`); ownership passes only at START.** This contradicts S0071 L66/L254 ("Handoff moves mutation ownership"). [L151]
9. **`recordedBy` is a homonym:** origin of the act on START, acting role on REGISTER/COMPLETE/CANCEL, performing lane on the architecture HANDOFFs. [L88, L155–157] [I] Internal quantifier slip: "6 of 37" (L88) vs "wrong on 37 of 37" (L155).
10. **`identity` returns the last AUTHORIZED grantId with no session filter:** a work-item-scoped value inside a session-scoped answer. OQ-13 `authorizationLinkage` deferred. [L87, L186]
11. **Writer separation:** `grant` refuses any writer except governance and `append` has no such check. 48/48 grants are governance-registered with a humanActRef. [L80, L147, L166]
12. **Independence is decomposed into process vs model.** A shared model means a possible shared blind spot. Model identity is self-contradictory (Fable 5 vs Opus 5). [L34–41, L185]
13. **Method:** re-derive first, compare second, so agreement counts as convergence, not deference. Drift 126/47 → 130/48. N-2 "date every census". [L46, L183]

## S0074 — Correction delivery note

1. **The correction author is the Verification #2 author.** This is disclosed as a divergence between the prompt and the grant. The safeguard is "bounded scope + independent review, not exclusion". [S0074 L13–23]
2. **Forward constraint:** whoever performs the correction must not verify it, so this author is doubly barred from V#3. [L18, L91]
3. **Correction 1:** the census becomes 20 instances, re-measured and dated (15 records / 133 transitions / 49 grants). Conclusion: "storage acceptance ≠ semantic authority". [L35–37]
4. **Correction 2:** the DP-6 class cell is derived mechanically from T-1a/b/c (Observed + Proposed), "derived, not invented". [L44–47]
5. **Correction 3:** OQ-10 gains the question "does `recordedBy` carry ONE referent?", recorded and not decided. [L55–57]
6. **Change Boundary Statement:** BC-7 boundary, aggregate, ADR-AIP-03, CAP-14 and the ADR-AIP-04 deferral are all unchanged. [L73–81]
7. **Self-reported diff metric "5 removed / 14 added"**, offered as an acceptance criterion. S0075 falsifies it. [L7, L67, L96]
8. **Self-recorded START:** the lane writes its own human-act START (as at seq 3 and seq 12). [L83] [I] So START "recordedBy human" is written by a lane, which bears on S0073 item 9.
9. **Left open:** R-4 (date all censuses), R-5 (a dangling grant amendment), R-7/OQ-13. [L61]
10. **"Falsify, don't confirm"** instruction to the next verifier. [L99]

## S0075 — Verification #3

1. **Storage acceptance is separated from semantic authority:** "not that such a value is refused at the boundary; it is that it can never become truth." [S0075 L43–47]
2. **After the correction, T-1 rests on the fold's definition;** only T-1c rests on a census. [L45]
3. **F-1:** the diff metric is 16/5 by git, not 14/5. The gap is two blank lines, and the blast radius was measured. "Method-dependent count." [L11, L107–132]
4. **F-2:** "6 of 37" cannot be reproduced; a plain criterion gives 14 of 38 and the selection rule is unstated. Class B evidence-integrity defect. [L12, L85–87]
5. **Recurring defect class, now on its third appearance** (V-D → R-1 → F-1/F-2). Candidate rule: "a census states its selection rule and its measurement point, or it is not evidence." [L14, L212, L231]
6. **Defect taxonomy:** A measurement error *about* an artifact, B evidence defect *inside* it, C scope violation, D model defect. B is ranked above A in class. [L171–194]
7. **Proportions are stable under drift (133→135) while denominators are not;** a dated snapshot is the remedy. Adjacent undated denominators 126/133 (R-1a). [L39, L53, L201]
8. **38/38 STARTs are `recordedBy: human`.** The specimen quotation is confirmed verbatim. [L78–81]
9. **Independence has three factors along the chain:** process, pen and model. Two of three are weakened (the same pen wrote V#2 and the correction; the same model wrote V#1 and V#3). Mitigation is re-derivation from primary evidence. [L136–163, L203]
10. **Amendment vs re-decision:** adding classification is "restatement and completion"; a changed verdict would need a new act. [L248–250]
11. **"Architecture impact: NONE"**, and "an architecture decision was strengthened by an evidence repair without being changed". [L238–244] [I] "Strengthened" is asserted rather than measured.
12. **Out of scope:** whether the mechanism should reject unknown keys. [L49, L254]
13. **Model labels in the commit table** (Fable 5 for the proposal, against S0071's self-declared Opus 5). [L139–146] [I] Declared model identities across the chain conflict and cannot be attested.

## Cross-source table

| Item | S0071 | S0072 | S0073 | S0074 | S0075 | Note [I] |
|---|---|---|---|---|---|---|
| Records | 15 | **13** | 15 | 15 | 15 | S0072 source defect |
| Transitions | 117 | 121 | 130 | 133 | 135 | drift; proportions stable |
| T-1 form | absolute, Proposed | storage half only | false as absolute → a/b/c by non-consultation | evidenced by 20 inert keys | rests on fold definition | concept changes over time |
| Handoff and ownership | moves ownership | atomic between two | confers nothing; START transfers | — | — | definition contradicted |
| `recordedBy` | claim of recorder class | — | homonym; 6/37 vs 37/37 | OQ-10 extended | 38/38; 6 not reproducible; 14/38 | unstable count |
| Lineage | base | verifies S0071 | verifies refinement (absent) | same pen as S0073 | same model as S0072 | one line of support: same work item, mechanism and estate; censuses re-derived but not independent in origin |

The refinement document (`KOS-ARCH-BASELINE-003-domain-model-refinement.md`), which holds T-1a/b/c, OQ-12 and OQ-13, is not among the sources. S0073–S0075 describe it second-hand.
