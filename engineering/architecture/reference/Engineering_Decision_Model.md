# Engineering Decision Model

**Class:** Engineering Platform architecture (reference) · **Owner:** Decision Authority
**Status:** **DRAFT** — adoption test (mirrors the EEP and Reference Architecture lifecycles): one real engineering cycle must show that engineers — human or AI — consistently resolve decisions through this model; then qualification + retrospective decide promotion to ADOPTED. The model earns adoption through use.
**Purpose:** formally define **what engineering decisions exist**, which standards answer them, how today's Engineer resolves them, and how compliance is verified. The Standards say *what the rules are*; this model says *what decisions an Engineer resolves with them*.
**Nature:** a specification, never an executor. *The architecture never executes — the Engineer consults it.* These are **Engineering Decisions, not services** — a DDD Domain Service is executable; a decision is a specification that exists independently of whoever answers it (today: an engineer reasoning; someday, perhaps, software — without changing this model).

## The universal decision pattern

```text
Question  →  Decision  →  Authority  →  Procedure  →  Evidence
(what is   (the named   (the standard (how today's  (how the outcome
 asked)     decision)    that answers) engineer      is verified)
                                       resolves it)
```

## The four-layer architecture

```text
Engineering Standards            what the platform defines        (ES-001..ES-006)
        │
        ▼
Engineering Decision Model       what decisions engineers make    (this document)
        │
        ▼
Runtime Binding                  how today's adapter is pointed   (.claude/CLAUDE.md — frozen as a pointer)
        │
        ▼
Engineer                         who decides and acts             (human or AI — the active party)
        │
        ▼
Qualification                    how compliance is checked        (ES-003; OQ instruments)
```

## The Decision Model Schema (reusable — every decision, in any catalog, is documented with it)

```text
Decision:                      the named decision
Question:                      the question the Engineer must answer
Authority:                     which standard governs this decision
Current Resolution Procedure:  how the Engineer resolves it today (the decision never changes; only its resolution evolves)
Qualification:                 how compliance is verified
Future Implementation Notes:   (optional) what would need to happen for automation
```

## The Engineering Decision Catalog

*(Who holds decision authority per rule — Machine / AI evaluates / Human decides — lives in ONE place: the Decision Authority & Verification Matrix in the Standards Index. This catalog deliberately does not repeat that dimension.)*

### DetermineConcern
- **Question:** does this belong to Product, Engineering, or Runtime?
- **Authority:** ES-005.1 (Three-Concern Separation)
- **Current resolution procedure:** apply the three-concern table
- **Qualification method:** OQ structural checks

### DetermineArtifactType
- **Question:** is this a standard, a guide, a pattern, a record, or research?
- **Authority:** ES-004 (Documentation) · ES-006 (Engineering Knowledge Governance) · ER-09 when ratified
- **Current resolution procedure:** match against the authorized-type index
- **Qualification method:** OQ documentation checks

### DeterminePlacement
- **Question:** where should this artifact live?
- **Authority:** ES-005.3 (Placement Litmus) · ES-005.2 (Folder Rule)
- **Current resolution procedure:** litmus ("adoptable unchanged by another project?") → folder rule (first-artifact) → placement
- **Qualification method:** OQ structural instruments (repository qualification)

### DetermineApplicableStandards
- **Question:** which ES documents govern this work?
- **Authority:** STANDARDS_INDEX
- **Current resolution procedure:** read the index's one-line table
- **Qualification method:** EP-02 completion review

### DetermineQualificationMethod
- **Question:** how will compliance with the governing standard be known?
- **Authority:** each standard's own Qualification Method header
- **Current resolution procedure:** read the governing standard's header
- **Qualification method:** ES-003 lifecycle (re-runs; verdict-history audits)

### DeterminePromotionPath
- **Question:** does this work create or promote a rule?
- **Authority:** ES-006.1 (Promotion Ladder)
- **Current resolution procedure:** a guide teaches rules and never creates them; anything rule-creating enters the ladder (pilot → qualification → recommendation → **ARB decides**)
- **Qualification method:** promotion-ladder audits
- **Future implementation notes:** none — promotion is a human governance act and is never automated

## Decision Catalogs (how future domains reuse this architecture)

Standards sets are **decision catalogs over the same decision architecture** — not separate frameworks:

| Catalog | Answers | Status |
|---|---|---|
| Engineering Standards (ES-001..006) | the Engineering Decisions above | this catalog |
| Project Knowledge Standards (PKS-class, future) | Knowledge Decisions — e.g. *DetermineKnowledgeNeed · DetermineKnowledgeSource · DetermineKnowledgeRepresentation · DetermineKnowledgeQualification · DetermineKnowledgePromotion* | **pilot-gated candidates** — arrive only with pilot evidence; same pattern (Question → Decision → Authority → Procedure → Evidence), different domain |

Worked example of the same pattern in the future domain: *Question:* "what knowledge is needed for this task?" → *Decision:* DetermineKnowledgeNeed → *Authority:* PKS (future) → *Procedure:* need derivation (per the Strategic Model) → *Evidence:* pilot qualification.

## The stopping rule

> **The Engineering Decision Model is complete. No new engineering decisions will be added unless operational evidence demonstrates insufficiency in the existing set.** If a new decision appears, the first question is: **"Which existing decision does this extend?"** — never "Should we add another decision?" (ES-001.1 parsimony, applied to the decisions themselves.)

## The automation stance

> **Automation is an implementation of governance, never governance itself.** *(An architectural statement of this model — NOT constitutional. Per the ARB (2026-07-11), it follows the same promotion path as everything else: Research → Use → Qualification → Adoption. It becomes constitutional only if operational evidence earns it that status; parsimony note: it generalizes R-26 + "governance precedes automation".)*

Consequences, all in force: decisions are documented, never coded · machine verification lives in qualification instruments (periodic), not resident daemons · the AI **evaluates and recommends** — authority remains with governance (ES-001.2) · a decision procedure earns software implementation only through the promotion ladder, with evidence that reasoning-over-standards fails.

---
*Traceability: ARB decision-model commissions + the services→decisions vocabulary correction (2026-07-11: "a Domain Service is executable; these are specifications"). Per the ARB closing order, platform refinement stops with this document; its own adoption is earned through one real engineering cycle.*
