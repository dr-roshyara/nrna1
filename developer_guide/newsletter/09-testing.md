# 09 — Testing Guide

---

## Running the Tests

```bash
# All newsletter tests
php artisan test tests/Feature/Organisation/Newsletter --no-coverage

# Individual suites
php artisan test tests/Feature/Organisation/NewsletterCreationTest.php --no-coverage
php artisan test tests/Feature/Organisation/NewsletterDispatchTest.php --no-coverage
php artisan test tests/Feature/Organisation/NewsletterBatchJobTest.php --no-coverage
php artisan test tests/Feature/Organisation/NewsletterKillSwitchTest.php --no-coverage
php artisan test tests/Feature/Organisation/NewsletterUnsubscribeTest.php --no-coverage
```

All 25 tests pass in under 15 seconds using SQLite `:memory:`.

---

## Test Database Configuration

Tests use SQLite in-memory, configured in `phpunit.xml`:

```xml
<server name="DB_CONNECTION" value="sqlite"/>
<server name="DB_DATABASE" value=":memory:"/>
```

Each test class uses the `RefreshDatabase` trait, which runs all migrations fresh before every test class and wraps each test method in a transaction that is rolled back — giving full isolation without manual teardown.

**Why SQLite?**
- No external dependency (no running MySQL server required)
- 10× faster than MySQL for test suites
- Full `migrate:fresh` every test run = no stale state

**Caveat:** Two migrations contain MySQL-specific SQL guarded behind `if (DB::connection()->getDriverName() === 'mysql')`. They are safely skipped on SQLite.

---

## Test Structure

### `NewsletterCreationTest` (6 tests)

Tests the `createDraft` flow:

| Test | Asserts |
|------|---------|
| `test_admin_can_create_newsletter_draft` | Status 302, DB row with `status=draft`, audit log `action=created` |
| `test_owner_can_create_newsletter_draft` | Same for owner role |
| `test_non_admin_cannot_create_newsletter` | Status 403 |
| `test_html_content_is_sanitised_on_save` | `<script>` tag not present in stored `html_content` |
| `test_admin_can_view_newsletter_list` | GET index returns 200 |
| `test_admin_can_preview_recipient_count_before_sending` | JSON response `{count: N}` |

### `NewsletterDispatchTest` (6 tests)

Tests state transitions:

| Test | Asserts |
|------|---------|
| `test_admin_can_dispatch_draft_newsletter` | Status→`queued`, `idempotency_key` set, job pushed |
| `test_cannot_dispatch_already_queued_newsletter` | 422 with error |
| `test_cannot_dispatch_completed_newsletter` | 422 with error |
| `test_non_admin_cannot_dispatch` | 403 |
| `test_admin_can_cancel_draft_newsletter` | Status→`cancelled`, audit log |
| `test_cannot_cancel_completed_newsletter` | 422 with error |

### `NewsletterBatchJobTest` (5 tests)

Tests the `SendNewsletterBatchJob` directly (no HTTP):

| Test | Asserts |
|------|---------|
| `test_batch_job_marks_recipients_sent` | Recipient `status=sent`, `sent_at` set, event fired |
| `test_batch_job_marks_failed_recipient_on_exception` | Recipient `status=failed`, `error_message` set, event fired |
| `test_batch_job_skips_already_sent_recipient` | `Mail::assertNothingSent()` |
| `test_batch_job_skips_recipient_with_status_sending` | `Mail::assertNothingSent()` |
| `test_campaign_counts_updated_via_job` | `sent_count=3`, `failed_count=1` after running job |

### `NewsletterKillSwitchTest` (3 tests)

Tests the kill switch threshold logic:

| Test | Asserts |
|------|---------|
| `test_kill_switch_triggers_when_failure_rate_exceeds_threshold` | `isKillSwitchTriggered() === true` when 52 sent + 16 failed |
| `test_kill_switch_does_not_trigger_below_threshold` | `isKillSwitchTriggered() === false` when 50 sent + 9 failed |
| `test_kill_switch_does_not_trigger_before_minimum_50_sent` | `isKillSwitchTriggered() === false` when 3 sent + 5 failed |

**Implementation note:** These tests set `sent_count`/`failed_count` directly on the model using `$newsletter->update([...])` rather than firing events. The counter increment architecture (inline in job, not in listener) means events do not increment counters — only the job's direct `increment()` calls do.

### `NewsletterUnsubscribeTest` (5 tests)

| Test | Asserts |
|------|---------|
| `test_member_can_unsubscribe_via_token` | `newsletter_unsubscribed_at` set, response 200 |
| `test_invalid_token_returns_404` | Status 404 |
| `test_already_unsubscribed_member_handled_gracefully` | Second call returns 200, timestamp unchanged |
| `test_unsubscribed_members_excluded_from_recipients_on_dispatch` | No recipient row for unsubscribed member |
| `test_bounced_members_excluded_from_recipients_on_dispatch` | No recipient row for bounced member |

---

## Test Helper: `createMember()`

All test classes share a private helper that builds the full `User → OrganisationUser → Member` chain required by the multi-tenant membership system:

```php
private function createMember(Organisation $org, string $role = 'admin'): User
{
    $user = User::factory()->create();

    UserOrganisationRole::create([
        'user_id'         => $user->id,
        'organisation_id' => $org->id,
        'role'            => $role,
    ]);

    Member::factory()->create([
        'user_id'         => $user->id,
        'organisation_id' => $org->id,
        'status'          => 'active',
    ]);

    return $user;
}
```

---

## Fakes Used in Tests

| Fake | Purpose |
|------|---------|
| `Mail::fake()` | Prevents actual SMTP delivery, enables `Mail::assertSent()` |
| `Queue::fake()` | Prevents actual job dispatch, enables `Queue::assertPushed()` |
| `Event::fake()` | Captures events, enables `Event::assertDispatched()` |

Tests that run jobs directly (batch job tests) do **not** use `Queue::fake()` — they instantiate and call `handle()` manually.

---

## Adding New Tests

1. Create the test file in `tests/Feature/Organisation/`.
2. Use `RefreshDatabase` trait.
3. Call `Mail::fake()` / `Queue::fake()` as needed at the top of each test method.
4. Use `createMember()` to build authenticated actors.
5. Follow Red-Green-Refactor: run the test, confirm it fails, implement, confirm it passes.
