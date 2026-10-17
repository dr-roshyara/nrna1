# Principal Review of the Membership Verification and Completion Plan, and the Next Step

**Reviewed artefact:** `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md` (status PROPOSED), plus the follow-up report of 2026-10-16 that added the security findings S-1 … S-11. That follow-up exists in the repository only inside an **untracked** transcript file, `docs/publicdigit/membership/reports/Ran 1 shell command` (not authored as a document; VERIFIED).
**Reviewer:** Session 1 (Architecture and Requirements Authority). **Status of this review:** PROPOSED. It ratifies nothing, accepts no slice and authorises no work.
**Mode:** read-only. No test, Composer, Artisan, migration, database query or HTTP request; no Git state change; no edit to code, tests, configuration, shared context files or session logs.

**Independence statement.** The reviewed plan was written by this same session. To reduce self-review bias, an **adversarial reviewer with no prior context** was given only the plan and the authoritative sources; its material citations were then re-read by Session 1 (§9). Findings it raised that Session 1 confirmed are marked **[ADV, re-checked]**. The limitation remains: this is not an organisationally independent review.

**Labels:** VERIFIED (read at the current HEAD) · HISTORICAL (earlier commit or working tree) · REPORTED (stated by another session or reviewer, not re-checked) · INFERRED (assumptions stated) · OPEN · BLOCKED · ACCEPTED (formal, record named). **Naming used** (PROPOSED, not adopted): COR-1…COR-4 = correction slices C-1…C-4 of the correction contract; CUT-0…CUT-10 = the cutover gate; GOV-END = "who may end a membership" (G-1 in the Slice 2 plan).

---

## 1. Executive verdict

**RETURN FOR REVISION.** This verdict concerns the plan's quality, not any slice.

- **Reliable:** the code-level findings (V-1 … V-6, V-9 … V-12) and the security findings S-1 … S-5 and S-7 / S-10 are accurate at source level.
- **Not reliable as written:**
  - the dependency plan contains a circular dependency (COR-3 ↔ W6) and gates Slice 2b behind the entire correction track, which no source requires;
  - two findings present new rules as derived obligations: AO-3 / V-8 (would change the PO-approved Slice 1a port) and V-7 (conflicts with the approved decisions S2-C, Z1 and Q-E1). This contradicts the plan's own statement that nothing ratified is reopened;
  - four decision owners are wrong;
  - required gates are missing: CUT-0, D-6 / R10, DV-1, the C-AMEND-1 "reason" criterion, BD-CONSENT / BD-REC0;
  - three recorder's notes are labelled RATIFIED;
  - the security track's exit criterion is incompatible with ARB-SEC-01.

**What is unaffected:** the next safe step (A1, a database-free re-run on the committed baseline) is the same under the corrected dependency graph (§6, §10).

---

## 2. Baseline and evidence assessment

### 2.1 Current repository state (VERIFIED)

| Item | State |
|---|---|
| Branch / HEAD | `election-flow-updated` / **`83a059f98`** |
| Since the plan's baseline `ebdfee484` | `3b4331ba3`, `061efb1d1`, `a28b63c38`, `278838199` (PBDIGIT-304 refactors), `2de10f8b3` (backlog), `baf4f1920` (deptrac), `f98a4d8fc`, `83a059f98` (KnowledgeOS state only: `.claude/CONTEXT.md`, `.claude/S-SERIES-TODO.md`, `.claude/sessions/2026-10-16.md`) |
| Membership ledger code | **unchanged since `834a4f65c`** (`git diff 834a4f65c HEAD` over `Membership/{Domain,Application,Infrastructure}/Organisational`, `tests/Unit/Contexts/Membership`, `tests/Architecture`: empty). The only Membership file touched since is the duplicate `Domain/ValueObjects/TermPeriod.php`, deleted in `3b4331ba3` (PBDIGIT-304) |
| Uncommitted, other sessions | `D app/Http/Middleware/VoterSlugStep.php`; `M tests/Architecture/MembershipDecisionCreationTest.php` (`UNPARSABLE_KNOWN = []`); `M composer.json`, `composer.lock`; IP-work documents; `docs/publicdigit/membership/architecture/models/README.md` (rev. 12, written earlier by this session) |
| Ownership of the uncommitted pair | `VoterSlugStep.php` is tracked by **PBDIGIT-70** (PBDIGIT-272 CLOSED as its duplicate); PBDIGIT-70 still records the file as "still unparseable" and no story records its deletion. **OPEN: which session or person owns and commits the pair** |
| Session 2's report | **not found as a repository file** (searched `docs/publicdigit/membership`, `docs/publicdigit/reviews/2026-10-16*`, `.claude/sessions/2026-10-16.md`). Its claims are known only as relayed in the commissions; they are treated as REPORTED |

### 2.2 Evidence currency per increment

