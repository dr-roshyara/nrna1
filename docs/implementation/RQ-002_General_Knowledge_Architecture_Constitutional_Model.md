# General Knowledge Architecture — Constitutional Model

**Kind:** constitutional modeling — the universal structure every knowledge domain must satisfy; the terminal synthesis artifact of RQ-002. NOT design: no folders, templates, software, or implementation.
**Status:** COMPLETE — presented for ARB review. **STOP: no domain-specific architecture may be derived until the ARB rules.**
**Evidence base (closed):** the full RQ-002 chain (charter · raw findings · discovery report · synthesis · taxonomy · meta-model) + the Engineering Platform Reference Architecture as first-party corroborating evidence. Confidence discipline: claims are marked universal-within-software-project-knowledge; cross-industry universality is extrapolation and is NOT asserted.

---

## 1. The Universal Dimensions — and the Purpose hypothesis, tested

**The instance model stands at three orthogonal dimensions plus one provisional scope:**

```text
Knowledge Instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )
    lifecycle regime  = f(Nature)
    validation method = f(Representation, Nature)
```

**The Purpose hypothesis: REAL, but REJECTED as an instance dimension.** Tested as instructed:

- *Creation-side purpose collapses into Nature.* "Why does this knowledge exist?" — a measurement exists to measure, a decision to decide, a finding to learn. The candidate values (Decide/Measure/Learn/Describe/Enforce/Operate) map onto nature values with one apparent exception, which resolves under scrutiny: the Infection baseline (Measurement) versus the `min-msi` ratchet (Enforce) is **not one instance changing purpose — it is two instances of different natures**, the Constraint *referencing* the Measurement. Purpose-shift at creation spawns a new instance; it never mutates the old one. (First-party evidence: exactly how the platform handled the A-3 baseline-then-ratchet policy.)
- *Consumption-side purpose is a property of the RELATION, not the instance.* The same ADR serves Decide-support for its author, Learn for an onboardee, Enforce-reference for a reviewer — simultaneously. Evidence: knowledge is consumed in fragments at the moment of a *task-situated* need (B); retrieval is task-driven (D, PK-P6); a runbook is validated by the purpose of its use.
- **Disposition:** Purpose is the **consumer-side selector** — the axis along which Discovery & Assembly filters the dimensional space ("canonical constraints for operating X"). It belongs to the retrieval act, not the stored instance. Adding it as an instance dimension would force one instance to carry many purposes, breaking orthogonality.

## 2. The Universal Invariants

All nine invariants of the Architectural Synthesis are confirmed **universal across knowledge domains** — none is domain-specific (each was traced to evidence from at least two unrelated domains in the corpus). One invariant is **added**, derived from the meta-model's transition insight:

10. **Representation transitions are deliberate, named acts that preserve nature and provenance.** Moving knowledge to a better representation (tacit rationale → decision record; prose rule → executable test; conversation → ADR) is the platform's core value-creating operation — and a silent transition (a summary that drops caveats, an encoding that changes the rule) is a defect. Evidence: D (compression decontextualization — evidence kept, caveats dropped, decisions silently change) · C (vaporization window) · B (the encode-or-decay gradient).

## 3. The Universal Governance Rules (PK-P1..P10, judged)

| Principle | Verdict |
|---|---|
| P1 Same-Population | **Universal** — the survival law; evidenced in KM, SE, DDD, AI domains alike |
| P2 Status-at-Discovery | **Universal** — the master failure signature appears in every domain studied |
| P3 Claim-Granularity | **Universal** (as reference model; composite artifacts remain legitimate) |
| P4 Execution-Proximity | **Universal as a gradient; domain-varying in population** — every domain trusts encoded over prose; how much of a domain *can* be encoded varies (engineering: most; business policy: less — but encoded policy still outranks prose policy) |
| P5 Two-Regime Evolution | **Universal by derivation** (= f(Nature); natures are universal) |
| P6 Minimal-Sufficient-Context | **Universal** — validated for human cognition and machine consumption independently |
| P7 Coexistence-with-Status | **Universal** — with the machine-consumer resolution gap open in every domain |
| P8 Staged-Forgetting | **Universal** — retirement was the least-designed stage in every field studied |
| P9 Succession-over-Capture | **Universal by theorem** (tacit representation cannot carry status → ungovernable as artifact — holds wherever the tacit column exists, i.e., everywhere humans work) |
| P10 Scent | **Universal** — foraging behavior evidenced for humans and mirrored by agentic search |

**Result: all ten are constitutional.** None demoted to domain-specific. The specializations differ in *weight and population* (§5), never in whether a rule applies.

## 4. The Universal Lifecycle

The eight-stage lifecycle (Creation → Qualification → Publication → Discovery & Consumption → Evolution-fork → Supersession → Historical Retention → Staged Retirement) is **universal for represented knowledge**, with two constitutional clarifications:

1. **The tacit column follows a parallel, shorter path** (built by practice → strengthened by use → lost at departure) — it never reaches Publication, which is *why* it is ungovernable (P9 theorem). The lifecycle governs knowledge that has crossed the representation boundary.
2. **The loop closes through consumption:** validation-is-use means Discovery & Consumption feeds Qualification continuously — a knowledge instance that is never consumed cannot maintain quality (A, B, D convergence). The lifecycle is a cycle with a feedback edge, not a pipeline.

No missing stages were evidenced. Stage *weights* vary by specialization (§5).

## 5. The Specialization Principle — and the domains

**A knowledge domain is a specialization of this model defined by: (a) which Nature×Representation cells it densely populates, (b) which validation machinery therefore dominates, (c) which authority scope its canon lives at, and (d) which lifecycle stages carry its risk.** Specializations never alter dimensions, invariants, or rules — only weights.

