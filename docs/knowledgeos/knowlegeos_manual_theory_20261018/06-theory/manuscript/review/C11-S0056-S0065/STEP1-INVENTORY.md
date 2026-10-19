*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind). Harness time: commissioned 15:03:58Z, received at the save.*

# STEP 1 INVENTORY — C11/T3

*Blind inventory. I did not open anything under 06-theory/ or RESEARCH-PURPOSE.md. I read in full: S0056–S0065, S0067, S0071, S0913. I read S0164 by targeted sections only: lines 1–440, 500–560, 700–830, 1060–1135, 1400–1430, 1514–1530, 1785–1800, 1866–1880, 1990–2140, 2213–2250, 2414–2445, 2595–2602, 2700–2830. I scanned the rest by heading and grep only, so it is "not read" for completeness. Marks: [I] = my own judgment. For arithmetic, "checked" means I recomputed it by hand in this session (reported, not shown).*

**Independence:** this reviewer is process-independent of the authors. It is not model-independent: I am the same model family as several of the source authors.

---

## S0056 — Phase A v1.1 correction verification (#2)
1. **Process separation vs model separation** [L25–29]. v1.1 and V#2 were written by the same model (Claude Opus 5). "A shared model can share a blind spot." This independence boundary is separate from process separation.
2. **Independence is Declared, not attestable** [L30–31]. The record cannot show which process wrote the report.
3. **Method: agreement between earlier artifacts is a hypothesis, not corroboration** [L35]. Re-derive from primary evidence, not from V#1, v1.1 or the producer's disclosure.
4. **Sweeps for residuals and regressions** [L35, L51, L113–119]. A commissioned fix list finds neither on its own. A correction created a new defect of the same class it was sent to repair (N-1: title v1.0 over a v1.1 banner) [L117].
5. **The verifier's evidence must not be borrowed to upgrade the producer's claim classes** [L76–90]. "Declared" stays Declared even where the verifier cleared it. Class totals: 44 Observed · 11 Declared · 5 Inferred [L87].
6. **Two defect classes: document-integrity vs domain-model** [L13]. Proportionality: the class sets the size of the correction cycle [L13, L145–151].
7. **Snapshot integrity** [L60–64]. A figure fixed at a snapshot ("8 workflow records") is deliberately not updated, because updating it would turn the snapshot into a live reading. Corrected figures are true at the snapshot because their sources were unchanged since then.
8. **"Wrong pen" rule** [L166]. A verifier that repairs its own findings becomes the producer and is barred from the next verification. Producer/verifier separation propagates forward.
9. **"Explanatory-power improvement ≠ defect"** [L107–109]. A weaker but true citation does not trigger a correction cycle. This acts as a stop rule against endless correction loops.
10. **The verdict must not be read as acceptance-readiness** [L9–11]. "Repairs correctly applied" answers only the commissioned question.
11. **Post-delivery amendments disclosed** [L168]. The claim "no finding added/removed/softened" is a self-claim.
12. **Measurement detail** [L49]: 25 grep matches, 0 dependency constructs. The text match is not the semantic match.
- *Lineage:* same day as S0057–S0065. Its amendments and the "different model" suggestion appear verbatim in the S0164 working log [S0164 L1076–1102].

## S0057 — N-1/N-2 correction delivery note
1. **Move, never renumber** [L13]. Identifier stability takes precedence over presentational order, because other artifacts reference the identifier (referential integrity).
2. **Pre-edit state check** [L6]: blob `8692a7de` exact match, "no drift". A precondition on the object before the edit.
3. **Diff discipline** [L24]: exactly two hunks, moved lines byte-identical.
4. **Four integrity checks** [L22–27]: diff, reference, snapshot, classification.
5. **The corrector is barred from verifying its own correction** [L35, L41].
6. **The PO recommends a different model for V#3** [L35]. Recorded as a recommendation only.
7. **All four checks are self-reported by the producer** [L20–27] [I]. They are later re-derived by S0059 O-2.
8. **Authority chain cited** [L4–5]: grant + verbatim human START.
- *Lineage:* S0056 → S0057 → S0059. Same work item, same day.

