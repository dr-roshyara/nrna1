# Research brief — **why implementable-looking surfaces keep terminating at semantic boundaries**
### plus: a live newsletter regression found while verifying `a72528b29`

**Date:** 2026-09-30 · **By:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) · `reviews/` is the established audit/research home
**Type:** research + defect evidence. ⛔ **No production code, no test, no ADR, no governance record changed. No decision made.**

---

## 1 · Current verified state

**Goal A — Election Operating Core.** 3 of 9 application surfaces implemented. Suite: **136 tests / 3,884 assertions / 15 errors + 1 failure**, all accounted for as planned REDs. UC-4 escalated (no `HaltedAtGate` producer). `GREEN-6`/`GREEN-7` held. `BND-1`/`BND-3` deferred. Acts C/D unauthorized. **Every remaining Goal-A path has now been audited and is confirmed blocked on a PO/ARB act** — the blocker inventory is complete for the first time.

**Goal B — pre-election.** 20 of 51 tickets explicitly await a decision. `IERVP-2`/`IERVP-3` live and unticketed.

---

## 2 · New evidence — including three corrections to my own prior claims

| | I claimed | Verified |
|---|---|---|
| **C-a** | `a72528b29` was *"a grab-bag of newsletter work"* | ❌ **Wrong.** It is a **bulk history-exclusion commit** that swept in essentially everything uncommitted at the time — `CONTEXT-ARCHIVE` (5,169 lines), `CONTEXT.md` (6,761), plans, `knowledgeos-ksme/*.py`, agents, scripts — plus the newsletter changes and the UC-4 decision record. The mislabelling is real; the characterisation was not. |
| **C-b** | the newsletter join fix is *"untested"* | ❌ **Wrong.** **Three suites exist**: `NewsletterAudienceSegmentationTest`, `ElectionOfficerNewsletterTest`, `NewsletterCreationTest`. |
| **C-c** | *"the one piece of unblocked engineering"* | ⚠️ **Understated.** It is not a missing-tests task. **The tests exist and are RED.** |

### 🔴 The live finding: the newsletter suites fail, and it is a regression

**Measured now:** `42 tests / 58 assertions / 6 errors / 9 failures`.
**`PBDIGIT-61` closed 2026-08-07 with:** *"all five newsletter suites green (25 passed, 45 assertions)"*.
⇒ **Regression since 2026-08-07**, on a suite that has since grown from 25 to 42 tests. **Not a known pre-existing condition.**

**Two distinct failure families:**

**(i) Every election-audience query returns ZERO recipients** — 4 failures, all of the form *"Failed asserting that 0 matches expected N"*:

| Test | Expected | Got |
|---|---|---|
| `test_dispatch_election_voters_uses_election_membership_table` | 2 | **0** |
| `test_dispatch_election_not_voted_excludes_already_voted` | 1 | **0** |
| `test_dispatch_election_committee_uses_election_officers_table` | 2 | **0** |
| `test_non_member_election_participant_receives_newsletter_without_member_id` | 1 | **0** |

⇒ **The "allow Chief/Deputy to notify voters" feature shipped 2026-09-16 (`1181d66e2`) selects nobody.** The 2026-09-30 change touched exactly these joins (`members.organisation_user_id → organisation_users.id`). Whether it caused or failed to cure the defect is **not established here**.

**(ii) Audit logging violates NOT NULL on dispatch** — 5 errors:
`SQLSTATE[23502]: null value in column "actor_user_id" of relation "newsletter_audit_logs"`. Root cause located: `NewsletterService.php:444` writes `'actor_user_id' => auth()->id()`, and the column is `$table->uuid('actor_user_id')` **NOT NULL** (`2026_04_06_090744_…`). `auth()->id()` is `null` whenever `dispatch()` runs outside an authenticated request.
⚠️ **Production impact NOT established** — it depends on whether any non-HTTP path (command, schedule, queue) calls `dispatch()`. **Bounded, unverified, not investigated further.**

Plus 1 error: `uq_user_election_active` duplicate-key — fixture-shaped. And 4 route/validation-shaped failures (store validation, rate limits).

---

## 3 · Research findings — the recurring structure

Classifying every blocker I could enumerate **with citable evidence**, not impression:

| # | Blocker | What is actually missing |
|---|---|---|
| 1 | `HaltedAtGate` (UC-4) | class **exists**, public ctor; **no authorized producer** |
| 2 | `RecordedOperationalStatus` | port **exists** (Act B); **unwired** — *"NO ADAPTER AND NO CALLER IS AUTHORIZED"* |
| 3 | `GREEN-7` read-side absence | every fact **obtainable**; **meaning of absence undefined** |
| 4 | `BND-1` | `ADR-1` §6(a) gives one reference **two** meanings; **discriminator does not exist** |
| 5 | `BND-3` | overlay **ownership** unassigned |
| 6 | `GREEN-5/6/7` hold | **authorization** withheld |
| 7 | `ADR-1` Rule-8 gate | consumable contract absent → now: exists, unwired |
| 8 | `IERVP-3` | `MemberPolicy` **does not exist** |
| 9 | `IERVP-2` | UI field for `grants_voting_rights` **does not exist** |
| 10 | `PBDIGIT-72` cause 2 | UC-4 handler body **does not exist** |
| 11 | newsletter audience | code exists and **is wrong** |

