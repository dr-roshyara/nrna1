# PBDIGIT-257 — Changing the membership mode crashes (HTTP 500) for any organisation that has an election

**Type:** Defect (production code, crash) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management (blocks the verification of `PBDIGIT-02` "Configure the organisation") · **Created:** 2026-10-07
**Found by:** `IERVP-1`, IERVP runtime verification, 2026-08-08 (`docs/publicdigit/reviews/2026-08-09-iervp-election-runtime-verification.md:46`; `.claude/sessions/2026-08-08.md:3036`). Recorded only in `PBDIGIT-64:103` under *"Related findings — recorded, not merged"*, **never ticketed**. Re-verified by `docs/publicdigit/reviews/2026-10-07-membership-mode-switch-blocked-review.md` (finding F-1).
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable), on the PO's instruction (*"create PBDIGIT-213 for the IERVP-1 crash"*).
**ID note:** the PO asked for `PBDIGIT-213`. That ID was already used by another story (`PBDIGIT-213-election-only-voter-admission-v1.md`), and IDs up to `PBDIGIT-256` were taken, so the **next free ID, `PBDIGIT-257`**, is used (`git grep "PBDIGIT-257"` → no occurrence before this file).
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — production-code defect, confirmed by code reading and class reflection** (no runtime request was made for this story; the original `IERVP-1` run on 2026-08-08 observed the HTTP 500). **Not authorised.** |
| **Problem** | `OrganisationSettingsController::validateModeChangeConstraints()` (`app/Http/Controllers/OrganisationSettingsController.php:126-129`) evaluates `ElectionLifecycle::of($election)->isParticipationLocked()` for each election of the organisation. `ElectionLifecycle::of()` returns the **facade** `App\Application\Election\Facades\ElectionLifecycle` (`:59-67`), **which has no `isParticipationLocked()` method and no `__call` forwarding**. The method exists only on `App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot` (`:39`), reachable through the facade's `snapshot()` (`:95`). So the first election in the collection raises `Error: Call to undefined method …ElectionLifecycle::isParticipationLocked()` → **HTTP 500**. |
| **Business consequence** | **No organisation that has ever had an election can change its membership mode**, in either direction, through `PATCH /organisations/{organisation}/settings/membership-mode` (`routes/organisations.php:100`), whatever the elections' states. Example (development data, 2026-10-07): *Namaste Nepal GmbH* (12 elections) cannot move from Election-Only to Full Membership. The settings page (`resources/js/Pages/Organisations/Settings/Index.vue`) shows no explanation. |

## Evidence

- **Reflection (2026-10-07, no database access):**
  - `method_exists(ElectionLifecycle::class, 'isParticipationLocked')` → `false`;
  - `method_exists(ElectionLifecycle::class, '__call')` → `false`;
  - `method_exists(ElectionLifecycleSnapshot::class, 'isParticipationLocked')` → `true`.
- **Introduced** with the guard in `ebf3d1a5a` (2026-05-25, *"Constitutional voter-source strategy snapshot & participation freeze"*). The facade and controller were last changed in `c46b02f84` (2026-05-25), so the defect has existed since then.
- **Observed at runtime** on 2026-08-08 (`IERVP-1`: *"Switch org to election-only → ❌ 500"*).
- **Side effect:** the rate limiter is hit **before** the guard (`:41-47`, 5 attempts per hour), so each crash consumes an attempt.

## Existing-story search

`git grep -i` over `docs/publicdigit/backlog/` for `IERVP-1`, `isParticipationLocked`, `membership mode`, `OrganisationSettingsController`, `updateMembershipMode` → matches only in:
- `PBDIGIT-64` (*"recorded, not merged"*);
- `PBDIGIT-68` G-7 (Full Membership unreachable because of `IERVP-2`/`IERVP-3`, **a different cause**);
- `PBDIGIT-EPIC-01` (`PBDIGIT-02` verification text);
- untracked notes.

**No story owns this crash.** (`PBDIGIT-254` concerns refusals on the **election** settings page, not the organisation page.)

## Scope (when authorised)

**RED:** a feature test in which an organisation with at least one election `PATCH`es the membership mode. Today it gets 500.

**GREEN:** the smallest change that makes the guard evaluate the lock through the snapshot (for example, `ElectionLifecycle::of($election)->snapshot()->isParticipationLocked()`) **without changing the lock rule itself**.

## Out of scope (decisions recorded elsewhere, not decided here)

- **Whether the participation-lock guard should exist, or be narrowed.** Under the immutable per-election `voter_source_strategy` snapshot it may be redundant, and it also locks on `ResultsPublished` (review F-2). **ARB decision.** ⚠️ **Fixing only this crash turns the 500 into a validation refusal** for organisations whose elections are in locked states, *Namaste Nepal GmbH* included: 10 elections `results_published`, 1 `voting_active`. **This story alone will not let that organisation switch.**
- Election archiving being unreachable through the product (review F-3, a proposed correction to `PBDIGIT-24`).
- The organisation settings page rendering no validation errors or flash messages (review F-4, a proposed addition to `PBDIGIT-02`).

## Acceptance criteria

- For an organisation with elections, a membership-mode change returns **no 500**:
  - if no election is participation-locked, the mode is persisted (**business state:** `organisations.uses_full_membership` changed, and the audit `Log::info` written);
  - if one is, the request is refused with the `membership_mode` validation error and the mode is unchanged.
- An organisation with no elections behaves as before.

## Dependencies / required authority

PO authorisation. No business or architecture decision is needed for the crash fix itself. **The ARB decision on the guard (F-2) determines whether the switch becomes possible in practice.**

## Verification method

The RED/GREEN feature test (both branches: locked and unlocked), then a manual mode change in a test organisation with one draft election (succeeds) and one with an election in `results_published` (refused with a message, once F-4 is addressed).

**Traceability:** `OrganisationSettingsController.php:36-76, :123-171` · `ElectionLifecycle.php:59-67, :95` · `ElectionLifecycleSnapshot.php:39` · `routes/organisations.php:100` · commits `ebf3d1a5a`, `c46b02f84` · `IERVP-1` (`2026-08-09-iervp-election-runtime-verification.md:46`, `PBDIGIT-64:103`) · review `2026-10-07-membership-mode-switch-blocked-review.md` F-1.
