# 06 — Kill Switch

The kill switch automatically cancels a campaign when the delivery failure rate becomes unacceptable.

---

## Threshold

```
Trigger when:
  (sent_count + failed_count) >= 50    ← minimum sample size
  AND
  failed_count / (sent_count + failed_count) > 0.20   ← >20% failure rate
```

The minimum sample of 50 prevents premature cancellation during the first few batches when a single transient error would produce a misleadingly high rate (e.g., 1 fail / 2 total = 50%).

---

## Model Method

```php
// app/Models/OrganisationNewsletter.php

public function failureRate(): float
{
    $total = $this->sent_count + $this->failed_count;
    return $total > 0 ? $this->failed_count / $total : 0.0;
}

public function isKillSwitchTriggered(): bool
{
    $total = $this->sent_count + $this->failed_count;
    return $total >= 50 && $this->failureRate() > 0.20;
}
```

---

## Where It Is Checked

**`SendNewsletterBatchJob::handle()`** — checked at the **start of every batch**:

```php
$newsletter->refresh(); // Always re-load fresh counts from DB

if ($newsletter->isKillSwitchTriggered()) {
    // Set status, write audit log, return early
}
```

`refresh()` is critical — without it, the job would check stale in-memory values that don't reflect other workers' progress.

---

## What Happens When It Triggers

1. Newsletter status → `failed`
2. Audit log entry: `action = 'failed'`, metadata contains:
   ```json
   {
     "reason": "kill_switch",
     "failure_rate": 0.23,
     "sent_count": 52,
     "failed_count": 16
   }
   ```
3. All currently running batch jobs detect `status = 'failed'` on their next early exit guard and return without sending further emails.
4. Any remaining `pending` recipient rows are **not processed** — they remain as `pending` permanently (intentional: they document what was not sent).

---

## Why Recipients Aren't Reverted

Recipients already marked `sent` cannot be un-sent. Reverting them in the database would create a false audit trail. The `pending` rows that were never processed document exactly who did not receive the email, which is the correct record for compliance purposes.

---

## Adjusting the Threshold

The threshold is hardcoded in `OrganisationNewsletter::isKillSwitchTriggered()`. To change it:

```php
// Example: trigger at >10% after ≥100 sends
public function isKillSwitchTriggered(): bool
{
    $total = $this->sent_count + $this->failed_count;
    return $total >= 100 && $this->failureRate() > 0.10;
}
```

The test in `NewsletterKillSwitchTest` must be updated to match any threshold changes.

---

## Manual Kill (Admin Cancel)

An admin can also cancel a processing campaign via `NewsletterService::cancel()`. This is distinct from the automatic kill switch:

| | Automatic Kill Switch | Manual Cancel |
|-|-----------------------|---------------|
| Trigger | Failure rate threshold | Admin action |
| Final status | `failed` | `cancelled` |
| Audit action | `'failed'` | `'cancelled'` |
| Who | System (job) | Human (controller) |

Both result in remaining batch jobs returning early without sending.
