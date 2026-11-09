# PublicDigit baseline stabilisation and strategic DDD decision preparation: review

| | |
|---|---|
| **Status** | **REVIEW / DECISION PREPARATION.** It records evidence and recommendations. It decides, accepts, ratifies and authorises nothing, and changes no status in any other record. Research convergence in this document is not authority. |
| **Authority** | generated (Claude, multidisciplinary architecture and research role). Not authoritative without Principal DDD Architect review; decisions stay with the PO, the ARB, the Principal Reviewer and the Membership owner as recorded. |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`, HEAD `4032d6ca9`. Assessment snapshot `2b89829cc` is an ancestor (100 commits between). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/`, beside the assessment it validates. |
| **Inputs (not authorities)** | `2026-11-03-publicdigit-architecture-assessment.md` (REPORT); `2026-11-03-publicdigit-as-is-baseline-architecture-report.md` (REPORT); five read-only evidence sweeps (P1/P2/P11; P3/P4/P5/P7/P9; P8/P10; domain concepts and P6; the earlier code sweeps); deterministic scripts run in this session (import graph; term presence). |
| **Not used as a source** | `docs/publicdigit/architecture/platform-rules-index.md` (PROPOSED; review findings M-01…M-10 open). |

**Labels.** [F] fact with path:line · [D] decision by a named authority · [P] proposal or recommendation · [OQ] open question · [EO] engineering observation from code · [A] assumption. Source statuses are quoted, not translated. **Diagram legend:** `[E]` established (ratified, adopted, confirmed, decided or frozen) · `[P]` proposed or candidate · `[U]` unresolved or conflicting.

---

## 1. Executive assessment

1. **The strategic frame has not moved since the assessment.** No strategic record (DR-07 paper, register, ARB pack, context map, `docs/implementation`, ADRs) changed between `2b89829cc` and HEAD. None of P1–P11 is resolved. Delivery moved: COR-2 (PBDIGIT-327) and COR-4 (PBDIGIT-329) are accepted, so P12 has progressed.
2. **The assessment's diagnosis stands, with corrections** (§2). Most corrections narrow a problem rather than widen it:
   - P8 is narrower: the payment-fact direction is already decided by the PO.
   - P2 is not blocked by P1.
   - The "competing baselines" are fewer than listed for product questions.
   - Two corrections widen it:
     - the anonymity guard covers less than claimed, and the vote path contains a voter↔vote linkage not recorded in committed records (G-1);
     - more Membership↔Election violations are baselined than the "four" it states.
3. **The remaining work is decisions, not discovery.** Of P1–P11, the following are prepared and await an authority:
   - ready or ready-with-conditions: P1, P3, P6, P10 (INV-ORG-SCOPE-1, XD-4 structure);
   - need a commissioning act first: P5 (falsification pass F6), P4 (after P3/P5/P9);
   - need external business evidence: P2 (statutes) and part of P11 (O-9).
4. **Critical path for Membership-related strategic DDD:** Membership owner's answers M-1…M-5 → ARB act on DR-07a (incl. ARB-8) → statutes (O-8/O-9/O-10) → Membership↔Governance relationship (P11). The vocabulary act (O-14) and INV-ORG-SCOPE-1 ratification can be taken now, in parallel.
5. **The domain concepts the commission focuses on are mostly undecided.** Position, Appointment, Tenure, Mandate, Jurisdiction, Permission and Forum have no ratified or adopted PublicDigit definition. Tenure and Forum do not exist in code at all (§9). Only Person, Member, Membership, the Election chain and the non-implication rules are established.

---

## 2. Validation of the 3 November assessment against current HEAD

**Snapshot.** Assessment HEAD `2b89829cc` → current `4032d6ca9`. 100 commits; app/test changes are limited to Membership COR-2/COR-4 (24 files, +1,580 / −50). No strategic record changed. The assessment itself has not been edited since placement (`71f7cf3b4`).

