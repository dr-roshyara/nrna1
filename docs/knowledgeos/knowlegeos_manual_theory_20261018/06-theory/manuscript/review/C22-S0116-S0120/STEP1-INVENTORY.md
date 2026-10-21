*Window reviewer W (Opus), step 1 blind inventory, returned as hand-back text, verbatim (saved by the Professor at 2026-10-21T06:23:06Z). sha256 of the hand-back text at receipt: `f582dbb4a93a0e4d29e40473647792e1a64646e1586fbca16cb0f78ae76a716b` (UTF-8, the text below this line exactly).*

C22 STEP 1: BLIND INVENTORY

Reviewer: independent reviewer of window C22, in transition-review mode. This is process independence, not personal independence. Nothing under W/06-theory/ was read, and I looked at no git log or diff of W and no read log. I edited nothing.

**Access and depth**
- All five paths are listed in corpus-access/CORPUS-ALLOWED.tsv at lines 1490–1494, as S0116–S0120. S0117's row did not appear in my grep for "c10" because its path uses "C10" in capitals, but I checked it separately with the full path (count 1). Line counts are 170, 300, 77, 77 and 714.
- Skimmed-source safeguard (Rulebook v1.3 §3): no source has yet been read at depth "skim". I read all five whole: S0120 in two parts (1–360 and 360–714), the others in one read each.
- I did one repetition check against S0108–S0115. It was a grep for key phrases, run as a single command; the results are not saved anywhere.
  - Hits in earlier sources: "limb" (S0112), "non-constitutive" (S0112, S0113), "acknowledgement was recorded" (S0114). "Class C" also matched S0109, but the meaning there is unchecked and probably different.
  - No hits: "enabled set", "authorized boundary", REPRODUCIBLE, self-evidencing, "status laundering".
  - A miss is not proof of absence.

**Blind depth judgements**
- S0116 full: it is a commission, but it sets the evidence classes A/B/C, the three "sharpenings", and the revisit-trigger requirements that S0117 is measured against.
- S0117 full: it holds the window's main theory (the four-dimension evidence model, E1–E10, the assurance levels L1–L3, and the trigger). It has the most claims open to attack.
- S0118 full: it is short, but its label-crossing defect S-1 is a ubiquitous-language finding the later D1 decision depends on.
- S0119 full: it is the structural argument (the referent gap caused by a *required* field) that the final D1 adopts.
- S0120 full, but repetition-weighted:
  - Lines 1–445 are the *decided* forms of D2, D3, D4.1, D4 and D4.3. Their preparation documents were S0111–S0115, but the decided forms add deltas: the D2 self-correction, the D3 non-constitutive clause, "vocabulary ≠ enabled set", the five prerequisites, and the two-way bar on M4.
  - Lines 450–714 (DEC-D5, D5-AMD1, D1 final) are wholly new.

---

## S0116: D5 commission (Governance), plus amendments AMD1 and AMD3
1. **L13–15** The task is to define the *minimum evidence sufficient* to move C-10 from NOT YET ESTABLISHED to ESTABLISHED. It sets a threshold only; D2 must not be changed.
2. **L19–23** There are three evidence classes:
   - A, Realization: ① a receipt exists, ② an acknowledgement was recorded, ③ a lifecycle transition occurred, ④ authoritative state was produced.
   - B, Boundary: ⑤ a distinct outcome, ⑥ applicability consumed as an external fact, ⑦ a distinct reason to change, ⑧ identifiable invariants.
   - C, Future: schema, plans, intentions, and an adopted invariant that was never exercised. Class C must never establish existence.
