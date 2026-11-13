<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * TEST ONLY (G-4g): the competence claim every territorial fixture relies on (D-82), published once per run DIRECTLY
 * (frozen claim + publication row, under the publication lock, through the G-4a guards), never through
 * PublishRevision or the ledger. A fixture must not depend on the code a mutation test changes; otherwise a mutant
 * kills the setup instead of failing an assertion (G-4g, mutant G4). Shared by every Geography PostgreSQL test so no
 * class relies on another having run first.
 */
final class CompetenceBootstrap
{
    public static function ensure(): void
    {
        $c = CompetentInstrument::competenceClaim();
        if (!PostgresPublishedAtlas::at(DB::connection())->history($c->slot)->priorAt(ValidDate::fromString('1960-01-01'))->isAbsent()) {
            return;
        }
        DB::transaction(static function () use ($c): void {
            $position = (int) DB::selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_position + 1;
            $revision = (string) Str::uuid();
            $atlas = PostgresPublishedAtlas::at(DB::connection());
            DB::table('refgeo_claims')->insert([
                'claim_id' => $c->id->value(), 'revision_id' => $revision, 'content_version' => 1, 'kind' => $c->slot->kind()->value, 'slot_key' => $c->slot->encoded(),
                'value' => $c->value->value(), 'valid_from' => $c->interval->from->value(), 'valid_to' => $c->interval->to?->value(),
                'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference,
                'before_image' => PostgresRevisionRepository::beforeImageJson(BeforeImage::of($atlas->history($c->slot), $c->interval, $atlas->p0())),
            ]);
            DB::insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, recorded_at)
                VALUES (?, 'revision', ?, 1, (SELECT last_roster_version FROM refgeo_sequence WHERE id = 1), 'test-fixture-bootstrap', clock_timestamp())", [$position, $revision]);
        });
    }
}
