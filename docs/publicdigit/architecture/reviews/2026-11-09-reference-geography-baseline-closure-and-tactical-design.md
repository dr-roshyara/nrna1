# Reference Geography: strategic baseline closure and tactical design (proposed)

| | |
|---|---|
| **Status** | PROPOSED. Architecture document; no code, migrations, schemas, APIs or tickets. **Uncommitted** pending the Principal's approval |
| **Commission** | "Integrated Strategic Baseline Closure and Tactical DDD Design Validation" (Principal DDD Architect; accepted by the Principal), **scoped by the Principal's instruction: "Focus on how we can implement Geographic Context correctly"** |
| **Builds on** | `2026-11-09-reference-geography-strategic-discovery.md` (revisions 1, 2); `2026-11-09-reference-geography-level-framework-strategic-discovery.md` (§1–§18, Addenda A, B) |
| **Conditional on** | Rulings still open: **L-1** (recommended B), L-2 (recommended ii), L-3 (recommended i), Question 1 (recommended a + c). Each design element states which ruling it depends on (§B.9) |
| **Categories** | [AD] accepted decision · [BF] verified business fact (cited) · [E] verified implementation fact · [SI] strategic interpretation · [R] architectural recommendation · [TD] proposed tactical design · [OQ] open Principal decision · [TU] unresolved technical uncertainty |

## 0. Disagreements (stated first)

1. **One integrated closure of all sixteen contexts in one pass would be shallow.** Each context needs the invariant-by-invariant proof the commission asks for (§7 of the commission). **This document closes Geography completely**, as the Principal asked, and records the other contexts' status by reference (§A.6). They follow one at a time, with the same method.
2. **The commission asks for tactical design while L-1 to L-3 are open.** It also says "do not freeze contracts dependent on unresolved geographical semantics". **Resolution:** the tactical design is written so that **only one element, the country profile (`CountryScheme`), depends on L-1**. L-2 and L-3 affect which data is held, not the model's structure. The design can therefore be reviewed now and frozen after the rulings.
3. **"Implement Geography correctly" cannot mean repairing the current code in place.** Its central concepts are wrong:
   - units are mutated in place, without history;
   - each organisation's copy re-numbers units, so identity is lost;
   - party units are stored as geography;
   - six level numberings coexist.

   **Most supporting code can be kept** (§C.2). **The core model has to be replaced**, not patched.

---

# Part A: strategic baseline (Geography)

## A.1 Purpose [SI]

Reference Geography is PublicDigit's **trusted atlas**: which official places exist, how they are officially arranged, how that changed over time, and the evidence for every fact. **Its job is to keep that atlas trustworthy. It establishes no facts of its own.**

## A.2 Ownership

| | |
|---|---|
| **Owns facts** | Canonical reference identities; claims about units (existence, names, codes, classification, containment, boundaries, end, succession) with validity, evidence and record time; country profiles (dated positions 3–10); the global levels 0–2; source registry and competence; candidates; crosswalks of external identifiers |
| **Owns decisions** | Whether evidence verifies a claim; precedence between competing claims under recorded precedence rules; publishing a verified claim; accepting or rejecting a candidate (D-31: "approval of submitted missing units") |
| **Consumes** | Evidence from external competent sources only |
| **Never owns** | Organisational units, levels, coverage, belonging (D-3, D-22, V-16, V-22); addresses (D-75 via XD-3b §2a); electorates or entitlement; **anything a party defines** (toles, zones, Hessen-Rheinland-Pfalz) |

## A.3 Strategic rules (invariants at business level)

| # | Rule | Basis |
|---|---|---|
| G-1 | A reference identity is never reused and never derived from a code or name | [SI], revision 2 R2-7 |
| G-2 | Recording is not establishing: every published claim has evidence from a competent source | [SI], revision 2 §4, R2-6 |
| G-3 | History is append-only: a correction is a new claim; earlier knowledge stays answerable | [AD] D-73 / CR-01 |
| G-4 | Within one scheme and date, containment is acyclic and **positions strictly increase** along it | [SI], Addendum B.2 |
| G-5 | An identity that ends by division, merger or absorption names its successors | [SI], R2-7 |
| G-6 | Within one country and date, **no position has two meanings**; addresses and committees read the same profile | [SI], Addendum B.2 |
| G-7 | Geography never changes another context's facts; it announces | [AD] D-79, D-80, D-21; R-10 |
| G-8 | Queries are answered as at (asOf, τ) with a `closedThrough` guarantee; beyond it, refused | [AD] D-73 |

## A.4 Context relationships

| Relationship | Pattern | Source fact | Consumer entitled to | Corrections | Source unavailable | Rebuildable |
|---|---|---|---|---|---|---|
| External sources → Geography | Anti-corruption layer | Datasets, legal acts, gazettes | — | New source versions are new evidence | Imports wait | — |
| Geography → Organisation | **Open host service, published language** (not a shared kernel) | Published claims, profiles, successor sets | Units as at (asOf, τ); revision announcements | Announced as new revisions; **coverage reviewed by the organ (D-21)** | Organisation works on its copy; coverage changes wait | **Yes**, from Geography's log |
| Geography → address owner | Same | Same plus place resolution | Resolution results with state and depth | Re-resolution is a new claim by the address owner | Addresses recorded unresolved; never guessed | Yes |
| Geography → Election (through Organisation) | Indirect | — | **None directly**; electorates use Organisation's belonging (D-79, D-80) | — | — | — |
| Organisation → Geography | Customer–supplier | Candidate submissions (D-31) | Candidate status | — | Submission queued | — |

**BR-5 applied:** a consumer deciding a constitutional matter (Election cut-off, Adjudication) reads Geography knowledge **at the operation's single `τ`** (§B.6). It may use its copy only if the copy is complete through that `τ`.

## A.5 Remaining strategic questions

L-1, L-2, L-3, Question 1 (one at a time, §C.5); Question 2 (constituencies) later, not blocking.

## A.6 Other contexts in this pass [SI]

Not re-validated here. Their current authority: Membership (ratified 2026-10-08, CR-01, D-73, D-77); Organisation (D-3, D-22, D-79, D-80; §45 PROPOSED and held); Governance (D-4, D-5, D-65, D-68 to D-72, D-74, D-76, D-78; tactical rounds 4–6 PROPOSED); Identity (D-75). Each needs the same closure.

---

# Part B: tactical design (Geography) [TD]

## B.1 Derivation: invariants first, then boundaries

