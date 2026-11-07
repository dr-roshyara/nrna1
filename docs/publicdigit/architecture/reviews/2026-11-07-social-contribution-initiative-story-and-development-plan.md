# Social Contribution: initiative story and development plan (nrna1)

> **This document is Phase 0 of the Social Contribution initiative: cross-project verification, initiative story and development plan.** It does not start strategic DDD design, assign bounded-context ownership, approve a model, or authorise implementation. Target milestone: **"Social Contribution Initiative Story and Development Plan — Principal Approved."**

| | |
|---|---|
| **Status** | PROPOSED, for Principal review. Not independently reviewed. |
| **Authority** | generated (Claude, Session A). Not authoritative without human review. |
| **Story** | `PBDIGIT-334` (Phase 0). Related: `PBDIGIT-331` (business discovery), `PBDIGIT-332` (PO decision on the existing module), `PBDIGIT-333` (generalisation, blocked) |
| **Authorisation** | Principal instruction "Social Contribution: Cross-Project Knowledge Transfer and Initiative Planning" (2026-11-07). Read all three projects; create this document. No code, schema, ADR, PBDIGIT-330, ODA-01, Membership-architecture or Contribution Points change. No implementation tickets. No commit. |
| **Date** | 2026-11-07 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0). Path as instructed. |
| **Supersedes / relates to** | Builds on `2026-11-07-social-contribution-cross-project-discovery.md` (**XPD**) and `2026-11-07-social-contribution-domain-discovery-phase-2.md` (**PH2**). PH2's interview guide and glossary are **Phase 1 preparation** in this plan's numbering (§11). |

**Labels.**
- [F] verified fact, with source.
- [R] reported by the Principal from the Komal Oli conversation; content not independently verified.
- [D] existing decision, with authority.
- [P] proposal by this document.
- [H] hypothesis.
- [I] inference.
- [OQ] open question.

**Transfer classes.**
- **REUSE**: minimal adaptation.
- **ADAPT**: valuable, but must change to fit nrna1.
- **REWRITE**: the idea is valuable, the old design is not carried forward.
- **REJECT**: unsuitable or conflicts with the validated purpose.
- **INVESTIGATE**: evidence insufficient.
- **ALREADY EXISTS**: nrna1 already provides it.

**Abbreviations.**
- **PD** = `public-digit` repository; **EL** = `election` repository; paths in their sections are relative to those roots.
- **nrna1** paths are relative to `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`.

**Transfer rule.** Every REUSE or ADAPT in this document transfers a **business idea or architectural pattern**, never code. [D] The instruction says: "Do not copy old code into nrna1."

---

## 1. Principal decisions

| ID | Decision | Authority |
|---|---|---|
| [D] Direction | `nrna1` is the current active project and the only target for Social Contribution. `public-digit` and `election` are old projects and reference sources only. No future development is planned in them. | Principal, 2026-11-07 |
| [D] Decision A | Start with Komal Oli's individual social contribution profile. Afterwards, investigate reuse for all party members. This does not authorise a general platform. | Principal, 2026-11-07 |
| [D] Decision B | The nrna1 Contribution Points module is a historical experiment, pending formal PO confirmation (`PBDIGIT-332`). Its formula is not approved business logic. It is not modified or retired. | Principal, 2026-11-07 |
| [D] Sequence | Verify → Extract → Evaluate → Adapt or rewrite → Design and develop in nrna1. Strategic DDD design starts only after this plan is approved. | Principal, 2026-11-07 |

---

## 2. Initiative story

**Background [H].** Individuals make social contributions. They organise activities, support communities, help individuals, start initiatives, contribute resources and sustain long-term programmes. The record of that work is scattered across personal files, appreciation certificates, photographs, reports and institutional documents. It is rarely consolidated, substantiated or presented coherently.

**Initial discovery case [R].** The Principal met Komal Oli, a Nepali political leader:
- She has received many appreciation certificates.
- She has made numerous social contributions.
- These are not systematically measured or consolidated.
- She wants to make them visible to members of her party and potentially the wider public.
- She wants to understand and demonstrate the extent of her social contribution.

Nothing further is attributed to her.

**Future possibility [H].** The same capability might later serve other party members. The individual case does **not** automatically define their requirements (`PBDIGIT-333`).

**What this initiative is not.** It is not office-holder performance (`PBDIGIT-330`), not financial donations, not political or organisational participation, not forum contribution, and not a score, ranking or leaderboard.

---

## 3. Business problem and intended value

**Problem [H; requires domain validation].** No established, evidence-backed method has yet been identified for consolidating an individual's social contributions, the recognition they received, and their demonstrable outcomes into a coherent history that can be shown credibly to chosen audiences.

**Intended value [H].** Enable an individual to:
1. maintain a consolidated history of social contributions;
2. present supporting evidence;
3. distinguish self-reported activities from verified contributions;
4. record recognition received from institutions and organisations;
5. demonstrate outcomes where evidence exists;
6. share an appropriate profile with authorised audiences.

**Boundary on value [D/P].** The platform must not imply that a number represents the full value of a person's social contribution.

---

## 4. Project positioning

| Project | Role | Basis |
|---|---|---|
| **nrna1** | **Target.** All approved design and development lands here. | [D] Principal direction. [F] Active: branch `election-flow-updated`, HEAD `78f87094c` (2026-11-07). Ratified Membership architecture, governance decision records, architecture tests, outbox/inbox. |
| public-digit | Reference: concepts, domain ideas, design proposals | [F] HEAD `a766ef5f2` (2026-01-09). Backend absent (§5.0). Only the Angular/Capacitor client is code. |
| election | Reference: workflow, approval and history patterns | [F] HEAD `50bb80e49` (2025-10-30). Database-per-tenant Laravel 12. No social-contribution feature. |

