# Round 36E-05 — Architecture Synthesis Plan

## Context

36E-04 produced an Architecture Option Catalog covering 7 pressure areas with 20+ options. Each option was evaluated independently across constraint satisfaction, DDD impact, governance impact, trustworthiness impact, and tradeoffs. 4 cross-option tensions were identified. No options were selected or eliminated.

36E-05 is authorized to transform that catalog into:
1. **Architecture Families** — coherent option combinations (not selections)
2. **Dependency Map** — which options depend on / conflict with others
3. **ADR Authoring Sequence** — the order in which Round 37 ADRs must be written

**Binding guardrails:**
- MAY recommend option combinations and sequencing
- MAY NOT select individual options as final architecture
- Recommended combination ≠ selected architecture
- No new contexts, aggregates, services, ADRs, technology selections
- Selection belongs to Round 37 ADRs

---

## Document to Produce

**File:** `docs/architecture/design/Round36E-05_Architecture_Synthesis.md`

---

## Document Structure

### Section 1 — Synthesis Framework
- 1.1 Purpose and Scope: transform catalog → families + dependencies + ADR sequence
- 1.2 Three-Level Synthesis Model (mandatory per ARB charter):
  - **Level 1 — Constitutional Compatibility:** do combined options satisfy the 30 ACs together?
  - **Level 2 — DDD Compatibility:** do combined options work within the existing DDD model?
  - **Level 3 — Operational Feasibility:** is the combination organizationally realistic?
- 1.3 Binding Prohibitions (explicitly listed)
- 1.4 Input Map — 36E-04 options by pressure area (reference table)

### Section 2 — Option Compatibility Matrix

Cross-option compatibility analysis BEFORE families are proposed. For each pair of options across different pressure areas, classify:
- **Reinforcing (R):** options strengthen each other constitutionally
- **Compatible (C):** options can coexist without tension
- **Tension (T):** options create additional constitutional or design pressure when combined
- **Incompatible (I):** options cannot coherently coexist (requires explicit constitutional evidence — high bar)

The 4 cross-tensions from 36E-04 Section 9 are the primary inputs:
- CT-1: EC-01 Option B × CPR-05 Option C (individual verifiability + cryptographic commitment)
- CT-2: OQ-03-05 Option C × CPR-03 dual authority map (separate authority layer + dual map)
- CT-3: CPR-04 Option D × program timeline (deferred certification + D39 sequencing)
- CT-4: CPR-01 Option A × AC-06 (role-level independence + nominal independence risk)

Additional compatibility analysis: EH-01 options × CPR-05 options (verifier independence model must match evidence integrity model).

### Section 3 — Architecture Families

Propose **3 candidate families** based on the compatibility matrix. Each family is a coherent option combination across all 7 pressure areas.

**Family naming convention:** F1 (Minimal Constitutional), F2 (Structural Distribution), F3 (Maximum Constitutional)

For each family, profile at all three synthesis levels:

```
Family F-N
-----------
Option selection per pressure area:
  EH-01      → Option X
  EC-01      → Option X
  CPR-01     → Option X
  CPR-02     → Option X
  CPR-03     → Option X
  CPR-04     → Option X
  CPR-05     → Option X

Level 1 — Constitutional Compatibility:
  ACs satisfied: [list]
  ACs stressed:  [list]
  ACs unsatisfied: [list]

Level 2 — DDD Compatibility:
  Model changes required: [assessment]
  Existing elements compatible: [list]
  New elements implied: [assessment — not design decisions]

Level 3 — Operational Feasibility:
  Governance structures required: [assessment]
  Organizational prerequisites: [list]
  D39/D42B dependency exposure: [assessment]

Family characteristics summary:
  [2-3 sentences capturing the constitutional/DDD/operational tradeoff profile]
```

**Important:** Families show combinations — they do NOT name a winner.

### Section 4 — Constitutional Dependency Map

For each pressure area's options, identify:
- **Constitutional prerequisites:** which other decisions must be resolved first
- **Enabling relationships:** which decisions open up or close off other decisions
- **Shared foundation questions:** the 6 OQs from 36E-04 mapped to the dependency structure

Candidate dependencies (to be validated):
- CPR-01 (form of independence) is a prerequisite for CPR-04 (certification independence model)
- CPR-01 + CPR-02 together constrain the viable forms of CPR-03 (authority in DDD)
- EH-01 option choice constrains CPR-05 option viability (verifier independence model must match evidence integrity architecture)
- CPR-04 is contingent on D39 resolution (Option D deferred; Options A/B/C provisional)
- EC-01 resolution (OQ-36E-04-03: Type D or E tension) is a prerequisite for vote challenge architecture

