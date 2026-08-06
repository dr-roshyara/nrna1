# ADR — Transactional isolation of security-audit persistence

**Status: ✅ APPROVED 2026-08-06** (Product Owner, *"approve with one restructuring request"*) — **restructured as requested; implementation authorised for the accepted scope only.**
**Date:** 2026-08-06 · **Origin:** `PBDIGIT-38` (defect) ← `PBDIGIT-00` (runtime verification)
**Decision owner:** Product Owner · **Author:** engineering (evidence and drafting only — `R-34`)

**Scope — one decision, deliberately:** *where security-audit events are persisted relative to the Vote transaction.*

**Explicitly NOT in this ADR:** the **meaning of the audit schema's fields**. That was extracted on review into **[`PBDIGIT-42`](../backlog/PBDIGIT-42-define-audit-schema-semantics.md)** so that approving *transactional isolation* does not implicitly approve *audit semantics* — two unrelated decisions deserving separate review cycles.

---

## 1 · Context — the business invariant at stake

A voter completes every step of the journey and **their vote is silently discarded** (`PBDIGIT-00`, confirmed independently by the Product Owner in a browser: *"I have tested demo election and its working but the problem about saving"*).

**Two business invariants are in play, and they point in opposite directions:**

| # | Invariant | Source |
|---|---|---|
| **I-1** | **A voter's ballot must never be lost because the audit subsystem could not record telemetry.** | the customer's expectation; and `SecurityEventRecorder:17` already asserts *"Never affects trust outcome"* |
| **I-2** | **A rejected vote must still leave a record of why it was rejected.** | `SecurityEventRecorder:18` — *"DENY events: always record immediately"* |

**Both are currently violated.** I-1 by the defect that stopped the journey; I-2 silently, and nobody had noticed.

## 2 · Consistency-boundary analysis

**Question: is `ElectionSecurityEvent` inside the Vote aggregate's consistency boundary?**

**Answer: No.** The evidence is in the code, not in judgement:

| Evidence | Location |
|---|---|
| Declared *"fire-and-forget… observation semantics only"*, *"Never affects trust outcome"* | `SecurityEventRecorder:15-17` |
| 🔑 **ALLOW events are ~10 % sampled** — deliberately discarded 90 % of the time | `SecurityEventRecorder:26-28` |
| Independent lifecycle: `retention_days` default **730** | migration `:26` |
| `voter_slug_id` deliberately `null` — no voter linkage by design | `SecurityEventRecorder:55` |

**The sampling is decisive: a record the system intentionally throws away nine times out of ten cannot participate in a transactional invariant.**

**What is currently true, stated without claiming intent:** *no explicit design evidence was found that audit recording belongs to the Vote transaction. It participates because it is invoked after the transaction begins* — `DemoVoteController:1456` opens it, `:1493` calls `trustEvaluator->evaluate()`, which calls the recorder, and `:1785` commits.

### Architectural classification

> **`ElectionSecurityEvent` is an observational projection of the voting process.** It is **not** part of the **Vote** aggregate, does **not** participate in its consistency boundary, and therefore **must not share its transactional lifetime.**

### The principle this decision applies

> **The Vote aggregate defines the consistency boundary. Transaction boundaries should *implement* consistency boundaries rather than extend beyond them. Because `ElectionSecurityEvent` is observational rather than transactional, its persistence must be decoupled from the Vote transaction.**

**That is the rationale in one sentence, and it is deliberately stated as a principle rather than as a local repair** — the same reasoning applies to any observational write that has drifted inside a business transaction. *(Filed as a repository-independent candidate at n=1: `docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md`. **Not adopted** — one occurrence.)*

## 3 · Alternatives considered

**Three were eliminated on evidence, not preference.**

| Option | I-1 · audit failure can't lose the vote | I-2 · deny audit survives rollback | Verdict |
|---|---|---|---|
| **A** · Make the columns nullable, change nothing else | 🔴 No — the next audit failure poisons the transaction again | 🔴 No | **eliminated** |
| **B** · SAVEPOINT / nested transaction | ✅ | 🔴 **No** — a savepoint rolls back with its parent | **eliminated** |
| **C** · `DB::afterCommit` / after-commit listener | ✅ | 🔴 **No** — denials never commit, so denials are never recorded | **eliminated** |
| **D** · **Independent connection** (same database, separate PDO connection ⇒ separate transaction) | ✅ | ✅ | **survives** |
| **E** · **Outbox + async** (`outbox_events` + `EventProvenance` already exist here) | ✅ | ✅ — and survives process death | **survives** |

⚠️ **Option B was engineering's first proposal and was rejected by the Product Owner before implementation.** The rejection was correct: B satisfies I-1 and fails I-2, and I-2 was only discovered *because* the boundary question was asked first. **Had B been implemented, it would have shipped as a fix while leaving deny audits silently discarded.**

