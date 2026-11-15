<?php
// PBDIGIT-354 S2-3b: copy-load mutation run for resign now and record a death. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s23b.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$res = "$M/Application/Organisational/ResignMembershipHandler.php"; $dth = "$M/Application/Organisational/RecordDeathHandler.php";
$endH = "$M/Application/Organisational/EndMembershipHandler.php"; $req = "$M/Application/Organisational/Ports/AuthorityRequest.php";
$json = "$M/Infrastructure/Organisational/ProvenanceJson.php"; $pg = 'pg:PostgresResignAndDeathTest';
$mutants = [
    'M1 a resignation is backdated a day' => ['unit', $res, [["return \$member->decideEnd(FactId::fromString(\$command->factId), \$period, \$on, \$on,", "return \$member->decideEnd(FactId::fromString(\$command->factId), \$period, TimePoint::of(\$command->declarationDate - 1), \$on,"]]],
    'M2 resigning with no current period is not refused' => ['unit', $res, [["                if (\$period === null) {\n                    return Decision::refused(RefusalReason::PeriodNotFound);   // before admission, or already ended\n                }\n", ""]]],
    'M3 a resignation declares no effective date' => ['unit', $res, [["\$member?->resolve()->currentPeriod(\$on), \$on, \$on, \$command->idempotencyKey),", "\$member?->resolve()->currentPeriod(\$on), \$on, null, \$command->idempotencyKey),"]]],
    'M4 a resignation names no subject' => ['unit', $res, [["new AuthorityRequest(MembershipAct::RecordResignation, \$command->actor, \$member?->holder(),", "new AuthorityRequest(MembershipAct::RecordResignation, \$command->actor, null,"]]],
    'M5 a blank evidence reference passes the boundary' => ['unit', $dth, [["\$evidence = \$command->evidenceRef === null ? null : trim(\$command->evidenceRef);", "\$evidence = \$command->evidenceRef;"]]],
    'M6 a death is evaluated for the date of death' => ['unit', $dth, [["\$member?->resolve()->currentPeriod(\$died),\n                    \$recorded, null,", "\$member?->resolve()->currentPeriod(\$died),\n                    \$died, null,"]]],
    'M7 the death evidence is not kept on the fact' => ['unit', $dth, [["Provenance::decision(\$command->actor, \$authority, \$evidence)", "Provenance::decision(\$command->actor, \$authority)"]]],
    'M8 a death may be recorded before it happened' => ['unit', $dth, [["return \$member->decideEnd(FactId::fromString(\$command->factId), \$period, \$died, \$recorded,", "return \$member->decideEnd(FactId::fromString(\$command->factId), \$period, \$died, \$died,"]]],
    'M9 EndMembership records a death without evidence' => ['unit', $endH, [["\$kind->value() === EndKind::DEATH => RefusalReason::DeathEvidenceRequired,", "\$kind->value() === EndKind::DEATH => new AuthorityRequest(MembershipAct::RecordDeath, \$command->actor, \$member?->holder(), PeriodId::fromString(\$command->periodId), TimePoint::of(\$command->decisionDate), null, \$command->idempotencyKey, 'unstated'),"]]],
    'M10 any act may carry an evidence reference' => ['unit', $req, [["        if (\$act !== MembershipAct::RecordDeath && \$evidenceRef !== null) {", "        if (false) {"]]],
    'M11 a death request needs no evidence' => ['unit', $req, [["        if (\$act === MembershipAct::RecordDeath && (\$evidenceRef === null || trim(\$evidenceRef) === '')) {", "        if (false) {"]]],
    'P1 (db) the declaration reference is not stored' => [$pg, $json, [["'declarationRef' => \$p->declarationRef(),", "'declarationRef' => null,"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Application/Organisational/ResignAndRecordDeathTest.php tests/Unit/Contexts/Membership/Application/Organisational/AuthorityContractTest.php';
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
