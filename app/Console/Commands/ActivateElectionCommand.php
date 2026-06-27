<?php

namespace App\Console\Commands;

use App\Application\Election\Governance\ElectionStateWriteContext;
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

        // HARDENED: Route through ElectionStateWriteContext authorized boundary
        // This records the mutation to metrics at all levels
        // At Level 4 (full strict), this would require proper guard authorization instead
        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->update(['state' => 'administration']);
        });

        $this->info("✅ Election '{$election->name}' activated!");
        $this->line("   Slug: {$election->slug}");
        $this->line("   Previous state: {$oldState}");
        $this->line("   New state: administration");
        $this->line("   ⚠️  WARNING: This bypassed ConstitutionalTransitionGuard validation!");
        $this->line("   ⚠️  Use only for development/testing. Production transitions require guard authorization.");
        $this->line("\n📍 Visit: http://localhost:8000/elections/{$election->slug}/management");

        return 0;
    }
}
