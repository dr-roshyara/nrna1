<?php
// PBDIGIT-345 task 12b: mutation run for the restore drill and the in-place recovery (T-17, D-234) (derived from the G-6c full driver) (no source file is modified: each mutant is a modified COPY of one
// class, loaded before the real one). Modes: "unit" (Geography unit tests, database-free) or "pg:<Filter>" (that test
// class on the isolated cluster). Classification per mutant:
//   KILLED            the run failed by assertion(s) only (Errors: 0)
//   KILLED+ERRORS     the run failed and some failures were errors: the first error class is printed so each can be
//                     classified (e.g. a database constraint refusing as a backstop) — never counted as an assertion kill
//   SURVIVED          the run passed (expected only where an equivalence is argued: listed in $equivalent)
// Usage: php mutation-check-g6c-full.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$AD = 'Domain/ApprovalDefects.php'; $FL = 'Domain/FindingLifecycle.php'; $FC = 'Domain/FindingConfirmation.php'; $PG = 'Domain/PublicationGovernance.php';
$CC = 'Domain/CorrectionCoverage.php'; $INC = 'Application/IdentityNoticeConsumer.php'; $LED = 'Infrastructure/PostgresPublicationLedger.php';
$PFL = 'Infrastructure/PostgresFindingLifecycles.php'; $PL = 'Domain/CldrParentLocales.php'; $DN = 'Domain/CldrDisplayNames.php'; $RV = 'Domain/RevisionValidator.php'; $CR = 'Domain/CldrRelease.php'; $RS = 'Infrastructure/PostgresGeographyReadSource.php'; $PGEO = 'Application/PublishedGeography.php';
$impact = 'pg:IdentityNoticeImpactTest'; $confirm = 'pg:ConfirmPublicationTest'; $correct = 'pg:CorrectionLinkTest'; $history = 'pg:GovernanceHistoryReadTest';
$OT = '../Platform/Application/OperateTechnically.php'; $DR = '../Platform/Infrastructure/PgDumpRestoreDrill.php';
$t = 'pg-platform:RestoreDrillTest';
$mutants = [
    'V1 the drill verdict ignores the identity row' => ['unit', $OT, [["return \$facts->ledgerSchemaPresent && \$facts->identityRowMatches && \$facts->ledgerClockRowPresent", "return \$facts->ledgerSchemaPresent && \$facts->ledgerClockRowPresent"]]],
    'V2 taking out does not make it unroutable' => ['unit', $OT, [["            \$organisation->markMigrationFailed();\n            \$this->registry->save(\$organisation);\n\n            return \$this->conclude(\$record, 'succeeded', null, report: true);", "            return \$this->conclude(\$record, 'succeeded', null, report: true);"]]],
    'V3 the restore operations are not technical' => ['unit', $OT, [["private const TECHNICAL = [self::MIGRATION_REPAIR, self::RESTORE_DRILL, self::TAKE_OUT_FOR_RESTORE];", "private const TECHNICAL = [self::MIGRATION_REPAIR];"]]],
    'V4 no database is not refused' => ['unit', $OT, [["        if (\$operation !== self::MIGRATION_REPAIR && \$organisation->locator() === null) {\n            return \$this->conclude(\$record, 'refused', 'no-database');\n        }\n", ""]]],
    'V5 a failed restore counts as verified' => ['unit', $OT, [["return \$this->conclude(\$record, 'failed', 'restore-failed', report: true);", "return \$this->conclude(\$record, 'succeeded', null, report: true);"]]],
    'V6 the scratch copy is left behind' => [$t, $DR, [["            \$context->connection->statement(\"DROP DATABASE IF EXISTS {\$scratch} WITH (FORCE)\");\n", ""]]],
    'V7 the backup file is left behind' => [$t, $DR, [["            if (is_file(\$dump)) {\n                unlink(\$dump);\n            }\n", ""]]],
    'V8 the drill reads the live database, not the copy' => [$t, $DR, [["return \$this->probe->factsOn(DB::connection(\$scratchConnection), \$id);", "return \$this->probe->factsOn(\$context->connection, \$id);"]]],
];
$equivalent = [];
$survivors = 0; $unexpected = 0;
foreach ($mutants as $label => $spec) {
    [$mode, $file, $edits] = $spec;
    if (($argv[4] ?? '') !== '' && !str_starts_with($label, $argv[4])) { continue; }
    $dir = "$scratch/mutants/" . substr(md5($label), 0, 8); @mkdir($dir, 0777, true);
    file_put_contents("$dir/label.txt", $label);
    $requires = '';
    foreach ([[$file, $edits], ...($spec[3] ?? [])] as $i => [$f, $es]) {
        $src = file_get_contents("$ctx/$f");
        foreach ($es as [$from, $to]) {
            if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND in $f\n"; $unexpected++; continue 3; }
            $src = str_replace($from, $to, $src);
        }
        file_put_contents("$dir/mutant$i.php", $src);
        exec('php -l ' . escapeshellarg("$dir/mutant$i.php") . ' 2>&1', $lint, $lintCode);
        if ($lintCode !== 0) { echo "$label: MUTANT DOES NOT PARSE\n"; $unexpected++; continue 2; }
        $requires .= "require '$dir/mutant$i.php'; ";
    }
    @unlink("$dir/suite.txt");
    if (str_starts_with($mode, 'arch:')) {
        file_put_contents("$dir/bootstrap.php", "<?php {$requires}require '$root/vendor/autoload.php';\n");
        $target = substr($mode, 5);
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result $target > $dir/suite.txt 2>&1", $out, $code);
    } elseif ($mode === 'unit') {
        file_put_contents("$dir/bootstrap.php", "<?php {$requires}require '$root/vendor/autoload.php';\n");
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result tests/Unit/Contexts/Platform > $dir/suite.txt 2>&1", $out, $code);
    } else {
        $suite = str_starts_with($mode, 'pg-platform:') ? 'PlatformPostgres' : 'ReferenceGeographyPostgres'; $filter = substr($mode, strpos($mode, ':') + 1);
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; {$requires}\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite $suite --filter $filter >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $suite = (string) @file_get_contents("$dir/suite.txt");
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-45 | sort -u | tr '\\n' ';'"));
    $tests = preg_match('/^Tests: (\d+)|^OK \((\d+) tests/m', $suite, $t) ? ($t[1] ?: $t[2]) : '?';
    $errors = preg_match('/Errors: (\d+)/', $suite, $e) ? (int) $e[1] : 0;
    $firstError = preg_match('/^There (?:was|were) \d+ errors?:\s+1\) [^\n]+\n([^\n:]+)/m', $suite, $fe) ? trim($fe[1]) : '';
    $id = explode(' ', $label)[0] . (str_contains($label, '(db)') ? ' (db)' : '');
    if ($tests === '?') {
        printf("%-62s %-10s INCONCLUSIVE (no test run recorded; exit %d)\n", $label, "[$mode]", $code);
        $unexpected++;
        continue;
    }
    if ($code === 0) {
        $class = isset($equivalent[$id]) ? 'SURVIVED (equivalent: ' . $equivalent[$id] . ')' : 'SURVIVED';
        $survivors++; $unexpected += isset($equivalent[$id]) ? 0 : 1;
    } else {
        $class = $errors === 0 ? 'KILLED' : "KILLED+ERRORS ({$errors}: {$firstError})";
    }
    printf("%-62s %-10s tests:%-4s %s  %s\n", $label, "[$mode]", $tests, $class, $failed);
}
echo "survivors: {$survivors}; unexpected (not argued equivalent, site missing or unparsable): {$unexpected}\n";
exit($unexpected === 0 ? 0 : 1);
