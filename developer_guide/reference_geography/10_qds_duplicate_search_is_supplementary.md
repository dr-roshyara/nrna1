# 10: Q-DS, the duplicate search is supplementary (D-229) (PBDIGIT-346)

**Design:**
- **D-229** (the Principal, 2026-11-13: Q-DS option A, amending v3.11 I-4);
- D-82, D-227;
- v3.11 §1 I-4 and the §3 Existence row (both annotated).

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2-qds/`.

## In business terms

**A place enters the official atlas only on a competent establishing instrument.**
- **That needn't be the founding document:** a later official instrument from a competent issuer counts, if it genuinely establishes the fact.
- **The search proving the place isn't already in the atlas** (a duplicate search) is still recorded. It's **extra** evidence about identity, never a replacement for the instrument.
- **"We found no other record" is not the same as "an authority established this place".**

## Code

| Element | Change |
|---|---|
| `BasisKind` | the case `DuplicateSearchAssessment` is **removed**: a duplicate search is no basis for any claim |
| `Claim` (I-4) | an establish claim (EXISTENCE with a value) needs `BasisKind::Instrument` |
| `AssessmentKind::DuplicateSearch` | the existing Revision-assessment mechanism, so no second mechanism. It's recorded with `Revision::recordAssessment()` and covered by the content-version digest. **Not in `required()`**: v3.11 doesn't define when it applies, so it is recorded, not demanded |
| `RevisionValidator` (D-82_BASIS) | the former Existence/duplicate-search exemption is gone. What remains: "none" claims (withdrawals), and a BOUNDARY on `AttributedCartographicSource` |

**How to use it:**

```php
$revision->addClaim($existenceOnInstrument, $preparer);          // Basis(BasisKind::Instrument, 'ev-2015')
$revision->attachEvidence($instrument2015, $preparer);            // issuer + issue date (D-227)
$revision->recordAssessment(new Assessment(AssessmentKind::DuplicateSearch, 'no existing identity found …', $assessor));
```

## Pitfalls

- **Don't model the duplicate search as evidence the claim rests on.** It doesn't establish the fact, and it doesn't bypass issuer competence (a test pins this).
- **Don't add it to `required()`** without a Principal decision on when it is "applicable".
- **Persistence of assessments** comes with the G-4 ledger. There is none yet.

## Testing

**`DuplicateSearchEvidenceTest` (4 tests):**
- no duplicate-search basis, and no non-instrument existence;
- recorded as an assessment, inside the digest, never the basis;
- no bypass of competence (D-82);
- a later competent instrument (2015, for a place existing since 1950) is accepted.

**RED:** 3 errors (the missing assessment kind) and 1 failure (the basis kind still existed).
**GREEN:** 140 domain tests.

**Two earlier assertions encoded the old I-4 reading.** D-229 made them invalid, so they changed deliberately:
- `SupersessionTest` (renamed `test_an_establish_claim_needs_an_establishing_instrument`);
- `CompetenceAndEvidenceTest` (the duplicate-search exemption assertion is removed; the stronger rule is in the new test).

**Traceability:** PBDIGIT-346 · D-229 · v3.11 §1 I-4, §3 · D-82, D-227.
