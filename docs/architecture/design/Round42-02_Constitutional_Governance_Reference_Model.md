# Round 42-02 — Constitutional Governance Reference Model (Substitution · Consent-Falsification · Primitive Matrix)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 42 (reference modelling) · **Under MB-39.1 (frozen)**
**Status:** 📐 REFERENCE MODEL (descriptive) — **program-canonical under MB-39.1; synthesized + hostile-tested, not externally verified.** The bridge to Governance Ontology v1.0.
**Date:** 2026-06-25

> **What this adds to `Round42-01`.** (1) Reframes the synthesis as a **Reference Model** — *what must exist in any constitutional governance system regardless of implementation* — distinct from architecture (*how a given system is organized*). (2) Adds **hostile substitution** (can X be replaced by Y?) to hostile removal. (3) **Falsifies "consent is the root invariant"** across system classes. (4) Produces the **Architectural Primitive Matrix.**
> **Gates.** No ontology; no methodology change; Constitution mapped not modified; governance claims = Draft GDRs. MB-39.1 frozen · Register locked · DDD gated. Synthesized + hostile-tested, **not** externally verified.

---

## 1. Reference model vs architecture (the distinction that enables DDD)

| | Asks | Examples |
|---|------|----------|
| **Architecture** | *How is **this** system organized?* | review tribunal · Election Authority · appeals body · audit-trail store |
| **Reference model** | *What must exist in **every** constitutional governance system, regardless of implementation?* | Finality · Authority-Delegation · Independence · Contestability · Evidence · Recursion-Termination · (emergent) Legitimacy |

**Finding (R-01).** Strategic DDD must be built on the **reference-model layer**, not the architecture layer — *"review tribunal"* is one implementation of the reference-model concept *Finality*; *"Election Authority"* is one implementation of *Authority-Delegation*. **The reference model is the domain; an architecture is one realization of it.** This is why the ontology must name reference-model concepts, not architecture artifacts.

---

## 2. Hostile substitution (can X be *replaced* by Y? — reveals non-independence)

Removal asks *can it disappear?*; substitution asks *can another concept do its job?* If yes, the two are not architecturally independent.

| Substitution test | Result | Implication |
|-------------------|--------|-------------|
| **Independence ← Transparency?** | Partial — a fully transparent but captured review still *rules*; transparency only defeats capture **via the consent channel** (members see → withdraw consent) | Independence & Transparency are **not orthogonal** — coupled through legitimacy; transparency partially covers independence-loss **only** in a consent system |
| **Review ← (other finality mechanisms)?** | **Yes** — finality can be produced by time-bounded auto-finalization, supermajority lock, etc., not only by a review tribunal | **Review is a MECHANISM; Finality is the PRIMITIVE** it produces |
| **Transparency ← Review?** | No — review *decides*; transparency *exposes*. Different functions | both needed; not substitutes |
| **Finality ← Review?** | No — review without finality (endless re-review) fails | Finality is the necessary *output*, not the process |
| **Independence ← Consent?** | No — consent is the *anchor*; independence is the *quality that earns/maintains* consent; informed consent to a dependent arbiter is fragile (manufactured-consent risk) | distinct; consent does not replace independence |
| **Transparency ← Accountability?** | No — accountability *requires* transparency (cannot hold accountable what cannot be seen) | **Accountability DEPENDS ON Transparency → Transparency more primitive** |

**Finding (R-02).** Substitution reveals what removal could not:
- **Review is replaceable → it is a mechanism, not a primitive; Finality is the primitive.**
- **Transparency and Independence are coupled (not orthogonal)** — they substitute partially through the consent/legitimacy channel.
- **Accountability ⊃ depends-on Transparency**, so Transparency is the more primitive of the two.
*Removal alone would have mislabeled Review as fundamental; substitution corrects it.*

---

## 3. Consent falsification across system classes (the key correction)

`Round42-01` over-claimed **Consent as "the root invariant."** Test it against other constitutional system classes:

| System class | Trust anchor / recursion terminator | Is consent the anchor? |
|--------------|-------------------------------------|------------------------|
| **Founding-stage voluntary diaspora (NRNA)** | **Consent** — no coercion available; rulings hold by acceptance | **Yes (this class)** |
| Nation-state with judicial enforcement | **Coercion** (courts, police) — rulings enforced regardless of momentary consent | **No** — coercion substitutes |
| Constitutional monarchy | tradition/crown **+** consent | partial |
| Supranational org (treaty-based) | member-state **consent** (mediated by states) | consent-like, mediated |
| Delegated governance | the **delegating principal** | a form of consent |

