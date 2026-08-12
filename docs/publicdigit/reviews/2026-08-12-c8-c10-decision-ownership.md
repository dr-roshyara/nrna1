# C8 Vote Casting · C10 Counting — where does the business decision live?

**Commission:** Principal Architect · Election Verification Slice 1 · **targeted authority investigation, C8 and C10 only**
**Date:** 2026-08-12 · **Checkpoint:** `0bfda8c5` · **Status:** **`SD-1` unchanged · 1,376 baseline preserved · Master Matrix still blocked · no production code, test, fixture, Constitution or ADR changed**

> **The question is not "why is there no constitutional action?" It is "where is the authoritative business rule that makes casting and counting legitimate?"**

---

## 1 · Executive answer

| | C8 · Vote Casting | C10 · Counting |
|---|---|---|
| **Q1 · Inside the Election business boundary?** | ✅ **Yes** — Level 0 journey step *"Vote"* | ✅ **Yes** — Level 0 step *"Count Votes"* |
| **Q2 · Governed by `ElectionConstitution`?** | 🔴 **No** — no action; the constitution governs the *window* (`open_voting`/`close_voting`), not the act | 🔴 **No** — `counting` is a lifecycle **state**, not a rule |
| **Q3 · Governed by another authoritative rule?** | ⚠️ **PARTLY** — anonymity is authoritative (`ADR-T11`, and the schema enforces it); one-vote-per-voter is **implementation only** | 🔴 **NO AUTHORITY FOUND** |
| **Q4 · Verified by the current estate?** | **NOT ESTABLISHED** — not classified | **NOT ESTABLISHED** |

> **The two capabilities differ fundamentally, and collapsing them would have been the error.** C8 has a **partial** authority structure. **C10 has none that could be located at all.**

## 2–7 · C8 · Vote Casting

**Business meaning:** an entitled voter records a ballot that counts once and cannot be traced to them.

### Invariant map — by authority class, not by importance

| Invariant | Where it lives | Authority class |
|---|---|---|
| **No voter↔vote linkage (anonymity)** | `ADR-T11`; **and the schema enforces it — `votes` has 79 columns and NO `user_id`** | ✅ **A · explicitly approved business rule**, with a structural guarantee |
| The voting **window** must be open | `ElectionConstitution` — `open_voting` / `close_voting` | ✅ **B · explicit architectural decision** *(governs the window, not the act)* |
| Voter must be entitled/eligible | 🔴 **CONTESTED** — `PBDIGIT-49`, two homes | **J · mechanism not established** |
| **One vote per voter** | `VoteController:574-584` — an `if` on `$code->has_voted`, **`real` elections only**, returning a validation error | 🔴 **H · implementation behaviour only** |

### 🔴 The C8 finding

**One vote per voter — the invariant most people would name first — exists as a controller conditional and nothing else.**

```php
// ⛔ REAL ELECTIONS: Block voting if already voted
if ($election->type === 'real' && $code && $code->has_voted) { … }
```

**OBSERVED FACTS:** it is enforced at the **Interface** layer · gated on `$election->type === 'real'` · keyed on **`codes.has_voted`**, not on a voter identity (**consistent with anonymity**) · **no constitutional action, no ADR, no domain invariant** was found declaring it.

> **This is exactly why `SD-9` was left `BUSINESS RULE NOT SPECIFIED`. The rule is *implemented*. It is not *specified*.** Promoting `H → A` — "the code does it, therefore the business requires it" — is the inference this programme forbids.

**Ownership:** **Interface/Projection** for the one-vote gate *(observed)* · **Domain + Infrastructure** for anonymity *(ADR + schema)* · **Unknown** for entitlement.

**Specification gaps:** is one-vote-per-voter an invariant? · **why is it `real`-only** — is a demo permitted to double-vote by design? · what makes a *submitted ballot* valid beyond the window and the code?

**Verification gaps:** `VoteAnonymityTest` exists **outside** the `SD-1` universe (SD-4A) · whether the one-vote gate is verified at all: **not established.**

## 8–13 · C10 · Counting

**Business meaning:** votes are aggregated into an outcome that is correct, reproducible, and preserves anonymity.

### 🔴 The C10 finding — no authority could be located

