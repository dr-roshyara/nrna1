<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * PBDIGIT-318 S1 / S1b — static guards for the ratified Membership–Election boundary.
 *
 * Ratified rule (2026-10-08): "There is no shared persistence and no Membership → entitlement shortcut"
 * (docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46).
 *
 * Three coupling kinds are guarded, statically (no database, no framework boot):
 *   1. write/class coupling  — Membership code references Election models, Election tables or the Election contexts;
 *   2. read coupling         — Election code references Membership- or affiliation-owned models, tables or context;
 *   3. integrity coupling    — the net migration state leaves a foreign key from an
 *                              Election table to a Membership- or affiliation-owned table.
 *
 * S1b (PO, 2026-11-02): violations are tracked per file, per target ("model:X", "table:y", "namespace:Z")
 * and per OCCURRENCE COUNT. A new target, or one more occurrence of a baselined target, fails the test.
 * Table references are anchored (query-builder calls, validation rules, `$table` properties, SQL statement
 * strings), so most prose strings and array keys are not reported. The detectors themselves are tested
 * with planted forms (the self-tests below). Known limits: developer guide 02.
 *
 * BASELINE: the known violations V1, V1b, V1c, V1d, V2, V3′ (plan
 * docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md). The baseline may only SHRINK:
 * fewer occurrences, or a target that no longer violates, fails the test until the baseline is lowered by
 * the slice that fixes it (S2, S3, S4).
 *
 * BASELINE GOVERNANCE (PO, 2026-11-02):
 *   - The PO approves routine baseline additions and changes that are consistent with existing architectural decisions.
 *   - The ARB decides changes to architectural policy and unresolved cross-context ownership questions (e.g. OQ-ME-3, OQ-ME-4).
 *   - A baseline entry records a KNOWN CONDITION; it does not make the dependency architecturally compliant.
 *   - No newly discovered violation is added automatically, and no baseline change may settle an open architectural question.
 *
 * Code is scanned with comments removed (PHP tokenizer), so prose in docblocks does not count.
 */
final class MembershipElectionBoundaryTest extends TestCase
{
    /** Membership-owned code (ratified context + legacy Membership models). Path scope unchanged by S1b. */
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

    /** Election-owned code. Path scope unchanged by S1b. */
    private const ELECTION_PATHS = [
        'app/Contexts/Election',
        'app/Contexts/Elections',
        'app/Domain/Election',
    ];

    /** Election-owned tables (explicit names plus prefixes). */
    private const ELECTION_TABLES = [
        'elections', 'voters', 'codes', 'votes', 'results', 'candidacies', 'candidacy_applications', 'posts',
    ];
    private const ELECTION_TABLE_PREFIXES = ['election_', 'voter_', 'demo_'];

    /** S1b: App\Models classes of the Election-owned tables above (inventory of 2026-11-02). */
    private const ELECTION_MODELS = [
        'BaseResult', 'BaseVote', 'Candidacy', 'CandidacyApplication', 'Code', 'DemoCandidacy', 'DemoCode',
        'DemoPost', 'DemoResult', 'DemoVote', 'DemoVoterSlug', 'DemoVoterSlugStep', 'Election', 'ElectionAuditLog',
        'ElectionMembership', 'ElectionOfficer', 'ElectionSecurityEvent', 'ElectionStateTransition', 'Post',
        'Result', 'Vote', 'Voter', 'VoterInvitation', 'VoterIpVerification', 'VoterRegistration', 'VoterSlug',
        'VoterSlugStep', 'VoterVerification',
    ];

    /** Membership- and affiliation-owned tables (Membership context; OrganisationAffiliation stores). */
    private const FOREIGN_TABLES = [
        'members', 'member_import_jobs', 'membership_types', 'membership_fees', 'membership_applications',
        'membership_renewals', 'membership_payments', 'org_memberships', 'org_membership_facts',
        'org_membership_commands', 'organisation_users', 'user_organisation_roles',
    ];

    /** App\Models classes of the tables above. */
    private const FOREIGN_MODELS = [
        'Member', 'MemberImportJob', 'MembershipApplication', 'MembershipFee', 'MembershipPayment',
        'MembershipRenewal', 'MembershipType', 'OrganisationUser', 'UserOrganisationRole',
    ];

