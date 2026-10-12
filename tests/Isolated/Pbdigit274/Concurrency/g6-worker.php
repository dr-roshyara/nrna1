<?php

/**
 * PBDIGIT-274 G6 worker: ONE separate PHP process with its OWN PostgreSQL connection, started by
 * tests/Isolated/Pbdigit274/Database/VoterSlugIssuerRealConcurrencyTest.php. Test code only; never deployed.
 *
 * Usage: php g6-worker.php '<json>'
 *   {"role":"issue","name":"...","voter":"<uuid>","election":"<uuid>","pause":"afterLocks|beforeInsert|null","key":123}
 *   {"role":"cast","name":"...","code":"<uuid>","slug":"<uuid>","pause":"afterCas|null","key":123}
 *
 * - Identity first: tests/bootstrap-isolated-database.php (approved name, raw current_database()), then the
 *   in-app check of every configured connection, exactly like IsolatedCommittedDatabaseTestCase.
 * - Pause points wait on a PostgreSQL advisory lock that the controlling test holds; they fire once.
 * - "cast" is a CAST STAND-IN (decision D2): the same participation update as VoteController.php:1807-1811
 *   (codes CAS) and the same slug marking as :1874-1878, in one transaction. Not the full cast path (S9).
 * - Prints one JSON line on stdout.
 */

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\User;
use App\Services\Voting\VoterSlugIssuer;
use Illuminate\Support\Facades\DB;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

$base = dirname(__DIR__, 4);
require $base.'/tests/bootstrap-isolated-database.php';      // exits non-zero unless the identity is confirmed

$app = require $base.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

config(['database.connections' => DatabaseIdentityGuard::withoutUnconfinedPostgresConnections(config('database.connections'))]);
DatabaseIdentityGuard::confirmBeforeRefresh(
    config('database.connections'),
    DatabaseIdentityGuard::approvedName(getenv(DatabaseIdentityGuard::ENV_NAME)),
    (string) config('database.default'),
    fn () => DB::connection()->selectOne('SELECT current_database() AS name')->name,
);

$job = json_decode($argv[1] ?? '{}', true, flags: JSON_THROW_ON_ERROR);
DB::statement("SELECT set_config('application_name', ?, false)", ['pbd274-g6-'.$job['name']]);

$pause = static function () use ($job): void {
    // Blocks while the controlling test holds the exclusive advisory lock on this key.
    DB::select('SELECT pg_advisory_lock_shared(?)', [$job['key']]);
    DB::select('SELECT pg_advisory_unlock_shared(?)', [$job['key']]);
};

try {
    if ($job['role'] === 'issue') {
        $entitled = new class () implements VotingEntitlementQuery {
            public function standingOf(string $userId, string $electionId): VotingEntitlementReason { return VotingEntitlementReason::Entitled; }
            public function decide(string $userId, string $electionId): VotingEntitlementReason { return VotingEntitlementReason::Entitled; }
        };
        $issuer = new class ($entitled, $job['pause'] ?? null, $pause) extends VoterSlugIssuer {
            private bool $paused = false;

            public function __construct(VotingEntitlementQuery $e, private ?string $at, private \Closure $wait)
            {
                parent::__construct($e);
            }

            protected function afterLocks(): void
            {
                $this->maybePause('afterLocks');
            }

            protected function beforeInsert(): void
            {
                $this->maybePause('beforeInsert');
            }

            private function maybePause(string $point): void
            {
                if ($this->at === $point && ! $this->paused) {
                    $this->paused = true;
                    ($this->wait)();
                }
            }
        };

        $voter = User::withoutGlobalScopes()->findOrFail($job['voter']);
        $election = Election::withoutGlobalScopes()->findOrFail($job['election']);
        $result = $issuer->issue($voter, $election);
        $out = ['role' => 'issue', 'name' => $job['name'], 'outcome' => $result->outcome, 'slug_id' => $result->slug?->id];
    } elseif ($job['role'] === 'cast') {
        $out = DB::transaction(function () use ($job, $pause) {
            // CAST STAND-IN: participation compare-and-set (VoteController.php:1807-1811).
            $consumed = DB::table('codes')->where('id', $job['code'])->where('has_voted', false)->update(['has_voted' => true]);
            if (($job['pause'] ?? null) === 'afterCas') {
                $pause();                                     // holds the codes row lock while paused
            }
            if ($consumed === 1) {
                // Slug marked voted by primary key, as VoterSlug::save() does at :1874-1878 (no soft-delete filter).
                DB::table('voter_slugs')->where('id', $job['slug'])->update(['has_voted' => true, 'status' => 'voted', 'updated_at' => now()]);
            }

            return ['role' => 'cast', 'name' => $job['name'], 'consumed' => $consumed];
        });
    } else {
        throw new InvalidArgumentException('unknown role');
    }
    fwrite(STDOUT, json_encode($out).PHP_EOL);
    exit(0);
} catch (Throwable $e) {
    fwrite(STDOUT, json_encode(['error' => $e::class, 'message' => $e->getMessage()]).PHP_EOL);
    exit(3);
}
