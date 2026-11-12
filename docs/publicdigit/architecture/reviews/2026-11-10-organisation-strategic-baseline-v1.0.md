# Organisation: strategic baseline v1.0 (proposed)

| | |
|---|---|
| **Status** | **ACCEPTED 2026-11-10 (D-106), revision 2c**, by the Principal as Human ARB Chief (act `docs/publicdigit/reviews/2026-11-10-organisation-strategic-baseline-acceptance-act.md`). Accepts the eight conclusions of §13 only; working decisions stay provisional; earlier rulings stay separate acts. No tactical design, code, schemas or tickets. Not committed |
| **Revision 2c (2026-11-10)** | Corrections ruled by the Principal (D-105, B-1), made by the coherence review's author (Claude): **the ratified Membership → Organisation contract CR-01 (C1, C2; D-73, D-77) is cited** where the baseline called the contract absent or provisional (§0, §5, §8, §10, §12, §14) · **"coverage" disambiguated:** *unit coverage* (territory, D-80) vs *Membership time coverage* (CR-01) (§3, §6, §8) · **D-103** ("Member" capacity reflects Membership) and **D-104** (workspace access) stated (§3, §5, §8, §14) · E-4 evidence strengthened with CR-01 · E-14 marked as an open question. Source: `./2026-11-10-publicdigit-strategic-baseline-coherence-review.md`. No other section changed. |
| **Revision 2b (2026-11-10)** | Final audit: Election's ownership cited from the ratified Membership text (`:47`, `:110`) and the Election-Only ADR, not from the shortcut prohibition alone · Authorization limited to **no business-domain truth**; technical permission policy may be its own; scope XD-4 open · tenancy: platform capability [AC], owner and detailed responsibilities open · Organisation identity and registry as an explicit [AC] · D-102 (Identity, working decision) checked and listed · §13 lists every [AC] |
| **Revision 2a (2026-11-10)** | §5 evidence precision: I5 shows only that **Membership does not own** unit belonging; Organisation's ownership is a separate [AC] · §13 acceptance scope reworded (decisions kept as separate acts) · §14 evidence audit added |
| **Revision 2 (2026-11-10)** | Unit belonging: **strategic ownership [AC] separated from the provisional detailed rules** (§5, §7, §8, §10, §12) · "membership ended" provisional everywhere · challenge wording corrected against D-53, D-67 and D-71 (§2, §11) · unit–organ example is not a default (§4.1) |
| **Revision 1** | §4.1 no universal unit–organ cardinality · §2 record versus real-world acts (no "reality prevails") · status labels: working decisions and proposals marked provisional throughout; new status key (§0) · §5 Identity wording verified against XD-3 · §7 collaboration model marked provisional, ownership kept |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=qualified --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/`, as for the ratified Reference Geography baseline. The commission's `architecture/baselines/` folder does not exist; **no new folder was created** (CLAUDE.md: "invent no category and no folder") |
| **Commission** | "Organisation Strategic Baseline v1.0" (Principal DDD Architect's note of 2026-11-10, accepted by the Principal) |
| **Evidence** | Decision record `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (D-1 … D-102; D-102, Identity's boundary, is a working decision) · discovery findings `2026-11-10-organisation-strategic-ddd-discovery-findings.md` (rounds 1 and 2) · ODA-01 v1.2 (PROPOSED) · business rules BR-1 … BR-14 · XD-3 act (D-75) · Membership ratification (2026-10-08; D-96) · Reference Geography ratification (D-93) |
| **Labels** | **[AD]** accepted decision, with its status (**RAT** ratified · **ARB** act · **D** direct Principal decision · **WD** working decision · **APP** approved business rule) · **[BF]** established fact · **[AC]** architectural conclusion · **[TU]** technical uncertainty · **[OQ]** open Principal question · **UNKNOWN** · **[PK]** reported practical knowledge (not documentary) |

---

## 0. Status key (revision 1)

**Why this matters:** accepting this baseline must not silently turn provisional material into binding rules. Every rule below carries one of these statuses.

| Status | Meaning | Examples |
|---|---|---|
| **Ratified boundary** | Ratified or ARB act; binding | Membership (D-96), Identity XD-3 (D-75), Reference Geography (D-93), D-94, D-95 |
| **Principal ruling** | The Principal's answer to an explicit question. **The decision record's §11 header classes all §11 entries as "working decisions" except ARB acts**; these rulings are therefore binding as the Principal's current rulings **but not yet ratified in a baseline** | D-79, D-80, D-97 … D-105 (D-102 … D-105 recorded 2026-11-10 from accepted notes) |
| **Approved business rule** | Confirmed by the PO | BR-1 … BR-8, BR-12 … BR-14, BR-13-S, BR-13a; Decision A and B; PO-DR07-01; K′ |
| **PO direction** | Stated direction, not confirmed as a rule | BR-10 (conflicts with XD-3b K4, open), BR-11 |
| **Working decision (provisional)** | Describes the current model; **not ratified; may change in tactical design or with statutes** | D-1 … D-30 (notably D-3, D-4, D-5, D-13 … D-22, D-27, D-29, D-30), D-40 … D-43, D-65 |
| **Proposal (provisional)** | A proposed working model inside a record | §8.3 C8 working model ("Membership supplies only *membership ended*"): **superseded as an information contract by the ratified CR-01 C1/C2** (revision 2c); what Organisation does with Membership time coverage remains Organisation's design |
| **Architectural conclusion** | This baseline's reasoning; accepted only if the baseline is accepted | marked [AC] |

**Precision on D-82 and D-83:** recorded in §11, but **integrated into the ratified Reference Geography baseline (D-93)** for Geography's scope.

---

## 1. Purpose and scope

This baseline states **what an Organisation is in PublicDigit**, what the Organisation context **owns and does not own**, and **how it relates** to the other contexts. It is written for a domain expert first; DDD terms follow where they add precision.

**Why it matters for a party or association:** members join an organisation, belong to its local units and wings, are served by committees whose areas are defined on the map, and elect office holders under the organisation's own rules. **Each of these is owned by a different context.** If Organisation is drawn wrongly, the error spreads into Membership, Governance and Election.

**In scope:** Organisation identity and registry; structure (units, wings, sections) over time; affiliation; the organisation-local person profile; unit belonging; organisation-defined areas and unit coverage; workspace access (D-104); Organisation's relationships.

**Not in scope:** tactical design (aggregates, events, ports); implementation; organisations' constitutional content (each organisation's own rules).

