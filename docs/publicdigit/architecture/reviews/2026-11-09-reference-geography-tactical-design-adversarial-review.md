# Reference Geography: adversarial review of the tactical DDD baseline

| | |
|---|---|
| **Status** | Review, PROPOSED. Challenges `2026-11-09-reference-geography-tactical-design-baseline.md`; it does not replace it. Corrections are proposals for the Principal DDD Architect's assessment |
| **Commission** | "Session A — Adversarial Review of Reference Geography Tactical DDD Baseline" (Principal DDD Architect, 2026-11-09) |
| **Binding** | D-31, D-73 / CR-01, D-75, D-79, D-80, D-81, D-82, D-83, D-84, D-84a, D-85, **D-86** |
| **Labels** | [AD] accepted decision · [BF] established fact · [AC] architectural conclusion · [TD] tactical decision (architect) · [TU] technical uncertainty · [OQ] Principal decision |

## 1. Executive assessment

**The direction holds:** propose, then approve, then publish; nothing visible without publication; append-only history; governance enforced at approval. **But the baseline has three real defects and four gaps, and it is not ready for ratification as written.**

| # | Finding | Kind |
|---|---|---|
| **R-1** | "Every claim needs a competent public authority" **contradicts D-85 and D-86**: ISO assigns codes, UNSD groups statistically, and PublicDigit itself admits level-2 entries | **Contradiction** |
| **R-2** | Nobody owns "one identity per real-world place"; a unique technical ID does not prevent two IDs for one place | **Missing invariant** |
| **R-3** | Publication validation checks the published state **at one date**. A retroactive correction can make the timeline inconsistent at other dates | **Missing invariant** |
| R-4 | What "current belief" means when knowledge is later corrected is undefined | Unclear |
| R-5 | An approved revision could change before publication; approval was not bound to content | Gap |
| R-6 | Conflict of interest was over-broad ("all office-holders of the submitting organisation"), against D-84a's test | Contradiction (small) |
| R-7 | `Authority` as a separate aggregate is unnecessary; authority and precedence records are structural changes under D-84 and should be published like geography | Over-engineering |
| R-8 | "No direct relationship" for Membership, Governance and Communication confuses owning with consuming | Unclear responsibility |
| R-9 | `DisputeMarked` / `DisputeCleared` duplicate `RevisionPublished` | Duplication |

## 2. Decisions to preserve

- Separation of evidence, authority, approval and publication.
- **Unit and profile as read models**; nobody edits a published place.
- **Nothing visible except through a log entry** (dispute markers included).
- **Serialised publication per log**, validating against the published state.
- `Candidate` versus `Objection` as distinct lifecycles (different inputs, different outcomes).
- **Typed region evidence; no relations between level-2 entries** (D-86).
- Territorial versus address tiers; **coverage only on territorial tiers** (D-83).

## 3. Findings with failure scenarios and corrections

### R-1 Authority model too narrow (contradiction)

- **Proposition challenged:** "every claim has an `EvidenceRef` whose mandate covers…"; "a mandate chain ends at a constitution".
- **Failure:** importing ISO's entry for Hong Kong under D-86 requires a public territorial authority. ISO has none, so **D-86's default admission cannot be executed.** The same holds for M49 region membership under D-85.
- **Correction [TD]:** claims are **typed by kind**, and each kind needs its own competence:

  | Claim kind | Competent for it | Competence record |
  |---|---|---|
  | Territorial fact (existence, containment, boundary, classification, succession) | Public authority with a territorial mandate | Mandate chain ending at a constitution |
  | Identifier (ISO, M49, national code) | The code-assigning body | That body's stated scope (ISO 3166 scope; UNSD; national statistical office) |
  | Statistical grouping (D-85) | The statistical body | Its statistical mandate |
  | Level-2 admission (D-86) | **PublicDigit's platform policy**: ISO listing by default, otherwise a Principal decision | The policy (D-86) or the recorded Principal decision |
  | Name | The source that uses it | Attributed, not authoritative |

- **Authenticity versus integrity:**
  - a fingerprint proves **integrity** (unchanged since receipt);
  - **authenticity** (that it came from the issuer) is a separate assessment: official channel, certified copy, signature.

### R-2 Identity uniqueness (missing invariant)

