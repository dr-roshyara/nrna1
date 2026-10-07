# Membership Domain Discovery & Constitution v1 — PublicDigit (Part 1: Phases A–D, checkpoint)

**Type:** Domain discovery (research maturity) · **Date:** 2026-10-08 · **Status:** 🟡 **DRAFT: PART 1 delivered, stopped at the PO checkpoint (plan §10)**. This is research. **It decides nothing, ratifies nothing and authorises nothing.**
**Plan:** `docs/plans/20261008-0008-membership-domain-discovery-plan.md` (APPROVED, PO 2026-10-08: *"approved, use docs/publicdigit/reviews, keep it generic"*)
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable) · **Role:** DDD Architect + Senior Requirements Engineer
**Placement derived:** `doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` per the PO.
**Reference organisation:** none (generic, per the PO).

> **Source tags used throughout.**
> - `[adopted: ref]`: a PO/ARB decision on record.
> - `[adopted·deferred: ref]`: adopted but not in force in the current phase (EM-SEQ-001).
> - `[accepted-legacy: ref]`: accepted before the current governance model; its status needs confirming.
> - `[proposed: ref]`: in documents, never decided.
> - `[legacy-impl: file:line]`: what the code does, which is **not** domain truth.
> - `[candidate]`: this discovery's suggestion.
> - `❓`: a question for the PO, deliberately **not** answered here.
>
> **The strongest statement in each row never exceeds its tag.**

---

## 0. Reading guide — what is already settled

Before any modelling, the governance record **already contains** a large part of what the PO direction (2026-10-08) asks for. This discovery **consumes** it and does not re-derive it:

| Already settled | Where |
|---|---|
| ElectionMembership = the election-specific entitlement record. Existence ≠ exercisability. Changes to organisation membership do not destroy the entitlement. One entitlement concept across both modes. Removing an ElectionMembership never removes organisation membership. The Election Chief governs it. | `[adopted: ELECTION_MANIFESTO EM-ENT-001…007]` |
| Election-Only: organisation membership is not required. A person may become an ElectionMember directly. Admission does not create organisation membership. Organisation changes have no automatic election effect. | `[adopted: EM-EO-001…004]` |
| Full Membership: organisation membership is a superior prerequisite. It must be active. Its removal automatically suspends the ElectionMembership, and that suspension is distinct from Chief suspension. It is an admission prerequisite that is continuously relevant to exercisability. | `[adopted·deferred: EM-FM-001…006; D-ENT-1/F1 refined by D-ENT-2]` |
| Election-Only is phase 1. Full Membership is a later phase. Full Membership rules must not be inferred from the Election-Only implementation. | `[adopted: EM-SEQ-001/002]` |
| Registration ≠ Admission ≠ VotingEntitlement. Admission is derived and never stored. | `[adopted: ADR_20261004_1549]` |
| The open Full Membership questions | **FM-1…FM-15** (`2026-08-12-election-only-first-governance-baseline.md:81-95`), EM-OPEN-014/015/016 |
| The Membership context owns membership lifecycle truth | `[accepted-legacy: architecture_legacy/membership/decisions/ADR-F3.2-Semantic-Hardening.md, D8]`. ⚠️ This conflicts in naming with EM-FM-005 ("Organisation context"); see §5 L-6. |

**What is NOT settled, and is therefore this discovery's real subject:** the **organisation side**, meaning what Membership itself *is* (states, authorities, types, fees, rights). The election side is largely decided. The organisation side has **never been decided at all**: it exists only as code, guides and AI-chat proposals.

---

## 1. Phase A — source register (summary)

Three read-only harvests were run. Their full reports are not committed. They are summarised here, and the load-bearing claims were re-checked by hand where marked ✔.

