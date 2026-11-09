# Reference Geography: strategic discovery of the global 0–10 level framework

| | |
|---|---|
| **Status** | Strategic discovery, PROPOSED. Decides nothing. No aggregates, schemas, events, APIs or code. **Not committed** (commission §16) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); file name as the commission specifies |
| **Commission** | "Reference Geography: Strategic DDD Discovery of the Global 0–10 Geographical Level Framework", Principal DDD Architect / Human ARB Chief, 2026-11-09 |
| **Builds on** | `2026-11-09-reference-geography-strategic-discovery.md` (revision 1 and revision 2, PROPOSED); D-31, D-79, D-80 |
| **Labels** | [AD] accepted decision · [PS] Principal-supplied business statement in this commission (§2), not yet recorded as a decision · [E] implementation fact · [PD] project-document evidence · [X] external source (cited) · [I] business interpretation · [R] architectural recommendation · [OQ] open Principal question |

## 1. Executive summary

- **The governing principle largely holds.** One geographical reference framework can serve both addresses and committees: both refer to the same canonical units, and Hessen can be referenced by an address and by a committee alike. [I]
- **It has three limits, and each needs a ruling:**
  1. **A level number cannot carry one global meaning beyond 0–2.** Germany, Nepal and Japan place comparable things at different depths, and some levels are missing in parts of a country. The workable reading is: **0 World, 1 continent or region, 2 country are global; 3–10 are an ordered scale whose meaning is set per country, and can change over time** (Question L-1). [I]
  2. **"Level 6 = Street" mixes two hierarchies.** Streets are not contained in one administrative unit, Japan's addresses mostly use no street names, and postal codes are delivery areas. Streets are best treated as **address components with a resolution depth**, not as geographical units (Question L-2). [I]
  3. **Levels 0 and 1 have no administrative authority.** World and continents need a **classification source** (for example the UN's regional classification), or they become PublicDigit's own invention (Question L-3). [I]
- **The repo contains six different numbering conventions** (§3.2), so no existing code can serve as the definition. [E]
- **NRNA Hessen-Rheinland-Pfalz works in the model without any new geographical unit:** one committee, coverage of two Länder, members placed by acts, addresses only suggest (D-79, D-80). [I]
- **Not ready for tactical design** until L-1 to L-3 are ruled. Question 1 (provisional references) is retested here; it can be ruled with or after them.

## 2. Accepted decisions and constraints

[AD] D-31 (Reference Geography is a platform-level context; party units are Organisation's); D-79 (placement basis); **D-80** ("address of a person and committee structure is two different things"); V-16, V-17 (organisational levels defined by the Highest Committee); V-23, V-24; D-21; ratified Membership architecture, CR-01, D-73, D-77; D-75. The V-23 admission–belonging contract stays open (model document §47). Geography is not a shared kernel (revision 1 PD-9).

**Previously rejected or withdrawn, and not revived:** Decision 1 (a/b/c) of the foundation reassessment; "Organisation records the belonging eventually"; "one dated tree per country"; a single status list (revision 2 K-3 to K-5).

## 3. The 0–10 framework: what a level means

### 3.1 Principal-supplied statements [PS]

| Level | Meaning supplied |
|---|---|
| 0 | World |
| 1 | Region / continent |
| 2 | Country |
| 3+ | Further subdivisions |
| 5 | **May** represent Bezirk (illustrative) |
| 6 | **May** represent Street (illustrative) |
| 7–10 | Further subdivisions or address-related levels; to be discovered |

### 3.2 What the repo says today [E]

| Source in code | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 |
|---|---|---|---|---|---|---|---|---|---|---|---|
| Landlord table comment (`Landlord/2025_01_01_000002_…:28`) | — | province/state | district | local level | ward | | | | | | |
| Continent migration comment (`Landlord/2026_01_14_000003_…`) | continent | country | province/state | district/county | local level/city | ward/zip code | neighbourhood/tole | street/block | house number | block/unit | reserved |
| `Domain/Services/GeographyLevelClassifier.php:17-29` | international | continent | country | province | district | local level | ward | neighbourhood | street | house number | block |
| `Application/Services/GeographyAntiCorruptionLayer.php` (Nepal) | | province | district | local level | ward | neighbourhood | street | house number | household | | |
| same, United States | | state | county | city | zip code | | | | | | |
| Tenant table (`Tenant/2025_01_01_000001_…:16`) | | | | | | | "levels 6–8: custom party units" | | | | |

**Six conventions.** Only `GeographyLevelClassifier` matches the Principal's 0–2. The others put continents at 0, countries at 1, postal codes at a level, and party units inside geography (D-31 assigns those to Organisation). **The implementation is no evidence of business meaning.**

### 3.3 Answers to the twelve questions [I]

| # | Question | Answer |
|---|---|---|
| 1 | Universal category, ordinal position, or convention? | **0–2: universal categories. 3–10: an ordinal position whose meaning a country profile defines** |
| 2 | Does level 3 mean the same in Germany, Nepal, Japan? | Same **position** (first subdivision below country), different **class**: Land, Province, Prefecture |
| 3 | Bundesland and Province both level 3 without being legally equal? | **Yes.** Same position means comparable depth, not equal legal status |
| 4 | Level 5 = Bezirk in one country, something else elsewhere? | Yes. **And "Bezirk" is itself ambiguous** (§4.1) |
| 5 | Level 6 = Street in one scheme? | Only as an **address resolution depth**, not as a containment level (§5) |
| 6 | Can a level be absent? | **Yes**, even within one country (Regierungsbezirke exist in Hessen, not in Rheinland-Pfalz) |
| 7 | Additional levels? | Yes, up to 10; beyond that the scale is exhausted ([OQ] none needed so far) |
| 8 | Concurrent schemes? | **Yes** (administrative, electoral, statistical; revision 2 R2-8) |
| 9 | One unit at more than one level? | **One position, plus status equivalence** (Wiesbaden: a district-free city at Kreis level, also a municipality) |
| 10 | Different types at the same level? | **Yes** (Kreis and kreisfreie Stadt both at the Kreis position; Japanese city, town and village) |
| 11 | Continuous hierarchy? | **No:** gaps occur (a Kreis directly under a Land where no Regierungsbezirk exists) |
| 12 | Administrative relationship, scale, address step, or a mix? | **Today it is a mix, which is the source of the confusion.** Recommended: levels describe **position in an administrative containment scheme**; address precision and organisational coverage are separate concepts that *refer* to positions (§5, §7) |

**Six concepts with separate definitions:** level number (position) · division class (Land, district, ward) · administrative authority (who established it) · containment (lies within) · address granularity (how precisely an address is resolved) · organisational coverage (Organisation's decision). [R]

### 3.4 Proposed level framework (unratified) [R]

| Level | Proposed meaning | Global or per country | Examples | Kind | Can be absent? | Several classes? | Unresolved |
|---|---|---|---|---|---|---|---|
| 0 | World | Global | World | Reference | No | No | — |
| 1 | Continent / world region | Global | Europe, Asia | **Classification**, not administrative | No | No | **Which classification (L-3)** |
| 2 | Country | Global | Germany, Nepal, Japan | Sovereign state or territory | No | Territories with special status | Disputed territories (later) |
| 3 | First subdivision | Per country | Land; Province; Prefecture | Administrative | Rare (small states) | Yes (Land; city-state) | — |
| 4 | Second position | Per country | DE: Regierungsbezirk; NP: District | Administrative | **Yes** (DE outside 4 Länder) | Yes | — |
| 5 | Third position | Per country | DE: Kreis / kreisfreie Stadt; NP: municipality / rural municipality | Administrative | Yes | Yes | **Principal's "Bezirk" (§4.1)** |
| 6 | Fourth position | Per country | DE: municipality; NP: ward | Administrative or sub-municipal | Yes | Yes | **Principal's "Street" (L-2)** |
| 7 | Fifth position | Per country | DE: Ortsbezirk / Stadtbezirk | Sub-municipal official divisions | Yes | Yes | — |
| 8–10 | Further official divisions **or** address precision | **Depends on L-2** | — | — | Yes | — | **L-2** |

*Per country* means a **dated country profile** that assigns each class of that country's scheme to a position. A profile can change (Nepal 2015: revision 2 R2-14). [R]

## 4. Global framework versus country schemes

### 4.1 Germany [X][I]

`World(0) → Europe(1) → Germany(2) → Land(3) → [Regierungsbezirk(4)] → Kreis or kreisfreie Stadt(5) → municipality(6) → Ortsbezirk / Stadtbezirk(7)`

- **Regierungsbezirke exist in only some Länder.** Hessen has three: Darmstadt, Gießen, Kassel [X OSM]. Rheinland-Pfalz dissolved its three on 1 January 2000 [X]. So position 4 is **absent** for every unit in Rheinland-Pfalz: a gap, not a different depth.
- **Wiesbaden is a kreisfreie Stadt:** one unit, position 5, with status equivalence as a municipality. It is not two nodes. [I; Regierungsbezirk Darmstadt from general knowledge]
- **Wiesbaden has 26 Ortsbezirke** under its main statute (Hauptsatzung, 24 March 1969, § 3); Biebrich is the largest [X wiesbaden.de].
- **"Bezirk" is ambiguous in German.** It may be a Regierungsbezirk (4), a Stadtbezirk or Ortsbezirk (7), Berlin's Bezirke, or Austria's politischer Bezirk. **The Principal's "5 = Bezirk" needs its intended meaning** before any profile is written. [OQ, folded into L-1]

### 4.2 Nepal [X]

`World(0) → Asia(1) → Nepal(2) → Province(3) → District(4) → municipality / rural municipality(5) → Ward(6)`. Toles and settlements are **not official divisions**; if a party uses them, they are Organisation's own areas (revision 2 R2-12). The 2015 restructuring and Schedule 4's division of Nawalparasi and Rukum are verified in revision 2 R2-14: **a profile change on 20 September 2015**.

### 4.3 Japan: a materially different structure [X][I]

`World → Asia → Japan → Prefecture(3) → municipality: city, town, village(4) → ward of a designated city (5)`.

- **Districts (gun) have no administrative function.** They appear in postal addresses of towns and villages and are used for statistics. Cities are not part of districts.
- **So Japan has an address unit that is not an administrative unit,** and cities and towns sit at the same position though only towns and villages have a gun.
- Most Japanese addresses use **block numbering, not street names** [I, general knowledge]: a "Street" level is meaningless there.

**Conclusion:** one ordinal framework with country profiles generalises; a fixed global meaning for 3–10 does not. [I]

## 5. Administrative versus address geography [I]

| Question | Finding |
|---|---|
| Does every street belong to one municipality? | **No.** Streets can cross municipal or Ortsbezirk boundaries; one name can exist in several municipalities |
| Can an address be identified without all intermediate levels? | Yes. Addresses name a municipality, not a Regierungsbezirk |
| Postal code: administrative area or delivery area? | **Delivery area,** set by the postal operator; it can cross municipal boundaries. It is not an administrative unit (the code's US "zip code" level, §3.2, is wrong) |
| Do address sources carry official identities? | [E] This repo's address data is **text only**: `users.street`, `housenumber`, `postalcode`, `city`, `state` (`database/migrations/2026_04_06_200001_…`), plus `members.residence_geo_unit_id` |
| Street, building, address: units or components? | **Address components.** They locate a point; they are not divisions of territory |
| Is "6 = Street" consistent with the framework? | Only as **address resolution depth**: "this address is resolved to street precision". It cannot be a containment position, because a street has no single parent |
| One unit for both uses? | **Yes:** Wiesbaden is the same unit whether an address resolves to it or a committee covers it |

**Geography owns** canonical units, schemes, profiles, containment, history and evidence. **The address owner owns** the address text, its components, and the **resolution result**: the deepest unit reached, the depth, the source and `τ`. [R]

## 6. The two source datasets

### 6.1 What exists [E]

- **Address source:** **no separate address database exists in this repo.** There are text fields on `users` and one reference `members.residence_geo_unit_id` (foreign key to Geography). **Limitation:** the address source described in the commission (§2.2) is not available to inspect; its identifiers, levels, corrections and update cycle are **unknown**.
- **Committee source:** `committees` with one `operational_geo_reference` path and `parent_committee_id` (Membership context, investigation 2026-11-09), plus committee structures with named levels. **One reference per committee:** Hessen-Rheinland-Pfalz **cannot be represented today.**

### 6.2 Properties [I]

| | Address source | Committee source |
|---|---|---|
| Authority for the record | The person or organisation that supplied the address | The organisation's competent organ (coverage decision; D-21, D-30) |
| Authority for the mapping to Geography | **Resolution** (suggestion-grade unless confirmed) | **The organisation's decision**, naming canonical units |
| Typical granularity | Down to building | Usually Land, district, municipality |
| Multiple units | One point → one deepest unit | **0..n units, possibly at different levels** |
| Partial units | Not applicable | Expressed by covering sub-units; otherwise an organisation-defined area (Question 1, option c) |
| Overlap | — | Allowed between levels (Germany contains Hessen); same-level overlap is an **organisation rule**, not a geographical question |
| Update cycle | Whenever a person moves | When the organisation decides |

### 6.3 Common mapping model [R]

- Both map to the **same canonical reference identities**.
- A **source identifier** (a national code, a dataset ID) is mapped to a canonical identity by a **crosswalk claim**: source, identifier, validity, evidence. Codes are never identity.
- **Names and aliases** (exonyms, transliterations, old names) are dated name claims of the unit.
- **Source authority ≠ mapping authority:** an address register may be authoritative for an address while the mapping of that address to Wiesbaden is a resolution PublicDigit made.
- A **conflict** (address resolves to Hessen, but the postal code's main municipality lies in Rheinland-Pfalz) is recorded as a resolution conflict on the address, never as a change to Geography.
- **Neither source can introduce a geographical unit.** An unknown unit becomes a **candidate** (D-31); the record keeps its text and points to the deepest known unit.
- **Use before verification:** a committee may name a candidate in its coverage only as *pending* (§42.4); an address may resolve only to verified units.

## 7. NRNA Hessen-Rheinland-Pfalz [I]

A person lives at "Rathausstraße 4, 65203 Wiesbaden" (postal code not verified). Committee: NRNA Hessen-Rheinland-Pfalz.

| # | Question | Answer |
|---|---|---|
| 1 | Same canonical units? | **Yes.** The address resolves to Wiesbaden (position 5), whose containment gives Hessen (3), Germany (2), Europe (1), World (0). The committee's coverage names Hessen and Rheinland-Pfalz (both 3) |
| 2 | One committee, several level-3 units? | **Yes:** coverage is 0..n (§42.4) |
| 3 | Organisational grouping or geographical unit? | **Organisational.** "Hessen-Rheinland-Pfalz" is an Organisation unit (with a Governance organ), **never** a Geography unit (D-31) |
| 4 | Associated with the committee without a new unit? | **Only by a basis** (D-79): an act or an expressly adopted rule. The address may produce a **suggestion** ("Hessen lies in this committee's coverage"); it creates nothing (D-80) |
| 5 | Coverage changes, Länder unchanged? | **Yes.** Adding Saarland is Organisation's decision; Geography is untouched |
| 6 | Official change without changing belonging? | **Yes.** Geography announces; Organisation's organ reviews coverage; belongings are unchanged (D-21, D-79) |
| 7 | Coverage different from belonging? | **Yes.** The person may belong to NRNA Bayern (an act after moving, or none yet) while living in Hessen |
| 8 | D-79's limit | The address is evidence for a suggestion only; **"lives in Hessen" never equals "belongs to Hessen-RLP"** (D-80) |

The model keeps **location** (address resolution), **coverage** (Organisation decision) and **belonging** (Organisation act) as three separate facts.

## 8. Hierarchy: not always a tree [I]

| Case | Treatment |
|---|---|
| Administrative subdivisions | Containment within one dated scheme |
| District-free city | One unit, one position, status equivalence |
| Overlapping jurisdictions | Separate schemes; territorial relations only when evidenced |
| Postal areas | **Not Geography units** (address components) |
| Electoral constituencies, statistical regions | Separate schemes (Question 2) |
| Different official classifications | Classification claims, dated |
| Skipped level | Containment may skip positions |
| Parent changes over time | Dated containment; identity continues (revision 2 R2-7) |

**Geography needs the concept of a scheme and a country profile** in addition to units and levels. **One unit can take part in several schemes under one identity.** [R]

## 9. Identity, succession and time

Revision 2 (R2-7, R2-10) holds: identity continuity, boundary revision, containment revision, succession, correction; five dates; `τ` = PublicDigit's record time only. **Applied here:** [R]

| Fact | `asOf` | `τ` |
|---|---|---|
| Unit identity, name, containment | Legal effective date | Record time of the published claim |
| **Level classification (country profile)** | Profile valid from (Nepal: 20 September 2015) | Record time |
| Boundaries | Legal effective date | Record time |
| Source crosswalk | Validity of the source's identifier | Record time |
| **Address resolution** | The address's own validity (moved in on…) | **Time of resolution**; a later Geography change does not re-resolve silently |
| **Committee coverage** | Organisation's decision effective date | Organisation's record time (its own `τ`, CR-01 convention) |

Example (A divided into B and C, effective 1 January, recorded 15 February): an **address resolved to A on 5 February stays resolved to A as known on 5 February**. A new resolution after 15 February may yield B; the change is a new resolution fact, never a rewrite.

## 10. Official standing and provisional references: Question 1 retested

Revision 2 R2-6 (five dimensions) and R2-12 (options) stand. **Retest against both sources:**

| Use | (a) alone | (a) + (c) | (b) |
|---|---|---|---|
| Address in an area whose official unit is missing | Resolves to the deepest known unit (lower precision); the text is kept | Same | Resolves to a provisional unit; must be re-resolved when the official unit arrives |
| Address in a tole | Resolves to the ward; "tole" stays text | Same; the party may map its own tole area | A provisional tole: a **never-official** unit in Geography (the PD-3 mistake) |
| NRNA Hessen-RLP | **No provisional reference needed** (both official) | Same | Same |
| Committee covering part of a city | Cover the Ortsbezirke | Or an organisation-defined area | A provisional sub-area |
| Reporting by area | Official areas only | Plus the party's own areas, kept separate | Mixed |
| **Levels 0–1** | **Need a classification source** (L-3); otherwise World and continents would themselves be provisional | Same | Same |

**Finding:** addresses reinforce the recommendation **(a) with (c)**: an address never needs a provisional unit, because it can always resolve to a shallower official one. **New:** (a) applies to levels 0 and 1 only if L-3 names a classification source.

## 11. Non-administrative classifications

Revision 2 R2-13 stands. **Correction to the commission's example:** "Election owns its use in a particular election" fits **public** elections. PublicDigit's Election runs **organisations' own** elections, so a public constituency matters to a party as a **coverage target** (Organisation). Question 2 stays the next bounded decision. [I]

## 12. Proposed Geography boundary [R]

| Capability | Owner |
|---|---|
| Canonical units, identities, names, codes, crosswalks | **Geography** |
| The 0–10 framework, **dated country profiles**, schemes, containment, status equivalence | **Geography** |
| Evidence, standing, verification, conflicts, history | **Geography** |
| Boundary facts; geometry as optional source-attributed data | **Geography** (revision 2 R2-9) |
| Resolving a place description to canonical units (a lookup and suggestion service) | **Geography offers it** (open host service) |
| **Address records** (text, components, the resolution result) | **The organisation-local person profile** (model document §31; proposed owner Organisation, INV-AFF-1). **Not a new bounded context** |
| External address registers, postal data | **External sources** behind an anti-corruption layer; no PublicDigit ownership |
| Committees, coverage, belonging, placement rules | **Organisation** (organs: Governance) |

**Address is a capability, not a bounded context:** it has no decisions or invariants of its own beyond "keep what was supplied, and the resolution with its depth and `τ`". [R]

## 13. Contract requirements [R]

Revision 2 R2-11 holds, plus:
- **Geography → consumers:** the country profile as at (asOf, τ); resolution results that always state the **depth reached** and never claim more precision than the evidence gives.
- **Address owner:** keeps the text as supplied, stores the resolution with depth and `τ`, and never treats a resolution as a belonging (D-80).
- **Organisation:** coverage names canonical units or candidates (pending), decided by its organ; never introduces Geography units.

## 14. Risks, contradictions and unresolved assumptions

| # | Item | Kind |
|---|---|---|
| X-1 | Six numbering conventions in code (§3.2) | Implementation contradiction |
| X-2 | Revision 2 said "continents are not official units"; the Principal makes them level 1. **Reconciled:** they are reference units from a **classification** source, not administrative units | Resolved by interpretation; needs L-3 |
| X-3 | "5 = Bezirk" is ambiguous | Needs the Principal's intended meaning |
| X-4 | "6 = Street" conflicts with containment and with Japan | L-2 |
| X-5 | The external address source in §2.2 is unavailable; its properties are assumed | Limitation |
| X-6 | The committee source holds one reference per committee | Implementation gap (conformance report row 5) |
| X-7 | **Organisational levels look like geographic levels:** NRNA's "0 international committee, continents, countries, states, city" (V-16) matches positions 0–4. **They remain separate concepts:** an organisational level may be linked to a position; it is not one | Confusion risk |
| X-8 | Postal code 65203 and Wiesbaden's Regierungsbezirk not verified | Example quality |

## 15. Rejected assumptions

- A level number has the same meaning in every country (beyond 0–2).
- A street is a geographical unit with one parent.
- A postal code is an administrative unit.
- A committee's coverage creates a geographical unit or a belonging.
- An address associates a person with a committee (D-80).
- Party units such as toles and households belong in Geography (D-31).
- Existing code defines the levels.

## 16. Principal decision dossier [OQ]

Ruled one at a time, in this order. **L-1 first:** the others depend on it.

### L-1. What do levels 3–10 mean?

**Why it matters:** every country profile, report and committee link depends on it.

| Option | Meaning | Consequence | Becomes difficult |
|---|---|---|---|
| **A. Global fixed categories** | Each number means one category worldwide (for example 5 = Bezirk) | Cross-country reports by number look simple | **Impossible** to represent Germany, Nepal and Japan faithfully: "Bezirk" means four things; Regierungsbezirke exist only in some Länder; Japan's gun is non-administrative |
| **B. Ordered positions with dated country profiles** | 0–2 global; 3–10 are positions; each country's profile names the class at each position; gaps and several classes per position allowed | Faithful per country; the Principal's examples become **profile entries** (for example DE: 5 = Kreis / kreisfreie Stadt) | Cross-country comparison is only "same depth", not "same thing"; profiles must be maintained |
| **C. Pure tree depth** | The level is the count of ancestors | Simple to compute | Kreise sit at depth 5 in Hessen and 4 in Rheinland-Pfalz: **one class, two levels.** Breaks "Kreis level" |

**Relevant:** V-16, V-17 (organisational levels are Organisation's, X-7); revision 2 R2-8.
**Recommendation: B.** It keeps the Principal's 0–2 and the ordered 0–10 scale, and lets "5 = Bezirk" or "6 = Street" be **country-profile entries** once their intended meaning is stated.
**Remains open until ruled:** whether "5 = Bezirk" is meant as Germany's Kreis position, Regierungsbezirk, Ortsbezirk, or Austria's Bezirk.

### L-2. Are streets and below part of the geographical framework?

| Option | Consequence | Becomes difficult |
|---|---|---|
| **(i) Yes: streets, buildings and addresses are Geography units at upper positions** | Street-level search and reporting | Streets cross boundaries (no single parent); Japan has none; very large external data with licensing; Geography becomes an address register |
| **(ii) No: Geography ends at the deepest official division; addresses keep street and house number as components, with a resolution depth on the 0–10 scale** | Geography stays a territorial reference; "Street" survives as **precision**, not as a unit | Street-level reports need the address data, not Geography |
| **(iii) Streets only where an official street register exists, as named places linked to one or more municipalities** | A middle way for countries with registers | Uneven coverage across countries |

**Recommendation: (ii)**, with (iii) possible later. **Remains open:** whether any business capability needs street-level geography.

### L-3. What defines level 1 (continent or region)?

| Option | Consequence | Becomes difficult |
|---|---|---|
| **(i) A recognised international classification**, for example the UN's statistical regions, cited as its source | Levels 0–1 meet Question 1 (a) | Its groupings may differ from common usage (for example where Cyprus or Turkey sit) |
| **(ii) A PublicDigit-defined list** | Full control | PublicDigit becomes the authority: contradicts "recording is not establishing"; needs Question 1 (b) for levels 0–1 |
| **(iii) No level-1 units; organisations define their regions** | Geography starts at country | Contradicts the Principal's level 1 [PS] |

**Recommendation: (i).** Regions an organisation uses (NRNA's "Middle East" or "Asia Pacific", for example) stay **Organisation units covering countries**.

### Question 1 (pending since revision 2)

Unchanged options and recommendation: **(a) with (c)**, reinforced by the address retest (§10).

### Question 2

Prepared (revision 2 R2-13; §11); after Question 1.

## 17. Recommended next strategic step

After L-1 to L-3 and Question 1 are ruled: **write Germany's and Nepal's country profiles as worked examples** and test them against the 2015 Nepal change and Rheinland-Pfalz's 2000 dissolution. That is the last strategic check before tactical design.

## 18. Readiness for tactical design

**Not yet.** Ready when L-1, L-2, L-3 and Question 1 are ruled and the two example profiles hold. Question 2 may stay open.

## Sources

- [Constitution of Nepal 2015](https://www.constituteproject.org/constitution/Nepal_2015.pdf?lang=en) (revision 2 R2-14)
- [Districts of Japan](https://www.wikipedia.org/wiki/Counties_of_Japan); [Administrative divisions of Japan](https://www.wikipedia.org/wiki/Subdivisions_of_Japan) (secondary sources)
- [OpenStreetMap: Regierungsbezirke in Hessen](https://wiki.openstreetmap.org/wiki/Category:Regierungsbezirke_in_Hessen)
- [Regierungsbezirk (Simple Wikipedia)](https://simple.wikipedia.com/wiki/Regierungsbezirk): Rheinland-Pfalz dissolution, 1 January 2000
- [Wiesbaden, Ortsbezirke (city statistics)](https://wiesbaden.de/medien/downloads/leben-in-wiesbaden/amt-12/12_Ortsbezirke_Auszug_Statistisches_Jahrbuch.pdf)

---

## Addendum A: two governed structures, and central identity versus central classification (2026-11-09)

**Status:** PROPOSED. Records the Principal DDD Architect's refinement as **an agreed strategic direction for validation, not a ratified decision**.

### A.1 Agreed direction (as proposed)

| Structure | Owner | Holds |
|---|---|---|
| **Central geographical reference** | Geography | The level classification (its definitions), country-specific schemes and canonical geographical units |
| **Address mappings** | The address owner (§12) | Address → canonical units, with resolution depth and `τ` |
| **Organisation's committee structure and mappings** | Organisation | Committees, their coverage as references to canonical units, belonging |

Four things are kept apart: the **central level classification**, the **country schemes**, **address mappings** and **organisation mappings**. A street is never forced into the administrative hierarchy merely because both carry a level number. **This is consistent with L-1 option B and L-2 option (ii).** [I]

**The two-organisation example holds:** NRNA (Hessen-Rheinland-Pfalz → Hessen + Rheinland-Pfalz) and Organisation B (Hessen → Hessen) reference **one** Hessen. Neither can redefine Hessen, its country or its level; their structures and coverage differ freely. [I]

### A.2 One disagreement with the refined table

The table "3 State/Province · 4 District · 5 Bezirk" is marked illustrative, but read as fixed categories it fails:

| | 3 | 4 | 5 | 6 |
|---|---|---|---|---|
| Germany (Hessen) | Land | Regierungsbezirk | **Kreis** (the German "district") / kreisfreie Stadt | municipality |
| Germany (Rheinland-Pfalz) | Land | *(absent)* | Kreis / kreisfreie Stadt | municipality |
| Nepal | Province | **District** | municipality / rural municipality | ward |

"District" sits at 4 in Nepal and 5 in Germany; "Bezirk" is not at 5 in either country. **Read as country-profile entries (L-1 B), the table works; read as global categories (L-1 A), it does not.** [I]

### A.3 The question: central identity, or central classification only?

| | **Alternative 1: central catalogue of levels and units** | **Alternative 2: central classification only; units held by several sources** |
|---|---|---|
| Who assigns a unit's identity | Geography: one canonical identity per real-world unit | Each source (national register, open dataset), under its own identifiers |
| Who supplies the facts (names, containment, boundaries, changes) | **Competent sources, recorded by Geography as evidenced claims** (revision 2 R2-6) | The sources directly |
| Two organisations referencing Hessen | The same identity, guaranteed | **Possibly two identities** from two sources; equality needs a crosswalk |
| Succession (a division) | Recorded once, visible to everyone | Each source records it differently, or not at all |
| "As at (asOf, τ)" history | One history | One per source; no common `τ` |
| Effort | Geography must hold each country it serves | Lower initially |
| Risk | Geography becomes a bottleneck for countries it does not yet hold (mitigated: units can be **candidates**, coverage pending, §6.3) | **Exactly the conflicting identities the refinement wants to prevent** |

**Findings:**

1. **Alternative 2 cannot prevent conflicting identities** without a central crosswalk that decides "these two source records are one unit". That crosswalk *is* a central identity catalogue, so Alternative 2 turns back into Alternative 1. [I]
2. **Alternative 1 does not mean Geography authors geography.** It centralises **identity and classification**; the **facts stay the sources'**, recorded with evidence and competence (revision 2 R2-6; "recording is not establishing"). [R]
3. **D-31 already settles this at boundary level.** [AD] D-31 (direct): "Official geography is its own platform-level bounded context (Reference Geography): **official units, revisions, approval of submitted missing units**." A context that owns official units and their revisions is Alternative 1. **Alternative 2 would reopen D-31**, and nothing found contradicts D-31. What D-31 left open is R-10's identity and temporal semantics (XD-5); revision 2 and this report address that.

**Recommendation [R]:** **Alternative 1, "central identity, evidenced facts":** one canonical identity per unit and one level classification, both governed by Geography; every fact backed by a competent source; external identifiers held as crosswalk claims. **No new Principal decision is needed:** D-31 covers it.

### A.4 Effect on the open questions

- **L-1** (meaning of levels 3–10) is still needed. The refinement's distinction between a central classification and country schemes is option B.
- L-2, L-3, Question 1 and Question 2: unchanged.

---

## Addendum B: review revision on level semantics and decision readiness (2026-11-09)

**Status:** PROPOSED. Commission: "Reference Geography: Strategic Architecture Review, Level Semantics and Principal Decision Readiness" (Principal DDD Architect; recommendations accepted by the Principal). **Where Addendum B differs from §1–§18 or Addendum A, Addendum B applies.** Not committed (commission §14).

### B.0 Disagreements with the commission (stated first)

1. **The review's key question mixes country and use.** "May Level 6 mean Street for addresses while it means Ward for Nepal's committees?" **Yes, but only because those are two countries' profiles. Never because they are two uses.** Within one country, an address and a committee must read a number identically (B.2).
2. **"Organisation owns committee structure" needs one refinement.** [AD] Organisation owns units and belonging (D-3, D-22). [AD] Governance owns positions and tenures (D-4) and has an Organs module (D-5). **The committee as a place in the structure, and its coverage, is Organisation's; the committee as a decision-making body is Governance's organ** (§42.2, PROPOSED).
3. **One of my own earlier claims was wrong.** §5 said streets are mere address components. **In Germany, municipalities name streets by official act** [I, general knowledge of municipal law, not verified here]. A street therefore *can* be an official fact with a competent authority. It is still **not a territorial division** (it has no area containment). The Street question is about **identity and containment, not authority** (B.3).

### B.1 Authority of existing decisions (verified wording)

| Ref | Exact wording or content (source) | What it settles | What it does not settle |
|---|---|---|---|
| D-31 | "Official geography is its own platform-level bounded context (Reference Geography): official units, revisions, approval of submitted missing units. The organisation's own units are Organisation's." (direct) | Geography **owns official units** and their revisions | **Not** that every consumer must reference one canonical identity. The record itself says: "R-10 … **D-31 settles the context boundary only; identity and temporal semantics open**" (discovery record line 563; XD-5) |
| D-79 | Placement by organisation's rules; default individual acts; address only suggests; residence rule only if expressly adopted (direct) | Basis of belonging | — |
| D-80 | "address of a person and committee structure is two different things…" (direct) | Address ≠ coverage ≠ belonging | — |
| D-21, D-30 | Highest Committee makes structural changes; change sets independent unless declared indivisible | Structural authority | — |
| V-16, V-17 | NRNA: "internal committee … zero level, then continents, then countries, then states and then city … defined by the highest committee"; similar structure everywhere, exceptions in the rule log | Organisational levels are Organisation's | Their link to geographic positions |
| V-22 | "Organisation's own territorial structure; keep C4 and C5 separate" | Organisation's territory ≠ Geography | — |
| V-23, V-24 | Admitted into a specific unit; one geographic unit, wings in addition | Belonging cardinality | — |
| D-73 / CR-01 rev. 2 | `τ` = knowledge boundary over `recordedAt ≤ τ`; `closedThrough` guarantee; queries beyond it refused; **one `τ` propagated across a multi-context operation** (CR-01 §2, §3, §10) | Temporal semantics | Geography's own `closedThrough` |
| D-75 via XD-3b §2a | "Name, contact details and other descriptive data live in each organisation's database. That organisation is the source of its members' data." (accepted 2026-11-09) | **Addresses are organisation-local**, not Identity's | **Which context** inside the organisation owns the person profile. INV-AFF-1 concerns the reference mechanism and remains open; "Organisation" as owner (model document §31) is **my proposal** |

**Correction (central identity, Addendum A.3):** "D-31 already settles this" was **too strong**. **Corrected:** central canonical identity is **consistent with** D-31 (Geography owns the official units, so it also holds their records). How identity, revision and time work across consumers is **open architecture (R-10, XD-5)**, not a Principal decision. Alternative 2 is rejected on the identity argument (A.3, finding 1), not on D-31's authority.

### B.2 What a level number is, and the three uses

**Definition (proposed) [R]: a level is a classification position.**
- A country's **dated profile** assigns each **geographical class** of that country (Land, Kreis, Province, District, Ward…) **one position number**; several classes may share one.
- **Rule of order:** along containment, positions **strictly increase**; containment **may skip** positions.
- **A level is never computed by counting parents.** In Rheinland-Pfalz a Kreis has the Land as its parent (one step) and is still at position 5 (Regierungsbezirke dissolved 1 January 2000).
- **It is not an authority.** Who established a unit is evidence (revision 2 R2-6), not its level.

| Concept | Definition | Example |
|---|---|---|
| Position (level number) | The number the profile gives a class | DE Kreis → 5 |
| Geographical class | The country's own category | Kreis, kreisfreie Stadt |
| Containment | Which unit lies within which, dated | Wiesbaden within Hessen (through Regierungsbezirk Darmstadt) |
| Administrative authority | Who establishes the unit | Land law |
| Status equivalence | A unit with the status of a second class | Wiesbaden: kreisfreie Stadt, also a municipality |

**The three uses are not three numberings:**

| Use | Where its number comes from | Own numbering? |
|---|---|---|
| **A. Geographical classification** | The unit's class, through the country profile | **The** classification |
| **B. Address resolution level** | The position of the **most precise class the address was resolved to** (a unit's class, or an address-only class such as Street that the same profile positions, B.3) | **No:** read from the same profile |
| **C. Organisational coverage level** | The positions of the **units the committee references** (Hessen + Rheinland-Pfalz → both 3; mixed levels allowed) | **No:** derived from the referenced units |
| *(Organisational level, V-16)* | **Organisation's own concept** | Separate; it may be *linked* to a position, never identical (X-7) |

**Finding [I]:** **one classification per country, read by both addresses and committees,** satisfies the Principal's requirement [PS] that both use levels 0–10. There is no semantic conflict as long as **no number has two meanings within one country**:
- Germany's Street at a given position and Nepal's Ward at 6 do not conflict.
- **Within Nepal, Ward = 6 for committees and Street = 6 for addresses** would conflict, and the profile forbids it.

**Consequence for the Principal's examples [PS]:** "5 = Bezirk, 6 = Street" can only be **one country's profile** (or a city profile). For all of Germany it collides with Kreis and municipality, for example: 3 Land, 4 Regierungsbezirk, 5 Kreis / kreisfreie Stadt, 6 municipality, 7 Ortsbezirk, then Street. **Which German profile is intended is profile content, decided after L-1** (B.6).

### B.3 Street, re-examined

| Question | Finding |
|---|---|
| Can a street be a geographical reference? | Yes, as a **linear feature**, named by a competent municipality [I] |
| Can it cross administrative boundaries? | Yes (Ortsbezirke, and municipalities) [I] |
| Can an address identify a street without the intermediate units? | Yes. It names municipality and street, not Regierungsbezirk or Kreis |
| Does a street need a canonical identity? | **Only if something other than one address must refer to the same street.** For example, a committee whose area is defined by streets (German local branches inside a city are commonly drawn along streets [I, unverified]) |
| Does Street belong to the classification committees use? | **It belongs to the same profile** (B.2). Whether committees may *reference* streets is L-2 |
| If Street is address-only, how does 0–10 represent it? | The profile gives Street a **position**, marked **address-only** (no Geography units). An address resolved to street precision then has that position as its resolution level |
| Does this contradict the shared framework? | **No:** the number comes from the same profile. Only Geography's holding of street identities differs |

**Evidence that addresses do not follow administrative units [X]:**
- Postal code 65203 is used in Biebrich, in Schierstein, and in a district named **"Mainz-Amöneburg"** [X directories].
- Biebrich itself has six postal codes [X].
- Wiesbaden's own Biebrich office gives **"65203 Biebrich" as its address but "65209 Wiesbaden" as its postal address** [X wiesbaden.de], and **also serves the Ortsbezirk Amöneburg** [X wiesbaden.de].
- That Amöneburg lies in Wiesbaden, Hessen, is inferred from the office's responsibility and general knowledge, not checked against an official source. **If true, a name-based resolution of "Mainz-Amöneburg" would wrongly place the address in Mainz, Rheinland-Pfalz.**
- *Rathausstraße 4* itself is not verified.

### B.4 Address: what is known, what is not [corrected]

| # | Item | Status |
|---|---|---|
| 1 | External address source (Principal §2.2) | **Exists per the Principal [PS]; not available to inspect.** Its identifiers, levels, corrections and update cycle are **unknown**. Not finding it in the repo is no evidence that it does not exist |
| 2 | Address supplied by a person | Organisation-local data (**[AD] D-75 via XD-3b §2a**) |
| 3 | Address retained by PublicDigit | [E] Text fields on `users` (`street`, `housenumber`, `postalcode`, `city`, `state`) and `members.residence_geo_unit_id` |
| 4 | Mapping an address to canonical units | A **resolution claim**: units reached, position reached, source, the Geography knowledge used, record time |
| 5 | Resolution state | **resolved** · **partially resolved** (deepest unit below the precision supplied) · **ambiguous** (several candidates) · **unresolved** |
| 6 | Owner of organisation-local address data | **Open.** Organisation-local is settled (row 2); **which bounded context is not** (my proposal, Organisation, model document §31, is unratified) |

**Address is not a new bounded context** [R]: no evidence of its own decisions or invariants beyond keeping what was supplied and the resolution claim.

### B.5 Committees: implementation and requirements

- **[E] Implementation fact only:** committees live in `app/Contexts/Membership/Domain/Committee/` with one `operational_geo_reference` each. **Conformance gap; it transfers no ownership** (Organisation units D-3, D-22; organs and positions D-4, D-5).
- **Coverage requirements [I] [R]:**
  - **0..n** references (zero for central and non-geographic units, by organisation rules);
  - several units at one position (Hessen + Rheinland-Pfalz);
  - units at **different** positions (Hessen plus the city of Mainz, for example);
  - dated changes, decided by the organ (D-21);
  - overlap where the organisation's rules permit it;
  - **partial coverage only through sub-units** or, if Question 1 allows (c), an **organisation-defined area**.

### B.6 Temporal correction (against CR-01)

Revision 2 R2-10 wrote "`τ` = PublicDigit's record time". **That loses CR-01's semantics.** Corrected [R]:
- **`recordedAt`** is a property of each fact (database clock at commit).
- **`τ`** is a **knowledge boundary** of a query: the facts with `recordedAt ≤ τ`.
- **`closedThrough`:** a query with `τ` beyond it is **refused, never answered partially** (CR-01 §3).
- **One `τ` per multi-context operation** (D-73 requirement). An electorate cut-off reads Membership, Organisation and Organisation's copy of Geography at **the same `τ`**. **Geography therefore needs the same `closedThrough` guarantee.**
- **Applied:**

| Fact | Effective (`asOf`) | Knowledge |
|---|---|---|
| Country profile | Valid from (Nepal 20 September 2015; Rheinland-Pfalz without Regierungsbezirke from 1 January 2000) | Geography `recordedAt`, queried at `τ` |
| Source crosswalk or mapping correction | Validity of the identifier | New claim; earlier knowledge kept |
| Address resolution | Validity of the address | The address owner's `recordedAt`; **records which Geography knowledge (`τ` and position in its log) it used**; re-resolution is a new claim |
| Committee coverage | Decision's effective date | Organisation `recordedAt`; records the Geography knowledge relied on |

- Source publication, receipt and verification dates remain **evidence attributes**, never `τ` (R2-10).

### B.7 Missing data and Question 1, re-examined

| Situation | What it is | Is a shallower official unit enough? |
|---|---|---|
| Address with an unknown locality, municipality known | **Partially resolved address** | Enough for suggestions and reporting at municipality level. **Not enough** where an organisation's adopted residence rule (D-79) places members at a finer level: then **no automatic placement**, an act is needed |
| Address whose municipality cannot be identified ("Mainz-Amöneburg") | **Ambiguous or unresolved address** | **No.** Must not be resolved by guessing; the candidates are kept |
| Municipality known, street unknown | Partially resolved | Enough unless coverage is street-defined |
| Committee covering a whole official region | Coverage of official units | Fully supported |
| Committee covering part of a region | **Coverage gap** | Sub-units if they exist; otherwise an organisation-defined area (option c) |
| Organisation-defined local area (tole) | **Organisation's own fact** | Not a Geography matter |
| New unit reported, evidence pending | **Candidate** (D-31) | Coverage may name it as *pending*; addresses resolve to its parent |
| Unknown source identifier | **Crosswalk gap** | The record keeps the identifier; resolution falls back |
| Competing sources | **Disputed fact** (revision 2 R2-6) | Consumers see the conflict |

**Finding [I]:** none of these cases needs a **provisional geographical unit**. They need **(1) address resolution states that never overstate precision** and **(2) organisation-defined areas** for partial coverage. **(b) would mainly substitute provisional units for unresolved addresses**, which hides uncertainty instead of recording it. **The risk that matters most:** under an adopted residence rule (D-79), a provisional or guessed unit would **move members between electorates**.

**Corrected Question 1 dossier:**

| | (a) alone | (a) + (c) | (b) |
|---|---|---|---|
| Unresolved addresses | Recorded as unresolved | Same | Tempting to fill with provisional units |
| Partial coverage | Only through official sub-units | **Organisation-defined areas** | Provisional sub-units |
| Residence-rule placement (D-79) | Only on resolved addresses | Same | **Risk of placement on non-official units** |
| Becomes difficult | Partial coverage without sub-units | — | Reconciling provisional with official units; two organisations' differing views of one area |

**Recommendation: (a) + (c)**, conditional on resolution states (B.4 row 5) being part of the address contract. **Uncertain:** whether the external address source can supply municipality identities reliably (B.4 row 1).

### B.8 Source audit

| Claim | Source now | Grade |
|---|---|---|
| Hessen: Regierungsbezirk Darmstadt includes the kreisfreie Städte Darmstadt, Frankfurt am Main, Offenbach am Main and Wiesbaden; seat Darmstadt | Hessian law on the Regierungspräsidien, § 2(2), in force since 1 January 2012, as quoted from a statute collection; RP Darmstadt brochure | **Statutory (via collection) + official** |
| Hessen's three Regierungsbezirke: Darmstadt, Gießen, Kassel | RP Darmstadt brochure (official); the OpenStreetMap category is **replaced** | Official |
| Rheinland-Pfalz: Bezirksregierungen Koblenz, Rheinhessen-Pfalz and Trier "zum 1. Januar 2000 aufgelöst" | Verwaltungsorganisationsreformgesetz of 12 October 1999, § 5 (text via umwelt-online; **landesrecht.rlp.de not checked**) | Statutory (secondary host) |
| Wiesbaden: 26 Ortsbezirke (Hauptsatzung 1969, § 3); Biebrich the largest | City statistics, wiesbaden.de | Official |
| Postal codes 65203 / 65209 for Biebrich | wiesbaden.de (official); postal directories (secondary) | Partly official |
| Amöneburg lies in Wiesbaden, Hessen | Inferred from the Biebrich office serving Ortsbezirk Amöneburg (wiesbaden.de); general knowledge | **Not verified** |
| Japan: gun "has no substance as an administrative unit; merely a geographical name" (行政単位としての実質がなく、単なる地理的名称) | Ministry of Internal Affairs and Communications (soumu.go.jp) | **Official** |
| Japan: prefectural assembly constituencies based on gun and city areas | Same ministry document (summary) | Official (summary) |
| German municipalities name streets | General knowledge | **Not verified** |
| Nepal | Constitution 2015, **unofficial** English translation (Nepal Law Society, IDEA, UNDP) | Primary text, unofficial translation |

### B.9 Revised Principal decision dossier

**L-1. What do levels 3–10 mean?** *Can now be presented cleanly* (B.2 resolves the Street conflict).
- **A. Fixed global categories:** impossible for Germany, Nepal and Japan (B.2, §4).
- **B. Classification positions in dated country profiles,** read identically by addresses and committees: 0–2 global; containment strictly increasing, gaps allowed; never computed by counting parents.
- **C. Tree depth:** a Kreis would be 5 in Hessen and 4 in Rheinland-Pfalz.
- **Recommendation: B.**
- **What becomes difficult under B:** cross-country comparison means "same position", not "same thing"; profiles must be maintained and dated.
- **Remains open after L-1:** the content of each country profile, including what the Principal meant by "5 = Bezirk, 6 = Street" for Germany.

**L-2. Street.** Street has a profile position in every option; the question is **whether Geography holds street identities**.
- **(i) Geography units** that may lie in several municipalities: street-defined coverage possible; a large external data burden; Japan has none.
- **(ii) Address-only class:** a position without Geography units; street-defined coverage only through organisation-defined areas.
- **(iii) Identities only where an official street register exists.**
- **Recommendation:** (ii) now, (iii) when a party needs street-defined coverage. **Less firm than before** (B.0 point 3).

**L-3. What defines level 1?**
- **(i) A statistical regional classification**, for example the UN's standard regions for statistical use: an authority for statistics, explicitly not for political affiliation [I]; its regions are not the conventional continents (for example one region for the Americas).
- **(ii) Conventional continents:** no single authority, so PublicDigit would choose, contradicting "recording is not establishing".
- **(iii) No level 1:** contradicts [PS].
- **Recommendation: (i).** Regions an organisation uses (such as NRNA's) stay Organisation units covering countries.

**Question 1:** (a) + (c), conditional (B.7).

**Question 2:** separate; **not a dependency of L-1 or L-2.** New evidence that it matters: Japan bases constituencies on non-administrative gun areas.

### B.10 Answers to the commission's seven questions

1. **Confirmed:** central identity; three separate facts in the NRNA case; the code's six numberings carry no business meaning.
2. **Corrected:** D-31 overclaim (B.1); `τ` semantics (B.6); address ownership (B.4); committee ownership refined (B.0 point 2); street authority (B.0 point 3); sources (B.8).
3. **Too strong before:** "D-31 already settles this"; "address can always fall back"; "Organisation owns address records"; "streets have no authority".
4. **Settled by existing authority:** D-31 (official units in Geography), D-79, D-80, D-75 (addresses organisation-local), CR-01 time semantics, D-3, D-4, D-5.
5. **Open Principal decisions:** L-1, then L-2, L-3, Question 1, Question 2.
6. **L-1 can now be presented as a clean decision:** yes.
7. **Does the common 0–10 framework serve addresses and committees without semantic conflict?** **Yes, under L-1 B**, provided one rule holds: no number has two meanings within one country.

**Readiness for tactical design:** not yet. It needs L-1 to L-3 and Question 1, plus worked German and Nepalese profiles.

---

**Ruling recorded (2026-11-09): L-1 option B accepted by the Principal as D-81** (direct: "I accept L-1 option B"; discovery record §11.15). L-1 is closed. `CountryScheme`'s structure (per-country dated profile, designated reference scheme read by addresses and committees) is no longer conditional on L-1. Still open, in order: Question 1, L-2, S-1, L-3, Question 2.
