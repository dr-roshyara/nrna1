# 10 — Troubleshooting

---

## Tests Fail With Migration Errors

**Symptom:** `SQLSTATE[HY000]: General error: 1 near "MODIFY"` or `near "em": syntax error`

**Cause:** Some migrations contain MySQL-specific SQL. SQLite does not support `MODIFY COLUMN` or table aliases in `UPDATE` statements.

**Fix:** These are already guarded in the codebase:

```php
// MySQL-only guard (already applied)
if (DB::connection()->getDriverName() === 'mysql') {
    DB::statement("ALTER TABLE `members` MODIFY COLUMN ...");
}
```

If you add a new migration with MySQL-specific SQL and tests run on SQLite, apply the same guard.

---

## `sent_count` Is Double What It Should Be

**Symptom:** After 3 sent emails, `sent_count = 6`.

**Cause:** Laravel 11 auto-discovers listeners from `app/Listeners/` by default. If `UpdateNewsletterCounters` is also registered manually in `EventServiceProvider`, each event fires the listener twice.

**This was the original architecture:** counters were incremented in the listener. The fix was to move counter increments **into `SendNewsletterBatchJob::handle()` inline** and make the listener's `handleSent()` method a no-op.

**Current state:** Counters are incremented directly in the job:
```php
OrganisationNewsletter::where('id', $this->newsletterId)->increment('sent_count');
```

The listener only checks the kill switch on `handleFailed()`. Double-registration is harmless.

**If you see double counts again:** Check that `bootstrap/app.php` has `->withEvents(discover: [])` and that `EventServiceProvider::shouldDiscoverEvents()` returns `false`.

---

## `previewRecipientCount()` Returns 0

**Symptom:** The preview count shows 0 even though there are active members.

**Cause:** The `Member` model has a `BelongsToTenant` global scope that filters by session context. In an admin context (or test context), the session tenant may not be set.

**Fix:** Already applied in `NewsletterService::previewRecipientCount()`:
```php
Member::withoutGlobalScopes()
    ->where('organisation_id', $newsletter->organisation_id)
    ->where('status', 'active')
    ->whereNull('newsletter_unsubscribed_at')
    ->whereNull('newsletter_bounced_at')
    ->count();
```

If you add a new query against `Member` in the newsletter service or controllers, always use `withoutGlobalScopes()`.

---

## Unsubscribe Always Returns 404

**Symptom:** Valid token in the URL, but the route returns 404.

**Cause:** `Member::where('newsletter_unsubscribe_token', $token)` without `withoutGlobalScopes()` returns null because there is no active tenant session.

**Fix:** Already applied in `NewsletterUnsubscribeController`:
```php
$member = Member::withoutGlobalScopes()
    ->where('newsletter_unsubscribe_token', $token)
    ->first();
```

---

## Campaign Stuck in `queued` State

**Symptom:** Newsletter shows `queued` but nothing is happening.

**Cause:** `DispatchNewsletterBatchJob` was never processed.

**Fix:**
```bash
# Check if queue worker is running
php artisan queue:work --queue=emails-normal

# Or process one job manually
php artisan queue:work --queue=emails-normal --once

# Check for failed jobs
php artisan queue:failed
```

---

## Campaign Stuck in `processing` State

**Symptom:** Newsletter shows `processing` for a long time. Some recipients are `sent`, rest are `pending`.

**Causes and fixes:**

1. **Queue worker died mid-batch** — restart the worker, it will re-process remaining pending recipients.

2. **Batch jobs failed and exhausted retries** — check `failed_jobs` table:
   ```bash
   php artisan queue:failed
   php artisan queue:retry all
   ```

3. **Recipients stuck in `sending` state** — a worker crashed after acquiring the Redis lock and marking a recipient `sending`, but before finishing. The Redis lock expires after 30 seconds automatically. After that, the recipient can be retried. If the lock expired but status is still `sending`, it will be skipped by the double-check guard. Manually reset:
   ```php
   NewsletterRecipient::where('status', 'sending')
       ->where('updated_at', '<', now()->subMinutes(5))
       ->update(['status' => 'pending']);
   ```

---

## `InvalidNewsletterStateException` in Production

**Symptom:** Admin gets a 422 error when trying to send a newsletter.

**Cause:** The newsletter is not in `draft` status. Refresh the page — it may have been dispatched or cancelled by another admin.

**Debug:**
```php
$newsletter = OrganisationNewsletter::find($id);
dd($newsletter->status); // Check current state
```

---

## Audit Log Has Duplicate `dispatched` Entries

**Symptom:** `newsletter_audit_logs` has two `dispatched` rows for one newsletter.

**Cause:** `NewsletterService::dispatch()` was called twice. This should be prevented by the `status !== 'draft'` guard, but could happen if two requests race before the status update commits.

**Prevention:** The `idempotency_key` column on `organisation_newsletters` has a `UNIQUE` index. A second dispatch attempt will fail with a unique constraint violation if the key was already set, rolling back the transaction.

---

## Mail Not Being Sent in Development

**Symptom:** Queue worker runs but no emails arrive.

**Fix:** Check your `.env` mail configuration:
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025  # Mailpit / Mailtrap
```

For local development, use [Mailpit](https://github.com/axllent/mailpit):
```bash
# Visit http://localhost:8025 to see caught emails
```

---

## `List-Unsubscribe` Header Missing

**Symptom:** Email clients don't show the unsubscribe button.

**Cause:** The member's `newsletter_unsubscribe_token` is null. Tokens are assigned during dispatch, but if the member was added after dispatch and a recipient row was somehow inserted manually, the token may be missing.

**Fix:**
```php
Member::whereNull('newsletter_unsubscribe_token')
    ->where('organisation_id', $orgId)
    ->each(fn ($m) => $m->update(['newsletter_unsubscribe_token' => Str::random(64)]));
```

Then re-dispatch the newsletter (cancel the current one first if needed).