## S0058 — Verification #3 registration (Governance)
1. **`authorized` never consults the session** [L81–88]. It is scope-string equality over AUTHORIZED grants, so a COMPLETED architecture lane reports itself authorized under a verification grant. **This is the key observation for "authorized vs recorded".**
2. **Verification #2 ran with no lane, no START and no grant** [L45–51]. "Its independence is not recorded anywhere." The content is not thereby wrong, but the assurance chain rests on an artifact made outside the governed lane.
3. **Wrong-referent defect** [L18–35]. The prompt cited V#1 as V#2; measured N-1/N-2 mention counts were 0/0 vs 8/7. "Third instance of the same class"; "checking the referent is load-bearing."
4. **Anachronistic authorization linkage** [L77]. The verification lane reports a grant that post-dates its work: a temporal-validity defect.
5. **A lane may not complete its own assignment** (G-1) [L43]. COMPLETE = delivered ≠ accepted.
6. **A fresh verification requires a new assignment** [L63]. The role is immutable per assignment (R8).
7. **The different-model preference is a recommendation, distinct from the R-34 obligation** [L65]. Deontic level: recommended vs required.
8. **The evidence chain is split across two documentation roots** [L92–94]. Nothing was moved, because moving would break references.
9. **Provenance block** [L8–13]: "Evidential: DECLARED, not attested."
- *Lineage:* Governance session (Session 2), same day. S0067 L83 later re-reads finding 1.

## S0059 — Verification #3 report
1. **Model separation from the corrector: NIL** [L21–30]. Corrector and verifier are both Claude Fable 5. The PO's different-model preference is not satisfied.
2. **Bounding claim** [L27–30]: mechanical checks (diffs, byte comparisons, line references) are "the check class least exposed to a shared model blind spot". Not demonstrated; it is an assumption [I].
3. **Prior role disclosed** [L17–20]: this process performed V#1. Independence was assessed per finding source (V#2, another model), not per subject [I].
4. **Two state instruments agree** (resolver and fold) [L4]. Redundant instruments are used to confirm lane state.
5. **The referential-integrity rule "did real work"** [L55]. Had the block been renumbered, seven references would dangle. This counterfactual is evidence for rule 1 of S0057.
6. **Delivery-note claims re-derived, not trusted** [L72].
7. **"Correction chain closed: no verified defect remains open"** [L7, L77]. The scope is limited to *verified* defects. Unknown defects are not covered [I].
8. **Spot-confirmation by another model offered as cheap but not required** [L73].
9. **What V#3 did not do** [L81]. Explicit scope exclusions, including no judgment of the whole architecture.
- *Lineage:* the V#1 process (Fable 5) verifies a Fable-5 correction of findings from an Opus-5 verifier. Model identities across S0056/S0059: v1.1 = Opus 5, V#2 = Opus 5, N-1/N-2 fix = Fable 5, V#3 = Fable 5 = V#1 process.

## S0060 — Acceptance of the V#3 evidence (only)
1. **Accepting verification evidence ≠ accepting the artifact verified** [L7, L22–24]. Two separate acts, deliberately kept apart.
2. **Preconditions are verified before recording** [L9–16]. Registration is conditional, not automatic.
3. **V-E stays an acceptance-context observation** [L24]: the snapshot's machine figures cannot be audited.
4. **The Stage 2 gate depends on baseline acceptance** [L24].
- *Lineage:* Governance registration. It quotes S0059 verbatim.