| Domain | Dense cells | Dominant validation | Canon scope | Risk-bearing stages | Evidence |
|---|---|---|---|---|---|
| **Engineering Knowledge** | Constraint×Executable · Measurement×Recorded · Decision×Recorded · Finding×Recorded(candidate) | Execution + instrument runs + claim evaluation (the promotion chain) | Platform / Constitutional | Qualification · Supersession | the existing platform (§7) |
| **Project Knowledge** | Definition×Conversational+Code · Decision×Recorded · Description×Recorded · Constraint×(Executable where encoded) | Conversation + review-with-code + enforcement-binding | Context-local / Project | Creation (capture window) · Discovery (the observed assembly gap) | B (DDD corpus), charter origin |
| **Runtime Knowledge** | Observation/Measurement×Recorded (instrument-produced) | The instrument run; freshness by construction (timestamps) | Project | Publication (status/provenance stamping) · Retirement (volume) | thin — carries D9's INSUFFICIENT EVIDENCE flag; EPIC-002's Evidence discovery produces the missing first-party evidence |
| **Business Knowledge** | Constraint×Recorded (policies) · Decision×Recorded (constitutional rulings) · Definition×Conversational | Human authority + encoding where possible (constitutional invariants AS fitness tests — anonymity CI-5/Q7 is a first-party existence proof of Business-Constraint×Executable) | Constitutional | Qualification (what may never be violated) · Historical Retention (rulings never vanish) | product constitution + platform practice |

## 6. The Knowledge Flow

The example flow (Research → Finding → Decision → Specification → Implementation → Qualification → Evidence → Learning) is **one domain's instantiation of a universal flow algebra**, not itself universal. The universal statement:

> **A knowledge flow is a sequence of two move types: NATURE DERIVATIONS (a new instance of a new nature referencing its source — finding→decision, decision→specification, measurement→constraint) and REPRESENTATION TRANSITIONS (the same nature moved to a better representation — tacit→recorded, prose→executable), closed into a loop by consumption feedback (evidence→finding).**

Corroboration: the Engineering Platform's own adopted loop — *Implementation → Execution → Evidence → Qualification → Retrospective → Platform Evolution* — parses exactly as such a sequence, and was adopted before this model existed. Each domain's flows are paths through the same dimensional space; the flows differ, the algebra does not.

## 7. The Deeper Question — answered by decomposition

> *Is the Engineering Platform one bounded context of a General Knowledge Architecture?*

**Yes — demonstrated, not asserted.** Every class of the platform's actual artifacts decomposes into the model with no residue and no new dimensions:

| Platform artifact | Nature | Representation | Status | Scope |
|---|---|---|---|---|
| ADR-AIP-01 | Decision | Recorded | Canonical | Platform |
| Rulings register entries | Decision | Recorded | Canonical/Superseded (append-only) | Platform |
| `merge-gate` / fitness suites | Constraint | Executable | Canonical | Project |
| Infection baseline | Measurement | Recorded (instrument) | Canonical-as-of-run | Project |
| OQ-ENG-001 | Measurement | Recorded (instrument) | Canonical w/ verdict history (PASS AFTER CORRECTION) | Platform |
| Pattern cards (EPC) | Finding | Recorded | Candidate (input-only until promoted) | Platform |
| EEP | Constraint (process) | Recorded | Canonical · Stable | Constitutional |
| Developer guides | Description | Recorded | Canonical (DoD-coupled) | Project |
| Session logs | Observation | Recorded | Historical (append-only) | Runtime |
| Sealed Baseline corpus | Decision+Description composite | Recorded | Historical (sealed) | Platform |

Furthermore, the platform independently converged on the constitutional invariants before the research existed (supersession-by-link, status history, demote-visibly, evidence-never-asserted, same-gesture guides). **The Engineering Platform is the General Knowledge Architecture's first implemented bounded context — specialized for engineering knowledge.**

**Consequence discipline (important):** this is a *model* conclusion, not a migration order. Nothing in the platform is restructured, renamed, or refactored to "match" the model (R-37, rule parsimony, and the model's own P1 all forbid it). The conclusion's value is forward-looking: future knowledge domains inherit a constitution that one context has already proven livable.

## 8. Readiness Assessment

| Question | Answer |
|---|---|
| Is the constitutional model complete enough to derive domain architectures? | **Yes** — dimensions, invariants (now ten), rules (all ten constitutional), lifecycle, specialization principle, and flow algebra are each evidence-anchored and mutually consistent (two principles now fall out as theorems). |
| Recommended derivation order | (1) **Project Knowledge Architecture** first — RQ-002's original commission, and the domain with the observed gap (context assembly); precondition: run **E-1, the EKP in-flow usage test** (still the cheapest decisive evidence). (2) **Engineering Knowledge Architecture** as a *descriptive* re-derivation of the existing platform — low urgency, zero refactoring; the platform already exists and the model explains it. (3) **Runtime Knowledge Architecture** only after EPIC-002's Evidence discovery supplies the missing first-party evidence (thread convergence). All sequenced behind the standing execution queue (C3, product primacy). |
| Remaining caveats | Cross-industry universality untested (software-project scope only) · Authority-Scope dimension provisional · Purpose-as-consumption-selector needs pilot evidence from a real assembly capability · machine-consumer conflict resolution still open (E-2) · discovery token economics still open (E-3). |

---

**Constraint check:** nothing designed; no new research; every section traceable to the closed evidence base; the deeper question answered by decomposition with first-party evidence. **STOP — awaiting ARB review before any domain-specific architecture is derived.**
