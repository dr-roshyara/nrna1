<?php
// PBDIGIT-354 S2-4d-Q5 (D-240): copy-load mutation run for 'a pending scheduled resignation takes effect first'. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-q5.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$sr = "$M/Domain/Organisational/ScheduledResignation.php"; $repo = "$M/Infrastructure/Organisational/PostgresMemberRepository.php"; $loop = "$M/Application/Organisational/DecideAndAppend.php";
$prec = 'pg:PostgresScheduledResignationPrecedenceTest'; $race = 'pg:PostgresScheduledResignationRaceTest';
$mutants = [
    'Q1 an End ON the scheduled date is allowed' => ['unit', $sr, [["&& !\$effectiveAt->isBefore(\$this->effectiveDate)\n            ? RefusalReason::ScheduledResignationTakesEffectFirst", "&& \$this->effectiveDate->isBefore(\$effectiveAt)\n            ? RefusalReason::ScheduledResignationTakesEffectFirst"]]],
    'Q2 a final declaration still refuses' => ['unit', $sr, [["return \$this->state === ScheduledResignationState::Scheduled && !\$effectiveAt->isBefore(\$this->effectiveDate)", "return !\$effectiveAt->isBefore(\$this->effectiveDate)"]]],
    'Q3 the append does not check (the rule unenforced)' => [$prec, $repo, [["        if (\$pending !== null) {\n            return AppendResult::refusedByDomain(\$pending);", "        if (false) {\n            return AppendResult::refusedByDomain(\$pending);"]]],
    'Q4 the executor\'s own End is not exempt' => [$prec, $repo, [[" || (\$executing !== null && \$declaration->id()->equals(\$executing))", ""]]],
    'Q5 another period\'s declaration counts' => [$prec, $repo, [["!\$declaration->period()->equals(\$fact->period()) || ", ""]]],
    'Q6 no membership row lock (a scheduling in flight is missed)' => [$race, $repo, [["'SELECT 1 AS locked FROM org_memberships WHERE organisation_id = ? AND id = ? FOR UPDATE'", "'SELECT 1 AS locked FROM org_memberships WHERE organisation_id = ? AND id = ?'"]]],
    'Q7 a record-time refusal is retried as a conflict' => [$prec, $loop, [["AppendOutcome::RefusedByDomain => CommandOutcome::refusedByDomain(\$result->refusal()\n                    ?? throw new \\LogicException('A domain refusal at record time names its reason.')),", "AppendOutcome::RefusedByDomain => null,"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/ScheduledResignationTest.php';
$survivors = 0; $unexpected = 0;
foreach ($mutants as $label => [$mode, $file, $edits]) {
    if (($argv[4] ?? '') !== '' && !str_starts_with($label, $argv[4])) { continue; }
    $dir = "$scratch/mutants/" . substr(md5($label), 0, 8); @mkdir($dir, 0777, true);
    file_put_contents("$dir/label.txt", $label);
    $src = file_get_contents("$root/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { printf("%-58s NOT RUN (site found %d times in %s)\n", $label, substr_count($src, $from), basename($file)); $unexpected++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    file_put_contents("$dir/mutant.php", $src);
    exec('php -l ' . escapeshellarg("$dir/mutant.php") . ' 2>&1', $lint, $lintCode);
    if ($lintCode !== 0) { printf("%-58s NOT RUN (the mutant does not parse)\n", $label); $unexpected++; continue; }
    @unlink("$dir/suite.txt");
    if ($mode === 'unit') {
        file_put_contents("$dir/bootstrap.php", "<?php require '$dir/mutant.php'; require '$root/vendor/autoload.php';\n");
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result $unit > $dir/suite.txt 2>&1", $out, $code);
    } else {
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite MembershipPostgres --filter " . substr($mode, 3) . " >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $suite = (string) @file_get_contents("$dir/suite.txt");
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-40 | sort -u | tr '\\n' ';'"));
    $tests = preg_match('/^Tests: (\d+)|^OK \((\d+) tests/m', $suite, $t) ? ($t[1] ?: $t[2]) : '?';
    $errors = preg_match('/Errors: (\d+)/', $suite, $e) ? (int) $e[1] : 0;
    $firstError = preg_match('/^There (?:was|were) \d+ errors?:\s+1\) [^\n]+\n([^\n]{0,110})/m', $suite, $fe) ? trim($fe[1]) : '';
    $id = explode(' ', $label)[0];
    if ($tests === '?') { printf("%-58s NOT RUN (no test run recorded; exit %d)\n", $label, $code); $unexpected++; continue; }
    if ($code === 0) {
        $class = isset($equivalent[$id]) ? 'SURVIVED (equivalent: ' . $equivalent[$id] . ')' : 'SURVIVED';
        $survivors++; $unexpected += isset($equivalent[$id]) ? 0 : 1;
    } else {
        $class = $errors === 0 ? 'KILLED (assertions)' : "KILLED+ERRORS ({$errors}: {$firstError})";
    }
    printf("%-58s tests:%-4s %s  %s\n", $label, $tests, $class, $failed);
}
echo "survivors: {$survivors}; unexpected (survived without an argued equivalence, or not run): {$unexpected}\n";
exit($unexpected === 0 ? 0 : 1);
