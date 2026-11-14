# Evidence and acceptance package: G-6c-6 and the whole G-6c D-90 governance notices (PBDIGIT-346), 2026-11-14

## Starting state

Branch `election-flow-updated` at `71e2a55dd` (G-6c-5). The commit was verified, and its file list is the step's own files plus evidence. **The six unrelated modified files were untouched throughout:** `.claude/sessions/2026-11-09.md`, `.claude/sessions/2026-11-11.md`, `bootstrap/cache/packages.php`, `bootstrap/cache/services.php`, `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md`, `readme-session-id.md`.

## Design to implementation (revision 3)

| Design | Implementation | Commit |
|---|---|---|
| §11 schema, row shapes, reference kinds, immutability, person index | migrations `2026_11_14_100000`, `2026_11_14_110000` (resolved once, with a safety stop) | `bd98f3e5a`, `cf2e81d32`, `035682387` |
| §4 to §8 domain: defects, the lifecycle (one definition), counted approver (§8.4 (a)) | `ApprovalDefects`, `FindingLifecycle`, `GovernanceNotice`, `ApprovalStatus`, `StewardRoster::componentAt` | `d416be813` |
| §5 atomic Identity merge/reversal + notices; §17 replay | `IdentityNoticeConsumer` under `withLock`; `PublicationGovernance::publishedApproval` | `c65650531`, `eedb2926f` |
| §7 confirmation | `ConfirmPublication`, `FindingConfirmation`, `PublicationGovernance::mayConfirm` | `fa0291f34` |
| §9 and §20 full-coverage correction | `CorrectionCoverage`, `PublishRevision::publish(…, corrects)` | `71e2a55dd` |
| §10 history, feed fields (B10); §11 no-bypass; §12 mutation run | `GeographyReadModel::approvalStatus` + `ApprovalHistory`; `PublicationEntry` + 3 optional fields; `PostgresFindingLifecycles`; no-bypass rule 7 | this commit |

## Files

| File | Shows |
|---|---|
| `c6-00-test-defect-final-method/` | first RED attempt: my helper `status()` clashed with PHPUnit's final `TestCase::status()` (a test defect, renamed) |
| `c6-01-red-pg/` | RED: `approvalStatus` and the feed fields don't exist (5 errors) |
| `c6-02-red-guard.txt` | RED: no rule catches the two planted notice bypasses |
| `c6-03-green-guard.txt` | GREEN: rule 7 catches them (and the existing `RogueSqlService`, which inserts into `refgeo_publications`); the real tree is clean |
| `c6-04-green-pg/` | GREEN: history, read model, Identity impact, confirmation and correction database tests: 43 tests |
| `c6-05-db-free.txt` | database-free (with the published-contract test and the ratchet): 343 GREEN |
| `mutation-check-g6c-full.php`, `c6-06-mutation-full.out`, `mutants/*/` | **the full G-6c mutation run, 43 mutants** (classification below). In this first pass, C3 was wrongly shown as killed after a `cd` failure, and R1/R2 were not yet classified |
| `c6-07-mutation-c3-r12-rerun.out` | after the driver fix: C3 killed by assertions; **R12 (both history bounds removed) killed** |
| `c6-08-mutation-r1-r2-classified.out` | R1 and R2: equivalent given each other |
| `c6-09-db-free.txt` | final database-free: **343 GREEN** |
| `c6-10-isolated-cluster-all/` | final full cluster: **320 tests, exit 0** |
| `c6-11-architecture-full.txt`, `.failures` | 343; **the same 4 pre-existing failures** (Election lifecycle ×2, Governance runtime, sovereignty leakage); not passing, unrelated |

## Mutation classification (43)

| Classification | Count | Mutants |
|---|---|---|
| Killed by assertions (0 errors) | 37 | D2–D7, C1–C6, K1–K9, L1–L8, R3, R4, R5, R12, K2/K4/K6/K7 (db) |
| Refused by the intended database constraint | 1 | K1 (db): `refgeo_publications_one_resolution` |
| Equivalent (argued) | 5 | D1; K3 (db), K5 (db); R1, R2 (R12 kills their combination) |
| Survived, unexplained | 0 | |

**Schema constraints:** covered by isolated refusal-reason tests (G-6c-1), not by schema mutants (the harness migrates inside the first test).

## Acceptance checklist

| Item | Status |
|---|---|
| History deterministic at a knowledge position; a later entry never rewrites an earlier answer | ✅ `GovernanceHistoryReadTest` |
| Every episode kept (finding, closure, new finding, correction) | ✅ |
| `FindingLifecycle` the one definition; one shared lifecycle reader | ✅ `PostgresFindingLifecycles` |
| Feed: notice kind, referent, correction link; ordered, gap-free, replay-stable; additive (existing consumers unaffected); no persons | ✅ |
| Feed consumer checkpoint atomicity | ➖ **a consumer obligation** (G-5c contract); to be tested by the first real consumer (backlog note) |
| Reading never writes | ✅ READ ONLY transaction test |
| Notices and the person index written only through the locked ledger | ✅ rule 7, with planted bypasses |
| Resolved once; concurrency single-winner; Identity atomicity; immutability | ✅ G-6c-1 to G-6c-5 tests, all re-run in the final cluster |
| Full mutation run | ✅ 0 unexplained survivors |
| Production restrictions (Platform Owner; migration approval) | ✅ unchanged; no route, no production action |

## Known limitations

- **The feed consumer obligations** are untestable from Geography.
- **Schema constraints are proven by refusal-reason tests,** not schema mutants.
- **The confirmer index row is a design superset** (guide 29).
- **Assertion totals vary between runs on unchanged code;** test counts and pass/fail are stable.
- **No HTTP delivery for steward acts** (G-4 conditions).
