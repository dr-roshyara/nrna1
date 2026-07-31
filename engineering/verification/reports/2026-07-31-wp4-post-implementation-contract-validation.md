# WP-4 Post-Implementation Contract Validation

**Status:** ✅ **ACCEPTED by the ARB (2026-07-31) as the ARCHITECTURAL BASELINE for the correction-loop integration pattern.** The Cross-Context Integration Contract is ruled sufficiently mature to govern subsequent **correction-loop** consumers; future implementations continue to validate — and where necessary refine — its **scope**, rather than assuming universal applicability.
**Date:** 2026-07-31 · **Role:** Chief Software Architect / ARB · **Commission:** post-implementation validation — *did the contract predict the implementation that emerged?*
**Constraints honored:** no production code changed · no ADR created · contract not redesigned · WP-4 not re-implemented · no new abstraction invented
**Method:** the implementation is the evidence. Every row below cites what the committed code actually does, read after GREEN.

> **Verdict in one line:** the first implementation provides **strong empirical support** for the contract — it predicted reality on all nine invariants, over-predicted on exactly **one specification row**, and the search for what *protects* it exposed **two pre-existing fitness gaps — one of them mine, from WP-3A.**

**Scope of this claim (ARB refinement, 2026-07-31 — adopted):** one implementation validates **this interaction pattern**, not the contract's full generality. Consumer behaviours **not yet exercised**: asynchronous compensation · competing consumers · long-running orchestration · consumer-owned version negotiation. *(Multiple consumers of one event **is** already exercised — `DeterminationIssued` has two — but as derivation evidence, not as a post-contract test.)* The stronger claim becomes justified once further consumers follow the same contract.

---

## Phase 1 — Prediction audit

The handler's **complete** import list is four lines — the primary evidence for R-1/R-2:

```php
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;  // own context
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;               // own context
use App\Contexts\Shared\Application\Inbox\InboxHandler;                        // Shared Application
use App\Contexts\Shared\Application\Inbox\InboxMessage;                        // Shared Application
```

| Rule | Predicted | Observed | Conforms |
|---|---|---|---|
| **R-1** | Consumer imports no producer Domain | Zero Contestation imports of any kind | ✅ |
| **R-2** | Consumer imports no producer Infrastructure | No hydrator import; nothing from `Contestation\Infrastructure` | ✅ |
| **R-3** | Hydrator stays producer-side | `ChallengeRoutedHydrator` is invoked only by the relay, inside Contestation | ✅ |
| **R-4** | Consumer reconstructs its own VO from primitives | `ChallengeRef::fromString($payload['challengeId'])` | ✅ |
| **R-5** | Payload carries primitives only | `{schema_version, challengeId, routedTo, occurredAt}` — all scalars | ✅ |
| **R-6** | Provenance on the envelope, never in the domain event | `InboxMessage` carries it; the domain event does not | ✅ *(but see D-1)* |
| **R-7** | Producer never names its consumers | Registration is in **`AdjudicationServiceProvider`**; Contestation names nobody | ✅ |
| **R-8** | Consumer idempotent at two seats | Seat 1 platform dedupe `(event_id, consumer_context)`; seat 2 **PM-1** | ✅ *(but see D-2)* |
| **R-9** | Identity crosses as an opaque string | `challengeId` is a string; `ChallengeRef` ≠ Contestation's `ChallengeId` | ✅ |

