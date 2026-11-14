<?php
// Extracts the ENGLISH M49 table from the saved UNSD overview page (read-only; no network). Output: CSV of the
// columns as published, one row per country or area, plus a summary. Usage: php extract-m49.php <html> <out.csv>
[$_, $html, $out] = $argv;
$doc = new DOMDocument();
@$doc->loadHTML((string) file_get_contents($html));
$xp = new DOMXPath($doc);
$tables = $xp->query('//table');
$picked = null;
foreach ($tables as $t) {
    $head = trim(preg_replace('/\s+/', ' ', $xp->query('.//th', $t)->item(0)?->textContent ?? ''));
    $id = $t->getAttribute('id');
    if ($picked === null && ($id === "downloadTableEN")) { $picked = $t; }
}
if ($picked === null) { fwrite(STDERR, "no English table found; table ids: " . implode(',', array_map(fn($t) => $t->getAttribute('id'), iterator_to_array($tables))) . "\n"); exit(1); }
$heads = array_map(fn($th) => trim(preg_replace('/\s+/', ' ', $th->textContent)), iterator_to_array($xp->query('.//thead//th', $picked)));
$fh = fopen($out, 'w'); fputcsv($fh, $heads);
$n = 0; $regions = [];
foreach ($xp->query('.//tbody/tr', $picked) as $tr) {
    $cells = array_map(fn($td) => trim(preg_replace('/\s+/', ' ', $td->textContent)), iterator_to_array($xp->query('./td', $tr)));
    fputcsv($fh, $cells); $n++;
    $row = array_combine(array_slice(array_pad($heads, count($cells), '?'), 0, count($cells)), $cells);
    $regions[($row['Region Code'] ?? '') . ' ' . ($row['Region Name'] ?? '')] = ($regions[($row['Region Code'] ?? '') . ' ' . ($row['Region Name'] ?? '')] ?? 0) + 1;
}
fclose($fh);
echo "table id: " . $picked->getAttribute('id') . "\ncolumns: " . implode(' | ', $heads) . "\nrows: $n\n";
ksort($regions); foreach ($regions as $r => $c) { echo "  region [" . $r . "]: $c\n"; }
