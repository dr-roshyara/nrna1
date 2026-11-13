<?php
// Mutation check for the G-4f application safeguards (no source file is modified): each mutant is a modified COPY of
// RevisionService, loaded before the autoloader; RevisionServiceTest must then FAIL ("killed").
// Usage: php mutation-check-app.php <repo root> <scratch dir>
[$_, $root, $scratch] = $argv;
$file = "$root/app/Contexts/ReferenceGeography/Application/RevisionService.php";
$mutants = [
    'U1 a published revision is not closed' => ['if ($stored->published) {', 'if (false) {'],
    'U2 the actor need not be appointed' => ['if (!$roster->mayActForAt($actor, $id, $roster->version())) {', 'if (false) {'],
    'U3 submission skips maySubmit' => ['$this->require($this->governance->maySubmit($actor, $r, $roster, $roster->version())->refusals);', '// skipped'],
    'U4 approval skips mayApprove' => ['$this->require($this->governance->mayApprove($actor, $r, $roster, $roster->version())->refusals);', '// skipped'],
];
$survivors = 0;
foreach ($mutants as $label => [$from, $to]) {
    $src = file_get_contents($file);
    if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND\n"; $survivors++; continue; }
    $dir = "$scratch/" . md5($label); @mkdir($dir, 0777, true);
    file_put_contents("$dir/RevisionService.php", str_replace($from, $to, $src));
    file_put_contents("$dir/bootstrap.php", "<?php require '$root/vendor/autoload.php'; require '$dir/RevisionService.php';\n");
    exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result tests/Unit/Contexts/ReferenceGeography/Application/RevisionServiceTest.php 2>&1", $out, $code);
    $failed = implode(';', array_map(static fn ($l) => preg_replace('/.*::test_/', '', $l), preg_grep('/^\d+\) /', $out)));
    $out = [];
    $killed = $code !== 0;
    $survivors += $killed ? 0 : 1;
    printf("%-40s %s  %s\n", $label, $killed ? 'KILLED' : 'SURVIVED', $failed);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
