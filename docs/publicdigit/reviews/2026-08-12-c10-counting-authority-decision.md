# SD-11 — Who owns counting?

**Commission:** Principal Architect · Election Verification Slice 1 · **targeted authority investigation, C10 only**
**Date:** 2026-08-12 · **Checkpoint:** `2c587182` · **Status:** **no production code, test, fixture, Constitution or ADR changed · nothing implemented**

---

## 1 · Executive answer

> **Counting is not an unowned decision. It is a read-time aggregation over a per-vote record that is written during casting.**
>
> **The business decision "which candidate does this vote count for?" is made at C8 (casting), not at C10 (counting).** That is why no counting service, domain aggregate or ADR exists — **and it may be correct rather than missing.**

**But two things genuinely lack an owner:** **which votes are excluded** (invalid/spoiled ballots), and **whether the aggregation is authoritative or merely displayed.**

## 2 · Business meaning of counting — as the implementation reveals it

**OBSERVED FACT.** `ResultController::index` computes the tally **inline, in raw SQL, in the Interface layer**:

```php
DB::table('results')
  ->select('results.post_id', 'results.candidacy_id', DB::raw('COUNT(*) as vote_count'))
  ->groupBy('results.post_id', 'results.candidacy_id')
```

**And the `results` table is populated at vote-submission time** — one row per vote per post per candidate (root `CLAUDE.md` domain model: `RESULT { vote_id, candidate_id, post_id }`).

> **INTERPRETATION — and it reframes SD-11.** The authoritative act is **recording a vote's candidate selections**, which happens in C8. **C10 aggregates rows that already encode the decision.** Counting is therefore closer to a **projection** than to a business decision.

**A second controller method matters:** `statisticalVerification($postId)` compares `COUNT(DISTINCT vote_id)` in `results` against `Vote::count()` — **an integrity reconciliation**, also in the controller. **Its existence implies the two can disagree**; what should happen if they do is **BUSINESS RULE NOT SPECIFIED**.

## 3 · Close → count → result → publish, kept separate

| Step | Nature | Authority |
|---|---|---|
| `close_voting` | **lifecycle transition** | ✅ Constitutional (chief · deputy) |
| **count** | 🔑 **computation** — SQL aggregation at read time | 🔴 **G · implementation behaviour only** (`ResultController`) |
| **result** | **a persisted per-vote record**, written during casting | **Infrastructure**, decided at C8 |
| `publish_results` | **governance act** | ✅ Constitutional (**chief only**) |

> **The constitution governs entering and leaving the `counting` state, and governs publication. It does not govern the arithmetic — and on this evidence it arguably should not.**

## 4 · Authority / evidence matrix

| Question | Finding | Class |
|---|---|---|
| What is a countable vote? | a `results` row, written at casting | **G** |
| Which votes are excluded? | 🔴 **nothing found** — no spoiled/invalid concept located | **H · NOT SPECIFIED** |
| Who determines validity? | C8's gates (window · code · `has_voted`) | **G/B**, and one-vote is **G** only |
| Is counting deterministic? | a `groupBy` over stored rows **is** deterministic given fixed data | **G** — a property of the mechanism, **not a stated rule** |
| May counting be repeated? | it is a **read** — implicitly yes, every page load | **G** |
| Repeat after publication? | **no rule found** | **H** |
| When does a result become authoritative? | 🔴 **no authoritative-result concept located** | **H** |
| Must counting use anonymous data only? | `results` joins `vote_id`, and **`votes` has no `user_id`** — so anonymity holds **structurally** | ✅ **A** (`ADR-T11`) + Infrastructure |
| Reconciliation mismatch handling | `statisticalVerification` exists; **consequence unspecified** | **H** |

## 5 · The `Determination` concept — checked, and it is NOT the counting authority

**OBSERVED FACT.** `Determination` exists as a **domain concept in the Adjudication context**: `app/Contexts/Adjudication/Domain/Determination/{DeterminationId,DeterminationOutcome,DeterminationState}`, `IssueDeterminationCommand`, and Election consumes it via `DeterminationId` + `DeterminationIssuedReactionHandler`.

> ⚠️ **It concerns contestation outcomes, not vote tallies. Conflating "determination" with "counting result" would import an Adjudication concept into a Counting question — a boundary violation.** **Explicitly not treated as the counting authority.**

**But it is evidence for something:** the platform **does** model *"an authoritative outcome issued by a named authority with a state"* — for contestation. **No equivalent exists for the count.** Whether one is needed is `SD-11`'s substance.

## 6 · Candidate ownership models

| | Model | Evidence for | Evidence against |
|---|---|---|---|
| **A** Constitution | consistency with other election acts | 🔴 the constitution governs **transitions**; counting is arithmetic **within** a state. **No evidence it was ever intended to own algorithms** |
| **B** Domain invariant | exclusion rules and "authoritative result" are domain concepts | no domain artifact exists today |
| **C** Application service | would give the aggregation a home and an owner | none exists; the projection reading may not need one |
| **D** Infrastructure/projection | ✅ **matches what is built**: rows written at casting, aggregated on read | leaves **exclusion** and **authoritative-result** unowned |
| **E** Separate bounded capability | the `Determination` precedent shows the platform can model authoritative outcomes | **speculative** — no demand established |
| **G** **Unspecified, PO decision required** | ✅ **the two genuine gaps are business questions** | — |

