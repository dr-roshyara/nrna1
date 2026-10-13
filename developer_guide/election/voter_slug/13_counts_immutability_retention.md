# 13: Count invariant, voted-slug immutability, retention scan (PBDIGIT-274 S9b)

**Story:** `PBDIGIT-274` S9b · **Production code:** none (verification) · **Tests:**
- `tests/Isolated/Pbdigit274/Database/CountInvariantTest.php`
- `…/CountInvariantRealConcurrencyTest.php`
- `…/VotedSlugImmutabilityTest.php`
- `tests/Architecture/VoterSlugParentHardDeleteScanTest.php`

## The count invariant (L-14, O-6 = B)
Per real election:
- **V** = votes;
- **C** = codes with `has_voted`;
- **S** = voter slugs with `has_voted` (soft-deleted included).

For P1 slug-flow casts, **ΔV = ΔC = ΔS = successful casts**. These are aggregates only: the votes table has no voter reference, and no test or query tries to attribute a vote to a voter.

- **`test_isolated_p1_casts_keep_deltas_equal`** runs real HTTP casts in a fresh election:
  - A casts;
  - B's link expires and is replaced, then B casts;
  - C's cast is forced to fail (an injected trigger, guide 12);
  - A submits again (refused).

  Result: Δ = 2 / 2 / 2.
- **`test_p1_concurrent_casts`** uses G6 processes:
  - a duplicate submission is observed blocked on the same codes row and consumes nothing;
  - another voter is not held up.

  It uses the D2 stand-in, so it proves ΔC = ΔS; ΔV is covered by the real-cast test above.

## The baseline query (for the release, O-6b = i)
`CountInvariantTest::BASELINE_SQL` gives V, C and S per real election. Run it in a read-only transaction, so PostgreSQL itself refuses any write (adapted from `test_baseline_read_only`, which also proves that a write inside is refused):
```php
DB::connection($c)->transaction(function () use ($c) {
    DB::connection($c)->statement('SET TRANSACTION READ ONLY');
    return DB::connection($c)->select(CountInvariantTest::BASELINE_SQL);
});
```
Differences are reported, never corrected. No absolute-equality claim is made (other vote-writing paths P2–P4 can change V and C without S).

## Voted-slug immutability (L-15, plan section 10.3)
A voter who has voted keeps an unchanged history. The fixture is a voted current link, an older replaced link, step rows 1–5 and a voted codes row. Every slug row (incl. status), every step row and the codes row stay **byte-identical** across:
- `elections.start`, `voter.start` and `voter.restart`;
- admin recovery and `election:recover-voter`;
- `voting:clean-expired-slugs`;
- a re-vote attempt.

**The requests carry `current_organisation_id`, as in production.** That arms the `retrieved` hook's write. The hook is the known exception (F10): it rewrites an expired, still-active slug's `status` / `is_active`, but **never `has_voted`**, and deletes nothing.

## Retention scan (O-12 = a, plan section 10.4)
`VoterSlugParentHardDeleteScanTest` scans `app/` for hard deletes of users, organisations and elections. Such a delete cascades and destroys voting-link history. The only allow-listed sites are `SetupDemoElection` and `SetupPublicDemoElection` (`forceDelete()` on a `type = 'demo'` election). Any new site fails the test and needs a decision. This is **limited evidence**: the scan cannot see raw SQL built elsewhere, future jobs or database administration.

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/CountInvariantTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/CountInvariantRealConcurrencyTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VotedSlugImmutabilityTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/VoterSlugParentHardDeleteScanTest.php
```

## Pitfalls
- **`code1` is unique per organisation:** use a random value per voter in fixtures.
- **Without a tenant in the session, the `retrieved` hook writes nothing.** Immutability tests that omit `current_organisation_id` can pass for the wrong reason.

**Traceability:** `PBDIGIT-274` S9b: tests `cd259e82c`; evidence `1c5df4496`.
