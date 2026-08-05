<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Adjudication — temporal parameters
|--------------------------------------------------------------------------
|
| ⚠️ INTERIM VALUES. These are IMPLEMENTATION BOOTSTRAP DEFAULTS, not
| constitutional defaults. Q-2 owns the durations; the APM only ENFORCES them
| (EPIC-004K §41/§81). Each row awaits the ARB parameter decision named beside
| it, and a constitutional value may replace a bootstrap WITHOUT any
| architectural redesign.
|
| Traceability: roadmap §WP-6 · Q-2 (§187 five-item parameter set) ·
| EPIC-004_Q2_Resolution_Package.md (ratified bootstrap values).
*/

return [

    /*
     | Maximum Adjudication Duration — bounds one adjudication's whole conduct.
     | Firing yields Expired, never a conclusion (Constitutional Policy 4).
     | INTERIM: 60 days — recorded as the WEAKEST of the ratified bootstraps and
     | the priority for stakeholder review.
     */
    'maximum_adjudication_duration_days' => (int) env('ADJUDICATION_MAD_DAYS', 60),

    /*
     | Per-election-type overrides, then per-organisation overrides. Both are
     | resolved by the durations port; absence means "use the default above".
     | INTERIM: empty — no election type or organisation deviates yet.
     |
     | Shape: ['election_type' => ['maximum_adjudication_duration_days' => 90]]
     */
    'per_election_type' => [],

    /*
     | Shape: ['<organisation_id>' => ['maximum_adjudication_duration_days' => 45]]
     */
    'per_organisation' => [],

];
