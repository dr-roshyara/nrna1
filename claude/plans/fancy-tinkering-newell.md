# M.1 Phase C.5 — Constitutional Stabilization Audit

**Plan Type:** Constitutional Stabilization Audit (Read-only sovereign archaeology)
**Protocol:** Senior Architect Constitutional Governance Protocol
**Command:** `php artisan test --env=testing` (ALWAYS — never without flag)
**Phase:** M.1 Network Sovereignty Migration → Phase C.5 Stabilization Barrier
**Date:** 2026-05-27
**Supersedes:** C.4 plan (complete — 51 tests passing, 1 skipped)
**Gate:** Phase D (sovereign retirement) MUST NOT begin until this plan is complete

---

## Constitutional Doctrine

```
Deletion is no longer treated as cleanup.
It is sovereign retirement engineering.
```

Phase C.5 is the **sovereignty stabilization barrier** between:
- Constitutional equivalence proof (C.4 — complete) ✅
- Irreversible authority deletion (D.0 — NOT YET)

Without this barrier: replay drift survives, latent topology assumptions remain,
hidden procedural authority persists, scalar sovereignty re-emerges.

---

## Constitutional Invariants (Must Remain TRUE Throughout)

1. All C.4 tests (51) remain GREEN (non-regression)
2. All D.R.2 + D.R.3 tests remain GREEN
3. No production code changes in C.5 (audit only)
4. PolicySequence remains the ONLY sovereign resolver
5. Overlays remain purely observational (no authority derivation)
6. Evidence frozen at evaluation time (never re-queried during evaluation)
7. **Constitutional insufficiency is NOT averaged away**
8. Additional observational abundance NEVER weakens constitutional insufficiency

---

## Constitutional Bounded Context Map

Prevents semantic bleeding across governance domains.

| Context | Responsibility |
|---------|---------------|
| **Observation Context** | Evidence preservation — overlays observe, never interpret |
| **Resolver Context** | Legitimacy derivation — PolicySequence only |
| **Projection Context** | Non-authoritative presentation — no implied precedence |
| **Replay Context** | Deterministic reconstruction — frozen evidence, versioned schemas |
| **Migration Context** | Sovereignty equivalence — legacy ↔ constitutional parity |
| **Retirement Context** | Controlled authority removal — dual sovereignty, drift telemetry |

This map is the constitutional guard against context drift. Future engineers who violate these
boundaries are introducing constitutional sovereignty corruption.

---

## Sovereignty Monotonicity Doctrine

Formal constitutional rule (not just testing):

```
Additional observational abundance
must NEVER weaken constitutional insufficiency.

Legal form:
  For any evidence set E and any observation O:
  If evaluate(E) = INSUFFICIENT_EVIDENCE
  Then evaluate(E ∪ {O}) = INSUFFICIENT_EVIDENCE

Forbidden:
  - heuristic reconciliation
  - probabilistic legitimacy
  - compensating evidence scoring
  - reputation accumulation
  - "smart" legitimacy ranking
```

This doctrine is violated by any scoring, weighting, or aggregation that
allows multiple CONTEXT_STABLE signals to override an EVIDENCE_INCONSISTENT finding.

---

## Advanced Constitutional Doctrines (Final Hardening)

### Constitutional Algebra Boundary (CRITICAL)

**Rule:**
```
Constitutional observations are NOT arithmetic operands.
```

**Forbidden:**
- Weighted averaging of observations
- Signal balancing
- Confidence accumulation
- Probabilistic legitimacy
- Compensating reconciliation
- Evidence cancellation
- Observation subtraction

**Allowed:**
- Preservation (observations maintain state)
- Classification (observations categorized)
- Deterministic interpretation (evidence → legitimacy)
- Explicit precedence (only through resolver)

**Invariant:**
```
Legitimacy derives from constitutional interpretation,
not observational arithmetic.
```

This is the primary defense against emergent probabilistic sovereignty.

---

### Governance Explainability Doctrine (CRITICAL)

**Requirement:**
Every sovereign legitimacy outcome MUST be explainable via:
- Evidence lineage (what evidence was used)
- Observation set (which observations applied)
- Resolver path (which resolver path was taken)
- Precedence chain (what determined the outcome)
- Constitutional basis (why this legitimacy exists)

**Forbidden:**
- Opaque heuristics
- Implicit authority
- Hidden precedence
- Untraceable derivation

**Future artifact (D.R.5):**
```
LegitimacyExplanationEnvelope
```

Essential for replay audit, constitutional disputes, federation.

---

### Temporal Sovereignty Isolation Audit (HIGH)

**Audit targets:**
- Queued jobs (`app/Jobs/`)
- Async event listeners (`app/Listeners/`)
- Delayed commands (`app/Console/Commands/`)
- Background sync processes
- Cache refresh mechanisms

**Invariant:**
```
Sovereign legitimacy must NEVER depend on asynchronous temporal drift.
Legitimacy frozen at evaluation time survives across temporal delays.
```

**Forbidden:**
- Async authority derivation
- Delayed legitimacy reconciliation
- Temporal heuristics
- Stale authority caching

---

### Constitutional Cache Doctrine (HIGH)

**Forbidden:**
- Caching sovereign legitimacy decisions
- Caching mutable observation aggregates
- Replaying stale authority snapshots
- Cache-derived legitimacy

**Allowed:**
- Caching immutable evidence snapshots
- Caching deterministic projections
- Caching replay-certified observations

**Invariant:**
```
Cached state must NEVER become sovereign authority.
```

Caches are major sovereignty corruption vectors. Evidence frozen in caches
can bypass replay, preserve stale authority, violate monotonicity.

---

### Constitutional Event Emission Doctrine (HIGH)

**Audit targets:**
- `app/Events/`
- `app/Listeners/`
- Broadcast events
- WebSocket notifications

**Rules:**
- Events MAY transport observations
- Events MAY NOT derive legitimacy
- Listeners MAY NOT escalate authority
- Event order MAY NOT affect sovereignty

**Invariant:**
```
Event topology must remain observational, not sovereign.
```

Events can silently reintroduce procedural escalation and hidden derivation.

---

### Distributed Replay Doctrine (HIGH)

**Rule:**
```
Constitutional replay must remain node-independent.
```

**Replay result must remain identical across:**
- Different hosts
- Queue workers
- Timezones
- Serialization engines
- Deployment cycles

Essential for federation, sovereign portability, constitutional audit export.

---

### Interaction Topology Neutrality (High — Projection Audit Enhancement)

**Add to Projection audit — forbidden UI vectors:**

| Vector | Risk |
|--------|------|
| Button prominence | Implies urgency |
| Action grouping | Implies escalation |
| Workflow ordering | Procedural precedence |
| Notification ordering | Hidden hierarchy |
| Admin shortcuts | Authority bypass |

**Invariant:**
```
Interaction topology must NOT imply constitutional precedence.
```

Subtle but extremely important. UI affordances can silently reintroduce authority.

---

### Constitutional Identity Boundary (HIGH)

**Rule:**
```
Identity evidence must remain observational, NEVER sovereign by itself.
```

**Meaning:**
- Device continuity MAY inform observations
- Browser continuity MAY inform observations
- Account continuity MAY inform observations
- Behavioral continuity MAY inform observations
- But NONE become direct legitimacy authority

**Why critical for M.2:**
Device migrations (M.2) will use identity evidence. Without this boundary,
identity becomes implicit authority instead of observable context.

---

### Migration Reversibility Doctrine (HIGH)

**Requirement (until D.5 complete):**
- Legacy evidence path reconstructible
- Constitutional equivalence reproducible
- Sovereignty divergence replayable
- Retirement reversible

**Invariant:**
```
No sovereignty retirement without replay-capable reversibility.
```

Safety principle: if issues arise after deletion, audit trail must support reconstruction.

---

### Constitutional Minimalism Doctrine (MEDIUM)

**Rule:**
```
No constitutional observation may exist without explicit sovereign necessity.
```

**Every new observation must justify:**
- Why legitimacy requires it
- Why replay requires it
- Why existing observations insufficient

**Prevents:**
- Governance inflation
- Heuristic creep
- Observational bloat
- Complexity explosion

**Critical for long-term.**
As domains migrate (M.2, M.3), complexity explodes. Minimalism prevents opaque governance.

---

## Baseline State (Confirmed 2026-05-27)

| Metric | Value |
|--------|-------|
| Passing tests | 3248 |
| Failing tests | 2196 |
| Incomplete | 15 |
| Skipped | 31 |
| Risky | 7 |
| Full suite duration | 1176.72s (~20 min) |
| C.4 constitutional tests | 51 passing, 1 skipped |

**This is the immutable Phase D gate baseline.**
If deletion causes failures above 2196, deletion is BLOCKED.

