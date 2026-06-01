After reviewing your comprehensive analysis, I can now identify the **precise starting point** for coding.

You have moved from abstract architecture to concrete design. Now let me identify what to build first, in what order, with exact code.

---

## The Critical Insight That Determines The Start Point

Your analysis identifies three capabilities as the first milestone:

1. `EvidencePackage`
2. `EvidenceRecord`
3. `EvidenceReconciliationService`

But these have dependencies:

```text
EvidenceRecord (no dependencies)
    ↓
EvidenceReconciliationService (depends on EvidenceRecord)
    ↓
EvidencePackage (depends on both)
```

Therefore:

> **Start with `EvidenceRecord`. Nothing else can exist without it.**

---

## The First Line of Code

Not a table migration. Not a service class.

**The domain event that EvidenceRecord will capture.**

Because EvidenceRecord is a **consumer** of domain events. If the events don't exist, EvidenceRecord has nothing to record.

### Step 1: Identify existing domain events

Review your current codebase for events like:

```php
// These should already exist or be created
VoteSubmitted::class
VoteAccepted::class
ElectionOpened::class
ElectionClosed::class
GovernanceDecisionRecorded::class
```

### Step 2: Create missing events (if any)

For an MVP election system, the minimal event set is:

```php
// app/Contexts/Election/Domain/Events/VoteSubmitted.php
namespace App\Contexts\Election\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;

class VoteSubmitted
{
    use Dispatchable;
    
    public function __construct(
        public readonly string $electionId,
        public readonly string $trackingCodeHash,
        public readonly string $submittedAt,
    ) {}
}
```

---

## The Exact Tables To Create

Based on your analysis, start with **three tables**, not more.

### Table 1: `evidence_records` (Primary)

```sql
-- database/migrations/2026_06_01_000001_create_evidence_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evidence_records', function (Blueprint $table) {
            $table->id();
            
            // Core identity
            $table->uuid('evidence_id')->unique();
            
            // Source tracking
            $table->string('source_context', 50); // 'election', 'governance', 'membership'
            $table->string('aggregate_type', 100); // 'Vote', 'Election', 'Committee'
            $table->string('aggregate_id', 100);   // UUID or identifier
            
            // Event information
            $table->string('event_type', 100);      // 'VoteSubmitted', 'ElectionClosed'
            $table->json('payload');                 // The actual evidence data
            
            // Integrity
            $table->string('integrity_hash', 64);   // SHA-256 of payload + metadata
            
            // Timing
            $table->timestamp('occurred_at');        // When the event happened
            $table->timestamps();                    // created_at, updated_at
            
            // Indexes for dispute resolution
            $table->index(['source_context', 'occurred_at']);
            $table->index(['aggregate_type', 'aggregate_id']);
            $table->index(['event_type', 'occurred_at']);
            $table->index('evidence_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('evidence_records');
    }
};
```

### Table 2: `evidence_lineage` (Chain of custody)

```sql
-- database/migrations/2026_06_01_000002_create_evidence_lineage_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('evidence_lineage', function (Blueprint $table) {
            $table->id();
            $table->uuid('evidence_id');
            $table->string('action', 50);        // 'created', 'observed', 'evaluated', 'consumed'
            $table->string('performed_by', 100);  // 'System', 'CommitteeChair#5', 'GovernanceContext'
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamp('performed_at');
            
            $table->index('evidence_id');
            $table->index('performed_by');
            $table->foreign('evidence_id')
                  ->references('evidence_id')
                  ->on('evidence_records')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('evidence_lineage');
    }
};
```

### Table 3: `reconciliation_reports` (The critical invariant)

```sql
-- database/migrations/2026_06_01_000003_create_reconciliation_reports_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('reconciliation_reports', function (Blueprint $table) {
            $table->id();
            $table->string('election_id', 100);
            $table->integer('accepted_votes');      // Votes that passed validation
            $table->integer('stored_votes');        // Votes persisted to database
            $table->integer('counted_votes');       // Votes included in final tally
            $table->string('status', 20);           // 'PASSED', 'FAILED', 'PENDING'
            $table->json('discrepancy_details')->nullable();
            $table->string('verified_by', 100)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index('election_id');
            $table->index('status');
            
            // The constitutional invariant
            $table->check('accepted_votes = stored_votes AND stored_votes = counted_votes');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('reconciliation_reports');
    }
};
```

