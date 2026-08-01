# 🏛️ **ELECTION OFFICER CONCEPT & ARCHITECTURE**

## **What is an Election Officer?**

An Election Officer (also called Election Commissioner, Returning Officer, or Election Administrator) is a **trusted person** responsible for overseeing the integrity and smooth execution of elections within an organisation.

---

## 🎯 **CORE RESPONSIBILITIES**

| Responsibility | Description |
|----------------|-------------|
| **Election Setup** | Create elections, define positions, set dates |
| **Voter Management** | Approve voter lists, handle disputes |
| **Candidate Management** | Verify candidate nominations |
| **Monitoring** | Oversee voting process, ensure fairness |
| **Results** | Count votes, certify results, handle appeals |
| **Compliance** | Ensure adherence to organisation bylaws and legal requirements (§26 BGB) |

---

## 👥 **HOW MANY ELECTION OFFICERS?**

### **Typical Models:**

| Model | Description | Best For |
|-------|-------------|----------|
| **Single Officer** | One person handles all duties | Small organisations (<100 members) |
| **Commission (3-5)** | Committee of officers with chair | Medium to large organisations |
| **Chief + Deputies** | One chief with 2-3 deputies | Organisations with multiple elections |

### **Legal Requirement (§26 BGB):**
- At least **one** election officer must be appointed
- Officer cannot be a candidate in the election they oversee
- Should be impartial/neutral

---

## 📋 **APPOINTMENT PROCESS**

### **Step 1: Nomination**
```php
// Who can nominate?
- Board members
- Current election officer (if exists)
- Organisation members (with petition)
```

### **Step 2: Approval**
```php
// Who approves?
- Board vote (simple majority)
- Member assembly vote
- Previous election officer handover
```

### **Step 3: Acceptance**
- Officer must formally accept
- Declaration of impartiality
- Conflict of interest disclosure

### **Step 4: Term Limits**
```php
// Common patterns:
- Single election term
- Fixed term (e.g., 2 years)
- Renewable with limits
```

---

## 🔑 **RIGHTS & PERMISSIONS**

### **Election Officer Dashboard Access**
```php
// Permissions needed:
- View all election data (but not voter identities)
- Manage election settings
- View real-time results (if allowed)
- Access audit logs
- Cannot vote (maintains impartiality)
```

### **Database Schema**
```sql
CREATE TABLE election_officers (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,
    user_id UUID NOT NULL,
    election_id UUID NULL, -- NULL means all elections in org
    role ENUM('chief', 'deputy', 'commissioner') DEFAULT 'commissioner',
    status ENUM('active', 'inactive', 'resigned') DEFAULT 'active',
    appointed_at TIMESTAMP NOT NULL,
    appointed_by UUID NOT NULL, -- user_id who appointed them
    term_ends_at TIMESTAMP NULL,
    can_manage_voters BOOLEAN DEFAULT true,
    can_manage_candidates BOOLEAN DEFAULT true,
    can_certify_results BOOLEAN DEFAULT true,
    can_view_audit_logs BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (appointed_by) REFERENCES users(id),
    
    UNIQUE KEY unique_officer_per_election (user_id, election_id)
);
```

---

## 🛡️ **SECURITY CONSTRAINTS**

### **Rule 1: Cannot be a Candidate**
```php
// Check when appointing
if ($officer->isCandidateInElection($election)) {
    throw new Exception('Election officer cannot be a candidate');
}
```

### **Rule 2: Cannot Vote in Their Election**
```php
// In voting eligibility check
if ($user->isElectionOfficerFor($election)) {
    return false; // Officers don't vote in elections they oversee
}
```

### **Rule 3: Multiple Officers for Oversight**
```php
// At least 2 officers for critical operations
if ($action === 'certify_results' && $activeOfficers < 2) {
    throw new Exception('Need at least 2 officers to certify results');
}
```

---

## 🏗️ **PROPOSED ARCHITECTURE**

