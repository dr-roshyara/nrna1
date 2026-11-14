# PBDIGIT-350: The central outbox relay skips an event written in the same second

**Status:** OPEN (created 2026-11-14) · low priority · not authorised · **Context:** Shared (outbox library) · **Kind:** defect (minor)

## Finding (PBDIGIT-345 task 9b)

`outbox_events.available_at` and `created_at` are `timestamp` (precision 0). PostgreSQL **rounds** their `CURRENT_TIMESTAMP` default to the nearest second, up to 0.5 s **ahead** of the real time.

The central relay (`OutboxEventProcessor::handle`) selects `available_at <= now()` with PHP's `now()`. An event written less than half a second before a relay run therefore looks "not yet available" and is skipped.

**Evidence:**
- a diagnostic in `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task9/t9b-04-debug-iso9/`: stored `available_at` `14:27:26`, database `now()` `14:27:25.75`;
- the central-relay test then failed every time until its row was made explicitly due.

## Business impact

**Small:** the event is delivered on the **next** run (the relay is scheduled every minute). Nothing is lost. Delivery is delayed by up to one minute for events written just before a run.

## Not changed in PBDIGIT-345

T-6 requires the central relay to stay as it is. The organisation relay (`ConnectionOutboxProcessor`) compares with the same rounding (`localtimestamp(0)`) and is not affected.

## Possible fix (when authorised)

Compare with `localtimestamp(0)` in the central relay's query too, or give `available_at` microsecond precision, centrally and in the organisation outbox migration together (parity rule). Test first, with the case "written and relayed within the same second".
