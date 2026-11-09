# Reference Geography: tactical DDD design baseline, version 2 (proposed for ARB)

| | |
|---|---|
| **Status** | PROPOSED for Principal/ARB ratification. No code, migrations or tickets. Not committed |
| **Supersedes** | Version 1 (`2026-11-09-reference-geography-tactical-design-baseline.md`, kept as record) |
| **Incorporates** | Adversarial review R-1 to R-9 (`2026-11-09-reference-geography-tactical-design-adversarial-review.md`) |
| **Binding decisions** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, D-86 |
| **Strategic basis** | `2026-11-09-reference-geography-strategic-baseline.md` |
| **Labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion · [TD] tactical decision (architect) · [TU] technical uncertainty · [OQ] open Principal decision |

## 1. What the context does [AC]

Reference Geography is PublicDigit's governed knowledge of official places over time. It has four responsibilities:
1. **Know what exists:** one identity per official place, never reused.
2. **Know why it is authoritative:** each fact has its kind and the competence that fits that kind.
3. **Know when it was true and when PublicDigit knew it:** valid time and knowledge time.
4. **Know how a change became trusted:** preparation, approval under D-84 and D-84a, and publication.

**Out of scope:**
- organisation areas (D-82);
- addresses and streets (D-83; the address belongs to Organisation's person-profile capability);
- coverage, belonging, entitlement (D-79, D-80);
- sovereignty (D-86).

## 2. Ubiquitous language [TD]

| Term | Meaning |
|---|---|
| **Place** | An entry the atlas represents: World, a statistical region, a country or area (D-86), or an official territorial unit |
| **`ReferenceUnitId`** | PublicDigit's identity of a place. Minted once, never reused, never an external code |
| **Claim** | One assertion about a place, with a **kind**, a statement, a validity interval `[from, to)` and an evidence reference. Insert-only |
| **Claim kinds** | **territorial** (existence, containment, boundary, classification, succession) · **identifier** (ISO, M49, national code) · **grouping** (statistical region membership, D-85) · **admission** (level-2 entry, D-86) · **name** (attributed to the source using it) · **registry** (authority, mandate, precedence rule) · **dispute marker** |
| **Competence** | What qualifies a source for a claim kind (§4) |
| **Instrument** | A document: constitution, statute, gazette notice, council resolution, dataset, standard, change notice |
| **Evidence reference** | Instrument, competence relied on, receipt date, authenticity check |
| **Assessment** | An appended judgement: authenticity, applicability, duplicate search, precedence applied, conflict declared, approval |
| **Revision** | One proposed change: a set of claims with their evidence and assessments |
| **Publication** | Appending one entry to a log; the only way anything becomes visible |
| **Belief(t, τ)** | What PublicDigit holds true for valid time t, as known at τ (§6) |
| **Steward, matter reviewer** | D-84, D-84a |
| **Candidate** | Missing-unit submission (D-31) |
| **Objection** | Counter-evidence against a published claim |

## 3. Aggregates, invariants and boundaries [TD]

**Five aggregates.** Each is justified by an invariant; everything else is a read model.

### 3.1 `Revision`

| | |
|---|---|
| Protects | A complete, evidenced, approved change |
| Invariants | Every claim's evidence matches **its kind's competence** (§4) · classification only on territorial tiers (D-83) · no territorial claim without a territorial mandate, so **organisation areas cannot pass** (D-82) · identities minted, never derived from codes · successor sets complete · every child of a unit ended or reclassified **within the affected valid-time range** is reassigned · **every establish claim cites an establishing instrument or a recorded duplicate-search assessment** (R-2) · **content frozen at approval** (R-5) |
| Approval | Against `StewardRoster`: every approver declares conflicts; **D-84a's test applies to the deciding person** (interest **and** a judgment between competing positions, R-6); from the appointment of a second steward, structural revisions need a second, independent natural person (distinct canonical person, not the preparer) |
| Lifecycle | `Draft` → `Approved` → `Published` · `Rejected` · `Stale` (terminal; re-preparation is a new revision referencing it) |
| Commands | Prepare, AddClaim, RecordAssessment, Approve, Reject |

### 3.2 `CountryPublicationLog` (per country) and `GlobalPublicationLog` (single)

| | |
|---|---|
| Protects | Integrity of the **published** state, and visibility |
| Global log holds | World; the five M49 regions (D-85); level-2 entries, their admission, identifiers and grouping (D-86); **the authority, mandate and precedence registry** (R-7) |
| Invariants, checked at publication over **every valid-time interval the revision touches** (R-3) | G-4 tiers strictly increase along containment · G-5 successors named · G-6 one meaning per tier; no class ends while units carry it · **no two live places carry the same identifier from the same source in overlapping periods** (R-2) · nothing visible except through an entry · sequence only grows · `closedThrough` |
| Publish | Serialised per log. **In one short step:** re-check approval against the roster **now** (R-5); validate the revision's touched set (units, their parents and children, the touched valid-time range, and the (place, kind) versions of claims it supersedes or marks) **against the authoritative published claim set, not a projection**; then append **one entry**. The revision's "Published" state is derived from the entry |
| Stale | Any changed version → the revision becomes `Stale` |

### 3.3 `Candidate`

| | |
|---|---|
| Lifecycle | Submitted → UnderVerification → Accepted (references its revision) · Rejected |
| Invariants | A candidate publishes nothing itself; candidates for the same place are **linked**; D-84a applies to the assessor |

### 3.4 `Objection`

| | |
|---|---|
| Lifecycle | Submitted → Assessed → Corrected (references a revision) · Disputed (references a dispute-marker revision) · Rejected (with reason) |
| Invariants | **The target claim is unchanged until an outcome is published**; reconsideration only through a **new** objection with **new** evidence, referencing the old one; D-84a applies |

### 3.5 `StewardRoster`

| | |
|---|---|
| Holds | Appointments and ends over time (by the platform owner); matter reviewers (one matter each); independence declarations; **a reference to Identity's canonical person only, no personal data** |
| Invariant | "A second steward exists" is computable as at any time |

### 3.6 Withdrawn or not created

- **`Authority` aggregate:** withdrawn (R-7); the registry is published through the global log.
- Containment, dispute and publication aggregates: not created.

## 4. Competence by claim kind (R-1) [TD]

| Claim kind | Competent source | Competence record |
|---|---|---|
| Territorial | Public authority with a territorial mandate | Mandate chain ending at a constitution |
| Identifier | The code-assigning body (ISO 3166 Maintenance Agency; UNSD; national office) | That body's stated scope |
| Grouping | UNSD (D-85) | Statistical mandate |
| Admission (level 2) | **PublicDigit platform policy (D-86):** ISO listing by default; otherwise a recorded Principal decision | D-86 or the decision record |
| Name | The source using it | Attribution only |
| Registry | Its own legal basis (statute, charter) | As the record states |

- **Integrity** is a fingerprint showing the document is unchanged since receipt. **Authenticity** is a separate assessment: official channel, certified copy, signature.
- **Source precedence** applies only through published precedence rules within their scope; otherwise the claim is published with a dispute marker or not at all.

## 5. Identity (R-2) [TD]

| Element | Rule |
|---|---|
| Minting | Only by an establish claim in a revision, citing an establishing instrument or a duplicate-search assessment |
| Uniqueness the system enforces | One live holder per identifier and source over any period |
| Uniqueness the system cannot fully enforce | **Two IDs for one real place.** Repaired by a **same-as revision**: one identity retired, pointing to the other, never deleted or reused |
| Code reuse | [BF, secondary sources; primary: ISO newsletters] ISO's CS was Czechoslovakia (until 1993) and later Serbia and Montenegro (about 2003 to 2006). **Two places, two identities, each with CS for its own period** |
| Continuity versus succession | By the instrument's characterisation (strategic baseline X.3); each case decided with its instrument |

## 6. Time (R-3, R-4) [TD]

| Time | Held as |
|---|---|
| Valid time | Claim interval `[from, to)`; open end = until further notice |
| Source publication | Instrument |
| Receipt | Evidence reference |
| Assessments and approval | `recordedAt` on assessments |
| Publication | Log entry `recordedAt` (database clock at commit) |

- **Belief(t, τ)** = for each place and claim kind, the claim from the **latest log entry with `recordedAt ≤ τ`** whose interval covers t, with its dispute state at τ. Later published knowledge supersedes earlier knowledge. **Source precedence is a separate matter:** it decides what may be published.
- **Queries:** refused if τ exceeds `closedThrough` of **any log involved**. Because `recordedAt` is database time, **one τ is meaningful across the global and country logs.**
- **Worked check (R-3 example):**
  - Parent P is valid 1 Jan–30 Jun (entry 1); child C is attached from 1 Jun (entry 2).
  - A correction moving P's end to 31 May **is rejected unless the same revision reassigns or ends C for 31 May–30 Jun.**
  - Belief(15 Jun) before the correction: P exists; after it: P ended. Both remain answerable.

## 7. Publication behaviour [TD]

| Guarantee | Mechanism |
|---|---|
| All or nothing | Claims invisible until one entry references their revision |
| No conflicting publications | Serialised per log; versions validated; else `Stale` |
| No unreviewed content | Frozen at approval; any edit is a new revision |
| Governance at publication | Roster re-checked: a second steward appointed after a single approval requires the second approval for structural revisions; a conflict declared after approval voids it; an approver whose appointment later ended stays valid if they were a steward when approving |
| Cross-country change | Both logs in one database; locked in canonical order; one transaction, one `recordedAt`, two entries; **one `RevisionPublished` event carrying both positions** [TU: frequency and lock duration] |
| Large revision | Prepared invisibly; publication = validation plus one entry [TU: validation time] |

## 8. Integration (R-8) [AC]

**Any context may read published geography through the published language; only Geography publishes.** A context that applies geography to a decision owns that decision.

| Context | Uses |
|---|---|
| Organisation | Coverage (**level 2 and territorial tiers only; not levels 0–1, not address tiers**); areas' relations; person-profile address resolution |
| Communication | Country and area names for postal formatting, if needed |
| Election | Through Organisation at one τ |
| Membership, Governance | Nothing needed now; permitted read-only if a capability arises |
| Identity | Geography reads a canonical-person check for steward independence |

**`ResolvePlace`** is a **stateless query** over official names, aliases and codes, returning a `ResolutionResult`. **Storing results, and matching streets against address sources, is the address owner's job.** Geography holds no address text.

## 9. Events and read models [TD]

| Event | Meaning |
|---|---|
| **`RevisionPublished`** (kind: territorial, attribute, correction, dispute-marker, registry, admission; all log positions) | "These claims are published as of these entries" |
| `CandidateDecided`, `ObjectionDecided` | The submitter's outcome |

| Read model | Rule |
|---|---|
| Country and area lookup | Source-attributed names kept separate from the display name (L-4b open); "country or area" with a non-recognition note; no relations between level-2 entries |
| Profile view | Tiers as at (asOf, τ) |
| Hierarchy navigation | Containment from level 2 down; regions shown as a labelled statistical grouping |
| Historical reconstruction | Belief(t, τ) computed from logs and revisions |
| Coverage selector | Level-2 entries and territorial tiers only; published; live at asOf |

**Not an event-sourcing framework:** the business is append-only and bitemporal, so revisions and logs are the authoritative record, and read models are rebuilt from them.

## 10. Scenario results (from the review, after corrections)

**All 15 mandatory scenarios pass, or have defined behaviour:**
- duplicate identity · code change · code reuse;
- non-territorial source · statistical grouping not taken as containment;
- large revision · two revisions on one state · second steward appointed after approval · conflict found after approval;
- late correction · concurrent dispute and change · cross-country change · consistent snapshot across logs;
- organisation area rejected · street rejected · objection reconsidered.

Details: adversarial review §4.

## 11. Open items

| Item | Kind | Blocks |
|---|---|---|
| **L-4b** display-name policy | [OQ] | Import of real data |
| L-4c region without an M49 row | [OQ] | Nothing (an unassigned region is allowed) |
| Japanese blocks | [OQ] later | Japan's profile |
| Q-2 constituencies | [OQ] later | Nothing |
| Validation time for very large revisions; cost of interval validation; cross-country lock | [TU] | Prototype measurement during implementation architecture |
| Primary verification of historical examples (Hong Kong 1997, Sudan 2011, Czechoslovakia 1993, ISO "CS") | Evidence | Their use as reference data |

## 12. Ratification request (when the Principal DDD Architect has assessed this)

**What the Principal would ratify:** the business guarantees, not the mechanisms.
1. **One identity per official place;** duplicates are repaired, never deleted.
2. **Each fact carries the authority that fits its kind;** PublicDigit's own policy governs admission only.
3. **Nothing becomes visible without approved publication; a change is all or nothing.**
4. **What was true and what was known are both answerable, unchanged, for every past knowledge time.**
5. **Objections never change a fact by themselves.**
6. **Geography decides nothing about belonging, coverage, entitlement or sovereignty.**

---

**Ruling recorded (2026-11-09): L-4b option C as D-87** (direct; discovery record §11.22). Country and area display names come from Unicode CLDR in the user's language (standard form; short form only where space is tight). Source names stay as attributed alternatives. **L-4b is no longer a blocker for importing real data.**
