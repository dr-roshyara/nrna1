# 09 — KnowledgeOS Doctor

## Purpose
Repeatable environment verification: eight checks answering "will observations
actually run on this clone?" — the 2026-08-04 hook incident (trigger active
nowhere, found only by manual diagnosis) made repeatable.

## Where it fits
Platform Bootstrap's VERIFY slice. Installation is NOT here — husky's npm
`prepare` script owns it (`package.json` line 29). The doctor checks and
reports; it never installs, never repairs.

## Key files
- `scripts/observations/KnowledgeOsDoctor.php` — pure `diagnose(state)`: checks + ready flag
- `scripts/observations/doctor.php` — runner: gathers real facts, prints report, exit 0/1
- `tests/Unit/KnowledgeOsDoctorTest.php` — 3 tests / 16 assertions (TDD-first: RED shown)

## Design decisions
- **Pure core:** `diagnose()` takes gathered facts, returns checks — fully testable without touching git/filesystem.
- **The incident check is named:** `commit trigger active (.husky/post-commit)` — its failure detail cites the incident and the fix.
- **Actionable details:** the hooks-path check tells you the actual remedy (`npm install`), not just "failed".
- Exit code 0/1 makes it usable later as a CI/onboarding gate — without building that gate now.

## How it works / use
```
php scripts/observations/doctor.php
```
Run after cloning, after npm install, or whenever observations seem missing.

## Testing
`php vendor/bin/phpunit tests/Unit/KnowledgeOsDoctorTest.php`

## Pitfalls
- READY means the pipeline CAN run, not that it HAS run — check the streams/dashboard for actual activity.
- Don't add repair logic here; installation belongs to husky prepare (or a future bootstrap CLI, register-gated).

## Traceability
Platform Bootstrap register row (PARTIALLY ACTIVE) · earned by the 2026-08-04 hook diagnosis · Bootstrap-CLI and CI/IDE installers remain gated per the register.
