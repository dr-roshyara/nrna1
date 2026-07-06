---
name: literature-research-phasing
description: "ARB decision on when election system literature (ElectionGuard, Helios, coercion resistance, social choice theory) may be integrated into NRNA design — deferred until after aggregate boundaries are stable"
metadata: 
  node_type: memory
  type: project
  originSessionId: d5e01507-9e85-498a-bf95-02711af72d39
---

Literature research into election systems is NOT permitted before aggregate boundaries are finalized. This is a binding ARB governance decision.

**Why:** Deep integration of election literature before aggregate design is stable will pull context boundaries, decision ownership, and invariants away from discovered evidence — violating the core governance principle of Rounds 17-32.

## Phasing

**Phase A — Round 33 (current):** DDD, Vernon, Ostrom only. No election system literature.

**Phase B — Rounds 34-35:** After aggregate approval. Structured research into ElectionGuard, Helios, Scantegrity, Prêt à Voter — question: "how do mature systems model vote lifecycle events?" NOT "should we copy them?"

**Phase C — Round 36: AUTHORIZED** (Round 35 closed 2026-06-08)
- 36A: Verifiability (ElectionGuard cast-as-intended, Helios end-to-end) — primary input for D42B resolution
- 36B: Auditability (Risk Limiting Audits)
- 36C: Threat Modeling (election/governance attack literature)
- 36D: Trustworthiness Synthesis — synthesizes 36A/36B/36C findings against discovered domain model

**Evaluation filter for every Round 36 finding (binding):**
"Does this strengthen the discovered model, or does it attempt to replace the discovered model?" Only findings that strengthen may advance.

**Round 36 governance posture (ARB clarification 2026-06-10 — binding):**
Round 36 is an EVALUATION of the current architecture, not a defence. All three outcomes are acceptable: (A) minor enhancements; (B) new capabilities/aggregates/contexts; (C) major architectural changes. The architecture is not sacred. The evidence is. Changes may be proposed — but must show: literature evidence + discovered-domain evidence + the gap + why the change is required. Submit through ADR review. Do not silently import patterns.

**Absolute constraints:** Literature may NOT override — Decision Ownership, Aggregate Boundaries, Constitutional Requirements, Discovered Invariants — UNLESS literature demonstrates a gap requiring their revision (in which case, evidence must be presented through ADR review).

**AUTHORITY-GAP-1 (Candidate D43):** Enrollment authority ownership unresolved. Flag in all 36A research touching Eligibility, voting preconditions, and cast-as-intended verifiability.

## Special Rules

**Coercion Resistance:** Only after NRNA Constitution explicitly requires it. Until then: research only, no design.

**Social Choice Theory** (Condorcet, IRV, STV, etc.): Only after Results/Tallying decision ownership is resolved (D39). Ownership before algorithm.

**Papers → DDD Reference Implementation Guide — Timing Rule (ARB Mentor ruling 2026-06-13, binding):**

DO NOT READ the guide during Rounds 36A, 36B, 36C, or 36D.

The guide is Architecture Translation Material (Literature → DDD Realizations). It is NOT literature evidence, not discovery evidence, not governance evidence. Reading it before ownership is settled would introduce implementation contamination (context names, aggregate names, CQRS/Saga patterns from ElectionGuard/Helios/Verifier architecture) before the NRNA model has settled its own answers.

READ the guide at Round 36E (Architecture Impact Assessment). By then: accepted findings, rejected findings, ownership, context impacts, aggregate impacts, and governance impacts are all known. The guide becomes a Reference Implementation Catalog, not an Architecture Authority.

Use it as: "Does this suggest a plausible DDD realization for our already-settled ownership?" NOT as: "Should we adopt this architecture?"

## Roadmap
Round 33 → 34 → 35 → 36A/B/C/D (research) → 36E (Architecture Impact — read guide here) → 37 (ADR Authoring) → 38 (Trust ADRs) → 39 (Technical Architecture)

**Why:** Literature strengthens trustworthiness — but only AFTER constitutional and domain structure is stable.

**How to apply:** When any design round is proposed that involves election system literature before Round 36, flag this phasing plan and require ARB approval.

Source document: `docs/architecture/design/Literature_Research_Phasing_Plan.md`

---

## PHASE 2 UPDATE (2026-06-26) — Validation-Driven Literature

The above (Rounds 33–36 phasing) was **Phase 1: discovery-driven** literature — now COMPLETE (governance discovery Rounds 38–45 done: ontology v1.0, semantic projection, ownership architecture all stable). **From here, literature is VALIDATION/QUESTION-DRIVEN**, not discovery-driven: consulted only for a *specific uncertainty*, to **position / stress-test / supply terminology** — never to discover or redefine settled theory. Sketch-before-literature (ADR-M-011) still holds for any new discovery.

**Triggers (only these warrant a review now):** A — a Stable theory appears → MAP it to scholarship; B — an unresolved RQ (e.g. RQ-EL-01 Eligibility) → targeted review to widen search space; C — before Strategic DDD → TRANSLATION review; D — before publication → positioning.

**Decision: do NOT run a broad review now.** Next review = **LIT-2 (translation: ontology engineering / enterprise ontology / semantic projection / DDD semantics)**, scheduled **AFTER Round 46 (Strategic DDD Readiness) and BEFORE Strategic DDD.** Three targeted reviews total (LIT-2 translation → LIT-3 DDD/architecture → LIT-4 publication), NOT one big review; reviews sit at named checkpoints and never interrupt Strategic→Tactical→Implementation flow.

**REFINEMENT (2026-06-26):** full roadmap = **LIT-2** (translation + conceptual positioning + boundary comparison; DONE `Round46-LIT2`) → Strategic DDD → **LIT-SYS** (Systems-Theory positioning — cybernetics/Ashby/Beer-VSM/Luhmann; DONE `Round47-LIT-SYS`; dedicated not optional) → Tactical DDD → **LIT-3** (DDD/context-mapping validation) → Implementation → **LIT-4** (engineering/secure-voting) → **LIT-EVAL** (empirical architecture-evaluation methods, ATAM-style) → **LIT-5** (publication positioning). **Governance-literature rule refined:** never another *UNGUIDED* governance review — allowed ONLY when driven by an explicit research question or governance change request (new RQ / amendment / new family / major model change). LIT-SYS key result: certified meta-arch ↔ VSM (S5 identity = consent/constituent trust anchor) + Ashby requisite variety (Meta-CVI = self-reference limit) + Luhmann legitimation-by-procedure (↔ finality constitutive) — corroborates, no model change.

Recorded as **Draft ADR DA-LIT-01** (extends ADR-M-011; PROPOSAL ONLY — MB-39.1 frozen → goes to Methodology Governance Review). Roadmap doc: `docs/architecture/design/Round45-LIT_Literature_Research_Roadmap_Phase2.md`. Supersedes the Round-36-era numbering above for current planning.
