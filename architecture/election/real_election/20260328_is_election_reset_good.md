# 4-Eyes Principle: Secure Real Election Vote Reset Architecture

## Overview

The **4-eyes principle** (dual control) ensures that no single person can reset a vote. Two authorized individuals must approve and execute the reset operation, providing accountability and preventing abuse.

---

## 🏗 Architecture Design

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    4-EYES VOTE RESET WORKFLOW                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  STEP 1: REQUEST                                                            │
│  ┌─────────────────┐                                                        │
│  │ Admin A initiates│ → Creates reset request with reason                   │
│  │ reset request   │ → Requires password confirmation                       │
│  └────────┬────────┘                                                        │
│           ↓                                                                │
│  STEP 2: PENDING APPROVAL                                                   │
│  ┌─────────────────┐                                                        │
│  │ Reset Request   │ → Stored in vote_reset_requests table                 │
│  │ Status: PENDING │ → Notifies Admin B                                    │
│  └────────┬────────┘                                                        │
│           ↓                                                                │
│  STEP 3: SECOND APPROVAL                                                    │
│  ┌─────────────────┐                                                        │
│  │ Admin B reviews │ → Reviews request details                             │
│  │ and approves    │ → Requires 2FA or password confirmation               │
│  └────────┬────────┘                                                        │
│           ↓                                                                │
│  STEP 4: EXECUTION                                                          │
│  ┌─────────────────┐                                                        │
│  │ System executes │ → Only after 2 approvals                              │
│  │ vote reset      │ → Comprehensive audit logging                         │
│  └─────────────────┘                                                        │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 📊 Database Schema

### Migration: `create_vote_reset_requests_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_reset_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('election_id');
            $table->uuid('user_id');           // Voter whose vote is being reset
            $table->uuid('requested_by');       // Admin who requested
            $table->uuid('approved_by')->nullable();  // Admin who approved
            $table->text('reason');
            $table->string('status', 20)->default('pending'); // pending, approved, rejected, executed
            $table->text('requestor_notes')->nullable();
            $table->text('approver_notes')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('election_id')->references('id')->on('elections');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
            
            // Indexes
            $table->index(['election_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('requested_at');
        });
        
        // Add audit fields to votes table
        Schema::table('votes', function (Blueprint $table) {
            $table->uuid('reset_request_id')->nullable()->after('receipt_hash');
            $table->timestamp('reset_at')->nullable()->after('reset_request_id');
            $table->uuid('reset_by')->nullable()->after('reset_at');
            $table->text('reset_reason')->nullable()->after('reset_by');
            
            $table->foreign('reset_request_id')->references('id')->on('vote_reset_requests');
            $table->foreign('reset_by')->references('id')->on('users');
        });
        
        // Add to codes table to track vote reset state
        Schema::table('codes', function (Blueprint $table) {
            $table->boolean('is_reset')->default(false)->after('has_voted');
            $table->uuid('reset_request_id')->nullable()->after('is_reset');
            $table->timestamp('reset_at')->nullable()->after('reset_request_id');
            
            $table->foreign('reset_request_id')->references('id')->on('vote_reset_requests');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['reset_request_id']);
            $table->dropForeign(['reset_by']);
            $table->dropColumn(['reset_request_id', 'reset_at', 'reset_by', 'reset_reason']);
        });
        
        Schema::table('codes', function (Blueprint $table) {
            $table->dropForeign(['reset_request_id']);
            $table->dropColumn(['is_reset', 'reset_request_id', 'reset_at']);
        });
        
        Schema::dropIfExists('vote_reset_requests');
    }
};
```

---

## 🔧 Model: VoteResetRequest.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VoteResetRequest extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'election_id',
        'user_id',
        'requested_by',
        'approved_by',
        'reason',
        'status',
        'requestor_notes',
        'approver_notes',
        'requested_at',
        'approved_at',
        'executed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'executed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXECUTED = 'executed';

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // Helper methods
    public function approve(User $approver, ?string $notes = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        if ($approver->id === $this->requested_by) {
            return false; // Cannot approve own request
        }

        $this->update([
            'approved_by' => $approver->id,
            'status' => self::STATUS_APPROVED,
            'approver_notes' => $notes,
            'approved_at' => now(),
        ]);

        return true;
    }

    public function reject(User $approver, ?string $notes = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->update([
            'approved_by' => $approver->id,
            'status' => self::STATUS_REJECTED,
            'approver_notes' => $notes,
            'approved_at' => now(),
        ]);

        return true;
    }

    public function execute(): bool
    {
        if ($this->status !== self::STATUS_APPROVED) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_EXECUTED,
            'executed_at' => now(),
        ]);

        return true;
    }
}
```

---

## 🎮 Service: VoteResetService.php

