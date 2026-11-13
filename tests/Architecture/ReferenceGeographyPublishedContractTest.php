<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use Tests\Architecture\Support\ArchitectureRatchet;

/**
 * PBDIGIT-346 G-5a: Reference Geography's published read contract (Application\Published) is the ONLY thing other
 * contexts may depend on (Hexagonal DDD baseline §4; Principal ruling 2026-11-13: explicit published contracts).
 * - no public signature (method parameter, return, public property) names a Domain or Infrastructure type, so a
 *   consumer never imports Geography's internals by using the contract;
 * - every contract type is on the published-language allow-list, and nothing else of Reference Geography is.
 */
final class ReferenceGeographyPublishedContractTest extends TestCase
{
    private const NS = 'App\\Contexts\\ReferenceGeography\\Application\\Published\\';

    /** @return list<class-string> */
    private static function contractTypes(): array
    {
        $types = [];
        foreach (glob(dirname(__DIR__, 2) . '/app/Contexts/ReferenceGeography/Application/Published/*.php') ?: [] as $file) {
            $types[] = self::NS . basename($file, '.php');
        }
        sort($types);

        return $types;
    }

    private static function allowed(?\ReflectionType $type): bool
    {
        if ($type === null) {
            return true;
        }
        if ($type instanceof \ReflectionUnionType || $type instanceof \ReflectionIntersectionType) {
            foreach ($type->getTypes() as $t) {
                if (!self::allowed($t)) {
                    return false;
                }
            }

            return true;
        }
        /** @var \ReflectionNamedType $type */
        return $type->isBuiltin() || str_starts_with($type->getName(), self::NS) || in_array($type->getName(), ['self', 'static'], true) || is_a($type->getName(), \Throwable::class, true);
    }

    public function test_the_contract_exists(): void
    {
        $this->assertContains(self::NS . 'GeographyReadModel', self::contractTypes());
    }

    public function test_no_public_signature_of_the_contract_names_an_internal_type(): void
    {
        foreach (self::contractTypes() as $class) {
            $r = new \ReflectionClass($class);
            foreach ($r->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
                if ($m->getDeclaringClass()->getName() !== $class) {
                    continue;   // inherited from \RuntimeException
                }
                $this->assertTrue(self::allowed($m->getReturnType()), "{$class}::{$m->getName()} returns an internal type");
                foreach ($m->getParameters() as $p) {
                    $this->assertTrue(self::allowed($p->getType()), "{$class}::{$m->getName()}(\${$p->getName()}) takes an internal type");
                }
            }
            foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
                $this->assertTrue(self::allowed($prop->getType()), "{$class}::\${$prop->getName()} is an internal type");
            }
        }
    }

    public function test_exactly_the_contract_is_published_language(): void
    {
        $listed = array_values(array_filter(
            ArchitectureRatchet::loadList(__DIR__ . '/baselines/published-language.json'),
            static fn (string $t): bool => str_starts_with($t, 'App\\Contexts\\ReferenceGeography\\'),
        ));
        sort($listed);

        $this->assertSame(self::contractTypes(), $listed);
    }
}
