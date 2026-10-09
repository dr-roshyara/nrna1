# PBDIGIT-258: Membership Slice 0, the semantic kernel (Admit / End, Resolution, invariants, G\*)

**Type:** Story (domain implementation, TDD) · **Epic:** `PBDIGIT-EPIC-02` Membership Management · **Created:** 2026-10-09
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable), on the PO's instruction to implement Slice 0 TDD-first (`docs/publicdigit/membership/architecture/brainstorming/20261009_0730_implementation_01.md`).
**ID note:** the next free ID; `git grep "PBDIGIT-258"` found no occurrence before this file.
**Index row:** **pending.** `backlog/README.md` currently carries another session's uncommitted edits, so the row is added when that file is clean.

| | |
|---|---|
| **Status** | 🟡 **AUTHORISED (PO, 2026-10-09)**: Slice 0 only. Slice 1a needs a separate approval |
| **Contract** | `docs/publicdigit/reviews/2026-10-08-membership-slice0-semantic-design-contract.md` (§4 scope, §5 non-scope) |
| **Principal Reviewer decisions** (from the instruction) | **C-S0-1:** End with `effectiveAt ≤ period start` → refused. **C-S0-2:** an Admit without an initial term has term = ∅, so it is Lapsed, not InForce, and IsMember is false. No default term, and no ∞ by default |
| **Baseline** | the ratified architecture (act `2026-10-08-membership-architecture-ratification-act.md`), EP-01 Rev 1, the principal design review |
| **Plan** | `docs/plans/20261009-0800-membership-slice0-tdd-implementation-plan.md` |
| **Out of scope** | persistence, repositories, Laravel, HTTP, Election, Finance, Governance implementation, MembershipType, number, Suspend / Restore / Renew / ChangeType / Correct / Annul / Void, consequences, events, projections |

## Acceptance criteria

1. Pure domain under `app/Contexts/Membership/Domain/Organisational/`. An architecture test proves there is no framework, persistence, clock or random dependency, and no Election / Finance / Governance vocabulary.
2. Admit / End decisions follow contract §15–§18. Every refusal names its rule.
3. Resolution is pure and deterministic, and never calls G\* (contract Theorem 0).
4. INV-1, INV-2, INV-3, INV-EP, INV-9 and INV-COV are tested. INV-M is stable identity in Slice 0; cross-aggregate uniqueness belongs to Slice 1a / 1b.
5. Independent pointwise oracle, exhaustive bounded test (T ≤ 5, L ≤ 5), seeded property tests and metamorphic tests are in place. Differential check: the decision counts equal the research model's.
6. Implementation review `docs/publicdigit/reviews/2026-10-09-membership-slice0-tdd-implementation-review.md`, plus a developer guide.
