# ARB decision request — tenant context for non-request execution (PBDIGIT-211)

**Date:** 2026-10-09 · **Requested by:** PO (Mission 2 approval) · **Prepared by:** Claude Code session (self-declared) · **Decision owner:** Architecture Review Board
**Status:** **PENDING — no answer is assumed anywhere.** The questions are posed, with options, constraints and consequences; none is chosen.
**Evidence:** `2026-10-09-adjudication-remediation-gate.md` §2 (T-1 chain) and §10 (execution record) · `PBDIGIT-211` (original + 2026-10-09 addendum)

## 1. The question

**What is the authoritative tenant-context model for work that does not run inside an HTTP request?** This covers scheduled jobs, console commands, the outbox / inbox relay, inbox handlers, and outbox writes made inside a consumer.

## 2. Facts the ruling must account for

| Fact | Source |
|---|---|
| Scheduled jobs act on an empty set when no tenant is set (`BelongsToTenant` fallback slug `platform` ≠ `publicdigit` gives `organisation_id IS NULL`). Runtime-confirmed in the test environment | PBDIGIT-211 |
| The relay (`outbox:process`, `inbox:redrive`, every minute) carries each event's `organisation_id` but never establishes it. Consumers call `TenantContext::require()` and fail; the outbox retries 5×, then the row is `failed`. **RUNTIME-CONFIRMED 2026-10-09** (RT-1 / RT-3 on the dedicated DB) | gate report §10.6 |
| `outbox_processing_errors` (written by the relay on failure) has no migration: error records are silently lost | gate report §10.6 (T-5) |
| Inbox and outbox rows have `organisation_id` NOT NULL with an FK to `organisations` (cascade on delete). A message therefore names an existing (possibly soft-deleted) organisation | migrations `2026_05_04_000001:27`, `2026_07_06_000001:47` |
| `TenantContext` is process-global static state | `app/Services/TenantContext.php` |
| Loop tests set the tenant in `setUp`, masking the consumer path | gate report §2 |

## 3. Binding constraints (accepted decisions)

- **ADR-MP-04:** Messaging *preserves* tenant isolation, never owns it, and "must never cross tenants". Guards live in the constitutional suite.
- **ADR-MP dispatcher invariants:** tenant propagation / isolation, consumer isolation, replay safety.
- **ADR-T16:** cross-context identities are opaque strings; the Election domain stays tenant-free.
- **PB-003 D-06:** inbox rows are tenant-scoped (`organisation_id NOT NULL`).
- **Q-1 / ADR-T8 / binding order 178 → 177 → 174 → 182:** unaffected by this ruling. It must not be used to wire an issuance-path caller.

## 4. Questions

| # | Question | Options (none chosen) | Consequence to weigh |
|---|---|---|---|
| Q1 | Where is the organisation established for consumed messages? | (a) at the inbox boundary, from the message's organisation, for one handler invocation · (b) explicit parameter through ports / adapters, no ambient state · (c) a scoped-execution primitive ("run as organisation"), used uniformly by the relay, redrive and per-tenant scheduled iteration | (a) / (c) change one boundary; (b) touches every adapter but removes global state |
| Q2 | Is process-global ambient tenant state acceptable outside HTTP? | yes, with guaranteed restore · no | Long-running workers leak static state unless it is restored |
| Q3 | An ambient tenant is already set and differs from the message's | refuse (permanent) · override for the call and restore · ignore the ambient | **Runtime evidence (2026-10-09, RT-4):** with organisation B ambient, an event of organisation A was processed **under B** and marked completed. Today the consumer path trusts the ambient tenant over the message. Not reachable in the current cron invocation (separate process), but reachable by any in-process relay or a non-restoring fix |
| Q4 | The message's organisation is soft-deleted | dead-letter with reason · park · process normally | Non-existent is impossible (FK); soft-deleted is reachable |
| Q5 | A tenant-scoped query runs with no tenant outside HTTP | fail loudly (remove the fallback) · fix the fallback slug | Silent empty results today |
| Q6 | Where does the mechanism live? | a tenancy capability Messaging invokes · inside Shared Messaging | ADR-MP-04 says Messaging preserves; it does not own |
| Q7 | One model for scheduled jobs and consumers, or two? | one · two | 211 presumes one |

**Engineering observation (non-binding, not a recommendation to adopt):** Q1 (a) and (c) compose: (c) is the primitive and (a) is its use at the inbox boundary.

## 5. Acceptance criteria any ruling must allow us to meet (PBDIGIT-211)

