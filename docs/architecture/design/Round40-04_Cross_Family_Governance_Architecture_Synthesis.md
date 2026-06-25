# Round 40-04 — Cross-Family Governance Architecture Synthesis

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Cross-Family Governance Architecture · **Under Methodology Baseline MB-39.1 (frozen)**
**Role:** Senior Governance / Constitutional / Election-Security / Strategic-Domain-Discovery Architect.
**Status:** 📐 CANONICAL CROSS-FAMILY ARCHITECTURE (descriptive) — the architectural baseline before F-REV.
**Date:** 2026-06-25

> **Scope.** Integrate the completed family architectures — **F-AUTH** (appointment/authority integrity, *emergent*), **F-PROC** (process integrity, *additive*), **F-THR** (threat/capture resistance, *mixed*) — into one governance capability architecture, and locate **F-REV** (review/adjudication) as the *anticipated* next family. **Synthesis, not discovery.**
> **Discipline.** F-REV relationships are marked **[PREDICTED]** — to be tested when F-REV runs, not claimed as found. No methodology/Register/ADR/DDD/software change. Constitutional properties **mapped, never modified.**

---

## D1 — Cross-Family Capability Graph

```
        CONSTITUTION  (S-1..S-5, anonymity invariant)
              │ grounds
              ▼
        ┌─────────────┐
        │   F-AUTH     │  appointment/authority integrity — FOUNDATIONAL
        │  (emergent)  │  produces: legitimate, independent bodies
        └─────┬────────┘
              │ enables (independence)                 ┌───────── cross-cutting ─────────┐
              ▼                                        │            F-THR                 │
        ┌─────────────┐   produces reviewable          │     (mixed) capture resistance   │
        │   F-PROC     │──── evidence ────────────────►│  PROTECTS F-AUTH, F-PROC, F-REV  │
        │  (additive)  │                               └───────────────┬──────────────────┘
        └─────┬────────┘                                               │ escalates to
              │ feeds                                                  ▼
              │                                              ┌───────────────────┐
              └─────────────────────────────────────────────►│  F-REV [PREDICTED] │ review/adjudication
                                                             │  binding decision  │ — TERMINAL + recursive
                                                             └─────────┬──────────┘
                                                                       │ emerges into
                                                                       ▼
                                                               ┌──────────────┐
                                                               │  LEGITIMACY  │  EMERGENT (owned by no family)
                                                               └──────────────┘
```

| Tier | Family | Basis |
|------|--------|-------|
| **Foundational** | F-AUTH | everything assumes legitimately-appointed, independent bodies |
| **Dependent** | F-PROC; F-REV [PREDICTED] | process needs legitimate authority; review needs legitimacy + evidence |
| **Cross-cutting** | **F-THR** | not a peer in a line — it *protects every other family* (appointment, process, review) |
| **Emergent** | Legitimacy | arises from the composed loop; not built by any single family |

**Finding (X-01).** F-THR is **structurally unlike** F-AUTH/F-PROC: it is **cross-cutting**, not sequential. The three families do not form a simple chain — F-AUTH→F-PROC→F-REV is a chain, and **F-THR wraps all three.** *This is a genuine architectural discovery only visible across families* (cf. D9 split/merge question).

---

## D2 — Family Responsibility Map

| Family | Protects | Consumes | Enables (produces) | Depended on by |
|--------|----------|----------|--------------------|----------------|
| **F-AUTH** | the legitimacy of all bodies | constitution (S-1/S-3) | independent, mandated bodies | F-PROC, F-THR, F-REV |
| **F-PROC** | correctness of the vote | F-AUTH (legitimate administrators) | reviewable evidence / audit trail | F-THR (detection input), F-REV (evidence) |
| **F-THR** | F-AUTH + F-PROC + F-REV against capture | F-AUTH (independence), F-PROC (evidence) | a closed detection→correction loop | the whole architecture's resilience |
| **F-REV** [PREDICTED] | binding resolution of disputes | F-AUTH (legitimacy), F-PROC (evidence), F-THR (detection) | final rulings (S-2); precedent | F-THR (its corrector), Legitimacy |

