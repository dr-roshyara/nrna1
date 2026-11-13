# Reference Geography G-3: acceptance review for the Principal (PBDIGIT-346)

**Date:** 2026-11-13 · **Author:** Session A (engineering supplies evidence; it does not accept its own work) · **Status:** for the Principal's decision.
**Design:** `2026-11-13-reference-geography-g3-tactical-assessment.md` (§1 contracts, §5 D-230 and the authority check, §6 G-4 obligations).
**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g3/`.
**Guides:** `developer_guide/reference_geography/11`, `12`.

## 1. Recommendation

**ACCEPT G-3 as the Reference Geography governance domain baseline.** The G-4 obligations in §5 are carried forward.

**This acceptance would not certify:**
- persistence;
- atomic publication;
- the application wiring of these rules.

## 2. What G-3 guarantees, in business terms

- **A versioned roster records who may act:** stewards, single-matter reviewers, conflict declarations and Identity's merge notices. **Every act is judged by the roster as it stood when the act was given.**
- **One steward ever:** that steward publishes alone, but only with a recorded conflict declaration.
- **After a second steward has ever been appointed:** every structural change needs an independent second person, permanently.
- **"Independent" means:**
  - not anyone who prepared, assessed or submitted any version;
  - appointed when approving;
  - approving exactly the version published;
  - **as far as Identity knows,** with merges counted and reversals honoured.
- **A decider with an organisational interest can't decide a matter involving competing positions.**
  - **Nobody can declare such a matter routine,** and a flag can't be removed.
  - **Without an eligible independent decider, the matter waits** for a reviewer the platform owner appoints for that matter alone (D-84a, D-230).

## 3. Your re-check list, against the code

| Check | Where enforced | Proof |
|---|---|---|
| The roster is append-only and versioned | `StewardRoster`: only append methods; a refused write takes no version | StewardRosterTest |
| Authority at the correct historical version | `…At(…, v)` reads entries ≤ v; an approver at the approval's version | StewardRosterTest; M3 |
| Merges and reversals are idempotent and affect independence | `recordMerge` / `recordReversal`, `knownSameAt` | MergeNoticeTest; M2, M7 |
| Merges never grant authority | authority by appointed reference only | MergeNoticeTest |
| Every preparer, assessor and submitter of every version is excluded | `Revision`: prepare, addClaim, removeClaim, attachEvidence and reprepare note preparers; recordAssessment and submit note assessors; accumulated | PublicationGovernanceTest (earlier versions, two preparers); M1 |
| The approval is of the exact version | `countingApprovals()` | M5 |
| The permanent two-steward threshold | latched with its activation version | M4 |
| The routine/judgment bypass is closed | triggers, permanent flags, no removal; conflict re-checked at publication for the assessor and the second person | JudgmentMatterConflictTest; M8 to M14 |

**Combination table coverage:** 9 of the 10 rows of v3.11 §8. The tenth (a merge after publication producing a finding) is G-6, with the D-90 notices.

## 4. Evidence

- **TDD:**
  - RED, then GREEN, for each of G-3a, G-3b, G-3c and G-3d.
  - **One invalid RED** (G-3c: invalid person-label fixtures) was kept and redone.
  - **One fixture update** (G-3c fixtures after the D-84 declaration invariant) was kept, with identical expected outcomes.
- **Mutation check:** 14 deliberately broken copies of the safeguards, **all killed**. No source file was modified. This shows each listed safeguard is tested. **It doesn't prove the absence of every defect.**
- **Regression:**
  - **domain:** 170 tests pass;
  - **database-free (with purity and architecture guards):** 195 pass;
  - **isolated PostgreSQL:** 154 pass, **in other contexts only;**
  - **architecture:** 316 tests, the same 4 pre-Geography failures by name and message.

## 5. Not yet proven, carried to G-4 and later

- **Atomicity and sequencing (G-4):** the final `mayPublish` and the publication append in one Geography publication lock, with every roster write and merge notice taking the same lock. **Not proven by the domain tests.**
- **Wiring (G-4):**
  - application services that call `maySubmit` / `mayApprove` before `Revision::submit` / `approve`;
  - binding `ActingPerson`;
  - the `PlatformOwnerAuthority` port. **Who D-84's platform owner is remains undefined.** It's needed before roster writes are exposed to users.
- **G-6:** post-publication merge findings (cases B and E) and D-90 notices; objections and missing-unit submissions arriving as judgment flags.
- **G-0:** its race test is still not mutation-proved.

## 6. Your decision

**Accept G-3, or ask for a revision.** No other decision is open.
