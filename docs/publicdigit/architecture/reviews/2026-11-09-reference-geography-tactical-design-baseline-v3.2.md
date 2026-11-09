# Reference Geography: tactical DDD design baseline, version 3.2 (proposed)

| | |
|---|---|
| **Status** | PROPOSED, for the Principal DDD Architect's independent assessment. Self-contained (v1 to v3.1 not needed). No code, schemas or tickets. Not committed |
| **Supersedes** | v3.1 (kept as design evidence) |
| **Companion** | `2026-11-09-reference-geography-tactical-design-v3.2-assessment.md` |
| **Changed in v3.2** | §3 slot keys typed by kind · §5 dependency predicates and phantom protection · §6 time watermark for D-73 cross-context τ; work before and under the lock · §8 governance roles reduced to D-84's own two · §9 fallback marked in the result |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, D-86, D-87 |
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
| Source name | (SOURCE_NAME, subject, source, language, name type) | Attribution |
| CLDR display name | (CLDR_NAME, entry, CLDR locale, form ∈ {standard, short}) | Display names (CLDR release), D-87 |
| CLDR locale inheritance | (CLDR_INHERITANCE, CLDR release) | CLDR supplemental data (parent locales) |
| Registry: issuer | (ISSUER, issuer) | — |
| Registry: mandate | (MANDATE, mandate) | Its legal basis **as the jurisdiction provides it** (constitution, statute, delegated act, municipal statute); optional delegation link to a parent mandate |
| Registry: non-territorial competence | (COMPETENCE, issuer, competence kind) | The issuer's stated scope |
| Registry: precedence rule | (PRECEDENCE, rule) | Its legal basis and scope |
| Dispute state | (DISPUTE, target claim) | Assessment |
| Withdrawal | **The target's slot, with the target's kind** (a withdrawal of a name is a SOURCE_NAME claim with value "none") | Instrument or correction |

**Collision check:**
- Every key starts with a distinct kind, so **no two kinds share a slot**.
- **Within a kind, the qualifiers separate facts that coexist**: namespaces, sources, languages, locales and forms.
- A withdrawal reuses its target's key, which is exactly the intended supersession.
- A dispute is keyed by the target **claim**, not its slot, so a dispute never supersedes a value.

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

**Dependencies as predicates, not records** [corrected in v3.2]:
- When a revision is validated, it records its **dependency predicates**: the **queries** its validation evaluated, not the records those queries happened to return. Examples:
  - "children of A in scheme S over V";
  - "parents of A over V";
  - "holders of (namespace, value) over V";
  - "the profile of country C over V";
  - "mandate M and precedence rules of scope X at t";
  - "slots superseded or disputed by this revision".
- It also records the position *p₀* it was validated against.
- **Stale:** **any published claim with position > p₀ that satisfies one of the predicates** makes the revision stale, **including claims about subjects that did not exist at p₀**.
  - A new sixth child of A matches "children of A over V" → stale.
  - A new holder of the expected-unique code matches its namespace predicate → stale.
  - **Phantoms are therefore caught.**
- **Not stale:** a claim published since p₀ that matches no predicate cannot change any invariant the revision's validation evaluated. G-4, G-5, G-6, I-1 and I-2 are all evaluated only through these predicates. Example: a publication in another country, or an unrelated slot of the same unit.
- **Why staleness and validation coincide:**
  - pre-validation proved the invariants for (state at p₀ + revision);
  - every claim published since was validated without the revision;
  - an interaction between them must pass through one of the revision's predicates, which the staleness check detects.
  - **[TU]** This rests on every invariant check being expressed through recorded predicates. Implementation tests must assert this property.
- **Stale means re-prepare:** a human must reconsider the revision (for example, include the new child). It does not re-validate silently.

## 6. Publication ordering and `closedThrough` [TD]

**Mechanism:** one **sequence row** (a single row holding the last committed position), not a PostgreSQL sequence object.

