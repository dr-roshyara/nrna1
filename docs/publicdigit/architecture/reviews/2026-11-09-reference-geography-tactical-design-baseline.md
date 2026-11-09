# Reference Geography: tactical DDD design baseline (proposed)

| | |
|---|---|
| **Status** | **Superseded by version 2:** `2026-11-09-reference-geography-tactical-design-baseline-v2.md`, which incorporates the adversarial review R-1 to R-9. Kept unchanged as the record of version 1. (Originally: PROPOSED tactical baseline. No code, migrations or tickets) |
| **Commission** | "Session A — Reference Geography: Transition to Tactical DDD Design" (Principal DDD Architect, 2026-11-09) |
| **Strategic basis** | `2026-11-09-reference-geography-strategic-baseline.md` (with its challenge revision, S-1 dossier, L-3 and L-4 sections); decisions D-31, D-73 / CR-01, D-75, D-79, D-80, **D-81, D-82, D-83, D-84, D-84a, D-85** |
| **L-4** | **Not ruled.** The commission's placeholder "[INSERT THE PRINCIPAL'S EXACT L-4 RULING HERE]" is empty, and general acceptance of the review is not that ruling. **Everything depending on L-4 is marked ⛔** (§10) |
| **Authority labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion from decisions · [TD] tactical recommendation (architect's) · [OQ] open Principal decision · [TU] technical uncertainty |

## 0. Disagreements and additions (first)

1. **L-4 is not taken as accepted** (status row). The design proceeds where L-4 does not matter and marks the rest.
2. **Two concepts the commission's list omits are needed to enforce accepted decisions:**
   - **a steward roster:** D-84 and D-84a cannot be enforced without knowing who is a steward, from when, whether a second steward exists, and who was appointed for one matter;
   - **a global publication log:** levels 0–2 (D-81, D-85) and level-2 admission are not any country's facts.
3. **Every consumer-visible change goes through a publication log, dispute markers included.** Revision 3 let a dispute marker appear by its own `recordedAt`. That bypasses `closedThrough` and the governance check. **Corrected: a dispute marker is published as a log entry like any other change.**
4. **The historical examples** (Hong Kong 1997, Sudan 2011, Czechoslovakia 1993) **are evidence-dependent illustrations**, as the review says. The rule is fixed; each case's identity outcome is recorded only with its instrument (§4.3).

## 1. Business understanding [AC]

Reference Geography is a governed system for authoritative geographical knowledge over time. It has four responsibilities:
1. **Know what exists:** official places, with identities that never change or get reused.
2. **Know why it is authoritative:** the authority, its mandate, the instrument, authenticity and applicability.
3. **Know when it was true and when PublicDigit knew it:** `asOf` and `τ`.
4. **Know how changes became trusted:** preparation, approval under D-84 and D-84a, and publication; plus objections and disputes.

**It never owns** organisation areas (D-82), addresses or streets (D-83; the address belongs to Organisation's person-profile capability), coverage, belonging or entitlement (D-79, D-80).

## 2. Ubiquitous language and domain model [TD]

| Concept | Kind | Meaning | Owns | Does not own |
|---|---|---|---|---|
| **`ReferenceUnitId`** | Value object | PublicDigit's identity of a place; minted once by a revision; never reused; never an external code | — | — |
| **Reference unit** | **Read model** (identity plus its published claims) | One official place as known at (asOf, τ) | — | Nothing writes to it directly |
| **Claim** | Entity in a `Revision` (insert-only) | One assertion: existence, name (source-attributed), external identifier, classification (tier and class), containment, boundary, succession, region membership | Statement, validity (`asOf`), evidence reference | Truth |
| **Assessment** | Entity in a `Revision`, `Candidate` or `Objection` (insert-only) | Authenticity checked; applicability judged; precedence applied; conflict declared; approved; rejected | Actor, time, reason | — |
| **`Revision`** | **Aggregate** | One evidenced proposed change (§3) | Claims, assessments, approval record, version expectations | Visibility |
| **`CountryPublicationLog`** | **Aggregate**, per country | What is published for that country, in order | Entries; published versions; `closedThrough` | Claims' content |
| **`GlobalPublicationLog`** | **Aggregate**, single | The same for levels 0–2: World, the five M49 regions (D-85), level-2 entries and their region membership | Same | — |
| **Country classification profile** | **Read model** of published profile claims | Tiers 3–10, classes, territorial versus address tiers (X.1), validity | — | — |
| **`Authority`** | **Aggregate** (reference data) | An issuing public body and its **mandates** (entities): scheme, territory, subject, period, each with its legal-basis instrument | Mandates | Instruments' content |
| **`Instrument`** | Immutable record | A document: constitution, statute, gazette notice, council resolution, dataset, standard (for example ISO 3166-1) | Content fingerprint, locator, publication date | Competence |
| **`EvidenceRef`** | Value object | Instrument + mandate relied on + receipt date + authenticity method | — | — |
| **`PrecedenceRule`** | Governed reference data | A recorded rule, with its legal basis, deciding between instruments within a stated scope | — | — |
| **`PrecedencePolicy`** | Domain service | Applies rules; unresolvable → disputed | — | — |
| **`Candidate`** | **Aggregate** | A missing-unit submission (D-31) | Lifecycle | Publication |
| **`Objection`** | **Aggregate** | Counter-evidence against a published claim (S1.1) | Lifecycle | Published claims |
| **`StewardRoster`** | **Aggregate** (platform, single) | Steward appointments and ends; matter-specific reviewers; declared independence (D-84, D-84a) | Appointments | Identity of persons (consumed from Identity) |
| Dispute marker | Claim-level state, **published via the log** | "Competent instruments conflict; no covering precedence rule" | — | — |
| Correction | A `Revision` kind | Changes recorded knowledge (for example an effective date) | — | — |
| `ResolutionResult` | Value object (published language) | Address matching outcome (X.1) | — | Belonging |

**Not created, by design:** a "Containment" aggregate (containment is a claim); a "Source" aggregate (split into `Authority`, `Mandate`, `Instrument`); a "Dispute" aggregate (a published marker); a "Publication" aggregate (an operation on a log).

## 3. Aggregates and invariants [TD]

### 3.1 `Revision`

- **Root:** `Revision`. **Lifecycle:** `Draft` → `Approved` → `Published` · or `Rejected`.
- **Invariants:**
  - every claim has an `EvidenceRef` whose mandate covers the scheme, territory, subject and the claim's `asOf`;
  - **classification claims name a territorial tier only** (D-83; address tiers hold no places);
  - **no claim without a competent public authority**, so an organisation area can never pass (D-82);
  - identities are minted, **never derived from codes**;
  - successor sets are complete (G-5);
  - every child of a unit being ended or reclassified is reassigned in the same revision;
  - claims and assessments are insert-only.
- **Approval** (D-84, D-84a), evaluated against `StewardRoster` **as at the approval time**:
  - every approver records a conflict declaration;
  - **a materially conflicted steward cannot approve**;
  - once a second steward exists, **structural** revisions need a second, independent natural person (distinct canonical person, not the preparer);
  - otherwise the revision stays `Draft`. **No bypass.**
- **Commands:** `Prepare`, `AddClaim`, `RecordAssessment`, `Approve`, `Reject`.
- **Events:** `RevisionApproved`, `RevisionRejected` (internal).

### 3.2 `CountryPublicationLog` (and `GlobalPublicationLog`)

- **Root:** the log. **Command:** `Publish(revision)`. **Event:** `RevisionPublished`.
- **Invariants, for the published state:**
  - G-4: tiers strictly increase along containment;
  - G-5: successors named;
  - G-6: one meaning per tier; a class cannot end while live units carry it;
  - a sequence that only grows;
  - `closedThrough`;
  - **nothing becomes visible except through an entry.**
- **Publication, serialised per log:**
  1. Re-check the approval against the roster as it is now.
  2. Validate the revision's recorded versions (§3.6).
  3. Append one entry.
- **One aggregate per transaction:** the revision's claims were written while it was a draft. Publication adds only the entry, and the revision's state "Published" is **derived from that entry**, not written separately.

### 3.3 `Candidate`

- **Lifecycle:** `Submitted` → `UnderVerification` → `Accepted` (it references the `Revision` it led to) or `Rejected`.
- **Invariants:** nothing is published by a candidate itself; **the submitting organisation's office-holders are materially conflicted** (D-84a).

### 3.4 `Objection`

- **Lifecycle:** `Submitted` → `Assessed` → `Corrected` (references a `Revision`) · `Disputed` (references a published dispute-marker revision) · `Rejected` (with reason).
- **Invariants:** **the target claim is unchanged until an outcome is published** (S1.1); D-84a conflicts apply.
- **Reconsideration:** only by a **new** objection citing **new** evidence, which references the earlier one. No reopening of a decided objection.

### 3.5 `Authority`, `StewardRoster`

- **`Authority`:** a mandate requires its legal-basis instrument; the chain ends at a constitution (the root).
- **`StewardRoster`:**
  - appointments and ends are made by the platform owner;
  - a matter reviewer is valid for one named matter only;
  - independence is recorded as a declaration;
  - **"a second steward exists" is computed as at a time.**

### 3.6 Concurrency and transaction recommendation

| Element | Choice | Why |
|---|---|---|
| Preparation | Long and unlocked; claims invisible | Large revisions (Nepal 2015) prepared at leisure |
| Publication | **Serialised per log** (a lock on the log row for a short step); inside it, validate every recorded version against the latest published state, then append one entry | Removes write skew without fine-grained locks; unrelated revisions both succeed |
| Versions per unit | Existence-and-classification; child set | §11.2 refinement |
| Cross-country revision (a border change) | Both logs locked in canonical order; one entry each, in one transaction | **Deliberate exception**, rare; [TU] whether to route it through the global log instead |
| `recordedAt` | Database clock at commit | CR-01; avoids the Membership defect |

**Rejected alternatives:**
- one aggregate per unit (multi-aggregate publication);
- a country-wide aggregate (false conflicts, size);
- per-province aggregates (Nawalparasi crosses provinces);
- **event-sourcing framework:** the log and revisions are append-only because the business is bitemporal, **not by fashion.** Read models are rebuilt from them; no generic event store is needed.

## 4. Temporal model and identity lifecycle

### 4.1 Times

| Time | Where held |
|---|---|
| Legal effective time (`asOf`) | Claim validity |
| Source publication | `Instrument` |
| Receipt | `EvidenceRef` |
| Verification, approval, conflict declarations | Assessments (`recordedAt`) |
| Publication (platform) | Log entry `recordedAt` |
| Knowledge boundary `τ` | Query parameter: entries with `recordedAt ≤ τ`; refused if `τ > closedThrough` |

### 4.2 Change handling

| Change | Handling |
|---|---|
| Source corrects itself | Correction revision |
| Rename | Name claim; identity continues |
| Classification change | Classification claim; profile check (G-6) |
| Abolition | End claim (with successors or none) |
| Division, merger | Successor sets |
| Late source | Past `asOf`, present `recordedAt` |
| Conflicting instruments, overlapping periods | `PrecedencePolicy`; no covering rule → published dispute marker |

### 4.3 Identity rule and its evidence

**Rule:** identity follows the legal entity **as the instrument characterises it** (X.3 table). **Each case's outcome** is an assessment in the revision, citing the instrument. Hong Kong 1997, Sudan and South Sudan 2011 and Czechoslovakia 1993 are **unproven illustrations until their instruments are recorded.**

## 5. Revision and publication behaviour [TD]

```
Draft --(Approve; D-84/84a satisfied)--> Approved --(Publish on log)--> Published
  \--(Reject)--> Rejected                    \--(stale versions)--> back to Draft for re-preparation
Published --(later assessments)--> only via a new Revision (correction, dispute marker)
```

| Prevented | By |
|---|---|
| Partial publication | One entry; claims invisible until it exists |
| Two conflicting publications for one country | Serialised publication plus version validation |
| A conflicted steward deciding | Approval check, repeated at publication |
| An objection overwriting a fact | An objection can only lead to a new revision through approval |
| A later source rewriting history | Append-only; new revision; `τ` |

## 6. Source authority and evidence [TD]

- `Authority` → `Mandate` (scope and period; legal-basis instrument) → `Instrument` (fingerprint, locator, publication date) → `EvidenceRef` on each claim (receipt, authenticity method) → applicability assessment → approval → publication.
- **No "latest wins" or "highest wins":** only `PrecedenceRule`s with a recorded legal basis and scope.
- **External source claims are kept separate from PublicDigit's publication decision:** a claim states what an instrument asserts; the log entry states that PublicDigit published it.

## 7. Context boundaries and integration [TD]

| Context | Relationship | Gets | Must not |
|---|---|---|---|
| Organisation (coverage, areas, person profile and addresses) | Open host service and published language; rebuildable copy keyed by `ReferenceUnitId` | Revision published; units, structure and profiles as at; place resolution | Write geography; reinterpret source authority; treat address tiers as coverage |
| Election | Through Organisation, at one `τ` | — | Read Geography for entitlement |
| Identity | **Geography consumes** a canonical-person check for steward independence (D-84) | "Same natural person?" | — |
| Membership, Governance, Communication | **No direct relationship** | — | — |

## 8. Domain events and read models [TD]

| Event | Produced by | Consumers may infer |
|---|---|---|
| `RevisionPublished` (with kind, affected identities, successors, profile changes) | Log | "As of this entry, these claims are published" |
| `DisputeMarked`, `DisputeCleared` | Log (dispute-marker revisions) | "Competent instruments conflict" (not which is right) |
| `CandidateDecided`, `ObjectionDecided` | Respective aggregates | The submitter's outcome |

Read-model updates are technical notifications, not domain events.

| Read model | Purpose | Rule |
|---|---|---|
| Country and area lookup | Level-2 entries, regions, names, identifiers | Source-attributed names kept apart from display ⛔ L-4b |
| Profile view | Tiers and classes as at | — |
| Hierarchy navigation | Containment as at (asOf, τ) | Territorial tiers only |
| Historical reconstruction | Any (asOf, τ) | Computed from logs and revisions |
| Coverage selector | Official places a committee may cover | **Territorial tiers only; published; not ended at asOf** |

## 9. Scenario tests

| # | Scenario | Business behaviour | Model behaviour |
|---|---|---|---|
| 1 | Profile with skipped tiers | Rheinland-Pfalz has no tier-4 units | Containment may skip; G-4 needs only increase |
| 2 | Profile change | Nepal, 20 Sep 2015 | Profile claims in one revision; class may end only once its units end (same revision) |
| 3 | Rename | Same place | Name claim; same `ReferenceUnitId` |
| 4 | Division | Two successors | End plus two new identities; successor set; children reassigned |
| 5 | Merger | One successor | Same pattern |
| 6 | Source correction | Knowledge changes, history kept | Correction revision; earlier `τ` answers unchanged |
| 7 | Conflicting sources | No silent choice | `PrecedencePolicy`; else a dispute-marker revision |
| 8 | Objection | Fact stable until outcome | `Objection` → correction, dispute marker or rejection |
| 9 | Conflicted steward | Cannot decide | Approval refused; waits for a non-conflicted steward or matter reviewer |
| 10 | Second steward appointed | Two-person rule from then | Roster as at approval **and** publication |
| 11 | Late source | Past effect, present knowledge | `asOf` past; `recordedAt` now |
| 12 | Organisation area as coverage | Allowed in Organisation | Never reaches Geography (no competent authority) |
| 13 | Street in an address | Not a place | Address tier; no identity; coverage selector excludes it |
| 14 | External identifier changes | Identity stable | New identifier claim; same `ReferenceUnitId` |
| H | Hong Kong 1997 and other historical cases | Evidence-dependent | Outcome recorded with the instrument; not established yet |

## 10. Remaining Principal decisions

| ID | Question | What it blocks ⛔ |
|---|---|---|
| **L-4** | Level-2 admission policy (A′ recommended) | The admission rule for level-2 entries in `GlobalPublicationLog`; whether an ISO change is evidence enough to propose an entry |
| L-4b | Display-name policy | The lookup read model's display name |
| L-4c | Region for entries without a published M49 row | Region membership claims for such entries |
| (later) | Japanese blocks: address component or official place | Japan's profile |
| Q-2 | Other schemes (constituencies) | Nothing |

## 11. Recommended tactical baseline (summary)

**Six aggregates, each justified by an invariant:**
- `Revision`: complete, evidenced, approved change;
- `CountryPublicationLog` and `GlobalPublicationLog`: published-state integrity and visibility;
- `Candidate`, `Objection`: governed inputs;
- `Authority`: competence;
- `StewardRoster`: D-84 and D-84a.

**Units and profiles are read models. Publication is serialised per log and validates versions. Everything is append-only and bitemporal. Consumers read through published language only.**

---

**Ruling recorded (2026-11-09): L-4 policy A′ as D-86** (direct; discovery record §11.21). The items marked ⛔ L-4 are unblocked:
- `GlobalPublicationLog` admits level-2 entries by revisions whose evidence is an ISO 3166-1 listing (instrument: the standard or its maintenance-agency change notice) **or** a recorded Principal admission decision;
- an ISO change is evidence for a proposed revision, assessed and approved under D-84 and D-84a; **a code change is an identifier claim; a deletion ends an entry only if an instrument shows the end.**

Still open: L-4b, L-4c, Japanese blocks, Q-2.
