# action-rationale-and-why-act-factfinding

**Scope(s):** OBJECT · **Row count:** 57 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ActionRationale`; `Determine(H) not-implies Select(Action)`; `KR-ACTION-FACTFINDING-2026-09`; `NoAction != NoDecision`
**Aliases:** "Gita Chapter 3 -- from fact-finding to a justified reason for action"
**Candidate group membership (NOT an identity claim):**
- G1889: links this to `kr-epistemic-agency-2026-09-formal-specification` — labels co-occur in the same contribution's labels[] 5 separate times across the corpus.
- G1890: links this to `knowledgeos-research-ledger-2026-09-artifact-status-matrix` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus.
- G1894: links this to `epistemic-value-anthology-lens-2026-09` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0068, scope OBJECT: "A structural (non-doctrinal) reading of Gita Chapter 3 proposing a second kind of fact-finding -- 'Action fact-finding' (why act, why this action) distinct from 'State fact-finding' (what is happening) -- introducing an ActionRationale object, the invariant Determine(H) does not imply Select(Action) and its converse, NoAction != NoDecision, and Action != ActorClaim / ObservedAction != CausalAttribution. Proposes KR-ACTION-FACTFINDING-2026-09 as the next artifact after KR-ZOOM-FACTFINDING, bridging to kr-epistemic-agency-2026-09-formal-specification."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2842 §"ActionRecommendation -> Challenge -> Clarification before execution. ... Can a proposed action itself trigger further fact-finding?"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S2842 §"ActionRecommendation -> Challenge -> Clarification before execution. ... Can a proposed action itself trigger further fact-finding?"]
- CANDIDATE-FORMAL-BIRTH: [S2842 §"ActionRationale containing: Inquiry, Observation, Relevant dimensions, Hypotheses, Evidence, Assessment, Determination, Candidate action, Mechanism/causal rationale, Expected consequence, Constraints, Authorization. ... Action -> Mechanism -> Expected consequence -> Goal/requirement."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2842 §"I would explicitly reject the earlier simplistic statement that 'Knowledge without action is incomplete.' ... that philosophical claim should not automatically become a KnowledgeOS invariant. The stronger research result is the structure of inquiry around action."]

## Lifecycle

last_seen: S2868. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a heuristic based on how recently (by source_id) this label was last used (last_seen: S2868), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2863 (×3) |
| informal_meaning | PRESENT | S2844, S2859 |
| formal_definition | PRESENT | S2842, S2843, S2844, S2855, S2863 (×3), S2866, S2868 (×2) |
| type_signature | PRESENT | S2844, S2859, S2868 |
| invariants | PRESENT | S2842 (×4), S2843 (×3), S2844 (×5), S2845, S2847, S2848, S2859 (×5) |
| dependencies | PRESENT | S2842 (×4), S2843 (×3), S2844, S2845 (×2), S2847, S2848, S2853, S2855, S2859 (×2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2842 (×4), S2843 (×4), S2844 (×3), S2847, S2848, S2855, S2859 (×2), S2863 (×5), S2868 (×3) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2842 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S2842 (×2) |

## Rationale

Argues that fact-finding can succeed (evidence gathered: Runner=50GB/day, Backup=15GB/day, Replication=5GB/day) while determination fails (Cause(Egress) remains ambiguous between {Runner, Backup} due to insufficient discrimination), strengthening the "Case-B witness" in the associated experiment [S2863]. Reads Chapter 3.27-28 (action attributed to the gunas of prakrti rather than the Self as sole doer) not as literal software agency but as raising a provenance/attribution question: given an action (e.g. "restart Nexus runner"), KnowledgeOS must be able to attribute its cause among Human/Policy/Agent/Scheduler/EpistemicRecommendation/Automation/ExternalEvent, yielding the invariant ActionOccurrence≠ActionAttribution, said to be directly compatible with existing provenance work [S2863]. States the research hypothesis "fact-finding is not merely discovering facts," decomposing it into ten activities of which the last three (sufficiency-for-action assessment, selecting/authorizing action, observing consequence) are explicitly excluded from Fact-Finding proper and classified as the downstream action boundary [S2863]. This closes a gap the thread identifies explicitly at its outset: prior fact-finding work (State Fact-Finding, "what is happening") had no counterpart for the distinct epistemic problem of "why act" — the label's own governing distinction, restated across its documents, is that knowing what is the case is not the same epistemic problem as determining what to do about it.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

57 rows across fourteen source documents, tracing one research thread from its Gita Chapter 3 origin through formalization, correction, cross-validation from other lenses, a major schema-correction pass, a deeper Chapter 3 re-read, and final candidate consolidation. Every row is accounted for exactly once.

**Phase 1 — Initial extraction from Gita Chapter 3 (S2842, 9 rows)**

Reads Arjuna's challenge to a proposed action (rather than simply executing it) as motivating a candidate pattern ActionRecommendation→Challenge→Clarification before execution; extracts from Chapter 3's rejection of mere abstention the structural distinction NoAction≠NoDecision, with inaction as itself a candidate action-state; proposes a candidate ActionRationale object (fields: Inquiry, Observation, relevant dimensions, hypotheses, evidence, assessment, determination, candidate action, mechanism/causal rationale, expected consequence, constraints, authorization); extracts from Chapter 3's example-setting/systemic-consequence argument the candidate distinction Action Evaluation≠Individual Utility Only; extracts from Chapter 3's actor/process distinction (verses 27-28, false-ego "I am the doer" vs. understanding underlying processes) the structural abstraction Action≠ActorClaim; distinguishes Type A state fact-finding (what is happening) from Type B action fact-finding (why act, what causes it, what can be changed); proposes the paired candidate invariants Determine(H) does not imply Select(Action) and its converse, illustrated by three cases; explicitly rejects adopting the source's own philosophical claim ("knowledge without action is incomplete") as an automatic KnowledgeOS invariant, insisting the structural extraction (the inquiry-around-action pattern) is the real result; and proposes the next research artifact KR-ACTION-FACTFINDING-2026-09.

**Phase 2 — Formalizing the Dual Fact-Finding Architecture (S2843, 8 rows)**

Formalizes the Dual Fact-Finding Architecture (Type A: State Fact-Finding, output Determination F_t, operational space Hypothesis Space H_Q, failure mode Underdetermination; Type B: parallel structure for action); states two non-equivalence invariants for Type B — Determination(H) does not imply Select(Action), and non-Determination(H) does not imply NoAction; fixes a notation collision (the new ActionRationale object had been named R_t, colliding with the established R_t=ReasoningRegime; renamed AR_t); corrects ActionRationale to not presuppose the action is already justified (AR_t is a rationale under evaluation, only Evaluate(AR_t) produces a warrant verdict); corrects the bare "Action→Mechanism→State Transition" field (which cannot itself establish causality) to a provenance-bearing causal_model=(Mechanism,Assumptions,Evidence,Alternatives); rejects assuming additive utility composition U(a)=U_local(a)+U_systemic(a), requiring a general composition function instead; proposes StateDetermination≠ActionWarrant as a candidate research-status invariant one level earlier than Determine(H)≠Select(Action), yielding the full separation chain F_t≠AR_t≠Decision_t; and states the deepest candidate result of the combined Chapter-2/Chapter-3 thread — knowing what is the case is not the same epistemic problem as determining what to do about it.

**Phase 3 — Five-stage chain and schema corrections (S2844, 8 rows)**

Consolidates KR-ACTION-FACTFINDING-2026-09 into a five-stage non-equivalence chain F_t≠W_t≠Decision_t≠Authorization_t≠Action_t and a three-layer architecture; corrects the ambiguous notation "~Determine(H)≠NoAction" to the intended implication not-Determine(H) does not imply NoAction, adding the converse; corrects the schema's requirement that every candidate action space include Mitigate (too strong/domain-dependent) to a two-part structure A_Q=A_Q^domain∪A_Q^epistemic; corrects the ActionRationale JSON schema's mandatory `proposed_action` field (biasing toward an already-selected intervention) to optional; corrects the schema's mandatory numeric `calculated_eu` field (silently assuming a scalar expected utility) to optional alongside an explicit utility_regime; formally defines the previously-undefined "Action Warrant" as a contract-relative predicate Warrant(a|K,Q,C,S,R); adds three further distinctions, including CausalModel≠CausalDetermination; and states the correct closing kernel-minimality question for the whole thread — whether ActionRationale adds a necessary, observable capability beyond composition of existing operations.

**Phase 4 — Filling ledger equations (S2845, 2 rows)**

Fills in the ledger's blank Five-Stage Non-Equivalence Chain equations and Action Non-Derivability equations; requires the Type-B ActionRationale function to take State Determination F_t as an explicit input, AR_t=f(F_t,A_Q,K_t,Q_t,C_t,S_t,R_t), with the constraint TypeB not-equivalent Reconstruct(TypeA).

**Phase 5 — Cross-validation from other lenses (S2847, S2848, S2853, S2855, 4 rows)**

- [S2847] Extracts Jones's evidentialist/pragmatist question of which goods can motivate belief (versus motivating action to acquire a belief), giving the three-way separation Reason to Believe≠Reason to Act≠Reason to Inquire.
- [S2848] States the deepest single result of the whole Epistemic Value lens as a five-way separation principle: "what was reached"≠"how it was reached"≠"what it enables us to understand"≠"why it is valuable"≠a fifth dimension.
- [S2853] Extracts the thesis's identification of "why" questions (concerning reasons for behavior) as external support connecting to the corpus's Action Fact-Finding work, via a What?→How?→Why?→Why-act? hierarchy.
- [S2855] Finalizes the Four-Way Epistemic Separation (Epistemic State K_t ≠ Epistemic Quality Q_epi ≠ Epistemic Value V_epi ≠ Practical Utility U_practical) into a table pairing each domain with its primary question.

**Phase 6 — Major schema-correction pass on ActionRationale (S2859, 10 rows)**

Identifies the biggest architectural issue in the ActionRationale schema: AR_t simultaneously performs Rationale Construction + Action Evaluation + Warrant Assessment, risking semantic collapse; reaffirms and sharpens that `proposed_action` must not be mandatory, since AR_t does not imply the existence of a selected action; reaffirms the action space must not have universally mandatory members, correcting the prior spec's unconditional {a1,...,NoOp,Wait,InvestigateFurther,Mitigate} requirement; identifies the most important remaining mathematical problem — mandatory scalar fields (u_local, u_systemic, calculated_eu) still assume scalar utility despite the declared non-additive model; corrects the still-undefined Action Warrant, giving it a candidate formal interface W_t=Warrant(AR_t,K_t,Q_t,C_t,S_t,R_t,EC_t) with output in {Warranted, NotWarranted, Underdetermined, Blocked}; corrects the untyped `counterfactual_status:string` field to a candidate closed vocabulary; reaffirms E^state≠E^action (evidence establishing a cause need not establish that acting on it is safe, authorized, effective, or non-disruptive), illustrated by "should the GitLab Runner be restarted?"; requires Authorization to remain strictly downstream of Decision (AR_t→Decision_t→Authorization_t, never AR_t→Authorization_t directly), preserving the governance boundary; finalizes the expanded non-equivalence chain F_t≠AR_t≠W_t≠Decision_t≠Authorization_t≠Action_t as "probably the cleanest downstream semantic chain so far"; and restates the correct kernel-minimality closing question for the whole thread.

**Phase 7 — Deeper Chapter 3 re-read (S2863, 9 rows)**

Reads Chapter 3.4-3.8 (action is unavoidable) as supporting a policy-mediated model Determine→ActionPolicy rather than a universal Determine→Action law; argues fact-finding can succeed while determination fails (the Runner/Backup/Replication egress example), strengthening the "Case-B witness"; reads Chapter 3.19-3.20 (act without attachment to fruits; action tied to social/order considerations) structurally, explicitly declining to import the religious norm as a KnowledgeOS rule; reads Chapter 3.27-28 (action attributed to the gunas of prakrti rather than the Self as sole doer) as raising a provenance/attribution question, yielding ActionOccurrence≠ActionAttribution; combines the Chapter 2 pattern (Crisis→Diagnosis→Dimension Expansion→Reframing→Understanding) and the Chapter 3 pattern (Question→Clarification→Duty/Action determination→Action) into a unified arc; introduces Action Grounding as a concept distinct from Fact-Finding (Fact-Finding asks "what is the case," Action Grounding asks "given what is established, why is this action justified"); states the research hypothesis "fact-finding is not merely discovering facts," decomposing it into ten activities, the last three explicitly excluded from Fact-Finding proper; makes the governance decision that the frozen/active KR-ZOOM-FACTFINDING-01 experiment (scoped to ZF2+ZF3+ZF5) must not be modified in light of the Chapter 3 material, deferring new hypotheses; and proposes the new downstream research artifact KR-FACTFINDING-ACTION-2026-09 with an eight-stage candidate decomposition.

**Phase 8 — External architectural cross-check (S2864, 1 row)**

Reads the GoF Command pattern (encapsulating a request as a parameterizable, queueable, loggable, undoable object) as architectural support (not proof) for the existing Decision≠Authorization≠Execution separation.

**Phase 9 — Candidate consolidation (S2866, S2867, S2868, 6 rows)**

- [S2866] Lists the eight new Chapter-3-derived candidates (A5-A12, dated 2026-09-07): the Type A/B fact-finding split, Determination(H) not-implies Select(Action), the T4-corroboration Epistemic≠Operational finding, and others.
- [S2867] Summarizes the new Chapter 2 candidates (inquiry revision, MisframedInquiry Zero class, contract-relative completion) and Chapter 3 candidates (Type A/B fact-finding, Determination(H) not-implies Select(Action), and others).
- [S2868] Details candidates A5-A6: the Type A (state) vs Type B (action) fact-finding distinction, rated a full layer, and the invariant that a determination outcome (even Determined) does not by itself select an action; explains why A7 (Epistemic determination≠Operational readiness, Determine→ActionPolicy) is classified T4-corroboration rather than a fresh contribution, since the corpus already owns the five-cut K_t→Zero→Decision chain; details candidates A8-A12 (NoAction≠NoDecision as a first-class action-state; the new Warrant(a|K,Q,C,S,R) object, four-valued, contract-relative over seven requirement families; and others); and gives the explicit four-way type distinction establishing Warrant as a genuinely new concept — Det (discrete epistemic stance), EU (utility model over feasible actions), Warrant (contract-relative adequacy judgment), and a fourth type.

## Notes for P3

- This label's own evidence shows an unusually disciplined, multi-pass correction cycle on a single formal object (ActionRationale/AR_t): Phase 2-3 propose and formalize it, Phase 6 (S2859) then identifies its "biggest architectural issue" (semantic collapse across three responsibilities) and systematically corrects six separate schema defects (mandatory proposed_action, mandatory action-space members, scalar utility fields, undefined Warrant, untyped counterfactual_status, Authorization ordering). P3 should treat S2859 as the authoritative post-correction state of the schema rather than the earlier S2842-S2844 drafts.
- The three candidate groups (G1889, G1890, G1894) point to three different kinds of relationship worth distinguishing in P3: G1889 (5 co-occurrences with `kr-epistemic-agency-2026-09-formal-specification`) looks like a direct downstream-artifact relationship (this thread explicitly proposes bridging there); G1890 (research ledger artifact-status matrix) is likely a tracking/bookkeeping relationship rather than a conceptual one; G1894 (epistemic-value-anthology-lens) is the source of this label's Phase 5 cross-validation rows (S2847, S2848, S2855).
- The label deliberately and repeatedly declines to import the Gita's own normative/religious content as KnowledgeOS rules (Phase 1's explicit rejection of "knowledge without action is incomplete" as an automatic invariant; Phase 7's explicit declining to import the "act without attachment to fruits" norm) while still extracting structural distinctions from the same verses — this discipline is stated consistently across both the earliest and latest documents in the thread and looks like a stable methodological commitment worth preserving as-is in any P3 write-up.
- No internal tension or contested-lifecycle discrepancy was found in this label's own 57 rows; the evidentiary base is unusually well-organized for its size, with nearly every correction explicitly tagged CORRECTION and referencing what it corrects.
