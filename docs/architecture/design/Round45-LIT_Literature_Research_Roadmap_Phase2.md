# Round 45-LIT — Literature Research Roadmap (Phase 2: Validation-Driven)

**Program:** NRNA DDD Trustworthiness Research Program · **Under MB-39.1 (frozen)**
**Status:** 📚 ROADMAP (descriptive policy). The methodology-rule extension is a **Draft ADR (DA-LIT-01)** — *proposal only*, for the Methodology Governance Review; **not enacted** (MB-39.1 frozen).
**Date:** 2026-06-26

> **The shift.** Literature's role changes as the program matures. **Phase 1 (Rounds 38–44): discovery-driven** — literature *expanded the design space* under sketch-before-literature (ADR-M-011). That phase is **complete.** **Phase 2 (now onward): validation/question-driven** — literature is consulted only for a **specific uncertainty**, to **position / stress-test / supply terminology** — never to discover or to redefine settled theory.
> **Decision (this round):** do **NOT** perform another broad literature review now. The next review is **targeted**, scheduled **after Round 46 (Strategic DDD Readiness) and before Strategic DDD.**

---

## 1. The two roles (Phase 1 done → Phase 2 active)

| | Phase 1 — discovery-driven (done) | Phase 2 — validation-driven (now) |
|---|-----------------------------------|-----------------------------------|
| Workflow | Question → Sketch → Literature → Mechanisms → hostile discovery → theory | Finding → specific RQ → **targeted** review → Support/Contradiction/Alternative/Boundary → decision |
| Searches for | "everything about governance" (design-space) | **one specific uncertainty** |
| Purpose | expand design space | **positioning · stress-testing · terminology** |
| Authority | informs, never dictates | informs, never dictates (unchanged) |

## 2. Triggers (the only events that warrant a review now)

- **Trigger A — a Stable theory appears** → *mapping* exercise: "what is the relationship between our concept (e.g. Finality, Trust-Anchor, the 4 Independences, emergent Legitimacy) and existing scholarship?" — not "is ours correct?"
- **Trigger B — an unresolved RQ** (e.g. RQ-EL-01 Eligibility) → targeted review (election law / franchise theory) to *widen or challenge* the search space before discovery.
- **Trigger C — before Strategic DDD** → review of *translation* scholarship (ontology engineering, enterprise ontology, semantic projection, DDD semantics, computational/conceptual modeling) to compare our projection approach with established ones.
- **Trigger D — before publication** → position every contribution against prior art.

## 3. Scheduled targeted reviews (three, not one) — REVISED ordering

**Revision (2026-06-26):** internal readiness is decided **before** literature calibration. `Round46A` (Internal Readiness — internal evidence only) precedes LIT-2; `Round46B` (Final Gate) follows it.

```
Ontology v1.0 ✓ / Projection (44) ✓ / Ownership (45) ✓ / Package v1.0 (46) ✓
   → Round 46A  INTERNAL Readiness Decision  ✓   [internal evidence ONLY — no literature]
   → LIT-2 (TRANSLATION & TERMINOLOGY review)     [Trigger C — external calibration; NO governance redesign]
   → Round 46B  FINAL Strategic DDD Gate Decision [binary READY/NOT-READY incl. terminology calibration]
   → Round 47 Strategic DDD Discovery
   → LIT-3 (DDD / architecture validation)        [strategic DDD · context mapping · EA · high-assurance/secure-voting]
   → Tactical DDD → Implementation
   → LIT-4 (publication positioning)              [Trigger D]
```

### LIT-2 charter (tightened — external calibration, NOT research)
**Forbidden:** changing the governance theory, ontology, or admissibility (that work is finished). **Objective:** compare the **Domain Knowledge Package v1.0** against ontology engineering · enterprise ontology · conceptual modeling · semantic projection · strategic DDD · context mapping · enterprise architecture, to answer: (1) which terminology already exists? (2) which concepts are genuinely novel? (3) which correspond to established theory? (4) which should be renamed for interoperability? (5) where does literature disagree? (6) does any literature expose an overlooked limitation? (7) does any literature invalidate the **translation strategy** (not the theory)?

**New LIT-2 deliverable — Terminology Mapping** (example shape):

| Our term | Literature equivalent (candidate) | Action |
|----------|-----------------------------------|--------|
| Semantic Projection | ontology→model transformation / model-driven transformation | adopt if apt |
| Truth-of-record | System of Record | align |
| Truth-of-computation | Derived View / Projection | align |
| Mandate | Delegation / Authority Assignment | compare |
| Trust-Anchor | Root of Trust (where applicable) | clarify difference |
| Independence (4 facets) | governance/constitutional/org-theory taxonomies | check for existing taxonomy |

*A limitation or terminology finding from LIT-2 may rename concepts for interoperability or be recorded as a risk — it may **not** reopen the governance theory.*

## 3b. Full literature roadmap (LIT-2 … LIT-5 + Systems-Theory) — extended

