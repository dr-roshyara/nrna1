# Round 49 / LIT-3 — Strategic DDD Validation Review (v1.1, epistemic positioning)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD validation (Phase II) · **Under MB-39.1 (frozen)**
**Status:** 📚 EPISTEMIC POSITIONING DOCUMENT — *what does the literature actually support?* (not "can I find agreement?"). Positions the Round 47–49 Strategic DDD decisions; **does not redesign governance.** Belongs in a dissertation's **Related Work / Research Gap**, not the architecture chapter.
**Date:** 2026-06-26 · *(v1.0→v1.1: Source→Says→Interpretation→Decision chains; Evidence Gap Matrix; Contribution Taxonomy; literature maturity scale; external validity; program position; reviewer challenges; Current-State-of-Knowledge table.)*

> **Verification status.** Citations are **training-knowledge, not retrieval-verified** this session; **[verify]** against primary sources before publication. An independent retrieval (Perplexity, 2026-06-26) confirmed only **2 sources** (a DDD systematic review; a distributed-SOA governance paper) — confirming standard DDD (UL/BC/Domain-Events) + the generic need for governance. **Current literature provides strong support for general DDD principles but limited evidence regarding governance-oriented strategic decomposition in high-assurance election systems; the remaining claims therefore require empirical validation.** *(Neutral framing — replaces "under-instrumented.")*

## 1. Source → What the source says → Our interpretation → Decision

*(Per review: separate the literature from our interpretation of it.)*

| Source [verify] | What the source actually says | Our interpretation | Decision |
|-----------------|-------------------------------|--------------------|----------|
| **Evans 2003** (foundational) | BC, Ubiquitous Language, context-map patterns (Conformist/ACL/OHS/PL/C-S/Shared-Kernel/Separate-Ways) | our patterns use the same vocabulary | **consistent — adopt** |
| **Vernon 2013** (industrial) | event-driven context integration; eventual consistency | supports our event-driven map | **consistent — adopt** |
| **Brandolini 2018** (discovery) | boundaries emerge along event-ownership | supports ownership-seam heuristic | **consistent** |
| **Young 2010/2017** (CQRS) | read models project from events; never own truth | supports Results/Legitimacy as read models | **consistent — adopt** |
| **Fowler 2011** (opinion) | a concept differs across contexts; don't force one representation | supports decomposing "Independence" by facet | **consistent** |
| **Rozanski & Woods 2012** (foundational) | cross-cutting concerns = perspectives, not components | supports Anonymity-as-invariant | **consistent — adopt** |
| **Ford et al. 2017** (industrial) | fitness functions encode (incl. negative) constraints | supports Forbidden Transformations as fitness functions | **consistent — adopt** |
| **Passos et al. 2010** (empirical, IEEE) | reflexion model: convergence/divergence/absence | **compatible with EBSD's conformance stage** | **adopt + extend** |
| **Nygard 2011** (industrial) | ADRs: immutable decision records | GI / BDR are ADR-analogues | **consistent — adopt** |
| **Cortier et al. 2016** (empirical, IEEE) | e-voting decomposes cryptographically/procedurally | **does not address governance decomposition** | **gap — candidate contribution** |

## 2. Evidence taxonomy + DDD-literature maturity layers

**Kinds:** empirical (Passos/Martini/Cortier — strongest) · foundational books (Evans/Vernon/Young) · industrial practice (Ford/Nygard/CQRS) · opinion (Fowler). **DDD layers (not interchangeable):** Evans = foundational *theory* · Vernon = industrial *refinement* · Brandolini = *discovery techniques* · Ford = architecture *evolution* · Young = *CQRS/event-sourcing* · Passos = *empirical evaluation*.

## 3. Disagreements / competing viewpoints
Event-sourcing vs CRUD · large vs small contexts · centralized (our KRG) vs autonomous-team governance · CQRS-everywhere vs selective · Shared-Kernel hazard (we use Published Language, not Shared Kernel). Our choices sit within these live debates — justified by high-assurance needs, not asserted as consensus.

## 4. Contribution taxonomy (separate kinds of novelty) — *appears* novel [verify]

| Kind | Candidate contributions |
|------|-------------------------|
| **Novel theory** | Knowledge Certification; constitutional Meta-CVI; consent trust-anchor |
| **Novel method** | **EBSD** (Evidence-Based Strategic DDD Discovery); BC Evaluation Framework (falsification, weighted tests) |
| **Novel architecture** | governance contexts (**Appointment · Contestation · Adjudication** as first-class BCs in a voting system) |
| **Novel process** | Knowledge Release Governance; Translation Assurance; Architecture Change Protocol |
| **Novel translation** | Semantic Ownership (incl. *negative* ownership); Semantic Projection; Forbidden Transformations |