```
┌─────────────────────────────────────────────────────────────────┐
│                    ELECTION OFFICER SYSTEM                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                   APPOINTMENT FLOW                         │  │
│  ├───────────────────────────────────────────────────────────┤  │
│  │  1. Nomination → 2. Approval → 3. Acceptance → 4. Active  │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                   │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                   PERMISSION MATRIX                        │  │
│  ├───────────────┬───────────────┬───────────────┬───────────┤  │
│  │    Action     │   Chief       │   Deputy      │Commissioner│  │
│  ├───────────────┼───────────────┼───────────────┼───────────┤  │
│  │ Create Election│ ✅ Yes        │ ✅ Yes        │ ❌ No     │  │
│  │ Manage Voters  │ ✅ Yes        │ ✅ Yes        │ ✅ Yes    │  │
│  │ Manage Cand.   │ ✅ Yes        │ ✅ Yes        │ ✅ Yes    │  │
│  │ Certify Results│ ✅ Yes        │ ⚠️ With Chief  │ ❌ No     │  │
│  │ View Audit Log │ ✅ Yes        │ ✅ Yes        │ ✅ Yes    │  │
│  │ Override Vote  │ ❌ No         │ ❌ No         │ ❌ No     │  │
│  └───────────────┴───────────────┴───────────────┴───────────┘  │
│                                                                   │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                    AUDIT TRAIL                             │  │
│  ├───────────────────────────────────────────────────────────┤  │
│  │  - Every action logged with officer ID                     │  │
│  │  - Cannot be deleted, only superseded                      │  │
│  │  - Full history for legal compliance (§26 BGB)            │  │
│  └───────────────────────────────────────────────────────────┘  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📝 **IMPLEMENTATION STEPS**

### **1. Create Migration**
```bash
php artisan make:migration create_election_officers_table
```

### **2. Create Model**
```php
class ElectionOfficer extends Model
{
    use HasUuids;
    
    public function user() { return $this->belongsTo(User::class); }
    public function organisation() { return $this->belongsTo(Organisation::class); }
    public function election() { return $this->belongsTo(Election::class); }
    public function appointer() { return $this->belongsTo(User::class, 'appointed_by'); }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('term_ends_at')
                  ->orWhere('term_ends_at', '>', now());
            });
    }
    
    public function canManageElection(Election $election): bool
    {
        if ($this->election_id && $this->election_id !== $election->id) {
            return false;
        }
        return $this->status === 'active';
    }
}
```

### **3. Create Policy**
```php
class ElectionOfficerPolicy
{
    public function appoint(User $user, Organisation $organisation)
    {
        return $user->isAdminOf($organisation) 
            || $user->isBoardMemberOf($organisation);
    }
    
