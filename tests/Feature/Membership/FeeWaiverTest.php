<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeeWaiverTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private \App\Models\Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organisation = \App\Models\Organisation::factory()->create();
        $this->admin = User::factory()
            ->hasAttached($this->organisation, ['role' => 'admin'])
            ->create();
    }

    /** @test */
    public function guest_cannot_waive(): void
    {
        $fee = $this->createTestFee($this->organisation->id);

        $response = $this->patch(
            '/organisations/' . $this->organisation->id . '/members/waive-fees',
            ['fee_ids' => [$fee->id]]
        );

        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_admin_cannot_waive(): void
    {
        $user = User::factory()
            ->hasAttached($this->organisation, ['role' => 'member'])
            ->create();

        $fee = $this->createTestFee($this->organisation->id);

        $response = $this->actingAs($user)->patch(
            '/organisations/' . $this->organisation->id . '/members/waive-fees',
            ['fee_ids' => [$fee->id]]
        );

        $response->assertForbidden();
    }

    /** @test */
    public function waive_changes_status_to_waived(): void
    {
        $fee = $this->createTestFee($this->organisation->id);

        $this->actingAs($this->admin)->patch(
            '/organisations/' . $this->organisation->id . '/members/waive-fees',
            ['fee_ids' => [$fee->id]]
        );

        $fee->refresh();
        $this->assertEquals('waived', $fee->status);
    }

    /** @test */
    public function paid_fee_cannot_be_waived(): void
    {
        $fee = $this->createTestFee($this->organisation->id);
        $fee->update(['status' => 'paid']);

        $response = $this->actingAs($this->admin)->patch(
            '/organisations/' . $this->organisation->id . '/members/waive-fees',
            ['fee_ids' => [$fee->id]]
        );

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function cannot_waive_fee_from_different_tenant(): void
    {
        $otherOrg = \App\Models\Organisation::factory()->create();
        $otherAdmin = User::factory()
            ->hasAttached($otherOrg, ['role' => 'admin'])
            ->create();

        $fee = $this->createTestFee($this->organisation->id);

        $response = $this->actingAs($otherAdmin)->patch(
            '/organisations/' . $otherOrg->id . '/members/waive-fees',
            ['fee_ids' => [$fee->id]]
        );

        $response->assertNotFound();

        $fee->refresh();
        $this->assertEquals('pending', $fee->status);
    }

    private function createTestFee(string $organisationId): \App\Models\MembershipFee
    {
        $member = \App\Models\Member::factory()
            ->for(\App\Models\Organisation::find($organisationId))
            ->create();

        return \App\Models\MembershipFee::factory()
            ->for($member)
            ->for(\App\Models\Organisation::find($organisationId))
            ->create([
                'status' => 'pending',
            ]);
    }
}
