# PBDIGIT-232 — The final vote's security-denial branch crashes with a `TypeError` (D3)

**Type:** Defect — robustness (fail-closed) · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟢 **RESOLVED (2026-10-05)** — Phase 4 E1, commit `7aa33df68`, accepted by the PO. `trackSovereigntyDivergence(?string $userId)`; `store()` catches `\Throwable`; one fail-closed exit (`failCastClosed()`) rolls back only the cast's own transaction and releases the slug lock only when this execution acquired it. Contracts `tests/Feature/Voting/CastFailClosedTest.php` R1-A…R1-D (4/4); the Phase 1 G2 known-gap test retired. Guide `developer_guide/election/final_cast/01_e1_fail_closed_exit.md`. *(Original status: OPEN — not authorised, Phase 4.)* |
| **Defect** | When the trust gate denies (e.g. IP density above the limit), `VoteController::store` calls `trackSovereigntyDivergence(..., ?int $userId)` with the voter's UUID → `TypeError` (not caught by `catch (\Exception)`) → **HTTP 500**, before its own `DB::rollBack()`. |
| **Evidence (FACT, synthetic data)** | `FinalDenialCharacterizationTest::test_g2_known_gap_…` — four voted codes from the same IP; the cast returns 500; no vote, `has_voted` not consumed. The slug lock is not involved: the trust gate (`:1522`) runs before the lock is taken (`:1644`). Signature `VoteController.php:1365-1371`. The same mismatch affects the lifecycle (`:1563`) and membership (`:1588`) denial branches (REPORTED, not separately exercised — middleware refuses those first). |
| **Affected boundary** | final cast boundary — denial handling |
| **Constitutional impact** | fails closed only by accident (an error page instead of a denial with a reason; no audited reason). |
| **Proposed phase** | Phase 4 (fail-closed invariant, `\Throwable` → rollback + reason) · ADR: Phase 3. |
