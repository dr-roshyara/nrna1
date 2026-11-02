<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * PBDIGIT-318 S1 — static guards for the ratified Membership–Election boundary.
 *
 * Ratified rule (2026-10-08): "There is no shared persistence and no Membership → entitlement shortcut"
 * (docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46).
 *
 * Three coupling kinds are guarded, statically (no database, no framework boot):
 *   1. write/class coupling  — Membership code references Election classes or tables;
 *   2. read coupling         — Election code reads Membership- or affiliation-owned data;
 *   3. integrity coupling    — the net migration state leaves a foreign key from an
 *                              Election table to a Membership- or affiliation-owned table.
 *
 * BASELINE: the known violations approved by the PO on 2026-11-02 (V1, V1b, V2, V3′; plan
 * docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md). The baseline may only
 * SHRINK: a baseline entry that no longer violates fails the test, so it must be removed by the slice
 * that fixes it (S2, S3, S4). Adding an entry requires an explicit architecture decision.
 *
 * Code is scanned with comments removed (PHP tokenizer), so prose in docblocks does not count.
 */
final class MembershipElectionBoundaryTest extends TestCase
{
    /** Membership-owned code (ratified context + legacy Membership models). */
    private const MEMBERSHIP_PATHS = [
        'app/Contexts/Membership',
        'app/Models/Member.php',
        'app/Models/MemberImportJob.php',
        'app/Models/MembershipApplication.php',
        'app/Models/MembershipFee.php',
        'app/Models/MembershipPayment.php',
        'app/Models/MembershipRenewal.php',
        'app/Models/MembershipType.php',
    ];

    /** Election-owned code. */
    private const ELECTION_PATHS = [
        'app/Contexts/Election',
        'app/Contexts/Elections',
        'app/Domain/Election',
    ];

    /** Patterns that indicate Membership code depends on Election classes or tables. */
    private const ELECTION_REFERENCES = [
        'ElectionMembership class' => '/\bElectionMembership\b/',
        'election_memberships table' => '/[\'"]election_memberships[\'"]/',
        'Election context namespace' => '/\\\\Contexts\\\\Elections?\\\\/',
        'Election model' => '/\bApp\\\\Models\\\\Election\b/',
    ];

    /** Membership- and affiliation-owned tables (Membership context; OrganisationAffiliation stores). */
    private const FOREIGN_TABLES = [
        'members', 'member_import_jobs', 'membership_types', 'membership_fees', 'membership_applications',
        'membership_renewals', 'membership_payments', 'org_memberships', 'org_membership_facts',
        'org_membership_commands', 'organisation_users', 'user_organisation_roles',
    ];

    /** Election-owned tables (explicit names plus prefixes). */
    private const ELECTION_TABLES = [
        'elections', 'voters', 'codes', 'votes', 'results', 'candidacies', 'candidacy_applications', 'posts',
    ];
    private const ELECTION_TABLE_PREFIXES = ['election_', 'voter_', 'demo_'];

    /** Approved baseline (PO, 2026-11-02): file => violation labels. May only shrink. */
    private const BASELINE_MEMBERSHIP_TO_ELECTION = [
        // V1: Member::endMembership writes election_memberships (Member.php:184-189) — removed by S2.
        // V1b: Member::canVoteInElection(Election) decides voting entitlement (Member.php:142-149) — removed by S2.
        'app/Models/Member.php' => ['ElectionMembership class', 'Election model'],
    ];
    private const BASELINE_ELECTION_READS = [
        // V2: Election eligibility adapter reads members / organisation_users / membership_types — replaced by S3.
        'app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php' => [
            'table name string', 'model static call', 'model import',
        ],
    ];
    /** "table(columns) -> target" */
    private const BASELINE_FOREIGN_KEYS = [
        // V3′: voters.member_id -> members ON DELETE CASCADE (2026_03_07_145529_create_voters_table.php:30) — S4.
        'voters(member_id) -> members',
    ];

    public function test_membership_code_does_not_reference_election(): void
    {
        $found = [];
        foreach ($this->phpFiles(self::MEMBERSHIP_PATHS) as $file) {
            $code = $this->codeOf($file);
            foreach (self::ELECTION_REFERENCES as $label => $pattern) {
                if (preg_match($pattern, $code)) {
                    $found[$file][] = $label;
                }
            }
        }

        $this->assertBaseline($found, self::BASELINE_MEMBERSHIP_TO_ELECTION, 'Membership → Election');
    }

