<?php
// Mutation check for G-6a/G-6b (candidates, objections) (no source file is modified). Each mutant is a modified COPY of one class,
// required by an extra bootstrap before the autoloader. "unit" mutants run the database-free Geography unit tests;
// "pg" mutants run PostgresGeographyReadModelTest on the isolated cluster. A mutant must make tests FAIL (killed);
// the failing tests are printed so each kill can be checked against its intent.
// Usage: php mutation-check-g5.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$only = $argv[4] ?? '';
$ctx = "$root/app/Contexts/ReferenceGeography";
$mutants = [
    'M1 the recording steward may decide (D-84a)' => ['unit', 'Domain/Objection.php', [
        ['if ($by->equals($this->recordedBy)) {', 'if (false) {'],
    ]],
    'M2 reconsideration without new evidence' => ['unit', 'Application/ObjectionService.php', [
        ['if ($new === []) {', 'if (false) {'],
    ]],
    'M3 a positive objection may be rejected' => ['unit', 'Domain/Objection.php', [
        ["if (\$this->assessment?->allPositive() === true) {\n            throw new ObjectionRefused('An authentic", "if (false) {\n            throw new ObjectionRefused('An authentic"],
    ]],
    'M4 correction without every finding positive' => ['unit', 'Domain/Objection.php', [
        ['if ($this->assessment?->allPositive() !== true) {', 'if (false) {'],
    ]],
    'M5 an unpublished claim may be objected to' => ['unit', 'Application/ObjectionService.php', [
        ['if (!$this->published->isPublished($target)) {', 'if (false) {'],
    ]],
    'M6 acceptance attaches instead of proposing' => ['unit', 'Application/CandidateService.php', [
        ['$this->revisions->prepare($proposedRevision);', '// not prepared'],
    ]],
    'M7 the revision is prepared before the lifecycle check' => ['unit', 'Application/ObjectionService.php', [
        ["\$decide(\$stored->objection, \$actor);\n        \$this->revisions->prepare(\$proposedRevision);", "\$this->revisions->prepare(\$proposedRevision);\n        \$decide(\$stored->objection, \$actor);"],
    ]],
    'M8 published means merely frozen' => ['pg-objection', 'Infrastructure/PostgresPublishedClaims.php', [
        ['FROM refgeo_claims c JOIN refgeo_publications p ON p.revision_id = c.revision_id AND p.content_version = c.content_version', 'FROM refgeo_claims c'],
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
