<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Organisation;

class VerifyArchitecture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:architecture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the voting system architecture and consistency';

    private int $passedChecks = 0;
    private int $failedChecks = 0;
    private array $results = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Verifying Public Digit Voting Platform Architecture...');
        $this->newLine();

        // Section 1: Core Foundation
        $this->section('1️⃣  CORE FOUNDATION');
        $this->checkPlatformOrganisation();
        $this->checkTableStructure();

        // Section 2: Tenant Isolation
        $this->section('2️⃣  TENANT ISOLATION');
        $this->checkNoNullOrganisationIds();
        $this->checkOrganisationConsistency();

        // Section 3: Vote Anonymity
        $this->section('3️⃣  VOTE ANONYMITY');
        $this->checkVotesAnonymity();

        // Section 4: Middleware Chain
        $this->section('4️⃣  MIDDLEWARE CHAIN');
        $this->checkMiddlewareFiles();

        // Section 5: Database Performance
        $this->section('5️⃣  DATABASE PERFORMANCE');
        $this->checkPerformanceIndexes();

        // Section 6: Exception Handling
        $this->section('6️⃣  EXCEPTION HANDLING');
        $this->checkExceptionClasses();

        // Summary
        $this->printSummary();

        return $this->failedChecks > 0 ? 1 : 0;
    }

    /**
     * Check if platform organisation exists with ID = 1
     */
    private function checkPlatformOrganisation(): void
    {
        $platformOrg = Organisation::find(1);

        if ($platformOrg) {
            $this->recordPass('✅ Platform organisation exists (ID: 1)');
            if ($platformOrg->slug === 'platform') {
                $this->recordPass('✅ Platform organisation has correct slug');
            } else {
                $this->recordFail('❌ Platform organisation slug incorrect: ' . $platformOrg->slug);
            }
        } else {
            $this->recordFail('❌ Platform organisation (ID: 1) not found!');
        }
    }

    /**
     * Check that all critical tables exist with correct structure
     */
    private function checkTableStructure(): void
    {
        $requiredTables = [
            'organisations',
            'users',
            'elections',
            'posts',
            'codes',
            'voter_slugs',
            'votes',
        ];

        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $this->recordPass("✅ Table exists: {$table}");
            } else {
                $this->recordFail("❌ Table missing: {$table}");
            }
        }
    }

    /**
     * Check that no organisation_id columns have NULL values
     */
    private function checkNoNullOrganisationIds(): void
    {
        $tables = ['users', 'elections', 'posts', 'voter_slugs', 'codes'];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'organisation_id')) {
                $this->warn("⚠️  Table {$table} doesn't have organisation_id column");
                continue;
            }

            $nullCount = DB::table($table)->whereNull('organisation_id')->count();

            if ($nullCount === 0) {
                $this->recordPass("✅ No NULL organisation_id in {$table}");
            } else {
                $this->recordFail("❌ Found {$nullCount} NULL organisation_id in {$table}");
            }
        }
    }

    /**
     * Check organisation consistency between voter_slugs and elections
     */
    private function checkOrganisationConsistency(): void
    {
        $mismatches = DB::table('voter_slugs as vs')
            ->join('elections as e', 'vs.election_id', '=', 'e.id')
            ->whereColumn('vs.organisation_id', '!=', 'e.organisation_id')
            ->where('e.organisation_id', '!=', 1)
            ->where('vs.organisation_id', '!=', 1)
            ->count();

        if ($mismatches === 0) {
            $this->recordPass('✅ Organisation-Election consistency verified (Golden Rule)');
        } else {
            $this->recordFail("❌ Found {$mismatches} organisation mismatches (Golden Rule violation!)");
        }

        // Show some examples
        $examples = DB::table('voter_slugs as vs')
            ->join('elections as e', 'vs.election_id', '=', 'e.id')
            ->select('vs.id', 'vs.organisation_id as voter_org', 'e.id as election_id', 'e.organisation_id as election_org')
            ->limit(3)
            ->get();

        foreach ($examples as $example) {
            $this->line("  Sample: VoterSlug {$example->id} (org {$example->voter_org}) → Election {$example->election_id} (org {$example->election_org})");
        }
    }

    /**
     * Verify votes table has NO user_id column (anonymity guarantee)
     */
    private function checkVotesAnonymity(): void
    {
        if (Schema::hasColumn('votes', 'user_id')) {
            $this->recordFail('❌ CRITICAL: votes table has user_id column! Anonymity violated!');
        } else {
            $this->recordPass('✅ Votes table is anonymous (no user_id)');
        }

        // Check that votes table has vote_hash instead
        if (Schema::hasColumn('votes', 'vote_hash')) {
            $this->recordPass('✅ Votes table has vote_hash for verification');
        } else {
            $this->warn('⚠️  Votes table missing vote_hash column');
        }
    }

    /**
     * Check that all required middleware files exist
     */
    private function checkMiddlewareFiles(): void
    {
        $middlewareFiles = [
            'app/Http/Middleware/VerifyVoterSlug.php',
            'app/Http/Middleware/ValidateVoterSlugWindow.php',
            'app/Http/Middleware/VerifyVoterSlugConsistency.php',
        ];

        foreach ($middlewareFiles as $file) {
            $path = base_path($file);
            if (file_exists($path)) {
                $this->recordPass("✅ Middleware exists: " . basename($file));
            } else {
                $this->recordFail("❌ Middleware missing: {$file}");
            }
        }
    }

    /**
     * Check that all required database indexes exist
     */
    private function checkPerformanceIndexes(): void
    {
        $indexes = [
            'voter_slugs' => ['idx_slug_lookup', 'idx_user_active_expires', 'idx_expires_cleanup'],
            'elections' => ['idx_org_status_date', 'idx_type_status'],
            'codes' => ['idx_code1_lookup', 'idx_user_active'],
        ];

        foreach ($indexes as $table => $requiredIndexes) {
            $existingIndexes = $this->getTableIndexes($table);

            foreach ($requiredIndexes as $indexName) {
                if (in_array($indexName, $existingIndexes)) {
                    $this->recordPass("✅ Index exists: {$table}.{$indexName}");
                } else {
                    $this->recordFail("❌ Index missing: {$table}.{$indexName}");
                }
            }
        }
    }

    /**
     * Check that all exception classes exist
     */
    private function checkExceptionClasses(): void
    {
        $exceptions = [
            'App\Exceptions\Voting\VotingException',
            'App\Exceptions\Voting\ElectionException',
            'App\Exceptions\Voting\VoterSlugException',
            'App\Exceptions\Voting\ConsistencyException',
            'App\Exceptions\Voting\VoteException',
        ];

        foreach ($exceptions as $exception) {
            if (class_exists($exception)) {
                $this->recordPass("✅ Exception exists: " . class_basename($exception));
            } else {
                $this->recordFail("❌ Exception missing: {$exception}");
            }
        }

        // Check handler is configured
        $handler = base_path('app/Exceptions/Handler.php');
        if (file_exists($handler)) {
            $content = file_get_contents($handler);
            if (str_contains($content, 'VotingException')) {
                $this->recordPass('✅ Handler configured for VotingException');
            } else {
                $this->recordFail('❌ Handler not configured for VotingException');
            }
        }
    }

    /**
     * Get all indexes for a table
     */
    private function getTableIndexes(string $table): array
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        $indexNames = array_map(function ($index) {
            return $index->Key_name;
        }, $indexes);

        return array_unique($indexNames);
    }

    /**
     * Record a passing check
     */
    private function recordPass(string $message): void
    {
        $this->line($message);
        $this->passedChecks++;
    }

    /**
     * Record a failing check
     */
    private function recordFail(string $message): void
    {
        $this->error($message);
        $this->failedChecks++;
    }

    /**
     * Print summary
     */
    private function printSummary(): void
    {
        $this->newLine();
        $this->section('VERIFICATION SUMMARY');

        $total = $this->passedChecks + $this->failedChecks;
        $percentage = $total > 0 ? round(($this->passedChecks / $total) * 100) : 0;

        $this->info("Passed:  {$this->passedChecks}");
        $this->info("Failed:  {$this->failedChecks}");
        $this->info("Total:   {$total}");
        $this->info("Score:   {$percentage}%");

        $this->newLine();

        if ($this->failedChecks === 0) {
            $this->info('✅ ARCHITECTURE VERIFICATION PASSED');
            $this->info('All systems operational. System ready for production.');
        } else {
            $this->error('❌ ARCHITECTURE VERIFICATION FAILED');
            $this->error("Fix {$this->failedChecks} issues before proceeding.");
        }

        $this->newLine();
    }

    /**
     * Print section header
     */
    private function section(string $title): void
    {
        $this->newLine();
        $this->info($title);
        $this->line(str_repeat('─', strlen($title)));
    }
}
