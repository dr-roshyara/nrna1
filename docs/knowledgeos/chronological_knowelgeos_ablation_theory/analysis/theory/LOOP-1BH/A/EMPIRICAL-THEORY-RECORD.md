# Empirical Theory Record (R3): **PROVISIONAL, pending R2**

| | |
|---|---|
| Status | **DRAFT, PROVISIONAL.** It consolidates reviewed results only. **No new evidence, codings or interpretations.** Every item is same-family (Claude), and so is this record (G02). The non-Claude Gate 1 coding (R2) is pending |
| Levels | **L0** = what a source text records verbatim. **L1** = established by a disciplined method (A′ coding, conformance analysis, sealed captures). **L2** = derived from L0/L1 by reviewed analysis (identifiability results, constraints) |
| Fields | each item has `id · statement · level · section · sources · R2_DEPENDENT (+ reason) · confidence`. Confidence is copied from the source and never raised; "not stated" means the source gives none. Full field values are in `EMPIRICAL-THEORY-RECORD.json` |
| Conventions | NOT-EVIDENCED-IN-CAPTURE replaces any "did not occur". Times are **recording times** unless the source gives a decision date (M-TIME, G05). A norm is never read as an act (R-A1) |
| Not used as a source of facts | `CONTINUOUS-REPORT.md` (context only) |

R2_DEPENDENT = **yes** when the item rests on A′ codings that R2 re-codes. It is **no** when the item rests on verbatim text, a mechanical capture or a human act.

---

## A. Observed (L0). Verbatim; confidence = SOURCE_FACT; R2_DEPENDENT = no (R2 re-codes spans, it does not change them)

