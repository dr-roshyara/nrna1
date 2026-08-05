# Knowledge Boundary Charter — Engineering Knowledge ↔ Project Knowledge

**Kind:** Reference-Architecture charter (one page) — NOT an Engineering Standard (ES-007 is forbidden by the stopping rule); NOT a modification of ES-001..006. **Status:** PROPOSED — awaiting ARB review.
**Question this charter answers:** *How do Engineering Knowledge and Project Knowledge interact without becoming coupled?*
**Relation (rules live once):** refines `Project_Knowledge_Strategic_Model.md` §5 (the platform-level sibling map) for the **knowledge-to-knowledge** boundary specifically; the promotion machinery is ES-006.1 — referenced, never restated.

## 1. The two bounded contexts

```text
        General Knowledge Constitution
                    │
     ┌──────────────┴──────────────┐
     │                             │
Engineering Knowledge        Project Knowledge
"learns how to engineer"     "learns how to build Project X"
```

| Property | **Engineering Knowledge** | **Project Knowledge** |
|---|---|---|
| Purpose | improve how engineering is done — anywhere | make one project understandable and safely extensible |
| Owner | Decision Authority (platform) | the project (its ARB/team) |
| Scope | patterns, evidence, standards candidates, promotion machinery (ES-006) | ubiquitous language, decisions, rules, construction knowledge, memory, context assembly |
| Exports | proven patterns · standards · the promotion ladder · qualification methods | lessons · evidence · qualification results · falsifications |
| Imports | project lessons/evidence **as harvest input** | engineering patterns/standards **as architectural inputs** |
| Qualification | promotion-ladder audits (ES-006 method) | the pilot + Context Evaluation (U/O) + validation-by-use |
| Lifecycle | harvest → pattern → evidence → standard → stable (ES-006.1) | claim lifecycle per the Constitutional Model; theory frozen at falsifiability |

## 2. The interaction model

| Direction | Interaction | Carrier |
|---|---|---|
| Engineering → Project | patterns, standards, promotion mechanisms consumed as **inputs, never mandates on content** (standards govern *how*, never *what the project means*) | ES documents · pattern cards (read) |
| Project → Engineering | lessons, evidence, qualification results consumed as **harvest input, never as standards** until promoted | retrospectives · qualification records · findings (harvested) |
| Promotion | a project concept becomes engineering knowledge **only** through ES-006.1 (pilot → qualification → recommendation → ARB) | the ladder — the boundary's single legal crossing |
| Prohibited | direct adoption in either direction without qualification · shared ownership of any artifact | — |

## 3. The promotion path

**The path IS ES-006.1 applied across the boundary** — no separate machinery: `Project Knowledge → Pilot → Qualification → Promotion Recommendation → ARB Review → Engineering Knowledge Standard.` Precedent already exists: P-1/P-3 (PB-007 principles) crossed exactly this path; ES-005.4 (Never-a-Copy) is currently *mid-crossing* — a project-knowledge invariant held as an engineering candidate pending pilot evidence. The boundary is not theoretical; it has traffic.

## 4. The boundary rule

> **Engineering Knowledge never owns project-specific artifacts. Project Knowledge never owns engineering standards.**

Corollaries: adoption evidence stays project property forever (even after its lesson is promoted, the *evidence record* remains the project's — engineering gets the pattern, not the files) · a standard citing project evidence cites by reference (never-a-copy) · shared infrastructure (repo, tooling) never merges the contexts (Strategic Model §5).

## 5. Open questions (pilot-gated — cannot be answered by more design)

| Question | Evidence required |
|---|---|
| Does the promotion path hold under real traffic? | the Project Knowledge pilot (its first promotion attempt) |
| Does ES-005.4 (Never-a-Copy) survive cross-domain? | pilot evidence — it is the test case in flight |
| Are the two contexts truly separate in practice, or does daily work blur them? | pilot + the ~20–30-task horizon |
| What does the boundary cost (translation overhead, duplicate-feeling records)? | operational evidence |

**STOP — ARB review. After ratification + C3 + STABLE, the pilot supplies these answers; PKS-class standards arrive only if the evidence warrants them.**
