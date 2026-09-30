# Adversarial review of ANSWER.json / ANSWER.md

Inputs: HYPOTHESES.md, TASK.md, RECORDS.json, CODING.json, CODING-REVIEW.json, SOURCES.md and the answer files. Nothing else was used. Quotes are verbatim, with markdown emphasis removed. Every issue is classified as one of: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Full detail is in REVIEW.json.

**Bottom line.** The answer's headline holds: nothing is IDENTIFIED and nothing is FALSIFIED. Several findings behind it are wrong, in both directions:
- **Overstated labels.** P3, P4 and P5 are labelled PARTIALLY IDENTIFIED even though the answer says they do not discriminate.
- **Understated evidence.** On P2 and P7 the answer treats the evidence as weaker than it is.
- **Mixed targets.** Two sequences (S3, and S4/PC3) combine different targets.
- **Missed evidence.** It misses a status-mediation episode (R-36) and several work records in the source text.
- **Wrong date.** It says the corpus ends on 2026-08-04; it runs to 2026-08-15.
- **Hidden circularity.** It does not notice that coding rule R-A3 makes each "intervening act" the same record as the norm state that follows it.

---

## 1. Sequences

| # | Class | Issue |
|---|---|---|
| SQ1 | coding | **S3 combines two targets.** R08 and R09 are about *the motion opening WP-7B-R1*, i.e. whether it is a note or a ruling. R04 is about *WP-7B-R1 implementation*. So WP-7B-R1 implementation has only one NORM record and should not be sequenced. PC4 comes from this merge, yet P1 still cites it ("and implicit PC4"). |
| SQ2 | source interpretation | **R08 → R09 is not a change in force.** The operative text already declared the package OPEN: "while its own handover statement and governance queue both declare the package OPEN". The row also says "the DECISION is unchanged". The heading placement was a drafting discrepancy that the register recorded. It was never a norm in force. The answer also misses the second annotation on the same row, which changed the category from Delivery to Planning, again with no change in force. |
| SQ3 | **substantive** | **PC3 compares different scopes.** R10 prohibits *beginning WP-4B implementation*. R36 authorizes *three batch-7 items* by lifting a *batch-7 freeze*. Both R-81 ("inside the seam already authorized by R-72") and R-86 ("resume implementation") assume WP-4B was already authorized and under way. So R-86 ends a freeze. It does not lift R10. The act that lifted R10 is not in the corpus. |
| SQ4 | coding | **R10 is coded PROHIBITED, but R-72 is a contingent authorization.** Its heading reads "AUTHORIZED … SUBJECT TO A PROVISO THAT IS NOT YET SATISFIED", and the coder concedes STATUS_CONSTRAINT would fit. The distinction matters: a contingent authorization that becomes operative when its condition is met ("when they land, engineering proceeds RED") is the one place where a change without an act could occur. |
| SQ5 | source interpretation | **The R11 timing confound is overstated.** R11 cites R-73..R-75, so they came before it. R-77 says "R-73–R-76 stand … AND WP-4B REMAINS BLOCKED". "Remains" means the block continued, whatever the time of day. R-79, which the answer does not use here, puts WP-4B RED after "the two remaining Board acts", and R-86 says those acts were still OPEN on 08-04. What stays uncertain is *what the block covered*: the tests were written "with producers deliberately unnamed". |
| SQ6 | formal reasoning | **The 7B and 7C polarity changes are trivial.** The UNAUTHORIZED starting states only record that no authorization exists: R14 is a scope limit, and R31 is a recording note that carries no ruling force. Going from no authorization to an authorization always needs an authorizing act, on either hypothesis. So these changes are real but say nothing about P1. |
| SQ7 | coding | R14's target is composite (7B and 7C). The answer discloses this correctly. The consequence is that S1 and S2 share their starting state and are not independent at the origin. |
| SQ8 | source interpretation | Ordering same-day rows by register number is well supported. No issue. |

## 2. Intervening acts

