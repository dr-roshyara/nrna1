<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VotingSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Admin controller for monitoring and managing voting security
 */
class VotingSecurityController extends Controller
{
    protected VotingSecurityService $securityService;

    public function __construct(VotingSecurityService $securityService)
    {
        $this->securityService = $securityService;
        $this->middleware(['auth', 'committee.member']); // Only election committee can access
    }

    /**
     * Security dashboard showing overall voting security status
     */
    public function dashboard()
    {
        $stats = [
            'total_active_slugs' => VoterSlug::where('is_active', true)
                                           ->where('expires_at', '>', now())
                                           ->count(),

            'total_voters_with_slugs' => VoterSlug::select('user_id')
                                                  ->where('is_active', true)
                                                  ->where('expires_at', '>', now())
                                                  ->distinct()
                                                  ->count(),

            'security_violations' => $this->getSecurityViolations(),

            'suspicious_activity' => $this->getSuspiciousActivity(),

            'recent_lockdowns' => $this->getRecentLockdowns(),
        ];

        return Inertia::render('Admin/VotingSecurity/Dashboard', [
            'stats' => $stats,
        ]);
    }

    /**
     * List all users with security violations
     */
    public function violations()
    {
        $violations = collect();

        // Find users with multiple active slugs
        $multipleSlugUsers = VoterSlug::select('user_id')
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->groupBy('user_id')
            ->having(\DB::raw('COUNT(*)'), '>', 1)
            ->get();

        foreach ($multipleSlugUsers as $userRecord) {
            $user = User::find($userRecord->user_id);
            $audit = $this->securityService->auditUserVotingSecurity($user);

            if ($audit['security_status'] !== 'secure') {
                $violations->push($audit);
            }
        }

        return Inertia::render('Admin/VotingSecurity/Violations', [
            'violations' => $violations->toArray(),
        ]);
    }

    /**
     * Detailed security audit for a specific user
     */
    public function auditUser(Request $request, User $user)
    {
        $audit = $this->securityService->auditUserVotingSecurity($user);

        return Inertia::render('Admin/VotingSecurity/UserAudit', [
            'user' => $user,
            'audit' => $audit,
        ]);
    }

    /**
     * Enforce security for a specific user (fix multiple slugs)
     */
    public function enforceSecurity(Request $request, User $user)
    {
        $enforcement = $this->securityService->enforceOneActiveSlugPerUser($user);

        return redirect()->back()->with('success',
            "Security enforcement completed. Deactivated {$enforcement['deactivated_slugs']} duplicate slugs."
        );
    }

    /**
     * Emergency lockdown for a user
     */
    public function emergencyLockdown(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $adminDetails = [
            'admin_name' => auth()->user()->name,
            'admin_id' => auth()->id(),
        ];

        $result = $this->securityService->emergencyLockdown(
            $user,
            $request->reason,
            $adminDetails
        );

        if ($result) {
            return redirect()->back()->with('success',
                'Emergency lockdown applied successfully. All voting access revoked.'
            );
        }

        return redirect()->back()->with('error', 'Failed to apply emergency lockdown.');
    }

