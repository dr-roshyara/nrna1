# PBDIGIT-48 — Login routing still reads a deprecated field instead of the constitutional lifecycle

**Type:** Discovery (architecture · **legacy consumer migration**) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06 · **Rewritten 2026-08-06** after verification
**Found by:** diagnosing `PBDIGIT-47` on the live election `namaste 2026`

| | |
|---|---|
| **Status** | ✅ **DISCOVERY COMPLETE 2026-08-06.** Consumer inventory verified; migration strategy recorded. **Implementation → [`PBDIGIT-58`](PBDIGIT-58-complete-legacy-election-state-migration.md)** |
| **Diagnosis** | **The domain successfully evolved to a constitutional lifecycle. The migration of legacy consumers remained incomplete.** The business rule never changed — only how "open" is computed |
| **Customer impact** | **`PBDIGIT-47` is the first customer-visible consequence** — a voter could not reach a ballot during an open voting period |
| **Completion** | ✅ **Met.** The authority was already declared; the consumer inventory is verified with its limits stated; the migration strategy is recorded. **No further discovery belongs here** |

---

## ⚠️ This story's first framing was wrong, and the correction is the point

The original title was *"Election state has four representations and they disagree"*, and it described **"four competing authorities"**.

**That framing was rejected by the Product Owner, and it does not survive verification.** There are not four competing authorities. There is **one declared authority**, **two formally deprecated fields** and **one manually-backfilled cache** — and the repository states this itself, in code.

> **The business rule never changed.** It has always been: *if exactly one election is currently open for this voter, send the voter to that election.* **What changed is how "open" is computed. The old computation kept its readers.**

**Why the distinction is not cosmetic:** "competing authorities" invites a decision about who *should* own the concept. **That decision was already made and recorded.** What remains is consolidation — a smaller, better-defined problem with a documented target.

### Architectural classification

> **This is not a Single-Source-of-Truth discovery. It is a Legacy Consumer Migration discovery.**

| | SSOT discovery asks | Legacy-migration discovery asks |
|---|---|---|
| Question | **Who owns the business concept?** | **The owner is known — which consumers still read the obsolete representation?** |
| Work | model the domain, declare an owner | **inventory consumers and migrate them** |
| Risk | designing the wrong owner | **missing a consumer** |

**The classification changes the work.** No part of the domain needs redesigning here: the aggregate, the lifecycle enum, the engine and the projection are all in place and correct. **What is needed is a complete consumer inventory and a migration** — which is why this story's completion criterion is a table, not a design.

## Verification — five pieces of evidence, all from the repository

### 1 · The authority is declared, by name

`app/Console/Commands/BackfillElectionState.php` — description: **“Backfill/audit election state consistency with SSOT engine.”** It computes the correct state via `ElectionLifecycleEngineImpl::getState($election)`, compares it with the stored column, counts **divergences**, and offers `--audit-only` to *“report divergences without fixing”*.

### 2 · `status` and `is_active` are FORMALLY DEPRECATED, with the replacement written down

`app/Application/Election/Deprecation/DeprecationPolicy.php`:

```php
public const FIELDS = [
    'status' => [
        'severity'    => 'warning',
        'replacement' => 'ElectionLifecycleEngine::compute($election)->state->value',
    ],
    'is_active' => [
        'severity'    => 'strict',
        'replacement' => 'ElectionLifecycleEngine::compute($election)->isActive()',
    ],
];
```

**The documented replacement for `status` is the lifecycle engine.** Not an inference — a constant.

**`state` is *not* in that list**, consistent with it being a derived cache rather than a legacy field.

### 3 · Enforcement scaffolding exists and is not connected

| Artifact | Intent | Reality |
|---|---|---|
| `ElectionReadModel` (namespace `…\Deprecation`) | *“Deprecation-Aware Access Wrapper … legacy field access is logged/warned/blocked based on deprecation mode … the primary way to read election data in Phase 2+”* | 🔴 **nothing outside the `Deprecation` namespace calls `ElectionReadModel::wrap()`** |
| `DeprecationAccessGuard` | logs/warns/blocks legacy access | reachable only through that unused wrapper |
| `DeprecationPolicy::STRICT_LEVEL` | graduated activation, Levels 0–4 (*“Level 4: full strict — all violations throw”*) | **`= 1`** — legacy reads warn at most |
| `app:backfill-election-state` | detect and repair `state` divergence | **manual — no scheduler exists** (verified: no `schedule->` entries anywhere) |

