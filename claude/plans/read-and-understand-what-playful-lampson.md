# Evidence Bounded Context — DD.4 Aggregate Discovery → Discovery Instrumentation

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete DD.4 Aggregate Discovery documentation, then build the minimum discovery instrumentation — one capture table, one infrastructure adapter — so real domain events can be observed before any aggregate boundary is finalized. Nothing in Phase 1 is domain modeling. It is observation infrastructure.

**Architecture:** Phase 0 is documentation only. Phase 1 is a discovery capture adapter that lives in `Infrastructure/Discovery/`, not Domain — because we have not yet completed aggregate discovery and cannot claim any concept is a domain entity. The folder name `Discovery/` (not `EvidenceCapture/` or `Persistence/`) is intentional: it signals the temporary, observation-only nature of this layer and prevents "accidental architecture" where a temporary table becomes a 5-year commitment. All Domain layer code is deferred until DD.5 (Scenario Analysis) produces invariant assignments and consistency boundaries.

**Tech Stack:** Laravel 11, Eloquent (infrastructure only), PHPUnit, PHP 8.2

---

## ARB Approval Conditions (must be met before implementation starts)

```
Required changes incorporated into this revision:

1. ✅ Aggregate candidates renamed to Hypotheses — "Suspected Root: Yes" removed
2. ✅ No assumption that aggregate roots exist — boundaries open
3. ✅ Evidence capture treated as discovery instrumentation, not domain modeling
4. ✅ EvidenceRecord removed from Domain/ — replaced by CapturedDomainEvent in Infrastructure
5. ✅ EVI-5 strengthened — indirect re-identification prohibited
6. ✅ "One week" replaced by scenario saturation criteria
```

---

## Architect Directive (read before starting any task)

```
What we accept:
  ✅ Verification is a future supporting bounded context
  ✅ EVI-5: Evidence must not permit reconstruction of voter identity
         without explicit constitutional authority (not just "no user_id")
  ✅ VR-1..VR-5 requirements are domain requirements, not implementation decisions
  ✅ Capture table is discovery instrumentation, not domain model

What we explicitly reject:
  ❌ EvidenceRecord as a Domain Entity (aggregate discovery not complete)
  ❌ ConstitutionalEvidenceSnapshot declared as Aggregate Root (hypothesis only)
  ❌ vote_commitments, tracking_code, sequence_number, integrity_hash
  ❌ Vote replacement / Estonian model
  ❌ Fixed time-based discovery exit (use scenario saturation instead)
  ❌ Any EvidencePackage, EvidenceLineage, ReconciliationReports
```

---

## File Map

### Phase 0A — Discovery Documentation (no code)

| Action | Path |
|--------|------|
| Create | `docs/architecture/contexts/EvidenceAggregateDiscovery.md` |
| Create | `docs/architecture/contexts/EvidenceEventTaxonomy.md` |
| Create | `docs/architecture/contexts/EvidenceScenarioCatalog.md` ← **NEW: Scenario-first thinking** |
| Modify | `docs/architecture/contexts/EvidenceContext.md` (add EVI-5 + VR-1..VR-5) |

### Phase 0B — ARB Review Checkpoint

Gate before Phase 1 begins.

ARB questions:
- Are hypotheses still valid after scenario catalog review?
- Is EVI-5 complete and correctly stated?
- Is Evidence/Evaluation boundary clear in all 7 scenarios?
- Are VR-1..VR-5 still sufficient?

### Phase 1 — Discovery Instrumentation (only after Phase 0B approval)

### Phase 1 — Discovery Instrumentation Only

| Action | Path | Layer |
|--------|------|-------|
| Create | `database/migrations/2026_06_01_000000_create_evidence_capture_table.php` | Infrastructure |
| Create | `app/Contexts/Evidence/Infrastructure/Discovery/CapturedDomainEvent.php` | Infrastructure |
| Create | `app/Contexts/Evidence/Infrastructure/Discovery/DiscoveryEventStore.php` | Infrastructure |
| Create | `app/Contexts/Evidence/Infrastructure/Discovery/EvidenceCaptureAdapter.php` | Infrastructure |
| Create | `app/Contexts/Evidence/EvidenceServiceProvider.php` | Infrastructure |
| Modify | `app/Providers/AppServiceProvider.php` | Infrastructure |
| Modify | `app/Providers/EventServiceProvider.php` | Infrastructure |
| Create | `tests/Feature/Contexts/Evidence/EvidenceCaptureAdapterTest.php` | Test |

**No `Domain/` folder is created in Phase 1.**
Domain modeling begins in DD.5 after scenario saturation.

---

## Phase 0: Architecture Documentation

### Task 1: Create EvidenceAggregateDiscovery.md

