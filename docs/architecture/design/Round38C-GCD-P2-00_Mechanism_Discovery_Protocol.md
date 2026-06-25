# Round 38C-GCD-P2-00 — Mechanism Discovery Protocol

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery — **Pass 2 protocol** (rules of engagement; not itself mechanism discovery)
**Authority:** 38C-15 ARB Ruling, Part F.1
**Status:** 🔒 **PROTOCOL — binding on all Pass 2 work.** No mechanisms discovered here; this defines *how* they will be.
**Date:** 2026-06-25

---

## Purpose

Pass 2 changes the abstraction (from *what capability* to *how realized*) and will produce a large alternative space. This protocol fixes the discipline **before** the explosion, so mechanism work cannot silently mutate the validated capability layer (SYN-03) or the frozen vocabulary (GLOSSARY-01).

**Objective — discover the mechanism design space, not "pick a mechanism."** Pass 2 is **exploration, not selection**. For each capability it maps and *classifies* the space of possible realizations, evaluates them, and lets a recommendation (or an explicit *no-dominant-mechanism* conclusion) fall out of the exploration. Selection-first framing is forbidden; it produces local, first-idea bias.

**Terminology guard:** mechanisms are grouped into **mechanism classes / categories** — **not** "mechanism families." "Family" is reserved for *capability* families (GLOSSARY-01); do not overload it.

### Pass 2 pipeline (enriched)

```
Capability
  → Mechanism Design Space (discover)
  → Mechanism Classes (classify; Rule 7)
  → Mechanism Candidates (individual options)
  → Mechanism Architectures (compositions; Rule 10)
  → Evaluation (functional + architectural; Rule 8)
  → Recommendation OR "no dominant mechanism" (Rule 9)
  → Archive (rejected retained — Rule 6; patterns — Rule 11; open questions)
```

Many governance mechanisms are **compositional**: several mechanisms combine into one *architecture* (e.g. appointment diversity ≈ staggered terms + regional nomination + term limits + eligibility screening) rather than four competitors. Composition is a first-class step, not an afterthought.

---

## The six rules (binding)

**Rule 1 — Mechanisms compete; capabilities do not.** A capability is a fixed requirement (what must be possible). Mechanisms are alternative realizations that compete to satisfy it. Disagreement belongs at the mechanism layer, never the capability layer.

**Rule 2 — Every mechanism must trace upward.** No mechanism is admissible unless it traces:
```
Mechanism → Capability → Family → Constitutional Property → Constitution
```
A mechanism that cannot be traced to a capability is out of scope and rejected.

**Rule 3 — Mechanisms may never redefine capabilities.** If a mechanism appears to require changing a capability's identity or purpose, that is a finding to escalate to a synthesis document (per the Architectural Stability Rule) — never a silent edit. The capability layer is frozen relative to Pass 2.

**Rule 4 — Mechanism comparison is mandatory.** No mechanism is accepted because it was discovered first. Each capability's mechanisms are evaluated comparatively against explicit criteria before any recommendation.

**Rule 5 — The discovery shape is fixed:**
```
one capability → several candidate mechanisms → comparative evaluation → recommended mechanism
```
Never `capability → first idea → accepted`.

**Rule 6 — Every rejected mechanism stays in the archive.** Rejected candidates and the reason for rejection are retained. The rejected set is itself a research asset (it records *why not*, which protects future decisions from re-litigation).

**Rule 7 — Classify before comparing.** Candidate mechanisms must first be grouped into **mechanism classes** (e.g. for detection: passive / continuous / distributed / statistical) *before* individual comparison. Comparing fundamentally different classes head-to-head is invalid; compare deliberately within and across classes.

**Rule 8 — Two independent fitness questions per mechanism.** Every mechanism is judged on **both**:
- **Functional fitness:** does it satisfy the capability?
- **Architectural fitness:** does it *strengthen or weaken* GRP handling, EGCP, family reuse, cross-family coupling, the dependency graph, and Option-B assumptions?
A mechanism can pass functional fitness yet fail architectural fitness (e.g. an automatic appointment algorithm satisfies the capability but creates hidden authority concentration). Both must be reported.