**Recommendation — and it is narrow, because the evidence supports only this much:** **D describes what exists and is defensible for the arithmetic.** The decision `SD-11` actually needs is **not** *"where does counting live?"* but:

> **Is the tally an authoritative election result, or a display of one?**

**If authoritative** → it needs an owner, an exclusion rule, and a point at which it becomes final (models B, C or E). **If a display** → D is correct, and the authoritative record is the `results` rows written at casting.

## 7 · Product Owner decisions required

| | Decision |
|---|---|
| **SD-11a** | **Is the computed tally an authoritative election result, or a projection of one?** *(Everything else follows from this)* |
| **SD-11b** | **Do invalid / spoiled / excluded ballots exist as a business concept?** No exclusion rule was found anywhere |
| **SD-11c** | **What must happen if `statisticalVerification` finds a mismatch** between `results` and `votes`? The check exists; the consequence does not |
| **SD-11d** | **May a count be re-run after publication, and does re-running change the authoritative result?** |

## 8 · Consequences

**Constitution — unchanged, and no amendment is indicated by this evidence.** Adding a counting action would place an algorithm in a transition register. **If SD-11a answers "authoritative", the right instrument is a domain concept or an ADR, not a constitutional action.**

**ADRs — unchanged.** If SD-11a answers "authoritative", **one ADR would be indicated** (authoritative result + exclusion + finality). **Not written here.**

**Master Matrix — still blocked**, but the reason has narrowed: C10's verification shape depends on **SD-11a**. If the tally is a projection, C10 is verified by projection tests; if authoritative, it needs invariant tests that **do not currently exist**.

**C8 dependency:** counting depends on C8 for *which candidate a vote records* and for *whether the vote should exist* (window · code · one-vote). **`PBDIGIT-49` entitlement and Session 2's `D-ENT-1` remain out of scope and are not imported.**

## 9 · Self-audit

| Check | ✓ |
|---|---|
| Business meaning established before seeking an owner | ✅ §2 |
| close / count / result / publish kept separate | ✅ §3 |
| Did **not** conclude the Constitution should own counting | ✅ §6 A rejected on evidence; §8 |
| `Determination` checked and **explicitly excluded** as the authority | ✅ §5 — Adjudication concept, not conflated |
| No promotion of implementation or test → business rule | ✅ determinism recorded as **G**, a mechanism property |
| `BUSINESS RULE NOT SPECIFIED` used where true | ✅ exclusion · finality · mismatch |
| Recommendation limited to what evidence supports | ✅ D for arithmetic only; the real question reframed as SD-11a |
| Session 2 not imported | ✅ §8 |
| Nothing implemented; no service, aggregate, ADR or test created | ✅ |

---

**SD-11 — OPEN** *(reframed: the decision is `SD-11a`, not "which layer")*
**C10 authority — IDENTIFIED as `ResultController` (implementation behaviour only); no authoritative-result concept exists**
**Counting business rules — PARTIALLY SPECIFIED** *(anonymity authoritative; exclusion, finality and mismatch unspecified)*
**Constitution — UNCHANGED · ADR — UNCHANGED · Implementation — NOT AUTHORISED · Master Matrix — STILL BLOCKED**

**Remaining Product Owner decisions: `SD-11a` · `SD-11b` · `SD-11c` · `SD-11d`** — plus previously open `SD-9`, `SD-10`, `SD-5`, `SD-7`, `SD-8`, `SD-3`, `BD-1`, `PBDIGIT-49`.

**Traceability:** `app/Http/Controllers/ResultController.php:29-113` · root `CLAUDE.md` domain model (`RESULT { vote_id, candidate_id, post_id }`) · `votes` schema (no `user_id`) · `ADR-T11` · `ElectionConstitution::RULES` (`close_voting`, `publish_results`) · `app/Contexts/Adjudication/Domain/Determination/*` · C8/C10 report

---

# SD-11a — DECISION PACKAGE

**Added 2026-08-12 · investigation only · nothing implemented, no ADR, no Constitution change, no test**

## 10 · The fact that makes SD-11a practical rather than philosophical

**OBSERVED FACT.** `publish_results` runs `applySideEffectsForPublishResults()`, which sets **`results_published = true`** and **`results_published_at`** (`Election.php:1680,1910`). **Searched for a persisted tally snapshot — `published_results`, `result_snapshot`, `final_tally`, `results_snapshot` — across `app/` and `database/migrations`: none exists.**

> 🔴 **Publication records THAT results were published. It does not record WHAT was published.**
>
> The tally is recomputed from `results` rows on **every page load, indefinitely, including after publication.**