**Files:**
- Create: `docs/architecture/contexts/EvidenceAggregateDiscovery.md`

- [ ] **Step 1: Write the document**

```markdown
# Evidence Bounded Context — Aggregate Discovery

**Status:** DD.4 — In Progress
**Next phase:** DD.5 Scenario Analysis (requires scenario saturation first)
**Feeds into:** Tactical DDD implementation (Domain layer, Phase 2+)

---

## Purpose

This document records aggregate hypotheses, open questions, and invariant assignments
for the Evidence Bounded Context. It does not finalize any aggregate boundary.
Boundaries are finalized after scenario saturation in DD.5.

---

## Aggregate Hypotheses

These are working hypotheses only. "Hypothesis" means:
we believe this area will contain an aggregate, but we have not yet run
the scenario matrix to validate consistency boundaries.

### Hypothesis A: ConstitutionalEvidenceSnapshot

**Domain area:** Evidence preservation
**Identity basis:** Snapshot ID + Election context
**Contains (candidate value objects — not confirmed):**
- VerificationEvidence
- NetworkEvidence
- DeviceEvidence
- SessionContinuity
- ParticipationEligibilityEvidence
- ElectionConstitutionSnapshot
- evaluatedAt (DateTimeImmutable)

**What we believe about its behavior:**
- Created once, frozen immediately (invariant I-1/EVI-1)
- Read by Replay Context (via ReplayEvidenceEnvelope)
- Read by Evaluation Context as input to evaluation

**Open questions (to be resolved in DD.5):**
- Does it acquire behavior beyond construction?
- Is it one snapshot per voter per election, or per evaluation request?
- Does it ever need updating after creation? (If yes, it might not be frozen.)
- Is the constitutionalHash computed at construction or stored separately?

**DO NOT conclude:** that this is a confirmed aggregate root until
DD.5 scenario matrix assigns invariants to it.

---

### Hypothesis B: EvaluationEnvelope

**Domain area:** Evaluation interpretation
**Critical boundary:** EvaluationEnvelope likely belongs to the Evaluation Context,
NOT the Evidence Context.

**Evidence Context produces input.**
**Evaluation Context owns the envelope.**

**Open questions (to be resolved in DD.5):**
- Which invariants does EvaluationEnvelope enforce?
- Who can modify it after creation?
- Is it an aggregate root or a value object passed across the boundary?

---

### Non-Aggregate Observation: ConstitutionalObservationContext

**Type:** Flat value-object container (likely)
**No lifecycle, no identity**
**Created by:** Observation Context
**Consumed by:** Evidence Context as input

This is not being challenged as an aggregate hypothesis — it is likely always a VO.

---

## DD.4 Exit Criteria — Scenario Saturation

**Do not proceed to DD.5 until ALL of the following are true:**

```
✓ All invariants in EvidenceContext.md have been assigned to an owner
  (aggregate candidate, value object, or policy)

✓ Scenario matrix is complete:
  At least 3 distinct election scenarios have been run through
  the constitutional authority chain observation → evidence → evaluation → legitimacy

✓ Consistency boundaries identified:
  For each hypothesis, we can state "these fields must change together"
  and "this aggregate never crosses this boundary"

✓ Event saturation reached:
  Running more election scenarios produces no new evidence observations
  (new events are variants, not structurally new)

✓ Evidence/Evaluation boundary validated:
  We can show a concrete example where Evidence answers
  "what facts exist?" and Evaluation answers "what do those facts mean?"
  as two separate, non-overlapping responsibilities

✓ Discovery instrumentation data reviewed:
  Real captured_domain_events data from at least one complete election cycle
  has been examined and described in the scenario analysis
```

---

## Future Verification Requirements

Extracted from German Bundestag paper + IEEE paper + architect review (2026-06-01).
These are domain requirements only. No implementation decisions here.

**VR-1:** Evidence must be independently verifiable.

**VR-2:** Verification must not require access to voter identity.

**VR-3:** Verification must not alter evidence.

**VR-4:** Verification authority must remain separate from governance authority.

**VR-5:** Verification must support replayability.

### Verification as a Future Bounded Context

```
Observation → Evidence → Evaluation → Legitimacy → Governance
                                                         │
                Supporting Contexts:                     │
                ─────────────────────────────            │
                Replay                                   │
                Verification  ◄──────────────────────────┘
                Certification
                Reporting
