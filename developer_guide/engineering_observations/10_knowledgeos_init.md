# 10 — KnowledgeOS Init

## Purpose
Idempotent repository initialization: plan the gap between current state and
a KnowledgeOS-ready repository, execute only what's missing, verify via the
doctor. Track A infrastructure automation — deterministic, no domain decisions.

## Where it fits
Platform Bootstrap's Initialize use case. Distinct from Track B (reusable
platform extraction), which stays behind the second-adopter gate. The
governance correction that unlocked this: *"automation of deterministic work
is not speculative architecture"* (review 2026-08-04).

## Key files
- `scripts/observations/KnowledgeOsInitPlanner.php` — pure `plan(state)`: 4 steps, each with a `needed` flag
- `scripts/observations/init.php` — runner: gathers state, prints plan, executes gaps, chains into `doctor.php`
- `tests/Unit/KnowledgeOsInitPlannerTest.php` — 3 tests / 8 assertions (TDD-first: RED shown)

## Design decisions
- **Idempotency is the contract, pinned by test:** an initialized repo plans zero actions ("already initialized — nothing to do" — this repo's real output).
- **The hook-path step is advisory, not executed:** `core.hooksPath` belongs to husky's npm `prepare`; init points at `npm install` instead of competing with it.
- `--dry-run` prints the plan without executing.
- Init ends by running the doctor — initialize, then prove it.

## How it works / use
```
php scripts/observations/init.php --dry-run   # show the plan
php scripts/observations/init.php             # execute + verify
```

## Testing
`php vendor/bin/phpunit tests/Unit/KnowledgeOsInitPlannerTest.php`

## Pitfalls
- Init creates infrastructure; it never writes observations or config content. Rules live in `recommendation-rules.yaml` (rules-as-data), untouched here.
- Don't add upgrade logic — `upgrade` is register-gated until versioned migrations actually exist.

## Traceability
Platform Bootstrap Track A (register row, ACTIVE) · the register's first absorbed row-level correction (init's second-adopter gating was over-applied) · guides 09 (doctor) is the verify counterpart.
