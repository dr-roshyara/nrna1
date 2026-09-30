# Plan — Document Management System for `knowledgeos_theory_research/`

**Station:** `docs/knowledgeos/knowledgeos_theory_research/` — nothing outside it is written except where §9 says otherwise.
**Status:** awaiting approval (EP-01).

---

## 1. Context

A brainstorming phase is starting. Its documents will be written into
`docs/knowledgeos/knowledgeos_theory_research/`, which today contains exactly one file —
`prompts/20260911_0221_prompt3-optimized.md`, the *KnowledgeOS Theory Reconstruction Master
Protocol v3.5*.

That protocol already defines a document-management system for its own output root
(`chronological-read/`, §B1): numbered artefacts whose prefix encodes pipeline stage, `_`-prefixed
meta files inside a stage folder, `ledger/` for raw per-unit records, machine records in JSONL
against narrative in Markdown, and — most importantly — **status expressed as a vector of
independent fields that can never be collapsed into one enum** (§B4).

This plan gives the new root the same discipline, scaled to what a brainstorming phase actually
produces. The intended outcome: by the time the first discussion document is written, there is
already a place for it, a name for it, a status vocabulary for it, and a register that knows it
exists — so the structure is derived from the discussion rather than improvised after it.

## 2. Decisions already taken

| Question | Decision |
|---|---|
| Structure model | **Hybrid — numbered stages, Markdown-first.** Stage prefixes and ledger discipline from the prompt; every artefact starts as Markdown. A stage gains JSONL machine records and a script only when it actually needs derivation. |
| Filename convention | **`YYYYMMDD-HHMM_<slug>.md`** — the underscore form `prompts/` in this root already uses. |
| Boundary | **This root owns only the brainstorming-phase documents** and everything derived from them. The charter names what it does *not* own. |

## 3. Deliverable

```
docs/knowledgeos/knowledgeos_theory_research/
├── 00-CHARTER.md            what this root is · boundary · what it does NOT own
├── 00-CONVENTIONS.md        naming · numbering · status vector · provenance classes
├── 00-INDEX.md              the register — every doc → stage → status → provenance
├── prompts/                 (EXISTS — untouched)
│   └── 20260911_0221_prompt3-optimized.md
├── scripts/
│   └── README.md            what a script may do: derive, never interpret. None exist yet.
├── ledger/
│   └── README.md            append-only raw per-unit records
├── templates/
│   ├── 00-README.md         how templates are used
│   ├── TEMPLATE-session.md          a brainstorming session
│   ├── TEMPLATE-finding.md          one finding
│   ├── TEMPLATE-decision.md         one decision (D-nn)
│   ├── TEMPLATE-open-question.md    one open question (OQ-nn)
│   ├── TEMPLATE-topic.md            one theory topic
│   ├── TEMPLATE-synthesis.md        a stage roll-up
│   └── TEMPLATE-record.md           a ledger record
├── 10-capture/_STAGE.md     P1 — What was said?
├── 20-objects/_STAGE.md     P2 — What forms and candidate objects are present?
├── 30-reconciliation/_STAGE.md  P3 — How are they related, and are they type-coherent?
├── 40-scope/_STAGE.md       P4 — What belongs?
├── 50-validation/_STAGE.md  P5 — What is validated, and by which means?
├── 60-governance/_STAGE.md  P6 — What is governed, and by which acts?
├── 70-theory/_STAGE.md      P7 — How is the theory written with provenance?
└── 90-meta/
    ├── _STAGE.md            runs · changelog · self-audit
    ├── RUNS.md              one line per working session (append-only)
    └── CHANGELOG.md         append-only
```

22 new files. `prompts/` is left exactly as it is.

## 4. What each of the three 00- files contains

**`00-CHARTER.md`** — placement line (resolved via `doc-placement.php --scope=product-specific
--domain=knowledgeos` → `docs/knowledgeos`); purpose in one sentence; **owns / does-not-own table**
(the corpus is read-only evidence; `chronological-read/` is its own lane; adjudication,
canonicalization and implementation are other layers); the provenance rule (*our synthesis can
organize evidence, it cannot create primary evidence*); and a short **spoken-for note** — a
plainly-worded list of what this root is and which neighbouring paths belong to other work, so the
next search can check here in seconds instead of re-deriving the boundary.

