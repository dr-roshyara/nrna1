# PKS Observation — a discovery commission converged on three phases

**Recorded:** 2026-08-04. **Status: OBSERVATION — ONE OCCURRENCE. NOT PROMOTED, and not proposed for promotion.**
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=pks` → `docs/pks`.

---

## What was observed

WP-4C-2's discovery commission was not planned as a three-phase process. **It converged on one:**

| Phase | Activity | Output |
|---|---|---|
| **1** | **Evidence collection** | what the accepted artefacts already settle — and, in this case, that one question (ownership of the corrective path) **had already been decided and merely not read** |
| **2** | **Dependency analysis** | which remaining questions are independent and which are consequences — here, Q1 and Q2 are prerequisites; Q3 and Q4 are downstream |
| **3** | **Authority transfer** | a clarification package handed to the domain owner, containing questions, evidence and constraints — and **no candidate answers** |

## Why it is recorded

**Phase 1 changed the shape of the problem twice.** A-0 was found mis-framed (two responsibilities, not one owner to choose), and the Context Map turned out to answer half of it already. **Neither was discoverable by reasoning; both required INTEGRATING accepted artefacts that already held material information.**

**Phase 2 was what made the hand-off small.** Without the dependency ordering, four questions would have gone to the domain owner as a flat list; with it, two are prerequisites and the other two follow.

## Why it is NOT promoted

**ES-006.1: never promote methodology from a single occurrence.** One work package demonstrating a useful shape is not evidence that every future discovery commission should be *required* to follow it.

**Two specific reasons to distrust generalizing this one:**

1. **Phase 1's payoff depended on previously accepted artefacts containing materially relevant information that had not yet been incorporated into the current reasoning.** In this commission that information was **COL-5a**; in another it might be an ADR, a policy, or a context-map edge. **A commission whose accepted corpus holds nothing material would find nothing there, and the phase would look like wasted motion rather than the decisive step it was here.** *(Broadened 2026-08-04 at the ARB's refinement, from “there being an unread decision” — the transferable lesson is that ACCEPTED EVIDENCE EXISTED AND HAD NOT BEEN INTEGRATED, not that a decision specifically went unread. The narrower phrasing would have failed to match a future commission that found its material in an ADR rather than a map.)*
2. **Phase 2 was cheap only because the questions were few.** Four questions have a tractable dependency graph; twenty may not, and mandating the analysis could turn a useful check into a ritual.

## What would make it promotable

**A second, independent discovery commission** — different bounded context, different question class — where the same three phases arise **without being imposed**, and where phase 1 again changes the problem's shape. **Two occurrences, not one, and arising rather than applied.**

**Until then this is a programme observation, and nothing may cite it as a rule.**

## Traceability

WP-4C-2: `2026-08-04-wp4c2-discovery.md` (phase 1 · A-0…A-5) · `…-responsibility-analysis.md` (phase 1 · COL-5a) · `…-phase-b-contestation-discovery.md` (phase 2 · the guards, §7a's dependency table) · `…-domain-clarification-package.md` (phase 3) · **ES-006.1** · **R-88** · R-90 (withdrawn — the register holds constitutional decisions, not operational acceptance) · precedent for this artefact class: `docs/pks/2026-08-02-triple-qualification-timing-observation.md`.
