# Evidence-Dependence Audit

## Was the commit counted multiple times?

MD-026's `03`, `05`, and `07` (DDD analysis) all cite commit `70fee73c`. **This is the same single
observation cited three times for three different sub-claims (git-tracking date, commit-message
wording, absence of `theory-v1.1-simulation/` citation) — legitimate reuse of one fact for distinct
purposes, not double-counting it as separate corroborating evidence for the same claim.** No violation
found.

## Were the "two independent decisive pieces" actually independent?

**No — this is the confirmed overstatement (claim audit #7 in spirit, formally in `01` under the
narrative summary).** The MD-010/MD-011 date (2026-09-01) and the git commit's date+wording
(2026-09-06) are:
- **Not derived from each other** — genuinely two different events.
- **But part of one connected repository-history chain**, not two independently-sampled data points
  about the same underlying question. Both ultimately trace back to decisions and actions by the same
  single human collaborator, within a 5-day window, about the same body of material. Calling them
  "independent, decisive pieces of evidence" borrows statistical-independence language for what is
  really **two sequential facts in one narrative**, correctly described as *convergent*, not
  *independent*.

## Was self-correction treated as independent corroboration anywhere?

Checked specifically for the `reviews/kernel/` case: MD-026's own `06` already correctly treats
`session2`'s disqualification of `session1` as **one lane's own internal correction**, not as two
independent sources agreeing — no violation found there.

## Was chronology treated as provenance anywhere beyond the already-flagged M0030 case?

No additional instance found beyond claim audit items 2/5/6, already addressed.

## Was citation treated as execution evidence anywhere beyond M0030?

No — MD-026's own `05` (theory-v1.1-simulation) correctly declines to treat subject-matter overlap as
citation-equivalent evidence, and rates it weaker specifically because no citation was found at all.
