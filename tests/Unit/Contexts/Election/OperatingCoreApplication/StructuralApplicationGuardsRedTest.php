<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use PHPUnit\Framework\TestCase;

/**
 * EM-IMPL-002 RED — structural guards over the GRANTED application namespace
 * `App\Contexts\Election\Application\OperatingCore\` (W-1…W-10; G-1/G-4/G-5;
 * RED-1/RED-3/RED-4 structural halves; A-8/G-3).
 *
 * FAILING-FIRST DESIGN: a structural guard over an ABSENT namespace is vacuously
 * green and proves nothing. Each guard therefore first asserts that the granted
 * production surface EXISTS — a genuine GREEN obligation, so the anchor is not an
 * artificial assertion — and then applies its forbidden-shape scan. The one
 * whole-tree guard (RED-4) runs its scans FIRST (they must hold today too) and
 * anchors LAST, so it fails RED by absence while still guarding the present tree.
 *
 * Phase-2 refinement notes (recorded, not hidden — reviewer recommendations,
 * 2026-08-17): (1) once handlers exist, extend the D-1 guard to reflect over
 * ACTUAL constructor signatures, not source text alone; (2) consider an
 * `assertProtocolSequence()` helper so history-order assertions cannot be
 * written incompletely; (3) refusal-propagation policy (record + optionally
 * rethrow) should be pinned explicitly once GREEN fixes it.
 */
final class StructuralApplicationGuardsRedTest extends TestCase
{
    private const APPLICATION_DIR = __DIR__ . '/../../../../../app/Contexts/Election/Application/OperatingCore';

    private const APP_DIR = __DIR__ . '/../../../../../app';

    private const TESTS_DIR = __DIR__ . '/../../../../..' . '/tests';

    private const NS = 'App\\Contexts\\Election\\Application\\OperatingCore\\';

    private const GRANTED_COMMANDS = [
        self::NS . 'Command\\ExpressCommitteePositionCommand',
        self::NS . 'Command\\RecordVacancyEventCommand',
        self::NS . 'Command\\FillCommitteeSeatCommand',
        self::NS . 'Command\\ReportPeriodExpiryCommand',
        self::NS . 'Command\\RecordCommitteeConstitutionCommand',
    ];

    private const GRANTED_HANDLERS = [
        self::NS . 'Handler\\ExpressCommitteePositionHandler',
        self::NS . 'Handler\\RecordVacancyEventHandler',
        self::NS . 'Handler\\FillCommitteeSeatHandler',
        self::NS . 'Handler\\ReportPeriodExpiryHandler',
        self::NS . 'Handler\\RecordCommitteeConstitutionHandler',
    ];

    private const GRANTED_QUERIES = [
        self::NS . 'Query\\GateIntervalStateQuery',
        self::NS . 'Query\\ProgressionEligibilityQuery',
        self::NS . 'Query\\ClockReadingQuery',
        self::NS . 'Query\\RecoveryPeriodStatusQuery',
    ];

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

    private function assertGrantedSurfaceExists(): void
    {
        $this->assertDirectoryExists(
            self::APPLICATION_DIR,
            'The granted Increment-2 application namespace does not exist yet — GREEN (Phase 2) must create it; until then this guard fails RED by absence.'
        );
        foreach ([...self::GRANTED_COMMANDS, ...self::GRANTED_HANDLERS, ...self::GRANTED_QUERIES] as $class) {
            $this->assertTrue(class_exists($class), sprintf('Granted class %s must exist (EM-IMPL-002; names carry EM-OPEN-045 placeholder standing).', $class));
        }
    }

