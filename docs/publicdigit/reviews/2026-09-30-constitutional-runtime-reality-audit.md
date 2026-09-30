# Constitutional Governance Runtime
## Reality Verification & Remaining Work Audit

| | |
|---|---|
| **Date** | 2026-09-30 |
| **Kind** | ⚠ authority: generated — engineering evidence. **Not a decision, not an acceptance.** Nothing in `app/`, `tests/`, `config/`, `routes/` or `database/` was changed. |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable). Four read-only subagents gathered facts in parallel; they share this session's identity, so this is **not** independent verification. Every load-bearing subagent claim used below was spot-checked from source by the preparing session. |
| **Branch / HEAD at start** | `knowelegeos-modelling` @ `76411541f` |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). Placed in `reviews/` beside the other dated audit records, **not** in the suggested `docs/architecture/`: the placement rule is derived, and `docs/architecture/` is the legacy "Think" root. |

**Evidence labels used throughout**

- **[RUN]**: executed in this session (tests run against `nrna_test`, not the development DB).
- **[STATIC]**: established by reading the code. Stated consequences are deduced, **not executed**.
- **[DOC]**: a documentation claim, recorded as a claim only.

---

## 1. Executive Summary

**What are we building?** An election runtime in which the right to participate (to vote) is derived in one place, from immutable per-election law plus recorded evidence. Every decision should be replayable and auditable.

**Architectural goal.** `Constitution → Evidence → independent Policies → Observations → ElectionCapabilityResolver (sole authority) → Snapshot → Projection`.

**What is actually implemented**
- A generic, deny-first `ElectionCapabilityResolver` with three policies. It is used for exactly one runtime purpose: computing which **admin lifecycle actions** are enabled on the election-management page.
- A **separate** voting pipeline: `TrustPolicyEvaluator → PolicySequence → ConstitutionalLegitimacyDecision`, enforced inside `VoteController::store`.
- A per-election snapshot of seven security fields, written at creation with a SHA-256 hash and guarded against Eloquent updates.
- Seven observational overlays whose output never affects any outcome.
- A large body of tests and documents.

**What is not implemented**
- Voting authority does not pass through the resolver.
- The frozen snapshot is **never read**.
- The trust evaluator reads mutable columns and `env()`, and **hard-codes** device, attestation and continuity facts.
- The IP-binding rule is not enforced by any active policy.
- There is no replay from storage.
- Parity exists at unit level only.
- CI enforcement does not cover these zones.

**Single biggest risk.** The code and documents present a sovereignty architecture that runtime does not execute, and several security rules are silently inert:
- device binding cannot fail;
- verification can never be required;
- IP binding only logs;
- dual-code mode cannot switch on from `.env`.

Anyone reading the documents would believe protections exist that the voting path does not apply.

**Are we converged?** **No.** Distributed authority still exists at runtime: two self-declared sovereign classes, at least four independent gates inside `VoteController::store`, seven voting-route middlewares, and a separate IP gate in `ElectionVotingController`. It is not a residue awaiting cleanup.

---

## 2. Repository State

| Item | State |
|---|---|
| Branch | `knowelegeos-modelling` |
| HEAD at start | `76411541f exec(knowledgeos): 1bb-r2 targeted repair …` |
| Tracked modifications at start | `.claude/sessions/2026-09-30.md` · `docs/knowledgeos/brainstorming/…/20260831-104505_…expert.md` · `docs/knowledgeos/chronological-read/P3B-STATE.json` · `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-decision-record.md` |
| Untracked at start | `docs/knowledgeos/chronological-read/_batch_input_r2/s5/rev7/OB0114/*.view.txt` (5) · `ledger-p3b-r2/OB0114-R7*` (6 dirs) · `docs/publicdigit/backlog/PBDIGIT-74…`, `PBDIGIT-75…` · `docs/publicdigit/reviews/2026-09-30-{GREEN-7…, PBDIGIT-74…, UC-4…halt-source, blocker-structure…}.md` · `docs/publicdigit/brainstorming/20260930_0935_current_status.md` |
| `app/` `tests/` `routes/` `config/` `database/` | **no uncommitted changes**; this audit changed none |

> **OUT OF SCOPE — DO NOT TOUCH.** All of the above belong to other concurrent sessions (KnowledgeOS S5 canary, UC-4/EM-IMPL-002, PBDIGIT-74/75), and this audit left them as they were. The working tree changed during the audit: `a4579b241` records "S5 canary OB0012 attempt 2 — BATCH-FAIL, FAILED class A1", and the session log and P3B state were committed by another process.

**Git provenance of the subsystem [STATIC]**
- `ElectionCapabilityResolver.php` was added in `11710f39a` (2026-05-24).
- `Security/Simplified` (Domain and Application) and most D-phase tests and claim documents were added together in `89914ce3a` (2026-05-29, subject "security domain created", 693 files, +43,929/−7,397, no body).
- The only D-numbered commit is `e697ffac6` (2026-05-26, "Phase D.5 Resolver Wiring"). It **predates** the commit that created the Simplified zone and the D1–D6 tests.
- No commit subject mentions D.1–D.4, D.5.5, D.6, D.R.2, D.R.3 or D.R.3.5.

---

## 3. Actual Architecture

```
ADMIN PATH (the only runtime use of the resolver)
GET election management page
 → ElectionManagementController::resolveCapabilities()      :1288 (called :1365)
     for each action in ElectionConstitution::RULES:
       new CapabilityContext(election, user, action, metadata, state)   ← trust NOT passed (null)
       ElectionCapabilityResolver::evaluate()                          Services/ElectionCapabilityResolver.php:28
         Overlay(1) → Trust/Evidence(2) → Lifecycle(3)   usort by layer priority, first deny returns
           OverlayCapabilityPolicy:  election->suspended_at set → shortCircuit(Suspended)
           EvidenceCapabilityPolicy: trust null → abstain  (always, at runtime)
           LifecycleCapabilityBaselinePolicy: action allowed in state → grant, else deny
 → UI projection {allowed, denial_reason}

VOTING PATH (real elections)
POST v/{vslug}/…  (routes/election/electionRoutes.php:507)
 → middleware: voter.slug.verify · voter.slug.window · voter.slug.consistency · ensure.election.voter
               · voter.step.order · vote.eligibility · validate.voting.ip · vote.organisation · throttle
 → VoteController::store()                                   :1463
     Code::where(election_id, client_ip, has_voted)->count() :1484   (controller-side DB evidence)
     TrustPolicyEvaluator::evaluate()                        :1492
        hash IP/fingerprint · build TrustCapabilityContext (live Election/User models)
        OverlayAggregator::aggregate()  → observations  ──► snapshot + event recorder only
        PolicySequence::evaluate()      → VotingTrustResult     ◄── the real decision
        TrustSnapshotAssembler · SecurityEventRecorder (sampled)
     ConstitutionalLegitimacyDecision::decide(result)        :1504   (1:1 enum map)
     GATE 1  outcome ≠ Allowed && type ≠ demo → rollback, back()->withErrors   :1522-1555
     GATE 2  ElectionLifecycle::canVote()                    :1560
     GATE 3  ensureVoterMembership()                         :1586
     … persist vote
```

