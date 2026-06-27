<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$org = \App\Models\Organisation::where('slug', 'namaste-nepal-gmbh')->first();
$user = \App\Models\User::where('email', 'restaurant.namastenepal@gmail.com')->first();

$applications = \App\Models\CandidacyApplication::withoutGlobalScopes()
    ->where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->get();

foreach ($applications as $app) {
    echo "Candidacy Application ID: {$app->id}\n";
    echo "  User ID: {$app->user_id}\n";
    echo "  Organisation ID: {$app->organisation_id}\n";
    echo "  Election ID: {$app->election_id}\n";
    echo "  Post ID: {$app->post_id}\n";
    echo "  Status: {$app->status}\n";

    if ($app->election_id) {
        $election = \App\Models\Election::withoutGlobalScopes()->find($app->election_id);
        echo "  Election Found: " . ($election ? $election->name : "NOT FOUND") . "\n";
    } else {
        echo "  Election ID is NULL!\n";
    }
}