```php
<?php

namespace App\Services;

use App\Models\VoteResetRequest;
use App\Models\Code;
use App\Models\Vote;
use App\Models\User;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VoteResetService
{
    /**
     * Create a vote reset request (First eye)
     */
    public function createRequest(
        Election $election,
        User $voter,
        User $requestor,
        string $reason,
        ?string $notes = null
    ): VoteResetRequest {
        // Verify requestor has permission
        if (!$this->canRequestReset($requestor, $election)) {
            throw new \Exception('You do not have permission to request a vote reset');
        }

        // Check if pending request already exists
        $existing = VoteResetRequest::where('election_id', $election->id)
            ->where('user_id', $voter->id)
            ->where('status', VoteResetRequest::STATUS_PENDING)
            ->first();

        if ($existing) {
            throw new \Exception('A pending reset request already exists for this voter');
        }

        // Verify voter has actually voted
        $code = Code::where('user_id', $voter->id)
            ->where('election_id', $election->id)
            ->first();

        if (!$code || !$code->has_voted) {
            throw new \Exception('This voter has not cast a vote yet');
        }

        DB::beginTransaction();
        try {
            $request = VoteResetRequest::create([
                'id' => (string) Str::uuid(),
                'election_id' => $election->id,
                'user_id' => $voter->id,
                'requested_by' => $requestor->id,
                'reason' => $reason,
                'requestor_notes' => $notes,
                'status' => VoteResetRequest::STATUS_PENDING,
                'requested_at' => now(),
            ]);

            Log::channel('voting_security')->info('VOTE RESET REQUEST CREATED', [
                'request_id' => $request->id,
                'election_id' => $election->id,
                'voter_id' => $voter->id,
                'requestor_id' => $requestor->id,
                'reason' => $reason,
                'ip' => request()->ip(),
            ]);

            DB::commit();
            return $request;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve a vote reset request (Second eye)
     */
    public function approveRequest(
        VoteResetRequest $request,
        User $approver,
        ?string $notes = null,
        bool $requires2FA = true
    ): bool {
        // Verify approver has permission
        if (!$this->canApproveReset($approver, $request->election)) {
            throw new \Exception('You do not have permission to approve vote resets');
        }

        // Cannot approve own request
        if ($approver->id === $request->requested_by) {
            throw new \Exception('You cannot approve your own reset request');
        }

        // 2FA verification (if required)
        if ($requires2FA && !$this->verifySecondFactor($approver)) {
            throw new \Exception('Two-factor authentication required');
        }

        DB::beginTransaction();
        try {
            $request->approve($approver, $notes);

            Log::channel('voting_security')->warning('VOTE RESET REQUEST APPROVED', [
                'request_id' => $request->id,
                'approver_id' => $approver->id,
                'approver_notes' => $notes,
                'ip' => request()->ip(),
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Execute the approved reset (System action)
     */
    public function executeReset(VoteResetRequest $request): bool
    {
        if ($request->status !== VoteResetRequest::STATUS_APPROVED) {
            throw new \Exception('Request must be approved before execution');
        }

        DB::beginTransaction();
        try {
            // Find the code and vote
            $code = Code::where('user_id', $request->user_id)
                ->where('election_id', $request->election_id)
                ->first();

            if (!$code || !$code->has_voted) {
                throw new \Exception('No vote found to reset');
            }

            // Find the original vote
            $originalVote = Vote::where('election_id', $request->election_id)
                ->where('id', $code->vote_id ?? '')
                ->first();

            // Mark the original vote as superseded
            if ($originalVote) {
                $originalVote->update([
                    'reset_request_id' => $request->id,
                    'reset_at' => now(),
                    'reset_by' => $request->approved_by,
                    'reset_reason' => $request->reason,
                ]);
            }

            // Reset the code for re-voting
            $code->update([
                'has_voted' => false,
                'can_vote_now' => 1,
                'vote_submitted' => false,
                'code_to_save_vote_used_at' => null,
                'is_code_to_save_vote_usable' => 1,
                'is_reset' => true,
                'reset_request_id' => $request->id,
                'reset_at' => now(),
            ]);

            // Mark request as executed
            $request->execute();

            Log::channel('voting_security')->critical('VOTE RESET EXECUTED', [
                'request_id' => $request->id,
                'election_id' => $request->election_id,
                'voter_id' => $request->user_id,
                'requestor_id' => $request->requested_by,
                'approver_id' => $request->approved_by,
                'reason' => $request->reason,
                'original_vote_id' => $originalVote?->id,
                'ip' => request()->ip(),
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('voting_security')->error('VOTE RESET EXECUTION FAILED', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Check if user can request a vote reset
     */
    private function canRequestReset(User $user, Election $election): bool
    {
        // Election chiefs can request
        if ($user->can('manageVotes', $election)) {
            return true;
        }
        
        // Election deputies can request
        if ($user->can('manageSettings', $election)) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if user can approve a vote reset
     */
    private function canApproveReset(User $user, Election $election): bool
    {
        // Only election chiefs can approve (higher authority)
        return $user->can('manageVotes', $election);
    }

    /**
     * Verify second factor (email/SMS/authenticator)
     */
    private function verifySecondFactor(User $user): bool
    {
        // Check if 2FA is enabled and verified in session
        $sessionKey = 'vote_reset_2fa_verified_' . $user->id;
        
        if (!session()->has($sessionKey)) {
            return false;
        }
        
        $verifiedAt = session($sessionKey);
        return now()->diffInMinutes($verifiedAt) < 5; // Valid for 5 minutes
    }

    /**
     * Generate 2FA code for approval
     */
    public function generate2FACode(User $user): string
    {
        $code = random_int(100000, 999999);
        session(['vote_reset_2fa_code_' . $user->id => $code]);
        session(['vote_reset_2fa_expires_' . $user->id => now()->addMinutes(10)]);
        
        // Send via email/SMS
        $user->notify(new VoteReset2FACode($code));
        
        return $code;
    }

    /**
     * Verify 2FA code
     */
    public function verify2FACode(User $user, string $code): bool
    {
        $storedCode = session('vote_reset_2fa_code_' . $user->id);
        $expires = session('vote_reset_2fa_expires_' . $user->id);
        
        if (!$storedCode || !$expires || now()->greaterThan($expires)) {
            return false;
        }
        
        if ((string) $storedCode !== (string) $code) {
            return false;
        }
        
        session(['vote_reset_2fa_verified_' . $user->id => now()]);
        
        return true;
    }
}
```

