# 02 — Database Schema

## Tables

### `organisation_newsletters`

The master campaign record. One row per newsletter draft/send.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint, PK | Auto-increment |
| `organisation_id` | uuid, FK → organisations | Tenant scoping |
| `created_by` | uuid, FK → users | Admin who created it |
| `subject` | string(255) | Email subject line |
| `html_content` | longText | XSS-sanitised before storage |
| `plain_text` | longText, nullable | Optional plain-text version |
| `status` | enum | draft, queued, processing, completed, failed, cancelled |
| `total_recipients` | uint, default 0 | Set on dispatch |
| `sent_count` | uint, default 0 | Incremented per successful send |
| `failed_count` | uint, default 0 | Incremented per failed send |
| `idempotency_key` | string(64), unique, nullable | Hash set on dispatch |
| `queued_at` | timestamp, nullable | When admin clicked Send |
| `completed_at` | timestamp, nullable | When last recipient processed |
| `created_at` / `updated_at` | timestamps | Standard |
| `deleted_at` | timestamp, nullable | Soft delete (draft only) |

**Indexes:**
- `(organisation_id, status)` — dashboard listing queries

---

### `newsletter_recipients`

One row per member per campaign. Created in bulk on dispatch.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint, PK | Auto-increment |
| `organisation_newsletter_id` | bigint, FK → organisation_newsletters | Cascade delete |
| `member_id` | uuid, FK → members | Cascade delete |
| `email` | string | Snapshotted at dispatch time |
| `name` | string, nullable | Snapshotted at dispatch time |
| `status` | enum | pending, sending, sent, failed, skipped |
| `idempotency_key` | string(64), unique, nullable | `sha256(newsletter_id:member_id)` |
| `error_message` | text, nullable | Last delivery error |
| `sent_at` | timestamp, nullable | When email was accepted by SMTP |
| `created_at` / `updated_at` | timestamps | Standard |

**Indexes:**
- `(organisation_newsletter_id, status)` — job batch loading

**Status meaning:**
- `pending` — waiting to be sent
- `sending` — lock acquired, in-flight
- `sent` — SMTP accepted
- `failed` — SMTP rejected after retries
- `skipped` — unsubscribed or bounced at dispatch time (not currently used — filtered out before insert)

---

### `newsletter_audit_logs`

Immutable GDPR trail. Never updated, never deleted.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint, PK | Auto-increment |
| `organisation_newsletter_id` | bigint, FK → organisation_newsletters | No cascade — log must survive campaign delete |
| `organisation_id` | uuid | Denormalised for fast org-scoped queries |
| `actor_user_id` | uuid | Who triggered this action |
| `action` | string | created, dispatched, cancelled, completed, failed |
| `metadata` | json, nullable | Subject, recipient_count, failure_rate, etc. |
| `ip_address` | string(45), nullable | IPv4 or IPv6 |
| `created_at` / `updated_at` | timestamps | Standard |

**Index:** `(organisation_newsletter_id, action)`

---

### `members` — added columns

Three columns added to the existing `members` table:

| Column | Type | Notes |
|--------|------|-------|
| `newsletter_unsubscribed_at` | timestamp, nullable | Set via unsubscribe link |
| `newsletter_unsubscribe_token` | string(64), unique, nullable | Random 64-char token, generated on dispatch |
| `newsletter_bounced_at` | timestamp, nullable | Set manually or by bounce webhook |

## Entity Relationships

```
organisations (1) ─────────────── (N) organisation_newsletters
                                              │
                                    (1) ──── (N) newsletter_recipients ──── (1) members
                                              │
                                    (1) ──── (N) newsletter_audit_logs
```

## Querying Patterns

```php
// Active drafts for an organisation
OrganisationNewsletter::where('organisation_id', $org->id)
    ->draft()
    ->latest()
    ->get();

// All pending recipients for a batch
NewsletterRecipient::where('organisation_newsletter_id', $newsletterId)
    ->where('status', 'pending')
    ->select('id', 'email', 'name', 'member_id', 'idempotency_key')
    ->get();

// Recipient count for preview (before dispatch)
Member::withoutGlobalScopes()
    ->where('organisation_id', $orgId)
    ->where('status', 'active')
    ->whereNull('newsletter_unsubscribed_at')
    ->whereNull('newsletter_bounced_at')
    ->count();

// Audit log for a campaign
NewsletterAuditLog::where('organisation_newsletter_id', $newsletterId)
    ->orderBy('created_at')
    ->get();
```
