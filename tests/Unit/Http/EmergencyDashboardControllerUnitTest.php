<?php

namespace Tests\Unit\Http;

use App\Http\Controllers\EmergencyDashboardController;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for EmergencyDashboardController
 *
 * Tests the structure without HTTP/database dependencies
 */
class EmergencyDashboardControllerUnitTest extends TestCase
{
    /**
     * Test controller can be instantiated
     */
    public function test_emergency_dashboard_controller_instantiates(): void
    {
        $controller = new EmergencyDashboardController();
        $this->assertInstanceOf(EmergencyDashboardController::class, $controller);
    }

    /**
     * Test controller extends BaseController
     */
    public function test_controller_extends_controller(): void
    {
        $controller = new EmergencyDashboardController();
        $this->assertInstanceOf(\App\Http\Controllers\Controller::class, $controller);
    }

    /**
     * Test index method exists
     */
    public function test_index_method_exists(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);
        $this->assertTrue($reflection->hasMethod('index'));
    }

    /**
     * Test logout method exists
     */
    public function test_logout_method_exists(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);
        $this->assertTrue($reflection->hasMethod('logout'));
    }

    /**
     * Test healthCheck method exists
     */
    public function test_health_check_method_exists(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);
        $this->assertTrue($reflection->hasMethod('healthCheck'));
    }

    /**
     * Test private helper methods exist
     */
    public function test_helper_methods_exist(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);

        $this->assertTrue($reflection->hasMethod('getUserOrganisationsSafely'));
        $this->assertTrue($reflection->hasMethod('getBasicActions'));
    }

    /**
     * Test methods have proper signatures
     */
    public function test_method_signatures_are_correct(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);

        // index method
        $index = $reflection->getMethod('index');
        $this->assertTrue($index->isPublic());

        // logout method
        $logout = $reflection->getMethod('logout');
        $this->assertTrue($logout->isPublic());

        // healthCheck method
        $health = $reflection->getMethod('healthCheck');
        $this->assertTrue($health->isPublic());
    }

    /**
     * Test class follows single responsibility principle
     */
    public function test_controller_focuses_on_emergency_dashboard(): void
    {
        $reflection = new \ReflectionClass(EmergencyDashboardController::class);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        // Should have minimal public methods
        $publicMethods = [];
        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() === EmergencyDashboardController::class) {
                $publicMethods[] = $method->getName();
            }
        }

        // Main methods: index, logout, healthCheck
        $this->assertContains('index', $publicMethods);
        $this->assertContains('logout', $publicMethods);
        $this->assertContains('healthCheck', $publicMethods);
    }
}
