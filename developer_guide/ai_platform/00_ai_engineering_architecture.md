# 00 — AI Engineering Architecture

*A Domain-Driven approach for AI-assisted software development. Provider-independent: Claude is one binding.*

## Goal

The goal of an AI Engineering Architecture is **not** to automate software development. Its purpose is to make AI-assisted engineering **deterministic, auditable, reproducible, traceable, and maintainable** — regardless of which AI model is used. The AI behaves like another senior engineer, not like an autonomous coding system.

This document explains **why** the rest of this platform exists. It is a map, not a rulebook: every principle below lives authoritatively somewhere else, and this guide only points there (rules live once — `Implementation_Process_v1.1_Draft.md`, header rule).

## The inversion (the core idea)

Most AI setups are built tool-first: *"We use Claude → let's configure Claude"* → prompts → agents → workflows. This platform inverts the stack:

```
Strategic DDD
   ↓
Engineering Domain  (bounded contexts, ubiquitous language, ownership)
   ↓
Capabilities        (CAP-nn — what the platform provides)
   ↓
Components          (CMP-nn — architectural abstractions, versioned)
   ↓
Runtime Assets      (AST-nn — concrete files, adopted with evidence)
   ↓
Provider Binding    (the ONLY layer that knows the provider)
   ↓
Claude (today)      — replaceable without touching anything above
```

The `.claude/` directory is **not the architecture**. It is one implementation of a provider-independent engineering platform. Migrating to another model rebuilds only the binding; the domain model, process, governance, and standards remain.

## The knowledge ecosystem (the layers above the runtime)

The inversion shows the runtime stack. The full ecosystem is a governed loop:

```
External Knowledge (articles, books, frameworks, postmortems)
   ↓  harvest — extract patterns, never copy (EPC-010)
Engineering Knowledge (pattern cards + evidence)
   ↓  promotion — only with evidence, only at retrospectives
Engineering Standards (post-PB-004; the principles' future single home)
   ↓
Engineering Process (EP/ER rules — how work flows)
   ↓
Runtime Platform (.claude — registry, hooks, scripts, binding)
   ↓
PublicDigit (the product — the only reason any layer exists)
   ↓
Implementation Evidence (gates, logs, Evidence Register)
   ↓
Retrospective  →  feeds Knowledge Evolution  →  loops to the top
```

**Layering rule:** the runtime never consumes articles — it consumes only Standards and Process. Knowledge flows downward only through governed promotion; evidence flows upward only through the retrospective.

## The thinking hierarchy

```
Think with DDD  →  Implement with TDD  →  Structure with Clean Architecture
→  Isolate with Hexagonal Architecture  →  Verify with automated evidence
```

DDD is the way of thinking, TDD the way of implementing, Clean/Hexagonal the way of structuring, evidence the way of knowing. Never the opposite order.

## The provider boundary (drawn explicitly)

```
PublicDigit Engineering (permanent)          │  Provider Binding (replaceable)
  Engineering Standards · Engineering        │    Claude (today)
  Process · Registry · Developer Guides ·    │    Gemini / Codex / Copilot /
  Knowledge · ADRs · Evidence                │    Cursor / … (any future)
─────────────────────────────────────────────┴──────────────────────────────
        everything left of the line survives any provider replacement
```

## Anti-patterns (what not to do — each learned, not theorized)

Never: start by writing prompts · start by creating agents · start by copying another framework · optimize the platform before product demand · add runtime assets without registry entries · add rules without evidence · treat reviewer praise or suggestions as governance · measure progress against someone else's inventory.

## Foundational Engineering Principles — and where each one lives

