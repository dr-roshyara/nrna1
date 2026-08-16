<?php

declare(strict_types=1);

/**
 * LCOM4 observation collector (Hitz & Montazeri).
 *
 * LCOM4 = number of connected components in the method graph of a class,
 * where methods are connected when they share an instance variable or one
 * uses the other as a behaviour of the same class. Constructors/destructors
 * are excluded (they touch everything and would mask real cohesion splits).
 *
 * The internal behavioural relationship is determined by the dependency, NOT
 * by the syntax expressing it: $this->m(), self::m(), static::m() and
 * OwnClass::m() are the same relationship. parent::m() is not — it uses
 * inherited behaviour outside the analysed class. Dynamic targets and
 * first-class callables are outside the contract. The pinned decisions in
 * examples/lcom4/expected.json are the source of truth; this file conforms
 * to them, never the reverse.
 *
 * Scope guard: emits OBSERVATIONS only — metric · class · value ·
 * interpretation. No thresholds, no warnings, no verdicts. Whether a value
 * matters is configured governance's question, never the collector's.
 *
 * Parsing: nikic/php-parser (already a repo dependency — reused, not built).
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

final class Lcom4Collector
{
    /**
     * @return list<array{metric:string,class:string,value:int,interpretation:string}>
     */
    public static function collect(string $phpSource): array
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse($phpSource) ?? [];
        $finder = new NodeFinder();

        $observations = [];
        foreach ($finder->findInstanceOf($ast, Node\Stmt\Class_::class) as $class) {
            $observations[] = self::observeClass($class, $finder);
        }
        return $observations;
    }

    /** @return array{metric:string,class:string,value:int,interpretation:string} */
    private static function observeClass(Node\Stmt\Class_ $class, NodeFinder $finder): array
    {
        $ownName = $class->name?->toString();

        $methods = [];   // name => ['props' => set, 'calls' => set]
        foreach ($class->getMethods() as $method) {
            $name = $method->name->toString();
            if (in_array($name, ['__construct', '__destruct'], true)) {
                continue;
            }
            $props = [];
            $calls = [];
            foreach ($finder->findInstanceOf($method, Node\Expr\PropertyFetch::class) as $fetch) {
                if ($fetch->var instanceof Node\Expr\Variable && $fetch->var->name === 'this'
                    && $fetch->name instanceof Node\Identifier) {
                    $props[$fetch->name->toString()] = true;
                }
            }
            // Internal behavioural relationship, reached through the object.
            foreach ($finder->findInstanceOf($method, Node\Expr\MethodCall::class) as $call) {
                if ($call->var instanceof Node\Expr\Variable && $call->var->name === 'this'
                    && $call->name instanceof Node\Identifier
                    && !self::isFirstClassCallable($call)) {
                    $calls[$call->name->toString()] = true;
                }
            }
            // Same relationship, reached through the class (self:: / static:: /
            // OwnClass::). parent:: is deliberately NOT here — see intra_class_calls.
            foreach ($finder->findInstanceOf($method, Node\Expr\StaticCall::class) as $call) {
                if ($call->class instanceof Node\Name
                    && $call->name instanceof Node\Identifier
                    && self::namesThisClass($call->class, $ownName)
                    && !self::isFirstClassCallable($call)) {
                    $calls[$call->name->toString()] = true;
                }
            }
            $methods[$name] = ['props' => array_keys($props), 'calls' => array_keys($calls)];
        }

        $value = self::connectedComponents($methods);

        return [
            'metric'         => 'LCOM4',
            'class'          => $class->name !== null ? $class->name->toString() : '(anonymous)',
            'value'          => $value,
            'interpretation' => match (true) {
                $value === 0 => 'no analyzable methods',
                $value === 1 => 'single connected component (cohesive)',
                default      => sprintf('%d connected components (disjoint responsibility clusters)', $value),
            },
        ];
    }

    /**
     * Does this static call name a method OF THIS CLASS?
     *
     * self:: and static:: do by definition. The class's own name spelled out is
     * the same relationship written differently. parent:: does NOT — it targets
     * inherited behaviour, which is outside the analysed class (inherited_methods).
     */
    private static function namesThisClass(Node\Name $class, ?string $ownName): bool
    {
        $ref = $class->toString();
        if (in_array(strtolower($ref), ['self', 'static'], true)) {
            return true;
        }
        if (strtolower($ref) === 'parent') {
            return false;
        }
        // Own name as written. Namespaced/aliased spellings are not resolved —
        // the class is analysed in isolation (known limitation).
        return $ownName !== null && strtolower($ref) === strtolower($ownName);
    }

    /**
     * First-class callable syntax — m(...) — REFERENCES a behaviour rather than
     * invoking it, and is outside this contract by decision (PO/ARB 2026-08-16).
     */
    private static function isFirstClassCallable(Node\Expr\MethodCall|Node\Expr\StaticCall $call): bool
    {
        foreach ($call->args as $arg) {
            if ($arg instanceof Node\VariadicPlaceholder) {
                return true;
            }
        }
        return false;
    }

    /** @param array<string,array{props:list<string>,calls:list<string>}> $methods */
    private static function connectedComponents(array $methods): int
    {
        $names = array_keys($methods);
        if ($names === []) {
            return 0;
        }

        // union-find over method names
        $parent = array_combine($names, $names);
        $find = function (string $m) use (&$parent, &$find): string {
            return $parent[$m] === $m ? $m : $parent[$m] = $find($parent[$m]);
        };
        $union = function (string $a, string $b) use (&$parent, $find): void {
            $parent[$find($a)] = $find($b);
        };

        // edge: shared instance variable
        $propOwners = [];
        foreach ($methods as $name => $info) {
            foreach ($info['props'] as $prop) {
                if (isset($propOwners[$prop])) {
                    $union($name, $propOwners[$prop]);
                } else {
                    $propOwners[$prop] = $name;
                }
            }
        }
        // edge: method call within the class
        foreach ($methods as $name => $info) {
            foreach ($info['calls'] as $callee) {
                if (isset($methods[$callee])) {
                    $union($name, $callee);
                }
            }
        }

        return count(array_unique(array_map($find, $names)));
    }
}