**Nine of nine conform, and none was forced.** The handler was written from the PM's needs; conformance fell out. The strongest single piece of evidence is negative: the audit's predicted near-miss (importing the producer's hydrator) **did not happen**, and Deptrac reports **0 violations** — so the contract's most important prediction was also its most useful one.

### Two prediction divergences — the contract was not perfect

**D-1 — the contract over-predicted provenance (§5, one row).** Contract §5 specifies: *"Provenance: `EventProvenance::fromConsumed(...)` — never `start()`"*. **Observed: the handler makes no provenance call at all.** It publishes nothing, so there is no envelope to stamp. §5's row silently assumed *every consumer reacts by publishing* — true of the three consumers the contract was derived from, false of the first consumer built after it. R-6 itself is unharmed (it constrains what a domain event may carry); the **specification row** is what over-reached.

**D-2 — R-8's "two seats" is right, but the second seat moved.** The contract's evidence described seat 2 as *handler/aggregate-level* idempotency. Observed: seat 2 is the **process manager**, which owns it as a designed property (*"every entry point is safe to replay"*). The rule holds; its *locations* are broader than the derivation recorded — platform + **any consumer-side owner**.

Neither divergence is a violation. Both are the contract being **more specific than its evidence warranted** — exactly what a first implementation is supposed to expose.

## Phase 2 — Responsibility ownership matrix

| Responsibility | Expected owner | Actual owner | Held |
|---|---|---|---|
| Decide a challenge is routed | Contestation domain | `Challenge::route()` | ✅ |
| Publish the routing | Contestation Infrastructure | `ChallengeOutboxAdapter::writeRouted()` | ✅ |
| Serialize / version the payload | Producer Infrastructure | same adapter (`schema_version: 1`) | ✅ |
| Transport, retry, dispatch | Shared platform | `OutboxEventProcessor` → `IntegrationEventDispatcher` | ✅ |
| Deduplicate delivery | Shared platform | inbox `(event_id, consumer_context)` | ✅ |
| Decide *that* Adjudication consumes | **Consumer** | `AdjudicationServiceProvider::boot()` | ✅ |
| Reconstruct local identity | Consumer Application | `ChallengeRoutedReactionHandler` | ✅ |
| Invoke the process | Consumer Application | same handler (one statement) | ✅ |
| **The PM-1 rule** (one active process per challenge) | Process manager | `AdjudicationProcessManager::openFor()` | ✅ |
| **Replay idempotency** | Process manager | same, via `activeForChallenge()` | ✅ |
| Legal state transitions | Adjudication domain | `AdjudicationProcessState::guard()` | ✅ |
| Active-scoped uniqueness | Database | partial unique index | ✅ |

**No responsibility crossed a boundary, and none was duplicated.** The notable observation is the handler's *thinness*: it owns reconstruction and invocation and nothing else. Every business question it could have answered is answered by an owner that already existed.

## Phase 3 — Defended absence validation

| Absence | Verdict | Evidence |
|---|---|---|
| **No outcome translator (G-2)** | **JUSTIFIED — and now empirically confirmed, not merely argued** | The house translator's signature is `ChallengeReactionOutcomeTranslator::toInboxOutcome(Throwable $businessCondition): Throwable` — it exists to classify **business conditions** into inbox markers. WP-4's handler raises **no** business condition (`openFor()` is void, idempotent, guard-free at this entry). There is literally nothing for a translator to translate. G-2 predicted this by enumeration; the translator's own type signature confirms it |
| **No local `RoutedTo` VO** | **JUSTIFIED** | `openFor(ChallengeRef)` takes identity only. `routedTo` is read by nobody. A VO would be a component without a consumer |
| **No producer Domain import** | **JUSTIFIED — architecturally required** | R-1 / ADR-T16 |
| **No producer hydrator import** | **JUSTIFIED — the audit's main save** | Would have been the codebase's first cross-context Infrastructure dependency and a Deptrac failure |
| **No provenance minting** | **JUSTIFIED, and stronger than the contract asked** | Not `fromConsumed()` either — see D-1. Nothing is published, so nothing is stamped |
| **No bespoke `PermanentInboxFailure`** | **JUSTIFIED *conditionally* — and the condition should be visible** | Unreachable because the relay hydrates producer-side **before** dispatch and `ChallengeRoutedHydrator` rejects a missing `challengeId` loudly. **But that means WP-4's fail-loud property is INHERITED from WP-3A, not owned by Adjudication.** Residual risk if that guard is ever weakened: `ChallengeRef` still refuses an empty identity (so no meaningless adjudication opens), but it throws a generic `InvalidArgumentException` — which the platform classifies as **transient**, so it would retry forever instead of dead-lettering. **Reversal condition:** if a `ChallengeRouted` producer ever publishes without hydrator-side validation, Adjudication must own its own permanent-failure classification |

**One absence — `PermanentInboxFailure` — is a genuine *future extension point*, not merely a justified omission.** The other five are justified and closed. None is accidental.

## Phase 4 — Layer validation

| Component | Must own | Must not own | Verified |
|---|---|---|---|
| `ChallengeRoutedReactionHandler` (Application) | reconstruction, orchestration | business rules, transport, persistence, translation | ✅ Four imports, all own-context or Shared **Application**. No Eloquent, no facade, no `DB::`, no `Illuminate\*`. `handle()` is one statement, so there is no room for a rule to hide |
| Registration in `AdjudicationServiceProvider` (Infrastructure) | container wiring | business logic | ✅ Three lines: make registry, make handler, register |

**Gate corroboration:** PHPStan max **no errors** · Deptrac **0 violations / 571 allowed** · Architecture suite **146 green**. The Application layer imports `Application\Process` (same layer) and Shared **Application** ports — never Shared Infrastructure, even though the *registry* it is registered into lives there. The provider absorbs that asymmetry, which is correct.

## Phase 5 — Fitness function review — **the commission's material finding**

| Contract rule | Protection status |
|---|---|
| R-1, R-2 (cross-context imports) | ✅ **Already protected** — Deptrac, fail mode, per-context hexagonal layers where cross-context = violation by omission |
| R-5 (payload primitives) | ✅ Adequately protected by hydrator tests + PHPStan |
| R-6 (no provenance in domain events) | ✅ Protected — dedicated minting/provenance fitness guard |
| R-7 (consumer-side registration) | ⚠️ **Protected only per-event** — WP-4's keystone 3 asserts `has('Adjudication','ChallengeRouted')`. Nothing asserts the *general* property |
| R-8 (dedupe) | ✅ Protected by inbox tests |
| R-3, R-4, R-9 | 📄 **Documentation only** — and adequate: Deptrac forbids the import that would violate R-3, and R-4/R-9 are visible in every consumer |

### Gap F-1 — the existing completeness gate is **50 % blind and green**

`tests/Architecture/EventRegistryCompletenessTest.php` asserts every produced event type has a hydrator — from a **hand-maintained list**:

```php
private const PRODUCED_EVENT_TYPES = ['FeePaid', 'DeterminationIssued'];
```

**Four event types are actually produced** (verified by reading the outbox adapters): `FeePaid` · `DeterminationIssued` · `ElectionCorrectionApplied` (`ReactionOutboxAdapter:45`) · `ChallengeRouted` (`ChallengeOutboxAdapter:58`). **The list covers two.**

The behaviour is correct — both missing hydrators *are* registered — so this is a **guard-coverage gap, not a production defect**. But the gate passes green while checking half its surface, and **`ChallengeRouted`'s omission is mine: WP-3A shipped a new produced event type and did not extend the list** (the test's own comment instructs exactly that). This is the **AD-004 lesson recurring** — *guards that report OK while checking almost nothing*.

