# 02 — Reaction handlers: adjudicate + resolve (PB-005 Step 5A)

## Purpose

Close the constitutional correction loop from Contestation's side: when Adjudication issues a binding determination, and when Election applies (or the challenge is dismissed), the **Challenge** records its two terminal facts — **Adjudicated** (legal finality) and **Resolved** (operational completion) — by *reacting* to inbox events. The aggregate decides; the handlers wire.

## Where it fits (layer / namespace)

```
app/Contexts/Contestation/
  Application/
    ChallengeAdjudicationReaction.php     # business: react to DeterminationIssued
    ChallengeResolutionReaction.php       # business: react to ElectionCorrectionApplied
    AdjudicateChallengeHandler.php        # InboxHandler (DeterminationIssued) — thin
    ResolveChallengeHandler.php           # InboxHandler (ElectionCorrectionApplied) — thin
    Port/ChallengeEventOutbox.php         # publish port
    Exception/{DeterminationAlreadyApplied,AwaitingAdjudication}.php   # business conditions
    Inbox/ChallengeReactionOutcomeTranslator.php                      # business → inbox marker
    Inbox/{ChallengeReactionReplay,ChallengeReactionPermanentFailure}.php  # marker impls
  Domain/Challenge/Exception/ConflictingDetermination.php             # domain invariant
```

## Design decisions

- **Two reactions, two transitions (ADR-T20/T14).** `DeterminationIssued → adjudicate()` (Routed→Adjudicated, `ChallengeAdjudicated`); `ElectionCorrectionApplied → resolve()` (Adjudicated→Resolved, `ChallengeResolved`). On a **Dismissed** determination the Election stays silent, so the adjudication reaction **short-circuits** to `resolve()` in the same transaction (T5').
- **Correlation keys differ.** `AdjudicateChallengeHandler` finds the Challenge by `challengeRef` (present on `DeterminationIssued`). `ResolveChallengeHandler` finds it by **`determinationId`** (the correction event carries no challengeId) via `findByDeterminationId` — a *correlation index*, never an identity. A not-yet-adjudicated Challenge simply has no determination, so the lookup returns null → the natural **park** signal.
- **Business vs messaging (the key boundary).** The reactions and the aggregate raise **business conditions** only: `ConflictingDetermination` (a challenge takes one binding determination), `DeterminationAlreadyApplied` (semantic replay), `AwaitingAdjudication` (temporally premature). The **only** place that knows messaging is `ChallengeReactionOutcomeTranslator`, which the thin handlers use to map a business condition to a Shared inbox marker:
  - `AwaitingAdjudication` → `CausalPreconditionMissing` (park + re-drive)
  - `DeterminationAlreadyApplied` → `IdempotentReplay` (ack, no-op)
  - `ConflictingDetermination` / `IllegalChallengeTransition` → `PermanentInboxFailure` (dead-letter + escalate)
- **Application timestamp.** Handlers pass the injected `ClockInterface`'s `now()` to the reaction; `ChallengeAdjudicated`/`ChallengeResolved` record *when Contestation reacts* (consistent with PB-004's ruling).
- **Atomicity inherited.** No Contestation TransactionManager — the reaction runs inside `Inbox::consume()`'s `DB::transaction` (ADR-T1).

## How it works (code)

```php
// ResolveChallengeHandler (thin): parse → clock → reaction → translate
public function handle(InboxMessage $message): void
{
    $determinationId = DeterminationId::fromString(/* payload determinationId */);
    try {
        $this->reaction->on($determinationId, $this->clock->now());
    } catch (DeterminationAlreadyApplied|AwaitingAdjudication|IllegalChallengeTransition $c) {
        throw $this->translator->toInboxOutcome($c);   // business → messaging, here only
    }
}
```

## Testing

- Behaviour (business, unit, in-memory doubles): `ChallengeAdjudicationReactionTest`, `ChallengeResolutionReactionTest` assert business conditions + events + state — **no inbox markers**.
- Translation (separate): `ChallengeReactionInboxTranslationTest` asserts each business condition maps to the right marker, and the handlers are Contestation inbox consumers.

## Pitfalls

- **Never** assert or throw inbox markers from the reactions — only the translator maps to messaging (keeps the Application decoupled from the platform).
- `resolution` (Upheld/Dismissed) is **not** on the `ChallengeResolved` domain event — it enriches only the published Integration Event (5C), supplied explicitly by the Application (ARB F-2).
- Don't treat a premature correction as an error — it is temporally premature (park), not invalid.

## Traceability

PB-005 Step 5A · IDD `docs/implementation/backlog/PB-005_Contestation_Reaction_Implementation_Design.md` · ADR-T20 (`Adjudicated ≠ Resolved`) · ADR-T14 (async reaction; AdjudicationService never writes Challenge) · ADR-T8 (forward-only) · ADR-T11 (anonymity) · ADR-T16 (local VOs) · Blueprint §7/§8 (park / dedupe / permanent) · ER-07 (behaviour over transport).