**Why nrna1 is the right target [I], beyond the Principal's direction:**
1. It is the only project with a live, governed domain model for the concepts this capability depends on: person, membership and organisation (§7.2).
2. It has the only **ratified** append-only, provenance-bearing decision ledger (§7.5).
3. It has an approved cross-context integration mechanism: outbox and inbox with correlation and causation (§7.6).
4. Its architecture is enforced by tests (deptrac and `tests/Architecture/*`), so a new capability can be guarded from its first day.
5. The two old projects contradict themselves on identity and lifecycle (§5.1, §5.3), so they cannot serve as a baseline.

---

## 5. Verified findings: public-digit (reference)

### 5.0 Repository state [F]

- `main` only; branches `main`, `origin/main`, `origin/HEAD`; no stash; 18 commits.
- `packages/laravel-backend` is a gitlink (`160000 a5a93136…`). The directory is empty, there is no `.gitmodules`, and `git cat-file -t a5a93136` fails ("Not a valid object name").
- Every ref contains exactly one `.php` file, a branding script.
- **Every backend "implemented" claim in PD is unverifiable.** The status values used below are: documented only, proposed, obsolete, unverifiable, and, for the mobile TypeScript types, partially implemented with no consumer.
- Filename date prefixes are unreliable. For example, `20250612_0847_all_political_parties_scheme.md` was first committed 2025-12-11.

### 5.1 Identity

| # | Finding | Source | Status | Original intention | Value for nrna1 | Change before reuse | Class |
|---|---|---|---|---|---|---|---|
| PD-1 | User (authentication) ≠ Member (standing): "Never put political logic on User"; "Membership IS NOT … a document store … a leaderboard entry" | `architecture/backend/membership-context/brainstorming/20260101_2309_architecture_review.md` §4 L97-128; `20260101_2313_membership_related_stuff.md` L29-40 | documented | Separate login from political standing | Contribution must not hang off a login account | **ALREADY EXISTS** in nrna1 as the XD-3b Person ≠ Account direction (§7.2) | ALREADY EXISTS |
| PD-2 | Three incompatible User/Member cardinalities | `2313` L102-107; `20260101_2340_what_is_member_and_what_is_not.md` L28; tenant-templates doc L414 | documented, contradictory | — | Shows the question is hard | nrna1 settles it differently (XD-3b, BR-5) | REJECT |
| PD-3 | No cross-organisation person. The older doc assumes one global login with tenant selection; newer docs assume per-tenant users | `architecture/design/ddd_bounded_contexts/boundaries/20251115_1627_boundaries.md` L52-85; `…/20251213_1213_claude_instructions_intigration_of_template_into_tenant.md` L16, L40-50 | proposed / obsolete | — | Confirms the gap a person-scoped profile would hit | nrna1 has its own (PROPOSED) XD-3a | REWRITE |
| PD-4 | Scope-based membership (geographic / functional / diaspora; "Identity + Affiliation + Scope"); geography optional via a `LocationAssignment` value object | `…/brainstorming/scopebased_concept/20260102_1111_scopebased_membership_context.md` L1-60, L283 | proposed | Diaspora and functional membership | Contributions are located somewhere; diaspora is relevant to NRN | Express as a location or scope value on the contribution, not a hard geography foreign key | ADAPT |

### 5.2 Evidence

| # | Finding | Source | Status | Value | Change | Class |
|---|---|---|---|---|---|---|
| PD-5 | Polymorphic `documents`: `document_type` (incl. `certificate`), `file_hash`, `visibility`, `access_level`, status pending/verified/rejected/archived, `verified_by_id` | `architecture/backend/tenant_database/political_parties_nepal/20250612_0847_all_political_parties_scheme.md` L1087-1141 | documented | Closest evidence model in any project | Add `organisation_id`, consent, retention and versioning; **move the verification decision out of the document row**; no MySQL ENUMs | ADAPT |
| PD-6 | "Documents are immutable — no overwrite; new version = new record; old versions retained"; the vault sits in a Verification/Trust context; Membership knows only "verified or not" | `20260101_2309` §7 L180-209 (verified) | documented | Strong evidence principle | — | REUSE (principle) |
| PD-7 | Pre-signed upload, encryption at rest, admin view by a 60-second temporary link | `brainstorming/20260101_1536_memership_extension.md` L37-43, L514-520 | documented | Access control for sensitive evidence | Use Laravel temporary URLs on the nrna1 `private` disk | ADAPT |
| PD-8 | Generic `media_files` (no hash, no verification, no visibility) | tenant-templates doc L187-205 | documented | Too weak for evidence | — | REJECT |

### 5.3 Verification and lifecycle

| # | Finding | Source | Status | Value | Change | Class |
|---|---|---|---|---|---|---|
| PD-9 | Four competing membership state machines; the newest (2026-01-04) contradicts itself | Summarised in the PD agent report: `20250612…` L505-518, `2309` §5, developer_guide `20260102…` L165-221, `20260104_0028…` L89-104 | documented / unverifiable | Warning against a single status field | Not carried forward | REJECT |
| PD-10 | Approve/reject with actor, timestamp and **mandatory reason**, emitted as events | `developer_guide/laravel-backend/membership-context/20260104_0028_member_approval_rejection_system.md` L59-87, L298-324 | unverifiable | Basic decision hygiene | Reject its "auto-approval after time period" (L423) | ADAPT |
| PD-11 | Verification **method** (document / interview / reference / field visit / testimony) and **approval level** (unit … national) recorded as data | `20250612…` L519-529; `20260102_0146_geography_ready…` L316-345 | documented | Directly expresses *how* and *at what level* a claim was substantiated | Drop `auto`; level becomes an authority reference, not a fixed hierarchy | ADAPT |
| PD-12 | Proposer/seconder sponsorship as peer attestation | `20260101_1536…` L539; `2309` §6 | documented | Peer corroboration | Attestation ≠ authoritative verification; attesters are never rewarded | ADAPT |
| PD-13 | Verifier authority scoped by unit and role (ABAC prefix rule) | `20251219_1449_membership_and_committee_contexts_seperation.md` L42-48 | documented | Who may verify | In nrna1, authority is a Governance / ARB-8 question (§7.4) | INVESTIGATE |
| PD-14 | **Points for verifying others' identities** (a conflict of interest) | `architecture/scoring/scoring_algorithm_01.md` L17 (verified) | documented | — | — | REJECT |

