# Round 40-02 — Threat & Capture Resistance Architecture (Domain Discovery)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Round 40 — Threat & Capture Resistance Architecture Discovery · **Executed under Methodology Baseline MB-39.1**
**Status:** DOMAIN DISCOVERY — primary deliverable (domain-first). Methodology measurement (the 10 %) lives in `Round40-01` §5.
**Mode:** Senior Governance / Constitutional / Election-Security / DDD-Domain-Discovery Architect. **Effort: ≥90 % domain, ≤10 % methodology.**
**Date:** 2026-06-25

> **What changed from Round40-01.** Round40-01 measured the locked predictions (the instrument check). **This** document is the research object: *what is the architecture of Threat & Capture Resistance?* Mechanisms appear **last**, as consequences of the architecture — not as the starting point.
> **Gates:** methodology FROZEN (MB-39.1); DDD GATED; Register LOCKED. Conceptual partitions below are **observations only** and **do not** influence this discovery.

---

## 1. Capability-family boundary

**Definition.** Threat & Capture Resistance = the family of governance properties that **prevent, detect, contain, recover from, and hold accountable** any **illegitimate acquisition of control over, or subversion of the integrity of,** the election and its governing/oversight bodies.

**The discriminating idea — legitimacy of control.** This family is not about whether a procedure is *correct* (that is F-PROC) or whether an appointee is *qualified* (that is F-AUTH). It is about whether *control itself* is **legitimate** and **stays** legitimate against an adversary who is actively trying to take it.

| Belongs (in scope) | Why |
|--------------------|-----|
| Capture of oversight / adjudication / certification bodies | the core target |
| Voter coercion, vote-buying, suppression | capture of the electorate's free choice |
| Result/tally manipulation, ballot stuffing | capture of the outcome |
| Insider subversion, collusion | capture from within |
| Information capture (disinformation, opacity) | capture of what can be seen/contested |
| **Meta-capture** (capturing the body that detects capture) | the deepest, recursive case |

| Does NOT belong (boundary) | Routed to |
|----------------------------|-----------|
| Appointment-mechanism *design* | **F-AUTH** (capture resistance *consumes* appointment integrity; it does not design it) |
| Procedural *correctness* of the 5-step vote | **F-PROC** (a target threats attack, not the resistance itself) |
| Pure platform/infrastructure security (DDoS, server hardening) | **Software Translation domain** (in scope only where it changes governance capture) |
| **Vote anonymity** | a **constitutional property** the family must *preserve*, not a capability it *provides* |

**Boundary rule (O-D-01).** Threat & Capture Resistance **depends on** F-AUTH (legitimate appointers), F-PROC (a correct procedure to protect), and platform security (an uncompromised substrate), but is **distinct** from all three: its subject is the *legitimacy and retention of control*, evaluated **only against an adversary**. *Interpretation:* this is the first family whose definition is **adversary-relative** (cf. Round40-01 O-THR-01) — a property the inherited evaluation framework does not model.

---

## 2. Sub-capabilities (the family has an internal architecture)

The family is **not flat**. It decomposes into a pipeline of sub-capabilities with two cross-cutting concerns:

```
            ┌──────────────── cross-cutting: TRANSPARENCY / DISCLOSURE ───────────────┐
            │                                                                          │
ANTICIPATE → PREVENT → DETECT → CONTAIN → RESPOND → RECOVER → ACCOUNT                  │
 (intel)    (raise    (monitor) (limit    (act/    (restore  (attribute &             │
            cost,      blast    escalate) legitimacy) consequences)                    │
            close      radius)                                                         │
            vectors)                                                                   │
            │                                                                          │
            └──────────────── cross-cutting: ESCALATION / ADJUDICATION ────────────────┘
```

| Sub-capability | Question it answers | Primary owner (see §4) |
|----------------|--------------------|------------------------|
| **Anticipation / Intelligence** | Who might attack, and how? | oversight + auditors |
| **Prevention** | How do we make capture costly/impossible? | constitution + authority |
| **Detection / Monitoring** | Is capture happening now? | observers + transparency |
| **Containment** | How do we limit the blast radius? | oversight |
| **Response / Escalation** | Who acts, and to whom is it escalated? | adjudication / appeal |
| **Recovery / Remediation** | How is legitimacy restored? | oversight + authority |
| **Accountability / Attribution** | Who did it; what follows? | appeals + external investigators |
| **Transparency** (cross-cutting) | Can outsiders see all of the above? | public / observers |
| **Escalation** (cross-cutting) | Can a detection reach a corrector with power? | standing + appeal |

