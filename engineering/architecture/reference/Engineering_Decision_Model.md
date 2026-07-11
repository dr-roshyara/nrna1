# Engineering Decision Model

**Class:** Engineering Platform architecture (reference) · **Status:** PROPOSED — awaiting ARB review · **Owner:** Decision Authority
**Purpose:** formally define **what engineering decisions exist**, which standards answer them, who holds decision authority, how outcomes are verified — and how future domains specialize the same model. The Standards say *what the rules are*; this model says *what decisions an Engineer resolves with them*.
**Nature:** a specification, never an executor. *The architecture never executes — the Engineer consults it. The platform provides sufficient information for an Engineer (human or AI, any provider) to determine the correct action.*

## The four-layer architecture

```text
Engineering Standards            what the platform defines        (ES-001..ES-006)
        │
        ▼
Engineering Decision Model       what decisions engineers make    (this document)
        │
        ▼
Runtime Binding                  how today's adapter is pointed   (.claude/CLAUDE.md — pointers only)
        │
        ▼
Engineer                         who decides and acts             (human or AI — the active party)
        │
        ▼
Qualification                    how compliance is checked        (ES-003; OQ instruments)
```

## Service vs Procedure (the anti-premature-implementation split)

A **Decision Service** is a logical capability — a named question every engineering action must resolve. A **Decision Procedure** is how *today's* Engineer answers it (reasoning over the standards). A future implementation (a `PlacementService`) may replace a procedure **without changing this model** — and may exist only when evidence demonstrates that reasoning-over-standards fails (ES-006.1; automation eligibility per the Standards Index matrix).

## The six Engineering Decision Services

| Service | The question | Answered by | Decision authority | Verified by | Today's procedure |
|---|---|---|---|---|---|
| **DetermineConcern** | Product, Engineering, or Runtime? | ES-005.1 | AI evaluates | OQ structural checks | apply the three-concern table |
| **DetermineArtifactType** | standard · guide · pattern · record · research? | ES-004, ES-006 | AI evaluates | OQ documentation checks | match against the authorized-type index (ER-09 when ratified) |
| **DeterminePlacement** | where does it live? | ES-005.3 litmus + ES-005.2 folder rule | AI evaluates | OQ structural checks (Machine) | litmus → folder rule → placement |
| **DetermineApplicableStandards** | which ES documents govern this work? | STANDARDS_INDEX | AI evaluates | EP-02 review | read the index's one-line table |
| **DetermineQualificationMethod** | how will compliance be known? | each standard's own Qualification Method header | AI evaluates + Human accepts | ES-003 lifecycle | read the governing standard's header |
| **DeterminePromotionPath** | does this create/promote a rule? | ES-006.1 | **Human decides** (AI recommends) | promotion-ladder audits | a guide teaches rules and never creates them; anything rule-creating enters the ladder |

## Specialization (how future domains reuse this model)

Domains do not get second decision processes — they add **rules behind the same services** and, where their domain demands it, **specialized services derived from the model**. The Project Knowledge candidates (pilot-gated, NOT defined here): `DetermineKnowledgeNeed · DetermineKnowledgeSource · DetermineKnowledgeRepresentation · DetermineKnowledgeQualification · DetermineKnowledgePromotion` — specializations of the same shape, arriving only with pilot evidence and PKS-class rules. One decision model; per-domain answers.

## The automation stance

> **Automation is an implementation of governance, never governance itself.** *(Recorded here as a ratification candidate for constitutional status — ARB decides at ES ratification; parsimony note: it generalizes the R-26 instrument rule + "governance precedes automation" already stated in the Reference Architecture.)*

Consequences, all in force: decision services are documented, not coded · machine verification lives in qualification instruments (periodic), not resident daemons · the AI **evaluates** compliance and **recommends** — authority remains with governance (ES-001.2) · a service earns software implementation only through the promotion ladder with evidence of reasoning-failure.

---
*Traceability: ARB decision-services correction + Decision Model commission (2026-07-11) · Enforcement/Decision-Authority matrix (Standards Index) · Enforcement classification evidence (OQ-ENG-002 era). This document completes the Engineering Platform's conceptual architecture; per the ARB's closing order, platform refinement STOPS here — further change requires qualification findings, retrospective rulings, or operational evidence.*
