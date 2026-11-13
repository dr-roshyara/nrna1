<?php
// Mutation check for the G-4g concurrency safeguards (no source file is modified). Each mutant is a modified COPY of one
// class, loaded by an extra bootstrap before the autoloader, on the isolated cluster; PostgresConcurrencyMatrixTest
// must then FAIL ("killed"), and the failing tests are printed.
// Usage: php mutation-check-pg.php <repo root> <scratch dir> <pinned image> [label prefix filter]
[$_, $root, $scratch, $image] = $argv;
$only = $argv[4] ?? '';
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'G1 publication takes no lock' => ['Infrastructure/PostgresPublicationLedger.php', [
        ['$this->db->selectOne(\'SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE\');', '$this->db->selectOne(\'SELECT last_position FROM refgeo_sequence WHERE id = 1\');'],
    ]],
    'G2 a roster write takes no lock' => ['Infrastructure/PostgresStewardRosterStore.php', [
        ['FROM refgeo_sequence WHERE id = 1 FOR UPDATE\')', 'FROM refgeo_sequence WHERE id = 1\')'],
    ]],
    'G3 publication does not lock the revision row' => ['Infrastructure/PostgresPublicationLedger.php', [
        ['$this->db->selectOne(\'SELECT id FROM refgeo_revisions WHERE id = ? FOR UPDATE\', [$id->value()]);', '// no row lock'],
    ]],
    'G4 governance judged at the previous roster version' => ['Application/PublishRevision.php', [
        ['$decision = $this->governance->mayPublish($revision, $roster, $roster->version());', '$decision = $this->governance->mayPublish($revision, $roster, max(0, $roster->version() - 1));'],
    ]],
    'G5 a close takes no lock' => ['Infrastructure/PostgresPublicationLedger.php', [
        ['FROM refgeo_sequence WHERE id = 1 FOR UPDATE NOWAIT");', 'FROM refgeo_sequence WHERE id = 1");'],
    ]],
    'G6 the timestamp is adjusted, not refused' => ['Infrastructure/PostgresPublicationLedger.php', [
        ["VALUES (?, 'revision', ?, ?, ?, ?, ?, ?::timestamptz)\",", "VALUES (?, 'revision', ?, ?, ?, ?, ?, ?::timestamptz + interval '1 microsecond')\","],
    ]],
    'G7 notice idempotency removed in the domain' => ['Domain/StewardRoster.php', [
        ['if ($this->hasNotice(RosterEntryKind::PersonsMerged, $mergeId)) {', 'if (false) {'],
    ]],
    'G8 the roster is read before the lock' => ['Infrastructure/PostgresStewardRosterStore.php', [
        ['return $this->db->transaction(function () use ($write): mixed {', '$early = $this->current(); return $this->db->transaction(function () use ($write, $early): mixed {'],
        ['$roster = $this->current();', '$roster = $early;'],
    ]],
];
$survivors = 0;
foreach ($mutants as $label => [$file, $edits]) {
    if ($only !== '' && !str_starts_with($label, $only)) {
        continue;
    }
    $src = file_get_contents("$ctx/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND ($from)\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/mutant.php", $src);
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter PostgresConcurrencyMatrixTest >/dev/null 2>&1", $out, $code);
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/cluster/suite.txt | sed -E 's/.*::test_//' | cut -c1-70 | sort -u | tr '\\n' ';'"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-52s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
