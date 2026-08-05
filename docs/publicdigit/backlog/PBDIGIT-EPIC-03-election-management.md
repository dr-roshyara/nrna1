# PBDIGIT-EPIC-03 — Election Management

**Journey segment:** `Create Election → Configure Election → (election ready for candidates)`
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00`; **depends on** `PBDIGIT-07` (a staffed committee is required before any election action is authorized)
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

> **This epic is governed by a constitution, unlike EPIC-01/02.** `app/Domain/Election/Constitution/ElectionConstitution.php` declares 15 allowed actions with `allowed_states` · `allowed_roles` · `preconditions`, over 12 states (`ElectionLifecycleState`). Everything below cites it.

---

## PBDIGIT-09 — Create an election and get it approved

| | |
|---|---|
| **Customer goal** | *"I want to hold an election, and I understand the platform must approve it first."* |
| **Business steps** | create election (state `draft`) → submit for approval → platform approves or rejects → (if rejected) revise and resubmit |
| **Route → code** | `elections.submit-for-approval` `routes/election/electionRoutes.php:310` (form) `:314` (POST) → `Election/ElectionManagementController` · engine `app/Application/Election/Services/ElectionLifecycleEngineImpl.php`; platform decisions `routes/platform.php:21` (approve) `:24` (reject) → constitution actions `submit_for_approval` · `auto_submit` · `approve` · `reject` · `revise_and_resubmit` → events `ElectionCreated` (`Election.php:2276`) · `ElectionSubmittedForApproval` · `ElectionApproved` · `ElectionRejected` (dispatch map `Election.php` ~`:1695`) |
| **Business rules evidenced** | **capacity-based approval:** free plan (≤ 40 voters) **auto-approves**; paid plan requires manual platform review (`ElectionConstitution` docblock) · only `super_admin`/`platform_admin` may approve · rejection is recoverable via `revise_and_resubmit` |
| **Verification** | create with ≤40 voters → confirm auto-approval path; create with >40 → confirm it waits for platform; reject → confirm the customer can revise and resubmit rather than being stuck |
| **Known findings** | **`begin_setup` (the transition out of `approved`) has no HTTP route** — it is invoked internally (`Election.php:1675`, `ElectionManagementController.php:214,232`). Verify the customer is **not stranded at `approved`** with no visible next action. This is the clearest candidate for a real customer-blocking defect in the lifecycle |
| **Tests** | 3 files match election-creation naming; constitutional test family (10 files) covers the action/state rules |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-10 — Configure the election (posts and rules)

| | |
|---|---|
| **Customer goal** | *"I want to define which positions are being contested, and how many candidates a voter may choose for each."* |
| **Business steps** | complete administration setup → create posts (national or regional) → set the selection rule per post |
| **Route → code** | `organisations.elections.complete-administration` `routes/organisations.php:328` → `Election/ElectionManagementController` · `Election/PostManagementController` · `Election/ElectionSettingsController` · `PostController` → `app/Models/Post.php` (`is_national_wide:27`, `required_number:29`) → constitution action `complete_administration` → event `AdministrationCompleted` (`Election.php:1360`); state `setup_administration → setup_nomination` |
| **Business rules evidenced** | **national posts** are visible to every voter; **regional posts** are filtered by the voter's `region` · `required_number` per post · `SELECT_ALL_REQUIRED` env switches *exact-N* vs *up-to-N* selection (root `CLAUDE.md`) · a candidate knows only their **post**; the post carries the region context |
| **Verification** | create one national + one regional post → confirm a Bayern voter sees the national post and only the Bayern regional post → confirm `required_number` is enforced at submission (not merely displayed) |
| **Known findings** | `Evidence not found`: whether `complete_administration` has a **precondition that posts exist** — if not, an election can advance to nomination with an empty ballot |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-11 — Take the election through setup to ready-for-voting

| | |
|---|---|
| **Customer goal** | *"I want to know the election is fully prepared and nothing is missing before voting opens."* |
| **Business steps** | administration complete → nomination complete → state `ready_for_voting` |
| **Route → code** | `organisations.elections.complete-nomination` `routes/organisations.php:330` → constitution `complete_nomination` → event `NominationCompleted` (`Election.php:1396`); states `setup_nomination → ready_for_voting`; suspend/resume available throughout `routes/election/electionRoutes.php:297,305` |
| **Business rules evidenced** | the constitution enforces a **strict order** — nomination cannot complete before administration, voting cannot open before `ready_for_voting`; `suspend`/`resume` exist as cross-cutting actions with their own state |
| **Verification** | attempt `complete_nomination` before `complete_administration` → expect constitutional refusal; suspend mid-setup → confirm resume restores the prior state, not a default one |
| **Known findings** | `Evidence not found`: what `suspend` does to an election **already in `voting_active`** (whether voters are locked out mid-journey) — related to `P-4` (the model has no concept of a voter mid-journey) |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-09` | Create & approve election | `IMPLEMENTED — NOT VERIFIED` | **medium-HIGH — `begin_setup` has no route; customer may be stranded at `approved`** |
| `PBDIGIT-10` | Configure posts & rules | `IMPLEMENTED — NOT VERIFIED` | medium — empty-ballot precondition unconfirmed |
| `PBDIGIT-11` | Setup → ready for voting | `IMPLEMENTED — NOT VERIFIED` | low-medium — suspend semantics unconfirmed |

**Architecture debt affecting this epic but NOT blocking a customer:** `L-1` (two rival transition tables still present, one live-instantiable), `L-2` (near-dead `ElectionState` enum with a non-matching vocabulary), `C-1` (no constitution→code reachability test — which is precisely why `begin_setup`'s missing route went unnoticed). See `../reviews/2026-08-05-election-process-review-phase1.md`.

**Nothing is authorized by this epic.**
