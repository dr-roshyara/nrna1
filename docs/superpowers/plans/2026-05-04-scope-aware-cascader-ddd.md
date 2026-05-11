# Scope-Aware Geography Cascader - DDD-First Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enable organisations with different geographic scopes (worldwide, regional, national, sub-country) to use a dynamically-configured geography cascader that respects their scope constraints, with proper DDD architecture across Geography and Membership contexts.

**Architecture:**
- **Geography Context** owns `GeographicScope`, `AdminLevels`, and `OrganisationGeographyConfig` (new aggregate)
- **Membership Context** uses Geography Context to constrain committee creation
- **Frontend** receives `GeographyConfig` DTO and renders scope-appropriate cascader
- **API** provides organisations their config via dedicated endpoint

**Tech Stack:** Laravel 11, Vue 3 + Pinia, DDD patterns, TDD

---

## File Structure

### Geography Context - New Files (DDD)
```
app/Contexts/Geography/Domain/
├── ValueObjects/
│   ├── GeographicScope.php (worldwide|regional|national|sub_country)
│   ├── AdminLevels.php (with validation, getMaxDepth(), getLabel())
│   └── CountryCode.php (NP, DE, AT, etc.)
├── Entities/
│   └── Country.php (aggregate root - id, code, name, admin_levels)
└── Repositories/
    ├── CountryRepositoryInterface.php
    └── Exceptions/
        └── CountryNotFoundException.php

app/Contexts/Geography/Domain/OrganisationGeography/
├── Entities/
│   └── OrganisationGeographyConfig.php (aggregate root)
├── ValueObjects/
│   ├── AllowedCountries.php (collection)
│   └── BaseGeography.php (country_code, region_id)
├── Repositories/
│   └── OrganisationGeographyConfigRepositoryInterface.php
├── Events/
│   └── OrganisationGeographicConfigDefined.php (domain event)
└── Exceptions/
    ├── InvalidGeographicScopeException.php
    └── CountryNotAllowedException.php

app/Contexts/Geography/Application/
├── Queries/
│   ├── GetCountriesForOrganisationQuery.php
│   ├── GetOrganisationGeographyConfigQuery.php
│   └── Handlers/
│       ├── GetCountriesForOrganisationHandler.php
│       └── GetOrganisationGeographyConfigHandler.php
├── Commands/
│   ├── DefineOrganisationGeographicConfigCommand.php
│   └── Handlers/
│       └── DefineOrganisationGeographicConfigHandler.php
└── DTOs/
    ├── CountryDTO.php (code, name, admin_levels)
    ├── AdminLevelDTO.php (level_number, name, local_name)
    └── GeographyConfigDTO.php (scope, allowed_countries, base_country, visible_levels)

app/Contexts/Geography/Infrastructure/
├── Repositories/
│   ├── EloquentCountryRepository.php
│   └── EloquentOrganisationGeographyConfigRepository.php
├── Persistence/Models/
│   ├── CountryModel.php (Eloquent model with admin_levels JSON)
│   └── OrganisationGeographyConfigModel.php
└── Providers/
    └── GeographyContextProvider.php (bind interfaces)

app/Http/Controllers/Geography/
├── OrganisationGeographyController.php (show config for organisation)
└── CountriesController.php (list countries for organisation)
```

### Membership Context - Updates
```
app/Contexts/Membership/Domain/Committee/
├── Events/
│   └── CommitteeCreated.php (includes geo_reference)
└── Repositories/
    └── CommitteeRepositoryInterface.php (validate geo_reference via Geography)

app/Contexts/Membership/Application/Committee/
├── Commands/
│   └── CreateCommitteeCommand.php (add geo_reference validation)
└── Handlers/
    └── CreateCommitteeHandler.php (use Geography Context to validate)
```

### Frontend - New Files
```
resources/js/Services/
└── geographyService.ts (fetch countries, config via API)

resources/js/Types/
├── Geography.ts (CountryDTO, AdminLevelDTO, GeographyConfigDTO)
└── Organisation.ts (update with geographic_scope)

resources/js/tests/
├── services/geographyService.test.ts
├── components/GeographyCascader.scope.test.ts
└── stores/geographyStore.scope.test.ts
```

### Database
```
Migrations (create if missing):
- Add admin_levels JSON column to countries table
- Create organisation_geography_configs table
```

---

## Phase A: Geography Context - Domain Foundation

### Task A1: Create GeographicScope Value Object

**Files:**
- Create: `app/Contexts/Geography/Domain/ValueObjects/GeographicScope.php`

- [ ] **Step 1: Write test for GeographicScope**

