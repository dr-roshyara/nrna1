<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Application\DTOs;

use App\Contexts\Geography\Application\DTOs\GeoUnitSummaryResponse;
use PHPUnit\Framework\TestCase;

final class GeoUnitSummaryResponseTest extends TestCase
{
    private function makeDto(array $overrides = []): GeoUnitSummaryResponse
    {
        return new GeoUnitSummaryResponse(
            id: $overrides['id'] ?? 100,
            adminType: $overrides['adminType'] ?? 'province',
            name: $overrides['name'] ?? 'Province 1',
            code: $overrides['code'] ?? null,
            parentId: $overrides['parentId'] ?? null,
        );
    }

    public function test_it_exposes_required_fields(): void
    {
        $dto = $this->makeDto();

        $this->assertSame(100, $dto->id);
        $this->assertSame('province', $dto->adminType);
        $this->assertSame('Province 1', $dto->name);
        $this->assertNull($dto->code);
        $this->assertNull($dto->parentId);
    }

    public function test_it_accepts_optional_fields(): void
    {
        $dto = $this->makeDto([
            'code' => 'NP-D1',
            'parentId' => 50,
        ]);

        $this->assertSame('NP-D1', $dto->code);
        $this->assertSame(50, $dto->parentId);
    }

    public function test_json_contract_is_stable(): void
    {
        $dto = $this->makeDto([
            'code' => 'NP-P1',
            'parentId' => 1,
        ]);

        $data = $dto->jsonSerialize();

        $this->assertSame(100, $data['id']);
        $this->assertSame('province', $data['adminType']);
        $this->assertSame('Province 1', $data['name']);
        $this->assertSame('NP-P1', $data['code']);
        $this->assertSame(1, $data['parentId']);
    }

    public function test_class_is_immutable_read_model(): void
    {
        $ref = new \ReflectionClass(GeoUnitSummaryResponse::class);

        $this->assertTrue($ref->isFinal());
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_it_is_json_serializable_contract(): void
    {
        $dto = $this->makeDto();

        $this->assertInstanceOf(\JsonSerializable::class, $dto);
        $this->assertNotFalse(json_encode($dto));
    }
}
