# Investigation: the existing Geography–Organisation relationship

| | |
|---|---|
| **Status** | Investigation report, PROPOSED. Decides nothing; no code changed. Tests were located, **not executed** |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Scope inspected** | `app/Contexts/Geography/`, `app/Contexts/Membership/Domain/Committee/`, Membership tenant migrations, `app/Http/Requests/Committee/`, `app/Http/Controllers/Committee/`, `app/Contexts/Membership/Application/Committee/Services/`, `app/Contexts/Membership/Infrastructure/{Ports,Query}/`; public-digit `architecture/backend/{geography-context,membership-context/ddd_connect_geographical_contexts}/` (documents only; its backend code is not present locally) |

## 1. Executive conclusion

**There is no implemented Organisation context.** The organisation's hierarchy is implemented as **Committees inside the Membership context**. The domain model of committees can represent non-geographic units (central, wings, diaspora), but **the HTTP path that creates committees requires an existing Geography unit for every committee type**, and **members are classified into committees through their residence geography**. In practice, Organisation-like structure **depends on Geography** today. This differs from the accepted design (D-3, D-22, D-31), in which Organisation owns units and belonging and Geography is official reference data only.

## 2. Geography, as implemented [E]

- `geo_administrative_units` in **landlord** and **tenant** databases (`Infrastructure/Database/Migrations/{Landlord,Tenant}/`); admin levels extended to 0–10 (`2026_01_14_000001_extend_admin_level_range_to_0_10.php`); a `version` column (`2026_05_11_000001_add_version_to_geo_administrative_units.php`).
- Mirror and sync (`Application/Services/GeographyMirrorService.php`, `DailyGeographySync.php`), candidate submission (`GeographyCandidateService.php`), fuzzy matching (`Domain/Services/FuzzyMatchingService.php`), paths (`Domain/ValueObjects/GeoPath.php`), anti-corruption layer (`Application/Services/GeographyAntiCorruptionLayer.php`), tenant profile (`Domain/Models/TenantGeographyProfile.php`).
- public-digit documents describe the same **hybrid** model: landlord golden source, tenant copy "Mirrored + Custom" with **custom levels 6–8 for party-specific units** (`geography-context/how_hybrind_geography_concept_works.md`).

## 3. Organisational structure, as implemented [E]

| Element | Where | Behaviour |
|---|---|---|
| **Committee** (aggregate) | `app/Contexts/Membership/Domain/Committee/Committee.php` | Holds type, name, code, `?GeoReference $operationalGeo`, structure, level index and name, `GeoPolicy`, `GeoScope`, `geoUnitId`, assignments |
| **Types (closed set)** | `CommitteeType` | `central`, `province`, `district`, `ward`, `youth_wing`, `women_wing`, `student_wing`, `diaspora`, `geographic` |
| **Rules in the aggregate** | `Committee::form()` lines ~137–150; `createCentral()`; `createForGeography()` | Central **must not** have geography; geographic types **must** have it; structure may require it |
| **Configurable levels** | `CommitteeLevel.php`, `committee_structures` / levels table (`2026_05_06_000001_create_committee_structures_tables.php`) | Organisation defines levels 1–10, each with `geo_policy` = `none` / `required` / `optional` and an optional `geo_scope` |
| **Hierarchy** | `committees.parent_committee_id` (self-referencing FK, `2026_01_16_000002_create_committees_table.php:72,101`) | Parent–child between committees |
| **Geography reference** | `committees.operational_geo_reference` (string path, e.g. `np.3.15.234`; **no FK**), `operational_geo_cache` (JSONB), `geo_cache_version`; plus `geo_unit_id` | One reference per committee |
| **Person ↔ committee** | `CommitteeAssignment`, `committee_assignments` | Role assignments, not a general belonging |
| **Organisation context as designed (Unit, PersonBelonging, change sets)** | — | **Not found in inspected implementation** (no `app/Contexts/Organisation`) |

## 4. Dependencies and data flow

```
HTTP StoreCommitteeRequest ──(geoUnitId required, exists:geo_administrative_units)──► Geography table   [hard DB dependency]
Committee aggregate ──GeoContextPort──► GeographyContextAdapter (stub: permits everything)              [no real validation]
NearbyCommitteesQueryService ──JOIN geo_administrative_units ON committees.geo_unit_id──► path prefix    [direct table read]
members.residence_geo_unit_id ──FK──► tenant geo_administrative_units                                    [Membership → Geography FK]
SessionMemberGeoPathProvider ──users.residence_geo_unit_id──► geo path ──► committees by path           [classification by address]
Governance: GovernanceGeoUnitQueryService ──direct read──► geo tables
```

## 5. Scenarios