**Finding (R-03, generalization).** **Consent is NOT a universal root invariant.** The universal invariant is weaker and stronger at once: **every constitutional system must terminate recursion at a trust anchor ∈ {coercion, consent}** (with fiat/axiom as *local* terminators beneath it). For systems **with** coercive enforcement, coercion can anchor; for systems **without an external sovereign** (NRNA's class), **coercion is unavailable, so the anchor is forced to be consent.** *Interpretation:* the program's headline result is **correct but class-relative** — *given NRNA's class*, consent is the anchor; *in general*, an anchor (coercion|consent) is required. This **strengthens** the result by scoping it honestly and explaining *why* NRNA lands on consent (it has no coercive alternative). *Revises INV-A6 (below).*

---

## 4. Architectural Primitive Matrix (the bridge to ontology)

| Candidate | Remove? | Replace? | Survives as | Evidence strength |
|-----------|---------|----------|-------------|-------------------|
| **Finality** | No | No | **Primitive** | High |
| **Independence** | No | Partial (via transparency+consent) | **Primitive** (coupled w/ transparency) | High |
| **Evidence** | No | No | **Primitive** | High |
| **Contestability / Standing** | No | No | **Primitive** | Medium-High |
| **Recursion-termination (anchor)** | No | No | **Primitive (meta)** | High |
| **Consent** | No *(this class)* | By **coercion** *(other classes)* | **Class-relative primitive** | Medium |
| **Transparency** | Erodes (slow-fail) | Partial | **Near-primitive (coupled w/ independence)** | Medium |
| **Authority-Delegation** | No | No | **Primitive (reference-model)** | Medium-High |
| **Review** | Function No, form Yes | **Yes** (other finality mechanisms) | **Mechanism** | High |
| **Appeal** | Yes | Yes | **Mechanism** | High |
| **Accountability** | No | depends-on Transparency | **Derived** (from transparency+finality) | Medium |
| **Legitimacy** | — | — | **Emergent (output, not primitive)** | High |

**Finding (R-04).** The **reference-model primitives** are: **Finality · Independence · Evidence · Contestability · Recursion-termination(anchor) · Authority-Delegation**, plus **Consent as a class-relative primitive** and **Transparency as a coupled near-primitive.** **Review/Appeal are mechanisms; Accountability is derived; Legitimacy is emergent.** *This matrix — not the family catalogue — is the input to the ontology.*

---

## 5. Revised architectural invariants

`Round42-01` gave INV-A1..A8. Substitution + consent-falsification revise two and add one:

- **INV-A6 (revised):** a constitution with no external sovereign terminates recursion at a trust **anchor ∈ {coercion, consent}**; **lacking coercion, the anchor is consent.** (fiat/axiom are *local* terminators beneath the anchor.)
- **INV-A3 (sharpened):** legitimacy requires **Finality** — and Finality is a *primitive output*, producible by mechanisms other than Review.
- **INV-A9 (new):** **Transparency and Independence are non-orthogonal** — in a consent-anchored system, transparency partially substitutes for independence via the consent channel; neither alone suffices.

---

## 6. Draft GDR updates (proposals only)

- **DGR-RM-01:** adopt the **reference-model / architecture distinction**; the ontology names reference-model concepts, not architecture artifacts. *(R-01)*
- **DGR-RM-02:** **Review/Appeal are mechanisms; Finality is the primitive.** *(R-02)*
- **DGR-RM-03:** **Consent is a class-relative anchor**, not a universal root invariant; the universal is *anchor ∈ {coercion, consent}*. *(R-03, corrects DGR-MA-04/06 scope)*
- **DGR-RM-04:** adopt the **Architectural Primitive Matrix** as the ontology input. *(R-04)*
- **DGR-RM-05:** **Transparency–Independence coupling** (INV-A9).

---

## 7. Executive summary + revised sequence

Reframing Round 42 as a **Constitutional Governance Reference Model** — *what must exist in any constitutional governance system, not how NRNA is organized* — and adding **hostile substitution** to hostile removal materially changed the result. Substitution showed that **Review is a replaceable mechanism while Finality is the primitive it produces**, that **Transparency and Independence are coupled (not orthogonal)** through the consent/legitimacy channel, and that **Accountability depends on Transparency.** Falsifying **consent across system classes** corrected the program's biggest over-claim: **consent is not a universal root invariant** — it is the anchor **specifically for NRNA's class** (self-governing, no external sovereign, no coercion); the universal invariant is that *every* constitutional system terminates recursion at an **anchor ∈ {coercion, consent}**, and NRNA lands on consent *because it has no coercive alternative.* The **Architectural Primitive Matrix** now names the reference-model primitives (Finality, Independence, Evidence, Contestability, Recursion-anchor, Authority-Delegation; Consent class-relative; Transparency coupled), separates **mechanisms** (Review, Appeal) and **derived** concepts (Accountability), and confirms **Legitimacy as emergent.** This matrix — stable under both removal and substitution — is the admissible input to the ontology.

Nothing frozen: Constitution, MB-39.1, locked Register, DDD gate unchanged; five Draft GDRs recorded. Population: NRNA, 4 families, below saturation, externally unvalidated; the consent result is now explicitly class-scoped and falsifiable.

```
Capability Discovery ✓ → Cross-Family Integration ✓ → Reference Model (42-01/02) ✓
   → Hostile Simplification ✓ → Hostile Substitution ✓ → Primitive Matrix ✓ → Invariants ✓
   → Governance Ontology v1.0  → Methodology Governance Review → Strategic DDD Readiness
```

---

*Round 42-02 — Constitutional Governance Reference Model — ISSUED (descriptive; program-canonical under MB-39.1; synthesized + hostile-tested, not externally verified).*
*Reference-model vs architecture · substitution: Review=mechanism / Finality=primitive, Transparency⟂Independence coupled · CONSENT falsified as universal → class-relative anchor ∈ {coercion, consent} · Architectural Primitive Matrix = ontology input · INV-A6 revised, INV-A9 added. Stable under removal AND substitution → Governance Ontology v1.0 admissible. MB-39.1 FROZEN · DDD GATED.*
