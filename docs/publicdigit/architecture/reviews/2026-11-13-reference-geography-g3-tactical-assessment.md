# Reference Geography G-3: tactical-design assessment (PBDIGIT-346)

**Date:** 2026-11-13 · **Author:** Session A · **Status:** assessment before implementation.
**Sources:**
- v3.11 §8 (governance), §14 (structural), §15 (the roster stays outside the knowledge stream);
- D-84, D-84a, D-88, D-89, D-90, D-225;
- the standalone completion §5 (merge consumption), §5a (what Identity can prove) and §8 (slices).

## 1. The six contracts

| # | Contract | Settled by | Resolution |
|---|---|---|---|
| 1 | **Steward authority and the roster used** | D-84 "the platform owner appoints Geography stewards"; v3.11 §8 "Roster and publication ordering" | **`StewardRoster`, an append-only aggregate:**<br>- **its entries:** appointment, end, matter-reviewer appointment (one revision), conflict declaration, merge notice, merge reversal;<br>- **versions:** each entry takes the next gap-free roster version;<br>- **authority is judged "at version v",** from entries ≤ v. A submission or approval is judged at the version it was given under; publication at the current version.<br><br>**Not valid time:** the roster is ordered by recording (§8), not by a business-time interval. The appointing person is recorded with the entry. |
| 2 | **Independence and `excluded(r)`** | D-88; v3.11 §8 (the one rule, cases 1–5, the full combination table) | `excluded(r)` already exists (`Revision::excludedPersons()`, G-2a: everyone who prepared, assessed or submitted any version). `PublicationGovernance` requires, for a structural revision in era B, a counting approval whose approver:<br>- is **not known-same** under E(v) as anyone in `excluded(r)`;<br>- **was appointed when approving** (a steward, or a matter reviewer for r, at the approval's roster version). |
| 3 | **Historical time** | v3.11 §8 Checks: "appointment ended after submission or approval → the act stands"; "a new steward after submission → the threshold activates"; D-89 latching | Each act is judged at **its own** roster version. **Publication re-checks at its version:**<br>- the threshold `secondStewardEverAppointed(v)`, latched with the version it activated;<br>- the conflict declarations;<br>- independence under E(v). |
| 4 | **Dependency completeness for predicates and absences** | v3.11 §8 "publication, inside the lock, re-reads the roster at its current version"; §15 | **Governance is not dependency-recorded. It is re-evaluated in full** at the publication's roster version, so there is no read set to miss:<br>- an absence ("no merge known") is re-evaluated;<br>- a predicate ("a second steward was ever appointed") is re-evaluated.<br><br>**The F-1 risk doesn't arise.** The obligation is that `PublicationGovernance` is a **pure function of (revision, roster at v)** that G-4 calls under the lock. |
| 5 | **Identity merges and reversals** | completion §5 (IG-4, IG-5), §5a; v3.11 §8 Identity merges | **Geography records the notices; it never decides sameness.**<br>- idempotent by `(mergeId, kind)`: a replay writes nothing, not even a version;<br>- `E(v)` = the transitive closure of the links of the merges recorded ≤ v whose reversal is not recorded ≤ v;<br>- order-insensitive; `survivorRef` is ignored;<br>- the publication records **"independent as known to Identity at roster version v"**, never "verified distinct". |
| 6 | **Sequencing** | v3.11 §8 "its own gap-free counter, in the same row"; "takes the same publication lock" | **Local to Reference Geography:** the roster version lives in Geography's publication sequence row under Geography's lock (G-4). **No platform-wide sequencer.** In G-3 the aggregate assigns the versions; G-4 persists them under the lock. |

## 2. Scope of G-3 (domain only)

**In scope:**
- **`StewardRoster`,** with the queries at version v and E(v);
- **`PublicationGovernance`:**
  - `maySubmit`, `mayApprove` and `mayPublish` at roster version v, giving a decision with its reasons;
  - the rule applied (era A or B; structural or not);
  - the independence statement;
- **the assessing steward of the current version recorded by `Revision`,** so the conflict test at publication knows who to test.

**Out of scope:**
- **Post-publication findings** (cases B and E) and **D-90 notices**: G-6, per completion §8.
- **Persistence and the lock:** G-4.
- **Service-provider binding of `ActingPerson`:** done with the first application service that consumes it (the roster-write and publication services, G-4). **This deviates from the runbook,** which said G-3: a binding with no consumer can't be tested.

## 3. What needs your decision

**Q-G3-1 (blocks only the material-conflict test, D-84a).**
- **The rule:** D-84a forbids a steward with an organisational interest from **deciding a matter that involves a judgment between competing positions**: "an objection, a missing-unit submission, a dispute, a precedence question, an assessment between competing instruments".
- **The gap:** the sources don't say **who or what determines that a revision is such a judgment matter.**
- **The business consequence:** if the assessing steward could freely call their own matter "routine", a conflicted sole steward could evade D-84a.
- **Recommendation:**
  - a revision **is a judgment matter if the system can see a D-84a trigger:** a DISPUTE claim, a precedence assessment, or (from G-6) a linked objection or missing-unit submission;
  - **or if anyone involved marks it so.**
  - **Nobody can unmark it,** and the marking is part of the approved content.
  - A competing-instruments judgment that nobody marks is caught afterwards by v3.11 §8's "conflict found after publication" procedure.
- **Each decider records a declaration for the matter:** whether they have a direct organisational interest in it. Material conflict = declared interest **and** judgment matter.

**Not a decision now, but needed before G-4 wires roster writes to real users: who is D-84's "platform owner"?**
- No Geography or Platform model defines it.
- The legacy application has a `platform_admin` flag.
- **Recommendation, when wiring:** an application port `PlatformOwnerAuthority`, whose adapter reads the platform's designated owner. Don't take it from a role anyone can be given.

## 4. Slices (strict TDD; each with RED, GREEN, regression, guide and evidence)

| Slice | Rules | Depends on Q-G3-1? |
|---|---|---|
| **G-3a** | `StewardRoster`: appointments, ends, matter reviewers, gap-free versions, the latched threshold with its activation version, authority at version v | no |
| **G-3b** | Merge notices and reversals: idempotency, E(v), transitive, reversal-first, overlap, partial reversal, unknown references | no |
| **G-3c** | `PublicationGovernance`: eras A and B, structural versus non-structural, `excluded(r)` under E(v), approver appointed at the approval's version, approval of the current version, cases 1–5, the full combination table, merge case A, the independence statement | no |
| **G-3d** | Conflict declarations (required for every decider) and the D-84a material-conflict test | **yes:** the declaration requirement can be built; the judgment-matter test waits for Q-G3-1 |

**Traceability:** PBDIGIT-346 G-3 · v3.11 §8, §14, §15 · D-84, D-84a, D-88, D-89, D-90, D-225 · completion §5, §5a.
