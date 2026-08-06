# PBDIGIT-50 — Election times are not converted to the viewer's timezone

**Type:** Product gap · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Raised by:** Product Owner — *"the software should be able to convert the time of the display to the time of the user's country and display accordingly"*

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **A voter in another country cannot tell when voting closes.** For a diaspora product — the platform's stated use case — that is a missed vote, not a cosmetic issue |

---

## ✅ What is already right — the storage is not the problem

| Fact | Value |
|---|---|
| `config('app.timezone')` | `UTC` |
| `elections.timezone` | **`Europe/Berlin`** — the election declares its own timezone |
| `namaste 2026` `end_date` (stored) | `2026-08-06 16:00:00` **UTC** |
| the same instant in `Europe/Berlin` | **`18:00`** — matches the Product Owner's own statement |

**So times are stored in UTC and the election records the timezone it was authored in. That is the correct foundation** — this is a **display** gap, not a data defect.

## The gap

1. **Nothing converts for the viewer.** `elections.timezone` exists and is populated; **the UI does not apply it, and applying it would still show Berlin time to a voter in Nepal.**
2. 🔴 **There is no viewer timezone to convert to.** `users` has a `country` column — **`NULL` for the reported user** — and **no `timezone` column at all**. So even a correct converter has no target.

   ✅ **And per the Product Owner, `country` is the wrong target anyway** — see the mechanism decision below.
3. **Every timestamp column is `timestamp without time zone`.** Correct if and only if everything writes UTC. **Nothing enforces that**, and a single naive `Carbon::parse()` of a local string would store a wrong instant silently.

## The product question that must be answered first

> **Which timezone should a voter see?**

| Option | Consequence |
|---|---|
| **The election's timezone** (`Europe/Berlin`) | one canonical answer for everyone; unambiguous in disputes; a voter in Kathmandu must convert mentally |
| **The viewer's timezone** | most usable; **requires capturing it**, and the same deadline then reads differently to different people |
| **Both** — viewer's, with the election's shown alongside | clearest; the Product Owner's request plus an audit-friendly anchor |

⚠️ **Not a purely cosmetic choice.** Voting deadlines are contestable facts. **If two voters are shown different closing times and one misses it, the record must be able to state what they were shown** — which is an argument for displaying both and for logging the timezone used.

## What must be decided

1. Which timezone is displayed (above).
2. ~~How the viewer's timezone is obtained~~ — ✅ **DECIDED by the Product Owner, 2026-08-06:**

   > **Use the timezone the user is *using the software from* — not where they live.**

   **So the mechanism is device/browser detection** — `Intl.DateTimeFormat().resolvedOptions().timeZone` — and **`users.country` is explicitly NOT the source.** Residence and location-of-use are different facts, and a diaspora product is precisely where they diverge: a Nepali citizen voting from Munich should see Munich time.

   ⚠️ **Two consequences of that decision, worth designing for rather than discovering:**

   * **The displayed deadline moves with the voter.** Someone who checks the closing time in Kathmandu and votes from Frankfurt sees two different wall-clock times for the same instant. **Both are correct.** This strengthens the case for always naming the zone and showing the election's own timezone alongside.
   * **Detection can fail or be wrong** — a misconfigured device, a VPN, a locked-down browser. **A fallback must be decided** (the election's timezone is the natural one, since it is already required by `open_voting`), and **the fallback must be visible**, not silent.
3. Whether the election's timezone is **always** shown next to a converted time.
4. Whether **UTC-only storage** becomes an enforced invariant with a test, given the column types cannot express anything else.

## Acceptance criteria

* [ ] The decision recorded before implementation.
* [ ] Voting windows, deadlines and results timestamps display in the decided timezone, **with the zone named** — never a bare `18:00`.
* [ ] The **device/browser** timezone is captured and used for display — **not** `users.country`.
* [ ] A decided, **visible** fallback when detection fails or returns something implausible.
* [ ] 🔒 **The timezone shown at the moment of voting is recorded**, so a later dispute about "when did voting close for me" is answerable from the record rather than reconstructed. *(Anonymity applies: record the zone, not anything that identifies the voter — ADR-T11.)*
* [ ] A test asserting a known UTC instant renders correctly in at least two zones — **including one whose offset differs across DST**, since `Europe/Berlin` is +1/+2 and an election could span the change.
* [ ] All writes proven to store UTC.

## Note on the lifecycle

`ElectionConstitution`'s `open_voting` action already carries a **`timezone_set`** precondition — so the domain already treats an election's timezone as required before voting opens. **This ticket is about honouring it in the interface, not about inventing the concept.**

---

**Traceability:** Product Owner statement, 2026-08-06 · `config/app.php` (`timezone = UTC`) · `elections.timezone = 'Europe/Berlin'` · `namaste-2026-74d3721c` `end_date = 16:00 UTC = 18:00 Berlin` · `users.country = NULL`, no `users.timezone` · `ElectionConstitution` `open_voting` precondition `timezone_set` · `PBDIGIT-47`
