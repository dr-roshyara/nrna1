<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

/**
 * What a register(ns) currently contains — the CRITERIA, supplied from outside.
 *
 * AP-7 / DR-4: "criteria are used here, owned elsewhere" and are READ-ONLY.
 *              This object is immutable and has no mutator, by construction.
 * AP-4:        collisions are prevented by REGISTER DISCIPLINE, not by a central
 *              authority — so this is an OBSERVATION of an existing register,
 *              never a registry of our own.
 *
 * `governed` vs `ungoverned` is the distinction M4 names as the *bare, unregistered
 * series* liability: a series with no governed register cannot be evaluated, and
 * absence of evidence must never become PASS.
 */
final readonly class SeriesContents
{
    /**
     * @param list<Identifier> $minted identifiers entered in the register
     * @param list<Identifier> $cited  identifiers cited in the corpus but not minted
     */
    private function __construct(
        private IdentifierSeries $series,
        private bool $governed,
        private array $minted,
        private array $cited,
    ) {
    }

    /** @param list<Identifier> $minted @param list<Identifier> $cited */
    public static function governed(IdentifierSeries $series, array $minted, array $cited = []): self
    {
        return new self($series, true, array_values($minted), array_values($cited));
    }

    /** A series with no governed register — evaluation is not possible. */
    public static function ungoverned(IdentifierSeries $series): self
    {
        return new self($series, false, [], []);
    }

    public function series(): IdentifierSeries
    {
        return $this->series;
    }

    public function isGoverned(): bool
    {
        return $this->governed;
    }

    public function containsMinted(Identifier $identifier): bool
    {
        foreach ($this->minted as $known) {
            if ($known->equals($identifier)) {
                return true;
            }
        }

        return false;
    }

    public function containsCitedButUnminted(Identifier $identifier): bool
    {
        if ($this->containsMinted($identifier)) {
            return false;
        }

        foreach ($this->cited as $citation) {
            if ($citation->equals($identifier)) {
                return true;
            }
        }

        return false;
    }

    public function mintedCount(): int
    {
        return count($this->minted);
    }

    public function citedCount(): int
    {
        return count($this->cited);
    }
}
