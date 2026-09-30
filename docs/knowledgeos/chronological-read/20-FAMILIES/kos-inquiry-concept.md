# kos-inquiry-concept

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Inquiry{question,subject,authority context,purpose,evidence requested,response,resolution} · **Aliases:** Question != Inquiry

**Candidate group membership (NOT an identity claim):**
- **G0291** [`inquiry-context-doubt-to-settlement` · `kos-inquiry-concept`] — explicit agent-stated uncertainty: 'kos-inquiry-concept' POSSIBLY relates to 'inquiry-context-doubt-to-settlement' (batch B0025). Note: This batch's formalization of Inquiry as a distinct epistemic-operation kernel object (vs. Question as mere content), reprising and re-deriving a distinction already explored in the B0011/B0012 Inquiry lenses.
- **G0292** [`critical-thinking-inquiry-lens` · `kos-inquiry-concept`] — explicit agent-stated uncertainty: 'kos-inquiry-concept' POSSIBLY relates to 'critical-thinking-inquiry-lens' (batch B0025). Note: This batch's formalization of Inquiry as a distinct epistemic-operation kernel object (vs. Question as mere content), reprising and re-deriving a distinction already explored in the B0011/B0012 Inquiry lenses.

## Sources (how this label entered the ledger)

- **PROPOSAL**, batch B0025, scope OBJECT: This batch's formalization of Inquiry as a distinct epistemic-operation kernel object (vs. Question as mere content), reprising and re-deriving a distinction already explored in the B0011/B0012 Inquiry lenses.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1050 §"Question ≠ Inquiry. Inquiry { question, subject, authority context, purpose, evidence requested, response, resolution }."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1060 §"Inquiry{inquiryId,question,subject,context,requester,authorityScope,evidenceRequirements,responses,determinations,status}. ChatMessage ≠ Inquiry — 'what is the Nexus architecture?' is communication, while 'determine whether Nexus conforms to approved architecture v1.1' has subject/purpose/evidence requirements/method/expected determination. AI conversation → Inquiry → Context → Agent Reasoning → Claims → Evidence → Determination; the conversation is not the authoritative artifact, the derived governed objects are."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1060. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1060) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1050, S1060 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1060 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1050]` types=[DEFINITION, EXTENSION] scope=OBJECT — "Formalizes Inquiry as a distinct kernel object (a Question is content; an Inquiry is an epistemic operation with its own schema), argued to fit well into the KnowledgeOS kernel." (anchor: "Question ≠ Inquiry. Inquiry { question, subject, authority context, purpose, evidence requested, response, resolution }.")
- `[S1060]` types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Formalizes Inquiry as a first-class domain concept, distinguishes it from a mere chat message, and shows how AI interaction becomes auditable by producing derived governed objects rather than treating the conversation itself as authoritative." (anchor: "Inquiry{inquiryId,question,subject,context,requester,authorityScope,evidenceRequirements,responses,determinations,status}. ChatMessage ≠ Inquiry — 'what is the Nexus architecture?' is communication, while 'determine whether Nexus conforms to approved architecture v1.1' has subject/purpose/evidence requirements/method/expected determination. AI conversation → Inquiry → Context → Agent Reasoning → Claims → Evidence → Determination; the conversation is not the authoritative artifact, the derived governed objects are.")

## Notes for P3

No internal tension or unusual evidentiary pattern noticed while assembling this file; the rows are mutually consistent at the level this pass can check.
