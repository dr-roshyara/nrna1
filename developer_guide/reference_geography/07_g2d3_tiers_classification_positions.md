# 07: G-2d-3, tiers, classification and positions (PBDIGIT-346)

**Design:** v3.11 §13 (classification profiles), §5.2 (G-4 rows), §14; D-81 (levels 3–10 per country profile; never by counting parents), D-83 (address components are no official units), D-91 (admission marks level 2). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2d3/`.

## In business terms

Each country publishes a **profile**: which position means what (for example 3 = province, 4 = district, 9 = street), and at which position each class sits.
- **A street is an address component, never an official unit:** a place can be classified only into a class at a **territorial** position (D-83).
- **A parent always sits above its child.** A district may sit directly under a country (a skipped position is fine), but never under another district or below a smaller unit (G-4).
- **A unit's position comes from the profile of its country, never from counting how many parents it has.** Its country is found by walking up to the entry marked by admission (level 2).
- **If a profile changes during a claim's period** (for example, districts move to a new position in 2020), the rule is checked again from that date.

## Code (`app/Contexts/ReferenceGeography/Domain`)

| Element | Role |
|---|---|
| `TierKind` | `TERRITORIAL` · `ADDRESS_COMPONENT` |
| `TierMeaning` | a PROFILE_TIER value: kind + label, canonical `KIND:label`; `parse()` |
| `ProfilePositions` | derives `isAdmitted`, `parentOf`, `countryOf` (bounded walk to the admitted ancestor), `classOf`, `positionOfClass`, `positionOf` (2 for a level-2 entry), `tierAt`; `checkpoints()` gives every date in the window where a read slot changes. All reads go through the validator's recorded read, with the revision on top |
| `RevisionValidator` rules | `PROFILE_FORM` (positions 3–10 only; tier value `KIND:label`; class value = a position) · `D-83` · `G-4` |

**How G-4 is checked:**
- **A containment claim:** the unit's edge to its parent.
- **A classification change:** the unit's parent edge **and its children's edges.** The children are a recorded family read (CONTAINMENT, *, scheme) with value = the unit.
- **The dates checked:** every date where a fact read changes, repeated until no new date appears.
- **Each broken edge is reported once.**
- **An unclassified unit has no position yet,** so its edge is checked when it is classified, as v3.11 §5.2 requires.

## Testing

`tests/Unit/Contexts/ReferenceGeography/Domain/TierAndPositionTest.php` (7 tests):
- tier encoding;
- profile form;
- D-83 (an address component, a class with no position);
- G-4 (a skipped position allowed; inversion and equality refused; on a classification change through the children);
- a profile change in the middle of the interval;
- staleness after a later reclassification of the parent.

**RED** (7 errors: missing types) → **GREEN** 112 domain tests, **no earlier test changed.**

**Regression:**
- 137 database-free tests pass.
- The architecture suite shows the same 4 pre-existing failures, **identical by name and by failure message** (`reasons-before.txt`, `reasons-now.txt`).

## Not changed

**D-82_BASIS** (guide 06) is untouched, neither broadened nor narrowed. It stays an item for your review in the G-2 package.

**Traceability:** PBDIGIT-346 G-2d-3 · v3.11 §5.2, §13, §14 · D-81, D-83, D-91.
