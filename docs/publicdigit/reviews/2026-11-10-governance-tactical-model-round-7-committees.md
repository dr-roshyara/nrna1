# Governance tactical model, round 7: committees after D-144 and D-145 (PROPOSED)

| | |
|---|---|
| **Status** | **Architect's proposal. Not accepted; no implementation authorised.** Written by Claude, not independent |
| **Extends** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`: §20, §33, §34, §40, §41 (Governance rounds 1–6). **Where this differs, this governs for committees.** Kept as a separate file because the model document is frozen for IR-OTD-04; a pointer is added there after that review |
| **Analysis** | `docs/publicdigit/architecture/reviews/2026-11-10-governance-committee-discovery-gap-analysis.md` (G-1 … G-5) |
| **Decisions applied** | [AD] D-144 (Governance owns committees), D-145 (a committee ends with its unit when nothing else is said), D-136 (authority first; no ranking from names, levels or containment), D-140, D-142, D-143; [WD] D-10, D-29; earlier D-12, D-15, D-21, D-23, D-24, D-28, D-37, D-38, D-65 … D-78 |

**Labels:** [AD] decided · [AC] conclusion · [TD] tactical design · [WD] working decision · [P] proposal · [OQ] Principal question · [TU] technical uncertainty.

## 52.1 Business explanation

A party's statutes say:
- which **committees** exist (central, province, district, control commission);
- which **offices** each has;
- **how holders are chosen and for how long**;
- **what each committee may decide**;
- **which committee prevails over which.**

PublicDigit records those statutes, the committees, the offices and their holders over time, and every decision taken. It can then answer, for any date and as known at any time:
- who held which office;
- what that office or committee was allowed to decide;
- **whether a particular decision, such as an area rule placing members, was made by a body with the authority to make it.**

**Two things PublicDigit never does:**
- **work out authority from names, levels or the map** (D-136);
- **let a committee outlive its unit unnoticed** (D-145).

## 52.2 Ubiquitous language (additions)

| Term | Meaning | Not |
|---|---|---|
| **Committee** (organ) | A constituted body under the written rules, attached to one or more units | A unit (Organisation's); an election committee (Election's, D-23) |
| **Office** (position) | A seat in a committee, with its powers from the rules | A person; a role or capacity (D-143) |
| **Term of office** (tenure) | One person holding one office for a period | Membership; an affiliation capacity |
| **Precedence grant** | A rule provision stating that one committee kind prevails over another for a kind of decision in a kind of scope | Hierarchy inferred from the unit tree |
| **Precedence evaluation** | Governance's finding, on a decision's date, of how that decision stands against existing decisions of other committees in the same scope | Organisation's ranking (Organisation never ranks) |

## 52.3 Aggregates: what changes from round 6

| Aggregate | Round 6 (§33.1, §41) | **Round 7** |
|---|---|---|
| **Organ** | Body, attachments (own / joint), lifecycle; one active own organ per unit and kind | **Attachments name Organisation's units (`UnitRef`)**, validated against R-2 `StructureAsAt(d, τ)` (D-29). **Ends with its unit when nothing else is said** (D-145). New invariant G-I7 |
| **RuleProvision** | Versions; grants of power | **Also holds precedence grants** (§52.4). Versions and transitions unchanged (D-78) |
| **Position** (tenures inside) | Unchanged | Tenures end on the organ's end (D-145) with Basis `RuleEffect(D-145)` unless the deciding act or rules say otherwise |
| **AuthorityAssignment** | Unchanged | Ends when either party's organ or tenure ends (cascade unchanged, §41.1) |
| **Resolution**, Submission, Sitting, GroupApplication, DecisionLog | Unchanged | A Resolution with an allocation part carries the precedence evaluation in its part (§52.5) |

**No new aggregate.** Precedence evaluation is a **domain service** beside organ competence (D-65, D-70), reading RuleProvision and existing decisions **as known at τ**.

## 52.4 Precedence grants (closes G-1) [TD]

**Form** (a grant inside a RuleProvision version):

| Field | Content |
|---|---|
| `decisionKind` | e.g. *area allocation*, *structural change*, *placement exception* |
| `superior` | organ kind (e.g. Highest Committee) or a named organ |
| `subordinate` | organ kind (e.g. Province Committee) |
| `scopeKind` | the scopes it applies to (e.g. any unit below the superior's unit) |
| `subordinateCompetence` | optional: an **express competence** the rules give the subordinate despite precedence (D-136's "identifiable provision"), with its own scope (e.g. "wards within districts") |
| `provisionRef` | article and version cited |

**Rules:**
1. **Precedence exists only where a grant records it.** No grant → **competence unknown** → the subordinate decision is refused and reported (D-136). **It is never inferred from the tree, names or Geography.**
2. **Setting up an organisation includes recording its precedence grants from its statutes.** D-115 (the province committee as default allocation authority) is a **working direction**, recorded only if the statutes support it.
3. **Equal authority** (no grant either way between two organs) → A-7 step (2): the most specific area wins.
4. **Precedence is evaluated on the decision date** (D-12) under the rule version in force then, as known at τ. **A later rule change never silently re-ranks stored decisions.** It acts through a new decision or the amending version's transition provision (D-78 pattern).

## 52.5 Precedence evaluation and the Organisation contract (refines D-29 R-1) [TD]

When a committee decides an area allocation (or its change or removal), Governance:
1. checks **organ competence** for the decision (D-65, D-70; unchanged);
2. runs **precedence evaluation** against every standing allocation in the same scope that covers the same or a containing or contained area. **Containment is read by Organisation and reported to Governance; Governance does not read Geography** (G-4);
3. records the Resolution part with the outcome:

| Outcome | Meaning | Organisation does |
|---|---|---|
| `Prevails(over: [allocationIds])` | Higher authority | Records; the others have no effect while it stands (D-140) |
| `Subordinate(to: [allocationIds])` | Lower authority, no express competence | **Refused at adoption** (Adopt row); reported. **Never applies later** (D-140) |
| `Authorised(provisionRef)` | Lower authority with an express competence | Records; specificity applies among them (A-7, scenario 29) |
| `Equal` | No precedence either way | Records; A-7 step (2) |
| `Unknown` | Precedence cannot be established | **Refused and reported** (D-136) |

4. sends **`BelongingAllocationsDecided`** carrying: allocation, deciding organ, decision date, **precedence outcome with the allocation ids it names**, `provisionRef[]`, Basis envelope (§41.4), log position.

**Organisation stores the outcome as a fact and derives from it** (A-7, D-140). **It never re-ranks.**

**The one Organisation-side check:** when a *higher* allocation later ends, Organisation reapplies the stored `Prevails(over: …)` sets to find which suspended allocations resume (D-140), with `PlacementReviewNeeded`.

**Ordering:** Organisation reports, with its read of the scope (`StructureAsAt`, allocation scheme as known at τ, containment at the scheme's confirmed position, A-8). Governance's evaluation is only as current as that read. **If Organisation's scheme has changed since (a newer τ), Organisation refuses the decision as stale and Governance re-evaluates.** That is the D-73 "complete or refused" rule, applied to the exchange.

**Status:** the contract is [P] until IR-OTD-04 and acceptance of §49–§51.

## 52.6 Committee lifecycle against unit lifecycle (closes G-2, G-3) [TD]

| Event | Committee | Terms, delegations, matters | Source |
|---|---|---|---|
| **Establish committee** | `EstablishOrgan` with ≥ 1 `UnitRef` active on the effective date (R-2) | — | G-I7 |
| **Attach / detach unit** | Only active units; detaching the last unit = ending the committee | — | G-I7 |
| **Unit ends, act or rules say what happens** | As stated: dissolve, re-attach to a successor, or merge committees | As stated | V-48, V-50, V-58 |
| **Unit ends, nothing said** | **Ends on the unit's end date** | Terms end (Basis `RuleEffect`, D-145); delegations end (cascade); mandates revoked; **pending matters pass to a named successor committee, otherwise lapse**; Highest Committee competent for the area until a new committee exists | **D-145** |
| **Joint committee loses one of several units** | Continues on the remaining units | Unchanged | G-I7 |
| **Unit moves tree, identity continues** (§50.1 R-1 (a)) | Attachment kept | Unchanged | R-1 |
| **Unit moves tree as a new unit** (R-1 (b)) | As "unit ends" above | As above | D-145 |
| **Committee dissolved, unit continues** | Ends; unit now has no committee; **Highest Committee competent** | Terms end per the dissolving act | V-48; [PK] |
| **Re-established after a gap** | **A new committee**, unless the act names the old one's identity (V-51 pattern) | New terms only | V-51 |

**Closure impact report** (Organisation's, §51.5): Governance adds the affected committees, terms, delegations and pending matters to the report sent to the deciding body.

## 52.7 Invariants (additions to §40.2 and §41.6)

| # | Invariant | Enforced by |
|---|---|---|
| **G-I7** | **A committee's attachments are units active on every date of the committee's life**; a committee with no active unit has ended | Organ on attach / detach; D-145 on unit end (processed from `UnitEnded`, same Basis rules) |
| **G-I8** | **No allocation decision leaves Governance without a precedence outcome** | Resolution part validation |
| **G-I9** | **Precedence is never inferred**: every `Prevails`, `Subordinate` or `Authorised` cites a grant; otherwise `Equal` (no grant between distinct kinds with a common superior) or `Unknown` | Precedence evaluation service |
| **G-I10** | **Governance never reads official geography** | Context boundary (G-4); architecture test later |
| G-I1 … G-I6, round 6 | Unchanged | — |

**On `Equal` versus `Unknown`:**
- **`Equal`:** the grants place both organs on the same level (e.g. two province committees under one Highest Committee, neither above the other).
- **`Unknown`:** the organs' relationship isn't covered by any grant at all.
- **Refusal applies only to `Unknown`.**

## 52.8 Commands and events (additions to §33.4)

| Aggregate / service | Commands | Events |
|---|---|---|
| Organ | `EstablishOrgan(unitRefs…)`, `AttachUnit`, `DetachUnit`, `EndOrgan(reason: dissolved · unitEnded · lastUnitDetached)` | `OrganEstablished`, `OrganAttachmentChanged`, **`OrganEnded(reason, basis)`** |
| RuleProvision | `PublishVersion` (now may include precedence grants) | `RuleVersionPublished` |
| Resolution | `RecordResolution` (allocation parts carry precedence outcome) | `ResolutionRecorded`, `PartApplied`, `PartInapplicable` |
| *(to Organisation)* | — | **`BelongingAllocationsDecided`** (with precedence outcome), `StructuralChangeSetsDecided` (D-29, unchanged) |
| *(from Organisation)* | — | consumes **`UnitEnded`** to apply D-145 where the act was silent |

## 52.9 Scenarios

| # | Scenario | Outcome |
|---|---|---|
| 1 | Statutes: "the Central Committee prevails over province committees"; province Y allocates ward W inside D4 after the Central Committee allocated D4 | `Subordinate` → refused at adoption, reported |
| 2 | Same, but the statutes let province committees allocate wards within districts | `Authorised(provisionRef)` → takes effect; W → D3 |
| 3 | No precedence grant recorded at all | `Unknown` → refused and reported; **the organisation is asked to record its statutes' precedence** |
| 4 | Two province committees, both under the Central Committee | `Equal`; A-7 specificity (normally disjoint scopes anyway) |
| 5 | Province allocates first; Central later allocates D4 | Central: `Prevails(over: [Y's])`; Y's suspended; Central's later removed → Y's resumes with `PlacementReviewNeeded` (D-140) |
| 6 | Statutes amended: precedence reversed | Stored decisions keep their outcomes (D-12); the amending version's transition, or new decisions, change effect from their dates |
| 7 | District D2 dissolved; the act is silent about its committee | D-145: the committee ends that day; terms and delegations end; pending matters lapse unless a successor committee is named; Highest Committee competent |
| 8 | D1 absorbs D2; the act says D2's committee merges into D1's | As stated; no second committee for D1 |
| 9 | Joint committee for D1 and D2; D2 ends | Continues for D1 |
| 10 | Attach a committee to a unit closed last month | Refused (G-I7) |
| 11 | Official map: ward W re-parented from D4 to D5 | **No effect on committees or authority** (G-4, G-I10); Organisation raises `AllocationReviewNeeded` (A-8) |
| 12 | A committee member's membership ends mid-term | Office continues until the competent committee decides (D-24); Governance reads CR-01 C3 |
| 13 | Organisation's scheme changed between Governance's read and delivery | Organisation refuses as stale; Governance re-evaluates (§52.5) |

## 52.10 Legacy (evidence only) [BF]

- **The frozen `Membership/Domain/Committee` code (D-144) maps onto three targets:**
  - units → Organisation;
  - organs → Organ;
  - `CommitteeAssignment` → tenures.
- **Its behaviour is the parity reference for the later, separately authorised migration. Its data model is not a design input.**
- **Do not carry over:**
  - rewriting `left_date` (tenures are append-only);
  - committee "geography policy" (G-4);
  - role limits as code constants (they are rule provisions).

## 52.11 Open

- **[OQ] none.** (D-145 closed G-2; G-1 follows from D-136.)
- **Per organisation, UNKNOWN until statutes are on file:**
  - committee kinds;
  - precedence grants;
  - express competences;
  - quorum;
  - incompatibilities (O-8 … O-10).
- **[TU]:**
  - the exclusion constraint and the guard (§41.8);
  - idempotent processing of `UnitEnded` against the closing decision's own instructions. **The closing act's instructions are applied first; D-145 only fills silence.**
- **Finalising the Governance ⇄ Organisation contract** waits for IR-OTD-04 and acceptance of §49–§51.

## 52.12 Readiness

**Readiness for implementation planning:** after the Principal accepts:
- this round;
- the Organisation design §49–§51.

**First slice (later, separately authorised):**
- Organ, Position and tenures, RuleProvision with precedence grants, and the precedence service;
- tests first;
- legacy code frozen alongside.

**Note (2026-11-11, D-167):** "the Highest Committee is competent for the area until a new committee is established" (D-145) no longer applies. **Until a committee is established for the area or a body is designated, acts needing that area's committee return `Undetermined` and wait.** PublicDigit never assigns authority by default.

**Note (2026-11-11, P-3):** rows in §52.6 ("Unit ends, nothing said", "Committee dissolved, unit continues") and scenario 7 that name the Highest Committee as competent are **superseded by D-167**: until a committee is established or a body designated, the acts wait (`Undetermined`).
