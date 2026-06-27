# Round 17 — Stream 3: Voting/Tally Relationship — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 3)

**Status:** Complete

**Scope:** Investigate the primary election processing path (voter → vote → recording → counting → result). Determine whether vote recording and result production are coupled or separated.

---

## 1. Investigation Scope

**Authorized by:** ARB Final Decision Record (Option B)

**Constraints:** Evidence collection only. No architecture recommendations, refactoring proposals, context maps, aggregate design, or redesign proposals.

---

## 2. Evidence Log

### E3-1: Vote Model Inheritance

**Artifact:** `BaseVote` (abstract) → `Vote` (real elections) / `DemoVote` (demo elections)

**Source:** `app/Models/BaseVote.php`, `app/Models/Vote.php`

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Vote data model with 60 candidate columns storing JSON selection data. No `user_id` column — votes are anonymous. Contains:
- `vote_hash`: SHA256 cryptographic proof
- `receipt_hash`: Voter self-verification
- `data_checksum`: SHA256 integrity checksum
- `participation_proof`: IP-based admin verification
- `device_fingerprint_hash`: Fraud detection

**Key Design (BaseVote.php lines 13-17):**
```
Implements "Verifiable Anonymity":
- Voters can verify their vote was recorded correctly
- Results remain completely anonymous
- NO user_id in database (votes are anonymous!)
```

---

### E3-2: Result Model Inheritance

**Artifact:** `BaseResult` (abstract) → `Result` (real elections) / `DemoResult` (demo elections)

**Source:** `app/Models/BaseResult.php`, `app/Models/Result.php`

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Result data model. Each row represents one candidate selection OR one abstention for one vote. Key columns:
- `vote_id` (FK to votes table)
- `candidacy_id` (nullable — null for no-vote/abstention rows)
- `post_id`
- `no_vote` (boolean — true when voter abstained for this post)

---

### E3-3: Results Migration

**Artifact:** `create_uuid_results_table.php` migration

**Source:** `database/migrations/2026_03_05_000012_create_uuid_results_table.php`

**Tier:** Tier 2 (Implementation Evidence)

**Description:** The results table stores individual vote selections, not aggregated counts. No `vote_count` or tally column in the original migration. `vote_count` column was added later via a `candidacy_id` nullable change and `no_vote` boolean migration.

**Original columns (lines 10-19):**
```php
Schema::create('results', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('organisation_id');
    $table->uuid('vote_id');
    $table->uuid('election_id');
    $table->uuid('candidacy_id');
    $table->uuid('post_id');
    $table->integer('position_order')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

---

### E3-4: Result Creation on Vote Save (Core Coupling)

**Artifact:** `BaseVote.booted()` → `saved` event → `createResultsFromCandidates()`

**Source:** `app/Models/BaseVote.php` lines 127-131, `app/Models/Vote.php` lines 61-122

**Tier:** Tier 2 (Implementation Evidence) — Tier 1 (Behavioral: auto-execution)

**Description:** When a Vote is saved, the BaseVote `saved` event handler automatically calls `createResultsFromCandidates()`. This means **every vote creation immediately creates Result records** in the same request lifecycle.

**Code (BaseVote.php lines 127-131):**
```php
static::saved(function ($vote) {
    if ($vote instanceof Vote) {
        $vote->createResultsFromCandidates();
    }
});
```

**Result creation logic (Vote.php lines 61-122):**
- Iterates candidate_01 through candidate_60
- Decodes JSON to extract post_id, candidacy_id, no_vote flag
- Creates Result record per candidate selection or abstention
- Deletes existing results first (idempotent — safe if called multiple times)
- Recalculates and saves data_checksum after creation

---

### E3-5: Result Sync (Manual Re-creation)

**Artifact:** `Vote.syncResults()`

**Source:** `app/Models/Vote.php` lines 260-271

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Results can be manually regenerated from the JSON source of truth (candidate columns). This is idempotent — deletes all existing results and recreates them.

**Code (Vote.php lines 260-271):**
```php
public function syncResults(): void
{
    Result::where('vote_id', $this->id)->forceDelete();
    $this->createResultsFromCandidates();
    $this->update(['results_last_synced_at' => now()]);
}
```

**Implication:** The JSON candidate data is the source of truth. Results are a derived projection. They can be reconstructed at any time from the source vote data.

---

### E3-6: Integrity Verification

**Artifact:** `Vote.verifyResultsIntegrity()`, `Vote.calculateChecksum()`, `Vote.verifyChecksum()`

**Source:** `app/Models/Vote.php` lines 181-252

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Each vote has a SHA256 `data_checksum` that verifies candidate data has not been modified. The system can compare stored Result count against expected count derived from JSON parsing.

**Integrity check (Vote.php lines 241-252):**
```php
public function verifyResultsIntegrity(): array
{
    $storedCount = Result::withoutGlobalScopes()->where('vote_id', $this->id)->count();
    $expectedCount = $this->getExpectedResultCount();
    return [
        'is_valid' => $storedCount === $expectedCount && $this->verifyChecksum(),
        'stored_count' => $storedCount,
        'expected_count' => $expectedCount,
        'checksum_valid' => $this->verifyChecksum(),
    ];
}
```

---

### E3-7: Vote Count Computation

**Artifact:** `ResultController.index()`

**Source:** `app/Http/Controllers/ResultController.php` lines 18-75

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Vote counts are computed on-demand by querying the results table with SQL aggregation. No separate tally table or materialized view.

**Count query (ResultController.php lines 32-38):**
```php
$voteCounts = DB::table('results')
    ->where('results.election_id', $election->id)
    ->whereNotNull('results.candidacy_id')
    ->select('results.post_id', 'results.candidacy_id', DB::raw('COUNT(*) as vote_count'))
    ->groupBy('results.post_id', 'results.candidacy_id')
    ->get()
    ->groupBy('post_id');
