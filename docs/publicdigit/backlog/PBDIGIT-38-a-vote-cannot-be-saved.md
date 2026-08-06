# PBDIGIT-38 — A vote cannot be saved: a security-audit write blocks the business operation

**Type:** Defect (blocking) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` part (b) — the end-to-end journey walk

| | |
|---|---|
| **Status** | ✅ **FIXED AND VERIFIED 2026-08-06** — implemented per `ADR_20260806_1340` (APPROVED). **Ready to close** |
| **Customer impact** | **Resolved.** A vote now persists: `demo_votes = 1`, `demo_results = 2`, and the journey completes |
| **Was** | 🔴 Blocking — a voter completed every step and their vote was silently discarded |
| **Residual** | **Invariant I-2 remains unsatisfied**, blocked on **`PBDIGIT-42`** (audit-schema semantics). **Not worked around** |

---

## What was observed

Steps 1–4 of the demo journey all succeed (`code entry → agreement → vote form → verify`). Then:

```
POST /v/{slug}/demo-vote/final   ->  302 back to /demo-vote/verify
demo_votes   = 0
demo_results = 0
```

**No vote row. No result row. No error shown to the voter.**

## Root cause — a NOT NULL column nobody writes

```
SQLSTATE[23502]: Not null violation:
  null value in column "overlay_influence_chain" of relation "election_security_events"
  violates not-null constraint
     ↓
SQLSTATE[25P02]: In failed sql transaction: current transaction is aborted
     ↓
