<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * Reference Geography's PUBLISHED read contract (G-5a; v3.11 §4, §6, §10, §12). The only thing another context may
 * depend on (published language, Hexagonal DDD baseline §4). Strings, ints and the DTOs of this namespace only.
 *
 * - Every answer is "as officially known at" a Knowledge: a publication position, or a time τ mapped to one.
 * - Only published facts are visible; nothing beyond the boundary is ever answered (KnowledgeNotAvailable).
 * - Places are referred to by their opaque unit id. Dates are ISO valid dates (YYYY-MM-DD). Lists are sorted by id.
 */
interface GeographyReadModel
{
    /** @throws KnowledgeNotAvailable beyond the committed position */
    public function knowledgeAt(int $position): Knowledge;

    /** Closes through τ, then maps it to the highest position recorded at or before τ. @throws KnowledgeNotAvailable */
    public function knowledgeAtTime(string $tau): Knowledge;

    /** @return list<string> the places believed to exist at the valid date */
    public function placesAt(string $validDate, Knowledge $knowledge): array;

    /** The place's profile at the valid date, or null if nothing about it is published at that knowledge. */
    public function place(string $unitId, string $validDate, Knowledge $knowledge): ?Place;

    /** @return list<string> the places whose parent in the scheme is believed, at the valid date, to be this one */
    public function childrenOf(string $unitId, string $scheme, string $validDate, Knowledge $knowledge): array;

    /** Which identity this one resolves to (a merge, v3.11 §12); itself if never merged or reversed. */
    public function resolve(string $unitId, Knowledge $knowledge): Resolution;
}
