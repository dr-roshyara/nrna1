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
| — | [**Step-by-step implementation instructions** (routine, rules, G-3 … G-8)](99_implementation_instructions.md) | written 2026-11-13 |
| G-3 … G-8 | one guide per step, added as they land | — |