Literature is now a **specialized validation instrument** whose role changes by phase. **Never again** a broad "everything about election/constitutional governance" review (would contaminate the independently-certified theory).

| Review | When | Purpose | Status |
|--------|------|---------|--------|
| **LIT-2** Translation + **conceptual positioning + boundary comparison** | before/at Phase II start | align terms **and position the work** vs ontology eng. / enterprise ontology / conceptual modeling / DDD semantics (terminology + where it sits + similarities/differences/boundaries) — never redesign theory | ✅ **DONE** (`Round46-LIT2` + addendum) |
| **LIT-SYS** Systems-Theory positioning *(dedicated, not optional)* | early (positions certified meta-arch) | strengthen the *scientific explanation* of the control-system architecture (cybernetics/Ashby/VSM/Luhmann) — **no model change** | ✅ **DONE** (`Round47-LIT-SYS`) |
| **LIT-3** Strategic/Tactical DDD validation | after Round 49–50 | validate context boundaries vs Strategic DDD / context mapping / Team Topologies / large-scale DDD | pending |
| **LIT-4** Engineering / secure-voting | during Tactical DDD + impl | event-sourcing/CQRS/aggregates/hexagonal; secure-voting; Laravel/Postgres/crypto | pending |
| **LIT-EVAL** Empirical architecture evaluation *(new)* | after implementation, **before** LIT-5 | how to *demonstrate the architecture works*: architecture assessment (ATAM-style), evaluation methods, experiments, case studies | pending |
| **LIT-5** Publication positioning | before dissertation/papers | position vs Evans/Vernon/Brandolini/DEMO/ArchiMate/voting & systems literature | pending |

**Sequence (revised):** LIT-2 ✓ → Strategic DDD → LIT-SYS ✓ → Tactical DDD → LIT-3 ✓ → Implementation → LIT-4 → **LIT-EVAL** → LIT-5.

### Milestone-anchored schedule (confirmed) — ~90% implementation / ~10% literature
- **During Round 50:** **NO major review.** Only **1–5 paper targeted searches** if a specific aggregate question arises (e.g. consistency boundaries, event versioning, repository patterns, immutable-evidence modeling).
- **LIT-METHOD** — **after Round 50 + the first implementation milestone** (so it validates an *implemented, evidence-backed* method, not a proposed one): architecture recovery · reflexion-model evolution · Strategic-DDD evaluation · boundary-discovery · architecture conformance · empirical software architecture · fitness-function research · evidence-driven architecture. *Validates the methodology (EBSD), not the software.*
- **LIT-4** — **after the greenfield Core (Adjudication + Contestation) is implemented**: compare the *real* architecture vs election/secure-voting/high-assurance/event-driven/DDD/constitutional-governance literature.
- **LIT-5** — **before dissertation/publication**: Related-Work chapter (what exists / what remains / where this differs / what's novel / what evidence supports it).
*Rationale: literature stays aligned with evidence the project produces, rather than driving implementation prematurely.* All are **validation/positioning**, never discovery; none reopens the governance theory.

**Governance-literature rule (refined):** *not* "never another broad governance review," but **"never another *unguided* governance review."** A governance literature review is permitted **only** when driven by an **explicit research question or governance change request** (e.g. a new RQ, a constitutional amendment, a newly-discovered family, a major NRNA-model change) — never "read everything." This refines DA-LIT-01.

## 4. Standing constraints (unchanged)

- **Sketch-before-literature** still holds for any *new* discovery (ADR-M-011).
- Literature **informs, never dictates**; **never reopens the constitutional architecture** (MC-06 / boundary contract).
- No literature review **interrupts** Strategic→Tactical→Implementation flow; reviews sit at the named checkpoints only.

---

## Draft ADR — DA-LIT-01 (PROPOSAL ONLY; not enacted)

- **Proposes:** extend the Literature Protocol (ADR-M-011) with an explicit **Phase 2 (validation-driven)** mode: triggers A–D, three scheduled targeted reviews (translation / DDD / publication), and the role shift to positioning/stress-testing/terminology.
- **Class:** Process Rule. **Evidence:** program maturity (Rounds 38–45 discovery complete; ontology + projection + ownership stable). **Why not enacted now:** MB-39.1 is frozen — this routes to the **Methodology Governance Review**.
- **Consequence if accepted:** the methodology gains a documented post-discovery literature mode without weakening sketch-before-literature.
- **Does not** change any governance finding, the Constitution, or the DDD gate.

---

*Round 45-LIT — Literature Research Roadmap (Phase 2) — ISSUED (descriptive; DA-LIT-01 = proposal).*
*Discovery-driven literature COMPLETE; now validation-driven (triggers A–D). No broad review now. Next = LIT-2 (translation) after Round 46, before Strategic DDD. Three targeted reviews, not one. MB-39.1 FROZEN · DDD GATED.*
