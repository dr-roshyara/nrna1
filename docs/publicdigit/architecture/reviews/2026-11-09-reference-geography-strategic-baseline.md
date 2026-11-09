# Reference Geography: strategic baseline and tactical model (proposed)

| | |
|---|---|
| **Status** | PROPOSED baseline for Principal/ARB review. Not ratified. No code, migrations, schemas or APIs |
| **Purpose of this document** | **One consolidated reference** that a domain expert can read and a solo developer can implement from, without the revision history. It **supersedes as working text** the proposals in `2026-11-09-reference-geography-strategic-discovery.md`, `…-level-framework-strategic-discovery.md` and `…-baseline-closure-and-tactical-design.md`, which stay as the record of how it was reached |
| **Commission** | "Reference Geography: Revision 3 Review, Strategic Baseline Completion and Tactical DDD Validation" (Principal, 2026-11-09); updated for "D-83 Integration, Strategic Baseline Completion and Tactical DDD Validation" (Principal, 2026-11-09) |
| **Accepted decisions integrated** | D-3, D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, **D-84, D-84a, D-85, D-86, D-87, D-88, D-89** (consolidated in §C below) |
| **Read first** | **§C, the consolidated strategic baseline (2026-11-09).** Where §C differs from a later section or dossier, **§C governs**; the sections after it are kept as the record of how it was reached |
| **Labels** | **[AD]** accepted decision · **[BF]** established fact (cited) · **[E]** implementation fact · **[SI]** strategic interpretation · **[R]** recommendation · **[TD]** proposed tactical design · **[OQ]** open Principal decision · **[TU]** unresolved technical uncertainty |

---

## C. Consolidated strategic baseline (2026-11-09) — read this first

*Business language. No knowledge of Laravel, PostgreSQL or aggregates needed. Tactical expression: `2026-11-09-reference-geography-tactical-design-baseline-v3.8.md`. Coherence check: `2026-11-09-reference-geography-baseline-coherence-assessment.md`.*

### C.1 Business purpose

Reference Geography is **PublicDigit's register of official geographical references**. For a political party this matters because committees, local branches, members' addresses and electoral rules all refer to places, and **those places change**: districts merge, municipalities are re-parented, names and codes change, sometimes with retroactive effect.

**Consumers may rely on:**
- **one permanent identity per entry**, never reused, even when names, codes or parents change;
- **officially asserted facts** (names, classification, containment, codes), each with its **evidence** and its **valid period**;
- the **full history**: nothing is overwritten;
- **historical answers** to two separate questions: *what was valid on a date* (valid time) and *what PublicDigit had recorded by a moment* (knowledge time); **once given, an answer never changes**;
- **complete answers or a refusal, never a partial answer.**

**Consumers must not assume:**
- that a published fact is **true**: it is **officially asserted by a competent source, with authenticity verified, and accepted by PublicDigit's governed process**. **Publication never creates official status** (D-84);
- **sovereignty or recognition** from a country-or-area entry (D-86);
- **anyone's belonging, membership, coverage or vote** from a place or an address;
- that a level number means the same thing in two countries (D-81).

### C.2 Bounded contexts and ownership

| Context | Decides and owns | Consumes | Never decides |
|---|---|---|---|
| **Reference Geography** | Official geographical references: identity, claims, evidence, assessment, publication, history, country profiles, reconciliation, objections and disputes about its facts; the steward roster | Nothing from other contexts (platform-level, D-31) | Sovereignty; organisation areas; coverage; belonging; membership; electorates |
| **Organisation** | **Belonging** to units and wing branches, in every capacity (D-3, D-22); organisation-defined areas (D-82); unit coverage (D-80); address-to-area suggestions | Published Geography | Official geography; anything for another organisation |
| **Membership** | Membership facts and their processes (ratified architecture) | Person references (D-75); Organisation's facts where its rules require | Belonging (Organisation's); official geography |
| **Election** | The **electorate** under its rules | Organisation's belonging and Membership's facts **at one shared τ** (D-73); Geography only through those | Belonging; geography |
| **Governance** | Organs, positions, tenures (D-4, D-5) | Organisation's units | Territory |
| **Address / person-profile** | Address as supplied, components (streets, D-83) and their resolution | Geography's resolution | Belonging; official identities. **Owner is a recommendation (Organisation's person-profile capability, §2a), not a ratified decision** |

**Context map (direction of flow):**

```
                 Reference Geography  (publishes; platform-level)
                   │ published facts, resolution, historical queries
     ┌─────────────┼──────────────────────┐
     ▼             ▼                      ▼
 Organisation   Address / person-profile  (other consumers)
 belonging,     (owner: recommendation)
 areas, coverage
     │ belonging at τ
     ▼
  Election  ◄── Membership facts at τ
```

- **Only Geography publishes geographical facts.** Every other context **consumes them read-only** and applies them under **its own** rules.
- **Collaboration across contexts uses one shared τ per operation** (D-73). **No cross-context cause-and-effect order is claimed.**

### C.3 Ubiquitous language (consolidated)

| Term | Meaning | Not to be confused with |
|---|---|---|
| **Official geographical reference** (entry) | Anything in the register: the World, an M49 region (D-85), a country or area (D-86), a territorial unit. Replaces "official place" where a region or country-or-area is meant | An organisation area; a street |
| **ReferenceUnitId** | PublicDigit's **permanent, never-reused** identity for one entry | A code; a name; the real-world entity |
| **Real-world geographical entity** | What the instrument establishes or describes | PublicDigit's identity for it |
| **Instrument** | The official act or publication that establishes or describes a fact | The evidence record about it |
| **External identifier** | A code in a namespace (ISO 3166-1, M49, national codes) | The identity |
| **Claim** | One asserted fact (name, parent, classification, code) with a **valid period** and evidence | A truth; an edit |
| **Evidence** | The recorded instrument, authenticity, covering mandate, applicability and any precedence rule (D-84) | A link to a website |
| **Assessment** | The steward's recorded judgement of that evidence | Publication |
| **Revision** | A coherent set of claims proposed and published together | A database update |
| **Publication** | The governed step that makes a revision part of PublicDigit's record | Official status |
| **Valid time** | When a fact holds in the world | Knowledge time |
| **Knowledge time** | When PublicDigit recorded it | Valid time |
| **Country profile** | A dated mapping of a country's classes to positions 3–10 (D-81) | Tree depth |
| **Territorial tier** | A position that holds official units | An address-component tier |
| **Address component** | Street, house number, postal code: describes address precision; **no identity** (D-83) | An official unit |
| **Organisation area** | A territory an organisation defines for its own purposes (D-82) | An official reference |
| **Reconciliation** | A published finding that two identities denote one entity; the old identity stays and resolves to the canonical one; reversible | A deletion |
| **Objection** | An organisation's challenge to a published fact, with counter-evidence (D-84) | A dispute |
| **Dispute** | A recorded unresolved conflict between instruments or claims | An objection pending assessment |
| **Correction** | A new revision replacing a wrong fact; the old one stays in history | An overwrite |
| **Geography steward** | A person appointed by the platform owner to **assess and publish** (D-84) | A source of official status |
| **Matter reviewer** | A person appointed for **one matter only** (D-84a); no standing publication authority | A steward |

### C.4 Lifecycle in business terms

1. **Prepare:** evidence is collected and the proposed facts are drafted. **Anyone preparing decides nothing.**
2. **Assess:** a non-conflicted steward judges the evidence (D-84a) and **submits** the revision.
3. **Check consequences:** the register refuses a revision that would create contradictions (two parents in one scheme, one code for two live entries, a parent ending under live children, a classification out of order).
4. **Second person, if required:** for a **structural** change, **once a second steward has ever been appointed** (D-89), a second, independent natural person approves: not the preparer and not the assessor (D-88). If none is available, **the change waits.**
5. **Publish:** the revision becomes part of PublicDigit's record, **all of it at once.**

**Afterwards:**
- **Correction:** a new revision; history is kept.
- **Objection:** an organisation challenges a fact with counter-evidence; the fact changes **only** by a correction or a recorded dispute (D-84).
- **Reconciliation:** two identities found to be one entity; both remain explainable; reversible by a later publication.

### C.5 Strategic invariants

1. **Permanent identity:** a ReferenceUnitId is never reused or deleted.
2. **No silent rewriting:** every change is a new publication; earlier answers stay reproducible.
3. **One publisher:** only Geography publishes geographical facts (D-31).
4. **Evidence and competence:** every published fact names its instrument and the competence behind it; publication never creates official status (D-84).
5. **Dated, country-specific classification** (D-81); streets are address components (D-83).
6. **Official and organisation geography stay separate** (D-82).
7. **No belonging, membership, coverage or entitlement is inferred from geography** (D-79, D-80).
8. **Governance:** D-84, D-84a, D-88, **D-89** (the second-person threshold, once active, stays active).
9. **Historical queries are complete or refused** (D-73 / CR-01).
10. **Neutrality:** no sovereignty asserted (D-86); CLDR display names, sources attributed (D-87); M49 regions only (D-85).

### C.6 Accepted decisions and deferred discovery

- **Accepted, integrated, not reopened:** D-3, D-31, D-73 / CR-01, D-75, D-79, D-80, D-81 to D-89, D-84a.
- **Architectural classifications by analogy (not Principal decisions):** ADMISSION of a country-or-area is **structural**; GROUPING into an M49 region is **non-structural**.
- **Deferred, not blocking the baseline:**

  | Item | Business question |
  |---|---|
  | Q-2 | Should other official schemes (e.g. electoral constituencies) be represented? |
  | L-4c | How to assign a region where M49 evidence does not cover an entry |
  | Japanese classification | The actual positions and classes |
  | Names below level 2 | Display-name policy for territorial units |
  | Level-1 region names | Naming policy for the five regions |
  | Address ownership | Whether Organisation's person-profile capability owns addresses (recommendation only) |

---

## 0. Challenges to Revision 3 (stated first)

