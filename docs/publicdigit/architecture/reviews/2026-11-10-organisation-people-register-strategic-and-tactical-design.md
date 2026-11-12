# Organisation people register: strategic and tactical design (proposal, revision 3)

| | |
|---|---|
| **Status** | **PROPOSED design, revision 3.** Decides nothing beyond the Principal rulings it cites; ratifies nothing; authorises no implementation. **Not independently reviewed** (Claude family): an outside review is required before acceptance. |
| **Commissions** | "Organisation People Register — Strategic Completion and Tactical DDD Design" (revision 1) and "Organisation People Register — Strategic Decisions and Tactical DDD Validation" (revision 2), Principal, 2026-11-10. |
| **Revision 3 changes** | D-111 (single platform identity, independent organisational data ownership) added (§2, §6.2 I-15); new §13 tests its implications for Identity, Membership, Election and Finance |
| **Revision 2 changes** | rulings D-107…D-110 incorporated (§2, §3); **aggregate boundary revised**: affiliations moved out of `OrganisationPerson` into their own aggregate (§9); operations, contracts and scenarios added (§5, §7, §8); duplicates split into three cases (§8.4); P-I1/BR-10 comparison for the external reviewer (§10). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`. Uncommitted: the decision record (D-100…D-110, partly from another session), the business-rules amendment note, the baseline (accepted as D-106; untracked file). No code changed. |

**Labels:** **FACT** · **RAT** (ratified; ARB acts per D-94) · **WD** (binding Principal ruling or working decision) · **APP** (approved business rule) · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. In business language
Every organisation on PublicDigit keeps **its own private register of the people it knows**: guests, workers, imported voters, members, applicants, including people who have never used PublicDigit. For each person, the register holds the organisation's **own profile** (name, contact, address as the organisation recorded them) and the **relationships the organisation itself has recorded**. Separately, the organisation decides **who may enter its digital workspace**.

**A person registers with PublicDigit once** and uses that one account for every organisation they deal with (D-110). Each organisation still keeps its own record and decides about its own workspace.

The register never decides:
- who is a formal member: Membership does; the register only reflects it (D-103);
- who is the same human across organisations: Identity does;
- who may vote: Election does (D-109);
- who holds authority: Governance does.

## 2. Principal rulings and their provenance
| Ruling | Content | Status | Provenance |
|---|---|---|---|
| D-100 | a platform record is not an Organisation | **WD** | direct, 2026-11-10 |
| D-101 | each organisation owns its affiliations and person profile, including address | **WD** | direct, 2026-11-10 |
| D-102 | Identity's boundary (account, authentication, claim, discovery mechanism) | **WD** | accepted note, 2026-11-10 |
| D-103 | "Member" reflects Membership; never asserted | **WD** | accepted note (B-2) |
| D-104 | workspace access is the organisation's own fact | **WD** | accepted note (B-3) |
| D-105 | baseline corrections; rulings stay separate acts | **WD** | accepted note (B-1, B-4) |
| D-106 | Organisation baseline rev. 2c accepted (eight §13 conclusions) | accepted act | direct, 2026-11-10 (another session) |
| **D-107** | **registration creates an Account only; the bootstrap holds no affiliations**; supersedes the "User affiliation" parts of BR-2, BR-8, BR-12 | **WD** | accepted note §8 (O-1 A) |
| **D-108** | **"User" is not a standard capacity**; a person may be registered with no capacity | **WD** | accepted note §8 (O-2) |
| **D-109** | **in election-only organisations the voter-related relationship is the organisation's; Election consumes it; it never creates entitlement** | **WD** | accepted note §8 (O-3 A) |
| **D-110** | **one registration, one Account, many organisations**; not repeated per organisation; uniqueness only through Identity's explicit act | **WD** | accepted note §9 |
| **D-111** | **single platform identity; each organisation's business data (people, affiliations, access, membership, election and finance records) belongs to that organisation**; the bounded contexts keep their rules; logical separation (a physical database per organisation stays an open tenancy decision) | **WD** | accepted note, 2026-11-10 (with recorder's precisions) |
| P-I1 | Person and `PersonRef` timing | **deferred** | accepted note §8: until an independent review that includes BR-10 |

**The recorder read "I accept the recommendations written in the document" as the explicit ruling for D-103…D-110.** The Principal may withdraw that reading.

## 3. Reconciled business rules
| Rule | Status after reconciliation | Basis |
|---|---|---|
| BR-1 (PublicDigit is the default organisation) | **clarified**: the bootstrap record is platform infrastructure, not an Organisation (D-100; BR-12) | D-100 |
| BR-2, BR-8 | **"User affiliation" superseded**: registration = an Account only. BR-8's voter-relationship sentence stands. | D-107; amendment note appended to the rules record |
| BR-3 | stands ("User" now only means "holds an Account") | — |
| BR-4 | stands; strengthened by D-110 (one Account for several organisations) | D-110 |
| BR-5, BR-6 | **stand; they require this capability** (record without an account; voter before registering) | — |
| BR-7 | stands (voting needs an account; an Election rule) | — |
| BR-9 | **answered** for ownership by D-101 | D-101 |
| BR-10 | **unchanged, open with P-I1**; "resolves" is not implementable without matching (D-75), so BR-10 in practice means eager **creation** (§10) | XD-3 §2.6 |
| BR-11, BR-13, BR-13a | stand | — |
| BR-12 | registrants not affiliated with the operator organisation: **stands**; "the bootstrap holds the User affiliation": **superseded** | D-107 |
| D-101's capacity list | "User" removed (D-108); "Member" is a reflection (D-103); "voter" is the organisation's relationship consumed by Election (D-109) | D-103, D-108, D-109 |

**Accepted baseline D-106:**
- Its §10 still lists BR-2, BR-8, BR-12 as approved rules, and its §3 lists "User" among the capacities.
- D-107 and D-108 govern where they conflict.
- **REC:** an **explicit amendment note** on the baseline, referencing D-107/D-108, approved by the Principal. It has **not** been applied here, because an accepted baseline is not edited silently.

## 4. Strategic domain model

### 4.1 Concepts
| Concept | Meaning | Owner |
|---|---|---|
| **Organisation person record** | the organisation's own record of a human it knows: who they are *to this organisation* | Organisation (D-101) |
| **Person profile** | the organisation's version of name, contact points, address as supplied (with a Geography reference where resolved); one organisation's version is independent of another's | Organisation (D-101) |
| **Affiliation** | a recorded relationship with the organisation **in a capacity**, with a period, a basis act and an actor | Organisation (D-101) |
| **Capacities** | Worker, Guest, voter (D-109) and later organisation-specific ones (UNKNOWN); **Member is a reflection, not a capacity anyone records** (D-103); **no "User"** (D-108) | Organisation, within those rulings |
| **Workspace access** | permission to work in the organisation's digital workspace | Organisation (D-104); Authorization enforces |
| **`PersonRef`** | Identity's opaque code-number for this human, scoped to this organisation | Identity issues and resolves; the record holds it |
| **Unit belonging** | the person's place in a unit or wing | Organisation, separate capability |
| **Formal membership** | the authoritative fact and its time coverage | Membership (RAT) |
| **Electorate, eligibility, entitlement** | who may vote, at τ | Election (RAT boundary) |

### 4.2 What a `PersonRef` is and is not
- **It is** the link that lets Identity connect this organisation's record to a human's Account (claim), and lets Membership and Election refer to the person without names.
- **It is not:**
  - proof of identity (the profile is not either);
  - proof of membership, affiliation, permission or authority (baseline §9);
  - a key that any organisation can use to learn about another (only Identity resolves it; XD-3 §2.3).
- **Its slot may be empty** (no reference issued yet; P-I1 timing).

### 4.3 Affiliation capacities differ in meaning and evidence
| Capacity | Business meaning | Typical basis (organisation's rules decide; P) | Ends by |
|---|---|---|---|
| **Worker** | works for the organisation (staff, volunteer role) | an engagement decision by the competent officer | decision; end of engagement |
| **Guest** | takes part without membership (observer, invited participant) | an invitation or officer decision | expiry; decision |
| **voter** (election-only organisations, D-109) | is on the organisation's list of people it puts forward for its elections | an **authorised import** or an officer decision; scope per election is **open (O-7)** | removal decision; election-specific end |
| **Member** | **not recorded**: shown from Membership's time coverage (D-103) | — | — |

**No universal rules beyond this table:** the actual evidence per capacity follows each organisation's written rules (D-97). Unknown until statutes are on file.

## 5. Context boundaries and collaboration contracts
| Relationship | Fact and owner | Kind | Never crosses |
|---|---|---|---|
| Organisation ← Identity | `PersonRef` (Identity); `ClaimRedeemed` for this organisation's reference only | reference; event to the issuing organisation | another organisation's references; any profile into Identity |
| Organisation ↔ Identity (sign-in) | the discovery mechanism (Identity, D-102; L1/L2 open) finds the person's references; the register finds the record by `PersonRef` | query | the contents of the register |
| Organisation ← Membership | Membership time coverage (RAT CR-01: C1 event, C2 query by `personRefs[]`, D-77) | event, query | Organisation's interpretation of Membership facts |
| Organisation → Authorization | workspace access granted or revoked | event or query | any grant inferred from account, membership or affiliation |
| Organisation ← Governance | competence to record affiliations or grant access (existing `AuthorityCheckPort`) | query | authority from platform roles |
| Organisation → Election | the "voter" relationship and its basis, at τ (D-109) | query at τ | entitlement (Election decides) |
| Organisation ← Reference Geography | official references for address resolution (RAT D-93) | reference | organisation areas as official |
| Organisation → Communication | the organisation's own contact points for its own communications | query | other organisations' contact data |

No shared tables, no shared transactions.

## 6. Lifecycle and invariants

### 6.1 Lifecycles (independent)
| Object | States | Note |
|---|---|---|
| Person record | `recorded` → `archived` → (`erased`, XD-PROV open) | archiving keeps history; affiliations end by consequence (§9) |
| Profile | versions corrected forward with basis and actor | not a separate lifecycle from the record |
| `PersonRef` slot | `empty` → `assigned` | changed only by an Identity notice (merge or correction) |
| Affiliation (one per capacity occurrence) | `active (from, basis)` → `ended (until, basis)`; corrections forward | independent per capacity |
| Workspace access | `granted (basis, by)` ↔ `revoked (basis, by)` | at most one active grant per record |

### 6.2 Invariants (owner, evidence)
| # | Invariant | Owner | Classification |
|---|---|---|---|
| I-1 | Organisation A cannot read B's person records, profiles or affiliations | Organisation, Identity | **RAT** (XD-3 §2.4) / **WD** (D-101) |
| I-2 | An Account never creates a person record or affiliation in any register | Organisation | **WD** (D-107, D-102) |
| I-3 | An e-mail address never establishes identity equivalence | Identity | **RAT** (XD-3 §2.6) |
| I-4 | A person can be recorded without an Account | Organisation | **APP** (BR-5, BR-6) |
| I-5 | A person can be recorded without formal Membership | Organisation | **APP** (BR-3); D-103 |
| I-6 | The organisation cannot establish formal Membership | Membership | **RAT**; **WD** D-103 |
| I-7 | The "Member" view reflects Membership's time coverage only | Organisation (read model) | **WD** D-103; **RAT** CR-01 |
| I-8 | Workspace access needs an explicit grant with a basis | Organisation | **WD** D-104 |
| I-9 | Workspace access is independent of Membership and affiliation | Organisation | **WD** D-104 |
| I-10 | No affiliation creates Governance authority | Governance | **APP** (Decision A) |
| I-11 | A "voter" relationship never creates entitlement | Election | **RAT** boundary; **WD** D-109 |
| I-12 | Only Identity resolves cross-organisation equivalence | Identity | **RAT** (XD-3 §2.3) |
| I-13 | One Account per registered human; registration is never repeated per organisation | Identity | **RAT** (0..1); **WD** D-110 |
| I-14 | Every affiliation and access act records its basis and actor | Organisation | **P** |
| I-15 | An organisation's business data (people, affiliations, access, membership, election, finance) is never readable by another organisation; providing the login never makes PublicDigit its owner | all contexts, per organisation | **WD** D-111; **RAT** XD-3 §2.4 |

## 7. Domain operations (candidates, not approved names)
| Operation | Context | Authority | Evidence | Invariants | Fact | Contract | Audit |
|---|---|---|---|---|---|---|---|
| RecordPerson | Organisation | an authorised officer, or an authorised import | the organisation's source (form, list) | I-2, I-4, I-5 | person recorded | — | who, when, source |
| UpdatePersonProfile | Organisation | officer; later the person for their own record (P, after claim) | the change request | I-1 | profile corrected | Communication reads | old/new, who |
| ImportPerson | Organisation | authorised officer | the import batch | **same operation as RecordPerson**, batched; I-2: **no account lookup** | persons recorded (+ voter affiliation, D-109) | — | batch id per row |
| RecordAffiliation | Organisation | competent per capacity (`AuthorityCheckPort`) | basis act | I-5, I-6 (no "Member"), I-10, I-14 | affiliation started | Election reads "voter" | basis, actor |
| ChangeAffiliation / EndAffiliation | Organisation | as above | basis act | period integrity | changed / ended | — | as above |
| GrantWorkspaceAccess / RevokeWorkspaceAccess | Organisation | competent under the organisation's access rules | basis | I-8, I-9 | granted / revoked | Authorization | basis, actor |
| ResolveMembershipCoverage | Organisation (consumer) | — | C1/C2 (RAT) | I-7 | "Member" view updated | — | event id |
| HandleIdentityClaimNotification | Organisation (consumer) | — | `ClaimRedeemed` for its reference | I-1, I-12 | "this record's person has an account" | — | event id |
| HandleIdentityMergeNotice | Organisation (consumer) | — | an Identity merge notice for its references | I-12 | slot updated; possible local duplicate flagged | — | notice id |

## 8. Scenario validation
| # | Scenario | Owner of the facts | Outcome |
|---|---|---|---|
| 8.1 | a guest has no account | Organisation | recorded with a Guest affiliation; no `PersonRef` needed (I-4) |
| 8.2 | a worker has an account but is not a member | Organisation; Identity; Membership | Worker affiliation; claim connects the Account (P-I1 mechanism); "Member" view empty (I-5, I-7) |
| 8.3 | a member loses workspace access but remains a member | Organisation (access); Membership | `RevokeWorkspaceAccess`; Membership untouched (I-9) |
| 8.4 | one human known to two organisations with different profiles | each Organisation; Identity | two records, two profiles, two `PersonRef`s; neither sees the other (I-1); one Account (D-110) |
| 8.5 | an organisation imports the same person twice | Organisation | **three different cases:** (a) **the same import row repeated**: idempotent per row key; no second record. (b) **two local records may describe the same human**: a hint from the organisation's own data only, never automatic; the organisation may merge its own records **only if they do not hold two different assigned `PersonRef`s**, otherwise it may request Identity's review (R-XD3c-3 style). (c) **Identity has established sameness**: a merge notice updates the slot; the organisation then sees that two of its own records are one human and decides about its records. Organisation never establishes cross-organisation sameness (I-12). |
| 8.6 | a voter imported before registering | Organisation (D-109); Election | record + "voter" affiliation; voting needs an Account (BR-7); claim later |
| 8.7 | recorded as voter, fails Election eligibility | Election | not in the electorate at τ; the affiliation is unchanged (I-11) |
| 8.8 | address changed in one organisation only | that Organisation | only that profile changes; the other is untouched (I-1); belonging unchanged (D-80) |
| 8.9 | Membership expires; Worker continues | Membership; Organisation | "Member" view ends with the time coverage; Worker affiliation continues (independent lifecycles) |
| 8.10 | the person claims the organisation's record with a code | Identity; Organisation | Identity binds the Account; the organisation receives `ClaimRedeemed`; access only if granted (I-8) |
| 8.11 | an organisation tries to assign "Member" without Membership | Organisation | **impossible**: no "Member" capacity can be recorded (I-6); admission is Membership's |

## 9. Tactical DDD design and aggregate boundary justification

### 9.1 Aggregates (revised)
| Aggregate | Business justification (APP) | Contents | Rules enforced inside |
|---|---|---|---|
| **`OrganisationPerson`** | the organisation's statement of **who** a person is to it; profile corrections must be consistent with the record's status, and the `PersonRef` slot must never be set by the organisation | id (organisation-scoped), profile versions, `PersonRef` slot, status | I-2, I-4; slot changes only from Identity notices; no change after `erased` |
| **`Affiliation`** (one per capacity occurrence) | **each relationship has its own authority, evidence and lifecycle** (a Worker engagement, a Guest invitation and a voter-list entry are decided by different people at different times); ending one must not touch the profile or another relationship | person id, capacity kind, period, basis, actor | non-overlap of periods per (person, capacity); "Member" not constructible (I-6); a basis required (I-14) |
| **`WorkspaceAccess`** | **a different decision under the organisation's access rules**, with its own audit; Authorization consumes only this; revocation must not alter the record or any relationship (8.3) | person id, grant/revocation history, basis, actor | one active grant (I-8); independent of affiliation and Membership (I-9) |

**Cross-aggregate rules (eventual, by events):**
- **archiving a person ends their active affiliations and revokes access.** The archive event triggers those acts, and recording a new affiliation checks the person's status first. A race is closed by the archive handler, which ends anything still active.
- **Why not one aggregate (revision 1):** revision 1 put affiliations inside `OrganisationPerson`. Revision 2 reverses this (ASP reversal condition met): concurrent officer actions on different relationships (an import adding "voter" while a registrar corrects an address) would collide, and **no business invariant needs profile and relationships changed atomically**.

### 9.2 Value objects
- `OrganisationPersonId` (scoped);
- `PersonRef` (the existing Membership value object, reused, not duplicated);
- `ProfileName`, `ContactPoint`, `AddressAsSupplied` (+ `GeographyRef`);
- `CapacityKind` (Worker, Guest, voter; no "User", no assertable "Member");
- `Period`;
- `Basis` (decision / invitation / authorised import / declaration, with a reference);
- `Actor`;
- `AccessBasis`.

### 9.3 Events (business facts, this organisation's ids only)
`PersonRecorded`, `ProfileCorrected`, `PersonRefAssigned`, `PersonArchived`, `AffiliationStarted`, `AffiliationChanged`, `AffiliationEnded`, `WorkspaceAccessGranted`, `WorkspaceAccessRevoked`. **Not events here:** Membership coverage (Membership's fact), claim redemption (Identity's fact).

### 9.4 Repositories and read models (RMSP)
- **Repositories:**
  - `OrganisationPersonRepository`: `get`, `findByPersonRef(organisation, ref)`, `save`;
  - `AffiliationRepository`: `get`, `activeFor(person, capacity)`, `save`;
  - `WorkspaceAccessRepository`: `get(person)`, `save`.
- **Never offered:** finders across organisations, or by e-mail across organisations.
- **Read models:**
  - people list (the organisation's own profile data only);
  - "Member" view (from C1/C2);
  - voter list for Election (D-109).

### 9.5 Transactions
One aggregate per transaction. Each import row is its own transaction, idempotent per row key. Cross-context effects go by events and queries.

## 10. Open dependencies
| Item | State | Effect on this design |
|---|---|---|
| **P-I1** (Person and `PersonRef` timing) | deferred until an **independent review that includes BR-10** | only when the slot gets filled; **does not block this design** |
| **BR-10 for the reviewer** | BR-10 (eager creation at import) is a PO direction; its "or resolves" cannot be done without matching (D-75), so it means "create". Options to compare: (a) eager = BR-10; (b) lazy with a local placeholder; (c) eager with restricted discovery; (d) reference at import, Person at claim (contradicts BR-10) | the reviewer must say whether BR-10 remains the business direction |
| O-7 | is the "voter" relationship scoped per election or standing? | affects the voter-list read model; not the aggregates |
| XD-PROV | erasure and retention | the `erased` state |
| Organisation-specific capacities | UNKNOWN until statutes | `CapacityKind` extensible later |
| Amendment note on baseline D-106 (BR-2/BR-8/BR-12, "User") | REC, needs the Principal's approval | consistency of the accepted baseline |

## 11. Readiness
| Item | Verdict |
|---|---|
| Strategic model | **coherent with D-100…D-110 and the ratified contracts**; residual: the D-106 amendment note |
| Tactical design | **ready for independent review**, with seams P-I1, O-7, XD-PROV |
| Implementation | **not authorised**; needs the review, then an EP-01 plan |

## 12. Smallest coherent first capability (business outcome)
**"An organisation can keep its own list of the people it knows, including people with no PublicDigit account, with its own contact details for each, record who works for it or is its guest, and decide explicitly who may use its workspace. Its screens show only its own data."**

It needs no Identity decision, no Membership change and no voter-import change, and it is the base for:
- voter lists (D-109);
- claim codes (Identity);
- the members' own phone view, "my details here".

## 13. D-111 tested against the other contexts (revision 3)
**Worked example (the note's):** one human, one Account.
- Organisation A: member and voter.
- Organisation B: voter, not a member.
- Organisation C: member, not currently a voter.

That is three independent sets of records; none sees another (I-15).

| Context | What D-111 means for it | Consistent with ratified records? | Consequence |
|---|---|---|---|
| **Identity** | one Account; one claim **per organisation** connects it to that organisation's record; discovery lists the person's organisations after sign-in | yes: XD-3 (0..1 Account; per-organisation `PersonRef`; only Identity resolves). D-111 precision (3): the minimised discovery information (C-4) is access infrastructure, not ownership | **tension for P-I1:** eager Person creation at import (BR-10) makes Identity hold a central record about people who have **never registered**, which sits awkwardly with "Identity owns the platform identity of registered people". Reference-at-import options (d) do not. **For the independent reviewer**; not decided here. |
| **Membership** | the example's A and C each hold their own membership records; B holds none. **One** Membership context (ratified model) applies to each organisation's data separately | yes: per organisation, `PersonRef` unique per organisation (RA); C1/C2 per organisation (CR-01) | the people register's "Member" view in A and C comes from each organisation's own C1/C2; nothing crosses (I-7, I-15) |
| **Election** | A and B hold voter relationships (D-109); each election's electorate is Election's, within that organisation's data; eligibility is evaluated under the organisation's rules (set by its competent organ, recorded by Governance; DD-11, `PBDIGIT-336`) | yes: Election owns entitlement (RAT); BR-13: no rule across organisations | voting in A's and B's elections with one Account is two independent rights issuances; Voting sees no identity in either (VO-1) |
| **Finance** | each organisation's fees, payments and transactions are its own; a person paying A and C has two unrelated finance histories | yes: Finance owns payment and settlement (RAT); charges reference Membership periods per organisation (RA:155) | Finance refers to **the organisation's own** person (by `PersonRef` or the register's `OrganisationPersonId`: **open**, with the FeePaid discovery); no central payer profile; **whether Finance data sits in a separate database per organisation is the open tenancy decision** (D-111 precision 2) |
| **Organisation (this capability)** | the people register is exactly "its own people and profiles" | yes: D-101 | no change to §9's aggregates; I-15 added |

**Conflicting rules after D-111:**
- **Bootstrap "User affiliation" (BR-2, BR-8, BR-12):** already resolved by D-107. Pending: the amendment note on the accepted baseline D-106 (REC, needs approval).
- **BR-10:** **still open** with P-I1. D-111 adds an argument the reviewer must weigh, but it does not decide P-I1.
- **Legacy code (FACT):** one database scoped by `organisation_id`; central `users` profile data shown across organisations (PBDIGIT-341 F-8, F-12). Logical isolation is what the code must reach; the physical form stays open.

**Still not frozen:** this design stays a proposal until the independent review; D-111 makes its direction firmer, not final.
