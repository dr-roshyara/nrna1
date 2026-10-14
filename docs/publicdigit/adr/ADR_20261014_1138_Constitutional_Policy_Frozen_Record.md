# ADR-B: Frozen constitutional-policy record, policy source, hash, fixtures and backfill

**Status: ✅ APPROVED CONDITIONALLY (G-ADR-B) by the Principal on 2026-10-14, as r2.**
- **Conditions:**
  - A-2 (ADR-A §3.5, approved);
  - **A-2b** approved with conditions (§3.6a);
  - the **empty-end-time block** for **any election** (§3.6b, approved);
  - every fail-closed rule (§3.4) and every release requirement (§3.6, §3.6b) stays intact.
- **Approves the design only.** The migration and backfill still need **G-MIG**, tests need **G-ISO**, release needs **G-REL**. **No implementation authorised.**
- **Earlier status:** PROPOSED r2, awaiting Principal approval. r2: the explicit historical-`none` exception (§3.6a) and the null-safe release pre-flight (§3.6b), after the Principal review of 2026-10-14.
- **Drafted under:** P0 of the PBDIGIT-286 plan (G-PLAN approved 2026-10-14).
- **The migration and the backfill additionally require G-MIG.**
- **Authorises nothing:** no code, tests, migrations, backfill or database access until G-ADR-B, G-MIG **and** G-ISO.

