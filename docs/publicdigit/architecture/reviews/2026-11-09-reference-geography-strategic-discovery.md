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