3. **L25** D1 counts as Class C. The proposed invariant I-K1 does not even reach Class C. Classifying them this way makes the D2-prep error (treating D1 as existence evidence) structurally impossible.
4. **L41** Sharpening (i): Class B is not independent of Class A. ⑦ needs a *history*, so no single instance can satisfy it. ⑧ must be split into an invariant that has been *identified* (B) and one that has been *exercised* (A).
5. **L42** Sharpening (ii): the incidental-versus-owned test. Does the acting context own the outcome, or does the outcome fall out of another context's mechanism? The worked examples are CAP-01 bootstrap and BC-7 tokenRef leakage.
6. **L43, L158** Sharpening (iii): monitoring is owned by nobody, so the trigger must be self-evidencing. AMD3 adds the fourth field, AUTHORITY: an observer who has no power to reopen D2 does not close the loop.
7. **L27** The key question is whether one realized instance can establish the capability. The commission forbids both automatic conjunction and accepting a single incidental event.
8. **L29** Establishment is not the same as a bounded context, ownership, full implementation, control-plane status, or BLOCK/HALT.
9. **L98–102** C-5's two-limb existence test must not be imported. This changes the question left open at D2 §6 from "does one limb suffice?" to "what is C-10's own test?" (a change over time).
10. **L87** There are five analysis axes. The authority axis separates recording, asserting, authoritative state, identity attestation and applicability authority.
11. **L137–150** There are three evidence schemes: (i) GOVERNED/OBSERVED/REPRODUCIBLE/INDEPENDENT; (ii) A/B/C; (iii) a measured census of 53, 29, 24, 16, 13 and 10. The commission measured that REPRODUCIBLE and INDEPENDENT appear nowhere. Model-integrity rule: a new dimension needs to be orthogonal, necessary and sufficient.
12. **L162** "A name is not a criterion." E10 is a maturity criterion that sits outside the minimum set but must still be defined.
13. **L49, L63–69** These are process rules about producer eligibility and the S5 lane blocker. They have little theoretical weight beyond the separation principle: an author must never judge satisfaction of its own test. [I]
14. [I] Item ⑦ is listed as Class B evidence, yet (i) shows it cannot be observed at a point in time. The commission's own classes are therefore not orthogonal to time.

## S0117: D5 establishment-criteria proposal (Architecture, process 5e1dd9ee)
1. **L11** Quality rule: a model describing a capability is not evidence that the capability exists.
2. **L30–36** Three things are kept apart: realization, bounded-context existence, and ownership or implementation. A capability can be realized without having a context; a context can be modelled for a capability that has never run.
3. **L46–53, L78–84** The evidence model has four orthogonal dimensions: SUBJECT (A/B/C), EPISTEMIC (Observed, Inferred, Proposed, Decided, Open), PROVENANCE (Governed, Ungoverned, External), and REPRODUCIBILITY. The commission's list is shown to mix three dimensions.
4. **L57–74** REPRODUCIBLE is added: it is orthogonal, necessary and sufficient. INDEPENDENT is refused, for two reasons: it is C-5's subject matter, and requiring it would make C-10 unestablishable, since C-5's organisational independence is not established.
5. **L72** Claim: REPRODUCIBLE "does the work INDEPENDENT was reaching for", because a reproducible observation cannot be self-certified.
   - [I] This is weak. Re-deriving from the recorded evidence still trusts the record, so a producer that writes the record can still certify itself. Reproducibility protects the inference, not the data.
6. **L90–93** D1, although DECIDED, is Class C evidence and advances establishment by zero. A DECIDED semantic is strong on the epistemic dimension and empty on the subject dimension.
7. **L102–214** E1–E10 each have a definition, purpose, evidence, class, classification, satisfaction test and counterexamples:
   - **E1 (L110):** a subset-and-superset test on the receipt's assertions.
   - **E2 (L122):** blocked by D1's conflict between two acts (eight properties versus seven).
   - **E3 (L128–134):** an authority map. NECESSARY when identified, SUPPORTING when exercised.
   - **E4 (L143–144):** SUPPORTING; the owner is not assigned.
   - **E5 (L154):** stratified (creation and acknowledgement; supersession or invalidation; dispute).
8. **L162–168** E6 (two or more independent governed acts) is presented as "the discriminator between owned and incidental".
   - [I] CAP-01 bootstrap delivery repeats on every session, yet is incidental. Repetition does not discriminate ownership; E7's counterfactual does. This is a counterexample within the stated scope.
   - [I] "Independent" in E6 is a homonym of the INDEPENDENT dimension that L67 refuses.