**Failure scenarios:**
- (a) Two missing-unit submissions for one new municipality become two candidates, then two revisions and two identities.
- (b) The initial import of Nepal's districts from two datasets creates duplicates.
- (c) **Code reuse:** ISO's "CS" stood for Czechoslovakia and was later reassigned to Serbia and Montenegro [I, general knowledge; verify]. Treating "CS" as one entry merges two places.

**Correction [TD]: the enforceable invariant, owned by the publication log at publication:**
1. **No two live identities carry the same identifier from the same source for overlapping periods.**
2. Every **establish** claim must cite either **an establishing instrument** (the entity is new) or an **initial-recording assessment** recording a duplicate search (name, codes, containment, tier at the date) with its result.
3. A duplicate discovered later is repaired by a **same-as revision**. Neither identity is deleted or reused.
4. Candidates for one place are **linked** (the second references the first).

**Residual risk accepted:** real-world identity resolution cannot be fully automatic. The model makes every establishment **accountable and repairable**. Continuity versus succession follows X.3, decided per case with its instrument.

### R-3 Validation only at one date (missing invariant)

**Failure:**
- Parent P exists 1 Jan–30 Jun (entry 1).
- Child C is attached to P from 1 Jun (entry 2).
- A correction moves P's end to 31 May (entry 3).
- Validating entry 3 "as at today" passes, but **C has an ended parent from 1 to 30 June.**

**Correction [TD]:** publication validates G-4, G-5, G-6 and the R-2 rules **over the valid-time intervals the revision touches**: every interval of every affected unit and of its parents and children. It does not check only the current date. **The touched set therefore includes the valid-time range**, and corrections that shorten a parent's life must reassign or end the children in that range.

### R-4 Meaning of "current belief"

**Correction [TD]:**
- **belief(t, τ)** = for each subject and claim kind, the claim from the **latest log entry at or before τ** whose validity covers t, together with its dispute state at τ.
- This is the platform's knowledge rule: **later published knowledge supersedes earlier published knowledge.** It is different from **source precedence**, which decides *what may be published* (R3.5).
- Worked example (R-3 data):
  - belief(15 Jun, τ before entry 3) = P exists;
  - belief(15 Jun, τ after entry 3) = P ended.
  - Both stay answerable.
- **Validity is a half-open interval `[from, to)`;** an open end means "until further notice".

### R-5 Approval not bound to content

**Failure:** a claim is edited after approval and published unreviewed.

**Correction [TD]:**
- **approval freezes the revision's content**;
- any change after approval creates a **new revision** that supersedes the old one, so the approval does not carry over;
- lifecycle: `Draft` → `Approved` → `Published` · `Rejected` · **`Stale`** (terminal: validation failed at publication; re-preparation is a new revision referencing it).

**Governance at publication** (each supported by D-84 and D-84a):

| Situation | Outcome |
|---|---|
| A second steward was appointed after a single approval of a structural revision | Publication needs the second approval, because it happens "from the appointment" (D-84) |
| The approver's appointment ended before publication | Approval still valid if the approver was a steward when approving (no rule says otherwise) |
| A conflict is declared after approval | The approval is void; a non-conflicted approver is needed (D-84a) |

### R-6 Conflict-of-interest scope

The baseline treated all office-holders of a submitting organisation as conflicted.

**Correction:** apply D-84a's test **to the person deciding** (steward or matter reviewer). There is a conflict only where that person has a material organisational interest **and** the decision is a judgment between competing positions.
- A candidate backed by a clear establishing instrument, with no duplicate in question, is **routine**.
- A contested candidate or an objection is **judgment**.

### R-7 `Authority` as an aggregate

[AD] D-84: "authority and mandate records" and "precedence rules" are **structural changes**. They must carry history, `τ` and two-person approval.

**Correction [TD]:** authorities, mandates and precedence rules are **published through the `GlobalPublicationLog` by revisions**, and read as **read models**. The `Authority` aggregate is withdrawn.

### R-8 Context relationships

**Correction [AC]:**
- **Any context may consume published geography read-only** through the published language.
- **Only Geography publishes.**
- **A context that applies geography to a decision owns that decision**: coverage and residence rules belong to Organisation, a postal format to Communication.

| Context | Consumes |
|---|---|
| Organisation | Coverage, areas, address resolution |
| Communication | Country and area names for postal formatting (if needed) |
| Election | Nothing direct (through Organisation) |
| Membership, Governance | Nothing needed today; permitted if a capability arises |

**Address resolution:**
- `ResolvePlace` is a **stateless query** offered by Geography over official names, aliases and codes;
- **storing results and matching streets against address sources is the address owner's job.**
- Geography holds no address text.

