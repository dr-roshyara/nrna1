# EPIC-004K Adjudication Process Manager Architecture

**Kind:** Architecture Evolution artifact — the successor phase the frozen chain's own residue pre-assembled. **NOT chain artifact №10:** the nine-artifact tactical chain is COMPLETE and FROZEN; this design begins where it intentionally stopped, treating every frozen artifact as immutable input.
**Role:** Chief Domain Architect under ARB governance. Nothing frozen is reopened; where this design's conclusions press on a frozen decision, the pressure is expressed as a **recommended superseding decision for ARB issuance** — succession, never retroactive editing.
**Opening agenda (per the frozen cluster rule — "opened together"):** the seven-member Emergent Design Cluster · the Q-2/finality center (cross-referenced, never merged) · jurisdiction semantics (only if necessary).
**Vocabulary note (deliberate):** this document says **Process Manager**, never "saga." ADR-T8 is binding: *the correction loop is 5 causally-linked transactions — never a saga.* §4 and §13 demonstrate that this PM does not touch that rule; the word is avoided so the design cannot be mistaken for what ADR-T8 forbids.
**Naming honesty (from the frozen Candidate-2 ruling):** "Proceeding" remains an architectural hypothesis without business identity. The PM is **architecture, not ubiquitous language** — it needs no business name, and this design does not smuggle one in. Working name: **the Adjudication Process Manager (APM)**. The recorded reversal condition (aggregate re-proposal if "proceeding"/"case" enters genuine business language) stands untouched.

---

## Derivation (the mandated order, made visible)

**Business process** — a routed challenge must be adjudicated: an evidentiary basis is assembled, a constitutional authority decides, and exactly one conclusion follows — either the binding ruling is issued, or failure is declared because the evidence is insufficient (the declare-failure obligation: *never certify on insufficient evidence*).
**Business responsibility** — someone must own the *conduct* of that process: receiving the request, tracking what evidence was admitted, expressing demands for more, receiving the authority's decision, and concluding exactly once. Q-1 settled that this owner **decides nothing** — the authority decides; Governance owns authority validity; the Determination aggregate owns the record.
**Business coordination** — the conduct spans: Contestation (the routed challenge), upstream evidence (admissions by opaque reference), Governance's authority contract (the decision arrives from a delegated authority), the Determination aggregate (issuance), and **time** (demands outstanding; deliberation horizons). No single aggregate can own this while preserving the participants' protected truths — which is the PM entry condition, validated in §2.
**From coordination → responsibilities → states → transitions → messages → timers → persistence → technical realization** — §§3–12. Never reversed.

## 1. Process purpose

Conduct the adjudication of one routed challenge from request to conclusion: assemble the evidentiary basis (admissions of opaque evidence references), express evidence demands, receive the constitutional authority's decision, and conclude **exactly once** — as a ruling request toward the Determination aggregate, or as a declared failure. The APM is the **head of the constitutional correction loop** — the piece EPIC-003 found missing (risk R-1: "the loop is an open arc") — and only the head: from `DeterminationIssued` onward, the loop remains pure event choreography (ADR-T8).

## 2. Entry condition validation

*A Process Manager exists only when business coordination spans multiple aggregates, or spans time, or cannot naturally belong to a single aggregate while preserving aggregate autonomy.*

- **Spans aggregates/contexts:** yes — Challenge (Contestation's aggregate), evidence references (upstream contexts' subjects), the authority decision (Governance's contract), the Determination aggregate (this context). Four owners, none of which may absorb the others' truths: Determination cannot track demands without becoming the deliberation (rejected at №2); Challenge cannot own Adjudication's conduct (TP-2: it *requests*, never creates).
- **Spans time:** yes — evidence demands are issued and await satisfaction; the authority's deliberation is not instantaneous; the adjudication horizon (Q-2's Maximum Adjudication Duration) bounds the whole conduct.
- **Removal test:** remove the APM and — the loop's head stays missing (no production path from routed challenge to issuance; EPIC-003 R-1 persists) · the declare-failure obligation has no seat (frozen №6/№7 deferrals become permanently homeless) · the conclude-time fixation obligation (R-4-expanded, the Candidate-2 ruling's surviving obligation) has no orchestration to feed it. Removal weakens accepted business responsibilities. **Entry condition: MET.**

