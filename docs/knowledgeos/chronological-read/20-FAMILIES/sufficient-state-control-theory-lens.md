# sufficient-state-control-theory-lens

**Scope(s):** OBJECT · **Row count:** 14 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Astrom's stochastic state, Information-Timeliness Principle
**Aliases:** sufficient information for future probability distribution
**Candidate group membership (NOT an identity claim):**
- G0441: explicit agent-stated uncertainty (batch B0050) that this label POSSIBLY relates to `state-sufficiency-candidate-definition-285` — the note there records a later, still-not-yet-solved candidate definition of Sufficient(K,𝒪,ℐ): K is sufficient iff it can (1) evaluate every mandatory invariant, (2) determine operation legality, (3) execute every canonical transformation, (4) distinguish required state identities, (5) support required equality decisions, (6) support deterministic replay where required — paired with an information-theoretic reducibility test that an operation-legality decision must not silently depend on an undeclared external dependency. Relationship between that later candidate definition and this control-theory-derived lens is not decided here (P3 question).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Extraction from Karl Astrom's 'Introduction to Stochastic Control Theory' (a 150-page scanned source, read via rendered pages): the stochastic state is defined as the minimum information needed to predict the FUTURE PROBABILITY DISTRIBUTION (not the exact future), motivating a KnowledgeOS question of minimum sufficient knowledge/context for a governed decision; control performance depends on information availability/timeliness (delayed measurements degrade performance); the book's Observation->Estimator->Feedback diagram (p.8) is treated as evidence for an Observation->Evidence->State Estimation->Sufficient Knowledge State->Decision->Action->new-Observation closed loop, explicitly classified as a research hypothesis, not architecture."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0474 §"Astrom defines the stochastic state in terms of the minimum information needed to predict the future probability distribution, rather than the exact future. ... what is the minimum sufficient knowledge/context required to make a governed decision?"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0474 §"Astrom imposes regularity conditions such as finite variance and continuity assumptions. ... a very strong Zero Lens candidate: what conditions must hold before a model or inference is even legitimate?"]
- CANDIDATE-FORMAL-BIRTH: [S0474 §"The diagram on page 8 shows an environment/system producing an observed signal, an estimator reconstructing state, and feedback operating from that estimated state. ... Observation -> Evidence -> State Estimation -> Sufficient Knowledge State -> Decision -> Action -> new observation."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0474 §"this particular uploaded volume is much more foundational than its title might suggest ... does not appear to contain a large modern treatment of Kalman filtering or dynamic programming. ... I would not import concepts such as 'Kalman filter' or 'dynamic programming' into the extraction as though they were developed by this source."]

## Lifecycle
last_seen: S1311. Candidate lifecycle: DORMANT.
Evidence: `retracted_by` and `superseded_by` are empty and `contested_by_own_contradiction_type` is `false`. Per the task's own rule, this is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or confirmed ongoing status. The label's actual last-seen row (S1311) still treats the concept as live and load-bearing (it supplies "the missing criterion" for an open IMPLEMENTATION QUESTION), so DORMANT here reads as "not recently revisited" rather than "abandoned."

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0474, S0474, S1310, S1310, S1311 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0474, S0474 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1310 |
| dependencies | PRESENT | S0474 (×9), S0667, S1310 (×3), S1311 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0474, S0474, S0474, S1310, S1311 |
| examples | PRESENT | S0474 |
| warnings | PRESENT | S0474 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0474 |

## Rationale
The lens was extracted from Karl Åström's *Introduction to Stochastic Control Theory* specifically because Åström's definition of the stochastic state — the minimum information needed to predict the *future probability distribution*, not the exact future — reframes into a KnowledgeOS question of "what is the minimum sufficient knowledge/context required to make a governed decision?" [S0474]. The initial assessment explicitly frames this reframing, not any specific control algorithm, as the book's deepest potential contribution to KnowledgeOS, and supplies a triage table rating sub-concepts by import priority (Sufficient State/Information Timeliness/State Estimation rated Very-High/INVESTIGATE, down to Stochastic Calculus machinery rated Low/RECORD ONLY) [S0474].

Its later rationale is about *use*, not re-derivation: S1-F034 (S0667) proposes "sufficient state" as the most operational temporal concept in the corpus's temporal family, because it gives a principled answer-shape to a previously measured-but-unanswerable question (whether an authority grant was valid at a past point in time) [S0667]. S1310 sharpens this: forward-only history retains prior states of an aggregate's own members, but Authority is only referenced (not held) by the aggregate, so retention of aggregate history does not retain the grant's own validity interval — "sufficient state" and the earlier measured gap are named as the same hole approached from opposite ends [S1310]. S1310 also disputes a Session-1 claim of *independent* convergence between this lens and Freedman's statistical-decision-theory sufficient statistic, arguing both fields define adequacy relative to a decision "by construction," so the convergence reflects a shared disciplinary prior rather than independent corroboration [S1310]. S1311 closes the loop, using the sufficiency criterion to resolve unspecified detail in a companion finding (S1-F001) without changing its verdict, and explicitly flags the "two independent arrivals" claim as only *possible* independent arrival, since both routes pass through the same Session-1 extraction step [S1311].

`rationale_truncated_count` is 0, so no further rationale-bearing rows are reported as omitted.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (`assumption_register` is empty in the derived data for this label).

