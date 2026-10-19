# Rules for extracting the KnowledgeOS theory from the corpus (2026-10-18)

**What this is.**
- A **compilation of rules that already exist**, each with its source, plus a small number of **PROPOSED** additions (marked) that the Principal has not yet approved.
- It is not a new method. M-04 v1.0 remains the approved method and is unchanged.

**Status column:**
- **ESTABLISHED** — approved method, or a Principal instruction;
- **GOVERNANCE** — a governance-log rule that applies to the Professor;
- **PROPOSED** — my addition, awaiting the Principal.

---

## Part A — Before opening a source (access)

| # | Rule | Status · source |
|---|---|---|
| A1 | Read only files on the allowed list (`CORPUS-ALLOWED.tsv`). Sealed hold-out files and concepts, sealed lists, measurement data, ledgers, batch inputs and hold-out files are never opened | GOVERNANCE · G-LOG-0141 items 2, 7; access record |
| A2 | Check each path against the allowed list **before** opening it | GOVERNANCE · G-LOG-0141 item 2 |
| A3 | Log every opened file: time (UTC), path, fingerprint (sha256), depth, purpose | GOVERNANCE · G-LOG-0141 item 3 |
| A4 | No corpus content goes to sub-agents | GOVERNANCE · G-LOG-01xx §5 (adopted in the access record) |
| A5 | No measurement, audit or hold-out prediction roles for the Professor | GOVERNANCE · G-LOG-0141 item 5 |
| A6 | Never invent read times. If a time was not captured, write "logged after reading" | ESTABLISHED · standing constraint |

## Part B — Order of reading

| # | Rule | Status · source |
|---|---|---|
| B1 | Read in the index order (`02-FILES.jsonl`, S0001, S0002, …). This is the chronological sequence | ESTABLISHED · Principal 2026-10-18 |
| B2 | **One source at a time: read it completely → write its record → verify the record → only then open the next source** | ESTABLISHED · Principal 2026-10-18 ("restart") |
| B3 | **The reported reading position is the last source whose record is complete and verified.** A source read without a record does not count | ESTABLISHED · Principal 2026-10-18 |
| B4 | A later document may be consulted to understand an earlier one. It is logged as a **contextual lookup**, never counted as reading, and its content is never attributed to the earlier source | ESTABLISHED · Principal 2026-10-18 |
| B5 | The index order is not proof of date order. S0006 is dated 2026-07-11 but placed after 2026-08-03 files. Record each source's own dates; ordering claims need explicit dates | ESTABLISHED · 04-corpus rule 2 (precedence only from explicit, strictly ordered dates) |

## Part C — The record for each source

**Format:** `E-<S-id>.md`, using the existing extraction template (`04-corpus/extract/E-TEMPLATE.md`), with these sections:

