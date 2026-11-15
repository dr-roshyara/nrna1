# PBDIGIT-355: The decision-creation guard does not list the S2 facts (Renew, Suspend, Restore)

**Status:** OPEN (created 2026-11-15) · not authorised · **Context:** Membership · **Kind:** guard gap (architecture test)
**Found by:** PBDIGIT-354 S2-3b, while checking the guards that a new use case must respect.

## Finding

`tests/Architecture/MembershipDecisionCreationTest` (R7) proves that **new `Admit` and `End` facts are created only by the `Member` aggregate** (and rehydrated only by the persistence decoder), so no code bypasses the `decide*` guards.

**The S2-1 and S2-2 facts `Renew`, `Suspend` and `Restore` are not in its list** (`scan()`: `[NS\Admit, NS\End]`). Code outside the aggregate could construct them without the guard noticing.

## Business impact

**None today:** no such code exists (the facts are built in `Member::decideRenew`, `decideSuspend`, `decideRestore` and decoded in `PostgresMemberRepository`). **But the protection that keeps every membership change behind its rules is weaker than it looks.**

## Fix (when authorised; test first)

Add `Renew`, `Suspend` and `Restore` to the scanned fact classes. Planted-violation cases for each. Same allowlist (the aggregate and the decoder).

**Traceability:** R7 (S2-F) · PBDIGIT-354 S2-1, S2-2 · CLAUDE.md "do not weaken architecture guards" (this strengthens one).