    /** @param list<string> $forbiddenNeedles */
    private function assertApplicationSourcesFreeOf(array $forbiddenNeedles, string $why): void
    {
        $this->assertGrantedSurfaceExists();
        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            $source = (string) file_get_contents($file);
            foreach ($forbiddenNeedles as $needle) {
                $this->assertStringNotContainsString($needle, $source, sprintf('%s — forbidden token "%s" in %s.', $why, $needle, $file));
            }
        }
    }

    /** The anchor: the granted surface itself. Fails RED by absence of production code. */
    public function test_the_granted_application_surface_exists(): void
    {
        $this->assertGrantedSurfaceExists();
    }

    /**
     * RED-4 / W-10: the D-1 wall extends to TESTS. No implementation, fake, mock,
     * stub or "temporary" binding of `OrganisationalAppointmentAuthority` exists
     * anywhere — app/ or tests/, this suite's own doubles included. Scans run
     * first (they bind today's tree too); the anchor runs last (failing-first).
     */
    public function test_red4_no_implementation_fake_mock_or_stub_of_the_authority_port_exists_anywhere(): void
    {
        $portFile = realpath(self::APP_DIR . '/Contexts/Election/Domain/OperatingCore/Port/OrganisationalAppointmentAuthority.php');
        $this->assertNotFalse($portFile, 'The port must remain DECLARED (B-3): a wall, not an omission.');

        foreach ([self::APP_DIR, self::TESTS_DIR] as $root) {
            foreach ($this->phpFilesUnder($root) as $file) {
                $real = realpath($file);
                if ($real === $portFile || $real === realpath(__FILE__)) {
                    continue; // the declaration itself, and this guard's own patterns
                }
                $source = (string) file_get_contents($file);
                if (! str_contains($source, 'OrganisationalAppointmentAuthority')) {
                    continue;
                }
                $this->assertDoesNotMatchRegularExpression(
                    '/implements[^{;]*\bOrganisationalAppointmentAuthority\b/',
                    $source,
                    sprintf('RED-4/W-10 violated: %s implements the external-authority port (D-1: no adapter, default, stub or fallback — anywhere, tests included).', $file)
                );
                $this->assertDoesNotMatchRegularExpression(
                    '/(createMock|getMockBuilder|createStub|createConfiguredMock|prophesize|Mockery::mock|Mockery::spy)\s*\(\s*[^)]*OrganisationalAppointmentAuthority/',
                    $source,
                    sprintf('RED-4/W-10 violated: %s creates a test double of the external-authority port — the wall extends to tests.', $file)
                );
            }
        }

        $this->assertGrantedSurfaceExists();
    }

    /**
     * G-4 "cannot invent authority" + reviewer recommendation (2026-08-17): the
     * application namespace never even REFERENCES the D-1 port — no import, no
     * constructor parameter, no call. The handlers are the entry points a future
     * adapter would call; they never call out to the authority (§3a).
     */
    public function test_g4_the_application_namespace_never_references_the_authority_port(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['OrganisationalAppointmentAuthority'],
            'G-4/D-1: the application layer neither calls nor implements nor names the external authority'
        );
    }

    /**
     * W-1 / RED-1 structural half: no application file may hold both the
     * condition arithmetic and a gate-outcome fact — the canonical forbidden
     * shape `if (unableToFunction) { fail the gate }` cannot compile-in.
     */
    public function test_w1_no_application_file_connects_unable_to_function_to_a_gate_outcome(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            $source = (string) file_get_contents($file);
            $touchesCondition = str_contains($source, 'unableToFunction') || str_contains($source, 'InoperativeOnset');
            if ($touchesCondition) {
                foreach (['GateFailedByDecision', 'GateSatisfied'] as $outcome) {
                    $this->assertStringNotContainsString(
                        $outcome,
                        $source,
                        sprintf('W-1/RED-1 violated (wrong BY ADOPTED RULE EM-GOV-070/071): %s reaches from the operational condition to gate outcome "%s" — a condition never becomes an actor.', $file, $outcome)
                    );
                }
            }
        }
    }

    /** W-2 / RED-2 structural half: the expression path carries NO Inoperative guard — a guard would be Reading B, which the PO did not choose. */
    public function test_w2_the_expression_path_carries_no_inoperative_guard(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            if (! str_contains(basename($file), 'ExpressCommitteePosition')) {
                continue;
            }
            $source = (string) file_get_contents($file);
            foreach (['Inoperative', 'OperationalCondition', 'unableToFunction'] as $guard) {
                $this->assertStringNotContainsString(
                    $guard,
                    $source,
                    sprintf('W-2/RED-2 violated: %s consults the operational condition — Meaning-1 (EM-GOV-071) makes decision facts recordable while Inoperative.', $file)
                );
            }
        }
    }

    /**
     * W-3 / G-1 / G-5: the application layer holds NO rule arithmetic — no
     * threshold math, no ⌈2n/3⌉, no compound policy conditions — and calls no
     * raw-primitive policy directly (the aggregates' trusted-collaborator
     * surfaces are the only rule doors: I-11).
     */
    public function test_w3_g1_g5_no_rule_arithmetic_and_no_raw_policy_calls_in_application_sources(): void
    {
        $this->assertApplicationSourcesFreeOf(
            [
                'intdiv(', 'ceil(', 'floor(', 'round(',
                '2 *', '* 2', '/ 3', '2/3',
                'GateIntervalClassification::', // P-2 raw call — the door is AG-2::intervalState (I-11)
                'UnableToFunction::',           // P-3 raw call — the door is AG-1::unableToFunction
                'ThresholdEvaluation::',        // P-1 raw call — the door is AG-2::requiredVotes
                'ClockAccrual::',               // P-5 raw call — the door is AG-3::readingAt/isExpiredAt
            ],
            'W-3/G-1/G-5: rule arithmetic lives in Domain policies only; the application computes nothing'
        );

        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            $source = (string) file_get_contents($file);
            $this->assertDoesNotMatchRegularExpression(
                '/count\(\)\s*(===|!==|==|!=|<=|>=|<|>)/',
                $source,
                sprintf('G-5 named smell: %s compares a domain count — a business rule surfacing in Application requires explicit architectural review.', $file)
            );
        }
    }

    /** W-4 / DD-1: no application class HOLDS a derived classification — returned, never stored. */
    public function test_w4_no_application_class_stores_a_derived_classification(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            $source = (string) file_get_contents($file);
            $this->assertDoesNotMatchRegularExpression(
                '/(private|protected|public)\s+(readonly\s+)?\??\s*(GateIntervalState|OperationalCondition|ElectionOperationalStatus|ClockReading|HaltedAtGate)\b/',
                $source,
                sprintf('W-4 violated (EM-GOV-065/068; DD-1): %s stores a derived classification — a second truth that drifts.', $file)
            );
        }
    }

    /** W-5 / G-3 / D-4: no deadline, timeout, scheduler, polling loop or civil-time reading anywhere in the application layer. */
    public function test_w5_no_time_mechanism_in_application_sources(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['Deadline', 'Timeout', 'Scheduler', 'Cron', 'ExpiryHook', 'sleep(', 'usleep(', 'microtime', 'DateTime', 'Carbon', 'strtotime', 'time()'],
            'W-5/G-3/D-4: nothing attaches time to an OPEN gate or to inaction; instants arrive only via InstantSource'
        );
    }

    /** W-6 / RED-3 structural half: no lifecycle-advancement surface exists in the application layer — progression is the Chief's. */
    public function test_w6_no_lifecycle_advancement_surface_in_application_sources(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['ElectionConstitution', '->advance', 'advancePhase', 'nextPhase', '->transitionTo', 'PhaseTransition', 'progressTo'],
            'W-6/RED-3/B-1: the gate decides acceptance, never progression; no handler advances a phase on GateSatisfied or restoration'
        );
    }

    /** W-7 (EM-GOV-067; EM-OPEN-049): no invented escalation, notification duty, or default authority where the external authority is silent. */
    public function test_w7_no_invented_escalation_notification_or_default_authority(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['Notification', 'Mailer', '->notify', 'Escalation', 'escalate', 'DefaultAuthority', 'FallbackAuthority'],
            'W-7: EM-GOV-067 expressly declined the duty; no default authority may be invented (EM-OPEN-049)'
        );
    }

    /** W-9 structural half: no empty catch block — a caught refusal is recorded, never smoothed over. */
    public function test_w9_no_empty_catch_block_in_application_sources(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach ($this->phpFilesUnder(self::APPLICATION_DIR) as $file) {
            $source = (string) file_get_contents($file);
            $this->assertDoesNotMatchRegularExpression(
                '/catch\s*\([^)]*\)\s*\{\s*\}/',
                $source,
                sprintf('W-9 violated: %s absorbs an exception silently — refusals and their reasons are material events (EM-GOV-005).', $file)
            );
        }
    }

    /** G-4 "cannot bypass domain": no application source constructs a PRIMARY act fact — those are produced by aggregate acts alone (I-8: the event is the truth). */
    public function test_g4_no_application_source_constructs_a_primary_act_fact(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['new CommitteePositionExpressed', 'new CommitteeSeatVacated', 'new CommitteeSeatFilled'],
            'G-4: primary facts come from the domain act (AG-1/AG-2 methods return them); a handler constructing one bypasses the domain'
        );
    }

    /** G-1: handlers orchestrate ONLY — one public entry point per handler, one governed act each. */
    public function test_g1_each_handler_exposes_exactly_one_public_entry_point(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach (self::GRANTED_HANDLERS as $handlerClass) {
            $reflection = new \ReflectionClass($handlerClass);
            $publicMethods = array_filter(
                $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
                static fn (\ReflectionMethod $m) => ! $m->isConstructor(),
            );
            $this->assertCount(
                1,
                $publicMethods,
                sprintf('G-1: %s must expose exactly one public entry point — one handler, one governed act.', $handlerClass)
            );
        }
    }

    /** A-8 / G-3 (registered, binding on Increment 2): ⛔ no ProcessManager / Saga / WorkflowEngine — "many steps → Saga" is expressly rejected. */
    public function test_a8_g3_no_process_manager_saga_or_workflow_engine(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['ProcessManager', 'Saga', 'WorkflowEngine', 'StateMachine'],
            'A-8/G-3: a process manager enters only on named triggers, never on step-count'
        );
    }

    /** Repo layer rule (+ reviewer recommendation, 2026-08-17): the Application layer is framework-free — no facades, no Eloquent, no Laravel artifact. */
    public function test_application_layer_is_framework_free(): void
    {
        $this->assertApplicationSourcesFreeOf(
            ['Illuminate\\', 'Laravel\\', 'use Carbon\\', 'App\\Models\\', 'App\\Http\\', 'Facades'],
            'Application layer rules: Facades banned, Eloquent banned, framework artifacts banned'
        );
    }

    /**
     * Command DTO discipline (repo Rule 4; A-2; D-8; ADR-T11): final readonly;
     * no identity/credential surface; no caller-supplied instants (instants come
     * from `InstantSource` at handling); no untyped array payloads — the one
     * documented exception is the constitution command's typed seat list.
     */
    public function test_command_dtos_are_final_readonly_and_carry_no_identity_or_time_surface(): void
    {
        $this->assertGrantedSurfaceExists();
        foreach (self::GRANTED_COMMANDS as $commandClass) {
            $reflection = new \ReflectionClass($commandClass);
            $this->assertTrue($reflection->isFinal(), sprintf('%s must be final (repo Rule 10 discipline).', $commandClass));
            $this->assertTrue($reflection->isReadOnly(), sprintf('%s must be readonly — a command is an immutable request record.', $commandClass));

            foreach ($reflection->getProperties() as $property) {
                $this->assertDoesNotMatchRegularExpression(
                    '/voter|ballot|principal|credential|password|session|token|identity/i',
                    $property->getName(),
                    sprintf('A-2/ADR-T11: %s::%s opens an identity surface — the application layer authenticates nobody, and no voter↔vote linkage may enter any command.', $commandClass, $property->getName())
                );
                $this->assertDoesNotMatchRegularExpression(
                    '/instant|timestamp|recordedAt|civil|datetime/i',
                    $property->getName(),
                    sprintf('D-8: %s::%s carries caller-supplied time — instants come from InstantSource only.', $commandClass, $property->getName())
                );

                $type = $property->getType();
                if ($type instanceof \ReflectionNamedType && $type->getName() === 'array') {
                    $this->assertSame(
                        self::NS . 'Command\\RecordCommitteeConstitutionCommand',
                        $commandClass,
                        sprintf('Rule 4 (no arrays): %s::%s is an array — only the constitution command\'s typed CommitteeSeatId list is excepted.', $commandClass, $property->getName())
                    );
                }
                if ($type instanceof \ReflectionNamedType && str_ends_with($type->getName(), 'RecordedInstant')) {
                    $this->fail(sprintf('D-8: %s::%s is a RecordedInstant — commands carry no instants.', $commandClass, $property->getName()));
                }
            }
        }
    }
}
