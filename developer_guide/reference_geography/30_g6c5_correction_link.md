# 30: G-6c-5, the full-coverage correction link (PBDIGIT-346)

**Design:** revision 3 §8, §9, with the append-only clarifications §19 and §20; Principal decision 1 (full coverage), 2026-11-14. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c5/`.

## The business rule

A flagged publication (an open D-90 finding) can be **corrected**: a new revision that replaces **all** of its facts is published with a link to it.
- **The published link resolves the finding,** in the same act and under the same lock.
- **"Corrected" must never hide a fact still standing on the defective approval,** so the replacement must be **complete:**
  - every claim of the flagged publication must be replaced in its own slot, for every day it was valid;
  - **several adjacent claims may replace it jointly;**
  - a one-day gap, a late start, an early end, or a finite end for an open-ended fact is not enough.
- **Otherwise the whole publication is refused and nothing is written.**
  - The same revision may still be published **without** the link, as an ordinary revision; the publication stays flagged until it is confirmed, closed or fully corrected.
- **A correction does not say the new values are right:** that is the revision's ordinary validation and approval.

| Refusal | When |
|---|---|
| `correction-without-open-finding` | the publication has no open finding: never flagged, already confirmed, closed or corrected (resolved once), or the position is not a revision publication |
| `correction-incomplete` | at least one flagged claim isn't fully covered |

## Coverage, precisely (`CorrectionCoverage::uncovered`)

```
for every flagged claim c:
  R = the correcting revision's claims in c's slot, sorted by start
  cursor = c.from                          first day not yet covered; intervals are [from, to), to exclusive
  for r in R:
    r.from > cursor        → gap: c not covered
    r.to = null            → covered (until further notice)
    cursor = max(cursor, r.to)
    c.to ≠ null and cursor ≥ c.to → covered
  otherwise                → not covered (an open-ended c needs an open-ended r)
```

## Components

| Layer | Component | Role |
|---|---|---|
| Domain | `CorrectionCoverage::uncovered`, `::refusals(lifecycle, flagged, replacements)` | the rule; "open" comes from `FindingLifecycle` (the one definition) |
| Domain | `GovernanceRefusal` | + `CorrectionWithoutOpenFinding`, `CorrectionIncomplete` |
| Application | `PublishRevision::publish(RevisionId $id, ?int $corrects = null)` | after governance and **before** the staleness check (a refused link writes nothing): lifecycle, flagged claims, coverage; then `append(…, $corrects)` |
| Port | `LockedLedger::claimsPublishedAt(p)`; `append(…, ?int $corrects)` | read the flagged facts; write the link |
| Infrastructure | `PostgresPublicationLedger` | writes `corrects_position`, which the G-6c-1 schema guards (backwards CHECK, FK, revision-kind trigger) |

**No new aggregate is needed:** the correcting revision is an ordinary `Revision`, and the link is decided by the publishing steward at publication, under the lock.
- **A draft, submitted or approved correcting revision has no effect** (the link exists only on the published row).
- **After a correction, the publication leaves D-90 evaluation** (`ApprovalDefects` skips corrected publications, §8.2 row 8): later merges raise nothing for it.

## Concurrency and failure

- **Two corrections of one publication at once serialise on the lock;** the later finds no open finding.
- **A confirmation racing a correction:** whichever runs first resolves; the other is refused.
- **A failure before commit** leaves neither the publication nor the resolution, and a retry succeeds.

## G-6c-4 follow-ups closed in this step

| Follow-up | Result |
|---|---|
| A stale digest that genuinely belonged to the revision | A revision is published once, so its only earlier digests are those of earlier **submitted** versions. `test_the_digest_of_an_earlier_submitted_content_version_is_refused`: version 1's digest is refused, version 2's accepted. **No defect** |
| A matter reviewer confirming | Design §7 rule 3 is `mayActForAt`, which admits a matter reviewer appointed for the matter. Tested: allowed for this matter, `not-appointed` for another. **No defect** |
| K1 to K7 against the database tests | **K2, K6, K7: killed.** **K1:** killed; the database index `refgeo_publications_one_resolution` independently refuses the duplicate resolution (a correction is a revision row, so only the domain rule refuses "confirm after correction"). **K4:** survived at first because every database fixture's excluded person was also the approver's alias; the new `test_a_second_preparer_unrelated_to_the_approver_is_refused_by_exclusion` now kills it. **K3 and K5 survive and are equivalent in any consistent database:** G-6c-3 keeps "open finding ⇔ the counted approver is currently known-same as an excluded person", so a person known-same as the defective approver of an open finding is already refused by the exclusion rule. The defective-approver check is defence in depth, distinguishable only with a hand-built lifecycle (the unit tests kill K3 and K5) |

## Testing

- **`CorrectionCoverageTest` (unit, 8):**
  - exact and wider; adjacent claims (in any order; three, ending open);
  - one-day gap; starts late or ends early; exclusive boundaries;
  - open-ended facts; all-but-one covered; another slot; empty set;
  - lifecycle (open, closed, confirmed, corrected, none).
- **`CorrectionLinkTest` (PostgreSQL, 7):**
  - full coverage resolves (draft no effect; status before and after; confirmation refused after);
  - joint coverage;
  - three partial shapes refused, then the ordinary-revision fallback;
  - no open finding (never flagged, already corrected, a finding position);
  - several findings and leaving evaluation;
  - failure before commit;
  - two forked races.
- **Mutation (`mutation-check-g6c5.php`):** L1 to L8 killed by assertions, 0 errors. L1 is "coverage checks the first flagged claim only", the mutation the design names.

**Administrator: no administrator action required.** No migration (the G-6c-1 schema already holds `corrects_position`), configuration, queue or schedule. No HTTP route (G-4 production conditions).

**Traceability:** PBDIGIT-346 G-6c-5 · design revision 3 §8, §9, §19, §20 · D-90.
