<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    /**
     * Display formal (paid) members of the organisation.
     * Only rows in the `members` table are shown — NOT everyone with a platform role.
     */
    public function index(Request $request, Organisation $organisation): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,status,joined_at,membership_expires_at,created_at',
        ]);

        $query = Member::where('organisation_id', $organisation->id)
            ->with('organisationUser.user');

        // Filtering
        if ($request->filled('name')) {
            $query->whereHas('organisationUser.user', fn ($q) =>
                $q->where('name', 'LIKE', '%' . $request->name . '%')
            );
        }
        if ($request->filled('email')) {
            $query->whereHas('organisationUser.user', fn ($q) =>
                $q->where('email', 'LIKE', '%' . $request->email . '%')
            );
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $allowedFields = ['status', 'joined_at', 'membership_expires_at', 'created_at'];
        $direction = in_array($request->input('direction'), ['asc', 'desc'])
            ? $request->input('direction') : 'desc';
        $field = in_array($request->input('field'), $allowedFields)
            ? $request->input('field') : 'created_at';

        $query->orderBy($field, $direction);

        $members = $query->paginate(20)->through(fn ($m) => [
            'id'                    => $m->id,
            'name'                  => $m->organisationUser?->user?->name ?? '—',
            'email'                 => $m->organisationUser?->user?->email ?? '—',
            'status'                => $m->status,
            'membership_expires_at' => $m->membership_expires_at?->toIso8601String(),
            'joined_at'             => $m->joined_at?->toIso8601String(),
            'pending_fees'          => (float) MembershipFee::where('member_id', $m->id)
                                            ->where('status', 'pending')->sum('amount'),
            'created_at'            => $m->created_at?->toIso8601String(),
        ]);

        return Inertia::render('Members/Index', [
            'members'      => $members,
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'filters'      => $request->only(['name', 'email', 'status', 'field', 'direction']),
            'stats'        => [
                'total_members'  => Member::where('organisation_id', $organisation->id)
                                        ->where('status', 'active')->count(),
                'expired_count'  => Member::where('organisation_id', $organisation->id)
                                        ->where('status', 'expired')->count(),
                'pending_fees'   => (float) MembershipFee::where('organisation_id', $organisation->id)
                                        ->where('status', 'pending')->sum('amount'),
            ],
        ]);
    }

    public function export(Request $request, Organisation $organisation): StreamedResponse
    {
        $query = Member::where('organisation_id', $organisation->id)
            ->with('organisationUser.user');

        if ($request->filled('name')) {
            $query->whereHas('organisationUser.user', fn ($q) =>
                $q->where('name', 'LIKE', '%' . $request->name . '%')
            );
        }
        if ($request->filled('email')) {
            $query->whereHas('organisationUser.user', fn ($q) =>
                $q->where('email', 'LIKE', '%' . $request->email . '%')
            );
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('created_at')->get();

        $filename = 'members-' . $organisation->slug . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($members) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Status', 'Joined', 'Expires'], ';');
            foreach ($members as $m) {
                fputcsv($handle, [
                    $m->organisationUser?->user?->name ?? '—',
                    $m->organisationUser?->user?->email ?? '—',
                    $m->status,
                    $m->joined_at?->format('Y-m-d'),
                    $m->membership_expires_at?->format('Y-m-d') ?? 'Lifetime',
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
