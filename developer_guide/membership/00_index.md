# Membership (organisational) — developer guide index

**Start here:** [10: Membership in business terms: what we are building, what exists, what comes next](10_business_overview_current_state_and_plan.md). It covers the business model, the status of each capability, the gaps and the plan, with the evidence for each.

The ratified Membership architecture is implemented slice by slice under
`app/Contexts/Membership/Domain/Organisational/`. It is separate from the legacy
`Member` / `Membership*` classes (see EP-01 §3.9, collisions C-1 / C-2).

| # | Guide | Slice / story | Status |
|---|---|---|---|
| 01 | [Slice 0: the semantic kernel](01_slice0_semantic_kernel.md) | Slice 0 · `PBDIGIT-258` | implemented |
| 02 | [Slice 1a: repository port, concurrency and idempotency](02_slice1a_repository_and_concurrency.md) | Slice 1a · `PBDIGIT-259` | implemented |
| 03 | [Slice 1b: PostgreSQL adapter](03_slice1b_postgres_adapter.md) | Slice 1b · `PBDIGIT-260` | **conditionally accepted** (ACC-260, PO, 2026-11-03; conditions: PBDIGIT-266, C-1 F-1); migrations not rolled out |
| 04 | [Correction C-1: Annul and Void in the kernel](04_correction_c1_annul_void.md) | C-1 · `PBDIGIT-268` | implemented; **accepted with explicit residual risks** (PO, 2026-11-02; kernel only) |
| 05 | [C-1 acceptance evidence and the sensitivity kit](05_c1_acceptance_evidence_and_sensitivity_kit.md) | C-1 · `PBDIGIT-268` | test-only correction done; formal acceptance deferred (H-6) |
| 06 | [Option E: storable valid time (R-1a-6)](06_option_e_storable_valid_time.md) | Slice 1a/1b amendment · `PBDIGIT-264` | implemented and locally tested; PostgreSQL unverified (Part 2); not accepted |
| 07 | [F-3: key first, and a MemberId collision is an infrastructure failure](07_f3_key_first_and_member_id_collision.md) | `PBDIGIT-264` F-3 | implemented and tested on the double; PostgreSQL unverified (Part 2); not accepted |
| 08 | [Slice 2a: Admit / End command rules](08_slice2a_admit_end_command_rules.md) | Slice 2a · `PBDIGIT-271` | implemented and hardened (database-free; H1, H2); **accepted with explicit residual risks** (PO, 2026-11-02) |
| 09 | [V-2: an Annul must itself carry decision provenance](09_v2_annul_requires_decision_provenance.md) | C-1 fix · `PBDIGIT-268` | implemented 2026-11-02 (database-free); C-1 G3 still deferred |
| 10 | [Membership in business terms: current state and plan](10_business_overview_current_state_and_plan.md) | overview (no story) | documentation, 2026-11-02 |
| 11 | [The isolated PostgreSQL container for the Membership suite](11_isolated_postgres_container.md) | W2 · `PBDIGIT-324` | implemented: steps 1–5 (TDD) and step 6 (first container run: 43 tests OK on PostgreSQL 15.19, W2-2 provisional), 2026-11-02; **accepted with explicit residual risks** (PO, 2026-11-02, conditional on W2-2) |
| 13 | [Correction C-2: Correct and its consequences in the kernel](13_cor2_correct_and_consequences.md) | C-2 · `PBDIGIT-327` | implemented 2026-11-03 (kernel only, database-free; steps 1–7); **accepted** (PO, 2026-11-03, after an independent review and conditions C-1…C-3; residual risks recorded) |
| 14 | [Correction C-4: persisting Annul, Void and Correct](14_cor4_correction_persistence.md) | C-4 · `PBDIGIT-329` | implemented 2026-11-03 (steps 1–8; PostgreSQL only in the throwaway container; migration not rolled out, release gate); **accepted** (PO, 2026-11-03, after an independent review and conditions C-1…C-4, F-3, F-7; residual risks recorded) |
| 15 | [Ledger slice 1, step 1: coverage, in-force and suspended intervals](15_ledger_kernel_coverage_and_standing_intervals.md) | release-1 build slice 1 · `PBDIGIT-343` | implemented 2026-11-12 (kernel, TDD); slice not yet accepted |
| 16 | [Ledger slice 1, step 2: the per-organisation ledger clock table](16_ledger_clock_table_and_backfill.md) | `PBDIGIT-343` | implemented 2026-11-12; migration on the throwaway cluster only (release gate) |
| 17 | [Ledger slice 1, step 3: the database clock reader](17_ledger_clock_reader.md) | `PBDIGIT-343` | implemented 2026-11-12 |
| 18 | [Ledger slice 1, step 4: the D-209 append and the atomic C1 event](18_d209_append_and_c1_outbox.md) | `PBDIGIT-343` | implemented 2026-11-12; **supersedes guide 14's `max(clock, last + 1 µs)`** |
| 19 | [Ledger slice 1, step 5: a record-time refusal is a fresh attempt](19_record_time_refusal_retry.md) | `PBDIGIT-343` | implemented 2026-11-12 |
| 20 | [Ledger slice 1, step 6: the completeness point (`LedgerClockCloser`)](20_ledger_closed_through_step.md) | `PBDIGIT-343` | implemented 2026-11-12; **corrected** after the Principal's review (first-time clock-row race, deviation 4); concurrency evidence on the throwaway cluster |
| 21 | [Ledger slice 1, step 7: the C1 hydrator, so the relay delivers `MembershipCoverageResolved`](21_c1_hydrator_and_relay.md) | `PBDIGIT-343` | implemented 2026-11-12 |
| 22 | [Ledger slice 1, step 8: C2 `CoverageKnownAt`, complete or refused](22_c2_coverage_known_at.md) | `PBDIGIT-343` | implemented 2026-11-12; no production binding until slice 2 |
| 23 | [The Membership ledger in an organisation's own database](23_organisation_schema_ledger.md) | `PBDIGIT-345` | organisation migrations 300100–300300 (tasks 4–5); written 2026-11-14 to close the guide gap |
| 24 | [The ledger's C2 ports in production (M-1 … M-3)](24_ledger_ports_in_production.md) | `PBDIGIT-345` | task 10, 2026-11-14 (D-233) |
| 25 | [Renew, and the rule that every admission states its term (lifecycle S2-1)](25_renew_and_the_admission_term_rule.md) | `PBDIGIT-354` | implemented 2026-11-15; fail-closed in production until Governance S3; Q-2 (correcting a Renew) open |
| 26 | [Suspend and Restore (lifecycle S2-2)](26_suspend_and_restore.md) | `PBDIGIT-354` | implemented 2026-11-15; fail-closed in production until Governance S3; correcting their dates is S2-5 |
| 27 | [The authority contract, aligned with Governance's AuthorityCheck (lifecycle S2-3a)](27_authority_contract.md) | `PBDIGIT-354` | implemented 2026-11-15 (D-238); no migration; reviewed (report-based) |
| 28 | [Resign now and record a death (lifecycle S2-3b)](28_resign_now_and_record_a_death.md) | `PBDIGIT-354` | implemented 2026-11-15; no migration; fail-closed in production until Governance S3 |
| 29 | [The scheduled resignation declaration and D-205's single exception (lifecycle S2-4a)](29_scheduled_resignation_domain.md) | `PBDIGIT-354` | implemented 2026-11-15 (domain only); S2-4b … d to follow |

**Acceptance (2026-11-02, PO):** Slice 0 (`PBDIGIT-258`) and Slice 1a (`PBDIGIT-259`) **accepted**. C-1 G3 first **deferred** for V-2 (guide 09), then **accepted with explicit residual risks** the same day, after the V-2 fix and the W0 clean-copy run (`docs/publicdigit/membership/evidence/2026-11-02-w0-clean-copy/`). Slice 2a (`PBDIGIT-271`) **accepted with explicit residual risks** on 2026-11-02, after an independent review of the committed hardening.

**Governing documents:**
- architecture (ratified): `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`;
- implementation design: `…-membership-ep01-implementation-readiness-and-design.md` (Rev 1);
- Slice 0 contract: `…-membership-slice0-semantic-design-contract.md`.
