<?php

namespace App\Console\Commands;

use App\Models\Election;
use Illuminate\Console\Command;

class ActivateElectionCommand extends Command
{
    protected $signature = 'election:activate {slug}';
    protected $description = 'Temporarily activate election to administration state (bypass approval workflow for testing)';

    public function handle(): int
    {
        $slug = $this->argument('slug');
        $election = Election::withoutGlobalScopes()->where('slug', $slug)->first();

        if (!$election) {
            $this->error("❌ Election with slug '{$slug}' not found");
            return 1;
        }

        $oldState = $election->state;
        $election->update(['state' => 'administration']);

        $this->info("✅ Election '{$election->name}' activated!");
        $this->line("   Slug: {$election->slug}");
        $this->line("   Previous state: {$oldState}");
        $this->line("   New state: administration");
        $this->line("\n📍 Visit: http://localhost:8000/elections/{$election->slug}/management");

        return 0;
    }
}