Create `tests/Unit/Contexts/Geography/Domain/ValueObjects/GeographicScopeTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\ValueObjects;

use App\Contexts\Geography\Domain\ValueObjects\GeographicScope;
use PHPUnit\Framework\TestCase;

class GeographicScopeTest extends TestCase
{
    public function test_can_create_worldwide_scope()
    {
        $scope = GeographicScope::worldwide();
        
        $this->assertTrue($scope->isWorldwide());
        $this->assertFalse($scope->isNational());
        $this->assertFalse($scope->isRegional());
        $this->assertFalse($scope->isSubCountry());
    }

    public function test_can_create_national_scope()
    {
        $scope = GeographicScope::national();
        
        $this->assertFalse($scope->isWorldwide());
        $this->assertTrue($scope->isNational());
    }

    public function test_can_create_regional_scope()
    {
        $scope = GeographicScope::regional();
        
        $this->assertTrue($scope->isRegional());
    }

    public function test_can_create_sub_country_scope()
    {
        $scope = GeographicScope::subCountry();
        
        $this->assertTrue($scope->isSubCountry());
    }

    public function test_throws_for_invalid_scope()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        GeographicScope::fromString('invalid_scope');
    }

    public function test_can_convert_to_string()
    {
        $scope = GeographicScope::worldwide();
        
        $this->assertEquals('worldwide', (string) $scope);
    }

    public function test_equal_scopes_are_equal()
    {
        $scope1 = GeographicScope::worldwide();
        $scope2 = GeographicScope::worldwide();
        
        $this->assertTrue($scope1->equals($scope2));
    }
}
```

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/ValueObjects/GeographicScopeTest.php`

Expected: FAIL (class doesn't exist)

- [ ] **Step 2: Implement GeographicScope value object**

Create `app/Contexts/Geography/Domain/ValueObjects/GeographicScope.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class GeographicScope
{
    private const WORLDWIDE = 'worldwide';
    private const REGIONAL = 'regional';
    private const NATIONAL = 'national';
    private const SUB_COUNTRY = 'sub_country';

    private const VALID_SCOPES = [
        self::WORLDWIDE,
        self::REGIONAL,
        self::NATIONAL,
        self::SUB_COUNTRY,
    ];

    public function __construct(private string $value)
    {
        if (!in_array($value, self::VALID_SCOPES, true)) {
            throw new InvalidArgumentException("Invalid geographic scope: {$value}");
        }
    }

    public static function worldwide(): self
    {
        return new self(self::WORLDWIDE);
    }

    public static function regional(): self
    {
        return new self(self::REGIONAL);
    }

    public static function national(): self
    {
        return new self(self::NATIONAL);
    }

    public static function subCountry(): self
    {
        return new self(self::SUB_COUNTRY);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function isWorldwide(): bool
    {
        return $this->value === self::WORLDWIDE;
    }

    public function isRegional(): bool
    {
        return $this->value === self::REGIONAL;
    }

    public function isNational(): bool
    {
        return $this->value === self::NATIONAL;
    }

    public function isSubCountry(): bool
    {
        return $this->value === self::SUB_COUNTRY;
    }

    public function equals(GeographicScope $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

- [ ] **Step 3: Run test to verify pass**

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/ValueObjects/GeographicScopeTest.php`

Expected: PASS

- [ ] **Step 4: Commit**

```bash
git add app/Contexts/Geography/Domain/ValueObjects/GeographicScope.php tests/Unit/Contexts/Geography/Domain/ValueObjects/GeographicScopeTest.php
git commit -m "feat: add GeographicScope value object to Geography context"
```

---

### Task A2: Create AdminLevels Value Object

**Files:**
- Create: `app/Contexts/Geography/Domain/ValueObjects/AdminLevels.php`

- [ ] **Step 1: Write test for AdminLevels**

Create `tests/Unit/Contexts/Geography/Domain/ValueObjects/AdminLevelsTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\ValueObjects;

use App\Contexts\Geography\Domain\ValueObjects\AdminLevels;
use PHPUnit\Framework\TestCase;

class AdminLevelsTest extends TestCase
{
    private array $nepaliLevels = [
        1 => ['name' => 'Province', 'local_name' => 'प्रदेश'],
        2 => ['name' => 'District', 'local_name' => 'जिल्ला'],
        3 => ['name' => 'Municipality', 'local_name' => 'नगरपालिका'],
        4 => ['name' => 'Ward', 'local_name' => 'वडा'],
    ];

    public function test_can_create_from_array()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->assertInstanceOf(AdminLevels::class, $levels);
    }

    public function test_throws_for_missing_name()
    {
        $invalid = [
            1 => ['local_name' => 'Province'],
        ];
        
        $this->expectException(\InvalidArgumentException::class);
        AdminLevels::fromArray($invalid);
    }

    public function test_throws_for_missing_local_name()
    {
        $invalid = [
            1 => ['name' => 'Province'],
        ];
        
        $this->expectException(\InvalidArgumentException::class);
        AdminLevels::fromArray($invalid);
    }

    public function test_get_max_depth()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->assertEquals(4, $levels->getMaxDepth());
    }

    public function test_get_label_in_english()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->assertEquals('Province', $levels->getLabel(1, 'en'));
        $this->assertEquals('District', $levels->getLabel(2, 'en'));
    }

    public function test_get_label_in_local_language()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->assertEquals('प्रदेश', $levels->getLabel(1, 'np'));
        $this->assertEquals('जिल्ला', $levels->getLabel(2, 'np'));
    }

    public function test_throws_for_invalid_level()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->expectException(\InvalidArgumentException::class);
        $levels->getLabel(99, 'en');
    }

    public function test_get_visible_level_numbers()
    {
        $levels = AdminLevels::fromArray($this->nepaliLevels);
        
        $this->assertEquals([1, 2, 3, 4], $levels->getVisibleLevels());
    }

    public function test_handles_non_sequential_levels()
    {
        $levels = AdminLevels::fromArray([
            1 => ['name' => 'Country', 'local_name' => 'देश'],
            3 => ['name' => 'Region', 'local_name' => 'क्षेत्र'],
            5 => ['name' => 'Village', 'local_name' => 'गाउँ'],
        ]);
        
        $this->assertEquals([1, 3, 5], $levels->getVisibleLevels());
        $this->assertEquals(5, $levels->getMaxDepth());
    }
}
```

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/ValueObjects/AdminLevelsTest.php`

Expected: FAIL

- [ ] **Step 2: Implement AdminLevels**

Create `app/Contexts/Geography/Domain/ValueObjects/AdminLevels.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class AdminLevels
{
    /**
     * @param array<int, array{name: string, local_name: string}> $levels
     */
    private function __construct(private array $levels)
    {
    }

    /**
     * @param array<int, array{name: string, local_name: string}> $data
     */
    public static function fromArray(array $data): self
    {
        foreach ($data as $level => $config) {
            if (!isset($config['name'], $config['local_name'])) {
                throw new InvalidArgumentException(
                    "Level {$level} missing required fields: name, local_name"
                );
            }
            if (!is_string($config['name']) || !is_string($config['local_name'])) {
                throw new InvalidArgumentException(
                    "Level {$level} fields must be strings"
                );
            }
        }

        return new self($data);
    }

    public function getMaxDepth(): int
    {
        if (empty($this->levels)) {
            return 0;
        }

        return max(array_keys($this->levels));
    }

    /**
     * @param 'en'|'np' $locale
     */
    public function getLabel(int $level, string $locale = 'en'): string
    {
        if (!isset($this->levels[$level])) {
            throw new InvalidArgumentException("Level {$level} not defined");
        }

        $key = $locale === 'en' ? 'name' : 'local_name';

        return $this->levels[$level][$key];
    }

    /**
     * Get sorted array of level numbers
     *
     * @return array<int>
     */
    public function getVisibleLevels(): array
    {
        return array_keys($this->levels);
    }

    /**
     * Get raw levels array
     *
     * @return array<int, array{name: string, local_name: string}>
     */
    public function toArray(): array
    {
        return $this->levels;
    }
}
```

