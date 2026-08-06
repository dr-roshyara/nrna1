# PBDIGIT-60 — Result Visibility never became a first-class capability during the constitutional migration

**Type:** Domain model correction + defect · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06 · **Revised:** 2026-08-06 (rev 2)
**Found by:** the Product Owner, pointing at a "legacy voting button" on `/elections/namaste-2026-74d3721c/management`
**Domain model supplied by:** the Product Owner — **it could not be derived from the repository**, and rev 1 of this ticket was wrong without it

| | |
|---|---|
| **Status** | 🔴 **D-2 is a defect against an already-decided rule — fixable now** · 🟢 **D-1's bounded-context question is ANSWERED; the design that follows is open** |
| **Diagnosis** | **Publication migrated into the constitutional lifecycle. Visibility never received its own capability — so the legacy boolean continued to be used as a surrogate for the missing one.** The button is labelled "Unpublish Results"; what the business meant was *hide the results during a dispute* |
| **Integrity impact** | 🔴 **A deputy can write `results_published_at` through the timeline form**, fabricating the constitutional publication fact with no transition — **and because the results page gates on the boolean alone, this can expose results for an election still in `voting_active`.** See invariant **V-1** |
| **Operational impact** | 🟡 **`namaste 2026`'s results are hidden with no supported way to show them again.** The lifecycle is *correct*; the missing thing is the capability |

---

## The domain model (Product Owner ruling, 2026-08-06)

> **What was treated as one capability is two: a constitutional fact and an operational control.**

| Capability | Meaning | Mutability | The verbs |
|---|---|---|---|
| **Election Lifecycle** | *This election reached `results_published`.* A constitutional fact. | **Immutable once true.** `counting → results_published → archived`. You cannot pretend publication never happened. | `publish_results` — **once** |
| **Result Visibility** | *The results are currently reachable by members/public.* An operational control. | **Toggleable**, repeatedly. | **`hide results` / `show results`** |

**Language rule for this ticket:** *publication* refers only to the constitutional fact (`results_published_at`, the `publish_results` transition). The control being redesigned is **hide results / show results** — never "unpublish", never "hide publication". Publication already happened and does not change.

The legacy behaviour was never "undo publication":

```
Results published → dispute → hide the results → dispute resolved → show them again
                              (the election stays published throughout)
```

### The dependency — Visibility exists only downstream of Publication

```
Election Lifecycle          draft → … → counting → RESULTS PUBLISHED → archived
                                                          │
                                                          │ prerequisite
                                                          ▼
Result Visibility                              visible ⇄ hidden
```

> **Invariant V-1: Result Visibility is defined only when the lifecycle has reached `results_published`.** Before that the capability does not exist — there is nothing to show or hide. **`draft + visible results` is not a permitted state; it is an impossible one.**

**V-1 is currently unenforced, and the violation is reachable — this is a finding, not a hypothetical:**

* `ResultController:23` gates the results page on the **boolean alone** — `if (! $election->results_published)` — with **no lifecycle check**.
* `validatePhaseUpdatePermissions()` `:1478-1503` guards the `administration`, `nomination` and `voting` date groups. **It has no guard for `results_published_at`.**

**So a deputy filling `results_published_at` on a `draft` election makes a public results page render for an election that never reached publication — and on a `voting_active` election, it exposes results while voting is still open.** That is the strongest reason D-2 is a defect rather than an untidiness, and it is why V-1 must be enforced at the visibility gate, not merely documented.

## The conflation, proven

`Election::applySideEffectsForPublishResults()` `:1905-1911` writes **both fields in a single statement**:

```php
->update([
    'results_published'    => true,          // ← visibility
    'results_published_at' => $currentTime,  // ← the constitutional fact
```

**Because publication set both at once, nothing ever revealed that they were two concepts.** The lifecycle then reads only the timestamp (`ElectionLifecycleEngineImpl::getState()` `:79`), and the public page reads only the boolean (`ResultController:23`). So the field pair already implements the correct model **by accident** — the boolean *is* the visibility flag, and it is misnamed.

---

## The two defects

| | **D-1 — Result Visibility never became a capability** | **D-2 — The timeline form writes the constitutional publication fact** |
|---|---|---|
| **Site** | `ElectionManagementController::unpublish()` `:873-889` | `ElectionManagementController::updateTimeline()` `:1418, :1452, :1459-1463` |
| **What it does** | `$election->update(['results_published' => false])` under the label "Unpublish Results" | accepts `results_published_at` as editable input, and sets `results_published = true` when it is filled |
| **Why it is wrong** | it presents a **visibility** change as a **lifecycle** action, so the capability is unnamed, unauthorised as itself, unaudited, and **one-way** | it lets a **deputy** write the constitutional publication fact, bypassing the chief-only gate, the `counting` precondition, and the integrity sweep `:820-838` |
| **Authorised by** | `publishResults` (chief) | **`manageSettings` (chief *or deputy*)** |
| **Needs** | **design** — the model is now decided | **a fix** — the rule was already decided |

**D-2 is the more severe of the two, and it is independent of the visibility redesign.** Publishing is chief-only by two decided rules (`ElectionPolicy:58-65`; `ElectionConstitution::RULES['publish_results']['allowed_roles'] = ['chief']`). `results_published_at` is the immutable constitutional fact — **it must be writable only by the `publish_results` transition, and never editable from a settings form.**