| Increment | Evidence | Produced against | Current? | Missing / not reproducible |
|---|---|---|---|---|
| Slice 0 (PBDIGIT-258), Slice 1a (PBDIGIT-259) | E-RUN 2026-10-10 18:27, HEAD `ce8bf3109`, "clean `app/` and `tests/`" (G3 dispositions, shared evidence index) | committed tree, pre-Laravel-12 | **HISTORICAL** | the outputs: only their hashes are recorded; the files lived in a scratchpad and are **not locatable** (G-6; `/tmp` searched) |
| COR-1 (PBDIGIT-268) | the same E-RUN; independent review (reading); the developer's mutation run | committed `ce8bf3109` | **HISTORICAL**, and the kernel has since been changed by `834a4f65c` | G-6 outputs; the Principal Reviewer's source is cited as `202610101901_implementation.md`, but the real file is `…/brainstorming/20261010_1901_implementation.md` and it is **untracked** (content VERIFIED, lines 13–17) |
| Slice 2a (PBDIGIT-271) | `docs/publicdigit/membership/evidence/2026-10-11-slice2a{,-hardening}/` with `SHA256SUMS` | **uncommitted working tree on `bf605092c`** (README:13; `env.txt`), php-parser v5.4.0, PHPUnit 11.5.6, PHP 8.5.11 | **HISTORICAL** | no source hashes, so equality with `834a4f65c` is **INFERRED** (same day, same author), not proven. The architect sub-review exists only as a summary in plan §15 |
| Slice 1b (PBDIGIT-260) | none on an isolated database | — | — | the whole PostgreSQL suite (DEFERRED, G3 dispositions §4) |
| PBDIGIT-264 F-1 / Option E / F-3 | evidence folders `2026-10-10-*` | test double only | HISTORICAL | PostgreSQL parity (Part 2 held); **not re-examined in this review** |

**Dependency drift (VERIFIED):** php-parser v5.4.0 → **v5.9.0** at HEAD (it drives the R7 / R8 scanner); Laravel 11 → 12 (`2bd6240a5`); PHPUnit unchanged (11.5.6).

**Conclusion:** no Membership test result exists for the committed code at HEAD. Every result is HISTORICAL, and none may be described as passing on HEAD.

### 2.3 Discrepancies in the reviewed plan and follow-up report (recorded, not silently corrected)

| # | Discrepancy | Evidence | Impact on the recommendation |
|---|---|---|---|
| D-1 | the plan's baseline is "`ebdfee484`" | §2.1 | none on Membership code; the plan must cite `83a059f98` |
| D-2 | plan §8: the TermPeriod deletion is "staged" | committed in `3b4331ba3` | none |
| D-3 | `InvariantSet.php:172-188` cited | the file has 73 lines [ADV, re-checked] | citation wrong; the claim (INV-9 enforced by G\*) is still true via `Member.php:184-192` |
| D-4 | `PostgresMemberRepository.php:159` | the version UPDATE is at `:160` [ADV] | cosmetic |
| D-5 | follow-up report: "S-3 … REPORTED" | now VERIFIED (§4) | raises confidence |
| D-6 | the follow-up report exists only in an untracked transcript | §header | not reviewable as a governed artefact until preserved |

---

## 3. Findings register (on the plan)

Severity concerns the plan's reliability. "Blocks next" = blocks the next authorised step (A1).

