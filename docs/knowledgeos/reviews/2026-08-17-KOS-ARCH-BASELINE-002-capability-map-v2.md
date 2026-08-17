# KnowledgeOS — Capability Map v2
## KOS-ARCH-BASELINE-002 · CAP-01…CAP-14 with every capability owned

**Work item:** `KOS-ARCH-BASELINE-002` · **Grant:** `G-KOS-ARCHBASE2-MODEL` · **Session:** `S4-architecture-landscape-v2` · **Date:** 2026-08-17 · **Status: PROPOSAL — returns for PO/ARB decision; does not self-adopt.**

> Companion to **Architecture Landscape v2** and **Context Map v2** (same date, same work item). Realization states are the accepted baseline v1.1's (snapshot 2026-08-15) unless marked re-observed. The one change of substance is the **`CAP-14` row** — the identifier the PO/ARB assigned to BC-7 by the signed ADR-AIP-03 consequence decision; this map gives it its declared place.

---

## 1 · Governing invariant

**Capability Identity Invariant** *(stated by the PO/ARB's CAP-14 act; recorded as this map's rule):* **one capability identifier = one semantic owner.** Identifiers are never reused, never shared across bounded contexts, and never reassigned by merge — a retired id stays retired (`CAP-04`). The CAP-07 collision pause of 2026-08-17 stands in the record as this invariant working.

## 2 · The map

| Capability | Owner (context) | Realized by | State | Notes |
|---|---|---|---|---|
| CAP-01 Context Bootstrap & Rehydration | BC-6 Session Continuity | `inject-context.sh` (AST-002), SessionStart | **live** | |
| CAP-02 Session Recording & Archival | BC-6 Session Continuity | `session-changes-logger.sh` (AST-003), `session-log-reminder.sh` (AST-004) | **live** | |
| CAP-03 Knowledge curation | BC-1 Knowledge Governance | — | **not built** | constitution + convention only; CMP-003 `deferred v0.0` |
| CAP-04 | — | — | **retired-by-merge** | merged into CAP-03 by ARB (`Phase-02.5:41`); id not reusable (§1) |
| CAP-05 Planning & Progress Derivation | BC-2 Implementation Guidance | plan/CONTEXT convention only | **convention only** | de-facto store partially BC-6's (OB-3 — open, carried) |
| CAP-06 Discipline Gating & Tripwires | BC-2 Implementation Guidance | AST-005, AST-006, AST-014 | **live**, all Tier-2 non-blocking | |
| CAP-07/08/09 Checks, verdicts, traceability | BC-3 Verification & Evidence | `db-safety-check.sh` (AST-007) live; `run-gates.sh` (AST-010) planned | **partial** | `CAP-07` remains BC-3's — the identity the collision pause protected |
| CAP-10/12 Drafting & coaching | BC-5 Design & Decision Support | frozen templates by reference (AST-011) | **by reference** | |
| CAP-11 Adversarial review | BC-4 Adversarial Review Support | — (performed by role model + human acts) | **not built** as component | whether this capability's *home* moves is ADR-C7's question — **unchanged here** |
| CAP-13 Platform Self-Governance | BC-5 Design & Decision Support | `registry.yaml` (AST-009) | **live** (data, not executable) | placement challenged (M-2/ADR-C2) — **carried open, not moved** |
| **CAP-14 Governed Session Orchestration** | **BC-7 Governed Session Orchestration** | **`workflow-state.php` (AST-015) · `session-resolve.php` (AST-016)** | **live** — re-observed 2026-08-17: 14 work-item records, in continuous governed use | **the previously undeclared row** (baseline §4 "no CAP id exists") **now owned** — assigned by the PO/ARB consequence act on ADR-AIP-03; T-1's homelessness closed *in the model* |

## 3 · What this map changes, and does not

* **Changes:** exactly one row — `CAP-14` enters, closing the measured gap the baseline stated as fact (*"the newest and most consequential executable capability … has no declared `CAP-` identifier"*). The T-1 centre-of-gravity finding (Stage-2 §3) now has a declared home: **T-1 = CAP-14 (BC-7)**.
* **Does not change:** any other row's owner or state · CAP-11's home (ADR-C7 open — T-3's role-separation capability stays declared under BC-4) · CAP-13's placement (ADR-C2 open) · CAP-05's ownership ambiguity (OB-3 open) · anything physical — the assignment *"establishes capability ownership in the architecture model only"* (the act's own words).
* **Identifier register after this map:** declared ids `CAP-01…14`; `CAP-04` retired-by-merge; **next free id: `CAP-15`.**

---

**Next actor:** PO/ARB — accept, amend, or decline with the v2 set. **Traceability:** ADR-AIP-03 §7 consequence (a), act verbatim (CAP-14 assignment + Capability Identity Invariant) · accepted baseline v1.1 `40026b12` §4 · Stage-2 report `f4eb4f76` §3 (T-1…T-8) · `Phase-02.5-Certification-Plan.md` (CAP-01…13 declarations) · Architecture Landscape v2 (companion) §§2–3.