**The split is not random.** Blockers 1–7 are Goal A; 8–11 are Goal B. And they differ in kind:

> **Goal A's blockers are about obtainability, meaning and authorization. Goal B's are about existence and correctness.**

---

## 4 · Candidate theory

> ### For a surface `s` depending on facts `f`, four independent conditions must hold — and **only one of them is visible in code.**

| Dimension | Holds when | Lives in |
|---|---|---|
| **E** existence | the type/class/contract is present | **the codebase** |
| **O** obtainability | an authorized path yields it at runtime | ports, adapters, wiring |
| **M** meaning | its significance — *including when absent* — is fixed by an authority | domain model, ADRs |
| **A** authorization | the slice may be implemented | governance records |

**Claim:** a surface *appears* implementable when **E** holds, because **E** is the only dimension a developer or an AI reading code can observe. **O**, **M** and **A** are recorded elsewhere. Hence code-reading **systematically over-estimates readiness**, and the over-estimate is discovered only at implementation.

| | |
|---|---|
| **Evidence** | `HaltedAtGate`: E ✅ O ❌ — a public constructor that must not be used · `RecordedOperationalStatus`: E ✅ O ❌, and its own docblock says so · `GREEN-7`: E ✅ O ✅ M ❌ · `BND-1`: M ❌ with no authority to fix it · the hold: A ❌ independent of all three · **my own four correction rounds (`C-1`, `M-1`, `M-3`, `M-7`) were each an E-signal mistaken for O, M or A.** |
| **Counter-evidence** | Blockers 8–11 are plain **E/correctness** failures with no semantic component — so the theory does **not** explain all blockers, and must not be stretched to. The newsletter defect is an ordinary bug. |
| **Confidence** | **Moderate–high for Goal A** (7 independent instances, all citable). **Low as a universal claim** — Goal B contradicts it, which is itself the theory's most useful prediction. |
| **Falsifier** | Find a Goal-A surface that was blocked *only* by **E** — i.e. writing the missing code cleared it with no decision. I found none in 7. One such case would bound the theory to a sub-region. |

### The sharper, predictive corollary

> **The E/O/M/A mix predicts what kind of work unblocks something** — and therefore whether engineering effort is available at all. **Goal A has no available engineering work because none of its blockers is an E-blocker. Goal B has available work because most of its blockers are.** This is why "find a cheap win in Goal A" kept failing: the search space was empty by structure, not by bad luck.

---

## 5 · Formal expression

For surface `s` with dependency set `deps(s)`:

```
Implementable(s)  ⟺  A(s) ∧ ∀f ∈ deps(s): E(f) ∧ O(f) ∧ M(f)
CodeReadingYields(s) = ∀f ∈ deps(s): E(f)
```

⇒ `CodeReadingYields(s)` is **necessary but not sufficient**, and the gap is exactly `A ∧ O ∧ M`.

### Answer to the partial/total question (§5.D) — proved, not assumed

**Proposition.** A producer `find : Key ⇀ T` (partial, realized as `?T`) composed into a consumer `q : Key → T` (total) creates a semantic obligation on absence.

**Proof.** `q` is total: it must yield a value of `T` for every key, including keys where `find` is undefined. The composition therefore requires a function `∅ ↦ T`, or the raising of an alternative outcome. Either is a choice of what absence *signifies*. Hence `M(absence)` is **mandatory**, not optional. ∎

**Corollary.** The obligation vanishes iff the consumer is also partial — `q : Key ⇀ T`, i.e. a nullable return — or iff a precondition restricts `q`'s domain to `dom(find)`. **Both are governance choices, not coding details.** This is exactly the UC-4 Option-A shape (domain restriction by precondition) and exactly what `GREEN-7` lacks: its four returns are total (`GateIntervalState`, `bool`, `ClockReading`, `bool`) over nullable producers.

⇒ **§5.D's proposition is TRUE**, with the stated escape conditions. This is a general result, testable mechanically (§7).

---

## 6 · DDD implications

- **The four dimensions map to different artifacts**, which is why no single document ever shows readiness: **E** → tactical types · **O** → ports/adapters + wiring authorization · **M** → domain model + ADR tables · **A** → governance records.
- **A declared port is an obtainability *promise*, not a capability.** `RecordedOperationalStatus` states *"the domain now states what must be retrievable; it does not make the status persisted, retrievable at runtime, or reachable by anything."* The estate knew this; planning artifacts (including mine) repeatedly read it as a capability.
- **`ADR-1` is command-side by its own wording** (every §6(a) row reads *"the command requires…"*). The read side is unlegislated. That is a **genuine gap in the domain model's coverage**, not an oversight in any one slice — and it will recur for every future query.
- **Where the design forces a strategic decision through tactical implementation:** the nullable-repository/total-query composition. Each new query re-raises `M(absence)`. A one-time read-side absence ruling would remove the obligation for **all** future queries — the highest-leverage single act available.