| ID | Sev. | Finding | Evidence | Affected | Required action | Owner | Blocks next? |
|---|---|---|---|---|---|---|---|
| **P-01** | high | **Circular dependency.** COR-3 (W4) requires G-A1 and "events emitted transactionally" (plan §5), while the publication mechanism is built in W6, whose entry needs W5, which follows W4 | plan §5 table and diagram [ADV, re-checked] | W4–W6 | split COR-3 into **COR-3a commands** (no emission) and **COR-3b emission** after the publication design; or place publication before COR-3 | Session 1 (plan); ARB (publication design) | no |
| **P-02** | high | **Slice 2b over-gated** behind COR-2…COR-4. Slice 2b's gate is "PostgreSQL resumed and its verification accepted; G-1 decided; separate authorisation" (Slice 2 plan §3, `:65`). ARB-MEM-01 gates "live writes and cutover" only (ARB record `:11`). The correction contract says Slice 2 "may proceed after its own contract" (`:233`, REPORTED by ADV) | sources quoted | W5 | remove W4 → W5; also remove W2 → COR-2 (COR-2 is a pure kernel slice blocked by BD-REV-ER and D-COR-3 only) | Session 1 | no |
| **P-03** | high | **V-7 conflicts with approved decisions.** S2-C "required `decisionDate` … no hidden clock; UI default under the organisation-time-zone rule" is **APPROVED 2026-10-11** (Slice 2 plan `:317`); Z1 "explicit calendar dates from the caller" (`:97`); Q-E1 "**DECIDED** … option A (validate, do not persist)" (`:327`); AUD-1 is assigned to the Audit slice 12 (`:328`). The plan's Slice 2b criterion "refused regardless of any supplied decision date" overrides S2-C | VERIFIED | BR-8 | reformulate V-7 as an **interpretation question**, not a defect: does D-5(b) "future-dated refused" require the *application boundary* (outside the domain, consistent with S2-C) to refuse a supplied `decisionDate` later than "today" in `organisations.timezone`? Persisting the date stays with AUD-1 / Slice 12 | **Human PO / ARB** (interpretation of D-5(b) under S2-C) | no |
| **P-04** | med-high | **AO-3 is a new rule; V-8 reopens an approved contract.** D-2 says the *application* stays outside the aggregate (closure record `:291`). The PO-approved Slice 1a port is `create(TenantId, MemberId, PersonRef) → Created(Member) \| HolderExists` (1a contract `:37`), i.e. create-then-decide by design. An empty identity row is not a "pending Member": every predicate derives from facts, so `IsMember` is false | VERIFIED | BR-5, AO-3 | relabel AO-3 **PROPOSED NEW RULE**; V-8 = **change request** against the 1a port (affects the handler and the version-0 concurrency anchor); move it out of "kernel hardening" (W3) | Human PO / ARB (on PR advice) | no |
| **P-05** | med | **G-A1 is a deliberate deferral**, not an omission. EP-01 placed the outbox in 1b (`:557`), but the PO-approved 1b contract excludes "event sourcing, generic persistence, projections; triggers" (1b contract Out list, `:28-40`). EP-01 decided `outbox_events \| REUSE (Shared)` (`:371`) and "publishes … each only when a consumer slice exists" (`:411`). The plan's "own outbox" recommendation reverses EP-01 without saying so | VERIFIED | G-A1, BR-16 | restate as "deferred by the 1b contract, landing slice not named"; any departure from REUSE (Shared) is a **change request** | ARB | no |
| **P-06** | med | **Recorder's notes labelled RATIFIED.** The act says the notes "do not amend the act" (act `:8-10`); BR-10 (N-3), BR-16 (N-5), BR-17 (N-6) are marked RATIFIED. Ratified text keeps the Correct-target rule as "policy (BD-REV) … Recommendation" (R `:164`). **Qualification:** ACT N-3 records that the act *settles* the sub-policy as recommended, so BR-10 is best labelled "act as recorded in N-3" | VERIFIED | BR-10, BR-16, BR-17 | relabel | Session 1 | no |
| **P-07** | med | **Wrong decision owners.** D-COR-3 = **PO** (CC `:213`; plan says PR) · CQ-3 = **PO + Legal + Governance** (CC `:735`; plan §7 says Governance) · CQ-9 / D-COR-5 (B) = **PO + Legal** (CC `:736`; plan bundles it with Governance) · BQ-P = Governance owner, BQ-P10/P11 = Finance owner (cutover gate `:285`; REPORTED by ADV for P1–P3/P8) · V-5 changes the frozen temporal model if KnownAt is redefined over seq, so it is a **change request** (act §16), not a PR clarification | VERIFIED except BQ-P (REPORTED) | §4.2 of the plan | correct the register (§7 below) | Session 1 | no |
| **P-08** | med | **Missing gates and items.** CUT-0 "a fitness test enumerates writers of `members`" (cutover gate `:291`) · D-6 / R10 "stays the gate for cutover and HTTP / UI" (Slice 2 plan `:333`) · DV-1 countersignature, a COR-4 / cutover blocker (CC `:740`) · C-AMEND-1 C-3 items 3–5 ("reason" OPEN under CQ-3; CC `:534-536`) · D-COR-5 (B) as a COR-3 workflow blocker · BD-CONSENT, BD-REC0 (EP-01 §19, Slice 2 Definition of Ready) · `competenceVerified` in the evidence tuple (Slice 2 plan `:107`) · PBDIGIT-267 TG-1 (tests for the *existing* `fromLog` checks; V-1 extends that scope — related, not identical) · PBDIGIT-269 (G3 residual risk) · PBDIGIT-295, 293, 261 (test-database isolation, `DB_URL`, engine for CUT-10; REPORTED) · formal acceptance of Option E | VERIFIED except where marked | traceability, waves | add them | Session 1 | no |
| **P-09** | med | **Security exit criterion ill-formed.** "Security track … must close before any HTTP exposure" ignores ARB-SEC-01: "investigation and tests only. No application fixes, route changes, middleware changes, schema changes, or configuration changes are authorised" (ARB record `:12`). A test-confirmed defect cannot be closed under that authorisation. Also, Slice 1b re-entry criterion 4 requires "the relevant persistence and security acceptance conditions" (G3 dispositions §4), so security conditions gate **ACC-260 → Slice 2b**, not only HTTP | VERIFIED | W-plan | define: characterisation (ARB-SEC-01 scope) → separate fix authorisation per story → closure; state which security conditions ACC-260 requires | ARB | no |
| **P-10** | low | **ARB-MEM-01 paraphrased wider.** Verbatim: "Verified correction/annulment capability is required before Membership live writes and cutover" (ARB record `:11`). The COR-1…4 mapping comes from the correction contract | VERIFIED | BR-19 | cite both precisely; do not use it to gate non-live work (P-02) | Session 1 | no |
| **P-11** | low | the plan's G-A10 (no append-only database guarantee) ignores the explicit 1b exclusion "no database trigger" (B-6) | 1b contract | G-A10 | restate as a deliberate exclusion; any trigger needs a change request (B-11 M-11) | Membership owner → ARB | no |
| **P-12** | low | the follow-up report ranked S-5 above live risks in Session 2's framing; already corrected there (S-5 latent) | §4 | — | keep | — | no |

---

## 4. Security findings, ordered by severity and confidence

**Status of the security evidence:** all source-level. **Nothing is dynamically confirmed**: no request or test was run. Runtime consequences are INFERRED. The governing authorisation is **ARB-SEC-01** (tests only, after isolated-database readiness), still **BLOCKED** on DEC-SEC-DB.