**Finding (X-02).** The map exposes a **production→consumption spine**: F-AUTH produces *independence*, F-PROC produces *evidence*, F-THR produces *a closed loop*, F-REV produces *binding rulings*. Each family's *product* is another family's *required input*. **No family is self-sufficient** — which is itself the structural reason the program needs a *cross-family* architecture, not four standalone ones.

---

## D3 — Inter-Family Information Flow

```
Appointment (F-AUTH) ─► Independent bodies stand up
        │
        ▼
Election Process (F-PROC) ─► produces Evidence (audit trail, process records)
        │                          │
        │                          ▼
        │                  Detection (F-THR) ─► Verified findings
        │                          │
        ▼                          ▼
        └────────────► Oversight/Escalation (F-THR) ─► Appeal/Review (F-REV) ─► Binding Decision
                                                                  │
                                                                  ▼
                                                            LEGITIMACY (public acceptance)
        ▲                                                         │
        └──────── Feedback: rulings (F-REV) update appointment norms (F-AUTH) & process (F-PROC) ◄┘
```

- The **forward spine** is Appointment → Process → Evidence → Detection → Review → Legitimacy.
- The **feedback edge** (F-REV rulings → F-AUTH/F-PROC norms) is how the system *matures* across elections — the cross-family version of Round40-03's Archive→Learning loop.
- **Anonymity invariant** sits across F-PROC↔F-THR↔F-REV: evidence must be *reviewable without being de-anonymizing* — a shared constraint that shapes every flow.

---

## D4 — Constitutional Dependency Graph

| Property | F-AUTH | F-PROC | F-THR | F-REV [PRED] | Type |
|----------|--------|--------|-------|--------------|------|
| **S-1** entrenched mandate | **Direct** | indirect | indirect | Direct | structural anchor |
| **S-2** final rulings | indirect | — | indirect | **Direct** | decision terminus |
| **S-3** appointment insulation | **Direct** | indirect | **Direct** (independence) | **Direct** | **common keystone** |
| **S-4** election-oversight jurisdiction | indirect | **Direct** | **Direct** | **Direct** | authority to act |
| **S-5** standing to challenge | indirect | — | **Direct** (closes loop) | **Direct** | contestation path |
| **Anonymity invariant** | — | **Direct** (constrains) | Direct (caps remediation) | Direct (caps attribution) | shared *negative* constraint |

**Finding (X-03).** **S-3 is the single most depended-upon property** — it is *Direct* for F-AUTH, F-THR, and F-REV. **Independence across the entire governance architecture rests on one safeguard.** That makes S-3 both the keystone *and* the dominant **common-mode failure point** (D6). The anonymity invariant is the only property that constrains *negatively* across three families — a deliberate, architecture-wide ceiling on remediation and attribution.

---

## D5 — Governance Recursion Analysis

**The central recursion (GRP-THR-REV).**

```
F-THR  needs  F-REV   (a captured situation must reach a binding corrector)
F-REV  needs  F-THR   (the review/appeal body must itself be capture-resistant)
        └────────── mutual dependency = Governance Recursion Point ──────────┘
```

This is the cross-family form of the program's deepest open question — *"where is the final constitutional trust anchor allowed to reside?"* The detector needs an adjudicator; the adjudicator needs to be protected by the detector. In a system with a **structurally external** vantage (a state court), the recursion terminates outside. In a **founding-stage voluntary diaspora association**, there is **no external terminus** — so the recursion is closed *internally* by **functional independence (Option B) + S-1..S-5**, and the residual **meta-capture** risk is **managed, not eliminated** (consistent with the 38C-15 ruling; Meta-CVI managed).

**Secondary recursion (F-AUTH ↔ F-THR):** F-THR assumes F-AUTH delivered real independence, but F-AUTH's appointees must be *protected* by F-THR. Mutual, but weaker — mediated by S-3.