9. **L178** E7 is a counterfactual test: remove C-10; does the receipt still appear? It reuses canonical line 552.
10. **L190** E8 requires that the record be reconstructable, not that identity be attested ("recording identity ≠ attesting identity").
11. **L201** E9 is negative-claim discipline, including organisational independence.
12. **L213, L225** The proposal says E10 "cannot be satisfied at a point in time". From this it concludes that ⑦ is structurally unsatisfiable at establishment, and that Option A (the full conjunction) would leave C-10 "PERMANENTLY UNESTABLISHABLE".
    - [I] The step to "permanently" does not follow. A history can accumulate, so the conjunction becomes satisfiable later. The argument silently assumes that establishment happens at first realization.
13. **L230–244** Option C, assurance levels:
    - **L1, prototype:** E1, E2, E5 (creation and acknowledgement), E7, E8, E9, and E3 (identified).
    - **L2, operational:** L1 plus E6, E5 (supersession or invalidation), and E4 (exercised).
    - **L3, authoritative:** L2 plus E3 (exercised) and E10.
    - D2 should flip at L2, not L1. One instance gives L1, which is a named milestone that does not flip D2.
    - [I] L2 is stricter than AMD2's Option B core; the proposal does not compare the two.
14. **L254–263** The four-field trigger is observer-minimised, not observer-free. It rides on Governance's mandatory reads, and Governance has a *duty* to register a detection while only the PO/ARB may reopen D2.
    - [I] The mandated trigger sentence says "independently reconstructable", reintroducing "independent".
15. **L50 versus S0116 L143** [I] The epistemic scheme (B) adds DECIDED, OPEN and UNGOVERNED, which are absent from the measured census. Whether this is "no fourth scheme" or a re-partition of scheme (iii) is arguable.

## S0118: D1 stabilization act registration (process 5e1dd9ee)
1. **L16** D1 is reframed from "receipt semantics" to "receipt claim boundary stabilization", which is a ubiquitous-language act.
2. **L19** Anti-corruption rule: C-10 must not import BC-1 concepts. Reference ≠ ownership, recording ≠ asserting, correlation ≠ authority.
3. **L20, L30** A new term, "external correlation reference", is recorded only, not attested and not owned. It is neutral about *what* is correlated, which is hygiene against importing BC-1 vocabulary.
4. **L18** Boundary restated from D3: the receipt claim, the acknowledgement record and the receipt lifecycle are inside C-10; applicability, correctness, execution and the delivery mechanism are outside.
5. **L24** Fixed sequence: D1, then E2, then D5, then the D2 revisit (on trigger), then D6, then D7.
6. **L36–37** The act asks for a selection but contains none. Supplying one would be making the decision, not recording it.
7. **L39** A later act is not automatically a supersession, so Act A (a0b61305) remains the DECIDED text. This is a defeasibility rule.
8. **L43–53** S-1: the option labels are *crossed*. The new Option B equals Act A's Option C *headline*; the new Option C equals Act A's Option C *body*. Therefore any selection must be recorded by the proposition's text, not its letter.
9. **L57** S-2: the act says "two interpretations" but lists three options. This is a source defect.
10. **L63** E2 remains blocked.
11. **L5** Disclosure: the registering process authored D5's R-5, which this act takes up. Same lineage as S0117. [I]
12. [I] "Correlation ≠ authority" is the third member of a family of non-equivalences (recording ≠ attesting identity, D3.2). The theory should treat them as one schema, not three facts.

## S0119: D1 consolidation preparation (Governance)
1. **L5–6** There is still no selection. The rule that option letters are invalid cures S-1.
2. **L12** All three earlier D1 records were written by process 4858c37c, which is provenance-conflicted.
3. **L16–18** Recommended dispositions:
   - Act A: its proposition is superseded, the record retained, and its eight properties must be re-recorded.
   - Act B: consolidated and retained as the conflict record.
   - Stabilization act: consolidated.
