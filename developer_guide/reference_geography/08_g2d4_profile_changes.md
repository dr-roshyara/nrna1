# 08: G-2d-4, profile changes (G-6) (PBDIGIT-346)

**Design:** v3.11 §13 "G-6 (derived)", §5.2 (G-6 rows); D-81, D-83. Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2d4/`.

## In business terms

A country can change its profile: a class moves to another position, a tier is renamed, changes kind or ends.
- **Such a change is refused if it would leave real places broken:**
  - a district classified into a class that no longer has a position;
  - a district at a position that is now a street-level (address) tier;
  - a district no longer sitting below its province, or no longer above its own children.
- **Renaming a tier, or ending a class nobody uses, is fine.**
- **Only that country's places count:** Germany's profile never affects a Nepali district.

## Code

`RevisionValidator` rule **`G-6`**, for every PROFILE_CLASS and PROFILE_TIER claim (a new value or an end):

| Step | Read (all recorded) |
|---|---|
| affected classes: the class itself, or for a tier the classes at its position | family (PROFILE_CLASS, country, scheme, *) with value = the position |
| units carrying a class (plus this revision) | family (CLASSIFICATION, *, scheme) with value = the class |
| for each unit of that country, at every change date in the window: a position exists, its tier is TERRITORIAL, parent above, children below | the unit's classification, containment and country walk; family (CONTAINMENT, *, scheme) with value = the unit |

Each unit is reported once per claim. Several classes may share a position (v3.11 §13), so moving "district" to an existing territorial position 5 is valid.

## Testing

`tests/Unit/Contexts/ReferenceGeography/Domain/ProfileChangeTest.php` (7 tests):
- **genuine RED:** the 5 cases needing G-6 failed; the 2 "allowed" cases passed vacuously until the rule existed;
- **GREEN:** 119 domain tests, no earlier test changed.

**Traceability:** PBDIGIT-346 G-2d-4 · v3.11 §5.2, §13 · D-81, D-83.
