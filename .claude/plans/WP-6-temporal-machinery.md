# WP-6 — Temporal Machinery (horizon · demand deadlines · finality)

**Status:** ✅ **EP-01 APPROVED · DECISION A APPROVED (expiry IS published integration language) · DECISION B direction approved (begins a NEW conversation).** ⏳ **Blocked on DECISION C only — an IMPLEMENTATION BOUNDARY decision** (where does WP-6's bounded-context responsibility end?). No code written, no RED yet.
**Slice:** WP-6 (EPIC-004 roadmap) · **Contexts:** Adjudication (primary), config/infrastructure · **Protocol:** `.claude/IMPLEMENTATION_PROTOCOL.md` (FROZEN)
**Architecture status:** CLOSED for correction-loop integration. This plan **consumes** architecture; it produces none.

---

## EP-03 Readiness

| Check | Evidence |
|---|---|
| Predecessors accepted | WP-1 · WP-2 · WP-5 **ACCEPTED + CLOSED**; WP-3A · WP-4 complete, slice acceptance pending (neither blocks WP-6) |
| Architecture frozen | ✔ Architecture Phase CLOSED |
| Protocol operational | ✔ OPERATIONAL, FROZEN |
| APM + store exist | ✔ WP-2: `AdjudicationProcessManager::enforceHorizon()` and `AdjudicationProcessStore::dueForHorizon(DateTimeImmutable $asOf)` |
| `Expired` state exists | ✔ WP-2: `AdjudicationProcessStatus` + `AdjudicationProcessState::expire()` |
| Horizon-expiry policy ruled | ✔ EPIC-004K §197 (quoted in Phase 2) |
| Finality target exists | ✔ `Determination::finalize()` — guarded `Issued → Final`, **emits no event** (Round 50-07 v1.2) |
| Q-2 numeric parameters | ⚠️ **ARB decision outstanding** (§187, five items). Roadmap pre-authorizes **INTERIM-marked** defaults — see Phase 8 |

**Readiness: GO**, with the numeric parameters carried as INTERIM.

## Phase 1 — Commission Reset

**Closed:** all WP-5 commissions; all architecture commissions of this milestone.
**Carried forward:** Q-2 (as enforced policy) · Constitutional Policy 4 · EPIC-004K §§3/6/7/15 · roadmap §WP-6 · ADR-T1 · ADR-T8 · ADR-MP-06 · the frozen contract · **the WP-5 forward checkpoint** (Phase 9b).
**NOT carried:** any mandate to design new architecture. Architectural uncertainty ⇒ **stop and report**.

## Phase 2 — Authority Register

| Authority | Governs |
|---|---|
| **Q-2** | *The APM enforces a duration it does not own.* MAD's **definition** is business policy; the APM is its **enforcer** (EPIC-004K §41, §81) |
| **Constitutional Policy 4** | *Automated verification never determines significance* — **no timer → conclusion path.** Expiry is a terminal fact, never an adjudication (§57, §142) |
| **EPIC-004K §197 (RULED)** | *"Horizon-expiry policy RULED: return to Contestation; late decisions never honored. Expiry announces the failure-to-conclude; the challenge's disposition is Contestation's per its own rules; **a post-expiry authority decision dead-letters as a conflict**."* |
| **EPIC-004K §80** | Evidence-demand deadline: *"a demand unanswered past its deadline returns to the process for re-decision … the reaction is the authority's/process's, **never automatic escalation to a conclusion**"* |
| **EPIC-004K §142** | `finality(determination) = issued-at + Contestation Window(determination-type)`, *provided no challenge against it is open at closure* |
| **ADR-T1 / ADR-T8** | One aggregate + its outbox rows per transaction; the loop stays choreography — no saga, no compensation |

## Phase 3 — Business Understanding

**Capability:** time itself becomes an actor — an adjudication may not run unbounded, an evidence demand may not hang forever, and a determination becomes final when its challenge window closes unchallenged.

**Objective:** bound the conduct **without ever letting a clock decide a constitutional question.**

**Business policies:** the horizon **expires** a process (a distinct terminal fact, never a conclusion) · an overdue demand **returns to the process for re-decision**, never auto-escalates · finality is **armed, not announced** (`finalize()` emits nothing) · **a late authority decision on an expired process is a conflict, not a no-op.**

**Invariants:** *exactly one conclusion, of exactly one kind, or an expiry — never more than one, never a mix* (§74) · Policy 4 · one origin per conversation.

**Ubiquitous language:** *adjudication horizon* · *Maximum Adjudication Duration (MAD)* · *evidence-demand deadline* · *Expired* · *finality* · *Evidence Preservation Window*.

**Ownership:** Q-2/ARB own the **durations**; the APM owns **enforcement**; Contestation owns **what an expiry means for the challenge**; the Determination aggregate owns **finality's legality**.

## Phase 4 — Strategic DDD

Primary context **Adjudication**. One outward edge: the **expiry announcement**, whose consumer §197 fixes as **Contestation** ("return to Contestation"). That crossing follows the frozen contract exactly as WP-3A/WP-4 did — publication + registration producer-side, primitives only, `fromConsumed()` on the consumer side. **No new integration style, no new relationship pattern.**

The **finality evaluator** is deliberately *not* an integration: it emits no event, so it creates no crossing at all (§142 + Round 50-07 v1.2).

## Phase 5 — Business Model Fidelity

| Dimension | Preserved how |
|---|---|
| Language | `MAD`, *horizon*, *Expired*, *deadline*, *finality* — the architecture's own words |
| Ownership | numbers stay ARB's (config, INTERIM-marked); enforcement stays the APM's |
| Boundaries | expiry **announces**; Contestation decides the challenge's disposition |
| Invariants | Policy 4 enforced by construction — no code path leads from a timer to a conclusion |
| Authority | a late ruling is refused as a **conflict**, not silently absorbed |
| Lifecycle | `expire()` and `finalize()` remain aggregate/state decisions |
| Anonymity | untouched |

## Phase 6 — Architectural Traceability

| Component | Authority |
|---|---|
| `config/adjudication.php` — MAD, demand deadline, Contestation Window (determination-type), Legal Safety Margin; **INTERIM-marked** | roadmap §WP-6 · Q-2 (§187 parameters) |
| A **duration resolver** port + adapter (per-election-type, org-overridable) | roadmap §WP-6 ("per-election-type, org-overridable") |
| `enforceHorizon()` becomes **MAD-aware** | Q-2 · §81 — see Finding F-1 |
| **Expiry announcement** — domain event + outbox mapping + hydrator | §197 (ruled) · ADR-T21 pattern |
| **Late-decision conflict** handling | §197 — see Finding F-2 |
| Evidence-demand deadline tracking + `demandsDue()` query | §80 · PM-3 |
| **Finality evaluator** (scheduled, no event) | §142 · Round 50-07 v1.2 |
| Scheduled command(s) to drive the above | roadmap §WP-6 ("timer execution") |
| **Nothing else** | no new VO for durations unless a keystone demands it; no saga; no compensation (ADR-T8) |

## Phase 7 — Simplification Review (reuse before create)

| Need | Existing asset | Create? |
|---|---|---|
| `Expired` terminal state + transition | `AdjudicationProcessStatus::Expired` · `AdjudicationProcessState::expire()` | ❌ |
| Horizon enforcement loop | `AdjudicationProcessManager::enforceHorizon()` | ♻️ **make MAD-aware** |
| Due-process query | `AdjudicationProcessStore::dueForHorizon($asOf)` — already takes a **caller-supplied cut-off**, by design | ❌ reuse as-is |
| Finality transition | `Determination::finalize()` (guarded, event-free) | ❌ |
| Publication machinery | `EventOutbox` + `OutboxEventAdapter` + `EventHydratorRegistry` | ♻️ one new mapping + hydrator |
| Transaction boundary | `TransactionManager` + `TransactionalAdjudicationService` | ❌ reuse |
| Config style | `config/election.php`, `config/voting.php` (`env()`-backed) | ♻️ mirror |
| Clock | `ClockInterface` (injected — no `now()` in domain) | ❌ |

**WP-2 built the horizon's *mechanism* deliberately without its *duration*.** WP-6 supplies the duration and the trigger — nothing more.

## Phase 8 — Business Assumption Review

| Interpretation | Class |
|---|---|
| Expiry is terminal, never a conclusion | **Explicit authority** (Policy 4 · §57) |
| Expiry announcement goes to Contestation | **Explicit authority** (§197) |
| A post-expiry authority decision dead-letters as a conflict | **Explicit authority** (§197) — and **currently not implemented**, see F-2 |
| An overdue demand returns to the process, never auto-escalates | **Explicit authority** (§80) |
| `finalize()` announces nothing | **Explicit authority** (Round 50-07 v1.2) |
| The horizon cut-off is `now − MAD` | **Derived implication** of `dueForHorizon`'s contract (*"opened before the cut-off the caller passes"*) |
| **The numeric values of MAD / demand deadline / Contestation Window / Legal Safety Margin** | **OPEN BUSINESS QUESTION — ARB's, five items per §187.** Roadmap pre-authorizes **INTERIM-marked** defaults so the machinery can ship; the numbers remain the ARB's to set |
| Whether the finality evaluator's *"no challenge open at closure"* check may query Contestation | **ARCHITECTURAL ASSUMPTION to confirm at RED** — if it needs Contestation state, that is a **new crossing** and the answer is *stop and report*, not invent |

## Phase 9 — Tactical DDD

```
config/adjudication.php            MAD · demand deadline · window · margin   (INTERIM)
        │
AdjudicationDurations (port)  ──►  resolve(electionType, organisationId): MAD etc.
        │                          adapter reads config + org override
        ▼
AdjudicationProcessManager::enforceHorizon()
        │   cut-off = clock->now() − MAD          ← F-1 fix
        ├─► store->dueForHorizon(cut-off)         (unchanged)
        ├─► state->expire(now)                    (unchanged)
        └─► outbox->enqueue(fromConsumed?/…, <expiry announcement>)   ← §197
        
AdjudicationProcessManager::receiveRulingDecision()
        └─► if process is EXPIRED → raise a CONFLICT (permanent) ← F-2, §197
            (distinct from redelivery of the same decision, which stays a no-op)

DemandDeadlines: store->demandsDue(asOf) → return to the process for re-decision (§80)

FinalityEvaluator (scheduled, Determination-only, NO event)
        └─► issued-at + window elapsed ∧ no open challenge → determination->finalize()
```

**Provenance question to settle at RED:** the expiry announcement is triggered by a **clock**, not by a consumed message. So which provenance does it carry? It is arguably a **new conversation** (nothing consumed it into being) — which would make the horizon enforcer a **chain origin** and require an allowlist entry. **Flagged, not decided:** this is exactly the kind of question the WP-5 invariant now answers precisely (*each conversation has exactly one origin*), and RED will expose which reading holds. **If it requires an allowlist change, that is an ARB decision (ADR-MP-06).**

## Phase 9b — Carried checkpoint from WP-5 (verification, not mandate)

> **Verify that the authority-decision conversation now derives provenance from the consumed message. If confirmed, remove the remaining allowlist entry and demonstrate that the "one origin per conversation" invariant still holds.**

**The removal follows the evidence.** If WP-6 finds the authority decision still has no incoming message, **the entry stays and the invariant is unharmed** — that is a legitimate outcome, not a failure. Note the interaction with Phase 9's provenance question: WP-6 could plausibly *remove* one originator and *add* another; both moves are ARB decisions and both must preserve one-origin-per-conversation.

---

## Two findings from the existing code — both grounded, both WP-6's to resolve

### F-1 — `enforceHorizon()` is not MAD-aware and would expire **everything**

```php
public function enforceHorizon(): void {
    $now = $this->clock->now();
    foreach ($this->store->dueForHorizon($now) as $process) {   // ← cut-off = NOW
        $this->store->save($process->expire($now));
    }
}
```

`dueForHorizon($asOf)` selects `opened_at <= $asOf` — its docblock is explicit that the **caller** supplies the cut-off, and that *"timer execution + the configured MAD land in WP-6."* With `$asOf = now`, **every non-terminal process is due**, so the first call would expire all of them, including one opened a second ago.

**Inert today** (nothing calls it — WP-6 delivers the scheduler), so this is **not a live defect**; it is the WP-2/WP-6 seam behaving exactly as WP-2 documented. **WP-6 must pass `now − MAD`, and a RED keystone must pin it:** *a process opened one minute ago does not expire under a 14-day MAD.*

### F-2 — a late authority decision is currently a silent no-op; §197 rules it a **conflict**

`receiveRulingDecision()` carries: `return; // already concluded (or expired): a redelivered decision is a no-op`. That is right for **redelivery** (ADR-T3 at-least-once) but §197 rules that *"a post-expiry authority decision dead-letters as a conflict."*

**Two different situations share one branch today:**

| Situation | Correct behaviour | Today |
|---|---|---|
| The **same** decision redelivered | idempotent no-op | ✔ no-op |
| A **late** decision on an **Expired** process | **conflict → dead-letter** (§197) | ✖ silently swallowed |

WP-6 must separate them. This is a **ruled-policy gap, not an architecture question** — §197 already decided it.

---

## RED plan (Phase 10) — written only after approval

| # | Keystone | Authority |
|---|---|---|
| 1 | A process opened **within** MAD does **not** expire | F-1 · Q-2 |
| 2 | A process opened **beyond** MAD **does** expire, to `Expired` | §57 · PM-8 |
| 3 | Expiry is **terminal, never a conclusion** — no outcome, no legitimacy, no determination | **Policy 4** |
| 4 | Expiry **announces** exactly one fact, once (idempotent across repeated timer runs) | §197 |
| 5 | A **late** ruling on an Expired process raises a **conflict** (permanent, dead-lettered) | §197 · F-2 |
| 6 | A **redelivered** decision on a concluded process stays a **no-op** | ADR-T3 — guards against over-correcting F-2 |
| 7 | An **overdue demand** returns to the process for re-decision and **does not conclude** anything | §80 |
| 8 | Finality: window elapsed ∧ unchallenged → `Final`, and **no event is emitted** | §142 · Round 50-07 v1.2 |
| 9 | Finality does **not** fire while a challenge against the determination is open | §142 |
| 10 | Durations resolve **per election type** and honour an **organisation override** | roadmap §WP-6 |
| 11 | The minting guard still passes — whatever the Phase-9 provenance answer turns out to be | ADR-MP-06 |

## Gates (13–17)

`composer merge-gate` · PHPStan max · Deptrac 0 · Architecture suite (incl. the minting guard) · triple qualification · developer guide `developer_guide/adjudication/` + index · **STOP for ARB slice acceptance.**

## Risks

| Risk | Mitigation |
|---|---|
| A timer path reaching a conclusion (Policy 4 breach) | Keystone 3 + 7 assert the **absence** explicitly |
| Over-correcting F-2 into breaking idempotency | Keystone 6 pins redelivery as a no-op |
| The finality check needing Contestation state → a new crossing | Phase 8 marks it an assumption; if confirmed, **stop and report** rather than invent |
| INTERIM numbers hardening into de-facto policy | Every default carries an `INTERIM` marker naming the ARB decision it awaits |

## Open questions for the ARB

1. **INTERIM defaults** — confirm that shipping INTERIM-marked numbers (MAD, demand deadline, window, margin) is authorized, and supply any values now if preferred.
2. **The expiry announcement's provenance** — chain origin (needing an allowlist entry) or something else? *(Recommend deciding at the RED boundary, on evidence.)*
3. **Expiry announcement's published-language status** — §111 flagged the kind as *"TBD by the horizon's business policy"* while §197 rules the consumer is Contestation. Confirm it is published language, hence hydrator + registration, as WP-3A did for `ChallengeRouted`.

---

**Traceability:** roadmap §WP-6 · Q-2 · Constitutional Policy 4 · EPIC-004K §§27/36/41/57/72/74/80/81/90/111/115/128/142/160/187/197 · ADR-T1 · ADR-T8 · ADR-MP-06 · Round 50-07 v1.2 (`finalize()` emits nothing) · WP-2 plan (horizon mechanism without duration) · WP-5 plan (carried checkpoint, one-origin invariant) · frozen `docs/architecture/Cross_Context_Integration_Contract.md`.

---

## ✅ EP-01 APPROVED (ARB, 2026-07-31) + PRE-RED CLARIFICATION RESOLVED

**Approved:** planning quality · F-1 · F-2 · INTERIM defaults (each marked with the authority it awaits) · the published-language decision criterion.
**Required before RED:** classify the expiry announcement. **Resolved below, on evidence.**

### The criterion, applied

> **Does Contestation need to react to Expiry as an external business fact?**

**YES — and it is ruled, not inferred.** EPIC-004K §197: *"Horizon-expiry policy RULED: **return to Contestation**; late decisions never honored. Expiry announces the failure-to-conclude; **the challenge's disposition is Contestation's per its own rules**."*

A ruling that assigns the disposition to another bounded context **is** a statement that the fact must cross the boundary.

### Therefore — the classification, and why it is an application of the invariant, not an exception

| Question | Answer | Grounds |
|---|---|---|
| Externally published integration event? | **YES** | §197 assigns Contestation the disposition |
| Published language (hydrator + registration, producer-side)? | **YES** | The frozen contract: publication **and** registration are both producer-owned (the WP-3A rule) |
| Does it continue an existing conversation? | **NO** | A timer consumes no message; there is no incoming correlation, so `fromConsumed()` has nothing to derive from |
| Does it therefore **begin** one? | **YES** | By the invariant: *an origin begins a conversation; it never transfers ownership of one.* A publication with no antecedent **is** an origin |
| Is the horizon enforcer a chain origin? | **YES** — requiring an allowlist entry | ADR-MP-06; **the extension is an ARB decision**, requested below |

**This is the invariant behaving correctly, not an exception to it.** The clock-triggered publication is the second lawful case of a *new* conversation: the first (`route`) begins the correction loop; this one begins the *failure-to-conclude* conversation. Each has exactly one origin.

### ⚠️ Applying the criterion exposed a gap that changes WP-6's scope

**Contestation cannot currently consume this event.** Its only timeout transition is:

```php
public function lapse(DateTimeImmutable $at): void
{
    $this->guard('lapse', ChallengeState::Raised, ChallengeState::Admitted);   // ← NOT Routed
    $this->state = ChallengeState::Lapsed;
}
```

A challenge whose adjudication expires is in state **`Routed`** — and `lapse()` refuses `Routed`. **There is no `Routed → <disposition>` transition in the Challenge aggregate.** So §197's *"the challenge's disposition is Contestation's per its own rules"* names a rule whose **transition does not yet exist**.

Two consequences, stated plainly:

1. **The publication side is fully inside WP-6's authority** — Adjudication announces the expiry (domain event + outbox mapping + hydrator + registration). Nothing about that requires Contestation.
2. **The consumption side is NOT** — it needs a new Contestation transition, which is a **Contestation domain change outside WP-6's stated scope** (*"Adjudication context; APM owns enforcement"*), and a modelling question (*is the disposition a lapse, a re-route, a dismissal, or a new state?*) that only the ARB/business can answer.

**Recommendation — the WP-3A/WP-4 precedent, which this project has already run once successfully:** WP-6 delivers **publication + registration**; the **consumer is a separate slice**. That precedent is exact — WP-3A published `ChallengeRouted` with no consumer, and WP-4 added the consumer one slice later, with the contract audit in between. It also keeps WP-6 honest: an announced fact with no consumer is *published language awaiting a consumer*, not a broken loop, and the inbox simply resolves no handler.

**I have not assumed this.** It is recorded as the second ARB decision requested below.

### Two ARB decisions requested (both are ADR-MP-06 / scope decisions, not implementation choices)

| # | Decision | Recommendation |
|---|---|---|
| **A** | **Add the horizon enforcer to `CHAIN_ORIGIN_ALLOWLIST`** as the origin of the failure-to-conclude conversation (ADR-MP-06 makes any extension an ARB decision) | **Approve.** Without it the guard fails the moment the enforcer publishes; with it, the invariant stays machine-enforced and the entry documents *which* conversation it begins |
| **B** | **Scope split:** WP-6 = publication + registration; the **Contestation consumer + its `Routed → disposition` transition** = a separate slice (provisionally **WP-6B**) | **Approve.** The alternative — designing a new Challenge transition inside an implementation commission — would be exactly the "silently answering an architectural question during coding" this plan set out to avoid |

**Note the interaction with the carried WP-5 checkpoint:** WP-6 may **add** an originator (the horizon enforcer) while the checkpoint asks whether another can be **removed** (the authority decision, if it becomes message-driven). Both moves are ARB decisions, and both are judged against conversation identity — **not** against the number of entries. The allowlist's size is an artifact of how many distinct conversations exist; it is never itself the rule.

### RED plan — amended by this clarification

Keystone 4 (*"expiry announces exactly one fact, once"*) now additionally asserts the payload is registered and hydratable (the WP-3A pattern), and keystone 11 becomes concrete: **the expiry announcement's `correlation_id` is newly minted and its `causation_id` is null** — a chain start, per Decision A. **No keystone asserts a Contestation reaction**, per Decision B.

---

## 🔍 DECISION A — *Does an expiry announcement exist as published language at all?*

**Commissioned by the ARB (2026-07-31) as the governing question, ahead of provenance. Recommendation only — the ARB rules.**

### The precedent is real, and stronger than one instance

The codebase contains **two** event-free temporal transitions, not one:

| Transition | Emits an event? | Source |
|---|---|---|
| `Challenge::lapse()` — decision-window timeout | **NO** — *"the Lapsed timeout emits NO domain event … Audit observes it via the decision-window timeout log, not an event"* | Round 50-07 v1.2 |
| `Determination::finalize()` — window closed unchallenged | **NO** — *"Final emits no event"* | Round 50-07 v1.2 |

So the architecture does distinguish **business events** from **system timeouts**, deliberately and repeatedly. The ARB's lean — *no publication unless explicitly decided otherwise* — is well founded on this evidence.

### The discriminator that explains BOTH precedents — and separates this case

Ask of each: **who bears the consequence?**

| Case | Timer owner | Consequence owner | Another BC requires the fact to discharge a business responsibility? | Crossing needed? |
|---|---|---|---|---|
| `Challenge::lapse()` | Contestation | **Contestation** (its own state) | **No** — same BC | **No** — nothing to tell |
| `Determination::finalize()` | Adjudication | **Adjudication** (its own state) | **No** — same BC | **No** |
| **Adjudication horizon expiry** | **Adjudication** | **Contestation** — *"the challenge's disposition is Contestation's"* (§197) | **YES** — §197 assigns it a responsibility it cannot discharge unknowingly | **Yes** |

**Both event-free precedents share a property this case does not have:** the context that owns the clock also owns the consequence, so no one needs to be told. The adjudication horizon is the **only** one of the three where the timer fires in one bounded context and the consequence lands in another.

### The architecture's own words point the same way

- §197: *"Expiry **announces** the failure-to-conclude"* — announcement is publication; a state change that tells nobody announces nothing.
- §111: *"An expired adjudication **is business-significant**; its **consumer set** is Q-2-adjacent — flagged, not invented."* A recorded *consumer set* presupposes something to consume.

### The falsification test — could Contestation learn any other way?

Every alternative was enumerated and each fails on an existing authority:

| Alternative | Verdict |
|---|---|
| Contestation runs its **own** MAD timer | ✖ Duplicates a duration it does not own. Q-2/§81: *the APM is the horizon's **enforcer***. Two enforcers of one policy is worse than none |
| Contestation **queries** Adjudication's process state | ✖ Reads another context's **internal process state**; the frozen contract admits only **published payloads** across a boundary. Also a new synchronous crossing — out of scope and unanalysed (per the strategic validation) |
| **Scheduled reconciliation** reading Adjudication's tables | ✖ Same defect, plus it is the ungoverned projection mechanism (Phase 6b of the contract validation), not business collaboration |
| **Adjudication calls Contestation** directly | ✖ Inverts dependency direction; the producer would name its consumer (contract R-7) |

**Decisive consequence:** if expiry publishes nothing, **§197's ruling cannot be honoured at all** — Contestation would never learn that the adjudication of its routed challenge failed to conclude, and the disposition it has been assigned could never be exercised. *"No publication"* does not merely differ from §197; it **makes a ruled policy unimplementable.**

### Recommendation

> ### **RECOMMENDATION TO THE ARB — Decision A = YES:** the expiry announcement should be published integration language.
>
> *Phrasing is deliberate: this is a **recommendation**, not a decision. Evidence → recommendation → **authority decision** — the recommendation does not become the ruling by being well argued, and this analysis has no authority to collapse the two.*
>
> Grounds, in order of weight: (1) **Contestation cannot discharge the business responsibility §197 assigns it** unless it learns the fact; (2) §197 uses *"announces"* and §111 records a *"consumer set"*; (3) every non-event mechanism is refuted by an existing authority; (4) *"No"* renders §197 unimplementable.
>
> **The `lapse()` precedent is respected, not overridden** — it governs same-context timeouts, and this is not one.

### The generalized rule — refined at ARB direction to rest on business responsibility, not boundary-crossing

**Earlier (mine, too weak):** *a temporal transition publishes only when the consequence crosses a boundary.*

> ### **A temporal transition becomes published integration language only when another bounded context cannot CORRECTLY discharge one of its EXPLICITLY ASSIGNED business responsibilities without learning that BUSINESS FACT.**

**Each of the three qualifiers earns its place (final ARB wording):**

| Qualifier | What it excludes |
|---|---|
| **correctly** | Convenience masquerading as necessity — a context that could act, but would act *better* informed, fails the test |
| **explicitly assigned** | Self-declared interest. The responsibility must trace to an **architectural authority** — a ruling, ADR, roadmap entry or the domain model. Here: §197 assigns it in so many words |
| **business fact** | Technical notifications. A crossing must carry **domain language**, not a signal that some mechanism ran |

The rule is strong **because it is hard to satisfy.** Most cross-context information fails it — which is the point: published language stays confined to facts other contexts genuinely depend upon, and does not inflate.

**The necessity constraint is the operative word (ARB refinement).** *Requires* was still too permissive — it is **not enough that another context would benefit**, be better informed, or find the fact useful. It must be **unable to fulfil an assigned responsibility without it**. That is exactly the shape of the §197 argument: Contestation *cannot* exercise the disposition it has been assigned while ignorant of the expiry. It also excludes the large class of **merely informative** events, which is where published-language inflation normally starts.

**Why the refinement matters — boundary-crossing alone is not sufficient.** Plenty of cross-boundary information is projection, analytics, reporting or monitoring, and none of that earns published-language status; the contract validation already found an entire ungoverned projection mechanism doing exactly that. The refined rule covers all four cases:

| Case | Another BC requires the fact to discharge a business responsibility? | Publishes? |
|---|---|---|
| `Challenge::lapse()` | **No** — the consequence stays in Contestation | ❌ |
| `Determination::finalize()` | **No** — stays in Adjudication | ❌ |
| **Adjudication horizon expiry** | **YES** — §197 assigns Contestation the challenge's disposition, which it cannot exercise unignorant of the expiry | ✅ |
| Cross-boundary projections / monitoring / analytics | **No** — a read model's convergence is not a business responsibility discharged by *reacting* to the fact | ❌ |

The discriminator is therefore **business responsibility under a necessity test**, not topology: *"who bears the consequence?"* was the right instinct, but it under-specified — it would have wrongly admitted the fourth row.

### Scope of this rule — accepted as reasoning, NOT promoted to a standard

**Recorded at ARB direction, and consistent with the standing freeze discipline:**

> The generalized rule is accepted **as the reasoning supporting Decision A**, **not** as a project-wide architectural standard. Its broader applicability must be confirmed through additional bounded-context implementations before promotion is even considered.

**A candidate observation is noted without being promoted.** The rule appears to generalize beyond time — drop the word *temporal* and it reads: *a business fact becomes published language when another bounded context cannot fulfil one of its assigned responsibilities without learning it* — which would apply equally to determinations, appointments, evidence and payments. **Not promoted today:** R-38's conceptual freeze admits new concepts only on operational evidence, and R-39 preserved the multi-context bar for exactly this situation (one context's evidence is not a general standard). Recorded as a candidate for future validation, in the same register as *Governance Verification Drift* — named, useful, deliberately un-constitutionalized.

