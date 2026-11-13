<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The succession event kinds (closure document G-5 and SuccessorSet; v3.11 §3 "a partial split, then an ending";
 * D-228). No other kinds.
 */
enum SuccessionKind: string
{
    case Division = 'division';
    case Merger = 'merger';
    case Absorption = 'absorption';
    case PartialSplit = 'partial-split';

    /** Division, merger and absorption end the predecessor; after a partial split it continues. */
    public function endsPredecessor(): bool
    {
        return $this !== self::PartialSplit;
    }
}
