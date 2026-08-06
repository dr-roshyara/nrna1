<?php

namespace App\Application\Election\Deprecation;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PBDIGIT-58A — observation only.
 *
 * Records which code paths still read the deprecated election-state fields, so the
 * migration inventory is measured rather than inferred. Text search could not settle
 * it: `status` and `is_active` appear in ~520 statements across the app, and two
 * consumers were wrongly attributed before this existed.
 *
 * Behaviour-preserving by construction:
 *   - a query listener cannot alter a query or its results;
 *   - it is registered only when observation is switched on;
 *   - it never throws — a probe must not be able to break what it observes.
 *
 * Deliberately NOT implemented as model accessors. `is_active` is cast to boolean,
 * so an accessor would have to reproduce the cast to avoid changing what callers
 * receive. Attribute reads ($election->status) are therefore not covered here; the
 * static inventory in PBDIGIT-48 lists those sites, and all of them are display-only.
 * What this probe covers is query predicates, which is where both Critical
 * capabilities read.
 *
 * @see docs/publicdigit/backlog/PBDIGIT-58-complete-legacy-election-state-migration.md
 */
final class LegacyElectionStateProbe
{
    /** Deprecated fields, per DeprecationPolicy::FIELDS. */
    private const FIELDS = ['status', 'is_active'];

    private const LOG_TAG = '[legacy-election-state]';

    /** Caller signatures already reported this process, to keep the log readable. */
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
                // A probe must never break the thing it observes.
            }
        });
    }

    private static function inspect(QueryExecuted $query): void
    {
        $sql = $query->sql;

        // Only queries touching the elections table.
        if (! preg_match('/\belections\b/i', $sql)) {
            return;
        }

        $fields = [];
        foreach (self::FIELDS as $field) {
            if (preg_match('/["`\']?\b' . $field . '\b["`\']?\s*(=|!=|<>|is|in|>|<)/i', $sql)) {
                $fields[] = $field;
            }
        }
        if ($fields === []) {
            return;
        }

        $caller = self::caller();
        $signature = implode(',', $fields) . '|' . ($caller[0] ?? 'unknown');
        if (isset(self::$seen[$signature])) {
            self::$seen[$signature]['count']++;

            return;
        }
        self::$seen[$signature] = ['count' => 1];

        Log::warning(self::LOG_TAG . ' deprecated field read in an elections query', [
            'fields' => $fields,
            'caller' => $caller,
            'sql' => \Illuminate\Support\Str::limit($sql, 220),
            'note' => 'PBDIGIT-58A observation — behaviour unchanged',
        ]);
    }

    /**
     * Application frames only. Vendor frames say nothing about which capability read
     * the field, and the caller is the only thing that makes a hit actionable.
     *
     * @return list<string>
     */
    private static function caller(): array
    {
        $base = base_path() . '/';
        $frames = [];

        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 40) as $frame) {
            $file = $frame['file'] ?? '';
            if ($file === '' || str_contains($file, '/vendor/')) {
                continue;
            }
            $relative = str_replace($base, '', $file);
            if (str_contains($relative, 'Deprecation/LegacyElectionStateProbe.php')) {
                continue;
            }
            $frames[] = $relative . ':' . ($frame['line'] ?? 0);
            if (count($frames) >= 4) {
                break;
            }
        }

        return $frames;
    }

    /**
     * What has been observed this process. Used by the reporting command.
     *
     * @return array<string, array{count: int}>
     */
    public static function observed(): array
    {
        return self::$seen;
    }
}
