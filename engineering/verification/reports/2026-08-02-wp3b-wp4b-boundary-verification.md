# WP-3B / WP-4B — Ownership and Sequencing Verification

**Date:** 2026-08-02 · **Prepared by:** Principal DDD Architect / Recording Architect
**Question:** does WP-3B's remaining responsibility — **removing the correlation mint from `CoordinatesAdjudication`** — stay in WP-3B, transfer to WP-4B, or require a governance amendment?
**Status:** verification only. **No code changed · no roadmap changed · no ADR created · no work packages merged · no implementation · no authorization drafted.**

---

## 1. Responsibility traceability matrix

**WP-3B as allocated:** *"move `start()` from `CoordinatesAdjudication` to the routing act + update `CHAIN_ORIGIN_ALLOWLIST`."*

| # | Responsibility | Allocated to | Implemented where | Accepted? | Ownership changed? |
|---|---|---|---|---|---|
| **1a** | mint at the routing act | **WP-3B** | `CoordinatesContestation::route()` | ⚠️ **delivered under WP-5**, annotated *"WP-5/WP-3B (ARB-approved 2026-07-31)"* in the allowlist — **no acceptance ruling names WP-3B** | ⚠️ **de facto** — performed by WP-5, never formally reassigned |
| **1b** | **remove the mint from `CoordinatesAdjudication`** | **WP-3B** | ⛔ **nowhere** | ⛔ no | ⛔ **no.** **Still uniquely WP-3B's** |
| **2a** | add the routing entry to the allowlist | **WP-3B** | `CorrelationIdMintingTest::CHAIN_ORIGIN_ALLOWLIST` | ⚠️ same as 1a | ⚠️ same as 1a |
| **2b** | **remove the Adjudication entry from the allowlist** | **WP-3B** | ⛔ **nowhere** | ⛔ no | ⛔ **no** |

> **Two of four responsibilities are outstanding, and both are the *removal* side of the same move. Neither has been reallocated by any ruling.**

**Checked and not found:** no ruling, plan or roadmap section assigns 1b or 2b to WP-4B, WP-4D, or any other package. **WP-4D's recorded scope is *"authority-decision intake port + interim administrative adapter"* — it does not mention the mint.**

## 1a. Who governs allocation — and does silence retain ownership?

**Asked because *“no ruling reassigns it”* is a fact about the record, not by itself a statement about ownership.**

| Question | Answer from the record |
|---|---|
| **Which artifact currently allocates the responsibility?** | **The WP-3 plan's split table**, which records the ARB's act of 2026-07-30 dividing WP-3 into 3A and 3B. **It is the only artifact that names 1b and 2b at all.** Note what this means: **the allocation lives in a plan, not in the rulings register** — the same R-62-class recording gap already carried for WP-3A's and WP-4A's authorizations |
| **Which artifact has authority over allocation?** | **The ARB.** The precedent is **R-68**, which subdivided §WP-4 and was typed **Architecture Governance · Approval** — subdivision is an ARB act, exercised in the register |
| **Does the canonical governance model contain a transition for this?** | ⚠️ **No.** **R-63** declares the model governs the work-package lifecycle and enumerates the supported transitions: *opened · authorized · accepted & closed · design decided*. **Reallocating a responsibility between two existing packages is not among them** |

> ### Two readings of the silence, and the evidence does not choose
>
> **Reading 1 — ownership is retained.** No declared transition can move an allocation, so on the record it has not moved: 1b and 2b remain WP-3B's.
>
> **Reading 2 — ownership already moved and was never recorded.** 1a and 2a *were in fact* delivered under WP-5 and annotated *"WP-5/WP-3B"*. **If that was a de facto reallocation, then a reallocation has already happened once without a ruling — and the question is not whether it may happen but whether the first one should be recorded retrospectively.**
>
> **R-63 declared a scope boundary, not a complete taxonomy**, so the absence of a transition is **not proof that reallocation is forbidden** — only that the model does not currently name it. **Inferring a prohibition from an absence would be exactly the over-reading this programme keeps catching.**

## 2. Is the replacement technically dependent on WP-4B's seam?

**The port's signature decides this:**

```php
public static function start(string $mintedCorrelationId): self
public static function fromConsumed(?string $incomingCorrelationId, string $triggeringEventId): self
```

**`fromConsumed()` requires two values that must come from the consumed message.** Tracing whether either is reachable at the call site:

| Carrier | Carries incoming provenance? | Evidence |
|---|---|---|
| `IssueDeterminationCommand` | ⛔ **no** | ten fields — `challengeRef · outcome · legitimacy · reason · issuedByAuthority · jurisdiction · evidenceEnvelopeRef · contestedOutcome · evidenceSet · occurredAt` |
| `AdjudicationProcessManager::receiveRulingDecision()` | ⛔ **no** | six parameters, none of them provenance |
| `AdjudicationProcessState` | ⛔ **no** | no provenance field is stored on the process record |
| `ChallengeRoutedReactionHandler` | ✅ **it consumes the message** — but | it **publishes nothing** and **stores no provenance**; **finding D-1** recorded exactly this: *"observed: no provenance call at all"* |

> ### Conclusion — stated as dependency, not as design
>
> **`issueDetermination()` cannot call `fromConsumed()` today because no carrier between consumption and issuance holds the incoming provenance.** **The gap is a missing path, and it is the same span that the conclude→issue seam would occupy.**
>
> **Softened deliberately, per ARB direction:** **WP-3B's remaining implementation *appears to depend on* the same integration seam that WP-4B introduces. The relationship should be confirmed by the ARB before any work-package boundary is changed.** **This report does not collapse two governance units into one engineering concept.**