    /**
     * Generate recovery slug for voters with expired/problematic slugs
     * This allows election committee to help voters continue voting
     */
    public function generateRecoverySlug(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $adminDetails = [
            'admin_name' => auth()->user()->name,
            'admin_id' => auth()->id(),
            'recovery_reason' => $request->reason,
        ];

        // Check if user is eligible for recovery
        if ($user->has_voted) {
            return redirect()->back()->with('error',
                'Cannot generate recovery slug - user has already completed voting.'
            );
        }

        if (!$user->is_voter || !$user->can_vote) {
            return redirect()->back()->with('error',
                'Cannot generate recovery slug - user is not eligible to vote.'
            );
        }

        try {
            // Deactivate any existing slugs first
            $existingSlugs = VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($existingSlugs as $slug) {
                $slug->update([
                    'is_active' => false,
                    'step_meta' => array_merge($slug->step_meta ?? [], [
                        'deactivated_by_admin' => true,
                        'deactivated_reason' => 'recovery_slug_generated',
                        'admin_name' => $adminDetails['admin_name'],
                    ])
                ]);
            }

            // Generate new recovery slug
            $result = $this->securityService->secureSlugGeneration($user, 'admin_recovery');

            if (!$result['success']) {
                return redirect()->back()->with('error',
                    'Failed to generate recovery slug: ' . implode(', ', $result['reasons'])
                );
            }

            $newSlug = $result['slug'];

            // Add recovery metadata
            $newSlug->update([
                'step_meta' => array_merge($newSlug->step_meta ?? [], [
                    'recovery_slug' => true,
                    'admin_generated' => true,
                    'admin_name' => $adminDetails['admin_name'],
                    'admin_id' => $adminDetails['admin_id'],
                    'recovery_reason' => $adminDetails['recovery_reason'],
                    'generated_at' => now()->toISOString(),
                ])
            ]);

            \Log::info('Recovery slug generated by admin', [
                'admin_id' => auth()->id(),
                'admin_name' => $adminDetails['admin_name'],
                'user_id' => $user->id,
                'user_name' => $user->name,
                'new_slug' => $newSlug->slug,
                'reason' => $request->reason,
                'expires_at' => $newSlug->expires_at,
            ]);

            $recoveryUrl = route('slug.code.create', ['vslug' => $newSlug->slug]);

            return redirect()->back()->with('success',
                "Recovery slug generated successfully! New voting URL: {$recoveryUrl}"
            )->with('recovery_url', $recoveryUrl);

        } catch (\Exception $e) {
            \Log::error('Failed to generate recovery slug', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error',
                'Failed to generate recovery slug due to system error.'
            );
        }
    }

    /**
     * Real-time security monitoring API endpoint
     */
    public function monitoringData()
    {
        return response()->json([
            'timestamp' => now(),
            'active_voters' => VoterSlug::where('is_active', true)
                                      ->where('expires_at', '>', now())
                                      ->count(),

            'violations' => VoterSlug::select('user_id')
                                   ->where('is_active', true)
                                   ->where('expires_at', '>', now())
                                   ->groupBy('user_id')
                                   ->having(\DB::raw('COUNT(*)'), '>', 1)
                                   ->count(),

            'recent_activity' => VoterSlug::where('created_at', '>', now()->subMinutes(5))
                                        ->count(),
        ]);
    }

    /**
     * Generate security report
     */
    public function generateReport()
    {
        $report = [
            'generated_at' => now(),
            'generated_by' => auth()->user()->name,
            'summary' => [
                'total_registered_voters' => User::where('is_voter', true)->count(),
                'eligible_voters' => User::where('is_voter', true)->where('can_vote', true)->count(),
                'completed_voters' => User::where('is_voter', true)->where('has_voted', true)->count(),
                'active_voting_sessions' => VoterSlug::where('is_active', true)
                                                    ->where('expires_at', '>', now())
                                                    ->count(),
            ],
            'security_analysis' => $this->getComprehensiveSecurityAnalysis(),
            'violations' => $this->getAllSecurityViolations(),
            'recommendations' => $this->getSecurityRecommendations(),
        ];

        return response()->json($report);
    }

    // Private helper methods

    private function getSecurityViolations(): array
    {
        return VoterSlug::select('user_id')
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->groupBy('user_id')
            ->having(\DB::raw('COUNT(*)'), '>', 1)
            ->with('user')
            ->get()
            ->map(function ($record) {
                return [
                    'user_id' => $record->user_id,
                    'user_name' => $record->user->name ?? 'Unknown',
                    'violation_type' => 'multiple_active_slugs',
                    'active_slugs_count' => VoterSlug::where('user_id', $record->user_id)
                                                   ->where('is_active', true)
                                                   ->count(),
                ];
            })
            ->toArray();
    }

