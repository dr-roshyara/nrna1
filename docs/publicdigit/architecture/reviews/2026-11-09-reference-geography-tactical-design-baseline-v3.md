# Reference Geography: tactical DDD design baseline, version 3 (proposed)

| | |
|---|---|
| **Status** | PROPOSED, for the Principal DDD Architect's independent assessment. Not an ARB request. No code, migrations or tickets. Not committed |
| **Supersedes** | v2 (`…-tactical-design-baseline-v2.md`, kept as design evidence); v1 |
| **Commission** | "Session A — Reference Geography: Tactical Baseline v3 Refinement" (Principal DDD Architect, 2026-11-09) |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, D-86, **D-87** (L-4b closed) |
| **Companion** | `2026-11-09-reference-geography-tactical-design-v3-assessment.md` |
| **Labels** | [AD] accepted · [BF] established fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical uncertainty · [OQ] Principal decision |

## 0. Corrections that shape this version (first)

1. **[AC] A timestamp taken inside a transaction is not its commit order.** v2 said `recordedAt` is the "database clock at commit". **That was technically wrong:** PostgreSQL's `now()` is the transaction's start time, and no ordinary timestamp gives commit order [TU: confirm for the chosen database setup]. **Knowledge order is therefore defined by a publication position, not by a timestamp** (§6).
2. **[TD] One platform-wide publication sequence, assigned and committed inside one serialised publication step.** This gives a determined, gap-free knowledge order across all countries. **As a consequence, a cross-country revision is one publication with one position**, not two coordinated logs (§7).
3. **[AC] D-87 covers countries and areas only.** Names of the five level-1 regions and of territorial units below level 2 are **not ruled**. They are handled as [OQ] items (§11), and no rule is invented for them.

## 1. Entity types and identity guarantees (refinement A)

| Entity type | What it is | Admitted by | Identity guarantee |
|---|---|---|---|
| **World** | The single level-0 entry | Platform definition (D-81) | One; never changes |
| **Statistical region** | One of the five M49 regions | [AD] D-85 | One per M49 region |
| **Country or area** | A neutral level-2 entry | [AD] D-86: ISO 3166-1 listing, or an explicit Principal decision | One per admission; **never a statement of status** |
| **Territorial unit** | An official place below level 2 in a country's designated scheme | A territorial claim with a territorial competence | One per legal entity **as the competent instrument characterises it** |

**Five things kept apart:**
1. **PublicDigit identity** (`ReferenceUnitId`): minted once, never deleted or reused.
2. **Real-world entity identity:** what the identity stands for. **For territorial units this is a governed judgement**, not something the system can compute.
3. **External identifiers**, each in a namespace (below).
4. **Source-attributed names.**
5. **CLDR display names** (D-87).

**Identifier namespace [TD]:** the scheme (issuing body and standard), the code type, and the scope. Examples:

| Namespace | Example value |
|---|---|
| (ISO 3166 MA, 3166-1, alpha-2, global) | HK |
| (ISO 3166 MA, 3166-1, alpha-3, global) | HKG |
| (ISO 3166 MA, 3166-2, subdivision, NP) | a province code |
| (UNSD, M49, numeric, global) | 344 |
| (Statistisches Bundesamt, AGS, DE) | a municipality key |

**Rules the system enforces:**
- **(I-1)** Within one namespace and any instant of valid time, a value identifies **at most one** live subject.
- **(I-2)** A subject holds **at most one** value per namespace at any instant.
- **(I-3)** An identity is never deleted or reused.
- **(I-4)** Every *establish* claim cites an establishing instrument, or an assessment recording a duplicate search (name, identifiers, containment, tier) and its result.

**Code reuse:** ISO's CS was held by Czechoslovakia until 1993, and by Serbia and Montenegro from about 2003 to 2006 [BF, secondary sources; primary: ISO newsletters].
- **That is two subjects, each holding CS for a disjoint interval.** I-1 holds; the subjects are never merged.

**Reconciliation (same-as) [TD]:** a revision of kind *reconcile*.
- It declares, with evidence and an assessment, that identity B denotes the same entity as A.
- It also states the merged value for every slot where A and B conflict.
- From its publication position, belief resolves B → A. **B's claims stay; queries at an earlier τ still show B separately.** Consumers receive the mapping.
- **An erroneous reconciliation** is corrected by a *separate* revision, which restores B as independent from its own position. Both acts stay in history.