- The original two criteria (scheduled jobs act on both tenants, never silently nothing).
- AC-R1 … AC-R4 from the addendum: per-organisation delivery; ambient restored; soft-deleted classified per Q4; never under a foreign tenant.

## 6. What happens after the ruling

1. The ruling is recorded (ADR or ARB log row).
2. PO authorisation of PBDIGIT-211 (jobs + relay).
3. RED characterisation tests exist and **were executed** on the dedicated database `nrna_test_adjudication` (2026-10-09; `tests/Feature/Contexts/Shared/Messaging/RelayTenantContextTest.php`): **5 tests: 3 failures, 1 pass, 1 incomplete** (the incomplete one awaits Q4). After the ruling: GREEN per the ruling, regression, developer guide. *(Corrected 2026-10-09; previously read "pending the dedicated DB".)*

**A failing characterisation test is evidence, not permission to change production code.**

## 7. Proposed direction for discussion (PO / principal review, 2026-10-09). **NOT a ruling**

The PO forwarded these as the proposed direction. **They become decisions only when the ARB records them.** The engineering notes state code facts that bear on each proposal. They are not counter-proposals.

| # | Proposed direction | Engineering note (fact-based) |
|---|---|---|
| Q1 | **(c)** a scoped-execution capability (`runAsOrganisation(organisationId, callback)`), used uniformly by scheduled jobs, relay, inbox handlers and redrive. At the inbox boundary the organisation comes **from the message**, not from ambient state | The message already carries it: `InboxMessage::organisationId()`, inbox `organisation_id` NOT NULL FK. **One entry point covers relay and redrive:** both reach handlers through `InboxExecutionEngine`. A boundary there covers both |
| Q2 | **Conditional yes:** process-global state only as a compatibility mechanism; one invocation; restore in `finally`; no shared mutable state under concurrent execution | Current runtime: scheduled commands run as **separate processes**; `QUEUE_CONNECTION` default `sync` (`config/queue.php:16`); no in-process concurrency found. **Long-running workers (`queue:work`, Octane) would reuse static state across jobs.** Restore-in-`finally` is the guard RT-2 / RT-4 test |
| Q3 | **Fail closed:** never process under a foreign tenant; detect the mismatch **before business writes**; reject or park per a defined policy; restore regardless | RT-4 shows today's behaviour is the opposite: the event was processed under B and **marked completed**. If Q1 (c) *overrides* the ambient tenant with the message's for the call, a "mismatch" arises only for **nested** scopes with different organisations. The ruling should say whether a nested foreign scope is refused, which the fail-closed wording implies |
| Q4 | **Park for explicit classification**, preserving message and diagnostics; dead-letter defensible if the lifecycle contract says such events can never be processed; decide recovery / replay before any terminal outcome | **"Park" is not indefinite here.** `InboxExecutionEngine.php:46-58` parks with a retry every `inbox.park_retry_minutes` (5) and an **absolute deadline `inbox.park_deadline_minutes` (60)**, after which the row becomes **`dead`** (`inbox:redrive`). Park today means "dead after ~60 min". Parking only applies to `CausalPreconditionMissing` today. **No replay tool exists for `dead` or `failed` rows** (status report §6). Q4 therefore also needs a retention / replay decision, or a distinct held state |
| Q5 | **Fail loudly:** remove the `platform` fallback for tenant-scoped operations; verify platform-level operations separately | `BelongsToTenant` (`app/Traits/BelongsToTenant.php:41-63`) is also used at **request time** (session fallback). Removing the fallback changes request paths whenever a tenant is absent, not only console. The ruling should state the scope (non-request only, or everywhere) and require an inventory of intentional platform-level reads |
| Q6 | **A tenancy capability** that Shared Messaging invokes; Messaging preserves identity and delivery and does not own tenancy policy | Consistent with ADR-MP-04 ("Messaging preserves"). No existing tenancy context exists; `App\Services\TenantContext` is an infrastructure service. Where the capability lives is part of the ruling (BC set question SK-215 is adjacent, not blocking) |
| Q7 | **One model:** one scoped-execution contract with adapters per entry point | 211's four jobs, the relay, redrive and handlers. Membership jobs need **per-tenant iteration** (no message carries the organisation); consumers get it from the message. One contract, two adapters |

**PO qualification recorded:**
- Q3 and Q4 need **explicit operational semantics**: permanent vs retryable vs held, observability, replay.
- No mismatch class may be declared permanently invalid without considering recovery.

**Requested of the ARB:** record Q1–Q7, including the mismatch and soft-deletion semantics. **No production implementation is authorised by this package.** PBDIGIT-211 remediation, and the binding order 178 → 177 → 174 → 182, stay behind their own gates.