**A declared target, a per-field replacement, a guard, a graduated activation plan and a divergence detector — and the consumers were never moved.** The guard cannot fire, because consumers bypass the wrapper it lives behind.

### 4 · Three generations, by migration date

| Gen | Introduced | Field | Vocabulary as declared |
|---|---|---|---|
| **1** | **2026-03-05** `create_uuid_elections_table` | `status` enum + `is_active` bool | `planned`, `active`, `completed`, `archived` |
| **2** | **2026-04-26** `add_state_column_to_elections_table` — comment: *“Add explicit state column **to replace computed state**”* | `state` | `draft, administration, nomination, voting, results_pending, results` |
| **3** | 2026-04/05 onward | `ElectionLifecycleState` + engine + projection | 12 members: `draft … ready_for_voting, voting_active, counting …` |

⚠️ **Gen 2's own comment lists a vocabulary that Gen 3 no longer uses** (`voting` vs `voting_active`, `nomination` vs `setup_nomination`). **So `state` was itself superseded — this is a three-step migration, not a two-step one.**

### 5 · Nothing keeps the legacy fields current

| Field | Written by |
|---|---|
| `elections.status` | **only** `SetupDemoElection` and `SetupPublicDemoElection`, at creation. **No lifecycle transition writes it** |
| `elections.state` | **only** `BackfillElectionState` — a manual backfill |
| the projection | never writes back to either |

> **The columns are not “wrong”. They are unmaintained by design, and one consumer still trusts them.**

## The observed divergence, restated as behaviour

`namaste 2026` (`namaste-2026-74d3721c`), one moment, 2026-08-06 ~15:20 UTC:

| Source | Value |
|---|---|
| lifecycle engine / projection | `voting_active` |
| `elections.state` | `setup_nomination` |
| `elections.status` | `planned` |
| `elections.is_active` | `true` |
| date window | open (`14:56 → 16:00 UTC` = `18:00 Berlin`) |

**Stated as behaviour, not as a verdict:** *the management capability behaves as though the lifecycle engine were authoritative — it offers `close_voting` as the only forward action. The login capability behaves as though `status` were authoritative — it filters on `status = 'active'`.* **Both are internally consistent. They consult different generations of the same rule.**

## Authority Analysis — **this story is complete when this table is complete**

**Two questions, and only one of them is open:**

| Question | Status |
|---|---|
| **Who owns election state** (the engineering authority) | ✅ **Already declared** — `ElectionLifecycleEngineImpl`, named “SSOT engine” in `BackfillElectionState`, with per-field replacements in `DeprecationPolicy`. **The Product Owner does not need to approve this** |
| **How the migration is completed** | 🟡 **Requires approval** — see the options below |

| Business concept | Declared authority | Derived / cached | Deprecated | Consumers | Migration strategy |
|---|---|---|---|---|---|
| **Election state** — *“is this election open for voting?”* | ✅ `ElectionLifecycleEngineImpl` / `ElectionLifecycleProjection` | `elections.state` — manual backfill | `elections.status` (`warning`) · `elections.is_active` (`strict`) | **inventory below — incomplete** | ⬜ |

### Consumer inventory — VERIFIED 2026-08-06

**Each row below was confirmed by reading the statement and identifying its query subject.** Rows I previously asserted and could not confirm are listed as corrections underneath.

#### Reads `elections.status` (deprecated, severity `warning`)

