# PBDIGIT-48 — Login routing still reads a deprecated field instead of the constitutional lifecycle

**Type:** Discovery (architecture · **legacy consumer migration**) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06 · **Rewritten 2026-08-06** after verification
**Found by:** diagnosing `PBDIGIT-47` on the live election `namaste 2026`

| | |
|---|---|
| **Status** | 🟡 **DISCOVERY — no implementation decisions belong in this story** |
| **Diagnosis** | **The domain successfully evolved to a constitutional lifecycle. The migration of legacy consumers remained incomplete.** The business rule never changed — only how "open" is computed |
| **Customer impact** | **`PBDIGIT-47` is the first customer-visible consequence** — a voter could not reach a ballot during an open voting period |
| **Completion** | **Complete when the consumer inventory is complete and a migration strategy is approved.** The authority half of the Authority Analysis is already answered. Implementation follows as a separate story |

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

### Consumer inventory — partial; completing it is this story's work

| Consumer | Reads | Note |
|---|---|---|
| `User::getActiveElection():1303` | **`status`** | 🔴 **caused `PBDIGIT-47`** |
| `ElectionPolicy:30,51,63` | **`status`** | 🔴 **authorisation** decisions on a deprecated field — **impact not assessed** |
| `ProcessElectionAutoTransitions:141` | **`status`** | 🔴 an **automatic transition** command driven by the legacy field |
| `ElectionManagementController:182,1297,1302` | `status` | the same controller also reads the engine |
| `ElectionManagementController` (state-machine panel) | **engine / projection** | the officer UI |
| `SetupDemoElection:362` · `SetupPublicDemoElection:295` | `status` | demo provisioning |
| ⬜ background jobs · API endpoints · notifications · exports | **not yet inventoried** | |

⚠️ **`ElectionPolicy` and `ProcessElectionAutoTransitions` were not part of the reported symptom and are potentially more serious than it** — one gates authorisation, the other performs automatic transitions on a field nothing maintains. **Neither has been assessed.**

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

## Acceptance criteria — discovery only

* [x] **All candidate representations identified** — engine/projection · `state` · `status` · `is_active`.
* [ ] **Consumer inventory completed** — background jobs, API, notifications and exports are not yet covered.
* [x] **Authority identified** — already declared in the repository (`DeprecationPolicy` + “SSOT engine”). **Nothing to approve.**
* [ ] **Migration strategy approved** — Option A, Option B, or another. **This is the only decision this story asks for.**
* [ ] **Impact on `ElectionPolicy` and `ProcessElectionAutoTransitions` assessed**, since both read a deprecated field outside the reported symptom.

**Implementation — migrating consumers, removing fields, repairing divergent rows — belongs to a separate story and must not begin here.**

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
