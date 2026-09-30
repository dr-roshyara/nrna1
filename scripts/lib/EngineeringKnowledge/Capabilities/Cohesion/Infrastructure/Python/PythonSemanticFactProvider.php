<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python;

use EngineeringKnowledge\Capabilities\Cohesion\Application\Ports\SemanticFactProvider;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\IndeterminateBehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodRole;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\StateAccess;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use RuntimeException;

/**
 * Python adapter — BOUNDED EXPERIMENT ONLY (grant G-KOS-CONTRACT-PYTHON-PROPERTY-EXPERIMENT,
 * 2026-09-27). Handles exactly the three constructs the experiment names: an
 * @property-backed attribute read, an explicit method invocation, a plain instance
 * attribute read, for a single class with no inheritance. Not a general Python adapter.
 *
 * Semantic interpretation (recognising @property and resolving `self.X` to the correct
 * canonical fact kind) happens in the Python-side AST walk (`extract_facts.py`), using
 * Python's own `ast` module — deliberately not regex, and deliberately not reimplemented
 * here in PHP. This class only deserialises the already-resolved semantic facts into the
 * existing, unmodified Domain types — it performs no interpretation of its own.
 */
final class PythonSemanticFactProvider implements SemanticFactProvider
{
    public function extract(string $source): FactSet
    {
        $script = __DIR__ . '/extract_facts.py';
        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process = proc_open(['python3', $script], $descriptors, $pipes);
        if (!\is_resource($process)) {
            throw new RuntimeException('Failed to start python3 for semantic extraction.');
        }

        fwrite($pipes[0], $source);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new RuntimeException("Python semantic extraction failed (exit {$exitCode}): {$stderr}");
        }

        /** @var array{units: list<array{unit: array{kind:string,name:string}, methods: list<array<string,mixed>>}>} $decoded */
        $decoded = json_decode($stdout, true, flags: JSON_THROW_ON_ERROR);

        return new FactSet(array_map(self::toDeclaredUnit(...), $decoded['units']));
    }

    /** @param array{unit: array{kind:string,name:string}, methods: list<array<string,mixed>>} $unit */
    private static function toDeclaredUnit(array $unit): DeclaredUnit
    {
        $methods = array_map(
            static fn (array $m): MethodFacts => new MethodFacts(
                $m['name'],
                $m['hasBody'],
                array_map(
                    static fn (array $s): StateAccess => new StateAccess($s['propertyName'], self::enumCase(AccessMode::class, $s['accessMode'])),
                    $m['stateAccesses'],
                ),
                array_map(
                    // D-1: a "kind": "Indeterminate" marker (no other fields — nothing
                    // legal to deserialise into a name) constructs the distinct L3 fact
                    // kind instead of BehaviourReference.
                    static fn (array $r): BehaviourReference|IndeterminateBehaviourReference => ($r['kind'] ?? null) === 'Indeterminate'
                        ? new IndeterminateBehaviourReference()
                        : new BehaviourReference(
                            $r['targetMethodName'],
                            self::enumCase(QualifierKind::class, $r['qualifierKind']),
                            self::enumCase(TargetUnitRelation::class, $r['targetUnitRelation']),
                            self::enumCase(ReferenceMode::class, $r['referenceMode']),
                            self::enumCase(AccessMode::class, $r['accessMode']),
                            self::enumCase(Determinability::class, $r['determinability']),
                        ),
                    $m['behaviourReferences'],
                ),
                self::enumCase(MethodRole::class, $m['methodRole']),
            ),
            $unit['methods'],
        );

        $unitName = $unit['unit']['name'];

        return new DeclaredUnit(self::enumCase(UnitKind::class, $unit['unit']['kind']), new UnitIdentity($unitName), $unitName, $methods);
    }

    /**
     * These Domain enums are unbacked (no `: string`) — `::from()` is not available.
     * Case lookup by name via `constant()` is the correct mechanism, not a workaround.
     *
     * @template T of \UnitEnum
     * @param class-string<T> $enumClass
     * @return T
     */
    private static function enumCase(string $enumClass, string $caseName): \UnitEnum
    {
        return constant($enumClass . '::' . $caseName);
    }
}
