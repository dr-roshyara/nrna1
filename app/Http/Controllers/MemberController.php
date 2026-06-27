<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Member as LegacyMember;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Services\MembershipPaymentService;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Application\Member\UseCases\RegisterMember;
use App\Contexts\Membership\Application\Member\UseCases\SuspendMember;
use App\Contexts\Membership\Application\Member\UseCases\ArchiveMember;
use App\Contexts\Membership\Application\Fee\UseCases\RecordFeePayment;
use App\Contexts\Membership\Application\Fee\UseCases\WaiveFee;
use App\Contexts\Membership\Application\Member\DTOs\RegisterMemberCommand;
use App\Contexts\Membership\Application\Fee\DTOs\RecordFeePaymentCommand;
use App\Contexts\Membership\Application\Fee\DTOs\WaiveFeeCommand;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class MemberController extends Controller
{
    /**
     * Display list of members.
     * Phase 3D: Read operation - now uses member_directories projection for consistency
     */
    public function index(Request $request, Organisation $organisation): Response
    {
        $this->authorize('viewApplications', $organisation);

        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:display_name,email,status,created_at',
        ]);

        $query = \Illuminate\Support\Facades\DB::table('member_directories')
            ->where('organisation_id', $organisation->id);

        // Filtering
        if ($request->filled('name')) {
            $query->where('display_name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $allowedFields = ['status', 'created_at', 'display_name', 'email'];
        $direction = in_array($request->input('direction'), ['asc', 'desc'])
            ? $request->input('direction') : 'desc';
        $field = in_array($request->input('field'), $allowedFields)
            ? $request->input('field') : 'created_at';

        $query->orderBy($field, $direction);

        $members = $query->paginate(20)->through(fn ($m) => [
            'id'                    => $m->member_id,
            'name'                  => $m->display_name ?? '—',
            'email'                 => $m->email ?? '—',
            'status'                => $m->status,
            'membership_expires_at' => null,
            'joined_at'             => null,
            'pending_fees'          => (float) MembershipFee::where('member_id', $m->member_id)
                                            ->where('status', 'pending')->sum('amount'),
            'created_at'            => $m->created_at ? \Carbon\Carbon::parse($m->created_at)->toIso8601String() : null,
        ]);

        return Inertia::render('Members/Index', [
            'members'      => $members,
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'filters'      => $request->only(['name', 'email', 'status', 'field', 'direction']),
            'stats'        => $this->getStats($organisation),
        ]);
    }

    /**
     * Register a new member using DDD use case.
     * Phase 3D: Routes through RegisterMember use case
     */
    public function store(Request $request, Organisation $organisation, RegisterMember $registerMember): RedirectResponse
    {
        $this->authorize('create', [LegacyMember::class, $organisation]);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'membership_type_id' => 'required|uuid|exists:membership_types,id',
        ]);

        $command = new RegisterMemberCommand(
            tenantId: TenantId::fromOrganisationId($organisation->id),
            fullName: $validated['full_name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? '',
            membershipTypeId: MembershipTypeId::fromString($validated['membership_type_id'])
        );

        try {
            $memberView = $registerMember->execute($command);
            return redirect()->route('organisations.members.index', $organisation->slug)
                ->with('success', "Member {$memberView->getFullName()} registered successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to register member: ' . $e->getMessage()]);
        }
    }

    /**
     * Export members to CSV.
     * Phase 3D: Read operation - legacy is fine
     */
    public function export(Request $request, Organisation $organisation): StreamedResponse
    {
        $query = \Illuminate\Support\Facades\DB::table('member_directories')
            ->where('organisation_id', $organisation->id);

        if ($request->filled('name')) {
            $query->where('display_name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
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
            fputcsv($handle, ['Name', 'Email', 'Status', 'Membership Type'], ';');
            foreach ($members as $m) {
                fputcsv($handle, [
                    $m->display_name ?? '—',
                    $m->email ?? '—',
                    $m->status,
                    $m->membership_type_name ?? '—',
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display member's financial dashboard.
     * Phase 3D: Read operation - legacy service is fine
     */
    public function finance(
        Organisation $organisation,
        LegacyMember $member,
        MembershipPaymentService $service
    ): Response {
        $this->authorize('recordFeePayment', $organisation);

        abort_if($member->organisation_id !== $organisation->id, 404);

        return Inertia::render('Organisations/Membership/Member/Finance', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'member' => $member->load('organisationUser.user', 'membershipType'),
            'outstandingFees' => $service->getOutstandingFees($member),
            'paymentHistory' => $service->getPaymentHistory($member),
            'stats' => $service->getDashboardStats($member),
        ]);
    }

    /**
     * Mark a member as paid (set fees_status to exempt and waive all pending fees).
     * Phase 3D: Admin operation to clear all outstanding fees for a member
     */
    public function markPaid(
        Organisation $organisation,
        LegacyMember $member
    ): RedirectResponse {
        $this->authorize('recordFeePayment', $organisation);

        if ($member->organisation_id !== $organisation->id) {
            abort(404);
        }

        $member->update(['fees_status' => 'exempt']);

        $member->fees()
            ->where('status', 'pending')
            ->update(['status' => 'waived']);

        return redirect()->back()->with('success', 'Member fees marked as paid successfully.');
    }

    /**
     * Waive all pending fees for a member.
     * Phase 3D: Routes through WaiveFee use case
     */
    public function waiveFees(
        Organisation $organisation,
        LegacyMember $member,
        WaiveFee $waiveFeeUseCase
    ): RedirectResponse {
        $this->authorize('recordFeePayment', $organisation);

        if ($member->organisation_id !== $organisation->id) {
            abort(404);
        }

        $tenantId = TenantId::fromOrganisationId($organisation->id);
        $pendingFees = $member->fees()->where('status', 'pending')->get();
        $waivedCount = 0;

        foreach ($pendingFees as $fee) {
            try {
                $command = new WaiveFeeCommand(
                    feeId: FeeId::fromString($fee->id),
                    tenantId: $tenantId,
                    reason: 'Waived by administrator',
                    waivedByUserId: auth()->id(),
                );
                $waiveFeeUseCase->execute($command);
                $waivedCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to waive fee {$fee->id}: " . $e->getMessage(), [
                    'member_id' => $member->id,
                    'organisation_id' => $organisation->id,
                ]);
            }
        }

        $message = $waivedCount > 0
            ? "Waived {$waivedCount} pending fee(s) for " . ($member->organisationUser?->user?->name ?? 'member')
            : "No pending fees to waive.";

        return back()->with('success', $message);
    }

    /**
     * Record a payment against a specific fee.
     * Phase 3D: Routes through RecordFeePayment use case
     */
    public function recordPayment(
        Organisation $organisation,
        LegacyMember $member,
        Request $request,
        RecordFeePayment $recordFeePayment
    ): RedirectResponse {
        $this->authorize('recordFeePayment', $organisation);

        if ($member->organisation_id !== $organisation->id) {
            abort(404);
        }

        $validated = $request->validate([
            'fee_id' => 'required|uuid|exists:membership_fees,id',
            'payment_method' => 'required|string|in:bank_transfer,cash,card',
            'transaction_reference' => 'nullable|string|max:200',
        ]);

        $tenantId = TenantId::fromOrganisationId($organisation->id);

        try {
            $fee = MembershipFee::where('id', $validated['fee_id'])
                ->where('organisation_id', $organisation->id)
                ->firstOrFail();

            $command = new RecordFeePaymentCommand(
                feeId: FeeId::fromString($validated['fee_id']),
                tenantId: $tenantId,
                paymentMethod: $validated['payment_method'],
                paidAt: new DateTimeImmutable(),
                transactionReference: $validated['transaction_reference'] ?? null,
                recordedByUserId: auth()->id(),
            );
            $recordFeePayment->execute($command);
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    /**
     * Suspend a member.
     * Phase 3D: Routes through SuspendMember use case
     */
    public function suspend(
        Organisation $organisation,
        LegacyMember $member,
        SuspendMember $suspendMember,
        Request $request
    ): RedirectResponse {
        $this->authorize('manageMembers', $organisation);

        if ($member->organisation_id !== $organisation->id) {
            abort(404);
        }

        $tenantId = TenantId::fromOrganisationId($organisation->id);

        try {
            $suspendMember->execute(
                MemberId::fromString($member->id),
                $tenantId,
                $request->input('reason', 'Suspended by administrator')
            );
            return back()->with('success', 'Member suspended successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to suspend member: ' . $e->getMessage());
        }
    }

    /**
     * Archive a member (terminal state).
     * Phase 3D: Routes through ArchiveMember use case
     */
    public function archive(
        Organisation $organisation,
        LegacyMember $member,
        ArchiveMember $archiveMember
    ): RedirectResponse {
        $this->authorize('manageMembers', $organisation);

        if ($member->organisation_id !== $organisation->id) {
            abort(404);
        }

        $tenantId = TenantId::fromOrganisationId($organisation->id);

        try {
            $archiveMember->execute(
                MemberId::fromString($member->id),
                $tenantId
            );
            return back()->with('success', 'Member archived successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to archive member: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard statistics.
     */
    private function getStats(Organisation $organisation): array
    {
        return [
            'total_members'  => LegacyMember::where('organisation_id', $organisation->id)
                                    ->where('status', 'active')->count(),
            'expired_count'  => LegacyMember::where('organisation_id', $organisation->id)
                                    ->where('status', 'expired')->count(),
            'pending_fees'   => (float) MembershipFee::where('organisation_id', $organisation->id)
                                    ->where('status', 'pending')->sum('amount'),
        ];
    }

    /**
     * Extract full name from personal_info JSON
     */
    private function getNameFromPersonalInfo(?string $personalInfo): ?string
    {
        if (!$personalInfo) {
            return null;
        }
        $data = json_decode($personalInfo, true);
        return $data['fullName'] ?? null;
    }

    /**
     * Extract email from personal_info JSON
     */
    private function getEmailFromPersonalInfo(?string $personalInfo): ?string
    {
        if (!$personalInfo) {
            return null;
        }
        $data = json_decode($personalInfo, true);
        return $data['email'] ?? null;
    }
}
