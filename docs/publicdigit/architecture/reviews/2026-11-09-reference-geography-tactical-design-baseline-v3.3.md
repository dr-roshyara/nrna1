# Reference Geography: tactical DDD design baseline, version 3.3 (proposed)

| | |
|---|---|
| **Status** | PROPOSED, for the Principal DDD Architect's independent assessment. Self-contained (v1 to v3.2 not needed). No code, schemas or tickets. Not committed |
| **Supersedes** | v3.2 (kept as design evidence) |
| **Companion** | `2026-11-09-reference-geography-tactical-design-v3.3-assessment.md` |
| **Changed in v3.3** | §3 within-kind slot uniqueness and set-valued slots · §5 **before-images** so dependencies catch both gains and losses, plus the invariant-to-dependency matrix · §6 **strict** watermark (later publications strictly after W) and the platform knowledge-time contract · §8 governance per D-88, interpretation options removed |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, D-86, D-87, **D-88** |
| **Labels** | [AD] accepted · [BF] fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical uncertainty · [OQ] Principal decision |

## 1. Purpose and scope [AC]

Reference Geography is PublicDigit's governed record of official geographical knowledge over time.
- **It answers:** what exists; on what authority; what was valid at a date (`t`); what PublicDigit knew and had published at a knowledge point (`τ`); who approved each change.
- **It never decides:**
  - membership, belonging or entitlement (D-79, D-80);
  - sovereignty (D-86);
  - organisation areas or coverage (D-82; Organisation's);
  - addresses or streets (D-83).

## 2. Entities and identity

| Entity type | Meaning | Admitted by |
|---|---|---|
| World | The single level-0 entry | D-81 |
| Statistical region | One of the five M49 regions | [AD] D-85 |
| Country or area | Neutral level-2 entry; asserts no status | [AD] D-86: ISO 3166-1 listing, or an explicit Principal decision |
| Territorial unit | An official place below level 2 in a country's designated scheme | A territorial claim with territorial competence |

**Six things kept apart [TD]:**
1. **PublicDigit identity** (`ReferenceUnitId`): minted once, never deleted or reused.
2. **The real-world geographical entity** it represents: **the geographical entity as established or characterised by the competent instrument.** It **need not have separate legal personality** (a ward or a statistical district may have none). [corrected from v3]
3. **The competent instrument** that establishes or characterises it.
4. **External identifiers**, each in a **namespace** (issuing body, standard, code type, scope). Examples: (ISO 3166 MA, 3166-1, alpha-2, global); (UNSD, M49, numeric, global); (Statistisches Bundesamt, AGS, DE).
5. **Source-attributed names.**
6. **CLDR display names** (countries and areas only, D-87).

**Enforced rules:**
- **I-1:** within a namespace, at any valid instant, a value identifies at most one live subject.
- **I-2:** a subject holds at most one value per namespace at any valid instant.
- **I-3:** an identity is never deleted or reused.
- **I-4:** every *establish* claim cites an establishing instrument, or a recorded duplicate-search assessment.

**Code reuse** (ISO "CS": Czechoslovakia to 1993, then Serbia and Montenegro about 2003 to 2006 [BF, secondary sources; primary: ISO newsletters]) means two subjects with disjoint intervals; I-1 holds.

**Reconciliation [TD]:**
- A *reconcile* revision states, with evidence, that B denotes the same entity as A, and gives the merged value for each conflicting slot.
- From its position onwards, belief resolves B → A. **B's claims and identity remain; earlier τ still shows B separately.** `RevisionPublished` carries the mapping, so consumers holding B can resolve it.
- **Correcting a wrong reconciliation:** a *separate* revision ends the mapping from its own position. Consumers that resolved B → A in the meantime receive the reverse mapping.
  - **Their earlier decisions are not rewritten.** Each recorded the Geography τ it used, so it stays explainable.
- **Human judgement:** whether two identities denote one entity. The system guarantees only accountability and repairability.

## 3. Claims and slots [TD]

A claim is an insert-only assertion about one **slot**, with a value, a valid interval `[from, to)` (`from` inclusive, `to` exclusive; no `to` = until further notice) and a basis.
- **Claims in one slot supersede each other, and only for the valid time they cover. Claims in different slots never interact.**
- **A slot key always begins with the claim kind** [corrected in v3.2: v3.1 gave containment and classification the same key, so one could have superseded the other].

| Kind | Slot key = (kind, …) | Basis (competence kind) |
|---|---|---|
| Existence | (EXISTENCE, unit) | Territorial |
| Containment | (CONTAINMENT, unit, scheme) | Territorial |
| Classification (territorial tiers only, D-83) | (CLASSIFICATION, unit, scheme) | Territorial |
| Boundary description | (BOUNDARY, unit, source) | Territorial, or a cartographic source attributed as such |
| Succession | (SUCCESSION, predecessor, succession event) | Territorial |
| Identifier | (IDENTIFIER, subject, namespace) | Identifier assignment by the namespace's issuer |
| Statistical grouping | (GROUPING, entry, grouping scheme) | Statistical (D-85), D-86 evidence types |
| Admission | (ADMISSION, entry) | PublicDigit policy (D-86) or a Principal decision record |
| Source name, single-valued (official, short) | (SOURCE_NAME, subject, source, language, name type) | Attribution |
| Source name, **set-valued** (alternative, former) [v3.3] | (SOURCE_NAME, subject, source, language, name type, **value**) | Attribution: each alternative is its own slot, so several coexist |
| CLDR display name | (CLDR_NAME, entry, CLDR locale, form ∈ {standard, short}) | Display names (CLDR release), D-87 |
| CLDR locale inheritance | (CLDR_INHERITANCE, CLDR release) | CLDR supplemental data (parent locales) |
| Registry: issuer | (ISSUER, issuer) | — |
| Registry: mandate | (MANDATE, mandate) | Its legal basis **as the jurisdiction provides it** (constitution, statute, delegated act, municipal statute); optional delegation link to a parent mandate |
| Registry: non-territorial competence | (COMPETENCE, issuer, competence kind) | The issuer's stated scope |
| Registry: precedence rule | (PRECEDENCE, rule) | Its legal basis and scope |
| Dispute state | (DISPUTE, target claim) | Assessment |
| Withdrawal | **The target's slot, with the target's kind** (a withdrawal of a name is a SOURCE_NAME claim with value "none") | Instrument or correction |

**Collision proof** [extended in v3.3]:
- **Between kinds:** every key starts with its kind, so containment cannot supersede classification, and source names cannot supersede CLDR names.
- **Within each kind:** the key must name **exactly what can hold only one value at a time**.

| Kind | What holds one value at a time | Coexisting facts | Supersedes |
|---|---|---|---|
| EXISTENCE | A unit's existence | — | Its own earlier existence claims |
| CONTAINMENT | A unit's parent **per scheme** | Parents in different schemes (Q-2, later) | Earlier parent claims in that scheme |
| CLASSIFICATION | A unit's class **per scheme** | Classes in different schemes | Same scheme only |
| BOUNDARY | A boundary representation **per source** | Several sources' representations | Same source only |
| SUCCESSION | One succession **event** of a predecessor | Several events (a partial split, then an ending) | Corrections of that event |
| IDENTIFIER | One value **per namespace** (I-2) | Several namespaces | Same namespace only |
| GROUPING | One region **per grouping scheme** | — | Same scheme |
| ADMISSION | Admission of one entry | — | Its own earlier claims |
| SOURCE_NAME, single | One name **per source, language and type** | Other sources, languages, types | Same key |
| SOURCE_NAME, set | Presence of **one value** | Many alternatives | Only that value (presence or withdrawal) |
| CLDR_NAME | One name **per locale and form** | Locales, forms | Same locale and form |
| CLDR_INHERITANCE | One parent map **per release** | Releases | Corrections of that release's map |
| ISSUER, MANDATE, PRECEDENCE | One record per registry item | Many items | That item |
| COMPETENCE | One competence statement **per issuer and kind** | Kinds | That pair |
| DISPUTE | The dispute state of **one claim** | Disputes on other claims | Earlier states of that claim's dispute |

- **A withdrawal is a claim in exactly the target's slot**, with value "none" over its interval. For set-valued names, it withdraws exactly that value.
- **A dispute is keyed by the target claim, not the target's slot**, so it can never supersede a value.

**Kept apart:**
- (1) source identity and **authenticity**: a provenance check, recorded as an assessment; a fingerprint shows only **integrity**;
- (2) the source's **competence for this claim kind**;
- (3) legal or statistical authority;
- (4) PublicDigit **admission** policy;
- (5) PublicDigit **publication approval**.

**An authentic source whose competence or applicability is disputed:**
- authenticity positive, applicability unresolved → **the revision cannot be approved**;
- if a claim is already published, the matter goes through an objection and may lead to a dispute marker.

## 4. Knowledge, belief and disputes [TD]

- **Knowledge token:** the **publication position** *p* (§6). A claim is **known at p** if its revision's publication position ≤ p.
- **Belief(slot, t, p)** = the value of the claim with the **highest publication position ≤ p** among claims in that slot whose interval contains t. A withdrawal gives "none".
- **Dispute state(claim, p)** = the latest dispute-state claim on that claim with position ≤ p. Its values:
  - **disputed** (with the competing instruments);
  - **cleared** (with the reason: a precedence rule now recorded, a competent resolution, or the conflicting instrument withdrawn).

  **A dispute never changes the target's value.** If a correction supersedes the target, the marker stays with the superseded claim (historical). The new claim has no marker unless one is published for it.
- **Correction of a correction:** another claim in the slot, with a higher position.

## 5. Validation (derived read set) [TD]

**Revision scope** = the claims it adds. **Validation read set** = derived from those claims, never chosen:

| From the revision's claims | Also read |
|---|---|
| Any claim on unit U over interval V | All published claims of U overlapping V |
| Containment or existence of U | U's parents and children in the scheme over V |
| Classification | The profile over V; parents and children over V |
| Identifier (namespace, value) | All subjects holding that value in that namespace over overlapping intervals, **in any partition** |
| Succession | Predecessors and successors |
| Grouping, admission | The entry's identifiers; D-86 evidence types |
| Evidence citing mandates and rules | Those registry claims at the claim's valid time |
| Dispute or supersession targets | Their current state |

**Invariants checked over the read set and every affected interval:**
- G-4: tiers strictly increase along containment;
- G-5: successors named;
- G-6: one meaning per tier; no class ends while units carry it;
- I-1, I-2;
- D-83: classification only on territorial tiers;
- D-82: territorial claims need territorial competence.

### 5.1 Dependencies that catch gains and losses [corrected in v3.3]

v3.2 marked a revision stale only when a **new** claim matched a predicate. **That missed removals.** If C moves from A to B, the new claim says "parent = B" and no longer matches "children of A". v3.3 closes the gap with **before-images**.

**Before-image [TD]:** every published claim carries, for its slot and interval, **the effective value it replaced** (belief in that slot over its interval immediately before its publication).
- It is computed when the revision is validated, against the state at p₀.
- **It is protected by the revision's own dependency on its target slots:** if any of them changed since p₀, the revision is stale and its before-images are recomputed on re-preparation.
- **It stays with the claim forever**, so it is also available for history.

**Two dependency forms**, recorded at validation with the position p₀:

| Form | Meaning | Invalidated by any claim published after p₀ that… |
|---|---|---|
| **Slot dependency** (a named slot over an interval) | "Mandate M at t", "the dispute state of claim X", "(EXISTENCE, P) over V" | …is **in that slot** with an overlapping interval, **whatever its value** |
| **Family dependency** (all slots of one kind, with a value condition φ, over an interval) | "children of A in S over V" = slots (CONTAINMENT, *, S) with value A; "holders of X in namespace N" = (IDENTIFIER, *, N) with value X | …is **in the family** with an overlapping interval, **and either its new value or its before-image satisfies φ** |

**Why gains and losses are both caught:** a family predicate's result changes over V only if some slot in the family changes its effective value somewhere in V, **either into the condition (gain) or out of it (loss).**
- Every effective-value change is made by some published claim in that slot over that time.
- That claim's new value is the value after the change; its before-image is the value before it.
- **One of the two satisfies φ.**

**The cases the review names:**

| Change since p₀ | Caught by |
|---|---|
| C moves from A to B | Before-image A satisfies "value = A" |
| A new sixth child of A | New value A |
| A code holder relinquishes X | Before-image X |
| A new holder takes X | New value X |
| A unit leaves or enters a class | Before-image or new value |
| A mandate withdrawn or rescoped | Slot dependency on the mandate |
| A claim withdrawn or superseded | It is a claim in the slot: slot or family dependency |
| A dispute-state change on relied-upon evidence | Slot dependency on (DISPUTE, claim) |
| A valid-time interval changed so that a fact no longer matches | The shortening is a claim (or a withdrawal) over the removed interval; its before-image matches |

**Not stale:** claims after p₀ in other families, or outside the dependency intervals. For example, a publication in another country, or an unrelated slot of the same unit.

### 5.2 Invariant-to-dependency matrix

| Invariant | Information read | Dependencies recorded | Phantom gain or loss caught by |
|---|---|---|---|
| **G-4** tiers strictly increase along containment | For each containment claim u → P over V: (CLASSIFICATION, u) and (CLASSIFICATION, P) over V. For each classification change of u: u's parent's classification and **u's children's** classifications over V | Slots: those classification slots. Family: (CONTAINMENT, *, S) with value = u | Family before-image or new value = u |
| **G-5** ended places name successors; no live child under an ended parent | (EXISTENCE, successors); children of the ended unit over the ended range | Slots: successors' existence. Family: children of the ended unit | Family |
| **G-6** one meaning per tier; no class ends while units carry it | Profile slots; units with class c over V | Slots: profile. Family: (CLASSIFICATION, *, S) with value = c | Family |
| **I-1** one holder per (namespace, value) | Holders of X in N over V | Family: (IDENTIFIER, *, N) with value = X | Family |
| **I-2** one value per (subject, namespace) | (IDENTIFIER, subject, N) | Slot | Slot |
| **I-4** establish needs an instrument or a duplicate search | Recorded in the revision | None (internal to the revision) | — |
| **Existence of parents and successors** | (EXISTENCE, P) | Slot | Slot |
| **Admission before grouping** (D-86) | (ADMISSION, entry) | Slot | Slot |
| **Evidence basis** | (MANDATE, m), (PRECEDENCE, r), (COMPETENCE, issuer, kind), (DISPUTE, relied-upon claim) | Slots | Slots |
| **Reconciliation** | All slots of subjects A and B | Family: every kind with subject ∈ {A, B} (a subject family) | Family |
| **D-83, D-82** (tier kind, territorial competence) | The revision's own claims and the competence registry | Slots (competence) | Slot |

**Where the proof stops [TU]:** completeness holds **if** every invariant check is implemented exactly through the dependencies in this matrix. **Implementation must enforce it structurally**: the validator may read published state only through a dependency-recording query interface, and tests assert that no other read path exists.

### 5.3 Stale means re-prepare

A stale revision is never re-validated silently. A human reconsiders it (for example, to include a newly arrived child).

## 6. Publication ordering and `closedThrough` [TD]

**Mechanism:** one **sequence row** (a single row holding the last committed position), not a PostgreSQL sequence object.

**The publication transaction:**
1. **Lock** the sequence row (`SELECT … FOR UPDATE`). The lock is held **until commit or rollback**.
2. Re-check governance (§8).
3. **Staleness check (§5):** published claims with position in (p₀, row value] matching the revision's predicates. Full validation already ran before the lock.
4. Set `p = value + 1`.
5. Insert the publication entry (p, revision, `recordedAt` = **max(statement clock, previous `recordedAt` + δ, W + δ)**, where δ is the smallest timestamp step, one microsecond for PostgreSQL `timestamptz`).
6. Update the row to p.
7. Commit; the lock is released.

**Properties:**
- **Positions are allocated only while holding the lock** and become visible only at commit. A waiting publisher acquires the lock after the commit and reads the **committed** value, so **a lower position can never commit after a higher one, and there are no gaps.**
  - [TU] This assumes the `READ COMMITTED` behaviour of `FOR UPDATE` re-reading the latest committed row. Under stricter isolation, the waiter would receive a serialization failure and must retry. **Verify during implementation.**
- **Rollback:** the row update rolls back; the position is **reused** by the next publication. A position has meaning only once committed; no one ever saw the failed one.
- **Rejected: a PostgreSQL sequence object.** It is non-transactional: it leaves gaps, and values can commit out of order.

**`closedThrough`:**
- = the sequence row's committed value.
- **Guarantee:** every publication with position ≤ `closedThrough` is committed and visible, and **no future publication can obtain a position ≤ `closedThrough`.**

**Consistent historical queries:** a reader, within **one snapshot** (one statement, or a `REPEATABLE READ` read-only transaction):
1. reads `closedThrough`;
2. answers from entries with position ≤ the requested p, **which must be ≤ `closedThrough`**, otherwise the query is refused (CR-01 §3).

Because there are no gaps below `closedThrough`, the answer is complete. An uncommitted publication is invisible.

**Work before and under the lock** [clarified in v3.2]:

| Phase | Work |
|---|---|
| **Before the lock** (any duration) | Evidence, authenticity and applicability assessments; approvals; **full validation** against the published state at p₀; recording of the dependency predicates |
| **Under the lock** (short) | Re-check governance (roster, threshold, conflict declarations, §8) · **staleness check:** claims with position in (p₀, current] matching the predicates · if stale, roll back and mark `Stale` · otherwise append the entry, advance the row, commit |

- **The critical section contains only the delta check**, so a large revision (Nepal 2015) validates for as long as it needs before the lock, and holds the lock only briefly.
- **A long-running preparation never blocks other countries.** Under the lock, unrelated revisions only queue for the duration of a delta check.
- **Several partitions:** the predicates span every partition the revision touches. **An invariant violation in any partition rejects the whole revision** (all or nothing).

**Knowledge tokens: position and time** [corrected in v3.2]

v3.1 compared `closedThrough` (a position) with τ (a time). **That compared two different kinds of value.**

- **Inside Geography, the authoritative token is the position.**
- **Across contexts, D-73 requires "one shared τ per multi-context operation", and CR-01 defines τ as a time:** knowledge = facts with `recordedAt ≤ τ`; `closedThrough` is a time beyond which queries are refused. Geography must therefore also offer **completeness as a time.**
- **Mechanism: a time watermark W, inclusive** [corrected in v3.3]. W is kept in the sequence row and **changed only under the publication lock**.
  - **Close through now:**
    1. take the lock;
    2. set `W = max(statement clock, W, last recordedAt)`;
    3. commit;
    4. return W.

    **Only a committed close is a confirmation.** If the close rolls back, W is unchanged and nothing is confirmed.
  - **Every later publication** records `recordedAt = max(statement clock, previous recordedAt + δ, W + δ)`, which is **strictly greater than W** and than every earlier `recordedAt`.
  - v3.2's `max(clock, previous, W)` could give exactly W, as the review showed. That is corrected.
  - **Guarantee, inclusive:** once a close through W is confirmed:
    - (1) every publication with `recordedAt ≤ W` is committed and visible;
    - (2) **no later publication can receive `recordedAt ≤ W`**, because it takes the lock after the close committed and is assigned at least W + δ;
    - (3) a query at τ ≤ W is therefore complete.
  - **The edge cases hold by construction:**
    - **a clock equal to W or behind it** gives W + δ;
    - **repeated closes** never lower W;
    - **concurrent publishers** serialise on the lock;
    - **many publications within one δ** take consecutive steps, so `recordedAt` behaves as a **logical clock** (physical time with increments).
  - **[TU] precision:** δ must be the true resolution of the stored timestamp type, one microsecond for PostgreSQL `timestamptz`.
  - **`closedThroughTime` = W.** This is the same inclusive meaning CR-01 gives Membership's `closedThrough`: "every fact with `recordedAt ≤ closedThrough` is committed and visible, and no fact with `recordedAt ≤ closedThrough` can still be committed later".
- **A multi-context operation at τ:**
  1. asks each context to be closed through τ (Membership's `closedThrough` ≥ τ; Geography's W ≥ τ, advancing W on demand);
  2. **each context answers from its own facts with recorded time ≤ τ.** Geography maps τ to "the highest position with `recordedAt` ≤ τ", which is monotonic by the clamp.
- **What this guarantees:** **each context's answer is complete as of τ on its own recorded-time scale.** It does not guarantee causal order between two contexts' facts recorded at nearly the same instant.

**Platform knowledge-time contract** (a platform-architecture requirement; Geography meets it but cannot impose it) [AC]. Every context that offers knowledge "as at τ" for D-73 operations must provide:
1. **`recordedAt` taken from the platform database clock** as its physical component;
2. **strictly increasing `recordedAt` within its own stream**, never at or below its confirmed watermark;
3. **an inclusive `closedThrough` time**, advanced only under exclusion from its writers, with CR-01's meaning;
4. **"close through τ" on request**, failing rather than confirming if it cannot commit;
5. **refusal** of a query beyond its `closedThrough`.

Geography satisfies 1–5 (§6). **Membership's CR-01 barrier must be checked against items 2 and 4** (its writers versus the exclusive close, model document §38.2). [TU] **A clock jumping backwards between two contexts' publications does not break completeness**, because each context clamps its own stream. It only blurs the cross-context order of near-simultaneous facts, which the contract does not promise.

## 7. Boundaries: ordering versus consistency [TD]

| Concern | Element | Kind |
|---|---|---|
| Platform-wide knowledge order | **Publication sequencer** (sequence row plus the append-only publication stream) | Technical coordination mechanism: **its only invariant is gap-free, ordered positions** |
| Geographical invariants | **Partitions:** each **country** (its scheme, national namespaces, its mandates) and **global** (World, regions, level-2 entries, global namespaces ISO/M49, global registry, CLDR) | **Consistency scopes**, validated read-only during publication. **No mutable state of their own:** published claims and their positions are the state |
| A proposed change | **`Revision`** | **Aggregate:** internal completeness; frozen at submission for publication; holds its dependency predicates and p₀ |
| Inputs | `Candidate`, `Objection` | Aggregates |
| Governance | `StewardRoster` | Aggregate |
| Publishing | **`Publish` application service** | Takes the lock, validates the affected partitions, appends |

**What a publication writes:** the sequence row and one entry. **The revision's claims were already written as a draft;** "Published" is derived from the entry. **So no domain aggregate is modified across partitions.**

**Consequences:**

| Change | Validation reads | Writes |
|---|---|---|
| One country | That partition (+ global namespaces if identifiers) | One entry |
| Several countries (a border change) | Those partitions | **One entry** (atomic) |
| Global registry | Global partition | One entry |
| Unrelated countries concurrently | Each its own partition | **No domain coupling**; only queueing for the short lock |
| Historical reconstruction across partitions | One position | — |

**Rejected:**
- one domain aggregate containing all geography (v3 wording);
- per-partition sequences (they would need a cross-sequence watermark).

## 8. Governance (D-84, D-84a) [TD]

**Two roles, as D-84 and [AD] D-88 define them:**

| Role | Rule |
|---|---|
| **Assessing steward** | Prepares and assesses the revision; **submitting it freezes the content**. While only one steward exists, publishes alone, with a recorded conflict declaration (D-84) |
| **Second person** | Required for **structural** revisions when two or more stewards are appointed **at publication time** (D-84). **A natural person other than the assessing steward** (D-88), checked as a distinct canonical person; a steward, or a matter reviewer where D-84a applies |
| Matter reviewer | Appointed for one matter only (D-84a) |

**Checks:**

| When | Check |
|---|---|
| At submission and at the second person's approval | The acting person is appointed now; conflict declaration recorded; D-84a test (interest **and** judgment between competing positions) for **that person** |
| At publication | **Threshold** (two or more stewards now → structural needs the second person); **conflict declarations re-checked**; second person ≠ assessing steward (D-88) |
| Appointment ended after submission or approval | The act stands (it was valid when given) |
| New steward appointed after submission | A second person's approval is needed for structural revisions |

**Conflict found after publication**, three separate steps:
1. **Did a material conflict exist?** Decided by a non-conflicted steward or a matter reviewer.
2. **If it did, is the published decision substantively supportable?** An independent reassessment, recorded as an assessment (audit, not consumer-visible).
3. **If not supportable, a correction revision is published** through normal governance.

**The finding of conflict alone changes no fact.**

## 9. CLDR (D-87) [TD]

| Concern | Design |
|---|---|
| Source of a value | Display-name claim per (entry, **CLDR locale where the value is defined**, form), recording the **CLDR release** |
| Inheritance | Resolved at display time using **that release's** parent-locale data (stored as a claim) |
| Short form missing | Use the standard form |
| Requested locale and its chain have no value | **Root is not assumed to be English.** If the chain ends without a value, a **platform default locale** is used. This is a **presentation default** (D-87 yields nothing there) [TD]; the platform default is configuration |
| Returned to the caller | `{ value, origin ∈ {CLDR, PLATFORM_FALLBACK}, requested locale, resolved CLDR locale (if CLDR), CLDR release (if CLDR), form used, short form fell back to standard (yes/no) }`. **A platform fallback is never presented as a CLDR result** [v3.2] |
| History | Belief at p over display-name and inheritance slots gives exactly what was shown then |
| CLDR update | A name revision; non-structural (D-84); one steward |

**Not decided (kept open):** names of the five regions; names of territorial units (§11).

## 10. Contexts [AC]

| Capability | Owner |
|---|---|
| Published places, claims, history; browse and search (entity type, tier kind, live at t, p); **`PlaceLookup`** (stateless match of name, alias or code to places, with match quality; **no address parsing, street matching or validation**) | Geography |
| Coverage selection and rules; organisation areas | **Organisation** |
| Address records, parsing, street matching, stored results with the Geography p used | Address owner; **Organisation's person profile recommended, not ratified** |
| Belonging, entitlement, sovereignty | Not Geography |

**Events:** `RevisionPublished` (position, revision, kinds, partitions, subjects, reconciliation mappings), one per position; `CandidateDecided`; `ObjectionDecided`.

**Geography read models** (all parameterised by t and p): browse and search; profile; hierarchy; belief; display-name resolution; partition views.

## 11. Open items

| Item | Kind |
|---|---|
| Names of the five level-1 regions | [OQ] |
| Names of territorial units below level 2 | [OQ] |
| L-4c; Japanese blocks | [OQ], later |
| **Q-2 (recorded, not lost):** should Geography represent official schemes other than the administrative one, such as electoral constituencies? (Geography discovery revision 2, §9 question 2 and R2-13) | [OQ], later; does not affect the baseline |
| Address owner | Recommendation |
| `FOR UPDATE` re-read under the chosen isolation; structural enforcement that the validator reads only through dependency-recording queries (§5.2); timestamp resolution δ; Membership's conformance to the platform knowledge-time contract (§6) | [TU] |
| Primary verification of historical examples | Evidence |
