# KnowledgeOS Theory Research — Root Charter

**`[DEF]` charter · status: DEFINED, not started · created 2026-09-11**

**Placement — DERIVED, not chosen:**

```bash
php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos   # → docs/knowledgeos
```

> **Purpose.** The governed home for the KnowledgeOS theory-research brainstorming documents, and
> for everything derived from them inside this root.

---

## 1. What this root owns

| | |
|---|---|
| **Owns** | the brainstorming-phase documents of this programme, and every artefact derived from them inside this root |
| **Owns** | the naming, numbering, status-vector and provenance conventions in [`00-CONVENTIONS.md`](00-CONVENTIONS.md) |
| **Owns** | [`00-INDEX.md`](00-INDEX.md) — the register of what exists here |

## 2. What this root does NOT own

| not owned | where it lives | relationship |
|---|---|---|
| the brainstorming corpus | `docs/knowledgeos/brainstorming/` | **read-only evidence** — never edited from here |
| the chronological reconstruction pass | `docs/knowledgeos/chronological-read/` | a **separate lane**; this root consumes its output as evidence, never as authority |
| adjudication — what is accepted as canonical | Governance | this root **records**; it does not decide |
| canonical theory · formal specification · implementation | their own layers | downstream; this root does not pre-empt them |

$$\boxed{\textbf{source} \neq \textbf{extraction} \neq \textbf{adjudication} \neq \textbf{implementation}}$$

**The boundary is directional.** Each layer consumes the one above as **evidence**, never as
**authority**.

## 3. Provenance rule — binding on every document here

> **Our synthesis can organize evidence; it cannot create primary evidence.**

Every substantive claim written in this root is `DERIVED` by default (§C6). A claim may **point at**
an external source; it may never **stand in for** one.

## 4. Spoken-for note

*A short list, so that the next search — human or machine — can check a path against what is already
claimed in seconds, instead of re-deriving the boundary from scratch.*

| path | what it is | may I write there from here? |
|---|---|---|
| `docs/knowledgeos/knowledgeos_theory_research/` | **this root** — theory-research brainstorming documents | ✅ yes |
| `docs/knowledgeos/knowledgeos_theory_research/prompts/` | controlling procedures, verbatim | ⛔ no — prompts are **added**, never edited in place |
| `docs/knowledgeos/brainstorming/` | the corpus (source material) | ⛔ no — read-only |
| `docs/knowledgeos/chronological-read/` | a separate reconstruction lane | ⛔ no — its own lane |

⚠️ This table records **claims of ownership only**. It adjudicates nothing, moves nothing, and
renames nothing.

## 5. Status

Defined by this charter. **Not started.**

No brainstorming document exists yet. The first one is written after the brainstorming discussion,
into the structure defined here.
