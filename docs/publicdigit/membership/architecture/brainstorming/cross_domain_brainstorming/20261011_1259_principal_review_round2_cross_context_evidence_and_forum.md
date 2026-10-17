# Principal Reviewer Round 2: cross-context evidence check and Discussion Forum model

**Date:** 2026-10-11 · **Author:** Claude (independent review; research only)
**Status:** RESEARCH. Authority: generated. Not ratified; decides nothing.
**Supersedes nothing.** It reviews `20261011_0900_cross_context_consistency_and_simulation_study.md`.
**Conduct:** the review itself was read-only. No production code, Membership code, migration, test or database was touched. This file was written afterwards, at the user's request, as the record of the review.

**Evidence labels:**
- `[S]` verified from current source;
- `[X]` verified by execution;
- `[P]` previous study, not re-verified;
- `[I]` inference;
- `[R]` recommendation;
- `[D]` open decision.

---

## A. Executive decision

**The architecture is not ready for integration.**

**The immediate blocker** is three missing decisions, which no further research can supply:
- who owns committee facts (DR-07);
- one shared person identifier (XD-3);
- the business rule linking Membership to committee tenure (BQ-P2/P3).

How often the contradictions occur could not be measured: no authorised read-only database exists, so every prevalence count is UNKNOWN.

This round changes three earlier conclusions:
- The earlier study recommended reusing `committee_structures` / `committee_structure_levels` for the committee-type ↔ geographic-level mapping. That is **weakened**. The tables exist, but their only writer has no route, the role limits it saves are always empty, and no ADR assigns ownership.
- **There is no forum in the code.** The only discussion feature is a legacy, unscoped "Openion" (opinion) wall with an **IDOR delete flaw**. The forum architecture documents describe a "Collective Intelligence Platform", not a forum. Which product we are building is an open decision.
- **g14 S2 overstates the benefit of the "agree or queue for review" policy.** If source errors are correlated rather than independent, its advantage over "most recent wins" largely disappears.

---

## B. Research baseline and evidence quality

**Baseline:**
- HEAD: `71e5a931e`.
- Working tree: 144 entries, all pre-existing. Not touched.
- They include another session's untracked Membership Slice 2a work: `InterimRoleAuthority.php`, `OrganisationRoleReader.php`, and plan / backlog PBDIGIT-271/272.

**What was executed:**
- `python3 -I g14_cross_context_consistency.py`, which only prints. The output matches the study exactly.
- One in-memory Python stress test of S1 and S2. It wrote no file.
- Two independent read-only reviewers (C1–C12 at HEAD; Forum inventory).
- Their key claims were spot-checked in person: the C9 width, the C11 index and `Committee.php:774`, the C12 mapping, the Openion IDOR, and the migration registration.

**What was not executed:**
- No database connection, test, migration or artisan command.

**Limits:**
- Migration source does not prove the deployed schema.
- Several CHECKs and partial indexes only apply under `pgsql` / `tenant_test`.
- **New:** `app/Contexts/Geography/Infrastructure/Database/Migrations/Tenant` is **never registered**. `AppServiceProvider.php:406` loads the parent folder, which has no files, and `loadMigrationsFrom` does not recurse; `GeographyServiceProvider.php:86` loads only `Landlord`. The Tenant folder's `admin_level` 0–10 CHECK, `landlord_geo_id` and `is_official` therefore never run from source.

---

## C. Independent review of the previous study

