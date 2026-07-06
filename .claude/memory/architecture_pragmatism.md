---
name: Architecture pragmatism over abstraction
description: Guidance on when to extract abstractions and when to ship code as-is
type: feedback
originSessionId: d38df17d-2b14-42d8-8d2a-df243fe02686
---
# When NOT to Extract Abstractions

## Rule: Wait for the second consumer

**When to extract a Projector/DTO class:**
- You have 2+ places using the same projection (API, mobile, CSV export, admin dashboard)
- Not when you have 1 use case

**Example:** Don't extract `ElectionProgressProjector` yet because:
- Only used in one place: Inertia response
- 40 lines of clean, organized code
- Creates indirection with zero behavioral benefit
- Schedule extraction for when second consumer appears

**Why:** Speculative abstraction adds cost (extra file, DI binding, mental overhead) before the problem exists.

---

## Rule: Ship first, refactor with intent

**Don't refactor:**
- String states → enum (valid, but 2-3 day refactor, high regression risk, no current blocker)
- Query optimization (3 queries on page load ~once per phase change, not a problem)
- Formal DTO classes (PHP arrays are sufficient contract without runtime validation)

**When to refactor these:**
- Once you have metrics showing perf is an issue
- Once enum provides type safety benefit across codebase (not just one method)
- Once multiple serialization formats require formal contract

---

## Rule: Concrete implementation catches more bugs than abstract design

Review caught:
- Factory method risks (`forOrganisation`, `inNominationState`) ✅
- Relationship method name (`memberships` vs phantom `members()`) ✅
- Transaction isolation in tests ✅

Abstract architecture review missed these because it reasoned about layers, not code.

**Best practice:** Code review + architecture review are complementary, not sequential.

---

## The User's Principle

> "You've built a solid state machine with proper domain enforcement and a clean UI projection. Don't let architecture astronauting slow you down — ship this, then iterate."

**This is the right tempo for a production system:**
1. ✅ Correct behavior
2. ✅ Clean code in current boundaries
3. ✅ Tests passing
4. ⏭️ Ship
5. ⏭️ Iterate based on real constraints (second consumer, perf metrics, type safety gaps)
