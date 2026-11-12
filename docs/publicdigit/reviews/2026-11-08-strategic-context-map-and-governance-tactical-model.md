# Strategic context map and Governance tactical model (organisation and governance domain)

**Status:** **PROPOSED. Working baseline, not ratified.** It records the Principal's working decisions D-2 to D-12 (discovery record §8.1) and the analysis behind them. It changes no ratified or frozen record: no ADR, BDR, Round 50 document, Context Map or Membership architecture is edited. The ARB acts it names are still to be done. It authorises no implementation.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/`.
**Baseline:** branch `election-flow-updated`, HEAD `200b37d32`.
**Evidence:** `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (V-1 to V-48; §6 wording governs).
**Labels:** [F] fact · [PK] Principal's practical knowledge · [D] working decision · [I] inference · [P] proposal · [OQ] open question.

---

## 1. Contexts

| Context | Purpose | Facts it owns | Excludes | Status |
|---|---|---|---|---|
| **Governance** (includes BDR-03's "Appointment (Authority)") | Who may decide what; who holds which position; what was decided | Rules (versioned), including any candidacy criteria an organisation adopts; competence and delegation; decisions (basis, organ, decision date, effective date); organs and conventions; positions with powers; position tenures; history of places and offices | Structure, belonging (including staff belonging), membership, voting eligibility and election-specific eligibility determinations, access, election-committee seats | [D] D-4, D-5 |
| **Organisation** | How the organisation is divided, and who belongs where | Registry; levels, units, wings (forward-only); unit belonging (person, unit or wing, capacity, basis, period); job titles of staff without powers | Organs, positions with powers, membership, authority | [D] D-3 |
| **Membership** | Who is a member, of which type, since when | Periods; types General and Active; membership tenure predicates | Units, positions, entitlements | Ratified, frozen |
| **Election** | Who may vote or stand, and with what result | Election lifecycle, voting entitlement, eligibility, results; `ElectionCommittee` and its seats; applying candidacy criteria [P] | Rules, position tenures, membership | Ratified / frozen; candidacy [P] |
| **Geography** | What the official administrative areas are | Official units and revisions | The organisation's hierarchy | Reference-data capability; bounded-context status [OQ] |
| Identity, Platform / tenancy, Finance, Authorization | Unchanged | Unchanged | Unchanged | Unchanged |

Outside this map, unchanged: Voting, Evidence, Contestation, Adjudication, Audit.

[I] An organisation unit may refer to an official unit at a given revision. Official boundary changes do not change the organisation's unit automatically (V-22, V-25).

## 2. Relationships

| # | From → to | What flows | Contract |
|---|---|---|---|
| R-1 | Governance → Organisation | Structural decisions and reassignment decisions, with decision reference and effective date. Where an organisation's rules require a decision to engage staff, that decision too; Organisation records the resulting **staff belonging**, which it owns. Which body engages staff is [OQ] (V-34 says only "formally appointed") | Missing |
| R-2 | Organisation → Governance | Structure and belonging, as at any date | Missing |
| R-3 | Membership → Organisation | *Membership ended* (membership, person, effective date); ends only belongings whose capacity requires membership | Missing; B-4 [OQ] |
| R-4 | Membership → Election | Status, type, suspension, membership tenure predicates | Exists (ratified §8) |
| R-5 | Organisation → Election | Member belonging as at the voter-list cut-off (direct conventions) | Missing |
| R-6a | Governance → Election | Candidacy criteria in force (V-41). Governance holds the criteria as rules; Election applies them in a particular election. Who owns candidate eligibility itself is [P], not settled | Missing; proposed contract, pending validation |
| R-6b | Governance → Election | Position tenure history (position, kind, organ, the organ's level, period) | Missing |
| R-6c | Governance → Election | Who is competent to appoint election-committee seats, and the appointment decisions; Election's adapter for `OrganisationalAppointmentAuthority` calls `fillSeat` | Missing; first to build |
| R-7 | Election → Governance | Results; Governance opens tenures from the start of the working period (V-13) | Missing |
| R-8 | Governance → Election | Holders of delegate positions (voters of higher conventions) | Missing |
| R-9 | Governance → Membership | Authority evidence for membership acts | Exists (§8 AuthorityCheck) |
| R-10 | Geography → Organisation | Official units at a revision | Open (XD-5) |
| R-11 | Governance → Authorization | `MandateGranted` / `MandateRevoked`, derived from tenures (§5) | Frozen (Round 50); producer now Governance |
| R-12, R-13 | Platform, Identity → … | Access roles; `PersonRef` | Exist |
| R-14 | Membership → Governance | Membership status and type, for checking appointees | [I] inferred; contract not validated |

**Governance ⇄ Organisation (R-1 / R-2).** Different facts in each direction; authority runs one way (Governance decides, Organisation applies). No synchronous call and no shared transaction are needed [P]. Conditions [P]:
- **C-1** A decision is valid if the deciding organ was competent **on the decision date**, under the rules and structure in force that day. Separately, its content must be **applicable on its effective date** (for example, the unit it acts on still exists); if it is not, C-4 applies. Decision date and effective date are both recorded. [D] D-12.
- **C-2** Organisation applies a change from the decision's effective date, not from when it processes it.
- **C-3** A decision that depends on an earlier, not-yet-applied one refers to it.
- **C-4** Organisation never refuses or skips a valid decision; if it cannot be applied, it records "inapplicable" and only a new decision follows (V-25). Being inapplicable does not make the original decision invalid; it means the decision could not be carried out as intended.
- **C-5** A ratified structural act is applied from the act date, with the ratification as its basis.

## 3. Subdomains (all [P])

Core: Governance; Election and Voting. Supporting: Organisation, Membership. Generic: Geography, Identity, Platform. Finance: not classified [OQ].

## 4. Time and ratification [D] D-6, D-8, D-9

- Ordinary decisions take effect no earlier than the date they are made.
- A ratification is a new decision approving named subjects: an existing decision, or an individual act recorded inside the ratification (B-light: who, what, when; optionally the claimed position, evidence, or a reference to another context's fact).
- RA-1 subjects named explicitly; dependent acts only if named · RA-2 ratification date after act date, both kept · RA-3 competence to ratify under the rules in force at the ratification date [P] · RA-4 effect relates back to the act date, for named subjects only · RA-5 the ratified decision or act is never changed · RA-6a no position, tenure or mandate created by itself · RA-6b a ratification naming an appointment may confirm its tenure from the act date, only if the rules allow and the scope says so · RA-6c a confirmed tenure is recorded with both the appointment and the ratification as basis · RA-6d ratifying individual acts never creates a tenure · RA-7 a refusal is also a decision [P].
- If another holder held the position in the confirmed period, the ratification is recorded as "not applicable" until a decision resolves it [P].

## 5. Governance tactical model [D] D-10

**Status of this model:** accepted by the Principal as the **working baseline**. It is not ratified, and it does not authorise implementation. Its qualifications (§8) stay in force.

| Aggregate | Protects | Candidate invariants |
|---|---|---|
| **RuleSet** (per organisation) | Which rule was in force when | A version exists only through a rule-making decision (resolution); the resolution prevails over the rulebook; exceptions in the rule log; never changed afterwards; "as at" gives one answer (V-17–V-19) |
| **Decision** | That a decision was made, and on what basis | Immutable; kinds: individual act, rule-making, correction, ratification, refusal; deciding organ competent on the decision date and content applicable on the effective date (C-1); effective date ≥ decision date except ratification; reference to the rule version used. Reuse `GovernanceDecision` after checking `Legitimacy` and `DecisionTrace` |
| **Organ** | Identity and life of an organ | Set up by a decision; one link to a unit or wing (`JurisdictionAssignment`); at most one active per unit and kind; ends when its unit ends or is divided, or by dissolution decision where the rules allow (V-3, V-4, V-48) |
| **Position** (with tenure history) | Who held this position, when, on what basis | Attached to an organ or a unit; kinds: office, member's place, acting post, staff post with powers, delegate's place; one holder at a time; powers versioned; every tenure has a basis (election result, replacement, appointment, ratified appointment); outgoing holder continues until the successor starts (V-12); past start only under RA-6b; tenures ended, never deleted (V-42–V-47) |
| **AuthorityAssignment** | Competence delegated from one organ to another | INV-A01–A05 as implemented |

**Not aggregates:** committee composition (a query over positions); mandate (derived, published as integration events); the rulebook (a summary).

**Policies and services:** competence check (rules as at a date, delegations, organ, structure via R-2) · compatibility policy for holding several positions (V-15) · result policy (election result → tenure from the start of the working period) · ending process (decision → organ → positions → tenures).

**Test requirement for the ending process** (decision → Organ → Position → position tenure history):
1. The decision's effective date is carried through every step: the Organ, each Position and each tenure record it.
2. Processing time never replaces the effective date.
3. Every processing step is safe to repeat.
4. Read models (composition, authority) answer "as at" queries consistently, including while the ending process is incomplete. A partly processed dissolution must not show a committee as dissolved before its effective date, nor as active after that date merely because processing is delayed.

**Mandate events (R-11), reading of the frozen contract.** `mandateId` = tenure × powers version · `holderId` = holder's `PersonRef` · `scope` = the position's powers · `termRef` = the tenure · `grantedAt` = effective start · `revokedAt` / `reason` = effective end and why (successor, resignation, removal, organ ended, change of powers, correction). A change of powers is Revoked plus Granted with a new `mandateId`. No event for ratifications of individual acts.

**Read models:** composition and "fully constituted"; rulebook; tenure history (R-6b); appointing competence (R-6c).

## 6. Where the code differs ([F]; for later slices, no action)

Governance `Committee` member→role map and deleting `removeMember` → Position with tenure history · `EloquentCommitteeRepository` rebuilds an empty aggregate · wings modelled as committee types → Organisation structure · committee data in three stores plus the legacy Membership committee write model (DR-07a) · `CommitteeAssociation` copies membership status · `GeoLevelType` is a fixed enum.

## 7. ARB acts still to be done

ADR + BDR v1.x addition for D-4. BDR-03 v1.0 confirmed "Appointment (Authority)" as a **separate** bounded context; that decision stays in the register's history unchanged. D-4 is a genuine consolidation of that context into Governance, recorded by a versioned ADR, a BDR v1.x addition and a Boundary Evolution History row; the v1.0 table is not edited · ADR superseding Round 50-01 `AGG — Mandate` with a note, original text kept (D-7) · DR-07b act for C8 (D-3) · O-14 vocabulary: unit belonging, capacity, position, position tenure (Membership keeps "tenure predicates"), mandate as derived, staff engagement · Context Map update under change control.

## 8. Open items

As in the discovery record §8.6.

## Traceability

Conversation: Architecture Session B, 8 November 2026 (one question per turn). Decisions: discovery record §8.1. Frozen sources read: `docs/architecture/design/Round49-06_Boundary_Decision_Register.md`, `Round49-04_Behavioral_Evidence_Dossier.md`, `Round50-01_Aggregate_Discovery.md`, `Round50-02_Aggregate_Review.md`, `Round50-05_Event_Catalogue.md`. Also: ARB decision pack `docs/publicdigit/reviews/2026-11-02-organisation-governance-arb-decision-pack.md`; DR-07 paper; context map; ratified Membership act and canonical artefact.

---

## 9. Organisation tactical model [D] D-22

**Status:** working baseline accepted by the Principal; not ratified; authorises no implementation. Decisions D-13 to D-22 (discovery record §9.1).

| Aggregate | Identity | Protects | Concurrency |
|---|---|---|---|
| **Organisation** (registry) | OrganisationId | Identity, name and legal details, top unit; exists before its structure | Own version |
| **Unit** | UnitId | Active periods with ending kinds (dissolved, merged into s, became new unit s, divided); dated parent records anchored to the parent's active period; successor links; continuation declarations (D-14); level; tree (geographic or wing) | Own version, plus the tree version for structural change |
| **PersonBelonging** (per person and organisation) | PersonRef + OrganisationId | Belongings with capacity, unit, basis act, period, anchor. Exactly one geographic member belonging (V-24); other limits per capacity from the organisation's rules (V-53, V-54); never carries authority; no suspended state | Own version |

**Structure rules [P]:** S-1 one active parent on any date (the top unit has none) · S-2 no cycles · S-3 levels fit, except recorded exceptions (V-17) and the temporary fallback, which may skip levels (V-52, D-19) · S-4 no removing a level in use · S-5 each wing is its own tree with a structure mode (V-21).

**Identity outcomes:** move keeps the unit (V-49); renaming and boundary adjustment keep it [P]; division ends it and creates new units [I]; merger and bringing back are stated by the decision (V-50, V-51).

**R-15 [P]:** Organisation keeps an immutable copy of each published Governance rule version it uses (RuleSet identity, version identity, resolution reference, effective from, content hash, relevant provisions: recognised capacities, which require membership, limits per capacity, wing joining, wing structure mode). The version in force on a belonging's start date applies and is referenced by the belonging. A rule change does not end existing belongings. No run-time call to Governance.

## 10. Working out "as at" a date [D] D-13 to D-19, D-27

**Recorded:** units with active periods and ending kinds; recorded parents and recorded belongings, each anchored to the active period (for belongings: the membership coverage interval, §12) in which it started; continuation declarations; successor links. Explicit allocations and reassignments are new records with their decision as basis.

**`resolve(x, D, anchor)` [P]:**
1. If x is active on D in the anchored period, or in a later period declared to continue this record, the answer is x.
2. Otherwise, if the anchored period ended with a single successor s, resolve s on D, anchored to s's period at that time (D-15).
3. Otherwise (dissolved or divided, no newer record), resolve x's parent as at the day before x ended (fallback; D-17, D-18, D-19).

The latest record valid on D wins. Effective unit, effective parent and higher-level belonging follow. Every step goes to a successor period starting no earlier or to an ancestor in a finite tree, so resolution terminates [I].

**Tests (all resolved from accepted rules):** move · dissolution with no successor · reassignment after fallback · member's later assignment · merger into a new unit · absorption · successor moves · successor dissolved · division with allocation · division omitting a direct member · division omitting a child unit · bringing back with no returns declared · bringing back with returns declared · reassignment before bringing back · several active periods · P1/P2 annulment after re-admission (D-27).

Read models walking the tree as at a date are rebuildable projections labelled with the tree version; never the source of truth.

## 11. Applying a structural decision (option C) [D] D-21, D-22

- A Highest Committee resolution's structural content becomes one or more **change sets**, one effective date each.
- `ApplyStructuralChangeSet` (decision reference, rule version, effective date, operations, idempotency key = decision + change set number), all or nothing in one transaction:
  1. already applied → return the earlier result;
  2. basis check: a recorded Governance structural decision exists (competence is not rechecked; D-12);
  3. load only affected units, their ancestors, and changes already recorded for later dates that touch them;
  4. check S-1 to S-5 **for every date from the effective date onwards** (for a ratified act, from the act date);
  5. write new records only;
  6. advance the tree version; if another change set got in first, start again from step 3.
- On failure: nothing written; an "inapplicable" fact is recorded and sent to Governance (C-4); the decision is not thereby invalid (D-12).
- A later decision may pre-empt an earlier one not yet in effect; both stay on record [I].
- Events: `UnitCreated`, `UnitMoved`, `UnitEnded` (kind), `SuccessorDeclared`, `UnitResumed`, `LevelDefined`; integration `StructuralChangeSetApplied` / `StructuralChangeSetInapplicable`; `BelongingChanged`.

## 12. Cross-context contracts

### 12.1 R-6c Governance ⇄ Election [D] D-23

Governance owns the competence to appoint election officials and fill election committee seats (the organ named by the organisation's rules) and the appointment decision. Election owns `ElectionCommittee`, officer roles, seats, vacancies and seat history, and checks independence from candidates (EM-GOV-029).

| Direction | Exchange |
|---|---|
| Election → Governance | `AppointmentsAwaited` (election, roles and seats needed) |
| Election → Governance | `CommitteeSeatVacated` (seat, ground under EM-GOV-064) |
| Governance → Election | `ElectionAppointmentDecided` (decision ID, organisation, election, role or seat, person, effective from, decision date, rule version) → Election's adapter for `OrganisationalAppointmentAuthority` issues `RecordCommitteeConstitutionCommand` / `FillCommitteeSeatCommand` |
| Election → Governance | `ElectionAppointmentApplied` / `ElectionAppointmentRejected` (reason); a rejection is "inapplicable" in Governance, never an invalid decision |

Keyed by decision ID. A Governance "removal" decision is rejected by Election unless it records an EM-GOV-064 ground. Open: a seat filled on an appointment later found invalid and never ratified [OQ].

### 12.2 R-3 Membership → Organisation [P], [D] D-27

`MembershipCoverageResolved(organisationId, personRef, membershipId, seq, coverage, schemaVersion)`, published per Membership commit whenever coverage changes. `seq` is the per-Membership total order (ratified §2); `coverage` is the contiguous lifetime intervals as now known (INV-COV). Organisation keeps the highest `seq` per membership; lower = stale, equal = repeat, equal with different content = integrity error; a snapshot query per membership returns `(seq, coverage)`.

A member belonging is anchored to the coverage interval in which it started. Effective on D ⇔ belonging valid on D ∧ D within that interval ∧ capacity requires membership under the recorded rule version ∧ latest valid record. An annulled End merges intervals (the belonging revives); a real re-admission starts a new interval (no revival, V-36). Organisation never interprets Admit, End, Annul, Void or Correct, and never depends on Membership's period attribution. `typeSegments` omitted until an Organisation rule needs membership type.

Supersedes the earlier `MembershipPeriodResolved` proposal and anchoring to `PeriodId`.

### 12.3 R-14 Membership → Governance [P], [D] D-24, D-26

Needed only for appointments outside elections (V-29, V-42, V-43). Query `(organisationId, personRef, asOf, predicates, knownAtSeq optional)` over the ratified predicates (`IsMember`, `CurrentType`, `Suspended`, `CumulativeMembershipDuration` / `Continuous…`). Response: QUALIFIED, NOT_QUALIFIED or INDETERMINATE, values, periodId, `seq` used, contract version. INDETERMINATE is never read as either answer. Governance stores the response in the decision's basis next to the RuleSet version. No general event subscription; a future notice about a holder's membership would change nothing (D-24). "As known then" is reproducible from `seq` (ratified INV-14).

### 12.4 Change Request to Membership §8 (scope; **not approved**)

Under D-25 the Principal approves it. Scope: (1) Organisation as consumer of `MembershipCoverageResolved` and the per-membership snapshot query; (2) Governance as consumer of the ratified predicates through an asOf / knownAt query; (3) Election's contract unchanged; (4) no change to the Membership aggregate, correction algebra or policy switches; no shared storage or transaction.

## 13. ARB acts still to be done (additions to §7)

- Change Request to Membership §8 (§12.4), approval by the Principal (D-25).
- Update of Election's open-question register for EM-OPEN-066 and EM-OPEN-049 (answered in form by D-23); not done here.
- §7's acts stand: ADR + BDR v1.x for D-4; ADR superseding Round 50-01 `AGG — Mandate`; DR-07b act for C8; O-14 vocabulary (add: unit belonging, capacity, coverage interval, change set, staff engagement; CQ-1 "Election Committee"); Context Map update under change control.

## 14. Superseded statements in this document (not edited; superseded here)

- §2, R-3 row ("ends only belongings whose capacity requires membership"): mechanism superseded by §12.2; the business rule is unchanged.
- §2, R-14 row ("[I] inferred; contract not validated"): superseded by §12.3.
- §2, R-6c row ("Missing; first to build"): superseded by §12.1.

**Traceability (§9–§14):** Architecture Session B, 8 November 2026, continued. Decisions: discovery record §9.1. Sources read: ratified Membership architecture (§2–§8); `docs/publicdigit/reviews/2026-08-16-EM-OPEN-049-authority-over-the-chief.md`; `docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`; `app/Contexts/Election/Domain/OperatingCore/Committee/ElectionCommittee.php`; `app/Contexts/Election/Domain/OperatingCore/Port/OrganisationalAppointmentAuthority.php`; `app/Contexts/Membership/Domain/Membership/Events/MembershipTerminated.php`; `docs/publicdigit/architecture/reviews/2026-11-07-oda-01-v1.3-correction-plan-approval-package.md:248`.

---

## 15. Consolidated context map (working baseline)

**Status:** This section consolidates the current working context map and the working decisions relevant to it (discovery record §8.1, §9.1, §10.1). It does not supersede, amend or unfreeze separately ratified architecture, including the Membership architecture. No implementation is authorised by it. It brings §1, §2 and §12 up to date; where they differ, this section governs (see §19).

| Context | Owns | Excludes | Status |
|---|---|---|---|
| **Governance** (incl. BDR-03's Appointment (Authority)) | Rules (versioned) incl. convention rules, participation routes and their rights, candidacy criteria, delegate rules, recording rules; competence and delegation; decisions (decision date, effective date, basis, evidence); organs incl. conventions; positions incl. delegate places; position tenures | Structure, belonging, membership, voting entitlement, candidacy decisions, election committee seats, access | [D] D-4, D-5, D-10 |
| **Organisation** | Registry; units and the tree over time; unit belonging | Organs, positions, membership, authority, official geography | [D] D-3, D-22 |
| **Membership** | Membership periods, types, facts, correction; predicates | Units, positions, entitlements | Ratified, frozen |
| **Election** | Election lifecycle; electorate and voting entitlement; candidacy and its decisions, **applying** candidacy criteria that Governance's rules define, with evidence from Membership (R-4) and Governance (R-6b); `ElectionCommittee`, officer roles, seats; results; snapshots | The criteria themselves, belonging, membership, tenures, authority | Ratified boundaries; Election-side changes pending (§17) |
| **Reference Geography** (platform level, generic) | Official units, revisions, approval of submitted missing units | Any organisation's structure | [D] D-31 |
| **Authorization** (service, BDR-07) | Nothing; read models of its inputs (§16, "R-11 inputs") | Any business truth | Frozen BDR-07; V-59 |
| Identity, Platform / tenancy, Finance | Unchanged | Unchanged | Unchanged |

Outside this map, unchanged: Voting, Evidence, Contestation, Adjudication, Audit.

**Patterns [P]:** a context deriving its own facts from another's keeps a rebuildable copy of published complete state (R-3, R-10); a context deciding on another's facts queries "as at" a date and stores the answer and version marker in the decision's basis (R-2, R-6b, R-14); Election takes everything through a snapshot at the cut-off, with each source's marker, and the snapshot never changes.

## 16. Contracts (working designs)

| # | Direction | Form | Content | Marker | Source |
|---|---|---|---|---|---|
| R-1 | Governance → Organisation | Events `StructuralChangeSetsDecided`, `BelongingAllocationsDecided` | decisionId, organisation, decision date, rule version; change sets (number, effective date, operations, ratification flag with act date, indivisible-group membership); allocations (person, unit, capacity, effective date, dependent change sets) | decisionId + set / allocation number | [D] D-29, D-30 |
| R-1 reply | Organisation → Governance | Events `StructuralChangeSetApplied` / `…Inapplicable`, `BelongingAllocationApplied` / `…Inapplicable` | Outcome per change set or allocation, with reasons; an indivisible group as one outcome; an inapplicable dependency makes dependants inapplicable | same | [D] D-29, D-30 |
| R-2 | Organisation → Governance | Queries `StructureAsAt(organisation, date)`, `BelongingAsAt(unit, date, capacity)` | Units, effective parents, levels; belongings | tree version (+ membership `seq`) recorded in the decision's basis | [D] D-29 |
| R-3 | Membership → Organisation | Event `MembershipCoverageResolved` + snapshot | §12.2 | `seq` | **Proposed [P]**, D-27; depends on the Membership §8 Change Request (not approved) |
| R-4 | Membership → Election | Ratified predicates and events | — | (MembershipId, asOf) | Ratified §8 |
| R-5 | Organisation → Election | Query `EffectiveMembersAsAt(unit, cut-off)` (on `BelongingAsAt`) | People whose effective member belonging is in the unit or below | tree version + `seq` | [D] D-32 |
| R-6a | Governance → Election | Query `ElectionRulesAsAt(organisation, convention, date)` | Rule version; direct or delegate electorate (V-8); participation routes and their rights (V-55); voter criteria; candidacy criteria; positions and delegate places to fill (posts carry `positionRef`) | rule version | [D] D-32 |
| R-6b | Governance → Election | Query `PositionTenuresOf(person, asOf, filter)` | Tenures with period, position kind, organ, and the organ's level on those dates (via R-2) | decision-log position | [D] D-32 |
| R-6c | Governance ⇄ Election | §12.1 | — | decisionId | [D] D-23 |
| R-7 | Election → Governance | Event `ElectionResultDeclared` | electionId, convention; per post: `positionRef`, elected people, optional ranked substitutes; publication date; working-period start; result version. No finality field; an overturn is a later separate fact | result version | [D] D-32, D-28 |
| R-8 | Governance → Election | Queries `DelegatesAsAt(convention, cut-off)`, `PositionHoldersAsAt(positions, cut-off)` (ex officio) | Holders with tenure references | decision-log position | [D] D-32 |
| R-10 | Reference Geography → Organisation (and Membership where rules use residence) | Event `OfficialUnitRevised`; query `OfficialUnitsAsAt(country, date, knownAtRevision?)` | See "R-10 identity and time" below | revision sequence | **Proposed [P]**; D-31 settles the boundary only |
| R-11 | Platform, Governance, Election → Authorization | See "R-11 inputs" below | Authorization's answer: allow or deny, rule applied, sources and markers, evaluation time; missing or uncertain source → deny (fail closed, INV-ORG-SCOPE-1) | sources' markers | **Proposed [P]**; BDR-07, V-59 |
| R-12, R-13 | Platform, Identity → … | Access roles; `PersonRef` | — | — | Existing |
| R-14 | Membership → Governance | §12.3 | — | `seq` | **Proposed [P]**, D-24, D-26; depends on the Membership §8 Change Request (not approved) |
| R-15 | Governance → Organisation | §9 | — | rule version | **Proposed [P]** |

**R-10 identity and time [P], open for tactical validation:**

| Concept | Meaning |
|---|---|
| Official identity | The stable identity of an official unit. Candidate: the landlord identifier, **if its stability is guaranteed** [OQ] |
| Official code | The government-assigned code (e.g. `NP-DIST-01`). An attribute that may change; **not** the identity |
| Revision | One published state of the unit, with a revision sequence and an effective date |
| Tenant row id | Local storage only; never used between contexts |

A correction recorded later may describe an earlier effective date. So `OfficialUnitsAsAt` must separate the **effective date** asked about from **what was known**, given by the revision sequence (`knownAtRevision`), so that a past answer can be reproduced. "Keep only the highest revision" is enough for a current copy, not for history. Open [OQ]: the scope and ordering of `knownAtRevision` (per unit, per country, or platform-wide) must be defined in tactical design; no global comparability across countries or units is assumed.

**R-11 inputs [P]:**

| Input | Owner | Purpose |
|---|---|---|
| Platform access | Platform (R-12) | May the user perform this software operation? |
| Mandate from a position | Governance (`MandateGranted` / `MandateRevoked`) | Does the actor hold the relevant authority in the organisation? |
| Election officer role | Election (`ElectionOfficerRoleAssigned` / `…Ended`, new) | Does the actor hold the relevant Election role? |
| Applicable requirements for the action | The context that owns the procedure: Governance for organisational procedure (e.g. recording an organ's decision, V-59); Election for election operations (e.g. opening an election, publishing a result, voter verification, under the adopted EM-GOV rules); Platform for platform access policy | Which of the three above this action requires |

Three sources of facts about the actor, and requirements owned by whichever context owns the procedure. V-59 is a Governance rule for recording decisions; it is not a general rule for every protected action. Authorization may keep read models of all of them, resolves the applicable requirements, and owns none of them.

**Later corrections:** a later correction to the evidence behind an actor's permission to **record** something does not automatically invalidate a decision already recorded. The decision's validity in the organisation remains governed by Governance competence (D-12), the applicable rules and any relevant adjudication. Permission to record never establishes validity.

**Delegates [P]:** a delegate place is a Governance position representing a lower unit; its tenure starts from the lower convention's result (R-7); how long it lasts, how a vacancy is filled (new election, appointment, or next substitute as a rule-based decision) and what restructuring does follow the organisation's rules (V-56 to V-58). Under a one-convention mandate the mandate is tied to that convention's identity, so a postponed or reconvened sitting is still covered; a cancelled convention is [OQ].

**Election snapshot [P]:** for a direct convention R-5 + R-4 + R-6a; for a delegate convention R-8 (delegates and ex officio holders) + R-4 + R-6a; each with its marker. Upstream changes after the cut-off never alter it; correcting a held election belongs to Adjudication. Open for tactical design [OQ]: how the markers from Organisation, Membership and Governance are obtained together as one consistent snapshot, and what happens when a source is incomplete or unavailable at the cut-off.

**Results and tenure:** Election publishes the convention's result. Governance records the resulting tenure in accordance with the applicable organisation's rules. Where those rules require a separate confirming act, its relationship to the tenure commencement date must follow the rule. D-28 remains applicable: a later successful challenge does not automatically erase the previously established tenure or invalidate actions taken during it.

**Recording a decision [P]:** each Governance decision records who entered it and, where the rules require (V-59), the mandate relied on, using the ratified authority-evidence shape {actor, capacity, ruleRef, policyVersion, result, checkedAt}. Entering a decision never makes it valid (D-12).

## 17. Election-side changes needing a PO/ARB act

Approval authority: the Principal as PO and human ARB Chief ([I] from the PO/ARB adoption acts).

1. Voter sources by unit (R-5) and by delegates and ex officio holders (R-8).
2. Posts linked to Governance positions (R-6a), replacing `user.region` matching.
3. Applying candidacy criteria with evidence from R-4 and R-6b.
4. `ElectionResultDeclared` naming winners and optional substitutes (R-7).
5. `ElectionOfficerRoleAssigned` / `…Ended` for Authorization (R-11).

None is approved.

## 18. Where the code differs (additions to §6; [F]; no action now)

`GovernanceDecision`'s inverted date rule · `GeoAdministrativeUnit`'s fixed `parentId` · direct table reads by `GovernanceGeoUnitQueryService` and `VoterSourceStrategy` · official and organisational units in one table · tenant row ids instead of official identity · `user.region` matching · authorization by platform-role policies with competence unverified.

## 19. Superseded statements in this document (not edited; superseded here)

- §1 (Contexts) and §2 (Relationships): superseded by §15 and §16 where they differ (Geography row; R-1, R-2, R-5, R-6a, R-6b, R-7, R-8, R-10, R-11 rows).
- §11: add indivisible groups of change sets (D-30); the rest stands.

**ARB acts (additions to §13):** the Election-side PO/ARB act (§17); Context Map update now also covering Reference Geography (D-31) and the narrowed Geography row.

**Traceability (§15–§19):** Architecture Session B, 8 November 2026, continued. Decisions: discovery record §10.1. Sources read: `app/Domain/Election/Enum/VoterSourceStrategy.php`; `app/Contexts/Elections/Domain/Events/ResultsPublishedEvent.php`; `app/Contexts/Governance/Domain/GovernanceDecision.php`; `app/Contexts/Geography/` (mirror, sync, candidate services, unit entity, migration); `app/Contexts/Governance/Application/Services/GovernanceGeoUnitQueryService.php`; `app/Policies/`; `docs/publicdigit/reviews/2026-08-16-adoption-act-v3.md`; `docs/architecture/design/Round49-06_Boundary_Decision_Register.md` (BDR-05, BDR-07, BDR-08).

---

## 20. Governance tactical design (working; decisions in discovery record §11)

**Status:** working design accepted in conversation; not ratified; no implementation authorised. Refines §5; where they differ, this section governs.

### 20.1 Decision-making lifecycle

| Concept | Meaning | Invariants |
|---|---|---|
| **Sitting** (aggregate) | A formal meeting of one organ: invitation, agenda items, attendance, record of proceedings, minutes | Consistent agenda, attendance and minutes of one sitting; minutes attached later and approved as a part at a later sitting; corrections are new facts |
| **Submission** (aggregate) | A matter before an organ (D-51): received → admitted / inadmissible → on agenda → exactly one outcome (adopted, amended, rejected, referred, deferred, withdrawn, **lapsed**) | Admissibility as at the submission date (PR-3); one outcome (PR-5); passes to a named successor organ, otherwise lapses ([I] by analogy with D-15) |
| **Resolution** (aggregate) | Recorded once, never changed (GD-1): decider, decision date, method (D-33), procedure evidence, parts | One decider; competence per part; dependencies, including across resolutions (joint organs) |
| **Part** | Type, content, effective date, dependencies, indivisible group, competence evaluation | Status is derived, never stored |
| **GroupApplication** (aggregate) | Application of an indivisible group across contexts: Prepared → Completed → Visible; or Inapplicable / Pre-empted | One outcome; never Completed after Pre-empted; never sets validity or dates |
| **DecisionLog** (aggregate, per organisation) | When PublicDigit learnt of each fact | Positions without gaps (GD-11); record time from the database clock, never decreasing with position (GD-12) |

**Competence evaluation (value object):** kinds *worked out* (structured provision), *asserted* (recorder cites the provision; marked not machine-checked), *gap declared* (D-65), *contradiction* (D-70). A record needs one of them; missing platform data never becomes a constitutional gap. Evaluated per part on the decision date under the rules in force then, as known at recording; never re-run automatically (G-1); a different view only through ratification, determination or a new decision (G-2).

**Failure kinds kept apart (G-5):** competence (Governance) · procedure (evidence, not validated) · applicability (consumer, C-4) · technical authorisation (Authorization; never affects validity). Recording needs recording authority (V-59), and recording is not deciding (G-6).

### 20.2 Organ, Position, Tenure

| Aggregate | Invariant |
|---|---|
| **Organ** | Attached to a set of units: own or joint. At most one active **own** organ per unit and kind. Composition on a date is derived (holders plus ex officio by rule). Election committees are Election's; the tribunal is a Governance organ (R-13) |
| **Position** (tenures and restrictions inside) | One holder at a time (V-46); outgoing holder continues until the successor starts **only for succession and expiry of a term** (V-12); other ends follow D-66, D-37, D-24, D-28 or the organ's end. Co-chairs are two positions; joint exercise is a condition on a power |
| **RuleProvision** | Versions do not overlap; contains **grants of power** (target position or kind, scope, conditions) |
| **AuthorityAssignment** | Delegation; delegator and delegatee may be an organ or a tenure (extends the organ-to-organ meaning; no second authority model) |

**Acting posts:** a separate position linked to the substantive one ("acts for P"), started by decision or a rule trigger. **Restrictions:** suspension, partial restriction (inside the tenure); recusal attaches to a matter, not a tenure.

**Incompatibility guard:** Position accepts tenure changes only with a `TenureChangeClearance` created by the guard service under a per-person lock (`FOR UPDATE`), after re-reading the Positions. Each tenure change references exactly one clearance (person, position, interval; single use). Imports and late corrections record conflicts as marked, never refused or ended automatically. The index holds no facts and is rebuilt from Positions. Bypass is possible only by direct database manipulation (accepted limitation).

### 20.3 Powers and mandates

- **Effective powers of a tenure for an act:** grants whose scope covers the act → declared precedence / "or" / "and"; **no declaration → all applicable conditions (D-69)**; unsatisfiable → unusable plus contradiction diagnostic → minus restrictions and recusals; delegation ∩ the delegator's effective powers; acting post = substantive powers minus exclusions.
- **Organ competence** follows D-65 and D-70; D-69 is never applied to organs, D-70 never to individuals. Separate domain services.
- **Mandates (R-11, D-68):** granted when the basis takes effect (`mandateId` = basis × powers version); on any change of rule, restriction or delegator's powers: revoked and granted again, dated from the effective date; on the end of the basis: revoked, with a cascade over dependent delegations. Appointment (Authority) is the producer, as a capability within Governance (BDR-03 v1.x pending).

## 21. Cross-context consistency (D-40 to D-43)

- **Indivisible groups across contexts:** consumers prepare hidden final records that count in later checks; Governance records Completed when every part is Prepared; contexts show records once they receive Completed. Combined views check the group's status. Only delivery lag of the completion fact remains.
- **Electorate readiness:** readiness queries to Organisation and Governance answer READY with markers or BLOCKED with group identities; "affects the electorate" is computed by comparing the electorate with and without the group's held records.
- **Knowledge boundary at cut-off T:** each source closes its boundary by a write barrier (shared lock for writers, exclusive lock for closing, database clock); Election records T, P_G, the tree version and Membership's T. Membership is queried "as known at T" through `CoverageKnownAt(organisation, memberships, τ)` (Change Request, not approved).
- **Change Request to Membership §8, revised scope:** Organisation consumes `MembershipCoverageResolved` unchanged; the coverage query takes τ; R-14's `knownAtSeq` becomes τ; Election's predicate queries take τ. No change to the aggregate, the correction algebra, policy switches or invariants.

## 22. Election and Voting (D-44 to D-49)

- **Boundary:** Election knows the person and records **"right issued"** (never "has voted"); Voting knows ballots and consumed credentials, never persons. Only totals return after close.
- **Credential:** a blind signature (RSABSSA, RFC 9474) under one published key per election and ballot style; Voting accepts no other key. Issuing time recorded to the day only.
- **Ballots (M3):** one credential, one atomic sealed submission per voter, encrypted under a Voting key; at close, split per race, shuffled, counted; digests H_sealed and H_r published. Key destroyed at close unless escrowed under D-49.
- **Reconciliation:** N_C,s ≤ N_I,s; B_r = Σ N_C,s over styles containing race r; T_r = B_r; duplicates max(0, N_C − P_I) ≤ D ≤ R; rejected attempts consume nothing.
- **Level A proof obligations:** separate storage; random IDs; no cast time; no identity on the cast path; no ballot-related emails, queue entries or files; no receipt that unlocks a ballot; independent security review.
- **Publication (D-48):** breakdowns computed once at publication, every cell ≥ the organisation's minimum, with secondary suppression across tables; national race records from small styles carry no style label.
- **Aggregates:** Election, ElectorateSnapshot, RightIssuance, NetworkIssuanceQuota (Election); BallotBox, SealedSubmission (Voting); Tally is a result, not an aggregate.

## 23. Deliberation (D-50 to D-56)

Forums are scopes: a unit (geographic) or an owner's committee (Governance organ or Election committee). Participation and reading are derived from belonging and tenures. Support (signal) and co-signature (formal, named) are distinct. Rules DL-1 to DL-17: one support per entitled person; rank never decides; no privileges from points (moderators come from positions or rules); rules in force when a support or post is made apply to it permanently; names under "counted only" visible to no role; disclosures only by Adjudication order; widening read access applies only to later posts; no `Decision` aggregate in Deliberation. Hand-over: `ProposalSubmitted`, `SupportThresholdReached` with a support certificate (count, tree version, Merkle root over salted entries).

## 24. Communication (D-57 to D-64)

Capabilities: newsletter (optional, editor designated by the committee in a meeting, official purpose only), formal notice, operational notice, candidate presentation (owned by Election), personal message and official message (sent in a role; threads belong to the office; muted, not blocked), forum notifications. Audiences are resolved by their owners; Communication stores the resolved snapshot with the basis of each inclusion; no "has voted" audiences. Contact details: Identity, within the XD-3 account area (not Membership; no Membership Change Request). Delivery evidence proves an attempt or acceptance, not receipt, awareness or valid convocation. Private messages: D-63.

## 25. Contestation, Adjudication and Evidence (D-67)

- **Adjudication (one context):** AdjudicationProcess (routing, evidence requests, COL-5a/b, deadlines; closes when every remedy has an outcome) and Determination (draft → issued → final; never changed once issued; one per challenge and instance; appeal is a new challenge at a higher instance; D1 automation record per step; issuing organ with its composition on the date). Remedy parts are applied by their owners, stand independently unless declared indivisible (then GroupApplication).
- **Evidence contexts (EPIC-002, accepted):** owners keep their facts; Record-Fixing attests; Integrity protects; Collection assembles only for a case. Streams: resolutions, minutes, snapshots, delivery evidence and determinations custodial; the closing digest self-verifying. `FixRecord(sourceRef, contentHash, actTime)` → receipt with Record-Fixing's own time: proves the content existed no later than then; `actTime` stays the owner's claim. Contemporaneous fixing only where fixing is part of the act (live sitting capture, digest publication). Minutes written later are a different artifact under D2, a violation only where the rules require timeliness. Aggregates: EvidenceBody (references only; content fetched and hash-checked, not stored), FixedRecord, CustodyTrail, PublicCommitment.
- **Audit** is a platform log, never evidence by itself.

## 26. ARB acts pending, superseded statements, code differences

**ARB acts pending:** BDR-03 v1.x (Appointment within Governance; blocks closure) · Membership §8 Change Request (§21; blocks closure; D-25) · X-10 interpretation ("later frozen records govern: Audit is a platform capability, consumes all events, other consumers per allowed sets; Round 35C describes its time") · ADR-UL extending Contestation `TargetType` (governance decision part, moderation decision, newsletter edition, official message) · XD-3 Identity (account, `PersonRef`, contact details) · recording D-67 and D-68 in their governed artifacts (EPIC-002 map annotation; Round 50-05 interpretation note).

**Superseded in this document:** §5 "Decision aggregate" → Resolution with parts (§20.1); §5 "RuleSet aggregate" → RuleProvision with RuleSet read model; Position's "powers versioned" → reference to grants; AuthorityAssignment "organ-to-organ" → organ or tenure; "the outgoing holder continues" → scoped to succession and expiry; the earlier statement that the decision log is the fixing → Record-Fixing attests independently.

**Code differences ([F]; no action now):** VO-1 links F1 to F14 (`PBDIGIT-337`); `GovernanceDecision` backwards date rule, untested; `Legitimacy` stored on the decision; no rule version in `ConstitutionalBasis`; "ICC" hard-coded in `AuthorityPath`; three `GovernanceDecision` models; no Sitting, Submission, Position, Tenure, RuleProvision or decision log; Membership `recordedAt` taken before commit; Audit consumes no events; newsletter audiences query other contexts directly.

**Traceability (§20–§26):** Architecture Session B, 8–9 November 2026. Decisions: discovery record §11. Sources read: `app/Contexts/Governance/Domain/`; `app/Http/Controllers/VoteController.php`; `app/Services/VoterStepTrackingService.php`; `app/Models/BaseVote.php`; `app/Services/ElectionAuditService.php`; `app/Services/NewsletterService.php`; `app/Http/Controllers/NewsletterUnsubscribeController.php`; `app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php`; `app/Contexts/Membership/Domain/Organisational/Member.php`; `app/Contexts/Contestation/Domain/`; `app/Contexts/Adjudication/Domain/`; `docs/implementation/EPIC-002_Canonical_Context_Map.md`; `docs/implementation/EPIC-002_Relationship_Pattern_Selection.md`; `docs/architecture/design/Round50-05_Event_Catalogue.md`; `docs/architecture/design/Round49-06_Boundary_Decision_Register.md`; `docs/publicdigit/reviews/2026-10-08-membership-architecture-final.md`; `docs/publicdigit/reviews/2026-10-08-membership-final-decision-register.md`.

## 27. Effect of a pending challenge (D-71)

A challenge does not change the effect of what it challenges. The challenged decision, moderation decision or result stays effective from filing until determination, unless the organisation's rules provide a suspensive effect for specific cases. The tribunal may suspend it by an **interim measure** (a remedy type in the Determination, applied by the owning context and citing it). An upheld determination ends the effect from the date it sets; acts done before then stand (D-28, G-2). "Effective until challenged" (D-65, D-70) therefore means "effective until a determination or interim measure says otherwise".

## 28. ARB act done: BDR-03 v1.2 (D-72)

The §26 act "BDR-03 v1.x" is done: ADR accepted, register `Round49-06` amended append-only (Boundary Evolution History row; BDR v1.2 delta). Still pending from §26: the Membership §8 Change Request (blocks closure), the ADR on Round 50-01 `AGG — Mandate` / D-7, X-10, ADR-UL `TargetType`, XD-3, recording notes for D-67 and D-68.

## 29. ARB act done: Membership Change Request CR-01 (D-73)

The §26 act "Membership §8 Change Request" is done: CR-01 revision 2 accepted; amendments appended to `2026-10-08-membership-architecture-ratified.md` (after §8) and `2026-10-08-membership-architecture-ratification-act.md` (after §11). §12.3's QUALIFIED/NOT_QUALIFIED response is superseded by C3 (facts only). **Both acts that blocked closure are now done** (D-72, D-73). Still open: the ADR on Round 50-01 `AGG — Mandate` / D-7, X-10, ADR-UL `TargetType`, XD-3, recording notes for D-67 and D-68.

## 30. ARB act done: Mandate aggregate superseded (D-74)

ADR accepted; append-only notes in `Round50-01_Aggregate_Discovery.md` (after `AGG — Mandate`) and `Round50-02_Aggregate_Review.md` (after the merge test). Still open, in order: XD-3a/b/c (Identity: `PersonRef`, Person ≠ Account, contact details), ADR-UL with ADR-PL (`TargetType`, `DeterminationIssued v2`), X-10 (Audit precedence), recording notes for D-67 and D-68.

## 31. Correction: contact details are organisation-local, not Identity's

**Superseded:** §24's "Contact details: Identity, within the XD-3 account area" and finding CF-1 (Identity grows to cover contact details). They were made without reading XD-3b §2a.

**Evidence [E]:** XD-3b §2a (`2026-11-01-identity-xd3b-person-vs-account-decision-record.md`, OQ-3b-1, answered 2026-11-01, PROPOSED): "The central Identity record of a Person is a **bare `PersonId`**. Name, contact details and other descriptive data live in **each organisation's database**. An Account holds only its own credential data (e.g. a login email)." Option A (central name and contact) "is not selected: it conflicts with conditions C-2 and C-4 of XD-3a".

**Corrected design [P]:** contact endpoints belong to the **organisation-local person profile**, owned within the organisation (the affiliation owner under INV-AFF-1, proposed: Organisation). Communication resolves endpoints from that owner; the Account's login email is a credential, not an organisational contact endpoint. Membership still owns no contact details. No Membership Change Request is needed either way.

## 32. ARB act done: XD-3 Identity (D-75)

`PersonRef` everywhere in this document means **the organisation-specific opaque reference** issued by Identity. The Google sign-in email takeover (`app/Http/Controllers/GoogleController.php:44`) is a **security blocker**. Remaining ARB acts: ADR-UL with ADR-PL (`TargetType`, `DeterminationIssued v2`), X-10, INV-AFF-1, recording notes for D-67 and D-68.

## 33. Governance tactical model, round 4 (PROPOSED; consolidates §20 under D-72, D-74, D-75)

**Status:** architect's proposal, not accepted; no implementation authorised.

### 33.1 Aggregates and why each boundary exists

| Aggregate | Owns | Invariant that needs this boundary | Atomic within | Rejected alternative |
|---|---|---|---|---|
| **Position** (tenures, restrictions) | The office and its holders over time | One holder at a time (V-46); outgoing holder continues only on succession or expiry (V-12); restrictions belong to one tenure | One Position | Tenure as its own aggregate: V-46 would span aggregates |
| **AuthorityAssignment** | One delegation: delegator (organ or tenure), delegatee (organ or tenure), delegated powers, period, re-delegation flag | Scope ⊆ delegator's effective powers **at grant**; one active assignment per (delegator, delegatee, power) | One assignment | Delegation inside Position: a delegation outlives neither, but belongs to neither alone |
| **Organ** | Body, attachments (own / joint), lifecycle | One active **own** organ per unit and kind | One Organ | Composition stored in Organ: duplicates tenures |
| **RuleProvision** | One provision's versions and its grants of power | Versions never overlap | One provision | One RuleSet aggregate: every rule change would lock all rules |
| **Resolution** (parts) | What an organ decided, once | Never changed (GD-1); one decider; competence per part | One Resolution | — |
| **Submission**, **Sitting** | Matter before an organ; a meeting | One outcome (PR-5); minutes lifecycle | Each | Inside Resolution: they exist before and without it |
| **GroupApplication** | Indivisible group across contexts | One outcome | One group | — |
| **DecisionLog** (per organisation) | Knowledge positions | GD-11, GD-12 | One log | — |
| *(consistency guard)* **OfficeholdingIndex** | Nothing (derived) | Incompatibility (person-level serialisation + clearance) | With the Position change | An aggregate: it would own facts |
| ~~Mandate~~ | — | — | — | **Superseded (D-74)** |

### 33.2 Authority: where the truth is checked

- **Effective powers** (§20.3, D-69) are worked out by a domain service from Position, AuthorityAssignment and RuleProvision **at the moment of use**. A delegation's validity is therefore always evaluated against its source tenure **as it stands**, so a delegation can never be used after its basis ends, even before any event is delivered.
- **Mandate events** are derived in the **same transaction** as the change that causes them (outbox), so Authorization learns of a revocation as soon as delivery allows.
- **Two checks, one owner of truth:** Authorization gates operations from mandates (fast, possibly lagging by delivery time). **Every authority-sensitive act that Governance records** (a decision part, an appointment, a signature recorded as an act) is **re-checked by Governance** against its own facts when recorded (G-6). The delivery lag can therefore never create a valid Governance act.
- **Organ competence** is a separate service (D-8, D-65, D-70); D-69 never applies to organs.

### 33.3 Decisions, challenges and remedies inside Governance

- A part is applied after recording, in its own transaction, by the aggregate it targets (Position, AuthorityAssignment, Organ, RuleProvision), keyed by part; outcome recorded (applied / inapplicable with reason).
- **A pending challenge changes nothing** (D-71).
- **An interim measure** addressed to Governance becomes a **RestrictionOfPowers** on the tenure (source: the Determination), with Mandate revoked and re-granted for the remaining scope.
- **A final determination's Governance part** is recorded as a **Resolution with method "determination"**, decider = the tribunal (a Governance organ, R-13), referencing the Determination. Governance applies it like any part; Adjudication never writes Governance facts.

### 33.4 Commands and events (proposals)

| Aggregate | Commands | Events (authoritative unless noted) |
|---|---|---|
| Position | CreatePosition, AbolishPosition, StartTenure (needs clearance), EndTenure(reason), RestrictPowers, LiftRestriction, RecordResignationSubmitted, RecordDeath | PositionCreated, PositionAbolished, TenureStarted, TenureEnded, PowersRestricted, RestrictionLifted, ResignationSubmitted, DeathRecorded |
| AuthorityAssignment | GrantDelegation, RevokeDelegation, ExpireDelegation | DelegationGranted, DelegationRevoked, DelegationEnded |
| Organ | EstablishOrgan, DissolveOrgan, AttachUnit, DetachUnit | OrganEstablished, OrganEnded, OrganAttachmentChanged |
| RuleProvision | PublishVersion | RuleVersionPublished |
| Resolution | RecordResolution | ResolutionRecorded(P), PartApplied, PartInapplicable |
| *(derived)* | — | **MandateGranted, MandateRevoked** (Round 50-05; derived, not authoritative) |

Every event carries decision reference, effective date and log position; consumers are idempotent on event id; corrections are new events, never edits.

### 33.5 Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | One person, two positions | Allowed unless an incompatibility rule applies; guard checks overlapping effective periods |
| 2 | Occupied position abolished | D-66: tenure ends on the abolition date unless rules protect; dependent delegations end; mandates revoked |
| 3 | Delegation becomes invalid | RevokeDelegation or expiry → DelegationRevoked → MandateRevoked; use-time check already refuses |
| 4 | Delegator loses the power (rule change or restriction) | Power service reassesses; delegation scope ∩ delegator powers → MandateRevoked + MandateGranted for any remaining scope |
| 5 | Conflicting individual grants | D-69: all applicable conditions; contradiction diagnostic |
| 6 | Conflicting organ competence rules | D-70: effective until challenged; diagnostic |
| 7 | Result challenged, still effective | D-71: tenure and mandates unchanged |
| 8 | Interim measure restricts some powers | RestrictionOfPowers; partial mandate re-grant |
| 9 | Final determination across contexts | Election and Governance parts; Governance part recorded as Resolution (method determination); acts before the effective date stand |
| 10 | Two concurrent incompatible appointments | Person lock; second sees the first; inapplicable |

### 33.6 Open

- **Default delegability of powers when the rules are silent** (Principal question, §33.7 of the conversation).
- Sitting content (quorum, minutes) pending statute evidence (PR-7).
- Legacy `GovernanceDecision`, `Legitimacy`, "ICC" in `AuthorityPath`: code conformance, not model questions.

## 34. Delegability (D-76), added to §33

- **RuleProvision grants** carry a **delegability** attribute: delegable / delegable further / not stated. *Not stated* means **not delegable** (D-76).
- **GrantDelegation** is refused (inapplicable, with reason) unless the grant is delegable under the rule version in force on the grant's effective date, the scope ⊆ the delegator's effective powers, and, for a delegator who is itself a delegatee, both the rules and the source delegation permit further delegation.
- A **rule change** that withdraws delegability ends affected delegations from its effective date (DelegationEnded → MandateRevoked); past acts stand.
- §33.6's open item "default delegability" is closed by D-76.

## 35. Deliberation (member forums): status on 2026-11-09

**Strategic design: complete as a working design** (D-50 to D-56; §23). A later review paste proposing a fresh strategic round for "Member Communication & Deliberation" was reconciled against these records; no new strategic round is needed.

**Settled:**
- **Two contexts, not one:** Deliberation (forums, supports, motions) and Communication (delivery, §24). The name "Member Communication & Deliberation" is not used for one context.
- **Two forum dimensions, not one tree:** geographic forums (ward, municipality, district, province, national) scoped by a unit; committee forums scoped by their owner's committee (Governance organ or Election committee, X-7).
- **Participation is automatic and derived from belonging**, never from manually maintained lists. A member **reads** their own unit's forum and every forum above it (D-56 default) and **writes** in every forum whose scope contains their belonging (D-56: writing within the forum's scope). Restructuring follows the successor unit; leaving a unit ends access, while the person's own contributions stay visible to them.
- One support per entitled person per item, withdrawal allowed (DL-1, DL-10); support visibility per D-52; rank never decides and minority ideas keep visibility (DL-2); referrals keep their origin and support counts per scope (DL-4); motions with named co-signatures pass to Governance on submission (D-51, DL-5); duty to respond only by rule (D-54); moderation from positions or rules, reviewed by the tribunal by default (DL-3, D-53); posting identity per D-55.

**Open, for Deliberation's tactical design (no Principal decision needed now):**
- **Ranking [P]:** several views side by side ("most supported", "new", "contested", "recently active"), no single universal score.
- **Duplicates [P]:** moderators may **link** ideas as duplicates; supports are never merged automatically; recorded and reviewable (DL-3).
- **Who besides members may write** (staff, guests, observers): organisation rule ("depends on the capacity").

**Order:** tactical design after Governance (§33) and Membership/Organisation, because it depends on sittings, positions, submissions and belonging.

## 36. Cross-project reconciliation: public-digit's forum design (2026-11-09)

**Source [E]** (`/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/public-digit`, brainstorming of Nov 2025 – Jan 2026; not governed, not ratified). Two names are used there: **"Discussion Forum Context"** (`architecture/backend/membership-context/brainstorming/20260101_2309_architecture_review.md:253`; `…/20260101_2313_membership_related_stuff.md:686`; `architecture/design/20251115_1438_architecture_boundies_and_routing_policy.md:890`) and **"MembershipForum Context"** (`architecture/backend/moduleregistry_context/phase_1/20251228_0131_starting_point_message.md:13`; `…/claude_analysis/MODULEREGISTRY_CONTEXT_PROFESSIONAL_PROMPT.md:22`). **The context name in nrna1 ("Deliberation") is open until the Principal chooses [OQ].**

**Conflicts with the accepted baseline (the baseline governs):**
1. "Bubbling" district view including ward posts conflicts with the D-56 default (own unit and above); allowed only where the organisation's rules open it.
2. Subscribing directly to Membership events conflicts with ratified Membership partners (D-25, D-73) and X-8; participation is derived through Organisation's member belonging.
3. Moderation by "District/Province Admin" role conflicts with DL-3 and D-1a; walking up the tree is possible only as a rule over positions.
4. A "Leadership Score Context" using individual upvotes conflicts with D-52 (counted only) and DL-3 (no privileges from points); scoring belongs to the separate social-contribution discovery (PBDIGIT-331/335), analytical only.

**Consistent:** forums never check user accounts (D-75); announcements from a central committee map to Communication official messages or notices (D-59), shown in forums.

**New candidate rule [OQ, organisation rules]:** only active members write; members whose membership is pending read only. Related open point: whether a suspended member may write (the Principal answered earlier that suspension restricts rights while the person still belongs).

## 37. Corrections to §33–§34 (review, 2026-11-09)

- **§34, third bullet, withdrawn as a decision:** "a rule change that withdraws delegability ends affected delegations" is **not** entailed by D-76, which governs only whether a delegation may be granted. Whether an existing delegation ends, or only new ones are prevented, is an **open organisation-rule question [OQ]**, to be brought to the Principal during the Governance review.
- **§33.3:** recording the Governance part of a final determination as a Resolution (method "determination") must record **the application of the remedy**, never a second determination; to be validated in the next Governance round.
- **Context naming:** "Deliberation" stays provisional pending the Principal's choice (§36).

## 38. Membership and Organisation tactical model (PROPOSED)

**Status:** architect's proposal; not accepted; no implementation authorised. Membership's ratified aggregate, correction algebra, policy switches and invariants are unchanged.

### 38.1 Responsibilities

- **Membership** answers *is this person a member, of what type, suspended, since when, and what was known when*. Aggregate (ratified): one **Membership** per (Person, Organisation), an append-only fact log ordered by `seq`, with `effectiveAt`, `decidedAt`, `recordedAt`.
- **Organisation** answers *which units exist, and who belongs where, in which capacity, over time*. Aggregates (D-22): **Unit** (tree with change sets and tree version) and **PersonBelonging** (person, unit, capacity, period, basis). Belonging facts are append-only with `recordedAt`.
- **Join key:** both use the **organisation-specific `PersonRef`** (D-75); nothing crosses organisations.

### 38.2 Knowledge barrier and `closedThrough` (each source: Membership, Organisation, Governance)

1. Every fact-writing transaction takes a **shared** per-organisation lock, then takes `recordedAt` from the **database clock**, as `max(clock_timestamp(), closedThrough + 1 µs, last per stream + 1 µs)`, and holds the lock until commit.
2. **Closing to τ:** a short transaction takes the **exclusive** lock (waiting for every in-flight writer to commit or abort), checks `clock_timestamp() ≥ τ`, and records `closedThrough = τ`.
3. **Proof:** a writer that assigned `recordedAt ≤ τ` held the shared lock before closing started, so it has committed or aborted. A writer that commits later obtained its lock after closing and, by rule 1, has `recordedAt > closedThrough`. So no fact with `recordedAt ≤ closedThrough` can appear later. This does not depend on clock monotonicity across servers: there is one database clock, and the `max(…)` rule also prevents a backward-moving clock from re-entering a closed interval.
4. **On demand:** a query with `τ > closedThrough` first attempts to close to τ (if τ is not in the future). It is refused with `BOUNDARY_NOT_CLOSED` only if closing cannot complete (for example, a lock timeout). Answers are read in a snapshot taken after `closedThrough` committed.
5. **A maximum observed `seq` is never treated as completeness.** Completeness is a property of the source's barrier, not of any consumer's events.

### 38.3 One shared τ per operation

A multi-context operation (for example fixing an electorate) **chooses τ once** (the cut-off instant T, D-42), passes the same τ to every query, and **stores it** with the result. Each source closes to τ before answering. **Projections never answer τ queries**: they serve current views only.

### 38.4 Coverage event, projection, rebuild

- `MembershipCoverageResolved` (D-73, C1) is written to Membership's **transactional outbox in the same transaction** as the fact that changes coverage.
- **Organisation's inbox:** records the event id; applies it only if `seq` is higher than the stored one for that stream; same `seq` with different content is an integrity alarm. Recording receipt and updating the projection happen **in one transaction**, so "received" and "applied" cannot diverge.
- **Missing or late events** do not corrupt the projection, because each event carries full state; the next event heals it. A **reconciliation** pass compares the projection's `seq` per membership with C2 at `closedThrough` and repairs differences.
- **Rebuild:** seed from C2 at `τ = closedThrough`, then apply events with higher `seq`.

### 38.5 Effective member belonging (R-5)

`EffectiveMembersAsAt(scopeUnit, asOf, τ)` is answered by Organisation from:
- the tree **as known at τ** and as at `asOf` (units at or below the scope);
- belongings with capacity *member*, valid at `asOf`, **as known at τ**;
- coverage from **C2 at τ** (never from the projection), with §12.2's anchoring (belonging anchored to the coverage interval in which it started; an annulled End merges intervals and revives it; a real re-admission does not).

It returns `PersonRef`s plus τ, both sources' `closedThrough` and the tree version. **Election** combines this with the C4 predicates (type, suspension) **at the same τ**, and decides entitlement itself.

### 38.6 Scenarios

| # | Scenario | Owner and event | Result |
|---|---|---|---|
| 1 | Becomes a member | Membership: Admit → coverage event | Member belonging counts from the covered date |
| 2 | Suspended | Membership: Suspend | Belonging unchanged (still belongs); Election's rules decide entitlement via C4 |
| 3 | Membership ends | Membership: End → coverage event | Member belonging stops counting from the End's effective date |
| 4 | Changes ward | Organisation: new belonging, old ends | History kept; R-5 as at a date picks the right ward |
| 5 | District restructured | Organisation: change set | Belonging resolves through D-13 to D-19 |
| 6 | Belonging corrected retrospectively | Organisation: correction fact | "As known now" changes; "as known at τ" unchanged |
| 7 | Related changes arrive at different times | Both | τ queries go to sources, so lag does not matter |
| 8 | Event twice | Inbox | Ignored by id and `seq` |
| 9 | Out of order | Inbox | Older `seq` ignored |
| 10 | Missing, recovered later | Reconciliation | Healed from C2 |
| 11 | Electorate query while the projection lags | R-5 uses C2 | Correct; projection not used |
| 12 | Reconstruct a past decision | Stored τ and markers | Same answers (P3 plus barrier) |

### 38.7 Findings

- **Contract gap in C2 [requires an ARB note]:** C2 is keyed by `membershipIds[]`, but Organisation may not yet know the membership id of a person admitted just before τ (its projection can lag). Because Membership is unique per (Person, Organisation) (ratified), C2 should also accept **`personRefs[]`**. Additive; no change in meaning.
- **Implementation defect:** `recordedAt` taken from the application clock before commit (`app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php:219`) violates §38.2 rule 1.
- **Technical uncertainty:** cost of C2 for large electorates (batching).

## 39. D-77 applied to §38

§38.5 and §38.4 use `CoverageKnownAt(organisationId, personRefs[], τ)` for R-5, so a person admitted just before τ is never missed because Organisation's projection lags. §38.7's contract gap is closed.

## 40. Governance tactical review, round 5 (PROPOSED)

### 40.1 Determination remedies: application facts, not Resolutions (supersedes §33.3's last bullet)

- **Rejected:** recording a final determination's Governance part as a Resolution with method "determination". A Resolution is an act of a Governance organ; the tribunal's act is the **Determination**, owned by Adjudication (D-67). A Resolution would create a second record of the same act.
- **Adopted [P]:** every change to a Governance aggregate carries a **Basis** value object, one of: `PartRef` (a Resolution part), `DeterminationRemedyRef` (determination id, remedy-part id, instance), or `RecordedFact` (death, resignation submitted). Governance applies a remedy part **directly** to the target aggregate (for example `EndTenure(reason = determination, basis = DeterminationRemedyRef, effectiveFrom = date set)`), records **`RemedyApplied` / `RemedyInapplicable`** in the decision log with its own log position, and reports the outcome to Adjudication. Interim measures follow the same path (`RestrictPowers`, basis = DeterminationRemedyRef).
- **Consequences:** one record per act; the decision log still shows when Governance learnt and applied it; idempotent per remedy-part id.

### 40.2 Invariants re-checked

| # | Invariant | Enforced by |
|---|---|---|
| G-I1 | One holder at a time per position | Position |
| G-I2 | Incompatibility over overlapping effective periods | Guard clearance (§33.1, storage-level reference) |
| G-I3 | Delegation scope ⊆ delegator's effective powers; delegable under the rules (D-76) | AuthorityAssignment at grant; power service at use |
| G-I4 | No authority-sensitive Governance act on a stale mandate | Governance re-checks its own facts when recording (§33.2) |
| G-I5 | Every change has exactly one Basis | Value object, required on every command |
| G-I6 | Resolutions never change; remedies never become Resolutions | GD-1; §40.1 |

### 40.3 Open: existing delegations when the rules withdraw delegability

Principal question (organisation rule), §40 conversation, 2026-11-09. Until decided, the model does not end existing delegations automatically, and does not let them create new authority either: use-time evaluation follows the Principal's ruling once made.

## 41. Governance tactical review, round 6 (PROPOSED; incorporates D-78)

### 41.1 Three levels of a delegation

1. **Record:** the AuthorityAssignment as granted (history, never deleted).
2. **Validity per power over time (derived):** for each delegated power item, the intervals in which it is valid = grant period ∩ delegable under the rule version in force (D-76) ∩ any D-78 transition ∩ not revoked or expired ∩ within the delegator's effective powers.
3. **Authority for an act (derived, at use):** the valid power items at the act's effective time, minus restrictions and recusals (§20.3, D-69).

A record never confers authority by existing. **Partial withdrawal** ends only the affected power items; the rest of the assignment continues.

**Transition provisions:** a RuleProvision version may carry a `TransitionProvision` (affected powers or position kinds; existing delegations continue until a date, until original expiry, or as stated). Absent → end at the version's effective date (D-78). When the version is published, Governance computes the affected delegations and emits `DelegationScopeEnded(assignment, powers, effectiveFrom)` and the corresponding `MandateRevoked` with `revokedAt` = that date [I: `revokedAt` read as effective time; to be confirmed in Authorization's tactical design].

### 41.2 Position and Tenure: boundary compared

| | A. Position owns its tenures | B. Tenure independent |
|---|---|---|
| One holder at a time | Checked inside one aggregate (optimistic version) | Needs a cross-tenure mechanism |
| Concurrent appointments | Second fails on version, retries, sees the first | Needs a lock or constraint |
| History, corrections, remedies | One place per office | Spread across aggregates |

**Recommended: A**, plus **defence in depth in storage**: tenure rows carry an exclusion constraint on (position, effective period) so that overlapping holders are refused even by a write that bypasses the aggregate. Incompatibility across positions stays with the guard (rules vary, so no static constraint expresses it).

**Guard guarantees:** the clearance is written in the same transaction as the tenure change (an abort leaves no orphan); single use; bound to person, position, interval and operation. The guard **reads the Positions** under the person lock; the index is only an accelerator and is **never used to evaluate authority**, so a missing or stale index can neither grant nor remove authority. Rebuild from Positions.

### 41.3 Other aggregates

- **AuthorityAssignment:** granted → (per power) valid / ended by revocation, expiry, transition, or loss of the delegator's power. Invariants at grant: D-76 delegability, scope ⊆ delegator's powers on the effective date, further-delegation flags.
- **RuleProvision:** versions with effective dates; for an evaluation at (t, τ) the version in force at t **as known at τ** (DecisionLog). Transition provisions belong to the amending version.
- **GroupApplication:** needed only for groups spanning contexts; indivisible groups within Governance are applied in one local transaction.

### 41.4 Basis, refined

One mandatory, typed **Basis envelope** per change (replaces §40.1's "exactly one basis"):

| Field | Content |
|---|---|
| `kind` | Part · DeterminationRemedy · RecordedFact · RuleEffect · Expiry · Correction |
| `source` | Part ref · (determination, remedy part, instance) · fact type with evidence and recorder authority (V-59) · amending rule version and transition · originating period · corrected record and correcting evidence |
| `supportingProvisions[]` | Every `(ruleRef, ruleVersion)` relied on, each with its evaluated conditions (so D-69 cumulative conditions keep their meaning) |
| `logPosition` | When Governance recorded it |

A `RecordedFact` without a fact type, evidence and recorder authority is refused.

### 41.5 Remedy application

- **Key:** (determination, remedy part, instance). Governance stores one **RemedyApplication** per key with its outcome: `Applied`, `AppliedNoChange` (target already in the required state), `Inapplicable(reason)`.
- **Duplicate or lost response:** a repeat returns the stored outcome; nothing is applied twice.
- **Target changed:** the remedy names its target (a specific tenure or assignment); evaluated against current facts; if no longer applicable → `Inapplicable` with reason.
- **Several parts:** each part has its own outcome; parts declared indivisible across contexts use GroupApplication.
- **Concurrency:** applied under the target aggregate's version (and the person lock for tenures).
- **Out of order:** a later remedy part for the same determination waits until earlier ones have outcomes.
- **Reporting:** every outcome is sent to Adjudication (`RemedyApplicationReported`); Inapplicable outcomes always.
- **Interim measures:** Governance's own acts re-check immediately (G-I4). For operations gated only by Authorization, the remaining window is delivery lag of `MandateRevoked`: an **accepted limitation** unless Authorization queries Governance's effective powers synchronously for designated sensitive operations [P, to be settled in Authorization's tactical design].

### 41.6 Invariants and enforcement

| Invariant | Enforcement |
|---|---|
| One holder per exclusive position | Position aggregate + storage exclusion constraint |
| No incompatible overlapping tenures | Guard under person lock + single-use clearance reference |
| Delegated scope ⊆ delegator's powers; D-76 | AuthorityAssignment at grant; power service at use |
| D-78 transitions | RuleProvision transition + per-power validity; scope-ended events |
| No act on stale authority | Governance re-checks its facts on every authority-sensitive write |
| Traceable basis | Basis envelope required on every command |
| Resolutions immutable; remedies never Resolutions | GD-1; RemedyApplication |
| History reconstructable | Append-only facts, log positions, rule versions as known at τ |
| Mandate never treated as Governance fact | Mandates derived only; Governance never reads them |

### 41.7 Scenarios (all pass on this model)

Normal appointment · two concurrent appointments (version conflict, retry) · incompatible positions (guard → inapplicable) · valid delegation · delegation without permission (refused, D-76) · further delegation without permission in the assignment (refused) · rule withdraws all delegability (all power items end at effective date) · withdraws one power (only that item ends) · express transition (ends on the stated date) · silent rule (ends at effective date, D-78) · revocation before expiry · determination ends a tenure (RemedyApplication, EndTenure with DeterminationRemedy basis) · interim restriction (RestrictPowers) · duplicate remedy (stored outcome) · remedy after target changed (Inapplicable or AppliedNoChange) · authority-sensitive act during a rule change (evaluated against the version in force at the act's effective time) · reconstruction before and after a D-78 termination (per-power validity intervals).

### 41.8 Remaining

- **Technical proofs:** the exclusion constraint, the guard and the barrier need database-level tests.
- **Authorization:** reading `revokedAt` as effective time, and a synchronous effective-powers query for sensitive operations.
- **No Principal decision remains** for Governance's tactical model.

## 42. Organisation strategic baseline, consolidated (PROPOSED; reconciles the 2026-11-09 investigation)

**Status:** consolidation of accepted working decisions (D-3, D-13 to D-22, D-27, D-29 to D-31, D-73, D-75, D-77) plus proposals for the gaps found by `docs/publicdigit/architecture/reviews/2026-11-09-geography-organisation-relationship-investigation.md`. Not ratified; no implementation, no migration design.

### 42.1 Purpose and classification

Organisation records **how the organisation is structured and who belongs where, over time, on the basis of the organisation's own acts and rules**. Supporting subdomain (§3): essential to every core capability (elections, governance, deliberation), but not where PublicDigit differentiates.

**Owns:** the organisation registry; units, their levels, trees (geographic and wings), parents over time, successors; each person's belongings (capacity, unit, basis, period); geographical coverage links; restructuring outcomes applied from Governance decisions (R-1). **Never owns:** membership truth (Membership), official geography (Reference Geography, D-31), organs, positions, tenures, authority (Governance, D-4, D-5), person data beyond the organisation-specific `PersonRef` (D-75), entitlement (Election).

### 42.2 The investigation's "Committee" is three concepts

| In code (`Membership/Domain/Committee`) | Target concept | Owner |
|---|---|---|
| The district, ward, province itself | **OrganisationalUnit** | Organisation |
| "District Committee" as a decision-making body | **Organ** attached to that unit (§5) | Governance |
| `CommitteeAssignment` (roles), role limits | **Position and tenure** | Governance |
| A member's place (today: residence geography) | **PersonBelonging** | Organisation |

### 42.3 Ubiquitous language

| Term | Meaning | Is not | Owner · kind |
|---|---|---|---|
| Organisation | The registered body with its constitution | A tenant or account | Organisation · fact |
| OrganisationalUnit | A place in the party's structure (ward, zone, district, chapter, wing branch) | An official geographic unit; an organ | Organisation · fact |
| OrganisationalLevel | A level the organisation defines for a tree | A geographic admin level | Organisation · policy (from rules, R-15) |
| OrganisationalHierarchy | Units and their parents over time, per tree | The geographic hierarchy | Organisation · derived view |
| PersonBelonging | A person's place in a unit, in a capacity, with a basis and period | Membership; a position | Organisation · fact |
| BelongingAssignment | The act that creates a belonging (allocation, decision, admission naming a unit, rule) | — | Basis of a belonging |
| BelongingTransfer | Ending one belonging and starting another, by one basis | A residence change | Organisation · decision applied |
| OrganisationalRestructuring | Change sets (create, move, divide, merge, dissolve) | Geographic revisions | Governance decides, Organisation applies (R-1, D-21, D-30) |
| UnitSuccession | Successor declared by a restructuring | — | Organisation · fact (D-15) |
| GeographicalCoverage | The set of official units a unit covers | Belonging; authority | Organisation · relationship (proposed) |
| GeographicalReference | A stable reference to one official unit and revision | A copied hierarchy | Reference Geography supplies; Organisation stores the reference |
| MembershipCoverage | Periods in which Membership covers a person (C1, C2) | Belonging | Membership · fact; Organisation · copy |

### 42.4 Hierarchy and geography [P]

- **Principle:** the party's structure is an organisational fact. Geography may inform or constrain it; **it never creates, changes or removes units or belongings.**
- **Units need no geography.** Coverage is **0..n** official units, each held as `(officialUnitRef, revision)`; a zone may cover parts of several districts; a diaspora chapter may cover several countries.
- **No unit for every official unit, and no shared parents:** an organisational parent is set by the organisation (S-1), independently of official parents.
- **Wings and diaspora:** separate trees (S-5) or units in the geographic tree, as the organisation's rules say.
- **Missing official unit:** the organisational unit is created anyway; its coverage stays empty or pending until the official unit exists (candidate submission, D-31).
- **Official changes** (renaming, division): Organisation receives `OfficialUnitRevised` (R-10), marks affected coverage for review, and **never moves belongings**; any organisational change goes through Governance (R-1).
- **Unavailable Geography:** structure and belonging keep working; only coverage display and suggestions wait.

### 42.5 Relationships

| Relationship | Pattern | Exchanged | Must not |
|---|---|---|---|
| Reference Geography → Organisation | Published language; Organisation keeps a rebuildable copy behind an anti-corruption layer (R-10) | Official units, revisions | Geography decides structure or belonging; direct table reads |
| Membership → Organisation | Published language (C1, C2; D-73, D-77) | Coverage | A second membership truth; reading Membership tables |
| Identity → Organisation | Published language (D-75) | Organisation-specific `PersonRef` | Cross-organisation discovery; personal data in Organisation |
| Governance → Organisation | Customer–supplier, Governance upstream for decisions (R-1) | Structural change sets, allocations | Organisation judging competence |
| Organisation → Governance | Organisation upstream for facts (R-2) | Structure and belonging as at a date | Governance copying structure |
| Organisation → Election | Organisation upstream (R-5) | Effective members as at (asOf, τ) | Election owning belonging |

### 42.6 Invariants

From accepted decisions: one active parent per date, no cycles, levels fit (S-1 to S-5); exactly one geographic member belonging per person (V-24); every belonging has a basis (D-22); history append-only, fallbacks derived (D-13 to D-19); a member belonging counts only while membership covers it (D-27); a belonging never carries authority (D-22). Derived: a belonging never creates membership; geographic coverage never establishes belonging; a residence change never changes belonging without a basis.

### 42.7 Scenarios

Sita moves from District X to District Y: her belonging stays in X until a basis moves it (outcome of question 1 decides what counts as a basis). A Europe chapter covering Germany, Austria and Switzerland: one unit, coverage of three official countries. A zone across two districts: coverage of the parts' official units. Province absent from Geography: the province unit and its organ exist; coverage pending. Official district divided: coverage marked for review; belongings unchanged until the Highest Committee decides (D-21).

### 42.8 Open

**Organisation question 1** (Principal): may an organisation's rule be the basis that places or transfers members automatically, and what is the default? Also open: whether wings are trees or unit types per organisation (organisation rules, earlier answer "depends on the wing").

## 43. Check of a reviewer model "Geography – Organisation – Membership" (2026-11-09)

| Claim | Verdict | Evidence |
|---|---|---|
| Geography has levels 0–10 | **True** | `app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/2026_01_14_000003_extend_level_range_add_continents.php:34` (`CHECK (admin_level BETWEEN 0 AND …)`); tenant migration `2026_01_14_000001_extend_admin_level_range_to_0_10.php`; `TenantGeographyProfile` (root and leaf levels 0–10). But `GeoLevelType` is a fixed enum of seven named levels (finding C-a) |
| The organisation names the geographic levels it uses | **Partly true in code; too narrow as a model** | Code: committee-structure levels carry a name, `geo_policy` and `geo_scope` (`2026_05_06_000001_create_committee_structures_tables.php`). Accepted: **organisational levels are defined by the Highest Committee and need not match geographic levels** (V-16: NRNA "internal committee … zero level, then continents, then countries, then states and then city, … defined by the highest committee"; V-17 exceptions in the rule log; §42.4). Mapping a level to a geographic level is optional, never the definition |
| "Organisation's units **and committees**" owned by Organisation | **False as worded** | Units: Organisation (D-3). Committees as decision-making bodies: **organs, owned by Governance** (§5, §42.2) |
| A member "belongs to the Ward Committee" | **Terminology to correct** | A member belongs to the **ward (unit)**; the ward committee's members are **position holders** (Governance) |
| Residence alone does not create belonging | **True** | D-22 (every belonging has a basis); Organisation question 1 open |
| Naming a level does not require a committee at that level | **True** | Units and organs arise only from decisions (D-21, D-24) |
| Open question: more than one unit at the same level? | **Already answered** | V-24 (direct): "One geographic unit only; wings in addition"; V-53 wings and V-54 staff/guest: organisation rules; V-15: several positions allowed, restricted by Central Committee rules |

## 44. D-79 applied to the Organisation baseline (§42)

- **Basis of a belonging** (D-22): `Act` (allocation, transfer decision, admission naming the unit) by default; `RuleEffect` (rule reference and version) **only** where the organisation's rules expressly adopt a residence rule.
- **Suggestions** (from an address via Reference Geography and coverage) are a read model; they never create, end or move a belonging.
- **A residence change** produces at most a proposed transfer; by default nothing changes until confirmed.
- **Code conformance:** today's classification by residence geography (`SessionMemberGeoPathProvider`, `NearbyCommitteesQueryService`; investigation D5) contradicts the D-79 default.
- §42.8's open Principal question is closed.

## 45. Organisation tactical design (PROPOSED; builds on D-22 and §9–§11, §38, §42–§44)

**Status:** architect's proposal; not accepted; no implementation, no migration. Decisions preserved with their scope: D-3, D-13 to D-22, D-27, D-29 to D-31, D-73, D-75, D-77, **D-79** (direct), **V-24** (direct: "One geographic unit only; wings in addition"), **V-53**, **V-54** (organisation rules), **V-15** (several positions; Central Committee rules restrict), **V-16**, **V-17** (Highest Committee defines levels; exceptions in its rule log).

### 45.1 Business explanation

Organisation records the party's own map of itself: the **levels** it uses, the **units** it has set up, which **official areas** each unit serves, and **where each person belongs**, always on the basis of the party's acts and rules. It uses Geography as a reference atlas, Membership as the register of who is a member, and Governance as the source of decisions; it owns none of their facts.

### 45.2 Geography levels: numbers versus types [E]

`geo_administrative_units.admin_level` is a **number per country** (`Landlord/2025_01_01_000002_create_geo_administrative_units_table.php:28`: "1=province/state, 2=district, 3=local level, 4=ward"; range 0–10). `GeoLevelType` is a **semantic label** mapped to that number per country by `GeoLevelMappingResolver::resolve(countryCode, type)`. **Organisation links a level to `(countryCode, adminLevel)`**; the type is a derived label for display. Geography is unchanged.

### 45.3 Ubiquitous language

| Term | Definition | Owner · kind | Not to confuse with |
|---|---|---|---|
| **Tree** | One structure of the organisation: the geographic tree, or one wing's tree (S-5) | Organisation · aggregate | Geography hierarchy |
| **OrganisationalLevel** | A level in a tree: order, current label, label history, optional geography link `(countryCode, adminLevel)` per country, geographic or non-geographic | Organisation · entity inside Tree | Geography level; a unit |
| **OrganisationalUnit** | A place in a tree (ward, zone, district, chapter, wing branch), with level, parents over time, periods, successors | Organisation · aggregate | An organ (Governance); an official unit |
| **Coverage** | The official units a unit serves, each `(officialUnitRef, knownRevision, period, status)` | Organisation · value objects inside Unit | Belonging |
| **PersonBelonging** | All of one person's belongings in one organisation | Organisation · aggregate (per organisation and `PersonRef`) | Membership; position |
| **Belonging** | One placement: tree, unit, capacity, period, basis, anchor | Organisation · entity inside PersonBelonging | Wing belonging is a Belonging in a wing tree |
| **Capacity** | member · staff · guest · observer (as the rules recognise) | Organisation · value object from rule copies | Membership type |
| **BelongingBasis** | `Act` (allocation, transfer decision, admission naming the unit, Governance allocation part) or `RuleEffect` (rule reference and version) | Organisation · value object | Residence |
| **Transfer** | Ending one belonging and starting another on one basis, same effective date | Organisation · command outcome | A move of residence |
| **PlacementSuggestion** | Unit proposed from an address via coverage | Organisation · read model | A belonging |
| **RuleCopy** | Immutable copy of a published Governance rule version used by Organisation (R-15): capacities, limits, placement rule, level authority | Organisation · value object | Governance's rule (the original) |

### 45.4 Aggregates

| Aggregate | Owns | Invariants | Concurrency |
|---|---|---|---|
| **Organisation** (registry) | Identity, legal details, the top unit | Exists before its structure | Own version |
| **Tree** (per organisation and tree kind) | Levels and their history; **tree version** | Levels ordered; a level in use cannot be removed (S-4); level-order changes keep every unit's level valid (S-3); label changes do not change level identity | Tree version (D-22 option C) |
| **OrganisationalUnit** | Periods, parents over time, successors, continuation declarations (D-14), coverage | One active parent per date (S-1), no cycles (S-2), level fit (S-3, exceptions per rule log V-17) | Own version **plus** the tree version for structural change sets |
| **PersonBelonging** (organisation, `PersonRef`) | All belongings with basis, period, anchor; record corrections | **At most one active geographic member belonging per date (V-24)**; wing, staff, guest limits from RuleCopy (V-53, V-54); every belonging has a basis (D-22); `RuleEffect` only if a RuleCopy expressly adopts it (D-79); never carries authority | Own version: two concurrent transfers → one wins, the other re-evaluates |

**Change sets** (R-1, D-30): structural changes are applied in one local transaction across the Tree and the affected Units, checked against the tree version; belonging allocations decided with them are applied to PersonBelonging aggregates afterwards, keyed by allocation.

### 45.5 Commands and events

| Aggregate | Commands | Events |
|---|---|---|
| Tree | DefineLevel, RenameLevel, LinkLevelToGeography, RetireLevel | LevelDefined, LevelRenamed, LevelLinked, LevelRetired |
| Unit (mostly through change sets) | EstablishUnit, MoveUnit, EndUnit(kind), BringBack, SetCoverage, MarkCoverageForReview | UnitEstablished, UnitMoved, UnitEnded, UnitBroughtBack, CoverageChanged, CoverageReviewNeeded |
| PersonBelonging | EstablishBelonging, TransferBelonging, EndBelonging, ApplyAllocation, CorrectBelongingRecord | BelongingEstablished, BelongingTransferred, BelongingEnded, BelongingRecordCorrected |

Every command carries a basis (Act or RuleEffect, §41.4 envelope); every event carries effective date and log position. **No event comes from an address change.**

### 45.6 Contracts

| With | Contract | Rule |
|---|---|---|
| Reference Geography | R-10 copy behind an anti-corruption layer; `OfficialUnitRevised` → `CoverageReviewNeeded` only | Never changes units or belongings |
| Membership | C1 (current projection), C2 by `personRefs[]` at τ (D-77) | Member capacity effective only while coverage covers it (D-27); no membership types in Organisation (types are Membership's; needed only if a RuleCopy makes capacity depend on type) |
| Identity | Organisation-specific `PersonRef` (D-75) | No personal data |
| Governance | R-1 in (change sets, allocations, rule copies R-15); R-2 out (`StructureAsAt`, `BelongingAsAt`); unit lifecycle events out | An organ references a `UnitId`; a unit may exist without an organ; organs end when their unit ends under Governance's rules (V-3, V-4, V-48) |
| Election | R-5 `EffectiveMembersAsAt(scope, asOf, τ)` | Belongings and tree as known at τ, coverage from C2 at τ (§38.5) |

### 45.7 Temporal model

Append-only facts with effective date and record time (barrier §38.2); fallbacks and successors derived (D-13 to D-19); a **record correction** changes what was known, a **new act** changes what is true; "as at (asOf, τ)" reconstructs tree, levels (label in force), coverage and belongings; current residence never rewrites history.

### 45.8 Scenarios

| # | Scenario | Context · aggregate | Outcome |
|---|---|---|---|
| 1 | Levels 0–4 only | Tree | Five levels defined; 5–10 unused |
| 2 | Same geography level, different names | Tree (per organisation) | Each organisation's own label |
| 3 | Level renamed | Tree | New label version; units and Geography unchanged |
| 4 | Central unit, no coverage | Unit | Coverage empty |
| 5 | District before the official district exists | Unit | Created; coverage pending |
| 6 | Unit covering two districts | Unit | Two coverage entries |
| 7 | Diaspora across countries | Unit (non-geographic level) | Coverage entries in several countries |
| 8 | Ward 5, no position | PersonBelonging | Belonging only; positions are Governance's |
| 9 | Moves residence, stays | — | At most a suggestion; nothing recorded (D-79) |
| 10 | Authorised transfer | PersonBelonging | One transfer, basis Act |
| 11 | Geographic plus wing | PersonBelonging | Two belongings in two trees (V-24, V-53) |
| 12 | Several positions | Governance | Not Organisation's (V-15) |
| 13 | Official unit divided or renamed | Unit | CoverageReviewNeeded; changes only by change set (D-21) |
| 14 | Membership ends | PersonBelonging (derived) | Member belonging stops counting; record kept (D-27) |
| 15 | Type change near cut-off | Membership | Answered "as known at τ" by Membership; Organisation not involved |
| 16 | Rule permitting residence placement changes | RuleCopy + PersonBelonging | New placements follow the rule version in force; existing belongings unchanged (no retroactive basis) |
| 17 | Two concurrent transfers | PersonBelonging | Version conflict; the second is re-evaluated |

### 45.9 Open

- **No Principal decision.** Organisation-rule matters already marked: wings as trees or unit types ("depends on the wing"), ex officio rights while suspended.
- **Technical:** the R-15 rule-copy contract; whether level links are per country or per tree; scaling of PersonBelonging per organisation (one aggregate per person is small).

### 45.10 Assessment

Sufficiently defined for Principal/ARB review as a tactical design. Not proven against code; the current implementation conforms in none of the belonging capabilities (conformance report, rows 10–13).

## 46. Status of §45 after the foundation reassessment (2026-11-09)

§45 (Organisation tactical design) is **held, not withdrawn**, pending the Reference Geography baseline (identity, revision, succession; R-10, XD-5). Corrections: Geography's `version` column is optimistic locking, not a revision (reassessment G-1); the level link `(countryCode, adminLevel)` is retained on business grounds (levels are per country) but was first derived from code (G-8); scenario "member without belonging" is excluded by V-23. Reassessment: `docs/publicdigit/architecture/reviews/2026-11-09-strategic-foundation-reassessment-geography-organisation-membership.md`.

## 47. Corrections accepted from the Principal DDD Architect review (2026-11-09)

- The foundation reassessment's Decision 1 (a/b/c) is **withdrawn and reframed** (Geography discovery §9).
- G-7's "Organisation records the belonging eventually" is **withdrawn as a resolution**: it does not show that V-23 holds. The invariant stands: Membership owns admission, Organisation owns belonging, and the platform never presents an effective admission without a unit belonging. Mechanism: Membership–Organisation tactical contract.
- Membership needs a **tactical conformance design**, not rediscovery. §45 stays a proposal.
- Geography strategic discovery: `docs/publicdigit/architecture/reviews/2026-11-09-reference-geography-strategic-discovery.md`.

## 48. D-80 and Geography revision 2 (2026-11-09)

- **D-80** (direct): address and committee structure are separate; an address in District X never associates a person with District X's unit or committee (discovery record §11.14). Applies to §42–§45: an address is never a belonging, basis or composition rule; at most a suggestion (D-79).
- Geography strategic discovery revision 2 appended (`docs/publicdigit/architecture/reviews/2026-11-09-reference-geography-strategic-discovery.md`): Nepal example corrected from the constitution's text; statuses split into five dimensions; schemes instead of one tree; five dates; Question 1 dossier.

## 49. Organisation tactical design, step 2: Structure (PROPOSED, 2026-11-10; revises §9, §11, §38.2 and §45 for Organisation's structure)

**Status:** architect's proposal under the **accepted** Organisation strategic baseline (D-106) and the **ratified** Reference Geography (D-93) and Membership (D-96; CR-01 = D-73, D-77). **Not accepted; no implementation.** Earlier sections are not edited; where this section differs, **§49 governs for Structure.** Entry assessment: `docs/publicdigit/architecture/reviews/2026-11-10-organisation-tactical-design-entry-assessment.md` (T-1 … T-8, each re-verified here against the source text).

**Labels:** [AD] (RAT · ARB · D ruling · WD working decision) · [BF] · [AC] · [TD] · [TU] · [OQ] · [PK] practical knowledge.

### 49.1 Business explanation

A party's structure is **its own**:
- for example a central, provincial, district, municipal and ward committee;
- or zones that cut across districts;
- or a diaspora network of country chapters.

**The party's written rules decide it, and its competent bodies change it.** Geography is the **atlas the party may point to**, never the template it must copy.

**Organisation's job:**
- record each structure faithfully, **on every date and as known at every moment;**
- **never let a map change, an address or the software alter it.**

### 49.2 Corrections to the earlier text (verified against the sources)

| # | Earlier text | Source check | Correction |
|---|---|---|---|
| C-1 | §11 step 2 and §45.4: "**A Highest Committee** resolution's structural content becomes … change sets" | D-21 is **[PK]** ("Describes the organisations under discovery, not a platform rule (D-1)"); D-106 conclusion 5 | "**A decision of the body competent under the organisation's written rules**" (recorded by Governance, D-12). No organ is named by the platform |
| C-2 | §45.3 level link `(countryCode, adminLevel)` | D-81: positions 3–10 are positions in a **dated country profile**; the same number means different things in different countries | Replaced by a **level correspondence** (§49.5) to a global level (0–2) or a **country's class**, never to a bare number |
| C-3 | §45.6 coverage `(officialUnitRef, knownRevision, …)`; "R-10 copy"; `OfficialUnitRevised` | D-93: identity `ReferenceUnitId`; knowledge by **publication position**; `RevisionPublished`; reconciliation `resolve(B, p)` → canonical A with `knownAtPosition` | §49.6 |
| C-4 | §38.2 rule 1, applied to Organisation: `recordedAt = max(clock_timestamp(), closedThrough + 1 µs, last + 1 µs)` | D-73: "proved by transaction ordering, not by moving timestamp generation" | **Refuse, don't bump** (§49.8). §38.2 rules 2 to 5 (shared lock for writers, exclusive close, on-demand close, refusal) are kept |
| C-5 | My entry assessment's "exactly one active parent" (§3) | §9 S-1 already says "(the top unit has none)" | **Roots are explicit** (§49.4 S-1) |
| C-6 | §45.4 "At most one active geographic member belonging per date (V-24)" | V-24 is a direct answer; D-30 and D-22 are **WD** | Belonging is step 3. **Not carried forward as accepted here** |

### 49.3 Ubiquitous language (Structure)

| Term | Meaning | Not to be confused with |
|---|---|---|
| **Tree** | One structure of the organisation: the main (often geographic) structure, or a wing's own structure where the rules give it one (S-5, V-21) | Geography's hierarchy |
| **Root** | The one unit of a tree that has no parent on a date (the national unit; a wing's top unit) | A unit with a missing parent |
| **Level** | An ordered rank in one tree, with a label history, defined by the organisation | A Geography level or profile position |
| **Level correspondence** | An optional statement that units at this level **usually serve** official references of a stated kind (§49.5) | A rule that units must copy Geography |
| **OrganisationalUnit** | A place in a tree where people may belong: periods, parent over time, successors | An organ (Governance); an official reference |
| **Structural change set** | All structural operations of one decision with one effective date, applied whole or not at all (D-30, WD) | A database batch |
| **Record correction** | Fixing PublicDigit's representation of an act that was recorded wrongly | A new act (a new decision) |
| **Coverage reference** | A unit's pointer to an official reference **as Geography knew it at a stated position** | Belonging |

### 49.4 Structural invariants

| # | Invariant | Business problem prevented | Source and status | Boundary |
|---|---|---|---|---|
| **S-0** | Every tree has **exactly one root** on any date it is in use | Two "national committees", or none, on the same day | [TD], from §9 "the top unit"; **provisional** until a written rule shows otherwise | Tree |
| **S-1** | On any date, **every active unit except the root has exactly one active parent in its tree**; the root has none | A district under two provinces at once; an orphaned district | §9 S-1 [P], refined; WD D-22 | Tree |
| **S-2** | **No cycles** on any date | A ward made the parent of its own district | §9 S-2 [P] | Tree |
| **S-3** | A child's level is **lower than** its parent's, except recorded exceptions (V-17, **[PK]** "rule log") and the temporary fallback (D-19, WD) | A district placed under a ward | §9 S-3 [P] | Tree |
| **S-4** | A level **in use** on any date cannot be retired | Units left without a rank | §9 S-4 [P] | Tree |
| **S-5** | A wing has its own tree **only where the written rules give it one**; otherwise its units sit in the main tree (V-21, D-99) | Forcing one shape on every party | §9 S-5 [P], qualified by D-99 | Organisation |
| **S-9** | **The geographic tree's original root unit exists from before the first admission** (a later root may be inserted above it, D-122), **and the root cannot be closed while any membership coverage exists**; inserting a new root above it moves no member without a decision (D-122). **Not a unit closure: dissolving the whole organisation is a separate act** (D-120). **The coverage condition is read from Membership's C2 at the change set's τ** (CR-01) | Members with no unit (D-114) | **D-120, D-122** | Tree (with a C2 read) |
| ~~**S-6**~~ | ~~A unit stays in its tree for life.~~ **Withdrawn (§50.1 R-1): the deciding act states continuity or succession.** | | | |
| **S-7** | **Every structural record has a basis:** a recorded decision (Governance) or a record correction referring to one | Structure appearing without authority | D-106 conclusions 4 and 5 [AC accepted] | Unit and change set |
| **S-8** | **Nothing from Geography, an address or a residence change creates, moves, ends or merges a unit** | The map rewriting the party | D-80, D-82 [D]; D-106 | All |

**Checked on every date from the effective date onwards** (§11 step 4, kept), because later-dated changes may already be recorded. A change set that would break any invariant on any such date is **inapplicable**: nothing is written; the fact is recorded and reported (§11, kept).

### 49.5 Organisational levels and Geography: independent, optionally corresponding [AC]

- **The organisation's hierarchy is its own** [§42; D-106]. It need not mirror Geography:
  - its units need not exist for every official unit;
  - **its parents are set by the organisation, independently of official parents.**
- **A level may carry a correspondence**, which has three forms:
  - **(a)** none (zones, chapters, non-geographic wings);
  - **(b)** a **global level** (0 World, 1 M49 region, 2 country or area), e.g. a diaspora organisation's country chapters;
  - **(c)** a **country's class** in that country's dated profile (e.g. Nepal's *district* class), **per country and dated**.

  **Not a bare position number** (C-2). A class is used rather than a position because a profile may re-number positions over time while the class stays.
- **What the correspondence is for:**
  - **checking coverage plausibility** (a warning when a district-level unit's coverage points at a ward);
  - **suggesting coverage;**
  - **presentation.**
- **What it never does:**
  - **impose units or parents;**
  - **block a change set:** it is a check that warns, not an invariant;
  - **change with Geography by itself.**

  If a country's profile changes (D-81), the correspondence is **flagged for review**; the organisation decides.

### 49.6 Geography reference contract (corrects §45.6) [TD]

**What Organisation stores per coverage reference:** `(ReferenceUnitId as relied on, knownAtPosition p₀, valid period [from, to), basis)`.
- **The original identity and position are never rewritten:** they show what the organisation relied on (D-93).
- **The valid period is the organisation's** (when the unit serves the place), not Geography's.

**What Organisation asks Geography:**
- **resolve(ref, p)** → canonical identity, with `knownAtPosition`;
- the reference's facts "as at (t, p)";
- **one shared τ** in cross-context operations (D-73), mapped by Geography to `positionAt(τ)`, **which must be within Geography's `closedThroughTime`** (otherwise refused or closed first). **This applies to coverage and current reads; allocation schemes read Geography at their confirmed position (A-8, §51 governs)** (IR-OTD-04 L-11).

**When Geography publishes** (`RevisionPublished`):

| Geography publication | Organisation's response |
|---|---|
| A reconciliation B → A | **No write to the structure or the coverage.** Reading coverage at a later position resolves B to A automatically (allocation schemes: only after confirmation, A-8). **CoverageReviewNeeded** only if the unit now covers the same entity twice, or A's facts differ materially (e.g. a different parent) |
| A reference ended, divided, merged or re-parented | **CoverageReviewNeeded** for each affected unit; **and AllocationReviewNeeded where the change could alter a rule-derived belonging** (§51.5). **Coverage, allocations and belonging stay as recorded until the organisation decides** (S-8; A-8: allocation schemes read Geography at their confirmed position) |
| A name, code or GROUPING change (non-structural, D-92) | Nothing; a display update when read |
| A country profile change (D-81) | Correspondence review flag (§49.5) |

- **A reversal of a reconciliation** (B independent again from position q): reading at q or later resolves B to itself. **CoverageReviewNeeded** if the organisation had relied on the merged meaning.
- **[AC] Reconciliation by itself never changes committee structure, coverage decisions or belonging.** It changes only how an identity is read. **For allocation schemes, a newer reading applies only after the scheme is confirmed at that position (A-8)**, which can move members.

### 49.7 Structural change sets (revises §11)

1. **Input:** a recorded decision of the competent body (C-1), the rule version, the effective date and the operations. **Idempotency key:** decision + change-set number.
2. **Basis check:** the decision is recorded by Governance. **Competence is Governance's (D-12); Organisation does not re-decide it.**
3. **Load** the affected units, their ancestors, and later-dated changes touching them.
4. **Check S-0 … S-5, S-7 and S-9 for every date from the effective date onwards** (S-6 withdrawn; S-8 holds by construction). S-9 reads Membership's coverage (C2 at τ) when the change set would close the root. *(Corrected per IR-OTD-01-C item 4.)*
5. **Write new records only**, and advance the **tree version**.
6. **If another change set advanced the tree version meanwhile, retry from step 3.**

**Why the tree is the consistency boundary [AC]:**
- **S-0 … S-3 span many units:** a cycle or a second root can only be seen across the tree.
- **Structural changes are rare** (decided at sittings), so serialising them per tree costs nothing in practice.
- **Non-structural edits** (a unit's display name, its coverage) **do not take the tree version**, so they never conflict with structure.

**This is D-22 option C (WD), justified here rather than assumed.**

- **Effective dates:**
  - ordinary decisions take effect **no earlier than their decision date**;
  - **retrospective effect only by ratification** (D-6, WD);
  - **a technical delay changes knowledge time only** (D-40).
- **The Governance–Organisation exchange is the provisional D-29 model:** decided change sets in; "inapplicable" and structure as at τ out. **Not a fixed contract** (D-106).

### 49.8 Temporal behaviour (replaces §38.2 rule 1 for Organisation)

**Two axes:**
- **effective date** ("what was the structure on 1 March?");
- **knowledge time τ** ("what had PublicDigit recorded by τ?").

**Every answer is "as at (date, τ)"** (CR-01 time line).

**Knowledge-time contract (the platform contract of D-93 §6, applied to Organisation's stream):**
- **(0) One boundary [IR-OTD-01 M-3]:** Organisation has **one** knowledge boundary (`closedThroughTime`) covering **structure, belonging and allocation schemes**; one close covers all three, so no answer mixes knowledge times.
- **(1) `recordedAt`** is the database clock reading taken inside the writing transaction, under the **shared** writer lock (§38.2 rule 1 without the bump).
- **(2) Refusal:** the write is **refused (and retried) if the reading is ≤ `closedThroughTime`, or ≤ the stream's last `recordedAt`**. No timestamp is ever adjusted.
- **(3) Closing to τ** takes the **exclusive** lock, waits for in-flight writers, checks clock ≥ τ, records the boundary (§38.2 rules 2 to 4, kept).
- **(4) Queries beyond the boundary** are refused, never answered partially.

**The proof** follows by transaction ordering, as in D-93 §6.

**What D-73 requires and what it does not [AC]:**
- **It requires:**
  - facts **recorded once and never changed in place;**
  - corrections as new records;
  - **"what was known at τ" reproducible.**
- **It does not require event sourcing or any particular storage mechanism.** Append-only storage of facts plus projections is **one** way to meet it [TU, for the implementation architecture].

**Record correction versus new act [TD]:**
- **A new act** changes what is true **from its effective date:** a new decision, by its basis.
- **A record correction** changes **what PublicDigit had recorded.** It must refer to the original act's evidence; it is made by the organisation's authorised recorder, and **administrative responsibility is not decision authority** (Decision A).
- **If a "correction" would change the meaning of the decision, it is not a correction; it needs a new act.**

### 49.9 Lifecycle, operations and events

| Unit lifecycle | Operation (through a change set unless noted) | Event candidate (fact · owner · consumers) |
|---|---|---|
| — → Active | EstablishUnit | **UnitEstablished**: a unit exists from date d (Organisation) · Governance, Election, Communication |
| Active → Active | MoveUnit (new parent; keeps identity, V-49) | **UnitMoved** (Organisation) · same |
| Active → Active | RenameUnit (**not** structural; own version) | **UnitRenamed** (Organisation) · display only |
| Active → Ended(kind: dissolved · divided · merged · absorbed) | EndUnit | **UnitEnded** (Organisation) · Governance (its organs' fate is Governance's, V-3, V-4, V-48), Election |
| Ended → Active (same unit) | BringBack, with continuation declaration (D-14) | **UnitBroughtBack** (Organisation) |
| — | DeclareSuccessor (D-15) | **SuccessorDeclared** (Organisation) |
| Levels | DefineLevel, RenameLevel, ReorderLevels, RetireLevel, SetLevelCorrespondence (outside change sets when not structural) | **LevelDefined**, **LevelRenamed**, **LevelsReordered**, **LevelRetired**, **LevelCorrespondenceSet** |
| Records | CorrectStructureRecord (§49.8) | **StructureRecordCorrected** (Organisation) · all consumers of the corrected record |
| Integration | — | **StructuralChangeSetApplied** / **StructuralChangeSetInapplicable** (to Governance) |
| From Geography | — | **CoverageReviewNeeded** (Organisation, internal) |

**Every event carries:** the effective date, `recordedAt`, the basis, and the tree version where structural.

### 49.10 Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | New party with only a national unit | One tree, one root, no parent; S-0 … S-2 hold |
| 2 | Party with zones spanning several districts | Zone level without correspondence; coverage lists several districts; no Geography rule involved |
| 3 | Diaspora association with country chapters | Chapter level corresponds to level 2 (country or area); coverage = one country each |
| 4 | Nepal-style national restructuring published in Geography | **CoverageReviewNeeded** for affected units; **no unit created, ended or moved** until the party decides (S-8) |
| 5 | Geography reconciles B → A | No structural write; coverage reads as A; review only if duplicated |
| 6 | Reconciliation reversed | Reads as B again from the reversal position; review if relied upon |
| 7 | Change set would make a ward the parent of its district | **Inapplicable** (S-2, S-3); nothing written; reported |
| 8 | Two change sets in one tree at once | The second retries after the tree version advanced; re-checked |
| 9 | Change dated next month already recorded; an earlier change set today | Checked on every date from today, **including** next month's state |
| 10 | Ratification of an act from three months ago (D-6) | Effective from the act date; checks from that date; knowledge time is today |
| 11 | Unit renamed | No tree version; history keeps both names |
| 12 | Wing with its own structure under the rules | Separate tree with its own root (S-5) |
| 13 | Wing unit moved into the main tree | Continuity or succession, **as the deciding act states** (§50.1 R-1; S-6 withdrawn) |
| 14 | Wrong effective date entered for a recorded decision | Record correction referring to the decision; knowledge history shows both |
| 15 | "Correction" that would change which districts merge | Refused as a correction; needs a new act |
| 16 | Clock steps back on the database server | Writes refused until the clock passes the last `recordedAt`; nothing adjusted |
| 17 | Election asks for the structure at cut-off τ | Answered "as at (cut-off, τ)" after closing to τ, or refused |

### 49.11 Cross-context responsibilities (Structure only)

| With | Organisation needs | Organisation provides | Never |
|---|---|---|---|
| Governance | The recorded decision and rule version (basis) | Applied or inapplicable; structure as at (date, τ) | Organisation deciding competence |
| Reference Geography | resolve(ref, p); facts as at (t, p); `RevisionPublished` | — | Geography changing structure |
| Election | — | Structure as at (cut-off, τ) | Organisation deciding who counts |
| Membership | — (belonging is step 3) | — | — |

### 49.12 Open and proof obligations

- **[OQ] None.** No genuine Principal question arises in Structure.
- **Provisional, depending on written rules:**
  - S-0 (one root per tree);
  - V-17 exceptions (**[PK]**);
  - whether a wing is a tree (S-5);
  - who may make record corrections in a given organisation (the recorder's designation).
- **[TU] for the implementation architecture:**
  - the knowledge-time contract's proof obligations (as PO-1, PO-5, PO-6, PO-7 of D-93) for Organisation's stream;
  - tree-version retry under contention;
  - **a single write path for structural records.**
- **Platform:** **Membership's §38.2 bump remains PO-8**; this section changes only Organisation's application.

### 49.13 Consistency assessment

| Against | Result |
|---|---|
| D-81 | Correspondence by class or global level, dated, optional; **no imposed hierarchy** |
| D-82, D-83 | Organisation areas are step 4; no unit is an official reference |
| D-84 … D-92 | Only Geography publishes; Organisation reads by position; GROUPING changes nothing (D-92) |
| D-93 | `ReferenceUnitId`, positions, `resolve`, reconciliation and reversal followed; original references kept |
| D-73, D-77 | One τ; refuse, don't bump; complete or refused |
| D-79, D-80 | No structure or belonging from an address or Geography (S-8) |
| D-97 … D-106 | Authority from written rules; Governance records; Organisation holds; no organ named by the platform; WD kept provisional |
| §45 | Kept: vocabulary, aggregates (Tree, Unit), change sets, scenarios. Revised: C-1 … C-6 |

**Readiness:** the Structure design is ready for independent review. **Next step: belonging (step 3).**

## 50. Organisation tactical design, step 3: Person belonging (PROPOSED, 2026-11-10; revises §45 PersonBelonging, §12.2 and §38.5 for belonging; amends §49 after its review)

**Status:** architect's proposal under D-106 (accepted), D-93 and D-96 (ratified), CR-01 (D-73, D-77). **Not accepted; no implementation.** Where this section differs from earlier ones, **§50 governs for belonging, and §50.1 governs for the four §49 points.**

**Labels:** [AD] (RAT · ARB · D ruling · WD working decision) · [BF] · [AC] · [TD] · [TU] · [OQ] · **[PK]** practical knowledge. **The V-series answers are [PK]:** "the Principal's experience of how organisations of this kind usually operate … none is [F] for any particular organisation until its actual constitution … is read" (discovery record §0).

### 50.1 Review of §49 (R-1 … R-4)

| # | Finding | Revision |
|---|---|---|
| **R-1** | S-6 ("a unit stays in its tree for life") is a hypothesis, not a rule | **S-6 withdrawn.** **Whether a unit keeps its identity when it changes tree is stated by the deciding act**, as for merger and bringing back (V-50, V-51 [PK]; D-14 WD). Two outcomes are supported: **(a) continuity**, the same unit with a dated tree assignment, applied as **one change set across both trees** (both tree versions advanced together; S-0 … S-3 checked in both); **(b) succession**, the unit ends and a new unit is declared its successor (D-15, WD), so belongings follow the successor by resolution (§10). **A change set moving a unit across trees without stating (a) or (b) is inapplicable** (reported for clarification). [TD] **No Principal question:** the identity outcome is the organisation's decision, recorded with its act **Continuity (a) is one change set over both trees, applied in one transaction, S-0 … S-3 checked in both; the unit's allocation scheme moves with it only if its destinations remain its descendants, otherwise it lapses and is reported** (IR-OTD-01 M-2; [TU] proof obligation) |
| **R-2** | S-4 confuses losing historical meaning with retiring a level | **S-4 restated:** a level keeps its identity for ever, and **historical assignments keep their meaning**. **Retiring a level from date d** is allowed when **no unit is at that level on any date ≥ d**; units at it must be re-levelled by the same or an earlier change set |
| **R-3** | §49.7 presented D-6 (WD) as a platform rule | **Platform requirement:** every structural record carries **decision date** and **effective date**, and checks run from the effective date. **Whether an earlier effective date is valid** (retrospective effect) **is assessed under the organisation's written rules by Governance** (D-12). **D-6 is the provisional default where the rules are silent** (WD), not a universal rule |
| **R-4** | Level operations not classified | See the table below |

| Level operation | Effect on tree invariants | Path |
|---|---|---|
| RenameLevel | **None:** label history only | Own version; no change set |
| SetLevelCorrespondence | **None** (§49.5: warnings and suggestions only) | Own version; no change set |
| DefineLevel **at the bottom** | **None:** no existing unit's order changes | Own version |
| DefineLevel **between** existing levels, **ReorderLevels** | **S-3** for every unit on every date ≥ effective date | **Structural change set**, with basis and tree version |
| **RetireLevel** | **S-4** (R-2) | **Structural change set** |

### 50.2 Business explanation

**A person's belonging is the organisation's decision about where, in its own structure, that person has a place**, and in what capacity:
- as a member of the Ward 5 unit;
- as staff of the district office;
- as a guest of the youth wing.

It is **not**:
- **membership:** whether the person was admitted, which Membership alone states;
- **an address:** where they live (D-80);
- **an office:** a seat on a committee (Governance);
- **a vote:** Election.

**The protections this step adds:**
- **a belonging exists only because an act of the organisation created it;**
- **it is never invented from a map or an address;**
- its history stays exact both by date and by what was known when;
- **membership's ups and downs change whether a member belonging counts, never the belonging record itself.**

### 50.3 Ubiquitous language

| Term | Meaning | Not to be confused with |
|---|---|---|
| **Belonging** | One placement: person (`PersonRef`) · unit · capacity · valid period · basis · anchor | Membership; affiliation (organisation-level, D-101) |
| **PersonBelonging** | All belongings of one person in one organisation | A person profile |
| **Capacity** | The kind of place a person has in a unit, **as the organisation's rules recognise it** | An affiliation capacity (User, Member, Worker, Guest, voter: D-101); a membership type |
| **Membership-dependent capacity** | A capacity whose belonging **counts only while Membership's coverage includes the date** (D-103: "Member" reflects Membership) | A belonging that Membership owns |
| **BelongingBasis** | The act or rule that created or changed the belonging: a **placement** (an individual act: in D-79's words an allocation, a transfer decision, or an admission naming the unit), a **change-set placement** (D-16 … D-18), an **exception** (D-116), a **ratification**, an **allocation-rule effect** (§51; a residence or area rule only where the written rules adopt one, D-79), or a **record correction** *(terms aligned per IR-OTD-01 M-7)* | An address; a suggestion |
| **Anchor** | The membership coverage interval in which a membership-dependent belonging started (§12.2) | A unit period |
| **Effective belonging** | Derived: valid on the date ∧ (if membership-dependent) within its anchored coverage ∧ the latest valid record ∧ resolved through unit successors and fallbacks (§10) | The stored record |
| **Transfer** | Ending one belonging and starting another **on one basis, same effective date** | A residence move |
| **PlacementSuggestion** | A unit proposed from an address via coverage, **for a person to confirm** | A belonging (D-79, D-80) |
| **Record correction** | Fixing a wrongly recorded belonging, with reference to its act | A new act |

**Vocabulary note:** the unit-level capacity list (§45.3: member, staff, guest, observer) and the affiliation list (D-101) **are different levels and stay different. Their naming is O-14 (open); nothing here settles it.**

### 50.4 Capacities

| Capacity (as §45 found it) | Business meaning | Needs Membership? | Needs a decision? | Governed by | Limits | Coexistence |
|---|---|---|---|---|---|---|
| **member** | The member's place in a unit | **Yes:** membership-dependent (D-103; §12.2) | A placement (D-79) or an allocation-rule effect (§51) | Organisation's rules | Organisation's rules; **[PK] V-24 "one geographic unit only; wings in addition"** | With wing and staff belongings, as the rules say (V-53, V-54 [PK]) |
| **staff / worker** | Works for the unit | No | An act | Rules | Rules (V-54 [PK]) | Rules |
| **guest / observer** | Admitted to a unit's activities without membership | No | An act | Rules | Rules (V-54 [PK]) | Rules |
| **others** | Only where the organisation's rules define them | As the rules say | As the rules say | Rules | Rules | Rules |

**[AC] The platform defines no capacity list and no limits.**
- **Capacities, which of them require membership, and their limits come from the organisation's registered rules** (RuleCopy, R-15, WD).
- **The only platform semantics is "membership-dependent".**
- **Where the rules state no limit, none is enforced; nothing is invented** (D-97, D-106).

### 50.5 Establishing, changing and ending (lifecycle)

| Transition | Basis | Old record | New record | Effective date · knowledge time |
|---|---|---|---|---|
| **Establish** | A placement (an individual act, D-79) or a rule effect (§51) | — | Belonging | The act's effective date · `recordedAt` |
| **Transfer** | One transfer act | Ended at d | Started at d | Same d; one write |
| **End** | An act, or the end of the unit (derived) | Ended at d (explicit) **or** kept, with effective belonging derived through successors and fallback (§10) | — | d |
| **Change-set placement** after a structural change | The change set's placements (D-16 … D-18, WD) | Kept | New belonging keyed by placement | Change-set effective date |
| **Fallback** (unit dissolved, no placement or rule places the member) | Derived (V-5, V-6 [PK]; D-17 … D-19, WD); **flagged "awaiting placement"** (D-121) | Kept | **None written** | Derived "as at" |
| **Restoration** after an annulled exclusion | Derived from Membership's corrected coverage (D-27, WD) | Kept; counts again for the regained coverage | None | From Membership's correction |
| **Capacity change** | An act | Ended | New belonging in the new capacity | d |
| **Record correction** | Reference to the original act's evidence (§49.8) | Kept; superseded in knowledge | Corrected record | Knowledge changes, truth does not |
| **Membership suspended** | — | **Unchanged** (§38.6 scenario 2) | — | Election decides entitlement (C4) |
| **Membership ended** | Membership's coverage | Kept; **stops counting** after the coverage end | — | Coverage end |

**[AC]:**
- **Ending a unit never rewrites belongings.** Effective belonging follows successors and fallbacks by resolution, so **a dissolution touches no person's record.**
- **Restructuring scales without mass writes**, and history stays exact.

### 50.6 Invariants

| # | Invariant | Problem prevented | Source · status | Boundary |
|---|---|---|---|---|
| **B-1** | **Every belonging has a basis act** (or a rule effect, §51.3). An address, a coverage change or a Geography publication is never a basis; **a residence move is never a basis, but a residence or area rule the written rules expressly adopt may be, recorded as the basis** (D-79, D-114; corrected per IR-OTD-01 L-8) | Automatic placement; the map deciding | D-79, D-80 **[D]**; D-3 (WD) | PersonBelonging |
| **B-2** | **A membership-dependent belonging counts only within Membership's coverage**, as Membership states it | Organisation asserting membership | **D-103 [D]**; CR-01 C1/C2 **[RAT]** | Derived (no write) |
| **B-3** | **Limits per capacity**, as the organisation's rules state them. **Default where the rules are silent: one geographic member belonging per member per date** (D-118, confirmed D-122); a second is refused unless the rules allow it; where they allow more, each counts for its unit. Wing and non-member capacities are additional | Contradictory placements | **D-118, D-122**; rules; V-24, V-53, V-54 **[PK]**; R-15 (WD) | PersonBelonging |
| **B-4** | A **transfer** ends and starts **in one write** | A person belonging nowhere, or twice, between the two steps | [TD] | PersonBelonging |
| **B-5** | **Record-time assertion** (not a cross-aggregate invariant): a belonging is recorded against the structure **as known at its recording**; if the unit is later closed, C-2 resolves it and **the record is never rewritten** (corrected per IR-OTD-01 M-8) | Belonging to a unit that does not exist | [TD]; §10 | PersonBelonging |
| **B-6** | **Records are never changed in place**: new acts and corrections are new records | Lost history | D-73 **[ARB]** | All |
| **B-8** | **At most one placement-relevant recorded association per member per date** (the one the organisation's rules use). Others are profile information (D-101). **A second is refused unless it replaces the first from a date** (IR-OTD-01 L-9; §51.11 R-5; normative home added per IR-OTD-02 C-2) | Ambiguous placement: which recorded association the rules use | [TD]; §51.3, §51.11 R-5 | PersonBelonging |

**The former "at most one active geographic member belonging per person per date" is B-3 with a rule-supplied limit, not a platform invariant** (C-6 of §49).

**B-7** (the root requirement, D-114) is stated in §50.15.

### 50.7 Aggregate assessment

- **PersonBelonging per (organisation, `PersonRef`) is kept [AC]:**
  - **B-3, B-4 and B-8 are per person** (limits, transfers and the one placement-relevant association concern one person's records);
  - the aggregate is small;
  - it never needs another person's data.
- **What it does not hold:**
  - membership coverage (Membership's; read at τ);
  - the structure (§49; read as known at write time for B-5);
  - suggestions (a read model).
- **Concurrency:**
  - **two acts on one person conflict on the aggregate version; the second is re-evaluated** (§45.8 scenario 17);
  - acts on different people never conflict;
  - structural change sets and belongings are **not** in one transaction. **Change-set placements** (D-16 … D-18) are applied **afterwards, keyed by placement**, idempotently. Until then the derived fallback answers.
- **Membership changes need no coordination:** they change only what counts (B-2), derived at read time from Membership's coverage.

### 50.8 Temporal behaviour

- **Every answer is "as at (date, τ)":**
  - belongings as recorded by τ;
  - **Membership's coverage from C2 at τ** (`CoverageKnownAt(organisationId, membershipIds[] or personRefs[], τ)`, D-77), **never from the projection** (§38.5);
  - structure as known at τ (§49.8).
- **The C1 projection** (`MembershipCoverageResolved`, highest `seq` per membership, §38.4) **serves current views only.**
- **Knowledge-time contract:** as §49.8 (refuse, don't bump), under **Organisation's single knowledge boundary** covering structure, belonging and allocation schemes (§49.8 (0)).
- **A new act changes what is true; a correction changes what was recorded** (§49.8). An annulment in Membership is Membership's correction; Organisation only re-derives (D-27).

### 50.9 Membership integration: conformance with CR-01 (verified source text)

- **C1** `MembershipCoverageResolved(eventId, organisationId, membershipId, personRef, seq, coverage, recordedAt, schemaVersion)` carries "the **complete** coverage as Membership resolves it (INV-COV), `seq` strictly increasing per membership" (ratified record, CR-01 addition). **Used for the current projection.**
- **C2** `CoverageKnownAt(organisationId, membershipIds[], τ)` "over `Res(F_τ)` with `closedThrough`" (`personRefs[]` accepted, D-77). **Used for every τ answer.**
- **"Organisation never interprets Membership facts"** (same text). **Organisation uses the coverage intervals only; types, suspension and tenure stay Membership's** (consumed by Election through C4).
- **Gap [TU, contract]:**
  - D-79 lists "**an admission naming the unit**" as a basis act.
  - **C1 and C2 carry coverage, not the unit an admission names.**
  - **Proposal:** the admitting act's unit part is recorded in Organisation **with the same act reference** as basis. **The act is the shared basis, and neither context copies the other's fact.** How the act reaches Organisation (a Governance act record, or a Membership notice) is for the cross-context step. **No new Membership contract is created here.**

### 50.10 Governance and Election

- **Governance:** placements, exceptions and allocation rules rest on decisions recorded by Governance under the written rules (D-106; D-12 competence on the decision date), **with the authority order of D-119**. **No organ is named by the platform.** The D-29 exchange stays provisional.
- **Election:** `EffectiveMembersAsAt(scope, asOf, τ)` (§38.5; R-5, WD) returns `PersonRef`s with τ, both sources' boundaries and the tree version. **Election combines it with C4 and decides entitlement itself.** Organisation never decides who may vote.

### 50.11 Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | Admission naming Ward 5 | Belonging (member, Ward 5) on the admission act's basis; counts from Membership's coverage start |
| 2 | Belonging recorded before the admission is recorded in Membership | Stored; **does not count** until coverage includes the date. Shown as **pending membership** (§50.12) |
| 3 | Moves to another district | At most a **suggestion**; nothing changes (D-79, D-80) |
| 4 | Authorised transfer to Ward 7 | One write: ends Ward 5 at d, starts Ward 7 at d |
| 5 | Ward 5 dissolved; no placement or rule places its members | Records unchanged; effective belonging resolves to the higher unit (fallback), **flagged "awaiting placement"** (D-121) until placed |
| 6 | Division with change-set placements | New belonging keyed by placement; until applied, the fallback answers |
| 7 | Membership suspended | Belonging unchanged; Election decides via C4 |
| 8 | Membership ends | Belonging kept; stops counting after the coverage end |
| 9 | Exclusion annulled | Coverage merges; earlier belonging counts again for the regained coverage; a later explicit belonging takes precedence (D-27) |
| 10 | Re-admission after a real end | New coverage interval; **explicit placements and exceptions do not revive; rule-derived belonging re-applies from re-admission** (corrected per §51.11 R-6, IR-OTD-01 H-5) |
| 11 | Staff in two units | Allowed **iff** the rules allow (B-3) |
| 12 | Member of main unit and youth wing | Two belongings in two trees, as the rules allow (V-24, V-53 [PK]) |
| 13 | Two acts on one person at once | Second re-evaluated (aggregate version) |
| 14 | Wrong unit entered for an act | Record correction with the act reference |
| 15 | Geography reconciles the district's reference | **The reconciliation by itself** changes nothing for belonging (§49.6); **a later confirmation of an allocation scheme can** (A-8, §51.6 scenario 12) |
| 16 | Unit moves tree with continuity (R-1 a) | Belongings unchanged; they follow the unit |
| 17 | Unit moves tree by succession (R-1 b) | Belongings follow the successor by resolution |
| 18 | Election asks for members of District X at cut-off τ | §38.5 answer at τ; Election decides |

### 50.12 The one Principal question: admission without a unit

**Situation:**
- **[PK] V-23:** "Admitted into a specific unit; never without a unit."
- §47 turned this into a **platform** invariant: "the platform never presents an effective admission without a unit belonging".
- **But D-97 and D-106 make each organisation's written rules the source of such rules.** Some associations admit members to the organisation directly, for example diaspora bodies with central membership.

**What is settled either way:**
- **Membership states admission; Organisation states belonging** (ownership unchanged).
- **The platform never invents a belonging.**

**The choice:**

| Option | Rule | Consequence |
|---|---|---|
| **A. Platform-wide** | Every effective admission is shown as **incomplete until a unit belonging exists** | Simple and protective for parties like those under discovery; **wrong for organisations whose rules admit without a unit** (they would show permanent "incomplete" members) |
| **B. The organisation's rules decide (recommended)** | Where the rules require a unit at admission, an admission without one is shown as **incomplete: awaiting unit belonging** and no unit-scoped list counts the person. Where the rules do not, organisation-level membership is complete | Consistent with D-97 and D-106; the protection applies exactly where the rules require it |

**[AC] recommendation: B.**

### 50.13 Open items and proof obligations

- **[OQ]:** §50.12 only.
- **Provisional, from rules or WD:**
  - capacity lists and limits (B-3);
  - fallback behaviour (D-17 … D-19);
  - D-6 as the default for retrospective effect;
  - S-0.
- **[TU]:**
  - how the unit part of an admission reaches Organisation (§50.9 gap);
  - idempotent allocation application;
  - the knowledge-time proof obligations for the belonging stream;
  - a single write path.
- **O-14:** capacity vocabulary.

### 50.14 Consistency assessment

| Against | Result |
|---|---|
| D-79, D-80 | Every belonging has an act; addresses only suggest (B-1) |
| D-101, D-103, D-104 | Affiliation is not designed here; "member" counts only through Membership (B-2); workspace access is untouched |
| CR-01 (D-73, D-77) | C1 for current views, C2 for τ answers; Organisation never interprets Membership facts |
| D-93 | Geography never changes belonging |
| D-106 | Authority from the written rules; Governance records; Organisation holds; **no platform capacity list or limits** |
| WD (D-3, D-22, D-27, D-29, D-30) | Used as provisional, labelled; none promoted |
| §45, §12.2, §38.5 | Kept: the PersonBelonging aggregate, anchoring, R-5. Revised: V-24 as a rule-supplied limit; capacities from rules; the admission gap identified |
| §49 | Amended by §50.1 (R-1 … R-4) |

### 50.15 Principal ruling D-114 applied (2026-11-10)

**§50.12 is answered by D-114:** every effective member belongs to a geographic unit on every date, **by default the root unit.** Consequences for §50:

- **New invariant B-7 [AD D-114]:** while a person's membership coverage includes a date, they have **at least one effective geographic member belonging** on that date. **It rests on S-9 (D-120): the root always exists while coverage does** (IR-OTD-01 L-6).
  - **Default basis:** the **admission act**, placing them in the root unit unless the admission names a lower unit (D-79).
  - **Problem prevented:** a member counted nowhere (no unit-scoped list, no electorate scope).
  - **Enforcement:** derived, not written. If no recorded geographic belonging is effective on a date, **the effective belonging is the root unit as at the member's admission, carried forward by unit identity** (D-122; corrected per IR-OTD-01-C C-1). This is the §10 resolution extended to "no recorded belonging yet". **No mass write is needed when the organisation has only its root unit.**
- **"Incomplete: awaiting unit belonging" (§50.12 option B) is not needed:** the root unit is always a valid answer.
- **Moving down** when a lower unit is created: only by a change-set placement, a placement act, or an allocation rule (§51; a residence rule only where the written rules adopt it, D-79), **never by address** (D-80). **Members not moved stay at the higher unit.**
- **How many** geographic belongings a member may hold remains the organisation's rule (B-3).

**Scenarios added:**

| # | Scenario | Outcome |
|---|---|---|
| 19 | Organisation with only its root unit; 1,000 members admitted | All belong to the root unit (derived; nothing written per person) |
| 20 | Month 2: province X created; its creating decision allocates 300 members | 300 belong to X from the effective date; 700 stay in the root unit |
| 21 | Month 3: province Y created; 200 allocated | X 300, Y 200, root 500 |
| 22 | Month 4: Y creates districts D1 and D2; 80 allocated to D1, 70 to D2 | D1 80, D2 70, **Y keeps 50**; X 300; root 500 |
| 23 | A member's address is in X but no act placed them | **Stays in the root unit**; at most a suggestion (D-79, D-80) |
| 24 | The organisation's rules adopt a residence rule for placement | Placement follows the rule version in force, **recorded as the basis**; no retroactive move |
| 25 | D1 is dissolved later | D1's members' effective belonging falls back to Y (§10, D-17), not to the root |
| 26 | Election asks for "members of Y" at cut-off τ | **Y's own members, plus those of D1 and D2** (units at or below the scope, §38.5); Election decides entitlement |
| 27 | A new top unit N is inserted above the root R; 500 members sit in R with no recorded placement | **They stay in R** (now a child of N) unless the restructuring decision moves them (D-122; B-7 default "root as at admission") |
| 28 | A member admitted after N's insertion, with no placement | Belongs to N (the root at their admission) |

## 51. Organisation tactical design, step 3b: rule-based geographical allocation and unit closure (PROPOSED, 2026-11-10; extends §49 and §50)

**Status:** architect's proposal. **Not accepted; no implementation.**
- **Binding:**
  - D-79, D-80, D-114, D-116 … D-122, D-136, D-140, D-155 (rulings);
  - D-106 (accepted);
  - D-93, D-96, D-73 (ratified / ARB);
  - working decisions D-13 … D-19, D-27, D-29, D-30 (provisional).
- **Not binding:** D-115 is a **working direction (WD)**, applied as the default where the written rules are silent, and open to challenge (corrected per IR-OTD-01 L-3 and -C2).
- **Where this differs from §49 or §50, §51 governs for allocation and closure.**

### 51.1 Business explanation

**An organisation does not have to copy the map.** Province Y can run three districts (D1, D2, D3) although Geography lists four (D1 … D4). D4 has few members, so **Province Y's committee adopts a rule: "geographical D4 is looked after by our district D3."**

From then on, **every member whose recorded area is D4 belongs to organisational D3**, with no separate decision for each member. **Province X is untouched**; members with no area recorded, or in an area no rule covers, **stay where they are**: in Y if Y has placed them by area, otherwise in the root unit (D-114).

**Closing works the other way:**
- If D3 is closed, D3 stops existing from that date, and **its rule for D4 lapses.**
- The closing decision says where D3 and D4 now go. **If it says nothing, members whom the remaining rules place (for example in Province Y) get a `PlacementReviewNeeded` notice; only members whom no rule or act places are shown as "awaiting placement"**, counted meanwhile in the nearest active higher unit, until the competent body decides *(corrected per D-121)*.
- **Nothing is deleted:** history still shows that D3 existed, what it covered, and who was in it.

### 51.2 Four facts kept apart

| Fact | Meaning | Owner | Source of authority |
|---|---|---|---|
| **Geographical reference** | An official district (e.g. geographical D4) | Reference Geography (D-93) | Competent public authority |
| **Organisational unit** | A unit the organisation established (e.g. organisational D3) | Organisation (§49) | Decision under the written rules (D-106) |
| **Area allocation** | "Within scope S, members associated with area A belong to unit U" (e.g. scope Y: D4 → D3) | **Organisation holds it; the rule and the competence are Governance's** (rule recorded with its decision; R-15 rule copy) | Province committee by default **(D-115 WD)**, unless the highest committee decides otherwise; **the written rules prevail** |
| **Recorded geographical association** | The area the organisation records for a member, by an act: registrar's entry, member's declaration accepted, admission naming it. **An address may suggest it, never set it** (D-79, D-80) | Organisation (belonging) | The organisation's rules define what counts (**UNKNOWN per organisation**) |
| **Belonging** | The resulting place of the member | Organisation | **Derived** from allocations and association, **or** an individual act |

**Not to be confused with electoral division assignment (D-125, D-126):** an organisation's assignment of people to **electoral divisions** grants **no organisational belonging** and is read by Election only; it is **not designed here** and shares nothing with allocation rules except the D-79/D-80/D-82 discipline (**no address and no organisation area alone**).

**Coverage (D-80) versus allocation [AC]: related, but distinct facts.**
- **Allocation places members.** **Coverage says which areas a unit serves** (organising, campaigning, communication).
- **An allocation of A to U makes U cover A** (coverage is derived from allocations), but **a unit may also declare coverage it does not allocate members by** (e.g. a diaspora unit serving a country for outreach).
- **The two are not collapsed:** allocation changes belonging, while coverage alone never does (D-80).

### 51.3 How belonging is derived (descent rule) [TD]

For a member on date d, as known at τ:

1. **Start at the root of the geographic tree, or at the member's effective placement floor where one exists (step 4)** (IR-OTD-04 L-6; D-114; wing belongings are additional, IR-OTD-01 L-4). For a member without a recorded placement, the root **as at their admission**, carried forward by unit identity (D-122).
2. At the current unit S, if S has an **effective allocation** for the member's recorded area (or an enclosing area, per Geography's containment **valid at d, as known at the scheme's confirmed Geography position**, A-8, in the designated administrative scheme) to an **active** child unit U, **descend to U**; repeat. *(Organisation's own records are read as known at τ; Geography is read at the confirmed position recorded in those records, so one τ still fixes the whole answer.)*
3. **Stop** when no allocation applies. **The member's rule-derived belonging is the unit reached.**
4. *(Corrected 2026-11-10, IR-OTD-01 H-6; D-116, D-119.)* **An ordinary placement** (a placement act, which D-79 calls an "allocation act"; a transfer; an admission naming a unit) **is a floor**: descent continues from it. **Only an exception** (an act that expressly places the member notwithstanding the rules, granted by the authority of the overridden rule or higher, D-119) **fixes the unit** while it stands (D-116). **An exception ends only by** (a) an act ending it, or a later decision for that member (D-116: a later exception, placement, change-set placement or ratification), **made by an authority at least equal to the one that granted the exception**; (b) a later decision of an authority **equal to or higher than the one that granted the exception, expressly addressing it**; **a later general rule does not end it unless it says so**; or (c) the closure of its destination (then the member re-derives by the rules and gets `PlacementReviewNeeded`, or is flagged "awaiting placement" if nothing places them). **A lower body's later act never ends it** (D-116 read with D-119), **unless the organisation's written rules provide otherwise** (D-119).

**Properties:**
- **A rule in Y's scope never affects X** (scoping).
- **Units need not exist for every area.**
- **Members with no recorded area, or an uncovered area, stay at the deepest unit that placed them**: progressive growth needs no mass writes.
- **The result is a pure function of dated, recorded facts**, so it is reproducible at any (d, τ) (D-73).

### 51.4 Invariants

| # | Invariant | Problem prevented | Status | Boundary |
|---|---|---|---|---|
| **A-1** | **Within one scope, at most one *effective* destination per area per date** (an overridden allocation may still be recorded, D-140)**, the area taken as its canonical identity resolved at the scheme's confirmed Geography position (A-8)** (IR-OTD-01 H-3); within one scope **A-7 governs which matching allocation wins: authority first (D-136), then the most specific area among allocations of equal authority (or a lower allocation expressly authorised under A-7)** (by containment in the designated administrative scheme, valid at d, **as known at the scheme's confirmed Geography position**, A-8; corrected per IR-OTD-01-C3 C-1). A Geography publication that merges two allocated areas, or divides, ends or re-parents an allocated area **in a way that could alter a rule-derived result**, raises **AllocationReviewNeeded** (A-8) **Allocation areas are official references in the designated administrative scheme; allocating by an organisation's own area (D-82) is not supported, because Geography provides no containment for it** (IR-OTD-04 L-12; to be revisited with the area-rules release, D-147) | Ambiguous placement | [TD] | Allocation scheme of the scope unit |
| **A-2** | **A destination is an active direct child of the scope unit (A-3; corrected per IR-OTD-01-C C-7) on every date the allocation is effective**; an allocation whose destination is closed is **ineffective from the closure date (lapses)**; **a lapsed allocation never resumes under D-140** (IR-OTD-04 L-2) | Pointing to a non-existent unit | [TD] | Derived (no write) |
| **A-7** | **Choosing among matching allocations in one scope** ([AD] **D-136**; IR-OTD-01-C2 items 2 and 17): **(1) authority first:** an allocation made by a **higher** body governs every area its allocated area contains, **whatever their order**; a lower body's allocation for a contained area **does not take effect**, unless the organisation's written rules **expressly give the lower body that competence (an identifiable provision, recorded as the basis)**; **where that competence is unknown or not established, the lower allocation is refused and reported**. **A lower allocation adopted before the higher one is not ended: it has no effect while the higher stands and applies again when the higher ends, with `PlacementReviewNeeded` for members whose unit changes (D-140)**. **(2) Then specificity:** among allocations of **equal** authority (or a lower body's allocation expressly authorised under (1)), the **most specific** matching area wins, i.e. the smallest by containment in the **designated administrative scheme**, **valid at d, as known at the scheme's confirmed Geography position (A-8)**. **Single-parent containment comes from Geography's containment slot `(CONTAINMENT, unit, scheme)`** (not G-4), so the matching areas form a chain and the result is unique. **No authority is inferred from committee names, levels or containment** | A lower body narrowing a higher body's rule; ambiguous placement | **D-119, D-136**; Geography containment slot | Allocation scheme |
| **A-8** | **Geography never changes belonging by itself** (accepted baseline D-106 §6; S-8). **Each allocation scheme records the Geography position at which the organisation last confirmed it** (`confirmedAtPosition`). **The scheme's competent authority is the body or bodies of highest authority that have an effective allocation in the scheme, or the body the written rules designate as the scheme's competent authority; only that authority moves the position** — by adopting or changing an allocation, or by an explicit **ConfirmScheme** act, effective from its date. **Except for a body the written rules expressly designate as the scheme's competent authority (a competence for a contained area under D-136 does not by itself suffice; IR-OTD-04 L-4), an act of a lower body does not move the scheme's position while a higher body's allocation stands in the scheme** (D-119, D-136); **where several bodies share the highest authority, a move that affects their allocations needs their concurrence or the written-rules designee (D-155, binding default).** **Members whose unit changes as a result of a confirmation receive `PlacementReviewNeeded`** (IR-OTD-04 L-8). **A confirmation is effective from its date and never earlier; a back-dated effect needs the retroactive route of §49.7.** **A confirmation names a position already published when the act is recorded; a query at τ uses a confirmed position only if that confirmation is known at τ, so the position is always ≤ `positionAt(τ)`** (the position selects a map version inside the τ snapshot; it is not a second knowledge time). **Dependency:** A-8 relies on published content at a position never changing (Geography v3.11); **if Geography decides that a defective publication can be voided ([OQ] in Geography v3.11), that ruling must say what happens to schemes confirmed at that position **and to coverage references recorded at it (§49.6)** (IR-OTD-04 L-3).** **Descent reads containment and identity resolution for that scheme at its confirmed position, never at a later one.** A later Geography publication that could alter a rule-derived result raises **AllocationReviewNeeded** and **has no effect on belonging until the scheme's competent authority confirms the scheme at a newer position** (a recorded organisational act, effective from its date). Members recorded against a reference unknown at the confirmed position match no allocation and stay at their floor. *(Corrected 2026-11-10 after the consistency review: §51 read Geography "as known at τ", which let a Geography re-parenting or reconciliation change belonging without an organisational act, contrary to D-106 §6)* | The map changing people's units | **D-106 §6**, S-8, D-80 | Allocation scheme |
| **A-3** | **An allocation targets only a direct child of its scope** (D-119); a deeper scope's rules are therefore always reached, and a higher body overrides them only by deciding within that scope. **Unless the organisation's written rules provide otherwise** (D-119; corrected per IR-OTD-01-C item 7) | Rules bypassing each other | **D-119** | Allocation scheme |
| **A-4** | **An allocation never creates or ends membership**; it places only effective members (B-2) | Rules making members | D-103, D-114 | — |
| **A-5** | **An address or residence change never changes a recorded association by itself**; only an act does | Map or address deciding | **D-79, D-80** | PersonBelonging |
| **A-6** | **Every allocation has a basis:** the decision adopting it, its authority, the rule version | Unauthorised rules | D-106; D-115 | Allocation scheme |
| **C-1** | **Closing a unit with active children is inapplicable** unless the same change set moves, ends or re-parents each child | Orphaned units | **§49 S-1** (already) | Tree |
| **C-2** | **After closure, no effective belonging is the closed unit.** Rule-derived belongings re-derive by descent and get a **`PlacementReviewNeeded`** notice; members whom **no rule or act places** fall back to the nearest active higher unit (D-17) and are flagged **"awaiting placement"** (D-121). Always with an effective unit (D-114) | Members in a dead unit; silent reassignment | D-17 (WD), D-114, D-121 | Derived + read model |
| **C-3** | **Closure never deletes:** the unit, its periods, coverage, allocations and belongings stay queryable as at any earlier (d, τ) | Lost history | D-73; §49.8 | All |
| **C-4** | **Closure never ends membership and never creates belonging elsewhere by itself** | Side effects on Membership | D-103 | — |

### 51.5 Lifecycle

| Event | What happens | Decision needed | Owner |
|---|---|---|---|
| **Establish unit U** | Change set (§49.7) | Competent body | Governance decides; Organisation applies |
| **Adopt allocation** (scope S: A → U) | New allocation, effective from its date. **Refused (and reported) where a higher body's allocation stands in the same scope for the same area or for an area containing it** (D-119, D-136), **unless an identifiable provision of the written rules gives the lower body that competence**, recorded as the basis | Province committee by default (D-115), else per rules | Governance (rule); Organisation (allocation fact) |
| **Change allocation** (D4: D3 → D2) | **New allocation from date d**; the old one ends at d; history kept. **Refused if the deciding body is lower than the body whose allocation it would change** (D-119, whatever the order; corrected per IR-OTD-01-C item 2), **unless an identifiable provision of the written rules gives the lower body that competence** (D-119, D-136) (corrected per IR-OTD-04 M-1). **If a higher body's allocation is changed so that it no longer covers an area a lower allocation it overrode covers, that lower allocation applies again from d, with `PlacementReviewNeeded`** (D-140) | Same | Same |
| **Remove allocation** | Ends at d; members re-derive (stop at S). **Refused if the deciding body is lower than the body that made the allocation** (D-119), **unless an identifiable provision of the written rules gives the lower body that competence** (D-119, D-136) (corrected per IR-OTD-04 M-1). **If it was a higher body's allocation, any lower allocation it overrode applies again from d, with `PlacementReviewNeeded` (D-140)** | Same | Same |
| **Change effective date** | If a new decision: a new allocation. **If the date was entered wrongly: a record correction** (§49.8) | — | — |
| **Higher body overrides** (e.g. the highest committee in Y's scope) | Its decision on S's scope prevails from its date (D-119; D-115 only supplies the default allocation authority) **and continues to prevail over any later decision of a lower body on the same scope** (D-119), **except where an identifiable provision of the written rules gives the lower body that competence** (D-119, D-136; A-7; scenario 29). **Unless so authorised, Y's allocation has no effect where they conflict; if it was adopted before the higher allocation, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140). An allocation refused at adoption never applies** (corrected per IR-OTD-03 R-1) | The higher body | Governance |
| **Geography supersedes or corrects a reference** | **No allocation and no belonging changes** (A-8). **A division, an end, a reconciliation merging two allocated areas, or a re-parenting that could alter a rule-derived result raises AllocationReviewNeeded** (IR-OTD-01 M-4, -C item 10) | The scheme's competent authority (A-8) decides; it may confirm the scheme at the newer Geography position (**ConfirmScheme**, effective from its date); **members who move as a result receive `PlacementReviewNeeded`** (A-8) | Organisation (signal); Governance (decision) |
| **Member's recorded association changes** | Re-derived from the new association's effective date | The act recording it | Organisation |
| **Close unit U** | Change set ends U (C-1 children handled); **allocations pointing to U lapse (A-2)**; the decision **may redirect** them (new allocations) and **may place** U's members; **otherwise rule-derived members re-derive and get `PlacementReviewNeeded`, and only members whom no rule or act places are flagged "awaiting placement"**, with interim effective belonging at the nearest active higher unit (C-2, D-121) | Competent body | Governance decides; Organisation applies and produces the closure impact report |
| **Correct a wrongly recorded closure** | Record correction; derived belongings for affected dates recomputed **as known after the correction**; earlier τ answers unchanged | — | Organisation |

**Closure is not the mechanical inverse of establishment:**
- establishment needs only the new unit;
- **closure needs the destination of everything that pointed to the unit**, which may require new decisions.

### 51.6 Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | Only the root unit; 1,000 members | All in root (D-114) |
| 2 | Province X established; root-scope allocations: X's areas → X | Members recorded in X's areas → X; others root |
| 3 | Province Y established; root-scope allocations: Y's areas → Y | Y's area members → Y; X unchanged; rest root |
| 4 | Y establishes D1, D2, D3; Y-scope allocations geo D1 → D1, D2 → D2, D3 → D3 | Those members descend; **members in geo D4 stay in Y** |
| 5 | **Y's committee adopts geo D4 → D3** | D4 members → D3, **no individual decisions**; **D3 now covers D3 and D4**; X untouched |
| 6 | A member's address is in D4, but no association is recorded | **Stays where the recorded facts place them** (A-5); at most a suggestion |
| 7 | The registrar records a member's association as D4 | From that date: D3 |
| 8 | Member moves; the association is changed to D1 by an act | From that date: D1 |
| 9 | Membership ends | Not counted (B-2); allocation irrelevant (A-4) |
| 10 | An individual act places a D4 member in D2 | **D2 while it stands** (Q-1 default) |
| 11 | The highest committee decides geo D4 → D2 | From its date: D4 members → D2; Y's D4 → D3 ends there |
| 12 | Geography reconciles two D4 references | **No change to belonging** (A-8: the scheme still reads identity at its confirmed position). If at most one carries an allocation in the scope, confirming the scheme at the new position raises **no allocation conflict**, **but members recorded against the reference without the allocation now resolve to the allocated one and may move from the confirmation's date, with `PlacementReviewNeeded`** (A-8, as scenario 32); **if both do, AllocationReviewNeeded**, and the scope decides before confirming (corrected per IR-OTD-04 M-2) |
| 13 | Geography divides D4 into D4a and D4b | **AllocationReviewNeeded; no change to belonging** (A-8). Members recorded against D4 keep matching the D4 allocation; members recorded against D4a or D4b (unknown at the confirmed position) stay at their floor until the scope allocates them and confirms the scheme |
| 14 | **D3 closed; the decision redirects geo D3 and D4 → D2** | D3 ends; its allocations lapse; new allocations → D2; members re-derive to D2 |
| 15 | **D3 closed; the decision says nothing about destinations** | Rule-derived D3/D4 members stop at Y and get **`PlacementReviewNeeded`**; members placed in D3 whom no remaining rule places are flagged **"awaiting placement"**, counted in Y meanwhile (D-121) |
| 16 | D3 closed; a new unit D3′ is established in the same change set with geo D3 and D4 | Members descend to D3′ |
| 17 | **Province Y closed while D1 … D3 are active** | **Inapplicable** unless the change set re-parents or ends each district (C-1) |
| 18 | Y closed; districts re-parented under the root | Root-scope allocations to Y lapse; the decision may allocate Y's areas directly to D1 … D3 at root scope |
| 19 | Allocation D4 → D3 future-dated to next month; D3 closed this month | The allocation **lapses at closure by derivation (A-2)** and appears in the **closure impact report** produced when the change set is applied; **there is no write-time check** (corrected per IR-OTD-01 M-6) |
| 20 | Closure recorded with the wrong date | Record correction; earlier τ answers unchanged |
| 21 | Historical query: "members of D3 on 1 March" before and after closure | Same answer for the same (d, τ); **D3 and its allocations still visible** |
| 22 | Wing unit closed | Same rules within the wing's tree |
| 23 | Member with two recorded associations (**only historical or imported data**: B-8 refuses a second) | **Flagged for an act**; meanwhile they stay at the deepest unit both areas agree on (corrected per IR-OTD-01 L-9) |
| 24 | Member with no recorded association | Stays at the unit an act placed them in, else root |
| 25 | Province Y's committee tries to remove a highest-committee allocation in Y's scope | **Refused** (D-119, whatever the order); reported. **Unless an identifiable provision of the written rules gives Y's committee that competence** (D-119, D-136): then it takes effect, the provision recorded as its basis |
| 26 | A district committee issues a later placement for a member excepted by the province committee | **Does not end the exception** (lower body, D-119); reported. **Unless the written rules provide otherwise** (step 4, D-119) |
| 27 | Highest committee: geo D4 → D2 in Y's scope; later Y's committee: geo D4 → D3 (same area) | **Refused** (D-119); reported. **Unless an identifiable provision of the written rules gives Y's committee that competence** (D-119, D-136; as scenario 29) |
| 28 | Highest committee: geo D4 → D2; later Y's committee: ward W (inside D4) → D3 | **Refused** (D-136, competence not established); W members stay in D2; reported |
| 29 | Same as 28, but the written rules expressly let province committees allocate wards within districts | **Takes effect**, the provision recorded as its basis; W members → D3 (specificity among authorised allocations) |
| 30 | Same as 28; the organisation's rules are not on file | **Refused** (competence unknown, D-136); reported |
| 31 | Y's committee: W → D3 first; later the highest committee: D4 → D2 | From the later date W members → D2 (higher body prevails whatever the order); **Y's W allocation is not ended but has no effect while the higher allocation stands** (D-140), reported. **When the higher allocation ends (removed, superseded by an allocation that no longer covers W, or lapsed by closure of D2), Y's allocation applies again from that date and the W members who move receive `PlacementReviewNeeded`** (D-140). **An allocation refused at adoption never applies**; **nor does one that has itself lapsed (A-2)** |
| 32 | Geography re-parents ward W from D4 to D5; Y's scheme allocates D4 → D2 and D5 → D3 | **No change** (A-8): W members stay in D2; AllocationReviewNeeded. **If Y's committee confirms the scheme at the newer position, W members move to D3 from that act's date**, with PlacementReviewNeeded |
| 33 | Highest committee allocates geo D4 → D2 in Y's scope; Geography re-parents W from D4 to D5; **Y's committee then adopts an unrelated geo D6 → D3 at the newer position** | Y's act is a valid allocation (D6 is not covered by the higher allocation) but **does not move the scheme's confirmed position while the higher allocation stands** (A-8, D-119/D-136): the higher allocation still covers W, and Y's allocation is read at the confirmed position — if D6 is unknown there, it matches no member (A-8). **Only the highest committee, as the scheme's competent authority, may confirm the scheme at the newer position**; reported |

### 51.7 Cross-context

| Context | Provides to Organisation | Consumes from Organisation | Never |
|---|---|---|---|
| Governance | **Decisions and rules:** establishment, closure, allocation adoption, competence under the written rules (D-12); the default authority (D-115) | Structure and belonging as at τ (D-29, provisional) | Organisation deciding competence |
| Membership | Coverage C1/C2 (who is a member, when) | — | Allocation affecting membership |
| Reference Geography | References, containment, `resolve`, `RevisionPublished` | — | Geography changing allocation or belonging |
| Election | — | Belonging as at (cut-off, τ) | Organisation deciding entitlement |

### 51.8 Where it sits

- **Allocation scheme per scope unit:** its own consistency boundary (A-1, A-6). It is **not inside the Tree aggregate**, because allocations change more often than structure and must not take the tree version.
- **Destination validity (A-2) is derived**, so closure needs no cross-aggregate write.
- **Recorded geographical association** belongs to **PersonBelonging** (§50).
- **The descent rule is a domain service** over the scheme, the association, the tree and Membership's coverage.

### 51.9 Open questions for the Principal

**Q-1. Individual act versus rule: which prevails?** *(Decided: D-116; refined by §51.3 step 4: a placement is a floor, only an exception fixes the unit.)*
- **Situation:** a rule places D4 members in D3, but the committee individually places one D4 member in D2.
- **Alternatives:**
  - (a) **the individual act prevails while it stands** (the specific over the general);
  - (b) the rule prevails;
  - (c) each organisation's rules decide, with no platform default.
- **Recommended: (a), as the default where the rules are silent.**
- **If left open:** each conflict needs a decision, and the derivation cannot run unattended.

*(Decided: D-117, option (a).)* **Q-2. Does an allocation apply to existing members from its effective date, or only to new placements?**
- **Situation:** D4 → D3 is adopted while D4 members already sit in Y.
- **Alternatives:**
  - (a) **from its effective date to all qualifying members** (what the commission describes: "no individual decision for each member");
  - (b) to new members only.
- **Recommended: (a).** It matches the commission. **No retroactive effect:** only from the date.

### 51.10 Readiness

**Ready for independent tactical review once Q-1 and Q-2 are answered.**
- **[TU]:** Geography containment for divided areas (scenario 13); the closure impact report as a read model.
- **Not implementation-ready.**

### 51.11 Adversarial review of §51 (2026-11-10; by its author, therefore **not independent**)

**Assumptions:** Q-1 (a) and Q-2 (a) **[P], pending the Principal**. **§51.12 states what changes if either is rejected.**

**Confirmed sound [AC]:**
- the five facts kept apart (§51.2);
- scoping: a rule in Y never touches X;
- the descent rule as a pure function of dated, recorded facts;
- allocation lapse by derivation (A-2), with no cross-aggregate write;
- children handled explicitly on closure (C-1, from S-1);
- closure never deletes (C-3);
- allocation and closure never touch membership (A-4, C-4).

**Findings:**

| # | Finding | Kind | Correction |
|---|---|---|---|
| **R-1** *(superseded by A-7 in §51.4: authority first, D-136)* | **Nested areas within one scope are not deterministic.** In scope Y, geo D2 → D2 and ward W (inside geo D2) → D3 both match a member in W. A-1 covers only the *same* area | Defect [AC] | **A-7: within one scope, the most specific matching area wins**, i.e. the smallest area, by Geography's containment as known at τ. Containment is single-parent per scheme (the containment slot `(CONTAINMENT, unit, scheme)`; corrected per IR-OTD-01 L-1, not G-4), so the result is unique. **Geographical specificity decides inside a scope; organisational descent decides between scopes**, so the two are never confused |
| **R-2** | **"Individual act" covers two different things.** An admission naming Y (D-79) would, under Q-1 (a), be read as an exception that blocks every later allocation, **contradicting Q-2 (a)** (existing members in Y move to D3) | **Contradiction** [AC] | **Two kinds of individual act:** **(i) placement** (D-79: allocation, transfer, admission naming a unit) sets a **floor**: the member is in that unit **or below it by its rules**; descent continues from it. **(ii) exception**: an act that **states** it places the member **notwithstanding the allocation rules** sets a **fixed unit**. **Only exceptions take Q-1 precedence** |
| **R-3** *(superseded in part by D-121: the flag applies only where no rule or act places the member; others get a `PlacementReviewNeeded` notice)* | **C-2 flags only explicit belongings.** Closing Y with its districts re-parented under the root makes Y's allocation scheme lapse. **Thousands of rule-derived members would silently stop at the root** | Defect [AC] | **C-2 revised:** every member whose effective belonging changes **because of a closure** is **reported and flagged "awaiting placement"**, unless the closing decision itself states their destination. The decision may do so by new allocations, by transferring the closed scope's allocations to the new parent, or by placements |
| **R-4** | **The exception lifecycle is undefined** | Gap | **Exception:** basis act and authority; effective from; **ends by** (1) an act ending it, (2) a later exception or placement for the same member, (3) **a later decision by an equal or higher authority that expressly addresses it**, (4) the destination's closure (then the member **re-derives by the rules** and is flagged, R-3). **A later general rule does not end it unless it says so** |
| **R-5** | **Several recorded associations make placement ambiguous** (scenario 23) | Gap | **B-8: at most one placement-relevant recorded association per member per date** (the one the organisation's rules use). Others are profile information (D-101). **A second is refused unless it replaces the first from a date** |
| **R-6** | **Re-admission after a real end** | Clarification | **Rule-derived belonging re-applies from re-admission** (it is derived from current facts). **Explicit placements and exceptions do not revive** (§12.2, V-36) |
| **R-7**  *(superseded for allocation by A-8: schemes read Geography at their confirmed position)* | **Scenario 13 [TU] resolved** | Clarification | Matching is by **reference identity or containment as known at τ**. A member associated with the ended reference D4 keeps matching the D4 allocation. Members associated with D4a or D4b match only if allocated, so until then they stop at Y, with **AllocationReviewNeeded** |
| **R-8**  *(superseded for allocation by A-8: schemes read Geography at their confirmed position)* | **Which Geography time** | Clarification | **Geography is read at the operation's shared τ** (`positionAt(τ)`, D-93 §6); allocations keep their original references (§49.6) |

**Aggregate boundaries:**
- **Allocation scheme per scope unit:** holds A-1 and A-7 internally.
- **PersonBelonging:** holds placements, exceptions, the placement-relevant association (B-8) and transfers.
- **Tree:** unchanged.
- **Belonging is derived** by the descent service.
- **No new aggregate and no new transaction.**

**Fallback at every level:**
- **the nearest active ancestor at d**, even when several ancestors close in one change set;
- **for a closed wing, the wing tree's nearest active ancestor**;
- **never across trees.**

### 51.12 If Q-1 or Q-2 is rejected

| Answer | Consequence |
|---|---|
| **Q-1 (b): the rule prevails** | Exceptions cannot exist; only placements (floors). Every special case needs a rule change |
| **Q-1 (c): no default** | Every conflict between an exception and a rule is **unresolved until the organisation's rules say**: the member is flagged, with interim belonging = the rule result |
| **Q-2 (b): new members only** | **Each existing member's placement must be frozen at adoption** (written per person); the derivation is no longer a pure function of current rules; mass writes return |

### 51.13 Readiness

**Tactical design needs further work only in the sense of recording R-1 … R-8**, which are now written above. **With Q-1 and Q-2 decided, §51 is ready for Principal tactical-design acceptance**, preferably after an independent (non-Claude) check. **Not implementation-ready.**

**Update (2026-11-10): Q-1 and Q-2 decided, as D-116 (exception wins; placements are floors) and D-117 (rules apply to all qualifying members from their date).** §51.12 is now history. With R-1 … R-8, **§51 is ready for the Principal's tactical-design acceptance**, preferably after an independent (non-Claude) check. Not implementation-ready.

### 51.14 Corrections after the independent review IR-OTD-01 (2026-11-10)

**Review:** `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01-independent-review.md` (deepseek-chat, not Claude; it disclosed that it had reviewed the people register earlier the same day). **Verdict: READY AFTER CORRECTIONS.**

**Decided by the Principal:** D-118 (H-1), D-119 (H-2, M-1), D-120 (H-4), D-121 (M-5).

**Applied in place:**
- §51.3 step 4 (H-6);
- S-6 marked withdrawn;
- A-1 over canonical identities with A-7 (H-3, M-4);
- A-3 (D-119);
- C-2 (D-121);
- §50.11 scenario 10 (H-5).

**Further corrections:**

| # | Correction |
|---|---|
| H-1 | **B-3 default limit = one geographic member belonging per date (D-118)**; a second is refused unless the rules allow it. Where the rules allow more, every such belonging counts for its unit (no tie-break needed) |
| H-3 | **Interim rule:** while an AllocationReviewNeeded from a reconciliation is open, **each member keeps the destination of the reference they were recorded against**; scenario 12 corrected |
| H-2 | Lifecycle checks authority (D-119): **a change, end or exception made by a lower body than the decision it would affect is refused** and reported |
| H-4 | **S-9 (D-120):** the root unit exists from before the first admission; **a change set closing the root while membership coverage exists is inapplicable**. B-7 therefore always has a root |
| M-2 | A cross-tree move with continuity is **one change set over both trees, applied in one transaction, both tree versions checked** (as rare as any structural change); the unit's allocation scheme moves with it only if its destinations remain its descendants, otherwise it lapses (reported). **[TU]** proof obligation for the implementation |
| M-3 | **Organisation has one knowledge boundary** (`closedThroughTime`) **covering structure, belonging and allocation schemes**; one close covers all three |
| M-4 | **The containment used is the designated administrative scheme's** (Geography §13); a containment change that alters a rule-derived result raises **AllocationReviewNeeded** |
| M-6 | A-2 is derived; **the closure impact is a report**, produced when the closure change set is applied, to the deciding body (Governance) and the organisation's registrars, not a write-time check |
| M-7 | **Vocabulary:** **"allocation rule"** = an area rule (§51); **"placement"** = an individual act (D-79). D-114's "an allocation in the creating decision" means **either** an allocation rule adopted with the change set **or** placements listed in it; the decision states which |
| M-8 | B-5 restated as a **record-time assertion**; later invalidity is handled by C-2, never by rewriting |
| L-1 | Single-parent containment comes from the containment slot `(CONTAINMENT, unit, scheme)`, **not G-4** |
| L-2 | Containment is the one **valid at d, as known at τ** |
| L-3 | D-115 is a **working direction (WD)**, not binding |
| L-4 | Descent starts at **the geographic tree's root**; wing belongings are additional |
| L-5 | **Placed members** resolve by **unit identity**, so a new root above the old one does not move them. **Members with no recorded placement also stay with the old root (now a child) unless the restructuring decision moves them (D-122): no silent displacement.** Implementation consequence [TD]: the default derivation uses **the root as at the member's admission**, carried forward by unit identity |
| L-6 | B-7 now rests on **S-9 (D-120)**, not on the provisional S-0 |
| L-7 | Label collisions: this section's corrections are cited as **IR-OTD-01 H-n / M-n / L-n**; §51.4's A-1 is not D-112's note A-1 |
| L-8 | B-1: "a residence move is never a basis; **a residence rule the written rules adopt may be** (D-79, D-114)" |
| L-9 | Scenario 23 (two associations) arises only from records predating B-8 or from imports; such records are flagged for an act |

**Review of the review (2026-11-10):** the author re-read IR-OTD-01 §4 and §5 in full and found that several corrections had again reached only this table, not the text. **§51.1, R-3, scenarios 12, 19 and 23 are now corrected in place**; the H-3 interim rule and the L-5 rule for unplaced members are added. **Disagreement with the review:** M-1's proposal (the highest committee may allocate past a province) is **not adopted**; D-119 (override within the scope) governs.

**Readiness:** **with these corrections, §49 … §51 are ready for the Principal's tactical-design acceptance.** Not implementation-ready.

**Update (2026-11-10, D-122):** D-118 confirmed (one geographic member unit by default); no silent displacement by a new root (L-5 corrected); the closure notice is named `PlacementReviewNeeded`. The Principal DDD Architect's five positions are thus all reflected: B, D, E as D-119 and D-121, C as D-120 + D-122, A as D-118 (kept by explicit ruling).

**Update (2026-11-10): IR-OTD-01-C (confirmation review, NOT CONFIRMED: 10 confirmed, 5 partial, 0 missing; contradictions C-1 … C-8) addressed in place:** items 2, 4, 7, 10, 15 and C-1 … C-8, each marked "corrected per IR-OTD-01-C" at its line. Report: `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c-confirmation-review.md`.

**Update (2026-11-10, full consistency pass after IR-OTD-01-C):** further residues corrected in place: "allocation" in the individual sense in §50.3, §50.4, §50.5, §50.10, §50.11 and §50.15 (now "placement" or "change-set placement"); the exception lifecycle with the D-119 authority check (§51.3 step 4); the authority check on "Adopt allocation"; the D-121 flag in the fallback rows and scenarios 5 and 15; new scenarios §50.15 27–28 (new root) and §51.6 25–26 (authority).

**Update (2026-11-10): IR-OTD-01-C2 (second confirmation, NOT CONFIRMED: 15 of 17; reviewer deepseek-chat again, independence limit disclosed) addressed in place:** A-7 added to §51.4 (authority first, then specificity; containment-slot citation); the Adopt row refuses a lower body's allocation inside a higher body's allocated area; step 4 names the granting authority, uses D-116's "later decision for that member", states that a later general rule does not end an exception, and carries D-119's written-rules clause; Q-2 marked decided; D-115 moved out of the Binding list. Report: `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c2-confirmation-review.md`.

**Update (2026-11-10, D-136):** nested areas decided: authority before specificity, with the written-rules competence exception and refusal where competence is unknown. Applied in A-7, the Adopt row and scenarios 27–31 (§51.6).

**Update (2026-11-10): IR-OTD-01-C3 (narrow confirmation: 6 confirmed, 1 partial) addressed:** A-1 now defers to A-7 (authority first); scenario 31 states that a lower allocation adopted before a higher one has no effect while the higher stands and applies again if it ends, while a refused allocation never applies; the §51.2 note adds "no organisation area alone"; R-1 marked superseded. Report: `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c3-confirmation-review.md`.

**Update (2026-11-10, D-140):** an overridden lower allocation resumes when the higher one ends, with `PlacementReviewNeeded`; applied in A-7, the Remove row and scenario 31.

**Update (2026-11-10, consistency review):** **A-8 added.** §51 read Geography "as known at τ", so a Geography re-parenting or reconciliation would have changed rule-derived belonging without any organisational act, **contrary to the accepted baseline D-106 §6** ("A change in published Geography never changes belonging by itself"). Each allocation scheme now reads Geography at its **confirmed position**, changed only by an organisational act (ConfirmScheme or an allocation change). A-1, step 2, the Geography rows in §49.6 and §51.5, and scenarios 12, 13 and new 32 corrected. **This supersedes the part of IR-OTD-01 M-4 that accepted an immediate effect with a signal.**

**Update (2026-11-10, IR-OTD-02 C-1 and C-2):** the Higher-body row now states D-140's rule — the lower allocation **has no effect** where it conflicts and **applies again when the higher ends**, with `PlacementReviewNeeded`; an allocation refused at adoption never applies. **B-8** (at most one placement-relevant recorded association per member per date) added to §50.6, with its §50.7 boundary. Report: `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-02-fresh-confirmation.md` (NOT CONFIRMED at the time; C-1 and C-2 applied here).

**Update (2026-11-10, IR-OTD-02 C-3):** A-8 now names the scheme's competent authority — the body or bodies of highest authority with an effective allocation in the scheme, or the written-rules designee — and only that authority moves `confirmedAtPosition`. A lower body's act does not move it while a higher allocation stands (D-119, D-136); where several bodies share the highest authority, a move that affects their allocations needs their concurrence or the designee. This closes the path by which an unrelated lower-body adoption at a newer Geography position could narrow a higher allocation's coverage. The §51.5 Geography row points to A-8 for the confirming authority; scenario 33 added in §51.6.

### 51.15 Consistency demonstration (2026-11-10; author's analysis, not normative)

*For each case: the answer given by the invariants, the lifecycle tables and the descent algorithm. "=" means all three agree.*

| # | Case | Invariants | Lifecycle (§51.5) | Algorithm (§51.3) | Agree |
|---|---|---|---|---|---|
| 1 | Higher allocation, then lower for the same area | A-1, A-7: lower without effect | Adopt: refused | Only the higher is effective | = |
| 2 | Lower, then higher (same or contained area) | A-7, D-140: lower without effect while higher stands | Higher-body row: prevails whatever the order | Higher chosen by A-7 step (1) | = (scenario 31) |
| 3 | Same-area conflict, equal authority | A-1: one destination per area per date | Change: the new ends the old at d | One allocation effective | = |
| 4 | Nested areas, higher above lower | A-7, D-136: refused unless a written-rules provision | Adopt: refused | Higher's destination | = (28) |
| 5 | Nested areas, equal authority | A-7 (2): most specific | — | Smallest matching area | = |
| 6 | Written-rules exception | A-7 with a recorded provision | Adopt: accepted, provision as basis | Lower's destination | = (29) |
| 7 | Competence unknown | A-7: refused, reported | Adopt: refused | Higher's destination | = (30) |
| 8 | Higher allocation removed or lapses | D-140: lower resumes, `PlacementReviewNeeded` | Remove row: lower applies again; closure row: re-derive | A-7 sees only the lower | = (31) |
| 9 | Destination unit closes | A-2: allocation lapses; C-2 | Close row: re-derive or "awaiting placement" | Descent stops above | = |
| 10 | Earlier lower allocation overridden | D-140: not ended | Higher-body row | Ignored while higher stands | = |
| 11 | Membership coverage changes | B-2: counts only within coverage; B-7 while covered; S-9 root guard | Membership facts never written by Organisation | Effective belonging filters by C2 at τ | = |
| 12 | Geography containment changes | **A-8: no effect until ConfirmScheme**; AllocationReviewNeeded | Geography row: no change | Reads the confirmed position | = (32) |
| 13 | Unit re-parented or closed | S-1 … S-5, S-9; C-1, C-2 | Change set; close row | Descent through the tree as known at τ | = |
| 14 | Exception versus general allocation | D-116, D-119: exception fixes the unit; general rule does not end it | — | Step 4: exception overrides | = |
| 15 | Historical query at τ₁ vs τ₂ | One knowledge boundary (§49.8 (0)); A-8 positions are recorded facts | Records never rewritten | Pure function of records as known at τ | = |

**No case gives different answers across the three.** **The one contradiction found in this pass, Geography read at τ against D-106 §6, is corrected by A-8.**

**Update (2026-11-10, IR-OTD-03):** R-1 fixed (the Higher-body row now carries the D-119/D-136 written-rules exception, agreeing with A-7, the Adopt row and scenario 29). Low notes: A-1 says *effective* destination and uses A-8's "could alter" trigger; the Change row states D-140 resumption; the §51 Binding list names D-116 … D-122, D-136, D-140.

**Update (2026-11-10, deepseek-chat check of A-8 vs one shared τ):** A-8 now states that a confirmation names an already-published position and is used only when known at τ, and records the dependency on Geography's open question about defective publications.

**Update (2026-11-10, IR-OTD-04, seventh deepseek-chat review, not independent; NOT VALIDATED: M-1, M-2 + L-1 … L-12):** M-1 (written-rules exception in the Change and Remove rows and scenarios 25–27) and M-2 (scenario 12, §50.11 scenario 15 and §49.6 [AC]: a later confirmation can move members) fixed. L-1, L-2, L-3, L-4, L-6, L-7, L-8, L-9, L-10, L-11, L-12 fixed in place; A-8 states that a confirmation is not retroactive except by the §49.7 route; §51.11 R-7 and R-8 marked superseded. **L-5 decided: D-155** (binding default; cited in A-8).

**Status note (2026-11-10, after acceptance):** **§49–§51 ACCEPTED as the Organisation tactical baseline (D-156**; act `docs/publicdigit/reviews/2026-11-10-minimum-package-tactical-designs-acceptance-act.md`), resting on self-review by one model, not independent validation. **Building §51's area rules requires an independent review first.** The "proposal" status lines in §49–§51 are superseded by this note.