| # | Finding | Source evidence | Status | Inferred runtime impact |
|---|---|---|---|---|
| **S-1** | `X-Tenant-Id` writes any organisation id into the session with no membership check | `app/Http/Middleware/TenantContext.php:48-56` (the membership check at `:73` runs only without the header); used on `routes/election/electionRoutes.php:263` (and a debug route at `:732`, REPORTED); `BelongsToTenant` falls back to `session('current_organisation_id')` (REPORTED `app/Traits/BelongsToTenant.php:46`) | **VERIFIED** (the session write) | any verified user poisons their session to organisation B; downstream routes that rely on the scope or on session-bound bindings act on B. **Conditional**: depends on each route's own checks and on the session being saved on error responses |
| **S-2** | fee waiver lacks the member-to-organisation check | `app/Http/Controllers/Membership/MembershipFeeController.php:140-155`; the four sibling actions have `abort_if($member->organisation_id !== $organisation->id, 404)` (`:26`, `:42`, `:82`, `:108`); `waive` checks only `fee->member_id === member->id` (`:143`) | **VERIFIED** | admin of A waives a fee of B through A's URL, if `$member`/`$fee` resolve to B's rows: binding order (`bootstrap/app.php:128-132`) and session scope are INFERRED |
| **S-3** | committee-member API: membership checked, role not checked | `bootstrap/app.php:19` stack `web, json.api, tenant.api, auth, verified`; `IdentifyTenantFromHeader.php:39-45` checks only `organisations()->where(...)->exists()`; `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` `index`/`store`/`destroy` (`:54`, `:98`, `:205`) contain no `authorize`/`Gate`/role check (grep) | **VERIFIED** (this review) | any participant, any role, changes committee composition. `tenant.api` runs **before** `auth`, so an unauthenticated call reaches `$request->user()->…` on null (INFERRED: 500, fails closed) |
| **S-4** | dashboard cache shares personal data across members | key `membership_dashboard_{$organisation->id}_{$role}` (`MembershipDashboardController` `:28-32`); the `member` payload includes `getMemberData($organisation)` (`:98`), built from `auth()->id()` (`:142-164`) | **VERIFIED** | the second member within 300 s sees the first member's status, fees and applications (same organisation). Confirmed by reading; not run |
| **S-5** | committee-application review: no reviewer authority, no organisation-membership check, no self-approval ban | `routes/committee/committeeRoutes.php:26-28, 80-81` (`auth`, `verified` only); controller import `:10`; repository save is `::create` with the existing id (`EloquentCommitteeMembershipApplicationRepository.php:19-31`) | **VERIFIED** (source) | **latent**: approval of an existing application re-inserts its primary key, so it fails with a 500 before anything is stored (INFERRED). The existing test `CommitteeMembershipApplicationTest.php:144-166` codifies self-approval (REPORTED). **The save must never be repaired separately from authorisation, organisation membership and the self-approval rule** |
| S-7 (part) | member export: no role check | `app/Http/Controllers/MemberController.php:125-132` (no `authorize`), route `routes/organisations.php:188` | **VERIFIED** (this review) | any participant exports the member directory of their organisation |
| S-10 (part) | suspend / archive / create member always refused | `MemberController.php:309`, `:338` (`manageMembers`), `:95` (`create`); no `manageMembers` method in `app/Policies` (grep) | **VERIFIED** (code); 403 outcome INFERRED | fail-closed availability gap; the authority rule is undefined |
| S-6 | committee apply: no organisation-membership check; eligibility only for `RESIDENCE` | REPORTED | REPORTED | junk applications; with S-5, self-admission once repaired |
| S-8 | member geography rendered from session-scoped binding | REPORTED | REPORTED | conditional cross-tenant read |
| S-9 | owner import attaches existing users by email, pre-verifies new accounts | REPORTED | REPORTED | consent rule undefined |
| S-11 | duplicate route registration; live debug routes | REPORTED | OPEN | ambiguous middleware stacks |

### 4.1 Closure specification for the four live risks

"Expected outcome" below is the outcome **the rule would require once decided**; the first test run is a characterisation that records what actually happens.

| | Rule to protect | Authority that defines it | Negative tests (minimum) | Positive test | Isolation expectation | Evidence to close |
|---|---|---|---|---|---|---|
| **S-1** | an organisation context is selected only within the caller's own memberships | **ARB (platform security)**; also affects Election routes (owner informed) | a verified user with no role in B sends `X-Tenant-Id: B` → refused, and the session does **not** contain B; a follow-up request without the header does not act on B | a member of B with the header → allowed, context B | no persistence of an unauthorised organisation id | characterisation output, then fix (separately authorised) + green tests on the isolated database |
| **S-2** | an action on a member's fee happens only inside that member's organisation (existing rule, applied by the four sibling actions) | **none new**: consistency with existing rules; fix still needs PO authorisation | admin of A, B's member + fee, A's slug, session set to B → 404, fee unchanged | admin of A, A's member + fee → waived | URL organisation = member organisation = fee's member | same |
| **S-3** | who may change a committee's composition | **Governance owner + PO** (UNDEFINED for the API; the web path uses `manageCommittee` = owner / admin, `OrganisationPolicy.php:46-60`, REPORTED) | a voter-role participant of A → refused; non-member of A → 403 (already, via `tenant.api`) | the authorised role of A → change recorded | header organisation only | same |
| **S-4** | a member's personal data is visible only to that member (and authorised officers) | **none new** (privacy) | member U1 loads, then member U2 loads within the TTL → U2 does not receive U1's data | U1 sees own data | per-user data never under a per-role key | same |

