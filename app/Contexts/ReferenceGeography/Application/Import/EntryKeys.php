<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Import;

use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * G-8: deterministic identities for imported data. PublicDigit owns each entry's identity (D-86 (2)); an import names
 * entries by stable keys ("iso:NP", "m49:150", "np:district:…"), and the same key always gives the same
 * ReferenceUnitId, in every dataset and on every run (a version-5 UUID in a fixed PublicDigit Geography namespace).
 * External codes stay IDENTIFIER claims; the key is only how a dataset names its entries.
 */
final class EntryKeys
{
    /** The PublicDigit Reference Geography import namespace (fixed forever; changing it changes every imported identity). */
    private const NAMESPACE = '4a1f7c2e-9b3d-5e6f-8a7b-2c1d0e9f8a6b';

    public static function unitId(string $key): ReferenceUnitId
    {
        if (trim($key) === '') {
            throw new DatasetRefused('An entry key is never empty.');
        }

        return ReferenceUnitId::fromString(self::v5("entry:{$key}"));
    }

    public static function revisionId(string $dataset, string $version): RevisionId
    {
        return RevisionId::fromString(self::v5("revision:{$dataset}@{$version}"));
    }

    public static function claimId(string $dataset, string $version, int $index): ClaimId
    {
        return ClaimId::fromString(self::v5("claim:{$dataset}@{$version}#{$index}"));
    }

    private static function v5(string $name): string
    {
        $hash = sha1(hex2bin(str_replace('-', '', self::NAMESPACE)) . $name);
        $hex = substr($hash, 0, 12) . '5' . substr($hash, 13, 3) . dechex((hexdec($hash[16]) & 0x3) | 0x8) . substr($hash, 17, 15);

        return sprintf('%s-%s-%s-%s-%s', substr($hex, 0, 8), substr($hex, 8, 4), substr($hex, 12, 4), substr($hex, 16, 4), substr($hex, 20, 12));
    }
}
