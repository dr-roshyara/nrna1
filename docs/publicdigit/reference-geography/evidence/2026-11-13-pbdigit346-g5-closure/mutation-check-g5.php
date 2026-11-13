<?php
// Mutation check for the G-5 read side (no source file is modified). Each mutant is a modified COPY of one class,
// required by an extra bootstrap before the autoloader. "unit" mutants run the database-free Geography unit tests;
// "pg" mutants run PostgresGeographyReadModelTest on the isolated cluster. A mutant must make tests FAIL (killed);
// the failing tests are printed so each kill can be checked against its intent.
// Usage: php mutation-check-g5.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$only = $argv[4] ?? '';
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'R1 the knowledge boundary is not checked' => ['unit', 'Application/PublishedGeography.php', [
        ['if ($position < 0 || $position > $committed) {', 'if (false) {'],
    ]],
    'R2 a merge is read without the knowledge position' => ['unit', 'Domain/PlaceBelief.php', [
        ['$latest = $reconciliation->latest($p);', '$latest = $reconciliation->latest(null);'],
    ]],
    'R3 a cleared or withdrawn dispute still flags' => ['unit', 'Domain/PlaceBelief.php', [
        ['if ($dispute !== null && !$dispute->value->isNone() && $dispute->value->value() === self::DISPUTED) {', 'if ($dispute !== null) {'],
    ]],
    'R4 codes are matched fuzzily' => ['unit', 'Domain/PlaceMatcher.php', [
        ['? [strtoupper(trim($text)) === strtoupper(trim($candidate->value)) ? 1.0 : 0.0, false]', '? [self::similarity(self::normalise($text), self::normalise($candidate->value)), false]'],
    ]],
    'R5 lookup ignores whether a place exists at t' => ['unit', 'Application/PublishedGeography.php', [
        ['if ($unit === null || $value === null || !isset($eligible[$unit->value()])) {', 'if ($unit === null || $value === null) {'],
    ]],
    'R6 the feed reads beyond the knowledge' => ['unit', 'Application/PublishedGeography.php', [
        ['$this->source->publicationsBetween($afterPosition, $p, $limit)', '$this->source->publicationsBetween($afterPosition, PHP_INT_MAX, $limit)'],
    ]],
    'R7 a subject\'s slots are found among unpublished claims' => ['pg', 'Infrastructure/PostgresGeographyReadSource.php', [
        ["foreach (\$this->db->select('SELECT DISTINCT c.slot_key ' . self::PUBLISHED . ' WHERE c.slot_key LIKE ? AND p.position <= ? ORDER BY c.slot_key', [\$needle, \$p]) as \$row) {",
         "foreach (\$this->db->select('SELECT DISTINCT c.slot_key FROM refgeo_claims c WHERE c.slot_key LIKE ? AND ? >= 0 ORDER BY c.slot_key', [\$needle, \$p]) as \$row) {"],
    ]],
    'R8 a publication recorded exactly at τ is excluded' => ['pg', 'Infrastructure/PostgresGeographyReadSource.php', [
        ['WHERE recorded_at <= ?::timestamptz', 'WHERE recorded_at < ?::timestamptz'],
    ]],
    'R9 lookup orders places by the matched text' => ['unit', 'Domain/PlaceMatcher.php', [
        ['[$b->score, $a->partial, $a->unit->value()] <=> [$a->score, $b->partial, $b->unit->value()]', 'self::order($a, $b) ?: strcmp($a->unit->value(), $b->unit->value())'],
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
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; require '$dir/mutant.php';\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter PostgresGeographyReadModelTest >/dev/null 2>&1", $out, $code);
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