| Finding | Verdict |
|---|---|
| C2, C3, C4, C5, C7, C8, C9, C11 | **Confirmed** at HEAD `[S]` |
| C1 | Confirmed, with nuances `[S]`: `type` was created NOT NULL, then made nullable (`2026_05_13_143633…:15`). The VO values (`youth_wing`…) differ from the migration comment (`youth`, `women`). |
| C10 | **Corrected: worse than reported.** Besides the four numberings, `StoreCommitteeRequest:36` writes `committees.level` as `governanceLevel` 0–100. `governance_level_definitions.level` is used directly as `admin_level` (`GovernanceGeoUnitQueryService.php:240-245`). The Tenant geography numbering is not loaded at all. |
| C12 | **Corrected: latent, not active.** No code in `app/` sets `is_committee_member` true; only a diagnostic command writes `false`. The flag → `commission` → `dashboard/commission` path exists (`User.php:1098-1101`; `routes/web.php:489`; `CheckUserRole.php:60,89-90`), but it is reached only through legacy data or a future writer. |
| C6 | **Extended.** ADR-008 (Accepted, 2026-05-11) promises `GeoUnitMoved`, `GeoUnitDeactivated` and `GeoUnitValidityChanged` events and a `GeographyHierarchyDomainService`. None exist; the service name appears only in a comment (`GeoAdministrativeUnit.php:21`). Accepted, not implemented. |
| C9 | **Extended.** The mismatch is inside a single write path, not only between stores. `CommitteeMemberController.php:30` validates a **user** id (`uuid|exists:users,id`), and `AssignMemberToCommitteeHandler.php:60` then looks that id up in **members**. That path cannot work for any input unless a user id equals a member id `[I]`. |
| "Reuse `committee_structures`" | **Weakened.** The only writer, `GovernanceSetupController` (via `DefineCommitteeStructure`), is unrouted. Its form has no `roleLimits` field, so `role_limits` is always `[]`. The ADR mention is indirect (`docs/publicdigit/adr/ADR_COMMITTEE_CREATION_DUAL_PATH.md:60`). This is a candidate home, not an established one. |
| Representation counts ("9 / 11 / 6 …") | **Rejected as semantic counts.** They count columns and classes, not distinct meanings. Recount by meaning: committee type has **3** independent classifications (string + VO; structure level; governance level definition), plus a duplicate vocabulary and derived snapshots. Committee geography has **one** meaning with three competing writers, plus caches. Assignments have **three different meanings**: application association; role-holding with dates; Governance roster keyed by `members.id`. |
| "OrganisationRoleReader unimplemented" | **Still true, with context.** It is an interface only, in another session's untracked Slice 2a work. Its doc says it is deferred to Slice 2b. Not touched. |
| g14 S1–S4 numbers | **Reproduced exactly** `[X]`. The interpretation is corrected in §E. |

---

## D. C1–C12 evidence matrix

Legend:
- **Kind:** D = data contradiction; S = schema / design contradiction; N = naming.
- **Enforced:** DB = the database enforces it; App = application code only.