### Section 5 — ADR Authoring Sequence

Propose a sequenced list of ADRs for Round 37. Earlier ADRs unblock later ones.

**Candidate sequence (to be validated against dependency map):**

```
ADR-1: Authority Model Vocabulary
  Addresses: OQ-03-05 / CPR-03 (how are authority relationships represented in DDD?)
  Unblocks: all subsequent ADRs that reference authority relationships

ADR-2: Independence Form per D43 Function
  Addresses: CPR-01 (form of constitutionally independent authority relationships)
  Depends on: ADR-1
  Unblocks: ADR-3, ADR-4, ADR-5

ADR-3: Audit Scope Authority Structure
  Addresses: OQ-03-03 / CPR-02 / ET-03 (unified vs separated audit scope + execution)
  Depends on: ADR-2
  Unblocks: ADR-5

ADR-4: Evidence Integrity Architecture
  Addresses: CPR-05 / EH-01 (verifier independence + evidence architecture)
  Depends on: ADR-2
  Unblocks: ADR-5

ADR-5: Certification Architecture
  Addresses: CPR-04 (provisional — pending D39)
  Depends on: ADR-2, ADR-3, ADR-4
  Note: provisional until D39 resolved

ADR-6: Challenge Architecture (EC-01)
  Addresses: OQ-36E-04-03 (Type D or E?); scope of challengeable decisions
  Depends on: ADR-1, ADR-2
  Note: EC-01 conflict status must be determined in this ADR

ADR-7: D42B Closure (Verification Representation Context)
  Previously deferred ADR-Candidate-01/02
  Depends on: ADR-1, ADR-2, ADR-4
```

**Note:** The exact sequence is the synthesis question — the document may revise this order after the dependency map analysis is complete.

### Section 6 — Open Questions Carried to Round 37

Map the 6 OQs from 36E-04 onto the ADR sequence:

| OQ | Addressed by ADR | Nature |
|---|---|---|
| 36E-04-OQ-01 (cross-option tensions) | ADR-1 through ADR-7 collectively | Architectural |
| 36E-04-OQ-02 (eliminable options) | Each relevant ADR | Selection decision |
| 36E-04-OQ-03 (EC-01 Type D or E) | ADR-6 | Constitutional determination |
| 36E-04-OQ-04 (ElectionConstitution as shared L-1) | ADR-1, ADR-2 | Constitutional determination |
| 36E-04-OQ-05 (certification before or after D39) | ADR-5 | Sequencing decision |
| 36E-04-OQ-06 (modeling vocabulary sufficiency) | ADR-1 | Vocabulary decision |

### Section 7 — ARB Decision Block

Three questions for ARB:
1. Are the Architecture Families constitutionally coherent? Are any families eliminable before Round 37 (high bar: requires explicit constitutional evidence)?
2. Is the ADR Authoring Sequence correct? Are any dependencies missing or incorrectly ordered?
3. Authorize Round 37 ADR Authoring — which Family (or cross-family options) enters Round 37 as the primary candidate?

---

## Status Header for the Document

```
**Status:** SUBMITTED FOR ARB REVIEW
**Predecessors:** 36E-01 through 36E-04 — APPROVED
**Binding Guardrail:** This round synthesizes options. It does NOT select options.
Recommended combination ≠ final architecture. Selection belongs to Round 37 ADRs.
```

---

## Verification

This is a documentation-only artifact. Verification:
1. Check that every pressure area from 36E-04 (7 areas) has an entry in each family
2. Check that no family section uses language implying selection ("the architecture is", "we will use")
3. Check that the dependency map references the correct AC numbers from 36E-01
4. Check that the ADR sequence has no circular dependencies
5. Check that all 6 OQs from 36E-04 are mapped in Section 6
6. Confirm Section 1.3 Binding Prohibitions are complete and consistent with 36E-04

---

## Notes for Execution

- Read 36E-04 in full before writing (all 7 section tradeoff summaries are the primary input)
- Read 36E-01 AC list (30 constraints) for Level 1 constitutional compatibility checking
- Read 36E-03 DDD impact assessment for Level 2 DDD compatibility checking
- Do NOT invent new options — only synthesize what 36E-04 produced
- Architecture Families should feel like coherent philosophical positions, not just arbitrary combinations
- The dependency map is the intellectual core of 36E-05 — spend most analysis effort there