## S0061 — Phase A v1.1 baseline acceptance
1. **Acceptance has a bounded meaning** [L31–43]. It authorizes Stage 2 *analysis* only. It approves no bounded contexts, aggregates, implementation, technology or migration. Acceptance ≠ approval of what the accepted thing implies.
2. **Accepted "with eyes open"** [L45–47]. A baseline whose figures can never be audited retroactively is still accepted as authoritative. Authority is given without auditability.
3. **The accepted object is pinned to a content hash** (`40026b12`) [L27]. The identity of the object of acceptance is explicit.
4. **The precondition list cites V#2 as "completed ✅"** [L19]. It does not weigh S0058's caveat that V#2 ran outside any governed lane, a caveat recorded "so it is weighed at acceptance" [S0058 L51] [I]. This is a dropped caveat.
5. **"No implementation decisions implied"** is justified by the *absence* of an implementation lane in the record [L25]: absence of a record used as evidence.
6. **A lifecycle-status annotation on the accepted artifact is not a content change** [L55]. Supersession discipline.
7. **Two messages, one act, registered once** [L5–8]. Identity of a human act across utterances.
- *Lineage:* Governance. Cites S0056/S0059/S0060.

## S0062 — Stage 2 bounded-context confirmation
1. **Three-test method for a bounded context** [L14–20]: language, ownership, change-reason. "A context that fails the language test cannot be rescued by having code"; a rules-only context with no code is real.
2. **Central finding: the measured centre of gravity is undeclared** [L60, L69]. T-1 "authority registration & audit" and T-3 "role separation" have no CAP id and no context.
3. **CBC-1 Governed Session Orchestration is promoted to candidate BC-7** [L75–84]. Its language is disjoint from the six declared contexts. **Against:** the parsimony ruling (ES-001.1). Confidence moved low → medium.
4. **BC-4 Adversarial Review has two readings** [L44]: (a) a rules-only context, or (b) a policy of the role model inside orchestration. Producer≠verifier is enforced by the same record that holds assignments (OQ-2).
5. **BC-1 may contain two knowledge systems** (EKP vs PKS, U-4) [L38, L177 OQ-1]. "The boundary line is right or one line short."
6. **C-3: authority state has no provenance** (records gitignored) [L113]. The store contradicts the context's purpose.
7. **C-4: the writer boundary rests on convention** [L114]. `recordedBy` is unvalidated on REGISTER/HANDOFF/START; I-5 is mechanism-backed on 2 of 5 transitions.
8. **C-2: interpretation authority can be redirected by an env var** [L112].
9. **BC-5 "declared Core" is the thinnest context as measured** [L47]. "The baseline cannot distinguish ambition from architecture."
10. **"Never infer a boundary from a name"** [L104 M-4].
11. **The context map is built from relationship patterns** (Conformist, Supplier), labelled Inferred [L132–169].
12. **Evidence restriction** [L5]: the accepted baseline only, every added judgment marked Inferred. Single-source dependency [I].
- *Lineage:* Architecture terminal. Builds only on S0061's accepted baseline. Feeds S0063/S0067.

## S0063 — KOS-ARCH-BASELINE-002 commission
1. **Hypotheses supplied "to be tested, not inherited"** [L21, L27]. Candidate owned and not-owned lists. "The exclusion list matters as much."
2. **BC-7 ↔ Governance hypothesis** [L28]: policy ("who may approve?") vs execution lifecycle ("was approval recorded?"). Business rule ≠ business process.
3. **BC-7 ↔ Knowledge Engineering hypothesis** [L29]: "what is known?" vs "how governed work progresses?". Do not merge.
4. **ADR-AIP-04 frozen** [L35]. BC-7 discovery may yield evidence about role ownership; "designing roles before understanding their ownership".
5. **A prohibition enforced mechanically** [L6]: implementation is excluded from the declared role set, so "no implementation" is a property of the record, not of prose.
6. **Capability Identity Invariant** [L45]: one identifier = one semantic owner.
7. **Placement is derived from the knowledge subject, not inherited from a predecessor path** [L49].
8. **BC-7 is already recognized by ADR-AIP-03 before this analysis** [L19, L45] [I]. The decision precedes the boundary discovery that is supposed to test it: a possible order inversion.
- *Lineage:* the PO/ARB act is drafted from the Principal review. Its hypotheses come from the reviewer, who later endorses the results (S0064) [I].

