<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipantController extends Controller
{
    /**
     * List everyone with a platform role in this organisation.
     * This includes owners, admins, election commissioners, voters, and members —
     * regardless of whether they have a formal membership record.
     */
    public function index(Request $request, Organisation $organisation): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,role,created_at',
        ]);

        $query = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->with('user');

        // Filtering
        if ($request->filled('name')) {
            $query->whereHas('user', fn ($q) =>
                $q->where('name', 'LIKE', '%' . $request->name . '%')
            );
        }
        if ($request->filled('email')) {
            $query->whereHas('user', fn ($q) =>
                $q->where('email', 'LIKE', '%' . $request->email . '%')
            );
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sorting
        $direction = in_array($request->input('direction'), ['asc', 'desc'])
            ? $request->input('direction') : 'asc';
        $field     = in_array($request->input('field'), ['role', 'created_at'])
            ? $request->input('field') : 'role';

        $query->orderBy($field, $direction);

        // Pre-load member status: map organisation_user_id → member status
        $memberStatuses = Member::where('organisation_id', $organisation->id)
            ->pluck('status', 'organisation_user_id')
            ->toArray();

        // We also need organisation_user records to link UserOrganisationRole → Member
        $orgUserMap = \App\Models\OrganisationUser::where('organisation_id', $organisation->id)
            ->pluck('id', 'user_id')
            ->toArray();

        $participants = $query->paginate(20)->through(function ($r) use ($memberStatuses, $orgUserMap) {
            $orgUserId     = $orgUserMap[$r->user_id] ?? null;
            $memberStatus  = $orgUserId ? ($memberStatuses[$orgUserId] ?? null) : null;

            return [
                'id'             => $r->id,
                'user_id'        => $r->user_id,
                'name'           => $r->user?->name ?? '—',
                'email'          => $r->user?->email ?? '—',
                'role'           => $r->role,
                'is_paid_member' => $memberStatus === 'active',
                'member_status'  => $memberStatus,
                'created_at'     => $r->created_at?->toIso8601String(),
            ];
        });

        // Role counts
        $roleCounts = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        return Inertia::render('Organisations/Participants/Index', [
            'participants' => $participants,
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'filters'      => $request->only(['name', 'email', 'role', 'field', 'direction']),
            'stats'        => [
                'total'      => array_sum($roleCounts),
                'role_counts' => $roleCounts,
            ],
        ]);
    }

    public function export(Request $request, Organisation $organisation): StreamedResponse
    {
        $query = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->with('user');

        if ($request->filled('name')) {
            $query->whereHas('user', fn ($q) =>
                $q->where('name', 'LIKE', '%' . $request->name . '%')
            );
        }
        if ($request->filled('email')) {
            $query->whereHas('user', fn ($q) =>
                $q->where('email', 'LIKE', '%' . $request->email . '%')
            );
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $rows = $query->orderByRaw("role, created_at")->get();

        $memberStatuses = Member::where('organisation_id', $organisation->id)
            ->pluck('status', 'organisation_user_id')->toArray();
        $orgUserMap = \App\Models\OrganisationUser::where('organisation_id', $organisation->id)
            ->pluck('id', 'user_id')->toArray();

        $filename = 'participants-' . $organisation->slug . '-' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows, $memberStatuses, $orgUserMap) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Platform Role', 'Paid Member', 'Joined'], ';');
            foreach ($rows as $r) {
                $orgUserId    = $orgUserMap[$r->user_id] ?? null;
                $memberStatus = $orgUserId ? ($memberStatuses[$orgUserId] ?? '—') : '—';
                fputcsv($handle, [
                    $r->user?->name ?? '—',
                    $r->user?->email ?? '—',
                    $r->role,
                    $memberStatus === 'active' ? 'Yes' : 'No',
                    $r->created_at?->format('Y-m-d'),
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
