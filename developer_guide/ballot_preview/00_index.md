# Ballot Preview & Voting-Flow Corrections — Developer Guide

**Audience:** engineers working on the real-vote flow (`VoteController`), the Voter Hub, or election-committee-only views.

**Session traceability:** three commits, 2026-09-13, on branch `knowelegeos-modelling`:

| Commit | Guide | Covers |
|---|---|---|
| `4865caf15` | [`01_ballot_assembly_service_and_preview.md`](./01_ballot_assembly_service_and_preview.md) | Extracting ballot-assembly logic out of `VoteController::create()` into `BallotAssemblyService`, and building the read-only, interactive-but-non-submittable **Ballot Preview** feature on top of it. |
| `81aa13b83` | [`02_national_only_regional_warning_fix.md`](./02_national_only_regional_warning_fix.md) | Fixing a false "no regional candidates" warning shown to voters with a region set, on elections that have no regional section at all. |
| `57ac18eeb` | [`03_receipt_codes_committee_only.md`](./03_receipt_codes_committee_only.md) | Restricting the Receipt Codes page to the election committee and removing it from Voter Hub. |

## Why these three are separate commits, not one

Each addresses a distinct concern discovered in sequence during the same working session, but they are independently revertable and independently reviewable:
1. A **new capability** (Ballot Preview) built via a behavior-preserving extraction.
2. A **correctness fix** to an existing warning's condition (unrelated to Ballot Preview once landed).
3. An **authorization/policy correction** to a pre-existing page (unrelated to either of the above).

None of the three depends on the other two for its own tests to pass; each was staged and reviewed against the others' diffs before committing, specifically to avoid one commit silently carrying another's change.

## Key invariants across all three

- **No unintentional behavioral change** to the real voting flow (code/agreement/session/IP/vote-persistence logic untouched by any of the three commits).
- **Anonymity/tenant-isolation rules** (see project `CLAUDE.md`) are unaffected — none of these commits touch `votes`/`results` tables or their scoping.
- **Read-only surfaces stay read-only**: Ballot Preview cannot create a `VoterSlug`, `Code`, or `Vote` row under any path (see `BallotPreviewTest`'s side-effect/idempotency/direct-POST tests).
