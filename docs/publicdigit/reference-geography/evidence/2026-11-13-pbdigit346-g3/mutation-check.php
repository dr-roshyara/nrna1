<?php
// Mutation check for G-3 safeguards (no source file is modified). Each mutant is a modified COPY of one class,
// loaded before Composer's autoloader can load the original; the G-3 tests must then FAIL ("killed").
// Usage: php mutation-check.php <repo root> <scratch dir>
[$_, $root, $scratch] = $argv;
$d = "$root/app/Contexts/ReferenceGeography/Domain";
$mutants = [
    'M1 independence check removed' => ['PublicationGovernance.php', 'if ($roster->knownSameAt($person, $excluded, $version)) {', 'if (false) {'],
    'M2 merges ignored by E(v)' => ['StewardRoster.php', "if (\$entry->kind !== RosterEntryKind::PersonsMerged || isset(\$reversed[\$entry->mergeId])) {", 'if (true) {'],
    'M3 approver judged at publication version, not approval version' => ['PublicationGovernance.php', '$roster->mayActForAt($approval->by, $revision->id(), $approval->rosterVersion)', '$roster->mayActForAt($approval->by, $revision->id(), $version)'],
    'M4 threshold not latched' => ['StewardRoster.php', 'return $this->thresholdActivatedAt !== null && $this->thresholdActivatedAt <= $version;', 'return count($this->stewardsAt($version)) >= 2;'],
    'M5 approvals of any version count' => ['PublicationGovernance.php', 'foreach ($revision->countingApprovals() as $approval) {', 'foreach ($revision->approvals() as $approval) {'],
    'M6 structural changes treated as non-structural' => ['PublicationGovernance.php', 'if (!$revision->validation()->structural) {', 'if (true) {'],
    'M7 reversals ignored' => ['StewardRoster.php', '$reversed[$entry->mergeId] = true;', '// reversal ignored'],
    'M8 a DISPUTE claim is no judgment trigger' => ['Revision.php', 'if ($claim->slot->kind() === ClaimKind::Dispute) {', 'if (false) {'],
    'M9 judgment flags ignored' => ['Revision.php', 'if ($this->judgmentFlags !== []) {', 'if (false) {'],
    'M10 a missing conflict declaration accepted' => ['PublicationGovernance.php', 'return [GovernanceRefusal::ConflictDeclarationMissing];', 'return [];'],
    'M11 declared interest never conflicts' => ['PublicationGovernance.php', 'return $interest && $revision->isJudgmentMatter() ? [GovernanceRefusal::MateriallyConflicted] : [];', 'return [];'],
    'M12 assessor not re-checked at publication' => ['PublicationGovernance.php', ': $this->conflict($revision->assessor(), $revision, $roster, $version);', ': [];'],
    'M13 second person conflict not checked' => ['PublicationGovernance.php', '$problems = $this->conflict($approval->by, $revision, $roster, $version);', '$problems = [];'],
    'M14 a later declaration does not supersede' => ['StewardRoster.php', '$interest = $entry->interest;', '$interest ??= $entry->interest;'],
];
$tests = "$root/tests/Unit/Contexts/ReferenceGeography/Domain/PublicationGovernanceTest.php $root/tests/Unit/Contexts/ReferenceGeography/Domain/StewardRosterTest.php $root/tests/Unit/Contexts/ReferenceGeography/Domain/MergeNoticeTest.php $root/tests/Unit/Contexts/ReferenceGeography/Domain/JudgmentMatterConflictTest.php";
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
    printf("%-70s %s  (%s)\n", $label, $killed ? 'KILLED' : 'SURVIVED', $summary);
}
echo $survivors === 0 ? "All mutants killed.\n" : "$survivors mutant(s) survived.\n";
exit($survivors === 0 ? 0 : 1);
