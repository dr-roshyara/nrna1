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