## All rows (source_id order)
- [S0474] DEFINITION/ARGUMENT, scope=OBJECT — State as sufficient information: minimum information to predict the future probability distribution, reframed as minimum sufficient knowledge/context for a governed decision. (anchor: "Astrom defines the stochastic state in terms of the minimum information needed to predict the future probability distribution...")
- [S0474] PRINCIPLE — Information-Timeliness Principle: performance depends on *when* information becomes available, not merely whether it exists; delayed measurements degrade performance. (anchor: "system performance to the information available when a control signal must be determined...")
- [S0474] FORMALIZATION — The book's page-8 observation→estimator→feedback diagram cited as evidence for a proposed Observation→Evidence→State Estimation→Sufficient Knowledge State→Decision→Action→new-observation loop, contrasted with a bare prompt→LLM→answer model. (anchor: "The diagram on page 8 shows an environment/system producing an observed signal...")
- [S0474] PRINCIPLE — Uncertainty (mean and covariance) is intrinsic to the state model itself, not after-the-fact metadata on a point estimate. (anchor: "the stochastic state models explicitly carry both mean and covariance...")
- [S0474] DISTINCTION, scope=THEORY-LEVEL — Multiple convergence notions (probability-one, in-probability, mean-square) motivate keeping KnowledgeOS assurance vocabulary (stable/converged/repeatable/assured) distinct rather than collapsed. (anchor: "convergence with probability one, convergence in probability, and mean-square convergence...")
- [S0474] CONCEPT — Model-validity regularity conditions (finite variance, continuity) offered as a strong Zero-Lens candidate for model-legitimacy prerequisites. (anchor: "Astrom imposes regularity conditions such as finite variance and continuity assumptions...")
- [S0474] WARNING/EXAMPLE, scope=THEORY-LEVEL — A finite/measurable computed quantity can exist even when the underlying system is unstable — a passing metric does not imply a valid system. (anchor: "an integral can exist even when the underlying dynamic system is unstable...")
- [S0474] LIMITATION/GOVERNANCE, scope=METHODOLOGICAL — Source-fidelity correction: this specific volume does not itself develop modern Kalman-filter/dynamic-programming treatment; those concepts must not be attributed to it. (anchor: "this particular uploaded volume is much more foundational than its title might suggest...")
- [S0474] FUTURE-RESEARCH/ANALYSIS, scope=THEORY-LEVEL — Frames maintaining a sufficient, uncertainty-aware knowledge state as more fundamental than any specific control algorithm; gives the import-priority classification table. (anchor: "What does it mean to maintain a sufficient, uncertainty-aware state of knowledge...")
- [S0667] CONCEPT/EXTENSION, scope=OBJECT — Introduces "sufficient state" as the most operational temporal concept in the corpus, a retention criterion for what must be kept so a governed decision remains possible; completeness PARTIAL (missing the sufficiency criterion itself); dependency S1-F001. (anchor: "uncertain environment -> observation -> sufficient state -> estimation -> decision -> action -> new observation...")
- [S1310] ANALYSIS/CORRECTION, scope=OBJECT — Names the sufficiency criterion precisely: Authority is a Reference held by the Authority context, not inside the referencing aggregate, so aggregate-history retention does not retain a grant's validity interval; does not change the earlier IMPLEMENTATION QUESTION verdict. Invariant recorded: "Authority is a Reference held by the Authority context, not stored inside the referencing aggregate." (anchor: "Retaining every prior state of the aggregate therefore does not retain the grant's validity interval...")
- [S1310] ARGUMENT/CORRECTION, scope=CROSS-OBJECT — Disputes a Session-1 "two independent sources" claim: Freedman's sufficient statistic and Åström's sufficient state are the same technical move in adjacent disciplines, both defining adequacy relative to a decision by construction, so the convergence reflects shared disciplinary prior, not independent evidence. Lineage: SOURCE-CLAIMED-CONTRADICTION of S1-F034's Session-1 independence claim. (anchor: "They are not independent. Freedman is statistical decision theory; Åström is stochastic control...")
- [S1310] DISTINCTION/RESTATEMENT, scope=METHODOLOGICAL — Discloses that "topology" is the reviewer's own analytical lens applied to Åström's material, not something Åström presents as the book's organizing theory — a lens-honesty discipline tied to the X-005 Zero-lens provenance issue. (anchor: "Topology is our analytical lens; it is not presented by Åström as the organizing theory of the book.")
- [S1311] RESTATEMENT/ANALYSIS, scope=CROSS-OBJECT, also labelled UNKNOWN-OBJECT-CANDIDATE — 2026-08-28 update: sufficient state supplies the missing criterion for a companion finding without changing its verdict (still IMPLEMENTATION QUESTION); records this as a POSSIBLE (not confirmed) independent arrival, since both routes pass through the same Session-1 extraction. (anchor: "Convergence note: two routes to one hole — S1-F001 measured that the question is unanswerable; S1-F034 states what would make it answerable.")

## Notes for P3
- `files_touching` lists S0475, S0476, S0477 in addition to the source_ids that actually appear in `family.rows` (S0474, S0667, S1310, S1311) — the same files_touching-vs-rows mismatch pattern I flagged for other labels in this batch. Possibly these three are adjacent extraction files from the same Åström-reading session with no distinct extractable statement under this label; worth a P3 check.
- G0441's linked label (`state-sufficiency-candidate-definition-285`, batch B0050) proposes a formal, still-unsolved Sufficient(K,𝒪,ℐ) definition with six explicit conditions. This label's own rows (especially S1310's naming of the sufficiency criterion via the Authority-as-Reference invariant) look like a concrete instance that a later formal Sufficient(K,𝒪,ℐ) definition would need to explain — but per R5/R12 this is only a candidate-group link, not an identity claim, and is flagged here for P3 to examine.
- The rationale rows repeatedly self-police against overclaiming (S0474's Kalman-filter/dynamic-programming disclaimer; S1310's topology-is-our-lens-not-Åström's disclaimer; S1310's "not independent, just same disciplinary prior" argument against a convergence claim). This label is a good example of a lens that stayed epistemically disciplined about its own scope throughout its row history — noted here as my own observation, not a claim made by any single source row.