    /**
     * Approved baseline (PO, 2026-11-02): file => target => occurrence count. May only shrink.
     * A count records the occurrences that exist today; each import or use counts once (see modelReferences()).
     * These are KNOWN VIOLATIONS awaiting remediation (S2, S3, S4), not compliant dependencies.
     */
    private const BASELINE_MEMBERSHIP_TO_ELECTION = [
        'app/Models/Member.php' => [
            // V1b: canVoteInElection(Election) decides voting entitlement (Member.php:142-149) — S2.
            // Occurrences: import :13, type hint :142.
            'model:Election' => 2,
            // V1: endMembership writes election_memberships (Member.php:184-189) — S2. Occurrence: :187.
            'model:ElectionMembership' => 1,
            // V1c (PO, 2026-11-02, found by S1b): voters() hasMany(Voter::class) (Member.php:82-85) — S2 (code side of V3′).
            'model:Voter' => 1,
        ],
        // V1d (PO, 2026-11-02, found by S1b): a Membership job soft-deletes Election-owned voter invitations
        // (CleanupExpiredInvitations.php:38-43) — S2. Occurrences: import :5, VoterInvitation::where :38.
        'app/Contexts/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php' => ['model:VoterInvitation' => 2],
    ];
    private const BASELINE_ELECTION_READS = [
        // V2: the Election eligibility adapter reads Membership / affiliation data directly — replaced by S3.
        'app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php' => [
            'model:OrganisationUser' => 3,       // import :8, :153, :179
            'table:members' => 2,                // :108, :197
            'table:membership_types' => 1,       // :110
            'table:organisation_users' => 2,     // :91, :109
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
            $targets = $this->membershipToElectionTargets($this->sourceOf($file));
            if ($targets !== []) {
                $found[$file] = $targets;
            }
        }

        $this->assertAgainstBaseline($found, self::BASELINE_MEMBERSHIP_TO_ELECTION, 'Membership → Election');
    }

    public function test_election_code_does_not_read_membership_or_affiliation_data(): void
    {
        $found = [];
        foreach ($this->phpFiles(self::ELECTION_PATHS) as $file) {
            $targets = $this->electionToForeignTargets($this->sourceOf($file));
            if ($targets !== []) {
                $found[$file] = $targets;
            }
        }

        $this->assertAgainstBaseline($found, self::BASELINE_ELECTION_READS, 'Election → Membership/affiliation data');
    }

    public function test_no_foreign_key_from_election_tables_to_membership_or_affiliation_tables(): void
    {
        $found = $this->crossContextKeys($this->replayForeignKeys($this->migrationSources()));

        $baseline = self::BASELINE_FOREIGN_KEYS;
        sort($baseline);
        $new = array_values(array_diff($found, $baseline));
        $fixed = array_values(array_diff($baseline, $found));

        $this->assertSame([], $new, "New cross-context foreign key(s) (not in the approved baseline):\n" . implode("\n", $new));
        $this->assertSame([], $fixed, "Baseline entry no longer present — remove it from BASELINE_FOREIGN_KEYS:\n" . implode("\n", $fixed));
    }

    // ------------------------------------------------------------------ S1b: detector self-tests

    /** @return array<string, array{string, list<string>}> planted Membership code => expected targets */
    public static function plantedMembershipViolations(): array
    {
        $m = "<?php\nnamespace App\\Contexts\\Membership;\n";

        return [
            'import of an Election model' => [$m . "use App\\Models\\ElectionMembership;\n", ['model:ElectionMembership']],
            'aliased import' => [$m . "use App\\Models\\Voter as V;\nV::query();\n", ['model:Voter']],
            'group import' => [$m . "use App\\Models\\{Vote, Code};\n", ['model:Code', 'model:Vote']],
            'fully qualified, no import' => [$m . "\\App\\Models\\Election::query()->update([]);\n", ['model:Election']],
            'same namespace App\\Models, bare class' => ["<?php\nnamespace App\\Models;\nclass P { public function v() { return \$this->hasMany(Voter::class); } }\n", ['model:Voter']],
            'write to voters (query builder)' => [$m . "DB::table('voters')->update(['x' => 1]);\n", ['table:voters']],
            'write with alias' => [$m . "DB::table('elections as e')->delete();\n", ['table:elections']],
            'raw SQL insert into a prefixed table' => [$m . "DB::insert('insert into voter_slugs (id) values (?)', [1]);\n", ['table:voter_slugs']],
            'raw SQL, schema-qualified' => [$m . "DB::statement('UPDATE public.votes SET x = 1');\n", ['table:votes']],
            'election_memberships' => [$m . "DB::table('election_memberships')->insert([]);\n", ['table:election_memberships']],
            'validation rule' => [$m . "Rule::exists('elections', 'id');\n", ['table:elections']],
            'validation string' => [$m . "\$rules = ['e' => 'required|exists:elections,id'];\n", ['table:elections']],
            'Election context namespace' => [$m . "use App\\Contexts\\Elections\\Domain\\Foo;\n", ['namespace:Election']],
        ];
    }

