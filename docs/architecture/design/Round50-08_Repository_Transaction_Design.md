# Round 50-08 — Repository & Transaction Design

**Phase III (Tactical Realization) · Built against Release 1.0 · Design only · 2026-06-26**
**Status:** 🗄️ REPOSITORY & TRANSACTION — one repo per aggregate root, transaction boundary = one aggregate, optimistic concurrency, consistency model. Concise.

## Repository ownership (one per aggregate root — frozen 50-03)
| Repository | Owns (root) | Never persists |
|------------|-------------|----------------|
| `VoteRepository` | Vote (incl. Ballot) | Result (projection), any user link |
| `EvidenceRepository` | EvidenceEnvelope | Replay artifacts |
| `MandateRepository` | Mandate | Committee internals |
| `ChallengeRepository` | Challenge (incl. submitted content) | Determination |
| `DeterminationRepository` | Determination (incl. Authority VOs) | Challenge, Election state |
| *(Election)* existing | Election aggregate | — |
- **Interface in Application/Domain; Eloquent impl in Infrastructure** (CLAUDE.md Rule 1/9). Repositories for **aggregates only** — Result/projections use Eloquent reads directly (CQRS-light Rule 5).
- A repository returns/accepts **whole aggregates**, never child rows in isolation.

## Transaction boundary (the core rule)
> **One transaction = one aggregate + its outbox row.** Never two aggregates in one txn (TP-1, ADQC Q10).

```
BEGIN
  save(aggregate)            -- root + children, version-checked
  outbox.append(events)      -- same txn (transactional outbox)
COMMIT
→ async dispatch (50-04 §5)  -- at-least-once, idempotent consumers
```
- Cross-aggregate effects happen **via events**, never a shared txn. The correction loop spans 3 aggregates = 3 txns linked causally.

## Optimistic concurrency
- Each aggregate carries `AggregateVersion`. Write asserts `WHERE version = :expected`; mismatch → `ConcurrencyConflict` → caller retries on fresh state.
- No pessimistic locks on the voting path (throughput + no long-held locks during a window).

## Consistency model
| Scope | Model | Mechanism |
|-------|-------|-----------|
| Within an aggregate | **strong** | single txn + invariants (50-06) |
| Across aggregates | **eventual** | events + outbox (effectively-once) |
| Read projections (Result/Legitimacy) | eventual | rebuilt from event log (50-05 replay=rebuild) |
| Anonymity (Q7) | **invariant, always** | no link persisted in any repo or projection |

## Idempotency & recovery (ties to 50-05/50-07)
- Consumer write paths idempotent on `EventId` (dedupe table or natural key).
- Crash between COMMIT and dispatch → outbox redelivers (no lost event).
- Parked causal waits (Challenge:Routed awaiting Determination) hold aggregate state; no txn held open.

## Fitness tests
- No transaction writes two aggregate roots (static check on repo calls within a txn).
- Every aggregate write appends to the outbox in the same txn (no event emitted outside a txn).
- No repository persists a voter↔vote link or `user_id` on Vote (Q7).
- Every write path version-checks (no blind overwrite).

## Open
- Dedupe-store shape for consumer idempotency (table vs natural-key) → implementation choice.
- Election repository already exists; align correction write with its current shape at implementation.

## Next
```
50-08 (this) ✓ → 50-09 Architecture Decision Verification (one-page checklist) → Implementation (greenfield Core, TDD)
```

---
*Round 50-08 — Repository & Transaction Design — ISSUED (design).*
*One repo per aggregate root (ownership frozen); transaction boundary = one aggregate + its outbox row (never two roots; cross-aggregate via events); optimistic concurrency (AggregateVersion, no pessimistic locks on voting path); consistency = strong intra / eventual inter / Anonymity always-invariant; idempotent recovery. Fitness tests derived. Next: 50-09 verification checklist. No code.*