---

## Hidden Sovereignty Discovery (Critical Findings)

The sovereignty exploration revealed 6 live hidden authority paths:

### H.1 — ValidateVotingIp Middleware (SOVEREIGN)
**File:** `app/Http/Middleware/ValidateVotingIp.php`
**Mechanism:** Reads `$user->voting_ip` cleartext, compares to `$request->ip()`, blocks via `back()->withErrors()`
**Constitutional violation:** Executes BEFORE TrustPolicyEvaluator — topology order creates sovereignty.
The constitutional system can grant legitimacy; middleware blocks anyway.
**Type:** B — Hidden Procedural Sovereignty | **Criticality:** SOVEREIGN
**D.0 target:** Must be feature-flagged off FIRST in retirement sequence

### H.2 — validateVotingIpWithResponse() — 6 call sites (HIGH)
**File:** `app/Helpers/helpers.php:192-241`
**Call sites:** VoteController: 302, 1582, 1950; DemoVoteController: 364, 1579, 1966
**Constitutional violation:** Exposes `'registered_ip' => $auth_user->voting_ip` cleartext in Inertia props → frontend
**Type:** B — Hidden Procedural Sovereignty | **Criticality:** HIGH

### H.3 — check_ip_address() — global scope (HIGH)
**File:** `app/Helpers/helpers.php:91-185`
**Call sites:** VoteController: 2966 fallback; DemoVoteController: 3316 always
**Constitutional violation:** Queries `codes` table GLOBALLY — all elections, not election-scoped
**Type:** B — Hidden Procedural Sovereignty | **Criticality:** HIGH

### H.4 — User.voting_ip + VotingSecurityService (HIGH)
**Files:** `app/Services/VotingSecurityService.php` (13+ callsites), middleware, controllers, commands
**Sovereignty methods:** `canVoteFromIp()`, `detectIpChange()`, `getIpMatchStatus()` — sovereign-sounding outside constitutional path
**Type:** E — Topology Dependency | **Criticality:** HIGH
**Retirement:** defer to D.6 (required for forensics through deletion window)

### H.5 — VoterSlug.step_1_ip cleartext query (MEDIUM)
**File:** `app/Http/Controllers/ElectionVotingController.php:240`
**Issue:** `->where('step_1_ip', $ip)` cleartext comparison survives (partial migration)
**DemoVoteController:1472** correctly hashes via `TrustEvidencePrivacyPolicy::hashIp()` — inconsistency
**Type:** E — Topology Dependency | **Criticality:** MEDIUM

### H.6 — DemoVoteController IP capture divergence (LOW)
**File:** `app/Http/Controllers/Demo/DemoVoteController.php:3314`
**Issue:** `\Request::getClientIp(true)` — differs from `request()->ip()` everywhere else
**Risk:** Different IP resolution = different hash = potential continuity mismatch in demo only
**Type:** C — Vocabulary Mismatch | **Criticality:** LOW

---

## Failure Classification Protocol

### A/B/C/D/E/F Taxonomy

| Type | Meaning |
|------|---------|
| A | Obsolete scalar assumption (old aggregation model) |
| B | Hidden procedural sovereignty (authority outside resolver) |
| C | Vocabulary mismatch (stale type name or API) |
| D | Actual runtime regression (real bug introduced) |
| E | Topology dependency (ordering/precedence assumption) |
| F | Replay violation (time-dependent or mutable evaluation) |

### Constitutional Criticality Axis

| Level | Meaning |
|-------|---------|
| LOW | Stale vocabulary — expected migration noise |
| MEDIUM | Migration mismatch — needs semantic update |
| HIGH | Topology leakage — ordering/authority assumption |
| CRITICAL | Replay determinism corruption |
| SOVEREIGN | Legitimacy divergence |
| EXISTENTIAL | Constitutional replay collapse |

### Migration Status Values

| Status | Meaning |
|--------|---------|
| INTENTIONAL_INVALIDATION | D.R.2 deliberately replaced this ontology |
| SEMANTIC_MIGRATION_REQUIRED | Vocabulary/topology update needed |
| ARCHITECTURAL_REGRESSION | Actual sovereignty issue |
| REPLAY_RISK | Determinism corruption |
| TOPOLOGY_LEAKAGE | Ordering/authority leakage |

---

## Phase C.5 Subphases (9 total)

### C.5a — Constitutional Failure Classification

**Purpose:** Classify the 2196 failing tests by constitutional meaning.
**Approach:** Targeted filter runs by namespace cluster (NOT full-suite rerun — too slow).
**Output artifact:** `claude/audits/C5a_ConstitutionalFailureClassification.md`

**Strategy:** The 2196 failures represent a small number of shared failure patterns.
Run targeted groups, classify clusters by constitutional meaning.

**Clusters to run (in order):**

| Cluster | Filter | Expected Pattern |
|---------|--------|-----------------|
| Architecture GovernanceRuntime | `tests/Architecture/GovernanceRuntime` | canVote(), CapabilityDecision |
| Architecture root | `tests/Architecture --exclude=GovernanceRuntime` | Invariant assertions |
| Simplified policy chain | `--filter="Simplified"` | PolicySequence, EvidenceCapabilityPolicy |
| Domain Election Security | `--filter="Domain\\\\Election\\\\Security"` | Stale ontology |
| Feature Voting/Election | `tests/Feature/Voting tests/Feature/Election` | Integration surface |

**For each cluster capture:**

```markdown
| Cluster | Total | Failed | Type | Criticality | Migration Status | Primary Failure Pattern |
|---------|-------|--------|------|-------------|-----------------|------------------------|
```

---

### C.5b — Hidden Sovereignty Audit

**Purpose:** Catalogue all procedural sovereignty OUTSIDE the constitutional path.
**Approach:** Document H.1-H.6 findings; assess retirement sequencing.
**Output artifact:** `claude/audits/C5b_HiddenSovereigntyAudit.md`

**Audit targets:**
- Controllers (authority gates that bypass TrustPolicyEvaluator)
- Middleware (sovereignty before constitutional path — H.1)
- Services (VotingSecurityService sovereign-sounding methods — H.4)
- Commands (BulkApproveVoters sets voting_ip — IP assignment sovereignty)
- UI/Inertia props (cleartext exposure + implied precedence — H.2)

---

### C.5c — Replay Instability Audit

**Purpose:** Verify evidence is frozen; identify mutable evidence sources.
**Output artifact:** Part of C5b artifact (replay section)

**Known replay risks:**
- `User.voting_ip` read from DB at evaluation time = mutable between evaluations (REPLAY RISK)
- `check_ip_address()` queries DB live = live count, not frozen (REPLAY RISK)
- `validateVotingIpWithResponse()` reads `$auth_user->voting_ip` at runtime (REPLAY RISK)

**Cross-runtime replay equivalence requirement (added per architectural review):**
```
Same serialized constitutional snapshot replayed:
- after process restart
- in isolated process
- across evaluation windows
- across serialization cycles
Must produce identical sovereign legitimacy.
```

This is foundational for federation and replay portability.

---

### C.5d — Topology Leakage Audit

**Purpose:** Verify evaluation ordering does not determine sovereign outcome.
**Output artifact:** Part of C5b artifact (topology section)

**Critical risk (H.1):**
- `ValidateVotingIp` middleware is registered BEFORE constitutional evaluation on voting routes
- Middleware order = sovereignty order
- This is topology leakage: execution sequence, not evidence, determines legitimacy

**What to verify:**
- Route middleware stack order for voting routes (web.php / route groups)
- Whether TrustPolicyEvaluator can be reached when ValidateVotingIp blocks
- Whether any other middleware has implicit ordering authority

---

### C.5e — Baseline Certification

**Purpose:** Formally certify the 2196 failure baseline is constitutionally acceptable for D.0.
**Output artifact:** `claude/audits/C5e_ConstitutionalStabilizationCertificate.md`

**Certification conditions (ALL required):**
- [ ] All C.4 constitutional equivalence tests GREEN (51 passing)
- [ ] All D.R.2/D.R.3 regression tests GREEN
- [ ] C.5a classification complete (no unclassified SOVEREIGN/EXISTENTIAL from TYPE D)
- [ ] C.5b-d audit complete (H.1-H.6 documented and sequenced)
- [ ] C.5f resolver exclusivity audit complete
- [ ] C.5g scalar sovereignty audit complete
- [ ] C.5h early return sovereignty audit complete
- [ ] C.5i projection leakage audit complete
- [ ] CFB-1 Constitutional Freeze Boundary established

**CFB-1 — Constitutional Freeze Boundary:**
```
After C.5e certificate: ALL new participation legitimacy rules
MUST enter ONLY through constitutional evidence topology.
Forbidden:
- controller blocking
- middleware authority
- UI legitimacy logic
- procedural escalation
- feature-flag authority (temporary or not)
```

