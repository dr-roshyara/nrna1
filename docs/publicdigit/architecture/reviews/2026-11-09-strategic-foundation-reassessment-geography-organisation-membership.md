# Strategic DDD foundation reassessment: Geography, Organisation and Membership

| | |
|---|---|
| **Status** | Reassessment, PROPOSED. Decides nothing; no code changed; tests not executed |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Labels** | [AD] accepted decision · [E] implementation fact · [I] proposed business interpretation · [R] architectural recommendation · [OQ] unresolved Principal decision |
| **Effect on earlier proposals** | Organisation tactical design (model document §45) is **held, not withdrawn**: its level link and coverage identity depend on the Geography baseline below. §42–§44 stand, with the corrections in G |

## A. Executive assessment

**The three foundations are not equally reliable.**

| Context | Strategic foundation | Implementation |
|---|---|---|
| Membership | **Reliable: ratified** (2026-10-08 ratification, plus CR-01) | Incomplete: type history missing, voting flag on types, a timestamp defect |
| Organisation | **Mostly reliable:** decisions D-3, D-13 to D-22, D-27, D-29, D-30, D-79, V-series | Not implemented (structure lives in Membership as Committees) |
| Geography | **Weak:** only the boundary is decided (D-31); identity, revision and succession are open (R-10 Proposed, XD-5 Open) | Partly implemented and partly mis-described in my earlier reports (see G-1) |

**Correction needed most:** Geography's business model. Organisation's coverage and level links depend on what an official unit's identity and revision mean, and that is not yet decided.

## B. Geography (Reference Geography) baseline

**Purpose [AD D-31]:** a platform-level reference of official geographical units, their revisions, and the approval of submitted missing units. Shared by all organisations; owned by none of them.

**Authoritative facts [I]:** official unit (stable identity independent of name, code, parent and level), its country, its position in the country's official hierarchy, its validity periods, its revisions (rename, re-parent, divide, merge, retire) with **successor links**, and the **source** of each fact.

**Not authoritative [I]:** imported raw data (input), submitted candidates (pending until approved), materialised paths and caches (projections), any organisation's own regions or zones (Organisation).

**Levels [E][I]:**
- [E] `admin_level` is a number per country (0–10; "1=province/state, 2=district, 3=local level, 4=ward"); `GeoLevelType` is a fixed set of seven labels mapped to numbers per country (`GeoLevelMappingResolver`); `countries.admin_levels` holds per-country level names.
- [I] **A level is country-specific.** "Level 2" in Nepal and in Germany are different concepts; hierarchies may differ between countries. A level number only orders the units *within one country*.
- [I] The semantic type (province, district) is a **classification for display and search**, not identity.
- [R] Level 0 holds continents in the current code; continents are not official administrative units. **Supranational regions belong to Geography only as reference groupings (for example a recognised regional classification), never as official units;** an organisation's "Europe" is an Organisation unit covering countries.

**Exclusions [AD/I]:** Geography never decides an organisation's units, levels, coverage, belonging or electorate; it never changes another context's records when it revises.

## C. Organisation baseline

Unchanged in substance from §42–§44, with these refinements:
- [AD V-16, V-17, V-20, V-22] **Organisational levels are the organisation's own**, defined by the Highest Committee; wings have their own levels. A link to Geography is optional and is **per country** (follows from B: a geographic level means nothing without its country).
- [AD D-22, D-79, V-23, V-24] Belonging: one geographic member belonging in the geographic tree; ancestors derived; wing, staff, guest additional per rules; every belonging with a basis; individual acts by default.
- [AD V-23] **"Admitted into a specific unit; never without a unit".** A member therefore always has a geographic belonging from admission. Scenario 8 below ("member without belonging") is **excluded by an accepted decision**, not an open question.
- [I] Coverage references an official unit's **stable identity** plus the revision known when coverage was decided; it depends on G-2.

## D. Membership baseline

