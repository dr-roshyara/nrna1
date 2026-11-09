# Reference Geography tactical design v3.5: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.5.md`. Not committed |
| **Binding** | D-73 / CR-01, D-75, D-79, D-80, D-81 to D-88 |
| **Labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical uncertainty · [OQ] Principal decision |

## 1. Disagreement with the review (first): what D-73 / CR-01 actually require

**The review states:** "the accepted D-73 contract … requires the database clock time at commit". **The authoritative texts, searched 2026-11-09, do not say that** [BF]:

| Source | Wording |
|---|---|
| Ratified Membership architecture (2026-10-08) | "`recordedAt` (system-of-record time)" |
| Ratification readiness record (resolved) | "`recordedAt` = when the fact entered PublicDigit's authoritative record; not DB `created_at`, queue time or audit timestamps (**implementations may realise it from the commit time**)" |
| Ratified final architecture | "total recording order … (ties in `recordedAt` broken by a sequence)" |
| CR-01 §3, §8 | `closedThrough` meaning; the application clock before commit is a defect |
| D-73 (CR-01 §9) | "proved by transaction ordering, not by moving timestamp generation" |
| "Database clock time at commit" | **Absent from every accepted text.** It appears only in this design series' own drafts (v2 and the Geography baseline closure document) |

**Conclusion [AC]:**
- **Commit time is permitted, not required.** v3.4's reading inside the serialised committing step is "when the fact entered the authoritative record".
- Ties are allowed (positions break them).
- No timestamp is moved, and completeness is proved by the lock protocol.
- **There is no competing reading to put to the Principal.**

The four distinctions the review asks for:

| Concept | Role in v3.5 |
|---|---|
| (1) The clock reading inside the writing transaction | **Used:** inside the serialised step |
| (2) The reading immediately before commit | **Same moment,** because the step commits immediately |
| (3) The actual commit timestamp | Not readable inside the transaction; **not required** |
| (4) A logical, adjusted timestamp | **Not used** (withdrawn in v3.4) |

## 2. Review findings

| Finding | Status |
|---|---|
| 1 D-73 | **Resolved** by the authoritative definition (§1); the close protocol and its proof as in v3.4 §6 |
| 2 Reconciliation | **Resolved:** RECONCILIATION slot, valid for all time; one level only (R-2, so no cycles); merged claims; reversal; consumer resolution; dependencies (§12) |
| 3 Profiles | **Resolved:** PROFILE_SCHEME, PROFILE_TIER, PROFILE_CLASS; derived position; G-4 and G-6 derived; no country profile decided (§13) |
| 4 Structural changes | **Resolved as an architectural reading** of D-84 and the S-1 dossier list; GROUPING and ADMISSION by analogy (§14) |
| 5 Conflict of interest by role | **Resolved:** preparing is not deciding; assessing, submitting and approving are decisions (§8) |
| 6 CLDR fallback reproducibility | **Resolved:** the platform default locale is a published fact (§9) |
| 7 Write paths | **Resolved:** only publication writes knowledge; the roster, candidates and objections are outside the stream (§15) |
| 8 Conditional guarantees | **Kept explicit** (§3 below) |

## 3. Guarantees by source

| Domain model | Database | Platform contract | Human governance |
|---|---|---|---|
| Slots, piecewise before-images, R-1, R-2, profile constraints, G-4, G-5, G-6, I-1 to I-4 **given the matrix**; **the single write path and dependency-only reads are structural preconditions [TU]** | Lock held to commit; serialised step; `clock_timestamp()`; isolation; host time by slewing | Every D-73 participant meets the knowledge-time contract; **Membership's §38.2 rule 1 still moves timestamps** (for the Membership tactical work) | Identity judgement; evidence; conflicts; supportability |

## 4. Scenarios

**Key:** D = domain-level · DB = database-dependent · P = platform-contract-dependent · H = human governance.

### 4.1 The 84 earlier scenarios: changed by v3.5

| Scenario | v3.5 outcome |
|---|---|
| Duplicate identity (v3 #1), reconciliation (v3 N6, N7; v3.1 #10) | Now through the RECONCILIATION slot. **H** (judgement) / **D** (mechanics) |
| Conflict found before publication (v3 #8, N13; v3.1 #6) | The deciding person is replaced; a conflicted preparer may still prepare. **H / D** |
| CLDR fallback (v3 N2, v3.1 #9) | Fallback reproducible through the published default locale. **D** |
| Profile change (v3 #2, Nepal 2015) | PROFILE_TIER and PROFILE_CLASS revision with G-4 and G-6 dependencies. **D** |

**All others are unchanged.** Their mechanisms are untouched.

### 4.2 New adversarial scenarios

| # | Initial state | Action | Invariant | Expected | Kind |
|---|---|---|---|---|---|
| 1 | B → A published | Reconcile A into C | R-2 | The same revision must re-map B to C; otherwise rejected | D |
| 2 | B → A | A reconciled into B | Cycle | Rejected: B is mapped, and a target must be unmapped | D |
| 3 | B → A with merged claims | Reversal | History | B restored from the reversal's position; belief at earlier p shows B → A; consumers receive the reversal | D, H |
| 4 | Two revisions reconcile B (into A, and into D) | Both publish | Uniqueness | The second is stale (slot dependency on (RECONCILIATION, B)) | D |
| 5 | Class "district" at 4 | Moved to 6 while municipalities (5) are its children | G-4 | Rejected unless the same revision reclassifies or moves the children | D |
| 6 | Class c ends | Units still carry c | G-6 | Rejected (family dependency) | D |
| 7 | Position 5 has a tier | Second tier claimed for position 5 | One meaning | Supersedes (one slot per position); never two meanings at once | D |
| 8 | Conflicted steward S is the only steward; objection from S's organisation | S prepares the evidence | D-84a | Allowed to prepare; **assessment and submission wait** for a non-conflicted steward or a matter reviewer | H / D |
| 9 | Second person declares a conflict after approving | Publish | D-84a | Approval void; another person needed | H / D |
| 10 | Default locale changed from en to de at p 300 | A display at p 250 reconstructed | Reproducibility | en fallback (the published default at p 250) | D |
| 11 | A roster change and a publication at the same moment | Publish | D-84 | Roster read "as at" publication; the roster is not in the knowledge stream | D, DB |
| 12 | A candidate accepted, but its revision not yet published | Query | Knowledge | Not visible; knowledge only through publication | D |
| 13 | M49 grouping changed by one steward while two stewards exist | Publish | D-84 | Allowed (non-structural, §14) | D (policy reading) |
| 14 | ISO admission of a new entry while two stewards exist | Publish | D-84 | **Second person required** (structural by analogy, §14) | D (policy reading) |

## 5. What still requires a Principal decision

**Nothing is required for the baseline.**
- **One optional confirmation:** §14 classifies **ADMISSION as structural** and **GROUPING as non-structural** by analogy, because the S-1 dossier list does not name them. **If the Principal prefers otherwise, it is a one-line change** with no architectural effect.
- **Later [OQ]s, unchanged:** names of regions and of territorial units; L-4c; Japanese blocks; Q-2; address owner.

## 6. Readiness

**Ready for independent ratification review.**

**Conditions for the implementation architecture:**
- a single write path;
- dependency-only reads;
- the isolation behaviour;
- host time kept by slewing;
- the Membership barrier's alignment with the knowledge-time contract.
