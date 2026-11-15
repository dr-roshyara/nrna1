<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\ProvenanceJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * COR-4 review condition C-1 (PBDIGIT-329; review F-1): stored provenance is validated by shape on decode, so a
 * damaged value fails with UnexpectedValueException (wrapped by the adapter into StoredMemberLogInvalid), never with a
 * PHP warning, ErrorException or TypeError.
 */
final class ProvenanceJsonTest extends TestCase
{
    /** @return array<string, array{Provenance}> */
    public static function provenances(): array
    {
        return [
            'decision' => [Provenance::decision('registrar-1')],
            'decision with declaration and evidence' => [Provenance::decision('registrar-1', AuthorityEvidence::of('registrar-1', 'secretary', 'statute-4', 'p1', true, Instant::of(1_700_000_000_000_000), false), 'decl-7')],
            'rule' => [Provenance::rule('statute-7', 'v1')],
            'attested' => [Provenance::attested('legacy-import', 'importer-1', 'a note')],
        ];
    }

    #[DataProvider('provenances')]
    public function test_every_provenance_kind_round_trips(Provenance $p): void
    {
        $this->assertEquals($p, ProvenanceJson::decode(ProvenanceJson::encode($p)));
    }

    private const VALID = '{"kind":"decision","actorOrSource":"registrar","declarationRef":null,"ruleRef":null,"ruleVersion":null,"importer":null,"note":null,"authorityEvidence":null}';

    /** @return array<string, array{string}> */
    public static function damaged(): array
    {
        $evidence = '{"actor":"a","capacity":"c","ruleRef":"r","policyVersion":"p","result":true,"checkedAt":1,"competenceVerified":false}';

        return [
            'a list, not an object' => ['[1,2]'],
            'a scalar' => ['5'],
            'no kind' => ['{"actorOrSource":"registrar"}'],
            'an unknown kind' => ['{"kind":"bogus"}'],
            'a kind that is not a string' => ['{"kind":5}'],
            'a decision without its actor' => ['{"kind":"decision"}'],
            'a decision whose actor is a number' => [str_replace('"registrar"', '5', self::VALID)],
            'a decision whose declarationRef is a number' => [str_replace('"declarationRef":null', '"declarationRef":5', self::VALID)],
            'authority evidence that is not an object' => [str_replace('"authorityEvidence":null', '"authorityEvidence":"x"', self::VALID)],
            'authority evidence without checkedAt' => [str_replace('"authorityEvidence":null', '"authorityEvidence":' . str_replace(',"checkedAt":1', '', $evidence), self::VALID)],
            'authority evidence whose result is a string' => [str_replace('"authorityEvidence":null', '"authorityEvidence":' . str_replace('"result":true', '"result":"yes"', $evidence), self::VALID)],
            'a rule without its version' => ['{"kind":"rule","ruleRef":"statute-7"}'],
            'an attestation whose importer is null' => ['{"kind":"attested","actorOrSource":"legacy","importer":null,"note":null}'],
        ];
    }

    #[DataProvider('damaged')]
    public function test_damaged_stored_provenance_fails_as_unexpected_value(string $json): void
    {
        $this->expectException(\UnexpectedValueException::class);

        ProvenanceJson::decode($json);
    }

    public function test_malformed_json_fails_as_json_exception(): void
    {
        $this->expectException(\JsonException::class);

        ProvenanceJson::decode('{');
    }

    /** @return iterable<string, array{\App\Contexts\Membership\Domain\Organisational\AuthorityBasis}> */
    public static function bases(): iterable
    {
        $of = static fn (...$a) => \App\Contexts\Membership\Domain\Organisational\AuthorityBasis::of(...$a);
        $kind = \App\Contexts\Membership\Domain\Organisational\CompetenceKind::class;
        $outcome = \App\Contexts\Membership\Domain\Organisational\AuthorityOutcome::class;

        yield 'authorised, worked out, with decision and evidence' => [$of($outcome::Authorised, null, TimePoint::of(20_000), $kind::WorkedOut, 'resolution:12/part:3', ['sha256:a', 'doc:minutes-7'])];
        yield 'authorised, own act, nothing else' => [$of($outcome::Authorised, null, TimePoint::of(20_001), $kind::OwnAct, null, [])];
        yield 'not authorised, with a reason' => [$of($outcome::NotAuthorised, 'not the member concerned', TimePoint::of(20_002), null, null, [])];
        yield 'undetermined, with a reason' => [$of($outcome::Undetermined, 'rules silent', TimePoint::of(20_003), $kind::GapDeclared, null, ['sha256:b'])];
    }

