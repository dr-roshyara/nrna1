<?php
// G-6c-6: the FULL mutation run over G-6c-2 to G-6c-6 (no source file is modified: each mutant is a modified COPY of one
// class, loaded before the real one). Modes: "unit" (Geography unit tests, database-free) or "pg:<Filter>" (that test
// class on the isolated cluster). Classification per mutant:
//   KILLED            the run failed by assertion(s) only (Errors: 0)
//   KILLED+ERRORS     the run failed and some failures were errors: the first error class is printed so each can be
//                     classified (e.g. a database constraint refusing as a backstop) — never counted as an assertion kill
//   SURVIVED          the run passed (expected only where an equivalence is argued: listed in $equivalent)
// Usage: php mutation-check-g6c-full.php <repo root> <scratch dir> <pinned image> [label prefix]
[$_, $root, $scratch, $image] = $argv;
$ctx = "$root/app/Contexts/ReferenceGeography";
$AD = 'Domain/ApprovalDefects.php'; $FL = 'Domain/FindingLifecycle.php'; $FC = 'Domain/FindingConfirmation.php'; $PG = 'Domain/PublicationGovernance.php';
$CC = 'Domain/CorrectionCoverage.php'; $INC = 'Application/IdentityNoticeConsumer.php'; $LED = 'Infrastructure/PostgresPublicationLedger.php';
$PFL = 'Infrastructure/PostgresFindingLifecycles.php'; $RS = 'Infrastructure/PostgresGeographyReadSource.php'; $PGEO = 'Application/PublishedGeography.php';
$impact = 'pg:IdentityNoticeImpactTest'; $confirm = 'pg:ConfirmPublicationTest'; $correct = 'pg:CorrectionLinkTest'; $history = 'pg:GovernanceHistoryReadTest';
$mutants = [
    // G-6c-2 domain
    'D1 affected persons from AFTER components only' => ['unit', $AD, [['[...$roster->componentAt($person, $before), ...$roster->componentAt($person, $after)]', '$roster->componentAt($person, $after)']]],
    'D2 a published correction does not resolve a finding' => ['unit', $FL, [["foreach (\$this->corrections as \$q) {\n            if (\$q > \$f", "foreach ([] as \$q) {\n            if (\$q > \$f"]]],
    'D3 the confirmer never becomes the counted approver' => ['unit', $FL, [['$counted = $notice->confirmedBy ?? $counted;', '// mutant']]],
    'D4 a corrected publication is still evaluated' => ['unit', $AD, [["if (\$lifecycle->correctedAt() !== null) {\n                continue;", "if (false) {\n                continue;"]]],
    'D5 any open finding is closed (another path ignored)' => ['unit', $AD, [['} elseif (!$defective && $open !== null) {', '} elseif ($open !== null) {']]],
    'D6 the status reads corrections beyond k' => ['unit', $FL, [['$corrected = $this->correctedAt($k);', '$corrected = $this->correctedAt(null);']]],
    'D7 a duplicate finding while one is open' => ['unit', $AD, [['if ($defective && $open === null) {', 'if ($defective) {']]],
    // G-6c-3 atomic Identity notices
    'C1 the person index is not written at publication' => [$impact, $LED, [["\$this->db->table('refgeo_publication_persons')->insert(array_values(\$rows));", '// mutant']]],
    'C2 the counted approver is the last approval, not the replay' => [$impact, $PG, [['$version->digest, $decision->secondPerson, $revision->excludedPersons());', '$version->digest, $revision->countingApprovals()[count($revision->countingApprovals()) - 1]->by, $revision->excludedPersons());']]],
    'C3 the lifecycles are ignored' => [$impact, $INC, [['$lifecycles = $ledger->lifecycleFor(array_map(static fn (PublishedApproval $a): int => $a->position, $candidates));', '$lifecycles = [];']]],
    'C4 a reversal names no persons' => [$impact, $INC, [['static fn (StewardRoster $r): array => $r->mergedPersonsOf($mergeId)', 'static fn (StewardRoster $r): array => []']]],
    'C5 the roster entry (checkpoint) is not recorded' => [$impact, $INC, [['$ledger->recordRoster($roster);', '// mutant']]],
    'C6 resolutions are not read into the lifecycle' => [$impact, $PFL, [['if ($publicationOf !== []) {', 'if (false) {']]],
    // G-6c-4 confirmation (domain rules: unit, then the same mutants against the database tests)
    'K1 a finding that is not open may be confirmed' => ['unit', $FC, [['if ($lifecycle->openFindingAt() !== $finding) {', 'if (false) {']]],
    'K2 the digest is not checked' => ['unit', $FC, [['$refusals = hash_equals($approval->digest, $digest) ? [] : [GovernanceRefusal::DigestMismatch];', '$refusals = [];']]],
    'K3 the defective approver is not checked' => ['unit', $PG, [[' || $roster->knownSameAt($confirmer, $defectiveApprover, $version)) {', ') {']]],
    'K4 excluded(r) is not checked for a confirmer' => ['unit', $PG, [['if ($this->isExcluded($confirmer, $revision, $roster, $version) || $roster->knownSameAt($confirmer', 'if ($roster->knownSameAt($confirmer']]],
    'K5 the defective approver is always the original' => ['unit', $FC, [['$defective = $lifecycle->countedApproverAt($approval->approver, $finding);', '$defective = $approval->approver;']]],
    'K6 appointment is not checked' => ['unit', $PG, [['$refusals = $roster->mayActForAt($confirmer, $revision->id(), $version) ? [] : [GovernanceRefusal::NotAppointed];', '$refusals = [];']]],
    'K7 the declaration and conflict are not checked' => ['unit', $PG, [["GovernanceRule::Act, [...\$refusals, ...\$this->conflict(\$confirmer, \$revision, \$roster, \$version)]);", 'GovernanceRule::Act, $refusals);']]],
    'K8 the confirmer is not indexed' => [$confirm, $LED, [["\$this->db->table('refgeo_publication_persons')->insertOrIgnore(", "if (false) \$this->db->table('refgeo_publication_persons')->insertOrIgnore("]]],
    'K9 the confirmation is not appended (outcome only)' => [$confirm, 'Application/ConfirmPublication.php', [['return ConfirmationOutcome::confirmed($ledger->appendNotice(GovernanceNotice::confirmation($finding, $actor, $digest, $w)));', 'return ConfirmationOutcome::confirmed($ledger->roster()->version());']]],
    // G-6c-5 correction
    'L1 coverage checks the first flagged claim only' => ['unit', $CC, [['array_filter($flagged, static fn', 'array_filter(array_slice($flagged, 0, 1), static fn']]],
    'L2 the slot is ignored' => ['unit', $CC, [['static fn (Claim $r): bool => $r->slot->equals($flagged->slot)', 'static fn (Claim $r): bool => true']]],
    'L3 an open-ended fact is covered by a finite replacement' => ['unit', $CC, [['if ($end !== null && !$cursor->isBefore($end)) {', 'if ($end === null || !$cursor->isBefore($end)) {']]],
    'L4 a gap is tolerated' => ['unit', $CC, [["if (\$cursor->isBefore(\$r->interval->from)) {\n                return false;", "if (false) {\n                return false;"]]],
    'L5 the exclusive end is treated as inclusive' => ['unit', $CC, [['if ($end !== null && !$cursor->isBefore($end)) {', 'if ($end !== null && $end->isBefore($cursor)) {']]],
    'L6 no open finding is needed' => [$correct, $CC, [['if ($lifecycle->openFindingAt() === null) {', 'if (false) {']]],
    'L7 coverage is not consulted at publication' => [$correct, 'Application/PublishRevision.php', [['$refusals = CorrectionCoverage::refusals($lifecycle, $ledger->claimsPublishedAt($corrects), $revision->claims());', '$refusals = [];']]],
    'L8 the link is not written' => [$correct, $LED, [['$recordedAt, $corrects]);', '$recordedAt, null]);']]],
    // G-6c-6 read side
    'R1 the history reads entries beyond the knowledge' => [$history, $RS, [['[$publication], $p)[$publication]', '[$publication], null)[$publication]']]],
    'R2 the status is taken as of everything known' => [$history, $PGEO, [['$status = $lifecycle->statusAt($p);', '$status = $lifecycle->statusAt(PHP_INT_MAX);']]],
    'R12 both bounds removed (SQL and statusAt)' => [$history, $RS, [['[$publication], $p)[$publication]', '[$publication], null)[$publication]']],
        [[$PGEO, [['$status = $lifecycle->statusAt($p);', '$status = $lifecycle->statusAt(PHP_INT_MAX);']]]]],
    'R3 approvalStatus does not check the knowledge' => ['unit', $PGEO, [["\$p = \$this->checked(\$knowledge->position);\n        \$lifecycle = \$this->source->findingLifecycle", "\$p = \$knowledge->position;\n        \$lifecycle = \$this->source->findingLifecycle"]]],
    'R4 a notice position gets an approval status' => [$history, $RS, [["WHERE position = ? AND kind = 'revision'\", [\$publication]", "WHERE position = ?\", [\$publication]"]]],
    'R5 the feed drops the governance relations' => [$history, $PGEO, [['$c->noticeKind, $c->concernsPosition, $c->correctsPosition);', 'null, null, null);']]],
];
foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7'] as $k) {
    foreach ($mutants as $label => $m) {
        if (str_starts_with($label, "$k ")) {
            $mutants[str_replace("$k ", "$k (db) ", $label)] = [$confirm, $m[1], $m[2]];
        }
    }
}
$equivalent = ['D1' => 'before ⊆ after components of the named persons (design §18; A8b)', 'K3 (db)' => 'G-6c-3 invariant: an open finding\'s defective approver is known-same as an excluded person now', 'K5 (db)' => 'same invariant', 'R1' => 'the status is also bounded by statusAt(k) (two defences; R12 removes both)', 'R2' => 'the SQL also reads only entries <= k (two defences; R12 removes both)'];
$survivors = 0; $unexpected = 0;
foreach ($mutants as $label => $spec) {
    [$mode, $file, $edits] = $spec;
    if (($argv[4] ?? '') !== '' && !str_starts_with($label, $argv[4])) { continue; }
    $dir = "$scratch/mutants/" . substr(md5($label), 0, 8); @mkdir($dir, 0777, true);
    file_put_contents("$dir/label.txt", $label);
    $requires = '';
    foreach ([[$file, $edits], ...($spec[3] ?? [])] as $i => [$f, $es]) {
        $src = file_get_contents("$ctx/$f");
        foreach ($es as [$from, $to]) {
            if (substr_count($src, $from) !== 1) { echo "$label: SITE NOT FOUND in $f\n"; $unexpected++; continue 3; }
            $src = str_replace($from, $to, $src);
        }
        file_put_contents("$dir/mutant$i.php", $src);
        exec('php -l ' . escapeshellarg("$dir/mutant$i.php") . ' 2>&1', $lint, $lintCode);
        if ($lintCode !== 0) { echo "$label: MUTANT DOES NOT PARSE\n"; $unexpected++; continue 2; }
        $requires .= "require '$dir/mutant$i.php'; ";
    }
    @unlink("$dir/suite.txt");
    if ($mode === 'unit') {
        file_put_contents("$dir/bootstrap.php", "<?php {$requires}require '$root/vendor/autoload.php';\n");
        exec("cd $root && vendor/bin/phpunit --no-configuration --bootstrap $dir/bootstrap.php --do-not-cache-result tests/Unit/Contexts/ReferenceGeography > $dir/suite.txt 2>&1", $out, $code);
    } else {
        $filter = substr($mode, 3);
        file_put_contents("$dir/bootstrap.php", "<?php require '$root/tests/bootstrap-membership-pg.php'; {$requires}\n");
        exec("cd $root && MEMBERSHIP_PG_EVIDENCE_DIR=$dir/cluster MEMBERSHIP_PG_IMAGE=$image bash scripts/testing/membership-pg.sh --bootstrap $dir/bootstrap.php --testsuite ReferenceGeographyPostgres --filter $filter >/dev/null 2>&1", $out, $code);
        @copy("$dir/cluster/suite.txt", "$dir/suite.txt");
    }
    $suite = (string) @file_get_contents("$dir/suite.txt");
    $failed = trim((string) shell_exec("grep -E '^[0-9]+\\) ' $dir/suite.txt | sed -E 's/.*::test_//' | cut -c1-45 | sort -u | tr '\\n' ';'"));
    $tests = preg_match('/^Tests: (\d+)|^OK \((\d+) tests/m', $suite, $t) ? ($t[1] ?: $t[2]) : '?';
    $errors = preg_match('/Errors: (\d+)/', $suite, $e) ? (int) $e[1] : 0;
    $firstError = preg_match('/^There (?:was|were) \d+ errors?:\s+1\) [^\n]+\n([^\n:]+)/m', $suite, $fe) ? trim($fe[1]) : '';
    $id = explode(' ', $label)[0] . (str_contains($label, '(db)') ? ' (db)' : '');
    if ($tests === '?') {
        printf("%-62s %-10s INCONCLUSIVE (no test run recorded; exit %d)\n", $label, "[$mode]", $code);
        $unexpected++;
        continue;
    }
    if ($code === 0) {
        $class = isset($equivalent[$id]) ? 'SURVIVED (equivalent: ' . $equivalent[$id] . ')' : 'SURVIVED';
        $survivors++; $unexpected += isset($equivalent[$id]) ? 0 : 1;
    } else {
        $class = $errors === 0 ? 'KILLED' : "KILLED+ERRORS ({$errors}: {$firstError})";
    }
    printf("%-62s %-10s tests:%-4s %s  %s\n", $label, "[$mode]", $tests, $class, $failed);
}
echo "survivors: {$survivors}; unexpected (not argued equivalent, site missing or unparsable): {$unexpected}\n";
exit($unexpected === 0 ? 0 : 1);
