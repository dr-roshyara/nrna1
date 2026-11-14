<?php
// Mutation check for G-6c-3 (no source file is modified). Each mutant is a modified COPY of one class, required by an
// extra bootstrap after the cluster bootstrap and before the class is autoloaded. Runs IdentityNoticeImpactTest on the
// isolated cluster. Killed = the suite fails; the failing tests and the count of executed tests are printed, and the
// errors count shows whether a failure was an assertion (intended) or an error.
// Usage: php mutation-check-g6c3.php <repo root> <scratch dir> <pinned image>
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'C1 the person index is not written at publication' => ['Infrastructure/PostgresPublicationLedger.php', [
        ["\$this->db->table('refgeo_publication_persons')->insert(array_values(\$rows));", '// mutant: none'],
    ]],
    'C2 the counted approver is the last approval, not the replay' => ['Application/IdentityNoticeConsumer.php', [
        ['$version->digest, $decision->secondPerson,', '$version->digest, $p->revision->countingApprovals()[count($p->revision->countingApprovals()) - 1]->by,'],
    ]],
    'C3 the lifecycles are ignored' => ['Application/IdentityNoticeConsumer.php', [
        ['$lifecycles = $ledger->lifecycleFor(array_map(static fn (PublishedApproval $a): int => $a->position, $candidates));', '$lifecycles = [];'],
    ]],
    'C4 a reversal names no persons' => ['Application/IdentityNoticeConsumer.php', [
        ['static fn (StewardRoster $r): array => $r->mergedPersonsOf($mergeId)', 'static fn (StewardRoster $r): array => []'],
    ]],
    'C5 the roster entry (checkpoint) is not recorded' => ['Application/IdentityNoticeConsumer.php', [
        ['$ledger->recordRoster($roster);', '// mutant: no checkpoint'],
    ]],
    'C6 resolutions are not read into the lifecycle' => ['Infrastructure/PostgresPublicationLedger.php', [
        ["if (\$publicationOf !== []) {", "if (false) {"],
    ]],
];
$survivors = 0;
foreach ($mutants as $label => [$file, $edits]) {
    if (($argv[4] ?? '') !== '' && !str_starts_with($label, $argv[4])) { continue; }
    $src = file_get_contents("$ctx/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND ($from)\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/mutants/" . substr(md5($label), 0, 8); @mkdir($dir, 0777, true);
    file_put_contents("$dir/label.txt", $label);
    file_put_contents("$dir/mutant.php", $src);
    exec('php -l ' . escapeshellarg("$dir/mutant.php") . ' 2>&1', $lint, $lintCode);
    if ($lintCode !== 0) { echo "$label: MUTANT DOES NOT PARSE\n"; $survivors++; continue; }
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter IdentityNoticeImpactTest >/dev/null 2>&1", $out, $code);
    @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-60 | sort -u | tr '\\n' ';'"));
    $tests = trim((string) shell_exec("grep -hoE '^Tests: [0-9]+|^OK.*\\([0-9]+ tests' $dir/suite.txt | head -1"));
    $errors = trim((string) shell_exec("grep -hoE 'Errors: [0-9]+' $dir/suite.txt | head -1"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-62s %-8s %s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $tests, $errors ?: 'Errors: 0', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