**Finding (X-04).** The architecture contains **at least one irreducible governance recursion** (GRP-THR-REV) with **no internal terminus**. This is not a defect to remove; it is the structural signature of self-governance without an external sovereign. The architecture's honesty is in **naming and bounding** it (S-1..S-5 + a declared managed residual), not in pretending it is closed.

---

## D6 — Cross-Family Failure Analysis

| Failure class | Example | Reach |
|---------------|---------|-------|
| **Single-family** | F-PROC tally bug | local; produces bad evidence but is detectable by F-THR |
| **Cascading** | F-AUTH capture → illegitimate bodies → F-THR has no real independence → F-REV rulings are corrupt | **whole architecture** |
| **Common-mode** | **S-3 fails** → independence collapses simultaneously for F-AUTH, F-THR, F-REV | **whole architecture, instantly** |
| **Common-mode (constraint)** | anonymity forces Contained-Only across F-THR & F-REV | bounded, permanent, by design |
| **Recursive** | meta-capture of F-REV → F-THR's loop opens (no corrector) → undetected capture | **whole architecture, silently** |

**Finding (X-05).** **Two failure points threaten the entire architecture: S-3 (common-mode independence) and meta-capture of F-REV (recursive).** Every other failure is local or detectable. *Therefore the architecture's assurance is dominated by exactly two things — keeping S-3 sound and keeping F-REV capture-resistant* — which is the cross-family confirmation of Round40-03's "bounded by the weakest of {real independence, closed loop}," now generalized: **bounded by the weakest of {S-3 independence, F-REV capture-resistance}.**

---

## D7 — Cross-Family Architectural Views

- **Dependency View** — D1 graph: F-AUTH foundational, F-THR cross-cutting, Legitimacy emergent.
- **Responsibility View** — D2 spine: each family's product is another's required input; none self-sufficient.
- **Information View** — D3: Appointment→Process→Evidence→Detection→Review→Legitimacy + maturity feedback.
- **Constitutional View** — D4: S-3 keystone (independence), anonymity shared negative constraint.
- **Failure View** — D6: S-3 common-mode + F-REV meta-capture are the architecture-level risks.
- **Lifecycle View** — families activate across the election timeline: F-AUTH (pre-election standup) → F-PROC (registration..counting) → F-THR (throughout, cross-cutting) → F-REV (certification..appeal..archival).

**Cross-view invariant (architecture-level).** All six views reduce to one statement: **the governance architecture is a self-governing loop with no external sovereign, anchored on S-3-borne independence and terminated (recursively) at F-REV.** Its strength is exactly the strength of that anchor and that terminus.

---

## D8 — Candidate Stable Governance Domains (recurring across families)

Domains that appear in **two or more** completed families (cohesion across the program, not within one family). **No Strategic DDD — identification only.**

| Candidate governance domain | Recurs in | Stability | Note |
|-----------------------------|-----------|-----------|------|
| **Oversight & Adjudication** | F-AUTH (appoints them) · F-THR (protects them) · F-REV (is them) | **Stable** | appears in **every** family — strongest cross-family domain |
| **Independence** | F-AUTH · F-THR · F-REV (all S-3-borne) | **Stable** | the keystone property; cross-family |
| **Evidence & Audit** | F-PROC (produces) · F-THR (consumes) · F-REV (consumes) | **Candidate-Stable** | the reviewable-record spine |
| **Transparency & Disclosure** | F-PROC · F-THR | **Candidate** | cross-cutting visibility |
| **Appeal & Contestation** | F-THR (escalation) · F-REV (core) | **Candidate** | standing + recourse |

**Discipline.** Two domains now reach **Stable** cross-family recurrence — **Oversight & Adjudication** and **Independence**. They are recorded for the eventual DDD Readiness Assessment as the **first candidate ubiquitous-language anchors** — but **not** promoted; the DDD gate is untouched. (Per MC-08: stable recurrence ≠ established; F-REV may still disturb them.)