## 2. The accepted definition

**[AD D-100 (D)]**

> "An Organisation is a self-governing, membership-based collective body (a political party, association, union or federation of such bodies) with its own identity and purpose, governed by written rules: either a constitution or another rules document formally adopted by a recorded decision. It conducts its internal affairs through the organs and office holders authorised under those rules, subject to applicable law and other binding external rules."

> **Federation note:** "A federation is in scope where its members take part as natural persons, directly or as delegates. Membership of organisations in a federation is not modelled now (BR-13a)."

**Explanatory principles [AD D-100, from ODA-01 §3.1, Decision A, D-99]:**
1. **Identity persists** through changes of name, structure, rules, leadership and status.
2. **Identity and authority do not depend on PublicDigit.** The software records an organisation's decisions; it never creates its authority (Decision A: "platform access permissions do not constitute organisational authority"; D-1a).
3. **Membership, Governance, Election and other capabilities serve the Organisation; none constitutes it.**
4. **A platform record** (the bootstrap organisation, demo data) **is not an Organisation** (BR-12).
5. **Tenancy is not part of the definition.**
6. **Sections are classified under D-99** (§4.2).

**What follows from the definition:**
- **In scope now [AD D-98]:** political parties, associations (including diaspora associations), unions, and federations whose members are persons. **Companies and memberless foundations later, as a separate capability**, by their own decision.
- **Written rules required [AD D-97]:** a constitution, or a rules document adopted by a recorded decision. **Unwritten custom alone is out of scope**, because competence (D-12) and powers (D-38) must be traceable to a written provision.
- **The real organisation and its PublicDigit representation are different things [AC, revised].** Four things are kept apart:
  1. **the real-world act or decision** of the organisation;
  2. **the evidence** of it submitted to PublicDigit;
  3. **the assessment** of that act's authority and validity under the organisation's written rules;
  4. **PublicDigit's recorded representation.**
- **PublicDigit records organisational acts and their evidence; it never creates organisational authority.** An asserted act does not become authoritative merely by being submitted.
- **A discrepancy is handled through the applicable authority and evidence process**, normally the organisation's competent organ under its written rules. **Whether a PublicDigit challenge (Contestation and Adjudication) is available for a given organisational act is not decided in the records** [BF]: D-53 settles the review route only for **moderation** decisions (the organisation's rules; default the independent tribunal); D-67 only makes Adjudication one bounded context; **D-71 governs only the effect of a challenge once filed** (the decision stays effective unless the rules provide otherwise; only the tribunal can suspend it). Organisational authority and PublicDigit's adjudication process stay distinct. History is preserved; corrections are recorded forward. **This baseline sets no universal rule for who resolves a discrepancy.**

