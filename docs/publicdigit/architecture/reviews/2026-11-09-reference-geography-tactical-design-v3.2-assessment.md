# Reference Geography tactical design v3.2: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.2.md`. Not committed |

## 1. Disagreements with the review (first)

1. **Cross-context time is not left open.** [AD] D-73: "one shared τ per multi-context operation"; CR-01 defines τ and `closedThrough` as **times** (facts with `recordedAt ≤ τ`). The exact requirement is therefore established, and Geography can meet it with a **time watermark advanced under the publication lock** (v3.2 §6), the same barrier pattern as Membership's. **What remains is a platform dependency:** one clock discipline across contexts.
2. **The second-approver options rest partly on an artefact of v3.1.** D-84 has two roles, "a steward assesses and publishes" and "a second, independent natural person". The "first approver" was mine. Without it, the review's option A reduces to **T: the second person is distinct from the assessing steward**, which needs only two people.
3. **Q-2 does not need recovering:** it is recorded (Geography discovery revision 2, §9 question 2 and R2-13: other official schemes such as electoral constituencies). It does not affect the baseline.

## 2. What changed from v3.1

| Area | v3.1 | v3.2 |
|---|---|---|
| Slot keys | Containment and classification shared (unit, scheme) | **Every key starts with its kind**; collision check (§3) |
| Staleness | Records in the read set | **Dependency predicates**; any later claim matching one makes the revision stale, **new subjects included** (phantoms) |
| Lock | Validation under the lock | **Full validation before; delta check only under the lock** |
| Cross-context time | `closedThrough` (a position) compared with τ (a time) | **Time watermark W** under the lock; `closedThroughTime` = W; τ maps to a position |
| Governance | Preparer, first, second approver | **D-84's two roles**; interpretations T, A, B put to the Principal |
| CLDR result | Value, form, locale, release | Plus **origin (CLDR or platform fallback)**, requested locale, and whether the short form fell back |

## 3. Review findings

| Finding | Status |
|---|---|
| 1 Slot collision | **Resolved** |
| 2 Phantoms and staleness | **Resolved by predicates**, provided every invariant check runs through recorded predicates ([TU]: tests must assert it) |
| 3 Cross-context time | **Resolved for Geography** (time watermark); **platform clock discipline remains a platform dependency** |
| 4 Sequencer | Kept; the critical section is the delta check only |
| 5 Second approver | **Put to the Principal** (T recommended) |
| 6 CLDR fallback | Resolved: the origin is marked |

## 4. Scenarios

**Key:** G = guaranteed by mechanism · H = depends on human judgement.

### 4.1 The 42 earlier scenarios

They hold, with these mechanism changes:
- **N9** (code collision outside the edited unit) and **#10** (dispute plus change) are now caught by **predicates**.
- **#5, N10, scenario 3 of v3.1** (concurrent publications) now hold the lock only for the delta check.

### 4.2 The ten new scenarios

| # | Initial facts | Action | Invariant at risk | Expected | Mechanism | G / H |
|---|---|---|---|---|---|---|
| 1 | Unit U has containment and classification in scheme S | Correct only the classification | Unrelated supersession | Only (CLASSIFICATION, U, S) changes | Kind-prefixed keys | G |
| 2 | Revision R ends District A at p₀ = 100 (five children, all reassigned) | A sixth child of A is published at 101; R publishes | G-4 (orphan) | The claim at 101 matches "children of A over V" → **R stale** | Predicate staleness | G |
| 3 | R expects code X unique at p₀ | Another subject gets X at 101 | I-1 | Matches "holders of (namespace, X) over V" → stale | Predicate | G |
| 4 | R cites mandate M | M is withdrawn at 101 | Evidence basis | Matches "mandate M at t" → stale | Predicate | G |
| 5 | R in Nepal, p₀ = 100 | A German publication at 101 | False conflict | No predicate matches → R publishes | Predicate | G |
| 6 | An election at τ needs Membership and Geography | Query | One τ (D-73) | Each closed through τ (Geography advances W); each answers at τ | Watermark; Membership's barrier | G within each context; **cross-context depends on the platform clock discipline [TU]** |
| 7 | Clock jumps back while Membership publishes | Geography publishes | Shared τ order | Geography clamps (≥ W, ≥ previous); **Membership must clamp too, or the two may misorder** | Clamp | **Platform dependency [TU]** |
| 8 | A national revision with 10,000 claims | Publish while others wait | Contention | Validated before the lock; under the lock, a delta check only | Phases | G (duration [TU]) |
| 9 | A two-country revision; partition B violates G-4 | Publish | Atomicity | Rejected as a whole; nothing visible | One entry or none | G |
| 10 | Steward A prepares; B approves; A is asked to approve as second person | Publish | D-84 | **T: A cannot (distinct from the assessing steward)**, B suffices · **A (review): same as T here** · **B (review): A could, if B gave a first approval** | Governance check | **Depends on the [OQ]** |

## 5. Proven and dependent guarantees

| Proven by the domain model | Dependent on database or platform | Human governance |
|---|---|---|
| Slot independence; supersession semantics; dispute never changes values; identity never reused; predicate staleness (given complete predicates) | Lock semantics and isolation (`FOR UPDATE`); atomic commit; **one platform clock discipline** | Real-world identity; authenticity, competence, applicability; conflicts; supportability reassessment |

## 6. Principal decisions

- **Now: the second person under D-84.**
  - **T (recommended):** the second person must be distinct from the steward who prepared and assessed the change.
  - **A (review):** distinct from the preparer and from a separate first approver.
  - **B (review):** distinct from a first approver; may be the preparer.
- **Later:** names of regions and of territorial units; L-4c; Japanese blocks; Q-2.

## 7. Readiness

**Ready for the Principal DDD Architect's independent assessment**, once the Principal has ruled on the D-84 interpretation.

---

**Ruling recorded (2026-11-09): D-88, interpretation T.** Scenario 10 resolves as: A cannot be the second person; B's approval suffices. **No Principal decision now blocks the tactical baseline.**
