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

---

# Appendix A — Part B: how a voting credential is initially armed (read-only, 2026-08-09)

**The single question everything downstream hung on:** *how does an entitled voter first get `codes.can_vote_now = true`?* **Answered. No code changed, no vote cast, no runtime data created.**

## The answer

**`CodeController::markCodeAsVerified()` (`:910-920`) is the sole authoritative arming write:**

```php
$code->update([
    'can_vote_now'                       => true,
    'is_code_to_open_voting_form_usable' => two_codes_system ? false : true,
    'code_to_open_voting_form_used_at'   => now(),
    'client_ip'                          => $this->clientIP,
]);
```

**It is reached only by successfully verifying the emailed code**, and the caller guards it twice, in this order (`:288-300`):

1. **already-voted check** → *"You have already voted in this election. Each voter can only vote once."*
2. **already-verified check** → `if ($code->can_vote_now === true) return handleAlreadyVerified(...)`
3. then, and only then, `verifyCode()` → `markCodeAsVerified()`

## Writer/reader matrix — `can_vote_now` on each table

| Table | Writers (production, non-demo) | Read by |
|---|---|---|
| **`codes`** | **`CodeController::markCodeAsVerified():916`** — *the arming event* · `CodeController:167` — regeneration, **preserves** the prior value and is gated on `!$code->has_voted` · `VoteController::markUserAsVoted():1978` — sets **false** (exhaustion) | **`VoteController:172-176`** — the credential guard · `ElectionProcessController` / `EndVotingPeriod` — counting only |
| **`voter_slugs`** | **`ElectionVotingController::start():167`** — session re-arm | slug/session flow |

> **They are different columns on different tables and only one of them gates voting.** `start()` writes the slug's; the vote guard reads the code's. **That is why credential re-arming is impossible via `start()`** (Appendix 4 / classification **A**).

## Part C — the business event that grants the ability to vote

**`OBSERVED IN CODE`:**

> **Voter import does NOT grant the ability to vote. Neither does entitlement, nor the voting window opening. The credential is armed by one event only: *the voter successfully verifying the code sent to them*.**

**Decision ownership:**

| | |
|---|---|
| **Business meaning** | *"This person has proved possession of the credential issued to them for this election."* |
| **Decision owner** | **Application** (`CodeController`), on evidence the **voter** supplies |
| **Authoritative representation** | **`codes.can_vote_now`** |
| **Enforcement** | `VoteController:172-176`, server-side, scoped `(user_id, election_id)` |
| **Constitutional status** | **ABSENT** — the constitution defines no credential, verification or arming event |

**This closes the loop opened in Appendix 4:** *how a second vote is prevented* was known; **how a first becomes possible is now known too.** Entitlement (`ElectionMembership`) and ability-to-vote (`codes.can_vote_now`) are **separate decisions with separate owners and separate triggers** — a third confirmation that the product does not fuse these concerns.

## The Voting Right Lifecycle — as the implementation actually defines it

| Step | Authority | Enforced by |
|---|---|---|
| Voter provisioned | `VoterImportService::importElectionOnly` | Application |
| **Entitlement established** | **`ElectionMembership{role, status}`** | `ElectionVotingController::start():106-113` |
| Session opened | `VoterSlug` (re-armable) | `start():155-185` |
| **Credential armed** | **`codes.can_vote_now`** ← **code verification** | `CodeController::markCodeAsVerified()` |
| Window open | computed lifecycle projection | `start():122` — **and `PBDIGIT-64` shows the clock alone can set this** |
| Ballot accessed | credential guard | `VoteController:172-176` |
| **Vote exercised** | anonymous `votes` row (**no `user_id`**) | vote persistence |
| **Credential exhausted** | `markUserAsVoted()` → `can_vote_now = false` | `VoteController:1978` |
| **Second vote prohibited** | `codes UNIQUE(election_id,user_id)` + exhaustion | guard + DB |
| Closed / counted | `close_voting` | **`NOT VERIFIED`** |

**Every row is `OBSERVED IN CODE`. None is `OBSERVED AT RUNTIME` beyond the lifecycle steps already executed** — **no vote was cast in this programme.**

## Explicitly NOT done from this commission

