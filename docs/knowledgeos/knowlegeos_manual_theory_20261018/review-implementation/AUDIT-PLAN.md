# Audit plan for part B (Verification Auditor, part A)

Mandate: `03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`, revision 1, approved 2026-10-29, §3 item 3. The plan is organised by the **five element types**. It names types only, never elements or their content, because no strategic design exists yet. Part B starts only when the strategic-design baseline has been reviewed **and** the Principal authorises part B explicitly. This plan authorises nothing.

## Baseline (mandate §4a)

| Item | Content |
|---|---|
| Audit date | 2026-10-29 |
| Run | **new audit** |
| C | commit `45c310312f1f37abd3863c3c814fda26143dea32`; the paths are the same as in `INVENTORY.md` |
| D | the same three `reference/` commits as `INVENTORY.md` (RA `c861e5d1…`, DM `c8906442…`, MM `3bdcc3db…`); ADRs used: none |
| T | **none; part A** |
| Tests | the same four commands and results as `INVENTORY.md` |
| Scope | the plan only; no element is examined. **Exclusion:** any element content (§3, "names element types, never elements") |
| Other | **Before part B runs**, its first report must record a new baseline: the C and D commits at that time and the T baseline version with copy hashes. If C or D has changed since this plan was written, the inventory and the D-against-C comparison are **rerun** as new reports, citing these by sha256 (§4a) |

## 1. Preparation common to every element type

| Step | Method | Input from part A |
|---|---|---|
| P-1 | Rerun the inventory script (`INVENTORY.md` Appendix A) and the two test commands at the part-B C commit, then compare the counts | `INVENTORY.md` §1–§5 |
| P-2 | Close or carry forward the open items: inventory O-1…O-4, and items S-2, S-3 and S-7 of D-against-C. Read the documents they need only if the part-B commission names them | `INVENTORY.md` §7; `DESIGN-VS-CODE.md` §2–§3 |
| P-3 | Build a term table: each term of the baseline's language, set against every identifier, namespace, class and docblock term in C and every term in D. Matches are recorded with their evidence, never assumed from spelling (V-4 shows the same name used for two different things) | `DESIGN-VS-CODE.md` V-4 |
| P-4 | For each element, record its level in the baseline. Elements below S2 are compared and recorded, never classified as a code deviation or a defect (§4b) | the T baseline |

## 2. The plan by element type

| Element type | Parts of C examined | Parts of D examined | Method | Evidence produced |
|---|---|---|---|---|
| **Subdomains** | The module set: the four capabilities, Shared, and the entry points with the modes that do and do not use the library (`INVENTORY.md` §2, §4). Stated responsibilities and the policies cited | RA §2 (five capabilities), MM §3–§4 (types and catalog), MM §8 (boundaries) | Responsibility mapping: list each module's stated and tested responsibility, then assign it to zero, one or several subdomains of the given type. Unassigned modules and empty subdomains are listed, not resolved | A mapping table: module → subdomain type, with file evidence |
| **Bounded contexts** | Namespace and folder boundaries; the dependency graph (`INVENTORY.md` §3), including the two ReferenceIntegrity → IdentifierIntegrity edges; the shared model (`Shared/Domain`); the self-declaration "not a bounded context" (README:62) | MM §8 (the existing six-context reference); RA §2 | **Dependency analysis**, rerunning the Appendix A script: compare code boundaries with context boundaries. Every cross-boundary edge is listed with its file and line. **Model-sharing analysis**: which types cross a candidate boundary | An edge table classified as inside a context or across contexts, plus a list of shared types |
| **Ownership of invariants** | The enforcing operations: domain services (`Assesses*`), policy classes (`*Policy`), value-object constructors that throw `InvalidArgumentException`, application-service fail-closed paths, `Verdict::emittable()`, `AssuranceHandoffReport` aggregation, and Cohesion's `EdgeRules` / `UnitEligibility` | Statements of invariants in D (MM §5 invariant-class caveat; DM automation stance) | **Reading of enforcing operations**: for each invariant of the given type, find *where* the code enforces it (file and line). Compare that location with the owner named in the baseline. Tests that pin the invariant are cited as evidence of enforcement, not of ownership | An invariant table: baseline owner → enforcing site(s) in C → statement in D |
| **Context map** | Who calls whom across module boundaries: entry points → services; the Cohesion port `SemanticFactProvider` with two adapters; Shared consumed by three capabilities and not by Cohesion; external dependencies (Symfony YAML, `python3`, `git` in the back-test) | MM §8 (Phase-02 context map, by reference only); RA §3 flow | **Dependency analysis** (direction and kind: call, shared type, configuration file), then a pattern reading of each relationship against the map's relationship type. A relationship the baseline marks **conditional** is compared under each branch, or recorded as unresolved (§4b) | A relationship table: pair → observed direction and mechanism → the baseline relationship (per branch) |
| **Published language** | Interfaces that cross module or process boundaries: the ports (`*Reader`, `VocabularySource`, `SemanticFactProvider`); the `Verdict` / `Assessment` shape; the CLI contracts (arguments, exit codes, the NOT-CHECKED text); the configuration files read; the JSON outputs; the L3 fact vocabulary passed from Python to PHP | DM decision names; MM types and relationship verbs; RA's verdict term | **Interface listing**: enumerate each crossing interface with its exact signature, enum values and exit codes, taken from code and from the tests that pin it (`Tests/Adapters`). Then a term-by-term comparison with the baseline's published language (P-3) | An interface catalogue, and a difference list term by term |

## 3. Methods: definitions and limits

| Method | Definition | Known limits |
|---|---|---|
| Dependency analysis | `use` and fully qualified references, from the Appendix A script; call sites in entry points found by grep | It misses dynamic dispatch, string class names and process boundaries (the Python process, `git show`), so those are listed by hand |
| Reading of enforcing operations | A line-by-line reading of the named classes. Each finding cites file and line | It is interpretive. Findings carry a confidence (§4b) |
| Interface listing | Signatures, enum cases, exit codes and CLI arguments, taken from code and adapter tests | A docblock claim is **reported** until a test or a run confirms it |
| Tests | Rerun of the two commands (252 + 187). New tests are never written, because the auditor changes no file outside its folder | A passing test shows behaviour, not ownership or intent |

## 4. Reporting

Every part-B finding has the six parts of §4b, in this order: observation, evidence, classification, confidence, status, limitations. Findings go to the Principal and the Governance Engineer only. The reports are saved with their sha256.

Part A is complete with this report. **Part B has not started.**
