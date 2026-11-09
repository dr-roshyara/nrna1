# Reference Geography: strategic discovery (business baseline)

| | |
|---|---|
| **Status** | Strategic discovery, PROPOSED. Decides nothing. No aggregates, no migrations, no code changed |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Labels** | [AD] accepted decision · [E] implementation fact (this repo) · [PD] fact from public-digit documents · [I] proposed business interpretation · [R] architectural recommendation · [OQ] Principal decision |
| **Commissioned by** | Principal DDD Architect review, 2026-11-09 (accepted by the Principal): Geography strategic discovery only; Membership not reopened; Organisation decisions preserved |
| **Supersedes** | Decision 1 (a/b/c) in `2026-11-09-strategic-foundation-reassessment-geography-organisation-membership.md` §H is **withdrawn and reframed** (§9 below): it mixed the source of official status with who may record it |
| **Sources read** | All 12 top-level documents and 4 brainstorming documents in `public-digit/architecture/backend/geography-context/` (2025-12-17 to 2026-01-02); this repo's Geography migrations; D-31; R-10 (model document) |

## 1. Preserved and not reopened

- [AD D-31] Official geography is a **platform-level bounded context**; the organisation's own units are Organisation's.
- [AD] Ratified Membership architecture (2026-10-08) and CR-01; D-73, D-75, D-77.
- [AD] Organisation decisions: D-3, D-13 to D-22, D-27, D-29, D-30, **D-79**, V-16, V-17, V-20 to **V-24**, V-53, V-54, V-15.
- **Open integration problem recorded, not solved here:** under V-23 an effective admission must never exist without a unit belonging. "Organisation records it eventually" (reassessment G-7) **does not demonstrate this** and is withdrawn as a resolution. It belongs to the Membership–Organisation tactical contract.
- Organisation tactical design (model document §45) stays a proposal; not for acceptance until this baseline and that contract exist.

## 2. What the public-digit documents show

| # | Finding | Evidence | Assessment |
|---|---|---|---|
| PD-1 | "Membership FIRST … Geography LATER (when needed)"; a diaspora organisation may "never install Geography" | `20251231_1003_Business_case_geopgraphy.md` | **Take:** consistent with §42.4 (units need no geography) and D-79 |
| PD-2 | Landlord "golden source"; each tenant gets a copy with `landlord_geo_id` and `is_official` | `how_hybrind_geography_concept_works.md`; ADR-001 (as quoted there, 2025-12-31) | **Take the copy** (R-10, T-3); the source reference is right |
| PD-3 | Tenants add **"custom party units, levels 6–8"** (sub-ward, tole, household) to the geography table | same; `brainstorming/20260102_1139_critical_to_haas.md` | **Don't take:** party-specific units are Organisation units (D-31). **This repo inherited it** [E]: `Geography/…/Tenant/2025_01_01_000001_create_geo_administrative_units_table.php:16, 52, 70` |
| PD-4 | Platform levels "1=country, 2=province …" in one document; "1=province/state" in another and in this repo | `critical_to_haas.md`; [E] `Landlord/2025_01_01_000002_…:28` | **Evidence that a level number has no stable meaning.** It must not carry business meaning |
| PD-5 | Platform units with `valid_from`, `valid_to`, `official_code`; `UNIQUE(country_code, level, official_code)` | `critical_to_haas.md` | **Don't take the key:** a code is not identity (codes are reused). This repo: [E] `unique(['country_code','code'])` (`Landlord/…000002…:68`) |
| PD-6 | Sync change types NEW, UPDATED, INACTIVATED, MOVED; new units arrive "pending_review" for the tenant admin | `critical_to_haas.md` | **Partly take:** classify changes, but add DIVIDED and MERGED with successors; a tenant may decide whether **it uses** a unit (Organisation coverage), never whether it is official |
| PD-7 | "Hierarchy Observer": when a ward is split, the platform emits an event and tenants **prompt their administrators to re-assign members** | `brainstorming/Hierarchy-as-a-Service_HaaS.md` §4A | **Take the principle:** inform and ask, never reassign automatically (D-21, D-79) |
| PD-8 | "Every member is hard-linked to a specific Ward"; "President of [GeographyID]"; `member_geographies` with `role`, `is_primary` | `Hierarchy-as-a-Service_HaaS.md`; `critical_to_haas.md` | **Don't take:** it merges belonging (Organisation), position (Governance) and geography into one link |
| PD-9 | Geography called a "Shared Kernel": read-only for tenants | `brainstorming/20260101_1210_use_of_shared_kernel.md` | **Rename the relationship:** a shared kernel is a model *co-owned* by two teams. Here one context publishes and others consume: **open host service with a published language** (D-31) |
| PD-10 | No concept of source authority, evidence, succession, or knowledge time anywhere | all 16 documents | Confirms the gap found in this repo (reassessment G-2) |

