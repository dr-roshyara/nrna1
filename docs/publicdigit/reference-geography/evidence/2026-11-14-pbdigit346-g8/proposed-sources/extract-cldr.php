<?php
// Extracts CLDR territory display names EXACTLY as each locale defines them in the ICU 77.1 data (CLDR 47), with NO
// ICU fallback (ResourceBundle fallback=false), for de, en, ne; standard and short forms. Codes: World 001, the five
// M49 regions, and the ISO alpha-2 codes listed in the extracted M49 table (used only as a code list for review).
[$_, $m49csv, $out] = $argv;
$codes = ['001', '002', '009', '019', '142', '150'];
foreach (array_map('str_getcsv', file($m49csv)) as $r) { if (count($r) >= 12 && $r[10] !== '') { $codes[] = $r[10]; } }
$fh = fopen($out, 'w'); fputcsv($fh, ['code', 'locale', 'form', 'name']);
$missing = [];
foreach (['en', 'de', 'ne'] as $l) {
    $rb = ResourceBundle::create($l, 'ICUDATA-region', false);
    $std = $rb['Countries']; $short = $rb['Countries%short'];
    foreach ($codes as $c) {
        $v = $std[$c] ?? null;
        if ($v === null) { $missing[] = "$l:$c"; continue; }
        fputcsv($fh, [$c, $l, 'standard', $v]);
        if ($short !== null && ($s = $short[$c] ?? null) !== null) { fputcsv($fh, [$c, $l, 'short', $s]); }
    }
}
fclose($fh);
echo "codes: " . count($codes) . "; missing (locale:code): " . (count($missing) ? implode(' ', $missing) : 'none') . "\n";
