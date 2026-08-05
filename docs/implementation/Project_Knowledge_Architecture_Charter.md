# Project Knowledge Architecture — Charter

**Kind:** architecture charter (one page) — authorizes *planning* an architecture, not the architecture itself. **Status:** PROPOSED — awaiting ARB approval.
**Foundation:** the General Knowledge Architecture Constitutional Model (Knowledge Space · 10 invariants · 10 constitutional rules · lifecycle · flow algebra · specialization principle). This architecture is a **specialization** — weights, never new rules.

## Purpose

> **How does an AI (or a newcomer) understand a project well enough to extend it correctly?**

The Engineering Platform governs *how work is performed*. This architecture governs *how the project is understood*: how its ubiquitous language, bounded contexts, decisions, business rules, domain models, implementation knowledge, and memory are organized, discovered, assembled, validated, and evolved — per the constitutional model.

## Scope

Ubiquitous language · bounded contexts · ADRs and decision knowledge · business rules · domain models · implementation knowledge · project memory · retrieval and context assembly · onboarding · evolution. **Out of scope:** engineering process (EEP/platform territory) · runtime telemetry (awaits EPIC-002 evidence) · any new store, portal, or repository (invariant 8 / PK-P1 forbid them by construction).

## Key questions the architecture must answer

1. **Context assembly:** what is the minimal sufficient project context for a given task, and how is it assembled in-flow from live sources (the observed gap — session startup injects engineering context; project context is assembled ad hoc)?
2. **The dense cells:** how are this domain's coordinates governed — Definition×Conversational+Code (language), Decision×Recorded (ADRs/IDDs), Description×Recorded (guides), Constraint×Executable-where-encoded (rules)?
3. **The rationale transition:** how does Decision×Tacit knowledge move to Decision×Recorded at near-zero gesture cost (mined from commits/PRs/session logs rather than authored)?
4. **Definitional validation with AI engineers:** what is the agent-compatible analogue of conversational validation?
5. **The EKP's role:** incumbent governance-metadata system — coupling, granularity, and consumption model judged by evidence (E-1).
6. **Boundary artifacts:** which regime governs composite instances (IDDs: decision+description)?

## Evidence gaps (must close before or during architecture)

- **E-1 — the EKP in-flow usage test** (decisive, cheap, unrun): did PB-004..007 sessions consult `docs/knowledge/`, or read raw ADRs/guides/code? Behavioral evidence from session logs.
- **E-2 — machine-consumer conflict resolution:** how does an agent act when retrieved claims contradict? (Open in the literature; a pilot produces first-party evidence.)
- **E-3 — token economics of live agentic discovery** at this repository's scale (measurable during normal sessions).

## Constraints

Constitutional model binding (all invariants and rules) · no design in this charter · architecture begins only on ARB authorization · the biggest known risk is reflexive: **context assembly must not itself become a second-population artifact** (PK-P1) — the design answer must come from a small evidence-producing pilot on real tickets.

## Success criterion (amended at ARB promotion-model review, 2026-07-11 — the Reference Architecture must EARN its existence)

The pilot and its **Pilot Qualification Report** determine whether a Project Knowledge Reference Architecture is warranted at all. Sequence: pilot → qualification report → **Promotion Recommendation** → *only if yes:* Reference Architecture draft (same DRAFT → ADOPTED → STABLE lifecycle as the platform's) → ARB review → engineering promotion → ER-09 ratification. Success = the question "does this architecture deserve to exist?" answered with evidence — in either direction. A recommendation of "the existing structures absorbed everything; no RA needed" is a SUCCESS outcome, not a failure.

**STOP — ARB approval of this charter precedes any architecture work.**
