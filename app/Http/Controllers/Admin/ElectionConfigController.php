<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Carbon\Carbon;

class ElectionConfigController extends Controller
{
    /**
     * Display a listing of elections
     */
    public function index()
    {
        try {
            $elections = Election::latest()->get()->map(function ($election) {
                return [
                    'id' => $election->id,
                    'name' => $election->name ?? 'Unnamed Election',
                    'description' => $election->description ?? 'No description provided',
                    'status' => $election->status ?? 'draft',
                    'timeline_status' => $this->getTimelineStatus($election),
                    'registration_start' => $election->registration_start,
                    'registration_end' => $election->registration_end,
                    'candidate_nomination_start' => $election->candidate_nomination_start,
                    'candidate_nomination_end' => $election->candidate_nomination_end,
                    'voting_start_time' => $election->voting_start_time,
                    'voting_end_time' => $election->voting_end_time,
                    'authorization_deadline' => $election->authorization_deadline,
                    'result_publication_date' => $election->result_publication_date,
                    'vote_count' => $this->getVoteCount($election->id),
                    'can_edit' => $this->canEditElection($election),
                    'created_at' => $election->created_at,
                    'voter_count' => $this->getElectionVoterCount($election->id),
                    'constituency' => $election->constituency ?? 'General',
                ];
            });

            // ✅ FIXED: Allow multiple active elections
            $canCreateNew = true; // Always allow creation

            return Inertia::render('Admin/Elections/Index', [
                'elections' => $elections,
                'canCreateNew' => $canCreateNew,
                'activeElections' => $elections->where('status', 'active')->count(),
                'votingElections' => $elections->where('status', 'voting')->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Election index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Admin/Elections/Index', [
                'elections' => [],
                'canCreateNew' => true,
                'error' => 'Failed to load elections: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new election
     */
    public function create()
    {
        // ✅ FIXED: Always allow election creation
        $defaultTimeline = $this->getDefaultTimeline();
        $timezones = $this->getTimezones();
        $constituencies = $this->getConstituencies();

        // Debug: Log what we're sending
        Log::info('Election create form data', [
            'constituencies' => $constituencies,
            'timezones' => $timezones,
        ]);

        return Inertia::render('Admin/Elections/Create', [
            'timezones' => $timezones,
            'defaultTimeline' => $defaultTimeline,
            'constituencies' => $constituencies,
        ]);
    }

    /**
     * Store a newly created election (FIXED: Support multiple elections)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:elections,name',
            'description' => 'required|string|max:1000',
            'constituency' => 'required|string|max:100',
            'timezone' => 'required|string|in:UTC,Europe/Berlin,Europe/London,America/New_York,Asia/Kathmandu',
            'registration_start' => 'required|date|after:now',
            'registration_end' => 'required|date|after:registration_start',
            'candidate_nomination_start' => 'required|date|after_or_equal:registration_start',
            'candidate_nomination_end' => 'required|date|after:candidate_nomination_start|before_or_equal:voting_start_time',
            'voting_start_time' => 'required|date|after:candidate_nomination_end',
            'voting_end_time' => 'required|date|after:voting_start_time',
            'authorization_deadline' => 'required|date|after:voting_end_time',
            'result_publication_date' => 'required|date|after_or_equal:authorization_deadline',
            'auto_phase_transition' => 'boolean',
            'notification_enabled' => 'boolean',
            'public_registration' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ FIXED: Create election with constituency support
            $election = Election::create([
                'name' => $request->name,
                'description' => $request->description,
                'constituency' => $request->constituency,
                'timezone' => $request->timezone,
                'status' => 'draft',
                
                // Timeline dates
                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,
                'candidate_nomination_start' => $request->candidate_nomination_start,
                'candidate_nomination_end' => $request->candidate_nomination_end,
                'voting_start_time' => $request->voting_start_time,
                'voting_end_time' => $request->voting_end_time,
                'authorization_deadline' => $request->authorization_deadline,
                'result_publication_date' => $request->result_publication_date,
                
                // Settings
                'auto_phase_transition' => $request->boolean('auto_phase_transition', true),
                'notification_enabled' => $request->boolean('notification_enabled', true),
                'public_registration' => $request->boolean('public_registration', true),
                
                // ✅ FIXED: Election-specific authorization session
                'authorization_session_id' => $this->generateElectionSpecificAuthSessionId($request->constituency),
                
                'created_by' => auth()->id(),
            ]);

            // ✅ FIXED: Update config for multiple elections
            $this->updateElectionConfig($election);

            Log::info('New election created (multi-election support)', [
                'election_id' => $election->id,
                'election_name' => $election->name,
                'constituency' => $election->constituency,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.elections.show', $election->id)
                ->with('success', 'Election created successfully! You can now configure positions and candidates.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Election creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to create election. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified election
     */
    public function show($id)
    {
        try {
            $election = Election::findOrFail($id);

            $electionData = [
                'id' => $election->id,
                'name' => $election->name,
                'description' => $election->description,
                'constituency' => $election->constituency ?? 'General',
                'status' => $election->status ?? 'draft',
                'current_phase' => $this->getTimelineStatus($election),
                'timezone' => $election->timezone ?? 'UTC',
                
                // Timeline
                'registration_start' => $election->registration_start,
                'registration_end' => $election->registration_end,
                'candidate_nomination_start' => $election->candidate_nomination_start,
                'candidate_nomination_end' => $election->candidate_nomination_end,
                'voting_start_time' => $election->voting_start_time,
                'voting_end_time' => $election->voting_end_time,
                'authorization_deadline' => $election->authorization_deadline,
                'result_publication_date' => $election->result_publication_date,
                
                // Settings
                'auto_phase_transition' => $election->auto_phase_transition ?? true,
                'notification_enabled' => $election->notification_enabled ?? true,
                'public_registration' => $election->public_registration ?? true,
                
                // Statistics
                'total_votes' => $this->getVoteCount($election->id),
                'total_positions' => $this->getPostCount($election->id),
                'total_candidates' => $this->getCandidateCount($election->id),
                'total_voters' => $this->getElectionVoterCount($election->id),
                
                // Permissions
                'can_edit' => $this->canEditElection($election),
                'can_delete' => $this->canDeleteElection($election),
                'can_activate' => $this->canActivateElection($election),
                
                'created_at' => $election->created_at,
                'updated_at' => $election->updated_at,
            ];

            return Inertia::render('Admin/Elections/Show', [
                'election' => $electionData,
            ]);

        } catch (\Exception $e) {
            Log::error('Election show failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.elections.index')
                ->with('error', 'Election not found.');
        }
    }

    /**
     * Update the specified election
     */
    public function update(Request $request, $id)
    {
        try {
            $election = Election::findOrFail($id);
            
            if (!$this->canEditElection($election)) {
                return back()->with('error', 'This election cannot be modified.');
            }

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', Rule::unique('elections')->ignore($election->id)],
                'description' => 'required|string|max:1000',
                'constituency' => 'required|string|max:100',
                'timezone' => 'required|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $election->update($request->only([
                'name', 'description', 'constituency', 'timezone',
                'auto_phase_transition', 'notification_enabled', 'public_registration'
            ]));
            
            return back()->with('success', 'Election updated successfully.');
            
        } catch (\Exception $e) {
            Log::error('Election update failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Failed to update election.');
        }
    }

    /**
     * Update election timeline
     */
    public function updateTimeline(Request $request, $id)
    {
        try {
            $election = Election::findOrFail($id);
            
            if (!$this->canEditElection($election)) {
                return back()->with('error', 'This election cannot be modified.');
            }

            $validator = Validator::make($request->all(), [
                'voting_start_time' => 'required|date',
                'voting_end_time' => 'required|date|after:voting_start_time',
                'authorization_deadline' => 'required|date|after:voting_end_time',
                'result_publication_date' => 'required|date|after_or_equal:authorization_deadline',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $election->update($request->only([
                'voting_start_time',
                'voting_end_time', 
                'authorization_deadline',
                'result_publication_date'
            ]));

            return back()->with('success', 'Timeline updated successfully.');

        } catch (\Exception $e) {
            Log::error('Timeline update failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Failed to update timeline.');
        }
    }

    /**
     * Transition election phase (FIXED: Multiple elections support)
     */
    public function transitionPhase(Request $request, $id)
    {
        try {
            $election = Election::findOrFail($id);
            
            $newPhase = $request->input('phase');
            
            if (!in_array($newPhase, ['draft', 'active', 'voting', 'completed'])) {
                return back()->with('error', 'Invalid phase transition.');
            }

            DB::beginTransaction();

            // ✅ FIXED: No longer deactivate other elections - allow multiple active
            $election->update(['status' => $newPhase]);
            
            // Update config for this specific election
            $this->updateElectionConfig($election);

            DB::commit();

            Log::info('Election phase transitioned (multi-election)', [
                'election_id' => $election->id,
                'new_phase' => $newPhase,
                'constituency' => $election->constituency,
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', "Election '{$election->name}' status changed to {$newPhase}.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Phase transition failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Failed to change election phase.');
        }
    }

    /**
     * Remove the specified election
     */
    public function destroy($id)
    {
        try {
            $election = Election::findOrFail($id);
            
            if (!$this->canDeleteElection($election)) {
                return back()->with('error', 'This election cannot be deleted. It may have votes or be currently active.');
            }

            DB::beginTransaction();
            
            // Delete related data first (be careful with your relationships)
            $election->votes()->delete();
            
            // Delete the election
            $election->delete();
            
            DB::commit();
            
            Log::info('Election deleted', [
                'election_id' => $election->id,
                'election_name' => $election->name,
                'deleted_by' => auth()->id(),
            ]);
            
            return redirect()->route('admin.elections.index')
                ->with('success', 'Election "' . $election->name . '" deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Election deletion failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Failed to delete election: ' . $e->getMessage());
        }
    }

    // ============================================================
    // MULTI-ELECTION HELPER METHODS
    // ============================================================

    /**
     * Get election-specific voter count
     */
    private function getElectionVoterCount($electionId)
    {
        try {
            // Count voters eligible for this specific election
            return DB::table('users')
                ->join('user_election_eligibility', 'users.id', '=', 'user_election_eligibility.user_id')
                ->where('user_election_eligibility.election_id', $electionId)
                ->where('users.is_voter', true)
                ->where('users.can_vote', true)
                ->count();
        } catch (\Exception $e) {
            // Fallback: count all voters if election-specific table doesn't exist
            return DB::table('users')
                ->where('is_voter', true)
                ->where('can_vote', true)
                ->count();
        }
    }

    /**
     * Get vote count safely (election-specific)
     */
    private function getVoteCount($electionId)
    {
        try {
            return DB::table('votes')
                ->where('election_id', $electionId)
                ->whereNotNull('final_vote_cast_at')
                ->count();
        } catch (\Exception $e) {
            try {
                return DB::table('votes')->where('election_id', $electionId)->count();
            } catch (\Exception $e2) {
                return 0;
            }
        }
    }

    /**
     * Update election configuration (FIXED: Multi-election support)
     */
    private function updateElectionConfig($election)
    {
        try {
            if (class_exists('\App\Models\Setting')) {
                // Store active elections list instead of single election
                $activeElections = Election::whereIn('status', ['active', 'voting'])
                    ->pluck('id')
                    ->toArray();

                Setting::updateOrCreate(
                    ['key' => 'active_election_ids'],
                    ['value' => json_encode($activeElections)]
                );

                // Keep backward compatibility
                Setting::updateOrCreate(
                    ['key' => 'current_election_id'],
                    ['value' => $election->id]
                );

                Setting::updateOrCreate(
                    ['key' => 'election.is_active'],
                    ['value' => 'true'] // Always true if any election exists
                );
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update election config', [
                'election_id' => $election->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate election-specific authorization session ID
     */
    private function generateElectionSpecificAuthSessionId($constituency)
    {
        return 'auth_' . strtolower(str_replace(' ', '_', $constituency)) . '_' . uniqid() . '_' . now()->format('YmdHis');
    }

    /**
     * Get available constituencies
     */
    private function getConstituencies()
    {
        return [
            'europe' => 'NRNA Europe',
            'americas' => 'NRNA Americas', 
            'asia_pacific' => 'NRNA Asia Pacific',
            'middle_east' => 'NRNA Middle East',
            'africa' => 'NRNA Africa',
            'oceania' => 'NRNA Oceania',
            'youth' => 'NRNA Youth Committee',
            'women' => 'NRNA Women Committee',
            'general' => 'General Election',
        ];
    }

    private function getPostCount($electionId)
    {
        try {
            return DB::table('posts')->where('election_id', $electionId)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCandidateCount($electionId)
    {
        try {
            return DB::table('candidacies')
                ->join('posts', 'candidacies.post_id', '=', 'posts.id')
                ->where('posts.election_id', $electionId)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTimelineStatus($election)
    {
        if (!$election->voting_start_time) {
            return 'upcoming';
        }
        
        try {
            $now = Carbon::now();
            
            if ($election->registration_start && $now->isBefore($election->registration_start)) {
                return 'upcoming';
            }
            
            if ($election->registration_start && $election->registration_end && 
                $now->isBetween($election->registration_start, $election->registration_end)) {
                return 'registration';
            }
            
            $votingStart = Carbon::parse($election->voting_start_time);
            $votingEnd = Carbon::parse($election->voting_end_time ?? $election->voting_start_time);
            
            if ($now->isBefore($votingStart)) {
                return 'preparation';
            } elseif ($now->isBetween($votingStart, $votingEnd)) {
                return 'voting';
            } else {
                return 'completed';
            }
        } catch (\Exception $e) {
            return 'upcoming';
        }
    }

    private function canEditElection(Election $election): bool
    {
        return in_array($election->status ?? 'draft', ['draft', 'upcoming']);
    }

    private function canDeleteElection(Election $election): bool
    {
        return $this->getVoteCount($election->id) === 0;
    }

    private function canActivateElection(Election $election): bool
    {
        return ($election->status ?? 'draft') === 'draft' && 
               $election->voting_start_time && 
               $election->voting_end_time;
    }

    private function getTimezones()
    {
        return [
            'UTC' => 'UTC (Coordinated Universal Time)',
            'Europe/Berlin' => 'Europe/Berlin (Central European Time)',
            'Europe/London' => 'Europe/London (Greenwich Mean Time)',
            'America/New_York' => 'America/New_York (Eastern Time)',
            'Asia/Kathmandu' => 'Asia/Kathmandu (Nepal Time)',
        ];
    }

    private function getDefaultTimeline()
    {
        $now = Carbon::now();
        
        return [
            'registration_start' => $now->copy()->addDays(7)->format('Y-m-d\TH:i'),
            'registration_end' => $now->copy()->addDays(21)->format('Y-m-d\TH:i'),
            'candidate_nomination_start' => $now->copy()->addDays(14)->format('Y-m-d\TH:i'),
            'candidate_nomination_end' => $now->copy()->addDays(28)->format('Y-m-d\TH:i'),
            'voting_start_time' => $now->copy()->addDays(35)->format('Y-m-d\TH:i'),
            'voting_end_time' => $now->copy()->addDays(37)->format('Y-m-d\TH:i'),
            'authorization_deadline' => $now->copy()->addDays(39)->format('Y-m-d\TH:i'),
            'result_publication_date' => $now->copy()->addDays(42)->format('Y-m-d\TH:i'),
        ];
    }
}