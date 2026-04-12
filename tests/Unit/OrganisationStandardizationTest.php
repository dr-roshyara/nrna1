<?php

namespace Tests\Unit;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Test Suite: Organisation Spelling Standardization
 *
 * Verifies that all tables and models use British English spelling:
 * - "organisation_id" (not "organisation_id")
 * - Consistent across all models and relationships
 */
class OrganisationStandardizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_tables_use_correct_column_name()
    {
        $tables = ['users', 'elections', 'voter_slugs', 'codes', 'posts'];

        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                continue;
            }

            $columns = DB::getSchemaBuilder()->getColumnListing($table);

            // Should NOT have 'organisation_id' (wrong spelling)
            $this->assertNotContains('organisation_id', $columns,
                "❌ Table {$table} still has 'organisation_id' column (should be 'organisation_id')");

            // Should HAVE 'organisation_id' (correct spelling)
            $this->assertContains('organisation_id', $columns,
                "❌ Table {$table} missing 'organisation_id' column");
        }
    }

    /** @test */
    public function no_duplicate_organization_columns()
    {
        // Specifically check elections table which had both columns
        if (DB::getSchemaBuilder()->hasTable('elections')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('elections');

            $this->assertNotContains('organisation_id', $columns,
                "❌ Duplicate organisation_id column still exists in elections table");

            $this->assertContains('organisation_id', $columns,
                "❌ Standard organisation_id column missing from elections table");
        }
    }

    /** @test */
    public function user_model_can_access_organisation_relationship()
    {
        $user = User::factory()->create(['organisation_id' => 0]);

        // This should not throw an error
        $relation = $user->organisation();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
    }

    /** @test */
    public function election_model_can_access_organisation_relationship()
    {
        $election = Election::create([
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'demo',
            'organisation_id' => 0,
        ]);

        // This should not throw an error
        $relation = $election->organisation();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
    }

    /** @test */
    public function creating_model_with_org_id_gets_normalized()
    {
        // Try to create with wrong spelling using mass assignment protection bypass
        $election = new Election();
        $election->name = 'Test';
        $election->type = 'demo';
        $election->slug = 'test-' . uniqid();

        // Simulate someone trying to set the wrong attribute
        $election->attributes['organisation_id'] = 5; // Wrong spelling

        $election->save();

        // Refresh from database
        $election->refresh();

        // Should have saved with correct spelling
        $this->assertEquals(0, $election->organisation_id); // Got 0 from trait default
        $this->assertFalse(isset($election->organisation_id));
    }

    /** @test */
    public function organisation_id_column_is_not_nullable()
    {
        // Check that organisation_id columns are NOT NULL
        $tables = ['users', 'elections', 'voter_slugs', 'codes'];

        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                continue;
            }

            $columns = DB::select("DESCRIBE {$table}");
            $orgIdColumn = collect($columns)->first(function ($col) {
                return $col->Field === 'organisation_id';
            });

            if ($orgIdColumn) {
                $this->assertNotNull($orgIdColumn);
                // Check Null field - should be 'NO'
                $this->assertEquals('NO', $orgIdColumn->Null,
                    "❌ Table {$table}.organisation_id should be NOT NULL but is nullable");
            }
        }
    }

    /** @test */
    public function organisation_id_default_is_zero()
    {
        // Check that organisation_id has default of 0
        $columns = DB::select("DESCRIBE elections");
        $orgIdColumn = collect($columns)->first(function ($col) {
            return $col->Field === 'organisation_id';
        });

        if ($orgIdColumn) {
            $this->assertEquals('0', $orgIdColumn->Default,
                "❌ Table elections.organisation_id should default to 0");
        }
    }

    /** @test */
    public function for_organisation_scope_works_correctly()
    {
        session(['current_organisation_id' => 0]);

        $election = Election::create([
            'name' => 'Platform Election',
            'slug' => 'platform-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => 0,
        ]);

        $found = Election::forOrganisation(0)->where('id', $election->id)->first();

        $this->assertNotNull($found);
        $this->assertEquals(0, $found->organisation_id);
    }

    /** @test */
    public function include_platform_scope_works_correctly()
    {
        session(['current_organisation_id' => 1]);

        $platformElection = Election::create([
            'name' => 'Platform Election',
            'slug' => 'platform-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => 0,
        ]);

        $tenantElection = Election::create([
            'name' => 'Tenant Election',
            'slug' => 'tenant-' . uniqid(),
            'type' => 'real',
            'organisation_id' => 1,
        ]);

        // includePlatform should find only the platform election
        $platformOnly = Election::includePlatform()->get();

        $this->assertTrue($platformOnly->contains('id', $platformElection->id));
        $this->assertFalse($platformOnly->contains('id', $tenantElection->id));
    }
}
