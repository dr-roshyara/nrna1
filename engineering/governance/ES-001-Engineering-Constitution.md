# ES-001 — Engineering Constitution

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** the foundational principles of the Engineering Platform and the rules by which governance itself is created.
**Scope:** all engineering work and all platform governance acts.
**Authority:** Decision Authority (ARB).
**Qualification Method:** constitution audits (OQ-ENG-002-class: ownership, discoverability, single-home, no-contradiction checks).
**Supersedes:** the MEMORY-resident texts of rule parsimony and governance-creation (now hosted here; MEMORY holds hints).
**Related Standards:** all (ES-002..ES-006 derive their authority from this document's registered sources).

## Registered constitutional sources (governed homes — pointers, never copies)

| Rule family | One-line statement | Canonical home |
|---|---|---|
| Principles AIP-01..14 | incl. AIP-10 Assertion Integrity · AIP-11 Append-Only History · AIP-13 Implementation-Driven Evolution · AIP-14 Product Primacy | sealed Baseline corpus, `../architecture/baseline/` (+ ADR-AIP-01/02) |
| Platform Decisions PD-01..20 · Fitness Functions FF-01..17 | defined; FF implementation deferred per AIP-14 | sealed Baseline corpus |
| Rulings R-30..R-37 | living governance decisions (R-27 governance freeze · R-37 structural freeze + burden of proof) | `../architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (append-only; R-1..29 sealed in Phase-02.5 §6) |
| Reference Architecture | what the platform IS (DRAFT→ADOPTED→STABLE lifecycle) | `../architecture/reference/` |
| Governing insight | *Governance precedes automation. Automation may implement governance. Automation never defines governance.* | stated in the Reference Architecture; numbering deferred to ratification |

## Hosted rules (this document is their canonical home; previously MEMORY-only)

**ES-001.1 — Rule Parsimony** *(ARB 2026-07-10)*. The constraint set is complete (R-27 claims · R-37 structure · EEP execution · ES-003.2 recording · inbox freeze). On any recurring problem, FIRST ask: *does an existing rule already cover this?* Prefer interpreting existing rules over creating new ones; a new rule requires a genuine "no." Corollary: the rulings register must not grow faster than the software.

**ES-001.2 — Documents Record Governance; They Do Not Create It** *(ARB 2026-07-11)*. Only explicit ARB decisions create governance. Workflow words (*continue, looks good, go ahead*) are permission to proceed — never Approved/Promoted/Retired/Closed. When a governance decision is needed: STOP and ask per item (Approve / Reject / Defer). Authors propose; the authority adopts (AIP-10 corollary: no artifact may assert an unoccurred adoption).
