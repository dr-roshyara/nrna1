# Constitutional investigation — Election-Only mode & voter eligibility

**Type:** Read-only constitutional/architectural investigation · **Date:** 2026-08-09
**Commissioned by:** Product Owner, following IERVP Experiment B · **Mode:** evidence gathering only — **no code, tests, fixtures or documents changed; nothing implemented; no ticket opened or closed**
**Governance note:** deliberately carries **no product-story ID** — no story caused this work.

---

## ⛔ Finding 0 (blocking, and it reframes every other question)

> **There is no Election Manifesto in this repository.**

A repository-wide search for the phrase **"Election Manifesto"** returns **two files, neither of which is one**:

| Where it appears | What it actually is |
|---|---|
| `docs/plans/20260808-1030-election-verification-execution-plan.md` | the **`PBDIGIT-48` workstream's own execution plan** — a plan that *refers* to a manifesto |
| `docs/publicdigit/backlog/full_discovery.md` | a discovery working document |

**No governed document defines itself as the Election Manifesto or Election Constitution.** The commission's step 1 — *"locate the canonical Election Manifesto; if several claim authority, stop and report the conflict"* — resolves to a different answer than expected: **there is no conflict because there is no claimant.**

## What is authoritative instead

**The only artifact that declares itself the authority for election rules is source code:**

`app/Domain/Election/Constitution/ElectionConstitution.php` — its own docblock states:

> *"Defines all allowed state transitions, required roles, and preconditions. **THE SINGLE SOURCE OF TRUTH** for what actions are constitutionally allowed."*
> *"1. All transitions defined here and **NOWHERE ELSE**"*

It defines **16 actions**. It is a `final class` with a `const RULES` array — **not a governed document, with no version, status, steward, ratification record, or amendment history.**

**Interpretation (not fact):** the programme's "constitution" is a code registry. That is a legitimate engineering choice, but it means **constitutional questions are answered by reading PHP**, and there is no artifact a non-engineer can be held to.

---

## Findings 1–8 as commissioned

### Finding 1 — Constitutional status of Election-Only

**ABSENT.** Searched `ElectionConstitution.php` for every spelling: `election_only` **0** · `ElectionOnly` **0** · `full_membership` **0** · `voter_source` **0**.

**Election-Only mode does not exist at the constitutional layer.** It exists only in implementation (`elections.voter_source_strategy`, `organisations.uses_full_membership`, `VoterSourceStrategy`) — i.e. **category (A) "does the code have it" is YES while (C) "does the constitution define it" is NO.**

### Finding 2 — Constitutional status of voter eligibility

**ABSENT as a rule; PARTIAL as a precondition.**

The word "eligib" appears **twice**, both as **`capacity_eligibility`** — which is about **billing/plan capacity** (*"free plan ≤40 voters auto-approves"*), **not about whether a person may vote.**

The constitution does carry **`has_voters`** as a precondition of `complete_administration`. **But the constitution only names the precondition; the rule lives in the application layer** (`ConstitutionalTransitionGuard:182-183`), and it is about *whether the election has any voters at all* — **not whether a given person is eligible.**

**No constitutional statement equivalent to `ElectionMembership.role = voter AND status = active` exists.** That rule lives solely in `ElectionVotingController:41-44`.

### Finding 3 — Constitutional status of `ElectionMembership`

**ABSENT.** `ElectionMembership` appears **0 times** in the constitution; `enroll` **0 times**.

### Finding 4 — Relationship between Election-Only and `ElectionMembership`

**NOT CONSTITUTIONALLY ESTABLISHED.** The model the commission sketched —

```
Election → voter-source strategy → {Full Membership | Election-Only} → eligibility → ElectionMembership
```

— **is not supported by the Manifesto, because there is no Manifesto and the code constitution does not mention any element of that chain.** The relationship is real in implementation and **unrecorded in any authority.**

### Finding 5 — Legacy voter-management supersession

**NOT CONSTITUTIONALLY ESTABLISHED.** No constitutional statement replaces the legacy voter authority. **Evidence that both models are still live simultaneously** — the constitution's own `has_voters` precondition consults **both**:

