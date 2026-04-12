# 07 — Unsubscribe & Bounce

---

## Unsubscribe Tokens

### Generation

Tokens are generated in `NewsletterService::dispatch()` before the recipient list is built:

```php
// Assign tokens to members who don't have one yet (batch update)
Member::withoutGlobalScopes()
    ->where('organisation_id', $org->id)
    ->whereNull('newsletter_unsubscribe_token')
    ->each(function (Member $member) {
        $member->update([
            'newsletter_unsubscribe_token' => Str::random(64),
        ]);
    });
```

This is a one-time assignment — tokens are permanent per member. A member retains the same token across all campaigns.

### Token Properties

| Property | Value |
|----------|-------|
| Column | `members.newsletter_unsubscribe_token` |
| Type | `VARCHAR(64)`, unique, nullable |
| Generation | `Str::random(64)` (cryptographically random) |
| Lifetime | Permanent (never rotated) |

---

## Unsubscribe Flow

### Route

```
GET /unsubscribe/{token}
```

This route is **public** — no authentication required. It is defined in `routes/web.php` outside any auth middleware group.

### Controller

**File:** `app/Http/Controllers/NewsletterUnsubscribeController.php`

```php
public function unsubscribe(string $token): Response
{
    $member = Member::withoutGlobalScopes()
        ->where('newsletter_unsubscribe_token', $token)
        ->first();

    if (! $member) {
        abort(404);
    }

    if (! $member->newsletter_unsubscribed_at) {
        $member->update(['newsletter_unsubscribed_at' => now()]);
    }

    return Inertia::render('Newsletter/Unsubscribed');
}
```

**`withoutGlobalScopes()` is required** because `Member` uses the `BelongsToTenant` global scope, which would filter by the current session's organisation context. An unsubscribe request has no session, so without this the query would always return null → 404.

### Idempotency

A second click on the unsubscribe link returns HTTP 200 and shows the same "you are unsubscribed" page. The `newsletter_unsubscribed_at` timestamp is not overwritten.

### Vue Page

**File:** `resources/js/Pages/Newsletter/Unsubscribed.vue`

No layout. Simple centred card: green checkmark, "You have been unsubscribed" heading, "You will no longer receive newsletters from this organisation." sub-text.

---

## List-Unsubscribe Header

Every outgoing newsletter email includes the RFC 2369 header:

```php
// app/Mail/OrganisationNewsletterMail.php

public function headers(): Headers
{
    return new Headers(
        text: [
            'List-Unsubscribe' => '<' . route('newsletter.unsubscribe', $this->recipient->member->newsletter_unsubscribe_token) . '>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]
    );
}
```

This enables one-click unsubscribe in Gmail, Outlook, and Apple Mail.

---

## Bounce Handling

### Column

```sql
members.newsletter_bounced_at  TIMESTAMP NULL
```

A member with a non-null `newsletter_bounced_at` is excluded from all future recipient lists (same as unsubscribed).

### Setting a Bounce

Bounces are currently set manually:

```php
$member->update(['newsletter_bounced_at' => now()]);
```

### Future: Bounce Webhook

When an SMTP provider (Mailgun, Postmark, SES) sends a bounce webhook, a dedicated controller would:

1. Receive the webhook POST.
2. Find the member by email address.
3. Set `newsletter_bounced_at`.
4. Return 200 to acknowledge receipt.

The newsletter system requires no changes — the `whereNull('newsletter_bounced_at')` filters already handle it.

---

## Suppression Logic (Dispatch Time)

Members are filtered at **dispatch time**, not at send time:

```php
// NewsletterService::dispatch()

Member::withoutGlobalScopes()
    ->where('organisation_id', $org->id)
    ->where('status', 'active')
    ->whereNull('newsletter_unsubscribed_at')   // ← suppressed
    ->whereNull('newsletter_bounced_at')         // ← suppressed
    ->chunk(500, function ($members) use (...) {
        // bulk insert into newsletter_recipients
    });
```

A member who unsubscribes *after* dispatch has already had a recipient row inserted and will still receive this one email. They will be excluded from all future campaigns.

---

## Re-subscribe

There is no re-subscribe feature. To manually re-subscribe a member, clear the timestamp:

```php
$member->update(['newsletter_unsubscribed_at' => null]);
```

This should only be done with the member's explicit consent.
