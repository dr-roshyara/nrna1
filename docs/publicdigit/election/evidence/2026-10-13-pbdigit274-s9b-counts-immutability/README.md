# PBDIGIT-274 S9b: count invariant, voted-slug immutability, retention scan, 2026-10-13

**Authority:** Human PO ("start S9", "continue").

**Scope:**
- `pbdigit274_isolated_test` only (committing base, identity check per run), plus one database-free architecture test.
- **No production code changed.** S9b is verification.
- No password and no voting link appear in this folder.

## Tests and results
| # | File | Test | Result |
|---|---|---|---|
| 01a | `01a-immutability-first-run.out` | `VotedSlugImmutabilityTest`, first run | 6 / 7. The retrieved-hook test expected status `expired` but the row stayed `voted`: the hook's update runs through the **tenant-scoped** query builder and writes nothing without a tenant in the session |
| 01b | `01b-immutability.out` | `VotedSlugImmutabilityTest`, final (the voter's session carries `current_organisation_id`, as in production) | **7 / 28** |
| 02 | `02-count-invariant.out` | `CountInvariantTest` (real HTTP casts) | **2 / 14** (a first attempt failed on a fixture bug, a duplicate `code1` per organisation, and was overwritten) |
| 03 | `03-count-concurrency.out` | `CountInvariantRealConcurrencyTest` (G6 processes) | **1 / 12** |
| 04 | `04-parent-hard-delete-scan.out` | `tests/Architecture/VoterSlugParentHardDeleteScanTest` (database-free) | **1 / 3** |
| 05 | `05-isolated-all.out` | the whole isolated PBDIGIT-274 suite | **90 / 458** |

## What is proven
**Section 10.3: a voter who has voted keeps an unchanged history.**

The fixture is a voted current link (past its lifetime, still active as the cast leaves it), an older replaced link, step rows 1–5, and a voted codes row. Every slug row (incl. status), every step row and the codes row stay **byte-identical** across:
- `elections.start`, `voter.start` and `voter.restart`;
- admin recovery and `election:recover-voter`;
- `voting:clean-expired-slugs --hours=1`;
- a re-vote attempt (the voted link → the election page; start → no link).

The retrieved hook (with a tenant session) marks the voted, expired slug `is_active = false` / status `expired` (known, F10), but **`has_voted` stays true and nothing is deleted**.

**Section 10.2 (1): isolated P1 casts.**
```
S9B-COUNTS before={"v":0,"c":0,"s":0} after={"v":2,"c":2,"s":2} delta={"v":2,"c":2,"s":2} successes=2
steps=["A cast: success","B cast after replacement: success","C forced failure: refused","A double submission: refused"]
```
B's replaced link is kept (soft-deleted) and was not voted with.

**Section 10.2 (2): real concurrency (G6).** X1 holds the codes row after its compare-and-set. The duplicate X2 is observed blocked on that row (`pg_stat_activity`), while voter Y casts without waiting.
```
X1 consumed 1 · X2 consumed 0 · Y consumed 1 · ΔC = 2 · ΔS = 2
```
**Limit (D2):** this uses the G6 cast stand-in (codes compare-and-set plus slug marking), so ΔV is not measured here; it is proven with real casts in (1).

**Section 10.2 (4): baseline.**
- `CountInvariantTest::BASELINE_SQL` runs inside `SET TRANSACTION READ ONLY`, and a probe write is refused by PostgreSQL ("read-only").
- On the isolated database: 1 election, 0 mismatches.
- **This is the query for the release runbook (G8):** it gives V, C and S per real election; mismatches are reported, never corrected (O-6b = i). No absolute-equality claim.

**Section 10.4: retention scan.**
- Allow-listed: two application hard deletes of a slug parent, `SetupDemoElection` and `SetupPublicDemoElection`, both `forceDelete()` on an election filtered to `type = 'demo'`.
- No other application site was found.
- **Limited evidence:** the scan cannot see raw SQL built elsewhere, future jobs or database administration.

## Observations (recorded, not changed)
- **The retrieved hook's write depends on the tenant context.** Without `current_organisation_id` it updates nothing; with it, it rewrites a voted slug's `status` to `expired` (F10; has_voted untouched).
- **Already covered elsewhere:** the O-13 residual-state test (`VoterSlugIssuerRealConcurrencyTest::test_issuer_first_then_cast_ends_in_the_accepted_o13_state`, G6).

Checksums: `SHA256SUMS`.
