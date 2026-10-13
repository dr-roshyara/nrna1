# 12: The cast success / failure contract and failure injection (PBDIGIT-274 S9a, G7)

**Story:** `PBDIGIT-274` S9a · **Code:** `app/Http/Controllers/VoteController.php` (`store`, the post-commit block) · **Test:** `tests/Isolated/Pbdigit274/Database/CastSuccessContractTest.php`.

## The contract
- **L-16:** the voter is told "success" only if the vote, the participation (`codes.has_voted`) and the slug's `has_voted` committed.
- **L-17:** once the cast has committed, a failure in post-commit work must never tell the voter to "try again". A retry would be refused anyway, because they have voted.

These are statements about committed data. The votes table has no voter reference, and the tests read aggregates only: vote count per election, the voter's code row, the voter's slug row. They never link a voter to a vote.

## What changed (F-B, PO decision: repair in 274)
After `DB::commit()`, the audit log, the voter-action log and the `current_step = 5` save used to sit inside the outer `catch (\Throwable)`, which answers "An error occurred… Please try again." They now have their own catch:
```php
        // PBDIGIT-274 S9a (L-17, PO decision 2026-10-13): the vote is committed from here on. A failure in the
        // post-commit work below is logged and must never reach the outer catch, …
        try {
        // Log vote_confirmed event
        …
        } catch (\Throwable $postCommitError) {
            Log::error('Post-commit work failed after a committed vote', [
                'election_id' => $election->id,
                'error' => get_class($postCommitError),
            ]);
        }
```

## Accepted, not changed
- **F-A → PBDIGIT-279:** a failed receipt write is absorbed. `ReceiptCode::firstOrCreate` runs in a savepoint (Laravel `createOrFirst`), so the transaction survives. The vote counts and "success" is shown, but no receipt row exists. The test pins this behaviour.
- **L-18 (O-15 = a):** the "vote confirmed" email is sent before the commit. If the commit fails, the email has gone out anyway. Characterised; follow-up D3.

## How the failure injection works (plan section 9.1)
- **C1** is the default connection, shared in-process by the test and the HTTP request. A failure is a uniquely named plpgsql trigger created on C1 outside any transaction (the committed base opens none), so the request sees it:
  ```php
  DB::statement("CREATE FUNCTION {$function}() RETURNS trigger LANGUAGE plpgsql AS \$\$ BEGIN RAISE EXCEPTION 't274 injected'; END \$\$");
  DB::statement("CREATE TRIGGER {$name} BEFORE INSERT ON {$table} FOR EACH ROW EXECUTE FUNCTION {$function}()");
  ```
  A failure **at commit** uses `CREATE CONSTRAINT TRIGGER … AFTER INSERT ON votes DEFERRABLE INITIALLY DEFERRED`.
- **C2** is a clone of the identity-checked connection with its own backend pid. It reads **committed** state only, drops the triggers, and asserts that no `t274_%` trigger or function is left.
- **Post-commit failures** are injected without the database:
  - **audit:** a fake `ElectionAuditService` whose `log()` throws;
  - **step save:** a `VoterSlug::updating` listener that throws only when `current_step` becomes 5.

  Each one records `DB::connection()->transactionLevel()` at the failure: it must be 0, proving the commit had happened.
- **Each test prints one evidence line:** HTTP status, `Location` (with `[slug]` masked), flash keys, committed counts, transaction level, and whether the cast lock is free.

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/CastSuccessContractTest.php
```

## Pitfalls
- **The test-database role must own the tables and be able to create plpgsql functions.** That holds after `migrate:fresh` by that role.
- **A post-commit failure is now only logged.** If you add post-commit work that matters to the voter, decide explicitly how its failure is reported. The outer catch is no longer the place.
- **Stub `ElectionAuditService` in tests,** or the cast writes JSONL audit files under `storage/logs/audit/`.

**Traceability:** `PBDIGIT-274` S9a: repair `083e4e785`; tests `39cad17d9`; evidence and decisions `1c5df4496`; follow-up `PBDIGIT-279`.