## 3. Core business concepts (ubiquitous language)

| Term | Meaning | Not to be confused with |
|---|---|---|
| **Organisation** | As defined in §2 | A tenant; a platform record; a Governance organ |
| **Written rules** | A constitution or an adopted rules document, in versions (D-1) | Platform configuration (D-1a) |
| **Unit** | A place in the organisation's structure where people **belong**: national, provincial, district, local | An organ; an official place |
| **Wing** | A unit-like section created under the organisation's rules for a group (youth, women, students, diaspora chapter) | A separate organisation (D-99) |
| **Section** | Any subdivision named by the organisation (branch, chapter, wing); classified under D-99 | — |
| **Organ** | A body that **decides** under the written rules (an executive committee, a convention); its seats are positions | A unit (PO-DR07-01) |
| **Office holder** | A person holding a position, who may decide alone where the rules allow (Decision A) | An administrator with platform permissions |
| **Affiliation** | A person's relationship **with an organisation**, in a capacity (User, Member, Worker, Guest, voter). **The capacity "Member" reflects Membership's facts and never establishes membership** [AD D D-103] | Membership; belonging; electoral entitlement; workspace access |
| **Unit belonging** | A person's relationship **with a unit or wing**, in a capacity, with a basis act and a period | Membership; authority |
| **Membership** | The authoritative membership fact and its periods (Membership context) | Affiliation |
| **Membership time coverage** | Membership's term for the time during which a membership is in force (INV-COV), supplied to Organisation by the ratified CR-01 contract (C1, C2) [AD RAT D-73] | Unit coverage |
| **Workspace access** | Permission to enter and work in the organisation's digital workspace, **granted under the organisation's own rules**, technically enforced by Authorization [AD D D-104] | Account; sign-in; working organisation; affiliation; Membership |
| **Person profile** | The organisation's own record of a person: name, contact details, address as supplied | Identity's canonical person |
| **Organisation area** | A territory the organisation defines for itself (D-82) | An official geographical reference |
| **Unit coverage** | The official references and/or organisation areas a unit serves (D-80) | Belonging; Membership time coverage |
| **Structural change** | Creating, changing, moving, merging, dividing, suspending, dissolving or restoring a unit | A Geography structural change (D-84) |

**Note on "structural":** in Geography, "structural change" is a publication-governance classification (D-84). Here it means a change to an organisation's own structure. **The two must not be conflated.**

## 4. Structure and lifecycle

### 4.1 Units, wings and organs

- **[AD APP PO-DR07-01] Governance committees are organs; organisational units are a distinct concept.**
- **[AC, revised] Units and organs are related through the organ's mandate and scope, as established by the organisation's written rules.** No universal cardinality is assumed. For example, a disciplinary committee may cover several provincial units, and a national election committee all local units. **The precise relationship model is for tactical design, subject to documentary evidence.** An organ is never where people belong as members; its seats are **positions** (Governance).
- **Example (not a default relationship):** "District Committee of Kathmandu" may name both
  - **the district unit,** where members belong, which covers certain places;
  - **the district executive committee,** which decides certain matters.

  **They are distinct concepts, never merged. A link between this unit and this organ exists only where the written rules and the organ's mandate and scope establish it.**
- **No committee is assumed to be the highest authority anywhere.** Which organ is highest is a matter of each organisation's written rules.

### 4.2 Sections: unit or separate organisation

**[AD D-99]**
- A section that exists **under the parent's written rules** is a **unit or wing** of the parent.
- A section with **its own written rules adopted by its own members** is a **separate Organisation**. The other organisation gains **no governing power** over it in PublicDigit (BR-13a), and their data stay separate.
- **Where the documents are not on file, the classification is UNKNOWN**, never assumed from a name or from custom.

### 4.3 Structure over time

**Working decisions on how changes take effect** (all [AD WD]):

| Topic | Decisions |
|---|---|
| "As at" fallbacks and successors; never written as changes to history | D-13 |
| Restoring a dissolved unit | D-14 |
| Successor continuity | D-15 |
| Divisions and their exceptions | D-16 … D-18 |
| Fallback skipping a level | D-19 |
| Change sets and indivisible groups | D-30, D-34 |
| Effective dates and technical delay | D-40 |
| Restructurings pending at an election cut-off | D-41 … D-43 |
| Restoring belonging after an exclusion is annulled | D-27 |