```

---

### E3-8: Denormalized votes_count on Election Model

**Artifact:** `BaseVote.saved()` event syncs `votes_count`

**Source:** `app/Models/BaseVote.php` lines 234-244

**Tier:** Tier 2 (Implementation Evidence)

**Description:** After each vote is saved, a denormalized `votes_count` column on the elections table is updated with a raw `COUNT(*)` query. This provides fast access to total vote count without querying the votes table.

---

### E3-9: State Machine "Counting" State

**Artifact:** `ElectionConstitution.RULES` — `close_voting` → `counting` → `publish_results`

**Source:** `app/Domain/Election/Constitution/ElectionConstitution.php` (from Stream 5)

**Tier:** Tier 2 (Implementation Evidence)

**Description:** The state machine defines:
- `close_voting`: allowed_states = ['voting_active'], target_state = 'counting'
- `publish_results`: allowed_states = ['counting'], allowed_roles = ['chief']

However, no separate "counting" service or batch process was observed. Vote → Result creation happens immediately at vote submission time, not when the election enters the "counting" state.

---

### E3-10: Results Publication Gate

**Artifact:** `ResultController.index()` checks `results_published`

**Source:** `app/Http/Controllers/ResultController.php` lines 23-25

**Tier:** Tier 2 (Implementation Evidence)

**Description:** Results are computed and available in the results table as soon as votes are cast, but they are hidden from public view until `publish_results` transition occurs. The `results_published` flag gates visibility, not availability.

**Code (ResultController.php lines 23-25):**
```php
if (! $election->results_published) {
    abort(403, 'Election results have not been published yet.');
}
```

---

## 3. Relationship Inventory

### Relationship 1: Vote → Result Creation (COUPLED)

**Observed:** Results are created synchronously when a Vote is saved, via the BaseVote `saved` event handler. No delay, no queue, no batch process.

**Source:** BaseVote.php:127-131, Vote.php:61-122

**Tier:** Tier 2 (Implementation) / Tier 1 (Runtime behavior)

**Evidence:** `static::saved()` → `$vote->createResultsFromCandidates()` fires in same request lifecycle.

---

### Relationship 2: Vote → Result Regeneration (DERIVED)

**Observed:** Results are a derived projection of vote candidate data. They can be deleted and regenerated from JSON source at any time via `syncResults()`.

**Source:** Vote.php:260-271

**Tier:** Tier 2 (Implementation)

**Evidence:** `syncResults()` deletes all results and recreates them from the same 60 candidate columns.

**Implication:** The results are not independent data — they are a denormalized representation of the vote. The vote is the source of truth.

---

### Relationship 3: Vote → Checksum (INTEGRITY)

**Observed:** Each vote has a SHA256 checksum that verifies candidate data integrity. Checksum includes `config('app.key')` as pepper.

**Source:** Vote.php:181-203

**Tier:** Tier 2 (Implementation)

---

### Relationship 4: Result → Count (COMPUTED)

**Observed:** Vote counts are computed on-demand by SQL aggregation (COUNT + GROUP BY) on the results table. No pre-computed tally or materialized view.

**Source:** ResultController.php:32-38

**Tier:** Tier 2 (Implementation)

---

### Relationship 5: State Machine → Publication (GATE)

**Observed:** Results exist in database immediately but are hidden until `results_published` flag is set. The "counting" state exists in state machine but no counting logic runs at state transition.

**Source:** ResultController.php:23-25, ElectionConstitution.php (Stream 5)

**Tier:** Tier 2 (Implementation)

---

## 4. Observed Process Flow

```
Voter submits ballot
    ↓