```

**Verification is NOT:** Audit (Audit consumes authority, not an authority source)
**Verification IS:** Independent re-derivation of correctness from sealed evidence

**Concepts accepted from research:**
- Evidence must be traceable without exposing voter identity (EVI-5)
- Verification requires reconstructable chronology
- "Election Certified" ≠ "Election Closed" — certification requires complete evidence chain

**Implementation:** Deferred until after DD.5 Scenario Analysis completes.

---

## Next Steps

1. Create `EvidenceEventTaxonomy.md` (observed vs target events)
2. Build `evidence_capture` table (discovery instrumentation, not domain model)
3. Wire capture adapter to existing domain events
4. **Run scenario saturation — observe, not decide**
5. When saturation criteria above are met → start DD.5 Scenario Analysis
6. After DD.5 → finalize aggregate boundaries → build Domain layer
```

- [ ] **Step 2: Commit**

```bash
git add docs/architecture/contexts/EvidenceAggregateDiscovery.md
git commit -m "docs(evidence): add DD.4 aggregate discovery — hypotheses only, scenario saturation exit criteria, VR-1..VR-5"
```

---

### Task 2: Create EvidenceEventTaxonomy.md

**Files:**
- Create: `docs/architecture/contexts/EvidenceEventTaxonomy.md`

- [ ] **Step 1: Write the taxonomy — two sections: Observed vs Target**

```markdown
# Evidence Event Taxonomy

**Status:** DD.4 — Working document
**Purpose:** Map which domain events the Evidence Context should observe.
**Rule:** Evidence Context subscribes. It never emits sovereign events.

---

## Constitutional Timeline (Target)

This is the desired sequence for a voter participation event.
NOT all of these events exist yet. This is the target state.

```
ObservationRecorded          ← Observation Context owns
        ↓
EvidenceFrozen               ← Evidence Context emits (future, not yet built)
        ↓
EvidenceEvaluationCompleted  ← Evaluation Context emits (future, not yet built)
        ↓
LegitimacyGranted / LegitimacyDenied
        ↓
GovernanceDecisionRecorded
```

---

## Section 1: Observed Events (Exist Today — Capture These Now)

These event classes are confirmed to exist in the codebase.
The capture adapter will subscribe to these in Phase 1.

| Event Class | Source Context | What It Tells Us | Confirmed? |
|-------------|---------------|------------------|------------|
| `App\Domain\Election\Events\VoterAssignedToElection` | Election | Voter registered for election | ✅ Yes |
| `App\Domain\Election\Events\VotingOpened` | Election | Voting window opened | ✅ Yes |
| `App\Domain\Election\Events\VotingClosed` | Election | Voting window closed | Verify |
| `App\Domain\Election\Events\ResultsPublished` | Election | Results published | Verify |
| `App\Domain\Election\Security\Event\LegitimacyGranted` | Election Security | Voter legitimacy confirmed | Verify |
| `App\Domain\Election\Security\Event\LegitimacyEvaluated` | Election Security | Evaluation completed | Verify |
| `App\Domain\Election\Security\Event\ObservationRecorded` | Election Security | Overlay observation recorded | Verify |

**Action for implementor:** Before wiring the capture adapter, run:
```bash
find app/ -name "*.php" | xargs grep -l "class VotingClosed\|class ResultsPublished\|class LegitimacyGranted" 
```
Only wire confirmed classes. Remove unconfirmed rows from adapter config.

---

## Section 2: Target Events (Do Not Exist Yet — Do Not Capture Yet)

These events represent the desired future state.
They are documented here for DD.5 Scenario Analysis, not for Phase 1 capture.

| Event | Owning Context | Why Deferred |
|-------|---------------|--------------|
| EvidenceFrozen | Evidence | Event class not yet created |
| EvidenceEvaluationCompleted | Evaluation | Evaluation Context not formalized |
| GovernanceDecisionRecorded | Governance | Event class not confirmed |
| ReplayCertificationIssued | Replay | Replay Context not formalized |

---

## Events We Do NOT Subscribe To

| Event | Reason |
|-------|--------|
| VoteSubmitted | No such domain event — votes are anonymous, no user linkage |
| VoteCounted | Not a domain event — counting is arithmetic, not constitutional |
| Any Membership events | Cross-context coupling risk; use integration events when ready |

---

## Privacy Rule At Capture Level

**EVI-5 applies to every captured event:**
Evidence capture must not store any field that, alone or combined
with other stored fields, permits reconstruction of voter identity
without explicit constitutional authority.

Scrutinize these fields before capturing:
- `user_id` → never store
- `email` → never store
- timestamps combined with `election_id` alone → may be acceptable (many voters)
- timestamps combined with `election_id` + `organisation_id` + unusual event time → review
```

- [ ] **Step 2: Commit**

```bash
git add docs/architecture/contexts/EvidenceEventTaxonomy.md
git commit -m "docs(evidence): add event taxonomy — observed vs target split, privacy scrutiny guidance"
```

---

### Task 3: Update EvidenceContext.md with EVI-5 + VR references

