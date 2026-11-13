<!-- markdownlint-disable MD013 -->
# Reference Geography G-6c: D-90 governance notices: design for Principal review

**Status:** PROPOSED, **revision 2** (the Principal review of 2026-11-13: "accept with technical conditions T-1 to T-7"). **Design only: nothing is implemented. Implementation starts only after the Principal explicitly accepts this revision,** because it changes the accepted G-4 publication write path. **Story:** PBDIGIT-346.
**Revision 1:** `926359ee8`. **Revision 2** adds T-1 to T-7, verified against the code (§0).

**Consumes, unchanged:**
- v3.11 §6 (lock, boundaries), §8 (five cases, D-90 K, the confirmation table), §10 (`GovernanceNoticePublished`), §15, §16;
- D-84, D-84a, D-88, D-89, D-90, D-225;
- G-3 (`excluded(r)`, E(v)), G-4 (the lock, PO-1 to PO-7), G-5 (`Knowledge`, the read model, the feed).

## 0. Facts from the code (verified 2026-11-13)

| Fact | Where |
|---|---|
| **E(v) is transitive:** `knownSameAt(a, b, v)` unions every merge ≤ v not reversed ≤ v (union-find). **One defect can rest on a chain of merges** | `StewardRoster::knownSameAt` |
| **A reversal may arrive before its merge;** that merge then never counts | `StewardRoster::recordReversal`, `knownSameAt` |
| **Idempotency checkpoint already exists:** a merge or reversal is a roster entry unique by (merge id, kind); a replay writes nothing (`null`) | `StewardRoster::recordMerge/recordReversal`, `refgeo_roster_entries` |
| **Case B at publication:** `mayPublish` counts approver X only if X is not known-same as anyone in `excludedPersons()` at the publication's roster version | `PublicationGovernance::mayPublish`, `isExcluded` |
| **The publication row stores** `roster_version`, `governance_rule` and the counted approver (`independence`); `kind IN ('revision', 'notice')` already exists | `refgeo_publications` |
| **Merges are recorded by `StewardRosterStore::writeUnderLock`,** which cannot append a publication row | `IdentityNoticeConsumer` |
| **A content version carries a digest** | `Revision`, `ContentVersion` |

## 1. In business terms (unchanged)

**Sometimes PublicDigit learns after publication that an approval wasn't as independent as it looked** (for example, Identity reveals that the "second steward" was the preparer).
- **The fact stays in force** (D-90, option K).
- **The record says so openly:**
  - **finding:** the approval is defective;
  - then **confirmation** by a genuinely independent person, or a **correction** (a new revision);
  - **closure:** if the defect stops being true (a merge reversed), the finding is closed as unfounded.
- **Nothing earlier is rewritten.** Every notice is a new stream entry, so "the approval status as known at p" is complete and repeatable.

## 2. Accepted rules (not reopened)

| Rule | Source |
|---|---|
| Case B: a structural, era-B, second-person publication whose counted approver X becomes known-same as someone in `excluded(r)` | v3.11 §8 |
| D-90 K: facts stay in force, flagged; confirm or correct; never suspend or void | D-90 |
| The finding is system-triggered; closure on case E | v3.11 §8 |
| Confirmation: exact content version (digest); eligible at the **current** roster version; not excluded; a conflict declaration (D-84a) | v3.11 §8 |
| A notice is a stream entry: own position and `recordedAt`, no claims, under the one lock | v3.11 §8, §15, §16 |
| PO-2: one write path for claims, notices, the sequence and the roster | v3.11 §6 |

## 3. Ownership and boundaries

- **Owner:** Reference Geography. Identity only *notifies* (D-225): it never decides about publications.
- **Domain (pure):**
  - `ApprovalDefects` (service);
  - `GovernanceNotice`, `ApprovalCase`, `PublishedApproval` (value objects);
  - `ApprovalStatus` (read value).
- **Application:**
  - `IdentityNoticeConsumer` (moved under the ledger lock);
  - `ConfirmPublication` (new);
  - `PublishRevision` (gains an optional `corrects` position);
  - `PublishedGeography::approvalStatus`.
- **Infrastructure:** `PostgresPublicationLedger`, the only writer of notices, and the new person index. Both are written under the lock.
- **No cross-context read:** Identity's merges are already in the roster (G-3). Organisation and Membership are untouched.

## 4. Domain model