**What the system cannot enforce:** that no undiscovered duplicate exists. I-4 makes every establishment **accountable**, and reconciliation makes duplicates **repairable**.

## 2. Claim model (refinement B)

A **claim** is an insert-only assertion about a **slot**, with a value, a valid interval `[from, to)` and an evidence or policy basis. **Claims in the same slot supersede each other; claims in different slots never do.**

| Kind | Slot key (subject + qualifiers) | Meaning | Basis and competence | Valid time |
|---|---|---|---|---|
| Existence | (unit) | The unit exists | Territorial competence | Interval |
| Containment | (unit, scheme) | Parent in the designated scheme (one parent per scheme) | Territorial | Interval |
| Classification | (unit, scheme) | Class and tier (territorial tiers only, D-83) | Territorial | Interval |
| Boundary description | (unit, source) | A boundary representation | Territorial, or a cartographic source attributed as such | Interval |
| Succession | (predecessor, succession event) | Successor set | Territorial | Event date |
| Identifier | (subject, namespace) | Code value | The namespace's issuing body | Interval |
| Statistical grouping | (entry, grouping scheme = M49 regions) | Region membership | UNSD (D-85), with D-86's evidence types | Interval |
| Admission | (entry) | Level-2 entry admitted | **PublicDigit policy** (D-86): ISO listing or a Principal decision record | Interval |
| Source name | (subject, source, language, name type) | Attributed name; several coexist | The source using it (attribution, not authority) | Interval |
| **CLDR display name** | (entry, locale, form ∈ {standard, short}) | Display name (D-87) | CLDR release | From adoption of the release |
| Registry: authority, mandate | (registry item, attribute) | Issuer; its competence: scope, territory, subject, period, legal basis **as the jurisdiction actually provides** | That legal basis | Interval |
| Registry: precedence rule | (rule) | Decides between instruments within a stated scope | Its own legal basis | Interval |
| Dispute marker | (target claim) | "Competent instruments conflict; no covering rule" | Assessment | Knowledge only |
| Withdrawal | Same slot as the target | Explicit "no value" for an interval | Instrument or correction | Interval |

**Five things never conflated [TD]:**
1. The source's identity and authenticity (provenance check; a fingerprint shows only integrity).
2. The source's competence for **this** claim kind.
3. Legal or statistical authority.
4. PublicDigit's admission policy (D-86).
5. PublicDigit's publication approval (D-84).

**A mandate's legal basis is recorded as the jurisdiction provides it.** This may be a constitution, a statute, delegated legislation, or a municipal statute. **No universal chain ending at a constitution is assumed** (v2 corrected).

**How D-85, D-86 and D-87 avoid false authority:**
- ISO supplies **identifier** claims and is the trigger for **admission** under PublicDigit's policy;
- UNSD supplies **grouping** and **identifier** claims;
- CLDR supplies **display-name** claims;
- **none of them supplies a territorial claim.**

## 3. CLDR and D-87 (refinement C)

| Concern | Design |
|---|---|
| Association | Display-name claims on the country-or-area entry, joined by its ISO alpha-2 identifier (CLDR keys territories by region code) at publication; the claim records the CLDR release |
| Locale selection | The user's locale; **CLDR's own locale inheritance** applies (for example ne-NP → ne → root) [TD; CLDR inheritance as documented by CLDR] |
| Standard and short forms | Two slots: (locale, standard) and (locale, short) |
| When the short form is used | **A presentation rule** ("space is tight", D-87): decided by the interface component, not by the domain |
| Missing short form | Fall back to standard |
| Missing locale | Inheritance chain; finally root or English per CLDR |
| Source names | Separate slots; shown as attributed alternatives in details |
| CLDR update | A new release is evidence for a **name** revision: non-structural (D-84), one steward |
| History | Belief at τ yields the display name that was published then |

## 4. Valid time and knowledge (refinement D)

- **Valid interval `[from, to)`:** `from` inclusive, `to` exclusive; an absent `to` means "until further notice". Adjacent intervals meet without overlap.
- **Knowledge position:** each publication has a position *p* in the platform sequence (§6). A claim is **known at τ** if its revision's publication position is ≤ position(τ).
- **Belief(slot, t, τ)** = the value of the claim, among claims in that slot known at τ whose interval contains t, with the **highest publication position**.
  - A **withdrawal** yields "no value".
  - **Dispute state at τ:** the latest dispute or clearance marker on that claim known at τ.