**Rule 9 — "No dominant mechanism" is a valid outcome.** Pass 2 does **not** force a recommendation. If no candidate dominates, the honest result is *"no dominant mechanism — alternatives carried into Strategic DDD"* with pros/cons preserved.

**Rule 10 — Mechanisms may be compositional.** Record **mechanism architectures** (named combinations that together realize a capability), not only competing singletons. A combination is evaluated as one architecture.

**Rule 11 — Record reusable governance mechanism patterns.** When the same composition recurs across capabilities (e.g. *Detection → Review → Correction*; *Distribution → Entrenchment → Observability*), record it as a **pattern**. Patterns are often more valuable than isolated mechanisms. (Note: some patterns echo EGCP stages — record the echo, do not redefine EGCP.)

**Rule 12 — Evidence strength + origin per mechanism.** Each mechanism records **evidence strength** (★1 research hypothesis … ★5 used in many real institutions) and **origin** (constitutional law / election administration / judicial administration / governance frameworks / enterprise architecture / safety-critical systems / **NRNA original** / **derived combination**).

**Rule 13 — Constraint map per mechanism.** Each mechanism records which properties (S-1..S-5), capabilities, and families it **supports**, the **dependencies** it creates, and its **GRP effect** (strengthen / neutral / weaken). This keeps Pass 2 from becoming local optimization.

**Rule 14 — Literature informs the space; it does not constrain it.** Include excellent mechanisms found in literature; **design novel** mechanisms where literature is silent; a mechanism that improves on the literature is an acceptable outcome. The program is *creating* a constitutional architecture, not surveying existing ones. Literature broadens the design space; it does **not** reopen the constitutional architecture (that phase is closed).

---

## Per-capability output format (Pass 2)

For each capability (or each **shared design space** within a family — explore once, map per capability), Pass 2 produces:

1. **Mechanism design space** — the mapped set of candidate realizations.
2. **Classification** into mechanism classes (Rule 7).
3. **Mechanism candidates** (individual) **and mechanism architectures** (compositions, Rule 10).
4. **Evaluation matrix** — functional + architectural fitness (Rule 8); with evidence strength + origin + constraint map (Rules 12–13).
5. **Recommendation** OR explicit *"no dominant mechanism"* (Rule 9), with rationale.
6. **Rejected alternatives (retained)** + reason (Rule 6).
7. **Governance mechanism patterns** surfaced (Rule 11).
8. **Open research questions**.

Every entry carries its **upward trace** (Rule 2): Mechanism → Capability → Family → Property → Constitution.

### Evaluation template (per mechanism)

| Criterion | Score / Finding |
|-----------|-----------------|
| Satisfies capability (**functional fitness**) | ✓ / Partial / ✗ |
| **Evidence strength** (Rule 12) | ★1…★5 |
| **Origin** (Rule 12) | lit domain / NRNA original / derived combination |
| Constitutional alignment | |
| Option-B consistency | |
| **GRP impact** (Rule 13) | strengthen / neutral / weaken |
| **Constraint map** (Rule 13) | supports S-? / capabilities / families; dependencies created |
| Family reuse | |
| Cross-family coupling | |
| Complexity / Transparency / Evolvability | |
| Failure modes | |
| Open questions | |

This is **governance architecture, not software.** **Still forbidden:** bounded contexts, aggregates, services, APIs, domain events, repositories, implementation. **Strategic DDD remains GATED.**

---

## Pass 2 completion criterion (prevents indefinite expansion)

Pass 2 is **complete** when **every capability** has: (a) an explored, classified mechanism design space; (b) a comparative evaluation (functional + architectural fitness); (c) either a recommended mechanism **or** an explicit *"no dominant mechanism"* conclusion; (d) documented rationale; (e) preserved rejected alternatives; and (f) recorded open research questions. Anything beyond this (detailed design of the chosen mechanism) is Strategic/Tactical DDD and remains gated.

---

## Literature protocol (Pass 2 role)

**Direction is reversed from the constitutional phase.** The architecture now *asks the literature questions*; literature no longer drives the architecture. Literature **broadens and validates the mechanism design space** — it does **not** reopen constitutional properties.

