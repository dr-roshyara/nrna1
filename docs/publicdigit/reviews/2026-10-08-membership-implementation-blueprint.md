# Membership Implementation Blueprint (architecture → implementation mapping; no code)

**Date:** 2026-10-08 · **Supersedes:** `2026-10-08-membership-implementation-readiness.md` (gate content carried over)
**Canonical model:** `…-membership-architecture-ratified-candidate.md` (**RC**) · **Decisions:** `…-membership-decision-register-final.md` (**DRF**)
**Implements nothing.** Layering per `.claude/CLAUDE.md`: Domain = pure PHP; Application = no Eloquent / Facades, DTOs; Infrastructure = Laravel.

## 1. Gate

**CONDITIONAL GO.** No Membership-domain code may start until:
- **G1:** the architecture is ratified (DRF B);
- **G2:** BD-1 and BD-I5 are answered (they fix the parameters);
- **G3:** a PO sequencing act under EM-SEQ-001;
- **G4:** an EP-01 plan is approved for the slice;
- **G5:** there is a story ID (frozen baseline: reuse EPIC-02 `PBDIGIT-05/06/08`, `-49`, `-68` or `-221` where they cover the slice).

**Per-command answers are needed before the matching command:**
- BD-2, BD-NU before the number;
- BD-REC0 before Recognise;
- BD-REV before g2;
- BD-CONSENT (+ LQ-1) before Admit with a decision;
- BD-TYPE before ChangeType;
- BD-SUSP (term pause) before TermExtension.

## 2. Domain (`app/Contexts/Membership/Domain`)

| Element | Kind | Notes |
|---|---|---|
| `Membership` (EM-VOC-001 "Member") | aggregate root | MembershipId, PersonRef, OrganisationId, MembershipNumber (credential), periods, fact log, `seq` counter |
| `MembershipPeriod` | entity | PeriodId; resolved lifetime, term segments, type segments and restrictions, all **derived** by a fold |
| `MembershipFact` + kinds | value objects | Admit, Renew, ChangeType*, Suspend, Restore, End(kind), Annul, Void, Correct, Trim (+ Recognise / TermExtension, optional) |
| `Provenance` | VO | Decision{actor, AuthoritySnapshot, PersonDeclarationRef?} · Rule{ruleRef, version} · Attested{sourceKind, sourceRef, importer} |
| `AuthoritySnapshot` | VO | actor, claimedCapacity, ruleRef, policyVersion, checkResult, checkedAt |
| `Interval` / `TermSegment` / `TypeSegment` / `Restriction` | VOs | `[from, until)`; a precision marker for attested dates |
| `Resolution` | domain service (pure) | the fold over facts in seq order → resolved periods. Deterministic |
| `MembershipPredicates` | domain service (pure) | InLifetime, Ended, TermCovered, InForce, Lapsed, Suspended, IsMember, CurrentPeriod, CurrentType, the Q-TEN set; Kleene three-valued |
| `OverlapPolicy` | policy | g1, g2 (refuse \| trim, per BD-REV), ER; scope key (p,o) or (p,o,class) per BD-I5 |
| `ConsentPolicy` | policy | per BD-CONSENT |

## 3. Application (`app/Contexts/Membership/Application`)

