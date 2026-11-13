# Reference Geography G-5: acceptance review (read side)

**Status:** submitted for the Principal's acceptance, 2026-11-13. **Design:** `2026-11-13-reference-geography-g5-read-models-design.md`. **Story:** PBDIGIT-346.

## 1. In business terms

**PublicDigit now has a safe, read-only way to use the official geography record:**
- **Questions** (G-5a): did a place exist on a date; what is it; what lies below it; was it merged, disputed or ended. Every answer is "as officially known at publication X" and is repeatable.
- **Search** (G-5b): a name, alias or code suggests official places, with a match quality. A suggestion never assigns anyone to a place.
- **Changes** (G-5c): every publication once, in order, with its provenance. Readers may raise review signals, never automatic changes.

**Nothing in G-5 can change official geography,** and nothing unpublished is ever visible.

## 2. Commits

| Step | Commit |
|---|---|
| G-5a published read model | `a7ac6c779` |
| G-5a verification; ratchet occurrence ceiling | `674168f40` |
| G-5b PlaceLookup | `975e77cb0` |
| G-5c publication feed | `d1d62e3a4` |
| G-5 closure (this record): `pg_trgm` differential, mutations, consumer obligations in the contract | the commit of this file |

## 3. Evidence (all under `docs/publicdigit/reference-geography/evidence/`)

| Question | Evidence | Result |
|---|---|---|
| Unpublished information is never visible | `…-g5a-verification/v-04…`; mutant R7 | every query tested; R7 killed |
| Historical reproducibility; corrections, endings, disputes, merges (R-2), reversals | `…-g5a`, `…-g5a-verification` | passing; R2, R3 killed |
| Invalid or future knowledge refused by every query | `…-g5a`, `…-g5a-verification` | passing; R1, R8 killed |
| The read side holds no publication lock | `TimeBoundary`; `ReferenceGeographyNoBypassTest` | the accepted guard passes |
| Matching equals PostgreSQL `pg_trgm` | `…-g5-closure/c-01…` (20 pairs) | equal within 1e-6 |
| Codes never fuzzy; ordering; existence at t | `…-g5b`; mutants R4, R5, R9 | killed |
| Feed: order, paging, provenance, reproducibility, never beyond the knowledge | `…-g5c`; mutant R6 | killed |
| Contract exposes only published language | `ReferenceGeographyPublishedContractTest`; ratchet A-3 | passing |
| **Mutation** | `…-g5-closure/c-02-mutation-check-g5.out` | **9 of 9 killed by assertions, 0 errors** |
| Regression on the final code | `…-g5-closure/c-03…`, `c-04…`, `c-05…` | see that folder's README |

## 4. Honest limitations

- **CI is not verified.** `architecture-ratchet.yml` runs on every push and pull request, without a database. **But nothing has been pushed, and whether it is a *required* check is a GitHub branch-protection setting this repository can't show.** To make it binding: push, confirm the job passes, then mark "architecture ratchet (blocking)" as required on `main`. That is your action (repository administration).
- **The full Architecture suite still has the 4 pre-existing failures** (Election lifecycle, Election state machine, GovernanceRuntime boundary, Phase C25). They're unrelated to Geography, and they make the merge gate fail fast.
- **Several verification tests passed on their first run.** They're labelled as verification, not RED-then-GREEN.
- **The feed's consumer obligations** (an atomic last-position store; review signals only) **are documented in the contract, but can only be enforced by a consumer's own tests.** No consumer exists yet (D-147).
- **Performance is unmeasured:** there's no real data until G-8. PlaceLookup scans every published name.
- **The production restrictions from G-4 are unchanged:** no production steward appointment or publication until the Platform Owner is established; no production migration until an enforced approval control exists.

## 5. Recommendation

**ACCEPT G-5 (a, b, c)** as the Reference Geography read side, with the limitations in §4 recorded. **A Principal decision is needed only for acceptance itself;** no business or constitutional question arose.

## 6. Principal decision (recorded 2026-11-13)

> **G-5 (a, b, c) accepted, with the limitations in §4,** as the Reference Geography read side: PublicDigit may read, search and track changes to official geography, without automatically changing organisational or membership records.
> **This is not acceptance of all of Reference Geography:** G-6 to G-8 remain, and the G-4 production restrictions stay in force (no production steward appointment or publication until the Platform Owner authority is formally established; no production Geography migration until a technically enforced migration-approval control exists).

**The Principal's words:** "I accept the recommendations written in the document". The Principal DDD review of 2026-11-13 recommended: "formally accept G-5 with its recorded limitations".

**Verified before recording:** the commits `a7ac6c779`, `674168f40`, `975e77cb0`, `d1d62e3a4`, `c9cbfc682` exist, and the five G-5 evidence folders match their sealed checksums.