**Files:**
- Modify: `docs/architecture/contexts/EvidenceContext.md`

- [ ] **Step 1: Find the Invariants section — add EVI-5 after I-7**

```markdown
- **I-8 / EVI-5: Privacy-Preserving Traceability**
  Evidence must not permit reconstruction of voter identity
  without explicit constitutional authority.
  This prohibition applies to direct identifiers (user_id, email)
  AND to indirect reconstruction via combination of stored fields.
  Evidence records carry source context and event type but never voter identity.
  Cross-reference: EvidenceEventTaxonomy, VR-2, VR-4.
```

- [ ] **Step 2: Find Context Relationships — add Verification note at end**

```markdown
### Future: Verification Context (Not Yet Built)

A Verification Context will sit alongside Replay as a supporting context.
It consumes ConstitutionalEvidenceSnapshot (sealed, read-only).
It has no write authority over evidence.
Requirements: VR-1 through VR-5 (see EvidenceAggregateDiscovery.md).
Implementation deferred until after DD.5 Scenario Analysis completes.
```

- [ ] **Step 3: Commit**

```bash
git add docs/architecture/contexts/EvidenceContext.md
git commit -m "docs(evidence): add EVI-5 (indirect re-identification prohibited) + future Verification Context note"
```

---

### Task 4: Create EvidenceScenarioCatalog.md

**Files:**
- Create: `docs/architecture/contexts/EvidenceScenarioCatalog.md`

- [ ] **Step 1: Write the scenario catalog**

```markdown
# Evidence Scenario Catalog

**Status:** DD.4 — Input to Aggregate Discovery  
**Purpose:** Map how domain events flow through the constitutional authority chain.  
**Usage:** These scenarios will be executed in Phase 1 (instrumentation) and DD.5 (scenario analysis).

---

## Scenario Mapping Format

Each scenario shows:
```
User Action
    ↓
Observation Recorded
    ↓
Evidence Captured
    ↓
Evaluation Completed
    ↓
Legitimacy Decision
    ↓
Governance Outcome
```

---

## Scenario SC-01: First-Time Voter

**Setup:** New user registered, first election

**Flow:**

```
Event: VoterAssignedToElection
    → Observation: User eligible to vote
    → Evidence Captured: voter_id, election_id, organisation_id
    → Evaluation: "eligible" (no flags)
    → Legitimacy: GRANTED
    → Governance: Voter added to ballot
```

**Questions for DD.5:**
- Does one ConstitutionalEvidenceSnapshot exist per voter per election?
- What is minimum evidence for "eligible"?
- When is Evidence frozen?

---

## Scenario SC-02: Returning Voter (No Changes)

**Setup:** User voted before, voting again in new election, no changes to eligibility

**Flow:**

```
Event: VoterAssignedToElection
    → Observation: User eligible (cached from previous election)
    → Evidence Captured: voter_id, election_id, organisation_id, previous_election_id
    → Evaluation: "eligible" (cross-election consistency check)
    → Legitimacy: GRANTED
    → Governance: Voter added to ballot
```

**Questions for DD.5:**
- Is voter history evidence or evaluation state?
- Can evaluation reference previous elections' evidence?
- Aggregate boundary: per-election or cross-election?

---

## Scenario SC-03: Voting Window Opens

**Setup:** Election voting starts

**Flow:**

```
Event: VotingOpened (election_id, opened_by)
    → Observation: Voting window is now open
    → Evidence Captured: election_id, opened_by timestamp
    → Evaluation: "voting_active" (implicit)
    → Legitimacy: N/A (applies to all voters equally)
    → Governance: Voters can now submit votes
```

**Questions for DD.5:**
- Is "voting_active" evidence or evaluation state?
- Does this emit a domain event from Evidence Context?

---

## Scenario SC-04: Voting Window Closes

**Setup:** Election voting ends

**Flow:**

```
Event: VotingClosed (election_id, closed_by)
    → Observation: Voting window is now closed
    → Evidence Captured: election_id, closed_by, closed_at
    → Evaluation: "voting_closed" (final state)
    → Legitimacy: N/A (applies to all voters equally)
    → Governance: No more votes accepted
