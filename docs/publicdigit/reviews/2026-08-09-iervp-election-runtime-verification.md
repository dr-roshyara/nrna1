# IERVP — Election runtime verification: consolidated findings

**Programme:** Independent Election Runtime Verification Programme (IERVP) — **a separate programme from `PBDIGIT-48`**, whose evidence `PBDIGIT-48` may later consume. The direction is IERVP → evidence → `PBDIGIT-48`, never reversed.
**Dates:** 2026-08-08 / 2026-08-09 · **Mode:** runtime execution + read-only inspection
**Companion:** [`2026-08-09-election-constitution-voter-eligibility-traceability.md`](2026-08-09-election-constitution-voter-eligibility-traceability.md) (constitutional side, 4 appendices)
**Why this document exists:** the runtime findings were recorded in `.claude/sessions/2026-08-08.md`, which is **session-state, not a review artifact**. This consolidates them into the reviews record. **Nothing here is new evidence** — it is the same evidence, given a durable home.

> **Code changed: none. Tests changed: none. Fixtures, migrations, configuration: none.** Across the whole programme.

---

## 1 · What was actually executed

**An election was built end to end through the real application, twice.** Experiment B is the clean, correctly-sequenced run:

```
organisation (election-only)  ->  Election 2026  ->  chief appointed + ACCEPTED
  ->  submit_for_approval -> approved (auto: <30 voters)  ->  begin_setup
  ->  5 voters imported (CSV)  ->  President post  ->  complete_administration
  ->  candidacy applied -> approved -> status approved  ->  complete_nomination
  ->  ready_for_voting  ->  window opened  ->  voting_active
```

**Every arrow above is `OBSERVED AT RUNTIME`.** The journey **built without a single failure**.

## 2 · What was NOT achieved

**No vote was ever cast.** Therefore: vote persistence · one-vote enforcement in practice · `close_voting` · counting · results — **all `NOT VERIFIED`.** Nothing in this programme claims otherwise.

**Blocked by `PBDIGIT-65`** (below): the enrolled, active voter was refused eligibility.

---

## 3 · Findings raised as tickets

| ID | Finding | Class |
|---|---|---|
| **`PBDIGIT-64`** | **An election can enter voting with no candidates.** `complete_nomination` correctly enforces `has_approved_candidates`; the **computed lifecycle projection** returns `voting_active` from the clock alone, consulting nothing. Two authorities over one concept; the projection wins | **Confirmed defect** (PO-established invariant) |
| **`PBDIGIT-65`** | **A voter's eligibility depends on which page they last visited.** `ElectionMembership` is tenant-scoped; `/elections/{slug}` never sets tenant context; a voter in two organisations is refused **at the enforcement boundary**, not merely in the UI | **Confirmed defect** (proven by A/B) |
| **`PBDIGIT-66`** | **Establish Election-Only in the constitution and the projection.** It works end to end; the constitution never mentions it | **Decision required** |

## 4 · Findings recorded without ticket (deliberately)

| ID | Finding | Why no ticket |
|---|---|---|
| **`IERVP-1`** | An organisation holding **any** election cannot change membership mode — **HTTP 500**. `ElectionLifecycle::of()` returns the *facade*; `isParticipationLocked()` lives on `ElectionLifecycleSnapshot` | **Confirmed defect, ticketable** — not yet raised; recorded here so it is not lost |
| **`IERVP-2`** | `grants_voting_rights` is read by five consumers as the authoritative voting predicate, defaults `false`, and **is written by no controller, request or UI field** | **No obligation established** that it be settable — not called a defect |
| **`IERVP-3`** | `POST /organisations/{org}/members` **can never authorise** — no `MemberPolicy`, no `Gate::before`, `Gate::allows` false for the owner → 403 | Same shape as the capability review's `G-1`; needs a business answer on whether that route is a supported capability |
| **`IERVP-4`** | **Product Owner requirement:** election-period timezone must derive from the **browser's** country, not the user's or chief's. `elections.timezone` was `NULL` throughout | Requirement, not a defect. Relates to `PBDIGIT-50` |

## 5 · The architectural result that matters most

**The two voter-source modes converge completely.**

```
Full-membership import ─┐
                        ├─►  ElectionMembership  ─►  entitlement  ─►  ballot
Election-only import  ──┘
```

