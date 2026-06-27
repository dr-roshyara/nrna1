# Round 38C-GCD-GLOSSARY-01 — Governance Vocabulary Stabilization

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery — vocabulary freeze (between SYN-02 and Pass 2)
**Status:** 🔒 **FROZEN reference.** Definitions are fixed before Pass 2 to prevent semantic drift during mechanism discovery.
**Date:** 2026-06-25

---

## Rule

These definitions are **frozen**. During Pass 2 and beyond, terms below may be **added to**, but **not silently redefined**. Any change to an existing definition requires an explicit dated **erratum** (as with the EGCP-01 freeze). If a Pass-2 discussion needs a term to mean something new, it must coin a *new* term, not overload an existing one.

---

## The layer pipeline (canonical)

```
Constitutional Property  →  Emergent Capability Family  →  Governance Capability  →  Governance Mechanism  →  Strategic DDD  →  Tactical DDD
   (38C-15)                  (SYN-02)                       (Pass 1)                  (Pass 2)                 (gated)            (gated)
```

---

## Core terms

**Constitutional Property (S-1..S-5).** A binding requirement established by the 38C-15 ruling that the constitutional order must satisfy (e.g., S-2 Appointment Diversity). *What must be protected.* Not a capability, not a mechanism.

**Safeguard.** Synonym, in this program, for a Constitutional Property S-1..S-5 together with the capabilities that realize it. (S-1 Amendment, S-2 Appointment, S-3 Jurisdiction, S-4 Challenge, S-5 Finality.)

**Governance Capability.** Something the governance system must be *able to do* to realize a property — stated abstractly, purpose-only (e.g., GC-S2-06 Composition Drift Detection). *What*, not *how*. Identifier form: `GC-S{n}-{nn}`.

**Governance Mechanism.** A concrete realization *of* a capability (e.g., staggered terms, sortition, a published register). *How.* Discovered in **Pass 2** — **not before**. A capability may have several competing candidate mechanisms.

**Mechanism Class → Group → Mechanism.** *(added 2026-06-25)* The three-level taxonomy mechanisms are organized in (P2-00 Rule 7). *Class* = mode; *Group* = a design idea within a class; *Mechanism* = a specific realization. ("Group", not "family".)

**Composite Governance Architecture.** *(added 2026-06-25)* A **coordinated selection of mutually interacting mechanisms distributed across multiple orthogonal mechanism dimensions** to realize one governance capability or family. Per Observation P2-02H-02, the composite — not the individual mechanism — is the **unit of design**, and its target behaviour is **emergent** from the interaction.

**Search / Candidate / Design Space.** *(added 2026-06-25; P2-17 §2)* **Search** = everything imaginable/in the literature; **Candidate** = the architecturally-plausible subset; **Design** = the subset surviving the constitutional filters. Excluded mechanisms are retained with a *categorized* reason.

**Architectural vs Research emergence.** *(P2-SYN-01 §3)* **Architectural** = interaction creates new governance behaviour (per-composite). **Research** = the methodology discovers a new principle (synthesis docs only). Never conflate.

**Emergent Capability Family.** A cross-safeguard grouping of capabilities that are manifestations of the same governance concern (e.g., F-OBS Observability). "Emergent" = *discovered from* the capabilities, not imposed. An **organizing layer only** — explicitly **NOT** a bounded context, service, module, or aggregate. Identifier form: `F-XXX`. Current candidates: F-OBS, F-ADJ (Constitutional Review), F-THR, F-AUTH, F-PROC. Always "**candidate** families," never "the five governance families."

**Governance Mechanism vs Capability vs Family (the three-line test):**
- Family = *kind* of thing the system does (Observability).
- Capability = *a specific ability* (Drift Detection).
- Mechanism = *a way to do it* (cycle-over-cycle composition monitoring).

---

## Model terms

**EGCP (Emergent Governance Capability Pattern).** The **working** (not "established") governance **lifecycle** model for NRNA, scoped to NRNA: `Authority → Exercise → Observation → Challenge → Resolution`. Frozen pre-registration in EGCP-01; graduated to working model in SYN-01. Open to future falsification.

**Lifecycle Stage.** One of the five EGCP steps. A *stage* is a step in a single safeguard's lifecycle; distinct from a *family* (cross-safeguard) and from a *cross-cutting concern*.
- **Authority** — who holds/defines/distributes governing power.
- **Exercise** — acting under that authority.
- **Observation** — making state visible; detecting drift.
- **Challenge** — invoking review of an exercise of authority.
- **Resolution** — the lifecycle's **closure operator** (the lifecycle *terminating*), producing one constitutional **outcome**. *Resolution is NOT a capability; there is no `GC-Resolution`.*

**Resolution outcome.** The result a Resolution produces. Currently two: **Confirmation** (the challenged exercise stands) and **Correction** (it is overridden/changed). "Resolution" is the operator; Confirmation/Correction are its outcomes.

**Cross-cutting governance concern.** A governance concern that *wraps* lifecycle stages rather than being one (currently: F-THR Threshold & Entrenchment — a protection applied across Authority/Exercise). DDD-recognizable framing.

**GRP (Governance Recursion Point).** A point where a lifecycle stage must apply to *itself* (e.g., who challenges the challenger?). **Within NRNA evidence**, every safeguard contains one. Ineliminable under a single constitutional source; **surrounded and made observable, not closed.** GRP-01 = the invariant (one phenomenon, five instances). The capability-phase name for 38C-15's "managed, not eliminated." Empirically stronger than EGCP within this research (held where EGCP stressed). **Scoped to NRNA — not asserted universal.**

**Threshold.** A heightened bar required to change a protected provision/decision (e.g., the S-1 amendment threshold).
**Entrenchment.** Placing a provision behind a Threshold so it cannot be changed by ordinary procedure. (Threshold = the bar; Entrenchment = the act of placing something behind it.)

---

## Process terms

**Pass 1.** Capability discovery (purpose-only, no mechanisms). **COMPLETE** (S-1..S-5, SYN-01/02).
**Pass 2.** Mechanism exploration, organized **by family**. **NOT YET STARTED.**
**Pass-1 Rule.** A capability may reference other capabilities; a capability may **not** reference a mechanism. (Extended sense for the whole discovery phase: each layer references only its own or higher layers, never lower.)
**Frozen.** A document/definition fixed as a pre-registration; changed only by explicit dated erratum, never silently. (EGCP-01, this glossary.)
**Working model.** A model adopted as the current best organizing structure, explicitly **open to falsification** — not "scientifically established."

---

## Status discipline (carried from the program)

| Level | May enter the archive |
|-------|------------------------|
| Observation | yes |
| Hypothesis (sealed) | yes |
| Decision | only via the proper authority (e.g., 38C-15 ruling, leadership decision) |

DDD constructs (bounded context, aggregate, service, domain event, repository) are **gated** and must not appear until Strategic DDD is authorized.

---

*Round 38C-GCD-GLOSSARY-01 — Governance Vocabulary Stabilization — FROZEN*
*Definitions fixed before Pass 2; additions allowed, silent redefinition forbidden (erratum required)*
*Next: Pass 2 (mechanism exploration, by family). Strategic DDD GATED.*
