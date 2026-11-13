<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2d-2 (v3.11 §5 "D-82: territorial claims need territorial competence", "Evidence basis"; D-82; D-227):
 * - an authority-dependent instrument records its issuer and issue/adoption date (and the mandates and precedence
 *   rules it cites);
 * - a territorial claim resting on an instrument needs (COMPETENCE, issuer, territorial) in force ON THE ISSUE DATE,
 *   not throughout the fact's validity (an abolished authority's earlier instruments stay valid);
 * - cited mandates and precedence rules must be in force on that date;
 * - a dispute on relied-upon authority evidence is recorded and visible; it never refuses by itself;
 * - every read is recorded.
 */
final class CompetenceAndEvidenceTest extends TestCase
{
    private const UNIT = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const PARENT = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    private static int $n = 900;

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval, string $evidence = 'act-1990', BasisKind $basis = BasisKind::Instrument): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis($basis, $evidence));
    }

    private static function instrument(string $id = 'act-1990', ?string $issuer = 'npl-govt', ?string $issuedOn = '1990-05-01', array $mandates = [], array $rules = []): EvidenceVersion
    {
        return new EvidenceVersion($id, self::FP, $issuer, $issuedOn === null ? null : ValidDate::fromString($issuedOn), $mandates, $rules);
    }

    /** The parent exists; npl-govt holds territorial competence over [1980, 1995) unless overridden. */
    private static function atlas(array $more = [], string $competenceFrom = '1980-01-01', ?string $competenceTo = '1995-01-01'): InMemoryPublishedAtlas
    {
        $position = 0;
        $claims = [
            self::claim(SlotKey::existence(ReferenceUnitId::fromString(self::PARENT)), 'exists', self::iv('1970-01-01')),
            // a fixed id, so a dispute built in a test targets the same claim in every atlas built here
            new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000000001'), SlotKey::competence('npl-govt', 'territorial'), ClaimValue::of('stated'), self::iv($competenceFrom, $competenceTo), new Basis(BasisKind::Other, 'gazette')),
            ...$more,
        ];

        return new InMemoryPublishedAtlas(count($claims), array_map(static function (Claim $c) use (&$position): PublishedClaim { $position++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position); }, $claims));
    }

    private static function containment(string $from = '1990-06-01', string $evidence = 'act-1990'): Claim
    {
        return self::claim(SlotKey::containment(ReferenceUnitId::fromString(self::UNIT), 'admin'), self::PARENT, self::iv($from), $evidence);
    }

    private static function validate(array $claims, array $evidence, ?InMemoryPublishedAtlas $atlas = null)
    {
        $byId = [];
        foreach ($evidence as $e) {
            $byId[$e->id] = $e;
        }

        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas ?? self::atlas()), $byId);
    }

    private static function rules($result): array
    {
        return array_map(static fn ($v): string => $v->rule, $result->violations);
    }

    public function test_an_instrument_records_issuer_and_issue_date_together(): void
    {
        $e = self::instrument();

        $this->assertSame(['npl-govt', '1990-05-01'], [$e->issuer, $e->issuedOn?->value()]);
        $this->assertNull((new EvidenceVersion('dup-search-1', self::FP))->issuer, 'evidence that is no instrument carries none');
        $this->expectException(\InvalidArgumentException::class);
        new EvidenceVersion('act-x', self::FP, 'npl-govt', null);
    }

    public function test_a_territorial_claim_by_a_competent_issuer_on_the_issue_date_is_valid_and_the_read_is_recorded(): void
    {
        $result = self::validate([self::containment()], [self::instrument()]);

        $this->assertTrue($result->isValid());
        $this->assertContains((new SlotDependency(SlotKey::competence('npl-govt', 'territorial'), self::iv('1990-05-01', '1990-05-02')))->describe(), $result->dependencies->describe());
    }

    public function test_an_abolished_authoritys_earlier_instrument_stays_valid_d227(): void
    {
        // competence ends 1995; the instrument is of 1990; the fact still holds in 2020
        $this->assertTrue(self::validate([self::containment('1990-06-01')], [self::instrument()])->isValid());
    }

    public function test_no_competence_on_the_issue_date_is_refused(): void
    {
        $this->assertSame(['D-82'], self::rules(self::validate([self::containment()], [self::instrument(issuedOn: '1996-01-01')])), 'issued after competence ended');
        $this->assertSame(['D-82'], self::rules(self::validate([self::containment()], [self::instrument(issuer: 'other-body')])), 'never competent');
    }

    public function test_a_territorial_claim_on_an_instrument_without_issuer_is_refused(): void
    {
        $this->assertSame(['D-82_ISSUER_UNKNOWN'], self::rules(self::validate([self::containment()], [new EvidenceVersion('act-1990', self::FP)])));
    }

    public function test_a_non_territorial_claim_needs_no_competence(): void
    {
        $name = self::claim(SlotKey::sourceName(ReferenceUnitId::fromString(self::UNIT), 'constitution', 'ne', 'official'), 'Koshi', self::iv('2015-09-20'), 'act-1990', BasisKind::Other);

        $this->assertTrue(self::validate([$name], [self::instrument(issuer: 'other-body')])->isValid());
    }

    public function test_cited_mandates_and_precedence_rules_must_be_in_force_on_the_issue_date(): void
    {
        $mandate = self::claim(SlotKey::mandate('npl-constitution-1990'), 'in-force', self::iv('1990-11-09'), 'gazette', BasisKind::Other);
        $rule = self::claim(SlotKey::precedence('constitution-over-act'), 'in-force', self::iv('1980-01-01'), 'gazette', BasisKind::Other);
        $atlas = self::atlas([$mandate, $rule]);

        $this->assertSame(['EVIDENCE_MANDATE'], self::rules(self::validate([self::containment()], [self::instrument(mandates: ['npl-constitution-1990'])], $atlas)), 'the mandate starts after the issue date');
        $this->assertTrue(self::validate([self::containment()], [self::instrument(issuedOn: '1991-01-01', mandates: ['npl-constitution-1990'], rules: ['constitution-over-act'])], $atlas)->isValid());
        $this->assertSame(['EVIDENCE_PRECEDENCE'], self::rules(self::validate([self::containment()], [self::instrument(rules: ['unknown-rule'])], $atlas)));
    }

    public function test_a_dispute_on_relied_upon_authority_is_visible_but_does_not_refuse(): void
    {
        $atlas = self::atlas();
        $competenceClaim = $atlas->history(SlotKey::competence('npl-govt', 'territorial'))->claimAt(ValidDate::fromString('1990-05-01'));
        $dispute = self::claim(SlotKey::dispute($competenceClaim->id), 'disputed', self::iv('1990-01-01'), 'objection-7', BasisKind::Other);
        $disputed = self::atlas([$dispute]);

        $result = self::validate([self::containment()], [self::instrument()], $disputed);

        $this->assertTrue($result->isValid(), 'a dispute alone never invalidates (D-227 (4))');
        $this->assertSame([$competenceClaim->id->value()], array_map(static fn (ClaimId $c): string => $c->value(), $result->disputedReliances));
        $this->assertContains((new SlotDependency(SlotKey::dispute($competenceClaim->id), self::iv('1990-05-01', '1990-05-02')))->describe(), $result->dependencies->describe(), 'its dispute state is a recorded read');
    }

    public function test_a_territorial_claim_must_rest_on_an_instrument_so_its_competence_can_be_checked(): void
    {
        $unit = ReferenceUnitId::fromString(self::UNIT);
        $onOther = self::claim(SlotKey::containment($unit, 'admin'), self::PARENT, self::iv('1990-06-01'), 'note', BasisKind::Other);
        $cartographic = self::claim(SlotKey::boundary($unit, 'survey-dept'), 'polygon-ref', self::iv('1990-06-01'), 'map', BasisKind::AttributedCartographicSource);   // F-2: was Other

        $this->assertSame(['D-82_BASIS'], self::rules(self::validate([$onOther], [])), 'no instrument, no issuer, no competence');
        $this->assertTrue(self::validate([$cartographic], [])->isValid(), 'v3.11 §3: a boundary may rest on a cartographic source attributed as such');
        // D-229: the former duplicate-search exception for EXISTENCE is gone; see DuplicateSearchEvidenceTest
    }

    public function test_the_next_day_of_a_valid_date(): void
    {
        $this->assertSame(['1990-05-02', '2016-03-01', '2017-01-01'], [ValidDate::fromString('1990-05-01')->nextDay()->value(), ValidDate::fromString('2016-02-29')->nextDay()->value(), ValidDate::fromString('2016-12-31')->nextDay()->value()]);
    }
}
