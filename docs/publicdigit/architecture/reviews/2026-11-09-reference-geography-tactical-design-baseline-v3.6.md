# Reference Geography: tactical DDD design baseline, version 3.6 (proposed)

| | |
|---|---|
| **Status** | PROPOSED, for the Principal DDD Architect's independent assessment. Self-contained (earlier versions not needed). No code, schemas or tickets. Not committed |
| **Supersedes** | v3.5 (kept as design evidence) |
| **Companion** | `2026-11-09-reference-geography-tactical-design-v3.6-assessment.md` |
| **Changed in v3.6** | §8 **D-84 threshold modelled as a roster fact**, with the two readings of D-84 put to the Principal (one narrow [OQ]) · §8 **roster and publication serialised on one lock;** each publication records the roster version it used · §12 reconciliation result carries the **publication position**, and the reconciliation is atomic · §6 the exact meaning of the clock reading versus commit versus position · §13 territorial tier versus address component versus official unit |
| **Changed in v3.5** | §6 the **authoritative definition of `recordedAt`** quoted and applied · §12 **reconciliation** as a first-class fact · §13 **classification profiles** as a domain model · §14 **structural versus non-structural** changes · §8 conflict of interest **by role** (preparing versus assessing and deciding) · §9 the platform default locale as a **published fact** · §15 the **write paths** inside and outside the knowledge stream · §16 aggregate review |
| **Previously** | v3.4: refuse, don't bump; close through a requested τ; piecewise before-images; exact queries. v3.3: slot uniqueness; set-valued names; matrix; D-88 |
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
| **Reconciliation** [v3.5, §12] | (RECONCILIATION, identity) | Assessment (identity judgement) with evidence |
| **Profile: designated scheme** [v3.5, §13] | (PROFILE_SCHEME, country entry) | Territorial |
| **Profile: tier meaning** [v3.5] | (PROFILE_TIER, country entry, scheme, position) | Territorial |
| **Profile: class position** [v3.5] | (PROFILE_CLASS, country entry, scheme, class) | Territorial |
| **Platform default locale** [v3.5, §9] | (PLATFORM_DEFAULT_LOCALE) | Platform configuration, published |
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
| RECONCILIATION | Where **one identity** resolves to | Other identities | Its own earlier mapping (including reversal to NONE) |
| PROFILE_SCHEME | A country's designated scheme | — | Itself |
| PROFILE_TIER | **The meaning of one position** in one scheme (enforces "one meaning per position" structurally) | Other positions | That position |
| PROFILE_CLASS | **The position of one class** | Other classes (several classes may share a position) | That class |
| PLATFORM_DEFAULT_LOCALE | The platform fallback locale | — | Itself |

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

**Before-image, piecewise [TD, refined in v3.4]:** a claim over interval I in slot s carries **a partition of I into segments, each with the effective value it replaced there**: `[(I₁, v₁), (I₂, v₂), …]`.
- Each `vᵢ = belief(s, t, p₀)` for every t in Iᵢ. A segment boundary falls wherever the prior effective value changes.
- **Three kinds of prior value are distinguished:**
  - a **value**;
  - **NONE**: a prior withdrawal was in force;
  - **ABSENT**: no claim had ever covered that time in that slot.
- **Example:** C belonged to A over [Jan, Jul) and to B from Jul. A correction over [Mar, Sep) to parent D carries `[([Mar, Jul), A), ([Jul, Sep), B)]`. It is therefore a **loss** for "children of A" over March–June **and** for "children of B" over July–August, and a **gain** for "children of D".
- **Partial replacement:** a claim covering part of an earlier claim's interval supersedes it **only there** (§4). Its before-image covers only its own interval.
- **Withdrawal:** a claim with value NONE over its interval; its before-image shows what was withdrawn.
- **One slot, one revision [new invariant R-1]:** a revision may not contain two claims for the same slot with overlapping intervals. So each claim's before-image is computed against p₀ alone, never against a sibling claim. Disjoint claims in one slot are allowed.
- **Computed at validation against p₀; protected** by the revision's slot dependencies on its own target slots over their intervals. Any intervening claim there makes the revision stale, and the before-images are recomputed on re-preparation.
- **Stored with the claim forever.** **Invariant for implementation tests:** a stored before-image equals the belief at the position immediately before its publication, so it can always be checked against history.