## 3. Purpose

[I] Reference Geography tells the platform **which official geographical units exist, how they are officially arranged, and how that has changed over time, together with the evidence for each fact.** It is a **reference**: it records the official world; it does not create it, and it decides nothing for any organisation.

## 4. Three things that must not be confused [I]

| Concept | What it is | Who determines it |
|---|---|---|
| **Official unit** (real world) | The district, municipality or ward that the competent public authority has established | The public authority (a constitution, statute, gazette notice, official dataset) |
| **Reference record** | PublicDigit's record of that unit: a stable reference identity and its recorded facts | Reference Geography, from evidence |
| **Evidence** | The source supporting a fact: official dataset, gazette notice, legal act, official publication | External; Reference Geography records it with every fact |

**Principle:** a reference record never makes a unit official. **Recording is not establishing.** A platform operator records official facts on evidence; the operator's approval is an act of verification, not of authority.

## 5. Status of a reference record [I]

| Status | Meaning |
|---|---|
| **Official, from a machine-readable source** | An authoritative dataset supports it |
| **Official, from documentary evidence** | An authoritative publication supports it (for example a gazette), entered and verified manually |
| **Candidate** | Submitted (for example by an organisation, D-31); not yet supported by sufficient evidence |
| **Disputed** | Sources contradict each other; neither version is presented as established |
| *(Provisional, non-official)* | **Only if the Principal permits it** (§9) |

## 6. Levels and hierarchy [I]

- **A level belongs to a country's administrative scheme**, not to the platform. "Level 2" in Nepal and in Germany are unrelated concepts (PD-4).
- **A country's level scheme is itself dated.** Example: Nepal's 2015 constitution replaced 5 development regions and 14 zones with 7 provinces. A query for 2014 must show zones; a query for 2020 must show provinces.
- **The level number is only an order within one scheme;** the meaning sits in the scheme's named level (province, district). `GeoLevelType` as a fixed list of seven names cannot hold this (finding C-a).
- **The tree assumption must be tested, not inherited** from `parent_id` and `path`:
  - *Level gaps:* a unit may sit directly under a unit two levels up. [I]
  - *One unit, two levels:* a city may be both a district-level and a municipality-level unit (for example German district-free cities). [I]
  - *Several official divisions:* electoral constituencies do not nest inside administrative districts in every country. **Whether PublicDigit must represent them is a business question (§9, question 2).**
  - [R] Treat the **administrative hierarchy as one dated tree per country**, allow a unit to hold more than one level, allow gaps; keep any other official division as a **separate hierarchy**, never merged into the administrative one.
- **Continents and world regions are not official units.** [R] At most reference groupings. An organisation's "Europe" is an Organisation unit covering countries (§42.4). [E] Continents are stored today as level 0 units (`…extend_level_range_add_continents.php`).

## 7. Identity and succession [R]

**Identity continues while the source treats the unit as the same unit; succession links units that are not the same.**

| Change | Identity | Recorded as |
|---|---|---|
| Rename, boundaries unchanged | Same | New name, effective date, evidence |
| Code changed | Same | New code; **the code is never the identity** |
| Moves under another parent | Same | New parent relationship |
| Territory transferred between two units | Both same | A boundary revision on each, with evidence |
| Division (A → B, C) | A ends; B and C are new | Succession: A → {B, C} |
| Merger (A, B → C) | A and B end; C is new | Succession: {A, B} → C |
| Absorption (A into B) | A ends; B continues | Succession: A → B (B continuing) |
| Code reused for a different unit | New identity | The old code's history stays with the old unit |

