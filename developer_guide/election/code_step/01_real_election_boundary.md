# 01 — Real-election boundary (PBDIGIT-238 R0 / R1)

**Story:** PBDIGIT-238 · **R0:** `b90547add` · **RED:** `f722520dc` · **GREEN:** `7a8407d06` · **Blueprint:** `docs/publicdigit/reviews/2026-10-05-codecontroller-refactoring-blueprint-v1.md`

## Purpose

`CodeController` serves the real-election code step but carried demo behaviour: a demo-session eligibility bypass, `real && has_voted` guards (the one-vote rule depended on the election type), and a block in `getOrCreateCode()` that **reset a consumed participation record** (`has_voted → false`) so demo voters could vote again. Demo elections reach these routes (the slug-consistency middleware only refuses *demo route + real election*, and the dashboard sends any in-progress slug to `slug.code.create`). It also wrote plaintext verification codes to the logs (VE-06).

R1 gives the controller a hard real-election boundary — nothing more. It is **not** a refactoring of the controller; the deeper decomposition is R2–R7 in the blueprint.

## R0 — discovery before change

The blueprint classifies every operation of the controller (context, membership, entitlement, voting period, IP, participation, code lifecycle, journey, agreement, audit, legacy, demo) and marks it keep / move / delete / replace / defer with its target owner. Two invariants it fixes:

- `codes.can_vote_now` = *has the code verification step been completed?* — process state, **not** authorization (target R4: `Code::isVerified()`).
- `codes.has_voted` = participation state; neither flag becomes `Code::canVote()`.

The R0 characterization test pins today's behaviour; legacy behaviour (slug presence as eligibility, PBDIGIT-231 short-circuit) is labelled *CURRENT BEHAVIOUR + LEGACY/DEFECT*, not adopted.

## How the boundary works

`getElection()` is the first call of every public action, before any Code read or write, session write, code generation, notification or step record:

```php
// Real-election boundary (PBDIGIT-238): this controller serves real elections only. Anything else
// is refused fail closed here — before any Code access, session write, code generation,
// notification or step recording in the calling action.
if ($election->type !== 'real') {
    Log::warning('Code step refused: not a real election', [
        'election_id' => $election->id,
        'route'       => $request->route()?->getName(),
    ]);
    abort(403, 'This voting step is only available for real elections.');
}
```

Only `real` and `demo` election types exist (schema and writers checked in R0), so `!== 'real'` means "demo".

The participation guards no longer depend on the election type:

```php
// Prevent double voting: participation already consumed
if ($code->has_voted) {
```

Removed: the demo branch in `isUserEligible()`, the demo re-vote block in `getOrCreateCode()`, demo comments, and the code value in every log context (`code`, `input`, `new_code`, `expected`, `submitted` → `code_id`).

## Testing

```bash
php artisan test tests/Feature/Voting/CodeControllerRealElectionBoundaryTest.php        # 10
php artisan test tests/Feature/Voting/CodeControllerRealElectionCharacterizationTest.php # 13
```

The boundary test asserts database state, not only HTTP: a demo election on any of the four routes gets 403 **and** no Code is created or modified **and** no step record or slug lifecycle field changes. Log assertions search the plaintext code value in every captured message and its JSON context.

## Pitfalls

- **Demo voters sent by the dashboard to the real routes now get a 403.** That is the PO's fail-closed decision; the routing root cause (`DashboardResolver`, missing reverse type check) is a separate item.
- **`ElectionFactory` picks `type` at random** — always set it in tests.
- Snapshot dates by raw database value (`getRawOriginal`), not by Carbon object identity — the R1 RED test first compared objects and failed for the wrong reason.
- `submitAgreement()` still logs `$request->all()` before the boundary (no code value; sensitive-logging follow-up).
- Not changed by R1: `users.can_vote` and the slug shortcut, `can_vote_now`, regeneration, the IP count, step tracking, `withoutGlobalScopes()` (PBDIGIT-235).

**Traceability:** PBDIGIT-238 · blueprint v1.0 · review `2026-10-05-real-election-code-and-vote-controller-review.md` (F-C1…F-C15) · audit Track B, VE-06, VE-09.