VoteController receives selections
    ↓
Vote model created with candidate_01..60 JSON
    ↓
Vote.saved() event fires
    ↓
createResultsFromCandidates() called
    ↓
Result rows created (one per candidate + abstention)
    ↓
data_checksum updated
    ↓
votes_count denormalized on election
    ↓
Results available immediately in database
    ↓
[Hidden until results_published = true]
    ↓
Chief publishes results (state machine transition)
    ↓
ResultController query: COUNT(*) GROUP BY candidacy_id
    ↓
Results displayed to voters
```

---

## 5. Observed Election Integrity Relationships

*(These are observations — not conclusions about whether guarantees are satisfied. Each artifact may relate to one or more election integrity concerns. The relationship is undetermined.)*

| Artifact | Observed In | May Relate To | Evidence |
|----------|-------------|---------------|----------|
| `vote_hash` | BaseVote | Verifiability, Uniqueness | SHA256 hash, unique constraint on votes table |
| `receipt_hash` | BaseVote | Verifiability, Auditability | Voter self-verification without exposing vote choice (BaseVote.php:291-294) |
| `data_checksum` | BaseVote | Integrity, Tamper Detection | SHA256 over candidate data + app.key (Vote.php:181-203) |
| `participation_proof` | BaseVote | Participation Integrity | IP-based admin verification without revealing vote (BaseVote.php:306-310) |
| `device_fingerprint_hash` | BaseVote | Uniqueness, Fraud Detection | Duplicate device detection (Vote.php:154-159) |
| `no_vote_option` | BaseVote | Eligibility Integrity | Abstention tracking |
| `encrypted_vote` | BaseVote | Secrecy, Verifiability | Encrypted vote data for voter verification |
| `syncResults()` | Vote | Recoverability, Auditability | Results regenerable from source of truth (Vote.php:260-271) |
| `verifyResultsIntegrity()` | Vote | Count Integrity, Auditability | Stored count vs expected, checksum validation (Vote.php:241-252) |

---

## 6. Hypothesis Updates

### H7: Voting and Tallying are Operationally Coupled

**Evidence Added (Tier 1, 2):**
- Vote saved event → createResultsFromCandidates() fires immediately
- No separate counting process, queue, or batch observed within examined implementation
- Results are a synchronous derived projection
- Results can be regenerated at any time from vote JSON

**Updated Status:** **Strengthening**

**Rationale:** Implementation-level coupling observed between vote persistence and result generation. Whether voting and tallying are distinct domain concerns remains unresolved.

---

### H8: Voting and Tallying are Distinct Operational Concerns

**Evidence Added (Tier 2):**
- State machine has explicit "counting" state
- ResultController checks `results_published` flag for visibility gating
- Results table exists as separate table from votes

**Updated Status:** **Weakening**

**Rationale:** Despite separate table and state, the operational flow within examined implementation shows no separation. Results are created as a synchronous side-effect of vote saving. "Counting" in the state machine controls publication visibility within examined scope, not computation.

---

### H9: Real-Time Coupling May Relate to Operational or Integrity Concerns

**Evidence Added (Tier 2):**
- Data checksum (SHA256) validates vote integrity
- Result counts can be verified against expected counts derived from JSON
- Results can be regenerated from source of truth
- No separate tally process that could introduce errors

**Updated Status:** **Open** (reframed)

**Rationale:** The coupling provides integrity benefits (single source of truth, no tally drift, verifiable reconciliation). Whether this was the design intent or an implementation convenience remains unresolved.

---

## 6. Design-Relevant Discovery Notes (Deferred)

*(Observations from evidence only. Recorded for potential future relevance. Design implications remain deferred until bounded-context discovery and tactical design phases.)*

### DRO-1: Result is a Derived Projection

Results are a denormalized representation of the candidate JSON in the vote. The vote is the authoritative source. Results can be regenerated at any time. May influence future discussions regarding reconstruction, projection strategies, or verification capabilities.

Design implications remain deferred until bounded-context discovery and tactical design phases.

### DRO-2: No Counting Process Observed

No counting process was observed within the examined implementation. The `close_voting` → `counting` transition controls when voting ends. May influence future discussions about whether counting is a distinct business activity or a publication gate.

Design implications remain deferred until bounded-context discovery and tactical design phases.

### DRO-3: Count is Computational

Vote tallies are computed on-demand via SQL aggregation on the results table. No stored tally or materialized view was observed. May influence future discussions regarding performance, scalability, or result verification strategies.

Design implications remain deferred until bounded-context discovery and tactical design phases.

### DRO-4: Vote Data as Authoritative Source

Vote data appears to be the authoritative source for result data within the examined implementation. Results can be reconstructed from votes, and integrity is verified via checksum. May influence future discussions regarding consistency guarantees, recovery procedures, or audit capabilities.

Design implications remain deferred until bounded-context discovery and tactical design phases.

---

## 7. Discovery Debt Updates

### D39 (New): What Is the Domain Meaning of the Counting State?

**Question:** The state machine defines `close_voting` → `counting` → `publish_results`, but no counting logic was observed executing at the `counting` state transition. Does the counting state represent a manual verification period, a legal waiting window, a challenge period, a publication gate, a placeholder for future implementation, or another domain concept?

**Priority:** MEDIUM

**Recommended Discovery:** Investigate whether any code runs specifically when elections enter the "counting" state, or whether counting is entirely implicit in the vote → result coupling.

---

### D40 (New): Why Are Results Generated During Vote Persistence?

**Question:** Results are created synchronously during the vote save lifecycle event. What drove this design choice? Why are results generated at vote persistence time rather than at another point in the election lifecycle?

**Priority:** LOW

**Recommended Discovery:** Architect interview on the rationale for temporal coupling between vote recording and result generation.

---

### D41 (New): What Prevents Result Drift Over Time?

**Question:** Results can be regenerated from vote JSON at any time via `syncResults()`. But what ensures that the stored results table is consistent with the vote source of truth in production? Is there any periodic reconciliation?

**Priority:** MEDIUM

**Recommended Discovery:** Investigate whether scheduled integrity checks run in production.

---

### D42 (New): What Election Integrity Guarantees Are Explicitly Intended by the System?

**Question:** Stream 3 has observed multiple integrity-related artifacts: vote_hash, receipt_hash, data_checksum, participation_proof, device_fingerprint_hash, encrypted_vote, and verifyResultsIntegrity(). These indicate election-integrity concerns, but the specific guarantees they were designed to provide remain unresolved. What election integrity guarantees (verifiability, eligibility, integrity, auditability, non-repudiation, recoverability) is the system explicitly intended to support?

**Priority:** HIGH STRATEGIC

**Rationale:** Integrity artifacts now clearly exist at the implementation level. Without understanding the intended guarantees, design decisions about future integrity features cannot be evaluated against domain requirements.

**Recommended Discovery:** Architect interview on design intent for election integrity mechanisms.

---

## 8. Summary of Stream 3 Findings

**Core Finding:** Vote recording and result production are operationally coupled within the examined implementation. Results are created as a synchronous side-effect of vote saving. No separate counting process was observed within the examined implementation scope.

### Key Characteristics

| Characteristic | Observed | Evidence |
|---------------|----------|----------|
| Vote → Result coupling | ✅ Yes — synchronous, same transaction | BaseVote.saved() → createResultsFromCandidates() |
| Separate counting process | ❌ Not observed within examined scope | No counting service, batch, queue, or job found in examined implementation |
| Results derived from votes | ✅ Yes — can be regenerated | syncResults() deletes and recreates |
| Vote data appears authoritative | ✅ Yes — results are a projection | Results regenerable from vote JSON columns |
| Integrity verification | ✅ Yes — SHA256 checksum | calculateChecksum() + verifyChecksum() |
| Count computation | On-demand SQL aggregation | ResultController GROUP BY query |
| Publication gating | ✅ Yes — results_published flag | Hides results until chief publishes |
| State "counting" | ✅ Exists but no logic | Controls state transition, not computation |

### Hypotheses

| ID | Status Change | Current Status |
|----|--------------|----------------|
| H7 | Strengthening | Operation is coupled (confirmed) |
| H8 | Weakening | Distinct operational concerns not supported |
| H9 | Open (reframed) | Coupling may relate to integrity concerns |

### Design-Relevant Observations (Deferred)

1. Result is a derived projection of vote data
2. No actual "counting" process — results computed immediately at vote time
3. Count is computed via on-demand SQL aggregation, not stored
4. Vote data as authoritative source with verifiable integrity

### Discovery Debt

| ID | Priority | Question |
|----|----------|----------|
| D39 | MEDIUM | Is the "counting" state a placeholder? |
| D40 | LOW | Why synchronous rather than async? |
| D41 | MEDIUM | What prevents result drift over time? |

---

**Stream 3 Status: COMPLETE — READY FOR ARB CHECKPOINT REVIEW**

**Hypothesis Register and Discovery Debt Register updated.**