**Recommendation (evidence-backed):** extend the list to all four, and prefer **deriving** it from the registered outbox adapters over maintaining it by hand — a hand-maintained list has now drifted twice.

### Gap F-2 — no test asserts that a handler is *registered at all*

`InboxHandlerRegistryWiringTest` asserts only that the registry is a container singleton. A consumer could therefore exist, compile, pass PHPStan and Deptrac, and **silently never consume** — the failure mode PB-006 named (*Registration ≠ Delivery*). WP-4 is protected; a future consumer would not be.

**Recommendation:** one fitness test asserting every `InboxHandler` implementation under `app/Contexts` resolves in the registry under its own `consumerContext()` × `eventTypes()`. This is the **inbox-side mirror of the hydrator completeness test** — the asymmetry is the argument.

**Both gaps predate WP-4 and neither is caused by it.** They were found *because* WP-4 forced the question "what actually protects this contract?"

## Phase 6 — Generalization test

**Would another context consuming an event implement the same structure? Yes — and WP-4 established which of two shapes is canonical.**

| | Shape A — react **and publish** | Shape B — react only |
|---|---|---|
| Instances | `DeterminationIssuedReactionHandler` (Election) · `AdjudicateChallengeHandler` · `ResolveChallengeHandler` (Contestation) | **`ChallengeRoutedReactionHandler` (WP-4 — the first)** |
| Dependencies | reaction service + **outbox** + clock + **translator** | **process manager only** |
| Provenance | `fromConsumed()` | **none** |
| Business-condition translation | dedicated `…OutcomeTranslator` collaborator | **none needed** |

