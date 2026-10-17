
The issuance adapter's docblock says CoordinatesAdjudication "opens the issuance transaction" (CoordinatorIssuanceRequest.php:15). But CoordinatesAdjudication itself calls save() (line 72) and then enqueue() (line 75) with no transaction, and its own docblock (line 25) says "the real adapter wraps" it. The adapter injects the undecorated coordinator (line 32), so the comments contradict each other and the code. This is a likely defect. It is latent, because no production caller exists.

The investigation is complete. The full report follows.

---

Read-only architecture verification: Committee, Membership and Geography vs the Adjudication workstream

A. Executive summary

Verdict: partially suitable, needing targeted changes. For Adjudication specifically it is suitable only with safeguards: Adjudication does not consume Committee, Membership or Geography at all today, so their defects cannot corrupt a ruling yet. They become blocking the moment Adjudication's authority intake is wired.

Verified facts:
1. Adjudication has no code dependency on Committee, Membership or Geography. Its issuing authority is an opaque, caller-supplied string, validated only as non-empty (app/Contexts/Adjudication/Domain/Determination/IssuedByAuthority.php:18-20). The single grep match on those names is a comment (TransactionalAdjudicationService.php:15).
2. Committee, Membership and Geography have confirmed tenant-isolation, authorization and integrity defects:
   - committee self-approval;
   - possible cross-organisation committee review;
   - an unguarded Governance committee API;
   - destructive history rewrites;
   - conditional uniqueness;
   - Geography without organisation filtering, with cross-organisation parents and cascading deletes;
   - client-supplied geography accepted unvalidated.
3. No approved decision defines which source establishes adjudication authority. EPIC-004 Q-1 requires a valid delegation at issuance time; the contract and source are open.

Unresolved:
- the runtime exploitability of the cross-organisation paths (static evidence only; no requests sent);
- the deployed database schema (index existence; whether migrations ran).

B. Scope and evidence