```

**Questions for DD.5:**
- When is evidence "frozen"? At close? At results?
- Do we need separate EvidenceFrozen event?

---

## Scenario SC-05: Legitimacy Granted (Happy Path)

**Setup:** Voter voted, no flags during evaluation

**Flow:**

```
Event: VotingOpened (for specific voter context)
    → Observation: Vote submitted; device trust verified; network continuity ok
    → Evidence Captured: voting_code_hash, device_fingerprint, network_snapshot, timestamp
    → Evaluation: "legitimate_vote" (all checks pass)
    → Legitimacy: GRANTED (voter's participation valid)
    → Governance: Vote added to ballot
```

**Questions for DD.5:**
- What data constitutes "vote submitted" evidence?
- Is voting_code_hash stored in evidence_capture, or is it derived?
- Is device fingerprint evidence or observation?
- Aggregate boundary: per-vote or per-voter?

---

## Scenario SC-06: Legitimacy Denied (Suspicious Activity)

**Setup:** Voter votes, but suspicious activity detected

**Flow:**

```
Event: ObservationRecorded (ip_velocity_high, device_mismatch)
    → Observation: Multiple votes from different IPs in 5 minutes; device changed
    → Evidence Captured: source_ip, timestamp, device_id, previous_device_id
    → Evaluation: "suspicious_activity" (overlay flags present)
    → Legitimacy: DENIED (voter participation not valid)
    → Governance: Vote not counted; voter notified
```

**Questions for DD.5:**
- Who owns the suspicion threshold (Observation or Evaluation)?
- Can voter appeal a denial? (Future: replay certification)
- Is the overlay policy part of Evidence Context?

---

## Scenario SC-07: Replay Request (Audit)

**Setup:** Election administrator requests audit/replay of voter participation

**Flow:**

```
Event: ReplaySessionOpened (election_id, voter_id, scenario)
    → Observation: Retrieve captured evidence from SC-05 or SC-06
    → Evidence Loaded: voting_code_hash, device_fingerprint, network_snapshot, timestamp
    → Evaluation: Re-run evaluation logic on frozen evidence
    → Legitimacy: Re-derive legitimacy decision
    → Governance: Compare original governance outcome with replay outcome
```

**Questions for DD.5:**
- Is ConstitutionalEvidenceSnapshot replayed as-is?
- Does evidence_capture table need version tracking?
- Can hash of evidence verify replay determinism?

---

## Cross-Scenario Invariant Check

Run all 7 scenarios and verify:

```
✓ EVI-1 (Evidence frozen at evaluation)
  — Evidence data does not change after evaluation completes

✓ EVI-5 (No indirect voter re-identification)
  — captured voter_id? If yes, violates invariant
  — captured timestamp + device + IP? Revisit field combinations

✓ Evidence/Evaluation separation
  — Evidence answers "what happened?"
  — Evaluation answers "what does it mean?"
  — No business logic in Evidence Context

✓ VR-2 (Verification without voter identity)
  — Can scenario be verified using only aggregate_reference + timestamp?
  — Or does verification need voter_id? (If yes, violates VR-2)
```

---

## Next Use

This catalog will be:

1. **Phase 1:** Used to design evidence_capture schema and CapturedDomainEvent structure
2. **DD.5:** Used to test aggregate hypotheses against real scenarios
3. **Domain layer:** Used to define what events Evidence Context should emit
```

- [ ] **Step 2: Commit**

```bash
git add docs/architecture/contexts/EvidenceScenarioCatalog.md
git commit -m "docs(evidence): add scenario catalog — 7 scenarios through constitutional authority chain for DD.5"
```

---

## Phase 0B: Architecture Review Checkpoint

After all 4 Phase 0A documents are committed, ARB reviews:

- [ ] **Step 1: Review artifacts for consistency**

Questions to verify:

1. Are hypotheses in `EvidenceAggregateDiscovery.md` consistent with scenarios in `EvidenceScenarioCatalog.md`?
2. Do all 7 scenarios follow the observed event types in `EvidenceEventTaxonomy.md`?
3. Is EVI-5 correctly enforced in all scenarios (no voter re-identification)?
4. Is Evidence/Evaluation boundary clear in each scenario?

- [ ] **Step 2: ARB approval decision**

Approve one of:

```bash
APPROVED — Proceed to Phase 1
APPROVED WITH CHANGES — Revise Phase 0A docs, then re-review
NOT APPROVED — Reassess architecture, redo discovery
```

Do not proceed to Phase 1 without approval.

---

## Phase 1: Discovery Instrumentation (only after Phase 0B approval)

> **Gate:** Do not start Phase 1 until Phase 0 documents are committed and reviewed.
>
> Everything in Phase 1 is **instrumentation infrastructure**. It is not domain modeling.
> The table is named `evidence_capture`, not `evidence_records`.
> The classes live in `Infrastructure/Discovery/`, not `Infrastructure/Persistence/` or `Domain/`.
> The Eloquent model is named `DiscoveryEventStore`, not `EvidenceRecord`.
> Naming is intentional: it signals "we are observing" not "we have modeled."
> This prevents **accidental architecture** — temporary tables that become permanent fixtures because nobody renamed them.

### Task 4: Create evidence_capture Migration

**Files:**
- Create: `database/migrations/2026_06_01_000000_create_evidence_capture_table.php`

- [ ] **Step 1: Write the migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidence_capture', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('source_context', 100);     // e.g. "Election", "Membership"
            $table->string('aggregate_reference', 255); // e.g. "election:{uuid}"
            $table->string('event_type', 150);          // e.g. "VoterAssignedToElection"
            $table->json('payload');                    // scalar identifiers only — no user_id per EVI-5
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['source_context', 'event_type']);
            $table->index('aggregate_reference');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_capture');
    }
};
```

**Schema decisions:**
- Named `evidence_capture` (not `evidence_records`) — signals instrumentation, not final model
- No `user_id` — EVI-5 direct prohibition
- No `integrity_hash` — deferred (premature without aggregate discovery)
- No `sequence_number` — may belong in Replay, not here
- `payload` is JSON of scalar identifiers only

- [ ] **Step 2: Run in test environment**

```bash
php artisan migrate --env=testing
```

Expected: `Migrating: 2026_06_01_000000_create_evidence_capture_table` → `Migrated`

- [ ] **Step 3: Commit**

```bash
git add database/migrations/2026_06_01_000000_create_evidence_capture_table.php
git commit -m "feat(evidence): add evidence_capture discovery instrumentation table — not domain model"
```

---

### Task 5: Create CapturedDomainEvent + DiscoveryEventStore

**Files:**
- Create: `app/Contexts/Evidence/Infrastructure/Discovery/CapturedDomainEvent.php`
- Create: `app/Contexts/Evidence/Infrastructure/Discovery/DiscoveryEventStore.php`

- [ ] **Step 1: Write CapturedDomainEvent — pure data carrier**

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Evidence\Infrastructure\Discovery;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

/**
 * @internal Discovery instrumentation. NOT a domain entity. NOT a confirmed aggregate.
 *           Captures observed domain events for DD.5 scenario analysis.
 *           Will be replaced after aggregate boundaries are confirmed in DD.5.
 *           Folder name "Discovery/" is intentional — signals temporary, not permanent.
 */
final readonly class CapturedDomainEvent
{
    public function __construct(
        public string $id,
        public string $sourceContext,
        public string $aggregateReference,
        public string $eventType,
        public array $payload,
        public DateTimeImmutable $occurredAt,
    ) {}

    public static function capture(
        string $sourceContext,
        string $aggregateReference,
        string $eventType,
        array $payload,
        DateTimeImmutable $occurredAt,
    ): self {
        return new self(
            id: Uuid::uuid4()->toString(),
            sourceContext: $sourceContext,
            aggregateReference: $aggregateReference,
            eventType: $eventType,
            payload: $payload,
            occurredAt: $occurredAt,
        );
    }
}
```