**The reusable pattern (all four instances):**

1. Implement `InboxHandler` in the consumer's **Application** layer.
2. Declare `consumerContext()` + `eventTypes()`; register in the consumer's **own** provider.
3. Read **primitives** from `InboxMessage`; reconstruct **local** VOs.
4. Delegate to one owner — aggregate, application service, or process manager.
5. **Add nothing else.** Publish only if the reaction produces a fact; translate only if business conditions must reach the inbox; mint never.

**The axis that varies is whether the reaction produces a new fact** — and that single question determines outbox, provenance and translator all at once. WP-4 is the **canonical minimal consumer**; the earlier three are the canonical *publishing* consumer. Both are the same pattern under one conditional.

### Phase 6b — *Are these the only architectural dimensions that vary?* (ARB question, 2026-07-31)

Tested by enumeration against the codebase and the roadmap, **not** by assertion. Result: **two shapes are canonical for the correction-loop family — and a THIRD consumption mechanism exists outside the contract entirely.**

| Candidate shape | Verdict | Evidence |
|---|---|---|
| **react-and-compensate** | **Architecturally EXCLUDED by decision** | ADR-T8: the loop is choreography from `DeterminationIssued` onward; the PM *"is not a saga and performs no compensation."* Not an unobserved gap — a ruled-out shape |
| **react-and-schedule** | **Exists, but is NOT an inbox consumer** | WP-6's temporal machinery is **clock-triggered**, not event-triggered (`enforceHorizon` / `dueForHorizon`), and the finality evaluator is explicitly *"scheduled policy … **NO event** — the armed condition holds."* A scheduled reader is outside the contract's scope (event-carried async integration) |
| **react-and-command** | **Collapses into Shape A** | A reaction that must drive another context cannot reach in (R-1/R-2) — it publishes. Not observed, and the contract itself forecloses the alternative |
| **react-and-ignore** | **Already inside Shape B** | PM-1 no-ops on a duplicate. "Ignore" is an *outcome* of the delegated owner, not a structural shape |
| **projection / read-model consumer** | ⚠️ **EXISTS TODAY — and is governed by NOTHING in this contract** | Governance's `CommitteeGovernanceProjector` + `CommitteeMemberProjectionListener`, Membership's `MemberDirectoryProjector` / `…ProjectionWorker`, Finance's `FeePaidProjection`. **None implements `InboxHandler`** — the exhaustive scan returns exactly the four correction-loop consumers. They are driven by Laravel listeners/workers and keep their **own** dedupe store (`Governance\Infrastructure\Projections\ProcessedEventModel`) |

**The material finding of this phase (wording sharpened at ARB review — the earlier "two parallel mechanisms" understated it):**

> **The platform contains two INDEPENDENTLY GOVERNED event-processing models serving different architectural purposes.**

They are not two implementations of one idea. They differ on every axis that matters:

