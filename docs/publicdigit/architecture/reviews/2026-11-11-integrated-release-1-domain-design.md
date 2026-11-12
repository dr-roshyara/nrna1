# Integrated release-1 domain design: Membership, Organisation, Governance, Forum, Newsletter, Geography

| | |
|---|---|
| **Status** | **PROPOSED; partly revised** by `2026-11-11-integrated-baseline-coherence-review-and-completion.md` (§2, §3, flows A, D, E, contracts 2–5). Written by Claude, **not independent** |
| **Commission** | "Integrated Release 1 Baseline: Membership, Organisation, Forum, Newsletter and Reference Geography", accepted by the Principal |
| **Integrates (does not repeat)** | Organisation §49–§50 (ACC D-156); Governance thin slice + correction notes 1–2 (ACC D-157); Newsletter and Forum (ACC D-158, amended by D-161); C1/C2 specification (ACC D-162) + CR-02 (D-161); Membership Renew / Suspend / Restore **revision 3** (P); AuthorityCheck review (2026-11-11); whole-baseline assessment (2026-11-11); context map §16.6 (Geography) |
| **Scope decisions** | D-146, D-147, D-148 … D-155, D-159, D-160, D-161, D-163 |

**Labels:** [RAT] · [AD] · [BF] · [AC] · [TD] · [TU] · [WD] · [P] · [OQ] · [LQ] · UNKNOWN.

## 0. Disagreements (first)

- **With the reviewer: none.** All four points held.
- **One went further than the reviewer thought:** suspension of a lapsed period is **refused** by the ratified guard (final §18, "in force"). My revision 2 had it accepted, so it was a defect, not a justification gap (Membership revision 3, C-2).

## 1. The integrated model in business language

An organisation on PublicDigit:
- **keeps its own units and decides who belongs where** (Organisation);
- **admits, renews, suspends and ends memberships** (Membership);
- **only through bodies and offices its own rules empower** (Governance);
- **its members discuss in their units' forums** (Deliberation);
- **receive the newsletters of their units** (Communication).

**A person registers once** (Identity). **The official map** (Reference Geography) **plays no part in release 1**: provinces and districts are the organisation's own units, not map entries.

| Person's situation | Read own unit's forum (and above) | Post | Receive newsletters | Formal notices |
|---|---|---|---|---|
| Belongs, membership in force, not suspended | yes | yes | yes (unless opted out of that series) | yes |
| Belongs, **lapsed membership** | yes | no | no | per the notice's own audience |
| Belongs, **suspended** | yes | no | no | per the notice's own audience |
| Worker or guest attached to a unit | yes (read) | no | no | — |
| Pending applicant | no | no | no | — |
| Not in the unit or below it | no | no | no | — |

*(D-152, D-153 as amended by D-161; D-151; K-3. "Belongs" = Organisation belonging, which counts while membership coverage exists, CR-01.)*

## 2. Membership revision 3 (corrected)

- **`IsMember` is unchanged [RAT].** A lapsed person **holds a lapsed membership** (covered, belongs) but **is not a member**.
- **Suspend and Restore require "in force"** (final §18): suspending a lapsed period is refused. **Renew first, then suspend** (one resolution, grouped if needed).
- **Grouped renewals:**
  - all or none, by **one transaction under the organisation's ledger-clock lock**;
  - **fail-closed size bound**;
  - Prepared state survives interruption.
- **CR-02 unchanged** (`inForce`, `suspended`).
- **Status:** PROPOSED; ready for an independent check, then acceptance.

## 3. Ownership matrix