**Two dependency forms**, recorded at validation with the position p₀:

| Form | Meaning | Invalidated by any claim published after p₀ that… |
|---|---|---|
| **Slot dependency** (a named slot over an interval) | "Mandate M at t", "the dispute state of claim X", "(EXISTENCE, P) over V" | …is **in that slot** with an overlapping interval, **whatever its value** |
| **Family dependency** (all slots of one kind, with a value condition φ, over an interval) | "children of A in S over V" = slots (CONTAINMENT, *, S) with value A; "holders of X in namespace N" = (IDENTIFIER, *, N) with value X | …is **in the family**, and **within its interval ∩ V either its new value satisfies φ, or some before-image segment overlapping V has a value satisfying φ** |

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

**Gains and losses by kind (v3.4):**

| Kind | Gain detected by | Loss detected by |
|---|---|---|
| Containment | New value = A | Before-image segment = A |
| Classification | New value = c | Before-image segment = c |
| Identifier | New value = X | Before-image segment = X |
| Mandate, competence | **Slot dependency: any claim in the slot** (creation, rescoping, withdrawal) | Same |
| Source name, set-valued | A new presence claim in the value's slot | A withdrawal (NONE) whose before-image shows presence |
| Dispute state | Slot dependency on (DISPUTE, claim): raised or cleared | Same |

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

**Exact queries (v3.4).** Each is a dependency-recording read over valid interval V at p₀:

| Invariant | Query | Dependency recorded |
|---|---|---|
| G-4 (new containment u → P over V) | `classOf(u, S, V)`, `classOf(P, S, V)` | Slots (CLASSIFICATION, u, S) and (CLASSIFICATION, P, S) over V |
| G-4 (classification change of u over V) | `parentOf(u, S, V)` and its class; `childrenOf(u, S, V)` and their classes | Slots: (CONTAINMENT, u, S), the parent's classification. Family: (CONTAINMENT, *, S) = u over V. Slots: each child's classification |
| G-5 (end of u at d) | `childrenOf(u, S, [d, ∞))`; `exists(successor)` | Family (CONTAINMENT, *, S) = u over [d, ∞); slots (EXISTENCE, successor) |
| G-6 (end of class c) | `unitsOfClass(c, S, V)` | Family (CLASSIFICATION, *, S) = c over V |
| I-1 (u takes X in N) | `holdersOf(N, X, V)` | Family (IDENTIFIER, *, N) = X over V |
| I-2 | `valueOf(u, N, V)` | Slot (IDENTIFIER, u, N) over V |
| Evidence | `mandate(m, t)`, `competence(issuer, kind, t)`, `rule(r, t)`, `dispute(y)` | Slots |
| Reconciliation of A, B | `allSlots(A)`, `allSlots(B)` | Subject families for A and B over all time |
| Multi-country | The union of the above for every claim, in every partition touched | — |

**A phantom insertion is a gain and a phantom removal is a loss of a family result. Both are caught by the family rule.**

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
5. Read `c = clock_timestamp()` (the database clock). **If `c ≤ W` or `c < previous recordedAt`: refuse.** Roll back with `CLOCK_NOT_PAST_BOUNDARY` and retry later. **Otherwise** insert the publication entry (p, revision, `recordedAt = c`). [v3.4: **the timestamp is never adjusted**]
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
- **Mechanism: a time watermark W, inclusive, proved by transaction ordering** [corrected in v3.4]. W is kept in the sequence row and **changed only under the publication lock**.
- **The authoritative texts (verified 2026-11-09)** [v3.5]:

  | Text | Exact wording |
  |---|---|
  | **Ratified Membership architecture** (2026-10-08), facts | "`recordedAt` (system-of-record time)" |
  | **Ratification readiness record**, the three clocks (resolved) | "`recordedAt` = when the fact entered PublicDigit's authoritative record; **not** DB `created_at`, queue time or audit timestamps (**implementations may realise it from the commit time**)" |
  | Ratified architecture, final | facts need "a **total recording order** … (ties in `recordedAt` broken by a sequence)" |
  | **CR-01 §3** | `closedThrough`: every fact with `recordedAt ≤ closedThrough` committed and visible; none can still be committed later; refusal beyond it |
  | **CR-01 §8** | taking `recordedAt` "from the application clock before commit" is a defect |
  | **D-73 (CR-01 §9)** | "the `closedThrough` guarantee **proved by transaction ordering, not by moving timestamp generation**" |
  | **The phrase "database clock time at commit"** | **Not found in any accepted text.** It appears only in this design series' own earlier drafts |

