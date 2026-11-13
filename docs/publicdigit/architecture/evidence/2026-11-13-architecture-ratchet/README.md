# Evidence: the architecture ratchet A-1 to A-5 (PBDIGIT-348), 2026-11-13

| File | Shows |
|---|---|
| `01-red-ratchet-absent.txt` | RED: 11 tests error, because the scanner class doesn't exist |
| `02-green-fixtures-real-tree-without-baseline.txt` | the fixture proofs pass (tolerated, new, hidden in a listed file, second occurrence, stale). The 5 real-tree checks and the reason check fail: there is no baseline yet |
| `03-red-delivery-rule-refined.txt` | RED for the refined A-4: a query port is wrongly reported, and a raw `DB::update` is missed |
| `04-green-delivery-rule.txt` | the fixture proofs pass after the change |
| `05-green-complete.txt` | complete: 11 tests, 213 assertions, with the classified baseline (198 entries) |
| `06-architecture-suite-full.txt`, `.failures` | the full `tests/Architecture` suite: 329 tests, the same 4 baseline failures as before (Election lifecycle, Election state machine, GovernanceRuntime ConstitutionalBoundary, Phase C25) |
| `classify.php` | how the initial baseline was classified (each entry gets a class, reason, remediation and decision) |
| `inventory.php` | generates `publicdigit-architecture-deviation-inventory.md` from the baseline |

**Not done, by instruction:** no mutation of the real application. The ratchet's behaviour is proven on isolated fixtures only.