| Command (DTO) | Handler responsibilities | Guards | Transaction |
|---|---|---|---|
| `AdmitMember` | load or create the Membership; AuthorityCheck port; ConsentPolicy; allocate or validate the number | g1, consent, authority | one Membership |
| `RenewMembership` | append Renew | the period exists and its lifetime is not ended | one Membership |
| `ChangeMembershipType`* | append ChangeType | in lifetime | one Membership |
| `SuspendMembership` / `RestoreMembership` | append | in lifetime; authority | one Membership |
| `EndMembership` | append End(kind) | the period is open; authority (or the member's own resignation) | one Membership |
| `AnnulFact` / `VoidFact` | append Annul / Void (+ Trim facts if the target's lifetime enlarges) | g2 / ER; authority (appeal organ vs registrar) | one Membership |
| `CorrectFact` | append Correct (+ Trim) | g2 / ER; authority | one Membership |
| `ImportHistoricalMembership` | append attested Admit / End / Renew | g1 / g2 / ER; number uniqueness | one Membership per row |
| `MergeMemberships` (future, DS-3) | survivor + merged-into; reconciliation facts | an organ's decision | two Memberships (a saga or a single transaction) |

**Ports:** `AuthorityCheck` (Governance) · `MembershipRepository` · `NumberAllocator` · `MembershipFactsQuery` (published) · `Clock`.

## 4. Persistence (`Infrastructure`; implementation choices)

| Item | Mapping |
|---|---|
| `memberships` (root) | one row per (organisation, person). Replaces the legacy `members` row semantics (RC §6) |
| `membership_periods` | id, membership_id. Materialised resolved lifetime and current classification (cache, **not** authoritative) |
| `membership_facts` | id, membership_id, seq (unique per membership), kind, target_fact_id, period_id, effective_at, decided_at, recorded_at, precision, provenance (json), payload (json), cause_fact_id. **Append-only** (no update/delete grants) |
| uniqueness | `UNIQUE(organisation_id, membership_number)` (if BD-NU = per organisation) · `UNIQUE(membership_id, seq)` |
| concurrency | an optimistic version on `memberships` (or a row lock) per command, which serialises a Membership's facts |
| projections | current state per membership; IsMember-at-date query support. Rebuildable from the facts |
| tenancy | `organisation_id` on every row; tenant scoping on queries |
| **no cascade deletes** | an affiliation deletion never deletes a membership |

## 5. Integration

| Flow | Mechanism | Contract |
|---|---|---|
| Membership → Election | `MembershipFactsQuery` (OHS) + an Election-side ACL | IsMember(p,o,asOf), Suspended, CurrentType, Q-TEN predicates, (MembershipId, asOf); events: End, Suspend, Trim. **Consumed only in the Full Membership phase** |
| Membership → Finance | events | FeePaid (existing), `PeriodTrimmed{periodId, removedInterval, causeFactId}` |
| Membership → Audit | fact stream | facts with the three clocks, provenance, cause |
| Governance → Membership | the `AuthorityCheck` port | result + rule ref + version |
| Identity → Membership | PersonRef; future equivalence event | — |

## 6. Migration (dispositions)

| Legacy | Disposition |
|---|---|
| two Member models | MIGRATE → one aggregate |
| status vocabularies | REINTERPRET → attested facts |
| `expired` | DEPRECATE (derived lapse) |
| `joined_at` / `membership_expires_at` | MIGRATE → Admit + first term segment (attested, with precision) |
| `membership_renewals` | MIGRATE → Renew facts |
| `suspended` | MIGRATE → Suspend (attested) |
| `organisation_user_id` UNIQUE | REINTERPRET → the root key (person, organisation) |
| affiliation cascade | DELETE (the cascade) |
| membership number | MIGRATE (root; per-organisation uniqueness) |
| `fees_status` / `fee_state` | MIGRATE → Fee (E0b-S) |
| `grants_voting_rights` | UNKNOWN until BD-GVR |
| `endMembership` | DELETE |
| six re-entry paths | MIGRATE → `AdmitMember` |
| no authority / exact dates | REINTERPRET → attested; precision; never fabricated |

**Dry-run reports first.**

## 7. Sequencing (after the gate)

1. the fact store + seq + provenance + no-cascade (model-neutral);
2. the aggregate core: Admit (g1), End, Resolution, predicates, KnownAt;
3. the term: Renew → segments; replace the expiry job;
4. reversals: Annul, Void, Correct with g2 / ER + Trim facts; PeriodTrimmed;
5. restrictions (needs the Governance port);
6. number (allocator + constraint);
7. migration (dry-run → apply);
8. the published query port (consumed by Election in the Full Membership phase only).

## 8. Acceptance tests (RED-first seeds; from RC / PV)

- re-entry: the same MembershipId and a new PeriodId;
- a backdated re-admission into an existing lifetime is refused (g1);
- End → re-Admit → Annul(End): trimmed (BD-REV = trim), ids intact, a Trim fact recorded; or refused (BD-REV = refuse);
- a late renewal leaves the lapse gap with IsMember false;
- KnownAt(τ before a correction) reproduces the old answer;
- a same-instant episode exists and is never in force; enlarging it over another period is refused (ER);
- Void excludes a record from statistics; Annul keeps the history;
- a suspended member: IsMember true, Suspended true;
- Election reads no Membership table (architecture test);
- deleting an affiliation leaves the membership history intact.

## 9. Risks

- seq ties and concurrency (optimistic version);
- policy drift (recorded Trim facts);
- legacy status read as truth (attested only);
- duplicate persons (DS-3);
- time zone (fix before step 3);
- Finance policy for trims (DS-1 before step 4);
- consumers reading tables (architecture test).

## 10. Prioritised TODOs

| ID | Pri | Owner | Reason | Dependency | Blocking? | Type | Phase |
|---|---|---|---|---|---|---|---|
| T-01 | **P0** | Principal Reviewer | ratify DRF B (AR-1…AR-15) | — | yes (architecture) | decision | ratification |
| T-02 | **P0** | PO | BD-1 (identity naming / EM-VOC-001) | — | yes (fixes a parameter) | decision | ratification |
| T-03 | **P0** | PO | BD-I5 (INV-9 scope) | — | yes (fixes a parameter) | decision | ratification |
| T-04 | P1 | PO | sequencing act (EM-SEQ-001) | T-01 | yes | decision | before slice 1 |
| T-05 | P1 | PO | EP-01 plan for slice 1 (fact store + core) + story ID | T-01…T-04 | yes | decision | slice 1 |
| T-06 | P1 | PO | BD-REV, BD-CONSENT (+ LQ-1), BD-NU, BD-2 | T-01 | for the respective commands | decision | slices 2–6 |
| T-07 | P1 | implementation | the tenant time-zone source | — | for step 3 | implementation | slice 3 |
| T-08 | P2 | Finance / E0b-S | DS-1 fee policy for trims; DS-5 standing | T-01 | for step 4 / E0b-S | decision | before Full Membership |
| T-09 | P2 | Governance | DS-4 authority model + port | — | for step 5 | decision + implementation | before Full Membership |
| T-10 | P2 | PO | BD-REC0, BD-TYPE, BD-SUSP (term pause), BD-GVR | T-01 | for optional facts | decision | before Full Membership |
| T-11 | P3 | Election | DS-2 Q-FM-MAP / FM-5 / FM-6 | Full Membership phase | no | decision | Full Membership |
| T-12 | P3 | Identity | DS-3 person equivalence + Membership merge act | — | no | decision + implementation | later |
| T-13 | P3 | legal | LQ-2…LQ-4 | — | no | validation | later |
| T-14 | P4 | future | ML-assisted duplicate / migration-anomaly detection (advisory) | data | no | enhancement | future |
| T-15 | P2 | PO | authorise the model-neutral fixes (F-4, F-6, F-8, F-11, F-12, F-1/F-5) + `PBDIGIT-257/-202/-209` | — | no | decision | any time |
