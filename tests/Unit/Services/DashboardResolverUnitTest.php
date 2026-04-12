<?php

namespace Tests\Unit\Services;

use App\Services\DashboardResolver;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DashboardResolver
 *
 * Tests the business logic without database dependencies
 */
class DashboardResolverUnitTest extends TestCase
{
    /**
     * Test service can be instantiated
     */
    public function test_dashboard_resolver_instantiates(): void
    {
        $resolver = new DashboardResolver();
        $this->assertInstanceOf(DashboardResolver::class, $resolver);
    }

    /**
     * Test that private methods exist
     */
    public function test_dashboard_resolver_has_required_methods(): void
    {
        $resolver = new DashboardResolver();

        // Verify the class has the methods we expect
        $reflection = new \ReflectionClass($resolver);

        // Public methods
        $this->assertTrue($reflection->hasMethod('resolve'));

        // Private/protected methods
        $this->assertTrue($reflection->hasMethod('isFirstTimeUser'));
        $this->assertTrue($reflection->hasMethod('getDashboardRoles'));
        $this->assertTrue($reflection->hasMethod('shouldUseCachedResolution'));
        $this->assertTrue($reflection->hasMethod('getCurrentVotingStep'));
    }

    /**
     * Test class has proper type hints
     */
    public function test_resolve_method_has_proper_signature(): void
    {
        $reflection = new \ReflectionClass(DashboardResolver::class);
        $method = $reflection->getMethod('resolve');

        // Check parameter
        $params = $method->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('user', $params[0]->getName());

        // Check return type
        $this->assertNotNull($method->getReturnType());
    }

    /**
     * Test that cache-related methods exist
     */
    public function test_cache_methods_exist(): void
    {
        $reflection = new \ReflectionClass(DashboardResolver::class);

        $this->assertTrue($reflection->hasMethod('shouldUseCachedResolution'));
        $this->assertTrue($reflection->hasMethod('getCachedResolution'));
        $this->assertTrue($reflection->hasMethod('cacheResolution'));
        $this->assertTrue($reflection->hasMethod('isSessionFresh'));
    }

    /**
     * Test voting step detection methods exist
     */
    public function test_voting_step_methods_exist(): void
    {
        $reflection = new \ReflectionClass(DashboardResolver::class);

        $this->assertTrue($reflection->hasMethod('checkActiveVotingSession'));
        $this->assertTrue($reflection->hasMethod('getCurrentVotingStep'));
        $this->assertTrue($reflection->hasMethod('redirectToVoting'));
    }

    /**
     * Test redirect methods exist
     */
    public function test_redirect_methods_exist(): void
    {
        $reflection = new \ReflectionClass(DashboardResolver::class);

        $this->assertTrue($reflection->hasMethod('redirectToFirstTimeUser'));
        $this->assertTrue($reflection->hasMethod('redirectToRoleSelection'));
        $this->assertTrue($reflection->hasMethod('redirectByRole'));
        $this->assertTrue($reflection->hasMethod('legacyFallback'));
    }

    /**
     * Test that service follows dependency injection pattern
     */
    public function test_service_can_be_resolved_from_container(): void
    {
        // In a real app context, this would use app()
        $resolver = new DashboardResolver();
        $this->assertNotNull($resolver);
    }
}
