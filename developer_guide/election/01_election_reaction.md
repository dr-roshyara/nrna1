# 01 — The Election reaction slice (PB-004 GREEN)

## Purpose

When an adjudication issues a **binding determination** on a contested election outcome, the Election context must react and, if the challenge was upheld, record that a correction was applied. This slice implements that reaction end-to-end at the domain + application layers: consume `DeterminationIssued`, decide the correction inside the aggregate, and emit `ElectionCorrectionApplied`.

This is the fifth (and closing) hop of the forward-only correction loop (ADR-T8). It is **consume-only** against the frozen Messaging Platform (ARR gate: PASS) — it adds no Shared capability.

## Where it fits (layer / namespace)

```
app/Contexts/Election/
  Domain/
    ElectionId.php · DeterminationId.php          # local identities (ADR-T16)
    RulingOutcome.php · CorrectionType.php         # enums (business vocabulary)
    Election.php                                   # the aggregate — DECIDES
    Policy/ElectionCorrectionPolicy.php            # the business policy (OQ-3)
    Events/ElectionCorrectionApplied.php           # historical fact (Catalog 50-05)
    Repository/ElectionRepository.php              # persistence port (domain question only)
    Exception/DeterminationLacksElectionScope.php
    Exception/CannotApplyDeterminationToUnknownElection.php
  Application/
    DeterminationIssuedReactionHandler.php         # the inbox consumer — REACTS
    Port/ReactionEventOutbox.php                   # transport-agnostic emit port
```

The **Infrastructure** layer (Eloquent repository, outbox adapter, migration) is a **later slice** — this slice is verified entirely with unit tests over in-memory ports.

## Design decisions

- **The aggregate decides; the handler reacts.** `Election::applyDetermination()` owns the rule (consult the policy, enforce forward-only + idempotency, record the event). `DeterminationIssuedReactionHandler` only reconstructs identities, resolves the aggregate, relays the decision to the outbox, and persists. (ER-07: the RED verifies behavior — the emitted domain event — not the wire shape.)
- **`ElectionCorrectionPolicy` is a policy, not a mapping** (OQ-3): `Dismissed → null` (no correction, D-02); `Upheld → ContainedOnly` (ADR-T8). `CorrectionType::isForwardOnly()` makes the constitutional bound executable — the closed enum currently admits only forward-only corrections.
- **Three business rulings (ARB round-2/3):**
  | Situation | Outcome | Why |
  |-----------|---------|-----|
  | Payload has no election scope (schema v1) | `DeterminationLacksElectionScope` | A *business incompatibility*, not corruption or a transient fault. A v1 event was valid when written but is insufficient for this consumer. Infrastructure later dead-letters it; it is never silently dropped and never retried as transient. |
  | Election not resolvable | `CannotApplyDeterminationToUnknownElection` | Election *reacts* to existing elections; it never provisions/derives one to satisfy a determination. |
  | Determination from another organisation | `CannotApplyDeterminationToUnknownElection` | A determination is never applied across organisation boundaries; it cannot reach another org's election, so it resolves to "unknown". |
- **Repository boundary refinement (ARB round-3):** `ElectionRepository::find(ElectionId): ?Election` asks a *pure domain question*. Organisation scope is **not** in the contract — it is applied by the organisation-scoped implementation (the Eloquent repository under the ambient tenant). This keeps the Election **domain tenant-free** (ADR-T16) and avoids accreting `OrganisationId`/`CountryId`/… onto repository signatures as scoping grows.

## How it works (code)

The aggregate is the single point of decision:

```php
public function applyDetermination(DeterminationId $determinationId, RulingOutcome $outcome, DateTimeImmutable $at): void
{
    if (isset($this->appliedDeterminations[$determinationId->toString()])) {
        return; // idempotent — already applied (message OR semantic duplicate)
    }
    $correction = (new ElectionCorrectionPolicy())->decide($outcome);
    if ($correction === null) {
        return; // Dismissed ⇒ Election stays silent (D-02)
    }
    $this->appliedDeterminations[$determinationId->toString()] = true;
    $this->recordedEvents[] = new ElectionCorrectionApplied($this->id, $determinationId, $correction, $at);
}
```

The handler reconstructs Election's own identities from the published payload and relays:

