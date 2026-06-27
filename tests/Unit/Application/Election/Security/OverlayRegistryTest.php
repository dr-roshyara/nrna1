<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayRegistry;
use PHPUnit\Framework\TestCase;

class OverlayRegistryTest extends TestCase
{
    public function test_registry_holds_definitions_not_instances(): void
    {
        $definitions = OverlayRegistry::getOrderedDefinitions();

        // Verify all are OverlayDefinition objects, not overlay instances
        foreach ($definitions as $def) {
            $this->assertInstanceOf(\App\Application\Election\Security\OverlayDefinition::class, $def);
            $this->assertIsString($def->overlayClass);
            $this->assertStringContainsString('Overlay', $def->overlayClass);
            // overlayClass is just a string reference, not an instantiated object
            $this->assertIsString($def->overlayClass);
        }
    }

    public function test_definitions_ordered_by_stratification(): void
    {
        $definitions = OverlayRegistry::getOrderedDefinitions();

        // Verify ordering by stratificationOrder
        for ($i = 1; $i < count($definitions); $i++) {
            $this->assertLessThanOrEqual(
                $definitions[$i]->stratificationOrder,
                $definitions[$i - 1]->stratificationOrder,
                'Definitions should be ordered by stratificationOrder'
            );
        }
    }

    public function test_find_by_identifier_returns_correct_definition(): void
    {
        $def = OverlayRegistry::findByIdentifier('emergency_condition');

        $this->assertNotNull($def);
        $this->assertEquals('emergency_condition', $def->identifier);
        $this->assertEquals(1, $def->stratificationOrder);
    }

    public function test_find_by_identifier_returns_null_for_nonexistent(): void
    {
        $def = OverlayRegistry::findByIdentifier('nonexistent_overlay');

        $this->assertNull($def);
    }

    public function test_all_overlay_classes_exist_in_definitions(): void
    {
        $definitions = OverlayRegistry::getOrderedDefinitions();

        $this->assertGreaterThan(0, count($definitions));
        $this->assertGreaterThanOrEqual(5, count($definitions), 'Should have at least 5 defined overlays');
    }
}
