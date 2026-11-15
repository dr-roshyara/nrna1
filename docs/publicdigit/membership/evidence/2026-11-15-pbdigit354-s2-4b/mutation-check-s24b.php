<?php
// PBDIGIT-354 S2-4b: copy-load mutation run for scheduling, withdrawal and the declaration store. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s24b.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$sr = "$M/Domain/Organisational/ScheduledResignation.php";
$A = "$M/Application/Organisational"; $loop = "$A/DecideAndRecordDeclaration.php"; $sch = "$A/ScheduleResignationHandler.php"; $wd = "$A/WithdrawScheduledResignationHandler.php";
$req = "$A/Ports/AuthorityRequest.php"; $pg = "$M/Infrastructure/Organisational/PostgresScheduledResignationStore.php";
$pgf = 'pg:PostgresScheduledResignationTest';
$mutants = [
    'W1 a stored withdrawal on its date is accepted' => ['unit', $sr, [["\$withdrawnBy !== null && \$withdrawnOn->isBefore(\$effectiveDate) &&", "\$withdrawnBy !== null &&"]]],
    'W2 a stored scheduled declaration may carry a withdrawal' => ['unit', $sr, [["ScheduledResignationState::Scheduled => \$withdrawnOn === null && \$withdrawnBy === null && ", "ScheduledResignationState::Scheduled => "]]],
    'A1 the same day reaches the authority' => ['unit', $sch, [["static fn (?Member \$member): AuthorityRequest|RefusalReason => \$on->isBefore(\$from)", "static fn (?Member \$member): AuthorityRequest|RefusalReason => true"]]],
    'A2 the withdraw shape loses the declaration reference' => ['unit', $wd, [["\$command->idempotencyKey, null, \$command->declarationId),", "\$command->idempotencyKey, null, null),"]]],
    'A3 the answer is not checked' => ['unit', $loop, [["            if (\$refusal !== null) {\n                return DeclarationOutcome::refused(\$refusal);", "            if (false) {\n                return DeclarationOutcome::refused(\$refusal);"]]],
    'A4 ownAct and undetermined read as a plain yes / no' => ['unit', $loop, [["\$refusal = DecideAndAppend::authorisationRefusal(\$asked, \$authorisation);", "\$refusal = \$authorisation->isAuthorised() ? null : ApplicationRefusal::NotAuthorised;"]]],
    'A5 member not found told before the answer (leak)' => ['unit', $loop, [["            \$authorisation = \$this->authority->check(\$tenant, \$asked);", "            if (\$member === null) { return DeclarationOutcome::refused(ApplicationRefusal::MemberNotFound); }\n            \$authorisation = \$this->authority->check(\$tenant, \$asked);"]]],
    'A6 a conflict is not decided again' => ['unit', $loop, [["DeclarationWriteOutcome::Conflict => null,", "DeclarationWriteOutcome::Conflict => DeclarationOutcome::refused(ApplicationRefusal::ConcurrentModification),"]]],
    'A7 no idempotency pre-check' => ['unit', $loop, [["        \$seen = \$this->answerFromKey(\$tenant, \$key, \$fingerprint);\n        if (\$seen !== null) {\n            return \$seen;\n        }\n", ""]]],
    'A8 the declaration is recorded on a stale version' => ['unit', $sch, [["\$member->version(), \$scheduled,", "\$member->version() - 1, \$scheduled,"]]],
    'A9 the withdrawal does not record who withdrew' => ['unit', $wd, [["\$declaration->withdraw(\$on, Provenance::decision(\$command->actor, \$evidence))", "\$declaration->withdraw(\$on, Provenance::decision('system', \$evidence))"]]],
    'R1 the withdraw shape accepts an effective date' => ['unit', $req, [["\$act !== MembershipAct::RecordResignation || \$declaredEffectiveAt !== null || trim(\$declarationRef) === ''", "\$act !== MembershipAct::RecordResignation || trim(\$declarationRef) === ''"]]],
    'R2 any act may name a declaration' => ['unit', $req, [["\$act !== MembershipAct::RecordResignation || \$declaredEffectiveAt !== null || trim(\$declarationRef) === ''", "\$declaredEffectiveAt !== null || trim(\$declarationRef) === ''"]]],
    'P1 the transition has no compare-and-set' => [$pgf, $pg, [["WHERE organisation_id = ? AND membership_id = ? AND declaration_id = ? AND state = 'scheduled'\"", "WHERE organisation_id = ? AND membership_id = ? AND declaration_id = ?\""]]],
    'P2 record ignores the membership version' => [$pgf, $pg, [["if ((int) \$current->version !== \$basedOnVersion) {", "if (false) {"]]],
    'P3 a second pending declaration is not a conflict' => [$pgf, $pg, [[" || PgUniqueViolation::isConstraint(\$e, self::TABLE . '_one_pending')", ""]]],
    'P4 a withdrawal is stored without who withdrew' => [$pgf, $pg, [["\$changed->withdrawnBy() === null ? null : ProvenanceJson::encode(\$changed->withdrawnBy()),", "null,"]]],
    'P5 record writes no key row' => [$pgf, $pg, [["            \$this->registerKey(\$tenant, \$member, \$scheduled->id(), \$key, \$fingerprint);\n", ""]]],
    'P6 reload drops who withdrew' => [$pgf, $pg, [["\$r->withdrawn_by === null ? null : ProvenanceJson::decode(\$r->withdrawn_by),", "null,"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/ScheduledResignationTest.php tests/Unit/Contexts/Membership/Application/Organisational/ScheduleAndWithdrawResignationTest.php';
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
