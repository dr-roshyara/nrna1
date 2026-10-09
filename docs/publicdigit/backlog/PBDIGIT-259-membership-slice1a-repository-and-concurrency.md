# PBDIGIT-259: Membership Slice 1a, repository port, concurrency and idempotency

**Type:** Story (application layer, TDD) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-09
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable), on the Principal Reviewer's instruction `docs/publicdigit/membership/architecture/brainstorming/202061009_0812_implementation_03.md`.
**ID note:** the next free ID (`git grep "PBDIGIT-259"`: no occurrence). **Index row:** pending (README carries another session's uncommitted edit).

| | |
|---|---|
| **Status** | 🟡 AUTHORISED (Principal Reviewer, 2026-10-09). Slice 1a only. No database (Slice 1b); see contract §0 S1-1 |
| **Contract** | `docs/publicdigit/reviews/2026-10-09-membership-slice1a-contract.md` |
| **Depends on** | `PBDIGIT-258` (Slice 0). D-1 confirmed as INV-FID by the instruction |

## Acceptance criteria
1. `MemberRepositoryPort` plus the Admit / End application handlers per the contract §3–§6.
2. A reusable repository contract test suite, green against the in-memory reference adapter.
3. Tests for: the conflict path (re-authorise, re-guard, no stale reuse), idempotency (IDEM-1…4), the creation race (INV-M), tenant isolation, append-only, monotone `recordedAt`.
4. An independent reference-model property test.
5. Architecture tests: no framework or other-context dependency in the new application code.
6. The full Membership regression, with pre-existing failures separated. A review plus the developer guide `02`.
