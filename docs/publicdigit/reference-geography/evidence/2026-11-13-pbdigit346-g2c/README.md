# PBDIGIT-346 G-2c, and the G-2 evidence index (2026-11-13)

**Raw output; the Principal decides acceptance.** Database-free. G-2 = G-2a (`4e0dece98`) + G-2b (`7016ddbe1`) + G-2c (this commit).

## G-2c cycles (`cycles/`)

| File | Cycle | Result |
|---|---|---|
| `red1` / `green1` | structural classification | RED Tests: 2, Assertions: 0, Errors: 2. → GREEN OK (2 tests, 25 assertions) |
| `red2` / `green2` | recording reader + port | RED Tests: 4, Assertions: 0, Errors: 4. → GREEN OK (4 tests, 8 assertions) |
| `red3` / `green3` | validator: before-images, parents, admission, staleness, signature | RED Tests: 7, Assertions: 0, Errors: 7. → GREEN OK (7 tests, 22 assertions) |
| `red4-fixture-defect` | I-1, first run | **not a valid RED:** my fixtures' existence claims lacked an instrument, so G-1's I-4 rightly refused them. Fixtures fixed |
| `red4` / `green4` | I-1 | **genuine RED** Tests: 7, Assertions: 7, Failures: 3. (the 3 cases needing the check; the 4 "allowed" cases passed vacuously until the check existed) → GREEN OK (80 tests, 885 assertions) |
| `red5` / `green5` / `green5b` | Revision integration | RED Tests: 7, Assertions: 0, Errors: 7.; then Tests: 87, Assertions: 965, Errors: 5. (**the intended change:** G-2a's tests now needed a validation before submitting; their helpers were updated); → GREEN OK (87 tests, 977 assertions) |

## G-0 … G-2 regression

| File | Result |
|---|---|
| `db-free-all.txt` | OK (112 tests, 1087 assertions) |
| `architecture-full.txt` / `.failures` | Tests: 316, Assertions: 964, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. Exactly the 4 pre-existing failures, by name (in `.failures`) |

## Requirement → test (all of G-2)

| Requirement (v3.11) | Test file (`tests/Unit/Contexts/ReferenceGeography/Domain/`) | Evidence |
|---|---|---|
| R-1; completeness; frozen content; immutable evidence; approvals per version; `excluded(r)`; lifecycle (§5, §5.3, §8, §16) | `RevisionTest` | `../2026-11-13-pbdigit346-g2a/` |
| before-images VALUE/NONE/ABSENT at p₀ (§5.1) | `BeforeImageTest` | `../2026-11-13-pbdigit346-g2b/` |
| slot / family / subject dependencies; gains and losses; not-stale (§5.1, §5.2) | `DependencyTest` | `../2026-11-13-pbdigit346-g2b/` |
| structural classification by kind (§14) | `StructuralClassificationTest` | here |
| every read recorded; p₀ visibility (§5.2) | `DependencyRecordingReaderTest` | here |
| parents exist; admission before grouping; own-slot dependencies; staleness; recording-reader-only signature | `RevisionValidatorTest` | here |
| I-1 across the read set | `RevisionValidatorIdentifierTest` | here |
| valid validation required; voiding; digest covers p₀, dependencies, before-images, structural (§5.3) | `RevisionValidationTest` | here |

## Not implemented: open questions (see the guide `developer_guide/reference_geography/04_g2c_validator.md`)

- **Q1:** G-5 (ending representation, successor encoding).
- **Q2:** G-4, G-6, D-83 (tier label and kind encoding; the level-2 marker).
- **Q3:** D-82 (the time of competence; the issuer field).
- **Q4:** the evidence-basis outcome.
