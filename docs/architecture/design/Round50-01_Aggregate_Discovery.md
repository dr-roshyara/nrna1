# Round 50-01 — Aggregate Discovery (decision-first)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** III (Strategic→Tactical Transition) · **Built against:** Architecture Release 1.0 (BDR v1.1)
**Status:** 🧩 AGGREGATE DISCOVERY — **design, not code.** Decision-first (decision → invariant → consistency → transaction → aggregate). Within the 5 Confirmed BCs only. Feeds an Aggregate Review, then implementation.
**Date:** 2026-06-26

> **Mindset shift (per chief-architect guidance):** Phase II was *discover → evaluate → falsify → confirm*. Round 50 is **design → review → implement → measure** — a different activity; not mixed. **Decision-first, not entity-first:** (1) what business **decision** is protected? → (2) what **invariant**? → (3) **consistency** model? → (4) **transactional** boundary? → (5) only then, what **aggregate**? Then: entity-vs-aggregate · events published/consumed · application service · repository boundary · can/should it split?
> **No code.** Honors Architecture Principle 7 (invariants dominate implementation).

---

## AGG — EvidenceEnvelope  *(Evidence BC — Operational)*
1. **Decision:** which evidence set participated in an evaluation, immutably.
2. **Invariant:** frozen at creation; hash integrity (same evidence → same hash); **voter id hashed (Anonymity Q7)**.
3. **Consistency:** immediate (atomic at creation).
4. **Transaction:** one envelope = one txn (write-once).
5. **Aggregate root:** `EvidenceEnvelope` (id = `envelopeHash`); evidence items = VOs inside.
- *entity-vs-aggregate:* aggregate (enforces immutability + integrity). *events:* `EvidenceRecorded`. *consumes:* evidence inputs (Voting/Trust pipeline). *app service:* `RecordEvidence`. *repository:* append-only `EvidenceRepository` (write-once, no update/delete). *split?* **No** — atomic.
- **Replay placement (resolves BDR-06 provisionally):** **Replay = an Application Service *over* Evidence** (`VerifyReplay`), **not** an aggregate; `ReplaySession`/`ReplayCertification` = operational records (read/operational side), not domain aggregates. *(Confirm at implementation → BDR evolution entry.)*

## AGG — Vote  *(Voting BC — Operational)*
1. **Decision:** is this vote valid and anonymous?
2. **Invariant:** **no voter↔vote linkage**; uniqueness (`vote_hash`); integrity (`data_checksum`); verifiability (`receipt_hash`); selection rules (`required_number` per post).
3. **Consistency:** immediate within a vote.
4. **Transaction:** one `Vote` = one txn; **Results are NOT in this txn** (async projection).
5. **Aggregate root:** `Vote`; entities/VOs: ballot selections (`candidate_01..60`), `VoteReceipt`, `VoteHash`, `DataChecksum`.
- *entity-vs-aggregate:* `Vote` is the aggregate (anonymity + integrity in one txn). *events:* `VoteAccepted`. *consumes:* Authorization decision + Lifecycle state (read-only). *app service:* `CastVote`. *repository:* `VoteRepository` (anonymous; no user link). *split?* Ballot = entity **within** Vote (consistency boundary = whole vote); **Result is a separate projection** (not an aggregate).
- **Tactical note:** current `Vote` is Active-Record creating `Result` synchronously → recommend **Vote aggregate owns only the vote; Results = async read model** (eventual consistency). Flag for Aggregate Review.

## AGG — Mandate  *(Appointment BC — Operational)*
1. **Decision:** who holds what delegated authority, and is it active?
2. **Invariant:** valid lifecycle only (`ACTIVE → REVOKED`; `REVOKED` terminal — `DelegationLifecyclePolicy`).
3. **Consistency:** immediate within a Mandate.
4. **Transaction:** one Mandate transition = one txn.
5. **Aggregate root:** `Mandate` (holds `DelegationStatus`).
- *events:* `MandateGranted`, `MandateRevoked`. *consumes:* appointment source/eligibility (external/Appointment). *app service:* `GrantMandate`/`RevokeMandate`. *repository:* `MandateRepository`. *split?* **No.**

