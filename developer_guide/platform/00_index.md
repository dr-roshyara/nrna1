# Platform (routing and organisation isolation): developer guide index

**Story:** `PBDIGIT-345` (release 1, slice 2) · **Plan:** `docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md` · **Design:** `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md` (delta, revision 3), consuming D-178 and D-193.

**One rule:** every organisation-side operation runs on exactly one registry organisation's own database, or is refused.

| # | Guide | Task | Status |
|---|---|---|---|
| 00 | Test harness: one central and three organisation databases → [`../testing/07_multi_database_harness.md`](../testing/07_multi_database_harness.md) (test infrastructure lives with the test-database safety guides) | task 0 | implemented 2026-11-12 |
| 01 | [The `ServedOrganisation` domain model](01_served_organisation_domain.md) | task 1 | implemented 2026-11-12 |
| 02 | [The registry and `ResolutionService`](02_registry_and_resolution.md) | task 2 | implemented 2026-11-13 |
| 03 | [`OrganisationContext` and one connection per organisation](03_organisation_context_and_connections.md) | task 3 | implemented 2026-11-13 |
| 04 | [The organisation migration set](04_organisation_migration_set.md): organisation_identity, F-13, the two sets kept apart (T-8, T-10 part 2) | task 4 | implemented 2026-11-14 |
| 05 | [Founding an organisation (F-1)](05_founding_an_organisation.md): steps 1–2 (5a), 3 in its own process (5b), 5 with L-1 (5c); step 4 outside the slice | task 5 | implemented 2026-11-14 |
| 06 | [HTTP entry E-1](06_http_entry.md): selecting and entering an organisation | task 6 | implemented 2026-11-14 |
| 07 | [The organisation queue E-2](07_organisation_queue.md): background work in the right organisation | task 7 | implemented 2026-11-14 |
| 08 | [Fan-out E-3](08_organisation_fan_out.md): one scheduled operation, every organisation | task 8 | implemented 2026-11-14 |
| 09 | [The per-organisation outbox relay E-5](09_organisation_outbox_relay.md) | task 9 | implemented 2026-11-14 |
| 10 | [Platform's published routing contract](10_published_routing_contract.md) (open host, D-233) | task 10 | implemented 2026-11-15 |
| 11 | [The operator channel E-4](11_operator_channel.md): `platform:operate <organisation> <operation> --operator=…`, technical only, recorded and reported (T-16) | task 11 | implemented 2026-11-15 |
| 12 | [Outage and restore](12_outage_and_restore.md): fail closed and resume (T-11, T-18); the restore drill and in-place recovery (T-17, D-234) | task 12 | implemented 2026-11-15; awaiting the Principal's review |
| 13 | [Proof obligations and evidence](13_proof_obligations_and_evidence.md): D-193's 53 accounted for; T-1 … T-26; open parts → PBDIGIT-351, 352, 353 | task 13 | 2026-11-15; awaiting the Principal's review |
| 90 | [Administrator notes](90_administrator_notes.md) (living page) | from task 2 | 2026-11-15 |

*(Not to be confused with `developer_guide/platform_context/`, an older, unrelated area.)*

*(Index rebuilt 2026-11-15: from task 6 to task 12a, each update had truncated this file to its newest row; rebuilt from the task 5c version plus the guides present.)*
