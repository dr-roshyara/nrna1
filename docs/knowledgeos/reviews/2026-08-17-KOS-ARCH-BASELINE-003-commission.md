# KOS-ARCH-BASELINE-003 — Commission

## BC-7 Governed Session Orchestration — Domain Model Discovery

**Work item:** `KOS-ARCH-BASELINE-003` · **Workflow:** `architecture-domain-model`
**Declared role set:** `governance`, `architecture`, `verification` — implementation excluded, mechanically
**Status:** grant AUTHORIZED · architecture assignment REGISTERED · HANDOFF recorded · **START NOT performed** *(per the commissioning prompt's own instruction: create, then STOP — the Human START is required)*

---

## 1 · The human act

> **"RECORD: Commission KOS-ARCH-BASELINE-003 — BC-7 Domain Model Discovery."** — PO/ARB, 2026-08-17, delivered with the Principal review's verdict (*"KOS-ARCH-BASELINE-002 is correctly completed… Strategic Design is accepted. Tactical Design may begin"*) and the full scope below.

**Prerequisites verified at commissioning:** ① ADR-AIP-03 ACCEPTED ② CAP-14 assigned to BC-7 (consequence block) ③ KOS-ARCH-BASELINE-002 accepted (`a265e1b7`) ④ Landscape v2 · Context Map v2 · Capability Map v2 authoritative (banners annotated ACCEPTED).

## 2 · Purpose and mode

**Architecture tactical discovery only.** This is the DDD phase change the review names: strategic design is done; the work now turns the accepted BC-7 boundary into a coherent domain model — a different cognitive mode, a fresh Architecture terminal, a new work item.

## 3 · Required deliverables

**1 · BC-7 Domain Model Proposal:** aggregate candidates · **aggregate-root decision** · entities · value objects · domain events · domain policies · invariants · **ubiquitous language**.

**2 · Boundary analysis:** **BC-7 ↔ Governance** — *authority vs execution lifecycle* (the accepted model's sentence is the anchor: *"Governance decides whether an act was authorized; BC-7 answers whether it was recorded"*) · **BC-7 ↔ Knowledge Engineering** — *artifact identity vs workflow occurrence* (meaning/identity/lineage vs movement/lifecycle/occurrence).

**3 · Open questions, carried not decided:** role ownership (ADR-AIP-04, deferred) · the BC-4 relationship (nature question carried open from the landscape) · future CAP refinement.

## 4 · Constraints (forbidden)

Implementation · code changes · component movement · **workflow_engine changes** · **ADR-AIP-04 decision** · **role-model ownership decision**. The deliverable is a **proposal**: Architecture may propose; the PO/ARB decides. Existing accepted models (Phase A v1.1, the v2 set, ADR-AIP-03) are inputs, never overwritten.

## 5 · Inputs

Accepted seven-context strategic model (`f278dc54`, acceptance `a265e1b7`) · accepted Phase A v1.1 baseline (`40026b12`) — the measured mechanism evidence, incl. its BC-7 invariants I-1…I-3/I-10 · ADR-AIP-03 with both consequences · the attribution domain work where relevant (`KOS-ATTR-ARCH-001` accepted Stage-1 model — the claim/evidence/assessment chain may relate to BC-7's Human Act concept; **relate, do not duplicate**, ES-005.4).

## 6 · Placement and disclosure

Work products to `docs/knowledgeos/reviews/`, derived from the subject. The deliverable discloses its producing process (self-declared, INV-ATTR-2). R-34/P-2 forward constraint: the producer must not independently verify the model.

**Traceability:** the act + Principal review 2026-08-17 · prerequisites (§1) · `R-37` · `ES-005.4` · commission precedent `fde18f56`
