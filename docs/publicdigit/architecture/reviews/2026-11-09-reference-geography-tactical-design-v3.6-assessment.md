# Reference Geography tactical design v3.6: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.6.md`. Not committed |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81 to D-88 |
| **Labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical assumption · [OQ] Principal decision |

## 1. Disagreement with the review (first)

**The review treats the D-84 threshold as an architectural correction ("the threshold … remains in force thereafter"). D-84's own text does not settle it** [BF]. The rule contains both of these sentences:
- "**While only one steward exists**, that steward may publish all changes …"
- "**From the appointment of a second steward**, every structural change requires approval by a second, independent natural person …"

After a second steward leaves, only one steward exists, so **the first clause permits sole publication while the review's latching reading forbids it.**
- **Choosing between them would change what D-84 means, so it is a Principal decision** (§5).
- **The model supports both:** the roster records `secondStewardEverAppointed`.

## 2. Review findings

| Finding | Status |
|---|---|
| 1 D-84 threshold | **Modelled for both readings; the reading itself is [OQ]** |
| 2 Roster and publication coordination | **Resolved:** roster writes take the publication lock and receive a roster version; approvals and publications record it; a concurrent appointment cannot be missed |
| 3 Reconciliation token | **Resolved:** `knownAtPosition` is authoritative; `recordedAt` supporting |
| 4 Reconciliation proof | **Resolved:** one revision, one entry (atomic); dependencies cover subject families, mappings, incoming mappings and identifier families; chained case; reversal |
| 5 `recordedAt` precision | **Resolved:** the clock reading, commit and visibility, position and W distinguished; **the reading is not the commit timestamp** |
| 6 ADMISSION and GROUPING | **Retained as proposed** (structural and non-structural); an optional confirmation, not blocking |
| Profiles versus D-83 | **Resolved:** territorial tier, address-component tier and official unit cannot be confused |

## 3. Guarantees and assumptions

| Guaranteed by the model | Database assumptions | Platform | Human |
|---|---|---|---|
| Atomic reconciliation; historical explainability by p and roster version; slot and before-image semantics; G-4, G-5, G-6, I-1 to I-4 **given dependency-only reads and a single write path** | Lock held to commit; one database clock; `READ COMMITTED` re-read; host time kept by slewing | The knowledge-time contract for D-73 participants | Identity judgement; evidence; conflicts |

## 4. Critical scenarios for this round

**Key:** D = domain-level · DB = database-dependent · H = human governance · OQ = depends on the [OQ].

| # | Situation | Expected | Kind |
|---|---|---|---|
| 1 | One steward publishes a structural change | Allowed (D-84) | D |
| 2 | A second steward is appointed; a structural change is submitted afterwards | Second person required | D |
| 3 | The second steward's appointment ends; a structural change follows | **L:** waits for a second person · **C:** the sole steward may publish | **OQ** |
| 4 | No eligible second person is available | The change waits (D-84) | D |
| 5 | A matter reviewer is appointed for one matter | May act as second person for that matter only | D |
| 6 | A new steward is appointed after submission | Second approval needed before publication | D, DB |
| 7 | A second approval exists; publication is delayed and the roster changes | Publication re-checks at the current roster version; the approval stands unless its approver now has a declared conflict | D, DB |
| 8 | An appointment commits while a publication waits for the lock | The publication sees it (same lock) | DB |
| 9 | A conflict declared concurrently with a publication | Ordered by the lock; seen if earlier, applies to later publications if later | DB |
| 10 | A reader during a reconcile publication | Sees none of it or all of it | D, DB |
| 11 | B → A, then A into C without re-mapping B | Rejected (R-2) | D |
| 12 | Two reconciliations of B concurrently | The second is stale | D |
| 13 | Consumer holds B after reconciliation at position 105 | `resolve` returns A with `knownAtPosition` 105 | D |
| 14 | Reversal at 140 | Belief before 140 shows B → A; from 140, B is independent | D |
| 15 | An address street submitted as a unit | Rejected: no identity for address components | D |

The 98 earlier scenarios are unaffected except those listed in the v3.5 assessment §4.1, which keep their v3.5 outcomes.

## 5. Principal decisions still required

**One: the D-84 threshold after a second steward leaves.**
- **Business meaning:** once PublicDigit has had two stewards, does a structural change always need a second person from then on, even if one steward later leaves?
- **L (latching):** yes, from the first appointment of a second steward, permanently. Structural changes wait until a second person is available: another steward, or a person you appoint for the matter.
  - Consequence: stronger and more predictable governance. **Structural changes can stall until you appoint someone.**
- **C (current count):** only while two or more stewards are appointed. Otherwise the sole steward publishes alone again, as D-84's first clause says.
  - Consequence: the platform never stalls. **Scrutiny falls back to one person whenever the roster shrinks.**
- **Architectural recommendation: L.** Once independent scrutiny has been established, letting it lapse because of a vacancy weakens trust in published geography. Waiting is safe because legal effective dates are preserved and only PublicDigit's knowledge is delayed. **But C is what the first clause's words most directly say**, so this needs your ruling.

**Optional, not blocking:** ADMISSION structural, GROUPING non-structural (§14 of the baseline).

## 6. Readiness

**Ready for independent ratification review once the D-84 threshold reading is ruled.**

**Conditions for the implementation architecture:**
- dependency-only reads;
- a single write path;
- isolation behaviour;
- slewing;
- Membership's contract alignment.
