# ADR-A: Constitutional limit (System B) freeze protocol

**Status: 📝 PROPOSED r2. ⛔ Approval BLOCKED until O-3 evidence is supplied** (operations confirmation of the production PostgreSQL isolation level; §8 item 1). The Principal can't approve G-ADR-A before then.
- **Decided inside this ADR on 2026-10-14** (the ADR itself remains unapproved):
  - **A-2** approved (§3.5);
  - the **ADR-A-T decision rule** approved (§3.3; its operational condition isn't yet satisfied).
- **Drafted under:** P0 of the PBDIGIT-286 plan (G-PLAN approved 2026-10-14).
- **r2** (Principal review, 2026-10-14):
  - the trigger becomes an explicit separate decision **ADR-A-T** (§3.3, §8), not implied by approving this ADR;
  - transactional failure behaviour (§3.6);
  - the legacy `none` boundary is cross-referenced (§3.5).
- **Authorises nothing:** no production code, tests, migrations or database access until G-ADR-A **and** G-ISO.

**Created:** 2026-10-14 · **Ticket:** PBDIGIT-286 · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/` beside the other PublicDigit ADRs.

**Consumes (not restated):**
- decision record r3 `docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md` (§2 facts, §3.2 approved decisions, §4.1 design);
- plan r3 `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` (phases P1, P2; AC-1 to AC-5).

**Companion ADRs:** ADR-B (frozen record and policy source), ADR-C (locking and concurrency at the vote).

> **What this ADR protects, in one line:** *Once voting has opened, System B's configuration cannot change through any application path, and a configuration change racing with the opening of voting either completes before it or is refused.*

---

## 1 · Context (facts, verified in source)
- **System B's configuration** is `elections.max_votes_per_ip` (default 6) and `elections.network_binding_strategy` (default `ip_count`; values `none`, `ip_count`, `ip_strict`; `ElectionConstitutionSchema.php:7`). No application path writes either today; neither is in `Election::$fillable` (`Election.php:121`).
- **"Voting opens"** means `voting_locked` becomes `true` (NOT NULL, default false, `2026_04_22_000003…:12`). It is never reset (`applySideEffectsForCloseVoting` keeps it; suspend and resume don't touch it).
- **Two opening paths:**
  - **Explicit:** `ElectionManagementController::openVoting` → `Election::transitionTo` (`:1664`): cache lock `election_transition:{id}` + `DB::transaction`. Side effect `applySideEffectsForOpenVoting` (`:1897`) does a raw `DB::table('elections')` update (`:1930`) with no condition on the flag.
  - **Scheduled:** `ProcessElectionAutoTransitions` → `Election::enforceVotingLock` (`:1609`): model `update`, no cache lock, no transaction.
  - **The scheduled path doesn't check the voting window:** `processNominationToVotingTransition` (`ProcessElectionAutoTransitions.php:80-118`) locks voting **without** the `voting_window_defined` precondition that guards the explicit path (`ConstitutionalTransitionGuard.php:226`). This is a **known interaction with backlog PBDIGIT-289**, not PBDIGIT-286 scope. Its release consequence is in ADR-B §3.6b.
- **An election can reach VotingActive straight from SetupNomination** (the explicit path sets `nomination_completed`).
- **The settings save** (`ElectionSettingsController::update`, `:73-134`):
  - authorization `UpdateElectionSettingsRequest::authorize()` → `manageSettings` (active chief/deputy);
  - an optimistic `settings_version` check (`:187-197`), bumped in `buildUpdateData` (`:239`);
  - **no transaction and no row lock**;
  - security fields locked from `isLocked()` (`:148-151`);
  - all settings frozen while VotingActive (`settingsFrozen`, `:47-50`).
- **`config/database.php` sets no isolation level.** PostgreSQL's default is READ COMMITTED; production is unverified (O-3).

## 2 · Decision drivers
- **R2:** configurable by an authorised committee member **before voting opens**; unchanged for the whole voting period; enforced server-side.
- **D1:** the freeze takes effect when voting opens; the general security lock and System A's lock stay unchanged.
- **D11:** System B is mandatory; a committee member can't remove it.
- **H6:** an unchanged System B value must never block a later settings save.
- **H7:** one `settings_version` increment per request.
- **Minimal change:** no new lifecycle state, no new service where a model method suffices.

## 3 · Decision (proposed)

### 3.1 One write path for System B (state protection)
- **The method:** System B is written **only** by one dedicated model method, `Election::writeConstitutionalLimit(int $maxVotesPerIp): bool` (name indicative).
- **What it executes**, inside the caller's transaction:
  ```sql
  UPDATE elections
     SET max_votes_per_ip = :n,
         network_binding_strategy = 'ip_count',
         updated_at = now()
   WHERE id = :id AND voting_locked = false
  ```
  **G-A2 (approved 2026-10-14):** the conditional update **doesn't** touch `settings_version`. See H7 below.
- **Return value:** `true` when 1 row changed. `false` (0 rows) means voting has opened.
- **D11:** the strategy is always written as `ip_count`. The method has no strategy parameter, so `none` can't be written through it.
- **H6, write only on change:** the settings controller calls the method **only** when the submitted N differs from the stored N, or when the stored strategy isn't `ip_count` (see A-2, §3.5). A request whose System B values equal the stored ones never calls it. So after voting opens, and after it closes, saving other settings is unaffected.
- **0 rows:** the **whole** settings request is refused with a `SecurityPolicyLocked`-style error ("The constitutional limit can't be changed after voting has opened."), and nothing is written.
- **One transaction:** the settings request runs in a single `DB::transaction` (new), so a 0-row refusal rolls back any other field already written in the same request.
- **H7, exactly one bump per request (G-A2, approved 2026-10-14):**
  - the controller's existing **absolute** assignment `settings_version = validated + 1` (`buildUpdateData`, `ElectionSettingsController.php:239`) stays the **only** writer of `settings_version`;
  - it runs on every accepted settings request, in the same transaction as the System B write, so the net result is exactly +1 whether or not System B changed;
  - the conditional System B update doesn't touch the version.
- **The optimistic check** (`checkOptimisticLock`) stays.

### 3.2 Both opening paths become conditional (concurrency protection)
- **Explicit path:** `applySideEffectsForOpenVoting`'s update (`Election.php:1930`) adds `AND voting_locked = false`. It already runs inside `transitionTo`'s transaction.
- **Scheduled path:** `enforceVotingLock` is wrapped in `DB::transaction` and uses the same conditional update. **It serves two call sites** (G-A1): the **auto-opening** (`ProcessElectionAutoTransitions.php:112`) and the **lock after an ended window that was never opened** (`:136`). The conditional update applies to both.
- **The winner:** only the transaction whose update changed **1 row** continues with the policy capture (ADR-B §3.3). 0 rows means another path already opened voting: nothing is captured, and no error is raised to the scheduler; the explicit path's transition guard refuses the duplicate anyway.

**Why this serialises (READ COMMITTED):** both the System B write and the opening update are single `UPDATE … WHERE … voting_locked = false` statements on the same row. The second statement waits for the first transaction's row lock, then re-evaluates its `WHERE` against the committed row.

| Interleaving | Outcome |
|---|---|
| Opening commits first | the System B update re-checks, finds `voting_locked = true`, changes 0 rows, and the request is refused |
| System B write commits first | the opening update proceeds; the capture (same transaction) reads the committed N |
| Manual and scheduled opening together | exactly one changes 1 row and captures |

### 3.3 Write-path protection (no bypass)
- **Model saves:** `Election::saving` refuses **any** dirty `max_votes_per_ip` or `network_binding_strategy` on an **existing** election, regardless of state (`LogicException`). Inserts (creation) are allowed, and the creation snapshot (`static::created`) is unchanged.
- **Builder `update()`, `updateQuietly()`, raw `DB::table`/SQL:** a database-free fitness test (style of `tests/Architecture/ClientIpHasOneSourceTest.php`) scans `app/`, `routes/`, `bootstrap/`, `config/` and `database/` (excluding migrations that add the columns and the backfill of ADR-B). It fails on any write of these two columns outside `writeConstitutionalLimit`.
  - **Known limit:** dynamic column names and manual database access aren't detected.
- **Database trigger, separate Principal decision ADR-A-T (pending):** a PostgreSQL `BEFORE UPDATE` trigger refusing a change of either column when `voting_locked = true` would also cover direct database edits.

  | ADR-A-T option | Consequence |
  |---|---|
  | **Deferred** (recommendation) | The application protocol + fitness test cover every application path. Direct database edits stay uncovered, as for System A today. No second, database-resident rule to keep in step with migrations and tests. Revisit if a manual or tooling write path appears. |
  | **Required now** | Also covers direct database edits. It needs a migration (under G-MIG), a test that the trigger refuses, and the backfill (ADR-B §3.6) must not update these columns (it doesn't). |

  **Approving this ADR does not decide ADR-A-T;** both answers are compatible with §3.1–3.2.

  **ADR-A-T decision rule: APPROVED by the Principal on 2026-10-14.**
  - **Deferral only with confirmed controls:** the trigger is **deferred only if operations confirms** the controls over direct production database writes:
    - which roles can write `elections`;
    - that only the application and the migration runner do so;
    - how ad-hoc SQL is controlled and audited.
  - **Otherwise required:** the trigger is **required** and must be part of the migration design (G-MIG).
  - **Not yet satisfied:** the operational condition is **NOT satisfied**; no confirmation has been received.

### 3.4 What stays unchanged
- `Election::SECURITY_POLICY_FIELDS` and the `isLocked()` lock: System A and the other security settings keep their current rules.
- `settingsFrozen` (all settings frozen while VotingActive).
- The constitutional creation snapshot and its immutability.
- Authorization: `manageSettings` (active chief/deputy; commissioners excluded, AUTH).

### 3.5 A-2: elections already storing `none` or `ip_strict` (configuration side; resolution required by the Principal)
**Fact:** no application path can have written these values, so they exist only through direct database edits or test fixtures [V/I]. Proposed resolution:

| Case | Proposed behaviour | Reason |
|---|---|---|
| **Not yet opened, stored `ip_strict`** | Valid. The live policy treats it as `ip_count` (`TrustPolicyEvaluator.php:145`). The next System B write (§3.1) stores `ip_count`. | Same enforcement meaning; no silent change. |
| **Not yet opened, stored `none`** | **Opening voting is refused** until System B is configured. The explicit path's guard and the scheduled **auto-opening** path (`:112`) refuse with "The constitutional limit must be configured before voting can open." **The lock after an ended, never-opened window (`:136`) is NOT refused** (G-A1): it proceeds and **reports** the stored `none` (`voting_audit` warning), because refusing would leave an ended election unlocked indefinitely and no voting took place. **A System B save in the panel writes `ip_count`**, which clears it. | D11 makes System B mandatory; opening with `none` would remove it for the whole voting period. Refusing is explicit and fail-safe; silently rewriting the value at opening would be a hidden change. |
| **Already opened** (stored before deployment) | **ADR-B §3.6a:** the backfilled record preserves the historical value as evidence, only for elections whose voting closed before deployment (null-safe pre-flight, ADR-B §3.6b). **Preserving it is not an approval of `none`;** no newly opened election can carry `none`. Pending decision **A-2b**. | — |

**Alternatives for "not yet opened, stored `none`"** (not chosen):
- (ii) normalise to `ip_count` at opening, with an audit entry: silent from the committee's view;
- (iii) honour `none`: contradicts D11.

**A-2: APPROVED by the Principal on 2026-10-14: row 2 = refuse opening.** It applies to the explicit `open_voting` transition and the scheduled **auto-opening** (`ProcessElectionAutoTransitions.php:112`). **G-A1 (approved 2026-10-14):** the after-end lock (`:136`) is not refused; it reports the stored `none`. It becomes effective with ADR-A, which itself stays blocked until O-3.

### 3.6 Transactional failure behaviour (r2)

| Failure | Behaviour |
|---|---|
| **Settings request: System B update changes 0 rows** | the request's transaction is rolled back (no field of the request persists); error "The constitutional limit can't be changed after voting has opened."; **no** `settings_changed` audit entry |
| **Settings request: any database error** | rolled back; the existing error handling applies; no audit entry |
| **Settings request: success** | committed; `settings_changed` is written **after commit** (`DB::afterCommit`), so a rolled-back request never produces a settings audit entry. Today `logSettingsChange` runs without a transaction (`ElectionSettingsController.php:260-269`). |
| **Opening (explicit): the conditional update changes 0 rows** | the transition guard has already refused a duplicate; if it still occurs, the `transitionTo` transaction rolls back and the state isn't changed |
| **Opening: capture fails** (ADR-B §3.3) | the opening transaction rolls back; `voting_locked` stays `false`; **voting doesn't open** |
| **Opening (scheduled): 0 rows or a failure** | the transaction rolls back; logged to `voting_audit`; the next scheduler run retries |
| **Model guard triggered** (`LogicException`) | the caller's transaction (if any) rolls back via its existing error handling; nothing persists |

## 4 · Options considered
| Option | Description | Verdict |
|---|---|---|
| (a) | **Conditional updates + model guard + fitness test** (this ADR) | **proposed** |
| (b) | (a) + database trigger | separate decision **ADR-A-T** (§3.3); recommended: deferred |
| (c) | `SELECT … FOR UPDATE` in the settings path, then a check-then-write | correct, but two statements where one suffices; easier to get wrong |
| (d) | add the fields to `SECURITY_POLICY_FIELDS` (lock from ReadyForVoting, F-a) | rejected by D1: forbids changes R2 allows |

## 5 · Invariants
- **I-A1:** after `voting_locked = true`, no application path changes `max_votes_per_ip` or `network_binding_strategy`.
- **I-A2:** a System B write and an opening on the same election are serialised; the captured value is the last committed value before opening.
- **I-A3:** the configuration path never writes `none` (D11).
- **I-A4:** a settings request whose System B values are unchanged never touches System B (H6).
- **I-A5:** each settings request increments `settings_version` exactly once (H7), with the controller's absolute assignment as the only writer (G-A2).
- **I-A6:** System A and every other security setting behave exactly as before.

## 6 · Consequences
- **New:** one model method; a transaction around the settings save; conditional opening updates; a transactional `enforceVotingLock`; a model guard; a fitness test; the A-2 opening refusal.
- **Unchanged:** System A, the lifecycle states, authorization, the creation snapshot.
- **Tests (plan P1, P2):**
  - **State:** the model guard; the HTTP refusal after opening; other settings saved after opening (H6); the version +1 (H7).
  - **Concurrency, two connections** (`lock_timeout` technique as in `IpVoteLimitAtCastTest`):
    - connection 2 holds an uncommitted opening (row locked, flag set), so connection 1's System B update times out (proves it waited); after the commit, a retry changes 0 rows;
    - the reverse order shows the committed value is what's stored;
    - manual + scheduled → one winner.
  - **Write paths:** the fitness test.
  - **A-2:** an election storing `none` can't open until configured; `ip_strict` behaves like `ip_count`.
- **Proof boundary:** a model guard or a single-process test proves state protection only. Concurrency protection is claimed only after the two-connection tests pass.

## 7 · Anonymity-boundary assessment
- **Data touched:** election-level configuration only. No voter, ballot or vote data is read or written.
- **Anonymity:** no effect on vote anonymity (votes and results unchanged).

## 8 · Open questions / approval conditions
1. **O-3 (operations), blocking:** confirm the production PostgreSQL default isolation level is READ COMMITTED (or stricter). If it is SERIALIZABLE, the protocol stays correct, but serialisation failures must be retried; this ADR would then need a retry clause (r3). **No approval of this ADR before O-3.**
2. **ADR-A-T:** decision rule APPROVED 2026-10-14 (§3.3). Deferred only with the operations confirmation of write controls; otherwise required. **The confirmation is outstanding.**
3. **A-2, §3.5 row 2:** APPROVED 2026-10-14 (refuse opening while `none` is stored, both opening paths).
4. **A-2b:** APPROVED WITH CONDITIONS 2026-10-14 in ADR-B §3.6a.

## 9 · Recommendation
**After O-3:** approve option (a) with the A-2 resolution of §3.5 and the failure behaviour of §3.6. **Decide ADR-A-T separately.**

**Traceability:** PBDIGIT-286 · R2, D1, D11, H6, H7, AUTH · decision record r3 §4.1 · plan r3 P1, P2, AC-1 to AC-5.
