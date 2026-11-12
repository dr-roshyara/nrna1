# Platform (routing and organisation isolation): developer guide index

**Story:** `PBDIGIT-345` (release 1, slice 2) · **Plan:** `docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md` · **Design:** `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md` (delta, revision 3), consuming D-178 and D-193.

**One rule:** every organisation-side operation runs on exactly one registry organisation's own database, or is refused.

| # | Guide | Task | Status |
|---|---|---|---|
| 00 | Test harness: one central + three organisation databases → [`../testing/07_multi_database_harness.md`](../testing/07_multi_database_harness.md) (test infrastructure lives with the test-database safety guides) | task 0 | implemented 2026-11-12; awaiting review |

*(Not to be confused with `developer_guide/platform_context/`, an older, unrelated area.)*
| 01 | [The `ServedOrganisation` domain model](01_served_organisation_domain.md) | task 1 | implemented 2026-11-12; awaiting review |
