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
- **Plans** *(ARB refinements 2026-07-11 — clarifications of this convention, not a new standard)*:
  - **Scope (Plan Concept Decision Paper, ADOPTED):** this rule governs **Engineering Plans** — deliberate, approved EP-01 deliverables. Provider plan-mode **Work Plans** are Runtime artifacts (ES-005.1) outside this rule's scope; at EP-01 approval, plan content is promoted into a governed Engineering Plan (paper: `docs/implementation/Plan_Concept_Decision_Paper.md`).
  - Saved in `./docs/plans/` (plans are project-specific, so `docs/`, not `engineering/`; the directory pre-exists with legacy plans, which stand as history).
  - Named `YYYYMMDD-HHMM-<what_is_it_about>-plan.md` — e.g. `20260711-1830-evidence-context-strategic-discovery-plan.md` *(Decision Authority override, 2026-07-11: plans are the explicit EXCEPTION to the ids-not-filenames bullet — the timestamp preserves chronological history; the description says what it is about; the `-plan.md` suffix marks the type)*.
  - When citing a plan from long-lived documents, prefer the work-item id it serves (e.g. "the EPIC-002 discovery plan") over the raw filename. A superseding plan references the superseded plan's filename in its traceability section.
  - Existing plans in `.claude/plans/` / `claude/plans/` are historical records — they stand where they are (no migration; R-37). The convention applies from the next plan onward. *Bindings reconciled 2026-07-11 (user-authorized): root `CLAUDE.md` and `.claude/CLAUDE.md` plan sections are now pointers to this rule — the convention lives once, here.*

**ES-004.3 — Artifact Lifecycle Consistency** *(Principal Architect instruction, 2026-07-30 — register R-41; provenance: the WP-1 closure inconsistency, a CLOSED work plan whose header still read "AUTHORIZED — execution begins…". Refined ROLE-BASED the same day per ARB review — structure and clarity only, substance unchanged.)*:

- **Principle:** an artifact has **one authoritative lifecycle state at any moment**. When work crosses a lifecycle boundary (authorization → execution → acceptance → closure), every authoritative artifact transitions with it; stale execution wording never remains inside completed artifacts. Artifacts are engineering evidence — their lifecycle state must be as correct as their technical content.
- **Work-plan lifecycle (exactly these stages):** Draft → Authorized → Executing → Accepted → **Closed (historical record)**. After a transition, the previous state no longer describes the artifact. An accepted work plan becomes historical evidence, not an execution document — its header is rewritten to the closed state; future execution references the successor plan.

**Artifact Roles** *(the architectural rationale — different artifact classes have different lifecycle semantics; the checklist is role-based, never a bare file list)*:

| Role | Examples | Rule |
|---|---|---|
| **Runtime** | CONTEXT.md · the active Work Plan | Must describe **today's execution state** |
| **Historical** | Session Logs · git history | Preserve **chronological truth** — never rewritten for consistency (ES-004.2) |
| **Reference** | Developer Guides · governance documents | Describe **current engineering knowledge** — updated only when knowledge changes |
| **Decision** | ADR logs · rulings registers · acceptance records | **Decision text immutable; status annotations may evolve** |

**Mutable vs Immutable** *(the governing distinction)*: **synchronization updates only the mutable portion of an artifact; immutable historical or decision content is never rewritten.**

| Mutable (may be updated) | Immutable (never rewritten) |
|---|---|
| Work-plan status · CONTEXT.md content · current implementation references · status annotations | Original ADR/ruling decision text · session history · historical review text |

*(Demonstrated on first execution: ADR-T22's stale "Implementation NOT yet authorized" was corrected by evolving the status ANNOTATION — the decision text untouched. "We update ADRs when implementation changes" is exactly the misreading this distinction forbids.)*

**Role-based synchronization checklist at every slice closure** — every artifact must describe the same program state:

| Role | Check | Purpose |
|---|---|---|
| Runtime | Work-plan status matches the current lifecycle state | Execution state |
| Runtime | CONTEXT.md reflects the current work | Current context |
| Historical | Session log records the work (append-only) | Chronological truth |
| Reference | Developer guide updated for the slice | Knowledge |
| Decision | ADR status annotations reflect implementation reality | Decision status |
| Decision | Acceptance record recorded | Governance |

- **Consistency review before the closure commit:** does any artifact still describe the slice as future work? execution as pending? authorization after acceptance? contradict the accepted lifecycle state? If yes — update the artifact's **mutable portion** before commit.
- **Historical Integrity Rule:** never rewrite history to manufacture consistency — history remains chronological (git + session logs); current documents remain current. (Same discipline as code: source has one current implementation, ADRs one current decision, work plans one current lifecycle state; evolution belongs in history, not in stale status fields.)
- **Expected AI behavior at work-package closure:** detect the lifecycle transition → update the work-plan status → verify all authoritative artifacts by ROLE (checklist above) → leave session history unchanged → commit only after lifecycle consistency is satisfied.

## Registered (pointers)

| Rule | Home |
|---|---|
| ER-09 — Project Knowledge Management / document architecture | `Implementation_Process_v1.1_Draft.md` §ER — **PROPOSED · PAUSED** pending the Knowledge pilot (its type/template index is authoritative when ratified) |
| Developer-guide Definition-of-Done | `.claude/CLAUDE.md` standing rule (project binding) + area convention |
| ADR classification & register | `docs/adr/README.md` (product) · `../architecture/adr/` (platform) |
| EP-02 Implementation Report format (changes · not-changed · evidence · commits · risks · next) | EEP §8 + the EP binding |
