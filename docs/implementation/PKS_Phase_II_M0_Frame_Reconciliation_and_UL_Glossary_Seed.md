# PKS Phase II — M0: Frame Reconciliation + Ubiquitous Language Glossary Seed

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M0 of the approved plan). Frame reconciliation + UL seed — **no bounded context, no architecture, no implementation.** |
| **Authority** | Generated — never authoritative without human review. |
| **Status** | **PRODUCED — checkpoint review round complete (PA, 2026-07-28: approve-with-refinements over two review rounds; all requested refinements applied same date — observational-only language, recommendation-not-adoption, confidence rubric, enriched provenance fields).** |
| **Commission** | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED, gate #1 passed 2026-07-28) + the PA's M0+M1 execution commission (session log 2026-07-28). |
| **Placement** | `docs/implementation/`, beside the accepted Phase-I inputs, per the plan's placement note. |

---

## Part 1 — Frame Reconciliation

**Question:** which phase frame governs Phase II execution — the PA's II.A–D frame, or the accepted candidate model's phase table (I–V)?

### The two frames, mapped

| PA frame (II.A–D) | Candidate model table (I–V) | Mapping | Semantic difference |
|---|---|---|---|
| **II.A Strategic Modeling** (UL · domains · BCs · context map · candidate validation) | **III. Strategic Modeling** (Bounded Contexts, Context Maps, Ubiquitous Language) | ≈ Direct | None material |
| **II.B Architecture Definition** (logical architecture · responsibilities · service boundaries · interaction/integration patterns · principles) | **IV. Reference Model** (formal schemas, Value Objects, Domain Events) | Partial | **IV is tactical-DDD-flavored** (VOs, events = tactical design); II.B is logical-architecture-flavored. They are different activities that both sit after strategic modeling |
| **II.C C4** (documentation of a stabilized architecture) | *(no counterpart)* | — | The candidate table has **no explicit C4/documentation stage**; the PA frame makes it an explicit gate |
| **II.D Implementation** | **V. Technical Architecture** (storage engines, pipelines, APIs) | Partial | V mixes architecture-design and implementation concerns that II.C/II.D separate |
| *(no counterpart — already done)* | I. Discovery · II. ARB Review | Historical | Completed 2026-07-27/28 |

### Reconciliation recommendation

**Recommendation: the PA's II.A–D as the operational execution frame for Phase II — because it is the frame currently established by project governance.** *(M0 is a modeling artifact, not governance: it recommends; adoption is the checkpoint reviewer's act.)* The candidate model's I–V table is preserved as the historical hypothesis it was accepted as, with the mapping above as the permanent cross-reference.