    /** @return array<string, array{string, list<string>}> planted Election code => expected targets */
    public static function plantedElectionReads(): array
    {
        $e = "<?php\nnamespace App\\Contexts\\Elections;\n";

        return [
            'query builder table' => [$e . "DB::table('organisation_users')->get();\n", ['table:organisation_users']],
            'aliased import' => [$e . "use App\\Models\\Member as M;\nM::query();\n", ['model:Member']],
            'group import' => [$e . "use App\\Models\\{Member, Voter};\n", ['model:Member']],
            'table alias' => [$e . "DB::table('members as m')->get();\n", ['table:members']],
            'schema-qualified raw SQL' => [$e . "DB::select('select * from public.members');\n", ['table:members']],
            'left join' => [$e . "\$q->leftJoin('membership_types', 'a', '=', 'b');\n", ['table:membership_types']],
            'validation rule' => [$e . "Rule::exists('user_organisation_roles', 'user_id');\n", ['table:user_organisation_roles']],
            'fully qualified model' => [$e . "\\App\\Models\\UserOrganisationRole::where('a', 1);\n", ['model:UserOrganisationRole']],
            'Membership context import' => [$e . "use App\\Contexts\\Membership\\Domain\\X;\n", ['namespace:Membership']],
        ];
    }

    /** @return array<string, array{string, string}> code that must not be reported => detector(s): both | membership | election */
    public static function permittedForms(): array
    {
        $m = "<?php\nnamespace App\\Contexts\\Membership;\n";
        $e = "<?php\nnamespace App\\Contexts\\Elections;\n";

        return [
            'array keys equal to table names (E6)' => [$e . "\$a = ['members' => 0, 'organisation_users' => 1, 'voters' => 2];\n", 'both'],
            'prose mentioning tables' => [$m . "\$msg = 'Remove the member from elections and update voters';\n", 'both'],
            'comment' => [$m . "// DB::table('voters')->update([]);\n/* use App\\Models\\Voter; use App\\Models\\Member; */\n", 'both'],
            'Membership code importing its own model' => [$m . "use App\\Models\\Member;\n", 'membership'],
            'Election code importing its own model' => [$e . "use App\\Models\\Voter;\n", 'election'],
            'method and variable names' => [$m . "class P { public function voters() { \$election = 1; \$member = 2; return \$this->election; } }\n", 'both'],
            'column reference only' => [$e . "\$q->where('members.status', 'active');\n", 'both'],
            'closure use' => [$m . "\$f = function () use (\$voter, \$member) { return \$voter; };\n", 'both'],
        ];
    }

