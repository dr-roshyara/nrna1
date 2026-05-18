<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Deprecation\DeprecationAccessGuard;
use App\Application\Election\Deprecation\DeprecationPolicy;
use App\Exceptions\DeprecatedFieldException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Phase 2: Deprecation Enforcement Layer
 * Test: DeprecationAccessGuard
 *
 * RED tests for runtime field access control.
 * Guards against silent legacy field usage.
 */
class DeprecationAccessGuardTest extends TestCase
{
    private DeprecationAccessGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = app(DeprecationAccessGuard::class);
    }

    /**
     * Test: Guard logs warning for deprecated field access (warning mode)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_logs_warning_for_deprecated_field_access(): void
    {
        Log::spy();

        $this->guard->checkFieldAccess('status', 'TestContext', 'warning');

        Log::shouldHaveReceived('warning')->once();
    }

    /**
     * Test: Guard throws exception for strict-level deprecated fields
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_throws_for_strict_deprecated_field(): void
    {
        $this->expectException(DeprecatedFieldException::class);

        $this->guard->checkFieldAccess('is_active', 'TestContext', 'strict');
    }

    /**
     * Test: Guard allows non-deprecated field access without logging
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_allows_non_deprecated_fields(): void
    {
        Log::spy();

        $this->guard->checkFieldAccess('voting_starts_at', 'TestContext', 'warning');

        Log::shouldNotHaveReceived('warning');
    }

    /**
     * Test: Guard includes replacement hint in exception message
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_includes_replacement_in_error(): void
    {
        try {
            $this->guard->checkFieldAccess('is_active', 'TestContext', 'strict');
            $this->fail('Expected exception');
        } catch (DeprecatedFieldException $e) {
            $this->assertStringContainsString('isActive()', $e->getMessage());
            $this->assertStringContainsString('Replacement:', $e->getMessage());
        }
    }

    /**
     * Test: Guard can operate in audit-only mode (logs but doesn't throw)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_audit_only_mode_logs_without_throwing(): void
    {
        Log::spy();

        $this->guard->checkFieldAccess('is_active', 'TestContext', 'audit_only');

        Log::shouldHaveReceived('warning')->once();
        $this->assertTrue(true); // didn't throw
    }

    /**
     * Test: Guard is injectable
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_is_injectable(): void
    {
        $resolved = app(DeprecationAccessGuard::class);

        $this->assertInstanceOf(DeprecationAccessGuard::class, $resolved);
    }

    /**
     * Test: Policy defines deprecated fields with severity
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function policy_defines_deprecated_fields(): void
    {
        $this->assertArrayHasKey('status', DeprecationPolicy::FIELDS);
        $this->assertArrayHasKey('is_active', DeprecationPolicy::FIELDS);
        $this->assertEquals('warning', DeprecationPolicy::FIELDS['status']['severity']);
        $this->assertEquals('strict', DeprecationPolicy::FIELDS['is_active']['severity']);
    }
}
