# Time-value trace — browser to display

**Type:** Read-only trace of one value through the full stack · **Date:** 2026-08-12
**Trigger:** the Product Owner's pipeline diagram (browser → Vue → payload → validation → Carbon → domain → DB → response → display)
**Relates to:** `IERVP-4` (election-period timezone must derive from the **browser's** country) · `PBDIGIT-50` (times not converted to viewer timezone) · `PBDIGIT-47` (window recorded as *"14:56 → 16:00 UTC (**18:00 Berlin**)"*)
**Mode:** evidence only — **no code, tests, fixtures, configuration or data changed.**

---

## Result in one line

> **The stack has three different timezone conventions, and the one place a user sets a time is the one place that gets it wrong — while the form they set it in hides the error by round-tripping the same wrong value back.**

---

## The trace, hop by hop

| # | Hop | What happens to `10:00` | Class |
|---|---|---|---|
| 1 | **Browser** | user types `10:00`, meaning **their local wall clock** | — |
| 2 | **Vue form model** | `<input type="datetime-local">` (`ElectionTimelineSettings.vue:98`) yields **`2026-08-20T10:00`** — **naive, no offset. The browser's zone is used to render and is never transmitted.** | `OBSERVED IN CODE` |
| 3 | **HTTP payload** | the same naive string | `OBSERVED IN CODE` |
| 4 | **Validation** | create: `date_format:Y-m-d\TH:i` (`:135`) · edit: `required\|date\|after:now` (`:1226`). **Neither requires nor accepts an offset.** `after:now` compares against **UTC** `now()` | `OBSERVED IN CODE` |
| 5 | **Carbon parsing** | create: `Carbon::createFromFormat('Y-m-d\TH:i', …)` (`:173`) · edit: raw string handed to Eloquent → `Carbon::parse` (`:1231`). **Neither passes a timezone → both default to `app.timezone` = `UTC`** | **MEASURED** |
| 6 | **Domain** | `ElectionClockService::isVotingOpen()` (`:47-55`) compares `now()` (UTC) against the stored value | `OBSERVED IN CODE` |
| 7 | **Database** | all election time columns are **`timestamp without time zone`** → Postgres stores the literal wall clock; **meaning is a convention held only by the application** | **MEASURED** |
| 8 | **Response** | model cast `'datetime'` → Carbon `tz=UTC` → JSON **`"2026-08-06T14:56:00.000000Z"`** — **correctly labelled UTC** | **MEASURED** |
| 9 | **Display** | **two paths, opposite behaviour** — see below | `OBSERVED IN CODE` |

### Measured baseline
```
app.timezone = UTC     php_tz = UTC     carbon now = ...+00:00
database session timezone = Europe/Berlin      <-- differs from the app
election time columns    = timestamp without time zone
```
**The DB session offset does not corrupt these columns** (they carry no zone), **but it is a live hazard for any raw SQL using `now()`/`CURRENT_TIMESTAMP`,** which would produce Berlin local time against a UTC-convention column. **Not audited here.**

---

## Finding 1 — the write path silently shifts the value · **MEASURED**

```
submitted string        2026-08-20T10:00        (naive)
store()      parse  ->  2026-08-20T10:00+00:00  tz=UTC
updateVotingDates() ->  2026-08-20T10:00+00:00  tz=UTC
what a Berlin user MEANT 2026-08-20T08:00+00:00 (UTC)
DRIFT                    120 minutes
```

**The drift is not constant — it is DST-dependent:**

| Submitted | Drift |
|---|---|
| `2026-01-15T10:00` (CET) | **60 min** |
| `2026-08-20T10:00` (CEST) | **120 min** |

> **A variable error cannot be mentally compensated for.** A chief who learns "add two hours" in August is wrong in January.

**Consequence:** an election window set for 10:00 local **opens 1–2 hours late**, because `isVotingOpen()` compares real UTC time against a value that was labelled UTC but meant local.

---

## Finding 2 — two display paths disagree with each other · 🔑 **the reason this is invisible**

| Path | Mechanism | Berlin user sees, for stored `14:56Z` |
|---|---|---|
| **Edit form** — `ElectionTimelineSettings.vue:239-253` | **pure string surgery**: `substring(0,16)`, or split on `' '`. **No `Date`, no `Intl`, no offset arithmetic** | **`14:56`** ❌ UTC shown as if local |
| **Display views** — `Show.vue:359`, `ElectionCard.vue:110`, `TimelineView.vue:167`, `VoterVerificationModal.vue:226`, +6 more | `new Date(raw).toLocaleString(...)` — **converts to the browser's zone**; **no `timeZone:` pinning anywhere** | **`16:56`** ✅ correctly converted |

**The same stored value renders as two different times in the same application.**

**And this is exactly why the defect survives review:** the edit form is **round-trip stable** — write `10:00`, read back `10:00`. **The person who sets the time never sees the discrepancy in the form they used.** It only appears when the value meets either (a) a display view, or (b) the real clock.

> **`PBDIGIT-47` already recorded the symptom** — *"14:56 → 16:00 UTC (18:00 Berlin)"* — without naming this cause.

---

## Finding 3 — `elections.timezone` is required, and never read · **MEASURED**

The column exists. Repository-wide, **exactly one production consumer**:

```php
// ConstitutionalTransitionGuard.php:194
'timezone_set' => !empty($election->timezone),
```

**A presence check. Nothing anywhere converts using its value.**

**And the guard that requires it never runs on the live path** — `PBDIGIT-64` established that lifecycle state is computed from the clock, so `open_voting` (whose preconditions include `timezone_set`) is bypassed entirely. `IERVP-4` recorded `elections.timezone` as **`NULL` throughout** the runtime verification, on an election that nevertheless reached `voting_active`.

> **The system asks for a timezone, gates a transition on its presence, never executes that gate, and never uses the value.**

---

## What this means for `IERVP-4`

The Product Owner's requirement — *election-period timezone must derive from the browser's country, not the user's or the chief's* — is:

* ✅ **already satisfied on the display side** (10 files, browser-zone conversion, no pinning);
* ❌ **violated on the input side**, where the browser's zone is discarded at hop 2 and re-assumed as UTC at hop 5;
* ⚠️ **unrepresented in the domain** — `elections.timezone` is presence-checked, never applied.

**The gap is one hop wide.** The offset is available in the browser and thrown away before transmission.

---

## Calibration — what is and is not established

| Claim | Status |
|---|---|
| App/PHP UTC; DB session `Europe/Berlin`; columns `timestamp without time zone` | **MEASURED** |
| Both write paths parse naive strings as UTC; drift 60/120 min | **MEASURED** (Carbon executed directly) |
| Cast → Carbon UTC → JSON `…Z` | **MEASURED** |
| Edit form does no conversion; 10 display files do convert; no `timeZone:` pinning | **OBSERVED IN CODE** (grep + read) |
| `elections.timezone` has one presence-only consumer | **MEASURED** (repo-wide grep) |
| **What a real browser actually renders** | **NOT VERIFIED** — no browser was run. `datetime-local` and `toLocaleString` semantics are standard-specified, but **not observed in this application** |
| Whether any raw SQL uses `now()` against these columns | **NOT AUDITED** |

---

## Decisions this raises — not engineering's to make

1. **Which layer owns the timezone?** The browser's offset (per `IERVP-4`), the `elections.timezone` column, or the organisation? **Three candidates exist; one is required-but-unused.**
2. **Should stored values remain UTC?** They are internally consistent today; the defect is at the boundary, not the storage.
3. **Is the edit form or the display view the correct rendering?** They disagree. **One of them must change, and the choice determines whether existing stored values need migration.**

> **No repair is proposed here.** Fixing the input hop without deciding (1) would make the edit form agree with the display views while leaving the domain with no owned timezone concept — trading a visible defect for an unowned one.

**Boundary:** code changed **none** · tests **none** · data **none** · browser verification **not performed**.
