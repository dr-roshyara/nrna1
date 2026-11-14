<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Import\DatasetRefused;
use App\Contexts\ReferenceGeography\Application\Import\EntryKeys;
use App\Contexts\ReferenceGeography\Application\Import\EvidencedDataset;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-8 importer (D-226 Q-G-1, D-232; Principal instruction 2026-11-14, options A + C): an evidenced dataset
 * is a manifest of proposed evidence (authority, URL, publication, retrieval time, file, SHA-256, reuse terms) and the
 * claims that rest on it. Parsing is strict: every claim cites declared evidence, every slot is built by the domain's
 * own SlotKey rules, entries are named by stable keys ("@iso:NP") mapped to deterministic ReferenceUnitIds.
 */
final class EvidencedDatasetTest extends TestCase
{
    private static function manifest(): string
    {
        return (string) file_get_contents(dirname(__DIR__, 4) . '/Fixtures/ReferenceGeographyDatasets/sample/manifest.json');
    }

    /** @param \Closure(array): array $change */
    private static function changed(\Closure $change): string
    {
        return json_encode($change(json_decode(self::manifest(), true)), JSON_THROW_ON_ERROR);
    }

    public function test_a_manifest_parses_into_claims_built_by_the_domain_and_proposed_evidence(): void
    {
        $d = EvidencedDataset::fromJson(self::manifest());

        $this->assertSame(['pbdigit346-synthetic-sample', '1', 6, 2], [$d->dataset, $d->version, count($d->claims), count($d->evidence)]);
        $xa = EntryKeys::unitId('sample:XA');
        $this->assertTrue($d->claims[0]->slot->equals(SlotKey::existence($xa)), '@sample:XA becomes its deterministic unit id');
        $this->assertSame([BasisKind::Instrument, 'sample-registry-1'], [$d->claims[0]->basis->kind, $d->claims[0]->basis->reference]);
        $this->assertSame(ClaimKind::CldrName, $d->claims[4]->slot->kind());
        $this->assertSame([BasisKind::Other, 'cldr:47'], [$d->claims[4]->basis->kind, $d->claims[4]->basis->reference]);
        $e = $d->evidence[0];
        $this->assertSame(['sample-registry-1', 'test-authority', '1960-01-01', 'https://example.invalid/sample-registry', 'sample-registry.csv', 'synthetic test fixture'],
            [$e->id, $e->authority, $e->issuedOn, $e->url, $e->file, $e->reuseTerms]);
        $this->assertSame(64, strlen($e->sha256));
    }

    public function test_entry_keys_and_the_revision_id_are_deterministic(): void
    {
        $this->assertSame(EntryKeys::unitId('iso:NP')->value(), EntryKeys::unitId('iso:NP')->value(), 'the same key, the same identity, in every dataset');
        $this->assertNotSame(EntryKeys::unitId('iso:NP')->value(), EntryKeys::unitId('iso:DE')->value());
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-5[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', EntryKeys::unitId('iso:NP')->value(), 'a version-5 UUID');
        $a = EvidencedDataset::fromJson(self::manifest());
        $this->assertSame($a->revisionId->value(), EvidencedDataset::fromJson(self::manifest())->revisionId->value(), 'one dataset version, one revision');
        $this->assertNotSame($a->revisionId->value(), EvidencedDataset::fromJson(self::changed(static fn (array $m) => ['version' => '2'] + $m))->revisionId->value());
    }

    public function test_a_malformed_manifest_is_refused_with_its_reason(): void
    {
        $cases = [
            'a claim citing undeclared evidence' => [static function (array $m) { $m['claims'][0]['evidence'] = 'nowhere'; return $m; }, 'undeclared evidence'],
            'evidence without its source URL' => [static function (array $m) { unset($m['evidence'][0]['url']); return $m; }, 'url'],
            'evidence without reuse terms' => [static function (array $m) { $m['evidence'][0]['reuse_terms'] = ''; return $m; }, 'reuse_terms'],
            'a checksum that is not SHA-256' => [static function (array $m) { $m['evidence'][0]['sha256'] = 'abc'; return $m; }, 'sha256'],
            'an unknown claim kind' => [static function (array $m) { $m['claims'][0]['slot']['kind'] = 'NONSENSE'; return $m; }, 'Claim 1: Not a slot key'],
            'a slot the domain refuses' => [static function (array $m) { $m['claims'][2]['slot']['name_type'] = 'nickname'; return $m; }, 'Claim 3'],
            'an undeclared entry reference' => [static function (array $m) { $m['claims'][0]['slot']['unit'] = 'not-a-key'; return $m; }, 'Claim 1'],
            'an unknown basis kind' => [static function (array $m) { $m['claims'][0]['basis'] = 'rumour'; return $m; }, "Claim 1: unknown basis 'rumour'"],
            'duplicate evidence ids' => [static function (array $m) { $m['evidence'][1]['id'] = $m['evidence'][0]['id']; return $m; }, 'duplicate'],
        ];
        foreach ($cases as $why => [$change, $reason]) {
            try {
                EvidencedDataset::fromJson(self::changed($change));
                $this->fail("accepted: {$why}");
            } catch (DatasetRefused $e) {
                $this->assertStringContainsString($reason, $e->getMessage(), $why);
            }
        }
        $this->expectException(DatasetRefused::class);
        EvidencedDataset::fromJson('not json');
    }
}
