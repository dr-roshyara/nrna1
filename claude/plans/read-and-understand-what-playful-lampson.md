# Round 8 Step 4 — Authority & Legitimacy Flow Analysis

## Context

Round 8 Steps 1-3 are complete:
- Step 1: Decision Ownership Matrix (20 constitutional decisions, 4 contexts)
- Step 2: Context Responsibility Matrix (operational vs legitimacy criticality)
- Step 3: Relationship Classification Matrix + Context Relationship Map

Round 8 Step 4 investigates how Authority (Power) and Legitimacy (Justification)
move through constitutional decisions.

**Goal:** Produce 3 lean artifacts that trace authority flows through representative
decisions, classify authority vs legitimacy, and collect balanced evidence for
H-B vs H-C — without creating another Evidence Context-scale discovery program.

**No code changes. Documentation only.**

---

## Revised Design Principles

1. **Authority Explosion Risk First** — Before any analysis, ask whether we are
   observing real authority behavior or relabeling ordinary decisions as authority.

2. **Representative Decisions Only** — Use 5 key constitutional decisions, not all 20.
   Expand only if patterns fail to emerge from the representative set.

3. **Candidate Lifecycle** — The 8-stage lifecycle is a hypothesis, not an established
   framework. Stages may be absent, merged, or irrelevant for some decisions.
   Only document stages that actually appear.

4. **Three Documents** — Flow + Lifecycle in one; Evidence in one; Risk in one.
   No spreadsheet archaeology.

---

## 3 Required Deliverables

All files go in: `docs/architecture/contexts/`

### 1. AuthorityFlowAnalysis.md

**Combines:** Authority Flow Through Decisions + Lifecycle Analysis

**Structure:**

**Section 1: Authority Explosion Risk Check (first activity)**
- Are we observing authority? Or relabeling ordinary decision behavior?
- Evidence of real authority patterns vs relabeling risk
- This section gates whether full analysis is warranted

**Section 2: Candidate Lifecycle Classification (RQ-0)**
Classify each of the 8 candidate stages as:
- Authority (Power operation)
- Legitimacy (Justification operation)
- Both / Unknown
Document which stages appear in which decisions. Allow stages to be absent.

**Section 3: Authority Flow Through Representative Decisions**
Five representative constitutional decisions:
1. Approve Membership (Membership decision)
2. Create Election (Election decision)
3. Certify Results (provisional — Election or independent certifier?)
4. Reverse Membership Decision (Appeals decision)
5. Grant Authority (Governance decision)

For each decision, document only stages that actually appear:
| Stage | Present? | Context | Evidence | Category |

**Section 4: Power vs Justification Test**
- Can authority be exercised before legitimacy exists? (Act First or Verify First?)
- Do Power and Legitimacy travel the same path?

**Section 5: Authority Boundary Test**
For each context: Can it create authority another context must respect?
Outcomes: Yes / No / Conditional / Unknown

**Section 6: Authority Conservation Test**
Can authority appear from nowhere? Trace origin for each observed authority claim.

---

### 2. AuthorityHypothesisEvidence.md

**Strictly balanced evidence for H-B vs H-C.**

**H-B (Authority Family):** Authority remains context-local, context-specific rules
**H-C (Cross-Cutting Authority):** Authority crosses contexts, verified outside origin

**Required structure:**
- Evidence FOR H-B (min 5 concrete observations)
- Evidence AGAINST H-B (min 5 concrete observations)
- Evidence FOR H-C (min 5 concrete observations)
- Evidence AGAINST H-C (min 5 concrete observations)

No winner may be declared. Evidence only.

**Also include:** Authority Acceptance Test observations
- What transforms claimed authority into effective authority?
- Which mechanisms appear: Governance approval, Verification, Multi-party, etc.

---

### 3. AuthorityRiskAssessment.md

**Section 1: Authority Explosion Risk**
- How many distinct authority types were observed?
- Are these genuine categories or modeling inflation?
- Recommend: reduce / maintain / investigate further

**Section 2: Candidate Lifecycle Assessment**
- Which stages consistently appeared across decisions?
- Which stages were absent or irrelevant?
- Does the 8-stage model survive contact with real decisions?

**Section 3: Open Questions for Round 8 Synthesis**
- Questions that Step 4 cannot resolve
- Questions that must be deferred to Synthesis or Step 5

---

## Representative Decisions (5)

Selected to cover all four contexts plus the Governance cross-context case:

| Decision | Primary Context | Why Selected |
|----------|----------------|--------------|
| Approve Membership | Membership | Core operational decision |
| Create Election | Election | Core operational decision |
| Certify Results | Election (provisional) | Tests independent certifier hypothesis |
| Reverse Membership Decision | Appeals | Tests corrective authority |
| Grant Authority | Governance | Tests constitutional authority origin |

Only expand to additional decisions if the 5 fail to reveal patterns.

---

## Candidate Lifecycle Stages (8)

These are hypotheses, not established framework.
Allow any stage to be absent, merged, or irrelevant.

1. **Claim** — Someone declares they have authority
2. **Origin** — Where did this authority come from?
3. **Delegation** — Who delegated it?
4. **Acceptance** — What validates the claim?
5. **Exercise** — Who performs the decision?
6. **Verification** — Who verifies legitimacy?
7. **Challenge** — Who can question it?
8. **Revocation** — Who can cancel it?

Stage category (RQ-0):
- Authority (Power): Claim, Origin, Delegation, Exercise, Revocation
- Legitimacy (Justification): Acceptance, Verification, Challenge

---

## Execution Order

1. Read Round8_DecisionOwnershipMatrix.md (foundation for 5 representative decisions)
2. Read VerificationDiscoverySummary.md (Authority patterns A1-A5, H-A/H-B/H-C)
3. **Start with Authority Explosion Risk** — gate the rest of the analysis
4. Produce AuthorityFlowAnalysis.md (lifecycle + flow)
5. Produce AuthorityHypothesisEvidence.md (balanced H-B vs H-C)
6. Produce AuthorityRiskAssessment.md (risk + open questions)
7. Commit all 3 artifacts together

---

## Success Criteria

✓ Authority Explosion Risk assessed first (not last)
✓ Candidate lifecycle stages classified as Authority vs Legitimacy
✓ Representative decisions analyzed (not all 20 unless necessary)
✓ Stages allowed to be absent/merged/irrelevant
✓ H-B and H-C evidence balanced (min 5 per side)
✓ Power vs Justification flows compared
✓ No architecture selected (evidence only)
✓ No winner declared for H-B vs H-C
✓ No Tactical DDD performed

Output must be suitable for Round 8 Step 5 Verification Placement Analysis.

---

## Governance Constraints

1. Authority lifecycle is a candidate model, not an established framework
2. No new concepts may be introduced beyond what prior rounds discovered
3. No architecture may be selected (evidence only)
4. No tactical design (no aggregates, entities, APIs, repositories)
5. Findings must be marked as "Observed Candidate Pattern" not conclusions

---

## Early Exit Rule

If the Authority Explosion Risk assessment (Section 1 of AuthorityFlowAnalysis.md)
concludes that **"authority" is primarily a descriptive lens rather than a distinct
domain concept**, Step 4 may terminate early and record that finding.

The goal is discovery, not completing the lifecycle model.

Evidence is allowed to falsify the premise.

This prevents the trap of:

```text
We planned Step 4
    ↓
Therefore Step 4 must find authority
```

Instead:

```text
Observe decisions
    ↓
See whether authority concept emerges
```