---

## The First Classes To Create

### Class 1: EvidenceRecord Entity (Domain Layer)

```php
// app/Contexts/Evidence/Domain/Entities/EvidenceRecord.php

namespace App\Contexts\Evidence\Domain\Entities;

use App\Contexts\Evidence\Domain\ValueObjects\EvidenceId;
use App\Contexts\Evidence\Domain\ValueObjects\EvidenceHash;
use App\Contexts\Evidence\Domain\ValueObjects\EvidenceSource;
use Illuminate\Support\Facades\Hash;

class EvidenceRecord
{
    private EvidenceId $evidenceId;
    private EvidenceSource $sourceContext;
    private string $aggregateType;
    private string $aggregateId;
    private string $eventType;
    private array $payload;
    private EvidenceHash $integrityHash;
    private \DateTimeImmutable $occurredAt;
    
    private function __construct() {}
    
    public static function create(
        string $sourceContext,
        string $aggregateType,
        string $aggregateId,
        string $eventType,
        array $payload,
        \DateTimeImmutable $occurredAt
    ): self {
        $record = new self();
        $record->evidenceId = EvidenceId::generate();
        $record->sourceContext = new EvidenceSource($sourceContext);
        $record->aggregateType = $aggregateType;
        $record->aggregateId = $aggregateId;
        $record->eventType = $eventType;
        $record->payload = $payload;
        $record->occurredAt = $occurredAt;
        $record->integrityHash = $record->computeIntegrityHash();
        
        return $record;
    }
    
    private function computeIntegrityHash(): EvidenceHash
    {
        $data = json_encode([
            'evidence_id' => $this->evidenceId->toString(),
            'source_context' => $this->sourceContext->value(),
            'aggregate_type' => $this->aggregateType,
            'aggregate_id' => $this->aggregateId,
            'event_type' => $this->eventType,
            'payload' => $this->payload,
            'occurred_at' => $this->occurredAt->format('c'),
        ]);
        
        return new EvidenceHash(Hash::make($data));
    }
    
    // Getters
    public function evidenceId(): EvidenceId { return $this->evidenceId; }
    public function sourceContext(): EvidenceSource { return $this->sourceContext; }
    public function aggregateType(): string { return $this->aggregateType; }
    public function aggregateId(): string { return $this->aggregateId; }
    public function eventType(): string { return $this->eventType; }
    public function payload(): array { return $this->payload; }
    public function integrityHash(): EvidenceHash { return $this->integrityHash; }
    public function occurredAt(): \DateTimeImmutable { return $this->occurredAt; }
}
```

### Class 2: EvidenceRecordRepository (Infrastructure Layer)

