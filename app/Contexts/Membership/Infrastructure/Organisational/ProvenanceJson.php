<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
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
            ],
        ], JSON_THROW_ON_ERROR);
    }

    public static function decode(string $json): Provenance
    {
        $d = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return match (ProvenanceKind::from($d['kind'])) {
            ProvenanceKind::Decision => Provenance::decision(
                $d['actorOrSource'],
                $d['authorityEvidence'] === null ? null : AuthorityEvidence::of(
                    $d['authorityEvidence']['actor'],
                    $d['authorityEvidence']['capacity'],
                    $d['authorityEvidence']['ruleRef'],
                    $d['authorityEvidence']['policyVersion'],
                    $d['authorityEvidence']['result'],
                    Instant::of($d['authorityEvidence']['checkedAt']),
                    $d['authorityEvidence']['competenceVerified'],
                ),
                $d['declarationRef'],
            ),
            ProvenanceKind::Rule => Provenance::rule($d['ruleRef'], $d['ruleVersion']),
            ProvenanceKind::Attested => Provenance::attested($d['actorOrSource'], $d['importer'], $d['note']),
        };
    }
}
