# Reference Geography (standalone): developer guide index

**Story:** `PBDIGIT-346` · **Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-standalone-tactical-completion.md` (D-226), consuming v3.11 (D-93). **New context `App\Contexts\ReferenceGeography`.** Not the legacy `App\Contexts\Geography`, which is not to be edited.

| Slice | Guide | Status |
|---|---|---|
| G-0 | Identity part and Geography's `ActingPerson` port → [`../identity/01_g0_minimal_person_and_context_person_ref.md`](../identity/01_g0_minimal_person_and_context_person_ref.md) | implemented 2026-11-13; awaiting review |
| G-1 | [The domain kernel: identities, claims, 21 kinds / 22 slot-key shapes, intervals, supersession, I-1 … I-4](01_g1_domain_kernel.md) | implemented 2026-11-13; awaiting review |
| G-2a | [The `Revision` aggregate and its content versions](02_g2a_revision_aggregate.md) | implemented 2026-11-13; G-2b, G-2c follow |
| G-2b | [Before-images and dependency predicates (gains and losses)](03_g2b_before_images_and_dependencies.md) | implemented 2026-11-13; G-2c follows |
| G-2c | [The Revision validator (recorded reads; parents, admission, I-1; structural)](04_g2c_validator.md) | implemented 2026-11-13; **G-2 at its review boundary; open questions Q1–Q4** |
| G-2d-1 | [Endings, corrections and succession (G-5, D-228)](05_g2d1_endings_and_succession.md) | implemented 2026-11-13 |
| G-2d-2 | [Territorial competence and the evidence basis (D-82, D-227)](06_g2d2_competence_and_evidence.md) | implemented 2026-11-13 |
| G-2d-3 | [Tiers, classification and positions (D-83, G-4)](07_g2d3_tiers_classification_positions.md) | implemented 2026-11-13; G-2d-4 (G-6) next |
| G-2d-4 | [Profile changes (G-6)](08_g2d4_profile_changes.md) | implemented 2026-11-13; **G-2 complete, at the Principal's acceptance boundary** |
| G-2 findings | [Country changes (F-1) and the boundary basis (F-2)](09_g2_findings_country_change_and_boundary_basis.md) | implemented 2026-11-13 |
| Q-DS | [The duplicate search is supplementary (D-229)](10_qds_duplicate_search_is_supplementary.md) | implemented 2026-11-13 |
| G-3a … G-3c | [Steward roster, Identity merges, publication governance](11_g3abc_roster_merges_governance.md) | implemented 2026-11-13 |
| G-3d | [Conflict declarations and judgment matters (D-230)](12_g3d_conflicts_and_judgment_matters.md) | implemented 2026-11-13; **G-3 at the Principal's acceptance boundary** |
| G-4a | [Publication stream schema and database guards](13_g4a_stream_schema_and_guards.md) | implemented 2026-11-13 |
| G-4b | [Rebuild paths for persistence](14_g4b_rebuild_paths.md) | implemented 2026-11-13 |
| G-4c | [The roster in PostgreSQL and the roster services](15_g4c_roster_store_and_services.md) | implemented 2026-11-13 |
| G-4d | [Revision repository and PostgreSQL atlas reader](16_g4d_revision_repository_and_atlas_reader.md) | implemented 2026-11-13 |
| G-4e | [Publication ledger under one lock](17_g4e_publication_ledger.md) | implemented 2026-11-13 |
| G-4f | [Use cases, production wiring and the no-bypass guard](18_g4f_application_wiring_and_no_bypass.md) | implemented 2026-11-13 |
| G-4g | [Concurrency matrix and consolidated mutation evidence](19_g4g_concurrency_matrix.md) | implemented 2026-11-13; **G-4 accepted with conditions 2026-11-13** |
| G-5a | [The published read model](20_g5a_published_read_model.md) | implemented 2026-11-13 |
| G-5b | [PlaceLookup](21_g5b_place_lookup.md) | implemented 2026-11-13 |
| G-5c | [The publication feed](23_g5c_publication_feed.md) | implemented 2026-11-13; **G-5 accepted 2026-11-13** |
| Admin | [Administrator notes for G-5 and G-6](22_g5_administrator_notes.md) | 2026-11-13 |
| — | [**Step-by-step implementation instructions** (routine, rules, G-3 … G-8)](99_implementation_instructions.md) | written 2026-11-13 |
| G-3 … G-8 | one guide per step, added as they land | — |
| G-6a | [Candidates](24_g6a_candidates.md) | implemented 2026-11-13 |
| G-6b | [Objections](25_g6b_objections.md) | implemented 2026-11-13 |
| G-6c-1 | [D-90 notice schema and person index](26_g6c1_notice_schema.md) | implemented 2026-11-14 |
| G-6c-2 | [D-90 domain: approval defects and the finding lifecycle](27_g6c2_approval_defects_domain.md) | implemented 2026-11-14 |
| G-6c-3 | [Identity merges and reversals with their D-90 notices, as one act](28_g6c3_atomic_identity_notices.md) | implemented 2026-11-14 |
| G-6c-4 | [Confirming a D-90 finding](29_g6c4_confirm_publication.md) | implemented 2026-11-14 |
| G-6c-5 | [The full-coverage correction link](30_g6c5_correction_link.md) | implemented 2026-11-14 |
| G-6c-6 | [The D-90 governance history, the feed fields and the notice-write guard](31_g6c6_governance_history_feed_guard.md) | implemented 2026-11-14 |
| G-7 | [CLDR display names (D-87, D-231)](32_g7_cldr_display_names.md) | implemented 2026-11-14 |
| G-8 | [The evidenced-dataset importer](33_g8_evidenced_dataset_importer.md) | importer implemented 2026-11-14; data pending the Principal's review |
| M1-1 (PBDIGIT-355) | [Frontend M1, step 1: the read-only web adapter for the explorer](34_m1_web_explorer_adapter.md) | implemented 2026-11-15; **performance stop condition reached; Principal decision pending** |
