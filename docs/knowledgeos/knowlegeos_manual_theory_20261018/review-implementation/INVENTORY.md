# Inventory of D and C (Verification Auditor, part A)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, revision 1, approved 2026-10-29 (S0200 D11), §3 item 1. Part A states what exists. It makes no claim about alignment with a strategic design.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-29 |
| Run | **new audit** (first part-A report; reruns none) |
| C | repository commit `45c310312f1f37abd3863c3c814fda26143dea32`. Paths: `scripts/lib/EngineeringKnowledge/` (last change `a72528b29657170fecc416c34dc61b899ba345d1`); entry points `scripts/knowledge-lint.php` (`cb97d70d17c3b0aecb824502548b6b3fef1454b3`), `scripts/identifier-check.php` and `scripts/link-check.php` (both `7f04e2205bd0ffb866bad515733fb9800f7e28be`); out-of-tree tests `tests/Unit/Cohesion/` (`a72528b29657170fecc416c34dc61b899ba345d1`); wiring `composer.json` (autoload), `phpunit.xml` (suite `EngineeringKnowledge`). `git status` showed no uncommitted change in any of these paths when the audit ran. Unrelated files elsewhere in the working tree were modified; they were not inspected |
| D | `engineering/architecture/reference/Engineering_Platform_Reference_Architecture.md`: commit `c861e5d1f6c9aa24ce69dae58311511e5c5660eb` (2026-07-10), sha256 `08dfea9949a0ef7e75aab73839eda9f14131d3f054025d95a2919a73c97a7d84`. `Engineering_Decision_Model.md`: `c8906442478dbee2bf72d2b9ba64765f885a91e3` (2026-07-11), sha256 `c19339d3d570aae3ad86cae3a86c74c2542dedebcb8a323df0b5a55ed117d9a5`. `Engineering_Platform_Knowledge_Metamodel.md`: `3bdcc3dba5542af9e046d9ef4c2651a8954b6f04` (2026-07-28), sha256 `a5c88e9bdbc5a40fe38304fc728508340a69b53788f42adac90c20996b9c2b44`. ADRs: the only ADR file D cites by path is `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (`8388ce91142bd69c189bdbd8b5cf94c9db067fc4`, sha256 `7795c14bf2551ce176d34f41fbb57e9fb418bcd27f49c66d74f3535a9541454f`). Its existence was checked; its content was **not used** in part A |
| T | **none; part A** |
| Tests | (1) `vendor/bin/phpunit --testsuite EngineeringKnowledge --do-not-cache-result --colors=never` → `OK (252 tests, 603 assertions)`, exit 0. (2) `vendor/bin/phpunit tests/Unit/Cohesion --do-not-cache-result --colors=never` → `OK (187 tests, 509 assertions)`, exit 0. (3) `php scripts/identifier-check.php --series` → exit 0. (4) `php scripts/knowledge-lint.php --profile=structural --root=engineering/architecture/reference` → exit 0 (15 assessments: S4 PASS 3; S1, S2, S3 and S5 INCONCLUSIVE 3 each). Runtime: PHP 8.5.11, PHPUnit 11.5.6. `git status --short` was identical before and after the runs |
| Scope | Examined: all 122 tracked files under `scripts/lib/EngineeringKnowledge/`, the three entry points, the `tests/Unit/Cohesion/` file list and test counts, and the three files of `engineering/architecture/reference/`. **Exclusions:** (a) everything under `docs/knowledgeos/` except the mandate and this folder (mandate §2). (b) The content of ADRs and of other `engineering/` documents that D cites. Only their existence was checked, because the start prompt limits reading to `reference/` and the library. (c) The non-library code paths of the entry points (the `knowledge-lint` default EKP mode and the `link-check` link classifier). They were inventoried but not analysed. (d) `tests/Unit/Cohesion/` test bodies, which were not read |
| Other | (i) The test `Tests/BackTest/Phase0BackTest.php` runs `git show` for three commits (`0a2fa71d`, `7d3abc59`, `8307beca`) of `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` into a temporary directory (lines 69, 256–268). Running the mandated tests therefore made the test process read that path. The auditor did not view the content. (ii) The dependency figures come from the script in Appendix A, run as `python3 -I deps.py <repo-root>`. (iii) At session start, a repository hook automatically injected project-memory and working-state files that sit outside `docs/knowledgeos/`. The auditor did not open the full hook output |

Labels: **verified** = checked in code or by running; **reported** = taken from a document or docblock, not checked; **inferred** = a conclusion drawn from verified facts; **open** = not decided.

## 1. C, the code: size and shape

| # | Statement | Label |
|---|---|---|
| 1.1 | There are 123 files on disk: 122 are tracked by git, and one is an ignored Python bytecode cache (`Cohesion/Infrastructure/Python/__pycache__/extract_facts.cpython-313.pyc`, matched by `.gitignore:36`) | verified |
| 1.2 | The tracked files are 84 production PHP files (6 235 lines), 36 PHP test files (4 724 lines), 1 Python file (`extract_facts.py`, 400 lines) and 1 README (`Capabilities/IdentifierIntegrity/README.md`, 232 lines) | verified |
| 1.3 | The PSR-4 namespace `EngineeringKnowledge\` maps to `scripts/lib/EngineeringKnowledge/` (`composer.json` autoload). PHPUnit suite `EngineeringKnowledge` = `./scripts/lib/EngineeringKnowledge`, suffix `Test.php` (`phpunit.xml:44-46`) | verified |
| 1.4 | The library has 13 commits, the first on 2026-08-04 (`d61bf5e84`) and the last on 2026-09-30 (`a72528b29`) | verified |
| 1.5 | Every production class in a `Domain/` folder is declared `final`, or is an `enum` or an `interface`: Cohesion 22/22, IdentifierIntegrity 8/8, ReferenceIntegrity 9/9, VocabularyIntegrity 11/11, Shared 5/5 | verified |

## 2. C: modules and stated responsibilities

The code organises itself into four **capabilities** plus **Shared**, each split into Domain, Application and Infrastructure folders. "Capability" is the code's own word. Part A does not adopt or test that classification.

| Module | Prod files (D/A/I) | Responsibility as the code states it | Policy it cites | Label |
|---|---|---|---|---|
| **IdentifierIntegrity** (CAP-001) | 16 (8/4/4) | "Protect the integrity of governed identifiers." It checks a proposed identifier against its register before minting (`ValidateIdentifier`), and checks numbered-section uniqueness and order within one document (`ValidateDocumentLocalIntegrity`, "S1") | DP-1 / PMR-10 (README §2) | responsibility verified as code behaviour by tests passing; the policy attribution is reported |
| **ReferenceIntegrity** (CAP-004 per docblocks) | 15 (9/4/2) | Resolves `§N` and `step N` references within one document (S2), and checks that table rows have the same column count (S4). The docblock calls S4 "CAP-004-ADJACENT … no DP-1..DP-6 verbatim owns this rule" (`AssessesTableColumnCount.php`) | DP-4 | reported |
| **VocabularyIntegrity** (CAP-003 per docblocks) | 19 (11/5/3) | Finds live occurrences of retired terms and confusable identifiers such as `CASE β`/`CASE B` (S3), and competing unlabelled disposition rows (S5, called a "WARN-only heuristic") | DP-3 | reported |
| **Cohesion** | 27 (22/2/3) | Computes LCOM4 for each type declaration in one source file. It emits three evidence levels: unit set, node and edge sets with exclusion reasons, and the metric (`AnalyseCohesion.php`). Language bindings: PHP (`PhpFactExtractor`, via `token_get_all`) and Python (`PythonSemanticFactProvider`, "BOUNDED EXPERIMENT ONLY", which runs `python3 extract_facts.py` through `proc_open`, line 42) | "Decision 13.x", INV-L3-n (docblocks) | reported; the Python call is verified |
| **Shared** | 7 (5/1/1) | Closed verdict vocabulary (`Verdict`: 7 cases, 4 "emittable" — PASS, FAIL, WARN, INCONCLUSIVE, `Verdict.php:21-38`); `Assessment` (verdict and evidence); `AssuranceHandoffReport` (fail-closed aggregate); `HandoffContext` and `CheckerVersion` (provenance); `StructuralCliReporter` (terminal rendering with a fixed NOT-CHECKED statement) | AP-1, AP-8, DP-5 (docblocks) | the vocabulary is verified; its policy basis is reported |

Self-description (reported): the README says CAP-001 is "**Not a bounded context** — this is a capability (**AD-1 §6.2**)" (`IdentifierIntegrity/README.md:62`) and is "the template for every future Engineering Knowledge capability" (line 3).

Identifier citations in C (verified count over the `.php` and `.md` files of the library, top entries): CAP-001 ×29, DR-1 ×26, AP-7 ×25, R-65 ×24, DP-3 ×24, DP-4 ×21, AP-2 ×18, DR-4 ×16, AP-4 ×16, DP-1 ×15, AP-8 ×14, CAP-004 ×11, PMR-10 ×9, CAP-002 ×5, ES-005.4 ×4, CAP-003 ×3, AD-1 ×3. No `CMP-` and no `AST-` identifier occurs anywhere in the library (verified). The authority documents behind these identifiers were not read (**open**).

## 3. C: dependencies (who uses whom)

Counts are distinct imported symbols summed over files. They come from `use` statements and fully qualified names (Appendix A). Edges inside a module are not counted.

| From → To | Count | Label |
|---|---|---|
| Cohesion/Application → Cohesion/Domain | 6 | verified |
| Cohesion/Infrastructure → Cohesion/Application · Cohesion/Domain | 2 · 29 | verified |
| IdentifierIntegrity/Application → own Domain · Shared/Domain | 6 · 4 | verified |
| IdentifierIntegrity/Domain → Shared/Domain | 4 | verified |
| IdentifierIntegrity/Infrastructure → own Application · own Domain · Shared/Domain | 2 · 5 · 2 | verified |
| ReferenceIntegrity/Application → own Domain · Shared/Domain | 4 · 4 | verified |
| ReferenceIntegrity/Domain → Shared/Domain · **IdentifierIntegrity/Domain** | 4 · **1** (`ReferenceIntegrityContents.php:7` → `DocumentSectionSequence`) | verified |
| ReferenceIntegrity/Infrastructure → own Application · own Domain · **IdentifierIntegrity/Infrastructure** | 2 · 6 · **1** (`MarkdownIntraDocumentReader.php:7` → `MarkdownDocumentContentsReader`) | verified |
| VocabularyIntegrity/Application → own Domain · Shared/Domain | 6 · 4 | verified |
| VocabularyIntegrity/Domain → Shared/Domain | 4 | verified |
| VocabularyIntegrity/Infrastructure → own Application · own Domain | 3 · 8 | verified |
| Shared/Application → Shared/Domain; Shared/Infrastructure → Shared/Domain | 3; 3 | verified |

Derived facts:
- No edge runs from Domain to Application or Infrastructure, from Application to Infrastructure, or from Shared to any capability (**verified**, from the table).
- Cohesion has no edge to Shared, and no capability has an edge to Cohesion (**verified**).
- The only edges between capabilities run from ReferenceIntegrity to IdentifierIntegrity. The code states this reuse as deliberate ("ES-005.4: consume the existing register notion"; reported).

External dependencies (verified): PHP built-ins (`InvalidArgumentException`, `RuntimeException`, `Throwable`); `Symfony\Component\Yaml\Yaml` (in IdentifierIntegrity/Infrastructure and VocabularyIntegrity/Infrastructure only); PHPUnit (tests only); `python3` (in Cohesion/Infrastructure/Python only). No Laravel (`Illuminate\`) symbol is imported anywhere.

I/O placement (verified, by grep for file and process functions): reading files and starting processes happen only in Infrastructure classes. The only write call in production library code is `fwrite` to the stdin pipe of the Python child process (`PythonSemanticFactProvider.php:47`). No production library code writes to a file. No scheduler, cron, daemon or loop construct was found in the library or the three entry points.

## 4. C: entry points

| Entry point | Library services it builds | Behaviour stated | Label |
|---|---|---|---|
| `scripts/identifier-check.php` (136 lines) | `ValidateIdentifier` with `MarkdownSeriesContentsReader` and `GovernedRegisterMap`; `ValidateDocumentLocalIntegrity` (`--document`) | exit codes 0/1/2/3. "PREVENTS; it never REPAIRS". `--document` is warn-only. The governed-register configuration is `docs/knowledge/schema/governed-registers.yaml`; the run listed two series (R, PMR) and said "every other series returns INCONCLUSIVE" | wiring verified (lines 46–48, 117, 125); run verified |
| `scripts/knowledge-lint.php` (597 lines) | `--profile=structural`: S1–S5 services (lines 135–142). `--report=handoff`: `GenerateHandoffAssuranceReport` (line 192), which writes `--out` (line 197) | warn-only unless `--strict`. The default mode validates `docs/knowledge/` frontmatter **without** the library (no library class is built outside `structural_services`, `run_handoff_report` and `run_structural_profile`) | verified |
| `scripts/link-check.php` (291 lines) | `--anchors`: `ValidateIntraDocumentReferences` (line 65) | warn-only. Separately, `--apply` rewrites documents for links at confidence ≥ 99 (lines 19, 285), in code outside the library | verified |
| (Cohesion) | — | **No production entry point** builds `AnalyseCohesion` or a `SemanticFactProvider` outside the library. Its only consumers are the 38 test files under `tests/Unit/Cohesion/` | verified (grep of `scripts/` and `app/`) |

## 5. C: tests

| Location | Files | Tests (from `--list-tests`) | Result |
|---|---|---|---|
| `Capabilities/IdentifierIntegrity/Tests` | 8 | 65 | pass |
| `Capabilities/ReferenceIntegrity/Tests` | 10 | 58 | pass |
| `Capabilities/VocabularyIntegrity/Tests` | 12 | 73 | pass |
| `Shared/Tests` | 3 | 24 | pass |
| `Tests/Adapters` (they run the real CLI entry points over temporary fixtures) | 2 | 13 | pass |
| `Tests/BackTest/Phase0BackTest.php` (S1–S5 over three historical states of one governed plan, materialised from git) | 1 | 19 | pass |
| **Suite `EngineeringKnowledge` total** | 36 | **252** (603 assertions) | exit 0 |
| `tests/Unit/Cohesion/` (outside the library path; part of suite `Unit`) | 38 | **187** (509 assertions) | exit 0 |

All figures above are **verified**. Cohesion has no `Tests/` folder inside the library (verified). Its test file names group as Characterization, Correction, Experiment, Regression and Validation (verified from names). What each group means is **reported** only through file names; the bodies were not read.

## 6. D, the design: documents

| Document | Lines | Status it declares | What it defines (stated) | Label |
|---|---|---|---|---|
| Engineering Platform Reference Architecture | 124 | **DRAFT** (line 3) | "An engineering governance architecture — not a software system" (line 14). Five capabilities: Knowledge, Governance, Execution, Qualification, Evidence, each mapped to a directory (§2, lines 22–30). Flow, lifecycle, decision log with implemented, deferred and rejected items (§5), constraints (§6), evolution path (§7) | reported |
| Engineering Decision Model | 134 | **DRAFT** (line 4) | A decision index of eight `Determine*` decisions, each pointing to ES-00n authorities. Automation stance: "decisions are documented, never coded · machine verification lives in qualification instruments (periodic), not resident daemons" (line 131) | reported |
| Engineering Platform Knowledge Metamodel | 142 | **CANDIDATE — NOT ADOPTED** (line 4) | Three levels: object, artifact, document. Five knowledge types: Normative, Descriptive, Evidence, Runtime, Learning. Object catalog, including `Capability · Component · Runtime Asset` = `CAP-nn · CMP-nnn · AST-nnn` (line 67). Relationship semantics, four lifecycle axes, an "illustrative" schema with "No validator exists and none is proposed" (line 126). Boundaries: "the frozen six bounded contexts + two external domains (Phase-02)" (§8, lines 128–132) | reported |

Facts about D (verified):
- All three D documents were last changed between 2026-07-10 and 2026-07-28, before the first commit of C (2026-08-04).
- No D document contains the words `script`, `php`, `lint`, `EngineeringKnowledge`, or any `CAP-0nn` identifier (whole-word search). `tooling` occurs at Metamodel lines 16 and 25, and `validator` at line 126.
- Paths D cites exist under `engineering/`: `governance/Engineering_Execution_Protocol.md`, `architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, `knowledge/patterns/`, `knowledge/methodology/`, `verification/qualification/`, `verification/reports/` (including `2026-07-27-knowledge-domain-model.md`), `architecture/baseline/Phase-02-Domain-Model.md`, `architecture/baseline/Phase-03A-Reference-Architecture.md`, `architecture/c4/AI_Engineering_Platform_Views.md`, `MIGRATION_REPORT.md`. Their content was not read (exclusion b).

