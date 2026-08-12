# Master Matrix L3 calibration — the business capability map that gives the reading a purpose

**Commission:** Principal Architect · Election Verification, Slice 1 — **calibration before scaling L3 classification**
**Date:** 2026-08-08 · **Status:** investigation only; no production code, test, fixture or configuration changed

> **Why this exists.** The matrix's L2 layer put **692 of 1,376 tests (50%) into `Election — general`** and 392 into `Election Security`. That is structural information, not architectural understanding. **Reading 1,340 remaining tests without a business map would be a documentation exercise, not verification.**

---

## A · Business capability map — **derived, not invented**

**Authoritative source: `ElectionConstitution::RULES`** (`app/Domain/Election/Constitution/ElectionConstitution.php`). **15 actions**, each declaring `allowed_states`, `allowed_roles`, `preconditions`, `target_state`. This *is* the repository's capability model; nothing here is authored by me.

| Action | Roles | Preconditions | → target |
|---|---|---|---|
| `submit_for_approval` | chief · deputy | `timezone_set` | submitted_for_approval |
| `approve` | platform_admin | `capacity_eligibility` | approved |
| `reject` | platform_admin | — | rejected |
| `auto_submit` | **system** | `capacity_eligibility` | approved |
| `begin_setup` | chief · deputy | — | setup_administration |
| `revise_and_resubmit` | chief · deputy | — | submitted_for_approval |
| `complete_administration` | chief · deputy | `has_posts`, `has_voters`, `has_chief` | setup_nomination |
| `complete_nomination` | chief · deputy | `has_approved_candidates` | **setup_nomination** *(self)* |
| `apply_candidacy` | voter · member | — | setup_nomination |
| `open_voting` | **chief only** | `voting_window_defined`, `timezone_set` | voting_active |
| `close_voting` | chief · deputy | — | counting |
| `publish_results` | **chief only** | — | results_published |
| `archive` | chief · deputy | — | archived |
| `suspend` | chief · platform_admin | — | suspended |
| `resume` | chief · platform_admin | — | **suspended** *(self)* |

**Capability flags** (`ElectionLifecycleSnapshot`): `canEdit` · `canVote` · `canManageVoters` · `canPublishResults` · `canEditTimeline` · `isLocked`.

### Decision-ownership map — carried forward from Relationships 4 and 5, not re-derived

| Business question | Owner | Mechanism |
|---|---|---|
| What state is the election in? | **Application** (derivation) | `ElectionLifecycleEngineImpl` — derives from business facts; **never reads `elections.state`** |
| Which action targets which state? | **Domain** | `ElectionConstitution::getTargetStateForAction()` |
| Is this action legal from this state, for this actor? | **Application** (mechanism) over **Domain** (rule content) | `ConstitutionalTransitionGuard` — **skipped entirely for system-triggered transitions** |
| Are this election's data preconditions met? | **Domain** | `Election::validateTransitionRules()` — and it **enforces three rules the constitution never declares** |
| What is legitimate in this state? | **Application** | capability flags on the snapshot |
| May this actor perform the operation? | **Policy/Authorization** | roles above + `ElectionPolicy`/middleware |
| Store the result | **Infrastructure** | `election_state_transitions` (immutable, **no production reader**) + `elections.state` (**cache**) |

## B · Two constitutional observations — one closed, one open

**Both are self-transitions (`target_state` equals the state the action is allowed from), and they are not equally documented.**

| | |
|---|---|
| `resume` | ✅ **deliberate and documented in place** — `'target_state' => 'suspended', // placeholder; side effects clear flags, engine re-derives`. Consistent with Relationship 5: state is derived, so a transition need not name the destination |
| `complete_nomination` | 🟡 **same shape, no such note.** Description is *"Complete candidate approval process"*. **Whether it is the same deliberate pattern or an oversight is NOT ESTABLISHED** — recorded as a question, **not** a defect |

⚠️ **I nearly reported both as constitution defects from my own parser output.** Checking the source showed `resume` carries an explicit comment. **Third instance today of my own tooling producing a plausible false finding** — after the recursive-`find` misread and the two wrong test counters.

## C · Test→capability mapping — **textual evidence, explicitly not intent**

**Method:** count test *files* in the 1,376-test universe whose text references each action name or capability flag. **This is stronger than namespace and weaker than reading the test.** It says a file *mentions* a capability; it does **not** say the test *verifies* it.