---

## 🎛️ Controller: VoteResetController.php

```php
<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use App\Models\VoteResetRequest;
use App\Services\VoteResetService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VoteResetController extends Controller
{
    protected VoteResetService $resetService;

    public function __construct(VoteResetService $resetService)
    {
        $this->resetService = $resetService;
    }

    /**
     * Show vote reset management page
     */
    public function index(Election $election)
    {
        $this->authorize('manageVotes', $election);

        $pendingRequests = VoteResetRequest::where('election_id', $election->id)
            ->where('status', VoteResetRequest::STATUS_PENDING)
            ->with(['voter', 'requestor'])
            ->latest('requested_at')
            ->get();

        $executedRequests = VoteResetRequest::where('election_id', $election->id)
            ->whereIn('status', [VoteResetRequest::STATUS_EXECUTED, VoteResetRequest::STATUS_REJECTED])
            ->with(['voter', 'requestor', 'approver'])
            ->latest('executed_at')
            ->limit(50)
            ->get();

        return Inertia::render('Election/VoteReset', [
            'election' => $election,
            'pendingRequests' => $pendingRequests,
            'executedRequests' => $executedRequests,
        ]);
    }

    /**
     * Create a reset request (First eye)
     */
    public function store(Request $request, Election $election)
    {
        $this->authorize('manageVotes', $election);

        $validated = $request->validate([
            'voter_id' => ['required', 'uuid', 'exists:users,id'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $voter = User::findOrFail($validated['voter_id']);

        try {
            $resetRequest = $this->resetService->createRequest(
                $election,
                $voter,
                auth()->user(),
                $validated['reason'],
                $validated['notes'] ?? null
            );

            return redirect()->back()->with('success', 'Reset request created. Waiting for second approval.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show approval form (Second eye)
     */
    public function showApproval(Election $election, VoteResetRequest $resetRequest)
    {
        $this->authorize('manageVotes', $election);

        if ($resetRequest->election_id !== $election->id) {
            abort(404);
        }

        if ($resetRequest->status !== VoteResetRequest::STATUS_PENDING) {
            return redirect()->route('elections.vote-reset', $election)
                ->with('error', 'This request has already been processed');
        }

        return Inertia::render('Election/VoteResetApproval', [
            'election' => $election,
            'request' => $resetRequest->load(['voter', 'requestor']),
        ]);
    }

    /**
     * Approve a reset request (Second eye with 2FA)
     */
    public function approve(Request $request, Election $election, VoteResetRequest $resetRequest)
    {
        $this->authorize('manageVotes', $election);

        $validated = $request->validate([
            '2fa_code' => ['required', 'string', 'size:6'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Verify 2FA
        if (!$this->resetService->verify2FACode(auth()->user(), $validated['2fa_code'])) {
            return redirect()->back()->with('error', 'Invalid or expired 2FA code');
        }

        try {
            $this->resetService->approveRequest(
                $resetRequest,
                auth()->user(),
                $validated['notes'] ?? null
            );

            return redirect()->route('elections.vote-reset', $election)
                ->with('success', 'Request approved. Ready for execution.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Execute an approved reset
     */
    public function execute(Request $request, Election $election, VoteResetRequest $resetRequest)
    {
        $this->authorize('manageVotes', $election);

        if ($resetRequest->status !== VoteResetRequest::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'Request must be approved before execution');
        }

        // Require confirmation
        $request->validate([
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $this->resetService->executeReset($resetRequest);

            return redirect()->route('elections.vote-reset', $election)
                ->with('success', 'Vote reset successfully. User can now vote again.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate 2FA code for approval
     */
    public function generate2FA(Request $request, Election $election)
    {
        $this->authorize('manageVotes', $election);

        try {
            $code = $this->resetService->generate2FACode(auth()->user());
            return response()->json(['success' => true, 'message' => '2FA code sent to your email']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
```

---

## 📝 Routes

