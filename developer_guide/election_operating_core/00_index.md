# Developer Guide — Election Operating Core (Model A)

Area: `app/Contexts/Election/Domain/OperatingCore/` · Work item: `EM-IMPL-001` (first bounded increment)

| # | Guide | Covers |
|---|---|---|
| 01 | [`01_step_domain_core_first_increment.md`](01_step_domain_core_first_increment.md) | The complete first increment: the three aggregates, value objects, policies P-1…P-7, condition types, events, ports, repository interfaces |
| 02 | [`02_step_application_layer_green1_surface.md`](02_step_application_layer_green1_surface.md) | `EM-IMPL-002` GREEN-1: the granted application surface (5 commands · 5 handlers · 4 queries) — structural guards green, behaviour deliberately pending GREEN-2…7 |
| 03 | [`03_step_application_layer_green2_uc1.md`](03_step_application_layer_green2_uc1.md) | `EM-IMPL-002` GREEN-2: UC-1 `ExpressCommitteePosition` behaviour — the F-2 consequence sequence, Q-REF fixed (record + rethrow), and the five conditions registered with the approval (binding on GREEN-3 onward) |
| 04 | [`04_step_act_b_recorded_operational_status_contract.md`](04_step_act_b_recorded_operational_status_contract.md) | `EM-DOM-001` Act B: the Domain-owned `Port/RecordedOperationalStatus` retrieval contract — declared, **deliberately unimplemented and uncalled**; `H-1` structural-absence RED before it |

| — | [`operating_rules_for_implementation_lanes.md`](operating_rules_for_implementation_lanes.md) | **Step-independent: the seven operating rules binding every lane in this subsystem, plus the pre-GREEN checklist** |

> ✅ **Status (2026-08-17, final):** ACCEPTED · CLOSED · BASELINED — committed (`4b707798` tests, `a31f54f1` production), independently verified (C-5, 8/8), accepted with four conditions. **Cold-start entry point: `docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-baseline-freeze.md`.** Historical note: the provenance defect and RED-ordering caveat remain permanently recorded; the original uncommitted-status header of guide 01 is superseded by its Acceptance/Baseline sections.