**Searched:** `app/` for `*Count*`, `*Tally*`, `*Result*Service*`; `docs/adr/` and `docs/publicdigit/adr/` for *counting*/*tally*.

| Sought | Found |
|---|---|
| Counting domain service | 🔴 **none** |
| Counting application service / use case | 🔴 **none** |
| Counting ADR | 🔴 **none.** The one ADR matching the term is the lifecycle SSOT ADR, where `counting` appears as a **state name** |
| Counting-named artifact | **only `app/Console/Commands/TestVoteCountingCommand.php`** — a console **test** command |
| Results writers/readers | `ResultController`, `ElectionManagementController`, `SitemapController`, `TestController` — **all Interface layer** |

> **CONCLUSION: C10 has no authoritative business rule that could be located, and no domain or application owner.** What exists is an outcome-shaped table (`results`) and controllers that read and write it.

**Every C10 invariant is therefore `I · BUSINESS RULE NOT SPECIFIED`:** what constitutes a countable vote · which votes are excluded · who decides validity · whether counting is **deterministic** · whether it may run **more than once** · what the authoritative result *is*.

**Ownership:** **Unknown**, with the observable behaviour sitting in **Interface/Projection**.

⚠️ **`counting` is a lifecycle state with no counting rule.** The constitution can move an election *into* `counting` (`close_voting`) and *out* (`publish_results`) **without any authority governing what happens in between.**

## 14 · Relationship to the Constitution

**Of the five permitted conclusions, the evidence supports different ones for each capability:**

| | Conclusion |
|---|---|
| **C8** | **(3) another explicit authority governs it — partially.** Anonymity is genuinely authoritative (ADR + schema). The window is constitutional. **But one-vote-per-voter is unspecified**, so C8 is *partly* (3) and *partly* **(4) business ownership unspecified** |
| **C10** | **(4) business ownership is currently unspecified — Product Owner decision required.** **Not (2):** claiming a *constitutional* gap would presuppose the constitution should own counting, and **no evidence establishes that.** The constitution governs lifecycle transitions; counting is an operation *within* a state |

**Explicitly NOT concluded:** that the Constitution is defective · that C8/C10 should become constitutional actions · that the missing rules are defects rather than **unwritten specifications**.

**The distinction that matters:** *"the constitution has no action for counting"* is a **fact about the constitution's scope**. *"nothing anywhere specifies counting"* is a **fact about the specification estate**. **Only the second is a gap, and it is the one the evidence supports.**

## 15 · Cross-stream evidence

**CROSS-STREAM EVIDENCE EXISTS — NOT YET SPECIFICATION.** Session 2's `D-ENT-1`/Model B work concerns `ElectionMembership` vs organisation `Member`, which bears on **C8's entitlement invariant** (the contested one). **Not imported, and not used to resolve C8.** It may enter only through an explicit governance handoff. **Nothing in this report depends on it.**

## 16 · Product Owner decisions required — only the necessary ones

| | Decision | Why it cannot be derived |
|---|---|---|
| **SD-9** *(reformulated)* | **Is "one vote per voter" a business invariant — and if so, whose?** Today it is a controller `if`, `real`-only. | The code cannot tell us whether it is *required* or merely *current* |
| **SD-10** | **Is a demo election permitted to accept repeat votes?** The gate is explicitly `type === 'real'` | Either answer is a coherent product |
| **SD-11** | **Who owns counting?** Domain, application, constitution, or a new authority — **no authority exists today** | The most consequential of the three: **an election's outcome is produced by code that no specification governs** |
| deferred | C8 entitlement — **blocked on `PBDIGIT-49`**, not on this report | |

**`SD-6` as originally posed should be withdrawn.** *"Are C8/C10 intentionally outside constitutional governance?"* presupposes the constitution as the reference authority. **The evidence says C8 is partly governed elsewhere and C10 is governed nowhere** — which `SD-9`/`SD-10`/`SD-11` capture and `SD-6` does not.

## 17 · Self-audit

| Check | ✓ |
|---|---|
| Asked *where does the decision live*, not *why no constitutional action* | ✅ |
| C8 and C10 answered **separately** — Q1–Q4 never collapsed | ✅ §1 |
| Authority classes applied; **no `H → A` or `G → A` promotion** | ✅ one-vote gate stays **H · implementation behaviour only** |
| Did not declare the Constitution defective | ✅ §14 — (2) explicitly rejected for lack of evidence |
| Anonymity claimed authoritative **with** evidence | ✅ ADR-T11 **and** 79 columns, no `user_id` |
| Session 2 not imported | ✅ §15 |
| Absence of a rule distinguished from absence of an action | ✅ §14 |
| `BUSINESS RULE NOT SPECIFIED` used rather than invented rules | ✅ all C10 invariants |
| No verification classification attempted | ✅ Q4 = NOT ESTABLISHED for both |
| Withdrew a question I had myself posed badly | ✅ `SD-6` |
| No production/test/fixture/Constitution/ADR change | ✅ |

---

**C8/C10 DECISION-OWNERSHIP INVESTIGATION COMPLETE**
**`SD-1` — UNCHANGED · 1,376 BASELINE — PRESERVED · MASTER MATRIX — STILL BLOCKED**

**Traceability:** `app/Http/Controllers/VoteController.php:574-584` · `ADR-T11` · `votes` schema (79 columns, no `user_id`) · `ElectionConstitution::RULES` · `app/Console/Commands/TestVoteCountingCommand.php` · `ResultController` · `ADR_20260807_1500` · SD-4A (155 files; `VoteAnonymityTest` out of universe) · `PBDIGIT-49` · capability boundary report §3 C8/C10
