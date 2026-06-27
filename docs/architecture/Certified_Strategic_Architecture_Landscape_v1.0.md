# Certified Strategic Architecture Landscape v1.0

**Status:** 🗺️ AKB Level-2 · the authoritative **certified strategic domain landscape** (not the entire software architecture). Reports **only what the frozen artifacts say**.
**AKB Principle 01 — evidence-driven:** *certified artifacts take precedence over memory, chat history, meeting notes, and unpublished analyses. When memory and evidence disagree, evidence wins.*
**Date:** 2026-06-27 · Sources (frozen): `docs/architecture/design/Round47-02_Strategic_Domain_Landscape_Certification.md` (Certified Landscape v1.0) · `Round49-06_Boundary_Decision_Register.md` (BDR v1.1) · `docs/architecture/discovery/Round28A_ARB_Final_Aggregate_Challenge.md` (Round 27–28: **9 accepted contexts**, 5 aggregates) · `architecture/strategic/ADR-004` + `BOUNDED_CONTEXT_ASSESSMENT` (Round 6).

## Reading guide
**This document answers:** what is the certified strategic architecture · which bounded contexts are confirmed · which candidates were downgraded · how the architecture evolved.
**It does NOT answer:** implementation status · coding standards · tactical DDD · deployment · roadmap. → see **Implementation Landscape v1.0** and the implementation governance docs for those.

## Document authority
This document is **descriptive**: it summarizes certified architectural decisions. It does **not** create or modify them. Changing the landscape requires an **updated Landscape Certification + updated BDR + Architecture Review** (KRG/SemVer, Round 47-02 §5).

## Correction note (two claims, checked against the repository)
- **"~32 bounded contexts" — not supported by certified artifacts.** Every `32` in the design docs is **"Round 32"** (the Round 32 Design Governance Charter; "Rounds 17–32"). No certified architecture with 32 bounded contexts exists in the repository.
- **"9 strategic domains (a two-tier domains→contexts hierarchy)" — not supported as stated.** The repository **does** document **9 bounded *contexts*** — the **Round 27–28 accepted tactical-discovery set** (`27A–27I`; 5 aggregates; discovery certified complete) [Round 28A] — but as a **flat set of contexts, not 9 strategic *domains* each containing sub-contexts**, and not the *current* certified count. Round 47 then **re-landscaped** these into the 8-candidate Certified Landscape. So "9" is **real** (9 contexts); the inaccuracies are the *domains→contexts hierarchy* framing and treating 9 as today's certified structure.
- **Certified truth (today):** **8 bounded-context candidates** (Round 47-02) → **5 Confirmed BCs** (BDR v1.1, Round 49-06).

## How the architecture evolved (history preserved, not rewritten)
Different discovery passes used different lenses/scopes, so **counts legitimately differ** — they are not contradictions, and none is "32" or a 9-domain tree:
```
DISCOVERY PASSES (prior strata):
  • architecture/strategic/ (Round 6)   → 5 contexts (Election Gov confirmed; Voting/Org/Membership candidate; Trustworthiness unresolved) [ADR-004, CONTEXT_MAP]
  • docs/.../discovery (Round 27–28)     → 9 ACCEPTED contexts (27A–27I); 5 aggregates; tactical discovery certified complete [Round 28A]   ← the real "9"

AUTHORITATIVE RE-LANDSCAPING (EBSD — the current certified structure):
  • Round 47-02 Certified Landscape v1.0 → 8 BC candidates + read models + invariant + external + deferred (FROZEN)
  • Round 49-06 BDR v1.1                 → 5 Confirmed BCs + downgrades (IMMUTABLE)
  • Greenfield Core                      → reference implementation of the 2 greenfield BCs (Contestation + Adjudication)
```
**Two lenses** also coexist and overlap on **Voting**: the *general* system lens (Round 6 / existing `app/Contexts/*`) and the *trustworthiness / correction-loop* lens (EBSD, Round 47–49). Both are valid viewpoints. The **authoritative current count** is Round 47-02 (8 candidates) → BDR v1.1 (5 confirmed).

## Certified landscape (Round 47-02) → BDR v1.1 verdicts (Round 49-06)
| # | Landscape candidate (47-02) | BDR v1.1 verdict (49-06) | Classification |
|---|------------------------------|--------------------------|----------------|
| 1 | Evidence & Replay | **Confirmed BC** (Evidence); Replay → Application Capability | BC + Capability |
| 2 | Audit | Infrastructure / Platform Capability | not a BC |
| 3 | Adjudication | **Confirmed BC — Greenfield** | BC |
| 4 | Authorization | Supporting Subdomain / Domain Service | not a BC |
| 5 | Contestation | **Confirmed BC — Greenfield** | BC |
| 6 | Appointment / Mandate | **Confirmed BC — Operational** | BC |
| 7 | Voting | **Confirmed BC — Operational** | BC (substrate) |
| 8 | Election Lifecycle | Supporting Subdomain | not a BC |
| — | Results | Derived Read Model | read model |
| — | Legitimacy | Derived Read Model | read model |
| — | Anonymity | Architectural Invariant (supreme) | invariant |
| — | Transparency | Held (experimental) | — |
| — | Trust-Anchor / Consent | External Boundary (outside software) | external |
| — | Eligibility · Identity-Trust | Deferred (GI-1 / RQ-ID-01) | deferred |

## Summary tally (at a glance)
| Category | Count |
|----------|------:|
| Candidate bounded contexts (Landscape v1.0) | 8 |
| **Confirmed bounded contexts (BDR v1.1)** | **5** |
| Supporting services / subdomains / capabilities | 4 (Authorization, Lifecycle, Audit, Replay) |
| Derived read models | 2 (Results, Legitimacy) |
| Architectural invariants | 1 (Anonymity) |
| External boundaries | 1 (Trust-Anchor / Consent) |
| Held / deferred concepts | 1 held (Transparency) + 2 deferred (Eligibility, Identity-Trust) |

## Candidate / Certified / Implemented
| Concept | Candidate | Certified BC | Implemented |
|---------|:---------:|:------------:|:-----------:|
| Evidence | ✅ | ✅ | ✅ (operational, legacy) |
| Voting | ✅ | ✅ | ✅ (operational, legacy) |
| Appointment/Mandate | ✅ | ✅ | ✅ (operational, legacy) |
| Contestation | ✅ | ✅ | ✅ (greenfield reference) |
| Adjudication | ✅ | ✅ | ✅ (greenfield reference) |
| Authorization | ✅ | ❌ (supporting service) | resolver exists |
| Replay | ✅ | ❌ (application capability) | exists (legacy Trust) |
| Election Lifecycle | ✅ | ❌ (supporting subdomain) | engine exists |
| Audit | ✅ | ❌ (infrastructure) | logger exists |
| Results / Legitimacy | ✅ | ❌ (read models) | — |

## Reference implementation
Architecture Release **1.1** validates the following certified bounded contexts through code:
- **Contestation**
- **Adjudication**

Implementation status, maturity, and roadmap are tracked separately in **Implementation Landscape v1.0** (this document does not mix in implementation detail).

---
*Certified Strategic Architecture Landscape v1.0 — certified strategic map (descriptive authority). "32" = Round 32; "9" = v0 hypothesis. Authoritative: 8 BC candidates (Landscape v1.0) → 5 Confirmed BCs (BDR v1.1). Flat set; two lenses; evolution recorded; evidence beats memory (AKB Principle 01).*