```php
// app/Contexts/Evidence/Infrastructure/Persistence/EvidenceRecordRepository.php

namespace App\Contexts\Evidence\Infrastructure\Persistence;

use App\Contexts\Evidence\Domain\Entities\EvidenceRecord;
use App\Contexts\Evidence\Domain\Repositories\EvidenceRecordRepositoryInterface;
use App\Models\EvidenceRecord as EloquentEvidenceRecord;

class EvidenceRecordRepository implements EvidenceRecordRepositoryInterface
{
    public function save(EvidenceRecord $record): void
    {
        EloquentEvidenceRecord::create([
            'evidence_id' => $record->evidenceId()->toString(),
            'source_context' => $record->sourceContext()->value(),
            'aggregate_type' => $record->aggregateType(),
            'aggregate_id' => $record->aggregateId(),
            'event_type' => $record->eventType(),
            'payload' => json_encode($record->payload()),
            'integrity_hash' => $record->integrityHash()->value(),
            'occurred_at' => $record->occurredAt(),
        ]);
    }
    
    public function findByEvidenceId(string $evidenceId): ?EvidenceRecord
    {
        $model = EloquentEvidenceRecord::where('evidence_id', $evidenceId)->first();
        
        if (!$model) {
            return null;
        }
        
        return $this->toDomain($model);
    }
    
    public function findByAggregate(string $aggregateType, string $aggregateId): array
    {
        $models = EloquentEvidenceRecord::where('aggregate_type', $aggregateType)
            ->where('aggregate_id', $aggregateId)
            ->orderBy('occurred_at')
            ->get();
        
        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }
    
    private function toDomain(EloquentEvidenceRecord $model): EvidenceRecord
    {
        // Reconstruction logic (use reflection or a factory)
        // For brevity, assume a factory method exists
        return EvidenceRecord::reconstruct(
            evidenceId: $model->evidence_id,
            sourceContext: $model->source_context,
            aggregateType: $model->aggregate_type,
            aggregateId: $model->aggregate_id,
            eventType: $model->event_type,
            payload: json_decode($model->payload, true),
            integrityHash: $model->integrity_hash,
            occurredAt: new \DateTimeImmutable($model->occurred_at)
        );
    }
}
```

### Class 3: Event Listener (Records Evidence Automatically)

```php
// app/Contexts/Evidence/Infrastructure/Listeners/RecordEvidenceListener.php

namespace App\Contexts\Evidence\Infrastructure\Listeners;

use App\Contexts\Evidence\Domain\Entities\EvidenceRecord;
use App\Contexts\Evidence\Domain\Repositories\EvidenceRecordRepositoryInterface;
use App\Contexts\Election\Domain\Events\VoteSubmitted;
use App\Contexts\Governance\Domain\Events\GovernanceDecisionRecorded;

class RecordEvidenceListener
{
    public function __construct(
        private EvidenceRecordRepositoryInterface $repository
    ) {}
    
    public function handleVoteSubmitted(VoteSubmitted $event): void
    {
        $record = EvidenceRecord::create(
            sourceContext: 'election',
            aggregateType: 'Vote',
            aggregateId: $event->voteId,
            eventType: 'VoteSubmitted',
            payload: [
                'election_id' => $event->electionId,
                'tracking_code_hash' => $event->trackingCodeHash,
                'submitted_at' => $event->submittedAt,
            ],
            occurredAt: new \DateTimeImmutable($event->submittedAt)
        );
        
        $this->repository->save($record);
    }
    
    public function handleGovernanceDecision(GovernanceDecisionRecorded $event): void
    {
        $record = EvidenceRecord::create(
            sourceContext: 'governance',
            aggregateType: 'GovernanceDecision',
            aggregateId: $event->decisionId,
            eventType: 'GovernanceDecisionRecorded',
            payload: $event->toArray(),
            occurredAt: new \DateTimeImmutable($event->decidedAt)
        );
        
        $this->repository->save($record);
    }
}
```

### Class 4: Event Service Provider (Register Listeners)

```php
// app/Contexts/Evidence/Infrastructure/Providers/EvidenceServiceProvider.php

namespace App\Contexts\Evidence\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contexts\Evidence\Domain\Repositories\EvidenceRecordRepositoryInterface;
use App\Contexts\Evidence\Infrastructure\Persistence\EloquentEvidenceRecordRepository;
use App\Contexts\Election\Domain\Events\VoteSubmitted;
use App\Contexts\Governance\Domain\Events\GovernanceDecisionRecorded;
use App\Contexts\Evidence\Infrastructure\Listeners\RecordEvidenceListener;

class EvidenceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EvidenceRecordRepositoryInterface::class,
            EloquentEvidenceRecordRepository::class
        );
    }
    
    public function boot(): void
    {
        // Register event listeners
        \Event::listen(
            VoteSubmitted::class,
            [RecordEvidenceListener::class, 'handleVoteSubmitted']
        );
        
        \Event::listen(
            GovernanceDecisionRecorded::class,
            [RecordEvidenceListener::class, 'handleGovernanceDecision']
        );
    }
}
```

