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

    /**
     * PBDIGIT-355: the places believed to exist at the valid date (as placesAt) with no parent in any scheme at that date
     * (no containment in force, as Place::$parents). Sorted by id. Bounded in queries, whatever the number of places.
     *
     * @return list<string>
     */
    public function topLevelAt(string $validDate, Knowledge $knowledge): array;

    /**
     * PBDIGIT-355: childrenOf across every scheme, each child with the scheme it is contained in. Sorted by scheme, then id.
     *
     * @return list<array{scheme: string, unitId: string}>
     */
    public function childrenInAnySchemeOf(string $unitId, string $validDate, Knowledge $knowledge): array;

    /** Which identity this one resolves to (a merge, v3.11 §12); itself if never merged or reversed. */
    public function resolve(string $unitId, Knowledge $knowledge): Resolution;

    /**
     * PlaceLookup (G-5b): which places, existing at the valid date, a name, alias or code may mean, with match quality.
     * Optionally only places below $withinUnitId in $scheme (both or neither). No address parsing (D-83).
     *
     * @param int $limit 1 to 50
     * @return list<LookupMatch> one per place, best first
     * @throws \InvalidArgumentException for half a scope or a limit outside 1..50
     */
    public function lookup(string $text, string $validDate, Knowledge $knowledge, ?string $withinUnitId = null, ?string $scheme = null, int $limit = 10): array;

    /**
     * The publication feed (G-5c): every publication after the consumer's last position, up to the knowledge, in order.
     * Keep the last position received and ask again from it: nothing is missed and nothing repeats.
     *
     * CONSUMER OBLIGATIONS (this contract cannot enforce them; the consumer's own tests must):
     * - store the last position processed IN THE SAME TRANSACTION as the work it caused (review signals); otherwise a
     *   crash between the two loses or repeats work;
     * - raise review signals only: never change membership, voter lists, committee assignments or belonging
     *   automatically from an entry (context map).
     *
     * @param int $limit 1 to 500
     * @return list<PublicationEntry>
     * @throws \InvalidArgumentException for a negative position or a limit outside 1..500
     */
    public function publicationsSince(int $afterPosition, Knowledge $knowledge, int $limit = 100): array;

    /**
     * D-90 (G-6c-6): the approval status and history of the revision publication at $position, as known at the
     * knowledge (entries at positions ≤ it only). Null if no revision publication at that position is known then (not
     * yet published, or a notice).
     *
     * @throws KnowledgeNotAvailable beyond the committed position
     */
    public function approvalStatus(int $position, Knowledge $knowledge): ?ApprovalHistory;

    /**
     * D-87 (G-7): the name to show for the place in the user's locale (CLDR form: de, de_AT, en_001; a hyphen form
     * de-AT is accepted), at the valid date, as known at the knowledge. Null if neither the user's locale chain nor the
     * platform default locale's chain has a CLDR name (PublicDigit never invents one).
     *
     * @param string $form standard | short
     * @throws \InvalidArgumentException for a malformed locale or form
     * @throws KnowledgeNotAvailable beyond the committed position
     */
    public function displayName(string $unitId, string $locale, string $validDate, Knowledge $knowledge, string $form = 'standard'): ?DisplayName;
}
