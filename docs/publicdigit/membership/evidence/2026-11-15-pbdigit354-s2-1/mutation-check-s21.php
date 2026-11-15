<?php
// PBDIGIT-354 S2-1: copy-load mutation run for Renew and the admission-term rule. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s21.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$member = "$M/Domain/Organisational/Member.php"; $res = "$M/Domain/Organisational/Resolution.php";
$admitH = "$M/Application/Organisational/AdmitMemberHandler.php"; $renewH = "$M/Application/Organisational/RenewMembershipHandler.php";
$svt = "$M/Application/Organisational/Ports/StorableValidTime.php"; $repo = "$M/Infrastructure/Organisational/PostgresMemberRepository.php";
$pg = 'pg:PostgresRenewTest';
$mutants = [
    'K1 a renewal after the period end is accepted' => ['unit', $member, [["        if (!\$effectiveAt->isBefore(\$target->baseClaim()->until())) {\n            return Decision::refused(RefusalReason::PeriodEnded);\n        }\n", ""]]],
    'K2 any period is taken, even one not yet started' => ['unit', $member, [["if (!\$start->isAfter(\$effectiveAt) && (\$target === null", "if ((\$target === null"]]],
    'K3 the earliest-started period is taken' => ['unit', $member, [["|| \$start->isAfter(\$target->baseClaim()->start()))) {", "|| \$start->isBefore(\$target->baseClaim()->start()))) {"]]],
    'K4 a renewal may be future-dated (D-205)' => ['unit', $member, [["        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }\n\n        return \$this->accept(new Renew(", "        return \$this->accept(new Renew("]]],
    'K5 an empty segment reaches the fact' => ['unit', $member, [["        if (!\$effectiveAt->isBefore(\$until)) {\n            return Decision::refused(RefusalReason::EmptySegment);\n        }\n", ""]]],
    'K6 a stored renewal need not name an admission' => ['unit', $member, [["if (\$fact instanceof End || \$fact instanceof Renew) {", "if (\$fact instanceof End) {"]]],
    'K7 correcting a renewal is not refused' => ['unit', $member, [["        if (\$targetFact instanceof Renew) {\n            return Decision::refused(RefusalReason::CorrectionOfRenewNotSupported);\n        }\n", ""]]],
    'K8 a duplicate fact id is accepted for a renewal' => ['unit', $member, [["     */\n    public function decideRenew(FactId \$id, TimePoint \$effectiveAt, TimePoint \$decisionDate, TimePoint \$until, Provenance \$provenance, ?Instant \$decidedAt = null): Decision\n    {\n        self::requireFiniteDates(\$effectiveAt, \$decisionDate);\n        if (\$this->usesFactId(\$id)) {\n            return Decision::refused(RefusalReason::DuplicateFactId);\n        }\n", "     */\n    public function decideRenew(FactId \$id, TimePoint \$effectiveAt, TimePoint \$decisionDate, TimePoint \$until, Provenance \$provenance, ?Instant \$decidedAt = null): Decision\n    {\n        self::requireFiniteDates(\$effectiveAt, \$decisionDate);\n"]]],
    'R1 the renewed term is not clipped to the lifetime' => ['unit', $res, [["        return \$segments->intersectedWith(\$lifetime);", "        return \$segments;"]]],
    'R2 a renewal counts for every period' => ['unit', $res, [["if (\$fact instanceof Renew && \$fact->period()->equals(\$admit->periodId())) {", "if (\$fact instanceof Renew) {"]]],
    'R3 renewals are ignored' => ['unit', $res, [["                \$segments = \$segments->union(IntervalSet::of(Interval::of(\$fact->effectiveAt(), \$fact->until())));", "                \$segments = \$segments;"]]],
    'A1 an admission without a term is accepted' => ['unit', $admitH, [["                if (\$until === null) {\n                    return Decision::refused(RefusalReason::AdmissionTermRequired);\n                }\n", ""]]],
    'A2 a refused admission still creates the member record' => ['unit', $admitH, [["            fn (): Member|ApplicationRefusal => \$until === null\n                ? (\$this->repository->findByHolder(\$tenant, \$person) ?? Member::fromLog(MemberId::fromString(\$command->memberIdIfNew), \$person, \$tenant))\n                : \$this->memberOf(\$tenant, \$person, MemberId::fromString(\$command->memberIdIfNew)),", "            fn (): Member|ApplicationRefusal => \$this->memberOf(\$tenant, \$person, MemberId::fromString(\$command->memberIdIfNew)),"]]],
    'A3 authority is asked for the wrong act' => ['unit', $renewH, [["            MembershipAct::Renew,", "            MembershipAct::End,"]]],
    'A4 a renewal without a term is read as unlimited' => ['unit', $renewH, [["                if (!\$command->unlimited && \$command->until === null) {\n                    return Decision::refused(RefusalReason::RenewalTermRequired);\n                }\n", ""], ["\$command->unlimited ? TimePoint::infinity() : TimePoint::of(\$command->until),", "\$command->unlimited || \$command->until === null ? TimePoint::infinity() : TimePoint::of(\$command->until),"]]],
    'A5 the renewal end date is not range-checked' => ['unit', $svt, [["            \$points[] = \$fact->until();\n", ""]]],
    'A6 the renewal start date is not range-checked' => ['unit', $svt, [["            \$points[] = \$fact->effectiveAt();\n            \$points[] = \$fact->until();", "            \$points[] = \$fact->until();"]]],
    'P1 (db) an open-ended renewal is stored as a dated one' => [$pg, $repo, [["'renew_until_kind' => \$fact->until()->isInfinite() ? 'unlimited' : 'until',", "'renew_until_kind' => 'until',"]]],
    'P2 the renewal is stored against no period (db)' => [$pg, $repo, [["return ['kind' => 'renew', 'effective_at' => DateOrdinal::toDate(\$fact->effectiveAt()), 'target_fact_id' => \$fact->period()->value(),", "return ['kind' => 'renew', 'effective_at' => DateOrdinal::toDate(\$fact->effectiveAt()),"]]],
    'P3 the stored end date is read as the start date' => [$pg, $repo, [["                    'until' => DateOrdinal::fromDate(\$f->renew_until_date),\n                },\n                \$provenance,\n                \$decidedAt,\n            ),\n            'annul'", "                    'until' => DateOrdinal::fromDate(\$f->effective_date)->successor(),\n                },\n                \$provenance,\n                \$decidedAt,\n            ),\n            'annul'"]]],
    'P4 an open-ended renewal is read back as dated' => [$pg, $repo, [["                    'unlimited' => TimePoint::infinity(),\n                    'until' => DateOrdinal::fromDate(\$f->renew_until_date),", "                    'unlimited' => DateOrdinal::fromDate(\$f->effective_date)->successor(),\n                    'until' => DateOrdinal::fromDate(\$f->renew_until_date),"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/RenewTest.php tests/Unit/Contexts/Membership/Application/Organisational/RenewMembershipHandlerTest.php tests/Unit/Contexts/Membership/Application/Organisational/ReferenceModelPropertyTest.php';
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
