# Reference Geography tactical design v3.7: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.7.md`. Not committed |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81 to D-88, **D-89** |
| **Labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical assumption · [OQ] Principal decision |

## 1. Disagreement with the review (first): the temporal counterexample

**The review calls the v3.6 temporal model a "blocking architectural defect" because of its counterexample.** The counterexample is **possible**, but it **does not produce an incorrect answer under the accepted contract** (baseline §6):

1. **CR-01 defines knowledge by `recordedAt`, not by visibility:** `F_τ = {f : recordedAt(f) ≤ τ}` [BF].
2. **No τ = 150 answer is ever given before P commits:** the close through 150 must wait for P (lock order).
3. **The answer is stable** (ratified P3).
4. **CR-01 §3 holds.**

The review's premise that a fact must be "visible at τ" to be "known at τ" **is not in the accepted text.**

**The alternative the review asks to examine,** `recordedAt` from the actual commit timestamp, **was compared** (baseline §6):
- it is exact about visibility;
- **but its completeness would depend on the clock never moving backwards**, because a refusal is impossible after the commit;
- **that is not "proved by transaction ordering", as D-73 requires.**

**Conclusion:** keep the v3.6 realisation, with its semantics stated plainly. **If the Principal DDD Architect still holds that "known at τ" must mean "visible at τ"**, the assessment records a genuine incompatibility: **no mechanism found gives both exact visibility time and a transaction-ordering proof.** That would then be a decision for the Principal. **On the accepted text, no such conflict exists.**

## 2. Changes from v3.6

| Area | Change |
|---|---|
| D-84 | **[AD] D-89 (Option L) normative.** `secondStewardEverAppointed` is set at the first appointment of a second steward and never unset; **the publication invariant uses it;** reading C and its [OQ] removed |
| Boundaries | **`closedThroughPosition`** (position) and **`closedThroughTime`** (W, time) named apart; `positionAt(τ)` defined with ties; **a position is never compared with a time** |
| Temporal proof | The counterexample analysed against CR-01's text; the commit-timestamp alternative compared and rejected on D-73's proof requirement |
| Governance | **No evasion:** classification computed from claim kinds; splitting or relabelling cannot avoid the second person |

## 3. Strategic coherence

**Checked; no defects:**
- Geography owns official geographical knowledge, evidence, publication and history.
- Organisation owns areas and coverage.
- Membership owns belonging.
- Election owns entitlement.
- **Address ownership is still a recommendation.**
- Streets are address components.
- **Only Geography publishes; others consume read-only.**

No tactical element gives Geography another context's decision.

## 4. Guarantees

| Guarantee | Domain | Mechanism | Database | Platform | Human |
|---|---|---|---|---|---|
| Time completeness at τ | Inclusive W | Close waits for writers; refusal rule | Lock to commit; one clock; slewing | Each D-73 participant meets the contract | — |
| Position completeness | Gap-free positions | Sequence row under lock | `FOR UPDATE` and `READ COMMITTED` | — | — |
| D-89 threshold | Flag never unset | Roster writes under the same lock; roster version recorded | Lock | — | Appointments by the platform owner |
| G-4 to G-6, I-1 to I-4 | Invariants | Dependency predicates, before-images | — | **Dependency-only reads and a single write path enforced structurally [TU]** | — |
| Reconciliation | Atomic, reversible | One revision, one entry | Atomic commit | — | **Identity judgement** |
| Conflict of interest | D-84a by role | Declarations under the lock | — | — | **Whether a conflict exists** |

**Membership's CR-01 barrier (model document §38.2 rule 1) still moves timestamps:** recorded for the Membership tactical work.

## 5. Decisive scenarios for this round

**Key:** D = domain-level · DB = database-dependent.

| # | Situation | Expected | Kind |
|---|---|---|---|
| 1 | The counterexample (reading 100, query at 150, commit 200) | The answer at 150 is given only after the close, which waits for the commit; it includes P; it is stable | DB |
| 2 | Clock steps back below W | Publication refused; completeness intact | DB |
| 3 | Second steward appointed, then leaves; structural change | **Waits** for an eligible second person (D-89) | D |
| 4 | Matter reviewer appointed for that matter | May give the second approval | D |
| 5 | A structural change split into two revisions | Both structural; each needs the second person | D |
| 6 | A "correction" of a containment claim | Structural (by claim kind) | D |
| 7 | An appointment concurrent with a publication | Ordered by the lock; the roster version used is recorded | DB |
| 8 | A query by position p > `closedThroughPosition` | Refused | D |

## 6. Principal decisions still required

**None.**
- D-89 settles D-84.
- **Possible future question, only if the review's premise is upheld:** whether "known at τ" must equal visibility at τ. On the accepted text it does not (§1).
- **Later [OQ]s, unchanged:** names of regions and of territorial units; L-4c; Japanese blocks; Q-2; address ownership.

## 7. Readiness

**Ready for independent ratification review.**

**Conditions for the implementation architecture:**
- dependency-only reads;
- a single write path;
- lock and isolation behaviour;
- host time kept by slewing;
- Membership's §38.2 alignment.
