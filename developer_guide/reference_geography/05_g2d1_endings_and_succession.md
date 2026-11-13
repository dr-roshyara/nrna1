# 05: G-2d-1, endings, corrections and succession (PBDIGIT-346)

**Design:** v3.11 §3 (SUCCESSION and withdrawal rows), §5.2 (G-5); closure document G-5 and `SuccessorSet`; **D-228** (endings versus corrections). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2d1/`.

## In business terms

- **A place that is abolished** gets a "none" existence claim from the abolition date, **based on the abolition instrument**.
- **A wrong earlier entry** gets a "none" claim **based on a correction**.
- **Nothing published is ever edited.**
- **When a place is divided, merged or absorbed,** the change also records the event and its successors:
  - the successors must exist on that date;
  - the old place must have ended by then;
  - after a **partial split** the old place continues.
- **No place may be left under a parent that has ended:** it must be moved or ended in the same change.

## Code (`app/Contexts/ReferenceGeography/Domain`)

| Element | Role |
|---|---|
| `BasisKind::Correction` | the correction basis of v3.11 §3 ("instrument or correction"), made explicit (D-228) |
| `SuccessionKind` | `division` · `merger` · `absorption` · `partial-split`; `endsPredecessor()` |
| `SuccessionEvent` | the SUCCESSION value: kind + ≥ 1 successor; canonical `kind:uuid,uuid` (sorted, de-duplicated); `parse()` |
| `RevisionValidator` rules | `WITHDRAWAL_BASIS`, `G-5_SUCCESSION_FORM`, `G-5_SUCCESSOR_EXISTS`, `G-5_PREDECESSOR_ENDS`, `G-5_LIVE_CHILD` |

**Recorded reads:**
- successors' and predecessor's EXISTENCE (slots);
- the children of the ended unit in **any** scheme: family (CONTAINMENT, *) with value = the unit.

## Testing

`tests/Unit/Contexts/ReferenceGeography/Domain/SuccessionValidationTest.php` (8 tests).

**Fixture corrections:**
- **An existing I-1 fixture's withdrawal rested on "other"**, which D-228 now forbids. It was corrected to an instrument basis rather than relaxing the rule.
- **One expected sort order of mine was wrong;** the code sorted correctly.

**Traceability:** PBDIGIT-346 G-2d-1 · v3.11 §3, §5.2 · closure G-5 · D-228.