    private function getSuspiciousActivity(): array
    {
        // Users with many slug generations in short time
        return VoterSlug::select('user_id')
            ->where('created_at', '>', now()->subHours(2))
            ->groupBy('user_id')
            ->having(\DB::raw('COUNT(*)'), '>', 3)
            ->with('user')
            ->get()
            ->map(function ($record) {
                return [
                    'user_id' => $record->user_id,
                    'user_name' => $record->user->name ?? 'Unknown',
                    'activity_type' => 'excessive_slug_generation',
                    'slug_count' => VoterSlug::where('user_id', $record->user_id)
                                           ->where('created_at', '>', now()->subHours(2))
                                           ->count(),
                ];
            })
            ->toArray();
    }

    private function getRecentLockdowns(): array
    {
        return VoterSlug::whereJsonContains('step_meta->emergency_lockdown', true)
            ->where('updated_at', '>', now()->subHours(24))
            ->with('user')
            ->get()
            ->map(function ($slug) {
                return [
                    'user_id' => $slug->user_id,
                    'user_name' => $slug->user->name ?? 'Unknown',
                    'lockdown_reason' => $slug->step_meta['lockdown_reason'] ?? 'Unknown',
                    'lockdown_time' => $slug->step_meta['lockdown_timestamp'] ?? $slug->updated_at,
                    'admin' => $slug->step_meta['lockdown_admin'] ?? 'Unknown',
                ];
            })
            ->toArray();
    }

    private function getComprehensiveSecurityAnalysis(): array
    {
        return [
            'one_person_one_vote_compliance' => [
                'compliant_users' => User::whereHas('voterSlugs', function ($query) {
                    $query->where('is_active', true)->where('expires_at', '>', now());
                })->whereDoesntHave('voterSlugs', function ($query) {
                    $query->where('is_active', true)
                          ->where('expires_at', '>', now())
                          ->having(\DB::raw('COUNT(*)'), '>', 1);
                })->count(),

                'violation_rate' => $this->calculateViolationRate(),
            ],
            'temporal_analysis' => [
                'peak_voting_hours' => $this->getPeakVotingHours(),
                'average_session_duration' => $this->getAverageSessionDuration(),
            ],
        ];
    }

    private function getAllSecurityViolations(): array
    {
        // Implement comprehensive violation detection
        return array_merge(
            $this->getSecurityViolations(),
            $this->getSuspiciousActivity()
        );
    }

    private function getSecurityRecommendations(): array
    {
        $recommendations = [];

        if (count($this->getSecurityViolations()) > 0) {
            $recommendations[] = 'Multiple active slugs detected - run security enforcement';
        }

        if (count($this->getSuspiciousActivity()) > 0) {
            $recommendations[] = 'Suspicious activity detected - review user behavior patterns';
        }

        return $recommendations;
    }

    private function calculateViolationRate(): float
    {
        $totalActiveUsers = VoterSlug::where('is_active', true)
                                   ->where('expires_at', '>', now())
                                   ->distinct('user_id')
                                   ->count();

        if ($totalActiveUsers === 0) return 0.0;

        $violatingUsers = count($this->getSecurityViolations());

        return round(($violatingUsers / $totalActiveUsers) * 100, 2);
    }

    private function getPeakVotingHours(): array
    {
        return VoterSlug::selectRaw("EXTRACT(HOUR FROM created_at) as hour, COUNT(*) as count")
            ->where('created_at', '>', now()->subDays(7))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($record) {
                return [
                    'hour' => $record->hour . ':00',
                    'activity_count' => $record->count,
                ];
            })
            ->toArray();
    }

    private function getAverageSessionDuration(): float
    {
        // Calculate average time between slug creation and deactivation
        return VoterSlug::where('is_active', false)
            ->where('created_at', '>', now()->subDays(7))
            ->get()
            ->avg(function ($slug) {
                return $slug->expires_at->diffInMinutes($slug->created_at);
            });
    }
}