```php
public function handle(InboxMessage $message): void
{
    $electionId      = $this->electionScopeOf($message);                 // v1 ⇒ DeterminationLacksElectionScope
    $determinationId = DeterminationId::fromString(/* payload */);
    $outcome         = RulingOutcome::from(/* payload 'outcome' */);
    $appliedAt       = $this->clock->now();                              // APPLICATION timestamp (injected clock)

    $election = $this->elections->find($electionId);
    if ($election === null) {
        throw CannotApplyDeterminationToUnknownElection::withId($electionId);
    }
    $election->applyDetermination($determinationId, $outcome, $appliedAt);
    $this->outbox->enqueue(...$election->pullEvents());
    $this->elections->save($election);
}
```

**`appliedAt` is the application timestamp** (Step 4A.1, ARB Q1 ruling): it records *when the Election bounded context applies the correction*, read from the injected `App\Domain\Shared\Clock\ClockInterface` (`SystemClock` in production, `FrozenClock` in tests). This is a **different fact** from the determination's issuance time — `DeterminationIssued.occurredAt` (say 10:00) and `ElectionCorrectionApplied.appliedAt` (say 10:04) coexist unmutated; the gap is real (inbox latency) and matters for latency/audit/replay. The determination's `occurredAt` is still parsed and available in the handler, but is intentionally **never** assigned to `appliedAt`; if ever surfaced it travels as its own attribute.

## How to use / extend

- **Adding the Infrastructure layer (next slice):** implement `ElectionRepository` with Eloquent under the ambient tenant (`BelongsToTenant`), and `ReactionEventOutbox` writing `ElectionCorrectionApplied` to the transactional outbox in the **same transaction** as the aggregate save (ADR-T1). Register the handler with the inbox registry (`consumerContext = 'Election'`, `eventTypes = ['DeterminationIssued']`).
- **New correction kinds** require an ADR revisiting ADR-T8 before adding a `CorrectionType` member — any non-forward-only member breaks the constitutional bound and the `isForwardOnly()` contract.

## Testing

Unit tests over in-memory ports (`tests/Unit/Contexts/Election/`):
- `ElectionCorrectionPolicyTest` — Dismissed→none; Upheld→ContainedOnly + forward-only.
- `ElectionTest` — event recorded on Upheld; silent on Dismissed; forward-only (no reverse/rescind); message idempotency; **semantic idempotency** (reconstituted-then-reapplied ⇒ no event); event boundary (no `ChallengeId`/`challengeRef`; no anonymity tokens).
- `DeterminationIssuedReactionHandlerTest` — is an inbox consumer; reconstructs from schema v2; the three rulings (v1 incompatibility · unknown → rejected, no emit · cross-org → unknown).

Gates: 13/13 Election unit tests · greenfield PHPStan (Election now in scope) · Architecture Fitness suite (regression-clean).

## Pitfalls

- **Do not put `OrganisationId` in `ElectionRepository`.** Scope belongs to the implementation/ambient tenant. A cross-org determination must resolve to `null` → `CannotApplyDeterminationToUnknownElection`.
- **Never provision an election** to satisfy a determination — reject it.
- **Do not add a reverse/rescind path.** Corrections are forward-only; anonymity forbids un-casting votes (ADR-T11).
- **Keep the event Election-owned.** `ElectionCorrectionApplied` must never carry a `ChallengeId` or any voter/vote token.
- **Schema-v1 is not corruption.** Map it to `DeterminationLacksElectionScope` (business incompatibility), not to a transient failure or a generic permanent-failure marker.

## Traceability

PB-004 Step 3 (GREEN) · Step 4A.1 (`appliedAt` = application timestamp via injected `ClockInterface`; ARB Q1) · ADR-T8 (forward-only correction loop) · ADR-T11 (anonymity) · ADR-T16 (local VOs, tenant-free domain) · ADR-UL-01 (ContestedOutcome) · ADR-PL-01 (DeterminationIssued schema v2) · D-02 (Dismissed ⇒ no correction) · OQ-3 (Election owns the correction policy) · Canonical Event Catalog 50-05 · ER-08 (reviews record, implementations repair) · ARB rulings (round 2: v1/unknown; round 3: cross-org + repository boundary refinement + semantic-duplicate test; Step 4A.1: temporal correction).