- [ ] **Step 2: Write DiscoveryEventStore — Eloquent model for the capture table**

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Evidence\Infrastructure\Discovery;

use Illuminate\Database\Eloquent\Model;

/**
 * @internal Eloquent persistence for evidence_capture table.
 *           Discovery instrumentation — NOT the final domain model.
 *           Named "DiscoveryEventStore" (not "EvidenceRecord") to prevent accidental
 *           architectural lock-in. This class is expected to be replaced or renamed
 *           after DD.5 Scenario Analysis confirms aggregate boundaries.
 */
class DiscoveryEventStore extends Model
{
    protected $table = 'evidence_capture';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'source_context',
        'aggregate_reference',
        'event_type',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];

    public static function fromCapture(CapturedDomainEvent $captured): self
    {
        return new self([
            'id'                  => $captured->id,
            'source_context'      => $captured->sourceContext,
            'aggregate_reference' => $captured->aggregateReference,
            'event_type'          => $captured->eventType,
            'payload'             => $captured->payload,
            'occurred_at'         => $captured->occurredAt->format('Y-m-d H:i:s'),
        ]);
    }
}
```

- [ ] **Step 3: Commit**

```bash
git add app/Contexts/Evidence/Infrastructure/Discovery/CapturedDomainEvent.php
git add app/Contexts/Evidence/Infrastructure/Discovery/DiscoveryEventStore.php
git commit -m "feat(evidence): add CapturedDomainEvent + DiscoveryEventStore in Discovery/ — instrumentation, not domain model"
```

---

### Task 6: Create EvidenceCaptureAdapter

**Files:**
- Create: `app/Contexts/Evidence/Infrastructure/Discovery/EvidenceCaptureAdapter.php`

- [ ] **Step 1: Verify which event classes actually exist (read-only step)**

Before writing the adapter, confirm the event classes exist:

```bash
find app/ -name "VotingClosed.php" -o -name "ResultsPublished.php" -o -name "LegitimacyGranted.php"
```

Only include confirmed classes in the `$captureMap` below.

- [ ] **Step 2: Write the adapter with confirmed events only**

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Evidence\Infrastructure\Discovery;

use DateTimeImmutable;

/**
 * Subscribes to domain events and persists them as CapturedDomainEvents.
 * This is discovery instrumentation for DD.4 Aggregate Discovery.
 * It is NOT a domain service. It does not enforce domain invariants.
 *
 * Adding a new event to capture = add one entry to $captureMap.
 * Naming of payload keys must never include user_id (EVI-5).
 */
class EvidenceCaptureAdapter
{
    private array $captureMap = [
        \App\Domain\Election\Events\VoterAssignedToElection::class => [
            'source_context' => 'Election',
            'aggregate_ref'  => fn($e) => "election:{$e->electionId}",
            'payload'        => fn($e) => [
                // EVI-5: no user_id stored
                'election_id'     => $e->electionId,
                'organisation_id' => $e->organisationId,
            ],
            'occurred_at' => fn($e) => $e->occurredAt,
        ],
        \App\Domain\Election\Events\VotingOpened::class => [
            'source_context' => 'Election',
            'aggregate_ref'  => fn($e) => "election:{$e->election->id}",
            'payload'        => fn($e) => [
                'election_id' => $e->election->id,
                'opened_by'   => $e->openedBy,
            ],
            'occurred_at' => fn($e) => new DateTimeImmutable(),
        ],
        // Add confirmed events here after running the find command in Step 1.
        // Pattern: ClassName::class => ['source_context', 'aggregate_ref' fn, 'payload' fn, 'occurred_at' fn]
    ];

    public function handle(object $event): void
    {
        $eventClass = get_class($event);

        if (!isset($this->captureMap[$eventClass])) {
            return;
        }

        $config = $this->captureMap[$eventClass];

        $captured = CapturedDomainEvent::capture(
            sourceContext:      $config['source_context'],
            aggregateReference: ($config['aggregate_ref'])($event),
            eventType:          class_basename($eventClass),
            payload:            ($config['payload'])($event),
            occurredAt:         ($config['occurred_at'])($event),
        );

        DiscoveryEventStore::fromCapture($captured)->save();
    }
}
```