4. **L24** The referent gap: "applicability reference" is a *required* property, but ContextSelected never occurs and has no owner. So a conforming receipt cannot be populated.
5. **L28–30** Three propositions:
   - P1 (applicability recorded) keeps the gap.
   - P2 (applicability fully outside) closes the gap but contradicts D3.2, which says C-10 MAY record references.
   - P3 (optional, non-authoritative references) closes the gap with no contradiction.
6. **L33** Key structural result: the gap is caused by the reference being REQUIRED, not by its being recorded.
7. **L39–41** DDD evaluation: P1 mixes claims about the world with a claim about the record. P2 over-corrects and cuts traceability. P3 has one meaning, and its references are annotations rather than claims.
8. **L45–46** E2 under P3:
   - Required: P, S, the delivery event, the acknowledgement event, T, and the issuer or evidence basis.
   - Optional: zero or more references, each marked non-authoritative.
9. **L50** Residue 1: knowledge versions K. Its status as a required property is undecided under every proposition.
10. **L51** Residue 2: S cannot be attested (INV-ATTR-1; D4.3 M4 = NOT ATTESTED). It must be marked RECORDED, NOT ATTESTED. The problem is sharper under P1, because "acknowledgement occurred" names a subject the platform cannot bind.
11. **L55–57** Correction by events: DEC-D5 §2 said "no D5 artifact exists". That was true when measured and is superseded by 10fbfb05. The change is additive, not a withdrawal.
12. **L59** The D5 proposal's E2 must be reconciled with whichever proposition is selected.
13. **L67–71** Recommended selection text, with its claims and non-claims (non-claims include organisational independence).

## S0120: the single C-10 decision register (D2, D3, D4.1, D4, D4.3, D5, D5-AMD1, D1 final)
1. **L11–21** D2 = NOT YET ESTABLISHED, which is not NO. The hypothesis is not rejected; this records its epistemic status.
2. **L15** The PO/ARB reasoning lists "C-10-owned *applicability* … activity" as unrealized.
   - [I] This contradicts D3.2 (L103), which places applicability determination outside C-10. The decided basis of D2 uses a boundary that D3 later rejects.
3. **L27–40** Self-correction by Governance. C-5's YES rested on two conjunctive limbs: (1) an ADOPTED invariant and (2) the capability having been PERFORMED. C-10 meets neither: I-K1 is only PROPOSED, and four of five activities never occur. "A decided semantic *about* an artifact ≠ a performed capability."
4. **L46** D1 is "a decided semantic awaiting a subject". It is coherent, but it is not evidence of existence.
5. **L48** OQ-J: a receipt carries at most two of five claims.
6. **L69–72** Revisit thresholds: adopting I-K1 or the authoritative state would supply limb 1; one performed instance would supply limb 2. Whether one limb is enough is OPEN.
   - [I] L72 reads "either limb alone was enough for C-5 only because C-5 had both". This is self-contradictory: no case with only one limb was observed. AMD1 (S0116 L98) later voids the import of the C-5 test (a change over time).
7. **L92–113** D3 = Option C, SPLIT.
   - Inside the boundary: receipt identity, lifecycle state, supersession or invalidation, and completeness at the delivery or acknowledgement point.
   - Outside: applicability, transport, execution, identity binding, dispute adjudication and compliance.
   - Recording ≠ attesting, for both identity and applicability. A capability may not own authoritative state it cannot establish.
8. **L127–137** Non-constitutive clause: candidate boundary ≠ existence ≠ realization. D3 is a specification, not an establishment. This closes the back door: adopting receipt state would otherwise have supplied limb 1.
9. **L141–159** Lifecycle candidate: ISSUED, VALID, SUPERSEDED, INVALIDATED, then EXPIRED or DISPUTED.
   - INVALIDATED has no owner or authority (L157).
   - The acknowledgement point is not stated to be START, unlike I-K1's "before an act begins" (L159). This is OPEN.
   - "Dependencies are not ownership" (L153).
