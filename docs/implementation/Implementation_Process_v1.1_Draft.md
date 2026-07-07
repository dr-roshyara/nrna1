# Implementation Process — v1.1 (DRAFT)

**Status:** DRAFT · **2026-07-07** · additive successor to the FROZEN `Implementation_Process_v1.0.md`. v1.0 remains authoritative until this draft is ratified; this draft is the **single home** for post-v1.0 process additions (so rules are not duplicated across the Decision Log / AKB).

**Rule:** engineering rules and process gates are **process**, and live **once** — here. Other documents *reference* them; they do not restate them.

---

## Engineering Rules — additions to §ER (v1.0 defines ER-01…ER-04)

**ER-05 — Architecture Convergence.** Every architectural iteration must **reduce or maintain** long-term architectural complexity.
- ✔ Generalize duplicate concepts · merge overlapping documents · retire obsolete/transition artifacts · replace implementation-specific concepts with domain abstractions · strengthen traceability.
- ✘ Create parallel frameworks · introduce synonyms · duplicate governance · add a *permanent* document without reducing future complexity.
- **Test for a new permanent governance artifact:** it must (1) eliminate an existing artifact, (2) simplify the architecture, (3) generalize knowledge already proven by multiple examples, or (4) directly support implementation. Otherwise it is transitional and is retired once its knowledge is folded in.
- *(Origin: 2026-07-07 governance maturity — architecture crossed from under-design risk to over-design risk; adopted via Decision Log D-13.)*

---

## Process gates — additions

**ARR Gate (Architecture Readiness / Reuse gate).** Every PB ticket that touches a **frozen Platform Capability** (e.g. the Messaging Platform) must explicitly answer, in its IDD, before any code:
1. Does this ticket **consume** the Platform Capability **unchanged**?
2. Does it require any **modification** to the Platform Capability?
3. Does it **violate** any Architecture Principle (incl. PGP-01…05)?
4. Does it require a **new ADR**?

**Routing:** if the answers are *consume-only · no modification · no violation · no ADR* → the ticket may proceed to RED. **Otherwise STOP and request ARB review** (a change to a frozen capability is an architecture decision, not an implementation detail). This prevents accidental drift into Shared.

---

## Pending fold-ins (queued for ratification into v1.1 body)
- Messaging Architecture Verification Matrix + the Platform Capability Readiness questions (from PB-003 C6A/C6B).
- ER-05 + ARR Gate (above).
- ADR template alignment for the ADR-MP / PGP style (principles referenced by decisions).

*(On ratification, this draft is renumbered to `Implementation_Process_v1.1.md` and v1.0's frozen body is superseded additively — no rule content lost.)*
