<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Console\Command;

class ListAllElections extends Command
{
    protected $signature = 'elections:list {--type=all}';
    protected $description = 'List all elections with their organisations';

    public function handle()
    {
        $type = $this->option('type');

        $query = Election::with('organisation');

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $elections = $query->orderBy('created_at', 'desc')->get();

        if ($elections->isEmpty()) {
            $this->info("No elections found");
            return;
        }

        $this->info("=== ELECTIONS ===\n");

        foreach ($elections as $election) {
            $orgName = $election->organisation?->name ?? 'DELETED';
            $this->line("Election: {$election->name}");
            $this->line("  ID: {$election->id}");
            $this->line("  Type: {$election->type}");
            $this->line("  Status: {$election->status}");
            $this->line("  Organisation: {$orgName}");
            $this->line("  Org ID: {$election->organisation_id}");
            $this->line("");
        }

        // Summary
        $realCount = $elections->where('type', 'real')->count();
        $demoCount = $elections->where('type', 'demo')->count();
        $this->info("=== SUMMARY ===");
        $this->line("Real elections: {$realCount}");
        $this->line("Demo elections: {$demoCount}");
        $this->line("Total: {$elections->count()}");
    }
}
