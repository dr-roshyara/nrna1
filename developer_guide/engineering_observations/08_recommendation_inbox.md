# 08 — Recommendation Inbox

## Purpose
The developer's decision entry point: list every recommendation still awaiting
a decision (the measured bottleneck — 9 of 10 at build time) and capture
decisions interactively, one prompt per item. Reduces decision friction without
changing what a decision *is*.

## Where it fits
Read-side + capture tooling under `scripts/observations/` — no domain, no
aggregate, no bus. The "implement NOW" slice of the EDA-proposal verdict
(2026-08-04): *approve the conceptual direction, reject the infrastructure timing.*

## Key files
- `scripts/observations/RecommendationInbox.php` — pure `classify(recs, decisionRecords)`: `needs_decision` (no decision, oldest first) + `deferred` (awaiting re-decision)
- `scripts/observations/recommendation-inbox.php` — runner: list, or `--decide` for the interactive a/i/d loop
- `tests/Unit/RecommendationInboxTest.php` — 3 tests / 5 assertions (TDD-first: RED shown)

## Design decisions
- **Semantics match the Loop Completion monitor exactly:** latest decision classifies; DEFERRED surfaces separately; IGNORED/ACCEPTED leave the inbox. One shared meaning of "open".
- **Records are byte-compatible with `recommendation-decide.php`** — same decision+rationale pair, same reason-code taxonomy (OTHER requires a comment), same actor/commit stamping. The inbox adds convenience, never a second format.
- A skipped or invalid prompt records **nothing** — the recommendation simply stays open. The inbox never infers a decision.

## How it works / use
```
php scripts/observations/recommendation-inbox.php            # list with ages
php scripts/observations/recommendation-inbox.php --decide   # interactive capture
php scripts/observations/dashboard-renderer.php              # then regenerate
```

## Testing
`php vendor/bin/phpunit tests/Unit/RecommendationInboxTest.php`

## Pitfalls
- Decisions belong to developers. Never run `--decide` on someone's behalf; an AI-recorded decision at user direction still stamps the git `user.name` (RD-6 — actor provenance is an open observation).
- Deferred items reappear in the `~` section, not the numbered list — re-deciding them is `recommendation-decide.php` territory for now.

## Traceability
WP: EDA-proposal verdict "implement NOW" slice (2026-08-04) · fixture keys match the live OBS contract (`text`, not `message` — corrected against `recommendations.jsonl`) · verdict recorded in `docs/ideas/2026-08-observation-runtime.md` (third enrichment).