| Consumer | Business capability | Risk | Priority |
|---|---|---|---|
| `User::getActiveElection():1303` | **Login routing** | 🔴 **Critical** — caused `PBDIGIT-47`; a voter cannot reach a live ballot | **1** |
| `OrganisationController` ~192, 193, 565 | Organisation dashboard statistics (`active_elections`, `completed_elections`) | 🟡 Medium — **counts are silently wrong**, no error | 2 |
| `OrganisationNewsletterController:46, 115` | Newsletter audience selection (`status != 'deleted'`) | 🟢 Low — excludes a value the lifecycle has no equivalent for | 3 |
| `CommissionDashboardController:28` | Commission dashboard display (`$election->status ?? 'active'`) | 🟢 Low — display only, **but it displays a stale value** | 3 |
| `ElectionReadModel:127` | the deprecation wrapper itself | ✅ **legitimate** — this is the shim | — |
| `SetupDemoElection:167,362` · `SetupPublicDemoElection:295` · `ListAllElections:38` | demo provisioning + dev CLI output | 🟢 Low — dev tooling | 4 |

#### Reads `elections.is_active` (deprecated, severity **`strict`**)

| Consumer | Business capability | Risk | Priority |
|---|---|---|---|
| 🔴 **`ElectionMiddleware:113-114, 127-128`** | **Election resolution for the voting flow** — `Election::where('type','real')->where('is_active', true)` picks the default election for a request | 🔴 **Critical** — **this decides which election a voter is operating in**, and it reads the field marked `strict` | **1** |
| `ElectionManagementController:381` | management listing | 🟡 Medium | 2 |
| `Demo/DemoVoteController:223, 573, 2461` · `Demo/PublicDemoController:239, 451-454` | demo voting + public demo gating | 🟡 Medium | 2 |
| `ElectionReadModel:140` | the deprecation wrapper itself | ✅ **legitimate** | — |

> 🔴 **`ElectionMiddleware` is the consumer this discovery nearly missed, and it is as important as login routing.** `PBDIGIT-47` was reported as "login sends me to the wrong page"; **election *resolution* reads a different deprecated field**, so the same class of divergence can misidentify which election a request belongs to.

### ⚠️ Corrections — two consumers I asserted earlier do NOT read these fields

| Previously claimed | Actually reads |
|---|---|
| `ElectionPolicy:30,51,63` — *"authorisation decisions on a deprecated field"* | **`ElectionOfficer.status`** — officer activation, unrelated |
| `ProcessElectionAutoTransitions:141` — *"automatic transitions driven by the legacy field"* | **`election_memberships.status`** — voter membership, unrelated |

**Both claims came from matching `where('status', 'active')` without checking the query subject.** They were the two items flagged as *"potentially more serious than the reported symptom"* — **and neither exists.** Recorded rather than quietly deleted, because the error is instructive: **a column name is not a consumer.**

*(Also not a consumer: `User::voterElections():307` uses `wherePivot('status','active')` — the pivot, not the election.)*

### 🔴 The limit of this inventory — and why the next step is not more grepping

**This inventory is verified but cannot be proven complete.** `status` and `is_active` are among the most common column names in the codebase — **520 candidate statements** app-wide, the overwhelming majority on other models. Three successive text-based passes each corrected the previous one, and two false claims survived two of them.

> **A column name is not a consumer, and text search cannot tell the difference at this scale.**

**The repository already contains the right instrument.** `DeprecationAccessGuard` + `ElectionReadModel` exist precisely to detect legacy field access, and `DeprecationPolicy::STRICT_LEVEL` graduates from warning to throwing. **Wiring the guard converts the inventory from a static guess into a runtime measurement** — every real access announces itself, including from code paths no grep would associate with elections (queues, notifications, exports, packages).

**So the first migration slice is not "migrate login routing". It is "make the consumers observable."** That is both cheaper and more certain than any inventory I can produce by reading.

## Migration Strategy — phases

**This is a controlled modernisation, not a bug fix.** Recorded so a future reader does not mistake one slice for the whole.

| Phase | Goal | Note |
|---|---|---|
| **1** | **Inventory legacy consumers** | ✅ verified list above · ⬜ **completed definitively by wiring the guard** |
| **2** | Migrate the highest-risk consumers | `User::getActiveElection()` and `ElectionMiddleware` — both Critical |
| **3** | Enable deprecation enforcement | raise `STRICT_LEVEL` one level at a time, per its own documented 12–24 h observation |
| **4** | Migrate remaining readers | dashboards, demo tooling, CLI |
| **5** | Remove the legacy fields | `status`, `is_active`, and reconsider `state` |

