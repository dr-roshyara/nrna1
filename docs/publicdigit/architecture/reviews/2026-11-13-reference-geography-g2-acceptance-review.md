# Reference Geography G-2: acceptance review for the Principal (PBDIGIT-346), revision 2

**Date:** 2026-11-13 · **Author:** Session A (engineering supplies evidence; it does not accept its own work) · **Status:** for the Principal's decision.

**Revision 2** follows the Principal's decisions on revision 1:
- F-1: option (a);
- F-2: narrowed to an attributed cartographic source;
- the duplicate search: no new exception; trace the sources.

**Sources:**
- v3.11 (D-93): §1 (I-4), §3, §5, §5.2 (with the 2026-11-13 implementation annotation), §13;
- D-82, D-83, D-91, D-227, D-228.

**Evidence:**
- review: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-review/`;
- fixes: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-findings/`.

## 1. Verdict

**ACCEPT WITH ONE CONDITION.**
- **F-1 and F-2 are closed,** test-first.
- **One question stays open,** because the sources genuinely conflict: Q-DS, how I-4's duplicate-search assessment relates to D-82 (§4).
- **Until you decide Q-DS,** the code keeps its G-2d-2 behaviour, unchanged and marked PENDING in the code.

## 2. What changed, in business terms

- **A place moved to another country is re-checked with everything under it.** The move is allowed, but it is refused if:
  - any child would end up above its parent;
  - any place under it has a class the new country doesn't have.

  The same applies when a place's containment ends, or when a country's admission starts or ends.
- **A boundary may rest on a map only if the map is named and attributed as cartographic evidence.** A vague "other" source no longer counts, and a map is no basis for anything except a boundary.

## 3. F-1: root cause, fix and evidence

**Root cause:**
- §13 derives every position from `country(u)`, and G-4 holds for every edge.
- But the §5.2 query for a containment change (written in v3.4, before §13) reads only the unit and its parent.
- So a change of country re-derived the positions of the whole subtree, and nothing re-checked them.

**Fix:** `RevisionValidator::countryChanges()`.
- **For each containment or admission claim,** it compares the unit's country before and after, at every change date in the claim's interval.
- **Where the country changes,** it re-checks D-83 for the unit and its descendants, and G-4 for each descendant's edge to its parent.
- **Descendants come from recorded family reads,** at most 10 levels deep.
- **Within one country, no descendant is read,** so there is no false staleness.
- **The §5.2 matrix now carries an implementation annotation** with the added query row, so it no longer lags §13.

**Evidence:** `CountryChangeSubtreeTest`, 10 tests.
- **RED:** 7 failures for the missing behaviour. The 3 guards against over-reach passed already: a compatible destination, a move within one country, and another scheme.
- **GREEN:** all pass.
- **The revision 1 probe** (Nepal → Germany) now reports `[G-4]` (`probe-cross-country-move-after.out`).

| Required case | Test |
|---|---|
| move to another country | `…compatible_profile_is_accepted`, `…nepal_to_france_move_breaks_g4…` |
| containment ending | `test_ending_a_containment_leaves_the_whole_subtree_without_a_country` |
| admission starting and ending | `test_ending_an_admission…`, `test_an_admission_starting_inside_a_tree…` |
| a class missing in the destination (D-83) | `test_a_descendant_whose_class_the_destination_profile_lacks…` |
| G-4 after the change | `…nepal_to_france_move_breaks_g4…` |
| a multi-claim revision | `test_an_admission_starting_inside_a_tree…` (detach, admit and profile together) |
| every period of the interval | `test_every_period_of_the_interval_is_checked` (France changes in 2020) |
| staleness from descendant facts | `test_a_later_change_to_a_descendant_makes_the_validation_stale` |
| scheme isolation | `test_a_country_change_in_another_scheme…` |
| no false staleness | `test_a_move_within_one_country_reads_no_descendants` |

## 4. F-2, and the duplicate-search question (Q-DS)

**F-2 (closed).**
- **The change:** `BasisKind::AttributedCartographicSource` is the only non-instrument basis a BOUNDARY accepts, and only a BOUNDARY.
- **Tests:** `BoundaryBasisTest` (3). RED was 2 errors and 1 failure.
- **One earlier assertion changed** (`CompetenceAndEvidenceTest`, the boundary example), because the approved narrowing invalidated it.