**Finding (O-D-02).** Capture resistance is a **pipeline with feedback**, not a set of independent mechanisms. The chain only delivers resilience if **Detection connects to a Corrector with power** (Transparency + Escalation closed into a loop). A break anywhere — especially detection→correction — collapses the family. *This is the architectural reason the family is "Mixed" (Round40-01 RQ-1): prevention is additive-ish, but detection→response→recovery is an irreducible loop.*

---

## 3. Lifecycle — threats differ by election stage

```
Pre-election → Registration → Nomination → Campaign → Voting → Counting → Certification → Appeal → Archival
  (rules)       (the roll)    (gatekeep)   (persuade) (choose) (tally)    (declare)      (contest) (preserve)
```

| Stage | Dominant capture vector | Most-needed sub-capability |
|-------|------------------------|----------------------------|
| Pre-election | rule-rigging (write the rules to pre-decide outcomes) | Prevention + Transparency |
| Registration | roll stuffing / eligibility manipulation / **suppression** | Detection + Accountability |
| Nomination | candidate **gatekeeping** capture | Prevention + Appeal |
| Campaign | disinformation, coercion, economic pressure | Detection + Transparency |
| Voting | coercion, vote-buying, ballot stuffing, **anonymity break** | Prevention + Containment |
| Counting | tally manipulation | Detection + Recovery |
| **Certification** | **capture of the certifying body** (the crown jewel) | Prevention + Escalation |
| Appeal | capture of the **appeals body** | Accountability + external escalation |
| Archival | **evidence destruction** (attacks Recovery & Accountability retroactively) | Transparency + immutable record |

**Finding (O-D-03).** **Certification and Appeal are the highest-value capture targets** — controlling who *declares* and who *reviews* the result captures the outcome regardless of the vote. *Interpretation:* this is why F-AUTH (who appoints these bodies) and F-REV (how they review) are *load-bearing dependencies* of capture resistance, and why **meta-capture** (capturing the appeals/oversight body) is the deepest threat. *Connects to the program's standing residual risk:* "no structurally-external vantage" (Option B + S-1..S-5; Meta-CVI *managed, not eliminated*).

---

## 4. Actors

| Actor | Role in the family | Capture if compromised |
|-------|--------------------|------------------------|
| Election authority | administers the process | total process capture |
| Independent oversight body | monitors, contains | detection blindness |
| Constitutional review body | interprets the rules | rule capture |
| Certifying body | declares results | outcome capture (crown jewel) |
| Appeals / adjudication body | resolves disputes | meta-capture (recursive) |
| Observers (domestic/international) | independent detection | loss of external eyes |
| Auditors | verify integrity, attribute | loss of attribution |
| External investigators | accountability beyond the system | loss of last-resort recourse |
| Candidates | raise challenges (standing) | silenced contestation |
| Citizens / voters | ultimate principals; transparency consumers | manufactured consent |
| Platform operators | technical substrate | substrate capture (→ software domain) |
| **Adversary** (faction / incumbent / external / insider) | the active threat | — |

**Finding (O-D-04).** Resilience requires **at least one actor the adversary cannot reach** in *each* of detection, escalation, and accountability. In a founding-stage voluntary diaspora association, the *structurally-external* actors (courts, state regulators, international bodies) are **weak or absent** — so the family must manufacture independence **functionally** (Option B), which is exactly the program's load-bearing condition and its dominant residual risk (F-4 / constitutional maturity).

---

## 5. Threat taxonomy (BEFORE mechanisms)

Two general axes plus a **capture-specific** axis that turned out to be the important one.

