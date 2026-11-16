# 05: Migrations whose `down()` refuses (conditional and forward-only)

**Origin:** PBDIGIT-349 I-3, review correction R-3: migration `2026_11_15_630000_identity_redemption_and_claim_cleanup_safety`. The pattern already existed in other contexts and is documented here as general database behaviour. Identity's specifics stay in `developer_guide/identity/04_i3_redemption_and_binding.md`.

## Purpose
Some schema changes can't be undone without losing data or breaking a guarantee. Their `down()` therefore **refuses**: it throws an exception **before changing anything**. Laravel's `migrate:rollback` stops at that migration, and every earlier migration stays applied.

## Two variants in the codebase
| Variant | When `down()` refuses | Examples |
|---|---|---|
| **Conditional** | only when undoing would destroy data or the schema isn't what it expects; otherwise it really rolls back | `2026_11_15_360000_create_org_membership_scheduled_resignations` (refuses while scheduled resignation declarations exist); `2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index` (refuses on schema drift) |
| **Forward-only** | always | `2026_11_15_630000_identity_redemption_and_claim_cleanup_safety` |

**Choose forward-only** when no safe rollback exists in principle. In the I-3 case:
- dropping `identity_outbox_events` would destroy undelivered `ClaimRedeemed` / `BindingRefused` events;
- the three dropped foreign keys to `identity_claims` can't be re-added once ended claims have been cleaned up.

## How it is written (the I-3 example)
```php
/** Forward-only (review R-3): changes nothing and refuses. See the header for why. */
public function down(): void
{
    throw new \RuntimeException(
        'Migration 2026_11_15_630000 is forward-only: rolling it back could destroy undelivered Identity events '
        . '(identity_outbox_events) and cannot re-add foreign keys after claim cleanup. Nothing was changed.'
    );
}
```
- **Throw first, and run no statement before the throw.** A refusing `down()` must leave the schema exactly as it was.
- **Say "Nothing was changed"** in the message, and explain why, in the migration header.
- **A real rollback, if ever needed,** is a new, reviewed migration that first proves it is safe. For I-3, that means proving no undelivered event exists.

## Consequences for developers and administrators
- **`php artisan migrate:rollback`** (with or without `--step`) stops with the exception when it reaches such a migration. The migrations after it in that rollback are already rolled back; the refusing one and everything before it stay applied.
- **Test databases:** reset them by recreating the database, as the isolated harness `scripts/testing/membership-pg.sh` does with a throwaway container, not by rolling back.
- **Never run `migrate:fresh` or `migrate:refresh` against the development database** (project rule). The refusal is a further safeguard, not a replacement for that rule.
- **Never "fix" a refusing `down()` to make tooling pass.** The refusal is the protection.

## Testing
The I-3 migration's refusal is tested in `tests/Feature/Contexts/Identity/RedeemClaimTest::test_the_redemption_migration_is_forward_only_and_never_drops_identitys_outbox`:
- it calls `down()`, expects the exception, and checks that `identity_outbox_events` still exists;
- an empty `down()` (mutant M-R3) makes it fail.

```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter test_the_redemption_migration_is_forward_only
```

## Traceability
PBDIGIT-349 I-3 review correction R-3 · evidence `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i3-corrections/` (`01a` RED, `03-mutant-R3`) · related: guide 04 (central vs organisation migrations).
