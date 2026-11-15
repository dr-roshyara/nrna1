<?php

declare(strict_types=1);

namespace App\Http\Controllers\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * PBDIGIT-355 (frontend M1): read-only Reference Geography explorer. Depends ONLY on the published contract
 * (GeographyReadModel), never on Geography's ports or infrastructure (no-bypass guard, rule 6). No write route.
 *
 * Close once, then pin: the entry asks "as officially known now" (knowledgeAtTime closes the boundary: a short write the
 * contract allows the read side), then redirects to a position; every pinned page reads with knowledgeAt(position)
 * only, so browsing never writes and every page of one visit shows the same publication.
 */
final class GeographyExplorerController
{
    /** The frontend locale codes (SetLocale) as language codes: `np` is Nepal's country code, Nepali is `ne`. */
    private const LANGUAGES = ['en' => 'en', 'de' => 'de', 'np' => 'ne'];

    public function __construct(private readonly GeographyReadModel $geography)
    {
    }

    public function entry(Request $request): RedirectResponse
    {
        $date = $this->validDate($request);
        try {
            $knowledge = $this->geography->knowledgeAtTime(now()->format('Y-m-d H:i:s.uP'));
        } catch (KnowledgeNotAvailable) {
            abort(503, 'Reference Geography is busy. Please try again.');
        }

        return redirect(route('geography.explorer', ['position' => $knowledge->position, 'date' => $date], false));
    }

    public function explorer(Request $request, int $position): Response
    {
        $date = $this->validDate($request);
        $data = $this->pageData($this->pinned($position), $date);
        $places = $data->topLevel();

        return Inertia::render('ReferenceGeography/Explorer', $this->common($position, $date) + [
            'places' => $places,
            'empty' => $places === [],
        ]);
    }

    public function place(Request $request, int $position, string $unitId): Response
    {
        $date = $this->validDate($request);
        $place = $this->pageData($this->pinned($position), $date)->place($unitId);
        if ($place === null) {
            abort(404);
        }

        return Inertia::render('ReferenceGeography/Place', $this->common($position, $date) + ['place' => $place]);
    }

    private function pinned(int $position): Knowledge
    {
        try {
            return $this->geography->knowledgeAt($position);
        } catch (KnowledgeNotAvailable) {
            abort(404);
        }
    }

    private function pageData(Knowledge $knowledge, string $date): ExplorerPageData
    {
        return new ExplorerPageData($this->geography, $knowledge, $date, $this->language());
    }

    /** @return array<string, mixed> */
    private function common(int $position, string $date): array
    {
        return [
            'knownAt' => ['position' => $position, 'latestUrl' => route('geography.entry', ['date' => $date], false)],
            'validDate' => $date,
            'language' => $this->language(),
        ];
    }

    private function validDate(Request $request): string
    {
        $request->validate(['date' => ['nullable', 'date_format:Y-m-d']]);

        return (string) ($request->query('date') ?? now()->toDateString());
    }

    private function language(): string
    {
        return self::LANGUAGES[app()->getLocale()] ?? 'en';
    }
}
