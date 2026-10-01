# SPEC 1bb-r2: targeted repair of SPEC-r1, with rewritten rank-0 and scoping protocols

**Status.** This is a specification only. **Nothing has been collected and no command has been run.** The two scripts in Parts B and C are written to be run later, verbatim, by a worker, and then checked by a reviewer. No hash of a script is given, because none has been computed. The reviewer computes the hashes at freeze time (B.8).

**Inputs used (only these):** SPEC-r1.md/.json, REVIEW-r1.md/.json (verdict FAIL), ENVIRONMENT.md, HUMAN-DECISIONS.md, HYPOTHESES.md, IDENTIFIABILITY.json, CONFORMANCE-CASE.json, STATE.md.

**Scope rule.** Only items that REVIEW-r1 marks as failing are repaired:
- the PARTIAL defects D1, D2, D5, D8 and D9;
- the new defects N1–N13;
- the rank-0 issues R0-1 to R0-9 (R0-1 and R0-2 are blocking);
- the scoping issues SC-1 to SC-7.

Everything REVIEW-r1 marks FIXED (D3, D4, D6, D7, D10, D11, D12, D13 and D14) stays as it is in SPEC-r1, with one exception. Where a repair unavoidably changes the text of a FIXED item, the change is limited to that consequence, marked **[CONSEQUENTIAL Nx]**, and listed in CHANGELOG-r2.md:
- FR-D13 item (2) relies on NIF-B, which N1 demotes. REVIEW-r1 names this carry-over itself ("N1 carries over").
- The S1-b window in FR-D4 is widened for N9 and N10.

**Precedence.** Any SPEC-r1 section that is not restated in Part A2 remains in force unchanged. Where an r2 section is given, it replaces the r1 section with the same number.

**Labels.** As in r1: SOURCE FACT, INFERENCE, PROPOSAL, UNKNOWN. Change marks: **[R2-CHANGED x]**, **[R2-NEW x]**, **[R2-REMOVED x]**, where x is the item repaired.

**Hypothesis clauses used below** (from HYPOTHESES.md, frozen):

| Code | Clause |
|---|---|
| **H-D.c1a** | Act-exclusivity: norms change only through norm-making acts. |
| **H-D.c1b** | Capability: those acts are made by actors who hold the capability. |
| **H-D.c2** | Status: force depends on the status of the making act (prepared vs adopted). |
| **H-B.c1** | Norms change through acts, and in response to work. |
| **H-B.c2** | Conformance: work conforms to the norms in force; execution begins only when permitted. |
| **H-B.c3** | Feedback: work outcomes feed back into norm changes. |

---

# PART A. Item-by-item repair

Each item gives: **(1)** the defect, **(2)** the correction, **(3)** why the correction is logically sufficient, **(4)** any change of prediction, and **(5)** the frozen rule. In (4), "hypothesis-level" means the clauses above, which never change. "Operational" means this spec's observation-and-falsifier layer.

## A.0 The symmetry rule G-SYM (core of N1–N3; it replaces G-D1)

**G-SYM (frozen).**
1. **Premise kinds.** Every premise of a falsifier is of exactly one kind:
   - **NS**: a norm-state premise, such as "N is (not) in force at t", "an operation is (not) permitted at t", "a waiver is (not) in force", "an authorization is active", "R-89 is (not) adopted at t", or "state S at t".
   - **ABS**: an absence-of-act premise, such as "no adoption", "no act producing S2", "no waiver act" or "no remediation act".
   - **OCC**: an occasion premise, such as "an execution trace exists", "a system-recorded work outcome exists" or "a text with this content exists (N-EXISTS)".
   - **TXT**: the content of a text as a text, meaning what it says, not whether it is in force.