## 3. Responsibilities (each with the discipline the chain used)

| # | Responsibility | Grounds (frozen) | What it is NOT |
|---|---|---|---|
| PM-1 | **Receive the adjudication request** for a routed challenge and open exactly one active process per challenge | TP-2 ("Contestation requests, never creates"); loop-head deferral (№7); mirrors INV-B1 from the process side (№2 C2 candidate) | Not admission of the challenge's merits — routing/admissibility is Contestation's |
| PM-2 | **Track evidence admissions** — the set of opaque references admitted into *this* judgment | EvidenceSet deferral (№5→cluster); COL-1 (Collection supplies; Adjudication consumes) | Not evidence content, custody, or integrity (upstream-owned; COL-3a) |
| PM-3 | **Express evidence demands** and track them to satisfaction or deadline | COL-5a's conditional backward flow (the C–S contract's return channel); the demands element of the №2 sketch | Not a command toward Collection's internals — a demand under the existing contract |
| PM-4 | **Receive the authority's decision** — ruling content or insufficiency — via Governance's published contract semantics | Q-1 (the authority decides; K1 structural: the process **receives, never computes**) | Not computing legitimacy/sufficiency (constitutionally forbidden — K1/Q-1); not validating the delegation (Governance's, sole owner) |
| PM-5 | **Conclude exactly once, atomically fixing the evidence-set-as-considered with the conclusion** | Criterion 1's surviving obligation (Candidate-2 ruling); P3 applied to the judgment | Not the *permanent* fixation — that is INV-4's, at the aggregate (see §R-4-expanded, §13) |
| PM-6 | **On ruling-requested: request issuance** (IssueDeterminationCommand toward the aggregate) and confirm completion | №7's confirmed command; INV-B1 makes the request replay-safe for free (duplicate → refusal → reconciliation) | Not issuing — the aggregate issues; the PM requests (TP-2 extended: nobody but the authority's decision creates a ruling) |
| PM-7 | **On failure-declared: record the declared failure and emit its occurrence** | The declare-failure obligation (ES family, mandatory finding); `AdjudicationFailureDeclared` + `DeclareFailureCommand` deferrals (№6/№7) | Not a correction demand decision — what Collection does with it is Collection's |
| PM-8 | **Enforce the adjudication horizon** — the process must not run unbounded | Q-2's Maximum Adjudication Duration component; the Evidence Preservation Window arithmetic requires a bounded horizon to be computable | Not defining the horizon's duration (Q-2 business policy — ARB parameter) |

## 4. Process boundaries

