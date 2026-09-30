# actor-vs-authority-distinction

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Actor != Authority`
**Aliases:** "'AI did it' anti-pattern"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope OBJECT: "Step 191's distinction that recording who/what performed an action (Actor: Human/System/AI/ExternalSystem) never by itself establishes Authority; includes the AI-proposes/human-authorizes worked chain and the named 'AI did it' anti-pattern (collapsing generation/assessment/authority/decision into one field)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1394 §"Actor\neq Authority. We must retain both when authority matters."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1394. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1394), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1394 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1394 (×2) |
| examples | PRESENT | S1394 |
| warnings | PRESENT | S1394 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1394] types=[DISTINCTION, INVARIANT] scope=OBJECT — "An event's Actor (Human, System, AI, or ExternalSystem) does not automatically establish Authority; both must be retained separately when authority matters." (anchor: "Actor\neq Authority. We must retain both when authority matters.")
- [S1394] types=[EXAMPLE, EXTENSION] scope=OBJECT — "Worked AI example: Actor=Claude generating a CandidateArchitectureChange records Actor=AI, while Authority=HumanArchitect only if/when the architect accepts the proposal -- the chain AI-Proposes->Candidate-ArchitectAuthority->AcceptedChange, framed as precisely the desired human/AI separation." (anchor: "AI \xrightarrow{Proposes} Candidate \xrightarrow{ArchitectAuthority} AcceptedChange.")
- [S1394] types=[WARNING] scope=OBJECT — "Names and forbids the 'AI did it' anti-pattern: recording architecture_status=approved / modified_by=AI collapses generation, assessment, authority, and decision into one field; the architecture must keep AI Generation, Human Determination, and Governance Approval as three distinct steps." (anchor: "AI Generation \neq Human Determination \neq Governance Approval.")
- [S1394] types=[RESTATEMENT, DISTINCTION] scope=THEORY-LEVEL — "Applies the Gita Chapter 4 lens (karma: knowing/acting/rightful action/consequences are distinct) to restate Knowledge!=Action, Action!=Outcome, and specifically RightToAct != AbilityToAct, mapped onto the architecture's Authority != Capability distinction." (anchor: "RightToAct \neq AbilityToAct. That maps beautifully onto: Authority \neq Capability.")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