- Repository: /home/…/nrna1 · branch knowelegeos-modelling · HEAD bf605092c (2026-10-11 08:12).
- Initial working tree: 35 modified tracked files and 107 untracked files, all pre-existing. They include uncommitted Membership kernel edits by another session (e.g. AdmitMemberHandler.php, Member.php, EndKind.php). None were touched.
- Inspected:
  - Committee: Membership/Domain/Committee, Application/Committee*, Governance/**, Committee/**; routes committee/committeeRoutes.php, api_v1.php, governance-api.php;
  - Membership: legacy controllers and requests, Organisational kernel ports;
  - Geography: models, services, migrations, Admin/GeoUnitController;
  - Adjudication: app/Contexts/Adjudication/**, provider, issuance adapter;
  - ADRs: ADR-0001, ADR-T-LOG; the ratified Membership act; EPIC-004_Q1_Authority_Resolution.md; the adjudication blueprint and remediation gate; the cross-domain candidate documents;
  - shared: BelongsToTenant, LaravelEventBus, config/database.php (connection names only).
- Method: my own static verification, plus four independent read-only reviewer subagents (Membership, Geography, Committee/Governance, Adjudication). I spot-checked every P0 claim used below.
- Deliberately not executed: any test, artisan command, migration, seeder or HTTP request (DB-backed tests require the isolated database, ARB-SEC-01). .env* was not read, and no configuration values were printed (a credential exposure is reported in PBDIGIT-270).
- No repository file was created or changed in this assignment.
- Limitations:
  - runtime middleware behaviour, the actual DB schema state and deployed environment values are UNKNOWN;
  - the reported counts of reviewer-collected files are approximate grep counts.

C. Current-state implementation map

Committee: four coexisting "member in committee" representations.

┌───────────────────────┬────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────┬─────────────────────────┬──────────────┐
│         Model         │                     Store                      │                                       Write path                                        │         History         │     Role     │
├───────────────────────┼────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────┼──────────────┤
│ A, application →      │ committee_associations + applications +        │ POST organisations/{organisation}/committee/membership/apply and /review                │ status only             │ none         │
│ association           │ lineage                                        │ (committeeRoutes.php:78,80) → ReviewMembershipApplicationHandler                        │                         │              │
├───────────────────────┼────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────┼──────────────┤
│ B, Committee          │                                                │ committeeRoutes.php:50-63 → AssignMemberToCommittee (in DB::transaction,                │ mutable joined_date /   │ role_path    │
│ aggregate             │ committee_assignments                          │ AssignMemberToCommittee.php:22)                                                         │ left_date / is_active   │ attribute    │
│ (Membership)          │                                                │                                                                                         │                         │              │
├───────────────────────┼────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────┼──────────────┤
│ C, Governance role    │ committee_member_projection                    │ api_v1.php:49-56, governance-api.php → Api/Governance/CommitteeMemberController         │ none (hard delete)      │ enum         │
│ map                   │                                                │                                                                                         │                         │              │
├───────────────────────┼────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────┼──────────────┤
│ D, participants /     │ organisation_participants,                     │                                                                                         │                         │              │
│ flags                 │ users.is_committee_member,                     │ organisations.php:140-147                                                               │ overwrite               │ string       │
│                       │ user_organisation_roles                        │                                                                                         │                         │              │
└───────────────────────┴────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────┴─────────────────────────┴──────────────┘

Membership:
- Live: legacy App\Models\Member / members.
- Ratified kernel: Organisational/*, persisting Admit / End only (MembershipAct.php enum), with no caller outside itself.
- Committee code lives in Membership (ADR-0001, Accepted).

Geography:
- one shared geo_administrative_units table with nullable organisation_id (added by database/migrations/2026_05_13_134529…);
- no BelongsToTenant on GeoAdministrativeUnit;
- path strings in three conventions;
- valid_from / valid_to defined (Landlord migration :58-59) but unused;
- FK parent_id ON DELETE CASCADE (:74, :80).

Adjudication:
- single live entry: the inbox consumer ChallengeRouted → openFor;
- the decision intake (receiveRulingDecision, admitIssuanceContext, finalize) has no production caller (grep in app/, routes/ outside Adjudication: none);
- facts are admitted as opaque snapshots, never read live.

mermaid
flowchart LR
  subgraph Confirmed
    UI[Routes / controllers] -->|legacy writes| MEMB_L[(members — legacy Membership)]
    UI -->|model A/B| COMM_M[(committee_associations / committee_assignments<br/>code in Membership ctx)]
    UI -->|model C, no authz| COMM_G[(committee_member_projection<br/>Governance API)]
    UI -->|no org filter| GEO[(geo_administrative_units)]
    COMM_M -. client-supplied geo ids, unvalidated .-> GEO
    MEMB_L -. GeographyLookupService 'tenant' conn (undefined) .-> GEO
    GOV_C[Governance domain] -->|imports types| MEMB_D[Membership domain types]
    INBOX[Inbox: ChallengeRouted] --> ADJ[Adjudication<br/>authority = opaque string]
  end
  KERNEL[(org_memberships kernel)]:::iso
  classDef iso stroke-dasharray: 5 5

Confirmed absences: Adjudication → Committee / Membership / Geography (none); kernel → any live caller (none).

D. Relationship and ownership matrix

Fact / rule: Organisation membership lifecycle
Approved owner: Membership
Status of approval: Ratified (2026-10-08 act)
Actual writers: legacy Member (live); kernel (unused)
Readers: Elections (legacy), Committee eligibility (none)
Respects ownership?: No: two models; the ratified one is not live
────────────────────────────────────────
Fact / rule: Committee write model
Approved owner: Conflict: ADR-0001 (Accepted) says Membership; act note N-6 (ratified) says it is a legacy defect, to move to Governance
Status of approval: Open (DR-07)
Actual writers: Membership (A, B) and Governance API (C)
Readers: dashboard reads A
Respects ownership?: No: three writers of one fact
────────────────────────────────────────
Fact / rule: Committee assignment history
Approved owner: (Governance, proposed)
Status of approval: Proposed
Actual writers: B overwrites left_date of ended rows (Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:143-149)
Readers: —
Respects ownership?: No: destructive
────────────────────────────────────────
Fact / rule: Committee geography / jurisdiction
Approved owner: Governance assigns; Geography owns units (proposed)
Status of approval: Proposed / Open (BQ-J1)
Actual writers: client-supplied in model A (ApplyForCommitteeMembershipRequest.php:22-24; controller :49-51); six columns on committees (reviewer)
Readers: —
Respects ownership?: No
────────────────────────────────────────
Fact / rule: Geographic hierarchy
Approved owner: Geography
Status of approval: implied; XD-5 open
Actual writers: GeoUnitController::store, seeder, repository (three path conventions)
Readers: Governance query service, Membership
Respects ownership?: partially; tenant isolation broken
────────────────────────────────────────
Fact / rule: Adjudication authority
Approved owner: Governance delegation at issuance (EPIC-004 Q-1)
Status of approval: Decided and frozen (Q-1 rule); contract Open
Actual writers: caller-supplied string
Readers: none
Respects ownership?: rule unenforced (no port)
────────────────────────────────────────
Fact / rule: Determination record
Approved owner: Adjudication
Status of approval: Ratified ADR-T1 / T19; Final immutability approved (domain README)
Actual writers: updateOrCreate + softDeletes (reviewer AJ-07); no finaliser
Readers: outbox
Respects ownership?: No: mutable, never final
────────────────────────────────────────
Fact / rule: Voter-identity exclusion
Approved owner: constitutional ADR-T11
Status of approval: Ratified
Actual writers: no voter columns in the schema (reviewer)
Readers: —
Respects ownership?: yes (structurally); free-text Reason unguarded (AJ-09)

E. Legacy-to-architecture gap analysis

Legend: C = confirmed by my own reading at HEAD; R = confirmed by a reviewer and not re-read by me.

ID: F-01
Context: Committee–Membership
Actual behaviour: self-approval: MembershipApplication::approve($reviewedBy) never compares the reviewer with the applicant; FormRequest authorize() = authenticated + verified only; the feature test applies and
approves as the same user, expecting 200
Expectation: only a competent organ decides (participation contract, N-6)
Evidence: MembershipApplication.php:102-112; ReviewMembershipApplicationRequest.php:11-14; CommitteeMembershipApplicationTest.php:144-165,215-226 (C)
Sev: P0
Conf: High (static)
Consequence: anyone can admit themselves into a committee
Recommended action: RED test → reviewer ≠ applicant + ability check
────────────────────────────────────────
ID: F-02
Context: Committee tenant
Actual behaviour: review / apply route group has auth, verified only, no ensure.organisation; the organisation comes from the URL binding; the handler loads by the URL tenant
Expectation: caller must belong to the organisation
Evidence: committeeRoutes.php:26-27,78-80; routed controller Membership/Infrastructure/Http/Controllers/Desktop/CommitteeMembershipApplicationController.php:78-95 (C)
Sev: P0
Conf: Medium (plausible; runtime unverified)
Consequence: a verified user of org A may review org B's applications by URL
Recommended action: runtime test on the isolated DB; then middleware + policy
────────────────────────────────────────
ID: F-03
Context: Committee (Governance API)
Actual behaviour: add / remove committee member: no authorize / Gate
Expectation: PDP / policy before every write
Evidence: Api/Governance/CommitteeMemberController.php (grep: none) (C)
Sev: P0
Conf: High
Consequence: any verified org member assigns roles, incl. chair
Recommended action: PBDIGIT-266 item 3
────────────────────────────────────────
ID: F-04
Context: Committee
Actual behaviour: remove handler imports a non-existent Governance\Domain\Committee\MemberId
Expectation: —
Evidence: RemoveCommitteeMemberHandler.php:10; directory listing (C)
Sev: P1
Conf: High
Consequence: removal through the API errors (caught as 500 per reviewer)
Recommended action: fix after F-03's guard
────────────────────────────────────────
ID: F-05
Context: Committee history
Actual behaviour: save step 4 sets left_date = now() on all rows not in the aggregate, incl. already-ended ones
Expectation: append-only history (TR-1)
Evidence: Repositories/EloquentCommitteeRepository.php:143-149 (C)
Sev: P1
Conf: High
Consequence: tenure history silently rewritten
Recommended action: restrict to active rows; regression test
────────────────────────────────────────
ID: F-06
Context: Committee uniqueness
Actual behaviour: partial unique indexes created only if config('database.default') ∈ {pgsql, tenant_test} (default fallback mysql); committee_associations has no unique active constraint
Expectation: INV-P2 / P3 backed by DB or locks
Evidence: …/Tenant/2026_01_16_000003…:95-116; config/database.php:18; …committee_associations…:35-38 (C)
Sev: P1
Conf: Medium (deployed state UNKNOWN)
Consequence: double assignments / associations under concurrency
Recommended action: unconditional pgsql index; verify pg_indexes (read-only)
────────────────────────────────────────
ID: F-07
Context: Committee–Geography
Actual behaviour: committee application geography taken from client input (committee_geo_unit_id integer only; path and segments free)
Expectation: geography validated against an authoritative, org-scoped source
Evidence: ApplyForCommitteeMembershipRequest.php:19-24; controller :43-51 (C)
Sev: P1
Conf: High
Consequence: forged or foreign geography stored on applications
Recommended action: validate the id exists in the same organisation; derive the path server-side
────────────────────────────────────────
ID: F-08
Context: Geography tenant
Actual behaviour: GovernanceGeoUnitQueryService::fetchFlat / lookup query geo_administrative_units with no organisation_id filter; the model has no tenant scope
Expectation: org-scoped reads
Evidence: GovernanceGeoUnitQueryService.php:55-96,175-210; GeoAdministrativeUnit.php (C)
Sev: P0
Conf: High (static)
Consequence: organisation-custom units visible to other organisations
Recommended action: org filter (IS NULL OR = :o); multi-org RED test
────────────────────────────────────────
ID: F-09
Context: Geography parent
Actual behaviour: store validates the parent globally and findOrFails it with no organisation check
Expectation: parent in the same organisation or official
Evidence: GeoUnitController.php:96,105-107 (C)
Sev: P0
Conf: High
Consequence: org A attaches units under org B's tree
Recommended action: parent-organisation validation
────────────────────────────────────────
ID: F-10
Context: Geography integrity
Actual behaviour: FK cascade delete; three path conventions; valid_from unused
Expectation: history preserved; one path rule
Evidence: Landlord …000002…:58-59,74,80 (C); conventions (R)
Sev: P1
Conf: High
Consequence: subtree loss; wrong containment
Recommended action: retire via valid_to; RESTRICT; normalise paths
────────────────────────────────────────
ID: F-11
Context: Membership–Geography
Actual behaviour: GeographyLookupService uses the undefined connection 'tenant'; module "not installed" → hierarchy validation passes silently, while the id-exists check returns false
Expectation: explicit validation outcome
Evidence: GeographyLookupService.php:51,56-64,128-133; connection names config/database.php:38-140 (C)
Sev: P1
Conf: High (static)
Consequence: inconsistent: hierarchy check skipped, existence check rejects
Recommended action: default connection + org filter; explicit outcome
────────────────────────────────────────
ID: F-12
Context: Tenancy (shared)
Actual behaviour: BelongsToTenant scope = TenantContext → session → fallback to the "platform" organisation
Expectation: explicit organisation id
Evidence: app/Traits/BelongsToTenant.php:44-58 (C)
Sev: P1
Conf: High
Consequence: outside requests (jobs, relay) the scope silently becomes "platform"
Recommended action: explicit tenant in repositories / handlers (PBDIGIT-211)
────────────────────────────────────────
ID: F-13
Context: Events
Actual behaviour: committee events dispatched synchronously inside the transaction; no listeners exist for CommitteeMemberAssigned / Removed
Expectation: publish after commit (outbox, ADR-T3)
Evidence: AssignMemberToCommittee.php:22,41-42; LaravelEventBus.php:11-16; EventServiceProvider.php (C)
Sev: P2
Conf: High
Consequence: harmless today; unsafe once a side-effecting listener is added
Recommended action: outbox or after-commit
────────────────────────────────────────
ID: F-14
Context: Membership kernel
Actual behaviour: ratified kernel has no live caller; Admit / End only; authority checked outside the transaction; port lacks subject / as-of
Expectation: ratified + integration contracts
Evidence: MembershipAct.php; grep (C); DecideAndAppend.php:119, PostgresMemberRepository.php:137 (R)
Sev: P1
Conf: High
Consequence: cannot serve Committee or Adjudication eligibility
Recommended action: ports + cutover (separate track)
────────────────────────────────────────
ID: F-15
Context: Membership end → Committee
Actual behaviour: no consumer reacts to membership end; assignments persist
Expectation: Open (BQ-P3); target: derived EffectiveTenure (proposed)
Evidence: grep (C)
Sev: — (open decision)
Conf: High
Consequence: ex-members keep committee roles in legacy data
Recommended action: do not implement an auto-termination rule without a decision
────────────────────────────────────────
ID: F-16
Context: Adjudication authority
Actual behaviour: IssuedByAuthority = non-empty string; no authority port
Expectation: EPIC-004 Q-1: valid delegation at issuance, refuse without an assertion (frozen)
Evidence: IssuedByAuthority.php:18-20 (C); EPIC-004_Q1…:4-25 (R)
Sev: P1 (latent)
Conf: High
Consequence: any future intake caller issues under any string
Recommended action: an assertion port evaluated inside the conclusion transaction, failing closed
────────────────────────────────────────
ID: F-17
Context: Adjudication evidence
Actual behaviour: only an authority string is stored; no assertion id, version or evaluation time
Expectation: reconstruction of the decision basis
Evidence: migrations (R, AJ-02)
Sev: P1 (latent)
Conf: High
Consequence: authority unverifiable after the fact
Recommended action: persist the assertion reference + instant (additive event v4, ADR-T5)
────────────────────────────────────────
ID: F-18
Context: Adjudication atomicity
Actual behaviour: CoordinatesAdjudication does save (:72) then enqueue (:75) with no transaction; the adapter injects it directly while its docblock claims the coordinator "opens the issuance transaction"
Expectation: single transaction (ADR-T3)
Evidence: CoordinatesAdjudication.php:22-25,72-76; CoordinatorIssuanceRequest.php:15,32; provider :49-53 (C)
Sev: P1 (latent)
Conf: Med-High
Consequence: lost DeterminationIssued, acknowledged forever on redrive (R)
Recommended action: PBDIGIT-178
────────────────────────────────────────
ID: F-19
Context: Adjudication tenancy
Actual behaviour: inbox handler ignores the message organisation; ambient tenant used
Expectation: explicit organisation
Evidence: runtime-confirmed in the remediation gate T-1 / T-4 (R)
Sev: P1
Conf: High
Consequence: process under the wrong organisation
Recommended action: PBDIGIT-211
────────────────────────────────────────
ID: F-20
Context: Adjudication immutability
Actual behaviour: determination row mutable (updateOrCreate, softDeletes); no finaliser; ADR-T19 projection unbuilt
Expectation: "Final and immutable" (approved domain README)
Evidence: (R, AJ-07 / AJ-08)
Sev: P2
Conf: High
Consequence: no append-only ruling record
Recommended action: ARB: realise ADR-T19 / retention
────────────────────────────────────────
ID: F-21
Context: Governance → Membership
Actual behaviour: the Governance domain imports Membership domain types
Expectation: contexts integrate via contracts
Evidence: (R, C-13: 27 files)
Sev: P2
Conf: High
Consequence: inverted dependency blocks DR-07
Recommended action: own identifiers + ACL

F. Defects and risks, classified

- Confirmed defects (static, high confidence):
  - F-01 self-approval;
  - F-03 unguarded Governance committee API;
  - F-04 broken import;
  - F-05 history rewrite;
  - F-07 unvalidated client geography;
  - F-08 / F-09 Geography without an organisation filter and with a foreign parent;
  - F-11 undefined connection;
  - F-13 in-transaction dispatch (no current consumer).
- Likely defects (static; runtime unverified):
  - F-02 cross-organisation committee review;
  - F-06 missing uniqueness on some deployments (schema state UNKNOWN);
  - F-18 non-atomic issuance (latent: no production caller).
- Design weaknesses:
  - F-12 implicit / session tenant scope with a platform fallback;
  - F-14 kernel not integrated;
  - F-17 / F-20 missing authority evidence and immutability;
  - F-21 inverted dependency.
- Hypotheses needing safe runtime verification:
  - whether F-02 and F-08 are exploitable through the deployed middleware stack;
  - whether the F-06 indexes exist in each database;
  - whether F-04 returns 500 (reviewer claim).

Proposed experiments (on an isolated disposable database only):
- a non-member of org B calls the review, Governance API and geo-unit endpoints for org B (expect 403);
- SELECT indexname FROM pg_indexes WHERE tablename = 'committee_assignments' (read-only).

No vulnerability is declared exploitable here. The evidence is static.

G. Suitability by relationship

┌────────────────────────┬────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│      Relationship      │                                                                                     Assessment                                                                                     │
├────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Committee–Membership   │ Unsuitable as an authority source until F-01…F-06 are fixed and DR-07 is decided. Three writers of one fact; mutable, lossy history; no eligibility link to membership (an open    │
│                        │ business rule).                                                                                                                                                                    │
├────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Committee–Geography    │ Unsuitable for jurisdiction: client-supplied geography (F-07); single-valued columns on committees; no history.                                                                    │
├────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Membership–Geography   │ Partially suitable: identifiers are stored. Validation is inconsistent (F-11), not organisation-aware, and outside the transaction; no snapshot or revision reference.             │
├────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ All three ↔            │ Isolated today (good), but Adjudication's Q-1 authority rule is unenforced (F-16, F-17). No legacy committee model, flag, Spatie role or Membership store may be used as the       │
│ Adjudication           │ authority source. The approved intent is a Governance delegation whose contract is open.                                                                                           │
└────────────────────────┴────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

H. Recommended target relationships (PROPOSED / OPEN)

mermaid
flowchart LR
  MEMB[Membership<br/>MembershipQueryPort — PROPOSED]:::p
  GOV[Governance<br/>Committee/Position/Tenure, Delegation — OPEN: DR-07, S1a]:::o
  GEO[Geography<br/>GeographyQueryPort with revision — PROPOSED, XD-5 OPEN]:::p
  ADJ[Adjudication]
  GOV -->|eligibility query| MEMB
  GOV -->|unit identity / containment| GEO
  ADJ -->|AuthorityAssertionPort: valid delegation at issuance — rule FROZEN (Q-1), contract OPEN| GOV
  ADJ -. jurisdiction reference — OPEN (D-4) .-> GEO
  classDef p stroke-dasharray: 4 2
  classDef o stroke-dasharray: 2 2,stroke-width:2px

Contracts (all proposed):
- AuthorityAssertionPort (Adjudication → Governance): assert(org, actorRef, decisionKind, challengeRef, at) → {assertionId, version, validFrom/To, outcome}. Evaluated inside the conclusion transaction; fails closed; its result is persisted with the determination.
- MembershipQueryPort: isMember(org, personRef, asOf, knownAtVersion) → {T|F|U, scope, version}.
- GeographyQueryPort: contains / ancestors(org, unitId, revision) → {answer, revision}. Organisation-scoped.

I. Prioritized roadmap

┌─────┬─────────────────────────────────────────────────────────────────────────┬──────────────────────────┬─────────────────────────────────────────────────────────────────┬───────────────────────────────┐
│  P  │                                 Action                                  │        Depends on        │                           Acceptance                            │        Approval needed        │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P0  │ provision the isolated test database with evidence                      │ infrastructure           │ evidence reviewed                                               │ infrastructure owner,         │
│     │                                                                         │                          │                                                                 │ ARB-SEC-01                    │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P0  │ RED tests + guards: F-01, F-02, F-03, F-08, F-09 (+ member export,      │ isolated DB              │ negative tests 403 for same-org non-privileged and other-org    │ PBDIGIT-266 authorisation +   │
│     │ debug routes per earlier reviews)                                       │                          │ users; applicant ≠ reviewer                                     │ new items                     │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P1  │ F-05 history fix; F-06 uniqueness; F-04 import (after F-03)             │ P0 tests exist           │ regression tests; pg_indexes evidence                           │ story authorisation           │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P1  │ F-07, F-11 geography validation; F-10 retire, not delete; path          │ P0                       │ org-scoped validation tests; invariant query = 0                │ story authorisation; XD-5 for │
│     │ normalisation                                                           │                          │                                                                 │  history                      │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P1  │ F-12 / F-19 explicit tenant outside requests                            │ —                        │ PBDIGIT-211 AC-R1…R4                                            │ ARB Q1–Q7                     │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P1  │ Adjudication: F-18 atomic issuance; F-16 / F-17 authority assertion     │ Q-1 contract, D-1 / D-2  │ issuance + outbox in one transaction; refuses without an        │ PO / ARB (PBDIGIT-177 / 178)  │
│     │ port + evidence                                                         │ / D-8                    │ assertion; assertion persisted                                  │                               │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P2  │ DR-07 → Governance owns Committee; legacy frozen as oracle; S1a         │ DR-07                    │ reconciliation parity                                           │ PO / ARB                      │
│     │ Governance delegation persistence                                       │                          │                                                                 │                               │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P2  │ F-20 immutable ruling record (ADR-T19 projection); F-13 outbox for      │ ARB                      │ append-only evidence; replay                                    │ ARB                           │
│     │ committee events                                                        │                          │                                                                 │                               │
├─────┼─────────────────────────────────────────────────────────────────────────┼──────────────────────────┼─────────────────────────────────────────────────────────────────┼───────────────────────────────┤
│ P3  │ F-21 own identifiers; kernel integration and cutover (separate          │ DR-07; ARB-MEM-01        │ —                                                               │ existing gates                │
│     │ Membership track)                                                       │                          │                                                                 │                               │
└─────┴─────────────────────────────────────────────────────────────────────────┴──────────────────────────┴─────────────────────────────────────────────────────────────────┴───────────────────────────────┘

J. Verification matrix

┌────────────────────────────┬─────────────────────────────┬─────────────────────────────────────────────────────────┬─────────────────────┬──────────────┬──────────────┬──────────────────────────────────┐
│         Invariant          │       Implementation        │                      Existing test                      │   What it proves    │ Independent? │    Status    │          Safe next step          │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Cross-tenant isolation     │ URL org binding, no         │ none found for review                                   │ —                   │ —            │ NOT TESTED   │ isolated-DB request as a foreign │
│ (Committee)                │ membership middleware       │                                                         │                     │              │              │  user                            │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Cross-org Geography parent │ none                        │ GeoUnitControllerTest.php:103-118 (R: passes because    │ false positive      │ no           │ FAIL         │ multi-org RED test with          │
│                            │                             │ the other org has no levels)                            │                     │              │ (static)     │ populated levels                 │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Assignment uniqueness      │ conditional index           │ UNKNOWN                                                 │ —                   │ —            │ UNKNOWN      │ read pg_indexes                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ History preservation       │ step-4 update               │ none                                                    │ —                   │ —            │ FAIL         │ RED test across two saves        │
│                            │                             │                                                         │                     │              │ (static)     │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Self-approval prevention   │ none                        │ test_review_application_approve asserts self-approval   │ codifies the defect │ no           │ FAIL         │ invert the test (RED)            │
│                            │                             │ succeeds                                                │                     │              │              │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Removal                    │ broken import               │ (R)                                                     │ —                   │ —            │ FAIL         │ after the guard: feature test    │
│                            │                             │                                                         │                     │              │ (static)     │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Membership eligibility /   │ none (open rule)            │ —                                                       │ —                   │ —            │ NOT TESTED / │ after BQ-P3                      │
│ revocation                 │                             │                                                         │                     │              │  OPEN        │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Authorization at write     │ partial (B:                 │ —                                                       │ —                   │ —            │ PARTIAL      │ PBDIGIT-266 tests                │
│ boundaries                 │ manageCommittee; C: none)   │                                                         │                     │              │              │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Hierarchy moves / stale    │ no revision; cascade delete │ none                                                    │ —                   │ —            │ NOT TESTED   │ after XD-5                       │
│ references                 │                             │                                                         │                     │              │              │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Transactions / concurrency │ DB::transaction (B); none   │ none concurrent                                         │ —                   │ —            │ NOT TESTED   │ isolated-DB concurrent assigns   │
│  (committee)               │ (A)                         │                                                         │                     │              │              │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Duplicate events / retries │ inbox dedupe; idempotent    │ ChallengeRoutedConsumptionTest (R; ambient tenant set)  │ dedupe under an     │ partly       │ PARTIAL      │ relay test with no ambient       │
│  (Adjudication)            │ openFor                     │                                                         │ ambient tenant      │              │              │ tenant (PBDIGIT-211)             │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Adjudication authority     │ non-empty string            │ DeterminationValueObjectsTest, process-manager test     │ non-empty only      │ no           │ FAIL vs Q-1  │ after the port: refuse without   │
│ evaluation                 │                             │ with doubles (R)                                        │                     │              │              │ an assertion                     │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Historical reconstruction  │ opaque snapshots; mutable   │ IssuanceContextRoundTripTest (R)                        │ round-trip only     │ partly       │ PARTIAL      │ immutability + assertion         │
│                            │ rows                        │                                                         │                     │              │              │ evidence                         │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Forward-only correction /  │ state guards; no finaliser; │ AdjudicationProcessStateTest, uniqueness tests (R)      │ sequential guards   │ yes          │ PARTIAL      │ concurrent-decision DB test      │
│ audit                      │  soft deletes               │                                                         │                     │              │              │                                  │
├────────────────────────────┼─────────────────────────────┼─────────────────────────────────────────────────────────┼─────────────────────┼──────────────┼──────────────┼──────────────────────────────────┤
│ Voter-data exclusion       │ no voter columns; free-text │ GreenfieldCoreArchitectureTest (static token scan)      │ code tokens only    │ partly       │ PARTIAL      │ content rule for Reason (D-7)    │
│ (ADR-T11)                  │  reason                     │                                                         │                     │              │              │                                  │
└────────────────────────────┴─────────────────────────────┴─────────────────────────────────────────────────────────┴─────────────────────┴──────────────┴──────────────┴──────────────────────────────────┘

No test was run in this assignment. The statuses above are from static reading.

K. Open decisions

┌──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬────────────────────────────────────┬────────────────────────────────────────┐
│                                                           Decision                                                           │            Who decides             │                 Blocks                 │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ DR-07 committee ownership (ADR-0001 vs N-6)                                                                                  │ PO / ARB                           │ all Governance work; the authority     │
│                                                                                                                              │                                    │ source                                 │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ EPIC-004 Q-1 contract name and issuance-time validation design                                                               │ PO / ARB                           │ F-16 / F-17                            │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ Blueprint D-1…D-9 (deciding body, appointer, procedure, jurisdiction, standing, finality, free text, scope vocabulary,       │ PO / ARB / Domain Owner (DPO for   │ Adjudication integration               │
│ authorisations)                                                                                                              │ D-7)                               │                                        │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ PBDIGIT-177 / 178 / 197 / 211 (ARB Q1–Q7)                                                                                    │ PO / ARB                           │ intake, atomicity, finality, tenancy   │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ BQ-P3 (membership end → tenure), BQ-P1 / BQ-INC, BQ-J1                                                                       │ PO / statute owners                │ committee rules                        │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ XD-5 geography history; XD-15 tenancy                                                                                        │ PO / ARB                           │ jurisdiction reconstruction            │
├──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────┼────────────────────────────────────────┤
│ ADR-T19 realisation and retention                                                                                            │ ARB                                │ immutable ruling record                │
└──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴────────────────────────────────────┴────────────────────────────────────────┘

L. Final TODO checklist

Immediate:
- provision the isolated test database;
- authorise PBDIGIT-266 (incl. F-01 / F-02 / F-03) and new items for F-08 / F-09, the member export and the debug routes;
- read-only pg_indexes check for F-06.

After authorisation:
- RED tests, then guards for F-01, F-02, F-03, F-08, F-09;
- fixes for F-05, F-06, F-07, F-11, then F-04.

After contracts are agreed:
- AuthorityAssertionPort (Q-1);
- MembershipQueryPort;
- GeographyQueryPort with revisions;
- explicit tenant (PBDIGIT-211);
- atomic issuance (PBDIGIT-178).

Adjudication integration acceptance:
- issuance refuses without a valid assertion;
- assertion evidence persisted and replayable;
- issuance and outbox atomic;
- tenant taken from the message;
- concurrent-decision test;
- no live read of legacy committee models.

Deferred:
- DR-07 migration (legacy as oracle);
- ADR-T19 projection;
- Membership cutover;
- removal of is_committee_member and global hasRole;
- outbox for committee events.

M. Principal architect conclusion

1. Keep unchanged:
   - the ratified Membership domain rules and kernel;
   - Adjudication's isolation from legacy Committee, Membership and Geography (opaque, snapshotted references);
   - inbox dedupe;
   - ADR-T11 structural exclusion;
   - Adjudication's state guards.
2. Correct:
   - self-approval and committee write authorization (F-01…F-03);
   - Geography organisation isolation and parent validation (F-08, F-09);
   - committee history rewrite (F-05);
   - unvalidated client geography (F-07);
   - the inconsistent geography validation (F-11);
   - non-atomic issuance (F-18).
3. Isolate behind contracts:
   - adjudication authority (AuthorityAssertionPort → Governance);
   - membership eligibility (MembershipQueryPort);
   - geographic containment (GeographyQueryPort with revision);
   - explicit tenant resolution.
4. Do not implement yet:
   - any use of legacy committee tables, flags, Spatie roles or the legacy Membership store as adjudication authority;
   - automatic termination of committee assignments on membership end (BQ-P3);
   - Governance migration before DR-07;
   - geography-based jurisdiction in Adjudication before D-4 and XD-5.
5. Next smallest safe step: provision the isolated test database. Then write one RED feature test that inverts test_review_application_approve (the applicant must not approve their own application) plus a foreign-organisation request to the same route. This is read-only evidence-gathering on disposable data and confirms or refutes F-01 / F-02 at runtime.

N. Evidence and confidence

- Proven (static, read at HEAD bf605092c):
  - F-01, F-03, F-04, F-05, F-07, F-08, F-09, F-11, F-12, F-13;
  - the absence of Adjudication dependencies;
  - IssuedByAuthority non-empty-only;
  - the F-18 code / docblock contradiction.
- Suggested by static analysis only: F-02 exploitability; F-06 deployed index state; the F-04 runtime status; F-19 (runtime-confirmed in an earlier gate document, not by me).
- Unknown: the deployed DB schema and data; middleware behaviour at runtime; environment configuration.
- Nothing was executed or changed in this assignment. Earlier evidence-gate scores are not restated as implementation progress.

✻ Sautéed for 7m 2s · done 8:37 AM · 1 shell still running

● Background command "Rerun E9 only on private cluster" completed (exit code 0)

  Ran 1 shell command

E9 finished (exit 0, all four parts measured), so it can now be closed as research evidence:

┌────────────────────────────────────────────────────────────────────────────┬───────────────────────────────────────────────────────┬──────────────┬───────────────────────┬───────────┐
│                                    Part                                    │                         Rule                          │ Unauthorised │ Duplicate memberships │ Deadlocks │
├────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────┼──────────────┼───────────────────────┼───────────┤
│ E9a: guard on "not a member" vs first admission (2 sessions, 35 schedules) │ lock the matching rows                                │ 16           │ —                     │ 0         │
├────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────┼──────────────┼───────────────────────┼───────────┤
│                                                                            │ organisation-level version record owned by Membership │ 0            │ —                     │ 0         │
├────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────┼──────────────┼───────────────────────┼───────────┤
│ E9b: guard vs two simultaneous creators (3 sessions, 4,200 schedules)      │ lock the matching rows                                │ 2,300        │ 2,520                 │ 0         │
├────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────────────┼──────────────┼───────────────────────┼───────────┤
│                                                                            │ organisation-level version record owned by Membership │ 0            │ 0                     │ 0         │
└────────────────────────────────────────────────────────────────────────────┴───────────────────────────────────────────────────────┴──────────────┴───────────────────────┴───────────┘

What it shows: a foreign context can safely guard "this person is not a member" without ever writing into Membership's tables. It does so by locking a version record that Membership owns and updates whenever it creates a membership. Two people creating the same membership at once are also kept in order, with no duplicates.

This is model evidence only: run on a private throwaway PostgreSQL, not on the real schema or Laravel code. The run showed 418 minutes of elapsed time because the machine slept through part of it; the cluster has been stopped.

The last assignment was read-only, so I did not write these results into the repository. Three places still say "pending":
- the closure document's §2.3 table;
- the models README;
- today's session log.

If you want, I can fill in those three now.

✻ Crunched for 11s · done 8:37 AM · 1 shell still running

● Background command "Wait for E9 run 5 to finish" completed (exit code 0)

That notification was just the background watcher for E9 exiting. Nothing new: the results are the ones in my previous message. Nothing is running in the background now, and the private test PostgreSQL has been stopped.

The only open item is your answer on whether I should write the final E9 results into the closure document, the models README and the session log.