---

## 7 · Computational opportunities — what would test this, honestly

**ML is not the right instrument for most of this. Static analysis is.** No results are invented below; none of these has been run.

1. **`?T` → `T` flow analysis** over `app/Contexts/Election` — enumerate every `find(): ?X` whose result reaches a total return or an unguarded dereference. **This mechanises §5's proposition and predicts every future `GREEN-7`-class stall before it is hit.** Small, reproducible, falsifiable. *The single most valuable experiment identified.*
2. **E/O/M/A classification of all 51 backlog tickets** from their recorded text. Prediction to test: Goal-A-area tickets skew O/M/A; Goal-B tickets skew E. A confusion matrix against my manual classification of the 11 above would measure inter-rater agreement.
3. **Dependency-concentration measure** — do unresolved dependencies cluster on few concepts? `HaltedAtGate`, `AcceptanceDecision`-absence and lifecycle-phase recur; if concentration is high, few rulings unblock much.

⚠️ Clustering/embedding over ADRs would be decoration here — the corpus is small enough to read, and the distinctions are definitional, not statistical.

---

## 8 · Goal A — exact remaining blockers, not dressed as tasks

| Surface | Blocker | Dimension |
|---|---|---|
| UC-4 `ReportPeriodExpiry` | no authorized `HaltedAtGate` producer (hold already released) | **O** |
| UC-5 `RecordCommitteeConstitution` | hold not released; conditional `Q-UC5` STOP | **A** |
| UQ-1…UQ-4 | hold not released **and** read-side absence unlegislated | **A** + **M** |
| normalization slice | `BND-1` discriminator absent | **M** |
| Acts C/D | unauthorized | **A** |

**Available engineering work in Goal A: none.** Audited, not estimated.

## 9 · Goal B — genuine unblocked work

1. **Newsletter election-audience queries return zero recipients** — RED tests, shipped feature, no governance question. **Real, available, and a correctness risk to a live feature.**
2. **`actor_user_id` NOT NULL on dispatch audit** — root cause located; production reachability unverified.
3. `IERVP-2`/`IERVP-3` — lightly decision-bound (is the members route supported?).
4. Backlog triage of the status-less tickets.

## 10 · Human decision queue — PO/ARB only

1. UC-4 escalation: (a) restoration-bounded slice / (b) authorize a halt source / (c) stay held
2. **`GREEN-7`: two acts** — release the hold, **and** legislate read-side absence
3. `GREEN-6` hold release
4. `BND-1` · then `BND-3`
5. `IERVP-3`: is `POST /organisations/{org}/members` supported?
6. `PBDIGIT-68`'s 11 decisions

## 11 · Engineering queue — already authorized

The newsletter defects (§9.1, §9.2). They sit in a shipped, authorized feature with an existing RED safety net. **A bounded ticket is warranted; none exists.**

## 12 · Research queue

Experiment 1 (§7) — it is cheap, mechanical, and would convert "we keep discovering this late" into a list computed in advance.

## 13 · Recommended next action

**Technical, not governance:** open a `PBDIGIT` ticket for the newsletter regression and fix the zero-recipient audience defect under its existing RED tests. It is the only place where authorized engineering work and a live correctness risk coincide.

⛔ **No governance recommendation.** The UC-4 and `GREEN-7` choices remain yours, unranked.

## 14 · STOP conditions — until human authorization exists

⛔ Do not create/synthesize/wire `HaltedAtGate` · do not start Act C or Act D · do not resolve `BND-1`/`BND-3` · do not reinterpret or amend `ADR-1` · do not invent read-side absence semantics (no `Unknown`, sentinel, default, empty, or exception-as-meaning) · do not implement `GREEN-6`/`GREEN-7` · do not implement the restoration-only UC-4 branch · do not choose among UC-4 (a)/(b)/(c) · do not amend or rewrite `a72528b29`.

> **Methodological principles surfaced here — *"technical reachability ≠ semantic implementability"*, *"a test exclusion is not architectural clearance"*, and §5's partial/total proposition — are recorded as CANDIDATE observations (PKS-shaped), NOT adopted methodology.** Promotion would need a second independent adopter (`ES-006.1`) and is a governance act; the methodology is frozen.

**Traceability:** `a72528b29` · `1181d66e2` · `PBDIGIT-61` (resolution 2026-08-07) · `NewsletterService.php:444` · `2026_04_06_090744_create_newsletter_audit_logs_table.php:18` · `NewsletterAudienceSegmentationTest` · `GREEN-7` feasibility audit (2026-09-30) · UC-4 escalation (2026-09-30) · decision record §§11-13 · `ADR_20260817_2145` §6(a) `:199-202` · `Port/RecordedOperationalStatus.php:56-58` · `AbsentAggregateReferenceRedTest.php:47-50` · `architecture-hold.md:3`