### 5.4 Recognition, visibility, history, outcomes, principles

| # | Finding | Source | Status | Value | Change | Class |
|---|---|---|---|---|---|---|
| PD-15 | DigitalCard lifecycle: issued / active / revoked / expired, revocation reason, hashed QR, signed offline validation bundle, validation audit, `retention_category` | `architecture/backend/digitcalcard_contexts/phase_1/20251225_2316_tables_necessary_for_digitalcard_context.md` L47, L59, L77, L121-122 (verified), L224-260 | documented (implementation claimed, unverifiable) | Best template for a **verifiable recognition record or certificate** that can be revoked and publicly checked | Drop location and device tracking on validation | ADAPT |
| PD-16 | Honorary membership as a membership type | `20250612…` L480; tenant-templates L465 | documented | — | Recognition must not be a membership type | REJECT |
| PD-17 | Rank tiers, badges, "+50 Leadership Points", ward/district leaderboards, spendable `contribution_balances` | `20251217_1634_political_parties_schema.md` L604-680; `frontend_view.md`; `20251217_1852…` L218-256 | documented | — | Conflicts with PD-21 and the initiative boundary | REJECT |
| PD-18 | Four competing visibility models (`public / members / unit_only / private`, `is_public`, mobile `privacy_level`, document `visibility` + `access_level`); "comply with Nepal's privacy laws", never analysed | `20251217_1728…` L495; `20251217_1634` L545, L578; `apps/mobile/…/member.models.ts` L186 | documented / types only | Per-item visibility, separate from profile visibility; default private | nrna1 design plus a data-protection analysis | ADAPT |
| PD-19 | "Members: view own ledger only" (the owner always sees their full history) | `20251217_1712…` L683 | documented | Self-view rule | — | REUSE |
| PD-20 | `membership_history` (old/new, actor, reason, snapshot); card lifecycle audit separating `occurred_at` from `recorded_at`, typed actor | `20250612…` L601-633; DigitalCard doc L135-165 | documented | History and provenance shape | **nrna1's ratified ledger already models this better** (§7.5) | ALREADY EXISTS (concept) |
| PD-21 | "Leadership Score must be Derived, Explainable, Non-transferable … purely analytical, never authoritative"; bad inputs include payments alone and popularity | `20260101_2309` L273-298 (verified) | documented | A principle consistent with PBDIGIT-330 L109-110 | — | REUSE (principle) |
| PD-22 | Financial neutrality: Membership reacts to payment events and does not calculate payments | `2313` Invariant 4; `2309` §9 | documented | Keeps money ≠ social contribution | — | REUSE (principle) |
| PD-23 | Geography snapshot "preserve historical truth" (`geography_path_snapshot`) | `2309` §8 L211-227 | documented | Snapshot location and organisation at the time of the activity | Check against nrna1 XD-5 (geography history, open) | ADAPT |
| PD-24 | Initiatives with a `target_outcome`, supporters and activities (lobby campaigns); `campaign_activities` estimated vs actual attendance plus `outcome_notes`; meeting attendance states | `…/20251217_1712_claude_cli_instructions_for_membership_digitilazation.md` L360-440; tenant-templates L799-850, L1013-1030 | proposed | Initiative as a parent of activities; reach as estimated vs actual | Reject `performance_rating` on people | ADAPT |
| PD-25 | `member_activities` (type, date, hours, `verified_by`, `verification_status`, `points_awarded`) | tenant-templates L575-596 (verified) | documented | Closest "contribution claim" shape | Drop points; split activity from claim; add evidence, outcome, reach and visibility | ADAPT |
| PD-26 | NGO `project_beneficiaries` stores named beneficiaries with **no consent** | `developer_guide/laravel-backend/multi-tenancy/02-template-system.md` L60-115 | example | Only beneficiary model anywhere | Counts and categories by default; named individuals only with consent | REWRITE |
| PD-27 | Proposed context maps (Identity, Geography and Document Vault feeding Membership; Verification/Trust; LeadershipScore downstream). **No map has a Contribution, Recognition or Evidence context.** | `architecture/design/ddd_bounded_contexts/context_map/20260101_1154_…`; `2309` §3 L70-95; `2313` L360-380 | proposed | Upstream/downstream intuition | nrna1 has its own PROPOSED context map (§7.4) | INVESTIGATE |

---

## 6. Verified findings: election (reference)