## 4 · Decision — **Option D, independent connection** (approved)

**Selected over E (outbox) for three reasons:**

1. **Proportionality.** E guarantees durability across process death. The record it would protect is **90 % sampled telemetry** — the product deliberately tolerates losing most of it. **A durability guarantee stronger than the data's own retention policy is not justified by the evidence.**
2. **Change size.** E introduces asynchronous delivery, a consumer, and failure/retry semantics into the vote path. D adds one configuration entry and one connection binding.
3. **Reversibility.** D does not foreclose E. If audit durability later becomes a requirement — e.g. a regulator asks for guaranteed retention — the recorder is the single write site and can be pointed at the outbox without touching the vote path.

**What is explicitly NOT claimed:** that D is better than E in general. **E is the stronger design.** D is the proportionate one for this data, today.

## 5 · Where the connection is actually selected — the binding point

**A configuration entry alone does nothing.** There is exactly **one writer** and, importantly, **one reader**:

| Site | Operation |
|---|---|
| `SecurityEventRecorder:52` | `ElectionSecurityEvent::create([...])` — **the only write** |
| `IpVelocityOverlay:38` | `ElectionSecurityEvent::query()` — **a read, during trust evaluation** |

`app/Models/ElectionSecurityEvent.php` declares `$table` but **no `$connection`**, so both currently use the default.

**Two binding options, and the reader decides between them:**

| | Binding | Consequence |
|---|---|---|
| ❌ | `ElectionSecurityEvent::on('pgsql_audit')->create(...)` at the call site | writes move, **reads do not**. Harmless while both point at one database — and **silently wrong the moment an operator uses a host override**: `IpVelocityOverlay` would read an empty table, and **trust decisions would change without any error** |
| ✅ | `protected $connection = 'pgsql_audit';` on the model | reads and writes stay together, permanently. One line, one place |

**Decision: bind at the model.** *(This is why the trace mattered: the call-site binding is the more obvious choice and is the unsafe one.)*

## 6 · Architectural consequences

**These change what the system means, not merely how it is built.**

1. **`election_security_events` becomes one row per *evaluation*, not per vote.** A rolled-back vote now leaves an audit row. **This is intended — it is invariant I-2** — but it is a semantic change to the table: **anyone reading it as "one row per vote" will be wrong.**
2. **Audit persistence no longer shares the Vote's atomicity.** An audit row can exist for a vote that does not, and (in principle) a vote can commit while its audit row failed. **That asymmetry is the deliberate consequence of classifying the event as observational** — it is the price of I-1, and it is the correct price.
3. **The audit store becomes relocatable.** Because reads and writes are bound together (§5), audit traffic can later move to another host or database without touching the vote path.
4. **A stronger guarantee remains available.** Option E (outbox) is not foreclosed; the recorder is the single write site.

## 6b · Implementation notes

**These are build-time cautions, not architectural properties.**

* **An extra PDO connection** is opened per request that records an event (≈10 % of allows, plus all denials).
* **In-transaction read-after-write is lost.** An event written during a request is no longer visible to a read on the default connection in the same transaction. **Checked: no such dependency exists** — `IpVelocityOverlay` reads previously-committed events, and only one event is written per evaluation, at the end.
* ⚠️ **Trap for future tests:** a test relying on transaction rollback will now see audit rows persist. This project's `TestCase` already skips transactions for PostgreSQL and uses `migrate:fresh`, so the impact here is nil — **but the trap is real for any future transactional test.**
* **Pre-existing layer debt, not fixed here and not approved by this ADR:** the Application-layer rules (`.claude/CLAUDE.md` Rule 2) forbid Facades and Eloquent in `app/Application/`. **`SecurityEventRecorder` already violates both** (`Log::`, `ElectionSecurityEvent::create`). Correcting it means introducing a port — a larger change than this defect warrants.

## 7 · Boundary with the audit-schema decision — **extracted, not decided here**

The recorder omits **two** required (`NOT NULL`, no default) columns, measured against the live schema:

| Column | Status for this ADR |
|---|---|
| `trust_state_transition` (varchar 100) | ✅ **in scope** — unambiguous, and no semantics are invented: the recorder already writes `trust_level_before` and `trust_level_after`, so the transition is `'<before>-><after>'` |
| 🟡 `overlay_influence_chain` (json) | ⛔ **OUT of scope → [`PBDIGIT-42`](../backlog/PBDIGIT-42-define-audit-schema-semantics.md)**. It has **no meaning anywhere in the codebase** — a migration, a `$fillable` entry, a cast, and nothing that constructs one |