| ID | Kind | Representable or reachable? | Detectable without an unapproved business assumption? | Owner (approved?) | Smallest safe corrective direction |
|---|---|---|---|---|---|
| C1 | S + D | Yes. Free string. CHECKs (pgsql only) cover only central / ward / district / province; `geographic` and the wing types escape them. | **No.** It needs a type ↔ level mapping. | Unapproved (DR-07; structure ownership) | Decide the mapping owner first; then a read-only detector |
| C2 / C7 | D | Yes. The FK is id-only (`2026_05_13_000001…:16-19`). Unscoped `exists` checks are at `StoreCommitteeRequest:37`, `GeoUnitController:96,106`, `MemberGeographyController:74` and `MemberImportService:65`. `GovernanceGeoUnitQueryService` filters geography by level / active / country only. | **Partly.** A unit owned by a *different* organisation is detectable. A unit with `organisation_id` NULL is **not proven safe**: NULL could mean "official" or "unowned legacy"; `is_official` exists only in the unloaded Tenant migration. | Tenant isolation (platform principle); geography sharing undecided | Scope the `exists` rules to `{own organisation, approved official}` (PBDIGIT-266 territory) |
| C3 | D | Yes. No FK, by design (M `…assignments…:79-80`). Legacy `Member::endMembership` (`app/Models/Member.php:172-190`) and `ExpireMemberships` do not touch committees; neither does the kernel `Organisational/Member::decideEnd`. | Detectable only after the person join is resolved (C9) and the rule is decided | **Open** (BQ-P2/P3) | Decide the rule; derive effectiveness, never cascade-delete |
| C4 | S + D | Yes. Three stores with different meanings and keys (see §C). | Disagreement is detectable. **Whether it is an error is not**: the stores mean different things, and associations have no end date. | **Open** (DR-07) | One authoritative writer; the other stores become derived |
| C8 | D (bug) | Yes, reachable on every save. `saveForTenant` (`Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:142-149`) runs `withoutGlobalScopes()` and overwrites `left_date = now()` on **already ended** rows (`findForTenant` loads only active rows, :52-54). | Partly. A rewritten date is indistinguishable from a real one without audit history. | Committee repository (code bug) | Restrict the update to rows that are active at load time (backlog item; not this round) |
| C5 | S | Yes. `manageMembers` (`MemberController.php:309,338`) and `can:admin` (`routes/election/electionRoutes.php:137`) are undefined (no `Gate::define`; `AuthServiceProvider.php:15-19`). Roles are free strings. Spatie teams are off (`config/permission.php` has no `teams` key). | Yes, statically | Authorization ownership open (XD-4) | Define or remove the undefined abilities; position ≠ permission |
| C6 | S | Yes. In-place updates with an optimistic `version`; hard delete; `parent_id` cascade delete. `valid_from` / `valid_to` are never used in queries. | **No**, because history does not exist | Geography (ADR-008 Accepted, not implemented) | Implement ADR-008's events / revisions before jurisdiction relies on history |
| C9 | S + D | Yes. `varchar(26)` (M `…assignments…:45`) vs a 36-character UUID user id vs a `members.id` lookup. The projection holds `members.id` (`Api/Governance/CommitteeMemberController.php:130-132,164`). | Format and existence checks: yes. Mapping to one person: **no** (needs XD-3). | **Open** (XD-3) | PersonRef decision; until then, never join across stores on `member_id` |
| C10 | S | Yes. 5+ numbering schemes. | **No.** Meaning is not data. | Geography + Governance (open) | One canonical scale; explicit mapping; never compare integers across scales |
| C11 | S | The DB index caps every `role_path` at 1 (M `…assignments…:112-116`, pgsql). The limits are keyed by **names** (`CentralCommitteeStructure.php:36-44`, e.g. `vice_chairperson => 2`), but `validateRoleLimits` looks them up by path (`Committee.php:774`), and `RolePath` accepts only digits (`RolePath.php:66`). The limits never apply. | Yes, statically | Open (BQ-P1) | Decide κ (role capacity) per position; then align the index and the limits |
| C12 | S (latent) | Representable; no live writer | Yes (count flagged users) | Product / security | Remove the flag → commission mapping (decision-sheet A-5) |
| **F-1** (new) | Security | **Openion delete IDOR.** `OpenionController.php:247-255` compares `Auth::user()->id` with a **client-supplied** `openion.user.id`, then deletes the client-supplied `openion.id`. Any verified user can delete any opinion. `GET /openions` is public and unscoped (`routes/openion/openionRoutes.php:16-19`). | n/a | n/a | Security backlog item (PBDIGIT-266 family). The `openions` table is created only in **backup** migration folders, so whether it is deployed is UNKNOWN. |

**Level numberings (C10) `[S]`:**

| Source | Numbering |
|---|---|
| Landlord geo create (`…000002:28`) | 1 = province, 2 = district, 3 = local, 4 = ward |
| Landlord extension (`2026_01_14_000003…:40`) | 0 = continent, 1 = country, 2 = province, 3 = district, 4 = local, 5 = ward … 10; `CHECK BETWEEN 0 AND 10` |
| Tenant geo (**not loaded**) | 1–4 official, 5–8 custom |
| `organisations.geographic_levels` default | `db_level` 1 = Province … 4 = Ward |
| `committees.level` comment (M `…000002:49`) | 1 = central, 2 = province, 3 = district, 4 = ward |
| `committees.level` as actually written | `governanceLevel` 0–100 (`StoreCommitteeRequest:36`) |
| `governance_level_definitions.level` | 0–10, used directly as `admin_level` |

---

## E. Simulation verification (g14)

### S1: Membership vs assignment

**State and enumeration:**
- State: `(member, held, end_live)`.
- 5 events; all sequences of length 1–5, 3,905 = Σ5^k ✓.
- Events that don't apply in the current state are no-ops: END without membership, ANNUL with no live End.

**Confirmed:**
- R1 checks only at appointment and leaves 161 violating sequences.
- R2 (check plus a cascading vacate) loses tenure in 19 sequences. Re-counted strictly, flagging only when the annulled End *itself* cascaded: still **19** `[X]`.
- R3 (derived) keeps the stored assignment unchanged; only effective eligibility changes.

