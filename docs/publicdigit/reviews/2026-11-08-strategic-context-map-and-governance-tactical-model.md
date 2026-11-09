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
