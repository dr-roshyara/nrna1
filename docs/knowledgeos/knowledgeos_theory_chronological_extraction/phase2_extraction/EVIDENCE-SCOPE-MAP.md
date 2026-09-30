# Evidence-Scope Map

> ⛔ **Being evidence is not the same as being part of the KnowledgeOS theory corpus.** `engineering/governance/` answers the theory's questions without becoming the theory.

| Category | Directories | md files | Role | ⛔ May it become theory? |
|---|---|---|---|---|
| **CORE KNOWLEDGEOS CORPUS** | `docs/knowledgeos/` | 9,163 *(⚠️ registry indexes only **3,081**)* | direct theory/research material | ✅ yes — this is the theory's own record |
| ⭐ **GOVERNANCE EVIDENCE** | `engineering/governance/` · `docs/adr/` | 172 · 18 | **rules that CONSTRAIN or DEFINE KnowledgeOS** | ⛔ **No.** Consulted to resolve terms; never becomes a theory object. *`ES-003.2` resolved `bar` without entering the theory* |
| **METHOD / DESIGN EVIDENCE** | `docs/architecture/` · `docs/implementation/` | 449 · 195 | the instruments the window cites (`Round39-MC`, `Round47-OP`, `RQ-002`) | ⛔ No — cited rubrics, not theory |
| **IMPLEMENTATION EVIDENCE** | `app/` · `tests/` · `scripts/` · `database/` | 8 · 3 · 3 · 2 | what actually executes | ⛔ No — evidence *about* whether theory is realized |
| **SCHEMA / EXECUTABLE EVIDENCE** | `docs/knowledge/schema/` | 10 yaml | ⭐ machine-checkable artifacts | ⛔ No — but **decisive**: validated `SI-0007` |
| **PRODUCT EVIDENCE** | `docs/publicdigit/` · `docs/pks/` · `architecture_legacy/` · `claude/` | 437 · 17 · 524 · 82 | product case law | ⛔ **Never** — F0020 `D-6b`: *"records are the producing track's"* |
| **ADMINISTRATIVE** | `.claude/` | 144 | provenance, session state | ⛔ No — provenance only |
| **OUT OF SCOPE** | `resources/` · `node_modules` · worktrees | — | unrelated | ⛔ No |

## ⭐ The scope finding

**8 of 10 instruments the 25-file window cites are present in the repository — and none is in the registry.**

| Cited instrument | Found at | In registry? |
|---|---|---|
| `ES-001` `ES-003` `ES-006` | `engineering/governance/` | ⛔ no |
| `Round39-MC` `Round47-OP` `Round38C-04` | `docs/architecture/design/` | ⛔ no |
| `RQ-002` | `docs/implementation/` | ⛔ no |
| `Platform_Capability_Pattern` | `docs/architecture/patterns/` | ⛔ no |
| `authorities.yaml` | `docs/knowledge/schema/` | ⛔ no |
| `CAP-001` | ⛔ **not found anywhere** | — |

⚠️ **And the core corpus is itself filtered:** `docs/knowledgeos/` holds **9,163** md files; the registry indexes **3,081**. Two thirds of the core corpus is outside the reconstruction's own scope.

> ⭐ **Architectural finding, not an inconvenience.** Both `bar` and `OT-0001` were resolved from material the registry excludes. **Reading more of the registry would not have answered either.**

## Proposed boundary — consult, do not absorb

```
KnowledgeOS Core Corpus  ← the theory's record (reconstructed)
        │  consults, never absorbs
        ├── Governance Evidence      resolves terms · constrains claims
        ├── Method/Design Evidence   supplies rubrics
        ├── Schema Evidence          makes claims machine-testable
        └── Implementation Evidence  tests realization
```

⛔ **Consultation is recorded in `EVIDENCE-EXPANSION.jsonl` with its question and its effect. Nothing crosses the boundary silently.**
