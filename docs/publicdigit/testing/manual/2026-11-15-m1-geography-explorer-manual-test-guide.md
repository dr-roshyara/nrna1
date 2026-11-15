# M1 Geography Explorer: manual test guide (PBDIGIT-355)

| | |
|---|---|
| **For** | the Principal, testing in a real browser |
| **Prepared** | 2026-11-15 by Claude (Session B), from the merged code on `election-flow-updated` (`5dd519fca`) and a **read-only** look at the local database |
| **Status** | M1 is implemented and merged. **Acceptance by the Principal is open.** This guide records nothing as passed |

---

## A. What is being tested?

**The business purpose:** the Geography Explorer shows the **officially published** geography of PublicDigit (countries, provinces, districts and so on) to signed-in users. A user can:
- see the top-level places (those not inside another place);
- open a place and see:
  - its names (in the user's language where an official name exists);
  - its classifications (for example "province") and identifiers (for example an ISO code);
  - the place it lies in and the places inside it;
- move between places while the application keeps **one fixed publication**, so nothing changes under the user while they browse ("as officially known at publication N").

**Deliberately not included:** editing, importing, approving or publishing geography. The pages only read.

---

## B. Environment and preparation

### What exists (checked 2026-11-15, read-only)
| Item | Local environment (your machine) |
|---|---|
| Application address | `http://127.0.0.1:8000` (the configured `APP_URL`) |
| Geography tables | present |
| **Published geography** | **none** (publication position 0; 0 publications) |
| Users | 2 local accounts exist. Use your own login; this guide contains no credentials |
| Route cache | **a stale cache from 19 October is present.** The Explorer routes are not served until it is cleared (step 2 below) |

**Staging and production:** not checked from here. The same steps apply. Production shows the empty state until official data is published through the accepted process. **No data may be published just for this test.**

### Starting the local application (administrator steps, once)
Run these in the project folder (`nrna1`):

1. Make sure PostgreSQL is running, as for normal local work.
2. **Clear the stale route cache** (safe; Laravel rebuilds it from the route files):
   ```
   php artisan route:clear
   ```
3. Start the web server:
   ```
   php artisan serve
   ```
4. In a second terminal, start the frontend dev server:
   ```
   npm run dev
   ```
   **Use `npm run dev`, not `npm run build`:** `public/build` is tracked in git, and a build would change committed files.
5. Open `http://127.0.0.1:8000/login` and sign in with your own account.

---

## C. Exact URLs

| Route name | URL pattern | Local URL to open | Expected page |
|---|---|---|---|
| `geography.entry` | `/geography?date=YYYY-MM-DD` | **`http://127.0.0.1:8000/geography`** | redirects to the Explorer at the **current** publication (today locally: position 0) |
| `geography.explorer` | `/geography/k/{position}?date=YYYY-MM-DD` | `http://127.0.0.1:8000/geography/k/0` | the Explorer: top-level places, or the empty state |
| `geography.place` | `/geography/k/{position}/places/{unitId}?date=YYYY-MM-DD` | **none possible locally** (no published place exists) | Place Details |

**Parameters:**
- **`position`:** the publication number. Always start from `/geography`, which chooses the current one for you.
- **`unitId`:** a place's id. Never type one in; click a place in the Explorer. If none is listed, there is nothing to open.
- **`date`:** optional, for example `?date=2020-01-01`. Leave it out and today's date is used.

**All pages require sign-in.** Signed-out visitors are sent to `/login`.

---

## D. Manual test cases

Fill in **Actual result**, **Result** (Pass / Fail / Blocked) and **Evidence** yourself.

**Locally, today, with no published geography:**
- **T01, T02, T08 (partly), T09 (partly), T10, T11, T12, T13 and T14 can be run.**
- **T03 to T07 are BLOCKED** until official geography is published in the environment you test.

| ID | Purpose | Preconditions | URL / action | Steps | Expected result | Actual result | Result | Evidence |
|---|---|---|---|---|---|---|---|---|
| **M1-T01** | Open the Explorer | signed in; preparation done | `http://127.0.0.1:8000/geography` | 1. open the URL | page "Reference geography" with the subtitle "Officially published places"; the PublicDigit header and footer; a blue banner "As officially known at publication 0 · Valid on (today)" with a link "Show the latest publication". The address bar now shows `/geography/k/0` | | | |
| **M1-T02** | Empty state | no published geography (true locally) | as T01 | 1. look below the banner | the message **"No official geography has been published yet."** and "Places appear here once they are officially published." **No place names, no sample data** | | | |
| **M1-T03** | Top-level places | **BLOCKED locally:** needs published data | `/geography` | 1. look at the list | cards with place names (for example countries); **no place that lies inside another place** (such as a province) at this level | | | |
| **M1-T04** | Open Place Details | BLOCKED (as T03) | click a card | 1. click a place; 2. compare the name and the identifiers | the page shows the place you clicked, with its name, "Classifications" and "Identifiers" | | | |
| **M1-T05** | Parent and child navigation | BLOCKED (as T03) | Place Details | 1. read "Contained in"; 2. read "Contains", grouped under "Scheme: …"; 3. click a child | the child's page opens; its "Contained in" names the place you came from | | | |
| **M1-T06** | Names and languages | BLOCKED for names (as T03); the **page texts can be tested locally** | header menu **DE / EN / NP** | 1. choose EN, then DE, then NP; 2. **reload the page (F5)** after each choice | the texts change straight away (for example DE "Referenzgeografie", NP "सन्दर्भ भूगोल"). **Place names change only after the reload** (they come from the server). With data: a name shown in another language carries the note "Platform default language (en)", never presented as a translation | | | |
| **M1-T07** | Classifications and identifiers | BLOCKED (as T03) | Place Details | 1. check both lists | values as published; where there are none, "None published", nothing invented | | | |
| **M1-T08** | Publication snapshot | locally only the Explorer; with data, the full test | Explorer → place → "All top-level places" | 1. note the number in the banner; 2. open a place; 3. click "← All top-level places" | the same publication number on every page; the address keeps `/k/<same number>` and the same `?date=` | | | |
| **M1-T09** | Valid date | — | `http://127.0.0.1:8000/geography/k/0?date=2020-01-01`, then `…?date=2020-13-45` | 1. open the first; 2. open the second | first: the banner shows "Valid on 2020-01-01". Second (an impossible date): **no data shown**; you are sent back to the previous page (or the start page). Record what you see | | | |
| **M1-T10** | Read-only | — | Explorer (and Place Details with data) | 1. look for buttons or forms | **no** edit, import, approve or publish controls. Only links and the language menu | | | |
| **M1-T11** | Sign-in required | — | a private window: `http://127.0.0.1:8000/geography/k/0` | 1. open it signed out; 2. sign in | 1: you are sent to the login page; 2: after signing in, the Explorer opens | | | |
| **M1-T12** | Invalid addresses | signed in | `…/geography/k/999999` · `…/geography/k/abc` · `…/geography/k/0/places/00000000-0000-4000-8000-000000000000` · `…/geography/k/0/places/not-an-id` | open each | each shows a **"not found" (404)** page; never a place, never data | | | |
| **M1-T13** | Responsive layout | — | Explorer (and Place Details with data) | use the sizes in §E | no sideways scrolling; no clipped or overlapping text; the header menu works; the banner wraps neatly; cards are one column on phones and two on wider screens | | | |
| **M1-T14** | Browser usability | — | Explorer | 1. refresh (F5); 2. go to another page, then Back and Forward | after a refresh the same publication number and date; Back and Forward return to the same addresses | | | |

---

## E. Browser and viewport matrix

| Device profile | Width | Height |
|---|---:|---:|
| Desktop | 1440 | 900 |
| Laptop | 1366 | 768 |
| Tablet | 768 | 1024 |
| Mobile | 390 | 844 |
| Small mobile | 360 | 800 |

**To simulate a size (Chrome or Edge):**
1. Press **F12**.
2. Click the **device toolbar** icon (or press Ctrl+Shift+M).
3. Choose "Responsive" and type the width and height.

In Firefox: **Ctrl+Shift+M** opens Responsive Design Mode.

---

## F. Screenshots to capture

1. Explorer in English.
2. Explorer in German.
3. Explorer in Nepali.
4. The empty state (locally, today).
5. Place Details *(when data exists)*.
6. "Contained in" / "Contains" *(when data exists)*.
7. Mobile Explorer (390 wide).
8. Mobile Place Details *(when data exists)*.
9. Anything unexpected, with the address bar visible.

Don't capture the login form with a password, or anything else private.

---

## G. Known limitations

- **Responsive behaviour wasn't checked in a real browser** by the implementing session (only automated tests and the shared responsive layout). T13 is the first real check.
- **The local environment has no published geography**, so it shows the empty state. **That is correct behaviour, not a defect.** T03–T07 need an environment with officially published data.
- **No geography may be published just to run these tests:** publication needs the accepted process, the G-8 data decision and D-84's platform owner.
- **Opening `/geography` updates a technical timestamp**: the publication boundary (`closed_through_time`) moves to the current time. That is how "as officially known now" is fixed; it changes **no geography fact**. The pinned pages (`/geography/k/…`) change nothing at all.
- **Names come from the server.** After changing the language, reload the page to see place names in it.

---

## H. Acceptance checklist (the Principal decides; nothing is pre-ticked)

**Functional**
- [ ] T01 the Explorer opens for a signed-in user
- [ ] T02 the empty state is correct while nothing is published
- [ ] T08 one publication number throughout a visit
- [ ] T09 the valid date is honoured; an impossible date shows no data
- [ ] T14 refresh, Back and Forward keep the publication and date

**Visual and responsive**
- [ ] T13 is fine at all five sizes
- [ ] T06 the page texts are correct in EN, DE and NP

**Data correctness** *(when published data exists; otherwise record "deferred: no data")*
- [ ] T03 top level · [ ] T04 details · [ ] T05 parents and children · [ ] T06 names and fallback note · [ ] T07 classifications and identifiers

**Read-only and security**
- [ ] T10 no editing or publishing controls
- [ ] T11 signed-out visitors are sent to login
- [ ] T12 wrong addresses give "not found"

**Known limitations accepted**
- [ ] data tests deferred until official data exists
- [ ] `/geography` updates the technical boundary timestamp only

**Defects found** (describe; each becomes a correction item):
1. …

**Decision:** ☐ M1 accepted · ☐ accepted with the defects above to be fixed · ☐ not accepted, signed by the Principal, dated ________