**If the ARB rules NO regardless**, the honest consequence must be recorded: §197's *"return to Contestation"* would have to be **reopened as a business question**, because no conforming mechanism would remain to deliver it. That is an architecture-phase reopening — permitted only on contradictory implementation evidence, which this arguably is.

### Decision B (provenance) — now well-founded, contingent on A = YES

Unchanged from the earlier clarification: the announcement **begins a new conversation** (a timer consumes no message, so `fromConsumed()` has nothing to derive from), making the horizon enforcer a chain origin and requiring an **ARB-approved allowlist entry**. This is the invariant applying, not an exception.

### Scope, restated

Even with A = YES, **WP-6 publishes and registers; it does not consume** — Contestation has no `Routed → disposition` transition (`lapse()` guards `Raised`/`Admitted` only). Published language awaiting a consumer, exactly as WP-3A stood before WP-4.

**Three ARB decisions now pending, in order:** **A** (publish at all — above) → **B** (allowlist entry, if A = YES) → **C** (scope split: WP-6 publishes, WP-6B consumes).

---

## ✅ ARB DECISION A — **APPROVED** (2026-07-31)

> **The expiry announcement IS published integration language.**

**Justification as recorded by the ARB — four points, in order:**

1. Another bounded context has an **explicitly assigned** business responsibility (§197: *"the challenge's disposition is Contestation's per its own rules"*).
2. That responsibility **cannot be correctly discharged** without the expiry fact.
3. **No existing conforming mechanism** delivers that fact (own timer · query · reconciliation · direct call — each refuted against an existing authority).
4. Therefore published integration language is **architecturally justified**.

