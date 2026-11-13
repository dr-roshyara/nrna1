# 09: G-2 findings, country changes (F-1) and the boundary basis (F-2) (PBDIGIT-346)

**Design:**
- the Principal's decisions on the G-2 acceptance review, 2026-11-13 (F-1 option (a); F-2 narrowed);
- v3.11 §3 (Boundary row), §13 (derived positions), and the §5.2 implementation annotation.

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-findings/`.

## In business terms

- **F-1:** when a place gets a different country, its whole subtree is re-checked against the new country's profile. That happens when it moves into another country, when its containment ends, or when its or an ancestor's admission starts or ends. **Moving between countries is allowed**, but it's refused if:
  - a child would end up above its parent;
  - a descendant has a class the new country doesn't have (for example a ward, in a country without wards).
- **F-2:** a boundary may rest on a map source only if that source is **explicitly identified and attributed as cartographic evidence**. A generic "other" source no longer qualifies, and a map source is no basis for any other kind of claim.

## Code

| Element | Role |
|---|---|
| `BasisKind::AttributedCartographicSource` | the only non-instrument basis a BOUNDARY accepts. The basis reference identifies the source |
| `RevisionValidator::countryChanges()` | for every CONTAINMENT or ADMISSION claim, compares `countryOf(u)` before (published) and after (this revision on top) at every change date. Where they differ, it re-checks D-83 for u and its descendants, and G-4 for each descendant's edge to its parent |
| `descendants()` | breadth-first over recorded family reads (CONTAINMENT, scheme) = node, plus the revision's own containment claims; at most 10 levels |
| `inSubtreeAt()` | at date t, whether a candidate's chain reaches u before any other admitted entry |
| `childSchemes()` | for an admission, which schemes u has children in (a recorded family read across all schemes) |

**Skipped:**
- descendants that are themselves admitted (they are their own country);
- units whose classification this revision also changes, because the classification claim's own G-4 and D-83 checks cover them.

**Each (rule, unit) is reported once per revision.**

## Pitfalls

- **Read descendants only when the country changes.** Otherwise every move within one country would depend on its whole subtree, and unrelated later changes would make it stale. A test pins this.
- **The "before" country is read through the same recording reader,** so a later change to an ancestor makes the validation stale.

## Testing

**`CountryChangeSubtreeTest` (10 tests):**
- a compatible destination;
- the inverted profile (G-4);
- a missing class (D-83);
- containment ending; admission ending; admission starting with its profile in the same revision;
- a profile change in 2020 inside the interval;
- staleness from a grandchild, and from a new grandchild;
- no false staleness within one country;
- scheme isolation.

**`BoundaryBasisTest` (3 tests).**

**RED:**
- F-1: 7 failures, the missing behaviour. The 3 guards against over-reach passed already.
- F-2: 2 errors (the missing basis kind) and 1 failure (`Other` still accepted).

**GREEN:** 136 domain tests pass.

**Changed earlier assertion:** `CompetenceAndEvidenceTest`, the boundary example, from `Other` to `AttributedCartographicSource`. The approved F-2 narrowing invalidated the old assertion.

**Fixture correction in a new test:** the F-2 "other claim kind" fixture also triggered D-83 (the unit has no country). The test now asserts that D-82_BASIS is present, with an instrument control case. The first-run output is kept.

**Traceability:** PBDIGIT-346 · G-2 findings F-1, F-2 · v3.11 §3, §5.2 annotation, §13.
