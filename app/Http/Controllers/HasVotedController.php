<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;

class HasVotedController extends Controller
{
    /**
     * Display a listing of users who have voted.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Query codes where has_voted is true
        $votedUsers = QueryBuilder::for(Code::class)
            ->with('user:id,name,email,region,nrna_id')
            ->where('has_voted', true)
            ->allowedFilters([
                AllowedFilter::partial('user.name'),
                AllowedFilter::partial('user.email'),
                AllowedFilter::partial('user.region'),
                AllowedFilter::partial('user.nrna_id'),
            ])
            ->allowedSorts(['vote_submitted_at', 'voting_started_at', 'created_at'])
            ->defaultSort('-vote_submitted_at') // Most recent first
            ->paginate($request->input('per_page', 15))
            ->withQueryString();

        return Inertia::render('Election/HasVoted', [
            'votedUsers' => $votedUsers,
            'filters' => $request->only(['filter', 'sort']),
            'stats' => [
                'total_voted' => Code::where('has_voted', true)->count(),
                'total_voters' => Code::count(),
            ]
        ]);
    }
}