**Literature categories:**
| Cat | Domain | Pass-2 use |
|-----|--------|-----------|
| A | Constitutional theory (separation of powers, courts, amendment, backsliding) | **CLOSED** — revisit only on genuinely new evidence |
| B | Institutional design (judicial appointment, regulators, ombudsmen, audit offices, election commissions, central banks) | **YES** — mechanism references |
| C | Governance engineering (governance frameworks, assurance, accountability, control, operating models) | **YES** — maps to capability families |
| D | Systems / safety architecture (systems eng., enterprise arch., control theory, safety-critical assurance, recursive/meta-governance) | **IMPORTANT** — esp. for GRP/observability/review analogues |

**Family ↔ literature-domain map:**
| Family | Literature domains |
|--------|--------------------|
| F-OBS | monitoring, auditing, observability, assurance |
| F-REV (Constitutional Review) | review systems, adjudication, judicial administration (recusal/standing/case assignment/appeals) |
| F-THR | amendment/entrenchment, eternity clauses |
| F-AUTH | appointment systems, delegation, regulator/board composition |
| F-PROC | workflow/lifecycle governance, tenure/succession |

**Per-source extraction (read nothing that can't fill these):**
| Field | Purpose |
|-------|---------|
| Source / Domain | traceability + category |
| Mechanism class / Mechanism | classification + candidate |
| Supported capability / property | upward trace |
| Evidence strength | confidence (Rule 12) |
| Advantages / Disadvantages | evaluation |
| Cross-family effects | architectural fitness |
| Open questions | continuity |

If a source cannot fill these fields for a *mechanism*, it is out of scope for Pass 2.

---

## Family ordering for Pass 2

```
F-OBS  (Observability)            ← first: highest reuse, lowest controversy
F-AUTH (Authority Comp. & Dist.)
F-PROC (Process Integrity)
F-THR  (Threshold & Entrenchment) ← cross-cutting; affects several others
F-REV  (Constitutional Review)    ← last: the hub; most entangled with GRP; needs prior families settled
```

**Hub rigor (from SYN-03 Part 4):** F-REV and the hub capabilities GC-S1-01, GC-S1-02 receive extra comparative rigor — a weak mechanism under a hub weakens many safeguards at once.

---

## Family identity update (explicit, per Architectural Stability Rule)

**F-ADJ is renamed F-REV (Constitutional Review), effective Pass 2.** `F-REV ≡ F-ADJ` (same family; "Challenge & Resolution" was the original label, "Constitutional Review" is canonical — challenge is the *trigger*, review is the *capability*). Prior committed documents (SYN-01/02/03) retain `F-ADJ`; this rename is recorded explicitly (not silent) and used going forward.

---

## Carried open items (do not block Pass 2)

- **S-3 status** (Authority-refinement vs meta/relationship level) — affects *organization* only; GC-S3-03's mechanism space is the one place this may surface (SYN-03 Part 5 watch item).
- **Shared-capability consolidation** — taken up **after** Pass 2, **before** Strategic DDD.
- **RQ-SYN02-01** (are families Option-B artifacts?) — recorded, not investigated in Pass 2.
- **P2-01 (F-OBS) predates this enrichment.** Its substantive findings stand (classes, layered-composite recommendation, anonymity constraint, rejected set). It will receive a **light retrofit** (evidence strength, origin, constraint map, patterns) at the Pass-2 consolidation — not re-done. F-AUTH onward uses the enriched format directly.

---

## Deliverable

```
Mechanism Discovery Protocol (binding on Pass 2)
  6 rules: compete-not-capabilities / trace-upward / never-redefine /
           comparison-mandatory / fixed-shape / rejected-retained
  Per-capability output format fixed
  Family order: F-OBS → F-AUTH → F-PROC → F-THR → F-REV
  Hub rigor: F-REV, GC-S1-01, GC-S1-02
  Rename recorded: F-ADJ → F-REV (Constitutional Review)
  DDD remains GATED
```

---

*Round 38C-GCD-P2-00 — Mechanism Discovery Protocol — ISSUED (binding)*
*Pass 2 may begin under this protocol, starting F-OBS. Strategic DDD GATED.*