| Fact / decision | Owner | Consumers | Never owned by | Evidence |
|---|---|---|---|---|
| Person, Account, login, claim, `PersonRef` | **Identity** | all | Organisation, Membership | D-75, D-110, D-111, D-113 |
| Organisation registry, profile and contacts, capacities | **Organisation** | Communication (contacts) | Identity | D-101, D-106 |
| Units, hierarchy over time, belonging, placement | **Organisation** | Governance, Communication, Deliberation | Geography, Membership, Governance | D-106, D-142, §49–§50 |
| Workspace access | **Organisation** (enforced by Authorization) | Authorization | Membership, Governance | D-104 |
| Membership periods, terms, renew / suspend / restore / end, coverage, `inForce`, `suspended` | **Membership** | Organisation (C1/C2), Governance (C3) | Organisation, Communication, Deliberation | RAT, CR-01, CR-02 |
| `InForceUnrestricted` (derived) | ~~Organisation derives~~ **the consuming context derives** (coherence review §2B; F-17), for consumers | Communication, Deliberation | — (not a fact) | D-161; terminology note 2 |
| Rules on file, committees, offices, terms, decisions, authority | **Governance** | Membership, Organisation, Communication, Deliberation | Organisation, admin roles | D-144, D-157, AuthorityCheck review |
| Forums, posts, supports, moderation acts | **Deliberation** | Communication (notifications) | Organisation, Governance | D-158 |
| Series, issues, dispatch, opt-outs | **Communication** | — | Membership, Organisation | D-158, D-151 |
| Official references, containment, publication | **Reference Geography** | Organisation (**later release**) | Organisation | D-93, §16.6 |

**No missing owner. No double owner.**

## 4. Context relationships (release 1)

```
Identity ──PersonRef──▶ Organisation ◀──C1/C2 (coverage, inForce, suspended)── Membership
                          │   ▲                                             ▲
   StructureAsAt, ancestors│   │ change sets (push), AuthorityCheck (pull)  │ AuthorityCheck (push/pull)
                          ▼   │                                             │
                        Governance ─────────────────────────────────────────┘
                          │ AuthorityCheck (editor, moderator)
                          ▼
   Organisation ──MembersOfScopeAsAt / BelongingWithAncestorsAsAt (+ InForceUnrestricted)──▶ Communication, Deliberation
   Reference Geography ··· (dormant in release 1; later: Organisation reads at confirmed positions, A-8)
```

**Direction rules:**
- **Membership never reads Organisation** (C0, one-way).
- **Communication and Deliberation never read Membership** (§36).
- **Governance never reads mandates back, and never reads the map** (G-I10).

## 5. End-to-end flows

### Flow A: admission

| # | Step | Owner | Fact or check |
|---|---|---|---|
| 1 | Person registers once (or exists) | Identity | Account (D-110) |
| 2 | Applies to the organisation; the organisation's record of the person is created | Organisation (register entry, D-101); Membership (application, M-TDD-01) | `PersonRef` issued by Identity |
| 3 | Admitting body or office decides | Governance | **AuthorityCheck `admit` → Authorised** (else Undetermined → the application waits, D-149) |
| 4 | **Admit with an explicit term** (finite or ∞) | Membership | Admit fact; **evidence = six fields + basis**; ledger clock |
| 5 | **Unit named in the admission** (else top unit, D-114) | Organisation | placement with **the same act reference and the same authority answer** (D-154; AuthorityCheck review G-6) |
| 6 | C1 published | Membership → Organisation | coverage, `inForce` |
| 7 | Member claims access to the member area | Identity | **claim code** (D-113; I-TDD-02) |

### Flow B: renewal (incl. late and back-dated)

| # | Step | Owner |
|---|---|---|
| 1 | Committee resolution (one or many parts; grouped or not) **or** an authorised office acts | Governance |
| 2 | AuthorityCheck `renew-membership` (push for a resolution, pull for an office) | Governance |
| 3 | Renew fact(s): segment from `effectiveAt` (**late keeps the gap; back-dated closes it; after End refused**) | Membership |
| 4 | C1 with new `inForce` (coverage unchanged) | Membership → Organisation |
| 5 | `InForceUnrestricted` true again: posting and newsletters resume from the effective date as known from the record time | Organisation → consumers |

### Flow C: suspension and restoration

| # | Step | Owner |
|---|---|---|
| 1 | Decision by an authorised body or office; AuthorityCheck `suspend-membership` | Governance |
| 2 | **Suspend (only while in force)**, open-ended or until a date | Membership |
| 3 | C1 with `suspended` | Membership → Organisation |
| 4 | Member can still **read**; **cannot post, receives no newsletters** | Deliberation, Communication |
| 5 | **No automatic loss of office** (D-24; statute decides) | Governance |
| 6 | Restore (in force; inside the restriction) or the `until` date passes | Membership |

### Flow D: membership forum

