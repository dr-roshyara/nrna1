# Membership–Election boundary: developer guide index

Ticket: **PBDIGIT-318** · Plan: `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` · Decision record: `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`

**Rule enforced (ratified 2026-10-08):** "There is no shared persistence and no Membership → entitlement shortcut."

| # | Step | Guide |
|---|---|---|
| S1 | Static guards against Membership ↔ Election coupling (write, read, integrity). **Closed as superseded by S1b** (PO, 2026-11-02); read guide 01 for the original design, guide 02 for what is in force | [`01_s1_static_boundary_guards.md`](01_s1_static_boundary_guards.md) |
| S1b | Hardened guards: per-target tracking, all Election models and tables, anchored table references, more foreign-key forms, detector self-tests, occurrence-count ratchet (test-only; **accepted by the PO 2026-11-02 with documented residual risks**) | [`02_s1b_hardened_boundary_guards.md`](02_s1b_hardened_boundary_guards.md) |
| S2 | Remove V1, V1b, V1c and V1d (Membership reaching into Election) | not started (gated) |
| S3 | Replace V2 (direct reads) by an approved contract | not started (gated) |
| S4 | Resolve V3′ (`voters.member_id` → `members` cascade) | not started (gated) |
