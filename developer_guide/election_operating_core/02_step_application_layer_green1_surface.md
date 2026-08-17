# Step 02 — Application Layer, GREEN-1: the granted surface (behaviour deliberately absent)

> **Status:** GREEN-1 **CLOSED** (PO/ARB, 2026-08-17). Surface exists; **behaviour intentionally absent** until GREEN-2…GREEN-7. Commits: RED `1f4b4c5f` → surface `d2a0fe7c`.
> **Placement note:** this guide lives in the **existing** `election_operating_core/` area rather than a new `election/` one — one area per subsystem, consumed not duplicated (ES-005.4).

## Acceptance note — what GREEN-1 does and does not certify (PO/ARB, 2026-08-17, registered wording)

> **GREEN-1 does not certify application behaviour.**
>
> It certifies only: **namespace existence · dependency boundary · DTO shape · handler/query entry points · port wiring surface · structural compliance.**
>
> **Behavioural authority remains entirely unimplemented until GREEN-2 through GREEN-7.**

*(Recorded so no later reader mistakes "GREEN-1 closed · 16 guards passing" for behavioural certification. UC-1 behaviour arrives in step 03.)*

## Purpose

Introduces the **application layer** for the Model A operating core as **orchestration only**, over the byte-identical frozen domain. Its whole reason for existing is stated in the governance record: **the Application Layer must never become a second Domain Layer.**

## Where it fits

`app/Contexts/Election/Application/OperatingCore/` — `Command/` (5 DTOs) · `Handler/` (5) · `Query/` (4). Domain stays at `…/Domain/OperatingCore/` and is **unchanged**.

## What GREEN-1 actually is

**The surface, and nothing else.** Every handler and query body throws:

```php
public function handle(ExpressCommitteePositionCommand $command): void
{
    throw new BadMethodCallException('EM-IMPL-002 GREEN-2 pending: UC-1 behaviour is not implemented yet.');
}
```

**That is deliberate, not unfinished work:** it makes the 36 behavioural tests fail *by pending increment* while the 16 structural guards — written **before** the code — legitimately pass. A body that returned something plausible could let a behavioural pin pass vacuously; a throw cannot.

## Key files (restored from the authoring lane's version, `d2a0fe7c`)

```
app/Contexts/Election/Application/OperatingCore/
  Command/   5 final readonly DTOs (no identity surface, no caller-supplied
             instants — A-2/D-8; the constitution command carries the one
             documented typed-list exception to the no-arrays rule)
  Handler/   5 final handlers, one public handle() each (G-1), constructor
             port order pinned by the RED base fixture (§3a):
             UC-1/UC-2: repos + policy snapshot + protocol + instants
             UC-3/UC-4: repos + protocol + instants (NO policy snapshot)
             UC-5:      committee repo + protocol + instants
  Query/     4 final query services (UQ-1…UQ-4), derivation-only by contract
```

⚠️ **The differentiated port sets matter and are governed, not incidental:** UC-3 gets **no** `ServicePolicySnapshot` because restoration resumes a remaining portion and never starts a period (`EM-GOV-061`(a)); UC-5 touches nothing but the committee, the protocol and instants.

## Key design facts (each traceable, none invented here)

| Fact | Source |
|---|---|
| **Constructor-injected ports only**, from a **closed six-port universe** (3 repositories, `ProtocolAppend`, `ServicePolicySnapshot`, `InstantSource`); no new port | authorized proposal §3a |
| **No `OrganisationalAppointmentAuthority` anywhere** — the D-1 wall; verified absent in all 14 files | B-3, W-10 |
| **Commands carry no actor and no instant** — the recording instant arrives from `InstantSource` at handling | A-2, D-8 |
| Commands are `final readonly` and carry **domain value objects**, not primitives (`ElectionId`, `GateDesignation`, `CommitteeSeatId`, `AcceptancePosition`) — and **validate no business rule** | PO ruling, File-1 record §2.5 |
| **One public entry point per handler** (`handle()`), one per query (`execute()`) — names are application vocabulary, not domain vocabulary | G-1; File-1 §2.4 |
| **UQ-2 `ProgressionEligibilityQuery` is the ANSWER-shape of Meaning-2** — it answers *"can progression continue?"*; performing progression stays the Chief's | `EM-GOV-071` + A-7 |
| **Constructor parameter ORDER is convention, not architecture** — dependency **set** governed, **order** accidental; ⛔ never an ADR | File-1 §2.1 |

## How to extend — the three binding obligations for GREEN-2 onward

1. **Read the established denominator only.** UC-2 takes `RequiredVotes` from the AG-2 repository (`requiredVotes()`); it must **never validate, calculate or reinterpret** it. *The application consumes an established fact; it does not create constitutional mathematics.*
2. **No P-6 predicate logic in `ReportPeriodExpiryHandler`** — verbatim criterion: *"contains no P-6 predicate logic. It only gathers state and delegates consequence meaning."*
3. **Never reproduce domain predicates**, in any handler.

**The invariant, generalised:** the application **may** receive · load · translate · delegate · append. It **may not** interpret · calculate · decide · govern.

## Testing

`tests/Unit/Contexts/Election/OperatingCoreApplication/` — 52 tests. Current expected state: **36 failed (all `BadMethodCallException`, pending increments) · 16 passed (structural guards).** Frozen core: **42 passed / 2434 assertions** — the assertion growth over 2420 is the frozen suite's **own D-1 scan now covering the new files**, so the baseline verifies the new layer by construction. Run:

```bash
php artisan test tests/Unit/Contexts/Election/OperatingCoreApplication
php artisan test tests/Unit/Contexts/Election/OperatingCore   # must stay 42 passed
```

## Pitfalls

- ⛔ **Do not implement a throwing body "just enough to make a test green"** — the pins are behavioural contracts; a shortcut here is the forbidden shape in disguise.
- ⛔ **Never write an adapter, fake, mock or stub of the D-1 port** — not in production, not in tests.
- ⛔ **Do not invent `HandlerResult`/`ApplicationResponse`/`CommandOutcome`** — return shapes stay open until a real need appears.
- ⛔ **No `ProcessManager`/`Saga`** — only a named long-running domain process could justify one; recovery is not that, it belongs to the domain.
- ⚠️ **Q-UC5 (the constitution fact type) is OPEN with a scheduled STOP at GREEN-6** — realize it through the existing `ProtocolEntry` surface, and if a domain event class is genuinely required, **STOP**: extending the frozen domain needs its own PO authorization.

## Traceability

Authorized boundary (`A-3` IN · `A-4` OUT/fixture · `A-7` query form) · grant `G-1…G-5`, `Q-1…Q-3` · RED acceptance record · File-1 acceptance record · GREEN-1 verification (`b0ef088c`) · `EM-GOV-070`/`071` · baseline freeze.
