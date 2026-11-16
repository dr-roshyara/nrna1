# Evidence: PBDIGIT-354 S2-5b, correcting a renewal's `until` (D-237) and a restore's date (row 14); D-241 / D-242 (2026-11-16)

**Decisions:** D-237, D-241, D-242 (recorded `eaeecc16a`), D-177 row 14, D-205, D-206. **Guide:** `developer_guide/membership/35_correcting_a_renewal_and_a_restore.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only.** **Migration:** central 370000 / organisation 300800 (same SQL).
**Baseline:** `eaeecc16a` (S2-5a `747a2ad4e`).

| File | Shows |
|---|---|
| `s25b-01-red-unit.txt` | **RED:** 70 tests, 14 failing for the right reasons: the kernel still refused Renew / Restore corrections, D-241's reason did not exist, ∞ was rejected on the fact, and the command could not say "open-ended" |
| `s25b-02-green-domain.txt` | the whole domain suite: **295 OK**, including the property-based reference runs |
| `s25b-03-red-pg.txt`, `s25b-03-red-pg-cluster/` | **RED (PostgreSQL):** ∞ could not be stored; the flag column did not exist. The finite `until` and the Restore cases already passed: the existing schema holds them |
| `s25b-04-red-isolation.txt` | **RED (guard):** `MembershipLedgerIsolationTest` flagged the new central migration before it was allowlisted |
| `s25b-10a-first-correction-pg-run.txt` | the first combined run: **1 error in `PostgresCorrectionSchemaTest`**. Its rolled-back down/up check met renew / suspend / restore rows that the new test (sorting before it) had committed. Fixed in that test's own rolled-back transaction |
| `s25b-10-correction-pg/` | every correction, renew, suspend / restore and scheduled resignation PostgreSQL test: **103 OK** |
| `s25b-11-parity-routing/` | `OrganisationMigrationsTest` (schema parity incl. the new column and its default) + `MembershipThroughRoutingTest`: **20 OK** |
| `s25b-06-full-cluster/`, `s25b-06-cluster-junit.xml` | **full cluster: 531 OK** (527 + 4) |
| `s25b-07-db-free.txt` | **1097 OK** (1085 + 12) |
| `s25b-08-architecture-full.*` | 367 tests: **the same 4 known failures**; nothing new. **They are failures, not passes.** |
| `s25b-09-membership-unit-full.*` | legacy Membership unit: only the known flaky `CommitteeSlugAvailabilityServiceTest` differs |
| `s25b-05-mutation-run.txt`, `mutants-run1/`, `mutation-check-s25b.php` | 12 mutants (below) |
| `s25b-12-code.diff` | the code, test and migration change of this step |

**Secret sweep:** no hits.

## Mutants (12)

| Class | Mutants |
|---|---|
| assertion kill | N1 (until = start allowed), **N2 (until bound by the decision date: D-237 (2) ignored)**, N3 (term ignores the correction), N4 (restriction ignores it), N6, N7, N8 (the Restore's own rules), N9 (∞ for any chain), N10 (stored ∞ outside a Renew loads) = **9** |
| killed by a second layer, by design | **N5** (a Suspend corrected: the kernel's value rule refuses it, `LogicException`); **P1** (∞ stored without its flag: the database CHECK refuses); **P2** (the flag ignored on load: the decoder fails loudly, never a wrong value) = **3** |
| survivor / equivalent / not run | 0 |

## Accepted tests changed (and why)

- `RenewTest::test_correcting_a_renewal_changes_its_until_d237` and `SuspendRestoreTest::test_a_suspension_is_not_corrected_but_a_restores_date_is_d241_row_14`: their "not yet supported" refusals are replaced by the decided rules (D-237, D-241, row 14).
- `CorrectDecisionTest`: the case "an infinite corrected date is rejected when the fact is constructed" is removed. D-237 makes ∞ valid for a Renew chain, and the fact cannot know its chain. The rule now lives in `decideCorrect`'s guard 1 (the accepted order kept: still before DuplicateFactId) and in `Member::fromLog`, both tested.
- `PostgresCorrectionSchemaTest`: its rolled-back transaction also deletes renew / suspend / restore rows before running the C-4 `down()`.

## Open

**Q-9** (a correction after the declaration was superseded; plan §7e). For that reason **S2-5 is not marked Done** (see the plan).
