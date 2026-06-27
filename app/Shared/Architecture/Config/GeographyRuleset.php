<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Config;

use App\Shared\Architecture\Rule\ArchitectureRule;
use App\Shared\Architecture\Rule\DependencyDirectionRule;
use App\Shared\Architecture\Rule\FinalClassRule;
use App\Shared\Architecture\Rule\ImmutableDtoRule;
use App\Shared\Architecture\Rule\JsonSerializableRule;
use App\Shared\Architecture\Rule\NoEloquentOutsideInfrastructureRule;
use App\Shared\Architecture\Rule\NoFrameworkImportsRule;
use App\Shared\Architecture\Rule\NoInfrastructureImportsRule;
use App\Shared\Architecture\Rule\NoPublicSettersRule;
use App\Shared\Architecture\Rule\ReadonlyValueObjectRule;

final class GeographyRuleset
{
    private const DOMAIN_PATH = 'app/Contexts/Geography/Domain';
    private const APPLICATION_PATH = 'app/Contexts/Geography/Application';
    private const INFRASTRUCTURE_PATH = 'app/Contexts/Geography/Infrastructure';
    private const CONTEXT_NAMESPACE = 'App\\Contexts\\Geography';

    private const LEGACY_DOMAIN_PREFIXES = [
        '/Domain/Models/',
        '/Domain/Services/FuzzyMatchingService.php',
        '/Domain/Services/GeographyPathService.php',
    ];

    private const LEGACY_NON_FINAL = [
        'GeoAdministrativeUnit.php',
        'CountryNotSupportedException.php',
        'InvalidHierarchyException.php',
        'InvalidParentChildException.php',
        'MaxHierarchyDepthException.php',
        'MissingRequiredLevelException.php',
    ];

    private const LEGACY_NON_READONLY_VOS = [
        'GeoPath.php',
        'MatchResult.php',
        'PotentialMatches.php',
        'SimilarityScore.php',
    ];

    /** @return ArchitectureRule[] */
    public function rules(): array
    {
        return [
            new NoFrameworkImportsRule(),
            new NoInfrastructureImportsRule(),
            new FinalClassRule(),
            new ReadonlyValueObjectRule(),
            new NoPublicSettersRule(),
            new ImmutableDtoRule(),
            new JsonSerializableRule(),
            new NoEloquentOutsideInfrastructureRule(),
            new DependencyDirectionRule(),
        ];
    }

    /** @return array<string, mixed> */
    public function config(): array
    {
        return [
            'no_framework_imports' => [
                'path' => self::DOMAIN_PATH,
                'excluded_prefixes' => self::LEGACY_DOMAIN_PREFIXES,
            ],
            'no_infrastructure_imports' => [
                'path' => self::DOMAIN_PATH,
                'context_namespace' => self::CONTEXT_NAMESPACE,
                'excluded_prefixes' => self::LEGACY_DOMAIN_PREFIXES,
            ],
            'final_class' => [
                'path' => self::DOMAIN_PATH,
                'excluded_prefixes' => self::LEGACY_DOMAIN_PREFIXES,
                'excluded_basenames' => self::LEGACY_NON_FINAL,
            ],
            'readonly_value_object' => [
                'path' => self::DOMAIN_PATH . '/ValueObjects',
                'excluded_basenames' => self::LEGACY_NON_READONLY_VOS,
            ],
            'no_public_setters' => [
                'path' => self::DOMAIN_PATH,
                'excluded_prefixes' => self::LEGACY_DOMAIN_PREFIXES,
            ],
            'immutable_dto' => [
                'path' => self::APPLICATION_PATH . '/DTOs',
                'exclude_patterns' => ['Mapper'],
            ],
            'json_serializable_dto' => [
                'path' => self::APPLICATION_PATH . '/DTOs',
                'exclude_patterns' => ['Mapper'],
            ],
            'no_eloquent_outside_infrastructure' => [
                'paths' => [self::APPLICATION_PATH, self::DOMAIN_PATH],
                'excluded_prefixes' => array_merge(self::LEGACY_DOMAIN_PREFIXES, ['/Domain/ValueObjects/']),
            ],
            'dependency_direction' => [
                'domain_path' => self::DOMAIN_PATH,
                'application_path' => self::APPLICATION_PATH,
                'context_namespace' => self::CONTEXT_NAMESPACE,
                'excluded_prefixes' => self::LEGACY_DOMAIN_PREFIXES,
            ],
        ];
    }
}
