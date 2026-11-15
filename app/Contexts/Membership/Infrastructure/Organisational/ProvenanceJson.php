<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\ProvenanceKind;

/** Provenance ⇄ jsonb, field for field (RA-7). */
final class ProvenanceJson
{
    public static function encode(Provenance $p): string
    {
        $e = $p->authorityEvidence();

        return json_encode([
            'kind' => $p->kind()->value,
            'actorOrSource' => $p->actorOrSource(),
            'declarationRef' => $p->declarationRef(),
            'ruleRef' => $p->ruleRef(),
            'ruleVersion' => $p->ruleVersion(),
            'importer' => $p->importer(),
            'note' => $p->note(),
            'authorityEvidence' => $e === null ? null : [
                'actor' => $e->actor(),
                'capacity' => $e->capacity(),
                'ruleRef' => $e->ruleRef(),
                'policyVersion' => $e->policyVersion(),
                'result' => $e->result(),
                'checkedAt' => $e->checkedAt()->ordinal(),
                'competenceVerified' => $e->competenceVerified(),
            ] + ($e->basis() === null ? [] : ['basis' => self::encodeBasis($e->basis())]),
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * S2-3a (D-238): the answer's basis, stored only when recorded. A row without the key is historical evidence and is
     * read back with no basis ("not recorded"); the key is never added to old rows.
     *
     * @return array<string, mixed>
     */
    private static function encodeBasis(AuthorityBasis $b): array
    {
        return [
            'outcome' => $b->outcome()->value,
            'reason' => $b->reason(),
            'evaluatedFor' => $b->evaluatedFor()->ordinal(),
            'competenceKind' => $b->competenceKind()?->value,
            'decisionRef' => $b->decisionRef(),
            'evidence' => $b->evidence(),
        ];
    }

    private static function decodeBasis(mixed $b): AuthorityBasis
    {
        $b = self::object($b, 'authority basis');
        $outcome = AuthorityOutcome::tryFrom(self::string($b, 'outcome'))
            ?? throw new \UnexpectedValueException('Stored authority basis has an unknown outcome.');
        $kind = $b['competenceKind'] ?? null;
        if ($kind !== null && (!is_string($kind) || CompetenceKind::tryFrom($kind) === null)) {
            throw new \UnexpectedValueException('Stored authority basis has an unknown competence kind.');
        }
        if (!is_int($b['evaluatedFor'] ?? null)) {
            throw new \UnexpectedValueException("Stored authority basis has no integer 'evaluatedFor'.");
        }
        $evidence = $b['evidence'] ?? null;
        if (!is_array($evidence) || !array_is_list($evidence)) {
            throw new \UnexpectedValueException("Stored authority basis has no list 'evidence'.");
        }
        try {
            return AuthorityBasis::of($outcome, self::nullableString($b, 'reason'), TimePoint::of($b['evaluatedFor']),
                $kind === null ? null : CompetenceKind::from($kind), self::nullableString($b, 'decisionRef'), $evidence);
        } catch (\InvalidArgumentException $e) {
            throw new \UnexpectedValueException('Stored authority basis is invalid: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * COR-4 review condition C-1 (PBDIGIT-329, F-1): every field is checked for presence and type before use, so damaged
     * stored provenance fails with \UnexpectedValueException (malformed JSON: \JsonException), never with a PHP warning,
     * ErrorException or TypeError. The adapter turns both into StoredMemberLogInvalid.
     */
    public static function decode(string $json): Provenance
    {
        $d = self::object(json_decode($json, true, 512, JSON_THROW_ON_ERROR), 'provenance');
        $kind = ProvenanceKind::tryFrom(self::string($d, 'kind'))
            ?? throw new \UnexpectedValueException('Stored provenance has an unknown kind.');

        return match ($kind) {
            ProvenanceKind::Decision => Provenance::decision(
                self::string($d, 'actorOrSource'),
                self::evidence($d['authorityEvidence'] ?? null),
                self::nullableString($d, 'declarationRef'),
            ),
            ProvenanceKind::Rule => Provenance::rule(self::string($d, 'ruleRef'), self::string($d, 'ruleVersion')),
            ProvenanceKind::Attested => Provenance::attested(self::string($d, 'actorOrSource'), self::string($d, 'importer'), self::nullableString($d, 'note')),
        };
    }

    private static function evidence(mixed $e): ?AuthorityEvidence
    {
        if ($e === null) {
            return null;
        }
        $e = self::object($e, 'authority evidence');
        foreach (['result', 'competenceVerified'] as $flag) {
            if (!is_bool($e[$flag] ?? null)) {
                throw new \UnexpectedValueException("Stored authority evidence has no boolean '{$flag}'.");
            }
        }
        if (!is_int($e['checkedAt'] ?? null)) {
            throw new \UnexpectedValueException("Stored authority evidence has no integer 'checkedAt'.");
        }

        try {
            return AuthorityEvidence::of(
                self::string($e, 'actor'),
                self::string($e, 'capacity'),
                self::string($e, 'ruleRef'),
                self::string($e, 'policyVersion'),
                $e['result'],
                Instant::of($e['checkedAt']),
                $e['competenceVerified'],
                array_key_exists('basis', $e) ? self::decodeBasis($e['basis']) : null,
            );
        } catch (\InvalidArgumentException $x) {
            // e.g. a stored result that disagrees with its basis outcome
            throw new \UnexpectedValueException('Stored authority evidence is invalid: ' . $x->getMessage(), 0, $x);
        }
    }

    /** @return array<string, mixed> */
    private static function object(mixed $value, string $what): array
    {
        if (!is_array($value) || ($value !== [] && array_is_list($value))) {
            throw new \UnexpectedValueException("Stored {$what} is not a JSON object.");
        }

        return $value;
    }

    /** @param array<string, mixed> $d */
    private static function string(array $d, string $key): string
    {
        return is_string($d[$key] ?? null) ? $d[$key] : throw new \UnexpectedValueException("Stored provenance has no string '{$key}'.");
    }

    /** @param array<string, mixed> $d */
    private static function nullableString(array $d, string $key): ?string
    {
        $value = $d[$key] ?? null;

        return $value === null || is_string($value) ? $value : throw new \UnexpectedValueException("Stored provenance field '{$key}' is not a string.");
    }
}
