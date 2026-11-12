# Organisation strategic DDD discovery: findings (round 1)

| | |
|---|---|
| **Status** | **PROPOSED discovery evidence.** Decides, ratifies and authorises nothing; changes no status in any other record. Not independently reviewed (Claude family) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Commission** | "Organisation Strategic DDD Discovery" (Principal DDD Architect's note, accepted by the Principal, 2026-11-10) |
| **Binding** | Membership (ratified 2026-10-08, CR-01 / D-73) · Identity XD-3 (D-75) · Reference Geography (D-93) · D-94 (ARB authority) · D-95 (Geography exception) |
| **Labels** | [AD] accepted decision (with its status: RAT ratified · ARB act · WD working decision · APP approved) · [BF] fact with source · [AC] architectural conclusion · [OQ] Principal decision · UNKNOWN |

## 1. Disagreements and contradictions (first)

| # | Point | Evidence | Consequence |
|---|---|---|---|
| **X-1** | **"FeePaid is a Finance fact" is not established Membership architecture.** The ratified text says only "fees reference periods; … settlement is Finance's" and that `Standing` is derived from fee facts. FeePaid's direction is a **recorder's note (N-5), "a reading for EP-01 to honour, not a decision"**; pre-ratification theory states the **opposite** direction | ratified record `:109`, `:155`; context map §4 (Finance row) | The commission's premise would silently ratify a reading. **Treated as open** (§6) |
| **X-2** | **"Affiliation" in the records means a person's association with an organisation** (User, Member, Worker, Guest, voter: BR-2 … BR-8, BR-12), **not one organisation affiliated with another.** Organisation-to-organisation relationships are a separate concept, and **BR-13a says PublicDigit provides no cross-organisation governance now** | business rules BR-1 … BR-14; INV-AFF-1 record; ODA-01 §9 | Two concepts must not share one word. Org-to-org stays out of scope now (BR-13a) |
| **X-3** | **The Membership ratification was an act of the "Principal Reviewer" (a human act), not recorded as an ARB act.** D-94 confirms acts "recorded as ARB acts". Whether it also covers this one is not stated | Membership act `:1-4`; D-25 (the Principal Reviewer is later advisory for Change Requests); D-94 | Organisation's discovery relies on Membership's ratified boundary. **[OQ] 1 (§8)** |
| **X-4** | **Organisation's foundation is working decisions only.** D-3, D-13 … D-22, D-27, D-29, D-30 and D-79 are WD ("none is ratified"); ODA-01 v1.2 is PROPOSED with Gate F open; the context itself is a **candidate** (ADR-004) | decision record §8 … §10 status lines; status record §9a | Strong material, but no binding baseline yet |
| **X-5** | Entry-point records are stale (status record 2026-11-08; context map 2026-11-02) | — | Already identified; correction belongs to the baseline step, not to discovery |

## 2. Business meaning of Organisation

**Candidate (ODA-01 §3.1, [P]):** "A collective body with its own identity, purpose and rules, which decides its internal affairs through its competent organs or authorised office holders, under its own constitution (statutes), within the law and any external rules that bind it."

**What is established around it:**
- [AD APP] **Decision A (PO-ORG-MEM-01):** the Organisation owns its Membership domain; decisions are made by a competent organ or an authorised office holder; **platform permissions are not organisational authority.**
- [AD WD D-1, D-1a] Each organisation has its own constitution and versions; **platform configuration creates no authority.**
- [AD APP BR-12, K′] PublicDigit's own organisational life is an ordinary organisation, separate from the platform record.
- [AD APP BR-13, BR-13a] **No business rules across organisations;** independence is the default.

**Still open:**
- [OQ ODA-M-2] the meaning itself;
- ~~[OQ ODA-M-20] are organisations without written statutes in scope?~~ **Ruled: [AD] D-97, written rules of any form (a constitution or an adopted rules document); unwritten custom out of scope.** **Second part ruled: [AD] D-98:** membership-based bodies now (parties, associations, unions, federations of them); companies and memberless foundations later, as a separate capability.
- ~~[OQ ODA-E-1] is a section (a wing, a branch) a unit or a separate Organisation?~~ **Ruled: [AD] D-99:** decided by where its authority comes from (the parent's written rules → unit or wing; its own written rules adopted by its own members → separate Organisation); UNKNOWN until the documents are on file.

**[AC]** For political parties and diaspora associations, the stable common meaning is **a self-governing body whose authority comes from its own rules, not from PublicDigit**. The variations are which organs and offices exist, how structure is changed and who may belong in what capacity. All of these come from each organisation's rules (D-1). **PublicDigit must model the variation as rules, never as fixed types.**

## 3. What Organisation owns and does not own

| Owns (status) | Does not own |
|---|---|
| **Registry** of the organisation (WD D-22) | **Membership truth** (RAT Membership) |
| **Structure:** units, wings, their tree over time, restructuring by change sets (WD D-13 … D-22, D-30) | **Authority:** organs, positions, tenures, mandates, competence (WD D-4, D-5; ARB D-72) |
| **Unit belonging** of people, in every capacity (WD D-3, D-22, D-79) | **Electorate and entitlement** (RAT Membership boundary; D-32, D-41 … D-43) |
| **Organisation-defined areas** (AD D-82) and **coverage** of units (WD D-80) | **Official geography** (RAT D-93) |
| **Affiliation records** of its own people, person ↔ organisation (WD per Identity discovery; BR-9 storage **open**) | **Persons** (ARB D-75) |
| | **Making** structural decisions: **Governance decides** them; Organisation **applies** them (WD D-21, D-29) |

**[AC]** The Governance–Organisation exchange is the decisive boundary: **Governance decides, Organisation holds the structure that results** (D-29: R-1 decisions in; R-2 `StructureAsAt` and `BelongingAsAt` out). It is designed, but only as working decisions.

## 4. Organisational units versus governance organs

- [AD APP PO-DR07-01] **Governance committees are organs; organisational units are a distinct concept;** existing records are unclassified; `CommitteeAssociation` is unresolved.
- [BF] The legacy `committees` table holds both (DR-07 paper `:224`; the code lives under `app/Contexts/Membership`).
- **Example:** a party's "District Committee of Kathmandu" is usually **both** the district *unit*, the place where members belong, **and** the district's executive *organ*, the body that decides. **The same name hides two concepts.**
- **[AC]:**
  - **one unit has 0..n organs;**
  - **an organ belongs to exactly one unit's governance;**
  - an organ is never where people "belong" as members. **Its seats are positions** (Governance).
- **Open:**
  - **classifying the legacy records** (O-16, DR-07b);
  - **committee ownership** (DR-07a; ARB-1 … 8);
  - incompatible positions (O-8);
  - membership and tenure (O-9);
  - committee authority (O-10).

  **O-8 to O-10 need statutes: UNKNOWN until registered.**

## 5. Belonging, affiliation, areas and coverage

| Concept | Meaning ([AC] unless marked) | Owner | Status |
|---|---|---|---|
| **Affiliation** | A person's association with **an organisation**, in a capacity (User, Member, Worker, Guest, voter) | Organisation (records); **discovery at sign-in through Identity** | WD; **storage BR-9 and discovery INV-AFF-1 open (referred)** |
| **Unit belonging** | A person's place in **a unit or wing**, in a capacity, with a basis act and a period; never carries authority or membership status | Organisation | WD D-3 (§8.3 working model) |
| **Membership** | The membership fact and its periods | Membership | RAT |
| **Organisation area** | A territory an organisation defines for itself | Organisation | AD D-82 |
| **Coverage** | The official references and/or own areas a unit serves | Organisation | WD D-80 |
| **Address** | What a person supplied, its components and their resolution | **Not decided**: recommended Organisation person-profile capability (Geography §2a, a recommendation) | open |

**Rules that already hold:**
- **an address never establishes belonging** (D-80);
- **coverage never establishes belonging or entitlement** (D-79, D-80, D-82);
- **Membership supplies only "membership ended"** to belonging (§8.3).

**On address ownership, the evidence still points to Organisation's person profile, without assuming it:**
- names and contact details are **organisation-local** (XD-3 §2 item 5), their owner within the organisation "follows INV-AFF-1";
- staff and guests without membership also have addresses (V-54), which **rules out Membership as owner.**
- **The alternative is a separate profile capability.** It would be justified only if profiles carried rules of their own, and **no evidence shows that.**

## 6. Cross-context relationships

| Fact | Owner | Consumer | What the consumer decides | Consistent? |
|---|---|---|---|---|
| Official references | Geography (RAT) | Organisation (areas, coverage); address owner (resolution) | Its own coverage meaning | Yes (D-82, D-92) |
| Membership facts; "membership ended" | Membership (RAT) | Organisation (belonging), Election | Belonging closure; eligibility | Yes |
| Structural decisions | Governance (WD) | Organisation | Applies them | Yes (D-29), WD only |
| Structure and belonging as at τ | Organisation (WD) | Governance, Election | Competence; electorate | Yes, **one shared τ (D-73)** |
| Person, references | Identity (ARB) | all | — | Yes |
| **Fee facts** | Finance (settlement RAT) | Membership (`Standing`) | standing | **Direction of FeePaid open (X-1)** |
| **Working Organisation, scoping** | **owner unnamed** (rules APP: B1, B10, K′, INV-ORG-SCOPE-1) | all | — | **Gap** |
| **Authorization** | **owns no truth** (FROZEN BDR-07); consumes mandate | all | permission decisions | Scope XD-4 open |

**No duplicated ownership found.**

**Hidden dependency:** Election's electorate depends on Organisation's belonging, which is **WD only**, while the Membership–Election boundary it sits beside is **RAT**.

## 7. Missing evidence and UNKNOWN

- **No statutes on file** (roadmap phase 2: NRNA request not yet sent).
  - **UNKNOWN:** which organs and offices exist; how belonging is assigned beyond D-79's default; incompatibility; committee authority; whether sections are units or organisations.
  - **Would resolve it:** at least one authoritative, versioned constitution, ideally NRNA plus two other organisations (roadmap §7).
- **Tenancy owner:** no business evidence that tenancy carries organisational rules.
  - **[AC]** It is a **platform capability**: Working Organisation, data scoping, demo mode. **It is not Organisation:** Decision A says platform permissions are not organisational authority, and ODA-01 §3.1 says tenancy "is not part of the definition".
  - **Whether it is a bounded context or a capability is not yet justified either way.**
- **Authorization:** BDR-07 says it owns no truth. **[AC]** It is a **supporting capability** that evaluates permissions from Governance mandates and platform roles. **No evidence justifies a bounded context.**

## 8. Principal decisions

**[OQ] 1, ruled 2026-11-10: [AD] D-96, yes.** *(Kept below as the question that was put.)* Does D-94 also confirm the 2026-10-08 Membership ratification?
- **Situation:** Membership was ratified by "the Principal Reviewer" (a human act). D-94 confirms acts recorded as ARB acts.
- **Yes (recommended):** the Principal Reviewer acted with the Human ARB authority D-94 describes. **Membership's ratified boundary is a fixed point for Organisation discovery.**
- **No:** Membership becomes provisional, and Organisation discovery loses its firmest neighbour.

**Later, once evidence exists (not now):**
- ODA-M-2 and ODA-M-20 (meaning and scope);
- ODA-E-1 (sections);
- O-16 (legacy records);
- BR-9 and INV-AFF-1 (affiliation storage and discovery; INV-AFF-1 is referred);
- the owner of tenancy (HD-8);
- the FeePaid direction (X-1);
- address ownership.

## 9. Independent strategic recommendation

1. **Organisation is a bounded context** (core subdomain). It owns registry, structure, unit belonging, affiliation records, areas and coverage, and the **person profile (recommended)**. **It never owns membership, authority, entitlement, persons or official geography.**
2. **Governance stays separate** (D-5). **"Decides vs holds" is the boundary:** units and organs are distinct, and one unit has 0..n organs.
3. **Tenancy is a platform capability, not Organisation.** Whether it is a bounded context waits for evidence.
4. **Authorization is a supporting capability without its own truth** (BDR-07).
5. **Organisation-to-organisation relationships are out of scope** (BR-13a); recorded so they are not lost.
6. **Next evidence step:** obtain and register at least one statute (NRNA). **Most UNKNOWNs above depend on it, and none should be filled by defaults.**

## 10. Is discovery sufficient for the Organisation baseline?

**Not yet.**
- **Sufficient:** the **boundaries** (§3, §6) and the **concepts** (§4, §5) to draft a strategic baseline.
- **Not sufficient:**
  - **the definition (ODA-M-2, ODA-M-20) and the section question (ODA-E-1)**, which need your rulings, ideally informed by a statute;
  - **the legacy-record classification (O-16)**;
  - Gate F.
- **A baseline drafted now would have to carry these as open items. That is possible, but the definition at least should be ruled first.**

---

# Round 2 (2026-11-10): after D-96 to D-99

**Binding additions:** D-96 (Membership ratification confirmed) · D-97 (written rules of any form) · D-98 (membership-based bodies now) · D-99 (sections by source of authority).

## R2.1 Disagreements and precisions (first)

| # | Point | Consequence |
|---|---|---|
| **Y-1** | **"Federation of such bodies" has two meanings.** A federation whose members are **persons**, directly or as delegates, fits the ratified Membership model, which holds the membership of persons (`PersonRef`). A federation whose members are **organisations** needs organisation-to-organisation membership, which **BR-13a excludes now** and Membership does not model. D-98 named federations "as a kind of body only" | **The definition should say which one is in scope** (R2.8, question 1) |
| **Y-2** | **INV-AFF-1 was "referred to the ARB"; under D-94 that is now the Principal.** It decides where affiliation records live and how sign-in discovery works (BR-9). **The Organisation baseline cannot fix affiliation ownership without it** | A genuine Principal decision (R2.8, question 2) |
| **Y-3** | **D-21 ("only the Highest Committee makes formal structural changes") is reported practical knowledge**, not a platform rule. The record itself says "Describes the organisations under discovery, not a platform rule (D-1)" | Structural authority is **per organisation, from its written rules (D-97)**; UNKNOWN for NRNA until the constitution is on file |
| **Y-4** | **FeePaid is outside Organisation.** It concerns Finance and Membership only | **It does not block the Organisation baseline**; analysed in R2.6, deferred |

**No disagreement with the reviewer's refinement** that the recorded-adoption requirement (D-97) must be explicit and that "subject to applicable law" qualifies the organisation's conduct; **it is not a PublicDigit promise to interpret law.**

## R2.2 Ownership of each business responsibility

| Responsibility | Owner | Reason | Status |
|---|---|---|---|
| The organisation's registry and identity | Organisation | It is the body itself | WD D-22 |
| Units, wings, their tree over time | Organisation | Structure is what the body *is* organised as | WD D-13 … D-22 |
| **Deciding** structural changes | **Governance** (the competent organ or office holder under the written rules) | Authority, not structure (Decision A, D-97) | WD D-21 (as reported), D-29 |
| Applying decided changes; structure "as at" | Organisation | Holds the result | WD D-29 |
| Unit belonging, every capacity | Organisation | D-3; never authority or membership status | WD |
| Affiliation (person ↔ organisation, in a capacity) | Organisation (records) · Identity (sign-in discovery port) | XD-3 §2; BR-2 … BR-12 | **INV-AFF-1 / BR-9 open (Y-2)** |
| Organisation-local person profile (name, contact, address as supplied) | **Organisation (recommended)** | XD-3 §2 item 5: organisation-local; staff and guests too (V-54) | open (INV-AFF-1) |
| Areas and coverage | Organisation | D-82, D-80 | AD / WD |
| Membership truth | Membership | — | RAT (D-96) |
| Organs, positions, tenures, mandates | Governance | — | WD D-4, D-5; ARB D-72 |
| Electorate and entitlement | Election | — | RAT boundary |
| Persons | Identity | — | ARB D-75 |
| Working Organisation, data scoping, demo mode | **Platform capability (not Organisation)** | Not organisational authority (Decision A); excluded from the definition | owner open (HD-8) |
| Permission checks | **Authorization, supporting, owns no truth** | BDR-07 | XD-4 open |

## R2.3 Units versus organs

- **A unit** is a place in the structure where people **belong** (D-3). **An organ** is a body that **decides** under the written rules; its seats are **positions** (Governance).
- **One unit has 0..n organs; an organ acts for exactly one unit or for the organisation as a whole.**
- **The legacy `committees` records (O-16):** classify each by its function, not by its name. Where a record is both, as with a "District Committee", it splits into **one unit and one organ**, linked. **[AC] This is a classification rule, not a Principal decision.** Applying it to real records needs their data; no record is classified here.

## R2.4 Belonging, affiliation, areas and coverage

Unchanged from round 1 §5, with three precisions:
- **Affiliation is organisation-level; belonging is unit-level.** A person can be affiliated (e.g. a Guest) without belonging to any unit.
- **Belonging requires an individual act by default** (D-79); **a residence rule only where the written rules adopt it**, recorded as the basis.
- **Coverage consumes Geography at one shared τ** (D-73, D-93). A GROUPING change (D-92) or a reorganisation of official places **never changes belonging by itself** (D-80).

## R2.5 Authority and organisational lifecycle

- **Who may create, change, merge, divide, suspend, dissolve or restore units:** the competent organ or office holder **under the organisation's written rules** (Decision A, D-97). **Platform default: UNKNOWN.** D-21 is practical knowledge only (Y-3).
- **Already decided for *how* changes take effect** (WD): D-13 … D-19 (fallbacks, successors, divisions), D-30 and D-34 (change sets), D-40 … D-43 (effective dates and pending restructurings before elections), D-66 (abolished positions).
- **[AC] When the rules are silent,** D-65 applies (a declared gap is decided by the organisation's rules, with its recorded default). **No structural default is invented.**

## R2.6 FeePaid: Finance and Membership (deferred; does not block Organisation)

Three facts must be kept apart:
- **(a) the obligation:** what is due, for which membership period;
- **(b) the payment and its settlement;**
- **(c) the consequence:** Membership's `Standing`, which the ratified record says is "derived from fee facts".

| Option | (a) Obligation | (b) Payment | Flow |
|---|---|---|---|
| F-1 | Finance | Finance | Finance → Membership: "fee settled for period P" |
| F-2 | Membership, from the organisation's rules | Finance | Membership → Finance: obligation; Finance → Membership: settled |
| F-3 | Membership | Membership | **Excluded:** "settlement is Finance's" (RAT) |

- **Fixed by the ratified record:** **(b) is Finance's, (c) is Membership's.**
- **What remains open is only (a) and the direction of flow.**
- **Who sets the dues and grants waivers is a decision of the organisation's competent organ (Governance), whichever option is chosen.**
- **[AC]:**
  - F-2 fits "fees reference periods", and the periods are Membership's.
  - F-1 fits the recorder's reading N-5.
- **Decide with the Finance and Membership work, not here.**

## R2.7 Identity and organisational information

- **Identity:** the canonical person, accounts and references (D-75).
- **Organisation:** the person **as known to that organisation**: its `PersonRef`, the profile (name, contact, address as supplied), affiliations, belongings.
- **Never copied between organisations** (XD-3 §2 items 3 to 5). **No duplication of identity truth:** the profile does not establish who a person *is*.

## R2.8 Genuine Principal decisions (one at a time)

1. ~~The definition of "Organisation" (ODA-M-2)~~ **Ruled: [AD] D-100** (refined wording plus the federation note).
2. **Ruled: [AD] D-101** (ownership now: affiliation records and the person profile, including addresses, are the organisation's; the discovery mechanism is left to Identity, nothing foreclosed). *(Question as put:)* INV-AFF-1 / BR-9. Where affiliation records and the person profile live, and how sign-in discovery works, within XD-3's limits. The 2026-11-01 INV-AFF-1 record holds the analysis.
3. **Later, not blocking the baseline:** the owner of tenancy (HD-8); Authorization's scope (XD-4); FeePaid (R2.6); O-16 per-record classification once the data are reviewed.

## R2.9 Is discovery sufficient for the baseline?

**Nearly.**
- **After rulings 1 and 2,** the Organisation strategic baseline can be drafted.
- **What it would carry as UNKNOWN:** the statute-dependent items, namely structural authority per organisation, O-8 … O-10, and NRNA's sections.
- **Gate F** follows the draft.