    public function test_election_code_does_not_read_membership_or_affiliation_data(): void
    {
        $tables = implode('|', self::FOREIGN_TABLES);
        $patterns = [
            'table name string' => '/[\'"](' . $tables . ')(\.[a-z_]+)?[\'"]/',
            'raw SQL table' => '/\b(from|join)\s+[`"]?(' . $tables . ')\b/i',
            'model static call' => '/\b(Member|MembershipType|MembershipFee|MembershipApplication|MembershipRenewal|MembershipPayment|OrganisationUser|UserOrganisationRole)::/',
            'model import' => '/\buse\s+App\\\\Models\\\\(Member|Membership\w*|OrganisationUser|UserOrganisationRole)\s*;/',
            'Membership context import' => '/\buse\s+App\\\\Contexts\\\\Membership\\\\/',
        ];

        $found = [];
        foreach ($this->phpFiles(self::ELECTION_PATHS) as $file) {
            $code = $this->codeOf($file);
            foreach ($patterns as $label => $pattern) {
                if (preg_match($pattern, $code)) {
                    $found[$file][] = $label;
                }
            }
        }

        $this->assertBaseline($found, self::BASELINE_ELECTION_READS, 'Election → Membership/affiliation data');
    }

    public function test_no_foreign_key_from_election_tables_to_membership_or_affiliation_tables(): void
    {
        $keys = $this->netForeignKeys();

        $found = [];
        foreach ($keys as $key) {
            if ($this->isElectionTable($key['table']) && in_array($key['target'], self::FOREIGN_TABLES, true)) {
                $found[] = sprintf('%s(%s) -> %s', $key['table'], implode(',', $key['columns']), $key['target']);
            }
        }
        sort($found);

        $baseline = self::BASELINE_FOREIGN_KEYS;
        sort($baseline);
        $new = array_values(array_diff($found, $baseline));
        $fixed = array_values(array_diff($baseline, $found));

        $this->assertSame([], $new, "New cross-context foreign key(s) (not in the approved baseline):\n" . implode("\n", $new));
        $this->assertSame([], $fixed, "Baseline entry no longer present — remove it from BASELINE_FOREIGN_KEYS:\n" . implode("\n", $fixed));
    }

    // ------------------------------------------------------------------ helpers

    /** @param array<string, list<string>> $found @param array<string, list<string>> $baseline */
    private function assertBaseline(array $found, array $baseline, string $rule): void
    {
        $new = [];
        foreach ($found as $file => $labels) {
            foreach ($labels as $label) {
                if (!in_array($label, $baseline[$file] ?? [], true)) {
                    $new[] = "$file: $label";
                }
            }
        }
        $fixed = [];
        foreach ($baseline as $file => $labels) {
            foreach ($labels as $label) {
                if (!in_array($label, $found[$file] ?? [], true)) {
                    $fixed[] = "$file: $label";
                }
            }
        }

        $this->assertSame([], $new, "$rule — violation(s) not in the approved baseline:\n" . implode("\n", $new));
        $this->assertSame([], $fixed, "$rule — baseline entry no longer violates; remove it from the baseline:\n" . implode("\n", $fixed));
    }

    /** @param list<string> $paths @return list<string> repository-relative paths */
    private function phpFiles(array $paths): array
    {
        $root = dirname(__DIR__, 2);
        $files = [];
        foreach ($paths as $path) {
            $abs = $root . '/' . $path;
            if (is_file($abs)) {
                $files[] = $path;
                continue;
            }
            if (!is_dir($abs)) {
                continue;
            }
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($abs, RecursiveDirectoryIterator::SKIP_DOTS));
            foreach ($it as $info) {
                if ($info->isFile() && $info->getExtension() === 'php') {
                    $files[] = substr($info->getPathname(), strlen($root) + 1);
                }
            }
        }
        sort($files);

