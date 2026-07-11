# Engineering Decision Model

**Class:** Engineering Platform architecture (reference) · **Owner:** Decision Authority
**Status:** **DRAFT** — adoption test (mirrors the EEP and Reference Architecture lifecycles): one real engineering cycle must show that engineers — human or AI — consistently resolve decisions through this model; then qualification + retrospective decide promotion to ADOPTED. The model earns adoption through use.
**Purpose:** formally define **what engineering decisions exist**, which standards answer them, how today's Engineer resolves them, and how compliance is verified. The Standards say *what the rules are*; this model says *what decisions an Engineer resolves with them*.
**Nature:** a specification, never an executor. *The architecture never executes — the Engineer consults it.* These are **Engineering Decisions, not services** — a DDD Domain Service is executable; a decision is a specification that exists independently of whoever answers it (today: an engineer reasoning; someday, perhaps, software — without changing this model).
**This model is a decision INDEX, never a second rulebook** *(ARB, 2026-07-11)*: every entry points to its Authority and names the resolution procedure in one line — it never restates rule text. The rules live once, in the ES documents; if an entry ever seems to need more than a one-line procedure, that text belongs in the governing standard, not here (rules-live-once, applied to this model's own content — otherwise Standards, Decision Model, and Runtime would slowly duplicate the same authority).

## The universal decision pattern

```text
Question  →  Decision  →  Authority  →  Procedure  →  Evidence
(what is   (the named   (the standard (how today's  (how the outcome
 asked)     decision)    that answers) engineer      is verified)
                                       resolves it)
```

## The layered architecture

*(ARB refinement, 2026-07-11: the Reference Architecture and this Decision Model are **complementary siblings, not a hierarchy** — the Reference Architecture explains what the platform IS; the Decision Model explains how engineering decisions are RESOLVED. Neither depends on the other; both depend on the Standards.)*

```text
                 Engineering Standards                (ES-001..ES-006 — the rules, stated once)
                  /                 \
                 ▼                   ▼
Reference Architecture       Engineering Decision Model
(what the platform is)       (how decisions are resolved — this document)
                  \                 /
                   ▼               ▼
          Engineering Execution Protocol              (how work is performed)
                     │
                     ▼
             Runtime Binding                          (.claude/CLAUDE.md — frozen as a pointer)
                     │
                     ▼
                 Engineer                             (human or AI — the active party)
                     │
                     ▼
              Qualification                           (ES-003; OQ instruments)
                     │
                     ▼
                 Evidence
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

### DetermineReusePotential
- **Question:** did this work produce reusable engineering knowledge?
- **Authority:** ES-006.4 (The Harvest Question)
- **Current resolution procedure:** ask the question at completion (EP-02 / retrospective) and answer honestly — **"No" is the common, fully valid outcome** (continue work, harvest nothing); only "Yes" proceeds to DetermineArtifactType. Never ask "can we create a new rule?" — the platform seeks knowledge, not rules
- **Qualification method:** EP-02 review
- *(Provenance: ARB-ordered split of DetermineArtifactType, 2026-07-11 — entered through the stopping rule's own question, "which existing decision does this extend?": first decide whether anything is worth keeping, only then what kind of thing it is.)*

### DetermineArtifactType
- **Question:** what should this reusable knowledge become — a pattern card, a guide, a qualification improvement, a candidate standard, or research? *(reached only after DetermineReusePotential = Yes; "on reflection, nothing" remains a valid answer)*
- **Authority:** ES-004 (Documentation) · ES-006 (Engineering Knowledge Governance) · ER-09 when ratified
- **Current resolution procedure:** match against the authorized-type index — the right representation emerges from the evidence; a standard is only one possible destination
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
*Traceability: ARB decision-model commissions + the services→decisions vocabulary correction (2026-07-11: "a Domain Service is executable; these are specifications") + ARB acceptance record (2026-07-11: **Decision: Accepted**, four minor recommendations — Current Resolution Procedure rename · decision-index-not-second-rulebook · automation stance held architectural · conceptual freeze — all applied) + the harvest amendment (2026-07-11: objective is "discover reusable engineering knowledge", never "extract rules"; DetermineReusePotential split out of DetermineArtifactType by ARB order; ES-006.4). Platform refinement stops with this document; its own adoption is earned through one real engineering cycle.*