- [ ] **Step 3: Run test to verify pass**

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/ValueObjects/AdminLevelsTest.php`

Expected: PASS

- [ ] **Step 4: Commit**

```bash
git add app/Contexts/Geography/Domain/ValueObjects/AdminLevels.php tests/Unit/Contexts/Geography/Domain/ValueObjects/AdminLevelsTest.php
git commit -m "feat: add AdminLevels value object with validation"
```

---

### Task A3: Create OrganisationGeographyConfig Aggregate

**Files:**
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfig.php`
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/AllowedCountries.php`
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/BaseGeography.php`

- [ ] **Step 1: Write tests for value objects**

Create `tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/AllowedCountriesTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\OrganisationGeography\ValueObjects;

use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\AllowedCountries;
use PHPUnit\Framework\TestCase;

class AllowedCountriesTest extends TestCase
{
    public function test_can_create_from_array()
    {
        $countries = AllowedCountries::fromArray(['DE', 'AT', 'CH']);
        
        $this->assertInstanceOf(AllowedCountries::class, $countries);
        $this->assertEquals(['AT', 'CH', 'DE'], $countries->toArray()); // sorted
    }

    public function test_can_create_empty()
    {
        $countries = AllowedCountries::empty();
        
        $this->assertEquals([], $countries->toArray());
    }

    public function test_contains_country()
    {
        $countries = AllowedCountries::fromArray(['DE', 'AT']);
        
        $this->assertTrue($countries->contains('DE'));
        $this->assertFalse($countries->contains('NP'));
    }

    public function test_throws_for_invalid_country_code()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        AllowedCountries::fromArray(['INVALID']);
    }

    public function test_deduplicate_countries()
    {
        $countries = AllowedCountries::fromArray(['DE', 'DE', 'AT']);
        
        $this->assertEquals(['AT', 'DE'], $countries->toArray());
    }
}
```

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/AllowedCountriesTest.php`

Expected: FAIL

- [ ] **Step 2: Implement AllowedCountries value object**

Create `app/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/AllowedCountries.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects;

use InvalidArgumentException;

