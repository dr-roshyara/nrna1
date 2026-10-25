C29 STEP 2-3: REVIEW REPORT

Reviewer W2 (Opus). Before comparing, I re-read my saved inventory (sha256 3f6d7a57…db05, matches). One limit up front: the book changed while I was reviewing. Commit `57785a947` landed at 01:23Z and changed §9.13.2, §9.15 K2-d, §9.17 R1/R2/R3(c)/§9.17.5 and the register. I reviewed its §9.17 diff only for what C2 uses (W-12). The rest of that change needs its own review.

## Summary
1. §8.22 is mostly sound as a record of agreement with the book, but **the window's headline relation is wrong**. The incumbent's output already meets CON-0257 as stated: its information does not come from the new engine and the replacement cannot rewrite it. What S0154 actually shows is that the reference must *be the intended state*. That is CON-0256 (i), which was dropped when CON-0256 was restated as CON-0257 (W-01).
2. C4's "falsifies specificity" fails. The AMD6 row was unreviewed (S0152 L164), and the book itself records that the quiet row stayed silent on DV-7 (W-03).
3. C3 restates "by nothing", which §8.20 C12 already corrected (W-05). The RD-* tension does bear on C4 (W-04).
4. C5's "no section states this" is false: this is the identity question T0-03 (W-06).
5. C11 reads as agreement what is really a contradiction with §1.16.1: S0158's consumer checks, then acts (W-07).
6. C9's "never" fails on event sourcing, which S0158 itself names (W-08).
7. S0152 and S0158 contradict each other on whether a mechanical PASS skips architecture review (W-09).
8. No finding is critical. Applying the R5 calibration, items with nothing depending on them are rated high or normal.
9. Largest risk: "second chain for CON-0257" inflates support for a PROVISIONAL concept at the next consolidation.

## Findings (ranked)