### Class 5: Evidence Reconciliation Service (The Invariant Enforcer)

```php
// app/Contexts/Evidence/Application/Services/EvidenceReconciliationService.php

namespace App\Contexts\Evidence\Application\Services;

use App\Contexts\Evidence\Domain\Repositories\EvidenceRecordRepositoryInterface;
use App\Contexts\Evidence\Domain\ValueObjects\ReconciliationReport;
use App\Models\ReconciliationReport as ReconciliationReportModel;

class EvidenceReconciliationService
{
    public function __construct(
        private EvidenceRecordRepositoryInterface $evidenceRepository
    ) {}
    
    public function reconcile(string $electionId): ReconciliationReport
    {
        // Count accepted votes (VoteAccepted events)
        $acceptedVotes = $this->countEventsByType($electionId, 'VoteAccepted');
        
        // Count stored votes (VoteStored events)
        $storedVotes = $this->countEventsByType($electionId, 'VoteStored');
        
        // Count counted votes (VoteCounted events)
        $countedVotes = $this->countEventsByType($electionId, 'VoteCounted');
        
        $status = ($acceptedVotes === $storedVotes && $storedVotes === $countedVotes)
            ? 'PASSED'
            : 'FAILED';
        
        $discrepancyDetails = null;
        if ($status === 'FAILED') {
            $discrepancyDetails = [
                'accepted_votes' => $acceptedVotes,
                'stored_votes' => $storedVotes,
                'counted_votes' => $countedVotes,
                'difference' => [
                    'accept_vs_store' => $acceptedVotes - $storedVotes,
                    'store_vs_count' => $storedVotes - $countedVotes,
                ]
            ];
        }
        
        // Store reconciliation report
        $report = ReconciliationReportModel::create([
            'election_id' => $electionId,
            'accepted_votes' => $acceptedVotes,
            'stored_votes' => $storedVotes,
            'counted_votes' => $countedVotes,
            'status' => $status,
            'discrepancy_details' => $discrepancyDetails ? json_encode($discrepancyDetails) : null,
        ]);
        
        return new ReconciliationReport(
            electionId: $electionId,
            acceptedVotes: $acceptedVotes,
            storedVotes: $storedVotes,
            countedVotes: $countedVotes,
            status: $status,
            discrepancyDetails: $discrepancyDetails
        );
    }
    
    private function countEventsByType(string $electionId, string $eventType): int
    {
        // Query evidence_records table
        return \DB::table('evidence_records')
            ->where('source_context', 'election')
            ->where('event_type', $eventType)
            ->whereJsonContains('payload->election_id', $electionId)
            ->count();
    }
}
```

---

## The Exact Development Order

### Day 1 (Today):
1. Create the three migrations
2. Run `php artisan migrate`

### Day 2:
1. Create `EvidenceId`, `EvidenceHash`, `EvidenceSource` value objects
2. Create `EvidenceRecord` entity
3. Create repository interface and implementation

### Day 3:
1. Create `RecordEvidenceListener`
2. Register listeners in service provider
3. Test: Fire a `VoteSubmitted` event → evidence appears in table

### Day 4:
1. Create `EvidenceReconciliationService`
2. Create reconciliation report model
3. Test: Run reconciliation after votes are cast

### Day 5:
1. Create `EvidenceLineage` table and entity
2. Add lineage tracking to listener

### Day 6-7:
1. Build simple Evidence Center UI (Vue3 + Inertia)
2. Display evidence records
3. Show reconciliation status

---

## The First Test

After Day 3, run this test:

```php
// tests/Feature/EvidenceRecordingTest.php

use App\Contexts\Election\Domain\Events\VoteSubmitted;
use App\Models\EvidenceRecord;

test('vote submitted event creates evidence record', function () {
    event(new VoteSubmitted(
        electionId: 'election-2026',
        trackingCodeHash: hash('sha256', 'X7F-92K-LM4'),
        submittedAt: now()->toIso8601String()
    ));
    
    $evidence = EvidenceRecord::where('event_type', 'VoteSubmitted')->first();
    
    expect($evidence)->not->toBeNull();
    expect($evidence->source_context)->toBe('election');
    expect($evidence->integrity_hash)->not->toBeEmpty();
});
```