    /** @return array<string, array{string, list<string>}> planted migration source => expected cross-context keys */
    public static function plantedForeignKeys(): array
    {
        $wrap = static fn (string $up): string => "<?php\nreturn new class { public function up(): void { {$up} } public function down(): void {} };\n";

        return [
            'foreign()->on()' => [$wrap("Schema::create('election_probe', function (\$t) { \$t->foreign('member_id')->references('id')->on('members'); });"), ['election_probe(member_id) -> members']],
            'constrained() with a named table argument' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreignUuid('member_ref_id')->constrained(table: 'members'); });"), ['voters(member_ref_id) -> members']],
            'Schema::connection()->table()' => [$wrap("Schema::connection('pgsql')->table('voters', function (\$t) { \$t->foreign('x_id')->references('id')->on('organisation_users'); });"), ['voters(x_id) -> organisation_users']],
            'raw ALTER TABLE ADD FOREIGN KEY, unnamed' => [$wrap("DB::statement('ALTER TABLE voters ADD FOREIGN KEY (organisation_user_id) REFERENCES organisation_users(id)');"), ['voters(organisation_user_id) -> organisation_users']],
            'raw CREATE TABLE ... REFERENCES' => [$wrap("DB::statement('CREATE TABLE election_probe (id bigint, member_id bigint REFERENCES members(id))');"), ['election_probe(member_id) -> members']],
            'added then dropped' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreign('member_id')->references('id')->on('members'); \$t->dropForeign(['member_id']); });"), []],
            'foreignIdFor(Model::class)->constrained()' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreignIdFor(\\App\\Models\\Member::class)->constrained(); });"), ['voters(member_id) -> members']],
            'foreignIdFor with a column name' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreignIdFor(OrganisationUser::class, 'org_user_id')->constrained(); });"), ['voters(org_user_id) -> organisation_users']],
            'foreignIdFor without constrained() adds no key' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreignIdFor(Member::class); });"), []],
            'foreign(column, name)' => [$wrap("Schema::table('voters', function (\$t) { \$t->foreign('member_id', 'voters_member_fk')->references('id')->on('members'); });"), ['voters(member_id) -> members']],
        ];
    }

    /** @return array<string, array{string, string, list<string>}> `$table` property: source, detector, expected targets */
    public static function plantedTableProperties(): array
    {
        return [
            'Membership class declaring an Election table' => ["<?php\nnamespace App\\Contexts\\Membership;\nclass P extends Model { protected \$table = 'voters'; }\n", 'membership', ['table:voters']],
            'Election class declaring a Membership table' => ["<?php\nnamespace App\\Contexts\\Elections;\nclass P extends Model { protected \$table = 'members'; }\n", 'election', ['table:members']],
        ];
    }

    /** @param list<string> $expected */
    #[DataProvider('plantedTableProperties')]
    public function test_table_property_detector(string $source, string $detector, array $expected): void
    {
        $targets = $detector === 'membership' ? $this->membershipToElectionTargets($source) : $this->electionToForeignTargets($source);
        $this->assertSame($expected, array_keys($targets));
    }

    public function test_an_additional_occurrence_of_a_baselined_target_is_reported(): void
    {
        $one = "<?php\nnamespace App\\Models;\nclass M { public function voters() { return \$this->hasMany(Voter::class); } }\n";
        $two = "<?php\nnamespace App\\Models;\nclass M { public function voters() { return \$this->hasMany(Voter::class); }\n  public function purge() { Voter::query()->delete(); } }\n";
        $baseline = ['app/Models/M.php' => $this->membershipToElectionTargets($one)];

        $this->assertSame(['app/Models/M.php' => ['model:Voter' => 1]], $baseline, 'precondition: one occurrence');
        [$increases, $decreases] = $this->baselineDiff(['app/Models/M.php' => $this->membershipToElectionTargets($two)], $baseline);
        $this->assertSame(['app/Models/M.php: model:Voter occurs 2 times, baseline 1'], $increases);
        $this->assertSame([], $decreases);
    }

    public function test_a_removed_occurrence_requires_a_baseline_update(): void
    {
        [$increases, $decreases] = $this->baselineDiff(
            ['app/Models/M.php' => ['model:Voter' => 1]],
            ['app/Models/M.php' => ['model:Voter' => 2, 'table:voters' => 1]],
        );
        $this->assertSame([], $increases);
        $this->assertSame([
            'app/Models/M.php: model:Voter occurs 1 times, baseline 2 — lower the baseline',
            'app/Models/M.php: table:voters no longer present — remove it from the baseline',
        ], $decreases);
    }

    /** @param list<string> $expected */
    #[DataProvider('plantedMembershipViolations')]
    public function test_membership_detector_reports_each_planted_violation(string $source, array $expected): void
    {
        $this->assertSame($expected, array_keys($this->membershipToElectionTargets($source)));
    }

    /** @param list<string> $expected */
    #[DataProvider('plantedElectionReads')]
    public function test_election_detector_reports_each_planted_read(string $source, array $expected): void
    {
        $this->assertSame($expected, array_keys($this->electionToForeignTargets($source)));
    }

    #[DataProvider('permittedForms')]
    public function test_detectors_do_not_report_permitted_forms(string $source, string $detectors): void
    {
        if ($detectors !== 'election') {
            $this->assertSame([], $this->membershipToElectionTargets($source), 'Membership detector');
        }
        if ($detectors !== 'membership') {
            $this->assertSame([], $this->electionToForeignTargets($source), 'Election detector');
        }
    }

    /** @param list<string> $expected */
    #[DataProvider('plantedForeignKeys')]
    public function test_foreign_key_replay_reports_each_planted_key(string $source, array $expected): void
    {
        $this->assertSame($expected, $this->crossContextKeys($this->replayForeignKeys(['2099_01_01_000000_planted.php' => $source])));
    }

    public function test_a_new_target_in_an_already_baselined_file_is_reported(): void
    {
        $baseline = ['app/Models/Member.php' => ['model:Election' => 2, 'model:ElectionMembership' => 1]];
        $found = ['app/Models/Member.php' => ['model:Election' => 2, 'model:ElectionMembership' => 1, 'table:voters' => 1]];

        [$increases, $decreases] = $this->baselineDiff($found, $baseline);
        $this->assertSame(['app/Models/Member.php: table:voters occurs 1 times, not in the baseline'], $increases);
        $this->assertSame([], $decreases);
    }

    public function test_landlord_migrations_are_replayed(): void
    {
        $landlord = glob(dirname(__DIR__, 2) . '/database/migrations/landlord/*.php') ?: [];
        $this->assertNotSame([], $landlord, 'precondition: landlord migrations exist');
        $replayed = array_keys($this->migrationSources());
        foreach ($landlord as $file) {
            $this->assertContains('database/migrations/landlord/' . basename($file), $replayed);
        }
    }

    // ------------------------------------------------------------------ detectors

    /** @return array<string, int> target => occurrences, for Membership code in the Election context */
    private function membershipToElectionTargets(string $source): array
    {
        $view = $this->views($source);
        $counts = [];
        foreach ($this->modelReferences($view, self::ELECTION_MODELS) as $model => $n) {
            $counts['model:' . $model] = $n;
        }
        foreach ($this->tableReferences($view) as $table) {
            if ($this->isElectionTable($table)) {
                $counts['table:' . $table] = ($counts['table:' . $table] ?? 0) + 1;
            }
        }
        $n = preg_match_all('/\\\\Contexts\\\\Elections?\\\\/', $view['code']);
        if ($n > 0) {
            $counts['namespace:Election'] = $n;
        }
        ksort($counts);

        return $counts;
    }

    /** @return array<string, int> target => occurrences, for Election code in Membership- or affiliation-owned data */
    private function electionToForeignTargets(string $source): array
    {
        $view = $this->views($source);
        $counts = [];
        foreach ($this->modelReferences($view, self::FOREIGN_MODELS) as $model => $n) {
            $counts['model:' . $model] = $n;
        }
        foreach ($this->tableReferences($view) as $table) {
            if (in_array($table, self::FOREIGN_TABLES, true)) {
                $counts['table:' . $table] = ($counts['table:' . $table] ?? 0) + 1;
            }
        }
        $n = preg_match_all('/\\\\?App\\\\Contexts\\\\Membership\\\\/', $view['plain']);
        if ($n > 0) {
            $counts['namespace:Membership'] = $n;
        }
        ksort($counts);

        return $counts;
    }

    /**
     * Counts model references: each import (plain, aliased, grouped) counts once; each use of the imported
     * local name, each fully qualified name outside imports and, inside namespace App\Models, each bare
     * class name counts once more.
     *
     * @param array{code: string, plain: string, strings: list<string>, namespace: string} $view
     * @param list<string> $models
     * @return array<string, int> model => occurrences
     */
    private function modelReferences(array $view, array $models): array
    {
        $counts = [];
        $locals = [];
        $plain = $view['plain'];
        $usePattern = '/\buse\s+(?!function\b|const\b)([^;(]+);/';

        preg_match_all($usePattern, $plain, $m);
        foreach ($m[1] as $clause) {
            $clause = trim($clause);
            if (str_contains($clause, '{')) {
                [$prefix, $rest] = explode('{', $clause, 2);
                $prefix = trim(trim($prefix), '\\');
                $items = explode(',', rtrim(trim($rest), '}'));
            } else {
                $prefix = '';
                $items = explode(',', $clause);
            }
            foreach ($items as $item) {
                $parts = preg_split('/\s+as\s+/i', trim($item)) ?: [''];
                $name = trim($parts[0]);
                $fq = ltrim(($prefix !== '' ? $prefix . '\\' : '') . $name, '\\');
                if (preg_match('/^App\\\\Models\\\\(\w+)$/', $fq, $x) && in_array($x[1], $models, true)) {
                    $counts[$x[1]] = ($counts[$x[1]] ?? 0) + 1;
                    $locals[isset($parts[1]) ? trim($parts[1]) : $x[1]] = $x[1];
                }
            }
        }

        // Everything below looks at the code without its import statements.
        $body = (string) preg_replace($usePattern, '', $plain);

        preg_match_all('/\\\\?App\\\\Models\\\\(\w+)\b/', $body, $m);
        foreach ($m[1] as $name) {
            if (in_array($name, $models, true)) {
                $counts[$name] = ($counts[$name] ?? 0) + 1;
            }
        }

        if ($view['namespace'] === 'App\\Models') {
            foreach ($models as $model) {
                $locals[$model] ??= $model;
            }
        }
        $code = (string) preg_replace('/\bfunction\s+\w+/', 'function _', $body);
        foreach ($locals as $local => $model) {
            $n = preg_match_all('/(?<![\w\\\\$])(?<!->)(?<!::)' . preg_quote($local, '/') . '\b/', $code);
            if ($n > 0) {
                $counts[$model] = ($counts[$model] ?? 0) + $n;
            }
        }

        return $counts;
    }

    /**
     * Tables named in query-builder calls, validation rules, `$table` properties and SQL statement strings.
     * A string that is merely equal to a table name (array key, prose) is not a reference.
     *
     * @param array{code: string, plain: string, strings: list<string>, namespace: string} $view
     * @return list<string>
     */
    private function tableReferences(array $view): array
    {
        $tables = [];

        preg_match_all('/(?:->|::)\s*(?:table|from|join|leftJoin|rightJoin|crossJoin|joinWhere|leftJoinWhere|exists|unique)\s*\(\s*([\'"])([^\'"]+)\1/i', $view['code'], $m);
        foreach ($m[2] as $arg) {
            if (preg_match('/^\s*(?:\w+\.)?(\w+)(?:\s+as\s+\w+)?\s*$/i', $arg, $x)) {
                $tables[] = $x[1];
            }
        }

        preg_match_all('/\$table\s*=\s*[\'"](\w+)[\'"]/', $view['code'], $m);
        array_push($tables, ...$m[1]);

        foreach ($view['strings'] as $string) {
            preg_match_all('/\b(?:exists|unique):(\w+)/', $string, $m);
            array_push($tables, ...$m[1]);

            if (preg_match('/^\s*(?:select|insert|update|delete|with|merge)\b/i', $string) || preg_match('/\(\s*select\s/i', $string)) {
                preg_match_all('/\b(?:from|join|into|update)\s+[`"]?(?:\w+[`"]?\.[`"]?)?(\w+)/i', $string, $m);
                array_push($tables, ...$m[1]);
            }
        }

        return array_map('strtolower', $tables);
    }

    /**
     * Three views of a source: code without comments; the same without string contents; the string contents.
     *
     * @return array{code: string, plain: string, strings: list<string>, namespace: string}
     */
    private function views(string $source): array
    {
        $code = '';
        $plain = '';
        $strings = [];
        foreach (token_get_all($source) as $token) {
            if (!is_array($token)) {
                $code .= $token;
                $plain .= $token;
                continue;
            }
            if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                continue;
            }
            $code .= $token[1];
            if ($token[0] === T_CONSTANT_ENCAPSED_STRING) {
                $strings[] = stripcslashes(substr($token[1], 1, -1));
                $plain .= "''";
            } elseif ($token[0] === T_ENCAPSED_AND_WHITESPACE) {
                $strings[] = $token[1];
            } else {
                $plain .= $token[1];
            }
        }
        $namespace = preg_match('/\bnamespace\s+([A-Za-z0-9_\\\\]+)\s*[;{]/', $plain, $m) ? $m[1] : '';

        return ['code' => $code, 'plain' => $plain, 'strings' => $strings, 'namespace' => $namespace];
    }

    // ------------------------------------------------------------------ baseline

    /** @param array<string, array<string, int>> $found @param array<string, array<string, int>> $baseline */
    private function assertAgainstBaseline(array $found, array $baseline, string $rule): void
    {
        [$increases, $decreases] = $this->baselineDiff($found, $baseline);

        $this->assertSame([], $increases, "$rule — violation(s) not covered by the approved baseline (new target or more occurrences):\n" . implode("\n", $increases));
        $this->assertSame([], $decreases, "$rule — fewer violations than the baseline records; update the baseline:\n" . implode("\n", $decreases));
    }

    /**
     * Compares occurrence counts per file and target. An increase (a new target, or more occurrences of a
     * baselined target) is a violation. A decrease requires the baseline to be lowered, so it can only shrink.
     *
     * @param array<string, array<string, int>> $found @param array<string, array<string, int>> $baseline
     * @return array{list<string>, list<string>} [increases, decreases]
     */
    private function baselineDiff(array $found, array $baseline): array
    {
        $increases = [];
        $decreases = [];
        foreach ($found as $file => $targets) {
            foreach ($targets as $target => $n) {
                $allowed = $baseline[$file][$target] ?? 0;
                if ($allowed === 0) {
                    $increases[] = "$file: $target occurs $n times, not in the baseline";
                } elseif ($n > $allowed) {
                    $increases[] = "$file: $target occurs $n times, baseline $allowed";
                } elseif ($n < $allowed) {
                    $decreases[] = "$file: $target occurs $n times, baseline $allowed — lower the baseline";
                }
            }
        }
        foreach ($baseline as $file => $targets) {
            foreach ($targets as $target => $allowed) {
                if (!isset($found[$file][$target])) {
                    $decreases[] = "$file: $target no longer present — remove it from the baseline";
                }
            }
        }
        sort($increases);
        sort($decreases);

        return [$increases, $decreases];
    }

    // ------------------------------------------------------------------ foreign keys

    /**
     * Migration sources in replay order (by file name): the main folder, the landlord folder and the context folders.
     *
     * @return array<string, string> repository-relative path => PHP source
     */
    private function migrationSources(): array
    {
        $root = dirname(__DIR__, 2);
        $files = array_merge(
            glob($root . '/database/migrations/*.php') ?: [],
            glob($root . '/database/migrations/landlord/*.php') ?: [],
            glob($root . '/app/Contexts/*/Infrastructure/Database/Migrations/*/*.php') ?: [],
        );
        usort($files, static fn (string $a, string $b): int => strcmp(basename($a), basename($b)));

        $sources = [];
        foreach ($files as $file) {
            $sources[substr($file, strlen($root) + 1)] = (string) file_get_contents($file);
        }

        return $sources;
    }

    /**
     * @param array<string, array{table: string, columns: list<string>, target: string}> $keys
     * @return list<string> "table(columns) -> target" for keys from an Election table to a Membership/affiliation table
     */
    private function crossContextKeys(array $keys): array
    {
        $found = [];
        foreach ($keys as $key) {
            if ($this->isElectionTable($key['table']) && in_array($key['target'], self::FOREIGN_TABLES, true)) {
                $found[] = sprintf('%s(%s) -> %s', $key['table'], implode(',', $key['columns']), $key['target']);
            }
        }
        sort($found);

        return $found;
    }

    /**
     * Replays the up() part of every migration in the given order and returns the foreign keys left at the end.
     * Approximation (documented in the developer guide): driver branches are treated as executed;
     * a Schema::create/table segment owns the ->foreign() calls that follow it.
     *
     * @param array<string, string> $sources migration file => PHP source, in replay order
     * @return array<string, array{table: string, columns: list<string>, target: string}>
     */
    private function replayForeignKeys(array $sources): array
    {
        $keys = [];
        foreach ($sources as $source) {
            $up = $this->upBody($this->stripComments($source));
            if ($up === '') {
                continue;
            }

            // Schema builder segments (optionally on a named connection).
            $parts = preg_split('/Schema::(?:connection\([^)]*\)\s*->\s*)?(?:create|table)\(\s*[\'"](\w+)[\'"]/', $up, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];
            for ($i = 1; $i + 1 < count($parts); $i += 2) {
                $table = $parts[$i];
                $segment = $parts[$i + 1];

                $events = [];
                // foreign(columns) or foreign(columns, 'constraint_name')
                preg_match_all('/->foreign\(\s*(\[[^\]]*\]|[\'"][^\'"]+[\'"])\s*(?:,\s*[\'"][^\'"]*[\'"]\s*)?\)(?:(?!->foreign\().)*?->on\(\s*[\'"](\w+)[\'"]/s', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $events[] = [$match[0][1], 'add', $this->columns($match[1][0]), $match[2][0]];
                }
                preg_match_all('/foreign(?:Id|Uuid|Ulid)\(\s*[\'"](\w+)[\'"]\s*\)(?:(?!foreign(?:Id|Uuid|Ulid)\().)*?->constrained\(\s*(?:table\s*:\s*)?(?:[\'"](\w+)[\'"])?/s', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $column = $match[1][0];
                    $target = isset($match[2]) && $match[2][0] !== '' ? $match[2][0] : preg_replace('/_id$/', '', $column) . 's';
                    $events[] = [$match[0][1], 'add', [$column], $target];
                }
                // foreignIdFor(Model::class[, 'column'])->constrained([table]): Laravel's conventions for the defaults
                preg_match_all('/foreignIdFor\(\s*\\\\?(?:[\w\\\\]+\\\\)?(\w+)::class\s*(?:,\s*[\'"](\w+)[\'"])?\s*\)(?:(?!foreignIdFor\(|->foreign\().)*?->constrained\(\s*(?:table\s*:\s*)?(?:[\'"](\w+)[\'"])?/s', $segment, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
                foreach ($m as $match) {
                    $snake = $this->snake($match[1][0]);
                    $column = isset($match[2]) && $match[2][0] !== '' ? $match[2][0] : $snake . '_id';
                    $target = isset($match[3]) && $match[3][0] !== '' ? $match[3][0] : $this->plural($snake);
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

            // Raw SQL: ALTER TABLE ... ADD [CONSTRAINT x] FOREIGN KEY ... / DROP ...
            preg_match_all('/ALTER TABLE\s+[`"]?(\w+)[`"]?\s+(ADD\s+(?:CONSTRAINT\s+[`"]?\w+[`"]?\s+)?FOREIGN KEY\s*\(([^)]*)\)\s*REFERENCES\s+[`"]?(\w+)|DROP\s+(?:FOREIGN KEY|CONSTRAINT)\s+(?:IF EXISTS\s+)?[`"]?(\w+))/i', $up, $m, PREG_SET_ORDER);
            foreach ($m as $match) {
                $table = $match[1];
                if (!empty($match[3])) {
                    $cols = $this->columns('[' . $match[3] . ']');
                    $keys[$table . '|' . implode(',', $cols)] = ['table' => $table, 'columns' => $cols, 'target' => $match[4]];
                } elseif (!empty($match[5])) {
                    $this->dropKey($keys, $table, $match[5]);
                }
            }

            // Raw SQL: CREATE TABLE t (... col type REFERENCES x(...) ... | FOREIGN KEY (col) REFERENCES x ...)
            preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?[`"]?(\w+)[`"]?\s*\((.*?)\)\s*;?\s*[\'"]\s*\)/is', $up, $m, PREG_SET_ORDER);
            foreach ($m as $match) {
                $table = $match[1];
                $body = $match[2];
                preg_match_all('/FOREIGN KEY\s*\(([^)]*)\)\s*REFERENCES\s+[`"]?(\w+)/i', $body, $fk, PREG_SET_ORDER);
                foreach ($fk as $f) {
                    $cols = $this->columns('[' . $f[1] . ']');
                    $keys[$table . '|' . implode(',', $cols)] = ['table' => $table, 'columns' => $cols, 'target' => $f[2]];
                }
                preg_match_all('/(?:^|,)\s*[`"]?(\w+)[`"]?\s+[^,]*?\bREFERENCES\s+[`"]?(\w+)/i', $body, $col, PREG_SET_ORDER);
                foreach ($col as $c) {
                    if (strcasecmp($c[1], 'FOREIGN') === 0 || strcasecmp($c[1], 'CONSTRAINT') === 0) {
                        continue;
                    }
                    $keys[$table . '|' . $c[1]] = ['table' => $table, 'columns' => [$c[1]], 'target' => $c[2]];
                }
            }
        }

        return $keys;
    }

    // ------------------------------------------------------------------ helpers

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

    private function sourceOf(string $relativePath): string
    {
        return (string) file_get_contents(dirname(__DIR__, 2) . '/' . $relativePath);
    }

    /** Source code with comments and docblocks removed. */
    private function stripComments(string $source): string
    {
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

    /** "OrganisationUser" => "organisation_user" (Laravel's Str::snake for class basenames). */
    private function snake(string $class): string
    {
        return strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $class));
    }

    /** A sufficient subset of Laravel's English pluralisation for model table names. */
    private function plural(string $word): string
    {
        if (preg_match('/[^aeiou]y$/', $word)) {
            return substr($word, 0, -1) . 'ies';
        }
        if (preg_match('/(s|x|ch|sh)$/', $word)) {
            return $word . 'es';
        }

        return $word . 's';
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
