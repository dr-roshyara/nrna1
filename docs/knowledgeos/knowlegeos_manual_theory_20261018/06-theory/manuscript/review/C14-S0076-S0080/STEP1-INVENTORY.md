*Reviewer's step-1 inventory, verbatim (window C14; saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — C14

Blind inventory. I read only the five sources and the reviewer instructions; nothing under `06-theory/` and not RESEARCH-PURPOSE.md. Independence here means process independence (a separate reviewer process), not personal independence. Arithmetic checks were done by hand on the source text; I ran no script. Each source is ranked by importance to a theory of a knowledge-governance kernel. [I] marks my own judgment.

**State lineage.** S0078 is the accepted BC-7 refinement, as corrected. S0076 records the acceptance of Verification #3 on that correction. S0077 records the acceptance of the model "as refined". S0079 is the portfolio reconciliation of 08-18. S0080 records the ARB's sequencing decision, which adopts S0079 as portfolio truth. S0076, S0077, S0079 and S0080 are all written by Governance with the same vocabulary (G-3, EKS-01, "disclosed, not repaired"), so they are one line of support. S0078 is a different process, but it is the only source for the model content.

## S0078 — BC-7 domain-model refinement (the main theory source)

1. **Recorded fact vs derived state vs validation input** [S0078 L155–163]. BC-7 stores only what happened (the transition log and the grant register). Everything about where things now stand is computed by the fold. "The party recording an act never supplies the standing against which the act is judged." This is a candidate kernel law: one truth, one producer, no self-supplied premise. It is tied to I-10, "the record outranks prose".
2. **T-1 split by enforcement strength** [L167–169, L189]. T-1a: a submitted transition never supplies its own validation state. This is structural, because the record is folded first [L146]. T-1b: a derived key carried on a transition is inert, because the fold does not read it. T-1c: writers must not store derived keys. T-1c is a writer obligation, observed only as a census. The invariant "now says less … and everything it says is true."
3. **OQ-12: enforcement class as a dimension of an invariant** [L314]. The proposed classes are refused · overwritten · not-consulted · writer obligation. This separates "the mechanism prevents X" from "no one has done X". [I] This is a candidate kernel primitive (modality of an invariant). It is open and was deliberately applied only to T-1.
4. **Three protection modes** [L138–144]. Refusal is by type, fields, ownership and writer class; there is no key whitelist. Overwrite covers `seq` and `registeredBy`. Non-consultation is the fold's read set. Refusal of a derived key is not among them: a submitted `mutationOwner` would be stored.
5. **Answerability precondition vs verdict function vs consistency invariant** [L88–98]. `authorized` refuses an unregistered session (a precondition on who may ask). Its verdict is ∃g: AUTHORIZED ∧ scope equality, with no reference to the session [L79–86]. No positive invariant spans lifecycle and authority. "A precondition on who may ask is not a consistency rule about what is answered" [L120].
6. **The C-3 verdict is (D), a mis-scoped invariant statement** [L212–222]. I-5 governs the Authority part. Measuring it against a lifecycle transition repeats the conflation that I-4 forbids. Where I-5 applies, enforcement is total: 47/47 grants written by governance [E-6, L206]. The verdict lies outside all three ARB candidates (A/B/C) [L228–234]. Its working rule is "the cheapest true repair".
7. **Writer identity vs authority identity is already in the model** [L218, L232]. T-4 says attribution is a claim, never an attestation. I-7 says process identity never authorizes, realized by absence. "An instance that the model predicts is not a counter-example."
8. **Producer identity is outside every bounded context** [L222]. BC-7 owns the rules under which a lifecycle fact is admitted. It cannot know who produced the fact. That ability is "a property of access to the mechanism", an operational and governance concern. [I] This is a kernel/application boundary claim: the kernel admits facts but cannot attest their origin.
9. **A HANDOFF confers nothing; ownership passes only at START** [E-4, L204], and START needs a human act together with a recorded predecessor handoff [E-5]. So the path from "an architecture lane recorded a transition" to "authority was written by a non-Governance actor" does not exist [L220]. This is a no-escalation property.
10. **OQ-10: `recordedBy` has more than one referent** [L258]. On START it names the origin of the act (the human). On REGISTER/COMPLETE/CANCEL it names the acting role. On the two HANDOFFs it names the performing lane. "A field whose referent shifts by transition type is what invites an invariant to be measured against the wrong part." This is a homonym. It is open, with no solution proposed.
11. **Cross-part reads inside one root** [L100, L266; N-1, OQ-13]. The refusal in `authorized` and the `authorizationLinkage` in `identity` (a value scoped to the work item, shown inside a session-scoped answer, taken as the last grant) make the two parts less separable. They strengthen the case for one aggregate root and weaken RA-4. OQ-9 (one aggregate or two) stays open.
12. **Temporal drift of census figures** [L71, L268]. Transitions went 117 → 121 → 126 (later 133) and grants 41 → 47 (later 49). The proportions are unchanged. The censuses "should carry their measurement date." [I] Measured facts are indexed by time; this links to the provenance issue in S0076.
13. **Source defect, internal contradiction** [L67 vs L169]. The corrected §2 row reports 20 non-canonical keys. The replacement text for T-1 at L169 still says "no non-canonical key appears anywhere in the estate", with the old denominator 0/126; L67 says 0 of 133. The correction left the falsified claim inside the proposed invariant text.
14. **Source defect, numbers** [L60 vs L258]. The type census gives START 35, but OQ-10 cites "6 of 37 STARTs" and "37 of 37". The measurement points differ. S0076 later found the "6" figure unreproducible (it gets 14 of 38).
15. **The disclosure rests on self-declaration** [L27–38]. Separation is "Declared, not attestable" (INV-ATTR-2). The refiner had been exposed to the ARB's framing. The input boundary was extended to primary source, on the argument that "primary source is not a position" [L40].

