<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-1, cycle 2 (v3.11 §3 "Claims and slots" and its collision proof):
 * - exactly the 21 claim kinds of v3.11, with 22 key shapes (SOURCE_NAME is single- or set-valued);
 * - a slot key always begins with its kind ("corrected in v3.2": containment and classification once shared a key);
 * - within a kind, the key names exactly what can hold one value at a time;
 * - two different keys never share an encoding, whatever characters their components contain.
 */
final class SlotKeyCollisionTest extends TestCase
{
    private const U1 = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const U2 = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const C1 = '1b2c3d4e-5f6a-4b7c-8d9e-0f1a2b3c4d5e';

    private static function u(string $id = self::U1): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    /** One key of each of the 22 shapes, all about the same unit where a unit appears. */
    private static function oneOfEachShape(): array
    {
        $u = self::u();

        return [
            'existence' => SlotKey::existence($u),
            'containment' => SlotKey::containment($u, 'admin'),
            'classification' => SlotKey::classification($u, 'admin'),
            'boundary' => SlotKey::boundary($u, 'survey-dept'),
            'succession' => SlotKey::succession($u, 'event-2015'),
            'identifier' => SlotKey::identifier($u, 'iso3166-1-alpha2'),
            'grouping' => SlotKey::grouping($u, 'm49'),
            'admission' => SlotKey::admission($u),
            'source name (single)' => SlotKey::sourceName($u, 'constitution-2015', 'ne', 'official'),
            'source name (set)' => SlotKey::sourceNameValue($u, 'constitution-2015', 'ne', 'alternative', 'Koshi'),
            'cldr name' => SlotKey::cldrName($u, 'de', 'standard'),
            'cldr inheritance' => SlotKey::cldrInheritance('cldr-46'),
            'issuer' => SlotKey::issuer('npl-govt'),
            'mandate' => SlotKey::mandate('npl-constitution'),
            'competence' => SlotKey::competence('npl-govt', 'territorial'),
            'precedence' => SlotKey::precedence('constitution-over-act'),
            'dispute' => SlotKey::dispute(ClaimId::fromString(self::C1)),
            'reconciliation' => SlotKey::reconciliation($u),
            'profile scheme' => SlotKey::profileScheme($u),
            'profile tier' => SlotKey::profileTier($u, 'admin', 3),
            'profile class' => SlotKey::profileClass($u, 'admin', 'district'),
            'platform default locale' => SlotKey::platformDefaultLocale(),
        ];
    }

    public function test_exactly_the_21_claim_kinds_of_v3_11(): void
    {
        $this->assertSame(
            ['EXISTENCE', 'CONTAINMENT', 'CLASSIFICATION', 'BOUNDARY', 'SUCCESSION', 'IDENTIFIER', 'GROUPING', 'ADMISSION',
             'SOURCE_NAME', 'CLDR_NAME', 'CLDR_INHERITANCE', 'ISSUER', 'MANDATE', 'COMPETENCE', 'PRECEDENCE', 'DISPUTE',
             'RECONCILIATION', 'PROFILE_SCHEME', 'PROFILE_TIER', 'PROFILE_CLASS', 'PLATFORM_DEFAULT_LOCALE'],
            array_map(static fn (ClaimKind $k): string => $k->value, ClaimKind::cases()),
        );
    }

    public function test_22_shapes_give_22_distinct_keys_each_beginning_with_its_kind(): void
    {
        $keys = self::oneOfEachShape();
        $encodings = array_map(static fn (SlotKey $k): string => $k->encoded(), $keys);

        $this->assertCount(22, $keys);
        $this->assertCount(22, array_unique($encodings), 'no two shapes collide');
        foreach ($keys as $shape => $key) {
            $this->assertStringStartsWith($key->kind()->value . '|', $key->encoded(), "{$shape}: the key begins with its kind");
        }
        $this->assertCount(21, array_unique(array_map(static fn (SlotKey $k): string => $k->kind()->value, $keys)), 'every kind has a shape');
    }

    public function test_containment_and_classification_with_the_same_unit_and_scheme_never_share_a_slot(): void
    {
        $this->assertFalse(SlotKey::containment(self::u(), 'admin')->equals(SlotKey::classification(self::u(), 'admin')), 'the v3.1 defect, corrected in v3.2');
    }

    public function test_within_a_kind_the_key_names_exactly_what_holds_one_value(): void
    {
        $u = self::u();

        $this->assertTrue(SlotKey::containment($u, 'admin')->equals(SlotKey::containment($u, 'admin')), 'same unit and scheme: one slot, claims supersede');
        $this->assertFalse(SlotKey::containment($u, 'admin')->equals(SlotKey::containment($u, 'electoral')), 'parents in different schemes coexist');
        $this->assertFalse(SlotKey::boundary($u, 'survey-dept')->equals(SlotKey::boundary($u, 'osm')), 'several sources coexist');
        $this->assertFalse(SlotKey::identifier($u, 'iso3166-1-alpha2')->equals(SlotKey::identifier($u, 'm49')), 'several namespaces coexist');
        $this->assertFalse(SlotKey::cldrName($u, 'de', 'standard')->equals(SlotKey::cldrName($u, 'de', 'short')), 'forms coexist');
        $this->assertFalse(SlotKey::profileTier($u, 'admin', 3)->equals(SlotKey::profileTier($u, 'admin', 4)), 'one meaning per position');
        $this->assertFalse(SlotKey::existence($u)->equals(SlotKey::existence(self::u(self::U2))), 'different units');
    }