`ElectionVotingController::start` (`elections.start`) applies its own IP gate, `resolveIpBlock()` (`:142`), before any voting flow.

---

## 4. Sovereignty Audit

### 4.1 Classification of authority candidates

| Component | Class | Evidence |
|---|---|---|
| `ElectionCapabilityResolver` | **A. Sovereign**, for admin actions only | only runtime caller `ElectionManagementController:1311` [STATIC] |
| `ConstitutionalLegitimacyDecision` | **A. Sovereign (nominal) for voting** | docblock: "THE exclusive constitutional authority" (`:12`); total map `:56-61`; enforced `VoteController:1522` [STATIC] |
| `PolicySequence::evaluate` | **H. Hidden authority leak** | fixed order, first concern → `insufficientEvidence` (`:122-185`); becomes `Denied` 1:1 [STATIC] |
| `TrustPolicyEvaluator` | **H. Hidden authority leak** (fact fabrication) | hard-coded facts and mutable reads, §5/§6 [STATIC] |
| `NetworkTrustEvidence::effectiveLimit` | **H. Hidden authority leak** | threshold policy inside an "evidence" VO (`:48-66`) [STATIC] |
| `VoteController::store` gates 2–3 | **H. Hidden authority leak** | `canVote()` and membership decide participation outside any resolver [STATIC] |
| `ElectionVotingController::resolveIpBlock` / `evaluateIpCount` | **H. Hidden authority leak** | `:219-270`; also named "illegal sovereign" in `claude/audits/D_5_5_Audit_1…` [STATIC] |
| `ValidateVotingIp` | **H / G** | blocks only when `VOTING_CONSTITUTIONAL_MODE=false`; default `true` passes through and logs (`:76-81`; `config/voting_security.php:108`) [STATIC] |
| `VoteEligibility`, `EnsureElectionVoter`, `VerifyVoterSlug*`, `EnsureVoterStepOrder`, `EnsureRealVoteOrganisation` | **F / H**. They decide participation at transport level; not individually audited | `bootstrap/app.php:85-99` [STATIC] |
| `OverlayAggregator` + 7 overlays | **B/D**. Observations only; **never reach any outcome** | observations go only to snapshot/recorder [STATIC] |
| `TrustSnapshotAssembler` | **E. Projection** (latent first-wins `activeOverlay`, `:29-37`, unconsumed) | [STATIC] |
| `SecurityEventRecorder` | **F. Infrastructure** (env + `rand()` sampling) | `:26-28` [STATIC] |
| `EvidenceCapabilityPolicy` | **C**, dormant at runtime (both inputs never set) | §7 |
| `TrustCapabilityPolicy`, `SnapshotAssembler` | **G. Legacy, dead**, reference non-existent symbols | §15 |

### 4.2 Hidden authority leaks

| # | File · class · method | Behaviour | Why it is authority | Intended replacement | Phase |
|---|---|---|---|---|---|
| L-1 | `Application/Election/Security/PolicySequence.php` `PolicySequence::evaluate` | verification → network → device; returns `INSUFFICIENT_EVIDENCE` at the first concern | its output maps 1:1 to `Denied` and blocks the vote; the "resolver" adds no judgement | policies emit findings; the resolver interprets the complete set | P1 |
| L-2 | `ConstitutionalLegitimacyDecision::decide` | second self-declared sovereign | contradicts "resolver is the only sovereign engine" | fold into `ElectionCapabilityResolver` (a voting action) or formally re-scope the invariant | P1 (decision first, P0-D) |
| L-3 | `TrustPolicyEvaluator::buildDeviceContext/buildAttestationRecord/buildSessionContinuity` | `registeredFingerprintHash=null`, `matchType=ExactMatch`, `attested=false`, `deviceChanged=false` | defaults chosen by the fact builder fix the outcome: device binding cannot fail, trust level is always `Unverified` | evidence from recorded sources (VoterVerification, step records) | P0 |
| L-4 | `NetworkTrustEvidence::effectiveLimit` | the limit scales by trust level; always `Unverified` → `max(1, intdiv(N, 2))` | changes the per-IP rule from 6 to effectively 4 votes (`votes > 3`) | a constitutional article plus a resolver interpretation | P0/P1 |
| L-5 | `ElectionVotingController::resolveIpBlock/evaluateIpCount` | blocks `start` when `VoterSlug.step_1_ip` count ≥ `ip_restriction_max_per_ip` or `config('app.max_use_clientIP')` | a separate per-IP authority with different law, counter and comparator | remove after the resolver path is proven | P4 (D.6) |
| L-6 | `VoteController::store` `canVote()` / `ensureVoterMembership()` | independent deny gates | participation decided outside the resolver | lifecycle and membership as resolver policies | P1 |
| L-7 | `ValidateVotingIp::handle` | config-switched block/pass | authority from `env`/`config` | remove, D.6 | P4 |

---

## 5. Constitutional Law Audit (D.2.5)