```php
// In routes/election/electionRoutes.php

// Vote Reset Management (4-eyes)
Route::prefix('/elections/{election}/vote-reset')
    ->middleware(['auth', 'verified', 'can:manageVotes,election'])
    ->group(function () {
        Route::get('/', [VoteResetController::class, 'index'])->name('elections.vote-reset');
        Route::post('/', [VoteResetController::class, 'store'])->name('elections.vote-reset.store');
        
        Route::get('/{resetRequest}/approval', [VoteResetController::class, 'showApproval'])
            ->name('elections.vote-reset.approval');
        Route::post('/{resetRequest}/approve', [VoteResetController::class, 'approve'])
            ->name('elections.vote-reset.approve');
        Route::post('/{resetRequest}/execute', [VoteResetController::class, 'execute'])
            ->name('elections.vote-reset.execute');
        Route::post('/generate-2fa', [VoteResetController::class, 'generate2FA'])
            ->name('elections.vote-reset.generate-2fa');
    });
```

---

## 🔐 Security Features

| Feature | Implementation |
|---------|----------------|
| **Two-Person Approval** | Requestor and approver must be different users |
| **2FA for Approval** | Second eye requires 6-digit code sent via email |
| **Role Separation** | Only chiefs can approve (higher authority) |
| **Audit Trail** | All actions logged with IP, user, timestamp |
| **No Deletion** | Original votes marked as superseded, never deleted |
| **Time Limits** | 2FA codes expire in 10 minutes, approvals have no timeout |
| **Pending Request Prevention** | No duplicate pending requests per voter |

---

## 📊 Audit Log Example

```json
{
  "channel": "voting_security",
  "level": "CRITICAL",
  "message": "VOTE RESET EXECUTED",
  "context": {
    "request_id": "550e8400-e29b-41d4-a716-446655440000",
    "election_id": "abc-123",
    "voter_id": "user-456",
    "requestor_id": "admin-789",
    "approver_id": "chief-012",
    "reason": "Voter reported technical issue during voting",
    "original_vote_id": "vote-345",
    "ip": "192.168.1.100",
    "timestamp": "2026-03-28T14:30:00Z"
  }
}
```

---

## ✅ Summary

| Component | Purpose |
|-----------|---------|
| **vote_reset_requests** | Tracks the 2-stage approval workflow |
| **VoteResetService** | Encapsulates all reset logic with security checks |
| **2FA Requirement** | Adds second factor for approval step |
| **Audit Logging** | Complete trail for accountability |
| **No Deletion** | Preserves original votes for audit |

This architecture ensures that **no single person** can reset a vote without oversight, while providing a clear, auditable process for legitimate emergency situations.
# Vote Reset: Core Logic Explained

## What Does "Vote Reset" Mean?

A **vote reset** is the process of allowing a voter to vote **again** in a real election after they have already cast a vote. This is a **critical administrative action** used only in exceptional circumstances:

- Voter reported technical issues during voting
- Voter accidentally submitted incomplete selections
- System error prevented proper vote recording
- Legal requirement to allow re-voting (court order)
- Testing/QA in staging environments

---

## 🔧 Core Logic: What Actually Changes

### Before Reset
```
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE STATE                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  codes table:                                               │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ has_voted = true                                    │   │
│  │ can_vote_now = false                                │   │
│  │ vote_submitted = true                               │   │
│  │ is_code_to_save_vote_usable = false                 │   │
│  │ code_to_save_vote_used_at = 2026-03-28 10:00:00    │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  votes table:                                               │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ id = abc-123                                        │   │
│  │ candidate_01 = {"selected": ["candidate-x"]}       │   │
│  │ receipt_hash = "a1b2c3..."                         │   │
│  │ reset_at = null                                     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  VOTER CANNOT VOTE AGAIN ❌                                │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### After Reset
```
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE STATE                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  codes table:                                               │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ has_voted = false      ← CHANGED                    │   │
│  │ can_vote_now = true    ← CHANGED                    │   │
│  │ vote_submitted = false ← CHANGED                    │   │
│  │ is_code_to_save_vote_usable = true ← CHANGED        │   │
│  │ code_to_save_vote_used_at = null ← CHANGED          │   │
│  │ is_reset = true                                     │   │
│  │ reset_request_id = req-456                          │   │
│  │ reset_at = 2026-03-28 15:30:00                     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  votes table:                                               │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ id = abc-123 (ORIGINAL - PRESERVED)                 │   │
│  │ candidate_01 = {"selected": ["candidate-x"]}       │   │
│  │ receipt_hash = "a1b2c3..."                         │   │
│  │ reset_request_id = req-456 ← ADDED                  │   │
│  │ reset_at = 2026-03-28 15:30:00 ← ADDED             │   │
│  │ reset_by = chief-123 ← ADDED                       │   │
│  │ reset_reason = "Technical issue during voting"     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  VOTER CAN VOTE AGAIN ✅                                    │
│  ORIGINAL VOTE PRESERVED FOR AUDIT 🔍                       │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📝 Core Logic: Step by Step