| | **Governed integration** | **Projection infrastructure** |
|---|---|---|
| Architectural intent | **Business collaboration** across bounded contexts | **Read-model maintenance** |
| Path | producer → outbox → hydrator → inbox → `InboxHandler` → business reaction | event → Laravel listener → projection worker → read model |
| Ownership | consuming **bounded context** (registers itself) | infrastructure/query side |
| Correctness criterion | the business reaction happened **exactly once** | the projection **converges** to the source of truth |
| Deduplication | inbox `(event_id, consumer_context)` | its own `ProcessedEventModel` |
| Lifecycle | a fact consumed once, permanently | rebuildable — `GovernanceProjectionRebuilder` exists |
| Governed by | **this contract** | **nothing in this contract** |

The rebuildability row is the clinching difference: a projection may be **discarded and replayed**; a business reaction may not. **Different architectural purposes require different governance**, so no attempt is made here to unify them — and none should be made without a commission of its own.

This is **concrete evidence for the scope statement** the strategic validation recommended on theoretical grounds; the second model is exactly what an unscoped reading would wrongly claim authority over.

**Therefore the classification is narrowed as the ARB anticipated:** the two shapes are canonical **for the event-carried correction-loop family (all four inbox consumers)** — *not* for every consumer in the system. Extending them to projections would require analysing a mechanism this contract never covered. **No redesign is proposed here; the boundary is recorded.**

**One corroboration worth noting:** WP-6's roadmap entry specifies that *"late decisions dead-letter per the ruled policy"* — a **permanent-failure classification**. That is precisely the seat WP-4 defended as absent-because-unreachable (Phase 3). The extension point was independently predicted by the roadmap, which strengthens the "future extension point, not closed omission" classification.

## Phase 7 — Architectural learning

| # | Learning | Kind |
|---|---|---|
| **L-1** | **The contract is stronger than expected on the rules that matter.** Its R-2 correction — derived *before* implementation — prevented the codebase's first cross-context Infrastructure dependency. Zero rules had to be relaxed to let the implementation exist | Contract stronger |
| **L-2** | **The contract's §5 *specification* over-predicted where its §2 *invariants* did not** (D-1: provenance). The invariants were derived from a property; the spec row was extrapolated from three same-shaped examples. **Derived rules survived first contact; extrapolated rows did not** | Contract weaker (bounded) |
| **L-3** | **A consumer need not publish.** Every prior consumer did, which silently shaped the contract's language ("provenance", "own outbox") and the roadmap's expectations. WP-4 splits the pattern along *does the reaction produce a fact?* | Documentation improvement |
| **L-4** | **"Defended absence" now has a validated method.** G-2's enumeration was independently confirmed by the translator's type signature (`Throwable → Throwable` has nothing to act on when no business condition exists). The method generalizes: to justify an absence, name the responsibility and show which existing owner holds it | Method learning |
| **L-5** | **An inherited guarantee should be recorded as a dependency, not banked as a property.** WP-4's fail-loud behaviour comes from WP-3A's hydrator. That is fine today and fragile silently — hence the reversal condition in Phase 3 | Risk visibility |
| **L-6** | **Hand-maintained fitness lists drift, and drift green.** F-1 is the second occurrence of the AD-004 class. Completeness gates should enumerate from the system, not from a constant | Verification opportunity |
| **L-6b** | **The class now has a name (ARB, 2026-07-31): GOVERNANCE VERIFICATION DRIFT.** *The architecture stays correct while the mechanisms that verify it quietly become incomplete.* Three instances: **AD-004** (guards scanning zero files) · **AD-009** (completeness gate checking 2 of 4) · **AD-010** (registration unverified). Its signature is a **green** gate, which is why it outruns ordinary review — a failing test announces itself; a shrinking one does not. Counter-measure, drawn from all three: **gates must enumerate their subject from the system, never from a constant.** *(Named as recognized vocabulary, not minted as a ruling — R-34 default classification, and no new governance document per R-38.)* | Pattern recognition |
| **L-7** | **The most valuable question after GREEN was not "is it correct?" but "what protects it?"** Correctness was provable in six tests; both real gaps appeared only when protection was audited | Process learning |

## Deliverable 8 — Final ARB recommendation

