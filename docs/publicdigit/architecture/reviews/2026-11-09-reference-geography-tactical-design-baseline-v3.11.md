# Reference Geography: tactical DDD design baseline, version 3.11 (proposed)

| | |
|---|---|
| **Status** | **RATIFIED** 2026-11-10 by the Principal/ARB (D-93; act `docs/publicdigit/reviews/2026-11-10-reference-geography-baseline-ratification-act.md`). Accepts the domain design only; PO-1 to PO-8 and the Identity contract remain open. No code, schemas or tickets. Self-contained (earlier versions not needed) |
| **Supersedes** | v3.10 (kept as design evidence) |
| **Strategic baseline** | `2026-11-09-reference-geography-strategic-baseline.md` (consolidated 2026-11-09) |
| **Companion** | `2026-11-09-reference-geography-baseline-coherence-assessment.md` |
| **Final validation (2026-11-10)** | §15 and §16 list the **D-90 governance notices** as stream entries (v3.11 contradicted itself) · §17 stale row closed · §6 heading |
| **Changed after the Principal's rulings** | **[AD] D-92: GROUPING non-structural** (§14) · PO-2 covers governance notices · **[AD] D-91: ADMISSION structural** (§14) · **[AD] D-90 (option K)** made normative in §8 case B · **§8 D-90 confirmation semantics** (what is confirmed, by whom, how recorded and shown) · §10 `GovernanceNoticePublished` |
| **Changed in v3.11** | §5.3 **content version covers everything approved;** evidence versions immutable · §8 **Identity merges, five cases;** the effect of a defective approval on published facts is **[OQ] for the Principal**, not derived · §14 GROUPING reasoning corrected (regions can be referenced in coverage) |
| **Changed in v3.10** | §8 **approvals bound to a content version;** re-preparation voids earlier assessments and approvals; `excluded(r)` accumulates over every version · §8 **Identity merge procedure** and the contract Geography needs from Identity · §6 **proof obligations** for the implementation architecture, each with its failure criterion |
| **Changed in v3.9** | §8 **one D-88 rule, `excluded(r)`, used by the role table, the combinations, approval and the publication invariant** · §8 era wording for the conflict test · §8 and §15 **person references:** how the roster refers to people under D-75 and XD-3 · binding list adds D-4, D-5, D-22 |
| **Changed in v3.8** | §8 **the D-84 rule by era** (before and after the threshold), D-89 wording made consistent · §8 **permitted combinations of preparer, assessing steward, second person and matter reviewer** under D-88 · §6 temporal proof **not dependent on the length of the gap** between the reading and the commit; tests added · §10 **belonging owned by Organisation** (D-3) |
| **Changed in v3.7** | §8 **[AD] D-89 (D-84 Option L) normative;** reading C removed · §6 **two named boundaries:** `closedThroughPosition` and `closedThroughTime` · §6 the **temporal counterexample analysed against CR-01's text**, with the commit-timestamp alternative compared · §14 **no evasion** of the structural classification by splitting or relabelling |
| **Binding (addition)** | **D-89** |
| **Changed in v3.6** | §8 **D-84 threshold modelled as a roster fact**, with the two readings of D-84 put to the Principal (one narrow [OQ]) · §8 **roster and publication serialised on one lock;** each publication records the roster version it used · §12 reconciliation result carries the **publication position**, and the reconciliation is atomic · §6 the exact meaning of the clock reading versus commit versus position · §13 territorial tier versus address component versus official unit |
| **Changed in v3.5** | §6 the **authoritative definition of `recordedAt`** quoted and applied · §12 **reconciliation** as a first-class fact · §13 **classification profiles** as a domain model · §14 **structural versus non-structural** changes · §8 conflict of interest **by role** (preparing versus assessing and deciding) · §9 the platform default locale as a **published fact** · §15 the **write paths** inside and outside the knowledge stream · §16 aggregate review |
| **Previously** | v3.4: refuse, don't bump; close through a requested τ; piecewise before-images; exact queries. v3.3: slot uniqueness; set-valued names; matrix; D-88 |
| **Binding** | D-3, D-4, D-5, D-22, D-31, D-73 / CR-01, D-75 (with the XD-3 act), D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, D-86, D-87, D-88, D-89, D-90, D-91, **D-92** |
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
  - **[Amended by D-229, 2026-11-13]:** every *establish* claim cites a **competent establishing instrument** (not necessarily the founding document). A duplicate-search assessment is **supplementary** identity evidence, recorded as a Revision assessment where applicable. **It is never the basis**, and it never establishes a unit alone.

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
| Existence | (EXISTENCE, unit) | Territorial (a competent establishing instrument, D-229; a duplicate search is supplementary, never the basis) |
| Containment | (CONTAINMENT, unit, scheme) | Territorial |
| Classification (territorial tiers only, D-83) | (CLASSIFICATION, unit, scheme) | Territorial |
| Boundary description | (BOUNDARY, unit, source) | Territorial, or a cartographic source attributed as such *(explicitly identified and attributed as cartographic evidence; a generic "other" source does not qualify; Principal F-2, D-229 (5))* |
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
| Withdrawal | **The target's slot, with the target's kind** (a withdrawal of a name is a SOURCE_NAME claim with value "none") | Instrument or correction (D-228) |

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
| **I-4** establish needs an instrument or a duplicate search *(amended by D-229: a competent instrument; the duplicate search is a supplementary assessment)* | Recorded in the revision | None (internal to the revision) | — |
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