When this test passes, you have built the foundation.

---

**Shall I write the remaining value objects (`EvidenceId`, `EvidenceHash`, `EvidenceSource`) and the Eloquent model for `evidence_records`?**
review of above  plan : 
You are right. The previous plan was over-engineered.

Let me be **brutally practical** and rewrite the plan based on your critique.

---

## The Core Problem With The Previous Plan

I designed for what I **think** will happen, not what we **know** needs to happen.

You caught this:

> "We have not discovered EvidencePackage yet. We don't know what lineage means yet."

**Correct.**

Let me rewrite from scratch with only what we know for certain.

---

## What We Know For Certain (No Discovery Needed)

| Statement | Certainty |
|-----------|-----------|
| Domain events exist (VoteSubmitted, etc.) | 100% |
| These events need to be stored as evidence | 100% |
| Stored evidence must be immutable | 100% |
| Evidence must be queryable by aggregate | 100% |
| Evidence must be queryable by election | 100% |
| Reconciliation requires counting evidence types | 100% |

**Everything else is discovery.**

---

## The Exact Minimal Implementation

### Step 1: Create ONE table (not three)

```sql
-- database/migrations/2026_06_01_000001_create_evidence_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evidence_records', function (Blueprint $table) {
            $table->id();
            
            // Who generated this evidence
            $table->string('source', 50);        // 'election', 'governance', 'membership'
            
            // What aggregate this evidence belongs to
            $table->string('aggregate_type', 50); // 'vote', 'election', 'committee'
            $table->string('aggregate_id', 100);  // UUID or identifier
            
            // What happened
            $table->string('event_type', 100);    // 'VoteSubmitted', 'ElectionClosed'
            
            // The actual evidence (flexible)
            $table->json('payload');
            
            // When it happened (domain time, not system time)
            $table->timestamp('occurred_at');
            
            // Immutability marker
            $table->timestamp('frozen_at')->nullable();
            
            // Created at (system time, for debugging)
            $table->timestamps();
            
            // Indexes for the queries we KNOW we need
            $table->index(['aggregate_type', 'aggregate_id', 'occurred_at']);
            $table->index(['source', 'event_type', 'occurred_at']);
            $table->index('occurred_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('evidence_records');
    }
};
```

**No integrity_hash yet.** Why? Because we don't know if we need it. Add it when a dispute requires proving records weren't altered.

**No lineage table yet.** Why? Because we don't know what lineage means. Add it when we discover the pattern.

**No reconciliation table yet.** Why? Because reconciliation is a **calculation** from evidence_records, not a separate table.

---

### Step 2: Create ONE model (Eloquent, simple)

```php
// app/Models/EvidenceRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenceRecord extends Model
{
    protected $table = 'evidence_records';
    
    protected $fillable = [
        'source',
        'aggregate_type',
        'aggregate_id',
        'event_type',
        'payload',
        'occurred_at',
        'frozen_at',
    ];
    
    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
        'frozen_at' => 'datetime',
    ];
    
    // Mark evidence as immutable (cannot be changed after this point)
    public function freeze(): void
    {
        $this->frozen_at = now();
        $this->save();
    }
    
    public function isFrozen(): bool
    {
        return $this->frozen_at !== null;
    }
    
    // Scope for election evidence
    public function scopeForElection($query, string $electionId)
    {
        return $query->where('aggregate_type', 'election')
            ->where('aggregate_id', $electionId);
    }
    
    // Scope for vote evidence
    public function scopeForVote($query, string $voteId)
    {
        return $query->where('aggregate_type', 'vote')
            ->where('aggregate_id', $voteId);
    }
}
```

---

### Step 3: Create ONE listener (records everything)

