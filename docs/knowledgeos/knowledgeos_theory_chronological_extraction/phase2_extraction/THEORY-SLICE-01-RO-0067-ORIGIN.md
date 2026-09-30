# `RO-0067` — where is artifact ORIGIN recorded?

*Phase 2, job `2C`. ⛔ Slice 01 unedited · no schema changed · nothing decided.*

| | |
|---|---|
| **Question** | If `authority:` records **trust**, what records **origin** — *"this was produced by AI"* — after review moves an artifact to `authoritative`? |
| **Why it matters** | `RO-0064` established `authority:` is a mutable trust ranking. ⭐ **The origin claim `F0018` attributed to it has to live somewhere, or nowhere** |
| **Sources** | `knowledge-schema.yaml` · `knowledge-relationships.yaml` · `Knowledge-Constitution.md` · `knowledge-lint.php` · all `docs/knowledge/**` frontmatter |
| **Result** | ⛔⛔ **CONFIRMED — no persistent representation of production-origin exists in the knowledge system** |

---

## 1 · The schema's full field set

**Sixteen fields:** `knowledge_id` · `title` · `knowledge_type` · `bounded_context` · `status` · **`authority`** · `audience` · `owner` · `reviewers` · `version` · `schema_version` · `tags` · `last_review` · `next_review` · `code_refs` · `test_refs`.

⛔ **None records how an artifact was produced.** `owner` is stewardship; `reviewers` is who checked it; `version`/`last_review` are temporal.

**Searched and absent from every artifact:** `origin` · `source` · `generated_by` · `provenance` · `lineage` · `author` · `produced_by` — ⛔ **all zero occurrences.**

## 2 · The three candidate mechanisms — each tested

### ⛔ Candidate 1 · `derived_from` — **document lineage, not production origin**

⭐ **Correction to my own reading:** I first recorded `derived_from` as *"used but undeclared"*. ⛔ **Wrong.** It is declared at `knowledge-relationships.yaml:27`, listed at `knowledge-schema.yaml:125`, and consumed by `knowledge-lint.php:307` as a cross-context relationship. **It is a first-class, validated field.**

**But its own definition settles what it is:**

> *"`derived_from` — **This doc was derived/generated from another source.**" · inverse `source_of` · directed*

⭐ **It relates a document to ANOTHER DOCUMENT.** ⛔ It does not record *what produced* the document. **3 uses**, all pointing at other knowledge artifacts *(`KNOWLEDGE-CONSTITUTION`, `ADJ-DISC-BOUNDARY`, and one empty template)*.

### ⛔ Candidate 2 · folder segregation — **does NOT persist**

`Knowledge-Constitution.md:86`:

> *"AI working material (prompts, raw outputs, context bundles) is segregated under `docs/knowledge/ai/` and **only migrates into authoritative knowledge after review**."*

⭐⭐ **Correction to my own reading — the second in this investigation.** `docs/knowledge/ai/` holds exactly **one** file with `authority: authoritative`, and I began to read that as *"the folder persists after promotion."* ⛔ **It does not.** That file is **`AI-README.md`, the folder's own index** — not a promoted artifact retaining its location.

> ### ⛔ **The Constitution says the material MIGRATES OUT. Segregation is PRE-REVIEW only, and the location is exactly what promotion changes.**

⚠️ **And nothing enforces it:** the only mention repository-wide is that one prose line. ⛔ **No lint rule, no schema constraint.**

### ⚠️ Candidate 3 · git history — **real, but outside the system**

⭐ Git records who committed and when. ⛔ **It records the COMMITTER, not the PRODUCER** — an AI-drafted document committed by a human appears as that human's commit. ⚠️ **And it is external to the knowledge system**: no field, lint rule or query consults it.

## 3 · ⭐⭐ The finding, and a third overload of one word

> ### ⛔⛔ **After review moves an AI-produced artifact to `authority: authoritative`, NOTHING in the knowledge system records that it was AI-produced.**
>
> **`authority:` has moved off `generated`. The folder has changed. `derived_from` points at a document, not a producer. No origin field exists.**

⭐ **And the closest mechanism carries the collision in its own text.** `derived_from`'s description reads *"This doc was derived/**generated** from another source"* — using **`generated`** for **document lineage**, while `authority: generated` uses it for **production origin**.

| Sense | Where |
|---|---|
| **produced by tooling or AI** | `authorities.yaml` |
| **a rank below `authoritative` for a topic** | `lifecycle.md` §6 |
| ⭐ **derived from another document** | `knowledge-relationships.yaml` |

⭐ **Three senses of `generated`** — a new instance for `T-0021`'s collision ledger, alongside `Baseline`×5 and `Constitution`×5.

## 4 · The three propositions, as requested

