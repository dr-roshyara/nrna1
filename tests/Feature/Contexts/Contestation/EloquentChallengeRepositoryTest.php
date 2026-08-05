<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Contestation;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Contestation\Infrastructure\Repositories\EloquentChallengeRepository;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-005 Step 5B (RED) — the single-source Eloquent persistence of the Challenge aggregate
 * (Contestation OWNS it; no ACL). The `challenges` table carries the FULL aggregate shape
 * (raise-time columns nullable, ADR ruling), but this reaction slice persists only the
 * reaction-relevant state (state + determination + timestamps); the aggregate stays
 * persistence-ignorant and the mapper is the sole translation point.
 *
 * `findByDeterminationId` is a CORRELATION capability only — identity remains `ChallengeId`.
 * Unique org per test + tenant-scoped assertions (pgsql harness has no per-test rollback).
 */
final class EloquentChallengeRepositoryTest extends TestCase
{
    private string $orgId;
    private string $cid;   // unique challenge id per test (global PK; pgsql harness has no rollback)
    private string $did;   // unique determination id per test

    protected function setUp(): void
    {
        parent::setUp();
        $this->orgId = (string) Organisation::create([
            'name' => 'Contestation persistence org',
            'slug' => 'contestation-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
        TenantContext::set($this->orgId);
        $this->cid = 'ch-'.Str::uuid();
        $this->did = 'det-'.Str::uuid();
    }

    private function repo(): ChallengeRepository
    {
        return $this->app->make(EloquentChallengeRepository::class);
    }

    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:04:00+00:00');
    }

    public function test_saves_and_reconstitutes_challenge_state(): void
    {
        $this->repo()->save(Challenge::reconstitute(ChallengeId::fromString($this->cid), ChallengeState::Routed, null));

        $loaded = $this->repo()->find(ChallengeId::fromString($this->cid));
        $this->assertInstanceOf(Challenge::class, $loaded);
        $this->assertSame(ChallengeState::Routed, $loaded->state());
        $this->assertNull($loaded->adjudicatedDeterminationId());
    }

    public function test_persists_a_reaction_transition_across_reload(): void
    {
        $this->repo()->save(Challenge::reconstitute(ChallengeId::fromString($this->cid), ChallengeState::Routed, null));

        $challenge = $this->repo()->find(ChallengeId::fromString($this->cid));
        $this->assertInstanceOf(Challenge::class, $challenge);
        $challenge->adjudicate(DeterminationId::fromString($this->did), $this->at());
        $this->repo()->save($challenge);

        $reloaded = $this->repo()->find(ChallengeId::fromString($this->cid));
        $this->assertInstanceOf(Challenge::class, $reloaded);
        $this->assertSame(ChallengeState::Adjudicated, $reloaded->state());
        $this->assertSame($this->did, $reloaded->adjudicatedDeterminationId()?->toString());
    }

    public function test_find_by_determination_id_correlates_to_the_adjudicated_challenge(): void
    {
        $this->repo()->save(Challenge::reconstitute(ChallengeId::fromString($this->cid), ChallengeState::Adjudicated, DeterminationId::fromString($this->did)));

        $found = $this->repo()->findByDeterminationId(DeterminationId::fromString($this->did));
        $this->assertInstanceOf(Challenge::class, $found);
        $this->assertSame($this->cid, $found->id()->toString());

        $this->assertNull($this->repo()->findByDeterminationId(DeterminationId::fromString('det-'.Str::uuid())));
    }

    public function test_challenges_are_scoped_to_the_ambient_organisation(): void
    {
        $this->repo()->save(Challenge::reconstitute(ChallengeId::fromString($this->cid), ChallengeState::Routed, null));

        $otherOrg = (string) Organisation::create([
            'name' => 'Other', 'slug' => 'other-'.Str::uuid(), 'type' => 'tenant', 'is_default' => false,
        ])->id;
        TenantContext::set($otherOrg);
        $this->assertNull($this->repo()->find(ChallengeId::fromString($this->cid)), 'another organisation sees no challenge');

        TenantContext::set($this->orgId);
        $this->assertInstanceOf(Challenge::class, $this->repo()->find(ChallengeId::fromString($this->cid)));
    }
}
