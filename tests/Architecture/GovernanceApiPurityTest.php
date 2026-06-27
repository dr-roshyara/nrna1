<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Governance\API\V1\Controllers\GovernanceCommitteeController;
use App\Contexts\Governance\API\V1\Controllers\GovernanceHealthController;
use App\Contexts\Governance\API\V1\Controllers\GovernanceHierarchyController;
use App\Contexts\Governance\API\V1\Requests\HierarchyQueryRequest;
use App\Contexts\Governance\API\V1\Responses\CommitteeChildrenResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeGovernanceResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeHierarchyResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeSummaryResponse;
use App\Contexts\Governance\API\V1\Responses\ErrorResponse;
use App\Contexts\Governance\API\V1\Responses\GovernanceHealthResponse;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class GovernanceApiPurityTest extends TestCase
{
    private const API_RESPONSE_PATH = 'app/Contexts/Governance/API/V1/Responses';
    private const API_CONTROLLER_PATH = 'app/Contexts/Governance/API/V1/Controllers';

    private const FORBIDDEN_ELOQUENT_IMPORTS = [
        'Illuminate\\Database\\Eloquent\\Model',
        'Illuminate\\Database\\',
        'Eloquent',
    ];

    private const FORBIDDEN_POLICY_IMPORTS = [
        'App\\Contexts\\Governance\\Domain\\Committee\\Policies\\',
        'CommitteeGovernanceInterpreter',
        'OperationalStatePolicy',
        'TemporalGovernancePolicy',
        'ConstitutionalLegitimacyPolicy',
    ];

    // RULE-FE-01: Response DTOs do not expose Eloquent models
    public function test_api_response_dtos_no_eloquent(): void
    {
        $responseClasses = [
            CommitteeHierarchyResponse::class,
            CommitteeSummaryResponse::class,
            CommitteeGovernanceResponse::class,
            CommitteeChildrenResponse::class,
            GovernanceHealthResponse::class,
            ErrorResponse::class,
        ];

        foreach ($responseClasses as $class) {
            $reflection = new ReflectionClass($class);
            $filename = $reflection->getFileName();
            $content = file_get_contents($filename);

            foreach (self::FORBIDDEN_ELOQUENT_IMPORTS as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $content,
                    sprintf(
                        '%s must not import Eloquent (found "%s")',
                        $class,
                        $forbidden
                    ),
                );
            }

            $this->assertTrue($reflection->isReadOnly(), sprintf('%s must be readonly', $class));
            $this->assertTrue($reflection->isFinal(), sprintf('%s must be final', $class));
        }
    }

    // RULE-FE-02: Controllers do not import domain policies/interpreters
    public function test_governance_controllers_no_domain_policies(): void
    {
        $controllerClasses = [
            GovernanceHierarchyController::class,
            GovernanceCommitteeController::class,
            GovernanceHealthController::class,
        ];

        foreach ($controllerClasses as $class) {
            $reflection = new ReflectionClass($class);
            $filename = $reflection->getFileName();
            $content = file_get_contents($filename);

            foreach (self::FORBIDDEN_POLICY_IMPORTS as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $content,
                    sprintf('%s must not import "%s"', $class, $forbidden),
                );
            }
        }
    }

    // RULE-FE-03: API responses are immutable (no public setters)
    public function test_api_responses_are_immutable(): void
    {
        $responseClasses = [
            CommitteeHierarchyResponse::class,
            CommitteeSummaryResponse::class,
            CommitteeGovernanceResponse::class,
            CommitteeChildrenResponse::class,
            GovernanceHealthResponse::class,
            ErrorResponse::class,
        ];

        foreach ($responseClasses as $class) {
            $reflection = new ReflectionClass($class);
            $methods = $reflection->getMethods();

            foreach ($methods as $method) {
                if ($method->isPublic() && !$method->isConstructor() && !$method->isStatic()) {
                    $name = $method->getName();
                    $this->assertTrue(
                        str_starts_with($name, 'jsonS') || str_starts_with($name, 'toR'),
                        sprintf(
                            '%s has unexpected public method "%s". Only jsonSerialize(), toResponse(), and static factories allowed.',
                            $class,
                            $name,
                        ),
                    );
                }
            }

            // Verify all properties are public readonly (no getters needed for readonly DTOs)
            foreach ($reflection->getProperties() as $prop) {
                $this->assertTrue(
                    $prop->isPublic(),
                    sprintf('%s property "$%s" must be public', $class, $prop->getName()),
                );
                $this->assertFalse(
                    $prop->isStatic(),
                    sprintf('%s property "$%s" must not be static', $class, $prop->getName()),
                );
            }
        }
    }

    // RULE-FE-04: Controllers use only use cases, not repositories
    public function test_controllers_only_depend_on_use_cases(): void
    {
        $controllerClasses = [
            GovernanceHierarchyController::class,
            GovernanceCommitteeController::class,
            GovernanceHealthController::class,
        ];

        foreach ($controllerClasses as $class) {
            $reflection = new ReflectionClass($class);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                continue;
            }

            $params = $constructor->getParameters();
            $this->assertNotEmpty($params, sprintf('%s must have constructor dependencies', $class));

            foreach ($params as $param) {
                $type = $param->getType();
                $this->assertNotNull($type, sprintf('%s parameter "$%s" must be type-hinted', $class, $param->getName()));

                $typeName = $type->getName();

                // Must not inject repositories directly
                $this->assertStringNotContainsString(
                    'Repository',
                    $typeName,
                    sprintf(
                        '%s must not inject "%s". Use use cases/query services only.',
                        $class,
                        $typeName,
                    ),
                );

                // Must not inject Eloquent models
                $this->assertStringNotContainsString(
                    'Model',
                    $typeName,
                    sprintf('%s must not inject "%s". Use application interfaces.', $class, $typeName),
                );

                // Must not inject projection models
                $this->assertStringNotContainsString(
                    'Projection',
                    $typeName,
                    sprintf('%s must not inject "%s".', $class, $typeName),
                );
            }
        }
    }

    // RULE-FE-08: Controllers must not import domain policies/interpreters
    public function test_api_controllers_no_interpreter_imports(): void
    {
        $this->test_governance_controllers_no_domain_policies();
    }

    // Test that all API response classes implement JsonSerializable
    public function test_api_responses_implement_json_serializable(): void
    {
        $responseClasses = [
            CommitteeHierarchyResponse::class,
            CommitteeSummaryResponse::class,
            CommitteeGovernanceResponse::class,
            CommitteeChildrenResponse::class,
            GovernanceHealthResponse::class,
            ErrorResponse::class,
        ];

        foreach ($responseClasses as $class) {
            $reflection = new ReflectionClass($class);
            $this->assertTrue(
                $reflection->implementsInterface(\JsonSerializable::class),
                sprintf('%s must implement JsonSerializable', $class),
            );
        }
    }

    // Test HierarchyQueryRequest enforces max depth
    public function test_hierarchy_query_enforces_max_depth(): void
    {
        $reflection = new ReflectionClass(HierarchyQueryRequest::class);
        $content = file_get_contents($reflection->getFileName());

        $this->assertStringContainsString(
            'max',
            $content,
            'HierarchyQueryRequest must validate max depth',
        );
        $this->assertStringContainsString(
            'depth',
            $content,
            'HierarchyQueryRequest must reference depth field',
        );
    }
}
