
// Updated Controllers to use ElectionUser
// app/Http/Controllers/VoterlistController.php
<?php

namespace App\Http\Controllers;

use App\Domain\Election\Models\ElectionUser;
use App\Domain\Election\Services\ElectionUserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoterlistController extends Controller
{
    protected ElectionUserService $electionUserService;
    
    public function __construct(ElectionUserService $electionUserService)
    {
        $this->electionUserService = $electionUserService;
    }
    
    public function index(Request $request)
    {
        $voters = ElectionUser::where('is_voter', true)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nrna_id', 'like', "%{$search}%");
            })
            ->paginate(50);
        
        return Inertia::render('Voterlist/Index', [
            'voters' => $voters,
            'statistics' => $this->electionUserService->getElectionUserStatistics()
        ]);
    }
    
    public function approveVoter($id)
    {
        $electionUser = ElectionUser::findOrFail($id);
        
        $electionUser->approveAsVoter(
            auth()->user()->name,
            request()->ip()
        );
        
        return redirect()->back()->with('success', 'Voter approved successfully');
    }
    
    public function rejectVoter($id, Request $request)
    {
        $electionUser = ElectionUser::findOrFail($id);
        
        $electionUser->suspend(
            auth()->user()->name,
            $request->input('reason', 'No reason provided')
        );
        
        return redirect()->back()->with('success', 'Voter suspended successfully');
    }
}
