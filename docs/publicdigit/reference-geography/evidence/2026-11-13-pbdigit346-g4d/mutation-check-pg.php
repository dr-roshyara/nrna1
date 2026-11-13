<?php
// Mutation check for the G-4d PostgreSQL repository and atlas reader (no source file is modified). Each mutant is a
// modified COPY of one class, loaded by an extra bootstrap before the autoloader, on the isolated cluster; the
// ReferenceGeographyPostgres suite must then FAIL ("killed").
// Usage: php mutation-check-pg.php <repo root> <scratch dir> <pinned image>
[$_, $root, $scratch, $image] = $argv;
$infra = "$root/app/Contexts/ReferenceGeography/Infrastructure";
$mutants = [
    'D1 unpublished claims visible in the atlas' => ['PostgresPublishedAtlas.php', [
        ["private const PUBLISHED = 'FROM refgeo_claims c JOIN refgeo_publications p", "private const PUBLISHED = 'FROM refgeo_claims c LEFT JOIN refgeo_publications p"],
        ["WHERE c.slot_key = ? AND p.position <= ? ORDER BY p.position, c.claim_id', [", "WHERE c.slot_key = ? AND coalesce(p.position, 1) <= ? ORDER BY p.position, c.claim_id', ["],
        ["\$history = \$history->with(PostgresRevisionRepository::claimOf(\$row), (int) \$row->position);", "\$history = \$history->with(PostgresRevisionRepository::claimOf(\$row), (int) (\$row->position ?? 1));"],
    ]],
    'D2 the atlas ignores p0' => ['PostgresPublishedAtlas.php', [["WHERE c.slot_key = ? AND p.position <= ? ORDER BY", "WHERE c.slot_key = ? AND p.position <= ? + 1000000 ORDER BY"]]],
    'D3 no optimistic concurrency check' => ['PostgresRevisionRepository.php', [["WHERE id = ? AND row_version = ?',", "WHERE id = ? AND ? > 0',"]]],
    'D4 a save is not one transaction' => ['PostgresRevisionRepository.php', [
        ['return $this->db->transaction(function () use ($s, $expectedRowVersion): int {', 'return (function () use ($s, $expectedRowVersion): int {'],
        ["            return \$expectedRowVersion + 1;\n        });", "            return \$expectedRowVersion + 1;\n        })();"],
    ]],
    'D5 a submitted version is not frozen' => ['PostgresRevisionRepository.php', [["if (\$s->current !== null && in_array(\$s->state, [RevisionState::Submitted, RevisionState::Stale], true)", "if (false && \$s->current !== null && in_array(\$s->state, [RevisionState::Submitted, RevisionState::Stale], true)"]]],
];
$survivors = 0;
foreach ($mutants as $label => [$file, $edits]) {
    $src = file_get_contents("$infra/$file");
    foreach ($edits as [$from, $to]) {
        if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND ($from)\n"; $survivors++; continue 2; }
        $src = str_replace($from, $to, $src);
    }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/$file", $src);
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/$file';\n");
    exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres >/dev/null 2>&1", $out, $code);
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/cluster/suite.txt | sed -E 's/.*::test_//' | head -3 | tr '\\n' ';'"));
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-46s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