final readonly class AllowedCountries
{
    private const VALID_COUNTRY_CODES = ['NP', 'DE', 'AT', 'CH', 'US', 'IN', 'GB'];

    /**
     * @param array<string> $codes
     */
    private function __construct(private array $codes)
    {
    }

    /**
     * @param array<string> $codes
     */
    public static function fromArray(array $codes): self
    {
        $validated = [];

        foreach ($codes as $code) {
            if (!is_string($code)) {
                throw new InvalidArgumentException('Country code must be string');
            }

            $code = strtoupper($code);

            if (!in_array($code, self::VALID_COUNTRY_CODES, true)) {
                throw new InvalidArgumentException("Invalid country code: {$code}");
            }

            $validated[] = $code;
        }

        // Deduplicate and sort
        $validated = array_unique($validated);
        sort($validated);

        return new self($validated);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function contains(string $code): bool
    {
        return in_array(strtoupper($code), $this->codes, true);
    }

    public function isEmpty(): bool
    {
        return empty($this->codes);
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return $this->codes;
    }
}
```

- [ ] **Step 3: Run tests and verify pass**

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/AllowedCountriesTest.php`

Expected: PASS

- [ ] **Step 4: Write test for BaseGeography**

Create `tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/BaseGeographyTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\OrganisationGeography\ValueObjects;

use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\BaseGeography;
use PHPUnit\Framework\TestCase;

class BaseGeographyTest extends TestCase
{
    public function test_can_create_with_country_only()
    {
        $base = BaseGeography::fromCountry('NP');
        
        $this->assertEquals('NP', $base->countryCode());
        $this->assertNull($base->regionId());
    }

    public function test_can_create_with_country_and_region()
    {
        $base = BaseGeography::fromCountryAndRegion('NP', 3);
        
        $this->assertEquals('NP', $base->countryCode());
        $this->assertEquals(3, $base->regionId());
    }

    public function test_throws_for_invalid_country()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        BaseGeography::fromCountry('INVALID');
    }
}
```

- [ ] **Step 5: Implement BaseGeography**

Create `app/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/BaseGeography.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects;

use InvalidArgumentException;

final readonly class BaseGeography
{
    private const VALID_CODES = ['NP', 'DE', 'AT', 'CH', 'US', 'IN', 'GB'];

    private function __construct(
        private string $countryCode,
        private ?int $regionId = null,
    ) {
    }

    public static function fromCountry(string $code): self
    {
        $code = strtoupper($code);
        if (!in_array($code, self::VALID_CODES, true)) {
            throw new InvalidArgumentException("Invalid country code: {$code}");
        }

        return new self($code);
    }

    public static function fromCountryAndRegion(string $code, int $regionId): self
    {
        $code = strtoupper($code);
        if (!in_array($code, self::VALID_CODES, true)) {
            throw new InvalidArgumentException("Invalid country code: {$code}");
        }

        return new self($code, $regionId);
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function regionId(): ?int
    {
        return $this->regionId;
    }
}
```

- [ ] **Step 6: Run tests and commit**

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/`

Expected: PASS

```bash
git add app/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/ tests/Unit/Contexts/Geography/Domain/OrganisationGeography/ValueObjects/
git commit -m "feat: add AllowedCountries and BaseGeography value objects"
```

---

### Task A4: Create OrganisationGeographyConfig Aggregate

**Files:**
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfig.php`

- [ ] **Step 1: Write test for aggregate**

Create `tests/Unit/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfigTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Geography\Domain\OrganisationGeography\Entities;

use App\Contexts\Geography\Domain\OrganisationGeography\Entities\OrganisationGeographyConfig;
use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\AllowedCountries;
use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\BaseGeography;
use App\Contexts\Geography\Domain\ValueObjects\GeographicScope;
use PHPUnit\Framework\TestCase;

class OrganisationGeographyConfigTest extends TestCase
{
    public function test_can_create_worldwide_config()
    {
        $config = OrganisationGeographyConfig::worldwide('org-123');
        
        $this->assertEquals('org-123', $config->organisationId());
        $this->assertTrue($config->scope()->isWorldwide());
        $this->assertTrue($config->allowedCountries()->isEmpty());
        $this->assertNull($config->baseGeography());
    }

    public function test_can_create_national_config()
    {
        $base = BaseGeography::fromCountry('NP');
        $config = OrganisationGeographyConfig::national('org-123', $base);
        
        $this->assertTrue($config->scope()->isNational());
        $this->assertEquals('NP', $config->baseGeography()->countryCode());
    }

    public function test_can_create_regional_config()
    {
        $allowed = AllowedCountries::fromArray(['DE', 'AT']);
        $base = BaseGeography::fromCountry('DE');
        $config = OrganisationGeographyConfig::regional('org-123', $allowed, $base);
        
        $this->assertTrue($config->scope()->isRegional());
        $this->assertTrue($config->allowedCountries()->contains('DE'));
        $this->assertTrue($config->allowedCountries()->contains('AT'));
    }

    public function test_can_create_sub_country_config()
    {
        $base = BaseGeography::fromCountryAndRegion('NP', 3);
        $config = OrganisationGeographyConfig::subCountry('org-123', $base);
        
        $this->assertTrue($config->scope()->isSubCountry());
        $this->assertEquals(3, $config->baseGeography()->regionId());
    }

    public function test_validates_regional_has_at_least_one_country()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $allowed = AllowedCountries::empty();
        $base = BaseGeography::fromCountry('DE');
        OrganisationGeographyConfig::regional('org-123', $allowed, $base);
    }

    public function test_validates_base_geography_required_for_scoped()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        OrganisationGeographyConfig::national('org-123', null);
    }
}
```

- [ ] **Step 2: Implement OrganisationGeographyConfig aggregate**

Create `app/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfig.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\OrganisationGeography\Entities;

use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\AllowedCountries;
use App\Contexts\Geography\Domain\OrganisationGeography\ValueObjects\BaseGeography;
use App\Contexts\Geography\Domain\ValueObjects\GeographicScope;
use InvalidArgumentException;

final class OrganisationGeographyConfig
{
    private function __construct(
        private readonly string $organisationId,
        private readonly GeographicScope $scope,
        private readonly AllowedCountries $allowedCountries,
        private readonly ?BaseGeography $baseGeography,
    ) {
    }

    public static function worldwide(string $organisationId): self
    {
        return new self(
            $organisationId,
            GeographicScope::worldwide(),
            AllowedCountries::empty(),
            null,
        );
    }

    public static function national(string $organisationId, ?BaseGeography $baseGeography): self
    {
        if ($baseGeography === null) {
            throw new InvalidArgumentException('National scope requires baseGeography');
        }

        return new self(
            $organisationId,
            GeographicScope::national(),
            AllowedCountries::empty(),
            $baseGeography,
        );
    }

    public static function regional(
        string $organisationId,
        AllowedCountries $allowedCountries,
        ?BaseGeography $baseGeography,
    ): self {
        if ($baseGeography === null) {
            throw new InvalidArgumentException('Regional scope requires baseGeography');
        }

        if ($allowedCountries->isEmpty()) {
            throw new InvalidArgumentException('Regional scope requires at least one allowed country');
        }

        return new self(
            $organisationId,
            GeographicScope::regional(),
            $allowedCountries,
            $baseGeography,
        );
    }

    public static function subCountry(string $organisationId, ?BaseGeography $baseGeography): self
    {
        if ($baseGeography === null) {
            throw new InvalidArgumentException('Sub-country scope requires baseGeography');
        }

        if ($baseGeography->regionId() === null) {
            throw new InvalidArgumentException('Sub-country scope requires regionId in baseGeography');
        }

        return new self(
            $organisationId,
            GeographicScope::subCountry(),
            AllowedCountries::empty(),
            $baseGeography,
        );
    }

    public function organisationId(): string
    {
        return $this->organisationId;
    }

    public function scope(): GeographicScope
    {
        return $this->scope;
    }

    public function allowedCountries(): AllowedCountries
    {
        return $this->allowedCountries;
    }

    public function baseGeography(): ?BaseGeography
    {
        return $this->baseGeography;
    }

    public function shouldShowCountrySelector(): bool
    {
        return $this->scope->isWorldwide() || $this->scope->isRegional();
    }

    public function getCountriesToShow(): ?array
    {
        if ($this->scope->isWorldwide()) {
            return null; // All countries
        }

        if ($this->scope->isRegional()) {
            return $this->allowedCountries->toArray();
        }

        // National or sub-country: show only base country
        return [$this->baseGeography?->countryCode()];
    }

    public function isCountryAllowed(string $code): bool
    {
        $code = strtoupper($code);

        if ($this->scope->isWorldwide()) {
            return true;
        }

        if ($this->scope->isRegional()) {
            return $this->allowedCountries->contains($code);
        }

        // National or sub-country
        return $code === $this->baseGeography?->countryCode();
    }
}
```

- [ ] **Step 3: Run tests and commit**

Run: `php artisan test tests/Unit/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfigTest.php`

Expected: PASS

```bash
git add app/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfig.php tests/Unit/Contexts/Geography/Domain/OrganisationGeography/Entities/OrganisationGeographyConfigTest.php
git commit -m "feat: add OrganisationGeographyConfig aggregate with validation"
```

---

### Task A5: Create Domain Event

**Files:**
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/Events/OrganisationGeographicConfigDefined.php`

- [ ] **Step 1: Implement domain event**

Create `app/Contexts/Geography/Domain/OrganisationGeography/Events/OrganisationGeographicConfigDefined.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\OrganisationGeography\Events;

use App\Contexts\Shared\Domain\Events\DomainEvent;

final readonly class OrganisationGeographicConfigDefined implements DomainEvent
{
    public function __construct(
        public string $organisationId,
        public string $scope,
        public array $allowedCountries,
        public ?string $baseCountryCode,
        public ?int $baseRegionId,
        public string $occurredAt,
    ) {
    }

    public static function from(
        string $organisationId,
        string $scope,
        array $allowedCountries,
        ?string $baseCountryCode,
        ?int $baseRegionId,
    ): self {
        return new self(
            organisationId: $organisationId,
            scope: $scope,
            allowedCountries: $allowedCountries,
            baseCountryCode: $baseCountryCode,
            baseRegionId: $baseRegionId,
            occurredAt: now()->toIso8601String(),
        );
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Contexts/Geography/Domain/OrganisationGeography/Events/OrganisationGeographicConfigDefined.php
git commit -m "feat: add OrganisationGeographicConfigDefined domain event"
```

---

## Phase B: Infrastructure Layer - Repositories & DTOs

### Task B1: Create Repository Interfaces

**Files:**
- Create: `app/Contexts/Geography/Domain/OrganisationGeography/Repositories/OrganisationGeographyConfigRepositoryInterface.php`

- [ ] **Step 1: Implement interface**

Create the file:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\OrganisationGeography\Repositories;

use App\Contexts\Geography\Domain\OrganisationGeography\Entities\OrganisationGeographyConfig;

interface OrganisationGeographyConfigRepositoryInterface
{
    public function save(OrganisationGeographyConfig $config): void;

    public function findByOrganisationId(string $organisationId): ?OrganisationGeographyConfig;

    public function delete(string $organisationId): void;
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Contexts/Geography/Domain/OrganisationGeography/Repositories/OrganisationGeographyConfigRepositoryInterface.php
git commit -m "feat: add OrganisationGeographyConfigRepository interface"
```

---

### Task B2: Create DTOs for Frontend

**Files:**
- Create: `app/Contexts/Geography/Application/DTOs/CountryDTO.php`
- Create: `app/Contexts/Geography/Application/DTOs/AdminLevelDTO.php`
- Create: `app/Contexts/Geography/Application/DTOs/GeographyConfigDTO.php`

- [ ] **Step 1: Implement CountryDTO**

Create `app/Contexts/Geography/Application/DTOs/CountryDTO.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

final readonly class CountryDTO
{
    /**
     * @param array<int, AdminLevelDTO> $adminLevels
     */
    public function __construct(
        public string $code,
        public string $name,
        public array $adminLevels,
    ) {
    }
}
```

- [ ] **Step 2: Implement AdminLevelDTO**

Create `app/Contexts/Geography/Application/DTOs/AdminLevelDTO.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

final readonly class AdminLevelDTO
{
    public function __construct(
        public int $level,
        public string $name,
        public string $localName,
    ) {
    }
}
```

- [ ] **Step 3: Implement GeographyConfigDTO**

Create `app/Contexts/Geography/Application/DTOs/GeographyConfigDTO.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

final readonly class GeographyConfigDTO
{
    /**
     * @param array<string> $visibleLevels Level numbers to show (e.g., [1, 2, 3, 4])
     * @param array<string>|null $allowedCountries null means all countries allowed
     * @param string|null $baseCountryCode Country to pre-select, if any
     */
    public function __construct(
        public string $scope,
        public bool $showCountrySelector,
        public ?array $allowedCountries,
        public ?string $baseCountryCode,
        public array $visibleLevels,
    ) {
    }
}
```

- [ ] **Step 4: Commit**

```bash
git add app/Contexts/Geography/Application/DTOs/
git commit -m "feat: add Geography DTOs for frontend"
```

---

### Task B3: Create Application Queries & Handlers

**Files:**
- Create: `app/Contexts/Geography/Application/Queries/GetOrganisationGeographyConfigQuery.php`
- Create: `app/Contexts/Geography/Application/Queries/Handlers/GetOrganisationGeographyConfigHandler.php`

- [ ] **Step 1: Implement query**

Create `app/Contexts/Geography/Application/Queries/GetOrganisationGeographyConfigQuery.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Queries;

final readonly class GetOrganisationGeographyConfigQuery
{
    public function __construct(public string $organisationId)
    {
    }
}
```

- [ ] **Step 2: Implement handler**

Create `app/Contexts/Geography/Application/Queries/Handlers/GetOrganisationGeographyConfigHandler.php`:

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Queries\Handlers;

use App\Contexts\Geography\Application\DTOs\GeographyConfigDTO;
use App\Contexts\Geography\Application\Queries\GetOrganisationGeographyConfigQuery;
use App\Contexts\Geography\Domain\OrganisationGeography\Repositories\OrganisationGeographyConfigRepositoryInterface;
use App\Models\Organisation;

final readonly class GetOrganisationGeographyConfigHandler
{
    public function __construct(
        private OrganisationGeographyConfigRepositoryInterface $repository,
    ) {
    }

    public function handle(GetOrganisationGeographyConfigQuery $query): GeographyConfigDTO
    {
        $config = $this->repository->findByOrganisationId($query->organisationId);

        // Fallback to legacy organisation model if not found
        if ($config === null) {
            $org = Organisation::findOrFail($query->organisationId);

            return $this->createFromLegacyOrganisation($org);
        }

        return new GeographyConfigDTO(
            scope: (string) $config->scope(),
            showCountrySelector: $config->shouldShowCountrySelector(),
            allowedCountries: $config->scope()->isRegional()
                ? $config->allowedCountries()->toArray()
                : null,
            baseCountryCode: $config->baseGeography()?->countryCode(),
            visibleLevels: [1, 2, 3, 4], // TODO: Load from countries table
        );
    }

    private function createFromLegacyOrganisation(Organisation $org): GeographyConfigDTO
    {
        // Temporary: map legacy columns to DTOs
        $scope = $org->geographic_scope ?? 'national';
        $baseCountryCode = $org->base_country_code ?? 'NP';

        return new GeographyConfigDTO(
            scope: $scope,
            showCountrySelector: $scope === 'worldwide' || $scope === 'regional',
            allowedCountries: $scope === 'regional' ? $org->allowed_countries : null,
            baseCountryCode: $baseCountryCode,
            visibleLevels: [1, 2, 3, 4], // TODO: Load from countries table
        );
    }
}
```

- [ ] **Step 3: Commit**

```bash
git add app/Contexts/Geography/Application/Queries/GetOrganisationGeographyConfigQuery.php app/Contexts/Geography/Application/Queries/Handlers/GetOrganisationGeographyConfigHandler.php
git commit -m "feat: add GetOrganisationGeographyConfig query and handler"
```

---

## Phase C: HTTP Layer - API Endpoints

### Task C1: Create OrganisationGeographyController

**Files:**
- Create: `app/Http/Controllers/Geography/OrganisationGeographyController.php`

- [ ] **Step 1: Implement controller**

Create `app/Http/Controllers/Geography/OrganisationGeographyController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Geography;

use App\Contexts\Geography\Application\Queries\GetOrganisationGeographyConfigQuery;
use App\Contexts\Geography\Application\Queries\Handlers\GetOrganisationGeographyConfigHandler;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Http\JsonResponse;

final class OrganisationGeographyController extends Controller
{
    public function __construct(
        private readonly GetOrganisationGeographyConfigHandler $handler,
    ) {
    }

    public function show(Organisation $organisation): JsonResponse
    {
        $this->authorize('view', $organisation);

        $query = new GetOrganisationGeographyConfigQuery($organisation->id);
        $config = $this->handler->handle($query);

        return response()->json([
            'data' => [
                'scope' => $config->scope,
                'show_country_selector' => $config->showCountrySelector,
                'allowed_countries' => $config->allowedCountries,
                'base_country_code' => $config->baseCountryCode,
                'visible_levels' => $config->visibleLevels,
            ],
        ]);
    }
}
```

- [ ] **Step 2: Add route**

In `routes/committee/committeeRoutes.php`, add:

```php
Route::get('/organisations/{organisation}/geography/config', 
    [\App\Http\Controllers\Geography\OrganisationGeographyController::class, 'show'])
    ->name('organisation.geography.config');
```

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/Geography/OrganisationGeographyController.php routes/committee/committeeRoutes.php
git commit -m "feat: add API endpoint for organisation geography config"
```

---

## Phase D: Frontend Integration

### Task D1: Create GeographyService TypeScript

**Files:**
- Create: `resources/js/Services/geographyService.ts`
- Create: `resources/js/Types/Geography.ts`

- [ ] **Step 1: Create types**

Create `resources/js/Types/Geography.ts`:

```typescript
export interface AdminLevelDTO {
  level: number
  name: string
  localName: string
}

export interface CountryDTO {
  code: string
  name: string
  adminLevels: AdminLevelDTO[]
}

export interface GeographyConfigDTO {
  scope: 'worldwide' | 'regional' | 'national' | 'sub_country'
  show_country_selector: boolean
  allowed_countries: string[] | null
  base_country_code: string | null
  visible_levels: number[]
}
```

- [ ] **Step 2: Create service**

Create `resources/js/Services/geographyService.ts`:

```typescript
import { GeographyConfigDTO } from '@/Types/Geography'

export const geographyService = {
  async getOrganisationConfig(organisationSlug: string): Promise<GeographyConfigDTO> {
    const response = await fetch(`/organisations/${organisationSlug}/geography/config`)
    
    if (!response.ok) {
      throw new Error(`Failed to fetch geography config: ${response.status}`)
    }
    
    const data = await response.json()
    return data.data
  }
}
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Types/Geography.ts resources/js/Services/geographyService.ts
git commit -m "feat: add Geography types and service for frontend"
```

---

### Task D2: Update GeographyCascader Component

**Files:**
- Modify: `resources/js/Components/Geography/GeographyCascader.vue`

- [ ] **Step 1: Update props and setup**

Replace the `<script setup>` section:

```vue
<script setup lang="ts">
import { ref, computed, onMounted, watch, reactive } from 'vue'
import { useGeographyStore } from '@/stores/geographyStore'
import { geographyService } from '@/Services/geographyService'
import type { GeographyConfigDTO } from '@/Types/Geography'

const props = defineProps({
  modelValue: String,
  organisationSlug: {
    type: String,
    required: true
  },
  levelLabels: {
    type: Array,
    default: () => ['Province', 'District', 'Municipality', 'Ward']
  }
})

const emit = defineEmits(['update:modelValue'])

const store = useGeographyStore()
const geographyConfig = ref<GeographyConfigDTO | null>(null)
const configLoading = ref(false)
const configError = ref<string | null>(null)

const selectedCountry = ref<string | null>(null)

const selections = reactive({
  province: null,
  district: null,
  municipality: null,
  ward: null
})

// Computed properties
const shouldShowCountrySelector = computed(() => geographyConfig.value?.show_country_selector ?? false)

const allowedCountries = computed(() => geographyConfig.value?.allowed_countries ?? null)

const visibleLevelCount = computed(() => geographyConfig.value?.visible_levels.length ?? 0)

const provinces = computed(() => {
  if (!selectedCountry.value) return []
  return store.getProvinces()
})

const districts = computed(() => {
  return selections.province ? store.getChildrenOf(selections.province.id) : []
})

const municipalities = computed(() => {
  return selections.district ? store.getChildrenOf(selections.district.id) : []
})

const wards = computed(() => {
  return selections.municipality ? store.getChildrenOf(selections.municipality.id) : []
})

// Methods
const loadGeographyConfig = async () => {
  configLoading.value = true
  configError.value = null

  try {
    geographyConfig.value = await geographyService.getOrganisationConfig(props.organisationSlug)
    
    // Set initial country
    if (geographyConfig.value.base_country_code) {
      selectedCountry.value = geographyConfig.value.base_country_code
    } else if (!geographyConfig.value.show_country_selector) {
      selectedCountry.value = 'NP' // Default
    }

    if (selectedCountry.value) {
      await store.fetchFlat(selectedCountry.value)
    }
  } catch (err) {
    configError.value = err instanceof Error ? err.message : 'Failed to load geography config'
  } finally {
    configLoading.value = false
  }
}

const onCountryChange = async () => {
  // Clear selections
  selections.province = null
  selections.district = null
  selections.municipality = null
  selections.ward = null

  if (selectedCountry.value) {
    await store.fetchFlat(selectedCountry.value)
  }
  
  emitValue()
}

const onProvinceChange = () => {
  selections.district = null
  selections.municipality = null
  selections.ward = null
  emitValue()
}

const onDistrictChange = () => {
  selections.municipality = null
  selections.ward = null
  emitValue()
}

const onMunicipalityChange = () => {
  selections.ward = null
  emitValue()
}

const onWardChange = () => {
  emitValue()
}

const emitValue = () => {
  if (!selectedCountry.value) return
  
  const geoRef = store.buildGeoReference(selections, selectedCountry.value)
  emit('update:modelValue', geoRef)
}

const initializeFromModelValue = (value: string | undefined) => {
  if (!value) {
    selections.province = null
    selections.district = null
    selections.municipality = null
    selections.ward = null
    return
  }

  const parsed = store.parseGeoReference(value)
  selections.province = parsed.province || null
  selections.district = parsed.district || null
  selections.municipality = parsed.municipality || null
  selections.ward = parsed.ward || null
}

const retry = () => {
  store.reset()
  loadGeographyConfig()
}

// Lifecycle
onMounted(async () => {
  await loadGeographyConfig()
  initializeFromModelValue(props.modelValue)
})

watch(() => props.modelValue, (newValue) => {
  initializeFromModelValue(newValue)
})
</script>
```

- [ ] **Step 2: Update template**

Replace the template with:

```vue
<template>
  <div class="space-y-4">
    <!-- Config Loading Error -->
    <div v-if="configError" class="rounded-lg bg-red-50 p-3 text-red-700 border border-red-200">
      <p class="text-sm font-medium">{{ configError }}</p>
      <button @click="retry" class="mt-2 text-sm underline hover:no-underline">
        Retry
      </button>
    </div>

    <!-- Geography Data Error -->
    <div v-if="store.error" class="rounded-lg bg-red-50 p-3 text-red-700 border border-red-200">
      <p class="text-sm font-medium">{{ $t('geography.error.title') || 'Failed to load geography data' }}</p>
      <button @click="retry" class="mt-2 text-sm underline hover:no-underline">
        {{ $t('geography.error.retry') || 'Retry' }}
      </button>
    </div>

    <!-- Country Selector -->
    <div v-if="shouldShowCountrySelector">
      <label for="geography-country" class="block text-sm font-medium mb-1">
        Country *
      </label>
      <select
        id="geography-country"
        v-model="selectedCountry"
        @change="onCountryChange"
        :disabled="configLoading || store.loading || store.error"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary disabled:opacity-50"
      >
        <option :value="null">Select Country</option>
        <option v-if="allowedCountries === null || allowedCountries.includes('NP')" value="NP">
          Nepal
        </option>
        <option v-if="allowedCountries === null || allowedCountries.includes('DE')" value="DE">
          Germany
        </option>
        <option v-if="allowedCountries === null || allowedCountries.includes('AT')" value="AT">
          Austria
        </option>
        <option v-if="allowedCountries === null || allowedCountries.includes('CH')" value="CH">
          Switzerland
        </option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="store.loading || configLoading" class="rounded-lg bg-blue-50 p-3 text-blue-700">
      <p class="text-sm">{{ $t('geography.loading') || 'Loading geography data...' }}</p>
    </div>

    <!-- Level 1: Province/State -->
    <div v-if="selectedCountry && visibleLevelCount >= 1">
      <label for="geography-level-1" class="block text-sm font-medium mb-1">
        {{ levelLabels[0] }} *
      </label>
      <select
        id="geography-level-1"
        v-model="selections.province"
        @change="onProvinceChange"
        :disabled="store.loading || store.error"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary disabled:opacity-50"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[0] }}</option>
        <option v-for="item in provinces" :key="item.id" :value="item">
          {{ item.name_local?.en ?? item.name_local }}
        </option>
      </select>
    </div>

    <!-- Level 2: District -->
    <div v-if="selections.province && visibleLevelCount >= 2">
      <label for="geography-level-2" class="block text-sm font-medium mb-1">
        {{ levelLabels[1] }}
      </label>
      <select
        v-if="districts.length > 0"
        id="geography-level-2"
        v-model="selections.district"
        @change="onDistrictChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[1] }}</option>
        <option v-for="item in districts" :key="item.id" :value="item">
          {{ item.name_local?.en ?? item.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[1] + 's found' }}
      </div>
    </div>

    <!-- Level 3: Municipality -->
    <div v-if="selections.district && visibleLevelCount >= 3">
      <label for="geography-level-3" class="block text-sm font-medium mb-1">
        {{ levelLabels[2] }}
      </label>
      <select
        v-if="municipalities.length > 0"
        id="geography-level-3"
        v-model="selections.municipality"
        @change="onMunicipalityChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[2] }}</option>
        <option v-for="item in municipalities" :key="item.id" :value="item">
          {{ item.name_local?.en ?? item.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[2] + 's found' }}
      </div>
    </div>

    <!-- Level 4: Ward -->
    <div v-if="selections.municipality && visibleLevelCount >= 4">
      <label for="geography-level-4" class="block text-sm font-medium mb-1">
        {{ levelLabels[3] }}
      </label>
      <select
        v-if="wards.length > 0"
        id="geography-level-4"
        v-model="selections.ward"
        @change="onWardChange"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
      >
        <option :value="null">{{ $t('geography.select') || 'Select ' + levelLabels[3] }}</option>
        <option v-for="item in wards" :key="item.id" :value="item">
          {{ item.name_local?.en ?? item.name_local }}
        </option>
      </select>
      <div v-else class="text-sm text-amber-600 p-2 bg-amber-50 rounded">
        {{ $t('geography.empty') || 'No ' + levelLabels[3] + 's found' }}
      </div>
    </div>
  </div>
</template>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Components/Geography/GeographyCascader.vue
git commit -m "refactor: update GeographyCascader to use scope-aware config from API"
```

---

### Task D3: Update Create.vue and Edit.vue

**Files:**
- Modify: `resources/js/Pages/Committee/Create.vue`
- Modify: `resources/js/Pages/Committee/Edit.vue`

- [ ] **Step 1: Update Create.vue**

Find the GeographyCascader component and update:

```vue
<GeographyCascader
  v-model="form.geo_reference"
  :organisation-slug="organisation.slug"
  :levelLabels="[$t('pages.committee.form.province') || 'Province', $t('pages.committee.form.district') || 'District', $t('pages.committee.form.municipality') || 'Municipality', $t('pages.committee.form.ward') || 'Ward']"
/>
```

- [ ] **Step 2: Update Edit.vue**

Find the GeographyCascader component and update:

```vue
<GeographyCascader
  v-model="form.geo_reference"
  :organisation-slug="organisation.slug"
  :levelLabels="[$t('pages.committee.form.province') || 'Province', $t('pages.committee.form.district') || 'District', $t('pages.committee.form.municipality') || 'Municipality', $t('pages.committee.form.ward') || 'Ward']"
/>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/Pages/Committee/Create.vue resources/js/Pages/Committee/Edit.vue
git commit -m "refactor: update forms to pass organisationSlug to cascader"
```

---

### Task D4: Write Component Tests

**Files:**
- Create: `resources/js/tests/components/GeographyCascader.ddd.test.ts`

- [ ] **Step 1: Write tests**

Create `resources/js/tests/components/GeographyCascader.ddd.test.ts`:

```typescript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import GeographyCascader from '@/Components/Geography/GeographyCascader.vue'
import * as geographyService from '@/Services/geographyService'

vi.mock('@/Services/geographyService', () => ({
  geographyService: {
    getOrganisationConfig: vi.fn()
  }
}))

describe('GeographyCascader - DDD Scope-Aware', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  describe('Worldwide Scope', () => {
    it('shows country selector for worldwide scope', async () => {
      vi.mocked(geographyService.geographyService.getOrganisationConfig).mockResolvedValue({
        scope: 'worldwide',
        show_country_selector: true,
        allowed_countries: null,
        base_country_code: null,
        visible_levels: [1, 2, 3, 4]
      })

      const wrapper = mount(GeographyCascader, {
        props: {
          organisationSlug: 'restaurant-namaste'
        }
      })

      await wrapper.vm.$nextTick()

      expect(wrapper.text()).toContain('Country')
    })
  })

  describe('National Scope', () => {
    it('hides country selector for national scope', async () => {
      vi.mocked(geographyService.geographyService.getOrganisationConfig).mockResolvedValue({
        scope: 'national',
        show_country_selector: false,
        allowed_countries: null,
        base_country_code: 'NP',
        visible_levels: [1, 2, 3, 4]
      })

      const wrapper = mount(GeographyCascader, {
        props: {
          organisationSlug: 'namaste-nepal'
        }
      })

      await wrapper.vm.$nextTick()

      const countrySelects = wrapper.findAll('select').filter(s => s.element.id === 'geography-country')
      expect(countrySelects).toHaveLength(0)
    })
  })

  describe('Regional Scope', () => {
    it('shows country selector with filtered countries', async () => {
      vi.mocked(geographyService.geographyService.getOrganisationConfig).mockResolvedValue({
        scope: 'regional',
        show_country_selector: true,
        allowed_countries: ['DE', 'AT', 'CH'],
        base_country_code: 'DE',
        visible_levels: [1, 2, 3]
      })

      const wrapper = mount(GeographyCascader, {
        props: {
          organisationSlug: 'nrna-germany'
        }
      })

      await wrapper.vm.$nextTick()

      expect(wrapper.text()).toContain('Country')
      expect(wrapper.text()).toContain('Germany')
    })
  })

  describe('Error Handling', () => {
    it('shows error when config fails to load', async () => {
      vi.mocked(geographyService.geographyService.getOrganisationConfig)
        .mockRejectedValue(new Error('Network error'))

      const wrapper = mount(GeographyCascader, {
        props: {
          organisationSlug: 'test-org'
        }
      })

      await wrapper.vm.$nextTick()

      expect(wrapper.text()).toContain('Network error')
    })
  })
})
```

- [ ] **Step 2: Run tests**

```bash
npm run test resources/js/tests/components/GeographyCascader.ddd.test.ts
```

Expected: PASS

- [ ] **Step 3: Commit**

```bash
git add resources/js/tests/components/GeographyCascader.ddd.test.ts
git commit -m "test: add DDD scope-aware cascader tests"
```

---

## Phase E: Data Seeding

### Task E1: Seed admin_levels for Countries

**Files:**
- Modify: `app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php`

- [ ] **Step 1: Update seeder with admin_levels**

Update the seeder to include admin_levels JSON:

```php
DB::table('countries')->updateOrInsert(
    ['code' => 'NP'],
    [
        'name' => 'Nepal',
        'code' => 'NP',
        'is_active' => 1,
        'admin_levels' => json_encode([
            1 => ['name' => 'Province', 'local_name' => 'प्रदेश'],
            2 => ['name' => 'District', 'local_name' => 'जिल्ला'],
            3 => ['name' => 'Municipality', 'local_name' => 'नगरपालिका'],
            4 => ['name' => 'Ward', 'local_name' => 'वडा'],
        ]),
    ]
);

DB::table('countries')->updateOrInsert(
    ['code' => 'DE'],
    [
        'name' => 'Germany',
        'code' => 'DE',
        'is_active' => 1,
        'admin_levels' => json_encode([
            1 => ['name' => 'State', 'local_name' => 'Bundesland'],
            2 => ['name' => 'Administrative District', 'local_name' => 'Regierungsbezirk'],
            3 => ['name' => 'Municipality', 'local_name' => 'Gemeinde'],
        ]),
    ]
);
```

- [ ] **Step 2: Run seeder**

```bash
php artisan db:seed --class="App\Contexts\Geography\Infrastructure\Database\Seeders\CountriesSeeder"
```

- [ ] **Step 3: Commit**

```bash
git add app/Contexts/Geography/Infrastructure/Database/Seeders/CountriesSeeder.php
git commit -m "feat: seed admin_levels for Nepal and Germany"
```

---

## Summary

**Total Tasks:** 15 major tasks across 5 phases
**Total Commits:** 15+ (one per logical step)

| Phase | Commits | Files |
|-------|---------|-------|
| A: Domain Foundation | 4 | ValueObjects, Aggregate, Event |
| B: Infrastructure | 3 | Repositories, DTOs, Queries |
| C: HTTP Layer | 1 | Controllers, Routes |
| D: Frontend | 4 | Services, Types, Components, Tests |
| E: Data | 1 | Seeders |

**Key Architectural Improvements:**
- ✅ Geography Context owns all geographic configuration (DDD-compliant)
- ✅ Frontend receives DTOs, not domain objects
- ✅ Clear separation of concerns: Domain → Application → API → Frontend
- ✅ Type-safe with TypeScript interfaces
- ✅ Proper error handling and fallbacks
- ✅ Extensible for future scopes and countries

