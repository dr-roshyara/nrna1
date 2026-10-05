<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C4 — Verify All is the same admission mechanism, not a second one.
 *
 * - The bulk command/handler and the new domain types are framework-free.
 * - The bulk action delegates to the application handler (no direct evidence writes).
 * - The entitlement decision never reads free-text `notes` (the attestation statement
 *   is audit data, not an admission authority) nor the evidence `method`.
 */
final class BulkIdentityVerificationArchitectureTest extends TestCase
{
    private const PURE_FILES = [
        'app/Contexts/Elections/Domain/ValueObjects/VerificationMethod.php',
        'app/Contexts/Elections/Domain/Exceptions/BulkVerificationCountChanged.php',
        'app/Contexts/Elections/Application/Commands/BulkRecordIdentityVerificationCommand.php',
        'app/Contexts/Elections/Application/Handlers/BulkRecordIdentityVerificationHandler.php',
    ];

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    public function test_bulk_verification_domain_and_application_are_framework_free(): void
    {
        $violations = [];

        foreach (self::PURE_FILES as $relative) {
            $path = $this->root() . '/' . $relative;

            if (! is_file($path)) {
                $violations[] = "{$relative} does not exist";
                continue;
            }

            if (preg_match('~use\s+Illuminate\\\\|\\\\Illuminate\\\\Support\\\\Facades~', file_get_contents($path))) {
                $violations[] = "{$relative} depends on Illuminate";
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }

    public function test_the_bulk_action_delegates_to_the_application_handler(): void
    {
        $controller = file_get_contents($this->root() . '/app/Http/Controllers/Election/VoterVerificationController.php');

        $this->assertStringContainsString('BulkRecordIdentityVerificationHandler', $controller);
        $this->assertStringNotContainsString('VoterVerification::create', $controller);
        $this->assertStringNotContainsString('VoterVerification::query', $controller);
    }

    public function test_the_entitlement_decision_does_not_read_notes_or_method(): void
    {
        $query = file_get_contents($this->root() . '/app/Contexts/Elections/Infrastructure/Queries/EloquentVotingEntitlementQuery.php');

        foreach (["'notes'", "'method'", '->notes', '->method'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $query, "admission must not depend on {$forbidden}");
        }
    }
}
