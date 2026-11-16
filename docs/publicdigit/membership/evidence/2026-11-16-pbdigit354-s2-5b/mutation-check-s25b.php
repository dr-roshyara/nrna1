<?php
// PBDIGIT-354 S2-5b: copy-load mutation run for D-237 (Renew until), row 14 (Restore date), D-241 (no Suspend correction). No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s25b.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$D = "$M/Domain/Organisational"; $member = "$D/Member.php"; $res = "$D/Resolution.php"; $repo = "$M/Infrastructure/Organisational/PostgresMemberRepository.php";
$pg = 'pg:PostgresCorrectRenewAndRestoreTest';
$mutants = [
    'N1 a corrected until may equal the renewal start' => ['unit', $member, [["return \$root->effectiveAt()->isBefore(\$value)\n                ? \$this->accept(", "return !\$value->isBefore(\$root->effectiveAt())\n                ? \$this->accept("]]],
    'N2 a corrected until is bound by the decision date (D-237 (2) ignored)' => ['unit', $member, [["return \$root->effectiveAt()->isBefore(\$value)\n                ? \$this->accept(", "return \$root->effectiveAt()->isBefore(\$value) && !\$value->isAfter(\$decisionDate)\n                ? \$this->accept("]]],
    'N3 the term ignores a corrected until' => ['unit', $res, [["IntervalSet::of(Interval::of(\$fact->effectiveAt(), self::value(\$live, \$fact)))", "IntervalSet::of(Interval::of(\$fact->effectiveAt(), \$fact->until()))"]]],
    'N4 the restriction ignores a corrected restore date' => ['unit', $res, [["TimePoint::min(\$fact->until(), self::value(\$live, \$other))", "TimePoint::min(\$fact->until(), \$other->effectiveAt())"]]],
    'N5 a Suspend may be corrected (D-241 ignored)' => ['unit', $member, [["        if (\$root instanceof Suspend) {\n            return Decision::refused(RefusalReason::CorrectionOfSuspendNotPermitted);\n        }\n", ""]]],
    'N6 a corrected restore may be after the decision date' => ['unit', $member, [["return \$date->isAfter(\$decisionDate) ? RefusalReason::EffectiveDateAfterDecisionDate : null;", "return null;"]]],
    'N7 a corrected restore may leave the restriction' => ['unit', $member, [["        if (!\$suspension->effectiveAt()->isBefore(\$date) || !\$date->isBefore(\$suspension->until())) {\n            return RefusalReason::OutsideRestriction;\n        }\n", ""]]],
    'N8 a restore of a voided suspension may be corrected' => ['unit', $member, [["if (!\$suspension instanceof Suspend || !Resolution::isLive(\$facts, \$suspension->id())) {", "if (!\$suspension instanceof Suspend) {"]]],
    'N9 any chain may be corrected to open-ended' => ['unit', $member, [["(\$valueTarget === null || !Resolution::rootOf(\$this->facts(), \$target) instanceof Renew)", "false"]]],
    'N10 a stored open-ended correction of a non-renewal loads' => ['unit', $member, [["                if (\$fact->value()->isInfinite() && !\$root instanceof Renew) {\n", "                if (false) {\n"]]],
    'P1 an open-ended value is stored without its flag' => [$pg, $repo, [["'correct_value_unlimited' => \$unlimited,", "'correct_value_unlimited' => false,"]]],
    'P2 the flag is ignored on load' => [$pg, $repo, [["\$f->correct_value_unlimited ? TimePoint::infinity() : DateOrdinal::fromDate(\$f->effective_date)", "DateOrdinal::fromDate(\$f->effective_date)"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/CorrectRenewAndRestoreTest.php tests/Unit/Contexts/Membership/Domain/Organisational/CorrectDecisionTest.php tests/Unit/Contexts/Membership/Domain/Organisational/RenewTest.php tests/Unit/Contexts/Membership/Domain/Organisational/SuspendRestoreTest.php';
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
