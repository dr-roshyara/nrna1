# Why "Namaste Nepal GmbH" cannot switch from Election-Only to Full Membership

| | |
|---|---|
| **Date** | 2026-10-07 |
| **Kind** | ⚠ authority: generated. A **read-only** DDD review: code reading, git history, and `SELECT`-only queries against the development database. **No file under `app/`, `resources/`, `routes/`, `tests/` or `database/` was changed. No data was written. Nothing was decided.** |
| **Question (user)** | *"Why am I not able to switch from election-only mode to membership mode at `http://127.0.0.1:8000/organisations/namaste-nepal-gmbh/settings`?"* |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, Senior DDD Architect role) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |

## 1. Answer in one paragraph

The switch fails for **four stacked reasons**, each of which would block it on its own:

1. **It crashes (HTTP 500).** The settings guard calls `isParticipationLocked()` on the `ElectionLifecycle` facade, which does not have that method. Any organisation that holds at least one election therefore gets a 500 on every attempt. This is the known finding `IERVP-1` (2026-08-08), still unfixed, and **owned by no backlog story**.
2. **Even with the crash fixed, the guard would refuse.** All 11 real elections of Namaste Nepal GmbH sit in "participation-locked" states: 10 in `results_published` (never archived) and 1 still in `voting_active`.
3. **There is no way out.** The only state that releases the lock is `archived`, and **no route, controller or UI performs the `archive` transition**, although the domain supports it.
4. **The page shows no error message.** It never renders validation errors or flash messages, so every refusal is silent.

**Architecturally, the guard is now redundant.** Each election already carries an immutable `voter_source_strategy` snapshot (all 11 real elections here: `election_only`), so changing the organisation's mode cannot affect them.

## 2. What the two modes mean (from the code and the developer guides)

| | Election-Only | Full Membership |
|---|---|---|
| Organisation flag | `organisations.uses_full_membership = false` | `true` |
| Voter source for **new** elections (`VoterSourceStrategy::fromOrganisation`, used **only at election creation**) | `ImportedVoterRegistry` (`'election_only'`): voters come from organisation enrolment (`OrganisationUser`), with no membership filtering | `MembershipRegistry` (`'full_membership'`): voters must be formal `Member`s with active status and paid or exempt fees |
| Existing elections | **unaffected:** *"Election snapshot is sovereign runtime authority. Once created, voter_source_strategy is IMMUTABLE at election level. Elections own their voter participation rules; org mutations don't retroactively change them."* (`app/Domain/Election/Enum/VoterSourceStrategy.php` docblock) | same |
| Organisation-level features gated by the flag | — | fee recording (`MembershipFeeController.php:112`: `abort_if(!uses_full_membership, 403)`), the member-application flow (`PBDIGIT-02` verification) |

Developer guides read: `developer_guide/organisations/membership_mode/{MEMBERSHIP_MODES, OPERATIONS_GUIDE}.md` and `developer_guide/organisations/election_only_mode/what_is_election_only_mode.md`. The operations guide's *"Mode Change Breaks Existing Elections"* section says a mode change *"only affects NEW elections"*. That matches the snapshot rule, and it contradicts the guard in §3.2.

## 3. The four causes, with evidence

### 3.1 Cause 1: the guard crashes (HTTP 500), so `IERVP-1` is still open

```php
// app/Http/Controllers/OrganisationSettingsController.php:126-129
$lockedElectionExists = Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->get()
    ->contains(fn($election) => ElectionLifecycle::of($election)->isParticipationLocked());
```

- `ElectionLifecycle::of()` returns **the facade** (`app/Application/Election/Facades/ElectionLifecycle.php:59-67`).
- `isParticipationLocked()` exists only on `ElectionLifecycleSnapshot` (`app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php:39`). The facade exposes it through `->snapshot()` (`:95`).
- **Verified by reflection (2026-10-07):** facade `isParticipationLocked` → **false**; facade `__call` → **false**; snapshot `isParticipationLocked` → **true**.
- **So** for the first election in the collection, PHP throws `Error: Call to undefined method …ElectionLifecycle::isParticipationLocked()` → **HTTP 500**. Namaste Nepal GmbH holds 12 elections (11 real and 1 demo), so **every attempt fails here, whatever their states.**
- **History:** the guard came in with `ebf3d1a5a` (2026-05-25). The defect was found and recorded as **`IERVP-1`** on 2026-08-08 (`docs/publicdigit/reviews/2026-08-09-iervp-election-runtime-verification.md:46`; `.claude/sessions/2026-08-08.md:3036`; listed under *"Related findings — recorded, not merged"* in `PBDIGIT-64:103`). **No story was ever created for it.** The PBDIGIT completeness audits of 2026-10-01 did not pick it up either (see §6).
- **Side effect:** the rate limiter is hit **before** the guard (`:41-47`, 5 attempts per hour). After five crashes in an hour, the response changes to a `rate` validation error, which the page also does not show (§3.4).