## 7. Open items from the inventory

| # | Open item |
|---|---|
| O-1 | Whether the code's `CAP-001/002/003/004` belong to the same identifier series as the Metamodel's `CAP-nn` (line 67). The registry was not read |
| O-2 | Where AD-1, DP-1..DP-6, AP-n, DR-n and PMR-10 are defined, and whether the code restates or only cites them |
| O-3 | Whether Cohesion is in operational use. No production entry point exists; its use outside tests is not visible in C |
| O-4 | What the Cohesion test-name groups mean (Characterization, Correction, Experiment, Regression, Validation) |

## Appendix A: dependency script (read-only)

This is the script that was run, with two unused variables removed (`per_file` and an output-mode switch). It produces the same output.

```python
# Read-only dependency extraction for scripts/lib/EngineeringKnowledge (auditor part A).
import os, re, sys, collections
root = sys.argv[1]
lib = os.path.join(root, 'scripts/lib/EngineeringKnowledge')
def module_of(ns_path):
    p = ns_path.split('\\')
    if p[0] != 'EngineeringKnowledge': return 'EXTERNAL:' + p[0]
    if p[1] == 'Capabilities': return f'{p[2]}/{p[3]}' if len(p) > 3 else p[2]
    if p[1] == 'Shared': return f'Shared/{p[2]}'
    return p[1]
def module_of_file(rel):
    p = rel.split('/')
    if p[0] == 'Capabilities': return f'{p[1]}/{p[2]}'
    if p[0] == 'Shared': return f'Shared/{p[1]}'
    return p[0]
edges = collections.Counter(); ext = collections.Counter(); files = collections.Counter()
for dp, dn, fn in os.walk(lib):
    dn[:] = [d for d in dn if d != '__pycache__']
    for f in fn:
        if not f.endswith('.php'): continue
        full = os.path.join(dp, f); rel = os.path.relpath(full, lib)
        src = open(full, encoding='utf-8').read()
        m = module_of_file(rel); files[m] += 1
        uses = re.findall(r'^use\s+([A-Za-z0-9_\\]+)', src, re.M)
        fq = re.findall(r'\\(EngineeringKnowledge\\[A-Za-z0-9_\\]+)', src)
        for u in set(uses) | set(fq):
            t = module_of(u)
            if t.startswith('EXTERNAL:'): ext[(m, u)] += 1
            elif t != m: edges[(m, t)] += 1
print('# files per module (.php)'); [print(f'{k}\t{v}') for k, v in sorted(files.items())]
print('# cross-module edges'); [print(f'{a} -> {b}\t{n}') for (a, b), n in sorted(edges.items())]
print('# external symbols'); [print(f'{a}\t{b}') for (a, b), n in sorted(ext.items())]
```

Other figures were produced by `git ls-files`, `wc -l`, `grep` and `phpunit --list-tests`, run at the C commit above.
