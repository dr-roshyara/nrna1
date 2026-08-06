# ADR — Where security-audit events are written relative to the vote transaction

**Status: 🟡 PROPOSED — awaiting Product Owner approval. No code has been written.**
**Date:** 2026-08-06 · **Origin:** `PBDIGIT-38` (defect) ← `PBDIGIT-00` (runtime verification)
**Decision owner:** Product Owner / whoever owns ADRs · **Author:** engineering (evidence and drafting only — `R-34`)

> ⚠️ **Placement, recorded because it was not purely derived.** `php scripts/doc-placement.php --scope=product-specific --maturity=adopted --domain=publicdigit` derives the root **`docs/publicdigit`**; the resolver has no rule for artifact *type*, so it names a root and not a subfolder. **The Product Owner directed `docs/publicdigit/adr/`** — consistent with the derived root and with the existing `backlog/` and `reviews/` subfolders under it.
>
> **The open governance question is unchanged and is not resolved here:** every *other* ADR in this repository lives in **`docs/adr/`**, which is **not a registered root** (`docs/knowledge/schema/documentation-placement.yaml` knows only `publicdigit`, `knowledgeos`, `pks`). **So ADRs now have two homes** — the dual-home defect `ES-005.4` warns about. **Escalated, not fixed:** whether the legacy `docs/adr/` becomes a registered root, or its contents migrate under the product roots, is a governance decision.

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

> **Therefore: the transaction boundary should be narrowed to the Vote. The audit write should sit outside it.**

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

## 4 · Decision — **PROPOSED: Option D, independent connection**

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

## 6 · Consequences and trade-offs — including the unwelcome ones

**Accepted:**

* **An extra PDO connection** is opened per request that records an event (≈10 % of allows, plus all denials).
* **A rolled-back vote now leaves an audit row.** This is intended (I-2) but it is a **visible behaviour change**: `election_security_events` will contain events for votes that do not exist. **Anyone reading the table as "one row per vote" will be wrong** — it is one row per *evaluation*.
* **In-transaction read-after-write is lost.** An event written during a request is no longer visible to a read on the default connection in that same transaction. **Checked: no such dependency exists** — `IpVelocityOverlay` reads previously-committed events, and only one event is written per evaluation, at the end.
* **Tests that rely on transaction rollback will see audit rows persist.** This project's `TestCase` already skips transactions for PostgreSQL and uses `migrate:fresh`, so the practical impact is nil here — **but it is a real trap for any future transactional test.**

**Rejected as out of scope:** the Application-layer rules (`.claude/CLAUDE.md` Rule 2) forbid Facades and Eloquent in `app/Application/`. **`SecurityEventRecorder` already violates both** (`Log::` and `ElectionSecurityEvent::create`). This ADR does not fix that — **it is pre-existing debt, and correcting it means introducing a port, which is a larger change than this defect warrants.** Recorded so the deviation is not mistaken for approval.

## 7 · A second decision this ADR requires — `overlay_influence_chain`

The recorder omits **two** required (`NOT NULL`, no default) columns, measured against the live schema: `overlay_influence_chain` **and** `trust_state_transition`. *(A fix supplying only the first would pass review and fail again at runtime.)*

* **`trust_state_transition`** (varchar 100) — unambiguous: the recorder already writes `trust_level_before` and `trust_level_after`, so the transition is `'<before>-><after>'`.
* 🟡 **`overlay_influence_chain`** (json) — **has no meaning anywhere in the codebase.** It appears only in the migration, `$fillable`, and a cast. **Nothing constructs an influence chain.**

**Decision required — three options:**

| | Option | Comment |
|---|---|---|
| **i** | Write the ordered identifiers of overlays that produced observations | the only meaning the available data supports |
| **ii** | Make the column nullable | honest about the fact that the concept does not exist |
| **iii** | Drop the column | if no consumer is ever intended |

**Engineering does not choose.** Writing a placeholder into an *audit* column whose semantics nobody can state would be worse than either (ii) or (iii).

## 8 · Verification required before this ADR is marked accepted

* [ ] A vote persists: `demo_votes` gains a row, `demo_results` gains rows.
* [ ] 🔒 **The saved vote carries no voter linkage** (ADR-T11). `PBDIGIT-00` could not check this, because no vote row ever existed.
* [ ] **I-1 tested:** an induced audit-write failure does not lose the vote.
* [ ] **I-2 tested:** a rejected vote still leaves a deny audit record.
* [ ] The journey re-walked end to end. *(Step 5 will still fail on **`PBDIGIT-40`** — an undefined `$vote` in `thankyou()`. **That is a separate story and must not be folded into this one.**)*
* [ ] Whether the **real** voting path shared the defect — answered either way.

## 9 · Reversal conditions

**Revisit this decision if:** audit durability becomes a stated requirement (⇒ Option E); audit volume makes a per-request connection costly (⇒ E, or batching); or a consumer of `overlay_influence_chain` appears (⇒ §7 reopens).

---

**Traceability:** `docs/publicdigit/backlog/PBDIGIT-38-a-vote-cannot-be-saved.md` §DISCOVERY D-1…D-6 · `docs/publicdigit/backlog/PBDIGIT-00-verify-the-journey-end-to-end.md` · `app/Application/Election/Security/SecurityEventRecorder.php:15-19,26-28,52,55` · `app/Application/Election/Security/Overlays/IpVelocityOverlay.php:38` · `app/Models/ElectionSecurityEvent.php:12,18,40` · `app/Http/Controllers/Demo/DemoVoteController.php:1456,1493,1785` + nine `rollBack()` sites · `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23,26` · `config/database.php:70-97` · ADR-T11 (anonymity) · `R-34` · `ES-002` · `ES-005.4` · `docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md`
