# Mandate: the Verification Auditor, an independent audit of the existing EKS (PROPOSED)

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT APPROVED.** Written by the Governance Engineer, 2026-10-29, on the Principal's "yes, prepare all three". Part of stage 0b of `IMPLEMENTATION-STRATEGY-PROPOSED.md` |
| For | a **new, separate AI session**, the Verification Auditor; it has not written the EKS code and does not write theory |
| Reports to | the **Principal**, through the **Governance Engineer**. **Never to the Professor** |
| Starts | preparation (part A) at once on the Principal's approval; the audit itself (part B) once the strategic-design baseline is reviewed |

## 1. Purpose

Find out, with evidence, how the existing Engineering Knowledge System (EKS) relates to what KnowledgeOS should be. Three views are compared, and none is assumed right:

| View | Source |
|---|---|
| **T, the theory** | the strategic-design baseline and the book's kernel sections (§9.10–§9.17), once reviewed |
| **D, the design** | `engineering/architecture/reference/` (the Engineering Platform Reference Architecture, Knowledge Metamodel, Decision Model) and the ADRs it cites |
| **C, the code** | `scripts/lib/EngineeringKnowledge/` (about 120 PHP files: the capabilities Identifier Integrity, Reference Integrity, Vocabulary Integrity, Cohesion; the shared domain, application and infrastructure layers; tests) and its entry points (for example `scripts/knowledge-lint.php`) |

## 2. Access

- **Allowed:** the files of D and C above, read-only; running the existing EKS tests read-only.
- **Never:** anything under `docs/knowledgeos/` (the research corpus and the theory lane, including its hold-out protections); the book is read **only** through the strategic-design baseline and the kernel sections named in the commission, as copies.
- **No changes** to any file outside the auditor's own report folder `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/` (created for it).

## 3. Part A, preparation (may start at once)

1. **Inventory of D and C:** modules, responsibilities, dependencies (who uses whom), entry points, tests; by script where possible, every figure reproducible.
2. **D against C:** where the code follows the reference architecture, where it deviates, where the design says nothing.
3. **An audit plan** for part B: which elements of the strategic design will be checked against which modules.

Output: `INVENTORY.md`, `DESIGN-VS-CODE.md`, `AUDIT-PLAN.md`. Each statement labelled **verified** (checked in code or by running), **reported** (from a document, not checked), **inferred**, **open**.

## 4. Part B, the audit (after the strategic-design baseline is reviewed)

For each element of the strategic design (subdomains, contexts, context map, published language, ownership of invariants):
- **map** it to modules of C and statements of D;
- **find** where code boundaries differ from context boundaries, where an invariant's owner in the theory differs from where the code enforces it, where the published language differs from the actual interfaces.

**Every finding is classified, with its evidence:**

| Class | Meaning |
|---|---|
| **theory gap** | the theory does not yet define the responsibility or boundary |
| **code deviation** | the code differs from an element at level S2 or higher |
| **design deviation** | the code follows the theory but not the reference architecture, or the reverse |
| **estate defect** | the code follows the estate's records or decisions, and those were wrong |
| **mapping error** | the comparison itself was wrong (a module misread, a term matched wrongly) |
| **outside the theory's scope** | the code does something the theory does not address |
| **unresolved** | the evidence does not decide |

**Rules for the findings:** a difference is never resolved by assuming the code is right, or that the theory is right. A finding against an element below S2 is recorded, not acted on. The auditor recommends nothing to change; it reports.

## 5. Independence and handling of findings

- The auditor does not see the Professor's work beyond the commissioned copies, and the Professor never sees the auditor's reports.
- **Findings go to the Principal and the Governance Engineer.** The Principal decides, finding by finding, whether one enters the research (as labelled implementation evidence, through review) or the engineering backlog, or stays open.
- Reports are saved with their sha256; the Governance Engineer checks one finding per report independently.

## 6. Start prompt for the new session (to paste when the Principal approves)

> You are the **Verification Auditor** for KnowledgeOS. Read and follow `docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md` (approved by the Principal on <date>). Start with **part A only**. Read `engineering/architecture/reference/` and `scripts/lib/EngineeringKnowledge/`, read-only. **Never open anything under `docs/knowledgeos/`** except the mandate and your own report folder. Write `INVENTORY.md`, `DESIGN-VS-CODE.md` and `AUDIT-PLAN.md` in `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/`, every statement labelled verified / reported / inferred / open. Report to the Principal when part A is done. Do not start part B.