| Capability | Files referencing | | Capability flag | Files |
|---|---:|---|---|---:|
| `open_voting` | **20** | | `canVote` | **24** |
| `close_voting` | 12 | | `canEdit` | 13 |
| `submit_for_approval` | 10 | | `canPublishResults` | 10 |
| `begin_setup` · `complete_administration` · `publish_results` · `resume` | 9 each | | `canEditTimeline` | 10 |
| `suspend` · `auto_submit` | 7 each | | `canManageVoters` | 9 |
| `approve` | 6 | | `isLocked` | 6 |
| `reject` · `complete_nomination` | 5 each | | | |
| `archive` | 4 | | | |
| `revise_and_resubmit` | 3 | | | |
| 🔴 **`apply_candidacy`** | **1** | | | |

### The gap this surfaces — and its status

* 🔴 **`apply_candidacy` is referenced by ONE file** — the only action whose roles are **`voter, member`** rather than officers. **It is the sole constitutional action a *participant* performs**, and it has the thinnest textual footprint in the estate.
* **`revise_and_resubmit` (3)** and **`archive` (4)** are also thin.
* **`open_voting` (20) and `canVote` (24) are the most referenced** — consistent with them being the customer-critical path.

> **These are `UNVERIFIED?` signals, not gaps.** A file count cannot establish that an invariant is unverified — only reading can. **Recorded as prioritisation input.**

## D · L3 prioritisation, with the evidence for the priority

| Priority | Basis | Batch |
|---|---|---|
| **P0** | **`apply_candidacy`** — 1 referencing file, the only participant-performed constitutional action | **B1** |
| **P0** | `open_voting` + `canVote` — customer-critical; `PBDIGIT-47` already showed a live customer failure on this path | **B2** |
| **P1** | The 8 timeline-authorization tests — **already established as contributing nothing** (abort before the guard) | **B3** |
| **P1** | `complete_administration` preconditions — Relationship 4 established the constitution is **not** the complete precondition set here | **B4** |
| **P2** | `suspend`/`resume` — `resume`'s self-transition means state is re-derived; **an authorization boundary with an unusual mechanism** | **B5** |
| **P2** | `auto_submit` — the **only `system`-role action**, and Relationship 4c established system transitions **bypass the guard entirely** | **B6** |
| **P3** | `Election Security` (392 rows) | **B7** — largest area, and its 23 failures are already partly classified |
| **P4** | Remainder | unbatched |

**Deliberately not prioritised by test count.** B1 has one file; B7 has 392 rows. **Business significance, not volume.**

## E · Coverage heatmap — what can and cannot be said today

| Capability | Meaningful verification | Weak/structural | Unverified invariant | Unknown |
|---|---|---|---|---|
| Timeline editing | **0 of 8** — all abort pre-guard | — | 🔴 **server-side `canEditTimeline` enforcement** | — |
| Overlay/trust security | **0 of 20** — cannot load or bind | — | 🔴 3 areas | — |
| Deprecation ladder | n/a | — | — | ✅ 4 failing **by design**, readiness intact |
| `apply_candidacy` | **NOT_ESTABLISHED** | — | **`UNVERIFIED?`** — 1 referencing file | ⬜ |
| **Everything else (1,256 passing)** | **NOT_ESTABLISHED** | — | — | 🔴 **whether they pass for the right reason is unexamined** |

**The honest summary: exactly three coverage statements are evidence-backed today, and all three are negative.**

## F · What this calibration does NOT do

* **No business rule invented.** The 15 actions, their roles and preconditions are read from the constitution.
* **No test classified from this mapping.** File-reference counts are prioritisation input only.
* **`BUSINESS RULE NOT SPECIFIED`** is not yet claimed anywhere — no capability was found to lack a rule; some lack *test references*, which is different.
* **Session 2 / IERVP not entered.** No runtime evidence used, and runtime behaviour is not treated as specification.

---

**MASTER MATRIX — BUSINESS CAPABILITY MODEL CALIBRATED. SLICE 1 NOT COMPLETE.**
**L3 remains 37/1,376 (2.7%). Batches B1–B7 are proposed with evidence, not started.**

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php` (15 actions, verified in source) · `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` · Relationship 4 (guard/model overlap; 4c system bypass) · Relationship 5 (derived state; cache; unread transition log) · `PBDIGIT-47` · `PBDIGIT-48` Cluster 6 · matrix `2026-08-08-election-master-matrix.tsv`
