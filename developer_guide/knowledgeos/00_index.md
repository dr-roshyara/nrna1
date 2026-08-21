# KnowledgeOS — Developer Guide

Guides for the KnowledgeOS engineering platform: its architecture, design
patterns, and governance methodology. The observation *tooling* has its own
area (`developer_guide/engineering_observations/`, guides 01–10); this area
explains the system those tools compose into.

| Step | Guide | Covers |
|---|---|---|
| 01 | [Architecture & Design Patterns](01_architecture_and_design_patterns.md) | capability–port–adapter structure, the deterministic pipeline, the design patterns in use, governance model, UL glossary, how to extend |
| 02 | [Track-2 Structural Profile](02_track2_structural_profile.md) | the S1–S5 structural checks (DI-1/DI-2/DI-5/DI-7/DI-4), the four read-only adapter entry points, D-2 fail-closed verdicts, the D-4 NOT-CHECKED statement, extending without re-implementing |

**The one sentence to understand before touching anything:**
KnowledgeOS is measured by changes in engineering behavior, not by its own
activity — every design decision below serves that sentence.

*Created 2026-08-04 on user commission, closing the architecture-phase review arc.*