1. **The German profile needs more positions than assumed.** [BF] Rheinland-Pfalz has **129 Verbandsgemeinden over 2,260 Ortsgemeinden** (Statistisches Landesamt, regional overview 2023), between Kreis and municipality.
   - Positions strictly increase (D-81), so Germany needs a position for municipal associations.
   - Germany then uses **eight positions between 3 and 10** (§6.2). **The 0–10 range is tight**; a country with more tiers could not fit (§12, risk K-1).
2. **Streets are not universal even in official address law.** [BF] Japan's Residential Address Indication Act (Act No. 119 of 1962) defines a **block method** (town name + block number + building number) and a **road method** (road name + building number). Each municipality chooses by council resolution, and **almost all use the block method**. **No global "Street" position can exist;** it is a profile entry where a country uses it.
3. **Revision 3's touched set was asserted, not proven.** In particular a concurrent revision **adding a child to a unit that another revision is ending** would not be caught by locking only the obvious units. Corrected in §11.2 with **child-set versions**.
4. **Revision 3 left the country list (level 2) unexamined.** Choosing a country list is politically sensitive (disputed territories). It is now **L-4**, a separate later decision (§7).

### 0a. Second challenge, after D-83

5. **The child-set version from §11.2 would create false conflicts.**
   - Two unrelated new municipalities in the same Kreis would both bump the Kreis's child-set version, so one would fail for no business reason.
   - **Refined (§11.2):** a revision validates a parent's **child-set version only when it ends or reclassifies that parent**, because only then must the child list be complete. **Adding or moving a child needs only the parent's existence-and-classification version.**
6. **A profile change could leave units with a class that no longer exists.** **Added rule (§11.2):** a class cannot end while live units carry it; those units must first be ended or reclassified by their own revision.
7. **"Street precision" is reported against an address source, never against Geography** (D-83: no street register). The resolution result therefore names **the source** against which a street was matched, and its **position reached** counts official places only (§3, §8).
8. **Address ownership no longer needs a Principal decision.** The evidence resolves it architecturally (§2a). L-5 is withdrawn from the decision list.

---

## 1. Business purpose and scope [SI]

Reference Geography is **PublicDigit's trusted atlas of official places**.

**Consumers may rely on:**
- one identity per official place;
- officially asserted names, classifications and containment, with their dates;
- the history of every change and the evidence behind it;
- answers "as at (date, knowledge boundary)" that never change once given.

**Consumers must not assume:**
- that a published fact is **true** (it is **officially asserted by a competent source, authenticity verified**);
- that an address, an area or a place determines **anyone's belonging or vote**;
- that a level number means the same in two countries;
- that a party's local area is a place in the atlas.

