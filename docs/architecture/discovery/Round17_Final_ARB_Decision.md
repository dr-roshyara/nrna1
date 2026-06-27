# Round 17 — Final ARB Decision

**Date:** 2026-06-07

**Status:** Decision Recorded

---

## Decision

### Step 2: CLOSED

**Rationale:** Repository discovery is complete across Streams 1, 2, 3, 4, 5, 6A, and 6B. The evidence corpus documents implementation behavior across Governance, Authority, Audit, Constitutional Rules, Arbitration, Voting/Tally, and Election Integrity artifacts. The remaining HIGH STRATEGIC questions (D42, D22, D30, D13, D35, D36, D37) are governance-origin questions that Step 2 cannot resolve through code analysis.

---

### Round 18: Authorized (Governance Clarification)

**Scope:** Non-repository discovery focused on governance-origin questions:

| Debt | Question | Method |
|------|----------|--------|
| D42 | What election integrity guarantees are intended? | ADR archaeology, architect interviews, governance document review |
| D22 | Where do constitutional rules originate? | Governance document review, stakeholder interviews |
| D30 | Are rules-in-code intentional or temporary? | ADR archaeology, architect interviews |

**Start:** Parallel with Step 3.

**Output:** Clarified understanding of election integrity guarantees and rule origins, enabling review of provisional context boundaries.

---

### Step 3: Authorized (Candidate Context Discovery — Not Final Bounded Context Definition)

**Start:** With Round 18.

**Constraint:** All guarantee-sensitive candidate context decisions remain **provisional** until Round 18 governance clarification completes. No final context boundaries are locked without governance input.

**Safeguard:** When Round 18 clarifies intended guarantees, any candidate context boundary that depends on guarantee understanding must be reviewed and either confirmed or revised before the context map is finalized.

---

## Authority Chain

```
Round 17 Repository Discovery ─────────────────────────── CLOSED
    │
    ├──► Step 3 (Candidate Bounded Context Discovery) ─── AUTHORIZED
    │       └── Provisional boundaries, reviewable after Round 18
    │
    └──► Round 18 (Governance Clarification: D42, D22, D30) ─ AUTHORIZED
            └── Feeds guarantee understanding back into Step 3
```

## Key Distinctions

| Dimension | Step 3 (BC Discovery) | Round 18 (Governance) |
|-----------|----------------------|----------------------|
| Method | Repository + domain analysis | ADRs, interviews, documents, policy |
| Focus | Candidate contexts (not final boundaries) | Election integrity guarantees, rule origins |
| Output | Provisional context candidates | Clarified guarantee understanding |
| Dependency | Provisional on Round 18 output | Independent of Step 3 |
| Timing | Parallel | Parallel |

## Round 18 Completion Gate

Upon completion of Round 18 governance clarification:

1. **Re-evaluate** all guarantee-sensitive candidate context decisions
2. **Re-evaluate** D42, D22, D30 impact on candidate boundaries
3. **ARB determines** whether:

   - **A:** Candidate contexts are confirmed
   - **B:** Candidate contexts require revision
   - **C:** Additional discovery is required

No final context map is established until this re-evaluation completes.

---

## Not Authorized

- Final context map (until Round 18 completes)
- Aggregate design
- Architecture synthesis
- Implementation planning
- Any design decision that assumes finality of guarantee-affected boundaries

---

**Round 17 is closed. Step 3 and Round 18 proceed in parallel with provisional boundaries.**
