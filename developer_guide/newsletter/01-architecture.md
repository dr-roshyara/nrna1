# 01 — Architecture

## System Overview

The newsletter system is a two-stage queue pipeline with idempotent delivery:

```
Admin clicks "Send"
       │
       ▼
NewsletterService::dispatch()
  ├── Validates state (must be 'draft')
  ├── Assigns unsubscribe tokens to members
  ├── Bulk-inserts NewsletterRecipient rows (chunk 500)
  ├── Sets status = 'queued', records idempotency_key
  ├── Writes audit log (action = 'dispatched')
  └── Dispatches DispatchNewsletterBatchJob → queue:emails-normal
              │
              ▼
       DispatchNewsletterBatchJob::handle()
         ├── Guards: status must be 'queued'
         ├── Sets status = 'processing'
         ├── Chunks pending recipient IDs (50 per batch)
         └── Dispatches N × SendNewsletterBatchJob → queue:emails-normal
                       │
                       ▼
              SendNewsletterBatchJob::handle()  [runs N times in parallel]
                ├── Guard: cancel/failed? → return early
                ├── Kill switch check → abort campaign if triggered
                ├── For each recipient (under Redis lock):
                │     ├── Double-check status = 'pending' (idempotency)
                │     ├── Mark status = 'sending'
                │     ├── Mail::to()->send(OrganisationNewsletterMail)
                │     ├── Success → status='sent', sent_count++
                │     └── Failure → status='failed', failed_count++
                └── If all recipients done → mark newsletter 'completed'
```

## File Map

```
app/
├── Models/
│   ├── OrganisationNewsletter.php      — campaign model + state helpers
│   ├── NewsletterRecipient.php         — per-member delivery record
│   └── NewsletterAuditLog.php          — append-only audit record
│
├── Services/
│   └── NewsletterService.php           — createDraft, dispatch, cancel, preview
│
├── Jobs/
│   ├── DispatchNewsletterBatchJob.php  — splits campaign into 50-recipient batches
│   └── SendNewsletterBatchJob.php      — sends one batch, handles kill switch
│
├── Mail/
│   └── OrganisationNewsletterMail.php  — mailable with List-Unsubscribe header
│
├── Events/Newsletter/
│   ├── NewsletterEmailSent.php
│   └── NewsletterEmailFailed.php
│
├── Listeners/Newsletter/
│   └── UpdateNewsletterCounters.php    — kill switch side-effect hook
│
├── Http/Controllers/Membership/
│   └── OrganisationNewsletterController.php
│
├── Http/Controllers/
│   └── NewsletterUnsubscribeController.php
│
└── Exceptions/
    └── InvalidNewsletterStateException.php

database/migrations/
├── 2026_04_06_090741_create_organisation_newsletters_table.php
├── 2026_04_06_090742_create_newsletter_recipients_table.php
├── 2026_04_06_090744_create_newsletter_audit_logs_table.php
└── 2026_04_06_090745_add_newsletter_fields_to_members_table.php

resources/js/Pages/
└── Newsletter/
    └── Unsubscribed.vue
```

## Design Decisions

### Why two jobs instead of one?

`DispatchNewsletterBatchJob` is a coordinator with `$tries = 1`. It runs once, splits the recipient list into chunks, and dispatches `SendNewsletterBatchJob` for each chunk. This means:

- The coordinator is lightweight and fast.
- Each 50-recipient batch is an independent, retryable unit.
- A single SMTP failure in batch 3 does not re-send batches 1 and 2.

### Why Redis locks per recipient?

If a batch job fails and retries, it might re-process recipients that were already marked 'sending'. The `Cache::lock("newsletter:recipient:{$id}", 30)` prevents two concurrent workers from sending to the same address in the same campaign.

### Why `sent_count` / `failed_count` incremented in the job, not a listener?

Laravel 11 event auto-discovery causes listeners to be registered twice when they type-hint an event class. To avoid double-increments, counters are updated inline in `SendNewsletterBatchJob::handle()` immediately after each send attempt. The domain events (`NewsletterEmailSent`, `NewsletterEmailFailed`) are still fired for extensibility (future webhooks, notifications).

### Why is `NewsletterAuditLog` append-only?

The audit log is a GDPR compliance record. Its `save()` method returns `false` for updates — you can only `create()` new rows, never modify existing ones. This guarantees an immutable trail of: who dispatched the campaign, when, to how many recipients, and any cancellation or failure events.