## S0064 — Principal review registration (three passes)
1. **The first pass was mis-targeted** ("the wrong artifact (EM-IMPL-002)") and is superseded by the reviewer's own declaration [L5]. Its findings are nonetheless "consistent with the second pass". A review of the wrong object agreeing with the right one undermines the review's discriminating power [I].
2. **The two boundary discoveries are endorsed** [L16, L177]. "Governance decides whether an act was authorized; BC-7 answers whether it was recorded." Knowledge seam: "meaning vs movement / adjudicated identity vs folded occurrence"; "they reference each other; they must not become one model."
3. **"Strategic boundary ≠ physical structure"** [L177].
4. **"Observation ≠ Interpretation ≠ Decision"** [L46]. Prompt principles for the next stage.
5. **Example event names are non-canonical placeholders** [L31]. Examples in a commission never pre-decide vocabulary.
6. **Draft acceptance text refined to avoid semantic duplication with ADR-AIP-03** [L213, L219–234]. Acceptance adopts the model *that realizes* already-decided recognitions.
7. **Registering a received review is not self-verification** [L10]. The producer is still barred.
8. **Name-fitness**: "Governed Session Orchestration" names the context by one of its entities; keep the name now and re-test later [L20].
9. **Deliverable status is unchanged by a review** [L9, L169]: recommendation ≠ decision.
- *Lineage:* the producing terminal registers the reviewer's text. S0164 L1119–1135 suggests these "Principal" reviews were pasted from a separate assistant session [I].

## S0065 — KOS-ARCH-BASELINE-002 acceptance
1. **Accepted without a separate independent verification of the v2 model** [L20]. The acceptance rests on "the Principal (human) review" and PO/ARB authority.
2. **It quotes the first-pass verdict** ("VERIFIED WITH NOTES", "do not reject") [L16]. S0064 L5 declares that pass superseded [I].
3. **"Principal (human) review"** [L20]. S0164 L1119–1135 suggests it may be AI output pasted in [I]. UNKNOWN; it matters for "independent assurance".
4. **The two boundary sentences enter the *accepted model*** [L29]. A finding becomes authoritative by acceptance: the authorized/recorded distinction is itself now "authorized".
5. **ADR-AIP-01 is amended by reference through acceptance** [L27]: "never silently".
6. **What acceptance does not mean** [L31–36]: no implementation, ADR-AIP-04 open, BC-4 open.
- *Lineage:* Governance. It cites S0064 and S0067.

## S0067 — Architecture Landscape v2 (topic: authorized vs recorded)
1. **Grant split** [L51]. BC-7 owns the grant *record* (schema, status, queryability), not its *authority content*. "The record never manufactures authority (G-2/R5b)."
2. **Human Act corrected to NOT owned** [L52]. "Owning the registration of a thing is not owning the thing." The act exists outside the software.
3. **Owns vs preserves an invariant** [L57]: the reason lives in this context vs the context enforces it for an owner elsewhere. This is the main boundary instrument here. I-2 is split: BC-7 owns the conjunction mechanics; it preserves the authority premise [L62].
4. **The defect is reinterpreted as the boundary** [L83, L144]. S0058's "`authorized` never consults the session" defect is re-read as "boundary-conforming behaviour … not a gap to fix but the boundary, realized as designed." This is a change over time; whether it is valid or post-hoc is open [I].
5. **Policy tests T-a…T-e** [L121–127]. BC-7 cannot answer policy, cannot create authority, and START validates form, not entitlement. The refutation attempt fails.
6. **Correction: "BC-7 does not execute approvals; humans do"** [L129]. BC-7 is a "registry of governed occurrence".
7. **Governance disambiguated** [L115–117]: (1) external Project Governance, the origin of policy, vs (2) the Governance *role* that writes through BC-7. Governance is upstream twice: policy flow vs operating flow.
8. **Adjudicated identity vs folded occurrence** [L162–165]. Knowledge truth is adjudicated (a human accepts a meaning); workflow truth is folded (a deterministic computation). "A fold is not an adjudication."
9. **Exclusions discovered** [L91–94]: authority evaluation, actor identity attestation, acceptance/closure decisions, time (`seq`, not wall-clock).
10. **Tension** [L180]: BC-7's store violates the estate's provenance discipline. The "different rules ⇒ boundary is real" inference is weak, because different rules may be a defect, not a boundary [I].
11. **I-5 is mechanised on "2 of 5 transition types"** [L65]. The vocabulary has 8 types [L48]; the denominator 5 is unexplained [I] (possible source defect).
12. **I-9 is absent from the invariant list** (I-1…I-10 with no I-9) [L59–69] [I].
13. **Census: 14 records vs the baseline's 8** [L36]. Not amended, because of V-E.
- *Lineage:* producer Fable 5 [L9]. It consumes S0062 and S0063 hypotheses and is endorsed by S0064. The S0063 hypothesis source and the S0064 endorser are the same reviewer: one line of support.

