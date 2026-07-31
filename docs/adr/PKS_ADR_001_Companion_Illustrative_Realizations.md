# PKS-ADR-001 — Companion: Illustrative Realizations (NON-NORMATIVE)

| | |
|---|---|
| **Kind** | **Non-normative companion** to PKS-ADR-001. Extracted from that ADR's Appendix B on **2026-07-29** per Authority disposition of Knowledge Contract Review findings **KC-1, KC-2, KC-9, KC-10** and recommendation **R-1**. |
| **Authority** | **NONE. This document is illustrative only.** It is not architecture, not a constraint, not guidance, and not authority. **PKS-ADR-001 and its governing artifacts prevail over anything here without exception.** |
| **Why it was extracted** | The material illustrates the ADR's argument but **carries the implementation layer**, which PKS-ADR-001 §4 declares out of scope. Naming technology stacks inside a strategic artifact created an internal inconsistency, and two rows had additionally invented strategic content (see the corrections below). **Extraction preserves the argument by reference and restores the strategic/tactical boundary.** |
| **Standing constraint on this document** | **A non-normative statement is still a statement.** Nothing here may assert the existence of a domain concept, component, event, or boundary that the **disposed** strategic model does not contain *(KC-13 cascade, 2026-07-30)*. Additions are subject to the same rule. |

---

## 1. Purpose

PKS-ADR-001 asserts that the strategic architecture is **implementation-agnostic** in the three senses its §3.1.2 defines. The most direct way to *illustrate* that — not to prove it — is to show that the same strategic element admits different realizations under different paradigms.

**This document does that, and nothing more.** It does **not** constrain implementation architecture, imply a preferred realization, prescribe technology, or claim that any listed paradigm satisfies the strategic constraints. **Per §3.1.2's explicit non-assertion, whether a given paradigm satisfies DR-1, AP-3, or any other constraint is determined in the implementation program by demonstration.**

---

## 2. Illustrative realizations

> **NON-NORMATIVE.** Illustrative examples only · does not constrain implementation architecture · does not imply preferred realization · does not prescribe technology · each element can be realized differently within a paradigm as well as across paradigms.

| Strategic element | Java 21 (Spring Boot) | PHP 8.3 (Laravel) | AI agent (LangGraph/AutoGen) |
|---|---|---|---|
| **CBC-1 — Knowledge Assessment** *(accepted bounded context)* | Core domain service + write store | Core domain module + persistence models | Auditor agent (retrieval + AST tools) — *one possible realization* |
| **CBC-2 — Knowledge Projection** *(accepted bounded context)* | Read-model processor + cache/object store | Read-side event listeners + webhooks | Projection agent (diagram & PR bot) — *one possible realization* |
| **AR-1 — Normative region** *(architecturally UNDEFINED)* | **No realization can be given.** | **No realization can be given.** | **No realization can be given.** |
| **DR-1 — nothing may depend on AC-2** *(dependency rule, AD-1)* | Build-tool dependency prohibition | Static architectural-rule gate | Isolated prompt scope and tool set — *one possible realization* |

### 2.1 The AR-1 row is deliberately empty, and the emptiness is the illustration

**AD-1 §6.2 holds that AR-1 is an architecturally undefined region and that no component may be defined over it** — because CBC-3 is a *candidate seam*, a formal state distinct from a bounded context (MCR-2), and defining a component there would assert an encapsulation the governance withheld.

**Therefore no realization can be offered in any paradigm — and that is more informative than a filled row.** It shows that implementation-agnosticism does not mean *everything is realizable somehow*; it means **the strategic model determines what may be realized, and it withholds this one.**

*(Correction record — KC-1: the extracted Appendix B previously gave AR-1 three realizations, which asserted that the region is realizable as a component. The non-normative label limited prescription but not assertion.)*

### 2.2 Two rows were removed rather than extracted

| Removed row | Reason |
|---|---|
| **"Domain Event (VerdictIssued)"** | **KC-2 — knowledge amplification.** The **disposed** strategic model defines **no domain events**: AD-1 excludes events from scope explicitly, and C4-1's validation records zero event content. `VerdictIssued` is plausible — a Verdict is issued by a gate or review — but **plausible is not governed**, and an illustration may not introduce a strategic concept *(KC-13 cascade, 2026-07-30)*. If such an event should exist, that is a strategic discovery act. |
| **"IC-5 — Unidirectional Boundary"** *(as a "Domain Concept")* | **KC-10 — double category error.** IC-5 is an *implementation constraint* from **IBC-1**, an unissued draft; and a constraint is not a domain concept. **The governing rule is DR-1 (AD-1)**, which now appears in its place, correctly labelled as a dependency rule. |

---

## 3. What this document may never become

**Recorded because extraction creates the risk it exists to remove:** if this companion accretes technology recommendations, comparative evaluations, or realization guidance, it stops being an illustration and becomes implementation architecture — which **no artifact in the strategic chain is authorized to produce**, and which belongs to the separately governed implementation program (Implementation Process v1.0). **Growth of this document is subject to §1's standing constraint and to PKS-ADR-001 §4's limits.**

---

*Traceability: extracted from PKS-ADR-001 Appendix B on 2026-07-29 under Authority disposition of `PKS_ADR_001_Knowledge_Contract_Review.md` findings KC-1 (AR-1 realizations withdrawn) · KC-2 (invented domain event removed) · KC-9 (implementation layer relocated out of the strategic artifact) · KC-10 (IC-5 → DR-1, recategorized) and recommendation R-1 (document balance) · governing artifacts: AD-1 §6.2 · MCR-2 · PKS-ADR-001 §3.1.2 and §4 · non-normative throughout.*
