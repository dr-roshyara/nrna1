# Handover — Session 2 → Session 3 · Election-Only admission

**Type:** Cross-stream handover · **Date:** 2026-08-12 · **From:** Session 2 (governance) · **To:** Session 3 (implementation)
**Status:** 🟡 **READY WITH ONE EXPLICIT BLOCKER — `BR-1.12`**
**⛔ NO IMPLEMENTATION IS AUTHORISED BY THIS DOCUMENT.** It transfers authority and questions; it does not authorise changes.
**Basis:** [`Election-Only Admission Governance Gate`](2026-08-12-election-only-admission-governance-gate.md) · [`Session 3 readiness report`](2026-08-12-session-3-governance-readiness-report.md) · [`Election-Only-first baseline`](2026-08-12-election-only-first-governance-baseline.md)

---

## A · AUTHORITATIVE — adopted business rules Session 3 may rely on

| # | Rule |
|---|---|
| **A-1** | **`ElectionMember` ≠ `Organisation Member`** — different concepts |
| **A-2** | **`ElectionMember` is election-specific** |
| **A-3** | **`Organisation Member` is organisation/tenant-specific — and it means the `Member` aggregate** |
| **A-4** | **Election-Only admission does NOT create Organisation Membership** *(`Q-B1`, closed)* |
| **A-5** | **`ElectionMembership` does not imply Organisation Membership** |
| **A-6** | **Removing `ElectionMembership` must NEVER remove Organisation Membership** |
| **A-7** | **Existence ≠ exercisability** — the entitlement's existence is distinct from whether it may be exercised now |
| **A-8** | **Full Membership Mode is DEFERRED** — a separate later phase |

**Vocabulary, authoritative:**

| Term | Means | **Does NOT mean** |
|---|---|---|
| **Organisation Membership** | the **`Member`** aggregate | `organisation_users`; `user_organisation_roles` |
| **`ElectionMembership`** | election-specific participation | organisation membership of any kind |
| `organisation_users` | technical organisation/tenant association *(unless separately established otherwise)* | Organisation Membership |
| `user_organisation_roles` | organisation **role assignment** *(unless separately established otherwise)* | Organisation Membership — **the literal role value `'member'` is NOT the `Member` aggregate** |

> **Conformance status against `A-4`: the system CONFORMS.** Measured on the live Election-Only election: **`members` = 0 of 5 voters.**

## B · IMPLEMENTATION CONFORMANCE QUESTIONS — Session 3 may investigate these independently

**These are yours to investigate. None is a business question, and none has a business answer waiting.**

| # | Question | What is already measured |
|---|---|---|
| **B-1** | **Why does Election-Only admission create `organisation_users` rows?** | **5 of 5** created. `ElectionOnlyPolicy` declares *"User must be in `organisation_users`"* — a **prerequisite** — while the import **creates** it. The May-2026 migration's WARNING requires application code to **validate** it for Election-Only |
| **B-2** | **Why does admission create `user_organisation_roles` with `role='member'`?** | **5 of 5** created. ⚠️ **The May migration's stated premise is that Election-Only users have `OrganisationUser` but NOT `UserOrganisationRole` — so these rows contradict the documented design intent** |
| **B-3** | **Are those two rows technical tenant/role associations rather than Organisation Membership?** | **`SEMANTIC NOT ESTABLISHED`** — no authoritative source defines the `'member'` role's semantics. **Per §A the business answer is that neither is Organisation Membership; whether they are technically necessary is yours** |
| **B-4** | **Is the admission-time validation actually wired?** | ⚠️ `VoterQualificationPolicy` — the successor the May migration designates — **does not exist**. The validation appears in `EloquentVoterEligibilityQueryService` (*"queries `organisation_users` table only"*) and `VoterEligibilityService`; **but the domain policies `ElectionOnlyPolicy::isEligible()` / `FullMembershipPolicy::isEligible()` are stubs returning `true`.** **Whether the real path is reached is `MECHANISM NOT ESTABLISHED`** |
| **B-5** | **Is the absent FK a problem?** | ❌ **No — closed.** Deliberately dropped in `2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` *because* it blocked Election-Only. **Integrity was explicitly transferred to the application layer. Do not restore it** |

**Constraint on B-1…B-4: investigating is permitted; changing behaviour is a separate authorisation.**

## C · STILL UNRESOLVED BUSINESS DECISION — `BR-1.12`

> **In Election-Only Mode, when the Election Chief imports a person as an ElectionMember, is that ElectionMembership immediately `ACTIVE`, or initially `INVITED` requiring explicit Election Chief approval before becoming `ACTIVE`?**