| # | Scenario | Result in the implementation | Evidence |
|---|---|---|---|
| 1 | District committee for an official district | Request needs an existing `geoUnitId`; `createForGeography()` needs a `GeoReference`; the port does **not** check the path against Geography | `StoreCommitteeRequest.php:37`; `Committee.php:885…`; `GeographyContextAdapter.php:22-26` ("Phase C: Permissive - allow all references") |
| 2 | Non-geographic unit (diaspora, wing, internal committee) | **Domain allows it** (types exist; level `geo_policy = none`). **The HTTP request blocks it**: `geoUnitId` is required for every type, so a central or diaspora committee cannot be created through this path without a geography unit, contradicting the aggregate's own rule for central committees | `StoreCommitteeRequest.php:35,37`; `Committee.php:138-143` |
| 3 | Province missing from Geography | **Blocked** at the request (`exists:` fails). The only route is to add the unit to Geography (candidate submission, D-31 approval) | `StoreCommitteeRequest.php:37`; `GeographyCandidateService.php` |
| 4 | Official district divided or renamed | Geography has versions and sync. Committees keep their own reference plus a versioned cache. **Not found** whether sync refreshes committee caches or whether any organisational decision is required; no change-set mechanism (D-30) is implemented. Belonging is not moved automatically, but committee references can go stale [I] | migrations above; `DailyGeographySync.php` |
| 5 | Member's higher classification | Derived from **residence geography**: the member's `residence_geo_unit_id` gives a path, and committees are found by path prefix. **Not** from an organisational belonging act | `SessionMemberGeoPathProvider.php:21-31`; `NearbyCommitteesQueryService.php:17,47-48` |
| 6 | Zone across several districts; diaspora across countries | **Not representable**: one committee has one `GeoReference`, which is a single path within one country. A diaspora type exists but cannot cover several countries | `Committee.php` (`?GeoReference $operationalGeo`); `GeoReference.php` (`country.level…` pattern) |

## 6. Ownership: actual versus accepted

| Fact | Actual owner in code | Accepted owner |
|---|---|---|
| Official province, district, their parents | Geography | Reference Geography (D-31) ✓ |
| Party province or district committee | **Membership** (Committee) | **Organisation** (D-3, D-22) |
| Party hierarchy | **Membership** (`parent_committee_id`, structure levels) | Organisation |
| Territory a committee covers | Committee's single geo reference | Organisation unit linked to official units (R-10), possibly several |
| A member's organisational belonging | **Residence geography** plus committee role assignments | Organisation's PersonBelonging, by act or rule (open question below) |
| A member's residence | `members` / `users.residence_geo_unit_id` (FK to Geography) | Organisation-local person profile (D-75 §2a; owner per INV-AFF-1) |

## 7. Strengths and weaknesses

**Strengths (preserve):** configurable levels with `geo_policy` none/required/optional; types for central, wings and diaspora; domain rules for central versus geographic committees; natural-key geo reference with cache instead of an FK in `committees`; a port between Membership and Geography; Geography's versioning, mirror, candidates and fuzzy matching.

**Weaknesses (demonstrated):**
- D1. No Organisation context; organisational structure sits in Membership, outside its ratified boundary (the ratified act's Membership owns membership truth only).
- D2. The HTTP request requires a geography unit for **every** committee type (`StoreCommitteeRequest.php:37`), contradicting the aggregate.
- D3. The Geography port is a permissive stub; the real check is a database `exists:` in the request.
- D4. `members.residence_geo_unit_id` is a **database FK** from Membership to Geography.
- D5. Member classification comes from residence geography, with no organisational act (risk 6 in the commission).
- D6. One geo reference per committee: zones across districts and multi-country diaspora units cannot be represented.
- D7. Direct reads of geography tables from Membership services, controllers and Governance.

**Commission risks 1–10:** 1 yes (through HTTP); 2 partly (committee levels mirror geography levels); 3 partly (committees by path prefix); 4 not found; 5 not found as an automatic move, stale caches possible; 6 yes; 7 partly (diaspora type exists, not multi-country); 8 yes (geo ids and paths across contexts); 9 possible [I]; 10 not found.

## 8. Confirmed defects versus open questions

- **Confirmed (tactical, code):** D2, D3, D4, D7.
- **Confirmed (strategic, boundary):** D1, D5, D6 against D-3, D-22, D-31 and the ratified Membership act.
- **Open:** whether sync refreshes committee caches; other creation paths besides the inspected request; how Committee will map onto the designed Organisation Unit.

## 9. Recommended next architectural step

A **migration design** from the Committee model in Membership to the accepted Organisation model (Unit, PersonBelonging, change sets, official-unit links through R-10), preserving what works (configurable levels, `geo_policy`, wings, diaspora) and correcting D2–D7. It depends on the open Principal decision below.

## Principal Decisions Required

**One decision is already pending (Organisation question 1):** is a member placed in a unit **by an act**, **automatically from residence**, or **by the organisation's rules with placement by act and an address-based suggestion as default**? The current code does the second (D5); the accepted design does not settle it. All other findings are technical or follow from accepted decisions.