**Inside:** one challenge's adjudication conduct, from request received to conclusion recorded (plus horizon enforcement).
**Outside — explicit:** the challenge lifecycle (Contestation's) · evidence content/custody (upstream) · authority validity (Governance, sole owner — the APM consumes an assertion/decision, per Q-1's binding constraint) · the ruling record and its lifecycle (Determination aggregate — all eight frozen invariants untouched) · **the correction loop from `DeterminationIssued` onward** (pure choreography; the APM neither observes nor coordinates Election/Contestation reactions — ADR-T8 honored by construction) · determination finality (the Q-2 center — a *separate* temporal concern; §Q-2 below assigns it a realization that is deliberately NOT this PM's instance lifecycle) · publication/supersession (Policy 1, publication-side).

## 5. States (business-derived, minimal)

| State | Meaning (business language) |
|---|---|
| **Opened** | The request for adjudication of a routed challenge has been received; the process exists |
| **Assembling** | Evidence admissions are being received; demands may be outstanding |
| **AwaitingDecision** | The assembled basis has been put before the constitutional authority; the process holds |
| **Concluded — RulingRequested** | The authority decided; issuance was requested with the fixed evidence-set-as-considered |
| **Concluded — FailureDeclared** | The authority found the evidence insufficient; the failure is declared and announced |
| **Expired** | The adjudication horizon elapsed without a conclusion — a distinct terminal fact, never a silent disappearance |

*No "UnderReview/Investigating" intermediary is minted beyond Assembling/AwaitingDecision — the business evidence names assembly, decision, conclusion; finer gradations would be invented, not discovered (ASP).*

## 6. State transitions

| From → To | Trigger | Guard |
|---|---|---|
| ∅ → Opened | Adjudication request received (§8/§9) | No active process exists for this challenge (PM-1's uniqueness) |
| Opened → Assembling | First admission or demand | — |
| Assembling → Assembling | Admission received / demand issued / demand satisfied | **No admission after conclusion** (structurally: only reachable pre-conclusion) |
| Assembling → AwaitingDecision | Basis submitted to the authority | At least the required basis present (presence, not sufficiency — sufficiency is the authority's judgment, K1) |
| AwaitingDecision → Concluded-RulingRequested | Authority's ruling decision received | Exactly-once conclusion; atomic fixation of the considered set (PM-5) |
| AwaitingDecision → Concluded-FailureDeclared | Authority's insufficiency decision received | Same exactly-once + fixation guard |
| AwaitingDecision → Assembling | Authority demands more evidence (a decision to not-yet-decide) | Conclusion has not occurred |
| any non-terminal → Expired | Adjudication-horizon timer fires | Conclusion has not occurred |

Terminal states: the two Concluded kinds and Expired. **Exactly one conclusion, of exactly one kind, or an expiry — never more than one, never a mix** (the №2 sketch's invariant, now seated).

## 7. Timers

| Timer | Purpose | Duration owner |
|---|---|---|
| Evidence-demand deadline (per demand) | A demand unanswered past its deadline returns to the process for re-decision (re-demand, proceed to the authority with what exists, or await) — the *reaction* is the authority's/process's, never automatic escalation to a conclusion (K1: no automatic adjudication) | Business policy — ARB parameter (Q-2-adjacent) |
| Adjudication horizon (per process) | Bounds the whole conduct; firing yields Expired | **Q-2's Maximum Adjudication Duration** — definition is Q-2's; the APM is its *enforcer*, not its owner |

**What is deliberately NOT here: the determination-finality timer.** See §Q-2 — finality attaches to the *determination* after this process has already concluded; giving it to the APM instance would keep every process alive past its own conclusion, conflating the two centers the frozen chain kept apart.

## 8. External triggers

1. **The adjudication request** (loop head) — from Contestation's side, per TP-2. Shape resolved in §9.
2. **Evidence admission arrivals** — upstream satisfaction of demands (transport counterpart deferred; see §14).
3. **The authority's decision** — via the Q-1 published-contract crossing (its tactical transport is later design bounded by Q-1; the APM consumes the decision, whatever the wire).
4. **Timer firings** — §7.
5. **Issuance confirmation** — the aggregate's acceptance (or INV-B1 refusal → reconciliation; §12).

## 9. Messages consumed — and the loop-head shape decision (cluster member №5, resolved as a recommendation)

| Message | From | Note |
|---|---|---|
| **`ChallengeRouted` (integration event) — RECOMMENDED as the loop head** | Contestation | See below |
| Evidence admission / demand-satisfaction | Upstream (Collection-side, future) | Business shape defined; transport deferred (§14) |
| Authority decision (ruling content or insufficiency) | Governance-delegated authority via the published contract | K1: received, never computed |
| `DeterminationIssued` (own context's event, observed for confirmation) | Adjudication outbox | Closes PM-6's loop; replay-safe by INV-B1 |

**The loop-head recommendation: event-consumption (`ChallengeRouted` published), not a `RequestDeterminationCommand`.** Grounds: (a) TP-2's language is *request*, and in this architecture's precedent the request-shaped crossing between contexts is an integration event consumed by a registered handler — exactly how every other loop hop already works (choreography, ADR-T8's spirit); (b) a cross-context *command* would be the architecture's first, inventing a new coupling style for no gain; (c) `CoordinatesAdjudication`'s own frozen comment anticipates precisely this ("when ChallengeRouted consumption lands, this becomes EventProvenance::fromConsumed") — the chain-start mint moves to Contestation's raise-path, and the APM propagates provenance. **Consequence requiring ARB sanction (succession, not editing):** `ChallengeRouted` is today an internal Contestation domain event with no hydrator; publishing it makes it published language — a Contestation-side evolution (hydrator + catalog entry), recommended here, decided there. **Rejected alternative, recorded:** `RequestDeterminationCommand` (cross-context command) — returns only if the ARB rules that loop-head initiation must be imperative rather than reactive (e.g., a human files the request directly with Adjudication, bypassing Contestation — no such business path exists today).

## 10. Messages produced

| Message | Toward | Note |
|---|---|---|
| `IssueDeterminationCommand` | The Determination aggregate's issuance boundary (in-context) | The frozen №7 command, unchanged in intention; carries the authority's decided content + the fixed considered-set (see R-4-expanded) |
| **`AdjudicationFailureDeclared`** (event — the cluster's first-class candidate, now seated) | Announced; consumers: Contestation (challenge disposition — open question §15) and Collection-side (the renewed-collection demand, COL-5a) | Business occurrence: "the evidence was insufficient; no ruling can issue." Domain-expert language confirmed at №6 |
| Evidence demand | Upstream under the COL-1/COL-5a contract | Business shape here; transport deferred (§14) |
| Expiry announcement (kind: to be ruled with Q-2's horizon policy) | TBD by the horizon's business policy | An expired adjudication is business-significant; its consumer set is Q-2-adjacent — flagged, not invented |

## 11. Persistence needs (cluster member №6, resolved as architecture)

The APM **requires durable state**: demands outstanding and their deadlines, admissions to date, the awaiting-decision hold, and timers must all survive process and deployment boundaries — this is long-running coordination by definition (§2).

- **Shape:** one durable process record per challenge (unique-active-per-challenge as a storage constraint — the same two-seat pattern as INV-B1: guard at the boundary, unique index as concurrency backstop), plus an **event-logged record** of admissions/demands/conclusion — the Candidate-2 ruling's own words ("coordination plus an event-logged record").
- **What it is NOT (RMSP honored by analogy, not stretched):** the PM record is **not an aggregate and gets no domain repository** — RMSP governs aggregate repositories; PM state is orchestration infrastructure (Application/Infrastructure-seated), persisted by its own minimal store whose surface is exactly: create-on-open, append admissions/demands, record conclusion, load-for-reaction, due-timer query. No query zoo; reads for humans go through read models (house CQRS-light).
- **Conclude-time atomicity (PM-5):** the conclusion + the fixed considered-set + the authority reference commit as one write to the PM record. This is the *working* record's fixation; the *permanent* fixation is the aggregate's (next).

**R-4-expanded — the seat decision (cluster member №4, resolved as a recommendation):** the permanent fixation of the evidence-set-as-considered belongs to **the aggregate's issuance** — the considered-set rides in `IssueDeterminationCommand` and is fixed into the ruling-bearing event by INV-4's existing single-fixation fact. Grounds: №3's own R-4 reasoning ("only the aggregate makes separation structurally impossible"); a PM-side-only fixation would make "issued" and "what it was issued from" two separable records — exactly what P3 forbids and what №4's INV-4 rider anticipated ("stated over the current single-envelope basis and does not foreclose the expansion"). **Consequences requiring ARB-sanctioned successors:** the `EvidenceSet` VO (cluster member №3) enters through artifact №5's deferral path as the command/event's considered-set carrier; `DeterminationIssued` evolves by **additive payload schema version** (the proven v1→v2 precedent — evolution by succession, no frozen artifact edited). The PM's event-logged conclusion remains the deliberation's working trace; the determination remains the constitutional record. One truth, two records, one authoritative — stated so no future reader mistakes the PM log for a second source of authority.

## 12. Failure handling

- **Duplicate adjudication request:** PM-1's uniqueness guard → idempotent replay (the established inbox translation: business replay → ack no-op).
- **Issuance request meets INV-B1 refusal** (a determination already exists for the challenge): not an error to escalate blindly — **reconcile**: if this process itself concluded-and-requested earlier (redelivery), ack; if another writer issued (should be impossible under PM-1 + INV-B1 together), **dead-letter + escalate** — the conflicting-determination translation precedent (`ConflictingDetermination → PermanentFailure`) applies unchanged.
- **Authority decision arrives after conclusion:** business replay (same decision) or conflict (different) — same translation family.
- **Authority decision arrives for an Expired process:** a genuine business question (late ruling on an expired adjudication) — **parked to Q-2's horizon policy** (§15); the mechanism (park/dead-letter) exists; the *rule* is business policy.
- **Transport failures:** entirely inherited — outbox retry/dead-letter, inbox park/re-drive/deadline, consumer isolation (ADR-MP series). The APM adds **no new failure machinery**; it is a consumer/producer like every other, with `EventProvenance::fromConsumed` propagation (chain-start minting moves to the true head per §9 — the loop's one-mint invariant is preserved, enforced by the existing CorrelationIdMintingTest allowlist, whose update is an ARB-sanctioned change).

## 13. Relationship with the frozen Determination aggregate (and the rest of the frozen world)

- The APM **requests; the aggregate rules.** Every one of the eight frozen invariants stands untouched; the APM depends on INV-B1 for its own replay safety (reuse, no new mechanism).
- The APM is **upstream of the correction loop, never inside it.** `DeterminationIssued` onward remains 5 causally-linked transactions of pure choreography — **ADR-T8 is not merely respected; the APM's boundary is drawn where it is *because* of ADR-T8.** No compensation, no cross-context transaction, no loop-coordination exists in this design.
- **Q-1 constraint honored structurally:** the APM consumes authority decisions; it contains no authority rules, no validity checks, no delegation knowledge. Where the published-contract consultation lands (application-layer port) is later tactical design, bounded by Q-1.
- **ADR-T17 superseding recommendation (the cluster's member №7 — the expected successor decision, drafted for ARB issuance):** *ADR-T17's anticipated "legitimacy rules as an Adjudication DOMAIN SERVICE" is superseded as follows: legitimacy/sufficiency conclusions are decided by the constitutional authority (Q-1; K1 structural) and received by the Adjudication Process Manager; no Adjudication domain service decides them. A future advisory computation (a recommendation an authority weighs) remains possible as application-layer support, explicitly non-binding, and returns through ARB review with a consumer as evidence.* — Recommended text; the ARB issues it into the ADR-T log as the next entry. ADR-T17 remains valid history until that issuance.

## Q-2 — the finality center, resolved to the commission's three questions (recommendations; ARB decides)

**Cross-referenced, never merged** (the frozen rule): the APM design and Q-2 interact at exactly two points — the adjudication horizon (§7, the APM enforces what Q-2 defines) and the executor question below. Neither absorbs the other.

1. **Retention arithmetic (shape, with ARB-owned parameters):** per Constitutional Policy 2, *Evidence Preservation Window(election) = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin*, attached to the election instance. The Determination adds one derived term: a determination is itself challengeable (TargetType includes Determination), so **finality(determination) = issued-at + Contestation Window(determination-type), provided no challenge against it is open at closure** — and preservation of adjudication evidence must run to *at least* the latest such closure plus any running adjudication's completion. The durations are business policy (per-election-type, per the policy's own future-proofing) — **the arithmetic is architecture; the numbers are the ARB's.**
2. **Finality trigger:** **the closure of the challenge window over the determination, with no open challenge** — exactly the DMT finding's lean (№7): the dormant `finalize()` is the mechanism of a missing *temporal* intention, not of a missing command.
3. **Realization shape: a TEMPORAL BUSINESS POLICY, executed by a scheduled policy evaluator — deliberately NOT this PM's instance, and NOT a public command.** Grounds: the APM instance concludes at issuance; finality occurs later, on the determination's own clock — keeping every process instance alive to watch it would conflate the centers. No actor *expresses* finalization (time does), so `FinalizeDeterminationCommand` correctly never exists (the №7 analysis, confirmed). The executor is the platform's existing scheduled-command pattern (the outbox/redrive precedent): *evaluate the policy → for each determination whose window closed unchallenged → invoke the aggregate's `finalize()` through its transactional boundary.* The dormant mechanism gains its caller; DMT's third branch (missing intention, now modeled) closes. **Armed reversal condition, surfaced for the ARB:** if the retention machinery (records-management side) must *observe* finality, №6's `DeterminationFinalized` reversal condition now has its consumer candidate — the event returns through ARB review with that consumer as evidence, exactly as the frozen condition prescribes. Not decided here.

## 14. Architectural silences (ASP — every absence a decision)

- **No saga, no compensation, no loop coordination** — constitutional (ADR-T8); recorded above.
- **No authority model, no validity check, no sufficiency computation in the APM** — Q-1/K1 structural; the APM is deliberately *judgment-free*.
- **No `DeterminationFinalized` event minted here** — the frozen reversal condition is armed (consumer candidate identified), not triggered; ARB decides.
- **No finality timer in the APM** — assigned to the temporal-policy executor; grounds in §Q-2.
- **No evidence-demand transport designed** — Collection's implemented counterpart does not exist yet (EPIC-003: the strategic Collection BC is unrealized); the demand's *business* shape is defined (PM-3, §10), its wire is future work when the upstream exists. Designing a transport toward a non-existent counterpart would be invention.
- **No challenge-disposition rule on failure-declared** — Contestation's reaction to `AdjudicationFailureDeclared` (does the challenge return to Routed? lapse? await new evidence?) is Contestation's design, flagged as an open question, not decided across the boundary.
- **No read models, APIs, UI, or Laravel artifacts** — out of scope by commission.
- **No new failure machinery** — inherited transport discipline suffices (§12).
- **Jurisdiction semantics: NOT needed by this design** — the APM passes the jurisdiction reference opaquely into the issuance command exactly as the frozen VO carries it; nothing in the process's conduct interprets it. The item stays open and non-blocking, untouched.

## 15. Open questions (carried forward, named owners)

1. **Q-2's parameters** — the Contestation Window terms (durations, per-election-type, per-target-type incl. determinations), the Maximum Adjudication Duration, the Legal Safety Margin: business policy, ARB definition (the standing request from the work package, now load-bearing in three places).
2. **Horizon-expiry policy** — what an Expired adjudication means for the challenge and whether a late authority decision can ever be honored: Q-2-adjacent business policy.
3. **Contestation's reaction to `AdjudicationFailureDeclared`** — challenge disposition on declared failure: Contestation-side design.
4. **`ChallengeRouted` publication** — Contestation-side evolution (hydrator, catalog entry, provenance mint at the true chain head): recommended here, decided there; the minting-allowlist update rides with it.
5. **The `DeterminationFinalized` armed reversal condition** — returns with the retention consumer as evidence, if the ARB triggers it.
6. **Advisory sufficiency computation** — permanently optional application-layer support under the recommended ADR-T17 successor; returns only with a consumer.

## 16. Self-review against the governance principles

- **Methodological Fitness Rule:** the entry condition discriminated — the finality concern was *refused* a PM realization (§Q-2), the loop-head command alternative was rejected, and the demand transport was refused as premature; the criteria did real work ✅
- **APP:** every touched aggregate protection (INV-1..B1) remains with its owner; the APM protects *process* properties (exactly-once conclusion, conclude-time fixation of the working record) and claims no aggregate property ✅
- **VODP:** one VO consequence (EvidenceSet) enters only through its frozen deferral path, traced to the R-4-expanded invariant need — no VO invented here ✅
- **ASP:** nine silences defended with grounds (§14) ✅
- **ADP:** every element derives from the frozen chain's named residue — the seven cluster members are each disposed (FailureDeclared → §10 · DeclareFailure → PM-7/§10 · EvidenceSet → §11/R-4-expanded · R-4-expanded seat → §11 · loop-head shape → §9 · PM-state persistence → §11 · ADR-T17 revision → §13); nothing bypassed the chain ✅
- **DMT:** the dormant `finalize()` closes on its third branch — a missing temporal intention, now modeled with its trigger, policy, and executor; the mechanism was neither deleted nor commanded into service ✅
- **RMSP:** no aggregate repository grows; the PM store is named as non-repository orchestration persistence with a minimal enumerated surface ✅
- **Succession, never editing:** three recommended successor decisions (ChallengeRouted publication · DeterminationIssued additive schema evolution · the ADR-T17 superseding text) — all drafted for ARB issuance; zero frozen artifacts modified ✅
- **Evidence:** every responsibility, state, message, and refusal carries its frozen grounds inline ✅ · **Removal tests:** §2 (the PM), §9 (the loop-head shape), §Q-2 (the non-command finality) ✅ · **Reversal conditions:** the aggregate re-proposal (standing), the loop-head command alternative, the DeterminationFinalized arm, the advisory-computation return — all recorded ✅

**Stop condition: STOP.** The Process Manager Architecture is complete as architecture — no implementation, no infrastructure, no Laravel artifacts, no code. Await explicit ARB review: the natural decision agenda is (1) this architecture itself, (2) the three drafted successor decisions, (3) Q-2's parameters, which three sections of this design consume.

---

## APPROVED (ARB, 2026-07-26)

**The Process Manager Architecture is APPROVED.** ARB verdict (recorded verbatim in substance): a model of disciplined architectural reasoning — derives from the frozen chain's named residue, applies every governance principle, drafts successor decisions rather than editing frozen artifacts; the nine silences are defended; the entry condition discriminated; DMT's third branch closes on `finalize()`.

**Status ledger at approval:** the architecture — APPROVED · the three successor decisions — **RECOGNIZED, awaiting explicit ARB issuance** (recognition is not issuance; nothing is issued by this approval) · Q-2's parameters — **ARB decision required** (five items: Contestation Window terms · Maximum Adjudication Duration · Legal Safety Margin · horizon-expiry policy · the armed `DeterminationFinalized` reversal condition) · open questions with named owners stand as §15 records them.

**Next: the ARB decision agenda** — issuance of the successor decisions and the Q-2 parameters — before any refinement or implementation.

---
*Immutable inputs: the frozen nine-artifact chain (`EPIC-004B`..`EPIC-004J`) · `EPIC-004_Q1_Authority_Resolution.md` · the four Constitutional Policies (EPIC-003 §THE FOUR DECISIONS) · ADR-T1/T8/T11/T14/T16/T17(valid until superseded)/T19 · Q-2 as framed by the work package · EPIC-003 implementation inventories (the open-arc finding; `finalize()`'s zero callers; the existing scheduled-command and inbox-translation precedents).*