| # | Pattern | Source | Status | Original problem | Value | Defects (verified ✓ or read from code) | Class |
|---|---|---|---|---|---|---|---|
| EL-1 | Registration review lifecycle: draft→submitted→under_review→approved / rejected / back to submitted (request changes); reason **required** for reject and request-changes; approved and rejected are final | `app/Contexts/TenantAuth/Domain/StateMachines/RegistrationStateMachine.php` L18-91; entity `…/Domain/Entities/TenantUserRegistration.php` L132-201 | implemented | Moderating self-registration | The **claim → review → decision with reason** shape maps onto contribution verification | Authority checked only in the controller (`TenantUserRegistrationController.php` L429-433); no separation of duties; `reject()` writes the rejecter into `approvedBy` (L150) | ADAPT (shape) |
| EL-2 | Separate transition-history table (from, to, triggered_by, reason, transitioned_at) | migration `…/2025_01_01_000004_create_tenant_user_registration_transitions_table.php` | implemented, **write-only** | Audit of reviews | Idea of history separate from the current state | ✓ cascade delete (L30); ✓ table referenced only by its repository (no reader); repository `delete()` removes rows; no transaction around user + registration + transition writes (`RegistrationService.php` L132-179) | ADAPT (idea) — nrna1's ledger is the better target (§7.5) |
| EL-3 | `AbstractStateMachine` closure-based engine | `app/Contexts/Shared/Domain/StateMachine/AbstractStateMachine.php` L13-148 | implemented | Generic transitions | Low | Untyped variadic context, dynamic property, state mutated outside the engine | REWRITE (use aggregate methods + enum) |
| EL-4 | Batch pipeline: upload → automated validation → human review → processing → completed / partially completed | `…/Domain/Entities/MassRegistrationBatch.php` L26-250 | partial | Bulk onboarding | Bulk import of e.g. attendance lists as evidence | Approver `auth()->id() ?? '1'` (`MassRegistrationController.php` L164); no role check on approve; domain events never dispatched; personal data copied into `validation_result` JSON | ADAPT shape · REJECT provenance |
| EL-5 | Admin accepts → **named third party consents** (email token) → admin finalises | legacy `app/Domain/Election/Domain/Models/ElectionRequest.php` L301-355; `…/Application/Services/RequestApprovalService.php` L37-103 | implemented | Election request with chief consent | **Corroboration** by an organiser, witness or beneficiary | `admin_notes` overwritten; emails sent inside the transaction | ADAPT |
| EL-6 | Advisory, explainable risk score (listed factors; never gates approval) | `RequestApprovalService.php` L390-434, L538-543 | implemented | Triage of requests | Triage of review queues only | Must never decide or be shown as worth | ADAPT (triage only) |
| EL-7 | File upload | `MassRegistrationService.php` L53 (`local` disk, client filename, no hash) | minimal | — | None | No hash, scan, retention or tenant path | REWRITE |
| EL-8 | Outbox (correlation_id, tenant, backoff) | `database/migrations/2025_09_18_164451_create_outbox_events_table.php`; `app/Contexts/Shared/Infrastructure/Events/OutboxService.php` | partial, defective | Reliable events | — | ✓ relay selects only `pending` (L66) while failures become `FAILED`, so they are never retried; no row locking | **ALREADY EXISTS** in nrna1 (§7.6) |
| EL-9 | Merkle commitment over ballots | `app/Contexts/Shared/Domain/Cryptography/MerkleTree.php`; `…/TenantScopedMerkleService.php` | implemented, likely odd-leaf proof bug (read, not run) | Tamper evidence for votes | Idea: tamper-evident evidence and decision records (e.g. a per-organisation hash chain) | — | REJECT code · INVESTIGATE idea |
| EL-10 | "Store the hash, not the secret" audit (`username_hash`) | `database/migrations/2025_10_29_050318_…provisioning_audit_table.php` L23-39 | implemented | Audit without disclosure | Hash evidence files and identifiers in provenance | — | REUSE (idea) |
| EL-11 | Committee `formed_at` / `dissolved_at`; `appointMember` overwrites the holder | `app/Contexts/Election/Domain/Entities/ElectionCommittee.php` L55-101 | partial | — | Validity-period idea only | Overwrite model | REJECT |
| EL-12 | Dead "Volunteer Management" tile, no route | `resources/js/pages/Domain/Election/CommitteeMemberDashboard.vue` L396-400 | stub | — | None | — | REJECT |
| EL-13 | Route-first locale files en/de/np | `resources/js/locales/README.md` | implemented | i18n | — | — | ALREADY EXISTS in nrna1 (`resources/js/locales/pages/<Page>/{en,de,np}.json`) |

**[F] election does not implement social contribution.** Everything above is pattern-level.

---

## 7. Verified findings: nrna1 (target)

### 7.1 Context inventory [F]

| Context | Role (one line) | Relevance |
|---|---|---|
| Membership | Largest (Domain 330 / Application 125 / Infrastructure 99 files); legacy members, fees and committees, **plus the ratified organisational ledger kernel** (`Domain/Organisational/*`) | Upstream for any member-scoped capability; provides the ledger pattern (§7.5) |
| Governance | Authority assignment and delegation, `Approval/*` (Requested / Granted / Rejected), GovernanceDecision, ConstitutionalBasis, DecisionTrace, v1 read API | Possible source of verifier authority (ARB-8 open) |
| Trust | Two domain events only (`IdentityAttested`, `VerificationRevoked`) | Name overlaps "verification": watch for vocabulary collision |
| Geography | GeoUnits, hierarchy, sync | Location of activities |
| Shared | Outbox/inbox, TenantId, ActorId, TenantAggregateRoot | Integration platform; **no business concepts allowed** (`deptrac.yaml` L33-36) |
| Adjudication / Contestation | Challenges and determinations | **Separate workstream.** Disputes of contributions are not routed there by assumption |
| Election / Elections | Voting core | Anonymity boundary (§7.6) |
| Committee, Finance | Read model; single projection | — |

**Enforcement.** deptrac covers Contestation, Adjudication, Election and Shared (`deptrac.yaml` L44-48, L73-84). Membership, Governance and Geography are guarded by `tests/Architecture/*` (e.g. `MembershipLedgerIsolationTest`, `GovernanceDomainPurityTest`, `TenantIdEnforcementTest`, `VocabularyProhibitionTest`).

