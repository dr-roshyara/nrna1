<?php

declare(strict_types=1);

namespace Tests\Architecture\Support;

/**
 * The scanner and comparator behind ArchitectureRatchetTest (PublicDigit Hexagonal DDD baseline §8).
 *
 * scan() returns every violation as "rule|relative/file|exact dependency" => occurrences. Comments and docblocks are
 * stripped first (token_get_all), so a class named in a comment is never a violation. compare() reports:
 *  - new:   found more often than the baseline allows (unlisted, or one more occurrence in a listed file);
 *  - stale: listed more often than found (fixed: the entry must shrink or go).
 */
final class ArchitectureRatchet
{
    /** Facades whose bare name is unambiguous enough to count as a facade call even without an import. */
    private const FACADES = ['DB', 'Log', 'Cache', 'Queue', 'Mail', 'Storage', 'Config', 'Session', 'Redis', 'Schema', 'Gate', 'Auth', 'Artisan', 'Bus', 'Hash', 'Crypt'];

    /** Table prefixes owned by a bounded context; delivery code must never name them (A-4). */
    private const CONTEXT_TABLE_PREFIXES = ['refgeo_', 'org_membership_'];

    private const DELIVERY_ROOTS = ['app/Http/', 'app/Console/', 'app/Jobs/', 'app/Listeners/', 'routes/'];

    /**
     * @param list<string> $publishedLanguage types other contexts may import (A-3), each named explicitly
     * @return array<string, int>
     */
    public static function scan(string $root, string $extension, array $publishedLanguage = []): array
    {
        $found = [];
        foreach (['app', 'routes'] as $top) {
            if (!is_dir("$root/$top")) {
                continue;
            }
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("$root/$top", \FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                $path = $file->getPathname();
                if (!str_ends_with($path, $extension)) {
                    continue;
                }
                $relative = substr($path, strlen($root) + 1);
                foreach (self::violations($relative, self::code((string) file_get_contents($path)), $publishedLanguage) as $key => $n) {
                    $found[$key] = ($found[$key] ?? 0) + $n;
                }
            }
        }
        ksort($found);

        return $found;
    }

    /**
     * Both sizes of a baseline: its entries, and the occurrences they tolerate (a raised count is growth too).
     *
     * @return array{entries: int, occurrences: int}
     */
    public static function totals(array $baseline): array
    {
        return ['entries' => count($baseline), 'occurrences' => array_sum(array_map(static fn (array $e): int => (int) $e['count'], $baseline))];
    }

