# F-3 wording alignment (W1, W2, W3, W5) — evidence, 2026-10-10

Authority: Human PO / ARB "Approve and authorise" (W-1 … W-3), "Proceed on new HEAD", "Include W5 (Recommended)" (plan, "F-3 clarification" progress). Comment / documentation only. **Database / connection configuration intentionally omitted; these suites do not connect to a database.** No `.env` or environment variables were read or captured.

| Check | Result |
|---|---|
| `tests-diff.txt` (`git diff -U0 -- tests/`) | only `//` comment lines changed (W1 test double, W2 contract suite); non-comment changed lines: 0 |
| `wording-grep-count.txt`: "precedes every other check" in both Slice contracts, guides 06/07, the test double, the contract suite | 0 (W4, the verbatim F3-1 decision record in the plan, is deliberately unchanged) |
| `1-application` (`vendor/bin/phpunit tests/Unit/Contexts/Membership/Application/Organisational`) | exit 0: 55 tests |
| `2-arch-*` (four Membership architecture tests) | exit 0: 4 + 3 + 5 + 1 |

The test runs (`env.txt`: HEAD `41d6a36d4`) were taken after W1–W3 and before W5; W5 changes Markdown only. Not covered: PostgreSQL (Part 2).
