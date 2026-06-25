# Round 40-03 — Threat & Capture Resistance: Governance Architecture Synthesis

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Round 40 — synthesis (integration, not discovery) · **Under Methodology Baseline MB-39.1**
**Role:** Senior Governance / Constitutional / Election-Security / DDD-Domain-Discovery Architect.
**Status:** 📐 REFERENCE ARCHITECTURE (descriptive) — **program-canonical under MB-39.1**, before Methodology Governance Review.
**Date:** 2026-06-25

> **Scope.** Integrate the Round 40 discoveries (`Round40-01` measurement, `Round40-02` domain) into one coherent governance architecture. **No new evidence; no new mechanisms** (except to resolve an internal contradiction, flagged if so); **no methodology change; no ADRs; no software/DDD.** Synthesis only.
> **Gates:** MB-39.1 FROZEN · Register LOCKED · DDD GATED. Constitutional properties are **mapped, never modified.**
> **Epistemic status (read before "canonical").** This architecture is **synthesized, internally consistency-checked, and evaluated under MB-39.1** — it is **NOT** externally *verified*, *proven*, or *empirically validated*. No formal proofs, model-checking, simulation, case studies, or independent review have been performed. "Canonical" means **canonical *for the NRNA program under MB-39.1*** — the program's working reference — **not** canonical for governance research generally. Population of inference: NRNA, one family. Generalizability: not yet established.

---

## D1 — Architectural Meta-Model

The family is governed by a fixed **grammar** of six architectural concept-types. Every concrete statement about this family is an instance of one relation below.

```
ConstitutionalProperty ──grounds──►  Capability ──realizedBy──► Mechanism
        (S-1..S-5,                       │  │  │
         anonymity)                      │  │  └─counters──► CaptureTarget (source/body/process/information/meta)
                                         │  └────operatesAt─► LifecycleStage (pre…archival)
                                         └───ownedBy/oversightBy/accountableTo─► Actor
   Capability ──dependsOn──► Capability        (responsibility)
   Capability ──interactsWith──► Capability  (reinforcing/masking/cascading/recursive/feedback)
   CaptureEvent ──transitions──► CaptureEvent (the state machine, D5)
```

**Reading the meta-model.** A *Capability* is the central type. It is **grounded in** one or more constitutional properties, **owned by** an actor (with separate oversight/accountability actors), **realized by** mechanisms, **counters** specific capture targets, **operates at** lifecycle stages, **depends on** other capabilities, and **interacts with** them. A *CaptureEvent* flows through the state machine. This grammar is what makes the rest of the document an *architecture* rather than a catalogue: every view (D7) is a projection of this single meta-model onto one relation.

**Integration statement.** The architecture is a **closed governance control loop** grounded in constitution, expressed as capabilities, assigned to actors, realized by mechanisms, and driven by the lifecycle of capture events. Its resilience is an **emergent property of the loop being closed**, not a sum of its parts (Round40-02 O-D-02; Round40-01 "cluster, not family").

---

## D2 — Capability Dependency Graph

```
                 ┌─────────────── EMERGENT ───────────────┐
                 │   Resilience / Legitimacy-Retention     │   (emerges from closed loop; owned by no one)
                 └───────────────▲─────────────────────────┘
                                 │ emerges from
   Accountability ◄── Recovery ◄── Decision ◄── Escalation ◄── Verification ◄── Detection ◄── Monitoring
        ▲              ▲             ▲              ▲              ▲              ▲             ▲
        └─ Evidence ───┴─────────────┴──────────────┘              │              │             │
                                                          Transparency ───────────┘             │
                                                          Independence ──────────(precondition)─┘
   Anticipation/Intelligence ─(optional, improves all)─► Monitoring                Containment ◄─ Decision
```

| Tier | Capabilities | Why |
|------|--------------|-----|
| **Foundational** | Monitoring · Independence · Transparency | nothing downstream functions without sight, independent vantage, and visibility |
| **Dependent** | Detection ← Monitoring; Verification ← Detection+Evidence; Escalation ← Verification; Decision ← Escalation; Containment/Recovery ← Decision; Accountability ← Evidence+Decision | each is meaningless without its predecessor |
| **Optional** | Anticipation/Intelligence · Deterrence | improve every downstream capability but the loop runs without them |
| **Emergent** | Resilience · Legitimacy-retention | not owned or built; arise *iff* the loop is closed and Independence is real |

