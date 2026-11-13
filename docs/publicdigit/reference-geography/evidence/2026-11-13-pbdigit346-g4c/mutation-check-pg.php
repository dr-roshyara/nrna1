<?php
// Mutation check for the G-4c PostgreSQL roster store (no source file is modified). Each mutant is a modified COPY of
// the store, loaded by an extra bootstrap before the autoloader can load the original, on the isolated cluster.
// The ReferenceGeographyPostgres suite must then FAIL ("killed").
// Usage: php mutation-check-pg.php <repo root> <scratch dir> <pinned image>
[$_, $root, $scratch, $image] = $argv;
$file = "$root/app/Contexts/ReferenceGeography/Infrastructure/PostgresStewardRosterStore.php";
$mutants = [
    'S1 the publication lock is not taken' => [["WHERE id = 1 FOR UPDATE')", "WHERE id = 1')"]],
    'S2 the write is not one transaction' => [
        ['return $this->db->transaction(function () use ($write): mixed {', 'return (function () use ($write): mixed {'],
        ["            return \$result;\n        });", "            return \$result;\n        })();"],
    ],
];
$survivors = 0;
foreach ($mutants as $label => $edits) {
    $src = file_get_contents($file);
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/PostgresStewardRosterStore.php", $src);
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/PostgresStewardRosterStore.php';\n");
    $evidence = "$dir/cluster";
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$evidence MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres >/dev/null 2>&1", $out, $code);
    $summary = trim((string) shell_exec("grep -E '^Tests:|^OK' $evidence/suite.txt | tail -1"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-45s %s  (%s)\n", $label, $killed ? 'KILLED' : 'SURVIVED', $summary);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