| Source family | Count | Authority | Character |
|---|---|---|---|
| `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` + 2026-08-12 governance package, D-ENT-1/2, 2026-09-16 SM2 decision, ADR_20261004 | ~12 | **adopted** (the Manifesto as a catalogue is itself PROPOSED, `EM-OPEN-017`; its rows carry their own adoption) | election side only |
| `architecture_legacy/membership/**` + `architecture_legacy/election/**` (membership / election-only) | 63 | 2 ADRs `accepted-legacy` (F3.2, F3.3, both about the *committee lineage* model); the rest `proposed` (mostly AI transcripts) | contradictory (§2) |
| `developer_guide/membership*`, `organisations/membership_mode`, `organisation_creation/membership`; `docs/membership/*` (incl. the 2026-04-03 **business case**) | ~70 | describe the implementation (`legacy-impl`), except the business case (`proposed`) | contradict each other and the code |
| `docs/publicdigit/backlog`: EPIC-02 (`PBDIGIT-04…08`, all *IMPLEMENTED — NOT VERIFIED*), `PBDIGIT-49`, `66`, `68`, `209`, `211`, `257` | — | backlog | |
| Code: `app/Contexts/Membership` (496 files), `app/Models/{Member,MembershipType,OrganisationUser,…}`, migrations | — | `legacy-impl` | §6 |

`docs/knowledge/domains/membership/README.md` is a stub (*"status: draft, authority: provisional"*). **No canonical Membership business-rule home exists.** The Election Manifesto is the only adopted catalogue, and it covers the election side.

---

## 2. Contradictions that block a single meaning of "Membership"

These are the conflicts the PO must resolve. Each is quoted from Phase A.

| # | Question | Position A | Position B (and C) |
|---|---|---|---|
| X-1 | **What is "membership"?** | a formal (paid) relationship between a person and the **organisation**: *"members → formal paid membership"* (`membership_lifecycle_implementation.md:198`) | a *"temporal constitutional relationship between a person and a **committee**"* (`DG/membership_contexts/20260514_1246_what_is_membership.md:4`; lineage model `[accepted-legacy: ADR-F3.2]`). C: the CommitteeAssociation is what *"Elections … queries to determine voting eligibility"* (`MEMBERSHIP_APPLICATION_SYSTEM.md:85`) |
| X-2 | **When is a member "active"?** | on **approval** `[legacy-impl: ApproveMembershipApplication → Member::register (active)]` | after **payment**: *"Approved → Fee Generated → Payment Recorded → Active Member"* (`docs/membership/…0006_business_case…:69-76`, `proposed`) |
| X-3 | **What gives voting rights?** | the membership **type** (`grants_voting_rights`) | **fee status** is *"the primary driver"* (`ablauf:680`); C: *"voting rights should be decoupled from membership status"* (`redefine:251`) |
| X-4 | **Associate members** | *"❌ None (voice only)"* (`ablauf:690`) | *"Limited voting (certain elections only)"* (`full_membership_management_architecture.md:499`) |
| X-5 | **Is a membership type mandatory?** | *"Must have MembershipType"* (`election_only_or_full_membership.md:58`) | members without a type are created and **are eligible** `[legacy-impl: OrganisationRoleController:102; eligibility predicate whereNull(type)]` |
| X-6 | **Grace period after expiry** | 30 days, eligible for elections (`config/membership.php:27-31`; guide `0003:375`) | none: *"expires_at IS NULL OR > now()"* `[legacy-impl]`; the config key is **unused** |
| X-7 | **Are voters auto-registered from members?** | *"Voters must be explicitly registered … not automatic"* (`ablauf:402`) | *"OR system auto-registers eligible members"* (`ablauf:~631`); 2026-10-08 review O-2: open |
| X-8 | **Status vocabulary of a Member** | table: active / expired / suspended / ended | aggregate: active / inactive / suspended / archived; a third VO: draft…archived (8 values); lineage: active / suspended / terminated |
| X-9 | **What "member" means as a word** | the `Member` aggregate | `user_organisation_roles.role='member'` (an authorization role, also given to Election-Only imported voters); and `ElectionMembership` (`model-b…:43-47`) |

---

## 3. Phase B — the business capabilities

### 3.1 Two products, not two modes of one product `[candidate]`, consistent with `[adopted: EM-EO-*, EM-FM-*, EM-SEQ-*]`

