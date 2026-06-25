# NRNA Governance Architecture — Knowledge Transfer & Architecture Handbook

## Volume 0 — Master Overview & Index (START HERE)

**Program:** NRNA DDD Trustworthiness Research Program
**Purpose of this handbook:** allow any senior architect (or future session) to understand not only *what* has been built, but *why* each decision was made, *how* the methodology evolved, *which assumptions remain provisional*, and *what evidence would change the conclusions.*
**Status of this volume:** canonical entry point. Volumes 1–10 are filled progressively; this volume is always current.
**Date:** 2026-06-25

> **How to use this handbook.** The *detailed* content lives in the committed `docs/architecture/design/Round38C-*` artifacts (single source of truth). This handbook is the **map, synthesis, and rationale layer** over them — it does not duplicate them. Each volume below indexes the artifacts it covers. The live program state is in the `ddd-program-state` memory.

---

## 1. The complete architectural pipeline

```
Constitution
   → Constitutional Properties (S-1..S-5)
   → Governance Capability Discovery (Pass 1)
   → Capability Families
   → Capability Architecture (validated)
   → Mechanism Discovery (Pass 2)
   → Mechanism Design Space
   → Composite Governance Architecture
   → Methodology Validation
   → Strategic DDD            ← GATED (not started)
   → Context Mapping          ← GATED
   → Tactical DDD             ← GATED
   → Hexagonal Architecture   ← GATED
   → Implementation           ← GATED
   → Testing (TDD) → Verification
```

**Three architecture levels** (P2-SYN-01 §8) sit inside this pipeline:
- **L1 Governance Capability Architecture** — *what functions must exist?* (EGCP, families)
- **L2 Governance Mechanism Architecture** — *how are they realized?* (Pass 2 mechanisms)
- **L3 Governance Composition Architecture** — *how do mechanisms interact to create emergent properties?* (composites — discovered, not designed-in)

---

## 2. Current status (as of this checkpoint)

```
Constitution Discovery        ██████████ 100%
Constitutional Ruling (38C-15) ██████████ 100%  Option B + permanent safeguards S-1..S-5
Capability Discovery (Pass 1)  ██████████ 100%
Capability Validation (SYN-03) ██████████ 100%
Mechanism Methodology (P2-00+) ██████████ 100%  (frozen except evidence-driven)
F-OBS Pass 2                   ██████████ 100%
F-AUTH Pass 2                  ██████████ 100%
Methodology Validation (SYN)   ██████████ 100%

F-PROC                         ░░░░░░░░░░   0%   ← NEXT (run as hostile test)
F-THR                          ░░░░░░░░░░   0%
F-REV (hub, last)              ░░░░░░░░░░   0%
Pass-2 cross-family synthesis  ░░░░░░░░░░   0%
Strategic DDD                  ░░░░░░░░░░   0%   GATED
Tactical DDD / Implementation  ░░░░░░░░░░   0%   GATED
```

**Where the program is paused:** Pass 2, after F-AUTH + methodology validation. **Next unit of work:** F-PROC (Process Integrity), run as a *deliberately hostile* test of provisional observations M-01–M-04 (P2-SYN-01 §10).

---

## 3. Volume map → committed artifacts

| Vol | Title | Primary artifacts (in `docs/architecture/design/` unless noted) | Status |
|----|-------|------------------------------------------------------------------|--------|
| **0** | Master Overview & Index | *this file* | current |
| **1** | Executive Program Overview | (to write) | TODO |
| **2** | Constitutional Architecture | Round38C-07..13B; **38C-14** PreRuling; **14A** validation summary; **14B** sealed hypothesis; **14C** leadership decision; **38C-15** ARB ruling; `docs/external-validation/01-04` | covered by artifacts |
| **3** | Governance Capability Architecture | GCD-01..05 (S-2/S-4/S-1/S-3/S-5); **EGCP-01** (frozen); **SYN-01/02/03**; **GLOSSARY-01** (frozen) | covered |
| **4** | Governance Mechanism Architecture | **P2-00** (frozen protocol); **P2-17** (provenance); **P2-18** (prediction register); **P2-SYN-01** (methodology validation); **P2-01** F-OBS; **P2-02H/I/P2-02** F-AUTH | covered (in progress) |
| **5** | DDD Preparation (why DDD has NOT started) | gating rationale across 38C-15 F.2, SYN-03, P2-00 | TODO |
| **6** | Research Methodology | EGCP discipline; P2-00; P2-17; P2-18; P2-SYN-01; observation lifecycle; freeze discipline | covered + TODO synthesis |
| **7** | Software Architecture (future) | (to write — Hexagonal/Clean/DDD/CQRS/events/security/persistence/testing) | TODO (gated) |
| **8** | Quality Architecture | TDD-first; ADR process; testing pyramid; mutation/property/architecture tests; quality gates; traceability | TODO |
| **9** | Literature Map (by purpose) | P2-17 literature categories A–D + family↔domain map + reading priority | covered + TODO expansion |
| **10** | Future Research Roadmap | governance patterns; metamodel; DSL; formal verification; simulation; publication | TODO |

