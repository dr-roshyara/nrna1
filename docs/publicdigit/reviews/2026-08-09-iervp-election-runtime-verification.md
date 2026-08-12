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

---

# Appendix C — Diagnosis of a reported "not eligible" (2026-08-12)

**Report:** *"I import voters as election chief, they are emailed, they are added as voters — but they cannot start voting. `roshyara@gmail.com` is a voter, yet `/elections/election-2026-65b26848` says **You are not eligible to vote in this election**."*

**Read-only. No code, tests, fixtures, configuration or data changed.** Every line below is **MEASURED**.

## The decision chain (Step 7 — which authority answers)

```
Election page  ->  ElectionVotingController::show():41-44
                   $membership = $user->electionMemberships()->where('election_id', …)->first();
                   $isEligible = $membership !== null && role === 'voter' && status !== 'removed';
                        |
                        v  Inertia prop  isEligible
                   Show.vue :297,369  ->  locales/pages/Election/en.json:67
                                          "You are not eligible to vote in this election"
```

**The authority is the tenant-scoped `ElectionMembership` lookup.** Not a policy, not the constitution, not `VoterEligibilityService`, not `User::isEligibleVoter()`.

## Three measurements, three different answers

### 1 · On the URL visited, the message is **correct**
`election-2026-65b26848` (org `iervp-experiment-b`) — **`roshyara@gmail.com` holds ZERO membership rows there.** Its five voters are `iervp.voter1..5@example.test`, imported by this programme during Experiment B. **That account is the chief/candidate there, never a voter.**

> **On that election the system is right and the premise of the report is not met.**

### 2 · The account **is** a voter — on a different election
| Election | Org | Role | Status |
|---|---|---|---|
| `namaste 2026` | `namaste-nepal-gmbh` | **voter** | active |
| **`test election 1`** (`test-election-1-62a8be36`) | `namaste-nepal-gmbh` | **voter** | active |

**Import worked.** `role=voter`, `status=active` — entitlement was created correctly, alongside `krish.hari.sharma@gmail.com`.

### 3 · On the right election, `PBDIGIT-65` hides it — **reproduced on real data**
```
election org              namaste-nepal-gmbh
user working org          iervp-experiment-b        <-- IERVP Experiment B residue
membership (unscoped)     EXISTS   role=voter status=active
membership (tenant = iervp-experiment-b)   INVISIBLE  -> isEligible = false
membership (tenant = namaste-nepal-gmbh)   VISIBLE
```

> **`PBDIGIT-65` is no longer only reproducible on synthetic accounts. It is now confirmed on the Product Owner's own account against real election data.**

### 4 · And voting is not open there anyway
`test election 1` lifecycle = **`setup_nomination`**. **Even with tenant context correct, no ballot is available.**

## ⚠️ This programme caused part of the symptom

**Experiment B changed `users.organisation_id` for `roshyara@gmail.com` to `iervp-experiment-b`.** That was recorded as disposal-pending residue in §7 of this review — **it is now actively interfering with Product Owner testing.** The residue is not merely untidy; **it is the tenant context that triggers `PBDIGIT-65` for this account.**

## What this does and does not establish

| Claim | Verdict |
|---|---|
| Import fails to create voter entitlement | ❌ **DISPROVEN** — `role=voter, status=active` created correctly |
| Import must grant immediate voting capability | **Not demonstrated either way** by this report |
| The Election Constitution is wrong about eligibility | ❌ **NOT DEMONSTRATED** — the constitution was never consulted on this path |
| An adapter/context defect blocks an entitled voter | ✅ **CONFIRMED** — `PBDIGIT-65`, on real data |
| The reported ticket can be closed | ❌ **NO** — but for reasons other than those hypothesised |

**The Product Owner's four-concept model — entitlement · eligibility · voting access · vote authority — is *not* contradicted by this report.** The failure occurred **before** any of those distinctions were reached: **the membership was never seen.**

## Recommended next step — one, and it is cheap

**Restore the account's working organisation and retest on `test-election-1-62a8be36`.** That separates the residue-induced `PBDIGIT-65` symptom from any genuine entitlement-vs-capability question, which cannot be observed until the election reaches `voting_active`.

**Not done here — changing a user's working organisation is a data change, and this commission is read-only.**

---

# Appendix D — Voter entitlement under the two organisation modes (2026-08-12)

**Commission:** the Product Owner's eight-step correction — establish entitlement against the **election's voter-source strategy**, not against "membership" generically.
**Read-only. No code, tests, fixtures, configuration or data changed.** All rows **MEASURED**.

**This supersedes Appendix C's recommendation** (*"restore the org context and retest"*), which was too narrow: it would have tested the symptom without establishing the entitlement rule.

## The eight steps

| # | Question | Answer |
|---|---|---|
| **1** | Organisation mode of `namaste-nepal-gmbh` | **`uses_full_membership = false` → Election-Only** |
| **2** | `test election 1` persisted strategy | **`election_only`** |
| **3** | Was the strategy snapshotted at creation? | ✅ **Yes, and it matches the org mode.** `VoterSourceStrategy::fromElection()` **throws** on `NULL` rather than deriving — honouring *"must be passed from election context, never derived internally"* |
| **4** | `ElectionMembership` for `roshyara@gmail.com`? | ✅ **`role=voter, status=active`** |
| **5** | Full Membership → org Member required? | **Not applicable here** — this election is Election-Only |
| **6** | Election-Only → is `ElectionMembership` sufficient? | ✅ **Yes — and confirmed at data level** (below) |
| **7** | **Does the voting page's `isEligible` respect the strategy?** | ❌ **No — zero references.** See below; **this is not automatically a defect** |
| **8** | Lifecycle / access / authority | `test election 1` is **`setup_nomination`** — no ballot exists yet regardless |

