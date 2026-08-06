# PBDIGIT-60 — Results publication can be changed without a constitutional transition, and unpublishing is a one-way door

**Type:** Defect (D-2) + governance decision required (D-1) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Found by:** the Product Owner, pointing at a "legacy voting button" on `/elections/namaste-2026-74d3721c/management`
**Capability:** **Results Publication** — one capability, two write paths that bypass the state machine

| | |
|---|---|
| **Status** | 🔴 **D-2 is a defect against an already-decided rule — fixable now** · 🟡 **D-1 requires a governance decision** |
| **Customer impact** | 🔴 **The live real election `namaste 2026` is stuck.** Its results are unpublished, the lifecycle says `results_published`, and **the only remaining constitutional action is `archive`** — the chief cannot publish results again through the UI |
| **Integrity impact** | 🔴 **A deputy can publish election results**, bypassing the chief-only gate, the `counting` precondition, and the pre-publish vote-integrity sweep |
| **Does NOT belong in** | `PBDIGIT-58` (migrates consumers of `status`/`is_active`) · `PBDIGIT-59` (the *voting window* timestamps). Same disease, different concept — see §Relations |

---

## The two defects

| | **D-1 — Unpublish bypasses the constitution** | **D-2 — The timeline form publishes results** |
|---|---|---|
| **Site** | `ElectionManagementController::unpublish()` `:873-889` | `ElectionManagementController::updateTimeline()` `:1459-1461` |
| **What it does** | `$election->update(['results_published' => false])` | `if ($request->filled('results_published_at')) { $validated['results_published'] = true; }` |
| **State machine** | not used | not used |
| **Authorised by** | `publishResults` (chief) | **`manageSettings` (chief *or deputy*)** |
| **Nature** | an action the constitution never defined | a violation of a rule the constitution already decided |
| **Needs** | **a governance decision** | **a fix** |

---

## Verified facts

**F1 — `unpublish()` does not transition.** It writes the boolean directly. Its neighbours all use the state machine:

| Method | Line | Mechanism |
|---|---|---|
| `publish()` | `:841-849` | `transitionTo(Transition::manual(action: 'publish_results', …))` |
| `openVoting()` | `:894` | `transitionTo(… 'open_voting' …)` |
| `closeVoting()` | `:919` | `transitionTo(… 'close_voting' …)` |
| **`unpublish()`** | **`:879`** | **`$election->update(['results_published' => false])`** |

**F2 — `unpublish_results` is not a constitutional action.** It appears in neither `ElectionAction` nor `ElectionConstitution::RULES`. **The UI offers a governance power that was never granted.** The `state` column is also left untouched, so the `ResultsUnpublishedEvent` it fires reports a `previousState` identical to the current one.

**F3 — the engine derives state from the timestamp, which `unpublish()` never clears.** `ElectionLifecycleEngineImpl::getState()` `:79`:

```php
if ($election->results_published_at !== null) {
```

**F4 — measured on the live election, 2026-08-06 (after the Product Owner clicked the button):**

| | |
|---|---|
| `results_published` (written by `unpublish()`) | **`false`** |
| `results_published_at` (what `getState()` reads) | **`2026-08-06 15:29:08`** |
| engine state | **`results_published`** |
| allowed actions | **`archive`** — nothing else |

**F5 — the way back does not exist.** `ElectionConstitution::RULES['publish_results']` requires `allowed_states => ['counting']`. The engine reports `results_published`, and nothing in the codebase clears `results_published_at` except the timeline form (D-2). **Unpublish is a one-way door whose only exit is `archive`.**

**F6 — viewers *are* blocked, so results did not leak.** `ResultController:23` gates on the boolean; measured live: officer → `403`, anonymous → `302`. The harm is not disclosure — it is an **unrecoverable state** plus a lifecycle that reports "published" when nothing is published.

