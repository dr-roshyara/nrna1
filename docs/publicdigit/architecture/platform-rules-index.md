# PublicDigit platform rules: architecture index

> **This document is an index of existing PublicDigit platform rules and architecture records. It creates no new rules and makes no architectural decisions. Where this index differs from an authoritative source, the authoritative source prevails.**

## 0. Status header

| | |
|---|---|
| **Status** | **PROPOSED.** Navigation reference only. Not ratified, not approved, not authoritative. |
| **Classification** | Architecture reference / index · lifecycle: living · authority: generated (decides nothing) |
| **Steward** | the strategic-design session, as for [`context-map.md`](context-map.md) and [`strategic-design-status.md`](strategic-design-status.md). The steward maintains this index and holds **no decision authority** over any entry. |
| **Date** | 2026-11-03 · **Author:** Claude (authority: generated) · **Verified at:** branch `election-flow-updated`, HEAD `f2920dd90` |
| **Revision** | r3: corrections from two independent reviews (r1 findings F-01…F-20; r2 findings N-01…N-13). r1 and r2 were never committed. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Method**
- **Authority belongs to the act, not to the document that contains it.** A PROPOSED catalogue can contain an ADOPTED row ([A47](#a47)). A report or decision pack can describe a decision without being its authority.
- **Statuses are copied** from the act or decision line of the source, not translated. A PO decision is not an ARB act. A proposal referred to the ARB is not approved. A report, pack or recommendation decides nothing. Code establishes no domain authority. Where a status cannot be established: **STATUS REQUIRES VERIFICATION**.
- **One governed item per row.** [Appendix A](#appendix-a-rule-to-source-table) is the only place where statuses and owners are stated. Sections 2–11 explain the subjects and point to rows (`A01` …).
- **"Owner / authority"** means who decides, per the source. Where the source names nobody: **not recorded**. The steward of this index is never an owner.
- **"PO" and "Principal".** Several records use "the Principal" for the PO's role (e.g. XD-15: "the Principal (PO)"). Each row keeps the word its source uses.

**How to read each section:** what it means · where it is defined · which rows hold the status · what must not be assumed.

---

## 1. Purpose and scope

**Purpose.** One place to see PublicDigit's platform-level rules, where each is authoritatively recorded, who decides it, and which questions remain open.

**In scope:** identity and login terminology; registration and the two PublicDigit organisations; people without an account; Working Organisation routing; Organisation Affiliation; the boundaries to formal Membership, Election participation and organisation governance.

**Out of scope:** Election internals, the EPIC-002 evidence and integrity contexts, context relationships ([`context-map.md`](context-map.md)), progress of strategic design ([`strategic-design-status.md`](strategic-design-status.md)).

**Not a source.** Brainstorming files, reports and decision packs are never cited as authority.

---

## 2. Platform rules versus other "constitutions"

**What it means.** The repository uses "constitution" in **at least** the following meanings. They have different scopes. This index does not use any of them as the source of the platform rules it lists; whether any of them also states such rules was not established.

| Document | What it is (its own description) | Status (its own header or card) |
|---|---|---|
| [`ES-001-Engineering-Constitution.md`](../../../engineering/governance/ES-001-Engineering-Constitution.md) | "the foundational principles of the Engineering Platform and the rules by which governance itself is created" | PROPOSED (part of the Standards Index). It registers rule families with their own homes. |
| [`Implementation_Architecture_Constitution_v1.0.md`](../../implementation/Implementation_Architecture_Constitution_v1.0.md) | normative rules for writing greenfield-core code | FROZEN |
| [`Project_Constitution_and_Knowledge_Transfer_Handbook_v1.0.md`](../../architecture/Project_Constitution_and_Knowledge_Transfer_Handbook_v1.0.md) | navigation and synthesis over frozen artifacts | LIVING |
| [`CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md`](../../CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md) | handbook of the governance and geography domain engine (GEO-3.x) | "GEO-3.4 Complete" (v3.4, 2026-05-08). Its relation to the constitution of an individual organisation: **STATUS REQUIRES VERIFICATION** |
| [`Knowledge-Constitution.md`](../../knowledge/Knowledge-Constitution.md) | rules of the Engineering Knowledge Platform | card: `status: frozen`, `authority: authoritative` |

The code also models organisation-level constitutional concepts, e.g. `app/Contexts/Membership/Domain/Committee/CommitteeConstitution.php` and `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php`. Code is cited only to show the word is in use; it is not a source of rules.

**Related rows:** [A72](#a72) (act §11, "organisational governance rules"); [A79](#a79), [A82](#a82) (BR-13, BR-13a); [A87](#a87).

**Must not be assumed:** that this index, or PublicDigit's platform rules, is a constitution. That "PublicDigit must not impose one universal governance model on every organisation" is a rule: no authoritative record states it ([A87](#a87)).

---

## 3. Identity and authentication: Person, Account, User

**What it means.** Three terms, kept apart:
- **Person**: a human. Identity and identity equivalence are owned by Identity ([A01](#a01)).
- **Account**: a credential used to authenticate. A Person has zero or one Account ([A05](#a05)).
- **User**: the affiliation with the **bootstrap PublicDigit organisation** that registration creates ([A11](#a11), [A12](#a12)). Decision C describes a User as "a person affiliated with PublicDigit who can use the platform" and models it as an affiliation role ([A15](#a15)).

"User" is **not** a general organisation capacity in the sources, **not** the login account, and **not** the legacy `users` table.

**Rows:** [A01](#a01)–[A09](#a09); open identity questions [A93](#a93), [A103](#a103)–[A111](#a111).

**Must not be assumed:**
- that the target identity model is approved ([A02](#a02), [A05](#a05), [A06](#a06), [A09](#a09));
- that the per-organisation `PersonRef` is decided ([A03](#a03));
- that a central Person record may hold profile data. XD-3b §2a does not select that option.

---

## 4. Registration, bootstrap organisation and operator organisation

**What it means.** There are **two separate** PublicDigit organisations:
- the **bootstrap** organisation: infrastructure, never anyone's Working Organisation ([A18](#a18));
- the **operator** organisation: a separate, ordinary organisation for PublicDigit's real organisational life, counted at sign-in like any other ([A19](#a19), option K′).

Registration makes the person a User of the bootstrap organisation ([A11](#a11)–[A13](#a13)).

**On Membership:**
- BR-3 says being a User "does not **automatically** make someone a formal Member" ([A14](#a14));
- "Registration **never** creates Membership" is a different, PROPOSED statement (DC-R2, [A16](#a16)).

**Wording difference: B1.1 and BR-3.**
- PBDIGIT-30 B1.1 says "That **membership** is infrastructure" ([A13](#a13)).
- BR-3 says being a User does not automatically make someone a formal Member ([A14](#a14)).
- The business-rules record's consistency note reads BR-3 as refining B1.1's informal word ([§3 of that record](../business_rules/20261101_1739_person_account_affiliation_business_rules.md#3-consistency-with-approved-pbdigit-30-b1-2026-08-06)). That note is the recorder's commentary, not one of the PO-confirmed rules.
- PBDIGIT-30 is not annotated. Whether and how to annotate it is OQ-IDX-2. This index does not change PBDIGIT-30.

**Records:**
- [K7 brief](../reviews/2026-11-02-organisation-k7-b1-amendment-decision-brief.md) (a brief; it decides nothing);
- [Decision C §2a](../reviews/2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md#2a-conflict-with-an-approved-business-decision-escalated) (the K′ decision text);
- BR-12.

**Rows:** [A10](#a10)–[A22](#a22), [A94](#a94).

**Must not be assumed:**
- that the operator organisation exists; creating it is not authorised (Decision C §2a);
- where the bootstrap User relationship is stored after K′ ([A94](#a94));
- how demo is separated from the technical default-tenant fallback ([A21](#a21));
- that "every user belongs to at least one organisation" ([A18](#a18)) applies to every Person. Decision C does not select "every Person must belong to an organisation", and BR-5 allows recording a person without an account ([A24](#a24)).

---

## 5. People without an account and imported people

**What it means.** An organisation can record a person without an account, including an imported voter. Voting requires registration and authentication.

**Rows:** [A23](#a23)–[A28](#a28), [A111](#a111).

**Open conflict K4.**
- BR-10 has Identity create or resolve the Person at import and issue the organisation's reference ([A27](#a27)).
- XD-3b §2 has a condition that registering a person without an account centrally must not record which organisation created them.
- Creating the Person at import, with a mapping to the importing organisation, would record exactly that.
- XD-3b §1a lists three resolution options and an INV-AFF-1 mechanism (L2) that might satisfy both. None has been chosen. K4 is open (B.1).

**Must not be assumed:** what importing creates (a Person, a mapping, an affiliation, or none of these) until K4 is resolved; the merge policy for one account redeeming two claim codes ([A111](#a111)).

---

## 6. Working Organisation and routing

**What it means.**
- The Working Organisation is "the organisation within which the authenticated user is currently operating" (PBDIGIT-30 terminology decision).
- Sign-in routing is deterministic and never guesses.
- PBDIGIT-30's own status block: discovery COMPLETE (closed 2026-08-06); implementation not complete.

**Rows:**
- [A29](#a29)–[A38](#a38), [A95](#a95): B1, B2, B3, B4 / PBDIGIT-34;
- [A39](#a39), [A40](#a40), [A96](#a96), [A97](#a97): B10 and eligibility;
- [A41](#a41)–[A42](#a42): B5–B9 dispositions;
- [A43](#a43), [A44](#a44), [A98](#a98), [A99](#a99), [A117](#a117), [A118](#a118): organisation-scoped access;
- [A45](#a45): ownership;
- [A46](#a46), [A59](#a59): implementation and open discovery questions.

**Remembering the choice:**
- B1.5 is approved behaviour: the choice remains until the user changes it, or another business rule changes it ([A33](#a33)).
- Its persistence detail was deferred to B4, which was withdrawn ([A34](#a34)). The product question continues as PBDIGIT-34 ([A95](#a95)).
- Register O-2 (B1.5 vs B1.3) is open (B.1).

**Election-day exception (B10), kept apart:**
1. the routing rule ([A39](#a39));
2. ownership of voting-time eligibility resolution ([A40](#a40), [A96](#a96));
3. the related work item ([A97](#a97)) and implementation ([A46](#a46)).

**Ownership:** the routing behaviour has its own rows. **No bounded context has been established as the owner of Working Organisation** ([A45](#a45)).

**Must not be assumed:**
- that the code already behaves this way;
- that cross-login memory is decided;
- that Identity, Organisation or any other context owns Working Organisation;
- that B8/B9 are settled for all time ([A42](#a42));
- how support or impersonation interacts with B3 (left open, [A37](#a37)).

---

## 7. Organisation Affiliation

**What it means.**
- A technical organisation association or role assignment is not Organisation Membership ([A47](#a47), EM-VOC-002).
- Where affiliation is stored, and who owns it, is open ([A50](#a50)–[A53](#a53)).

**Rows:** [A47](#a47)–[A54](#a54), [A114](#a114).

**Must not be assumed:**
- that central storage is approved. It is the current implementation and a research recommendation ([multi-database investigation 07](../brainstorming/multi-database-system/07-authentication-and-identity.md)), not a decision;
- that organisation-local storage is the approved final architecture ([A51](#a51); feasibility not established, [discovery evaluation](../reviews/2026-11-01-identity-affiliation-discovery-evaluation.md));
- that any central affiliation register may be introduced without an authorised decision;
- that today's affiliation tables define the domain model.

**Recorded tension (not resolved here):**
- The ratified Membership architecture's concept table names "Organisation" as owner of OrganisationAffiliation ([A48](#a48)).
- BR-9 lists *target storage and ownership* as open ([A50](#a50)).
- The context map lists Organisation as a candidate context ([A114](#a114)).
- See OQ-IDX-4.

---

## 8. Formal Membership

**What it means.** Membership is the enduring, formal membership relationship of one Person with one organisation, owned by the Membership bounded context.

**Rows:** [A55](#a55)–[A57](#a57); also [A14](#a14), [A16](#a16), [A17](#a17), [A58](#a58).

**Must not be assumed:** that any affiliation (User, Guest, Worker) is Membership; that Membership decides voting entitlement ([A64](#a64), [A65](#a65)). This index does not restate or redefine the Membership model.

---

## 9. Affiliation capacities and role vocabulary

**There is no approved list of organisation roles.** The terms often listed together have different homes. Statuses are in the rows.

| Term | Home, as recorded | Rows |
|---|---|---|
| **User** | the bootstrap-organisation affiliation (§3) | [A11](#a11), [A15](#a15) |
| **Worker, Guest, Observer** | "further affiliation capacities" in Decision C §1, which adds that Observer "may also exist per process, in the owning context" | [A60](#a60) |
| **Observer** | three meanings in the records (an election roster role; a "no voting rights" membership type; a default authorisation label) | [A61](#a61) |
| **Member** | Organisation Membership (EM-VOC-001; Membership architecture §1) | [A58](#a58) |
| **ElectionMember / ElectionMembership** | three separately governed points: Election owns ElectionMembership ([A64](#a64)); ElectionMember and Organisation Member are different concepts, and ElectionMembership does not imply Organisation Membership ([A116](#a116), EM-VOC-003); the term's definition and its single role column are part of the open vocabulary split ([A62](#a62), [A63](#a63)) | [A116](#a116), [A62](#a62)–[A64](#a64) |
| the vocabulary split as a whole | register O-14 | [A63](#a63) |

**Must not be assumed:** that these terms form one approved taxonomy; that "Observer" has one meaning; that the meaning of ElectionMembership is fully settled ([A63](#a63)); that the organisation access roles (owner / admin) are established (context map §4: "not established").

---

## 10. Election participation

**What it means.**
- Taking part in an election is an **Election** relationship ([A64](#a64)).
- ElectionMembership does not imply Organisation Membership ([A116](#a116)).
- Act §12 states that "Membership itself never becomes the VotingEntitlement engine", and that Election-Only (ImportedVoterRegistry) "does not require Membership" ([A65](#a65)).

**Rows:** [A64](#a64)–[A71](#a71), [A100](#a100)–[A102](#a102).

**Must not be assumed:**
- that an election participant is an organisation Member;
- that the current implementation respects the boundary (PBDIGIT-318 and context map §9 record known violations);
- that the meaning of "no shared persistence" is settled ([A66](#a66), [A90](#a90)).

---

## 11. Organisation-specific governance

**What it means.**
- Act §11: Governance "owns competence, organs, authority and organisational governance rules" ([A72](#a72)).
- Committee, Position, Appointment, Tenure, Mandate and related terms are a candidate vocabulary ([A73](#a73)).
- The frozen Boundary Decision Register records that Appointment (Authority) owns Mandate truth ([A74](#a74)). How Governance relates to it is ARB-8 ([A75](#a75)).

**Rows:** [A72](#a72)–[A89](#a89), [A112](#a112), [A113](#a113).

**Must not be assumed:**
- that Position (or any candidate concept) carries ratified force;
- that PublicDigit prescribes, or is barred from prescribing, a governance model for every organisation ([A87](#a87));
- that DR-07a or DR-07b is decided beyond PO-DR07-01 ([A76](#a76)–[A78](#a78));
- that committee authority and statutes are settled ([A86](#a86)).

---

## Appendix A: rule-to-source table

One row per independently governed decision, rule, proposal or question. "Status" is copied from the source. Rows A94 onward were added when bundled rows were split; they are grouped under the section they belong to. Paths are relative to this file.

**Source shorthand** (links):
- [BR] [`../business_rules/20261101_1739_person_account_affiliation_business_rules.md`](../business_rules/20261101_1739_person_account_affiliation_business_rules.md#1-rules)
- [MAN] [`../business_rules/ELECTION_MANIFESTO.md`](../business_rules/ELECTION_MANIFESTO.md#4--adopted-vocabulary)
- [P30] [`../backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md`](../backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md)
- [ACT] [`../reviews/2026-10-08-membership-architecture-ratification-act.md`](../reviews/2026-10-08-membership-architecture-ratification-act.md#11-ratified-context-boundaries)
- [RAT] [`../reviews/2026-10-08-membership-architecture-ratified.md`](../reviews/2026-10-08-membership-architecture-ratified.md)
- [VER] [`../reviews/2026-10-08-membership-final-ratification-verdict.md`](../reviews/2026-10-08-membership-final-ratification-verdict.md)
- [XD3A] [`../reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md`](../reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md)
- [XD3B] [`../reviews/2026-11-01-identity-xd3b-person-vs-account-decision-record.md`](../reviews/2026-11-01-identity-xd3b-person-vs-account-decision-record.md)
- [XD3C] [`../reviews/2026-11-01-identity-xd3c-account-lifecycle-decision-record.md`](../reviews/2026-11-01-identity-xd3c-account-lifecycle-decision-record.md)
- [PROV] [`../reviews/2026-11-02-identity-xd-prov-person-provenance-and-deletion-authority-decision-record.md`](../reviews/2026-11-02-identity-xd-prov-person-provenance-and-deletion-authority-decision-record.md)
- [DC] [`../reviews/2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md`](../reviews/2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md)
- [AFF] [`../reviews/2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md`](../reviews/2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md)
- [ORG] [`../reviews/2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md`](../reviews/2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md)
- [SEP] [`../reviews/2026-11-02-membership-election-separation-decision-record.md`](../reviews/2026-11-02-membership-election-separation-decision-record.md)
- [DR07] [`../reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md`](../reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md)
- [PACK] [`../reviews/2026-11-02-organisation-governance-arb-decision-pack.md`](../reviews/2026-11-02-organisation-governance-arb-decision-pack.md) (decision preparation; decides nothing)
- [AUD] [`../reviews/2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md`](../reviews/2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md) (report; decides nothing)
- [REG] [`../reviews/2026-11-02-organisation-governance-strategic-design-status-assessment.md`](../reviews/2026-11-02-organisation-governance-strategic-design-status-assessment.md)
- [CM] [`context-map.md`](context-map.md)
- [BDR] [`../../architecture/design/Round49-06_Boundary_Decision_Register.md`](../../architecture/design/Round49-06_Boundary_Decision_Register.md)
- [XD15] [`../reviews/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md`](../reviews/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md)

### A.1 Identity (§3)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a01"></a>A01 | Identity owns Person identity and identity equivalence | [ACT] §11 | RATIFIED | Principal Reviewer (act 2026-10-08) | — |
| <a id="a02"></a>A02 | One platform-wide Person (option A), with binding conditions C-1…C-5 | [XD3A] §1–§2 | PROPOSED, pending governance approval | PO + ARB + data protection ([REG] P-1) | approval |
| <a id="a03"></a>A03 | Per-organisation opaque `PersonRef` (option D; K3) | [XD3A] §1; [XD3B] §1a | "Preferred direction (Principal, 2026-11-01; NOT decided, NOT ratified)" | own architecture decision required | mechanism L1 / L2 |
| <a id="a04"></a>A04 | PublicDigit must be able to record a person who has no login account (business part of XD-3b) | [XD3B] header | answered by the Principal, 2026-11-01: yes | Principal | — |
| <a id="a05"></a>A05 | Person ≠ Account; zero or one Account; `PersonRef` is never the account id (option B2) | [XD3B] §1 | PROPOSED; the architectural part needs ARB approval | ARB ([REG] P-2) | approval |
| <a id="a06"></a>A06 | Account remains active when a person leaves their only organisation, unless deletion is requested or an Identity security policy suspends it (option C) | [XD3C] §1 | PROPOSED, pending governance approval | PO + ARB; legal / data protection ([REG] P-3) | [A108](#a108), [A109](#a109) |
| <a id="a07"></a>A07 | Joining or leaving an organisation never creates or deletes the account | [DC] DC-R7 | PROPOSED | PO + ARB ([REG] P-4) | approval |
| <a id="a08"></a>A08 | The User affiliation is not a login gate | [DC] DC-R8 (marked [R]) | PROPOSED | PO + ARB | OQ-C-0 (confirm DC-R8) |
| <a id="a09"></a>A09 | Person provenance is organisation-local; deletion authority | [PROV] | PROPOSED (the Principal selected organisation-local provenance, 2026-11-02) | PO + ARB; legal / data protection ([REG] P-6) | [A107](#a107) |

### A.2 Registration and the PublicDigit organisations (§4)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a10"></a>A10 | PublicDigit is the default organisation | [BR] BR-1 | Confirmed (PO, 2026-11-01) | PO | — |
| <a id="a11"></a>A11 | Every person who registers becomes a User of the default PublicDigit organisation | [BR] BR-2 | Confirmed | PO | — |
| <a id="a12"></a>A12 | Registration establishes a User affiliation with PublicDigit; the person keeps their voter relationship elsewhere | [BR] BR-8 | Confirmed | PO | — |
| <a id="a13"></a>A13 | On registration the user automatically belongs to PublicDigit; "that membership is infrastructure" | [P30] B1.1 | APPROVED (PO, 2026-08-06) | PO | OQ-IDX-2 |
| <a id="a14"></a>A14 | Being a User of PublicDigit does not automatically make someone a formal Member | [BR] BR-3 | Confirmed | PO | — |
| <a id="a15"></a>A15 | Registration creates an OrganisationAffiliation with the PublicDigit organisation, role User | [DC] DC-R1 | PROPOSED | PO + ARB | approval |
| <a id="a16"></a>A16 | Registration never creates Membership in PublicDigit or anywhere else | [DC] DC-R2 | PROPOSED | PO + ARB | approval |
| <a id="a17"></a>A17 | Membership and OrganisationAffiliation keep their ratified / adopted meanings | [DC] DC-R3, DC-R4 | PROPOSED | PO + ARB | approval |
| <a id="a18"></a>A18 | Bootstrap organisation: infrastructure, never a Working Organisation, satisfies "every user belongs to at least one organisation" | [P30] B1, "The bootstrap organisation"; K7 confirmation | APPROVED (2026-08-06); not amended by K7 | PO | — |
| <a id="a19"></a>A19 | PublicDigit's real organisational life is in a separate ordinary (operator) organisation, counted under B1.3; registrants are not affiliated with it by registering | [BR] BR-12; [DC] §2a; [K7 brief](../reviews/2026-11-02-organisation-k7-b1-amendment-decision-brief.md) (brief only) | Decided by the PO, 2026-11-02 (K7, option K′) | PO | K′ naming; read-only data check (B.1) |
| <a id="a20"></a>A20 | Demo stays distinguishable from both organisations | [BR] BR-12 | Decided by the PO, 2026-11-02 | PO | — |
| <a id="a21"></a>A21 | Demo and the technical default-tenant fallback are kept separate from PublicDigit's real relationships | [DC] DC-R6 | PROPOSED | PO + ARB | OQ-C-4 |
| <a id="a22"></a>A22 | User affiliation stored in "PublicDigit's organisation database" | [DC] DC-R9 (marked [R]) | PROPOSED | PO + ARB | [A94](#a94) |
| <a id="a94"></a>A94 | After K′: is the bootstrap User relationship a platform-store fact or an organisation-database fact? | [DC] §2a "Consequences recorded, not decided"; [strategic-design-status](strategic-design-status.md) T1 | open (recorded, not decided) | D-1 / ARB | — |

### A.3 People without an account and imported people (§5)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a23"></a>A23 | One person can be associated with multiple organisations | [BR] BR-4 | Confirmed | PO | — |
| <a id="a24"></a>A24 | An organisation can record a person without a login account | [BR] BR-5 | Confirmed | PO | — |
| <a id="a25"></a>A25 | A person imported as a voter can be recorded before registering | [BR] BR-6 | Confirmed | PO | — |
| <a id="a26"></a>A26 | A voter without an account cannot vote; they must register, authenticate and meet the voting rules | [BR] BR-7 | Confirmed (an Election rule) | PO; rule owned by Election | — |
| <a id="a27"></a>A27 | At import, Identity creates or resolves the Person and issues the organisation's reference; no PublicDigit User affiliation at import | [BR] BR-10; [XD3B] §1a | PO direction, 2026-11-01 | PO | K4 |
| <a id="a28"></a>A28 | Independent imports of the same human are not assumed to be one person; merging is an explicit Identity process | [BR] BR-11; [XD3B] §1a | PO direction, 2026-11-01 | PO; Identity process | [A111](#a111) |
| <a id="a111"></a>A111 | When one account redeems claim codes of two different Persons: merge evidence or review? | [XD3B] §1a ("Open policy") | open | not recorded | — |

### A.4 Working Organisation (§6)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a29"></a>A29 | The term is "Working Organisation", not "Active Organisation" | [P30] terminology decision | APPROVED | PO | — |
| <a id="a30"></a>A30 | First real organisation created or joined becomes the Working Organisation | [P30] B1.2 | APPROVED | PO | — |
| <a id="a31"></a>A31 | Routing ignores the bootstrap organisation: 0 → Create or Join · 1 → enter it · 2+ → Organisation Selection | [P30] B1.3 | APPROVED | PO | O-2 |
| <a id="a32"></a>A32 | With several organisations the system never guesses | [P30] B1.4 | APPROVED | PO | — |
| <a id="a33"></a>A33 | The chosen organisation remains the Working Organisation until the user changes it, or another business rule changes it | [P30] B1.5 | APPROVED ("persistence detail overlaps B4 — refined there") | PO | O-2; [A34](#a34) |
| <a id="a34"></a>A34 | B4: is the Working Organisation remembered between logins? | [P30] B4 | WITHDRAWN by the PO, 2026-08-06; binds nothing | PO | continued as [A95](#a95) |
| <a id="a95"></a>A95 | Should the platform remember the Working Organisation across logins? | [PBDIGIT-34](../backlog/PBDIGIT-34-remember-the-working-organisation-across-logins.md) | OPEN — unprioritised (work item) | not recorded | — |
| <a id="a35"></a>A35 | Every authenticated request executes inside exactly one Working Organisation | [P30] B1 invariant | APPROVED | PO | — |
| <a id="a36"></a>A36 | When it changes: only on an explicit user business action, or when the current one is no longer valid; never as a technical side effect | [P30] B2 "As approved" | APPROVED, WITH AMENDMENTS (PO, 2026-08-06) | PO | — |
| <a id="a37"></a>A37 | Who may establish or change it: the user; the platform only on invalidity; never an administrator on another's behalf | [P30] B3 authorities | APPROVED | PO | B3 open edge: support / impersonation ("left open") |
| <a id="a38"></a>A38 | The platform shall always be able to explain why the current Working Organisation was established | [P30] B3 business invariant | APPROVED (part of B3) | PO | — |
| <a id="a39"></a>A39 | Election-day direct entry at login / platform root only: 1 eligible election → that election; 0 → B1.3; 2+ → election selection; reuse existing eligibility rules | [P30] B10 | APPROVED | PO | — |
| <a id="a40"></a>A40 | AD-2: formal ownership of voting-time eligibility resolution | [`../reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md`](../reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md) (B-7) | recorded as an open architecture decision on 2026-08-15. **STATUS REQUIRES VERIFICATION:** whether the later act §11 ("Election owns … eligibility", [A64](#a64)) settles it in part was not established | ARB | [P30] B10 note: to be settled before B10 is implemented |
| <a id="a96"></a>A96 | MB-5: "Eligibility has five implementations and no named authority" ("who owns eligibility?") | [`../reviews/2026-08-06-membership-capability-review.md`](../reviews/2026-08-06-membership-capability-review.md) (MB-5) | finding classified "Product", 2026-08-06 | not recorded | — |
| <a id="a97"></a>A97 | Voter eligibility has two homes | [PBDIGIT-49](../backlog/PBDIGIT-49-voter-eligibility-has-two-homes.md) | OPEN — not authorised (work item) | PO (authorisation) | — |
| <a id="a41"></a>A41 | B5–B7 need no additional business rule (derived from B1–B3) | [P30] "Disposition of B4–B9" | closed with PBDIGIT-30 (PO, 2026-08-06) | PO | — |
| <a id="a42"></a>A42 | B8 / B9 need no further discovery under B1–B3 as they stand; new business behaviour would reopen them | [P30] "Disposition of B4–B9"; "What this closure deliberately does not claim" | conditionally closed (2026-08-06) | PO | experience design; reopening condition |
| <a id="a43"></a>A43 | Owning organisation = Working Organisation; access fails closed; platform-level clause (sign-in routing runs before a Working Organisation exists); identity access and request-context rules | [ORG] §1, §1a, §1c (approval record §1b) | Principle approved by the PO, 2026-11-01 (§1, §1a, §1c) | PO | [A98](#a98) |
| <a id="a98"></a>A98 | ARB ratification of INV-ORG-SCOPE-1 | [ORG] §1b; [PACK] §1 | pending | ARB | — |
| <a id="a99"></a>A99 | Enforcement of INV-ORG-SCOPE-1 (implementation, tests) | [ORG] §1b | open; not authorised | not recorded | — |
| <a id="a44"></a>A44 | OQ-OS-1: rules for official shared reference data with no owning organisation | [ORG] §4 | OPEN | ARB (with XD-5) | — |
| <a id="a117"></a>A117 | OQ-OS-2: exception catalogue (platform support acting for an organisation; platform administration; authority and audit trail) | [ORG] §4 | OPEN | PO + ARB + Security | — |
| <a id="a118"></a>A118 | OQ-OS-3: classification of the 379 organisation-filter bypasses | [ORG] §4; §1b | OPEN; investigation not started, not authorised | ARB | — |
| <a id="a45"></a>A45 | Which bounded context owns Working Organisation | none. [CM] §4 records the owner label "Platform / tenancy" (from the governance map) with "assignment to a named context not established" | UNRESOLVED | not recorded | escalate to governance |
| <a id="a46"></a>A46 | Implementing B1–B3 and B10 (PBDIGIT-31 evidence: the 0 / 2+ destinations and the B10 2+ branch are not yet implemented) | [PBDIGIT-32](../backlog/PBDIGIT-32-implement-working-organisation-routing.md); [PBDIGIT-31](../backlog/PBDIGIT-31-redirection-mechanism-review.md) (evidence only) | "UNBLOCKED — ready for authorisation" (work item) | PO (authorisation) | — |
| <a id="a59"></a>A59 | PBDIGIT-29 Q1 / Q2: is the Working Organisation one field or a concept with behaviour; which store is authoritative | [PBDIGIT-29](../backlog/PBDIGIT-29-organisation-context-discovery.md) Q1, Q2; [P30] closure | open; "not prerequisites for implementing B1–B3" | not recorded | — |

### A.5 Organisation Affiliation (§7)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a47"></a>A47 | EM-VOC-002: a technical organisation association or role assignment is not Organisation Membership, whatever its name or role value | [MAN] §4 (`:219`); adoption row `:405` (Q-B1 closure, 2026-08-12) | ADOPTED (row). The catalogue is PROPOSED, not ratified ([A115](#a115)) | PO, 2026-08-12 | — |
| <a id="a48"></a>A48 | Concept table: OrganisationAffiliation = "access / roles (EM-VOC-002)", owner "Organisation" | [RAT] §1 | RATIFIED document | Principal Reviewer | OQ-IDX-4 |
| <a id="a49"></a>A49 | Roles are not mutually exclusive in one organisation | [DC] DC-R5 | PROPOSED | PO + ARB | OQ-C-5 |
| <a id="a50"></a>A50 | Target storage and ownership of OrganisationAffiliation | [BR] BR-9 | Open | PO ([CM] §10); INV-AFF-1 direction to the ARB | — |
| <a id="a51"></a>A51 | Each organisation owns and stores its affiliation records; discovery at sign-in; no persisted cross-organisation register | [AFF] §1 | PROPOSED; referred to the ARB by the PO on 2026-11-02; ARB decision pending | PO + ARB, and data-protection review | feasibility |
| <a id="a52"></a>A52 | Which store represents roles consistently, incl. several roles per person | [DC] OQ-C-5 | OPEN | ARB | — |
| <a id="a53"></a>A53 | Can the central organisation-access index be avoided or minimised? | [XD3A] §4 OQ-3a-3 | OPEN | ARB, linked to multi-database decision D1 | — |
| <a id="a54"></a>A54 | Does ending a User affiliation affect anything else (never the account)? | [DC] OQ-C-2 | OPEN | PO + ARB | — |
| <a id="a114"></a>A114 | Candidate contexts (incl. Organisation), context map, invariants G-1…G-9 | [REG] P-8; [CM] §4 | PROPOSED (candidate) | ARB | — |

### A.6 Formal Membership (§8)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a55"></a>A55 | The Membership architecture (concepts, facts, periods, invariants, aggregate, contracts) | [RAT]; [ACT] | RATIFIED, FROZEN FOR IMPLEMENTATION; changes only via an Architecture Decision / Change Request | Principal Reviewer | — |
| <a id="a56"></a>A56 | Membership owns organisational membership truth | [ACT] §11 | RATIFIED | Principal Reviewer | — |
| <a id="a57"></a>A57 | The operator organisation's own membership rules (eligibility, approval) | [DC] OQ-C-6 | OPEN | PO | — |

### A.7 Vocabulary (§9)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a58"></a>A58 | Member = Organisation Membership (EM-VOC-001) | [MAN] §4; adoption row `:405`; [RAT] §1 | EM-VOC-001 ADOPTED (PO, 2026-08-12); concept in the RATIFIED [RAT] §1 | PO; Principal Reviewer | — |
| <a id="a116"></a>A116 | EM-VOC-003: "`ElectionMember` and `Organisation Member` are different concepts. `ElectionMembership` does not imply Organisation Membership." | [MAN] §4 (`:220`); adoption row `:406` (2026-08-12) | ADOPTED (row) | PO, 2026-08-12 | — |
| <a id="a60"></a>A60 | Worker, Guest, Observer as "further affiliation capacities" | [DC] §1 | PROPOSED | PO + ARB | O-14 |
| <a id="a61"></a>A61 | "Observer" has three meanings (election roster role; "no voting rights" membership type; default authorisation label) | [AUD] §2 (report) | marked CONFLICTING by a report; no decision | ARB (via O-14) | O-14 |
| <a id="a62"></a>A62 | ElectionMembership's single role column (voter, candidate, observer, admin, committee) conflates concepts | [AUD] §2 (report) | observation; no decision | ARB (via O-14) | O-14 |
| <a id="a63"></a>A63 | Vocabulary split: member / role / observer / ElectionMembership (incl. the term definitions) | [REG] O-14 | OPEN | ARB | — |

### A.8 Election participation (§10)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a64"></a>A64 | Election owns ElectionMembership, VotingEntitlement, eligibility and exercisability; "Membership does not decide these" | [ACT] §11 | RATIFIED (ownership) | Principal Reviewer | term definition: [A63](#a63) |
| <a id="a65"></a>A65 | "There is no shared persistence and no Membership → entitlement shortcut"; "Membership itself never becomes the VotingEntitlement engine"; Election-Only "does not require Membership" | [VER] `:46` (part of the ratified artifact set); [ACT] §12; [RAT] §8 | RATIFIED (artifact set) | Principal Reviewer | wording vs act §11: [A66](#a66) |
| <a id="a66"></a>A66 | "There is no shared persistence" (verdict) vs "No shared persistence is required" (act §11) | [CM] §11 C-3 | OPEN (reading and scope) | ARB (OQ-ME-3, Q-new-1); a PO direction exists for Membership ↔ Election ([A67](#a67)) | [A90](#a90), [A101](#a101) |
| <a id="a67"></a>A67 | Membership ↔ Election: option A for the first milestone (separate persistence ownership within one application) | [SEP] §1 (Decision 1) | Decided by the PO, 2026-11-02 (a direction) | PO | — |
| <a id="a68"></a>A68 | Membership ↔ Election remediation plan | [SEP] §2 (Decision 2) | plan accepted as PROPOSED (PO, 2026-11-02) | PO, per slice | — |
| <a id="a69"></a>A69 | OQ-ME-1: what happens to an active election participant when their membership ends? | [SEP] §4 | OPEN | Election owner + PO | [REG] O-12 |
| <a id="a100"></a>A100 | OQ-ME-2: is the V3 cascade intended? Which facts must remain to explain an election after a role or membership record changes? | [SEP] §4 | OPEN | PO + Election / Governance owners | [REG] O-12 |
| <a id="a101"></a>A101 | OQ-ME-3: does a cross-context foreign key count as "shared persistence"? | [SEP] §4 | OPEN | ARB (interpretation) | [REG] O-12 |
| <a id="a102"></a>A102 | OQ-ME-4: contract semantics for V2 (fact vs decision; current vs as-of; unknown vs negative; source unavailable; organisation scope) | [SEP] §4 | OPEN | ARB + Membership owner | [REG] O-12 |
| <a id="a70"></a>A70 | Boundary remediation slices | [PBDIGIT-318](../backlog/PBDIGIT-318-membership-election-boundary-remediation.md) | OPEN; S1b ACCEPTED by the PO 2026-11-02 with documented residual risks; S2–S4 gated (work item) | PO, per slice | — |
| <a id="a71"></a>A71 | Voter import writes organisation-affiliation data | [PBDIGIT-320](../backlog/PBDIGIT-320-voter-import-writes-organisation-affiliation-data.md) | OPEN · tracked only (work item) | PO | routed to BR-9 / OQ-C-5 |

### A.9 Organisation governance and related platform questions (§11)

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a72"></a>A72 | Governance "owns competence, organs, authority and organisational governance rules" | [ACT] §11 | RATIFIED | Principal Reviewer | — |
| <a id="a73"></a>A73 | Committee (as organ), Position, Appointment, Tenure, Mandate, Delegation, JurisdictionAssignment as Governance concepts | [REG] P-7; [CM] §4 | PROPOSED (CANDIDATE vocabulary) | ARB | — |
| <a id="a74"></a>A74 | Appointment (Authority) "owns Mandate truth" | [BDR] BDR-03 | "Confirmed BC — Operational" in a register marked IMMUTABLE | ARB (frozen register) | ARB-8 |
| <a id="a75"></a>A75 | ARB-8: how DR-07a option A relates to BDR-03 | [PACK] §3; [DR07] §19 | OPEN | ARB | — |
| <a id="a76"></a>A76 | Governance committees are constituted organs; organisational units are distinct; existing records not classified by this ruling; `CommitteeAssociation` unresolved | [DR07] [§15](../reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md#15-po-business-decision-po-dr07-01-2026-11-02) | "PO business decision PO-DR07-01 … recorded 2026-11-02" | PO | — |
| <a id="a77"></a>A77 | DR-07a: ownership of governance organs; ADR-0001 location clause | [DR07] §15–§17; [REG] O-6 | OPEN | PO + ARB + Membership owner ([REG] O-6); after PO-DR07-01 "DR-07a proceeds to the Membership owner and the ARB" ([DR07] §15) | — |
| <a id="a78"></a>A78 | DR-07b: organisational units; meaning of `CommitteeAssociation` | [DR07] §15, §18; [REG] O-16 | OPEN | PO, Membership owner; ARB for ownership | — |
| <a id="a79"></a>A79 | No business rules must hold across organisations | [BR] BR-13 | Confirmed by the PO, 2026-11-02 | PO | — |
| <a id="a80"></a>A80 | BR-13 covers organisational business rules, not platform mechanisms that only ask whether an organisation recognises a person | [BR] BR-13-S | Confirmed by the PO, 2026-11-02 | PO | — |
| <a id="a81"></a>A81 | Is the deletion-eligibility check a permitted platform-level operation under INV-ORG-SCOPE-1 §1a? | [BR] BR-13-S; [REG] O-5 | OPEN | PO + ARB | — |
| <a id="a82"></a>A82 | Organisation independence is the default; no cross-organisation governance functionality now | [BR] BR-13a | Confirmed by the PO, 2026-11-02 | PO | — |
| <a id="a83"></a>A83 | Position incompatibility is scoped per organisation | [BR] BR-14 | Confirmed by the PO, 2026-11-02 | PO | — |
| <a id="a84"></a>A84 | Which positions are incompatible within an organisation | [REG] O-8 | OPEN | PO | statutes |
| <a id="a85"></a>A85 | Membership ↔ tenure (BQ-P2 / P3) | [REG] O-9 | OPEN | PO | statutes |
| <a id="a86"></a>A86 | Committee authority and statutes (CQ-2 / 3 / 4 / 10) | [REG] O-10 | OPEN; statutes not yet requested | PO | — |
| <a id="a87"></a>A87 | "PublicDigit imposes no universal governance model on every organisation" | **no authoritative record** | not established | not recorded | OQ-IDX-1 |
| <a id="a88"></a>A88 | XD-4: who owns authorisation decisions | [PACK] §4; [REG] O-7 | OPEN | ARB | — |
| <a id="a89"></a>A89 | Authorization: "pure resolver; owns no truth; consumes Mandate" | [BDR] BDR-07 | "Supporting Subdomain / Domain Service" in a register marked IMMUTABLE | ARB (frozen register) | [A88](#a88) |
| <a id="a112"></a>A112 | XD-12: write guard | [REG] O-7 | OPEN | ARB | — |
| <a id="a113"></a>A113 | XD-7: unknown-fact semantics (a dependency of authorisation content) | [PACK] §4; [CM] §4, §10 | OPEN | ARB | — |

### A.10 Cross-cutting platform questions

| # | Rule / subject | Authoritative record | Status (copied) | Owner / authority | Outstanding question |
|---|---|---|---|---|---|
| <a id="a90"></a>A90 | Q-new-1: does a direct cross-context read count as shared persistence? | [REG] O-17; [CM] §10; [PACK] item 6 | OPEN; routed to the ARB by the PO 2026-11-02 | ARB | — |
| <a id="a91"></a>A91 | Q-new-2: is event-only integration meant for the greenfield core, platform-wide, or per relationship? | [REG] O-18; [CM] §10; [PACK] item 7 | OPEN; routed to the ARB by the PO 2026-11-02 | ARB | — |
| <a id="a92"></a>A92 | Tenancy and data ownership: option C as candidate direction (D-1) | [XD15] §8a | "a PO direction, not a confirmed target architecture" | PO; ARB | validation conditions 1 and 2 |
| <a id="a115"></a>A115 | EM-OPEN-017: ratification of the Election Manifesto as the governed home of adopted rules | [MAN] header (`:4`, `:30`) | open ("NOT YET RATIFIED") | ARB ("when ratified by ARB", `:30`) | — |
| <a id="a93"></a>A93 | OQ-3a-1: legal basis; controllers and processors | [XD3A] §4 | OPEN | PO + data protection | [REG] O-13 |
| <a id="a103"></a>A103 | OQ-3a-2: does any organisation require legally separate identities? | [XD3A] §4 | OPEN | PO | [REG] O-13 |
| <a id="a104"></a>A104 | OQ-3b-2: membership-record retention vs account deletion | [XD3B] §3 | OPEN | PO + legal | [REG] O-13 |
| <a id="a105"></a>A105 | OQ-3b-3: verified identity before membership or appointment? | [XD3B] §3 | OPEN | PO | [REG] O-13 |
| <a id="a106"></a>A106 | OQ-3b-4: when, if ever, are several accounts per person (B3) needed? | [XD3B] §3 | OPEN | PO / ARB | [REG] O-13 |
| <a id="a107"></a>A107 | OQ-PROV-1…9 (retention, anonymisation, importer capability, deletion discovery, confirmation design, etc.) | [PROV] §7 | OPEN | **per question in the source**; not collapsed here | [REG] O-13 |
| <a id="a108"></a>A108 | OQ-3c-1: content of the Identity account-security policy | [XD3C] §5 | OPEN | PO + Security | — |
| <a id="a109"></a>A109 | OQ-3c-2: Identity's handling of an organisation's suspension request | [XD3C] §5 | OPEN | PO + ARB | — |
| <a id="a110"></a>A110 | Account linking by email (Google login takes over accounts with the same email) needs an explicit Identity matching rule | [XD3C] §5 (carried) | OPEN | ARB | — |

---

## Appendix B: open decisions and change control

### B.1 Open decisions

**Inclusion rule.** B.1 lists every item that is OPEN, UNRESOLVED, PROPOSED-awaiting-approval, pending an act, or a recorded conflict, **where that item bears on a subject in §3–§11.** Its sources are:
- Appendix A;
- register §4 ([REG]);
- context map §10–§11 ([CM]);
- the open-question sections of the cited decision records;
- OQ-IDX items found while compiling this index.

**Excluded, as outside this index's subjects:**
- O-3 (scale figures) and O-4 (isolated test database), which concern validation, not rules;
- O-11 and XD-5 (geography);
- O-15 (forum);
- context map C-1, C-2, C-4…C-9 (C4, EPIC-002 and BDR documentation discrepancies), except where an A-row cites them.

| Reference | Question | Status | Responsible authority (as recorded) | Row |
|---|---|---|---|---|
| XD-3a | one platform-wide Person | PROPOSED | PO + ARB + data protection | [A02](#a02) |
| XD-3a option D | per-organisation `PersonRef` | preferred direction, not decided | own decision required | [A03](#a03) |
| XD-3b | Person ≠ Account (B2) | PROPOSED | ARB | [A05](#a05) |
| XD-3c | account lifecycle | PROPOSED | PO + ARB; legal / data protection | [A06](#a06) |
| OQ-3c-1 / OQ-3c-2 / email linking | account-security policy; suspension requests; email account linking | OPEN | PO + Security / PO + ARB / ARB | [A108](#a108)–[A110](#a110) |
| XD-PROV | provenance, deletion authority | PROPOSED | PO + ARB; legal / data protection | [A09](#a09) |
| O-13 | OQ-3a-1 / 2, OQ-3b-2 / 3 / 4, OQ-PROV-1…9 | OPEN | per question | [A93](#a93), [A103](#a103)–[A107](#a107) |
| K4 | creating a Person at import vs the XD-3b §2 condition | OPEN | PO + ARB + data protection ([XD3B] §1a) | [A27](#a27) |
| claim-code merge policy | one account redeeming two Persons' claim codes | open | not recorded | [A111](#a111) |
| Decision C | DC-R1…R9 as a whole | PROPOSED | PO + ARB | [A07](#a07), [A08](#a08), [A15](#a15)–[A17](#a17), [A21](#a21), [A22](#a22), [A49](#a49) |
| OQ-C-0 | confirm DC-R8 | OPEN | PO + ARB | [A08](#a08) |
| OQ-C-2 | effects of ending a User affiliation | OPEN | PO + ARB | [A54](#a54) |
| OQ-C-4 | how demo and the fallback are separated | OPEN | PO + ARB | [A21](#a21) |
| OQ-C-5 | which store represents roles | OPEN | ARB | [A52](#a52) |
| OQ-C-6 | operator organisation's membership rules | OPEN | PO | [A57](#a57) |
| DC-R9 after K′ | bootstrap User relationship: platform store or organisation database? | open | D-1 / ARB | [A94](#a94) |
| K′ naming | UI names of the two entities | open | PO ([DC] §2a) | [A19](#a19) |
| K′ data check | does the production platform organisation hold real member / worker data? | verification still required | PO / Ops authorisation ([DC] §2a) | [A19](#a19) |
| OQ-3a-3 | avoid or minimise the central organisation-access index | OPEN | ARB, linked to multi-database D1 | [A53](#a53) |
| BR-9 | target storage and ownership of affiliation | Open | PO; INV-AFF-1 direction to the ARB | [A50](#a50) |
| INV-AFF-1 | organisation-owned affiliation, no central register | PROPOSED; referred to the ARB | PO + ARB, data-protection review | [A51](#a51) |
| P-8 | candidate contexts (incl. Organisation), G-1…G-9 | PROPOSED (candidate) | ARB | [A114](#a114) |
| EM-OPEN-017 | ratification of the Manifesto catalogue | open | ARB | [A115](#a115) |
| O-2 | enter a remembered Working Organisation without counting (B1.5 vs B1.3)? | OPEN | PO ([REG] O-2) | [A31](#a31), [A33](#a33) |
| PBDIGIT-34 | remember the Working Organisation across logins | OPEN — unprioritised | not recorded | [A95](#a95) |
| B3 open edge | support or impersonation establishing a user's Working Organisation | left open | not recorded | [A37](#a37) |
| B8 / B9 | conditional closure; reopens on new business behaviour | conditionally closed | PO | [A42](#a42) |
| PBDIGIT-29 Q1 / Q2 | field or concept; which store is authoritative | open (not prerequisites) | not recorded | [A59](#a59) |
| AD-2 | ownership of voting-time eligibility resolution | STATUS REQUIRES VERIFICATION (recorded open 2026-08-15) | ARB | [A40](#a40) |
| MB-5 / PBDIGIT-49 | eligibility implementations; two homes | finding (2026-08-06); OPEN — not authorised | not recorded / PO | [A96](#a96), [A97](#a97) |
| WO owner | which bounded context owns Working Organisation | UNRESOLVED | not recorded; escalate | [A45](#a45) |
| INV-ORG-SCOPE-1 | ARB ratification | pending | ARB | [A98](#a98) |
| INV-ORG-SCOPE-1 | enforcement (implementation, tests) | open; not authorised | not recorded | [A99](#a99) |
| OQ-OS-1…3 | shared reference data; exception catalogue; bypass classification | OPEN | ARB; PO + ARB + Security; ARB | [A44](#a44), [A117](#a117), [A118](#a118) |
| O-5 | BR-13-S §1a: deletion-eligibility check | OPEN | PO + ARB | [A81](#a81) |
| O-14 | vocabulary split, incl. ElectionMembership's definition | OPEN | ARB | [A60](#a60)–[A63](#a63) |
| O-12 | OQ-ME-1…4 | OPEN | per question | [A69](#a69), [A100](#a100)–[A102](#a102) |
| C-3 / O-17 | "no shared persistence" reading; direct reads (Q-new-1) | OPEN | ARB | [A66](#a66), [A90](#a90) |
| O-18 | integration style (Q-new-2) | OPEN | ARB | [A91](#a91) |
| O-6 / DR-07a | ownership of governance organs | OPEN | PO + ARB + Membership owner | [A77](#a77) |
| ARB-8 | DR-07a vs BDR-03 | OPEN | ARB | [A75](#a75) |
| P-7 | Governance candidate vocabulary | PROPOSED (CANDIDATE) | ARB | [A73](#a73) |
| O-16 / DR-07b | organisational units; `CommitteeAssociation` | OPEN | PO, Membership owner; ARB | [A78](#a78) |
| O-7 | XD-4 authorisation ownership; XD-12 write guard (XD-5 excluded above) | OPEN | ARB | [A88](#a88), [A112](#a112) |
| XD-7 | unknown-fact semantics | OPEN | ARB | [A113](#a113) |
| O-8 / O-9 / O-10 | incompatible positions; Membership ↔ tenure; committee authority and statutes | OPEN | PO | [A84](#a84)–[A86](#a86) |
| D-1 | tenancy direction validation | PO direction, not confirmed | PO; ARB | [A92](#a92) |
| OQ-IDX-1 | is "no universal governance model on every organisation" a platform rule? | no source | not recorded; routing: register owner | [A87](#a87) |
| OQ-IDX-2 | PBDIGIT-30 B1.1 says "membership"; BR-3 says being a User does not automatically make someone a Member. Annotate B1.1? | wording difference | not recorded; routing: register owner | [A13](#a13), [A14](#a14) |
| OQ-IDX-3 | *withdrawn in r2: it rested on the error corrected by F-01 (A47)* | — | — | — |
| OQ-IDX-4 | [RAT] §1 names "Organisation" as owner of OrganisationAffiliation; BR-9 lists "target storage and ownership" as open; Organisation is a candidate context. Does BR-9 concern ownership of the concept, or storage only? | STATUS REQUIRES VERIFICATION | not recorded; routing: register owner | [A48](#a48), [A50](#a50), [A114](#a114) |
| OQ-IDX-5 | PBDIGIT-30 is a sequential record: an earlier "B2 … NEXT" heading and "B3–B9 remain unanswered" / "B4–B9 remain open" precede later "APPROVED" sections and the closing status block. Which passages are current is for the record's owner to confirm | wording difference | not recorded; routing: register owner | [A36](#a36), [A37](#a37) |

### B.2 Change control

- **Rules change only in their authoritative source**, through that source's own process: PO decision, ARB act, or an Architecture Decision / Change Request for the ratified Membership architecture.
- When a source changes, update this index **in the same approved documentation change, where appropriate** (ES-004.3, [`ES-004-Documentation.md`](../../../engineering/governance/ES-004-Documentation.md), itself PROPOSED).
- **This index can never be used to approve, amend or resolve the underlying change.** An edit here without a corresponding source change is an error.
- Statuses here are copies. On disagreement, the source prevails, and the index is corrected.
- New questions found while maintaining the index get an `OQ-IDX-n` entry and are routed to the register owner. They are not answered here.
- Placement policy: [`ADR_20260801_1740_ Documentation Roots and Artifact Placement.md`](<../../adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md>).
