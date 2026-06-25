# Round 42-01 — Constitutional Governance Meta-Architecture: System Synthesis

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 42 · **Under MB-39.1 (frozen)**
**Status:** 📐 META-ARCHITECTURE — **program-canonical under MB-39.1; synthesized + hostile-tested, not externally verified.** The scientific foundation for Governance Ontology v1.0.
**Date:** 2026-06-25

> **Method.** Derive the system from the four families as *evidence*, then **try to destroy it** (hostile simplification). Keep only what survives removal. No ontology; no constitution change; governance claims = Draft GDRs.
> **⚠️ Scoping erratum (2026-06-25, post-review).** This document calls **Consent** the "root invariant." That is **over-claimed** — it is the anchor **only for the class {self-governing · no external sovereign · no coercive enforcement}** (NRNA's class). `Round42-02` **falsifies consent-as-universal** across system classes and generalizes the result: *every* constitutional system needs a recursion-terminating **trust anchor ∈ {coercion, consent}**; consent is NRNA-class-specific. `Round42-02` also adds **hostile substitution** and the **Architectural Primitive Matrix**, and reframes this round as a **Constitutional Governance Reference Model.** Read 42-02 alongside this.

---

## 1. The constitutional governance machine

The four families compose into a **closed-loop control system** whose **setpoint is legitimacy** and whose **power supply is consent**:

```
            ┌──────────────── power supply: CONSENT (constituent body) ───────────────┐
            │                                                                          │
 calibrate          plant            sensor          comparator        actuator        │
 (F-AUTH) ──────► (F-PROC) ─────► (F-THR) ───────► (F-REV) ─────────► correction ───────┤
 independence     the election    detection/      independent        enforcement-by-   │
 source           process         transparency    finality           acceptance        │
            │                                                                          │
            └────── setpoint: LEGITIMACY ◄── feedback: did the constituent ACCEPT? ─────┘
```

- **F-AUTH** = the *calibration* (sets independence — the institutional precondition).
- **F-PROC** = the *plant* (produces the outcome and the reviewable evidence).
- **F-THR** = the *sensor* (detects deviation/capture; cross-cutting — wraps the whole loop).
- **F-REV** = the *comparator* (compares against the constitution and issues binding, **independent finality**).
- **Correction** = the *actuator*, whose force is **acceptance** (no coercion available).
- **Consent** = the *power supply*; **Legitimacy** = the *setpoint/output*.

**Finding (M-01).** The governance system is a **feedback controller, not a hierarchy.** Its defining feature is that the actuator has **no coercive force** — correction takes effect only through acceptance — so the loop is powered by, and continuously draws down, **consent.**

---

## 2. Emergence hierarchy (Q3, Q6 — primitive / derived / emergent)

| Layer | Element | Why |
|-------|---------|-----|
| **L0 Primitives / inputs** | **Consent** · **Evidence** · **Institutional independence (S-3 given)** | not produced by the system; assumed/supplied (consent), recorded (evidence), constitutionally granted (institutional independence) |
| **L1 Derived** | Operational independence · Decisional independence · Finality · Authority · Transparency · Perceived independence | each is produced *from* L0 by a capability (e.g. decisional independence ⟸ operational ⟸ institutional) |
| **L2 Emergent** | **Resilience** · **Legitimacy** | arise only from the *whole loop* operating; owned by no capability |

**Finding (M-02).** **Legitimacy is emergent (derived), never primitive** — it is the setpoint the loop produces, never an input. **Consent is primitive** — it is the input the loop consumes. *These are different nodes:* the common temptation to equate "legitimacy" and "consent" is wrong — consent is the **fuel**, legitimacy is the **output**. *(Answers Q6: legitimacy is derived/emergent.)*

---

## 3. Master dependency graph (Q7)

```
Constitution (S-1..S-5, anonymity)
   │ expresses (Q8 reduction)
   ▼
Architectural principles:  INDEPENDENCE · FINALITY · CONTESTABILITY
   │ grounds
   ▼
Capabilities:  F-AUTH ─► F-PROC ─► [F-THR cross-cutting] ─► F-REV
   │ owned by / realized by
   ▼
Actors → Responsibilities → Mechanisms
   │ produce
   ▼
EVIDENCE ─► (reviewed under INDEPENDENCE, terminated by FINALITY) ─► TRUST(consent) ─► LEGITIMACY
   ▲                                                                                      │
   └───────────────────────── feedback: acceptance renews/withdraws consent ─────────────┘
```

**Finding (M-03).** The graph has a **single output sink (Legitimacy)** and a **single power source (Consent)**, connected by **one path** that must pass through evidence → independence → finality. Cut that path anywhere and legitimacy stops. *(This is the system-level form of "bounded by the weakest link.")*

---

## 4. Hostile simplification (Q8, Q9 — try to destroy it)

| Removal attempt | Result | Survives? |
|-----------------|--------|-----------|
| Remove **F-AUTH** | no institutional independence → no decisional independence → captured review → no legitimacy | **load-bearing — keep** |
| Remove **F-PROC** | no reviewable evidence → review is arbitrary → no legitimacy | **load-bearing — keep** |
| Remove **F-THR** | capture undetected → loop opens silently → legitimacy erodes | **load-bearing — but cross-cutting, not a peer** |
| Remove **F-REV** | no finality → infinite contestation → no legitimacy | **load-bearing — keep** |
| Merge **F-AUTH + F-REV** | appointer adjudicates its own appointments → separation of duties lost → recaptures | **cannot merge — keep separate** |
| Remove **Consent** | actuator has no force (no coercion) → rulings unenforceable | **irreducible (given no external sovereign)** |
| Remove **Finality** | infinite appeal → no legitimacy | **irreducible** |
| Remove **Independence** | captured comparator → no legitimacy | **irreducible** |
| Remove **Recursion-termination** | no defined trust anchor → system cannot close | **irreducible** |
| Reduce **S-1..S-5 → 3 principles** | S-1+S-3 ⇒ Independence; S-2 ⇒ Finality; S-4+S-5 ⇒ Contestability | **survives — real simplification** (architectural observation; Constitution unchanged) |
| Remove **anonymity** | changes what the system *is* (anonymous voting) — not removable, it is a defining constraint | **keep as constraint (bounds correctability)** |

**Finding (M-04, the key simplification).** What survives hostile removal:
- **3 peer capabilities** — *Independence-source (F-AUTH)*, *Evidence-production (F-PROC)*, *Independent-finality (F-REV)* — **plus 1 cross-cutting concern** *Capture-resistance (F-THR)*. **The "4 peer families" model is wrong; it is 3 + 1.** *(Confirms RQ-THR-01.)*
- **3 architectural principles** — *Independence · Finality · Contestability* — express S-1..S-5. *(Confirms RQ-CONST-01 as an architectural reduction; the Constitution still lists S-1..S-5.)*
- **2 primitives** — *Consent · Evidence* — and **1 constraint** — *anonymity*.
Everything else is derived or emergent. Nothing else survived as fundamental.

---

## 5. Minimal sufficient architecture (Q2, Q4)

```
MINIMAL CONSTITUTIONAL GOVERNANCE ARCHITECTURE (preserves legitimacy):

  Consent (anchor)  +  Evidence (record)  +  Independence-source
        │                    │                      │
        └──────────► Independent Finality ◄──────────┘   (the comparator)
                            │
                  Capture-resistance (cross-cutting, protects all of the above)
                            │
                            ▼
                        LEGITIMACY (emergent)

  Recursion terminates at: fiat (finality) / axiom (evidence trusted) / consent (anchor).
```

**Finding (M-05).** The minimal sufficient architecture is **{Consent, Evidence, Independence-source, Independent-finality} + Capture-resistance (cross-cutting) + a recursion-termination rule.** This is strictly smaller than the four-peer-family picture: **F-THR is demoted to cross-cutting, S-1..5 collapse to 3 principles, and legitimacy/authority/resilience drop out as emergent (not architecture, but output).**

**Foundational vs dependent (Q4):**
- **Foundational:** Consent (anchor) and Independence-source (without it, finality is captured).
- **Most-depended-on capability:** Independent-finality (F-REV) — the convergence point.
- **Disappears-if-another-disappears:** Legitimacy disappears if *any* of {consent, evidence, independence, finality} disappears → legitimacy is the conjunction, not a part.

---

## 6. Center of gravity (Q5 — falsify "consent")

The candidate centers were Authority, Review, Capture, Transparency, and now **Consent**. Hostile evaluation of "is consent the center?":

- Consent is **not a capability** (the system doesn't *produce* it).
- Consent is **not emergent** (it's an input, not output).
- Consent is **partly endogenous** — the system can *erode* it (opacity) or *manufacture* it (information capture, F-THR) — so it is **not a purely external assumption** either.
- Consent **is the root invariant and the power supply** — every recursion terminates there, and the actuator runs on it.

**Finding (M-06).** **The architectural center of gravity is the *independent-finality node powered by consent* — i.e., the comparator–power-supply pair — not "consent" alone.** Consent is the **anchor/fuel**; the **closed correction loop** is the **structure**; the point where they meet — *an independent body issuing binding finality that the constituent accepts* — is the center. *Caveat (anti-elegance):* "consent is the center" is seductive and should be resisted; consent is better modeled as **root invariant + attack surface** than as the center. *Confidence: Medium; competing model (center = finality-under-independence) retained.*

**Is consent a capability / primitive / emergent / external / root invariant?** → **root invariant + partial attack surface.** Not a capability; not emergent; not purely external.

---

## 7. System trust model

```
Trust does NOT reside in any organ.
   F-AUTH trusted? only if independent (S-3) — which is granted, an AXIOM.
   F-PROC trusted? only via its EVIDENCE — an AXIOM (the record is believed).
   F-REV trusted?  only if independent + final — and final by FIAT.
   All organ-trust bottoms out in →  CONSENT of the constituent.
Therefore: trust is anchored in the CONSTITUENT, distributed through the loop,
   and terminated by fiat/axiom/consent — never proven.
```

**Finding (M-07).** The system has **no trusted organ** — trust is anchored in the **constituent (consent)** and merely *delegated* through independence/evidence/finality. *(This is the system-level statement of the F-REV trust-anchor result, OQ-38B05-05.)*

---

## 8. Architectural invariants (Q10 — derived, not assumed)

Each derived from the four families + §§1–7 (not posited):

| # | Invariant | Derived from |
|---|-----------|--------------|
| **INV-A1** | Binding authority requires legitimacy (else not accepted) | F-REV consent-based authority |
| **INV-A2** | In the absence of coercion, legitimacy requires **consent** | F-REV (no external sovereign) |
| **INV-A3** | Legitimacy requires **finality** | F-REV (infinite appeal → no legitimacy) |
| **INV-A4** | Decisional ⟹ Operational ⟹ Institutional independence (strict) | F-REV independence chain |
| **INV-A5** | Every correction loop requires **evidence** | F-PROC→F-THR→F-REV |
| **INV-A6** | A constitution with no external sovereign terminates recursion by **fiat / axiom / consent** (never proof) | F-REV recursion theory |
| **INV-A7** | **Correctability is bounded by anonymity** (irreversible harm → partial recovery) | F-REV + anonymity invariant |
| **INV-A8** | Consent-based legitimacy requires **perceived** independence (perception-capture defeats actual independence) | F-REV 4th independence |

**Finding (M-08).** These eight are **architectural** invariants (about the structure of constitutional governance), distinct from the program's *methodology* invariants (INV-1..5) and the *Constitution's* principles (MC/S). They are candidates for the future ontology's axioms — **recorded, not frozen.**

---

## 9. The meta-model (what survived) + deltas

**The surviving meta-model:** a **consent-powered, evidence-fed, independence-calibrated, finality-closing feedback controller** whose emergent output is legitimacy, protected by cross-cutting capture-resistance, with trust anchored in the constituent and recursion terminated by fiat/axiom/consent.

**Draft Governance Decision Records (proposals only):**
- **DGR-MA-01:** the architecture is **3 peer capabilities + 1 cross-cutting** (not 4 peer families). *(absorbs DGR-REV-04, RQ-THR-01)*
- **DGR-MA-02:** S-1..S-5 express **3 architectural principles** (Independence/Finality/Contestability) — *architectural reduction, Constitution unchanged.* *(RQ-CONST-01)*
- **DGR-MA-03:** **Legitimacy is emergent; Consent is primitive** — never conflate. *(M-02)*
- **DGR-MA-04:** **Center of gravity = independent-finality powered by consent** (not consent alone). *(M-06)*
- **DGR-MA-05:** eight **architectural invariants INV-A1..A8** are the candidate ontology axioms.
- **DGR-MA-06:** **no organ is trusted**; trust anchored in the constituent. *(M-07)*

**Prediction / RQ impact (proposed):** P-PROFILE — F-THR-as-cross-cutting explains why its profile was "Mixed" (it is not a peer with a single profile); **DA-THR-01/M-07 "cluster-as-unit"** now reads as an artifact of analyzing a *cross-cutting* concern as if it were a family → strengthens the methodology observation that the **unit must match the element's architectural role.** Recorded for the Methodology Governance Review; **not enacted.**

**DDD discipline:** candidate ontology anchors now have *system-level* grounding — **Consent, Independence (4-concept), Finality, Legitimacy (emergent), Evidence.** Classified **Candidate** (Consent, Finality, Evidence), **Candidate** (Independence-4), **Experimental** (the 3+1 capability cut). **None promoted.** DDD gate closed; SI-01/SI-02 still gate the language until the Ontology round.

---

## 10. Executive summary

The constitutional governance system is **not a hierarchy of four families but a consent-powered feedback controller**: F-AUTH calibrates independence, F-PROC is the plant producing evidence, F-THR is the cross-cutting sensor, F-REV is the comparator issuing **independent finality**, and **correction takes effect only by acceptance** because no coercion is available. Under **hostile simplification** the architecture collapses cleanly: **3 peer capabilities + 1 cross-cutting concern** (F-THR is not a peer), **3 architectural principles** (Independence/Finality/Contestability) expressing S-1..S-5, **2 primitives** (Consent, Evidence) and **1 constraint** (anonymity). **Legitimacy is emergent, never primitive; consent is the primitive fuel and the root invariant** — and the **center of gravity is the independent-finality node powered by consent**, *not* consent alone (an elegance worth resisting). **No organ is trusted**; trust is anchored in the constituent and every recursion terminates by **fiat/axiom/consent, never proof** (INV-A6). Eight **architectural invariants (INV-A1..A8)** were *derived* (not assumed) and are the candidate axioms for the ontology.

Nothing was frozen. The Constitution, MB-39.1, the locked Register, and the DDD gate are unchanged; six Draft GDRs and one methodology observation are recorded for the Governance Ontology round and the Methodology Governance Review. Population: NRNA, 4 families, below saturation, externally unvalidated. **The architecture is now stable under hostile simplification — the precondition for building Governance Ontology v1.0.**

---

*Round 42-01 — Constitutional Governance Meta-Architecture — ISSUED (descriptive; program-canonical under MB-39.1; synthesized + hostile-tested, not externally verified).*
*Machine = consent-powered feedback controller · 3 peer capabilities + 1 cross-cutting · 3 principles · 2 primitives + 1 constraint · legitimacy emergent / consent primitive · center = independent-finality⊗consent · no trusted organ · INV-A1..A8 derived. Survives hostile simplification → Ontology v1.0 now admissible. Next: Governance Ontology v1.0 → Methodology Governance Review. MB-39.1 FROZEN · DDD GATED.*