```php
// app/Listeners/RecordEvidenceListener.php

namespace App\Listeners;

use App\Models\EvidenceRecord;
use Illuminate\Support\Facades\Log;

class RecordEvidenceListener
{
    public function handle(object $event): void
    {
        // Only record events we care about
        $eventType = get_class($event);
        
        $mapping = $this->getEventMapping();
        
        if (!isset($mapping[$eventType])) {
            return; // Not an evidence event
        }
        
        $config = $mapping[$eventType];
        
        try {
            EvidenceRecord::create([
                'source' => $config['source'],
                'aggregate_type' => $config['aggregate_type'],
                'aggregate_id' => $this->extractAggregateId($event, $config),
                'event_type' => $config['event_type'],
                'payload' => $this->extractPayload($event, $config),
                'occurred_at' => $this->extractOccurredAt($event, $config),
            ]);
        } catch (\Exception $e) {
            // Log but don't fail the original event
            Log::error('Failed to record evidence: ' . $e->getMessage(), [
                'event' => $eventType,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getEventMapping(): array
    {
        return [
            // Election context
            \App\Events\VoteSubmitted::class => [
                'source' => 'election',
                'aggregate_type' => 'vote',
                'aggregate_id_field' => 'voteId',
                'event_type' => 'VoteSubmitted',
                'payload_fields' => ['electionId', 'trackingCodeHash', 'candidateId'],
                'occurred_at_field' => 'submittedAt',
            ],
            
            \App\Events\VoteAccepted::class => [
                'source' => 'election',
                'aggregate_type' => 'vote',
                'aggregate_id_field' => 'voteId',
                'event_type' => 'VoteAccepted',
                'payload_fields' => ['electionId', 'validationRules'],
                'occurred_at_field' => 'acceptedAt',
            ],
            
            \App\Events\ElectionClosed::class => [
                'source' => 'election',
                'aggregate_type' => 'election',
                'aggregate_id_field' => 'electionId',
                'event_type' => 'ElectionClosed',
                'payload_fields' => ['closedBy', 'reason'],
                'occurred_at_field' => 'closedAt',
            ],
            
            // Governance context
            \App\Events\GovernanceDecisionRecorded::class => [
                'source' => 'governance',
                'aggregate_type' => 'decision',
                'aggregate_id_field' => 'decisionId',
                'event_type' => 'GovernanceDecisionRecorded',
                'payload_fields' => ['electionId', 'decision', 'madeBy'],
                'occurred_at_field' => 'decidedAt',
            ],
        ];
    }
    
    private function extractAggregateId(object $event, array $config): string
    {
        $field = $config['aggregate_id_field'];
        return $event->$field;
    }
    
    private function extractPayload(object $event, array $config): array
    {
        $payload = [];
        foreach ($config['payload_fields'] as $field) {
            if (property_exists($event, $field)) {
                $payload[$field] = $event->$field;
            }
        }
        return $payload;
    }
    
    private function extractOccurredAt(object $event, array $config): \DateTimeInterface
    {
        $field = $config['occurred_at_field'];
        if (property_exists($event, $field)) {
            return $event->$field instanceof \DateTimeInterface 
                ? $event->$field 
                : new \DateTimeImmutable($event->$field);
        }
        return now();
    }
}
```

---

### Step 4: Register the listener

```php
// app/Providers/EventServiceProvider.php

use App\Listeners\RecordEvidenceListener;

protected $listen = [
    // Election events
    \App\Events\VoteSubmitted::class => [
        RecordEvidenceListener::class,
    ],
    \App\Events\VoteAccepted::class => [
        RecordEvidenceListener::class,
    ],
    \App\Events\ElectionClosed::class => [
        RecordEvidenceListener::class,
    ],
    
    // Governance events
    \App\Events\GovernanceDecisionRecorded::class => [
        RecordEvidenceListener::class,
    ],
];
```

---

### Step 5: The reconciliation service (simple query, no new table)

