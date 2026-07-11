# ES-004 — Documentation

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** how engineering records are structured, governed, and kept honest.
**Scope:** all engineering records — logs, reports, retrospectives, decision documents, guides.
**Authority:** Decision Authority (ARB).
**Qualification Method:** documentation checks within operational qualifications (link/reference/status integrity; record-type responsibility).
**Supersedes:** the MEMORY-resident texts of retrospectives-recommend and the record conventions (now hosted here).
**Decision authority (compliance):** AI (+ one Machine candidate: append-only guard, ARB pending) — the AI evaluates, governance decides (see the Decision Authority & Verification Matrix in the Standards Index).
**Related Standards:** ES-001 (governance creation) · ES-003 (verdict recording) · ES-005 (where records live).

## Hosted rules (canonical here; previously MEMORY-only or scattered)

**ES-004.1 — Retrospectives Recommend; They Never Declare** *(ARB 2026-07-11)*. The AI is researcher/recorder/analyst; the ARB is the authority. Retrospective language: "Recommended for Promotion/Retirement" + evidence + "ARB decision: PENDING." A decision becomes PROMOTED/RETIRED only when the ARB confirms. Authority chain: implementation → evidence → analysis → ARB decision → standard.

**ES-004.2 — Record Conventions** *(consolidated ARB instructions)*:
- Session logs are **append-only** — corrections are appended, never rewritten (violation precedent recorded 2026-07-11: an overwrite, repaired from git within minutes).
- Historical records are never path-updated; paths inside them describe the world as it was. Living documents sweep; history stands.
- Cite durable **ids**, not dated filenames, in long-lived documents.
- No numeric scores in records (ES-003.2); reasoning lives in session records — decision documents carry decisions and rationale, never debate transcripts.
- One responsibility per record type: **IDD = implementation decisions · ADR = architectural decisions · retrospective = lessons · CONTEXT = current state only** (EPIC-001 retrospective consolidation rule, P-6).
- **Plans** *(ARB refinement 2026-07-11 — a clarification of this convention, not a new standard)*:
  - Saved in `./docs/plans/` (plans are project-specific, so `docs/`, not `engineering/`; per the folder rule ES-005.2, the directory is created only when the first plan arrives).
  - Named `YYYYMMDD-HHMM-<what_is_it_about>-plan.md` — e.g. `20260711-1830-evidence-context-strategic-discovery-plan.md` *(Decision Authority override, 2026-07-11: plans are the explicit EXCEPTION to the ids-not-filenames bullet — the timestamp preserves chronological history; the description says what it is about; the `-plan.md` suffix marks the type)*.
  - When citing a plan from long-lived documents, prefer the work-item id it serves (e.g. "the EPIC-002 discovery plan") over the raw filename. A superseding plan references the superseded plan's filename in its traceability section.
  - Existing plans in `.claude/plans/` / `claude/plans/` are historical records — they stand where they are (no migration; R-37). The convention applies from the next plan onward. *Bindings reconciled 2026-07-11 (user-authorized): root `CLAUDE.md` and `.claude/CLAUDE.md` plan sections are now pointers to this rule — the convention lives once, here.*

## Registered (pointers)

| Rule | Home |
|---|---|
| ER-09 — Project Knowledge Management / document architecture | `Implementation_Process_v1.1_Draft.md` §ER — **PROPOSED · PAUSED** pending the Knowledge pilot (its type/template index is authoritative when ratified) |
| Developer-guide Definition-of-Done | `.claude/CLAUDE.md` standing rule (project binding) + area convention |
| ADR classification & register | `docs/adr/README.md` (product) · `../architecture/adr/` (platform) |
| EP-02 Implementation Report format (changes · not-changed · evidence · commits · risks · next) | EEP §8 + the EP binding |