| W-nn | Finding | Sev. · type | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C7 is not a second chain for CON-0257.** (a) The PHP output originates independently of the Python output and the act cannot rewrite it, so CON-0257 is met by the incumbent too. (b) The distinguishing condition is "the reference is exactly the intended state": CON-0256 (i), lost in the restatement, and R4's "exact" premise. (c) R4/CON-0265 is about a record moving between carriers; replacing an engine is not a carrier change. (d) The boundary the claim needs: the specification is complete and decidable (it fixes which methods and edges LCOM4 counts). Otherwise the incumbent is the de facto specification for the open choices [I]. | high · defect | §8.21 C1 (CON-0256 → CON-0257); §9.17 R4 Setting; S0154 L497–505, L584 | Rehome C7 under "reference = intended state" (R4 exactness or a restated CON-0257 clause). State that CON-0257 is necessary, not sufficient. Remove "second chain" from 08.00-index L36 and THEORY-MAP L190 (R3 run: these are the only inbound lines). | C7 no longer cites CON-0257 or CON-0265 as its home; map and index updated; CON-0257's follow-up notes the missing clause |
| W-02 | **Spec-not-incumbent is the recorder's gloss on an unquoted act.** S0154 L582–584 is the recorder's "read precisely". The adviser's own plan was differential testing against the incumbent ("PHP LCOM4 ≟ Python LCOM4", L497–505). The adviser's RECORD said "against the accepted conformance evidence model" (L541–543). So C8's "kept apart" holds for the language only. L584's "would not satisfy" confuses evidence of conformance with conformance: an engine that reproduces a conforming incumbent does conform. | high · defect (evidence) | S0154 L497–505, L541–543, L582–584 | Label L584 as the recorder's reading (C23 rule). Add the change from the adviser's text to the act. State the result as "matching the incumbent is not evidence of conformance". | C7 and C8 attribute L584 to the recorder; the change over time is recorded |
| W-03 | **C4's "specificity" is not falsifiable by the quiet row.** S0152 calls the AMD6 chain "unreviewed" (L164), so an alarm there cannot be classed false. §8.20 C12 (W-09) records that the quiet AMD6 row was silent on DV-7, a defect in the checker's own class. | high · defect | S0152 L104, L164; §8.20 C12 | C4: the back-test falsifies reach on the listed findings. The quiet row is evidence only within the implemented scope and against a validated key (CON-0253's last sentence). | C4 restated |
| W-04 | **RD-* bears on C4.** S0152 puts RD-* among the 17 findings to be rediscovered (L102), and G-2 says RD-1, RD-7 and RD-3·b are caught only by humans (L132). §8.20 C12 (W-08): RD-7·a *was* found as DI-5, and "by NOTHING" holds only for acts referenced nowhere. For uncited missing acts reach is zero by construction. So the exit criterion is unsatisfiable unless the 17 exclude them, and S0152 does not say which. The C4 challenge record's boundary ("then also tests reach for that class") is therefore **inadequate**. C4 also omits that the experiment was later run narrowed without a record (§8.20 C12: 5 classes, not 17). | high · omission | S0152 L102, L132; §8.20 C12 W-08 | Add both points to C4; fix the challenge record | C4 cites §8.20 C12 W-08 and the narrowing |
| W-05 | **C3 restates an unscoped claim the book has already corrected.** It quotes "reduces missing-act defects by nothing" (L138) and equates it with §8.20 C12/C13, which scoped it to acts referenced nowhere. "Dangling references to zero" is a design claim for a checker not yet run. | high · defect | S0152 L138; §8.20 C12 W-08 | Scope both quantifiers | C3 carries the scope |
| W-06 | **C5 has existing homes.** §0.2/T0-03 (KnowledgeOS called "the reusable engineering platform", S0008 L22; the S0032 mission); §0.8 (Engineering Platform and Project Knowledge as sibling contexts, PKSM); §1.11 (two subjects, S0011); §1.14.3-4 (a plane, not a bounded context). S0153 **conflicts** with S0008: it puts the platform outside KnowledgeOS. The §8.22.3 open question duplicates T0-03 and is mis-posed, since the kernel theory is built largely from the platform's own records. | high · defect | grep hits listed above; S0153 L541–583 | Route C5 to T0-03 as a conflicting framing; drop the new open question | T0-03 cites S0153; §8.22.3 updated |
| W-07 | **C11 records agreement where there is a contradiction.** S0158's idempotent consumer checks "Already processed?", then processes, then records the receipt (L485–501). §1.16.1 rejects exactly that order: "a check performed before an effect is not deduplication; it is a race" (DE L352). For identity, the closer home is §1.16.6 ("identity must include the version of what was read", DE L368); content-derived identity merges two genuine emissions with equal content. The outbox makes only state plus outbox atomic, within the producer; consumers lag. | high · defect | S0158 L485–501; §1.16.1; §1.16.6 | Restate C11: contradiction recorded; homes corrected; outbox scope stated | C11 restated |
| W-08 | **C9's "an event is not the record" is too broad.** Under event sourcing ("Selective", S0158 L662) the log *is* the record. By §9.17 R1, which store is the record is set by designation. | high · defect (nothing depends) | S0158 L662; §9.17 R1 | Restate: "an event stream not designated as the carrier is a projection" | C9 restated |
| W-09 | **Cross-source contradiction (missing).** S0152 sends every assurance result through human architecture review (L37–60; G-2, G-3). In S0158, AssurancePassed goes to Governance and then to KnowledgeApproved with no architecture review (L337–350). An undeclared act yields PASS (G-2), so S0158's two-way routing lacks the case "silent U reported as PASS", which is S0152's top risk. C10 holds only for unknowns the checker knows it has. | high · omission | S0152 L37–60, L132–133; S0158 L331–350 | Add to C10 | C10 scoped |
| W-10 | **C2: homonym, and no owner for the invariant.** "Root resolution" in S0152 is a lint scope that also reads earlier commits of the plan. "Resolution" in §9.17 is location plus predicate of the record. K2-r covers only reads of the record. S0152's `--root` parameter is CON-0239's "own default or parameter" hazard, and nothing enforces "follow". | normal · defect | S0152 L100–102, L151; CON-0239 | Qualify the term. Name the enforcing operation: resolve by following designation entries from the root designation, which `57785a947` now makes possible. | C2 restated |
| W-11 | **§9.17 R2.** (a) The K1-only witness, "a cut of the altered ρ", merges record and carrier: removing an entry from the carrier does not change ρ, so a full reader breaks K2-r too. Repaired model: the removed member lies beyond every reader's cut. Independence survives. (b) Old-K2 "across a carrier change … R4 checks this" hides a premise: faithful carry-across, while R4 misses C3 losses and the interval. That part is a sketch, not a proof; within one designation the derivation is adequate. | normal · defect | §9.17 L96, L107 | Fix the witness; scope the sentence | table and sentence revised |
| W-12 | **`57785a947` leaves inconsistencies.** §9.17 L65 still says "That is open (§9.17.5)", against the new bullet and "Decided". The append of the designation entry itself is unspecified: which carrier receives it, and which designation is in force at that moment (K2-w refers to itself). The commission said no kernel section changed. | normal · defect | `git show 57785a947` | Delete L65; state where the designation entry is appended; review the commit | done and reviewed |
| W-13 | **C1: the S0149 skim gap can now be closed.** I checked that the bodies are identical (808 lines; hashes match CORPUS-ALLOWED). Points not in §8.21 C13: "AI is a capability, not the core domain" (L415–417, a kernel/application point); the ACTIVE invariant has no single owner (L127, L192, L211–219). Of the window's 3,322 lines, 808 duplicate S0149's body. | normal · omission | S0151 L415–417, L127, L192 | Add to C1 or §8.21 C13; note in the measures | added |

## Concept verdicts (claims; there is no new concept)

| Claim | Verdict | Reason and scope |
|---|---|---|
| C1 | VALIDATED | body identity verified; index order is not a count |
| C2 | PROVISIONAL | W-10 |
| C3 | PROVISIONAL | W-05 |
| C4 | PROVISIONAL (reach) / INVALID as stated (specificity) | W-03, W-04 |
| C5 | INVALID as stated | W-06 |
| C6 | VALIDATED | as recorded agreement |
| C7 | VALIDATED: the deduction "matching is not conformance", for a complete, decidable spec · INVALID: the CON-0257/CON-0265 link | W-01, W-02 |
| C8 | PROVISIONAL | W-02 |
| C9 | INVALID as stated | W-08 |
| C10 | PROVISIONAL | W-09 |
| C11 | INVALID as stated | W-07 |
| §9.17 R1; K2-r ⇒ old K2 within one designation | VALIDATED | concur with A and B |
| K2-w / K2-r / K1 independence | VALIDATED | with the repaired K1 witness (W-11) |
| CON-0265 | PROVISIONAL (unchanged) | anchor_model v2 re-run: output identical to the saved run (a passing model is not a proof) |

**Settlement spot-check: 10 rows checked, all consistent with §9.17 and the register.** Rows checked: A-01/B-03, A-03/B-02, A-02, A-05/B-04, B-01, A-06/B-06, A-07, A-08/B-09, A-10, A-12. Also consistent: B-08 against CON-0251's meaning, and §9.15 L74, which no longer cites CON-0263. Register recount: 265 concepts (45 VALIDATED / 124 PROVISIONAL / 96 INVALID), as stated.

**R5 calibration.** Agreed. W-08 has no dependants and is rated high, not critical, for that reason.

## Coverage against my inventory (only gaps listed; everything else COVERED or JUSTIFIABLY EXCLUDED)

| Item | Status |
|---|---|
| S0152-3 (G-2), S0152-7 (back-test) | DISTORTED (W-05, W-03) |
| S0152-12 (RD-*) | MISSING (W-04) |
| S0153-6 (domain vs platform) | DISTORTED (W-06) |
| S0154-6 (spec vs incumbent) | DISTORTED (W-01, W-02) |
| S0154: the adviser's differential-testing plan | MISSING (W-02) |
| S0158-6, -9 | DISTORTED (W-08, W-07) |
| S0152 vs S0158 routing | MISSING (W-09) |
| S0151-5b (AI is a capability), S0151-2 (owner) | MISSING (W-13) |
| S0158-2 ("EvidenceQualified ≠ approved"), S0158-11 (producer owns the fact, consumer owns the reaction) | MISSING (normal; homes §8.17 and §1.16.1) |

**New fork:** whether the incumbent may serve as the specification for choices the specification leaves open (W-01 d).

**Source defects, not relied on:**
- S0154 L476–481 against L133;
- S0152 L151 "single scope gap";
- S0153's evidence is n = 1, against its own L651.

## Next steps (dependency order)
1. W-01 and W-02: restate C7, then update the map and index lines.
2. Fix C4 (W-03, W-04), then C3 (W-05).
3. Route C5 to T0-03 (W-06).
4. Restate C9, C10 and C11 (W-07 to W-09).
5. W-10 and W-13.
6. Review `57785a947` in full, including W-11 and W-12, before §9.17 is cited again.

No question for the Principal is needed.

## Limits
- I did not open the Ch. 0–2 basis sources. §0.2, §0.8, §1.11, §1.14.3-4 and §1.16 were read only through grep lines and short excerpts.
- §1.14.5-1 and §1.9 were not opened.
- S0153 item 5 (separation of duties) was not checked against §8.06 or §8.09.
- The "17" count cannot be checked from S0152.
- `57785a947` was reviewed only as a diff of §9.17.

Independence is process independence, not personal independence: the same model family as the Professor, a different session, and a blind step 1.

## Appendix
- S0158 uses "Kernel" (L177, L600) without definition; it is a homonym of the book's kernel.
- S0151's "should be event-driven" (L632) against S0158's "not redesign around events" (L18) is a change over time that the book does not record.
- The bounded-context set drifts from S0151 to S0158 (Execution added; Intelligence and Admin dropped). Recording it only is justified.

## Files opened
- W/03-methodology/REVIEWER-INSTRUCTIONS.md (step 1)
- W/06-theory/manuscript/review/C29-S0151-S0158/BRIEF-W-STEP1.md (step 1)
- W/06-theory/manuscript/review/C29-S0151-S0158/BRIEF-W-STEP2.md
- W/06-theory/manuscript/review/C29-S0151-S0158/STEP1-INVENTORY.md
- W/06-theory/manuscript/review/C29-S0151-S0158/STEP8-AND-CHECKS.md
- W/06-theory/manuscript/chapter_08/08.22-a-replacement-conforms-to-the-specification-not-the-incumbent.md
- W/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/SETTLEMENT.md
- W/06-theory/manuscript/chapter_09/09.17-the-designated-record-and-its-anchors.md, plus `git show 57785a947` for it
- Excerpts by grep/sed:
  - W/06-theory/CONCEPT-REGISTER.tsv (CON-0239, 0243, 0251, 0253, 0256, 0257, 0259, 0261–0265; status recount)
  - THEORY-MAP.md L190
  - 08.00-index.md
  - 08.17 (C8, L73)
  - 08.20 L143–200
  - 08.21 L43–66 and the C13 lines
  - 01.16 L5–30 and L119
  - 09.15 L74–75
  - 09.16 (grep)
  - 09.13 L48
  - RESEARCH-PURPOSE.md L1–60
  - 00.08 L1–30
  - grep hit lines in 00.02, 00.04, 01.11, 01.14, 09.08 and earlier review files
- W/tmp/tools/inbound_citations.py (header; run on §8.22 C3, C4, C5, C7, C9, C11)
- anchor_model.py (grep; re-run, output saved outside the repository in my scratchpad, then compared with anchor_model-run-20261024-v2.txt)
- docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (grep)
- Corpus:
  - S0149 `docs/knowledgeos/brainstorming/20260819-204431-kos-ddd-architecture-review-v3.md`: on the allowed list, line 1509; read by a script for the body comparison, content not displayed
  - S0151–S0154 and S0158 (hashed; read at step 1)
- Not opened: BRIEF-W-STEP1-WITHDRAWN.md and ACCESS-INCIDENT.md (their names appeared in a directory listing only). Nothing edited.