**This is the formal basis for Decision B.** The generalized rule stands as the *reasoning* for this decision only — **not** a project-wide standard (promotion bar unchanged: R-38 · R-39).

**Progression recorded, because the sequence is itself the method:** boundary crossing → responsibility → **necessity** → governance restraint. The final rule is strong *because it is difficult to satisfy*.

## ✅ DECISION B — direction approved: the announcement BEGINS a new conversation

Following from A rather than introducing a special case: provenance is treated **consistently with the established conversation-origin model**.

| Property | Value | Grounds |
|---|---|---|
| Continues an existing conversation? | **No** | A timer consumes no message; `fromConsumed()` has nothing to derive from |
| Begins one? | **Yes** | *An origin begins a conversation; it never transfers ownership of one* |
| `correlation_id` | **newly minted** | `EventProvenance::start()` |
| `causation_id` | **null** | Chain start (ADR-MP-06) |
| Allowlist | **the horizon enforcer is added** as the origin of the *failure-to-conclude* conversation | ADR-MP-06 — extension authorized under Decision A's basis |

**The invariant is about conversation identity, not system cardinality (ARB correction).** Stated correctly:

> **After WP-6, every integration conversation continues to satisfy "exactly one origin per conversation." Additional conversations may introduce additional origins without violating the invariant.**

