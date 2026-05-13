<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application;

use App\Contexts\Governance\Application\DTOs\GeoUnitQueryFilter;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GeoUnitQueryFilterTest extends TestCase
{
    #[Test]
    public function from_request_parses_all_parameters(): void
    {
        $filter = GeoUnitQueryFilter::fromRequest([
            'search' => 'nepal',
            'level' => '1',
            'country' => 'np',
        ]);

        $this->assertEquals('nepal', $filter->search);
        $this->assertEquals(1, $filter->level);
        $this->assertEquals('NP', $filter->country);
    }

    #[Test]
    public function from_request_handles_empty_strings_as_null(): void
    {
        $filter = GeoUnitQueryFilter::fromRequest([
            'search' => '',
            'level' => '',
            'country' => '',
        ]);

        $this->assertNull($filter->search);
        $this->assertNull($filter->level);
        $this->assertNull($filter->country);
    }

    #[Test]
    public function from_request_handles_missing_keys_as_null(): void
    {
        $filter = GeoUnitQueryFilter::fromRequest([]);

        $this->assertNull($filter->search);
        $this->assertNull($filter->level);
        $this->assertNull($filter->country);
    }

    #[Test]
    public function to_criteria_returns_only_non_null_values(): void
    {
        $filter = new GeoUnitQueryFilter(search: 'asia', level: null, country: 'NP');
        $criteria = $filter->toCriteria();

        $this->assertArrayHasKey('search', $criteria);
        $this->assertArrayHasKey('country', $criteria);
        $this->assertArrayNotHasKey('level', $criteria);
        $this->assertEquals('asia', $criteria['search']);
        $this->assertEquals('NP', $criteria['country']);
    }

    #[Test]
    public function has_filters_returns_false_when_all_null(): void
    {
        $filter = new GeoUnitQueryFilter();
        $this->assertFalse($filter->hasFilters());
    }

    #[Test]
    public function has_filters_returns_true_when_any_set(): void
    {
        $this->assertTrue((new GeoUnitQueryFilter(search: 'test'))->hasFilters());
        $this->assertTrue((new GeoUnitQueryFilter(level: 1))->hasFilters());
        $this->assertTrue((new GeoUnitQueryFilter(country: 'NP'))->hasFilters());
    }
}
