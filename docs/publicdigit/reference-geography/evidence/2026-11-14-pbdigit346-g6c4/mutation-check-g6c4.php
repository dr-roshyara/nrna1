<?php
// Mutation check for G-6c-4 (no source file is modified): each mutant is a modified COPY of one class, loaded before the
// autoloader would load the real one. "unit" mutants run the Geography unit tests (database-free); "pg" mutants run
// ConfirmPublicationTest on the isolated cluster. Killed = the suite fails; the failing tests, the executed test count
// and the error count (0 = killed by assertions) are printed.
// Usage: php mutation-check-g6c4.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'K1 a finding that is not open may be confirmed' => ['unit', 'Domain/FindingConfirmation.php', [['if ($lifecycle->openFindingAt() !== $finding) {', 'if (false) {']]],
    'K2 the digest is not checked' => ['unit', 'Domain/FindingConfirmation.php', [['$refusals = hash_equals($approval->digest, $digest) ? [] : [GovernanceRefusal::DigestMismatch];', '$refusals = [];']]],
    'K3 the defective approver is not checked' => ['unit', 'Domain/PublicationGovernance.php', [[' || $roster->knownSameAt($confirmer, $defectiveApprover, $version)) {', ') {']]],
    'K4 excluded(r) is not checked for a confirmer' => ['unit', 'Domain/PublicationGovernance.php', [['if ($this->isExcluded($confirmer, $revision, $roster, $version) || $roster->knownSameAt($confirmer', 'if ($roster->knownSameAt($confirmer']]],
    'K5 the defective approver is always the original' => ['unit', 'Domain/FindingConfirmation.php', [['$defective = $lifecycle->countedApproverAt($approval->approver, $finding);', '$defective = $approval->approver;']]],
    'K6 appointment is not checked' => ['unit', 'Domain/PublicationGovernance.php', [['$refusals = $roster->mayActForAt($confirmer, $revision->id(), $version) ? [] : [GovernanceRefusal::NotAppointed];', '$refusals = [];']]],
    'K7 the declaration and conflict are not checked' => ['unit', 'Domain/PublicationGovernance.php', [["GovernanceRule::Act, [...\$refusals, ...\$this->conflict(\$confirmer, \$revision, \$roster, \$version)]);", 'GovernanceRule::Act, $refusals);']]],
    'K8 the confirmer is not indexed' => ['pg', 'Infrastructure/PostgresPublicationLedger.php', [["\$this->db->table('refgeo_publication_persons')->insertOrIgnore(", "if (false) \$this->db->table('refgeo_publication_persons')->insertOrIgnore("]]],
    'K9 the confirmation is not appended (outcome only)' => ['pg', 'Application/ConfirmPublication.php', [['return ConfirmationOutcome::confirmed($ledger->appendNotice(GovernanceNotice::confirmation($finding, $actor, $digest, $w)));', 'return ConfirmationOutcome::confirmed($ledger->roster()->version());']]],
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
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter ConfirmPublicationTest >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-55 | sort -u | tr '\\n' ';'"));
    $tests = trim((string) shell_exec("grep -hoE '^Tests: [0-9]+|^OK \\([0-9]+ tests' $dir/suite.txt | head -1"));
    $errors = trim((string) shell_exec("grep -hoE 'Errors: [0-9]+' $dir/suite.txt | head -1"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-52s %-8s [%s] %s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $mode, $tests, $errors ?: 'Errors: 0', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