❌ EXCEPTION in store() - Vote submission failed
```

| Fact | Evidence |
|---|---|
| The column is `NOT NULL` with **no default** | `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23` — `$table->json('overlay_influence_chain');` (Laravel's `json()` without `->nullable()`) |
| Every **sibling** overlay column *is* nullable | `overlay_applied`, `overlay_signal_type` — `is_nullable = YES` |
| The insert never supplies it | `app/Application/Election/Security/SecurityEventRecorder.php:52` — the `create([...])` array has no `overlay_influence_chain` key |
| It is in `$fillable` and cast to `array` | `app/Models/ElectionSecurityEvent.php:29,44` — so the intent was clearly to write it |

**So the model declares the field, the schema demands it, and the recorder forgets it.**

## Why the failure is worse than the bug

**The security event is recorded inside the vote's transaction.** So an *audit* write failure aborts the *business* operation:

> **A mechanism that exists to observe voting prevents voting.**

That is an architectural point, not a typo: an observation path must not be able to fail the path it observes. **Whether the recorder should participate in the vote transaction at all is the real design question** — and it is a bigger question than the missing column.

## ✅ Scope was wider than demo — CONFIRMED 2026-08-06

`SecurityEventRecorder` is **not** demo-specific; its caller is `TrustPolicyEvaluator`, on the shared trust-evaluation path. **When this story was written that made real elections "likely affected", and the story said so without asserting it. It has now been established:**

| | `VoteController::store()` (real) | `DemoVoteController::store()` (demo) |
|---|---|---|
| transaction opens | `:1515` | `:1456` |
| `trustEvaluator->evaluate()` | `:1542` | `:1493` |
| commit | `:1892` | `:1785` |

**Identical shape.** So this was not *"demo mode is broken"* — **the product could not hold an election at all.**

**Both paths are fixed by the same change, and this is why the binding point mattered:** the connection is bound at the **model**, which both paths share, so no second edit was required and none was made. *(A call-site binding in `DemoVoteController` would have fixed demo and left real elections broken — while appearing to fix "the" bug.)*

---

# OUTCOME — implemented and verified 2026-08-06

**Architecture decision:** [`ADR_20260806_1340_Audit_Event_Transaction_Boundary`](../adr/ADR_20260806_1340_Audit_Event_Transaction_Boundary.md) — **APPROVED**, then implemented exactly as recorded. **No deviations.**

**Four functional lines of production change:**

| Change | Where |
|---|---|
| `pgsql_audit` connection — same database, separate PDO connection, inheriting `DB_*` so no environment needs new configuration | `config/database.php` |
| `protected $connection = 'pgsql_audit';` — **the line that makes the config live** | `app/Models/ElectionSecurityEvent.php` |
| `trust_state_transition` now written *(no semantics invented — both endpoints were already recorded)* | `SecurityEventRecorder` |
| `\Exception` -> `\Throwable` | `SecurityEventRecorder` |

**Verified:**

* ✅ **The vote persists** — `demo_votes = 1`, `demo_results = 2`.
* ✅ **I-1 proven by the real failure, not a mock:** the audit `INSERT` still fails (`overlay_influence_chain`, pending `PBDIGIT-42`) and **the vote survived**. `25P02` occurrences in that request's own log: **0**.
* ✅ 🔒 **Anonymity holds** (ADR-T11) — no linkage column, no stored value equal to the voter's `user_id`, and the indirect `voting_code` join returns zero rows.
* ✅ **The journey completes** — the final submission redirects to `verify-show`, which renders **200**.
* ✅ **The real path is fixed by the same change** — `VoteController::store()` has the identical shape, and the binding is at the model, so no second edit was needed.
* ⛔ **I-2 still blocked on `PBDIGIT-42`** — no audit row can be written at all.
* 🧪 `tests/Feature/AuditEventTransactionIsolationTest.php` — 3 tests, 6 assertions, green.

**Discovered while verifying, recorded not fixed:** `demo_votes.voting_code` is never populated -> **`PBDIGIT-43`**, which also carries the Product Owner's rule that the code must be derived from `vote_id` and never from `code_id` *(an anonymity rule, not a naming preference)*.

**Correction issued:** `PBDIGIT-40`'s severity was **overstated** — `thank-you` is reachable and broken, but it is **not** on the voting happy path. Downgraded to Low, with the reasoning recorded there.

---

# DISCOVERY — 2026-08-06 (commissioned before any implementation)

**A first patch was proposed and rejected by the Product Owner for committing to an implementation (a nested transaction / SAVEPOINT) before the transaction boundary had been established.** That was the right call, and the discovery below **changed the answer** — the rejected implementation would not have been sufficient.

## D-1 · Who starts the transaction, and what is inside it

| Line | Event |
|---|---|
| `DemoVoteController:1456` | **`DB::beginTransaction()`** — the controller opens it |
| `DemoVoteController:1493` | `$this->trustEvaluator->evaluate(…)` — **37 lines later, inside the transaction** |
| `TrustPolicyEvaluator:68` | `$this->eventRecorder->record(…)` — called from inside `evaluate()` |
| `DemoVoteController:1785` | `DB::commit()` |

> **No explicit design evidence was found that audit recording belongs to the Vote transaction. It currently participates because it is invoked after the transaction begins.**

**That is the whole claim, and it is deliberately narrow.** Nothing in the migration, the recorder, the evaluator or the controller states a decision either way — and the absence of evidence is not evidence of absence. *(An earlier draft of this section said "nobody decided" and "the participation is accidental, not designed." Both were **inferences about intent** that the repository cannot support. What can be shown is the call order; what cannot be shown is what anyone meant.)*

**Either way, the boundary question must be settled rather than patched** — because the observable behaviour depends on the boundary, not on the intent.

## D-2 · Is the Security Event inside the Vote consistency boundary? — **No**, and the code proves it

| Evidence | Where |
|---|---|
| *"**Fire-and-forget** audit recording — observation semantics only"* · *"**Invariant: … Never affects trust outcome**"* | `SecurityEventRecorder:15-17` |
| 🔑 **ALLOW events are ~10% sampled** — deliberately discarded 90% of the time | `SecurityEventRecorder:26-28` |
| Independent lifecycle: `retention_days` default **730** | migration `:26` |
| `voter_slug_id` deliberately `null` — no voter linkage by design | `SecurityEventRecorder:55` |

**The sampling is decisive.** A record the system intentionally throws away nine times out of ten **cannot** be part of a transactional invariant. You cannot have a consistency rule that holds 10% of the time.

> **Conclusion: the Security Event is an OBSERVATION of the vote, not a PART of it. It belongs outside the Vote aggregate's consistency boundary.**

## D-3 · 🔴 A second defect the discovery uncovered — deny audits are silently discarded

`evaluate()` runs at `:1493`. **Nine `DB::rollBack()` calls follow it** — `:1517, 1551, 1567, 1593, 1608, 1651, 1668, 1687, 1699`.

**Every one of them rolls back the security event written at 1493.**

But the recorder's stated policy is:

> `SecurityEventRecorder:18` — *"**DENY events: always record immediately**"*

**So for every rejected vote — organisation mismatch, ineligibility, code failure, any of the nine — the security audit row is created and then destroyed with the transaction.** The audit trail is *guaranteed absent* exactly for the events it most needs to capture.

**This is the finding that decides the implementation**, because it means the invariant is **two-directional**:

1. An audit failure must never block a legitimate vote *(the bug that stopped the journey)*.
2. **An audit record must survive a vote rollback** *(the bug nobody had noticed)*.

## D-4 · Options, judged against both directions of the invariant

| Option | (1) Audit failure can't block the vote | (2) Deny audit survives rollback | Cost |
|---|---|---|---|
| **SAVEPOINT / nested transaction** *(the rejected patch)* | ✅ | 🔴 **No** — a savepoint rolls back with its parent | low |
| **`DB::afterCommit` / after-commit listener** | ✅ | 🔴 **No** — denials never commit, so they are never written | low |
| **Independent connection / independent transaction** | ✅ | ✅ | medium |
| **Outbox + async** *(the repo already has `outbox_events` + `EventProvenance`)* | ✅ | ✅ — and survives process death | high |
| Make the columns nullable and change nothing else | 🔴 no — the next audit failure poisons the transaction again | 🔴 No | trivial |

### ⛔ ARCHITECTURE DECISION REQUIRED — discovery stops here

```text
ELIMINATED on evidence (D-3), not on preference:
  · SAVEPOINT / nested transaction   — rolls back with its parent
  · after-commit listener            — never fires for denials
  · nullable columns alone           — fails both directions