| Assessment claim | Verdict | Evidence |
|---|---|---|
| C-1: Baseline 1.1 names a "five-state" Challenge lifecycle | **Corrected:** it names **six** states (Raised→Admitted→Routed→Resolved/Dismissed/Lapsed); HEAD has eight (adds Investigating, Adjudicated). The "sole Adjudication event" claim is at `:16`, not `:19`. Baseline was stale about three hours after freezing (frozen `69ffec2d0` 13:46; ADR-T20 states `bd57c9155` 16:18, 2026-06-27) | [F] `docs/implementation/Architecture_Baseline_1.1.md:11,16`; `app/Contexts/Contestation/Domain/Challenge/ChallengeState.php:15-22` |
| C-2: two live Adjudication events outside every catalogue | **Qualified:** only `AdjudicationExpired` is published in production. `AdjudicationFailureDeclared` has class, hydrator and writer but no production enqueue (D4 HELD). **Missed:** `FeePaid` is also outboxed and hydrated without a catalogue row | [F] `AdjudicationProcessManager.php:340-356, 396`; `OutboxEventAdapter.php:31,64-73,96`; `docs/implementation/PROGRAM_STATUS.md:48`; `MembershipServiceProvider.php:582-583` |
| C-3: `Contexts/Elections` has no provider, is absent from deptrac | **Holds.** It has grown from 15 files (2026-08-13) to 82 at HEAD and is imported by 10 HTTP files | [F] `deptrac.yaml:46,62-66`; file count |
| §4.1 "AT-Q7-001 scans every context and the messaging surface" | **Corrected:** it scans Contestation, Adjudication, Election and the Shared inbox/outbox only; not `VoteController`, `app/Services` or `Contexts/Elections` | [F] `tests/Architecture/GreenfieldCoreArchitectureTest.php:33-38,65-84` |
| §4.1 "Anonymity is executable" | **Qualified:** the schema is anonymous (`votes` has no `user_id`), but the vote path writes linkage: vote id in step data and logs (recorded known gaps), and an audit receipt hash `sha256(vote_id . user_id . election_id)` logged with the user (G-1, not in committed records) | [F] `app/Http/Controllers/VoteController.php:1871-1879, 1950-1965`; `tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php:145-176`; untracked `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php:149` |
| C-4: "four verified boundary violations" | **Corrected:** six are baselined and still present: V1, V1b, V1c, V1d, V2, V3′ (plus X-1…X-9) | [F] `docs/publicdigit/backlog/PBDIGIT-318-membership-election-boundary-remediation.md`; `app/Models/Member.php:184-189` |
| B3: "AIP-14 declares the Election system the Core Domain" | **Misattributed:** AIP-14 contains no subdomain classification. The wording is in a views document that defers to frozen artefacts. The misattribution originates in `KOS-ARCH-BASELINE-001:40` | [F] `engineering/architecture/adr/ADR-AIP-02-Product-Primacy.md:16-24`; `engineering/architecture/c4/AI_Engineering_Platform_Views.md:3,11` |
| §1.1 four competing baselines | **Narrowed:** Phase-03A and KOS-ARCH-BASELINE-001 concern the engineering platform. For product questions the competitors are Baseline 1.1, PushB v1.0 and Release 1.0 (plus EPIC-002 and the Membership act) | [F] `engineering/architecture/baseline/Phase-03A-Reference-Architecture.md:5-7`; KOS-ARCH-BASELINE-001 `:40` |
| P2 depends on P1 ("recipient named") | **Not supported:** the ARB pack calls statutes a "PO action, parallel"; the PO names the recipient. The DR-07 paper: "Ownership (Q2) can be decided without them; the contracts (T6) cannot" | [F] `docs/publicdigit/reviews/2026-11-02-organisation-governance-arb-decision-pack.md:150-159`; DR-07 paper `:146` |
| P8 / §4.3: FeePaid direction undecided; theory states the opposite | **Narrowed and sharpened:** the PO decided E-1 (2026-11-02): Finance "publishes payment facts that Membership consumes". The opposite direction is stated **inside the ratified artifact set** (final implementation blueprint `:61`), not only in pre-ratification theory | [F] `docs/publicdigit/reviews/2026-11-02-membership-e0b-s-decision-record.md:4,16`; `2026-10-08-membership-final-implementation-blueprint.md:61` |
| P5 DoD "Election authority recorded in one place" | **Exceeds the ADR:** `ADR_20260813_1722` decides an evidence-ranking rule and an authority matrix, not a code placement or a bounded-context owner; its falsification pass F6 was never commissioned | [F] `docs/publicdigit/adr/ADR_20260813_1722_*:65-86, 179, 190-201`; decision package `:251-258` |
| AD-009 "2 of 4" event types covered | **Stale:** the test covers 3 of 8 outboxed, hydrated types | [F] `tests/Architecture/EventRegistryCompletenessTest.php:28-31`; `docs/implementation/Architecture_Debt_Backlog.md:27,48` |
| P13 isolated test database missing | **Partly addressed for Membership:** PBDIGIT-324 isolated PostgreSQL container (accepted with residual risks). O-4 remains open platform-wide | [F] `docs/publicdigit/backlog/PBDIGIT-324-*`; evidence `docs/publicdigit/membership/evidence/2026-11-02-w2-membership-pg/` |
| 379 scope bypasses | **Count differs:** 397 `withoutGlobalScope(s)` in 110 files at HEAD (different counting basis; classification OQ-OS-3 not authorised) | [EO] grep of `app/` |
| P12 | **Progressed:** COR-2 and COR-4 accepted; next per roadmap is COR-3, then Slice 2b | [F] commits `580870f70`, `a3454e17c` |
| "Q-1" (P4, P9) | **Undefined** in the assessment; the label also means something else in ADR-T23 | [F] `docs/adr/ADR-T-LOG-Tactical-Implementation.md:31` |

The original assessment is not modified; these corrections apply to this review only.

---

## 3. Current-state architecture landscape (ten areas)

| Area | What exists / is implemented | Formally decided or ratified | Proposed | Open / inconsistent |
|---|---|---|---|---|
| 1 Business capabilities | Full journey in code (organisation → membership → committees → elections → voting → results); demo, finance, geography, newsletters [F] | BR-1…14 (Confirmed/Decided); Manifesto rows ADOPTED; PBDIGIT-30 B1–B3, B10 APPROVED [D] | enterprise capability model none | PBDIGIT-00: "journey … does not complete" (2026-08-06); no current E2E evidence [U] |
| 2 Domain architecture | Context directories ≠ contexts [EO] | act §11 ownership statements RATIFIED; EPIC-002 ACCEPTED; BDR FROZEN [D] | Organisation, Geography, Communication contexts; Governance vocabulary (P-7) | product-scope subdomain classification absent (P7) |
| 3 Bounded contexts | Membership kernel + legacy; greenfield Adjudication/Contestation/Election; Governance; Geography | Membership RATIFIED; evidence/integrity ACCEPTED | context map PROPOSED | committee ownership (P1); Election code placement (P5); Audit/Authorization classification conflict |
| 4 Application | One Laravel 12 app; three election homes; legacy hot spots (`VoteController` 4,388 lines) [F] | voting authorisation single point (tested) | — | `Contexts/Elections` ungoverned but live |
| 5 Data | One database, row tenancy; Membership ledger unwired; three affiliation stores; "has voted" ×5 [F] | Membership–Election option A DECIDED (PO) | D-1 option C (PO direction); INV-AFF-1 | BR-9 open; production engine UNKNOWN |
| 6 Integration | outbox/inbox loop (4 consumers); in-process listeners elsewhere; direct cross-context reads [F] | Event Catalog FROZEN; Blueprint §11 FROZEN (loop scope) | — | P3 event surface; Q-new-1/2 (O-17/O-18) |
| 7 Governance / constitutional authority | GEO constitutional engine; `AuthorityCheckPort` unbound [F] | Governance owns competence/organs/authority (RATIFIED); PO-DR07-01; GOV-END B1 | DR-07 option A+B | DR-07a, ARB-8, O-10 |
| 8 Technical / deployment | Stack, CI (6 workflows), no deploy automation [F] | Laravel 12 upgrade ACCEPTED | — | deployment, monitoring, backups UNKNOWN/absent |
| 9 Security, tenancy, authorisation | `X-Tenant-Id` trusted (contained per Stage 1); 397 bypasses; secrets tracked (PBDIGIT-270); secrets in logs (PBDIGIT-317) [F] | INV-ORG-SCOPE-1 principle approved (PO) | XD-4 structure (BDR-07) | ARB act pending; G-1 anonymity linkage |
| 10 Architecture governance / verification | 42 architecture tests; deptrac for 4 dirs; reviews and acceptance records [F] | Implementation Process v1.0 FROZEN | ES-001…006 PROPOSED | guards narrower than names (AD-009/010; anonymity scan scope) |

---

## 4. Cross-functional domain concept map

### 4.1 Concept status

