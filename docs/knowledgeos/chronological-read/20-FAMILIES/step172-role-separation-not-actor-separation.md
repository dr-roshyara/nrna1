# step172-role-separation-not-actor-separation

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AI.Generate does not imply AI.Verify; AI.Verify does not imply AI.Authorize`, `Actor ∈ {Human,AI,System,ExternalSystem}`, `ActorSeparation is not itself the invariant. RoleSeparation is.`, `RequiredControl = f(Risk,Impact,Uncertainty,DomainPolicy)` · **Aliases:** `AI as a new kind of actor`, `role separation vs actor separation law`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 172's key consolidated law: 'ActorSeparation is not itself the invariant. RoleSeparation is' -- a single actor (human or AI) may perform several roles, but the architecture must still classify each action by its semantic role, so a single AI agent's capability to inspect/reason/generate/test/modify/report must not collapse Generate=Verify=Approve=Execute (AI.Generate does not imply AI.Verify; AI.Verify does not imply AI.Authorize). Models Actor as a type Actor in {Human,AI,System,ExternalSystem} participating in the same transition contract -- AI becomes 'a new kind of actor' rather than requiring a wholly separate architecture -- but AI-generated outputs carry additional Uncertainty requiring explicit Confidence/Uncertainty/Evidence/Method/Verification handling, with RequiredControl=f(Risk,Impact,Uncertainty,DomainPolicy) replacing the vague phrase 'human in the loop' with a sharper question: at which transition is human authority required, and what exactly does the human establish (HumanVerification != HumanAuthorization != HumanDecision).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1365 §"ActorSeparation is not itself the invariant. RoleSeparation is. ... AI.Generate does not imply: AI.Verify. And: AI.Verify does not imply: AI.Authorize. ... Actor ∈ {Human,AI,System,ExternalSystem}. ... AI becomes a new kind of actor participating in an existing governed lifecycle. ... RequiredControl = f(Risk,Impact,Uncertainty,DomainPolicy)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1365 §"ActorSeparation is not itself the invariant. RoleSeparation is. ... AI.Generate does not imply: AI.Verify. And: AI.Verify does not imply: AI.Authorize. ... Actor ∈ {Human,AI,System,ExternalSystem}. ... AI becomes a new kind of actor participating in an existing governed lifecycle. ... RequiredControl = f(Risk,Impact,Uncertainty,DomainPolicy)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1365 §"ActorSeparation is not itself the invariant. RoleSeparation is. ... AI.Generate does not imply: AI.Verify. And: AI.Verify does not imply: AI.Authorize. ... Actor ∈ {Human,AI,System,ExternalSystem}. ... AI becomes a new kind of actor participating in an existing governed lifecycle. ... RequiredControl = f(Risk,Impact,Uncertainty,DomainPolicy)."]

## Lifecycle
last_seen: S1365. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1365 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1365 |
| dependencies | PRESENT | S1365 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1365] types=['GOVERNANCE', 'FORMALIZATION'] scope=THEORY-LEVEL — "Consolidates the falsification-experiment finding into the key law: 'ActorSeparation is not itself the invariant. RoleSeparation is' -- a single actor may hold several roles, but the architecture must still classify actions by semantic role, so an AI agent's generic capability (inspect/reason/generate/test/modify/report) must not collapse Generate=Verify=Approve=Execute (AI.Generate does not imply AI.Verify; AI.Verify does not imply AI.Authorize). Models Actor as a type in {Human,AI,System,ExternalSystem} within the same transition contract -- AI is 'a new kind of actor', not requiring a separate architecture -- while AI-generated outputs carry additional epistemic uncertainty requiring explicit handling, giving RequiredControl=f(Risk,Impact,Uncertainty,DomainPolicy), replacing the vague 'human in the loop' with the sharper question of which transition requires human authority and what exactly the human establishes (HumanVerification!=HumanAuthorization!=HumanDecision)." (anchor: "ActorSeparation is not itself the invariant. RoleSeparation is. ... AI.Generate does not imply: AI.Verify. And: AI.Verify does not imply: AI.Authorize. ... Actor ∈ {Human,AI,System,ExternalSystem}. ... AI becomes a new kind of actor participating in an existing governed lifecycle. ... RequiredControl = f(Risk,Impact,Uncertainty,DomainPolicy).")

## Notes for P3
- Thin evidence base (n=1 row(s)) — treat conclusions here as provisional.
