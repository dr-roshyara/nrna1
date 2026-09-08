# Temporal Evidence Matrix

| Event | Evidence type | Date | What it establishes | What it does NOT establish |
|---|---|---|---|---|
| MD-010 | Direct primary-source text | 2026-09-01 | Corpus boundary defined by directory location, 5 excluded subdirectories named | Anything about `docs/knowledgeos/research/` — never mentioned |
| MD-011 | Direct primary-source text | 2026-09-01 (same day) | Boundary narrowed to `brainstorming/` only, 711-file `OUT_OF_SCOPE_ROOT` count | Whether `research/` was part of that 711-count crawl or absent from it entirely — not stated either way |
| M0030's own filename | Filename convention (established reliable elsewhere in this reconstruction) | 2026-09-01 | The prompt document's own authored date | Whether the *target* directory (`kernel-reduction/`) existed or was populated on this same date |
| `kernel-reduction/00-INDEX.md`'s "Experiment ID KR-2026-09-01" | Content-internal self-dating | 2026-09-01 | The experiment's own self-claimed date | Independent, external confirmation of that date — this is the source's own claim about itself, not verified against a second source |
| Filesystem `mtime` of `04-operator-contracts.md` and M0030 | Machine-observable, **low reliability** | Both 2026-09-01, 19 minutes apart | Consistent with same-session handling — `[MACHINE-OBSERVABLE FACT]` of low evidentiary weight | Creation time, authorship time, or execution time — `mtime` is a well-known checkout/copy artifact and is not treated as strong evidence here |
| First Git tracking of `docs/knowledgeos/research/` | `git log`, direct | 2026-09-06 | The date this material entered *this repository's* version-controlled history | **Does NOT establish** first filesystem existence, first authorship, or first creation — corrected per `01` claim 2 |
| Commit `70fee73c`'s own message | Direct primary-source text | 2026-09-06 | The check-in author's own wording distinguishing "brainstorming corpus" from "research lanes" at archival time | Does NOT establish Phase-0's own original intent (5 days earlier), formal governance authority, or bounded-context ownership |
| `kernel-reduction/19-directive-adoption...md` | Direct read | Undated internally (no separate self-dating found beyond the directory's own general 2026-09-01 experiment ID) | It supersedes the directory's own headline minimality claim | Whether it was written the same day or later — `TEMPORAL ORDER NOT ESTABLISHED` for its own specific position relative to `04`, beyond both sitting in the same nominally-dated experiment |

## Corrected reading

The temporal evidence is **consistent with** `kernel-reduction/` being produced the same day as M0030,
and **consistent with** the whole `docs/knowledgeos/` research material only entering this git
repository 5 days after MD-010/MD-011. Neither consistency is proof: the first rests on self-dating and
a low-reliability filesystem signal; the second establishes only tracking date, not existence date.
