<?php
// Mutation check for the G-4f persistence safeguards (no source file is modified). Each mutant is a modified COPY of
// one class, loaded by an extra bootstrap before the autoloader, on the isolated cluster; the
// ReferenceGeographyPostgres suite must then FAIL ("killed"), and the failing tests are printed.
// Usage: php mutation-check-pg.php <repo root> <scratch dir> <pinned image>
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'P1 publication does not lock the revision row' => ['Infrastructure/PostgresPublicationLedger.php', [
        ['$this->db->selectOne(\'SELECT id FROM refgeo_revisions WHERE id = ? FOR UPDATE\', [$id->value()]);', '// no row lock'],
    ]],
    'P2 the trigger refusal is not mapped' => ['Infrastructure/PostgresRevisionRepository.php', [
        ['if (str_contains($e->getMessage(), \'REVISION_ALREADY_PUBLISHED\')) {', 'if (false) {'],
    ]],
];
$survivors = 0;
foreach ($mutants as $label => [$file, $edits]) {
    $src = file_get_contents("$ctx/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/mutant.php", $src);
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres >/dev/null 2>&1", $out, $code);
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/cluster/suite.txt | sed -E 's/.*::test_//' | cut -c1-60 | tr '\\n' ';'"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-48s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