**Verified, not assumed:** `Election` has `$guarded = []` and 72 fillable attributes, and **`results_published`, `results_published_at`, `state` and `status` are all mass-assignable** — so the timeline form's write does reach the database.

> **Adjacent observation, deliberately not a new ticket** (discovery is frozen): **the constitutional `state` column is itself mass-assignable.** Any `update()` reached by validated input could write it. Nothing observed does — this is an unexercised gap, not a defect — but it is the protection that would have made both defects here impossible. It belongs with `PBDIGIT-48`'s retirement work, where the fields' writability is already the subject.

**Note the irony:** the timeline form is currently the *only* working way to restore visibility on `namaste 2026`. **The escalation defect is also the accidental recovery path** — which is why D-2 must not be fixed by itself without D-1's control existing, or the election's results become unreachable by any supported route.

---

## ⚠️ Withdrawn from rev 1 — two claims were wrong

Rev 1 read the boolean and the timestamp as competing authorities. Under the correct model they are **two different facts, both true**:

| Rev 1 claim | Corrected |
|---|---|
| "the engine reports `results_published` while the boolean says otherwise — a divergence" | **Not a divergence.** *The election is published* (timestamp) and *its results are hidden* (boolean) are simultaneously true. No inconsistency exists. |
| "unpublish is a one-way door whose only exit is `archive` — the election is stuck" | **The lifecycle is correct.** Publication *should* be irreversible, and `archive` *should* be the only exit. Nothing is stuck at the lifecycle level. |
| "the chief cannot publish results again" | **The chief should not be able to.** They were never unpublished. The real gap: **no control exists to make the results visible again**, and the UI offers a disabled "Publish Results" button instead — mislabelled *and* correctly refused. |

**What survives rev 1 unchanged:** the mechanism of `unpublish()` `:879`; that `unpublish_results` exists in neither `ElectionAction` nor `ElectionConstitution::RULES`; that viewers are correctly blocked (officer `403`, anonymous `302`); the D-2 escalation; and the UI gating (`Management.vue:854/872/886` gate on the boolean while the voting buttons gate on the engine's action set).

---

## Scope

**D-2 (fix now):** `results_published_at` becomes non-editable input — removed from `updateTimeline()`'s validation, `$validated`, and the auto-publish coupling at `:1459-1461`. **The constitutional fact changes only via the `publish_results` transition.**

**D-1 (design, then implement):** make **Result Visibility** a first-class capability — the controls **`hide results` / `show results`**, its own authorisation (A-1), its own audit record (A-2), and **V-1 enforced at the visibility gate** so results cannot be reachable for an election that never published. The lifecycle is not touched.

**Eventual (with `PBDIGIT-48`'s retirement, not here):** rename `results_published` → `results_visible` / `results_publicly_visible`, so the field says what it means. Renaming before consumers are inventoried would repeat the mistake this ticket documents.

## No open domain questions remain

**The domain model is settled: two capabilities, one dependency (V-1), verbs `hide results` / `show results`.** What is left is not modelling:

| | Kind | Item |
|---|---|---|
| **A-1** | **Authorization policy** — a rule to set, not a model to discover | **who may hide and show results**: chief · chief + deputy · a dispute committee. Hiding is operational and publishing is constitutional, so **they need not share an authority** |
| **A-2** | **Design detail, follows from the dispute use case** | whether hiding carries a **reason**, and therefore its own small audit record (`hidden_at`, `hidden_by`, `reason`) — which is what `ResultsUnpublishedEvent` was already groping toward |

**Neither blocks implementation of D-2, and neither is a bounded-context question.** A-1 needs one sentence from the Product Owner whenever the visibility control is built.

## Acceptance criteria

- [ ] **D-2:** a **deputy** saving the timeline form cannot change `results_published` or `results_published_at` — asserted by test
- [ ] `results_published_at` has exactly one writer: the `publish_results` transition — asserted by test, not inspection
- [ ] **V-1 enforced:** the results page is unreachable whenever the lifecycle has not reached `results_published`, **regardless of the boolean** — asserted by a test that sets `results_published = true` on a `draft` election and on a `voting_active` one
- [ ] **D-1:** Result Visibility exists as a named capability (`hide results` / `show results`) with its own authorisation, and **hiding then showing results leaves the lifecycle state untouched** — asserted by test
- [ ] `namaste 2026`'s results shown again through the new control (**not** through the timeline form, and not by a database write)
- [ ] The management UI labels the control **hide/show results**, and stops offering "Publish Results" for an already-published election

## Relations

- **`PBDIGIT-48`** — owns retirement and the eventual rename. **This ticket adds the reason the rename matters:** the legacy name encoded the conflation.
- **`PBDIGIT-59`** — same shape once more: a *scheduled* fact and an *actual* fact sharing a name. `end_date`/`voting_ends_at` there, publication/visibility here.
- **`docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md`** — this instance contributes the sharper heuristic: **a migration can inherit a conflation.** Before deciding where a legacy field's authority moved, establish **how many business concepts it encodes** — otherwise both concepts migrate to one authority and the second becomes unimplementable.

## Traceability

Reported by the Product Owner, 2026-08-06 · domain model supplied by the Product Owner (rev 2), correcting rev 1 · sites `ElectionManagementController:873-889`, `:1418`, `:1452`, `:1459-1463` · `Election.php:1905-1911` · `ElectionLifecycleEngineImpl:79` · `ElectionPolicy:46-65` · `ResultController:23` · `Management.vue:852-894` · live measurement on `namaste-2026-74d3721c`
