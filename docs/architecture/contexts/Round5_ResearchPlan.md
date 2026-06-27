# Round 5 — Research Plan (Decision-Focused)

**Purpose:** Identify which architectural decisions block Evidence Context design  
**Method:** Decision-focused evidence mapping, not open-ended historical research  
**Discipline:** Only research information capable of changing D1–D3  
**Date:** 2026-06-03

---

## Three Blocking Decisions

### Decision D1: SecurityEventRecorder Classification

**Question:** Is SecurityEventRecorder infrastructure or domain capability?

| Option | Meaning | Implication |
|--------|---------|------------|
| **Infrastructure** | Temporary mechanism supporting voting workflow | Evidence Context may need different recording system |
| **Domain Capability** | Foundational voting architecture (permanent) | Evidence Context must align with SecurityEventRecorder's design |
| **Unknown** | Cannot determine from code alone | Blocks Evidence Context design until resolved |

**Current Confidence:** MEDIUM (code shows it is tested and called, but purpose is unclear)

**Evidence Gap:** Why was it created? What problem does it solve architecturally?

**Potential Research Sources:**
- Commit message for first SecurityEventRecorder introduction (git log --all --grep="SecurityEventRecorder" or first commit touching the file)
- ADR entries mentioning security event recording
- Architecture documentation describing trust evaluation layer
- Author statement on whether SecurityEventRecorder is permanent

**Expected Impact on D1:** HIGH
- If source states "temporary audit mechanism" → Infrastructure (CONFIDENCE → HIGH)
- If source states "core voting verification capability" → Domain (CONFIDENCE → HIGH)
- If no clear statement exists → Unknown persists (no change to confidence)

**Research Cost:** Low (git history + one ADR check)

---

### Decision D2: Inactive Domain Events Classification

**Question:** Are the five domain events future architecture or temporary/abandoned code?

| Option | Meaning | Implication |
|--------|---------|------------|
| **Future Architecture** | Will be dispatched after D.0.3c migration completes | Evidence Context must accommodate them |
| **Migration Artifact** | Temporary instrumentation, will be removed | Can be ignored for Evidence Context design |
| **Experimental Code** | Abandoned prototype, no longer planned | Should be deleted or explicitly marked |
| **Unknown** | Cannot determine from code alone | Blocks Evidence Context design |

**Current Confidence:** LOW (docstrings mention D.0.3c, but no confirmation of what that means)

**Evidence Gap:** What is D.0.3c? Has it completed or failed? Are these events planned for permanent integration?

**Potential Research Sources:**
- Commit history for all five event classes (same 89914ce3 commit)
- ADR entries mentioning D.0.3c phase or migration strategy
- Architecture roadmap or project timeline
- Author statement on event class permanence

**Expected Impact on D2:** HIGH
- If D.0.3c is documented with completion criteria → Can classify (CONFIDENCE → HIGH)
- If no D.0.3c phase exists → Events are likely abandoned (CONFIDENCE → HIGH)
- If D.0.3c is active but undocumented → Still unknown (no confidence change)

**Research Cost:** Medium (may require roadmap search, author clarification)

---

### Decision D3: Relationship Between Systems

**Question:** What is the intended relationship between SecurityEventRecorder and domain events?

| Option | Meaning | Implication |
|--------|---------|------------|
| **Replace** | Domain events will replace SecurityEventRecorder | Current recorder is temporary; design around events |
| **Supplement** | Domain events will coexist alongside recorder | Both are permanent; must interoperate |
| **Separate** | They serve different purposes (operational vs. strategic) | Independent design, no interaction required |
| **Unknown** | No clear intent exists | Blocks coordination decisions |

**Current Confidence:** LOW (both exist, but relationship is unclear)

**Evidence Gap:** Was this dual approach intentional or accidental? Are they competing designs or complementary?

**Potential Research Sources:**
- Architecture decision record (ADR) comparing approaches
- Commit messages for domain events explaining rationale
- Design document for security recording layer
- Author statement on whether both systems are needed

**Expected Impact on D3:** HIGH
- If relationship is documented → Can classify and proceed with Evidence Context (CONFIDENCE → HIGH)
- If no relationship documentation exists → Indicates design decision not yet made (CONFIDENCE → remains LOW, but fact is discovered)
- If both are documented as intentional → Can design Evidence Context to accommodate both (CONFIDENCE → HIGH)

**Research Cost:** High (requires coordination documentation or author interview)

---

## Research Prioritization

**Research in this order (highest impact first):**

1. **First:** Check git history for the commit introducing SecurityEventRecorder
   - Cost: 5 minutes
   - Expected confidence gain: D1 clarity
   - If successful: Can classify SecurityEventRecorder

2. **Second:** Search for "D.0.3c" in codebase, ADRs, roadmap
   - Cost: 10 minutes
   - Expected confidence gain: D2 clarity
   - If successful: Can classify domain events

3. **Third:** Search for ADR or document comparing the two approaches
   - Cost: 15–30 minutes
   - Expected confidence gain: D3 clarity
   - If successful: Can understand relationship

**Stop After:** Any source explicitly answers D1–D3 from project documentation or author statement

**Do NOT research:** Git blame, commit history archaeology, tangential architecture decisions, unrelated ADRs

---

## Expected Outcome

After Round 5 research:

| Decision | Outcome Path |
|----------|-------------|
| **D1** | SecurityEventRecorder is [Infrastructure / Domain / Unknown] with confidence [HIGH / MEDIUM / LOW] |
| **D2** | Domain events are [Future / Migration / Experimental / Unknown] with confidence [HIGH / MEDIUM / LOW] |
| **D3** | Relationship is [Replace / Supplement / Separate / Unknown] with confidence [HIGH / MEDIUM / LOW] |

**Success Criterion:** At least 2 of 3 decisions reach HIGH confidence (≥2)

**Failure Criterion:** All 3 decisions remain LOW/MEDIUM after research exhaustion

---

## Next Steps After D1–D3 Clarity

**If D1–D3 have HIGH confidence:**
- Proceed to Constitutional Evaluation (Round 6)
- Evidence Context design can proceed with clear architectural constraints

**If any decision remains LOW confidence:**
- Flag for author/architect decision
- Evidence Context design pauses until decision is made
- Document as blocking assumption

---

## Discipline Checkpoint

This plan answers only the gating question:

```
What observation would materially change
an architectural decision?
```

It does NOT:
- ❌ Design Evidence Context
- ❌ Define aggregates
- ❌ Create repositories
- ❌ Perform constitutional evaluation
- ❌ Explore tangential architecture

It focuses on:
- ✅ Decision D1: SecurityEventRecorder purpose
- ✅ Decision D2: Domain events intent
- ✅ Decision D3: System relationship

---

**Status: Ready for Round 5 research. Decision targets identified. Proceeding with evidence collection focused on D1–D3.**
