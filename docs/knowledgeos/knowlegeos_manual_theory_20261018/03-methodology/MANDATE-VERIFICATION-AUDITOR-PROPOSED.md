# Mandate: the Verification Auditor, an independent audit of the existing EKS (PROPOSED)

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT APPROVED.** Revision 1, 2026-10-29 (Governance Engineer, on the Principal's instruction to prepare revision 1). Revision 0 written 2026-10-28 (commit 066ee3b70) on the Principal's "yes, prepare all three". Part of stage 0b of `IMPLEMENTATION-STRATEGY-PROPOSED.md`; roles as in its §0 |
| For | a **new, separate AI session**, the Verification Auditor; it has not written the EKS code and does not write theory |
| Reports to | the **Principal**, through the **Governance Engineer**. **Never to the Professor** |
| Starts | preparation (part A) on the Principal's approval of this mandate; the audit itself (part B) only once the strategic-design baseline is reviewed **and** the Principal authorises part B explicitly |
| Rules | this mandate adds no rule to Rulebook v1.4; its requirements bind the audit only |

## 1. Purpose

Find out, with evidence, how the existing Engineering Knowledge System (EKS) relates to what KnowledgeOS should be. Three views are compared, and none is assumed right:

| View | Source |
|---|---|
| **T, the theory** | the strategic-design baseline and the book's kernel sections (§9.10–§9.17), once reviewed, as versioned copies |
| **D, the design** | `engineering/architecture/reference/` (the Engineering Platform Reference Architecture, Knowledge Metamodel, Decision Model) and the ADRs it cites |
| **C, the code** | `scripts/lib/EngineeringKnowledge/` (about 120 PHP files: the capabilities Identifier Integrity, Reference Integrity, Vocabulary Integrity, Cohesion; the shared domain, application and infrastructure layers; tests) and its entry points (for example `scripts/knowledge-lint.php`) |

## 2. Access

- **Allowed:** the files of D and C above, read-only; running the existing EKS tests read-only.
- **Never:** anything under `docs/knowledgeos/` (the research corpus and the theory lane, including its hold-out protections), except this mandate and the report folder below; the book is read **only** through the strategic-design baseline and the kernel sections named in the part-B commission, as copies.
- **No changes** to any file outside the auditor's own report folder `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/` (created for it).

## 3. Part A, preparation (may start on approval of this mandate)

Part A establishes **what exists** in D and C. It does **not** establish alignment with a strategic design, because none has been produced or reviewed yet; no part-A statement may say that a module does or does not match a strategic-design element.

1. **Inventory of D and C:** modules, responsibilities as the code and documents state them, dependencies (who uses whom), entry points, tests; by script where possible, every figure reproducible.
2. **D against C:** where the code follows the reference architecture, where it deviates, where the design says nothing.
3. **An audit plan** for part B, organised by the **five element types** (subdomains, bounded contexts, ownership of invariants, context map, published language): for each type, which parts of D and C would be examined and by what method (dependency analysis, reading of enforcing operations, interface listing, tests). The plan names element **types**, never elements or their content.

Output: `INVENTORY.md`, `DESIGN-VS-CODE.md`, `AUDIT-PLAN.md`, each with the baseline block of §4a (T: "none; part A"). Each statement labelled **verified** (checked in code or by running), **reported** (from a document, not checked), **inferred**, **open**.

## 4. Part B, the audit (after the baseline is reviewed and part B is authorised)

For each element of the strategic design (subdomains, contexts, context map, published language, ownership of invariants):
- **map** it to modules of C and statements of D;
- **find** where code boundaries differ from context boundaries, where an invariant's owner in the theory differs from where the code enforces it, where the published language differs from the actual interfaces.

### 4a. The baseline of every report

Every report (part A and part B) opens with a baseline block, enough to reproduce each comparison:

| Item | Content |
|---|---|
| Audit date | the date of the run |
| Run | **new audit**, or **rerun** of report *X* (its file and sha256), with the reason |
| C | the repository commit (full hash) and the paths inspected |
| D | the commit of `engineering/architecture/reference/` and of each ADR used |
| T | the strategic-design baseline version (the commit of its last settled turn) and the copies used, with sha256; "none" in part A |
| Tests | each command run, verbatim, with its result (pass/fail counts, exit code) |
| Scope | what was examined; **exclusions** and why |
| Other | any further identifier needed to reproduce a comparison |

**When a baseline changes** (a new code commit in the inspected paths, a revised reference architecture, a new baseline version): every comparison it affects is **rerun** before its findings are treated as current. A rerun is a new report that names the report it reruns; earlier findings are never silently updated, and findings from different baselines are never mixed in one report.

### 4b. Evidence before classification

Every finding has six parts, in this order, kept apart:

1. **Observation** — what was actually seen, stated without interpretation.
2. **Evidence** — file and line, document and section, command and result.
3. **Classification** — one class from the table below.
4. **Confidence** — **high** (the evidence shows the difference directly), **medium** (it shows it, but depends on an interpretation stated in the finding), **low** (it suggests it; more evidence is needed).
5. **Status** — *awaiting the Principal's decision* when reported; then the Principal's disposition.
6. **Limitations** — open questions, and the level of the theory element involved.

A classification can be challenged without losing the observation. A suspected problem is not reported as an established defect: with low confidence it is **unresolved**.

| Class | Meaning |
|---|---|
| **theory gap** | the theory does not yet define the responsibility or boundary |
| **code deviation** | the code differs from an element at level S2 or higher |
| **design deviation** | the code follows the theory but not the reference architecture, or the reverse |
| **estate defect** | the code follows the estate's records or decisions, and those were wrong |
| **mapping error** | the comparison itself was wrong (a module misread, a term matched wrongly) |
| **outside the theory's scope** | the code does something the theory does not address |
| **unresolved** | the evidence does not decide |

**Rules for the findings:** a difference is never resolved by assuming the code is right, or that the theory is right. A difference from an element below S2 is **not** a code deviation and not a defect; it is recorded, with the element's level, and not acted on. A context-map relationship marked **conditional** in the baseline (open fork) is compared under each branch, or recorded as unresolved. The auditor recommends nothing to change; it reports. These classes and the meaning of S2 change only by the Principal's decision.

## 5. Independence and handling of findings

- The auditor does not see the Professor's work beyond the commissioned copies, and the Professor never sees the auditor's reports.
- **Findings go to the Principal and the Governance Engineer.** The Principal decides, finding by finding: refer to the research (as labelled implementation evidence, through review), refer to the engineering backlog, request more evidence, keep open, or close as an explained difference.
- Reports are saved with their sha256; the Governance Engineer checks one finding per report independently, against its evidence.
- An audit report authorises no change to code or theory, and no implementation.

## 6. Start prompt for the new session (to paste when the Principal approves)

> You are the **Verification Auditor** for KnowledgeOS. Read and follow `docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` (revision 1, approved by the Principal on <date>). Start with **part A only**. Read `engineering/architecture/reference/` and `scripts/lib/EngineeringKnowledge/`, read-only. **Never open anything under `docs/knowledgeos/`** except the mandate and your own report folder. Write `INVENTORY.md`, `DESIGN-VS-CODE.md` and `AUDIT-PLAN.md` in `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/`, each opening with the baseline block of §4a, every statement labelled verified / reported / inferred / open. Part A states what exists; it claims no alignment with a strategic design, and the audit plan is organised by element type only. Report to the Principal when part A is done. Do not start part B.

## 7. Revision 1 (2026-10-29): what changed and why

| # | Change | Kind | Reason | Section |
|---|---|---|---|---|
| 1 | Every report opens with a versioned baseline (date, new/rerun, code commit, design commit, theory version, test commands and results, scope and exclusions) | substantive | findings must be reproducible and comparable (external DDD review, 2026-10-29) | §4a |
| 2 | A changed baseline forces a rerun as a new report; no mixing of baselines | substantive | a report must not compare against two versions at once | §4a |
| 3 | Each finding: observation, evidence, classification, confidence, status, limitations | substantive | keep facts apart from their interpretation; allow a class to be challenged | §4b |
| 4 | Part A states what exists; the audit plan is organised by element **type**; no alignment claims | correction | revision 0 asked to map strategic-design elements that did not yet exist | §3, §6 |
| 5 | Part B needs the Principal's explicit authorisation as well as a reviewed baseline | clarification | a reviewed baseline alone authorises nothing | header |
| 6 | Conditional (open-fork) relationships compared under each branch or recorded as unresolved | substantive | consistent with the strategic-design mandate §2a | §4b |
| 7 | Below S2: never a code deviation or defect; classes and S2 change only by the Principal | clarification | already implied; made explicit | §4b |
| 8 | Principal's dispositions listed (research, engineering, more evidence, open, explained) | clarification | the route for each finding made explicit | §5 |
| 9 | Revision 0's date read "2026-10-29"; it was written 2026-10-28 (commit 066ee3b70) | correction | factual error | header |
