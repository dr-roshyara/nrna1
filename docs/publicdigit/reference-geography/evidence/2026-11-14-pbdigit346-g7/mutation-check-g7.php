<?php
// G-7: mutation run for the CLDR display names (derived from the G-6c full driver) (no source file is modified: each mutant is a modified COPY of one
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
$mutants = [
    'N1 truncation ignored: the chain is the locale and root only' => ['unit', $PL, [["\$current = \$this->parents[\$current] ?? (\$cut === false ? CldrLocale::ROOT : substr(\$current, 0, \$cut));", "\$current = \$this->parents[\$current] ?? CldrLocale::ROOT;"]]],
    'N2 the release parent map is ignored' => ['unit', $PL, [["\$current = \$this->parents[\$current] ?? (", "\$current = (\$this->parents[\$current] ?? null) === '' ? '' : ("]]],
    'N3 root is skipped (treated as the end, not a locale)' => ['unit', $PL, [["            \$chain[] = \$current;\n            if (\$current === CldrLocale::ROOT) {\n                return \$chain;", "            if (\$current === CldrLocale::ROOT) {\n                return \$chain;\n            }\n            \$chain[] = \$current;\n            if (false) {\n                return \$chain;"]]],
    'N4 a short form never falls back to standard' => ['unit', $DN, [["foreach (\$form === self::SHORT ? [self::SHORT, self::STANDARD] : [self::STANDARD] as \$tryForm)", "foreach ([\$form] as \$tryForm)"]]],
    'N5 the fallback is presented as CLDR' => ['unit', $DN, [["DisplayNameOrigin::PlatformFallback, \$requested, null, null,", "DisplayNameOrigin::Cldr, \$requested, null, null,"]]],
    'N6 a withdrawn name counts as a name' => ['unit', $DN, [["if (\$claim !== null && !\$claim->value->isNone()) {", "if (\$claim !== null) {"]]],
    'N7 a CLDR name need not name its release' => ['unit', $RV, [["                if (\$release === null) {\n                    \$out[] = new Violation('D-87_RELEASE'", "                if (false) {\n                    \$out[] = new Violation('D-87_RELEASE'"]]],
    'N8 root accepted as the platform default locale' => ['unit', $RV, [["if (\$value === CldrLocale::ROOT || !CldrLocale::isValid(\$value)) {", "if (!CldrLocale::isValid(\$value)) {"]]],
    'N9 releases compared as text' => ['unit', $CR, [["return version_compare(\$this->value, \$other->value, '>');", "return \$this->value > \$other->value;"]]],
    'N10 the read ignores the knowledge position for names' => ['pg:CldrDisplayNameReadTest', $PGEO, [["\$this->source->slotHistory(SlotKey::cldrName(\$unit, \$l, \$f), \$p)->claimAt(\$t, \$p)", "\$this->source->slotHistory(SlotKey::cldrName(\$unit, \$l, \$f), \$this->source->committedPosition())->claimAt(\$t)"]]],
    'N11 the release map is not read (truncation only)' => ['pg:CldrDisplayNameReadTest', $PGEO, [["\$this->parentLocales(\$t, \$p),", "CldrParentLocales::none(),"]]],
    'N12 the default locale is not read' => ['pg:CldrDisplayNameReadTest', $PGEO, [["\$default === null || \$default->value->isNone() ? null : \$default->value->value());", "null);"]]],
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