| id | Statement (quotes verbatim; emphasis stripped as in the sources) | Sources |
|---|---|---|
| A01 | R-72 (dated 2026-08-02) authorizes WP-4B "provided its authorization package demonstrates executable scope and no unresolved governance blockers remain. THAT PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN" … "when they land, engineering proceeds RED → GREEN → VERIFY → acceptance evidence". It also says: "WP-4C AND WP-4D ARE EXPRESSLY NOT AUTHORIZED. Each is to be authorized from IMPLEMENTATION EVIDENCE rather than roadmap intent, after its predecessor is accepted." | RECORDS.json R10; CONFORMANCE-ANSWER.md C1 §1, C2 step 1 |
| A02 | R-77 (2026-08-03): "R-73–R-76 stand · WP-4B's authorization (R-72) stands · … WP-4B REMAINS BLOCKED: … IT DOES NOT GIVE `ContestedOutcomeRef` AN INBOUND PATH". R-76 (2026-08-03): the scope ambiguity "IS CLOSED" | CONFORMANCE-ANSWER.md C1 §1 |
| A03 | Commit 6a67da5d7: "Date: 2026-08-03 16:10:37 +0200 / Subject: test(adjudication): WP-4B RED -- the conclude-to-issue seam and its redrive". Its message says "GREEN not attempted: three producers are absent and the two Board acts are outstanding" and cites "approved decision (R-72 authorization, R-76 scope)". The span has no Author line | RECORDS.json R11; CODING.json R11 notes; CONFORMANCE-ANSWER.md C1 §3 |
| A04 | R-79 (2026-08-03; heading "WP-8 DEFERRED PENDING CLOSURE OF §WP-4 — … Directive · ARB"): "WP-8 MAY BE PLANNED AND MUST NOT BE STARTED"; "It authorizes nothing"; "ENGINEERING IS READY AND NOT EXECUTABLE". R-80: "authorized read as executable (R-72's unmet proviso)" | RECORDS.json R12; CODING-REVIEW.json corrected R12; CONFORMANCE-ANSWER.md C1 §1 |
| A05 | R-86 (2026-08-04): "Decision Authority confirms and adopts R-81 through R-85 without amendment"; "These become governing rulings effective immediately"; "engineering is AUTHORIZED to execute R-81's per-process isolation, R-84's §12 reconcile …"; "Engineering is authorized to resume implementation …"; "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it." | RECORDS.json R02, R36; CODING.json R02 status; CONFORMANCE-ANSWER.md C1 §1, C2 step 3 |
| A06 | R-89: "WP-4C-1 AUTHORIZED TO PLAN AND IMPLEMENT — … ARB CHIEF (PREPARED)" and "PROVENANCE: ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED … this ruling awaits the Decision Authority". It cites "(R-88)" and "R-88's adoption was a SINGLE ACT", and says "§WP-4 remains OPEN". R-88 cites "Per R-86 and R-87". The R-90 withdrawal quotes the Authority: "R-89 already governs authorization" | RECORDS.json R05; CODING.json R05 notes; CONFORMANCE-ANSWER.md C2; IDENTIFIABILITY.json P7 |
| A07 | **Recording** times: R-77 15:28:34 and R-79 15:47:45 on 2026-08-03; R-87 01:47:00, R-88 01:54:24 and R-89 02:08:48 on 2026-08-04. R-80 has no timeline entry | CONFORMANCE-ANSWER.md C1 §2, C2 step 2; CONFORMANCE-REVIEW.md §3 |
| A08 | R-90: "Then R-90 itself withdrawn before adoption, because the Authority ruled it was operational acceptance rather than a constitutional decision — the register holds constitutional decisions." "THE NUMBER R-90 IS RETIRED AND MUST NOT BE REUSED." R-91: "HELD 2026-08-04 — NOT ADOPTED, AND NOT ELIGIBLE FOR ADOPTION AS ISSUED …" | RECORDS.json R03, R06, R25 |
| A09 | R-94 (heading names the ARB CHIEF): "superseding would blur chronology"; "ANNOTATE is adopted."; it records "Executed at IMPLEMENTATION_PROGRESS.md:58". It carries no PREPARED tag | RECORDS.json R29, R30; CODING.json R30 notes; IDENTIFIABILITY.json P2 |
| A10 | Slice-7 texts. R-47: "First authorized activity: RED at 7A"; "7B and 7C are NOT authorized and follow their own gates". R-56 (a recording note marked not part of the ruling): "Execution of 7B is a SEPARATE act and has NOT been issued". R-58: "Engineering is authorized to begin Slice 7B RED on keystones K1–K10 …"; "Slice 7C remains unauthorized". R-65: "Slice 7C AUTHORIZED; engineering may begin RED" | RECORDS.json R13, R14, R31–R34; CODING.json R31 notes |
| A11 | Execution reports in register and log text, all **uncoded**: "Executed at IMPLEMENTATION_PROGRESS.md:58"; "Container resolution executed and reproduced"; the grep in R-83; "first checklist execution the same day caught a second instance" (R-41); R-86 "engineering waiting solely on a constitutional act"; S0804 "Nothing implemented this turn by the review's own instruction" | IDENTIFIABILITY.json P2, P6; STATE.md |
| A12 | The other A′ spans (R-81 annotation, R-60, R-36 matrix rows, R-41, R-77 on ADR-T14, R-83 on §12, S0815 "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one.") are recorded verbatim and not restated here | RECORDS.json R01, R08, R09, R15–R23, R26, R27 |

## B. Reconstructed (L1)