| Invariant | Operation that could violate it | Boundary that prevents it |
|---|---|---|
| G-1 identity never reused | Minting a unit from a reused code; merging duplicates | `ReferenceUnit` identity is minted once, opaque; duplicates are resolved by a **same-as claim** that retires one identity (never deleted, never reused) |
| G-2 evidence for every published claim | Publishing an unverified claim | `ReferenceUnit` accepts claims only with an evidence reference; publication requires the verified state |
| G-3 append-only | Editing a name or parent in place | No update operation exists on claims; corrections are new claims |
| G-4 containment order and acyclicity | A re-parenting or division creating a cycle or a decreasing position | **`Revision` applied under the `CountryScheme` version** (one local transaction, optimistic version check), the pattern of D-22 option C |
| G-5 successors named | Ending a unit without successors | `Revision` of kind division, merger or absorption is invalid without its successor set |
| G-6 one meaning per position | Two classes at one position with different meanings in one country | `CountryScheme` owns the profile: each class has exactly one position per validity period |
| G-8 completeness | A consumer reading a half-applied revision | Revisions are atomic; `closedThrough` advances only past committed revisions |

## B.2 Aggregate catalogue

| Aggregate | Business concept protected | Why a consistency boundary | Owns | References |
|---|---|---|---|---|
| **`CountryScheme`** (per country × scheme) | The country's dated profile: classes, their positions 3–10, validity; **and the scheme version** | G-6 within itself; serialises structural revisions (G-4) | Classes, positions, status-equivalence rules, scheme version | Country unit |
| **`ReferenceUnit`** (per identity) | One official unit's identity and its claims | G-1, G-2, G-3 for one unit; small; frequent reads, rare writes | Identity; claims (name, code, identifier, classification, containment, boundary, end, same-as), each with validity, evidence, verification and conflict state | Its container(s) by identity; evidence; sources |
| **`Revision`** (per evidenced official change) | One official change, applied atomically | G-4, G-5 across several units | Kind (establish, rename, re-parent, boundary change, divide, merge, absorb, end, correct), effective date, evidence, affected units, successor sets | Units, scheme version |
| **`Candidate`** (per submission) | A submitted missing or changed unit (D-31) | Own lifecycle: submitted → under verification → verified (becomes a `Revision`) or rejected | Submitter (organisation reference), description, evidence offered, decision | — |
| **`Source`** (per issuing source) | Who may establish what | Competence and precedence must be consistent for every claim citing it | Issuer, competence (scheme, territory, period), precedence rank, authenticity method | — |

**Not aggregates:**
- **Evidence references** are value objects on claims.
- **Crosswalks** are identifier claims on `ReferenceUnit`.
- **Hierarchy, paths and "units at level n"** are **read models**.
- **Fuzzy matching** is a domain service whose output is **suggestions only**.

**Global levels 0–2** [depends on L-3]: World is one `ReferenceUnit`. Regions (level 1) come from a **global regional scheme** (`CountryScheme` with country = world, source e.g. UN M49). Countries are `ReferenceUnit`s classified at 2 and members of a region in that scheme. **The administrative scheme of each country starts at its country unit.**

## B.3 Value objects and entities

| Name | Kind | Meaning |
|---|---|---|
| `ReferenceUnitId` | VO | Opaque, minted by Geography; **never a code, never a database row id of a copy** |
| `Claim` | Entity inside `ReferenceUnit` | Statement + `ValidityPeriod` + `EvidenceRef` + `VerificationState` + `ConflictState` + `recordedAt` |
| `ValidityPeriod` | VO | Legal effective from / until (`asOf` side) |
| `EvidenceRef` | VO | Source, document locator, publication date, receipt date, authenticity check |
| `ClassPosition` | VO | (scheme, class, position 0–10, validity) |
| `SuccessorSet` | VO | Ended identity → one or more successor identities, with continuing flag (absorption) |
| `ResolutionResult` | VO (published language) | State (resolved, partially resolved, ambiguous, unresolved), deepest unit(s), position reached, candidates, Geography knowledge used (`τ`, log position) |
| `LocalizedName` | VO | Kept from current code |

## B.4 Commands and lifecycles

| Aggregate | Commands | Lifecycle |
|---|---|---|
| `Source` | RegisterSource, ChangeCompetence | active → retired |
| `CountryScheme` | DefineClass, AssignPosition, RetireClass (each dated, each evidenced) | versioned; never deleted |
| `Revision` | ProposeRevision, VerifyRevision, ApplyRevision, RejectRevision | proposed → verified → **applied** (atomic) / rejected |
| `ReferenceUnit` | (through applied revisions) AddClaim, MarkConflict, ResolveConflictByPrecedence, DeclareSameAs | established → (ended, with successors) or (retired as same-as) |
| `Candidate` | Submit, StartVerification, Accept (→ ProposeRevision), Reject | submitted → under verification → accepted / rejected |

**Verification is an accountable act:** an operator verifies **evidence**, recorded with the actor and time; the operator never creates official standing (G-2).

## B.5 Domain events and integration contracts (business content, not payloads)

| Contract | Meaning | Ordering and identity | Idempotency | Corrections |
|---|---|---|---|---|
| **Revision published** | An applied revision: affected identities, kind, effective date, successor sets, evidence references | Per scheme, by Geography log position; ordered | Keyed by revision identity; re-delivery harmless | A correction is a new revision of kind *correct* |
| **Profile changed** | A class gained or changed its position, from a date | Per scheme log | Same | Same |
| **Candidate decided** | Accepted (with the resulting identity) or rejected | Per candidate | Same | — |
| **Query: units as at (country, asOf, τ)** | Published claims valid at `asOf`, known at `τ` | — | Pure read | Answer stable for a given (asOf, τ) |
| **Query: resolve place (text or identifier, country, asOf, τ)** | `ResolutionResult` | — | Pure read | Never overstates precision |
| **closedThrough** | Every Geography fact with `recordedAt ≤ closedThrough` is committed and visible | Monotone | — | Queries beyond it refused (CR-01 §3) |

Delivery: at least once; consumers idempotent; consumers' copies **rebuildable from the log**.

## B.6 Transactions, consistency and time

- **Writes are rare and batched** (imports, verified revisions). **One `Revision` = one local transaction**: it checks the `CountryScheme` version and appends claims to the affected `ReferenceUnit`s.
- **Concurrency:** two revisions on one scheme serialise on the scheme version; the loser is re-evaluated (in the same way as two concurrent transfers in Organisation, §45 scenario 17).
- **`recordedAt` comes from the database clock at commit**, under the barrier pattern of model document §38.2:
  - writers take a shared per-scheme lock;
  - advancing `closedThrough` takes an exclusive lock.
  - **This avoids the Membership defect** (`PostgresMemberRepository.php:219` takes the time before commit).
- **One `τ` per multi-context operation** (D-73): an electorate cut-off reads Organisation belonging and Organisation's Geography copy at the same `τ`. The copy must be complete through `τ`, or the query is refused.
- **Five dates** stay separate (revision 2 R2-10). Publication, receipt and verification dates are evidence attributes; only `recordedAt` is compared with `τ`.