- **Continuity is a recorded assertion with evidence, never inferred** from a similar name or code. Fuzzy matching (T-4) only *suggests*.
- Example [I]: Nepal's Nawalparasi and Rukum districts were each divided in 2017 (75 → 77 districts): two divisions with successors.

## 8. Two dimensions of time [R]

Consistent with the platform's accepted `asOf` / `τ` distinction (Membership ratification; D-73):

- **Official validity (`asOf`):** from and until when the unit, name, parent or level was officially in force, **as the evidence states**.
- **Knowledge (`τ`):** when PublicDigit recorded the fact (database clock at commit, as in the §38.2 barrier).
- **Corrections** are new records with their own record time; earlier knowledge is never rewritten.
- **Worked example.** District A is divided into B and C, legally effective 1 January; PublicDigit records it on 15 February.

  | Query | Result |
  |---|---|
  | Valid on 20 January, as known on 10 February | A (the division was not yet known) |
  | Valid on 20 January, as known on 20 February | B and C (retroactive knowledge) |
  | Organisation coverage decided on 5 February | Still references A (its basis records what was known); flagged for review on 15 February |
  | Election with cut-off τ on 10 February | Uses A: the election's τ fixes what counts |

- **What happens to coverage of A** [AD D-21, D-79; I]: the old coverage stays historically valid; it **does not become coverage of B and C automatically**; Organisation is told and its organs decide.

## 9. Principal decisions

**Question 1 (reframed).** *Can PublicDigit establish a usable geographical reference when official status cannot be established from an authoritative source? And if so, how must that reference be shown so that nobody mistakes it for an official fact?*

| Option | Meaning | Consequences |
|---|---|---|
| **(a) No** | Reference Geography holds only official records (machine-readable or documentary evidence). Everything else stays a candidate. | "Official" is never ambiguous. Organisations are **not blocked**: their units need no coverage (§42.4) and coverage can stay pending. Where no source exists, coverage cannot be expressed geographically |
| **(b) Yes, platform-wide provisional** | The operator may publish a **provisional, non-official** reference on evidence, visibly marked, usable by every organisation | Organisations can express coverage everywhere. Two kinds of "geography" coexist; every consumer must handle the distinction; a provisional unit may later conflict with the official one |
| **(c) Yes, but organisation-local only** | No provisional records in Reference Geography; an organisation may describe its own area inside Organisation (for example as a named area on its unit) | The platform's reference stays official; an organisation's own description is clearly its own fact; no sharing between organisations |

In every option, **a platform operator records facts only on evidence, and the evidence is recorded with the fact**; the operator never creates official status.

**Recommendation: (a).** Coverage only informs and never decides belonging (D-79), so no business process depends on a provisional reference. (c) remains available later without touching Reference Geography. **Undecided until you rule:** whether a status "provisional" exists at all.

**Question 2 (next, after question 1).** Must Reference Geography represent **official divisions other than the administrative hierarchy**, for example **electoral constituencies**, which parties may organise around and which do not always nest inside districts?

## 10. Facts other contexts may consume [R]

- Official units "as at (asOf, τ)" per country, with status, names, codes, parents, levels and evidence references (R-10 `OfficialUnitsAsAt`).
- The country's level scheme as at a date.
- Revision events, including **successor sets** for divisions and mergers (R-10 `OfficialUnitRevised`).
- Candidate status for submissions an organisation made (D-31).

Consumers keep a rebuildable copy (R-10, PD-2). **No context reads Geography's tables** (T-2).

## 11. What Geography never decides

Which units an organisation has, what it calls its levels, what its units cover, where a person belongs, whether a person's residence means anything, who may vote, or whether a party's sub-ward or household cluster exists (those are Organisation units, PD-3).

## 12. What remains open for tactical design (architecture, not the Principal)

