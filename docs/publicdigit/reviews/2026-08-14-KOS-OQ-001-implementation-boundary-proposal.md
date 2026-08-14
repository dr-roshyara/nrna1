# KOS-OQ-001 — Implementation Boundary Proposal — **REVISION 2**

**Type:** Implementation-boundary presentation · **Date:** 2026-08-14 · **Work item:** KOS-OQ-001 (Operational Qualification vehicle) · **Role:** IMPLEMENTATION
**⛔ NO PRODUCTION CODE OR TEST CODE WAS CHANGED.** No config, documentation, mechanism, hook, lock, or Election rule touched. This document is the deliverable and is **not** implementation authority.

> *Revision 2 (2026-08-14, per Principal-Architect review): (1) ONE canonical implementation form determined **from framework source**, alternatives withdrawn — and a mandatory pairing discovered that v1 missed; (2) "byte-identical behaviour" **withdrawn** as overclaimed, replaced with an enumerated behavioural-equivalence statement; (3) verification boundary now names the exact observable and adds a shadowing guard; (4) documentation boundary confirmed and fenced from the unrelated guide debt. Candidate unchanged. Corrections are recorded, never silently swapped.*

**Predecessor:** Governance determination `d95352ed`. **Record state (queried, not interpreted):** `S3-implementation-oq` ACTIVE · `mutationOwner = S3-implementation-oq` · grant `G-KOS-OQ-001` AUTHORIZED.

---

## 1 · Candidate *(unchanged from v1)*

| | |
|---|---|
| **Existing value** | `3` — delivery attempts for a queued voter-invitation email |
| **Current location** | `app/Jobs/SendVoterInvitation.php:18` — `public $tries = 3;` **(observed fact)** |
| **Current owner** | `App\Jobs\SendVoterInvitation` — queue-delivery infrastructure serving the Election voter-import flow |
| **Configuration key** | `election.invitation_send_attempts` |
| **Configuration location** | `config/election.php` — existing home of this concern's operational tuning (`voter_cache_ttl`, `settings_cache_ttl`, `max_voters_per_election`), existing `env()` idiom **(observed fact)** |
| **Default** | `3` |
| **Consumer** | exactly one — the queue payload builder, at dispatch |

## 2 · Implementation form — ONE canonical shape, determined by evidence

**Observed fact (framework source, `vendor/laravel/framework/src/Illuminate/Queue/Queue.php:188-199`):**

```php
public function getJobTries($job)
{
    if (! method_exists($job, 'tries') && ! isset($job->tries)) { return; }
    if (is_null($tries = $job->tries ?? $job->tries())) { return; }
    return $tries;
}
```

`getJobTries()` is called from `createObjectPayload()` (`:146`, `'maxTries' => $this->getJobTries($job)`), and the worker later reads `payload()['maxTries']` (`Jobs/Job.php:294-296`).

**🔑 Consequence v1 did not state — and the reason alternatives are now withdrawn:** the null-coalesce means **the property WINS whenever it is set**. Adding a `tries()` method *while leaving* `public $tries = 3` would produce a method the framework never calls — a live "configuration key exists but is not consumed" defect, silently green in a naive test.

**AUTHORIZED FORM (the only one proposed):**

```php
// remove:  public $tries = 3;
public function tries(): int
{
    return (int) config('election.invitation_send_attempts', 3);
}
```

**The property removal is not cosmetic; it is load-bearing** — it is what makes `method_exists($job, 'tries')` reachable in the framework's own resolution order. The two edits are one atomic change.

**Why this preserves job semantics:** with the property absent, `$job->tries ?? $job->tries()` evaluates the method (no PHP notice — `??` is isset-safe); a non-null int is returned, so `maxTries` is populated exactly as before. `$backoff` is resolved by a separate path (`getJobBackoff`) and is untouched. **Resolution timing is unchanged in kind but worth stating plainly: the value is read at dispatch time, in the dispatching process, and travels in the serialized payload — precisely as the literal `3` does today.**

*(Constructor assignment to the property also works, but is rejected: it leaves either a misleading literal at the declaration site or an untyped bare property, and it hides the configuration source from a reader of the class.)*

## 3 · Behaviour preservation — claim corrected

**v1's "byte-identical behaviour" is WITHDRAWN as overclaimed.** The defensible statement:

> **With no configuration present, the observable retry behaviour is unchanged: three delivery attempts, with the existing backoff schedule.**

Enumerated, and each traceable to the code path rather than assumed:

| Aspect | After the change, with configuration absent |
|---|---|
| retry count | `3` — from the method's `config(..., 3)` fallback, so it holds even if the config key is missing entirely |
| backoff | `[30, 60, 120]` — property untouched, resolved by a different framework path |
| idempotence guard | unchanged — the `email_status === 'sent'` early return is not touched |
| failure / rethrow | unchanged — `email_status = 'failed'` + `email_error` recorded, exception rethrown for retry |
| queue semantics | unchanged — same `ShouldQueue`, connection, queue, serialization, dispatch sites |

**Not claimed:** that no byte of the payload differs; that the class file is unchanged; that any test has passed (none exists yet).

## 4 · Verification boundary — exact observable named

**Observable: the job's own public method, `(new SendVoterInvitation($invitation))->tries()`.** This is *our* class's contract, not framework internals — no queue is run, no worker booted, no payload inspected, no framework class mocked. A non-persisted `new VoterInvitation()` suffices, so the test is hermetic (no database).

`tests/Unit/Jobs/SendVoterInvitationRetryConfigTest.php` (new — **not written**):

