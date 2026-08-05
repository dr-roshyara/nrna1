# Engineering — the PublicDigit Engineering Platform

```
                 PublicDigit Repository

              ┌───────────────────────────┐
              │         Product           │
              │───────────────────────────│
              │  app/        tests/       │
              │  docs/       architecture/│
              └───────────────────────────┘
                           ▲
                    engineered using
                           │
              ┌───────────────────────────┐
              │   Engineering Platform    │   ← you are here
              │───────────────────────────│
              │  architecture/            │
              │  knowledge/               │
              │  verification/            │
              └───────────────────────────┘
                           ▲
                      executed by
                           │
              ┌───────────────────────────┐
              │      Runtime Adapter      │
              │───────────────────────────│
              │  .claude/                 │
              └───────────────────────────┘
                           ▲
                           │
                   Execution Engine
              (AI assistant or human engineer —
                 replaceable, never the center)
```

**This directory is not product documentation.** It is the Engineering Platform: the standards, decisions, knowledge, and verification evidence that govern *how* PublicDigit is engineered — independent of whichever execution engine performs the work.

**Authority:** ADR-AIP-01 (Baseline v1.0) and ADR-AIP-02 (Product Primacy) in `architecture/adr/`. Established by migration EM-001 (2026-07-10, ARB-approved) — audit trail in `MIGRATION_REPORT.md`.

---

## The three concerns of this repository

| Location | Concern | Question it answers |
|---|---|---|
| `docs/` · `architecture/` · `app/` · `tests/` | **Product** | *How does PublicDigit work?* Election domain, constitution, product ADRs, product C4, source code. |
| `engineering/` | **Engineering** | *How is PublicDigit engineered?* Standards, platform decisions, knowledge, verification evidence. |
| `.claude/` | **Runtime** | *How does the current runtime adapter execute that engineering?* Active context, plans, session logs, runtime registry, hooks, provider settings. |

The runtime mount point (`.claude/` today) is dictated by tooling — like `.git/` or `.github/`. It never moves, and it is never "the architecture": it is where the current provider binding executes the engineering defined here. **The Election System is the Core Domain; this platform is a Supporting Subdomain** (AIP-14, Product Primacy).

> **Multi-product relationship (DA clarification, 2026-07-27):** this directory contains the **Engineering Knowledge Architecture Baseline v1.0** and the shared engineering capabilities used to develop **all PublicDigit products** (Online Voting today; KnowledgeOS and future products if their gates open). Product-specific domain architectures remain owned by their respective product domains and **consume, but do not redefine, the engineering capability** — the capability never depends on any product's domain concepts (no election lifecycle here; no retrieval/embeddings here), and improvements discovered while building a product flow back **only** through the evidence-driven governance process (ES-006.4 → ES-006.1 → Decision Authority). One capability, many consumers, zero forks (EEP §10.9). *(A second real adopting product is the recorded trigger for the Platform ≙ Adoption split — pre-positioned, not executed.)*

---

## Information map

```
engineering/
├── README.md                  ← entry point (this file)
├── MIGRATION_REPORT.md        ← EM-001 audit trail (old path → new path → why)
│
├── architecture/              What the platform IS
│   ├── adr/                   Engineering platform decisions (ADR-AIP-01, ADR-AIP-02, rulings log)
│   ├── c4/                    Platform architecture views (16 diagrams)
│   ├── baseline/              Sealed Baseline v1.0 corpus (Phase-01 … Phase-03A; frozen — R-30: moves allowed, edits never)
│   └── reference/             Reference Architecture (DRAFT — describes implemented reality; adoption pending review)
│
├── governance/                What the platform ENFORCES
│   ├── STANDARDS_INDEX.md     ES-001..ES-006 — every binding rule's canonical home or authoritative pointer (PROPOSED)
│   ├── ES-001..ES-006         Constitution · Execution · Qualification · Documentation · Repository · Knowledge
│   └── Engineering_Execution_Protocol.md   Provider- and project-independent execution lifecycle
│                                            (plan → review → approve → implement → verify → report → decide)
│
├── knowledge/                 What the platform LEARNS (organized by domain object)
│   └── patterns/              Pattern cards EPC-001..018 + Pattern Evidence Register (the harvest already happened; these are its results)
│       └── sources/           Source material (provenance; eventual home: research/sources/)
│
└── verification/              What the platform PROVES
    └── reports/               Architecture reviews, audits, assessments (evidence-executed)
```