- **Supersession:** a later claim in the same slot supersedes earlier ones **only for the valid time it covers**. Earlier claims remain known and answerable for every earlier τ.
- **Correction of a correction:** another claim in the slot, with a higher position.
- **Several simultaneous facts:** different slots (several identifiers, names, locales). They never interfere.
- **Retroactive end:** an existence claim with a shorter interval, plus the reassignment of children (§5).

## 5. Validation of a revision (refinement F)

**Revision scope:** the claims it adds.

**Validation read set (derived, never hand-picked):**

| From the revision's claims | Must also be read |
|---|---|
| Any claim on unit U over interval V | All published claims of U overlapping V |
| Containment or existence changes of U | U's parents and **children** in the scheme over V |
| Classification changes | The profile (tiers) over V; parents and children over V |
| Identifier claims | **All subjects holding the same (namespace, value) over overlapping intervals** (I-1), including subjects outside the revision |
| Succession | Predecessors and successors' existence |
| Grouping, admission | The entry's identifiers; D-86 evidence types |
| Evidence citing mandates and precedence rules | Those registry facts at the claim's valid time |
| Supersession or dispute targets | The target claims' current state |

**Invariants checked over the whole read set and every affected interval:**
- G-4: tiers strictly increase along containment;
- G-5: successors named;
- G-6: one meaning per tier; no class ends while units carry it;
- I-1, I-2: identifier rules;
- D-83: classification only on territorial tiers;
- D-82: territorial claims need territorial competence.

**Example:** a parent's end is moved earlier. Its children's containment over the removed interval must be reassigned or ended **in the same revision**, or validation fails.

## 6. Publication consistency and `closedThrough` (refinement E)

**Recommendation [TD]:**
1. **One platform publication sequence.** Publication is a single serialised step (one platform-wide lock).
2. **Inside the step:** re-check governance (§9); validate against the authoritative published claims at the current last position; take the next position *p*; write one publication entry (position, revision, `recordedAt` = statement time inside the step); commit; release the lock.
3. **Because positions are assigned and committed under the same lock**, commits happen in position order. **There are no gaps**, and no earlier position can appear later.
4. **`closedThrough`** = the highest committed position, together with its `recordedAt`. It only grows.
5. **A τ given as a time** maps to "the highest position whose `recordedAt` ≤ τ". The mapping is monotonic because positions and recorded times rise together under the lock. **Refused if τ is later than `closedThrough`** (CR-01 §3: never a partial answer).
6. **Several logs:** "country log" and "global log" are **partitions of one sequence** (views by affected country, or global). **One τ is consistent across all of them by construction.**
7. **A query during concurrent publication** reads a position ≤ `closedThrough`, so it cannot see a half-committed state. Claims of unpublished revisions are invisible because no entry references them.

**Rejected:**
- **per-log positions with a cross-log watermark:** correct but more complex, and needed only if publications become frequent;
- **timestamps as order:** not commit order.

**[TU]** Contention: publications are human-approved and rare, so a short global step is acceptable. **Validation of a very large revision must not run long under the lock:** validate first without the lock, then re-validate under the lock only the part of the read set touched by positions published since. Measure during implementation.

## 7. Aggregates and boundaries (refinement G)

| Aggregate | Consistency it protects | Why this boundary |
|---|---|---|
| **`Revision`** | Internal completeness: every claim has its kind's basis; the successor and reassignment rules; content **frozen at approval** | Long-lived preparation; independent lifecycle |
| **`PublicationLog`** (one, platform-wide) | The published state's invariants (§5) over the affected partitions; the sequence; `closedThrough` | **Knowledge order is platform-wide** (§6). A cross-country revision is one position, so **one aggregate per transaction** without hidden coordination |
| `Candidate` | Missing-unit submission lifecycle; linking of submissions for one place | Different inputs and outcomes from objections |
| `Objection` | Counter-evidence lifecycle; target unchanged until an outcome is published; reconsideration only with new evidence | Same |
| `StewardRoster` | Appointments over time; matter reviewers; independence declarations; **only references to Identity's canonical persons** | D-84 and D-84a need roster state "as at" a time |