**Recorded alongside it:** a provenance carrier could in principle be added *without* the full WP-4B seam. **That option is not evaluated here** — evaluating it would be design, and design is not this commission's scope.

## 3. The two completion conditions — reconciled as far as evidence allows

| | Condition | Introduced by | Date |
|---|---|---|---|
| **Code comment**, `CoordinatesAdjudication` | *"When `ChallengeRouted` consumption lands, this becomes `EventProvenance::fromConsumed`"* | **`823ff129c`** — *"PB-006 6B-1 GREEN: EventProvenance — the constitutional correlation chain (F-PB006-2)"* | **2026-07-09** |
| **Allowlist comment**, `CorrelationIdMintingTest` | *"It becomes a reacting producer when that path is wired (WP-6), at which point this entry is expected to disappear"* | **`52b0f4a54`** — *"chore(hygiene): F-2 executed — CONTEXT.md pruned to a Runtime artifact"* | **2026-07-30** |

### ⚠️ The finding that changes the question

**Neither condition is a governance artifact.** The earlier is a developer annotation written during PB-006's implementation; **the later was introduced in a `chore(hygiene)` commit** whose stated purpose was pruning `CONTEXT.md`. **No ADR and no ruling states either condition.**

> **So this is not one accepted architectural artifact superseding another. It is two code comments disagreeing, neither of them normative.** **Later-in-time does not decide it, because a hygiene commit carries no more authority than an implementation comment.**

**What *is* normative:** **ADR-MP-06** (one mint per conversation; causation = immediate parent) and the **executable allowlist test**, which today permits three entries and **does not require any removal**. **Nothing normative is currently violated.**

**Therefore: ARB clarification is required.** **The underlying question is not "which comment wins" but *"does `DeterminationIssued` belong to the conversation `ChallengeRouted` began, or to a distinct authority-decision conversation?"*** **The allowlist's third-entry note already frames this as the test for a legitimate origin, and roadmap §WP-8's keystone — *one CorrelationId per conversation asserted end-to-end* — is the executable form of the same question.**

## 4. Two decisions, kept apart

**The options below were previously presented as one list. They answer two different questions, and conflating them would let a sequencing constraint masquerade as an ownership argument.**

| Decision | Question | Nature |
|---|---|---|
| **Allocation** | **who owns 1b and 2b?** | governance — ARB, per the R-68 precedent |
| **Sequencing** | **when may that work be implemented?** | **already answered by §2, and answered identically under every allocation** |

> **The sequencing answer does not depend on the allocation answer.** **Implementation cannot precede a provenance carrier — that is a property of the code.** **So sequencing is not an argument for any particular owner, and must not be used as one.**

### 4a. Allocation options — evidence-supported, none recommended

| | Option | Evidence for | Evidence against |
|---|---|---|---|
| **A** | **Remaining work stays in WP-3B** | 1b and 2b are **allocated to WP-3B and reallocated by nothing**; WP-3's keystone *"reacting handlers never mint"* is WP-3's own acceptance criterion | **§WP-3 then stays open until a seam WP-3B does not own exists.** *(A sequencing consequence, not an ownership objection — see the note above)* |
| **B** | **Transfers to WP-4B** | the missing carrier spans exactly the conclude→issue seam; **the change becomes possible only when that path exists** | **no ruling reassigns it**, and WP-4B's scope as recorded is the seam, **not the Adjudication mint's removal**. Transferring is a boundary change, which is a governance act |
| **C** | **Amend the roadmap allocation** | the two comments encode **two different architectural triggers**, and **neither is normative** (§3) — the ambiguity is in the record itself, which is what an amendment repairs | it is the heaviest instrument, and **A or B may settle it without one** |

**No option is recommended.** **The repository supports the *existence* of all three; it does not select among them, because selection turns on the conversation question in §3 — and that is a modelling decision the ARB owns.**

### 4b. Sequencing — one answer, invariant across the allocation options

**Implementation of 1b/2b cannot precede the provenance carrier.** §2 establishes this from signatures and carriers; **it holds identically under A, B and C.**

**And §WP-3 cannot close until 1b and 2b are disposed of — by delivery or by explicit reallocation.**

## 5. The question that governs all of it

**Both decisions above are downstream of one modelling question**, now put to the Board separately: `engineering/verification/commissions/2026-08-02-determination-conversation-decision.md`.

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §"the two slices" · §keystones · `app/Contexts/Shared/Application/Messaging/EventProvenance.php` (`start` · `fromConsumed` signatures) · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` · `.../Process/AdjudicationProcessManager.php` · `.../Process/AdjudicationProcessState.php` · `.../Application/Command/IssueDeterminationCommand.php` · `.../Application/ChallengeRoutedReactionHandler.php` · `tests/Architecture/Messaging/CorrelationIdMintingTest.php` · commits **`823ff129c`** (2026-07-09) and **`52b0f4a54`** (2026-07-30) · **ADR-MP-06 · ADR-T21** · **D-1** · **R-69** · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 keystones · `engineering/verification/reports/2026-08-02-wp3b-closure-verification.md`. **Verification only — nothing decided, nothing merged, nothing implemented.**
