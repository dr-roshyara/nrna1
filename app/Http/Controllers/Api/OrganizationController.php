<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Mail\OrganizationCreatedMail;
use App\Models\Organization;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\DemoVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Create a new organization from the onboarding form.
     *
     * POST /organizations
     */
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $user = auth()->user();

        // Create organization
        $organization = Organization::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'representative' => $request->representative,
            'created_by' => $user->id,
            'slug' => Str::slug($request->name),
        ]);

        // Attach current user as organization admin
        $organization->users()->attach($user->id, [
            'role' => 'admin',
            'assigned_at' => now(),
        ]);

        // Update current user's organisation_id
        $user->update(['organisation_id' => $organization->id]);

        // Handle representative - check if user IS the representative
        $isSelfRepresentative = $request->representative['is_self'] ?? false;

        if (!$isSelfRepresentative) {
            // Someone else is the representative
            $representativeEmail = $request->representative['email'] ?? null;

            if ($representativeEmail) {
                // CRITICAL: Check if representative email is the current user's email
                // If so, skip adding them again (they're already added as admin)
                if (strtolower($representativeEmail) === strtolower($user->email)) {
                    // Current user is the representative - they're already admin, no action needed
                } else {
                    // Create or find the representative user (different person)
                    $representativeUser = User::firstOrCreate(
                        ['email' => $representativeEmail],
                        [
                            'name' => $request->representative['name'],
                            'password' => bcrypt(Str::random(40)),
                            'email_verified_at' => null, // Must verify themselves
                        ]
                    );

                    // Check if user is already attached to organization (avoid duplicates)
                    $isAlreadyMember = $organization->users()
                        ->where('users.id', $representativeUser->id)
                        ->exists();

                    if (!$isAlreadyMember) {
                        // Attach as voter only if not already a member
                        $organization->users()->attach($representativeUser->id, [
                            'role' => 'voter',
                            'assigned_at' => now(),
                        ]);
                    }

                    // Update representative user's organisation_id
                    $representativeUser->update(['organisation_id' => $organization->id]);

                    // Send password setup invitation
                    Mail::to($representativeEmail)->send(
                        new \App\Mail\RepresentativeInvitationMail($representativeUser, $organization, $user)
                    );
                }
            }
        }

        // Send notification email to organization
        Mail::to($organization->email)->send(
            new OrganizationCreatedMail($organization, $user)
        );

        return response()->json([
            'success' => true,
            'message' => 'Organisation erfolgreich erstellt!',
            'redirect_url' => route('organizations.show', $organization->slug),
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'email' => $organization->email,
                'slug' => $organization->slug,
            ]
        ], 201);
    }

    /**
     * Show organization dashboard.
     *
     * GET /organizations/{slug}
     */
    public function show(string $slug)
    {
        $organization = Organization::where('slug', $slug)
            ->with(['users' => function ($query) {
                $query->wherePivot('role', 'admin');
            }])
            ->firstOrFail();

        // Check if current user is a member
        $isMember = $organization->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'Sie haben keinen Zugriff auf diese Organisation.');
        }

        // Check if demo election exists for this organisation
        $demoElection = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $organization->id)
            ->first();

        $demoStats = null;
        if ($demoElection) {
            $posts = DemoPost::where('election_id', $demoElection->id)->count();
            $candidates = DemoCandidacy::whereIn('post_id',
                DemoPost::where('election_id', $demoElection->id)->pluck('post_id')
            )->count();
            $codes = DemoCode::where('election_id', $demoElection->id)->count();
            $votes = DemoVote::where('election_id', $demoElection->id)->count();

            $demoStats = [
                'exists' => true,
                'election_id' => $demoElection->id,
                'election_name' => $demoElection->name,
                'posts' => $posts,
                'candidates' => $candidates,
                'codes' => $codes,
                'votes' => $votes,
            ];
        } else {
            $demoStats = [
                'exists' => false,
                'posts' => 0,
                'candidates' => 0,
                'codes' => 0,
                'votes' => 0,
            ];
        }

        return inertia('Organizations/Show', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'email' => $organization->email,
                'created_at' => $organization->created_at->format('d.m.Y'),
            ],
            'stats' => [
                'members_count' => $organization->users()->count(),
                'elections_count' => 0, // Update when elections context exists
            ],
            'demoStatus' => $demoStats,
            'canManage' => $isMember,
        ]);
    }
}