**Read-only investigation that would resolve the REPORTED items** (each can be done without execution): S-6 — read `ApplyForCommitteeMembershipHandler.php` and `MembershipApplication.php` eligibility branch; S-8 — read `MemberGeographyController::show` and the `Member` model's scopes; S-9 — read `OrganisationUserImportService.php:300-385`; S-11 — read `app/Providers/RouteServiceProvider.php:38-56` against `bootstrap/app.php:11-24`. **Only runtime inspection** (`route:list`, needs authorisation) can settle which duplicate route wins and the binding-versus-middleware order.

---

## 5. Requirements traceability assessment (corrected)

### 5.1 Reclassification of V-1 … V-12

Two separate judgments: **(S)** the slice's own acceptance criteria; **(A)** the overall ratified architecture.

| ID | Classification | Requirement | (S) | (A) | Owner of next decision |
|---|---|---|---|---|---|
| V-1 load-time structural validation absent (`Member.php:25-40`) | **2. gap, enforcement deferred** (COR-4; no persisted Annul/Void exists yet) | invariants independent of enforcement (R §5); INV-TGT / INV-N3 structural (CC §4) — the obligation on load is an **inference**, not a written rule | conforms | gap | PR → PO (as a COR-4 entry criterion) |
| V-2 Annul's own provenance unchecked (`Member.php:142-165`) | **1. confirmed defect against an approved requirement** — CC §3.1 "Resulting fact `Annul{…, provenance(decision, authority, reason), …}`" — with no acceptance criterion covering it | CC §3.1 | conforms (no K-criterion) | defect | PO / ARB (authorise a small RED-first fix before COR-3) |
| V-3 R7 does not list `new Annul` / `new VoidFact` | **4. evidence / hardening gap**. Appending requires an accepted `Decision` (`MemberRepositoryPort.php:36`), and `Decision::accepted` is restricted to `Member.php`, so a stray `new Annul` cannot be appended | Slice 2 plan §4.6 | conforms | minor | PR |
| V-4 Annul/Void unstorable; raw `\Error` (`PostgresMemberRepository.php:223`) | **2. gap, explicitly deferred** (C-AMEND-1 C-4 F-1, CC `:538`) | C-AMEND-1 | conforms | gap | — (scheduled: COR-4) |
| V-5 equal `recordedAt` | **3. unresolved decision**; redefining KnownAt is a **change request** to the frozen temporal model | INV-14 | conforms within bounds | open | PR proposes; ARB decides |
| V-6 RED-first partial | process; accepted (H-2) | CC §15.7(1) | accepted deviation | — | — |
| V-7 future dating relative to the supplied date | **3. unresolved interpretation** (P-03); not a defect under S2-C / Z1 | D-5(b), S2-C, Q-E1, AUD-1 | conforms | open | Human PO / ARB |
| V-8 empty Membership after a refused first Admit (`AdmitMemberHandler.php:60-72`) | **3. unresolved domain decision** (P-04); behaviour follows the approved 1a port | 1a contract `:37` | conforms | open (proposed new rule) | Human PO / ARB |
| V-9 tenant / actor from the command | **2. later-slice gap** (Slice 2b / HTTP boundary); unreachable today | D-4 evidence meaning | conforms | gap | PR (boundary contract) |
| V-10 scanner limits (per-file union allowlist; `app/` only; literal R5) | **4. evidence gap / hardening** | AC-6, AC-7 | conforms with limitation | minor | PR |
| V-11 no `end_kind` CHECK; no load check of legacy ⇔ attested | **2. later-slice gap** (Slice 2b follow-up, plan §15) | D-3 | conforms | gap | PR proposes; PO / ARB decides the representation |
| V-12 denied authority evidence discarded (`DecideAndAppend.php:48-49`) | **3. unresolved decision** (Audit slice 12) | R §8 "Membership stores evidence" (for decisions taken) | conforms | open | PO with the Audit slice |

**Not supported as written:** none of V-1 … V-12 is factually wrong at source level; the overstatements are in their classification (V-7, V-8) and severity (V-3).

### 5.2 Traceability corrections to BR-1 … BR-20 / AO-1 … AO-5

| Item | Correction |
|---|---|
| BR-5 / AO-3 | "contradicted in effect" → **no contradiction**; AO-3 is a PROPOSED NEW RULE (P-04) |
| BR-8 | add S2-C (APPROVED), Z1, Q-E1 (DECIDED A), AUD-1 (Slice 12); V-7 becomes an interpretation question (P-03) |
| BR-7 | add `competenceVerified` to the evidence tuple (Slice 2 plan `:107`) |
| BR-10, BR-16, BR-17 | label "act + recorder's note" (P-06) |
| BR-16 / AO-5 | EP-01: REUSE (Shared) outbox, publication per consumer slice (P-05) |
| BR-19 | cite ARB-MEM-01 verbatim; the COR-1…4 mapping is the correction contract's (P-10) |
| BR-3 / G-A10 | append-only trigger explicitly excluded by 1b B-6 (P-11) |
| missing | CUT-0; D-6 / R10; DV-1; C-AMEND-1 C-3 "reason"; D-COR-5 (B); BD-CONSENT, BD-REC0; Option E formal acceptance (P-08) |

