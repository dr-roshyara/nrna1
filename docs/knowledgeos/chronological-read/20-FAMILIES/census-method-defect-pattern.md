# census-method-defect-pattern

**Scope(s):** THEORY-LEVEL · **Row count:** 16 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1113** [`architecture-baseline-001` · `census-method-defect-pattern`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- **G1119** [`census-method-defect-pattern` · `recordedby-referent-ambiguity`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1120** [`bc7-domain-model` · `census-method-defect-pattern`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0002, scope THEORY-LEVEL: The recurring defect class of a measured figure stated without its selection method or measurement point, found repeatedly across work items.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0054 §"D-1 · Hook count wrong: Phase A says 8, actual is 10 ... the total was eyeballed rather than computed"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S0074 §"Correction 1 ... 20 instances ... Correction 2 ... DP-6 replacement instruction completed to all three cells ... Correction 3 ... OQ-10 extended"]
- CANDIDATE-GOVERNANCE-BIRTH: [S0076 §"1. Accept BC-7 correction. 2. Record F-1 and F-2 as knowledge integrity findings. 3. Do not reopen architecture model. 4. Route measurement provenance issue to future KES governance work. 5. Keep independence limitation recorded."]

## Lifecycle
last_seen: S1398. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0075 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0054, S0055 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0055, S0056, S0075 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0075] (ANALYSIS) A recurring defect class (a measured number stated without its selection method) is named as having now recurred three times across two work items, recommended as a candidate standing rule for future KES/Governance work: a census must state its selection rule and measurement point or it is not evidence.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S0054] types=['CORRECTION'] scope=OBJECT — "D-1: Phase A's high-confidence Observed claim of 8 wired hooks is wrong; the actual count via a JSON-parsing method is 10 (PreToolUse 5, PostToolUse 2, SessionStart 1, Stop 2), the original figure having been eyeballed rather than computed, with settings.json unchanged since the baseline commit ruling out temporal drift." (anchor: "D-1 · Hook count wrong: Phase A says 8, actual is 10 ... the total was eyeballed rather than computed")
- [S0054] types=['CORRECTION'] scope=OBJECT — "D-2: the claim that four of seven components have no executable substance is false (the actual figure is two of eight zero-asset components) and self-contradicting, since Phase A's own detail table already correctly marks two other components PARTIAL rather than NONE." (anchor: "D-2 · 'Four of seven components have no executable substance' — false, and self-contradicting ... Phase A's own §3.1 table already says this")
- [S0054] types=['PRINCIPLE'] scope=METHODOLOGICAL — "The producer states a governing principle for its own self-review: a high-confidence Observed measurement that is wrong is worse than an honestly acknowledged unknown." (anchor: "A wrong observation is worse than an acknowledged unknown.")
- [S0054] types=['PRINCIPLE'] scope=METHODOLOGICAL — "The self-review method is stated as deliberate falsification-seeking: re-deriving each original claim using a measurement method different from the one originally used." (anchor: "each Phase-A claim was re-derived using a different measurement method than the original, deliberately seeking falsification.")
- [S0055] types=['WARNING'] scope=OBJECT — "V-A (new finding): the same document's own §6 diagram already sums to 10 hooks while its caption says 8, a same-page self-contradiction of D-2's exact shape, showing the correct number was already present inside the document." (anchor: "V-A · Phase A §6's own diagram sums to 10 while its caption says 8 ... it upgrades D-1 from miscount to internal inconsistency")
- [S0055] types=['PRINCIPLE'] scope=METHODOLOGICAL — "The verification method deliberately uses a third, independently different measurement method than both the original and the producer's self-review, and attempts falsification on load-bearing claims rather than confirming formatting." (anchor: "Method: every checked claim re-derived by a method different from both the original and (where known) the producer's self-review method. Falsification attempted on the load-bearing claims")
- [S0056] types=['WARNING'] scope=OBJECT — "N-1 (new defect): the corrected document's own title line still says v1.0 while its version banner says v1.1, exactly the same self-contradiction shape the correction round was convened to repair, though it changes no conclusion." (anchor: "N-1 (MODERATE) — same class as the defects repaired ... Line 1 is still v1.0 ... line 6 declares v1.1")
- [S0073] types=['CORRECTION'] scope=OBJECT — "Verification #2 falsifies one of the refinement's own high-confidence Observed measurements (claimed 0 non-canonical keys; actual 20, e.g. `note` on 16 COMPLETE transitions the mechanism never reads), while judging the correction strengthens rather than weakens the T-1b invariant it was meant to support." (anchor: "'Non-canonical keys anywhere in the estate: 0' is wrong; there are 20 ... the falsified measurement in fact strengthens T-1b.")
- [S0074] types=['IMPLEMENTATION'] scope=OBJECT — "Three mechanical corrections are delivered against Verification #2's R-1/R-2/R-3: the false non-canonical-key measurement is replaced with a dated 20-instance census, the DP-6 replacement instruction is completed to all three table cells, and OQ-10 is extended to record the recordedBy-referent ambiguity as a question only." (anchor: "Correction 1 ... 20 instances ... Correction 2 ... DP-6 replacement instruction completed to all three cells ... Correction 3 ... OQ-10 extended")
- [S0075] types=['VALIDATION'] scope=OBJECT — "Verification #3 confirms all three of the correction's own repairs (R-1/R-2/R-3) as accurate and re-derived from primary evidence, and that the change boundary held (all hunks fall inside the four declared sites, nothing reaches BC-7 ownership, the aggregate, ADR-AIP-03, or CAP-14)." (anchor: "EXECUTIVE VERDICT — VERIFIED WITH NOTES ... All three corrections (R-1, R-2, R-3) are accurate")
- [S0075] types=['CORRECTION'] scope=OBJECT — "F-1 (minor): the correction's own delivery note miscounts its diff (claims 5 removed/14 added; git shows 16 insertions/5 deletions), traced to a grep-based method silently dropping two added blank lines; measured as LOW impact with no concealed semantic change." (anchor: "F-1 ... the delivery note's own diff metric ('5 removed · 14 added') disagrees with git, which reports 16 insertions / 5 deletions.")
- [S0075] types=['WARNING'] scope=OBJECT — "F-2 (minor): the correction's cited '6 of 37 STARTs' figure for the recordedBy-referent ambiguity cannot be independently reproduced (a plain reading yields 14 of 38), its selection rule being unstated, though the underlying conclusion is unaffected because it rests on separately confirmed facts." (anchor: "F-2 ... R-3's '6 of 37 STARTs' is not independently reproducible. ... a plain reading gives 14 of 38.")
- [S0075] types=['ANALYSIS'] scope=THEORY-LEVEL — "A recurring defect class (a measured number stated without its selection method) is named as having now recurred three times across two work items, recommended as a candidate standing rule for future KES/Governance work: a census must state its selection rule and measurement point or it is not evidence." (anchor: "Third recurrence of the method-dependent-count defect (V-D → R-1 → F-1/F-2). Repairing instances one at a time has not stopped it")
- [S0076] types=['GOVERNANCE'] scope=OBJECT — "The PO/ARB accepts Verification #3 (VERIFIED WITH NOTES) via five explicit acts: accept the correction, classify F-1/F-2 as knowledge-integrity (not architecture) findings, decline to reopen the architecture model, route the recurring measurement-provenance defect to future KES governance work, and keep the independence limitation on permanent record." (anchor: "1. Accept BC-7 correction. 2. Record F-1 and F-2 as knowledge integrity findings. 3. Do not reopen architecture model. 4. Route measurement provenance issue to future KES governance work. 5. Keep independence limitation recorded.")
- [S0078] types=['IMPLEMENTATION'] scope=OBJECT — "The refinement document's own banner records that it was subsequently corrected under a separate grant to apply exactly Verification #2's R-1/R-2/R-3 findings and nothing else, with the correcting pen disclosed as the same process that authored Verification #2 itself." (anchor: "Three corrections applied under G-KOS-ARCHBASE3-CORRECT ... implementing Verification #2's R-1/R-2/R-3 and nothing else")
- [S1398] types=['VALIDATION'] scope=OBJECT — "B-3: substantiates the chapter's 'fourth sighting' census of a recurring governance-gap failure family (Step 121 audit, CON-06 freeze practice, F-1, CF-003) as contemporaneously recorded, not retrospectively assembled, cross-checked against archaeology finding AF-008 confirming sightings 1-2 historically independent of 3-4." (anchor: "The four members and provenance: (1) Step 121 corpus audit... (2) CON-06 freeze practice... (3) F-1 (3B, synthesis); (4) CF-003 (3C, repository...) Family membership is NOT retrospective")

## Notes for P3
None beyond what is recorded above.