- [ ] **Step 3: Commit**

```bash
git add app/Contexts/Evidence/Infrastructure/Discovery/EvidenceCaptureAdapter.php
git commit -m "feat(evidence): add EvidenceCaptureAdapter — configurable event capture map, EVI-5 enforced in payload extractors"
```

---

### Task 7: Wire Capture Adapter

**Files:**
- Create: `app/Contexts/Evidence/EvidenceServiceProvider.php`
- Modify: `app/Providers/AppServiceProvider.php`
- Modify: `app/Providers/EventServiceProvider.php`

- [ ] **Step 1: Create EvidenceServiceProvider**

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Evidence;

use Illuminate\Support\ServiceProvider;

class EvidenceServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {}
}
```

- [ ] **Step 2: Register in AppServiceProvider**

Open `app/Providers/AppServiceProvider.php`, inside `register()`:

```php
$this->app->register(\App\Contexts\Evidence\EvidenceServiceProvider::class);
```

- [ ] **Step 3: Wire to events in EventServiceProvider**

Open `app/Providers/EventServiceProvider.php`. Note: `shouldDiscoverEvents()` returns `false` — all listeners must be registered here explicitly.

Add to the `$listen` array:

```php
\App\Domain\Election\Events\VoterAssignedToElection::class => [
    \App\Contexts\Evidence\Infrastructure\Discovery\EvidenceCaptureAdapter::class,
],
\App\Domain\Election\Events\VotingOpened::class => [
    \App\Contexts\Evidence\Infrastructure\Discovery\EvidenceCaptureAdapter::class,
],
```

- [ ] **Step 4: Commit**

```bash
git add app/Contexts/Evidence/EvidenceServiceProvider.php
git add app/Providers/AppServiceProvider.php
git add app/Providers/EventServiceProvider.php
git commit -m "feat(evidence): wire EvidenceCaptureAdapter to VoterAssignedToElection + VotingOpened"
```

---

### Task 8: Write Feature Test

**Files:**
- Create: `tests/Feature/Contexts/Evidence/EvidenceCaptureAdapterTest.php`

- [ ] **Step 1: Write the test**

```php
<?php

namespace Tests\Feature\Contexts\Evidence;

