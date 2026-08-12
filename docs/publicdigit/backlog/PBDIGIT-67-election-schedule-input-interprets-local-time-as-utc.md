# PBDIGIT-67 — The election schedule input interprets browser-local time as UTC

**Type:** Defect (data, not display) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-12
**Found by:** IERVP runtime verification (Session 2) — **an independent programme from `PBDIGIT-48`**
**Evidence:** [`../reviews/2026-08-12-time-value-trace-browser-to-display.md`](../reviews/2026-08-12-time-value-trace-browser-to-display.md) — full hop-by-hop trace, measured
**Status:** `OPEN — NOT AUTHORISED FOR IMPLEMENTATION.` **Business decisions below must be answered first.**

| | |
|---|---|
| **Customer impact** | 🔴 **An election opens 1–2 hours later than the officer scheduled it.** A voting window set for 13:10–14:29 local silently opens at 15:10–16:29 local. **Voters arriving in the advertised window find voting closed** |
| **Severity** | **High** — it affects the one field that decides when a real election runs, and it is **currently live** (below) |
| **Confidence** | **High** — measured by executing the parse, not inferred |

---

## ⚠️ This corrects `PBDIGIT-50`'s central premise

`PBDIGIT-50` states:

> *"So times are stored in UTC and the election records the timezone it was authored in. **That is the correct foundation** — this is a **display** gap, **not a data defect**."*

**Both halves of that conclusion are disproven by measurement.**

* The stored value is **labelled** UTC but **means** browser-local — the offset is discarded at the input boundary and never reapplied.
* **It is a data defect.** Display conversion alone would render the *wrong instant* correctly.

**`PBDIGIT-50`'s display decision remains valid and is not reopened** (device/browser detection via `Intl.DateTimeFormat().resolvedOptions().timeZone`; `users.country` explicitly not the source; election timezone as visible fallback). **Only its "not a data defect" classification is corrected here.**

---

## 🔴 Live instance — not hypothetical

`test election 1`, created **2026-08-12 10:29 UTC**, with **`elections.timezone = Europe/Berlin` explicitly set**:

```
stored  voting_starts_at   2026-08-12 13:10 UTC   ->  opens 15:10 Berlin
        voting_ends_at     2026-08-12 14:29 UTC   ->  closes 16:29 Berlin
if the officer meant Berlin wall-clock, they intended 11:10 UTC
```

**The 79-minute window the officer scheduled will have fully elapsed before the system opens voting.**

**Corroboration that local was intended** (`INTERPRETATION`, strongly supported, not proven): the row carries an **explicitly set** `Europe/Berlin` (the column defaults to `NULL`, so a human chose it), and the election was created at **12:29 Berlin** — making a 13:10 start a natural "in 41 minutes" choice, and a 13:10 **UTC** start an unlikely "in 2h41m" one.

---

## The defect, precisely

**One hop.** The browser knows the offset; it is discarded before transmission and re-assumed as UTC on arrival.

```
Browser (Berlin, +02:00)      user enters 10:00
   |
   v  <input type="datetime-local">          -> "2026-08-20T10:00"   OFFSET DISCARDED
HTTP payload                                    naive string
   |
   v  validation                                date_format:Y-m-d\TH:i  /  date|after:now
   |                                            neither requires nor accepts an offset
   v  Carbon::createFromFormat(...) with no tz  -> 2026-08-20T10:00+00:00   RE-ASSUMED UTC
   |                                            (user meant 08:00Z)
   v  stored                                    2026-08-20 10:00   (timestamp without time zone)
   |
   v  ElectionClockService::isVotingOpen()      compares UTC now() against it
```

### Measured

| Submitted | Parsed as | Berlin user meant | Drift |
|---|---|---|---|
| `2026-01-15T10:00` | `10:00Z` | `09:00Z` | **60 min** |
| `2026-08-20T10:00` | `10:00Z` | `08:00Z` | **120 min** |

> **The drift is DST-variable.** An officer who learns "add two hours" in August is wrong in January. **No mental compensation is reliable.**

### Call sites
* `ElectionManagementController.php:173` — create path, `Carbon::createFromFormat('Y-m-d\TH:i', …)` **without timezone**
* `ElectionManagementController.php:1231` — edit path, raw string to Eloquent → `Carbon::parse` **without timezone**
* `ElectionTimelineSettings.vue:98,109` — `type="datetime-local"`, no offset captured

---

## Why it survived review — the edit form hides it

**The edit form is round-trip stable and therefore self-confirming.** `ElectionTimelineSettings.vue:239-253` (`formatDateForInput`) is **pure string surgery** — `substring(0,16)`, no `Date`, no `Intl`, no offset arithmetic.

**Write `10:00` → read back `10:00`. It looks correct.**

Meanwhile **ten other files** render the same value with `new Date(raw).toLocaleString(...)` and **no `timeZone:` pinning**, converting correctly to the browser zone. For a stored `14:56Z` a Berlin user sees:

| Where | Shows |
|---|---|
| Settings / edit form | **14:56** |
| `Show.vue`, `ElectionCard.vue`, `TimelineView.vue`, +7 | **16:56** |

**The same value, two answers, same application.**

> ⚠️ **This is a second, distinct defect and it is NOT yet raised as a ticket.** Whether it belongs here or in its own story is a **Product Owner decision** — it has a different cause (rendering) and a different fix from the input hop. **Recorded here so it is not lost; deliberately not created.**

---

## 🟡 Business decisions required — engineering must not settle these

