<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class MembershipDashboardController extends Controller
{
    // ── Entry point ───────────────────────────────────────────────────────────

    public function index(Organisation $organisation): Response
    {
        $role = $this->getUserRole(auth()->user(), $organisation);

        if (! in_array($role, ['owner', 'admin', 'commission', 'member'])) {
            abort(403, 'You do not have access to membership management.');
        }

        $data = Cache::remember(
            "membership_dashboard_{$organisation->id}_{$role}",
            300,
            fn () => $this->buildDashboardData($organisation, $role)
        );

        return Inertia::render('Organisations/Membership/Dashboard/Index', array_merge(
            ['organisation' => $organisation, 'role' => $role],
            $data
        ));
    }

    // ── Role resolution ───────────────────────────────────────────────────────

    private function getUserRole(User $user, Organisation $organisation): ?string
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
    }

    // ── Dashboard data builder ────────────────────────────────────────────────

    private function buildDashboardData(Organisation $organisation, string $role): array
    {
        return match ($role) {
            'owner', 'admin' => [
                'stats' => [
                    'total_members'      => Member::where('organisation_id', $organisation->id)
                        ->where('status', 'active')->count(),
                    'pending_apps'       => MembershipApplication::where('organisation_id', $organisation->id)
                        ->whereIn('status', ['submitted', 'under_review'])->count(),
                    'pending_fees_total' => (float) MembershipFee::where('organisation_id', $organisation->id)
                        ->where('status', 'pending')->sum('amount'),
                    'expiring_in_30'     => Member::where('organisation_id', $organisation->id)
                        ->whereBetween('membership_expires_at', [now(), now()->addDays(30)])->count(),
                ],
                'applications'    => MembershipApplication::where('organisation_id', $organisation->id)
                    ->with(['user', 'membershipType'])
                    ->latest()
                    ->paginate(15),
                'expiringMembers' => Member::where('organisation_id', $organisation->id)
                    ->with('organisationUser.user')
                    ->whereBetween('membership_expires_at', [now(), now()->addDays(30)])
                    ->orderBy('membership_expires_at')
                    ->get(),
                'recentActivity'  => $this->getRecentActivity($organisation),
                'memberSelf'      => null,
            ],

            'commission' => [
                'stats' => [
                    'total_members' => Member::where('organisation_id', $organisation->id)->count(),
                    'pending_apps'  => MembershipApplication::where('organisation_id', $organisation->id)
                        ->whereIn('status', ['submitted', 'under_review'])->count(),
                ],
                'applications'    => MembershipApplication::where('organisation_id', $organisation->id)
                    ->with(['user', 'membershipType'])
                    ->latest()
                    ->paginate(15),
                'expiringMembers' => collect(),
                'recentActivity'  => [],
                'memberSelf'      => null,
            ],

            'member' => [
                'stats'           => [],
                'applications'    => null,
                'expiringMembers' => collect(),
                'recentActivity'  => [],
                'memberSelf'      => $this->getMemberData($organisation),
            ],

            default => abort(403),
        };
    }

    // ── Recent activity feed (owner / admin) ──────────────────────────────────

    private function getRecentActivity(Organisation $organisation, int $limit = 10): array
    {
        $apps = MembershipApplication::where('organisation_id', $organisation->id)
            ->with(['user', 'membershipType'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn ($a) => [
                'type'       => 'application',
                'message'    => ($a->user->name ?? 'Unknown') . ' applied for ' . ($a->membershipType->name ?? 'membership'),
                'status'     => $a->status,
                'created_at' => $a->created_at?->toIso8601String(),
            ]);

        $fees = MembershipFee::where('organisation_id', $organisation->id)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take($limit)
            ->get()
            ->map(fn ($f) => [
                'type'       => 'payment',
                'message'    => "Payment of {$f->amount} {$f->currency} recorded",
                'status'     => 'paid',
                'created_at' => $f->paid_at?->toIso8601String(),
            ]);

        return $apps->concat($fees)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    // ── Member self-view data ─────────────────────────────────────────────────

    private function getMemberData(Organisation $organisation): array
    {
        $member = Member::where('organisation_id', $organisation->id)
            ->whereHas('organisationUser', fn ($q) => $q->where('user_id', auth()->id()))
            ->first();

        $myApplications = MembershipApplication::where('organisation_id', $organisation->id)
            ->where('user_id', auth()->id())
            ->with('membershipType')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($a) => [
                'id'             => $a->id,
                'status'         => $a->status,
                'submitted_at'   => $a->submitted_at?->toIso8601String(),
                'membership_type'=> $a->membershipType ? $a->membershipType->name : null,
            ]);

        if (! $member) {
            return [
                'has_membership'  => false,
                'platform_role'   => UserOrganisationRole::where('user_id', auth()->id())
                                        ->where('organisation_id', $organisation->id)
                                        ->value('role'),
                'apply_url'       => route('organisations.membership.apply', $organisation->slug),
                'my_applications' => $myApplications,
            ];
        }

        return [
            'has_membership'  => true,
            'member_id'       => $member->id,
            'status'          => $member->status,
            'expires_at'      => $member->membership_expires_at?->toIso8601String(),
            'expires_in_days' => $member->membership_expires_at
                ? max(0, (int) now()->diffInDays($member->membership_expires_at, false))
                : null,
            'pending_fees'    => (float) $member->fees()->where('status', 'pending')->sum('amount'),
            'can_self_renew'  => method_exists($member, 'canSelfRenew') ? $member->canSelfRenew() : false,
            'my_applications' => $myApplications,
        ];
    }
}