| id | Statement | Sources | R2 | Confidence |
|---|---|---|---|---|
| B01 | 36 A′ span records were coded. R24, R28 and R35 have NO SOURCE SPAN AVAILABLE. The review found 0 violations of R-A1..R-A3, no suppression and 25 corrections. Reviewer datasets: ACT n=26, NORM n=31, overlap 24. 13 records are flagged uncertain | CODING.json; CODING-REVIEW.json datasets_reviewer, uncertain; SUPPORT-RECONCILIATION.md table | yes: these are the codings R2 re-codes | reviewer counts; same-family |
| B02 | Under A′ coding and normalization, ACT is **23+ / 1−**, so act-level contrasts **cannot be tested on this coded dataset**. The pipeline removed negatives: the R06/R07 merge deleted the only same-operation ACT negative | SUPPORT-RECONCILIATION.md "What the chain establishes", defect 2; STATE.md | yes: counts depend on codings | "descriptive, same-family" |
| B03 | NORM START-EXECUTION is **5+ / 5−**, with up to 12 genuine same-operation, same-actor, opposite-polarity pairs that differ in target. They are confounded with time / the authorizing ruling, and 2 are within a single ruling | SUPPORT-RECONCILIATION.md | yes: depends on NORM codings | "descriptive, same-family" |
| B04 | The same target flips polarity across successive rulings with all coded fields equal (R31 → R32; R33 → R34). "Target alone does not index polarity; an intervening norm-making act does." | SUPPORT-RECONCILIATION.md; RECORDS.json R31–R34 | yes: equality of coded fields is a coding result | "descriptive, same-family" |
| B05 | Status mediation: a Chief-prepared ruling gains force when the Decision Authority adopts it. **2 episodes, both self-reported by the rulings themselves** | STATE.md; IDENTIFIABILITY.json P4; H5-ADOPTED.md §2 | yes: reconstructed from coded rows | n=2, self-reported |
| B06 | Protocol A capture: LOCAL_REF_SET (25 refs, 4020 commits); 51 matches = 3 EXECUTION-TRACE · 47 DOC-OR-GOVERNANCE · 1 UNCLASSIFIABLE. The earliest EXECUTION-TRACE is `e4724a44` at 2026-08-04T00:30:25Z, "WP-4C-1 batch B1 -- RED for K1..K4", **RECORDING_ORDER only**. `cc1a3394` is substantively WP-6 work (L1) | PROTOCOL-A-RECONCILIATION.md | no: mechanical capture | PASS_WITH_LIMITATIONS |
| B07 | Protocol B: **no locally checkable channel qualifies as an independent norm-in-force record** (F-B1). The overlap test has almost no power, so NOT_INDEPENDENT means "no evidence of role separation", not "evidence of role fusion" (F-B2). The only open candidates are host-side or the 23 K-SIG signatures (F-B3) | PROTOCOL-B-RECONCILIATION.md | no: mechanical capture | PASS_WITH_LIMITATIONS |

## C. Regularities (L2): **none qualify**

Criterion: a pattern recurs across **independent decision clusters**. **Qualifying clusters: 0. Section C is empty.** The candidates below were checked and stay in B/E:
- **B04 (polarity flip):** 2 instances. The sources do not establish that they are independent clusters, and both involve R-58 (R32 and R33 are R-58 spans).
- **B05 / E04 (status mediation):** n=2, self-reported. IDENTIFIABILITY P4 requires ≥3 different adoption acts, not rulings covered by one adoption.
- **B03 (START polarity pairs):** confounded with time and the authorizing ruling; 2 are within one ruling.

## D. Non-identifiability results (L2)