| # | Question | Why engineering cannot decide it |
|---|---|---|
| **D-1** | **What does a schedule timestamp entered by an officer mean?** browser-local · election timezone · organisation timezone · something else | `PBDIGIT-50` decided this **for display**. **It has never been decided for input.** The two need not be the same rule, and saying so is a product statement |
| **D-2** | **Which column is authoritative?** `elections.timezone` (**2/10 populated, default `NULL` — set deliberately**) or `organisations.timezone` (**6/6 populated, but the column default is literally `'UTC'` — a default, not a decision**) | **Two competing concepts exist and neither is read to interpret anything.** Choosing makes one authoritative |
| **D-3** | **What do existing stored values mean?** | **The rows contradict themselves** — see below. **This decides whether a migration is required and which direction it runs** |
| **D-4** | **Should `elections.timezone` become behavioural, or be retired?** | Today it is presence-checked once (`ConstitutionalTransitionGuard:194`, `timezone_set`) and **never used to convert**. `PBDIGIT-64` established that the guard requiring it never runs on the live path |

### D-3 in detail — the rows contradict themselves

| Election | `timezone` | `voting_starts_at` | Implied meaning |
|---|---|---|---|
| `namaste 2026` | **`Europe/Berlin`** (explicit) | `14:56` | stored as **UTC**; `PBDIGIT-47` read it as UTC (*"→ 18:00 Berlin"*) |
| `test election 1` | **`Europe/Berlin`** (explicit) | `13:10` | stored as **UTC**; creator almost certainly meant **Berlin** |
| 5 IERVP test elections | `NULL` | various | set deliberately in UTC by this programme — **known-UTC** |

> **On the two real elections, `elections.timezone` asserts Berlin intent while the timestamp beside it was interpreted as UTC. The contradiction is inside a single row.**

**⛔ Do not migrate anything until D-3 is answered.** A blanket shift would corrupt the five known-UTC test rows and any genuinely-UTC production value.

---

## Candidate solutions — options, not a recommendation

**Recorded so the decision has alternatives. None is endorsed; each implies a different domain model.**

| Option | Mechanism | Implies |
|---|---|---|
| **A · Send the offset** | client submits ISO-8601 **with offset** (or a companion IANA zone from `Intl.DateTimeFormat().resolvedOptions().timeZone`); server parses the instant | browser-local is authoritative — **consistent with `PBDIGIT-50`'s display decision**. Smallest change; **makes the browser a domain input** |
| **B · Interpret against `elections.timezone`** | keep naive string; parse with the election's zone | the **election** owns its schedule. Requires the column to be **mandatory and populated** — today 2/10, and the guard requiring it never runs (`PBDIGIT-64`) |
| **C · Interpret against `organisations.timezone`** | parse with the organisation's zone | the **organisation** owns time. **Currently unusable as evidence** — all 6 values are the schema default `'UTC'` |
| **D · Keep UTC, label the field** | change the UI to state "UTC" explicitly and convert nowhere | the product declares itself UTC-native. **Cheapest, and contradicts `PBDIGIT-50`'s accepted display decision** |

**Cross-cutting, whichever is chosen:** the **fallback when detection fails must be decided and visible** (`PBDIGIT-50` already requires this), and **A and B disagree for a voter/officer travelling** — an officer in Munich scheduling a Kathmandu election is exactly where they diverge.

---

## Acceptance criteria — business behaviour

* [ ] **An officer who schedules a voting window sees it open at the wall-clock time they entered**, in the timezone the product has declared authoritative.
* [ ] **The same instant renders identically everywhere in the application** — edit form and display views agree. *(Depends on the unraised render-divergence defect above.)*
* [ ] **The authoritative timezone is recorded on the election and is used to interpret, not merely present.**
* [ ] **Existing schedules are either provably unaffected or migrated under a decided rule** — with the five IERVP test elections excluded or disposed of first.
* [ ] **A regression test encodes a non-UTC submitter across a DST boundary** — a UTC-only test cannot reproduce this, and a summer-only test misses the 60-minute case.

## Explicit non-goals

* **Not** reopening `PBDIGIT-50`'s display decision — only its *"not a data defect"* classification is corrected.
* **Not** choosing between options A–D.
* **Not** migrating data.
* **Not** fixing the edit/display divergence — **recorded above, deliberately not raised as a ticket** (a Product Owner call on scope).
* **Not** auditing raw SQL `now()` usage (the DB session is `Europe/Berlin` while the app is UTC — **a separate, unaudited hazard**).

## Related

* **[Trace review](../reviews/2026-08-12-time-value-trace-browser-to-display.md)** — the hop-by-hop evidence, with calibration on what was measured vs. read.
* **`PBDIGIT-50`** — display conversion. **Its decision stands; its "display gap, not a data defect" classification is corrected here.**
* **`PBDIGIT-47`** — recorded the symptom (*"14:56 → 16:00 UTC (18:00 Berlin)"*) without naming this cause.
* **`PBDIGIT-64`** — why `timezone_set` never gates anything on the live path.
* **`IERVP-4`** — the Product Owner requirement that election-period timezone derive from the browser.

---

**Traceability:** `app/Http/Controllers/Election/ElectionManagementController.php:135,173,1226,1231` · `resources/js/Pages/Election/Partials/ElectionTimelineSettings.vue:98,109,239-253` · `app/Services/ElectionClockService.php:47-55` · `app/Application/Election/Services/ConstitutionalTransitionGuard.php:194` · `config/app.php:94` · measured 2026-08-12: `app tz UTC`, `db session Europe/Berlin`, all election time columns `timestamp without time zone`.