**Created:** 2026-10-14 · **Ticket:** PBDIGIT-286 · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/`.

**Consumes:**
- decision record r3 (§3.2: D8, D8-L, D8-L-S, H1, H2 = F1, H4, AUD-1; §4.2–4.8; §8);
- plan r3 (P3, P4, R; AC-6, AC-13, AC-14, AC-15);
- **ADR-A** (the conditional opening update produces the single "winner" that captures).

**Principal confirmation already given (2026-10-14):** **A-1:** `election_id` is a UUID string and is serialised as a **JSON string** in the canonical JSON.

> **What this ADR protects, in one line:** *For every opened real election, exactly one immutable, hash-verified record holds the System B policy that was fixed when voting opened, and voting never proceeds on a missing or altered record.*

---

## 1 · Context (facts, verified in source)
- **The creation snapshot** (`security_articles_snapshot`, `constitutional_hash`) is written once in `Election::created` (`Election.php:2379-2397`) and is immutable (`:2300-2313`). Nothing reads or validates it.
- **Enforcement reads live columns** (`TrustPolicyEvaluator.php:143-145`).
- **The security assessment runs before the votability check** (`CodeController.php:718` → `:727`) and for **every** election type at the vote (`VoteController.php:1491`). `canVote` is true only in VotingActive (`ElectionLifecycleEngineImpl.php:257-259`).
- **Election IDs are UUID strings** (`Election.php:40-43`).
- **Elections are tenant-scoped** (`BelongsToTenant`, `organisation_id`).
- **`ElectionFactory`:** the default `type` is random demo/real (`database/factories/ElectionFactory.php:34`). `inVotingState()` sets `voting_locked = true` directly (`:180-185`). About 30 test files rely on it.
- **`election_security_events`** requires trust-evaluation columns (`network_evidence`, `policy_evaluated`, … NOT NULL; `2026_05_26_000001…:13-27`), so it doesn't fit a non-evaluation event.
- **`ElectionAuditService`** writes JSONL files outside database transactions.
- **A `voting_audit` log channel exists** (`config/logging.php:122`).

## 2 · Decision drivers
- **D8 = S2:** an append-only record captured atomically when voting opens; the authoritative source for opened real elections; no silent fallback.
- **D8-L = (a), D8-L-S = (i):** a reviewed, idempotent backfill of every election with `voting_locked = true` before activation. **Invariant:** `voting_locked = true` (real) ⇒ the record exists.
- **H1:** before opening, use the live configuration; demo uses the live path; an opened real election uses the record, failing closed.
- **H2 = F1:** a test-only fixture record, never treated as protocol proof.
- **H4 + A-1:** a canonical hash with the election ID as a JSON string.
- **AUD-1:** after-commit audit and reconciliation, owned by P3.

## 3 · Decision (proposed)

### 3.1 Table `election_constitutional_policies` (additive migration; G-MIG)

| Column | Type | Rule |
|---|---|---|
| `id` | uuid, PK | — |
| `election_id` | uuid, FK → `elections.id`, **unique** | one record per election |
| `organisation_id` | uuid, not null | tenant isolation (`BelongsToTenant`) |
| `format_version` | smallint, not null | `1` (the hash's `v`) |
| `max_votes_per_ip` | integer, not null | as captured |
| `network_binding_strategy` | string(30), not null | as captured (A-2: never rewritten) |
| `policy_hash` | char(64), not null | lowercase hex SHA-256 (§3.2) |
| `captured_by` | string(24), not null | `opening` (explicit `open_voting`) · `scheduled` (scheduled auto-opening) · `scheduled_after_end` (lock after an ended, never-opened window; G-A1) · `backfill` · `test_fixture` |
| `captured_actor_id` | uuid, nullable | the officer for `opening`; null otherwise |
| `captured_at` | timestamptz, not null | — |

- **No `updated_at`.**
- **Immutable:** the model refuses `update` and `delete` (`LogicException`). A fitness test (as in ADR-A §3.3) forbids builder or raw writes outside the capture, backfill and fixture code.
- **Holds no voter, ballot or vote data.**

### 3.2 Canonical hash (H4, A-1)
**One pure-PHP domain builder**, `App\Domain\Election\Constitution\ConstitutionalPolicyHash` (name indicative), is the **only** code that serialises or hashes. Capture, backfill, the test fixture and runtime verification all call it.

- **Input:** `format_version` (int), `election_id` (string), `max_votes_per_ip` (int), `network_binding_strategy` (string).
- **Canonical JSON:** exactly
  `{"v":<int>,"election_id":"<uuid>","max_votes_per_ip":<int>,"network_binding_strategy":"<string>"}`
- **Serialisation rules:**
  - keys in this order, no whitespace, UTF-8;
  - integers as JSON numbers without sign, leading zeros or exponent;
  - `election_id` as a JSON **string**, the canonical **lowercase** hyphenated UUID (as PostgreSQL returns it);
  - `network_binding_strategy` as a JSON string;
  - no escaped slashes (all values are ASCII).
- **Implementation:** `json_encode([...ordered array...], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)`, after the builder has validated the types and lower-cased the UUID.
- **Hash:** `hash('sha256', $canonicalJson)`, as lowercase hex.
- **Tests (database-free, P3):** a fixed known input produces a fixed expected string and hash (a golden vector); a mixed-case UUID gives the same result as lowercase; wrong types are rejected.

### 3.3 Capture when voting opens (atomic)
- **Who captures:** only the transaction whose conditional opening update changed **1 row** (ADR-A §3.2), in **both** opening paths.
- **Where:** in the same transaction, next to the existing side effects in `Election` (`applySideEffectsForOpenVoting`, `enforceVotingLock`). No new service.
- **How:** it reads the locked row's `max_votes_per_ip` and `network_binding_strategy`, builds the hash, and inserts the record. `captured_by` depends on the path:
  - `opening` for the explicit `open_voting`;
  - `scheduled` for the scheduled auto-opening;
  - **`scheduled_after_end`** for the lock after an ended, never-opened window (G-A1, approved 2026-10-14).

  The last records **that voting never opened**. A stored `none` is captured **as stored** there (ADR-A §3.5 G-A1), and no vote can follow, since the window has ended.
- **Real elections only.** Demo elections don't capture (H1).
- **Failure** (insert error, unique violation, hash error): the transaction rolls back and **voting doesn't open**.
  - **Explicit path:** error to the officer.
  - **Scheduled path:** logged to `voting_audit`, retried on the next run.
- **A-2 (capture side):** the stored strategy is captured **as stored**. Under ADR-A §3.5 row 2, an election storing `none` can't reach this point, because opening is refused first.

### 3.4 Policy source and failure handling (H1)
**A read port**, `ConstitutionalPolicySource` (Application layer; Eloquent adapter in Infrastructure), returns a pure value object `ConstitutionalNetworkPolicy { maxVotesPerIp, strategy, source: live|frozen }`. `TrustPolicyEvaluator::buildNetworkEvidence` uses it instead of reading the columns directly.

| Case | Source | On failure |
|---|---|---|
| Real election, `voting_locked = false` | **live** columns | — (no vote possible; the assessment must not fail) |
| Demo election | **live** columns | — |
| Real election, `voting_locked = true`, record present, hash valid | **frozen** record | — |
| Real election, `voting_locked = true`, **no record** | — | **integrity failure** |
| Real election, `voting_locked = true`, **hash mismatch** | — | **integrity failure** |

**An integrity failure** throws `ConstitutionalPolicyIntegrityFailure`. The existing fail-closed paths refuse:
- code entry (`CodeController.php:728-729`);
- the vote (`VoteController` outer `catch` → `failCastClosed`).

Each failure also emits:
- a **forensic** log entry on the `voting_audit` channel at `critical` level: event `constitutional_policy_integrity_failure`, election ID, kind (missing/mismatch), stored vs recomputed hash prefix; **no IP, no voter identity**;
- an **admin-facing** election audit entry of the same name;
- **an alert**, the `critical` log level. Routing alerts to operations is an operational setting [U].

`election_security_events` is **not** used (its schema requires evaluation columns, §1).

### 3.5 Audit (AUD-1)
- **`constitutional_policy_frozen`:** written to the election audit through `DB::afterCommit()`, so it is written **only** when the opening commits. Metadata: `max_votes_per_ip`, `network_binding_strategy`, `policy_hash`, `captured_by`. No IP, no voter data.
- **Reconciliation (P3, task T-6):** an Artisan command, `elections:reconcile-constitutional-policy-audit` (indicative). It is **dry run by default** and lists frozen records whose `constitutional_policy_frozen` entry is missing from the election's audit; with `--apply` it re-emits them, marked `reconciled: true`. **The frozen record is the authoritative evidence;** the file entry is derived.

### 3.6 Backfill (D8-L = (a), D8-L-S = (i); design for G-MIG)
An Artisan command, `elections:backfill-constitutional-policies` (indicative):

| Aspect | Rule |
|---|---|
| **Data scope** | every **real** election with `voting_locked = true` and no record (D8-L-S (i)) |
| **Record generation** | the live columns at backfill time; `captured_by = backfill`; `captured_at = now()`; the same hash builder (§3.2) |
| **Idempotency** | an election that already has a record is skipped and reported, **never rewritten** |
| **Dry run** | `--dry-run` (the **default**) lists every election it would write, with values and hash, and writes nothing |
| **Inconsistency report** | reports, **without changing**: stored strategy `none` or `ip_strict` (**A-2**, §3.6a); `max_votes_per_ip` outside [1, 50]; **null `voting_ends_at`** (**blocks activation**, §3.6b); missing creation snapshot; demo elections with `voting_locked = true` (skipped) |
| **Failure handling** | one transaction per election; on any error it stops and reports; partial progress is safe because of idempotency |
| **Verification** | after `--apply`: count(real elections with `voting_locked = true`) = count(their records); the invariant check is part of the G-REL pre-flight |
| **Recovery / rollback** | **before activation:** backfilled rows (`captured_by = backfill`) can be deleted by a reviewed, dedicated command, the only sanctioned delete. **After activation:** authoritative; never removed |
| **Execution** | only under G-MIG + G-REL, by operations, before the code that requires records is active (record §5) |

### 3.6a A-2 backfill side: historical `none` is preserved, not approved (revision r2, Principal finding 2)
**Two different cases:**

| Case | Rule | Source |
|---|---|---|
| **A not-yet-opened election storing `none`** | **May not open** until System B is configured (a System B save writes `ip_count`). `none` is **never** a valid policy for a newly opened election. | ADR-A §3.5 |
| **An election opened before deployment, storing `none`** | Its backfilled record **preserves the historical stored value** as evidence of what was in force while it was voting. **This preservation is not an approval of `none`** and creates no precedent for new elections. | this section |

**When preserving it is safe.** All of these must hold, and each is checked at release:
1. **Its voting window had ended before deployment.** The release pre-flight (§3.6b) admits only opened elections with a **non-null** `voting_ends_at <= now()`.
2. **Voting can't resume through the application [V]:**
   - `voting_locked` never resets;
   - voting dates are editable only while `voting_locked = false` (`canUpdatePhaseDates('voting')`, `Election.php:979`);
   - Counting has `canEditTimeline = false` (`ElectionLifecycleEngineImpl.php:264-270`);
   - extending the end time requires VotingActive (`extendVotingEndTime`).
3. **Every one is reported** by the backfill: ID and stored strategy, listed in the release evidence.
4. **Not covered:** direct database edits that reopen such an election. They are outside every application guarantee, as in ADR-A §3.3.

**A-2b: APPROVED WITH CONDITIONS by the Principal on 2026-10-14.** Historical `none`/`ip_strict` values are preserved in backfilled records **only** for elections **demonstrably closed before deployment**, and **only** when **all** conditions 1–3 hold. **This is not an approval of `none` as a policy;** no newly opened election may carry it (A-2).
- **Alternative (b):** block activation while any opened election stores `none`, and decide each case individually.
- **Alternative (c):** record `ip_count` instead. **Rejected:** it would rewrite a closed election's constitutional history.

### 3.6b Release pre-flight and the invariant: null `voting_ends_at` (revision r2, Principal finding 3)
**The gap:** the earlier pre-flight condition `voting_locked = true AND voting_ends_at > now()` does **not** match rows with a null `voting_ends_at`, so such rows would pass silently. Null is also unsafe: `hasVotingEnded()` returns **false** for a null end (`ElectionLifecycleEngineImpl.php:183-186`), so whether such an election can still vote can't be established from its data [U]. The application normally prevents it (opening requires an end time, `validateOpenVoting`; closing sets one), so only direct edits or fixtures produce it.

**Corrected pre-flight condition (activation blocker):**
> **No election (any type) with `voting_locked = true AND (voting_ends_at IS NULL OR voting_ends_at > now())`.**

**Rules:**
- **Null end time:** any opened election with a null `voting_ends_at` **blocks activation**. Operations must provide, per election: ID, type, lifecycle state, `voting_locked_at`, the time of the last completed vote, and the evidence that no vote can be cast. **Activation proceeds only after the Principal signs off each such election individually.** The backfill lists them; it never infers an end time.
- **Invariant check after the backfill:** every **real** election with `voting_locked = true` has exactly one record. A mismatch **blocks activation**.
- **Order:** pre-flight (null-safe) → backfill dry run reviewed → backfill applied → invariant check → activation (decision record §5).

**Empty-end-time release rule: APPROVED by the Principal on 2026-10-14, any election.**
- The condition above applies to **every election type**, demo included.
- Leaving demo elections out of the backfill is **no** reason to narrow it.
- **Narrowing to real elections needs** all of E-D1 to E-D5 below **and a separate Principal decision**:

| # | Evidence required before any narrowing | Source |
|---|---|---|
| E-D1 | demo voting writes only to demo tables (`demo_codes`, `demo_votes`), never to `codes` or `votes` | code review (read-only) |
| E-D2 | demo elections are excluded from both IP controls and System B's gate along every cast and code-entry path | code review |
| E-D3 | no path converts a demo election into a real one or back (otherwise an opened real election could lack a frozen record) | code review + operations |
| E-D4 | an inventory of production demo elections matching the condition | operations |
| E-D5 | demo and real elections in one organisation share no security settings or counters | code review |

**Known interaction with PBDIGIT-289** (not PBDIGIT-286 scope; nothing here fixes it):
- **The gap:** the scheduled job `ProcessElectionAutoTransitions::processNominationToVotingTransition` (`:80-118`) can set `voting_locked` **without a voting window** (no `voting_window_defined` check) [V].
- **At deployment:** the pre-flight above catches such elections, and **it must pass**.
- **After deployment:** the job could create one again. It would take no votes and would capture a record from the live values (backlog `PBDIGIT-289`).
- **G-REL must state** either that **PBDIGIT-289 is fixed**, or that it is **accepted as a documented known risk**. In either case, operations reviews production `allow_auto_transition` usage and the scheduled-transition behaviour.

### 3.7 Test fixtures (H2 = F1)
- **What:** `ElectionFactory::inVotingState()` gets an `afterCreating` callback. **Only for `type = real`**, it inserts a record with `captured_by = test_fixture`, using the shared builder (§3.2).
- **Explicitly not proof:** the fixture is **not** evidence that the opening protocol works. AC-5 and AC-6 test the real opening paths (`transitionTo('open_voting')`, `enforceVotingLock`) on their own.
- **Order:** introduced as its own RED → GREEN step **before** the policy source becomes fail-closed (plan P3 T-4 before P4), with the full isolated suite green after it.
- **Fixtures that bypass the factory:** any test that sets `voting_locked = true` directly (`DB::table` or `forceFill`) instead of through `inVotingState()` gets the record through a small shared test helper calling the same builder. The P3 step first lists these files by search. Which files exist is [U]; the isolated PBDIGIT-274/288 tests use the factory.

## 4 · Options considered
| Option | Verdict |
|---|---|
| S2 table + one hash builder + live/frozen source (this ADR) | **proposed** (D8 approved S2) |
| S1: rewrite the creation snapshot at opening | rejected (D8): breaks "immutable after creation"; history lost |
| S3: frozen live columns only | rejected (D8): no integrity evidence |
| Reuse `election_security_events` for integrity failures | rejected: schema requires evaluation columns (§1) |
| A separate capture service | rejected unless needed: capture sits beside the existing opening side effects |

## 5 · Invariants
- **I-B1:** real election with `voting_locked = true` ⇒ exactly one record (after the backfill).
- **I-B2:** a record is never updated or deleted, except the reviewed pre-activation backfill rollback.
- **I-B3:** every hash is produced and verified by the single builder (§3.2).
- **I-B4:** an opened real election is never evaluated from live columns; a missing or mismatching record refuses.
- **I-B5:** before opening, and for demo elections, the assessment never fails because a record is absent.
- **I-B6:** `constitutional_policy_frozen` never claims an uncommitted freeze.
- **I-B7:** no record, log entry or audit entry carries an IP address or voter identity.

## 6 · Consequences
- **New:** one table (additive), one model, one hash builder, one read port + adapter, one value object, an integrity exception, two Artisan commands (backfill, reconciliation), a factory callback + test helper.
- **Unchanged:** the creation snapshot; System A; admission.
- **Tests (plan P3, P4, R):**
  - **Hash:** the golden vector.
  - **Capture:** in both paths; capture failure → not opened; manual + scheduled → one record; record immutable.
  - **Audit and reconciliation:** after-commit audit; reconciliation dry run and apply.
  - **Fixtures:** F1 fixture; the full isolated suite green after F1.
  - **Policy source:** frozen vs live; missing → refusal + forensic + audit; mismatch → same; pre-opening and demo assessments don't fail.
  - **Backfill:** idempotent, dry run, report, invariant check.
- **Rollback:** revert the code; the table stays; the pre-activation backfill rollback per §3.6.

## 7 · Anonymity-boundary assessment
- **No voter data:** the record and its events hold election configuration only. No voter, vote, ballot, IP or code data.
- **Unchanged:** `votes` and `results` (no user ID).
- **No new link** between voters and votes.

## 8 · Open questions / approval conditions
1. **A-2b (§3.6a):** APPROVED WITH CONDITIONS, 2026-10-14.
1a. **Empty end times (§3.6b):** APPROVED, 2026-10-14. Blocks activation for any election; individual sign-off; narrowing only after E-D1 to E-D5 plus a separate decision.
2. **Table and command names** are indicative. Approve or rename.
3. **Alert routing** for the `critical` integrity log is operational [U]. Operations confirm a destination before G-REL.
4. **G-MIG:** the migration file and this backfill design are approved separately.

## 9 · Recommendation
**Approve §3.1–3.7** with A-2 per §3.3, A-2b per §3.6a, and the null-safe pre-flight per §3.6b. Then G-MIG for the migration and backfill design.

**Traceability:** PBDIGIT-286 · R2 · D8, D8-L, D8-L-S, H1, H2 = F1, H4, A-1 (confirmed), A-2, AUD-1 · decision record r3 §4.2–4.8 · plan r3 P3, P4, R; AC-6, AC-13, AC-14, AC-15.
