<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Identity;

use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Identity\Infrastructure\PostgresIssuedReferences;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * PBDIGIT-349 step I-1 on PostgreSQL (I-TDD-02 §4 INV-REF-1, INV-REF-2; P-I2 = L1, a stored table):
 * - (O, E) → R is stored once: issuing again returns the same R, also when a concurrent issuance wins the insert;
 * - R is unique and names one organisation; the same entry id in two organisations gives two references;
 * - a value collision is retried with a fresh value, never reused;
 * - a reference row is never changed or deleted; production wiring binds the PostgreSQL adapter.
 * Central database only (D-164): no organisation database is touched.
 */
final class PostgresIssuedReferencesTest extends MembershipPostgresTestCase
{
    private static function org(): TenantId
    {
        return TenantId::fromString((string) Str::uuid());
    }

    private static function entry(): LocalEntryRef
    {
        return LocalEntryRef::fromString('entry-' . Str::random(12));
    }

    public function test_production_wiring_binds_the_postgres_adapter(): void
    {
        $this->assertInstanceOf(PostgresIssuedReferences::class, app(IssuedReferences::class));
    }

    public function test_one_reference_per_organisation_entry(): void
    {
        $refs = new PostgresIssuedReferences(DB::connection());
        [$o, $e] = [self::org(), self::entry()];

        $r = $refs->issue($o, $e);

        $this->assertTrue($r->equals($refs->issue($o, $e)), 'issuing again returns the same reference');
        $this->assertSame(1, DB::table('identity_issued_references')->where('organisation_id', $o->value())->where('entry_ref', $e->value())->count());
        $this->assertTrue($refs->isIssuedTo($r, $o));
        $this->assertFalse($refs->isIssuedTo($r, self::org()), 'issued to exactly one organisation');
        $this->assertFalse($refs->isIssuedTo(OrganisationPersonRef::fromString(str_repeat('0', 32)), $o), 'an unknown reference is issued to nobody');
    }

    public function test_the_same_entry_id_in_two_organisations_gives_two_references(): void
    {
        $refs = new PostgresIssuedReferences(DB::connection());
        $e = self::entry();

        $this->assertFalse($refs->issue(self::org(), $e)->equals($refs->issue(self::org(), $e)));
    }

    public function test_a_concurrent_issuance_that_wins_the_insert_is_returned(): void
    {
        [$o, $e] = [self::org(), self::entry()];
        $winner = bin2hex(random_bytes(16));
        $mine = bin2hex(random_bytes(16));
        // the seam runs after the "not yet issued" check: a competitor commits (O, E) → winner in between
        $refs = new PostgresIssuedReferences(DB::connection(), static function () use ($o, $e, $winner, $mine): string {
            DB::insert('INSERT INTO identity_issued_references (ref, organisation_id, entry_ref) VALUES (?, ?, ?) ON CONFLICT DO NOTHING', [$winner, $o->value(), $e->value()]);

            return $mine;
        });

        $this->assertSame($winner, $refs->issue($o, $e)->value(), 'the committed reference is returned; mine is discarded');
        $this->assertSame(0, DB::table('identity_issued_references')->where('ref', $mine)->count());
    }

    public function test_a_value_collision_is_retried_with_a_fresh_value(): void
    {
        $taken = (new PostgresIssuedReferences(DB::connection()))->issue(self::org(), self::entry());
        $fresh = bin2hex(random_bytes(16));
        $values = [$taken->value(), $fresh];
        $refs = new PostgresIssuedReferences(DB::connection(), static function () use (&$values): string {
            return array_shift($values);
        });
        [$o, $e] = [self::org(), self::entry()];

        $this->assertSame($fresh, $refs->issue($o, $e)->value(), 'a value already issued elsewhere is never reused');
        $this->assertFalse($refs->isIssuedTo($taken, $o));
    }

    public function test_a_reference_is_never_changed_or_deleted(): void
    {
        $r = (new PostgresIssuedReferences(DB::connection()))->issue(self::org(), self::entry());

        foreach ([
            fn () => DB::update('UPDATE identity_issued_references SET entry_ref = ? WHERE ref = ?', ['other', $r->value()]),
            fn () => DB::delete('DELETE FROM identity_issued_references WHERE ref = ?', [$r->value()]),
        ] as $i => $change) {
            try {
                DB::transaction($change);
                $this->fail("change {$i} was allowed");
            } catch (QueryException $e) {
                $this->assertStringContainsString('never changed or deleted', $e->getMessage());
            }
        }
    }
}
