# WP-6 — The adjudication horizon, and announcing a failure to conclude

**Step:** WP-6 (re-scoped: horizon + expiry announcement) · **Status:** GREEN, awaiting slice acceptance

WP-2 built the horizon's *mechanism* and deliberately left out its *duration* — its own docblock says *"timer execution + the configured MAD land in WP-6."* This step supplies the duration and the trigger, and adds the one fact the horizon must tell another context.

---

## The rule that governs everything here

> **A timer must never conclude anything.** (Constitutional Policy 4)

Expiry is a **distinct terminal fact**, not a verdict. An expired process carries no outcome, no legitimacy, no reason and no concluding authority — and no determination is issued. There is deliberately **no code path** from the clock to a conclusion, and a test asserts that absence rather than trusting it.

## Where it fits

```
schedule ──► AdjudicationProcessManager::enforceHorizon()
                   │  cut-off = now − MAD          ← Q-2 owns MAD; the APM only subtracts it
                   ├─► store->dueForHorizon(cut-off)      (unchanged since WP-2)
                   ├─► state->expire(now)                 → status = Expired
                   └─► outbox: AdjudicationExpired v1     → Contestation (WP-6B consumes)
                         EventProvenance::start(…)        ← BEGINS a new conversation
```

## Key files

| File | Role |
|---|---|
| `config/adjudication.php` | **INTERIM** temporal parameters, each naming the ARB decision it awaits |
| `Application/Port/AdjudicationDurations.php` | The seam that keeps *Q-2 owns the duration* visible in code |
| `Infrastructure/Config/ConfiguredAdjudicationDurations.php` | Resolves organisation → election type → default |
| `Application/Process/AdjudicationProcessManager.php` | MAD-aware `enforceHorizon()`; the late-decision guard |
| `Application/Process/Exception/LateDecisionOnExpiredAdjudication.php` | §197's conflict — a `PermanentInboxFailure` |
| `Domain/Events/AdjudicationExpired.php` | The announced fact (published language) |
| `Infrastructure/Outbox/AdjudicationExpiredHydrator.php` | Registration — the other half of published-language status |

## Why the durations live behind a port

Q-2 owns the **duration**; the APM is its **enforcer** (EPIC-004K §41/§81). A constant inside the manager would erase that distinction on the first read. The port makes it structural: the manager *asks* for a duration and can never *decide* one.

```php
$cutOff = $now->sub($this->durations->maximumAdjudicationDuration());
```

**All configured values are INTERIM implementation bootstraps, not constitutional defaults.** Each carries the ARB decision it awaits, and replacing one is a configuration change — never a code change.

## The bug this step fixed (F-1)

`dueForHorizon($asOf)` selects processes `opened_at <= $asOf`. WP-2's caller passed **`now`**:

```php
foreach ($this->store->dueForHorizon($now) as $process) {   // ← every process was "due"
```

So the first real invocation would have expired **everything**, including a process opened a second earlier. It was inert only because nothing called it yet. The fix is one line — `now − MAD` — and the keystone that pins it is deliberately stated in business terms: *a process opened one minute ago does not expire under a 60-day MAD.*

## Late decision ≠ redelivered decision (F-2)

Before WP-6 these shared one early-return branch, so a **late ruling was silently swallowed**:

| Situation | §197 / ADR-T3 | Behaviour now |
|---|---|---|
| The **same** decision redelivered on a concluded process | idempotent no-op (at-least-once delivery) | still a no-op |
| A **late** decision on an **Expired** process | *"dead-letters as a conflict"* | `LateDecisionOnExpiredAdjudication` |

`activeForChallenge()` returns null for **both**, which is why the store gained `latestForChallenge()` — the conduct genuinely needs to ask *"what happened to this challenge?"*, not only *"is one running?"*. The exception implements `PermanentInboxFailure`: retrying cannot help, because **the horizon will never un-elapse**.

## Why the announcement begins a new conversation

A clock consumes no message. There is no incoming correlation, so `fromConsumed()` has nothing to derive from — and by the invariant, **an origin begins a conversation; it never transfers ownership of one.** So `enforceHorizon()` mints:

```php
EventProvenance::start($this->identities->next())   // correlation minted, causation null
```

The manager is therefore an allowlisted chain origin (ADR-MP-06, ARB-approved). The allowlist now names three entries because **three distinct conversations exist** — the correction loop, the authority decision, and the failure-to-conclude. **The allowlist's size is an artifact of how many conversations exist; it is never itself the rule.**

## Testing

`tests/Feature/Contexts/Adjudication/AdjudicationHorizonTest.php` — 10 keystones, 22 assertions. Three assert **absences**, which is the point:

| Keystone | Proves |
|---|---|
| MAD resolves from configuration | the duration is not hardcoded |
| A process **inside** the horizon does not expire | F-1 |
| A process **beyond** it expires to `Expired` | PM-8 |
| **Expiry concludes nothing** | **Policy 4** — no outcome, legitimacy, reason, authority or determination |
| Announces exactly one fact, payload v1 | §197 |
| The announcement is **registered** | published language needs both halves |
| Newly minted correlation, **null** causation | chain start |
| A second timer run announces nothing further | expiry is terminal |
| A **late** decision is a conflict | F-2 · §197 |
| A **redelivered** decision stays a no-op | ADR-T3 — guards against over-correcting the row above |

**Gates:** PHPStan max — no errors · Deptrac — **0 violations** · Architecture suite **146 green** · Adjudication + Contestation + Election + Shared feature suites **91 tests / 260 assertions green**.

## Pitfalls

- **Never pass `now` as the horizon cut-off.** That is F-1, and it silently expires everything.
- **Never let a timer produce an outcome.** Policy 4 is constitutional, and the keystone asserting the absence exists because this is an easy mistake to make while "improving" the expiry path.
- **Don't collapse late and redelivered decisions.** They differ in business meaning, not in mechanism.
- **`DateInterval::$days` is `false`** for a constructed interval — the day component is `->d`. (Cost one RED iteration.)
- **A challenge ref reaches `outbox_events.aggregate_id`, a UUID column.** Non-UUID test fixtures fail at insert, not at assertion. (Cost another.)
- **`AdjudicationExpired` is not a `Determination` event.** It is a fact about the **process**, which EPIC-004K §11 states is not an aggregate — which is why the AT-EVT-001 ownership map now admits the context's own name as a prefix. **That widening is flagged for ARB review.**

## Not in this slice

**Evidence-demand deadlines** (§80/PM-3) and the **finality evaluator** (§142) are excluded: the first has no `Demand` concept modelled at all, the second requires knowing whether a challenge is open against a determination — Contestation's state. Both await their own authority; see the plan's classification. **The Contestation consumer of `AdjudicationExpired` is WP-6B** (ARB Decision C).

---

**Traceability:** roadmap §WP-6 · Q-2 (§187 parameters, INTERIM) · **Constitutional Policy 4** · EPIC-004K §41/§57/§72/§74/§80/§81/§142/**§197** · ADR-T3 (at-least-once) · ADR-T5 (version window) · ADR-MP-06 (one origin per conversation) · ADR-T8 (choreography, no compensation) · ARB Decisions A/B/C (2026-07-31) · plan `.claude/plans/WP-6-temporal-machinery.md`.
