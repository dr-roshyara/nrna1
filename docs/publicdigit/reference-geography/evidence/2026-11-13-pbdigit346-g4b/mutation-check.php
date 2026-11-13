<?php
// Mutation check for G-4b rebuild safeguards (no source file is modified). Each mutant is a modified COPY of one
// class, loaded before Composer's autoloader can load the original; RebuildPathsTest must then FAIL ("killed").
// Usage: php mutation-check.php <repo root> <scratch dir>
[$_, $root, $scratch] = $argv;
$d = "$root/app/Contexts/ReferenceGeography/Domain";
$mutants = [
    'R1 decode accepts a non-canonical encoding' => ['SlotKey.php', 'if ($key->encoded() !== $encoded) {', 'if (false) {'],
    'R2 before-image segments need not be contiguous' => ['BeforeImage.php', '|| ($previous !== null && ($previous->to === null || !$previous->to->equals($interval->from)))) {', ') {'],
    'R3 roster history gaps accepted' => ['StewardRoster.php', 'if ($version !== $i + 1 || $e->version !== $i + 1) {', 'if (false) {'],
    'R4 frozen digest not checked on rebuild' => ['Revision.php', '|| !hash_equals($s->current->digest, $r->digest($s->current->number, $s->assessor))) {', ') {'],
    'R5 overlapping claims accepted on rebuild' => ['Revision.php', "throw new \\InvalidArgumentException('Stored revision: two claims for one slot overlap (R-1).');", '// accepted'],
];
$tests = "$root/tests/Unit/Contexts/ReferenceGeography/Domain/RebuildPathsTest.php";
$survivors = 0;
foreach ($mutants as $label => [$file, $from, $to]) {
    $src = file_get_contents("$d/$file");
    if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue; }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/$file", str_replace($from, $to, $src));
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/vendor/autoload.php'; require '$dir/$file';\n");
    exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result $tests 2>&1", $out, $code);
    $summary = trim((string) end($out)); $out = [];
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-60s %s  (%s)\n", $label, $killed ? 'KILLED' : 'SURVIVED', $summary);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