| | Statement | Status |
|---|---|---|
| ⭐ **E1** | `authority:` is a single-valued, machine-enforced, **per-topic trust dimension** | ⭐⭐ **ESTABLISHED** — `lifecycle.md` §6 · `single_authoritative` enforced at `knowledge-lint.php:519` |
| ⭐ **E2** | its labels are named in terms of origin/history, making the terminology easy to misread | ⭐ **STRONGLY INDICATED** — `F0018` misread it; the `generated` overload is a third instance |
| ⛔ **E3** | the system lacks an independent representation of immutable origin | ⭐⭐ **CONFIRMED for the knowledge system**, on three tested mechanisms. ⚠️ **NOT established for KnowledgeOS as a whole** — `.claude/platform/registry.yaml`, the ADR log and the verification corpus were **not** searched |

⛔ **E3's scope is exactly what was searched. It is not a claim about every subsystem.**

## 5 · What this does NOT establish

| ⛔ | |
|---|---|
| **That an origin field is needed** | ⭐ a system may legitimately decide production-origin stops mattering once a human takes responsibility. **`reviewers` and `owner` arguably record exactly that transfer** |
| **That provenance is lost in practice** | git retains it; ⛔ **just not in a form the knowledge system reads** |
| **That `F0018` was simply wrong** | ⭐ it identified a real distinction. ⛔ It attributed the distinction to a field that does not implement it |

## 6 · ⚠️ Two more corrections in one investigation — the pattern is now at four

| # | My claim | Corrected |
|---|---|---|
| 1 | *"`derived_from` is used but undeclared"* | ⛔ declared and lint-consumed |
| 2 | *"the folder persists after promotion"* | ⛔ the Constitution says material **migrates out**; the one file is the folder's README |

⭐ **Both were inferences from a count rather than from the governing text**, and both dissolved on reading the text. ⚠️ **With the two in `RO-0064`, that is four.** `RO-0069` is upgraded: ⛔ **read the governing statement before inferring from a measurement.**

## 7 · Origin and epistemic status

| Item | Origin | Level |
|---|---|---|
| the 16 fields; zero origin fields; Constitution §86; `derived_from`'s definition; the lint consumption | `[C]` | **L0** |
| the field-absence census | `[T]` | **L0** *(measured)* |
| E1 | `[E]` from `[C]` | **L3** |
| E2 · the third `generated` sense | `[E]` | **L2** |
| E3 | `[E]` | **L3**, ⛔ scoped to what was searched |

⛔ **Nothing above `L3`.** ⚠️ `RA-15`: SOURCE-SUPPORTED · RECONSTRUCTION-VALID · ⛔ **not independently corroborated.**

## 8 · Obligations

| # | |
|---|---|
| `RO-0067` | ⭐ **DISCHARGED for the knowledge system** · ⚠️ **open for the rest of KnowledgeOS** |
| ⭐ **`RO-0070`** | Search `.claude/platform/registry.yaml` *(`T-0053`'s `CMP`/`AST` stable ids)*, the ADR log and the verification corpus for a production-origin record. ⛔ **Until then E3 is scoped, not general** |
| **`RO-0071`** | ⭐ **Do `reviewers` + `owner` intentionally REPLACE origin** — a transfer of responsibility rather than a loss of provenance? ⛔ **If so, E3 is a design choice, not a gap** |
| **`RO-0072`** | `generated` now has **three** senses. Add to `T-0021`'s ledger |
| `RO-0069` | ⭐ **upgraded** — four instances |

## 9 · Unresolved

⛔ Whether the origin gap is a **defect or a decision** *(`RO-0071` — the question that decides it)* · ⛔ whether any other subsystem records production-origin *(`RO-0070`)* · ⚠️ `PM-1` · ⚠️ `RO-0065`.

---

## ⭐ `RO-0067` in one paragraph

**Three candidate mechanisms were tested and all three fail to preserve production-origin.** `derived_from` is a validated first-class field but relates **documents to documents**, not artifacts to producers. Folder segregation under `docs/knowledge/ai/` is **pre-review only** — the Constitution says material *"migrates into authoritative knowledge after review"*, and nothing enforces even that. Git records the **committer**, not the producer, and sits outside the system. ⛔ **So after review moves an AI-produced artifact to `authoritative`, nothing in the knowledge system records that it was AI-produced.** ⭐ **But whether that is a defect or a deliberate transfer of responsibility to `reviewers` and `owner` is the question this investigation cannot settle — and `RO-0071` is now sharper than the gap itself.**

---

*`RO-0067` discharged for the knowledge system · 3 mechanisms tested, 3 negative · E1 established · E2 strongly indicated · E3 confirmed **but scoped** · a third sense of `generated` found · **2 further self-corrections, pattern now at 4** · 4 obligations · ⛔ Slice 01 unedited · no schema changed · v0.9 FROZEN.*
