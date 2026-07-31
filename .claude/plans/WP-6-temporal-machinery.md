# WP-6 — Temporal Machinery (horizon · demand deadlines · finality)

**Status:** ✅ **EP-01 APPROVED · DECISION A APPROVED (expiry IS published integration language) · DECISION B direction approved (begins a NEW conversation).** ✅ **DECISION C APPROVED (Option A)** · ✅ **RED CONFIRMED** · ✅ **FRAMEWORK FROZEN · VERIFIED · VALIDATED · OPERATIONAL** (8 dimensions; governance external; survives governance change). **OPERATIONALLY READY — framework/protocol REFINEMENT CLOSED** (further **refinement** requires cross-slice evidence; architectural **evolution** is unaffected). ⏳ **Only gate left: the ARB re-scope ruling, then GREEN.**
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

**Neither of THESE TWO findings is an implementation defect** *(F-1 and F-2 are — see the normalization table; the categories differ and so do their authority paths)*. **Both identify architectural capabilities that have not yet been MODELLED** (ARB wording — *"absence"* can read as accidental; *"not yet modelled"* names the architectural reason). Both were flagged in this plan's own Phase 8 before RED began, which is the checkpoint working as designed.

### Consequence for RED

RED is written for the **two ready items** — the horizon (with its config/resolver and the F-1 fix) and the expiry announcement (with the F-2 late-decision conflict). **Keystones 7, 8 and 9 are NOT written**, because writing them requires the two decisions above.

### RECOMMENDATION TO THE ARB — not a roadmap change (governance correction)

**The earlier draft named "WP-6C" and "WP-6D" as though they existed. They do not.** RED's evidence establishes only the first of these two statements:

| Statement | Established by RED? |
|---|---|
| *These capabilities cannot be implemented under today's approved model* | ✅ **Yes** — evidence above |
| *Therefore they become WP-6C and WP-6D* | ❌ **No** — that is an **authority decision**, and amending the roadmap is not the implementation team's to do |

**Stated in three separated layers (ARB form), so no layer is mistaken for another:**

**1 — OBSERVED EVIDENCE (from RED, not interpretation).**
`AdjudicationProcessState` has `admittedEvidence` and `returnForMoreEvidence()`; it has **no** demand entity, **no** per-demand deadline, and the migration has **no** column for either. §142 conditions finality on *"no challenge open against it"*, and a challenge against a determination is a Contestation aggregate (`TargetType::Determination`), which Adjudication cannot read.

**2 — ARCHITECTURAL INTERPRETATION.**
Two capabilities named by the roadmap **have not yet been modelled**: *Evidence Demand* and *Evidence Demand Deadline* (§80/PM-3), and *finality evaluation across a bounded-context boundary* (§142). The ubiquitous language currently lacks all three concepts. These are **modelling gaps, not coding defects** — implementing either would require minting domain concepts or a new crossing inside an implementation commission.