- **Origin:** Internal · External.
- **Nature:** Technical · Human · Political · Procedural · Organizational · Legal · Social · Economic · Information · Supply-chain.
- **Capture-specific (the discriminating axis):**
  - **Source capture** — control the appointer (→ F-AUTH dependency).
  - **Body capture** — control an oversight/certifying/appeal body.
  - **Process capture** — control the procedure (→ F-PROC dependency).
  - **Information capture** — control what is visible (disinformation / opacity).
  - **Meta-capture** — capture the body that *detects/corrects* capture (recursive; the deepest).

**Finding (O-D-05).** The capture-specific axis is **orthogonal to** Origin and Nature and is the one that actually predicts severity. *Interpretation:* a useful threat model for this family is the **capture target** (source / body / process / information / meta), not the threat's origin or technical nature. *Prediction-impact (10 %):* this is independent evidence that the family needs a **target/topology** view the per-mechanism D1–D5 model lacks (Round40-01 RQ-5).

---

## 6. Threat interactions

Threats do not occur independently — and the *interactions* are where capture actually succeeds.

| Interaction | Example in this domain |
|-------------|------------------------|
| **Reinforcing** | information capture + body capture (control the body *and* the narrative) |
| **Conflicting** | a safeguard vs a constitutional property (recovery that would break **anonymity**) |
| **Masking** | information capture **masks** body capture (opacity hides the takeover) |
| **Cascading** | body capture → process capture → outcome capture (one breach flows downstream) |
| **Recursive** | meta-capture (capture the detector → all other detections are nullified) |
| **Escalating** | small eligibility manipulation → roll capture → certification capture |
| **Feedback loop** | transparency → detection → challenge → correction (the *defensive* loop; the adversary attacks the loop itself) |

**Finding (O-D-06).** The decisive defensive structure is a **feedback loop** (transparency→detection→challenge→correction); the decisive *offensive* structure is **recursive meta-capture** (break the loop by capturing its corrector). *Interpretation:* capture resistance is fundamentally a contest **over the integrity of a feedback loop** — which is why prevention-only architectures fail and why the family is irreducibly Mixed.

---

## 7. Failure modes (how the family fails)

| Failure mode | Mechanism of collapse | Connection |
|--------------|----------------------|------------|
| **Captured detector (meta-capture)** | the body meant to detect capture is itself captured | deepest; Meta-CVI *managed not eliminated* |
| **Common-mode independence** | "distributed" bodies share a hidden common dependency (same appointer, same faction) → independence is nominal | attacks O-D-04; the B+C common-mode risk noted in prior rounds |
| **Detection without correction** | transparency exists but no actor has **standing or power** to act → the loop is open | breaks O-D-02 |
| **Legitimate-threshold capture** | a genuine supermajority faction captures *legally* — capture indistinguishable from a valid mandate | prevention thresholds cannot tell them apart |
| **Founding-stage maturity gap** | no track record, no precedent, no institutional memory to resist a determined first-mover | program's F-4 dominant residual risk / constitutional maturity thesis |
| **Safeguard–property conflict** | a recovery/audit action that would expose voters → cannot be used | anonymity invariant constrains the recovery sub-capability |

**Finding (O-D-07).** The family's **assumptions that fail first** are (1) that independent bodies are *actually* independent (common-mode), and (2) that detection *implies* correction (open loop). *Interpretation:* these two — not the absence of any mechanism — are where capture becomes possible. **Capture resistance is bounded above by the weakest of {real independence, closed correction loop}**, regardless of how many prevention mechanisms are stacked.

---

## 8. Mechanisms (as consequences of the architecture)

Now — and only now — mechanisms appear, each as the **realization of a sub-capability against a capture target**. (The same mechanisms catalogued in Round40-01 §2, re-derived top-down.)

| Sub-capability | Mechanism(s) | Counters which capture target |
|----------------|--------------|-------------------------------|
| Prevention | power dispersion (M-T9); supermajority/entrenchment (M-T6); distributed sources (M-T1); COI exclusion (M-T4) | source, body, process |
| Detection | transparency / public audit (M-T10); immutable audit trail (M-T11); observers | information, body, process |
| Containment | staggered terms (M-T2); quorum-diversity (M-T5) | body (limits how much one moment/faction takes) |
| Response/Escalation | standing to challenge + external appeal (M-T8) | body, meta |
| Recovery | re-run / invalidation procedures (constrained by anonymity) | process, outcome |
| Accountability | attribution via audit trail; external investigation | all (after the fact) |
| Anti-entrenchment | rotation / term limits (M-T3); insulation-with-removability (M-T7) | body, meta |