### Reserved namespaces *(documented here, not created as empty folders — a folder appears when evidence-justified content arrives, AIP-14)*

| Namespace | Will hold | Trigger |
|---|---|---|
| ~~`governance/standards/`~~ | **FULFILLED (2026-07-11):** the Engineering Standards landed as `governance/ES-001..ES-006` + `STANDARDS_INDEX.md` (R-32 executed in its evidence-justified form, per OQ-ENG-002; no subfolder needed — folder rule) | — |
| `governance/rulings/` | non-ADR rulings, if ever split from the ADR-AIP log | ARB decision |
| `knowledge/research/harvests/` · `knowledge/research/sources/` | dated harvest activity records + research material (Harvest → Pattern → Evidence separation) | PB-004 retrospective (four-way dossier split) |
| `knowledge/evidence/` | the Pattern Evidence Register as its own artifact | PB-004 retrospective (four-way dossier split) |
| `capabilities/` | capability model as first-class artifacts (ddd-assurance, knowledge-management, provider-assurance, …) | retrospective ruling on the capability layer |
| `verification/evidence/` | raw gate outputs / captured verdicts, separate from narrative reports | when AST-010 produces them |
| `verification/fitness-functions/` | FF-1..17 implementations | product-driven need (AIP-14) |
| `verification/qualification/` | OQ records | OQ-1 execution (plan currently lives in the runtime mount) |
| `developer/guides/` · `developer/onboarding/` | `developer_guide/ai_platform/` content | later slice — currently hook-coupled (see MIGRATION_REPORT) |
| `registry/` | provider-independent registry **spec** | when a second runtime adapter exists |

### The Registry — the platform's composition root

The **live registry** is `.claude/platform/registry.yaml` (a runtime asset, deliberately in the mount). It is not "another config file": it declares every component (CMP-nnn) and runtime asset (AST-nnn), their adoption state (adopted / planned / deprecated / verify), their verification evidence, and the five-question trace (capability → context → principle → decision → ADR). **Everything else is discovered from the registry.** When a second runtime adapter ever exists, the provider-independent registry spec lands in `engineering/registry/` and each adapter carries its own binding.

---

## Where does a new document go? — three questions

```
1. Product?      Does it describe the election domain or product architecture?
                 → docs/ (official truth) or architecture/ (product workspace)

2. Engineering?  Does it decide, teach, prove, or harvest HOW we engineer?
                 → engineering/  (decision → architecture/adr · pattern card → knowledge/patterns
                                  · evidence → verification/reports · protocol → governance/)
                 Litmus: could a different project adopt it UNCHANGED? Yes → engineering/.
                 Needs project-specific context or evidence → it belongs with the project.

3. Runtime?      Is it an active plan, session log, or context for the current adapter?
                 → .claude/
```

Still in doubt? Ask the five questions (capability? context? principle? decision? ADR?) — if any answer is missing, the artifact must not be created (R-17).

## Rules that bind this directory

- **Product Primacy (AIP-14):** every addition must serve a product feature. No feature → don't build it.
- **Sealed corpus (R-30):** `architecture/baseline/Phase-*` is permanently sealed — moves allowed, edits never.
- **Assertion Integrity (AIP-10):** no document here may assert an event that has not occurred; no invented scores or metrics.
- **Append-only history (AIP-11):** rulings and evidence registers are appended, never rewritten.
- **Governance freeze (R-27/R-29):** platform changes only when a product feature demonstrates insufficiency, via retrospective.