| | **P1 — Election digitalisation (Election-Only)** | **P2 — Membership digitalisation (Full Membership)** |
|---|---|---|
| Customer statement | *"We only want to run an election online."* | *"We want PublicDigit to run our membership, and our elections draw on it."* |
| Population source | an imported or assigned list of persons **for one election** | the organisation's **member registry** |
| Organisation relationship of a voter | an **OrganisationUser** (tenant identity) is created; **no Member** `[legacy-impl: VoterImportService:293-312]` `[adopted: EM-EO-003]` | a Member in good standing is a **prerequisite** `[adopted·deferred: EM-FM-001/002]` |
| Status | **current phase** `[adopted: EM-SEQ-001]` | **deferred phase**. Its election-side rules are adopted; its organisation-side rules are **undecided** |

**Correction to the PO direction's sketch** (`[legacy-impl]`, recorded so the model starts from facts): in P1 the person is **not** free of every organisational relationship. They become an `OrganisationUser`, and the import also gives them `user_organisation_roles.role='member'` (FM register `W-6`). The model must name this relationship, the **Organisation Affiliation** below, explicitly, and must keep it distinct from Membership.

### 3.2 What P2 promises (business case, `proposed`), 2026-04-03

Online application → approval workflow → automatic fee creation → payment recording → self-service renewal (90 days) → expiry automation → **"election eligibility automation"** → notifications. Personas are an administrator, a treasurer, a potential member, an election officer and the board (`…0006_business_case…:51-61`).

❓ **MQ-0 (scope of P2):** Is this list still the intended product promise? In particular, is *"election eligibility automation"* a promise, given that X-7 is open and the adopted rules make the Election Chief the governor of ElectionMembership (EM-ENT-007)?

### 3.3 Actors `[candidate]`, from sources

Applicant (internal or public) · Member · Organisation owner · Organisation admin · Commission (read-only today) · Treasurer (*persona only; no role in code*) · **"Organisation Chief"** (EM-FM-005, *no referent*: EM-OPEN-014 / FM-4) · Election Chief / Deputy · System (scheduled).

---

## 4. Phase B — business problem and vision (draft)

**Problem:** associations keep members in spreadsheets, and so voter lists, renewals and fee follow-up are manual and error-prone (business case). **Vision `[candidate]`:** *PublicDigit keeps an organisation's authoritative member registry, recording who is a member, of which kind, in what standing, decided by whom and on what evidence. Elections consume a published "standing" from it, never its tables.*

---

## 5. Phase C — ubiquitous language (candidate glossary)

| Term | Candidate definition | Owning context | Current code mapping | Status |
|---|---|---|---|---|
| **Person / User** | a global authenticated identity | Identity / platform | `users` | `legacy-impl`, uncontested |
| **Organisation Affiliation** *(new term, candidate)* | a person's tenant-scoped relationship to an organisation that gives access. It is **not** membership. | Organisation | `organisation_users` (+ `user_organisation_roles`) | ❓ the name is new. It replaces the overloaded "organisation user / participant" (`1736:15-16`: *"Being an 'organisation user' is NOT the same as being a 'paid member'"*) |
| **Organisation Role** | an authorization role within the organisation (owner, admin, commission, …) | Organisation | `user_organisation_roles.role`. **Note:** `organisation_users.role` exists but authorization does not read it | ❓ L-1: `role='member'` must be **renamed or redefined**, because it collides with Member (X-9) |
| **Participant / Guest / Staff** | an affiliated person with an operational function and no membership | Organisation | `organisation_participants` | `proposed`; not examined further |
| **Membership Application** | a request to become a Member, decided by an authority | Membership | `membership_applications` + `Domain/Application` | `legacy-impl` |
| **Member** | a person the organisation formally recognises as belonging to it under its membership rules | Membership | `members` + `Domain/Member/Member` | definition `[candidate]` (cf. `check_domain:110`: *"recognizes this person as a constitutional member"*) |
| **Membership Type** | a class of membership with its own terms (fee, duration, approval, rights) | Membership | `membership_types` | ❓ whether **rights** belong here (MQ-5) |
| **Membership Fee** | a charge owed for a membership period | Membership | `membership_fees` (+ `members.fees_status`, **and** `members.fee_state`: two fields) | `legacy-impl` |
| **Membership Standing** *(new term, candidate)* | the **published, point-in-time fact** "this person is / is not a Member in good standing of organisation O, with type T, and why". It is the only thing other contexts consume. | Membership (publisher) | **does not exist.** Elections reads the tables directly (2026-10-08 review O-1) | `[candidate]`, the key concept of this discovery |
| **Committee Membership** | a person's association with a committee (lineage of episodes) | Membership / Governance | `MembershipLineage`, `CommitteeAssociation` `[accepted-legacy: ADR-F3.2]` | ❓ L-2 / X-1: a **different concept** that shares the word "membership" |
| **ElectionMembership** | the election-specific entitlement record (role voter, candidate, …) | Elections | `election_memberships` | `[adopted: EM-ENT-001]` |
| **Registration / Admission / Voting Entitlement** | as in the ADR | Elections | | `[adopted: ADR_20261004_1549]`. ⚠️ "admission" is overloaded with PBDIGIT-68's "admission = creating the ElectionMembership" (ADR G11) |
| **Voter** | the role `voter` on an ElectionMembership. It is **not** a person type. | Elections | `role='voter'`; the `voters` table is empty (`PBDIGIT-49:24`) | consistent with `[adopted]` |

