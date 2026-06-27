# Plan: ARB Decision Record + ARB Review Package Corrections

## Context

Phase 1 discovery (Rounds 1-5) is complete. The ARB Review Package has been committed but requires three corrections before formal submission. After corrections, the ARB Decision Record is created.

Governance principle: ARB is a *role*, not a person. The current reviewer acts in the ARB role. The document must be separable from the individual who filled it out.

The task is to produce a one-page `ARB_Decision_Record.md` that the user fills out — answering five governance questions — producing an explicit authorization (or not) for Round 6.

## What To Produce

**File:** `docs/architecture/ARB_Decision_Record.md`

A single-page template structured around six governance questions:

```
Q0: Has discovery materially strengthened H1 (Evidence Context is a valid BC)?
    Answers: YES / NO / INCONCLUSIVE

Q1: Is D1 sufficient?
Q2: Is D2 uncertainty acceptable to proceed?
Q3: Is D3 uncertainty acceptable to proceed?
Q4: Is additional author clarification required?
Q5: What is the authorized next step?
    Outcomes: A. Continue discovery  B. Seek clarification  C. Authorize design  D. Conclude investigation
```

Each question has:
- One-sentence evidence summary
- Explicit answer field (YES / NO / INCONCLUSIVE / chosen outcome)
- Brief rationale field

Final section: explicit authorization statement. Signed as "ARB Reviewer" (governance role, not individual name).

## Governance Corrections Applied

1. **Role vs. Person** — Document refers to "current reviewer acting in ARB role," not "the user is the ARB." Governance roles must be separable from individuals.

2. **Q0 Added** — Primary hypothesis question first. "Has discovery materially strengthened H1?" All other questions support this one.

3. **Q5 Reframed** — Not binary "authorized / not authorized" but explicit outcome selection: Continue / Clarify / Design / Conclude.

4. **Governance Warning Added** — Explicit statement: "Completion of this form does not automatically authorize design. Authorization depends on Q5 outcome."

## Constraints

- One page (scannable, not exhaustive)
- No design proposals, no architectural recommendations, no code
- Pure governance record
- Reviewer fills it out; AI provides structure only
- No mention of "user IS the ARB"

## Step 1: Correct ARB_Review_Package.md (Already Committed)

Three corrections required to `docs/architecture/ARB_Review_Package.md`:

**Correction A — Remove vote-steering recommendations**
Find and remove all instances of:
- "Recommend voting: YES"
- "Recommendation: High priority"
- "Recommendation: Low priority"
- "Recommendation: Conditional on author availability"
- "Recommendation: Medium priority"

Replace each with neutral: "Evidence Summary / Confidence / Known Risks / Open Questions"
The ARB presents evidence. It does not steer the board.

**Correction B — Rewrite Option C neutrally**
Current: "Proceed with design using provisional assumptions" (advocates for Option C)
Replace with: Describe Option C factually — assumptions required, risks, revision triggers — without recommending it. 
All four options must be presented with equal weight.

**Correction C — Add ARB Q0 as the primary governance question**
Before Q1-Q4, add Q0:
"Has the discovery effort materially strengthened H1 (Evidence Context is a valid bounded context)?"
Votes: YES / NO / INCONCLUSIVE

Rationale: D1-D3 are supporting evidence. H1 is the hypothesis being tested. Q0 is the actual architectural question; Q1-Q4 are its inputs.

## Step 2: Create ARB_Decision_Record.md

**File:** `docs/architecture/ARB_Decision_Record.md`

Structure:
```
Header: Role = "ARB Reviewer" (not individual name)
Date: [DATE]
Reviewed: ARB_Review_Package.md

Q0: Has H1 been materially strengthened?       YES / NO / INCONCLUSIVE
Q1: Is D1 sufficient?                          YES / NO
Q2: Is D2 uncertainty acceptable?             YES / NO / DEFER
Q3: Is D3 uncertainty acceptable?             YES / NO / DEFER  
Q4: Is author clarification required?         YES / NO
Q5: Authorized next step:
    A. Continue discovery
    B. Seek author clarification  
    C. Authorize design
    D. Conclude investigation

Governance Warning (printed): 
"Completion of this form does not automatically authorize design.
Authorization depends on the outcome selected in Q5."

Signature: ARB Reviewer
Date: 
```

Each Q has: evidence summary (one line) + answer field + rationale (one line)

## Governance Corrections Applied

1. ARB is a **role**, not a person — document says "current reviewer acting in ARB role"
2. Q0 added — primary hypothesis question first
3. Q5 is outcome selection, not binary
4. Governance warning printed in document
5. No recommendations, no advocacy — evidence and questions only
6. Options presented with equal weight

## Verification

Work is complete when:
- ARB_Review_Package.md has no vote-steering recommendations
- Option C is descriptive, not advocated
- Q0 is present in both documents
- ARB_Decision_Record.md has all six questions with answer fields
- Governance warning is present
- No design content in either document