    /** PBDIGIT-354 S2-3a (D-238): the answer's basis is stored and read back exactly. */
    #[\PHPUnit\Framework\Attributes\DataProvider('bases')]
    public function test_the_authority_basis_round_trips(\App\Contexts\Membership\Domain\Organisational\AuthorityBasis $basis): void
    {
        $authorised = $basis->outcome() === \App\Contexts\Membership\Domain\Organisational\AuthorityOutcome::Authorised;
        $p = Provenance::decision('officer-1', AuthorityEvidence::of('officer-1', 'tenure:7', 'rule:9', 'v3', $authorised, Instant::of(42), true, $basis));

        $this->assertEquals($p, ProvenanceJson::decode(ProvenanceJson::encode($p)));
    }

    /** D-238: evidence stored before the basis existed reads as "not recorded": nothing is inferred or backfilled. */
    public function test_historical_evidence_without_a_basis_reads_as_not_recorded(): void
    {
        $stored = '{"kind":"decision","actorOrSource":"registrar","declarationRef":null,"ruleRef":null,"ruleVersion":null,"importer":null,"note":null,'
            . '"authorityEvidence":{"actor":"registrar","capacity":"organisation_role:owner","ruleRef":"interim:x","policyVersion":"interim-1","result":true,"checkedAt":7,"competenceVerified":false}}';

        $evidence = ProvenanceJson::decode($stored)->authorityEvidence();

        $this->assertNull($evidence->basis());
        $this->assertTrue($evidence->result());
        $this->assertSame('interim:x', $evidence->ruleRef(), 'the six ratified fields as they were');
    }

    /** @return iterable<string, array{string}> */
    public static function damagedBases(): iterable
    {
        $with = static fn (string $basis): string => '{"kind":"decision","actorOrSource":"a","declarationRef":null,"ruleRef":null,"ruleVersion":null,"importer":null,"note":null,'
            . '"authorityEvidence":{"actor":"a","capacity":"c","ruleRef":"r","policyVersion":"v","result":true,"checkedAt":7,"competenceVerified":true,"basis":' . $basis . '}}';

        yield 'not an object' => [$with('"authorised"')];
        yield 'unknown outcome' => [$with('{"outcome":"maybe","reason":null,"evaluatedFor":1,"competenceKind":"worked-out","decisionRef":null,"evidence":[]}')];
        yield 'unknown competence kind' => [$with('{"outcome":"authorised","reason":null,"evaluatedFor":1,"competenceKind":"magic","decisionRef":null,"evidence":[]}')];
        yield 'evaluatedFor not an integer' => [$with('{"outcome":"authorised","reason":null,"evaluatedFor":"x","competenceKind":"worked-out","decisionRef":null,"evidence":[]}')];
        yield 'evidence not a list' => [$with('{"outcome":"authorised","reason":null,"evaluatedFor":1,"competenceKind":"worked-out","decisionRef":null,"evidence":"x"}')];
        yield 'outcome disagrees with result' => [$with('{"outcome":"undetermined","reason":"rules silent","evaluatedFor":1,"competenceKind":null,"decisionRef":null,"evidence":[]}')];
        yield 'authorised with no competence kind' => [$with('{"outcome":"authorised","reason":null,"evaluatedFor":1,"competenceKind":null,"decisionRef":null,"evidence":[]}')];
    }

    /** A damaged stored basis fails as an UnexpectedValueException (the adapter turns it into StoredMemberLogInvalid). */
    #[\PHPUnit\Framework\Attributes\DataProvider('damagedBases')]
    public function test_a_damaged_stored_basis_fails_as_unexpected_value(string $json): void
    {
        $this->expectException(\UnexpectedValueException::class);

        ProvenanceJson::decode($json);
    }
}
