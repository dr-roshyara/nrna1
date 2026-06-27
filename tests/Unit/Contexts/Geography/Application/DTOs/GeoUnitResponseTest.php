<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Application\DTOs;

use App\Contexts\Geography\Application\DTOs\GeoUnitResponse;
use PHPUnit\Framework\TestCase;

final class GeoUnitResponseTest extends TestCase
{
    private function makeDto(array $overrides = []): GeoUnitResponse
    {
        return new GeoUnitResponse(
            id: $overrides['id'] ?? 100,
            countryCode: $overrides['countryCode'] ?? 'NP',
            adminLevel: $overrides['adminLevel'] ?? 3,
            adminType: $overrides['adminType'] ?? 'province',
            parentId: array_key_exists('parentId', $overrides) ? $overrides['parentId'] : 200,
            code: array_key_exists('code', $overrides) ? $overrides['code'] : 'NP-P1',
            name: $overrides['name'] ?? ['en' => 'Province 1', 'np' => 'प्रदेश १'],
            isActive: $overrides['isActive'] ?? true,
            validFrom: array_key_exists('validFrom', $overrides) ? $overrides['validFrom'] : '2020-01-01T00:00:00+00:00',
            validTo: array_key_exists('validTo', $overrides) ? $overrides['validTo'] : null,
        );
    }

    public function test_it_exposes_all_required_fields(): void
    {
        $dto = $this->makeDto();

        $this->assertSame(100, $dto->id);
        $this->assertSame('NP', $dto->countryCode);
        $this->assertSame(3, $dto->adminLevel);
        $this->assertSame('province', $dto->adminType);
        $this->assertSame(200, $dto->parentId);
        $this->assertSame('NP-P1', $dto->code);
        $this->assertSame(['en' => 'Province 1', 'np' => 'प्रदेश १'], $dto->name);
        $this->assertTrue($dto->isActive);
        $this->assertSame('2020-01-01T00:00:00+00:00', $dto->validFrom);
        $this->assertNull($dto->validTo);
    }

    public function test_it_allows_nullable_fields(): void
    {
        $dto = $this->makeDto([
            'parentId' => null,
            'code' => null,
            'validFrom' => null,
        ]);

        $this->assertNull($dto->parentId);
        $this->assertNull($dto->code);
        $this->assertNull($dto->validFrom);
    }

    public function test_json_contract_is_stable(): void
    {
        $dto = $this->makeDto();

        $json = json_encode($dto);
        $this->assertIsString($json);

        $data = json_decode($json, true);

        $this->assertSame(100, $data['id']);
        $this->assertSame('NP', $data['countryCode']);
        $this->assertSame(3, $data['adminLevel']);
        $this->assertSame('province', $data['adminType']);
        $this->assertSame(200, $data['parentId']);
        $this->assertSame('NP-P1', $data['code']);
        $this->assertSame(['en' => 'Province 1', 'np' => 'प्रदेश १'], $data['name']);
        $this->assertTrue($data['isActive']);
    }

    public function test_it_supports_read_model_contract_semantics(): void
    {
        $dto = $this->makeDto();

        $array = $dto->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('countryCode', $array);
        $this->assertArrayHasKey('adminLevel', $array);
        $this->assertArrayHasKey('adminType', $array);
        $this->assertArrayHasKey('parentId', $array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('isActive', $array);
        $this->assertArrayHasKey('validFrom', $array);
        $this->assertArrayHasKey('validTo', $array);
    }

    public function test_class_is_immutable_read_model(): void
    {
        $ref = new \ReflectionClass(GeoUnitResponse::class);

        $this->assertTrue($ref->isFinal());
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_it_is_json_serializable_contract(): void
    {
        $dto = $this->makeDto();

        $this->assertInstanceOf(\JsonSerializable::class, $dto);
        $this->assertJson(json_encode($dto));
    }
}