**Language defects found (L-n):**
- **L-1:** "member" names three things (X-9).
- **L-2:** "membership" names two (organisation vs committee, X-1).
- **L-3:** the Election-Only import refuses with *"is not an active **member** of this organisation"* when it means "affiliation" (`VoterImportService.php:183`).
- **L-4:** "admission" has two meanings (ADR G11).
- **L-5:** "exempt" is set by **`mark-paid`** (`MemberController.php:192-207`), which blurs *paid* and *exempt*.
- **L-6:** the owner of organisation membership is named both the "Organisation context" (EM-FM-005) and the "Membership context" (ADR-F3.2 D8, the code namespace).

**Principle `[candidate]`, from the PO direction and consistent with EM-EO-003 / EM-ENT-006:** *Membership is an organisational relationship. Election participation is an election-specific relationship. Affiliation is neither.* The three never collapse into one another:

```text
Person ──Affiliation──► Organisation           (access; P1 and P2)
   ├────Membership────► Member ──publishes──► Membership Standing   (P2 only)
   └────ElectionMembership──► Election  (entitlement; route of admission differs by mode, EM-ENT-005)
```

---

## 6. Phase A evidence — what the implementation does today (`legacy-impl`, not domain truth)

This is recorded so the constitution questions are asked against facts. ✔ marks claims verified by hand in this session. The rest is from the code harvest.

1. ✔ **A member approved through the official application path can never be an eligible voter.** Approval saves a `membership_type_id` but leaves `fees_status` at its default `unpaid` (`EloquentMemberRepository.php:38-71` never writes `fees_status`). No product code sets `grants_voting_rights`, so it stays `false` (`MembershipTypeController.php:52-65`). The eligibility predicate requires type null **or** grants_voting_rights, so typed members fail it.
2. ✔ **Suspending or archiving a member is impossible.** `MemberController` authorises `manageMembers` (`:309`, `:338`), an ability that is defined nowhere. No `MemberPolicy` exists (IERVP-3).
3. **Three member-status vocabularies**, plus `archived` is not allowed by the `members.status` CHECK. The DDD repository cannot rehydrate `expired` or `ended` rows.
4. **Two fee fields** (`fees_status` via the legacy pay path; `fee_state` via the DDD path, which does not update `fees_status`). Eligibility reads only `fees_status`.
5. **Three payment-recording paths.** Annual fee generation would fail on insert (it is also inert under `PBDIGIT-211`).
6. **Expiry is one-way:** renewal does not reset `expired` to `active`.
7. **Unused configuration:** `grace_period_days`, `application_expiry_days`, notifications, `requires_approval`, `form_schema`.
8. **Coupling to Elections:** Membership publishes nothing that Elections consumes. The outbox maps four Member events, and `MembershipSuspended`/`Terminated`/`Restored` have no subscriber (FM-11). A lineage-based `Domain/Voting/VotingEligibilityPolicy` has no callers: a **fourth** eligibility model.

**Classification:** all of these are **conformance evidence for a deferred phase** (EM-SEQ-001). They map to `PBDIGIT-05/06/08` (EPIC-02, *IMPLEMENTED — NOT VERIFIED*), `49`, `209`, `211`, IERVP-2/3 and FM-11/15. **No new ID is created** (frozen baseline): each is a gap within an existing delivery obligation, and none can be fixed correctly before the constitution questions below are answered.