REMAIN:
  · independent transaction on its own connection
  · outbox + async  (outbox_events + EventProvenance already exist here)

→ ARCHITECTURE DECISION REQUIRED
```

**No option is recommended.** Both survivors satisfy the invariant in both directions; choosing between them trades durability against change size, and that trade is not engineering's to make (**`R-34`** — evidence, recommendation and authority stay separate; **`ES-002`** — the decision belongs to whoever owns ADRs).

**Both eliminated low-cost options fail the same half of the invariant** — the half that was invisible until D-3. **That is the concrete cost of choosing an implementation before establishing the boundary:** the cheap fix looked adequate against a one-directional invariant, and would have been shipped as a fix while leaving deny audits still silently discarded.

## D-5 · Separately, and regardless of where the write happens — two required columns are never written

**Live-schema measurement, not inspection:** of the `NOT NULL`-without-default columns on `election_security_events`, the recorder omits **two**:

* `overlay_influence_chain` (json) — the failure observed in `PBDIGIT-00`
* `trust_state_transition` (varchar 100) — **would have failed next**

⚠️ **A fix supplying only `overlay_influence_chain` would appear correct in review and fail again at runtime.** *(Later migrations — `2026_05_27_120001_ensure_election_security_events_schema` and `…_add_evaluation_summary_…` — made other columns nullable, which is why only these two remain.)*

**Also unresolved: `overlay_influence_chain` has no meaning anywhere in the codebase.** It appears only in the migration, `$fillable`, and a cast (`ElectionSecurityEvent:29,44`). **Nothing constructs an "influence chain".** So the honest options are to write the ordered overlay identifiers actually observed, or `[]` — **and to decide whether the column should exist at all.** Writing a placeholder into an audit column whose semantics nobody can state is worse than leaving it nullable.

## D-6 · Business context supplied by the Product Owner (2026-08-06)

> **There are two demos: a PUBLIC demo (no login), where results are NOT persisted, and a PRIVATE demo (authenticated), where results ARE persisted — exactly like production.**

**This confirms the defect rather than explaining it away.** `PBDIGIT-00`'s walk was **authenticated** (`admin@publicdigit.org`, full session), so persistence was required and did not happen. *(A `/public-demo/start` route exists, consistent with the two-mode model.)*

⚠️ **Whether the code actually distinguishes the two modes at the persistence boundary is NOT established** — the walk only exercised the authenticated path. **If a single code path serves both, then "public demo must not persist" is an unverified claim about behaviour**, and that is its own discovery, not part of this fix.

## What this discovery deliberately does NOT do

**It proposes no code, and it recommends no option.** The boundary question is answered (D-2), a second defect is recorded (D-3), and three options are eliminated on evidence (D-4). **Two remain, and discovery stops at "architecture decision required."**

**It also claims nothing about intent.** D-1 states the call order, not what anyone meant by it.

---

## Acceptance criteria

* [ ] **An architecture decision on D-4 is recorded before any code is written** — independent transaction, outbox, or something else. **SAVEPOINT and after-commit are eliminated on evidence (D-3), not on preference.**
* [ ] **Both directions of the invariant hold**, and each has its own test: an audit failure does not lose a legitimate vote; a rejected vote still leaves a deny audit record.
* [ ] **Both** missing required columns are written (D-5) — or `overlay_influence_chain`'s existence is decided, since nothing in the codebase gives it meaning.
* [ ] Determine whether the real voting path hits the same failure. **Answer recorded either way.**
* [ ] Whether one code path serves both the public and private demo, and where persistence is suppressed for the public one (D-6). **If the distinction is not implemented, that is a separate story, not this fix.**
* [ ] A vote is persisted end to end: `demo_votes` gains a row, `demo_results` gains rows.
* [ ] **The anonymity invariant is asserted on the saved vote** — no `user_id`, no voter linkage (ADR-T11). *`PBDIGIT-00` could not check this, because no vote row was ever created.*
* [ ] Decide, and record, whether security-event recording belongs inside the vote transaction. **If it stays inside, a recorder failure must still not lose the vote; if it moves outside, the audit gap must be acknowledged.**
* [ ] A regression test that casts a vote and fails if `demo_votes` stays empty — **the test `PBDIGIT-36` says does not exist**.
* [ ] The voter is never returned to a page with no explanation: a failed save must surface as an error, not a silent redirect.

## Explicitly NOT the fix

**Do not simply make the column nullable and move on.** That silences the symptom and leaves two questions unanswered: what `overlay_influence_chain` is *for*, and whether an audit write should be able to fail a vote. If the column is genuinely optional, making it nullable is correct — **but that must be a decision, not a reflex.**

---

**Traceability:** `PBDIGIT-00` §Result (b) · `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23` · `app/Application/Election/Security/SecurityEventRecorder.php:52` · `app/Application/Election/Security/TrustPolicyEvaluator.php` · `app/Models/ElectionSecurityEvent.php:29,44` · `app/Http/Controllers/Demo/DemoVoteController.php` `store()` · `PBDIGIT-36` (the missing regression test) · ADR-T11 (anonymity)