## B.7 Read models and projections

- **Hierarchy projection** per scheme and date: a materialised path or `ltree` is allowed **as a projection**, rebuilt from claims. It is never identity (today's `GeoPath` becomes this).
- **"Units at position n"**, cascading selectors, search indexes: projections.
- **Consumer copies** (Organisation; the address owner): keyed by `ReferenceUnitId`, rebuilt from "revision published" plus "units as at". **No consumer re-numbers units.**

## B.8 Correction and historical behaviour (worked examples)

| Case | What happens |
|---|---|
| District A divided into B and C, effective 1 January, recorded 15 February | One `Revision` (divide): A ends 1 January, successors {B, C}. As at 20 January: known 10 February → A; known 20 February → B, C (R2-10) |
| Source corrects the effective date to 1 February (recorded 1 March) | New `Revision` (correct). Known 5 March → A for 20 January; known 20 February still → B, C |
| Two records found to be one place | `DeclareSameAs`: one identity retired, pointing to the other; consumers told; neither reused |
| Nepal, 20 September 2015 | `CountryScheme` NP: provinces from 2015; development regions and zones end. Schedule 4: Nawalparasi and Rukum end, four successors. The other 75 districts **continue** |
| Rheinland-Pfalz, 1 January 2000 | `CountryScheme` DE: position 4 has no units in Rheinland-Pfalz from that date (Regierungsbezirke dissolved); Kreise keep position 5 |
| Japan's gun | Address-related class without administrative function [BF, MIC]; position per JP profile [depends on L-2] |
| "Mainz-Amöneburg" | Resolve by name → **ambiguous** (Mainz, Rheinland-Pfalz, versus Wiesbaden, Hessen); never guessed |
| NRNA Hessen-Rheinland-Pfalz | Geography holds Hessen and Rheinland-Pfalz only; the committee is Organisation's; coverage references both identities |

## B.9 What depends on which ruling

| Element | L-1 | L-2 | L-3 | Q1 |
|---|---|---|---|---|
| `CountryScheme` positions | **Structure depends on it** (B: per country; A: one global table; C: no profile, depth computed) | Whether Street has a position marked *address-only* | — | — |
| `ReferenceUnit`, `Revision`, `Source`, claims, time | Unaffected | (i) adds street units (containment "lies partly in") | — | — |
| Global regions scheme | — | — | **Which source** | — |
| `Candidate`; consumer areas | — | — | — | (b) would add a *provisional* claim state; (a)+(c) adds nothing here (areas are Organisation's) |

---

# Part C: validation and implementation

## C.1 Scenario results

| Scenario | Result |
|---|---|
| NRNA Hessen-Rheinland-Pfalz (address in Wiesbaden; coverage Hessen + Rheinland-Pfalz; belonging by act) | **Passes:** three separate facts, three owners; no new geographical unit |
| Two organisations reference one Hessen | **Passes:** one `ReferenceUnitId` |
| Division with late knowledge; later correction | **Passes** (B.8) |
| Nepal 2015; Rheinland-Pfalz 2000 | **Passes** under L-1 B |
| Ambiguous address | **Passes:** ambiguous state, no guess |
| Party unit (tole, zone) offered to Geography | **Rejected by design:** Organisation's (D-31) |
| Concurrent revisions on one country | **Passes:** scheme version serialises |
| Election cut-off with a pending division | **Passes if** Organisation's copy is complete through `τ`; otherwise refused |

**Contradictions found:** none in the target model. **Missing invariants found and added:** same-as retirement (G-1); profile meaning per position (G-6); a rule that consumer copies never re-number units (§B.7).

## C.2 Implementation conformance (current code → target) [E]

| Current | Verdict | Target |
|---|---|---|
| `Domain/Models/GeoAdministrativeUnit.php` (Eloquent in the domain layer, `extends Model`) | **Wrong layer** (project rule: domain has no Laravel) | Eloquent belongs in Infrastructure; the domain model is `ReferenceUnit` |
| `Domain/Entities/GeoAdministrativeUnit.php` with `changeParent`, `updateName`, `updateOfficialCode` | **Wrong model:** overwrites history (G-3) | Claims appended through revisions |
| Two models of one concept (Models and Entities) | **Duplicate** | One |
| `GeographyLevelClassifier`, `GeographyAntiCorruptionLayer::determineAdminType`, `GeoLevelType`, migration comments (six numberings) | **Contradictory** | `CountryScheme` profiles |
| `unique(['country_code','code'])`; `version` column as optimistic lock | Code treated as identity; no revisions | Identifier claims; `Revision` |
| Continents as level 0 | Contradicts [PS] 0 = World | World 0, regions 1 (L-3) |
| `GeographyMirrorService` copying into each organisation's database **with new row ids** (`landlord_geo_id` as the link) | **Identity lost per organisation** | Consumer copies keyed by `ReferenceUnitId` |
| Tenant table with party units, levels 6–8, `is_official = false` (`Tenant/2025_01_01_000001_…:16, 70`) | **Wrong context** (D-31) | Organisation units or areas |
| `DailyGeographySync` pushing into organisations' databases | Geography writing consumers' data (G-7) | Consumers pull from the log, or receive announcements |
| `GeographyCandidateService::submitMissingGeography(array)` | Concept right; arrays in the application layer break the project rule | `Candidate` aggregate; DTO command |
| `FuzzyMatchingService`, `PotentialMatches`, `SimilarityScore` | **Keep** | Suggestions only (resolution) |
| `LocalizedName`, `CountryCode`, `GeoUnitId` | **Keep** (`GeoUnitId` becomes `ReferenceUnitId`, opaque) | — |
| `GeoHierarchyProjectionBuilder`, `GeoPath` | **Keep as projections** | Never identity |
| **15 files outside Geography reading `geo_administrative_units`**: Governance (`GovernanceGeoUnitQueryService`), Membership (`CreateMemberRequest`, `NearbyCommitteesQueryService`, `Committee`, `MemberResidenceGeoIdentity`, `GeographyLookupInterface`, residence migration), `app/Http` (5 controllers, `StoreCommitteeRequest`), `GovernanceGeographyProjectionBuilder` | **Contract violation** (T-2) | Published queries and copies |
| `members.residence_geo_unit_id` referencing an organisation-local copy row | Not canonical; residence used for classification (D-80) | Address owner stores a `ResolutionResult` with `ReferenceUnitId` |
| `GeographyLookupService` checking `Schema::hasTable` | Infrastructure guessing whether Geography exists | Explicit contract; "unresolved" when unavailable |

## C.3 How to implement it correctly (principles, not a plan)

1. **Domain first, pure PHP:** `ReferenceUnit`, `Revision`, `CountryScheme`, `Candidate`, `Source` and the value objects, with their invariants under unit tests **before** any Eloquent (project layer rules; TDD RED → GREEN).
2. **Application:** commands with DTOs, never arrays, behind repository interfaces. Read side: Eloquent queries and projections (CQRS-light, project rule 5).
3. **Infrastructure:** landlord database; append-only claim storage; database-clock `recordedAt` with the per-scheme barrier and `closedThrough`. Projections rebuilt from claims.
4. **Data loading is evidence:** seeders (`NepalGeographySeeder`) become imports of evidenced revisions. Nepal's provinces and districts, for example, cite Schedule 4 of the Constitution.
5. **Consumers switch to the contract** before any old table is retired, so that the 15 direct readers have a path.
6. **Fitness tests** verify properties (project rule): no consumer reads Geography tables; no claim is updated; no code is used as identity; every position has one meaning per country and date.

## C.4 Validated versus proposed

| Item | Status |
|---|---|
| Strategic ownership and rules G-1 to G-8 | Validated against scenarios; **PROPOSED** until accepted |
| Aggregate catalogue (B.2) | **Proposed tactical design**, justified invariant by invariant (B.1) |
| `CountryScheme` structure | **Conditional on L-1** |
| Barrier and `closedThrough` for Geography | Pattern validated in Membership design (§38.2); **[TU]** performance of the per-scheme lock under bulk imports |
| Global regions scheme | **Conditional on L-3** |
| Street | **Conditional on L-2** |

## C.5 The next Principal decision

**L-1:** do levels 3–10 mean (A) fixed global categories, (B) positions in each country's dated profile, read identically by addresses and committees, or (C) tree depth? **Recommendation: B.**

## Sources

As cited in the two Geography discovery documents (Constitution of Nepal 2015; Hessian law on the Regierungspräsidien § 2(2); Rheinland-Pfalz VwORG 1999 § 5; wiesbaden.de; Ministry of Internal Affairs and Communications, Japan).

---

# Revision 2: independent architectural challenge and corrected baseline (2026-11-09)

**Status:** PROPOSED. Commission: "Reference Geography: Independent Architectural Challenge and Baseline Closure" (Principal DDD Architect; recommendations accepted by the Principal). **Where Revision 2 differs from Parts A–C, Revision 2 applies.** Not committed (commission §10).

**On L-1:** the review asks the Principal directly: "Do you accept L-1 Option B…?" **General acceptance of the review is not taken as that ruling** (standing rule: never treat general acceptance as an explicit ruling when an explicit one is demanded). L-1 stays open.

## R2.0 Disagreements (stated first)

1. **Finding 2 is right, but the multi-aggregate change set has precedent:** [AD] D-22 option C (Organisation: tree consistency by change sets and a tree version). **The conclusion still holds:** Part B gave the invariants the wrong home. G-4 and G-5 are **inherently cross-unit**, so they belong to the aggregate that holds the structure (R2.5).
2. **Finding 3 adds one genuine business question the review did not list:** **who on the platform may verify and publish geography, and who handles source conflicts** (decision S-1). D-31 names "approval of submitted missing units" but not the approver. This is the authority part of my withdrawn Decision 1, now isolated from the source question.
3. **Correction of my own reply, not of the document:** I wrote "the only part of the design still waiting on a ruling is L-1". The document itself (B.9, C.4) listed L-2, L-3 and Question 1. **The reply was wrong;** R2.3 is the complete register.

## R2.1 Executive judgement

The strategy holds: one trusted atlas, canonical identity, no party structures, two time dimensions, no guessing, no table access. **The tactical design does not hold as written.** It needs four corrections:
- structure moves into one aggregate;
- `Revision` becomes a record, not an aggregate;
- every state change on a claim becomes an appended assessment;
- precedence becomes a policy, separate from `Source`.

Evidence has to mean **"officially asserted"**, not "true".

## R2.2 Self-challenge findings

| # | Finding | Category | Consequence for PublicDigit |
|---|---|---|---|
| F-1 | Open decisions stated inconsistently (my reply) | Contradiction | A ruling on L-1 might have been read as freezing everything |
| F-2 | `Revision` modifying several `ReferenceUnit` aggregates | Boundary does not protect its invariant | Cycles or a missing successor could slip in between two transactions |
| F-3 | `Source` cannot enforce precedence between sources | Ownership unclear | Conflicting official data could be published by whichever was entered last |
| F-4 | `MarkConflict` and `ResolveConflictByPrecedence` read as mutations | Temporal semantics underspecified | Earlier knowledge ("as known on 10 February") could be rewritten |
| F-5 | Street, address-only classes and coverage eligibility not separated in the model | Concept mixing | A street could become coverage or an administrative level by accident |
| F-6 | "Every claim has evidence" read as "the claim is true" | Claim stronger than the evidence | Adjudication could treat the platform as a guarantor of geographic truth |
| F-7 | Several schemes per country, but the 0–10 framework's scheme not designated | Missing rule | Two profiles for one country, so G-6 is unenforceable |
| F-8 | "Scenario passes" stated without its invariant | Weak validation | Unproven confidence |
| F-9 | Consumer copy "complete through `τ`" undefined | Missing rule | An election cut-off could read an incomplete copy |
| F-10 | The verification operator's role and accountability undefined | Missing business authority | Nobody is answerable for a wrong publication |

## R2.3 Complete decision register

| ID | Question | Alternatives | Blocks baseline acceptance? | Blocks only | Recommendation |
|---|---|---|---|---|---|
| **L-1** | What do levels 3–10 mean? | A global categories · **B dated country positions, shared by addresses and committees** · C tree depth | **Yes:** the meaning of a level is strategic | — | **B** |
| **L-2** | Does Geography hold streets (and below)? | (i) as units · **(ii) address-only class with a position, no units** · (iii) only where official street registers exist | **Partly:** the scope statement of Geography | Coverage eligibility of streets | (ii), later (iii) |
| **Q-1** | May PublicDigit publish non-official provisional references? | (a) · **(a) + (c)** · (b) | **Yes:** it defines what Geography is ("officially asserted only" or not) | Candidate workflow; Organisation areas | (a) + (c) |
| **S-1** *(new)* | Who on the platform may verify and publish geographic claims, and what happens when sources conflict? | (i) **a designated platform geography steward role**: verifies evidence; conflicts without a precedence rule stay disputed, never decided by the steward · (ii) any platform operator · (iii) only automated imports from registered sources | No | Verification workflow; conflict handling | (i) |
| **L-3** | What defines level 1? | **(i) a statistical regional classification** · (ii) conventional continents · (iii) none | No | Global regions scheme content | (i) |
| **Q-2** | Other official schemes (constituencies)? | include as separate schemes · exclude | No | — | Later |

**Order:** L-1 → Q-1 → L-2 → S-1 → L-3 → Q-2. One at a time.

## R2.4 Strategic separation, validated

| Concept | Owner | Kept apart from | Example |
|---|---|---|---|
| Official identity | Geography (`ReferenceUnit`) | Codes, names, copies' row ids | One Hessen for NRNA, Organisation B and addresses |
| Official claims (officially asserted) | Geography | Truth; party facts | "Schedule 4 asserts Rukum (Eastern part) in Province No. 5" |
| Country profile | Geography (`CountryScheme`, designated reference scheme) | Tree depth; authority | DE 5 = Kreis / kreisfreie Stadt; NP 5 = municipality |
| Administrative containment | Geography | Address hierarchy | Wiesbaden within Regierungsbezirk Darmstadt within Hessen |
| Address-only classes | Geography profile (position only) [L-2] | Administrative units; coverage | Street; Japan's gun [BF, MIC] |
| Address resolution | Address owner (open, B.4) using Geography's service | Belonging (D-80) | "Mainz-Amöneburg" is ambiguous |
| Organisational unit, coverage | Organisation | Geography units | NRNA Hessen-Rheinland-Pfalz → {Hessen, Rheinland-Pfalz} |
| Belonging | Organisation | Address, coverage (D-79, D-80) | By act, not by residence |

## R2.5 Corrected tactical model

| Element | Kind | Owns | Protects |
|---|---|---|---|
| **`CountryScheme`** (per country × scheme) | **Aggregate** | Profile (classes, positions, validity); **all structural facts: unit existence periods in the scheme, containment, successions, status equivalences**; the **revision log**; the scheme version | **G-4** (positions strictly increase along containment, so cycles are impossible); **G-5** (successors); **G-6** (one meaning per position); a whole revision is atomic within **one aggregate** |
| **`Revision`** | **Record (entity in `CountryScheme`'s log), not an aggregate** | Kind, effective date, evidence references, affected identities, successor sets | The evidenced unit of change |
| **`ReferenceUnit`** (per identity) | **Aggregate** | Identity; **attribute** claims only (names, codes, external identifiers, boundary descriptions); same-as retirement; **assessments** | **G-1**, **G-3**, attribute side of **G-2** |
| **`Candidate`** | Aggregate | Submission lifecycle | Submissions never become published facts without verification |
| **`Source`** | **Aggregate, narrowed** | Issuer, competence (scheme, territory, period), authenticity method, precedence rank | Consistency of one source's own competence record |
| **`PrecedencePolicy`** | **Domain service** | — | Cross-source evaluation (R2.6 B) |
| **Designated reference scheme** | Rule in `CountryScheme` | — | **One** scheme per country carries the 0–10 positions read by addresses and committees (F-7); other schemes are unpositioned or have their own classes |

**How a division now works:**
1. New identities are minted (new `ReferenceUnit`s, attributes such as names unpublished).
2. **One transaction on `CountryScheme`** appends the `Revision` (end A; establish B, C with containment; successors A → {B, C}), checked against the scheme version.
3. **Publication** of the revision makes the structure and the new units' attributes visible together. Consumers see all of it at the publication's `recordedAt`, or none of it.

**[TU]** `CountryScheme` is large (Germany has about 11,000 municipalities). As a consistency boundary it need not be loaded whole. A version row plus set-based checks of the touched edges in one transaction is the implementation pattern to validate. Writes are rare, so contention is low.

## R2.6 Specific challenges answered

**A. Revision and ReferenceUnit.** Structural invariants live in `CountryScheme`; a revision is one aggregate transaction. `ReferenceUnit` changes (a rename) are separate transactions on that unit. Atomic **visibility** across both comes from **publication**, not from one transaction across aggregates.

**B. Source competence and precedence.** `PrecedencePolicy` evaluates conflicting assertions at **verification**:
1. It filters to sources competent for the scheme, territory and period.
2. A **later act of equal or higher competence supersedes** an earlier one.
3. A primary act outranks a dataset restating it.
4. **No rule decides** → the claim is **disputed**: not published as established; consumers keep the last published state with a dispute marker.
5. **Neither source competent** → **not verifiable**; it stays a candidate-level assertion.

Outcomes are recorded as assessments on the target aggregate. Accountability: decision **S-1**.

**C. Append-only history.** Claims are **insert-only**. Every state change is an appended **assessment** referencing a claim:
- *verified* (actor, evidence check, time);
- *disputed with* (other claim);
- *superseded by precedence* (rule, winning claim);
- *published*;
- *withdrawn by source*.

A claim's current state is its latest assessment **as at `τ`**, so earlier knowledge is reproducible. `MarkConflict` and `ResolveConflictByPrecedence` become **"record assessment"**. **Made impossible, not discouraged:** storage permits insert only (no update or delete rights for the application), plus a fitness test.

**D. CountryScheme.** Several schemes per country are allowed; **exactly one is the designated reference scheme** carrying positions 3–10. Classification position ≠ tree depth ≠ authority (Addendum B.2).

**E. Evidence and verification.** Five steps, each recorded:
1. source competence (`Source`);
2. evidence receipt (`EvidenceRef`: received date);
3. authenticity check (assessment);
4. verification against competence and precedence (assessment);
5. publication (assessment).

Corrections or withdrawals arrive as new evidence. **The published meaning is "a competent source officially asserts X, authenticity verified", never "X is true".** For contestation, evidence documents should be fixed through Record-Fixing (a contract to design later).

## R2.7 Invariants G-1 to G-8, individually

| # | Rule (business) | Violating operation | Prevention | Local or coordinated | If unsatisfiable | History |
|---|---|---|---|---|---|---|
| G-1 | A place's identity is never reused | Minting from a reused code; merging duplicates | Opaque minting; same-as retirement | Local (`ReferenceUnit`) | Duplicate stays two identities until same-as is evidenced | Retired identity kept, points onward |
| G-2 | Only competent sources' assertions are published | Publishing an unverified or incompetent claim | Publication requires a *verified* assessment via `PrecedencePolicy` | Coordinated (policy reads `Source`) | Not published; disputed or pending | Assessments kept |
| G-3 | History is never rewritten | Updating a claim | Insert-only storage; assessments | Local | — | Every past `τ` answerable |
| G-4 | No loops; levels increase downwards | Re-parenting or division producing a cycle or a lower position | Check within `CountryScheme` transaction (strict order makes cycles impossible) | Local (`CountryScheme`) | Revision rejected | Rejection recorded |
| G-5 | An ended place names its successors | Division without successors | Revision validity rule | Local | Revision rejected | — |
| G-6 | One meaning per position per country and date | Two classes with different meaning at one position | Profile rule in the designated scheme | Local | Profile change rejected | Profile versions kept |
| G-7 | Geography never changes others' facts | Sync writing into consumers | Contract only announces; no write path | Architectural | — | — |
| G-8 | Answers are complete or refused | Reading beyond `closedThrough` | Barrier (§38.2 pattern) and refusal | Local (Geography log) | Refused; retry later | — |

## R2.8 Time, consistency and integration

- **Queries** `StructureAsAt(country, asOf, τ)` and `UnitsAsAt(…)`: from published entries with `recordedAt ≤ τ`; refused if `τ > closedThrough` (CR-01 §3).
- **Late change:** effective date in the past, `recordedAt` now. Earlier `τ` answers are unchanged (B.8 table).
- **Retroactive correction:** a new revision of kind *correct*; the old one stays answerable.
- **Concurrent revisions:** serialised by the `CountryScheme` version; the loser is re-evaluated.
- **Consumer copy completeness (F-9):** a copy is **complete through `τ`** only if:
  - it has applied every published entry with `recordedAt ≤ τ`, **and**
  - Geography's `closedThrough ≥ τ` at the time of applying.

  The copy records both.
- **Single `τ` across contexts:** an electorate cut-off needs Organisation's `closedThrough ≥ τ` **and** its Geography copy complete through `τ`. Otherwise it is refused.
- **Rebuild:** from the log, from position 0 or from a snapshot.
- **Geography unavailable:**
  - Organisation may act on its complete copy. Decisions that require knowledge beyond the copy's completeness are refused, not guessed.
  - The address owner records "unresolved" and retries.
  - **Neither may create an alternative identity.** Their only path is a `Candidate`.

## R2.9 Contracts

| Consumer | Published to it | May rely on | Corrections | Projection | Completeness and replay |
|---|---|---|---|---|---|
| Organisation | Revision published; profile changed; units as at | Officially asserted, verified, published claims as at (asOf, τ) | New revisions; **its organ decides about coverage (D-21)** | Yes, keyed by `ReferenceUnitId` | R2.8 rule; replay from log |
| Address owner | Resolve place; units as at | `ResolutionResult` with state and depth; never more precision than evidenced | Re-resolution is the owner's new claim | Optional | Same |
| Both | — | **Not** truth; **not** belonging (D-80) | — | — | — |

## R2.10 Scenarios, with their reasons

| Scenario | Why it passes |
|---|---|
| NRNA Hessen-Rheinland-Pfalz | Coverage references two `ReferenceUnitId`s (Organisation); G-7 prevents Geography from holding the committee; D-80 keeps the address out of belonging |
| Two organisations, one Hessen | G-1 (one identity); copies keyed by it (R2.9) |
| Ambiguous "Mainz-Amöneburg" | `ResolutionResult` state *ambiguous*; the contract forbids overstating precision |
| Division learned late, then corrected | `CountryScheme` atomic revision (G-4, G-5); G-3 assessments; `τ` queries (R2.8) |
| Concurrent revisions on Germany | `CountryScheme` version |
| Nepal 2015 | Profile change and structural revision in **one** `CountryScheme` transaction (G-6, G-5) |
| Conflicting sources on a name | `PrecedencePolicy`; no rule → disputed (G-2) |
| Party tole offered to Geography | No competent source → not verifiable (G-2); belongs to Organisation (D-31) |

## R2.11 Status of every material element

| Element | Status |
|---|---|
| Strategic boundary and separations (R2.4) | PROPOSED; depends on L-1, Q-1, L-2 for acceptance |
| G-1 to G-8 | PROPOSED, each with mechanism (R2.7) |
| `CountryScheme` holding structure; `Revision` as record | **Corrected tactical design**, PROPOSED |
| Assessments instead of mutation | PROPOSED |
| `PrecedencePolicy` | PROPOSED; accountability depends on S-1 |
| Designated reference scheme | PROPOSED |
| Completeness rule for copies | PROPOSED |
| `CountryScheme` size handling | **[TU]** |
| Record-Fixing of evidence documents | Later contract |

## R2.12 Decisions required from the Principal

1. **L-1** (now).
2. Q-1.
3. L-2.
4. S-1.
5. L-3.
6. Q-2 later.

---

**Ruling recorded (2026-11-09): L-1 option B accepted by the Principal as D-81** (direct: "I accept L-1 option B"; discovery record §11.15). L-1 is closed. `CountryScheme`'s structure (per-country dated profile, designated reference scheme read by addresses and committees) is no longer conditional on L-1. Still open, in order: Question 1, L-2, S-1, L-3, Question 2.

---

# Revision 3: D-82 integration, organisation-defined areas, corrected consistency model (2026-11-09)

**Status:** PROPOSED. Commission: "D-82 Integration, Strategic Geography Closure and Tactical DDD Validation" (Principal DDD Architect; accepted by the Principal). **Where Revision 3 differs from Revision 2 or Parts A–C, Revision 3 applies.** Not committed.

## R3.0 Disagreements and corrections (stated first)

1. **D-82's provenance is recorded as "via paste, accepted", not "direct".** The ruling ("Option A + C") was given in the reviewer's session and reported in the paste; in this session the Principal accepted the document. If the Principal wants it recorded as direct, a one-line confirmation suffices.
2. **Revision 2's country-wide `CountryScheme` aggregate is withdrawn.** The reviewer's concern (Concern A) is confirmed by evidence:
   - [BF] Schedule 4 of Nepal's Constitution divides Nawalparasi **across two provinces** (Province No. 4 east of Bardaghat Susta, No. 5 west).
   - **So no subtree-sized aggregate (per province or Land) can hold a real division**, and a country-wide one serialises every change in the country.
   - The correct boundary is **the revision's touched set** (R3.4).
3. **Revision 2's precedence rule "a later act of equal or higher competence supersedes" is withdrawn as a general rule.** "The later law prevails" applies only within the same subject, territory and period, and only where the legal order says so. **Precedence is applied only where a recorded rule, with its own legal basis, covers the case; otherwise the claim is disputed** (R3.5).
4. **D-82 makes L-2 easier, not harder.** A party that needs street-defined committee areas can define them as **organisation areas described by street names** (D-82). Geography therefore does not need street units for coverage, which strengthens L-2 option (ii).

## R3.1 Accepted decisions and their consequences

| Decision | Consequence for Geography |
|---|---|
| [AD] **D-81** levels | `CountryProfile` per country and scheme; one designated reference scheme carries 0–10, read by addresses and committees |
| [AD] **D-82** official geography + organisation areas | Geography holds **only** officially asserted facts. **Today's per-tenant party units (`is_official = false`, levels 6–8) are organisation areas, not geography** |
| [AD] D-31, D-73, D-79, D-80 | Boundary; time; placement basis; address ≠ committee |
| [AD] V-22 "Organisation's own territorial structure; keep C4 and C5 separate" | **Independent support for D-82:** the Principal already separated the organisation's own territory from Geography |

## R3.2 Strategic ownership, updated

| Concept | Owner | Owns | Never |
|---|---|---|---|
| Official geographical unit | Reference Geography | Identity, officially asserted claims, history, evidence | Party areas; belonging |
| **Organisation-defined area** | **Organisation** (that organisation) | Area identity, name, definition versions, relations to official units, lifecycle | An official identity; another organisation's areas |
| Organisational unit | Organisation | A place in the party structure where people belong | A territory |
| Committee coverage | Organisation | 0..n references, each **an official unit or an own area** | Belonging |
| Address resolution | Address owner (open, Addendum B.4) | Resolution to **official** units, with state and depth | Areas; belonging |
| Address-to-area association | **Organisation** (a suggestion for its own purposes) | Suggestion only | Belonging (D-79, D-80) |
| Belonging | Organisation | By act or adopted rule (D-79) | Derived from address, area or coverage |
| Electorate | Election, from Organisation's belonging at one `τ` | — | Derived from areas or geography (D-82) |

## R3.3 The organisation-defined area model [SI unless marked]

| Question | Finding |
|---|---|
| Separate concept or a kind of unit? | **Separate.** A unit is where people *belong*; an area is a *territory description*. An area can exist without a unit (defined for future committees or reporting) and serve several units (a geographic branch and a wing branch covering one tole, V-24, V-53) |
| Identity | Minted by Organisation, **scoped to the organisation**; never a `ReferenceUnitId`, never shared |
| Nested? | Yes (a tole within a settlement area), as a declared "part of" relation |
| Overlap? | Yes as territory. Whether overlapping areas may both be *covered* is an **organisation rule** |
| Cross official boundaries? | Yes (a settlement spanning two wards) |
| Several committees per area? | Yes |
| Definition | Dated **definition versions**: name; description (text, optionally the organisation's own geometry); **relations to official units** ("lies within ward 5", "overlaps wards 5 and 6"), which are **the organisation's assertions, not Geography facts** |
| Governed by | The organisation's competent organ under its rules; by default the body that makes structural changes (D-21 describes the organisations studied, not a platform rule) **[OQ later: only if a platform default is needed]** |
| Revised, retired, replaced | New definition version; retirement with successor areas or "none" |
| Historical coverage | Coverage references (area, definition version in force at the decision); retirement flags dependent coverage for review, as with official changes |
| Without official reference | Allowed (an area known only by name and description) |
| Identity vs territory | **Identity persists across definition versions;** the territory it describes may change |

**Invariants for areas (to be protected by Organisation's tactical design):**

| # | Rule | Violating operation | Required boundary |
|---|---|---|---|
| OA-1 | Area identity is organisation-scoped and never an official identity | Promoting an area into Geography | Contract: Geography accepts only `Candidate`s with competent evidence |
| OA-2 | Every definition change has a basis (act or rule) | Unaccountable edit | Area's own consistency boundary (as D-22 for units) |
| OA-3 | History append-only; coverage references a dated definition | Rewriting an area's definition | Same |
| OA-4 | An area never creates belonging | Auto-placing residents | D-79, D-80, D-82; no command exists |
| OA-5 | References to official units use canonical identities from a complete copy | Referencing a copy's row id | Organisation's Geography copy keyed by `ReferenceUnitId` |
| OA-6 | Declared nesting is acyclic | "A part of B, B part of A" | **[TU]** organisation-level area-catalogue version, or a strict area rank |

**The aggregate is not designed here** (the commission's instruction); the boundary must satisfy OA-2, OA-3 and OA-6.

## R3.4 Corrected Geography tactical model

| Element | Classification | Business concept | Invariant protected | Justification |
|---|---|---|---|---|
| **`ReferenceUnit`** | **Aggregate** (per identity) | One official place | G-1, G-3; holds **its own** claims again, structural ones included (containment, classification, existence period, succession links) with their assessments | Small; most writes touch few units |
| **`CountryProfile`** | **Aggregate** (per country × scheme) | Classes, positions 0–10 (designated scheme), validity | G-6 | Small; changes rarely |
| **`Revision`** | **Aggregate** (per proposed change) **plus an atomic publication step** | One evidenced official change: kind, effective date, evidence, write set, read set, successor sets | G-5 (successors complete) and evidence completeness, within itself; **G-4 and no partial visibility at publication** | Its own lifecycle (received → verified → disputed → published / rejected) |
| **Publication** | **Coordinated atomic step** inside Geography | Makes a revision visible | **One local transaction:** validates the versions of every write-set and read-set `ReferenceUnit` (optimistic), checks G-4 per new containment edge (parent position < child position at the date, from `CountryProfile`), appends the claims, appends one entry to the **country's publication sequence** | **Deliberate exception to "one aggregate per transaction"**, justified: (1) one context and one database; (2) the business forbids partial visibility; (3) the touched set is bounded; (4) unrelated revisions do not conflict. Same pattern as [AD] D-22 option C |
| **Country publication sequence** | Log (not a domain aggregate) | Order of publications per country | `recordedAt` and `closedThrough` barrier (§38.2) | Held only for the publication step, not during preparation |
| **`Candidate`** | **Aggregate** (small, process-like) | An organisation's submission (D-31) | Never published without becoming a verified `Revision` | Own lifecycle and submitter communication |
| **`Source`** | **Aggregate** (small reference data) | Issuer and competence (scheme, territory, period), each with its legal basis | A competence statement needs evidence of its legal basis | Lifecycle (registered, changed, retired) |
| **`PrecedenceRule`** | **Governed reference data** (claims with legal basis), maintained like `Source` | "Corrigenda in Gazette X supersede the original notice" | No precedence without a recorded legal basis | Data, not code |
| **`PrecedencePolicy`** | **Domain service** | Applies recorded rules to conflicting assertions | Unresolvable → disputed | Stateless |
| Claim | Entity in `ReferenceUnit` | One assertion | Insert-only | — |
| Assessment | Entity (append-only) in `ReferenceUnit` or `Revision` | Verified, disputed, superseded, published, withdrawn | Never mutates a claim | — |
| `EvidenceRef`, `ValidityPeriod`, `ClassPosition`, `SuccessorSet`, `ResolutionResult` | Value objects | — | — | — |
| Hierarchy, paths, units at a level, search, resolution index | **Read models** | — | Rebuildable | — |

**Why not country-wide (Revision 2):** a version per country makes unrelated revisions (a rename in Bavaria and a division in Hessen) conflict, and the aggregate size grows with the country. **Why not per province or Land:** real divisions cross them (Nawalparasi). **Touched set plus publication** keeps atomicity and lets unrelated changes proceed. **[TU]** The read-set needed for G-4 (parents' classification) must be proven complete; profile changes that reclassify a class must re-validate affected edges (a revision of its own).

## R3.5 Sources, competence and conflicts

1. **Competence:** a source counts only within its recorded competence (scheme, territory, period), and each competence statement has its legal basis.
2. **Authenticity:** checked and recorded as an assessment.
3. **Conflict:**
   - `PrecedencePolicy` applies **only recorded `PrecedenceRule`s** whose legal basis covers the case (same subject, territory, period).
   - **No covering rule** → the claim is **disputed**; consumers keep the last published state with a dispute marker.
   - **Neither source competent** → not verifiable.
4. **Nobody decides a conflict by preference**, and the S-1 decision governs who is accountable.
5. **Meaning of a published claim:** "a competent source officially asserts X; authenticity verified". It is never a claim that X is true.

## R3.6 Temporal worked example (receipt → correction)

District A, official notice N1 (division into B, C, effective 1 January).

| Date (`recordedAt`) | Event |
|---|---|
| 10 Feb | N1 received → `Revision` R1 *received* |
| 12 Feb | R1 *verified* and **published** |
| 14 Feb | Notice N2 received from another source: A divided into B, C, **D** → R2 *received* |
| 15 Feb | Conflict recorded: R1 *disputed with* R2 |
| 20 Feb | A recorded precedence rule covers it (N2 is a corrigendum of N1 by the same authority) → R2 *verified*, **published**; R1 *superseded* |
| 1 Mar | Correction N3 (effective date of the division 1 February) → R3 published |

**Consumer view (published state), as at 20 January:**

| Known at `τ` | Answer |
|---|---|
| 11 Feb | A (R1 received, not yet published) |
| 13 Feb | B, C (R1) |
| 16 Feb | B, C **with dispute marker** |
| 21 Feb | B, C, D (R2) |
| 5 Mar | **A** (R3: the division was effective only from 1 February) |
| 21 Feb, asked again after 1 March | Still B, C, D: earlier knowledge is never rewritten |

**Steward and audit view:** the full trail of receipts and assessments at each `τ`. All queries are refused beyond `closedThrough` (CR-01 §3).

## R3.7 Remaining decisions

| ID | Question | Blocks | Next? |
|---|---|---|---|
| **L-2** | Streets: units, address-only class, or per country? | Geography scope statement | **Yes, next** |
| S-1 | Governance: who is accountable for publishing geography; one approver or two? (The mechanism is architecture) | Verification workflow | After L-2 |
| L-3 | Level-1 source | Global regions content | After S-1 |
| Q-2 | Constituencies | Nothing current | Later |

**L-3, evidence so far [BF]:** UN M49, "Standard Country or Area Codes for Statistical Use" (UN Statistics Division; print last issued 1999, maintained online). The grouping is for statistical convenience and "does not imply any assumption regarding political or other affiliation". **Recommendation unchanged:** use it, cited as a statistical classification. Organisations' own regions are organisation areas (D-82).

## R3.8 Scenario validation (rule · owner · invariant · boundary · outcome · failure)

| Scenario | Rule | Owner | Invariant | Boundary | Outcome | If no reliable answer |
|---|---|---|---|---|---|---|
| Nepal 2015 | Dated profile; successors | Geography | G-6, G-5, G-4 | `CountryProfile`; `Revision` publication | Provinces from 20 Sep 2015; Nawalparasi and Rukum succeeded by four districts; 75 continue | Revision rejected; previous state stays |
| German differences | Country profile | Geography | G-6 | `CountryProfile` | Rheinland-Pfalz has no position-4 units from 1 Jan 2000; Kreise stay at 5 | — |
| Japan's gun | Address-related class | Geography | G-6 | `CountryProfile` (L-2) | Positioned, not administrative | Pending L-2 |
| NRNA Hessen-Rheinland-Pfalz | Coverage references official units | Organisation | G-1, OA-5 | Organisation coverage | Two `ReferenceUnitId`s | Copy incomplete → coverage change waits |
| Two organisations, one Hessen | One identity | Geography | G-1 | `ReferenceUnit` | Same identity | — |
| Tole defined by one organisation | D-82 | Organisation | OA-1, OA-2 | Area boundary (to design) | Organisation area; Geography untouched | — |
| Two organisations, different areas, same place | D-82 | Each organisation | OA-1 | Organisation-scoped identity | Two unrelated areas; no conflict | — |
| Area crossing official boundaries | D-82 | Organisation | OA-5 | Area definition | "Overlaps wards 5 and 6" as the organisation's assertion | — |
| Address inside an area, member elsewhere | D-79, D-80, D-82 | Organisation | OA-4 | No command derives belonging | Suggestion at most; belonging unchanged | — |
| Ambiguous address | No guessing | Address owner | Resolution contract | `ResolutionResult` | *Ambiguous* | Recorded unresolved |
| Late official change | Two times | Geography | G-3, G-8 | Publication sequence | R3.6 | Refused beyond `closedThrough` |
| Later correction | Append-only | Geography | G-3 | Assessments | R3.6 | — |
| Concurrent unrelated changes | No false conflicts | Geography | G-4 | **Touched-set versions** | Both publish | — |
| Concurrent overlapping changes | Atomicity | Geography | G-4, G-5 | Touched-set versions | One publishes, the other is re-evaluated | — |
| Conflicting official sources | Recorded precedence only | Geography | G-2 | `PrecedencePolicy` + `PrecedenceRule` | Superseded or disputed | Disputed, never chosen |
| Election at `τ` | One `τ`; complete copy | Election ← Organisation | G-8, R2.8 rule | Organisation copy completeness | Answer at `τ` | Refused, never partial |

## R3.9 Implementation consequences (for building Geography correctly)

- **Two stores, two owners:** official geography (Geography, landlord database) and **organisation areas (Organisation, each organisation's own data)**. **Never one table with an `is_official` flag.**
- **Today's tenant party units** (`Tenant/2025_01_01_000001_…:16, 70`) **move to Organisation areas.** The tenant geography table becomes a pure, rebuildable copy keyed by `ReferenceUnitId`.
- **Insert-only claim and assessment storage;** publication as the single atomic step; per-country publication sequence for `recordedAt` and `closedThrough`.
- `PrecedenceRule`s and `Source` competences are **data with legal-basis evidence**, not code.
- **Fitness tests:**
  - no claim is updated;
  - no Geography identity is minted outside `Revision` publication;
  - no Organisation area is ever written into Geography;
  - no consumer reads Geography tables.

## R3.10 Unresolved technical uncertainties

- Completeness of the G-4 read set under profile reclassification.
- OA-6 nesting acyclicity mechanism.
- Size of the publication step for very large revisions (for example a national re-numbering).
- Address owner's context (Addendum B.4 row 6).
