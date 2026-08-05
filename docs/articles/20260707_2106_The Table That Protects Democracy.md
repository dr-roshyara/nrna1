# The Table That Protects Democracy

*How a single database table became the trust foundation for an entire election platform*

---

## The Problem No One Talks About

Every election system eventually faces the same moment. The votes are counted. The results are published. Someone challenges the outcome. And then—nothing. The software has no answer. The challenge leaves the system. Lawyers take over. Committees deliberate. Trust becomes a human problem again.

We built a different system. In our platform, when a challenge is filed, software carries it all the way to constitutional finality. A challenge is raised. A determination is issued. A correction is applied. The challenge is resolved. Every step is recorded. Every decision is auditable. Every event is durably stored.

But there was a problem we didn't see coming. The relay delivers events *at least once*. Networks fail. Processes crash. Databases restart. The same `DeterminationIssued` event can arrive twice. And if it arrives twice, the election correction it triggers could be applied twice. A duplicate event could resolve the same challenge twice. A voter's legitimate contest could be silently corrupted—not by malice, but by infrastructure.

We needed a way to guarantee that every event in the correction loop is processed exactly once. The solution was a single database table.

---

## The Table

It's called `inbox_events`. It has thirteen columns. It lives in Shared Infrastructure, not inside any business domain. And it solves a problem that has quietly haunted event-driven systems since the first message broker went live.

Here's how it works.

When an event arrives for a consuming context—say, the Election context receives a `DeterminationIssued`—the Inbox wrapper claims a row in this table before invoking any business logic. The row is keyed by `(event_id, consumer_context)`. The claim happens inside a database transaction. If two workers race to claim the same event, the database's unique constraint ensures exactly one wins. The loser sees the existing row and returns immediately. The event is processed at most once.

If the event arrives before its cause—`ElectionCorrectionApplied` arriving before `DeterminationIssued` has been processed—the handler throws a specific exception. The Inbox catches it, marks the row `parked`, and sets a retry timer. Five minutes later, the redrive command wakes up and tries again. The event waits patiently until its predecessor is done.

If the handler crashes—database deadlock, network timeout, out of memory—the transaction rolls back. The row insert is undone. The relay redelivers the event cleanly. No partial state. No orphaned corrections. No manual recovery.

If the handler fails permanently—a constitutional guard rejects the event, or a required reference is missing after exhaustive retries—the row is marked `dead`. A structured log entry records the full context: event ID, consumer context, event type, organization ID, error message. An operator is alerted. The dead letter queue preserves the evidence. Nothing is silently dropped.

---

## Why a Separate Table

We could have built deduplication into each bounded context's own schema. Many systems do. The Election context could track which events it has processed in an `election_events` table. The Contestation context could do the same in `contestation_events`.

We didn't. Here's why.

The Inbox is infrastructure. It transports, schedules, retries, and persists messages. It never decides what those messages mean. That separation matters. When deduplication lives inside a business schema, it becomes a business concern. Developers start asking whether a duplicate `DeterminationIssued` should be handled differently than a duplicate `ChallengeRaised`. Business logic leaks into the transport layer.

A separate table, owned by Shared Infrastructure, enforces a hard boundary. The Inbox guarantees exactly-once delivery. Business handlers assume exactly-once delivery. Neither layer needs to know how the other works. Adding a new consuming context—a sixth, a tenth, a twentieth—requires zero schema changes to any existing context. Each new handler registers itself in the Inbox registry and implements one interface. The platform scales without accumulating technical debt.

And critically: the architecture tests can verify this separation in one place. Eleven property-based tests scan the entire codebase. They prove that Shared Infrastructure imports no business context. They prove that exactly one component classifies handler outcomes. They prove that no voter identity token leaks into the transport layer. These tests are not documentation. They are executable governance. They fail the build if anyone violates the boundary.

---

## What This Has to Do With Trust

A voter challenges an election result. Their challenge is recorded as `ChallengeRaised`. It is routed to adjudication as `ChallengeRouted`. A determination is issued as `DeterminationIssued`. A correction is applied as `ElectionCorrectionApplied`. The challenge is resolved as `ChallengeResolved`.

Every one of those events carries a `CorrelationId`—a single identifier, minted when the challenge was raised, copied unchanged into every subsequent event. A single database query traces the complete legal history of that challenge across four bounded contexts and five event types. No event is missing. No event is duplicated. No correction was applied twice.

The Inbox guarantees this. Not through documentation. Not through code review. Through a unique constraint on `(event_id, consumer_context)` and a transaction that claims the row before the handler runs. The guarantee is structural. The database will not permit a duplicate, even if every application server in the cluster tries to deliver one simultaneously.

This is what trust looks like in software. Not a promise. Not a process. A constraint.

The voter cannot see the `inbox_events` table. They cannot read the `CorrelationId`. But they can ask a question: "What happened to my challenge?" And the platform can answer—not with a committee's recollection, not with a lawyer's argument, but with a complete, immutable, deduplicated event chain that begins with their challenge and ends with its resolution. The answer is not an opinion. It is a query.

---

## What We Built

The Inbox is not a feature. It is platform infrastructure. It took six implementation steps, fourteen exit criteria, eleven architecture fitness tests, and an adversarial Architecture Readiness Review that tried to reject it and failed.

It is certified. It is reusable. Every future bounded context—Evidence, Voting, Appointment—will consume it unchanged.

And it started with a single question: what happens if the relay delivers the same event twice?

The answer is a thirteen-column table in Shared Infrastructure, a unique constraint on two columns, and a transaction that claims a row before any business logic runs. That's all. But it's enough.