- **AC1 (formal reasoning).** In PC1, PC2 and PC4 the "intervening act" and the resulting norm state are **the same record** (R32, R34, R04). Coding rule R-A3 says a register row performs its own act. So the act is not observed separately from the norm state.
- **AC2 (coding).** The ARB is given as the actor of PC1 and PC2 without an INFERENCE label. That attribution comes from row headings, which CODING-REVIEW marks as INFERENCE. The answer applied that label to R14 but not to R32 or R34.
- **AC3 (source interpretation).** R09, "filed as a ruling", is a recorder's bookkeeping act with an inferred actor (the verb is passive). It is not norm-making.
- **AC4 (evidence scope).** For PC3 the act that lifted R10's prohibition is **NOT ATTESTED**, not "PARTIAL". The act that created the batch-7 freeze is missing too.
- **AC5 (coding).** R27 ("APPLIES §12") does not belong among E1's records.
- **AC6 (source interpretation).** E1 has a step the answer misses. As issued, R-81 claimed force ("Batch 7 partially released: isolation repair ACTIVE"). The Chief's annotation then withdrew that claim ("“BATCH 7 IS RELEASED” DOES NOT HOLD") before R-86 adopted it. This is still one decision.
- **AC7 (wording).** The list of applied corrections omits the R06 act.status.operation correction. No effect on the analysis.

## 3. Status mediation

**Reviewer's independent_n = 2** (one certain, one probable).

- **E1: accepted.** R-81..R-85 went from PREPARED to ADOPTED by R-86. The identical annotations on R-81 and R-83 are one decision, and the answer correctly counted them once.
- **E3: accepted as probable, missed by the answer.** R-36 (2026-07-09) adopted the Promotion Review. The matrix entries are *Recommendations*. They bind only through the adoption (CODING R15: "It becomes a binding norm only through R-36"), and the ARB changed the text when adopting it ("ARB wording refinement at adoption"; "2 added by ARB at review"). The decision, date, actor and regime all differ from E1. Caveat: no quote says the matrix was *not* in force before R-36. That premise is an INFERENCE from the recommendation/adoption structure, supported by R-47: "PROPOSED and non-binding — silence is not adoption".
- **E2: rejected** (see SQ2). No change in force is observed.
- **Candidate, not counted:** AST-016 went from QUALIFIED to ADOPTED (S0815). No force change is stated.
- **Not observed:** R-87 and R-88. The answer handles these correctly.

## 4. Predictions