| Item | Verdict | Evidence |
|---|---|---|
| `ElectionConstitution` exists | ✅ but it is the **lifecycle action matrix** (`RULES`), not security law | `Domain/Election/Constitution/ElectionConstitution.php`; `ElectionConstitutionRegistry` |
| `SECURITY_ARTICLES` | ❌ **not found** anywhere | `git grep -w` 0 hits |
| **A.** Security rules as election-specific law | ⚠️ PARTIAL | `ConstitutionalArticlesSnapshot` holds `network_binding_strategy`, `max_votes_per_ip`, `device_binding_strategy`, `ballot_authorization_protocol`, `trust_overlay_active/priority/reason`. It has no article for the verified IP (rules 1/4), officer capture (rule 6) or verification requirement. It carries `trust_overlay_priority`, procedural vocabulary inside the "law" |
| **B.** Captured at creation | ✅ | `Election::booted` `created` hook (`Election.php:2328-2350`), via raw `DB::table()->update` |
| **C.** Cannot be modified | ⚠️ PARTIAL | Eloquent `saving` guard only (`:2266-2280`). The raw query builder bypasses it. The test `test_constitutional_fields_cannot_be_updated_even_via_query_builder` **asserts the update succeeds** (`result === 1`, then `assertTrue(true)`). The name contradicts the body. No DB trigger or permission. |
| **D.** Version persisted | ⚠️ | `security_articles_version = 'D.2.5'`, a **phase label**, not a law version |
| **E.** Hash persisted | ✅ | `constitutional_hash = sha256(toJson())` |
| **F.** Hash reproducible | ✅ in test | `ConstitutionalImmutabilityTest` [RUN: OK, 13 tests / 52 assertions] |
| **G.** Runtime reads frozen snapshot | ❌ **NOT IMPLEMENTED** | `ConstitutionalArticlesSnapshot::fromJson()` has **zero callers**; `security_articles_snapshot` is read nowhere in `app/` outside the model's own hooks |
| **H.** Runtime avoids mutable sources | ❌ **FAILS** | `TrustPolicyEvaluator:137-149` reads live `max_votes_per_ip`, `network_binding_strategy`, `ip_whitelist`. `IpVelocityOverlay:19` `env('IP_VELOCITY_THRESHOLD')`. `ValidateVotingIp` config. `ElectionVotingController` `ip_restriction_max_per_ip` (admin-editable, `UpdateElectionSettingsRequest:24`, max 50) + `config('app.max_use_clientIP')`. Code mode `config('voting.two_codes_system')` (global env, not per election). The source columns have no mutation guard. |
| **I.** Immutability tests | ⚠️ ORM-level only | as C |
| **J.** Hash-integrity tests | ⚠️ creation-time only; **no runtime verification** of the hash anywhere | [STATIC] |

**Conclusion: the D.2.5 claim "IMPLEMENTATION COMPLETE · ✅ LIVE" (`claude/audits/D_2_5_Phase_Complete.md:1-4`) is FALSE for its purpose.** The law is recorded, but runtime never applies it.

---

## 6. Seven Security Rules Audit

| # | Rule | Current implementation | Constitutional source? | Mutable? | Resolver-controlled? | Tests? | Status |
|---|---|---|---|---|---|---|---|
| 1 | Snap IP at verification | `CodeController::markCodeAsVerified` writes `codes.client_ip` (`:912-920`). `VoterSlug.step_1_ip`. `users.voting_ip`. Officer `verified_ip` in `VoterVerification`. **Four stores, no single one** | no | yes | no | partial | **CONTRADICTORY** |
| 2 | Single code | `config('voting.two_codes_system')` (global env) in `CodeController`/`VoteController` | no (snapshot has `ballot_authorization_protocol`, never read) | yes (env) | no | feature tests largely error | **NOT CONSTITUTIONALIZED** |
| 3 | Dual code | same flag. ⚠️ `env('TWO_CODES_SYSTEM')` returns string `"1"` [RUN: `Env::get` → `string(1) "1"`], and `VoteController:1936/2970/3112` and `CodeController:917` compare with `=== 1` / `=== true`. **With `.env` = 1, real-election strict mode never activates.** `DemoVoteController` uses `== 1` and does. `BallotAuthorizationPolicy` has **no caller** | no | yes | no | — | **CONTRADICTORY** (probable defect) |
| 4 | Vote only from the verified IP | `ValidateVotingIp`: under the default `constitutional_mode=true` it **passes through and logs**. Legacy `NetworkBindingPolicy` checks only the count, never `current == registered`. The comparison exists only in `NetworkContinuityObservation` (an unconsumed overlay) and the unwired Simplified policy | no | yes (config) | no | `ValidateVotingIpTest` [RUN: 1 failure, blocking case] | **NOT ENFORCED** in default config |
| 5 | Device fingerprint binding | `TrustPolicyEvaluator::buildDeviceContext`: `registeredFingerprintHash=null`; any submitted fingerprint → `ExactMatch` | `device_binding_strategy` in snapshot, never read | — | no | — | **NOT ENFORCED** (cannot fail) |
| 6 | Officer captures IP + fingerprint | `VoterVerificationController::store` writes `verified_ip`, `verified_device_fingerprint_hash` (mutable `update`, revocable). **Never read by the voting decision**: `buildAttestationRecord` hard-codes `attested=false`, and `voter_verification_required` **does not exist as a column** (sole occurrence `TrustPolicyEvaluator:168`) | no | yes | no | — | **LEGACY ONLY** (captured, unused) |
| 7 | Max votes per IP = 6 | **Three implementations:** (a) `NetworkBindingPolicy` + `effectiveLimit`: `Code.client_ip` count, `> intdiv(6, 2)` → **4 votes allowed**. (b) `ElectionVotingController::evaluateIpCount`: `VoterSlug.step_1_ip`, `>= ip_restriction_max_per_ip` (admin-set 1–50) or `config('app.max_use_clientIP')`. (c) whitelist: `NetworkTrustEvidence::isWhitelisted` compares an IP **hash** to stored raw entries (never matches), while `TrustPolicyEvaluator:140` `json_decode()`s an `array`-cast attribute → **`TypeError`** whenever the whitelist is non-empty [RUN in isolation: `json_decode(array)` → TypeError; not run through the controller] | partially (`max_votes_per_ip` is frozen but not read) | yes | no | — | **CONTRADICTORY** |

---

## 7. Overlay / Observation Topology Audit

**Actual topology: observational in structure, and inert in effect.**
- `OverlayAggregator::aggregate` (`:13-24`) collects all 7 signals in DI order into a flat `ConstitutionalObservationContext`. There is no priority, no strongest-signal selection and no short-circuit.
- `OverlaySignal` carries only `signalType ∈ {CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT}`, id, basis and evidence.
- **The observations never reach any decision.** `PolicySequence` does not receive them, and `ConstitutionalLegitimacyDecision` takes only the result. The "Resolver interprets them" step of the target topology **does not exist at runtime**.

**Residue that could become authority**
- `TrustSnapshotAssembler:29-37` picks the first non-stable signal as `activeOverlay` (first-wins, order-dependent). It is unconsumed.
- `EvidenceCapabilityPolicy:112-125` turns `ADDITIONAL_ATTESTATION_PRESENT` into `HardBlock`. That is a direct overlay→deny path. It is dormant only because `simplified_overlay_signal` is never set.
- `OverlayRegistry`, `OverlayStratum::evaluationOrder`, `OverlayIdentifier::evaluationOrder` and `OverlayStratification` hold ordering and "influences" metadata. They have no app consumer.
- Vocabulary in code:
  - "Election suspended: …" text (`EmergencyConditionOverlay:28`);
  - a class named `RegistrarAttestationElevation`;
  - `trust_overlay_priority` in the constitution snapshot.