**Key milestone commits (this program phase):** 38C-15 ruling `1da5338c3` · GCD Pass-1+EGCP `499940176` · SYN-01 `4fa771828` · SYN-02+GLOSSARY `10d37def2` · SYN-03+P2-00 `e4a52049c` · P2-00 enrich `a4d00e78e`/`304612bb3` · freeze+P2-17+harvest `6b9fc90e2` · F-AUTH `daa667b92`/`9ece6dae0` · P2-SYN-01 `dbeb1bde0`/`8208f40c2` · P2-18+refine `89347775b`.

---

## 4. The mentoring chapter — "How a senior architect should steer this project"

These are the program's standing disciplines. They are **binding**, not aspirational.

1. **Never skip discovery.** No DDD before governance discovery completes.
2. **Never invent abstractions.** Only elevate *repeated* observations; a single instance stays "Observed."
3. **Never optimize prematurely.** Discovery → synthesis → validation → *then* optimization.
4. **Literature informs; it never dictates.** Sketch before reading; design novel where literature is silent.
5. **Every abstraction must earn its existence** (a new rule/dimension/layer requires a concrete exposed gap).
6. **Every principle needs falsifiers** (Observation → Prediction → Falsifier → Evidence).
7. **Every conclusion must remain traceable** (Mechanism → Capability → Family → Property → Constitution).
8. **Architecture is evidence-driven**, not preference-driven.
9. **Capability ≠ Mechanism ≠ DDD** — never mix layers.
10. **Research before implementation.**
11. **Preserve rejected alternatives** (with categorized reasons).
12. **Keep the audit trail** (sealed hypotheses, dated errata, commits).
13. **Protect epistemic discipline:** separate Observation / Hypothesis / Decision; provisional ≠ adopted; working model ≠ established.

---

## 5. Invariants that must never be violated

- **DDD gate ACTIVE:** no bounded contexts, aggregates, services, APIs, domain events, repositories, or code until Strategic DDD is authorized.
- **Anonymity invariant:** votes carry no `user_id`; nothing may observe/operate at the vote/voter level (architectural FAIL).
- **Frozen artifacts** (EGCP-01, GLOSSARY-01, P2-00): change only via dated erratum / evidence-driven refinement — never silently.
- **OQ-38A05-02 PROTECTED:** finality-vs-validity routes to the Constitutional Interpretation Chamber; not decided in this program.
- **Option A reservation retained:** A was deselected, *not refuted*; available if permanent safeguards prove inadequate.
- **Provisional observations (M-01..M-04)** must not be elevated until reinforced across families (lifecycle in P2-SYN-01 §6).

---

## 6. The single most important sentences to carry forward

- **Constitutional:** *Option B (Functional Independence) + permanent safeguards; Meta-CVI is managed, not eliminated.*
- **Capability:** *GRP-01 — single-source architectures contain ineliminable Governance Recursion Points, surrounded and observed, not closed.*
- **Mechanism:** *The unit of design is the composite governance architecture, not the mechanism; capture resistance is emergent.*
- **Methodology:** *Discover → synthesize → validate → continue; literature broadens the design space, it does not define it.*

---

## 7. Volume-writing plan (next steps for the handbook)

Write order (each its own file `docs/handbook/Handbook_NN_*.md`): **V1 Executive Overview → V6 Research Methodology → V2 Constitutional → V3 Capability → V4 Mechanism → V5 DDD Preparation → V9 Literature Map → V8 Quality → V7 Software (gated) → V10 Future Roadmap.** Each volume **synthesizes + indexes** its artifacts; it does not duplicate them.

> **Recommendation:** resume F-PROC **after** at least V1 + V6 exist, so the next session opens on a stable canonical frame rather than fragmented context.

---

*Handbook Volume 0 — Master Overview & Index — LIVING (update on every milestone)*
*Next: Volume 1 (Executive Program Overview). Strategic DDD GATED. Pass 2 paused at F-AUTH; F-PROC next (hostile test).*