## S0071 — BC-7 domain model proposal (topic: authorized vs recorded)
1. **"BC-7 can refuse a *malformed* act. It can never refuse an *unauthorized* one"** [L293]. All domain policies DP-1…DP-7 are record-consistency rules [L282]: the tactical form of authorized vs recorded.
2. **Domain policy vs governance policy** [L268]. "What must always be true of the record?" vs "who may decide?". BC-7 holds the first and only preserves the second (GP-1…GP-4).
3. **Mutation ownership is only a fold projection** [L47–50, L71]: 0 stored occurrences, 9 references in the mechanism, observed being derived live. **T-1:** no derived projection is stored or accepted as input [L240, L208].
4. **Aggregate test rule** [L80]: "an invariant that spans several instances of a candidate proves the candidate is not the root." This is a necessary condition only [I]. Work Item passes; Assignment fails (I-1 is over the set; HANDOFF is atomic across two).
5. **One root or two** [L130–145]. No positive invariant spans transitions and grants. One root is kept because I-4 is a *negative* spanning invariant. L143 concedes that a split makes I-4 true by construction. The argument for one root is weak: an invariant satisfiable by the split model needs no holder [I]. The question stays open as OQ-9 [L353].
6. **Human Act rejected as aggregate** [L117–126]: no identity, no lifecycle in BC-7. "Importance is not ownership" [L218].
7. **T-2: a reference is never dereferenced** [L241]. **T-4: attribution is a claim, never an attestation** [L243]. "BC-7 records the claim; the attribution model adjudicates it" [L330].
8. **GrantScope must stay an opaque string** [L307]. A scope algebra would import governance semantics.
9. **Census** [L39–47]: 117 transitions = HANDOFF 34 + REGISTER 33 + START 32 + COMPLETE 16 + STOP 1 + CANCEL 1 (sum checked). recordedBy 83 + 33 + 1 = 117 (checked). 41 grants, all AUTHORIZED. humanAct on START 32/32, on COMPLETE 3/16.
10. **Declared-but-never-exercised vocabulary** [L42, L154, L349]: CONTINUATION 0, FAIL 0. "A concept with no instance is a claim, not a fact." The exit from sticky STOPPED has never run.
11. **Revocation is never observed** [L350, OQ-6]. All grants are AUTHORIZED: is authority withdrawable at all?
12. **Self-recorded START** [L11]. The producer wrote its own seq-3 START, and the estate has one `recordedBy: architecture` [L237, L354]. These sit in tension with I-5 "only Governance writes authority" [I].
13. **33 REGISTER / 32 START / 16 COMPLETE asymmetry** [L262]: about half of the lanes have no recorded completion (OQ-8).
14. **Domain events are recorded facts, not published events** [L249]: no subscriber exists.
- *Lineage:* producer Opus 5. Executes the S0064 L33–165 prompt and builds on S0067. Same day.