10. **L188–209** D4.1 sets out the effects INFO, WARN, BLOCK and HALT+ESCALATE. A compensating control is an *exception mechanism*, not a fifth effect; it must preserve the unresolved condition and must not convert NOT ATTESTED into PROVEN or COMPLIANT.
11. **L213–219, L408** No-laundering: an unresolved status may never become a resolved one. The register counts this in three, then four, places, kept as an observation and not promoted. It is distinct from §8.3, which is about self-assertion.
12. **L223–227, L335** Category by consequence: mapping any condition to BLOCK or HALT makes C-10 control-plane (it "gates START"). No mapping means C-10 is advisory and its category is undecided.
13. **L233–234, L292–295** MISSING is 100% of cases, because there are no receipts. Any BLOCK on it would halt the estate. The gate cannot bind a receipt to a session.
14. **L273, L287–317** D4 sets a target, not enabled enforcement. Vocabulary ≠ enabled set. The floor is WARN+RECORD. There are four prerequisites (state, ownership, identity binding, triggers). Tiers are explicitly not to be invented.
15. **L376–386** DEC-D4.3 adds five prerequisites; the fifth is "within C-10's authorized boundary", which formally excludes M3 and M5.
16. **L404–410** M4 = NOT ATTESTED, never MISSING and never a valid receipt. The bar runs both ways. It is a *classification* rule; no effect is attached.
17. **L392–400** SUPERSEDED ≠ STALE: STALE must not be inferred from a newer version alone. DISPUTED is orthogonal to the lifecycle and is not a terminal state.
18. **L456–518** DEC-D5 adopted E1–E9 plus E10 (maturity) as names only.
    - There was no artifact, no lane (27 transitions), and eight of the nine names appear nowhere.
    - The combination and the trigger are absent. "An undefined name is not a satisfied definition."
19. **L546–616** D5-AMD1: the framework is adopted provisionally and its provenance is pending.
    - "A good model is not the same as an authorized deliverable."
    - Options A, B and C are all left open (B's core is E1, E2, E6, E7, E8, E9). There is a three-part trigger. The commission stays open (Reading 2).
    - "A NAME IS NOT A CRITERION."
20. **L642–667** The final D1 selection text uses "claims … delivery occurred and acknowledgement was *recorded*": the shift from "proves" to "claims", and from the world to the record. "Identity binding was established" becomes an explicit non-claim.
    - [I] "Acknowledgement was recorded" moves part of the claim onto the record itself, close to self-reference. The receipt's own record could then satisfy it trivially, which E1's superset test would need to exclude.
21. **L679–689** The referent gap closes at the level of the claim, consistent with D3.2. K and the attestation status of S stay OPEN.
22. **L638** The registrar read a heading that says "RECOMMENDED" as a SELECTION on four structural grounds, and flagged that reading for correction.
23. [I] Trivial-model check: every D-entry is a "must-never" or a non-decision. C-10 has no command that is decided, so the empty mechanism satisfies the whole register, consistent with NOT YET ESTABLISHED. The theory must not read the register's internal consistency as content.

## Cross-source table (chronology and drift) [I]

| Point | S0116 | S0117 | S0118/S0119 | S0120 |
|---|---|---|---|---|
| Order of D5 | commission open, START not performed | proposal delivered: lane seq 28–30, 10fbfb05 18:24 | S0119 L56 corrects "no artifact" | DEC-D5 adopted names *before* the proposal, then AMD1 made them provisional; the D1 final marks the proposal delivered and adoption pending |
| E3/E4 names | — | "Claim authority model" / "Receipt state integrity" | — | "Claim authority decision" / "Write protection" (L465): label drift |
| C-5 two-limb test | do not import (L98) | does not import | — | D2 §3/§6 relied on it; superseded in form |
| Acknowledgement claim | ② "acknowledgement actually recorded" | E1: delivery + acknowledgement + time | P3: "acknowledgement occurred" | final text: "acknowledgement was recorded" (E1 needs re-reading) |
| "Independent" | scheme (i) value | refused as a dimension, yet used in E6 and in the trigger | — | — |