**`BUSINESS RULE NOT SPECIFIED.` Awaiting Product Owner / ARB.**

| | **Option A — `ACTIVE`** | **Option B — `INVITED` → approval → `ACTIVE`** |
|---|---|---|
| Evidence | **production only** — import writes `'active'`; assignment uses the model default; **no production path writes `'invited'`** | **schema · UI · tests · documentation (×2)** |

> 🔴 **Session 3 MUST NOT resolve `BR-1.12`, and must not resolve it implicitly by building whichever option the current code already does.** **Production is the only evidence for Option A** — so *"we kept existing behaviour"* would be a business decision taken by default. **If the decision is outstanding when admission work begins, stop and report.**

## D · NOT AUTHORIZED

**No production changes · no schema changes · no migration changes · no test changes · no Full Membership work · no suspension work · no credential changes.**

Additionally: do not restore the dropped FK · do not create `Member` rows · do not activate `FullMembershipPolicy` · do not implement `scopeEligible()` *(it returns **0 of 20** and would deny every voter)* · do not rename or remove the `'member'` role value · do not amend `ADR-002` or the Constitution · do not promote the Officer Guide.

## E · The mode boundary — the one thing most likely to trip this phase

**Measured: `VoterSourceStrategy` is consulted at ADMISSION ONLY.** In `ElectionVoterController` it appears at lines **74, 113, 126, 157** — all admission. `suspend`, `approve`, `propose/confirmSuspension`, `cancelProposal`, `destroy` consult it **nowhere.**

| | |
|---|---|
| ✅ **Separable by construction** | Admission paths — the mode is already a parameter. **The gate and the seam coincide for this phase** |
| 🔴 **Mode-blind — a change here defines Full Membership too** | `User::isVoterInElection()` · `EnsureElectionVoter` · `VoteEligibility` · `ElectionVotingController::show/start` · `ElectionMembership`'s own methods · the `EnsuresVoterMembership` trait *(3 consumers: `OrganisationController`, `CodeController`, `VoteController`)* |

> **HARD STOP:** **if a change would simultaneously define Full Membership semantics — STOP · REPORT THE CROSS-MODE IMPACT · DO NOT IMPLEMENT.** *(For admission this should not trigger. It will trigger on suspension or removal work, which is out of scope for this phase.)*

**Guardrails that need no decision but must hold:** `status='inactive'` must not be vacated before a replacement enforces *(it is currently the only thing preventing a suspended voter from voting)* · **`ADR-T11`** — nothing may identify or mutate a cast ballot · **`ElectionUser` must not be cited as prior art** — a dead legacy model built on the retired `is_voter` flag, now parseable, which makes the mistake easier.

## F · Deferred, and not to be treated as dependencies

`BR-1.13` suspension actor · `Q3` exercisability · `Q-E1` credential control · `Q-E2` distinguishability · `BR-1.1`/`1.2` removal · `BR-1.8` restoration · **`FM-1`…`FM-15` Full Membership.**

**No dependency on any of these was found for admission.** *(`BR-1.13` was previously mis-classified by me as blocking any Election-Only work; corrected — it blocks the suspension slice only.)*

## G · Session separation

* **Session 1** owns independent verification / the Master Matrix. **Session 2 has not read, classified, consumed or modified it**, and cites no row count as fact.
* **Session 2** owns governance interpretation, business-decision preparation and authority clarification. **It does not implement.**
* **Session 3** owns implementation. **This handover confers no authority to change business rules**, and **no implementation finding in §B is a business rule.**

---

**Traceability:** `database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php:8-29,39-43` · `database/migrations/2026_03_17_213212_create_election_memberships_table.php:25,36-45` · `app/Contexts/Elections/Domain/Policies/{ElectionOnlyPolicy,FullMembershipPolicy}.php` · `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:23,91,146` · `app/Services/{VoterEligibilityService,VoterImportService}.php` · `app/Http/Controllers/ElectionVoterController.php:74,113,126,157` *(mode-aware)*, `:171,196,222,290,330` *(mode-blind)* · `app/Models/ElectionMembership.php:67` · `app/Models/User.php:315-328` · `app/Traits/EnsuresVoterMembership.php` · measured 2026-08-12: `members` **0 rows** · `organisation_users` **5/5** · `user_organisation_roles` **5/5 (`role='member'`)** · **no production writer of `invited`** · `VoterQualificationPolicy` **does not exist**.
