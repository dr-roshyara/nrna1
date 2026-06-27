# Round 24G — Electoral System Variability Notes

**Date:** 2026-06-07

**Phase:** Future Domain Awareness (Post-Acceptance Reference)

**Status:** Complete

**Source:** Electoral System Design handbook

---

## 1. Purpose

Record observations about future election variability for later phases. These are NOT context acceptance criteria — they are future aggregate design awareness notes.

---

## 2. Observations

### Observation 1: Electoral Formula Is a Potential Source of Domain Variability

The handbook identifies multiple fundamentally different election formulas: FPTP, STV, List PR, MMP, Borda Count, Two-Round. The current system appears designed for a single formula. Electoral Formula appears to be a potential source of domain variability. Whether that variability belongs inside Results/Tallying, Constitutional Governance, or another model remains unresolved until aggregate discovery.

### Observation 2: Ballot Structure May Vary Independently

Ballot design (candidate selection, party selection, ranked choice, preference ordering) is separate from tallying in the handbook. The current system couples ballot structure to tallying (vote → synchronous result creation). Future variability may require separation between ballot capture and ballot interpretation.

### Observation 3: Tallying May Become Independently Complex

Current tallying is simple (COUNT + GROUP BY). The handbook describes STV, MMP, D'Hondt, Sainte-Laguë, Droop Quota — each requiring different tally algorithms. This strengthens the argument that Results/Tallying should not be dismissed too quickly during Round 25.

### Observation 4: Constitutional Governance Is Validated

The handbook treats election rules as constitutional/legal/governance artifacts — not software design choices. This strongly validates the Constitutional Governance candidate as a distinct domain concern.

### Observation 5: Election Rule Volatility

The handbook demonstrates that electoral systems change through legal/governance processes. Formula changes, district changes, seat allocation changes, and representation rules change independently of vote collection mechanics. This strengthens evidence that election rules are a separate governance concern, supporting the Constitutional Governance candidate.

---

## 3. Impact Assessment

| Candidate | Impact | When Relevant |
|-----------|--------|---------------|
| Constitutional Governance | Strengthened — election rules are governance artifacts | Round 25 acceptance |
| Results/Tallying | Slightly strengthened — may require independent complexity | Round 25 acceptance consideration |
| Voting | Not affected — vote capture vs. vote interpretation may become relevant later | Aggregate discovery |

---

## 4. Round 25 Implication

**None.** These notes are for future aggregate design awareness. They do not change candidate context proposals. Proceed to Round 25 without delay.

---

**Round 24G complete. Proceed to Round 25 — ARB Context Acceptance Review.**
