# Round 43-01 — Governance Ontology v1.0

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 43 — Ontology · **Under MB-39.1 (frozen)**
**Status:** 📖 GOVERNANCE ONTOLOGY v1.0 — the stable domain language of constitutional governance, built from the externally-validated primitive matrix. **Program-canonical under MB-39.1; synthesized + hostile-tested + cross-domain-validated; not empirically proven.**
**Date:** 2026-06-25

> **What this is.** The first stable ontology — definitions for the concepts that **survived** discovery (Rounds 38–41) → synthesis (42-01) → hostile simplification + substitution (42-01/02) → cross-domain validation (42B). It is the **governance domain language**, the candidate ubiquitous language **input** to Strategic DDD.
> **What this is NOT.** Not software design, not bounded contexts, not code. **Strategic DDD remains GATED** — this ontology is the *domain language DDD will consume*, not DDD itself.
> **Closes:** `Round40-06` Semantic Inventory **SI-01** and **SI-02** (now definable — discovery complete). Other SI/RQ items remain open (§9).
> **Carries (normative):** the Scope-of-Applicability boundary from `Round42B-01` — every definition is scoped; over-generalization is forbidden.

---

## 1. Ontology scope boundary (normative)

This ontology is **validated for**: voluntary, online, anonymous constitutional elections **without an external sovereign** (NRNA's class), and **explains** coercion-anchored governmental/judicial systems. It is **bounded/partial** for weighted (corporate/DAO) and tradition-anchored (sacral) systems. **Every concept below carries a scope tag; none may be read as universal beyond its tag.**

---

## 2. Concept catalogue

Each entry: **definition · type · scope · stability · provenance.**
Type ∈ {Primitive · Derived · Emergent · Mechanism · Constraint}. Stability ∈ {Stable · Candidate · Experimental}. Scope ∈ {Universal · Class-relative · Context-dependent}.

### Universal-core primitives

- **Finality** — *the property by which a determination becomes binding and terminates a dispute.* Type: **Primitive**. Scope: **Universal** (all 8 validation cases). Stability: **Stable**. Provenance: F-REV; survived removal + substitution (Review is replaceable, Finality is not). *Note: produced by mechanisms (Review, time-bound, supermajority); the primitive is the property, not the process.*
- **Independence** — *the capacity of a governance body to determine outcomes free of the influences it governs.* A **structured concept of four** (§3). Type: **Primitive**. Scope: **Universal**. Stability: **Stable**. Provenance: F-AUTH/F-THR/F-REV; resolves **SI-01**.
- **Evidence** — *the reviewable record on which determinations are grounded.* Type: **Primitive**. Scope: **Universal**. Stability: **Stable**. Provenance: F-PROC; present in all 8 cases.
- **Trust-Anchor** — *the terminus at which governance recursion stops, conferring trust not itself derived.* A **ternary+ concept** (§5). Type: **Primitive (meta)**. Scope: **Universal (the need); type is context-dependent**. Stability: **Stable** (need) / **Candidate** (taxonomy). Provenance: F-REV recursion theory; revised by 42B (VF-1).
- **Legitimacy** — *the emergent property by which governed parties accept outcomes as rightfully binding.* Type: **Emergent** (never primitive). Scope: **Universal**. Stability: **Stable**. Provenance: F-REV; resolves **SI-02** (§4).

### Class-relative / context-dependent concepts

- **Consent** — *the acceptance by the constituent that confers and renews authority.* Type: **Primitive within systems lacking an external sovereign** (the required qualifier). Scope: **Class-relative** (anchor only where enforcement is absent). Stability: **Stable (scoped)**. Provenance: F-REV; corroborated by open-source foundations (42B VF-5); falsified as universal (42B VF-1).
- **Contestability** — *the structural possibility of challenging a determination.* Type: **Primitive (where present)**. Scope: **Context-dependent** (near-absent in sacral governance, 42B VF-2). Stability: **Candidate**.
- **Authority-Delegation** — *the transfer of decision power from the constituent to a body.* Type: **Primitive (reference-model)**. Scope: **Context-dependent** (minimized in direct democracy, 42B VF-3). Stability: **Candidate**.
- **Transparency** — *the visibility of governance state to those who must trust it.* Type: **Near-primitive, coupled with Independence** (INV-A9). Scope: **Context-dependent**. Stability: **Candidate**.

### Mechanisms (named, NOT primitives)

- **Review** — *a mechanism that produces Finality by re-examination.* Type: **Mechanism**. Replaceable (42-02 R-02). Stability: **Stable (as mechanism)**.
- **Appeal** — *a mechanism that re-opens a determination before Finality.* Type: **Mechanism**.
- **Audit** — *a mechanism that produces/checks Evidence.* Type: **Mechanism**.

### Derived / constraint

- **Accountability** — *the attribution of consequences for governance acts.* Type: **Derived** (from Transparency + Finality). Scope: Context-dependent. Stability: Candidate.
- **Resilience** — *emergent persistence of legitimacy under attack.* Type: **Emergent**. Stability: Candidate.
- **Anonymity** — *the constraint that voter–vote linkage is impossible.* Type: **Constraint** (bounds Correctability → Contained-Only). Scope: **NRNA/secret-ballot class**. Stability: **Stable (constraint)**.

### Explicitly NOT primitives (named to prevent over-generalization)

- **Equality-of-consent** — *one-party-one-vote.* **NOT a reference-model primitive** (corporate/DAO weighting — 42B VF-4); a **democratic value assumption** of NRNA's class.

---

## 3. Independence sub-ontology (resolves SI-01)

Independence is **four distinct concepts**, three in a strict dependency order plus one orthogonal:

```
Decisional independence  ⟹ requires ⟹  Operational independence  ⟹ requires ⟹  Institutional independence
   (rule free of influence)              (act/detect w/o interference)            (insulated source — S-3)
                                   ⊕  Perceived independence  (independence as the constituent sees it)
```

- **Institutional** — insulated appointment/source (S-3). *Foundational; given.*
- **Operational** — freedom to act/detect without interference. *Requires institutional.*
- **Decisional** — freedom to rule without influence. *Requires operational.*
- **Perceived** — independence as members perceive it. **Orthogonal**; load-bearing **because authority is consent-anchored** (perception-capture defeats actual independence). *(INV-A8)*

**Stability: Stable.** This supersedes SI-01's "do not define."

---

## 4. Legitimacy sub-ontology (resolves SI-02)

**Legitimacy = emergent composite of {Consent, Independence(×4), Finality, Transparency, Correctability}**, with properties:
- **Continuously renewed**, never conferred once (consent-anchored classes).
- **Asymmetric** — slow to build, fast to lose, only **partially** recoverable (Correctability bounded by Anonymity → Contained-Only).
- **Necessary condition:** Finality (no finality → infinite contestation → no legitimacy).
- **Emergent:** present only when the whole control loop operates; owned by no body.

**Stability: Stable** (definition); **Candidate** (the exact component set — "Consent" decomposition remains open, O-REV-Q1). Supersedes SI-02's "do not define."

---

## 5. Trust-Anchor sub-ontology (ternary)

**Every constitutional system terminates recursion at a Trust-Anchor.** The anchor TYPE (revised from binary by 42B VF-1):

```
Trust-Anchor ∈ { Enforcement (state OR code) , Consent , Tradition / Sacral }
   beneath which sit LOCAL terminators:  Fiat (declared finality) · Axiom (a trusted ground)
```

- **Enforcement** — coercion (nation-states) or algorithmic execution (DAOs: "code is law").
- **Consent** — acceptance by the constituent (NRNA's class; voluntary associations).
- **Tradition/Sacral** — authority from tradition/faith (conclave). *Under-explored (42B ETV-3).*

**NRNA's anchor is Consent** — because it has no Enforcement alternative (no external sovereign) and no sacral basis. **Stability: Stable** (the need for an anchor) / **Candidate** (the ternary taxonomy — one sacral case only).

---

## 6. Ontology axioms (the architectural invariants, as relations)

| Axiom | Statement | Scope |
|-------|-----------|-------|
| **AX-1 (INV-A1)** | Binding Authority requires Legitimacy | Universal |
| **AX-2 (INV-A2, revised)** | Legitimacy requires a Trust-Anchor; absent Enforcement, the anchor is Consent | Universal (anchor) / Class-relative (consent) |
| **AX-3 (INV-A3)** | Legitimacy requires Finality | Universal |
| **AX-4 (INV-A4)** | Decisional ⟹ Operational ⟹ Institutional independence | Universal |
| **AX-5 (INV-A5)** | Every correction loop requires Evidence | Universal |
| **AX-6 (INV-A6, revised)** | A no-external-sovereign system terminates recursion by fiat/axiom/consent | Class-relative |
| **AX-7 (INV-A7)** | Correctability is bounded by Anonymity | Secret-ballot class |
| **AX-8 (INV-A8)** | Consent-anchored Legitimacy requires Perceived independence | Class-relative |
| **AX-9 (INV-A9)** | Transparency and Independence are non-orthogonal (coupled via Legitimacy) | Context-dependent |

---

## 7. Relationship model (entity–relation)

```
Authority ──requires──► Legitimacy ──requires──► {Trust-Anchor, Finality, Independence, Evidence, Transparency}
Legitimacy ◄──renews── Consent (class) ;  Legitimacy ──emergesFrom──► the control loop
Independence ──chain──► (Decisional⟹Operational⟹Institutional) ⊕ Perceived
Finality ◄──producedBy── {Review, time-bound, supermajority}   (mechanisms)
Evidence ◄──producedBy── Audit ;  Accountability ──dependsOn──► {Transparency, Finality}
Recursion ──terminatesAt──► Trust-Anchor ∈ {Enforcement, Consent, Tradition}
Correctability ──boundedBy──► Anonymity
```

---

## 8. Stability & DDD-readiness classification

| Class | Concepts | DDD admissibility |
|-------|----------|-------------------|
| **Stable** | Finality, Independence(×4), Evidence, Legitimacy, Consent(scoped), Anonymity, Trust-Anchor(need) | **candidate ubiquitous language** — admissible *when* DDD opens |
| **Candidate** | Contestability, Authority-Delegation, Transparency, Accountability, Resilience, Trust-Anchor(taxonomy) | hold — context-dependent or under-tested |
| **Experimental** | the 3+1 capability cut; "consent decomposition" | not admissible |
| **Excluded** | Equality-of-consent (scope assumption), Review/Appeal/Audit (mechanisms, not domain primitives) | named to prevent over-generalization |

**DDD gate remains CLOSED.** This classifies the language; it does not open Strategic DDD.

---

## 9. Open items (NOT defined in v1.0)

- **O-REV-Q1** — is *Consent* decomposable (active / tacit / manufactured)? → v1.1 candidate.
- **RQ-EL-01** — is *Eligibility/Franchise* a distinct capability/family? Still **partially supported**, undefined here.
- **Trust-Anchor taxonomy** — sacral anchor under-explored (one case); ternary is provisional.
- **Contestability / Authority-Delegation** — context-dependence not yet fully characterized.

---

## 10. Executive summary

Governance Ontology v1.0 names the concepts that survived four families of discovery, system synthesis, hostile removal **and** substitution, and cross-domain validation. The **universal core** — **Finality, Independence, Evidence, Trust-Anchor(need), and emergent Legitimacy** — is defined as **Stable** and Universal. **Independence** is resolved (closing SI-01) into **four concepts** (Decisional⟹Operational⟹Institutional + Perceived); **Legitimacy** is resolved (closing SI-02) as an **emergent, continuously-renewed, asymmetric composite**. **Consent** is defined precisely **as primitive *within systems lacking an external sovereign*** (the corrected, scoped claim), and the **Trust-Anchor** is **ternary {Enforcement, Consent, Tradition}**, not binary. Mechanisms (Review/Appeal/Audit), derived concepts (Accountability/Resilience), the Anonymity constraint, and the **excluded** Equality-of-consent are all named to prevent over-generalization. Nine **axioms** carry the invariants with explicit scope. Every term is **scope-tagged and stability-graded**; the **DDD gate stays closed** — this is the domain language DDD will consume, not DDD.

Nothing was modified beyond closing two now-resolved Semantic-Inventory items: MB-39.1, the Constitution, and the locked Register are unchanged; the ontology inherits the bounded scope and the corrected anchor; six prior Draft GDR families remain proposals for the Methodology Governance Review. Population: NRNA (4 families) + 8 external cases — provisional, single-analyst, scope-bounded.

```
... Reference Model ✓ → Validation ✓ → Governance Ontology v1.0 (this) ✓
   → Methodology Governance Review (DA-THR-* + DGR-* → MB-39.2?) → Strategic DDD Readiness Assessment → [gate decision]
```

---

*Round 43-01 — Governance Ontology v1.0 — ISSUED (stable domain language; scope-bounded; DDD-gated).*
*Universal core (Finality/Independence/Evidence/Trust-Anchor/Legitimacy) Stable; SI-01 (Independence=4) + SI-02 (Legitimacy=emergent composite) CLOSED; Consent scoped "lacking external sovereign"; anchor ternary; 9 axioms; Equality excluded. Candidate UL for DDD — gate still CLOSED. Next: Methodology Governance Review. MB-39.1 FROZEN.*