**Finding (O-D-08).** Mechanisms map **many-to-one** onto sub-capabilities and **one-to-many** onto capture targets — confirming that **the mechanism is the wrong unit of architecture**; the **sub-capability × capture-target** matrix is the architecture, and mechanisms are its fillings. *This is the domain-side restatement of Round40-01's "cluster, not family" result — arrived at independently from the domain, which strengthens it.*

---

## Candidate conceptual partitions (OBSERVATION ONLY — proto-bounded-contexts)

**Not DDD contexts. Not influencing this discovery.** Recorded for a future DDD Readiness Assessment, each classified Stable / Candidate / Experimental / Blocked.

| Candidate partition | Coheres around | Stability | Note |
|---------------------|----------------|-----------|------|
| **Oversight & Adjudication** | bodies that monitor/contain/rule | **Candidate-Stable** | recurs across F-AUTH, F-REV, F-THR — strongest |
| **Transparency & Disclosure** | making state visible | **Candidate** | cross-cutting; appears in F-PROC + F-THR |
| **Appeal & Contestation** | standing, challenge, external recourse | **Candidate** | recurs (F-REV anticipated) |
| **Threat Intelligence / Anticipation** | knowing adversaries | **Experimental** | new, n=1 |
| **Incident Response & Containment** | acting during an attack | **Experimental** | new, n=1 |
| **Accountability & Attribution** | who did it; consequences | **Experimental** | overlaps audit trail |
| **Recovery & Remediation** | restoring legitimacy | **Blocked** | conflicts with anonymity; unresolved |

**Discipline:** the only partition approaching cross-family stability is **Oversight & Adjudication** (it appears in every family so far). Even it stays **Candidate** — *not* promoted to Ubiquitous Language. DDD gate remains closed.

---

## Methodology measurement (the ≤10 %)

Deferred to `Round40-01` §5 (predictions measured, register update *proposed* not written, Draft ADRs DA-THR-01..06). One-line reconciliation with this domain pass: the domain architecture **independently reproduces** the "mechanism is not the unit" result (O-D-08 ↔ Round40-01 F-THR-COMPOSITE) and the "needs a topology/target view" result (O-D-05 ↔ RQ-5) — two routes, same conclusion, which raises confidence without any methodology change. **MB-39.1 unmodified; Register LOCKED; DDD GATED.**

---

## Executive summary (domain)

Threat & Capture Resistance is a **distinct family** whose subject is the *legitimacy and retention of control against an adversary* — depending on, but separate from, appointment integrity (F-AUTH), process integrity (F-PROC), and platform security. It has a real **internal architecture**: a feedback pipeline **Anticipate→Prevent→Detect→Contain→Respond→Recover→Account**, with **Transparency** and **Escalation** as cross-cutting concerns that must close the **detection→correction loop**. Threats differ by **lifecycle stage** (Certification and Appeal are the crown jewels) and are best modelled by **capture target** (source/body/process/information/**meta**), not by origin or technical nature. The family **fails** primarily through **meta-capture**, **common-mode pseudo-independence**, and **open correction loops** — and is **bounded above by the weakest of {real independence, closed correction loop}**, no matter how many prevention mechanisms are stacked. Mechanisms are **consequences**: fillings of a **sub-capability × capture-target** matrix, which is the actual architecture. The only conceptual partition near cross-family stability is **Oversight & Adjudication** — recorded as a Candidate, **not** promoted. Methodology stayed frozen; DDD stayed gated; the domain pass independently corroborated the methodology pass.

---

*Round 40-02 — Threat & Capture Resistance Architecture (domain discovery) — ISSUED under MB-39.1.*
*Boundary · sub-capability pipeline · lifecycle · actors · capture-target taxonomy · interactions · failure modes · mechanisms-as-consequences · candidate partitions (Oversight & Adjudication strongest). Methodology FROZEN, DDD GATED, Register LOCKED. Next: Methodology Governance Review (Round40-01 Draft ADRs) → possibly MB-39.2 → F-REV.*
