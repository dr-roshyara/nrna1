# B-07 — `engineering/governance/`: the Engineering Standards and the Execution Protocol

| Field | Value |
|---|---|
| Read (full) | `STANDARDS_INDEX.md` · `ES-001` Constitution · `ES-002` Execution · `ES-003` Qualification · `ES-004` Documentation · `ES-005` Repository · `ES-006` Engineering Knowledge Governance · `Engineering_Execution_Protocol.md` (EEP) |
| Status of the sources | ES-001…006 and the index are **PROPOSED** (unratified); the EEP is **Adopted · STABLE** (2026-07-10) |
| Standing | engineering background, **not** KnowledgeOS corpus |
| Labels | [SRC] stated · [I] interpretation · [?] open |

## 1. What is new compared with B-01…B-06

| # | Finding [SRC] | Anchor |
|---|---|---|
| G-1 | **An earlier knowledge theory exists: "RQ-002".** *"The RQ-002 knowledge theory (General Knowledge Constitution + Project Knowledge Strategic Model, `docs/implementation/`) is complete-enough-to-be-falsified and frozen."* Hierarchy: **Constitution → Engineering Platform → Project Knowledge → Pilot Evidence**, with *"no Level 5 exists; do not invent one."* | ES-006.2 |
| G-2 | **The "constitutional telos":** *"knowledge exists to satisfy knowledge needs; documentation is merely one possible representation"* | ES-006.4 |
| G-3 | **Two knowledge domains, deliberately separated:** ES-006 governs **engineering** knowledge only; **Project Knowledge** is *"a separate bounded context; its standards (PKS-class) arrive only after its pilot"*. The scoping exists *"to prevent ES-006 from slowly absorbing project concepts"* | ES-006 header |
| G-4 | **Harvest:** *"Did this work REVEAL reusable engineering knowledge?"*: *"the project reveals · the engineer captures · the platform governs · engineering promotes — knowledge is discovered, never manufactured"*; "No" is the healthy common answer | ES-006.4 |
| G-5 | **Promotion ladder:** Research → Pilot → Qualification → Engineering Standard → Stable Capability; maturity vocabulary *Evidence = proven · Pattern = proven · Capability = strong hypothesis · Principle = research question* | ES-006.1 |
| G-6 | **"Documents record governance; they do not create it."** Workflow words (*continue, looks good*) are never approval | ES-001.2 |
| G-7 | **Governance orientation duty** (2026-08-16): governance must state the next governed action, in **business language first**. The separation **Observed fact → Business interpretation → Governance recommendation → Human decision → Recorded authorization → Execution**: *"each arrow is a boundary"*; governance owns only the first three | ES-001.3 |
| G-8 | **Qualification lifecycle:** findings are never fixed in-run; *"a corrected failure is never re-labelled a clean pass; history is part of the verdict"*; separate F-/CR-/OQ- series | ES-003.1 |
| G-9 | **No persisted scores;** records hold governance states (Accepted · Rejected · Deferred · Research question · Evidence required · Stable). *"Displayed values are derived, never asserted"* | ES-003.2, ES-003.3 |
| G-10 | **Mutable vs immutable:** status annotations may evolve; decision text and history never | ES-004.3 |
| G-11 | **Never a copy:** *"Governed knowledge references, assembles, validates, and contextualizes existing artifacts; it never duplicates them"* (from the Project Knowledge context invariant) | ES-005.4 |
| G-12 | **EEP:** plan → independent review → approval → implement → verify → report → decide; *"verification (how) and evidence (what) are distinct"*; *"what was not changed is reported"*; *"final authority is human"* | EEP §3, §7, §10 |
| G-13 | **Decision Authority & Verification Matrix:** three dimensions never mixed (who decides · how checked · what is automated); *"smallest automation set justified by evidence: ZERO new hooks"* | Standards Index |

## 2. A term collision found

**[?] "PKS" has two meanings in the record:**
- *"Project Knowledge Standards (PKS-class)"*: the future standards of the **Project Knowledge** bounded context (ES-006; Decision Model, Decision Catalogs).
- *"PKS — Product Knowledge System"*: a **product domain root** (placement registry; `docs/pks/README.md`).

Whether these name the same thing is not stated. It is recorded here and not resolved.

## 3. Interpretation

**[I] RQ-002 is the strongest candidate for the theoretical origin of KnowledgeOS:**
- It is a knowledge theory, dated 2026-07-11 by git.
- It is frozen as "complete-enough-to-be-falsified".
- It comes **before** both the KnowledgeOS charter (git 2026-07-12) and the corpus (Aug–Sep).
- It explicitly orders **Constitution → Engineering Platform → Project Knowledge**.

If the corpus theory builds on it, the origin story becomes: engineering governance → a general knowledge constitution (RQ-002) → the KnowledgeOS product idea → the corpus theory. This is **a hypothesis (H-B3)**, testable by reading RQ-002 and the charter.

## 4. Files found that answer the basis's open questions

All outside the corpus registry (0 rows each), so readable under the current grant. **Not yet read.**

| File (`docs/implementation/`) | Lines | git first added |
|---|---|---|
| `KnowledgeOS_Product_Discovery_Charter.md` | 37 | 2026-07-12 |
| `KnowledgeOS_Development_Constitution.md` | 165 | 2026-07-28 |
| `KnowledgeOS_Governance_Execution_Plan.md` | 97 | 2026-07-28 |
| `Project_Knowledge_Architecture_Charter.md` | 39 | 2026-07-11 |
| `Project_Knowledge_Strategic_Model.md` | 129 | 2026-07-11 |
| `RQ-002_*.md` (7 files: General Knowledge Architecture Constitutional Model, Knowledge Meta-Model, Knowledge Taxonomy, Project Knowledge Domain, Strategic Discovery Report, Architectural Synthesis, Research Findings Raw) | 825 total | 2026-07-11 |

These are exactly the **charter → constitution → execution plan** stack named by the EKS Reference Model (B-03 K-1), plus the RQ-002 knowledge theory.