| # | Principle (one line) | Authoritative home |
|---|---|---|
| 1 | **Start with the engineering domain, not the AI tool** — the provider is the outermost layer | `engineering/architecture/proposals/Phase-02-Domain-Model.md` (frozen Baseline) |
| 2 | **Model the engineering domain with DDD** — the election system is the Core Domain; this platform is a Supporting Subdomain, always | Phase-02 §1–2 · handover sentence in session log 2026-07-08 |
| 3 | **Separate architecture from runtime** — "Verification Engine" is architecture; `run-gates.sh` is one implementation | `Phase-03A-Reference-Architecture.md` §1 |
| 4 | **Capabilities before files** — Capability → Component → Implementation → Asset; files appear last | Phase-02.5 §2 (capability catalog) + `.claude/platform/registry.yaml` |
| 5 | **Five questions per artifact** — capability? context? principle? decision? ADR? — or it must not exist | ADR-AIP-01 Consequences · registry `trace:` blocks |
| 6 | **Registry first** — register → review → approve → implement → verify → adopt | `01_registry_first_workflow.md` (guide) · registry header (rule) |
| 7 | **The engineering process lives outside the provider** — identical for Claude, Copilot, Cursor, Gemini, Codex, and humans | `Implementation_Process_v1.0.md` (frozen) + v1.1 draft §EP |
| 8 | **Plan first** — no implementation without an approved plan; approval applies to the plan, not the task; invalidation → STOP → re-plan | EP-01 (v1.1 draft) · `02_engineering_process_for_developers.md` (how-to) |
| 9 | **The engineering mindset** — think with DDD · implement with TDD · structure with Clean + Hexagonal · verify with automated evidence | `.claude/platform/OPERATING_INSTRUCTIONS.md` §Engineering Mindset |
| 10 | **Provider independence in language** — "the Engineering Process requires…", never "Claude knows…" | Phase-02.6 Ubiquitous Language (forbidden terms) · FF-15 |
| 11 | **Runtime assets are implementation details** — hooks, commands, scripts, bindings are never architecture | Phase-03A §1/§7 |
| 12 | **Knowledge and runtime never mix** — `architecture/`+`docs/` think and record; `.claude/` executes | CLAUDE.md layer note (Think/Official Truth/Build) · Registry Economy |
| 13 | **Authority Boundary** — AI drafts, reviews, verifies, recommends; humans approve; AI never owns architecture, requirements, or acceptance | `Phase-02.7-Platform-Decisions.md` PD-01..20 |
| 14 | **Evidence first** — ask "what evidence demonstrates a need?", never "what should we improve?"; implementation drives architecture | AIP-13 (Phase-02.5 §3) · Evidence-First rules (AST-013) |
| 15 | **Product Primacy** — which feature requires this change? No feature → don't build it | ADR-AIP-02 (AIP-14) · the 30-second question (session log 2026-07-08) |
| 16 | **One responsibility per document class** — Standards=principles · Process=steps · ADR=why · Registry=what runs · Guide=how · Log=what happened | session log 2026-07-08 (rule); Engineering Standards will host it |
| 17 | **Harvest knowledge, never copy it** — extract patterns → candidates → evidence → retrospective → accept/reject | `engineering/knowledge/harvests/engineering_pattern_cards_agent_skills.md` (EPC-010 + Evidence Log) |
| 18 | **Evolve on demand, never ahead of it** — feature → observation → evidence → retrospective → one small improvement | AIP-13/AIP-14 · R-27/R-29 freeze |
| 19 | **Measure outcomes, not inventory** — fewer mistakes, better traceability, faster safe delivery; never counts of prompts/agents/hooks | R-33 (Operational Readiness v1.0 criteria) · Platform Value ledger (plan) |
| 20 | **Maturity is a ladder** — prompts → reusable prompts → agents → workflows → engineering process → **AI engineering architecture** → self-improving platform | this guide; the journey record is `.claude/sessions/2026-07-08.md` |

## How to use this platform, in one paragraph

Start a session normally — the bootstrap injects the current state. For any non-trivial task, enter the Planning Stage and get the plan approved (guide 02). Any new `.claude` artifact goes through the registry first (guide 01). Run the gates and paste evidence; verdicts are measurements, never interpretations. End the session by answering the completion questions and recording the smallest permanent record. If the process itself gets in your way, report it as a defect observation — don't fix it opportunistically. The platform improves only through the retrospective, only with evidence.

## Reading order for a new engineer (or a new AI)

| # | Document | Answers | ~Time |
|---|---|---|---|
| 1 | This guide | Why does this exist? How do I think here? | 10 min |
| 2 | `engineering/architecture/adr/ADR-AIP-01…` (+ Addendum) | What was decided, by whom? | 15 min |
| 3 | `.claude/platform/registry.yaml` | What runs? (read it whole — it is small) | 10 min |
| 4 | Guide 01 — Registry-First Workflow | How do I add/change anything? | 15 min |
| 5 | Guide 02 — Engineering Process | How do I work day-to-day? | 15 min |

**Onboarding cost: about one hour.** Everything else loads on demand — progressive disclosure applied to onboarding itself.

---

> **The purpose of the AI Engineering Architecture is not to build an AI platform. Its purpose is to enable engineers to build PublicDigit with greater correctness, auditability, and maintainability. Every architectural decision must ultimately improve the election system — or it does not belong in this platform.**

## Traceability

Origin: ARB directive 2026-07-08 ("foundational document — explains why, not just how"). Grounded in: ADR-AIP-01/02 · Baseline corpus (Phase-01…03A, frozen) · EP-01/02 · PD-01..20 · AIP-01..14 · R-1..R-35 · registry v1.0 · Knowledge Harvest cards. This guide restates no rule normatively; on any conflict, the pointed-to artifact wins.
