<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$org = \App\Models\Organisation::where('slug', 'namaste-nepal-gmbh')->first();
session(['current_organisation_id' => $org->id]);

$activeElections = \App\Models\Election::withoutGlobalScopes()
    ->where('organisation_id', $org->id)
    ->where('type', 'real')
    ->whereIn('state', ['approved', 'setup_administration', 'setup_nomination', 'ready_for_voting', 'voting_active', 'counting', 'results_published'])
    ->get(['id', 'name', 'state']);

echo "Elections showing in voter hub:\n";
foreach ($activeElections as $e) {
    echo "  ✓ {$e->name} ({$e->state})\n";
}
