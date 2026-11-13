# PBDIGIT-346 G-2b: evidence (2026-11-13)

| File | Result |
|---|---|
| `cycles/red1.txt` | before-images: **RED** Tests: 7, Assertions: 0, Errors: 7.. Missing `BeforeImage`, `ValidInterval::intersect` |
| `cycles/green1.txt` | **GREEN** OK (49 tests, 885 assertions) (whole domain suite) |
| `cycles/red2.txt` | dependencies: **RED** Tests: 11, Assertions: 0, Errors: 11.. Missing dependency classes, `SlotKey::component` |
| `cycles/green2.txt` | **GREEN** OK (60 tests, 918 assertions) (whole domain suite) |
| `db-free-all-RED-purity-finding.txt`, `architecture-full-RED-purity-finding.txt` | **the purity guard caught a `$name` loop variable** in the new `SlotKey::component()`; renamed, not weakened |
| `db-free-all.txt` | all G-0 … G-2b database-free tests: OK (85 tests, 943 assertions) |
| `architecture-full.txt` / `.failures` | Tests: 316, Assertions: 964, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The same 4 pre-existing failures by name |

**Non-vacuity of the loss tests:** in "C moves to B" and the shortening, the new value is B or none, never A. Only the before-image can invalidate, and it does.