| ID | Answer | Reviewer | Favours | Reason |
|---|---|---|---|---|
| P1 | NON-ID | **NON-IDENTIFIABLE** | neither | Agree, for stronger reasons than selection. Every observed change creates an authorization (SQ6), and each act is the same record as its outcome (AC1). If "in response to work" also covers acts triggered by work, H-B predicts exactly what H-D predicts. The one place a change without an act could show up, R-72's proviso being met, is never observed. |
| P2 | CONFOUNDED | **CONFOUNDED** (a candidate anomaly against H-B, n=1) | neither; weakly against H-B | Only H-B is at risk here. The evidence points more strongly to non-conformance than the answer allows (SQ5). What remains unclear is (i) whether RED tests with unnamed producers fall under the block, and (ii) the unobserved rows R-76, R-78 and R-80. Uncoded work observations are also missing: R-94 "Executed at IMPLEMENTATION_PROGRESS.md:58", R-86 "engineering waiting solely on a constitutional act", and S0804 "Nothing implemented this turn by the review's own instruction". |
| P3 | PARTIALLY ID | **NON-IDENTIFIABLE** | neither | The answer says this "does not discriminate". Observational equivalence means NON-IDENTIFIABLE. It also misses feedback cases that run through acts: R-41 (the WP-1 inconsistency led to ES-004.3), and S0815 (O-CLOSURE-VOCAB led to a binding vocabulary ruling that was "Applied immediately"). |
| P4 | PARTIALLY ID | **NON-IDENTIFIABLE** as a discriminator; H-D's clause corroborated n=2 by self-report | neither | H-B does not deny status mediation, so observing it cannot discriminate. Force is seen only through the adopting act's own declaration, a standard the answer itself rejects in P1. R-89 remains a possible falsifier of H-D. |
| P5 | PARTIALLY ID | **NON-IDENTIFIABLE** | neither | H-B is silent, so P5 cannot discriminate. R-94 may be a scope question (R-90: operational acceptance is not constitutional) rather than a breach of the capability rule. |
| P6 | CONFOUNDED | **NON-IDENTIFIABLE** | neither | H-B predicts the record "carries **or references**" work. Even the register rows alone reference and report work ("Executed at …", "Container resolution executed and reproduced", R-83's grep). So H-B is satisfied at every boundary, and H-D ("need not") allows the same content. The result does not depend on the boundary. |
| P7 | INSUFF. EV. | **CONFOUNDED** (with R-89's status) | neither | There is positive indirect evidence that WP-4B was not yet accepted when R-89 was issued. R-86 authorized batch-7 execution the same day, and acceptance only comes after RED→GREEN→VERIFY. R-89 also says "§WP-4 remains OPEN". Whether this counts against H-B depends on whether R-89, which was PREPARED, ever took force: the Decision Authority says "R-89 already governs authorization". On the question of act versus no act, P7 reduces to P1. |
| P8 | NON-ID | **NON-IDENTIFIABLE** | neither | Agree. But the corpus runs to **2026-08-15** (S0815), not 2026-08-04. The S0815 remediation cases also run through acts. |

## 5. Corpus limitation vs domain property

**No true instance found.** No prediction is scored for H-D because execution records are missing. Near-misses and related scope errors:
- **LP1 (P6).** "Draw the boundary at the register and it matches H-D's self-description." This treats the register's filing convention as if it agreed with H-D. It is not scored, but it presents a feature of the corpus as a fact about the domain.
- **LP2.** "The only execution record in the corpus." R11 is only the only *coded* one. The source text reports other execution (R-94, R-91 Phase 1, R-41, S0815). Understating the work evidence is the precondition for this error, although the answer does not go on to commit it.
- **LP3.** "The corpus ends on 2026-08-04". This is false.

## 6. Circularity

**The exact chain "few execution records → rules → H-D" does not occur.** Two weaker circularities do:
- **CI1 (coding).** R-A3 builds H-D's structure (norm only through act) into the coding. "Every polarity change has an attested act" then appears to confirm it. See AC1.
- **CI2 (formal reasoning).** In P4 and P5, force is shown only by the norm-making text declaring that it confers force. The answer holds P1 to a stricter standard ("shows the actors endorse H-D's rule. It does not show that the rule holds") but does not apply that standard to P4 or P5.

## 7. Minimum evidence

The specifications are useful and name the right missing records (the text of R-73..R-80 and R-87/R-88, and R-89's adoption status). Their weaknesses:
- **P1.** The H-D falsifier requires a complete log that includes *unregistered* acts. Absence of an unregistered act can never be demonstrated. Replace it with a closed-world capability register plus an outcome state attested outside the register. The H-B falsifier works only if "in response to work" means changes *without* an act; the specification must fix that reading first. The requirement of "≥3" episodes is arbitrary.
- **P2.** The specification is sufficient if it adds the text of R-76, R-78 and R-80, times of day for R-77 and R-79, and a ruling on whether RED counts as "implementation". The H-B falsifier ("no later sanction") needs a time window, or it cannot be tested.
- **P3.** Cannot discriminate unless it requires feedback *without* an act, which is P1 again. The falsifier "actors treat these as normal" depends on attitudes and cannot be operationalized as written.
- **P4 and P5.** Cannot discriminate, because H-B has no falsifier. Three episodes only corroborate H-D, and one counterexample is enough to falsify it. In P4, "cited as governing" must exclude citation by the adopting authority itself, since that citation may be the adoption.
- **P6.** The H-B falsifier "while the norms still function" cannot be observed without observing work. Drop P6 as a discriminator.
- **P7.** R-89's status must be resolved first.
- **P8.** Adequate: it has a time window and is minimal. Only the end date needs correcting.

## 8. What survives

**Identified: none. Favours: neither.** The hypotheses come close to being nested. H-D says nothing about work (P2, P3, P6). H-B, read as allowing acts that work triggers, accepts every act-based claim H-D makes (P1, P4, P5, P8). Only two kinds of observation could separate them:
1. a verified norm change with no act, which would falsify H-D's "only"; or
2. verified non-conformance, or verified absence of feedback, which would count against H-B.

The corpus contains **no** case of (1). It has **two confounded candidates** for (2):
- R11, set against the WP-4B block recorded in R-72, R-77 and R-79; and
- R-89, set against R-72's acceptance gate.

The answer reaches the same bottom line. But it rates P3, P4 and P5 too high and P2 and P7 too low. It also misses the R-36 episode, the R-79 sequence, the execution reports in R-94, R-91 and S0815, and the fact that the corpus extends to 2026-08-15.
