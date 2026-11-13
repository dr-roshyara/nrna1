<?php
// Mutation check for the G-4e publication safeguards (no source file is modified). Each mutant is a modified COPY of
// one class, loaded by an extra bootstrap before the autoloader, on the isolated cluster; the
// ReferenceGeographyPostgres suite must then FAIL ("killed"), and the failing tests are printed.
// Usage: php mutation-check-pg.php <repo root> <scratch dir> <pinned image> [mutant label prefix filter]
[$_, $root, $scratch, $image] = $argv;
$only = $argv[4] ?? '';
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'L1 publication takes no lock' => ['Infrastructure/PostgresPublicationLedger.php', [["\$this->db->selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE');", "\$this->db->selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1');"]]],
    'L2 governance not re-checked under the lock' => ['Application/PublishRevision.php', [['if (!$decision->allowed) {', 'if (false) {']]],
    'L3 staleness ignored' => ['Application/PublishRevision.php', [['if ($dependencies->firstInvalidating($ledger->publishedAfter($dependencies->p0)) !== null) {', 'if (false) {']]],
    'L4 no already-published check' => ['Application/PublishRevision.php', [['if ($version !== null && $ledger->isPublished($id, $version)) {', 'if (false) {']]],
    'L5 clock rule removed (trigger is the backstop)' => ['Infrastructure/PostgresPublicationLedger.php', [['if (!$clock->past) {', 'if (false) {']]],
    'L6 the actor is not checked' => ['Application/PublishRevision.php', [['if (!$roster->mayActForAt($actor, $id, $roster->version())) {', 'if (false) {']]],
    'L7 publication is not one transaction' => ['Infrastructure/PostgresPublicationLedger.php', [
        ['return $this->db->transaction(function () use ($work): mixed {', 'return (function () use ($work): mixed {'],
        ["            return \$result;\n        });\n    }\n\n    public function closeThrough", "            return \$result;\n        })();\n    }\n\n    public function closeThrough"],
    ]],
];
$survivors = 0;
foreach ($mutants as $label => [$file, $edits]) {
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
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres >/dev/null 2>&1", $out, $code);
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/cluster/suite.txt | sed -E 's/.*::test_//' | cut -c1-60 | tr '\\n' ';'"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-48s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