**Defects in interpretation:**
1. **R3's "0 violations" is true by definition.** Effective = held ∧ member, so `A_eff ⇒ M` holds automatically. The real evidence is the *0 history loss*.
2. R3 has **no appointment check**, so 1,913 sequences store appointments of non-members `[X]`. The meaningful candidate, check at appointment + derived effect (R1+R3), was **not modelled**.
3. Under R3, a tenure **comes back** when the person is re-admitted without being re-appointed: 36 sequences `[X]`. Whether re-admission restores office is a **business decision** `[D]`.
4. Not modelled: suspension, annulment of the *Admission*, corrections, future-dated facts, several persons or positions, concurrency.

**The model cannot decide whether a position requires current Membership** (advisers, honorary seats). That is BQ-P2/P3.

### S2: reconciliation of the duplicate stores

**Policy definitions:** checked and correct.
- P1 = maximum `updated`.
- P2 = first present source in the order B > A > C.
- P3 = accept only when every present source agrees; otherwise queue.

**Defects and assumptions:**
1. **Errors are independent across sources.** The in-memory stress test shares staleness across sources with probability ρ (the variant consumes randomness differently, so ρ = 0 is close to, not identical with, g14) `[X]`:

   | P3 silent errors (low / med / high noise) | |
   |---|---|
   | ρ = 0 | 40 / 448 / 1,706 |
   | ρ = 0.5 | 540 / 1,682 / 3,535 |
   | ρ = 1 | 990 / 2,784 / 5,300 |
   | g14 P1 "most recent wins", for comparison | 1,191 / 3,514 / 6,769 |

   **P3's advantage is mostly an artefact of the independence assumption.** The residual risk when all sources agree but all are wrong is the dominant risk under correlation.
2. Under independence, most of P3's residual errors come from **single-source** auto-accepts: 342 / 448 (medium), 1,274 / 1,706 (high). Requiring at least two sources, or queuing single-source rows, is the obvious refinement `[R]`.
3. `updated` is random and unrelated to truth, so P1 is effectively a random pick. The C8 rewrite actually makes B *newer and wrong*, which is not modelled.
4. **The model does not match the real stores:**
   - associations (A) have **no end date**, so "agreement on `left_date`" is unattainable;
   - the projection (C) hard-deletes rows, so a *missing* row in C carries meaning;
   - the identity mismatch (C9) means no reliable pairing key exists yet.
5. The 50 % active prior is arbitrary. Rows where all sources are missing are silently skipped (25 at low noise).

**Conclusion:** the qualitative claim, "latest-wins and fixed priority silently discard evidence", is supported. **No migration policy should be chosen from these synthetic rates.**

### S3: detectability

