<?php
// PBDIGIT-354 S2-4a: copy-load mutation run for the scheduled resignation declaration and D-205's exception. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s24a.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$sr = "$M/Domain/Organisational/ScheduledResignation.php"; $member = "$M/Domain/Organisational/Member.php";
$mutants = [
    'S1 the scheduled date may equal the declaration' => ['unit', $sr, [["        if (!\$declarationDate->isBefore(\$effectiveDate)) {", "        if (\$effectiveDate->isBefore(\$declarationDate)) {"]]],
    'S2 two pending declarations per period' => ['unit', $sr, [["            if (\$other->period->equals(\$periodId) && \$other->state === ScheduledResignationState::Scheduled) {", "            if (false) {"]]],
    'S3 a withdrawn declaration still counts as pending' => ['unit', $sr, [["            if (\$other->period->equals(\$periodId) && \$other->state === ScheduledResignationState::Scheduled) {", "            if (\$other->period->equals(\$periodId)) {"]]],
    'S4 a duplicate declaration id is accepted' => ['unit', $sr, [["            if (\$other->id->equals(\$id)) {\n                return RefusalReason::DuplicateDeclarationId;\n            }\n", ""]]],
    'S5 a period ending before the date is accepted' => ['unit', $sr, [["        if (!\$effectiveDate->isBefore(\$member->period(\$periodId)->baseClaim()->until())) {", "        if (false) {"]]],
    'S6 withdrawal on the effective date is accepted' => ['unit', $sr, [["        if (!\$on->isBefore(\$this->effectiveDate)) {\n            return RefusalReason::WithdrawalAfterEffectiveDate;", "        if (\$this->effectiveDate->isBefore(\$on)) {\n            return RefusalReason::WithdrawalAfterEffectiveDate;"]]],
    'S7 a withdrawn declaration can be withdrawn again' => ['unit', $sr, [["    public function withdraw(TimePoint \$on): self|RefusalReason\n    {\n        if (\$this->state !== ScheduledResignationState::Scheduled) {\n            return RefusalReason::DeclarationNotScheduled;\n        }\n", "    public function withdraw(TimePoint \$on): self|RefusalReason\n    {\n"]]],
    'S8 a final declaration can be superseded' => ['unit', $sr, [["    public function supersede(string \$because): self|RefusalReason\n    {\n        if (\$this->state !== ScheduledResignationState::Scheduled) {\n            return RefusalReason::DeclarationNotScheduled;\n        }\n", "    public function supersede(string \$because): self|RefusalReason\n    {\n"]]],
    'K1 execution before the date is accepted (D-223)' => ['unit', $member, [["        if (\$recordingDate->isBefore(\$declaration->effectiveDate())) {\n            return Decision::refused(RefusalReason::ExecutionBeforeEffectiveDate);\n        }\n", ""]]],
    'K2 a non-scheduled declaration is executed' => ['unit', $member, [["        if (\$declaration->state() !== ScheduledResignationState::Scheduled) {\n            return Decision::refused(RefusalReason::DeclarationNotScheduled);\n        }\n        if (\$recordingDate", "        if (\$recordingDate"]]],
    'K3 execution ignores an ended period' => ['unit', $member, [["            return Decision::refused(RefusalReason::PeriodAlreadyEnded);   // the declaration is to be superseded (MI-3)", "            // mutated"]]],
    'K4 the End takes effect on the recording date' => ['unit', $member, [["\$declaration->effectiveDate(), EndKind::fromString(EndKind::RESIGNATION),", "\$recordingDate, EndKind::fromString(EndKind::RESIGNATION),"]]],
    'K5 the End loses its declaration reference' => ['unit', $member, [["Provenance::decision(\$stored->actorOrSource(), \$stored->authorityEvidence(), \$declaration->id()->value())", "Provenance::decision(\$stored->actorOrSource(), \$stored->authorityEvidence())"]]],
    'K6 the End is decided at execution, not at the declaration' => ['unit', $member, [["\$provenance, \$declaration->decidedAt() ?? \$recordedDecidedAt));", "\$provenance, \$recordedDecidedAt));"]]],
    'K7 an ordinary End may be future-dated (the exception leaks)' => ['unit', $member, [["        if (\$kind->isLegacy() && \$provenance->kind() !== ProvenanceKind::Attested) {\n            return Decision::refused(RefusalReason::LegacyEndRequiresAttestedHistory);\n        }\n        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }", "        if (\$kind->isLegacy() && \$provenance->kind() !== ProvenanceKind::Attested) {\n            return Decision::refused(RefusalReason::LegacyEndRequiresAttestedHistory);\n        }"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/ScheduledResignationTest.php tests/Unit/Contexts/Membership/Domain/Organisational/EndTest.php';
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
