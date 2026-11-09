# Organisation: implementation conformance investigation and finalisation proposal

| | |
|---|---|
| **Status** | Investigation report, PROPOSED. Decides nothing; no code changed; tests located, **not executed** |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Freshness** | No commit touched `app/` or `routes/` on 2026-11-09 and the working tree has no changes there, so the earlier investigation's findings still apply |
| **Builds on** | `2026-11-09-geography-organisation-relationship-investigation.md` (with addendum); model document `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` §42–§44 |

## Two premises of the commission already settled

- **Placement basis:** decided by the Principal as **D-79** (direct, 2026-11-09): organisation's rules decide; by default only individual acts create or change a belonging; addresses only suggest; a residence rule only if expressly adopted.
- **"One geographic member belonging"** means option **C** of the commission: one belonging in the **geographic tree** (the unit where the member sits; its parents are derived, not separate belongings), with **wing** belongings, **staff** and **guest** capacities additional under the organisation's rules, and **positions** separate (V-24 direct: "One geographic unit only; wings in addition"; V-53, V-54: organisation rules; V-15: several positions allowed).

## A. Executive conclusion

**The implementation does not yet support the intended model.** Geography is solid. Membership types exist per organisation. But organisational structure lives in Membership as "Committees", there is **no belonging record**, members are classified by residence, and organisation-specific levels exist only inside committee structures.

## B. What already works [E]

- Geography: units with levels 0–10 (`Landlord/2026_01_14_000003_extend_level_range_add_continents.php:34`), versions (`…/2026_05_11_000001_add_version_to_geo_administrative_units.php`), mirror, sync, candidates, fuzzy matching; per-country level names in `countries.admin_levels` (JSON; `CountryController.php:190` reads `level_name`).
- Committee structures per organisation with named levels, `geo_policy` and `geo_scope` (`Membership/…/2026_05_06_000001_create_committee_structures_tables.php:17-39`).
- Membership types **per organisation** (`database/migrations/2026_04_03_155706_create_membership_types_table.php`: `organisation_id`, `name`, `slug`, fee, `requires_approval`, unique per organisation).
- Ratified Membership aggregate with corrections and KnownAt (`app/Contexts/Membership/Domain/Organisational/`).

## C. What does not work or is missing (business consequences)

- **No place to record "Sita belongs to Ward 5".** Without a belonging record, transfers, history and electorates rest on addresses.
- **Diaspora chapters, wings and central bodies cannot be created** through the application (HTTP requires a geography unit).
- **A zone across districts or a chapter across countries cannot be represented** (one geography reference per committee).
- **Membership type history is not in the ratified aggregate:** no type facts in `Domain/Organisational/` (type segments, `CurrentType` and INV-11 are ratified but not implemented there). A type change near a cut-off cannot be reproduced "as known at τ".
- **Voting rights hang on the type** (`membership_types.grants_voting_rights`, read in `app/Models/User.php:215`): entitlement logic inside Membership, contrary to INV-11 ("no entitlement from Membership").
- **Committee as unit, organ and roles in one model:** Governance's positions and tenures are not implemented (no Position or Tenure classes in `app/Contexts/Governance`).

## D. Conformance matrix