**Ratified [AD]** (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`): admission, renewal, lapse, end, suspension and restoration as restriction intervals, type change as **type segments** (`ChangeType`), corrections and annulments as separate facts, `IsMember`, `Suspended`, `CurrentType` as predicates, reinstatement as End plus a new Admit (INV-EP), **INV-11: no voting entitlement from Membership**, and CR-01 queries "as known at τ".

- [I] Membership types: the **catalogue** per organisation is Membership's; **who may define it** comes from the organisation's rules (Governance); **what a type means for voting** is Election's.
- [AD V-23] Admission names the unit; the belonging it creates is Organisation's fact (basis: the admission act).
- **Gaps are implementation gaps, not strategic ones** (G-4 to G-6).

## E. Context map (target)

```
Reference Geography ──(published official units, revisions, successors; R-10)──▶ Organisation
Identity ──(organisation-specific PersonRef; D-75)──▶ Membership, Organisation
Membership ──(coverage C1/C2, facts "as known at τ"; D-73, D-77)──▶ Organisation, Governance (C3), Election
Organisation ──(admission's unit is recorded as belonging basis)◀── Membership admission (V-23)
Governance ──(change sets, allocations, rule versions; R-1, R-15)──▶ Organisation
Organisation ──(structure and belonging as at; R-2, R-5)──▶ Governance, Election
Election: owns entitlement; reads Membership and Organisation at one shared τ
```

No context reads another's tables. [R] The admission-to-belonging hand-over is the one point where two contexts act on one business act; it needs an explicit contract (G-7).

## F. Binding decisions

D-3, D-13 to D-22, D-25, D-27, D-29 to D-31, D-72 to D-79; V-15, V-16, V-17, V-20 to V-24, V-53, V-54; the Membership ratification (including INV-11, INV-EP) and CR-01; D-75 (Identity). None is reinterpreted here.

## G. Gaps and contradictions

| # | Business problem | Evidence | Context | Kind | Recommended resolution |
|---|---|---|---|---|---|
| G-1 | **My earlier reports said Geography is "versioned". It is not.** The `version` column is optimistic locking ("increments on each write"), not a geographic revision | `Landlord/2026_05_11_000001_add_version_to_geo_administrative_units.php:20` | Geography | Correction of my own claim | Recorded here; conformance report row 16 overstated |
| G-2 | An official unit has no defined stable identity across rename, re-parent, division or merger; there are no succession links and no revision events | Columns `parent_id`, mutable `path`, `code`, `valid_from`/`valid_to`, `is_active`; no domain events, no successor concept in `app/Contexts/Geography/Domain` | Geography | Strategic gap | Define official-unit identity, revision and succession (R-10, XD-5) before Organisation coverage is finalised |
| G-3 | Who may approve an official unit or a change to one, and how sources are recorded, is undecided | D-31 names "approval of submitted missing units" but not the approver | Geography | **Business decision** | Principal decision 1 below |
| G-4 | Type history cannot be reconstructed | No type facts in `app/Contexts/Membership/Domain/Organisational/`; ratified `ChangeType` | Membership | Implementation gap | Implement as ratified |
| G-5 | A membership type decides voting | `membership_types.grants_voting_rights`, `app/Models/User.php:215` | Membership vs Election | Implementation contradicts INV-11 | Move the meaning to Election |
| G-6 | Record time taken before commit | `PostgresMemberRepository.php:219` | Membership | Implementation defect | Database clock at commit (§38.2 barrier) |
| G-7 | Admission into a unit (V-23) touches Membership and Organisation in one act | V-23; D-22 | Membership, Organisation | Technical architecture gap | One admission act; Organisation records the belonging from the published admission; eventual, with the admission as basis |
| G-8 | My Organisation design linked levels to `(countryCode, adminLevel)` because of the code | §45.2 | Organisation | Design derived from implementation | Re-derive from B once G-2 is settled; the per-country link stands on business grounds |
| G-9 | Continents stored as level 0 official units | `…extend_level_range_add_continents.php` | Geography | Implementation assumption | Reference groupings, not official units ([R] in B) |

## H. Principal decisions

**Decision 1 (Geography authority).** When an official geographical unit is missing or has changed, who may establish it on the platform?
- (a) only an imported official source;
- (b) the platform operator approves, with the source recorded;
- (c) an official source where one exists; otherwise the platform operator approves a submission, recording source, approver and evidence.

Consequences: (a) blocks organisations where no machine-readable source exists; (b) relies on the operator without requiring a source; (c) keeps the official character and still lets organisations proceed. **Recommendation: (c).** Undecided until ruled: approval of candidates, and the source attached to revisions.

No other Principal decision is required: the remaining questions are answered by accepted decisions or are architectural.

## I. Recommended tactical sequence

1. **Reference Geography first**: official-unit identity, revisions and succession, sources, the R-10 contract. Organisation's coverage depends on it.
2. **Membership**: no redesign; the ratified model stands. Only the admission-to-belonging contract (G-7) needs designing with Organisation.
3. **Organisation**: revalidate §45 against the Geography outcome and G-7, then submit for review.

## Scenario check

| # | Scenario | Outcome |
|---|---|---|
| 1 | Only some levels used | Organisation's own levels; links optional [AD V-16] |
| 2 | Same geographic level, different names | Names are Organisation's |
| 3 | Different hierarchies per country | Levels are per country [I] |
| 4 | Official unit divided or renamed | Geography revision with successors (G-2); Organisation reviews coverage; no belonging changes [AD D-21] |
| 5 | Central unit without coverage | Allowed |
| 6 | Unit covers several official units | Coverage set |
| 7 | Diaspora across countries | Coverage of several countries |
| 8 | Member without belonging | **Excluded by V-23** |
| 9 | Belongs, no position | Positions are Governance's |
| 10 | Moves residence | No change [AD D-79] |
| 11 | Authorised transfer | New basis; history kept |
| 12 | General → Active | Membership type segment [AD ratified] |
| 13 | Membership ends | Belonging stops counting; history kept [AD D-27] |
| 14 | Geographic plus wing | Two belongings, two trees [AD V-24, V-53] |
| 15 | Several positions | Governance [AD V-15] |
| 16 | Type change near cut-off | Membership facts at τ; Election decides meaning [AD INV-11, D-73] |
| 17 | Geography incomplete or unavailable | Unit established; coverage pending [I] |
| 18 | Concurrent belonging changes | One wins per person's belongings; the other re-evaluated [R] |
