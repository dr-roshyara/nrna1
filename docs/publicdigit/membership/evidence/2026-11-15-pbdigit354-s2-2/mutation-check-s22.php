<?php
// PBDIGIT-354 S2-2: copy-load mutation run for Suspend and Restore. No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s22.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$member = "$M/Domain/Organisational/Member.php"; $res = "$M/Domain/Organisational/Resolution.php";
$sH = "$M/Application/Organisational/SuspendMembershipHandler.php"; $rH = "$M/Application/Organisational/RestoreMembershipHandler.php";
$interim = "$M/Infrastructure/Organisational/InterimRoleAuthority.php"; $repo = "$M/Infrastructure/Organisational/PostgresMemberRepository.php";
$pg = 'pg:PostgresSuspendRestoreTest';
$mutants = [
    'K1 a lapsed membership can be suspended' => ['unit', $member, [["        if (!\$target->isInForce(\$effectiveAt)) {\n            return Decision::refused(RefusalReason::NotInForce);\n        }\n        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }\n\n        return \$this->accept(new Suspend(", "        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }\n\n        return \$this->accept(new Suspend("]]],
    'K2 an empty restriction reaches the fact' => ['unit', $member, [["        if (!\$effectiveAt->isBefore(\$until)) {\n            return Decision::refused(RefusalReason::EmptyRestriction);\n        }\n", ""]]],
    'K3 a suspension may be future-dated' => ['unit', $member, [["        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }\n\n        return \$this->accept(new Suspend(", "        return \$this->accept(new Suspend("]]],
    'K4 a second live restore is accepted' => ['unit', $member, [["                return Decision::refused(RefusalReason::AlreadyRestored);", "                break;"]]],
    'K5 a restore at or before the start is accepted' => ['unit', $member, [["if (!\$target->effectiveAt()->isBefore(\$effectiveAt) || !\$effectiveAt->isBefore(\$target->until())) {", "if (!\$effectiveAt->isBefore(\$target->until())) {"]]],
    'K6 a restore after the restriction is accepted' => ['unit', $member, [["if (!\$target->effectiveAt()->isBefore(\$effectiveAt) || !\$effectiveAt->isBefore(\$target->until())) {", "if (!\$target->effectiveAt()->isBefore(\$effectiveAt)) {"]]],
    'K7 a restore while not in force is accepted' => ['unit', $member, [["if (\$period === null || !\$period->isInForce(\$effectiveAt)) {", "if (\$period === null) {"]]],
    'K8 an annulled suspension can be restored' => ['unit', $member, [["if (!\$target instanceof Suspend || !Resolution::isLive(\$facts, \$suspension)) {", "if (!\$target instanceof Suspend) {"]]],
    'K9 a restore may be future-dated' => ['unit', $member, [["        if (\$effectiveAt->isAfter(\$decisionDate)) {\n            return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);\n        }\n\n        return \$this->accept(new Restore(", "        return \$this->accept(new Restore("]]],
    'K10 a stored restore need not name a suspension' => ['unit', $member, [["            if (\$fact instanceof Restore) {\n                self::requireEarlier(\$earlier, \$fact->suspension(), \$id, Suspend::class);\n            }\n", ""]]],
    'K11 correcting a suspension is not refused' => ['unit', $member, [["        if (\$targetFact instanceof Suspend || \$targetFact instanceof Restore) {\n            return Decision::refused(RefusalReason::CorrectionOfSuspendOrRestoreNotSupported);\n        }\n", ""]]],
    'R1 the restriction is not clipped to the lifetime' => ['unit', $res, [["        return \$all->intersectedWith(\$lifetime);\n    }\n\n    /**\n     * Stage 7", "        return \$all;\n    }\n\n    /**\n     * Stage 7"]]],
    'R2 a restore is ignored by resolution' => ['unit', $res, [["                    \$until = TimePoint::min(\$fact->until(), \$other->effectiveAt());", "                    \$until = \$fact->until();"]]],
    'R3 a suspension counts for every period' => ['unit', $res, [["if (!\$fact instanceof Suspend || !\$fact->period()->equals(\$period)) {", "if (!\$fact instanceof Suspend) {"]]],
    'A1 suspension asks authority for the wrong act' => ['unit', $sH, [["            MembershipAct::Suspend,", "            MembershipAct::Restore,"]]],
    'A2 restoration asks authority for the wrong act' => ['unit', $rH, [["            MembershipAct::Restore,", "            MembershipAct::Suspend,"]]],
    'A3 a suspension without an end gets a one-day end' => ['unit', $sH, [["\$command->until === null ? TimePoint::infinity() : TimePoint::of(\$command->until),", "\$command->until === null ? TimePoint::of(\$command->effectiveAt + 1) : TimePoint::of(\$command->until),"]]],
    'I1 the interim adapter grants lifecycle acts' => ['unit', $interim, [["MembershipAct::Renew, MembershipAct::Suspend, MembershipAct::Restore => [self::LIFECYCLE_RULE, false],", "MembershipAct::Renew, MembershipAct::Suspend, MembershipAct::Restore => [self::LIFECYCLE_RULE, true],"]]],
    'P1 (db) an open-ended suspension is stored as dated' => [$pg, $repo, [["'restriction_until_kind' => \$fact->until()->isInfinite() ? 'unlimited' : 'until',", "'restriction_until_kind' => 'until',"]]],
    'P2 (db) a restore is stored without its target' => [$pg, $repo, [["return ['kind' => 'restore', 'effective_at' => DateOrdinal::toDate(\$fact->effectiveAt()), 'target_fact_id' => \$fact->suspension()->value()] + \$common + \$none;", "return ['kind' => 'restore', 'effective_at' => DateOrdinal::toDate(\$fact->effectiveAt())] + \$common + \$none;"]]],
    'P3 the stored restriction end is read as the start' => [$pg, $repo, [["                    'until' => DateOrdinal::fromDate(\$f->restriction_until_date),", "                    'until' => DateOrdinal::fromDate(\$f->effective_date)->successor(),"]]],
    'P4 the stored restore date is read a day late' => [$pg, $repo, [["'restore' => new Restore(\$id, FactId::fromString(\$f->target_fact_id), DateOrdinal::fromDate(\$f->effective_date), \$provenance, \$decidedAt),", "'restore' => new Restore(\$id, FactId::fromString(\$f->target_fact_id), DateOrdinal::fromDate(\$f->effective_date)->successor(), \$provenance, \$decidedAt),"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/SuspendRestoreTest.php tests/Unit/Contexts/Membership/Application/Organisational/SuspendRestoreHandlersTest.php tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php';
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