    public function manageElection(User $user, Election $election)
    {
        return $user->electionOfficers()
            ->where('organisation_id', $election->organisation_id)
            ->where('status', 'active')
            ->exists();
    }
}
```

### **4. Add UI Components**
- Appointment modal
- Officer list view
- Permission management
- Audit log viewer

---

## 🏁 **CONCLUSION**

The Election Officer system provides:
- ✅ **Legal compliance** with §26 BGB
- ✅ **Separation of duties** (voters vs officers)
- ✅ **Audit trail** for transparency
- ✅ **Flexible roles** for different organisation sizes
- ✅ **Security** through permission matrix

**This is the natural next step after your Quick Actions redesign!** 🚀
# 📊 **MERMAID PROCESS: Election Officer Appointment & Rights Assignment**

```mermaid
graph TD
    %% Styles
    classDef startEnd fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef process fill:#fff3e0,stroke:#ff6f00,stroke-width:2px
    classDef decision fill:#ffebee,stroke:#c62828,stroke-width:2px
    classDef database fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef rights fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px

    %% Start
    Start((Start)):::startEnd

    %% Step 1: Initiation
    Start --> CheckPerms{User has permission<br/>to appoint?}:::decision
    CheckPerms -->|No| AccessDenied[Access Denied]:::process
    AccessDenied --> End((End)):::startEnd

    CheckPerms -->|Yes| Initiate[Initiate Officer Appointment]:::process

    %% Step 2: Candidate Selection
    Initiate --> SelectCandidate[Select Candidate from<br/>Organisation Members]:::process
    SelectCandidate --> ValidateCandidate{Validate Candidate}:::decision

    ValidateCandidate -->|Not a member| Invalid[Invalid Candidate]:::process
    Invalid --> SelectCandidate

    ValidateCandidate -->|Is already officer| AlreadyOfficer[Candidate Already an Officer]:::process
    AlreadyOfficer --> SelectCandidate

    ValidateCandidate -->|Is candidate in election| IsCandidate[Candidate Running in Election]:::process
    IsCandidate --> SelectCandidate

    ValidateCandidate -->|Valid| Nominate[Nominate Candidate]:::process

    %% Step 3: Store Nomination
    Nominate --> StoreNom[Store Nomination Record]:::database
    StoreNom --> NotifyCandidate[Notify Candidate]:::process

    %% Step 4: Candidate Acceptance
    NotifyCandidate --> AcceptDecline{Candidate Accepts?}:::decision
    AcceptDecline -->|Decline| Decline[Record Decline]:::database
    Decline --> SelectCandidate

    AcceptDecline -->|Accept| Acceptance[Record Acceptance<br/>with Declaration]:::database

    %% Step 5: Approval Workflow
    Acceptance --> ApprovalRequired{Approval Required?}:::decision

    ApprovalRequired -->|By Board| BoardApproval[Board Approval Process]:::process
    BoardApproval --> BoardVote{Board Votes}:::decision
    BoardVote -->|Reject| Reject[Record Rejection]:::database
    Reject --> NotifyReject[Notify Candidate]:::process
    NotifyReject --> SelectCandidate

    BoardVote -->|Approve| Approve[Record Approval]:::database

    ApprovalRequired -->|By Assembly| AssemblyApproval[Member Assembly Approval]:::process
    AssemblyApproval --> AssemblyVote{Assembly Votes}:::decision
    AssemblyVote -->|Reject| Reject
    AssemblyVote -->|Approve| Approve

    ApprovalRequired -->|Direct Appointment| DirectApprove[Direct Appointment<br/>by Authorised Role]:::process
    DirectApprove --> Approve

    %% Step 6: Determine Officer Rights
    Approve --> DetermineRights{Determine Officer Rights}:::decision

    DetermineRights -->|Chief Election Officer| ChiefRights[Assign Chief Rights]:::rights
    DetermineRights -->|Deputy Officer| DeputyRights[Assign Deputy Rights]:::rights
    DetermineRights -->|Commission Member| CommissionRights[Assign Commissioner Rights]:::rights

    %% Step 7: Rights Assignment Details
    subgraph ChiefRightsDetails [Chief Election Officer Rights]
        ChiefRights1[✓ Create/Manage Elections]:::rights
        ChiefRights2[✓ Manage All Voters]:::rights
        ChiefRights3[✓ Manage All Candidates]:::rights
        ChiefRights4[✓ Certify Final Results]:::rights
        ChiefRights5[✓ View Full Audit Logs]:::rights
        ChiefRights6[✓ Appoint Deputy Officers]:::rights
        ChiefRights7[✗ Cannot Vote in Election]:::rights
        ChiefRights8[✗ Cannot Override Votes]:::rights
    end

    subgraph DeputyRightsDetails [Deputy Election Officer Rights]
        DeputyRights1[✓ Create Elections]:::rights
        DeputyRights2[✓ Manage Voters]:::rights
        DeputyRights3[✓ Manage Candidates]:::rights
        DeputyRights4[✓ View Audit Logs]:::rights
        DeputyRights5[⚠ Certify Results (with Chief)]:::rights
        DeputyRights6[✗ Cannot Appoint Officers]:::rights
        DeputyRights7[✗ Cannot Vote]:::rights
    end

    subgraph CommissionRightsDetails [Commission Member Rights]
        CommissionRights1[✓ Manage Assigned Voters]:::rights
        CommissionRights2[✓ Manage Assigned Candidates]:::rights
        CommissionRights3[✓ Monitor Voting Process]:::rights
        CommissionRights4[✗ Cannot Create Elections]:::rights
        CommissionRights5[✗ Cannot Certify Results]:::rights
        CommissionRights6[✗ Cannot Vote]:::rights
    end

    ChiefRights --> ChiefRights1
    ChiefRights1 --> ChiefRights2
    ChiefRights2 --> ChiefRights3
    ChiefRights3 --> ChiefRights4
    ChiefRights4 --> ChiefRights5
    ChiefRights5 --> ChiefRights6
    ChiefRights6 --> ChiefRights7
    ChiefRights7 --> ChiefRights8

    DeputyRights --> DeputyRights1
    DeputyRights1 --> DeputyRights2
    DeputyRights2 --> DeputyRights3
    DeputyRights3 --> DeputyRights4
    DeputyRights4 --> DeputyRights5
    DeputyRights5 --> DeputyRights6
    DeputyRights6 --> DeputyRights7

    CommissionRights --> CommissionRights1
    CommissionRights1 --> CommissionRights2
    CommissionRights2 --> CommissionRights3
    CommissionRights3 --> CommissionRights4
    CommissionRights4 --> CommissionRights5
    CommissionRights5 --> CommissionRights6

    %% Step 8: Store Officer Record
    ChiefRights8 --> StoreOfficer[Store Officer Record in Database]:::database
    DeputyRights7 --> StoreOfficer
    CommissionRights6 --> StoreOfficer

    %% Step 9: Notifications & Completion
    StoreOfficer --> NotifyAppointed[Notify Candidate of Appointment]:::process
    NotifyAppointed --> LogAudit[Log to Audit Trail]:::database
    LogAudit --> End((Appointment Complete)):::startEnd