### R-9 Events

**Correction:**
- **`RevisionPublished`** carries the revision kind (structural, attribute, correction, dispute-marker, registry) and **all log positions** (two for a cross-country revision);
- **`CandidateDecided`** and **`ObjectionDecided`** remain.

The separate dispute events are dropped.

## 4. Mandatory failure scenarios

| # | Scenario | Baseline | After corrections |
|---|---|---|---|
| 1 | Duplicate identity | **Fails** | R-2: identifier rule, establishing instrument or initial-recording assessment, same-as repair |
| 2 | Code change without the entity ending | Succeeds | New identifier claim; identity unchanged. Reuse handled by R-2 rule 1 |
| 3 | A source assigns codes but has no territorial competence | **Fails** (no claim kinds) | R-1: identifier claim, competent as code-assigner only |
| 4 | Statistical grouping mistaken for containment | Needs clarification | Grouping is its own claim kind, never containment. **[AC] coverage may reference level 2 and territorial tiers, not the statistical levels 0–1.** NRNA's "Europe" covers countries explicitly (M49 puts Cyprus in Asia) |
| 5 | Large revision (parent and many children) | Succeeds | Prepared invisibly; one entry; validation over intervals (R-3) |
| 6 | Two revisions on the same state | Succeeds | Second is `Stale` (R-5) |
| 7 | Single approval, then a second steward appointed | Unclear | R-5: second approval needed at publication |
| 8 | Conflict found between approval and publication | Unclear | R-5: approval void |
| 9 | Late correction of a past effective date | **Fails** (R-3) | Interval validation; belief(t, τ) per R-4 |
| 10 | Dispute marker and a change to the same claim, concurrently | Unclear | Both revisions include the claim's (subject, kind) version in their touched set; the later is `Stale`. A marker applies to the claim version it names; a correction supersedes it unless re-marked |
| 11 | Cross-country boundary change | Succeeds, with a caveat | Both logs live in one database, so one transaction gives one `recordedAt` and two entries. A query spanning both needs each `closedThrough ≥ τ` |
| 12 | Consistent snapshot across global and country logs | Unclear | **τ must be ≤ every involved log's `closedThrough`**; `recordedAt` is database time, so one τ is meaningful across logs |
| 13 | Organisation area submitted as official | Succeeds | No territorial mandate → rejected (D-82) |
| 14 | Street mistaken for a place | Succeeds | Address tiers hold no claims; coverage selector excludes them |
| 15 | Objection reconsidered | Succeeds | New objection, new evidence, referencing the old |

## 5. Revised assessments

**Aggregates (five, down from six):**

| Aggregate | Invariant | Atomic operation |
|---|---|---|
| `Revision` | Complete, evidenced, typed claims; content frozen at approval | Approve |
| `CountryPublicationLog` | Published-state integrity over valid-time intervals; identifier uniqueness; ordering; `closedThrough` | Publish |
| `GlobalPublicationLog` | The same for levels 0–2, level-2 entries, **authority, mandate and precedence registry** | Publish |
| `Candidate`, `Objection` | Governed inputs | Decide |
| `StewardRoster` | Appointments over time; matter reviewers. **Holds only a reference to Identity's canonical person, no personal data** | Appoint, end |

- **Global log size:** about 250 entries plus a small registry; it grows slowly, so it is acceptable as one aggregate.
- **Validation reads the authoritative published claim set inside the serialised step, never an asynchronous projection** [TD].

**Temporal model:** [from, to) validity; belief(t, τ) (R-4); `closedThrough` per log; one τ across logs as described in scenario 12.

**Integration:** R-8.

## 6. Principal decisions

**None new.** The corrections are architectural and follow from D-84, D-84a, D-85 and D-86.

**Still open from before:**
- **L-4b** (display names): **before any real data is imported**;
- **L-4c** (no-M49-row regions): an unassigned region is allowed meanwhile;
- Japanese blocks;
- Q-2.

## 7. Recommendation

**Not ready for ratification yet.** First:
1. incorporate R-1 to R-9 into the tactical baseline;
2. verify the ISO "CS" reuse example against the ISO 3166 Maintenance Agency record before citing it as fact.

**After that the tactical baseline can go to ARB.** The remaining uncertainties are technical:
- duration of very large publications;
- the performance of interval validation;
- the cross-country transaction.
