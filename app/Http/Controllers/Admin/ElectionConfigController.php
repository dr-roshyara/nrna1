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
     * Display a listing of elections (FIXED)
     */
    public function index()
    {
        try {
            // ✅ SIMPLIFIED: Get elections without problematic relationships first
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
                ];
            });

            $canCreateNew = $this->canCreateNewElection();

            // ✅ DEBUG: Log what we're sending
            Log::info('Elections index debug', [
                'elections_count' => $elections->count(),
                'elections_data' => $elections->toArray(),
                'can_create_new' => $canCreateNew
            ]);

            return Inertia::render('Admin/Elections/Index', [
                'elections' => $elections,
                'canCreateNew' => $canCreateNew,
            ]);

        } catch (\Exception $e) {
            Log::error('Election index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // ✅ BETTER ERROR HANDLING: Still try to get basic elections
            try {
                $simpleElections = Election::select('id', 'name', 'created_at')->latest()->get();
                
                return Inertia::render('Admin/Elections/Index', [
                    'elections' => $simpleElections,
                    'canCreateNew' => true,
                    'error' => 'Some election data could not be loaded: ' . $e->getMessage(),
                ]);
            } catch (\Exception $fallbackError) {
                return Inertia::render('Admin/Elections/Index', [
                    'elections' => [],
                    'canCreateNew' => true,
                    'error' => 'Failed to load elections: ' . $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Show the form for creating a new election
     */
    public function create()
    {
        // Check if user can create elections
        if (!$this->canCreateNewElection()) {
            return redirect()->route('admin.elections.index')
                ->with('error', 'Cannot create new election while another is active.');
        }

        // Generate default timeline (starting from next week)
        $defaultTimeline = $this->getDefaultTimeline();

        // Available timezones
        $timezones = $this->getTimezones();

        return Inertia::render('Admin/Elections/Create', [
            'timezones' => $timezones,
            'defaultTimeline' => $defaultTimeline,
        ]);
    }

    /**
     * Store a newly created election (FIXED: Proper Inertia response)
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:elections,name',
            'description' => 'required|string|max:1000',
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
        ], [
            'name.required' => 'Election name is required.',
            'name.unique' => 'An election with this name already exists.',
            'registration_start.after' => 'Registration start must be in the future.',
            'registration_end.after' => 'Registration end must be after registration start.',
            'candidate_nomination_start.after_or_equal' => 'Nomination start must be during or after registration period.',
            'candidate_nomination_end.after' => 'Nomination end must be after nomination start.',
            'candidate_nomination_end.before_or_equal' => 'Nomination must end before voting starts.',
            'voting_start_time.after' => 'Voting must start after nomination period ends.',
            'voting_end_time.after' => 'Voting end must be after voting start.',
            'authorization_deadline.after' => 'Authorization deadline must be after voting ends.',
            'result_publication_date.after_or_equal' => 'Result publication must be after authorization deadline.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if another election is active
        if (!$this->canCreateNewElection()) {
            return back()->with('error', 'Cannot create new election while another is active.');
        }

        try {
            DB::beginTransaction();

            // Create the election
            $election = Election::create([
                'name' => $request->name,
                'description' => $request->description,
                'timezone' => $request->timezone,
                'status' => 'draft', // Start as draft
                
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
                
                // Generate unique session ID for authorization
                'authorization_session_id' => $this->generateAuthorizationSessionId(),
                
                // Metadata
                'created_by' => auth()->id(),
            ]);

            // Update election config if this is the first/active election
            $this->updateElectionConfig($election);

            // Log the creation
            Log::info('New election created', [
                'election_id' => $election->id,
                'election_name' => $election->name,
                'created_by' => auth()->id(),
                'timeline' => [
                    'registration' => $request->registration_start . ' to ' . $request->registration_end,
                    'voting' => $request->voting_start_time . ' to ' . $request->voting_end_time,
                ],
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
     * Update timeline (existing method)
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
     * Transition election phase (existing method)
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

            // Deactivate other elections if making this one active
            if ($newPhase === 'active') {
                Election::where('id', '!=', $election->id)
                    ->where('status', 'active')
                    ->update(['status' => 'completed']);
            }

            $election->update(['status' => $newPhase]);
            
            // Update config
            $this->updateElectionConfig($election);

            DB::commit();

            Log::info('Election phase transitioned', [
                'election_id' => $election->id,
                'new_phase' => $newPhase,
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', "Election status changed to {$newPhase}.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Phase transition failed', [
                'election_id' => $id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Failed to change election phase.');
        }
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Get vote count safely (IMPROVED)
     */
    private function getVoteCount($electionId)
    {
        try {
            // ✅ SAFER: Use DB query instead of model relationships
            return DB::table('votes')
                ->where('election_id', $electionId)
                ->whereNotNull('final_vote_cast_at')
                ->count();
        } catch (\Exception $e) {
            // ✅ FALLBACK: Try simpler count
            try {
                return DB::table('votes')->where('election_id', $electionId)->count();
            } catch (\Exception $e2) {
                return 0;
            }
        }
    }

    /**
     * Get post count safely
     */
    private function getPostCount($electionId)
    {
        try {
            return DB::table('posts')->where('election_id', $electionId)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get candidate count safely
     */
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

    /**
     * Get timeline status (IMPROVED - more defensive)
     */
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

    /**
     * Check if new election can be created
     */
    private function canCreateNewElection(): bool
    {
        try {
            return !Election::whereIn('status', ['active', 'voting'])->exists();
        } catch (\Exception $e) {
            return true; // Allow creation if we can't check
        }
    }

    /**
     * Check if election can be edited
     */
    private function canEditElection(Election $election): bool
    {
        return in_array($election->status ?? 'draft', ['draft', 'upcoming']);
    }

    /**
     * Check if election can be deleted
     */
    private function canDeleteElection(Election $election): bool
    {
        return $this->getVoteCount($election->id) === 0;
    }

    /**
     * Check if election can be activated
     */
    private function canActivateElection(Election $election): bool
    {
        return ($election->status ?? 'draft') === 'draft' && 
               $election->voting_start_time && 
               $election->voting_end_time;
    }

    /**
     * Update election configuration
     */
    private function updateElectionConfig($election)
    {
        try {
            // Update settings to reflect current active election
            if (class_exists('\App\Models\Setting')) {
                Setting::updateOrCreate(
                    ['key' => 'current_election_id'],
                    ['value' => $election->id]
                );

                Setting::updateOrCreate(
                    ['key' => 'election.is_active'],
                    ['value' => $election->status === 'active' ? 'true' : 'false']
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
     * Generate unique authorization session ID
     */
    private function generateAuthorizationSessionId(): string
    {
        return 'auth_' . uniqid() . '_' . now()->format('YmdHis');
    }

    /**
     * Get available timezones
     */
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

    /**
     * Get default timeline template
     */
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