---

## 7. Phase D — Membership Constitution v1 (candidate structure + questions)

### 7.1 Platform invariants vs organisation configuration `[candidate]`

| Platform invariant (same for every organisation) | Basis |
|---|---|
| Membership, Affiliation and ElectionMembership are distinct. None is created as a side effect of another. | EM-EO-003, EM-ENT-006 `[adopted]`; principle §5 |
| Other contexts consume **Membership Standing** only, never Membership's storage. | `[candidate]` (O-1) |
| Every membership state transition has an authorised actor, a decision and recorded evidence (who, when, why). | `[candidate]`; matches ADR-F3.2 *"suspend/terminate require actor and reason"* `[accepted-legacy]` |
| Vote anonymity: Membership never receives anything about votes. | `[adopted: ADR-T11]` |
| A per-election voter-source snapshot. Changing the organisation's mode never changes an existing election. | `[adopted: VoterSourceStrategy snapshot]` |

| Organisation-configurable (candidate) | Default today |
|---|---|
| membership types (name, fee, currency, duration / lifetime) | `legacy-impl` |
| whether a type requires approval | stored, **unused** |
| renewal window; grace period | global config; grace **unused** |
| application expiry | hard-coded 60 days (public applications only) |
| ❓ which types or standings carry **voting** rights | see MQ-5 |

### 7.2 Candidate requirement cards (Actor → Goal → Business Rule → Authority → State → Decision → Evidence → Outcome)

| ID | Actor | Goal | Business rule | Authority | State | Decision | Evidence | Outcome |
|---|---|---|---|---|---|---|---|---|
| **R-M1** Apply | Applicant | become a Member | ❓ who may apply (anyone? affiliated persons only?) | — | Application: submitted | — | application data, timestamp, source | an application awaits review |
| **R-M2** Decide application | ❓ owner/admin today (`MembershipPolicy:33-36`) | admit or refuse | ❓ MQ-2: is approval always required, or per type (`requires_approval`)? | ❓ MQ-3 | submitted → approved / rejected | approve / reject (+ reason) | reviewer, time, reason. *Today `reviewed_by` and `reviewed_at` are **not written*** | Member created (❓ in which state: MQ-1) |
| **R-M3** Record payment / exemption | ❓ treasurer? owner/admin today | the member's dues are settled | ❓ MQ-4: paid vs exempt vs waived as distinct facts | ❓ | Fee: pending → paid / waived | record | payment reference, amount, actor | standing recomputed |
| **R-M4** Renew | Member (self) or admin | extend membership | window, lifetime excluded `legacy-impl` | member or admin | expiry extended; ❓ does `expired` return to `active`? | — | renewal record | standing recomputed |
| **R-M5** Expire | System | membership lapses at the end of its period | ❓ MQ-6: grace period, and its effect | rule-based | active → expired | automatic | job record | standing changes; ❓ election effect: FM-6 |
| **R-M6** Suspend / restore | ❓ MQ-3 | temporarily withdraw standing | ❓ grounds, duration | ❓ | active ⇄ suspended | suspend / restore + reason | actor, reason | standing changes; election effect EM-FM-003 (deferred; FM-6/7) |
| **R-M7** End / terminate | Member (resign) or ❓ authority (expel) | membership ends | ❓ MQ-7: is it final? is re-application a new membership? | ❓ | → ended / terminated | end + reason | actor, reason | EM-FM-003 auto-suspension (FM-5 = which act is "removal") |
| **R-M8** Publish standing | Membership context | other contexts know who is in good standing | standing = f(status, dues, type, period) ❓ MQ-5 | — | — | derived | the derivation inputs | Elections consumes it (Phase G) |
| **R-E1** Election-Only population | Election Chief | admit an imported population | `[adopted: EM-EO-001…003]` | Chief | Affiliation + ElectionMembership | admit | import record | **no Member created** |

### 7.3 Questions for the PO checkpoint (answers needed before Phases E–G)

Each question is cross-referenced to an existing register, so nothing is asked twice.