    public function test_single_and_set_valued_source_names_cannot_collide(): void
    {
        $u = self::u();

        $this->assertTrue(SlotKey::sourceName($u, 's', 'ne', 'official')->equals(SlotKey::sourceName($u, 's', 'ne', 'official')), 'one official name per source, language: they supersede');
        $this->assertFalse(SlotKey::sourceNameValue($u, 's', 'ne', 'alternative', 'Koshi')->equals(SlotKey::sourceNameValue($u, 's', 'ne', 'alternative', 'Purwanchal')), 'alternatives coexist: one slot per value');
        foreach ([fn () => SlotKey::sourceName($u, 's', 'ne', 'alternative'), fn () => SlotKey::sourceNameValue($u, 's', 'ne', 'official', 'x'), fn () => SlotKey::sourceName($u, 's', 'ne', 'nickname')] as $wrongType) {
            try {
                $wrongType();
                $this->fail('a name type belongs to exactly one shape');
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_dispute_is_keyed_by_the_target_claim_never_by_the_targets_slot(): void
    {
        $dispute = SlotKey::dispute(ClaimId::fromString(self::C1));

        $this->assertSame(ClaimKind::Dispute, $dispute->kind());
        foreach (self::oneOfEachShape() as $shape => $key) {
            if ($shape !== 'dispute') {
                $this->assertFalse($dispute->equals($key), "a dispute can never supersede a {$shape} value");
            }
        }
    }

    public function test_separator_characters_inside_components_cannot_forge_another_key(): void
    {
        $u = self::u();
        $pairs = [
            [SlotKey::boundary($u, 'a|b'), SlotKey::boundary($u, 'a')],
            [SlotKey::competence('x|competence_kind=1:y', 'z'), SlotKey::competence('x', 'y|z')],
            [SlotKey::sourceName($u, 's|ne', 'ne', 'official'), SlotKey::sourceName($u, 's', 'ne|ne', 'official')],
            [SlotKey::profileClass($u, 'admin|3', 'x'), SlotKey::profileClass($u, 'admin', '3|x')],
            [SlotKey::issuer('5:abcde'), SlotKey::issuer('abcde')],
        ];

        foreach ($pairs as [$a, $b]) {
            $this->assertNotSame($a->encoded(), $b->encoded(), "{$a->encoded()} vs {$b->encoded()}");
        }
    }

    public function test_randomised_keys_never_share_an_encoding_unless_they_are_the_same_key(): void
    {
        $alphabet = ['a', 'b', '|', '=', ':', '1', ' '];
        $tok = static function () use ($alphabet): string {
            $s = 'a';   // never whitespace-only: an empty component is refused by design (tested separately)
            for ($i = 0, $n = random_int(0, 4); $i < $n; $i++) {
                $s .= $alphabet[random_int(0, count($alphabet) - 1)];
            }

            return $s;
        };
        $units = [self::u(), self::u(self::U2)];
        $seen = [];
        for ($i = 0; $i < 3000; $i++) {
            $u = $units[random_int(0, 1)];
            [$shape, $key] = match (random_int(0, 6)) {
                0 => [['containment', $u->value(), $s = $tok()], SlotKey::containment($u, $s)],
                1 => [['classification', $u->value(), $s = $tok()], SlotKey::classification($u, $s)],
                2 => [['boundary', $u->value(), $s = $tok()], SlotKey::boundary($u, $s)],
                3 => [['competence', $a = $tok(), $b = $tok()], SlotKey::competence($a, $b)],
                4 => [['sourceName', $u->value(), $a = $tok(), $b = $tok()], SlotKey::sourceName($u, $a, $b, 'official')],
                5 => [['sourceNameValue', $u->value(), $a = $tok(), $b = $tok(), $c = $tok()], SlotKey::sourceNameValue($u, $a, $b, 'former', $c)],
                6 => [['profileClass', $u->value(), $a = $tok(), $b = $tok()], SlotKey::profileClass($u, $a, $b)],
            };
            $identity = json_encode($shape);
            $enc = $key->encoded();
            if (isset($seen[$enc])) {
                $this->assertSame($seen[$enc], $identity, "collision: {$enc}");
            }
            $seen[$enc] = $identity;
        }
        $this->addToAssertionCount(1);
    }

    public function test_an_empty_component_is_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        SlotKey::containment(self::u(), '');
    }
}
