# ADR — Voting Authorization Boundary (`forEntry()` / `forCast()`)

**Status: ✅ APPROVED — Phase 3 Authorization Boundary Design** (PO / ARB decision, 2026-10-05; record: `docs/publicdigit/reviews/2026-10-05-phase3-authorization-boundary-po-arb-decision-record.md`). **Revision 3 (ballot integrity) APPROVED 2026-10-05** (record: `docs/publicdigit/reviews/2026-10-05-adr-revision-3-po-arb-decision-record.md`). **Architectural baseline only — frozen; no production implementation authorised.** Q1–Q7 are approval conditions / implementation prerequisites. Phase 3 of the roadmap — **design only**. Nothing here authorises production code, migrations, schema, routes, UI or behavioural change.
**Created:** 2026-10-05 · **Roadmap:** `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` (Phase 3; governance state §2a) · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/` beside the other PublicDigit ADRs.

**Evidence base (consumed, not restated):**
- Situation report `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` (SR, rev 9)
- Phase 1 evidence `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md` (Gate 1 ACCEPTED) and its tests `tests/Feature/Characterization/Phase1/`
- ADR `ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md` (admission, entitlement — DECIDED)
- Backlog PBDIGIT-223 … 233

**Binding constraints on this ADR:** the interim anonymity rule (roadmap §2a, 7-point checklist) — every mechanism below carries an anonymity-boundary assessment (§7); the Model A / Model B anonymity decision is **not** taken here (Phase 2.0); `Code.can_vote_now` and `Code.has_voted` semantics are not changed by this ADR; no persisted authorization field.

> **What this ADR protects, in one line:** *Whether a voter may enter the voting process is decided fresh by the authorization boundary (admission inside entitlement, the lifecycle, and one security authority); whether a cast attempt is currently valid is determined by fresh authorization plus process validation, followed by atomic participation consumption — while the code process records facts and never grants authority.*

> **Revision 2 (2026-10-05, PO review — precision pass, no redesign):** three distinct concepts (authorization ≠ process validation ≠ atomic participation consumption); the result of `forCast()` is a `CastDecision`, not "authorization"; the compare-and-set is **outside** any decision and the only authoritative "already voted"; `VotingSecurityDecision` is a **conceptual role** fulfilled by the existing Trust → Legitimacy chain (no new engine); Q3 reduced to deciding the policy boundary; new invariant I10; I6 qualified; Q4 (demo) a security-scope decision with a no-silent-change rule; Q5 as explicit options; implementation separately authorised per phase and per production edit group.

> **Revision 3 (2026-10-05, PO / ARB — ballot integrity, VE-01 / PBDIGIT-237):** fourth concept *ballot integrity* beside `forCast()`; I1 amended (the commit condition requires a `ValidatedBallot` and persists exactly its content); new invariants I11 (transport data untrusted), I12 (vote persistence accepts only a `ValidatedBallot`), I13 (authorization and ballot validation both complete before the participation CAS, from one CastContext). Authorization semantics, `CastDecision` and its reason vocabulary, CAS semantics and the anonymity rules are unchanged. No implementation authorised by this revision.

---

## 1 · Context (facts)

| # | Fact | Source |
|---|---|---|
| F1 | Admission (ALL-of) is evaluated inside `VotingEntitlementPolicy::standing()`; `decide()` adds the lifecycle (`ElectionNotVotable`). Reasons: `Entitled · NotRegistered · NotVoterRole · OrganisationMismatch · NotActive · NotAdmitted · ElectionNotVotable`. | `app/Contexts/Elections/Domain/Policies/VotingEntitlementPolicy.php`; `…/ValueObjects/VotingEntitlementReason.php` |
| F2 | Entitlement is asked at every slug voting step by route middleware (`ensure.election.voter`, cached, invalidated by model events) and **fresh, uncached, in the transaction** at the final vote. | SR §3, §7; `VotingProcessEntitlementBoundaryTest` (41) |
| F3 | One security decision already exists, used only by the final vote: `TrustPolicyEvaluator` → `PolicySequence` (Verification, NetworkBinding, DeviceBinding policies) → `TrustEvaluationState {SUFFICIENT_EVIDENCE, INSUFFICIENT_EVIDENCE, REVIEW_REQUIRED, INCONCLUSIVE}` → `ConstitutionalLegitimacyDecision::decide()` → `LegitimacyOutcome {Allowed, Denied, Deferred, Investigate}`. In practice only IP density can deny. | `app/Application/Election/Security/*`; `VoteController::store:1492-1556`; SR §5 |
| F4 | Three further, overlapping IP-count checks exist outside that decision (`CodeController::verifyCode`, `vote_post_check`, `ElectionVotingController::resolveIpBlock`). | SR §5; PBDIGIT-233 |
| F5 | The code is created and e-mailed on **GET `code/create`** (`getOrCreateCode()`), before any security evaluation. | SR §3 |
| F6 | Process facts on `Code`: `can_vote_now` (code step completed — also overloaded as timer / kill switch), `has_agreed_to_vote`, `has_voted` (authoritative participation). `store()` does not read `can_vote_now` or `has_agreed_to_vote`. | SR §4, §4a; PBDIGIT-231 |
| F7 | Final-vote defects: no DB guarantee against double voting (cache lock + re-read), denial branch `TypeError` → 500, ignored / crashing pre-check, unset `MAX_USE_IP_ADDRESS` blocks every vote. | PBDIGIT-230, 232, 233 |
| F8 | Admission policy is locked from ReadyForVoting (handler + model guard). For election **settings** (incl. `voter_verification_mode`, `ip_restriction_*`, `ip_whitelist`), `ElectionSettingsController::update` shows only optimistic locking; no lifecycle lock was found in the controller or its route line (INFERENCE — outer route group to be verified). | ADR_20261004_1549; `ElectionSettingsController.php:59` |
| F9 | Demo elections bypass entitlement (`EnsureElectionVoter`, `VoteEligibility`) and are excluded from the trust gate. | SR §2; `VoteController.php:1522-1523` |

## 2 · Decision drivers

1. One authoritative answer per question (no gate engine, no duplicated predicates in middleware and controllers).
2. Fresh at every authority boundary; nothing persisted as "authorized".
3. Fail closed: a denial *or an error* never writes a vote.
4. Keep process state (code step, agreement, participation) separate from authority.
5. Admission evaluated once (inside entitlement).
6. No new voter ↔ ballot correlation path (interim anonymity rule).

## 3 · The boundary (recommended design)

### 3.0 Four distinct concepts

| Concept | Question | Mechanism | Nature |
|---|---|---|---|
| **Authorization** | *May this voter participate / cast now?* | `VotingEntitlement.decide()` (standing incl. admission, lifecycle) + the security role | derived, fresh |
| **Process validation** | *Is this voting attempt / process state valid?* | code value matches, code step completed, agreement (Q5) | derived from process facts |
| **Atomic participation consumption** | *Has this voter's participation already been consumed?* | compare-and-set `codes.has_voted` false → true | **database transition** — not a decision |
| **Ballot integrity** | *Is the content of this cast act valid according to the voter's authoritative ballot and all applicable ballot-integrity rules?* | pure evaluation `SubmittedBallot` × `AuthoritativeBallot` × ballot-integrity rules → `ValidatedBallot` \| `BallotRejection` | derived, fresh, at the final cast |

> Ballot integrity is evaluated **beside** `forCast()`, not inside it: `forCast()` answers *may this attempt proceed?*, ballot integrity answers *is the content of the act valid?* `CastDecision`, `CastAttempt` and the reason vocabulary are unchanged. `SubmittedBallot` is the untrusted, normalised proposal from the session / request path; `AuthoritativeBallot` is assembled server-side from the election's ballot authority (today the *current ballot assembly authority*, `BallotAssemblyService`; the constitutional regional authority is decided separately — D6 / PBDIGIT-236); `ValidatedBallot` is a **normalized `SubmittedBallot` that has been proven valid against the `AuthoritativeBallot` and all applicable ballot-integrity rules** — membership in the authoritative ballot is necessary but not sufficient (duplicates, maximum per post, abstention exclusivity and permission, not-answered semantics and electability are ballot-integrity rules too; their content lives in the VE-01 decision record, not in this ADR). `BallotValidator` owns **ballot validity**, not merely set membership, and is **domain-pure**: it does not query the database, read the session or request, access `User` or `ElectionMembership`, mutate state, consume participation or persist votes. **CastContext** (Organisation · User · Election · ElectionVoter) is an application / domain **context object** — the coherent snapshot used to evaluate one cast attempt — **not an aggregate**.

`forEntry()` contains authorization only. `forCast()` = fresh authorization **+** process validation; its result is a **`CastDecision`**, not an "authorization". Neither of them decides "already voted": only the compare-and-set does.

**`VotingSecurityDecision` is a conceptual role**, not a new component. Until Q1 is decided it is fulfilled by the existing chain `TrustPolicyEvaluator` → `PolicySequence` → `ConstitutionalLegitimacyDecision` → `LegitimacyOutcome`. **No new parallel security engine or decision class may be introduced**; Q1 may at most rename/relocate the existing chain.

```
                         ┌───────────────────────────── AUTHORITY (decides) ─────────────────────────────┐
Admission (policy+evidence → AdmissionDecision, ALL-of)          ⊂ VotingEntitlement.standing()
VotingEntitlement.decide() = standing ∧ lifecycle                                    (exists, unchanged)
VotingSecurityDecision     = conceptual ROLE of the one security authority, fulfilled today by the
                             existing Trust → Legitimacy chain (no new engine; name/home: Q1)
                         └────────────────────────────────────────────────────────────────────────────────┘
                                         │ composed by
                                         ▼
VotingAuthorization (application service, pure composition, no state)
   forEntry(voter, election, attempt)        →  EntryAuthorization  (authorization only; reason enum)
   forCast (voter, election, attempt, code)  →  CastDecision        (fresh authorization + process validation;
                                                                     reason enum; NOT "already voted")

                         ┌───────────────────────────── PROCESS (records facts) ─────────────────────────┐
Code: code value · can_vote_now (code step completed) · has_agreed_to_vote · has_voted (participation)
                         └────────────────────────────────────────────────────────────────────────────────┘

Flow:  GET code/create ─ forEntry ─▶ getOrCreateCode ─▶ POST code (code entry) ─ forEntry ─▶ can_vote_now
       ─▶ agreement ─▶ ballot ─(advisory forEntry)─▶ POST vote/verify
       ─▶ BEGIN ─▶ forCast (fresh) ─ Allowed? ─▶ ballot validation ─ ValidatedBallot? ─▶ CAS codes.has_voted false→true ─ exactly 1 row? ─▶ INSERT validated anonymous vote ─▶ COMMIT
          (decision)                            (ballot integrity)                     (consumption — outside any decision; 0 rows → reject)
          any denial / rejection / \Throwable ─▶ ROLLBACK (no vote, has_voted stays false)
```
*(The order of `forCast()` and ballot validation shown is the approved implementation direction, not a constitutional rule — see I13.)*

### 3.1 `forEntry()` — *may this voter enter or continue the voting process now?*
- **Inputs:** voter, election, the attempt context (request IP as resolved by trusted infrastructure, device evidence if any).
- **Composition (order fixed, first failure wins):** `VotingEntitlement.decide()` (standing incl. admission, then lifecycle) → `VotingSecurityDecision`.
- **Used at:** GET `code/create` **before** `getOrCreateCode()` (no code issued on denial — F5); POST `code` (code entry) — **replacing** `CodeController::verifyCode`'s own IP count (F4), not added beside it; optionally as an **advisory** check at agreement / ballot pages (UX only).
- **Not used for:** the final decision.

### 3.2 `forCast()` — *is this cast attempt allowed and valid now?* → `CastDecision`
- **Composition:** **authorization predicates** — `forEntry()` evaluated fresh (uncached); **process predicates** — code value matches the submitted code; code step completed (`can_vote_now`); agreement given (`has_agreed_to_vote`) **only if Q5 decides so**; `has_voted = false` as an *early, non-authoritative pre-check* (for a clear message only).
- **Evaluated:** inside the cast transaction, before the compare-and-set.
- **`forCast()` can never decide "not already voted" authoritatively.** Only the database transition can:

```
forCast() → Allowed → CAS has_voted false→true → exactly 1 row?  ── no → reject (already consumed)
                                                                 └ yes → INSERT vote → COMMIT
```
The compare-and-set is **atomic participation consumption** (PBDIGIT-230), not part of any decision.

### 3.3 Reason vocabulary (derived, never stored)
`EntryAuthorizationReason` (authorization only) and `CastDecisionReason` (authorization + process) — one enum family, `Allowed` the only success:
- **authorization reasons:** `Allowed` · forwarded entitlement reasons (`NotRegistered`, `NotVoterRole`, `OrganisationMismatch`, `NotActive`, `NotAdmitted`, `ElectionNotVotable`) · `SecurityDenied` (carrying the security sub-reason, e.g. IP density, IP binding mismatch) · `SecurityReviewRequired` (maps `Deferred` / `Investigate` — fail closed, Q2);
- **process reasons (`CastDecisionReason` only):** `CodeMismatch`, `CodeStepIncomplete`, `AgreementMissing` (only if Q5 = B), `AlreadyVotedPreCheck` (non-authoritative);
- **not a reason of any decision:** "participation already consumed" — the compare-and-set outcome (0 rows), reported by the cast command.

### 3.4 Fail-closed rule
A cast commits **only** on `Allowed` **and** an existing `ValidatedBallot` **and** exactly-one-row compare-and-set **and** successful insert of exactly the validated content. Every other outcome fails closed — rollback, no vote, `has_voted` unchanged, lock released, never an HTTP 500 — with the outcome kept distinct: an authorization denial is a `CastDecision` reason; missing, malformed or invalid ballot content is a `BallotRejection`; an unexpected `\Throwable` is an internal failure (generic refusal, audited). Fixes F7 (PBDIGIT-232/233) belong to Phase 4.

### 3.5 Fresh vs cached
| Decision | Navigation / advisory | `forEntry()` at code create / entry | `forCast()` |
|---|---|---|---|
| Entitlement | cached allowed (model-event invalidation) | **fresh** | **fresh, in transaction** |
| Lifecycle | fresh | fresh | fresh |
| Security | — | fresh | fresh |
| Process facts | — | — | read in transaction; `has_voted` decided by CAS |

## 4 · Decision matrix (options considered)

| Question | Option A | Option B | Recommendation |
|---|---|---|---|
| Where does security sit? | inside `VotingEntitlement` | separate `VotingSecurityDecision`, composed | **B** — entitlement stays standing; security is per attempt |
| How many security authorities? | keep the three IP checks + trust gate | one authority (trust/legitimacy) absorbing the IP checks | **one** |
| Authorization artefact | persisted flag / session marker | derived reason, recomputed | **derived** |
| Entry check placement | middleware only | application service called by the code-entry and code-create steps (middleware may delegate to it) | **service**, single definition |
| `Deferred` / `Investigate` | allow | deny with a review reason | **deny (fail closed)** unless the PO defines a review workflow |
| Code issued before denial | issue then deny later | decide before `getOrCreateCode()` | **decide first** |

## 5 · Invariants

- **I1** `Allowed` is the only reason that permits entry or a cast attempt; it is never persisted. `CastDecision = Allowed` alone does **not** commit a vote — a vote is committed **if and only if** `CastDecision = Allowed` ∧ a `ValidatedBallot` exists ∧ the CAS changes exactly one participation record ∧ exactly the `ValidatedBallot` content is persisted ∧ the transaction commits (§3.4). These are the necessary and sufficient conditions of a successful cast, not merely a sequence of steps.
- **I2** Admission is evaluated once, inside `VotingEntitlement`; no other component restates it.
- **I3** One security authority; no IP / device check outside it.
- **I4** `forCast()` is evaluated in the cast transaction; nothing evaluated earlier authorises the cast.
- **I5** `Code.can_vote_now`, `has_agreed_to_vote`, `has_voted` are process facts; none of them is authority.
- **I6** `Code.has_voted` is consumed by an atomic compare-and-set before the vote insert. For every successfully committed **ordinary** voting transaction, exactly one `codes.has_voted` false → true transition corresponds to exactly one anonymous vote insertion; therefore the committed production voting population satisfies the participation / ballot cardinality invariant (aggregate only, never per code). Demo, test and historical / manual-repair data are outside this statement.
- **I7** A denial or error writes no vote and leaves `has_voted` false.
- **I8** No authorization or security outcome is logged together with a ballot-side identifier (interim anonymity rule).
- **I9** The security policy is immutable from ReadyForVoting (like the admission policy) — the policy boundary itself is Q3.
- **I10** Authorization and process decisions operate entirely on the voter / process side: they do **not** modify the `Vote` aggregate and introduce **no** voter-derived value into the ballot record.
- **I11** Session, request or any other transport data is untrusted input. It may carry ballot state but never establishes ballot validity; validity is established only against the server-side authoritative ballot and the ballot-integrity rules, at the final cast, whatever the transport.
- **I12** Vote persistence (today `save_vote()`) accepts only a `ValidatedBallot`. It never accepts a raw request payload, session ballot, client candidacy ids or client post ids; stored ballot content is derived from validated content only.
- **I13** Authorization (`forCast()` → `Allowed`) and ballot validation (→ `ValidatedBallot`) both complete successfully **before** the participation CAS, and both derive from **one CastContext** (Organisation · User · Election · ElectionVoter). Their relative order is not constitutional.

## 6 · Authority / responsibility model

| Component | Layer | Responsibility | Owns |
|---|---|---|---|
| `VoterAdmissionPolicy` / `AdmissionDecision` | Domain (Elections) | requirements satisfied? | admission semantics (ADR_20261004_1549) |
| `VotingEntitlementPolicy` / `VotingEntitlementQuery` | Domain + port (Elections) | standing + lifecycle | entitlement reasons |
| `VotingSecurityDecision` — **role**, fulfilled by the existing Trust → Legitimacy chain | Application security | is this attempt acceptable? | security policy evaluation (no new engine) |
| Security policy boundary (Q3 — whether the settings form one coherent policy) | to be decided | what security applies | target boundary only; refactoring in a later phase |
| `VotingAuthorization` (`forEntry`, `forCast`) | Application service | compose, order, fail closed | reason vocabulary; **no state** |
| Code process (`CodeController`, `Code`) | voting process | record process facts | `can_vote_now`, agreement, `has_voted` |
| Cast transaction (`VoteController::store`) | voting process | transaction, **atomic participation consumption (CAS)**, anonymous insert | consumes `CastDecision`; owns the authoritative "already voted" |
| `BallotValidator` (`SubmittedBallot`, `AuthoritativeBallot`, `ValidatedBallot`, `BallotRejection`) | Domain (Elections), framework-free, pure (no database, session, request, `User` / `ElectionMembership` access, no state change) | is the submitted content valid according to the authoritative ballot and the ballot-integrity rules? | ballot validity (rules: VE-01 decision record D1–D3, D5, N1–N5) |
| `AuthoritativeBallotQuery` (port) + adapter over the ballot assembly | Application port / infrastructure | obtain / build the authoritative ballot for the CastContext | the source of the authoritative ballot — **no ballot-validity rule** (validation stays in `BallotValidator`); no duplicated assembly rules |

## 7 · Anonymity-boundary assessment (interim rule, 7 points)

| Mechanism | 1 no voter field on `votes` | 2 no joinable voter-side time | 3 no voter+ballot log | 4 no ballot identity in receipt/notification/export | 5 no derivable value | 6 no voter→ballot relation | 7 no A5/A6 change | Verdict |
|---|---|---|---|---|---|---|---|---|
| `forEntry()` (before code creation / at entry) | ✓ | ✓ (no new timestamp) | ✓ if it logs only voter-side data | ✓ | ✓ | ✓ | ✓ | **passes** |
| `forCast()` → `CastDecision` (in transaction) | ✓ | ✓ | ✓ **only if** its reason is not logged with the vote id | ✓ | ✓ | ✓ | ✓ | **passes, with I8** |
| Reason enum (derived) | ✓ | ✓ | ✓ (I8) | ✓ | ✓ | ✓ | ✓ | **passes** |
| CAS on `codes.has_voted` (Phase 4) | ✓ | ✓ **only if** no new voter-side timestamp is written (existing `code_to_save_vote_used_at` untouched — A2 stays as is until 2.0) | ✓ | ✓ | ✓ | ✓ | ✓ | **passes, with constraint** |
| Security-policy boundary + lock (Q3) | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | **passes** |
| IP evidence in `VotingSecurityDecision` (IP density today) | ✓ | ✓ | ✗ today: the cast already logs vote id + IP (A1) | ✓ | ✓ | ✓ | ✓ | **no new path**, but must not add new IP logging |
| IP binding (Phase 7) | ✓ | ✓ | ✗ stores voter-side IP while A1 pairs vote id + IP | ✓ | ✓ | ✗ (voter IP ⋈ logged ballot IP) | ✓ | **fails today → blocked until PBDIGIT-225 / Phase 2.0** |
| Ballot integrity (`BallotValidator` at the final cast) | ✓ no voter field written | ✓ no new timestamp | ✓ **only if** a rejection reason is not logged with a ballot-side identifier (I8) | ✓ | ✓ the validated ballot contains only election-side ids | ✓ reads voter-side context (region, membership) to assemble the ballot; writes nothing voter-derived to the ballot (I10) | ✓ | **no new anonymity linkage is introduced by this design, provided rejection / audit telemetry obeys I8 and carries no voter ↔ ballot correlation** — an architectural constraint; compliance is proven by implementation evidence at GREEN |

*Inherent and unchanged:* regional posts in a ballot reveal the voter's region, as today; revision 3 adds no new disclosure.

## 8 · Consequences for Phases 4–8

| Phase | Consequence |
|---|---|
| 4 Final-cast hardening | implement §3.2–3.4: `forCast()` in the transaction, CAS before insert (outside the decision), fail closed (PBDIGIT-230, 232, 233); no new voter-side timestamp (§7); `has_agreed_to_vote` only per Q5; **no silent change to demo behaviour (Q4)** |
| 5 Entry authorization | `forEntry()` before `getOrCreateCode()` and at code entry; remove the duplicate IP check in `verifyCode` (F4); **no silent change to demo behaviour (Q4)** |
| 6 IP prerequisites | trusted proxies, normalisation, IPv6, matching rule; implement the security-policy boundary **as decided in Q3**, with its lock (I9) |
| 7 IP binding | blocked until the §7 assessment passes (PBDIGIT-225 / Phase 2.0) |
| 8 Device binding | deferred; would be one more policy inside the single security authority |
| Phase 2.0 | unaffected: this ADR adds no ballot-side data and does not choose Model A / B |
| VE-01 (after Phase 4) | ballot integrity at the final cast per I1, I11–I13; `forCast()` and CAS unchanged; demo scope: **real elections only — demo-election behaviour unchanged (S2, PO / ARB 2026-10-05)**; separate GREEN authorisation (PBDIGIT-237) |

## 9 · Open questions (PO / ARB)

- **Q1** Name and home of the security authority: keep *Constitutional Trust / Legitimacy* or rename to *VotingSecurity* (rename only, no parallel class).
- **Q2** `Deferred` / `Investigate`: deny (recommended) or define a review workflow?
- **Q3** Do the security settings (`voter_verification_mode`, `network_binding_strategy`, `max_votes_per_ip`, `ip_restriction_*`, `ip_whitelist`, `ip_mismatch_action`, `voting_ip_mode`) constitute **one coherent security policy**? If yes, define the **target policy boundary** (and its lock from ReadyForVoting, I9). Creating or refactoring a policy object is **not** part of Phase 3; it belongs to the relevant later phase (6). (F8 suggests settings are not lifecycle-locked today — verify.)
- **Q4 — security-scope decision (not a minor question):** demo elections today bypass entitlement and the trust gate (F9). Exclude demo from `forEntry()` / `forCast()` (today's behaviour) or apply a demo-specific policy? **Rule until decided: no Phase 4 / 5 implementation may silently change demo-election behaviour.**
- **Q5 — explicit behavioural decision:** **Option A** — agreement is merely a UI / process step (today: `store()` does not check it). **Option B** — agreement is a mandatory precondition for casting → it becomes a `forCast()` process predicate (behaviour change, own tests). Not to be decided implicitly by implementation.
- **Q6** Middleware: keep `ensure.election.voter` as cached navigation guard and have it delegate to `VotingAuthorization`, or move entry checks into the controllers' calls only?
- **Q7** PBDIGIT-231 (`can_vote_now` preserved on regeneration): fold into Phase 5 (code entry) or keep frozen?

## 10 · Recommendation

Adopt §3 as the target boundary: one stateless `VotingAuthorization` composition — `forEntry()` (authorization) and `forCast()` (fresh authorization + process validation → `CastDecision`) — over the existing decisions; one security authority as a **role** of the existing Trust → Legitimacy chain; a derived reason enum with `Allowed` as the only success; atomic participation consumption by a compare-and-set on `codes.has_voted` **outside** any decision, before the anonymous insert; fail-closed casting; the security policy locked from ReadyForVoting once its boundary is decided (Q3). Phase 7 stays blocked per §7.

**After PO / ARB approval of this ADR, implementation still requires separate authorisation per phase (4, 5, 6) and per production edit group**, with the answers to Q1–Q7 and the anonymity-boundary checklist applied to each change.

**Traceability:** roadmap Phase 3 · SR rev 9 · Phase 1 evidence (Gate 1 ACCEPTED) · ADR_20261004_1549 · PBDIGIT-223 … 233 · `tests/Feature/Election/VotingProcessEntitlementBoundaryTest.php` · `tests/Feature/Characterization/Phase1/` · VE-01: PBDIGIT-237, design proposal `2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md`, gate report §15–§18, `tests/Feature/Voting/FinalCastBallotInvariantTest.php`, revision-3 decision record `docs/publicdigit/reviews/2026-10-05-adr-revision-3-po-arb-decision-record.md`.