| id | Statement | Sources | R2 | Confidence |
|---|---|---|---|---|
| D01 | **H5:** "On the acquired evidence and within the authorized scope, the competing hypotheses H-D and H-B are non-identifiable." Neither is falsified or validated; both are retained as unresolved alternatives. **Scope qualifier:** a scope/corpus limitation, relative to the evidence and the scope | H5-ADOPTED.md §1, §4; READINESS-RECONCILIATION.md | yes: reopening condition 3 (a defect in the A′ coding on which D1 rests) | ADOPTED by human act 2026-10-01 (F-LOG-0197); review PASS_WITH_LIMITATIONS |
| D02 | **Waiver:** the F-LOG-0190 condition ("We do not declare non-identifiability until the bounded, genuinely informative evidence channels have been exhausted") was **knowingly waived**. H1 was skipped, not exhausted; Q4 was never evaluated; Protocol C is sealed and not executed | H5-ADOPTED.md header, §0 | no: a human act | ADOPTED ("Adopt H5, waive 0190") |
| D03 | **D1** is a **necessary, not sufficient,** condition for discrimination: an observable of norm state, independent of the norm-maker, the executor and the self-asserting ruling. Discrimination additionally requires a mapping to the target at t, and a state that one hypothesis predicts and the other excludes. On the acquired evidence D1 holds: work evidence cannot separate "the norm changed without a recorded act" from "the work violated an unchanged norm". D1 was derived same-family and is not validated | H5-ADOPTED.md §1, §5.2; READINESS-RECONCILIATION.md | yes: D1 rests on A′ coding | "not a validated formal condition"; `d1_broken: false` only "not on the acquired evidence" |
| D04 | Whether an independent norm-state observable exists **anywhere** is **NOT_DETERMINABLE** | READINESS-RECONCILIATION.md; H5-ADOPTED.md §1 | no: a capture/scoping result | review PASS_WITH_LIMITATIONS |
| D05 | **P1** (norms change only through acts): NON-IDENTIFIABLE. Every observed change goes from no authorization to authorization, which needs an act on either hypothesis. Under R-A3 the act and the after-state are the same record. The R-72 contingent authorization becoming operative is not observed | IDENTIFIABILITY.json P1 | yes | reviewed status |
| D06 | **P2** (work conforms): CONFOUNDED, a candidate anomaly against H-B, n=1 (see E01) | IDENTIFIABILITY.json P2 | yes | reviewed status |
| D07 | **P3** (feedback): NON-IDENTIFIABLE; H-D accommodates acts that cite evidence | IDENTIFIABILITY.json P3 | yes | reviewed status |
| D08 | **P4** (status mediation): NON-IDENTIFIABLE as a discriminator; H-D's clause is corroborated, n=2, by self-report (see E04) | IDENTIFIABILITY.json P4 | yes | reviewed status |
| D09 | **P5** (capability): NON-IDENTIFIABLE as a discriminator; H-B is silent (see E03) | IDENTIFIABILITY.json P5 | yes | reviewed status |
| D10 | **P6** (record content): NON-IDENTIFIABLE. H-B's prediction is met at every boundary, and H-D permits it | IDENTIFIABILITY.json P6 | yes | reviewed status |
| D11 | **P7** (outcome-gated authorization): CONFOUNDED with R-89's status. On act versus no act it reduces to P1 (see E02) | IDENTIFIABILITY.json P7 | yes | reviewed status |
| D12 | **P8** (remediation): NON-IDENTIFIABLE. The corpus window runs to 2026-08-15 (S0815), not 2026-08-04 | IDENTIFIABILITY.json P8 | yes | reviewed status |
| D13 | No prediction is IDENTIFIED, and neither hypothesis is favoured; they "come close to being nested". Only two kinds of observation could separate them: (i) a verified norm change with no act, of which "The corpus has no case" (NOT-EVIDENCED-IN-CAPTURE); (ii) verified non-conformance or verified absence of feedback, which has two confounded candidates | IDENTIFIABILITY.json discriminating_observation_types | yes | reviewed |
| D14 | Method result: STRICT witnesses are unreachable by construction, so "no strict witness" carries **no evidential weight**. "Act-level guards non-identifiable" is valid **only under this coding and pipeline**, not "in the corpus". r3 is EXPLORATORY | SUPPORT-RECONCILIATION.md defects 1, 2, 7 | yes | formal reasoning; r3 EXPLORATORY |

## E. Counterexamples / anomalies (L1), stated symmetrically

| id | Item | Direction | n | Confound | Alternative reading | Confidence (as stated) | R2 |
|---|---|---|---|---|---|---|---|
| E01 | **WP-4B RED** (commit A03 against A01/A02): NON-CONFORMS under the primary text reading. This is "the first discriminating observation of type (ii)" | **weakly against H-B's** conformance clause. It refutes only a *universal* "all recorded work conforms". No frequency claim; not decoupling. **H-D makes no prediction** | 1 (one case, one episode) | Contested reading of the proviso: does the R-77 block (inbound path) cover producer-agnostic RED tests? Texts of R-73..R-75 are missing. R-80's timing is unknown. R-79 is a WP-8 directive (corroboration only). Only disclosed RED test code was written; R-87's later acceptance does not ratify the start. IDENTIFIABILITY P2 had labelled it CONFOUNDED | **CONFORMS or UNDETERMINED:** R-72's "when they land, engineering proceeds RED"; R-73..R-76 had landed; R-78 reframes the remainder as implementation-delivery; R-86 "resume implementation"/"Batch 7"; R-87 accepted WP-4B without flagging the start | verdict **moderate**, text-robust; bearing **WEAK** | no: 1ba read source text, not A′ codings (same-family, G02) |
| E02 | **R-89 / WP-4C-1** | against H-B, **if** R-89 took force | 1 predecessor–successor pair | R-89's status: "PREPARED, NOT ADOPTED", with no adoption annotation in the sources, versus the Authority's "R-89 already governs authorization" (which may itself be the adoption). **The sources diverge on ordering:** IDENTIFIABILITY P7 says R-89 "very probably authorized WP-4C-1 before WP-4B was accepted" (INFERENCE); 1ba C2 orders R-87 < R-88 < R-89 by text | **C2 CONFORMS (qualified):** a conforming *draft*, not in force, bearing on neither hypothesis. Under the strict reading of "IMPLEMENTATION EVIDENCE" it is UNDETERMINED; "not applicable" is also offered | P7 "INFERENCE, strongly supported"; C2 "moderate-high (qualified)" | yes: the P7 framing rests on coded R05; the C2 text order does not |
| E03 | **R-94** (A09) | a candidate **falsifier of H-D** (capability clause) | 1 | Does a delivery disposition by the Chief need Decision Authority adoption? R-86 says Chief rulings stay PREPARED until the DA acts, yet R-94 has no PREPARED tag and records execution. Left unresolved in the coding. R29 (supersede refused) is a "hard case" | **A scope question per R-90** (operational acceptance is not a constitutional decision), so no capability breach | open | yes: rests on coded R29/R30 |
| E04 | **P4 status mediation** (B05) | **corroborates H-D's** status clause; **does not discriminate** (H-B does not deny it and has no P4 falsifier) | 2 | Force is "observed" only through declarations inside the norm-making acts, the same kind of evidence that was rejected for P1 | consistent with both hypotheses. R-89 remains an unresolved possible falsifier of H-D | n=2, self-reported | yes |

