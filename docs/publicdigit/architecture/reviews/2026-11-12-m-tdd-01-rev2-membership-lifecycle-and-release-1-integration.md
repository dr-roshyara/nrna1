# M-TDD-01 revision 4: Membership lifecycle and its release-1 integration (Organisation, Newsletter, Forum, Reference Geography)

| | |
|---|---|
| **Status** | **PROPOSED integrated tactical design, M-TDD-01 revision 4 (this document's revision 3). Independently reviewed (IR-GM-01, GPT-6: READY AFTER SPECIFIC CORRECTIONS); this revision updates its dependencies and the proof list.** Previous texts: `…-docrev1.md` (`37f66b4b…`), `…-docrev2.md` (`b0331276…`). It decides nothing, ratifies nothing and authorises no implementation. Written by Claude, not independent |
| **Supersedes** | `docs/publicdigit/membership/architecture/2026-11-09-m-tdd-01-membership-participant-operations-tactical-design.md` (M-TDD-01 rev 1, proposed) **where they differ**. Rev 1 predates D-160 … D-205 |
| **Commission** | Principal, 2026-11-12: "Integrated tactical design for Membership, Newsletter, Membership Forum and Reference Geography" |
| **Binding** | **RA** (ratified Membership architecture); Membership register **D-1 … D-5(b)** (D-5(b) as clarified by **D-205**); **D-114**, **D-146 … D-169**, **D-93**, D-164, D-166, **D-194, D-200 … D-205**; C1 / C2 specification (accepted D-162) + CR-02 (D-161); renew / suspend / restore design (accepted D-177); Organisation §49–§50 (accepted D-156); Newsletter and Forum design (accepted D-158); Governance thin slice (D-157 + notes); Identity I-TDD-02 rev 12 (D-200); Platform rev 11 (D-193); Reference Geography §C + v3.11 (D-93) |
| **Depends on (proposed)** | Governance completion **rev 5** (`2026-11-12-governance-completion-tactical-design.md`); **ledger-clock amendment proposal rev 2** (`2026-11-12-c1-c2-ledger-clock-amendment-proposal.md`, with the IR-GM-01 A-02 regression guard) |
| **Changes in this revision** | corrections D-1 … D-8 of the author's review (`2026-11-12-m-tdd-01-rev2-and-governance-rev3-author-review.md`, `4b16ce1c…`); decisions **D-206, D-207** |

**Labels:** [RAT] ratified · [ACC] accepted tactical design · [AD] accepted decision · [P] proposed · [AC] conclusion · [TD] tactical choice made here · [TU] proof · [LQ] legal · [OQ] Principal question · [EXT] owned elsewhere.

## 1. In business language

**One person, one membership record per organisation, one place of belonging, one source of authority:**

| Context | Answers |
|---|---|
| **Membership** | **is this person a member, in force or suspended, from when to when?** It is the only source of that truth |
| **Organisation** | **where does this person belong** (ward, district, top unit)? It counts a member's belonging only while Membership says the membership covers the date |
| **Governance** | **who may admit, renew, suspend, end, edit or moderate?** |
| **Identity** | **who is acting?** |
| **Newsletter** (Communication) | asks Organisation **once per send** for the members of a unit and its sub-units, with their standing; removes those who opted out of that series; sends; **keeps no member list** |
| **Forum** (Deliberation) | asks Organisation at **each read or post** where the person belongs and their standing; a ward forum is read by that ward's members, a district forum by everyone in the district, the top forum by all; **posting needs good standing and a moderator in office** |
| **Reference Geography** | **the official map.** **Not used in release 1.** Later, an organisation's units may refer to map entries; a change on the map flags units for review and **never moves a member** |

## 2. Responsibility map and databases (D-164)

| Database | Contexts |
|---|---|
| **Central** | Identity (Person, Account, bindings), Platform (registry, routing, grants), **Reference Geography** (shared reference data) |
| **Each organisation's own** | **Membership, Organisation, Governance, Communication, Deliberation**, person records and contacts (D-101), outbox / inbox, audit |

**The central Account-to-organisation association never establishes membership, office or authority** (D-164; ISO-7). **Staff see content only by case grant** (D-166, D-193).

## 3. Ubiquitous language and ownership

| Term | Meaning | Owner | Source |
|---|---|---|---|
| **Period** (q) | one membership episode, admission to ending | Membership | [RAT] |
| **Coverage** | `[from, to)` intervals of a person's lifetimes | Membership | [ACC] CR-01 |
| **`inForce`** | `InLifetime ∧ TermCovered` (= `IsMember`) | Membership | [RAT] §4; CR-02 |
| **`suspended`** | `InLifetime ∧` a restriction interval | Membership | [RAT]; CR-02 |
| **Lapsed** | in lifetime, term not covered; **not an ending** | Membership | [RAT] INV-3 |
| **`InForceUnrestricted`** | `inForce ∧ ¬suspended`. **Derived by each consuming context; not a Membership fact or field** | consumer | D-161 terminology note; CR-02 :37 |
| **Scheduled resignation declaration** | a resignation with a later effective date, **held outside the ledger** until that date | Membership | **D-204, D-205** |
| **Belonging** | person · unit · capacity · valid period · basis · anchor | Organisation | [ACC] §50.3 |
| **Effective belonging** | valid on the date ∧ (if membership-dependent) within its anchored coverage ∧ latest record ∧ resolved through unit successors | Organisation | [ACC] §50.3 |
| **Series, issue, opt-out** | newsletter series, an edition, a per-series opt-out | Communication | D-57 … D-61, D-151 |
| **Forum, post, moderation act** | | Deliberation | D-50 … D-56, D-150 … D-153 |
| **Official reference unit** (`ReferenceUnitId`) | an official map entry; identity minted once, never reused | Reference Geography | [RAT] v3.11:43 |

## 4. Context map and exact contracts

| # | Contract | Producer → consumer | Shape | Failure |
|---|---|---|---|---|
| K-1 | **C2 `CoverageKnownAt`** | Membership → Organisation | `(organisationId, membershipIds[] \| personRefs[], τ) → {closedThrough, items: [{membershipId \| personRef, seq, coverage, inForce, suspended}]}` (spec :104; CR-02 :30) | `BOUNDARY_NOT_CLOSED` if τ > `closedThrough`; `SOURCE_NOT_LEDGER`; cross-tenant refused; **never partial; a refusal is never "not a member"** (spec :108–109, K-5) |
| K-2 | **C1** events | Membership → Organisation | full state per membership: `seq`, coverage, `inForce`, `suspended`, `recordedAt`, `schemaVersion` 2 (CR-01 :41–47; CR-02 :26–28) | equal `seq` with different content → integrity error. **C1 serves current views only; historical answers use C2** (§50.8:1245) |
| K-3 | **`EffectiveMembersAsAt(scopeUnit, asOf, τ)`** (R-5) | Organisation → internal | `PersonRef`s + τ + both sources' `closedThrough` + tree version (§38.5:548–553) | refused, never partial |
| K-4 | **`MembersOfScopeAsAt(unit, τ)`**, **`BelongingWithAncestorsAsAt(person, τ)`** | Organisation → Communication, Deliberation | per person: belonging (from coverage) and `inForce` / `suspended` at the same τ (spec :185). **Organisation decides no right** (:187) | send refused, or read / write denied (NFD :70, :130, :145) |
| K-5 | **AuthorityCheck** | Governance → Membership, Organisation, Communication, Deliberation | six fields + basis; act kinds below | refused; **Undetermined ≠ No** |
| K-6 | **Exclusion push** | Governance → Membership | the Resolution part, outbox → inbox, idempotent per part (Governance completion C-1b) | applied / inapplicable, reported back |
| K-7 | **`ParticipationRuleAsAt`** | Governance → Communication, Deliberation | the organisation's written rule, else **D-161 labelled "platform default (D-161)"** (D-168) | no answer → refused |
| K-8 | **R-1 `ActingRef`** | Identity → every context | the actor's `PersonRef` in this organisation | refused |
| K-9 | **Reference Geography** (later) | Geography → Organisation | `resolve(ref, p)`; coverage references `(ReferenceUnitId, knownAtPosition, valid period, basis)` (§49.6:966–973) | **not in release 1** |

**Act kinds used** (Governance thin slice and additions):
- `admit-member` / `decide-application` **with `place-member(unit)` in one request** (note 3);
- `renew-membership`, `suspend-membership`, `restore-membership` (D-163);
- **`record-resignation`** (D-203 `ownAct`), **`exclude-member`** (push), **`record-death`** (Governance completion);
- `send-newsletter(series)`, `moderate(forum)`;
- `manage-claims` (D-196).

**Direction rules [ACC]:**
- **Membership never reads Organisation.**
- **Communication and Deliberation never read Membership:** they receive standing through Organisation (CR-02 :37).
- **Governance never reads the map.**

## 5. Membership lifecycle (M-TDD-01 rev 2)

### 5.1 Commands

| Command | Authority (K-5) | Fact (RA) | Guards |
|---|---|---|---|
| **Admit** (decision on an application, with explicit term and optional unit) | `admit-member` + `place-member(unit)`, one request | `Admit` (decision provenance: actor + six fields + **declaration ref**, RA line 72) | D-1 basis; **explicit term** (D-163; open-ended allowed); D-5(b) as D-205 (3); not inside an existing lifetime |
| **Renew** | `renew-membership` | `Renew` (term segment) | [ACC] D-177: late renewal keeps the gap; after End refused |
| **Suspend** / **Restore** | `suspend-membership` / `restore-membership` | restriction interval | [ACC] D-177: only while in force |
| **Resign now** | `record-resignation` (self-service `ownAct`, or recorder) | `End(resignation)`, effective = declaration date | D-3 kinds; open period |
| **Schedule a resignation** (later date, D-201) | `record-resignation` | **no ledger fact:** a `ScheduledResignation` record | effective date > declaration date; **one scheduled declaration per period**; period open |
| **Withdraw a scheduled resignation** | `record-resignation` (withdraw shape) | **none:** the declaration → `withdrawn` | only while `scheduled` and before its effective date |
| **Execute a scheduled resignation** (system, on the effective date) | the answer stored with the declaration | `End(resignation)`: `effectiveAt` = the scheduled date, **`decidedAt` = the declaration date**, recorded on or after that date | **D-205 (2): the only fact allowed to take effect after its decision date; never after its recording date** |
| **Apply an exclusion** | pushed Resolution part (K-6) | `End(exclusion)` with the part as authority evidence | open period, else `Inapplicable` |
| **Record a death** | `record-death` | `End(death)` with evidence | open period |
| **Correct** / **Void** | `correct-membership-record` / `void-membership-record` (recording authority with evidence; **a Void is reported and challengeable**, D-206) | RA `Correct` / `Void` | RA §9 (unchanged) |
| **Annul** | **push only:** a competent body's new Resolution part (category A), **or a tribunal remedy applied by Governance as a separate `RemedyApplied` fact** (no Resolution; IR-GM-01 A-01) (D-206) | RA `Annul` with the part or the remedy application as decision provenance | RA §9: targets decision provenance only |

### 5.2 Scheduled resignation (D-204, D-205)

**States:**
- `scheduled` → `executed` (End recorded);
- `scheduled` → `withdrawn`;
- `scheduled` → `superseded`: **the period ended earlier** (another End, or an Annul / Void of the period), recorded with the reason.

**Rules:**
- **Held per period, outside the Member aggregate's facts** (like an application, D-2). It is visible to the member and to officers; **C1 / C2 do not show it** (D-204).
- **Execution and withdrawal serialise** under the existing order: **authority answer → ledger-clock lock → the membership's version compare-and-set** (spec correction note :190). The declaration's state is checked and changed in the same transaction.
- **Execution is coupled to Membership's knowledge boundary (correction D-1):**
  - **`closedThrough` may not advance past the start of an organisation-local date D** (organisation's time zone, D-5(a)) **until every scheduled resignation with effective date ≤ D has been executed** (its End recorded) **in the same `closedThrough` step**, under the ledger-clock lock;
  - **an execution failure holds `closedThrough`**, so C2 answers `BOUNDARY_NOT_CLOSED` for later τ, and Organisation, Newsletter and Forum **refuse**. **Nobody acts on a person who has resigned.**
  - **Cost, accepted as fail-closed (D-73):** a stuck execution blocks the organisation's membership answers until fixed.
  - **This changes the accepted specification's mechanism (§4 step 2), not the C1 / C2 contract. It is therefore part of the ledger-clock amendment proposal and needs the same ARB acceptance.**
- **Late execution** (the job runs after the date; now possible only before `closedThrough` passes the date):
  - effective date = the scheduled date; recording = later;
  - the ending is **backdated within the existing invariants** (D-5(b) allows backdating);
  - **withdrawal is refused once the date has started** (organisation-local), even if not yet executed (D-201: "before its effective date");
  - **withdrawal vs execution:** both under the ledger-clock lock; **a compare-and-set on the declaration's state means exactly one succeeds**, whether concurrent or delayed.

### 5.3 What the feeds show (K-1, K-2)

**No ledger fact is future-dated (D-205 (1)).** So coverage, `inForce` and `suspended` never contain a planned future change.
- **A scheduled resignation is invisible to C1 / C2 until executed.** Forum and newsletter rights continue until the date, as D-201 intends.
- **A backdated fact** (late execution, late exclusion) changes coverage **from its effective date, as known from its recording time**.
  - **Historical answers at an earlier τ do not change** (C2 at τ).
  - Organisation and its consumers read the new state at τ after recording.

### 5.4 Corrections (Annul / Void / Correct): authority (D-206)

- **The ratified algebra is unchanged** (RA §9).
- **Correct and Void: recording authority with evidence.** A Void is **reported to the organisation and challengeable**.
- **Annul: a new decision of the body competent for the original act, or a tribunal determination remedy** (push only).
- **Rules silent → `Undetermined`; nothing is changed.**
- **Act kinds:** Governance completion rev 4 §2.5.

### 5.5 Open gate: Membership's ledger clock (PO-8)

- **Membership's accepted ledger clock** takes `recordedAt = max(appClock, last + 1 µs, closedThrough + 1 µs)` (spec :74).
- **Reference Geography's ratified contract** requires every D-73 participant to **refuse on clock regression and never adjust** (v3.11 PO-6, PO-8). D-93 records this as **a known non-conformance:** "until it is resolved, cross-context historical queries involving Membership are not fully guaranteed".
- **[EXT] implementation gate. A separate amendment is proposed:** `2026-11-12-c1-c2-ledger-clock-amendment-proposal.md` (refuse and retry; never adjust). **It amends the accepted spec's mechanism, not the C1 / C2 contract, and needs ARB acceptance.** Governance completion follows the refuse-on-regression contract.

## 6. Organisation integration (§49–§50, D-114, D-147)

| Rule | Source |
|---|---|
| **every effective member has at least one effective geographic belonging; by default the root unit**, unless the admission names a lower unit | [ACC] §50.15:1337–1342; D-114 |
| **placement only by an act** (admission naming the unit, transfer, exception); **never by address or map** | [ACC] B-1 :1211; D-79, D-80 |
| **a membership-dependent belonging counts only within Membership's coverage, as Membership states it** | [ACC] B-2 :1212 |
| **transfer = end one belonging and start another, same date, one write;** membership identity unchanged | [ACC] :1168, B-4 :1214 |
| **membership ended → belonging kept; stops counting after the coverage end; history not deleted** | [ACC] §50.5:1201, scenario 8 :1275 |
| **suspension → belonging unchanged** | [ACC] §50.5:1200 |
| **coverage read from C2 at τ, never from the C1 projection, for historical answers** | [ACC] §50.8:1243–1245 |
| **one knowledge boundary for structure, belonging and schemes; refused beyond it; no timestamp adjusted** | [ACC] §49.8:1018–1022 |
| **area rules (§51) and Reference Geography: not in release 1**; §51 needs independent review first | D-147, D-156 |
| **admission → placement (correction D-8):** Governance answers `admit-member` + `place-member(unit)` in one request. Membership records `Admit` and emits C1. **Organisation records the belonging from the admission act** (basis = that act, §50.15:1340) **via Membership's outbox → Organisation's inbox, idempotent on the admission fact id**, consuming the same authority answer. **Until applied, the root-unit default holds** (§50.15:1342), so a member is never without a unit | [TD]; [ACC] §50.15 |
| **non-member capacities (Worker, Guest):** a belonging only by a placement act `place-person(capacity, unit)`; **not membership-dependent**; counts by its own validity (§50.3) | **D-207**; Gov rev 4 §2.6 |
| **pending applicants:** **no belonging from the application**; read only where placed as **Guest** | **D-207** |

**Organisation's model document fingerprint drift:**
- D-156 cites `2bc6b65c…`; the file now hashes to `1e9eaf31…` (recorded in IR-R1-02 :43, :282).
- The quotations above are from the current file.
- **[EXT] a re-fingerprint belongs to the next acceptance act** (D-173 change-control rule).

## 7. Newsletter (Communication, D-158, D-161, D-168)

**One send = one τ (D-73):**
1. **`send-newsletter(series)`** Authorised for the actor at send time. **No designated editor → no send in the organisation's name.**
2. **Audience = `MembersOfScopeAsAt(scope unit, τ)`** (the unit and every unit below it).
3. **Filter by the participation rule** (K-7): the organisation's written rule, else **"platform default (D-161)": `InForceUnrestricted`**, derived from `inForce` and `suspended`.
4. **Remove those who opted out of this series** (D-151). **Re-subscribing removes the opt-out.**
5. **Contact = the organisation's person profile (D-101), never the login address** (INV-ID-3). **No contact → excluded, reason recorded.**
6. **Any source incomplete → no send** (never partial).
7. **Snapshot** of the audience with the basis of each inclusion; delivery records (attempt / accepted, never "read").

**Privacy:** editors see **counts** of opt-outs, never identities (D-151).

**Formal notices** (convocations): a separate capability, **not suppressed by opt-out**, later release.

**Delivery protocol (correction D-5):**

| # | Concern | Rule |
|---|---|---|
| 1 | identity | `seriesId`; `issueId` per edition; **one send per issue** |
| 2 | idempotency | `Send(issueId)`: a repeat returns the existing send; **a second send of the same issue is refused** (a correction is a new issue) |
| 3 | snapshot | **frozen at one τ in one transaction:** per recipient `PersonRef`, inclusion basis (belonging, the participation rule or "platform default (D-161)", not opted out), contact reference |
| 4 | records | per recipient: `queued` → `handed-to-provider` → `accepted` \| `rejected` \| `failed`. **Never "read"** |
| 5 | retry | provider idempotency key = (issueId, recipient); **only non-accepted records are retried** |
| 6 | crash or partial failure | the outbox drives delivery; a restart resumes from the records; **nothing accepted is resent** |
| 7 | opt-out after the snapshot | **excluded at delivery** (an opt-out takes effect from its act, D-151) |
| 8 | membership or belonging change after the snapshot | **the snapshot governs** (one τ, D-73) |
| 9 | contact | read at delivery from the person profile (D-101); missing → excluded, reason recorded |
| 10 | access and retention | editors see counts only (D-151); other access per the organisation's rules; **retention [LQ]** |

**Communication keeps no member list:** only opt-outs, snapshots and delivery records.

**[LQ]:** counsel confirms the legal basis before launch (D-151).

## 8. Forum (Deliberation, D-150 … D-153, D-161, D-168)

**Every read and post is evaluated at its own τ, from Organisation's and Governance's answers:**

| Check | Rule | Source |
|---|---|---|
| **read** | the person's effective belonging is the forum's unit **or a unit below it** (`BelongingWithAncestorsAsAt`) | D-152 |
| **read, other capacities** | **workers and guests** read where an effective non-member belonging places them (`place-person`); **pending applicants only if placed as Guest** | D-153 as **D-207** |
| **read, lapsed or suspended members** | still read where they belong (belonging counts while covered; lapse and suspension are inside coverage) | [RAT] INV-3; D-161 |
| **post** | read ∧ **participation rule** (K-7: the organisation's rule, else "platform default (D-161)": `InForceUnrestricted`) ∧ **forum open** | D-161, D-168 |
| **forum open** | **a moderator designation (tenure) is valid for the forum at the read or post time** (`moderate(forum)` competence); **nobody needs to be online.** No valid tenure → **read-only**; a tenure ending makes the forum read-only from that date | D-150 |
| **moderation** | an act with reason; `moderate(forum)`; challengeable; a challenge waits for the reviewing body the rules name (D-167) | D-53 as D-167 |
| **committee forums** | **accepted requirement: confidential to their office holders** (D-56, D-152). **Deferred to a later release: a release-scope boundary, not dropped** | D-56, D-152 |

- **Deliberation keeps no member status and no hierarchy.**
- **Any answer unavailable → deny.**

## 9. Reference Geography: the later-release boundary (D-93, D-147)

- **Not a dependency of release 1.** Membership, Organisation, Newsletter and Forum run without it.
- **Later integration (as ratified and accepted):**
  - **stable identity** `ReferenceUnitId`, never reused (v3.11:43, I-1 … I-3);
  - **valid time vs knowledge time** (§C:26, :78–79); `positionAt(τ)`, defined only within `closedThroughTime` (v3.11:304);
  - Organisation stores **`(ReferenceUnitId as relied on, knownAtPosition p₀, valid period, basis)`**, never rewritten (§49.6:966–967). *(Older forms `(officialUnitRef, revision)` and `OfficialUnitRevised` are superseded by §49.6 and `RevisionPublished`.)*
  - **a revision (ended, divided, merged, re-parented)** → `CoverageReviewNeeded` (and `AllocationReviewNeeded` for rule-derived belonging). **Coverage, allocations and belonging stay as recorded until the organisation decides** (:980, :985).
  - **No belonging, membership, coverage or entitlement is inferred from geography** (§C:114).
  - **Unavailable** → Organisation acts on a complete copy and **refuses beyond it** (strategic baseline §8:399).
- **Open, unchanged:**
  - **PO-1 … PO-8** (v3.11 §6:399–406);
  - **the four-point Identity contract** (v3.11 §8:560–566): one platform-level person reference per canonical person, equality, merge notices, merge reversals.
  - **[EXT] note:** I-TDD-02 (D-200) does **not** provide that platform-level reference. Which reference platform-level contexts receive is still open (v3.11:765).
  - Deferred items L-4c, Q-2, Japanese classification, naming, address ownership (§C.6).

## 10. Temporal and consistency rules

| # | Rule | Source |
|---|---|---|
| T-1 | business date (`effectiveAt` / `asOf`) ≠ knowledge time (`recordedAt` / τ) everywhere | [RAT]; D-73 |
| T-2 | **one τ per cross-context operation; complete or refused** | D-73 |
| T-3 | **no Membership fact takes effect after its recording; only a scheduled resignation's End takes effect after its decision date** | **D-205** |
| T-4 | backdating allowed within the existing invariants; earlier τ answers never change | D-5(b); C2 |
| T-5 | append-only everywhere; corrections are new facts | [RAT]; D-202 |
| T-6 | Membership append order: authority → ledger-clock lock → version compare-and-set | spec :190 |
| T-7 | **Membership ledger clock: PO-8 non-conformance open** (§5.5) | D-93 |

## 11. Failure and refusal

| Situation | Result |
|---|---|
| Governance cannot answer, or `Undetermined` | no fact recorded; the act waits |
| C2 refuses (`BOUNDARY_NOT_CLOSED`, `SOURCE_NOT_LEDGER`) | Organisation refuses; **never "not a member"** |
| Organisation cannot answer | newsletter send refused; forum read / post denied |
| Identity cannot answer | request refused |
| scheduled-resignation job fails | retried; idempotent per declaration; the effective date unchanged |
| exclusion push for an already-ended period | `Inapplicable`, reported |
| Geography unavailable | no effect in release 1 |

## 12. Security and privacy

- Per-organisation isolation: ISO-1 … ISO-11; no cross-organisation query.
- Contacts are organisation-local (D-101). Login addresses are used only for D-179 notices.
- **Opt-out identities are hidden from editors.**
- **No context copies another's truth:** Communication keeps no member list; Deliberation no status; Organisation no membership facts.
- Staff access only by case grant (D-166).

## 13. Integrated scenarios

| # | Scenario | Owner of the fact | Command / query | Contract | Time | Result | Failure |
|---|---|---|---|---|---|---|---|
| 1 | registers and joins | Identity (Account); Organisation (person record) | register; application (declaration, outside the aggregate, D-2) | I-TDD-02 | now | Account; application pending | — |
| 2 | admitted with term, unit named | Membership (`Admit`); Organisation (belonging) | Admit; `admit-member` + `place-member` | K-5, K-2 | effective ≤ decision | period with term; belonging in the unit | `Undetermined` → waits |
| 3 | receives newsletters | Communication | send | K-4, K-7, K-5 | one τ | included if in scope ∧ rule ∧ not opted out ∧ contact | incomplete → no send |
| 4 | opts out, later re-subscribes | Communication | opt-out / re-subscribe | — | from the act | excluded, then included | — |
| 5 | reads ward and district forums | Deliberation | read | K-4 | per read | allowed (ward ⊂ district) | deny |
| 6 | writes in force, not suspended | Deliberation | post | K-4, K-7, K-5 | per post | allowed if the forum is open | deny |
| 7 | suspended | Membership | Suspend | K-5, K-2 | effective date | reads; **no posting, no newsletters** (D-161 default) | — |
| 8 | restored | Membership | Restore | K-5 | effective date | rights return | — |
| 9 | expires or renews | Membership | (lapse derived) / Renew | K-5 | term | lapsed: reads, no posting or newsletters; renewal keeps the gap | — |
| 10 | resigns now | Membership | `record-resignation` (`ownAct`) | K-8, K-5 | effective = declaration | belonging stops counting; rights end | — |
| 11 | future-dated resignation, withdrawn | Membership (declaration) | schedule; withdraw | K-5 | before the date | **no ledger fact; rights unchanged throughout** | withdrawal after the date → refused |
| 12 | excluded by a competent organ | Governance (Resolution); Membership (`End`) | push (K-6) | K-6 | the part's effective date | ended; challenge changes nothing (D-71) | no competent organ → `Undetermined`, nothing ends |
| 13 | death recorded | Membership | `record-death` | K-5 | date of death (backdated) | ended | no recorder → waits |
| 14 | moves to another unit | Organisation | transfer (`place-member`) | K-5 | same date, one write | new belonging; membership identity unchanged | — |
| 15 | official unit renamed or divided (later release) | Reference Geography | `RevisionPublished` | K-9 | positions | `CoverageReviewNeeded`; **nobody moved** | — |
| 17 | **scheduled resignation, execution delayed** | Membership | `closedThrough` step | K-1 | the date | **no answer at τ ≥ the date until the End is recorded** (D-1) | execution fails → `BOUNDARY_NOT_CLOSED` → consumers refuse |
| 18 | **scheduled resignation vs concurrent withdrawal** | Membership | execute / withdraw | — | around the date | **exactly one succeeds** (compare-and-set) | — |
| 19 | **correction, void, annul** | Membership | Correct / Void (pull); Annul (push) | K-5, K-6 | backdated within invariants | per D-206; a Void is reported | no recorder → `Undetermined` |
| 20 | **newsletter: partial provider failure, retry** | Communication | delivery | — | after the snapshot | only non-accepted records retried; no duplicate | — |
| 21 | **forum read by an applicant, worker or guest** | Deliberation | read | K-4 | per read | allowed only where placed (`place-person`; applicants as Guest) | not placed → nothing |
| 22 | **moderator designated, then removed** | Governance; Deliberation | `moderate(forum)` | K-5 | tenure dates | open while the tenure is valid; read-only after it ends | — |
| 16 | Geography unavailable | — | — | — | — | **release 1 unaffected**; later: Organisation refuses beyond its complete copy | — |

## 14. Proof obligations (all NOT EXECUTED)

| # | Property |
|---|---|
| **A1–A3** | C1 / C2 ledger-clock proofs (spec §11 K-2): **build gate** |
| MI-1 | **D-205 guard:** an End with effective date after its decision date is accepted **only** when executed from a scheduled resignation on its date; refused for every other fact; no fact takes effect after its recording |
| MI-2 | scheduled resignation: execute vs withdraw, both orders, under the existing append order; never two Ends; withdrawal after the date refused |
| MI-3 | a scheduled declaration is superseded when the period ends earlier |
| MI-4 | C1 / C2 show no planned change before execution; a backdated End changes coverage only as known from its recording |
| MI-5 | Organisation: a belonging stops counting after the coverage end; history kept; transfer is one write |
| MI-6 | newsletter: one τ; the participation rule, else the labelled D-161 default; opt-outs; never partial; no login address |
| MI-7 | forum: read by unit containment; post needs the rule and an open forum; deny when any source is unavailable |
| MI-8 | no consumer converts a refusal into "not a member" |
| MI-10 | **D-1:** no C2 answer at τ ≥ an effective date before the due End is recorded; a failed execution holds `closedThrough`; withdrawal vs execution, one wins |
| MI-11 | **D-5:** one send per issue; no duplicate acceptance; late opt-out excluded at delivery (incl. races); reading never recorded; **crash after provider acceptance → no resend** |
| MI-12 | **D-8:** admission → placement exactly once; root default until applied |
| MI-13 | **ledger-clock amendment (if accepted):** no timestamp adjusted; refusal and retry on regression |
| MI-14 | **D-207 and D-153:** an applicant without a Guest placement reads nothing; **workers and guests read where placed and cannot post under the default; with an organisation's written rule (D-168) that allows them to post, they can**; non-member belongings count by their own validity |
| MI-9 | architecture tests: Communication and Deliberation do not import Membership; no cross-context table access |

## 15. Open business decisions, legal questions and gates

| Item | Class |
|---|---|
| ~~authority for Annul / Void / Correct~~ **settled by D-206**; act kinds in Governance rev 4 §2.5 | ratification at acceptance |
| applicants' forum access | **settled by D-207** |
| Membership ledger clock vs the D-73 contract (PO-8) | [EXT] gate; **amendment proposed separately**, needs ARB acceptance |
| Reference Geography PO-1 … PO-8 and the Identity contract (platform-level person reference not provided by I-TDD-02) | [EXT] later release |
| Organisation model fingerprint drift (D-156) | [EXT] next acceptance act |
| newsletter legal basis; resignation effective time; retention | [LQ] |
| **mail provider supports the idempotency key and keeps deduplication over the retry period** (IR-GM-01 C-03) | [EXT] provider selection |
| **monitoring for stuck scheduled executions and clock anomalies** (IR-GM-01 C-04) | [EXT] operations |
| Governance completion rev 3 acceptance (D-203 `ownAct` ratification) | Principal, after review |
| **no new Principal decision is required by this design** | — |

**Author's statement:**
- **Proposed; for independent review; not accepted.**
- **No ratified invariant is changed.** D-205 adds one narrowly decided guard exception.
- **No implementation authorised.**

---

**Status note (2026-11-12, D-210):**
- **M-TDD-01 revision 4 is ACCEPTED WITH CONDITIONS** (decision record §11.109).
- **Accepted text:** this file at SHA-256 `b4aba1687e3cfcbc3171bd731ef1248b01ce2b9d25400431fdb80659b790c137`, as reviewed in IR-GM-01-R1. **This note is the only later change.**
- **Dependencies are now accepted:** Governance completion (D-208) and the ledger-clock amendment (D-209).
- **Build gates:** MI-1 … MI-14 and A1 … A3 (not executed).
- **Before launch:** newsletter legal basis, resignation effective time, retention [LQ]; mail-provider idempotency; monitoring [EXT].
- **No implementation authorised;** that needs an EP-01-approved implementation plan.

---

**Status note (2026-11-13, append-only; approved by the Principal with the reconciliation `2026-11-12-integrated-tactical-baseline-reconciliation.md`):**
- **§5.5 and the open-items rows on the ledger clock are superseded by D-209:** one database clock (`clock_timestamp()`), refusal and retry on regression, no timestamp adjusted. **Implemented and accepted in slice 1** (PBDIGIT-343). The formula `max(appClock, last + 1 µs, closedThrough + 1 µs)` in §5.5 is historical.
- **The open-items rows for D-208 and D-209 are closed:** both were accepted on 2026-11-12. The row "no new Principal decision is required" is superseded; **D-223 was required and is decided.**
- **D-223 (late execution of a scheduled resignation):** an `End(resignation)` from a scheduled declaration may be recorded **on or after** its effective date, never before. A later recording is backdating under D-5(b): `decidedAt` = the declaration date, `effectiveAt` = the scheduled date, `recordedAt` = the actual recording time.
  - **MI-1 is read with "on or after its date".**
  - **Added proof obligations (slice 5, test-first):**
    - **MI-15a** normal execution on the date;
    - **MI-15b** late execution: recorded later, effective on the scheduled date, `decidedAt` unchanged;
    - **MI-15c** execution before the effective date: refused;
    - **MI-15d** withdrawal before the date wins; after the date has started, refused;
    - **MI-15e** knowledge boundary: no C2 answer at τ ≥ the effective date until the End is recorded (with MI-10);
    - **MI-15f** the exception applies to no other fact.
- **Platform mechanism:** D-222.
