# Decision record — ADR_20261005_0950 revision 3 (ballot integrity, VE-01)

**Date:** 2026-10-05 · **Decided by:** PO / ARB · **Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**ADR:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (revision 3 applied)
**Draft:** `docs/publicdigit/reviews/2026-10-05-adr-voting-authorization-boundary-revision-3-draft.md` (rev 2 — principal-architect review applied) · **Story:** PBDIGIT-237 (VE-01)

## Decision

**APPROVED AS DRAFTED.**

| Point | Decision |
|---|---|
| R1–R12 | applied to the ADR verbatim from draft rev 2 §3 |
| **S1** anonymity assessment (§7 row) | **confirmed** — no new anonymity linkage by design, conditional on I8-compliant rejection / audit telemetry; compliance proven by implementation evidence at GREEN |
| **S2** demo scope | **decided: ballot integrity applies to real elections only; demo-election behaviour unchanged.** Not derived from Q4 (which covers `forEntry()` / `forCast()` only) — an explicit decision of this revision. Recorded in ADR §8 |
| **S3** heading | **confirmed** — §3.0 "Four distinct concepts" |

## What the ADR now states (summary — the ADR text is authoritative)

- §3.0: fourth concept **ballot integrity** beside `forCast()`; `SubmittedBallot` (untrusted) · `AuthoritativeBallot` (today the current ballot assembly authority; regional authority decided separately — D6 / PBDIGIT-236) · `ValidatedBallot` (valid against the authoritative ballot **and** all ballot-integrity rules) · `BallotValidator` domain-pure · CastContext a context object, not an aggregate.
- **I1** a vote is committed if and only if `Allowed` ∧ `ValidatedBallot` ∧ CAS = 1 participation record ∧ exactly the validated content persisted ∧ commit.
- **I11** transport data untrusted · **I12** vote persistence accepts only a `ValidatedBallot` · **I13** authorization and ballot validation both before the CAS, from one CastContext; order not constitutional.
- §3.4 fail-closed outcomes kept distinct (`CastDecision` reason · `BallotRejection` · internal failure).

## Explicitly unchanged

`forEntry()` · `forCast()` and `CastDecision` with its reasons · CAS semantics and placement · I2–I10 · Q1–Q7 · the Q4 rule for the authorization boundary · A5 / A6 · the interim anonymity rule · D6 (open) · D5 and N1–N5 stay in the VE-01 decision record (ballot-validity rules, not ADR invariants).

## Consequences

- **No implementation is authorised by this revision.** VE-01 GREEN needs its own authorisation (PBDIGIT-237): baseline first, fixture-only adaptations for D5 (approved candidacies only) approved separately.
- The VE-01 design proposal and PBDIGIT-237 record "ADR revision 3 done".

**Traceability:** ADR_20261005_0950 rev 3 · draft rev 2 · VE-01 design proposal · gate report §15–§18 · PBDIGIT-237 · PBDIGIT-236.
