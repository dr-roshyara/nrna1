# 11: G-3a to G-3c, the steward roster, Identity merges, publication governance (PBDIGIT-346)

**Design:**
- v3.11 §8 (the independence rule, eras, combinations, merges, roster ordering), §14, §15;
- D-84, D-84a, D-88, D-89, D-225;
- completion §5, §5a;
- the G-3 tactical assessment (`docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g3-tactical-assessment.md`).

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g3/`.

## In business terms

- **The platform owner appoints Geography stewards** (D-84). Every appointment, ending, single-matter reviewer and Identity merge notice is written into a **roster** with a running version number.
- **Every act is judged by the roster as it stood when the act was given.** An approval stays valid even if the approver later leaves.
- **While only one steward has ever been appointed,** that steward may publish alone.
  - **Once a second steward has been appointed, even briefly,** every structural change needs approval from a **second person**: someone who neither prepared nor assessed any version of it.
  - **That rule never switches off again** (D-89).
- **If Identity later says two references are one person,** that is taken into account before publishing: an approval by "another" person who turns out to be the preparer is refused.
- **The record says "independent as known to Identity at roster version v",** never "verified different person".

## Code (`app/Contexts/ReferenceGeography/Domain`)

| Element | Role |
|---|---|
| `StewardRoster` | **append-only aggregate.** `appointSteward`, `endSteward`, `appointMatterReviewer`, `recordMerge`, `recordReversal` (each returns its gap-free version; a merge or reversal replay returns `null` and writes nothing).<br>**Queries at version v:** `isStewardAt`, `mayActForAt` (a steward, or the matter reviewer for that revision), `secondStewardEverAppointedAt`, `thresholdActivatedAt`, `knownSameAt` (E(v)) |
| `RosterEntry`, `RosterEntryKind`, `RosterRefused` / `RosterRefusal` | the entries and the refusals. A refused write takes no version |
| `PublicationGovernance` | `maySubmit`, `mayApprove`, `mayPublish(revision, roster, v)`, giving a `GovernanceDecision` (allowed, refusals, the `GovernanceRule` applied, the roster version, the second person, the independence statement) |

**`mayPublish`, in order:**
1. The revision is submitted.
2. **Non-structural** → allowed (`NonStructural`).
3. **Era A** at v → allowed (`EraASolePublication`).
4. **Otherwise,** a counting approval (of the current content version) whose approver:
   - was **appointed at the approval's roster version**;
   - is **not known-same under E(v)** as anyone in `Revision::excludedPersons()`.

**E(v):** union-find over the merges recorded ≤ v, minus those whose reversal is recorded ≤ v, so it's order-insensitive.

## Design choices (fail closed)

- **A merge widens only the independence check,** the one place v3.11 applies it. It never grants steward authority to the merged reference (`MergeNoticeTest`).
- **The second-steward threshold counts appointed references.** A merge never stops the latch.
- **Governance is not dependency-recorded.** G-4 must call `mayPublish` **under the publication lock at the current roster version**, so later appointments and merges are always seen (assessment §1, contract 4). The §5.2 matrix is therefore unchanged: it covers the knowledge stream, and the roster stays outside it (§15).

## Not yet (by design)

- **G-3d:** conflict declarations and the D-84a material-conflict test. **Waiting for the Principal's Q-G3-1.**
- **Cases B and E** (post-publication findings) and **D-90 notices:** G-6.
- **The `PlatformOwnerAuthority` port, and binding `ActingPerson`:** with the first application service (G-4).

## Testing

**Tests:**
- `StewardRosterTest` (6);
- `MergeNoticeTest` (5);
- `PublicationGovernanceTest` (12): eras A and B, the latch, matter reviewers, merge cases A and D, submission and approval authority. **It covers 9 of the 10 rows of the §8 combination table;** the tenth ("merge recorded after publication → governance finding") belongs to G-6.

**Not yet safe to wire:** era A sole publication doesn't yet require the conflict declaration that D-84 demands. That's G-3d, so `mayPublish` must not be called from an application service before G-3d lands.

**RED:**
- G-3a: 6 errors (missing types).
- G-3b: 4 errors and 1 failure (missing methods).
- G-3c: **first run invalid, from my own fixture** (person labels weren't valid references; kept as `red-g3c-invalid-fixture.txt`). Fixed, then 12 errors (missing types).

**GREEN:** 163 domain tests.

**Mutation check** (`mutation-check.php`, no source file is modified): **7 of 7 mutants are killed:**
- the independence check removed;
- merges ignored;
- the approver judged at the wrong version;
- the threshold not latched;
- approvals of any version counted;
- structural treated as non-structural;
- reversals ignored.

**Traceability:** PBDIGIT-346 G-3a, G-3b, G-3c · v3.11 §8, §14, §15 · D-84, D-88, D-89, D-225 · completion §5, §5a.