**Out of scope:** party areas (Organisation, [AD] D-82), addresses (Organisation's person-profile capability, §2a), committees, coverage, belonging (Organisation), electorates (Election), **streets and any street register ([AD] D-83)**.

## 2. Bounded-context responsibilities and ownership

| Concept | Owner | Responsible for | Forbidden |
|---|---|---|---|
| Official place | **Reference Geography** | Identity, claims, history, evidence, country profiles | Party areas; belonging; electorates [AD D-31, D-82] |
| Organisation-defined area | **Organisation** (the defining organisation) | Area identity, definitions, lifecycle, relations to official places | Official identity; other organisations' areas [AD D-82] |
| Organisational unit | **Organisation** | A place in the party structure where people belong | A territory [AD D-3, D-22] |
| Committee coverage | **Organisation** | 0..n references to official places and/or own areas | Belonging [AD D-80] |
| Committee as a body | **Governance** | Organ, positions, tenures | Territory [AD D-4, D-5] |
| Address and its resolution | **Organisation's person-profile capability** ([R], §2a) | Address as supplied, its components (streets included, D-83) and their resolution to official places | Official identities; areas; belonging [AD D-75 via XD-3b §2a: addresses are organisation-local] |
| Address-to-area suggestion | **Organisation** | A suggestion for its own purposes | Belonging [AD D-79, D-80] |
| Belonging | **Organisation** | By act or expressly adopted rule | Derived from address, area or coverage [AD D-79, D-80, D-82] |
| Electorate | **Election** | From Organisation's belonging at one `τ` | Derived from geography or areas [AD D-73, D-82] |

### 2a. Who owns addresses: architectural finding [R]

| Evidence | What it shows |
|---|---|
| [AD] D-75 via XD-3b §2a: "Name, contact details and other descriptive data live in each organisation's database. That organisation is the source of its members' data." | Addresses are **per organisation**, not Identity's |
| XD-3b, accepted trade-off: "a person in two organisations has two separately maintained profiles" | The address lives in an **organisation-local person profile** |
| Ratified Membership architecture (2026-10-08): no mention of address, contact or profile | **Membership does not own it.** It owns membership facts only |
| [AD] V-54: staff and guests can belong to units; [AD] D-3: Organisation owns belonging "in every capacity" | People with **no membership** (guests, staff, imported persons) also have profiles and addresses. A Membership-owned profile would make Membership hold non-members' data |
| Model document §31 (proposed): contact endpoints in the organisation-local profile, owned by the affiliation owner, proposed Organisation | Consistent direction |

**Finding:** the address belongs to **Organisation's person-profile capability**: the organisation-local profile of every person affiliated with the organisation in any capacity.
- **It is a capability within Organisation, not a new bounded context**: it has no decisions of its own beyond keeping what was supplied and its resolution.
- **It consumes** Geography's resolution service.
- **It never decides belonging (D-80).**
- **Communication reads endpoints from it** (§31).
- **Membership refers to the person** through `PersonRef` (D-75).

*Status:* architectural recommendation for ARB acceptance with this baseline; **not a Principal business decision**.

## 3. Ubiquitous language

| Term | Meaning | Not to be confused with |
|---|---|---|
| **Official place** | A territorial division established by a competent public authority | An organisation area; a street |
| **Reference identity** | PublicDigit's never-reused identity for one official place | A code; a database row id |
| **Claim** | One officially asserted statement about a place, with validity, evidence and record time | A truth |
| **Assessment** | An appended judgement on a claim or a revision: authentic, verified, disputed, superseded, published, withdrawn | An edit |
| **Revision** | One evidenced official change (rename, re-parent, divide, merge, absorb, end, correct) | A database update |
| **Publication** | The single step that makes a revision visible to consumers | Verification |
| **Country profile** | The dated assignment of a country's classes to positions 3–10 in its **designated reference scheme** [AD D-81] | Tree depth |
| **Position (level)** | The number a profile gives a class; strictly increasing along containment; may skip | A count of parents; a legal rank |
| **Class** | A country's own category (Land, district, chōme) | A position |
| **Address component** | A part of an address that is not an official place: street, house number, building number, postal code (D-83) | An official place; a street identity |
| **Address-component class** | A position in a profile that addresses use for a component, holding **no places in the atlas**. Only where the country uses it (Germany: street 9; Japan: none for streets) | An official class |
| **Resolution precision** | The **position of the deepest official place** an address was matched to, plus the status of each address component (given · matched against a named address source) | Official verification of a street |
| **Source** | A publication of an issuing body, with its recorded competence | A website |
| **Competence** | A source's legal power over a scheme, territory and period | Format of the evidence |
| **Precedence rule** | A recorded rule, with its own legal basis, deciding between conflicting claims | "The later wins" |
| **Candidate** | A submitted missing or changed place (D-31) awaiting evidence | A published place |
| **Organisation area** | A territory description an organisation defines for its own purposes [AD D-82] | An official place; a unit |
| **Coverage** | The official places and/or own areas a unit serves | Belonging |
| **Resolution result** | The outcome of matching an address: state, deepest official place, position reached, street component status | A placement |
| **Geography steward** | The platform role that verifies and publishes (S-1) | A source of official status |

## 4. Accepted decisions

| ID | Content | Provenance |
|---|---|---|
| D-31 | Reference Geography is a platform-level context: official units, revisions, approval of submitted missing units; the organisation's own units are Organisation's | direct |
| D-73 / CR-01 | `asOf`, `τ`, `recordedAt ≤ τ`, `closedThrough`, one `τ` per multi-context operation, refusal beyond the boundary | accepted |
| D-75 | Addresses and other descriptive data are organisation-local (XD-3b §2a) | accepted |
| D-79 | Placement by the organisation's rules; default individual acts; address only suggests | direct |
| D-80 | Address and committee structure are different things | direct |
| **D-81** | Levels 0–2 global; 3–10 positions in dated country profiles, shared by addresses and committees | **direct** |
| **D-82** | Official geography only; organisations may define their own areas, organisation-owned, never official, never determining belonging or entitlement | **direct** (provenance corrected 2026-11-09) |
| **D-83** | Streets are address components where relevant, not official units; no global street register; street precision establishes no official street identity; street-based areas under D-82 | **direct**, 9 November 2026 |
| V-22 | "Organisation's own territorial structure; keep C4 and C5 separate" | direct |

## 5. Organisation-defined areas (D-82): business rules

| Rule | Status |
|---|---|
| Owned by the defining organisation; identity scoped to it; never an official identity; never shared | **Follows from D-82** |
| May be used for structure and coverage; never determines belonging or entitlement | **Follows from D-82** (with D-79, D-80) |
| Distinct from a unit: a unit is where people belong, an area describes territory. An area may exist without a unit and serve several units (geographic branch and wing branch, V-24, V-53) | [SI] |
| Definition: name; description (text; **coordinates or polygons optional, never required**); optional relations to official places ("within ward 5", "overlaps wards 5 and 6"), which are **the organisation's assertions** | [SI] |
| Street-based areas: described as street names **anchored to an official place** ("Hauptstraße, Bahnhofstraße in Wiesbaden-Biebrich"), because a street name is unique only within a place and a street may cross boundaries (each anchored part is listed) | [SI] |
| Imprecise boundaries allowed ("the settlement around the temple"); they support suggestions only, never placement | [SI] |
| Nesting allowed (declared "part of"), acyclic; overlap allowed as territory | [SI] |
| Whether **overlapping areas may both be covered**, and **who may create or change areas** | **Organisation rules** (as D-21 describes the organisations studied); **not a platform decision** |
| Revisions dated; retirement with successor areas or "none"; dependent coverage flagged for review | [SI] |
| Historical interpretation: coverage refers to the area and the definition version in force at the coverage decision | [SI] |
| Address association: computed by Organisation as a suggestion from the address's resolution and the area's relations; recomputed when either changes; never stored as belonging | [SI] |

## 6. Country profiles (D-81) [SI] [R]

Each profile is dated and belongs to the country's **designated reference scheme**. Positions may be skipped; a level is never derived by counting parents.

### 6.1 Nepal

| Pos. | Before 20 Sep 2015 | From 20 Sep 2015 |
|---|---|---|
| 3 | Development region | **Province** |
| 4 | Zone | **District** |
| 5 | District | **Local level** (metropolitan city, sub-metropolitan city, municipality, rural municipality) |
| 6 | VDC / municipality | **Ward** |
| 7 | Ward | Street (address-only, where streets are named) [address component, D-83] |
| 8 | — | House number (address-only) [address component, D-83] |

- A district **keeps its identity across 2015 while its position changes** (5 → 4). D-81's dating allows this.
- **Toles are not in the profile:** they are organisation areas (D-82).
- The pre-2015 column is a structural illustration; its exact class list needs a primary source before import.

### 6.2 Germany

| Pos. | Class | Note |
|---|---|---|
| 3 | Land | City-states also have municipality status |
| 4 | Regierungsbezirk | Only in some Länder (Hessen: Darmstadt, Gießen, Kassel); absent in Rheinland-Pfalz from 1 Jan 2000 [BF] |
| 5 | Kreis / kreisfreie Stadt | Wiesbaden: kreisfreie Stadt in Regierungsbezirk Darmstadt [BF, Hessian law § 2(2)] |
| 6 | Gemeindeverband (Verbandsgemeinde, Amt, Samtgemeinde…) | Only where a Land has them (Rheinland-Pfalz: 129) [BF] |
| 7 | Gemeinde | A kreisfreie Stadt holds status equivalence at 7 |
| 8 | Ortsbezirk / Stadtbezirk | Wiesbaden: 26 Ortsbezirke [BF] |
| 9 | Street (address-only) [address component, D-83] | |
| 10 | House number (address-only) [address component, D-83] | **The range is now full** |

The Principal's earlier illustration "5 = Bezirk, 6 = Street" [PS] does not fit Germany; this profile replaces it as a proposal. **[R] Profile contents are approved through publication (S-1), not as a Principal ruling.**

### 6.3 Japan

| Pos. | Class | Note |
|---|---|---|
| 3 | Prefecture | |
| 4 | Gun (district) | **Non-administrative**: "merely a geographical name" [BF, Ministry of Internal Affairs and Communications]; used in addresses; contains towns and villages only |
| 5 | Municipality (city, town, village; Tokyo special ward) | Cities skip 4 |
| 6 | Ward of a designated city | Administrative ward, not a municipality |
| 7 | Town / aza (町・字) | Address-relevant area |
| 8 | Block (街区符号) | Official under the Residential Address Indication Act where the block method is used [BF] |
| 9 | Building number (住居番号) | Address-only |

**No street position**, except in the few municipalities using the road method [BF].

## 7. Remaining Principal decisions (one at a time)

| ID | Question | Blocks | Order |
|---|---|---|---|
| ~~L-2~~ | Streets | — | **Closed by D-83** |
| **S-1** | Who is accountable for verifying and publishing geography, and with what control? | Verification workflow | **Now** |
| L-3 | Which source defines level 1 (regions)? | Level-1 content | Next (a genuine but low-impact business choice: what "region" means to users) |
| L-4 | Which source defines the country list (level 2), and how are disputed territories shown? | Level-2 content | Then (political and reputational, so the Principal's) |
| ~~L-5~~ | Address ownership | — | **Resolved architecturally** (§2a) |
| Q-2 | Other official schemes (constituencies) | Nothing current | Later |

### 7.1 L-2: do streets belong in the official atlas? **Closed by D-83 (option ii).** Analysis kept for the record.

**Challenge of option (ii):**
- **Does (ii) preserve D-81?** Yes. A street class can have a position in a profile (Germany 9, Nepal 7) as an *address-only* class, read by addresses and committees alike.
- **Must every profile contain a street level?** **No.** Japan's block method has none; D-81 allows absent positions.
- **What does "street precision" mean?** Three things, never conflated:

  | Status | Meaning |
  |---|---|
  | (1) Given | The text contains a street |
  | (2) Matched | Found in an address source for that official place |
  | (3) Officially verified | Confirmed against a competent register |

  Under (ii) Geography holds no streets, so **the position reached counts a street only when it is matched (2), and states the source**; (1) is never reported as precision.
- **Street names for areas:** yes, anchored to an official place (§5).
- **Same name in several places, or a street crossing boundaries:** an anchor per place solves both.

| Option | What PublicDigit commits to | Business consequence | Becomes difficult |
|---|---|---|---|
| **(i) Streets are official atlas units** | Keeping streets for every supported country current, with evidence | Street search and reporting from the atlas; street-based coverage by official identity | A very large, municipally scattered register (Germany has no single national one [SI]); Japan has almost none; streets cross boundaries, so they are not containment units |
| **(ii) Streets are address-only classes** (position where a country uses them; no atlas records) | Nothing beyond the profile | Atlas stays a register of official territories; addresses keep streets as text, matched against address sources; **street-based committees use organisation areas (D-82)** | Street-level reports must come from address data |
| **(iii) Per country: street units only where a competent, available register exists** | Streets for some countries | Uneven: identity for streets in one country, text in another | Consumers must handle both |

**Recommendation: (ii).** It keeps D-81, D-82 already covers street-based committees, and it commits PublicDigit to nothing it cannot keep current. (iii) remains possible later without changing the model.

**The business choice for the Principal:** **should PublicDigit's official atlas promise streets?** Under (i) it must keep them official and current; under (ii) it never does.

### 7.2 S-1 (now): who is accountable for verifying and publishing geography?

| Action | Who | Limit |
|---|---|---|
| Submit | Organisations (`Candidate`); steward (imports) | A submission creates nothing |
| Authenticity | Steward | Records how it was checked |
| Competence | **Recorded with legal-basis evidence**; the steward records it, the law determines it | No competence without legal basis |
| Precedence | Recorded rules with legal basis only | **Nobody chooses by preference;** otherwise disputed |
| Verification and publication | Steward | **Never creates official status** |
| Conflict without a rule | Nobody | Stays disputed until a competent source resolves it |
| Appointment of stewards | Platform owner (the Principal) | — |

**The governance choice (to be asked):** one steward approves everything, or **a second person must approve structural changes** (divisions, mergers, profile changes, competence records).

**Realistic constraint:** the Principal is today the only person. A two-person rule cannot be staffed now.

**Settled without a ruling (architecture, from D-31 and G-2):**
- Competence comes from law, recorded with evidence.
- Precedence only by recorded rules with a legal basis; otherwise disputed.
- Every act is recorded with its actor, time and evidence.
- **An organisation that disagrees with a publication submits counter-evidence as a `Candidate`.** That uses the existing path; no separate challenge procedure is needed for geography.

**The genuine business choice: what control applies before a geographical change is published?**

| Option | Meaning | Business consequence | Becomes difficult |
|---|---|---|---|
| **(a) One steward publishes everything** | Evidence and audit trail are the only safeguards | Works today | A mistaken division reaches every organisation's coverage review with no second look |
| **(b) Two-person rule for structural changes now** | Divisions, mergers, profile changes, competence and precedence records need a second approver | Strongest control | **Cannot be staffed today:** structural changes would be blocked |
| **(c) One steward now; two-person rule for structural changes and competence and precedence records as soon as a second steward is appointed** | (a) now, (b) later, as a standing platform rule | Matches today's reality; tightens automatically | Until then trust rests on evidence and audit |
| **(d) One steward, with a notice period for structural changes** | Affected organisations see a structural publication some days before it becomes the platform's published state, and can submit counter-evidence | A substitute for a second person | Delays the platform's knowledge. **The official effective date is unaffected** |

**Recommendation: (c).** It is honest about a one-person platform, keeps evidence and audit mandatory, and turns on the stronger control without a new decision. **(d) is a reasonable addition** if organisations' trust in geography becomes a concern.

**Remains undecided until ruled:** whether the two-person rule becomes mandatory, and when.

### 7.3 L-3 and L-4 (prepared)

- **L-3:** [BF] UN M49, "Standard Country or Area Codes for Statistical Use" (UN Statistics Division; print last 1999, maintained online). The grouping is for statistical convenience and "does not imply any assumption regarding political or other affiliation". Its regions are not the conventional continents (one region for the Americas). **Recommendation: M49 regions at level 1.**
- **L-4:** the M49 page itself notes that ISO 3166 alpha codes represent a country's **identity** and the M49 code its **statistical area** [BF]. **Recommendation:**
  - ISO 3166-1 for the country list;
  - M49 for region membership;
  - an explicit platform policy for territories that the sources treat differently.

  **That policy is a Principal decision.**

## 8. Cross-context contracts [TD]

| Consumer | Published | May rely on | Corrections | Copy | Completeness | Source unavailable |
|---|---|---|---|---|---|---|
| Organisation | Revision published; profile changed; places as at (asOf, τ) | Officially asserted, published claims | New revisions; **its organ decides about coverage (D-21)**; areas flagged where their relations reference an ended place | Yes, keyed by reference identity | Complete through `τ` only if every published entry with `recordedAt ≤ τ` is applied **and** Geography's `closedThrough ≥ τ` | Acts on a complete copy; refuses beyond it |
| Organisation's person-profile capability (address owner, §2a) | Resolve place; places as at | Resolution result: state, **deepest official place and its position**, components given, components matched (with the address source named). **Never a street identity (D-83)** | Re-resolution is its own new claim | Optional | Same | Records "unresolved"; retries |
| Organisation, from the address owner | Resolution results | Suggestions only | — | — | — | — |
| Election (through Organisation) | — | Nothing from Geography directly | — | — | One `τ` across Organisation and its Geography copy | Refused, never partial |

**No consumer creates an alternative identity.** The only path into the atlas is a `Candidate`.

## 9. Temporal guarantees [AD D-73] [TD]

- **Dates kept apart:** legal effective date (`asOf`); source publication; receipt; verification; publication; correction. **Only `recordedAt` (database clock at commit) is compared with `τ`.**
- **Consumers see published state;** stewards and audit see the full trail of receipts and assessments.
- **Beyond `closedThrough`: refused**, never partial.

**Worked example (as at 20 January):**
- Notice N1 is received on 10 February and verified and published on 12 February.
- A conflicting notice N2 arrives on 14 February; the dispute is recorded on 15 February.
- On 20 February a recorded precedence rule covers it (N2 is a corrigendum by the same authority), and N2 is published.
- On 1 March a correction moves the effective date to 1 February.

| Known at | Answer |
|---|---|
| 11 Feb | old district |
| 13 Feb | B, C |
| 16 Feb | B, C, disputed |
| 21 Feb | B, C, D |
| 5 Mar | old district |
| 21 Feb, asked after 1 Mar | B, C, D (unchanged) |

## 10. Scenario validation

| # | Scenario | Rule | Owner | Invariant | Expected result | Failure behaviour | Time |
|---|---|---|---|---|---|---|---|
| 1 | Nepal 2015 | Dated profile; succession | Geography | G-6, G-5 | New profile from 20 Sep 2015; continuing districts keep identity | Revision rejected; previous state stays | Effective 20 Sep 2015, whenever recorded |
| 2 | Nawalparasi across provinces | Touched set, not subtree | Geography | G-4, G-5 | One revision across provinces 4 and 5 | Version conflict → re-prepare | As 1 |
| 3 | German structures | Profile with gaps and associations | Geography | G-6, G-4 | §6.2 | Profile change rejected if order breaks | Rheinland-Pfalz without position 4 from 1 Jan 2000 |
| 4 | Japanese addresses | Block method; gun non-administrative | Geography | G-6 | §6.3 | — | — |
| 5 | NRNA Hessen-Rheinland-Pfalz | Coverage references official places | Organisation | G-1, OA-5 | Two reference identities | Copy incomplete → coverage change waits | Coverage dated by Organisation |
| 6 | Two organisations, different areas, one municipality | D-82 | Each organisation | OA-1 | Two unrelated areas | — | Each area's own history |
| 7 | Settlement across two wards | D-82 | Organisation | OA-5 | "Overlaps wards 5 and 6" as its assertion | — | Re-flagged if a ward is divided |
| 8 | Street-based coverage | D-82, **D-83** | Organisation | OA-1 | Area of street names anchored to an official place; **no street identity** | Unanchored name → rejected as ambiguous | Area definition versions |
| 9 | Address inside an area | Suggestion only | Organisation | OA-4 | Suggestion | Ambiguous address → no suggestion | Recomputed on change |
| 10 | Member not in the committee covering their residence | D-79, D-80 | Organisation | OA-4 | Belonging unchanged | — | — |
| 11 | Late official change | Two times | Geography | G-3, G-8 | §9 | Refused beyond `closedThrough` | §9 |
| 12 | Correction moving the effective date | Append-only | Geography | G-3 | §9 | — | §9 |
| 13 | Conflicting official sources | Recorded precedence only | Geography | G-2 | Superseded or disputed | Disputed, never chosen | Marker visible from the recording |
| 14a | Concurrent unrelated changes | No false conflict | Geography | G-4 | Both publish | — | Separate publication entries |
| 14b | Concurrent overlapping changes | Atomicity | Geography | G-4, G-5 | One publishes; the other re-prepares | — | — |
| 15 | Election snapshot | One `τ`, complete copy | Election ← Organisation | G-8 | Answer at `τ` | Refused | Same `τ` everywhere |

**Invariants referenced:** G-1 identity never reused · G-2 only competent, verified assertions published · G-3 append-only · G-4 positions strictly increase along containment (hence no cycles) · G-5 ended places name successors · G-6 one meaning per position per country and date · G-7 Geography never changes others' facts · G-8 complete or refused · OA-1 to OA-6 (§11.3).

## 11. Tactical DDD model [TD]

### 11.1 Building blocks

| Element | Classification | Protects | Why |
|---|---|---|---|
| `ReferenceUnit` | **Aggregate** (per identity) | G-1, G-3; holds its claims (names, codes, identifiers, classification, containment, existence, succession links), assessments, and a **child-set version** | Small; changes touch few units |
| `CountryProfile` | **Aggregate** (per country × scheme) | G-6; designated reference scheme | Small; rare changes |
| `Revision` | **Aggregate** (per proposed change) | G-5; evidence completeness; its lifecycle (received → verified → disputed → published / rejected) | Independent lifecycle |
| Publication | **Coordinated atomic step** (§11.2) | G-4; no partial visibility | Deliberate, bounded exception; pattern of [AD] D-22 option C |
| Country publication sequence | Log | `recordedAt`, `closedThrough` | — |
| `Candidate` | **Aggregate** (small, process-like) | Nothing published without a verified revision | Lifecycle and submitter communication |
| `Source` | **Aggregate** (reference data) | Competence statements need legal-basis evidence | Lifecycle |
| `PrecedenceRule` | Governed reference data | No precedence without legal basis | Data, not code |
| `PrecedencePolicy` | Domain service | Unresolvable → disputed | Stateless |
| Claim, Assessment | Entities (insert-only) | G-3 | — |
| `EvidenceRef`, `ValidityPeriod`, `ClassPosition`, `SuccessorSet`, `ResolutionResult` | Value objects | — | — |
| Hierarchy, paths, places at a level, search, resolution index | Read models | — | Rebuildable |
| ~~`CountryScheme` as country-wide aggregate~~ | **Withdrawn** (Revision 3) | — | False conflicts; size |
| Organisation area | **Organisation's**; not designed here (§11.3) | — | — |

### 11.2 Touched-set publication (proof)

1. **The touched set is derived from the revision's kind, not chosen by hand:**

   | Kind | Write set | Read set |
   |---|---|---|
   | Rename, code change | The unit | — |
   | Re-parent | The unit, old parent, new parent | New parent's classification |
   | Divide A → {B, C} | A; new B, C; **every current child of A** (each must be re-parented to B or C, or ended, in the same revision) | Parents of B and C |
   | Merge {A, B} → C | A, B, C; all children of A and B | Parent of C |
   | Absorb A into B | A, B; children of A | — |
   | Profile change | The profile | **Only if the change reorders classes:** all units of the reordered classes. Order-preserving changes cannot break G-4 |

2. **Validity rule:** after the revision, **no live place has an ended parent**, and every ended place has its successor set (G-5).
3. **Detecting concurrent changes:**
   - every `ReferenceUnit` has two versions:
     - an **existence-and-classification version**, incremented by its claims;
     - a **child-set version**, incremented when a child is attached or detached.
   - At publication, every write-set version is re-validated, together with **the read-set versions of the kind each entry needs**:
     - **ending or reclassifying a parent** (divide, merge, absorb, end) validates that parent's **child-set version**, because its child list must be complete;
     - **adding or moving a child** validates only the new parent's **existence-and-classification version**.
   - **The missed case is caught:** a concurrent revision that added a child to A while A's division was being prepared changes A's child-set version, so **the division fails and is re-prepared** with the new child.
   - **No false conflict:** two new municipalities in the same Kreis touch only the Kreis's existence-and-classification version (read, unchanged), so both publish.
   - **Ending a class:** a class cannot end in a profile while live units carry it.
4. **G-4 at publication:** for every new containment edge, parent position < child position at the effective date (from `CountryProfile`). Since positions strictly increase, **no cycle can form**.
5. **Changes spanning provinces:** nothing assumes a subtree; the sets are whatever the kind derives (Nawalparasi).
6. **Unrelated changes:** disjoint touched sets never conflict.
7. **Atomic publication:**
   - claims are written **beforehand, invisible** (tagged with the revision, referenced by no publication entry);
   - publication is **one short transaction**: re-validate the versions, append one entry to the country's publication sequence;
   - consumers see only claims referenced by published entries, so **all or nothing**.
8. **Large national changes** (Nepal 2015: every district re-parented, 753 local levels):
   - preparation writes thousands of invisible claims at leisure;
   - publication stays a version check plus one entry.
   - **[TU]** Re-validating thousands of versions in one transaction must be measured.

### 11.3 Organisation areas: consistency (for Organisation's design)

| # | Invariant | Required boundary |
|---|---|---|
| OA-1 | Identity organisation-scoped; never official | Contract: Geography accepts only `Candidate`s with competent evidence |
| OA-2 | Every definition change has a basis | The area's own boundary |
| OA-3 | Definitions append-only; coverage references a dated version | Same |
| OA-4 | No belonging from areas | No such command |
| OA-5 | Relations to official places use reference identities from a complete copy | Organisation's Geography copy |
| OA-6 | Declared nesting acyclic | **Recommended:** a per-organisation **area catalogue version** for nesting changes. Areas per organisation are few, so serialising them is cheap. Attribute changes stay on the area |

**Not designed here:** it is Organisation's tactical design, and it does not block freezing Geography. **Geography's contract needs only OA-1 and OA-5.**

### 11.4 Implementation consequences

- **Two stores, two owners:** the atlas (Geography) and organisation areas (Organisation). **Never one table with an "is_official" flag.**
- Today's tenant party units ("levels 6–8", `is_official = false`) **move to Organisation areas**. The tenant geography table becomes a pure, rebuildable copy keyed by reference identity.
- **Insert-only** claims and assessments (no update or delete rights); invisible-until-published claims; one publication entry per revision.
- `Source` competences and `PrecedenceRule`s are **data with legal basis**.
- **Fitness tests:**
  - no claim is updated;
  - no identity is minted outside publication;
  - no organisation area is written into Geography;
  - no consumer reads Geography tables;
  - positions strictly increase along every published containment edge.

## 12. Remaining architectural uncertainties [TU]

| # | Uncertainty |
|---|---|
| K-1 | The 0–10 range: Germany uses all positions 3–10. A country with more tiers would not fit. **D-81 fixes 0–10;** if a real country needs more, that is a Principal matter, raised only with evidence |
| K-2 | Performance of publication re-validation for very large revisions |
| K-3 | OA-6 mechanism (Organisation's design) |
| ~~K-4~~ | Address owner: **resolved** architecturally (§2a), pending ARB acceptance of this baseline |
| K-6 | The external address source the Principal described (§2.2 of the level-framework commission) is still not available. Its identifiers and update cycle determine how "matched" street status is obtained |
| K-5 | Primary sources for Nepal's pre-2015 class list and for every profile before import |

## Sources

- Constitution of Nepal 2015 (unofficial English translation, Nepal Law Society, IDEA, UNDP): constituteproject.org
- Hessian law on the Regierungspräsidien § 2(2) (via a statute collection); RP Darmstadt brochure
- Rheinland-Pfalz VwORG 1999 § 5; Statistisches Landesamt Rheinland-Pfalz, Kreisübersichten 2023
- wiesbaden.de (Ortsbezirke; Ortsverwaltung Biebrich)
- Ministry of Internal Affairs and Communications, Japan (gun)
- Residential Address Indication Act (住居表示に関する法律, Act No. 119 of 1962): hourei.net; Suginami City
- UN Statistics Division, M49

---

# Challenge revision (2026-11-09): strategic closure and adversarial tactical validation

**Status:** PROPOSED. Commission: "Strategic Discovery Closure and Adversarial Tactical DDD Validation" (Principal DDD Architect; accepted by the Principal). **This section supersedes §6, §9, §11 and the S-1 / L-3 / L-4 texts of §7 where it differs.** No accepted decision is reopened. S-1 is presented for an **explicit** ruling; general acceptance of the review is not taken as that ruling.

## X.0 Disagreements with the review (first)

1. **"L-5 is still open":** §2a already resolved it architecturally, with the same recommendation the review now makes (Organisation owns organisation-local addresses). Re-challenged in X.4 as asked; **it holds, strengthened** by one new requirement (address history must be bitemporal).
2. **The review fears the touched-set needs fine-grained locking to be correct.** The stronger and simpler argument is different: **serialise each country's publication step** (short) and **validate the touched set inside it**. Correctness then does not depend on row-locking subtleties. As a consequence **the aggregate model gets smaller**: publication changes one aggregate only (X.6). This resolves the earlier "one transaction, several aggregates" objection properly, instead of excusing it.
3. **L-4 is not only about recognition. It is about real diaspora structures.** NRNA has national coordination committees in **non-sovereign territories such as Hong Kong**, home to a large Nepali community [I, general knowledge]. A list of sovereign states only would make that structure unrepresentable.

## X.1 D-81 and D-83 made coherent: what a position means

**Smallest clarification (not a reopening):** a position's single meaning (D-81) is a **tier**, a granularity rank within the country's profile. Classes are the legal categories placed in a tier. A profile contains two kinds of tiers on one scale:

| Kind | Holds places in the atlas? | Containment? | Used by |
|---|---|---|---|
| **Territorial tier** (official places: Land, district, municipality, ward) | Yes, each with a reference identity | **Yes:** positions strictly increase along containment (D-81) | Addresses (deepest place matched), committees (coverage), everyone |
| **Address-component tier** (street where used, house number) | **No** (D-83) | **No:** it ranks precision only; a street need not lie within one unit of the tier above | Addresses only |

- **Unranked address components** (postal code, flat number) have **no position**: they are neither territorial nor a precision rank (a postal code crosses municipalities, Addendum B.3). **This relieves the 0–10 range.**
- An address-component tier is always positioned **after every territorial tier it may attach to**.
- **What each consumer reads:**

  | Consumer | Reads |
  |---|---|
  | (1) Official class | Its territorial tier |
  | (2) Address-only class | Its address-component tier |
  | (3) Resolution result | **Position of the deepest official place matched**, plus each ranked component's status and tier ("street: matched against source S, tier 9") |
  | (4) Coverage reference | **Territorial tiers only.** An address-component tier has no identities, so **coverage cannot reference it**; street-based coverage is an organisation area (D-82, D-83) |

- **Consequence:** a street or house number can never become an official identity, a containment level or a coverage level, by construction.

## X.2 Authority, mandate, instrument (S-1 groundwork)

The former single `Source` mixed six things. Separated:

| Concept | Meaning | Example |
|---|---|---|
| **Authority** | The issuing public body | Constituent Assembly of Nepal; Landtag Rheinland-Pfalz; a Japanese municipal council |
| **Mandate** | The legal power of that authority over a scheme, territory, subject and period, **with its own legal basis** | Constitution of Nepal Art. 56(3) and Schedule 4; Hessian law on the Regierungspräsidien; Residential Address Indication Act Art. 3 (council resolution) |
| **Instrument** | The document issued: constitution text, statute, gazette notice, council resolution, official dataset | Schedule 4 text |
| **Authenticity** | Whether the instrument is genuine: official channel or certified copy | Recorded check |
| **Applicability assessment** | PublicDigit's judgement: does *this* instrument, by *this* authority, under *this* mandate, apply to *this* place, subject and date? | "Schedule 4 applies to Nawalparasi, effective 20 Sep 2015" |
| **Publication decision** | The steward's act making the assessed claim visible | Logged act |

- **An authentic instrument without a covering mandate is not authoritative** for that subject (the review's example).
- **Mandates chain:** a mandate's legal basis is itself an instrument of a higher authority, ending at a constitution (recorded as the root).

## X.3 Identity lifecycle rules [R]

**Principle:** identity follows **the legal entity as the instrument characterises it**, never the name or the code.

| Event | Identity | Successor relation |
|---|---|---|
| Rename | Continues | — |
| Reclassification (a municipality raised to sub-metropolitan city) | Continues | — |
| Re-parenting | Continues | — |
| Boundary adjustment (territory transfer) | Both continue | — |
| Division where the instrument **dissolves** the old unit | Old ends; all parts new | old → {new…} |
| Division where the instrument **keeps** the old unit (a part is split off) | Old continues; split part new | old → new (partial, old continuing) |
| Merger into a new unit | All old end; one new | {old…} → new |
| Absorption | Absorbed ends; absorber continues | absorbed → absorber (continuing) |
| Abolition with territory distributed | Ends | old → {receivers} (receivers continue) |
| Abolition without successor | Ends | none (explicit) |
| **Re-establishment** later under the same name | **New identity** | optional "historically related" link, **not continuity** |
| **Annulment of an abolition** (for example a court declares it void) | **Original identity continues**; the end claim is **corrected** | — |
| Name or code reused for a different unit | New identity | none |

**Business consequence:** history is never merged by accident. "Old Rukum" and "Rukum (Western part)" are different places linked by succession.

## X.4 Address ownership, re-challenged [R]

| Option | Verdict | Reason |
|---|---|---|
| Identity | Rejected | [AD] D-75 / XD-3b §2a: descriptive data is organisation-local |
| Membership | Rejected | Guests and staff have profiles without membership (V-54, D-3); ratified Membership owns no profile |
| Communication | Rejected | A consumer of endpoints, not their owner (§31) |
| **Separate Address context** | **Not justified now** | Needs invariants of its own, independent of the organisation's people. None found; postal validation, if ever needed, is an external service behind an anti-corruption layer |
| **Organisation's person-profile capability** | **Recommended** | Organisation owns affiliation in every capacity (D-3); the profile is per organisation (XD-3b) |

**What it owns:**
- address records and their history;
- parsing;
- **resolution results** (state, deepest place, ranked components, the Geography `τ` used);
- uncertainty.

Suggestions to areas are computed by Organisation's placement read model.

**New requirement:** address history must be **bitemporal** (moved-in date as `asOf`, record time). If an organisation adopts a residence rule (D-79), placement as at a date depends on the address **as known at `τ`**.

## X.5 Country profiles: status and sources

**All three profiles (§6) are illustrative proposals, not reference data.** None may be published before its sources are recorded (S-1).

| Entry | Classification | Source needed before publication |
|---|---|---|
| Nepal 2015–: Province, District | Territorial | **Have:** Constitution Art. 56(3), Schedule 4 |
| Nepal: local levels (753), wards | Territorial | **Missing:** the government's 2017 local-level instrument (Nepal Gazette); title-level evidence only (revision 2 R2-14) |
| Nepal pre-2015: development region, zone, district, VDC | Territorial (historical) | **Missing:** primary instruments (weakly sourced) |
| Germany: Land, Regierungsbezirk, Kreis / kreisfreie Stadt, municipality | Territorial | Land laws (Hessen: law on the Regierungspräsidien; Rheinland-Pfalz: VwORG 1999). Official municipality directory of the federal statistical office as the dataset; its competence is statistical, so the **Land laws are the mandate** |
| Germany tier 6: Gemeindeverband | Territorial tier | **One tier, several legal classes** (Verbandsgemeinde, Amt, Samtgemeinde, Verwaltungsgemeinschaft). They differ in legal nature but share the tier role "municipal association", so **one meaning per position holds**. Per-Land laws needed |
| Germany: Bavaria's Regierungsbezirk and Bezirk on one territory | **One place, two classes in tier 4** (state administration and regional self-government) | Bavarian law [I, general knowledge, unverified] |
| Germany: Berlin, Hamburg | One place, Land with municipality status equivalence | Land constitutions |
| Germany: street 9, house number 10 | **Address-component tiers** (X.1) | None (not atlas data) |
| Japan: prefecture, municipality, ward of a designated city | Territorial | Local Autonomy Act; the ministry's municipality codes |
| Japan: gun (tier 4) | **Territorial, non-administrative** ("merely a geographical name") | The Ministry of Internal Affairs and Communications [BF] |
| Japan: town / aza (tier 7) | Territorial (municipality-defined areas) | Municipal instruments |
| Japan: block (街区) and building number | **Open:** official under the Residential Address Indication Act, but address-system artefacts | **[TU]** Classifying them as address components extends D-83's reasoning beyond streets. **Flagged for later confirmation, not decided here** |

**0–10 range:** no country found that cannot be represented once unranked components take no position. France, checked as a stress case [I], fits within 3–10. **No case for revisiting D-81.**

## X.6 Tactical model, corrected [TD]

**The key change: each country's publication is a serialised step that validates the touched set.** Publication modifies one aggregate.

| Element | Classification | Invariant | Why this boundary |
|---|---|---|---|
| **`Revision`** | **Aggregate** | Its claims are internally complete: evidence, successor sets, every child of an ended unit reassigned. Append-only claims and assessments, **including after publication** (a later dispute marker) | Its own lifecycle; prepared at leisure, invisible |
| **`CountryPublicationLog`** | **Aggregate** (per country) | G-4, G-5, G-6 hold for the **published state**; sequence monotonic; `closedThrough` | **The only writer of visibility.** `Publish(revision)`: within one **serialised, short** step, validate the revision's touched set against the latest published state, then append **one entry**. **One aggregate per transaction** |
| `Candidate` | Aggregate | Nothing becomes a revision without evidence | Lifecycle |
| `Authority` (with `Mandate`s) | Aggregate (reference data) | A mandate needs its legal-basis instrument; the chain ends at a constitution | Lifecycle |
| `Instrument` | Immutable record (evidence) | Never changed; referenced by claims and mandates | Shared by many claims; later fixed through Record-Fixing |
| `PrecedenceRule` | Governed reference data with a legal basis | — | — |
| `PrecedencePolicy` | Domain service | No covering rule → disputed | — |
| **`ReferenceUnit`** | **Identity plus read model** (no longer an aggregate) | Identity minted once, by a revision; never reused | Every change goes through a revision, so nothing writes to a unit independently |
| **`CountryProfile`** | **Read model of published profile claims** (no longer an aggregate) | G-6 validated at publication | Profile changes are revisions |
| Units, hierarchy, paths, places at a tier, resolution index | Read models | — | Rebuilt from the log and its revisions |

**Proof of the publication step:**

1. **Serialisation:** at most one publication per country at a time. The step is short (validation plus one entry). A rare cross-border revision locks both countries in canonical order.
2. **The touched set is derived from the revision's kind** (§11.2 table), with version kinds: **child-set** for parents being ended or reclassified, **existence-and-classification** for parents receiving children.
3. **Validation against the latest published state, inside the serialised step:** every recorded version must equal the current published version, or the revision is rejected for re-preparation.
4. **No phantom child:** containment can only be created by a published revision, and publishing an attachment changes the parent's child-set version.
   - A division prepared before a concurrent attachment therefore fails validation.
   - An attachment prepared before a division fails because the parent no longer exists.
   - **Unpublished claims are not facts, so they cannot be missed.**
5. **No write skew:** both conflicting publications are validated in sequence against committed state, never against each other's snapshots.
6. **Unrelated revisions:** both succeed; one waits milliseconds for the other.
7. **All or nothing:** visibility is defined as "claims of revisions with a log entry at or before `τ`". **One entry is one atomic insert.**
8. **Large national revisions** (Nepal 2015): thousands of claims are written invisibly beforehand; the serialised step validates thousands of versions and appends one entry. **[TU]** Duration must be measured; worst case it delays other publications in that country, never correctness.

## X.7 Temporal worked example (explicit)

Place A is divided into B and C.

| Date | Event | `recordedAt` |
|---|---|---|
| 1 Jan | **Legal effective date** of the division (per instrument N1) | — |
| 8 Feb | N1 published by the competent authority (source publication date) | — |
| 10 Feb | N1 received → revision R1 *received* | 10 Feb |
| 12 Feb, 09:00 | R1 verified | 12 Feb 09:00 |
| 12 Feb, 10:00 | R1 **published** (log entry 1) | 12 Feb 10:00 |
| 14 Feb | Statistics dataset S2 still listing A received; competing claim | 14 Feb |
| 15 Feb | Conflict recorded: R1 *disputed with* S2 | 15 Feb |
| 20 Feb | Recorded precedence rule: the authority's instrument prevails over a statistical restatement → S2 superseded; dispute closed (log entry 2) | 20 Feb |
| 27 Feb | Authority's corrigendum N3: effective date is **1 Feb**, not 1 Jan | — |
| 1 Mar | N3 received, verified, published (log entry 3) | 1 Mar |

**(1) Fixed `asOf` = 20 January, varying `τ`:**

| `τ` | Answer | Why |
|---|---|---|
| 11 Feb | A | R1 received, not published |
| 12 Feb 09:30 | A | Verified, not yet published |
| 13 Feb | B, C | Entry 1 |
| 16 Feb | B, C, **disputed** | Assessment of 15 Feb |
| 21 Feb | B, C | Entry 2 |
| 5 Mar | **A** | Entry 3: division effective only from 1 Feb |

**(2) The correction affects only some effective dates:**

| `asOf` | `τ` 21 Feb | `τ` 5 Mar |
|---|---|---|
| 20 Jan | B, C | A |
| 5 Feb | B, C | B, C |

**(3) Reproducibility:** the question (asOf 20 Jan, τ 13 Feb), asked on 13 February or on 5 March, answers **B, C** both times. Only facts with `recordedAt ≤ τ` count, and none is ever rewritten.

**(4) `closedThrough`:** on 12 Feb at 10:00:01, before entry 1 commits, a consumer asks with τ = 12 Feb 10:00. `closedThrough` is still 12 Feb 09:59 → **refused** (CR-01 §3); retried, it answers B, C. **An election snapshot chooses one `τ` ≤ `closedThrough` for Organisation and its Geography copy, or waits.**

## X.8 Additional scenarios

| Scenario | Rule | Owner | Invariant | Expected | Failure |
|---|---|---|---|---|---|
| Street crossing two municipalities | D-83; X.1 | Organisation's person profile (address) | No containment for address tiers | Each address resolves to its own municipality; the street stays text with its tier | Ambiguous → *ambiguous* |
| Japan, block method versus road method in one prefecture | Profile per country; address tiers by municipality's method | Geography (profile); address owner | X.1 | Block-method address: block and building number; road-method address: street and number | Unknown method → resolution to municipality only |
| Concurrent child addition during a division | X.6 step 4 | Geography | G-4, G-5 | Division rejected and re-prepared with the new child | — |
| Abolition later annulled by a court | X.3 | Geography | G-1, G-3 | Correction revision; original identity continues | — |

## X.9 L-3 and L-4 (prepared; to be asked after S-1)

**L-3, UN M49.**
- **What it is:** the UN Statistics Division's codes and groupings "for statistical use", grouped "for convenience", not implying political affiliation [BF].
- **What it is not:** a definition of continents. For example, it groups the Americas as one region.
- **Fit:** level 1 is for grouping and reporting. Diaspora "continental" structures such as NRNA's are **Organisation units covering countries** (D-82), so they need no Geography level-1 match.
- **Limitations:** regroupings by the UN are rare but possible (handled as revisions); sub-regions exist but are not level 1.
- **Recommendation:** M49 regions at level 1, labelled as statistical regions.
- **Principal's choice:** whether users may see "Americas" where they might expect North and South America.

**L-4, countries and territories.**

| Concept | Meaning |
|---|---|
| Identifier | ISO 3166-1 code |
| Territory | Has its own ISO entry (Hong Kong, Greenland, Puerto Rico) |
| Statistical area | M49 |
| Recognition | Not PublicDigit's role |
| Display | Name shown |

| Option | Consequence |
|---|---|
| **(a)** Level 2 = "country or territory" from ISO 3166-1, with a stated rule that **inclusion implies no recognition** | Hong Kong representable |
| (b) Sovereign states only | **NRNA's Hong Kong structure unrepresentable** |
| (c) (a) plus recorded additions for entries outside ISO (for example user-assigned codes), only by explicit platform policy | Covers cases ISO omits |

**Recommendation: (a) with (c).** Display uses neutral official short names; organisations name their own units freely.

## X.10 Status after this revision

| Item | Status |
|---|---|
| D-81 / D-83 coherence (tiers) | Clarification, PROPOSED |
| Authority / mandate / instrument | PROPOSED |
| Identity lifecycle rules | PROPOSED, for ARB acceptance (architecture; changes no business meaning) |
| Address ownership | Recommendation, strengthened |
| Profiles | **Illustrative only;** sources listed |
| Tactical model (two core aggregates; serialised publication) | PROPOSED; X.6 proof; **[TU]** large-revision duration |
| Japanese blocks | Open classification (X.5) |
| **Principal decisions** | **S-1 now**; then L-3, L-4; Q-2 later |

---

# S-1 dossier (2026-11-09): publication governance

**Status:** PROPOSED; awaiting the Principal's explicit ruling. Commission: "Strategic Discovery Completion and Adversarial Tactical DDD Validation", §3 ("Begin with S-1 … wait for the ruling").

## S1.0 Notes on the review

- **Issue 1 (streets in the profile)** is answered in X.1: territorial tiers versus address tiers. **Coverage can reference territorial tiers only**, because address tiers hold no identities. The consumer rules will be demonstrated at the context-validation step.
- **Issue 2 (concurrent child additions)** is answered in X.6: **publication is serialised per country**, and every version is validated against the latest published state inside that step. Two additions under one parent are published one after the other, so neither is lost and neither conflicts with the other. The five worked cases follow at the tactical step, as the commission orders.

## S1.1 Finding: an objection to a published fact needs its own path

[AD] D-31 gives Geography "approval of **submitted missing units**". A `Candidate` therefore covers **missing** places only. **Counter-evidence against an already published fact is a different act**, so the earlier statement "organisations object through the existing `Candidate` path" (§7.2, X.4) is **withdrawn**.

**Proposed lifecycle: Objection** [R]
1. **Submitted:** by an organisation, citing an instrument.
2. **Assessed:** authenticity, mandate, applicability.
3. **Outcome**, one of:
   - **(a) Correction:** the objection rests on a competent instrument the platform lacked → a new `Revision`.
   - **(b) Disputed:** it rests on a competent instrument that conflicts, with no recorded precedence rule → a dispute marker on the published claim, from its `recordedAt`.
   - **(c) Rejected:** not authentic, no covering mandate, or not applicable, with the reason recorded.
4. **The published claim is unchanged until an outcome is recorded.** Lodging an objection changes nothing for consumers, so a submission cannot be used to unsettle official geography.

**Why Geography and not Contestation or Adjudication:** those contexts handle challenges to an **organisation's** decisions and results, before its own competent bodies. **No organisation's tribunal is competent over official geography**, so objections stay within Geography.

## S1.2 Answers to the commission's eight questions

| # | Question | Proposed answer |
|---|---|---|
| 1 | What qualifies as a steward? | A **named natural person appointed by the platform owner** to verify and publish geography, recorded with the appointment date. The appointment authorises publication; **it never confers power to create official status** |
| 2 | What is an independent second approver? | **A different natural person**, checked against Identity's canonical person (D-75), so that two accounts of one human never count as two approvers. Also: not the preparer of the revision; no declared conflict of interest in the matter; separately authenticated |
| 3 | Which changes are structural? | Changes to **existence** (establish, end, re-establish), **containment** (re-parent), **succession** (divide, merge, absorb), **classification tier**, **boundary** (territory transfer), **country profiles**, **authority and mandate records**, **precedence rules**, **same-as retirements**, and **corrections that alter any of these or their effective dates** |
| 4 | Which need two-person approval? | **All structural changes**, once a second steward exists. **Non-structural** changes (names, codes, external identifiers, aliases, descriptive text) need one steward |
| 5 | Mandatory checks, always | Instrument stored with its locator; authenticity method; covering mandate (scheme, territory, subject, period); applicability assessment; precedence rule if a conflict exists; the steward's **conflict-of-interest declaration**; all recorded with actor and database time |
| 6 | Second steward unavailable? | **Structural publication waits.** No bypass. **Waiting is safe:** legal effective dates are kept regardless (`asOf`), and only PublicDigit's knowledge is later. An election or decision fixed at an earlier `τ` is unaffected by design. The platform owner may appoint further stewards |
| 7 | Organisations' counter-evidence? | Yes, through an **Objection** (S1.1). A published claim changes only through outcome (a) or (b) |
| 8 | Does `Candidate` cover it? | **No:** D-31 covers missing units only. Objection is a separate lifecycle within Geography |

**Conflict of interest while there is one steward:** recusal is impossible. The declaration is therefore **mandatory and visible in the audit trail**. Once a second steward exists, a declared conflict makes the declarer **ineligible** to decide that matter.

## S1.3 The rule proposed for ratification (option C with conditions)

> **S-1.** The platform owner appoints Geography stewards. A steward assesses and publishes official geographical references on recorded evidence: instrument, authenticity, covering mandate, applicability, and any precedence rule applied. **Publication never creates official status.** While only one steward exists, that steward may publish all changes, with a recorded conflict-of-interest declaration. **From the appointment of a second steward, every structural change requires approval by a second, independent natural person**; if none is available, the change waits. Organisations may object to a published fact with counter-evidence; the fact changes only by a correction or a recorded dispute.

| Option | Consequence |
|---|---|
| (a) One steward for everything, forever | No stronger control as the platform grows |
| (b) Two-person rule for structural changes now | **Structural changes impossible today** |
| **(c) The rule above** | Works today; tightens automatically; objections possible |
| (d) One steward plus a notice period | Delays every structural publication; a second policy to run |

---

**Ruling recorded (2026-11-09): S-1 accepted as D-84** (direct: "I accept S-1 option C with the rule as worded"; discovery record §11.18), with the S1.3 wording unchanged. Open: a proposed conflict-of-interest amendment (the reviewer's correction), asked separately.

## S1.4 Proposed amendment to D-84: conflicts of interest (awaiting an explicit ruling)

**Why:** the reviewer is right, and the weakness is the architect's own. **A declaration makes a conflict visible; it does not make the declarer impartial.** D-84 as worded lets a conflicted sole steward decide alone.

**Refinement of the reviewer's correction (stated first):** a rule that blocks every matter touching an organisation the steward is involved in would **paralyse a one-person platform**. If the steward is active in NRNA, a change to any German Land might count. **Conflict matters where the steward exercises judgment between competing interests, not where the steward applies an uncontested, competent instrument that affects every organisation equally.**

**Proposed amendment text:**
> A steward has a **material conflict** in a matter if the steward holds office in, acts for, or submitted the matter on behalf of an organisation that brought the matter (a missing-unit submission or an objection), or whose position the matter's judgment favours (a dispute, a precedence question, or an assessment between competing instruments). **A materially conflicted steward must not decide that matter**, whether or not it is structural. It waits for a non-conflicted steward, or for an **independent reviewer appointed by the platform owner for that matter alone**. That reviewer must be a different natural person, with no tie to the organisations concerned, and must declare this; the appointment and the declaration are recorded. **Publishing an uncontested instrument whose mandate, authenticity and applicability are not in question is not a judgment between interests**: a recorded declaration suffices.

**Consequences:**
- Routine official changes keep flowing.
- **Objections and candidates from the steward's own organisation, and disputes, cannot be decided by that steward.**
- A reviewer appointed for one matter **approves only that matter**: no standing publication authority, and no power to create official status.
- **Remaining weakness:** the platform owner appoints the reviewer; mitigated by the recorded independence declaration.

---

**Amendment recorded (2026-11-09): D-84a** (conflict of interest; discovery record §11.19). D-84 is unchanged as the original; D-84a governs from its date. The test is **organisational interest combined with a judgment between competing positions**. Mere association does not block routine publication.

## L-3 dossier: global regions (awaiting the Principal's ruling)

**What level 1 is for in PublicDigit [SI]:** grouping countries and territories for display, reporting and search. **No belonging, coverage or entitlement depends on it** (D-79, D-80, D-82). International party structures are **Organisation units covering countries**, so an organisation's "Middle East" or "Asia Pacific" committee is its own unit, whatever the level-1 grouping says.

**What M49 is [BF]:** the UN Statistics Division's "Standard Country or Area Codes for Statistical Use". Countries and areas are grouped "for statistical convenience", which "does not imply any assumption regarding political or other affiliation". The online version is maintained after the last print edition (1999).

**What it is not:** a definition of continents, borders or political blocs. Its regions are Africa, Americas, Asia, Europe and Oceania. **The Americas form one region**, and some placements differ from common usage (for example Cyprus and Türkiye in Western Asia [I, general knowledge, to verify against the current M49 table before import]).

**How it fits the model:** M49 regions become level-1 places whose **authority is statistical** (UNSD's competence covers statistical grouping, not territory). Their class is "statistical region (UN M49)"; membership of a country in a region is a claim with the M49 table as its instrument; regroupings by the UN are revisions. The **"canonical versus reference" distinction is unnecessary**: every place's standing is defined by its source's competence (X.2), so labelling makes the statistical nature explicit.

| Option | Meaning | Consequence |
|---|---|---|
| **(A) M49 regions at level 1, labelled statistical** | Five regions; sub-regions not used at level 1 | Neutral, maintained, sourced; "Americas" is one region; organisations' own regions stay theirs |
| (B) As A, plus M49 sub-regions as a second, separately named grouping | Western Europe, Southern Asia… available for reporting | Finer reports; two groupings to explain |
| (C) Conventional continents defined by PublicDigit | North and South America separate | **No competent source:** PublicDigit would author geography, contrary to "publication never creates official status" (D-84) and D-82's logic |
| (D) No level-1 places | Countries directly under World | Contradicts the Principal's level 1 [PS] and D-81's global levels |

**Recommendation: (A).** (B) can be added later without changing anything else.

---

**Ruling recorded (2026-11-09): L-3 option A as D-85** (discovery record §11.20). The L-3 dossier above stays as the record of the recommendation.

## L-4 dossier: countries and territories (awaiting the Principal's ruling)

**The business problem:** international organisations (NRNA, for example) have structures in **non-sovereign territories**. The model must represent them distinctly, **without PublicDigit declaring anything about sovereignty or recognition.**

**Already established:**

| Basis | What it establishes |
|---|---|
| [AD] D-81 | Level 2 is global; each level-2 entry has its own dated profile for 3–10 |
| [AD] D-85 | Region membership comes from M49, statistical only |
| [AD] D-84 / D-84a | Publication never creates official status |
| Identity rules (X.3) | Identity follows the entity as the instrument characterises it |
| G-1 | One identity per place |

**Sources and their competence [BF]:**
- **M49** (UN Statistics Division): statistical areas. It lists **Hong Kong (344) and Macao (446)** separately, both in Eastern Asia. **Taiwan is treated as part of China (156)**, with code 158 usable for statistical purposes only (per secondary sources quoting UNSD; to be confirmed against the UNSD page).
- **ISO 3166-1** (ISO 3166 Maintenance Agency): codes for "countries and their subdivisions", with names taken from the UN Terminology Bulletin and the UNSD list. It has **HK, MO and TW** ("Taiwan, Province of China"). Its competence is **code assignment, not status**.
- The M49 page itself states that ISO alpha codes represent a country's or area's **identity** and the M49 code its **statistical area** (UNSD; cited in revision B.9 / §7.3).

**Distinctions, and the business need behind each:**

| Concept | Needed? | Why |
|---|---|---|
| Reference entry identity | Yes | Committees and addresses need one stable place |
| Class "country or area" (neutral) | Yes | Level 2 must not say "sovereign state" |
| Names from each source (ISO short name; M49 name, for example "China, Hong Kong Special Administrative Region") | Yes | Display and search; each is a claim with its source |
| Identifiers (ISO alpha-2/alpha-3, M49 numeric) | Yes | Crosswalk claims; never identity |
| Region membership | Yes | D-85 |
| Containment between level-2 entries | **No** | Two level-2 entries never contain each other; that would give one place two levels and break D-81 |
| Sovereignty or legal-status claims | **Not now** | No business capability needs them. Recording them would make PublicDigit adjudicate contested cases. They can be added later as sourced claims, never as containment, if a real need appears |

**Test cases (under option A):**

| Case | Result |
|---|---|
| **Hong Kong** | Level-2 entry (ISO HK; M49 344), region Asia; **its own profile** (its districts at 3+); NRNA's Hong Kong committee covers it; no relation to China recorded |
| Macao | Same (MO; 446) |
| Germany | Level-2 entry (DE; 276), region Europe |
| Greenland (non-sovereign) | Its own level-2 entry (GL); Denmark separate; no containment between them |
| Hong Kong on 1 July 1997 | **Entry continues** (same territory, same entity as an area); its status changed, which PublicDigit does not record. No identity event |
| South Sudan, 9 July 2011 | New entry; Sudan **continues** (a part split off, X.3) |
| Czechoslovakia, 1 January 1993 | Entry ends; successors Czechia and Slovakia (X.3) |
| Taiwan | ISO has TW, M49 folds it into China. **Under A, the ISO list governs identity**, so TW is an entry. Its region comes from M49 (Asia, via the statistical code); its displayed name is a later question |

**Options:**

| Option | Meaning | Consequence |
|---|---|---|
| **(A) Level 2 = "country or area" from ISO 3166-1; neutral class; no sovereignty status; no containment between level-2 entries** | Every ISO entry is a place in its own right | Hong Kong, Macao and Greenland representable; **PublicDigit takes no position** |
| (B) Sovereign states only at level 2; territories at level 3 under their sovereign | Hong Kong under China, Greenland under Denmark | **PublicDigit must decide contested cases** (Taiwan, Kosovo, Western Sahara); Hong Kong's own districts must fit China's profile |
| (C) The M49 list as the identity list | Follows the UN's statistical list | **Taiwan cannot be represented**, although organisations may have structures there |
| (D) Both: a level-2 entry and a level-3 place under the sovereign | — | **Two identities for one place:** violates G-1 |

**Recommendation: (A).** **Later, separate questions:** (1) entries outside ISO (for example Kosovo's user-assigned code), only by an explicit platform policy; (2) the display-name policy where source names are contested.

## L-4 reassessment (2026-11-09): supersedes the L-4 dossier above where it differs

### L4.0 Corrections of my own claims (from the UNSD M49 page, fetched 2026-11-09)

| Earlier claim | Status now |
|---|---|
| "The M49 page states ISO alpha codes represent identity and M49 the statistical area" (B.9, §7.3, L-4 dossier) | **Withdrawn as [BF].** The UNSD page says only that ISO codes are "assigned by the International Organization for Standardization" and that M49 codes are "used for statistical processing purposes by the Statistics Division". The identity wording came from secondary sources |
| Taiwan's region "Asia, via the statistical code" | **Withdrawn.** Taiwan does not appear in the UNSD page's country or region lists. Code 158 "for statistical purposes" is supported only by secondary sources: **unverified** |
| Hong Kong and Macao "separate entries in Eastern Asia" | **Confirmed and refined:** "China, Hong Kong Special Administrative Region" (344) and "China, Macao Special Administrative Region" (446) appear **listed under China (156)** in Eastern Asia (030), Asia (142) |
| Disclaimer | **Confirmed:** grouping "does not imply any assumption regarding political or other affiliation of countries or territories by the United Nations" |

### L4.1 Agreements and disagreements with the review

- **Agree (Issue 1):** in the dossier, ISO decided **admission** ("the ISO list governs identity"). Identity is PublicDigit's own (`ReferenceUnitId`, G-1); ISO and M49 codes are identifier claims. **Admission must not be ISO-exclusive.**
- **Refinement:** an admission policy that lets "business need" add places would make PublicDigit author geography, contrary to D-84 ("publication never creates official status"). **Default admission follows recognised listing sources; any addition is an explicit, recorded platform-policy decision**, never a steward's discretion.
- **Agree (Issue 2), with a finding:** relationships between level-2 entries cannot be made politics-free. A level-2 entry is a **politically defined territory**, so "Hong Kong lies within China" is not a neutral geographic fact the way "Biebrich lies within Wiesbaden" is. **M49's listing of Hong Kong under China is a statistical presentation by UNSD**, with its own disclaimer. **No relationship between level-2 entries is needed for the baseline** (no capability uses one). A future extension must be a **typed, sourced relation with declared permitted inferences**, never classification containment.
- **Agree (Issue 3):** evidence for region membership must be typed (L4.3).

### L4.2 Refined policy (proposed)

1. Level 2 holds neutral **"country or area"** entries. **An entry asserts no sovereignty, recognition or political status.**
2. **Identity is PublicDigit's own.** ISO 3166-1 alpha-2, alpha-3 and numeric codes, and M49 codes, are identifier claims with their sources.
3. **Default admission: entries of ISO 3166-1** (competent for code assignment; its names follow UN sources).
4. **Any other entry** (an M49-only area, a place in neither list) is admitted **only by an explicit platform-policy decision of the Principal**, recorded with evidence and reason, **and with the same non-recognition statement**.
5. **Region membership (D-85)** only from evidence of a stated type (L4.3).
6. **Names:** source-attributed names are kept separately from the preferred display name. **The display-name policy is a separate, later decision (L-4b).**
7. **No status and no relationship between level-2 entries** in the baseline; future relations typed and sourced, never containment.

### L4.3 Evidence types for level-1 membership

| Type | Allowed? | Example |
|---|---|---|
| Published M49 row placing the area in a region | **Yes** | Hong Kong 344 → Asia |
| Special-purpose statistical code | Only if verified in the UNSD source, and recorded as that type | Taiwan 158: **unverified** |
| Inference ("part of X, so in X's region") | **No** | — |
| Assignment by explicit platform policy | Only by a Principal decision, recorded as such | Taiwan, if needed (**L-4c, later**) |

An entry without allowed evidence has **no region**. It is still fully usable by addresses and committees; it is only absent from region navigation and reports.

### L4.4 Test cases

| Case | Entry? | Identity and names supported by | Region | Relation to another level-2 entry | History | May infer / may not infer |
|---|---|---|---|---|---|---|
| Hong Kong | Yes (ISO HK) | ISO; M49 name "China, Hong Kong SAR" kept as source-attributed | Asia (published M49 row) | None | Continues through 1 July 1997 (status change only) | May: it is a listed area. May not: sovereignty, or that M49's "under China" is a PublicDigit statement |
| Macao | Yes (MO) | Same | Asia | None | Continues through 20 Dec 1999 | Same |
| Taiwan | Yes (ISO TW) | ISO; ISO name "Taiwan, Province of China" kept as source-attributed only | **None** until L-4c, or until UNSD evidence is verified | None | — | May not: any status; any region by inference |
| Germany | Yes (DE) | ISO; M49 276 | Europe | None | Continues through 3 Oct 1990 (the identity question is for X.3 with the instrument) | — |
| Greenland | Yes (GL) | ISO; M49 | Americas (M49 row; to verify before import) | None (Denmark separate) | — | May not: independence |
| Non-sovereign territory (for example Puerto Rico) | Yes (ISO PR) | ISO | M49 row | None | — | — |
| South Sudan and Sudan | Both (SS, SD) | ISO | Africa | None | SS new, 9 July 2011; SD continues (X.3) | — |
| Czechoslovakia, Czechia, Slovakia | CS ended; CZ, SK new | ISO (with its historical code record) | Europe | None | Ends 1 January 1993; successors (X.3) | — |

### L4.5 Options

| Option | Consequence |
|---|---|
| **(A′) The refined policy (L4.2)** | Neutral; Hong Kong, Macao, Greenland and Taiwan representable; additions only by an explicit decision |
| (A) The original dossier (ISO-exclusive admission) | No path for a needed place outside ISO |
| (B) Sovereign states only | PublicDigit must decide contested cases |
| (C) M49 list for admission | Taiwan absent |

**Recommendation: (A′).** **Later, separate questions:** L-4b display-name policy; L-4c region assignment for entries without a published M49 row.

## L-4b dossier: display names of countries and areas (awaiting the Principal's ruling)

**The business problem:** every screen, list and report shows a name for a country or area. Sources name some places differently, sometimes in politically loaded ways, and PublicDigit is multilingual (English, German, Nepali at least). **Someone's naming is shown either way; the question is whose, by a rule that is neutral and predictable.**

**Already fixed:**
- [AD] D-86: source names are kept as attributed name claims, apart from the display name; entries assert no status.
- [AD] D-84: publication creates no official status; names are non-structural changes (one steward).
- Organisations name **their own units** freely (for example "NCC Hong Kong"); this question concerns only the atlas entry.

**Sources [BF]:**

| Source | Languages | Examples |
|---|---|---|
| ISO 3166-1 English short names | English, French | "Taiwan, Province of China" (per secondary sources, D-86 dossier); "Hong Kong" |
| UN (M49) | The six UN languages | "China, Hong Kong Special Administrative Region"; **no Taiwan entry** |
| **Unicode CLDR** territory names (cldr.unicode.org, translation guide) | Very many locales, maintained by the Unicode Consortium for software interfaces | **"Hong Kong SAR China"**, with a short variant **"Hong Kong"**; "Macao SAR China" likewise |

A CLDR disclaimer on political status is **not verified**; the claim is not relied on.

**Options:**

| Option | Consequence |
|---|---|
| (A) ISO English short names | English and French only: **no Nepali or German**. Shows formal forms ("Korea, Republic of") |
| (B) UN names | No Nepali or German; **Taiwan has no name at all** |
| **(C) CLDR display names in the user's language (standard form; CLDR's short form only where space is tight); official source names kept as attributed alternatives in the place's details** | Localised everywhere; maintained externally for exactly this purpose; **PublicDigit authors no names**. A new CLDR release is evidence for a name revision (one steward, D-84) |
| (D) Each organisation chooses its own display names for atlas entries | The same place appears differently across the platform and in shared reports; **naming disputes move into every organisation** |
| (E) A PublicDigit-authored neutral name list | **PublicDigit becomes the authority on names**, contrary to D-84's spirit |

**Recommendation: (C).** Users see names in their own language from a source maintained for interfaces. Official names stay visible as attributed alternatives. PublicDigit decides no naming dispute itself.

**Becomes difficult under (C):** if users object to a CLDR form, PublicDigit can only point to its rule. Any exception would need a further explicit Principal decision.

---

**Ruling recorded (2026-11-09): L-4b option C as D-87** (direct; discovery record §11.22). Country and area display names come from Unicode CLDR in the user's language (standard form; short form only where space is tight). Source names stay as attributed alternatives. **L-4b is no longer a blocker for importing real data.**
