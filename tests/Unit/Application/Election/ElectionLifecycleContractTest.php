<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Contracts\ElectionLifecycleContract;
use App\Application\Election\Contracts\ElectionLifecycleFactory;
use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use Tests\TestCase;

class ElectionLifecycleContractTest extends TestCase
{
    /**
     * @test
     * STREAM 3: RED — Contract is bound in service container
     */
    public function contract_is_bound_in_service_container()
    {
        $this->assertTrue($this->app->bound(ElectionLifecycleContract::class));
    }

    /**
     * @test
     * STREAM 3: RED — Contract resolves to factory instance
     */
    public function contract_resolves_to_factory_instance()
    {
        $resolved = $this->app->make(ElectionLifecycleContract::class);
        $this->assertInstanceOf(ElectionLifecycleFactory::class, $resolved);
    }

    /**
     * @test
     * STREAM 3: RED — Contract of() returns ElectionLifecycle instance
     */
    public function contract_of_returns_lifecycle_instance()
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $factory = $this->app->make(ElectionLifecycleContract::class);
        $lifecycle = $factory->of($election);

        $this->assertInstanceOf(\App\Application\Election\Facades\ElectionLifecycle::class, $lifecycle);
    }

    /**
     * @test
     * STREAM 3: RED — Contract of() canVote() returns bool
     */
    public function contract_of_lifecycle_can_vote_returns_bool()
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $factory = $this->app->make(ElectionLifecycleContract::class);
        $canVote = $factory->of($election)->canVote();

        $this->assertIsBool($canVote);
    }
}