| Element | Kind | Content and invariants |
|---|---|---|
| `PublishedApproval` | value | position p, revision r, content version v, digest, **counted approver X**, `excludedPersons(r)`, publication roster version v0. **Only `SecondPersonApproved` publications** (era B, structural) can be case B |
| `ApprovalCase` | value | `defective(p, at version w)`: X is known-same as some e ∈ excluded(r) under E(w) · `sound(p, w)` otherwise. **Basis:** the merge ids on the union path(s) joining X and e, for audit only |
| **`ApprovalDefects`** | domain service | `evaluate(roster, w, approvals, openFindings)` gives **new findings** (sound before, defective at w, no open finding) and **closures** (an open finding whose case is sound at w). Pure; deterministic; ordered by position |
| `GovernanceNotice` | value | kind ∈ {finding, confirmation, closure}; finding: concerns p, merge basis, roster version w; confirmation: concerns **finding f**, confirmer, digest, w; closure: concerns **finding f**, reversal or merge that ended it, w |
| `ApprovalStatus` | read value | status ∈ {approved, approval-defective, confirmed, corrected, finding-closed} + the ordered history of its notices and corrections |

## 5. T-1: one atomic act for an Identity notice

```
IdentityNoticeConsumer::merged(mergeId, persons)   (and ::reversed(mergeId))
  └─ PublicationLedger::withLock(L)                          ← THE one lock (refgeo_sequence FOR UPDATE), one transaction
       1. w = L.recordRoster(r ⇒ r.recordMerge(...))          ← null = already applied (the CHECKPOINT): return, write nothing
       2. affected = L.approvalsInvolving(classes of the merged persons at w)   ← publication-person index (T-7)
       3. (findings, closures) = ApprovalDefects.evaluate(roster@w, w, affected, L.openFindingsFor(affected))
       4. for each, in position order: L.appendNotice(notice)  ← each takes the next position; recordedAt as G-4
       5. commit                                              ← roster entry + every notice, or nothing
```

