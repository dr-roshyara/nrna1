# Engineering Platform Reference Architecture

**Class:** Engineering Platform architecture · **Status:** **DRAFT** — adoption test: this document is used through one complete engineering cycle (qualification → implementation → retrospective); it becomes **Adopted** only if qualification and the retrospective show it accurately described reality throughout · **Owner:** Decision Authority
**Placement note:** this document is the **first artifact** of `architecture/reference/` — the directory exists because the artifact now exists (folder rule), not the reverse.
**Relation:** describes the platform *as implemented*; on any conflict, the rulings register, the sealed Baseline corpus (`../baseline/` — the genesis record), and the Engineering Execution Protocol win. *This reference architecture describes the repository as qualified by the latest Engineering Qualification; repository conformance is verified by qualification, not by this document.*

> **Governing principle:** *Architecture documents describe implemented capabilities and accepted governance. Research ideas, candidate domain models, and speculative structures belong in the retrospective inbox until evidence promotes them into the architecture.*

---

## 1. What this platform is

An **engineering governance architecture** — not a software system. It governs how engineering work is planned, approved, executed, verified, and evolved, independent of project, programming language, or execution provider. **No orchestrator, workflow engine, state machine, or execution aggregate is part of the current architecture** — deliberately, and not forever: the platform enforces its lifecycle through protocol, human authority, and evidence, and it has ruled (burden of proof, R-37) that such software mechanisms may exist only after operational evidence demonstrates the governance model is insufficient.

**The platform's central insight:**

> **Governance precedes automation. Automation may implement governance. Automation never defines governance.**

*(Stated here as this document's principle; formal principle numbering is deliberately deferred — R-27 permits no new numbered principles until the retrospective, and rule parsimony requires checking whether existing rules already carry it.)*

## 2. The five engineering capabilities (implemented; capabilities survive folder renames)

| Capability | Responsibility | Current implementation |
|---|---|---|
| **Engineering Knowledge** | Harvest external knowledge into pattern cards; evidence decides promotion | Pattern-card repository w/ evidence register — currently `knowledge/patterns/` (EPC-001..018; sources under `patterns/sources/`) |
| **Engineering Governance** | Define what processes exist, when they are mandatory, who approves, what evidence suffices | Execution protocol + rulings register + freezes — currently `governance/Engineering_Execution_Protocol.md` · `architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-27/R-37) |
| **Engineering Execution** | Perform work through the protocol lifecycle: plan → review → approve → implement → verify → report → decide | The EEP (Adopted · Stable) + each adopting project's binding in its own implementation-process document |
| **Engineering Qualification** | Verify implementation against specification; never fix in-run; verdicts keep their history | Qualification records — currently `verification/qualification/` (first record: OQ-ENG-001 — PASS AFTER CORRECTION) |
| **Engineering Evidence** | Record what instruments measured; append-only; evidence is produced, never asserted | Evidence records — currently `verification/reports/` · qualification records · append-only session logs and registers |

Governance **defines** the process; the process **executes** the work; qualification **verifies** it; evidence **records** it; knowledge **learns** from it. These five are real: they have directories, documents, and responsibilities.

## 3. How engineering work flows (implemented reality)

```text
                    Engineering Platform
                           │
     ┌─────────────────────┼──────────────────────┐
     │                     │                      │
 Knowledge            Governance            Qualification
     │                     │                      │
     └──────────────┬──────┘                      │
                    │                             │
        Engineering Execution Protocol            │
                    │                             │
             Human Approval                       │
                    │                             │
            Engineer (role)                       │
                    │                             │
           Execution Adapter                      │
     (the currently configured adapter —          │
      replaceable; a human engineer               │
      fills the same role)                        │
                    │                             │
               Repository                         │
                    │                             │
             Verification ────────────────────────┘
                    │
                Evidence
                    │
              Retrospective
                    │
           Platform Evolution
```

The **Engineer role may currently be fulfilled by a human engineer or an AI execution adapter — the protocol governs the role, not its implementation.** **Final authority is human** (EEP principle 10). No orchestrator appears in the diagram because no orchestrator is part of the current architecture.

## 4. The lifecycle (governance, not software)

The Engineering Execution Protocol defines an **explicit lifecycle**: Implementation Plan → Independent Review → Approval → Implementation → Verification → Implementation Report → Decision → (recursive continuation or completion). The platform *behaves like* a disciplined state machine because the protocol forbids skipping stages and continuation is never implicit — but the lifecycle is enforced by governance and review, **not implemented as software**. That choice is the architecture.

## 5. Decision log (evidence-based)

**Implemented (repository evidence):**
- ✅ Execution lifecycle with mandatory human approval — `governance/Engineering_Execution_Protocol.md`
- ✅ Qualification with correction lifecycle (never fix in-run · PASS AFTER CORRECTION · F-/CR-/OQ- id series) — `verification/qualification/2026-07-10-OQ-ENG-001.md`
- ✅ Traceability change → plan → decision — five-question trace + implementation reports (project binding)
- ✅ Knowledge harvest → pattern → evidence chain — `knowledge/patterns/`
- ✅ Provider-neutral Engineer role — the EEP (zero provider names; qualification-verified)
- ✅ Append-only history + assertion integrity — rulings register, session logs; no asserted metrics, no persisted review scores
- ✅ Repository separation: Product · Engineering · Runtime — `MIGRATION_REPORT.md` (EM-001)

**Deferred — promotion rule:** *a deferred concept is promoted only after repeated operational evidence demonstrates that the current governance model is insufficient.* Currently deferred (concept · trigger — nothing more; details live in the retrospective inbox, frozen until the retrospective):
- Workflow engine · trigger: repeated manual lifecycle failures
- Execution aggregates · trigger: multiple execution styles
- Machine-readable artifacts · trigger: a machine consumer exists
- Approval UI · trigger: review volume exceeds the current channel
- Code metrics · trigger: operational need
- Qualification dashboard · trigger: accumulated qualification evidence
- Platform ≙ Adoption split · trigger: a second adopting project

**Rejected (by design, not deferred):**
- Autonomous continuation — a report recommending further work never authorizes it; the Decision Authority decides (EEP §9)
- Orchestration software as the platform's center
- Numeric review scores as persisted records

## 6. Constraints in force

| Constraint | Effect |
|---|---|
| Governance freeze (R-27) | no new platform principles until the retrospective |
| Structural freeze (R-37) + burden of proof | no new structure; expansion requires demonstrated insufficiency — rejected by default |
| Rule parsimony | the constraint set is complete; interpret existing rules before creating new ones |
| Folder rule | a directory exists only when its first artifact arrives |
| Placement litmus | adoptable-unchanged by another project → `engineering/`; needs project context → the project |

## 7. Architecture evolution

The evolution process is itself architectural. This architecture changes through exactly one path:

```text
Operational Evidence → Qualification → Retrospective → Decision Authority → Architecture Update
```

Never `idea → ADR → implementation`. An architecture update without qualified operational evidence behind it is, by the platform's own rules, rejected by default.

## 8. How a project adopts this platform

A project references the Engineering Execution Protocol from its own implementation-process document and defines its bindings there (tools, gates, templates, review depth). The binding may be stricter than the protocol, never looser. **Projects bind the platform; they do not fork it.** Adoption evidence (tickets, qualification runs, retrospectives) remains project property — the platform stays reusable.

---

**This document is normative. Qualification verifies conformance to this reference architecture. Operational evidence may trigger revisions through the platform governance process.** It is rewritten when repository reality changes and the evidence has been accepted — never ahead of it.