| # | Verifies | Shape |
|---|---|---|
| **T1** | default preserves current behaviour | with the config key set to its shipped default, `tries()` returns `3` |
| **T2** | an explicitly configured value is genuinely consumed | with `election.invitation_send_attempts = 5`, `tries()` returns `5` — fails if the key is exposed but unread |
| **T3** | the fallback is in the code path, not only in the config file | with the key **absent entirely**, `tries()` returns `3` and does not error |
| **T4** | **shadowing guard** (added in Rev 2 from the §2 finding) | `SendVoterInvitation` exposes no `tries` property — if a future edit reintroduces it, the framework would silently ignore the method and the parameter would become inert |

T4 pins *our* class shape against a documented framework resolution rule established by source evidence; it does not test Laravel's behaviour. No existing test is modified.

## 5 · Documentation boundary — confirmed and fenced

- `config/election.php` — one comment line (meaning · default), matching neighbouring keys.
- `developer_guide/election_engine/` — one short entry: purpose · default · when to change · what it does **not** change (not invitation validity, not voter limits, not eligibility).

**Explicitly fenced:** the repository's recurring `developer_guide/models` · `developer_guide/http` reminder is a **non-blocking gate reflecting PBDIGIT-65's outstanding debt**. Nothing in this change touches those areas, and no guide there will be created or edited to silence it — doing so would contaminate the OQ evidence with another work item's debt.

## 6 · Files that WOULD change *(none changed)*

| File | Change | Class |
|---|---|---|
| `app/Jobs/SendVoterInvitation.php` | remove `public $tries = 3;` · add `tries(): int` (atomic pair, §2) | production |
| `config/election.php` | +1 key `invitation_send_attempts` with `env('ELECTION_INVITATION_SEND_ATTEMPTS', 3)` + comment | configuration |
| `tests/Unit/Jobs/SendVoterInvitationRetryConfigTest.php` | new (T1–T4) | test |
| `developer_guide/election_engine/election-system.md` | append one entry to the **existing** file (voter-import workflow section) — no new guide file | documentation |
| `.env.example` | +1 line `ELECTION_INVITATION_SEND_ATTEMPTS=3` — **resolved: include** (§11) | configuration surface |

## 7 · Constraints honoured / prohibitions

One parameter only · `$backoff` untouched · no business rule · no `app/Models`, no `app/Http` · no Election authorization/enforcement · `.claude/scripts/workflow-state.php` untouched (OQ freeze) · no Increment-2 mechanism · no hook/lock/lease · no new component, aggregate, entity, service, port, event, or bounded context · no second session activated.

## 8 · DDD confirmation

The retry count is **already** an operational concern of `SendVoterInvitation`. The change externalizes *where the value comes from*; it transfers no domain ownership, creates no new bounded context, and introduces no business policy. `config/election.php` is the same concern's existing tuning surface (already consumed by Election-side code). Nothing in the domain model, state machine, entitlement model, or authority model is reachable from this change.

**Genuine architectural question? NO. Business decision required? NO.** *(The adjacent value that would be a business decision — invitation validity, `now()->addDays(7)` — is deliberately excluded, §9.)*

## 9 · Alternatives surveyed and rejected *(unchanged from v1)*

Election-transition lock TTL (`app/Models/Election.php:1616`) and the vote-submission lock (`VoteController:1694`) — clean operationally, but land in the `models`/`http` areas carrying PBDIGIT-65's guide debt · invitation expiry `addDays(7)` — **business meaning**, would require a Business Decision Request · `self_service_voter_limit` (40) — business policy plus open `EM-OPEN-019` · `voter_cache_ttl` — already configurable · import upload limit — two call sites, larger.

## 10 · OQ observations *(observations only; mechanism frozen, nothing repaired)*

- **O-1:** the eight-question startup check was answerable entirely from the record; no prose file consulted for state, ownership, or authorization.
- **O-2:** *"am I ACTIVE?"* was a query, not an inference — record and commission agreed, checkably.
- **O-3:** the grant's `scope` carries an operative prohibition in free text; whether scope should be structured is Increment-2-adjacent — recorded, not raised, not repaired.
- **O-4 (new):** the boundary-refinement cycle itself produced a defect that would otherwise have shipped — the dead-`tries()` shadowing trap (§2) surfaced only because implementation form was demanded *before* implementation authority. Evidence that "ACTIVE ≠ begin coding" has practical value, not merely procedural value.

No claim is made that implementation succeeded, that any test passed, or that OQ is qualified. Session 1 is the independent evidence compiler.

## 11 · Open questions — **both resolved WITHIN THE IMPLEMENTATION ANALYSIS; no human authority is claimed**

> **Authority note (A-3, correction recorded not silently swapped):** an earlier phrasing of this section said these were *"closed by the reviewer's decisions."* **Withdrawn as an authority overclaim.** The two items were resolved inside the architectural/implementation analysis and are recorded here as the proposal's own content. They carry **no performative authority**, and — like every other line of this boundary — they take effect only if and when the PO/ARB approves the boundary and Governance registers that act.

1. **`.env.example`** — ✅ **INCLUDE.** `ELECTION_INVITATION_SEND_ATTEMPTS=3`, alongside the 8 existing `ELECTION_*` entries. Now **unconditional** in §6 (its "conditional on the approver" qualifier is withdrawn).
2. **Documentation placement** — ✅ **USE AN EXISTING FILE; create none.** Resolved to a named target by inspection: **`developer_guide/election_engine/election-system.md`**, which already documents the voter-import flow this setting serves (§ *"Workflow 3: Bulk Voter Import"*, line 417) and is one of only three files in the area mentioning invitations. The entry is appended there; **no new guide file is created**, avoiding documentation fragmentation.

**No unresolved question remains.** Session 3 now holds **zero discretion over the file set** — §6 is exhaustive and exact.

---

**SESSION 3 — REVISION 2 PRESENTED. NO PRODUCTION CODE OR TEST CODE WAS CHANGED. STOPPED.**