**Phantom symbols** [STATIC, `git ls-files`]
- `OverlayInfluenceContext`, `OverlayCoordinator`, `RecommendedProceduralPath`, `ConstitutionalFinding` and `PolicyDependencyGraph` have **no class definition**.
- `TrustCapabilityPolicy` (`:11,66,74`) and live tests reference them. The only file is `OverlayCoordinatorD4Test.php.disabled`.
- **The status document's workstream "Investigate `OverlayInfluenceContext`" targets a class that does not exist.**

**Overlay impurity**
- `IpVelocityOverlay` reads `env()` and queries `ElectionSecurityEvent` over 5 minutes. It counts all events for the election, not per IP.
- It reads the rows `SecurityEventRecorder` writes, which is a feedback loop.
- `DeviceAnomalyOverlay`'s inputs are hard-coded, so it always returns `CONTEXT_STABLE`.

---

## 8. Policy Purity Audit

| Policy set | Inputs | Cross-policy coupling | Returns authority? | Runtime? |
|---|---|---|---|---|
| Legacy `Verification/Network/DeviceBinding/AttestationPolicy` | `TrustCapabilityContext` (readonly, but it carries **live Eloquent `Election` and `User`**) | none direct. `NetworkBindingPolicy` consumes `currentTrustLevel()`, **an interpretation derived from attestation**, so it is indirect coupling to verification | no (`PolicyFinding`) | **yes** |
| `PolicySequence` | the three policies | short-circuit; later policies do not run; earlier `supportingFacts` merged into the result | **de facto yes** (L-1) | **yes** |
| Simplified `Verification/NetworkBinding/DeviceBindingPolicy` | readonly `ConstitutionalEvidenceSnapshot` | none | `passed` bool per policy | **no (tests only)** |
| `SimplifiedPolicySequence` | all three, no short-circuit; reason precedence `verification ?? network ?? device` | uses `Log` facade and `now()` in the Application layer | — | **no** |
| Capability policies (`Overlay`, `Evidence`, `Lifecycle`) | `CapabilityContext` with live `Election`/`User` | none | yes: they are the resolver's policies | admin path only |

Also found:
- Unused imports of non-existent types: `PolicyFinding` in `PolicySequence:8`, `PolicyIdentifier` in `ConstitutionalPolicy:5`.
- `PolicyIdentifier` enum values differ from `identifier()` values.
- A canonical-zone determinism violation, `date()` in `Domain/Election/Security/Simplified/ElectionConstitutionValidator.php`, is detected by `TemporalDeterminismAuditTest` [RUN: FAIL]. `ElectionConstitutionValidator` itself has **no caller**.

---

## 9. Snapshot Purity Audit

- `ElectionCapabilitySnapshot` is a readonly carrier [RUN: `ElectionCapabilitySnapshotTest` OK, 11/22].
- `TrustSnapshotAssembler` is a pure mapping, but it reads `election->ballot_authorization_protocol` live and computes `trusted` and a first-wins `activeOverlay` (both unconsumed).
- `SnapshotAssembler` is dead: 0 callers, and it references the non-existent `OverlaySignalCategoryContext` and `$signal->proceduralPath`.
- Layer violation: Domain `TrustEvaluationEnvelope` imports the Application `ConstitutionalTrustSnapshot` (`:5`).
- **No snapshot executes policy or grants authority.** The purity risk is latent selection logic, not active authority.

---

## 10. Execution Sequence Audit

The real voting sequence is in §3. **Authority is derived before any resolver** in these places:
- `elections.start`: `resolveIpBlock`.
- Route middleware: `vote.eligibility`, `ensure.election.voter`, slug and step middlewares.
- Inside `store`: `PolicySequence`, then `LegitimacyDecision`, then `canVote()`, then membership.

`ElectionCapabilityResolver` is **not in the sequence at all**.

Demo elections are **exempt** from gate 1 (`type !== 'demo'`), so demo runs do not exercise the constitutional gate.

Static risk: `VoteController::trackSovereigntyDivergence` declares `?int $userId` (`:1370`) but receives UUID user ids (`User` uses `HasUuids`). In PHP's coercive mode a non-numeric string passed to an `int` parameter throws `TypeError`. Every denial branch that calls it (`:1545`, `:1563`, `:1588`) is therefore expected to throw. Not executed.

---

## 11. Replay Determinism Audit

| Needed for replay | Recorded? | Evidence |
|---|---|---|
| Constitution version / hash per decision | ❌ per election only; not on events. Event version columns are static DB defaults, never set | `…_add_evaluation_summary_to_election_security_events.php:22-29` |
| Evidence | ⚠️ hashed IP, count and match type; no registered hashes, attestation or continuity | `SecurityEventRecorder:56-86` |
| All decisions | ❌ allow events sampled with `rand()` at `env('TRUST_EVENT_SAMPLE_RATE', 0.1)`, so ~90% of allows go unrecorded | `:26-28` |
| Policy identity, sequence | ✅ `policy_evaluation_sequence` | |
| Observations | ✅ overlay observations | |
| Resolver input / final outcome | ❌ `LegitimacyOutcome` not persisted (logs only). The resolver result is never persisted | `VoteController:1509-1515` |
| Timestamps / provenance / correlation | ⚠️ `recorded_at`; `voter_slug_id` null (possibly deliberate for anonymity); no request or decision id | |
| Replay harness | ❌ `ReplayDeterminismAuditTest` re-runs evaluators in memory; it never reads stored events [RUN: 3 errors] | |

**Verdict: in-memory repeatability is partially demonstrated. Replay from preserved evidence is UNPROVEN and not currently possible.** The inputs are mutable (live columns, `env`), so even the same evaluator re-run later can produce a different answer.

---

## 12. Parity Audit

**Verdict: UNIT-LEVEL ONLY. There is no runtime parity.**
- `CapabilityParitySnapshot` compares 8 dimensions: network, device, verification and trust legitimacy; overlay; protocol; lifecycle; participation. It does not compare HTTP status or persisted state.
- `LegacyVotingBehavior` **re-implements** legacy rules inside `tests/` (for example `$voteCountFromIp = 0; // In real system…`) instead of calling `ValidateVotingIp` or `VoteController`.
- `ResolverVotingBehavior` calls the real resolver with `trust: null` and hard-codes most extractor fields.
- `ConstitutionalDivergenceLedger` is in-memory. No approved divergence exists anywhere. `approvedBy` is a free string with no signature. It is never persisted, and `electionId` is typed `int` against UUIDs.
- `ConstitutionalParityIntegrationTest` makes no HTTP calls [RUN: 5 errors, `ModelNotFoundException`].
- `LogDivergenceObservations` middleware is **not wired**. Its observation window can never be active, because the config keys don't exist (`config/constitutional.php` is absent). Its insert would violate a NOT NULL FK. Its log channel `constitutional` is undefined.

