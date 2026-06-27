<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrganisationSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $nonAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'owner',
        ]);

        $this->nonAdmin = User::factory()->create(['email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->nonAdmin->id,
            'organisation_id' => $this->org->id,
            'role' => 'member',
        ]);
    }

    /** @test */
    public function admin_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/organisations/{$this->org->slug}/settings");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_membership_mode(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch("/organisations/{$this->org->slug}/settings/membership-mode", [
                'uses_full_membership' => false,
                'confirm_mode_change' => 'on',
            ]);

        $response->assertRedirect();

        $this->org->refresh();
        $this->assertFalse($this->org->uses_full_membership);
    }

    /** @test */
    public function non_admin_cannot_access_settings(): void
    {
        $response = $this->actingAs($this->nonAdmin)
            ->get("/organisations/{$this->org->slug}/settings");

        $response->assertStatus(403);
    }
}