**Implementation annotation (2026-11-13, Principal decision on G-2 finding F-1, option (a)).** These queries date from v3.4, before §13 derived positions from `country(u)`, so they lag §13's G-4 ("for every containment u → P"). Added rows:

| Invariant | Query | Dependency recorded |
|---|---|---|
| G-4 and D-83 (a CONTAINMENT or ADMISSION claim of u over V that changes `country(u)` at some date) | `countryOf(u)` before and after, through the ancestors' containment and admission; when they differ: `descendantsOf(u, S, V)` (breadth-first, bounded by the profile depth) and each one's class, parent and country; the profile slots read | Slots: the ancestors' containment and admission, each descendant's classification and containment, the profile slots. Family: (CONTAINMENT, S) = each subtree node over V (for an admission: (CONTAINMENT, *) = u, every scheme) |

- **A change that leaves `country(u)` unchanged reads no descendants,** so it creates no false staleness.
- **A descendant that is itself admitted** (its own country) is outside the affected subtree.
- **Cross-country moves remain permitted.**

**A phantom insertion is a gain and a phantom removal is a loss of a family result. Both are caught by the family rule.**

**Where the proof stops [TU]:** completeness holds **if** every invariant check is implemented exactly through the dependencies in this matrix. **Implementation must enforce it structurally**: the validator may read published state only through a dependency-recording query interface, and tests assert that no other read path exists.

### 5.3 Stale means re-prepare

A stale revision is never re-validated silently. A human reconsiders it (for example, to include a newly arrived child).

**Content versions** [v3.10; completed in v3.11]:
- Every submission freezes a **content version** `v` of the revision. Its digest covers **everything the assessor and second person judge:**
  - every claim with its kind, slot, value and **valid interval**;
  - every **evidence version** relied on, by identifier **and content fingerprint**;
  - every **assessment** with its outcome (authenticity, competence, applicability, precedence);
  - the **dependency predicates** and p₀;
  - the **derived classification** (structural or not);
  - the **preparers and assessor of v** (recorded with v; `excluded(r)` is the union over all versions).
- **Evidence is immutable** [v3.11]: an evidence record is never edited. A corrected scan, a fuller copy or a revised extract is a **new evidence version** with a new identifier and fingerprint. **So no evidence can change under an unchanged reference** [v3.10 covered only "evidence references", which left that open].
- **Re-preparation after staleness, or any change to anything above, creates a new version.** Assessments and approvals **name the version they judged**; **only those naming the version being published count** (§8).
- Published authority claims (ISSUER, MANDATE, COMPETENCE) are protected by the dependency predicates, not the digest: a later change to them makes the revision stale.

## 6. Publication ordering and the completeness boundaries (`closedThroughPosition`, `closedThroughTime`) [TD]

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

**Two completeness boundaries, named apart** [v3.7]:

| Name | Kind | Meaning | Used by |
|---|---|---|---|
| **`closedThroughPosition`** | A publication **position** | = the sequence row's committed value. Every publication with position ≤ it is committed and visible; no future publication can obtain a position ≤ it | Geography-internal queries **by position** (consumers that record the p they used) |
| **`closedThroughTime`** (W) | A **time** | Inclusive: every publication with `recordedAt ≤ W` is committed and visible; no future publication can obtain `recordedAt ≤ W` | **Cross-context queries at a shared τ** (D-73; CR-01's `closedThrough`) |

**Mapping a time to a position:** `positionAt(τ) = max { p : recordedAt(p) ≤ τ }`, defined only if `τ ≤ closedThroughTime`.
- **Ties:** every entry with `recordedAt = τ` is included, and positions order them.
- **A position is never compared with a time.**

**Consistent historical queries:** a reader, within **one snapshot** (one statement, or a `REPEATABLE READ` read-only transaction):
1. reads the relevant boundary (`closedThroughPosition` for a position query, `closedThroughTime` for a time query);
2. answers only if the request is within it; **otherwise it refuses, or first attempts a close through τ** (CR-01 §3).

There are no gaps below either boundary, so the answer is complete. An uncommitted publication is invisible.

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
  | **`closedThroughTime` (W)** | **Inclusive:** every entry with `recordedAt ≤ W` has committed; no later entry can have `recordedAt ≤ W` |

- **Guarantee:** for any τ ≤ W, the set of publications with `recordedAt ≤ τ` is final and complete, and ordered by position.
- **Assumptions:**
  - a lock held to commit;
  - one database clock;
  - the refusal rule;
  - host time kept by slewing.
- **Cross-context causal consistency is not claimed.**

**The temporal counterexample (review of v3.6), analysed** [v3.7]:

| Step | Event |
|---|---|
| 1 | Publication P holds the lock and reads `recordedAt = 10:00:00.100` |
| 2 | A caller asks about τ = 10:00:00.150. W < 150, so a close through 150 is required first (§6 rules) |
| 3 | P commits at 10:00:00.200 |
| 4 | The close takes the lock **after** P's commit, checks clock ≥ 150, sets W = 150 and commits |
| 5 | The query answers at τ = 150 and **includes P** (`recordedAt` 100 ≤ 150) |

- **Is the sequence possible? Yes.**
- **Is the answer wrong under the accepted contract? No** [AC]:
  - (a) **CR-01 defines knowledge by `recordedAt`, not by visibility:** "`KnownAt(φ, asOf, τ)` … over `F_τ = {f : recordedAt(f) ≤ τ}`". P's `recordedAt` is 100, so P belongs to F₁₅₀ **by definition.**
  - (b) **No answer at τ = 150 can be given before step 4.** A query at 150 with W < 150 is refused or must close first, and the close waits for P (lock order). **So no caller ever receives a τ = 150 answer without P.**
  - (c) **The answer is stable** (ratified P3: `KnownAt` never changes once given). Every later query at 150 gives the same set.
  - (d) **CR-01 §3's guarantee holds:** after W = 150, everything with `recordedAt ≤ 150` is committed, and nothing later can enter with `recordedAt ≤ 150`.
- **What is true, stated plainly:** "known at τ" means **recorded with system-of-record time ≤ τ**. It does **not** mean "visible to an arbitrary reader at wall-clock τ".
  - A reader who looked at the database at wall-clock 10:00:00.150 **without** the D-73 protocol would not have seen P. **The contract forbids answering a τ question that way.**
  - Such a reader can only have read **by position**, and records the position it used, which is unambiguous.
- **The proof does not depend on how long the gap is** between the reading and the commit [corrected in v3.8: v3.7 wrongly called it "the duration of the commit itself"; commit latency is unbounded in principle]. The proof depends only on:
  - (1) the writer lock held from the reading until commit or rollback;
  - (2) a close taking the same lock, and therefore waiting for every in-flight writer;
  - (3) later writers refused if their reading is ≤ W or below the previous `recordedAt`;
  - (4) readers answering from one snapshot taken after the boundary they rely on has committed;
  - (5) ties ordered by position.
- **Proof obligations for the implementation architecture** [v3.10]. **A conceptual design is not an implementation proof.** Each obligation must be demonstrated, and the stated observation **falsifies** it:

  | # | Obligation | Kind | Falsified by |
  |---|---|---|---|
  | PO-1 | The publication lock is held from the `recordedAt` reading until commit or rollback, by every writer and every close | Database | A close committing while a writer that read its clock earlier is still open |
  | PO-2 | **Single write path:** no code path writes claims, governance notices (D-90), the sequence row, `closedThroughTime` or the roster except under that lock | Architecture | Any write found outside it (code search plus a database-level guard) |
  | PO-3 | **Dependency-only reads:** validation reads published state only through queries that record their predicate | Architecture | A validation read with no recorded predicate |
  | PO-4 | Positions are gap-free and committed in order | Database | A visible position p+1 while p is not visible |
  | PO-5 | A reader uses one snapshot taken after the boundary it relies on committed | Database | An answer mixing two snapshots |
  | PO-6 | Refusal on clock regression; no timestamp is ever adjusted | Architecture | A stored `recordedAt` ≠ the clock reading, or one ≤ W |
  | PO-7 | One database clock for all writers of one stream | Database | Two clocks feeding `recordedAt` |
  | PO-8 | Every D-73 participant meets the same contract | **Platform** | **Membership's §38.2 barrier, which moves timestamps (known today)** |

  **Classification of the guarantee:** (A) follows logically from the contract and transaction ordering, **given PO-1 to PO-7**; (B) PO-1, PO-4, PO-5, PO-7 depend on PostgreSQL behaviour; (C) PO-8 depends on other contexts.

- **Tests of the protocol** [v3.8]:

  | Case | Result |
  |---|---|
  | **Delayed commit** (reading 100, commit much later, a close through 150 requested in between) | The close waits for the commit; the 150 answer includes the publication; stable |
  | **Abort after the reading** | Nothing visible; W unchanged; the position is reused; no answer ever contained it |
  | **Close and publication concurrently** | Serialised by the lock: either the publication committed first (included if its reading ≤ τ), or it waits and is then refused unless its reading > W |
  | **Clock regression** | Writes refused until the clock passes max(W, last `recordedAt`); no timestamp adjusted |
  | **Equal timestamps** | Allowed; ordered by position; all included in `positionAt(τ)` |
  | **Query by position above `closedThroughPosition`** | Refused |
  | **Query by time above `closedThroughTime`** | Close attempted; refused if the close cannot commit, or if τ is in the future |

**The alternative compared: `recordedAt` from the actual commit timestamp** (PostgreSQL `track_commit_timestamp`, `pg_xact_commit_timestamp`):

| Property | Reading inside the step (v3.7) | Actual commit timestamp |
|---|---|---|
| Available when | Inside the transaction | **Only after commit** |
| Refusal rule possible | **Yes:** refuse before committing | **No:** the value exists only once the fact is already visible |
| Completeness under a clock step backwards | **Holds** (refusal) | **Breaks:** a later commit can receive a commit time ≤ W, and nothing can stop it |
| Proof basis | **Transaction ordering** (D-73) | **Clock monotonicity**, contrary to D-73's "proved by transaction ordering" |
| Fidelity to visibility | Earlier than visibility by the commit duration | Exact |

**Conclusion [AC]:**
- The commit-timestamp realisation is more faithful to the moment of visibility, **but its completeness depends on the clock never moving backwards, so it is not proved by transaction ordering.** D-73 rules it out as the proof basis.
- **v3.7 keeps the reading inside the step**, whose guarantee is proved by transaction ordering and which CR-01's definition permits ("implementations may realise it from the commit time": *may*, not *must*).
- **[TU]** If the Principal DDD Architect nevertheless judges that "entered the authoritative record" must equal visibility, **no PostgreSQL mechanism found gives both exact visibility time and a transaction-ordering proof.** That would then be a genuine conflict to put to the Principal. **This review finds no such requirement in the accepted text.**
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

**Roles, as D-84, D-84a and [AD] D-88 define them:**

**The one independence rule** [v3.9; AD D-88]: for a revision r, `excluded(r)` = **every person who prepared, assessed or submitted any content version of r** [v3.10: accumulated over versions; v3.9 said "the steward who assessed and submitted", singular, which a re-assessment by a second steward could evade]. A second-person approval of r is valid only from a person **not in `excluded(r)`**. **Every passage below uses this one rule;** none restates it more narrowly.

| Role | Rule |
|---|---|
| **Preparer** [v3.5] | **Collects evidence and drafts claims. Decides nothing.** May be any steward, **even a conflicted one** |
| **Assessing steward** | **Assesses** the evidence (authenticity, competence, applicability) and **submits** the revision (which freezes it). **Assessing and submitting are decisions** under D-84a. Usually the same person as the preparer. **May publish without a second person:** for **any** change **while no second steward has ever been appointed**; for **non-structural** changes always (D-84, D-89) |
| **Second person** | Required for **structural** revisions (§14) **once `secondStewardEverAppointed` is true** (D-84 as interpreted by D-89, permanently). **A natural person not in `excluded(r)`**: neither a preparer nor the assessing steward (D-88), checked as a distinct canonical person; a steward, or a matter reviewer where D-84a applies |
| Matter reviewer | Appointed for one matter only (D-84a); may act as assessing steward or as second person for that matter |

**The D-84 rule by era** [v3.8; AD D-84, D-89]:

| Era | Structural change | Non-structural change |
|---|---|---|
| **A: no second steward ever appointed** (`secondStewardEverAppointed` false) | The sole steward may publish alone, with a recorded conflict declaration (D-84 "while only one steward exists") | Same |
| **B: a second steward has been appointed at least once** (flag true, permanently) | **Needs a second person**, whatever the current roster size. **A sole remaining steward cannot publish it alone;** it waits (D-89) | The assessing steward may publish alone (D-84 applies the second-person rule to structural changes only) |

**Permitted combinations of people** [v3.8; AD D-88: the second person is "other than the steward who prepared and assessed it"; "the preparer can never approve their own structural change"]:

| Case | Preparer (P) | Assessing steward (S) | Second person (X), era B, structural | Rule |
|---|---|---|---|---|
| 1 Usual | P | S = P | X ≠ P | Two people |
| 2 Conflicted preparer | P (conflicted, prepares only) | S ≠ P, not conflicted | **X ∉ {P, S}**, not conflicted | **Three people.** X must differ from both, because D-88 excludes the steward who assessed (S) **and** the preparer (P) |
| 3 Matter reviewer assesses | P | S = matter reviewer R | X ∉ {P, R} | R cannot be both S and X for one matter |
| 4 Matter reviewer approves | P | S = P | X = matter reviewer R ≠ P | Allowed (D-84a) |
| 5 Era A | P | S = P (the sole steward) | Not required | If S is conflicted in a judgment matter, a matter reviewer becomes S (D-84a). **A matter reviewer is not a steward appointment, so it does not set the flag** |

- **No person can approve their own structural change by splitting roles:** the second person is checked, as a distinct canonical person, against **every** person who prepared or assessed the revision.

**Approval binds to content** [v3.10]:
- A second-person approval names **revision r, content version v and the roster version** it was given under.
- **At publication, under the lock:** the approval must name the version being published, and its approver must be outside `excluded(r)` **as evaluated at the current roster version** (which includes Identity merges recorded so far, below).
- **Without this,** a revision re-prepared after staleness (for example with an added child) could have been published on an approval given to different content.

**Full combination check** [v3.10] (✓ allowed · ✗ refused):

| Case | Approver X | Result |
|---|---|---|
| One preparer P = assessor S | X ≠ P | ✓ |
| Two preparers P1, P2; S = P1 | X = P2 | ✗ (a preparer) |
| P ≠ S | X = P or X = S | ✗ |
| P ≠ S | X third person | ✓ |
| Matter reviewer R assesses | X = R | ✗ |
| Matter reviewer R approves; S = P | X = R ≠ P | ✓ |
| Version 1 assessed by S1; re-prepared, version 2 assessed by S2 | X = S1 | ✗ (assessed a version of r) |
| Approval given to version 1; version 2 published | any | ✗ (wrong version) |
| X distinct at approval; Identity later merges X with P **before** publication | X | ✗ (re-checked under the lock) |
| Merge recorded **after** publication | — | Governance finding (below) |

**Identity merges** [v3.10; AD D-75, XD-3 C-5]:
- **Geography requires from Identity (contract, not mechanism):**
  1. **one reference per canonical person** for platform-level use, stable and never reused;
  2. **reference equality ⇔ the same canonical person**, as far as Identity knows;
  3. **a merge notice** when Identity merges persons, naming the affected platform references and the time of Identity's act, **and nothing about organisations** (XD-3 §2 items 3–4);
  4. the same for a reversal of a mistaken merge.
- **Geography records a merge notice as a roster write** (under the publication lock, with a roster version). From that version, the merged references count as one person in `excluded(r)`.
- **Unpublished revisions:** re-checked automatically at publication.
- **Five cases** [v3.11]. Four concepts are kept apart: **(1)** validity of the approval when given · **(2)** effectiveness of the published facts · **(3)** the governance finding from later knowledge · **(4)** a new confirmation or correction.

  | Case | Known then / known now | (1) Approval | (3) Finding | (2) and (4) | Basis |
  |---|---|---|---|---|---|
  | **A** Merge before publication | — / X and P are one person | Refused at publication (re-check under the lock) | None needed | Nothing published; a valid second person must approve | [AC] D-88 directly |
  | **B** After publication; second person required; merge puts X in `excluded(r)` | Distinct / one person | **Procedurally valid when given; substantively defective** (D-88's "independent natural person" was not met) | **Recorded**, appended, with the roster version | **Kept in force, flagged approval-defective, until confirmed or corrected** | [AD] D-90 |
  | **C** After publication; no second person required (era A, or non-structural) | — / any merge | Not applicable | **None for D-88**; the merge notice is recorded only | Unaffected | [AC] D-84, D-89 |
  | **D** After publication; merge involves people in it but **not** X with someone in `excluded(r)` (e.g. two preparers) | Distinct / one person | Valid | None | Unaffected | [AC] D-88 compares only X with `excluded(r)` |
  | **E** A merge later reversed by Identity | One person / distinct | — | A finding raised under B is **closed as unfounded** (appended); nothing earlier rewritten | A confirmation or correction already published stays a normal publication | [AC] |

- **Case B, effectiveness meanwhile: [AD] D-90 chose K.** The analysis that led to it: **D-84 did not settle it:** "the fact changes only by a correction or a recorded dispute" governs changes to facts, not the effect of a defective approval. **Two coherent options** (voiding the publication retroactively is excluded: it would rewrite history, strategic invariant 2):
  - **K (keep, flagged):** the facts stay in force and are marked **approval-defective** in their explanation and in a published notice; a valid second person **confirms** (appended) or a **correction** replaces them. Consumers see the flag and apply **their own** rules (e.g. Election may decline to rely on a flagged fact for an electorate).
  - **S (suspend):** from the finding, the facts are **withdrawn by a new publication** until a valid second person approves them again. History before the suspension stays.
  - **Ruled: K (D-90).** The facts rest on the official instrument, not on PublicDigit's approval; suspending them would disrupt organisations' coverage and elections for an internal governance defect, and **a suspension is itself a structural change needing an independent approval**, which in case B may be exactly what is unavailable.
- **History is never rewritten** in any case: the publication, the approval and the references as given all stay.

**D-90 confirmation, tactical meaning** [TD; implements AD D-90, no new policy]:

| Question | Rule | Basis |
|---|---|---|
| What is confirmed | **The original publication's exact content version v**: its claims and intervals, evidence versions (fingerprints), assessments and outcomes, derived classification. The confirmation names r, v, the publication position p and its digest | D-90 "confirms them"; §5.3 |
| Who may confirm | A natural person **not in `excluded(r)`**, evaluated at the **current** roster version, so including every merge recorded since. The defective approver is excluded automatically (merged with a preparer or the assessor) | D-88 |
| Eligibility | Appointed when confirming (steward, or matter reviewer for that matter); conflict declaration recorded; D-84a test for that person | D-84, D-84a, D-89 (era B is permanent) |
| What it is | **A governance notice, not a geographical publication.** It changes no claim and no belief. It is written **under the publication lock** and takes **its own position and `recordedAt`** in the publication stream, with **no claims** | So the approval status at any p or τ is complete under both boundaries (§6) |
| If the content is no longer right | **Confirmation is impossible for different content.** A confirmer who finds v wrong does not confirm; the route is a **correction**: an ordinary revision with its own approval. The original stays flagged and is shown as **defective, corrected at q** | D-90 "or a correction replaces them" |
| The finding itself | Recorded as a governance notice when the merge notice reveals case B: system-triggered, no personal judgement; closed by a later notice if the merge is reversed (case E) | §8 five cases |
| Visible to consumers | The explanation read model gives a publication's **approval status as at (p or τ)**: approved · approval-defective (notice f) · confirmed (notice c) · corrected (position q) · finding closed (notice e). `GovernanceNoticePublished` carries each notice. **The original entry is never modified** | Strategic invariant 2 |
- **Safe to retain as a dependency [AC]:** comparison uses only reference equality; the residual risk is an undiscovered duplicate person, handled by the procedure above once Identity merges.

**Conflict of interest by role (D-84a applied exactly)** [v3.5]:
- **D-84a forbids deciding,** where a material conflict exists: an organisational interest **and** a judgment between competing positions.
- **The preparer** does not decide, so D-84a does not bar preparation.
- **The assessing steward and the second person** decide, so neither may be materially conflicted in that matter.
- **If the only steward is conflicted** in a judgment matter, the matter waits for a non-conflicted steward or a matter reviewer (D-84a). The conflicted steward may still prepare the evidence.
- **In era A** (no second steward ever appointed), the conflict test runs for the assessing steward. **In era B,** for a structural revision, it runs for both the assessing steward and the second person. [v3.9: v3.8 said "before D-88 applies"; D-88 always applies, the threshold is what activates]
- **Routine publication** (no judgment between competing positions) needs only the declaration (D-84a).

**Checks:**

| When | Check |
|---|---|
| At submission and at the second person's approval | The acting person is appointed now; conflict declaration recorded; D-84a test (interest **and** judgment between competing positions) for **that person** |
| At publication | **Threshold (D-89):** if `secondStewardEverAppointed` is true at the roster version used, every structural revision needs a second person's approval; **conflict declarations re-checked**; **second person ∉ `excluded(r)`** (D-88) |
| Appointment ended after submission or approval | The act stands (it was valid when given) |
| New steward appointed after submission | If this is the first second steward, the threshold activates: a structural revision needs a second person's approval before publication |
| **Second steward's appointment ends** | **The threshold stays active (D-89).** A sole remaining steward cannot publish a structural change alone; it waits for an eligible second person: another steward, or a matter reviewer appointed for the matter (D-84a) |

**The D-84 threshold, normative [AD D-89]:**
- The roster holds **`secondStewardEverAppointed`**. It is **set by the first appointment of a second steward** (the roster write that brings the count of appointed stewards to two), **recorded with that roster version**, and **never unset.**
- **Publication invariant:** `secondStewardEverAppointed` at the roster version used by the publication ⇒ every structural revision carries an approval by a natural person **not in `excluded(r)`** (D-88), who is a steward or a matter reviewer for that matter, is not materially conflicted (D-84a), and was appointed when approving.
- **History:** the roster version at which the flag became true identifies when the threshold activated. Each publication's recorded roster version shows which rule applied.

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
| **Belonging** (to units and wing branches, in every capacity) | **Organisation** ([AD] D-3, D-22). Membership owns membership facts (ratified architecture), not belonging [corrected in v3.8] |
| Electorate | **Election**, from Organisation's belonging and Membership's facts at one shared τ |
| Sovereignty | Nobody in PublicDigit (D-86) |

**Events:** `RevisionPublished` (position, revision, kinds, partitions, subjects, reconciliation mappings) or `GovernanceNoticePublished` (position, publication concerned, notice kind), one per position; `CandidateDecided`; `ObjectionDecided`.

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
| ADMISSION | **Structural** | **[AD] D-91.** It makes a level-2 entry exist. Admission authority (D-86) and publication governance (D-84, D-88, D-89) are separate controls |
| IDENTIFIER, SOURCE_NAME, CLDR_NAME, CLDR_INHERITANCE | Non-structural | "Names, codes, external identifiers, aliases"; D-87 explicitly calls a CLDR name revision non-structural |
| GROUPING (M49 region) | Non-structural | **[AD] D-92**, for assignments on an M49 row or a verified special-purpose code. A statistical grouping, not containment (D-85). Organisations decide what a regrouping means for coverage (D-80). **Platform-policy assignments: L-4c, deferred** |
| DISPUTE (marker or clearance) | Non-structural | It changes no fact (§4). **D-84a still applies,** because a dispute is a judgment matter |
| PLATFORM_DEFAULT_LOCALE | Non-structural | Presentation |
| **Any correction that alters a structural fact or its effective dates** | **Structural** | Dossier list |

**No evasion** [v3.7]:
- **Classification is by claim kind, never by label or revision type.** A revision is structural **if any of its claims is of a structural kind**, so a "correction" of a structural slot is structural.
- **Splitting** a structural change across several revisions leaves **each part** structural, so each needs the second person under D-89.
- The validator computes the classification from the claims; **it cannot be set by the preparer.**
| StewardRoster records | **Outside D-84's scope** | Platform-owner acts (D-84: "the platform owner appoints"), not geographical publications |

- **Residual:** none. ADMISSION (D-91) and GROUPING (D-92) are ruled; every other row follows the S-1 dossier list or D-87. **Region assignment by platform policy remains L-4c.** **If the Principal wishes otherwise, it is a one-line policy change, not an architectural one.** Listed in the assessment, not as a required decision.

## 15. Write paths: inside and outside the knowledge stream [AC, v3.5]

| Write | In the knowledge stream (`closedThrough`)? | Why |
|---|---|---|
| Publication of a revision (every claim kind in §3, dispute markers, reconciliation, profiles, registry, CLDR, platform default locale) | **Yes: the only path** | These are what consumers know as geography |
| **Governance notices (D-90):** finding, confirmation, closure | **Yes:** own position and `recordedAt`, **no claims** | Consumers need a publication's approval status as at p or τ, complete under both boundaries (§8) |
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
| `StewardRoster` (aggregate) | Appointments over time; matter reviewers; declarations | Governance record | Geography facts; personal data (only person references, below) |

**Person references in the roster** [v3.9; AD D-75, XD-3 act §2]:
- Geography is **platform-level and not an organisation**, so it holds **no organisation `PersonRef`**. The roster holds **one platform-scoped person reference per steward, preparer, approver and matter reviewer, issued by Identity.**
- **Its only use is accountability and the `excluded(r)` comparison.** Geography **never resolves** it to an organisation's reference and **never queries affiliations** (XD-3 §2 item 3, C-1).
- **Conflicts of interest are declared, not derived:** Geography learns of an organisational interest only through the recorded declaration (D-84a), never by reading Organisation or Membership data.
- **[TU] Dependency on Identity:** which reference a platform-level context receives (the bare `PersonId` or a platform-scoped reference) is **not decided by XD-3**; it belongs to Identity's tactical design. **If Identity later merges two persons (C-5),** an approval given when the two references looked distinct must be re-examined; the roster records the reference as given, so the case is detectable.
| Publication sequencer | Gap-free ordered positions; W | The sequence row, entries | Domain invariants |
| Partitions | Consistency scopes (read-only) | — | Mutable state |
| `Publish` service | Coordination: lock, governance re-check, delta check, append | — | Business decisions |

**Roster facts are not in the publication model:** they govern who may act and are read "as at" the moment of acting. **Geographical facts are, and so are D-90 governance notices**, which say something about a publication that consumers rely on. [Corrected at final validation: v3.11 said "governance facts" generally, contradicting the D-90 notices in §8 and §10.]

## 17. Open items (renumbered from §11 in v3.5)

| Item | Kind |
|---|---|
| Names of the five level-1 regions | [OQ] |
| Names of territorial units below level 2 | [OQ] |
| L-4c; Japanese blocks | [OQ], later |
| **Q-2 (recorded, not lost):** should Geography represent official schemes other than the administrative one, such as electoral constituencies? (Geography discovery revision 2, §9 question 2 and R2-13) | [OQ], later; does not affect the baseline |
| Address owner | Recommendation |
| `FOR UPDATE` re-read under the chosen isolation; structural enforcement that the validator reads only through dependency-recording queries (§5.2); **a single write path for published claims (§15)**; host time kept by slewing (refusal frequency); Membership's conformance to the platform knowledge-time contract (§6) | [TU] |
| ~~GROUPING and ADMISSION classified by analogy (§14)~~ | **Closed:** ruled D-91 (structural) and D-92 (non-structural) |
| PO-1 to PO-8 (§6) and the Identity contract (§8) | [TU], implementation architecture |
| Primary verification of historical examples | Evidence |