| Concept | Business meaning (as recorded) | Authority | Responsible context | Key open items |
|---|---|---|---|---|
| Person | a human; identity `PersonId` | RATIFIED (`…ratified.md:41`; act §11) | Identity [E]; `PersonRef` production [P] (XD-3a/b) | K3, K4, O-13 |
| Member | Person holding an Organisation Membership | EM-VOC-001 ADOPTED (`ELECTION_MANIFESTO.md:218,404`); ratified §1 | Membership [E] | technical overloads (§4.3) |
| Membership | enduring Person↔Organisation relationship of periods and append-only facts | RATIFIED, FROZEN | Membership [E] | BD-1, BD-2, BD-SUSP, OQ-ME-1…4, FeePaid event |
| Organisation | the recognising body; tenant is technical isolation | ratified ontology (as cited); BR-1, BR-12 (K′) | Organisation context [P] | O-16, XD-15, G-4 wording |
| Committee | "constituted governance organs with positions, appointments and governance authority" | PO-DR07-01 recorded; act §11 (Governance owns organs) | **[U]** DR-07a; ADR-0001 places write model in Membership | M-1…M-5, ARB-1…8, O-16 |
| Geographical committee / Jurisdiction | committee bound to jurisdiction (hypothesis); assignment of geo units over time [P] | none | Governance (assignment) / Geography (units) [P] | BQ-J1, O-11, XD-5, OQ-OS-1 |
| Position | office or seat of an organ [P] | none; BR-14 (incompatibility per organisation) Confirmed | Governance [P] | O-8, BQ-P1 |
| Role | four meanings (PositionKind, AccessRole, Spatie role, dashboard role) | none; EM-VOC-002 covers role assignments ≠ Membership | **[U]** | O-14, XD-4 |
| Appointment | act opening a tenure [P] | programme: BDR-03 "Appointment (Authority)" owns Mandate truth (FROZEN) | Governance vs Appointment BC **[U]** (ARB-8) | CQ-2, EM-OPEN-049/066 |
| Tenure | Person holds Position over [s,e) [P] | none | Governance [P] | O-9 (BQ-P2/P3), CQ-5, CQ-6 |
| Mandate | scope of authority [P]; programme vocabulary says "scope and term" | programme: BDR-03; Event Catalog `MandateGranted/Revoked` | **[U]** (ARB-8) | definition conflict; CQ-4, CQ-8 |
| Permission | (action, resourceType) capability [P] | none | Authorisation (BDR-07: owns no truth) | XD-4, XD-7 |
| Authorisation | being allowed to act; competence evidence to other contexts | Governance owns competence (RATIFIED); BDR-07 FROZEN; INV-ORG-SCOPE-1 principle approved | supporting domain service [E per BDR-07; scope OPEN] | XD-4, OQ-OS-1…3 |
| Forum | communication space / thread / contribution [P] | none; product choice open | Communication [P] | O-15, BQ-F1…F3 |

### 4.2 Concept map

```
 Person [E] ──0..1──► Account [P XD-3b]
   │ 1                                          Organisation [E as recognising body]
   │ 0..1 per Organisation (INV-M) [E]                    │
   ▼                                                      │ contains
 Membership [E] ──periods/facts── Member [E]              ▼
   │   ⇏ Affiliation (EM-VOC-002) [E]          OrganisationalUnit [P] (O-16)
   │   ⇏ ElectionMembership (EM-VOC-003) [E]              │
   │   ⇏ VotingEntitlement (INV-11) [E]                    │ constitutes
   │                                                      ▼
   │ authority evidence via AuthorityCheck port [E]   Committee = organ [E PO-DR07-01]
   └──────────────────────────────────────────────►  owner [U DR-07a] ── Position [P]
                                                           │                │ held by
   Affiliation (User, Worker, Guest…) [P DC]               │ jurisdiction  ▼
   Role [U O-14] ── AccessRole [P]                         ▼            Tenure [P] ◄─ ε(Membership)? [U O-9]
                                                      GeoUnit [E code] /       │ opened by
   Permission [P] = f(AccessRole, EffectiveTenure,    Jurisdiction [P]     Appointment [P] (BDR-03 [E programme])
     Mandate, Jurisdiction) [P XD-4]                                          │ carries
   Forum [P O-15] ── access = Authorisation decision [P]                   Mandate [U ARB-8]
```

### 4.3 P6 vocabulary: meanings found (nothing amended)

| Word | Domain meaning(s) | Technical meaning(s) | Status |
|---|---|---|---|
| Member | holder of an Organisation Membership (EM-VOC-001) | `user_organisation_roles.role='member'` (registration); `organisation_users.role` enum; committee role `MEMBER`; three `MemberId` classes; `EnsureOrganisationMember` "any role row" | domain ADOPTED; technical names are not Membership (EM-VOC-002) |
| Membership | Person↔Organisation relationship (ratified) | legacy committee "membership lineage"; election roster "membership" in entitlement formula | ratified; others OPEN (O-16, OQ-DR07-1) |
| ElectionMembership | Election-owned roster record; Election owns it (act §11) | single `role` column mixing voter/candidate/observer/admin/committee | **ownership RATIFIED; term definition and role split OPEN (O-14)**; does not imply Membership (EM-VOC-003 ADOPTED) |
| Role | PositionKind; AccessRole | Spatie roles (global, no teams); dashboard roles; seven code vocabularies | OPEN (O-14) |
| Position | office/seat of an organ [P] | `role_path`, `role_hierarchy`; Election `CommitteeSeat` | PROPOSED |
| Observer | (a) election roster role; (b) "no voting rights" membership type | (c) default authorisation label (`Election.php:1868`); (d) `CommitteeRole::OBSERVER` (`Governance/Domain/Committee/Enums/CommitteeRole.php:12`) — **not listed in the vocabulary records**; (e) newsletter audience | OPEN (O-14); E-4 DECIDED: a voting label on a type is a classification only |

Identifier collisions recorded [EO]: "BR-5" (≥ 5 rules), "G-1" (5 meanings), "G-7" (2), "Q-1" (2), "Election Committee" (3).

---

## 5. Context ownership and responsibility matrix

| Responsibility | Owner (status) | Evidence | Implemented where | Gap |
|---|---|---|---|---|
| Person identity, equivalence | Identity (RATIFIED) | act §11 `:311-313` | `users` (account stands in) | Account/Person split PROPOSED |
| Membership truth | Membership (RATIFIED) | act §11 | legacy `members` (live); kernel (unwired) | G-3 |
| Affiliation (User, staff, guest) | Organisation (named in ratified §1 concept table; context candidate) | `…ratified.md:42` | three stores | BR-9 open; OQ-IDX-type tension |
| Governance organs, competence, authority | Governance (RATIFIED) | act §11 `:319-321` | write model in Membership (ADR-0001) + second path in Governance | DR-07a |
| Mandate truth | Appointment (Authority) (FROZEN programme, BDR-03) | `Round49-06…:16` | none | ARB-8 |
| ElectionMembership, entitlement, eligibility, exercisability | Election (RATIFIED) | act §11 `:323-332` | `Contexts/Elections`, legacy, plus Membership `VotingEligibilityPolicy` claiming "the ONLY place" | AD-2; G-5 |
| Election lifecycle | `ADR_20260807_1500` ACCEPTED (lifecycle single source); placement PROPOSED | — | `app/Domain/Election`, `app/Application/Election`, `app/Models/Election.php` | P5 |
| Settlement, accounting | Finance (RATIFIED) | act §11 | legacy Income listeners | FeePaid event (P8) |
| Payment facts → Membership | Finance publishes (DECIDED, PO E-1) | e0b-s `:16` | code does the reverse | P8 |
| Authorisation decisions | Authorization: domain service, owns no truth (FROZEN BDR-07) | `Round49-06…:20` | policies, `VotingAuthorization` | XD-4 for this scope |
| Audit | consumer (act §11) vs platform capability (BDR-09) | context map C-6 | imperative `ElectionAuditService` | classification conflict |
| Working Organisation | rules APPROVED; owner not established | PBDIGIT-30; context map §4 | `DashboardResolver`, `TenantContext` | owner UNRESOLVED |