### 3.2 Cause 2: even if fixed, the guard would refuse, and by design it should no longer exist in this form

**Development-database state** (`SELECT` only, 2026-10-07):

| | Value |
|---|---|
| Organisation `namaste-nepal-gmbh` | `uses_full_membership = false` (election-only) |
| Real elections | **11**, none archived. **10 × `results_published`**: `testing 1`, `onf-europe-1`, `namaste 2026`, `test election 1`, `onf-europe-2`, `onf-europe-5`, `onf-europe-3`, `Krishna`, `onf-2`, `onf-1`. **1 × `voting_active`**: `onf-europe-test` (voting window ended 2026-09-14 21:29, no results published) |
| Their `voter_source_strategy` snapshot | **`election_only` for all 11 real elections queried** |
| Demo election | `draft`, snapshot `NULL` |

**The lock rule** (`ElectionLifecycleSnapshot::isParticipationLocked`): it is `false` only for `Draft`, `SubmittedForApproval`, `Approved`, `Rejected` and `Archived`. Every state from `SetupAdministration` through **`ResultsPublished`** is locked. So **an election that has published results but was never archived locks the organisation's mode for ever.**

**The error text understates the rule.** `organisations.errors.participation_locked_by_active_election` reads *"…while an election is in setup, nomination, voting, or counting phase"*. It does not mention `results_published`, which is the state 10 of the 11 real elections are in.

**DDD assessment: the guard protects an invariant that the election aggregate already owns.**
- **Ownership.** Voter participation rules for an election are owned by the **Election** aggregate, through its immutable `voter_source_strategy` snapshot. The **Organisation** owns only the governance mode **for future elections**.
- **Why the guard existed.** In `ebf3d1a5a` the snapshot column was *"nullable for Phase 2 compatibility"* and `ElectionMode::fromElection()` *"reads snapshot first, falls back to org"*. Freezing the organisation flag while an election was live was then a **compensating control** for elections without a snapshot.
- **Why that reason is gone.** Today `VoterSourceStrategy::fromElection()` **throws** when the snapshot is missing (`:92-106`). There is **no fallback to the organisation**, and every real election here has its snapshot. The only election without one is a draft (not locked).
- **Consequence.** The guard now couples an organisation-level policy change to the lifecycle of elections that are already immune to it. **It blocks a legitimate business act and protects nothing.** Whether it should be removed, narrowed (for example, only elections lacking a snapshot), or kept for a reason not recorded in the code is an **architecture decision (ARB)**. It is not engineering's to make.

### 3.3 Cause 3: there is no way out, because elections cannot be archived through the product

- **The domain supports archiving:**
  - `ElectionConstitution` `'archive'` (`results_published → archived`, roles chief and deputy; `ElectionConstitution.php:126-132`);
  - the engine offers it (`ElectionLifecycleEngineImpl.php:336`);
  - side effects exist (`Election::applySideEffectsForArchive`, `Election.php:2035-2042`).
- **No product path triggers it.** `git grep` finds no route, controller, console command or Vue action that calls the `archive` transition. The only `PATCH /archive` route (`routes/organisations.php:201`) is **`MemberController::archive`**, which archives a *member*.
- **The backlog record is wrong.** `PBDIGIT-24` ("Archive the election", `IMPLEMENTED — NOT VERIFIED`) cites *"`archive` action `routes/organisations.php:199` (`PATCH /archive`)"* as its route. That is the member route. **The election-archive capability is not reachable.**
- `onf-europe-test` additionally sits in `voting_active` after its window closed. It would first need closing, counting and publishing before it could be archived.

### 3.4 Cause 4: every refusal is invisible

`resources/js/Pages/Organisations/Settings/Index.vue` submits with `form.patch(route('organisations.settings.update-membership-mode', …))` (`:688-699`), but **renders no errors and no flash messages anywhere in the file**. `form.errors`, `$page.props.errors` and `flash` are absent; there is not a single `errors` reference in its 828 lines. So:
- a `membership_mode` refusal (cause 2), a `rate` refusal (§3.1) and a `confirm_mode_change` refusal all **disappear silently**;
- the toggle then looks as if it did not work;
- the 500 of cause 1 shows only Inertia's generic error modal (or nothing, depending on the environment). In neither case does it say *why*.

## 4. What would happen after a successful switch (business consequence, recorded for the decision)