**Consequence for implementation, stated plainly so it is not mistaken for a failure:** because `overlay_influence_chain` stays unwritten, **the audit `INSERT` will still fail** until `PBDIGIT-42` is decided. **That is now harmless to the voter and is the entire point of this ADR** — the failure is isolated, so:

* **I-1 is satisfied** — the vote persists despite the audit failure;
* **I-2 is NOT yet satisfiable** — no audit row can be written at all, so deny audits remain absent for a *different* reason.

**Engineering does not invent the meaning of an audit field.** Writing a placeholder into an audit column whose semantics nobody can state would be worse than leaving it unwritten and visible.

## 8 · Verification — executed 2026-08-06

* [x] **A vote persists.** `demo_votes = 1`, `demo_results = 2` (two national posts, one candidate each).
* [x] 🔒 **The saved vote carries no voter linkage** (ADR-T11), checked four ways: `demo_votes` and `demo_results` have **no** `user_id`/`voter_id`/`member_id`/`email`/`slug` column; **no stored value equals the voter's `user_id`**; and the indirect path — `demo_votes.voting_code` → `demo_codes.voting_code` → `user_id` — **does not join**, because `demo_votes.voting_code` is `NULL`. *(That null is itself a finding — see `PBDIGIT-43`.)*
* [x] **I-1 verified, and by the real failure rather than a mock.** The audit `INSERT` still fails (`overlay_influence_chain`, pending `PBDIGIT-42`) — and **the vote persisted anyway.** `25P02` occurrences in the request's own log: **0** (was the cause of the lost vote). Regression test: `tests/Feature/AuditEventTransactionIsolationTest.php` — 3 tests, 6 assertions, green.
* [ ] **I-2 — still BLOCKED on `PBDIGIT-42`**, honestly: no audit row can be written while a required column has no defined meaning. **Not worked around** by making the column nullable, which would decide `PBDIGIT-42` in code.
* [x] **The journey completes end to end.** The final submission redirects to `/v/{slug}/demo-vote/verify-show`, which renders `Vote/DemoVote/VerifyVotingCode` (**200**). ⚠️ **Correction to `PBDIGIT-40`:** `thank-you` still 500s, but it is **not on the redirect path**, so it is a reachable-but-unused route — *not* "the last thing a voter sees". That story's severity was overstated and has been corrected.
* [x] **The real voting path shared the defect — confirmed.** `VoteController::store()` (`:1513`) has the identical shape: `beginTransaction` `:1515` → `trustEvaluator->evaluate()` `:1542` → `commit` `:1892`. **Because the fix binds the connection at the model, both paths are fixed by the same change** — no second edit was required, and none was made.

**Deviations from the ADR as approved: none.** Scope held to transactional isolation plus `trust_state_transition`; `overlay_influence_chain` was left unwritten and visible.

## 9 · Reversal conditions

**Revisit this decision if:** audit durability becomes a stated requirement (⇒ Option E); audit volume makes a per-request connection costly (⇒ E, or batching); or a consumer of `overlay_influence_chain` appears (⇒ §7 reopens).

---

## Appendix · Placement note (governance, not architecture)

`php scripts/doc-placement.php --scope=product-specific --maturity=adopted --domain=publicdigit` derives the **root** `docs/publicdigit`; the resolver has no rule for artifact *type*, so it names a root, not a subfolder. **The Product Owner directed `docs/publicdigit/adr/`** — consistent with that root and with the existing `backlog/` and `reviews/` subfolders.

**The governance question stands and is not resolved here:** every *other* ADR lives in **`docs/adr/`**, which is **not a registered root** (`docs/knowledge/schema/documentation-placement.yaml` knows only `publicdigit`, `knowledgeos`, `pks`). **So ADRs now have two homes** — the dual-home defect `ES-005.4` warns about. Whether `docs/adr/` becomes a registered root or its contents migrate under the product roots is a governance decision. **Escalated, not fixed.**

---

**Traceability:** `docs/publicdigit/backlog/PBDIGIT-38-a-vote-cannot-be-saved.md` §DISCOVERY D-1…D-6 · `docs/publicdigit/backlog/PBDIGIT-42-define-audit-schema-semantics.md` (extracted §7) · `docs/publicdigit/backlog/PBDIGIT-00-verify-the-journey-end-to-end.md` · `app/Application/Election/Security/SecurityEventRecorder.php:15-19,26-28,52,55` · `app/Application/Election/Security/Overlays/IpVelocityOverlay.php:38` · `app/Models/ElectionSecurityEvent.php:12,18,40` · `app/Http/Controllers/Demo/DemoVoteController.php:1456,1493,1785` + nine `rollBack()` sites · `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23,26` · `config/database.php:70-97` · ADR-T11 (anonymity) · `R-34` · `ES-002` · `ES-005.4` · `docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md`
