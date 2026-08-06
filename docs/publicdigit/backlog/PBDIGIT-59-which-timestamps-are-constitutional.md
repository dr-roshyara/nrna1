# PBDIGIT-59 — Which timestamps are constitutional? The lifecycle and the persisted schedule disagree

**Type:** Discovery → architecture decision · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Found by:** `PBDIGIT-58B`, while establishing the Election Entry Resolution capability boundary
**Extracted from `PBDIGIT-58`** so consumer migration is not expanded into a data-model repair.

| | |
|---|---|
| **Status** | 🟡 **ARCHITECTURE DECISION REQUIRED** |
| **Question** | **Which timestamps define the voting window — and are the others derived, legacy, or independent?** |
| **Customer impact** | 🟡 **The four elections' legacy and lifecycle states all disagree, and demo elections can never reach a voting state** (`voting_*` is `NULL`). The ~31-minute early close that prompted this ticket is **resolved as an operator action, not a defect** — see §Cause ESTABLISHED |
| **Blocks** | **`PBDIGIT-58B`'s implementation** — not its scope. Consumers should read the authority either way; **what is undecided is what the authority reads** |

---

## The disagreement

**Two column pairs describe one concept — "when is voting open?"**

| Election | `start_date` → `end_date` | `voting_starts_at` → `voting_ends_at` |
|---|---|---|
| **namaste 2026** (real) | `14:56` → **`16:00`** | `14:56` → **`15:28:44`** |
| Demo Election | set | **`NULL`** → **`NULL`** |
| Demo Election — Namaste Nepal GmbH | set | **`NULL`** → **`NULL`** |
| Demo Election — Public Digit | set | **`NULL`** → **`NULL`** |

**The lifecycle engine uses only the second pair.** `ElectionLifecycleEngineImpl::isVotingWindowOpenNow()` delegates to `ElectionClockService::isVotingOpen($election)`, which reads `voting_starts_at` / `voting_ends_at`.

**Consequence, measured 2026-08-06 19:16 UTC:**

| Election | legacy `status` | engine state |
|---|---|---|
| Demo Election | `planned` | `draft` |
| Demo Election — Namaste Nepal GmbH | **`active`** | `draft` |
| Demo Election — Public Digit | **`active`** | `draft` |
| namaste 2026 | `planned` | `results_published` |

**4 of 4 disagree.** The demo elections cannot reach any voting state at all, because the pair the engine reads is `NULL`.

## ✅ Cause ESTABLISHED (2026-08-06, from `election_state_transitions`) — no timing defect

| Fact | Value |
|---|---|
| Officer's stated close | **18:00 Berlin = 16:00 UTC** |
| `end_date` | `16:00` — agrees with the officer |
| `voting_ends_at` | **`15:28:44`** |
| `results_published_at` | **`15:29:08`** — 24 seconds later |

**So voting closed ~31 minutes before its stated end, and results published immediately after.**

**It was two deliberate clicks in the management UI, by the same human officer, 24 seconds apart.** `election_state_transitions` for this election:

| Timestamp | Target state | Actor |
|---|---|---|
| `14:14:28` | `approved` | *(null)* |
| `14:14:31` | `setup_administration` | `a26ed09e…` |
| `14:23:36` | `setup_nomination` | `a26ed09e…` |
| **`15:28:44`** | **`counting`** | **`a26ed09e…`** |
| **`15:29:08`** | **`results_published`** | **`a26ed09e…`** |

**`15:28:44` is the `close_voting` transition itself** — and `Election::applySideEffectsForCloseVoting()` `:1850-1861` writes `voting_ends_at = $currentTime`. So `voting_ends_at` did not drift from `end_date`; **it was overwritten by the act of closing.**

> **Resolved: there is no autonomous timing defect.** The officer closed voting ~31 minutes early and published 24 seconds later. **The serious-defect branch of this observation is closed.**

**Consequence for the decision below: `voting_ends_at` is written by the lifecycle to record the *actual* close, while `end_date` holds the *scheduled* close. That is Option C's premise, now evidenced rather than merely possible** — the two column pairs were being used for two different purposes. **The decision itself remains the Product Owner's** (`R-34`); what has changed is that C is no longer a hypothesis.

## The decision required

> **Which timestamps are constitutional?**

| | Option | Consequence |
|---|---|---|
| **A** | **`voting_starts_at` / `voting_ends_at` are authoritative**; `start_date` / `end_date` become derived or legacy | consistent with the lifecycle as built — **but three demo elections have `NULL`, so they must be backfilled or demo mode stays `draft` forever** |
| **B** | **`start_date` / `end_date` are authoritative**; `voting_*` are derived from them | matches what the officer set and what the UI shows — **requires changing `ElectionClockService`, i.e. the constitutional model** |
| **C** | **They mean different things** — e.g. `start_date`/`end_date` is the *published schedule*, `voting_*` the *actual* window | legitimate if intended — **then the divergence is not a defect and both must be documented, because nothing says so today** |

**Engineering does not choose** (`R-34`). **But note that Option C is the only one under which today's data is not evidence of a defect** — and if C is chosen, the officer-facing UI must make clear which window it is editing.

## What must be established

* [x] **Who set `voting_ends_at` to `15:28:44`, and when** — the officer `a26ed09e…`, via the `close_voting` transition at that instant.
* [x] Whether `voting_*` is written by the lifecycle transitions, by the management UI, or derived on save — **two paths write it** — the lifecycle (`applySideEffectsForCloseVoting` `:1850-1861`) and the timeline form (`ElectionManagementController::updateTimeline` `:1450-1451`). **It is never derived.**
* [ ] Why the three demo elections have `NULL` — never set, or a provisioning gap in `demo:setup`.
* [ ] Which pair the officer-facing UI edits, and which it displays.
* [ ] **The decision, recorded.** Then `PBDIGIT-58B` proceeds.

## Relationship to the other tickets — kept deliberately separate

| Ticket | Concern |
|---|---|
| **`PBDIGIT-58`** | **consumer migration** — move `User::getActiveElection()` and `countActiveElections()` onto the authority |
| **`PBDIGIT-59`** (this) | **what the authority computes from** — the timestamp semantics |
| **`PBDIGIT-48`** | **retire the legacy fields**, after migration |

> **`PBDIGIT-58` is not blocked by this in scope, only in sequencing.** Migrating consumers to the authority is right regardless of which timestamps the authority reads — **but doing it before this is decided would change voting windows silently**, and that is why `58B` waits rather than proceeds.

---

**Traceability:** `PBDIGIT-58B` (where it surfaced) · `PBDIGIT-48` (the same two-representation pattern, one level up) · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:99-101,166-168` · `ElectionClockService::isVotingOpen()` · `elections.start_date/.end_date/.voting_starts_at/.voting_ends_at/.results_published_at` · `elections.state_audit_log` · `election_state_transitions` · `PBDIGIT-50` (timezone display — the officer's 18:00 Berlin) · `R-34`
