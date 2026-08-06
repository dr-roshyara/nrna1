<?php

namespace App\Application\Election\Deprecation;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PBDIGIT-58A — observation only.
 *
 * Mission: discover which BUSINESS CAPABILITIES still make decisions using the
 * deprecated election-state representation, so the migration inventory is measured
 * rather than inferred.
 *
 * A legacy consumer is a capability that makes a business decision using the
 * deprecated representation — NOT any code path that obtains the value. Obtaining is
 * not depending: `return $election->status` does not prevent migration;
 * `if ($election->status === 'active')` does.
 *
 * Phase 1 observes SQL predicates only, because that is the highest-signal access
 * path: code filters on a value because it is deciding something, and a predicate
 * never fires during serialisation. Attribute interception is deliberately NOT
 * implemented — it would fire on every toArray(), and the three attribute-based
 * decisions already known are recorded statically in PBDIGIT-48. Extend this only if
 * the observed inventory proves incomplete.
 *
 * Behaviour-preserving by construction: a query listener cannot alter a query or its
 * results; it is registered only when observation is switched on; and it never throws.
 *
 * @see docs/publicdigit/reviews/2026-08-06-58A-observation-mechanism-review.md
 * @see docs/publicdigit/backlog/PBDIGIT-58-complete-legacy-election-state-migration.md
 */
final class LegacyElectionStateObserver
{
    /** Deprecated fields, per DeprecationPolicy::FIELDS. */
    private const FIELDS = ['status', 'is_active'];

    private const LOG_TAG = '[58A]';

    /**
     * Calling code → the capability it serves, using PBDIGIT-58's capability names.
     * The report is capability-first because that is what the migration slices migrate.
     */
    private const CAPABILITIES = [
        'Models/User.php' => 'Election Entry Resolution',
        'Middleware/ElectionMiddleware.php' => 'Election Context Resolution',
        'Controllers/OrganisationController.php' => 'Organisation Reporting',
        'Controllers/Election/ElectionManagementController.php' => 'Election Management',
        'Controllers/CommissionDashboardController.php' => 'Commission Dashboard',
        'Controllers/Membership/OrganisationNewsletterController.php' => 'Member Communication',
        'Controllers/Demo/' => 'Demo Platform',
        'Console/Commands/Setup' => 'Demo Platform',
        'Console/Commands/ListAllElections.php' => 'Demo Platform',
        'Policies/ElectionPolicy.php' => 'Authorisation',
    ];

    /** Observed hits this process, keyed by capability + caller. */
    private static array $seen = [];

    public static function isEnabled(): bool
    {
        return (bool) config('voting_security.observe_legacy_election_state', false);
    }

    /**
     * Attach the listener. A no-op unless observation is switched on.
     */
    public static function register(): void
    {
        if (! self::isEnabled()) {
            return;
        }

        DB::listen(static function (QueryExecuted $query): void {
            try {
                self::inspect($query);
            } catch (\Throwable $e) {
                // An observer must never break the thing it observes.
            }
        });
    }

    private static function inspect(QueryExecuted $query): void
    {
        $sql = $query->sql;

        if (! preg_match('/\belections\b/i', $sql)) {
            return;
        }

        // A predicate, not a mention: the field must be compared against something.
        $fields = [];
        foreach (self::FIELDS as $field) {
            if (preg_match('/["`\']?\b' . $field . '\b["`\']?\s*(=|!=|<>|is\b|in\b|>|<)/i', $sql)) {
                $fields[] = $field;
            }
        }
        if ($fields === []) {
            return;
        }

        $frames = self::appFrames();
        $capability = self::capabilityFor($frames);
        $signature = $capability . '|' . implode(',', $fields) . '|' . ($frames[0] ?? 'unknown');

        if (isset(self::$seen[$signature])) {
            self::$seen[$signature]['count']++;

            return;
        }

        self::$seen[$signature] = [
            'capability' => $capability,
            'fields' => $fields,
            'caller' => $frames[0] ?? 'unknown',
            'stack' => $frames,
            'count' => 1,
        ];

        Log::warning(self::LOG_TAG . ' legacy election-state predicate', [
            'capability' => $capability,
            'fields' => $fields,
            'caller' => $frames[0] ?? 'unknown',
            'stack' => array_slice($frames, 0, 4),
            'sql' => Str::limit($sql, 200),
        ]);
    }

    /**
     * The capability behind a call stack. An UNMAPPED result is itself a finding: a
     * code path exists that PBDIGIT-58's capability table does not describe, which is
     * precisely what 58A was commissioned to surface.
     *
     * @param  list<string>  $frames
     */
    private static function capabilityFor(array $frames): string
    {
        foreach ($frames as $frame) {
            foreach (self::CAPABILITIES as $needle => $capability) {
                if (str_contains($frame, $needle)) {
                    return $capability;
                }
            }
        }

        return 'UNMAPPED — not in the capability table';
    }

    /**
     * Application frames only. Vendor frames cannot name a capability.
     *
     * @return list<string>
     */
    private static function appFrames(): array
    {
        $base = base_path() . '/';
        $frames = [];

        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 50) as $frame) {
            $file = $frame['file'] ?? '';
            if ($file === '' || str_contains($file, '/vendor/')) {
                continue;
            }
            $relative = str_replace($base, '', $file);
            if (str_contains($relative, 'LegacyElectionStateObserver.php')) {
                continue;
            }
            $frames[] = $relative . ':' . ($frame['line'] ?? 0);
            if (count($frames) >= 6) {
                break;
            }
        }

        return $frames;
    }

    /**
     * What has been observed in this process, grouped by capability.
     *
     * @return array<string, mixed>
     */
    public static function report(): array
    {
        $byCapability = [];
        foreach (self::$seen as $hit) {
            $byCapability[$hit['capability']][] = $hit;
        }

        return $byCapability;
    }
}