- **History is kept and corrected forward.**

### 4.4 Who may change the structure

- **[AD (APP) Decision A; D-97]** **The competent organ or authorised office holder under the organisation's written rules** decides. **PublicDigit applies no platform default.**
- **[PK] D-21** ("only the Highest Committee makes formal structural changes; lower committees may propose") **describes the organisations under discovery and is not a platform rule** (its own text).
- **For NRNA and any organisation whose rules are not on file: UNKNOWN.**
- **When the rules are genuinely silent** (a declared gap): **D-65** applies. **No structural default is invented.**
- **The real-world decision and PublicDigit's record of it are separate [AC]:**
  - the organ decides;
  - **Governance** records the organ, its competence and the decision (D-12: competence on the decision date);
  - **Organisation** maintains the resulting structure (strategic ownership [AC], §5; the exchange that carries it is the provisional D-29 model, §7).

## 5. Affiliation, person profile, membership and belonging

| Concept | Owner | Rule |
|---|---|---|
| **Canonical person** | **Identity** [AD ARB D-75] | XD-3 §2: "**One canonical Person** exists, owned by Identity" (a bare `PersonId`); "**Each organisation receives a different opaque `PersonRef`** for the same human"; "**Only Identity can resolve** one organisation's `PersonRef` to the canonical Person or to another organisation's reference." **No business context may query that relationship.** One canonical Person per human is the aim, **not a guarantee:** independent imports are not assumed to be the same person (BR-11, PO direction), and merges happen only by Identity's explicit act (C-5) |
| **Person profile** (name, contact, address as supplied) | **Organisation**, each for its own people [AD D D-101] | Organisation-local; **never disclosed to another organisation**; does not establish who the person is |
| **Affiliation**, in a capacity | **Organisation** [AD D D-101] | Organisation-level; a guest can be affiliated without belonging to a unit. **"Member" reflects Membership's facts (CR-01) and is never set independently** [AD D D-103] |
| **Workspace access** | **Organisation**, under its own rules [AD D D-104] | A distinct fact with its own basis and lifecycle; enforced technically by Authorization; never implied by an Account, sign-in, working-organisation selection, affiliation or Membership |
| **Membership** | **Membership** [AD RAT; D-96] | Only Membership states membership truth |
| **Unit belonging** | **Organisation**: **strategic ownership [AC], proposed for acceptance** | **Two separate propositions:** (1) **Membership does not own unit belonging**, supported by the ratified Membership architecture: most strongly by **CR-01** (D-73), under which Membership supplies Organisation only its **Membership time coverage** (C1, C2) and "Organisation never interprets Membership facts"; and by I5, which maps a list including units and committee roles collectively to "a type, a provenance, another relationship or another organisation", never to overlapping membership periods (revision 2c precision) [BF]; (2) **Organisation owns it**, an architectural conclusion [AC] **not proven by I5**, supported by the Organisation domain analysis (belonging describes the organisation's own internal structure, §4), by working decisions D-3 and D-22 (provisional) and by the Identity discovery's assignment (proposed). **Accepting this baseline would accept the ownership; it would not ratify D-3 or D-22.** **Detailed rules (provisional unless marked):** placement by the organisation's rules, by default only individual acts, a residence rule only where adopted and recorded (**Principal ruling D-79**); belonging never carries authority or membership status (D-3, WD); creating, changing, ending, restoring and allocating belonging (D-3, D-13 … D-19, D-27, WD); what Membership supplies: **Membership time coverage through the ratified CR-01 contract (C1 event, C2 query)**; how belonging reacts to it is Organisation's design (the §8.3 "membership ended" proposal is superseded as an information contract) |
| **Eligibility and entitlement** | **Election** [AD RAT boundary] | Never inferred from affiliation, belonging, profile or address |

**What must never be inferred [AC]:**
- **from an affiliation, membership:** a Guest is not a Member (BR-3);
- **from an affiliation in the capacity "voter", an entitlement.** The capacity records an organisational relationship (BR-6 … BR-8); **Election alone decides eligibility and entitlement;**
- **from an address, belonging** (D-80) **or entitlement;**
- **from a person profile, identity.**

## 6. Areas and unit coverage

- **Official geographical facts belong to Reference Geography** [AD RAT D-93]. Organisation **consumes** them read-only, at one shared τ (D-73).
- **Organisation areas belong to the organisation that defines them** [AD D-82]. An organisation area is never an official reference; it is not visible to other organisations.
- **Unit coverage** is 0..n official references and/or organisation areas per unit [Principal ruling D-80]. (Not to be confused with Membership time coverage, CR-01.)
- **Unit coverage never establishes belonging or entitlement** (D-79, D-80).
- **A change in published Geography never changes belonging by itself.** Examples: a district re-parented, or a UN regrouping (D-92). Organisation decides, under its own rules, what such a change means for its unit coverage.
- **Streets are address components, not official units** [AD D-83]. Addresses, with their components and their resolution against Geography, live in **the organisation's person profile** [AD D-101].

## 7. Authority and the Governance relationship

**[AC] Three distinct roles; Governance is not a universal decision-maker:**

| Role | Who | What |
|---|---|---|
| **Source of authority** | **The organisation's written rules** (D-97, D-1) | Say which organ or office holder may decide what |
| **Decider** | **The competent organ or authorised office holder**, in the real world (Decision A) | Makes the decision |
| **Record of authority** | **Governance context** [AD WD D-4, D-5, D-10; ARB D-72] | Organs, positions, tenures, mandates, competence, decisions |
| **Record of structure** | **Organisation context** [AC; current model in working decisions D-22, D-29, provisional] | Units, wings, belonging, as decided |

**Strategic boundary [AC]:** Governance owns authority and mandate facts; Organisation owns the resulting structure and unit belonging (strategic ownership, §5; detailed rules provisional); **every structural change rests on a valid decision under the organisation's written rules.**

**Working collaboration model (provisional, D-29; for tactical design, not frozen here):** decided structural change sets and belonging allocations from Governance to Organisation; structure and belonging "as at" a date from Organisation to Governance for competence checks. **Message direction and contents are not fixed by this baseline.**

**Appointing office holders, and establishing organs and mandates, are Governance matters**, under the written rules (D-4, D-7, D-74, D-76, D-78). Organisation neither appoints nor holds mandates.

**Statute-dependent and UNKNOWN until rules are on file:** incompatible positions (O-8); membership and tenure (O-9); committee authority (O-10).

## 8. Bounded-context ownership and relationships

| Business fact | Owner | Organisation provides | Organisation consumes | Never inferred or transferred |
|---|---|---|---|---|
| Organisation identity and registry | **Organisation** ([AC], E-0; current model in D-22, WD) | Registry | — | Authority from the platform |
| Structure, units, wings | **Organisation** | Structure as at τ | Decisions changing the structure (from Governance; exchange provisional, §7) | That a unit is an organ |
| Unit belonging | **Organisation** (strategic ownership [AC]; detailed rules provisional, §5) | Belonging as at τ | **Membership time coverage through the ratified CR-01 contract** (C1 `MembershipCoverageResolved`, C2 `CoverageKnownAt`; D-73, D-77); how belonging reacts is Organisation's design | Membership or authority from belonging |
| Affiliation | **Organisation** | Its own affiliations, to the affiliated person and, for sign-in discovery, through Identity's mechanism | Membership time coverage, for the capacity "Member" (D-103) | Membership or entitlement; "Member" set independently of Membership |
| Workspace access | **Organisation**, under its own rules (D-104) | The grant, to Authorization for enforcement | — | From an Account, sign-in, working-organisation selection, affiliation or Membership |
| Person profile | **Organisation** | — | Person reference (Identity); Geography resolution | Canonical identity |
| Areas and unit coverage | **Organisation** | Unit coverage as at τ | Official references (Geography) | Belonging or entitlement from coverage |
| Organs, positions, tenures, mandates | **Governance** | Structure and belonging as at τ | — | Organisation appointing |
| Membership truth | **Membership** (RAT) | — | **Membership time coverage via CR-01 C1/C2 (RAT)**; Organisation never interprets Membership facts | Organisation stating membership |
| Person identity | **Identity** | — | `PersonRef` | Cross-organisation linking (XD-3) |
| Electorate and entitlement | **Election** | Belonging (and structure) at one τ | — | Entitlement from Organisation's facts |
| Official geography | **Reference Geography** | — | Published references at τ | Organisation areas as official |
| Payment and settlement | **Finance** [AD RAT] | — | — | — |
| Tenancy (Working Organisation, data scoping, demo mode) | **A platform capability, not Organisation [AC]; owner and detailed responsibilities open (HD-8); INV-ORG-SCOPE-1 an approved principle, ARB act pending** | — | Working Organisation context | Organisational authority from tenancy |
| Permission checks | **Authorization, supporting [AC]:** owns **no business-domain truth** (no organisational, membership, governance, electoral or constitutional facts; FROZEN BDR-07: consumes mandate). **It may own technical permission policies and access decisions**; its scope is open (XD-4). Identity is not the permission authority either (D-102, WD) | — | Mandates (Governance); the organisation's rules | Organisational or constitutional authority |

**Cross-context time [AD ARB D-73]:** every collaboration uses one shared τ; complete answers or refusal. **PO-8 (Membership's timestamp handling) remains a platform dependency** (D-93 act §3).

## 9. Privacy and information boundaries

- **One organisation never receives another organisation's person records, affiliations, belongings or areas** [AD D-101; XD-3 §2 items 3 to 5; BR-13a].
- **Identity resolves references only for its own purposes**; no business context may query cross-organisation relationships (XD-3 §2 item 3).
- **Sign-in discovery** ("which organisations can I enter?") is **an Identity mechanism, not an Organisation one** (D-102, working decision, not ratified), and the mechanism itself is **not decided** [AD D-101]:
  - Identity evaluates the stored minimal mapping (L1) and the keyed derivation (L2) within XD-3's conditions C-1 to C-5.
  - **INV-AFF-1's stricter "no persisted central register" wording was not adopted** and stays a proposal tied to the mechanism.
- **A `PersonRef` is not proof of identity or authorisation** (INV-AFF-1 boundaries, carried as a principle by XD-3).

## 10. Decisions, conclusions and recommendations

| Kind | Items |
|---|---|
| **Ratified boundaries** | Membership (D-96); Identity XD-3 (D-75); Reference Geography (D-93, including D-82, D-83); D-67, D-72 … D-74, D-77 (ARB); D-94, D-95 |
| **Principal rulings (binding, not yet ratified in a baseline)** | D-79, D-80, D-97 … D-105 (D-102 Identity's boundary; D-103 "Member" reflects Membership; D-104 workspace access; D-105 corrections and separate acts) |
| **Approved business rules** | BR-1 … BR-8, BR-12 … BR-14, BR-13-S, BR-13a; Decision A and B; PO-DR07-01; K′ |
| **PO directions** | BR-10 (conflicts with XD-3b K4), BR-11 |
| **Working decisions (provisional)** | D-1 … D-30 (notably D-3, D-4, D-5, D-13 … D-22, D-27, D-29, D-30), D-40 … D-43, D-65 |
| **Proposals (provisional)** | §8.3 C8 working model, **superseded as an information contract by CR-01 (RAT)** (revision 2c) |
| **Architectural conclusions [AC]** | **Organisation is the strategic owner of structure and unit belonging (§5)** · Unit ≠ organ, related through mandate and scope (§4.1) · four-way distinction of act, evidence, assessment and record (§2) · three roles of authority (§7) · tenancy is a platform capability (§8) · Authorization owns no truth (§8) · legacy classification by function (§11) |

**This baseline elevates no working decision, proposal or PO direction.** Accepting it would accept **the strategic boundaries and architectural conclusions it states**; it would not ratify D-1 … D-30, the §8.3 proposal or BR-10 and BR-11 one by one. **Whether acceptance also ratifies the Principal rulings D-79, D-80 and D-97 … D-101 is part of the acceptance decision itself** (§13).

## 11. UNKNOWNs and deferred questions

| Item | Status | What would resolve it |
|---|---|---|
| Who may change the structure, per organisation | **UNKNOWN** | Each organisation's written rules (NRNA first) |
| O-8, O-9, O-10 (incompatibility, tenure, committee authority) | **UNKNOWN** | Statutes |
| NRNA sections: units or separate organisations (D-99) | **UNKNOWN** | NRNA constitution |
| **Legacy `committees` records (O-16)** | **Open** | **Classify each by function, not by name:** where it decides, it is an organ; where people belong, it is a unit; where both, one of each, linked. **No record is classified without reviewing its data** |
| **Availability of a PublicDigit challenge for organisational acts** (beyond moderation, D-53) | **Open; not decided in the records** | A later decision with the Governance and Adjudication work |
| Committee ownership (DR-07a; ARB-1 … 8) | Open, now with the Principal (D-94) | DR-07 paper |
| Tenancy owner (HD-8) | **[OQ] deferred** | With INV-ORG-SCOPE-1 ratification |
| Authorization scope (XD-4) | **[OQ] deferred** | — |
| FeePaid: obligation owner and direction | **[OQ] deferred**; does not block this baseline because it concerns Finance and Membership only. **Fixed:** payment and settlement are Finance's; standing is Membership's | Finance–Membership discovery (findings R2.6: options F-1, F-2) |
| Sign-in discovery mechanism | **[TU]** for Identity's design | Feasibility evaluation |
| Vocabulary (O-14: member / role / observer / ElectionMembership) | Open | — |
| Organisation-to-organisation relationships | **Out of scope now** (BR-13a; D-100 federation note) | A future decision |
| Companies, memberless foundations | **Later capability** (D-98) | Own discovery |

**Consequence for the ratified Geography baseline:** its §C.2 and §C.6 still call address ownership a recommendation; **D-101 has decided it.** Geography's design is unaffected (it never owned addresses). **A wording update to that document is due under D-101**, recorded with a header note (D-93 act §4).

## 12. What this baseline does not decide

- **No tactical design:** aggregates, entities, value objects, events, commands, repositories, ports, APIs.
- **No implementation, schema, transaction or mechanism**, including sign-in discovery.
- **No constitutional content** of any organisation, and **no platform default for structural authority.**
- **No ratification of working decisions** D-1 … D-30, **including D-3 and D-22**, even though the strategic ownership of unit belonging is proposed (§5).
- **No new Membership–Organisation information contract:** the ratified CR-01 contract (C1, C2) applies; this baseline adds nothing to it and changes nothing in it (revision 2c; the earlier "no contract" wording was wrong).
- **No rule on whether a PublicDigit challenge is available for organisational acts** (§2).
- **No owner for tenancy or Authorization**, and no decision on FeePaid.
- **No classification of actual legacy records.**
- **No change** to Membership, Identity, Reference Geography, Governance or Election baselines.

---

**Coherence check performed** against:
- D-93 … D-101;
- the Membership ratification;
- the Reference Geography ratification;
- D-73;
- XD-3;
- D-79, D-80, D-82, D-83;
- BR-13a;
- the discovery findings.

**One inconsistency found and reported, not resolved:** the Geography wording on address ownership (§11). **No contradiction found within this baseline.**

## 13. Questions for the acceptance decision (not now)

When the Principal decides whether to accept this baseline, two things are distinct:

- **A. Acceptance of the Organisation strategic architecture.** It would establish these architectural conclusions, and only these:
  1. **Organisation owns its identity and registry** (E-0);
  2. **Organisation owns its structure and unit belonging; Membership independently owns membership truth** (E-5, E-4);
  3. **unit ≠ organ, related only through the organ's mandate and scope** (E-6);
  4. **act, evidence, assessment and record are distinct; PublicDigit creates no authority** (E-7);
  5. **written rules → decider → Governance record → Organisation structure** as the authority boundary (E-8), **without the D-29 exchange**;
  6. **tenancy is a platform capability, not Organisation** (E-11), **without deciding its owner;**
  7. **Authorization owns no business-domain truth** (E-12), **without deciding its scope;**
  8. **legacy records are classified by function** (E-13), **without classifying any.**
- **B. The status of the Principal rulings D-79, D-80 and D-97 … D-105.** **Ruled (D-105, B-4): they stay separate acts, referenced by this baseline,** with their own identity, provenance and history; **not re-ratified or rewritten** by the acceptance.

**Working decisions D-1 … D-30 (including D-3, D-22, D-29), the §8.3 proposal and BR-10, BR-11 remain provisional either way.**

## 14. Evidence audit (revision 2b)

| # | Conclusion | Supporting source | Status |
|---|---|---|---|
| E-0 | **Organisation owns its own identity and registry** | D-100 (identity persists, independent of PublicDigit); D-22 (WD) for the current model | **[AC], for acceptance** |
| E-1 | Organisation is a real self-governing body, not a tenant or platform record | D-100; Decision A; BR-12; D-1a | **Principal ruling / approved rule** |
| E-2 | Sections classified by source of authority | D-99 | **Principal ruling** |
| E-3 | Affiliation records and person profile are the organisation's; never disclosed to another | D-101; XD-3 §2 items 3 to 5 | **Principal ruling; ratified Identity act** |
| E-4 | Membership does not own unit belonging | **CR-01 C1/C2 (D-73)**: Membership supplies only time coverage; "Organisation never interprets Membership facts"; Membership I5 as supporting evidence (revision 2c) | **Ratified** |
| E-5 | **Organisation owns its structure and unit belonging** | domain analysis §4; D-3, D-22 (WD); Identity discovery (proposed) | **[AC], for acceptance** |
| E-6 | Unit ≠ organ; related through the organ's mandate and scope; no cardinality | PO-DR07-01 (approved) for the distinction; the relation is reasoning | **Approved rule + [AC]** |
| E-7 | Act, evidence, assessment and record are distinct; PublicDigit creates no authority | Decision A; D-1a (the "no authority" part); the four-way split is reasoning | **Approved rule + [AC]** |
| E-8 | Written rules → decider → Governance record → Organisation structure | Decision A, D-97 (authority source); D-4, D-5, D-72 (Governance owns organs and mandates, WD/ARB); D-22, D-29 (WD) | **[AC]; exchange provisional** |
| E-9 | Official geography versus organisation areas; unit coverage never decides belonging | D-82, D-83 (ratified with D-93); D-79, D-80 | **Ratified / Principal rulings** |
| E-10 | Election alone decides eligibility and entitlement | **Ratified Membership architecture** assigns "ElectionRegistration / VotingEntitlement / Exercisability / Vote" to Election (`:47`, citing ADR_20261004_1549 and ADR-T11) and "`Eligibility`: Election's (not a Membership predicate)" (`:110`); "Membership … grants no entitlement" (`:50`); the Election-Only ADR is DECIDED for its scope | **Ratified** (for the boundary); Election's own baseline is working design (D-32) |
| E-11 | Tenancy is a platform capability, not Organisation | Decision A (platform permissions are not organisational authority, approved); ODA-01 §3.1 (tenancy not in the definition, proposed); D-100 principles | **[AC], for acceptance**; owner and detailed responsibilities **open** (HD-8) |
| E-12 | Authorization owns no business-domain truth; it may own technical permission policy | BDR-07 (frozen programme scope); D-102 (Identity is not the permission authority, WD) | **Frozen record + [AC]**; scope (XD-4) open |
| E-13 | Legacy records classified by function, not name | PO-DR07-01 (existing records unclassified); the rule is reasoning | **[AC], for acceptance**; per-record classification open (O-16) |
| E-15 | Sign-in discovery is Identity's mechanism, not Organisation's; mechanism undecided | D-102 (WD); D-101 | **Working decision + Principal ruling** |
| E-16 | The affiliation capacity "Member" reflects Membership; one source of membership truth | D-103; CR-01 C1/C2 (RAT) | **Principal ruling** |
| E-17 | Workspace access is a distinct Organisation fact, enforced by Authorization | D-104; D-102 (WD) | **Principal ruling** |
| E-14 | *(an open question, not a conclusion; listed here for continuity)* No general PublicDigit challenge for organisational acts is asserted | D-53 (moderation only), D-67, D-71 (effect only) | **Open** (§11) |

**Genuine unresolved strategic questions** (none blocks acceptance):
- availability of PublicDigit challenges for organisational acts;
- tenancy owner (HD-8);
- Authorization scope (XD-4);
- FeePaid obligation owner and direction;
- committee ownership (DR-07a);
- vocabulary (O-14);
- statute-dependent UNKNOWNs (structural authority per organisation, O-8 … O-10, NRNA sections).

**Readiness:** **ready for the Principal's acceptance decision** after the final independent review, with the two acceptance matters in §13.

---

## Amendment note A-1 (2026-11-10, D-112): later rulings D-107 and D-108

*Append-only. The accepted text above (D-106, revision 2c) is not changed; D-106 stays accepted. This note records how later rulings change the reading of specific statements.*

| Statement in the accepted text | Superseded by | Current reading |
|---|---|---|
| §3, **Affiliation** row: capacities "(**User**, Member, Worker, Guest, voter)" | **D-108** | "User" is **not** a standard affiliation capacity. A person can be in an organisation's register with no capacity at all; workspace access is its own fact (D-104); an Account is never a reason for an affiliation |
| §0 and §10: "Approved business rules BR-1 … BR-8, BR-12 …", insofar as **BR-2, BR-8 and BR-12** speak of a "User affiliation" created at registration | **D-107** | **Registration creates an Account only, and no affiliation of any kind.** BR-2 and BR-8 read: "a person who registers becomes a registered user of PublicDigit, i.e. holds an Account". BR-12's statement that registrants are not affiliated with the operator organisation stands; its clause that the bootstrap record holds "the User affiliation created at registration" is superseded |
| §2 principle 4, E-1: "a platform record … is not an Organisation (BR-12)" | **Unchanged**, reinforced by D-107 (the bootstrap record holds no affiliations) | — |

**Not affected:** the eight accepted conclusions of §13; D-79, D-80, D-97 … D-105 as separate acts.