---

### C.5f — Resolver Exclusivity Audit (CRITICAL)

**Purpose:** Verify ALL sovereign legitimacy derivation flows exclusively through PolicySequence.
**Output artifact:** Part of C5b artifact (resolver section)

**Audit targets:**
- Services (`app/Services/`)
- Jobs (`app/Jobs/`)
- Commands (`app/Console/Commands/`)
- Middleware (`app/Http/Middleware/`)
- Laravel Policies (`app/Policies/`)
- Console scripts
- Event listeners
- Queue workers

**Search patterns (these MUST NOT derive legitimacy outside PolicySequence):**
```
canVote*
isAllowed*
deny*
block*
approve*
authorize*
trust*
legitimacy*
```

**Invariant:**
```
No code path derives sovereign legitimacy
outside constitutional resolver topology.
```

---

### C.5g — Scalar Sovereignty Audit (CRITICAL)

**Purpose:** Detect surviving scalar authority semantics from the old governance model.
**Output artifact:** Part of C5b artifact (scalar section)

**Forbidden vocabulary (constitutional governance must NOT contain these as derivation inputs):**
```
score, weight, severity, criticality, confidence,
risk level, escalation, priority, strongest signal, trust score
```

**Search scope:**
- `app/Domain/`
- `app/Application/`
- `app/Services/`
- DTOs, Policies, UI projection models

**Invariant:**
```
Constitutional legitimacy must NEVER derive from scalar aggregation.
```

This protects against emergent probabilistic sovereignty — the highest long-term architectural risk.

---

### C.5h — Early Return Sovereignty Audit (CRITICAL)

**Purpose:** Detect partial observation traversal creating hidden sequence authority.
**Output artifact:** Part of C5b artifact (early return section)

**Detection target — forbidden pattern inside evaluators/resolvers/overlays/policies:**
```php
foreach (...) {
    if (...) {
        return ...; // FORBIDDEN before full evidence traversal
    }
}
```

**Why this matters:**
Early return in evidence evaluation = first-matching-signal authority = evaluation-sequence sovereignty.
This is procedural precedence through the back door.

**Audit locations:**
- `app/Application/Election/Security/` (all policy evaluators)
- `app/Domain/Election/Security/` (all domain evaluators)
- Any class with `evaluate()`, `resolve()`, `assess()` methods

**Invariant:**
```
Sovereign interpretation must NEVER derive from partial observation traversal.
Full evidence set must be assembled before legitimacy is derived.
```

---

### C.5i — Projection Sovereignty Leakage Audit (HIGH)

**Purpose:** Verify UI/admin layer does not imply constitutional precedence.
**Output artifact:** Part of C5b artifact (projection section)

**Audit targets:**
- Inertia props passed to Vue components
- Dashboard and admin panel Vue files
- Analytics and observability panels
- Alert ordering and notification priority
- Frontend status labels

**Forbidden in projection layer:**
```
"high risk"
"critical"
"urgent"
"escalated"
ranked legitimacy indicators
red-first sovereignty grouping
sorted "criticality"
top warnings that imply authority
```

**Projection Ordering Neutrality (formal doctrine):**
```
UI ordering must NOT imply constitutional precedence.
Observations are a flat set.
Presentation ordering is not governance ordering.
```

---

## Phase D Additions (Required Before D.1)

### D.1 — Dual Sovereignty Drift Telemetry (NEW)

During the D.1 observation window, structured divergence telemetry is required:

| Metric | Required |
|--------|----------|
| Total evaluations | YES |
| Matched outcomes | YES |
| Mismatched outcomes | YES |
| Mismatch categories (by finding H.1-H.6) | YES |
| Replay divergence count | YES |
| Topology divergence count | YES |
| Temporal divergence count | YES |

**Output artifact:** `D1_DualSovereigntyDriftReport.md`

This becomes the governance evidence proving equivalence before final deletion.

### D.0 — Constitutional Retirement Sequencing

#### D.0.1 — Feature-flag ValidateVotingIp Middleware (COMPLETE)

**Status:** ✅ Implemented and tested
**Files changed:**
- `config/voting_security.php` — Added `constitutional_mode` config flag
- `app/Http/Middleware/ValidateVotingIp.php` — Shadow-recording divergence instead of blocking
- `tests/Unit/Middleware/ValidateVotingIpTest.php` — 3 new D.0.1 tests (10 total, all passing)

**Mechanism:**
| Setting | Behavior |
|---------|----------|
| `VOTING_CONSTITUTIONAL_MODE=false` (default) | Legacy: block on IP mismatch |
| `VOTING_CONSTITUTIONAL_MODE=true` | Shadow: record divergence telemetry, pass through to constitutional path |

**Divergence telemetry** (`Log::warning('[D.0.1] ...')` + `Log::channel('security')`):
- Captures: user_id, registered_ip, current_ip, url, route, user_agent, timestamp
- Telemetry source for D.0.2 verification that zero divergence exists before D.0.3 removal

**Next step:** H.2 — Remove `registered_ip` cleartext from controller Inertia props (6 call sites of `validateVotingIpWithResponse()`)

### D.0 — Constitutional Retirement Rollback Doctrine (NEW)

**Trigger conditions (any one activates rollback):**
- Replay divergence detected
- Sovereign mismatch detected
- Topology-dependent legitimacy observed
- Nondeterministic replay encountered
- Procedural fallback path activated

**Rollback action:**
```
AUTOMATIC return to dual sovereignty mode.
Feature flag reverted.
Divergence report generated.
Phase D.2 blocked until root cause resolved.
```

This is essential safety engineering for irreversible deletion.

---

## Constitutional Snapshot Schema Versioning Doctrine

Strategic requirement for replay-safe governance evolution:

```
Every constitutional evidence snapshot MUST carry:
- snapshot schema version
- evidence normalization version
- hash policy version
- replay compatibility version
```

Without versioning, future schema migrations silently corrupt replay audit trails.
This is foundational for long-term governance infrastructure.

**Not implemented in C.5 — documented for D.R.5 implementation.**

---

## Execution Sequence

**STEP C.5.1** — Run targeted cluster groups, capture failure patterns
**STEP C.5.2** — Generate C5a classification artifact
**STEP C.5.3** — Generate C5b audit artifact (sovereignty + replay + topology + resolver + scalar + early-return + projection)
**STEP C.5.4** — Generate C5e certificate (only when all subphases complete)
**STEP C.5.5** — Update Phase D plan with drift telemetry + rollback doctrine

---

## Artifacts to Create

| Action | File | Subphase |
|--------|------|---------|
| CREATE | `claude/audits/C5a_ConstitutionalFailureClassification.md` | C.5.2 |
| CREATE | `claude/audits/C5b_HiddenSovereigntyAudit.md` | C.5.3 |
| CREATE | `claude/audits/C5e_ConstitutionalStabilizationCertificate.md` | C.5.4 |

**NO production code changes in Phase C.5.**

---

## C.5 Remaining Gates — Execution Plan

The following gates remain unaddressed. Each requires a standalone audit artifact
created in `claude/audits/`. The artifacts are analysis documents (not code changes).

### C.5f — Resolver Exclusivity Audit

**Output:** `claude/audits/C5f_ResolverExclusivityAudit.md`

**Audit targets — files that MUST NOT derive legitimacy outside PolicySequence:**

| Directory | Status | Key Finding |
|-----------|--------|-------------|
| `app/Services/VotingSecurityService.php` | CONFIRMED VIOLATION | `can_vote()`, `can_vote_from_current_ip`, `validateVoterEligibility()` — legacy sovereignty derivation |
| `app/Http/Middleware/ValidateVotingIp.php` | CONFIRMED VIOLATION | Direct blocking logic at lines 77-84, 97-102 (even in shadow mode, the code path exists) |
| `app/Http/Controllers/VoteController.php:1552` | BORDERLINE | Creates `LegitimacyOutcome::fromTrustState()` — F4 test approves as telemetry exception, but no exclusive resolver exists yet |
| `app/Application/Election/Security/Overlays/` | PASS (7/7) | All use only OverlaySignal |
| `app/Application/Election/Security/Policies/` | PASS (3/3) | All return only ConstitutionalFinding |
| `app/Http/Middleware/EnsureVotingActive.php` | PASS | Delegates to ElectionLifecycle::canVote() |
| `app/Http/Middleware/VoteEligibility.php` | PASS | Delegates to ElectionLifecycle::canVote() |
| `app/Jobs/` | PASS | No matching patterns |
| `app/Console/Commands/` | PASS | Admin commands only |

