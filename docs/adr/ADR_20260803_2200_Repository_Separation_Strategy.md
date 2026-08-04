# ADR — Repository Separation Strategy

| | |
|---|---|
| **Status** | ⚠️ **PROPOSED — awaiting Decision Authority** *(drafted from the Principal-Architect review, 2026-08-03; nothing here executes until accepted)* |
| **Class** | Repository / delivery architecture |
| **Decision drivers** | the recurring question *"should KnowledgeOS / PKS / PublicDigit live in separate repositories?"* — answered by rule rather than re-litigated per mood |

## Decision

> **KnowledgeOS, PKS, and PublicDigit remain in ONE repository throughout the discovery and validation phase.**
> ⭐ **The governing rule, stated at its true breadth (REV 2, review 2026-08-03): PHYSICAL SEPARATION FOLLOWS VALIDATED ARCHITECTURAL BOUNDARIES.** *Repositories are one manifestation; the same rule governs separate deployments · databases · images · services · APIs. Never anticipated architecture.*

## Rationale

1. **The boundaries are still stabilizing** — the ontology is CANDIDATE, the docket is unruled (0/11), the second-adopter gate is closed. A repository split would freeze boundaries the architecture has not yet earned.
2. ⭐ **This is the platform's own standing principle applied to repositories:** *separate when evidence shows the boundary is stable, not when the idea of separation becomes attractive* — the same rule as the second-adopter gate, structure-follows-demonstrated-need (spike REV 5), and evidence-before-architecture.
3. **Premature splits create synchronization work without creating evidence** — the same argument that refused the Python rewrite.

## Repository Readiness Levels *(the roadmap — each level gated, none scheduled)*

| Level | State | Gate to advance |
|---|---|---|
| **0 · Monorepo** | everything together | — |
| **1 · Logical separation** | clear ownership, per-concern roots, own tests/readmes | ⭐ **SUBSTANTIALLY ACHIEVED ALREADY — recorded, not planned:** ES-005.1's three-concern separation *(product · `engineering/` platform · `.claude/` runtime mount)* · three registered documentation roots *(`publicdigit` · `knowledgeos` · `pks`)* · `app/Contexts/*` bounded-context layout · separate test suites *(Architecture · GreenfieldCore)*. Remaining gaps close opportunistically, never as a project |
| **2 · Build separation** | each component builds/tests independently | a component that actually needs an independent build *(none today)* |
| **3 · API separation** | interaction via explicit contracts *(`POST /observations`, never `KnowledgeOS::analyse()`)* | ⭐ the observation-JSON boundary already staged in the metrics spike *(Phase 2+, real-usage gate)* |
| **4 · Repository separation** | separate repos + SDKs | **ALL exit criteria below** |

## Exit criteria *(Level 4 opens only when every one holds)*

- APIs stable · release cycles independent · tests independent · configuration independent
- ⭐ **operational evidence demonstrates reuse outside PublicDigit** *(the second-adopter gate — the same gate as everything else)*
- ⭐ **INDEPENDENT OWNERSHIP AND OPERATION** *(REV 2)* — *distinct owners who release and operate the component separately in practice. If the same people always release both together, a split creates operational overhead without benefit — the component must be operable independently, not merely buildable independently*

## Consequences — how code is written TODAY

> **Code as if already separated; live as if never separating.**

- cross-boundary interaction goes through **contracts/adapters** *(`ObservationPublisher` → adapter)*, never through another component's internal classes — *the adapter binds in-repo today, over REST tomorrow, and the caller never notices*
- the metrics tool's staged migration path (PHP reference implementation → observation JSON → language-neutral services) is this ADR's Level-3 instance, already gated
- **language mapping recorded as ANTICIPATED DIRECTION, not decided** *(PublicDigit PHP · KnowledgeOS/PKS likely Python · SDKs multi-language)* — each language choice is its own future decision at its own gate
- ⭐ **REV 3 (2026-08-03) — the one direction sentence, recorded exactly and nothing more:** *"If the engineering platform is extracted from PublicDigit in the future, **Python is the preferred implementation language** because it enables a language-agnostic observation platform and aligns with the broader AI and analysis ecosystem. **This is an architectural direction, not an implementation decision, and remains gated by the repository-separation criteria.**"* ⛔ *No package structure · no API · no collectors directory · no modules — the design is earned at the gate, from observed needs (the PHP collector · usage logs · operational evidence), never from today's expectations. Existing tools become ADAPTERS of the extracted platform, never technical debt to be removed.* ⭐ *The argument in one picture: PHP/Java/.NET/Go adapters → one platform — **Python is not replacing PHP; Python hosts the language-neutral platform that all languages feed.***

## What this ADR prevents

⛔ *"We should split the repositories because it feels cleaner"* — from anyone, including future maintainers and future AI sessions. **The split is earned through the exit criteria or it does not happen.**

---

*Traceability: drafted 2026-08-03 from the Principal-Architect repository-separation review · check-before finding: Level 1 substantially pre-exists via ES-005.1 + registered doc roots + `app/Contexts` + split test suites · consistent with: the second-adopter gate · structure-follows-demonstrated-need (spike plan REV 5) · the refused Python rewrite (same evidence logic) · the metrics spike's staged Phase 2+ (this ADR's Level 3). **Status PROPOSED — the Decision Authority accepts, amends, or rejects.***