The architecture does **not** prefer two origins, or any particular number — the count is an artifact of how many distinct conversations exist. Today: the correction loop (Contestation's routing service) and the failure-to-conclude (the horizon enforcer). The WP-5 checkpoint separately asks whether the *authority-decision* originator can leave. Whatever the count becomes, **the invariant does not move.**

## ⏳ DECISION C — **IMPLEMENTATION BOUNDARY DECISION** (the one remaining gate before RED)

**Reframed at ARB direction.** This was written as a *"scope split"*, which understated it — that phrasing makes it read as scheduling. The real question is a **DDD boundary** question:

> ### **Where does WP-6's bounded-context implementation responsibility end?**

| | **Option A — ends at publication** | **Option B — includes the consumer** |
|---|---|---|
| Flow | timer → expire → **publish → register** ▮END | timer → expire → publish → **consume → Challenge transition** ▮END |
| Contexts touched | **Adjudication** only | **Adjudication + Contestation** |
| Mirrors | **WP-3A** (producer-side) with **WP-6B** mirroring WP-4 | no precedent in this program |
| Requires a modelling answer first? | **No** | **Yes** — the `Routed → disposition` transition does not exist, and its shape (lapse · re-route · dismissal · new state) is a business question |

### Recommendation: **Option A**, on FOUR DDD grounds rather than convenience

1. **The two responsibilities belong to two different bounded contexts.** Adjudication owns **determining and announcing** that it failed to conclude — the aggregate *first determines*, only then announces, so determination is never someone else's to claim later; Contestation owns *what that means for the challenge* (§197). A slice that implements both makes one commit the owner of two contexts' decisions.
2. **Aggregate completion (ARB addition).** *A work package should end where the producing aggregate has fulfilled all of its responsibilities; everything beyond that belongs to a different aggregate's lifecycle.* At publication the adjudication process has expired, emitted the required business language, persisted and registered — **its responsibilities are complete.** This argues from the aggregate, not the context boundary, and it is the cleaner of the two: it would hold even if both aggregates lived in one context.
3. **Option B would couple the producer's completeness to a consumer's existence** — the precise coupling **PB-006's *Registration ≠ Delivery*** exists to prevent. Under Option B, WP-6 could not be "done" until Contestation reacted, which reintroduces the producer→consumer dependency the platform deliberately removed.
4. **The consumer needs an authority that does not yet exist.** Option B would force the `Routed → disposition` modelling decision *inside an implementation commission* — exactly what this plan's Phase 1 forbids (*architectural uncertainty ⇒ stop and report*).

**Evidence that Option A works here:** WP-3A published `ChallengeRouted` with **no consumer at all**; the contract audit ran in between; WP-4 added the consumer one slice later. Published language awaiting a consumer is a **complete** producer-side deliverable — the inbox simply resolves no handler.

**On approval, RED begins immediately** — 11 keystones already planned, none asserting a Contestation reaction.

---

## ✅ DECISION C APPROVED — Option A. RED AUTHORIZED.

**WP-6 ends at publication + registration.** The consumer becomes **WP-6B**. Four DDD grounds accepted: bounded-context ownership · **aggregate completion** · Registration ≠ Delivery · architectural uncertainty belongs before implementation.

## ⚠️ SCOPE FINDING AT RED ENTRY — two of WP-6's four items are NOT implementable under the frozen model

Verified against the code before writing a single test, per Phase 1 (*architectural uncertainty ⇒ stop and report*) and Decision C's fourth ground.

| WP-6 item | Ready? | Evidence |
|---|---|---|
| **Adjudication horizon** (MAD-aware, config + resolver, F-1) | ✅ **READY** | `enforceHorizon()` · `dueForHorizon($asOf)` · `expire()` · `Expired` all exist |
| **Expiry announcement + late-decision conflict** (Decisions A/B, F-2, §197) | ✅ **READY** | Outbox/hydrator machinery exists; the policy is ruled |
| **Evidence-demand deadlines** (§80 · PM-3) | ⛔ **BLOCKED — no Demand concept exists** | `AdjudicationProcessState` has `admittedEvidence` (admissions) and `returnForMoreEvidence()` (the authority's not-yet-decide), but **no demand entity, no per-demand deadline, and no storage column** — the migration has `admitted_evidence` only. PM-3's *"express evidence demands and track them to satisfaction or deadline"* was **never modelled**. Implementing it means inventing a Demand aggregate-internal model + schema + deadline semantics — **new tactical modelling inside an implementation commission** |
| **Finality evaluator** (§142) | ⛔ **BLOCKED — needs knowledge Adjudication does not have** | §142 conditions finality on *"provided no challenge against it is open at closure."* A challenge **against a determination** is a Contestation aggregate (`TargetType::Determination`). Adjudication cannot answer *"is a challenge open against this determination?"* locally — that requires a **new cross-context crossing**, which Phase 8 flagged as an assumption to confirm, with the recorded instruction to **stop and report, not invent** |

**Neither blocker is a defect.** Both are *absences* — capabilities the roadmap names and the frozen model has not yet modelled. Both were flagged in this plan's own Phase 8 before RED began, which is the checkpoint working as designed.

### Consequence for RED

RED is written for the **two ready items** — the horizon (with its config/resolver and the F-1 fix) and the expiry announcement (with the F-2 late-decision conflict). **Keystones 7, 8 and 9 are NOT written**, because writing them requires the two decisions above.

**Recommended disposition (ARB's call):** re-scope WP-6 to *horizon + expiry announcement* — a coherent, complete slice — and split the remainder:
- **WP-6C — Evidence-demand deadlines**, opening with the Demand modelling question (§80/PM-3);
- **WP-6D — Finality evaluator**, opening with the cross-context question (§142) that the strategic validation already predicted would arise for a query-shaped crossing.

This is the same discipline that split WP-3 into 3A/3B and WP-6 into 6/6B: **a slice ends where its authority ends.**

---

## WP-6 RED WRITTEN + CONFIRMED (2026-07-31) — STOP at the RED boundary

**`Tests: 10, Assertions: 14, Errors: 2, Failures: 5`** — 7 failing for the expected reasons, 3 passing (each classified honestly below).

| # | Keystone | Status | Reason |
|---|---|---|---|
| 1 | MAD resolves from configuration | **ERROR** | `AdjudicationDurations` port does not exist |
| 2 | a process **inside** the horizon does not expire | **FAIL** | **F-1 PROVEN EMPIRICALLY** — a process opened *one minute ago* was expired, because the cut-off is `now`, not `now − MAD` |
| 3 | a process **beyond** the horizon expires | **PASSES — for the WRONG reason** | Today `enforceHorizon()` expires *everything*, so this passes vacuously. It becomes meaningful only once keystone 2 passes too. Declared, not banked |
| 4 | **Policy 4** — expiry concludes nothing | **PASSES legitimately** | WP-2's `expire()` already sets no outcome/legitimacy/reason/authority. This is a **regression guard on an existing constitutional guarantee**, not a new capability |
| 5 | expiry announces exactly one fact | **FAIL** | no announcement exists |
| 6 | the announcement is registered for hydration | **FAIL** | no hydrator registered — published language needs both halves |
| 7 | the announcement begins a new conversation | **ERROR** | no outbox row to inspect |
| 8 | a second timer run announces nothing further | **FAIL** | no announcement exists |
| 9 | a **late** decision on an expired process is a **conflict** | **FAIL** | `LateDecisionOnExpiredAdjudication` does not exist — **F-2 confirmed**: the late decision is currently swallowed silently |
| 10 | a **redelivered** decision stays a no-op | **PASSES legitimately** | ADR-T3 behaviour already correct. Its job is to **guard against over-correcting keystone 9** |

**Two Phase-15 diagnoses during authoring, both "incorrect test":**
1. `submitToAuthority()` from `opened` is an illegal transition — §6 requires evidence admitted first. *(Same class as the WP-2 error; the lawful path is `openFor → admitEvidence → submitToAuthority`.)*
2. `receiveRulingDecision()`'s sixth argument is an **`EvidenceSet`**, not a timestamp — **the decision time is the manager's, taken from the injected clock, never a caller parameter.** I had guessed; the signature was read and the test corrected.

**Progress:** ✔ EP-03 · ✔ Phases 1–9 · ✔ Decisions A/B/C · ✔ **RED confirmed** · ⏳ GREEN (awaiting report acceptance) · ⏳ gates · ⏳ dev guide · ⏳ slice acceptance.