---

## 6. Data and integration relationship analysis

### 6.1 Import dependency graph (deterministic, this session)

Context→context `use` imports in `app/` [EO]:

| From → To | Imports / files | Comment |
|---|---|---|
| Membership → Shared | 121 / 116 | expected (shared kernel) |
| **Governance → Membership** | **50 / 33** | Governance Domain imports Membership `CommitteeId`, `MemberId`, `CommitteeFacts`, `CommitteeModel`; **no Membership file imports Governance**. Ratified direction is Membership → Governance via the AuthorityCheck port, so compile-time coupling runs opposite to the ratified ownership of organs |
| Membership → Geography | 15 / 9 | committee geo policy |
| Committee ↔ Membership | 5 / 2 and 4 / 3 | read model and dashboard |
| Geography → Membership | 2 / 2 | — |
| Membership → TenantAuth / Platform / Subscription | 4, 2, 1 | **directories do not exist** (dangling imports) |
| Greenfield (Adjudication, Contestation, Election) → legacy | tenancy trait, `TenantContext`, clock; Election ACL → `App\Models\Election` | consistent with ports/ACL |

**Cycle (Tarjan SCC):** {Membership, Committee, Geography} form one strongly connected component. No other cycles. Legacy → contexts: Membership 75, Elections 60, Governance 16 imports.

### 6.2 Data and integration relationships

| Relationship | Mechanism | Status vs decisions |
|---|---|---|
| Election ← Membership facts | `Elections` adapter reads `members`, `membership_types`, `organisation_users` (V2) | ratified contract: (MembershipId, asOf) predicates; violation baselined (PBDIGIT-318) |
| Election → Membership deletion | `voters.member_id ON DELETE CASCADE` (V3′) | OQ-ME-2/3 open |
| Membership → Election writes | `Member::endMembership` updates `election_memberships` (V1) | violation baselined |
| Membership ↔ Governance authority | `AuthorityCheckPort` (consumer port); only `InterimRoleAuthority` (unbound; Admit for owner/admin, End denied) | port RATIFIED; content PROPOSED; GOV-END B1 not yet in code |
| Governance ← Membership data | `CommitteeMemberProjectionListener` reads `member_directories`; imports `CommitteeModel` | Q-new-1 (O-17) |
| Membership → Finance | legacy `MembershipFeePaid` → Income (listener registered twice); DDD `FeePaid` via outbox → listener on `'outbox.event'` that nothing dispatches | contrary to E-1 direction; P8 |
| Correction loop | outbox → relay → inbox: ChallengeRouted, DeterminationIssued, ElectionCorrectionApplied | catalogue-conformant except P3 items |
| "Has voted" | 5 stores; only `codes` conditional update is the atomic guard | consistency risk (G-18) |

---

## 7. Business requirements and invariant inventory

Only established items are requirements. Open matters are listed as questions in §10, not converted into requirements. Types: **INV** domain invariant · **FR** functional requirement · **NFR** non-functional · **AC** architectural constraint.

| ID | Type | Requirement (short) | Source | Owner / authority | Status | Acceptance criterion | Verification |
|---|---|---|---|---|---|---|---|
| REQ-01 | INV | A vote cannot be linked to its voter by any party with access to stored data | VO-1 (`Round29_Invariant_Catalog.md:251-266`); Blueprint CI-5; ADR-T11 | Principal / ARB (frozen) | FROZEN | no stored or logged value allows recomputing (vote, user) | schema test (exists); linkage test (untracked, PBDIGIT-274); scan of vote path (missing) |
| REQ-02 | INV | One Membership per (Person, Organisation) | ratified INV-M | Principal Reviewer | RATIFIED | unique (organisation_id, person_ref) | DB constraint + kernel tests (exist) |
| REQ-03 | INV | Membership facts append-only; corrections by new facts | INV-6/14 | Principal Reviewer | RATIFIED | no UPDATE/DELETE of facts | code-only today; DB enforcement absent |
| REQ-04 | INV | No voting entitlement from Membership | INV-11; act §12 | Principal Reviewer | RATIFIED | entitlement decided only by Election | `VotingEntitlementSinglePointTest`; V1b and Membership `VotingEligibilityPolicy` contradict |
| REQ-05 | AC | Optimistic concurrency with recomputation | act §13 | Principal Reviewer | RATIFIED | expected-version commit | Postgres race tests (exist) |
| REQ-06 | INV | Affiliation/role assignment is not Membership | EM-VOC-002 | PO | ADOPTED | no code derives Member from a role row | `EnsureOrganisationMember` contradicts [EO] |
| REQ-07 | INV | ElectionMembership does not imply Membership | EM-VOC-003 | PO | ADOPTED | — | not tested |
| REQ-08 | AC | Membership aggregate does not absorb committee participation | act §10 | Principal Reviewer | RATIFIED | committee code outside the aggregate | kernel purity tests (exist); legacy committee code inside the context |
| REQ-09 | AC | Membership stores authority evidence only; Governance decides competence | ratified §8; act §11 | Principal Reviewer | RATIFIED | port call per act; evidence stored | `AuthorityCheckPort` (exists, unbound) |
| REQ-10 | FR | Registration creates a User of the default organisation, not a Member | BR-2, BR-3, BR-8 | PO | Confirmed | — | not established |
| REQ-11 | FR | Working Organisation routing 0 / 1 / 2+, never guess, bootstrap ignored | PBDIGIT-30 B1.3/B1.4 | PO | APPROVED | 9-case matrix (§8 F-3) | PBDIGIT-32 not authorised |
| REQ-12 | FR | Election-day direct entry at login only | PBDIGIT-30 B10 | PO | APPROVED | — | 2+ branch missing (PBDIGIT-31) |
| REQ-13 | AC | Every authenticated request inside exactly one Working Organisation; owning = working organisation; fail closed | PBDIGIT-30 B1 invariant; INV-ORG-SCOPE-1 §1 | PO; ARB pending | APPROVED / principle approved | — | Stage 1 found no fail-closed path |
| REQ-14 | INV | No cross-organisation business rules | BR-13, BR-13a | PO | Confirmed | — | — |
| REQ-15 | INV | Position incompatibility scoped per organisation | BR-14 | PO | Confirmed | — | which positions: O-8 |
| REQ-16 | INV | Governance committees are organs; units are distinct | PO-DR07-01 | PO | recorded PO business decision | — | classification O-16 |
| REQ-17 | FR | Resignation and death recordable by owner/admin with evidence; exclusion refused until Governance competence exists | GOV-END B1 | PO | DECIDED (interim) | — | not in code (`MembershipAct` lacks End kind / evidence) |
| REQ-18 | FR | Membership owns the dues obligation; Finance publishes payment facts | E-1 | PO | DECIDED | — | code does the reverse (P8) |
| REQ-19 | AC | Membership–Election: separate persistence ownership within one application | separation Decision 1 | PO | DECIDED | violations removed per slice | S1b guard ACCEPTED; S2–S4 gated |
| REQ-20 | NFR | Tests must never reset a non-test database | Human PO incident record; `TestDatabaseSafety` | PO | implemented guard | allow-listed DB names only | fitness tests (exist) |