**CONSEQUENCE — the sharpest in this report.** If a `results` row were added, removed or altered after publication, **the "published" tally would change silently, and no record of the originally published figures would exist.** Nothing in the estate would detect it: `statisticalVerification` compares `results` against `votes`, so a change consistent across both passes.

**This is stated as a structural property, not an allegation.** No evidence was sought or found that rows *do* change post-publication. **What is established is that the design does not preclude it and would not record it.**

## 11 · Option A — the tally is the authoritative result

**What would have to become true.** *(None of these exists today; each is `H · BUSINESS RULE NOT SPECIFIED`.)*

| Requirement | Today |
|---|---|
| A definition of a **countable** vote independent of "a row exists" | `G` — a row *is* the definition |
| **Exclusion** semantics (invalid · spoiled · withdrawn candidate) | 🔴 **no concept found anywhere** |
| A **finality point** after which the result cannot change | 🔴 none — `results_published_at` marks publication, not immutability |
| **A persisted record of the published figures** | 🔴 **none — §10** |
| **Recount** semantics: permitted? by whom? does it supersede? | 🔴 none |
| A defined **reconciliation** consequence | 🔴 none — `statisticalVerification` detects, decides nothing |
| A named **authority** who declares the result | partially: `publish_results` is **chief only** — but that authorises *publication*, not *declaration* |
| Audit evidence of the count | 🔴 none — and `BD-1` shows the transition log itself has no reader |

**Verification consequence:** C10 would need **invariant** tests — exclusion applied, finality enforced, recount governed, mismatch handled. **None of these tests can exist today, because none of the rules do.**

## 12 · Option B — the tally is a projection

**What would have to be true, and most of it already is.**

| Requirement | Today |
|---|---|
| The **authoritative facts** are the recorded `results` rows | ✅ matches the design — written at casting |
| Those records are trustworthy | ⚠️ **partially** — anonymity is structural (`ADR-T11`, no `user_id`); **but immutability of `results` rows is NOT established** |
| Recount = **re-reading the same facts** | ✅ literally what happens |
| Something *else* declares the official result | 🔴 **`BUSINESS RULE NOT SPECIFIED`** — and this is Option B's real cost |

> **Option B is architecturally coherent and cheaper, but it does not remove the question — it relocates it.** If the tally is only a display, **what makes the election result official?** *"Whatever the page renders"* cannot be the answer for a governance platform.

**Verification consequence:** C10 would be verified by **projection** tests — the aggregation is arithmetically correct, respects tenancy, and preserves anonymity. **A much smaller and cheaper suite than Option A.**

## 13 · Close · Cast · Count · Result · Publish — final separation

| | Nature | Authority | Status |
|---|---|---|---|
| **CLOSE** | lifecycle transition | Constitutional (chief · deputy) | ✅ governed |
| **CAST** | records the voter's candidate decision | C8 — anonymity `A`, window `B`, one-vote `G` | ⚠️ partial |
| **COUNT** | computation over stored rows | `ResultController` | `G` only |
| **RESULT** | 🔴 **not a distinct business concept in this system** | — | **`H`** |
| **PUBLISH** | governance act that flips two flags | Constitutional (**chief only**) | ✅ governed — **but publishes no artifact** |

> **`RESULT` is the missing concept.** The system has a *count* and a *publication*, and **nothing in between that names the outcome.** That absence is what `SD-11a` is really about.

## 14 · Product Owner decision required

> **`SD-11a` — Is the computed tally an authoritative election result, or a projection of recorded facts?**

| | If A | If B |
|---|---|---|
| Instrument | a **domain concept** (+ one ADR) — **not** a constitutional action | none required |
| Must specify | exclusion · finality · recount · reconciliation · published-figure record | **only**: what declares the official result |
| C10 verification | invariant tests, **none of which can exist yet** | projection tests |
| Cost | high | low, **plus one unavoidable specification** |

**Dependent decisions:** `SD-11b` exclusion · `SD-11c` reconciliation consequence · `SD-11d` recount-after-publication. **All three are `H` today and all resolve differently under A and B.**

## 15 · Open business questions

* **What makes an election result official?** — **must be answered under either option.**
* Are `results` rows immutable after publication? **Not established.**
* Should the published figures be recorded as an artifact? *(Under A, necessarily. Under B, still arguably — see §10.)*
* Does `publish_results` **declare** or merely **announce**? The constitution grants it to `chief` alone, which suggests declaration; **the side effects only announce.**

**Determination boundary preserved:** `Adjudication::Determination` is **not** the counting result and is not imported. **Anonymity preserved:** no proposed interpretation identifies a voter's ballot; both options operate on `vote_id` only.

**Master Matrix:** **C10 rows blocked on `SD-11a`. No other rows are blocked by it** — the dependency is C10's verification *shape*, not the matrix as a whole.

---

**SD-11 — OPEN**
**SD-11a — PRODUCT OWNER DECISION REQUIRED**
**IMPLEMENTATION — NOT AUTHORISED**
**MASTER MATRIX — C10 BLOCKED ONLY**
