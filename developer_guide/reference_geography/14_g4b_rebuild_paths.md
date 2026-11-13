# 14: G-4b, rebuild paths for persistence (PBDIGIT-346)

**Design:** the G-4 interpretation §3 (a domain addition: mechanism only, **no rule changes**). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4b/`.

## In business terms

**When the system reloads a stored change or the roster, it must get back exactly what was saved, and refuse anything the rules could never have produced.**

A submitted change whose stored contents no longer match the fingerprint frozen at submission is refused. So stored data altered behind the system's back is detected, not trusted.

## Code (`app/Contexts/ReferenceGeography/Domain`)

| Method | Refuses |
|---|---|
| `SlotKey::decode(string)` | an unknown kind; a malformed or truncated component; a label the shape doesn't have; a missing component; a non-canonical value (e.g. position `04`); an invalid id. **It rebuilds through the shape's own factory and requires an exact re-encoding** |
| `BeforeImage::fromSegments(list)` | no segments; segments that aren't contiguous and ascending |
| `StewardRoster::fromEntries(list<RosterEntry>)` | a version gap; any write the domain refuses at that point (an end without an appointment); a repeated notice. **It replays through the domain's own write methods** |
| `Revision::snapshot()` / `Revision::fromSnapshot(RevisionSnapshot)` | duplicate claim or evidence ids; two claims for one slot overlapping (R-1); a preparer missing from excluded(r); an approval of a version that doesn't exist. **For a submitted or stale revision: a missing current version, assessor or valid validation, or content that doesn't reproduce the frozen digest** |

## How to use (G-4c, G-4d)

- **Repositories store the parts and call these methods to rebuild.** They never set private state.
- **A refusal on load is a data-integrity incident,** surfaced as an infrastructure error. It's never silently repaired.

## Testing

`RebuildPathsTest` (8 tests):
- all 22 slot-key shapes round-trip, and 8 kinds of tampered encoding are refused;
- a before-image round-trips, and bad segments are refused;
- a roster rebuilt from its entries answers identically at every version, and bad histories are refused;
- revisions round-trip in the draft, submitted and re-prepared states;
- overlap and digest tampering are refused.

**RED:**
- **The first run was invalid:** my fixture used "official" for a set-valued name. Fixed and kept.
- **Then 7 errors** for the missing behaviour.

**GREEN:** 189 domain and purity tests.

**Mutation check:** the first run **found a gap.** The R-1 overlap refusal had no test (R5 survived). A test was added, and then **5 of 5 mutants were killed.** Both runs are kept.

**Regression:** 203 database-free tests pass; the architecture suite shows the same 4 pre-Geography failures.

**Traceability:** PBDIGIT-346 G-4b · G-4 interpretation §3.
