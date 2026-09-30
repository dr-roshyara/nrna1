# KnowledgeOS — Developer Guide

Guides for the KnowledgeOS engineering platform: its architecture, design
patterns, and governance methodology. The observation *tooling* has its own
area (`developer_guide/engineering_observations/`, guides 01–10); this area
explains the system those tools compose into.

| Step | Guide | Covers |
|---|---|---|
| 01 | [Architecture & Design Patterns](01_architecture_and_design_patterns.md) | capability–port–adapter structure, the deterministic pipeline, the design patterns in use, governance model, UL glossary, how to extend |
| 02 | [Track-2 Structural Profile](02_track2_structural_profile.md) | the S1–S5 structural checks (DI-1/DI-2/DI-5/DI-7/DI-4), the four read-only adapter entry points, D-2 fail-closed verdicts, the D-4 NOT-CHECKED statement, extending without re-implementing |
| 03 | [Phase-1 Author-Side Adoption](03_phase1_author_side_adoption.md) | the `--report=handoff` workflow — the command, the nine-element report, the FIX BEFORE HANDOFF practice (warn-only), the five-item handoff package, what remains human, the false-assurance pitfalls |
| 04 | [Session Bootstrap & Responsibility Resolution (AST-017)](04_session_bootstrap_ast017.md) | `session-bootstrap.php` — the command, the six-way block schema, AMENDMENT 2 delegation + the one V-3 bounded read, fail-closed verdicts, S-1…S-17 (incl. the S-16 V-3 boundary regression and S-17 cross-provider conformance) |
| 05 | [Activating a Commissioned Fresh Session (AST-019)](05_activate_commissioned_fresh_session_ast019.md) | `activate-commissioned-fresh-session.php` — the analyse→write path, why the authoritative fold travels forward, the **ownership-provenance defect class** (`ASD-001` + `F-1`), and how to build a fixture that can actually see it (`GO-26`…`GO-30`) |
| 06 | [Research-Gate Instrument Trust (extended 1a)](06_research_gate_instrument_trust_extended_1a.md) | the temporary theory-extraction gate runner after IC-1…IC-8: `INCONCLUSIVE` for missing inputs, `GOVERNANCE_INOPERATIVE` (exit 3), activation pins, declared = implemented scope, canonical F-ids, and the fixtures-first way to add a check |
| 07 | [`admit.py --audit` and VOID rows (L0-DEC-18)](07_admit_audit_void_rows.md) | the three receipt-stream shapes (receipt · VOID · unrecognised), fail-closed classification, and why a completed audit still reports STOP on the known 9-ID divergence (*completes ≠ passes*) |

**The one sentence to understand before touching anything:**
KnowledgeOS is measured by changes in engineering behavior, not by its own
activity — every design decision below serves that sentence.

*Created 2026-08-04 on user commission, closing the architecture-phase review arc.*