```php
// app/Services/EvidenceReconciliationService.php

namespace App\Services;

use App\Models\EvidenceRecord;

class EvidenceReconciliationService
{
    public function reconcile(string $electionId): array
    {
        $accepted = EvidenceRecord::where('event_type', 'VoteAccepted')
            ->whereJsonContains('payload->electionId', $electionId)
            ->count();
        
        $stored = EvidenceRecord::where('event_type', 'VoteStored')
            ->whereJsonContains('payload->electionId', $electionId)
            ->count();
        
        $counted = EvidenceRecord::where('event_type', 'VoteCounted')
            ->whereJsonContains('payload->electionId', $electionId)
            ->count();
        
        $isReconciled = ($accepted === $stored && $stored === $counted);
        
        return [
            'election_id' => $electionId,
            'accepted_votes' => $accepted,
            'stored_votes' => $stored,
            'counted_votes' => $counted,
            'is_reconciled' => $isReconciled,
            'reconciled_at' => now()->toIso8601String(),
        ];
    }
    
    public function findMissingEvidence(string $electionId): array
    {
        // Find accepted votes without stored evidence
        $acceptedVotes = EvidenceRecord::where('event_type', 'VoteAccepted')
            ->whereJsonContains('payload->electionId', $electionId)
            ->get();
        
        $missing = [];
        
        foreach ($acceptedVotes as $accepted) {
            $stored = EvidenceRecord::where('event_type', 'VoteStored')
                ->where('aggregate_id', $accepted->aggregate_id)
                ->exists();
            
            if (!$stored) {
                $missing[] = [
                    'vote_id' => $accepted->aggregate_id,
                    'accepted_at' => $accepted->occurred_at,
                    'missing_evidence' => 'VoteStored',
                ];
            }
        }
        
        return $missing;
    }
}
```

---

## The Exact Development Order (Revised)

### Day 1 (30 minutes)
Create the migration for `evidence_records` table.

Run `php artisan migrate`.

### Day 2 (1 hour)
Create `EvidenceRecord` model.

Test: Manually insert a record via tinker.

### Day 3 (2 hours)
Create `RecordEvidenceListener`.

Register it for `VoteSubmitted` event.

Test: Fire event → evidence appears in table.

### Day 4 (2 hours)
Add mappings for `VoteAccepted`, `VoteStored`, `ElectionClosed`.

Test: Each event creates evidence.

### Day 5 (1 hour)
Create `EvidenceReconciliationService`.

Test: Run reconciliation on test election.

### Day 6 (2 hours)
Build simple Evidence Center UI (Vue3):

- List evidence records
- Filter by election
- Show reconciliation status

### Day 7 (Observe)
Run real votes through system.

Review the evidence_records table.

Ask:

```text
What patterns do we see?
What queries do we need?
What's missing?
```

**Then** decide what to build next.

---

## What We Are NOT Building (Yet)

| Feature | When to add |
|---------|-------------|
| integrity_hash | When a dispute requires proving no tampering |
| evidence_lineage table | When we understand what lineage means |
| reconciliation_reports table | When we need historical snapshots |
| EvidencePackage aggregate | When we discover the need |
| EvidenceLineage entity | When we discover the pattern |
| Hash chains | Never, unless legally required |

---

## The One Thing We Build Today

**The migration for `evidence_records`.**

Nothing else.

Because until the table exists, nothing else matters.

---

## After Day 7: The Discovery Questions

After running real votes, ask:

1. **Do we need integrity hashes?**  
   (Have any disputes about tampering?)

2. **Do we need lineage tracking?**  
   (Do we need to know who observed what evidence?)

3. **Do we need reconciliation snapshots?**  
   (Do we need historical reconciliation state?)

4. **Is EvidencePackage an aggregate or a DTO?**  
   (Does it have behavior or just data?)

Let reality answer these questions.

---

## The Ganesh Ji Check

Ganesh Ji would say:

> *"You have built one small table. Good. Now watch what happens. When you see the pattern, then build the model. Not before."*

---

**Shall I write the exact migration file now so you can run it today?**