2. **Sources and presuppositions.** Every source of an NS or ABS premise is one of the following, with a fixed presupposition:

   | Source class | Examples | Independent? | Presupposes |
   |---|---|---|---|
   | **NIF-A** | an independent norm-state record meeting I1–I4 | **yes** | Only RECORDER_ACCURACY. This is hypothesis-neutral: neither hypothesis says what an independent recorder writes. |
   | **DX-BEH** (behaviour) | executor work under N; a third party applying N (the former NIF-B, now **ACT-TP**); citation (NIF-C) | no | **H-B.c2**, applied to the acting party ("parties act only on norms in force") |
   | **DX-MAK** (the maker's records) | ruling texts, adoption forms AF-1/2/3 or register rows, used as evidence of *force* | no | **H-D.c2** (force follows from the status of the making act), plus **H-D.c1a** whenever *persistence* is inferred ("nothing later changed it") |
   | **DX-ABS-INF** (absence used to infer a state) | "no permitting, lifting or waiver act ⇒ not permitted, not lifted, no waiver" | no | **H-D.c1a** (without an act, the state does not change) |
   | **DX-ABS** (absence as the premise itself) | "no adoption", "no act producing S2" | no | RECORD_COMPLETENESS, meaning that no act exists outside the record (UNRECORDED_ACT) |
   | **DX-ASSERT** | MAKER_FORCE_ASSERTION ("R-89 already governs"); a ruling's self-assertion | no | SOURCE_ACCURACY, and the adoption-form reading (AF-N/AF-B, see N4) |

   OCC and TXT premises are neutral: both hypotheses admit that work occurred, and that texts exist and say what they say. They are **necessary conditions of applicability and never carry the firing.**
3. **Grading.** The grading is evaluated in this order; the first match wins.
   1. **NOT_APPLICABLE**: the falsifier's precondition cannot hold in the episode.
   2. **UNDETERMINED(reason)**: any premise is NOT_IDENTIFIABLE; a required text was not obtained; an ABS premise is neither recorded by NIF-A nor shown in a complete channel (§0.8), and so is NOT-EVIDENCED-IN-CAPTURE; or the AF readings disagree (N4).
   3. If the falsifier condition is **false** on the premises: **NOT-FIRED**, if the premise that makes it false is NIF-A, OCC or TXT. Otherwise **NON-DISCRIMINATING**, because a dual-explainable source cannot acquit either hypothesis either.
   4. If the condition is **true**, and **every NS and ABS premise is from NIF-A**: **FIRED**. If a neutral weakness applies (SOURCE_STATED git times, ACTOR_SET_ID or a WEAK_TRACE), the grade is **FIRED-CONDITIONAL(that list)**. These neutral conditions are the only ones allowed there.
   5. If the condition is **true** but some NS or ABS premise comes from a DX-* source: **DISJUNCTIVE{falsified clause; presupposition set}**, meaning "at least one of these fails; which one is not identified". It carries the tag:
      - **CROSS**, if the presupposition set contains a clause of the *other* hypothesis;
      - **SELF/NEUTRAL** otherwise.

      DISJUNCTIVE **never counts as a falsification of either hypothesis.**
4. **The same rule is applied to H-D and H-B.** No source class is admitted for one hypothesis's falsifiers and not for the other's. The check is in the symmetry table (§A.S).

## A.1 D1 (PARTIAL): the same observation serves both sides

**(1) Defect (REVIEW-r1).** D1 is only partly repaired. NIF-B is behaviour that both hypotheses explain (N1). G-D1 covers H-D's status rule but not its act-exclusivity rule (N2). The maker's-records fallback is offered against H-B only (N3).

**(2) Correction.** G-D1 is replaced by G-SYM (§A.0), which grades behaviour, the maker's records, absences and assertions identically on both sides. N1, N2 and N3 below implement it.
- **NIF has one tier only: NIF-A.**
- NIF-B is renamed **ACT-TP** (a third-party act on N), and is recorded as an act.
- NIF-C is renamed **N-ASSERTED** (third party).
- The graded fallback of r1 §0.4 is **[R2-REMOVED D1/N3]** as a route to FIRED or FIRED-CONDITIONAL on either side.

**(3) Why this is sufficient.**
- The confound is an observation O that is predicted by (H-D clause holds ∧ H-B clause fails) and by (H-D clause fails ∧ H-B clause holds).
- Under G-SYM, a firing premise must come from NIF-A. The only thing an NIF-A entry presupposes is RECORDER_ACCURACY, which is a clause of neither hypothesis. So a FIRED grade cannot rest on a premise that one hypothesis explains away by invoking the other.
- Every dual-explainable O is graded DISJUNCTIVE, whose content is exactly the disjunction that O supports.
- This holds for behaviour (DX-BEH) and for absences (DX-ABS-INF, DX-ABS), which is where r1 failed. It also holds for the maker's records (DX-MAK), and G-SYM(4) forbids admitting them on one side only.
- So no observation is counted as falsifying one side while the other side can explain it.

**(4) Prediction change.** The hypothesis-level claims are unchanged. Operationally, every falsifier's FIRED route now needs NIF-A. The realistic outcomes become DISJUNCTIVE, UNDETERMINED or NON-DISCRIMINATING, because Stage 0 cannot supply NIF-A (SC-1).

**(5) Frozen rule.**
> **FR-D1-r2.** G-SYM governs every falsifier. N-FORCE is established only by NIF-A. Executor work and third-party acts (ACT-TP) establish ACT-CONF_TEXT or ACT-CONTRA_TEXT relative to the texts, or ACT-TP, and never N-FORCE or N-CHANGE-NOACT. FR-D1 and G-D1 of r1 are superseded.

## A.2 N1: NIF-B is behavioural

**(1) Defect.** A third party that "applies R-89 as binding" while R-89 is not adopted is explained by H-D (that party acted contrary to the norm, or erred) and by H-B (practice changed the norm). Yet r1 treated this as N-FORCE[OPERATIONAL], and O3 fired P4-HD. It also downgraded the application only if it was "expressly voided", so silence counted as force.

**(2) Correction.**
- NIF-B is deleted as a force observable.
- A third party's application is **ACT-TP**, recorded with its time and identity grade.
- As a force premise, ACT-TP is DX-BEH, and it presupposes H-B.c2 for that party.
- The rule "voided ⇒ FIRED-CONDITIONAL" is **[R2-REMOVED N1]**.
- O3 (P4-HD) fires only on NIF-A (DP-P4, §5).
- **[CONSEQUENTIAL N1] FR-D13 item (2)**: NIF-B uptake after adoption is recorded as ACT-TP and cannot certify "independently observed". Only item (0), NIF-A, can do that. Items (1)–(3) together give at most PARTIALLY_INDEPENDENT.

**(3) Why this is sufficient.** ACT-TP is an act according to, or contrary to, a norm (distinction 4 or 5). Using it as distinction 2 (in force) is valid only if the party conforms, which is H-B.c2. G-SYM therefore makes any H-D falsifier built on it DISJUNCTIVE{H-D.c2; H-B.c2} CROSS, which is exactly the two-way explanation named in the defect. Silence can no longer count as force, because no behavioural source can reach FIRED, whether or not the act is voided.

**(4) Prediction change.** P4-HD can no longer fire on third-party application. The NIF-B route to N-FORCE is also closed for P1, P5, P7 and P8. P4 is expected to be NON-DISCRIMINATING or DISJUNCTIVE unless NIF-A becomes available.

**(5) Frozen rule.**
> **FR-N1.** Third-party application is ACT-TP. It establishes an act, never N-FORCE. As a force premise it is DX-BEH, presupposing H-B.c2. Status mediation counts as independently observed only through NIF-A (FR-D13 item 0).

## A.3 N2: G-D1 missed the act-exclusivity premise

**(1) Defect.** "No permitting act in a complete DCS ⇒ not permitted" is valid only if H-D.c1a holds. Under H-B reading 1, a permission can arise from work without an act. P2, C1, O5 and ACT-CONTRA therefore falsified H-B on an H-D premise.

**(2) Correction.**
- The DX-ABS-INF source class is added to G-SYM, and it presupposes H-D.c1a.
- **ACT-CONTRA** is redefined (§0.3-r2): it is established only when NIF-A shows the prohibiting or non-permitting state at t⁻.
- From texts alone, the result is **ACT-CONTRA_TEXT**, a reading of what the maker's texts say. As a falsifier premise it is DX-MAK/DX-ABS-INF and grades DISJUNCTIVE{H-B.c2; H-D.c2, H-D.c1a} CROSS.
- The r1 baseline "no authorization unless shown" (FR-D9 default, O5) is **[R2-REMOVED N2]**.

**(3) Why this is sufficient.** The fallacious step infers a *norm state* from the *absence of an act*. Any such inference is now classed DX-ABS-INF. That class states its presupposition explicitly, so a falsifier that uses it cannot be graded FIRED. It becomes the disjunction {H-B clause fails, or H-D.c1a fails}, which is exactly what the observation supports.

**(4) Prediction change.** The P2 (C1), P5-HB, P7-HB and P8-HB falsifiers can no longer fire on "no permitting, waiver or remediation act". They are graded DISJUNCTIVE CROSS if the absence is shown complete, and UNDETERMINED otherwise.

**(5) Frozen rule.**
> **FR-N2.** A norm-state premise inferred from the absence of an act is DX-ABS-INF and presupposes H-D.c1a. Only NIF-A can establish non-permission, no waiver, or no remediation, for the purpose of FIRED.

## A.4 N3: asymmetric fallback

**(1) Defect.** The maker's records were admitted as a FIRED-CONDITIONAL premise against H-B (P2, P5-HB, P7), but a maker force assertion was barred against H-D (P4).

**(2) Correction.**
- The maker's records, used as evidence of force, are DX-MAK **on both sides**.
- MAKER_FORCE_ASSERTION is DX-ASSERT **on both sides**.
- Neither can give FIRED or FIRED-CONDITIONAL.
- Both give DISJUNCTIVE:
  - Against H-B (P2, P7): DISJUNCTIVE{H-B.c2; H-D.c2(, H-D.c1a)} CROSS.
  - Against H-D (P4, on "R-89 already governs"): DISJUNCTIVE{H-D.c2; SOURCE_ACCURACY, AF-N} SELF/NEUTRAL. If work or an ACT-TP under R-89 is also present, the grade is DISJUNCTIVE{H-D.c2; H-B.c2} CROSS (DP-P4).
- FIRED-CONDITIONAL is kept only for hypothesis-neutral weaknesses (SOURCE_STATED times, ACTOR_SET_ID, WEAK_TRACE).

**(3) Why this is sufficient.** Symmetry means that the same source class receives the same grade whichever hypothesis it cuts against. After the correction, each source class has one grade, fixed in G-SYM(2), and it does not depend on which hypothesis's falsifier it appears in. The symmetry table checks this row by row. The TASK disjunction "NIF, or else NON-DISCRIMINATING or DISJUNCTIVE" is now met on both sides.

**(4) Prediction change.**
- P2 and P7-HB: the r1 FIRED-CONDITIONAL on the maker's texts becomes DISJUNCTIVE CROSS.
- P4-HD: the r1 "barred" becomes DISJUNCTIVE (SELF/NEUTRAL or CROSS). It is recorded, and it never fires.

**(5) Frozen rule.**
> **FR-N3.** DX-MAK and DX-ASSERT grade DISJUNCTIVE on every falsifier of either hypothesis. FIRED-CONDITIONAL conditions are limited to {SOURCE_STATED_TIME, ACTOR_SET_ID, WEAK_TRACE}.

## A.5 N4: "R-89 already governs" frozen as non-adoption

**(1) Defect.** A statement by the sole adopter was frozen as non-adoption, and the sensitivity result was never scored. Together with N1, this let O3 falsify H-D in a scenario that H-D explains.

**(2) Correction.**
- The classification of an adopter's descriptive force statement is **UNKNOWN**. Reading it as not an adoption is a PROPOSAL, not a fact.
- Two readings are frozen:
  - **AF-N**: the statement is not an adoption (r1's reading);
  - **AF-B**: the statement is an adoption of the rulings it names, taking effect no later than the statement's decision time.
- Under AF-B, the decision time is not observable: recording ≠ decision. So "adopted before t" is NOT_IDENTIFIABLE whenever the statement is recorded after t. If it is recorded before t (RECORDING_ORDER), R-89 counts as adopted at t.
- Every P4 and P7 premise that depends on R-89's adoption is graded under both readings. **The scored grade is the common grade if the two agree. Otherwise it is UNDETERMINED(AF_READING)**, and both grades are reported.
- An NIF-A record of R-89's status settles the matter under both readings, because the independent status record, and not the reading, then decides adoption.

**(3) Why this is sufficient.** The false falsification needed a reading chosen against H-D. Requiring agreement under both readings means no scored result depends on the interpretive choice. The scenario in the defect gives FIRED only under AF-N, so under the new rule it is UNDETERMINED(AF_READING).

**(4) Prediction change.** P4-HD, and every P7 row that uses AD, are likely to be UNDETERMINED(AF_READING) unless NIF-A exists. AF-1, AF-2 and AF-3 stay unchanged as the adoption forms common to both readings.

**(5) Frozen rule.**
> **FR-N4.** For each MAKER_FORCE_ASSERTION by a capability holder, evaluate under AF-N and AF-B. Score only a grade that is common to both; otherwise UNDETERMINED(AF_READING). An NIF-A status record overrides both readings.

## A.6 N5: the entry-into-force fallback is only a lower bound, and the verdict was not a function

**(1) Defect.** "Entry into force not earlier than recording time" is not implied by the inputs, and it gives only a lower bound, so "in force before 16:10" could never be shown. NON-CONFORMS and UNDETERMINED could then hold at once.

**(2) Correction.**
- The recording-time fallback is **[R2-REMOVED N5]**.
- FR-D9 is rewritten (§2-r2) with a strict verdict precedence, in which UNDETERMINED is checked first.
- It separates two questions:
  - **existence-before**: was the text recorded before R11? This uses RECORDING_ORDER or SOURCE_STATED_ORDER, and is a TXT/OCC matter.
  - **force-before**: was the text in force before R11? This is NS, and only NIF-A can establish it.
- The textual verdict V_text is computed on existence-before. Its bearing on H-B is graded by G-SYM.

**(3) Why this is sufficient.**
- V_text is defined by an ordered first-match list with a final catch-all. So exactly one value is assigned: it is a function.
- No step needs an upper bound on entry into force, because force is never inferred from recording.
- Whether a text *existed* before 16:10 is decidable from recording order, and that is all the textual reading needs.

**(4) Prediction change.** C1's bearing on H-B becomes:
- **FIRED** only with NIF-A;
- otherwise **DISJUNCTIVE{H-B.c2; H-D.c2, H-D.c1a} CROSS**, if V_text = NON-CONFORMS_TEXT and every required absence is shown complete;
- otherwise **UNDETERMINED**.

**(5) Frozen rule.** FR-D9-r2 (§2-r2).

## A.7 N6: the default turned an unshown absence into NON-CONFORMS

**(1) Defect.** P ("no unresolved governance blockers") requires an absence. When P was not shown, the default gave NON-CONFORMS.

**(2) Correction.**
- There is no default from absence.
- ¬P is established **positively**: a text recorded before R11 states that a named precondition of R-72 is unresolved, or that WP-4B is blocked.
- P is established only positively as well: a text recorded before R11 states that the proviso is met, or that RED may proceed.
- If neither holds, V_text = UNDETERMINED(PROVISO_NOT_EVIDENCED).
- Any absence premise (for example, "no lifting text before R11") that is not shown complete makes the *bearing* UNDETERMINED(ABSENCE_NOT_EVIDENCED). V_text is still reported as a CAPTURE_PATTERN, which is never scored.

**(3) Why this is sufficient.** The two verdicts that carry content now require positive textual premises, so an unobserved absence can produce neither of them. The residual absence ("nothing lifted the block") is routed to UNDETERMINED by G-SYM step 2, which matches §0.8 and FR-D5(e).

**(4) Prediction change.** The r1 "likely NON-CONFORMS" becomes: V_text is likely NON-CONFORMS_TEXT, because R-77's "WP-4B REMAINS BLOCKED" is a positive blocking text (SOURCE FACT in CONFORMANCE-CASE). The bearing is likely UNDETERMINED(ABSENCE_NOT_EVIDENCED), because completeness is not shown (SC-4).

**(5) Frozen rule.** It is included in FR-D9-r2, steps V1–V4.

## A.8 D9 (PARTIAL): the C1 rule

**(1) Defect.** The fallback is a lower bound (N5). An unshown absence gives NON-CONFORMS (N6). The "unauthorized unless shown" baseline carries H-D (N2).

**(2) Correction.** FR-D9-r2 (§2-r2) replaces FR-D9. What is kept from r1: the P ∧ ¬B structure, as the *textual* reading; the default scope (a block covers all WP-4B work, including producer-agnostic RED, unless a text narrows it); and the R11 16:10 label (SOURCE_STATED).

**(3) Why this is sufficient.** The three sub-defects are removed by N5 (the verdict is a function and needs no bound on force), N6 (no default from absence) and N2 (no act-exclusivity baseline). The r1 fixes that REVIEW-r1 accepted are retained.

**(4) Prediction change.** As in N5 and N6. The C1 bearing on H-B is at most DISJUNCTIVE CROSS without NIF-A.

**(5) Frozen rule.**
> **FR-D9-r2.** See §2-r2. It supersedes FR-D9.

## A.9 N7: the outcome table is not exhaustive and not exclusive

**(1) Defect.**
- There is no row for {cites R-89, ACC, ¬AD, no NIF} or for ¬G.
- MENTION_ONLY and CAPTURE_ERROR are unmapped.
- O5 lacks ¬ACC.
- Joint firing and the precedence of O6 are unstated.

**(2) Correction.** Table O0–O8 is replaced by three **ordered first-match decision procedures**: DP-P7, DP-P4 and DP-C1 (§5-r2 and §2-r2). Each is evaluated over fixed variables, and each ends with a catch-all. Joint results are reported per procedure. Identical DISJUNCTIVE sets from different procedures are merged into one finding.

**(3) Why this is sufficient.**
- A first-match list whose last row is unconditional assigns exactly one outcome to every input. So each procedure is exhaustive and exclusive by construction.
- Every rank-0 label is mapped in step 1 of DP-P7.
- ACC, G and WV are tested before any firing row. So the O5 error (firing although acceptance was in force) is impossible: an ACC row returns NOT-FIRED or NON-DISCRIMINATING first.
- Precedence is the list order.

**(4) Prediction change.** O5's wrong firing is removed. Outcomes that r1 left unmapped now have defined grades.

**(5) Frozen rule.**
> **FR-N7.** P4, P7 and C1 are scored only by DP-P4, DP-P7 and DP-C1, in list order. First match wins. Identical DISJUNCTIVE sets are reported once.

## A.10 N8: O0 drops P4, and the P4 items are gated on a trace

**(1) Defect.** P4-HD does not need a WP-4C-1 trace, yet O0 said that "P4 discriminates nothing", and S1-e and S1-f ran only if a trace existed.

**(2) Correction.** Stage 1 is split (§5-r2).
- **Ungated (P4, P8):** S1-a, S1-b, S1-e, S1-f and S1-h.
- **Gated on a trace (P7 only):** S1-c, S1-d and S1-g.
- A rank-0 result with no trace sets only DP-P7 to NO_OCCASION_IN_CAPTURE. It does not affect DP-P4.

**(3) Why this is sufficient.** The gate now depends only on the predictions whose falsifier has an OCC premise "execution trace" (P7), and P4 has no such premise.

**(4) Prediction change.** P4 stays live when there is no trace. Its realistic grade is still NON-DISCRIMINATING or UNDETERMINED(AF_READING) without NIF-A.

**(5) Frozen rule.**
> **FR-N8.** Only S1-c, S1-d and S1-g are gated on EXECUTION_TRACE_FOUND. All other Stage 1 items run after Stage 0, whatever its result.

## A.11 D2 (PARTIAL): the acquisition and the outcome table

**(1) Defect.** The table is not exhaustive and O5 is wrong (N7). O0 wrongly removes P4 (N8).

**(2) Correction.** N7 and N8, as above. The two-stage design, the items (a)–(d), the effort restatement, and the explicit no-trace outcome (now DP-P7 step 2) are kept from r1.

**(3) Why this is sufficient.** Both named failures are removed: the decision procedures are exhaustive by construction, and the gate is limited to P7.

**(4) Prediction change.** None at the hypothesis level. Operationally, as N7 and N8.

**(5) Frozen rule.**
> **FR-D2-r2.** Stage 0 = Part B and Part C, both run before any Stage 1 item. Stage 1 gating follows FR-N8. Scoring follows FR-N7.

## A.12 N9: the late-recording rule cannot fire

**(1) Defect.** S1-b read the DCS only within the window, so acts recorded later were never seen. Acts that were late but stated no effect date were not covered.

**(2) Correction.**
- S1-b reads the **full DCS v1 history, from the root of each channel to the capture**.
- Any act on the same target and of the same kind (adoption, waiver, permission, acceptance or remediation, for the same norm or work package) that is recorded **after** window W is marked **POSSIBLY_LATE**, whether or not it states a date.
- Its decision and effect time relative to W is NOT_IDENTIFIABLE, unless NIF-A places it.
- Premises of the form "no such act in W" or "such act in force in W" then become NOT_IDENTIFIABLE, which gives UNDETERMINED.

**(3) Why this is sufficient.** The rule now sees every act that could be late, because it reads up to capture. It no longer depends on the act stating a date: any act recorded after W *could* have been decided in W (recording ≠ decision), and that is exactly the case the rule neutralizes.

**(4) Prediction change.** Some "no act" premises that r1 would have taken as absent become UNDETERMINED.

**(5) Frozen rule.**
> **FR-N9 (= FR-D5(b)-r2).** The DCS is read from the root to capture. A same-target, same-kind act recorded after W is POSSIBLY_LATE, and makes the in-W absence or presence premises NOT_IDENTIFIABLE, unless NIF-A dates it.

## A.13 N10: waivers recorded before the window start

**(1) Defect.** "No waiver in force at t_w⁻" needs waivers recorded before the window, but S1-b was bounded by the window.

**(2) Correction.** This is covered by FR-N9 (reading from the root). The waiver history is read from the root, and every waiver for WP-4C-1, for R-72's gate, or for a successor gate is listed, whatever its date. **[CONSEQUENTIAL N9/N10]** In FR-D4, only the S1-b *reading range* changes. The P7 window itself is unchanged.

**(3) Why this is sufficient.** A waiver in force at t_w⁻ must have been recorded by capture time if it is in the record at all, and the read range [root, capture] contains every such recording.

**(4) Prediction change.** None beyond N9.

**(5) Frozen rule.**
> **FR-N10.** The WV premise is evaluated against all waiver entries from the root to capture. Absence is still graded by G-SYM (DX-ABS-INF), so it can never fire on its own.

## A.14 N11: the R-91/R-94 reference check is missing

**(1) Defect.** The check whether R-91 or R-94 refer to R11 was promised but was not an item, and Stage 1 was gated.

**(2) Correction.** A new item **S1-h** (P8, ungated) records whether the texts of R-91 and R-94, and any DCS entry that names R11 or its commit, refer to R11. Each hit is recorded as a response candidate (LATE_RESPONSE where applicable). It is an OCC/TXT observation.

**(3) Why this is sufficient.** The promised observation is now an item with no gate.

**(4) Prediction change.** None. P8 gains the premise it already needed.

**(5) Frozen rule.**
> **FR-N11.** S1-h is acquired after Stage 0, unconditionally.

## A.15 N12 and D8 (PARTIAL): the P1 satisfaction rule is not frozen

**(1) Defect.** The per-clause satisfaction rule was deferred. The P1 eligibility criterion also derived H-B's "work condition" from the texts of H-D-style conditional acts.

**(2) Correction.** The rules are frozen now (§1-P1-r2).
- **Eligibility is act-independent.**
  - A target X is **eligible** iff a system-recorded **work outcome** of one of H-B's own types (below) occurred on X within W, and X's relevant norm state just before it is recorded by NIF-A.
  - The work-outcome types are **WO-EVID** (a system-recorded verification run with a passing result on a commit that is an execution trace of X) and **WO-FAIL** (the same, with a failing result; or a P2 non-conformance on X graded FIRED).
  - A commit message or report that claims a result is MENTION/REPORT, and does not count as a work outcome.
- **The expected change is frozen per type** (H-B.c1/c3):
  - WO-EVID → acceptance of X, or authorization of X's successor;
  - WO-FAIL → a remediation or hold state on X.
- **The satisfaction rule for act-stated conditions** is used only for P1-HD's exclusion "no prior conditional act", and is frozen as a keyword table:
  - condition text naming GREEN, "IMPLEMENTATION EVIDENCE", "passing" or "verified" → satisfied by WO-EVID on the named target;
  - "accepted" or "acceptance" → satisfied only by an acceptance *act*, so the change is act-mediated;
  - "when they land" and similar → satisfied only by the named acts;
  - any other wording → **NOT_FROZEN_CLAUSE**: that target is excluded from P1-HD and reported.
- The D8 fields added in r1 are kept. The rest of `missing_fields` cannot be checked, because the r1-era review JSON is not an input (this is disclosed).

**(3) Why this is sufficient.**
- The rules are fixed before acquisition, so they cannot be tuned to the outcome.
- Eligibility no longer depends on what an act says, so H-B's work condition is not defined through H-D's vocabulary.
- The keyword table is exhaustive, because it has a catch-all.

**(4) Prediction change.** The P1-HB census domain becomes "targets with a system-recorded work outcome". CI run records are host-side and NOT_DETERMINABLE_BY_EXISTENCE_CHECK. So P1-HB (1a and 1b) is expected to be UNDETERMINED(NO_SYSTEM_RECORDED_OUTCOME) until such records are authorized.

**(5) Frozen rule.**
> **FR-D8-r2 / FR-N12.** As stated in (2). P1 is no longer NOT_FROZEN.

## A.16 D5 (PARTIAL): the closed-world register

**(1) Defect.** (b) Late recording is not operational (N9). (c) The "already governs" classification was frozen against H-D (N4).

**(2) Correction.** (b) is replaced by FR-N9, and (c) is qualified by FR-N4. Sub-fixes (a), (d), (e) and (f) are kept. In (d), ACTOR_SET_ID still caps a grade at FIRED-CONDITIONAL, which is a neutral condition under FR-N3.

**(3) Why this is sufficient.** The two parts that REVIEW-r1 found insufficient are replaced by rules that are operational (N9) and interpretation-neutral (N4).

**(4) Prediction change.** As in N4 and N9.

**(5) Frozen rule.**
> **FR-D5-r2.** FR-D5 (a), (d), (e) and (f) as in r1; (b) = FR-N9; (c) = AF-1, AF-2 and AF-3, plus FR-N4.

## A.17 N13: vocabulary

**(1) Defect.** Several grades were used but not defined. N-KNOWN mixed knowledge with claims. DCS v1 membership was inconsistent.

**(2) Correction.**
- The single vocabulary is in §0.9-r2.
- N-KNOWN is split:
  - **N-ASSERTED(X)** is a claim by X;
  - **N-KNOWN(X)** is factive: N-FORCE by NIF-A, and X asserts it.
- **DCS v1** = DCS v0 ∪ {channels with Q2 ∋ ADOPTION_EVENT or APPROVAL_EVENT}. NORM_STATE channels are NIF-A candidates, not DCS members. This is stated identically in §0.6-r2, C.5-r2 and the JSON.

**(3) Why this is sufficient.** Every label used anywhere in r2 is defined once. The factive and non-factive senses have separate names. There is one membership rule.

**(4) Prediction change.** None.

**(5) Frozen rule.**
> **FR-N13.** Only labels defined in §0.9-r2 are used.

## A.18 R0-1 (blocking): Run B cannot run in a bare mirror

**(1) Defect.** `git rev-parse --show-toplevel` dies in a bare mirror, and `set -e` aborts, so every Run B gave CAPTURE_ERROR.

**(2) Correction.**
- There is no mirror, no bundle and no Run B. The script runs in the **ordinary working clone** (ENVIRONMENT: `is-bare-repository` = false).
- A precondition refuses to start (exit 2) on a bare repository or outside a work tree.
- There is no `set -e`. Every git command's exit code is checked against an explicit list of allowed codes, and unexpected codes are recorded in `00_errors.tsv`, which gives CAPTURE_ERROR.
- Reproducibility comes from two back-to-back runs, plus a reviewer rerun, on the same ref set (B.8).

**(3) Why this is sufficient.** The failing command context (a bare repository) no longer exists. A bare repository is refused up front, with a defined exit and without searching. No expected non-zero exit can abort the run, because none aborts.

**(4) Prediction change.** None.

**(5) Frozen rule.**
> **FR-R0-1.** Rank-0 runs only in a non-bare work tree. The script never aborts on a git exit code. Codes are classified as allowed or unexpected per call.

## A.19 R0-2 (blocking): the C2 check conflicts with the fetch refspec

**(1) Defect.** The fetch used the default refspecs, `ls-remote` listed `refs/pull/*`, and so C2 failed on any hosted repository.

**(2) Correction.**
- There is no fetch and no `ls-remote`: host access is not authorized.
- **The capture is declared to be the local ref set**: every ref listed by `git for-each-ref` (heads, remote-tracking refs, tags, and any others such as refs/stash or refs/notes). The walk starts from exactly those tips. HEAD is not added separately.
- **Completeness vs the host is NOT shown.** `refs/pull/*` and anything not held locally are out of channel.
- C2, C3, C4 and ABSENT_IN_COMPLETE_CHANNEL are **[R2-REMOVED R0-2]** from rank-0, because they are unreachable under this environment.
- Every absence of a trace is **NOT-EVIDENCED-IN-CAPTURE**.
- C1 (not shallow, not partial) is kept, and a failure is CAPTURE_INVALID (exit 3, before the walk). `GIT_NO_LAZY_FETCH=1` guarantees that no object is fetched.

**(3) Why this is sufficient.**
- The spurious failure came from comparing the capture with a ref set it could never contain. The capture is now defined as what is searched, so there is nothing to mismatch.
- The claim that this removes (completeness vs the host) is withdrawn, not assumed.
- Unfetched PR and MR work is declared out of channel, not claimed as searched.

**(4) Prediction change.** Rank-0 can never return ABSENT_IN_COMPLETE_CHANNEL. So the DP-P7 "no occasion" row is always "in capture". This matches r1's reading of O0.

**(5) Frozen rule.**
> **FR-R0-2.** Capture = local ref set at run time, recorded in `01_refs.tsv`, with its SHA-256 in the canonical key. Completeness: NOT_SHOWN. Absence: NOT-EVIDENCED-IN-CAPTURE. Host refs: out of channel.

## A.20 R0-3: path-name hits are not in the hit set

**(1) Defect.** The path-name hits were not in `90_hitset.txt`, so ET-2 clause 3 was never classified and had no ancestry.

**(2) Correction.**
- The hit set is the union of: message hits, non-merge diff hits, merge first-parent diff hits, path-name hits (non-merge name-status and merge own-paths), and ref-exclusive commits.
- Every commit in the hit set gets features, a class, and ancestry rows.
- Path-name matching is done over a full `--name-status` dump, not over a pathspec glob.

**(3) Why this is sufficient.** Every source that can produce an ET-2 clause feeds the single hit set. Classification and ancestry are computed only from that set, so no ET can bypass ancestry.

**(4) Prediction change.** None.

**(5) Frozen rule.**
> **FR-R0-3.** `90_hitset` = ∪{10, 11, 12, 14, 16}. Classification and ancestry operate on `90_hitset` only.

## A.21 R0-4: multibyte hyphens were not matched

**(1) Defect.** Under `LC_ALL=C`, `[^0-9a-z]{0,2}` counts bytes, so a 3-byte hyphen was not matched.

**(2) Correction.**
- The hyphen class HY is an explicit alternation of byte sequences:
  - `-`;
  - U+2010, U+2011, U+2012, U+2013, U+2014 and U+2015;
  - U+2212;
  - U+FE58 and U+FE63;
  - U+FF0D;
  - U+00AD.
- It is built with bash `$'…'` and matched bytewise under `LC_ALL=C` by git, grep and awk.
- **PRIMARY** = `WP HY 4C HY 1` (case-sensitive, with alnum boundaries). A typographic hyphen substitution is the same identifier. This is a PROPOSAL, frozen.
- **VARIANT** = case-insensitive W, P, 4, C, 1, with at most one separator from (HY | space | `_` | `.` | `/`) between them. Variants are reported and never counted.

**(3) Why this is sufficient.** Each listed code point is matched by its exact UTF-8 byte string, so byte-counting cannot miss it. Hyphen-like code points that are not listed are disclosed as unmatched.

**(4) Prediction change.** None.

**(5) Frozen rule.** The frozen rule is the patterns in B.2.

## A.22 R0-5: mentions were over-included as execution traces

**(1) Defect.** A subject mention in another work package's commit (MIXED), governance data files (for example register.yaml or queue.json), and `TODO(WP-4C-1)` comments could each become t_w.

**(2) Correction.** These rules are applied mechanically by the classifier (B.3):
- If the subject's **first** WP identifier is not WP-4C-1, a subject hit is demoted from ET-1 to ET-2 (flag FIRST_WP_NOT_TARGET).
- DOC_GOV additionally covers `yaml|yml|json|csv|tsv|toml` files whose basename stem is a governance stem.
- An added line that matches PAT and is **comment-only** gives MENTION/M-COMMENT, not ET-2.
- **WEAK_TRACE:** if every ancestry-minimal trace is ET-2, the P7 grade is capped at FIRED-CONDITIONAL(WEAK_TRACE).

**(3) Why this is sufficient.** Each over-inclusion path named in the review is now a separate rule clause with a defined output. The residual risk, an ET-2 based on a message body, is disclosed and neutralised by the WEAK_TRACE cap.

**(4) Prediction change.** Fewer false t_w. P7 carries WEAK_TRACE when it applies.

**(5) Frozen rule.** B.3 clauses E1, E2a–E2d, and M-*.

## A.23 R0-6: the merge rule was not operational

**(1) Defect.** "NON_DOC changes not present in any other hit commit" gave no criterion for identity.

**(2) Correction.**
- A merge's **own changes** are the paths whose resulting blob differs from **every** parent: git's combined diff, `--diff-merges=combined --name-only`.
- Only own NON_DOC paths count toward ET-1 or ET-2 for a merge. Added-line hits count only on own paths.
- A tokened merge with no own changes is MENTION/M-MERGE.
- The side commits it integrates are **not** promoted. They become hits only through their own tokens, diffs or ref exclusivity.

**(3) Why this is sufficient.** Path identity is defined by blob inequality with all parents. This is computed by git and is deterministic. Integrated work is attributed to the commits that made it, and those are searched by the non-merge passes.

**(4) Prediction change.** None.

**(5) Frozen rule.** B.3, merge clause.

## A.24 R0-7: CLOCK_ANOMALY, global config, and the byte-identity claim

**(1) Defect.**
- CLOCK_ANOMALY was defined but computed by no script.
- The global git config was not isolated.
- The byte-identity claim was false, because the output held machine paths.
- W7 classification was manual.

**(2) Correction.**
- The script computes CLOCK_ANOMALY, for every ancestry pair found, as ct(descendant) < ct(ancestor).
- It sets `GIT_CONFIG_GLOBAL=/dev/null` and `GIT_CONFIG_NOSYSTEM=1`, plus explicit `-c` overrides.
- No machine path, remote URL or toplevel path is written.
- Classification is automated and frozen in awk (B.3). The reviewer checks it by blind recoding (B.8).
- **Byte-identity is claimed only for two runs on the same ref set with the same git version.** Across git versions, only the canonical key is compared, and it is not guaranteed.

**(3) Why this is sufficient.** Each sub-defect now has a mechanism. The narrowed identity claim is one that deterministic, sorted outputs with no machine-specific content can meet.

**(4) Prediction change.** None.

**(5) Frozen rule.** B.4 and B.7.

## A.25 R0-8: ref names were not matched

**(1) Defect.** Ref names such as `feature/WP-4C-1` were not matched against PAT.

**(2) Correction.**
- Ref names are matched against PAT (and VAR, into the variant bucket).
- For every PAT-named ref R, the commits reachable from R and from no ref whose name fails PAT (`rev-list R ^others`, non-merge) are **ref-exclusive**. They enter the hit set.
- Ref-exclusive commits with NON_DOC changes are ET-2, clause E2d.

**(3) Why this is sufficient.** Work on a WP-4C-1-named branch that has not been merged elsewhere is now in the hit set, even without the token in its messages. Once such work is merged into other refs, it is no longer exclusive, and it must be found through its own tokens (disclosed).

**(4) Prediction change.** None.

**(5) Frozen rule.** B.1 and B.3 E2d.

## A.26 R0-9: race, and path truncation

**(1) Defect.** There was a race between fetch and `ls-remote`. awk `$2` truncated paths containing spaces, and deleted files were mapped to `/dev/null`.

**(2) Correction.**
- There is no fetch and no `ls-remote`, so there is no race.
- name-status is split on TAB only.
- Diff headers are parsed with a header state machine: the old path is taken from `--- a/`, the new path from `+++ b/`, and `/dev/null` handling is explicit, so removed lines keep the old path.
- `--src-prefix=a/` and `--dst-prefix=b/` are forced.

**(3) Why this is sufficient.** Each named failure mode is removed by construction. Quoted paths (those with control characters) are disclosed as a residual risk.

**(4) Prediction change.** None.

**(5) Frozen rule.** B.7, AWK_LINES.

## A.27 SC-1: NIF_A_CHANNEL_EXISTS is unreachable by construction

**(1) Defect.** Q5 = YES needed NORM_STATE, but content is not read, so the result was fixed before the run.

**(2) Correction.**
- The unreachability is **declared**: NIF_A_CHANNEL_EXISTS is labelled **NOT_REACHABLE_BY_THIS_PROTOCOL**.
- The overall headline is **NIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK**, with reachable sub-results:
  - OPEN_LOCAL_CANDIDATES(n);
  - EXCLUDED_LOCAL_CANDIDATES(n), for example writers overlapping with the maker or the executor (ACTOR_SET_ID), or a configuration type;
  - NOT_EVIDENCED_LOCAL(n);
  - OPEN_HOST_CHANNELS(n).
- Every per-cell answer is either reachable or labelled NOT_DETERMINABLE_BY_EXISTENCE_CHECK.

**(3) Why this is sufficient.** The TASK allows "reachable, or labelled". The one unreachable outcome is labelled. Every other outcome has a stated condition that the frozen commands can meet, and C.5-r2 lists the reaching condition for each value.

**(4) Prediction change.** It is now stated in advance that Stage 0 cannot deliver NIF-A. So every FIRED grade in DP-P4, DP-P7, DP-C1, P1, P5 and P8 is out of reach unless a later, separately authorized content-read or host protocol finds NIF-A (§4-r2).

**(5) Frozen rule.** C.5-r2.

## A.28 SC-2: the negative overall result

**(1) Defect.** Q5 had no UNKNOWN value, so "NO_NIF_A_CHANNEL_IN_CHECKED_SET" read as a negative finding.

**(2) Correction.**
- The Q1 values are YES_IN_CAPTURE, NOT_EVIDENCED_IN_CAPTURE and NOT_DETERMINABLE_BY_EXISTENCE_CHECK. There is **no NO**.
- Q5 values:
  - NO_BY_TYPE (configuration or identity channels are not norm-state records, by definition);
  - NO_AS_NIF_A (the norm-maker channel; or writers overlapping with the maker or executor, [ACTOR_SET_ID]);
  - NOT_DETERMINABLE_BY_EXISTENCE_CHECK.
- The overall negative wording is **[R2-REMOVED SC-2]**.

**(3) Why this is sufficient.** Every "no" that remains is a definitional or positively observed exclusion of a specific channel, never a claim about the absence of any NIF-A channel.

**(4) Prediction change.** None.

**(5) Frozen rule.** C.5-r2.

## A.29 SC-3: current tree only

**(1) Defect.** Only the paths in the current default-branch tree were checked.

**(2) Correction.**
- The path universe is **every path changed in any commit reachable from the capture**: `git log -m --name-only`, including root commits, merges against each parent, and deletions.
- Presence at each tip is recorded separately, through `ls-tree` of every tip.
- Per candidate path, the script reports: earliest and latest touch (SOURCE_STATED), whether the path was ever deleted, and whether it is present at any tip.

**(3) Why this is sufficient.** A path that existed during any window reachable in the capture was added by some reachable commit, so it appears in that commit's name list. Paths from unreachable history are outside the capture (disclosed).

**(4) Prediction change.** None.

**(5) Frozen rule.** C.3-r2 S2.

## A.30 SC-4: completeness is unreachable

**(1) Defect.** K3 and Part B's C3 needed audit-log content, so COMPLETE_SHOWN and any complete DCS were unreachable. The ranking did not say so.

**(2) Correction.**
- COMPLETE_SHOWN is labelled **NOT_REACHABLE_BY_THIS_PROTOCOL**, and Q6 = NOT_DETERMINABLE_BY_EXISTENCE_CHECK with a K-vector:
  - K1 = ND;
  - K2 = INDICATED[SOURCE_STATED] or NOT_INDICATED;
  - K3 = CHANGED_IN_CAPTURE_DURING_CORPUS_SPAN (reachable) or NO_CHANGE_IN_CAPTURE (not shown stable);
  - K4 = ND.
- §4-r2 states that every absence-premised falsifier is expected to be UNDETERMINED.

**(3) Why this is sufficient.** The unreachable grade is declared. The components that can be observed are reported. The ranking no longer implies that Part C can supply completeness.

**(4) Prediction change.** It is stated in advance that ABS premises are NOT-EVIDENCED, which gives UNDETERMINED.

**(5) Frozen rule.** C.4-r2 and §4-r2.

## A.31 SC-5: names and emails in the output

**(1) Defect.** `58_path_history_meta.txt` wrote author names and emails.

**(2) Correction.** The scoping outputs contain **no personal identities**:
- no author or committer names or emails;
- no ref names, remote URLs, CODEOWNERS owners or config paths;
- no path strings. Paths appear as 16-hex SHA-256 ids, with class and trigger token taken from a fixed vocabulary.

Identities are compared only in a private temporary directory outside the repository and outside OUT, which is deleted on exit. Only counts are written. A final **identity guard** fails the run (exit 1) if any output file contains `@`. Git's stderr is kept in the temporary directory, and only its line count is written.

**(3) Why this is sufficient.** No command writes an identity field to OUT. The only identity processing is set intersection, whose output is a number. The guard catches email-like leaks mechanically.

**(4) Prediction change.** None. Q3 and I1 use counts only, and are graded [ACTOR_SET_ID].

**(5) Frozen rule.** C.1-r2.

## A.32 SC-6: the hard-coded completeness window

**(1) Defect.** The completeness window was hard-coded to 2026-08-02..15, which is inconsistent with FR-D4.

**(2) Correction.** The script does not evaluate completeness over any window. It reports per-path and per-channel earliest and latest SOURCE_STATED dates over the full captured history. It gives the corpus-span count (2026-08-02 to 2026-08-15) only as a descriptive tag. Stage 1 compares those ranges with each prediction's FR-D4 window.

**(3) Why this is sufficient.** No window is assumed. The data needed for any window are reported.

**(4) Prediction change.** None.

**(5) Frozen rule.** C.4-r2.

## A.33 SC-7: host detection, and an over-broad STATUS_RE

**(1) Defect.** Host detection used a URL substring, and STATUS_RE was over-broad (`norm` matched `normalize`).

**(2) Correction.**
- There is no host detection. Host channels are not queried, because this is not authorized. They are listed as NOT_DETERMINABLE_BY_EXISTENCE_CHECK(HOST_NOT_AUTHORIZED).
- Status matching is **whole-token**: the path is lowercased and split on non-alphanumerics, and a token must equal a fixed vocabulary word (bigrams in+force, sign+off, work+plan).
- Status matching is restricted to document and data extensions, or to paths with no extension.

**(3) Why this is sufficient.** `normalize` is no longer the token `norm`. Code files are excluded from the token classes. Host detection is not needed.

**(4) Prediction change.** None.

**(5) Frozen rule.** C.3-r2, AWK_CH.

---

## A.S Symmetry table (the check required by the TASK)

The table uses these abbreviations:
- **"Indep."** says whether the source is independent (NIF-A only).
- **"Presupposes"** gives the hypothesis clause that the non-independent source assumes.
- **"Can fire?"** says whether this premise-and-source combination can support FIRED.

Every falsifier of both hypotheses is listed.

| Falsifier (hyp.) | Premise | Kind | Source → Indep. / presupposes / can fire? | Grade when a non-independent source is used |
|---|---|---|---|---|
| **P1-HD** (H-D.c1a) | S1 at t1 | NS | NIF-A → yes / — / **yes** · maker's records → no / H-D.c2 (SELF) / no · engineering acting on S1 → no / H-B.c2 (CROSS) / no | Maker's records: NOT_APPLICABLE (a change recorded by the maker is itself an act). Behaviour: DISJUNCTIVE{H-D.c1a; H-B.c2} CROSS |
| | S2 ≠ S1 at t2 | NS | as for S1 | as for S1 |
| | work event in [t1,t2] | OCC | ET or system-recorded outcome → neutral / — / never carries the firing | — |
| | no act producing S2; no prior conditional act; no late act | ABS | NIF-A transition record with basis "none" → yes / — / **yes** · complete DCS → no / RECORD_COMPLETENESS / no · capture only → not evidenced | Complete DCS: DISJUNCTIVE{H-D.c1a; UNRECORDED_ACT} NEUTRAL. Capture only: UNDETERMINED |
| | no legitimating act | ABS | as above; a positive legitimating act gives LEGITIMATED | as above |
| **P4-HD** (H-D.c2) | R-89 in force at t | NS | NIF-A → yes / — / **yes** · executor work citing R-89 → no / H-B.c2 (CROSS) / no · ACT-TP (ex-NIF-B) → no / H-B.c2 (CROSS) / no · N-ASSERTED by a third party → no / SOURCE_ACCURACY / no · "R-89 already governs" (DX-ASSERT) → no / SOURCE_ACCURACY + AF-N / no | DISJUNCTIVE{H-D.c2; H-B.c2} CROSS, or DISJUNCTIVE{H-D.c2; SOURCE_ACCURACY, AF-N} NEUTRAL. Under AF-B the grade differs, which gives UNDETERMINED(AF_READING) |
| | R-89 not adopted at t | ABS/NS | NIF-A status record → yes / — / **yes** · complete DCS, no AF-1/2/3 → no / RECORD_COMPLETENESS / no · capture only → not evidenced | Complete DCS: DISJUNCTIVE{H-D.c2; UNRECORDED_ACT} NEUTRAL. Capture only: UNDETERMINED. The AF readings must agree (FR-N4) |
| **P5-HD** (H-D.c1b) | capability norm at t_R94 requires DA adoption | NS | NIF-A → yes / — / **yes** · R-86/R-90 texts → no / H-D.c2 (SELF) / no | DISJUNCTIVE{H-D.c1b; H-D.c2} SELF |
| | R-94 in force | NS | NIF-A → yes · execution under R-94 → no / H-B.c2 (CROSS) · the Chief's own text → no / SOURCE_ACCURACY | DISJUNCTIVE{H-D.c1b; H-B.c2} CROSS, or NEUTRAL |
| | no DA adoption | ABS | as for P4 | as for P4 |
| **P1-HB-1a** (H-B.c1) | eligible targets (census) | OCC | system-recorded work outcomes → neutral; completeness of the census domain needs a complete EC | EC not complete → UNDETERMINED |
| | state unchanged until a separate act, for each target | NS | NIF-A → yes / — / **yes** · register shows no change → no / H-D.c1a (CROSS) / no · engineering executed nothing → no / H-B.c2 (SELF) / no | DISJUNCTIVE{H-B.c1; H-D.c1a} CROSS, or DISJUNCTIVE{H-B.c1; H-B.c2} SELF |
| | the separate act exists | OCC/TXT | the act's text → neutral | — |
| **P1-HB-1b** (H-B.c1) | as 1a, for one target | NS/OCC | as 1a | as 1a |
| **P2-HB** incl. C1 (H-B.c2) | execution event and operation class | OCC | ET plus the frozen diff rule → neutral | — |
| | not permitted at t_exec⁻ | NS | NIF-A → yes / — / **yes** · blocking texts R-72..R-80 → no / H-D.c2 (CROSS) / no · "no permitting text" → no / H-D.c1a (CROSS) / no | DISJUNCTIVE{H-B.c2; H-D.c2, H-D.c1a} CROSS (if the absences are complete), else UNDETERMINED |
| | no waiver in force at t_exec⁻ | NS/ABS | NIF-A → yes · absence of a waiver act → no / H-D.c1a (CROSS) | as above |
| **P5-HB** via P2 (H-B.c2) | R-94 not in force at execution | NS | NIF-A → yes · no adoption + status rule → no / H-D.c2 (CROSS) | DISJUNCTIVE{H-B.c2; H-D.c2} CROSS |
| | execution attested | OCC | the revision that introduced line 58, if versioned → neutral · the line-58 report → SOURCE_ACCURACY | report only → UNDETERMINED |
| **P7-HB** (H-B.c2) | ET at t_w | OCC | rank-0 ET → neutral (ET-2 only → WEAK_TRACE, a neutral condition) | — |
| | ¬ACC (WP-4B acceptance not in force at t_w⁻) | NS | NIF-A → yes · no acceptance form, or R-87 not adopted → no / H-D.c2, H-D.c1a (CROSS) | DISJUNCTIVE{H-B.c2; H-D.c2, H-D.c1a} CROSS |
| | G (R-72 gate in force) | NS | NIF-A → yes · R-72 text + adoption → no / H-D.c2 (CROSS) | DISJUNCTIVE{…; H-D.c2} CROSS |
| | ¬WV (no waiver in force; R-89 does not waive) | NS/ABS | NIF-A → yes · absence of a waiver act → no / H-D.c1a (CROSS) · R-89 text (content) → TXT neutral; R-89 force → NS as in P4 | DISJUNCTIVE CROSS, or UNDETERMINED(AF_READING) |
| **P8-HB** (H-B.c3) | verified deviation or failure | OCC/NS | system-recorded failing run → neutral · P2 non-conformance → inherits the P2 grade | P2 DISJUNCTIVE → P8 at most DISJUNCTIVE |
| | active target | NS | NIF-A → yes · the authorization text → no / H-D.c2 (CROSS) | DISJUNCTIVE CROSS |
| | no remediation or deferral in force before resumption | NS/ABS | NIF-A → yes · absence of a remediation act → no / H-D.c1a (CROSS) | DISJUNCTIVE{H-B.c3; H-D.c1a} CROSS, else UNDETERMINED |
| | next execution on the target | OCC | ET → neutral | — |
| **P7-HD, P8-HD** | — | — | NOT_APPLICABLE for R-89 (FR-D12, unchanged); otherwise scored as P1-HD | — |

**Symmetry check (summary).**

| Check | H-D falsifiers (P1-HD, P4-HD, P5-HD) | H-B falsifiers (P1-HB, P2-HB/C1, P5-HB, P7-HB, P8-HB) |
|---|---|---|
| Source classes that can carry FIRED | NIF-A only | NIF-A only |
| Behaviour (executor or third party) | DX-BEH → DISJUNCTIVE (CROSS: H-B.c2) | DX-BEH → DISJUNCTIVE (SELF: H-B.c2) |
| The maker's records as force | DX-MAK → DISJUNCTIVE (SELF: H-D.c2) or NOT_APPLICABLE | DX-MAK → DISJUNCTIVE (CROSS: H-D.c2) |
| Absence of an act | DX-ABS → DISJUNCTIVE (NEUTRAL) if complete, else UNDETERMINED | DX-ABS-INF → DISJUNCTIVE (CROSS: H-D.c1a) if complete, else UNDETERMINED |
| Maker's assertion | DX-ASSERT → DISJUNCTIVE (NEUTRAL); both AF readings required | DX-ASSERT (R-89 waiving, via WV) → the same, with both AF readings required |
| Fallback to FIRED-CONDITIONAL on maker's records | **none** | **none** |
| Neutral FIRED-CONDITIONAL conditions | SOURCE_STATED_TIME, ACTOR_SET_ID | SOURCE_STATED_TIME, ACTOR_SET_ID, WEAK_TRACE |

The CROSS/SELF tag differs between the columns because each source class presupposes a fixed hypothesis. The *grade* per source class is identical in both columns. That identity is the symmetry requirement.

WEAK_TRACE appears only on the H-B side because only H-B falsifiers have an ET occasion premise. It is a neutral condition, not a hypothesis clause.

---

# PART A2. Amended sections of the specification

## §0.3-r2 The distinctions [R2-CHANGED N1, N2, N13]

| Code | Distinction | Established by | Never established by |
|---|---|---|---|
| N-EXISTS | The norm's text is recorded | the record (TXT/OCC) | — |
| **N-FORCE** | The norm is in force at t | **NIF-A only** | behaviour (executor or third party); the maker's records; assertions; recording times; absences |
| **N-ASSERTED(X)** | X states that N is in force | X's statement | — (non-factive) |
| **N-KNOWN(X)** | X knows that N is in force (factive) | N-FORCE by NIF-A ∧ N-ASSERTED(X) | — |
| **ACT-CONF / ACT-CONTRA** | An act is according to, or contrary to, the norm in force | an ET plus N-FORCE by NIF-A of a permitting or non-permitting state at t⁻ | texts alone; absences |
| **ACT-CONF_TEXT / ACT-CONTRA_TEXT** | The act is according to, or contrary to, what the maker's texts say | an ET plus V_text-style reading of the texts recorded before t | — (as a falsifier premise it is DX-MAK/DX-ABS-INF) |
| **ACT-TP** | A third party acts on N as binding | its record, with AUTH_ID or ACTOR_SET_ID and time | — (never N-FORCE) |
| N-CHANGE-NOACT | The norm changes without an act | N-FORCE at t1 ≠ at t2, both by NIF-A, **and** NIF-A records the transition with no act as its basis | work events; absences in the DCS alone (DX-ABS → DISJUNCTIVE) |

## §0.4-r2 The independent observable of the norm in force [R2-CHANGED N1, N3]

- **NIF-A** is the only NIF. It is an entry in a channel meeting I1–I4 that records NORM_STATE (in force / not in force / status: prepared, adopted or withdrawn / active / waived), with a system-generated time.
- An NIF-A entry is used with the neutral condition RECORDER_ACCURACY.
- **[R2-REMOVED]** NIF-B, NIF-C and the graded fallback. See G-SYM (§A.0) for how behaviour, the maker's records, absences and assertions are graded instead.

## §0.6-r2 DCS and adoption forms [R2-CHANGED N4, N13]

- **DCS v1** = DCS v0 ∪ {channels with Q2 ∋ ADOPTION_EVENT or APPROVAL_EVENT}. NORM_STATE channels are NIF-A candidates, not DCS members.
- Because Part C-r2 cannot determine Q2 for content channels, DCS v1 = DCS v0, with the paths located by Part C (as ids).
- AF-1, AF-2 and AF-3 are unchanged. MAKER_FORCE_ASSERTION is graded under AF-N and AF-B (FR-N4).
- The rest of §0.6 is unchanged.

## §0.8-r2 Absence and completeness [R2-CHANGED R0-2, SC-4]

The r1 criteria are unchanged. This is added:
- The rank-0 capture is the local ref set, with completeness NOT_SHOWN.
- Part C-r2 cannot reach COMPLETE_SHOWN.
- So every ABS premise is NOT-EVIDENCED-IN-CAPTURE unless NIF-A records it. G-SYM step 2 then gives UNDETERMINED.

## §0.9-r2 Vocabulary [R2-CHANGED N13]

- **Falsifier grades:** NOT_APPLICABLE; UNDETERMINED(reason); NOT-FIRED; NON-DISCRIMINATING; FIRED; FIRED-CONDITIONAL(SOURCE_STATED_TIME | ACTOR_SET_ID | WEAK_TRACE); DISJUNCTIVE{…}[CROSS | SELF/NEUTRAL]; LEGITIMATED (P1); VACUOUS (status contrast); NOT_TESTABLE_AS_SPECIFIED (the P8 tendency reading).
- **Prediction status:** DISCRIMINATING_IN_PRINCIPLE, ONE-SIDED(H-x) or NON-DISCRIMINATING.
- **Order labels** (unchanged): RECORDING_ORDER, SOURCE_STATED_ORDER, INFERRED_ORDER, NOT_IDENTIFIABLE.
- **Rank-0 labels:** EXECUTION_TRACE_FOUND, MENTION_ONLY, VARIANT_ONLY, NOT-EVIDENCED-IN-CAPTURE, CAPTURE_INVALID, CAPTURE_ERROR. ABSENT_IN_COMPLETE_CHANNEL is removed.
- **Scoping labels:** YES_IN_CAPTURE, NOT_EVIDENCED_IN_CAPTURE, NOT_DETERMINABLE_BY_EXISTENCE_CHECK, NOT_REACHABLE_BY_THIS_PROTOCOL, NO_BY_TYPE, NO_AS_NIF_A, CLIENT_SET, YES_LOCAL, INDICATED, NOT_INDICATED, CHANGED_IN_CAPTURE_DURING_CORPUS_SPAN, NO_CHANGE_IN_CAPTURE.
- **Other labels:** POSSIBLY_LATE, OUT_OF_CHANNEL_ACT, CAPTURE_PATTERN (descriptive, never scored), NO_OCCASION_IN_CAPTURE, NOT_FROZEN_CLAUSE.

## §1-P1-r2 [R2-CHANGED N12, D8]

- Eligibility, the work-outcome types, the expected changes and the satisfaction keyword table are as in A.15.
- `response_or_legitimation` is read from t2 to capture (FR-N9).
- The census domain for 1a is limited to targets with system-recorded outcomes. Its completeness needs a complete EC, which is not shown, so 1a is UNDETERMINED(EC_NOT_COMPLETE).
- Grades follow G-SYM.

## §1.7-r2 NIF matrix [R2-CHANGED N1, N3]

Every limb requires **NIF-A**. Without NIF-A, a limb is:
- **DISJUNCTIVE**, per the symmetry table, when its premises come from DX sources and every absence is shown complete;
- **UNDETERMINED**, when an absence is not evidenced;
- **NON-DISCRIMINATING**, when no premise is established at all.

The r1 entries "NIF-B" and "graded fallback" are deleted from every row.

## §2-r2 C1 (the R11 commit "WP-4B RED") [R2-CHANGED D9, N2, N5, N6]

The items to acquire are as in r1, except that the item "entry-into-force times … not earlier than recording" is **[R2-REMOVED N5]**. It is replaced by: "NIF-A records of the state of WP-4B at 16:10⁻, if any NIF-A channel is later authorized".

**Textual verdict V_text** (a function, first match wins; "recorded before R11" means RECORDING_ORDER or SOURCE_STATED_ORDER relative to the R11 commit):
- **V1 UNDETERMINED(TEXT_MISSING | ORDER_NOT_IDENTIFIABLE | DIFF_UNCLASSIFIED)** if any of R-72..R-80 was not obtained, or the recording order of a deciding text relative to R11 is NOT_IDENTIFIABLE, or the R11 diff cannot be classified by the frozen rule.
- **V2 NON-CONFORMS_TEXT** if a text recorded before R11 **positively** blocks WP-4B, or states that a named R-72 precondition is unresolved (under the default scope, all WP-4B work including RED), and no text in the capture recorded before R11 lifts that block or narrows it to exclude RED.
- **V3 CONFORMS_TEXT** if a text recorded before R11 **positively** states that R-72's proviso is met, or that RED may proceed, and no text recorded before R11 positively blocks RED.
- **V4 UNDETERMINED(PROVISO_NOT_EVIDENCED)** otherwise.

V2 and V3 are exclusive: V3 requires that no positive block exists, and V2 requires that one does.

**Bearing on H-B.c2 (DP-C1, first match wins):**
1. V_text ∈ {V1, V4} → UNDETERMINED (same reason).
2. NIF-A shows WP-4B not permitted at 16:10⁻ and no waiver in force → **FIRED-CONDITIONAL(SOURCE_STATED_TIME)**, because 16:10 is a git date. If NIF-A shows it permitted → **NOT-FIRED**.
3. V_text = V3 → **NON-DISCRIMINATING** (a DX-MAK acquittal).
4. V_text = V2, and the absence "no lifting or narrowing text recorded before R11" is shown complete → **DISJUNCTIVE{H-B.c2; H-D.c2, H-D.c1a} CROSS**.
5. V_text = V2, and that absence is not shown complete → **UNDETERMINED(ABSENCE_NOT_EVIDENCED)**, with CAPTURE_PATTERN = NON-CONFORMS_TEXT.

Any response to R11 is scored under P8 (FR-D6, unchanged).

## §3-r2 Status mediation [CONSEQUENTIAL N1]

FR-D13 is unchanged except for this: item (2) "NIF-B uptake" is recorded as ACT-TP. "Independently observed" requires item (0), NIF-A. Items (1) ∧ (2) ∧ (3) give PARTIALLY_INDEPENDENT at most.

## §4-r2 Ranking [R2-CHANGED SC-1, SC-4, N1; PROPOSAL]

Given SC-1 and SC-4, Stage 0 cannot deliver NIF-A or completeness. The realistic outcome for **every** falsifier, on both sides, is DISJUNCTIVE, UNDETERMINED or NON-DISCRIMINATING. The ranking is therefore by the probability of a result that is not UNDETERMINED, and then by effort:

0. **Stage 0** (Parts B and C). Cheap, and a prerequisite.
1. **A human decision on an NIF-A route.** This means authorizing either a content read of the OPEN_LOCAL_CANDIDATES, or host queries (audit, protection, CI runs). It is the only route by which any falsifier can reach FIRED. It is a PROPOSAL, and it is not part of this spec's frozen protocols.
2. **P2/C1.** The texts are named and cheap to obtain. V_text is likely V2, and the bearing is likely UNDETERMINED(ABSENCE_NOT_EVIDENCED).
3. **P4.** Ungated. It is likely UNDETERMINED(AF_READING) or NON-DISCRIMINATING.
4. **P7.** Only if a trace exists. It is likely DISJUNCTIVE CROSS or UNDETERMINED.
5. **P5, P8 and P1.** Low.

## §5-r2 Acquisition and decision procedures [R2-CHANGED N7, N8, N9, N10, N11]

**Stage 1 items.**

| Item | Content | Gate |
|---|---|---|
| S1-a | R-89 text, including whether it waives or supersedes R-72's gate | none (P4, P7) |
| S1-b | Full DCS v1 history, **root → capture**: every AF-1/2/3 form, waiver, acceptance, remediation and MAKER_FORCE_ASSERTION entry for R-72, R-87, R-89, WP-4B and WP-4C-1; POSSIBLY_LATE flags | none |
| S1-c | R-87 text; acceptance state (NIF-A if any) | trace |
| S1-d | R-72 text; gate state (NIF-A if any) | trace |
| S1-e | NIF-A records for R-89, R-87, WP-4B and WP-4C-1, if an NIF-A channel is authorized | none |
| S1-f | ACT-TP and N-ASSERTED entries naming R-89 or WP-4B's acceptance, recorded as acts or assertions only | none |
| S1-g | Link from each ancestry-minimal ET to R-89 (the CITES_R89 flag and message text) | trace |
| S1-h | References to R11 in R-91, R-94 and the DCS (P8) | none |

**Variables**, for each ancestry-minimal ET e (with t_w = e):
- **TR**: the rank-0 existence label.
- **ACC, G, WV, AD, F89**: each a value (T/F) with a source grade: **A** (NIF-A), **D** (DX source with the absences shown complete), or **U** (not established, NOT_IDENTIFIABLE, or absence not evidenced).
- AD and F89 are evaluated under AF-N and AF-B (FR-N4).

**DP-P7** (P7-HB; first match wins; evaluated for each minimal ET; the reported grade is the strongest over the ETs, in the order FIRED > FIRED-CONDITIONAL > DISJUNCTIVE > NOT-FIRED > NON-DISCRIMINATING > UNDETERMINED, and each ET's grade is also reported):
1. TR ∈ {CAPTURE_ERROR, CAPTURE_INVALID} → UNDETERMINED(CAPTURE).
2. TR ∈ {NOT-EVIDENCED-IN-CAPTURE, MENTION_ONLY} → NO_OCCASION_IN_CAPTURE. This is not NOT-FIRED, because absence of work is not shown.
3. TR = VARIANT_ONLY → NO_OCCASION_IN_CAPTURE, and the case is escalated to the human for a protocol decision.
4. Any of ACC, G or WV is graded U (under either AF reading, where AD or F89 enters WV) → UNDETERMINED(list). If the AF readings differ → UNDETERMINED(AF_READING).
5. ACC = T → NOT-FIRED if its grade is A; NON-DISCRIMINATING if D.
6. G = F → as in 5.
7. WV = T → as in 5.
8. ACC = F, G = T, WV = F, all graded A → FIRED. With a SOURCE_STATED t_w, or WEAK_TRACE when every minimal ET is ET-2 → FIRED-CONDITIONAL(list).
9. Otherwise (the condition is true with ≥1 D premise) → DISJUNCTIVE{H-B.c2; presuppositions of the D premises} CROSS.

**DP-P4** (P4-HD; not gated; first match wins):
1. S1-a not obtained → UNDETERMINED(TEXT_MISSING).
2. NIF-A records R-89 in force at some t, and NIF-A records R-89 not adopted at t → FIRED (this overrides the AF readings).
3. NIF-A records R-89 not in force throughout the P4 window, or adopted before any recorded force → NOT-FIRED.
4. Evaluate under AF-N and AF-B. If their results (from rows 5–7) differ → UNDETERMINED(AF_READING).
5. F89 not established by any source → NON-DISCRIMINATING.
6. AD = F not shown complete (capture only) → UNDETERMINED(ABSENCE_NOT_EVIDENCED).
7. F89 from DX-BEH (executor work or ACT-TP citing R-89) → DISJUNCTIVE{H-D.c2; H-B.c2} CROSS. From DX-ASSERT only → DISJUNCTIVE{H-D.c2; SOURCE_ACCURACY, AF-N} NEUTRAL.

**Joint reporting.** DP-P4, DP-P7 and DP-C1 are reported separately. When two procedures return the same DISJUNCTIVE set (for example DP-P4 row 7 and DP-P7 row 9, both {H-D.c2; H-B.c2}), it is one finding, not two. No procedure's result is used as a premise in another, except P8's inheritance of the P2 grade (symmetry table).

---

# PART B. Rank-0 protocol r2: WP-4C-1 execution trace (executable in ENVIRONMENT.md)

## B.0 Environment and declared capture

- **Environment (SOURCE FACT, ENVIRONMENT.md):**
  - git 2.55.0, in an ordinary working clone (not bare);
  - 7 local branches, 16 remote-tracking refs and 1 tag;
  - 4002 commits reachable from all local refs;
  - origin is on GitHub;
  - `refs/pull/*`, audit logs and push times are out of scope, and host access is not authorized;
  - a `.github` directory exists, and it is not inspected by rank-0.
- **Capture (frozen):**
  - the **local ref set**, which is every ref printed by `git for-each-ref` at run time, stored in `01_refs.tsv`;
  - the walk starts from exactly those ref tips, with tags peeled to commits;
  - HEAD, reflogs and unreachable objects are **not** part of the capture;
  - **completeness is NOT shown**, so any absence is **NOT-EVIDENCED-IN-CAPTURE**.
- **Read-only:**
  - the script uses only read-only git commands: `for-each-ref`, `rev-list`, `rev-parse`, `log`, `show`, `cat-file`, `merge-base --is-ancestor`, `config --get` and `--version`;
  - it also uses `sort`, `awk`, `cut`, `sha256sum`, `find`, `ls` and `realpath`;
  - it sets `GIT_OPTIONAL_LOCKS=0` and `GIT_NO_LAZY_FETCH=1`;
  - it runs no network command;
  - it writes only inside OUT, which must be outside the repository and new or empty.
- **Deterministic:**
  - `LC_ALL=C` and `TZ=UTC`;
  - global and system config are ignored;
  - diff and log behaviour is forced by `-c` overrides;
  - every set is sorted, and no machine path, URL or name is written.
- **Exit codes:** these are handled explicitly by `ok LABEL RC ALLOWED...`:
  - grep and `merge-base --is-ancestor` may return 1;
  - `config --get` may return 1;
  - any other code is recorded in `00_errors.tsv`, which makes the result CAPTURE_ERROR.

  The script's own exits are 0 (completed), 1 (completed with unexpected codes), 2 (refused to start) and 3 (CAPTURE_INVALID before the walk).

## B.1 Channels searched

For every commit reachable from the capture, the script searches:
1. the message (subject, trailer values and body), with `--grep`;
2. added or removed lines, with `-G`:
   - non-merge commits against their parent;
   - merges against their first parent, counted only on their own paths (B.3);
3. changed path names, from a full `--name-status` dump for non-merges and the combined-diff own paths for merges;
4. **ref names**: matching refs, and the commits exclusive to them.

The positive control is `WP-4B RED` (fixed string) in messages. If it is empty, the result is CAPTURE_INVALID.

The anchors are R-72, R-87 and R-89 (message and diff). They record only when text naming those rulings was recorded in the repository.

## B.2 Target matching (frozen)

- **HY** is one of these byte strings: `-`, or U+2010, U+2011, U+2012, U+2013, U+2014, U+2015, U+2212, U+FE58, U+FE63, U+FF0D or U+00AD (UTF-8).
- **Boundaries:** left = start of line or a byte that is not `[0-9A-Za-z]`; right = end of line or such a byte. So `WP-4C-10`, `WP-4C-1a` and `XWP-4C-1` are excluded, while `feature/WP-4C-1`, `feature-WP-4C-1-x` and `(WP-4C-1)` match.
- **PAT (primary, counted):** `WP HY 4C HY 1`, case-sensitive. Typographic-hyphen forms count as primary. This is a PROPOSAL: substituting a hyphen is a typographic transform, not an identification inference.
- **VAR (variant, reported, never counted):** `[Ww][Pp] SEP? 4 SEP? [Cc] SEP? 1`, where SEP ∈ HY ∪ {space, `_`, `.`, `/`}. VARIANT commits = VAR hits minus the hit set. If only variants exist, the result is VARIANT_ONLY and the case is escalated to the human.
- **Excluded:** bare `WP-4C`; `WP-4`, `§WP-4` and `WP-4B`; and identification by technical content.
- **Ref names:** PAT or VAR on the full ref name, with the same boundaries.
- **Merges:** see B.3.

## B.3 Execution trace versus mention (frozen, applied by the classifier AWK_CLASS)

**Path classes** (lower-cased):
- **CI definition paths are NON_DOC:** `.github/workflows/`, `.gitlab-ci.yml`, `.circleci/` and `Jenkinsfile`.
- **DOC_GOV** is any of:
  - an extension `md | markdown | txt | rst | adoc | org`;
  - a directory or file segment in {doc(s), governance, ruling(s), decision(s), adr(s), session(s), log(s), notes, plan(s), planning, minutes};
  - a basename `<stem>[-_.…].(yaml | yml | json | csv | tsv | toml)`, where the stem is in {register(s), ruling(s), decision(s), queue, work[-_]plan(s), plan(s), backlog, roadmap, status, progress, context, session(s), minutes, adr(s)}.
- **NON_DOC** is everything else.

**Features per hit commit:**
- **S, T, B**: PAT in the subject, a trailer value, or the body.
- **k**: the number of NON_DOC paths changed. For merges, only the **own** paths are counted, meaning paths whose blob differs from every parent (combined diff).
- **al**: PAT on an added line in a NON_DOC path (an own path, for merges) that is **not comment-only**. A comment-only line is one whose first non-blank characters are `# // /* * -- ; <!-- % """ '''` or `rem`.
- **pn**: a NON_DOC path name matches PAT (status A, M or T; or an own path).
- **rx**: the commit is exclusive to a PAT-named ref.
- **fnt**: the subject's first WP identifier (`WP HY digits [letter] [HY digits]`) is not WP-4C-1.

**Class** (first match wins):

| Clause | Condition | Class |
|---|---|---|
| E1 | k ≥ 1 ∧ (S ∨ T) ∧ ¬fnt | **ET-1** |
| E2a | k ≥ 1 ∧ (S ∨ T ∨ B) | **ET-2** (this includes a subject hit demoted by fnt) |
| E2b | al | **ET-2** |
| E2c | pn | **ET-2** |
| E2d | rx ∧ k ≥ 1 | **ET-2** |
| M-MERGE / M-COMMENT / M-REMOVAL / M-DOC / M-OTHER | otherwise | **MENTION** |

**Flags:** MIXED, FIRST_WP_NOT_TARGET, CITES_R89, COMMENT_HIT, REMOVAL_HIT, ON_TARGET_NAMED_REF, MERGE and SIGNED (a `gpgsig` header is present; the keyring is not consulted).

A tokened merge with no own changes is MENTION (M-MERGE). The side commits it integrates are **not** promoted.

Rulings, plans and reports are never traces, even when they say that work was done: they are MENTION/REPORT in the descriptive sense.

## B.4 Timestamps and order

- `%at` and `%ct` (and `%aI` and `%cI`) are **SOURCE_STATED**, because they are client-set.
- Ancestry (`merge-base --is-ancestor`) gives **RECORDING_ORDER**.
- **CLOCK_ANOMALY:** for every ancestry pair found, ct(descendant) < ct(ancestor). Both commits are flagged, and every date-based order involving them is NOT_IDENTIFIABLE.
- **Order label** for each (minimal ET, node) pair, where a node is the control or an anchor commit:
  - RECORDING_ORDER, if ancestry holds in either direction;
  - SOURCE_STATED_ORDER, if the author-date order equals the committer-date order, the two are not equal, and neither commit is anomalous;
  - NOT_IDENTIFIABLE otherwise.

  INFERRED_ORDER is never computed by the script. It may be asserted in the report only with the chain written out, and the reviewer checks it.
- **Earliest trace** = the ancestry-minimal ET set (`97b`). The ancestry-minimal ET-1 set (`97c`) is also reported. If `97c` is empty while `97b` is not, WEAK_TRACE applies.
- No git time establishes a decision time or entry into force. The order relative to the decision or force of R-72, R-87, R-89 or WP-4B's acceptance is **always NOT_IDENTIFIABLE** from this channel. This is unchanged.

## B.5 Absence

- **NOT-EVIDENCED-IN-CAPTURE** = the control is non-empty, there are no unexpected exit codes, and there is no ET-1 and no ET-2.
- It never means that no work occurred, because:
  - the capture is local only;
  - the search is identifier-based;
  - work may exist outside git.
- ABSENT_IN_COMPLETE_CHANNEL is not a possible output.

## B.6 Window

- There is **no lower bound and no date filter** (FR-D4, unchanged).
- The upper bound is the capture.
- The corpus span (2026-08-02..15) and PRE_R72/POST_CORPUS tags are derived by the reviewer from `95` (%at and %ct), and they are descriptive only.

## B.7 The exact script: `rank0-r2.sh`

```bash
#!/usr/bin/env bash
# rank0-r2.sh -- frozen by SPEC-r2 Part B. Do not edit.
# Capture = the local ref set (every ref printed by `git for-each-ref`) of the ordinary working clone.
# Completeness of the capture is NOT shown. Read-only. No network. No host access. No `set -e`.
# Usage (from anywhere inside the working clone):  OUT=/absolute/path/to/new/empty/dir bash rank0-r2.sh
# Exit: 0 completed (read 80_status.tsv) | 1 completed with unexpected exit codes (CAPTURE_ERROR)
#       2 refused to start (nothing searched) | 3 stopped before the walk (CAPTURE_INVALID)
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_NO_REPLACE_OBJECTS=1 GIT_OPTIONAL_LOCKS=0 GIT_NO_LAZY_FETCH=1 GIT_TERMINAL_PROMPT=0
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE GIT_CEILING_DIRECTORIES
TAB=$'\t'
die() { printf 'rank0-r2: %s\n' "$2" >&2; exit "$1"; }

# ---- P0 preconditions (exit 2: nothing is searched) ----
OUT="${OUT:-}"; [ -n "$OUT" ] || die 2 "OUT is not set"
case "$OUT" in /*) ;; *) die 2 "OUT must be an absolute path" ;; esac
[ "$(git rev-parse --is-inside-work-tree 2>/dev/null)" = true ] || die 2 "not inside a work tree"
[ "$(git rev-parse --is-bare-repository 2>/dev/null)" = false ] || die 2 "bare repository"
TOP="$(git rev-parse --show-toplevel 2>/dev/null)" || die 2 "no toplevel"
GD="$(git rev-parse --absolute-git-dir 2>/dev/null)" || die 2 "no git dir"
OUT="$(realpath -m -- "$OUT")" || die 2 "realpath failed"
for d in "$(realpath -- "$TOP")" "$(realpath -- "$GD")"; do
  case "$OUT/" in "$d"/*) die 2 "OUT must be outside the repository" ;; esac
done
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die 2 "OUT exists and is not an empty directory"; fi
mkdir -p -- "$OUT" || die 2 "cannot create OUT"
cd -- "$TOP" || die 2 "cannot cd to the toplevel"

ERR="$OUT/00_errors.tsv"; LOG="$OUT/00_stderr.log"; : > "$ERR"; : > "$LOG"
ok() { # ok LABEL RC ALLOWED... : record an unexpected exit code; never aborts
  local l="$1" rc="$2" a; shift 2
  for a in "$@"; do [ "$rc" -eq "$a" ] && return 0; done
  printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1
}
g() { git --no-replace-objects -c core.quotepath=off -c core.fsmonitor=false -c log.showSignature=false \
  -c log.mailmap=false -c log.decorate=false -c log.showRoot=true -c diff.renames=false \
  -c diff.noprefix=false -c diff.mnemonicPrefix=false -c diff.relative=false -c color.ui=never "$@" 2>>"$LOG"; }
lst() { # lst LABEL FILE git-args... : git must exit 0; keep non-empty lines, sorted and unique
  local l="$1" f="$2" ps; shift 2
  g "$@" > "$f.tmp"; ok "$l" $? 0
  awk 'NF' "$f.tmp" | sort -u > "$f"; ps=("${PIPESTATUS[@]}"); ok "$l.awk" "${ps[0]}" 0; ok "$l.sort" "${ps[1]}" 0
  rm -f -- "$f.tmp"
}
cnt() { awk 'END{print NR}' "$1"; }
finish() { # finish EXIT : hash every output file; print the SHA-256 of SHA256SUMS
  ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"
  sha256sum < "$OUT/SHA256SUMS" | cut -d' ' -f1
  exit "$1"
}

# ---- frozen patterns (B.2); HY = ASCII hyphen or one of 11 Unicode hyphen/dash code points, as UTF-8 bytes ----
HY=$'(-|\xe2\x80\x90|\xe2\x80\x91|\xe2\x80\x92|\xe2\x80\x93|\xe2\x80\x94|\xe2\x80\x95|\xe2\x88\x92|\xef\xb9\x98|\xef\xb9\xa3|\xef\xbc\x8d|\xc2\xad)'
LB='(^|[^0-9A-Za-z])'; RB='($|[^0-9A-Za-z])'
CORE="WP${HY}4C${HY}1"
PAT="${LB}${CORE}${RB}"                        # git/grep: line-anchored ERE
PATX="[^0-9A-Za-z]${CORE}[^0-9A-Za-z]"          # awk: applied to " " s " "
SEP="(${HY}|[ _./])"
VCORE="[Ww][Pp]${SEP}?4${SEP}?[Cc]${SEP}?1"
VAR="${LB}${VCORE}${RB}"; VARX="[^0-9A-Za-z]${VCORE}[^0-9A-Za-z]"
WPANY="WP${HY}[0-9]+[A-Za-z]?(${HY}[0-9]+)?"; WPC="^${CORE}\$"
R89X="[^0-9A-Za-z]R${HY}89[^0-9A-Za-z]"
RP72="${LB}R${HY}72${RB}"; RP87="${LB}R${HY}87${RB}"; RP89="${LB}R${HY}89${RB}"
CTRL='WP-4B RED'
DX=(--no-renames --no-ext-diff --no-textconv)

# ---- S0 environment (no paths, no names) and preflight validity ----
{ printf 'git_version\t%s\n' "$(git --version)"
  printf 'is_bare\t%s\n' "$(g rev-parse --is-bare-repository)"
  printf 'is_shallow\t%s\n' "$(g rev-parse --is-shallow-repository)"; } > "$OUT/00_env.tsv"
PC="$(g config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
PR="$(g config --get-regexp '^remote\..*\.(promisor|partialclonefilter)$')"; ok cfg.promisor $? 0 1
NPR="$(printf '%s' "$PR" | awk 'NF{c++} END{print c+0}')"
printf 'partial_clone\t%s\npromisor_or_filter_entries\t%s\n' "$([ -n "$PC" ] && echo set || echo unset)" "$NPR" >> "$OUT/00_env.tsv"
SS="$(sha256sum < "${BASH_SOURCE[0]}")"; ok script.sha256 $? 0; printf '%s\n' "${SS%% *}" > "$OUT/00_script.sha256"
SH="$(g rev-parse --is-shallow-repository)"
if [ "$SH" != false ] || [ -n "$PC" ] || [ "$NPR" != 0 ]; then
  printf 'existence\tCAPTURE_INVALID\nreason\tshallow_or_partial_clone\n' > "$OUT/80_status.tsv"; finish 3
fi

# ---- S1 capture = local ref set ----
g for-each-ref --format='%(objectname)%09%(objecttype)%09%(*objectname)%09%(*objecttype)%09%(refname)' > "$OUT/01_refs.tmp"; ok refs $? 0
sort -t "$TAB" -k5,5 "$OUT/01_refs.tmp" > "$OUT/01_refs.tsv"; ok refs.sort $? 0; rm -f -- "$OUT/01_refs.tmp"
awk -F'\t' -v nc="$OUT/01b_noncommit_refs.tsv" 'BEGIN{printf "" > nc}
  $2=="commit"{print $1; next} $2=="tag" && $4=="commit"{print $3; next} {print $2 "\t" $5 > nc}' "$OUT/01_refs.tsv" | sort -u > "$OUT/01c_tips.txt"
ps=("${PIPESTATUS[@]}"); ok tips.awk "${ps[0]}" 0; ok tips.sort "${ps[1]}" 0
awk -F'\t' '{c = ($5 ~ /^refs\/heads\//) ? "heads" : (($5 ~ /^refs\/remotes\//) ? "remotes" : (($5 ~ /^refs\/tags\//) ? "tags" : "other")); n[c]++}
  END{printf "heads\t%d\nremotes\t%d\ntags\t%d\nother\t%d\n", n["heads"], n["remotes"], n["tags"], n["other"]}' "$OUT/01_refs.tsv" > "$OUT/01d_ref_counts.tsv"
TIPS="$OUT/01c_tips.txt"
if [ ! -s "$TIPS" ]; then printf 'existence\tCAPTURE_INVALID\nreason\tno_commit_refs\n' > "$OUT/80_status.tsv"; finish 3; fi
lst commits "$OUT/02_commits.txt" rev-list --stdin < "$TIPS"
cnt "$OUT/02_commits.txt" > "$OUT/02_commit_count.txt"

# ---- S2 positive control (empty => CAPTURE_INVALID) ----
lst control "$OUT/05_control.txt" log --stdin -F --grep="$CTRL" --format=%H < "$TIPS"

# ---- S3 primary passes over the whole captured history (no lower bound, no date filter) ----
lst msg "$OUT/10_msg_pat.txt"           log --stdin -E --grep="$PAT" --format=%H < "$TIPS"
lst dnm "$OUT/11_diff_pat_nonmerge.txt" log --stdin --no-merges "${DX[@]}" -G"$PAT" --format=%H < "$TIPS"
lst dm  "$OUT/12_diff_pat_merge.txt"    log --stdin --merges --diff-merges=first-parent "${DX[@]}" -G"$PAT" --format=%H < "$TIPS"
g log --stdin --no-merges "${DX[@]}" --name-status --format='::commit::%x09%H' < "$TIPS" > "$OUT/13a_paths_nonmerge.tsv"; ok paths.nonmerge $? 0
g log --stdin --merges --diff-merges=combined "${DX[@]}" --name-only --format='::commit::%x09%H' < "$TIPS" > "$OUT/13b_paths_merge_own.tsv"; ok paths.merge $? 0
: > "$OUT/14_path_pat.tsv"; : > "$OUT/24_path_var.tsv"
awk -F'\t' -v pat="$PATX" -v var="$VARX" -v fp="$OUT/14_path_pat.tsv" -v fv="$OUT/24_path_var.tsv" '
  $1=="::commit::" && NF==2 {h=$2; next}
  NF==0 {next}
  { if (FILENAME ~ /13b_paths_merge_own[.]tsv$/) {st="O"; p=$0} else {st=$1; p=$2}
    s=" " p " "
    if (s ~ pat) print h "\t" st "\t" p > fp; else if (s ~ var) print h "\t" st "\t" p > fv }' \
  "$OUT/13a_paths_nonmerge.tsv" "$OUT/13b_paths_merge_own.tsv"; ok pathscan $? 0
sort -u -o "$OUT/14_path_pat.tsv" "$OUT/14_path_pat.tsv"; ok sort14 $? 0
sort -u -o "$OUT/24_path_var.tsv" "$OUT/24_path_var.tsv"; ok sort24 $? 0

# ---- S4 ref names, and commits exclusive to PAT-named refs ----
awk -F'\t' -v pat="$PATX" -v var="$VARX" '{s=" " $5 " "; t = ($2=="commit") ? $1 : (($2=="tag" && $4=="commit") ? $3 : "-")
  if (s ~ pat) print "PAT\t" t "\t" $5; else if (s ~ var) print "VAR\t" t "\t" $5}' "$OUT/01_refs.tsv" > "$OUT/15_refname_hits.tsv"; ok refscan $? 0
awk -F'\t' -v pat="$PATX" '(" " $5 " ") !~ pat {t = ($2=="commit") ? $1 : (($2=="tag" && $4=="commit") ? $3 : ""); if (t != "") print "^" t}' \
  "$OUT/01_refs.tsv" | sort -u > "$OUT/16a_negative_tips.txt"
ps=("${PIPESTATUS[@]}"); ok neg.awk "${ps[0]}" 0; ok neg.sort "${ps[1]}" 0
: > "$OUT/16_ref_exclusive.tsv"
while IFS="$TAB" read -r kind tip rn <&3; do
  [ "$kind" = PAT ] && [ "$tip" != "-" ] || continue
  { printf '%s\n' "$tip"; cat "$OUT/16a_negative_tips.txt"; } | g rev-list --no-merges --stdin > "$OUT/16.tmp"
  ps=("${PIPESTATUS[@]}"); ok "refx.$tip" "${ps[1]}" 0
  awk -v r="$rn" 'NF{print $1 "\t" r}' "$OUT/16.tmp" >> "$OUT/16_ref_exclusive.tsv"
done 3< "$OUT/15_refname_hits.tsv"
rm -f -- "$OUT/16.tmp"; sort -u -o "$OUT/16_ref_exclusive.tsv" "$OUT/16_ref_exclusive.tsv"

# ---- S5 hit set (R0-3: every primary source feeds it) ----
{ cat "$OUT/10_msg_pat.txt" "$OUT/11_diff_pat_nonmerge.txt" "$OUT/12_diff_pat_merge.txt"
  cut -f1 "$OUT/14_path_pat.tsv"; cut -f1 "$OUT/16_ref_exclusive.tsv"; } | awk 'NF' | sort -u > "$OUT/90_hitset.txt"

# ---- S6 variant passes (reported separately; never counted) ----
lst vmsg "$OUT/20_msg_var.txt"           log --stdin -E --grep="$VAR" --format=%H < "$TIPS"
lst vdnm "$OUT/21_diff_var_nonmerge.txt" log --stdin --no-merges "${DX[@]}" -G"$VAR" --format=%H < "$TIPS"
lst vdm  "$OUT/22_diff_var_merge.txt"    log --stdin --merges --diff-merges=first-parent "${DX[@]}" -G"$VAR" --format=%H < "$TIPS"
{ cat "$OUT/20_msg_var.txt" "$OUT/21_diff_var_nonmerge.txt" "$OUT/22_diff_var_merge.txt"; cut -f1 "$OUT/24_path_var.tsv"
  awk -F'\t' '$1=="VAR" && $2!="-"{print $2}' "$OUT/15_refname_hits.tsv"; } | awk 'NF' | sort -u | comm -23 - "$OUT/90_hitset.txt" > "$OUT/29_variant_only_commits.txt"

# ---- S7 anchors: when text naming R-72 / R-87 / R-89 was recorded (never decision or force) ----
for n in 72 87 89; do v="RP$n"
  lst "a$n.msg"  "$OUT/40_anchor_R${n}_msg.txt"  log --stdin -E --grep="${!v}" --format=%H < "$TIPS"
  lst "a$n.diff" "$OUT/41_anchor_R${n}_diff.txt" log --stdin "${DX[@]}" -G"${!v}" --format=%H < "$TIPS"
done

# ---- S8 per-hit features ----
AWK_LINES=$(cat <<'EOF'
function iscomment(s) { sub(/^[ \t]+/, "", s); return (s ~ /^(#|\/\/|\/\*|\*|--|;|<!--|%|"""|'''|[Rr][Ee][Mm][ \t])/) }
/^diff / { hdr = 1; op = ""; np = ""; next }
hdr && /^--- / { op = ($0 == "--- /dev/null") ? "" : substr($0, 7); next }
hdr && /^\+\+\+ / { np = ($0 == "+++ /dev/null") ? "" : substr($0, 7); next }
/^@@/ { hdr = 0; next }
hdr { next }
/^\+/ { t = substr($0, 2); s = " " t " "
        if (s ~ pat) print h "\tA\t" np "\t" (iscomment(t) ? "COMMENT" : "CODE") "\t" t
        if (s ~ r89) print h "\tC89\t" np "\t-\t-"
        next }
/^-/ { t = substr($0, 2); if ((" " t " ") ~ pat) print h "\tR\t" (op != "" ? op : np) "\t-\t" t; next }
EOF
)
: > "$OUT/91_meta.tsv"; : > "$OUT/92_msg.tsv"; : > "$OUT/92b_sig.tsv"; : > "$OUT/93_changes.tsv"; : > "$OUT/94_lines.tsv"
if [ -s "$OUT/90_hitset.txt" ]; then
  g log --stdin --no-walk=unsorted --format='%H%x09%P%x09%at%x09%ct%x09%aI%x09%cI' < "$OUT/90_hitset.txt" > "$OUT/91_meta.tsv"; ok meta $? 0
fi
T="$OUT/.t"
while IFS= read -r h <&3; do
  np="$(awk -F'\t' -v h="$h" '$1==h{print split($2, a, " "); exit}' "$OUT/91_meta.tsv")"
  g log -1 --format=%s "$h" < /dev/null > "$T"; ok "subj.$h" $? 0; awk -v h="$h" '{print h "\tS\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g log -1 --format='%(trailers:only,unfold,valueonly)' "$h" < /dev/null > "$T"; ok "trl.$h" $? 0; awk -v h="$h" 'NF{print h "\tT\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g log -1 --format=%b "$h" < /dev/null > "$T"; ok "body.$h" $? 0; awk -v h="$h" '{print h "\tB\t" $0}' "$T" >> "$OUT/92_msg.tsv"
  g cat-file commit "$h" < /dev/null > "$T"; ok "obj.$h" $? 0
  awk -v h="$h" '/^$/{exit} /^gpgsig/{s=1} END{print h "\t" (s ? "SIGNED" : "UNSIGNED")}' "$T" >> "$OUT/92b_sig.tsv"
  if [ "${np:-0}" -ge 2 ]; then
    g show "${DX[@]}" --diff-merges=combined --name-only --format= "$h" < /dev/null > "$T"; ok "own.$h" $? 0
    awk -v h="$h" 'NF{print h "\tO\t-\t" $0}' "$T" >> "$OUT/93_changes.tsv"
    g show "${DX[@]}" --diff-merges=first-parent -U0 --src-prefix=a/ --dst-prefix=b/ --format= "$h" < /dev/null > "$T"; ok "fpdiff.$h" $? 0
  else
    g show "${DX[@]}" --name-status --format= "$h" < /dev/null > "$T"; ok "ns.$h" $? 0
    awk -F'\t' -v h="$h" 'NF{print h "\tN\t" $1 "\t" $2}' "$T" >> "$OUT/93_changes.tsv"
    g show "${DX[@]}" -U0 --src-prefix=a/ --dst-prefix=b/ --format= "$h" < /dev/null > "$T"; ok "diff.$h" $? 0
  fi
  awk -v h="$h" -v pat="$PATX" -v r89="$R89X" "$AWK_LINES" "$T" >> "$OUT/94_lines.tsv"; ok "lines.$h" $? 0
done 3< "$OUT/90_hitset.txt"
rm -f -- "$T"

# ---- S9 classification (B.3), mechanical ----
AWK_CLASS=$(cat <<'EOF'
BEGIN { FS = "\t" }
function isdoc(p,   q, b, w) {
  q = tolower(p); w = "/" q "/"; b = q; sub(/.*\//, "", b)
  if (w ~ /^\/[.]github\/workflows\// || q == ".gitlab-ci.yml" || w ~ /^\/[.]circleci\// || b == "jenkinsfile") return 0
  if (q ~ /[.](md|markdown|txt|rst|adoc|org)$/) return 1
  if (w ~ /\/(docs?|governance|rulings?|decisions?|adrs?|sessions?|logs?|notes|plans?|planning|minutes)\//) return 1
  if (b ~ /^(registers?|rulings?|decisions?|queue|work[-_]?plans?|plans?|backlog|roadmap|status|progress|context|sessions?|minutes|adrs?)([-_.][^\/]*)?[.](ya?ml|json|csv|tsv|toml)$/) return 1
  return 0
}
function wpscan(s,   t) {
  first = ""; other = 0
  while (match(s, wpany)) { t = substr(s, RSTART, RLENGTH); if (first == "") first = t; if (t !~ wpc) other = 1; s = substr(s, RSTART + RLENGTH) }
}
FILENAME == f91 { np[$1] = split($2, a, " "); at[$1] = $3; ct[$1] = $4; ord[++n] = $1; next }
FILENAME == f93 {
  if ($2 == "O") own[$1 SUBSEP $4] = 1
  if (!isdoc($4)) { nd[$1]++; if (($2 == "O" || $3 ~ /^[AMT]/) && (" " $4 " ") ~ pat) pn[$1] = 1 }
  next }
FILENAME == f92 {
  s = " " $3 " "
  if ($2 == "S") subj[$1] = $3
  if (s ~ pat) { if ($2 == "S") S[$1] = 1; else if ($2 == "T") T[$1] = 1; else B[$1] = 1 }
  if (s ~ r89) c89[$1] = 1
  next }
FILENAME == f94 {
  if ($2 == "A") { m = (np[$1] >= 2) ? (($1 SUBSEP $3) in own) : 1
                   if (m && !isdoc($3)) { if ($4 == "COMMENT") cm[$1] = 1; else al[$1] = 1 } }
  else if ($2 == "R") rl[$1] = 1
  else if ($2 == "C89") c89[$1] = 1
  next }
FILENAME == f16 { rx[$1] = 1; next }
FILENAME == f92b { sg[$1] = $2; next }
END {
  for (i = 1; i <= n; i++) {
    h = ord[i]; wpscan(subj[h]); fnt = (first != "" && first !~ wpc)
    k = nd[h] + 0; msg = (S[h] || T[h] || B[h])
    if (k >= 1 && (S[h] || T[h]) && !fnt) { c = "ET-1"; z = "E1" }
    else if (k >= 1 && msg) { c = "ET-2"; z = "E2a" }
    else if (al[h]) { c = "ET-2"; z = "E2b" }
    else if (pn[h]) { c = "ET-2"; z = "E2c" }
    else if (rx[h] && k >= 1) { c = "ET-2"; z = "E2d" }
    else { c = "MENTION"; z = (np[h] >= 2 && msg) ? "M-MERGE" : (cm[h] ? "M-COMMENT" : (rl[h] ? "M-REMOVAL" : (k == 0 ? "M-DOC" : "M-OTHER"))) }
    f = ""
    if (other) f = f ",MIXED"
    if (fnt) f = f ",FIRST_WP_NOT_TARGET"
    if (c89[h]) f = f ",CITES_R89"
    if (cm[h]) f = f ",COMMENT_HIT"
    if (rl[h]) f = f ",REMOVAL_HIT"
    if (rx[h]) f = f ",ON_TARGET_NAMED_REF"
    if (np[h] >= 2) f = f ",MERGE"
    if (sg[h] == "SIGNED") f = f ",SIGNED"
    f = (f == "") ? "-" : substr(f, 2)
    print h "\t" c "\t" z "\t" f "\t" np[h] "\t" k "\t" at[h] "\t" ct[h]
  }
}
EOF
)
awk -v f91="$OUT/91_meta.tsv" -v f92="$OUT/92_msg.tsv" -v f92b="$OUT/92b_sig.tsv" -v f93="$OUT/93_changes.tsv" \
    -v f94="$OUT/94_lines.tsv" -v f16="$OUT/16_ref_exclusive.tsv" -v pat="$PATX" -v r89="$R89X" -v wpany="$WPANY" -v wpc="$WPC" \
    "$AWK_CLASS" "$OUT/91_meta.tsv" "$OUT/93_changes.tsv" "$OUT/92_msg.tsv" "$OUT/94_lines.tsv" "$OUT/16_ref_exclusive.tsv" "$OUT/92b_sig.tsv" \
  | sort > "$OUT/95_classification.tsv"
ps=("${PIPESTATUS[@]}"); ok classify "${ps[0]}" 0; ok classify.sort "${ps[1]}" 0
awk -F'\t' '$2=="ET-1" || $2=="ET-2"{print $1}' "$OUT/95_classification.tsv" | sort > "$OUT/96_et.txt"
awk -F'\t' '$2=="ET-1"{print $1}' "$OUT/95_classification.tsv" | sort > "$OUT/96_et1.txt"

# ---- S10 ancestry, earliest traces, order labels, CLOCK_ANOMALY (B.4) ----
isanc() { g merge-base --is-ancestor "$1" "$2" < /dev/null; local rc=$?; ok "anc.$1.$2" "$rc" 0 1; return "$rc"; }
: > "$OUT/97_et_ancestry.tsv"
while IFS= read -r a <&3; do
  while IFS= read -r b <&4; do
    [ "$a" = "$b" ] && continue
    if isanc "$a" "$b"; then printf '%s\t%s\n' "$a" "$b" >> "$OUT/97_et_ancestry.tsv"; fi
  done 4< "$OUT/96_et.txt"
done 3< "$OUT/96_et.txt"
MINAWK='BEGIN{FS="\t"; while ((getline l < only) > 0) e[l]=1; while ((getline l < anc) > 0) {split(l, p, "\t"); if ((p[1] in e) && (p[2] in e)) d[p[2]]=1}} ($1 in e) && !($1 in d)'
awk -v only="$OUT/96_et.txt"  -v anc="$OUT/97_et_ancestry.tsv" "$MINAWK" "$OUT/96_et.txt"  > "$OUT/97b_et_minimal.txt"
awk -v only="$OUT/96_et1.txt" -v anc="$OUT/97_et_ancestry.tsv" "$MINAWK" "$OUT/96_et1.txt" > "$OUT/97c_et1_minimal.txt"
cat "$OUT/05_control.txt" "$OUT"/40_anchor_R*_msg.txt "$OUT"/41_anchor_R*_diff.txt | awk 'NF' | sort -u > "$OUT/98a_nodes.txt"
cat "$OUT/97b_et_minimal.txt" "$OUT/97c_et1_minimal.txt" | sort -u > "$OUT/98b_min.txt"
cat "$OUT/98a_nodes.txt" "$OUT/96_et.txt" | sort -u > "$OUT/98c.tmp"
: > "$OUT/98c_dates.tsv"
if [ -s "$OUT/98c.tmp" ]; then g log --stdin --no-walk=unsorted --format='%H%x09%at%x09%ct' < "$OUT/98c.tmp" > "$OUT/98c_dates.tsv"; ok dates $? 0; fi
rm -f -- "$OUT/98c.tmp"
: > "$OUT/98d_pairs_raw.tsv"
while IFS= read -r e <&3; do
  while IFS= read -r x <&4; do
    [ "$e" = "$x" ] && continue
    if isanc "$e" "$x"; then r=E_BEFORE_X; elif isanc "$x" "$e"; then r=X_BEFORE_E; else r=NONE; fi
    printf '%s\t%s\t%s\n' "$e" "$x" "$r" >> "$OUT/98d_pairs_raw.tsv"
  done 4< "$OUT/98a_nodes.txt"
done 3< "$OUT/98b_min.txt"
AWK_ORDER=$(cat <<'EOF'
function sgn(v) { return (v > 0) - (v < 0) }
function chk(a, d) { if ((a in ct) && (d in ct) && ct[d] + 0 < ct[a] + 0) { bad[a] = 1; bad[d] = 1; print a "\t" d > fanom } }
BEGIN { FS = "\t"; printf "" > fanom
  while ((getline l < fd) > 0) { split(l, p, "\t"); at[p[1]] = p[2]; ct[p[1]] = p[3] }
  while ((getline l < fa) > 0) { split(l, p, "\t"); chk(p[1], p[2]) }
  while ((getline l < fr) > 0) { split(l, p, "\t"); if (p[3] == "E_BEFORE_X") chk(p[1], p[2]); else if (p[3] == "X_BEFORE_E") chk(p[2], p[1]) }
  close(fr)
  while ((getline l < fr) > 0) { split(l, p, "\t"); e = p[1]; x = p[2]
    if (p[3] != "NONE") lab = "RECORDING_ORDER\t" p[3]
    else if (bad[e] || bad[x] || !(e in ct) || !(x in ct)) lab = "NOT_IDENTIFIABLE\t-"
    else { sa = sgn(at[e] - at[x]); sc = sgn(ct[e] - ct[x])
           lab = (sa == sc && sa != 0) ? ("SOURCE_STATED_ORDER\t" (sa < 0 ? "E_BEFORE_X" : "X_BEFORE_E")) : "NOT_IDENTIFIABLE\t-" }
    print e "\t" x "\t" lab }
}
EOF
)
awk -v fd="$OUT/98c_dates.tsv" -v fa="$OUT/97_et_ancestry.tsv" -v fr="$OUT/98d_pairs_raw.tsv" -v fanom="$OUT/98e_clock_anomalies.tsv" \
  "$AWK_ORDER" < /dev/null | sort > "$OUT/98f_order_labels.tsv"
ps=("${PIPESTATUS[@]}"); ok order "${ps[0]}" 0; ok order.sort "${ps[1]}" 0
sort -u -o "$OUT/98e_clock_anomalies.tsv" "$OUT/98e_clock_anomalies.tsv"
: > "$OUT/99a_refclass.tsv"
while IFS= read -r e <&3; do
  g for-each-ref --contains "$e" --format='%(refname)' < /dev/null > "$OUT/.c"; ok "contains.$e" $? 0
  awk -v e="$e" '{if ($0 ~ /^refs\/heads\//) h++; else if ($0 ~ /^refs\/remotes\//) r++; else if ($0 ~ /^refs\/tags\//) t++; else o++}
    END{printf "%s\theads=%d\tremotes=%d\ttags=%d\tother=%d\n", e, h, r, t, o}' "$OUT/.c" >> "$OUT/99a_refclass.tsv"
done 3< "$OUT/98b_min.txt"
rm -f -- "$OUT/.c"

# ---- S11 status (B.9) and canonical key ----
nC=$(cnt "$OUT/05_control.txt"); nH=$(cnt "$OUT/90_hitset.txt"); nE=$(cnt "$OUT/96_et.txt"); nE1=$(cnt "$OUT/96_et1.txt")
nM=$(cnt "$OUT/97b_et_minimal.txt"); nM1=$(cnt "$OUT/97c_et1_minimal.txt"); nV=$(cnt "$OUT/29_variant_only_commits.txt")
code=0
if [ -s "$ERR" ]; then ex=CAPTURE_ERROR; code=1
elif [ "$nC" -eq 0 ]; then ex=CAPTURE_INVALID
elif [ "$nE" -gt 0 ]; then ex=EXECUTION_TRACE_FOUND
elif [ "$nH" -gt 0 ]; then ex=MENTION_ONLY
elif [ "$nV" -gt 0 ]; then ex=VARIANT_ONLY
else ex=NOT-EVIDENCED-IN-CAPTURE; fi
weak=no; if [ "$nM" -gt 0 ] && [ "$nM1" -eq 0 ]; then weak=WEAK_TRACE; fi
printf 'existence\t%s\ncapture\tLOCAL_REF_SET\ncompleteness\tNOT_SHOWN\ncontrol_commits\t%s\nhit_commits\t%s\net_total\t%s\net1\t%s\net_minimal\t%s\net1_minimal\t%s\nweak_trace\t%s\nvariant_only_commits\t%s\nclock_anomaly_pairs\t%s\n' \
  "$ex" "$nC" "$nH" "$nE" "$nE1" "$nM" "$nM1" "$weak" "$nV" "$(cnt "$OUT/98e_clock_anomalies.tsv")" > "$OUT/80_status.tsv"
cat "$OUT/01_refs.tsv" "$OUT/02_commits.txt" "$OUT/05_control.txt" "$OUT/10_msg_pat.txt" "$OUT/11_diff_pat_nonmerge.txt" \
    "$OUT/12_diff_pat_merge.txt" "$OUT/14_path_pat.tsv" "$OUT/15_refname_hits.tsv" "$OUT/16_ref_exclusive.tsv" "$OUT/29_variant_only_commits.txt" \
    "$OUT"/40_anchor_R*_msg.txt "$OUT"/41_anchor_R*_diff.txt "$OUT/90_hitset.txt" "$OUT/95_classification.tsv" "$OUT/97_et_ancestry.tsv" \
    "$OUT/97b_et_minimal.txt" "$OUT/97c_et1_minimal.txt" "$OUT/98e_clock_anomalies.tsv" "$OUT/98f_order_labels.tsv" "$OUT/80_status.tsv" \
  | sha256sum | cut -d' ' -f1 > "$OUT/99_canonical_key.txt"
finish "$code"
```

**Output files:**
- `00_*`: environment, script hash, errors and stderr.
- `01*`: the ref set and tips.
- `02`: the commit set.
- `05`: the control.
- `10`–`16`: primary passes.
- `20`–`29`: variants.
- `40`–`41`: anchors.
- `90`–`95`: the hit set, features and classification.
- `96`–`99`: traces, ancestry, order, anomalies, ref classes, status, the canonical key, and `SHA256SUMS`.

## B.8 Worker and reviewer procedure (r2)

**Freeze.**
- The reviewer extracts `rank0-r2.sh` verbatim from B.7, computes its SHA-256, and records it in `FREEZE.txt` before any run.
- No rule may change afterwards. A change means protocol r3.

**Worker.**
1. **W1.** Check the script hash against `FREEZE.txt`, and stop on a mismatch.
2. **W2.** Record `git for-each-ref | sha256sum` in the worker log. Make no fetch, commit, branch or tag change from here until R2.
3. **W3.** Run the script into `OUT_A`, then immediately into `OUT_B`. Require `diff -r OUT_A OUT_B` to be empty. If it is not, the result is NONDETERMINISM: report it and stop.
4. **W4.** Read the exit code:
   - 2: fix the precondition (OUT) only, and rerun. No other change is allowed.
   - 3: the result is CAPTURE_INVALID.
   - 1: the result is CAPTURE_ERROR (keep `00_errors.tsv`).
   - 0: read `80_status.tsv`.
5. **W5.** Check `01d_ref_counts.tsv` and `02_commit_count.txt` against ENVIRONMENT.md (7, 16, 1, 4002). A mismatch is recorded as REF_DRIFT_VS_ENVIRONMENT. It is not fatal. The capture is what was searched.
6. **W6.** Write `RANK0-REPORT.md`:
   - the labels from `80_status.tsv`;
   - the minimal ETs, with SOURCE_STATED dates and `99a` ref classes;
   - the order labels from `98f`;
   - the SHA-256 of `SHA256SUMS`, and the canonical key.

   It contains no statement about the hypotheses.

**Reviewer** (independent: no shared session, and no sight of `95` before R3).
1. **R1.** Verify the script hash and `SHA256SUMS`.
2. **R2.** On the same ref set (compare with the W2 hash), rerun into `OUT_R`. Require byte-identity with `OUT_A`. If the refs have changed, the result is NOT_REPRODUCED(REF_DRIFT) and the worker's result stands, flagged.
3. **R3.** Blind-classify from `92`, `93` and `94` by the B.3 text:
   - all hits if there are ≤ 60;
   - otherwise the first 60 in hash order, plus every ET.

   Compare with `95`. A disagreement where the script misapplies the B.3 text is CLASSIFIER_DEFECT: it is DISPUTED and goes to the human (protocol r3). There is no hand correction.
4. **R4.** Check four things:
   - the control is non-empty;
   - variants are not counted;
   - the absence wording is exactly NOT-EVIDENCED-IN-CAPTURE;
   - no git time is presented as a decision time or as entry into force.
5. **R5.** Give a verdict: ACCEPT, ACCEPT_WITH_CORRECTIONS (only to the report text), or REJECT.

## B.9 Output labels

- **Existence:** EXECUTION_TRACE_FOUND, MENTION_ONLY, VARIANT_ONLY, NOT-EVIDENCED-IN-CAPTURE, CAPTURE_INVALID or CAPTURE_ERROR. Each carries `capture = LOCAL_REF_SET` and `completeness = NOT_SHOWN`.
- **Qualifiers:** weak_trace, variant_only_commits and clock_anomaly_pairs.
- **Order:** from `98f`, plus INFERRED_ORDER only with a written chain. Order relative to decision or force is always NOT_IDENTIFIABLE.

---

# PART C. Scoping protocol r2: an independent norm-status channel (existence only)

## C.1 Rules of restraint

- Only the channels in C.2 are checked, and only the script in C.3 is run.
- **No host access.** Host channels are listed as NOT_DETERMINABLE_BY_EXISTENCE_CHECK(HOST_NOT_AUTHORIZED).
- **No content is read, except:**
  - git object headers, for signature presence;
  - CODEOWNERS files, which are reduced in memory to counts of rules and owner-token types.
- **No personal identities are output:**
  - no author or committer names or emails, user names, CODEOWNERS owners, ref names, remote URLs or config paths;
  - no path strings. Paths appear as 16-hex SHA-256 ids, with class and trigger token from a fixed vocabulary.
  - Identities are compared only in a private temporary directory outside the repository and outside OUT, which is deleted on exit. Only counts are written.
  - The identity guard makes the run exit 1 if any output file contains `@`.
- **History, not only the current tree:** every path changed in any commit reachable from the capture is covered (SC-3).
- **The environment rules are the same as Part B:** read-only, deterministic, no `set -e`, and the same capture.
- **Stop rule:** when the script ends, stop. Anything else that is noticed is recorded as CANDIDATE_NOT_CHECKED, for a human decision.

## C.2 Channels

| ID | Channel | How it is located (history) |
|---|---|---|
| K-REG | Register / rulings / decisions | whole-token {register(s), ruling(s), decision(s)} on doc or data extensions |
| K-SESS | Session logs | token {session(s)} |
| K-PROG | Progress files | token {progress} |
| K-STATUS | Candidate status / audit files | tokens {status, audit(s), ledger(s), adopt(ed), adoption(s), approval(s), signoff(s), queue, workplan(s), context, norm(s), inforce}, including the bigrams in+force, sign+off and work+plan |
| K-CFG | CODEOWNERS | basename `codeowners` |
| K-CI | CI definitions | `.github/workflows/`, `.gitlab-ci.yml`, `Jenkinsfile`, `.circleci/`, `azure-pipelines.yml`, `.drone.yml`, `bitbucket-pipelines.yml`, `.buildkite/`, `.woodpecker(.yml|/)` |
| K-HOOK | Tracked hooks | `.githooks/`, `.husky/`, `.pre-commit-config.yaml`, `(.)lefthook.yml` |
| K-SIG | Signed commits and tags | object headers |
| K-NOTES | git notes | `refs/notes/*` count |
| K-LOCALHOOK | `.git/hooks`, `core.hooksPath` | LOCAL_ONLY |
| K-HOST-CFG, K-HOST-PR, K-HOST-AUDIT, K-HOST-MEMBERS, K-CI-RUNS | Host channels | **not queried** |

Token classes require a document or data extension (md, markdown, txt, rst, adoc, org, json, yaml, yml, csv, tsv, toml, log, xml, html) or no extension (SC-7).

## C.3 The exact script: `scope-r2.sh`

```bash
#!/usr/bin/env bash
# scope-r2.sh -- frozen by SPEC-r2 Part C. Existence and metadata only. Do not edit.
# Capture = the same local ref set as rank0-r2.sh. Read-only. No network. No host access. No `set -e`.
# Writes NO personal identities: no names, e-mails, user names, ref names, URLs, CODEOWNERS owners or path strings.
# Paths appear only as 16-hex SHA-256 ids; identities are compared inside a private temp dir; only counts are written.
# Usage (from anywhere inside the working clone):  OUT=/absolute/path/to/new/empty/dir bash scope-r2.sh
# Exit: 0 completed | 1 completed with unexpected exit codes or identity-guard failure | 2 refused to start | 3 capture invalid
set -u -o pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_CONFIG_GLOBAL=/dev/null \
  GIT_NO_REPLACE_OBJECTS=1 GIT_OPTIONAL_LOCKS=0 GIT_NO_LAZY_FETCH=1 GIT_TERMINAL_PROMPT=0
unset GIT_DIR GIT_WORK_TREE GIT_INDEX_FILE GIT_OBJECT_DIRECTORY GIT_ALTERNATE_OBJECT_DIRECTORIES GIT_NAMESPACE GIT_CEILING_DIRECTORIES
TAB=$'\t'
die() { printf 'scope-r2: %s\n' "$2" >&2; exit "$1"; }

# ---- P0 preconditions (exit 2) ----
OUT="${OUT:-}"; [ -n "$OUT" ] || die 2 "OUT is not set"
case "$OUT" in /*) ;; *) die 2 "OUT must be an absolute path" ;; esac
[ "$(git rev-parse --is-inside-work-tree 2>/dev/null)" = true ] || die 2 "not inside a work tree"
[ "$(git rev-parse --is-bare-repository 2>/dev/null)" = false ] || die 2 "bare repository"
TOP="$(git rev-parse --show-toplevel 2>/dev/null)" || die 2 "no toplevel"
GD="$(git rev-parse --absolute-git-dir 2>/dev/null)" || die 2 "no git dir"
OUT="$(realpath -m -- "$OUT")" || die 2 "realpath failed"
TOPR="$(realpath -- "$TOP")"; GDR="$(realpath -- "$GD")"
for d in "$TOPR" "$GDR"; do case "$OUT/" in "$d"/*) die 2 "OUT must be outside the repository" ;; esac; done
if [ -e "$OUT" ]; then { [ -d "$OUT" ] && [ -z "$(ls -A -- "$OUT")" ]; } || die 2 "OUT exists and is not an empty directory"; fi
TMP="$(mktemp -d)" || die 2 "mktemp failed"; TMP="$(realpath -- "$TMP")"
case "$TMP/" in "$TOPR"/*|"$GDR"/*|"$OUT"/*) rm -rf -- "$TMP"; die 2 "TMPDIR must be outside the repository and OUT" ;; esac
trap 'rm -rf -- "$TMP"' EXIT
mkdir -p -- "$OUT" || die 2 "cannot create OUT"
cd -- "$TOP" || die 2 "cannot cd to the toplevel"

ERR="$OUT/00_errors.tsv"; : > "$ERR"; : > "$TMP/stderr"
ok() { local l="$1" rc="$2" a; shift 2; for a in "$@"; do [ "$rc" -eq "$a" ] && return 0; done; printf '%s\t%s\n' "$l" "$rc" >> "$ERR"; return 1; }
g() { git --no-replace-objects -c core.quotepath=off -c core.fsmonitor=false -c log.showSignature=false \
  -c log.mailmap=false -c log.decorate=false -c log.showRoot=true -c diff.renames=false -c color.ui=never "$@" 2>>"$TMP/stderr"; }
cnt() { awk 'END{print NR}' "$1"; }
iso() { if [ "$1" = - ]; then echo -; else date -u -d "@$1" +%Y-%m-%dT%H:%M:%SZ; fi; }
finish() {
  cnt "$TMP/stderr" > "$OUT/00_stderr_line_count.txt"
  ( cd -- "$OUT" && find . -type f ! -name SHA256SUMS -print | sort | xargs -r -d '\n' sha256sum ) > "$OUT/SHA256SUMS"
  sha256sum < "$OUT/SHA256SUMS" | cut -d' ' -f1
  exit "$1"
}
SS="$(sha256sum < "${BASH_SOURCE[0]}")"; ok script.sha256 $? 0; printf '%s\n' "${SS%% *}" > "$OUT/00_script.sha256"
SPAN_LO_ISO=2026-08-02T00:00:00Z
SPAN_LO="$(date -u -d "$SPAN_LO_ISO" +%s)"; SPAN_HI="$(date -u -d 2026-08-16T00:00:00Z +%s)"

# ---- S0 environment and validity (same gate as rank-0) ----
SH="$(g rev-parse --is-shallow-repository)"; ok env.shallow $? 0
PC="$(g config --get extensions.partialClone)"; ok cfg.partialClone $? 0 1
PR="$(g config --get-regexp '^remote\..*\.(promisor|partialclonefilter)$')"; ok cfg.promisor $? 0 1
NPR="$(printf '%s' "$PR" | awk 'NF{c++} END{print c+0}')"
printf 'git_version\t%s\nis_shallow\t%s\npartial_clone\t%s\npromisor_or_filter_entries\t%s\n' "$(git --version)" "$SH" \
  "$([ -n "$PC" ] && echo set || echo unset)" "$NPR" > "$OUT/00_env.tsv"
if [ "$SH" != false ] || [ -n "$PC" ] || [ "$NPR" != 0 ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi

# ---- S1 capture (ref names stay in TMP; only a hash and counts are written) ----
g for-each-ref --format='%(objectname)%09%(objecttype)%09%(*objectname)%09%(*objecttype)%09%(refname)' > "$TMP/refs.raw"; ok refs $? 0
sort -t "$TAB" -k5,5 "$TMP/refs.raw" > "$TMP/refs.tsv"; ok refs.sort $? 0
RS="$(sha256sum < "$TMP/refs.tsv")"; ok refs.hash $? 0; printf '%s\n' "${RS%% *}" > "$OUT/50_capture_refs.sha256"   # equals SHA-256 of rank-0 01_refs.tsv
awk -F'\t' '$2=="commit"{print $1; next} $2=="tag" && $4=="commit"{print $3}' "$TMP/refs.tsv" | sort -u > "$TMP/tips"
awk -F'\t' '{c = ($5 ~ /^refs\/heads\//) ? "heads" : (($5 ~ /^refs\/remotes\//) ? "remotes" : (($5 ~ /^refs\/tags\//) ? "tags" : (($5 ~ /^refs\/notes\//) ? "notes" : "other"))); n[c]++}
  END{printf "heads\t%d\nremotes\t%d\ntags\t%d\nnotes\t%d\nother\t%d\n", n["heads"], n["remotes"], n["tags"], n["notes"], n["other"]}' "$TMP/refs.tsv" > "$OUT/50b_ref_counts.tsv"
if [ ! -s "$TMP/tips" ]; then printf 'overall\tCAPTURE_INVALID\n' > "$OUT/71_overall.tsv"; finish 3; fi

# ---- S2 path universe over history (SC-3) ----
g log --stdin -m --no-renames --name-only --format= < "$TMP/tips" > "$TMP/pa.raw"; ok paths.history $? 0
awk 'NF' "$TMP/pa.raw" | sort -u > "$TMP/paths_all"
: > "$TMP/pt.raw"
while IFS= read -r t <&3; do g ls-tree -r --name-only --full-tree "$t" < /dev/null >> "$TMP/pt.raw"; ok "lstree.$t" $? 0; done 3< "$TMP/tips"
sort -u "$TMP/pt.raw" > "$TMP/paths_tips"
NA=$(cnt "$TMP/paths_all"); NT=$(comm -12 "$TMP/paths_all" "$TMP/paths_tips" | awk 'END{print NR}')
printf 'paths_ever\t%s\npaths_ever_and_at_some_tip\t%s\npaths_ever_not_at_any_tip\t%s\npaths_ever_under_dot_github\t%s\n' \
  "$NA" "$NT" "$((NA - NT))" "$(awk '/^[.]github\//' "$TMP/paths_all" | awk 'END{print NR}')" > "$OUT/51_path_universe.tsv"

# ---- S3 candidate channels (fixed vocabulary; whole tokens; SC-7) ----
AWK_CH=$(cat <<'EOF'
BEGIN { FS = "\t"
  split("register registers ruling rulings decision decisions", a, " "); for (i in a) REG[a[i]] = 1
  split("session sessions", a, " "); for (i in a) SESS[a[i]] = 1
  PROG["progress"] = 1
  split("status audit audits ledger ledgers adopt adopted adoption adoptions approval approvals signoff signoffs queue workplan workplans context norm norms inforce", a, " "); for (i in a) STAT[a[i]] = 1
  split("md markdown txt rst adoc org json yaml yml csv tsv toml log xml html", a, " "); for (i in a) DOCX[a[i]] = 1 }
{ p = $0; q = tolower(p); w = "/" q; b = q; sub(/.*\//, "", b)
  e = ""; if (b ~ /[.][^.]+$/) { e = b; sub(/.*[.]/, "", e) }
  if (w ~ /^\/[.]github\/workflows\// || q == ".gitlab-ci.yml" || b == "jenkinsfile" || w ~ /^\/[.]circleci\// || q == "azure-pipelines.yml" || q == ".drone.yml" || q == "bitbucket-pipelines.yml" || w ~ /^\/[.]buildkite\// || q == ".woodpecker.yml" || w ~ /^\/[.]woodpecker\//) print "K-CI\t" p "\tci-path"
  if (w ~ /^\/[.]githooks\// || w ~ /^\/[.]husky\// || q == ".pre-commit-config.yaml" || b == "lefthook.yml" || b == ".lefthook.yml") print "K-HOOK\t" p "\thook-path"
  if (b == "codeowners") print "K-CFG\t" p "\tcodeowners"
  if (e != "" && !(e in DOCX)) next
  n = split(q, t, /[^a-z0-9]+/); r = ""; s = ""; gg = ""; x = ""
  for (i = 1; i <= n; i++) { k = t[i]; if (k == "") continue
    if (i < n && ((k == "in" && t[i+1] == "force") || (k == "sign" && t[i+1] == "off") || (k == "work" && t[i+1] == "plan"))) k = k t[i+1]
    if (r == "" && (k in REG)) r = k
    if (s == "" && (k in SESS)) s = k
    if (gg == "" && (k in PROG)) gg = k
    if (x == "" && (k in STAT)) x = k }
  if (r != "") print "K-REG\t" p "\t" r
  if (s != "") print "K-SESS\t" p "\t" s
  if (gg != "") print "K-PROG\t" p "\t" gg
  if (x != "") print "K-STATUS\t" p "\t" x }
EOF
)
awk "$AWK_CH" "$TMP/paths_all" | sort -u > "$TMP/cand.tsv"; ps=("${PIPESTATUS[@]}"); ok cand "${ps[0]}" 0

# ---- S4 per-path history metrics (ids only) ----
declare -A SIG
sigof() { if [ -z "${SIG[$1]:-}" ]; then g cat-file commit "$1" < /dev/null > "$TMP/obj"; ok "obj.$1" $? 0
  SIG[$1]="$(awk '/^$/{exit} /^gpgsig/{s=1} END{print (s ? "SIGNED" : "UNSIGNED")}' "$TMP/obj")"; fi; SIGR="${SIG[$1]}"; }
mkdir -p "$TMP/w" "$TMP/hc"; : > "$TMP/pm.tsv"; : > "$TMP/pid.tsv"
cut -f2 "$TMP/cand.tsv" | sort -u > "$TMP/cpaths"
while IFS= read -r p <&3; do
  id="$(printf '%s' "$p" | sha256sum | cut -c1-16)"
  g log --stdin --no-merges --full-history --no-renames --name-status --format='::c::%x09%H%x09%ct%x09%ae%x09%ce' -- ":(literal)$p" < "$TMP/tips" > "$TMP/h"; ok "hist.$id" $? 0
  awk -F'\t' '$1=="::c::"{print $2 "\t" $3}' "$TMP/h" > "$TMP/hc/$id"
  awk -F'\t' '$1=="::c::"{print tolower($4); print tolower($5)}' "$TMP/h" | awk 'NF' | sort -u > "$TMP/w/$id"
  nc=$(cnt "$TMP/hc/$id")
  lo=$(awk -F'\t' 'NR==1{m=$2} $2<m{m=$2} END{print (NR ? m : "-")}' "$TMP/hc/$id")
  hi=$(awk -F'\t' 'NR==1{m=$2} $2>m{m=$2} END{print (NR ? m : "-")}' "$TMP/hc/$id")
  sp=$(awk -F'\t' -v a="$SPAN_LO" -v b="$SPAN_HI" '$2>=a && $2<b{c++} END{print c+0}' "$TMP/hc/$id")
  dl=$(awk -F'\t' '$1 ~ /^D/{c++} END{print (c ? 1 : 0)}' "$TMP/h")
  grep -Fxq -- "$p" "$TMP/paths_tips"; rc=$?; ok "attip.$id" "$rc" 0 1; tp=$((rc == 0 ? 1 : 0))
  ns=0; while IFS="$TAB" read -r c _ <&4; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then ns=$((ns + 1)); fi; done 4< "$TMP/hc/$id"
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$id" "$nc" "$lo" "$hi" "$sp" "$dl" "$tp" "$ns" "$(cnt "$TMP/w/$id")" >> "$TMP/pm.tsv"
  printf '%s\t%s\n' "$p" "$id" >> "$TMP/pid.tsv"
done 3< "$TMP/cpaths"

# ---- S5 writer sets (ACTOR_SET_ID; kept in TMP; only counts leave) ----
EXC=(':(exclude,glob,icase)**/*.md' ':(exclude,glob,icase)**/*.markdown' ':(exclude,glob,icase)**/*.txt' ':(exclude,glob,icase)**/*.rst'
     ':(exclude,glob,icase)**/*.adoc' ':(exclude,glob,icase)**/*.org')
for d in doc docs governance ruling rulings decision decisions adr adrs session sessions log logs notes plan plans planning minutes; do
  EXC+=(":(exclude,glob,icase)**/$d/**"); done
g log --stdin --no-merges --full-history --format='%ae%n%ce' -- . "${EXC[@]}" < "$TMP/tips" > "$TMP/we.raw"; ok writers.exec $? 0
tr 'A-Z' 'a-z' < "$TMP/we.raw" | awk 'NF' | sort -u > "$TMP/w_exec"; ps=("${PIPESTATUS[@]}"); ok we.tr "${ps[0]}" 0
awk -F'\t' '$1=="K-REG"{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp_reg"
: > "$TMP/w_reg.raw"
while IFS= read -r p <&3; do id="$(awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv")"; cat "$TMP/w/$id" >> "$TMP/w_reg.raw"; done 3< "$TMP/cp_reg"
sort -u "$TMP/w_reg.raw" > "$TMP/w_reg"

# ---- S6 per-path and per-channel metrics (no identities, no path strings) ----
: > "$OUT/61_path_metrics.tsv"
while IFS="$TAB" read -r id nc lo hi sp dl tp ns nw <&3; do
  oe=$(comm -12 "$TMP/w/$id" "$TMP/w_exec" | awk 'END{print NR}'); orr=$(comm -12 "$TMP/w/$id" "$TMP/w_reg" | awk 'END{print NR}')
  cls=$(awk -F'\t' -v i="$id" 'FILENAME==ARGV[1]{if ($2==i) P[$1]=1; next} ($2 in P){printf "%s%s:%s", (n++ ? "," : ""), $1, $3} END{print ""}' "$TMP/pid.tsv" "$TMP/cand.tsv")
  ext=$(awk -F'\t' -v i="$id" '$2==i{b=$1; sub(/.*\//, "", b); e="none"; if (b ~ /[.][A-Za-z0-9]{1,8}$/) {e=tolower(b); sub(/.*[.]/, "", e)}; print e; exit}' "$TMP/pid.tsv")
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$id" "$cls" "$ext" "$nc" "$(iso "$lo")" "$(iso "$hi")" "$sp" "$dl" "$tp" "$ns" "$nw" "$oe" "$orr" >> "$OUT/61_path_metrics.tsv"
done 3< "$TMP/pm.tsv"
sort -o "$OUT/61_path_metrics.tsv" "$OUT/61_path_metrics.tsv"
: > "$OUT/60_channel_metrics.tsv"
for ch in K-REG K-SESS K-PROG K-STATUS K-CFG K-CI K-HOOK; do
  awk -F'\t' -v c="$ch" '$1==c{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp"
  : > "$TMP/cids"; while IFS= read -r p <&3; do awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv" >> "$TMP/cids"; done 3< "$TMP/cp"
  np=$(cnt "$TMP/cids"); : > "$TMP/cc"; : > "$TMP/cw"; at=0; de=0
  while IFS= read -r id <&3; do cat "$TMP/hc/$id" >> "$TMP/cc"; cat "$TMP/w/$id" >> "$TMP/cw"
    at=$((at + $(awk -F'\t' -v i="$id" '$1==i{print $7}' "$TMP/pm.tsv"))); de=$((de + $(awk -F'\t' -v i="$id" '$1==i{print $6}' "$TMP/pm.tsv"))); done 3< "$TMP/cids"
  sort -u "$TMP/cc" > "$TMP/ccu"; sort -u "$TMP/cw" > "$TMP/cwu"
  ncm=$(cnt "$TMP/ccu")
  lo=$(awk -F'\t' 'NR==1{m=$2} $2<m{m=$2} END{print (NR ? m : "-")}' "$TMP/ccu"); hi=$(awk -F'\t' 'NR==1{m=$2} $2>m{m=$2} END{print (NR ? m : "-")}' "$TMP/ccu")
  sp=$(awk -F'\t' -v a="$SPAN_LO" -v b="$SPAN_HI" '$2>=a && $2<b{c++} END{print c+0}' "$TMP/ccu")
  sg=0; while IFS="$TAB" read -r c _ <&3; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then sg=$((sg + 1)); fi; done 3< "$TMP/ccu"
  oe=$(comm -12 "$TMP/cwu" "$TMP/w_exec" | awk 'END{print NR}'); orr=$(comm -12 "$TMP/cwu" "$TMP/w_reg" | awk 'END{print NR}')
  printf '%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n' "$ch" "$np" "$at" "$de" "$ncm" "$(iso "$lo")" "$(iso "$hi")" "$sp" "$sg" "$(cnt "$TMP/cwu")" "$oe" "$orr" >> "$OUT/60_channel_metrics.tsv"
done

# ---- S7 CODEOWNERS: counts of rules and owner-token types only ----
: > "$OUT/62_codeowners_counts.tsv"
awk -F'\t' '$1=="K-CFG"{print $2}' "$TMP/cand.tsv" | sort -u > "$TMP/cp_cfg"
while IFS= read -r p <&3; do
  id="$(awk -F'\t' -v p="$p" '$1==p{print $2; exit}' "$TMP/pid.tsv")"; H="$(awk -F'\t' 'NR==1{print $1}' "$TMP/hc/$id")"
  if [ -z "$H" ]; then printf '%s\tno_nonmerge_commit\t0\t0\t0\t0\t0\n' "$id" >> "$OUT/62_codeowners_counts.tsv"; continue; fi
  g cat-file -e "$H:$p" < /dev/null; rc=$?; ok "coexists.$id" "$rc" 0 1 128
  if [ "$rc" -eq 0 ]; then
    g cat-file blob "$H:$p" < /dev/null > "$TMP/co"; ok "coblob.$id" $? 0
    awk -v pid="$id" '/^[ \t]*#/ || NF == 0 {next}
      {r++; for (j = 2; j <= NF; j++) {o = $j; if (o ~ /^@[^\/]+\/./) tm++; else if (o ~ /^@/) us++; else if (o ~ /@/) em++; else ot++}}
      END{printf "%s\tpresent_at_latest_touch\t%d\t%d\t%d\t%d\t%d\n", pid, r, tm, us, em, ot}' "$TMP/co" >> "$OUT/62_codeowners_counts.tsv"
  else printf '%s\tdeleted_at_latest_touch\t0\t0\t0\t0\t0\n' "$id" >> "$OUT/62_codeowners_counts.tsv"; fi
done 3< "$TMP/cp_cfg"

# ---- S8 local hooks (LOCAL_ONLY; standard names only) ----
HOOKS="applypatch-msg commit-msg fsmonitor-watchman p4-changelist p4-post-changelist p4-pre-submit p4-prepare-changelist post-applypatch post-checkout post-commit post-index-change post-merge post-receive post-rewrite post-update pre-applypatch pre-auto-gc pre-commit pre-merge-commit pre-push pre-rebase pre-receive prepare-commit-msg push-to-checkout reference-transaction sendemail-validate update"
{ g config --get core.hooksPath > /dev/null; rc=$?; ok cfg.hooksPath "$rc" 0 1; printf 'core.hooksPath\t%s\n' "$([ "$rc" -eq 0 ] && echo set || echo unset)"
  nh=0; for hk in $HOOKS; do if [ -e "$GD/hooks/$hk" ]; then printf 'hook\t%s\tpresent\n' "$hk"; nh=$((nh + 1)); fi; done
  ls -1A -- "$GD/hooks" > "$TMP/hk" 2>/dev/null; rc=$?; ok ls.hooks "$rc" 0 2
  printf 'standard_hooks_present\t%s\n' "$nh"
  printf 'other_non_sample_entries\t%s\n' "$(awk -v known="$HOOKS" 'BEGIN{n=split(known, a, " "); for (i = 1; i <= n; i++) k[a[i]] = 1} !($0 in k) && $0 !~ /[.]sample$/ {c++} END{print c+0}' "$TMP/hk")"
} > "$OUT/56_local_hooks_LOCAL_ONLY.tsv"
NHK=$(awk -F'\t' '$1=="standard_hooks_present"{print $2}' "$OUT/56_local_hooks_LOCAL_ONLY.tsv"); HKP=$(awk -F'\t' '$1=="core.hooksPath"{print $2}' "$OUT/56_local_hooks_LOCAL_ONLY.tsv")

# ---- S9 signatures, tags, notes (presence only; keyring not consulted) ----
g rev-list --stdin < "$TMP/tips" > "$TMP/allc"; ok revlist $? 0
nS=0; nU=0; while IFS= read -r c <&3; do sigof "$c"; if [ "$SIGR" = SIGNED ]; then nS=$((nS + 1)); else nU=$((nU + 1)); fi; done 3< "$TMP/allc"
nTA=0; nTS=0
while IFS="$TAB" read -r o ty _ _ rn <&3; do
  case "$rn" in refs/tags/*) ;; *) continue ;; esac
  [ "$ty" = tag ] || continue
  nTA=$((nTA + 1)); g cat-file tag "$o" < /dev/null > "$TMP/tg"; ok "tag.$o" $? 0
  grep -qE '^-----BEGIN (PGP|SSH) SIGNATURE-----$' "$TMP/tg"; rc=$?; ok "tagsig.$o" "$rc" 0 1; [ "$rc" -eq 0 ] && nTS=$((nTS + 1))
done 3< "$TMP/refs.tsv"
nTL=$(awk -F'\t' '$5 ~ /^refs\/tags\// && $2=="commit"' "$TMP/refs.tsv" | awk 'END{print NR}')
nN=$(awk -F'\t' '$5 ~ /^refs\/notes\//' "$TMP/refs.tsv" | awk 'END{print NR}')
printf 'commits_signed\t%s\ncommits_unsigned\t%s\ntags_annotated\t%s\ntags_annotated_signed\t%s\ntags_lightweight\t%s\nnotes_refs\t%s\n' \
  "$nS" "$nU" "$nTA" "$nTS" "$nTL" "$nN" > "$OUT/58_signature_counts.tsv"

# ---- S10 answers Q1-Q8 (frozen mapping, C.5-r2) ----
AWK_ANS=$(cat <<'EOF'
BEGIN { FS = "\t"; OFS = "\t"; ND = "NOT_DETERMINABLE_BY_EXISTENCE_CHECK"; NA = "NOT_APPLICABLE(Q1)" }
{ ch = $1; np = $2 + 0; lo = $6; sp = $8 + 0; oe = $11 + 0; orr = $12 + 0
  cfg = (ch == "K-CFG" || ch == "K-CI" || ch == "K-HOOK")
  if (np == 0) { print ch, "NOT_EVIDENCED_IN_CAPTURE", NA, NA, NA, NA, NA, NA, NA; next }
  q2 = (ch == "K-REG") ? "NORM_TEXT,ADOPTION_EVENT(by channel type; content not read)" : (cfg ? "CONFIG_RULE(by channel type)" : ND "(content not read)")
  if (ch == "K-REG") q3 = "NORM_MAKER(by construction)"
  else if (oe && orr) q3 = "MIXED[ACTOR_SET_ID]"
  else if (oe) q3 = "INCLUDES_EXECUTOR_WRITERS[ACTOR_SET_ID]"
  else if (orr) q3 = "INCLUDES_REGISTER_WRITERS[ACTOR_SET_ID]"
  else q3 = ND
  q4 = "CLIENT_SET(git recording times);content_timestamps=" ND
  if (cfg) q5 = "NO_BY_TYPE(configuration rule, not a norm-state record)"
  else if (ch == "K-REG") q5 = "NO_AS_NIF_A(norm-maker channel)"
  else if (oe || orr) q5 = "NO_AS_NIF_A[ACTOR_SET_ID](writers overlap register or executor writers)"
  else q5 = ND
  k2 = (lo != "-" && lo < span_lo) ? "INDICATED[SOURCE_STATED]" : "NOT_INDICATED"
  k3 = cfg ? (sp ? "CHANGED_IN_CAPTURE_DURING_CORPUS_SPAN" : "NO_CHANGE_IN_CAPTURE(not shown stable)") : "NOT_APPLICABLE"
  q6 = ND "[K1=" ND ";K2=" k2 ";K3=" k3 ";K4=" ND ";COMPLETE_SHOWN=NOT_REACHABLE_BY_THIS_PROTOCOL]"
  i1 = (ch == "K-REG") ? "FAILS(norm-maker)" : ((oe || orr) ? "FAILS[ACTOR_SET_ID]" : ND)
  q7 = "NOT_INDEPENDENT_AS_GIT_RECORD(I3 fails: client-set recording times);I1=" i1 ";I2=" ND ";I4=" ND ";content_level=" ND
  q8 = "YES_LOCAL(content-addressed; rerunnable on the same ref set)"
  print ch, "YES_IN_CAPTURE", q2, q3, q4, q5, q6, q7, q8 }
EOF
)
awk -v span_lo="$SPAN_LO_ISO" "$AWK_ANS" "$OUT/60_channel_metrics.tsv" > "$OUT/70_answers.tsv"; ok answers $? 0
ND=NOT_DETERMINABLE_BY_EXISTENCE_CHECK
{ if [ "$nS" -gt 0 ] || [ "$nTS" -gt 0 ]; then
    printf 'K-SIG\tYES_IN_CAPTURE\tIDENTITY_ATTESTATION\t%s(keyring not consulted)\tCLIENT_SET(signer clock)\tNO_BY_TYPE(identity attestation, not a norm-state record)\t%s\t%s(signatures not verified)\tYES_LOCAL(presence only)\n' "$ND" "$ND" "$ND"
  else printf 'K-SIG\tNOT_EVIDENCED_IN_CAPTURE\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  if [ "$nN" -gt 0 ]; then
    printf 'K-NOTES\tYES_IN_CAPTURE\t%s(content not read)\t%s\tCLIENT_SET(git recording times)\t%s\t%s\tNOT_INDEPENDENT_AS_GIT_RECORD(I3 fails);content_level=%s\tYES_LOCAL\n' "$ND" "$ND" "$ND" "$ND" "$ND"
  else printf 'K-NOTES\tNOT_EVIDENCED_IN_CAPTURE\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  if [ "$NHK" -gt 0 ] || [ "$HKP" = set ]; then
    printf 'K-LOCALHOOK\tYES_LOCAL_ONLY\tCONFIG_RULE(by channel type)\t%s(local machine)\tNONE\tNO_BY_TYPE\t%s\t%s\tNO(local, unversioned)\n' "$ND" "$ND" "$ND"
  else printf 'K-LOCALHOOK\tNOT_EVIDENCED_LOCALLY\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\tNOT_APPLICABLE(Q1)\n'; fi
  for hc in K-HOST-CFG K-HOST-PR K-HOST-AUDIT K-HOST-MEMBERS K-CI-RUNS; do
    printf '%s' "$hc"; for q in 1 2 3 4 5 6 7 8; do printf '\t%s(HOST_NOT_AUTHORIZED)' "$ND"; done; printf '\n'; done
} >> "$OUT/70_answers.tsv"

# ---- S11 overall result (SC-1, SC-2: never a negative) ----
OPEN=$(awk -F'\t' '($1=="K-SESS" || $1=="K-PROG" || $1=="K-STATUS" || $1=="K-NOTES") && $2=="YES_IN_CAPTURE" && $6 ~ /^NOT_DETERMINABLE/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
EXCL=$(awk -F'\t' '$2 ~ /^YES/ && $6 ~ /^NO_/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
NEV=$(awk -F'\t' '$2 ~ /^NOT_EVIDENCED/' "$OUT/70_answers.tsv" | awk 'END{print NR}')
printf 'headline\tNIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK\nnif_a_channel_exists\tNOT_REACHABLE_BY_THIS_PROTOCOL\ncomplete_shown\tNOT_REACHABLE_BY_THIS_PROTOCOL\nopen_local_candidates\t%s\nopen_host_channels\t5\nexcluded_local_channels\t%s\nnot_evidenced_local_channels\t%s\ndcs_v1\tDCS_v0(located paths: see 61, classes K-REG,K-SESS,K-PROG)\nec_v1\tLOCAL_REF_SET(CI runs %s)\n' \
  "$OPEN" "$EXCL" "$NEV" "$ND" > "$OUT/71_overall.tsv"
code=0; [ -s "$ERR" ] && code=1
grep -rlF -- '@' "$OUT" > "$TMP/at"; rc=$?; ok guard "$rc" 0 1
if [ "$rc" -eq 0 ]; then printf 'identity_guard\tFAIL(%s files)\n' "$(cnt "$TMP/at")" >> "$OUT/71_overall.tsv"; code=1
else printf 'identity_guard\tPASS\n' >> "$OUT/71_overall.tsv"; fi
finish "$code"
```

## C.4-r2 Criteria

- **I1–I4 and K1–K4** are as in r1.
- **What can be reached locally:**
  - I1 FAILS[ACTOR_SET_ID] (a writer overlap);
  - I3 FAILS for git recording times (definitional);
  - K2 INDICATED or NOT_INDICATED (SOURCE_STATED);
  - K3 CHANGED_IN_CAPTURE_DURING_CORPUS_SPAN.
- **NOT_REACHABLE_BY_THIS_PROTOCOL:** INDEPENDENT, COMPLETE_SHOWN, and any positive I1, I2, I4, K1 or K4.
- **No completeness window** is evaluated. Date ranges are reported for Stage 1 to compare with the FR-D4 windows (SC-6).

## C.5-r2 Answer vocabulary, and the condition that reaches each value

| Q | Values, each with the condition that reaches it |
|---|---|
| 1 | YES_IN_CAPTURE (≥ 1 path in history) · NOT_EVIDENCED_IN_CAPTURE (0 paths) · YES_LOCAL_ONLY / NOT_EVIDENCED_LOCALLY (local hooks) · NOT_DETERMINABLE_BY_EXISTENCE_CHECK(HOST_NOT_AUTHORIZED) (host) |
| 2 | NORM_TEXT, ADOPTION_EVENT (K-REG, by type) · CONFIG_RULE (K-CFG, K-CI, K-HOOK, K-LOCALHOOK) · IDENTITY_ATTESTATION (K-SIG) · NOT_DETERMINABLE_BY_EXISTENCE_CHECK (K-SESS, K-PROG, K-STATUS, K-NOTES: content not read; host) |
| 3 | NORM_MAKER (K-REG, by construction) · MIXED / INCLUDES_EXECUTOR_WRITERS / INCLUDES_REGISTER_WRITERS [ACTOR_SET_ID] (overlap count > 0) · NOT_DETERMINABLE_BY_EXISTENCE_CHECK (no overlap, or host) |
| 4 | CLIENT_SET (git channels; content timestamps ND) · NONE (local hooks) · ND (host) |
| 5 | NO_BY_TYPE (configuration or identity) · NO_AS_NIF_A (K-REG; or writer overlap [ACTOR_SET_ID]) · NOT_DETERMINABLE_BY_EXISTENCE_CHECK (otherwise). **YES is NOT_REACHABLE_BY_THIS_PROTOCOL.** |
| 6 | NOT_DETERMINABLE_BY_EXISTENCE_CHECK[K-vector] always. COMPLETE_SHOWN is NOT_REACHABLE. |
| 7 | NOT_INDEPENDENT_AS_GIT_RECORD (I3, definitional), with I1 = FAILS or ND and content level ND · ND (host). INDEPENDENT is NOT_REACHABLE. |
| 8 | YES_LOCAL (git channels) · NO (local hooks) · ND (host) |

**Overall (reachable):**
- headline = **NIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK** (by declaration);
- sub-results: open_local_candidates, open_host_channels (5), excluded_local_channels, not_evidenced_local_channels and identity_guard.
- **It is never reported as a negative.**

**Consequences (frozen):**
- DCS v1 = DCS v0 with located path ids (FR-N13).
- EC v1 = the local ref set; CI runs ND.
- NIF-A is not available to Stage 1.
- Every FIRED route waits for a human decision on an NIF-A route (§4-r2 rank 1).

## C.6-r2 Worker and reviewer

The procedure is the same as B.8, with these differences:
- the worker runs twice, and the two outputs must be byte-identical;
- the reviewer reruns on the same ref set, and checks that `50_capture_refs.sha256` equals `sha256sum` of rank-0's `01_refs.tsv`;
- the reviewer checks `identity_guard = PASS`, and spot-checks that no output file contains a path string, ref name or identity;
- the reviewer checks that every answer cell carries a value from C.5-r2.

**Output:** `SCOPING-REPORT.md`, with the rows from `70`, the overall result from `71`, and the SHA-256 of `SHA256SUMS`.

## Residual risks (disclosed, not repaired)

- Work committed without the token, on refs that are not WP-4C-1-named or that have been merged, is invisible.
- Hyphen-like code points outside HY are not matched.
- Quoted paths (those with control characters) may be mis-parsed.
- A commit-message line that imitates a diff header does not affect parsing, because parsing is header-state based. A path literally named `::commit::` would.
- Identity overlap uses emails, which are ACTOR_SET_ID, so it is indicative only.
- The EXEC writer set is approximated by path exclusion. Either kind of overlap (with EXEC or with REG) disqualifies a channel as NIF-A, so misassigning a writer between EXEC and REG does not change Q5.