**[I]** There is no context for person, individual profile, recognition or evidence.

### 7.2 Identity model (the decisive finding)

**Facts [F]**
- `users` is a **global account** (email unique platform-wide, `database/migrations/2026_03_05_000002_create_uuid_users_table.php:16`). It carries a *current-organisation* pointer (`app/Models/User.php:167-170`) and does not use `BelongsToTenant`.
- `members` is **organisation-scoped** (`2026_03_07_145529_create_members_table.php:14-36`; `app/Models/Member.php:17`). Chain: "User → OrganisationUser → Member → Voter" (`User.php:225`).
- **There is no Person table or Identity context.** The ratified kernel has only a `PersonRef` value object (`app/Contexts/Membership/Domain/Organisational/PersonRef.php`). `org_memberships.person_ref` is unique per organisation (`2026_10_09_100000_create_org_membership_tables.php:28,33`).

**Governing rules**

| Rule | Status | Effect on a contribution profile |
|---|---|---|
| Membership architecture: "Identity owns person identity … Membership owns organisational membership truth … no shared persistence" (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` L41-52, L157; ratification act §11 L309-349) | [D] RATIFIED, frozen | Profile content about membership must come from Membership; Identity owns "who the person is" |
| XD-3a: one platform-wide Person owned by Identity; organisation facts stay in the organisation; **C-1** "Identity does not imply visibility into another organisation's data"; L84 flags that a central record "can disclose political affiliation" (`docs/publicdigit/reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md`) | PROPOSED (PO + ARB + data protection) | A cross-organisation profile depends on an unapproved decision and runs into C-1 and the political-affiliation risk |
| XD-3b: Person ≠ Account; `PersonRef` is never the account id; central record is a bare `PersonId` (`…-xd3b-person-vs-account-decision-record.md` L20-22, L73-79) | PROPOSED | **The Contribution Points experiment keys on `user_id` (the account)** and conflicts with this direction |
| BR-4 (one person, many organisations); BR-5 (person without an account); **BR-13a "PublicDigit provides no cross-organisation governance functionality now … a new requirement … not designed for in advance"** (`docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` L26-39) | [D] PO-confirmed | A person-scoped, cross-organisation capability is a **new requirement needing PO and ARB decisions** |
| INV-ORG-SCOPE-1 §1a: platform-level operations "must not access organisation-specific person records" | [D] APPROVED principle | A platform-level profile cannot read organisation person data without an amendment |
| PO-ORG-MEM-01: "The Organisation owns its Membership domain"; technical platform roles ≠ organisational authority | [D] APPROVED by PO, ARB validation pending | Verification authority inside an organisation is organisational, not a platform admin flag |

**[I] Consequence: the identity anchor is the gating decision for the whole initiative.**

| Option | Fit with current decisions | Tension |
|---|---|---|
| O-1 **Member-scoped**: the profile lives inside one organisation (e.g. her party) and is about its member; it may *record* activities done outside the party, as that organisation's record | Fits RATIFIED Membership, INV-ORG-SCOPE-1, BR-13a and the D-1 option-C direction | Her history becomes the party's record; it is not portable; another organisation sees nothing; consent and ownership of the narrative sit with the party |
| O-2 **Person-scoped, platform-level**: a profile owned by the person, across organisations | Needs Identity (XD-3a/3b approved), an amendment to INV-ORG-SCOPE-1 §1a, an exception to BR-13a, and a data-protection review | Political-affiliation disclosure risk (XD-3a L84); largest governance cost |
| O-3 **Person as their own organisation**: the individual is the organisation in which their profile lives, and parties are audiences | Reuses existing tenancy without cross-organisation reads | Stretches "organisation" semantics; needs PO acceptance; audience access across organisations still needs a rule |

These are options for the PO and ARB. **This document chooses none.**

### 7.3 Tenancy [F]

- `BelongsToTenant` resolves `TenantContext::get() ?? session('current_organisation_id')`. A null or 0 organisation silently falls back to the `platform` organisation (`app/Traits/BelongsToTenant.php` L43-85). INV-ORG-SCOPE-1 V-4 records this as contradicting "fail closed".
- D-1 tenancy option C (per-organisation data plus central identity) is a **candidate direction**; ARB approval is pending (`docs/publicdigit/reviews/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md` L184-201).

### 7.4 Governance, authority and context map [F]

- `docs/publicdigit/architecture/strategic-design-status.md`: only Membership's boundaries are ratified (L42). Open items: O-6 DR-07, O-7 XD-4/XD-5, **O-9 Membership ↔ tenure**, O-10, O-14.
- `docs/publicdigit/architecture/context-map.md`: **PROPOSED** (L3). It has no Profile, Recognition or Contribution context. "Appointment (Authority) owns Mandate truth" is FROZEN (BDR-03), but its relation to Governance is **UNRESOLVED (ARB-8)**. The AuthorityCheck port is ratified as a contract element; its content is proposed.
- **[I]** Verification "with authority" and any office or tenure content on a profile depend on ARB-8, O-9 and DR-07. A personal social-contribution profile may not need office content at all (PH2 OQ-B1).

### 7.5 Verification, approval and history patterns [F]

| Mechanism | Source | Nature |
|---|---|---|
| **Ratified organisational fact ledger**: append-only facts with `effective_at` / `decided_at` / `recorded_at`, JSONB provenance (decision / rule / attested), `AuthorityEvidence` {actor, capacity, ruleRef, policyVersion, result, checkedAt} with `competenceVerified`; corrections Annul / Void / Correct (COR-4, PBDIGIT-327/329) | `database/migrations/2026_10_09_100000_create_org_membership_tables.php` L25-88; `…/2026_11_03_120000_extend_org_membership_facts_for_corrections.php`; `app/Contexts/Membership/Domain/Organisational/AuthorityEvidence.php` L13-26; ratified doc L54-72, L159-164 | **The only ratified model of a correctable, provenance-bearing decision history.** Membership-internal; isolated by `tests/Architecture/MembershipLedgerIsolationTest.php`. It is not wired to HTTP, and its migrations are release-gated. |
| Membership applications | `2026_04_03_155710_…`; `app/Models/MembershipApplication.php` L75-120; `app/Policies/MembershipPolicy.php` L33-43 | Mutable status row, optimistic lock, platform-role approval, no history |
| Committee associations | `2026_05_14_000001_…` | Actor and reason columns, updated in place |
| Candidacy review | `CandidacyReviewController.php` L60-108 | Transactional, reviewer recorded, no history |
| Governance approval process | `app/Contexts/Governance/Domain/Approval/*`; `Application/Approval/GovernanceApprovalProcessManager.php` | Domain events Requested / Granted / Rejected |
| Contribution Points experiment | XPD §4.1 | Columns only; no workflow (Decision B) |

### 7.6 Evidence storage, audit, anonymity [F]

- **Files.** `private`, `public`, `local` and `s3` disks exist (`config/filesystems.php` L33-58).
  - The best pattern is the newsletter attachment: `private` disk, `mimetypes` and size validation, uploader recorded, organisation-checked, admin-only delete (`app/Http/Controllers/Membership/OrganisationNewsletterController.php` L357-397).
  - **No uploaded file is hashed anywhere. No access-controlled download route exists.**
- **Integration.** Outbox with `organisation_id` and `correlation_id`/`causation_id` (`2026_05_04_000001_…`, `2026_07_10_000001_add_provenance_to_outbox_events.php`). Inbox dedupe on `(event_id, consumer_context)` (`2026_07_06_000001_…`). deptrac TP-1: contexts collaborate only via events.
- **Audit.** `election_audit_logs`, `newsletter_audit_logs`, per-voter JSONL logs (`app/Services/ElectionAuditService.php` L100-166). `HasAuditFields` is a column helper, not an audit log.
- **Anonymity.** ADR-T11 (`docs/adr/ADR-T-LOG-Tactical-Implementation.md:18`): no voter↔vote linkage anywhere. **[I]** A contribution profile must never show or derive "voted" or "how voted". Any participation fact touching elections needs explicit ADR-T11 review.

### 7.7 Profiles and visibility [F]

- There is no sanctioned profile surface. `/user/profile` is a placeholder (`routes/web.php:175-177`).
- `member_directories` is a per-organisation projection with no biography.
- The only visibility setting is `users.leaderboard_visibility`, which belongs to the experiment.
- The legacy public `/user/{profile}` endpoint is precedent to avoid (§12, S-1).

### 7.8 Conventions to follow [F]

- **Plans:** EP-03 readiness → EP-01 plan with explicit approval → EP-02 completion review (`.claude/CLAUDE.md`). Approved plans go in `docs/plans/YYYYMMDD-HHMM-<what>-plan.md` (ES-004.2).
- **ADRs:** `docs/publicdigit/adr/ADR_YYYYMMDD_HHMM_*.md`. Decision records: `docs/publicdigit/reviews/YYYY-MM-DD-<topic>-decision-record.md`.
- **Engineering:** architecture tests in `tests/Architecture/`; design system (`.claude/UI_GUIDELINES.md`, `npm run design-check`); i18n en/de/np per page; developer guide per step (`developer_guide/<area>/`); PostgreSQL test isolation (`phpunit.xml`, `phpunit.membership-pg.xml`).

---

## 8. Cross-project comparison matrix

| # | Area | public-digit | election | nrna1 today | Recommendation | Reason |
|---|---|---|---|---|---|---|
| 1 | Contribution documentation | `member_activities` (PD-25), initiatives with target outcome (PD-24) | — | Experiment `contributions` keyed on `user_id`, one status field (XPD §4.1) | **REWRITE**, with PD-24/25 ideas **ADAPTED** | Split activity / participation / claim; anchor per §7.2 decision, never on the account (XD-3b) |
| 2 | Evidence management | Immutable versioned documents with hash and visibility (PD-5, PD-6, PD-7) | CSV upload only (EL-7) | Private-disk upload pattern, no hash, no download (§7.6) | **ADAPT** (PD-5/6/7 + nrna1 newsletter pattern) | No project has evidence-grade storage; the principles exist |
| 3 | Verification | Method + level + mandatory reason (PD-10, PD-11); attestation (PD-12) | Review lifecycle + required reasons (EL-1); third-party consent (EL-5) | Ratified ledger with AuthorityEvidence (§7.5); Governance Approval domain | **ADAPT** the ledger concept + EL-1 / EL-5 shapes | The ledger already separates decision, rule and attested provenance; authority source is ARB-8 |
| 4 | Recognition | DigitalCard issue / revoke / verify (PD-15); honorary type (PD-16) | Email prose only | None | **ADAPT** PD-15 · **REJECT** PD-16 | Recognition is a record of an issuer's act; certificates also act as evidence (PH2 §6) |
| 5 | Individual profile | Mobile `MemberProfile.bio` (types only); `member_profiles` design | Starter profile | None sanctioned; legacy `/user/{profile}` unsafe | **REWRITE** | Must follow from the anchor decision and visibility model |
| 6 | Beneficiaries and reach | Named beneficiaries without consent (PD-26); estimated vs actual attendance (PD-24) | — | None | **REWRITE** (PD-26) · **ADAPT** (estimated vs actual) | Default to counts and categories; personal data of third parties needs consent |
| 7 | Outcomes and impact | Free-text `outcome_notes`, `target_outcome` | — | `outcome_bonus` integer (experiment) | **INVESTIGATE** | No credible model anywhere; depends on Komal Oli's intent (PH2 OQ-C1, OQ-I1) |
| 8 | Measurement | Many competing formulas (PD-17) + anti-gamification principle (PD-21) | Advisory risk score (EL-6) | `GaneshStandardFormula` (Decision B) | **REJECT** all formulas and rankings · **REUSE** PD-21 · **ADAPT** EL-6 for triage only | PBDIGIT-330 L109-110; Decision B; PH2 §9 multi-dimensional account |
| 9 | Visibility and privacy | Four competing models (PD-18); owner self-view (PD-19); retention and anonymisation (PD-15) | — | `leaderboard_visibility` only; XD-3a C-1; INV-ORG-SCOPE-1 | **ADAPT** PD-18 per-item visibility · **REUSE** PD-19 · **INVESTIGATE** data protection | Political-affiliation risk is documented (XD-3a L84) |
| 10 | Audit and history | `membership_history`, typed-actor lifecycle audit (PD-20) | Write-only transitions (EL-2); hash-not-secret (EL-10); Merkle (EL-9) | **Ratified append-only ledger with corrections** (§7.5); outbox/inbox provenance | **ALREADY EXISTS** (pattern) · **REUSE** EL-10 idea · **INVESTIGATE** EL-9 idea | nrna1's ledger is stronger than anything in the old projects; it is Membership-internal, so the *pattern*, not the code, transfers |

---

## 9. Consolidated transfer recommendations

| Class | Items |
|---|---|
| **REUSE** (principles and ideas) | PD-6 immutable versioned evidence · PD-19 owner self-view · PD-21 derived/explainable/never-authoritative · PD-22 financial neutrality · EL-10 hash-not-secret |
| **ADAPT** | PD-4 scope/location value · PD-5 evidence record · PD-7 temporary-link access · PD-10 decision with mandatory reason · PD-11 verification method + level · PD-12 attestation (unrewarded) · PD-15 DigitalCard lifecycle for recognition records · PD-18 per-item visibility · PD-23 snapshot at activity time · PD-24 initiative with target outcome and estimated/actual reach · PD-25 activity/claim split · EL-1 review lifecycle shape · EL-4 batch pipeline shape · EL-5 third-party corroboration · EL-6 triage-only advisory signals |
| **REWRITE** | PD-3 cross-organisation identity · PD-26 beneficiaries · EL-3 state-machine engine · EL-7 file handling · contribution documentation · individual profile |
| **REJECT** | PD-2 · PD-8 · PD-9 · PD-14 · PD-16 · PD-17 · EL-4 provenance approach · EL-9 code · EL-11 · EL-12 · every scoring formula and leaderboard |
| **INVESTIGATE** | PD-13 verifier authority (ARB-8) · PD-27 old context maps · EL-9 tamper-evidence idea · outcomes and impact · data protection for political-affiliation exposure |
| **ALREADY EXISTS in nrna1** | PD-1 (XD-3b direction) · PD-20 / EL-2 (ratified ledger pattern) · EL-8 (outbox/inbox) · EL-13 (i18n) · architecture-test enforcement |

---

## 10. Existing nrna1 architecture assessment (summary)

1. **[F] Suitable foundations exist:**
   - the ratified ledger *pattern* for decisions with provenance and correction;
   - outbox/inbox integration;
   - architecture-test enforcement;
   - a private-disk upload pattern;
   - en/de/np i18n;
   - governance records for identity and tenancy.
2. **[F] Missing for this capability:** person identity (no Identity context; XD-3a/3b PROPOSED); evidence-grade file storage (hash, download policy, retention); a profile or visibility model; a recognition concept.
3. **[I] The identity anchor (§7.2) must be decided before strategic DDD can name an owner.** It is a PO and ARB decision because it touches RATIFIED and APPROVED rules (BR-13a, INV-ORG-SCOPE-1 §1a).
4. **[I] The experiment is architecturally misaligned**, independent of its formula: it is keyed on the account (contrary to XD-3b), sits outside any context, has no tests at the HTTP level, and its developer guide overstates it. This informs, but does not decide, `PBDIGIT-332`.
5. **[I] Constraints for any future design:**
   - Shared hosts no business concepts.
   - The Membership ledger cannot be used from outside Membership (`MembershipLedgerIsolationTest`).
   - Adjudication is not a default dispute channel.
   - ADR-T11 forbids any vote linkage.

---

## 11. Recommended development phases (nrna1)

| Phase | Content | Entry gate | Output | Status |
|---|---|---|---|---|
| **0 Cross-project verification and plan** | This document | Principal instruction | Initiative story, transfer inventory, baseline, plan | **Delivered; awaiting approval** |
| **1 Business discovery** | Consent and scope (PH2 W0); interviews with Komal Oli (PH2 §7: W1 story, **W2 walkthrough of real certificates**, W3 measurement and visibility); synthesis (W4) | Phase 0 approved; consent confirmed | Validated problem, real use cases, requirements, open questions; **an anchor decision package (O-1/O-2/O-3) for the PO and ARB** | Prepared in PH2 |
| **2 Strategic DDD** | Ubiquitous Language v2; capabilities; subdomain classification; bounded-context candidates; responsibilities and relationships; dependencies on Membership, Governance, Identity and PBDIGIT-330; context-map impact | Phase 1 synthesis; **anchor decision taken** | Strategic model, context proposals, decisions for the Principal | Not authorised |
| **3 Strategic architecture review** | Independent review of boundaries, ownership, authority, consistency with ratified architecture | Phase 2 output | Findings, corrections, Principal and ARB decisions; ADRs if warranted | Not authorised |
| **4 Tactical design** | Aggregates, value objects, commands, events, policies, invariants; evidence and verification responsibilities; the §9 ADAPT items realised as nrna1 designs | Phase 3 approval | Tactical design and EP-01 plan | Not authorised |
| **5 Product planning** | Confirm PO; prioritise; epics, stories, acceptance criteria and increments | Phase 4 approval | Implementation backlog | Not authorised |
| **6 Implementation** | Approved designs only, with TDD, architecture tests and developer guides. The experiment is migrated or replaced only under an approved migration plan (`PBDIGIT-332`) | Phase 5 approval | Working capability | Not authorised |

**Generalisation (`PBDIGIT-333`)** runs as a separate pass after Phase 1, before Phase 2 concludes, so that the strategic model is not shaped by one case alone.

---

## 12. Dependencies and risks

**Dependencies**

| ID | Dependency | Status |
|---|---|---|
| DEP-1 | Identity anchor decision (§7.2; XD-3a/3b; BR-13a; INV-ORG-SCOPE-1 §1a) | Open: PO + ARB + data protection |
| DEP-2 | `PBDIGIT-332`, status of the experiment | Open: PO |
| DEP-3 | ARB-8 / O-9 / DR-07 (authority, tenure), only if office or tenure content or authority-based verification is in scope | Open |
| DEP-4 | Consent of the initial case (PH2 OQ-S2) | Open: Principal |
| DEP-5 | Priority against the delivery focus WP-7C → WP-8 → EPIC-005 | Open: PO |

**Risks**

| ID | Risk | Mitigation (proposal) |
|---|---|---|
| R-1 | Designing the whole capability around one prominent person | Run the generalisation pass (`PBDIGIT-333`) before Phase 2 concludes |
| R-2 | Political-affiliation disclosure through a visible profile (XD-3a L84) | Data-protection review in Phase 1; per-item visibility defaulting to private |
| R-3 | A profile becoming campaign material, or being read as a ranking | Keep the anti-scoring rules; no comparison views |
| R-4 | Third-party personal data (beneficiaries, photographs) | Counts by default; consent for named persons |
| R-5 | Inheriting the experiment by inertia (its URLs remain reachable) | `PBDIGIT-332` decision before Phase 4 |
| R-6 | Vocabulary collision with "Trust", "Verification" and "Contribution" (forum, finance) | UL v2 in Phase 2; check against `VocabularyProhibitionTest` |
| R-7 | Copying old schemas by convenience | §9 transfer classes; the no-code-copy rule |

**Out-of-scope observations (security, verified statically, not run).** Under this instruction no ticket was created. They need separate authorisation.

| ID | Observation | Evidence | Tracking |
|---|---|---|---|
| **S-1** | **The legacy public route `/user/{profile}` passes the `password` field (hash) and contact data of any user to the page, with no authentication middleware.** | `routes/user/userRoutes.php:54` is outside the `auth:sanctum` groups (which close at L47); included by `routes/web.php:440`; `app/Http/Controllers/UserController.php` `show()` `->only([... 'telephone','email','password', ...])` | **Not found in PBDIGIT-266.** Recommend urgent triage. |
| S-2 | `X-Tenant-Id` header sets the tenant before any membership check | `app/Http/Middleware/TenantContext.php:50-55` | Already tracked: `PBDIGIT-266` item 5 |
| S-3 | Committee-assignment history overwrite | XPD §4.2 | Awaiting separate authorisation (PH2 §14 #8) |

---

## 13. Decisions still required

| # | Decision | Authority | Needed before |
|---|---|---|---|
| 1 | **Approve this initiative story and development plan** (milestone) | Principal | Phase 1 |
| 2 | Consent and naming of the initial case | Principal | Phase 1 interviews |
| 3 | Confirm the Product Owner for this initiative | Principal | Phase 1 |
| 4 | **Identity anchor: member-scoped / person-scoped / person-as-organisation (§7.2)** | PO + ARB (+ data protection) | Phase 2 conclusion |
| 5 | Status of the Contribution Points experiment (`PBDIGIT-332`) | PO | Phase 4 |
| 6 | Whether office, tenure or political participation appears on a social profile (PH2 OQ-B1) | PO, after Phase 1 | Phase 2 |
| 7 | Measurement stance after Komal Oli's answer (PH2 OQ-C1) | PO | Phase 2 |
| 8 | Priority against WP-7C → WP-8 → EPIC-005 | PO | Phase 1 scheduling |
| 9 | **Triage authorisation for S-1** (and S-3) | Principal | Independent of this initiative; S-1 is urgent |

---

## 14. Stopping condition

Phase 0 ends with this document. **No strategic DDD design, bounded-context proposal, tactical design, implementation ticket or code change** follows until the Principal approves the milestone "Social Contribution Initiative Story and Development Plan — Principal Approved" and explicitly authorises Phase 1.

---

**Traceability.**
- Principal direction and Decisions A and B (2026-11-07) · `PBDIGIT-330` · `331` · `332` · `333` · `334` · XPD · PH2.
- Governance and architecture records cited in §7: `2026-10-08-membership-architecture-ratified.md`; XD-3a; XD-3b; `20261101_1739_person_account_affiliation_business_rules.md`; INV-ORG-SCOPE-1; XD-15; `strategic-design-status.md`; `context-map.md`; ADR-T11.
- Reference-project evidence: three read-only verification passes on 2026-11-07, with spot checks re-run in session (PD gitlink, PD-6, PD-14, PD-15, PD-21; EL-2 cascade and reader; EL-8 relay; S-1; S-2).
