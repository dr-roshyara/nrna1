# 05 — Queue Jobs

Two jobs handle newsletter delivery. They are always on the `emails-normal` queue.

---

## `DispatchNewsletterBatchJob`

**File:** `app/Jobs/DispatchNewsletterBatchJob.php`

```php
public int $timeout = 3600;
public int $tries   = 1;
```

**Purpose:** Coordinator job. Runs once, splits the recipient list into 50-recipient chunks, and dispatches one `SendNewsletterBatchJob` per chunk.

### `handle()` flow

1. Load the newsletter. If status ≠ `queued`, return early (safe re-entry guard).
2. Update status → `processing`.
3. Cursor through `NewsletterRecipient` rows where `status = 'pending'`, chunked at 50:
   ```php
   NewsletterRecipient::where('organisation_newsletter_id', $this->newsletterId)
       ->where('status', 'pending')
       ->select('id')
       ->chunk(50, function ($chunk) use ($newsletterId) {
           SendNewsletterBatchJob::dispatch(
               $newsletterId,
               $chunk->pluck('id')->all()
           )->onQueue('emails-normal');
       });
   ```
4. If zero pending recipients exist → mark newsletter `completed` immediately and write audit log.

### Why `$tries = 1`?

The coordinator is lightweight. If it fails before chunking, no emails have been sent, so retrying with a new job is safe. If it fails *after* dispatching batch jobs, the batch jobs are already in the queue — a retry would dispatch duplicates. `$tries = 1` avoids this. A failed coordinator is surfaced as a failed job in Horizon/Telescope for manual inspection.

---

## `SendNewsletterBatchJob`

**File:** `app/Jobs/SendNewsletterBatchJob.php`

```php
public int $tries   = 3;
public array $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min
```

**Purpose:** Sends emails to one batch of up to 50 recipients. Runs N times in parallel (one job per chunk).

### Constructor

```php
public function __construct(
    private readonly int   $newsletterId,
    private readonly array $recipientIds,
) {}
```

### `handle()` flow

```
1. Load newsletter
2. Early exit guard (cancelled / failed)
3. Kill switch check
4. For each recipient ID in $recipientIds:
   a. Acquire Redis lock (30s TTL)
   b. Re-load recipient, check status = 'pending'
   c. Mark status = 'sending'
   d. Mail::to()->send(OrganisationNewsletterMail)
   e. On success: status='sent', sent_at=now(), increment sent_count, fire NewsletterEmailSent
   f. On failure: status='failed', error_message, increment failed_count, fire NewsletterEmailFailed
   g. Release lock
5. Check if all recipients for newsletter are done → mark 'completed'
```

### Early exit guard

```php
$newsletter = OrganisationNewsletter::find($this->newsletterId);

if (! $newsletter || in_array($newsletter->status, ['cancelled', 'failed'])) {
    return; // Silently discard — campaign was cancelled mid-flight
}
```

### Kill switch check

```php
if ($newsletter->isKillSwitchTriggered()) {
    $newsletter->update(['status' => 'failed']);
    NewsletterAuditLog::create([
        'organisation_newsletter_id' => $newsletter->id,
        'organisation_id'            => $newsletter->organisation_id,
        'actor_user_id'              => $newsletter->created_by,
        'action'                     => 'failed',
        'metadata'                   => [
            'reason'       => 'kill_switch',
            'failure_rate' => $newsletter->failureRate(),
            'sent_count'   => $newsletter->sent_count,
            'failed_count' => $newsletter->failed_count,
        ],
    ]);
    return;
}
```

### Redis lock pattern

```php
$lock = Cache::lock("newsletter:recipient:{$recipientId}", 30);

if (! $lock->get()) {
    continue; // Another worker has this recipient — skip
}

try {
    $recipient = NewsletterRecipient::find($recipientId);

    if (! $recipient || $recipient->status !== 'pending') {
        continue; // Already processed (idempotency double-check)
    }

    $recipient->update(['status' => 'sending']);

    Mail::to($recipient->email)->send(
        new OrganisationNewsletterMail($newsletter, $recipient)
    );

    $recipient->update(['status' => 'sent', 'sent_at' => now()]);
    OrganisationNewsletter::where('id', $newsletter->id)->increment('sent_count');
    event(new NewsletterEmailSent($recipient));

} catch (\Throwable $e) {
    $recipient?->update([
        'status'        => 'failed',
        'error_message' => substr($e->getMessage(), 0, 500),
    ]);
    OrganisationNewsletter::where('id', $newsletter->id)->increment('failed_count');
    event(new NewsletterEmailFailed($recipient, $e->getMessage()));
} finally {
    $lock->release();
}
```

### Completion check

After the per-recipient loop:

```php
$pendingCount = NewsletterRecipient::where('organisation_newsletter_id', $newsletter->id)
    ->whereIn('status', ['pending', 'sending'])
    ->count();

if ($pendingCount === 0) {
    $newsletter->update(['status' => 'completed', 'completed_at' => now()]);
    NewsletterAuditLog::create([...]);
}
```

**Race condition note:** Multiple batch jobs finish near-simultaneously and all see `pendingCount === 0`. The `update()` call is idempotent (setting `completed` when already `completed` is harmless), so no locking is needed here.

---

## Queue Configuration

```bash
# Run both jobs from the same queue worker
php artisan queue:work --queue=emails-normal

# With Horizon (recommended for production)
# horizon.php → environments → production → supervisor → queue: ['emails-normal']
```

Both jobs share `emails-normal`. The coordinator runs first and is fast; batch jobs fan out across all available workers.

---

## Rate Throttling (per org)

Inside `SendNewsletterBatchJob::handle()`, before sending:

```php
Redis::throttle("newsletter:send:{$newsletter->organisation_id}")
    ->allow(10)
    ->every(1)
    ->then(
        fn () => $this->sendEmail($newsletter, $recipient),
        fn () => $this->release(2) // Re-queue this job in 2 seconds
    );
```

This limits delivery to 10 emails/second per organisation, preventing SMTP rate-limit rejections.