**3 — AUTHORITY ACTION (the ARB's alone).**
> **Recommendation to the ARB, derived from RED evidence:** RED identified two architectural capabilities that cannot be implemented under the currently approved model. **If the ARB agrees with this analysis**, it may amend the roadmap by re-scoping WP-6 to *horizon + expiry announcement* and introducing follow-on implementation slices **after** the required modelling decisions are approved:
> - a follow-on slice for **evidence-demand deadlines**, opening with the Demand modelling question;
> - a follow-on slice for the **finality evaluator**, opening with the cross-context question the strategic validation already predicted would arise for a query-shaped crossing.

**Referred to descriptively on purpose — no identifier is claimed.** Formal names, numbering and sequencing arise **only** from the ARB's roadmap amendment; naming them here would let this document imply a decision that has not been made. The implementation team discovered a boundary; **the ARB decides how the roadmap changes.**

This follows the same discipline that produced WP-3A/WP-3B and Decision C's Option A: **a slice ends where its authority ends** — and so does a plan.

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

---

# 🧭 ARCHITECTURAL NORMALIZATION (pre-GREEN consistency pass, 2026-07-31)

**Commission:** classify every finding by DDD responsibility and governing authority. **No redesign · no new work packages · no new domain concepts · no roadmap amendment.** Corrections are textual only.

## Remaining inconsistencies found in this document (four)

| # | Inconsistency | Why it matters (DDD) |
|---|---|---|
| **N-1** | **Findings carried no common taxonomy.** F-1 and F-2 sat in one section; the two blocked items in another; the two Phase-15 corrections in a third. Nothing said which *kind* of finding each was | A finding's kind determines its **authority path**. Without a taxonomy, a reader cannot tell which findings can be fixed inside an implementation commission and which need a modelling decision |
| **N-2** | **"Neither finding is an implementation defect" read as contradicting F-1/F-2**, which *are* implementation gaps | Both statements are true of *different* findings. Unclassified, they look inconsistent — the appearance of contradiction was created by the missing taxonomy, not by any error of fact |
| **N-3** | **The two blocked items were grouped as "two capabilities."** They are **different categories** — one lacks *concepts*, the other lacks *collaboration* | They require **different authorities**: a domain-model extension vs an integration design decision. Grouping them would produce one muddled ADR instead of two clean ones |
| **N-4** | **Evidence was not split by kind.** Repository facts and RED behaviour were presented as one undifferentiated "observed evidence" | Static structure and executable behaviour are **independent** forms of proof. Naming both makes the interpretation stronger: the concept is absent *and* the behaviour cannot be satisfied without inventing it |

## Correction — the classification every finding now carries

**Four categories, exactly one per finding:**

| Finding | Category | Repository evidence | Behavioural evidence | Authority required |
|---|---|---|---|---|
| **F-1** `enforceHorizon()` uses `now` as the cut-off | **Implementation Gap** | `dueForHorizon($asOf)` selects `opened_at <= $asOf`; caller supplies `now` | RED keystone 2 **fails**: a process opened one minute ago expired | **None new** — Q-2/§81 already rule that the APM enforces MAD |
| **F-2** a late decision is silently swallowed | **Implementation Gap** | `receiveRulingDecision()` returns early for "concluded **or expired**" in one branch | RED keystone 9 **fails**; keystone 10 **passes**, proving the two cases are distinguishable | **None new** — §197 already ruled it a conflict |
| **Evidence-demand deadlines** (§80/PM-3) | 🔷 **DOMAIN MODELLING GAP** | No `Demand` entity · no deadline value object · no aggregate member · no column in the migration (`admitted_evidence` only) | RED **cannot proceed**: a keystone could not be written without first **minting domain concepts** | **Domain-model extension** — the ubiquitous language lacks *Evidence Demand* and *Evidence Demand Deadline* |
| **Finality evaluation** (§142) | 🔶 **INTEGRATION CONTRACT GAP** | `Determination::finalize()` exists and is guarded; **nothing** in Adjudication can answer *"is a challenge open against this determination?"* — that state is a Contestation aggregate (`TargetType::Determination`) | RED **cannot proceed**: satisfying §142's proviso would require **reading another bounded context** | **Integration design decision** — the crossing's *shape* is undecided (the strategic validation predicted a query-shaped crossing would eventually arise) |
| Two Phase-15 diagnoses (illegal transition · guessed signature) | **Test Gap** | — | Tests failed for reasons unrelated to the behaviour under test | **None** — corrected in the tests; production code untouched |

**N-2 is resolved by the table itself:** F-1/F-2 **are** implementation gaps; the two blocked items **are not**. Both earlier statements stand once each is scoped to its own category.

## Why N-3's split matters, in DDD terms

| | 🔷 Domain Modelling Gap | 🔶 Integration Contract Gap |
|---|---|---|
| What is missing | **Concepts** inside one aggregate's language | **Collaboration** between two bounded contexts |
| Who owns the decision | Adjudication's model — a **tactical DDD** question (aggregate members, invariants, lifecycle) | The **relationship** between Adjudication and Contestation — a **strategic DDD** question |
| Blast radius | Adjudication only; no crossing created | A **new crossing**, and possibly a **new integration style** (query-shaped, which the frozen contract explicitly does not govern) |
| If conflated | One ADR would have to both mint concepts *and* design a crossing — two unrelated decisions in one vote | |

**One creates concepts; one creates collaboration.** Keeping them apart is what will make the eventual ADRs clean — and it is why the recommendation names them separately rather than as "two capabilities."

## Governance discipline — verified, not assumed

☑ No work-package identifier appears outside the correction record that exists to disown it (`grep "WP-6C\|WP-6D"` → two hits, both inside that record).
☑ Every recommendation is phrased *"Recommendation to the ARB"*; none assigns names, numbering or sequencing.
☑ No new domain concept is introduced by this pass — *Evidence Demand* etc. are named as **missing**, never defined.
☑ Historical session entries are **not** rewritten (ES-004.2 append-only); corrections are recorded forward.
☑ The four decision layers stay separate throughout: **evidence → interpretation → recommendation → authority**.

## Final readiness assessment

> ### **The WP-6 planning package is ARCHITECTURALLY NORMALIZED and READY FOR GREEN — for the two Implementation Gaps only.**

**Ready now (no new authority required):** **F-1** (MAD-aware horizon) and **F-2** (late-decision conflict), plus the expiry announcement authorized by Decisions A/B/C. Every rule they must obey is already ruled: Q-2 · Policy 4 · §197 · ADR-MP-06 · ADR-T1.

**Not ready, and correctly out of GREEN:** the 🔷 Domain Modelling Gap and the 🔶 Integration Contract Gap. Each awaits its own authority, and neither can be resolved by writing code.

**GREEN may begin on the ARB's re-scope ruling.** Nothing further in this document requires architectural work.

---

# 🧭 DDD NORMALIZATION — final pass before GREEN (2026-07-31)

**Commission:** verify every finding against DDD responsibility · architectural layer · required authority · required artifact. No redesign · no new concepts · no roadmap change.

## Remaining inconsistency found — one, and it was a real defect in my own RED test

| # | Inconsistency | DDD justification |
|---|---|---|
| **N-5** | **The late-decision exception was placed in the wrong architectural layer.** My RED test imported `Domain\Exception\LateDecisionOnExpiredAdjudication`. The house places **process** exceptions in `Application\Process\Exception\` (`IllegalProcessTransition`) and **aggregate** exceptions in `Domain\Exception\` (`DeterminationAlreadyIssued`, `DeterminationNotFound`) | *"A late decision arrived for an **expired process**"* is a rule about the **process manager's lifecycle**, not about the `Determination` aggregate's invariants. WP-2 deliberately seated the APM and its state machine in the **Application** layer; its exceptions must follow, or the Domain layer would acquire a concept that belongs to orchestration. **Corrected; RED re-confirmed unchanged (10 tests · 2 errors · 5 failures).** |

**This is exactly the defect the ARB's requested "DDD Layer" column was meant to surface** — the taxonomy classified *what* the finding was, and said nothing about *where it belongs*. Adding the column found a misplacement the four-category table could not.

## Phases 1–4 — the complete classification

| Finding | Taxonomy (Phase 1) | **DDD owner / layer** (Phase 2) | **Authority path** (Phase 3) | **Resolving artifact** (Phase 4) |
|---|---|---|---|---|
| **F-1** horizon cut-off is `now`, not `now − MAD` | Implementation Gap | **Application Layer** — the process manager orchestrates; MAD is injected, never owned | **Existing authority sufficient** (Q-2 · §81) | **Production code** |
| **F-2** late decision silently swallowed | Implementation Gap | **Application Layer** — process lifecycle (hence N-5's correction) | **Existing authority sufficient** (§197, already ruled) | **Production code** |
| **Expiry announcement** | authorized scope, not a gap | **Published Language** — producer-side publication + registration | **Existing** — Decisions A/B/C | **Production code** (event · outbox mapping · hydrator · allowlist entry) |
| 🔷 **Evidence Demand / Deadline** | Domain Modelling Gap | **Domain Model / Aggregate** — the language itself lacks the concepts | **Modelling authority required** (ARB) | **ADR + domain model** |
| 🔶 **Finality evaluation** | Integration Contract Gap | **Context Mapping** — a relationship between Adjudication and Contestation | **Integration authority required** (ARB) | **Context map / integration contract + ADR** |
| Two Phase-15 diagnoses · N-5 | Test Gap | **Verification** | **Implementation authority sufficient** | **Test suite** |

**Each row has exactly one taxonomy, one owner, one authority path, one artifact.** No overlaps: F-1/F-2 touch orchestration only; 🔷 touches language; 🔶 touches a relationship; test gaps touch verification. The artifact always matches the authority — *code where a rule already exists, an ADR where a concept must be minted, a context map where a relationship must be designed.*

## Phase 5 — implementation readiness

**GREEN contains only findings whose authority already exists.** Verified item by item:

| GREEN item | New ubiquitous language? | New aggregate concept? | New bounded-context relationship? |
|---|---|---|---|
| MAD-aware horizon + duration resolver | **No** — *adjudication horizon* and *MAD* are §27/§81's own terms | No | No |
| Late-decision conflict | **No** — §197's own words (*"dead-letters as a conflict"*) | No | No |
| Expiry announcement | **No** — and this deserves the distinction: it introduces a new **event name** for an **existing** concept (`Expired`, §57 · `AdjudicationProcessStatus::Expired`). Naming an existing fact is not minting a new one | No | **No** — the crossing to Contestation is the established Published-Language edge; only the *consumer* would be new, and that is out of scope by Decision C |

**Excluded from GREEN, correctly:** 🔷 requires new ubiquitous language; 🔶 requires a new bounded-context relationship. Neither can be resolved by writing code.

## Final assessment

> ### **The WP-6 planning package is DDD-NORMALIZED and ARCHITECTURALLY READY FOR GREEN.**

☑ one taxonomy per finding · ☑ one DDD owner per finding · ☑ one authority path per finding · ☑ one governing artifact per finding · ☑ **GREEN contains only implementation work already authorized.**

**Observation recorded, deliberately NOT promoted.** The five questions this pass converged on — *what kind of finding · where does it belong · who owns it · what artifact resolves it · can implementation proceed now* — are filed with the other un-promoted observations (Governance Verification Drift · the published-language necessity rule · discovery-does-not-create-architecture). Let them prove themselves across further work packages before any elevation is considered (R-38 · R-39). **N-5 is their first OPERATIONAL VALIDATION** (ARB wording): the added dimension detected a defect the previous framework **structurally could not** — stronger than supportive anecdote, still below the promotion threshold.

---

# 🧭 OWNERSHIP VERIFICATION — final pass before GREEN (2026-07-31)

**Commission:** verify that documented **architectural layer** and actual **business owner** agree. No redesign · no new concepts · no roadmap change.

## Remaining inconsistencies found — two, both created by leaving ownership implicit

| # | Inconsistency | DDD justification |
|---|---|---|
| **O-1** | **The two blocked items were given a LAYER but have NO OWNER.** The previous table assigned 🔷 to *"Domain Model / Aggregate"* and 🔶 to *"Context Mapping"* — which reads as though an owner exists. **Neither does:** the aggregate that would own *Evidence Demand* has not been modelled, and the Adjudication ↔ Contestation relationship has no owner for a **query-shaped** crossing (the frozen contract governs event-carried async integration only) | Naming a layer for an **unowned** concern overstates readiness. A layer says *where a thing would live*; an owner says *who is answerable for it*. **Correction:** owner recorded as **UNASSIGNED — pending the modelling / integration decision.** This yields a mechanical readiness test, below |
| **O-2** | **The expiry announcement risked TWO owners** — the Adjudication BC *as producer* (owns the published language) and the process manager *as emitter* (executes the publication). Left unstated, a reader could assign either | The frozen contract settles it: **the producing bounded context owns publication and registration** (R-7 — the producer never names its consumers, and owns both halves of published-language status). The APM is the **emitter**, not the owner. **Correction:** one owner — **Adjudication BC (producer)**; the APM emits under it |

## Phase 1 + 2 — layer and business owner, one each

| Finding | Architectural layer | **Business owner** | Consistent? |
|---|---|---|---|
| **F-1** horizon cut-off | Application | **Adjudication Process Manager** | ✔ orchestration stays in Application |
| **F-2** late-decision conflict | Application | **Adjudication Process Manager** | ✔ same — and N-5's correction was exactly this alignment |
| **MAD resolution** (config + port) | Application port · Infrastructure adapter | **Q-2 / ARB owns the DURATION; the APM owns ENFORCEMENT** | ✔ **This split is not a defect — it is Q-2's rule made visible:** *"the APM enforces a duration it does not own"* |
| **Expiry announcement** | Published Language | **Adjudication BC (producer)** — the APM emits under it (O-2) | ✔ producer owns publication + registration |
| 🔷 **Evidence Demand / Deadline** | Domain (where it *would* live) | ⛔ **UNASSIGNED** — the owning aggregate is not modelled | ⚠️ layer known, owner absent |
| 🔶 **Finality evaluation** | Context Mapping | ⛔ **UNASSIGNED** — no owner exists for a query-shaped crossing | ⚠️ layer known, owner absent |
| Phase-15 diagnoses · N-5 | Verification | **Test suite (development team)** | ✔ verification stays in verification |

**No responsibility migrates across layers:** orchestration stays in Application · aggregate invariants stay in Domain · collaboration stays in Context Mapping · verification stays in the test suite. N-5 was the one violation and it is corrected.

## Phase 3 — artifact alignment with the OWNER (not merely the layer)

| Owner | Resolving artifact | Aligned? |
|---|---|---|
| Adjudication Process Manager | **Production code** | ✔ a process owner resolves its concerns by code |
| Adjudication BC (producer) | **Production code** — event · outbox mapping · hydrator · registration | ✔ the producer owns both halves |
| Q-2 / ARB (durations) | **Config value, INTERIM-marked, naming the decision it awaits** | ✔ the artifact records that the number is *not* the code's to choose |
| ⛔ UNASSIGNED (🔷) | **ADR + domain model** — which *creates* the owner | ✔ the artifact's job is to establish ownership, not to work around its absence |
| ⛔ UNASSIGNED (🔶) | **Context map / integration contract + ADR** — likewise | ✔ |
| Test suite | **Tests** | ✔ |

**No artifact resolves a concern outside its owner's responsibility.** Note the shape of the last two rows: where the owner is absent, the artifact's purpose is to **establish the owner** — which is precisely why code cannot substitute for it.

## Phase 4 — GREEN readiness, with a mechanical test

**The ownership column yields a sharper readiness test than any previous pass:**

> ### **An item with no business owner cannot be implemented.**

Not because implementation is technically hard, but because there would be **nobody answerable for the invariant the code would encode.** That test classifies WP-6 without judgement calls:

| GREEN item | Owner | Existing authority | Correct artifact | New business concept? | **GREEN?** |
|---|---|---|---|---|---|
| MAD-aware horizon | APM (+ ARB owns the number) | ✔ Q-2 · §81 | code + INTERIM config | No | ✅ |
| Late-decision conflict | APM | ✔ §197 | code | No | ✅ |
| Expiry announcement | Adjudication BC (producer) | ✔ Decisions A/B/C | code | No — a new **name** for `Expired` (§57), not a new concept | ✅ |
| 🔷 Evidence Demand | ⛔ none | ✖ | ADR + domain model | **Yes** | ❌ |
| 🔶 Finality evaluation | ⛔ none | ✖ | context map + ADR | **Yes** (a relationship) | ❌ |

## Final assessment

> ### **The WP-6 planning package is DDD-NORMALIZED, OWNERSHIP-VERIFIED, and READY FOR GREEN.**

Every GREEN finding carries **one taxonomy · one architectural layer · one business owner · one authority path · one resolving artifact.**

**Wording corrected at ARB direction:** N-5 is the framework's **first OPERATIONAL VALIDATION**, not merely *"first evidence in its favour"* — the added dimension detected a defect the previous framework **structurally could not**. That is stronger than supportive anecdote, and still **below the promotion threshold** (R-38 · R-39): one validation in one work package. The framework's three stages — **taxonomy** (*what kind?*) → **layer** (*where does it belong?*) → **ownership** (*who is answerable?*) — stay in the un-promoted observation register until further work packages test them.

---

# 🧭 OWNERSHIP SEMANTICS — final pass before GREEN (2026-07-31)

**Commission:** separate *architectural location* · *business ownership* · *execution responsibility*, and never let them collapse. No redesign · no new concepts · no invented future owners.

## Remaining ambiguities found — three, and the first is a correction to my own previous correction

| # | Ambiguity | DDD justification |
|---|---|---|
| **O-3** | **My O-2 resolution collapsed a distinction instead of resolving it.** I wrote *"one owner — the BC; the APM is the emitter, not the owner"*, treating the two as **competing**. They are **complementary** | Two different accountabilities: the **Adjudication BC** owns the *meaning* of "adjudication expired" (only a bounded context can own a business fact's meaning); the **APM** owns *performing* the publication. Recording only one erases a real responsibility. Corrected to **business owner + execution owner** |
| **O-4** | **🔷 Evidence Demand was marked UNASSIGNED on BOTH axes — overstating the gap.** Its **execution owner is already assigned**: EPIC-004K **PM-3** gives the APM *"express evidence demands and track them to satisfaction or deadline"* | The executor is **known**; the **concept** is not. That asymmetry is precisely what makes this a **Domain Modelling Gap** rather than an execution gap — there is someone to run it and nothing yet for them to run |
| **O-5** | **🔶 Finality was marked UNASSIGNED flatly — understating what exists.** Finality's *legality* **is** owned: `Determination::finalize()` guards `Issued → Final`, and this plan's Phase 3 already records *"the Determination aggregate owns finality's legality"* | What is unowned is **the collaboration** — answering *"is a challenge open against this determination?"* across a boundary. Naming it precisely is what distinguishes an **Integration Contract Gap** from a domain gap: the invariant has an owner; the **crossing** does not |

## Phases 1 + 2 — location · business ownership · execution responsibility · responsibility type

| Finding | Layer | **Business owner** | **Execution owner** | **Responsibility type** |
|---|---|---|---|---|
| **F-1** horizon cut-off | Application | **Adjudication BC** (the meaning of *Expired*) · *duration parameter: Q-2/ARB* | **Adjudication Process Manager** | **Execution** |
| **F-2** late-decision conflict | Application | **Adjudication BC** (§197 ruled the meaning) | **Adjudication Process Manager** | **Execution** |
| **Expiry announcement** | Published Language | **Adjudication BC** — owns the published fact | **Adjudication Process Manager** — emits it | **Business + Execution** |
| 🔷 **Evidence Demand / Deadline** | Domain (destination) | ⛔ **UNASSIGNED** — no aggregate owns the concept | **APM** (assigned by **PM-3**) | **Business (future)** |
| 🔶 **Finality evaluation** | Context Mapping | **Determination aggregate** owns finality's *legality*; ⛔ **the collaboration is UNASSIGNED** | ⛔ **UNASSIGNED** — no evaluator exists, and whether it may query is undecided | **Collaboration** |
| Phase-15 diagnoses · N-5 | Verification | **Development team** | **Test suite** | **Verification** |

**No future owner is invented.** `UNASSIGNED` appears only where no accountable owner exists today, and in each case the *reason* is named — an unmodelled concept (🔷) or an undesigned crossing (🔶). "A future aggregate" is an architectural **destination**, never an owner.

## Phase 4 — consistency check

| Rule | Holds? |
|---|---|
| A bounded context owns business **meaning** | ✔ Adjudication BC owns *Expired* and the §197 conflict rule |
| Application services / process managers own **orchestration** | ✔ the APM executes horizon enforcement, the conflict guard, and the emission — and owns none of the meanings |
| Aggregates own **invariants** | ✔ `Determination::finalize()` guards the `Issued → Final` legality |
| Context mappings own **collaboration** | ✔ and this is exactly the slot that is empty for 🔶 |
| Tests own **verification** | ✔ |

**No responsibility overlaps without explicit distinction.** The one place two accountabilities meet — the expiry announcement — is now recorded as **Business + Execution with two named owners**, not as one owner absorbing the other.

## Phase 3 — GREEN readiness

| GREEN item | Business owner | Execution owner | Authority | Artifact approved? |
|---|---|---|---|---|
| MAD-aware horizon | ✔ Adjudication BC (+ Q-2/ARB parameter) | ✔ APM | ✔ Q-2 · §81 | ✔ code + INTERIM config |
| Late-decision conflict | ✔ Adjudication BC | ✔ APM | ✔ §197 | ✔ code |
| Expiry announcement | ✔ Adjudication BC | ✔ APM | ✔ Decisions A/B/C | ✔ code |
| 🔷 Evidence Demand | ⛔ UNASSIGNED | ✔ APM | ✖ | ✖ |
| 🔶 Finality evaluation | ⛔ collaboration UNASSIGNED | ⛔ UNASSIGNED | ✖ | ✖ |

**Both UNASSIGNED items remain outside GREEN.** Note that 🔷 has an execution owner and is *still* excluded — **an assigned executor does not substitute for an unowned concept.** That is the sharper form of the earlier readiness test.

## Final assessment

> ### **The WP-6 planning package is DDD-NORMALIZED, OWNERSHIP-VERIFIED, SEMANTICALLY SEPARATED, and READY FOR GREEN.**

Every GREEN finding carries **one taxonomy · one layer · one business owner · one execution owner · one responsibility type · one authority path · one resolving artifact.**

**The framework's dimensions, and what each answers:** *taxonomy* — what kind of finding · *layer* — where it belongs · *owner* — who is accountable · *responsibility type* — what kind of accountability · *artifact* — what resolves it · *authority* — who may decide.

**Operational validations to date: three** — N-5 (layer found a misplacement), O-1 (ownership found a layer with no owner), and O-4/O-5 (responsibility type found ownership recorded at the wrong granularity in both directions — one overstated, one understated). **Still un-promoted** (R-38 · R-39): the validations come from **one** work package, and the promotion bar asks for evidence across several. Recorded in the observation register; elevation is not proposed.

---

# 🧭 SEMANTIC NORMALIZATION — the LAST pass; the framework is now FROZEN (2026-07-31)

**Commission:** stabilize vocabulary. **No new dimension · no redesign · no new governance.** Clarity only.

## Remaining semantic inconsistencies — three leaks, each a dimension answering another's question

| # | Leak | DDD justification |
|---|---|---|
| **S-1** | 🔷's Responsibility Type read **"Business (future)"** — *"future"* is a **GREEN-readiness** statement smuggled into a responsibility classification | Responsibility Type must answer *what kind of accountability*, full stop. Whether it can proceed **today** is GREEN Readiness' question. Corrected to **Business**; the timing lives in its own column |
| **S-2** | 🔷's DDD Layer read **"Domain (destination)"** — *"destination"* smuggles an **ownership** statement into a location classification | Layer answers *where it belongs*, which is knowable even when nothing lives there yet. That nothing does is already carried by **Business Owner = UNASSIGNED**. Corrected to **Domain** |
| **S-3** | F-1's Business Owner read **"Adjudication BC · duration parameter: Q-2/ARB"** — **two owners in one cell**, and a duplicate: MAD's ownership already has its own row | One dimension, one answer. F-1 is a finding about **enforcement**, whose business meaning the BC owns. The duration's ownership is a *different concern*, already recorded. Corrected to **Adjudication BC** |

**One redundancy noted and deliberately NOT corrected:** taxonomy *Test Gap* maps 1:1 to responsibility type *Verification*. They answer different questions (*what kind of finding* vs *what kind of accountability*) and simply coincide for this category. A 1:1 mapping is not an overlap — collapsing them would lose the distinction everywhere else.

## The stabilized framework — two groups, eight dimensions, one question each

### Group A — ARCHITECTURE (describes the architecture)

| Dimension | Answers exactly |
|---|---|
| **Taxonomy** | What kind of finding is this? |
| **DDD Layer** | Where does it belong architecturally? |
| **Business Owner** | Who owns the business **meaning**? |
| **Responsibility Type** | What kind of responsibility is involved? |

### Group B — EXECUTION (describes implementation)

| Dimension | Answers exactly |
|---|---|
| **Execution Responsibility Owner** | Who **performs** the implementation responsibility? |
| **Authority** | Who may authorize change? |
| **Artifact** | What resolves it? |
| **GREEN Readiness** | Can implementation proceed **today**? |

**Rename adopted (ARB):** *Execution Owner* → **Execution Responsibility Owner.** Placed beside *Business Owner*, the old name read as a **competing** owner; the new one makes explicit that they own **different responsibilities** — which is exactly O-3's finding, now carried by the vocabulary itself rather than by a footnote.

## The normalized classification

**Group A — Architecture**

| Finding | Taxonomy | DDD Layer | Business Owner | Responsibility Type |
|---|---|---|---|---|
| **F-1** horizon cut-off | Implementation Gap | Application | **Adjudication BC** | Execution |
| **F-2** late-decision conflict | Implementation Gap | Application | **Adjudication BC** | Execution |
| **MAD resolution** | Implementation Gap | Application port · Infrastructure adapter | **Q-2 / ARB** (owns the duration) | Business |
| **Expiry announcement** | authorized scope | Published Language | **Adjudication BC** | Business + Execution |
| 🔷 **Evidence Demand / Deadline** | Domain Modelling Gap | **Domain** | ⛔ **UNASSIGNED** | **Business** |
| 🔶 **Finality evaluation** | Integration Contract Gap | Context Mapping | Determination aggregate owns *legality*; ⛔ **collaboration UNASSIGNED** | Collaboration |
| Phase-15 · N-5 | Test Gap | Verification | Development team | Verification |

**Group B — Execution**

| Finding | Execution Responsibility Owner | Authority | Artifact | GREEN today? |
|---|---|---|---|---|
| **F-1** | **Adjudication Process Manager** | Existing — Q-2 · §81 | Production code | ✅ |
| **F-2** | **Adjudication Process Manager** | Existing — §197 | Production code | ✅ |
| **MAD resolution** | APM (enforces) via the durations port | Existing — roadmap §WP-6 | Code + **INTERIM** config naming its pending decision | ✅ |
| **Expiry announcement** | **Adjudication Process Manager** (emits) | Existing — Decisions A/B/C | Production code | ✅ |
| 🔷 **Evidence Demand** | **APM** (assigned by PM-3) | **Modelling authority required** | ADR + domain model | ❌ |
| 🔶 **Finality evaluation** | ⛔ UNASSIGNED | **Integration authority required** | Context map / contract + ADR | ❌ |
| Phase-15 · N-5 | Test suite | Implementation | Tests | ✅ done |

☑ Phase 3 — Business Owner owns meaning; Execution Responsibility Owner owns performance; **complementary, never competing** (O-3 is the reference case, and the expiry announcement is the row where both appear).
☑ Phase 4 — an execution responsibility exists **without** a model (🔷); a model exists **without** collaboration (🔶); `UNASSIGNED` appears only where no accountable owner exists today. **No future owner invented.**
☑ Phase 5 — no dimension answers another's question; architecture and execution are visually separated; terminology is internally consistent; **no dimension was added.**

## 🔒 FRAMEWORK FROZEN — the next evidence must be stability, not sophistication

**Adopted at ARB recommendation.** The framework has produced **three operational validations**, all from **one** work package: **N-5** (Layer exposed a misplacement) · **O-1** (Ownership exposed an unowned location) · **O-4/O-5** (Responsibility semantics exposed two *opposite* ownership errors).

> **The framework is used UNCHANGED on the next work packages.** No dimension is added, renamed or subdivided. Cross-slice evidence — does it keep finding real issues in *independent* slices? — is the only evidence that could support promotion (R-38 · R-39). **Until then the most valuable property it can demonstrate is stability, not additional sophistication.**

*One reusable insight is recorded verbatim as the framework's most portable output, still un-promoted:* **an assigned executor does not substitute for an unowned concept** — it prevents an implementation team from mistaking orchestration for modelling.

## Final assessment

> ### **The WP-6 planning package is SEMANTICALLY NORMALIZED and READY FOR GREEN. The review framework is frozen. No further architectural, ownership or semantic work remains.**

---

# ✅ FRAMEWORK FREEZE VERIFICATION (2026-07-31) — final; attention returns to delivery

**Commission:** verify internal coherence and make the freeze criteria explicit. **No dimension added · none renamed · nothing redesigned.**

## Phase 1 — Semantic independence: verified, with one clarification

**No dimension's *description* depends on another's answer.** But the audit found something worth stating explicitly:

| # | Clarification (not an inconsistency) | Why it matters |
|---|---|---|
| **V-1** | **Two dimensions are DERIVED, not independently asserted** — but of **different kinds** (corrected below): **Authority** is **governance-derived** (Taxonomy *informs* it; governance rules mediate). **GREEN Readiness** is **derived from the execution dimensions** | Recording this makes readiness **non-negotiable**: it is an observable consequence, not a subjective judgement. Nobody can assert "ready" by opinion — it changes only when an execution dimension changes |

## Phase 2 — Architectural relationships (clarified, not redefined)

**Group A — Architecture: each dimension constrains the next.**

```
Taxonomy            classifies the finding
     ↓  (structural)
DDD Layer           locates it architecturally
     ↓  (structural)
Business Owner      who owns the meaning at that location
     ↓  (structural)
Responsibility Type that owner's kind of accountability
```

**Group B — Execution.**

```
Execution Responsibility Owner    who performs it
     ⋯  (governance-mediated)
Authority                         who may authorize change
     ↓  (structural — an artifact must match its authority)
Artifact                          what resolves it
     ⋯  (derived from the execution dimensions)
GREEN Readiness                   can implementation proceed today?
```

### Every dependency classified — structural · governance-derived · implementation-derived

| Dependency | Classification | Evidence | Wording |
|---|---|---|---|
| Taxonomy → DDD Layer → Business Owner → Responsibility Type | **Structural** | Holds regardless of who decides anything; a location has an owner and an owner has a kind of accountability | *derives from* |
| **Taxonomy ⋯ Authority** | **GOVERNANCE-DERIVED** *(corrected — previously overstated)* | Change governance — send Integration Contract changes to an Architecture Council instead of the ARB — and the **taxonomy is unchanged while the authority changes.** So taxonomy cannot *determine* authority | **"Taxonomy SELECTS THE APPLICABLE GOVERNANCE RULE; the governance rule IDENTIFIES THE AUTHORITY."** Taxonomy never points at a person or committee — it points at a **policy**. Chain: *Finding → Taxonomy → **Governance Rule** → Authority* |
| Authority → Artifact | **Structural** | An artifact that cannot express the decision its authority must make is the wrong artifact — true under any governance |
| Owner · Authority · Artifact ⋯ GREEN Readiness | **IMPLEMENTATION-DERIVED** *(corrected — previously an equation)* | In WP-6 those three happened to encode scope, implementation boundary and open modelling questions **because Decision C settled the boundary and the ARB settled the scope**. That is a property of **this** work package, not of the framework | **"GREEN Readiness is derived from the execution dimensions."** No conjunction is asserted |

**The single cross-group link is Taxonomy ⋯ Authority, and it is governance-mediated, not structural.** That is what keeps the taxonomy useful without making it authoritative: it selects *which governance rule applies*, and never *what that rule says or who it appoints*.

## Phase 3 — Freeze criteria, stated operationally

> ### **The framework is FROZEN FOR REFINEMENT, NOT FOR USE.**
>
> Future work packages may produce evidence that challenges it, and **should**. But **no refinement is considered unless CROSS-SLICE OPERATIONAL EVIDENCE demonstrates that an existing dimension is insufficient** — not because a cleaner formulation can be imagined.

**The freeze rests on evidence, recorded so a future reader can test it:**
- three operational validations exist — **N-5** (Layer exposed a misplacement) · **O-1** (Ownership exposed an unowned location) · **O-4/O-5** (Responsibility Type exposed two *opposite* ownership errors);

**What the freeze does NOT freeze:** today's **governance decisions**. Frozen are the **dimensions, the vocabulary and the responsibilities**. The *values* — who the authority is, which artifact is approved, what is in scope — change whenever governance changes, and the framework absorbs that without amendment. **Test:** if the ARB were replaced tomorrow by an Architecture Council for integration decisions, every dimension would still ask the same question; only the Authority column's *value* would change. A framework that broke under that substitution would have frozen governance, not structure.

- **all three originate from ONE work package (WP-6)**;
- therefore further refinement would lack independent evidence, and would be preference dressed as improvement.

**Reopening condition (singular, mirroring the architecture-phase closure):** a later, **independent** slice in which the framework **fails to classify or fails to expose** a real issue. Anything else is not evidence.

## Phase 4 — GREEN independence: verified

**GREEN does not depend on any further framework refinement.** Checked per item — every GREEN row has all eight dimensions populated, with **no `UNASSIGNED` and no open question**:

| GREEN item | All 8 dimensions populated? | Any dimension missing to proceed? |
|---|---|---|
| MAD-aware horizon (F-1) | ✔ | **No** |
| Late-decision conflict (F-2) | ✔ | **No** |
| Expiry announcement | ✔ | **No** |

**The test applied honestly:** *if GREEN required another review dimension, the framework would not actually be frozen.* It does not. The two excluded items are excluded by **populated** dimensions (`Business Owner = UNASSIGNED`, `Authority = required`) — the framework **classified** them rather than failing to.

## Deliverable 4 — recommendation

> **The framework should remain UNCHANGED for future work packages.** Attention returns to software delivery. Discussion of the framework should be triggered **only** by a later independent slice where it fails to classify or expose an issue — never by a theoretically cleaner formulation.

**Nothing in the WP-6 planning package now requires architectural, ownership, semantic or framework work.** The single remaining gate is the ARB's re-scope ruling, after which GREEN implements three items whose authority already exists.

## Final freeze validation (2026-07-31) — structure separated from governance

| Check | Status |
|---|---|
| The frozen framework contains only **stable architectural concepts** | ✅ — dimensions, vocabulary, responsibilities. Governance *values* are not frozen |
| The framework **survives governance changes** | ✅ — substitute an Architecture Council for the ARB on integration decisions and every dimension still asks the same question; only the Authority column's **value** changes |
| **No operational observation is elevated to framework semantics** | ✅ — the two that were are corrected and reclassified |
| Every dependency classified structural / governance-derived / implementation-derived | ✅ — table above |
| The freeze is **operational, not declarative** | ✅ — reopens only on a later *independent* slice where the framework fails to classify or expose a real issue |

**DDD justification for the two corrections:**

- **Authority is governance-mediated, not structurally determined.** Taxonomy is a property of the *finding*; authority is a property of the *organisation deciding about it*. Collapsing them would embed an organisational arrangement into an architectural framework — the same category error N-5 corrected in code, where an orchestrator held a bounded context's meaning.
- **GREEN Readiness must not be an equation.** Its three factors sufficed *in WP-6* only because Decision C settled the implementation boundary and the ARB settled the scope. Another slice could have an owner, an authority and an artifact and still be blocked by an unresolved modelling question elsewhere. A conjunction would generalize one slice's contingency into a law.

> ### **VALIDATED. The framework contains only stable architectural concepts, survives governance change, and elevates no operational observation. Framework REFINEMENT ends here; framework APPLICATION begins here — the next evidence comes from applying it unchanged to independent slices.**

---

# ➡️ TRANSITION TO OPERATIONAL USE (2026-07-31) — refinement ends, application begins

**Commission:** transition review, not refinement. No dimension added · nothing redesigned · governance untouched.

## 1. Remaining operational inconsistencies — two, both wording

| # | Correction | DDD justification |
|---|---|---|
| **T-1** | *"Taxonomy informs Authority"* → **"Taxonomy SELECTS the applicable governance RULE; the governance rule IDENTIFIES the authority."** | *Informs* still pointed taxonomy at a **person or committee**. It points at a **policy**. In DDD terms taxonomy is a property of the finding, a governance rule is a **policy** over findings of that kind, and the authority is whoever that policy appoints. Two hops, not one — and the distinction becomes load-bearing the moment governance rules become configurable, because only the middle hop would change |
| **T-2** | *"Framework review ENDS here"* → **"Framework REFINEMENT ends here; framework APPLICATION begins here."** | The framework is not finished forever; it has changed **phase** — from design artifact to operational instrument. Absolute wording would misdescribe an evidence-driven process as a completed one, and would make a later legitimate challenge look like a violation |

## 2. Phase 1 — operational invariants: all eight are stable concepts

| Dimension | Stable concept, not a WP-6 observation? |
|---|---|
| Taxonomy · DDD Layer · Business Owner · Responsibility Type | ✅ each names a DDD concept that exists in any bounded context |
| Execution Responsibility Owner · Authority · Artifact | ✅ any change has a performer, an approver and a resolving artifact |
| GREEN Readiness | ✅ as a **question** (*can implementation proceed today?*); its **answer** is always slice-specific — which is why the conjunction was removed |

## 3. Phase 2 — every dependency in exactly one category

| Dependency | Category | In one category only? |
|---|---|---|
| Taxonomy → DDD Layer → Business Owner → Responsibility Type | **Structural** | ✅ |
| Authority → Artifact | **Structural** | ✅ |
| **Taxonomy ⋯ Governance Rule ⋯ Authority** | **Governance-derived** | ✅ — and the two hops are now explicit |
| Execution dimensions ⋯ GREEN Readiness | **Implementation-derived** | ✅ |

**No dependency belongs to two categories, and no arrow implies direct causality where governance mediates.**

## 4. Phase 3 — freeze boundaries

| Frozen | NOT frozen |
|---|---|
| Dimensions · vocabulary · responsibilities | Governance bodies · organizational structures · approval assignments · roadmap sequencing |

**Validity under governance change re-tested:** replace the ARB with an Architecture Council for integration decisions — every dimension asks the same question, the **governance rule** changes, the **Authority value** changes, and **no dimension, name or responsibility requires amendment.** ✅

## 5. Phase 4 — operational readiness

| Check | Status |
|---|---|
| Framework contains only stable architectural concepts | ✅ |
| Governance remains **external** to the framework | ✅ — the framework *asks* who may authorize; it never *states* who does |
| Implementation observations are not generalized into framework laws | ✅ — the two that were (Taxonomy→Authority, the GREEN conjunction) are corrected and reclassified |
| Ready to be applied **unchanged** to future work packages | ✅ |

> ### **Framework refinement is complete. Future evidence will come exclusively from operational application across independent slices.**

**Applied unchanged from the next slice onward.** Reopening remains singular: a later **independent** slice where the framework fails to classify or fails to expose a real issue. Four consecutive passes have *reduced over-generalization* rather than added capability — the signal that the design phase is genuinely over.

---

# 📋 OPERATIONAL ADOPTION — the review protocol (2026-07-31)

**No framework change is proposed here.** The eight dimensions, their vocabulary, their responsibilities and the governance model are **untouched**. This defines only *how evidence about the framework is collected while it stays unchanged.*

## The protocol — five steps, run once at each work-package closure

1. Complete the work package (GREEN · gates · dev guide · slice acceptance).
2. Record the **framework outcome** — exactly one of the four categories below.
3. Record any **classification failures** — a real finding the framework could not place in one taxonomy, one layer, one owner, one responsibility type.
4. Determine whether the **reopening criteria** were met.
5. Make exactly one **recommendation**: *continue unchanged* **or** *reopen framework*.

**The four outcomes — closed set, no additions:**

| Outcome | Meaning |
|---|---|
| **Exposed a previously unknown defect** | The framework found something real that was already in the work |
| **Prevented a potential architectural defect** | The framework stopped a mistake before it landed |
| **Required no framework changes** | It classified everything and needed no amendment |
| **Failed to classify a finding** | A real issue it could not place — **the only outcome that can trigger reopening** |

**Reporting semantics — the four outcomes are mutually exclusive FOR REPORTING, not mutually exclusive in reality:**

> **Multiple outcomes may be true during a work package. The operational record reports only the STRONGEST applicable outcome, to keep slices comparable.**

Order: *Exposed* > *Prevented* > *Required no changes*. (A framework that exposed a defect trivially also required no changes; reporting the weaker one would hide the stronger evidence.) **`Failed to classify` is recorded whenever it occurs, regardless of what else also applies** — it is the only reopening trigger, so it must never be masked by a stronger-sounding outcome. **This is a REPORTING CONVENTION, not a framework rule.**

**Reopening — unchanged and singular:** permitted **only** on evidence that the framework cannot correctly classify or expose an issue **in an independent slice**. **Never** for elegance, preference, simplification or theoretical improvement.

## Reusable template — copy into each work package's closure record

```markdown
### Framework Operational Record — WP-nn

| Field | Value |
|---|---|
| Work Package | WP-nn |
| Framework Outcome | Exposed / Prevented / Required no changes / Failed to classify |
| **Evidence Source** | Architectural Review · Code Review · RED Test · GREEN Verification · Static Analysis · Integration Verification · Runtime Observation |
| Evidence (1–2 lines, concrete) | |
| Classification Failures | none · or: <finding> could not be placed in <dimension> |
| Reopening Criteria Met? | Yes / No |
| Recommendation | Continue unchanged / Reopen framework |
```

## WP-6's own record — opened, not yet closed

| Field | Value |
|---|---|
| Work Package | **WP-6** |
| Framework Outcome | **Exposed a previously unknown defect** *(strongest applicable; see §Reporting semantics)* |
| **Evidence Source** | **Architectural Review** — N-5 · O-1 · O-4 · O-5 · S-1..S-3 (the *subject* of N-5 was a RED Test, but the **Architectural Review** found it) |
| Evidence so far | **N-5** — a committed RED test imported a **Domain** exception for an **Application-layer process** rule; the DDD-layer dimension exposed it. Plus **O-1 · O-4 · O-5 · S-1..S-3**, each a mis-record caught **before** GREEN |
| Classification Failures | **none so far** — every finding placed in exactly one taxonomy, layer, owner and responsibility type, including the two whose owner is legitimately `UNASSIGNED` |
| Reopening Criteria Met? | **No** — no finding failed to classify |
| Recommendation | **Continue unchanged** |

**Independence honoured:** all of that evidence was gathered **without** changing a taxonomy, dimension, term or governance rule. *The framework is the measuring instrument, not the subject of measurement.*

> ### **The framework is operationally adopted. Future confidence will come from repeated successful application across independent work packages, not from additional theoretical refinement.**

## Protocol verification (2026-07-31)

| Phase | Check | Status |
|---|---|---|
| **1** Completeness | completion · framework outcome · classification failures · reopening evaluation · recommendation | ✅ all five, no framework concept added |
| **2** Traceability | every outcome names its **evidence source** | ✅ added — **metadata about the record, not a ninth dimension** |
| **3** Reporting semantics | several outcomes may occur; **exactly one is reported**; strongest wins; `Failed to classify` never masked | ✅ stated as a **reporting convention** |
| **4** Continuity | record lives in the work package · discoverable via the `MEMORY.md` pointer · **no new governance document** | ✅ single canonical home preserved |

**Why evidence source is worth recording (and why it is not a dimension):** it is **metadata identifying where the supporting evidence originated** — interpretable only **in aggregate**. *(Deliberately not "how the framework earned its result": the framework classified, exposed, or failed to classify. It has no agency and earns nothing.)*

**Observed evidence — WP-6 only:** all seven findings — N-5 · O-1 · O-4 · O-5 · S-1..S-3 — were identified by review passes; none by a failing gate.

**Operational question (NOT a conclusion):** *does this pattern persist across independent work packages?* **Only after multiple independent work packages may it justify any conclusion about gate coverage.** One slice surfaces a pattern to watch, never a systemic defect — and drawing the stronger claim here would breach the very discipline this protocol exists to enforce. *(A second data point may already exist in AD-009/AD-010, found by the same route; two is still not several.)*

> ### **The operational protocol is verified. The framework has three distinct layers: (1) frozen architectural concepts, (2) frozen evidence collection process, (3) evolving operational evidence. Future confidence will come from applying the protocol unchanged across independent work packages.**

---

# ✅ FINAL OPERATIONAL READINESS (2026-07-31) — meta-engineering closes here

## 1. Statements that overreached the evidence — one, and it was mine

| Statement | Classification | Correction |
|---|---|---|
| *"all seven findings came from review passes, none from a failing gate — that indicts the gates"* | ❌ **Conclusion from one slice** | Split into **observed evidence** (the seven findings and their source) and an **operational question** (*does this pattern persist across independent work packages? Only then may it justify a conclusion about gate coverage*) |

**How the error happened, recorded because the pattern matters more than the instance:** I wrote *"one slice cannot support a conclusion"* and then drew one, in the same paragraph. The correction restores the three-level discipline the protocol exists to enforce — **observed evidence · hypothesis · conclusion** — and only the third requires cross-slice support.

## 2. Wording corrections

| Before | After | Rationale |
|---|---|---|
| *"that indicts the gates"* | **Observed in WP-6** … **Operational question** … **requires cross-slice validation** | One slice surfaces a pattern to watch, never a systemic defect. *"Operational question"* is preferred over *"hypothesis"*: the framework guides engineering decisions, it does not test scientific propositions |
| Evidence Source: *RED test · GREEN implementation · architectural review · …* | **Architectural Review · Code Review · RED Test · GREEN Verification · Static Analysis · Integration Verification · Runtime Observation** | *Not every review exercises the framework.* Separating **Architectural Review** from **Code Review** gives future aggregate analysis the resolution to tell "the framework found it" from "someone reading the diff found it" |

## 3. DDD justification

| Principle | Application |
|---|---|
| **Evidence before conclusion** | The protocol's own credibility depends on it. A protocol that overreaches on its first record teaches future readers that its records can be discounted |
| **Ubiquitous language** | Evidence-Source *values* are the vocabulary of the **operational record**, not of the domain or the framework — which is why refining them changes no dimension |
| **Bounded context** | The framework is one context; operational records are another. Metadata may be enriched inside the record's context without touching the framework's |

## 4. Final operational readiness

| Check | Status |
|---|---|
| Framework development complete | ✅ — 8 dimensions, frozen, verified, validated |
| Protocol development complete | ✅ — 5 steps, 4 outcomes, template, reporting convention, evidence source |
| Evidence discipline verified | ✅ — observed / hypothesis / conclusion now distinguished, with the one overreach corrected |
| Evidence Source remains **operational metadata** | ✅ — outside the framework, outside the taxonomy; no dimension added |
| Future work packages need **no** framework or protocol refinement | ✅ — WP-7 requires only an operational record |
| Operational records are the **sole** source of framework **refinement** | ✅ |

**Nothing further is required before WP-7.** No framework work, no protocol work — only the closure record WP-6 will produce.

> ### **The framework and its operational protocol are ready for long-term use. Framework refinement is complete. Protocol refinement is complete. Future evidence will come from operational application across independent work packages. The next engineering milestone is: ARB re-scope ruling → GREEN implementation → Complete WP-6 → Record the first operational outcome → Apply the unchanged framework to WP-7.**

**🔒 META-ENGINEERING REFINEMENT CLOSES HERE.** The remaining uncertainty in both artifacts can only be reduced by **use** — and evidence-based reopening is itself meta-engineering, so what closes is *refinement*, not the activity. Attention returns to software delivery.

---

# 🔒 EVIDENTIAL NORMALIZATION — REFINEMENT CLOSED (2026-07-31)

## 1. Remaining evidential overstatements — one, found by scanning rather than assuming

| Statement | Level | Correction |
|---|---|---|
| *"One slice **establishes** a pattern to watch"* | conclusion-verb in **my own prose** | *"One slice **surfaces** a pattern to watch"* |

**Method note:** the conclusion-verbs (*proves · demonstrates · indicts · establishes*) were **grepped**, not recalled. Three hits — two were quoted before-text inside correction records, legitimate since a correction must quote what it corrects, and one was live prose, now fixed.

## 2. Wording corrections

| Before | After | Rationale |
|---|---|---|
| **Hypothesis** | **Operational question** | The framework guides **engineering decisions**; it does not test scientific propositions. *"Does this pattern persist across independent work packages?"* is answerable by engineering practice — *"hypothesis"* borrowed an epistemology the framework does not use |
| *Evidence Source answers how the framework earned its result* | *Evidence Source is **metadata identifying where the supporting evidence originated*** | The framework has **no agency**: it classified, exposed, or failed to classify. Attributing achievement to an instrument invites treating its output as authority — the opposite of the evidence-to-recommendation-to-authority separation |

**The three epistemic levels now stand apart on the WP-6 record:** Observed evidence (WP-6 only) then Operational question then cross-slice validation required. No statement combines two.

## 3. DDD justification

**Ubiquitous language applies to the process vocabulary too.** *"Hypothesis"* and *"earned"* imported a scientific and an agentive frame into a vocabulary otherwise strictly about **responsibility and evidence** — the same category discipline that put process exceptions in the Application layer (N-5) and kept governance outside the framework (T-1). A framework whose own language drifts cannot be trusted to police drift elsewhere.

## 4. Confirmation

| Check | Status |
|---|---|
| Observations separated from conclusions | ✅ |
| Single-slice evidence not generalized | ✅ |
| Operational questions remain explicitly **open** | ✅ |
| No framework or protocol change proposed | ✅ |

> ### **The operational protocol satisfies the project's evidence discipline. FRAMEWORK/PROTOCOL REFINEMENT IS CLOSED. Future **refinement** requires cross-slice operational evidence.**

**Every remaining improvement would be about expression, not architecture.** Architecture stable · governance stable · protocol stable. The only remaining source of meaningful evidence is **operational use across independent work packages**.

**Delivery resumes: ARB re-scope ruling, then GREEN, then WP-6 closure with its first operational record, then WP-7 under the unchanged framework.**

---

# ✅ GOVERNANCE CLOSURE REVIEW (2026-07-31)

## 1. Wording that overstated finality — three, found by scanning every finality word

| Statement | Classification | Correction |
|---|---|---|
| *"The framework/protocol WORKSTREAM IS CLOSED"* | ⚠️ overstates — implies no further evolution | **"Framework/protocol REFINEMENT is closed. Future refinement requires cross-slice operational evidence."** |
| *"EVIDENTIAL NORMALIZATION — WORKSTREAM CLOSED"* (heading) | ⚠️ same | **"— REFINEMENT CLOSED"** |
| *"META-ENGINEERING CLOSES HERE"* | ⚠️ **found by the scan, not flagged** — evidence-based reopening *is* meta-engineering, so the activity cannot close | **"META-ENGINEERING REFINEMENT CLOSES HERE"** |

**Statements checked and left unchanged, because each is correctly scoped:**

| Statement | Why it is accurate |
|---|---|
| *"The four outcomes — **closed set**, no additions"* | Closes a **vocabulary**, deliberately: adding a category would destroy cross-slice comparability |
| *"WP-6's own record — opened, **not yet closed**"* | Refers to the **record**, which closes at slice acceptance |
| *"Framework **development** complete · Protocol **development** complete"* | **Development** is complete; *evolution* is not. Different words, correctly used |
| *"Framework **refinement** is complete"* | Already scoped to refinement |
| *"**frozen** architectural concepts"* | *Frozen* is established project vocabulary (Architecture Freeze · protocol FROZEN) and always means **refinement-frozen** here — established terms are not churned |

## 2. DDD justification

| Principle | Application |
|---|---|
| **Ubiquitous language** | *"Closed"* and *"refinement closed"* are different statements about different subjects. Using the broader word for the narrower fact is precisely the imprecision this program has corrected repeatedly — in code (N-5), in governance (T-1), and now in its own closure |
| **Evidence before conclusion** | A closure that forbade evolution would contradict the reopening condition **stated three sections earlier in the same document.** An internal contradiction in the closing paragraph would undermine everything above it |
| **Bounded context** | Refinement and application are distinct activities. One closes; the other begins. Conflating them would make a later legitimate reopening look like a governance breach |

## 3. Final confirmation

| Check | Status |
|---|---|
| Closure wording does not contradict the reopening principle | ✅ |
| Closure reflects evidence-driven governance | ✅ — **refinement** requires **cross-slice operational evidence**, never preference or elegance |
| No new vocabulary appears in the closure | ✅ — only established terms: *refinement · operational evidence · cross-slice · work package* |
| Workstream has transitioned to operational application | ✅ |

> ### **Framework/protocol refinement is closed. Future refinement requires cross-slice operational evidence. From here, every independent work package becomes a validation exercise. If the framework consistently classifies findings, exposes meaningful issues, and requires no changes across multiple slices, that is the strongest possible evidence of its maturity — evidence derived from software delivery, not further refinement of the framework itself.**

---

# ✅ GOVERNANCE CONSISTENCY REVIEW (2026-07-31) — the reopening rule was attached to the wrong scope

## 1. Governance wording ambiguity — one, and it is mine

| Statement | Ambiguity | Correction |
|---|---|---|
| *"Future **evolution** requires cross-slice operational evidence"* | **Evolution is broader than refinement.** As written, the rule would gate **architectural** evolution — a new bounded context, a new business capability, a new strategic domain — on evidence about a *review framework*. That is a nonsense dependency | **"Future REFINEMENT requires cross-slice operational evidence."** Applied in **six** places (status line · protocol verification · correction table · confirmation row · completion statement · the "sole source" row) |

**I introduced the term and then attached the narrower rule to it** — the exact inversion of the previous correction, where a narrower word had carried a broader claim. Same failure mode, opposite direction.

## 2. The project already recorded this discipline — my wording violated an existing rule

`.claude/MEMORY.md` (R-37, 2026-07-10) states it in one line:

> ***"baselines freeze; architectures evolve — never say 'the architecture is frozen'."***

So the distinction between what **freezes** (an artifact, a baseline, a framework's refinement) and what **evolves** (the architecture) is **established project vocabulary**, not a new idea introduced by this review. **The correction restores an existing rule rather than adding one** — which is also why no new vocabulary enters the closure.

## 3. Lifecycle vocabulary — one meaning per term, verified against usage

| Term | Single meaning | Verified in this document |
|---|---|---|
| **Development** | building the framework | *"Framework development complete"* — accurate |
| **Refinement** | changing the framework | now the **only** subject of the reopening rule |
| **Operational adoption** | using the framework | *"used unchanged from the next slice"* |
| **Evolution** | architectural change | left broad **on purpose**; explicitly **not** gated by framework evidence |
| **Frozen** | refinement-frozen, never use-frozen | stated at first use, per R-37 |
| **Complete** | a phase is finished | applied to *development* and *refinement* only |
| **Closed** | refinement closed; evolution open | now consistent everywhere |

## 4. DDD justification

| Principle | Application |
|---|---|
| **Ubiquitous language** | *Evolution* and *refinement* denote different subjects with different scopes. Using the broader term for the narrower rule is the same imprecision corrected in code (N-5), in governance (T-1), and in the closure (three finality overstatements) |
| **Bounded context** | The **framework** is one context; the **product architecture** is another. Refinement rules govern the framework; evolution belongs to the architecture — and a rule may not reach across that boundary |
| **Evidence before conclusion** | Cross-slice evidence justifies **framework refinement**. Architectural evolution is justified by **business requirements** — a different authority, a different evidence base |

## 5. Final confirmation

| Check | Status |
|---|---|
| Lifecycle vocabulary has one meaning per term | ✅ |
| Governance statements remain within intended scope | ✅ — the reopening rule now reaches only the framework |
| Evidence discipline applies **only to refinement** | ✅ |
| Control loop preserved (observation → verification → classification → correction → authority) | ✅ — this review ran it: grep, classify, correct, report |

> ### **The framework's governance vocabulary is internally consistent with the framework's architectural principles. Future refinement requires cross-slice operational evidence. Operational application continues. Architectural evolution remains independent unless evidence requires framework refinement.**

---

## WP-6 GREEN COMPLETE (2026-07-31) — STOP for slice acceptance

**`OK (10 tests, 22 assertions)`** on the re-scoped slice. Delivered exactly the three authorized items.

| Item | Delivered |
|---|---|
| **MAD-aware horizon** | `config/adjudication.php` (INTERIM) · `AdjudicationDurations` port · `ConfiguredAdjudicationDurations` (organisation → election type → default) · **F-1 fixed**: cut-off is `now − MAD` |
| **Late-decision conflict** | `LateDecisionOnExpiredAdjudication` (`PermanentInboxFailure`, **Application/Process** layer per N-5) · store gained `latestForChallenge()` — `activeForChallenge()` cannot distinguish *expired* from *concluded* · **F-2 fixed**, redelivery still a no-op |
| **Expiry announcement** | `AdjudicationExpired` domain event · outbox mapping (v1) · `AdjudicationExpiredHydrator` · registration · **allowlist entry** (third conversation) · registry-completeness list extended |

### Gates

| Gate | Result |
|---|---|
| PHPStan max | ✅ no errors — **4 fixed at root** (narrow-before-cast ×3; concrete model type ×1), none suppressed |
| Deptrac | ✅ **0 violations** |
| Architecture suite | ✅ **146 green** |
| Adjudication + Contestation + Election + Shared features | ✅ **91 tests, 260 assertions** |
| Developer guide | ✅ `developer_guide/adjudication/05_adjudication_horizon_and_expiry.md` + index |

### ⚠️ ONE ITEM REQUIRING ARB REVIEW — an architecture guard's rule was widened

**`AT-EVT-001` (event ownership) failed**, because it requires Adjudication's events to be prefixed **`Determination`**. Three options were weighed:

| Option | Verdict |
|---|---|
| Rename to `DeterminationExpired` | ✖ **False.** No determination exists and nothing about one expired — this would corrupt the ubiquitous language to satisfy a naming guard |
| Move the event out of `Domain/Events` | ✖ The outbox port types `DomainEvent`; the relocation is a larger modelling question |
| **Widen the ownership map** to admit the context's own name | ✔ **Chosen** — `AdjudicationExpired` is a fact about the **process**, which **EPIC-004K §11 states is NOT an aggregate**, so it has no owning aggregate to be named after |

**This widened an architecture guard's encoded rule rather than merely satisfying it, so it is flagged rather than absorbed.** The justification is recorded in the test itself. **ARB may reverse it.**

### Two Phase-15 diagnoses, both "incorrect test"

1. **`DateInterval::$days` is `false`** for a constructed interval — the day component is `->d`. My accessor guess.
2. **Challenge refs reach `outbox_events.aggregate_id`, a UUID column** — non-UUID fixtures fail at *insert*. The same class as the WP-4 `'conversation-1'` error; the lesson had not transferred.

### Framework Operational Record — WP-6

| Field | Value |
|---|---|
| Work Package | **WP-6** |
| Framework Outcome | **Exposed a previously unknown defect** (strongest applicable) |
| Evidence Source | **Architectural Review** |
| Evidence | **N-5** — a committed RED test imported a `Domain` exception for an **Application-layer process** rule; the DDD-layer dimension exposed it. GREEN then confirmed the placement is what the house requires |
| Classification Failures | **none** — every finding placed in exactly one taxonomy, layer, owner and responsibility type |
| Reopening Criteria Met? | **No** |
| Recommendation | **Continue unchanged** |

**Progress:** ✔ Decisions A/B/C · ✔ RED · ✔ **GREEN** · ✔ gates · ✔ dev guide · ✔ operational record · ⏳ **slice acceptance (STOP)**.

---

# 🔎 ARCHITECTURE PRESERVATION REVIEW — WP-6 GREEN (2026-07-31)

**Commission:** did the implementation preserve the approved architecture? Verification by **inspection**, not assertion. No redesign · no code optimization.

## 1. Preservation findings — two policy leaks found and fixed, one invariant change referred

| Finding | Status |
|---|---|
| Ownership preserved | ✅ — the APM contains **no** duration literal (`grep` for `60`/`DateInterval`/`P…D` in the manager: **zero hits**) |
| Policy in its intended layer | ⚠️→✅ — **two leaks found and corrected**, see AP-1/AP-2 |
| Published language preserved | ✅ — `AdjudicationExpired` carries exactly `challengeRef` + `expiredAt`; **no outcome, legitimacy, reason or authority field exists to misuse** |
| Context boundaries intact | ✅ — Deptrac **0 violations**; Adjudication imports nothing from Contestation |
| Architecture tests represent the approved architecture | ⚠️ — **AT-EVT-001 was widened: referred to the ARB** (§6) |

### AP-1 — a business floor had leaked into Infrastructure

```php
return new DateInterval('P'.max(1, $days).'D');   // ← the adapter chose a duration
```

`max(1, …)` is a **duration decision**: it silently substitutes one day for any non-positive configuration. **Q-2 owns durations** (§81), so an infrastructure adapter may not pick one — not even a floor, not even a safe-looking one. **Corrected:** the adapter now **fails closed** with an explicit error, matching the house config discipline already established by EG-002a (*a config value that cannot be trusted blocks; it does not get a substitute*).

### AP-2 — the ARB's number had two homes

`60` appeared in `config/adjudication.php` **and** as the adapter's fallback. The interim MAD is the ARB's value with exactly one declared home; a second copy means a future ARB change could be silently shadowed. **Corrected:** the adapter reads the key and **throws if it is missing or non-numeric** rather than carrying a rival default.

**The implementation introduced two policy defects, and no technical gate reported either** — PHPStan, Deptrac and 146 architecture tests all passed with them in place. They surfaced only from asking *"does policy still live where the architecture put it?"* *(Reviews describe the artifact, not the author -- the finding scales to a team; the attribution does not.)*

## 2. Ownership verification

| Component | Business owner | Orchestration owner | Technical owner | Matches architecture? |
|---|---|---|---|---|
| `AdjudicationDurations` (port) | **Q-2 / ARB** | APM (enforces) | `ConfiguredAdjudicationDurations` | ✅ — and the port exists *because* those differ |
| `LateDecisionOnExpiredAdjudication` | Adjudication BC (§197 ruled the meaning) | APM | Application/Process code | ✅ (layer per N-5) |
| `AdjudicationExpired` | **Adjudication BC** (producer owns published language, R-7) | APM (**emits**, does not own) | outbox adapter + hydrator | ✅ — the business/execution split from O-3 |
| `latestForChallenge()` | Adjudication BC | APM | Eloquent store | ✅ — added because §197 requires distinguishing *expired* from *concluded* |

**No ownership migration.** The one place two accountabilities meet — the announcement — carries **two named owners**, not one absorbing the other.

## 3. Policy verification

| Policy | Intended layer | Verified |
|---|---|---|
| MAD's **value** | Q-2 / config | ✅ after AP-1/AP-2; the adapter now **decides nothing** |
| MAD's **enforcement** | Application (APM) | ✅ — `$now->sub($this->durations->maximumAdjudicationDuration())` |
| **Policy 4** — a timer concludes nothing | Constitutional | ✅ — asserted as an **absence**: no outcome, legitimacy, reason, authority or determination |
| §197 — late ≠ redelivered | Application/Process | ✅ — two branches, two meanings, both pinned by keystones |
| Expiry is terminal | Domain/process state | ✅ — `expire()` unchanged from WP-2 |

## 4. Context-boundary verification

| Interaction | Mechanism | Verified |
|---|---|---|
| Adjudication → Contestation | `AdjudicationExpired` **published language** (payload primitives, producer-side registration) | ✅ |
| Adjudication reading Contestation state | **none** — and this is why the finality evaluator stayed OUT of the slice | ✅ |
| Hidden dependency · shared state · knowledge leakage | none — Deptrac 0, and no cross-context import exists | ✅ |

## 5. Published-language verification

| Property | Value |
|---|---|
| Producer | Adjudication BC (emitted by the APM) |
| Consumer | **none yet** — Contestation, in WP-6B (ARB Decision C) |
| Business meaning | *the adjudication horizon elapsed without a conclusion* — a **fact**, never a verdict |
| Payload | `schema_version: 1` · `challengeRef` · `expiredAt` — primitives only |
| Registration | `AdjudicationExpiredHydrator`, v1-only window, rejects other versions loudly |

**It communicates a fact, not implementation state:** nothing in the payload exposes the process's internal shape — no status string, no process id, no admitted-evidence list.

## 6. Architecture-test impact — the one item needing authority

| Change | Classification | Authority |
|---|---|---|
| **AT-EVT-001**: `Adjudication ⇒ Determination*` widened to `Determination* OR Adjudication*` | **ARCHITECTURAL EVOLUTION** — not implementation alignment, not clarification | ⚠️ **ARB / ADR authority required** |
| Mint allowlist: third entry | Implementation of **ARB Decision B** | ✅ already authorized |
| `EventRegistryCompletenessTest`: new produced type declared | Implementation alignment | ✅ |

**The rule that changed, stated plainly:** *"an event is named after the aggregate that owns it"* became *"an event is named after the aggregate that owns it, **or after its context when no aggregate owns it**."*

**Why the rule needed to evolve — the architectural reason, not the test-failure reason:** the ownership rule assumed **every event originates from an aggregate**. The process manager introduces **process-owned events**, and **EPIC-004K §11 states the process is orchestration, not an aggregate**. An event with no owning aggregate cannot be named after one. *"Otherwise the test fails"* is not a justification and is not offered as one.

## 7. Required ADR / ARB decision

> **One decision requested:** accept (or reverse) the AT-EVT-001 evolution — *process-owned events may carry the context prefix where no aggregate owns them.* If accepted, it belongs in an ADR or a recorded ARB ruling, **not** in a test comment; the comment is a stopgap that flags the gap, not a home for the rule.

## 8. Recommendation

| Item | Recommendation |
|---|---|
| **WP-6 GREEN implementation** | **Accept** — ownership, policy layers, published language and context boundaries all preserved, with AP-1/AP-2 corrected before acceptance |
| **AT-EVT-001 widening** | **Refer to the ARB** for explicit authority; the implementation does not depend on the outcome, only the guard's canonical status does |

> ### **WP-6 GREEN preserves architectural ownership, policy layers, published language, and context boundaries. One architectural invariant changed: AT-EVT-001 was widened from aggregate-prefix only to context-or-aggregate prefix. This requires explicit ARB/ADR authority before the architecture test is considered canonical. The implementation itself is accepted; the architecture test change awaits authority.**

**Post-correction verification:** `OK (10 tests, 22 assertions)` · PHPStan max **no errors** · behaviour unchanged by AP-1/AP-2 (they removed decisions, not capability).

---

# 🔬 ARCHITECTURE DECISION PRESERVATION REVIEW (ADPR) — WP-6 GREEN (2026-07-31)

**The distinction this review adds (ARB refinement).** *Architecture* preservation and *decision* preservation are not the same question:

| | Architecture Preservation | **Decision Preservation** |
|---|---|---|
| Asks | did boundaries · ownership · layers · contracts hold? | did the **approved decisions** hold? |
| WP-6 result | ✅ **intact** — Deptrac 0, no cross-context import, layers respected | ⚠️ **two failures** (AP-1, AP-2) |

**AP-1 and AP-2 were Decision-Preservation failures, not boundary failures.** The boundary never moved: `ConfiguredAdjudicationDurations` stayed in Infrastructure, imported nothing it shouldn't, and violated no layer rule. **What leaked was a decision** — *who may choose a duration* — and that is invisible to any tool measuring structure.

## 1. Architectural decision inventory (approved decisions only — nothing inferred)

| Decision | Authority | Business rationale | Expected location |
|---|---|---|---|
| MAD's **value** | **Q-2** (§187 parameter set) | durations are business policy; interim values are bootstraps | `config/adjudication.php` — **one home** |
| MAD's **enforcement** | EPIC-004K **§81** | the APM enforces a duration it does not own | Application — the APM |
| Expiry is a **fact, not a verdict** | **Constitutional Policy 4** · §57 | automated verification never determines significance | Domain event; no verdict fields |
| **Late ≠ redelivered** | EPIC-004K **§197** | a post-expiry decision is a conflict; redelivery is a no-op | Application/Process |
| Expiry is **published language** beginning a new conversation | **ARB Decisions A/B** | Contestation cannot discharge §197's disposition unknowing | outbox + hydrator + allowlist |
| Slice ends at publication | **ARB Decision C** | aggregate completion; Registration ≠ Delivery | no consumer in this slice |

## 2. Decision-preservation findings

| Decision | Classification | Evidence |
|---|---|---|
| MAD's value | ⚠️ **DECISION DUPLICATION** → corrected (**AP-2**) | `60` existed in the config **and** as an adapter fallback; a value the ARB owns had two homes, so a future ARB change could be silently shadowed |
| *Who may choose a duration* | ⚠️ **DECISION LEAK** → corrected (**AP-1**) | `max(1, $days)` had the adapter **substitute** a duration for any non-positive value — a business decision taken where no owner authorized one |
| MAD's enforcement | ✅ **Preserved** | the APM contains no duration literal; it subtracts what the port returns |
| Expiry is a fact | ✅ **Preserved** | the event has **no field** for outcome, legitimacy, reason or authority — preserved *structurally*, not by discipline |
| Late ≠ redelivered | ✅ **Preserved** | two branches, two meanings, each pinned by its own keystone |
| Published language | ✅ **Preserved** | event + mapping + hydrator + registration + allowlist entry |
| Slice ends at publication | ✅ **Preserved** | no consumer exists; no Contestation file touched |

**The asymmetry worth keeping:** both failures concerned **a policy's value or its chooser**; every decision expressed **structurally** (a missing field, an injected port, a separate branch) was preserved. **Decisions encoded in structure survive implementation; decisions encoded only in prose require CONTINUOUS INTERPRETATION** — which is why reviews become necessary, and why a structural encoding retires the need for one.

## 3. Ownership preservation — three owners, independent

| Component | Business owner | Execution owner | Technical owner | Migration? |
|---|---|---|---|---|
| `AdjudicationDurations` | **Q-2 / ARB** | APM (enforces) | `ConfiguredAdjudicationDurations` | **None** — and AP-1 was precisely a *technical* owner briefly acting as a *business* owner |
| `LateDecisionOnExpiredAdjudication` | Adjudication BC (§197) | APM | Application/Process code | None |
| `AdjudicationExpired` | Adjudication BC (R-7) | APM (**emits**) | outbox adapter + hydrator | None |
| `latestForChallenge()` | Adjudication BC | APM | Eloquent store | None |

## 4. Policy preservation

| Policy | Approved owner | Verified |
|---|---|---|
| MAD's value | Q-2 / config | ✅ single home |
| A duration **floor** | **nobody** — never an approved decision | ✅ removed; the adapter fails closed |
| A **substitute** default | **nobody** | ✅ removed |
| Enforcement | APM | ✅ |
| Policy 4 | constitutional | ✅ asserted as an absence |

**All four detection targets the commission names were present in one 40-line adapter:** duplicated policy (AP-2) · hidden defaults (AP-2's fallback) · substituted business values (AP-1) · silent business decisions (AP-1). Worth recording as a warning about where policy hides: **small adapters attract defaults.**

## 5. Boundary preservation

✅ Adjudication → Contestation only via published language · ✅ no read of Contestation state (which is *why* the finality evaluator stayed out of scope) · ✅ no hidden dependency, shared state or knowledge leakage (Deptrac 0).

## 6. Executable-architecture impact

| Change | Classification | Authority |
|---|---|---|
| **AT-EVT-001** `Determination*` → `Determination* OR Adjudication*` | **ARCHITECTURAL EVOLUTION** | ⚠️ **ARB/ADR required** |
| Mint allowlist third entry | implementation of ARB Decision B | ✅ authorized |
| Registry-completeness list extended | implementation alignment | ✅ |

## 7. Technical vs architectural verification — stated precisely

| Gate | Result | What it measures | Could it have caught AP-1/AP-2? |
|---|---|---|---|
| PHPStan max | PASS | types | **No** — `max(1, $x)` is perfectly typed |
| Deptrac | PASS | dependency direction | **No** — no dependency was violated |
| Architecture suite (146) | PASS | structural properties | **No** — no structural property was violated |
| Feature suites (91) | PASS | behaviour | **No** — behaviour was *correct*; the floor never fired |

> **The gates were not blind; they were measuring something else.** Each passed correctly, because none measures *who is entitled to make a decision*. That is the gap an ADPR fills — and the reason it cannot be closed by adding another gate of the same kind.

## 8. Decision-preservation summary

| Decision | Preserved | Evidence | Authority |
|---|---|---|---|
| MAD value — one home | ✅ *after correction* | config only | Q-2 §187 |
| Duration chooser — Q-2 only | ✅ *after correction* | adapter fails closed | §81 |
| MAD enforcement — APM | ✅ | no literal in the manager | §81 |
| Expiry ≠ verdict | ✅ | no verdict field exists | Policy 4 |
| Late ≠ redelivered | ✅ | two branches | §197 |
| Published language | ✅ | publication + registration | Decisions A/B |
| Slice ends at publication | ✅ | no consumer | Decision C |

## 9. Required ARB/ADR decision · recommendation

| Item | Recommendation |
|---|---|
| **WP-6 GREEN** | **Accept** — every approved decision preserved; both failures corrected before acceptance |
| **AT-EVT-001 widening** | **Refer to the ARB.** If accepted, the rule belongs in an ADR or recorded ruling — a test comment flags a gap, it does not host a rule |

> ### **WP-6 GREEN preserves approved architectural decisions. Two decision-preservation failures — one DUPLICATION (AP-2) and one LEAK (AP-1) — were detected by ADPR, not by technical gates, and were corrected. One architectural evolution (AT-EVT-001) is referred for ARB authority. The ADPR is complete.**

---

# 🏛️ ARCHITECTURE DECISION **AUTHORITY** PRESERVATION REVIEW (ADAPR) — WP-6 (2026-07-31)

**The refinement that renames this review, and it is decisive.** Both failures were about **who was entitled to decide**, not about what was decided:

> `MAD = 60` was **not wrong**. **Who selected it** was wrong.

The adapter's fallback would have produced the *same duration* the ARB ratified. Nothing observable would have differed — and that is exactly why this is an **authority** failure rather than a correctness failure. A review that only asked *"is the value right?"* would have found nothing.

## 1. Structural preservation (dimension 1, verified independently)

| Dimension | Status | Evidence |
|---|---|---|
| Bounded contexts | ✅ Preserved | zero Contestation imports in Adjudication |
| Layers | ✅ Preserved | Domain → no framework; Application → ports only; Infrastructure → adapters |
| Dependencies | ✅ Preserved | Deptrac **0 violations**, fail mode |
| Ports | ✅ Preserved | `AdjudicationDurations` · `EventOutbox` · `IdentityGenerator` · `AdjudicationProcessStore` all injected, none bypassed |
| Contracts | ✅ Preserved | payload primitives; producer-side registration |
| Published language | ✅ Preserved | event + mapping + hydrator + registration |

**No boundary drift · no dependency drift · no layer drift.** The adapter that failed authority preservation never left Infrastructure.

## 2. Decision authority inventory (approved decisions only)

| Decision | Business owner | **Decision authority** | Implementation owner | Expected location |
|---|---|---|---|---|
| MAD's **value** | Adjudication BC (subject) | **Q-2 / ARB** (§187) | config file | `config/adjudication.php`, one home |
| What happens when MAD is **invalid** | — | **Q-2 / ARB** (no decision issued) | — | **nowhere — the case was never decided** |
| MAD's **enforcement** | Adjudication BC | EPIC-004K **§81** | APM | Application |
| Expiry ≠ verdict | constitutional | **Constitutional Policy 4** | domain event shape | Domain |
| Late ≠ redelivered | Adjudication BC | EPIC-004K **§197** | APM | Application/Process |
| Expiry is published language | Adjudication BC | **ARB Decision A** | outbox + hydrator | Infrastructure |
| Announcement begins a conversation | platform | **ARB Decision B** | APM + allowlist | Application |
| Slice ends at publication | — | **ARB Decision C** | scope | — |

## 3. Decision authority preservation

| Decision | Classification | **Who became entitled to decide** | Evidence |
|---|---|---|---|
| MAD's value | ⚠️ **DECISION DUPLICATION** → corrected | **the adapter**, concurrently with the ARB — two components each asserting the interim value | `60` in config **and** as the adapter's fallback |
| MAD invalid-case | ⚠️ **UNAUTHORIZED DECISION** → corrected | **the adapter**, deciding a case **no authority had ruled on** — `max(1, …)` answered *"what if MAD ≤ 0?"* by inventing one day | the clamp silently produced a duration nobody approved |
| MAD's enforcement | ✅ Preserved | APM (as approved) | no duration literal in the manager |
| Expiry ≠ verdict | ✅ Preserved | Policy 4 (as approved) | the event has **no field** to carry a verdict |
| Late ≠ redelivered | ✅ Preserved | §197 (as approved) | two branches, two meanings |
| Published language | ✅ Preserved | Decision A | publication + registration |
| Conversation origin | ✅ Preserved | Decision B | allowlist entry, ARB-approved |
| Slice boundary | ✅ Preserved | Decision C | no consumer, no Contestation file touched |

**The sharper reclassification:** AP-1 is better named an **Unauthorized Decision** than a Decision Leak. Nothing *moved* — the invalid-MAD case had **never been decided by anyone**, and the adapter filled the vacuum. That is a distinct failure mode: **not authority drifting, but authority being created where none existed.**

## 4. Structural vs authority analysis (independent dimensions)

| Decision | Structure | Authority | Outcome |
|---|---|---|---|
| MAD's value | ✅ Preserved | ⚠️ **Drifted** (duplication) | **Preserved / Drifted** |
| MAD invalid-case | ✅ Preserved | ⚠️ **Drifted** (unauthorized) | **Preserved / Drifted** |
| MAD enforcement | ✅ | ✅ | Fully preserved |
| Expiry ≠ verdict | ✅ | ✅ | Fully preserved |
| Late ≠ redelivered | ✅ | ✅ | Fully preserved |
| Published language | ✅ | ✅ | Fully preserved |
| Conversation origin | ✅ | ✅ | Fully preserved |

**Every failure landed in the same quadrant: Structure Preserved / Authority Drifted.** That quadrant is the one no technical gate can reach, and the reason the two dimensions must not be merged — a system can keep every layer, boundary and dependency while decision-making authority migrates into the wrong component.

## 5. Structural invariant review

| Decision | Structural? | Preservation method | Risk |
|---|---|---|---|
| Expiry ≠ verdict | ✅ **Yes** | **the field does not exist** — nothing to populate | **Low** — cannot be violated accidentally |
| MAD enforcement not ownership | ✅ Yes | port injection; the manager has no way to author a duration | **Low** |
| Late ≠ redelivered | ✅ Yes | two branches + a typed exception | **Low** |
| Published language | ✅ Yes | hydrator registration is asserted by a test | **Low** |
| Conversation origin | ✅ Yes | allowlist guard fails on an unlisted mint | **Low** |
| **MAD's value has one home** | ❌ **No — prose only** | *"the numbers are the ARB's"* | **HIGH — and this is where both failures occurred** |
| **Invalid MAD is undecided** | ❌ **No — not written anywhere** | — | **HIGHEST — an undecided case invites invention** |

**Both authority failures occurred in the only two rows without structural expression.** Refined wording, per ARB:

> **Decisions encoded only in prose require CONTINUOUS INTERPRETATION** — which is precisely why reviews become necessary, and why a structural encoding retires the need for one.

## 6. Gate coverage review — each gate judged only within its own responsibility

| Gate | Measures | **Can never detect** | Status |
|---|---|---|---|
| PHP compiler | syntax | anything semantic | correct |
| PHPStan max | types | authority drift — `max(1, $x)` is perfectly typed | **correct, not deficient** |
| Deptrac (fail mode) | dependency direction | decision duplication — no dependency was violated | **correct** |
| Architecture suite (146) | structural properties | decision leaks with no structural signature | **correct** |
| Feature suites (91) | behaviour | who was entitled to decide — behaviour was *right*, the clamp never fired | **correct** |
| **ADAPR (this review)** | **authority** | **a decision nobody recorded** | see limit below |

**No gate is criticized here.** Each measured its own subject accurately. The failures were outside every gate's *intended* scope, which makes them a coverage gap in the **review system**, not defects in the gates.

**This review's own limit, stated honestly:** the ADAPR depends on the **decision inventory being complete**, and only *approved* decisions participate. **An authority failure over a decision nobody ever recorded is invisible to it too** — which is how the invalid-MAD case slipped in, and why it appears above as *"nowhere — the case was never decided."* The ADAPR caught it only because implementation forced the question.

## 7. Required ARB/ADR decisions

| Item | Decision required |
|---|---|
| **AT-EVT-001 widening** | Accept or reverse the architectural evolution: *process-owned events may carry the context prefix where no aggregate owns them.* If accepted, it belongs in an ADR or recorded ruling |
| **Invalid-MAD case (optional)** | The implementation now **fails closed**, which decides nothing on Q-2's behalf. If the ARB wishes to *rule* on the case, that ruling would supersede the fail-closed behaviour — recorded, not requested |

## 8. Recommendation

| Item | Recommendation |
|---|---|
| **Structural preservation** | ✅ **Accept** — no drift in any dimension |
| **Authority preservation** | ✅ **Accept after correction** — one duplication and one unauthorized decision, both corrected before acceptance |
| **WP-6 GREEN** | ✅ **Accept** |
| **AT-EVT-001** | ⚠️ **Refer to the ARB** |

> ### **WP-6 preserves architectural structure and decision authority. Two authority failures — a Decision Duplication and an Unauthorized Decision — were detected by ADAPR, not by technical gates, and corrected. One architectural evolution (AT-EVT-001) awaits ARB authority. The ADAPR is complete.**

**The principle this slice earned, recorded as an observation and not promoted:**

> **Architecture is preserved by structure; governance is preserved by authority.** They are related but independent, and only the first is mechanically verifiable today.

---

# 🏛️ ARCHITECTURE GOVERNANCE INTEGRITY REVIEW (AGIR) — WP-6 (2026-07-31)

**Structural and governance findings are reported in separate sections and never mixed.**

## 1. Structural integrity — reported alone

| Area | Status | Evidence |
|---|---|---|
| Bounded contexts | **Preserved** | zero Contestation imports in Adjudication |
| Dependencies | **Preserved** | Deptrac **0 violations**, fail mode |
| Layers | **Preserved** | Domain framework-free · Application ports-only · Infrastructure adapters |
| Ports | **Preserved** | four injected, none bypassed |
| Contracts | **Preserved** | payload primitives; producer-side registration |
| Published language | **Preserved** | event + mapping + hydrator + registration |

**No structural drift · no dependency drift · no boundary drift.** Nothing further belongs in this section.

## 2. Governance integrity inventory (approved decisions only)

| Decision | Business owner | Decision authority | Implementation owner | Execution owner | Expected location |
|---|---|---|---|---|---|
| MAD's value | Adjudication BC | **Q-2 / ARB** (§187) | config file | APM (applies) | `config/adjudication.php` |
| MAD's enforcement | Adjudication BC | **§81** | APM | APM | Application |
| Expiry ≠ verdict | constitutional | **Policy 4** | event shape | APM | Domain |
| Late ≠ redelivered | Adjudication BC | **§197** | APM | APM | Application/Process |
| Expiry is published language | Adjudication BC | **ARB Decision A** | outbox + hydrator | APM | Infrastructure |
| Announcement begins a conversation | platform | **ARB Decision B** | APM + allowlist | APM | Application |
| Slice ends at publication | — | **ARB Decision C** | scope | — | — |

## 3. Governance integrity review

| Decision | Classification | Who gained authority | Evidence |
|---|---|---|---|
| MAD's value | **DUPLICATED AUTHORITY** → corrected | the adapter, concurrently with the ARB | `60` in config **and** as adapter fallback |
| MAD's enforcement | **Preserved** | — | no duration literal in the manager |
| Expiry ≠ verdict | **Preserved** | — | no field exists to carry a verdict |
| Late ≠ redelivered | **Preserved** | — | two branches, two meanings |
| Published language | **Preserved** | — | publication + registration |
| Conversation origin | **Preserved** | — | ARB-approved allowlist entry |
| Slice boundary | **Preserved** | — | no consumer, no Contestation file touched |

**No authority migration.** One duplication, corrected before acceptance.

## 4. Decision completeness review — the invalid-MAD case is TWO findings, not one

**Applying the ARB's split, the single line `max(1, $days)` produced two independent findings:**

| # | Finding | Classification | Owner of the problem |
|---|---|---|---|
| **DC-1** | *What should happen when MAD resolves to ≤ 0 was never decided by any authority.* Q-2's parameter set (§187) ratifies **values**; it is silent on invalid ones | **UNSPECIFIED DECISION** — a **governance gap** | **The ARB / Q-2** — a question never put to them |
| **DC-2** | The implementation **answered** that unanswered question by silently substituting one day | **UNAUTHORIZED DECISION** — **implementation overreach** | **The implementation** |

**Why separating them matters, and it is not bookkeeping:** they have **different owners and different remedies.** DC-2 was fixed by code — the adapter now fails closed and decides nothing. **DC-1 cannot be fixed by code at all**; it is resolved only if the ARB rules on the case, or deliberately declines to. Merged into one finding, DC-1 would have looked closed the moment DC-2 was corrected — and the governance gap would have survived, invisible, behind a correct implementation.

**Every implementation branch was reviewed against this question.** Other branches trace to explicit decisions: the horizon cut-off → §81 · the late-decision refusal → §197 · the version window rejection → ADR-T5 · the announcement → Decisions A/B. **`max(1, …)` was the only branch answering a question no authority had been asked.**

## 5. Review-system coverage — intended responsibility and blind spots

| Mechanism | Intended responsibility | Architectural blind spot |
|---|---|---|
| PHP compiler | syntax | everything semantic |
| PHPStan max | type correctness | authority — `max(1, $x)` is perfectly typed |
| Deptrac (fail mode) | dependency direction | duplicated authority — no dependency was violated |
| Architecture suite (146) | structural properties | authority with no structural signature |
| Feature suites (91) | behaviour | who was entitled to decide — the clamp never fired, so behaviour was *correct* |
| **AGIR (this review)** | **governance integrity** | **see §6** |

**No mechanism is criticized.** Each measured its own subject accurately, and the failures lay outside every one's designed responsibility.

## 6. This review's own limits — declared, because a mature review must

| Review | Detects | **Cannot detect** |
|---|---|---|
| **AGIR** | duplicated · migrated · unauthorized authority; unspecified decisions **that implementation forces into the open** | ① an unspecified decision that **no branch ever reaches** — it stays invisible until some future input hits it; ② a decision **absent from the inventory**, since only *approved* decisions participate; ③ whether an authority's ruling is *wise* — only whether it was theirs to make |

**DC-1 is a live example of limit ①.** It surfaced only because an implementation branch touched it. Had the adapter simply trusted the config, the governance gap would still exist and this review would not have found it. **The review is only as complete as the code paths that force questions into the open.**

## 7. Required ARB/ADR decisions

| Item | Decision required |
|---|---|
| **AT-EVT-001 widening** | Accept or reverse: *process-owned events may carry the context prefix where no aggregate owns them.* If accepted, it belongs in an ADR or recorded ruling — not a test comment |
| **DC-1 — the invalid-MAD case** | **Rule, or deliberately decline to rule.** The implementation currently **fails closed**, which decides nothing on Q-2's behalf and is safe indefinitely. **Recorded as a governance gap, not a defect** |

## 8. Recommendation

| Item | Recommendation |
|---|---|
| **Structural integrity** | ✅ **Accept** — no drift in any dimension |
| **Governance integrity** | ✅ **Accept after correction** — one duplicated authority, one unauthorized decision, both corrected |
| **Decision completeness** | ⚠️ **One governance gap recorded (DC-1)** — owner is the ARB; not a blocker, and not fixable in code |
| **WP-6 GREEN** | ✅ **Accept** |
| **AT-EVT-001** | ⚠️ **Refer to the ARB** |

> ### **WP-6 preserves structural integrity and governance integrity. One duplicated authority and one unauthorized decision were detected and corrected; one unspecified decision (DC-1) is recorded as a governance gap the ARB owns. One architectural evolution (AT-EVT-001) awaits authority. The AGIR is complete.**

**Observation recorded, not acted on (the framework is refinement-closed):** the three dimensions may compress into two architectural layers — **Architectural Integrity** (structure · boundaries · layers · dependencies) and **Governance Integrity** (authority · ownership · authorization), with decision preservation as an *outcome* of the second rather than a peer of it. Filed with the other un-promoted observations; **no dimension is added, renamed or merged today.**

---

# 📦 WP-6 CLOSURE & ARB ACCEPTANCE PACKAGE (2026-08-01)

**Repository Integrity Gate: PASSED.** Working tree == index == HEAD · no operation in progress · working tree clean. Evidence below is sourced from the **working tree at HEAD `3568bf47e`**, which are identical. *(The gate was added after a conflicted rebase briefly made evidence unsourceable; the incident cost no work — every artifact was recoverable from the pre-rebase tip.)*

## 1. Implementation status — **COMPLETE**

| Authorized item | Delivered |
|---|---|
| MAD-aware horizon | `config/adjudication.php` (INTERIM) · `AdjudicationDurations` port · `ConfiguredAdjudicationDurations` · **F-1 fixed** (cut-off `now − MAD`) |
| Late-decision conflict | `LateDecisionOnExpiredAdjudication` (`PermanentInboxFailure`) · `latestForChallenge()` · **F-2 fixed** |
| Expiry announcement | `AdjudicationExpired` · outbox mapping v1 · hydrator · registration · allowlist entry |

**Nothing beyond the authorized three was implemented.** Evidence-demand deadlines and the finality evaluator remain out (unmodelled concept / undesigned crossing); the Contestation consumer remains WP-6B.

| **Evidence** | **Result** |
|---|---|
| WP-6 keystones | ✅ **10 tests, 22 assertions** |
| PHPStan max | ✅ no errors |
| Deptrac (fail mode) | ✅ **0 violations** |
| Architecture suite | ✅ **146 green** |
| Adjudication + Contestation + Election + Shared | ✅ **91 tests, 260 assertions** |
| Developer guide | ✅ `05_adjudication_horizon_and_expiry.md` + index |
| Operational record | ✅ filed (outcome *Exposed a previously unknown defect*; source *Architectural Review*) |

## 2. Structural integrity — **PASS**

Bounded contexts · layers · dependencies · ports · contracts · published language: **all preserved**. Deptrac 0 violations; zero cross-context imports. No structural, dependency or boundary drift.

## 3. Governance integrity — **PASS after correction**

| Finding | Resolution |
|---|---|
| **Duplicated authority** — `60` in config *and* as an adapter fallback | ✅ Corrected: the adapter reads the single declared home and throws if absent |
| **Unauthorized decision** — `max(1, $days)` chose a duration no authority had ruled on | ✅ Corrected: fails closed, decides nothing |

Decision authority now matches the approved architecture on every inventoried decision. **No authority migration.**

## 4. Remaining open items — one classification each

| Item | Classification | Owner | Required action | Blocks acceptance? |
|---|---|---|---|---|
| **AT-EVT-001 widened** — an event may carry its context's prefix where no aggregate owns it | **Architectural Evolution** | **ARB** | Accept or reverse; if accepted, host the rule in an **ADR or recorded ruling** (a test comment flags a gap, it does not host a rule) | **NO** — the implementation is unaffected either way; only the guard's canonical status depends on it |
| **DC-1** — what happens when MAD resolves ≤ 0 was never decided by any authority | **Governance Gap** | **ARB / Q-2** | Rule, or deliberately decline to | **NO** — the code fails closed, deciding nothing on Q-2's behalf; safe indefinitely |
| Q-2's five numeric parameters | **Governance Gap** | **ARB** | Replace INTERIM values when ready | **NO** — roadmap pre-authorized INTERIM defaults |
| Evidence-demand deadlines (§80/PM-3) | **Deferred Future Work** | ARB (modelling) | Model *Evidence Demand* / *Deadline* first | **NO** — explicitly out of scope |
| Finality evaluator (§142) | **Deferred Future Work** | ARB (integration) | Decide the cross-context crossing's shape | **NO** — explicitly out of scope |
| Contestation consumer of `AdjudicationExpired` | **Deferred Future Work** | next slice (WP-6B) | Consume + `Routed → disposition` transition | **NO** — ARB Decision C |
| F-1 · F-2 · N-5 · AP-1 · AP-2 · DC-2 | **Completed** | — | — | No |

**No item is an Implementation Defect within WP-6's approved scope. No item blocks acceptance.**

## 5. DDD traceability — complete

| Layer | WP-6's chain |
|---|---|
| Business decision | bound the conduct; a clock never decides significance |
| Strategic architecture | Q-2 owns durations · Policy 4 · §197 returns disposition to Contestation |
| Approved design | EPIC-004K §57/§72/§81/§197 · ADR-T3/T5/T8 · ADR-MP-06 · ARB Decisions A/B/C |
| Implementation | port + adapter · MAD-aware enforcement · conflict guard · published event |
| Verification | 10 keystones (3 asserting absences) · 4 gates · 4 reviews (APR · ADPR · ADAPR · AGIR) |
| Acceptance | this package |

**Every implemented component traces to an approved authority.** The two exceptions were *found and closed*: AP-1/DC-2 (a decision without authority — removed) and AT-EVT-001 (an evolution — escalated, not absorbed). **No missing traceability remains.**

## 6. Required ARB decisions — two, neither blocking

1. **AT-EVT-001** — accept or reverse the ownership-map evolution; if accepted, place the rule in an ADR or recorded ruling.
2. **DC-1** — rule on the invalid-MAD case, or record a deliberate decision not to.

## 7. Acceptance recommendation

> ### **THE EVIDENCE INDICATES WP-6 IS READY FOR ARB ACCEPTANCE.**
>
> **Readiness is established by evidence; acceptance is established by authority — they are different acts, and this report performs only the first.**

**Recommended formulation for the record, if the ARB accepts the evidence:**

> *The implementation, verification and governance evidence indicate that WP-6 is ready for ARB acceptance. If the ARB accepts the evidence, WP-6 may be recorded as **Accepted**; the remaining governance items (**AT-EVT-001** and **DC-1**) remain explicitly tracked as **non-blocking**; and the roadmap authorizes opening WP-7. The constitutional questions **B-1..B-6** remain a separate strategic governance matter and are **outside WP-6's acceptance scope**.*

| ARB Decision | Outcome |
|---|---|
| WP-6 slice acceptance | ⏳ **awaiting the ARB** |
| AT-EVT-001 evolution | ⏳ awaiting (non-blocking) |
| DC-1 invalid-MAD case | ⏳ awaiting (non-blocking) |

**Evidence:** implementation complete and verified across four independent gates · structural integrity preserved · governance integrity preserved after two corrections applied before acceptance · every remaining item classified with a named owner · **no known implementation work remains within the approved scope of WP-6** · **no item blocking**. The two ARB decisions concern *governance artifacts and future rules*, not this slice's software.

## 8. Next work the roadmap authorizes UPON ARB acceptance

**Upon ARB acceptance of WP-6, the roadmap authorizes opening WP-7 — Retention alignment** (`audit:cleanup` becomes EPW-aware; per-election arithmetic from the same config). It depends on WP-6's config existing, which it now does, and it is the roadmap's next item.

**Carried forward into WP-7:** the WP-5 checkpoint (can Adjudication's authority-decision originator leave the allowlist once `issueDetermination` derives provenance from a consumed message?) and the framework's **second** operational record — the first cross-slice evidence of whether the review framework holds unchanged.