**Withdrawn from v2:** separate `CountryPublicationLog` and `GlobalPublicationLog` aggregates. They are now partitions (read models) of the one log.

**Is the single log a bottleneck?** Publications are rare and the critical section is short (§6, [TU]). **Validation scope stays per partition**, so a change in Germany never reads Nepal.

**Application service versus aggregate:** the `Publish` application service loads the approved revision (read-only, frozen) and calls `PublicationLog.publish(revision)`, which validates and appends. **Only the log changes in that transaction.** The revision's "Published" state is derived from the entry.

## 8. Lifecycle and governance (refinement H)

**Roles:**

| Role | Who | Rules |
|---|---|---|
| Preparer | A steward | Creates and edits the revision while it is a draft |
| First approver | A steward | Freezes the content. While only one steward exists, may be the preparer |
| Second approver | **A different natural person** (distinct canonical person) from the preparer and the first approver; a steward or a matter reviewer | **Required for structural revisions whenever two or more stewards are appointed at publication time** (D-84) |
| Matter reviewer | Appointed by the platform owner for one matter | Approves only that matter (D-84a) |

**Lifecycle:** `Draft` → `Approved` (frozen) → `Published` · `Rejected` · `Stale` (validation failed; re-preparation is a new revision referencing it).

**Rules at publication:**

| Situation | Outcome |
|---|---|
| Second steward appointed after a single approval of a structural revision | A second approval is needed before publication |
| The approver's appointment ended before publication | Approval still valid (the approver was a steward when approving) |
| **Material conflict (D-84a test) found before publication** | That person's approval is void; a non-conflicted approver is needed |
| **Material conflict found after publication** [AC] | A non-conflicted steward or matter reviewer re-assesses. If confirmed, an assessment is recorded; if not, a correction revision follows. **The published fact changes only by publication** |
| **The D-84a test itself** | Applied to the **deciding person only**: a material organisational interest **and** a judgment between competing positions. Membership of a submitting organisation alone is not a conflict |

## 9. Context ownership (refinement I)

| Capability | Owner | Notes |
|---|---|---|
| Published places, claims, history; **browse and search** of published places (filters: entity type, tier kind, live at asOf, τ) | Geography | Read-only for everyone |
| **`PlaceLookup`** | Geography | Stateless match of a **place name, alias or code** to published places, with match quality. **No address parsing, no street matching, no address validation** |
| **Coverage selection and coverage rules** (for example excluding statistical regions, or allowing organisation areas) | **Organisation** | Uses Geography's browse; **owns the decision** |
| Address records, parsing, street matching against address sources, stored resolution results | **The address owner.** Recommended: Organisation's person-profile capability ([TD], **not ratified**) | Uses `PlaceLookup` |
| Belonging, entitlement, sovereignty | Not Geography | D-79, D-80, D-86 |

v2's "coverage selector" in Geography is **withdrawn**.

## 10. Events and read models (refinement J)

| Event | Meaning |
|---|---|
| **`RevisionPublished`** (position, revision, kinds, affected partitions and subjects, reconciliation mappings) | The single publication fact, **one per position**, including cross-country revisions |
| `CandidateDecided`, `ObjectionDecided` | The submitter's outcome |

| Read model | Owner | Time parameters |
|---|---|---|
| Place browse and search | Geography | asOf, τ |
| Profile view | Geography | asOf, τ |
| Hierarchy browse | Geography | asOf, τ |
| Historical view (belief) | Geography | asOf, τ |
| Display-name resolution (D-87) | Geography | locale, form, τ |
| Partition views (country, global) | Geography | τ |
| Coverage records and selection | **Organisation** | Organisation's own |
| Address resolution results | **Address owner** | Its own, recording the Geography τ used |

## 11. Open items

| Item | Kind |
|---|---|
| Display names of the **five level-1 regions** (CLDR also names M49 regions): extend D-87, or another rule? | **[OQ]** |
| Display names of **territorial units below level 2** (official name, transliterations, translations) | **[OQ]** |
| L-4c regions without an M49 row (unassigned meanwhile) | [OQ], later |
| Japanese blocks; Q-2 constituencies | [OQ], later |
| Address-owner boundary | [TD] recommendation, not ratified |
| Commit-order assumption; lock contention; large-revision validation | [TU] |
| Primary verification of historical examples | Evidence |

## 12. Scenario results

See the companion assessment, §3 (30 scenarios).