> ### **The first implementation provides STRONG EMPIRICAL SUPPORT for the Cross-Context Integration Contract. WP-5 may proceed.**

1. **Contract status: strongly supported by its first implementation** — not yet validated in full generality (see the scope note at the head of this report). Nine invariants predicted; nine observed; none forced; zero Deptrac violations. It has earned the authority to govern the next consumer **of this kind**.
2. **One correction to §5 (not to the invariants):** its provenance row presumes a publishing consumer. Recommended wording — *"if the reaction publishes, provenance continues the conversation via `fromConsumed()`; a non-publishing consumer stamps nothing."* **A documentation correction the ARB may authorize; not taken here.**
3. **Record R-8's second seat as "any consumer-side owner"** (handler, aggregate, **or process manager**) — a clarification of scope, not a change of rule.
4. **Two fitness gaps recommended for repair, neither caused by WP-4 and both pre-existing:** **F-1** extend/derive `PRODUCED_EVENT_TYPES` (currently 2 of 4 — includes my own WP-3A miss); **F-2** add the inbox mirror of the hydrator completeness test. **Recommended as engineering work items, not created here.**
5. **Adopt the two consumer shapes as canonical FOR THE EVENT-CARRIED CORRECTION-LOOP FAMILY** (Phase 6 + 6b), with *does the reaction produce a fact?* as the discriminator — **explicitly not for all consumers**, because a third mechanism (listener/worker projections with their own dedupe store) exists outside this contract's scope.
   - ☑ **compensate** — excluded by ADR-T8 · ☑ **schedule** — clock-triggered, out of scope · ☑ **command** — collapses into Shape A · ☑ **ignore** — inside Shape B · ⚠️ **projection** — **exists and is ungoverned by this contract**.

6. **Convert F-1/F-2 to engineering work items, create no new document** (ARB direction). Filed in the existing register as **AD-009** and **AD-010** in `docs/implementation/Architecture_Debt_Backlog.md` — the same home as AD-004, the guard defect whose class F-1 repeats. Everything else in this report stays architectural *knowledge*.
7. **Arm one reversal condition:** if a `ChallengeRouted` producer ever publishes without hydrator-side validation, Adjudication must own its own `PermanentInboxFailure` classification. *(Corroborated: WP-6 already specifies that late decisions dead-letter — the same seat, predicted independently by the roadmap.)*
8. **WP-4 remains accepted as implemented.** Nothing in this validation asks for a change to its production code — the correct outcome for a commission that validates architecture *through* implementation rather than reshaping implementation to fit architecture.

## Success criteria (self-check)

☑ The implementation **naturally** conforms — conformance was a by-product of following the PM's needs, not an exercise in satisfying a document · ☑ every responsibility remained in its intended context (Phase 2, twelve rows) · ☑ **no accidental coupling appeared** — four imports, zero cross-context, Deptrac 0 · ☑ confidence in the contract is **strengthened and bounded**: its derived invariants survived, one extrapolated spec row did not, and that distinction is itself the finding · ☑ future consumers have a canonical pattern with an explicit conditional — plus two named protections that would make it self-enforcing.

---

**Traceability:** ARB post-implementation validation commission 2026-07-31 · contract `docs/architecture/Cross_Context_Integration_Contract.md` (§2 invariants, §5 spec) · strategic validation + layer classification (2026-07-31) · WP-4 GREEN commit `08c7d2066` · evidence read from `ChallengeRoutedReactionHandler` · `AdjudicationServiceProvider` · `ChallengeReactionOutcomeTranslator` · `OutboxEventAdapter` · `ReactionOutboxAdapter` · `ChallengeOutboxAdapter` · `EventRegistryCompletenessTest` · `InboxHandlerRegistryWiringTest` · `InboxHandlerRegistry` · ADR-T16 · ADR-T3/T4/T5 · ADR-MP-06 · PB-006 · AD-004/AD-007/AD-008. **No production code changed; no ADR created; contract not redesigned.**