### Step 1: Create Reset Request (First Eye)

```php
public function createRequest($election, $voter, $requestor, $reason)
{
    // 1. Validate permissions
    // 2. Check voter actually voted
    // 3. No pending request exists
    
    // 4. Create reset request record
    $request = VoteResetRequest::create([
        'election_id' => $election->id,
        'user_id' => $voter->id,
        'requested_by' => $requestor->id,
        'reason' => $reason,
        'status' => 'pending',
    ]);
    
    // 5. Log for audit
    Log::channel('voting_security')->info('RESET REQUEST CREATED', [...]);
}
```

### Step 2: Approve Request (Second Eye)

```php
public function approveRequest($request, $approver, $notes)
{
    // 1. Verify approver has higher authority (chief only)
    // 2. Cannot approve own request
    // 3. Verify 2FA code
    
    // 4. Update request status
    $request->update([
        'approved_by' => $approver->id,
        'status' => 'approved',
        'approver_notes' => $notes,
        'approved_at' => now(),
    ]);
    
    // 5. Log approval
    Log::channel('voting_security')->warning('RESET REQUEST APPROVED', [...]);
}
```

### Step 3: Execute Reset (System Action)

```php
public function executeReset($request)
{
    DB::beginTransaction();
    
    try {
        // 1. Find the voter's code record
        $code = Code::where('user_id', $request->user_id)
            ->where('election_id', $request->election_id)
            ->first();
        
        // 2. Find the original vote
        $originalVote = Vote::find($code->vote_id);
        
        // 3. MARK ORIGINAL VOTE AS SUPERSEDED (NOT DELETED!)
        if ($originalVote) {
            $originalVote->update([
                'reset_request_id' => $request->id,
                'reset_at' => now(),
                'reset_by' => $request->approved_by,
                'reset_reason' => $request->reason,
            ]);
        }
        
        // 4. RESET CODE FOR RE-VOTING
        $code->update([
            'has_voted' => false,           // Allow voting again
            'can_vote_now' => 1,            // Re-enable voting window
            'vote_submitted' => false,      // Reset submission flag
            'code_to_save_vote_used_at' => null,  // Clear second code usage
            'is_code_to_save_vote_usable' => 1,   // Make code usable again
            'is_reset' => true,             // Mark as reset
            'reset_request_id' => $request->id,
            'reset_at' => now(),
        ]);
        
        // 5. Mark request as executed
        $request->update([
            'status' => 'executed',
            'executed_at' => now(),
        ]);
        
        DB::commit();
        
        // 6. Log execution (CRITICAL level)
        Log::channel('voting_security')->critical('VOTE RESET EXECUTED', [
            'request_id' => $request->id,
            'voter_id' => $request->user_id,
            'original_vote_id' => $originalVote?->id,
            'reason' => $request->reason,
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

---

## 🎯 What Actually Changes vs What Stays

| Entity | Before Reset | After Reset | Action |
|--------|--------------|-------------|--------|
| **Code.has_voted** | `true` | `false` | ✅ RESET |
| **Code.can_vote_now** | `false` | `true` | ✅ RESET |
| **Code.vote_submitted** | `true` | `false` | ✅ RESET |
| **Code.code_to_save_vote_used_at** | `timestamp` | `null` | ✅ RESET |
| **Code.is_code_to_save_vote_usable** | `false` | `true` | ✅ RESET |
| **Code.is_reset** | `false` | `true` | ✅ NEW FIELD |
| **Vote.reset_at** | `null` | `timestamp` | ✅ NEW FIELD |
| **Vote.reset_by** | `null` | `admin_id` | ✅ NEW FIELD |
| **Vote.reset_reason** | `null` | `reason text` | ✅ NEW FIELD |
| **Vote.candidate_01...** | `selection data` | **UNCHANGED** | ❌ NOT DELETED |
| **Vote.receipt_hash** | `hash` | **UNCHANGED** | ❌ NOT DELETED |
| **Vote.id** | `uuid` | **UNCHANGED** | ❌ NOT DELETED |

---

## 🔍 Why This Matters: Audit Trail

After reset, you can trace:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         COMPLETE AUDIT TRAIL                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Request ID: req-456                                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │ Requested By: Admin A (requestor_id)                                │   │
│  │   Time: 2026-03-28 14:00:00                                        │   │
│  │   Reason: "Voter reported technical glitch during submission"      │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │ Approved By: Chief B (approver_id, must be different)               │   │
│  │   Time: 2026-03-28 14:30:00                                        │   │
│  │   2FA Verified: Yes                                                 │   │
│  │   Notes: "Confirmed with voter, reset authorized"                   │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │ Executed By: System                                                 │   │
│  │   Time: 2026-03-28 14:31:00                                        │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │ Original Vote: vote-abc-123                                         │   │
│  │   Selections: [President: John Doe, VP: Jane Smith]                │   │
│  │   Cast At: 2026-03-28 09:45:00                                     │   │
│  │   Reset Reason: "Technical issue"                                  │   │
│  │   Marked As: Superseded                                             │   │
│  ├─────────────────────────────────────────────────────────────────────┤   │
│  │ New Vote: vote-def-456 (after reset)                                │   │
│  │   Selections: [President: Jane Smith, VP: John Doe]                │   │
│  │   Cast At: 2026-03-28 15:00:00                                     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
│  🔍 TOTAL ACCOUNTABILITY: Who requested, who approved, why, when           │
│  📊 ORIGINAL VOTE PRESERVED: Can verify original vote if needed            │
│  ✅ VOTER CAN VOTE AGAIN: Fresh voting session started                      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 📋 Summary: Core Logic

| Operation | What Happens | What Doesn't Change |
|-----------|--------------|---------------------|
| **Create Request** | Creates `vote_reset_requests` record | Original vote data |
| **Approve Request** | Updates status, records approver | Original vote data |
| **Execute Reset** | Resets `codes` table flags | Original vote (marked superseded) |
| **User Votes Again** | Creates **NEW** vote record | Original vote (preserved) |

**Key Principle:** **NEVER DELETE ORIGINAL VOTES.** Always preserve them for audit, just mark them as superseded and allow new votes.

This ensures:
- ✅ Voter can vote again
- ✅ Complete audit trail
- ✅ No data loss
- ✅ Legal compliance
- ✅ 4-eyes principle enforced
# Vote Reset: Individual vs Election-Wide

## ✅ Answer: Individual Voter Only

**Vote reset is for a SINGLE VOTER, not the entire election.**

The core logic described resets **one specific voter's** ability to vote again, leaving all other voters unaffected.

---

## 🔍 Clarification: What Gets Reset

| Scope | What Changes | Other Voters |
|-------|--------------|--------------|
| **Individual Reset** | One voter's `codes` record flags | Unaffected ✅ |
| **Election-Wide Reset** | All voters' flags | All affected ❌ |

---

## 📊 Individual Reset (What We Implemented)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    INDIVIDUAL VOTE RESET                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  BEFORE:                                                                    │
│  ┌───────────────────────────────────────────────────────────────────┐    │
│  │ Voter A: has_voted = true  → CANNOT VOTE ❌                      │    │
│  │ Voter B: has_voted = true  → CANNOT VOTE ❌                      │    │
│  │ Voter C: has_voted = true  → CANNOT VOTE ❌                      │    │
│  └───────────────────────────────────────────────────────────────────┘    │
│                                                                             │
│  AFTER RESET FOR VOTER B ONLY:                                             │
│  ┌───────────────────────────────────────────────────────────────────┐    │
│  │ Voter A: has_voted = true  → CANNOT VOTE ❌ (unchanged)          │    │
│  │ Voter B: has_voted = false → CAN VOTE ✅ (RESET)                 │    │
│  │ Voter C: has_voted = true  → CANNOT VOTE ❌ (unchanged)          │    │
│  └───────────────────────────────────────────────────────────────────┘    │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🌍 Election-Wide Reset (Different Use Case)

If you need to reset **all voters** for an entire election (e.g., testing, re-run election), that's a **separate operation**:

```php
// Election-wide reset (NOT the same as individual reset)
public function resetElection(Election $election)
{
    // ⚠️ EXTREMELY DANGEROUS - Only for testing!
    
    Code::where('election_id', $election->id)
        ->update([
            'has_voted' => false,
            'can_vote_now' => 1,
            'vote_submitted' => false,
        ]);
    
    // Log as CRITICAL security event
    Log::channel('voting_security')->critical('ELECTION-WIDE RESET', [
        'election_id' => $election->id,
        'admin_id' => auth()->id(),
        'reason' => 'Test environment reset',
    ]);
}
```

---

## 🎯 Which One Does the 4-Eyes Architecture Support?

| Feature | Individual Reset | Election-Wide Reset |
|---------|------------------|---------------------|
| **4-Eyes Approval** | ✅ Yes | ⚠️ Would need separate process |
| **Audit Trail** | ✅ Per voter | ✅ Per election |
| **Use Case** | Fix individual voter issues | Testing, re-run election |
| **Risk Level** | Low | 🔴 High |
| **Implementation** | Our current design | Would need separate controller |

---

## 📝 Summary

**Our 4-eyes reset architecture is for:**

```
┌─────────────────────────────────────────────────────────────┐
│              INDIVIDUAL VOTER RESET                         │
│                                                             │
│  Purpose: Allow ONE specific voter to vote again           │
│  Example: Voter A had technical issue during voting        │
│  Effect: Only Voter A's record changes                     │
│  Other Voters: Unaffected, cannot vote again               │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**NOT for:**

