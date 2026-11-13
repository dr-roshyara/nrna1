<?php
$b = json_decode(file_get_contents($argv[1]), true);
$titles = ['A-1' => 'A-1 Domain purity', 'A-2' => 'A-2 Application layer', 'A-3' => 'A-3 Cross-context imports', 'A-4' => 'A-4 Delivery boundary', 'A-5' => "A-5 Shared 'tenant' connection (D-222)"];
echo "# PublicDigit architecture deviation inventory\n\n";
echo "**Generated** from `tests/Architecture/baselines/architecture-ratchet.json` (the ratchet baseline) by `docs/publicdigit/architecture/evidence/2026-11-13-architecture-ratchet/inventory.php`. **Do not edit by hand:** fix the code, shrink the baseline, regenerate.\n\n";
echo "Each row is one violation: rule, file, exact dependency, number of occurrences. Only `.php` files are scanned, with comments stripped, so a backup file or a class named in a comment is never counted.\n\n";
echo "**Separately recorded artefact:** `app/Contexts/Membership/Domain/Models/Member.legacy.php.backup` (not executable; outside the scan). Recommendation: delete it with R-4.\n\n";
echo "| Rule | Violations | Files |\n|---|---|---|\n";
$by = [];
foreach ($b as $k => $e) { [$r, $f] = explode('|', $k); $by[$r][$k] = $e; $files[$r][$f] = 1; }
foreach ($titles as $r => $t) echo "| $t | " . count($by[$r] ?? []) . " | " . count($files[$r] ?? []) . " |\n";
foreach ($titles as $r => $t) {
    echo "\n## $t\n\n| File | Dependency | n | Classification | Reason | Remediation | Decision needed |\n|---|---|---|---|---|---|---|\n";
    foreach ($by[$r] ?? [] as $k => $e) {
        [, $f, $d] = explode('|', $k);
        echo '| `' . $f . '` | `' . $d . '` | ' . $e['count'] . ' | ' . $e['class'] . ' | ' . $e['reason'] . ' | ' . $e['remediation'] . ' | ' . $e['decision'] . " |\n";
    }
}