**Q-DS: the exact texts.**
- **D-82:** "Reference Geography publishes only geographical facts supported by **competent official sources**."
- **v3.11 §1, Identity, I-4:** "every *establish* claim cites an establishing instrument, **or a recorded duplicate-search assessment**."
- **v3.11 §3:** "A claim is an insert-only assertion about one slot, with a value, a valid interval … and **a basis**." The Existence row's basis is "**Territorial**".
- **v3.11 §5:** "D-82: territorial claims need territorial competence."
- **D-227:** competence is assessed at the **instrument's** issue or adoption date, which presupposes an instrument with an issuer.

**Analysis:**
- **The two rules protect different things.** I-4 sits in §1 (Identity) and protects against minting a second identity for a place that already has one: the duplicate search is the proof of that. D-82, §3 and §5 protect the evidence for the fact that the place exists.
- **The model gives each claim one basis.** An existence claim whose basis is a duplicate-search assessment satisfies I-4 but has no issuer, so D-82 can't be satisfied for it.
- **So the texts can't all apply literally to the same claim.** It's neither an "alternative expressly permitted" (D-82 names no exception) nor cleanly "alongside" (there's only one basis). **It's a genuine conflict that needs your decision.**

**Options:**

| Option | Rule | Business consequence |
|---|---|---|
| **A, cumulative (recommended)** | Existence always rests on a competent instrument; D-82 holds with no exception. The duplicate search is recorded **in addition**, as a Revision assessment, not as the claim's basis. I-4 reads: "an establishing instrument, with a recorded duplicate search where the place is not newly created" | **Strongest evidence:** every place in the atlas traces to a competent source. A place whose founding instrument can't be found can't be entered until a competent source is found. G-8 must then cite competent sources for countries and areas (under D-227) |
| B, alternative (current code) | A duplicate-search assessment may be the basis of an existence claim: an exception to D-82 | Long-existing places can be entered without a founding instrument. Their containment and classification still need instruments. **A weaker D-82** |
| C, model change | A claim carries an instrument basis **and** an optional duplicate-search reference | The same policy as A, with a richer model and more change |

**If you choose A, it's one small test-first step:**
- an existence claim on a duplicate-search basis is refused;
- the duplicate search is recorded as an assessment.

## 5. Traceability, updated (changes from revision 1)

| Invariant | Source | Code | Test |
|---|---|---|---|
| G-4 and D-83 after a change of country | §13; §5.2 annotation; Principal F-1 (a) | `countryChanges`, `descendants`, `inSubtreeAt`, `childSchemes` | CountryChangeSubtreeTest (10) |
| Boundary basis | §3 Boundary row; Principal F-2 | `BasisKind::AttributedCartographicSource`; D-82_BASIS | BoundaryBasisTest (3); CompetenceAndEvidenceTest |
| Duplicate-search existence | I-4 vs D-82 / §3 | unchanged, **PENDING Q-DS** | CompetenceAndEvidenceTest (current behaviour) |

**All other rows of revision 1's table are unchanged:** the revision lifecycle, supersession, before-images, staleness, the recording reader, parents, admission, I-1 and I-2, the structural classification, G-5 and D-228, D-82 and D-227, the profile form, G-4 on classification, and G-6.

## 6. Regression

| Group | Result | What it proves |
|---|---|---|
| Geography domain | 136 tests pass | the G-2 domain rules |
| Database-free (Geography, Identity, purity and architecture guards) | 161 tests pass | no regression, domain stays pure |
| Isolated PostgreSQL (Membership, Platform, Identity) | 154 tests pass | no regression **in other contexts**. Geography has no database code yet |
| Full architecture suite | 316 tests, 4 failures | **names identical to the pre-Geography slice 1 baseline; messages identical to the G-2d-2 run** |

- **Assertion counts vary between runs** (randomised property tests and race tests). Test counts don't.
- **No test was deleted or weakened.**
- **One earlier assertion changed under F-2** (§4).
- **One new test's fixture was corrected after its first GREEN run** (also triggering D-83). Its first-run output is kept: `green-first-run-fixture-defect.txt`.

## 7. Remaining G-2 risks and proof obligations

- **Q-DS** (§4).
- **The complete-dependency claim (§5.2 [TU]) is only as strong as the matrix.** Any future derived rule must annotate the matrix first; F-1 shows what happens otherwise.
- **The obligations that belong to G-4, not G-2:** atomic publication, concurrent publishers, the stored before-image, and indexed family reads (a country change in a large country reads a large subtree).
- **G-0's race test is still not mutation-proved.**

## 8. Your decisions

1. **Q-DS:** A (recommended), B, or C.
2. **G-2 acceptance:** accept on Q-DS.

Not asked again: D-227, D-228, D-83, D-91, G-4, Q2, F-1 (a), F-2.