```

---

## 📋 **APPOINTMENT PROCESS FLOW (Text Summary)**

### **Phase 1: Initiation & Validation**
1. User with proper permissions initiates appointment
2. Candidate selected from organisation members
3. Validation checks:
   - Is member of organisation?
   - Already an officer?
   - Running as candidate in election?

### **Phase 2: Nomination & Acceptance**
4. Nomination record created
5. Candidate notified and must accept
6. Declaration of impartiality signed

### **Phase 3: Approval (Varies by Organisation)**
| Method | Process |
|--------|---------|
| **Board Approval** | Board votes on appointment |
| **Assembly Approval** | Member assembly votes |
| **Direct Appointment** | Authorised role appoints directly |

### **Phase 4: Rights Assignment**

#### **Chief Election Officer**
```
✓ Create/Manage Elections
✓ Manage All Voters
✓ Manage All Candidates  
✓ Certify Final Results
✓ View Full Audit Logs
✓ Appoint Deputy Officers
✗ Cannot Vote in Election
✗ Cannot Override Votes
```

#### **Deputy Election Officer**
```
✓ Create Elections
✓ Manage Voters
✓ Manage Candidates
✓ View Audit Logs
⚠ Certify Results (with Chief)
✗ Cannot Appoint Officers
✗ Cannot Vote
```

#### **Commission Member**
```
✓ Manage Assigned Voters
✓ Manage Assigned Candidates
✓ Monitor Voting Process
✗ Cannot Create Elections
✗ Cannot Certify Results
✗ Cannot Vote
```

### **Phase 5: Finalisation**
5. Officer record stored in database
6. Candidate notified of appointment
7. Audit trail updated
8. Process complete

---

## 🏁 **KEY SECURITY RULES**

```sql
-- Enforced by database
- One officer record per user per election
- Cannot be both officer and candidate
- Cannot vote in election they oversee
- All actions logged for audit
- Term limits enforced
```

**This process ensures legal compliance (§26 BGB) while maintaining election integrity!** 🚀
# 🏛️ **ELECTION OFFICER IMPLEMENTATION GUIDE**

## **Complete Implementation Roadmap**

---

## 📦 **PHASE 1: DATABASE LAYER**

### **1.1 Create Migration**
```bash
php artisan make:migration create_election_officers_table
```

```php
<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_election_officers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('election_officers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('election_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('appointed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->enum('role', ['chief', 'deputy', 'commissioner'])->default('commissioner');
            $table->enum('status', ['pending', 'active', 'inactive', 'resigned'])->default('pending');
            
            $table->timestamp('appointed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('term_starts_at')->nullable();
            $table->timestamp('term_ends_at')->nullable();
            
            // Permission flags
            $table->boolean('can_manage_elections')->default(true);
            $table->boolean('can_manage_voters')->default(true);
            $table->boolean('can_manage_candidates')->default(true);
            $table->boolean('can_certify_results')->default(false);
            $table->boolean('can_view_audit_logs')->default(true);
            $table->boolean('can_appoint_officers')->default(false);
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Constraints
            $table->unique(['user_id', 'election_id'], 'unique_officer_per_election');
            $table->index(['organisation_id', 'status']);
            $table->index(['election_id', 'role']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('election_officers');
    }
};
```

### **1.2 Create Model**
```bash
php artisan make:model ElectionOfficer
```

```php
<?php
// app/Models/ElectionOfficer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class ElectionOfficer extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'election_id',
        'appointed_by',
        'role',
        'status',
        'appointed_at',
        'accepted_at',
        'term_starts_at',
        'term_ends_at',
        'can_manage_elections',
        'can_manage_voters',
        'can_manage_candidates',
        'can_certify_results',
        'can_view_audit_logs',
        'can_appoint_officers',
        'metadata',
    ];

    protected $casts = [
        'appointed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'term_starts_at' => 'datetime',
        'term_ends_at' => 'datetime',
        'can_manage_elections' => 'boolean',
        'can_manage_voters' => 'boolean',
        'can_manage_candidates' => 'boolean',
        'can_certify_results' => 'boolean',
        'can_view_audit_logs' => 'boolean',
        'can_appoint_officers' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function appointer()
    {
        return $this->belongsTo(User::class, 'appointed_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('term_ends_at')
                    ->orWhere('term_ends_at', '>', now());
            });
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where(function ($q) use ($electionId) {
            $q->where('election_id', $electionId)
                ->orWhereNull('election_id');
        });
    }

    public function scopeChief($query)
    {
        return $query->where('role', 'chief');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && (!$this->term_ends_at || $this->term_ends_at->isFuture());
    }

    public function canManageElection(Election $election): bool
    {
        if (!$this->isActive()) return false;
        
        if ($this->election_id && $this->election_id !== $election->id) {
            return false;
        }
        
        return $this->can_manage_elections;
    }

    public function accept(User $user): void
    {
        $this->update([
            'status' => 'active',
            'accepted_at' => now(),
            'term_starts_at' => now(),
        ]);
        
        // Clear cache
        Cache::forget("org.{$this->organisation_id}.officers");
        Cache::forget("election.{$this->election_id}.officers");
    }
}
```

---

## 🎨 **PHASE 2: USER MODEL UPDATES**

```php
// app/Models/User.php - Add these relationships