| # | Step | Owner |
|---|---|---|
| 1 | A unit exists → **its forum exists, read-only** | Organisation (`UnitEstablished`) → Deliberation |
| 2 | Committee designates a moderator at a sitting (exclusivity per decision; moderators may be several) | Governance |
| 3 | Forum **open** while a moderator tenure covers it (D-150) | Deliberation (asks Governance) |
| 4 | **Read:** the person's belonging unit is this unit or below it (D-152) | Organisation answers; Deliberation decides |
| 5 | **Post:** step 4 **and** `InForceUnrestricted` (D-161) | same |
| 6 | Moderation act with reason; AuthorityCheck `moderate`; challengeable (D-53); nothing deleted | Deliberation, Governance |
| 7 | Unit ends → forum archived read-only; moderator office ends (D-145) | Organisation → Deliberation, Governance |

### Flow E: newsletter

| # | Step | Owner |
|---|---|---|
| 1 | Committee decides a series and its scope unit; designates an editor (D-58, D-60) | Governance → Communication |
| 2 | Editor sends; **AuthorityCheck `send-newsletter(series)` at send time** | Communication asks Governance |
| 3 | **One τ for the send:** Organisation returns members of the scope (unit and below) with `InForceUnrestricted` | Organisation |
| 4 | Remove opt-outs of **this series** (D-151); contact from the organisation's profile (never the login e-mail) | Communication |
| 5 | **Any source incomplete → no send** (never partial, D-73) | Communication |
| 6 | Snapshot with the basis of each inclusion; delivery records (attempt / accepted, never "read") | Communication |
| — | **Formal notices** (convocations) are a different capability with their own audience; opt-out does not apply | Communication (later) |
| — | **[LQ]** counsel confirms the legal basis before launch (D-151) | external |

### Flow F: organisation structure and Geography