| # | Question | Why it blocks | Existing reference |
|---|---|---|---|
| **MQ-1** | **When does a person become an active Member:** on approval, or on approval plus first payment? | it defines the state machine's entry (X-2) | — (new; business case vs code) |
| **MQ-2** | Is approval always required, or configurable per membership type? | R-M2; `requires_approval` stored but unused | — |
| **MQ-3** | **Who decides membership acts** (approve, suspend, end): the owner/admin, a named office (secretary, board), or is it per organisation? Is this the "Organisation Chief" of EM-FM-005? | authorities for R-M2/6/7 | **EM-OPEN-014 / FM-4** |
| **MQ-4** | Are *paid*, *exempt* and *waived* three different facts? Does each one count toward good standing? | L-5; the eligibility predicate | — |
| **MQ-5** | **Where do voting rights come from:** (a) the membership type, (b) the individual member, (c) a membership-constitution rule per organisation, or (d) never from Membership, only from the election's own rules? | the content of Membership Standing; `grants_voting_rights` (X-3, X-4) | IERVP-2; PO direction §7 (A–D) |
| **MQ-6** | Does expiry end good standing immediately or after a grace period? Can an expired membership be renewed back to active? | R-M4/5; X-6 | **FM-6 / W-5** (election side) |
| **MQ-7** | Is ending final, with re-joining as a *new* membership? Is a resignation distinct from an expulsion? | R-M7; the lineage model | **FM-5 / W-7** |
| **MQ-8** | **Is "membership of a committee" part of Membership, or a separate Governance concept?** | X-1, L-2; ADR-F3.2 status | ADR-F3.2/F3.3 (`accepted-legacy`) |
| **MQ-9** | Rename the authorization role `member` (L-1)? Accept the term **Organisation Affiliation**? | the glossary | FM register `W-6` |
| **MQ-10** | Is *"election eligibility automation"* (auto-enrolment of members) part of the P2 promise? | X-7, MQ-0 | 2026-10-08 review O-2 |

**Not asked again (already registered):** FM-1…FM-15 / W-1…W-7 (election effects of membership changes), F-2 (participation-lock guard), ADR G8/G11, `PBDIGIT-49` (the authoritative eligibility home). Phase G will answer these *from* the MQ answers or route them to the ARB.

---

## 8. Remaining sections (after the checkpoint)

Plan Phases E–J correspond to the outline's §8–23: lifecycle, authorities, rights and obligations, types, fees, standing, the Membership ↔ Election contract, the context map, the decisions required, the domain model, requirements and traceability. They are **drafted after the MQ answers**, because every one of them depends on MQ-1…MQ-8. Pre-work that is safe now and is already done above: §6 evidence and the §7.2 cards.

---

## 9. Session observations (classified)

| Observation | Class |
|---|---|
| The organisation side of Membership has no adopted rule at all. The Manifesto covers only the election side. | **Governance** (a missing canonical home: a *Membership* rule catalogue analogous to the Election Manifesto). **Returned to governance; nothing invented.** |
| ADR-F3.2/F3.3 (`accepted-legacy`) predate the current governance model and sit in `architecture_legacy/` | **Governance:** confirm or retire them (MQ-8) |
| §6 items 1–8 | **Engineering conformance**, deferred phase; existing owners |
| My 2026-10-08 integration review understated D-ENT-2 | **Documentation:** corrected additively (that review §8) |

**Traceability:** plan `20261008-0008` · `ELECTION_MANIFESTO.md` §1, §2, §5, §6 (`:93-105`, `:347-363`) · `2026-08-12-arb-package-membership-hierarchy-refinement.md` §1, §7 · `2026-08-12-election-only-first-governance-baseline.md:81-95` · `PBDIGIT-68` · ADR_20261004_1549 · ADR-F3.2/F3.3 · `docs/membership/20260403_0006_business_case_of_membership_management.md:7-100` · `PBDIGIT-EPIC-02` (05/06/08) · `EloquentMemberRepository.php:38-71` · `MembershipTypeController.php:52-65` · `MemberController.php:192-207, :309, :338` · `VoterImportService.php:183, :293-312` · review `2026-10-08-member-to-election-membership-integration-review.md`.
