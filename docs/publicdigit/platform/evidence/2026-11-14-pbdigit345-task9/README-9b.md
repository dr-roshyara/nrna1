# Evidence: PBDIGIT-345 task 9b, the per-organisation relay (2026-11-14)

(9a: `README-9a.md`.)

| File | Shows |
|---|---|
| `t9b-01-red-pg/` | RED: `RelayOrganisationOutbox` and `ConnectionOutboxProcessor` don't exist. **Also two invalid fixtures:** Membership's append refuses an outer transaction (`AppendInsideTransactionRefused`), so the T-24 cases were rewritten as a generic business change plus its event |
| `t9b-02-green-pg/` | 3 relay tests delivering nothing (cause found below) |
| `t9b-03-debug/`, `t9b-04-debug-iso9/` | diagnosis: stored `available_at` 14:27:26 against database `now()` 14:27:25.75. **`timestamp(0)` rounds the default UP**, so a same-second event looked "not yet due". Fixed in the new relay (`localtimestamp(0)`) |
| `t9b-05a-central-relay-skips-same-second-row-run1/2` | the **central** relay has the same delay (PHP `now()`): its test row was skipped every time. **Not changed (T-6); recorded as PBDIGIT-350**; the test row was made explicitly due |
| `t9b-06-green-pg-run1/2` | the Platform PostgreSQL suite GREEN twice: 80 |
| `t9b-07-same-second-test/` | the new deterministic test: an event committed in the second half of a second is relayed in that second (its precondition, a rounded `available_at`, is asserted) |
| `t9b-08-central-relay-unchanged.txt` | `OutboxEventProcessor`, `ProcessOutboxEvents` and `routes/console.php` unchanged against HEAD |
| `t9b-09a-db-free-a6-shared-class-not-listed.txt` | ratchet A-6: a new Shared class must be listed consciously. `ConnectionOutboxProcessor` (technical outbox library) was added to `shared-contents.json`; `EventRegistryCompletenessTest` now checks both processors |
| `t9b-09-db-free.txt`, `t9b-13-db-free.txt` | database-free: **465 GREEN** |
| `t9b-10-mutation-run.txt`, `mutation-check-t9.php`, `mutants/` | 9 copy-loaded mutants (no source modified): **all KILLED by assertions**; no error kills, no equivalents, no survivors |
| `t9b-11a-…`, `t9b-11b-…` | the full cluster: the central-relay test was affected by other suites' committed central rows (the central relay takes the 100 oldest, and delivered other suites' C1 events). The test now asserts only its own route: its central row made the oldest, and only A's deliveries counted. **No relay code was changed** |
| `t9b-11-isolated-cluster-all/` | the full cluster: **386, exit 0** |
| `t9b-12-architecture-full.txt`, `.failures` | 361; **the same 4 pre-existing failures** (Election lifecycle ×2, GovernanceRuntime, Phase C25 sovereignty); not passing |
| `t9b-14-platform-suite-final/` | the Platform PostgreSQL suite, final: **81 GREEN** |

**Mutation note:** the central-relay test was edited after the mutation run (assertions narrowed to its own route). It was a killer of R9 only, and R9 is also killed by three other tests (`t6_c1…`, `t24_the_relay…`, `the_claim_lease…`). No classification depends on the edit.