> **Entitlement verdict for `roshyara@gmail.com` on `test election 1`: ✅ ENTITLED, per the Product Owner's own table (Election-Only + ElectionMember = entitled).** **The refusal was never an entitlement decision.**

## Step 6 confirmed against real data

Voters per election, and how many lack an organisation `Member` record:

| Election | Strategy | Voters | Without org `Member` |
|---|---|---:|---:|
| `namaste 2026` | `election_only` | 3 | **3** |
| `test election 1` | `election_only` | 2 | **2** |
| `Election 2026` (iervp-experiment-b) | `election_only` | 5 | **5** |
| `Election 2026 Round 2` | `election_only` | 5 | **5** |
| `Election 2026` (iervp-election-only-org) | `election_only` | 5 | **5** |
| `Election 2026` (pbdigit63-…-org) | **`full_membership`** | **0** | 0 |
| `Election 2026` (pbdigit63-…-org-1) | **`full_membership`** | **0** | 0 |

**Every Election-Only voter lacks an organisation `Member` record — and that is exactly correct.** The Product Owner's model is confirmed by data:

```
Election-Only:     ElectionMember  ->  entitlement          (no org Member required)
Full Membership:   org Member  ->  ElectionMember  ->  entitlement
```

## 🔑 Step 7 in full — where the strategy *is* enforced

**The strategy is consulted at ASSIGNMENT time, and never at EXERCISE time.**

```
IMPORT / VOTER MANAGEMENT              VOTING PAGE / start()
VoterImportService, ElectionVoter-     ElectionVotingController
Controller::store()                    :41-44, :106-113
        |                                      |
        v  VoterSourceStrategy::fromElection    v  reads ElectionMembership ONLY
        v  eligibilityService->isEligibleVoter  v  role === 'voter' && status !== 'removed'
        v  (mode-aware: org Member for full,        ZERO strategy references
           org user for election-only)
        |                                      |
        +----------> ElectionMembership <-------+
```

**Consequences, stated separately because they differ by mode:**

* **Election-Only — correct.** `ElectionMembership` *is* the entitlement; re-consulting the strategy would add nothing.
* **Full Membership — a point-in-time grant, not a continuous invariant.** The org-Member condition is verified **once, at assignment**, and **never re-checked when the ballot is served.** If a person's organisation membership lapses, is revoked, or has its fees fall out of `paid|exempt` **after** import, **their election entitlement survives unchanged.**

> **The open DDD question this raises — and it is the Product Owner's, not engineering's:**
> **Is voter entitlement a point-in-time grant made at assignment, or a continuous invariant that must hold at the moment the ballot is served?**
> The implementation currently answers *point-in-time*. **Nothing records that as a decision.**

## ⚠️ Full Membership mode has never been exercised end to end

**Both `full_membership` elections have zero voters.** So the Product Owner's table row —

| Full Membership | org Member ❌ | ElectionMember ✅ | should be **NOT entitled** |

— **has never been tested in this system, and cannot be tested today**, because two prerequisites are independently blocked:

* **`IERVP-3`** — `POST /organisations/{org}/members` can never authorise (no `MemberPolicy`, `Gate::allows` false even for the owner → 403). **No member can be created through the supported route.**
* **`IERVP-2`** — `grants_voting_rights` defaults `false` and **is written by no controller, request or UI field**, so any member created another way would still fail the full-membership eligibility predicate.

> **Full Membership mode is configured, snapshotted, documented and eligibility-checked — and is currently unreachable through supported journeys.** `OBSERVED`. **This is the strongest argument yet for prioritising `IERVP-2` and `IERVP-3`, which were both deliberately left unticketed.**

## Latent hazard — three elections carry a `NULL` strategy

`Demo Election`, `Demo Election - Public Digit`, `Demo Election - Namaste Nepal` have **`voter_source_strategy = NULL`**. Any path calling `VoterSourceStrategy::fromElection()` on them **throws a `RuntimeException`**. A backfill command exists (`app:backfill-voter-source-strategy`, with `--audit-only`). **Not run — that would be a data change.**

## What this establishes, and what it does not

| Claim | Verdict |
|---|---|
| The two-mode entitlement model is implemented as the Product Owner describes | ✅ **CONFIRMED** for Election-Only, at data level |
| `ElectionMembership` is election-scoped, distinct from organisation `Member` | ✅ **CONFIRMED** — every Election-Only voter has the former and not the latter |
| The strategy snapshot is respected at assignment | ✅ **CONFIRMED** — mode passed from election context, `NULL` fails closed |
| The voting page respects the strategy | ❌ **It does not consult it** — correct under Election-Only, **unvalidated under Full Membership** |
| Full-Membership entitlement behaves per the table | ❌ **NOT VERIFIED — no data exists, and the mode is unreachable** |
| The Constitution is wrong about eligibility | ❌ **STILL NOT DEMONSTRATED** — the constitution remains absent from this path (see the companion review) |
| `roshyara@gmail.com` is entitled on `test election 1` | ✅ **YES** — the refusal was `PBDIGIT-65` plus a pre-voting lifecycle |

**Decisions A–E from the Product Owner's model map cleanly onto the code, except that D and E are not reached in the reported case, and C is enforced only at assignment.**

## Recommended next step — one

**Make Full Membership mode reachable** (`IERVP-3`, then `IERVP-2`), then test the four-row table directly. **Until then, half the entitlement model is unverifiable — and no conclusion about the Constitution's adequacy can be drawn from a mode that cannot run.**