---

## 13. Test Reality

All runs used `vendor/bin/phpunit --no-coverage <path>` against DB `nrna_test`, not the development `publicdigit` [RUN, by subagent; two rows re-run by the preparing session]. Local PHP 8.5.8, CI 8.2/8.3. The "3 deprecations" come from vendor `PDO::MYSQL_ATTR_SSL_CA`.

| Target | Result | Tests / Assertions | Fail / Err | Skip / Incompl / Risky | Time |
|---|---|---|---|---|---|
| `ElectionCapabilityResolverTest` | OK | 8 / 17 | 0 / 0 | – | 0.02s |
| `ElectionCapabilitySnapshotTest` | OK | 11 / 22 | 0 / 0 | – | 0.03s |
| `OrchestrationInvariantTest` | OK | 7 / 25 | 0 / 0 | – | 6.8s |
| `ElectionCapabilityResolverTrustIntegrationTest` | ERR | 8 / 1 | 0 / 7 | – | 6.7s |
| `Unit/Application/Election/Capabilities/` | FAIL | 47 / 113 | 3 / 0 | – | 9.0s |
| `Unit/Application/Election/Security/` | ERR | 218 / 522 | 3 / 3 | 1 / 10 / 5 | 14.5s |
| `Unit/Domain/Election/Security/` | ERR | 312 / 686 | 0 / 13 | 0 / 2 / 0 | 4.7s |
| `ConstitutionalImmutabilityTest` | OK | 13 / 52 | 0 / 0 | – | 6.9s |
| `ValidateVotingIpTest` (**re-run: reproduced**) | FAIL | 10 / 15 | 1 / 0 | – | 6.2s |
| `Unit/Audit/` | ERR | 35 / 229 | 2 / 3 | – | 11.6s |
| `Unit/Infrastructure/Observation/` | OK | 10 / 19 | 0 / 0 | 0 / 0 / 1 | 0.06s |
| `SovereigntyConvergenceReportTest` | OK | 7 / 26 | 0 / 0 | – | 7.1s |
| **`tests/Architecture/`** (CI merge-gate suite) | **FAIL** | 149 / 647 | 3 / 0 | 1 / 0 / 0 | 18.0s |
| 3 `Feature/Election` files | ERR | 21 / 39 | 1 / 5 | – | 6.7s |
| real-election voting-flow Feature (25 files) | ERR | 254 / 236 | 5 / 184 | – | 44.9s |

`D5ResolverIntegrationTest` was re-run by the preparing session and reproduced its 8 errors.

