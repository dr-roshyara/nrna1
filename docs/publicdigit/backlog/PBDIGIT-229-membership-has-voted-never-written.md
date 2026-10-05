# PBDIGIT-229 — `election_memberships.has_voted` is never written after a real vote (D9)

**Type:** Defect — consistency (projection) · **Severity:** Medium · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR); unchanged until then** (PO, 2026-10-05, roadmap §2a). Interim rule: must **not** be fixed by writing `voted_at = now()` in the cast (new correlation path). |
| **Defect** | A real cast sets `codes.has_voted` (authoritative) and `voter_slugs.has_voted` / `status = voted`, but `election_memberships.has_voted` and `voted_at` stay unset — `ElectionMembership::markAsVoted()` has no caller. |
| **Evidence (FACT, synthetic data)** | `VotingIntegrityCharacterizationTest::test_d_known_gap_…` and `::test_c_…`. Consumers of the stale field: `resources/js/Pages/Elections/Voters/Index.vue` (row "voted" styling, "Voted: Cast" column, row actions hidden only when voted), `app/Services/NewsletterService.php:215, 236` (voted / not-voted audiences). |
| **Affected boundary** | participation projection ↔ Voter Management UI, newsletters |
| **Constitutional impact** | Officers see voters who voted as not having voted and are offered verify/suspend actions; newsletter audiences wrong. **A naive fix (`voted_at = now()` in the cast) would add a new timestamp correlation (PBDIGIT-226).** |
| **Proposed phase** | Phase 2.3 · ADR required (derive from `codes.has_voted`, or write without a correlatable timestamp, or remove the column). |
