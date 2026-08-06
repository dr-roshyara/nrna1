# PBDIGIT-38 — A vote cannot be saved: a security-audit write blocks the business operation

**Type:** Defect (blocking) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` part (b) — the first end-to-end journey ever walked in this repository

| | |
|---|---|
| **Status** | **OPEN — not authorised.** Found by verification; the repair is a separate decision |
| **Customer impact** | 🔴 **A voter completes every step and their vote is silently discarded.** The UI returns them to the verification page with no explanation |
| **Severity** | **Blocking.** This is the defect that stops the product working |

---

## What was observed

Steps 1–4 of the demo journey all succeed (`code entry → agreement → vote form → verify`). Then:

```
POST /v/{slug}/demo-vote/final   ->  302 back to /demo-vote/verify
demo_votes   = 0
demo_results = 0
```

**No vote row. No result row. No error shown to the voter.**

## Root cause — a NOT NULL column nobody writes

```
SQLSTATE[23502]: Not null violation:
  null value in column "overlay_influence_chain" of relation "election_security_events"
  violates not-null constraint
     ↓
SQLSTATE[25P02]: In failed sql transaction: current transaction is aborted
     ↓
❌ EXCEPTION in store() - Vote submission failed
```

| Fact | Evidence |
|---|---|
| The column is `NOT NULL` with **no default** | `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23` — `$table->json('overlay_influence_chain');` (Laravel's `json()` without `->nullable()`) |
| Every **sibling** overlay column *is* nullable | `overlay_applied`, `overlay_signal_type` — `is_nullable = YES` |
| The insert never supplies it | `app/Application/Election/Security/SecurityEventRecorder.php:52` — the `create([...])` array has no `overlay_influence_chain` key |
| It is in `$fillable` and cast to `array` | `app/Models/ElectionSecurityEvent.php:29,44` — so the intent was clearly to write it |

**So the model declares the field, the schema demands it, and the recorder forgets it.**

## Why the failure is worse than the bug

**The security event is recorded inside the vote's transaction.** So an *audit* write failure aborts the *business* operation:

> **A mechanism that exists to observe voting prevents voting.**

That is an architectural point, not a typo: an observation path must not be able to fail the path it observes. **Whether the recorder should participate in the vote transaction at all is the real design question** — and it is a bigger question than the missing column.

## ⚠️ Scope is probably wider than demo — NOT VERIFIED

`SecurityEventRecorder` is **not** demo-specific. Its caller is `app/Application/Election/Security/TrustPolicyEvaluator.php`, on the shared trust-evaluation path.

**So real elections are likely affected identically.** **This was NOT tested** — no real-election vote was attempted, and it must not be assumed either way. **Establishing it is the first task of this story**, because it decides whether this is "demo mode is broken" or "the product cannot hold an election."

## Acceptance criteria

* [ ] Determine whether the real voting path hits the same failure. **Answer recorded either way.**
* [ ] A vote is persisted end to end: `demo_votes` gains a row, `demo_results` gains rows.
* [ ] **The anonymity invariant is asserted on the saved vote** — no `user_id`, no voter linkage (ADR-T11). *`PBDIGIT-00` could not check this, because no vote row was ever created.*
* [ ] Decide, and record, whether security-event recording belongs inside the vote transaction. **If it stays inside, a recorder failure must still not lose the vote; if it moves outside, the audit gap must be acknowledged.**
* [ ] A regression test that casts a vote and fails if `demo_votes` stays empty — **the test `PBDIGIT-36` says does not exist**.
* [ ] The voter is never returned to a page with no explanation: a failed save must surface as an error, not a silent redirect.

## Explicitly NOT the fix

**Do not simply make the column nullable and move on.** That silences the symptom and leaves two questions unanswered: what `overlay_influence_chain` is *for*, and whether an audit write should be able to fail a vote. If the column is genuinely optional, making it nullable is correct — **but that must be a decision, not a reflex.**

---

**Traceability:** `PBDIGIT-00` §Result (b) · `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23` · `app/Application/Election/Security/SecurityEventRecorder.php:52` · `app/Application/Election/Security/TrustPolicyEvaluator.php` · `app/Models/ElectionSecurityEvent.php:29,44` · `app/Http/Controllers/Demo/DemoVoteController.php` `store()` · `PBDIGIT-36` (the missing regression test) · ADR-T11 (anonymity)
