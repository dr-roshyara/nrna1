# 04: G-2c, the Revision validator (PBDIGIT-346)

**Story:** `PBDIGIT-346` slice G-2, step c (completes G-2's first boundary) · **Design:** v3.11 §5, §5.1, §5.2, §5.3, §14; D-86, D-91, D-92; D-93, D-226
**Status:** implemented with TDD (five cycles). **Four rule groups are not implemented** because v3.11 does not define what they need (§"Open questions" below). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2c/`.

## In business terms

Before a proposed change can be submitted, PublicDigit checks it **against the atlas as it stood at one moment (p₀)**:
- **Do the parents it names exist** for the whole period?
- **Is a country admitted** before it is grouped into a UN region?
- **Is an identifier free:** does no other existing place hold it at the same time?
- **Is the change structural?** That decides whether a second person must approve. The system works it out from what the change touches; **nobody can label it otherwise.**

**Every fact the check reads is written down.** If any of those facts changes before publication, the change goes stale and must be re-prepared. That is the safeguard against a check silently relying on something that later changed.

**It does not decide who may approve or publish** (G-3, G-4).

## Where it fits (`app/Contexts/ReferenceGeography/Domain`, pure PHP)

| Element | Role |
|---|---|
| `PublishedAtlas` (interface) | the published atlas at p₀: `history(slot)`, `familySlots(kind, fixed, value, interval)`. The PostgreSQL adapter comes in G-4 |
| `DependencyRecordingReader` | **the validator's only view of the atlas:** each `history()` records a slot dependency, each `family()` a family dependency; repeated reads are recorded once |
| `RevisionValidator::validate($claims, DependencyRecordingReader)` | before-images, checks, structural flag. It has **no constructor and no other route** to the atlas (tested) |
| `ValidationResult` | violations · `DependencySet` (p₀ + dependencies) · before-images per claim · `structural` · `describe()` for the digest |
| `Violation` | rule code (`PARENT_EXISTS`, `PARENT_NOT_A_UNIT`, `ADMISSION_BEFORE_GROUPING`, `I-1`) + claim + detail |
| `StructuralClassification` | §14 by kind (exhaustive `match`: a new kind cannot be forgotten) |
| `Revision::validateWith()` | validates exactly the current claims. `submit()` now needs a **valid** validation; a claim change or re-preparation **voids** it |
| `tests/Support/ReferenceGeography/InMemoryPublishedAtlas` | test double of the port |

**How the checks see this revision:** each check reads the published slot (recorded), then lays the revision's own claims for that slot on top, as they will be after publication. So a parent or admission created in the same revision counts, and so does an identifier given up in it.

**The digest now also covers** p₀, the dependency predicates, every before-image and the structural flag (v3.11 §5.3).

## Rules implemented

| Rule | v3.11 | Test |
|---|---|---|
| before-images at p₀; slot dependencies on own target slots | §5.1 | `RevisionValidatorTest` |
| structural classification derived | §14 | `StructuralClassificationTest`, `RevisionValidatorTest` |
| existence of parents (over the whole interval; a name is not a parent) | §5.2 | `RevisionValidatorTest` |
| admission before grouping | §5.2; D-86 | `RevisionValidatorTest` |
| I-1 across the read set (holders, liveness, same-revision relinquishment and collisions) | §2, §5.2 | `RevisionValidatorIdentifierTest` |
| I-2 | structural (G-1) | `IdentityInvariantsTest` |
| I-4 | G-1 `Claim` | `SupersessionTest` |
| every read recorded; a later relevant publication invalidates, an unrelated one does not | §5.1, §5.2 | `DependencyRecordingReaderTest`, `RevisionValidatorTest`, `RevisionValidatorIdentifierTest` |
| submission needs a valid validation of the current claims; digest covers it | §5.3 | `RevisionValidationTest` |

## Open questions (not implemented; v3.11 does not define what they need)

| # | Rule | What is missing |
|---|---|---|
| **Q1** | **G-5:** ended places name successors | how an *ending* is represented (an EXISTENCE claim with an end, or a withdrawal); how a SUCCESSION value encodes the event kind and the successor set; which endings need successors |
| **Q2** | **G-4, G-6, D-83:** positions, tiers, territorial tiers | a PROFILE_TIER value must carry a tier label **and** a tier kind (TERRITORIAL / ADDRESS_COMPONENT), but there is no encoding; "the level-2 ancestor" needs a rule for what marks level 2 (reading ADMISSION as that marker is an inference from D-91, not text) |
| **Q3** | **D-82:** territorial competence | "at the claim's valid time": the date it takes effect, or its whole interval; and the claim basis needs the issuer reference |
| **Q4** | **evidence basis:** mandates, precedence rules, disputes on relied-upon claims | the reads to record are named; the outcome that makes a claim **invalid** (e.g. a disputed relied-upon claim) is not |

## Pitfalls

- **Never give the validator a `PublishedAtlas` directly.** An unrecorded read breaks the staleness guarantee.
- **The validation belongs to exactly the current claims.** Adding or removing one voids it; attaching evidence or recording an assessment does not.
- **An existence claim needs an instrument or a duplicate-search basis (I-4),** test fixtures included (this caught a fixture of mine).

**Traceability:** PBDIGIT-346 G-2c · v3.11 §2, §5, §5.1–§5.3, §14 · D-84, D-86, D-91, D-92, D-93, D-226.