Representation of a scheme with dated levels; a unit with more than one level; the succession model; evidence references; the R-10 identity and revision contract (XD-5); how today's per-tenant copy with custom units (PD-3) is separated into Reference Geography and Organisation.

---

# Revision 2: strategic validation and decision readiness (2026-11-09)

| | |
|---|---|
| **Status** | PROPOSED. Revision 1 above is kept unchanged as the record; **where revision 2 differs, revision 2 applies** (corrections listed in R2-3) |
| **Commission** | Principal DDD Architect, "Reference Geography Strategic Validation and Decision Readiness", 2026-11-09. Targeted validation only: no aggregates, events, schemas or code |
| **New evidence** | Constitution of Nepal 2015, English translation (Nepal Law Society, International IDEA, UNDP; constituteproject.org, generated 28 September 2022): Art. 56(3), Schedule 4, Art. 84, Art. 286, Art. 307. Kathmandu Post, 26 April 2017. Republica, 25 August 2017 (as reported in search results) |

## R2-1. Executive assessment

The direction of revision 1 holds: Reference Geography is a platform-level reference; recording is not establishing; codes are not identity; identity and succession are separate; both time dimensions apply. **Four parts of revision 1 were too simple:** the single status list, "one dated tree per country", "a unit may hold two levels", and the claim that option (a) costs nothing. They are corrected below. **The Nepal example was also wrong in substance** (R2-14). Geography is ready for tactical design **once Question 1 is ruled**; Question 2 can follow without blocking it.

## R2-2. Accepted decisions preserved

[AD] D-31; ratified Membership architecture with CR-01, D-73, D-77, INV-11; D-75; D-79; V-15, V-16, V-17, V-23, V-24, V-53, V-54; D-21. Open and not solved here: the V-23 admission–belonging contract (model document §47). §45 stays PROPOSED.

**[AD] D-80 (direct, 2026-11-09): "make sure that address of a person and committee structure is two different things. having address in District X does not mean, it is associated with the same committee."** Consequences, consistent with D-79:
- A person's address is a contact or residence fact (organisation-local, XD-3b). It may *reference* an official unit; it is never a belonging, never a link to a unit or committee, never a basis.
- A unit's coverage of District X says **nothing** about the people who live in District X.
- The committee of District X (an organ, Governance) and the District X unit (Organisation) are composed of people through belonging and positions only, never through addresses.
- Under D-79 an address may still produce a **suggestion**; it decides nothing.
- [E] Today's classification by residence (`SessionMemberGeoPathProvider.php:21-31`, `NearbyCommitteesQueryService.php:47-48`) contradicts D-80 as well as D-79.

## R2-3. Corrections to revision 1

| # | Revision 1 said | Correction |
|---|---|---|
| K-1 | Nawalparasi and Rukum "were each divided in 2017" | **Divided by Schedule 4 of the 2015 Constitution**, in force 20 September 2015; 2017 brought boundary adjustments and separate district administration (R2-14) |
| K-2 | 2015 "replaced 5 development regions and 14 zones with 7 provinces" (could read as a renaming) | A **constitutional restructuring of the state** into federal, provincial and local levels (Art. 56); not a renaming of a level |
| K-3 | One status list: official (machine) · official (documentary) · candidate · disputed · provisional | **Five separate dimensions** (R2-6). Machine-readable versus documentary is the *format* of evidence, not its authority |
| K-4 | "One dated administrative tree per country"; "a unit may hold more than one level" | **Dated schemes with classification, containment and equivalence kept apart** (R2-8) |
| K-5 | Two time dimensions | **Five dates; `τ` is only PublicDigit's record time** (R2-10) |
| K-6 | Option (a) recommended because "no business process depends on a provisional reference" | Not proven; tested against eight scenarios (R2-12) |
| K-7 | Question 2 suggested constituencies matter to Election | **PublicDigit's Election context runs organisations' own elections**; public constituencies matter to parties as organisational coverage (R2-13) |

## R2-4. Business vocabulary [I]

