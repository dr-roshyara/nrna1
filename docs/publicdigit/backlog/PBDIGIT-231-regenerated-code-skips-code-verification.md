# PBDIGIT-231 — A regenerated code keeps the code step verified; code entry accepts any value (D6)

**Type:** Defect — voting process · **Severity:** Medium · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — not authorised.** `Code.can_vote_now` is frozen; needs an explicit decision. |
| **Defect** | When code 1 of a verified, not-yet-voted voter is regenerated (window expired), `CodeController::create` issues a new code value but keeps `can_vote_now = true` ("Preserve verified status"). `CodeController::store` then short-circuits "already verified", so **a wrong code passes the code-entry step**. |
| **Evidence (FACT, synthetic data)** | `CodeRegenerationCharacterizationTest::test_f2_known_gap_regeneration_…`, `::test_f2_known_gap_after_regeneration_a_wrong_code_passes_…`. **Mitigation observed:** the final vote compares against the *current* code value — the old code is refused, the new one (e-mailed on regeneration) accepted (`::test_f2_mitigation_…`). F1 holds: an already-voted voter obtains no new usable code (`::test_f1_…`). Sources: `CodeController.php:126-165`, `:293-296`. |
| **Affected boundary** | code-verification process (`Code.can_vote_now`) |
| **Constitutional impact** | the code step stops proving possession of the current code; the final vote still does. |
| **Proposed phase** | separate decision on `can_vote_now` semantics (outside the anonymity/authorization phases unless the PO assigns it) · ADR: not necessarily — a process decision. |
