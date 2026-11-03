<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Provenance;
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
}
