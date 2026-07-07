We are building the **messaging infrastructure for the constitutional correction loop**.

---

## What That Means

The NRNA platform has an existing election system. It can register voters, conduct voting, count ballots, and publish results. What it cannot do is **constitutionally close an election** — handle challenges, adjudicate disputes, apply corrections, and reach binding finality.

That missing capability is the **Greenfield Core**. It consists of two bounded contexts:

- **Contestation** — legal challenges (raise, admit, route, resolve)
- **Adjudication** — binding legal determinations (issue, finalize)

These two contexts must communicate through events — not direct calls, not shared databases. Contestation publishes events. Adjudication consumes them and publishes its own. Election reacts to determinations. Contestation reacts to corrections. The chain is:

```text
Challenge → Determination → Correction → Resolution
```

---

## What We Are Building Right Now

For this event-driven chain to work reliably, every consuming context needs a way to receive events **exactly once**, even when the relay delivers them multiple times. Events can arrive out of order. Handlers can crash. The network can fail.

We are building the **Inbox** — the shared infrastructure component that provides:

- **Deduplication** — the same event processed at most once per consumer
- **Ordering tolerance** — out-of-order events are parked and retried
- **Failure recovery** — crashed handlers retry cleanly; permanent failures dead-letter
- **Operational recovery** — a redrive command re-attempts parked events

This is not business logic. It is platform infrastructure. Every bounded context — Contestation, Election, and any future context — will use this same Inbox to consume events reliably.

---

## Current State

The Inbox subsystem is built and tested. We are now in **C6 — Architecture Certification** — verifying that this subsystem is not merely functional but is a properly governed, reusable platform capability before any business code (PB-004, the Election reaction) is allowed to depend on it.