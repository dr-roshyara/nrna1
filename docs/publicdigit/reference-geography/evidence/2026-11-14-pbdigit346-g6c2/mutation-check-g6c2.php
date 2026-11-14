<?php
// Mutation check for G-6c-2 (the D-90 domain) (no source file is modified). Each mutant is a modified COPY of one class,
// required by an extra bootstrap before the autoloader. "unit" mutants run the database-free Geography unit tests;
// "pg" mutants run PostgresGeographyReadModelTest on the isolated cluster. A mutant must make tests FAIL (killed);
// the failing tests are printed so each kill can be checked against its intent.
// Usage: php mutation-check-g5.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$only = $argv[4] ?? '';
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'D1 affected persons from AFTER components only' => ['unit', 'Domain/ApprovalDefects.php', [
        ['[...$roster->componentAt($person, $before), ...$roster->componentAt($person, $after)]', '$roster->componentAt($person, $after)'],
    ]],
    'D2 a published correction does not resolve a finding' => ['unit', 'Domain/FindingLifecycle.php', [
        ["        foreach (\$this->corrections as \$q) {\n            if (\$q > \$f", "        foreach ([] as \$q) {\n            if (\$q > \$f"],
    ]],
    'D3 the confirmer never becomes the counted approver' => ['unit', 'Domain/FindingLifecycle.php', [
        ['$counted = $notice->confirmedBy ?? $counted;', '// ignored'],
    ]],
    'D4 a corrected publication is still evaluated' => ['unit', 'Domain/ApprovalDefects.php', [
        ["if (\$lifecycle->correctedAt() !== null) {\n                continue;", "if (false) {\n                continue;"],
    ]],
    'D5 any open finding is closed (another path ignored)' => ['unit', 'Domain/ApprovalDefects.php', [
        ['} elseif (!$defective && $open !== null) {', '} elseif ($open !== null) {'],
    ]],
    'D6 the status reads corrections beyond k' => ['unit', 'Domain/FindingLifecycle.php', [
        ['$corrected = $this->correctedAt($k);', '$corrected = $this->correctedAt(null);'],
    ]],
    'D7 a duplicate finding while one is open' => ['unit', 'Domain/ApprovalDefects.php', [
        ['if ($defective && $open === null) {', 'if ($defective) {'],
    ]],
];
$survivors = 0;
foreach ($mutants as $label => [$mode, $file, $edits]) {
    if ($only !== '' && !str_starts_with($label, $only)) {
        continue;
    }
    $src = file_get_contents("$ctx/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/mutant.php", $src);
    if ($mode === 'unit') {
        file_put_contents("$dir/bootstrap.php", "<?php require '$dir/mutant.php'; require '$root/vendor/autoload.php';\n");
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result tests/Unit/Contexts/ReferenceGeography > $dir/suite.txt 2>&1", $out, $code);
    } else {
        $filter = $mode === 'pg-objection' ? 'PostgresObjectionRepositoryTest' : 'PostgresGeographyReadModelTest';
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter $filter >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-80 | sort -u | tr '\\n' ';'"));
    $errors = trim((string) shell_exec("grep -cE '^(Error|PHP Fatal)' $dir/suite.txt"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-58s %-8s [%s] errors:%s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $mode, $errors, $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