**The publication transaction:**
1. **Lock** the sequence row (`SELECT … FOR UPDATE`). The lock is held **until commit or rollback**.
2. Re-check governance (§8).
3. **Staleness check (§5):** published claims with position in (p₀, row value] matching the revision's predicates. Full validation already ran before the lock.
4. Set `p = value + 1`.
5. Insert the publication entry (p, revision, `recordedAt` = **max(statement clock, previous `recordedAt`)**).
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
- **Mechanism: a time watermark W**, kept in the sequence row and **advanced only under the publication lock**:
  - every publication records `recordedAt = max(statement clock, previous recordedAt, W)`;
  - **"close through now"** takes the lock, sets `W = max(statement clock, W)`, commits, and returns W.
  - **Guarantee:** every publication with `recordedAt ≤ W` is committed, and **no future publication can receive `recordedAt ≤ W`**, because it takes the lock afterwards and is clamped at ≥ W.
  - **`closedThroughTime` = W.** This is the same barrier pattern as Membership's (model document §38.2: writers versus an exclusive close).
- **A multi-context operation at τ:**
  1. asks each context to be closed through τ (Membership's `closedThrough` ≥ τ; Geography's W ≥ τ, advancing W on demand);
  2. **each context answers from its own facts with recorded time ≤ τ.** Geography maps τ to "the highest position with `recordedAt` ≤ τ", which is monotonic by the clamp.
- **What this guarantees:** **each context's answer is complete as of the same recorded time τ.** It does not guarantee more (for example, causal order between two contexts' facts with equal times).
- **[TU] platform dependency:** "the same τ" assumes every context takes `recordedAt` from **one platform clock with the same non-decreasing discipline.** If one context clamps and another does not, a backward clock jump can misorder them. **The platform clock discipline is a platform-architecture requirement, not something Geography can solve alone.**

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

**D-84's own roles** [corrected in v3.2]: "**A steward assesses and publishes** … From the appointment of a second steward, every structural change requires approval by **a second, independent natural person**."
- D-84 names two roles: **the assessing steward** and **the second person**. v3.1's "first approver" was an additional role not found in D-84. **It is removed:** the assessing steward's **submission for publication** freezes the content.

| Role | Rule |
|---|---|
| **Assessing steward** (preparer) | Prepares and assesses; submitting freezes the content. While one steward exists, publishes alone (D-84) |
| **Second person** | Required for structural revisions when two or more stewards are appointed at publication time (D-84): a steward or a matter reviewer, a distinct canonical person. **Its relation to the preparer is [OQ] (§11)** |
| Matter reviewer | One matter only (D-84a) |

**Interpretations for the Principal [OQ]:**

| Interpretation | Meaning | Persons needed | Consequence |
|---|---|---|---|
| **T (textual):** the second person is distinct from **the assessing steward** | Read "second" as second to the steward who assesses | 2 | The preparer can never approve their own structural change; minimum staffing |
| A (the reviewer's option A): distinct from the preparer **and** a separate first approver | Keeps three roles | 2 or 3 | Equals T when the preparer submits; adds a third person if a separate first approval is introduced |
| B (the reviewer's option B): distinct from a first approver; the preparer may be the second | Keeps three roles | 2 | **The preparer can approve their own change** if someone else gave the first approval |

**Recommendation: T.** It is the most direct reading of D-84's words, gives the independent scrutiny the reviewer seeks, and needs no third role. **Not decided:** this is put to the Principal.

**Checks:**

| When | Check |
|---|---|
| At submission and at each approval | The acting person is appointed now; conflict declaration recorded; D-84a test (interest **and** judgment between competing positions) for **that person** |
| At publication | **Threshold** (two or more stewards now → structural needs the second approval); **conflict declarations re-checked** |
| Appointment ended after approval | Approval stands (it was valid when given) |
| New steward appointed after approval | A second approval is needed for structural revisions |

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
| **Second person's relation to the preparer under D-84** (T, A or B; §8) | **[OQ], now** |
| L-4c; Japanese blocks | [OQ], later |
| **Q-2 (recorded, not lost):** should Geography represent official schemes other than the administrative one, such as electoral constituencies? (Geography discovery revision 2, §9 question 2 and R2-13) | [OQ], later; does not affect the baseline |
| Address owner | Recommendation |
| `FOR UPDATE` re-read under the chosen isolation; predicate-completeness tests (§5); one platform clock discipline across contexts (§6) | [TU] |
| Primary verification of historical examples | Evidence |

---

**Ruling recorded (2026-11-09): interpretation T as D-88** (direct; discovery record §11.23). §8's second person must be distinct from the assessing steward (the preparer). The [OQ] in §11 is closed.
