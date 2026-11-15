# Evidence: PBDIGIT-354 S2-3a, the authority contract aligned with Governance's AuthorityCheck (2026-11-15)

**Decision:** D-238 (Q-3 Option A; recorded `9211e7fcc`). **Guide:** `developer_guide/membership/27_authority_contract.md`.
**Author:** Claude, the implementer; not independent. PostgreSQL 15.19, pinned image, throwaway cluster. **No migration.**

| File | Shows |
|---|---|
| `s23a-01-red-domain.txt` | **RED (domain):** `AuthorityOutcome`, `CompetenceKind` and `AuthorityEvidence::basis()` did not exist |
| `s23a-02-red-application.txt` | **RED (application):** the old port `check(tenant, actor, act)` cannot take an `AuthorityRequest` |
| `s23a-03-red-provenance.txt` | **RED (persistence):** the basis was neither stored nor validated (plus one missing import in my test) |
| `s23a-04-green-provenance.txt` | GREEN: `ProvenanceJsonTest`, 30 |
| `s23a-05a-pg-test-sql-error/` | **a defect in my test:** the jsonb `?` operator read by PDO as a placeholder |
| `s23a-05-pg/` | GREEN: `PostgresAuthorityEvidenceTest`, 3 |
| `s23a-06-full-cluster/` | **the full cluster on the final code: 445, exit 0** |
| `s23a-07-mutation-run.txt`, `mutants-run1/` | 20 mutants, 0 survivors |
| `s23a-08-db-free.txt` | database-free: **1015 OK** |
| `s23a-09-architecture-full.txt`, `.failures` | 364 tests, **the same 4 pre-existing failures** as the task 13 list, by method; not passing |
| `s23a-10-membership-unit-full.txt`, `.failures` | legacy Membership unit: 728 tests; **no new failure** against the task 13 list (differences only in the known flaky `CommitteeSlugAvailabilityServiceTest`); none in `…\Organisational` |
| `mutation-check-s23a.php` | the copy-load driver |

**Unit results at the end:** domain 263, application 125, infrastructure 71 (the existing handler, race and reference-model tests pass unchanged).

## Mutation classification (20)

| Class | Count | Mutants |
|---|---|---|
| assertion kill | 19 | D1–D6, E1–E4, R1–R3, V1–V3, J1, J2, I1 |
| assertion kill **plus one error, not a pure assertion kill** | 1 | **D7** (the member created before authorisation): failed by assertions in three tests, and in one more the in-memory repository's own guard (`LogicException: No Member was created`) refused the mutant's double creation |
| database refusal / equivalent / survived / not run | 0 | |

## Not in this step

- **S2-3b** (resign now, record a death): after review.
- **Production authority:** none bound until Governance S3; every Membership use case is proven unbuildable without one.