- **What these require:**
  - (a) `recordedAt` is **the moment the fact entered the authoritative record**, and an implementation **may** take it from the commit time;
  - (b) a total recording order, **ties allowed and broken by a sequence**;
  - (c) completeness proved by transaction ordering, not by moving timestamps.
- **v3.4's realisation meets all three** [AC]:
  - (a) **the record is entered by the serialised publication step**, and `recordedAt` is the database clock reading taken inside that step, immediately before its commit. Commit time is permitted, not required;
  - (b) positions give the total order and allow ties;
  - (c) the close protocol and the refusal rule, with **no timestamp ever moved.**
- **No ambiguity remains for a Principal decision** on `recordedAt`.
- **Precise semantics, without overstatement** [v3.6]:

  | Concept | Meaning |
  |---|---|
  | **System-of-record time** (`recordedAt`) | The **database clock reading taken inside the serialised publication step**, after all checks and immediately before commit |
  | **Commit and visibility** | **Strictly later than that reading.** The entry becomes visible at commit. **The reading is not the literal commit timestamp** |
  | **Publication position** | The **total order** of publications. Ties in `recordedAt` are possible and ordered by position |
  | **`closedThrough` (W)** | **Inclusive:** every entry with `recordedAt ≤ W` has committed; no later entry can have `recordedAt ≤ W` |

- **Guarantee:** for any τ ≤ W, the set of publications with `recordedAt ≤ τ` is final and complete, and ordered by position.
- **Assumptions:**
  - a lock held to commit;
  - one database clock;
  - the refusal rule;
  - host time kept by slewing.
- **Cross-context causal consistency is not claimed.**
- **v3.3 moved timestamps** (`max(clock, previous + δ, W + δ)`). v3.4 withdrew that. **No timestamp is ever adjusted;** a write whose clock reading would fall at or below the boundary is refused.

**Rules:**

