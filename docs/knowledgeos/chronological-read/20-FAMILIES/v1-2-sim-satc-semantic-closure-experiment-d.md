# v1-2-sim-satc-semantic-closure-experiment-d

**Scope(s):** `OBJECT` · **Row count:** 16 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A-1`, `A-2`, `KR-SIM-2026-09-02-D`, `PB-2..PB-5`, `Zero_reasoned` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0817**: [`v1-2-sim-satc-semantic-closure-experiment-d` · `zero-reasoned-closure-reading`] — labels share the notation 'Zero_reasoned'
- **G1789**: [`gap-theory-v1-delta-set-definition` · `v1-2-sim-satc-semantic-closure-experiment-d`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0058, scope OBJECT): The Sat_c Semantic Closure Experiment: specifying (not implementing) the eight Sat_c classes already exposes that only 3 of 8 (content, evidence, provenance) are executable -- status/consistency/governance/temporal/operational are blocked by named missing evaluators (A-1) -- and that no class in the family requires factivity at all, so a state can satisfy all eight and still be false (A-2), locating CE-1's cause in the satisfaction family's structure rather than in Gamma. Adversarial testing then finds Sat_content is not total under contradiction (PB-2), the declared U-reason vocabulary is incomplete (PB-3), blocked-class contagion makes every composite requirement of arity>=4 permanently U (PB-4), and operational satisfaction is not provably well-founded (PB-5). Introduces a fourth Zero reading, Zero_reasoned (closes on non-agent-remediable U only), and the derived chain Zero_strict => Zero_reasoned => Zero_weak.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2405] §"U ├── epistemic U ... ├── theory U ├── NO_EVALUATOR ├── NO_ORDERING ... └── world U └── UNOBSERVABLE ... That is potentially more important than the eight classes themselves."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2405] §"U ├── epistemic U ... ├── theory U ├── NO_EVALUATOR ├── NO_ORDERING ... └── world U └── UNOBSERVABLE ... That is potentially more important than the eight classes themselves."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2405] §"I would rename the next experiment: Sat_c Specification Repair & Evaluator Completion Experiment ... Gate 1 -- Repair, without implementation ... Gate 5 -- Ask the decisive question"

## Lifecycle
last_seen: `S2519`. Candidate lifecycle: **CONTESTED**.
Evidence: contested_by_own_contradiction_type=True.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2405, S2405 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2405, S2517 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2509, S2517, S2519 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2408, S2408, S2509 |
| examples | PRESENT | S2407, S2407 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2407, S2407, S2407, S2436 |
| open_questions | PRESENT | S2405 |

## Rationale
Endorses A-2 as the strongest finding of the run, restating it more precisely: the defect is structural (no attachment point for truth in the satisfaction family), not an accident of any particular evaluator [S2405]. Reframes PB-4 (blocked-class contagion) as evidence that Kleene conjunction is working correctly by faithfully propagating unresolved semantics, not as a defect of the composition rule [S2405].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2405]` types=[EXTENSION, FORMALIZATION] scope=OBJECT — "Proposes a three-branch taxonomy of the value U itself -- epistemic U (agent doesn't know), theory U (no evaluator exists), world U (unobservable) -- judged potentially more important than the eight requirement classes." (anchor: "U ├── epistemic U ... ├── theory U ├── NO_EVALUATOR ├── NO_ORDERING ... └── world U └── UNOBSERVABLE ... That is potentially more important than the eight classes themselves.")
- `[S2405]` types=[ANALYSIS] scope=OBJECT — "Endorses A-2 as the strongest finding of the run, restating it more precisely: the defect is structural (no attachment point for truth in the satisfaction family), not an accident of any particular evaluator." (anchor: "the satisfaction family has no place to attach truth. ... If KnowledgeOS intends satisfaction to support a truth-bearing notion of knowledge, the current satisfaction family contains no semantic at...")
- `[S2405]` types=[ANALYSIS] scope=OBJECT — "Reframes PB-4 (blocked-class contagion) as evidence that Kleene conjunction is working correctly by faithfully propagating unresolved semantics, not as a defect of the composition rule." (anchor: "PB-4 is probably the most important operational result ... more requirements -> more class evaluations -> more opportunities for blocked evaluation -> U propagation -> closure becomes impossible. ....")
- `[S2405]` types=[GOVERNANCE, FUTURE-RESEARCH] scope=METHODOLOGICAL — "Recommends a five-gate 'Sat_c Specification Repair & Evaluator Completion Experiment' sequencing PB-2/PB-3/PB-5 repair before candidate governance/temporal/operational evaluators are introduced, keeping the eventual Zero choice a theory/governance decision." (anchor: "I would rename the next experiment: Sat_c Specification Repair & Evaluator Completion Experiment ... Gate 1 -- Repair, without implementation ... Gate 5 -- Ask the decisive question")
- `[S2407]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "A-1: specifying (not implementing) the eight Sat_c classes reveals only 3 (content, evidence, provenance) are executable; status/consistency/governance/temporal/operational are each blocked by a named specific missing evaluator or undefined relation, explaining why E1 produced permanent U for those classes." (anchor: "Only 3 of 8 classes are executable now ... status | Contr undefined; whether contradiction needs a 4th value is OPEN ... governance | no evaluator exists -- this is why every governance requirement...")
- `[S2407]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "A-2: none of the eight satisfaction classes carries any factivity requirement, so full satisfaction never entails truth -- a stronger, specification-level restatement of CE-1's mechanism." (anchor: "No class in the family requires factivity. ... A knowledge state can satisfy all eight classes and still be false. CE-1 is therefore not an accident of Gamma: the satisfaction family has no place t...")
- `[S2407]` types=[COUNTEREXAMPLE] scope=OBJECT — "PB-2: Sat_content is not total under contradiction (both p and not-p present), since the codomain has no value for that case; three repairs are possible but none is chosen." (anchor: "Sat_content returns top if p in Content(K) and bot if not-p in Content(K). Both can hold. The codomain {top,bot,U} has no value for it, so Sat_content is not a function on such states.")
- `[S2407]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "PB-4: under Kleene conjunction with 5 of 8 classes blocked, every composite requirement of arity 4 or higher is permanently U, making the satisfaction family effectively inert for realistic multi-class requirements." (anchor: "blocked-class contagion: the decisive result. Every composite requirement of arity >= 4 is permanently U, no matter how well the executable classes perform.")
- `[S2407]` types=[COUNTEREXAMPLE] scope=OBJECT — "C-3: the B1 factivity counterexample case is nevertheless declared closed under both Zero_weak and Zero_reasoned, establishing that epistemic closure and truth are orthogonal under the current candidate semantics." (anchor: "Zero closure holds on a state whose knowledge is false. ... Zero_weak = true, Zero_reasoned = true. Because no Sat_c requires factivity (A-2). ... Zero and truth are orthogonal.")
- `[S2408]` types=[RESTATEMENT] scope=OBJECT — "Confirms and restates the verdict that the Sat_c family is not semantically closed, keeping Sat a research-level candidate interface." (anchor: "Sat_c family is not semantically closed ... Sat: K x R -> {top,bot,U} remains a research-level candidate interface, not a canonical semantic contract.")
- `[S2408]` types=[RESTATEMENT] scope=OBJECT — "Restates the Zero-does-not-imply-truth counterexample (B1) as probably the most important conceptual result of the experiment, separating closure from truth." (anchor: "Zero does not imply truth. B1 has: false attribution, Zero_weak = true, Zero_reasoned = true. Therefore: Zero !=> Truth under the current theory. ... It separates: closure != truth")
- `[S2408]` types=[EXTENSION, HYPOTHESIS] scope=OBJECT — "Pivots the research object one level below Sat to Eval_c(K,r,Gamma)->EVal, registers hypothesis H-EVAL-01 (a requirement-relative evaluation mechanism preserving explanatory structure), and recommends discovering the value codomain experimentally rather than presupposing three values." (anchor: "(K_t, r, Gamma_t) --Eval_c--> EVal_c ... and we are explicitly not yet deciding what an evaluation value fundamentally is. ... H-EVAL-01 [PROP] ... V = {top,bot,U}")
- `[S2436]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "M-4: the derived implication chain Zero_strict => Zero_reasoned => Zero_weak was proved in two lines and machine-checked over 1,620,000 assignments (every choice of which reason-buckets close) with zero counterexamples, and is independent of the reason partition even though that partition has already been revised twice." (anchor: "M-4. The Zero chain is derived. Zero_strict ⇒ Zero_reasoned ⇒ Zero_weak, proved in two lines and machine-checked over 1 620 000 assignments ... 0 counterexamples. It is independent of the reason pa...")
- `[S2509]` types=[RESTATEMENT] scope=OBJECT — "Restates the v1.2 headline one-line result: three-valued Sat makes 'Zero iff Delta_t=empty' ambiguous, naming three predicates (Zero_strict, Zero_weak, Kleene) that disagree even on the best case (a determined, corroborated state is strict=false, weak=true, Kleene=U); Theory status B (partially executable); the three undetermined requirement classes (governance, temporal, operational) are exactly the classes v1.2 marks open." (anchor: "> `[EXP]` **Three-valued `Sat` makes v1.1's sentence "`Zero ⟺ Δ_t = ∅`" ambiguous: it names three predicates, and they disagree on the best case**")
- `[S2517]` types=[DEFINITION, CONTRADICTION] scope=OBJECT — "Maps the explicit/implicit belief distinction directly onto Sat(K_t,r) iff K_t entails Content(r), and Zero onto a complete-and-consistent ('vivid') knowledge base under the closed-world assumption -- this identification is in unreconciled tension with the main research lane's actual v1.2 finding that Sat is semantically incoherent under the theory's own model, with only 3 of 8 Sat_c classes executable." (anchor: "| **Explicit** | Directly represented in KB | Stored sentences |
| **Implicit** | Entailed by explicit beliefs | Computed via reasoning |

\[
\text{Sat}(K_t, r) \iff K_t \models \text{Content}(r)
\]")
- `[S2519]` types=[CORRECTION, CONTRADICTION] scope=OBJECT — "Explicitly REJECTS ten over-strong translations from the Brachman & Levesque source, most notably Sat=FOL-entailment (too strong, evaluation has more dimensions) and Zero=CWA (contradicts Unknown≠Absent and NoEvidence≠EvidenceOfAbsence) -- this directly corrects the immediately preceding extraction in the same thread (S2517), which had proposed exactly these two identifications as confirmations of KnowledgeOS concepts; also rejects Zero=Delta-empty-universally (different requirement universes produce different meanings), Boundary=FrameAxiom, delta=SituationCalculus, Identity=UniqueNames+DomainClosure, DL=BoundaryTaxonomy, mandatory default reasoning, FOL as the representation language, and flat YES/NO/UNKNOWN evaluation (already refuted by the Contr research)." (anchor: "| `Sat = FOL entailment` | ❌ Reject | Too strong; evaluation has more dimensions |
| `Zero = CWA` | ❌ Reject | Unknown ≠ Absent; NoEvidence ≠ EvidenceOfAbsence |
| `Zero = Δ = ∅` universally | ❌ Re...")

## Notes for P3
- Lifecycle heuristic flagged CONTESTED — worth priority attention in P3 to determine whether the internal contradiction reflects genuine revision-in-progress or a labeling/scope error.
- This label carries 2 candidate-group memberships beyond G0759 (see group list above) — a comparatively dense set of mechanical cross-links, which may make it a useful anchor point for P3 reconciliation, but none of these links are identity claims and each must be assessed on its own evidence.
