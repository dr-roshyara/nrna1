<?php

declare(strict_types=1);

namespace Tests\Support\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;

/**
 * TEST ONLY (G-2d-2, D-82 / D-227): an issuer with territorial competence from 1950, and instruments it issued on
 * 1960-01-01, so fixtures whose territorial claims rest on an instrument satisfy D-82 without restating it.
 */
final class CompetentInstrument
{
    public const ISSUER = 'test-authority';
    public const ISSUED_ON = '1960-01-01';
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    public static function evidence(string $id): EvidenceVersion
    {
        return new EvidenceVersion($id, self::FP, self::ISSUER, ValidDate::fromString(self::ISSUED_ON));
    }

    /** @param list<string> $ids @return array<string, EvidenceVersion> */
    public static function byId(array $ids): array
    {
        $out = [];
        foreach ($ids as $id) {
            $out[$id] = self::evidence($id);
        }

        return $out;
    }

    /** The published (COMPETENCE, test-authority, territorial) claim, with a fixed id. */
    public static function competenceClaim(): Claim
    {
        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-0000000c0de1'), SlotKey::competence(self::ISSUER, 'territorial'), ClaimValue::of('stated'), new ValidInterval(ValidDate::fromString('1950-01-01')), new Basis(BasisKind::Other, 'gazette'));
    }
}