---

## D9 — Research Synthesis (completeness · missing families · split/merge)

**Do completed families appear complete?** F-AUTH, F-PROC, F-THR each have internal architectures (40-02/40-03 + prior rounds) and compose without orphan capabilities — **internally** complete. The *set* is **not** complete (F-REV outstanding; see below).

**Possibly-missing families (observations only):**
- **Eligibility / Franchise integrity** — *who is entitled to participate* (voter-roll legitimacy, enfranchisement) is not cleanly owned by F-AUTH (appoints *officials*, not voters), F-PROC (assumes a roll), or F-THR (defends a roll it doesn't define). **Candidate missing family.**
- **Transparency / Disclosure** — currently cross-cutting; may deserve standing as its own family (it recurs strongly). **Open.**

**Split/merge questions (observations only):**
- **F-THR may be cross-cutting rather than a peer family** (X-01) — it protects the others. *Scientific question:* is "capture resistance" a *family* or a *quality attribute of every family*? Evidence leans **cross-cutting**, but it has enough internal architecture to remain a study unit. **Unresolved — record, do not decide.**
- **F-AUTH and F-REV** both inhabit "Oversight & Adjudication" — *could* be one meta-family (appointment + review of the same bodies). But appointment ≠ adjudication (different lifecycle, different mechanisms). **Leave separate pending F-REV.**

**Finding (X-06).** Three families in, the taxonomy is **provisionally flat but probably not** — there is evidence for (a) a **missing Eligibility family**, and (b) **F-THR as cross-cutting** rather than peer. *These are recorded as research observations; per MB-39.1 they change nothing now* and are the natural inputs to a post-F-REV taxonomy review.

---

## D10 — Executive Cross-Family Governance Architecture Summary

Across three completed families the NRNA governance architecture is a **self-governing loop with no external sovereign.** **F-AUTH** is foundational — it produces the legitimate, independent bodies everything else assumes; **F-PROC** produces the reviewable evidence; **F-THR** is **cross-cutting**, wrapping and protecting the others with a closed detection→correction loop; and **F-REV** [PREDICTED] is the terminal, binding adjudicator. Their products chain (independence → evidence → closed loop → rulings → **emergent Legitimacy**), and **no family is self-sufficient.** Constitutionally, the whole architecture rests on **S-3** (appointment insulation = independence) as its single keystone and dominant common-mode point, with the **anonymity invariant** as a deliberate architecture-wide ceiling on remediation and attribution. The architecture contains an **irreducible governance recursion** — F-THR needs F-REV and F-REV needs F-THR — with **no internal terminus**, closed only functionally (Option B + S-1..S-5) and carrying a **managed (not eliminated) meta-capture residual**. Architecture-level assurance is therefore **bounded by the weakest of {S-3 independence, F-REV capture-resistance}**. The strongest **recurring governance domains** are **Oversight & Adjudication** and **Independence** (both now cross-family **Stable** candidates, recorded not promoted). Open research observations: a **possibly-missing Eligibility/Franchise family** and the question of whether **F-THR is a peer family or a cross-cutting quality** — both deferred, unchanged, to the post-F-REV review.

This is the canonical cross-family architectural baseline. It introduces no evidence, changes no methodology, modifies no constitutional property, enacts no ADR, and performs no software design. **F-REV is the next discovery; the Methodology Governance Review of DA-THR-01..06 follows it.**

---

*Round 40-04 — Cross-Family Governance Architecture Synthesis — ISSUED (canonical, descriptive).*
*F-AUTH foundational · F-PROC evidence · F-THR cross-cutting · F-REV [PREDICTED] terminal+recursive · Legitimacy emergent. Keystone S-3; GRP-THR-REV recursion with no external terminus; assurance bounded by {S-3, F-REV}. Stable domains: Oversight & Adjudication, Independence. Open: missing Eligibility family?; F-THR peer-vs-cross-cutting? MB-39.1 FROZEN · Register LOCKED · DDD GATED.*
