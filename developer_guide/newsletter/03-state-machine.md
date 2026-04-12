# 03 — Campaign State Machine

## States

```
DRAFT
  ├──(admin clicks Send)──────────────────► QUEUED
  │                                            │
  │                              (DispatchJob starts)
  │                                            │
  │                                            ▼
  │                                       PROCESSING
  │                                       /    |    \
  │                             (all done)  (kill)  (cancel)
  │                                /         |         \
  │                                ▼         ▼          ▼
  │                           COMPLETED   FAILED    CANCELLED
  │
  └──(admin cancels)──────────────────────► CANCELLED
```

## Allowed Transitions

| From | To | Trigger |
|------|----|---------|
| `draft` | `queued` | `NewsletterService::dispatch()` |
| `draft` | `cancelled` | `NewsletterService::cancel()` |
| `queued` | `processing` | `DispatchNewsletterBatchJob::handle()` |
| `processing` | `completed` | `SendNewsletterBatchJob` — all recipients done |
| `processing` | `failed` | Kill switch triggers in `SendNewsletterBatchJob` |
| `processing` | `cancelled` | `NewsletterService::cancel()` |

Any other transition throws `InvalidNewsletterStateException`.

## Enforcement

State is enforced in the **service layer**, not the model. The controller catches `InvalidNewsletterStateException` and returns HTTP 422:

```php
// app/Services/NewsletterService.php

public function dispatch(OrganisationNewsletter $newsletter, ...): void
{
    if ($newsletter->status !== 'draft') {
        throw new InvalidNewsletterStateException(
            "Newsletter cannot be dispatched from status [{$newsletter->status}]."
        );
    }
    // ...
}

public function cancel(OrganisationNewsletter $newsletter, ...): void
{
    if (! in_array($newsletter->status, ['draft', 'processing'])) {
        throw new InvalidNewsletterStateException(
            "Newsletter cannot be cancelled from status [{$newsletter->status}]."
        );
    }
    // ...
}
```

## What Happens to Recipients by Status

| Campaign status | Recipient rows | Description |
|-----------------|----------------|-------------|
| `draft` | None | Recipients not yet inserted |
| `queued` | All `pending` | Bulk-inserted during dispatch |
| `processing` | Mix of pending / sending / sent / failed | Jobs running |
| `completed` | All `sent` or `failed` | Campaign done |
| `failed` | Many `pending` still unprocessed | Kill switch fired — orphaned pending rows are NOT sent |
| `cancelled` | Possibly mix | Remaining `pending` rows are NOT processed |

## Idempotency Key

On dispatch, a unique `idempotency_key` is generated and stored on the newsletter:

```php
$newsletter->update([
    'idempotency_key' => hash('sha256', $org->id . ':' . $newsletter->id . ':' . now()->timestamp),
]);
```

This key serves as a guard against double-dispatch — if the same newsletter is somehow dispatched twice, the second call would fail the `status !== 'draft'` check before reaching any insertion logic.

Each recipient row also has its own `idempotency_key`:

```php
'idempotency_key' => hash('sha256', $newsletterId . ':' . $memberId)
```

This prevents inserting the same member twice for the same campaign, enforced by a `UNIQUE` index in the database.
