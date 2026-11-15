<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * I-TDD-02 §2, §5: a claim code, the organisation-issued one-time proof of possession for one R. Shown to the issuing
 * officer once; Identity stores only its hash (INV-ID-7). 20 characters of Crockford base32 (about 100 bits; no I, L,
 * O, U), shown as four groups of five. Case, spaces and hyphens are ignored when a person types it.
 */
final readonly class ClaimCode
{
    private const ALPHABET = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    private function __construct(private string $normalised)
    {
        if (preg_match('/^[0-9A-HJKMNP-TV-Z]{20}$/', $normalised) !== 1) {
            throw new \InvalidArgumentException('A claim code is 20 characters of Crockford base32.');
        }
    }

    public static function fromString(string $typed): self
    {
        return new self(strtoupper(str_replace([' ', '-'], '', $typed)));
    }

    /** @param string $bytes at least 20 random bytes, supplied by the caller (the domain draws no randomness) */
    public static function fromRandomBytes(string $bytes): self
    {
        if (strlen($bytes) < 20) {
            throw new \InvalidArgumentException('A claim code needs at least 20 random bytes.');
        }
        $code = '';
        for ($i = 0; $i < 20; $i++) {
            $code .= self::ALPHABET[ord($bytes[$i]) % 32];
        }

        return new self($code);
    }

    /** The code as shown once to the officer: four groups of five. */
    public function value(): string
    {
        return implode('-', str_split($this->normalised, 5));
    }

    /** SHA-256 of the normalised code, lower-case hex: the only form Identity stores. */
    public function hash(): string
    {
        return hash('sha256', $this->normalised);
    }
}
