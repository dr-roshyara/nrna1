# Step 02 — Application Layer GREEN-1: the granted surface (EM-IMPL-002)

**What this step actually implements — and deliberately nothing more.**

## Purpose

GREEN-1 establishes the granted Increment-2 application namespace
`App\Contexts\Election\Application\OperatingCore\` so the RED suite's structural
contract binds real production classes. **No use-case behaviour exists yet**:
every handler and query body throws `BadMethodCallException` naming the GREEN
increment that will implement it. This is deliberate — a silent no-op body could
vacuously satisfy the suite's zero-mutation assertions; a throwing body cannot
make any behavioural test pass without the real domain delegation.

## Where it fits

Application layer (orchestration only — G-1/G-2): the layer receives commands,
will invoke frozen Domain operations, and will record accepted facts/refusals
through `ProtocolAppend`. It decides no election meaning, holds no domain state,
and never references the external-authority port (D-1 wall; verified by
`StructuralApplicationGuardsRedTest`, 16/16 green after this step).

## Key files

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

## Design decisions

- **Bodies throw, never no-op** — RED integrity: 36 behavioural tests keep
  failing loudly; 16 structural guards pass because the surface is real.
- **Constructor wiring = the RED fixture's pinned §3a order** — GREEN-2…7
  implement *to* this wiring; no new port may appear (grant).
- **UC-5's constitution fact type stays OPEN** (Q-UC5/EM-OPEN-045) — named in
  the handler docblock; GREEN-6 resolves it via review, never silently.

## Testing

```
php artisan test tests/Unit/Contexts/Election/OperatingCoreApplication
  → 36 failed (all BadMethodCallException "GREEN-n pending"), 16 passed
php artisan test tests/Unit/Contexts/Election/OperatingCore
  → 42 passed (frozen; assertion count grows +14 because the frozen D-1 scan
    now iterates the 14 new files — the guard working, not drift)
```

## Pitfalls

- The structural guards scan **source text including comments**: the UC-1
  handler file must not even mention the operational-condition vocabulary
  (W-2), and no application file may name the external-authority port (G-4).
- `readonly class` + promoted properties satisfies the DTO reflection guard;
  property names must avoid the identity/time regexes.

## Traceability

EM-IMPL-002 · GREEN authorization (PO/ARB 2026-08-17, this increment = GREEN-1
of the 8-step sequence) · RED baseline `1f4b4c5f` · acceptance record
`f5edb951` · proposal §3a/§8 · guards W-1…W-10, G-1…G-5, RED-1…RED-5.
