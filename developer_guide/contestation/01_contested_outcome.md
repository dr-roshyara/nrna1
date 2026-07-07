# Step 1 — The ContestedOutcome: what a Challenge contests

**Layer:** Domain (pure PHP).
**Namespace:** `App\Contexts\Contestation\Domain\Challenge`.
**Delivered by:** PB-004 prerequisite step 1 (`d2fa97d30`, + polish `c9c3b6c71`).
**Traceability:** ADR-UL-01 (Ubiquitous Language Evolution) · BDR v1.1 (certified term) · ADR-T11 (anonymity) · ADR-T16 (identities as strings).

---

## Purpose

A `Challenge` contests exactly one thing. That thing used to be modelled as an opaque `TargetRef: string` — Primitive Obsession that hid a real business concept and left the target's Election *implicit*. Step 1 replaces it with the concept the certified vocabulary already names — a **`ContestedOutcome`** — and a Value Object that identifies one: **`ContestedOutcomeRef`**.

> **ContestedOutcome** — an authoritative outcome of the constitutional process that a standing-holder may legally challenge within the appeal window. Today: an **Election Result** or a **prior Determination** (certified term, BDR v1.1). A `Challenge` — and its `Determination` — is therefore scoped to **exactly one Election**.

## The Value Object

`ContestedOutcomeRef` **identifies** a `ContestedOutcome`; it does not contain vote content.

```php
final readonly class ContestedOutcomeRef      // @immutable
{
    private function __construct(
        public ElectionId $electionId,   // the single Election it belongs to
        public TargetType $type,         // ElectionResult | Determination
        public TargetId   $targetId,     // the Result/Determination id — NEVER a vote/voter id
    ) {}

    public static function of(ElectionId $electionId, TargetType $type, TargetId $targetId): self;
    public function equals(self $other): bool;
}
```

Supporting VOs (Contestation-local, ADR-T16): `ElectionId`, `TargetId` (both `fromString()`/`toString()`, non-empty — matching sibling VOs like `RaiserStandingRef`, ER-03), and:

```php
enum TargetType: string
{
    case ElectionResult = 'election_result';
    case Determination  = 'determination';
}
```

**`TargetType` is a CLOSED SET (closed by ADR-UL).** Adding a case (e.g. `Membership`) is a *Published Language change* — it flows into `DeterminationIssued` and every consumer — and is forbidden without an **ADR-UL** evolution (ER-06). Never write "etc."; extend only via ADR-UL.

## How it flows

```php
Challenge::raise(
    ChallengeId $id,
    RaiserStandingRef $raiser,
    ContestedOutcomeRef $contestedOutcome,   // ← the VO, not a string
    SubmittedContent $content,
    DateTimeImmutable $at,
): self                                        // records ChallengeRaised($…, $contestedOutcome, …)
```

`ChallengeRaised` carries the `ContestedOutcomeRef`. It is an **internal domain event** confined to Contestation — **not** part of the published language — so it may evolve **in place** (no version) until it is ever promoted to a published integration event (ADR-PL-01). The *published* crossing (`DeterminationIssued`, consumed by Election) is handled in step 2.

## Anonymity (why this is safe)

`ContestedOutcomeRef` holds `electionId · type · targetId` — a Result or Determination reference and the election it belongs to. **No `user_id` / `voter_id` / `voting_code`.** `electionId` is *not* voter↔vote linkage (`VoteAccepted` already carries `electionId`; ADR-T11 forbids linking a voter to a vote, not the election's identity). The constitutional anonymity fitness test (`GreenfieldCoreArchitectureTest`) scans Contestation and would fail on any forbidden token.

## How to extend
- **New kind of contestable thing?** That is an **ADR-UL** (ubiquitous-language) decision → add a `TargetType` case → it becomes a Published Language change (ADR-PL) → then code. Never add a `TargetType` case directly.
- **Reconstructing the VO from wire strings** (e.g. in an event hydrator) is an **Infrastructure** concern — build it in a mapper/hydrator, not on the VO (the VO owns identity/equality/invariants only; that is why there is no `fromParts()`).

## Testing
`tests/Unit/Contexts/Contestation/ChallengeTest.php` — `raised()` builds a `ContestedOutcomeRef`; `test_raise_creates_raised_and_records_event` asserts the emitted `ChallengeRaised` carries it (`electionId`, `type`). 12/12 green; Architecture + Contestation 154 passed / 1 skip; greenfield PHPStan clean.

## Pitfalls
- Don't reintroduce a string target — the whole point was to kill the primitive.
- Don't put transport/serialisation on the VO.
- Don't add a `TargetType` case without an ADR-UL — it silently changes the published language.

## Traceability
ADR-UL-01 · ADR-PL-01 · BDR v1.1 · ADR-T11 (anonymity) · ADR-T16 (string identities) · ER-03 (sibling VO consistency) · ER-06 (UL before Published Language).