use App\Contexts\Evidence\Infrastructure\Discovery\DiscoveryEventStore;
use App\Domain\Election\Events\VoterAssignedToElection;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvidenceCaptureAdapterTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_assigned_event_is_captured(): void
    {
        $event = new VoterAssignedToElection(
            userId: 'user-uuid-001',
            electionId: 'election-uuid-001',
            organisationId: 'org-uuid-001',
            assignedBy: 'admin-uuid-001',
            occurredAt: new DateTimeImmutable('2026-06-01 10:00:00'),
        );

        event($event);

        $this->assertDatabaseHas('evidence_capture', [
            'source_context'      => 'Election',
            'aggregate_reference' => 'election:election-uuid-001',
            'event_type'          => 'VoterAssignedToElection',
        ]);
    }

    public function test_captured_payload_does_not_contain_user_id(): void
    {
        // EVI-5: voter identity must not be reconstructible from captured payload
        $event = new VoterAssignedToElection(
            userId: 'sensitive-user-id',
            electionId: 'election-uuid-001',
            organisationId: 'org-uuid-001',
            assignedBy: null,
            occurredAt: new DateTimeImmutable(),
        );

        event($event);

        $log = DiscoveryEventStore::where('event_type', 'VoterAssignedToElection')->first();
        $this->assertNotNull($log);
        $this->assertArrayNotHasKey('user_id', $log->payload);
        $this->assertArrayNotHasKey('email', $log->payload);
    }

    public function test_unregistered_event_is_silently_ignored(): void
    {
        // Adapter must not fail on events not in the capture map
        $unknownEvent = new class {};
        event($unknownEvent);

        $this->assertDatabaseCount('evidence_capture', 0);
    }

    public function test_voting_opened_event_is_captured(): void
    {
        $election = \App\Models\Election::factory()->create();

        event(new \App\Domain\Election\Events\VotingOpened(
            election: $election,
            openedBy: 'admin-uuid-001',
        ));

        $this->assertDatabaseHas('evidence_capture', [
            'source_context' => 'Election',
            'event_type'     => 'VotingOpened',
        ]);
    }
}
```

- [ ] **Step 2: Run tests — expect failure before wiring is complete**

```bash
php artisan test tests/Feature/Contexts/Evidence/EvidenceCaptureAdapterTest.php
```

Expected: FAIL (table missing if migration not run in test env)

- [ ] **Step 3: Run all tests to verify no regressions**

```bash
php artisan test
```

Expected: 4 new tests pass, no existing tests broken

- [ ] **Step 4: Commit**

```bash
git add tests/Feature/Contexts/Evidence/EvidenceCaptureAdapterTest.php
git commit -m "test(evidence): verify EvidenceCaptureAdapter + EVI-5 payload exclusion + silent ignore of unknown events"
```

---

## Verification

After Phase 0 and Phase 1 are complete:

**1. Documentation check:**
```bash
ls docs/architecture/contexts/
# EvidenceAggregateDiscovery.md — hypotheses + saturation criteria + VR-1..VR-5
# EvidenceEventTaxonomy.md     — observed vs target split
# EvidenceContext.md            — EVI-5 added (indirect re-identification prohibited)
```

**2. Schema check (no forbidden columns):**
```bash
php artisan tinker
# Schema::hasTable('evidence_capture')                     → true
# Schema::getColumnListing('evidence_capture') must NOT include: user_id, integrity_hash, sequence_number
```

**3. Adapter test:**
```bash
php artisan test tests/Feature/Contexts/Evidence/EvidenceCaptureAdapterTest.php --verbose
# 4 tests, all pass
```

**4. Full suite:**
```bash
php artisan test
# No regressions
```

**5. No Domain layer created — only Discovery instrumentation:**
```bash
ls app/Contexts/Evidence/
# Should show only: Infrastructure/, EvidenceServiceProvider.php
# Should NOT show: Domain/

ls app/Contexts/Evidence/Infrastructure/
# Should show only: Discovery/
# Should NOT show: Persistence/, Repositories/, Entities/

ls app/Contexts/Evidence/Infrastructure/Discovery/
# CapturedDomainEvent.php, DiscoveryEventStore.php, EvidenceCaptureAdapter.php
```

---

## What Comes After This Plan — Not In Scope

After scenario saturation criteria are met (see EvidenceAggregateDiscovery.md):

1. **DD.5 Scenario Analysis** — run scenario matrix against captured instrumentation data
2. **Invariant assignment** — assign each invariant to a confirmed owner
3. **Aggregate confirmation** — promote Hypothesis A/B to confirmed aggregates
4. **Domain layer** — only then create `app/Contexts/Evidence/Domain/`
5. **More event capture** — wire Phase 2 events from taxonomy
6. **EvidenceReconciliationService** — constitutional arithmetic (not DB constraints)
7. **Evidence Center UI** — after domain layer is confirmed
8. **Verification Context** — separate bounded context design, VR-1..VR-5

Do NOT build any of these until scenario saturation criteria are met.