public function electionOfficers()
{
    return $this->hasMany(ElectionOfficer::class);
}

public function appointedOfficers()
{
    return $this->hasMany(ElectionOfficer::class, 'appointed_by');
}

public function isElectionOfficerFor($electionId = null)
{
    return $this->electionOfficers()
        ->active()
        ->when($electionId, function ($q) use ($electionId) {
            $q->where(function ($sub) use ($electionId) {
                $sub->where('election_id', $electionId)
                    ->orWhereNull('election_id');
            });
        })
        ->exists();
}

public function getElectionOfficerRole($electionId = null)
{
    $officer = $this->electionOfficers()
        ->active()
        ->when($electionId, function ($q) use ($electionId) {
            $q->where(function ($sub) use ($electionId) {
                $sub->where('election_id', $electionId)
                    ->orWhereNull('election_id');
            });
        })
        ->first();
        
    return $officer?->role;
}
```

---

## 🛡️ **PHASE 3: POLICIES**

```bash
php artisan make:policy ElectionOfficerPolicy --model=ElectionOfficer
```

```php
<?php
// app/Policies/ElectionOfficerPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\ElectionOfficer;
use App\Models\Organisation;

class ElectionOfficerPolicy
{
    public function viewAny(User $user, Organisation $organisation): bool
    {
        return $user->organisations()
            ->where('organisation_id', $organisation->id)
            ->exists();
    }

    public function appoint(User $user, Organisation $organisation): bool
    {
        // Who can appoint officers?
        return $user->isAdminOf($organisation)
            || $user->isBoardMemberOf($organisation)
            || $user->electionOfficers()
                ->where('organisation_id', $organisation->id)
                ->where('role', 'chief')
                ->where('status', 'active')
                ->exists();
    }