        return $files;
    }

    /** Source code with comments and docblocks removed. */
    private function codeOf(string $relativePath): string
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2) . '/' . $relativePath);
        $code = '';
        foreach (token_get_all($source) as $token) {
            if (is_array($token)) {
                if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                    continue;
                }
                $code .= $token[1];
            } else {
                $code .= $token;
            }
        }

        return $code;
    }

    private function isElectionTable(string $table): bool
    {
        if (in_array($table, self::ELECTION_TABLES, true)) {
            return true;
        }
        foreach (self::ELECTION_TABLE_PREFIXES as $prefix) {
            if (str_starts_with($table, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replays the up() part of every migration in filename order and returns the foreign keys left at the end.
     * Approximation (documented in the developer guide): driver branches are treated as executed;
     * a Schema::create/table segment owns the ->foreign() calls that follow it.
     *
     * @return array<string, array{table: string, columns: list<string>, target: string}>
     */
    private function netForeignKeys(): array
    {
        $root = dirname(__DIR__, 2);
        $files = glob($root . '/database/migrations/*.php') ?: [];
        foreach (glob($root . '/app/Contexts/*/Infrastructure/Database/Migrations/*/*.php') ?: [] as $f) {
            $files[] = $f;
        }
        usort($files, static fn (string $a, string $b): int => strcmp(basename($a), basename($b)));

        $keys = [];
        foreach ($files as $file) {
            $code = $this->codeOf(substr($file, strlen($root) + 1));
            $up = $this->upBody($code);
            if ($up === '') {
                continue;
            }

            // Schema builder segments.
            $parts = preg_split('/Schema::(?:create|table)\(\s*[\'"](\w+)[\'"]/', $up, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];
            for ($i = 1; $i + 1 < count($parts); $i += 2) {
                $table = $parts[$i];
                $segment = $parts[$i + 1];

                // drops first in the segment order: apply in textual order by scanning events
                $events = [];
                preg_match_all('/->foreign\(\s*(\[[^\]]*\]|[\'"][^\'"]+[\'"])\s*\)(?:(?!->foreign\().)*?->on\(\s*[\'"](\w+)[\'"]/s', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $events[] = [$match[0][1], 'add', $this->columns($match[1][0]), $match[2][0]];
                }
                preg_match_all('/foreign(?:Id|Uuid|Ulid)\(\s*[\'"](\w+)[\'"]\s*\)(?:(?!foreign(?:Id|Uuid|Ulid)\().)*?->constrained\(\s*(?:[\'"](\w+)[\'"])?/s', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $column = $match[1][0];
                    $target = isset($match[2]) && $match[2][0] !== '' ? $match[2][0] : preg_replace('/_id$/', '', $column) . 's';
                    $events[] = [$match[0][1], 'add', [$column], $target];
                }
                preg_match_all('/->dropForeign\(\s*(\[[^\]]*\]|[\'"][^\'"]+[\'"])\s*\)/', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $arg = trim($match[1][0]);
                    $events[] = [$match[0][1], 'drop', str_starts_with($arg, '[') ? $this->columns($arg) : trim($arg, '\'"'), null];
                }
                usort($events, static fn (array $a, array $b): int => $a[0] <=> $b[0]);

                foreach ($events as [, $kind, $cols, $target]) {
                    if ($kind === 'add') {
                        $keys[$table . '|' . implode(',', $cols)] = ['table' => $table, 'columns' => $cols, 'target' => $target];
                    } else {
                        $this->dropKey($keys, $table, $cols);
                    }
                }
            }

            // Raw SQL statements.
            preg_match_all('/ALTER TABLE\s+[`"]?(\w+)[`"]?\s+(ADD CONSTRAINT\s+[`"]?\w+[`"]?\s+FOREIGN KEY\s*\(([^)]*)\)\s*REFERENCES\s+[`"]?(\w+)|DROP\s+(?:FOREIGN KEY|CONSTRAINT)\s+(?:IF EXISTS\s+)?[`"]?(\w+))/i', $up, $m, PREG_SET_ORDER);
            foreach ($m as $match) {
                $table = $match[1];
                if (!empty($match[3])) {
                    $cols = $this->columns('[' . $match[3] . ']');
                    $keys[$table . '|' . implode(',', $cols)] = ['table' => $table, 'columns' => $cols, 'target' => $match[4]];
                } elseif (!empty($match[5])) {
                    $this->dropKey($keys, $table, $match[5]);
                }
            }
        }

        return $keys;
    }

    private function upBody(string $code): string
    {
        $start = strpos($code, 'function up(');
        if ($start === false) {
            return '';
        }
        $end = strpos($code, 'function down(', $start);

        return $end === false ? substr($code, $start) : substr($code, $start, $end - $start);
    }

    /** @return list<string> */
    private function columns(string $raw): array
    {
        preg_match_all('/[\'"`]?([a-z0-9_]+)[\'"`]?/i', trim($raw, '[]'), $m);

        return array_values(array_filter($m[1], static fn (string $c): bool => $c !== ''));
    }

    /**
     * @param array<string, array{table: string, columns: list<string>, target: string}> $keys
     * @param list<string>|string $colsOrName column list, or a constraint name (Laravel convention {table}_{cols}_foreign)
     */
    private function dropKey(array &$keys, string $table, array|string $colsOrName): void
    {
        foreach ($keys as $id => $key) {
            if ($key['table'] !== $table) {
                continue;
            }
            $name = $table . '_' . implode('_', $key['columns']) . '_foreign';
            $match = is_array($colsOrName) ? $key['columns'] === $colsOrName : $colsOrName === $name;
            if ($match) {
                unset($keys[$id]);
            }
        }
    }
}
