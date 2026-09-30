# explicit-implicit-knowledge-distinction

**Scope(s):** THEORY-LEVEL · **Row count:** 9 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Cn_S(K^exp), K^exp, K^imp · **Aliases:** explicit vs implicit knowledge
**Candidate group membership (NOT an identity claim):**
- G1829: [`explicit-implicit-knowledge-distinction` · `kr-hr-fr-series-theory-additions`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0061 · scope THEORY-LEVEL: Candidate KnowledgeOS distinction between explicitly represented knowledge and implicit knowledge entailed under a selected reasoning semantics, adapted from Brachman & Levesque's KB/entailment framing.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2520 §"the KB contains explicitly given beliefs, while entailments are implicitly given beliefs"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2520 §"Table: what I would actually add to KnowledgeOS Theory v1.3 (T1-T5) vs What I would NOT adopt"]

## Lifecycle
last_seen: S2573. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (ACTIVE) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S2520, S2565 |
| formal_definition | PRESENT | S2569 |
| type_signature | PRESENT | S2520, S2565, S2569 |
| invariants | PRESENT | S2521, S2565 |
| dependencies | PRESENT | S2520, S2565, S2568, S2569, S2573 |
| assumptions | PRESENT | S2520 |
| semantics | PRESENT | S2521, S2565, S2568, S2573 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| explicit and implicit knowledge may live in different representational spaces | EXPLICIT | S2520 | "although we must be careful because the two may live in different representational spaces" |

## All rows (source_id order)
- [S2520] types=[DEFINITION, EXTENSION] scope=THEORY-LEVEL — "Candidate KnowledgeOS distinction: K^exp (explicit, represented) vs K^imp = Cn_S(K^exp) (implicit, entailed under a selected reasoning semantics S), with K^exp subseteq K^imp conceptually (though possibly in different representational spaces)." (anchor: "the KB contains explicitly given beliefs, while entailments are implicitly given beliefs")
- [S2520] types=[GOVERNANCE] scope=THEORY-LEVEL — "Recorded editorial recommendation: five candidate theoretical commitments (T1 explicit/implicit knowledge, T2 reasoning is semantics-dependent Cn_S(K), T3 evaluation may consume implicit knowledge Eval_c(Cn_S(K),r,Gamma), T4 state transition needs persistence semantics Succ_S, T5 explanation is not determination) proposed for Theory v1.3 as strong candidates, none yet kernel-promoted; explicitly rejects Sat=FOL-entailment, Zero=CWA, Zero=Delta=empty universally, Boundary=frame axioms, delta=situation calculus, Identity=unique-names+domain-closure, DL=Boundary-taxonomy, mandatory default reasoning, FOL-as-representation-language, and YES/NO/UNKNOWN-sufficiency." (anchor: "Table: what I would actually add to KnowledgeOS Theory v1.3 (T1-T5) vs What I would NOT adopt")
- [S2521] types=[PRINCIPLE] scope=THEORY-LEVEL — "Reasoning semantics must be declared: there is no single undifferentiated 'reasoning' operation in KnowledgeOS (may be classical, rule-based, defeasible, probabilistic, taxonomic); a derived conclusion must preserve which regime S produced it, and KnowledgeOS must not silently equate stored=derived=entailed=determined=true — these are different epistemic statuses. Grounded in the KR&R source's separation of the knowledge level from the symbol/implementation level." (anchor: "Cn_{S_1}(K)\neq Cn_{S_2}(K) may hold even when K^{E} is identical... reasoning regime is part of the semantic context of a derivation")
- [S2565] types=[DEFINITION, EXTENSION] scope=OBJECT — "FR-1 (section 2): defines K_t^exp (explicit) and K_t^der,S = Cn_S(K_t^exp) (derived under reasoning system S) as distinct conceptual layers; states K^exp != K^der and that derivability does not imply operational availability, so K^der,S != K^available,S. Grounded in the Handbook's distinction between explicit beliefs and logical-closure consequences, and the problem of logical omniscience. Status: STRONG." (anchor: "Add Explicit vs Derived Knowledge ... K_t^{exp} and K_t^{der,S} with K_t^{der,S}=Cn_S(K_t^{exp}) ... K^{exp}!=K^{der} and Derivability does not imply operational availability. Therefore K^{der,S} != K^{available,S}.")
- [S2565] types=[PRINCIPLE, DISTINCTION, EXTENSION] scope=THEORY-LEVEL — "FR-4 (section 5): distinguishes Cn_S(K) (all consequences) from Avail_S(K,B) (what a bounded reasoning process can actually compute), with Avail_S(K,B) subseteq Cn_S(K), giving a three-way distinction Explicit != Derivable != Available; a proposition can be derivable-but-unavailable, which is a computational-boundary phenomenon, explicitly NOT the same kind of UNKNOWN as absence of representation. Flagged as feeding the future Zero/Evaluation model." (anchor: "Implement Computationally Bounded Knowledge ... Avail_S(K,B) subseteq Cn_S(K) ... Explicit != Derivable != Available ... This is not UNKNOWN in the same sense as absence of representation. It is a computational boundary.")
- [S2565] types=[DISTINCTION, EXTENSION] scope=THEORY-LEVEL — "Section 9 (feeding FR-3/FR-4): distinguishes six typed causes of epistemic failure-to-derive -- U_rep (not represented), U_der (not derivable under S), U_avail (derivable but resource-unavailable), U_evid (insufficient evidence), U_eval (evaluation cannot determine), U_scope (outside declared scope) -- explicitly refusing to flatten them into one enum, per prior FDE/Zero experiments, and reaffirming the structured EVal=(Value,Reason,Boundary,...) direction as better than a flat value set. Grounded in the Handbook's observation that incomplete reasoning and incomplete knowledge can have different causes." (anchor: "Implement 'failure to derive' as a typed epistemic distinction ... U_rep, U_der, U_avail, U_evid, U_eval, U_scope ... We do not need to make these six values of one enum ... we should not flatten them. Instead EVal=(Value,Reason,Boundary,...) remains the better direction.")
- [S2568] types=[RESTATEMENT] scope=THEORY-LEVEL — "Part 1 formalizes FR-1 through FR-4 with explicit [STRONG] status labels and 'Promote to theory principle/invariant' actions: FR-1 K^exp != K^der; FR-2 Derive_S(K,q) with S=(L,Sigma,R,Sem,B); FR-3 Query != Reasoning != Evaluation != Determination with Sat != ASK, Sat != Truth, Determination != Truth; FR-4 Avail_S(K,B) subseteq Cn_S(K), Explicit != Derivable != Available." (anchor: "Part 1: The Foundational Epistemic Pipeline -- FR-1 Representation Separation [STRONG], FR-2 Reasoning Relativity [STRONG], FR-3 Epistemic Processing Separation [STRONG], FR-4 Bounded Derivation [STRONG]")
- [S2569] types=[DEFINITION, EXTENSION] scope=OBJECT — "Adds D-1.3 Explicit-vs-Implicit Knowledge to the R_req inventory (Tier P1): Explicit(phi) iff phi in K; Implicit(phi) iff phi not-in K and K entails phi; explicit lookup is O(1), implicit deduction is NP-hard in general -- connecting this batch's FR-1/K^exp-K^der distinction into the R_req formal distinction inventory with a stated complexity class." (anchor: "D-1.3 Explicit vs. Implicit Knowledge ... Explicit(phi) iff phi in K; Implicit(phi) iff phi not-in K and K entails phi. Tractability: O(1) for explicit; NP-hard in general for implicit deduction.")
- [S2573] types=[RESTATEMENT, VALIDATION] scope=THEORY-LEVEL — "Notes that the reviewed report consolidates eight distinctions already independently discovered elsewhere in the KnowledgeOS research program (TRUE!=BELIEVED!=KNOWN, Explicit!=Implicit, Stored!=Derived!=Entailed, Observed!=Inferred!=Reported, NoEvidence!=NotAssessed, Unresolved!=False, NoKnownGap!=Complete, Representation!=Reality) as Tier-1 corpus distinctions, positioning R_req as a unifying framework rather than an isolated new idea." (anchor: "What the research has also confirmed: TRUE != BELIEVED != KNOWN, Explicit != Implicit, Stored != Derived != Entailed, Observed != Inferred != Reported, NoEvidence != NotAssessed, Unresolved != False, NoKnownGap != Complete, Representation != Reality. The report explicitly lists these as Tier-1 corpus distinctions ... R_req is becoming a unifying framework for distinctions discovered across the KnowledgeOS research program.")

## Notes for P3
(none beyond what is captured above)
