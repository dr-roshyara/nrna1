<?php

declare(strict_types=1);

namespace App\Http\Controllers\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\Published\Place;

/**
 * PBDIGIT-355 (frontend M1): page data for the read-only Geography explorer, composed ONLY from Reference Geography's
 * published contract (GeographyReadModel). Presentation only: it decides no Geography fact.
 * - every answer is read at ONE Knowledge (the pinned position) and one valid date;
 * - the top level = the places with no published parent in any scheme; children = the places naming this one as a
 *   parent in a scheme (both from placesAt + place: the contract has no "roots" query);
 * - a label is the CLDR display name in the user's language ("display"), the platform fallback ("display_fallback",
 *   never presented as a translation), else an attributed source name ("source"), else nothing ("none").
 */
final class ExplorerPageData
{
    /** @var array<string, Place>|null every place existing at the valid date, by unit id */
    private ?array $index = null;

    public function __construct(
        private readonly GeographyReadModel $geography,
        private readonly Knowledge $knowledge,
        private readonly string $validDate,
        private readonly string $language,
    ) {
    }

    /** @return list<array<string, mixed>> */
    public function topLevel(): array
    {
        $top = [];
        foreach ($this->index() as $place) {
            if ($place->parents === []) {
                $top[] = $this->summary($place);
            }
        }

        return self::sorted($top);
    }

    /** @return array<string, mixed>|null */
    public function place(string $unitId): ?array
    {
        $place = $this->geography->place($unitId, $this->validDate, $this->knowledge);
        if ($place === null) {
            return null;
        }
        $parents = [];
        foreach ($place->parents as $scheme => $parentId) {
            $parent = $this->geography->place($parentId, $this->validDate, $this->knowledge);
            $parents[] = ['scheme' => $scheme] + ($parent === null ? $this->bare($parentId) : $this->summary($parent));
        }
        $children = [];
        foreach ($this->index() as $candidate) {
            foreach ($candidate->parents as $scheme => $parentId) {
                if ($parentId === $unitId) {
                    $children[] = ['scheme' => $scheme] + $this->summary($candidate);
                }
            }
        }
        $display = $this->geography->displayName($unitId, $this->language, $this->validDate, $this->knowledge);

        return [
            'unitId' => $place->unitId,
            'validAt' => $place->validAt,
            'exists' => $place->exists,
            'label' => $this->label($place),
            'displayName' => $display === null ? null : [
                'value' => $display->value,
                'origin' => $display->origin,
                'resolvedLocale' => $display->resolvedLocale,
                'cldrRelease' => $display->cldrRelease,
                'fallbackLocale' => $display->fallbackLocale,
            ],
            'sourceNames' => $place->names,
            'classifications' => $place->classifications,
            'identifiers' => $place->identifiers,
            'disputedKinds' => $place->disputedKinds,
            'mergedInto' => $place->mergedInto === null ? null : ['unitId' => $place->mergedInto, 'url' => $this->url($place->mergedInto)],
            'parents' => $parents,
            'children' => self::sorted($children),
        ];
    }

    /** @return array<string, Place> */
    private function index(): array
    {
        if ($this->index === null) {
            $this->index = [];
            foreach ($this->geography->placesAt($this->validDate, $this->knowledge) as $unitId) {
                $place = $this->geography->place($unitId, $this->validDate, $this->knowledge);
                if ($place !== null) {
                    $this->index[$unitId] = $place;
                }
            }
        }

        return $this->index;
    }

    /** @return array<string, mixed> */
    private function summary(Place $place): array
    {
        return [
            'unitId' => $place->unitId,
            'label' => $this->label($place),
            'classifications' => $place->classifications,
            'url' => $this->url($place->unitId),
        ];
    }

    /** @return array<string, mixed> a parent named by a published claim but without a profile at this date */
    private function bare(string $unitId): array
    {
        return ['unitId' => $unitId, 'label' => ['value' => null, 'kind' => 'none', 'language' => null], 'classifications' => [], 'url' => $this->url($unitId)];
    }

    /** @return array{value: ?string, kind: string, language: ?string} */
    private function label(Place $place): array
    {
        $display = $this->geography->displayName($place->unitId, $this->language, $this->validDate, $this->knowledge);
        if ($display !== null) {
            return $display->origin === 'CLDR'
                ? ['value' => $display->value, 'kind' => 'display', 'language' => $display->resolvedLocale]
                : ['value' => $display->value, 'kind' => 'display_fallback', 'language' => $display->fallbackLocale];
        }
        $chosen = null;
        foreach ($place->names as $name) {
            if ($name['language'] === $this->language) {
                $chosen = $name;
                break;
            }
            $chosen ??= $name;
        }

        return $chosen === null
            ? ['value' => null, 'kind' => 'none', 'language' => null]
            : ['value' => $chosen['value'], 'kind' => 'source', 'language' => $chosen['language']];
    }

    private function url(string $unitId): string
    {
        return route('geography.place', ['position' => $this->knowledge->position, 'unitId' => $unitId, 'date' => $this->validDate], false);
    }

    /**
     * @param list<array<string, mixed>> $items
     * @return list<array<string, mixed>>
     */
    private static function sorted(array $items): array
    {
        usort($items, static fn (array $a, array $b): int => [(string) $a['label']['value'], $a['unitId']] <=> [(string) $b['label']['value'], $b['unitId']]);

        return $items;
    }
}
