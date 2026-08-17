<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\OperatingCore\Condition\GateIntervalState;
use App\Contexts\Election\Domain\OperatingCore\Event\NonCanonicalEventName;
use PHPUnit\Framework\TestCase;

/**
 * Structural guards of the readiness report §E: D-1 (no authority adapter exists),
 * D-4/G-3 (nothing attaches to OPEN), D-5 (no C-2 classifier), D-7 (events marked
 * non-canonical), repo layer rule (Domain framework-free), ADR-T11 surface check.
 */
final class StructuralGuardsTest extends TestCase
{
    private const OPERATING_CORE_DIR = __DIR__ . '/../../../../../app/Contexts/Election/Domain/OperatingCore';

    private const APP_DIR = __DIR__ . '/../../../../../app';

    /** @return list<string> */
    private function phpFilesUnder(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        sort($files);

        return $files;
    }

    /**
     * D-1: the `OrganisationalAppointmentAuthority` port has NO adapter, default, stub,
     * fallback or container binding anywhere in app/. The rule is "no IMPLEMENTATION of
     * the port", not "no textual reference" — documentation and type annotations may
     * legitimately mention it (reviewer correction, 2026-08-17).
     */
    public function test_no_adapter_exists_for_the_organisational_appointment_authority_port(): void
    {
        $portFile = self::OPERATING_CORE_DIR . '/Port/OrganisationalAppointmentAuthority.php';
        $this->assertFileExists($portFile, 'The port must be DECLARED (B-3): a wall, not an omission.');

        foreach ($this->phpFilesUnder(self::APP_DIR) as $file) {
            if (realpath($file) === realpath($portFile)) {
                continue; // the declaration itself
            }
            $source = (string) file_get_contents($file);

            // No concrete class implements the port.
            $this->assertDoesNotMatchRegularExpression(
                '/implements[^{;]*\bOrganisationalAppointmentAuthority\b/',
                $source,
                "D-1 violated: {$file} implements the external-authority port. The design forbids any adapter, default, stub or fallback (EM-OPEN-049; B-3)."
            );

            // No container binding wires the port to anything (adapters/bindings live in Infrastructure/Providers).
            if (str_contains($file, '/Infrastructure/') || str_contains($file, '/Providers/')) {
                $this->assertStringNotContainsString(
                    'OrganisationalAppointmentAuthority',
                    $source,
                    "D-1 violated: {$file} wires the external-authority port in infrastructure. §5b forbids Infrastructure from holding ANY adapter for it (B-3)."
                );
            }
        }
    }

    /** Repo layer rule: the Domain operating core is framework-free — pure PHP only. */
    public function test_the_operating_core_domain_is_framework_free(): void
    {
        $files = $this->phpFilesUnder(self::OPERATING_CORE_DIR);
        $this->assertNotEmpty($files, 'The operating core must exist under the Election context Domain layer.');

        foreach ($files as $file) {
            $source = (string) file_get_contents($file);
            foreach (['Illuminate\\', 'use Carbon\\', 'Laravel\\', 'use App\\Models\\', 'use App\\Http\\'] as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $source,
                    "Domain layer must hold no framework artifact ({$file})."
                );
            }
        }
    }

    /** D-5 / G-3: the gate classification is closed — no C-2 classifier, no fifth state, no discretionary conversion of waiting into impossibility. */
    public function test_gate_interval_classification_is_closed_and_no_c2_classifier_exists(): void
    {
        $this->assertSame(
            ['Open', 'DecidedPass', 'DecidedFailure', 'Unachievable'],
            array_map(static fn ($c) => $c->name, GateIntervalState::cases()),
            'EM-GOV-068 defines the complete classification; no actor can convert waiting into impossibility (EM-OPEN-109).'
        );

        foreach ($this->phpFilesUnder(self::OPERATING_CORE_DIR) as $file) {
            $source = (string) file_get_contents($file);
            $this->assertDoesNotMatchRegularExpression(
                '/class\s+\w*(UnsatisfiableInFact|C2)\w*Classifier/i',
                $source,
                "D-5 violated: a C-2 classifier component exists in {$file}."
            );
        }
    }

    /**
     * D-4 / G-3: nothing in the operating core attaches a timer, scheduler or expiry hook
     * to an OPEN gate or to inaction. Time CONCEPTS (RecordedInstant, InstantSource) are
     * approved domain ports for recorded facts; the forbidden thing is an attachment
     * MECHANISM on the gate (reviewer correction, 2026-08-17).
     */
    public function test_nothing_attaches_time_to_open_or_to_inaction(): void
    {
        foreach ($this->phpFilesUnder(self::OPERATING_CORE_DIR . '/Gate') as $file) {
            $source = (string) file_get_contents($file);
            foreach (['Deadline', 'Timeout', 'Scheduler', 'Cron', 'ExpiryHook', 'OpenGateExpiry'] as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $source,
                    "D-4/G-3 violated: {$file} attaches a time mechanism to the gate. OPEN has no clock, no deadline, no expiry hook."
                );
            }
        }
    }

    /** D-7: every operating-core domain event is marked non-canonical (EM-OPEN-045) and honours the event boundary (ADR-T11). */
    public function test_every_domain_event_is_marked_non_canonical(): void
    {
        $eventFiles = $this->phpFilesUnder(self::OPERATING_CORE_DIR . '/Event');
        $eventClasses = [];
        foreach ($eventFiles as $file) {
            $class = 'App\\Contexts\\Election\\Domain\\OperatingCore\\Event\\' . basename($file, '.php');
            if (class_exists($class)) {
                $eventClasses[] = $class;
            }
        }

        // Event names are non-canonical placeholders (D-7; EM-OPEN-045): the guard verifies
        // that every DISCOVERED event satisfies the rule — it does not freeze the class
        // count, so a governance-approved split/merge needs no test rewrite (reviewer
        // correction, 2026-08-17). Which facts must be recorded is fixed by design §5f;
        // that traceability lives on each event class, not in a frozen inventory here.
        $this->assertNotEmpty($eventClasses, 'The §5f recorded business facts must exist as domain events.');

        foreach ($eventClasses as $class) {
            $reflection = new \ReflectionClass($class);
            $this->assertTrue(
                $reflection->implementsInterface(NonCanonicalEventName::class),
                "{$class} must be marked non-canonical: the canonical protocol-event vocabulary is expressly open (EM-OPEN-045; D-7)."
            );
            $this->assertTrue(
                $reflection->implementsInterface(DomainEvent::class),
                "{$class} must be an Election domain event (carries no voter↔vote linkage — ADR-T11)."
            );
            $this->assertTrue($reflection->isFinal() && $reflection->isReadOnly(), "{$class} must be a final readonly historical fact.");

            // ADR-T11 surface check: no event payload names a voter or a vote.
            foreach ($reflection->getProperties() as $property) {
                $this->assertDoesNotMatchRegularExpression(
                    '/voter|ballot|^vote$/i',
                    $property->getName(),
                    "ADR-T11: {$class}::{$property->getName()} must not open a voter↔vote linkage surface."
                );
            }
        }
    }
}