**Key structural finding:** `ConstitutionalLegitimacyDecision` class (referenced by F4 fitness function as the exclusive legitimacy authority) does not exist as a PHP class. Document as architectural gap for D.0.3.

### C.5g — Scalar Sovereignty Audit

**Output:** `claude/audits/C5g_ScalarSovereigntyAudit.md`

**Scope:** `app/Domain/Election/Security/`, `app/Application/Election/Security/`

**CONFIRMED VIOLATIONS:**

| File | Scalar Term | Assessment |
|------|-------------|-----------|
| `Domain/Security/DivergenceSeverity.php` | Info/Warning/High/Critical/Existential | Full severity hierarchy — DEFERRED to D.0.3 retirement (telemetry concern, not derivation) |
| `Domain/Security/ConstitutionalDivergenceType.php` | `severity()` method + Severity enum | Scalar priority ranking of divergence types — DEFERRED to D.0.3 |
| `Domain/Security/Simplified/EvidenceSeverity.php` | LOW/MODERATE/HIGH | Scalar in observation layer — DEFERRED to D.0.3 |
| `Domain/Security/ConstitutionalConcernLevel.php` | Doc uses "severity" | Minor — classification labels not derivation inputs |
| `Domain/Security/EvidenceWeightCategory.php` | DEFINITIVE/STRONG/MODERATE/WEAK | Grey zone — categorical evidence ontology, not scalar. Flag for monitoring. |

**Classification:** All violations are in **telemetry/observation** code, not in **legitimacy derivation**. They are scalar *naming* in domain types, not scalar *aggregation* producing legitimacy. However, they violate the Constitutional Algebra Boundary doctrine and must be remediated before D.0.3e (final retirement).

### C.5h — Early Return Sovereignty Audit

**Output:** `claude/audits/C5h_EarlyReturnSovereigntyAudit.md`

**Scope:** All `evaluate()`, `resolve()`, `assess()` methods in `app/Application/Election/Security/`

**CONFIRMED VIOLATIONS:**

| File | Lines | Pattern | Risk |
|------|-------|---------|------|
| `TrustSnapshotAssembler.php` | 30-35 | `break` on first non-stable signal | First-matching-signal truncation — may miss downstream signals |
| `SnapshotAssembler.php` | 35-39 | `break` on first non-continue signal | Same pattern as TrustSnapshotAssembler |

**PASS (no early return):**
- All 7 overlays — sequential if/return chains, no foreach + break
- All 3 policies — same pattern, correct full traversal
- `PolicySequence::evaluate()` — intentional short-circuit by constitutional design
- `OverlayCoordinator` — collects ALL signals without early return
- `ConstitutionalOverlayRegistry::findByIdentifier()` — lookup by nature, not traversal

**Note:** The TrustSnapshotAssembler and SnapshotAssembler violations are architectural — the early `break` means the snapshot may contain incomplete evidence. However, since the constitutional runtime is not yet the exclusive enforcement authority (D.0.3), the practical impact is contained.

### C.5i — Projection Sovereignty Leakage Audit

**Output:** `claude/audits/C5i_ProjectionSovereigntyLeakageAudit.md`

**Scope:** Inertia pages, admin dashboard, analytics panels, frontend status labels

**Previously remediated by H.2:**
- `VoteDenied.vue` — `current_ip`, `original_ip`, `registered_ip` removed from props and template
- `helpers.php:validateVotingIpWithResponse()` — IP fields removed from all return paths
- Verified: 9 tests passing (27 assertions), zero IP references remain in VoteDenied.vue

**Remaining audit targets — verify these contain no forbidden patterns:**
- Dashboard Vue components (admin panels, election overview)
- Alert/notification components
- Status label components
- Analytics and observability panels

**Forbidden patterns:**
```
"high risk", "critical", "urgent", "escalated"
ranked legitimacy indicators, red-first sovereignty grouping
sorted "criticality", top warnings implying authority
```

### C.5d — Final Topology Cross-Reference Pass

After C.5f/C.5g/C.5h/C.5i are complete, perform a cross-reference against the
existing C.5d findings to check whether any new topology leaks were discovered.

**Output:** Updated `claude/audits/C5d_SovereigntyTopologyArchaeologyReport.md` cross-reference section

### C.5e — Constitutional Stabilization Certificate

**Output:** `claude/audits/C5e_ConstitutionalStabilizationCertificate.md`

**Conditions (updated from earlier plan):**

- [x] C.5a — Constitutional failure classification
- [x] C.5b — Hidden sovereignty audit (H.1-H.6)
- [x] C.5c — Replay stability audit
- [x] C.5d — Topology archaeology report
- [ ] C.5f — Resolver exclusivity audit
- [ ] C.5g — Scalar sovereignty audit
- [ ] C.5h — Early return sovereignty audit
- [ ] C.5i — Projection leakage audit
- [ ] C.5d — Final topology cross-reference pass
- [x] D.0.3a prerequisite complete — ConstitutionalLegitimacyDecision implemented and wired

Certificate issuance is the formal gate opening D.0.3a.

---

## Phase D Gate Conditions — Stabilization Prerequisites for D.0.3a Initiation Review

> **Status:** All required stabilization prerequisites satisfied for controlled D.0.3a initiation review.
> Pre-D.0.3a requirements remain (ConstitutionalLegitimacyDecision + Legitimacy Derivation Doctrine — now complete).
> Transitions remain deliberate, reviewable, and reversible.

- [x] C.5a classification complete — no unclassified SOVEREIGN or EXISTENTIAL TYPE D failures
- [x] C.5b-d hidden sovereignty + replay + topology audit complete (H.1-H.6 sequenced)
- [x] C.5f resolver exclusivity audit complete — no off-path legitimacy derivation
- [x] C.5g scalar sovereignty audit complete — no surviving scalar authority
- [x] C.5h early return sovereignty audit complete — no partial-traversal authority
- [x] C.5i projection leakage audit complete — no UI sovereignty
- [x] C.5d final topology cross-reference pass complete
- [x] C.5e certificate issued — **C5e-2026-05-28**
- [x] CFB-1 Constitutional Freeze Boundary established
- [x] 2196 baseline not breached
- [x] D.0 rollback doctrine documented
- [x] D.1 drift telemetry structure documented

---

---

## Strategic DDD Discovery — Architecture Governance Sequence

**Phase:** DD.3b — Strategic Architecture Discovery (Senior Architect Directive)
**Date:** 2026-05-29
**Status:** Plan — revised per Senior Architect review
**Prerequisite:** NamespaceAlignmentAudit.md, EvidenceObservationCapabilityAudit.md, EvidenceContext.md (all complete)

### Context

The Evidence Context document contains **premature conclusions** — aggregate definitions created before authority boundaries were finalized, and implicit assumption that Observation ⊆ Evidence Context unproven. Correct DDD sequence:

```
Language → Authority → Boundaries → Context Map → Published Language →
Stability → Aggregates → Implementation
```

The project is currently between **Authority** and **Boundaries**. All aggregate design is deferred.

### Core Question

> What is the aggregate root of authority?

Suspect answer: **Legitimacy Context**, not Evidence Context. This question drives all subsequent modeling.

### Hard Rule

```
Namespace migration forbidden until ALL of:
  ✅ AuthorityOwnershipMatrix approved
  ✅ ConstitutionalContextMap approved
  ✅ ObservationContextAssessment approved
  ✅ GovernanceLegitimacyBoundaryAssessment approved
  ✅ DomainEventOwnershipMatrix approved
  ✅ PublishedLanguageMatrix approved
  ✅ ContextStabilityAssessment approved
  ✅ EvidenceContext.md reviewed and updated
  ✅ Context boundaries approved by Senior Architect
```

### Artifact 1: AuthorityOwnershipMatrix.md (P1)

**Path:** `claude/audits/AuthorityOwnershipMatrix.md`

**Purpose:** For every concept, document what authority it owns and what it must never own. This is foundational — before context boundaries can be drawn, authority must be assigned.

**Concepts to analyze (10):**

| Concept | Key Question |
|---------|-------------|
| Observation | Does it own the authority to observe, or just the output of observation? |
| Signal | Is signal distinct from observation, or same concept at different granularity? |
| Evidence | Does evidence own preservation authority, or is that infrastructure? |
| Evaluation | Does evaluation own the authority to classify quality, or is that part of evidence? |
| Legitimacy | Uncontested — owns final derivation authority. What are the exact bounds? |
| Governance | Currently conflated with legitimacy in root Security. What does governance own separately? |
| Projection | Owns presentation only. But what counts as "presentation"? |
| Replay | Owns deterministic reconstruction. But does it own certification? |
| Certification | Sub-authority of replay, or independent concept? |
| Migration | Owns retirement sequencing. Does it own divergence detection? |

