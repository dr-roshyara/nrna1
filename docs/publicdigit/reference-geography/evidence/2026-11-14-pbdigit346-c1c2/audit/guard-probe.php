<?php
// Probe (audit, read-only): the REAL rule-7 pattern, read by reflection from the test class, against bypass shapes.
require 'vendor/autoload.php';
require 'tests/Architecture/ReferenceGeographyNoBypassTest.php';
[$pattern] = (new ReflectionClassConstant(Tests\Architecture\ReferenceGeographyNoBypassTest::class, 'RULES'))->getValue()['7 notice writes'];
$cases = [
    'query builder insert'           => "DB::table('refgeo_publications')->insert([...]);",
    'raw SQL insert'                 => 'DB::insert("INSERT INTO refgeo_publication_persons VALUES (1)");',
    'appendNotice call'              => '$l->appendNotice($n);',
    'dynamic table name'             => "\$t = 'refgeo_' . 'publications'; DB::table(\$t)->insert([]);",
    'table name from a constant'     => "DB::table(self::T)->insert([]); // const T = 'refgeo_publications'",
    'builder call split over lines'  => "DB::table('refgeo_publications')\n    ->where('x', 1)\n    ->insert([]);",
    'COPY / unprepared SQL'          => "DB::unprepared('COPY refgeo_publications FROM STDIN');",
    'variable SQL string'            => "\$sql = 'INSERT ' . 'INTO refgeo_publications'; DB::statement(\$sql);",
    'Eloquent model on the table'    => "class P extends Model { protected \$table = 'refgeo_publications'; } P::create([]);",
];
foreach ($cases as $why => $code) {
    printf("%-30s %s\n", $why, preg_match($pattern, $code) === 1 ? 'DETECTED' : 'NOT DETECTED');
}