**Phases 3–5 are only reachable after 1 and 2.** `STRICT_LEVEL` cannot be raised while consumers bypass the wrapper, because the levels govern access *through* it.

## The solution space — two options, neither chosen here

### Option A · Transitional synchronisation — keep the legacy field in step while consumers migrate

When the engine reports `voting_active`, write `status = 'active'`.

* **For:** every existing reader keeps working unchanged; smallest immediate risk; **buys time to migrate consumers one at a time.**
* **Against:** adds a synchronisation path that can itself fail, and **`DeprecationPolicy` marks `status` for replacement, not maintenance.**
* ⚠️ **Only defensible as a transition, never as a destination.** If adopted it needs an explicit end date and a named successor step — **otherwise "transitional synchronisation" becomes the architecture by default**, which is how this situation arose in the first place.

### Option B · Complete the migration — move the readers

Stop reading `status`; read the engine, via **the replacement the repository already documents**:

```php
ElectionLifecycleEngine::compute($election)->state->value
```

`status` then becomes legacy → deprecated → removable, and the graduated activation levels can advance.

* **For:** the rule is read from the model that owns it; the existing plan is honoured rather than contradicted; nothing to keep in step.
* **Against:** each consumer migrates individually, and **`ElectionPolicy` is authorisation code** — changing it changes who may do what, so it needs its own care and its own tests.

**Engineering states the trade-off and does not choose** (`R-34` — evidence and authority stay separate). *The Product Owner has expressed a preference for Option B; that is recorded as a preference, not as approval.*

## 🔴 Architectural risk

```
Current state:
  Two deprecated fields and one manually-backfilled cache remain readable, and
  the guard that would warn on their use is not wired to any consumer.

Risk:
  Every new consumer that reads elections.status or .is_active enlarges the
  divergence surface and makes the migration more expensive. The graduated
  deprecation plan (Levels 0-4) cannot advance while consumers bypass the
  wrapper, because the levels only govern access THROUGH it.

Recommendation:
  No new consumer may read elections.status or elections.is_active. The
  authority is already declared, so this needs no decision - new code reads
  the lifecycle engine. What awaits approval is only HOW the existing
  consumers are migrated.
```

## Consumer inventory — the artifact that closes this ticket (started 2026-08-06)

**Status: OPEN. One capability migrated of an unknown-but-bounded total.**

### ✅ Migrated

| Consumer | Was | Now | Evidence |
|---|---|---|---|
| `User::getActiveElection()` | `where('status','active')` | `ElectionLifecycle::of($e)->canVote()` | `PBDIGIT-58B`, 9 tests green |
| `User::countActiveElections()` | `where('status','active')` | `ElectionLifecycle::of($e)->canVote()` | same |
| `DashboardResolver` | — | **never read it**; it calls the two above | verified `PBDIGIT-58B` |

**Browser-verified 2026-08-06:** a real election with `status='planned'` and an open voting window routes the voter to `/elections/{slug}` (HTTP 302 → 200), not the organisation homepage. **`PBDIGIT-47` is fixed.** Test election created and removed; dev data left at 4 elections.

### ⏳ Remaining — raw scan, NOT yet classified

**46 candidate sites in 26 files** (subject-aware scan, 2026-08-06). **This is an upper bound, not a consumer count.** The output demonstrably includes other tables (`candidacies.status`, `voter_slugs.status`, `election_memberships.status`, `ElectionOfficer.status`, organisation pivots), doc comments, and one commented-out line.

**Per-site classification is the remaining work**, and each site resolves to exactly one of:

| Class | Meaning | Action |
|---|---|---|
| **Decision** | `if ($election->status === 'active')` — a business decision on the deprecated representation | **migrate** |
| **Display** | `"Status: {$election->status}"` — shown, not decided on | migrate with the read model, or accept until field removal |
| **Not elections** | a different table that happens to have a `status` column | **strike from the inventory** |

