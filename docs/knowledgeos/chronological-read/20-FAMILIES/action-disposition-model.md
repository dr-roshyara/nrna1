# action-disposition-model

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ACT/REFRAIN/DEFER/ESCALATE/INVESTIGATE/REQUEST_AUTHORIZATION`, `ActionDisposition` · **Aliases:** `Decision -> ActionDisposition`
**Candidate group membership (NOT an identity claim):**
- **G1062**: candidate group with `action-formal-model` — working_label token overlap Jaccard=0.50 (shared tokens: ['action', 'model']) (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): Step 158's proposed extension of the Decision->Action chain to Decision->ActionDisposition in {ACT,REFRAIN,DEFER,ESCALATE,INVESTIGATE,REQUEST_AUTHORIZATION}, motivated by the Gita-Chapter-4-derived requirement that a system be able to represent a legitimate decision not to act; ActionDisposition=ACT proceeds through Authorization to Action, ActionDisposition=REFRAIN yields NoAction; framed as a semantic capability around Decision/Action, not a new bounded context.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1349] §"ActionDisposition ├── ACT ├── REFRAIN ├── DEFER ├── ESCALATE ├── INVESTIGATE └── REQUEST_AUTHORIZATION ... Decision -> ActionDisposition. ActionDisposition=ACT ⇒ Authorization ⇒ Action. ActionDisposition=REFRAIN ⇒ NoAction."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1349] §"ActionDisposition ├── ACT ├── REFRAIN ├── DEFER ├── ESCALATE ├── INVESTIGATE └── REQUEST_AUTHORIZATION ... Decision -> ActionDisposition. ActionDisposition=ACT ⇒ Authorization ⇒ Action. ActionDisposition=REFRAIN ⇒ NoAction."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1357. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1349 |
| Type signature | PRESENT | S1349 |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S1357 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1357 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1349] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Proposes replacing the direct Decision->Action edge with Decision->ActionDisposition in {ACT,REFRAIN,DEFER,ESCALATE,INVESTIGATE,REQUEST_AUTHORIZATION}: ActionDisposition=ACT proceeds through Authorization to Action; ActionDisposition=REFRAIN yields NoAction. Argues a responsible agent must be able to output DO NOT ACT when evidence is insufficient, authorization is absent, the requirement is ambiguous, conflicting claims exist, or the action is outside scope -- framed as often the correct outcome, not agent failure." (anchor: "ActionDisposition ├── ACT ├── REFRAIN ├── DEFER ├── ESCALATE ├── INVESTIGATE └── REQUEST_AUTHORIZATION ... Decision -> ActionDisposition. ActionDisposition=ACT ⇒ Authorization ⇒ Action. ActionDisposition=REFRAIN ⇒ NoAction.")
- [S1357] types=[EXTENSION, RESTATEMENT] scope=THEORY-LEVEL — "Elaborates the ActionDisposition branch events at the domain-event level: DecisionDeferred means no operational decision has yet been authorized (not failure); DecisionEscalated means authority was insufficient or the case needs higher governance (Escalation != Failure); DecisionDispositionSetToRefrain represents a deliberate, intentional and auditable decision not to act (KnowledgeOS should support Input -> Determine -> DoNothing as a valid outcome, unlike conventional workflows that implicitly assume Input -> Action)." (anchor: "DecisionDeferred. The meaning is: No operational decision has yet been authorized. This is not failure. ... DecisionEscalated. ... Escalation ≠ Failure. ... DecisionDispositionSetToRefrain. This represents a deliberate decision not to act. ... Input → Determine → DoNothing. The 'do nothing' outcome must be intentional and auditable.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