    public function manage(User $user, ElectionOfficer $officer): bool
    {
        // Can manage this officer (edit/remove)
        if ($user->isAdminOf($officer->organisation)) {
            return true;
        }
        
        // Chief officers can manage others
        return $user->electionOfficers()
            ->where('organisation_id', $officer->organisation_id)
            ->where('role', 'chief')
            ->where('status', 'active')
            ->exists();
    }
}
```

Register in `AuthServiceProvider.php`:
```php
protected $policies = [
    ElectionOfficer::class => ElectionOfficerPolicy::class,
];
```

---

## 🚦 **PHASE 4: CONTROLLERS**

```bash
php artisan make:controller ElectionOfficerController
```

```php
<?php
// app/Http/Controllers/ElectionOfficerController.php

namespace App\Http\Controllers;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ElectionOfficerController extends Controller
{
    public function index(Organisation $organisation)
    {
        $this->authorize('viewAny', [ElectionOfficer::class, $organisation]);
        
        $officers = ElectionOfficer::where('organisation_id', $organisation->id)
            ->with(['user', 'appointer', 'election'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return Inertia::render('Organisations/ElectionOfficers/Index', [
            'organisation' => $organisation,
            'officers' => $officers,
            'stats' => [
                'total' => ElectionOfficer::where('organisation_id', $organisation->id)->count(),
                'active' => ElectionOfficer::where('organisation_id', $organisation->id)->active()->count(),
                'chief' => ElectionOfficer::where('organisation_id', $organisation->id)->chief()->count(),
            ]
        ]);
    }
    
    public function create(Organisation $organisation)
    {
        $this->authorize('appoint', [ElectionOfficer::class, $organisation]);
        
        $members = $organisation->users()
            ->wherePivotNotIn('user_id', function ($query) use ($organisation) {
                $query->select('user_id')
                    ->from('election_officers')
                    ->where('organisation_id', $organisation->id)
                    ->whereIn('status', ['active', 'pending']);
            })
            ->get(['users.id', 'users.name', 'users.email']);
            
        return Inertia::render('Organisations/ElectionOfficers/Create', [
            'organisation' => $organisation,
            'members' => $members,
        ]);
    }
    
    public function store(Request $request, Organisation $organisation)
    {
        $this->authorize('appoint', [ElectionOfficer::class, $organisation]);
        
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($organisation) {
                    if (!$organisation->users()->where('users.id', $value)->exists()) {
                        $fail('User must be a member of this organisation.');
                    }
                },
            ],
            'role' => 'required|in:chief,deputy,commissioner',
            'election_id' => 'nullable|exists:elections,id',
            'term_ends_at' => 'nullable|date|after:today',
            'permissions' => 'array',
        ]);
        
        $officer = ElectionOfficer::create([
            'organisation_id' => $organisation->id,
            'user_id' => $validated['user_id'],
            'election_id' => $validated['election_id'] ?? null,
            'appointed_by' => auth()->id(),
            'role' => $validated['role'],
            'status' => 'pending',
            'appointed_at' => now(),
            'term_ends_at' => $validated['term_ends_at'] ?? null,
            'can_manage_elections' => $validated['permissions']['manage_elections'] ?? true,
            'can_manage_voters' => $validated['permissions']['manage_voters'] ?? true,
            'can_manage_candidates' => $validated['permissions']['manage_candidates'] ?? true,
            'can_certify_results' => $validated['role'] === 'chief',
            'can_view_audit_logs' => $validated['permissions']['view_audit_logs'] ?? true,
            'can_appoint_officers' => $validated['role'] === 'chief',
        ]);
        
