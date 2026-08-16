# ES-001 — Engineering Constitution

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** the foundational principles of the Engineering Platform and the rules by which governance itself is created.
**Scope:** all engineering work and all platform governance acts.
**Authority:** Decision Authority (ARB).
**Qualification Method:** constitution audits (OQ-ENG-002-class: ownership, discoverability, single-home, no-contradiction checks).
**Supersedes:** the MEMORY-resident texts of rule parsimony and governance-creation (now hosted here; MEMORY holds hints).
**Decision authority (compliance):** Human + AI — the AI evaluates, governance decides (see the Decision Authority & Verification Matrix in the Standards Index).
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

**ES-001.3 — Governance Orientation Duty** *(ARB 2026-08-16)*. **Governance is the system of record *and* the orientation layer: it must actively maintain the programme's execution state and tell the Human/PO/ARB what the next governed action is.** Recording what happened is necessary and not sufficient.

Whenever a task, decision, finding, verification result or dependency is recorded, Governance evaluates the state **of the records** and returns a concise *"what happens next"*: **current state** (what is actually recorded) · **what is waiting** (human decision, START, verification, architecture, implementation, or another dependency) · **recommended next action**, in business language · **responsible role** · **whether human authority is required** · **why**, derived from the recorded state and its dependencies, never from assumption. Where several actions are possible, it presents the recommendation, the reason, the other waiting items, and the human decision required — rather than a menu with no recommendation.

Governance must actively detect and surface: stale or blocked assignments · duplicate or conflicting records · missing STARTs · missing handoffs · **work completed in prose but not closed in the record** · verification not yet performed · findings needing a separate governed act · **scope that does not match the recorded grant** · dependencies between work items.

**The separation is the rule, and it is absolute:**

```
Observed state → Governance recommendation → Human decision → Recorded authorization → Execution
```

**Governance must never silently perform the action it recommends, and must never turn its own recommendation into an authorization.** This extends ES-001.2 rather than qualifying it: ES-001.2 forbids Governance from *creating* authority; ES-001.3 obliges it to *seek* authority proactively instead of waiting to be asked. A recommendation stated as a finding, a next action taken because it was obvious, or a state reported from prose rather than from the record, each breach this rule.

*Burden of proof (R-37) — this rule was adopted on implementation evidence, not on principle: two records created for one commission with one grant ID issued twice · one human START act recorded in two records · a human act recorded against an assignment the human did not name · a lane left ACTIVE while its verdict existed only in prose (the recurring `E-2` pattern) · a verification lane handed off and left unstarted for two days · an authorized grant with no assignment · an initialized record with no content · and three separate state claims that the records falsified. Companion: ES-006.1 — this is a repeated pattern, not a single occurrence.*
