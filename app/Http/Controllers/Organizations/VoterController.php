<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * VoterController - Organization-Scoped Voter Management
 *
 * Manages voters for a specific organization with strict security:
 * - ALL queries filtered by organization_id
 * - Commission members can approve/suspend voters
 * - Regular members can only view voters
 * - Comprehensive audit logging
 *
 * CRITICAL: Every database query must include organization_id filter
 */
class VoterController extends Controller
{
    /**
     * Display a paginated listing of voters for the organization
     *
     * GET /organizations/{slug}/voters
     *
     * Query Parameters:
     * - search: Filter by name, user_id, or email
     * - status: Filter by 'approved', 'pending', 'voted'
     * - sort: Sort by column name
     * - order: 'asc' or 'desc'
     * - per_page: Items per page (default 50)
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Get organization from middleware (set by EnsureOrganization)
        $organization = $request->attributes->get('organization');
        if (!$organization) {
            abort(400, 'Organization not found in request context');
        }

        // Check if user is commission member for action permissions
        $isCommissionMember = auth()->user()->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->wherePivot('role', 'commission')
            ->exists();

        // Build base query with organization scope
        $query = User::where('is_voter', 1)
            ->where('organisation_id', $organization->id)
            ->select([
                'id', 'user_id', 'name', 'email', 'region',
                'is_voter', 'has_voted', 'approvedBy', 'voting_ip', 'created_at'
            ]);

        // Apply search filter
        if ($search = $request->input('search')) {
            if (strlen($search) > 2) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', $search . '%')
                      ->orWhere('user_id', 'LIKE', $search . '%')
                      ->orWhere('email', 'LIKE', $search . '%');
                });
            }
        }

        // Apply status filter
        if ($status = $request->input('status')) {
            switch ($status) {
                case 'approved':
                    $query->whereNotNull('approvedBy');
                    break;
                case 'pending':
                    $query->whereNull('approvedBy');
                    break;
                case 'voted':
                    $query->where('has_voted', 1);
                    break;
            }
        }

        // Apply sorting
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        if (in_array($sort, ['name', 'user_id', 'created_at', 'approvedBy'])) {
            $query->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
        }

        // Paginate results
        $voters = $query->paginate($request->input('per_page', 50));

        // Calculate statistics (cached for 1 hour per organization)
        $stats = Cache::remember("org_{$organization->id}_voter_stats", 3600, function () use ($organization) {
            return DB::selectOne('
                SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN approvedBy IS NOT NULL THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN approvedBy IS NULL THEN 1 ELSE 0 END) as pending,
                    SUM(has_voted) as voted
                FROM users
                WHERE organisation_id = ? AND is_voter = 1
            ', [$organization->id]);
        });

        Log::channel('voting_audit')->info('Voter list accessed', [
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'total_voters' => $voters->total(),
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ]);

        return Inertia::render('Organizations/Voters/Index', [
            'organization' => $organization,
            'voters' => $voters,
            'stats' => [
                'total' => $stats->total ?? 0,
                'approved' => $stats->approved ?? 0,
                'pending' => $stats->pending ?? 0,
                'voted' => $stats->voted ?? 0,
            ],
            'isCommissionMember' => $isCommissionMember,
            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'sort' => $request->input('sort', 'created_at'),
                'order' => $request->input('order', 'desc'),
            ],
        ]);
    }

    /**
     * Approve a voter for voting
     *
     * POST /organizations/{slug}/voters/{voter}/approve
     *
     * @param  Request  $request
     * @param  Organization  $organization
     * @param  User  $voter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Organization $organization, User $voter)
    {
        // Verify user is commission member
        if (!auth()->user()->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->wherePivot('role', 'commission')
            ->exists()) {
            Log::warning('Non-commission member attempted voter approval', [
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'voter_id' => $voter->id,
                'ip' => $request->ip(),
            ]);
            abort(403, 'Only commission members can approve voters');
        }

        // Verify voter belongs to this organization
        if ($voter->organisation_id !== $organization->id) {
            Log::warning('Cross-organization voter access attempt', [
                'user_id' => auth()->id(),
                'accessed_org_id' => $organization->id,
                'voter_org_id' => $voter->organisation_id,
                'voter_id' => $voter->id,
                'ip' => $request->ip(),
            ]);
            abort(403, 'Voter does not belong to this organization');
        }

        // Approve the voter
        $voter->update([
            'approvedBy' => auth()->user()->name,
            'voting_ip' => $request->ip(),
        ]);

        // Invalidate cached statistics
        Cache::forget("org_{$organization->id}_voter_stats");

        // Log the action
        Log::channel('voting_audit')->info('Voter approved', [
            'approver_id' => auth()->id(),
            'approver_name' => auth()->user()->name,
            'voter_id' => $voter->id,
            'voter_name' => $voter->name,
            'organization_id' => $organization->id,
            'ip_address' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        return back()->with('success', __('organizations.voters.messages.approved', [
            'name' => $voter->name
        ]));
    }

    /**
     * Suspend a voter (revoke voting rights)
     *
     * POST /organizations/{slug}/voters/{voter}/suspend
     *
     * @param  Request  $request
     * @param  Organization  $organization
     * @param  User  $voter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend(Request $request, Organization $organization, User $voter)
    {
        // Verify user is commission member
        if (!auth()->user()->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->wherePivot('role', 'commission')
            ->exists()) {
            Log::warning('Non-commission member attempted voter suspension', [
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'voter_id' => $voter->id,
                'ip' => $request->ip(),
            ]);
            abort(403, 'Only commission members can suspend voters');
        }

        // Verify voter belongs to this organization
        if ($voter->organisation_id !== $organization->id) {
            Log::warning('Cross-organization voter access attempt', [
                'user_id' => auth()->id(),
                'accessed_org_id' => $organization->id,
                'voter_org_id' => $voter->organisation_id,
                'voter_id' => $voter->id,
                'ip' => $request->ip(),
            ]);
            abort(403, 'Voter does not belong to this organization');
        }

        // Suspend the voter (revoke approval)
        $voter->update([
            'approvedBy' => null,
            'can_vote_now' => 0,
        ]);

        // Invalidate cached statistics
        Cache::forget("org_{$organization->id}_voter_stats");

        // Log the action
        Log::channel('voting_audit')->info('Voter suspended', [
            'suspender_id' => auth()->id(),
            'suspender_name' => auth()->user()->name,
            'voter_id' => $voter->id,
            'voter_name' => $voter->name,
            'organization_id' => $organization->id,
            'ip_address' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        return back()->with('success', __('organizations.voters.messages.suspended', [
            'name' => $voter->name
        ]));
    }

    /**
     * Bulk approve multiple voters
     *
     * POST /organizations/{slug}/voters/bulk-approve
     *
     * @param  Request  $request
     * @param  Organization  $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkApprove(Request $request, Organization $organization)
    {
        // Verify user is commission member
        if (!auth()->user()->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->wherePivot('role', 'commission')
            ->exists()) {
            abort(403, 'Only commission members can approve voters');
        }

        $voterIds = $request->input('voter_ids', []);
        if (empty($voterIds)) {
            return back()->withErrors(['error' => 'No voters selected']);
        }

        // Get voters in this organization only
        $voters = User::whereIn('id', $voterIds)
            ->where('organisation_id', $organization->id)
            ->where('is_voter', 1)
            ->get();

        // Approve each voter
        foreach ($voters as $voter) {
            $voter->update([
                'approvedBy' => auth()->user()->name,
                'voting_ip' => $request->ip(),
            ]);

            Log::channel('voting_audit')->info('Voter approved (bulk)', [
                'approver_id' => auth()->id(),
                'voter_id' => $voter->id,
                'organization_id' => $organization->id,
            ]);
        }

        // Invalidate cached statistics
        Cache::forget("org_{$organization->id}_voter_stats");

        return back()->with('success', __('organizations.voters.messages.bulk_approved', [
            'count' => $voters->count()
        ]));
    }

    /**
     * Bulk suspend multiple voters
     *
     * POST /organizations/{slug}/voters/bulk-suspend
     *
     * @param  Request  $request
     * @param  Organization  $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkSuspend(Request $request, Organization $organization)
    {
        // Verify user is commission member
        if (!auth()->user()->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->wherePivot('role', 'commission')
            ->exists()) {
            abort(403, 'Only commission members can suspend voters');
        }

        $voterIds = $request->input('voter_ids', []);
        if (empty($voterIds)) {
            return back()->withErrors(['error' => 'No voters selected']);
        }

        // Get voters in this organization only
        $voters = User::whereIn('id', $voterIds)
            ->where('organisation_id', $organization->id)
            ->where('is_voter', 1)
            ->get();

        // Suspend each voter
        foreach ($voters as $voter) {
            $voter->update([
                'approvedBy' => null,
                'can_vote_now' => 0,
            ]);

            Log::channel('voting_audit')->info('Voter suspended (bulk)', [
                'suspender_id' => auth()->id(),
                'voter_id' => $voter->id,
                'organization_id' => $organization->id,
            ]);
        }

        // Invalidate cached statistics
        Cache::forget("org_{$organization->id}_voter_stats");

        return back()->with('success', __('organizations.voters.messages.bulk_suspended', [
            'count' => $voters->count()
        ]));
    }
}