| # | Step | Owner |
|---|---|---|
| 1 | Organisation joins; statutes and the decision naming a registrar are filed (D-148) | Governance (records) |
| 2 | **Registrar records existing units from evidence** (D-159): two-layer basis (registrar's authority checked; the original act recorded from evidence, challengeable) | Organisation + Governance |
| 3 | Later changes: decided by a designated body (D-154), pushed as change sets | Governance → Organisation |
| 4 | **Members placed by act** (admission or transfer); **never by address, residence or map** (D-79, S-8) | Organisation |
| 5 | **Later release:** a unit may **reference** official map entries (coverage at known positions); area rules read the map only at confirmed positions (A-8, D-156 gated) | Organisation reads Reference Geography |
| — | **Why the map never decides belonging or forum access:** D-106 §6, S-8, D-152 (containment of the organisation's **own units**). **Legacy `geo_*` fields and `residence_geo_unit_id` are never read** | — |

## 6. Cross-context contracts

| # | Producer → consumer | Meaning | Business time | Knowledge time | Failure | Consumer decides | Status |
|---|---|---|---|---|---|---|---|
| 1 | Membership → Organisation (C1, C2) | coverage, `inForce`, `suspended` per membership | intervals | τ ≤ `closedThrough` | refused (`BOUNDARY_NOT_CLOSED`, `SOURCE_NOT_LEDGER`), never partial; **refusal ≠ "not a member"** | belonging counts; `InForceUnrestricted` | RAT + ACC (D-162) |
| 2 | Organisation → Deliberation | person's belonging unit with ancestors + `InForceUnrestricted` | at request | same τ | deny the read / post | read / post | ACC (D-158) + D-161 |
| 3 | Organisation → Communication | members of scope + `InForceUnrestricted` | at send | one τ | **no send** | audience | ACC + D-161 |
| 4 | Governance → Organisation | change sets with evidence (push); AuthorityCheck (pull) with caller-supplied ancestors | decision date | τ | refused | records | ACC (D-157 + notes) |
| 5 | Governance → Membership | AuthorityCheck six fields + basis | `evaluatedFor` | `checkedAt` | refused; **Undetermined ≠ No** | applies the fact | RAT (fields) + ACC (basis) |
| 6 | Governance → Communication, Deliberation | editor / moderator authority | at the act | τ | refused | send / moderate | ACC |
| 7 | Organisation → Reference Geography | official references at positions | — | `positionAt(τ)` / confirmed position | refused | — | **RAT contract, dormant in release 1** |

## 7. Tactical design (release 1, only what is necessary)

| Context | Aggregates (consistency boundary) | Key invariants | Notes |
|---|---|---|---|
| **Membership** | Membership per (person, organisation) | ratified INV set; INV-12; guards (final §18) | **No new aggregate**; ledger clock per organisation |
| **Organisation** | **Tree** (structure, S-0 … S-9); **PersonBelonging** per (organisation, `PersonRef`) (B-1 … B-8) | structure only by decided change set or evidence record; placement by act; root default | §49–§50; reduced descent (no area rules) |
| **Governance** | Organ, Position (tenures), RuleProvision, Resolution (parts, groups), DecisionLog; GroupApplication | G-I1 … G-I10; competence evidence (four elements) | thin slice; round 7 deferred |
| **Deliberation** | Forum, Thread, Idea, ModerationAct | open only while moderated; history never deleted; one support per person | D-158 |
| **Communication** | Series, Issue, Dispatch, Preference | send only with authority at send time; full audience or none; content fixed when sent | D-158 |
| **Reference Geography** | — (not built for release 1) | — | RAT design; PO-1 … PO-8 later |

**One-developer constraint [AC]:**
- **one database, one deployable**, contexts as namespaces with architecture tests;
- **no distributed transactions**: cross-aggregate atomicity only where a business decision requires it (grouped resolutions) and only inside one database transaction;
- **events via the existing outbox**;
- **no event sourcing** (ratified final §8);
- **queries in-process through Published Language DTOs.**

## 8. Invariants and temporal rules (cross-context)

1. **One τ per cross-context operation; complete or refused** (D-73).
2. **Business date ≠ knowledge time** in every fact and every authority answer (`effectiveAt` / `recordedAt`; `evaluatedFor` / `checkedAt`).
3. **Append-only everywhere.** Corrections are new records.
4. **Belonging counts only while covered; rights need `InForceUnrestricted`.**
5. **Authority never inferred:** not from admin roles, names, levels or the map.
6. **The map never changes belonging** (inactive in release 1; A-8 later).

## 9. Release 1 boundaries

| In | Later |
|---|---|
| Units, placement by act, top-unit default; registrar onboarding | area rules (§51, independent review first); Reference Geography; wings / second trees |
| Admit (explicit term), Renew, Suspend, Restore, End, corrections | fees, dues `Standing`, automatic renewal, ChangeType |
| Governance thin slice | round 7 precedence, delegation, automatic remedies |
| Unit forums, moderation, supports | committee forums (optional), motions to Governance |
| Newsletters to members, opt-out | outside subscribers, formal notices, reminders |
| Ledger organisations only (D-160) | cutover of existing organisations (gate C-1 … C-10) |

## 10. Proof obligations [TU]

- **Ledger clock** A1–A3 (D-162).
- **Grouped-renewal transaction:** waiting, rollback, idempotent retry, size bound.
- **Tree invariants** across later-dated changes.
- **Exclusion constraint** for exclusive offices.
- **Access checks per request** without stale caches.
- **Outbox delivery** idempotency.
- **Architecture tests:**
  - no cross-context table access;
  - Communication and Deliberation do not import Membership;
  - no map or address types in Organisation's belonging.

## 11. Genuine open questions

- **[OQ] None for the Principal.**
- **[LQ]** the newsletter legal basis (D-151).
- **UNKNOWN per organisation:** statute content (terms, who renews, suspends and moderates; precedence). Recorded per organisation; no platform rule.
- **Deferred processes:** dissolution (B-5); challenges without a recorded tribunal (B-6).

## 12. Assessment

**BASELINE COHERENT WITH SPECIFIC OPEN QUESTIONS** (the open items are §11, all outside the Principal's immediate decisions).

- **In plain terms:** each of the six flows can be told end to end:
  - every step has one owner;
  - every hand-over has a defined meaning and a defined failure;
  - **nothing depends on the official map.**
- **Tactical readiness for release 1:**

| Area | Readiness |
|---|---|
| Organisation, Governance (thin slice), Newsletter and Forum, C1/C2 | **ready** (accepted) |
| Membership Renew / Suspend / Restore | **ready after an independent check** of revision 3 |

- **Not independent:** an independent reviewer should check:
  - the Membership revision 3 decision table against the ratified formal proofs;
  - the grouped-transaction argument.

**Supersession note (2026-11-11):** §7's "one database, one deployable" is superseded by **D-164**: one deployable; **one central database and one database per organisation.** §3's matrix is superseded by `2026-11-11-multi-database-boundary-tactical-design.md` §2 (three kinds of ownership).
