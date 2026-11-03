# PBDIGIT-326: Membership PostgreSQL adapter: locale-robust key-collision classification (R-3); keep the PostgreSQL tests out of the default suite (R-7)

**Type:** Technical debt (robustness, data safety) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-03 · **Raised by:** PBDIGIT-264 acceptance (PO, 2026-11-03; `docs/publicdigit/reviews/2026-11-02-pbdigit264-part2-independent-review-and-acceptance.md` §3)

| | |
|---|---|
| **Status** | `OPEN` · **not authorised** |
| **Classification** | reported by the independent review (VERIFIED there, by probe) |

## Items

| ID | Finding | Proposed direction (TDD; needs a plan) |
|---|---|---|
| R-3 | `PgUniqueViolation::isConstraint()` parses the server's English text `violates unique constraint "…"` on the first line of `errorInfo[2]`. Under a non-English `lc_messages`, a real `org_membership_commands_pkey` collision is not recognised. It surfaces as an exception (fail-closed, no bad data) instead of `AlreadyApplied` / `IdempotencyKeyConflict` | either (a) a locale-independent signal (e.g. a constraint name taken from the driver's structured fields, if available, or matching the exact quoted name anywhere on line 1 regardless of the surrounding words); or (b) a documented server requirement (`lc_messages` = an English locale) plus a startup / preflight check. **Until then:** the release preflight checks `SHOW lc_messages` |
| R-7 | The Membership PostgreSQL test classes (`tests/Feature/Contexts/Membership/Organisational/Postgres*Test.php`) also run in the default `phpunit.xml` suite on the shared `nrna_test`. Since Part 2 they include migration down / up round trips and REPEATABLE READ sessions. The restore logic is sound, but this conflicts with the intent of ARB-SEC-01 / BR-20 (persistence tests on an isolated database) | run them only through `phpunit.membership-pg.xml` (e.g. a group excluded in `phpunit.xml`, or a separate directory). **Check CI first:** `membership-architecture.yml` GATE 7 runs `tests/Feature` with `|| true` |

## Not in scope

- The accepted Part 2 behaviour.
- The release gate itself (OQ-01, the §10.2 preflight).

## Related

- PBDIGIT-264 (accepted 2026-11-03);
- PBDIGIT-324 / PBDIGIT-325 (container isolation);
- PBDIGIT-263 (per-test transactions on PostgreSQL).
