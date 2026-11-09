# Geography: cross-project comparison, nrna1 and public-digit

| | |
|---|---|
| **Status** | Discovery report, PROPOSED. Decides nothing. Not independently reviewed |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/`, beside the 2026-11-07 cross-project discovery |
| **Evidence limit** | public-digit's `packages/laravel-backend/` is **empty** in the local checkout (`/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/public-digit`). Its Geography **code** could not be compared; this report compares **public-digit's documents** with **nrna1's code and records** |

## 1. What each project has [E]

**nrna1** (`app/Contexts/Geography/`, 71 PHP files): landlord and tenant migrations (`geo_administrative_units`, admin levels extended to 0–10, a `version` column); `GeographyMirrorService`, `DailyGeographySync`, `GeographyAntiCorruptionLayer`, `GeographyCandidateService` (submitting missing units), `FuzzyMatchingService`, `TenantGeographyProfile`, `GeoPath`, `GeoReference`. Records: **D-31** (Reference Geography is a platform-level context owning official units, revisions and approval of submitted units); **R-10** (`OfficialUnitRevised`, `OfficialUnitsAsAt`; Organisation keeps a rebuildable copy); model document §15 (Geography excludes any organisation's structure); known code differences: `GeoAdministrativeUnit` with a fixed `parentId`, `GovernanceGeoUnitQueryService` reading tables directly.

**public-digit** (documents, Dec 2025): `architecture/backend/geography-context/` and `developer_guide/laravel-backend/geography-context/`.
- **Hybrid model:** landlord "golden source" plus a mirrored tenant copy, "Mirrored + Custom", with **custom levels 6–8 for party-specific units** (`…/20251231_0230_EXECUTIVE_DECISION_REQUIRED.md`).
- **A recorded contradiction** between "landlord only" (developer guide 2025-12-18, completion report 2025-12-21) and the hybrid specification (2025-12-19), which blocked Membership installation.
- **Resolution: optional geography** (`…/20251231_1100_FINAL_ARCHITECTURE_COMPLETE.md`, `…/20251231_1003_Business_case_geopgraphy.md`): Membership installs first and works without Geography; members carry nullable `province_id` / `district_id` **without foreign keys**, validated by the application; Geography is installed later per tenant and mirrors official units.
- **Fuzzy matching** for imports (`…/20251223_0030_implementation_of_fuzzy_method.md`); an Angular `packages/geo-location` front-end package (purpose not reviewed).

## 2. Relationship [I]

nrna1's Geography context **continues public-digit's design**: the same hybrid landlord/tenant mirror, fuzzy matching and installer job exist in nrna1's code. nrna1's records then **moved the boundary**: Geography became **Reference Geography** (official units only, D-31), and **organisational units belong to Organisation** (D-3, D-22), connected by R-10.

## 3. What to take from public-digit

| # | Item | Take? | Reason |
|---|---|---|---|
| T-1 | **Geography is optional; Membership never depends on it** | **Yes** | Matches the ratified Membership architecture (no geography in Membership) and the context map |
| T-2 | **No database foreign keys across contexts; validation at the boundary** | **Yes** | Matches "no shared persistence" and R-10's rebuildable copy |
| T-3 | **Official source plus tenant copy, installed per tenant** | **Yes, as already in nrna1** | It is R-10's pattern; the copy is a projection, never authoritative |
| T-4 | **Fuzzy matching of imported place names** | **Yes, within Reference Geography** | Supports candidate submission (`GeographyCandidateService`); matching never creates an official unit without approval (D-31) |
| T-5 | The recorded contradiction (landlord-only vs hybrid) | **Yes, as a lesson** | A boundary must be decided before implementation; nrna1 has done so (D-31) |

## 4. What not to take

| # | Item | Why not |
|---|---|---|
| N-1 | **Members holding `province_id` / `district_id`** | Conflicts with the ratified Membership architecture and with D-3: where a person belongs is **Organisation's** belonging, not a Membership attribute |
| N-2 | **Custom levels 6–8 for party units inside the geography table** | Conflicts with D-31 and §15: party structure (wards, branches, committees) is **Organisation's** tree, linked to official units, never stored as geography |
| N-3 | Landlord-only reads from tenants | Conflicts with R-10 (each context keeps its own rebuildable copy) |

## 5. Gaps found in nrna1 (code conformance, no action now)

- `GovernanceGeoUnitQueryService` reads geography tables directly (contradicts R-10).
- Tenant migrations allow levels up to 10 for custom units, which is the N-2 pattern still present in code.
- `GeoAdministrativeUnit` has a fixed `parentId`, while D-13 to D-19 need structure over time (that applies to Organisation's tree, not to official units, which change by revision).

## 6. Open

- Obtain public-digit's Laravel backend (`packages/laravel-backend/`) to compare code, not only documents.
- Review the Angular `geo-location` package's purpose.
