# step169-law-falsification-pass

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `L1..L8 attacked -> Laws A..H refined`
**Aliases:** "falsification of the eight candidate laws"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 169's falsification attempt against the Step-168 L1-L8 laws, each refined rather than simply confirmed or rejected: L1 refined to 'AIOutput ALONE does not imply KnowledgeEstablished' (the word 'alone' matters -- AI can participate in knowledge production but cannot bypass the establishment rule); L2 refined from 'always different' to the logical claim 'capability does not imply authority' (they may coincide in a minimal system by design, but that is an added constraint, not an identity); L3 refined to Execution does not imply Correctness or Verification; L4 (Unknown!=False) survives as 'foundational logic' and is strengthened into a three-valued TruthState(P) in {True,False,Unknown} kept distinct from a separate VerificationState(P) in {Verified,Failed,Unverified}, plus a four-valued refinement {True,False,Both,Neither} for temporarily contradictory evidence (Conflict!=Error); L5 refined from 'current state can never represent history' (too strong) to 'current state does not guarantee historical reconstructibility' (the projection pi: H->S would need to be injective over the required domain, which most status-based systems do not satisfy); L6 survives as a conceptual distinction (What must remain consistent? vs How does something evolve over time?) even when a trivial process involves exactly one aggregate; L7 (Evidence != Claim, evidence supports rather than equals a claim) and L8 (Verification != GovernanceDecision, a PASS verdict is decision input, not the decision itself, since governance additionally weighs risk/strategy/budget/legal/business priorities/exceptions) both judged 'strong'. Consolidates into eight final laws A-H plus one meta-law: 'use the weakest mechanism that provides the required assurance.'"

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1362 §"Every important architectural law should have a potential falsifier. If nothing could ever disprove it, it is not functioning as a useful verification rule."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1362 §"TruthState(P) ∈ {True,False,Unknown}. And separately: VerificationState(P) ∈ {Verified,Failed,Unverified}. These must not be confused. ... {True,False,Both,Neither}. ... contradictory evidence may temporarily support both: P and ¬P. Rather than forcing the system to choose prematurely, we can represent: Both. This connects to the earlier principle: Conflict ≠ Error."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1362 §"Law A: AIOutput alone ⇏ KnowledgeEstablished ... Law H: HistoricalRecord ≠ HistoricalTruth. And one meta-law emerges: Use the weakest mechanism that provides the required assurance. ... For each arrow we should ask four questions: 1. What changes? 2. What invariant must hold? 3. What evidence proves the transition? 4. Who or what has authority to perform it?"]

## Lifecycle

last_seen: S1362. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1362), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1362 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1362 (×4) |
| dependencies | PRESENT | S1362 (×3) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1362 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1362 |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1362] types=[PRINCIPLE] scope=METHODOLOGICAL — "States the governing scientific/falsificationist principle for the step: every important architectural law should have a potential falsifier; a law nothing could ever disprove is not functioning as a useful verification rule." (anchor: "Every important architectural law should have a potential falsifier. If nothing could ever disprove it, it is not functioning as a useful verification rule.")
- [S1362] types=[CORRECTION, EXTENSION] scope=THEORY-LEVEL — "Attacks and refines L1 (a deterministically-computed correct AI output like 2+2=4 shows AI CAN produce knowledge-like results, so the real question is which mechanism establishes epistemic status, not whether AI can be correct) into 'AIOutput ALONE does not imply KnowledgeEstablished' -- AI can participate but cannot bypass the applicable establishment rule. Attacks and refines L2: a minimal system where capability happens to equal authority by design shows Capability=Authority is possible as a consequence of an added constraint (Capability(a,x)=>Authority(a,x)), not a logical identity, so the correct, stronger claim is 'capability does not logically IMPLY authority', not 'they must always differ'." (anchor: "AIOutput alone ⇏ KnowledgeEstablished. The word alone matters. AI can participate in knowledge production. It cannot bypass the applicable establishment rule merely by producing an answer. ... We should not claim: Capability and authority must always be different. We claim: Capability does not logically imply authority. That is much stronger mathematically.") — lineage claim: SOURCE-CLAIMED-REFINEMENT of Step 168's L1 and L2.
- [S1362] types=[FORMALIZATION, EXTENSION] scope=THEORY-LEVEL — "Strengthens L4 (Unknown!=False, described as 'almost foundational logic') by keeping a three-valued TruthState(P) in {True,False,Unknown} strictly separate from a parallel VerificationState(P) in {Verified,Failed,Unverified} (they can coexist coherently, e.g. TruthState=Unknown with VerificationState=Unverified), and further proposes a four-valued refinement {True,False,Both,Neither} for cases where contradictory evidence temporarily supports both P and not-P, rather than forcing a premature choice -- connecting to the earlier Conflict!=Error principle." (anchor: "TruthState(P) ∈ {True,False,Unknown}. And separately: VerificationState(P) ∈ {Verified,Failed,Unverified}. These must not be confused. ... {True,False,Both,Neither}. ... contradictory evidence may temporarily support both: P and ¬P. Rather than forcing the system to choose prematurely, we can represent: Both. This connects to the earlier principle: Conflict ≠ Error.")
- [S1362] types=[CORRECTION] scope=THEORY-LEVEL — "Refines L5 from the too-strong 'current state can never represent history' to the correct, weaker law 'CurrentState does not guarantee historical reconstructibility' -- a specially designed system COULD retain enough information for the state->history projection pi:H->S to be injective over the required domain (making CurrentState=History possible in principle), but most ordinary status-based systems do not satisfy this, so the general (non-universal) law holds." (anchor: "the projection: π:H→S must be injective over the required domain. Most ordinary status-based systems do not satisfy this. Therefore the general law remains valid ... We should therefore avoid: 'Current state can never represent history.' That is too strong. Instead: CurrentState does not guarantee historical reconstructibility. This is a better architectural law.") — lineage claim: SOURCE-CLAIMED-REFINEMENT of Step 168's L5.
- [S1362] types=[GOVERNANCE, FUTURE-RESEARCH] scope=THEORY-LEVEL — "Consolidates the falsification pass into eight refined final laws (A: AIOutput alone does not imply KnowledgeEstablished; B: Capability does not imply Authority; C: Execution does not imply Correctness or Verification; D: Unknown!=False; E: CurrentState does not guarantee HistoricalReconstructibility; F: Evidence!=Claim; G: Verification!=GovernanceDecision; H: HistoricalRecord!=HistoricalTruth) plus the meta-law 'use the weakest mechanism that provides the required assurance.' States the architecture has survived a first falsification pass with important refinements, not proof. Proposes Step 170 = The End-to-End KnowledgeOS Proof Chain, applying four questions per pipeline arrow (what changes? what invariant must hold? what evidence proves the transition? who has authority?) to test whether the Steps-1-169 architecture is 'closed' (no unexplained transition, missing state, missing evidence, missing authority, or circular assumption)." (anchor: "Law A: AIOutput alone ⇏ KnowledgeEstablished ... Law H: HistoricalRecord ≠ HistoricalTruth. And one meta-law emerges: Use the weakest mechanism that provides the required assurance. ... For each arrow we should ask four questions: 1. What changes? 2. What invariant must hold? 3. What evidence proves the transition? 4. Who or what has authority to perform it?") — lineage claim: SOURCE-CLAIMED-CONTINUATION of Step 170.

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