| # | Capability | Intended owner | Current implementation | Evidence | Status | Required action |
|---|---|---|---|---|---|---|
| 1 | Geography levels 0–10 | Reference Geography | Implemented; `GeoLevelType` fixed to 7 named types | migrations above; `Domain/Enums/GeoLevelType.php` | PARTIALLY IMPLEMENTED | Keep; named types not extensible (C-a) |
| 2 | Organisation-specific level names | Organisation | Names on committee-structure levels (in Membership); per-country names in Geography | `committee_structures` levels; `countries.admin_levels` | IMPLEMENTED IN WRONG CONTEXT | OrganisationalLevel in Organisation |
| 3 | Selecting relevant geography levels | Organisation | `TenantGeographyProfile` (root/leaf levels) in Geography | `Geography/Domain/Models/TenantGeographyProfile.php` | IMPLEMENTED IN WRONG CONTEXT | Selection by Organisation's levels (optional mapping) |
| 4 | Units and hierarchy | Organisation | Committees with `parent_committee_id` in Membership | `committees` migration :72, :101 | IMPLEMENTED IN WRONG CONTEXT | Unit in Organisation (D-22) |
| 5 | Geographic coverage | Organisation | One `operational_geo_reference` per committee | `committees` migration :55 | PARTIALLY IMPLEMENTED | Coverage 0..n (§42.4) |
| 6 | Non-geographic units | Organisation | Domain allows; HTTP blocks | `StoreCommitteeRequest:37`; `CreateCommitteeRequest:21` | ARCHITECTURALLY INCONSISTENT | Remove geography requirement at entry points |
| 7 | Multiple coverage | Organisation | Not possible | `GeoReference` single path | NOT IMPLEMENTED | Coverage set |
| 8 | Membership type definitions | Membership (catalogue per organisation; who may define them per Governance rules) | `membership_types` per organisation | migration above | PARTIALLY IMPLEMENTED (legacy table, outside the ratified aggregate) | Align with ratified type model |
| 9 | Type assignment and history | Membership | Not in ratified aggregate | `Domain/Organisational/` | NOT IMPLEMENTED | Type segments as ratified |
| 10 | Member-to-unit belonging | Organisation | None; residence-based classification | `SessionMemberGeoPathProvider.php:21-31` | NOT IMPLEMENTED | PersonBelonging (D-22) |
| 11 | Belonging basis | Organisation | None | — | NOT IMPLEMENTED | Basis per D-22, D-79 |
| 12 | Multiple belongings | Organisation | None | — | NOT IMPLEMENTED | V-24, V-53, V-54 |
| 13 | Residence vs belonging | Organisation | Residence decides | `NearbyCommitteesQueryService.php:47-48` | ARCHITECTURALLY INCONSISTENT | D-79 |
| 14 | Organs, positions, tenures | Governance | Inside Membership's Committee (assignments, role limits) | `CommitteeAssignment` | IMPLEMENTED IN WRONG CONTEXT | Governance §20, §41 |
| 15 | Cross-context access | Contracts | Direct geography table reads; `members.residence_geo_unit_id` FK | investigation D4, D7 | ARCHITECTURALLY INCONSISTENT | R-10 copy and contracts |
| 16 | Historical correctness | Each owner | Geography versioned; committees cache; no belonging history | — | PARTIALLY IMPLEMENTED | D-13 to D-19 in Organisation |

## E. Target ownership

- **Reference Geography:** official units, levels, identities, revisions (D-31).
- **Organisation:** OrganisationalLevel (name; optional link to a geography level), units and trees, coverage 0..n, PersonBelonging with basis and history (D-3, D-22, D-79).
- **Membership:** membership lifecycle, **membership-type catalogue per organisation and type assignment with history** (ratified: type segments, `CurrentType`); no entitlement (INV-11).
- **Governance:** organs, positions, tenures, authority, delegations; **the rule that says who may define membership types and levels**.
- **Identity:** canonical Person and organisation-specific `PersonRef` (D-75).
- **Election:** what a type means for voting (entitlement), using Membership's facts at the shared τ.

## F. Modelling clarifications

- **Membership type ownership:** Membership owns both the catalogue and the assignment, because the type is a fact of the membership (ratified). Governance's rules decide who may define or change the catalogue; Election decides what a type means for voting.
- **Level naming:** an organisational level is Organisation's concept; linking it to a geography level is optional (V-16, V-17).
- **One versus several belongings:** one in the geographic tree (V-24); ancestors are derived; wings, staff, guests per rules; positions are not belongings.
- **Hierarchical belonging versus independent affiliation:** parents are derived from the tree; a wing belonging is an independent belonging in another tree.
- **Residence versus decision:** D-79.

## G. Scenarios (summary)

1 levels 0–4 only: Organisation defines four levels mapped to them. 2 different names: per-organisation levels. 3 renaming a label: Organisation change, Geography untouched. 4 central unit: no coverage. 5 district missing in Geography: unit created, coverage pending. 6 two districts: coverage of both. 7 diaspora across countries: coverage of several countries. 8 General → Active: new type segment in Membership, effective-dated. 9 residence move: suggestion only (D-79). 10 transfer: an act ends one belonging and starts another. 11 belongs without position: belonging only. 12 youth wing alongside: additional wing belonging (V-24, V-53). 13 official unit divided: coverage flagged; no belonging changes until a decision (D-21). 14 membership ends: member belonging stops counting, history kept (D-27). 15 type change near cut-off: answered "as known at τ" from type segments (D-73).

## H. Finalisation recommendation

1. Preserve Geography and the ratified Membership aggregate.
2. Add membership-type segments to the ratified aggregate as ratified; treat `grants_voting_rights` as a conformance defect (entitlement belongs to Election).
3. Move units, levels, coverage and belonging conceptually to Organisation; organs and positions to Governance.
4. Redesign the Committee model, which mixes unit, organ and roles.
5. New concepts: OrganisationalLevel, Coverage, PersonBelonging with basis.
6. Contracts: R-10 (geography copy), C1/C2 (coverage), R-1/R-2, R-5.

## I. Next step

**Tactical DDD design of Organisation** (OrganisationalLevel, Unit with coverage, PersonBelonging with basis under D-79), validated against Membership, Reference Geography, Governance and Identity. Migration design only after that.

## Principal Decisions Required

No Principal decision is required at this investigation stage. D-79 settles placement; V-24, V-53 and V-54 settle the number of belongings; the ratified Membership architecture settles type ownership.
