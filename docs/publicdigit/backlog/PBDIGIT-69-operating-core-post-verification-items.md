# PBDIGIT-69 — Operating-core post-verification items (C-5 findings N-3…N-6)

**Origin:** C-5 verification report (`docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-c5-verification-report.md`), acceptance condition (2). **None blocks acceptance; each is a named backlog item, not a sentence in a reply.**

| # | Item | Class |
|---|---|---|
| N-3 | `RefusalRecord` / `appointeeReference` free text: non-blank-checked but unbounded — bound it as an ADR-T11-constrained surface like `VacancyReason` (≤500) | hardening |
| N-4 | AG-1 `ElectionCommittee` and AG-3 `RecoveryProcess` lack the replay constructor AG-2 has (`fromRecordedFacts`) — B-7 parity | consistency |
| N-5 | `RecoveryProcess::$active` is redundant bookkeeping beside the interval facts — remove or derive | simplification |
| N-6 | A few one-line guards untested (per the C-5 test-quality section) | test coverage |

**Rule:** each fix is a future bounded slice under its own authorization; none may be folded silently into other work.