## F. Explicit unknowns

| id | Unknown | Sources | R2 |
|---|---|---|---|
| F01 | **H5 §3 gap:** host-side records (PR approvals, branch protection, rulesets, CI runs, repository events) were **not examined**, by human decision. Protocol C is sealed, SAFE TO RUN, and not executed. Its stated limits: current state only; events retention is an unverified assumption; Q3 defaults to INDEPENDENCE_NOT_DETERMINABLE; Q2 needs an API relation to `e4724a44`. The "< 10%" gain is a main-analyst estimate, not reviewed | H5-ADOPTED.md §3 | no |
| F02 | **H5 §3:** signer verification was declined (H2 = NO). The signer kind of the 23 K-SIG headers is undetermined | H5-ADOPTED.md §3; PROTOCOL-B-RECONCILIATION.md F-B3 | no |
| F03 | **H5 §3:** the content of status, register, CI and hook files was not read (existence and metadata only), nor the K-CI/K-HOOK enforcement history | H5-ADOPTED.md §3 | no |
| F04 | **H5 §3:** `IMPLEMENTATION_PROGRESS.md:58` was not acquired. The execution reports in A11 are uncoded | H5-ADOPTED.md §3; READINESS-RECONCILIATION.md E3 | no |
| F05 | **H5 §3:** self-contamination. The A and B captures predate R-SELF and are not re-run. **H5 §3:** corpora outside this repository were not examined | H5-ADOPTED.md §3 | no |
| F06 | The missing fact: "Does a record kept by someone other than the norm-maker, the executor or the self-asserting ruling attest the norm state of a register target at a stated time t?" Motivating instance: was WP-4C-1 authorized or blocked at 2026-08-04T00:30:25Z (RECORDING_ORDER)? | READINESS-RECONCILIATION.md | no |
| F07 | R-89 adoption: **UNKNOWN** (no adoption act was found in the sources) | CODING.json R05 notes; CONFORMANCE-ANSWER.md C2 | no |
| F08 | For C1: the text of R-73..R-75 and R-81..R-85; the hour of decision of R-77 and R-79; R-80's timing; any act decided before 16:10 that is absent from SOURCES | CONFORMANCE-ANSWER.md C1; CONFORMANCE-REVIEW.md §3 | no |
| F09 | Execution under the authorizations R-70 (R04), R-47 (R13), R-58 (R32), R-65 (R34) and R-86 (R36), and WP-8 planning (R12): **NOT-EVIDENCED-IN-CAPTURE** in the coded spans (R-A1) | CODING.json R04, R12, R13, R32, R34, R36 notes | yes |
| F10 | Any R-91 remediation after 2026-08-04: NOT-EVIDENCED-IN-CAPTURE (a corpus limitation) | IDENTIFIABILITY.json P8 | no |
| F11 | Capture completeness against the host: **NOT SHOWN** (local refs only) | PROTOCOL-A/B-RECONCILIATION.md | no |
| F12 | Whether the A′ codings reproduce under a non-Claude coder (R2): pending. 13 records have unresolved coding alternatives | H5-ADOPTED.md header, §5.3; CODING-REVIEW.json uncertain | yes |
| F13 | R24, R28, R35: NO SOURCE SPAN AVAILABLE | RECORDS.json; CODING.json | no |

