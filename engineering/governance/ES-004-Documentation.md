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

## Registered (pointers)

| Rule | Home |
|---|---|
| ER-09 — Project Knowledge Management / document architecture | `Implementation_Process_v1.1_Draft.md` §ER — **PROPOSED · PAUSED** pending the Knowledge pilot (its type/template index is authoritative when ratified) |
| Developer-guide Definition-of-Done | `.claude/CLAUDE.md` standing rule (project binding) + area convention |
| ADR classification & register | `docs/adr/README.md` (product) · `../architecture/adr/` (platform) |
| EP-02 Implementation Report format (changes · not-changed · evidence · commits · risks · next) | EEP §8 + the EP binding |
