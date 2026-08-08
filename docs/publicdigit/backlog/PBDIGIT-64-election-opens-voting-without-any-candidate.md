# PBDIGIT-64 — An election can enter voting with no candidates

**Type:** Defect (confirmed by Product Owner) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-08
**Found by:** IERVP runtime verification (independent Election runtime programme) · **Evidence:** `.claude/sessions/2026-08-08.md`
**Status:** `OPEN — not authorised`. Recorded for later work at the Product Owner's instruction.

| | |
|---|---|
| **Business rule** | **"Without a candidate an election must not go into the next phase."** — stated by the Product Owner, 2026-08-08. **This is the obligation that makes the behaviour below a defect rather than an observation.** |
| **Customer impact** | 🔴 An election can be **open for voting with nothing to vote for**. Voters are routed to a ballot that cannot express a choice, and the election consumes its voting window before anyone notices |
| **Confidence** | **High** — reproduced end to end at runtime, and the same rule is *correctly* enforced on the neighbouring transition |

---

## What was observed at runtime

**Two transitions lead out of `setup_nomination`, and only one enforces the rule.**

| Transition | Behaviour | Evidence |
|---|---|---|
| **`complete_nomination`** | ✅ **Correctly refuses**: *"Cannot complete nomination: No candidates approved"* | `POST …/complete-nomination` returned to the page with that error while the only candidacy was `status=draft`. After the candidacy became `approved`, the same call succeeded → `ready_for_voting` |
| **`open_voting` / automatic window transition** | 🔴 **Does not enforce it** | Election `election-2026-ea560731` moved from `setup_nomination` to **`voting_active` with zero candidacies**, when its voting window opened |

**Reproduced concretely.** `Election 2026` (`election-2026-ea560731`, organisation `iervp-election-only-org`) was left in `setup_nomination` with **1 post (President) and 0 candidacies**. Its voting window opened and the election auto-transitioned to **`voting_active`**, whose only remaining action is `close_voting`. A voter logging in was then routed to that election's page, which reports `lifecycle.state = voting_active` and `lifecycle.canVote = true` — **for an election with no candidate to vote for.**

## Why this is a defect and not a configuration mistake

**The rule already exists in the product** — `complete_nomination` enforces exactly it. So the business obligation is not in doubt, and it is not newly invented by this ticket. **The defect is that the obligation is enforced on one path out of the state and not on the other(s).** An election therefore reaches voting with no candidates **without any actor doing anything wrong**: the window simply arrived.

> **A rule that is enforced on one transition and skipped on another is not a rule — it is a suggestion with one polite implementation.**

## Acceptance criteria — stated as business behaviour

* [ ] **An election cannot enter its voting phase unless at least one approved candidate exists** — by *any* route into that phase, including the automatic window transition, not only via `complete_nomination`.
* [ ] **A voting window arriving on an election that is not ready does not force it into voting.** What should happen instead — hold, warn, or extend — **is a Product Owner decision and is not prescribed here.**
* [ ] **A voter is never presented with a ballot that has no candidate for a post.**
* [ ] A regression test encodes **the automatic-transition path specifically**, since that is the path that failed. Encoding only `complete_nomination` would leave the defect live.

## Explicit non-goals

* **Not** changing the `complete_nomination` guard — it already behaves correctly.
* **Not** deciding what the automatic transition should do instead when preconditions are unmet.
* **Not** touching the wider `PBDIGIT-48` election work.

## Related findings from the same run — recorded, not merged into this ticket

* **`IERVP-1`** — an organisation holding any election cannot change membership mode (HTTP 500): `ElectionLifecycle::of()` returns the facade while `isParticipationLocked()` is defined on `ElectionLifecycleSnapshot`. **Confirmed defect, separate cause.**
* **`IERVP-6`** — an **election-only** voter is enrolled (`election_memberships` active) yet `User::isEligibleVoter($organisation)` returns **`false`**, because that method only consults `Member` records, which election-only mode does not create. The voter's election page shows `isEligible: false` / `canVote: false` while the election itself shows `canVote: true`. **Needs its own ticket — it may block voting entirely in election-only mode.**
* **`IERVP-2`** — `grants_voting_rights` is read by five consumers and written by none.
* **`IERVP-3`** — `POST /organisations/{org}/members` can never authorise (no `MemberPolicy`).
* **`IERVP-4`** — Product Owner requirement: election-period timezone must derive from the **browser's** country, not the user's or chief's. Relates to `PBDIGIT-50`.

---

**Traceability:** `app/Http/Controllers/Election/ElectionManagementController.php` (`completeNomination`, `openVoting`, `activate`) · `app/Application/Election/Services/ConstitutionalTransitionGuard.php` (precondition checks) · `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` · elections `election-2026-ea560731` (defect reproduced) and `election-2026-round-2-c7863e07` (correct path, reached `ready_for_voting` only after an approved candidate existed) · Product Owner statement 2026-08-08.
