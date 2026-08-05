# WP-3B — Closure Verification

**Date:** 2026-08-02 · **Prepared by:** Recording Architect
**Commission:** demonstrate from repository evidence that **every** planned responsibility of WP-3B has been satisfied elsewhere, and that disposing of WP-3B loses no planned capability.
**Status:** verification only. **No ruling issued · no code changed.**

---

> # ⛔ VERIFICATION FAILS — **WP-3B must not be disposed of as delivered.**
>
> **WP-3B's scope has two halves. One is done. The other is not, and nothing else owns it.**
>
> **My earlier word was *"appears"*, and the Board was right to refuse it. On inspection the appearance does not hold.**

---

## 1. What WP-3B was allocated

**`.claude/plans/WP-3-challengerouted-published-language.md`, verbatim:**

> **WP-3B — Correlation Origin Relocation**: **move `start()` from `CoordinatesAdjudication`** to the routing act **+ update `CHAIN_ORIGIN_ALLOWLIST`**

**Two responsibilities, and the first is a *move* — which is a removal and an addition, not an addition alone.**

## 2. Responsibility-by-responsibility

| # | Responsibility | Satisfied? | Evidence |
|---|---|---|---|
| **1a** | **mint at the routing act** | ✅ **yes** | `CoordinatesContestation::route()` calls `EventProvenance::start()` and publishes `ChallengeRouted` |
| **1b** | **remove the mint from `CoordinatesAdjudication`** | ⛔ **NO** | `CoordinatesAdjudication::issueDetermination()` **still calls `EventProvenance::start()`** |
| **2** | update `CHAIN_ORIGIN_ALLOWLIST` | ⚠️ **partly** | the routing entry was **added** (*"WP-5/WP-3B (ARB-approved 2026-07-31)"*); **the Adjudication entry was not removed** |

### The code says its own removal condition is now met

**`CoordinatesAdjudication::issueDetermination()`, verbatim:**

```php
// Chain start (raise path not yet implemented): mint the loop's correlation here.
// When ChallengeRouted consumption lands, this becomes EventProvenance::fromConsumed.
$this->outbox->enqueue(
    EventProvenance::start($this->identities->next()),
    ...$determination->pullEvents(),
);
```

**Both of that comment's premises are now false.** The raise path **is** implemented (WP-5). `ChallengeRouted` consumption **has** landed — **WP-4A, accepted by R-69 earlier today.** **By the code's own stated condition, this call should already have become `fromConsumed`.**

## 3. ⚠️ Two artifacts give two different removal conditions, and they disagree

| Artifact | Condition for removing the Adjudication mint | Met? |
|---|---|---|
| **The code comment** (above) | *"when `ChallengeRouted` consumption lands"* | ✅ **yes — R-69, today** |
| **The allowlist comment** (`CorrelationIdMintingTest`) | *"the authority's decision does not yet arrive as a message… It becomes a reacting producer **when that path is wired (WP-6)**, at which point this entry is expected to **disappear**"* | ⛔ **no** — the authority-decision intake is **WP-4D**, unbuilt |

**And the allowlist's own reference is stale on its face: it names *(WP-6)*, which is accepted, while the path it describes belongs to WP-4D.**

> **This is not a contradiction engineering may resolve by choosing the convenient reading.** The two conditions imply different owners and different timing:
>
> - **Code-comment reading** → the removal is **due now**, and it is **WP-3B's unfinished half**.
> - **Allowlist reading** → the removal waits on **WP-4D**, and WP-3B's remaining half **transfers** to it.

## 4. The three questions the Board asked

| Question | Answer |
|---|---|
| **1. Is every responsibility allocated to WP-3B implemented elsewhere?** | ⛔ **No.** Responsibility **1b** — removing the mint from `CoordinatesAdjudication` — **is implemented nowhere** |
| **2. Does anything remain uniquely assigned to WP-3B?** | ⛔ **Yes.** **1b is assigned to WP-3B and to no other package.** WP-4D's recorded scope is *"authority-decision intake port + interim administrative adapter"* — it does not mention the mint |
| **3. Would closing WP-3B orphan acceptance criteria?** | ⛔ **Yes.** WP-3's keystone family includes *"only allowlisted chain-origin producers may mint; **reacting handlers never mint** (`fromConsumed`)"*. **Closing WP-3B as delivered would retire that criterion while the behaviour it governs is still outstanding** |

## 5. What the evidence does and does not establish

**Establishes:** WP-3B is **partially delivered** — its *addition* half landed under WP-5 and was ARB-approved on 2026-07-31; **its *removal* half did not.**

**Does not establish:** which removal condition governs, or whether the outstanding half **stays with WP-3B** or **transfers to WP-4D**. **Both artifacts are accepted; they disagree; reconciling them is a governance act.**

**Does not establish either:** whether the correction loop today runs as **one** conversation or **two**. The allowlist defends *one origin per conversation* and treats these as **two distinct conversations**; **roadmap §WP-8's keystone — *"ONE CorrelationId per conversation asserted end-to-end"* — is precisely the test that would settle it, and it has not been written.** **Recorded, not concluded.**

## 6. Consequence for the critical path

**The cheap win is not available.** WP-3B cannot close on one ruling, and §WP-3 cannot close behind it.

**And the item connects to the path rather than sitting beside it:** `issueDetermination()` becoming `fromConsumed` requires provenance derived from the consumed `ChallengeRouted` — **which is exactly the conclude→issue seam WP-4B builds.** **On the code-comment reading, WP-3B's remaining half and WP-4B's seam are the same wiring problem.**

**Recorded as a relationship, not proposed as a merge. Whether they are one slice or two is the Board's.**

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §"the two slices" · §keystones · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` (the surviving mint and its comment) · `app/Contexts/Contestation/Application/Service/CoordinatesContestation.php` (the relocated mint) · `tests/Architecture/Messaging/CorrelationIdMintingTest.php` (`CHAIN_ORIGIN_ALLOWLIST` and its three annotated entries) · **ADR-MP-06** · **ADR-T21** · **R-67 · R-69** · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 keystones · `engineering/verification/commissions/2026-08-02-programme-authorization-matrix.md` (**corrected by this report**). **Verification only — no ruling issued, no code changed, no work package merged or closed.**
