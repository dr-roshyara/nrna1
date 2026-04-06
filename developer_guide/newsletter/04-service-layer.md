# 04 — Service Layer

**File:** `app/Services/NewsletterService.php`

The service is the single source of truth for all newsletter business logic. Controllers call the service; jobs call the service indirectly. No business logic lives in the controller or model.

---

## `createDraft()`

```php
public function createDraft(
    Organisation $org,
    User $creator,
    array $data,      // ['subject', 'html_content', 'plain_text'?]
    Request $request
): OrganisationNewsletter
```

**What it does:**
1. Sanitises `html_content` using `strip_tags()` with a safe allowlist:
   ```
   <p><br><strong><em><ul><ol><li><a><h1><h2><h3><img>
   ```
   This removes `<script>`, `<iframe>`, event attributes, and all other dangerous tags.
2. Creates the `OrganisationNewsletter` record with `status = 'draft'`.
3. Writes an audit log entry: `action = 'created'`.

**Returns:** The newly created `OrganisationNewsletter` model.

---

## `previewRecipientCount()`

```php
public function previewRecipientCount(
    OrganisationNewsletter $newsletter
): int
```

**What it does:**
Returns the number of members who *would* receive this newsletter if dispatched right now. Counts active, non-unsubscribed, non-bounced members.

```php
return Member::withoutGlobalScopes()
    ->where('organisation_id', $newsletter->organisation_id)
    ->where('status', 'active')
    ->whereNull('newsletter_unsubscribed_at')
    ->whereNull('newsletter_bounced_at')
    ->count();
```

This is a dry run — no rows are written. Used by the `previewRecipients` controller action to show a count before the admin clicks Send.

**Note:** `withoutGlobalScopes()` is required because `Member` uses the `BelongsToTenant` global scope, which would filter by session context and return 0 in this administrative context.

---

## `dispatch()`

```php
public function dispatch(
    OrganisationNewsletter $newsletter,
    Organisation $org,
    User $actor,
    Request $request
): void
```

**What it does (inside a DB transaction):**

1. **State guard** — throws `InvalidNewsletterStateException` if status ≠ `draft`.
2. **Assign unsubscribe tokens** — any member without a `newsletter_unsubscribe_token` gets one assigned (batch update).
3. **Build recipient list** — queries `Member` for active, non-unsubscribed, non-bounced members with their user email.
4. **Bulk insert** recipient rows in chunks of 500. Each row gets:
   - `status = 'pending'`
   - `idempotency_key = sha256(newsletter_id:member_id)`
   - Snapshotted `email` and `name`
5. **Update newsletter** — sets `status = 'queued'`, `idempotency_key`, `queued_at`, `total_recipients`.
6. **Write audit log** — `action = 'dispatched'`, metadata includes `recipient_count`.
7. **Dispatch job** — `DispatchNewsletterBatchJob::dispatch($newsletter->id)->onQueue('emails-normal')`.

If any step fails, the transaction rolls back and the newsletter remains in `draft` state.

---

## `cancel()`

```php
public function cancel(
    OrganisationNewsletter $newsletter,
    User $actor,
    Request $request
): void
```

**What it does:**
1. **State guard** — throws `InvalidNewsletterStateException` if status is not `draft` or `processing`.
2. Sets `status = 'cancelled'`.
3. Writes audit log: `action = 'cancelled'`.

**Note on mid-flight cancellation:** When a `processing` campaign is cancelled, any `SendNewsletterBatchJob` that is currently running will check `$newsletter->status` at the start of each batch, see `cancelled`, and return early without sending. Recipients already marked `sent` are not reverted.

---

## XSS Sanitisation Detail

The allowed HTML tags are intentionally minimal:

```php
private const ALLOWED_TAGS = '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><img>';
```

**Removed by sanitisation:**
- `<script>`, `<style>`, `<iframe>`, `<object>`, `<embed>`
- `on*` event attributes (e.g. `onclick`, `onmouseover`)
- `<form>`, `<input>`, `<button>`
- Any tag not in the allowlist

**Not removed (intentional):**
- `href` attributes on `<a>` — link tracking can be added later
- `src` attributes on `<img>` — needed for inline images

If stricter sanitisation is needed in future, replace `strip_tags()` with the `mews/purifier` or `symfony/html-sanitizer` package.
