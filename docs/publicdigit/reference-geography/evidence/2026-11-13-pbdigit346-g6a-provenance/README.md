# Evidence: G-6a provenance (Principal review 2026-11-13), PBDIGIT-346

**Finding:** acceptance named any *existing* revision, so an unrelated revision could be attached to a candidate.
**Ratified rule:** the closure design lists the command "Accept (→ ProposeRevision)".

| File | Shows |
|---|---|
| `g6a2-01-red.txt` → `g6a2-02-green.txt` | `CandidateServiceTest`: RED (the service took a catalogue, not `RevisionService`), then GREEN, 8 tests including the no-bypass guard. Acceptance prepares the revision; attaching an existing one is refused; nothing is prepared if acceptance is refused |
| `g6a2-03-red-postgres/` → `g6a2-04-green-postgres/` | the database refused nothing; then, with the unique index, a second candidate on the same revision is refused. 7 tests |
| `g6a2-05-db-free.txt` | database-free: 296 GREEN |
| `g6a2-06-isolated-cluster-all/` | **the full cluster on the final code: 273 tests, exit 0** |
| `g6a2-07-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |

**Also removed:** the read-only port `RevisionCatalogue`, unused once acceptance goes through `RevisionService`.