**`00-CONVENTIONS.md`** — the rules a new document must satisfy:

1. Naming: `YYYYMMDD-HHMM_<slug>.md`; stage folders `NN-<stage>/`; meta files `_`-prefixed; templates `TEMPLATE-<kind>.md`.
2. Numbering encodes **pipeline stage only** — never priority, never authority.
3. A document is "in the system" when `00-INDEX.md` lists it.
4. **Status is a vector of independent fields**, never one enum: `evidence · identity · lifecycle · origin`, plus the validation and governance vectors at their stages.
5. **Status advances only by a recorded act** — never because of the directory a document sits in.
6. Provenance classes: `PRIMARY` (external source) · `DERIVED` (our own synthesis — the default here) · `SECONDARY-SYNTHESIS`.
7. Machine records JSONL; narrative Markdown; **never TSV for quoted text**.
8. `ledger/` and session records are **append-only**; a correction is a new record, never a silent rewrite.
9. A verbatim quote carries an anchor — a heading or the quote itself, **never a line number**.
10. **No new vocabulary is invented here.** Status values, relationship taxonomy and the status chain are *borrowed by pointer* from the estate's existing governed vocabularies (the epistemic-status vocabulary, the MD-017 relationship taxonomy, the MD-018 status chain). The canonical paths are confirmed at creation time and cited, not restated — per `ES-005.4`, never a copy.

**`00-INDEX.md`** — the register: `ID · path · stage · kind · compact status-vector line ·
provenance`. Pre-populated with the one existing prompt and the templates, then with each stage
folder. Carries the rule: *a document not listed here is not yet part of the system.*

## 5. Stage contracts

Every stage folder holds exactly one `_STAGE.md` stating: **the one question it answers · entry
criteria · exit criteria (its terminal predicate) · what may be written here · what may not.**
A stage folder is never empty and never bare — it always carries its contract. Deeper machinery
(JSONL records, a deriver script) is added **only when that stage actually runs**, per the
Markdown-first decision.

## 6. Templates

Each template opens with the same compact header — `ID · date · stage · kind · provenance ·
one-line status vector` — then only the sections that kind requires. Templates exist so
conventions 4, 6 and 9 are satisfied by construction rather than by memory.

## 7. Explicit non-goals

- No theory content, no discussion notes, no view on any concept — this is scaffolding only.
- No JSONL files and no scripts created in advance; their homes are declared, not filled.
- No change to `prompts/`, and no file written outside this root.
- No new status vocabulary or taxonomy — everything borrowed by pointer.

## 8. Verification

1. `find docs/knowledgeos/knowledgeos_theory_research -type f | sort` — 23 files (22 new + the prompt); every folder carries its `_STAGE.md` or `README.md`.
2. Every path named in `00-INDEX.md` resolves on disk, and every file on disk appears in `00-INDEX.md` — both directions.
3. `git status --porcelain` — every entry is under `docs/knowledgeos/knowledgeos_theory_research/`.
4. `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` still resolves this root to `docs/knowledgeos` (recorded in the charter).
5. `npm run knowledge-lint` is **not** a gate here — it governs `docs/knowledge/`, not `docs/knowledgeos/`. Recorded so the absence of a lint result is not mistaken for a skipped gate.

## 9. Open items to confirm before or during execution

1. **Session bookkeeping writes outside this root.** The project's End-of-Commission checklist requires `.claude/CONTEXT.md`, a session log under `.claude/sessions/`, and a plan under `docs/plans/`. All three sit outside the station you scoped me to. **Confirm whether these are in scope**, or whether this commission closes with the root self-contained.
2. **Plan promotion.** Per `ES-004.2` a governed plan belongs at `docs/plans/YYYYMMDD-HHMM-<slug>-plan.md`. On approval, this plan can be promoted there (also outside the station — same question).
3. **Template set.** Six kinds are proposed. If you already know a document kind this set lacks, name it and it is added before creation rather than after.