**Correctly specified but not enforced:** V-2 (Annul provenance), V-4 (storage of reversals), V-11 (stored end kinds). **Enforced on one path only:** structural invariants at decide time but not on load (V-1). **Dependent on an open decision:** GOV-END, CQ-3, BD-REV-ER, V-5, V-7, V-8. **Wrongly treated as accepted:** none found in the plan; every slice remains unaccepted.

---

## 6. Dependency review

### 6.1 Corrected order

```
A1 ─► A2 ─► verdicts ACC-258 / 259 / 268 / 271 (Human PO / ARB)
                │
                ├─► COR-2 Correct (kernel)          ◄─ BD-REV-ER, D-COR-3 (PO)
                ├─► V-2 fix (kernel, small)         ◄─ authorisation
                │
DEC-SEC-DB ─► P-DB + I-1 + PV ─► Part 2 (PG suite) ─► F-5 → F-4b → F-2 ─► ACC-260 (+ DV-1, + security conditions per re-entry 4)
                │                                                      │
                └─► security characterisation (ARB-SEC-01) ──► fix stories (separately authorised)
                                                                       │
ACC-260 + GOV-END + authorisation ─► Slice 2b (bindings, role reader, End authority; V-8 / V-11 decided before binding)
COR-1 accepted + Part 2 + 264 + D-COR-4 (+ V-5) ─► COR-4 persistence (Annul/Void first if split) ─► COR-3a commands (CQ-3 + "reason", G-AUTH-1)
publication design (ARB; EP-01 REUSE Shared) ─► COR-3b event emission / Slice 3 queries ─► Slices 10–12
D-6 / R10 + LQ-1 + D-3-L + V-7 interpretation + V-9 boundary + affected security fixes ─► HTTP / UI (Slice 14)
ARB-MEM-01 (COR verified) + ACC-260 + DV-1 + CUT-0…CUT-10 + PBDIGIT-265 ─► migration / cutover (Slice 13, 15)
```

### 6.2 Changes and their architectural reasons

| Change | Reason |
|---|---|
| remove "W4 → W5" | Slice 2b's sources gate it on PostgreSQL acceptance and GOV-END only (P-02); bindings write nothing live (live writes are gated by D-6 / R10 and ARB-MEM-01) |
| remove "W2 → COR-2" | COR-2 is pure kernel work; a database gives it nothing |
| split COR-3 into 3a / 3b | removes the circular dependency (P-01); emission must not be designed ad hoc inside a command slice |
| keep "COR-4 before COR-3a" | V-4: an Annul/Void handler wired before storage exists would hit a raw `\Error`; the contract's C-3 / C-4 order allows this sequencing |
| keep "security characterisation before HTTP" and add "security conditions before ACC-260" | ARB-SEC-01 and 1b re-entry criterion 4 (P-09) |
| keep "queries / projections before integrations and cutover" | CUT-5 reverse projection and CUT-6 reader inventory need them |
| keep "cutover last" | ARB-MEM-01; CUT-0…CUT-10; DV-1 |

**Critical path (INFERRED):** isolated-database provisioning (Infrastructure) gates Part 2, ACC-260, Slice 2b, COR-4 and all security tests. The second path is the Governance / Legal package (GOV-END, CQ-3 + reason, CQ-9). A1 is off the critical path but gates every acceptance verdict.

---

## 7. Decision register (corrected)

**Already decided (not to be reopened without change control):** architecture (ratified 2026-10-08); BD-I5, BD-REV (trim), BD-SYNC; D-1, D-2, D-3, D-4, D-5(b)(c); S2-A … S2-G; Q-E1 = A; ARB-MEM-01, ARB-SEC-01, ARB-DEC-01, ARB-MEM-02; D-COR-5 (A); C-AMEND-1; Option E decisions; PBDIGIT-264 F-4b / F-5 N1.