`ElectionVotingController::start()` — the **server-side** enforcement boundary — reads **only `ElectionMembership`**. It never consults `voter_source_strategy`, `uses_full_membership`, `Member`, or `User::isEligibleVoter()`. **The modes differ only in how that row is created.**

**And the one-vote invariant is somewhere else entirely.** `votes` has no uniqueness against a voter and cannot — no `user_id`, by anonymity design. So the invariant sits on the **credential**: `codes UNIQUE (election_id, user_id)`, exhausted on use.

> **Entitlement, execution, and the one-vote invariant have three different owners. The product already separates them — anonymity forces it to.**

---

## 6 · Discipline record — what this programme got wrong, and how

**Ten claims were made and withdrawn during this work.** They are listed because the pattern is more useful than any single finding:

| Withdrawn claim | Why it was wrong |
|---|---|
| *"The journey is not broken, just unprotected"* | asserted a runtime property never observed |
| *"A regression would not be caught"* | universal claim from a sample of six assertions |
| *"Repair the test safety net"* (ticket title) | named the remedy before the diagnosis |
| `F-6` classed **`MISSING`** | *missing* is normative; no obligation was established |
| `F-2` classed a **security defect** | the invariant cited was imported from an AI-assistant instruction file and from `PBDIGIT-37`, which concerns **files, not logs** |
| *"Ready for verification"* | a readiness **judgement**, not a status |
| *"Platform-admin approval blocks the journey"* | auto-accept applies under 30 voters |
| *"Zero election memberships after import"* | my query omitted `withoutGlobalScopes()` |
| *"The chief lacks permission"* | my probe had no `Auth::user()`; the real cause was `has_posts` |
| *"Election verification belongs to `PBDIGIT-48`"* | a shared **domain name** was allowed to decide the **workstream** |

**Plus three 500s that were my own request construction, not product defects** (a readonly `UID` shell variable, a missing `confirm_mode_change`, a stale token).

**Every one came from measuring through the wrong lens** — an unauthenticated probe, a scoped query, an assumed authority, an imported invariant. **The runtime evidence corrected each. None reached a ticket as a defect.**

> **The recurring lesson: well-evidenced findings do not produce a well-calibrated summary. Calibration is a separate discipline from evidence-gathering, and it is the one that kept failing.**

---

## 7 · Test data left in the development database

**Nothing was deleted.** Disposal is a Product Owner decision.

```
organisations   pbdigit63-runtime-verification-org, ...-org-1, iervp-election-only-org,
                iervp-experiment-b
elections       election-2026-2a759bea · election-2026-3ce94174 · election-2026-ea560731
                (voting_active, NO candidate — the PBDIGIT-64 specimen)
                election-2026-round-2-c7863e07 · election-2026-65b26848 (Experiment B)
users           iervp.voter1..5@example.test  (+ election memberships, invitations)
other           membership type "IERVP Voting Member"; President posts; 1 approved candidacy
account         roshyara@gmail.com working organisation was CHANGED by this work;
                the evidence suggests it was previously namaste-nepal-gmbh
```

**The credential supplied for verification appears in no file and no commit.**

## 8 · Open questions, in priority order

1. **Can `start()` re-arming a `VoterSlug` also re-arm the code?** The one path that could defeat credential exhaustion. `UNDETERMINED`.
2. **What arms `can_vote_now = true`?** Untraced — so *how a second vote is prevented* is known; *that a first can complete* is not.
3. **`PBDIGIT-66` `D-1`…`D-6`** — the constitutional decisions.
4. **Browser rendering** of any of this — never observed; a stale gitignored `public/hot` points at a Vite dev server that was not running.

## 9 · Authorization boundary

```
Code / tests / fixtures / migrations / config changed   none
Votes cast                                              none
Data deleted                                            none
Test suite                                              NOT RUN  -> all test evidence UNDETERMINED
Browser verification                                    none
Status                                                  STOPPED — awaiting Product Owner
```

---

**Traceability:** `.claude/sessions/2026-08-08.md` (full runtime narrative) · `PBDIGIT-64` · `PBDIGIT-65` · `PBDIGIT-66` · companion constitutional review + appendices 1–4 · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:99-107` · `app/Http/Controllers/ElectionVotingController.php:98-185` · `app/Http/Controllers/VoteController.php:172-176,1977-1981` · `app/Traits/BelongsToTenant.php:44-60` · PostgreSQL unique indexes on `votes`, `codes`, `voter_slugs`, `election_memberships`.
