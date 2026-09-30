# human-act

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1163: links `human-act` with `grant-record` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1307: links `human-act` with `inv-003-assessment-ne-authority` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope OBJECT): The Human Act concept: a performative act outside the software, of which BC-7 owns only the registration/reference.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0067] §"Human Act | CORRECTED — NOT owned. The commission's candidate list placed it in the owned column; the test moves it out."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0234] §"Human authority: the PO/ARB is the reserved decision authority"
- CANDIDATE-FORMAL-BIRTH: [S0237] §"Family 3 -- AUTHORITY (Who/What may authorize?)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0234] §"Human authority: the PO/ARB is the reserved decision authority"

## Lifecycle
last_seen: S0241. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0071, S0237 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0237, S0241 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0234 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0172, S0188 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0188 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0071] (ANALYSIS) Human Act is rejected as aggregate root on two independent grounds: it has neither identity nor lifecycle inside BC-7, and promoting it would duplicate a concept another (Knowledge Engineering/Governance) context owns and would improperly drag authority semantics into a context that cannot evaluate authority (ES-005.4).
- [S0237] (ANALYSIS) Nine landscape findings (C-1..C-10 evidence) mapped onto the register, with F2/F3/F4 carry-forwards honored (implemented mechanism != domain ownership; evidence is current-state-scoped not universal; consumption != ownership != KnowledgeOS): state-as-fold/append-only log (C-2, SAME as Revisability-001's enforcement, mechanism layer); forward-only supersession (C-5, SAME as Revisability-001, asymmetric ownership PKS-governance/EKS-mechanism/AIP-declared-only); closed-verdict vocabulary (C-3, SAME as the Validation Layer, a published-language boundary); assessment-conferring-no-authority (C-4, SAME as INV-003 Established); epistemic-class discipline (C-8, SAME as Pramana-001's source-role separation, PKS-owned, asymmetric F3); regenerable non-authoritative projection (C-7, SAME as INV-004 Established); honest-UNKNOWN (C-10, SAME as H-ZERO-001, 'discipline, not mechanism'); authority-as-recorded-reference (C-1, SAME as the Authority family's core, X-02 SAME-vs-RELATED across systems UNKNOWN/U-02); the F4 relationship question (AIP is DISTINCT -- a consumer/runtime, not the owner, not the KnowledgeOS layer itself).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0067]` types=[CORRECTION] scope=OBJECT — "The commission's hypothesis that BC-7 owns Human Act is corrected by evidence: BC-7 owns only the requirement that an act be recorded (its registration) and a reference to it, never the performative act itself, which exists outside the software as a committed artifact owned elsewhere." (anchor: "Human Act | CORRECTED — NOT owned. The commission's candidate list placed it in the owned column; the test moves it out.")
- `[S0071]` types=[ANALYSIS] scope=OBJECT — "Human Act is rejected as aggregate root on two independent grounds: it has neither identity nor lifecycle inside BC-7, and promoting it would duplicate a concept another (Knowledge Engineering/Governance) context owns and would improperly drag authority semantics into a context that cannot evaluate authority (ES-005.4)." (anchor: "Candidate D — Human Act Aggregate (rejected, on two independent grounds) ... Promoting it to an aggregate root would duplicate a concept another context owns")
- `[S0172]` types=[PRINCIPLE/VALIDATION] scope=THEORY-LEVEL — "States the authority principle discovered from live evidence: 20/20 grants in the examined record reference a human act, and Governance only records that reference, never manufacturing authority itself." (anchor: "The mechanism records authority; it does not create authority.")
- `[S0187]` types=[EXTENSION] scope=THEORY-LEVEL — "Proposes generalizing the existing EKS Authority invariant (record ≠ authority) into a kernel-level Authority mechanism owning principal/scope/capability/grant/lifecycle/evidence, with domain-specific 'which architecture is correct' judgement left above the kernel." (anchor: "Artifact existence ≠ Authority ... Record existence ≠ Authority establishment. That is exactly the kind of invariant a kernel can protect.")
- `[S0188]` types=[PRINCIPLE/EXPERIMENTAL-RESULT] scope=OBJECT — "States the highest-confidence current-state finding of this EKS baseline: measured directly from 9 work items and 20 grants, every grant references a human act and is registered by governance, evidencing that the mechanism records rather than creates authority." (anchor: "The mechanism records authority; it does not grant authority ... All 20/20 grants carry humanActRef, and registeredBy has exactly one observed value — governance.")
- `[S0234]` types=[CONCEPT/GOVERNANCE] scope=OBJECT — "Grants register a recorded human act by reference and never manufacture authority (G-2/R5b). The four-stage chain: Architecture proposal -> Principal Architect recommendation -> PO/ARB decision -> Governance registration." (anchor: "Human authority: the PO/ARB is the reserved decision authority")
- `[S0234]` types=[INVARIANT] scope=OBJECT — "Grants: a grant without a humanActRef is refused by the engine ('the record never manufactures authority -- G-2/R5b'). Exactly one mutation owner per work item is mechanically enforced (I-1). Closure is a governance act (G-1): COMPLETE refuses any writer but governance/human. Producer != acceptor (R-34, INV-ATTR-2): a process cannot verify/accept its own work; separation is declared, not attestable." (anchor: "Exactly one mutation owner per work item is a mechanically enforced invariant (I-1)")
- `[S0237]` types=[FORMALIZATION] scope=CROSS-OBJECT — "Family 3 (AUTHORITY) table: INV-003 Assessment != Authority (ESTABLISHED, forbids Assessment->Authority; an instance of Relation-001); Authority-as-Recorded-Reference (C-1, STRONG landscape row -- EKS grant 126/126 humanActRef, PKS Charter G-17, AIP grant G-2/R5b -- 'the record references a human act; it never manufactures authority'; forbids Record->Authority; whether this landscape row is SAME or RELATED to the register's authority rows across systems is UNKNOWN, U-02)." (anchor: "Family 3 -- AUTHORITY (Who/What may authorize?)")
- `[S0237]` types=[ANALYSIS] scope=CROSS-OBJECT — "Nine landscape findings (C-1..C-10 evidence) mapped onto the register, with F2/F3/F4 carry-forwards honored (implemented mechanism != domain ownership; evidence is current-state-scoped not universal; consumption != ownership != KnowledgeOS): state-as-fold/append-only log (C-2, SAME as Revisability-001's enforcement, mechanism layer); forward-only supersession (C-5, SAME as Revisability-001, asymmetric ownership PKS-governance/EKS-mechanism/AIP-declared-only); closed-verdict vocabulary (C-3, SAME as the Validation Layer, a published-language boundary); assessment-conferring-no-authority (C-4, SAME as INV-003 Established); epistemic-class discipline (C-8, SAME as Pramana-001's source-role separation, PKS-owned, asymmetric F3); regenerable non-authoritative projection (C-7, SAME as INV-004 Established); honest-UNKNOWN (C-10, SAME as H-ZERO-001, 'discipline, not mechanism'); authority-as-recorded-reference (C-1, SAME as the Authority family's core, X-02 SAME-vs-RELATED across systems UNKNOWN/U-02); the F4 relationship question (AIP is DISTINCT -- a consumer/runtime, not the owner, not the KnowledgeOS layer itself)." (anchor: "Landscape integration -- which EKS/PKS/AIP responsibilities already exist, and where their boundaries lie")
- `[S0241]` types=[FORMALIZATION] scope=OBJECT — "Article 3 (Authority), 3 clauses: evidence/assessment/source SHALL NOT constitute authority; authority SHALL be assigned -- a recorded reference to a human act, never intrinsic or content-emergent; sources SHALL NOT be interchangeable (the means of acquisition SHALL be preserved -- sensor != expert statement != AI inference != historical document). Renders INV-001, INV-003, INV-KOS-Pramana-001. Failure if removed: a recorded reference self-authorizes; assessment becomes binding; source-role collapse." (anchor: "Article 3 -- Authority: 1. Evidence SHALL NOT constitute authority ... 2. Authority SHALL be assigned ... 3. Sources SHALL NOT be interchangeable")

## Notes for P3
- No unusual internal tension observed across this label's 10 captured row(s); evidentiary base is proportionate to row count.