| ID | Question | Options | Recommendation (not a decision) | Owner | Prerequisites | Evidence required | Dependent work |
|---|---|---|---|---|---|---|---|
| ACC-258 / 259 | accept Slice 0 / 1a? | accept / defer | decide after A1 | Human PO / ARB | A1 | A1 outputs | — |
| ACC-268 (G3) | accept COR-1? | accept with residual risks / defer / reject | decide after A1 + A2; record V-1, V-2, V-5 as conditions | Human PO / ARB (PR recommendation exists) | A1, A2 | A1 outputs; preserved PR source | COR-2, COR-4 |
| ACC-271 | accept Slice 2a? | accept / defer | PR review now; verdict after A1; carry V-9, V-10, V-11 to 2b | PR → Human PO / ARB | A1 | A1 outputs | Slice 2b |
| ACC-260 | Slice 1b | stays DEFERRED | — | Human PO / ARB | Part 2, DV-1, security conditions | PG evidence | Slice 2b, COR-4 |
| DEC-G6 | lost outputs | A1 replaces / accept hashes | A1 replaces | Human PO / ARB | A1 | — | ACC-258/259/268 |
| BD-REV-ER | may a correction absorb a whole period? | allow / refuse | refuse (CC recommendation) | **PO** | — | — | COR-2 |
| D-COR-3 | which Admit/End attributes are correctable? | dates only / more | dates only first (CC) | **PO** | — | — | COR-2 |
| D-COR-4 | `target_fact_id` column | rename / generalise | rename (CC) | PR | — | — | COR-4 |
| **Package G (governance and legal)** | GOV-END (who ends) · CQ-3 (who annuls / voids / corrects, and what "reason" means) · CQ-9 / D-COR-5 (B) (appeal body, appeal after re-entry) · DS-4 (interim replacement) · LQ-1 · D-3-L · committee-review authority and self-approval (S-5) · committee composition authority (S-3) · suspend / archive authority (S-10) | per statute / platform default / per-organisation configuration | present as **one package**, because all of them define who may decide what over membership and committees | GOV-END, DS-4: Governance owner · CQ-3: PO + Legal + Governance · CQ-9, LQ-1, D-3-L: PO + Legal · S-3/S-5/S-10 rules: Governance owner + PO | — | decision records | Slice 2b End, COR-3a, HTTP, security fix stories |
| V-5 | KnownAt ties | strictly increasing `recordedAt` in the adapter (no model change) / KnownAt over seq (change request) | adapter-level strictness first, because it changes no ratified definition | PR proposes; ARB if the model changes | — | — | COR-4 |
| V-7 | does D-5(b) require the application boundary to refuse a supplied `decisionDate` later than "today" in the organisation's time zone? | yes (boundary check, domain unchanged, consistent with S2-C) / no | yes, as an application-boundary rule | Human PO / ARB | — | — | HTTP / UI |
| V-8 | must a refused first Admit leave no identity row? | keep the 1a port (document that an identity row is not membership) / change request to the 1a port | keep the port unless a reader is shown to count empty rows; document readers' obligation | Human PO / ARB on PR advice | — | a reader inventory showing whether any reader counts identity rows | Slice 2b, CUT-6 |
| V-11 | stored end kinds | CHECK on the closed set + load check of legacy ⇔ attested / JSON CHECK | CHECK on the closed set; legacy ⇔ attested on load | PR → Human PO / ARB | — | — | Slice 2b |
| PUB | event publication | EP-01 REUSE (Shared) outbox, after its tenant defects are fixed / Membership-owned outbox (change request) | REUSE as decided by EP-01, unless the shared relay's tenant-context defects (PBDIGIT-211, a separate track, not investigated here) remain unresolved when the first consumer slice starts | ARB | first consumer slice | — | COR-3b, Slices 10–12 |
| DEC-SEC-DB | isolated database for Part 2 and PBDIGIT-266 | dedicated / re-use `pbdigit274_isolated_test` under a new authorisation | dedicated, consuming the PBDIGIT-283 rules (no second guard) | ARB + PO + Infrastructure (P-DB, I-1) | — | provisioning evidence (marker, OID, role, no `nrna_test` access) | Part 2, security characterisation |
| DEC-TENANT-HDR | `X-Tenant-Id` | testing-only / membership check | testing-only | ARB (platform security) | characterisation | — | S-1 fix |
| BQ-P | conflicting legacy records | latest / priority / agree-or-queue | agree-or-queue with attested provenance; queue size measured in the dry run | Governance owner (P1–P3, P8), Finance owner (P10, P11) (REPORTED owners) | — | dry-run report | Slice 13 |
| PBDIGIT-265, E-12 / DR-07 | eligibility predicate; committee seat effectiveness | — | — | Election + Membership; Membership + Committee owners | — | — | CUT-6; committee integration |
| Naming / roadmap | adopt COR / CUT / GOV-END names and the corrected order | adopt / keep | adopt | PO (sequencing act) | — | — | all |

---

## 8. Next-step recommendation

**Smallest safe, dependency-correct step: A1, a database-free regression run on the committed baseline `83a059f98`.**

| Gate | Content |
|---|---|
| **Entry** | (1) written authorisation by the Human PO naming the commit, the paths below and the evidence location; (2) authorisation for the `composer install` network step in an isolated copy; (3) a documentation-write authorisation for the evidence folder |
| **Scope** | `tests/Unit/Contexts/Membership/Domain/Organisational`, `…/Application/Organisational`, `…/Infrastructure/Organisational` (includes `DateOrdinalTest`, `InterimRoleAuthorityTest`), the six `tests/Architecture/Membership*Test.php` files, and the C-1 / G6 files (`C1ExhaustiveDifferentialTest`, `C1PropertyTest`, `G6CorrectionOracleGoldenTest`, already inside the Domain directory, also run alone to record their counts) |
| **Exit** | every run's command, commit, PHP / PHPUnit / php-parser versions, exit code and output SHA-256 recorded; C-1 asserts 15,530 decisions and an empty mismatch list; the G6 golden test passes; any failure **reported, not fixed** |
| **What it will not prove** | PostgreSQL persistence or concurrency; HTTP authorisation; correctness on real data; the mutation score; the health of the wider Membership suites; overall readiness |

