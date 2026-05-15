<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\CommitteeSlugAvailabilityService;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeSlug;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use Tests\TestCase;

final class CommitteeSlugAvailabilityServiceTest extends TestCase
{
    private CommitteeSlugAvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommitteeSlugAvailabilityService::class);
    }

    public function test_check_returns_not_exists_for_available_slug(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertFalse($result->exists);
        $this->assertSame('nrna-icc', $result->slug);
        $this->assertEmpty($result->suggestions);
    }

    public function test_check_returns_exists_for_taken_slug(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc',
            'code' => 'ICC',
        ]);
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $this->assertSame('nrna-icc', $result->slug);
    }

    public function test_check_provides_numeric_suggestions(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc',
            'code' => 'ICC',
        ]);
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $this->assertNotEmpty($result->suggestions);
        $this->assertContains('nrna-icc-2', $result->suggestions);
    }

    public function test_check_skips_taken_numeric_suggestions(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc',
            'code' => 'ICC1',
        ]);
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc-2',
            'code' => 'ICC2',
        ]);
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $this->assertContains('nrna-icc-3', $result->suggestions);
        $this->assertNotContains('nrna-icc-2', $result->suggestions);
    }

    public function test_check_provides_semantic_suggestions(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc',
            'code' => 'ICC',
        ]);
        // Fill numeric slots 2-15 to exhaust numeric suggestions
        for ($i = 2; $i <= 15; $i++) {
            CommitteeModel::factory()->create([
                'organisation_id' => $organisation->id,
                'slug' => "nrna-icc-{$i}",
                'code' => "CODE{$i}",
            ]);
        }
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $suggestions = $result->suggestions;
        $this->assertTrue(
            in_array('nrna-icc-global', $suggestions) ||
            in_array('nrna-icc-central', $suggestions) ||
            in_array('nrna-icc-executive', $suggestions)
        );
    }

    public function test_check_returns_max_three_suggestions(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'test',
            'code' => 'TEST',
        ]);
        $slug = CommitteeSlug::fromString('test');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $this->assertLessThanOrEqual(3, count($result->suggestions));
    }

    public function test_check_is_tenant_scoped(): void
    {
        $org1 = \App\Models\Organisation::factory()->create();
        $org2 = \App\Models\Organisation::factory()->create();

        CommitteeModel::factory()->create([
            'organisation_id' => $org1->id,
            'name' => 'NRNA ICC',
            'slug' => 'nrna-icc',
            'code' => 'ICC',
        ]);

        $slug = CommitteeSlug::fromString('nrna-icc');

        // Same slug available in org2
        $result = $this->service->check($org2->id, $slug);
        $this->assertFalse($result->exists);

        // But taken in org1
        $result = $this->service->check($org1->id, $slug);
        $this->assertTrue($result->exists);
    }

    public function test_check_provides_error_key_for_taken_slug(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        CommitteeModel::factory()->create([
            'organisation_id' => $organisation->id,
            'slug' => 'nrna-icc',
            'code' => 'ICC',
        ]);
        $slug = CommitteeSlug::fromString('nrna-icc');

        $result = $this->service->check($organisation->id, $slug);

        $this->assertTrue($result->exists);
        $this->assertFalse($result->reserved);
        $this->assertSame('committee.slug.taken', $result->errorKey);
    }
}
