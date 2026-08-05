# ADR-AIP-02 — Product Primacy (AIP-14)

| | |
|---|---|
| **Status** | **Accepted** (Chief ARB, 2026-07-08) |
| **Series** | ADR-AIP (AI Engineering Platform) |
| **Decision authority** | Architecture Review Board (Chief ARB directive, 2026-07-08; session record: `.claude/sessions/2026-07-08.md`) |
| **Drafted by** | AI platform (authority: generated); records a Human Decision Event |
| **Supersedes** | None. **Amends** (by ADR authority, per the Frozen-changes-only-via-ADR rule): `Phase-02.5-Certification-Plan.md` §3 (adds AIP-14) |
| **Related** | ADR-AIP-01 · AIP-13 (Implementation-Driven Evolution) · ER-05 (Convergence) |

## Context

Construction has begun (Iteration 1: C1 accepted). The ARB observed the structural risk of every platform: the platform starts producing platform work — C1 → C2 → C3 → registry → validator → … — until it exists for itself. The claude-flow specimen examined in Phase 1 is the terminal form of that failure. One governance safeguard is missing: a rule that binds every iteration to the product.

## Decision — AIP-14: Product Primacy

> The AI Engineering Platform exists solely to improve delivery of PublicDigit.
>
> Every implementation iteration shall produce measurable progress on a PublicDigit feature.
>
> Platform-only iterations are exceptional and require explicit ARB approval.
>
> If two consecutive iterations modify only the platform and no PublicDigit feature, the ARB shall review whether the platform is over-evolving.

**Guard host:** Design & Decision Support (registry/iteration ledger); measured via the Platform Cost metric (below) and the iteration-close protocol.

## Consequences

1. **Platform Cost metric** — ER-05 gets something concrete to measure. Derived (never asserted) at every iteration boundary from the registry, settings, and filesystem. Baseline at adoption (2026-07-08, Iteration 1/C1): **components 8 · assets 11 · scripts 6 · hook wirings 8 · commands 0 · agents 0 · registry 283 lines**.
2. **Iteration-close protocol** — every iteration ends by answering, convincingly and with evidence: *What became simpler? What became safer? What became more deterministic? What complexity did we remove? What complexity did we add? Was the addition justified by a PublicDigit feature (currently PB-004)?* An iteration that cannot answer these is rejected.
3. Iteration 1 is the sanctioned platform-only iteration (explicitly ARB-approved via the Iteration 1 Construction Plan); the exception rule applies from Iteration 2 onward.
4. Recorded in the rulings register as R-23; AIP-14 row added to `Phase-02.5-Certification-Plan.md` §3 under this ADR's authority.

---

*Traceability: Chief ARB directive 2026-07-08 · rulings register `Phase-02.5-Certification-Plan.md` §6 · Platform Cost derivation recorded in `.claude/plans/AIP-iteration-1-construction.md`.*