**Counts:** 6 types × 4 levels × 3 relations = 72, minus 12 (no unit referenced, so the relation doesn't apply) = **60 ✓**. 18 = 6 × 3 × other-organisation ✓.

**Defects:**
- The type list (`national`, `wing`, `disciplinary`) **does not match the code vocabulary** (central, three wings, diaspora, `geographic`). Disciplinary committees don't exist in code.
- The levels omit continent and country.
- So **18 / 60 is an artefact of the chosen enumeration**. Only the qualitative split is valid:
  - an organisation-boundary check needs only identifiers plus the tenant-isolation principle;
  - type ↔ level compatibility needs an approved mapping.
- Counting "official_shared" under "needs a decision" is correct, because NULL organisation ≠ safe (§D, C2).

### S4: geography change

**Verified**, but it is a single two-place example, not an enumeration. MOVE: SUCCESSOR = FOLLOW_ID, so the comparison is trivial.

**Missing cases:**
- splits (1 → N) and merges (N → 1), which make "successor" a relation rather than a function;
- multi-step chains;
- **cyclic or missing successors**;
- revisions that are pruned or unavailable;
- a decision whose revision cannot be reconstructed, which should return RECONSTRUCTION_INCOMPLETE.

**An extension is warranted `[R]`.** The "SNAPSHOT for history + SUCCESSOR for current coverage" recommendation stands, but only for 1:1 retirements.

---

## F. Legacy-data profiling

**Not executed. All counts are UNKNOWN.**

No authorised read-only data source exists:
- ARB-SEC-01 forbids the shared `nrna_test`;
- no read-only production or replica connection has been granted;
- connection definitions do not imply permission.

**Prerequisites:**
1. A named database (a snapshot or read replica) with written PO / ARB authorisation.
2. A read-only role (`default_transaction_read_only = on`, SELECT-only grants).
3. `statement_timeout` ≤ 5 s and `lock_timeout` = 100 ms.
4. Confirmation of the database identity (`current_database()`, `pg_is_in_recovery()`), without printing credentials.
5. A check that the deployed columns match the templates (`information_schema.columns`).
6. Aggregate output only.

**Query templates** (SELECT-only; to be run inside `BEGIN READ ONLY; … ROLLBACK;`):

| ID | Predicate (template) | Counts reported |
|---|---|---|
| C2 | `SELECT count(*), count(DISTINCT c.organisation_id) FROM committees c JOIN geo_administrative_units g ON g.id = c.geo_unit_id WHERE g.organisation_id IS NOT NULL AND g.organisation_id <> c.organisation_id` (repeat with `operational_geo`) | committees, organisations |
| C2-null | the same, with `g.organisation_id IS NULL`: reported as **unclassified**, not as safe | committees |
| C7 | the same join for `members.residence_geo_unit_id` and `users.residence_geo_unit_id` against each person's organisation (users have many organisations, so use `EXISTS`, not a join, to avoid multiplication) | distinct persons |
| C1 | `SELECT type, level, g.admin_level, count(*) … GROUP BY 1,2,3`: a **distribution only**; no violation count until a mapping is approved | combinations |
| C3 | **Unresolved** until XD-3: there is no safe join from `committee_assignments.member_id` to `members` | — |
| C4 | per committee: distinct-key counts in A / B / C with `FULL OUTER JOIN` on `(committee_id, member_id)` **within stores that share an id kind only** (A ↔ B are user ids; C uses `members.id`) | committees, keys |
| C8 | `SELECT count(*) FROM committee_assignments WHERE is_active = false AND left_date >= updated_at - interval '1 second'` (a heuristic only; candidates, not violations) | rows |
| C9 | `SELECT length(member_id), count(*) FROM committee_assignments GROUP BY 1`, plus `EXISTS` in users vs members | rows by format |
| C10 | `SELECT admin_level, count(*) FROM geo_administrative_units GROUP BY 1`; `committees.level` distribution | distributions |
| C11 | `SELECT committee_id, role_path, count(*) FROM committee_assignments WHERE is_active GROUP BY 1,2 HAVING count(*) > 1` (should be 0 if the index exists; check `pg_indexes`) | pairs |
| C12 | `SELECT count(*) FROM users WHERE is_committee_member` | users |
| F-1 | `SELECT to_regclass('openions')` (does the table exist?) | yes / no |

For C4 / C8, the review queue may be estimated **only** from observed rows, classified as:
- **agree**;
- **explainable transition**, e.g. an A status of `terminated` with an ended B row;
- **conflict**, which goes to a human;
- **unpairable**, blocked on identity (C9).

A zero count never proves an invariant is enforced.

---

## G. Discussion Forum architecture

**Current implementation `[S]`:**
- **No forum context exists:** no tables, aggregates, policies or tests.
- **Legacy "Openion" wall:** `app/Models/Openion.php`, `app/Http/Controllers/OpenionController.php`, `resources/js/Components/Discussion/*`.
  - Opinions are tied to users only. They have no organisation, committee or geography, no moderation, no audit, no idempotency, no transaction, and are cascade-deleted with the user. Plus F-1.
  - The Committee "Activities" and "Calendar" pages are static, with no committee scoping (`CommitteeController.php:87-96`).
- `MemberStatus::canParticipateInForums()` (`MemberStatus.php:216`) has **zero callers**.
- **Documentation is split:**
  - `docs/architecture/members-forum-context/` explicitly rejects "a forum (like Reddit, Discourse, phpBB)" in favour of a *Collective Intelligence Platform* (Problem / Idea / Decision). Its implementation planning is blocked by 5 gaps, and several files its index lists don't exist.
  - The cross-domain package v1.5 (CANDIDATE) proposes a Communication context with Forum / Topic / Post.
  - **Which product this is remains an open decision `[D]`.**

**Proposed relationship model `[R]`** (everything below is PROPOSED; ⟨?⟩ marks an open decision):

```
Identity ──PersonRef──▶ all contexts                       (XD-3 ⟨?⟩)
Membership ──MembershipQueryPort(isMember asOf)──▶ PDP     (read only)
Governance ──TenureQuery(holds position s asOf)──▶ PDP     (read only; DR-07 ⟨?⟩)
Geography ──GeographyQueryPort(rev)──▶ Forum (optional relevance only ⟨?⟩)
Forum policy module (owned by Forum) ─▶ PDP ─▶ decision {PERMIT|DENY|INDETERMINATE|…}
Forum (writes only Forum facts: Forum, Topic, Post, ModerationAction)
   └─ DecisionRecord (Audit evidence, same transaction as the post)
```

**Example: `ContributePost`.** This is hypothetical. It fits the VS-1 proposal (closure §7), **not** an existing forum architecture.

1. **Resolve the actor.** The actor's PersonRef comes from the session. The forum id comes from the route; its organisation is **loaded from the Forum aggregate, never taken from the client**.
2. **Gather evidence.** The PDP gathers Membership (`isMember(org, person, now)` → T / F / U) and, if the forum is committee-anchored, tenure. U leads to DENY or INDETERMINATE (K2).
3. **One transaction:**
   - take Forum scope tokens (FOR SHARE on the membership / tenure footprint, FOR UPDATE on the topic);
   - re-validate against those tokens;
   - append the Post with an idempotency key `(person, client_nonce)`;
   - append the DecisionRecord.
4. **Emit** `PostContributed`. No write touches Membership or Governance.

**Answers to the 13 questions:**

| # | Answer |
|---|---|
| 1 | Today, `users.id` in practice, but `member_id` is ambiguous (C9). The authoritative source is **open** (XD-3). |
| 2 | Today, legacy `members.status`. The ratified kernel (`org_memberships`) has no live writer. The source for consumers must be agreed with the Membership owner. |
| 3 | **Unknown.** Non-members, guests, external advisers or honorary participants: decision BQ-F2. |
| 4 | **Proposed:** committee membership or tenure is only an *input* to an explicit forum policy, never a grant (otherwise C12 repeats). Needs approval. |
| 5 | **Proposed:** separate abilities `forum.read`, `forum.contribute`, `forum.moderate`, `forum.administer`, evaluated by the policy. Today: none. |
| 6 | Yes. Position ≠ permission (C5). The rule itself is proposed. |
| 7 | **Open** (BQ-F3, BQ-P2): does suspension block posting? Does ending an assignment end moderation rights? |
| 8 | **Proposed:** evaluated on every command, against version-checked evidence (closure TC rules). Reads may be eventually consistent, within a stated bound ⟨?⟩. |
| 9 | **Open.** Recommendation: posts are retained and attributed, never cascade-deleted (unlike Openion today). Retention or anonymisation is a privacy decision. |
| 10 | Today Openion has no scope, so the question is moot. **Proposed:** the organisation and committee are derived server-side from the forum aggregate. Geography ids are never taken from the client (as `ApplyForCommitteeMembershipRequest` wrongly does). |
| 11 | **Proposed:** an idempotency key on Post. A concurrent revocation is serialised by scope tokens; g11 shows plain reads are unsafe (model evidence only). |
| 12 | **Proposed:** a ModerationAction fact recording actor, decision inputs (evidence versions), policy version, reason and target, append-only. |
| 13 | **Not required for access.** Geographic relevance is an optional feature ⟨?⟩. Jurisdiction-based moderation would need C6 / XD-5 resolved first. |

**Can the Forum consume Membership and Governance contracts without writing their facts?** Yes, by design. Its only writes are its own aggregates and Audit records.

---

## H. Canonical cross-context contracts (all PROPOSED; none implemented)

| Contract | Provider → consumer(s) | Returns | Organisation scope / time | Missing or conflicting evidence | Approval needed | Current support |
|---|---|---|---|---|---|---|
| **PersonRef** | Identity (+ Membership) → all | an opaque id plus its kind | global person; per-organisation links | an unmapped id returns UNRESOLVED, never a guess | XD-3, Membership owner | **None** (C9) |
| **MembershipQueryPort** | Membership → PDP, Governance, Adjudication | `{T, F, U}` + version token | per organisation; `asOf`, `knownAt` | U on conflict; DEPENDENCY_FAILURE on error | **Membership owner** (suspension, future dates) | Kernel model exists; no live port |
| **GeographyQueryPort** | Geography → Governance, Forum (optional), Membership | unit, ancestors, contains | organisation-scoped or approved-official; `revision` | RECONSTRUCTION_INCOMPLETE if the revision is pruned | XD-5; needs ADR-008 implemented | **None** (no revisions) |
| **CommitteeStructurePort** | Governance → Governance, PDP | `levelOf`, `allowedGeoLevels`, `geoPolicy`, κ | organisation-configured; versioned | CONFIGURATION_ERROR if absent | DR-07 + structure ownership | Tables exist; writer unrouted |
| **TenureQuery** | Governance → PDP, Forum | holds(person, position, asOf) as T / F / U | organisation / committee | U while the stores disagree (C4) | DR-07 | Three conflicting stores |
| **AuthorityAssertionPort** | Membership / Governance → Adjudication | an assertion plus evidence | organisation; `asOf` | DENY or INDETERMINATE | Adjudication + DR-07 | Interim, unbound (Slice 2a, other session) |
| **Authorization decision (PDP)** | Authorization → command handlers | PERMIT / DENY / INDETERMINATE / DEPENDENCY_FAILURE / CONFIGURATION_ERROR + DecisionRecord | per request | K2 semantics | XD-4 | None (policies use strings) |
| **Forum contribution policy** | Forum (policy module) → PDP | conditions over the above | forum's organisation, derived server-side | as PDP | BQ-F1..F3, product choice | None |
| **Write-guard / version token** | each owner → protected writes | scope token + version | per scope | retry or deny | XD-12 | None; model evidence only (g11) |

**Minimum set for a first forum slice:** PersonRef, MembershipQueryPort, PDP, forum policy, write-guard. Geography and CommitteeStructure are not needed unless the forum is anchored to a jurisdiction or committee.

No context updates another context's authoritative tables. Coordination with the Membership owner is by contract text and review questions only.

---

## I. Contradiction and reconciliation strategy

- **Read-only detection only.**
- Classify each disagreement as **agree / explainable transition / conflict / unpairable / undecidable (needs a rule)**.
- Conflicts and unpairable rows go to a **human adjudication queue**, keeping every source row as evidence.
- Single-source rows are queued, not auto-accepted (S2 finding).
- Never "most recent wins"; never repair in place; never cascade-delete.
- Each authorised resolution becomes a new fact, attributed to its decision.

**Anomaly detection:**
- allowed only as a triage aid: explainable features (store disagreement, date ordering, format), human review mandatory;
- an anomaly is not a confirmed violation;
- no training on real personal data in this round.

**Formal properties:**

| Kind | Property | Status |
|---|---|---|
| Safety | `∀ write w: tenant(w.refs) ⊆ {org(w), official}` | PROPOSED |
| Safety | `PERMIT(contribute, p, f, t) ⇒ policy_f(evidence@versions) = T` | PROPOSED |
| Business policy | `A_eff(p,c,t) ⇔ held(p,c,t) ∧ M(p,O(c),t)` | **OPEN** |
| Data-quality predicates | C2, C8, C9, C11 queries in §F | — |
| Historical reconstruction | `replay(d) = decided(d)` under SNAPSHOT | proven only for 1:1 (S4) |
| Liveness (operational) | a queued conflict is eventually adjudicated or escalated, within an SLA ⟨?⟩ | — |

---

## J. Architecture decisions

**Approved:**
- the Membership architecture (2026-10-08);
- ADR-T11;
- EM-VOC-001/002;
- ADR-008 (Accepted, **not implemented**);
- ADR-0001 (Accepted, contradicted by C4);
- ADR-002 and ADR-003 (election-adjacent; not used here).

**Open business questions:**
- BQ-P1: κ (role capacity) per position (C11);
- BQ-P2/P3: Membership ↔ tenure, including whether re-admission revives tenure (C3);
- BQ-F1..F3: moderator source; guests / public; suspension effect;
- whether the product is a forum or a collective-intelligence platform;
- post retention after access loss;
- C12 flag semantics.

**Open architecture decisions:**
- DR-07 (committee fact ownership / authoritative store);
- XD-3 (PersonRef);
- XD-4 (Authorization ownership);
- XD-5 (geography revisions, successor relation including splits and merges);
- the canonical level scale (C10);
- the owner of the type ↔ level mapping;
- whether NULL-organisation geography units are official.

**Recommendations:**
- implement ADR-008 before any historical jurisdiction work;
- R1+R3 as the candidate rule (if BQ-P2/P3 adopt the invariant);
- the queue-single-source refinement;
- the S4 extension (splits, merges, cycles, pruning).

---

## K. Next implementation gates

1. **Security:** PBDIGIT-266 plus new items for F-1 (Openion IDOR) and C8 (`left_date` rewrite). These need backlog items, which were not created (read-only round).
2. **Isolated test database** (ARB-SEC-01) and an **authorised read-only profiling source**.
3. **Decisions:** DR-07, XD-3, BQ-P2/P3, and the forum-vs-CIP product choice.
4. **Membership owner** agrees the MembershipQueryPort and PersonRef text.
5. **Profiling results** for C2, C4, C8, C9, C11 and C12, with the review queue sized from real rows.
6. Only then: an EP-01 plan for a test-only VS-1 slice, then shadow reconciliation, then migration with a single writer (Strangler Fig).

---

## L. Progress and TODO checklist

**Research completed:**
- independent re-verification of C1–C12 at `71e5a931e`;
- g14 reproduced and stress-tested;
- Forum inventory;
- contract proposal with semantics;
- profiling query templates.

**New this round:**
- F-1 (Openion IDOR);
- unregistered Geography Tenant migrations;
- ADR-008 not implemented;
- C9 broken inside a single write path;
- C11 limits that can never apply;
- C12 latent;
- the S2 correlation sensitivity.

**Decisions pending:**
- [ ] DR-07, XD-3, XD-4, XD-5;
- [ ] BQ-P1/P2/P3, BQ-F1..F3;
- [ ] forum vs CIP;
- [ ] level scale; NULL-organisation semantics.

**Profiling pending:**
- [ ] authorised read-only database;
- [ ] run the §F templates;
- [ ] size the review queue from real rows.

**Research TODO:**
- [ ] S1 with R1+R3, suspension and annulment of the Admission;
- [ ] S2 with correlated errors and the real store semantics;
- [ ] S4 with splits, merges, cycles and pruned revisions.

**Backlog to create (needs authorisation):**
- [ ] F-1;
- [ ] C8;
- [ ] unregistered Geography Tenant migrations.

**Implementation:** not started, and correctly so.

---

## M. Principal Reviewer conclusion

1. **What is established?**
   - C2–C5, C7–C11 exist at HEAD as schema and design contradictions with reachable write paths.
   - Geography has no history, despite an accepted ADR.
   - No forum exists; the legacy Openion wall is unscoped and has an IDOR.
   - The derived-effect rule preserves history; cascading writes do not (model evidence).
2. **What remains uncertain?**
   - How often each contradiction actually occurs: UNKNOWN.
   - Every cross-context business rule.
   - Whether NULL-organisation geography is official.
   - Whether `openions` is deployed.
   - Whether P3 outperforms other policies under realistic, correlated errors.
3. **What should be done next?**
   - Obtain the four decisions in §K.3.
   - Authorise read-only profiling.
   - Raise the F-1 and C8 backlog items.
   - Agree MembershipQueryPort and PersonRef with the Membership owner.
4. **What must not be implemented yet?**
   - Any forum feature.
   - Any reconciliation or migration write.
   - Cascades from Membership into committees.
   - Use of `committee_structures` as the mapping owner.
   - Anything inside Membership.
5. **What would demonstrate readiness?**
   - Signed DR-07 and XD-3.
   - An agreed contract text with the Membership owner.
   - Measured C-counts with a sized review queue.
   - An isolated test database.
   - An approved EP-01 plan for one test-only slice whose RED tests encode only approved rules.