## G. Scope and provenance

| id | Statement | Level | Sources | R2 | Confidence |
|---|---|---|---|---|---|
| G01 | **Corpus boundary:** a register of rulings, plus a few session logs (up to S0815, 2026-08-15) and one git commit. 36 A′ spans are drawn from it, and source identity is pinned by git blob ids (SOURCE-IDENTITY.txt, which is not in this directory). Captures: local refs only (25 refs, 4020 commits); host completeness not shown | L1 | STATE.md; SCHEMA-r2-Aprime.md header; IDENTIFIABILITY.json P8; PROTOCOL-A-RECONCILIATION.md | no | not stated |
| G02 | **Same-family:** every finding cited is same-family (Claude), including codings, reviews and this record. The non-Claude Gate 1 coding (R2) is pending. R2 can only test the coding on which D1 rests; it cannot supply the missing observable | L1 | H5-ADOPTED.md header, §5.3 | yes | not stated |
| G03 | **R-SELF:** the recommended rule is path-based (a declared root, initially `docs/knowledgeos/`, listed in SELF-ROOTS.txt; class SELF-ARTEFACT, excluded from the classes and the earliest trace). Protocol A: "**Not applied** … → human decision". 21/51 matches are programme artefacts (DOC-OR-GOVERNANCE 47 vs 27 historical); the ET set is unchanged. Protocol B: `.claude/` and `claude/` are not excluded. H5: the A and B captures predate R-SELF and are not re-run ("R-SELF applies forward only") | L1 | PROTOCOL-A-RECONCILIATION.md L2; PROTOCOL-B-RECONCILIATION.md; H5-ADOPTED.md §3 | no | not stated |
| G04 | **Instrument:** A′ is an "OBSERVATION REPRESENTATION, NOT A THEORY". It does not assert two ontological layers, H-DEONTIC, or that governed work is out of scope. Under R-A3, act and after-state are the same record | L0 | SCHEMA-r2-Aprime.md header, R-A3; IDENTIFIABILITY.json P1 | no | not stated |
| G05 | **M-TIME:** recording time ≠ decision time ≠ entry into force. Recording time "is NOT an upper bound on entry into force"; it bounds drafting (PREPARED rulings are written before adoption) | L1 | CONFORMANCE-RECONCILIATION.md; CONFORMANCE-REVIEW.md §3; STATE.md | no | reviewed |
| G06 | H-D and H-B are frozen and stated neutrally. Both accept that norm-making acts exist | L0 | HYPOTHESES.md | no | frozen |

## H. What R4 may and may not assume (L2, derived strictly from A–G)

**May assume (as inputs, not as theory):**
- **H01:** the L0 spans and texts (A01–A12) with their provenance (G01).
- **H02:** the A′ ACT/NORM separation as a representation, **conditional on R2** (B01, G04).
- **H03:** D1 as a candidate **necessary, not sufficient**, non-validated condition (D03).
- **H04:** M-TIME: three distinct times (G05).
- **H05:** H-D and H-B as **unresolved alternatives** (D01, G06).
- **H06:** H5 only with its scope qualifier, waiver and gap attached (D01, D02, F01–F05).

**May not assume:**
- **H07:** H-D or H-B as an axiom; **hypotheses are never axioms** (H5 §5.2).
- **H08:** any regularity. C is empty, and B04/B05 must not be generalized.
- **H09:** act-level guards, or a corpus-level claim of their non-identifiability (B02, D14).
- **H10:** norm → act. Authorization is not execution, and prohibition is not refusal (R-A1, F09).
- **H11:** non-occurrence from absence (NOT-EVIDENCED-IN-CAPTURE; D13, F09, F10).
- **H12:** a decision order or entry into force read from recording order (A07, G05).
- **H13:** a fixed status of R-89, a resolution of R-94, or a C1 stronger than "moderate / WEAK, n=1, contested" (E01–E03).
- **H14:** that an independent norm-state observable exists, or that none exists (D04, F06).
- **H15:** that D1 is sufficient (D03).
- **H16:** corpus completeness or cross-family coding reliability (F11, F12, G02).
- **H17:** evidential weight for absent STRICT witnesses (D14).
