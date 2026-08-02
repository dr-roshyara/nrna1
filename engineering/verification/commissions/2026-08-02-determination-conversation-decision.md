# ARB Modelling Decision — one question

**Date:** 2026-08-02 · **Prepared by:** Principal DDD Architect
**Status:** **CONVENED — one question, no recommendation, no ruling issued here.**

---

> # THE QUESTION
>
> ## Does `DeterminationIssued` belong to the same business conversation that `ChallengeRouted` began, or does it begin a distinct authority-decision conversation?

**Nothing else is asked. This package exists to keep it that way.**

---

## 1. Why this one question is worth convening for

**It is upstream of at least four open items, and each of them is currently blocked on a different symptom of it:**

| Open item | What it is waiting for |
|---|---|
| **WP-3B's remaining half** (1b · 2b) | whether the Adjudication mint is a **defect to remove** or a **legitimate second origin** |
| **WP-3B / WP-4B allocation** | whether the removal is coupled to the conclude→issue seam at all |
| **WP-4B's seam** | whether it must **carry provenance** end-to-end, or merely request issuance |
| **WP-8's keystone** — *one CorrelationId per conversation, asserted end-to-end* | **what "one conversation" means for this loop** — the assertion cannot be written until this is answered |

> **Answer it once and four things resolve consistently. Leave it open and each is settled separately, by whoever touches it first.**

## 2. What is normative today

| Artifact | What it says |
|---|---|
| **ADR-MP-06** | **one mint per conversation** · propagate unchanged · **causation = immediate parent** · correlation = the conversation, not the event |
| **`CorrelationIdMintingTest`** (executable) | three allowlisted origins, each annotated as **a distinct conversation**: the correction loop · the authority decision · the failure-to-conclude |
| **Roadmap §WP-8** | *"IT-style full-loop suites… **ONE CorrelationId per conversation asserted end-to-end**"* |

**Nothing normative is violated today.** **The two comments that appear to conflict are not governance artifacts** — one is a PB-006 implementation annotation, the other entered in a `chore(hygiene)` commit. *(Evidence: `2026-08-02-wp3b-wp4b-boundary-verification.md` §3.)*

## 3. The two answers and what each entails

### Answer A — **one conversation**: `DeterminationIssued` continues what `ChallengeRouted` began

| Consequence | Detail |
|---|---|
| Provenance | **propagated** — `issueDetermination()` becomes `fromConsumed()` |
| The Adjudication mint | **a second origin for one conversation — to be removed** |
| The allowlist | drops to **two** entries; **WP-3B's 2b becomes due** |
| WP-3B's remaining half | **coupled to the seam** — it needs a provenance carrier that does not exist (boundary verification §2) |
| WP-4B | must carry provenance from consumption through conclusion to issuance |
| WP-8's keystone | asserts **one** id across raise → route → adjudicate → issue → correct → resolve |
| Supporting evidence already in the repository | the `CoordinatesAdjudication` comment — *"when `ChallengeRouted` consumption lands, this becomes `fromConsumed`"* — **and both its premises are now true** |

### Answer B — **two conversations**: the authority's decision begins its own

| Consequence | Detail |
|---|---|
| Provenance | **not propagated across the decision** — the mint is correct where it is |
| The Adjudication mint | **legitimate and permanent**, not interim |
| The allowlist | **stays at three**; **WP-3B's 1b and 2b are void, not outstanding** — and WP-3B may then be complete |
| WP-4B | requests issuance **without** threading provenance |
| WP-8's keystone | asserts **one id per conversation**, with the loop spanning **more than one** |
| Supporting evidence already in the repository | the allowlist's own framing — *"two originators serving two **distinct conversations**… a deliberate intermediate state"* — and the third entry (WP-6's expiry origin) **already accepted on exactly this reasoning: a clock consumed no message, so it begins a conversation** |

> **Both answers have repository support. That is precisely why it needs deciding rather than inferring.**

## 4. What the decision is *not*

**It does not authorize anything** · **it does not allocate WP-3B's remaining half** (that is a separate governance act, boundary verification §4a) · **it does not schedule implementation** (sequencing is already fixed by the code and is invariant across both answers) · **and it does not require a new ADR unless the Board decides ADR-MP-06 needs a successor annotation.**

## 5. Decision

| | |
|---|---|
| **Authority** | ARB |
| **Category** | Architecture Governance · **modelling** |
| **Decision** | ⬜ **A — one conversation** / ⬜ **B — two conversations** / ⏸️ **DEFER** |

**Architecture recommends neither.** **The programme's own precedent supports each: Answer A by the code's stated intent, Answer B by the accepted reasoning behind the third allowlist entry.**

---

**Traceability:** **ADR-MP-06** · **ADR-T21** · **ADR-T8** (choreography) · `tests/Architecture/Messaging/CorrelationIdMintingTest.php` (the three annotated origins) · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` · `app/Contexts/Contestation/Application/Service/CoordinatesContestation.php` · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 keystones · `engineering/verification/reports/2026-08-02-wp3b-wp4b-boundary-verification.md` · `…-wp3b-closure-verification.md` · **R-63** (lifecycle scope) · **R-68** (subdivision precedent) · **D-1**. **One question · no recommendation · no ruling · no implementation.**