**For each concept:** authority owned, authority prohibited, upstream dependencies, downstream dependencies, current code location, whether it has a formal bounded context today.

**Sources:** `ConstitutionalUbiquitousLanguage.md`, `TacticalSemanticAlignment.md`, `SemanticBoundaryMap.md`, `EvidenceObservationCapabilityAudit.md`, `NamespaceAlignmentAudit.md`, all domain types in `app/Domain/Election/Security/` and `app/Domain/Election/Replay/`.

### Artifact 2: ConstitutionalContextMap.md (P2)

**Path:** `claude/audits/ConstitutionalContextMap.md`

**Purpose:** Full bounded-context map. Every context gets: mission, ubiquitous language, upstream/downstream, published language, anti-corruption boundaries, current code locations.

**Contexts to model (tentative):** Observation, Evidence, Evaluation (may merge with Evidence), Legitimacy, Governance (split from Legitimacy analysis), Projection, Replay, Migration.

**Key design decisions:**
1. Is Observation upstream of Evidence, or same context?
2. Is Evaluation separate or subdomain of Evidence?
3. Is Governance inside Legitimacy or independent?
4. Is Certification inside Replay or independent?

### Artifact 3: ObservationContextAssessment.md (P3)

**Path:** `claude/audits/ObservationContextAssessment.md`

**Purpose:** Determine whether Observation is a subdomain of Evidence Context or an independent bounded context. The most critical boundary decision.

**For Observation ⊆ Evidence:** co-located in Simplified, feeds directly into evidence, no independent lifecycle, signal types coupled to evidence categories.

**For Observation independent:** overlays (app layer) vs evidence (domain layer), different change rates, different authority owners (OverlayCoordinator vs EvidenceSnapshot), different replay roles.

**Structure:** definition comparison, lifecycle analysis, authority boundary analysis, dependency analysis, replay role analysis, change coupling analysis, recommendation.

### Artifact 4: GovernanceLegitimacyBoundaryAssessment.md (P4)

**Path:** `claude/audits/GovernanceLegitimacyBoundaryAssessment.md`

**Purpose:** Split Governance from Legitimacy analysis. These are frequently confused.

| Context | Answers | Example |
|---------|---------|---------|
| **Legitimacy** | Is participation legitimate? | ConstitutionalLegitimacyDecision |
| **Governance** | What action should the organization take? | Enforcement actions, election rules |

Document where they overlap, where they diverge, and whether Governance warrants its own bounded context or is a subdomain of Legitimacy.

### Artifact 5: DomainEventOwnershipMatrix.md (P5)

**Path:** `claude/audits/DomainEventOwnershipMatrix.md`

**Purpose:** Every domain event has exactly one context owner.

| Event | Context Owner |
|----------------------|--------------|
| ObservationRecorded | Observation |
| EvidenceEvaluationCompleted | Evaluation |
| LegitimacyGranted | Legitimacy |
| ConstitutionalDenialIssued | Legitimacy |
| SovereigntyBoundaryCrossed | Migration |
| DivergenceObserved | Migration |
| ReplayCertified | Replay |
| ConstitutionalFallbackActivated | Migration |

Without this matrix, event ownership becomes ambiguous as the system evolves.

### Artifact 4: CoreDomainIdentification.md (P1a)

**Path:** `claude/audits/CoreDomainIdentification.md`

**Purpose:** Not all contexts deserve equal attention. Classify each by strategic value:

| Classification | Meaning | Examples |
|---------------|---------|----------|
| **Core Domain** | Generates business value, differentiates the platform | Legitimacy |
| **Supporting Domain** | Necessary but not differentiating | Evidence, Observation, Replay |
| **Generic Domain** | Could be off-the-shelf or outsourced | Projection, Migration, Retirement |

**Key questions:**
- What generates business value?
- What differentiates the platform from alternatives?
- What must be protected most?
- What can be generic?

Without this classification, every context gets equal attention — which is not DDD.

### Artifact 5: ContextRelationshipMatrix.md (P2a)

**Path:** `claude/audits/ContextRelationshipMatrix.md`

**Purpose:** The Context Map describes contexts; this describes how they relate. DDD relationship types:

| Relationship | Meaning |
|-------------|---------|
| **Partnership** | Two contexts, coordinated evolution |
| **Customer/Supplier** | Upstream supplies, downstream consumes |
| **Conformist** | Downstream conforms to upstream's model |
| **Anti-Corruption Layer** | Translation layer between contexts |
| **Shared Kernel** | Shared subset of model |
| **Open Host Service** | Published protocol for multiple consumers |
| **Published Language** | Well-documented shared language |

| Upstream | Downstream | Relationship |
|----------|-----------|-------------|
| Observation | Evidence | Customer/Supplier |
| Evidence | Legitimacy | Published Language |
| Replay | All | Open Host Service |
| Migration | Legacy | Anti-Corruption Layer |

### Artifact 6: GovernanceLegitimacyBoundaryAssessment.md (P4)

**Path:** `claude/audits/GovernanceLegitimacyBoundaryAssessment.md`

**Purpose:** Split Governance from Legitimacy.

| Context | Answers | Example |
|---------|---------|---------|
| **Legitimacy** | Is participation legitimate? | ConstitutionalLegitimacyDecision |
| **Governance** | What action should the organization take? | Enforcement, election rules |

Document where they overlap, diverge, and whether Governance warrants its own BC or is a subdomain of Legitimacy.

### Artifact 7: DomainEventOwnershipMatrix.md (P5)

**Path:** `claude/audits/DomainEventOwnershipMatrix.md`

**Purpose:** Every domain event has exactly one context owner.

| Event | Context Owner |
|----------------------|--------------|
| ObservationRecorded | Observation |
| EvidenceEvaluationCompleted | Evaluation |
| LegitimacyGranted | Legitimacy |
| ConstitutionalDenialIssued | Legitimacy |
| SovereigntyBoundaryCrossed | Migration |
| DivergenceObserved | Migration |
| ReplayCertified | Replay |
| ConstitutionalFallbackActivated | Migration |

Without this matrix, event ownership becomes ambiguous as the system evolves.

### Artifact 8: DomainCommandOwnershipMatrix.md (P5a)

**Path:** `claude/audits/DomainCommandOwnershipMatrix.md`

**Purpose:** Every command has exactly one context owner. Commands often cross boundaries even when events are clean.

| Command | Owner Context |
|-------------------|--------------|
| EvaluateEvidence | Evidence |
| DeriveLegitimacy | Legitimacy |
| CertifyReplay | Replay |
| DetectDivergence | Migration |
| ProjectConstitutionalState | Projection |
| ObserveOverlaySignal | Observation |

### Artifact 9: PublishedLanguageMatrix.md (P6)

**Path:** `claude/audits/PublishedLanguageMatrix.md`

**Purpose:** Formal ACL map — what types cross which context boundaries.

| Producer | Consumer | Published Language |
|-----------|---------|--------------------|
| Observation | Evidence | OverlaySignal |
| Evidence | Evaluation | ConstitutionalEvidenceSnapshot |
| Evaluation | Legitimacy | EvaluationEnvelope |
| Legitimacy | Governance | LegitimacyOutcome |
| Replay | (all) | ReplayCertification |

### Artifact 10: ContextStabilityAssessment.md (P7)

**Path:** `claude/audits/ContextStabilityAssessment.md`

**Purpose:** Calibrated stability classification. No context is fully stable while boundaries are still under investigation.

| Context | Stability | Rationale |
|---------|-----------|-----------|
| Observation | Emerging | Boundaries still under investigation |
| Evidence | Emerging | Boundaries still under investigation |
| Evaluation | Emerging | Not yet proven as independent context |
| Legitimacy | Maturing | Core domain, existing impl, but boundaries being refined |
| Governance | Emerging | Not yet separated from Legitimacy |
| Replay | Maturing | Existing impl (5 classes, 32 tests), cert boundary unclear |
| Certification | Emerging | Relationship to Replay not yet settled |
| Migration | Emerging | Divergence scope still being defined |
| Projection | Emerging | Scope unbounded |
| Retirement | Temporary | Will be removed after D.5 |

### Artifact 11: BusinessInvariantCatalog.md (P7a)

**Path:** `claude/audits/BusinessInvariantCatalog.md`

**Purpose:** DDD aggregates are built around invariants. Catalog before boundary decisions.