---

## 8. Mathematical and logical findings

Each formalisation answers a concrete question. Where a symbol depends on an open decision it is marked.

**F-1 Membership (ratified, restated).** Let P be persons, O organisations, T time. Membership is a partial injective map m: P×O ⇀ MembershipId. Periods(p,o) is a set of pairwise disjoint intervals (INV-9). IsMember(p,o,t) ⇔ ∃q ∈ Periods(p,o): InForce(q,t). Suspension does not change IsMember. *Use:* the Governance and Election contracts consume only this predicate plus (MembershipId, asOf). No contract needs the legacy `members.status`.

**F-2 Non-implication lattice (established).** For all p, o, e, t:
- User(p, o_boot) ⇏ Member(p, o_boot) (BR-3);
- Aff(p,o,c) ⇏ Member(p,o) (EM-VOC-002);
- EM(p,o,e) ⇏ Member(p,o) (EM-VOC-003);
- Member(p,o,t) ⇏ Entitled(p,e,t) (INV-11).

*Use:* each arrow is a testable property ("no code path derives the right side from the left side alone"). Three current code paths violate it [EO]: `EnsureOrganisationMember` (any role row ⇒ member); `Member::canVoteInElection` (V1b); `MemberStatus::canParticipateInForums` (membership ⇒ forum access, a concept still PROPOSED).

**F-3 Working Organisation routing (approved) as a total function.** With n = |RealOrgs(u)| and k = |EligibleElectionsToday(u)| at login:
- route = Election(e) if k = 1;
- otherwise route = CreateOrJoin if n = 0; Enter(o) if n = 1; Select if n ≥ 2.

Properties: totality (every (n,k) maps to exactly one destination), determinism, and "never guess" (no branch chooses among ≥ 2 candidates). *Use:* an exhaustive 3×3 test over n, k ∈ {0, 1, ≥2} (9 cases) fully verifies B1.3 + B10; PBDIGIT-31 found 3 cells not implemented.

**F-4 Authorisation decision template (a PROPOSED shape, to expose dependencies).**
allow(s, a, r, t) ⇔ Scope(r) = WO(s) ∧ ( Grants(AccessRole(s, org(r), t), a) ∨ ∃pos: EffTenure(s, pos, t) ∧ a ∈ Mandate(pos, t) ∧ r ∈ Jur(committee(pos), t) ),
with EffTenure(s,pos,t) ⇔ Tenure(s,pos,t) ∧ ε(s, org(pos), t) ∧ Active(committee(pos), t).

Each symbol has an owner and an open decision:

| Symbol | Owner (status) | Open |
|---|---|---|
| Scope = WO | INV-ORG-SCOPE-1 (principle approved) | ARB act |
| AccessRole | Organisation / Authorisation [U] | O-14, XD-4 |
| Tenure, Active | Governance [P] | DR-07a |
| ε (membership requirement for a seat) | Membership predicate, rule from statutes | **O-9** |
| Mandate | Governance or Appointment [U] | **ARB-8** |
| Jur | Governance (assignment) / Geography (units) [P] | XD-5, O-11 |

