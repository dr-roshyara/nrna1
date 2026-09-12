# KnowledgeOS Theory Research — Root Charter

**`[DEF]` charter · created 2026-09-11 · purpose stated 2026-09-11**

**Placement — DERIVED, not chosen:**

```bash
php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos   # → docs/knowledgeos
```

> ## Purpose
>
> ### *Build the provenance-preserving research corpus from the research we conduct now, so that KnowledgeOS theory can subsequently be constructed from that corpus.*

Everything in this root serves that sentence. The numbered stages, the conventions, the templates and
the register are **corpus machinery** — they exist so that what is said here is preserved with enough
provenance that theory can later be built from it.

**Not the mission.** Reconstructing the old theory from the historical corpus. Historical material
may be **cited as evidence** where it bears on a current question; it is never the object of this
station, and no stage here is a reconstruction pass.

---

## 1. What this root owns

| | |
|---|---|
| **Owns** | the corpus of the current research — every statement, finding, question and decision recorded here from now on |
| **Owns** | the naming, numbering, status-vector, provenance and claim-layer conventions in [`00-CONVENTIONS.md`](00-CONVENTIONS.md) |
| **Owns** | [`00-INDEX.md`](00-INDEX.md) — the register of what exists here |

## 2. What this root does NOT own

| not owned | where it lives | relationship |
|---|---|---|
| the historical corpus | `docs/knowledgeos/brainstorming/` | **read-only evidence** — never edited from here, and **not the object of this station** |
| the chronological reconstruction pass | `docs/knowledgeos/chronological-read/` | a **separate lane**; this root consumes its output as evidence, never as authority |
| the controlling procedures | `prompts/` | **added, never edited in place** |
| adjudication — what is accepted as canonical | Governance | this root **records**; it does not decide |
| canonical theory · formal specification · implementation | their own layers | downstream; this root does not pre-empt them |

$$\boxed{\textbf{source} \neq \textbf{extraction} \neq \textbf{adjudication} \neq \textbf{implementation}}$$

**The boundary is directional.** Each layer consumes the one above as **evidence**, never as
**authority**.

## 3. Provenance rule — binding on every document here

> **Our synthesis can organize evidence; it cannot create primary evidence.**

A **participant's statement made in this root is primary evidence** (§C6). It is the corpus. Our
reading of it is never `PRIMARY`, however confident, however well organised — and it never replaces
the statement it reads (§C4.3).

## 4. Spoken-for note

*A short list, so that the next search — human or machine — can check a path against what is already
claimed in seconds, instead of re-deriving the boundary from scratch.*

| path | what it is | may I write there from here? |
|---|---|---|
| `docs/knowledgeos/knowledgeos_theory_research/` | **this root** — the corpus and its machinery | ✅ yes |
| `docs/knowledgeos/knowledgeos_theory_research/prompts/` | controlling procedures, verbatim | ⛔ no — prompts are **added**, never edited in place |
| `docs/knowledgeos/brainstorming/` | the historical corpus | ⛔ no — read-only; cited, never revised |
| `docs/knowledgeos/chronological-read/` | a separate reconstruction lane | ⛔ no — its own lane |

⚠️ This table records **claims of ownership only**. It adjudicates nothing, moves nothing, and
renames nothing.

## 5. Status

**Started.** The machinery is defined and amended (A1–A4, 2026-09-11), and the first corpus records
exist — `S-01`, `F-01`, `OQ-01`, written as the bootstrap capture test.

Large-scale capture begins once the bootstrap test is accepted. Until then this root records; it does
not conclude.
