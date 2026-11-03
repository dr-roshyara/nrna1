<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Unit\Contexts\Membership\Application\Organisational\MemberRepositoryContract;

/**
 * The PostgreSQL adapter must satisfy the same repository contract as the in-memory
 * reference adapter (Slice 1b contract §8 T1). PBDIGIT-260.
 *
 * Isolation: Tests\TestCase skips per-test transactions on PostgreSQL, so every test
 * uses fresh organisations (tenants). All Membership rows are tenant-scoped.
 */
final class PostgresMemberRepositoryTest extends MembershipPostgresTestCase
{
    use MemberRepositoryContract;

    /** @var array<string, string> label => organisation uuid */
    private array $tenants = [];

    protected function repository(\Closure $clock): MemberRepositoryPort
    {
        return new PostgresMemberRepository(DB::connection(), $clock);
    }

    /**
     * COR-4 step 7 runtime budget: every append is a real transaction, so PostgreSQL checks every 10th bounded
     * history (about 750 of 7,470, about 2,900 appends); the in-memory double checks all of them.
     */
    protected function reloadDifferentialStride(): int
    {
        return 10;
    }

    protected function tenantFor(string $label): TenantId
    {
        if (!isset($this->tenants[$label])) {
            $id = (string) Str::uuid();
            DB::table('organisations')->insert([
                'id' => $id,
                'name' => "Membership 1b test $label",
                'slug' => 'm1b-' . $id,
                'type' => 'tenant',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->tenants[$label] = $id;
        }

        return TenantId::fromString($this->tenants[$label]);
    }
}
