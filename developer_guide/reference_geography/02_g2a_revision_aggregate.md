# 02: G-2a, the `Revision` aggregate and its content versions (PBDIGIT-346)

**Story:** `PBDIGIT-346` slice G-2, step a · **Design:** v3.11 §5 (R-1), §5.3 (content versions), §8 (approvals, `excluded(r)`), §16 (`Revision`); D-93, D-226
**Status:** implemented with TDD. Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2a/`. **G-2b** (before-images, dependency predicates) and **G-2c** (validator, invariant checks) follow; the review boundary is the end of G-2.

## In business terms

A **revision** is a proposed change to the atlas.
- Someone prepares it: claims, plus the evidence they rest on.
- The **assessing steward** records the assessments (authenticity, competence, applicability) and **submits** it. Submitting **freezes** a numbered content version with a fingerprint of everything judged.
- A **second person** may then approve **that exact version**.
- **If anything must change** (for example because the world moved on and the revision went stale), it is **re-prepared**. That opens a new version, which must be **assessed afresh**. Earlier assessments and approvals stay on record but **no longer count**.
- So **nobody can be published on an approval given to different content.**

## Rules enforced (`app/Contexts/ReferenceGeography/Domain/Revision.php`)

| Rule | v3.11 | Refusal |
|---|---|---|
| no two claims for one slot with overlapping intervals (disjoint, or other slots, are fine) | §5 R-1 | `SlotOverlapInRevision` |
| submission needs ≥ 1 claim, every claim's evidence attached, and **authenticity, competence, applicability** assessed **for this version** | §16 completeness; §8 / D-84 | `Incomplete` |
| after submission nothing changes (claims, evidence, assessments, re-submission) | §5.3 frozen | `NotDraft` |
| evidence is immutable: one fingerprint per evidence id | §5.3 | `EvidenceChangedUnderSameIdentifier` |
| an approval names (content version, roster version) and only the **current** version can be approved | §8 | `ApprovalNamesAnotherVersion`, `NotSubmitted` |
| only approvals naming the current version **count**; all stay recorded | §5.3, §8 | — |
| `excluded(r)` = everyone who prepared, assessed or submitted **any** version | §8 | — (read by G-3) |
| draft → submitted → stale / withdrawn; re-prepare from submitted or stale | §5.3, §16 | `NotSubmitted`, `NothingToReprepare`, `Withdrawn` |

**There is no "approved" state:** approvals are records counted at publication (§8). The completion document's §3 listed one; a correction note records this. **Publication is G-4.**

**The digest** (SHA-256, deterministic) covers: revision id, version number, every claim (id, slot, value or none, interval, basis), every evidence version (id + fingerprint), the version's assessments with outcomes and assessor, the preparers (accumulated), and the submitter. **G-2b / G-2c add the dependency predicates, p₀ and the derived classification**, as §5.3 requires.

## Supporting types

`RevisionId`, `RevisionState`, `RevisionRefusal` / `RevisionRefused` (`DomainException`), `EvidenceVersion` (id + SHA-256), `AssessmentKind` (+ `required()`), `Assessment`, `Approval`, `ContentVersion`.

## How to use

```php
$r = Revision::prepare($revisionId, $preparer);
$r->addClaim($claim, $preparer);
$r->attachEvidence(new EvidenceVersion('ev-1', $sha256), $preparer);
foreach (AssessmentKind::required() as $kind) {
    $r->recordAssessment(new Assessment($kind, 'satisfied', $assessor));
}
$v1 = $r->submit($assessor);                         // ContentVersion(1, digest)
$r->approve(new Approval($v1->number, $rosterVersion, $secondPerson));
$r->countingApprovals();                             // approvals naming the current version only
$r->excludedPersons();                               // for G-3's independence check
```

## Testing

`tests/Unit/Contexts/ReferenceGeography/Domain/RevisionTest.php` (11 tests), database-free:

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography
```

## Pitfalls

- **Assessments are per version.** Re-preparation clears them; the next version is assessed again. This test was first written wrongly and corrected before GREEN; see the evidence.
- **A claim's id is part of the content.** Two revisions with "the same" claim under different ids have different digests.
- **Don't judge the approver here.** Eligibility, independence (`excluded(r)` under Identity equivalence, D-226 §5a) and the era rules are G-3.

**Traceability:** PBDIGIT-346 G-2a · v3.11 §5, §5.3, §8, §16 · D-84, D-88, D-93, D-226.
