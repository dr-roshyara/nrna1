# Architecture Release 1.0 (official baseline)

**Program:** NRNA DDD Trustworthiness Research Program
**Status:** 🏷️ RELEASE MANIFEST — the single official baseline bundling the frozen knowledge + boundary artifacts. **Immutable; changes only by a versioned re-issue (Release 1.x / 2.0) via ADR.**
**Date:** 2026-06-26 · **Closes Phase II (Strategic DDD Discovery).**

> A release marker, **not** a methodology document. It names the coherent set that all downstream work (migration, aggregates, implementation) builds from — so "the baseline" is one citable version, not four loose documents.

## Contents of Release 1.0

| Layer | Artifact | Version |
|-------|----------|---------|
| **Knowledge** | Certified Domain Knowledge Package (`Round46-02`) | 1.0 |
| **Vocabulary** | Canonical Vocabulary Dictionary (`Round46-VOCAB`) | 1.0 |
| **Landscape** | Strategic Domain Landscape (`Round47-02`) | 1.0 |
| **Boundaries** | Boundary Decision Register (`Round49-06`) | **1.1** (BDR-05 resolved) |

**Frozen methodology (the instruments, also 1.0):** `Round47-MC` Methodology Constitution · `Round47-OP` Operating Protocol · ADQC v1.1 · EBSD methodology · `Round48A` BC-Evaluation-Framework v1.1 · `Round49-04/05` (Dossier/Evaluation). Governed by Knowledge Release Governance (`Round46-KRG`).

## What Release 1.0 establishes
- **5 Confirmed bounded contexts:** Evidence · Voting · Appointment (Operational); Contestation (Greenfield); Adjudication (Greenfield — BDR v1.1). Plus Replay=Application Capability, Authorization=Service, Lifecycle=Supporting, Audit=Infrastructure; Results/Legitimacy=Read Models; Anonymity=Invariant; Trust-Anchor/Consent=External.
- **The greenfield Core to build:** **Adjudication + Contestation** = the unbuilt correction loop (adjudicate a contested election → binding determination). Publication-finality exists (Lifecycle); adjudicative finality does not.
- **Certified governance concepts unchanged** — only software boundaries decided.

## Candidate dissertation contributions (named, for later validation — observation, not claim)
1. **Knowledge Certification Pipeline** (governance discovery → certified knowledge).
2. **EBSD — Evidence-Based Strategic DDD** (software boundaries from certified semantics + empirical code evidence).
3. **Boundary Decision Register** as a governed artifact separating evidence / reasoning / decisions.
4. **Governance-to-Software Translation Framework** (semantic ownership and software ownership are related but independently discoverable).
*Each `[verify]` via LIT-METHOD (architecture-recovery / empirical-architecture positioning) — deferred to after Round 50, for the dissertation Methodology-Validation chapter.*

## Architecture Principles (Release 1.0) — the software-architecture constitution
1. **Knowledge before software.**
2. **Semantic ownership before software ownership.**
3. **Evidence before architecture decisions.**
4. **Strategic discovery before tactical design.**
5. **Boundary decisions are governed** (BDR; versioned).
6. **One authoritative implementation per confirmed BC.**
7. **Invariants dominate implementation** (decision-first, not entity-first).
8. **Architecture fitness tests prevent drift.**
9. **Evidence remains permanent** (append-only; never overwritten).
10. **Architecture evolves only through governed releases** (1.x / 2.0).

## ✅ Formal declaration
> **Architecture Release 1.0 APPROVED. Strategic Architecture Discovery COMPLETE. Phase III (Strategic → Tactical Transition) AUTHORIZED.**

---
*Architecture Release 1.0 — ISSUED. Bundle: Knowledge 1.0 · Vocabulary 1.0 · Landscape 1.0 · BDR 1.1. Phases: I COMPLETE · II COMPLETE · III Strategic→Tactical Transition STARTS · IV Tactical after Aggregate Discovery. Next: Migration Plan → Round 50 Aggregate Discovery. Code/migration awaits authorization.*
