# C15 — Extraction and audit isolation · v1.2 (new; remediation of F-02, F-14)

**Treatment:** F-SPECIFIC. **Code:** `f_checks.check_isolation_attestation`, the `S_LANE` identifier guard, the write guard, and the reader's eligibility check.

## 1. Override
For an F-Series EXTRACTOR or AUDITOR, **this contract overrides any repository-wide or session-start instruction** to read project memory or context files, including `.claude/CLAUDE.md` workflows that say "read MEMORY.md / CONTEXT.md first". The orchestrator's prompt to the agent states this override explicitly.

## 2. Allow-list (what the agent receives and reads)
| Role | May read |
|---|---|
| both | the F-Series protocol, runbook and contracts in force (`<lane>/prompts/`) · the F-Series scripts (`<lane>/scripts/`) · the inherited S methodology texts named by the contracts (XC, P3B sections), read-only and at their pinned hashes · its own F-file **only through the reader** (`reader:F####`) · its own scratch directory (`scratch:extractor-F####` / `scratch:auditor-F####`) |
| EXTRACTOR | its own ledger files: `READ-LOG`, `PAGE-DIGESTS`, `UNITS`, `CONTENT-INVENTORY`, `UNIT-DISPOSITIONS`, `CATEGORY-CHECK`, `ISOLATION-ATTESTATION` |
| AUDITOR | `READ-LOG`, `UNITS` (via `f_units --auditor`), its own `AUDITOR-*` files — **never** the extractor's inventory, dispositions, category check or attestation |

## 3. Deny-list (never read, never quoted, never inferred)
- `.claude/CONTEXT.md`, `.claude/MEMORY.md`, `.claude/sessions/`, `.claude/plans/`, `.claude/memory/`;
- `docs/knowledgeos/chronological-read/` (the S-Series) except the named methodology texts;
- any KnowledgeOS theory, conclusion, synthesis or architecture document outside the F-file itself;
- other F-files;
- other F-IDs' ledgers;
- `<lane>/quarantine/`;
- `<lane>/audits/` (earlier audits);
- earlier analytical outputs of this lane;
- other agents' scratch directories.

## 4. Attestation
Each agent writes `ISOLATION-ATTESTATION.json` (EXTRACTOR) or `AUDITOR-ATTESTATION.json` (AUDITOR):
```json
{"run_id":"FR-F####-NNN","role":"EXTRACTOR|AUDITOR","model_id":"…",
 "received":["every document/path the prompt gave"],"read_paths":["every path it opened, or reader:F####"],
 "denied_material_consulted":false}
```
The gate refuses a path outside the role's allow-list and `denied_material_consulted ≠ false`. If denied material was seen, the agent records it truthfully and stops: that run is not isolated.

## 5. Identifier guard
S-lane identifiers are refused in every analyst-authored field at L1, L2 and L3: `S####`, `P3a`, `P3b`, `P3B`, `S5`, `S5a–c`, `G-LOG-####`, `OB####`, `H-19`, `chronological-read/`. The file's own text is quoted verbatim in quotes and anchors.

## 6. What is and is not guaranteed
| | |
|---|---|
| **Mechanical** | lane-only writes · reader eligibility · approval · identifier guard · attestation allow-list |
| **Self-attested** | what the agent read |
| **Not preventable in this harness** | an agent opening a denied file with a general-purpose tool; project instructions injected into every agent's context; model priors |
| **Detection** | the independent L1 audit's own inventory and the computed comparison; human acceptance of L1 |

Accepting this residual is **HDR-1**.
