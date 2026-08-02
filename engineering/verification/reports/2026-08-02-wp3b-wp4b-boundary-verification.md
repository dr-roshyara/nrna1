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

## 4. Options — evidence-supported, none recommended

| | Option | Evidence for | Evidence against |
|---|---|---|---|
| **A** | **Remaining work stays in WP-3B** | 1b and 2b are **allocated to WP-3B and reallocated by nothing**; WP-3's keystone *"reacting handlers never mint"* is WP-3's own acceptance criterion | **WP-3B cannot complete alone** — §2 shows no provenance carrier exists, so WP-3B would block on a seam it does not own |
| **B** | **Transfers to WP-4B** | the missing carrier spans exactly the conclude→issue seam; **the change becomes possible only when that path exists** | **no ruling reassigns it**, and WP-4B's scope as recorded is the seam, **not the Adjudication mint's removal**. Transferring is a boundary change, which is a governance act |
| **C** | **Amend the roadmap allocation** | the two comments encode **two different architectural triggers**, and **neither is normative** (§3) — the ambiguity is in the record itself, which is what an amendment repairs | it is the heaviest instrument, and **A or B may settle it without one** |

**No option is recommended.** **The repository supports the *existence* of all three; it does not select among them, because selection turns on the conversation question in §3 — and that is a modelling decision the ARB owns.**

## 5. Sequencing consequence

**Whichever option is chosen, one thing does not change: implementation of 1b/2b cannot precede the provenance carrier.** **§2's dependency is a property of the code, not of the allocation** — it holds under Option A, B and C alike.

**And §WP-3 cannot close until 1b and 2b are disposed of, by delivery or by explicit reallocation.**

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §"the two slices" · §keystones · `app/Contexts/Shared/Application/Messaging/EventProvenance.php` (`start` · `fromConsumed` signatures) · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` · `.../Process/AdjudicationProcessManager.php` · `.../Process/AdjudicationProcessState.php` · `.../Application/Command/IssueDeterminationCommand.php` · `.../Application/ChallengeRoutedReactionHandler.php` · `tests/Architecture/Messaging/CorrelationIdMintingTest.php` · commits **`823ff129c`** (2026-07-09) and **`52b0f4a54`** (2026-07-30) · **ADR-MP-06 · ADR-T21** · **D-1** · **R-69** · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 keystones · `engineering/verification/reports/2026-08-02-wp3b-closure-verification.md`. **Verification only — nothing decided, nothing merged, nothing implemented.**