Switching an organisation to Full Membership changes **only new elections**. Their voters must then be `Member`s with **paid or exempt fees**. Today:
- **`PBDIGIT-209`:** a fee payment cannot be recorded (the `membership_payments` columns do not match);
- **`PBDIGIT-211`:** scheduled annual-fee generation and overdue marking do nothing for real organisations (no tenant context);
- **`IERVP-6`** (2026-08-08; recorded in `PBDIGIT-64:104`): `User::isEligibleVoter()` consults only `Member` records.

So **the first Full-Membership election could end up with no eligible voters**: members cannot reach "paid" status through the product. This does not argue against switching. It is a dependency the PO should see when deciding the switch.

## 5. Classification against the backlog (frozen baseline `PBDIGIT-00`…`212`, new IDs only for an uncovered delivery obligation)

| # | Finding | Class | Owner / proposal |
|---|---|---|---|
| F-1 | Settings guard calls a non-existent facade method → HTTP 500 for any organisation with elections (**`IERVP-1`**) | **C — uncovered delivery defect** (recorded 2026-08-08, never ticketed) | **Story created on the PO's instruction: `PBDIGIT-257`** (2026-10-07; `PBDIGIT-213`, the number first proposed here, was already taken by another story) |
| F-2 | The participation-lock guard is redundant under the snapshot rule and over-broad (includes `ResultsPublished`); its message understates it | **D — architecture decision (ARB)** | remove, narrow, or keep, with a recorded reason. Until decided, fixing F-1 alone would turn the 500 into a permanent (silent) refusal for this organisation |
| F-3 | Election archiving is unreachable through the product | **B: correction to `PBDIGIT-24`** (its route evidence points at the member-archive route) | 24's "IMPLEMENTED — NOT VERIFIED" should read "domain implemented; **no product path**" |
| F-4 | The settings page renders no validation errors or flash messages | **B: addition to `PBDIGIT-02`** ("Configure the organisation", its verification includes "switch membership mode") | or a small story, if the PO prefers |
| F-5 | `onf-europe-test` remains `voting_active` after its window closed | observation only | out of scope here. It is consistent with the scheduled-execution findings, but **not verified** as such |
| F-6 | After a switch, fees cannot be recorded or generated (§4) | A: already owned | `PBDIGIT-209`, `211`; `IERVP-6` (in `PBDIGIT-64` related findings, also untracked) |

## 6. A note on my own earlier audits

`IERVP-1` and `IERVP-6` were recorded on 2026-08-08 only in session logs, a verification report and the *"related findings, not merged"* section of `PBDIGIT-64`. The 2026-10-01 completeness and closure audits classified documentation findings by **finding-ID plus status marker**, and these lines carried no status marker. **So they were missed.** This is the limitation those audits named (*"a finding written as prose … would be missed"*), and here it materialised. **Discovery stays closed by the ARB's instruction.** This review records the two items so the PO can decide, and does not reopen the sweep.

## 7. What can be done today without code

**Nothing through the product UI**, given causes 1–4. Changing the flag directly in the database would bypass the governance path and the audit log (`Log::info` in `:66-72`), so **it is not recommended** without a PO decision. The decisions that unblock the switch:
1. **PO:** ~~create the story for F-1 (`IERVP-1`)~~ **created as `PBDIGIT-257`**; authorise it.
2. **ARB:** decide F-2 (the guard's role under the snapshot rule).
3. **PO:** decide whether election archiving must become a product capability (F-3) or whether F-2 makes it unnecessary for this purpose.

## 8. Evidence index

- **Code:**
  - `OrganisationSettingsController.php:20-171`;
  - `ElectionLifecycle.php:59-67, :95`;
  - `ElectionLifecycleSnapshot.php:39-…`;
  - `VoterSourceStrategy.php` (docblock, `:92-106`, `:118-130`);
  - `Organisation.php:17-49, :200-214`;
  - `Settings/Index.vue:603-699`;
  - `ElectionConstitution.php:126-132`;
  - `ElectionLifecycleEngineImpl.php:336`;
  - `Election.php:1747, :2035-2042`;
  - `routes/organisations.php:99-101, :201`;
  - `MembershipFeeController.php:112`;
  - `resources/lang/en/organisations.php:9, :12`.
- **History:**
  - `ebf3d1a5a` (2026-05-25, the guard and nullable snapshot with org fallback);
  - `c46b02f84` (last change to the facade and controller, 2026-05-25).
- **Prior records:**
  - `IERVP-1`/`IERVP-6`: `docs/publicdigit/reviews/2026-08-09-iervp-election-runtime-verification.md:46`, `PBDIGIT-64:103-104`, `.claude/sessions/2026-08-08.md:3036`;
  - `PBDIGIT-24` (EPIC-06 `:83-93`);
  - `PBDIGIT-02` (EPIC-01 `:21-31`).
- **Runtime evidence:**
  - reflection on the facade and snapshot classes (no database access);
  - `SELECT` on `organisations` and `elections` (development database, read-only).
