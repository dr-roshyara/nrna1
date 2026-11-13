# 12: G-3d, conflict declarations and judgment matters (D-84a, D-230) (PBDIGIT-346)

**Design:**
- D-84 ("with a recorded conflict-of-interest declaration"), D-84a, **D-230** (Q-G3-1);
- v3.11 §8 "Conflict of interest by role", "Checks";
- the G-3 assessment §5 and §6.

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g3/`.

## In business terms

- **Everyone who decides on an atlas change** (the assessing steward, the second person, a matter reviewer) **first declares** whether they have an organisational interest in that matter.
- **If they do, and the matter involves competing positions** (a dispute, an objection, a missing-unit submission, a precedence question, competing instruments, or simply doubt), **they can't decide it.** The matter **waits** for a steward without an interest, or for a reviewer the platform owner appoints for that matter alone.
- **Nobody can declare their own matter "routine" to get round this.** A judgment flag can't be removed by anyone.

## Code

| Element | Role |
|---|---|
| `StewardRoster::declareConflict(person, matter, interest)` | a roster write with its own version; the latest declaration ≤ v counts (`declaredInterestAt`) |
| `Revision::flagJudgmentMatter(by, JudgmentReason)` | anyone involved, in any state but withdrawn. **Permanent:** it survives re-preparation; there is no removal operation (a test guards this) |
| `Revision::isJudgmentMatter()` | a flag ever raised, a DISPUTE claim, or a precedence assessment of the current version |
| `Revision::assessor()` | the assessing steward of the current version |
| `PublicationGovernance` | `conflict()` runs in `maySubmit`, `mayApprove`, and in `mayPublish` for the current assessor (every branch: era A, non-structural, era B) and for the second person. It gives `ConflictDeclarationMissing` or `MateriallyConflicted` |

**Flags are not in the content digest,** because a flag may be raised after submission. They're read at publication, so a late flag still blocks a conflicted decider.

## Pitfalls

- **Don't add an "unflag" or "reclassify" operation.** No decision creates that authority (D-230).
- **Every new decider role must go through `conflict()`.**
- **Fixtures must record declarations for every decider.** That's why the G-3c test fixtures were updated (below).

## Testing

**`JudgmentMatterConflictTest` (7 tests):**
- triggers and flags;
- permanence, and that no removal operation exists;
- declarations required (including era A);
- routine versus judgment;
- the held matter, then the matter reviewer (case 5);
- the second person re-checked;
- the conflict test runs for the current version's assessor.

**RED:** 7 errors (missing behaviour).

**First GREEN run:** 11 G-3c tests failed, because their fixtures had no declarations. That's the D-84 / §8 invariant, now enforced. **The fixtures were updated, not the rule:**
- declarations were added;
- versions are taken from the roster instead of being hard-coded;
- the same 12 tests, the same 29 assertions, identical expected outcomes (checked by diff).

The output is kept: `green-g3d-first-run-old-fixtures.txt`.

**GREEN:** 170 domain tests. **Mutation check:** 14 of 14 killed (M8 to M14 are the G-3d safeguards).

**Traceability:** PBDIGIT-346 G-3d · D-84, D-84a, D-230 · v3.11 §8.