        return redirect()->route('organisations.election-officers.index', $organisation->slug)
            ->with('success', 'Election officer appointed successfully. They must accept the appointment.');
    }
    
    public function accept(Request $request, Organisation $organisation, ElectionOfficer $officer)
    {
        $this->authorize('manage', $officer);
        
        if ($officer->user_id !== auth()->id()) {
            abort(403, 'You can only accept your own appointments.');
        }
        
        $officer->accept(auth()->user());
        
        return redirect()->route('organisations.election-officers.index', $organisation->slug)
            ->with('success', 'You have accepted the appointment.');
    }
    
    public function destroy(Organisation $organisation, ElectionOfficer $officer)
    {
        $this->authorize('manage', $officer);
        
        $officer->update(['status' => 'resigned']);
        $officer->delete();
        
        return redirect()->route('organisations.election-officers.index', $organisation->slug)
            ->with('success', 'Election officer removed.');
    }
}
```

---

## 🖥️ **PHASE 5: VUE COMPONENTS**

### **5.1 Officer List View**

```vue
<!-- resources/js/Pages/Organisations/ElectionOfficers/Index.vue -->
<template>
  <OrganisationLayout :organisation="organisation">
    <Head :title="`${organisation.name} - Election Officers`" />
    
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold text-gray-900">Election Officers</h1>
          <p class="text-sm text-gray-500 mt-1">
            Manage who can oversee elections in your organisation
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
          <Link
            v-if="canAppoint"
            :href="route('organisations.election-officers.create', organisation.slug)"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Appoint Officer
          </Link>
        </div>
      </div>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Total Officers</p>
          <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Active</p>
          <p class="text-2xl font-bold text-green-600">{{ stats.active }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Chief Officers</p>
          <p class="text-2xl font-bold text-purple-600">{{ stats.chief }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Pending Acceptance</p>
          <p class="text-2xl font-bold text-amber-600">{{ stats.pending || 0 }}</p>
        </div>
      </div>
      
      <!-- Officers Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Officer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Election</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Appointed By</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="officer in officers.data" :key="officer.id">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ officer.user.name }}</div>
                    <div class="text-sm text-gray-500">{{ officer.user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span :class="roleClass(officer.role)">{{ roleLabel(officer.role) }}</span>
              </td>
              <td class="px-6 py-4">
                <span :class="statusClass(officer.status)">{{ officer.status }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ officer.election?.name || 'All Elections' }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ officer.appointer?.name || 'System' }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                <span v-if="officer.term_ends_at">
                  Until {{ new Date(officer.term_ends_at).toLocaleDateString() }}
                </span>
                <span v-else>Indefinite</span>
              </td>
              <td class="px-6 py-4 text-right text-sm font-medium">
                <button
                  v-if="officer.status === 'pending' && officer.user_id === $page.props.auth.user.id"
                  @click="acceptAppointment(officer)"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Accept
                </button>
                <button
                  v-if="canManage(officer)"
                  @click="confirmRemove(officer)"
                  class="text-red-600 hover:text-red-900"
                >
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <Pagination :links="officers.links" class="mt-4" />
    </div>
  </OrganisationLayout>
</template>

<script>
import { Link, router } from '@inertiajs/vue3'
import OrganisationLayout from '@/Layouts/OrganisationLayout.vue'

export default {
  components: { OrganisationLayout, Link },
  
  props: {
    organisation: Object,
    officers: Object,
    stats: Object,
    canAppoint: Boolean,
  },
  
  methods: {
    roleClass(role) {
      return {
        'chief': 'px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800',
        'deputy': 'px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800',
        'commissioner': 'px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800',
      }[role]
    },
    
    roleLabel(role) {
      return {
        'chief': 'Chief Officer',
        'deputy': 'Deputy Officer',
        'commissioner': 'Commissioner',
      }[role]
    },
    
    statusClass(status) {
      return {
        'active': 'px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800',
        'pending': 'px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800',
        'inactive': 'px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600',
        'resigned': 'px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800',
      }[status]
    },
    
    canManage(officer) {
      return this.$page.props.auth.user.permissions?.manage_officers
    },
    
    acceptAppointment(officer) {
      router.post(
        route('organisations.election-officers.accept', {
          organisation: this.organisation.slug,
          officer: officer.id
        })
      )
    },
    
    confirmRemove(officer) {
      if (confirm(`Remove ${officer.user.name} as election officer?`)) {
        router.delete(
          route('organisations.election-officers.destroy', {
            organisation: this.organisation.slug,
            officer: officer.id
          })
        )
      }
    }
  }
}
</script>
```

### **5.2 Appointment Modal**

```vue
<!-- resources/js/Components/ElectionOfficers/AppointmentModal.vue -->
<template>
  <Modal :show="show" @close="$emit('close')">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Appoint Election Officer</h2>
      
      <form @submit.prevent="submit">
        <!-- Member Selection -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Select Member
          </label>
          <select
            v-model="form.user_id"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            required
          >
            <option value="">Choose a member...</option>
            <option v-for="member in members" :key="member.id" :value="member.id">
              {{ member.name }} ({{ member.email }})
            </option>
          </select>
        </div>
        
        <!-- Role Selection -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Role
          </label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input
                type="radio"
                v-model="form.role"
                value="chief"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              />
              <span class="ml-3">
                <span class="block text-sm font-medium text-gray-700">Chief Election Officer</span>
                <span class="block text-xs text-gray-500">Full permissions, can appoint others</span>
              </span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                v-model="form.role"
                value="deputy"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              />
              <span class="ml-3">
                <span class="block text-sm font-medium text-gray-700">Deputy Officer</span>
                <span class="block text-xs text-gray-500">Can manage elections, cannot certify alone</span>
              </span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                v-model="form.role"
                value="commissioner"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              />
              <span class="ml-3">
                <span class="block text-sm font-medium text-gray-700">Commission Member</span>
                <span class="block text-xs text-gray-500">Limited to assigned tasks</span>
              </span>
            </label>
          </div>
        </div>
        
        <!-- Election Scope -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Scope
          </label>
          <select
            v-model="form.election_id"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          >
            <option :value="null">All Elections (Organisation-wide)</option>
            <option v-for="election in elections" :key="election.id" :value="election.id">
              {{ election.name }}
            </option>
          </select>
        </div>
        
        <!-- Term End -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Term Ends (Optional)
          </label>
          <input
            type="date"
            v-model="form.term_ends_at"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            :min="new Date().toISOString().split('T')[0]"
          />
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="submitting"
            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
          >
            {{ submitting ? 'Appointing...' : 'Appoint Officer' }}
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>

<script>
import { router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

export default {
  components: { Modal },
  
  props: {
    show: Boolean,
    organisation: Object,
    members: Array,
    elections: Array,
  },
  
  data() {
    return {
      form: {
        user_id: '',
        role: 'commissioner',
        election_id: null,
        term_ends_at: '',
      },
      submitting: false,
    }
  },
  
  methods: {
    submit() {
      this.submitting = true
      router.post(
        route('organisations.election-officers.store', this.organisation.slug),
        this.form,
        {
          preserveScroll: true,
          onSuccess: () => {
            this.$emit('close')
            this.resetForm()
          },
          onFinish: () => {
            this.submitting = false
          }
        }
      )
    },
    
    resetForm() {
      this.form = {
        user_id: '',
        role: 'commissioner',
        election_id: null,
        term_ends_at: '',
      }
    }
  }
}
</script>
```

---

## 🧪 **PHASE 6: ROUTES**

```php
// routes/organisations.php - Add these routes

Route::prefix('/election-officers')
    ->name('organisations.election-officers.')
    ->group(function () {
        Route::get('/', [ElectionOfficerController::class, 'index'])
            ->name('index');
        Route::get('/create', [ElectionOfficerController::class, 'create'])
            ->name('create');
        Route::post('/', [ElectionOfficerController::class, 'store'])
            ->name('store');
        Route::post('/{officer}/accept', [ElectionOfficerController::class, 'accept'])
            ->name('accept');
        Route::delete('/{officer}', [ElectionOfficerController::class, 'destroy'])
            ->name('destroy');
    });
```

---

## 📋 **PHASE 7: QUICK ACTIONS UPDATE**

Update your `ActionButtons.vue` to link to the officer appointment flow:

```vue
<!-- In ActionButtons.vue - Update the Appoint Officer card -->
<Link
  :href="route('organisations.election-officers.create', organisation.slug)"
  class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border-2 border-gray-200 hover:border-amber-400"
>
  <!-- Card content -->
</Link>
```

---

## 🏁 **SUMMARY**

| Phase | Component | Status |
|-------|-----------|--------|
| 1 | Database Migration | ✅ Ready |
| 2 | Models | ✅ Ready |
| 3 | Policies | ✅ Ready |
| 4 | Controllers | ✅ Ready |
| 5 | Vue Components | ✅ Ready |
| 6 | Routes | ✅ Ready |
| 7 | Quick Actions | ✅ Ready |

**Total implementation time: ~4-6 hours** 🚀