```php
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()
    || $election->memberships()->where('role','voter')->where('status','active')->exists(),
```

**Two voter authorities, OR-ed together, at the constitutional precondition layer.** *(This corroborates `PBDIGIT-49` — "voter eligibility has two homes" — from the constitutional side.)*

**Note the asymmetry, which is directly relevant to `PBDIGIT-65`:** the legacy `voters()` branch is called `withoutGlobalScopes()`; the `memberships()` branch is **not**. **The same tenant-scoping exposure proven in `PBDIGIT-65` is present inside the constitutional precondition itself.** `OBSERVED IN CODE` — its runtime consequence was **not** measured here.

### Finding 6 — Current implementation conformity

**UNDETERMINABLE for these concepts.** Conformity requires a standard to conform to. For Election-Only, voter-source strategy, `ElectionMembership` and voter eligibility, **no constitutional statement exists**, so the implementation can be neither conformant nor divergent.

**For what the constitution *does* cover** (16 transition actions, roles, preconditions) the implementation **is** wired to it — `ConstitutionalTransitionGuard` reads `ElectionConstitution::RULES` — **but `PBDIGIT-64` proves that layer can be bypassed entirely**, because lifecycle state is *computed from the clock* rather than reached by a guarded transition.

### Finding 7 — Gaps and contradictions

1. **No Election Manifesto exists**, while at least one active workstream plans against one.
2. **The de-facto constitution is a PHP class** with no version, status or steward.
3. **The entire voter-eligibility domain is outside it.**
4. **Two voter authorities are OR-ed** inside a constitutional precondition (Finding 5).
5. **Constitutional transitions are not the only path to a lifecycle state** — the computed projection outranks them (`PBDIGIT-64`).
6. **`capacity_eligibility` and voter eligibility share the word "eligibility"** while being unrelated concepts — a vocabulary collision at the constitutional layer.

### Finding 8 — What requires Product Owner / Architecture Board decision

