# Reference Geography G-2: acceptance review for the Principal (PBDIGIT-346)

**Date:** 2026-11-13 · **Author:** Session A (engineering supplies evidence; it does not accept its own work) · **Status:** for the Principal's decision.
**Sources:**
- v3.11 (D-93): §3 (basis column), §5 (invariants), §5.2 (dependency matrix and exact queries), §13 (positions, G-4, G-6), §14;
- D-82, D-83, D-91, D-227, D-228.

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-review/`.

## 1. Verdict

**ACCEPT WITH EXPLICIT CONDITIONS.** The G-2 rules are implemented as v3.11 writes them, and every rule has tests covering both its failure case and its success case.

The review found two things you must decide:

- **F-1: a source gap in G-4.** §13 says a parent sits above its child on **every** edge. But the §5.2 matrix reads only the unit and its parent when a containment changes. A change that moves a unit into **another country**, or that ends its containment or admission, re-derives the positions of its whole subtree, and nothing re-checks them. The probe demonstrates this. No code was changed, because the fix depends on your decision.
- **F-2: one D-82_BASIS exception is wider than the source text.** A boundary may rest on "a cartographic source attributed as such", but the code accepts any basis of kind `Other`.

## 2. What G-2 guarantees, in business terms

- **A proposed change to the official atlas is a numbered, frozen revision.** A second person approves it exactly as it stands.
- **It is checked against the atlas as it stood at one moment.** Every fact it read is recorded, so a relevant later publication makes the approval stale and forces a re-check.
- **History is never rewritten.** Endings, corrections, divisions, mergers and successions are new facts (D-228).
- **Every official fact must rest on evidence from an issuer competent at the instrument's issue date,** and the mandates and rules it cites must have been in force then (D-227).
- **Each country's own profile governs classification.** A street can never become an official unit; a parent sits above its child; and a profile change cannot break existing places. **Subject to F-1.**

## 3. Traceability (invariant → source → behaviour → test)

| Invariant | Source | Implemented as | Test (file) | Failure case exercised? |
|---|---|---|---|---|
| Revision lifecycle, frozen content version, approval of that version | v3.11 §7; D-89 | `Revision` | RevisionTest (11), RevisionValidationTest (7) | yes (edit after approval, stale, re-prepare) |
| Supersession within a slot only; withdrawal = "none" in the target's slot | §3, §4 | `SlotKey`, `SlotHistory` | SupersessionTest (9), SlotKeyCollisionTest (9) | yes (collisions, randomised) |
| Before-image: VALUE / NONE / ABSENT at p₀ | §5.1 | `BeforeImage` | BeforeImageTest (7) | yes |
| Gains and losses make a validation stale | §5.1, §5.2 | `DependencySet` and the three dependency kinds | DependencyTest (11), DependencyRecordingReaderTest (4) | yes (phantom gain and loss; "not stale" cases) |
| The validator reads only through the recording reader | §5.2 [TU] | `DependencyRecordingReader`; purity guard | ReferenceGeographyDomainPurityTest | yes |
| Parents exist; admission before grouping (D-86) | §5.2 | PARENT_EXISTS, ADMISSION_BEFORE_GROUPING | RevisionValidatorTest (7) | yes |
| I-1, I-2 (identifiers) | §5, §5.2 | I-1 rule; `IdentifierInvariants` | RevisionValidatorIdentifierTest (7), IdentityInvariantsTest (7) | yes |
| Structural classification derived from claim kinds | §14 | `StructuralClassification` | StructuralClassificationTest (2) | yes |
| G-5 endings, successors, no live child; D-228 | §5.2; D-228 | G-5_* rules, WITHDRAWAL_BASIS | SuccessionValidationTest (8) | yes |
| D-82 competence at the issue date; mandates and precedence; disputes visible but not invalidating | D-82; §5.2 evidence row; D-227 | D-82, D-82_ISSUER_UNKNOWN, EVIDENCE_*; D-82_BASIS (§4) | CompetenceAndEvidenceTest (10) | yes |
| Profile form (positions 3–10; KIND:label) | §13; D-81 | PROFILE_FORM | TierAndPositionTest (7) | yes |
| D-83: a class must sit at a territorial position | D-83; §13 | D-83 | TierAndPositionTest; UndeterminedPositionTest | yes (address component; no position; no country) |
| G-4 on a classification change (parent and children) | §5.2 queries; §13 | G-4 | TierAndPositionTest; UndeterminedPositionTest | yes |
| G-4 on a containment change | §5.2 queries (unit and parent only) | G-4, parent edge | TierAndPositionTest | yes for the parent edge; **the subtree is not covered (F-1)** |
| G-4 at every change date in the interval | §5 "every affected interval" | `ProfilePositions::checkpoints`, iterated until no new date appears | TierAndPositionTest (a profile change in 2020) | yes |
| G-6: profile changes | §13 G-6; §5.2 | G-6 | ProfileChangeTest (7); UndeterminedPositionTest (scheme isolation) | yes (end, move, re-kind; another country; another scheme; staleness) |

**Multi-claim revisions:** each check sees the revision's own claims on top of the published atlas (`withRevision`). This is tested with a new unit made of three claims (TierAndPositionTest), and with revision units counted by G-6.

## 4. D-82_BASIS: exact source basis

**The rule as implemented:** a claim of a territorial kind needs a basis of kind `Instrument` (existence, containment, classification, boundary, succession, and the three profile kinds). The issuer's competence is then checked by D-82.

| Element | Exact source text | Strength |
|---|---|---|
| The general rule | D-82: "Reference Geography publishes only geographical facts supported by **competent official sources**." v3.11 §5: "D-82: territorial claims need territorial competence." v3.11 §3, basis column: "Territorial" for those kinds | **Direct.** Requiring an *instrument* is a **necessary implication**: competence is checked against the issuer of an instrument (D-227) |
| Exception: boundary | §3 Boundary row: "Territorial, **or a cartographic source attributed as such**" | **Direct requirement, implemented too broadly (F-2):** the code accepts any `BasisKind::Other`, not only an attributed cartographic source |
| Exception: existence through a duplicate search | §1, I-4: "every *establish* claim cites an establishing instrument, **or a recorded duplicate-search assessment**" | **Direct for I-4.** Using it as an exception to D-82 is an **interpretation** that reconciles I-4 with D-82 |
| Exception: withdrawal as a correction | §3 Withdrawal row: "Instrument **or correction**"; D-228 | **Direct.** "None" claims are exempt from D-82_BASIS, and WITHDRAWAL_BASIS separately requires an instrument or a correction |

**Changes to accepted decisions:** none. The rule makes D-82 and §3 executable. **F-2 is the only point where the code is looser than the text.**

**If you accept it, with F-2 narrowed:**
- an official fact always rests on a competent instrument;
- the only exceptions are an attributed map source for a boundary, a duplicate-search assessment for establishing a place, and a correction for a withdrawal.

**If you reject it:**
- D-82 is still checked when an instrument is cited;
- but a territorial claim could rest on a bare "other" source, which is weaker than D-82's "competent official sources".

**F-2 fix (one small TDD step):** a distinct basis kind for an attributed cartographic source, with a boundary accepting only that. **Recommendation: accept D-82_BASIS with F-2 narrowed.**

## 5. G-4 / G-6: the null-position verification

**Result:** a null position does not let an edge escape in any of the cases v3.11 §5.2 lists. This is pinned by UndeterminedPositionTest (4 tests). They passed on their first run, so these are verification tests, not RED tests, and no code changed.

- **An edge under an unclassified parent:** the parent has no position yet, so the edge is checked when the parent is classified, because a classification change reads the children (§5.2).
- **An edge of an unclassified child:** checked when the child is classified (its parent edge).
- **A classified unit with no reachable country:** refused by D-83.
- **G-6 in a profile change:** units without a country are skipped. A unit becomes classified only through D-83, which requires a country.

**F-1, the one gap.** It's in the source, not in a misreading of it.
- **What happens:** a unit whose `country(u)` changes re-derives the position of **every descendant** from a different profile (§13: "position = PROFILE_CLASS(country(u), …)"). There are three ways:
  - a containment change into another country;
  - a containment ending;
  - an admission starting or ending.
- **Why nothing catches it:** the §5.2 G-4 query for a containment change reads only `classOf(u)` and `classOf(P)`. It dates from v3.4, before derived positions existed in §13.
- **Probe** (`probe-cross-country-move.php` / `.out`): a province with a district under it moves from Nepal (province 3, district 4) to Germany (district 4, province 6). The move validates with **no violation**, although the district now sits above its province.
- **The same applies to D-83:** descendants may land on classes the new country does not have.

**Options:**
- **(a) Recommended. Whenever a claim changes `country(u)`,** re-check G-4 and D-83 for the whole subtree:
  - recorded family reads (CONTAINMENT, *, S) = each unit, bounded by depth 10;
  - this amends the §5.2 query row by an annotation, not a redesign;
  - it is a necessary implication of §13's "for every containment".
- **(b)** Forbid a containment change that changes the country, and require a D-228 succession (absorption or partial-split) instead. This is new policy.
- **(c)** Record it as a known limitation. Not recommended: the atlas could publish an inverted hierarchy.

## 6. Regression, re-run during this review

**Domain:** 123 tests pass (119 + 4 verification).

**Database-free:** 148 pass across all Reference Geography and Identity tests, with the purity and architecture guards.

**Isolated PostgreSQL (Membership, Platform, Identity):** 154 pass.
- **Assertion counts vary between runs** (randomised property tests and race tests). Test counts don't.
- **Geography has no PostgreSQL code yet;** that is G-4. So these runs prove no regression elsewhere, not Geography persistence.

**Architecture:** 316 tests, 4 failures.
- **Names:** identical to the **slice 1 acceptance baseline** (`docs/publicdigit/membership/evidence/2026-11-12-pbdigit343-slice1-acceptance/head/architecture-full.failures`), which predates all Geography work. So "pre-existing" is established.
- **Messages:** identical to G-2d-4.
- **The four failures:** election lifecycle voting-active state; ElectionClockService use; code creation redirect; the hard-coded "not eligible" denial reason.

**No earlier test was weakened or deleted.**

## 7. Remaining architectural risks

- **F-1:** the descendant edges after a country change (above).
- **F-2:** the boundary basis is accepted too broadly (above).
- **The complete-dependency claim (§5.2 [TU]) is only as strong as the matrix.** F-1 shows that one matrix row lags §13. A future derived rule must update the matrix first.
- **G-2 is domain-only.** Atomic publication, concurrent publishers and the stored before-image are unproven until G-4 (the ledger). G-0's race test has still not been mutation-proved.

## 8. Your decisions

1. **F-1:** choose (a), (b) or (c). Session A recommends **(a)**, implemented test-first as one step **before** G-3.
2. **D-82_BASIS:** accept, with the F-2 narrowing, or reject.
3. **G-2:** accept on the conditions you choose in 1 and 2.

Not asked again, because they are settled: D-227, D-228, D-83, G-4, Q2.