**Critical dependency (the architecture's load-bearing edge):** **Escalation depends on Independence + Transparency simultaneously.** If either fails, Verification cannot reach a Decider with power — the loop opens — and the emergent capabilities vanish. This is the graph-level statement of Round40-02's "bounded above by the weakest of {real independence, closed correction loop}."

---

## D3 — Governance Information Flow

```
Threat ─► Observation ─► Evidence ─► Verification ─► Decision ─► Action ─► Review ─► Archive
   ▲          │             │            │                                   │         │
   │          │             │            └── (insufficient) ──► back to Observation     │
   │          └── Transparency publishes each step to Observers/Citizens               │
   │                                                                                    │
   └────────────────── Feedback A: Review ─► Anticipation (update threat model) ◄───────┘
                       Feedback B: Archive ─► Learning/Intelligence (precedent, memory) ◄┘
```

- **Forward chain:** raw threat → observed signal → admissible evidence → verified finding → governing decision → executed action → post-hoc review → immutable archive.
- **Feedback A (fast):** Review updates the live threat model (Anticipation) — the loop *learns within an election*.
- **Feedback B (slow):** Archive builds precedent and institutional memory — the loop *matures across elections* (directly addresses the founding-stage maturity gap / F-4).
- **Transparency** is a *side-channel on every node*, not a stage — it publishes state so independent actors can detect divergence.

**Synthesis note.** The two feedback loops are the only path by which a *founding-stage* system accumulates the institutional memory that mature systems are simply endowed with. Their integrity is therefore a first-class architectural concern (an adversary who poisons Archive attacks the system's ability to mature).

---

## D4 — Responsibility Architecture (separation of duties)

| Capability | Owner | Supporting | Oversight | Accountability |
|-----------|-------|------------|-----------|----------------|
| Monitoring | Election Authority | Platform operators | Independent Oversight | Auditors |
| Detection | Independent Oversight | Observers | Constitutional Review | Auditors |
| Verification | Auditors | Oversight | Appeals Body | External investigators |
| Escalation | Candidates/Citizens (standing) | Observers | Appeals Body | Constitutional Review |
| Decision | Appeals / Adjudication Body | Constitutional Review | Constitutional Review | External investigators |
| Containment | Election Authority | Oversight | Appeals Body | Auditors |
| Recovery | Election Authority | Oversight | Constitutional Review | Auditors |
| Accountability | Appeals Body | Auditors | Constitutional Review | External investigators |
| Transparency (X-cut) | Election Authority | Platform | Observers/Citizens | Auditors |

**Separation-of-duties rule (synthesis).** No actor may **own** a capability *and* be its **oversight** *and* its **accountability**. Inspecting the table, the **Election Authority owns execution-heavy capabilities** (monitoring, containment, recovery) but **never owns Detection, Verification, Decision, or Accountability** — those sit with independent bodies. This is the responsibility-layer expression of "who discovers ≠ who decides ≠ who checks" (RGA §4) applied to governance rather than methodology.

**Known fragility:** every oversight/accountability column ultimately leans on bodies that, in a voluntary diaspora association, are only **functionally** independent (Option B). The responsibility architecture is therefore only as strong as S-3 (appointment insulation) — see D6.

---

## D5 — Governance State Machine (lifecycle of a capture event)

```
        ┌───────────► DISMISSED (false positive; evidence insufficient)
        │
POTENTIAL ─► SUSPECTED ─► OBSERVED ─► VERIFIED ─► CONTAINED ─► RESOLVED ─► AUDITED ─► CLOSED
                │             │           │            │            │
                │             │           │            │            └─► UNRESOLVED (remediation failed) ─► ESCALATED-EXTERNAL
                │             │           │            └─► ESCALATED (decision required above current body)
                │             │           └─► (anonymity blocks remediation) ─► CONTAINED-ONLY
                └─────────────┴─ (re-observation strengthens/weakens) ──────────────────────────────────
```

| Transition | Condition |
|-----------|-----------|
| Potential → Suspected | a monitored signal crosses a threshold |
| Suspected → Observed | independent corroboration (≥2 sources / observers) |
| Observed → Verified | auditor-grade evidence; **or → Dismissed** if not |
| Verified → Contained | decision authority limits blast radius |
| Contained → Resolved | remediation restores legitimacy; **or → Contained-Only** if anonymity blocks full remediation; **or → Unresolved** if it fails |
| Resolved → Audited | independent post-hoc review |
| Audited → Closed | review accepted; archived; feeds Anticipation/Learning |
| any → Escalated-External | internal independence exhausted (last-resort) |

**Two terminal states matter most.** **Contained-Only** is the architecturally honest state where a real capture is *contained but not fully remediated* because remediation would violate anonymity — a permanent feature, not a defect to design away. **Escalated-External** is the state a founding-stage voluntary association can rarely reach (no external court) — which is precisely why the *internal* loop must be sound.

---

## D6 — Constitutional Dependency Map (mapped, never modified)

| Constitutional property | Grounds which capabilities | Mechanism of support |
|--------------------------|----------------------------|----------------------|
| **S-1** entrenched mandate (Tier-3) | Independence, Prevention | makes the oversight mandate hard to strip → resists body capture |
| **S-2** final rulings | Decision, Escalation (terminus) | gives the loop a binding endpoint → prevents endless re-litigation capture |
| **S-3** appointment insulation (PAN-resistant) | Independence, Detection, Verification | keeps the independent bodies independent → the load-bearing safeguard |
| **S-4** explicit election-oversight jurisdiction | Detection, Containment, Decision | authority to *act* on findings → closes detection→correction |
| **S-5** standing to challenge self-interpretation | Escalation, Accountability | guarantees a path from detection to a corrector → keeps the loop closed |
| **Anonymity invariant** | *constrains* Recovery, Accountability | forbids re-deriving votes/voters → caps remediation & attribution (→ Contained-Only) |

**Synthesis finding.** **S-3 and S-5 are the constitutional keystones of this family:** S-3 makes Independence real, S-5 keeps the correction loop closed — together they are exactly the two edges D2 identified as load-bearing. **S-4 supplies the *authority* and S-5 the *standing*; without both, Transparency produces detection that cannot act.** The anonymity invariant is the only property that *subtracts* capability — and it does so deliberately. *(No property is altered here; this is a dependency map.)*

---

## D7 — Architectural Views (six projections of the D1 meta-model)

- **Capability View** — the pipeline `Anticipate→Prevent→Detect→Contain→Respond→Recover→Account` + cross-cutting Transparency/Escalation (D2 graph).
- **Responsibility View** — the owner/oversight/accountability matrix (D4); separation of duties enforced.
- **Interaction View** — reinforcing (info+body), masking (info hides body), cascading (body→process→outcome), recursive (meta-capture), feedback (transparency→detection→challenge→correction). The *defensive* feedback loop vs the *offensive* recursive meta-capture is the central contest (Round40-02 O-D-06).
- **Lifecycle View** — capture vectors per election stage; Certification & Appeal = crown jewels (Round40-02 O-D-03).
- **Failure View** — meta-capture · common-mode pseudo-independence · open correction loop · legitimate-threshold capture · founding-stage maturity gap · safeguard↔anonymity conflict (Round40-02 O-D-07).
- **Information-Flow View** — `Threat→Observation→Evidence→Verification→Decision→Action→Review→Archive` + two feedback loops (D3).

**Cross-view invariant.** All six views agree on one thing: the architecture's strength is the **closed loop with real independence**. Each view is just that invariant seen from a different angle (capabilities, owners, interactions, time, failures, information). Convergence of six independent projections on one invariant is the synthesis's main structural result.

---

## D8 — Conceptual Domain Partitions (candidate governance domains — NOT software contexts)

Grouped by cohesion (capabilities that share data, actors, and rate-of-change). **No Strategic DDD; identification only.**

| Candidate governance domain | Owns capabilities | Cohesion basis | Stability |
|-----------------------------|-------------------|----------------|-----------|
| **Oversight & Adjudication** | Detection, Decision, Containment | independent-body authority; recurs in F-AUTH/F-REV/F-THR | **Candidate-Stable** (strongest) |
| **Transparency & Disclosure** | Transparency, parts of Monitoring | public visibility; cross-family | **Candidate** |
| **Appeal & Contestation** | Escalation, Accountability | standing + external recourse | **Candidate** |
| **Threat Intelligence** | Anticipation, Learning | adversary modelling; new | **Experimental** (n=1) |
| **Incident Response** | Containment, Recovery | acting during an attack; new | **Experimental** (n=1) |
| **Evidence & Audit** | Verification, Evidence, Archive | auditor-grade record | **Candidate** (overlaps platform audit trail) |
| **Remediation** | Recovery | restoring legitimacy | **Blocked** (anonymity conflict; unresolved) |

**Discipline.** **Oversight & Adjudication** is the only domain near cross-family stability (it appears in every family). It stays **Candidate** — recorded for the eventual DDD Readiness Assessment, **not** promoted to Ubiquitous Language. The DDD gate is untouched.

---

## D9 — Architectural Consistency Review

| Check | Result |
|-------|--------|
| **Contradictions** | **1 real, declared:** Recovery vs Anonymity — remediation cannot re-derive votes. *Not resolved by inventing a mechanism;* modelled as the terminal state **Contained-Only** (D5) and the **Blocked** Remediation domain (D8). This is an honest constitutional limit, not an architecture defect. |
| **Duplicated responsibilities** | none — D4 gives each capability a single owner; oversight/accountability are deliberately *separate* actors, not duplicates. |
| **Orphan capabilities** | none — every capability in D2 has an owner (D4), a constitutional ground (D6), and a place in the information flow (D3). |
| **Circular dependencies** | none vicious — the Review→Anticipation and Archive→Learning edges are **feedback loops** (intended, time-separated), not synchronous cycles. Distinguished explicitly. |
| **Constitutional conflicts** | none introduced; one mapped *constraint* (anonymity) and zero modifications. Meta-capture is a **bounded residual risk** (independence may be only functional — Option B), recorded, not a structural inconsistency. |

**Verdict:** the architecture is internally consistent. Its **one declared limit** (anonymity-bounded remediation) and **one bounded residual risk** (functional-only independence / meta-capture) are *named and located*, which is the correct architectural treatment of an irreducible constraint and a managed risk respectively.

---

## D10 — Executive Architecture Summary (program-canonical under MB-39.1)

The Threat & Capture Resistance governance architecture is a **closed control loop grounded in constitution**. Its grammar (D1) is fixed: capabilities, grounded in constitutional properties, owned by actors, realized by mechanisms, countering capture targets, across the election lifecycle. Its capabilities form a **dependency graph** (D2) whose foundational tier is Monitoring + Independence + Transparency and whose **emergent** apex — Resilience / Legitimacy-retention — exists *only when the loop is closed and independence is real*. Governance **information flows** Threat→Observation→Evidence→Verification→Decision→Action→Review→Archive (D3) with a fast feedback loop (Review→Anticipation) and a slow one (Archive→Learning) that is the founding-stage system's only route to institutional maturity. **Responsibility** is split so that the Election Authority executes but never adjudicates or audits (D4); a capture event moves through a **state machine** (D5) whose two decisive terminal states are **Contained-Only** (anonymity caps remediation) and **Escalated-External** (rarely reachable without a court). The **constitutional keystones** are **S-3** (makes independence real) and **S-5** (keeps the correction loop closed), with S-4 supplying authority and the anonymity invariant deliberately capping remediation (D6). Six **architectural views** (D7) all converge on one invariant: **a closed correction loop with genuine independence.** The architecture is **internally consistent** (D9), with one declared limit (anonymity-bounded remediation) and one bounded residual risk (functional-only independence / meta-capture). The strongest **candidate governance domain** is **Oversight & Adjudication** (D8) — recorded, not promoted.

This is the **program-canonical** architectural reference for the family **under MB-39.1** (synthesized and internally checked — not externally verified). It introduces no new evidence, changes no methodology, modifies no constitutional property, and performs no software design.

---

*Round 40-03 — Threat & Capture Resistance Governance Architecture Synthesis — ISSUED (descriptive; program-canonical under MB-39.1; synthesized, not externally verified).*
*Meta-model · capability dependency graph · information flow · responsibility architecture · state machine · constitutional dependency map · 6 views · candidate domains · consistency review. MB-39.1 FROZEN, Register LOCKED, DDD GATED. Next: Methodology Governance Review (Round40-01 DA-THR-01..06) → MB-39.2 decision → F-REV.*