* **Does an Election Manifesto exist elsewhere** (outside this repository), or must one be created? **Engineering cannot answer this.**
* **Is `ElectionConstitution.php` intended to BE the constitution**, or to implement one?
* **Should voter eligibility be constitutional at all**, or is it legitimately an application concern?
* **Which voter authority is canonical** — legacy `voters` or `ElectionMembership`? *(`PBDIGIT-49`)*
* **Is tenant context permitted to participate in constitutional predicates?** *(Finding 5's asymmetry, `PBDIGIT-65`.)*

---

## Constitutional Traceability Matrix

| Concept | In constitution | Reference | Implementation | Test evidence | Status |
|---|---|---|---|---|---|
| Election-Only | ❌ 0 hits | — | `voter_source_strategy`, `VoterSourceStrategy` | not examined | **ABSENT** |
| Full Membership | ❌ 0 hits | — | `organisations.uses_full_membership` | not examined | **ABSENT** |
| voter source strategy | ❌ 0 hits | — | `elections.voter_source_strategy` | not examined | **ABSENT** |
| voter eligibility | ❌ (only `capacity_eligibility`, unrelated) | `RULES` `:35,49` | `ElectionVotingController:41-44` | not examined | **ABSENT** |
| `ElectionMembership` | ❌ 0 hits | — | `app/Models/ElectionMembership.php` | not examined | **ABSENT** |
| election enrollment | ❌ 0 hits | — | `VoterImportService::importElectionOnly` | not examined | **ABSENT** |
| Membership dependency | ❌ | — | `User::isEligibleVoter()` (`Member`-based) | not examined | **ABSENT** |
| Eligibility policy | ⚠️ named only | `has_voters` precondition | `ConstitutionalTransitionGuard:182-183` | not examined | **PARTIAL** |
| legacy voter authority | ⚠️ **both authorities OR-ed** | `ConstitutionalTransitionGuard:182-183` | `voters` + `election_memberships` | not examined | **CONTRADICTED** |
| constitutional snapshot | ❌ 0 hits | — | set at creation from the organisation | not examined | **ABSENT** |

**Test evidence is `not examined` on every row — the suite was not run. `UNDETERMINED`, not "absent".**

---

## The four questions, kept separate as commissioned

| Question | Answer |
|---|---|
| **A** Does the code have Election-Only? | **YES** — observed at runtime, an election-only election was built and voters imported |
| **B** Does an architecture document describe it? | **Partially** — implementation/design documents reference it; **none is constitutional** |
| **C** Does the Election Manifesto constitutionally define it? | **NO — and there is no Election Manifesto** |
| **D** Is the implementation conformant? | **Not answerable** — no standard exists to measure against |

## Authorization boundary

```
Code changed             none
Tests changed            none
Documents amended        none  (the constitution was READ, never edited)
Tickets opened/closed    none
Manifesto created        none — creating one is a governance act
Test suite               NOT RUN (so all test evidence is UNDETERMINED)
Status                   STOPPED — awaiting Product Owner / Architecture Board
```

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php:7-21,35,49,72,87` · `app/Application/Election/Services/ConstitutionalTransitionGuard.php:182-186` · `app/Http/Controllers/ElectionVotingController.php:41-44` · repository-wide searches for *"Election Manifesto"*, `election_only`, `ElectionMembership`, `voter_source`, `enroll` · `PBDIGIT-64` · `PBDIGIT-65` · `PBDIGIT-49`

---

# Appendix — Legacy Authority Register (read-only, 2026-08-09)

**Scope note, stated first:** the commission that requested this ran to 18 sections. **Only the Legacy Authority Register below was newly measured.** Sections already answered by earlier proven work are cross-referenced rather than repeated, and **everything else is explicitly NOT DONE** (see the boundary at the end). **The session's context budget, not the evidence, is what limited this.**

## E · Legacy Authority Register

**Schema measured directly via `Schema::hasColumn`:**

| Field | `users` | `election_memberships` | Production readers/writers | Classification |
|---|---|---|---|---|
| `can_vote` | **❌ absent** | **❌ absent** | `ResetElection.php:197` (`DB::table('users')->where('can_vote',1)`), `DebugVoterSlug.php:60,92,93` | **LEGACY — refers to a column that does not exist** |
| `is_voter` | **❌ absent** | **❌ absent** | `AddHimaniCandidateCommand.php:32`, `BulkDisapproveVoters.php:58` | **LEGACY — same** |
| `can_vote_now` | ❌ absent | ❌ absent | — | **LEGACY / unused** |
| `is_eligible` | ❌ absent | ❌ absent | — | **not persisted at all** |
| `has_voted` | ❌ absent | **✅ present** | read in `ElectionVotingController`; also read on the **`Code`** model (`EndVotingPeriod.php:64`, `ElectionProcessController.php:353`) | **PROJECTION on `election_memberships`; a *separate* `has_voted` exists on `codes`** |

### Findings from the register

1. **`can_vote` and `is_voter` are read and written by production console code against columns that do not exist.** `ResetElection.php:197` would fail at runtime. **`OBSERVED IN CODE`; not executed here.** This corroborates **`PBDIGIT-35`** with fresh schema evidence, and **`PBDIGIT-49`'s** correction stands: eligibility is org+election scoped, not a global user flag.
2. **`isEligible` is not persisted anywhere.** It is computed per request in `ElectionVotingController:41-44`. **It is therefore a projection, never an authority** — which is exactly why `PBDIGIT-65` could flip it by changing session context alone.
3. **`has_voted` exists on two different tables** (`election_memberships` and `codes`) — two representations of "this person has voted". **Business authority not yet established** for which is canonical. **Not investigated further.**

## B · What currently makes a voter eligible — answered from proven work

**Mechanism (proven, `PBDIGIT-65`):** `ElectionVotingController:41-44` computes it live from `$user->electionMemberships()->where('election_id', …)->first()`, requiring `role === 'voter' && status !== 'removed'`. **That query is tenant-scoped via `BelongsToTenant`, so the answer depends on session organisation.** **Decision ownership:** the *rule* is Application-layer; the *data* is `ElectionMembership`; **the deciding input is Infrastructure** — which is the defect.

## C · What Election-Only means in the current system

**IMPLEMENTATION ESTABLISHED, NOT CONSTITUTIONALLY ESTABLISHED.** It is a real, coherent, working mechanism — `organisations.uses_full_membership` → snapshot to `elections.voter_source_strategy` at creation → `VoterImportService::importElectionOnly` creates users + `ElectionMembership` directly, bypassing `Member` entirely. **It executed end to end at runtime.** The constitution mentions **none** of it (Findings 1–4 above).

> **This is existing business behaviour whose constitutional ownership has not been documented — NOT legacy behaviour to be removed.** The commission's §8 distinction is the correct reading here, and this appendix records it as such.

## Authorization boundary for this appendix

```
Code changed            none
Tests changed           none
Database rows changed   none — no eligibility manufactured, no flag set
New runtime data        none — no organisation, election, voter, candidate or vote created
Test suite              NOT RUN
Sections NOT DONE       Track A re-run · §6 full writer/reader trace of the import→ballot
                        path · §11 lifecycle-vs-ballot-access separation · §17 A/D/F/G/H/I
Status                  STOPPED
```

---

# Appendix 2 — The Election-Only voter-entitlement chain (read-only, 2026-08-09)

**Correction first:** the previous appendix was signed off *"DISCOVERY COMPLETE"* for a commission of which **one section** had been done. **That wording is withdrawn** — it is the same overstatement this programme has repeatedly caught. This appendix answers the narrow question that was actually outstanding.

## A · The current voter-entitlement chain — ESTABLISHED

**The enforcement boundary is `ElectionVotingController::start()` (`:98-133`), not the page that renders `isEligible`.** In order:

```
1. membership   $user->electionMemberships()->where('election_id',…)->first()
                must exist AND role === 'voter' AND status !== 'removed'
2. uniqueness   $membership->has_voted            -> "You have already voted."
3. lifecycle    ElectionLifecycle::of($election)->canVote()
4. IP           resolveIpBlock($election, $request->ip())
```

## B · Decision ownership

| Decision | Authoritative representation | Owner | Evidence | Constitutional? |
|---|---|---|---|---|
| Is this person a voter here? | **`ElectionMembership.role`** | Application | `start():106-113` | ❌ absent |
| Is the registration still valid? | **`ElectionMembership.status`** | Application | `start():112` | ❌ absent |
| Has this person already voted? | **`election_memberships.has_voted`** | Application | `start():115-118` | ❌ absent |
| May voting happen at all now? | **computed lifecycle projection** | Domain (nominally) | `start():122-124` | ⚠️ partially — but `PBDIGIT-64` shows the clock outranks it |
| May this request vote from here? | election IP config | Infrastructure/Policy | `start():136+` | ❌ absent |
| *Displayed* eligibility | `isEligible` | **Interface projection** | `show():41-44` | n/a — not an authority |

## C · Election-Only vs Membership mode — **THEY CONVERGE COMPLETELY**

**`start()` never consults `voter_source_strategy`, `uses_full_membership`, `Member`, or `User::isEligibleVoter()`.** It reads **only `ElectionMembership`.**

```
Full-membership import ─┐
                        ├─►  ElectionMembership  ─►  entitlement + uniqueness  ─►  ballot
Election-only import  ──┘
```

**Convergence point: `ElectionMembership`.** The modes differ **only in how that row is created** — `importElectionOnly()` creates `User` + `ElectionMembership` directly, bypassing `Member`. **After import the two modes are indistinguishable to the voting path.**

> **`ElectionMembership` *is* the voter-entitlement model in the current implementation.** There is **no separate entitlement concept**: registration, eligibility and one-vote enforcement are all carried by one row. **Whether the business intends them to be one decision or three is undecided** — that is the constitutional gap, not a code gap.

**This also resolves the `has_voted` duplication for the voting path:** the enforcement boundary reads **`election_memberships.has_voted`**. The `codes.has_voted` column is read by other code (`EndVotingPeriod`, `ElectionProcessController`) and **its relationship to this one is still `UNDETERMINED`.**

## D · Legacy transition assessment

**SAFE TRANSITION — with one caveat.**

Historical Election-Only behaviour is already fully expressible in the current model: `ElectionMembership{role: voter, status: active}` is created by the supported import and is the sole input the enforcement boundary reads. **No legacy field needs restoring as authority**, and none could be — `can_vote`/`is_voter` are absent from the schema.

**Caveat:** the transition is safe *technically*; **it is not yet safe *semantically***, because nothing declares that `ElectionMembership` **means** voting entitlement. Today that is true by implementation only.

## E · Open business decisions

1. **Is `ElectionMembership` the constitutional voter-entitlement authority**, or merely a registration relationship with entitlement to be decided separately?
2. **Are registration · eligibility · entitlement one decision or three?** The code says one; the business has not said.
3. **Which `has_voted` is canonical** — `election_memberships` or `codes`?
4. **Should Election-Only appear in the constitution at all**, given the modes converge immediately after import?

## F · Next discovery — one step

**Trace `codes.has_voted` against `election_memberships.has_voted`**: writers, readers, and whether any path can mark one without the other. **That is the remaining unmeasured duplication, and it sits directly on the anonymity-critical vote path.**

## ⚠️ One consequence worth stating plainly

**`PBDIGIT-65` is worse than recorded.** The tenant-scoped membership lookup it proved is used **not only by the display query but by `start():106` — the enforcement boundary itself.** A voter whose session points at another organisation is therefore **refused the ballot server-side**, not merely shown a discouraging page. **`OBSERVED IN CODE`; the runtime refusal was not executed** (no vote was attempted).

## Boundary

```
Code / tests / fixtures / DB rows changed   none
Runtime data created                        none — no election, voter, candidate or vote
Test suite                                  NOT RUN
Status                                      STOPPED — awaiting Product Owner
```

---

# Appendix 3 — Legacy `has_voted` vs current one-vote enforcement (read-only, 2026-08-09)

**Business invariant (given, not inferred):** *a voter may cast at most one valid vote in an election.* **Kept separate from any mechanism throughout.**

## Classification: **B / C — and which one is `UNDETERMINED`**

**I cannot return a single letter honestly, and the reason is itself the finding.**

### What is proven

| Observation | Evidence |
|---|---|
| **The current enforcement READS `election_memberships.has_voted`** | `ElectionVotingController::start():115-118` — *"You have already voted."* |
| **`ElectionMembership::markAsVoted()` has ZERO callers** | `grep '\->markAsVoted(' app/` returns **nothing** outside model definitions |
| **No production code writes `election_memberships.has_voted`** | `'has_voted' => true` outside `app/Models/` appears only in: `PublicDemoController:337` (demo), `DemoVoteController:1794,1802` (demo), `VoteController:1845` (**`$voterSlug`**), `VoteController:1978` (**`markUserAsVoted($code, …)` — a `Code`/`DemoCode`**), `VoteController:2201` (**an Inertia render prop, not persistence**) |
| **Four other models each define their own `markAsVoted()`** | `Voter:80` · `VoterRegistration:182` · `VoterSlug:113` · `User:1006` |

### The reading, stated at evidence strength

**The read side and the write side appear to target different representations.** The current election path *reads* `election_memberships.has_voted`; every writer found *writes* `codes` / `voter_slugs` / demo tables. **`ElectionMembership::markAsVoted()` — the method that would close the loop — is dead code by call-graph.**

**If that holds, the one-vote guard at `start():116` would never fire, because the flag it reads is never set by the path that casts the vote.**

### ⚠️ Why I will not assert that

**No vote has ever been cast in this programme.** `INTERPRETATION`, not `CONCLUSION`. Three things could falsify it and none was measured:

1. **`VoteController` is a different, older vote path** than the one `ElectionVotingController::start()` leads to — **I never traced `start()` past the entitlement checks to the actual submission**, so I do not know which controller ultimately persists an election vote.
2. A model event, observer, or listener could set the flag without a literal `has_voted => true`.
3. The submission path may enforce uniqueness by a mechanism I did not look for — a vote lookup or a database constraint.

**`MECHANISM NOT ESTABLISHED.`**

## Answering the commissioned questions

* **Is `has_voted` legacy?** **On `codes` and `voter_slugs`: yes** — written by the older `VoteController`/demo paths. **On `election_memberships`: it is the *current* representation, and it is read by current enforcement.** So "legacy" splits by table, not by field name — **exactly the trap the commission warned about.**
* **Has the legacy mechanism been superseded?** **Partially at best.** The new representation exists and is read; **its writer was not found.**
* **Is `ElectionMembership` merely the participation relationship, with the invariant enforced elsewhere?** **Possible and untested** — that is precisely hypothesis (3) above, and it would make `ElectionMembership.has_voted` a vestigial read rather than an authority.
* **Existing verification:** `Existing verification not established` — the suite was not run.

## The one next step that settles it

**Trace `ElectionVotingController::start()` forward to the controller that actually persists an election vote, and identify what that path writes.** Everything above collapses to a single letter once that is known. **Do not delete, migrate or "clean up" `has_voted` before then** — on this evidence it is impossible to say which representation would be removed.

## Boundary

```
Code / tests / fixtures / DB changed   none
Vote cast                              none — so no runtime claim about double voting
Test suite                             NOT RUN
Classification                         B or C — UNDETERMINED between them, deliberately
```

## Appendix 3a — the step taken; classification resolved to **C**

**`ElectionVotingController::start():155-185` was traced forward. It does not persist a vote at all.** It creates or **refreshes a `VoterSlug`** and redirects to **`slug.code.create`** — handing the voter into the **`VoterSlug` + `Code`** flow that `VoteController` serves.

```
start()  -- reads election_memberships.has_voted (entitlement gate)
   |
   +-> VoterSlug created/refreshed:  status=active, is_active=true,
   |                                 can_vote_now=true, expires_at=+30min
   |
   +-> redirect slug.code.create  ->  VoteController  ->  writes has_voted on
                                                          voter_slugs / codes
```

**This falsifies hypothesis (1) in the appendix above:** `VoteController` is **not** a different, older path — **it is the path `start()` leads to.**

### Classification: **C — CURRENT ENFORCEMENT STILL DEPENDS ON LEGACY**

**The new representation is read and never written; the mechanism that actually records having voted is the one described as legacy.** `ElectionMembership::markAsVoted()` — which would close the loop — remains **dead code by call-graph**.

**Additional observation, and it is the one I would look at first:** the reuse branch **re-arms** an existing slug (`status=active`, `can_vote_now=true`, fresh 30-minute expiry) **before** any check of that slug's own voting state. **Whatever prevents a second vote is therefore not in `start()`** — it must live inside the `Code` flow, or nowhere. **`MECHANISM NOT ESTABLISHED`; hypotheses (2) and (3) — an observer, or code-exhaustion/constraint enforcement inside the `Code` flow — remain untested, and (3) is now the likely candidate given the two-use code design.**

> **No vote has been cast, so this is NOT a claim that double voting is possible.** It is a claim about which representation the current path writes: **not `election_memberships`.**

**Consequence for `PBDIGIT-66` `D-1`/`D-2`:** `ElectionMembership` is the authority for **entitlement** (proven) but demonstrably **not** for **"has exercised the vote"**. **The collapse of registration/eligibility/entitlement into one row does not extend to the one-vote invariant** — which strengthens the case that these are separate business decisions rather than one.

**Next step (unchanged in kind, narrower in scope):** trace the `Code` flow's one-vote enforcement — `codes.has_voted`, code exhaustion, or a constraint — and determine whether the invariant holds without `election_memberships` participating at all.

---

# Appendix 4 — The one-vote invariant: where it is actually enforced (read-only, 2026-08-09)

**Invariant investigated, not a column:** *a voter may cast at most one valid vote in an election.* **No vote cast · no data written · no new election · nothing modified.**

## The structural finding — and it explains the whole design

**Unique indexes, read from PostgreSQL:**

```
codes             UNIQUE (election_id, user_id)        <-- one credential per voter per election
voter_slugs       UNIQUE (election_id, user_id)        <-- one session slug per voter per election
election_memberships UNIQUE (user_id, election_id) WHERE deleted_at IS NULL
votes             UNIQUE (id), (vote_hash), (receipt_hash)   <-- and NOTHING tying a vote to a voter
```

> **`votes` carries no uniqueness against a voter, and it cannot — the table has no `user_id` by constitutional design (anonymity).**

**Therefore the one-vote invariant is structurally forced off the ballot and onto the credential.** This is not an accident or a legacy leftover: **anonymity makes credential-exhaustion the only place the invariant can live.** `OBSERVED IN DATABASE` + `INTERPRETATION`.

## The three facts, kept separate as commissioned

| | Question | Representation | Owner |
|---|---|---|---|
| **A · Entitlement** | May this person vote here? | **`ElectionMembership`** (`role`, `status`) | Application — proven earlier |
| **B · Execution** | May *this request* submit now? | **`Code.can_vote_now`** (+ `VoterSlug` state, window, IP) | Application |
| **C · One-vote invariant** | Has the right already been exercised? | **the `codes` row — `UNIQUE(election_id, user_id)` + exhaustion** | **Infrastructure (uniqueness) + Application (exhaustion)** |

**These have different owners and different representations. The product does NOT collapse them** — which contradicts the earlier working assumption that `ElectionMembership` might own all three.

## The enforcement point

`VoteController:172-176` gates the real-election path on:

```php
Code::withoutGlobalScopes()
    ->where('user_id', $user->id)->where('election_id', $election->id)
    ->where('can_vote_now', true)->exists();
```

and `markUserAsVoted($code, …)` (`:1977-1981`) sets **`has_voted=true`, `can_vote_now=false`, `is_code_to_save_vote_usable=false`.**

**So the mechanism is credential exhaustion, scoped correctly to `(election_id, user_id)`** — the commission's §6 election-scoping check **passes**: the guard, the exhaustion write and the unique index are all keyed on the election, not user-global.

## Classification: **B — CURRENT MECHANISM ENFORCES IT, BUT LEGACY STATE IS STILL CONSULTED**

*(Revising Appendix 3a's **C**, which asked a narrower question — "does the current path consult legacy state?" — and answered it correctly. Asked about **the invariant** rather than the field, the answer differs.)*

* **The invariant has a real mechanism** — one credential row per voter per election, exhausted on use, guarded server-side, election-scoped. **`ElectionMembership.has_voted` is not required for it.**
* **But `ElectionVotingController::start():115-118` still consults `election_memberships.has_voted`** — a field **nothing writes**. That coupling is unnecessary and, being permanently `false`, that particular guard is **inert**.

> **The architectural fault is not a missing mechanism. It is that the entitlement model is asked a question that belongs to the exercise-of-vote history.** Exactly the separation the commission anticipated.

## What is NOT established

* **`can_vote_now` must be set to `true` somewhere before voting** (code verification). **I did not trace that arming step**, so I cannot prove a voter can complete a first vote — only how a second is prevented. `MECHANISM NOT ESTABLISHED`.
* **Failure modes 1–7 were not exercised.** No vote was cast. Whether `start()` re-arming a `VoterSlug` can re-arm the *code* is **`UNDETERMINED`** and is the single most important open question, because it is the one path that could defeat exhaustion.
* **Existing test evidence: `Existing verification not established`** — suite not run.

## Implication for the Manifesto discovery (`PBDIGIT-66`)

**`D-2` now has evidence rather than speculation: registration/eligibility/entitlement and the one-vote invariant are already separate in the implementation, and anonymity forces them apart.** A constitution that fused them would contradict the anonymity invariant. **Recorded as evidence for the decision — not as the decision.**