| Operation | Rule |
|---|---|
| **Publication** | Under the lock: `c = clock_timestamp()`; **refuse if `c ≤ W` or `c < previous recordedAt`**; otherwise `recordedAt = c`. Equal readings at successive positions are allowed: **positions order them** |
| **Close through a requested τ** | (1) If `τ ≤ W` already: **confirmed**, no lock needed (W only grows and is committed). (2) Otherwise take the lock, which waits for any in-flight publication to commit or abort. Read `c = clock_timestamp()`. **If `c < τ`: refuse** (`BOUNDARY_NOT_CLOSED`: τ is in the future), and the caller retries later. Otherwise set `W = τ`, commit, **confirmed**. **A close that rolls back confirms nothing** |
| **Close through now** | Close through τ = the clock reading taken under the lock |
| **Query at τ** | If `τ ≤ W` (in the reader's snapshot): answer from entries with `recordedAt ≤ τ`; Geography maps τ to the highest position with `recordedAt ≤ τ`. **Otherwise** attempt to close through τ; refuse if that fails (CR-01 §3) |

**Proof by transaction ordering (inclusive guarantee):**
- (1) **Every publication with `recordedAt ≤ W` is committed:** a publication holds the lock from reading its clock until it commits, and the close took the same lock afterwards. So every publication that read a clock before the close committed or aborted before W was set.
- (2) **No later publication can get `recordedAt ≤ W`:** it takes the lock after the close committed, sees W, and **is refused unless its clock reading is greater than W.**
- (3) **No timestamp is ever adjusted.** `recordedAt` is the database clock reading taken inside the serialised publication step, immediately before commit.
- **The order of `recordedAt` values matches commit order:** steps are serialised and readings are non-decreasing, or the write is refused.
- [TU] **Literal commit time:** PostgreSQL's actual commit timestamp cannot be read inside the committing transaction (`track_commit_timestamp` is only available afterwards). "Database clock at commit" is therefore realised as **the reading inside the serialised step that commits**. This is the faithful realisation available; v3.4 states it rather than assuming it.

**The clock moving backwards:**
- Publications are **refused** until the clock passes max(W, last `recordedAt`).
- **Completeness is never violated; availability is reduced during the step.**
- [TU] An operational requirement: the database host keeps time by **slewing**, never by stepping backwards (normal NTP practice), so refusals stay rare and brief.

**A multi-context operation at τ (D-73):**
1. the coordinator chooses **one τ** that is not in the future;
2. **asks every context to close through that same τ**: no context may substitute its own time;
3. if **any** context refuses, the operation waits and retries, or reports `BOUNDARY_NOT_CLOSED`. **It never proceeds with a partial set;**
4. each context then answers at τ.

**What D-73 then guarantees:** **each context's answer is complete for facts it recorded at or before τ.** It does **not** guarantee causal consistency between contexts (for example, that a Membership fact recorded at 11:59:59 "saw" a Geography fact recorded at 11:59:58). v3.4 claims no more.

**Platform knowledge-time contract** (a platform-architecture requirement; Geography meets it but cannot impose it) [AC]. Every context that offers knowledge "as at τ" for D-73 operations must provide:
1. **`recordedAt` = the platform database clock reading** taken inside the writing transaction while holding the context's writer lock; **never adjusted**;
2. **writes refused** whose reading is at or below the context's confirmed boundary;
3. **an inclusive `closedThrough` time**, advanced only by a close that excludes in-flight writers (waits for them), with CR-01's meaning;
4. **"close through τ" for a requested τ**, refusing if τ is in the future or the close cannot commit;
5. **refusal** of a query beyond `closedThrough`.

**Geography satisfies 1–5.**

**Finding for the platform [AC]:** the proposed barrier for Membership, Organisation and Governance (model document §38.2 rule 1) takes `recordedAt` as `max(clock_timestamp(), closedThrough + 1 µs, last per stream + 1 µs)`. **That moves timestamps**, the same pattern v3.4 withdraws for Geography.
- **Under the strict reading of D-73 it would need the same "refuse, don't bump" correction.**
- **This review does not change Membership's design** (out of scope; ratified architecture untouched). It **records the inconsistency for the Membership tactical work.**
- **Clock moving backwards between contexts:** each context refuses writes below its own boundary, so completeness holds in each. Only the cross-context order of near-simultaneous facts is unspecified, and D-73 does not promise it.

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
| **Preparer** [v3.5] | **Collects evidence and drafts claims. Decides nothing.** May be any steward, **even a conflicted one** |
| **Assessing steward** | **Assesses** the evidence (authenticity, competence, applicability) and **submits** the revision (which freezes it). While only one steward exists, also publishes (D-84). **Assessing and submitting are decisions** under D-84a. Usually the same person as the preparer |
| **Second person** | Required for **structural** revisions (§14) when two or more stewards are appointed **at publication time** (D-84). **A natural person other than the assessing steward** (D-88), checked as a distinct canonical person; a steward, or a matter reviewer where D-84a applies |
| Matter reviewer | Appointed for one matter only (D-84a); may act as assessing steward or as second person for that matter |

**Conflict of interest by role (D-84a applied exactly)** [v3.5]:
- **D-84a forbids deciding,** where a material conflict exists: an organisational interest **and** a judgment between competing positions.
- **The preparer** does not decide, so D-84a does not bar preparation.
- **The assessing steward and the second person** decide, so neither may be materially conflicted in that matter.
- **If the only steward is conflicted** in a judgment matter, the matter waits for a non-conflicted steward or a matter reviewer (D-84a). The conflicted steward may still prepare the evidence.
- **Before D-88 applies,** the conflict test runs for the assessing steward. **After it applies,** it runs for both the assessing steward and the second person.
- **Routine publication** (no judgment between competing positions) needs only the declaration (D-84a).

**Checks:**

| When | Check |
|---|---|
| At submission and at the second person's approval | The acting person is appointed now; conflict declaration recorded; D-84a test (interest **and** judgment between competing positions) for **that person** |
| At publication | **Threshold** per the D-84 reading in force (below); **conflict declarations re-checked**; second person ≠ assessing steward (D-88) |
| Appointment ended after submission or approval | The act stands (it was valid when given) |
| New steward appointed after submission | A second person's approval is needed for structural revisions (both readings) |

**The D-84 threshold [v3.6]:** D-84 contains two clauses that diverge once a second steward has **left**.
- "**While only one steward exists**, that steward may publish all changes …"
- "**From the appointment of a second steward**, every structural change requires approval by a second, independent natural person; if none is available, the change waits."

| Reading | Rule | After the second steward's appointment ends |
|---|---|---|
| **L (latching)** | The requirement **activates at the first appointment of a second steward and stays active** | Structural changes still need a second person; **they wait** until one is available (another steward, or a person appointed for the matter) |
| **C (current count)** | The requirement applies **while two or more stewards are appointed** | The sole remaining steward may publish structural changes alone again ("while only one steward exists") |

**Modelling (works for both readings):**
- the roster holds the fact **`secondStewardEverAppointed`**, set once, never unset;
- **reading L** uses that fact; **reading C** uses the current count;
- **which reading applies is the Principal's ruling ([OQ], assessment §5).** The design implements whichever is chosen.

**Roster and publication ordering [v3.6]:**
- **Every roster write** (appointment, end, matter-reviewer appointment, conflict declaration) **takes the same publication lock** and receives a **roster version** (its own gap-free counter, in the same row).
- **Approvals record the roster version they were given under.**
- **Publication, inside the lock,** re-reads the roster at its current version: threshold, appointments, conflict declarations. **The publication entry records the roster version used.**
- **Consequences:**
  - **a concurrent appointment can never be missed.** It either committed before the publication took the lock, so it is seen, or it waits for the lock, so it comes after the publication and applies to later publications only;
  - a conflict declaration made concurrently is ordered the same way;
  - **every historical publication can be explained** by the roster version it recorded.
- The roster **stays outside the knowledge stream** (§15). It only shares the lock for ordering.
- [TU] Roster writes are rare and short, so contention is negligible.

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
| Requested locale and its chain have no value | **Root is not assumed to be English.** If the chain ends without a value, the **platform default locale** is used. **Since v3.5 the default is a published fact** (slot PLATFORM_DEFAULT_LOCALE, global partition; a change is a non-structural revision). **The fallback is therefore reproducible at any p**, and the result records the default locale used. D-87 yields nothing at that point; CLDR's value in the default locale is still a CLDR value, but the result is marked `PLATFORM_FALLBACK` because the user's chain produced nothing |
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

## 12. Reconciliation (first-class fact) [TD, v3.5]

| Element | Definition |
|---|---|
| Fact | Claim in slot (RECONCILIATION, B): value = canonical identity A, or **NONE** (unmapped) |
| Time | **Valid time is all of time** `(-∞, ∞)`: an identity judgement concerns what the entity always was. **Belief changes only by knowledge** (position). A reversal is a later claim with NONE |
| **R-2: one level only** | A mapping must point to an **unmapped** identity. When A is itself reconciled into C, the same revision **re-maps every B with B → A to C**. **No chains, so no cycles**; resolution is a single step |
| Many to one | Allowed (B → A, D → A) |
| **Conflicting slots** | The reconcile revision must include, for every slot where A and B conflict over overlapping valid time, **a claim on A with the merged value**. **B's identifier claims are withdrawn** where they would break I-1 or I-2 for the canonical identity. **B's claims stay recorded**; belief for B now resolves through A |
| **Reversal** | A revision with (RECONCILIATION, B) = NONE, **plus** the claims that restore B's slots and correct A's merged slots. Prepared by a human; validated like any other revision |
| **Consumers** [v3.6] | `resolve(B, p)` → `{ canonical: A, knownAtPosition: p_m, recordedAt, revision }`, or `{ canonical: B }`. **`knownAtPosition` is authoritative**; `recordedAt` is supporting information, since ties are possible. `RevisionPublished` carries the mapping or its reversal. **Earlier decisions stay explainable**: each recorded the p it used, and belief at that p is unchanged |
| **Atomicity** [v3.6] | **One reconcile revision carries everything:** the mapping, merged claims on A for every conflicting slot, re-mapping of every incoming identity, and withdrawals of B's conflicting identifiers. **It becomes visible through one publication entry.** A reader at any position sees either none of it or all of it |
| **Coverage of the dependency model** [v3.6] | Subject families of A and B (every slot, every kind, all time); slots (RECONCILIATION, A) and (RECONCILIATION, B); family (RECONCILIATION, *) with value ∈ {A, B}; the identifier families for every (namespace, value) held by A or B (I-1 after the merge). **Any concurrent change to any of these makes the revision stale** |
| **Chained case** [v3.6] | B → A published; then A into C. **The A → C revision must re-map B → C in the same revision** (R-2). Its family dependency on mappings with value A catches any incoming mapping added concurrently |
| **Reversal proof** [v3.6] | A reversal is a new revision: (RECONCILIATION, B) = NONE, plus claims restoring B's slots and correcting A's merged slots. **Earlier positions are untouched**, so belief at any p before the reversal still shows B → A. Decisions taken then remain explainable by their recorded p |
| **Dependencies** | Slots (RECONCILIATION, A) and (RECONCILIATION, B); family (RECONCILIATION, *) with value ∈ {A, B} (incoming mappings); subject families of A and B. **A concurrent reconciliation touching A or B makes the revision stale** |
| Identity rule | No `ReferenceUnitId` is deleted or reused (I-3) |

## 13. Classification profiles (D-81 as a domain model) [TD, v3.5]

- **A profile = the published claims for one country-or-area entry and one scheme:**
  - (PROFILE_SCHEME, country) names the **designated reference scheme** (the one carrying positions 3–10 for addresses and committees);
  - (PROFILE_TIER, country, scheme, position) gives **the meaning of a position**, as a tier label and a tier kind (TERRITORIAL or ADDRESS_COMPONENT, D-83);
  - (PROFILE_CLASS, country, scheme, class) gives **the position of a class**.
- **Ownership:** Geography, global partition for the scheme choice, country partition for tiers and classes. Published on territorial competence evidence under D-84 (structural, §14).
- **Constraints:**

  | Rule | Enforced by |
  |---|---|
  | **One meaning per position** | One PROFILE_TIER slot per position |
  | **One position per class** | One PROFILE_CLASS slot per class |
  | **A class must sit at a TERRITORIAL position** to be used in CLASSIFICATION claims (D-83) | Validation |
  | **Three things never confused** [v3.6]: (a) a **territorial tier** (holds official units with identities); (b) an **address-component tier** (a position only, used to rank address precision; **holds no units, no identities, no claims**); (c) an **official geographical unit** (a `ReferenceUnitId` with claims) | No CLASSIFICATION claim may name an address-component tier; **no `ReferenceUnitId` can be minted for an address component**; address precision reports positions, never identities |
  | **Skipped positions** | Positions without a tier |
  | **Positions 0–2 fixed** | World, region, country or area; not profile slots |

- **Derived position of a unit:** `position(u, t, p) = PROFILE_CLASS(country(u, t), scheme, class(u, t))`, where `country(u, t)` is u's level-2 ancestor through containment at t.
- **G-4 (derived):** for every containment u → P at t, `position(P) < position(u)`. **Never computed by counting parents** (D-81).
- **G-6 (derived):**
  - a PROFILE_TIER or PROFILE_CLASS claim may not end, move or change kind while it would break G-4 or leave units classified into a class without a position. Its dependencies are the family (CLASSIFICATION, *, scheme) with value = c, plus those units' containment edges;
  - **a change of a class's position** must keep G-4 for every containment edge involving units of that class, over the changed interval. Dependencies: the same family, plus parents and children of those units.
- **History:** belief over the profile slots at (t, p) gives the profile in force and known. Nepal's 2015 change is one revision changing tiers and class positions, plus the units' containment and classification.
- **No actual country profile is decided here.** The Nepal, Germany and Japan tables elsewhere remain illustrations.

## 14. Structural versus non-structural changes (for D-84's second person) [AC, v3.5]

- **Basis:** D-84 ("every structural change requires approval by a second, independent natural person") and the list put to the Principal with S-1 (strategic baseline, S-1 dossier, S1.2 question 3): "existence, containment, succession, classification tier, boundary, country profiles, authority and mandate records, precedence rules, same-as retirements, and corrections that alter any of these or their effective dates; non-structural: names, codes, external identifiers, aliases, descriptive text".
- **Status of that list:** it was the proposed answer in the dossier; D-84 itself does not enumerate. **Applied here as the architectural reading, extended by analogy:**

| Kind | Classification | Reason |
|---|---|---|
| EXISTENCE, CONTAINMENT, CLASSIFICATION, SUCCESSION, BOUNDARY | **Structural** | Dossier list |
| PROFILE_SCHEME, PROFILE_TIER, PROFILE_CLASS | **Structural** | "Country profiles" |
| ISSUER, MANDATE, COMPETENCE, PRECEDENCE | **Structural** | "Authority and mandate records, precedence rules"; competence is an authority record |
| RECONCILIATION | **Structural** | "Same-as retirements" |
| ADMISSION | **Structural** | It makes a level-2 entry exist (analogous to existence) |
| IDENTIFIER, SOURCE_NAME, CLDR_NAME, CLDR_INHERITANCE | Non-structural | "Names, codes, external identifiers, aliases"; D-87 explicitly calls a CLDR name revision non-structural |
| GROUPING (M49 region) | Non-structural | A statistical grouping, explicitly **not** containment (D-85), with no use in coverage (§10) |
| DISPUTE (marker or clearance) | Non-structural | It changes no fact (§4). **D-84a still applies,** because a dispute is a judgment matter |
| PLATFORM_DEFAULT_LOCALE | Non-structural | Presentation |
| **Any correction that alters a structural fact or its effective dates** | **Structural** | Dossier list |
| StewardRoster records | **Outside D-84's scope** | Platform-owner acts (D-84: "the platform owner appoints"), not geographical publications |

- **Narrow residual [AC]:** GROUPING and ADMISSION are classified by analogy, not by explicit text. **If the Principal wishes otherwise, it is a one-line policy change, not an architectural one.** Listed in the assessment, not as a required decision.

## 15. Write paths: inside and outside the knowledge stream [AC, v3.5]

| Write | In the knowledge stream (`closedThrough`)? | Why |
|---|---|---|
| Publication of a revision (every claim kind in §3, dispute markers, reconciliation, profiles, registry, CLDR, platform default locale) | **Yes: the only path** | These are what consumers know as geography |
| Revision drafts, claims and before-images before publication | No | Invisible until a publication entry references them |
| Candidate lifecycle | No | Operational. Its effect on knowledge is only through a revision |
| Objection lifecycle | No | Same |
| StewardRoster appointments, ends, declarations | No: **a separate append-only governance record**, read "as at" approval and publication time | It decides who may act, not what Geography knows |
| Instruments and evidence | No | Stored before use; referenced by claims |
| Audit logs | No | Audit |

**Therefore the publication lock covers every write that changes geographical knowledge.** No other path can create, change or withdraw a published claim [AC]; implementation must enforce it structurally ([TU]: single write path).

## 16. Aggregate review [TD, v3.5]

| Element | Protects | Owns | Does not own |
|---|---|---|---|
| `Revision` (aggregate) | Internal completeness, R-1, frozen content, dependencies and before-images | Its draft claims, assessments, approvals | Visibility |
| `Candidate` (aggregate) | Submitted → UnderVerification → Accepted (references a revision) · Linked (duplicate of another candidate) · Rejected | Submission record | Published facts |
| `Objection` (aggregate) | Submitted → Assessed → Corrected · Disputed · Rejected; reconsideration only by a **new** objection with new evidence | Objection record | The target claim |
| `StewardRoster` (aggregate) | Appointments over time; matter reviewers; declarations | Governance record | Geography facts; personal data (only canonical-person references) |
| Publication sequencer | Gap-free ordered positions; W | The sequence row, entries | Domain invariants |
| Partitions | Consistency scopes (read-only) | — | Mutable state |
| `Publish` service | Coordination: lock, governance re-check, delta check, append | — | Business decisions |

**Governance facts are not in the publication model:** they govern who may act and are read "as at" the moment of acting. **Geographical facts are.**

## 17. Open items (renumbered from §11 in v3.5)

| Item | Kind |
|---|---|
| Names of the five level-1 regions | [OQ] |
| Names of territorial units below level 2 | [OQ] |
| L-4c; Japanese blocks | [OQ], later |
| **Q-2 (recorded, not lost):** should Geography represent official schemes other than the administrative one, such as electoral constituencies? (Geography discovery revision 2, §9 question 2 and R2-13) | [OQ], later; does not affect the baseline |
| Address owner | Recommendation |
| `FOR UPDATE` re-read under the chosen isolation; structural enforcement that the validator reads only through dependency-recording queries (§5.2); **a single write path for published claims (§15)**; host time kept by slewing (refusal frequency); Membership's conformance to the platform knowledge-time contract (§6) | [TU] |
| GROUPING and ADMISSION classified by analogy (§14) | Architectural reading; a one-line policy change if the Principal disagrees |
| Primary verification of historical examples | Evidence |