> **A column name is not a consumer** — the lesson that produced two withdrawn claims in this ticket. **Classification must name the query subject, never the column.**

**The measuring instrument already exists:** `PBDIGIT-58A`'s `LegacyElectionStateObserver` reports *decisions* at runtime rather than *mentions* in text, and it already found a site three static passes missed. **Closing this inventory means running it over the real journeys, not grepping harder.**

### Clarification of an earlier claim

**"`status` absent entirely" was scoped to the fixtures of `HasActiveElectionTest`** — verified there by grep (only `voter_slugs.status` remained). **It was never a claim about the codebase**, where the scan above shows the field is still widely referenced.

## Acceptance criteria — implementation/consolidation

**Reframed by the Product Owner, 2026-08-06: this is an implementation story, not a discovery story.** The architectural decision is established; discovery language has been retired.

* [ ] **Every consumer of legacy election state has been inventoried** — static inventory complete; measured inventory delivered by `PBDIGIT-58A`.
* [x] **Login routing no longer depends on the legacy `status` field** — `User::getActiveElection()` and `User::countActiveElections()` migrated to `ElectionLifecycle::of($e)->canVote()` (`PBDIGIT-58B`, 2026-08-06).
* [ ] **Each migrated consumer has regression tests** — done for the two above (9 tests green, incl. a named `PBDIGIT-47` regression).
* [ ] **Browser verification confirms the expected behaviour** — **outstanding for `58B`**: no real election is currently inside a voting window, so the defect condition cannot be reproduced live.
* [ ] **No remaining production code depends on the retired representation before it is removed.**

**Discovery findings retained as evidence (not criteria):** all four candidate representations identified · authority already declared in-repo (`DeprecationPolicy` + "SSOT engine") · Option B approved · `ElectionPolicy` and `ProcessElectionAutoTransitions` assessed and **neither reads these fields** (both earlier claims withdrawn above).

**Implementation — migrating consumers, removing fields, repairing divergent rows — belongs to [`PBDIGIT-58`](PBDIGIT-58-complete-legacy-election-state-migration.md) and must not begin here.**

⚠️ **One shortcut that must be refused:** hand-correcting `namaste 2026`'s columns. It would clear the symptom, leave every cause in place, and destroy the only live evidence of the divergence. *(`app:backfill-election-state --audit-only` reports it. Note the command repairs `state` only — so it would **not** fix `PBDIGIT-47`, which reads `status`.)*

## The recurrence — three instances, one shape

| Concern | Shape |
|---|---|
| Votes per IP (`PBDIGIT-45`) | constitutional snapshot owns it; a legacy global is still read; retirement instrumented, never completed |
| Voter eligibility (`PBDIGIT-49`) | two stores, one populated; authority undeclared |
| **Election state (this story)** | **engine owns it; deprecated fields still read; deprecation scaffolded, migration of consumers never completed** |

> **The recurring problem is not any of these concerns. It is that a domain evolves successfully and its legacy consumers are not migrated with it.**

**Recorded as an observation at n=3 within one repository.** A KnowledgeOS **candidate** only if it recurs in another domain (Finance, Membership) — not a promotion (`ES-006.1`).

---

**Traceability:** `PBDIGIT-47` (the consequence) · `namaste-2026-74d3721c` observed 2026-08-06 ~15:20 UTC · `app/Application/Election/Deprecation/DeprecationPolicy.php` (`FIELDS`, `STRICT_LEVEL = 1`) · `app/Application/Election/Deprecation/ElectionReadModel.php` (unused wrapper) · `app/Console/Commands/BackfillElectionState.php` (“SSOT engine”) · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` · `app/Domain/Election/Enum/ElectionLifecycleState.php` · `app/Models/User.php:1301-1312` · `app/Policies/ElectionPolicy.php:30,51,63` · `app/Console/Commands/ProcessElectionAutoTransitions.php:141` · migrations `2026_03_05_000004`, `2026_04_21_181032`, `2026_04_26_004809` · `PBDIGIT-45` · `PBDIGIT-49` · `R-34` · `ES-006.1`
