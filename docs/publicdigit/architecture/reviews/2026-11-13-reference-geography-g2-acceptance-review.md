# Reference Geography G-2: acceptance review for the Principal (PBDIGIT-346), revision 3

**Date:** 2026-11-13 · **Author:** Session A (engineering supplies evidence; it does not accept its own work) · **Status:** **ready for final Principal acceptance.**

**History:**
- **revision 1:** found F-1 and F-2;
- **revision 2:** closed F-1 and F-2; raised Q-DS;
- **revision 3:** implements the Principal's D-229 (Q-DS option A).

**Evidence:**
- `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-review/` (review);
- `…-g2-findings/` (F-1, F-2);
- `…-g2-qds/` (D-229).

## 1. Verdict and status

**Recommended verdict: ACCEPT G-2** as the Reference Geography **domain-validation** baseline, with the G-4 obligations in §7 carried forward.

| Item | Status |
|---|---|
| F-1: a country change re-checks the subtree (option (a)) | **accepted and implemented** (`b90500b4c`) |
| F-2: a boundary rests on an attributed cartographic source only | **accepted and implemented** (`b90500b4c`) |
| Q-DS: the duplicate search is supplementary (option A) | **approved as D-229 and implemented** (this revision's commit) |

## 2. What G-2 guarantees, in business terms

- **A change to the official atlas is a numbered, frozen revision,** validated against the atlas at one moment, and approved exactly as it stands.
- **Every fact it read is recorded,** so a relevant later publication makes it stale.
- **History is never rewritten.** Endings, corrections and successions are new facts (D-228).
- **Every official fact rests on evidence from an issuer competent at the instrument's issue date** (D-82, D-227).
  - **A place exists in the atlas only on a competent establishing instrument,** which may be a later official instrument, not only the founding document.
  - **A search showing the place isn't already present is recorded as extra evidence,** never as a substitute (D-229).
  - **A boundary may rest on a named, attributed map.** Nothing else rests on a map.
- **Each country's profile governs its places:**
  - an address component is never an official unit;
  - a parent always sits above its child;
  - a profile change can't break existing places;
  - **moving a place to another country re-checks everything under it.**

## 3. D-229: implementation and evidence

**Mechanism.** The existing Revision-assessment mechanism, so no second mechanism:
- `AssessmentKind::DuplicateSearch` is recorded with `recordAssessment()` and frozen in the content-version digest.
- **It is not mandatory.** v3.11 does not define when a duplicate search is "applicable", so it's recorded, not demanded. Making it mandatory would be a further decision for the Principal.
- **`BasisKind::DuplicateSearchAssessment` is removed,** and `Claim` (I-4) requires an instrument for every establish claim.
- **The validator's former exemption is removed.**

| Required proof | Test (`DuplicateSearchEvidenceTest`) |
|---|---|
| a duplicate search alone is refused; no non-instrument basis establishes existence | `test_a_duplicate_search_is_not_a_basis_and_existence_needs_an_instrument` |
| recorded as a supplementary assessment, never the basis, inside the approved version | `test_a_duplicate_search_is_recorded_as_a_supplementary_assessment_and_frozen_with_the_version` |
| it doesn't bypass issuer competence (D-82, D-227) | `test_a_duplicate_search_does_not_bypass_issuer_competence` |
| a later competent instrument is accepted | `test_a_later_competent_instrument_establishes_a_long_existing_place` |
| a competent instrument is accepted | the same test, and every existing Existence fixture |
| the Boundary (F-2) and Withdrawal (D-228) rules are preserved | BoundaryBasisTest (3), SuccessionValidationTest (8): unchanged and passing |

- **RED:** 3 errors (the missing assessment kind) and 1 failure (the basis kind still existed).
- **GREEN:** 140 domain tests.
- **Two earlier assertions encoded the old I-4 reading.** D-229 made them invalid, so they were updated deliberately, by manual edits:
  - `SupersessionTest` (a duplicate-search existence claim was constructible; the test is renamed);
  - `CompetenceAndEvidenceTest` (the duplicate-search exemption assertion).

  The first-GREEN output showing exactly these two failures is kept (`green-before-old-tests.txt`).

**Normative documents reconciled:**
- **the decision record** (`…/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md`): §11.129 holds **D-229**, explicitly a new Principal decision amending I-4;
- **v3.11:** I-4 (in §1 and in the §5.2 row) and the §3 Existence and Boundary rows carry dated "amended by D-229 / F-2" annotations. **The original I-4 wording remains visible, so nothing presents Option A as original text.**

## 4. Traceability

| Invariant | Source | Code | Tests |
|---|---|---|---|
| Revision lifecycle; frozen content version; approval of that version | v3.11 §7; D-89 | `Revision` | RevisionTest (11), RevisionValidationTest (7) |
| Insert-only slots; supersession within a slot; withdrawal = "none" | §3, §4 | `SlotKey`, `SlotHistory` | SupersessionTest (9), SlotKeyCollisionTest (9) |
| Before-images (VALUE / NONE / ABSENT) | §5.1 | `BeforeImage` | BeforeImageTest (7) |
| Staleness from gains and losses | §5.1, §5.2 | `DependencySet`, the three dependency kinds | DependencyTest (11), DependencyRecordingReaderTest (4) |
| Reads only through the recording reader | §5.2 [TU] | `DependencyRecordingReader`, purity guard | ReferenceGeographyDomainPurityTest |
| Parents exist; admission before grouping | §5.2; D-86 | PARENT_EXISTS, ADMISSION_BEFORE_GROUPING | RevisionValidatorTest (7) |
| I-1, I-2 | §5 | I-1 rule, `IdentifierInvariants` | RevisionValidatorIdentifierTest (7), IdentityInvariantsTest (7) |
| I-4 as amended: existence on an instrument; duplicate search supplementary | **D-229**; §1; D-82; D-227 | `Claim`, `AssessmentKind::DuplicateSearch` | DuplicateSearchEvidenceTest (4), SupersessionTest |
| Structural classification | §14 | `StructuralClassification` | StructuralClassificationTest (2) |
| G-5 endings, successors, no live child; endings versus corrections | §5.2; D-228 | G-5_*, WITHDRAWAL_BASIS | SuccessionValidationTest (8) |
| D-82 competence at the issue date; mandates, precedence, disputes | D-82; D-227 | D-82, D-82_ISSUER_UNKNOWN, EVIDENCE_* | CompetenceAndEvidenceTest (10) |
| D-82_BASIS: territorial claims on an instrument; boundary on an attributed map; withdrawals | D-82; §3; F-2; D-229 (5) | `authorityBasis`, `BasisKind::AttributedCartographicSource` | BoundaryBasisTest (3), CompetenceAndEvidenceTest |
| Profile form | §13; D-81 | PROFILE_FORM | TierAndPositionTest (7) |
| D-83 territorial positions only | D-83; §13 | D-83 | TierAndPositionTest, UndeterminedPositionTest (4) |
| G-4 on containment and on classification changes, at every change date | §5.2; §13 | G-4, `ProfilePositions::checkpoints` | TierAndPositionTest, UndeterminedPositionTest |
| G-4 and D-83 after a change of country | §13; the §5.2 annotation; F-1 (a) | `countryChanges` | CountryChangeSubtreeTest (10) |
| G-6 profile changes | §13; §5.2 | G-6 | ProfileChangeTest (7) |

## 5. Regression (final)

| Group | Result | What it proves |
|---|---|---|
| Geography domain | **140 tests pass** | the G-2 domain rules |
| Database-free (Geography, Identity, purity and architecture guards) | **165 tests pass** | no regression; the domain stays pure |
| Isolated PostgreSQL (Membership, Platform, Identity) | **154 tests pass** | no regression **in other contexts**. **It proves nothing about Geography persistence,** which has no database code yet |
| Full architecture suite | 316 tests, 4 failures | **names identical to the pre-Geography slice 1 baseline; messages identical to the earlier Geography runs.** So the four are pre-existing: election lifecycle voting-active state; ElectionClockService use; code creation redirect; the hard-coded "not eligible" denial reason |

**No test was deleted or weakened to obtain GREEN.** Every changed earlier assertion was invalidated by a Principal decision (F-2, D-229) and is listed.

**Assertion counts vary between runs** (randomised property tests and race tests). Test counts don't.

**Process deviation, accounted for:** in `b90500b4c`, one comment block in `UndeterminedPositionTest` was changed by a small script, contrary to the repository's manual-edit rule.
- It was one hunk: a two-line note about "open finding F-1" was replaced by a pointer to `CountryChangeSubtreeTest`.
- It was inspected and is correct, so nothing needs reverting.
- Every edit in this revision was manual.

## 6. Remaining G-2 limitations

- **When a duplicate search is "applicable"** is undefined in v3.11, so it is recorded but not required. This needs a Principal decision only if it should become mandatory.
- **The complete-dependency claim is only as strong as the §5.2 matrix.** Every future derived rule must annotate the matrix first (the F-1 lesson).

## 7. Carried to G-4 (not G-2 obligations)

- **The publication ledger:** atomic publication; serialised concurrent publishers; the stored before-image equal to the belief just before publication; W-closing.
- **Persistence** of revisions, assessments (including the duplicate search) and approvals.
- **Indexed family reads,** since a country change in a large country reads a large subtree.
- **G-0's race test is still not mutation-proved.**

## 8. Your decision

**Accept G-2, or don't.** Nothing else is open.

Not asked again: D-227, D-228, D-229, D-83, D-91, G-4, Q2, F-1, F-2.
