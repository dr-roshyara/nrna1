<!-- markdownlint-disable MD013 -->
# Reference Geography G-6c: D-90 governance notices: design for Principal review

**Status:** PROPOSED. **This changes the accepted G-4 publication write path, so it is not implemented before the Principal accepts this design.** **Date:** 2026-11-13. **Story:** PBDIGIT-346.
**Consumes, unchanged:** v3.11 §8 (the five cases, D-90 option K, the confirmation table), §10 (`GovernanceNoticePublished`), §15, §16; D-84, D-84a, D-88, D-89, D-90, D-225; G-3 (`excluded(r)`, merge notices), G-4 (the one lock, PO-1 to PO-7), G-5 (the read model, the feed).

## 1. In business terms

**Sometimes PublicDigit learns after publication that an approval wasn't as independent as it looked.**
- Example: Identity merges two accounts and reveals that the "second steward" who approved a structural change was the same person as its preparer.
- **The official fact stays in force** (D-90, option K): it rests on the official instrument, not on PublicDigit's approval.
- **But the record must say so openly:**
  1. **finding:** "this publication's approval is defective";
  2. then either **confirmation** by a genuinely independent person, or a **correction** (an ordinary new revision);
  3. **closure:** if Identity reverses the merge, the finding is closed as unfounded.
- **Nothing earlier is ever rewritten.** Every notice is a new entry in the official stream, so anyone asking "what was the approval status as known at publication p" gets a complete, repeatable answer.

## 2. What is already settled (ACCEPTED, not reopened)

| Rule | Source |
|---|---|
| Case B only: a structural publication in era B, whose approver is later merged with someone in `excluded(r)` | v3.11 §8, five cases |
| **K: keep the facts in force, flagged; confirm or correct.** No suspension, no voiding | D-90 |
| **The finding is system-triggered:** no personal judgement | v3.11 §8, confirmation table |
| **Confirmation:** the exact content version v (named by its digest); by a person not in `excluded(r)` at the **current** roster version, appointed, with a conflict declaration (D-84a) | v3.11 §8 |
| **A notice is a stream entry:** its own position and `recordedAt`, **no claims**, under the publication lock | v3.11 §8, §15, §16 (final validation) |
| **Closure on a reversed merge (case E);** earlier entries untouched | v3.11 §8 |
| **Approval status as at p or τ:** approved · approval-defective · confirmed · corrected (at q) · finding closed | v3.11 §8 |
| **PO-2: a single write path** for claims, notices, the sequence and the roster, under the one lock | v3.11 §6 |

## 3. The gap in the accepted G-4 path (why the write path must change)

**A merge is recorded as a roster write** (`IdentityNoticeConsumer` → `StewardRosterStore::writeUnderLock`). **That path can't append a publication entry.**
- So a merge revealing case B can't, today, record its finding **in the same locked transaction**.
- Doing it in a second transaction would leave a window where the merge is known but the finding isn't.
- **Then "approval status at τ" could be incomplete under the time boundary,** which v3.11 §6 forbids.

## 4. Proposed design (TECHNICAL choices within the accepted rules)

| # | Element | Proposal |
|---|---|---|
| T-1 | **One locked act for merge and findings** | `IdentityNoticeConsumer` moves from `StewardRosterStore::writeUnderLock` to **`PublicationLedger::withLock`**. Inside it: record the merge (or reversal) on the roster; derive the findings (or closures); append one notice per affected publication. **One transaction, one lock** (PO-1, PO-2). `LockedLedger` gains `recordRoster(\Closure)` and `appendNotice(GovernanceNotice)`. **Both are written only by `PostgresPublicationLedger`** |
| T-2 | **Domain service `ApprovalDefects`** (pure) | Given the roster at version v and the published approvals concerned, it returns: case-B publications newly defective (findings); open findings whose basis merge is reversed (closures). It reuses `excluded(r)` and `PublicationGovernance`'s structural/era rule. **It never reads Identity or Organisation** |
| T-3 | **Value object `GovernanceNotice`** | kind (finding · confirmation · closure) · the publication concerned (position p, revision, content version, digest) · roster version · merge id (finding, closure) · confirmer reference (confirmation) |
| T-4 | **`ConfirmPublication` use case** | Actor from `ActingPerson`. Eligibility at the **current** roster version: appointed (steward, or matter reviewer for that matter); not in `excluded(r)`; a conflict declaration recorded (D-84a). **The digest must equal the published content version v; otherwise refused** (the route is then a correction). Appended under the lock |
| T-5 | **Correction link** | A correcting revision is published normally. **At publication it may name the defective position it corrects;** the publication row records `corrects_position`. The read model derives "corrected at q" from it. **No change to the `Revision` aggregate** |
| T-6 | **Schema (central, additive)** | `refgeo_publications` gains `notice_kind`, `concerns_position` (referencing an earlier position), `merge_id`, `confirmed_by`, `confirmed_digest`, `corrects_position`. CHECKs: a notice has exactly its fields and **no claims**; a revision row has no notice fields. **The immutability and append triggers are unchanged** |
| T-7 | **Read model (published contract, additive)** | `GeographyReadModel::approvalStatus(position, Knowledge): ApprovalStatus` (status, finding, confirmation or correction positions). **`PublicationEntry` gains `noticeKind` and `concernsPosition`** (null for revisions), so the G-5c feed carries `GovernanceNoticePublished` |
| T-8 | **Guards** | The no-bypass guard gains: notices only through `PostgresPublicationLedger`. **The PO-2 database guard extends to notice rows** |

## 5. Tests (planned, test-first)

| Level | What |
|---|---|
| Domain | `ApprovalDefects`: cases A to E exactly as v3.11 §8 (A: refused at publication, already in G-3/G-4; **B: a finding; C, D: none; E: closure**); a finding once per publication and merge; idempotent re-delivery |
| Application | `ConfirmPublication`: eligibility at the current version; a digest mismatch refused; the defective approver (now merged) refused; the actor from `ActingPerson` |
| PostgreSQL | merge and finding in **one** transaction (no window: a close through τ waits for both); idempotent notices; immutable; status at p and at τ before and after each notice; correction link; **concurrency** (a merge notice and a publication racing: serialised by the lock) |
| Architecture | notices written only by the ledger; the published contract unchanged except the additive fields |
| Mutation | finding missed in case B; finding raised in case C or D; confirmation by an excluded person; digest mismatch accepted; merge without its finding in one transaction |

## 6. Decisions

| Item | Kind |
|---|---|
| D-90 K, the cases, eligibility, stream entries | **ACCEPTED** (not reopened) |
| T-1 to T-8 (how to realise them) | **TECHNICAL**, but **T-1 and T-6 change the accepted G-4 write path and schema** |
| **Accept this design before implementation** | **PRINCIPAL DECISION** (requested by the review of 2026-11-13) |

**Recommendation: accept.** The change is additive. It keeps one lock and one write path, and it closes a real completeness gap (§3) instead of working around it.

**No new business rule is introduced.** Not in scope: the Platform Owner, and production publication (both still restricted by G-4).

**Traceability:** v3.11 §6, §8, §10, §15, §16 · D-84, D-84a, D-88, D-89, D-90, D-225 · G-3, G-4, G-5 · PBDIGIT-346.
