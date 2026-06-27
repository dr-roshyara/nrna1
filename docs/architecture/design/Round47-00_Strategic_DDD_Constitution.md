# Round 47-00 — Strategic DDD Constitution

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 47 — Strategic DDD (Software Architecture Layer) · **Gate:** OPEN (per `Round46B`)
**Tier:** 🏛️ CONSTITUTIONAL (software side) — the entrenched principles every Strategic DDD artifact obeys. Mirrors the *Methodology Constitution* (`Round39-MC`) on the software side.
**Status:** ADOPTED (2026-06-26). Consumes **Certified Domain Knowledge Release v1.0**.
**Date:** 2026-06-26

> **Why first.** Strategic DDD begins **not** with bounded contexts but with its **constitution** — the same discipline applied to the research methodology. These principles bind all of Round 47+; an artifact violating one is void to the extent of the conflict.
> **Position.** This is the **Software Architecture Layer**'s constitution. It sits *beneath* the certification boundary: it governs how DDD consumes the Certified Release, never how the Release is made.

---

## The principles (SD-1 .. SD-7)

### SD-1 — Consume only certified knowledge
Strategic DDD consumes **only** the **Certified Domain Knowledge Release v1.0** (Package v1.0 + Canonical Vocabulary v1.0). No raw research artifact, no uncertified draft, no external source may enter the software model.

### SD-2 — No governance concepts invented in DDD
No new **core governance concept** may be created during Strategic DDD. DDD discovers **software boundaries**, not constitutional theory. A felt need for a new concept → **STOP** → governance item (Architecture Change Protocol, `Round46-KRG` §4).

### SD-3 — Ubiquitous Language originates from the Canonical Vocabulary
All ubiquitous language **MUST** come from `Round46-VOCAB`. Forbidden synonyms (e.g. CaseDecision/Judgment for **Determination**) **MUST NOT** appear in code. Overloaded terms (Review/Authority/Independence) **MUST** be qualified. New terms follow Certified Vocabulary Governance.

### SD-4 — Forbidden Transformations are architectural constraints
The Forbidden Transformations (`Round46-02` Part IV) are **hard constraints**, enforced by fitness functions/tests: e.g. Legitimacy is never an Aggregate or persisted-truth (one read model only); Trust-Anchor is never an Entity (external boundary); bare "Independence" is never a class; Anonymity-violating linkage is never stored; Blocked concepts never become contexts/aggregates.

### SD-5 — Carried constraints are binding
The five constraints + assurance findings hold: **(1)** federate Independence by its 4 facets · **(2)** Anonymity is supreme over all other concerns · **(3)** exactly one Legitimacy projection · **(4)** software does **not** own enforcement (acceptance event, never a compelling aggregate — TA-1) · **(5)** design **from ownership seams**, then reconcile with existing code — **no retrofit** (TA-2). Plus TA-3 (semantic review against Blocked-smuggling) and TA-4 (naming hygiene).

### SD-6 — Any governance change returns to Knowledge Release Governance
Anything affecting Ontology · Vocabulary · Package · Admissibility **MUST** originate in Governance Research and arrive via a new **Published release** (`Round46-KRG`). DDD may *request*, never *enact*. **Software ↛ Governance** (MC-06) holds.

### SD-7 — Traceability is mandatory
Every Strategic DDD artifact **MUST** declare the Package/Vocabulary/Ontology versions it was built against (`Round46-KRG` §5 Traceability Version Matrix). Decisions are auditable to the certified knowledge that produced them.

---

## Hierarchy (software side)

```
Methodology Constitution (research side)        Strategic DDD Constitution (this — software side)
        │ governs research                              │ governs software design
        ▼                                               ▼
   ...MB-39.1...                              Certified Domain Knowledge Release v1.0
                                                        │ consumed by
                                                        ▼
                                   Round 47 Context Discovery → 48 Context Mapping →
                                   49 Bounded Contexts → 50 Aggregates → 51 Domain Services/Policies → Tactical DDD
```

## Amendment
A principle changes only by an explicit amendment record (sponsor + ARB), like the Methodology Constitution. SD-principles are **not** amendable inside ordinary DDD work.

## Starting conditions for Round 47 Context Discovery (next)
- **Start from:** the 4 owning seams (Record-Keeping · Adjudication · Contestation · Appointment) + the persistence split (System-of-Record vs never-store) — **not** the concept list, **not** the existing code structure.
- **Admitted material only:** Package v1.0 Part IV (Admissible + with-restrictions + External-Boundary + Read-Model + Invariant).
- **Held/Blocked excluded:** Contestability/Transparency/Accountability/Resilience (held); Eligibility/Coercion/Voter-ID/Amendment (blocked).
- **Built against:** Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0.

---

*Round 47-00 — Strategic DDD Constitution — ADOPTED. DDD gate OPEN.*
*SD-1 consume-only-certified · SD-2 no-governance-invention · SD-3 UL-from-Canonical-Vocabulary · SD-4 Forbidden-Transformations-as-constraints · SD-5 carried-constraints-binding · SD-6 changes-return-to-KRG · SD-7 traceability-mandatory. Next: Round 47 Context Discovery from the 4 owning seams. Built against Certified Release v1.0.*
