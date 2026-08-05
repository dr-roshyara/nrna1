<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Election — evidence preservation parameters (Constitutional Policy 2)
|--------------------------------------------------------------------------
|
| ⚠️ INTERIM VALUES. These are IMPLEMENTATION BOOTSTRAP DEFAULTS, not
| constitutional defaults. Q-2 owns the durations; WP-7 only ENFORCES them.
| Each row awaits the ARB parameter decision named beside it, and a
| constitutional value may replace a bootstrap WITHOUT any architectural
| redesign.
|
| Policy 2's Retention Invariant:
|   "evidence required for a legally permissible challenge must never expire
|    before that challenge can no longer be initiated or resolved"
|
|   EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
|
| ⛔ THE MAXIMUM ADJUDICATION DURATION IS DELIBERATELY ABSENT FROM THIS FILE.
| MAD has exactly ONE home — `config/adjudication.php` — and a second copy here
| would be precisely the Decision Duplication corrected in WP-6 (AP-2). The
| retention adapter READS that key; it does not redeclare it. The fitness guard
| `Tests\Architecture\DurationPolicyOwnershipTest` enforces this.
|
| Traceability: Constitutional Policy 2 (EPIC-003 §THE FOUR DECISIONS №2) ·
| EPIC-004K §142 · roadmap §WP-7 · rulings R-44 (A-1) and R-47 (slice 7A) ·
| WP-6 findings AP-1 (fail closed) and AP-2 (one home per parameter).
*/

return [

    /*
     | Contestation Window — how long a legally permissible challenge may still be
     | initiated. Policy 2 is explicit that this term "must be explicitly defined";
     | before WP-7 it had no home anywhere in the codebase.
     | INTERIM: 30 days — the Q-2 ratified bootstrap, awaiting confirmation.
     */
    'contestation_window_days' => (int) env('ELECTION_CONTESTATION_WINDOW_DAYS', 30),

    /*
     | Legal Safety Margin — the buffer beyond challenge-and-resolution, so evidence
     | never expires exactly as the last permissible action closes.
     | INTERIM: 30 days — the Q-2 ratified bootstrap, awaiting confirmation.
     */
    'legal_safety_margin_days' => (int) env('ELECTION_LEGAL_SAFETY_MARGIN_DAYS', 30),

    /*
     | Per-election-type overrides, then per-organisation overrides. Both are
     | resolved by the durations port; absence means "use the default above".
     | INTERIM: empty — no election type or organisation deviates yet.
     |
     | Shape: ['election_type' => ['contestation_window_days' => 45]]
     */
    'per_election_type' => [],

    /*
     | Shape: ['<organisation_id>' => ['legal_safety_margin_days' => 60]]
     */
    'per_organisation' => [],

];