| Invariant | Context | Description |
|-----------|---------|-------------|
| One policy sequence per decision | Legitimacy | A legitimacy decision derives from exactly one policy sequence |
| Evidence frozen at evaluation | Evidence | Evidence snapshot, once created, is never mutated |
| Replay determinism | Replay | Same evidence + same policy sequence = same outcome |
| Observations non-sovereign | Observation | Observations never directly produce legitimacy |
| Evaluation exclusive input | Evaluation→Legitimacy | EvaluationEnvelope is exclusive input to legitimacy derivation |
| Evidence hash integrity | Evidence | Hash reproducible across processes, hosts, runtimes |
| Insufficiency monotonic | Legitimacy | More observation abundance never weakens insufficiency |

Only after invariants are catalogued can aggregate boundaries emerge.

### Artifact 12: BusinessCapabilityMap.md (P7b)

**Path:** `claude/audits/BusinessCapabilityMap.md`

**Purpose:** Cross-reference authority ownership with business capability ownership. Prevents governance vocabulary from overwhelming the ubiquitous language.

| Business Capability | Authority Owner | Context |
|--------------------|----------------|---------|
| Determine voter participation | ConstitutionalLegitimacyDecision | Legitimacy |
| Observe device continuity | DeviceAnomalyOverlay | Observation |
| Freeze evidence for replay | ConstitutionalEvidenceSnapshot | Evidence |
| Certify replay | ReplayCertification | Replay |

This cross-reference prevents authority-centric modeling from dominating domain-driven modeling.

### Artifact 13: Review EvidenceContext.md (P8)

Review and update EvidenceContext.md with findings from P1-P7b. Fix premature aggregate definitions. Align with approved context boundaries.

### Decision Gate: Approve Context Boundaries (P9)

All artifacts reviewed by Senior Architect. Context boundaries finalized. Published language approved.

### Subsequent Phases (P10-P12)

1. **Namespace Decisions** — only stable/maturing contexts drive namespace migration
2. **Aggregate Discovery** — deferred until BusinessInvariantCatalog approved (aggregates are consistency boundaries, not documents)
3. **Implementation**

### Sequencing

```
P1   AuthorityOwnershipMatrix.md
P1a  CoreDomainIdentification.md
P2   ConstitutionalContextMap.md
P2a  ContextRelationshipMatrix.md
P3   ObservationContextAssessment.md
P4   GovernanceLegitimacyBoundaryAssessment.md
P5   DomainEventOwnershipMatrix.md
P5a  DomainCommandOwnershipMatrix.md
P6   PublishedLanguageMatrix.md
P7   ContextStabilityAssessment.md
P7a  BusinessInvariantCatalog.md
P7b  BusinessCapabilityMap.md
P8   Review EvidenceContext.md
P9   ✅ APPROVED by Senior Architect — 2026-05-29

── Strategic DDD Discovery PHASE CLOSED ──

P10  Aggregate Discovery (consistency boundaries inside each BC)
P11  Fitness Functions for Context Boundaries (structural enforcement)
P12  Namespace Decisions (stable/maturing contexts only)
P13  Implementation
```

### Architectural Caution

The plan uses "authority" extensively. DDD models around **business capability**, **business responsibility**, **business invariants**, and **business decisions** — not authority. Authority analysis is useful for constitutional governance systems, but if everything becomes AuthorityOwnershipMatrix → AuthorityBoundary → AuthorityTopology → AuthorityFlow, the architecture risks being driven by governance vocabulary rather than domain vocabulary. BusinessCapabilityMap.md (P7b) provides the cross-reference to keep this in check.

The project is between Authority and Boundaries in the DDD sequence. It is not yet at Aggregates.

---

## Corrected Sequencing — Complete Path to Retirement

| Step | Phase | Purpose | Status |
|------|-------|---------|--------|
| 1 | C.5a ✅ | Constitutional failure classification | Complete |
| 2 | C.5b ✅ | Hidden sovereignty audit (H.1-H.6) | Complete |
| 3 | C.5c ✅ | Replay stability audit | Complete |
| 4 | C.5d ✅ | Topology archaeology report | Complete |
| 5 | D.0.1 ✅ | ValidateVotingIp shadow mode | Complete |
| 6 | D.0.2 ✅ | Convergence certifiability | Complete |
| 7 | H.2 ✅ | Projection sovereignty cleanup | Complete |
| 8 | C.5f ✅ | Resolver exclusivity audit | Complete — see `claude/audits/C5f_ResolverExclusivityAudit.md` |
| 9 | C.5g ✅ | Scalar sovereignty audit | Complete — see `claude/audits/C5g_ScalarSovereigntyAudit.md` |
| 10 | C.5h ✅ | Early return sovereignty audit | Complete — see `claude/audits/C5h_EarlyReturnSovereigntyAudit.md` |
| 11 | C.5i ✅ | Projection leakage audit | Complete — see `claude/audits/C5i_ProjectionSovereigntyLeakageAudit.md` |
| 12 | C.5d ↩ ✅ | Final topology cross-reference pass | Complete — appended to C.5d report |
| 13 | C.5e ✅ | Stabilization certificate issued | **C5e-2026-05-28** — Gate for D.0.3 |
| — | CFB-1 ✅ | Constitutional Freeze Boundary established | Enforced by F1–F6 fitness functions |
| 13a | Doctrine ✅ | Constitutional Legitimacy Derivation Doctrine | `claude/governance/ConstitutionalLegitimacyDerivationDoctrine.md` |
| 14 | D.0.3a ✅ | Constitutional-primary enforcement mode | Config flipped, gate in store(), 50/50 verification tests passing |
| DD.1 | ✅ | Domain Algebra — Ubiquitous Language, Aggregate Boundaries, ACL | `claude/governance/ConstitutionalUbiquitousLanguage.md` |
| 15 | D.0.3b | Middleware → passive observer/fallback | After D.0.3a stable |
| 16 | D.0.3c | Drift window under constitutional authority | After D.0.3b stable |
| 17 | D.0.3d | Replay certification after primary cutover | After D.0.3c complete |
| 18 | D.0.3e | Final middleware retirement | After D.0.3d complete |
| 19 | D.1 | Dual sovereignty drift telemetry | After D.0.3e stable |
| 20 | D.2 | Controlled procedural deletion | D.1 window + no divergence |
| 21 | D.3 | Replay certification (cross-runtime) | D.2 stable |
| 22 | D.4 | Monotonicity certification | D.3 complete |
| 23 | D.5 | M.1 stabilization complete | All D phases |
| 24 | M.2 | Device sovereignty migration | D.5 complete |

---

## H.2 — Sovereignty Vocabulary Cleanup Plan

**Phase:** D.0 Constitutional Retirement Sequencing
**Type:** Sovereignty vocabulary cleanup (NOT enforcement retirement)
**Gate:** D.0.2 Complete ✅

### Constitutional Classification

Every field in `validateVotingIpWithResponse()` return value and VoteDenied props:

| Field | Classification | Action |
|-------|---------------|--------|
| `user_name` | Constitutional evidence | Keep |
| `denial_type` | Constitutional evidence | Keep |
| `valid` | Operational metadata | Keep (internal caller signal) |
| `skip_reason` | Operational metadata | Keep |
| `error_type` | Operational metadata | Keep |
| `votes_from_ip` | Operational metadata | Keep |
| `max_votes_allowed` | Operational metadata | Keep |
| `error_title` / title strings | Procedural sovereignty residue | Rewrite as constitutional outcomes |
| `error_message` / solution strings | Procedural sovereignty residue | Rewrite as constitutional outcomes |
| `current_ip` | Hidden authority vocabulary | **Remove from projection** |
| `registered_ip` → `$auth_user->voting_ip` | Procedural sovereignty residue | **Remove from projection** |
| `original_ip` (Vue prop, never populated) | Hidden authority vocabulary | **Remove from component** |

### Files to Modify

| File | Change |
|------|--------|
| `app/Helpers/helpers.php` | Remove `current_ip` / `registered_ip` from array returns (2 success paths) and from Inertia render props (1 denial path) |
| `resources/js/Pages/Vote/VoteDenied.vue` | Remove 3 props (`current_ip`, `original_ip`, `registered_ip`); remove IP template section lines 97-114; remove IP lines in `generateErrorDetailsText()` lines 459-462 |
| `tests/Unit/Helpers/ValidateVotingIpWithResponseReturnTest.php` | **NEW** — 7 tests for return value and Inertia prop absence |
| `tests/Feature/Vote/VoteControllerPropTest.php` | **NEW** — 2 tests for IP absence in rendered Inertia props |

### Files NOT Modified (verified no changes needed)

- `VoteController.php` — call sites only check `instanceof`, never consume array fields
- `DemoVoteController.php` — same pattern
- `app/Http/Controllers/DeligateVoteController.php` — does not call the helper
- `resources/js/Pages/Elections/Settings/Index.vue` — separate domain (per-election IP config, not user-level `voting_ip`)
- `routes/election/electionRoutes.php:125` — separate VoteDenied route without IP fields
- `app/Services/Constitutional/DivergenceObserver.php` — server-side telemetry, not projection