```
┌─────────────────────────────────────────────────────────────┐
│              ELECTION-WIDE RESET                            │
│                                                             │
│  Purpose: Reset ALL voters for an election                  │
│  Example: Test environment, re-running election             │
│  Effect: Every voter can vote again                         │
│  This is a SEPARATE feature with higher security            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔧 If You Need Election-Wide Reset

If you need the ability to reset an entire election, add a separate command:

```bash
php artisan election:reset {election_id} --force
```

With stricter controls:
- Multiple admin approvals (3 or more)
- Requires physical meeting confirmation
- Full board approval record
- Only available in `.env` with `ALLOW_ELECTION_RESET=true`

**But for the current scope, the 4-eyes architecture correctly implements INDIVIDUAL voter reset.**
# Why Allow Single Person Vote Reset?

You're absolutely right to question this. Let me be direct: **In a properly functioning real election system, there is NO legitimate reason to reset a single person's vote.**

---

## 🔴 The Problem: Single Person Reset is a Security Risk

| Risk | Explanation |
|------|-------------|
| **Vote Integrity** | Allowing any admin to reset a single vote creates a backdoor to manipulate election results |
| **Audit Trail** | Even with 4-eyes, it's impossible to prove the reset was legitimate vs malicious |
| **Slippery Slope** | If one person gets a reset, why not others? Where does it stop? |
| **Legal Challenge** | Any contested election could claim "someone's vote was reset improperly" |
| **Trust Erosion** | Voters lose confidence if they know votes can be individually reset |

---

## ✅ The Correct Architecture: No Single Person Reset

### For Real Elections (Production)

```php
// ❌ NEVER ALLOW INDIVIDUAL RESET IN PRODUCTION
// The code we discussed should NOT exist in production

