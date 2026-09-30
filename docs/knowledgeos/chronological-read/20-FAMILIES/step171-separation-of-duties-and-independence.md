# step171-separation-of-duties-and-independence

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `RequiredIndependence(C) = f(Risk,Authority,Domain,ControlObjective)`, `SinglePointOfTrust: Generate→Verify→Approve→Execute by one actor`, `TechnicalIndependence vs OrganizationalIndependence vs EpistemicIndependence` · **Aliases:** `independence is contextual`, `separation-of-duties risk recognition`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 171 names the SinglePointOfTrust anti-pattern (one actor performing Generate->Verify->Approve->Execute) as a risk to be recognized, not automatically unacceptable. Distinguishes TechnicalIndependence, OrganizationalIndependence, and EpistemicIndependence (a verifier can be technically separate but organizationally dependent; conversely an automated verifier can be epistemically independent of a human producer for a deterministic predicate); connects self-verification (Producer=Verifier) to the correlated-evidence problem (E1 and V(E1) may represent the same epistemic source, not two independent observations). Formalizes RequiredSeparation = f(Risk,Authority,Domain,ControlObjective), explicitly not a universal 'always required' rule, to prevent overengineering.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1364] §"Actor=A performs: Generate → Verify → Approve → Execute. This creates a potential: SinglePointOfTrust. The risk is not automatically unacceptable. But it must be recognized. ... TechnicalIndependence from: OrganizationalIndependence. And: EpistemicIndependence. ... RequiredSeparation = f(Risk,Authority,Domain,ControlObjective). Not: RequiredSeparation=always. This prevents overengineering."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1364] §"Actor=A performs: Generate → Verify → Approve → Execute. This creates a potential: SinglePointOfTrust. The risk is not automatically unacceptable. But it must be recognized. ... TechnicalIndependence from: OrganizationalIndependence. And: EpistemicIndependence. ... RequiredSeparation = f(Risk,Authority,Domain,ControlObjective). Not: RequiredSeparation=always. This prevents overengineering."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1364`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1364 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1364 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1364 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1364]` types=[WARNING, FORMALIZATION] scope=THEORY-LEVEL — "Names the SinglePointOfTrust anti-pattern (one actor across Generate->Verify->Approve->Execute) as a recognized-but-not-automatically-unacceptable risk. Distinguishes TechnicalIndependence, OrganizationalIndependence, and EpistemicIndependence -- a verifier can be technically separate yet organizationally dependent, or an automated verifier can be epistemically independent of a human producer for a deterministic predicate; self-verification (Producer=Verifier) parallels the correlated-evidence p…" (anchor: "Actor=A performs: Generate → Verify → Approve → Execute. This creates a potential: SinglePointOfTrust. The risk is not automatically unacceptable. But it must be recognized. ... TechnicalIndependence…")

## Notes for P3
- Thin evidentiary base (1 row) — any relationship claims beyond what is listed here would be unsupported.
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.
- No rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) rows were found for this label in the capture; the object's motivation is not evidenced here.