- **Evidence:** the II.A–D frame is the one carried by the recorded scope guard and commission seed (session log 2026-07-28, final entries — binding PA guidance); the candidate table entered the record as a hypothesis inside an artifact whose acceptance explicitly adopted no concept (item 2 Status, DR-1). Where the frames differ, the differences (no C4 gate; tactical Reference Model placed immediately after strategic modeling) are exactly the risks the scope guard exists to prevent.
- **Rationale:** an execution frame must match the governance actually in force; II.A–D is that governance restated as phases.
- **Rejected alternative:** adopting the candidate's I–V — rejected because its stage IV presupposes tactical-DDD sequencing that has not been planned or authorized, and it lacks the explicit C4-after-stability gate the PA established. *(Not rejected as wrong — rejected as not the governing frame; it remains a useful lens on what a Reference Model stage might later contain.)*
- **Deliberately NOT decided here:** where a tactical Reference Model stage sits inside II.B/II.D. That is future planning work (post-Strategic-Modeling), flagged, not resolved — deciding it now would exceed M0.
- **Confidence:** High (the recommendation follows from recorded governance, not judgment about the frames' intrinsic merits).

---

## Part 2 — Ubiquitous Language Glossary Seed

Seeded from the 17 operating concepts (item 1, §1.2 — the accepted evidence baseline). Definitions are grounded in **observed behavior**, not aspiration. Each entry: operational definition · identity observed · origin evidence · portability · **confidence · assumptions · contradicting evidence** (per the checkpoint refinements). **Names follow the operating evidence; candidate synonyms are recorded, not adopted.**

**Scope boundary (M0 ≠ M1):** this glossary records **observed vocabulary** — what the corpus demonstrably calls things and how those things behave. It **validates nothing**. Formal evaluation of every concept (origin / supporting / contradicting evidence · portability verdict · validated-rejected-deferred classification) happens exclusively in the M1 Concept Register; a glossary entry here confers no validated standing.

| # | Term | Operational definition (as the corpus behaves) | Observed identity | Observed evidence (item 1) | Portability | Conf. | Assumptions · contradicting evidence |
|---|---|---|---|---|---|---|---|
| G-1 | **Decision** | A selection among alternatives under explicit trade-offs, recorded with rationale, individually citable, superseded only forward | ADR-Tn · D-nn · ADR-MP-nn · ADR-nnn | K-1; §1.3 (23-in-1-file) | Essential to any PKS; the register/log *serialization* is local convention | High | Assumes durable citability across supersession (observed consistently). **Contra: 5 colliding ADR numbering spaces** — contradicts identity *uniqueness*, not the concept (→ OQ-1/M4) |
| G-2 | **Rule** | A binding behavioral norm with a single canonical home; other documents reference, never restate | ER-nn/EP-nn · ES-00n.m | K-2 | Essential; pointer discipline local, concept not | High | Assumes single-canonical-home holds (consolidation precedent). Contra: none observed |
| G-3 | **Invariant** | A condition that must always hold; violation is a defect, not a choice | CI-n/BI-n · INV-n | K-3 | Essential | High | **Contra: corpus does not separate it from Constraint (V-3)** — distinctness deferred to M2 (C-3); existence itself uncontradicted |
| G-4 | **Ruling** | A recorded governance act by an authority, smaller-grained than a decision record | per-ruling docs · R-nn rows | K-4 | Portable as concept; register form local | Medium | Assumes Ruling ≠ Decision by grain/authority — observed, but the grain boundary is fuzzy. Contra: none direct |
| G-5 | **Finding** | An evidenced defect/observation raised by a review or audit, id-scoped to its run | F-… (≥6 run-scoped spaces) | K-5 | Portable; run-scoped ids a local liability (→ OQ-1/M4) | High | Assumes run-scoping is intentional rather than accidental — unverified (OQ-1-adjacent). Contra: none |
| G-6 | **Question** (open) | An owned, routed unknown whose lifecycle outlives its carrier document | OQ-n · Q-n · D3x | K-6 | Essential | High | Contra: none observed |
| G-7 | **Term** | A ubiquitous-language entry; changes are first-class governed events | term→definition; ADR-UL-nn | K-7 | Essential. *(Candidate synonym "Definition" (item 2) recorded, not adopted — operating name wins)* | High | Contra: none observed |
| G-8 | **Contract** | A versioned specification of expectations at a boundary; rows/versions evolve independently of their carrier | catalog row + SchemaVersion | K-8 | Observed exclusively in event-contract contexts; **formal interpretation deferred to M1** | Medium-High | Observed usage is event-scoped; no non-event instance measured. Interpretation of that observation belongs to M1 |
| G-9 | **Model element** | A named element of a governed model, citable independently of the artifact that froze it | named per element · R-n | K-9 — the corpus's own enumeration of element kinds (aggregate · VO · responsibility · state machine · policy) is **cited evidence, not imported taxonomy** (frozen EPIC-004 chain) | Portable; one-concept-or-family is an open grain question (→ M4/M6) | Medium | Assumes the family can be treated as one seed entry — grain deliberately open. Contra: none |
| G-10 | **Observation** | A factually captured evidence entry (measurement, benchmark, review input), labeled by epistemic class | O-n · register rows | K-10 | Essential. OQ-CM-1 attaches (→ M4) | High | Contra: none observed |
| G-11 | **Risk** | An identified potential harm with a register lifecycle | R-1..R-7 (register-scoped) | K-11 | Portable | Medium-High | **Contra: id-space collision with Ruling's R-nn** (→ OQ-1/M4) |
| G-12 | **Candidate** | A knowledge item holding probationary status pending qualifying evidence; promotion only by human act | P-n · "Candidate …" headers | K-12 | Portable (probationary status universal); the specific ladder local | Medium | Assumes the status generalizes beyond this repo's ladder — plausible, single-corpus evidence. Contra: none |
| G-13 | **Work item** | A unit of work with lifecycle ≠ progress | PB-nnn · EPIC-nnn | K-13 | Portable — **boundary flag: possibly an adjacent work-management domain; classification is M5's** | Medium | Assumption deliberately withheld on domain membership. Contra: none on existence |
| G-14 | **Guide step** | A teaching unit (how-to), one step per file, ordered | numbered files per area | K-14 | Portable concept | Medium | **Contra to operating strength: 25 of 31 areas do not follow the convention** — contradicts conformance, not existence |
| G-15 | **Verdict** | The outcome of evaluating evidence against a criterion (PASS/FAIL/…/CERTIFIED); issued by a gate or review, never by the author | per-run outcome tokens | K-15 | Essential | High | **"Qualification" carries two meanings (OQ-11, ARB-owned; → Surfacing Register, M1)** — glossary carries both senses provisionally, canonicalizes neither. Contra on Verdict itself: none |
| G-16 | **Exception record** | A recorded, approved deviation with owner and expiry semantics | file+element keyed | K-16 | Portable | Medium | **Contra to operating completeness: expiry mechanism exists but is unused** (`expires: null, status: permanent`) |
| G-17 | **Charter grant** | An authorization permitting a named next activity and nothing else | per-charter | K-17 | Essential ("construction permit, not a design") | High | Contra: none observed |

**Vocabulary risks attached (from the accepted record):** V-1 lifecycle-vocabulary fragmentation touches *every* entry's status semantics — the glossary deliberately records **no lifecycle vocabulary**; that mapping is OQ-9 (ARB-owned, surfaced) with M4 handling the modeling side. V-2 (Qualification) → G-15 note. V-3 (Constraint≠Invariant) → G-3 note, M2. V-4 (Requirement) → not seeded; C-1 is M2's. V-5 (Conformance) → not seeded; M3 decides its kind first — *naming before kind-decision would prejudge it*. V-6 (case/form drift) → glossary uses these G-forms consistently from here.

---

## Appendix — Confidence Assessment Rubric

*(Adopted for M0 and reused by M1; per checkpoint refinement — confidence without a rubric is not reproducible.)*

| Level | Criteria |
|---|---|
| **High** | Observed consistently across multiple independent artifacts with no significant contradiction |
| **Medium** | Observed consistently but with limited corpus coverage or unresolved ambiguity |
| **Low** | Observed sparsely or with significant contradictory evidence |

Intermediate grades (Medium-High) indicate the criteria of the higher level are met except one, which is named in the entry's contradicting-evidence field.

---

*Traceability: executes WP M0 of `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) · inputs: item 1 §1.2/Q5, item 2 phase table, PA frame + scope guard (session log 2026-07-28) · checkpoint refinements applied 2026-07-28: recommendation-not-adoption wording · observational-only language (validation deferred to M1) · tactical terms retained only as cited corpus vocabulary (K-9) · confidence rubric added · companion: `PKS_Phase_II_M1_Concept_Register.md`. **STOP — M1 checkpoint review follows; no boundary, no architecture, no lifecycle vocabulary chosen here.***