*Use:* this explains formally why XD-4 permission content cannot be decided before DR-07a, O-9, XD-5 and XD-7 (matches the ARB pack's READY-WITH-CONDITIONS), and shows Authorization consumes facts from four owners while owning none (BDR-07).

**F-5 Vote non-linkability (G-1).** Requirement REQ-01: no efficiently computable f over stored data D yields (vote v, user u) better than chance. Audit stores (u, h) with h = SHA-256(v ‖ u ‖ e); the votes table stores v. For each audit entry, compute SHA-256(v ‖ u ‖ e) over all v ∈ V_e and compare. Cost: |A_e| · |V_e| hashes. For 10,000 voters that is 10⁸ hashes, minutes on commodity hardware [A: estimate]. The linkage is therefore practical for anyone with read access to audit logs and votes, which is the insider and coercion threat the invariant exists for. The same reasoning applies to `vote_hash` whenever `config('app.vote_salt')` is empty [A: deployed salt unknown].

**F-6 Business-level cycle Membership ↔ Governance.** The authority arrow Membership → Governance is RATIFIED (port). The reverse arrow Governance → Membership (ε in F-4) exists only if O-9 decides that seats require membership. If both hold, the dependency graph has a 2-cycle at the business level. The recorded proposal (DR-07 paper `:191-194`) resolves it as two directed published languages, which breaks the cycle at compile time (each side depends on a published contract, not on the other's model). The code today has a different cycle: the compile-time SCC {Membership, Committee, Geography} (§6.1), and Governance → Membership imports. Neither is the ratified shape.

**F-7 Temporal: what happens to tenure when membership ends (BQ-P3).** Two candidate semantics:
- (a) **Derivation:** EffTenure is recomputed from ε, so no write on membership end.
- (b) **Cascade:** a Vacate fact is written.

(a) needs no write coupling and survives Annul (ex tunc revival) automatically. (b) needs compensation on Annul. *Use:* this is a precise question for the statute and PO decision (O-9); it is not decided here.

**F-8 Position capacity: three-way inconsistency.**
- Database: |{active assignments with (committee c, role_path p)}| ≤ 1 (partial unique index, `…/Tenant/2026_01_16_000003_create_committee_assignments_table.php:113-115`).
- Code: `ROLE_LIMITS` allows vice-chair 2, coordinator 5, member unlimited.
- Business: κ per position is open (BQ-P1).

These are consistent only if `role_path` encodes individual seats [U: not established]. *Use:* a concrete test question for the Membership owner (M-1).

**F-9 "Has voted" replication.** Five stores must agree after every commit. Only `codes` has an atomic conditional update. *Use:* candidate property-based test (any interleaving of cast attempts ⇒ at most one vote per code and all five flags equal); not run here.

Statistical analysis: **not performed.** There are no production data, scale figures (U-1…U-5, O-3) or test-run records in scope to analyse.

---

## 9. ML / computational findings

**Machine learning was not used.** For every question in this review a deterministic method gave stronger evidence: import parsing, strongly-connected-component detection, term presence, grep with source verification. ML clustering of the 143-file brainstorming corpus was considered and rejected as a research hypothesis generator, because it cannot establish authority and the corpus is already indexed by the register.

**Deterministic results:**
1. **Import graph** (§6.1): one compile-time cycle {Membership, Committee, Geography}; Governance → Membership 50 imports in 33 files; three dangling context namespaces.
2. **Term presence across authority tiers** (files containing the term):

| Term | RATIFIED (2 files) | ADOPTED/CONFIRMED rules (2) | FROZEN BDR (1) | Nov decision records (12) | Research corpus (143) | Code (1,971) |
|---|---|---|---|---|---|---|
| Person | 2 | 1 | 0 | 6 | 49 | 3 |
| Membership | 2 | 2 | 0 | 12 | 124 | 627 |
| Committee | 1 | 1 | 1 | 1 | 61 | 393 |
| Jurisdiction | 0 | 0 | 0 | 0 | 30 | 24 |
| Position | 0 | 1 | 0 | 0 | 24 | 6 |
| Appointment | 0 | 1 | 1 | 1 | 24 | 4 |
| Tenure | 0 | 0 | 0 | 0 | 16 | **0** |
| Mandate | 0 | 0 | 1 | 0 | 33 | 1 |
| Permission | 0 | 0 | 0 | 0 | 25 | 11 |
| Forum | 0 | 0 | 0 | 0 | 29 | **0** |

*Reading:* the governance concepts the commission focuses on live almost entirely in research material. Tenure and Forum have no code representation. Presence is not meaning: this locates where definitions are missing; it proves nothing about what a term means.

---

## 10. P1–P11 decision readiness and dependency matrix

Categories: **A** baseline decision · **B** strategic DDD decision before Membership-related design · **C** external business evidence · **D** implementation/verification.

| # | Item | Cat. | Readiness | Decides | Depends on | Blocks | Smallest next act |
|---|---|---|---|---|---|---|---|
| P1 | DR-07a committee write-model ownership | B | **READY WITH CONDITIONS.** ARB-1…4 and ARB-8 are readings of ratified/frozen texts, decidable now. ARB-5/6 need M-1…M-5. ARB-7 not satisfiable yet (G-7 production use, O-16, O-4) | Membership owner (M-1…M-5); ARB (ARB-1…8) | Membership owner **identity not named** ("another developer") | O-6; P11; XD-4 content; R-07/08/09/13 providers | PO names the Membership owner and sends §16 |
| P2 | Statutes | C | Request text exists but covers only part of the need (§10.1) | PO (recipient, organisation) | none (corrected: not P1) | O-8, O-9, O-10; T-08; R-07/R-08 content | PO widens and sends the request |
| P3 | Event surface | A | Options (a)/(b)/(c) written (`docs/plans/20260804-1900-wp4c1-…:98`); D3 HELD | Board (R-97 owner) | — | P4 | Board rules (a)/(b)/(c); also decide FeePaid, DeterminationIssued v3, the in-place edit `9aa0a0d31` |
| P4 | Baseline authority / 1.2 | A | Not ready; content listed (§10.2) | ARB via ADR-T + Architecture Review | P3, P5, P9 | baseline claims in every review | ARB adopts the question→artefact mapping as a working rule |
| P5 | Election code placement ADR | A | **Not ready:** F6 not commissioned; matrix stale (Elections 15→82 files) | PO/ARB (incl. whether F6 runs) | F6 | P4 | PO/ARB decide whether F6 runs |
| P6 | Vocabulary O-14 | B | **READY** (meanings only; ARB pack `:84`) | ARB | add the fourth Observer meaning to the brief | all relationship contracts | ARB act on O-14 |
| P7 | Subdomain classification | B | Analysis shows scopes differ; a product-scope classification is missing | ARB (BDR re-issue if changed) | — | prioritisation of investment | ARB states scopes; annotate context-map row 74 |
| P8 | FeePaid direction | B | Narrowed: payment direction DECIDED (E-1); open: the event name/owner and blueprint `:61` | PO routing; Membership + Finance owners; AD/CR for `:61` (actor not stated) | — | Standing (E-2) on Finance facts; W8 slice 11 | PO routes the question to the two owners with options A–D |
| P9 | Pattern applicability | A | Analysis ready: patterns differ in persistence/temporal model, not opposites | ARB (frozen Baseline element; ratified act for Membership) | — | P4 | ARB states the test ("retroactive legal history required") |
| P10a | INV-ORG-SCOPE-1 ratification | B | **READY** (act text pack `:51`); register vs pack disagree on whether O-5 is included | ARB | — | Stage 2 (G-5), PBDIGIT-266/309 | ARB act (items 1–2 can precede DR-07a) |
| P10b | INV-AFF-1 | B | Target principle only; full ratification BLOCKED | ARB; data protection | U-1…U-5 (O-3); mechanism L1/L2 | D-1 option C; BR-9 | adopt as target principle only |
| P10c | XD-4 | B | READY WITH CONDITIONS (structure) | ARB | content: DR-07a, XD-7, O-11 | authorisation contract | ARB confirms BDR-07 for this scope |
| P10d | XD-5 | B | **Not ready; no act drafted** | PO + ARB | O-11, OQ-OS-1, A-3/I-4/I-5 | jurisdiction replay; R-11 | not next |
| P10e | ES-001…006 | A | Evidence exists (ES-005-ADOPT-1; SIA-1); legitimacy gap, not an execution blocker | "the Authority" (identity not stated) | — | CLAUDE.md bindings | per-rule adoption act |
| P11 | Membership ↔ Governance relationship | B | **NOT READY to finalise**; authority direction (R-08) can be outlined now | ARB | P1, O-9 (statutes), Q-new-1/2 | strategic exit criterion "relationships specified" | outline R-08 only |

### 10.1 P2: needs not covered by the prepared statutes request

The request (ARB pack `:161-162`) covers held-together positions, membership↔tenure in general, and which organ admits, excludes, appoints and budgets. **Not covered:**
- numeric position limits and conflict-of-interest/recusal (CQ-7);
- position kinds and minimum years (BQ-P2); exceptions and who authorises them (CQ-5);
- review, suspend/restore, correct/annul, attest migrated records (CQ-3);
- finance thresholds, waivers, write-offs, refunds (CQ-4); which body is the governance body (CQ-2);
- constituting/dissolving organs; terms and discharge (BQ-P8); delegation (CQ-8, BQ-DEL);
- jurisdiction assignment; general-assembly powers and appeals (CQ-9);
- the exclusion procedure (D-3-L); resignation effective date (G-E3); statutory/ex-officio admission (LQ-1); validity after annulled tenure (CQ-6).

**Missing request metadata:** recipient; which organisation (the operator organisation is not yet created); version, in-force date and amendment history; language; confidentiality and corpus placement; who interprets; deadline; default when statutes are silent; whether organ rules of procedure are included. CQ-10 ("how far rules vary per organisation") cannot be answered by one organisation's statutes; it needs a PO platform-policy decision or several statutes.

### 10.2 P4: proposed mapping and Baseline 1.2 content [P]

| Review question | Candidate governing artefact |
|---|---|
| Evidence/integrity contexts and ownership | EPIC-002 Canonical Context Map (ACCEPTED 2026-07-25) |
| Ownership for Membership, Identity, Governance, Finance, Election | Membership ratification act §10–§11 |
| Greenfield aggregate pattern | Baseline 1.1 "pattern to copy", subject to P9 |
| Correction-loop sequencing, transactions, failure | PushB v1.0 + ADR-T20…T23 |
| Event names and contracts | Catalog v1.0 + Round50-05, subject to P3 |
| Election lifecycle authority | `ADR_20260807_1500` (ACCEPTED); placement P5 |
| Engineering platform | Phase-03A / ADR-AIP-* (out of product scope) |

**Baseline 1.2 would need:**
- an amendment log (ADR-T20, T21, T22, T23; WP-4/6; WP-4C-1; PB-006);
- a restated frozen-element table;
- an event-surface statement (after P3);
- an enforcement coverage statement (deptrac directories; guards narrower than names; anonymity scan scope);
- a pattern-applicability section (P9);
- a scope statement;
- the status of PushB.

---

## 11. Architecture conflicts and implementation gaps

| # | Conflict / gap | Sources | Authority to resolve |
|---|---|---|---|
| K-1 | Vote path linkage: audit receipt hash, step data, logs (G-1) | `VoteController.php:1871-1965` | PO (triage; backlog item recommended, not created) |
| K-2 | Committee write model: ADR-0001 (Membership) vs act §11 + PO-DR07-01; two live write paths; read model reads `committee_associations`, not the declared write store | DR-07 paper; `EloquentCommitteeMembershipReadModelAdapter.php:12,19` | Membership owner + ARB |
| K-3 | FeePaid: ratified blueprint `:61` vs E-1 vs code; DDD path never reaches Finance; legacy listener double-registered | §6.2 | PO routing; owners; AD/CR |
| K-4 | Eligibility: Election owns (ratified) vs Membership `VotingEligibilityPolicy` "ONLY place" vs `Elections` reading legacy tables; AD-2 open | §5 | ARB |
| K-5 | Event surface: three published/hydrated events outside the catalogue; catalogue edited in place once; `DeterminationIssued` schema v3 vs catalogue v1 | §2; P3 | Board |
| K-6 | Governance → Membership compile-time dependency, opposite to ratified ownership | §6.1 | ARB (with DR-07a) |
| K-7 | Position capacity: DB index ≤ 1 vs code limits vs open κ | F-8 | Membership owner (M-1); statutes |
| K-8 | Mandate: "scope and term" (programme vocabulary) vs scope only (research); owner Governance vs Appointment | §4 | ARB (O-14, ARB-8) |
| K-9 | INV-ORG-SCOPE-1 act scope: register includes O-5, pack excludes it | P10a | ARB |
| K-10 | Subdomain row in context map lacks scope; product domains unclassified | P7 | ARB |
| K-11 | `Contexts/Elections` grew into cast-time authorisation and security without an authority decision (ADR C-3 question) | P5 | ARB |
| K-12 | Stale records: `strategic-design-status.md:73,98` (OQ-DR07-0 "with the PO", already answered); `2026-11-02-membership-ddd-status.md` §4–§5; INV-ORG-SCOPE-1 §1b ("investigation not started"); AD-009 counts; vocabulary audit §11 listing decided items as open | as cited | record owners |
| K-13 | ES-005.4 DEFERRED by the Authority but bound in CLAUDE.md (recorded X-3) | P10e | the Authority |
| K-14 | Membership owner and a standing Governance owner not named | DR-07 paper `:40`; GOV-END `:4` | PO |

---

## 12. Recommended next steps

A dependency-aware sequence, not a parallel programme:

1. **Membership owner answers M-1…M-5** (DR-07 paper §16). The PO first names who the Membership owner is (K-14). *This is the single critical-path item.*
2. **ARB session, first tranche (can run before the Membership owner's answers arrive):**
   - INV-ORG-SCOPE-1 ratification, with the O-5 scope settled (K-9);
   - O-14 vocabulary, with the fourth Observer meaning added;
   - the ARB-1…4 and ARB-8 readings for DR-07a.
3. **ARB session, second tranche:** DR-07a ARB-5/6 (after the M-1…M-5 answers); XD-4 structure; INV-AFF-1 as target principle only; Q-new-1/2.
4. **Statutes request widened (§10.1) and sent by the PO.** This is independent of the Membership owner's answers and both ARB tranches, and is the only route to O-8/O-9/O-10.
5. **Board ruling on P3**, then the ARB on P9. These feed P4. P5 waits until the PO/ARB decide whether F6 runs.
6. **P11:** outline R-08 (authority direction) only, after the second ARB tranche; finalise after O-9 is answered.
7. **In parallel and unaffected:** Membership delivery P12 (COR-3, then Slice 2b) within the ratified architecture; PO triage of K-1 as a security matter outside this sequence.

---

## 13. Confidence, evidence and limitations

- **HIGH confidence:** code facts (paths, counts, wiring, imports, schema); record statuses (read from headers and decision lines); the validation table in §2; the import graph.
- **MEDIUM:** sweep-based counts (events, files); interpretations of scope (P7, P9); readiness verdicts (they depend on authorities' judgement).
- **LOW / ASSUMPTION:** runtime effects (double income rows, Google login failure, linkage attack timing, deployed salt); production state (database engine, applied migrations, G-7 use).
- **Not done:** no test suite, database or deployment was exercised; no domain expert or owner was interviewed; no statutes exist in the corpus; uncommitted work of other sessions was not assessed; decisions taken outside the repository are not reflected.
- **Method limits:** term presence measures where words occur, not meaning; the import graph covers `use` statements only (not string references, container bindings or table access).

---

## Final implementation-goal assessment

### A. How far have we progressed?

Scale (from the assessment): L1 initial discovery · L2 concepts identified · L3 structure and responsibilities defined · L4 decisions accepted and governed · L5 stable, verified and ready for controlled implementation. No overall percentage is given.

| Dimension | Level | Evidence |
|---|---|---|
| Business architecture | **L2** | rules exist for person/account/affiliation and election (Manifesto rows); the governance business rules (O-8/O-9/O-10) await statutes; no capability model; journey unverified since August |
| Strategic DDD | **L3** | ownership statements ratified for six areas and EPIC-002 accepted; the map is PROPOSED; DR-07a, O-14, XD-4, P7 undecided; the commission's governance concepts are all candidates (§4) |
| Bounded context architecture | **L3** | Membership boundaries ratified and guarded; Governance owner of organs decided in principle but the write model sits elsewhere; Election core outside every context |
| Architecture baseline | **L2–L3** | pattern content sound, factual content stale within hours of freezing; three product-scope competitors; enforcement narrower than claimed |
| Tactical DDD | **L4** for the greenfield loop and the Membership kernel; **L2** for Governance concepts | 23 ADR-T decisions; kernel with formal invariants; no tactical model for Position/Tenure/Mandate |
| Implementation | **L3** | disciplined where it follows decisions; production runs legacy Membership and legacy election core; kernel unwired; ledger release gate closed |
| Verification | **L3** | strong kernel and architecture tests, isolated Membership PostgreSQL suite; no end-to-end election evidence; guards narrower than their names (anonymity scan, AD-009/010) |

### B. What remains before implementation is architecturally ready

Prioritised. Each entry: what · why · who · dependencies · outcome.

1. **Name the Membership owner and obtain M-1…M-5.**
   - *Why:* every committee, appointment and authority design waits on DR-07a.
   - *Who:* PO; Membership owner.
   - *Depends on:* nothing.
   - *Outcome:* DR-07a becomes fully decidable.
2. **ARB act on DR-07a (ARB-1…8)**, including ARB-8 (Governance vs Appointment).
   - *Why:* assigns the owner of organs, positions, appointments and mandates.
   - *Who:* ARB.
   - *Depends on:* item 1 for ARB-5/6.
   - *Outcome:* O-6 closed; providers for R-07/R-08/R-09/R-13 named.
3. **ARB act on O-14 vocabulary.**
   - *Why:* no relationship contract can be written over an ambiguous language.
   - *Who:* ARB.
   - *Depends on:* the brief updated with the fourth Observer meaning.
   - *Outcome:* one definition and owner per term.
4. **Statutes obtained (widened request).**
   - *Why:* O-8/O-9/O-10 are business rules that cannot be inferred.
   - *Who:* PO; the organisation that supplies the statutes.
   - *Depends on:* the PO naming the recipient and organisation.
   - *Outcome:* incompatibility, membership↔tenure and organ competence rules recorded.
5. **ARB act on INV-ORG-SCOPE-1 (scope of O-5 settled).**
   - *Why:* tenant isolation is a constitutional invariant that cannot be enforced without the act.
   - *Who:* ARB.
   - *Depends on:* —
   - *Outcome:* Stage 2 enforcement can be authorised.
6. **XD-4 structure confirmed (BDR-07 for this scope).**
   - *Why:* defines how permission decisions consume facts (F-4).
   - *Who:* ARB.
   - *Depends on:* content waits for items 2, 4 and XD-7.
   - *Outcome:* an authorisation contract shape.
7. **P11 Membership↔Governance relationship specification.**
   - *Why:* the only strategic exit criterion that needs new design.
   - *Who:* engineering proposes; ARB accepts.
   - *Depends on:* items 2, 3, 4 (O-9); Q-new-1/2.
   - *Outcome:* two directed published languages, or an explicitly accepted cycle.
8. **P3 → P9 → P4 baseline rulings.**
   - *Why:* reviews cannot claim to apply "the baseline".
   - *Who:* Board; ARB.
   - *Depends on:* P5 (F6 decision).
   - *Outcome:* Baseline 1.2 accepted, or a scoped governing map.
9. **P8 FeePaid event ownership routed and decided.**
   - *Why:* Standing (E-2) needs Finance payment facts; code currently flows the other way.
   - *Who:* PO; Membership and Finance owners; AD/CR for blueprint `:61`.
   - *Depends on:* —
   - *Outcome:* one fee/payment event model.
10. **P7 product-scope subdomain classification.**
    - *Why:* directs where strategic investment goes.
    - *Who:* ARB.
    - *Depends on:* —
    - *Outcome:* scoped classifications.
11. **Strategic exit assessment (P16).**
    - *Why:* the formal act that strategic design is complete or has accepted exceptions.
    - *Who:* PO + ARB.
    - *Depends on:* items 1–10.
    - *Outcome:* go/no-go for tactical design of the governance concepts.

Delivery continues in parallel without waiting: P12 Membership COR-3 and Slice 2b within the ratified architecture; the security triage of K-1.

### C. Smallest next step

**The PO names the Membership owner and sends the DR-07 paper §16 questions (M-1…M-5) to that person.** It is one act. It costs one message. It is on the critical path of every downstream item in §B (DR-07a → XD-4 content → P11), and no other single act unblocks as much.

Membership DDD strategic design has not started. This review recommends; it decides nothing.

---

## Traceability

- Commission: "PublicDigit Baseline Stabilisation and Strategic DDD Decision Preparation" (2026-11-03).
- Validated: `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-architecture-assessment.md` (REPORT; not modified).
- Companion: `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-as-is-baseline-architecture-report.md` (REPORT).
- Key sources: Membership ratified set (`docs/publicdigit/reviews/2026-10-08-membership-architecture-*`); DR-07 paper; ARB decision pack; register `2026-11-02-organisation-governance-strategic-design-status-assessment.md`; context map; BDR `docs/architecture/design/Round49-06_Boundary_Decision_Register.md`; `docs/implementation/Canonical_Event_Catalog_v1.0.md`; `Architecture_Baseline_1.1.md`; `PushB_Architecture_Blueprint.md`; `docs/publicdigit/adr/ADR_20260813_1722_*`; E0b-S and GOV-END decision records; `ELECTION_MANIFESTO.md`; business rules BR-1…14.
- Evidence was gathered read-only; no code, schema, ADR, business rule, decision record, context map, register or Platform Rules Index was changed.