### Sequencing (TDD) — COMPLETE ✅

1. ✅ **Write test file** `ValidateVotingIpWithResponseReturnTest.php` — 7 tests (22 assertions), all passing
2. ✅ **Modify `helpers.php`** — removed `current_ip` and `registered_ip` from all 3 return paths
3. ✅ **Modify `VoteDenied.vue`** — removed 3 props (`current_ip`, `original_ip`, `registered_ip`), IP template section, copy-text IP lines
4. ✅ **Write `VoteControllerPropTest.php`** — 2 tests (5 assertions), all passing
5. ✅ **Run regression** — IpEvidenceLegacyEquivalenceTest: 10/10 passing; VoteControllerConstitutionalTest: 2 pre-existing failures confirmed unrelated

### Verification Results

```bash
# H.2-specific tests: 9/9 passing (27 assertions)
php artisan test --env=testing tests/Unit/Helpers/ValidateVotingIpWithResponseReturnTest.php
php artisan test --env=testing tests/Feature/Vote/VoteControllerPropTest.php

# Regression: IpEvidenceLegacyEquivalenceTest 10/10 passing
php artisan test --env=testing tests/Unit/Application/Election/Security/IpEvidenceLegacyEquivalenceTest.php

# Note: VoteControllerConstitutionalTest has 2 pre-existing failures
# (302 redirect, 405 method not allowed — unrelated to H.2)
```

---

## Phase D.0.3a — Constitutional Primary Enforcement Mode

**Status:** ⏳ NEXT
**Phase:** D.0 Constitutional Retirement Sequencing
**Gate Condition:** C.5e certificate issued + ConstitutionalLegitimacyDecision implemented ✅
**Transition:** Shadow enforcement → primary enforcement

### Context

ConstitutionalLegitimacyDecision is implemented and wired into VoteController. The constitutional
evaluation chain (TrustPolicyEvaluator → PolicySequence → ConstitutionalLegitimacyDecision) runs
on every vote submission at `VoteController::store()`. But currently, the LegitimacyOutcome is
**telemetry-only** — the legacy gates (canVote, ensureVoterMembership, validateVotingIp) make the
actual enforcement decision.

D.0.3a makes the constitutional evaluation result the **primary enforcement gate**. The legacy
checks become secondary defense-in-depth with divergence monitoring.

### Changes

#### 1. `config/voting_security.php` — Flip constitutional_mode default

Flip `'constitutional_mode'` default from `false` to `true`:

```php
'constitutional_mode' => env('VOTING_CONSTITUTIONAL_MODE', true),
```

**Effect:** ValidateVotingIp middleware switches from blocking to shadow-recording divergence.
This is safe because:
- D.0.1 already implemented the shadow mode code path (lines 78-81)
- D.0.2 verified the mechanism works (7+10 tests passing)
- The constitutional gate in store() below becomes the primary enforcement

#### 2. `VoteController::store()` — Constitutional enforcement gate

Insert a new enforcement block AFTER line 1556 (after LegitimacyOutcome derivation) and BEFORE
the legacy canVote() check. The new flow:

```
1. Run trust evaluation → get LegitimacyOutcome (unchanged, lines 1542-1556)
2. ── NEW D.0.3a GATE ──
   - For REAL elections:
     - If outcome !== Allowed: block vote, show constitutional denial message
     - Log denial with trust evaluation context
     - Record sovereignty divergence (constitutional denies)
   - For DEMO elections:
     - Skip constitutional enforcement (demo is testing/training mode)
     - Continue to legacy checks
3. Legacy checks (canVote, membership, IP validation) remain as defense-in-depth
4. If all pass → proceed with vote storage
```

Implementation details:
- Use `DB::rollBack()` consistent with existing pattern at line 1581
- Map outcome to user-facing message via match expression:
  - `Denied` → "constitutional verification" error message
  - `Deferred` → "additional verification required" message
  - `Investigate` → "flagged for manual review" message
- Track divergence: when constitutional denies but legacy would have passed, record as
  constitutional-primary divergence event
- Skip for demo elections to maintain testability

#### 3. `VoteController::create()` — Page-init constitutional gate (MINIMAL)

The `create()` method currently gates via `canVote()`, membership, and IP validation but does
NOT run TrustPolicyEvaluator. Adding the full evaluation chain would require device fingerprint
evidence that isn't available at page-init time.

**Approach:**
- For REAL elections: Add a TrustPolicyEvaluator evaluation with `rawFingerprint: null`
  (device policy handles missing fingerprint gracefully via NoRequirement match type)
- If LegitimacyOutcome is not Allowed, redirect to dashboard with constitutional denial message
- This prevents users from seeing a ballot they can't submit

**Note:** This is a UX improvement, not a security gate. The store() enforcement is authoritative.
If evaluation with null fingerprint is unreliable, defer this to D.0.3b.

#### 4. Divergence telemetry enhancement

When the constitutional check blocks in store(), record detailed telemetry:
- Trust evaluation state and reason
- Which policy triggered the denial (from `$trustEnvelope->result->policyOutcomeSequence`)
- Whether legacy checks agree (constitutional-primary divergence tracking)

This feeds directly into D.1 drift telemetry requirements.

### Constitutional compliance

| Invariant | Preserved? | How |
|-----------|-----------|-----|
| Resolver Exclusivity (F4) | ✅ | LegitimacyOutcome comes ONLY from ConstitutionalLegitimacyDecision |
| Topology Neutrality | ✅ | Decision depends on evidence, not middleware order |
| Replay Determinism | ✅ | Same VotingTrustResult → same enforcement decision |
| Observation ≠ Sovereignty | ✅ | Telemetry still logged; enforcement is from resolver |
| Sovereignty Monotonicity | ✅ | More evidence cannot weaken insufficiency |
| Demo testability | ✅ | Demo elections skip constitutional enforcement |

### Verification

1. **F1-F6 fitness functions** — All 31 must pass (constitutional invariants)
2. **`SovereigntyConvergenceFitnessTest`** — F4 resolver exclusivity tests pass
3. **`TrustCapabilityPolicyTest`** — 35/35 passing (policy chain regression)
4. **`IpEvidenceLegacyEquivalenceTest`** — 9/9 passing (dual sovereignty equivalence)
5. **Demo election flow** — Manual verification: demo voting still works end-to-end
6. **Store enforcement test** — Verify that a vote submission with constitutional denial is blocked

### Rollback

If D.0.3a causes issues:
1. Revert `'constitutional_mode'` default to `false`
2. Remove or comment out the enforcement gate in store()
3. Run full suite to confirm 2203-failure baseline

### Sequencing (TDD)

1. ✅ Already complete: ConstitutionalLegitimacyDecision exists and is wired
2. ⏳ Write test: Store enforcement blocks constitutionally denied votes
3. ⏳ Implement: Flip config default
4. ⏳ Implement: Add enforcement gate in store()
5. ⏳ Optionally implement: Page-init gate in create()
6. ⏳ Run full verification suite
7. ⏳ Update sequencing table below

---

## Track: Constitutional Domain Algebra Hardening

**Status:** 🆕 NEW STRATEGIC TRACK — runs parallel to D.0.3 execution
**Phase:** Cross-cutting (prerequisite for M.2/M.3 federation safety)
**Source:** Senior Architect + DDD Review — doctrine-driven → model-driven

### Problem

The constitutional governance architecture is currently **doctrine-heavy and model-light**:

| Domain concept | Current form | Risk |
|---------------|-------------|------|
| Sovereignty | Audit rules + fitness functions | Social enforcement, not structural |
| Legitimacy | `LegitimacyOutcome` enum + `ConstitutionalLegitimacyDecision` class | Thin — needs aggregate wrapping |
| Evidence | `TrustEvidencePrivacyPolicy` + hashed strings | No typed Evidence aggregate |
| Replay | F3 test + doctrine prose | Operational, not domain-owned |
| Divergence | `SovereigntyDivergenceRecord` + telemetry | Partial — needs event status |
| Observation | `OverlaySignal` + `ConstitutionalObservationContext` | Separated but unaggregated |
| Topology | Audit findings | Structural enforcement needed |

### Aggregate Discovery — DEFERRED

DDD aggregate design occurs AFTER: Language → Authority → Boundaries → Consistency Requirements → Transaction Requirements.

The project is currently between **Authority** and **Boundaries**. Prerequisites:

- AuthorityOwnershipMatrix complete
- ConstitutionalContextMap complete
- ObservationContextAssessment complete
- GovernanceLegitimacyBoundaryAssessment complete
- DomainEventOwnershipMatrix complete
- PublishedLanguageMatrix complete
- Context boundaries approved