*"Appears novel" = to the best of current (unretrieved) knowledge; absence of precedent ≠ proof of novelty. Each requires empirical validation, not literature validation.*

## 5. Literature maturity scale (per topic: current → target)
**L0** none · **L1** foundational · **L2** industrial · **L3** empirical (single study) · **L4** systematic review · **L5** meta-analysis.

| Topic | Current | Target |
|-------|---------|--------|
| Context-mapping patterns | L1–L2 (+L4 DDD SLR for BC/events) | L3 (our specific patterns) |
| Ownership → contexts | L1–L2 | L3 |
| Gap-analysis method | **L3** (Passos) | maintain |
| High-assurance DDD | L1–L2 | L3 |
| Voting governance contexts | **L0** | L3 |
| UL / terminology governance | L1 (+L4 SLR for UL) | L3 |
| Architecture governance / KRG | L1–L2 | L3 |

## 6. Evidence Gap Matrix (where literature ends, the program begins)

| Topic | Literature | Gap | Program response |
|-------|-----------|-----|------------------|
| BC relationships | Partial (Evans/Vernon) | no high-assurance/regulated-domain evidence | empirical validation (EBSD/L3) |
| Ownership → contexts | Partial (Young/Fowler) | semantic/negative ownership absent | candidate contribution |
| Gap analysis | Empirical (Passos) | not extended to a governance ontology | adopt + extend (EBSD) |
| High-assurance DDD | Partial | anonymity-invariant / never-persisted / external-anchor specifics absent | candidate + empirical |
| Voting contexts | **None** | governance decomposition absent | candidate contribution |
| Terminology governance | Partial | versioned vocabulary / synonym control absent | candidate (KRG / Canonical Vocabulary) |
| Architecture governance | Partial (Nygard/Ford) | no KRG / certification-boundary equivalent | candidate + empirical |

## 7. External validity (limitations)
Validated **for NRNA's class** (voluntary, online, anonymous, no external sovereign). **Not yet validated** for national/governmental elections; **not validated** for other governance systems (corporate/DAO/sacral — cf. `Round42B`). Single-analyst; static; pre-implementation.

## 8. LIT-3's position in the literature program
```
LIT-2 (translation, R46) → R47 → LIT-SYS (explanation, R47) → R49 → LIT-3 (architecture validation, this)
   → R50 → LIT-4 (engineering/secure-voting) → LIT-EVAL (architecture evaluation) → LIT-5 (publication)
```

## 9. Anticipated reviewer challenges
| Challenge | Literature | Program response |
|-----------|-----------|------------------|
| "Why not merge Appointment?" | no answer | Round 49 EBSD evaluation (falsification) |
| "Why governance contexts?" | no precedent | candidate contribution → empirical validation |
| "Is EBSD novel or just reflexion?" | reflexion = 1 of 4 perspectives | EBSD adds behavioral/architectural/Strategic-DDD + falsification → candidate method |
| "Is the architecture validated?" | literature can't validate it | **only** empirical (L3 + BDR) can |

## 10. Current State of Knowledge (summary)

| Topic | Literature | Program | Remaining work |
|-------|-----------|---------|----------------|
| Standard DDD (UL/BC/events) | **Strong (L4)** | Consistent | done |
| Context-mapping patterns | Foundational/Industrial | Consistent | L3 empirical |
| Ownership heuristics | Industrial/Opinion | Consistent + extends | empirical |
| Gap-analysis method | Empirical | Adopted + extended (EBSD) | validate EBSD |
| Governance decomposition | **None** | Candidate contribution | empirical |
| EBSD method | **None** | Candidate | validate |
| KRG / Certification boundary | **None** | Candidate | validate |
| Governance contexts | **None** | Candidate | validate |

## 11. Conclusion
The literature **strongly supports general DDD principles** (UL/BC/events, ownership heuristics, fitness functions, ADR governance) — our decisions are **consistent with** it. The **governance-centered decomposition (Appointment/Contestation/Adjudication), EBSD, KRG, and Knowledge Certification appear to be novel candidate contributions requiring empirical — not literature — validation.** The decisive next knowledge comes from **EBSD + behavioral (L3) verification + the Boundary Decision Register**, which will determine which candidates mature into validated contributions.

---

*Round 49 / LIT-3 v1.1 — Strategic DDD Validation Review — ISSUED (epistemic positioning; [verify]).*
*Source→Says→Interpretation→Decision chains; Evidence Gap Matrix; Contribution Taxonomy (Theory/Method/Architecture/Process/Translation); literature maturity L0–L5 (current→target); external validity (NRNA class only); program position; reviewer-challenge anticipation; Current-State-of-Knowledge table. Retrieval confirms standard DDD + generic governance; governance decomposition/EBSD/KRG = candidate contributions awaiting empirical validation. Belongs in dissertation Related-Work/Research-Gap.*
