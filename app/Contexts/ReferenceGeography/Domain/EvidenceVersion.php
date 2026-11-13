<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * One immutable evidence version (v3.11 §5.3): an identifier and its content fingerprint (SHA-256). A corrected scan
 * or fuller copy is a NEW version with a new identifier, so no evidence can change under an unchanged reference.
 *
 * D-227: an authority-dependent INSTRUMENT also records its issuer and issue/adoption date, and the mandates and
 * precedence rules it cites, all assessed at that date. Evidence that is no instrument (e.g. a duplicate-search
 * assessment) carries none of these.
 */
final readonly class EvidenceVersion
{
    /**
     * @param list<string> $mandates registry mandate ids the instrument cites
     * @param list<string> $precedenceRules registry precedence-rule ids the instrument relies on
     */
    public function __construct(
        public string $id,
        public string $fingerprint,
        public ?string $issuer = null,
        public ?ValidDate $issuedOn = null,
        public array $mandates = [],
        public array $precedenceRules = [],
    ) {
        if (trim($id) === '') {
            throw new \InvalidArgumentException('An evidence version needs its identifier.');
        }
        if (preg_match('/^[0-9a-f]{64}$/', $fingerprint) !== 1) {
            throw new \InvalidArgumentException('An evidence fingerprint is a SHA-256 hex digest.');
        }
        if (($issuer === null) !== ($issuedOn === null) || ($issuer !== null && trim($issuer) === '')) {
            throw new \InvalidArgumentException('An instrument records its issuer and its issue/adoption date together (D-227).');
        }
        if (($mandates !== [] || $precedenceRules !== []) && $issuedOn === null) {
            throw new \InvalidArgumentException('Cited mandates and rules are assessed at the issue date, so it is required (D-227).');
        }
    }
}
