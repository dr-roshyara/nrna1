<?php
// Mutation check for G-6c-5, plus the G-6c-4 follow-up (K1-K7 against the DATABASE tests). No source file is modified:
// each mutant is a modified COPY of one class, loaded before the real one. Mode "unit": the Geography unit tests
// (database-free); "pg:<Test>": that test class on the isolated cluster. Killed = the run fails; the failing tests, the
// executed test count and the error count (0 = killed by assertions) are printed.
// Usage: php mutation-check-g6c5.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$cov = 'Domain/CorrectionCoverage.php';
$mutants = [
    'L1 coverage checks the first flagged claim only' => ['unit', $cov, [['array_filter($flagged, static fn', 'array_filter(array_slice($flagged, 0, 1), static fn']]],
    'L2 the slot is ignored' => ['unit', $cov, [['static fn (Claim $r): bool => $r->slot->equals($flagged->slot)', 'static fn (Claim $r): bool => true']]],
    'L3 an open-ended fact is covered by a finite replacement' => ['unit', $cov, [['if ($end !== null && !$cursor->isBefore($end)) {', 'if ($end === null || !$cursor->isBefore($end)) {']]],
    'L4 a gap is tolerated' => ['unit', $cov, [["if (\$cursor->isBefore(\$r->interval->from)) {\n                return false;", "if (false) {\n                return false;"]]],
    'L5 the exclusive end is treated as inclusive' => ['unit', $cov, [['if ($end !== null && !$cursor->isBefore($end)) {', 'if ($end !== null && $end->isBefore($cursor)) {']]],
    'L6 no open finding is needed' => ['pg:CorrectionLinkTest', $cov, [['if ($lifecycle->openFindingAt() === null) {', 'if (false) {']]],
    'L7 coverage is not consulted at publication' => ['pg:CorrectionLinkTest', 'Application/PublishRevision.php', [['$refusals = CorrectionCoverage::refusals($lifecycle, $ledger->claimsPublishedAt($corrects), $revision->claims());', '$refusals = [];']]],
    'L8 the link is not written' => ['pg:CorrectionLinkTest', 'Infrastructure/PostgresPublicationLedger.php', [['$recordedAt, $corrects]);', '$recordedAt, null]);']]],
    'K1 (pg) a finding that is not open may be confirmed' => ['pg:ConfirmPublicationTest', 'Domain/FindingConfirmation.php', [['if ($lifecycle->openFindingAt() !== $finding) {', 'if (false) {']]],
    'K2 (pg) the digest is not checked' => ['pg:ConfirmPublicationTest', 'Domain/FindingConfirmation.php', [['$refusals = hash_equals($approval->digest, $digest) ? [] : [GovernanceRefusal::DigestMismatch];', '$refusals = [];']]],
    'K3 (pg) the defective approver is not checked' => ['pg:ConfirmPublicationTest', 'Domain/PublicationGovernance.php', [[' || $roster->knownSameAt($confirmer, $defectiveApprover, $version)) {', ') {']]],
    'K4 (pg) excluded(r) is not checked for a confirmer' => ['pg:ConfirmPublicationTest', 'Domain/PublicationGovernance.php', [['if ($this->isExcluded($confirmer, $revision, $roster, $version) || $roster->knownSameAt($confirmer', 'if ($roster->knownSameAt($confirmer']]],
    'K5 (pg) the defective approver is always the original' => ['pg:ConfirmPublicationTest', 'Domain/FindingConfirmation.php', [['$defective = $lifecycle->countedApproverAt($approval->approver, $finding);', '$defective = $approval->approver;']]],
    'K6 (pg) appointment is not checked' => ['pg:ConfirmPublicationTest', 'Domain/PublicationGovernance.php', [['$refusals = $roster->mayActForAt($confirmer, $revision->id(), $version) ? [] : [GovernanceRefusal::NotAppointed];', '$refusals = [];']]],
    'K7 (pg) the declaration and conflict are not checked' => ['pg:ConfirmPublicationTest', 'Domain/PublicationGovernance.php', [["GovernanceRule::Act, [...\$refusals, ...\$this->conflict(\$confirmer, \$revision, \$roster, \$version)]);", 'GovernanceRule::Act, $refusals);']]],
];
$survivors = 0;
foreach ($mutants as $label => [$mode, $file, $edits]) {
    if (($argv[4] ?? '') !== '' && !str_starts_with($label, $argv[4])) { continue; }
    $src = file_get_contents("$ctx/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/mutants/" . substr(md5($label), 0, 8); @mkdir($dir, 0777, true);
    file_put_contents("$dir/label.txt", $label);
    file_put_contents("$dir/mutant.php", $src);
    exec('php -l ' . escapeshellarg("$dir/mutant.php") . ' 2>&1', $lint, $lintCode);
    if ($lintCode !== 0) { echo "$label: MUTANT DOES NOT PARSE\n"; $survivors++; continue; }
    if ($mode === 'unit') {
        file_put_contents("$dir/bootstrap.php", "<?php require '$dir/mutant.php'; require '$root/vendor/autoload.php';\n");
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result tests/Unit/Contexts/ReferenceGeography > $dir/suite.txt 2>&1", $out, $code);
    } else {
        $filter = substr($mode, 3);
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter $filter >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-50 | sort -u | tr '\\n' ';'"));
    $tests = trim((string) shell_exec("grep -hoE '^Tests: [0-9]+|^OK \\([0-9]+ tests' $dir/suite.txt | head -1"));
    $errors = trim((string) shell_exec("grep -hoE 'Errors: [0-9]+' $dir/suite.txt | head -1"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-56s %-8s [%s] %s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $mode, $tests, $errors ?: 'Errors: 0', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