**Parts D–I (the controlled five-voter runtime experiment), Part J, K, L and the full Part M report were not performed.** Reason: **session context exhaustion, not evidence**. **No fresh election was created, no voter voted, no anonymity inspection of persisted ballots, no results verification.** All remain `NOT VERIFIED`.

**Recommended next step, unchanged:** Parts D–I as written, now unblocked — **the credential-arming mechanism they depended on is established, so a runtime run can verify each of the ten lifecycle rows above rather than discover them.**

```
Code / tests / fixtures / config changed   none
Runtime data created                       none
Votes cast                                 none
Test suite                                 NOT RUN
Status                                     STOPPED — Parts D-M outstanding
```

---

# Appendix B — Experiment A, part 1: entitlement → armed credential (2026-08-09)

**Controlled single-voter journey on the clean Experiment B election (`election-2026-65b26848`, organisation `iervp-experiment-b`, `voting_active`, one approved President candidate).** No code, tests, fixtures or schema changed. **One vote NOT yet cast.**

**Credential values are deliberately omitted.** The issued voting code and session slug are live runtime credentials; recording them here would reproduce the hygiene defect this programme has been auditing. **The next session recovers experiment state from the runtime, not from a document.**

## Steps — all `OBSERVED AT RUNTIME`

| # | Step | Observed |
|---|---|---|
| 1 | Voter authenticates | `302` |
| 2 | Organisation page visited — **establishes tenant context** | `200` |
| 3 | `POST /elections/{slug}/start` — **the server-side entitlement gate** | `302` → `/v/{slug}/code/create` |
| 4 | `GET /v/{slug}/code/create` — **credential issuance** | `200` · a `codes` row appears where **none existed before** |
| 5 | Credential state **on issue** | **`can_vote_now = false`** · `has_voted = false` |
| 6 | `POST /v/{slug}/code` with the issued code — **verification** | `302` → `/vote/agreement` |
| 7 | Credential state **after verification** | **`can_vote_now = TRUE`** · `has_voted = false` |

## What this establishes

**The credential-arming model, previously traced only in code, is now confirmed at runtime:**

```
entitlement (ElectionMembership)  ->  credential ISSUED, unarmed
                                  ->  voter proves possession
                                  ->  credential ARMED  ->  voting access
```

**Steps 5 → 7 are the decisive pair:** the credential exists and is **not** armed until the voter verifies it. **`CodeController::markCodeAsVerified()` is confirmed as the arming event.**

> **Voter import does not grant the ability to vote. Entitlement does not either. Possession, proved, does.**

**This also confirms, at runtime, that entitlement and voting access are separate decisions with separate representations** — `ElectionMembership` and `codes.can_vote_now`. A fourth independent confirmation that the product does not fuse these concerns.

## ⚠️ This does NOT close `PBDIGIT-65`

**Step 2 was necessary.** The journey succeeded **only because the organisation page was visited first**, which set tenant context. Without it, the `start()` entitlement gate refuses the voter — that is the defect `PBDIGIT-65` records, **at the enforcement boundary**.

**The correct conclusion is narrow:**

> **Voting path verified *under correctly established tenant context*.**

**`PBDIGIT-65` remains open and is not disproved by this run.** A real voter arriving from a login redirect does not reliably pass step 2.

## NOT reached — all `NOT VERIFIED`

**Agreement → ballot · first vote · vote persistence · credential exhaustion · second-vote rejection · anonymity of the persisted ballot · results.**

**No vote was cast.** Nothing here claims the invariant composes into a working anonymous voting operation — only that the voter now holds an armed credential.

## Resume point

**The experiment is paused mid-journey, not abandoned.** The controlled voter is authenticated, entitled, and holds an **armed** credential sitting at the agreement step. **Resuming needs no setup** — only agreement → ballot → one vote → the three checks after it.

**Reason for pausing:** session context exhaustion. **Casting a vote that could not then be verified and recorded would have been worse than stopping one step short** — it would put a vote in the database with its most important evidence unexamined.

```
Code / tests / fixtures / schema changed   none
Votes cast                                 none
Data created                               one codes row (credential), one voter slug
Status                                     PAUSED mid-experiment — resume at /vote/agreement
```
