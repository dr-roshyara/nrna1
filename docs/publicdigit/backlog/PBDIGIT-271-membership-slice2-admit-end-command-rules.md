# PBDIGIT-271: Membership Slice 2 — Admit / End command rules

**Type:** Implementation story (Membership Slice 2) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-11 · **Revised:** 2026-10-11 (plan revision 2, after two Principal Reviewer reviews) · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the Human PO / ARB decisions D-1 … D-5 of 2026-10-11. **ID note:** the next free ID (`git grep "PBDIGIT-271"` and the untracked files: no occurrence). **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `IN REVIEW` · **Slice 2a implemented and tested locally (2026-10-11), then hardened after the independent review (H1, H1-b, H2; authorised 2026-10-11); committed 2026-10-11 on the Human PO's instruction (`834a4f65c`, `14f89d18e`), not pushed; not accepted.** Implementation was authorised by the Human PO / ARB on 2026-10-11 (S2-G approved, S2-E closed). Slice 2b stays held. Spin-off: `PBDIGIT-272` |
| **Plan** | `docs/plans/20261011-0010-membership-slice2-command-rules-plan.md` (revision 5; the plan is authoritative for design, tests and acceptance; §15 holds the progress and deviations) |
| **Decisions** | D-1 … D-5: the single "Decision record" in `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md` (linked, not copied) |
| **Design** | EP-01 design §18 row 2 and §19 (`2026-10-08-membership-ep01-implementation-readiness-and-design.md:558, 589`) |

## Scope

| Slice 2a (database-free; subject of the plan) | Slice 2b (**held** with PostgreSQL) |
|---|---|
| R1 D-1 admission-basis guard (the ordinary command offers declaration-based admission only) · R2 closed ending acts (`legacy` only with attested history) · R3 **required** decision date, future dating refused · R4 interim authority for **Admit** only, End fails closed until G-1 · R5 Election-Only ledger-isolation test · R6 idempotency unchanged · R7 creation-path fitness test | container binding of the repository port to the PostgreSQL adapter and of the authority port to the interim adapter; the Eloquent role reader; End authority after G-1 |

**Not in scope:** HTTP / UI and any live ledger authority; legacy cutover (D-6 / R10); migration; PostgreSQL Part 2, F-2, F-4, F-5; legacy `MemberStatus` types; statutory, ex-officio or attested admission through the command; ending procedures (hearing, appeal).

## Acceptance criteria (authoritative list: plan §10)

- **Slice 2a: AC-1 … AC-10.**
  - D-1 enforced;
  - closed end kinds;
  - `legacy` only with attested history;
  - future dating refused, with a decision date required at every new decision;
  - Admit authority mirrored, End fail-closed;
  - ledger isolation and creation-path rules enforced, with detection proven;
  - IDEM-1 … IDEM-4 unchanged;
  - C-1 0 mismatches / 15 530 decisions, G6 hash unchanged, property and metamorphic tests green;
  - additive contract amendments and the guide;
  - no database used.
- **Slice 2b: AC-11 … AC-13.** Only after the PostgreSQL hold is lifted, its verification accepted, and G-1 decided.

## Open decisions and external dependencies (plan §12)

> **Sync 2026-10-11:** S2-A … S2-G are **decided** (plan §12; S2-E closed), and Q-E1 = A with the follow-up AUD-1. **Still open:** G-1, DS-4 (Governance); LQ-1, D-3-L (legal). The list below is the original record.

- **Human PO / ARB:** S2-A (split), S2-B (end-kind boundary), S2-C (required decision date), S2-D (four additive refusals), S2-E (D-1 guard in the domain), S2-F (creation-path fitness test).
- **Governance:** G-1 (which authority ends a membership; **unresolved, not inferred**), DS-4 (replacement criterion for the interim adapter).
- **Legal:** LQ-1 (admission bases), D-3-L (ending procedures).
- **Engineering, then PO:** Q-E1 (persist the decision date?).