## AGG — Determination  *(Adjudication BC — Greenfield)*
1. **Decision:** is a contested outcome constitutionally valid → issue a **binding Determination**.
2. **Invariant:** **final once issued** (S-2 finality); reasoned; traces evidence; issued under **decisional independence**.
3. **Consistency:** immediate (atomic).
4. **Transaction:** one `Determination` = one txn.
5. **Aggregate root:** `Determination` {outcome, legitimacy, reason, `EvidenceEnvelope`-ref, finalizedAt}.
- *entity-vs-aggregate:* aggregate (enforces finality). *events:* `DeterminationIssued`. *consumes:* `Challenge` (Contestation), `EvidenceEnvelope` (Evidence). *app service:* `AdjudicateChallenge`. *repository:* `DeterminationRepository` (immutable once final). *split?* **No** (atomic); an appeal is **Contestation's** concern, not a split of Determination.
- **Note:** distinct from the committee `ConstitutionalArbitrationKernel` (separate jurisdiction concern — not this aggregate).

## AGG — Challenge  *(Contestation BC — Greenfield)*
1. **Decision:** does a party **with standing (S-5)** raise a valid challenge, and route it?
2. **Invariant:** only standing-holders raise; valid lifecycle (`Raised → Admitted/Dismissed → Routed → Resolved`); **time-bounded** (appeal window).
3. **Consistency:** immediate within a Challenge.
4. **Transaction:** one Challenge transition = one txn.
5. **Aggregate root:** `Challenge` {raiser-standing, target (outcome/Determination), status, window}.
- *events:* `ChallengeRaised`, `ChallengeAdmitted`/`Dismissed`, `ChallengeRouted`, `ChallengeResolved`. *consumes:* standing (Appointment/Authorization), the contested outcome. *app service:* `RaiseChallenge`. *repository:* `ChallengeRepository`. *split?* **No.**

---

## Cross-aggregate notes
- **The correction loop (greenfield):** `Challenge → (routed) → Determination → (issued) → Correction/Finality`. Two new aggregates (`Challenge`, `Determination`) + events; **no cross-aggregate transaction** (each atomic; linked by domain events → eventual consistency) — satisfies ADQC Q10 (no cross-context txn for an invariant).
- **Results & Legitimacy:** read models / projections (not aggregates) — `Result` from `Vote`; legitimacy outcome from `Determination`.
- **One aggregate = one transaction = one invariant set** held throughout (Evans/Vernon-consistent).

## Open questions → Aggregate Review (Round 50-02)
- Vote: confirm Results-as-async-projection (vs current synchronous creation) — consistency-model decision.
- Replay: confirm Application-Service placement at implementation (BDR-06).
- Mandate vs Committee membership: one aggregate or two (Authority/Delegation vs Committee)?
- Determination: does "Correction" (re-run/invalidate) belong to Adjudication or Lifecycle? (correction-loop terminus).

## Status
**Design only — no code.** Next: **Aggregate Review (50-02)** (validate each aggregate's invariant/consistency/transaction; resolve open questions) → Domain Event Design → Repository & Transaction Design → Implementation (greenfield Core first) → Fitness Tests.

---
*Round 50-01 — Aggregate Discovery — ISSUED (design only; decision-first).*
*5 candidate aggregates in Confirmed BCs: EvidenceEnvelope (immutable, write-once) · Vote (anonymous, integrity; Results=async projection) · Mandate (ACTIVE/REVOKED) · Determination (final-once, greenfield) · Challenge (standing, time-bounded, greenfield). Correction loop = Challenge→Determination via events (no cross-aggregate txn). Replay = Application Service over Evidence (BDR-06 provisional). Open questions → Aggregate Review. No code.*
