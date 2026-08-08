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

---

# 🔬 Root cause — ESTABLISHED 2026-08-09 (read-only investigation, no code changed)

**The first framing in this ticket — *"the automatic transition skips the guard"* — is WITHDRAWN. It was close, but wrong, and the truth is more serious.**

**Nothing transitioned this election. Its state is COMPUTED from the clock.**

`ElectionLifecycleEngineImpl::compute()` is a **priority-ordered derivation**, not a state machine (`app/Application/Election/Services/ElectionLifecycleEngineImpl.php:99-107`):

```php
// 5. Voting active — voting window is open NOW
if ($this->isVotingWindowOpenNow($election)) {
    return ElectionLifecycleState::VotingActive;      //  <-- consults ONLY the clock
}

// 6. Ready for voting — setup complete, awaiting voting window
if ($election->administration_completed && $election->nomination_completed) { … }
```

`isVotingWindowOpenNow()` delegates to `ElectionClockService::isVotingOpen($election)` — **time and nothing else** (`:166-169`). **Completeness is only consulted at priority 6, which is unreachable once the window is open.**

### The consequence, stated plainly

> **An election IS `voting_active` for as long as its voting window is open — regardless of nomination completeness, approved candidates, or whether any guarded transition was ever invoked.**

**No actor performed `open_voting`. The console auto-transition command (`ProcessElectionAutoTransitions`) was never run.** The election simply became `voting_active` because `voting_starts_at` passed.

### Why the existing guards did not help

| Guard | What it requires | Why it did not fire |
|---|---|---|
| `complete_nomination` | **`has_approved_candidates`** — evaluated as `candidacies()->where('status','approved')->exists()` (`ConstitutionalTransitionGuard:188-190`) | ✅ It **works** — it refused us, correctly. **But it guards a transition that the clock makes optional** |
| `open_voting` | `voting_window_defined`, `timezone_set` · source states `setup_nomination`\|`ready_for_voting` (`ElectionConstitution:94-100`) | **Never invoked.** Note it carries **no candidate precondition at all**, and our election's `timezone` was `NULL`, so it would have *failed* this guard — yet the election reached voting anyway |

**So this is not "a missing check on one path". It is two competing authorities over one business concept:** a **guarded transition system** that enforces candidate rules, and a **computed temporal projection** that outranks it and consults nothing. The projection wins, because everything downstream — routing, `canVote`, the voter's ballot — reads the projection.

*(The engine already anticipates this: at priority 4 it logs a `"Constitutional anomaly: voting ended without setup completion"` warning — **it detects the condition and proceeds anyway**, `:89-96`.)*

### Answers to the commissioned questions

| | |
|---|---|
| **A · Confirmed invariant** | *Voting must not begin unless the election has at least one valid (approved) candidate.* **"Valid" is already defined by the product** as `candidacies.status = 'approved'` — taken from `has_approved_candidates`, **not invented here** |
| **B · Runtime evidence** | `election-2026-ea560731`: `setup_nomination`, 1 post, **0 candidacies** → became **`voting_active`** when its window opened; voter page reports `lifecycle.canVote = true` |
| **C · Accepting boundary** | `ElectionLifecycleEngineImpl::compute()` **priority 5** |
| **D · Current preconditions** | `complete_nomination`: `has_approved_candidates` ✅ · `open_voting`: `voting_window_defined`, `timezone_set` — **no candidate rule** · **computed path: none** |
| **E · Root cause** | **The lifecycle state is derived from the voting window before any completeness condition is evaluated.** Not a bypass, not a missing branch — a **competing authority** |
| **F · Impact** | Any election with a window is `voting_active` during it. Voters are routed there (`PBDIGIT-47` routing reads this projection) and shown a ballot for an election that may have no candidate |
| **G · Tests that should exist** | An election in `setup_nomination` with **zero approved candidacies** whose voting window is open **must not** compute to `voting_active`. Also: window open + nomination incomplete; window open + only `draft`/`pending` candidacies |
| **H · Existing coverage** | **Not established** — the suite was not run. `UNDETERMINED` |
| **I · Independent of other IERVP findings?** | **Yes** — distinct from `IERVP-1` (facade/snapshot method), `IERVP-2`, `IERVP-3`. **Interacts with `IERVP-6`**: the projection says the *election* can accept votes while the voter is computed ineligible by a different authority |
| **J · Recommended repair location** | The decision belongs at **`ElectionLifecycleEngineImpl::compute()` priority 5** — the temporal check must be conditioned on setup completeness. **NOT IMPLEMENTED, and the precise form is a domain decision** (does an unready election hold, warn, or refuse?) |

---

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