## S0164 — governance role/cost working log (topic: model separation, independence, correction chains)
1. **Independence must not be automated** [L369–402]. AI may output "CONFLICT DETECTED", never "INDEPENDENCE = TRUE": asymmetric decidability.
2. **Boundary invariants** [L262–280]: Author ≠ Independent Reviewer; Author ≠ Acceptance Authority; Governance ≠ Technical Design Authority; AI agent ≠ Governance Authority. Plus "Automation may reduce assurance cost but must never manufacture authority" [L709].
3. **Seven independence criteria** [L2059–2077], including no continuation/compaction/fork lineage and no inherited working context. "A different session ID alone is insufficient." Three-valued outcome INDEPENDENT / NOT INDEPENDENT / NOT ATTESTED; unknown lineage ⇒ NOT ATTESTED, never PASS.
4. **Shared-blind-spot argument for a different model** [L1100] (the V#2 author): the remaining defects "survived a same-model correction pass"; "about catch rate". Source of the S0057 recommendation.
5. **"Differential testing detects disagreement, never shared blindness"** [L1529]. A shared extractor "collapses the very independence the experiment measures" [L1406]. Independence of parsers vs interpretability of disagreement is a trade-off [L1417].
6. **Partial independence** [L1789]: "independent of the implementation — yes; independent of the commission — no." Subject-matter neutrality "is not establishable by anyone" when the subject is the role model [L1872].
7. **INDEPENDENT refused as an evidence dimension; REPRODUCIBLE substitutes** [L2420–2424]. Organizational independence is "unachievable in a single-operator estate". Evidence model: four orthogonal dimensions, SUBJECT · EPISTEMIC · PROVENANCE · REPRODUCIBILITY.
8. **Correction-chain economics** [L2736–2738]. The mechanical share of findings rose 29% → 46% → 58% (17 of 39). 4,315 lines, 5 amendments, 4 reviews, 0 implementation. Per-review denominators are not given, so I cannot recompute this [I].
9. **The checker has a blind spot** [L2742, X-5]. Trace resolution is deterministic, but act derivation is judgment. "It'll be trusted for the class it can't see."
10. **"The mandated verification caught it, not care"** [L2601]: the author re-introduced its own defect (DI-1). Argument for mechanical checks over author trust.
11. **Off-record work** [L2098–2125]. HANDOFF was refused because the producing lane was never STARTed; the record shows no ownership. Governance will not manufacture a START. A false "ALL PRECONDITIONS SATISFIED" output came from a truncated pipe and was withdrawn [L2129].
12. **Authorship by commit vs session namespace** [L1998–2007]: a category error was corrected. Transcript search is "self-contaminating"; only the originating tool_use is evidence.
13. **Assurance classes A–E by risk** [L420–427]. A no-findings gate comes before independent review [L507–540].
14. **E6 needs ≥2 independent governed acts** [L2242, L2435]. One instance cannot show a capability is owned rather than incidental. A criterion requiring history (E10) is unsatisfiable at establishment time [L2432].
- *Lineage:* composite scratch log [frontmatter L11] of pasted replies from several processes and models. It contains raw precursors of S0056 (L1076–1102) and of the Stage-2/Principal review interaction (L1119–1135). It is therefore not independent of S0056–S0065.

## S0913 — Step 25U, multi-agent epistemics
1. **Corroboration counts independent information, not agents** [L1511–1515], stated as the key result. AgentIndependence ≠ InformationIndependence [L250]. Common-source dependency ⇒ about 1 source [L274–282].
2. **Evidence independence graph G_D** [L290–310]. E₁ ⊥̸ E₂ when they share an ancestor.
3. **Likelihood-ratio example** [L1464–1500]. LR 19 × 15 = 285 only if independent (checked). The dependent combination is not given; only "must not automatically be 285" [I].
4. **Trust(a,d,c,t) is contextual** [L134]. Competence(a,q) per question type [L684]. A global trust score is rejected.
5. **Trust ≠ Authority; Expertise ≠ Authority** [L189–197]. "Trust never substitutes for authority": an architectural invariant [L1399–1403]. "Trust modifies epistemic weight; it does not create truth" [L1427].
6. **Capability ≠ Authority** [L1325–1345]. Epistemic permissions CanObserve / Assert / Derive / Approve / Execute are distinct [L1356–1372].
7. **Conflict is a first-class state, never silently erased** [L450–453]. Apparent, contextual and genuine conflict differ [L351–431]. Conflict may signal "ContextMissing" [L518].
8. **Resolution outcomes** [L471–488]: Confirmed(E₁), Confirmed(E₂), BothValid, BothInvalid, Unresolved. "Unresolved" is a valid terminal state [L1148] mapped to "Zero" [L1171].
9. **A resolution is a 6-tuple** [L1114–1122]: Conflict, Method, Evidence, Decision, Authority, ResidualUncertainty.
10. **Nine conflict types** [L1086–1097], including Model conflict vs Fact conflict [L1024–1030, L1065–1074].
11. **Authority supersedes for governance status; the superseded assertion stays historical evidence** [L631–637].
12. **Self-reinforcing reputation (FeedbackBias)** [L748–752]. Trust needs provenance and becomes a derived knowledge object [L760–804]. Quorum k is a domain policy, not an epistemic law [L928].
13. **EpistemicConflict ≠ ByzantineConsensus** [L958, L976]. EvidenceQuality ≠ NodeCount.
14. **The "falsification tests" A–H are asserted PASS with no execution and no model** [L1524–1616] [I]. The verdict "25U PASS" [L1656] is self-certified.
15. **Undefined dependencies** [L1179–1217]: "Zero" and "Lord" come from earlier steps. The conflict definition is binary (A vs ¬A), and the "both/neither" cases are not formalized [I].
- *Lineage:* the phase_measure_theory series (it cites 25N). Its vocabulary differs from S0056–S0071, but its principles mirror theirs: Trust≠Authority ≈ authorized-vs-recorded; agent independence ≈ model separation [I]. Different date (08-28) and genre, so possibly independent support. Shared-author lineage (Principal + AI) is not excluded.

---

## Cross-source table (theory-relevant convergences and tensions) [I]

| Theme | Sources | Note |
|---|---|---|
| Process ≠ model ≠ information independence | S0056 L25–29 · S0059 L21–30 · S0164 L1100, L1529, L2073 · S0913 L250, L1511 | S0913 gives the formal version (G_D). The others are case evidence from one estate. |
| Independence is undecidable / not attestable | S0056 L30 · S0058 L12 · S0164 L401, L1872, L2076, L2424 | Converges on: "conflict" can be detected; "independence" cannot be certified. |
| Authorized vs recorded | S0058 L86 (defect) → S0067 L83, L144 (re-read as boundary) → S0071 L293 · S0913 L1399 | Change over time: one observation re-read from defect to design. |
| Acceptance has a bounded meaning | S0060 L22–24 · S0061 L35–41 · S0065 L31–36 | Evidence acceptance ≠ object acceptance ≠ implied decisions. |
| Dropped or overridden caveats | S0058 L51 vs S0061 L19 · S0064 L5 vs S0065 L16 · S0064 vs S0065 "human" | Assurance-chain weaknesses not carried into acceptance. |
| Correction chains create defects of the same class | S0056 L117 · S0164 L2601, L2736–2738 | Termination rule: S0056 L109 (explanatory power ≠ defect). |

**Lineage summary:** S0056–S0065, S0067 and S0071 come from one estate, one day (2026-08-17) and one work-item chain, and are mutually citing. They are one line of support, with internal producer/verifier separation only. S0164 contains the raw precursors of S0056 and S0064/S0065, so it is not independent of them. S0913 is the only source outside the chain.

