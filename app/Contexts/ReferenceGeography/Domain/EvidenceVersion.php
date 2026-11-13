<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * One immutable evidence version (v3.11 §5.3): an identifier and its content fingerprint (SHA-256). A corrected scan
 * or fuller copy is a NEW version with a new identifier, so no evidence can change under an unchanged reference.
 */
final readonly class EvidenceVersion
{
    public function __construct(public string $id, public string $fingerprint)
    {
        if (trim($id) === '') {
            throw new \InvalidArgumentException('An evidence version needs its identifier.');
        }
        if (preg_match('/^[0-9a-f]{64}$/', $fingerprint) !== 1) {
            throw new \InvalidArgumentException('An evidence fingerprint is a SHA-256 hex digest.');
        }
    }
}
