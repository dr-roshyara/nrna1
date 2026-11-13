<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\GeographyReadSource;
use App\Contexts\ReferenceGeography\Application\Ports\TimeBoundary;
use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use App\Contexts\ReferenceGeography\Application\Published\LookupMatch;
use App\Contexts\ReferenceGeography\Application\Published\Place;
use App\Contexts\ReferenceGeography\Application\Published\Resolution;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\LookupCandidate;
use App\Contexts\ReferenceGeography\Domain\PlaceBelief;
use App\Contexts\ReferenceGeography\Domain\PlaceMatcher;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;

/**
 * The published read model (G-5a; design 2026-11-13-reference-geography-g5-read-models-design.md §2).
 * Orchestration only: the boundary (v3.11 §6) here, belief in the domain (PlaceBelief), data behind one port.
 */
final class PublishedGeography implements GeographyReadModel
{
    public function __construct(private readonly GeographyReadSource $source, private readonly TimeBoundary $boundary)
    {
    }

    public function knowledgeAt(int $position): Knowledge
    {
        return new Knowledge($this->checked($position));
    }

    public function knowledgeAtTime(string $tau): Knowledge
    {
        try {
            $this->boundary->closeThrough($tau);
        } catch (BoundaryNotClosed $e) {
            throw new KnowledgeNotAvailable($e->getMessage(), 0, $e);
        }

        return new Knowledge($this->source->positionAtTime($tau), $tau);
    }

    public function placesAt(string $validDate, Knowledge $knowledge): array
    {
        $p = $this->checked($knowledge->position);
        $t = ValidDate::fromString($validDate);
        $places = [];
        foreach ($this->source->unitsWithExistence($p) as $unit) {
            if ($this->source->slotHistory(SlotKey::existence($unit), $p)->resolve($t, $p) !== null) {
                $places[] = $unit->value();
            }
        }
        sort($places);

        return $places;
    }

    public function place(string $unitId, string $validDate, Knowledge $knowledge): ?Place
    {
        $p = $this->checked($knowledge->position);
        $unit = ReferenceUnitId::fromString($unitId);
        $slots = $this->source->subjectSlots($unit, $p);
        if ($slots === []) {
            return null;
        }
        $facts = PlaceBelief::of($unit, $slots, $this->source->disputesForSubject($unit, $p), ValidDate::fromString($validDate), $p);

        return new Place($unitId, $validDate, $p, $facts->exists, $facts->parents, $facts->classifications, $facts->identifiers,
            $facts->names, $facts->disputedKinds, $facts->mergedInto?->value());
    }

    public function childrenOf(string $unitId, string $scheme, string $validDate, Knowledge $knowledge): array
    {
        $p = $this->checked($knowledge->position);
        $t = ValidDate::fromString($validDate);
        $children = [];
        foreach ($this->source->containedIn(ReferenceUnitId::fromString($unitId), $scheme, $p) as $child) {
            if ($this->source->slotHistory(SlotKey::containment($child, $scheme), $p)->resolve($t, $p)?->value() === $unitId) {
                $children[] = $child->value();
            }
        }
        sort($children);

        return $children;
    }

    public function resolve(string $unitId, Knowledge $knowledge): Resolution
    {
        $p = $this->checked($knowledge->position);
        $unit = ReferenceUnitId::fromString($unitId);
        $merge = PlaceBelief::resolve($unit, $this->source->slotHistory(SlotKey::reconciliation($unit), $p), $p);
        if ($merge === null) {
            return new Resolution($unitId, $unitId, false);
        }
        $publication = $this->source->publication($merge->knownAtPosition);

        return new Resolution($unitId, $merge->canonical->value(), true, $merge->knownAtPosition, $publication?->recordedAt, $publication?->revisionId);
    }

    public function lookup(string $text, string $validDate, Knowledge $knowledge, ?string $withinUnitId = null, ?string $scheme = null, int $limit = 10): array
    {
        $p = $this->checked($knowledge->position);
        if (($withinUnitId === null) !== ($scheme === null)) {
            throw new \InvalidArgumentException('A lookup scope needs both a place and a scheme, or neither.');
        }
        if ($limit < 1 || $limit > 50) {
            throw new \InvalidArgumentException("A lookup limit is 1 to 50, not {$limit}.");
        }
        $t = ValidDate::fromString($validDate);
        $eligible = array_flip($this->placesAt($validDate, $knowledge));
        if ($withinUnitId !== null) {
            $eligible = array_intersect_key($eligible, array_flip($this->descendants($withinUnitId, (string) $scheme, $validDate, $knowledge)));
        }
        $candidates = [];
        foreach ([ClaimKind::SourceName, ClaimKind::Identifier] as $kind) {
            foreach ($this->source->slotsOfKind($kind, $p) as $history) {
                $unit = $history->slot()->subject();
                $value = $history->resolve($t, $p);
                if ($unit === null || $value === null || !isset($eligible[$unit->value()])) {
                    continue;
                }
                $slot = $history->slot();
                $candidates[] = $kind === ClaimKind::Identifier
                    ? new LookupCandidate($unit, LookupCandidate::IDENTIFIER, $value->value(), (string) $slot->component('namespace'))
                    : new LookupCandidate($unit, LookupCandidate::NAME, $value->value(), $slot->component('language') . '/' . $slot->component('name_type'));
            }
        }
        $matches = [];
        foreach (array_slice(PlaceMatcher::best($text, $candidates), 0, $limit) as $m) {
            $merged = PlaceBelief::resolve($m->unit, $this->source->slotHistory(SlotKey::reconciliation($m->unit), $p), $p);
            $matches[] = new LookupMatch($m->unit->value(), $m->quality->value, $m->score, $m->candidate->kind, $m->candidate->value,
                $m->candidate->detail, $m->partial, $merged?->canonical->value());
        }

        return $matches;
    }

    /** @return list<string> every place below the given one in the scheme at t (transitive), as known at the knowledge */
    private function descendants(string $unitId, string $scheme, string $validDate, Knowledge $knowledge): array
    {
        $seen = [];
        $queue = [$unitId];
        while ($queue !== []) {
            foreach ($this->childrenOf(array_shift($queue), $scheme, $validDate, $knowledge) as $child) {
                if (!isset($seen[$child]) && $child !== $unitId) {
                    $seen[$child] = true;
                    $queue[] = $child;
                }
            }
        }

        return array_keys($seen);
    }

    private function checked(int $position): int
    {
        $committed = $this->source->committedPosition();
        if ($position < 0 || $position > $committed) {
            throw new KnowledgeNotAvailable("Position {$position} is beyond what is published (committed: {$committed}).");
        }

        return $position;
    }
}