**F7 — D-2 is a role escalation.** Publishing is chief-only by two independent decided rules — `ElectionPolicy::publishResults()` `:58-65` (`role = 'chief'`) and `ElectionConstitution::RULES['publish_results']['allowed_roles'] = ['chief']`. `updateTimeline()` authorises `manageSettings` `:46-53`, which admits **`['chief', 'deputy']`**. Filling one date field on a *timeline* form therefore publishes results — skipping the chief-only gate, the `counting` precondition, and **the pre-publish integrity sweep at `:820-838`** that verifies votes and calls `syncResults()`.

**F8 — the UI is driven by the boolean, its neighbours by the engine.** `Management.vue` gates Publish on `!election.results_published` `:854`, and Unpublish `:872` / the results link `:886` on `election.results_published` — whereas the voting buttons gate on the engine's allowed-action set. In the current stuck state the Publish button renders but is `:disabled="!canPublishResults"` `:857` with a no-op click `:860`, beside a denial message `:867`. **The officer is told publishing is not allowed, while the results sit unpublished.**

---

## The governance question (D-1) — engineering must not answer this

> **May published election results be withdrawn at all?**

The constitution's only edge out of `results_published` is `archive`, i.e. **as decided today, publication is final.** Nobody decided that withdrawal is permitted; a button was built. Two coherent answers, and the choice is the Product Owner's:

| | Answer | Consequence |
|---|---|---|
| **A** | **Withdrawal is legitimate** | `unpublish_results` becomes a constitutional action with explicit `allowed_states`, `allowed_roles`, `preconditions` and a `target_state` (`counting`? a new `results_withdrawn`?), and `unpublish()` routes through `transitionTo()` |
| **B** | **Publication is final** | the button and the route are removed; a mistaken publication is handled by a named governance process, not a toggle |

**Recovery of `namaste 2026` depends on this answer.** A direct database write would restore it today — but that is precisely the non-constitutional write this ticket exists to stop, so it is not being done unilaterally.

---

## Scope

**D-2 (fix — no new governance needed):** a timeline edit must not change publication state. Remove the auto-publish coupling at `:1459-1461`; publication happens only through `publish_results`.

**D-1 (after the decision):** implement answer A or B. Either way, **every write to results-publication state goes through the state machine**, and the boolean stops being an independent authority.

**Out of scope:** migrating `results_published` → derived-from-`results_published_at` (that is the retirement question, and it belongs with `PBDIGIT-48`'s legacy-field retirement once consumers are migrated).

## Acceptance criteria

- [ ] **D-2:** saving the timeline form cannot change `results_published` or `results_published_at`-driven state — covered by a test that saves a timeline as a **deputy** and asserts publication state is unchanged
- [ ] **D-1 decided** by the Product Owner (A or B), recorded in an ADR
- [ ] No write path to results-publication state bypasses `transitionTo()` — asserted by a test, not by inspection
- [ ] The lifecycle state and the `results_published` boolean cannot disagree; a regression test pins the pairing that produced F4
- [ ] `namaste 2026` recovered through whichever path the decision authorises
- [ ] The management UI gates publication buttons on the engine's allowed-action set, as the voting buttons already do

## Relations

- **`PBDIGIT-48`** — same disease, **fourth concept**: two representations of one fact, with consumers split across them (`results_published` boolean vs `results_published_at` timestamp). Retirement of the boolean belongs there; this ticket is about the write paths.
- **`PBDIGIT-59`** — same flag-vs-timestamp family, different concept (the voting window). **Do not merge.**
- **`docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md`** — additional evidence: the authority moved to the timestamp, the consumers did not follow, and a *new* feature was then built against the stale representation. Evidence for the **Legacy Modernization Principle** (Authority → Consumers → Persistence).

## Traceability

Reported by the Product Owner ("there is a legacy voting button"), 2026-08-06 · sites `ElectionManagementController:873-889` · `:1459-1461` · `ElectionLifecycleEngineImpl:79` · `ElectionConstitution::RULES['publish_results']` · `ElectionPolicy:46-65` · `ResultController:23` · `Management.vue:852-894` · live measurement on `namaste-2026-74d3721c`
