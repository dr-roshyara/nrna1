<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * TEST ONLY (PBDIGIT-355): publishing fixture claims DIRECTLY under the sequence lock, as PostgresGeographyReadModelTest
 * does, never through PublishRevision; and freezing claims that no publication names (a revision before publication).
 */
trait DirectPublishing
{
    private static function unit(): ReferenceUnitId
    {
        return ReferenceUnitId::fromString((string) Str::uuid());
    }

    private static function c(SlotKey $slot, string $value, string $from = '2000-01-01', ?string $to = null): Claim
    {
        return new Claim(ClaimId::fromString((string) Str::uuid()), $slot, ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act'));
    }

    /** @return list<array<string, mixed>> */
    private static function rows(string $revision, Claim ...$claims): array
    {
        $atlas = PostgresPublishedAtlas::at(DB::connection());
        $rows = [];
        foreach ($claims as $c) {
            $rows[] = [
                'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                'value' => $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => $c->interval->to?->value(),
                'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference,
                'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
            ];
        }

        return $rows;
    }

    /** One publication of the claims; returns its position. */
    private static function publish(Claim ...$claims): int
    {
        return DB::transaction(static function () use ($claims): int {
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_position + 1;
            $revision = (string) Str::uuid();
            foreach (array_chunk(self::rows($revision, ...$claims), 200) as $chunk) {
                DB::table('refgeo_claims')->insert($chunk);
            }
            DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at)
                VALUES (?, 'revision', ?, 1, (SELECT last_roster_version FROM refgeo_sequence WHERE id = 1), 'test-fixture-direct', clock_timestamp())", [$position, $revision]);

            return $position;
        });
    }

    private static function freezeUnpublished(Claim ...$claims): void
    {
        DB::table('refgeo_claims')->insert(self::rows((string) Str::uuid(), ...$claims));
    }
}
