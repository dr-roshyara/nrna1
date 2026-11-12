<?php
// Probe: what does a Connection object, held by a repository, reach after its NAME is re-pointed and purged?
require $argv[1].'/vendor/autoload.php';
$app = require $argv[1].'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$dir = $argv[2];
foreach (['a', 'b'] as $x) { @unlink("$dir/$x.sqlite"); touch("$dir/$x.sqlite"); $p = new PDO("sqlite:$dir/$x.sqlite"); $p->exec("CREATE TABLE who (n text)"); $p->exec("INSERT INTO who VALUES ('organisation $x')"); }
$db = $app['db'];
$cfg = fn ($file) => ['driver' => 'sqlite', 'database' => $file, 'prefix' => ''];

// Pattern 1 (one shared name, re-pointed per tenant; the SwitchTenantDatabaseTask pattern)
config(['database.connections.tenant' => $cfg("$dir/a.sqlite")]);
$held = $db->connection('tenant');                       // a repository receives this object while A is current
echo "1. held object, A current: ", $held->selectOne('select n from who')->n, "\n";
config(['database.connections.tenant' => $cfg("$dir/b.sqlite")]); $db->purge('tenant');   // switch to B
echo "1. SAME held object after switch to B: ", $held->selectOne('select n from who')->n, "\n";

// Pattern 2 (one name per organisation; delta C-1)
config(['database.connections.org_a' => $cfg("$dir/a.sqlite"), 'database.connections.org_b' => $cfg("$dir/b.sqlite")]);
$heldA = $db->connection('org_a');
$db->purge('org_a');                                       // end of A's job: purge
$db->connection('org_b');                                  // B's job starts
echo "2. held org_a object after purge and B's job: ", $heldA->selectOne('select n from who')->n, "\n";