    /** @return list<string> */
    public static function loadList(string $path): array
    {
        return is_file($path) ? json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR) : [];
    }

    /** A-6: every class file in app/Contexts/Shared, relative to $root. @return list<string> */
    public static function sharedContents(string $root, string $extension): array
    {
        $dir = "$root/app/Contexts/Shared";
        $out = [];
        if (is_dir($dir)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
                if (str_ends_with($file->getPathname(), $extension)) {
                    $out[] = substr($file->getPathname(), strlen($root) + 1);
                }
            }
        }
        sort($out);

        return $out;
    }

    /**
     * @param list<string> $found
     * @param list<string> $listed
     * @return array{new: list<string>, stale: list<string>}
     */
    public static function compareShared(array $found, array $listed): array
    {
        return ['new' => array_values(array_diff($found, $listed)), 'stale' => array_values(array_diff($listed, $found))];
    }

    /** The human-readable inventory, generated from the baseline (never edited by hand). */
    public static function inventory(array $baseline): string
    {
        $titles = ['A-1' => 'A-1 Domain purity', 'A-2' => 'A-2 Application layer', 'A-3' => 'A-3 Cross-context imports', 'A-4' => 'A-4 Delivery boundary', 'A-5' => "A-5 Shared 'tenant' connection (D-222)"];
        $md = "# PublicDigit architecture deviation inventory\n\n"
            . "**Generated** from `tests/Architecture/baselines/architecture-ratchet.json` (the ratchet baseline) by `ArchitectureRatchet::inventory()`; `ArchitectureRatchetTest` fails if this file and the baseline differ. **Do not edit by hand:** fix the code, shrink the baseline, regenerate.\n\n"
            . "Each row is one violation: rule, file, exact dependency, number of occurrences. Only `.php` files are scanned, with comments stripped, so a backup file or a class named in a comment is never counted. Allowed and therefore absent: the published language (`tests/Architecture/baselines/published-language.json`) and Shared, whose contents are a closed list (A-6, `shared-contents.json`).\n\n"
            . "**Separately recorded artefact:** `app/Contexts/Membership/Domain/Models/Member.legacy.php.backup` (not executable; outside the scan). Recommendation: delete it with R-4.\n\n"
            . "| Rule | Violations | Files |\n|---|---|---|\n";
        $by = [];
        $files = [];
        foreach ($baseline as $k => $e) {
            [$r, $f] = explode('|', $k);
            $by[$r][$k] = $e;
            $files[$r][$f] = true;
        }
        foreach ($titles as $r => $t) {
            $md .= "| $t | " . count($by[$r] ?? []) . ' | ' . count($files[$r] ?? []) . " |\n";
        }
        foreach ($titles as $r => $t) {
            $md .= "\n## $t\n\n| File | Dependency | n | Classification | Reason | Remediation | Decision needed |\n|---|---|---|---|---|---|---|\n";
            foreach ($by[$r] ?? [] as $k => $e) {
                [, $f, $d] = explode('|', $k);
                $md .= '| `' . $f . '` | `' . $d . '` | ' . $e['count'] . ' | ' . $e['class'] . ' | ' . $e['reason'] . ' | ' . $e['remediation'] . ' | ' . $e['decision'] . " |\n";
            }
        }

        return $md;
    }

    /** @return array<string, array{count: int, reason: string}> */
    public static function loadBaseline(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<string, int> $found
     * @param array<string, array{count: int}> $baseline
     * @return array{new: list<string>, stale: list<string>}
     */
    public static function compare(array $found, array $baseline): array
    {
        $new = array_keys(array_filter($found, static fn (int $n, string $k): bool => $n > (int) ($baseline[$k]['count'] ?? 0), ARRAY_FILTER_USE_BOTH));
        $stale = array_keys(array_filter($baseline, static fn (array $e, string $k): bool => (int) $e['count'] > ($found[$k] ?? 0), ARRAY_FILTER_USE_BOTH));
        sort($new);
        sort($stale);

        return ['new' => $new, 'stale' => $stale];
    }

    /** The source without comments and docblocks. */
    private static function code(string $source): string
    {
        $out = '';
        foreach (token_get_all($source) as $t) {
            if (is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $out .= is_array($t) ? $t[1] : $t;
        }

        return $out;
    }

    /** @return array<string, int> */
    private static function violations(string $file, string $code, array $publishedLanguage): array
    {
        $v = [];
        $add = static function (string $rule, string $dep, int $n = 1) use (&$v, $file): void {
            $v["$rule|$file|$dep"] = ($v["$rule|$file|$dep"] ?? 0) + $n;
        };

        preg_match_all('/^\s*use\s+\\\\?([A-Za-z_][\w\\\\]*)(?:\s+as\s+(\w+))?\s*;/m', $code, $m, PREG_SET_ORDER);
        $imports = [];   // alias => fully qualified name
        foreach ($m as $u) {
            $imports[$u[2] ?? '' ?: substr(strrchr('\\' . $u[1], '\\'), 1)] = $u[1];
        }
        preg_match_all('/(?<![\w\\\\])\\\\((?:Illuminate|App)\\\\[\w\\\\]+)/', $code, $inline);
        $references = array_unique(array_merge(array_values($imports), $inline[1]));

        $facadeCalls = [];
        preg_match_all('/(?<![\w\\\\$>:])\\\\?([A-Z]\w*)::/', $code, $calls);
        foreach ($calls[1] as $name) {
            $isFacade = isset($imports[$name])
                ? str_starts_with($imports[$name], 'Illuminate\\Support\\Facades\\')
                : in_array($name, self::FACADES, true);
            if ($isFacade) {
                $facadeCalls[$name] = ($facadeCalls[$name] ?? 0) + 1;
            }
        }

        if (preg_match('#^app/Contexts/(\w+)/(\w+)/#', $file, $ctx)) {
            [$context, $layer] = [$ctx[1], $ctx[2]];
            foreach ($references as $ref) {
                if ($layer === 'Domain' && (str_starts_with($ref, 'Illuminate\\') || str_starts_with($ref, 'Laravel\\') || str_starts_with($ref, 'App\\Models\\'))) {
                    $add('A-1', $ref);
                }
                if ($layer === 'Application' && (str_starts_with($ref, 'Illuminate\\Support\\Facades\\') || str_starts_with($ref, 'Illuminate\\Database\\Eloquent\\') || str_starts_with($ref, 'App\\Models\\'))) {
                    $add('A-2', $ref);
                }
                if (preg_match('#^App\\\\Contexts\\\\(\w+)\\\\(\w+)\\\\#', $ref, $other) && $other[1] !== $context && $other[1] !== 'Shared' && !in_array($ref, $publishedLanguage, true)) {
                    $add('A-3', $ref);
                }
            }
            foreach ($facadeCalls as $name => $n) {
                if ($layer === 'Domain') {
                    $add('A-1', "$name::", $n);
                }
                if ($layer === 'Application') {
                    $add('A-2', "$name::", $n);
                }
            }
        }

        $isDelivery = (bool) preg_match('#^app/Contexts/\w+/(Http|Console)/#', $file);
        foreach (self::DELIVERY_ROOTS as $root) {
            $isDelivery = $isDelivery || str_starts_with($file, $root);
        }
        if ($isDelivery) {
            foreach ($references as $ref) {
                $infrastructure = (bool) preg_match('#^App\\\\Contexts\\\\\w+\\\\Infrastructure\\\\#', $ref);
                $writePort = (bool) preg_match('#^App\\\\Contexts\\\\\w+\\\\Application\\\\Ports?\\\\[\w\\\\]*(Repository|Store|Ledger)$#', $ref);
                if ($infrastructure || $writePort) {
                    $add('A-4', $ref);
                }
            }
            preg_match_all('/(?<![\w\\\\$>:])\\\\?DB::(insert|update|delete|statement|unprepared|affectingStatement)\s*\(/', $code, $writes);
            foreach ($writes[1] as $write) {
                $add('A-4', "DB::$write");
            }
            // a query-builder write chained within one statement: DB::table('x')->where(...)->delete()
            preg_match_all('/(?<![\w\\\\$>:])\\\\?DB::table\s*\([^;]*?->(insert|insertGetId|insertOrIgnore|insertUsing|update|updateOrInsert|upsert|delete|truncate|increment|decrement)\s*\(/', $code, $chained);
            foreach ($chained[1] as $write) {
                $add('A-4', "DB::table->$write");
            }
            preg_match_all("/['\"]((?:" . implode('|', array_map('preg_quote', self::CONTEXT_TABLE_PREFIXES)) . ")\\w+)['\"]/", $code, $tables);
            foreach ($tables[1] as $table) {
                $add('A-4', "table:$table");
            }
        }

        $n = preg_match_all("/connection\\(\\s*['\"]tenant['\"]\\s*\\)/", $code);
        if ($n > 0) {
            $add('A-5', "connection('tenant')", $n);
        }
        $n = preg_match_all("/\\\$connection\\s*=\\s*['\"]tenant['\"]/", $code);
        if ($n > 0) {
            $add('A-5', "\$connection = 'tenant'", $n);
        }

        return $v;
    }
}