| Term | Meaning |
|---|---|
| **Official unit** | A territorial division established by a competent public authority (real world) |
| **Reference unit** | PublicDigit's record of one official unit, under a reference identity that is never reused |
| **Territorial scheme** | A system of divisions established by one authority framework for one purpose, valid for a period (Nepal's administrative scheme before 20 September 2015; the federal scheme since; a constituency scheme) |
| **Division class** | A named class within a scheme (province, district, municipality, ward). The class carries the meaning; a level number is only an order |
| **Containment** | "Unit X lies within unit Y" in one scheme, for a period |
| **Status equivalence** | "Unit X also has the status of class C" (for example a German district-free city: a municipality with district status) |
| **Territorial relation** | Between units of **different** schemes: within, overlaps, coincides |
| **Claim** | One statement about a unit (it exists, its name, code, class, containment, boundary, succession), with validity, evidence and record time |
| **Source** | A publication of an issuing body: legal act, gazette notice, official dataset, official register |
| **Competence** | The issuing body's legal power over a scheme, territory and period. **Competence makes a source authoritative, not format or website** |

## R2-5. Purpose and exclusions

Unchanged from revision 1 §3 and §11, plus D-80. [I]

## R2-6. Official standing, evidence and source authority [I] [R]

**Five dimensions, kept apart:**

| Dimension | Question | Values (business vocabulary, not an enum) |
|---|---|---|
| Official standing | Has a competent authority established this unit, as claimed? | established · not established · undetermined |
| Evidence | What supports the claim? | issuer and its competence; kind (legal act, gazette, dataset, register, secondary); format; how authenticity was checked |
| Verification | Has PublicDigit verified the claim? | submitted · under verification · verified · rejected |
| Conflict | Do authoritative claims contradict each other? | uncontested · resolved by precedence · unresolved |
| Publication | Do consumers see it? | published · withheld |

**Source rules:**
1. **Authority comes from competence.** For example: constitution or statute, then subordinate acts, then the competent agency's official dataset, then other government publications, then secondary sources.
2. **Authenticity:** obtained from the issuer's official channel, or a certified copy; the check is recorded.
3. **Several sources** may support one claim; all are recorded.
4. **Precedence:** a later act of equal or higher competence supersedes an earlier one; a primary act outranks a dataset that restates it.
5. **Correction or withdrawal** by a source is a **new claim** with its own record time; earlier knowledge stays.
6. **Two competent sources contradicting each other** with no precedence rule: the conflict is **unresolved**, both claims are recorded, consumers are told, and **the operator does not choose**.
7. **Continuity, boundary change and succession** need evidence that states them; similarity never proves them.

**"Official source, contradicting older source":** the claim with precedence is published as the established claim; the older claim is kept as *superseded by precedence*. Consumers see one established claim, not two equal ones. [R]

## R2-7. Identity, revision and succession [R]

Five different things: **identity continuity**, **boundary revision**, **containment revision**, **succession** (identity ends, successors named), and **correction of recorded knowledge**.

| # | Case | Identity | Recorded as |
|---|---|---|---|
| 1 | Rename, boundary unchanged | continues | name claim, dated |
| 2 | Code changes | continues | code claim; code is never identity |
| 3 | Old code reused for another unit | new identity | the code's history stays with the old unit |
| 4 | Moves to another parent | continues | containment revision |
| 5 | Boundary changes, identity continues | continues | boundary revision |
| 6 | Territory transferred between units | both continue | boundary revision on both, one evidence |
| 7 | Division A → B, C | A ends | succession A → {B, C} |
| 8 | Merger A, B → C | A, B end | succession {A, B} → C |
| 9 | Absorption A into B | A ends, B continues | succession A → B, B continuing |
| 10 | Source corrects a historical fact | unchanged | correction claim; validity may change, earlier knowledge kept |
| 11 | Sources disagree: rename or division? | **undetermined** | conflict unresolved; both claims kept; no identity decision by PublicDigit |

Fuzzy matching (T-4) **suggests** candidates only; it never establishes identity or succession.

**Nepal applied (R2-14):** Nawalparasi and Rukum end with the 2015 scheme change and are succeeded by four districts. The 75 other districts **continue** across the 2015 scheme change, because the constitution keeps them by name. The April 2017 transfers of former VDC areas between provinces are **boundary revisions** (case 6).

## R2-8. Schemes, classes and hierarchy [I] [R]

**Test of "one dated tree per country":**

| Situation | Example | One tree enough? |
|---|---|---|
| Scheme replaced | Nepal, 20 September 2015 | No: two schemes, with units continuing across them |
| Classes of one scheme start at different dates | Nepal: provinces from 2015; new local levels from 2017 (title-level report, R2-14) | Only if classes are dated |
| Concurrent schemes | Administrative districts and electoral constituencies (Art. 84, Art. 286) | No: different authority, purpose and lifecycle |
| Missing intermediate class | A unit directly under a unit two classes up | Yes, if containment may skip classes |
| Special status | German district-free city | Yes, as **status equivalence**, not as a second position in the tree |
| Overlap | A constituency crossing a district boundary (country-dependent; not verified for Nepal) | No: a territorial relation across schemes |

**Minimum business model:**
- Per country, **one or more dated territorial schemes**, each with its authority and purpose.
- Within a scheme: **dated division classes**; units **classified** into a class; **dated containment** that may skip classes; **status equivalence** where the law grants it.
- Between schemes: **territorial relations** only when evidenced (or derived from boundaries as a suggestion, R2-9).
- A unit's **identity can span schemes** when the source keeps it (the 75 continuing Nepal districts).
- **No universal ontology:** only what the country's own schemes state.
- [E] `GeoLevelType` (seven fixed names) and `admin_level` numbers cannot be the business meaning. Continents as level 0 are not official units (revision 1 §6).

## R2-9. Boundaries and geometry [I] [R]

| Question | Answer |
|---|---|
| Does Geography own **boundary facts** (that a boundary changed, a territory transferred, with evidence and dates)? | **Yes.** They are part of identity and succession (R2-7, cases 5–9) |
| Does Geography own **geometry** (polygons)? | **As optional reference data only:** attributed to its source, dated, with stated accuracy; several representations may coexist |
| Can identity or succession be derived from geometry? | **No** (R2-6 rule 7) |
| Which business uses need geometry? | Map display, address-to-unit **suggestion** (D-79, D-80), cross-scheme overlap **suggestion**. None decides anything |
| Recommendation | Geometry is **not required for any current capability**; if held, it is a source-attributed projection inside Geography, never authoritative for identity. No GIS subsystem now |

## R2-10. Time [R]

**Five dates, kept apart:**

| Date | Meaning | Use |
|---|---|---|
| Legal effective date | When the fact holds officially (as the evidence states) | `asOf` validity |
| Source publication date | When the source was issued | evidence |
| Receipt date | When PublicDigit obtained the evidence | evidence |
| Record time | When PublicDigit recorded the claim (database clock at commit, §38.2) | **`τ`** |
| Verification and publication | When the claim was verified and published to consumers (its own recorded event) | what consumers can see at `τ` |

**`τ` is never the source's publication date.** Consumers see, at `τ`, the claims **published** with record time ≤ `τ`. This is the CR-01 convention (`KnownAt` over facts recorded by `τ`), with no new temporal rule.

**Worked example.** District A is divided into B and C, effective 1 January, published and recorded 15 February. On 1 March the source corrects the effective date to 1 February, recorded on 1 March.

| Query | Result | Why |
|---|---|---|
| asOf 20 Jan, known 10 Feb | A | division not yet known |
| asOf 20 Jan, known 20 Feb | B, C | effective 1 Jan, known since 15 Feb |
| asOf 20 Feb, known 20 Feb | B, C | |
| asOf 20 Jan, known 5 Mar | **A** | corrected date 1 Feb, known since 1 Mar |
| asOf 20 Jan, known 20 Feb, **asked again after 1 March** | still B, C | earlier knowledge is never rewritten |

## R2-11. Geography–Organisation contract: business guarantees [R]

1. A reference identity is stable and never reused.
2. Every published claim carries validity, record time, evidence reference, verification state and conflict state.
3. Ends of identity always come with their successor set and evidence.
4. Schemes and classes are dated.
5. Any "as at (asOf, τ)" question gives the same answer whenever it is asked.
6. Changes are **announced**, never applied to a consumer's facts.
7. Consumers keep rebuildable copies; no table access.
8. **Organisation's side:** on an announcement its competent organ decides about its own coverage (D-21); a coverage decision keeps the reference identity and the `τ` at which it was decided; **belonging is never affected (D-79, D-80)**.

Membership–Organisation (V-23) remains a separate, open contract (R2-2).

## R2-12. Decision dossier: Question 1 [OQ]

**Question.** *Can PublicDigit create a usable geographical reference when official standing cannot be established from a competent source? If so, how must it be shown so that nobody mistakes it for an official fact?*

**What the testing found first:** the gap is **narrower** than revision 1 implied. A country with **no machine-readable dataset** is not a gap: its official units can be recorded from **documentary evidence** (R2-6). The gap is only where **no competent source exists at all** for an area someone wants to describe.

**The options:**
- **(a) No provisional references.** Reference Geography holds only claims with competent evidence; unsupported submissions stay candidates.
- **(b) Platform-wide provisional references.** An operator may publish a reference that is marked non-official, on evidence short of competence, and that every organisation can use.
- **(c) Organisation-local areas.** Reference Geography stays strictly official; an organisation may describe its own areas **inside Organisation, as its own facts** (for example "Tole Ganesh, as the party defines it"), optionally related to official units.

**(a) and (c) are not exclusive:** (c) concerns Organisation and can be combined with (a). The real choices are **(a) alone**, **(a) with (c)**, or **(b)**.

| # | Scenario | (a) alone | (a) + (c) | (b) |
|---|---|---|---|---|
| 1 | Country without machine-readable data | Documentary records; more manual work | Same | Same, plus provisional records where evidence is weak |
| 2 | Diaspora organisation not using Geography | Unaffected | Unaffected | Unaffected |
| 3 | Party needs an area no authority defines (tole, settlement, household cluster) | **Cannot describe it geographically** | Its own area in Organisation | A provisional platform record of something that is **never official**: the PD-3 mistake again |
| 4 | Official unit exists but evidence not yet obtained | Candidate; coverage pending; organisation works (D-79) | Same, or describe it locally meanwhile | Provisional record usable now |
| 5 | Provisional reference later conflicts with the official unit | — | Organisation decides whether to relate its area to the official unit | **Reconciliation:** consumers' coverage must be re-decided, and two records exist for one place |
| 6 | Two organisations describe the same non-official area differently | — | Each keeps its own description; no conflict | **Platform must choose one** or hold both, contradicting a single reference |
| 7 | Reporting, search, representation, elections | Official areas only; candidates flagged | Official areas plus each party's own areas, clearly separate | Mixed official and provisional; every report must distinguish them. **No option affects belonging, representation computed from belonging, or entitlement** (D-79, D-80, INV-11) |
| 8 | Effect of pending coverage | Geographic reporting, maps and suggestions incomplete; **no effect** on belonging, organs or elections | Same, mitigated by own areas | None |

**Boundaries and consumers:** (a) keeps Reference Geography single-meaning. (c) adds an Organisation concept (an organisation-defined area), within Organisation's existing ownership of its own territorial structure (D-31, V-22); it needs Organisation tactical design. (b) adds a second kind of reference that **every consumer** must handle forever.

**Extensibility:** (a) → (a)+(c) later is easy and touches Geography not at all. (a) → (b) later is possible. **(b) → (a) is hard to undo** once organisations depend on provisional records.

**Recommendation: (a) together with (c).**
- Reference Geography stays an official reference only: no second kind of geography for consumers to handle.
- Areas no authority defines are the party's own facts. They belong to Organisation, where two parties can describe the same place differently without conflict.
- Missing evidence for real official units is handled by documentary records and candidates.

**What your choice determines:**
- **(a) alone:** areas no authority defines cannot be described anywhere on the platform.
- **(a)+(c):** they can be, as the organisation's own facts. Organisation's tactical design must then include organisation-defined areas.
- **(b):** the platform itself publishes non-official geography and owns its reconciliation with official units.

## R2-13. Question 2: preliminary analysis (not asked yet)

| Division | Official? | Authority (example) | Own hierarchy or overlap | Lifecycle | Who gives it meaning in PublicDigit |
|---|---|---|---|---|---|
| Electoral constituencies | Yes | Nepal: Constituency Delimitation Commission (Art. 286(1)); 165 House of Representatives constituencies (Art. 84); "put to a review in every 20 years" (Art. 286(12)); "No question shall be raised in any court of law" (Art. 286(7)) | Own scheme; nesting in districts **not verified** | Independent of administrative units | **Organisation**, as coverage for party structures organised by constituency. **Not Election:** PublicDigit's Election runs organisations' own elections, not public ones |
| Judicial districts | Yes | Judiciary or statute | Often follows administrative units | Independent | No current PublicDigit use |
| Statistical divisions | Yes | Statistics office | Often follows administrative units, sometimes finer | Census cycles | Reporting only |
| Postal areas | Operational, not territorial administration | Postal operator | Overlaps freely | Operator-driven | Address suggestions only (D-80) |

**Preliminary view [R]:** if any is included, it is a **separate territorial scheme** in Reference Geography, never merged into the administrative scheme. Electoral constituencies are the only plausible need. **Question 2 does not block Question 1**; it is the next bounded decision.

## R2-14. Nepal: verification [E-source]

| Fact | Status | Source |
|---|---|---|
| The Constitution came into force on 20 September 2015 | **Verified** | Art. 307(2): "shall come into force from 20th day of the 9th month of the year 2015 A.D." |
| Three levels of state structure: federal, provincial, local | Verified | Art. 56(1) |
| Provinces "with the districts listed in Schedule-4" | Verified | Art. 56(3) |
| **Nawalparasi and Rukum divided by the constitution** | **Verified** | Schedule 4: Province No. 4 "(11) Nawalparasi (east of Bardaghat Susta)"; Province No. 5 "(1) Nawlparasi (west of Bardaghat Susta)", "(7) Rukum (Eastern part)"; Province No. 6 "(1) Rukum (Western part)" |
| Cabinet adjusted the areas on 26 April 2017 | Verified (press) | Kathmandu Post, 26 April 2017: 11 former VDCs of Rukum, including Sisne and Uttarganga, to Province No. 5; wards 6 and 7 of Triveni VDC of Nawalparasi made part of Binayi VDC under Province No. 4 |
| Separate district coordination committees, bringing the number to 77 | Reported (press, search result only) | Republica, 25 August 2017 |
| New local-level structure in force from 10 March 2017 | **Title only, not verified** | Himalayan Times headline (search result) |
| Previous structure of 5 development regions and 14 zones | From general knowledge; consistent with the reviewer's sources; **not checked against a primary text here** | — |

**Corrected wording:** *Nepal's 2015 Constitution, in force from 20 September 2015, restructured the state into federal, provincial and local levels and established seven provinces, replacing the development-region and zone structure. Its Schedule 4 divided Nawalparasi and Rukum between provinces, giving 77 districts. In 2017 the government adjusted the areas of the divided districts (26 April) and separate district administrations followed.*

**What this tests:** three candidate "effective dates" for one change (legal 2015, boundary adjustment April 2017, administration August 2017). The **legal effective date** governs official validity; the others are separate claims. A naive model that dates the division "2017" would answer historical questions about 2016 wrongly.

## R2-15. Remaining Principal decisions

1. **Question 1** (R2-12): now ready for a ruling.
2. **Question 2** (R2-13): after Question 1.

Everything else (source precedence per country, scheme representation, geometry handling, the contract's technical form) is architecture.

## R2-16. Conditions for entering tactical design

- Question 1 ruled.
- This revision accepted or corrected by the Principal.
- Question 2 may stay open: tactical design must then leave room for additional schemes, without modelling constituencies.