## S0076 — Verification #3 acceptance registration

1. **Measurement-provenance law** [S0076 L28]. "A figure stated without its method cannot be reproduced, and a figure handed onward as a check criterion inherits authority it has not earned." [I] A candidate kernel law that ties provenance to authority. It was routed, not solved.
2. **Precision vs integrity** [L16]. F-1 and F-2 are "regressions in precision, not in integrity". The correction turned a false cell into a dated census and an unstated ambiguity into a recorded question.
3. **F-2 as an unreproducible figure** [L24]. "6 of 37" has no stated selection rule; a plain criterion gives 14 of 38. It is Class B because it sits inside the evidence, and the conclusion it serves rests on findings classified `Observed`.
4. **F-1 class A** [L23]. A metadata miscount (5/14 against git's 16/5) concealed nothing, but it was handed to verification as a check criterion.
5. **Independence limitation kept permanently** [L30]. Two of three independence links are compromised (the same pen for V#2 and the correction, the same model for V#1 and V#3). It is a qualification "never repaired away". [I] Assurance is graded, not binary.
6. **The off-record lane** [L57–59]. The V#3 lane was never STARTed, so the G-3 conjunction (handoff and human START) never held. The acceptance still rests on the report's substance. [I] This is a counterexample: lifecycle validity and validity of the content are decoupled. Nothing was back-dated; the log is append-only.
7. **Distinction** [L22, L26]. Knowledge-integrity findings are separate from architecture defects. Accepting a verification does not reopen the model.
8. **Acceptance is scoped** [L34, L51]. Accepting a verification is not accepting the model, and authorizes nothing.
9. **R-3 is "correctly unresolved"** [L47]. Leaving a question open is accepted as a correct outcome.

## S0077 — BC-7 acceptance as refined

1. **Accepted object** [S0077 L11, L30]. The proposal plus the refinement and correction become the authoritative tactical model under the seven-context strategic model.
2. **A qualified acceptance** [L36–41]. The independence limitation and the off-record START are carried as permanent qualifications. [I] An accepted state can carry defects it discloses.
3. **Acceptance ≠ implementation authority** [L34]. OQ-10 stays open, ADR-AIP-04 stays deferred, and CAP-14 and ADR-AIP-03 are unchanged.
4. **"Never accepted on its author's word"** [L26]. The claim is that acceptance requires verification by others.
5. **Source defect** [L26 vs L18–24]. The text claims "four independent verification passes", but only V#1–V#3 are listed (the ARB acceptance is not a verification). It also calls them "independent", against its own L38 ("two of three … compromised").
6. **Lifecycle annotation vs content** [L45]. The status banner may change; the verified content stays untouched.

## S0079 — portfolio lifecycle reconciliation

1. **Closure rule** [S0079 L4]. Close only when both the substantive deliverable and the lifecycle evidence justify it, "not … merely because prose says it is finished." [I] This is a conjunctive closure law and the converse guard of I-10.
2. **No retroactive manufacture** [L5]. No START or HANDOFF is manufactured after the fact, and off-record work is never converted into on-record work. This is append-only history.
3. **Pattern E-2: done in prose, not closed in the record** [L74]. It covered 15 of the 19 open lanes. "Bookkeeping drift, not work drift." The arithmetic checks: 15 + 4 = 19, and 5 COMPLETE + 1 ACTIVE + 4 REQUIRES DECISION = 10 items.
4. **COMPLETE means the work was finished** [L57]. A superseded lane that produced nothing is not COMPLETEd, because "completing it would assert it finished work it never did." [I] COMPLETE is an assertion, not a disposal.
5. **Not closing the only remaining assurance** [L47]. Closing it would have made the portfolio look clean while destroying the experiment's only remaining assurance.
6. **Supersession must be recorded, not inferred** [L62].
7. **Off-record V#3 closed, with the defect stated** [L73]. This is the same counterexample as S0076 item 6.
8. **Taxonomy of non-closure** [L44–67]. The categories are active-unperformed, eligible-unstarted, superseded-empty, stale, and empty record.

## S0080 — ARB sequencing determination

1. **A recommendation is never an authorization** (ES-001.2) [S0080 L4, L51]. Recommended dispositions are not executed until an explicit act.
2. **Capability first, then ownership, then context or stewardship, then human role, then agent, then shared service** [L33]. Roles are derived from capabilities, never the reverse [L24–28]. [I] This is a candidate ordering law for the kernel/application boundary.
3. **Sequencing rationale** [L18]. Starting implementation architecture before the role model "risks encoding an incomplete role model". The order is deferred question → dependency → order.
4. **Stability of an accepted model** [L57]. Reopen only on new evidence or explicit change pressure, not through "endless architectural polishing". [I] A stewardship or defeasibility rule.
5. **Mechanism gaps** [L46, L48]. CANCEL is terminal (there is no edge back). There is no "retire" transition, so retirement would be a governance registration outside the workflow. [I] The lifecycle vocabulary is incomplete for retiring a record.
6. **A reconciliation adopted as truth** [L8–10]. A synthesis document is declared "authoritative portfolio state". [I] This sits in tension with the record-outranks-prose principle (S0078 L163, S0079 L4).

## Cross-source table

| Theme | Sources | Note [I] |
|---|---|---|
| Record over prose, append-only, no back-dating | S0078 L163 · S0079 L4–5 · S0076 L59 | one law, three statements; S0080 L10 makes a prose reconciliation "truth" |
| Lifecycle validity vs content validity | S0076 L57–59 · S0077 L39 · S0079 L73 | the off-record V#3 is the counterexample to the claim that acceptance requires a valid lifecycle |
| Precision/provenance of figures | S0076 L28 · S0078 L71, L268, L67/L169 | S0078 itself still contains a stale absolute claim |
| Assurance graded, not binary | S0076 L30 · S0077 L26 vs L38 | the "four independent" overclaim |
| Enforcement modality | S0078 L138–144, L314 | candidate primitive; generalization untested |

