<?php
// G-8: mutation run for the evidenced-dataset importer (derived from the G-6c full driver) (no source file is modified: each mutant is a modified COPY of one
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
$PFL = 'Infrastructure/PostgresFindingLifecycles.php'; $PL = 'Domain/CldrParentLocales.php'; $DN = 'Domain/CldrDisplayNames.php'; $RV = 'Domain/RevisionValidator.php'; $CR = 'Domain/CldrRelease.php'; $RS = 'Infrastructure/PostgresGeographyReadSource.php'; $PGEO = 'Application/PublishedGeography.php';
$impact = 'pg:IdentityNoticeImpactTest'; $confirm = 'pg:ConfirmPublicationTest'; $correct = 'pg:CorrectionLinkTest'; $history = 'pg:GovernanceHistoryReadTest';
$IMP = 'Application/ImportEvidencedDataset.php'; $EDS = 'Application/Import/EvidencedDataset.php'; $EK = 'Application/Import/EntryKeys.php'; $RS2 = 'Application/RevisionService.php';
$mutants = [
    'I1 the checksum is not compared' => ['pg:ImportEvidencedDatasetTest', $IMP, [["if (!hash_equals(\$e->sha256, \$actual)) {", "if (false) {"]]],
    'I2 a missing file is accepted' => ['pg:ImportEvidencedDatasetTest', $IMP, [["if (\$actual === null) {\n                throw", "if (false) {\n                throw"]]],
    'I3 a second import is not refused' => ['pg:ImportEvidencedDatasetTest', $IMP, [["} catch (RevisionChangedConcurrently) {\n            throw new DatasetRefused(", "} catch (RevisionChangedConcurrently) {\n            if (false) throw new DatasetRefused("]]],
    'I4 the import needs no appointment' => ['pg:ImportEvidencedDatasetTest', $RS2, [["\$actor = \$this->acting->current();\n        \$this->requireAppointed(\$this->roster->current(), \$actor, \$id);\n        \$revision = Revision::prepare(", "\$actor = \$this->acting->current();\n        \$revision = Revision::prepare("]]],
    'I5 only the first claim is kept' => ['pg:ImportEvidencedDatasetTest', $RS2, [["foreach (\$claims as \$claim) {\n            \$revision->addClaim(\$claim, \$actor);\n        }", "foreach (array_slice(\$claims, 0, 1) as \$claim) {\n            \$revision->addClaim(\$claim, \$actor);\n        }"]]],
    'I6 undeclared evidence is accepted' => ['unit', $EDS, [["if (!isset(\$evidence[\$c['evidence'] ?? ''])) {", "if (false) {"]]],
    'I7 entry references are not resolved' => ['unit', $EDS, [["if (str_starts_with(\$value, '@')) {", "if (false) {"]]],
    'I8 the reuse terms may be missing' => ['unit', $EDS, [["private const EVIDENCE_FIELDS = ['id', 'authority', 'issued_on', 'url', 'publication', 'retrieved_at', 'file', 'sha256', 'reuse_terms'];", "private const EVIDENCE_FIELDS = ['id', 'authority', 'issued_on', 'url', 'publication', 'retrieved_at', 'file', 'sha256'];"]]],
    'I9 identities are not version-5 UUIDs' => ['unit', $EK, [["substr(\$hash, 0, 12) . '5' . substr(\$hash, 13, 3)", "substr(\$hash, 0, 12) . '4' . substr(\$hash, 13, 3)"]]],
];
$equivalent = [];
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