A1 does not depend on DEC-SEC-DB, P-DB or any business decision, and every acceptance verdict depends on it.

---

## 9. Developer handoff — **PROPOSED — NOT AUTHORISED**

Session 2 stays **WAITING** until the named authority approves each task in writing.

| Task | Scope | Preconditions | Allowed | Prohibited | Test-first | Acceptance criteria | Evidence to retain | Approver |
|---|---|---|---|---|---|---|---|---|
| **H-1 (A1)** | §8 scope at `83a059f98` | §8 entry | `git archive 83a059f98` into the session scratchpad; `composer install` there; `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>` per path; copy outputs to `docs/publicdigit/membership/evidence/<date>-head-rerun/` | running in the shared working tree; any `.env`; any database; fixing failures; editing code or tests; `phpunit.xml` configurations | n/a (characterisation) | §8 exit | `.out`, `.exit`, `env.txt` (commit, versions), `SHA256SUMS` | Human PO |
| **H-2 (A1b)** | wider `tests/Unit/Contexts/Membership` (database-free only), as a characterisation | H-1 | same as H-1 | same; mixing results into H-1's acceptance evidence | n/a | list of failures with file and cause class | same | Human PO |
| **H-3 (A2)** | preserve the Principal Reviewer source of the G3 recommendation and correct its citation | PO decision on DEC-G6 and on the documentation change | add or reference `20261010_1901_implementation.md`; correct the citation in the G3 dispositions document | rewriting decision text | n/a | the citation resolves to a tracked file | diff | Human PO |
| **H-4** | security characterisation of S-1 … S-5, S-7, S-10 (tests only) | DEC-SEC-DB; isolated database provisioned with identity proof; ARB-SEC-01 confirmed | write and run feature tests that record actual behaviour | any fix, route, middleware, schema or configuration change (ARB-SEC-01); `nrna_test`; shared databases | RED / characterisation first | each finding confirmed or refuted with output | test files, outputs, database identity proof | ARB |
| **H-5** | fix stories S-2, S-4 (no new business rule) | H-4 confirms the defect; separate fix authorisation | RED → GREEN on the isolated database | fixing S-5's save alone; touching other sessions' changes | yes | §4.1 negative and positive tests | as H-4 | Human PO / ARB |

**Stop conditions:** a failing test in H-1 (report, do not fix); any database identity mismatch; any change outside the task's paths; any need for an undecided rule; the S-5 save fix without its authorisation fix; any COR-3 wiring before COR-4 acceptance; Slice 2b before ACC-260 and GOV-END; any cutover activity.

**Independent review of the result:** Session 1 performs a conformance review of the H-1 evidence against §8 (commit, versions, counts, hashes); the Principal Reviewer reviews it; the Human PO / ARB records ACC-258 / 259 / 268 / 271 and DEC-G6.

---

## 10. Residual risks and limitations

- **Self-review:** the plan and this review come from the same session; mitigated, not removed, by the context-free adversarial reviewer and Session 1's re-reads.
- **Re-checked by Session 1 in this review:** slice 2 plan `:97`, `:317`, `:327-328`, `:333`; ARB record `:11-12`; EP-01 `:371`, `:411`; CC `:213`, `:735-737`, §3.1; cutover gate `:285-291`; 1a contract `:37`; 1b contract Out list; `InvariantSet.php` length; `api_v1.php:45-57`, `bootstrap/app.php:14-24`, `IdentifyTenantFromHeader.php:25-50`, `CommitteeMemberController.php` (grep); `MemberController.php:95, 125-132, 309, 338`; `MembershipFeeController.php:140-160`; the dashboard controller `:26-34, :90-100`.
- **REPORTED, not re-checked:** S-6, S-8, S-9, S-11; the owners of BQ-P; PBDIGIT-293 / 295 / 261 relevance; the PBDIGIT-264 evidence folders.
- **Cannot be concluded without execution:** current test results; HTTP outcomes; binding-versus-middleware order; whether the session is saved on error responses; the winning duplicate route; the production cache driver; real-data prevalence of any defect.
- **Not in scope:** Adjudication; IP work; the shared outbox's tenant-context defects (only noted as a dependency of PUB).

---

## 11. Completion summary

- **Can the plan be relied upon?** As a findings inventory, yes. As a sequencing and decision instrument, **not until P-01 … P-11 are applied** and re-checked.
- **Decisions needed before implementation beyond A1:** DEC-SEC-DB (with P-DB, I-1); the acceptance verdicts after A1; then BD-REV-ER and D-COR-3 (COR-2); Package G (Slice 2b End, COR-3a, HTTP, security fixes); V-5 (COR-4); V-8, V-11 (before Slice 2b binding); V-7 (before HTTP); PUB (before any event emission).
- **What blocks the next wave and what can wait:** A1 is blocked only by its own authorisation. Everything database-related waits for DEC-SEC-DB. Everything live waits for D-6 / R10, ARB-MEM-01 and the security fixes.
- **Session 2's first action once authorised:** H-1.

**Single safest next action:** the **Human PO** authorises H-1 (A1) — naming commit `83a059f98`, the paths in §8, the isolated-copy `composer install`, and the evidence folder. In parallel, the **ARB with Infrastructure** decides DEC-SEC-DB, which is the critical path for every database and security step.