**What the failures mean** (the tests were not changed):
- **Tests targeting deleted or never-existing classes and APIs:**
  - `OverlayInfluenceContext` (6).
  - `App\Domain\Election\Security\OverlaySignal`, which moved to `…\Simplified\` (5).
  - `VotingTrustResult::allow()`.
  - `ConstitutionalObservationContext::$highestConcernLevel`.
  - `OverlayAggregator::getEvaluationOrder()`.
  - The D5 envelope signature: `EvaluationEnvelope` now expects `ConstitutionalObservationContext`, `OverlaySignal` is given (8).
- **Lifecycle derivation:** five independent files agree that `VotingActive` is not being derived. These are `CanEditTimelineCapabilityTest`, two Architecture tests, `ElectionPolicyStateAwareTest::test_cast_vote_allowed_during_voting_state` and `VoteControllerConstitutionalTest`. **This is the most consistent behavioural signal** and needs its own investigation.
- **Feature voting suite: dominated by fixture/schema drift, not logic.**
  - `users.is_voter`: 101.
  - unique role: 30.
  - NOT NULL `organisation_id`: 29.
  - `posts.post_id`: 8.
  - `VoteController::__construct` arity: 4.
  - **The real voting path therefore has effectively no working regression suite.**
- **Tests that never run:**
  - `tests/Unit/Domain/Election/Security/ConstitutionalSemanticRegressionTests.php` is **never collected** (the suffix is not `Test.php`). Run explicitly: OK, 12/77.
  - `OverlaySignalCategoryTest.php` declares `class OverlayInfluenceTest`.
- **Test infrastructure:** `tests/TestCase.php` applies `RefreshDatabase` globally. On pgsql this runs `migrate:fresh` on `nrna_test`, contrary to its own comment (`:59-67`, "wrapped in a transaction"). `pgsql_geo` resolves to the dev DB under testing, but it is currently unused.

**Not executed:**
- the full suite;
- `tests/Feature/Demo`;
- CI itself (`gh run list` → HTTP 401).

---

## 14. Architecture Enforcement

| Can CI detect… | Verdict | Why |
|---|---|---|
| **A.** authority outside the resolver | PARTIAL | text greps in `Http/Controllers` only. `ConstitutionalAssertionsTest` forbids `new CapabilityDecision`, but the constructor is private and policies use static factories 26× (**vacuous**). The `CapabilityDecision::(ALLOW|DENY)` constants don't exist |
| **B.** procedural vocabulary in canonical zones | NO | no directory scan. Unit reflection bans exist but are not in any CI suite. The Layer-3 check is skipped (directory missing) |
| **C.** Trust/Influence imports in canonical zones | NO | none. `Simplified` already imports `TrustLevel` and `TrustEvaluationState` |
| **D.** new middleware authority | NO | allow-lists for two method names only |
| **E.** policy-to-policy coupling | NO | `PolicyPurityTest` is not in CI |
| **F.** snapshot impurity | NO in CI | `TemporalDeterminismAuditTest` detects `date()` and is failing, but it is not in CI |

**The CI blocking gate** is `greenfield-merge-gate.yml` → `composer merge-gate`: Architecture suite + deptrac + PHPStan + GreenfieldCore.
- deptrac, PHPStan and Infection are scoped to **`app/Contexts/*` only**. The resolver and `Security/*` live in `app/Application` and `app/Domain`, outside every static tool.
- The Architecture suite currently fails 3 tests locally.
- `ElectionCapabilityArchitectureTest` globs `Capabilities/Policy/` only, so `Capabilities/Policies/` (Trust/Evidence) is never scanned.
- No AST or token analysis is used for these rules.

⚠️ **Hygiene (not repeated here):** plaintext credentials are committed in `config/database.php` (testing connection), three CI workflows and `phpunit.xml` (APP_KEY). I am recording this as a finding only; rotating them is outside this audit.

---

## 15. Canonical vs Legacy Boundary

| Component | Active? | Callers | Authority? | Isolated? | Expanding? | Deletable? |
|---|---|---|---|---|---|---|
| `Security/Simplified/*` (Application + Domain), the declared canonical zone | **NO** | tests only | n/a | yes (unused) | — | would be dead code if deleted |
| `EvidenceCapabilityPolicy` (canonical) | registered, but **always abstains** at runtime | resolver (admin path) | dormant HardBlock | no: it imports legacy `TrustEvaluationState`, and `CapabilityContext::$trust` is typed Simplified while the policy matches legacy states | — | — |
| `TrustCapabilityPolicy` (legacy) | **no** (not registered) | tests | latent | yes | no | **yes**. It references non-existent `RecommendedProceduralPath` and `strongestProceduralPath` |
| `TrustSnapshotAssembler` (legacy) | **yes** | `TrustPolicyEvaluator:65` | no | no | no | only after the voting path is re-homed |
| `SnapshotAssembler` (legacy) | no | none | no | yes | no | **yes** (broken references) |
| `TrustPolicyEvaluator` + `PolicySequence` + legacy policies | **yes: the actual voting authority** | `VoteController`, `DemoVoteController` | **yes** | — | — | no, it is the live path |

**The canonical/legacy labelling is inverted relative to runtime.** The code labelled "legacy" is what decides votes. The code labelled "canonical" runs only in tests. The developer guide says both "old pipeline remains authoritative" (`04-migration-and-testing.md:5,8`) and that E.1 is verified complete (`:133-144`).

---

## 16. Phase Completion Reconciliation

| Claimed state | Repository reality | Evidence | Verdict |
|---|---|---|---|
| D.1–D.4 "established" (status doc :311) | classes and tests exist; D4/D5 test files error against moved or missing classes | §13 | **PARTIAL** |
| **D.5** "Resolver Wiring — Controller Integration Complete … only ElectionCapabilityResolver derives authority" (`e697ffac6`) | the voting controller does not call the resolver; `TrustCapabilityPolicy` is not registered | §3, §4 | **FALSE** |
| D.5 "Connected trust evaluation to the resolver" (status doc) | trust evaluation is connected to `ConstitutionalLegitimacyDecision`, not the resolver | `VoteController:1504` | **FALSE** |
| **D.R.2** overlay signals carry no procedural authority | true for `OverlaySignal`; phantom `RecommendedProceduralPath` still referenced; overlay→HardBlock path dormant in `EvidenceCapabilityPolicy` | §7 | **PARTIAL** |
| **D.R.3** policy-to-policy coupling removed; policies emit `ConstitutionalFinding` | `ConstitutionalFinding` does not exist; the D.R.3 completion checklist is all unchecked (`claude/PolicyPurityRules.md:301-312`); indirect coupling via `currentTrustLevel()`; live sequence short-circuits | §8 | **FALSE** as "complete"; PARTIAL in substance |
| **D.R.3.5** vocabulary doctrine "established … executable via CI/AST" | the doctrine document exists; no CI/AST vocabulary enforcement; the Layer-3 test is skipped | §14 | **PARTIAL** (documented, not enforced) |
| **D.5.5** convergence audit | Audit 1 "COMPLETE" but states `ValidateVotingIp` "NOT registered in routes", **false when written**: registered since `c0aa62c09` (2025-11-29), audit added 2026-05-29. Audit 2 "FAILS". Mutation and sequence audits are blank templates | `claude/audits/D_5_5_*` | **NOT DONE**; Audit 1 contains a false finding |
| **D.2.5** "IMPLEMENTATION COMPLETE · LIVE · 13/13" | 13/13 passes [RUN]; snapshot never read; runtime reads mutable sources | §5 | **FALSE** for its purpose (tests pass; invariant unproven) |
| "Phase D is complete" (`.claude/memory/architecture_fitness_functions_recommendation.md:22`) | see above | — | **FALSE** |
| "Parity complete, 15/15" (`claude/plans/PHASE_1_COMPLETE.md`) | unit-level only; the integration test errors today | §12 | **FALSE** as runtime parity |
| C.2.5 "Resolver is sole authority source" (`c3afc0e70`, `c46b02f84`) | true only of lifecycle transitions on the admin path | §3 | **PARTIAL** |
| E.1 "Completion Conditions (Verified)" (dev guide 04:133-144) | the Simplified pipeline is not wired | §15 | **FALSE** at runtime |
| Status doc: "Structure ~90%, Semantics ~90%, Runtime 65–75%" | not measurable; the doc itself calls these estimates | — | **UNKNOWN / unmeasured**; runtime convergence evidence is far below the stated range |

---

## 17. Gap Matrix

| # | Area | Intended | Actual | Gap | Sev | Evidence | Required action | Phase |
|---|---|---|---|---|---|---|---|---|
| G-1 | Sovereignty | resolver is the only authority | voting decided by `PolicySequence` → `ConstitutionalLegitimacyDecision` + 3 more gates | resolver absent from the voting path | CRITICAL | §3, §4 | P0-D decision, then P1-1 | P0/P1 |
| G-2 | Constitutional law | runtime reads the frozen snapshot | `fromJson` unused; live columns and env read | law not applied | CRITICAL | §5 G/H | P0-2 | P0 |
| G-3 | Rule 5 | device binding enforced | registered hash null → always match | rule inert | CRITICAL | `TrustPolicyEvaluator:153-163` | P0-3 | P0 |
| G-4 | Rule 4 | vote only from the verified IP | no active comparison; middleware only logs by default | rule inert | CRITICAL | `NetworkBindingPolicy`, `ValidateVotingIp:76-81` | P0-3 | P0 |
| G-5 | Rule 6 / verification | officer attestation consumed | `attested=false`; `voter_verification_required` column absent | rule inert | HIGH | `TrustPolicyEvaluator:166-183` | P0-3 | P0 |
| G-6 | Rules 2/3 | per-election code protocol from law | global env; strict `=== 1` vs string `"1"` | dual-code cannot activate (real) | HIGH | §6 row 3 | P0-4 | P0 |
| G-7 | Rule 7 | one limit (6) from law | three implementations, effective 4 / admin 1–50 / global | contradictory | HIGH | §6 row 7 | P0-3, P4-1 | P0/P4 |
| G-8 | Whitelist | whitelisted IPs bypass | hash-vs-raw never matches; `json_decode(array)` TypeError | crash / no effect | HIGH | `TrustPolicyEvaluator:140`, `NetworkTrustEvidence:23` | P0-5 | P0 |
| G-9 | Divergence tracking | observation never breaks the flow | `?int $userId` vs UUID → TypeError on denial branches (static) | crash risk | HIGH | `VoteController:1370` | P0-5 | P0 |
| G-10 | Immutability | law unmodifiable | ORM-only; the test documents the bypass under a misleading name | partial | MEDIUM | §5 C | P3-3 | P3 |
| G-11 | Replay | reconstruct from storage | sampled events, no hash/version/outcome, mutable inputs | not possible | HIGH | §11 | P1-3 | P1 |
| G-12 | Topology | resolver interprets observations | observations unconsumed; dormant overlay→HardBlock | inert / latent | MEDIUM | §7 | P2-1 | P2 |
| G-13 | Phantom symbols | code compiles against real types | 5 phantom classes; live tests reference them | broken tests/dead code | MEDIUM | §7, §13 | P2-2 | P2 |
| G-14 | Canonical zone | canonical runs in production | Simplified is test-only | inverted boundary | HIGH | §15 | P0-D | P0 |
| G-15 | Enforcement | CI fails on violations | no CI coverage of `app/{Application,Domain}/Election`; Architecture suite failing | unenforced | HIGH | §14 | P3-1, P3-2 | P3 |
| G-16 | Parity | runtime parity + documented divergences | unit-only; ledger in-memory, empty | unproven | MEDIUM | §12 | P3-4 | P3 |
| G-17 | Test health | green, meaningful suites | 3 Architecture fails; voting feature suite 189 fail/err; uncollected test file | no regression safety | HIGH | §13 | P1-0 | P1 |
| G-18 | Lifecycle | `VotingActive` derived when the window is open | 5 files fail on it | behaviour suspect | HIGH | §13 | P1-0 | P1 |
| G-19 | Documentation | claims match code | ≥7 false completion claims | misleading | HIGH | §16 | P5-1 | P5 |
| G-20 | Hygiene | no committed secrets | credentials in config/CI/phpunit | exposure | MEDIUM (security) | §14 | separate backlog item | — |

---

## 18. Remaining TODOs

Each TODO lists ID · title · why · evidence · files · tests · dependencies · definition of done.

**P0-D · Decide the sovereignty target (a human act, not engineering)**
- **Why:** two sovereign classes exist, and "canonical" code is unwired. Engineering cannot pick which one wins.
- **Evidence:** §4, §15.
- **Files:** `ConstitutionalLegitimacyDecision`, `ElectionCapabilityResolver`, `Security/Simplified/*`.
- **Tests:** none.
- **Dependencies:** none.
- **Done when** an ADR records one of these two options:
  - (a) voting becomes a resolver action; or
  - (b) the invariant is re-scoped to two engines.

  The ADR also records whether the Simplified pipeline replaces the legacy one.

**P0-1 · Characterize current voting behaviour before changing it**
- **Why:** the voting feature suite is broken (G-17), so there is no safety net.
- **Evidence:** §13.
- **Files:** `tests/Feature/*Vote*`, factories.
- **Tests:** repair fixtures for schema drift (`users.is_voter`, `organisation_id`, `posts.post_id`) as a separately authorized test-maintenance slice. Do not change assertions.
- **Dependencies:** none.
- **Done when** the voting feature suite runs, and every failure left is a behavioural one.

**P0-2 · Runtime consumes the frozen constitution**
- **Why:** G-2.
- **Evidence:** `fromJson` has 0 callers.
- **Files:** `TrustPolicyEvaluator`, `ConstitutionalArticlesSnapshot`, `Election`.
- **Tests:** RED first:
  - changing `max_votes_per_ip` after creation does not change the outcome;
  - a hash mismatch refuses evaluation.
- **Dependencies:** P0-D, P0-1.
- **Done when** no participation input is read from a mutable column, `config()` or `env()`.

**P0-3 · Real evidence for rules 4/5/6/7**
- **Why:** G-3, G-4, G-5, G-7.
- **Evidence:** §6.
- **Files:** `TrustPolicyEvaluator::build*`, `NetworkBindingPolicy`, `NetworkTrustEvidence::effectiveLimit`, `VoterVerification`.
- **Tests:** one RED per rule:
  - mismatched fingerprint is denied;
  - mismatched IP is denied when binding is on;
  - an officer attestation is consumed;
  - the limit equals the article value.
- **Dependencies:** P0-2 and the decision on whether trust scaling of the limit is law (P0-D).
- **Done when** each rule is decided by recorded evidence and the frozen article.

**P0-4 · Code-protocol source and type defect**
- **Why:** G-6.
- **Files:** `VoteController:1936,2970,3112`, `CodeController:917`, `config/voting.php`.
- **Tests:** RED: `TWO_CODES_SYSTEM="1"` (a string) activates strict mode. Plus a per-election protocol from the snapshot.
- **Dependencies:** P0-1.
- **Done when** a single per-election protocol is read from the snapshot.

**P0-5 · Crash defects on the voting path (static; verify first)**
- **Why:** G-8, G-9.
- **Files:** `TrustPolicyEvaluator:140`, `NetworkTrustEvidence::isWhitelisted`, `VoteController:1370`.
- **Tests:** RED reproductions through `VoteController::store`.
- **Dependencies:** P0-1.
- **Done when** a whitelisted election and a denied UUID user both complete without a `TypeError`.

**P1-0 · `VotingActive` derivation**
- **Why:** G-18.
- **Evidence:** 5 failing files.
- **Tests:** the existing failing tests are the RED.
- **Done when** they pass without being edited.

**P1-1 · Single authority path for voting**
- **Why:** G-1.
- **Files:** `VoteController::store`, `PolicySequence`, the resolver.
- **Tests:** an architecture test that no controller branches on a trust or legitimacy outcome outside the resolver.
- **Dependencies:** P0-D, P0-2, P0-3.
- **Done when** the resolver is the only producer of the vote decision.

**P1-2 · Fold lifecycle and membership gates into resolver policies**
- **Why:** L-6.
- **Dependencies:** P1-1.

**P1-3 · Persisted decision record for replay**
- **Why:** G-11.
- **Contents:** every decision (no sampling), constitution hash and version, evidence hashes, findings, outcome, and a correlation id that does not identify the voter (ADR-T11 anonymity).
- **Tests:** replay reads the stored record and reproduces the outcome.
- **Dependencies:** P1-1.

**P2-1 · Observations reach the resolver or are removed**
- **Why:** G-12.
- **Includes:** deciding the dormant `EvidenceCapabilityPolicy` HardBlock and the unconsumed first-wins `activeOverlay`.
- **Dependencies:** P1-1.

**P2-2 · Remove or repair references to phantom symbols**
- **Why:** G-13.
- **Scope:** tests that target non-existent classes are re-scoped or retired by an authorized decision, never silently "fixed".
- **Dependencies:** P0-D.

**P3-1 · Bring `app/{Application,Domain}/Election` under CI**
- **Why:** G-15.
- **Scope:** deptrac and PHPStan paths, plus a suite that CI runs.
- **Dependencies:** P1-1.

**P3-2 · Make the enforcement tests effective**
- Rename or collect `ConstitutionalSemanticRegressionTests.php`.
- Scan `Capabilities/Policies/`.
- Replace the vacuous `new CapabilityDecision` check.
- Fix the 3 failing Architecture tests.
- **Dependencies:** P3-1.

**P3-3 · DB-level immutability**
- **Why:** G-10.
- **Scope:** a trigger or column privilege, and rename the misleading test.
- **Dependencies:** P0-2.

**P3-4 · Runtime parity**
- **Why:** G-16.
- **Scope:** HTTP-level legacy vs new comparison, plus persisted divergences with a constitutional basis.
- **Dependencies:** P1-1, P0-1.

**P4-1 · Delete legacy authority**
- **Scope:** `ValidateVotingIp`, `resolveIpBlock`/`evaluateIpCount`, `TrustCapabilityPolicy`, `SnapshotAssembler`, unused overlay metadata.
- **Dependencies:** P3-4 green.

**P5-1 · Correct documentation claims** listed in §16 (additive corrections), then freeze v1.0.

**Deferred / non-blocking**
- Renaming `OverlayInfluenceContext` → `ConstitutionalObservationContext`. The first class does not exist, so there is nothing to rename.
- Vocabulary polish in overlay messages.
- `D.R.5` article decomposition beyond what P0-2 needs.
- `pgsql_geo` test pinning.
- The PHP 8.5 vendor deprecation.

---

## 19. Dependency-Ordered Roadmap

```
P0-D (human decision) ─┬─> P0-2 ─> P0-3 ─┐
P0-1 (characterize) ───┼─> P0-4          ├─> P1-1 ─> P1-2
                       └─> P0-5          │     └──> P1-3 ─┐
P1-0 (VotingActive) ─────────────────────┘               │
                          P2-1, P2-2 <── P1-1            │
                          P3-1 ─> P3-2 ; P3-3 <── P0-2   │
                          P3-4 <── P1-1 + P0-1 ──────────┤
                          P4-1 <── P3-4 green            │
                          P5-1 (docs) ─> v1.0 freeze <───┘
```

---

## 20. Definition of Done (v1.0)

v1.0 is done when each of these is shown by a test that CI runs:

1. Exactly one component produces the vote decision (by an architecture test and by an HTTP-level test).
2. No participation input comes from mutable columns, `config()` or `env()`.
3. The constitution hash is verified at evaluation time.
4. Each of the 7 rules has a RED→GREEN test through the real HTTP path.
5. Every decision is persisted with hash and version.
6. A replay test reproduces a stored outcome from storage alone.
7. There are no phantom symbols.
8. Legacy gates have been deleted after runtime parity was shown.
9. §16's false claims have been corrected.

---

## 21. Final Architectural Verdict

| Invariant | Status |
|---|---|
| Sovereign authority uniqueness | **UNPROVEN**. Contradicted: 2 sovereign classes plus ≥4 in-path gates |
| Constitutional immutability | **PARTIAL**. ORM guard only, and the law is not read |
| Constitutional version / hash | **PARTIAL**. Stored at creation; version is a phase label; never verified at runtime |
| Evidence provenance | **UNPROVEN**. Key evidence fabricated by defaults |
| Observation purity | **PARTIAL**. Pure in structure, but unconsumed and with a latent overlay→deny path |
| Resolver supremacy | **UNPROVEN**. The resolver is absent from voting |
| Snapshot purity | **PARTIAL**. No authority; latent selection logic; one layer violation |
| Replay determinism | **UNPROVEN**. In-memory repeatability only |
| Parity | **UNPROVEN**. Unit-level with test-local re-implementations |
| Legacy removal | **UNPROVEN**. The legacy pipeline is the live authority |
| CI architectural enforcement | **UNPROVEN**. Zones uncovered; the gate suite is failing |

**Proven:**
- A deny-first resolver behaves correctly for lifecycle actions (8/8).
- The constitution snapshot is written and hash-consistent at creation (13/13).
- Overlays do not rank or short-circuit.

**Partially proven:** ORM-level immutability, and snapshot non-authority.

**Unproven:** everything the runtime needs in order to call itself a Constitutional Governance Runtime.

**Previous completion claims for D.5, D.R.3, D.2.5 ("live"), "Phase D complete", parity and E.1 were incorrect.**

**The exact next task: P0-D.** It is a PO/ARB decision on the sovereignty target: which engine decides votes, and whether the Simplified pipeline is the replacement. P0-1 (restoring a working voting characterization suite) may be authorized in parallel, because it changes no behaviour. **No implementation should start before P0-D.** Each P0 fix changes voting behaviour in a live election system.

---

**Traceability:**
- Code: `app/Application/Election/Services/ElectionCapabilityResolver.php`; `app/Application/Election/Security/{TrustPolicyEvaluator,PolicySequence,ConstitutionalLegitimacyDecision,SecurityEventRecorder,TrustSnapshotAssembler}.php`; `app/Domain/Election/Constitution/ConstitutionalArticlesSnapshot.php`; `app/Models/Election.php:2266-2350`; `app/Http/Controllers/{VoteController,ElectionVotingController,CodeController}.php`; `app/Http/Middleware/ValidateVotingIp.php`.
- Routes and bootstrap: `routes/election/electionRoutes.php:507`; `bootstrap/app.php:85-99`.
- Config: `config/{voting,voting_security}.php`.
- CI: `.github/workflows/greenfield-merge-gate.yml`; `composer.json` `merge-gate`.
- Claims: `claude/audits/D_2_5_Phase_Complete.md`, `claude/audits/D_5_5_*`, `claude/PolicyPurityRules.md`, `claude/plans/PHASE_1_COMPLETE.md`, `.claude/memory/architecture_fitness_functions_recommendation.md`, `developer_guide/election/real_election/security/0{1,4}-*.md`, `docs/publicdigit/brainstorming/20260930_0935_current_status.md`.
- Commits: `11710f39a`, `e697ffac6`, `89914ce3a`, `c0aa62c09`.
- Test runs: see §13; raw outputs are in the session scratchpad (not in the repository).
