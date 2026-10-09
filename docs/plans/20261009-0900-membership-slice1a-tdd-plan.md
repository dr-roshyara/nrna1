# Plan: Membership Slice 1a, TDD (PBDIGIT-259)

**Status:** 🟢 APPROVED (Principal Reviewer instruction, 2026-10-09) · **Created:** 2026-10-09 · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1a-contract.md`

## Objective
Repository port, conflict re-decide, idempotency and creation race around the unchanged Slice 0 kernel. No database (S1-1).

## Design decisions
- **Port location:** `App\Contexts\Membership\Application\Organisational\Ports`. It carries idempotency (an application concern); this follows the precedent `Application/Membership/Ports/*RepositoryPort`.
- **Handlers:** `AdmitMemberHandler`, `EndMembershipHandler`, sharing one bounded decide-append loop.
- **In-memory reference adapter** in `tests/Doubles/` (precedent). The contract suite is an abstract PHPUnit class, so that Slice 1b's database adapter reuses it.
- **IDs are caller-supplied;** there is no clock in the application. `recordedAt` comes from the adapter's clock, clamped to be monotone.

## Checklist
- [ ] C1: repository contract tests (RED) → in-memory adapter + port (GREEN)
- [ ] C2: Admit / End handlers, happy path + refusals + authority (RED → GREEN)
- [ ] C3: conflict re-decide + creation race (RED → GREEN)
- [ ] C4: idempotency IDEM-1…4 (RED → GREEN)
- [ ] C5: reference-model property test; architecture tests
- [ ] regression, review, developer guide `02`

## Progress
- 2026-10-09: plan and contract created.
