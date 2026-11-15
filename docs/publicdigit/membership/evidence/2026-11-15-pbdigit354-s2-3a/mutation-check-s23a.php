<?php
// PBDIGIT-354 S2-3a: copy-load mutation run for the authority contract (D-238). No source file is modified: each
// mutant is a modified COPY of one class, required before the autoloader so PHP uses it instead of the real file.
// Modes: "unit" (the S2-1 database-free tests) or "pg:<Filter>" (that class in the MembershipPostgres suite, cluster).
// Classification per mutant (never only a total):
//   KILLED (assertions)   the run failed by assertions only (Errors: 0)
//   KILLED+ERRORS         some failures were errors; the first is printed so it can be classified by hand
//                         (database refusal, or a test error such as a TypeError): never counted as an assertion kill
//   SURVIVED              the run passed: a test is missing, or an equivalence is argued in $equivalent
//   NOT RUN               the site was not found exactly once, or the mutant does not parse: a driver defect
// Limits: mutants do not reach child processes, static scanners or migration files (migrations are loaded by path).
// Usage: php mutation-check-s23a.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$M = 'app/Contexts/Membership';
$loop = "$M/Application/Organisational/DecideAndAppend.php"; $admit = "$M/Application/Organisational/AdmitMemberHandler.php";
$endH = "$M/Application/Organisational/EndMembershipHandler.php"; $renew = "$M/Application/Organisational/RenewMembershipHandler.php";
$susp = "$M/Application/Organisational/SuspendMembershipHandler.php"; $dec = "$M/Application/Organisational/Ports/AuthorityDecision.php";
$ev = "$M/Domain/Organisational/AuthorityEvidence.php"; $basis = "$M/Domain/Organisational/AuthorityBasis.php";
$json = "$M/Infrastructure/Organisational/ProvenanceJson.php"; $interim = "$M/Infrastructure/Organisational/InterimRoleAuthority.php";
$mutants = [
    'D1 the ownAct check is skipped' => ['unit', $loop, [["AuthorityOutcome::Authorised => self::ownActMisapplied(\$asked, \$authorisation) ? ApplicationRefusal::OwnActNotApplicable : null,", "AuthorityOutcome::Authorised => null,"]]],
    'D2 ownAct counts for any act' => ['unit', $loop, [["        return \$request->act !== MembershipAct::RecordResignation\n            || \$request->subject === null", "        return \$request->subject === null"]]],
    'D3 ownAct counts for any actor' => ['unit', $loop, [["            || \$request->subject === null\n            || \$request->subject->value() !== \$request->actor;", "            || \$request->subject === null;"]]],
    'D4 Undetermined reported as NotAuthorised' => ['unit', $loop, [["AuthorityOutcome::Undetermined => ApplicationRefusal::AuthorityUndetermined,", "AuthorityOutcome::Undetermined => ApplicationRefusal::NotAuthorised,"]]],
    'D5 Undetermined treated as authorised' => ['unit', $loop, [["AuthorityOutcome::Undetermined => ApplicationRefusal::AuthorityUndetermined,", "AuthorityOutcome::Undetermined => null,"]]],
    'D6 member-not-found before authority (leak)' => ['unit', $loop, [["            \$asked = \$request(\$member);", "            if (\$member === null && \$create === null) {\n                return CommandOutcome::refused(ApplicationRefusal::MemberNotFound);\n            }\n            \$asked = \$request(\$member);"]]],
    'D7 the member is created before authorisation' => ['unit', $admit, [["            fn (): ?Member => \$this->repository->findByHolder(\$tenant, \$person),", "            fn (): ?Member => \$this->repository->findByHolder(\$tenant, \$person) ?? \$this->repository->create(\$tenant, MemberId::fromString(\$command->memberIdIfNew), \$person)->member(),"]]],
    'E1 a pull exclusion is asked as a death' => ['unit', $endH, [["\$kind->value() === EndKind::EXCLUSION => RefusalReason::ExclusionIsPushOnly,", "\$kind->value() === EndKind::EXCLUSION => new AuthorityRequest(MembershipAct::RecordDeath, \$command->actor, \$member?->holder(), PeriodId::fromString(\$command->periodId), TimePoint::of(\$command->decisionDate), null, \$command->idempotencyKey),"]]],
    'E2 a death asks record-resignation' => ['unit', $endH, [["\$kind->value() === EndKind::DEATH => new AuthorityRequest(MembershipAct::RecordDeath,", "\$kind->value() === EndKind::DEATH => new AuthorityRequest(MembershipAct::RecordResignation,"]]],
    'E3 a resignation declares no effective date' => ['unit', $endH, [["PeriodId::fromString(\$command->periodId), TimePoint::of(\$command->decisionDate), TimePoint::of(\$command->effectiveAt), \$command->idempotencyKey),", "PeriodId::fromString(\$command->periodId), TimePoint::of(\$command->decisionDate), null, \$command->idempotencyKey),"]]],
    'E4 legacy is asked before refusal' => ['unit', $endH, [["\$kind->value() === EndKind::LEGACY => RefusalReason::LegacyEndRequiresAttestedHistory,", "\$kind->value() === EndKind::LEGACY => new AuthorityRequest(MembershipAct::RecordDeath, \$command->actor, \$member?->holder(), PeriodId::fromString(\$command->periodId), TimePoint::of(\$command->decisionDate), null, \$command->idempotencyKey),"]]],
    'R1 the request names no subject' => ['unit', $renew, [["new AuthorityRequest(MembershipAct::Renew, \$command->actor, \$member?->holder(), null,", "new AuthorityRequest(MembershipAct::Renew, \$command->actor, null, null,"]]],
    'R2 the request is evaluated for the effective date' => ['unit', $renew, [["MembershipAct::Renew, \$command->actor, \$member?->holder(), null, TimePoint::of(\$command->decisionDate),", "MembershipAct::Renew, \$command->actor, \$member?->holder(), null, TimePoint::of(\$command->effectiveAt),"]]],
    'R3 the request id is not the key' => ['unit', $susp, [["TimePoint::of(\$command->decisionDate), null, \$command->idempotencyKey),", "TimePoint::of(\$command->decisionDate), null, 'x'),"]]],
    'V1 the outcome ignores the basis' => ['unit', $dec, [["        return \$this->evidence->basis()?->outcome()\n            ?? (", "        return ("]]],
    'V2 result and basis may disagree' => ['unit', $ev, [["        if (\$basis !== null && \$result !== (\$basis->outcome() === AuthorityOutcome::Authorised)) {", "        if (false) {"]]],
    'V3 an authorisation may carry a reason' => ['unit', $basis, [["        if (\$outcome === AuthorityOutcome::Authorised && \$reason !== null) {", "        if (false) {"]]],
    'J1 the basis is not stored' => ['unit', $json, [["] + (\$e->basis() === null ? [] : ['basis' => self::encodeBasis(\$e->basis())]),", "],"]]],
    'J2 a damaged competence kind is tolerated' => ['unit', $json, [["        if (\$kind !== null && (!is_string(\$kind) || CompetenceKind::tryFrom(\$kind) === null)) {", "        if (false) {"]]],
    'I1 the interim adapter calls its check competent' => ['unit', $interim, [["CompetenceKind::InterimRole, null, [])", "CompetenceKind::WorkedOut, null, [])"]]],
];
$equivalent = [];
$unit = 'tests/Unit/Contexts/Membership/Domain/Organisational/AuthorityBasisTest.php tests/Unit/Contexts/Membership/Application/Organisational tests/Unit/Contexts/Membership/Infrastructure/Organisational/ProvenanceJsonTest.php tests/Unit/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthorityTest.php';
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
