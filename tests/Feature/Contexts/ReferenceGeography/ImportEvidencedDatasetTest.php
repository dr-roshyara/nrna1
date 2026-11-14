<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\GeographyActRefused;
use App\Contexts\ReferenceGeography\Application\Import\DatasetRefused;
use App\Contexts\ReferenceGeography\Application\Import\EntryKeys;
use App\Contexts\ReferenceGeography\Application\ImportEvidencedDataset;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Application\RevisionService;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Infrastructure\LocalEvidenceFiles;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlasSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-8 importer (options A + C, 2026-11-14), on the isolated cluster with a SYNTHETIC dataset only:
 * - the import verifies every evidence file against its recorded SHA-256, then prepares ONE DRAFT revision holding all
 *   claims and evidence, in one save (all or nothing), through RevisionService (the authorised path; the actor must be
 *   appointed);
 * - it publishes nothing and assesses nothing: assessing, validating, submitting, approving and publishing stay the
 *   stewards' acts (D-84). A working importer is not accepted data;
 * - one dataset version is one revision: a second import is refused, nothing changes.
 */
final class ImportEvidencedDatasetTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private const SAMPLE = 'tests/Fixtures/ReferenceGeographyDatasets/sample';

    private static function acting(string $who): ActingPerson
    {
        return new class (self::p($who)) implements ActingPerson {
            public function __construct(private readonly PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person;
            }
        };
    }

    private static function revisions(string $who): RevisionService
    {
        return new RevisionService(self::repo(), self::store(), new PostgresPublishedAtlasSource(DB::connection()), self::acting($who), new PublicationGovernance(), new RevisionValidator());
    }

    private static function importer(string $who): ImportEvidencedDataset
    {
        return new ImportEvidencedDataset(self::revisions($who), new LocalEvidenceFiles());
    }

    /** A private copy of the sample with a unique dataset name (the cluster keeps data between tests). */
    private static function sample(string $t, ?\Closure $change = null): string
    {
        $dir = sys_get_temp_dir() . "/pbdigit346-g8-{$t}";
        @mkdir($dir);
        foreach (['sample-registry.csv', 'sample-cldr.txt'] as $f) {
            copy(base_path(self::SAMPLE . "/{$f}"), "{$dir}/{$f}");
        }
        $m = json_decode((string) file_get_contents(base_path(self::SAMPLE . '/manifest.json')), true);
        $m['dataset'] .= "-{$t}";
        file_put_contents("{$dir}/manifest.json", json_encode($change === null ? $m : $change($m), JSON_THROW_ON_ERROR));
        if ($change === null) {
            return $dir;
        }

        return $dir;
    }

    public function test_a_dataset_becomes_one_draft_revision_with_its_evidence_and_nothing_is_published(): void
    {
        self::ensureCompetence();
        $t = Str::random(8);
        self::appoint("s-$t");
        $before = self::lastPosition();

        $result = self::importer("s-$t")->import(self::sample($t));

        $row = DB::selectOne('SELECT state FROM refgeo_revisions WHERE id = ?', [$result->revisionId]);
        $this->assertSame(['draft', 6, 2, []], [$row->state, $result->claims, $result->evidence, $result->violations], 'a draft; the validation preview is clean');
        $this->assertSame($before, self::lastPosition(), 'nothing published');
        $stored = self::repo()->get(\App\Contexts\ReferenceGeography\Domain\RevisionId::fromString($result->revisionId))->revision;
        $this->assertCount(6, $stored->claims());
        $registry = array_values(array_filter($stored->snapshot()->evidence, static fn ($e) => $e->id === 'sample-registry-1'))[0];
        $this->assertSame([hash_file('sha256', base_path(self::SAMPLE . '/sample-registry.csv')), 'test-authority'], [$registry->fingerprint, $registry->issuer], 'the fingerprint is the file\'s SHA-256');
    }

    public function test_a_file_that_does_not_match_its_checksum_or_is_missing_is_refused_and_nothing_is_written(): void
    {
        $t = Str::random(8);
        self::appoint("s-$t");
        $dir = self::sample($t);
        file_put_contents("{$dir}/sample-registry.csv", "\nTAMPERED", FILE_APPEND);
        $u = Str::random(8);
        $missing = self::sample($u);
        unlink("{$missing}/sample-cldr.txt");

        foreach ([[$dir, 'sample-registry.csv'], [$missing, 'sample-cldr.txt']] as [$d, $file]) {
            try {
                self::importer("s-$t")->import($d);
                $this->fail("imported despite {$file}");
            } catch (DatasetRefused $e) {
                $this->assertStringContainsString($file, $e->getMessage());
            }
        }
        $this->assertSame(0, (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_revisions WHERE id IN (?, ?)', [
            EntryKeys::revisionId("pbdigit346-synthetic-sample-$t", '1')->value(), EntryKeys::revisionId("pbdigit346-synthetic-sample-$u", '1')->value()])->n);
    }

    public function test_one_dataset_version_is_imported_once_and_only_by_an_appointed_steward(): void
    {
        self::ensureCompetence();
        $t = Str::random(8);
        self::appoint("s-$t");
        $dir = self::sample($t);
        self::importer("s-$t")->import($dir);

        try {
            self::importer("s-$t")->import($dir);
            $this->fail('imported twice');
        } catch (DatasetRefused $e) {
            $this->assertStringContainsString('already imported', $e->getMessage());
        }

        $u = Str::random(8);
        try {
            self::importer("nobody-$u")->import(self::sample($u));
            $this->fail('an actor who is not appointed imported a dataset');
        } catch (GeographyActRefused) {
            $this->addToAssertionCount(1);
        }
        // mutant I4: refused only later (at the validation preview) would leave a draft behind; nothing may be written
        $this->assertNull(DB::selectOne('SELECT 1 AS x FROM refgeo_revisions WHERE id = ?', [EntryKeys::revisionId("pbdigit346-synthetic-sample-$u", '1')->value()]), 'refused before anything is written');
    }

    public function test_the_imported_draft_goes_through_the_ordinary_governed_path_to_publication(): void
    {
        self::ensureCompetence();
        $t = Str::random(8);
        self::appoint("s-$t", "x-$t");
        $result = self::importer("s-$t")->import(self::sample($t));
        $id = \App\Contexts\ReferenceGeography\Domain\RevisionId::fromString($result->revisionId);
        self::declare("s-$t", $id);
        self::declare("x-$t", $id);

        $steward = self::revisions("s-$t");
        foreach (AssessmentKind::required() as $kind) {
            $steward->recordAssessment($id, $kind, 'satisfied (test)');
        }
        $this->assertSame([], $steward->validate($id)->violations);
        $steward->submit($id);
        self::revisions("x-$t")->approve($id);
        $out = self::publisher("s-$t")->publish($id);

        $this->assertSame(PublicationStatus::Published, $out->status, implode(',', array_map(static fn ($r) => $r->value, $out->refusals)));
        $geo = new PublishedGeography(new PostgresGeographyReadSource(DB::connection()), new PostgresPublicationLedger(DB::connection()));
        $xa = EntryKeys::unitId('sample:XA')->value();
        $place = $geo->place($xa, '2026-01-01', new Knowledge($out->position));
        $this->assertSame([true, ['iso3166-1-alpha2' => 'XA']], [$place->exists, $place->identifiers]);
        $this->assertSame(['Probeland', '47'], [$geo->displayName($xa, 'de', '2026-01-01', new Knowledge($out->position))->value, $geo->displayName($xa, 'de', '2026-01-01', new Knowledge($out->position))->cldrRelease]);
    }
}
