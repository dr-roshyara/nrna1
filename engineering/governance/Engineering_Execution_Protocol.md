# Engineering Execution Protocol (EEP)

**Class:** Engineering Platform standard · **Status:** Adopted · **STABLE** (Decision Authority, 2026-07-10) — changes only on usage evidence from real engineering sessions; imagined improvements are rejected by default · **Owner:** Decision Authority
**Scope:** every engineering work item executed under this platform, in any project, in any language, by any engineer — human or automated.

---

## 1. Purpose

This protocol governs **how engineering work is executed**: how a change is planned, reviewed, approved, implemented, verified, reported, and continued. It is independent of implementation technology, of any AI provider, and of any specific project. A project adopts this protocol and binds it to its own tools, gates, and conventions; the protocol itself never changes when the project, the toolchain, or the engineer does.

## 2. Roles

| Role | Responsibility |
|---|---|
| **Engineer** | Prepares implementation plans, implements only approved scope, performs verification, submits implementation reports. May be a human engineer or an automated engineering agent — the obligations are identical. |
| **Independent Reviewer** | Evaluates implementation plans before approval. Must be independent of the plan's author. May be a human architect, a review board, or an automated reviewer. |
| **Decision Authority** | Approves or rejects plans, accepts or contests reports, and decides continuation. Always accountable to the organization; final authority is never delegated to an automated system. |
| **Stakeholder** *(optional)* | Provides the need the work serves; consulted when the objective or its acceptance criteria are unclear. |

One person or system may not occupy both the Engineer and the Independent Reviewer roles for the same work item.

## 3. Engineering Lifecycle

```text
Idea
  ↓
Implementation Plan          (Engineer)
  ↓
Independent Review           (Independent Reviewer)
  ↓
Approval                     (Decision Authority)
  ↓
Implementation               (Engineer — approved scope only)
  ↓
Verification                 (Engineer — evidence-based)
  ↓
Implementation Report        (Engineer)
  ↓
Decision                     (Decision Authority)
  ↓
Next Phase  ──────────────►  new Implementation Plan
     or
Complete
```

No stage may be skipped. The depth of each stage scales with the size and risk of the work; the sequence does not.

## 4. Implementation Plan

Every work item begins with a written plan. Minimum contents:

- **Objective** — what the change accomplishes, in one or two sentences
- **Governing decision** — the architectural decision or standard that justifies the change
- **Scope** — what is included
- **Files affected** — the artifacts expected to change
- **Risks** — what could go wrong, and its severity
- **Verification strategy** — HOW correctness will be known (the methods)
- **Evidence** — WHAT kind of artifacts verification is expected to produce; the exact artifacts are recorded in the report (verification does not always know beforehand precisely what evidence will exist)
- **Out of scope** — what deliberately will not change

The plan ends in the state **WAITING FOR REVIEW**. Purely clerical record-keeping (appending to an existing log or register, transcribing a decision verbatim) requires no plan; everything that mutates engineering artifacts does.

## 5. Independent Review

The Independent Reviewer evaluates the plan against the architecture, the standards in force, and the stated risks. Possible outcomes:

- ✅ **Approved**
- ⚠️ **Approved with requested changes** — the changes are folded into the plan before implementation
- ❌ **Rejected** — with reasons; a new plan may be submitted

**Implementation must not begin before approval.** Approval applies to the plan, not merely to the underlying request: a request authorizes planning; only an approved plan authorizes implementing.

## 6. Implementation

The Engineer implements **only the approved scope**. If implementation reveals that the approved plan is materially invalid — wrong assumption, undiscovered constraint, larger blast radius — implementation **stops**, the finding is stated, and a revised plan enters review. Silent scope change is a protocol violation regardless of the quality of the result.

## 7. Verification

Verification is **evidence-based**: the claim "it works" is always backed by an artifact someone else could inspect. Acceptable forms include, without prescribing tools:

- automated test results
- qualification / quality-gate outputs
- static analysis results
- document verification (review against a checklist, link/reference scans)
- migration verification (rename-only diffs, byte-identity checks)

The protocol does not prescribe specific tools; the adopting project binds each verification form to its own toolchain.

**Verification and evidence are distinct concepts:** *verification* describes **how** correctness was checked (the method — a test run, a diff inspection, a scan); *evidence* describes **what** artifacts that checking produced (the output — a test log, a rename-only diff, a scan result). A report states both. Evidence is produced by instruments and recorded as produced — never asserted, estimated, or summarized into existence.

## 8. Implementation Report

Every implementation ends with a concise report containing exactly:

1. **Changes made**
2. **Changes deliberately NOT made**
3. **Verification performed**
4. **Evidence produced**
5. **Commit(s)** or equivalent change identifiers
6. **Remaining risks**
7. **Recommended next action**

The report ends in the state **READY FOR DECISION**. Item 2 is mandatory: what was intentionally left untouched is as auditable as what changed.

## 9. Continuation

The Decision Authority reviews the report and decides:

- **Complete** — the work item closes, and
- **Continue** — another phase is required.

**Continuation is recursive:** a continued phase is a complete new engineering cycle — Implementation Plan → Independent Review → Approval → Implementation → Verification → Report → Decision. No phase inherits approval from its predecessor; each plan is reviewed and approved on its own. Continuation is never implicit — a report that recommends further work does not authorize it.

## 10. Guiding Principles

1. **Plans before implementation.**
2. **Architecture before implementation** — the plan identifies the architectural decision or governing standard that justifies the change.
3. **Independent review before approval.**
4. **Evidence before acceptance.**
5. **Scope changes require replanning.**
6. **Every implementation produces traceable evidence.**
7. **What was not changed is reported alongside what was.**
8. **The protocol is provider-independent** — the Engineer role is fillable by any competent human or automated system.
9. **The protocol is project-independent** — projects bind it; they do not fork it.
10. **Final authority is human** — automation plans, implements, verifies, and recommends; it does not approve itself.

---

*Adopting projects reference this protocol from their own implementation-process documents and define the project-specific bindings (which gates, which templates, which review depth per work-item class) there. This document defines the protocol once; it is not duplicated.*
