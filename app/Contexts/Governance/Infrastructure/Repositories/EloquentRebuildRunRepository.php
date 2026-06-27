<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Repositories;

use App\Contexts\Governance\Application\Ports\RebuildRunRepository;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;

final class EloquentRebuildRunRepository implements RebuildRunRepository
{
    public function start(string $generation, ?string $tenantId, DateTimeImmutable $now, int $total): void
    {
        DB::table('projection_rebuild_runs')->insert([
            'generation' => $generation,
            'tenant_id' => $tenantId,
            'started_at' => $now,
            'status' => 'running',
            'total_committees' => $total,
            'rebuilt_count' => 0,
            'failed_count' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function complete(string $generation, int $rebuilt, int $failed, DateTimeImmutable $now): void
    {
        DB::table('projection_rebuild_runs')
            ->where('generation', $generation)
            ->update([
                'status' => $failed > 0 ? 'completed_with_errors' : 'completed',
                'rebuilt_count' => $rebuilt,
                'failed_count' => $failed,
                'completed_at' => $now,
                'updated_at' => $now,
            ]);
    }

    public function fail(string $generation, string $error): void
    {
        DB::table('projection_rebuild_runs')
            ->where('generation', $generation)
            ->update([
                'status' => 'failed',
                'error_log' => $error,
                'updated_at' => now(),
            ]);
    }
}