- **No merge without its findings, no finding without its merge:** steps 1 to 4 share one transaction. A failure rolls everything back.
- **The checkpoint is the roster entry itself,** unique by (merge id, kind) in the same transaction, **so it can never advance alone.** A separate checkpoint table would be a second source of truth (disagreement with the review's wording, same intent).
- **Redelivery:** step 1 returns `null`; nothing else runs.
- **Multiple publications:** step 3 returns every affected publication; all are appended in step 4.
- **Serialisation with publication:** both take `refgeo_sequence FOR UPDATE`. A publication waits for the merge, or the reverse, and then re-checks governance at the new roster version (G-4). **A publication committed after the merge is evaluated by `mayPublish` and never needs a finding** (case A).
- **Closes through τ** (G-4 `closeThrough`) wait for the same lock, so no τ answer can see the merge without its findings (v3.11 §6).

## 6. T-2: historical evaluation

- **Inputs:** the roster as committed through w (all entries); for each candidate publication, its `PublishedApproval` (X, excluded(r), v0, from the publication-person index); the open findings.
- **Rule:** a finding for p at w exists exactly when case B holds at w and didn't hold under the last evaluation that produced its current state. **The evaluation is always E(w):** never "current identity" outside the roster's versioned history.

| Situation | Result |
|---|---|
| Merge **after** publication makes X ~ e | **finding** at w |
| Merge **before** publication | `mayPublish` refused it, or the counted approver was independent at v0 (case A); **no finding** |
| Merge involving people in r but not X with e (case D) | none |
| Era A or non-structural (case C) | not evaluated (no `PublishedApproval`) |
| **Chain:** m1 (X~A), then m2 (A~P), P ∈ excluded | finding at m2's version, basis {m1, m2} |
| **Two independent chains** prove X~P; one reversed | still defective, so **no closure** |
| Reversal of any merge on the **only** path | closure (case E) |
| Reversal arriving **before** its merge | the merge never counts: no finding |
| Defective again after a closure | a **new** finding (a new episode); history keeps both |
| An open finding already exists for p | **no second finding** (also a unique index, T-7) |
| One publication with two approvers / two merges | evaluated per publication: one open finding at a time; its basis lists every merge on the path |

## 7. T-3: confirmation names the finding

`ConfirmPublication::confirm(FindingPosition f, ContentDigest d)`, with the actor from `ActingPerson`, under the lock. **Refused unless all hold at the current roster version w:**
1. f is a **finding** notice, and at w it is **open** (no closure, no confirmation, no correction of its publication);
2. d equals **the digest of the content version the finding concerns** (the exact published content);
3. the actor `mayActForAt(r, w)` (a steward, or the matter reviewer for r);
4. the actor is **not** known-same at w as anyone in excluded(r) **or as the defective approver X**;
5. a conflict declaration for (actor, r) exists at w, and is not a material conflict for a judgment matter (D-84a).

**It's appended as a notice:** confirmation, concerning f, with the confirmer and the digest. **One per finding** (unique index, T-7).
- **It changes no claim and no publication, and it isn't a publication approval:** no `mayPublish`, no new content.
- **If the content is wrong, the route is a correction.**

## 8. T-4: closure (exact)

**After every roster change (merge or reversal) in T-1, every open finding among the affected publications is re-evaluated at w. It is closed exactly when its case is sound at w.**
- **So a finding still supported by another effective merge is never closed** (two-chain row in §6), and **a finding on a chain closes when any link on its only path is reversed.**
- **The closure records** the roster entry that made it sound (reversal id) and w.
- **Earlier entries are never rewritten.**
- **Repeated delivery:** the checkpoint (§5).
- **A closure is final for its finding;** a later defect is a new finding (§6).

**This refines the review's "close only findings whose basis is that specific merge":** with transitive merges, a basis is a set, and only re-evaluation is exact.

## 9. T-5: correction link

- **Where:** on the correcting revision's **publication row** (`corrects_position`), set by `PublishRevision::publish(r, corrects: p)` under the lock. **The `Revision` aggregate is unchanged.**
- **Refused at publication unless all of these hold:**
  - p exists, is earlier, and is a **revision** row (not a notice);
  - **p has an open finding;**
  - **the correcting revision has at least one claim in a slot of p's claims** (it replaces some of them: D-90 "a correction replaces them").
- **"Related" means exactly this slot overlap** (TECHNICAL). It's stated, not invented: the minimal meaning of "replaces".
- **Database:** an FK and `corrects_position < position`; a trigger checks the target kind is `revision`. The application checks the open finding and the slot overlap (under the lock).
- **A draft never corrects:** "corrected at q" is derived only from **publication** rows. A prepared or approved draft has none.

## 10. T-6: historical status

`GeographyReadModel::approvalStatus(int $position, Knowledge $k): ApprovalStatus` (published, additive).
- **Knowledge has one meaning (G-5):** a stream position k.position. **As at τ** is `knowledgeAtTime(τ)`, which closes through τ and maps it to the highest position with `recordedAt ≤ τ` (ties ordered by position).
- **Only entries with position ≤ k.position are read, so a later notice can never change an earlier answer.**
- **Derivation**, over entries ≤ k concerning p (findings for p; confirmations and closures of those findings; corrections of p):
  1. **any correction q ≤ k:** corrected (q) (it dominates: the content was replaced);
  2. **else no finding:** approved;
  3. **else the latest finding f:** closed gives finding-closed; confirmed gives confirmed; otherwise approval-defective.
- **The history** lists every finding, confirmation, closure and correction ≤ k, in position order, so no episode is lost.
- **p beyond the knowledge,** or not a revision row: refused (`KnowledgeNotAvailable`) or `\InvalidArgumentException`.
- **The feed (G-5c)** gains `noticeKind` and `concernsPosition` on `PublicationEntry` (additive).

## 11. T-7: schema, immutability, write path

**`refgeo_publications`, additive columns:** `notice_kind`, `concerns_position` (FK → position), `merge_basis jsonb`, `ended_by text`, `confirmed_by text`, `confirmed_digest text`, `corrects_position` (FK → position).

| Row | Must have | Must not have |
|---|---|---|
| revision | revision_id, content_version (as today); optionally `corrects_position` | notice_kind, concerns_position, merge_basis, ended_by, confirmed_* |
| finding | notice_kind, concerns_position (a **revision** row), merge_basis | revision_id, content_version, confirmed_*, ended_by, corrects_position |
| confirmation | notice_kind, concerns_position (a **finding** row), confirmed_by, confirmed_digest | revision fields, merge_basis, ended_by, corrects_position |
| closure | notice_kind, concerns_position (a **finding** row), ended_by | revision fields, merge_basis, confirmed_*, corrects_position |

- **CHECKs** encode the table above, plus `concerns_position < position` and `corrects_position < position`.
- **A BEFORE INSERT trigger** checks the kind of the referenced row (finding → revision; confirmation and closure → finding).
- **Unique partial indexes:**
  - one confirmation per finding;
  - one closure per finding;
  - **one open finding per publication**, enforced in the ledger under the lock (a finding is open iff it has no closure or confirmation; partial uniqueness can't see "open", so the domain guarantees it and a test proves it).
- **Notices have no claims:** `refgeo_claims` rows reference (revision_id, content_version), which notices don't have.
- **New `refgeo_publication_persons`** (position, person_ref, role ∈ {approver, excluded}): written by the ledger at each `SecondPersonApproved` publication; append-only and immutable (the same G-4 trigger function). It's backfilled from stored revisions in its migration (no production data exists: G-4 condition).
- **Unchanged G-4 safeguards:** `refgeo_publication_append` (gap-free positions, counters), the immutability triggers, the clock refusal. **Notices advance positions exactly like revisions** (the existing trigger already accepts `kind = 'notice'`).
- **Write path:**
  - only `PostgresPublicationLedger` writes notices and person rows;
  - **the no-bypass guard gains a rule:** `appendNotice`, `notice_kind` and `refgeo_publication_persons` are allowed only in the ledger and the migrations.

## 12. Tests to write first (none run; design only)

| # | Test | Level |
|---|---|---|
| 1 | Cases A to E exactly (B: finding; C, D: none; E: closure) | domain |
| 2 | Chain merge: finding at the second link; basis {m1, m2} | domain |
| 3 | Two independent chains, one reversed: **no** closure; the only path reversed: closure | domain |
| 4 | Reversal before merge: no finding | domain |
| 5 | Defective → closed → defective: two findings | domain |
| 6 | One merge affecting several publications: a finding for each, in position order | domain + PG |
| 7 | **Merge and its findings in one transaction:** a forced failure after the roster write leaves neither | PG |
| 8 | Redelivery: nothing written the second time | PG |
| 9 | A close through τ racing a merge: never sees the merge without its findings | PG (forked, G-4 method) |
| 10 | Publication racing a merge: serialised; the later one re-evaluates | PG |
| 11 | Confirmation: the open finding only; wrong digest refused; X or excluded refused; no declaration refused; a second confirmation refused; after closure refused | app + PG |
| 12 | Correction: the target is an earlier revision row with an open finding and slot overlap; a notice target refused; a draft changes no status | app + PG |
| 13 | `approvalStatus` at p and at τ before and after each notice; a later notice never changes an earlier answer | app + PG |
| 14 | Schema: every forbidden field combination refused; notice rows immutable; positions gap-free with notices interleaved | PG |
| 15 | No-bypass: notice writes only in the ledger | architecture |

**Mutation intentions:**
- a finding missed in B, or raised in C or D;
- a closure while another chain still holds;
- a merge committed without its findings;
- a confirmation by X or an excluded person;
- a digest not compared;
- a correction to a notice;
- the status reading beyond k.

**Each must be killed by an assertion.**

## 13. Implementation sequence (after acceptance; each step RED → GREEN → guide → commit)

1. **G-6c-1** schema: columns, CHECKs, triggers, `refgeo_publication_persons` and its backfill (tests 14).
2. **G-6c-2** domain: `ApprovalDefects`, `GovernanceNotice`, `ApprovalStatus` (tests 1 to 5).
3. **G-6c-3** the ledger: `recordRoster`, `appendNotice`, the person index at publication; `IdentityNoticeConsumer` under `withLock` (tests 6 to 10).
4. **G-6c-4** `ConfirmPublication` (test 11).
5. **G-6c-5** the correction link in `PublishRevision` (test 12).
6. **G-6c-6** the read model and the feed fields (test 13); no-bypass rule (test 15); mutation run; acceptance package.

## 14. Administrator implications

- **New Reference Geography migrations** (columns, triggers, `refgeo_publication_persons`): **not for production until the G-4 migration-approval control exists.**
- **No configuration, queue or scheduler:** the consumer runs in the code path that receives Identity's notices, as today.

## 15. Risks, assumptions, decisions

| Item | Kind |
|---|---|
| D-90, the cases and eligibility | **ACCEPTED** |
| T-1 to T-7 as written, **including two refinements of the review's wording:** the checkpoint is the roster entry (§5); closure by re-evaluation (§8) | **TECHNICAL**, for the Principal's acceptance with the design |
| "Related" for a correction = slot overlap with p (§9) | **TECHNICAL** (the minimal meaning of "replaces"); **flagged** in case the Principal wants it stricter |
| Confirmation after a correction is refused (resolved by the correction) | **TECHNICAL**, derived from D-90's either/or |
| Evaluating only publications involving the merged classes needs the new person index (§11) | technical **cost**: one row per approver and excluded person, per second-person publication |
| **Accept this revision before implementation** | **PRINCIPAL DECISION** |

**No new business rule. G-4's conditions, lock and single write path are kept.**

**Traceability:** v3.11 §6, §8, §10, §15, §16 · D-84, D-84a, D-88, D-89, D-90, D-225 · G-3, G-4, G-5 · the Principal review of 2026-11-13 (T-1 to T-7) · PBDIGIT-346.
