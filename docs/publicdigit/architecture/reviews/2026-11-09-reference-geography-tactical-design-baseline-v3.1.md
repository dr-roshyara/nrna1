# Reference Geography: tactical DDD design baseline, version 3.1 (proposed)

| | |
|---|---|
| **Status** | PROPOSED, for the Principal DDD Architect's independent assessment. Self-contained (v1 to v3 not needed). No code, schemas or tickets. Not committed |
| **Supersedes** | v3 (kept as design evidence) |
| **Companion** | `2026-11-09-reference-geography-tactical-design-v3.1-assessment.md` |
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

| Kind | Slot key | Basis (competence kind) |
|---|---|---|
| Existence | (unit) | Territorial |
| Containment | (unit, scheme) | Territorial |
| Classification (territorial tiers only, D-83) | (unit, scheme) | Territorial |
| Boundary description | (unit, source) | Territorial, or a cartographic source attributed as such |
| Succession | (predecessor, succession event) | Territorial |
| Identifier | (subject, namespace) | Identifier assignment by the namespace's issuer |
| Statistical grouping | (entry, M49 regions) | Statistical (D-85), D-86 evidence types |
| Admission | (entry) | PublicDigit policy (D-86) or a Principal decision record |
| Source name | (subject, source, language, name type) | Attribution |
| CLDR display name | (entry, CLDR locale, form ∈ {standard, short}) | Display names (CLDR release), D-87 |
| CLDR locale inheritance | (CLDR release) | CLDR supplemental data (parent locales) |
| Registry: issuer | (issuer) | — |
| Registry: mandate | (mandate) | Its legal basis **as the jurisdiction provides it** (constitution, statute, delegated act, municipal statute); optional delegation link to a parent mandate |
| Registry: non-territorial competence | (issuer, competence kind ∈ {identifier assignment, statistical grouping, display names}) | The issuer's stated scope |
| Registry: precedence rule | (rule) | Its legal basis and scope |
| Dispute state | (target claim) | Assessment |
| Withdrawal | The target's slot | Instrument or correction; value "none" for an interval |

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

**Staleness without version counters:** a revision records the position *p₀* it was prepared against. **It is stale if any slot or subject in its read set has a published claim with position > p₀.**

## 6. Publication ordering and `closedThrough` [TD]

**Mechanism:** one **sequence row** (a single row holding the last committed position), not a PostgreSQL sequence object.

**The publication transaction:**
1. **Lock** the sequence row (`SELECT … FOR UPDATE`). The lock is held **until commit or rollback**.
2. Re-check governance (§8).
3. Validate (§5) against published claims up to the row's current value.
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

**τ as time:**
- **The authoritative token is the position.**
- A time τ is needed for **multi-context operations** that share one τ (D-73). It maps to "the highest position with `recordedAt` ≤ τ".
- Because `recordedAt` is **clamped to be non-decreasing** under the lock, the mapping is **monotonic and deterministic** even if the clock moves backwards.
- `recordedAt` then means "platform publication time, never earlier than the previous one". **It may lag the real clock after a backward jump.**
- [TU] Cross-context: Membership's `recordedAt` comes from the same database clock but has its own barrier (§38.2). A shared time τ is coherent only if both contexts guarantee completeness through it; **each context's `closedThrough` must be ≥ τ.**

## 7. Boundaries: ordering versus consistency [TD]

| Concern | Element | Kind |
|---|---|---|
| Platform-wide knowledge order | **Publication sequencer** (sequence row plus the append-only publication stream) | Technical coordination mechanism: **its only invariant is gap-free, ordered positions** |
| Geographical invariants | **Partitions:** each **country** (its scheme, national namespaces, its mandates) and **global** (World, regions, level-2 entries, global namespaces ISO/M49, global registry, CLDR) | **Consistency scopes**, validated read-only during publication. **No mutable state of their own:** published claims and their positions are the state |
| A proposed change | **`Revision`** | **Aggregate:** internal completeness; frozen at approval |
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

| Role | Rule |
|---|---|
| Preparer | A steward; edits the draft |
| First approver | A steward or a matter reviewer; **freezes the content**; may be the preparer |
| Second approver | Required **for structural revisions when two or more stewards are appointed at publication time** (D-84). **A natural person distinct from the first approver** (distinct canonical person). **Whether the second approver may be the preparer: D-84 is silent; the narrowest reading allows it** → [OQ] |
| Matter reviewer | One matter only (D-84a) |

**Checks:**

| When | Check |
|---|---|
| At approval | Approver appointed now; conflict declaration recorded; D-84a test (interest **and** judgment between competing positions) for **that person** |
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
| Returned to the caller | Value, form, **resolved locale**, release |
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
| **May the preparer be the second approver under D-84?** | **[OQ] (interpretation)** |
| L-4c; Japanese blocks; Q-2 | [OQ], later |
| Address owner | Recommendation |
| `FOR UPDATE` re-read under the chosen isolation; lock duration for large revisions (pre-validate without the lock, re-validate the delta under it); cross-context τ completeness | [TU] |
| Primary verification of historical examples | Evidence |