| § | Content |
|---|---|
| header | path, S-id, position, sha256, lines and bytes total = read, dates with their basis, read time, status |
| 0 | **business-language explanation** of what the source says (Principal 2026-10-18) |
| 1–9 | what it says it is · definitions · assumptions · claims (with the source's own strength words) · arguments · examples · mathematics · terms introduced or changed · references |
| 10 | contradictions and uncertainties |
| 11 | **[I] my interpretation — kept apart** |
| 12 | open questions and TODOs |
| 13 | completion check |

**Rules for the record:**

| # | Rule | Status · source |
|---|---|---|
| C1 | Every definition, claim and mathematical statement carries a **verbatim quote with line anchors** (`> Qn La-b: "…"`) | ESTABLISHED · 04-corpus rule 1 |
| C2 | Each record is checked with `quote_anchor_check.py`. **Every quote must PASS before the next source is opened** | ESTABLISHED · M-01 W-6; Principal (verify before advancing) |
| C3 | A meaning line describes **only this source**. Comparisons go to §10/§11, marked [I] | ESTABLISHED · R1 (V-2 report) |
| C4 | Each numbered or bulleted finding is its own item, never "summarised" | ESTABLISHED · R2 |
| C5 | Keep **four layers** apart: what the source says · what other corpus documents establish (contextual) · external research · my interpretation. Plus remaining uncertainty | ESTABLISHED · Principal 2026-10-18 |
| C6 | My own mathematics or deductions are marked [NEW] or [I] and are never merged into source rows | ESTABLISHED · 04-corpus rule 4 |
| C7 | Undefined → `TODO — UNDEFINED`. Unclear after research → `TODO — MEANING UNRESOLVED`, saying what was searched and what would resolve it | ESTABLISHED · Principal 2026-10-18 |
| C8 | Records are append-only. A correction is a new, dated entry; earlier text is not silently changed | ESTABLISHED · 04-corpus rule 5; Principal |
| C9 | **Identifiers are cited with their source.** Today's evidence: "I-9", "C-1", "C-5" and "R-n" each mean different things in different documents. Write "I-9 (Relationship Ontology)", never bare "I-9" | **PROPOSED** (from evidence in E-S0001…E-S0005) |
| C10 | Quotes are copied as plain text without Markdown emphasis. Today's three quote failures were all caused by `**` marks inside the quoted span | **PROPOSED** (practical) |

## Part D — Researching unclear concepts

| # | Rule | Status · source |
|---|---|---|
| D1 | Investigate before asking: other authorized corpus documents, official documentation, standards, books, academic sources | ESTABLISHED · Principal 2026-10-18 |
| D2 | External explanations never rewrite what the historical source meant | ESTABLISHED · Principal |
| D3 | External sources are labelled: fetched this session, or general knowledge not fetched. A claim is never presented as verified when it was not checked | ESTABLISHED · the five distinctions |
| D4 | Ask the Principal only when a genuine conflict or a reserved decision blocks the next step: one question, with evidence, options and a recommendation | ESTABLISHED · Principal |

## Part E — From records to theory

| # | Rule | Status · source |
|---|---|---|
| E1 | The transformation runs in four layers: **corpus → reconstruction** (what the corpus defines, distinguishes, relates, changes, leaves open; provenance kept) **→ cross-corpus abstraction** (recurring structures and invariants across independent material) **→ canonical theory** (new, precise wording, every claim traceable to its evidence) | ESTABLISHED as a direction · G-LOG-0028 (informational, non-normative) |
| E2 | For each theory claim, establish: premises · assumptions (including unstated but necessary ones) · inference · counterexamples · evidence · dependencies · status (established / disproved / conditional / hypothesis / unresolved) | ESTABLISHED · M-04 one-page method |
| E3 | Keep the kinds of evidence apart: proof (under assumptions) · counterexample (refutes a universal claim) · generated tests (find candidates, prove nothing) · source verification (the source says it, not that it is true) | ESTABLISHED · M-04 one-page |
| E4 | When several concepts are defensible, keep them all, explain their consequences, and recommend one (marked as the AI's). Reserved decisions stay with the Principal | ESTABLISHED · M-04 step 8 |
| E5 | A later document is not automatically right; supersession must be shown by the source or by a governance act | ESTABLISHED · M-04 step 3; Principal |
| E6 | All findings are research observations and hypotheses, **never proof**, and never replace the formal measurement stage | GOVERNANCE · G-LOG-0141 item 4 |
| E7 | No verdict-bearing theory ("robust theory found / no coherent theory") without S5 data and adopted verdict criteria | GOVERNANCE · G-LOG-0125 (O1), G-LOG-0140 |
| E8 | A theory claim is supported by at least two independent sources before it is called "recurring". A single source gives "observed once" | **PROPOSED** (consistent with E1's "independently investigated material"; the threshold is my suggestion) |
| E9 | Every theory statement in the working theory document cites record IDs (`E-S0003 Q6`), never a source directly without a record | **PROPOSED** |

## Part F — Where things are written

| What | Where | Status · source |
|---|---|---|
| per-source records | `tmp/corpus-reading/records/E-<S-id>.md` | ESTABLISHED · Principal storage instruction (`tmp/` for research artifacts) |
| read log | `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv` | same |
| working theory | `tmp/theory/` (to be created when the first theory statements are written) | **PROPOSED** |
| concept evolution across time | `06-theory/CONCEPT-EVOLUTION.md` | ESTABLISHED · authorized write |
| No edits to frozen documents, governance records, the authority store or Git | — | ESTABLISHED |

---

**Progress under these rules so far:**
- **S0001–S0005:** recorded and verified; all quotes PASS.
- **Next:** S0006.
- **S0105–S0116:** read before the restart but not recorded, so they do not count.

**Open for the Principal:** approve, change or reject the PROPOSED rules C9, C10, E8, E9 and the `tmp/theory/` location.

---

## CORRECTION — appended 2026-10-18 ≈21:25Z, after the Principal's review (nothing above deleted)

**What was wrong.** Parts B, C and E above omitted core steps of the **approved method M-04 v1.0** (`03-methodology/M-04-v1.0-approved-baseline.md`) and its working practice (`M-04-working-practice-20261018.md`). Per-source extraction records were treated as the whole job, which they are not.

**ESTABLISHED rules restored (M-04 v1.0, approved 2026-10-18), applied per source after the record:**

| M-04 step | Rule |
|---|---|
| 3 | **Compare each meaningful idea with the book** and label it: New · Repeat · Refinement · Contradiction · Evidence or example · Open. A later document is not automatically right |
| 4 | **Check that it is true, not only written:** re-derive arguments, look for counterexamples, check software claims against code and records, and separate proposed / track-decided / formally adopted |
| 5 | **Write it into the book immediately**, improving existing sections rather than duplicating. A correction revises the earlier text with a reason |
| 6 | **Keep a trace per idea:** source, label, how it was checked, where it sits in the book. Rejected or out-of-scope ideas stay recorded with the reason |
| 6a | **Completeness against the source, not the register:** every meaningful idea ends with a disposition (integrated · elsewhere · evidence · unresolved · rejected · excluded with justification) |
| 7 | **Look back twice:** "did I miss anything?" and "does the book still make sense?" (proportionate until E-3 is decided) |
| per 5 files | **Review the chapters as one work; decide continue / correct / pause** |
| reviews | an independent AI reviewer at sensible points; **reviewer access to corpus material needs the Principal's separate approval** (M-04 correction 1) |
| rules | source / check / opinion labelled separately · repetition is not proof · "unknown" is an answer · the book matters more than the paperwork |

**Still pending (not used as rules):** M-04 A-1 v2 (PROPOSED) · E-3 (step-7 change) · confirmation of the M-04 v1.0 hash.

**How these fit the per-source records:** the E-record (Part C) is the evidence base. The book integration (steps 3–7) is written per five-file group in `tmp/theory/R01-BOOK-INTEGRATION-<range>.md`, because Chapters 0–2 are baselines not edited in this session. Location **PROPOSED**; it sits under the established `tmp/` storage instruction.

**PROPOSED items remain PROPOSED:** C9, C10, E8, E9 and the `tmp/theory/` location. None is presented as approved.