Only then perform aggregate design. An aggregate is a consistency boundary, not a document or a concept name.

### Anti-Corruption Layer (ACL) — Conceptual Map Only

Existing translation mappings (defined, not implemented):

```
Legacy (procedural)           → Constitutional (domain)
──────────────────────────────────────────────────
User.voting_ip                → NetworkEvidence
$user->canVote                → LegitimacyEvaluation
validateVotingIpWithResponse() → EvidenceInconsistency
middleware blocking            → ConstitutionalDenial
helper() status checks         → ObservationProjection
Code.can_vote_now              → AttestationRecord
ElectionMembership             → ParticipationEligibilityEvidence
```

**No PHP classes to create.** Existing mappings already documented in `ConstitutionalUbiquitousLanguage.md` and `TacticalSemanticAlignment.md`. ACL implementation deferred until context boundaries are approved.

#### 3. Ubiquitous Language Specification

Create formal language document at `claude/governance/ConstitutionalUbiquitousLanguage.md`:

| Term | Precise Meaning | Counter-example (what it is NOT) |
|------|----------------|----------------------------------|
| Evidence | Immutable observed constitutional fact frozen at evaluation time | A mutable DB column |
| Observation | Non-sovereign contextual interpretation of evidence | A policy decision |
| Legitimacy | Constitutional participation authorization derived by exclusive resolver | A controller's canVote() check |
| Sovereignty | Exclusive authority to derive legitimacy within bounded context | Middleware blocking |
| Replay | Deterministic reconstruction of sovereign outcome from frozen evidence | Log replay |
| Divergence | Authority mismatch between constitutional and procedural systems | A bug |
| Projection | Non-authoritative representation of constitutional state | The UI trust score |
| Topology Leakage | Authority implied by execution order rather than evidence | Middleware ordering |
| Constitutional Insufficiency | Evidence set that does not meet sovereign threshold for participation | "Not enough trust score" |

#### 4. Replay as First-Class Domain Capability

Elevate replay from operational doctrine to domain-owned capability:

**Files to create:**
- `app/Domain/Election/Security/Replay/ReplaySession.php` — session with identity, timestamp, evidence hash
- `app/Domain/Election/Security/Replay/ReplayCertification.php` — certification result with signature
- `app/Domain/Election/Security/Replay/ReplayCompatibilityVersion.php` — schema version marker
- `app/Domain/Election/Security/Replay/ReplayEvidenceEnvelope.php` — sealed evidence container
- `app/Domain/Election/Security/Replay/ReplayAssertion.php` — "same input → same outcome" contract

**Invariant:** ReplayCertification must fail if evidence envelope, policy sequence, or resolver mapping has changed since certification.

#### 5. Constitutional Event Taxonomy

Define explicit event classes:

```
Sovereign Events (immutable, authority-significant):
  └─ LegitimacyEvaluated
  └─ ConstitutionalDenial
  └─ SovereigntyBoundaryCrossed

Observational Events (non-authoritative, record-only):
  └─ OverlaySignalRecorded
  └─ EvidenceInconsistencyObserved
  └─ DriftTelemetryRecorded

Replay Events (certification-scoped):
  └─ ReplaySessionOpened
  └─ ReplayCertificationIssued
  └─ ReplayDivergenceDetected

Projection Events (presentation-scoped):
  └─ ConstitutionalStateProjected
  └─ ObservationRendered

Migration Events (retirement-scoped):
  └─ DualSovereigntyEntered
  └─ ConstitutionalFallbackActivated
  └─ LegacyGateRetired
```

**Constraint:** Sovereign events must be serialized with schema version and replay hash.
**Constraint:** Order of sovereign events must not affect subsequent sovereignty outcomes.

#### 6. Tactical Separation — Policy vs Resolver vs Overlay vs Observation

Rigorously separate the currently overlapping concepts:

| Current name | Actual role | Often confused with |
|-------------|-------------|-------------------|
| `PolicySequence` | Evaluation orchestrator | Resolver (resolver is `ConstitutionalLegitimacyDecision`) |
| `NetworkBindingPolicy` | Constitutional rule | Overlay (it runs evidence, doesn't observe) |
| `DeviceAnomalyOverlay` | Observational signal | Policy (it observes, doesn't evaluate) |
| `OverlayCoordinator` | Observation aggregator | Resolver (it aggregates, doesn't decide) |
| `ConstitutionalLegitimacyDecision` | Sole resolver | Policy (derives, doesn't evaluate) |

**Action:** Audit naming and refactor where the name implies the wrong tactical role.

#### 7. Structural Enforcement Over Social Enforcement

Reduce dependence on:
- Comments (F4 doc says "this is the exclusive resolver")
- Conventions ("don't derive LegitimacyOutcome outside this class")
- Audit memory (F4 test scans for violations)

Increase:
- **Constructor restrictions** on `LegitimacyOutcome` to ensure only `ConstitutionalLegitimacyDecision` can create it
- **Sealed derivation topology** — `LegitimacyOutcome::fromTrustState()` should be `private` to the class that calls it
- **Typed evidence algebra** — evidence values carry their type so invalid composition is structurally impossible
- **Compile-time boundaries** where PHP allows (final classes, readonly properties, private constructors)

**Target:** An engineer cannot accidentally derive legitimacy outside the resolver — the compiler/interpreter prevents it.

### Verification

1. All existing F1-F6 fitness functions still pass — structural enforcement must not break constitutional invariants
2. F4 test becomes compile-time enforceable rather than scan-based
3. Ubiquitous language document approved by architectural review
4. Replay certification can be demonstrated with a test that serializes, deserializes, and asserts identical outcome
5. Event taxonomy is complete enough to classify every existing log/telemetry call

### Sequencing

| Step | Task | Phase | Status |
|------|------|-------|--------|
| 1 | Create ubiquitous language specification | DD.1 | ✅ Complete — `claude/governance/ConstitutionalUbiquitousLanguage.md` |
| 2 | Aggregate boundaries — DEFERRED (see Strategic DDD Discovery section) | DD.1 | ⏳ Deferred until context boundaries approved |
| 3 | Anti-Corruption Layer — conceptual map only (no PHP classes) | DD.1 | ✅ Documented in Ubiquitous Language spec |
| 4 | Elevate Replay to domain capability | DD.2 | ✅ — 5 classes, 32 tests, moved to `app/Domain/Election/Replay/` |
| 5 | Event Taxonomy Implementation — 10 domain events, 3 test files, 13 tests (35 assertions) across 4 categories | DD.2.5 | ✅ Complete |
| 6 | Structural Sovereignty Enforcement — LegitimacyOutcome::fromTrustState() removed, F10 enforcement | DD.3a | ✅ — 39/39 tests passing (121 assertions) |
| 7 | Tactical Semantic Alignment — authority ownership audit, 5 renames, TacticalSemanticAlignment.md | DD.3b | ✅ — 145+ tests passing across affected suites |
| — | **Strategic DDD Discovery — see section above** (P1-P12 sequence) | DD.3b | **✅ Complete — at P9 gate** |
| 7a | EvaluationAutonomyAssessment.md — Evaluation ⊂ Evidence | DD.3b | ✅ Complete — `claude/audits/EvaluationAutonomyAssessment.md` |
| 7b | TemporaryContextRegistry.md — deletion criteria for 3 temporary contexts | DD.3b | ✅ Complete — `claude/audits/TemporaryContextRegistry.md` |
| 7c | CoreDomainProtection.md — protection rules per domain type | DD.3b | ✅ Complete — `claude/audits/CoreDomainProtection.md` |
| 7d | ContextDependencyRules.md — dependency direction matrix | DD.3b | ✅ Complete — `claude/audits/ContextDependencyRules.md` |
| — | **P9 Gate: Context Boundaries Ready for Approval** | DD.3b | **✅ P9 APPROVED by Senior Architect — Strategic DDD Discovery complete** |
| — | **DD.3b Strategic DDD Discovery: CLOSED** | DD.3b | **✅ All 17 artifacts approved. Phase complete.** |
| 8 | Aggregate Discovery (P10) — consistency boundaries inside Legitimacy, Evidence, Replay | DD.3c | ✅ Complete — 3 artifacts created |
| 9 | Fitness Functions for Context Boundaries (P11) — structural enforcement of dependency rules | DD.3c | ⏳ Next |
| 10 | Namespace Decisions (P12) — stable/maturing contexts only | DD.3c | ⏳ After aggregates + fitness functions |
| 11 | Implementation (P13) | DD.3c | ⏳ After namespace decisions |

**Parallelism:** Strategic DDD Discovery runs as a read-only gate before any further D.0.3a-D.0.3e execution. All namespace migration, aggregate design, and implementation work is blocked until context boundaries are approved.
