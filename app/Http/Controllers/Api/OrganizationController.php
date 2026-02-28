<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Mail\OrganizationCreatedMail;
use App\Models\Organisation;
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
     * Create a new organisation from the onboarding form.
     *
     * POST /organisations
     */
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        // Debug: Log that store() method was called
        error_log('🔵 OrganizationController::store() called at ' . date('Y-m-d H:i:s'));
        error_log('📝 Request data: ' . json_encode($request->all()));

        try {
            \Log::info('organisation creation started', [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['password', 'representative']),
            ]);

            $user = auth()->user();

            // Create organisation
            \Log::info('Creating organisation record');
            $organisation = Organisation::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'representative' => $request->representative,
                'created_by' => $user->id,
                'slug' => Str::slug($request->name),
            ]);
            \Log::info('organisation created', ['organisation_id' => $organisation->id]);

            // Attach current user as organisation admin
            \Log::info('Attaching user to organisation');
            $organisation->users()->attach($user->id, [
                'role' => 'admin',
                'assigned_at' => now(),
            ]);
            \Log::info('User attached to organisation');

            // Update current user's organisation_id
            \Log::info('Updating user organisation_id');
            $user->update(['organisation_id' => $organisation->id]);
            \Log::info('User organisation_id updated');

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

                        // Check if user is already attached to organisation (avoid duplicates)
                        $isAlreadyMember = $organisation->users()
                            ->where('users.id', $representativeUser->id)
                            ->exists();

                        if (!$isAlreadyMember) {
                            // Attach as voter only if not already a member
                            $organisation->users()->attach($representativeUser->id, [
                                'role' => 'voter',
                                'assigned_at' => now(),
                            ]);
                        }

                        // Update representative user's organisation_id
                        $representativeUser->update(['organisation_id' => $organisation->id]);

                        // Send password setup invitation
                        try {
                            Mail::to($representativeEmail)->send(
                                new \App\Mail\RepresentativeInvitationMail($representativeUser, $organisation, $user)
                            );
                        } catch (\Exception $e) {
                            \Log::warning('Failed to send representative invitation email', [
                                'organisation_id' => $organisation->id,
                                'representative_email' => $representativeEmail,
                                'error' => $e->getMessage(),
                            ]);
                            // Continue even if email fails
                        }
                    }
                }
            }

            // Send notification email to organisation
            try {
                Mail::to($organisation->email)->send(
                    new OrganizationCreatedMail($organisation, $user)
                );
            } catch (\Exception $e) {
                \Log::warning('Failed to send organisation created email', [
                    'organisation_id' => $organisation->id,
                    'organisation_email' => $organisation->email,
                    'error' => $e->getMessage(),
                ]);
                // Continue even if email fails
            }

            // For Inertia 2.0: Return JSON with redirect URL
            // Inertia will handle the redirect on the frontend
            return response()->json([
                'success' => true,
                'message' => 'Organisation erfolgreich erstellt!',
                'redirect' => route('organisations.show', $organisation->slug),
                'organisation' => $organisation,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('organisation creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            // CRITICAL: Always return JSON for errors (JSON requests or validation errors)
            // The StoreOrganizationRequest->failedValidation() already throws JSON responses
            // So this catch block is for unexpected server errors
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Erstellen der Organisation: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show organisation dashboard.
     *
     * GET /organisations/{slug}
     */
    public function show(string $slug)
    {
        $organisation = Organisation::where('slug', $slug)
            ->with(['users' => function ($query) {
                $query->wherePivot('role', 'admin');
            }])
            ->firstOrFail();

        // Check if current user is a member
        $isMember = $organisation->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'Sie haben keinen Zugriff auf diese Organisation.');
        }

        // Check if demo election exists for this organisation
        $demoElection = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $organisation->id)
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
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
                'email' => $organisation->email,
                'created_at' => $organisation->created_at->format('d.m.Y'),
            ],
            'stats' => [
                'members_count' => $organisation->users()->count(),
                'elections_count' => 0, // Update when elections context exists
            ],
            'demoStatus' => $demoStats,
            'canManage' => $isMember,
        ]);
    }
}