// ✅ Instead: Log everything, but NEVER allow reset
public function cannotResetIndividualVote() {
    // If a voter had a technical issue, the election administrator
    // should log the incident, but the vote stands.
    // There is no mechanism to let them vote again.
}
```

### What Happens When a Voter Has Issues?

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              REAL ELECTION: VOTER WITH TECHNICAL ISSUE                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Scenario: Voter reports error during voting                               │
│                                                                             │
│  ✅ CORRECT RESPONSE:                                                       │
│  1. Log the incident with details                                          │
│  2. Investigate the technical issue                                        │
│  3. Fix the system for FUTURE elections                                    │
│  4. Vote stands as recorded                                                │
│                                                                             │
│  ❌ INCORRECT RESPONSE:                                                     │
│  1. Reset the vote so they can try again ← THIS IS DANGEROUS               │
│                                                                             │
│  Why? Because you cannot verify:                                           │
│  - Did the vote actually fail?                                             │
│  - Was the error real or fabricated?                                       │
│  - What if they already voted successfully?                                │
│  - How do you prove the reset wasn't malicious?                            │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔧 What Should Exist Instead

### 1. Comprehensive Logging (Read-Only)

```php
// Log every vote attempt, success or failure
Log::channel('voting_audit')->info('VOTE_ATTEMPT', [
    'user_id' => $user->id,
    'election_id' => $election->id,
    'timestamp' => now(),
    'ip' => request()->ip(),
    'success' => $success,
    'error' => $error ?? null,
]);
```

### 2. Incident Tracking (Separate System)

```php
// Track voter issues WITHOUT resetting
class VoterIncident
{
    public function reportIssue(User $voter, Election $election, string $description)
    {
        // Record the issue for audit purposes
        // But NEVER modify the vote
        VoterIssueLog::create([
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'description' => $description,
            'reported_by' => auth()->id(),
            'reported_at' => now(),
        ]);
    }
}
```

### 3. Election-Wide Reset Only (For Testing/Staging)

```php
// Only allowed in non-production environments
public function resetElectionForTesting(Election $election)
{
    if (!app()->environment('local', 'staging')) {
        abort(403, 'This action is only available in test environments');
    }
    
    // Reset ALL votes for the election
    Code::where('election_id', $election->id)
        ->update(['has_voted' => false, 'can_vote_now' => true]);
}
```

---

## 📋 Summary: What You Should Actually Build

| Feature | Should Exist? | Purpose |
|---------|---------------|---------|
| **Individual Vote Reset** | ❌ NO | Security risk, creates manipulation vector |
| **Election-Wide Reset** | ✅ YES (test only) | For testing, staging environments |
| **Incident Logging** | ✅ YES | Track issues for audit |
| **Comprehensive Audit Logs** | ✅ YES | Prove system integrity |
| **4-Eyes for Election Reset** | ✅ YES | If resetting entire test election |

---

## 🎯 Final Recommendation

**Do NOT implement individual vote reset for real elections.**

Instead:

1. **Log Everything** - Every vote attempt, success, failure
2. **Investigate Issues** - When voters report problems, investigate system, not individual votes
3. **Fix Root Causes** - If there was a bug, fix it for future elections
4. **Document Incidents** - Track voter reports for transparency
5. **Election-Wide Reset** - Only for test/staging, never for production real elections

The 4-eyes architecture we designed is technically sound, but the **use case itself is problematic**. A real election system should never allow individual vote resets. If a voter had issues, the vote stands as recorded, and you improve the system for next time.

**This is the correct architectural decision for election integrity.**
# Election-Wide Reset: Critical Analysis

## ❌ NO - Election-Wide Reset is Even Worse Than Individual Reset

Let me be direct: **Resetting an entire election is NEVER acceptable in a real election system.**

---

## 🔴 Why Election-Wide Reset is Catastrophic

| Issue | Impact |
|-------|--------|
| **Destroys All Votes** | Every single vote cast is erased |
| **No Audit Trail** | Cannot prove what votes existed before reset |
| **Legal Liability** | Subject to criminal investigation for election tampering |
| **Trust Destruction** | Voters will never trust the system again |
| **Irreversible** | Once reset, original votes are gone forever |

---

## 📊 Comparison: Individual vs Election-Wide Reset

| Aspect | Individual Reset | Election-Wide Reset |
|--------|------------------|---------------------|
| **Risk Level** | High | 🔴 **CRITICAL** |
| **Votes Affected** | 1 person | **ALL VOTERS** |
| **Auditability** | Possible to track | **Impossible to recover** |
| **Legal Exposure** | Moderate | **Criminal level** |
| **Use Case** | None legitimate | **None** |

---

## ✅ What Should Exist Instead

### For Real Elections (Production)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              REAL ELECTION: NO RESET CAPABILITY                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  What happens when something goes wrong?                                   │
│                                                                             │
│  1. INDIVIDUAL VOTER ISSUE                                                 │
│     → Log incident                                                         │
│     → Vote stands as recorded                                              │
│     → Investigate root cause for future elections                          │
│                                                                             │
│  2. SYSTEM-WIDE FAILURE                                                    │
│     → Emergency election suspension                                        │
│     → Forensic audit of all votes                                          │
│     → Legal determination on validity                                      │
│     → NEVER reset and re-run                                               │
│                                                                             │
│  3. TEST ENVIRONMENT ONLY                                                  │
│     → Election-wide reset allowed ONLY with:                               │
│       - .env flag: ALLOW_ELECTION_RESET=true                               │
│       - App environment: local/staging                                     │
│       - Not in production                                                  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔧 The Correct Architecture

### 1. No Reset in Production (Period)

```php
// app/Console/Commands/ResetElection.php
class ResetElection extends Command
{
    public function handle()
    {
        // ❌ This command should NOT exist in production
        // If you need to test, use staging environment
        
        if (app()->environment('production')) {
            $this->error('❌ Election reset is DISABLED in production');
            return 1;
        }
        
        // Only for local/staging
        // ...
    }
}
```

### 2. Election Suspension (Not Reset)

```php
// ✅ CORRECT: Suspend election, preserve data
public function suspendElection(Election $election)
{
    // Mark election as suspended (not reset)
    $election->update([
        'status' => 'suspended',
        'suspended_at' => now(),
        'suspended_by' => auth()->id(),
        'suspension_reason' => 'System investigation',
    ]);
    
    // No votes are deleted
    // No one can vote further
    // Data preserved for audit
    
    Log::channel('voting_security')->critical('ELECTION SUSPENDED', [
        'election_id' => $election->id,
        'votes_cast' => Vote::where('election_id', $election->id)->count(),
        'reason' => 'System investigation',
    ]);
}
```

### 3. Forensic Audit Trail

```php
// ✅ CORRECT: Track everything, never delete
Schema::table('votes', function (Blueprint $table) {
    $table->timestamp('suspended_at')->nullable();
    $table->uuid('suspended_by')->nullable();
    $table->text('suspension_reason')->nullable();
    $table->boolean('is_validated')->default(true);
    $table->timestamp('validated_at')->nullable();
    $table->uuid('validated_by')->nullable();
});
```

---

## 📋 Summary: What You Should Build

| Feature | Real Election (Production) | Test/Staging |
|---------|---------------------------|--------------|
| **Individual Vote Reset** | ❌ NEVER | ❌ Not needed |
| **Election-Wide Reset** | ❌ NEVER | ⚠️ Only with .env flag, never in prod |
| **Election Suspension** | ✅ YES | ✅ YES |
| **Forensic Audit** | ✅ YES | ✅ YES |
| **Full Logging** | ✅ YES | ✅ YES |

---

## 🎯 Final Recommendation

### What NOT to Build:
- ❌ Individual vote reset
- ❌ Election-wide reset
- ❌ Any mechanism that modifies or deletes votes

### What TO Build:
- ✅ **Election Suspension** - Stop voting, preserve all data
- ✅ **Forensic Audit Trail** - Track every action
- ✅ **Incident Logging** - Record voter reports
- ✅ **Read-Only Archive** - Preserve votes permanently
- ✅ **Test Environment Reset** - Only for local/staging with clear warnings

### The Golden Rule:
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                             │
│   IN A REAL ELECTION, VOTES ARE PERMANENT.                                 │
│                                                                             │
│   No admin, no chief, no system should ever be able to delete              │
│   or modify a cast vote.                                                   │
│                                                                             │
│   The only acceptable actions are:                                         │
│   1. Log everything                                                        │
│   2. Suspend election (preserve data)                                      │
│   3. Investigate                                                           │
│   4. Fix for future elections                                              │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

**If you need to test voting flows, use the DEMO election system, not real elections.** The demo system already allows unlimited re-voting. That's exactly what it's for.
