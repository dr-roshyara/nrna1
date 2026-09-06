# 00_control — Corpus Validation Report

**Produced:** 2026-09-01
**Governing prompt:** `three_model_convergence/prompts/202609011141_prompt.md`
**Authoritative reading order:** `docs/knowledgeos/brainstorming/files_to_read_one_by_one.log`

This report discharges §43 steps 1–3 of the governing prompt: *read the log, validate that the
file list is readable, report the number of files found*. **No model synthesis is performed here.**

---

## 1. Log parse

The reading log is an `ls -la`-format listing. Each line is one corpus entry. The path is
fields 9..NF (**not** `$NF` — several corpus filenames contain spaces, e.g. the Shapiro PDF
and `... (1).md`; a naive `$NF` parse produces 8 spurious "missing file" records).

| Measure | Value |
|---|---|
| Lines in log | 2,320 |
| Entries parsed | 2,320 |
| Duplicate paths | 0 |
| Paths that do not exist on disk | **0** |
| **Files found (readable)** | **2,320** |

**Validation result: PASS.** Every entry in the authoritative reading log resolves to an
existing, readable file. No read failures to record under §1 ("If a file cannot be read,
record the failure explicitly").

## 2. Volume

| Extension | Count | Bytes |
|---|---:|---:|
| `.md` | 2,124 | 34,894,474 |
| `.py` | 74 | 507,598 |
| `.txt` | 67 | 307,013 |
| `.pyc` | 17 | 272,746 |
| `.gitkeep` | 16 | 0 |
| *(no extension)* | 6 | 117,109 |
| `.json` | 5 | 17,971 |
| `.puml` | 4 | 7,267 |
| `.png` | 2 | 1,569,023 |
| `.docx` | 2 | 99,587 |
| `.pdf` | 1 | 26,391,786 |
| `.odt` | 1 | 62,403 |
| `.log` | 1 | 407,439 |
| **Total** | **2,320** | **64,453,916** (61.5 MB) |

## 3. Analyzability classification

Assigned in `reading-manifest.tsv`, column `class`. This classification is **mechanical**
(by file type and size) and is deliberately *not* a research classification — the research
classification of §4 STEP 4 is assigned only after the file has actually been read.

| Class | Count | Meaning |
|---|---:|---|
| `TEXT` | 2,206 | Directly readable research prose/data (`md`, `txt`, `json`, `puml`, no-ext) |
| `CODE` | 74 | Python source — analyzable as EXPERIMENTAL artifacts |
| `BINARY_NOT_ANALYZABLE` | 17 | `.pyc` bytecode — derived from the `.py` sources already in the corpus |
| `EMPTY_PLACEHOLDER` | 16 | Zero-byte `.gitkeep` — carries directory-structure evidence only |
| `OFFICE_EXTRACTABLE` | 3 | `.docx` / `.odt` — text extraction required before analysis |
| `IMAGE` | 2 | `.png` diagrams — visual analysis required |
| `EXTERNAL_BOOK_PDF` | 1 | Shapiro, *Thinking about Mathematics* (26.4 MB) — **third-party published book, not corpus-authored research** |
| `CONTROL_SELF_REFERENCE` | 1 | The reading log itself (self-referential entry) |

**Analyzable research volume: 2,280 files / 34.2 MB.**

## 4. Declared handling of non-prose entries

Per §1 the protocol forbids skipping files. It does **not** require pretending a `.pyc` is a
research document. Each non-prose entry still receives a permanent per-file record; the record
states what the entry is and why full textual analysis is or is not applicable. Specifically:

- **`.pyc` (17):** record created; marked `BINARY — NOT ANALYZABLE`; the corresponding `.py`
  source is analyzed instead and cross-referenced. No content claims are made from bytecode.
- **`.gitkeep` (16):** record created; marked `EMPTY`; the evidence they carry is *structural*
  (which directories the researcher intended to exist), and that is recorded.
- **`.png` (2):** record created after visual inspection.
- **`.docx` / `.odt` (3):** text extracted, then analyzed as prose.
- **PDF (1):** this is an external published monograph, **not** a KnowledgeOS research artifact.
  It is recorded as an *external reference dependency* — evidence of what the research read,
  not evidence of what the research claimed. Bounded structural analysis only (front matter,
  table of contents, and the chapters the corpus actually cites). Its presence is itself a
  research datum (the corpus consulted philosophy-of-mathematics literature); its 26.4 MB of
  third-party content is **not** a source of KnowledgeOS claims and will never be cited as one.
- **The reading log (1):** recorded as `CONTROL_SELF_REFERENCE`; it is the protocol's own
  input and carries no theory content.

## 5. Corpus shape — directory distribution

The log is **not** confined to `brainstorming/`. It spans the whole `docs/knowledgeos/` tree.
Top contributors:

| Directory | Entries |
|---|---:|
| `brainstorming/phase_measure_theory` | 564 |
| `knowledgeos/reviews` | 191 |
| `brainstorming/kernel` | 163 |
| `brainstorming/phase_measure_theory/knowledgeos_kernel/prompts` | 114 |
| `brainstorming` (root) | 102 |
| `brainstorming/verification` | 71 |
| `reviews/kernel/session2` | 65 |
| `reviews/synthesis/analysis` | 59 |
| `phase_measure_theory/knowledgeos_kernel/research` | 59 |
| `knowledgeos` (root) | 50 |
| *(remainder distributed across ~60 further directories)* | 882 |

**Preliminary structural observation (NOT yet a finding):** the directory names alone suggest
the three research directions named by the governing prompt map onto identifiable regions of
the corpus (`phase_measure_theory` → mathematical; `kernel` → Kernel/DDD; the lens documents in
`brainstorming/` root → philosophical/epistemic). **This is a hypothesis about corpus geography
only.** Per §4 STEP 2 no file is classified from its filename or path. Every classification in
`file-classification.md` is assigned after reading.

## 6. Temporal span

| | |
|---|---|
| Earliest entry mtime | 2026-08-05 |
| Latest entry mtime | 2026-09-01 |

The corpus is a ~4-week evolving research record. §39 (no hindsight bias) is therefore live and
binding: chronology is evidence, and the manifest carries each file's mtime so that a later
concept is never read back into an earlier document without being marked as a later interpretation.

## 7. Scale disclosure (execution honesty)

At the fidelity §5 demands (a 25-section record per file, 5–15-sentence substantive summary,
cumulative comparison against all prior files), 2,280 analyzable files at 34.2 MB is a
multi-session undertaking on the order of ~9M tokens of source reading alone. The control plane
in this directory (`reading-manifest.tsv`, `progress.tsv`, `research-ledger.md`) exists so that
**all cumulative research state lives on disk, never only in conversation context**, and the
pass is therefore resumable and reproducible rather than dependent on a single unbroken run.

**Status: file-by-file analysis AUTHORIZED TO BEGIN. Model synthesis NOT authorized (§43.4).**

---

## Addendum — Corpus boundary applied (MD-010, 2026-09-01)

The 2,320-entry manifest is split into **primary source material** and **derived research
artifacts**, per human instruction. Five top-level `brainstorming/` subdirectories are excluded from
the primary sequential reading pass and reserved as a secondary verification layer:

| Directory | Entries | Status |
|---|---:|---|
| `verification/` | 438 | EXCLUDED — derived |
| `synthesis/` | 8 | EXCLUDED — derived |
| `falsification/` | 4 | EXCLUDED — derived |
| `corpus/` | 2 | EXCLUDED — derived |
| `classification/` | 2 | EXCLUDED — derived |
| everything else | 1,866 | **PRIMARY** |

**N_primary = 1,866 · N_excluded = 454 · N_total = 2,320.** See `14_decision-log/model-boundary-
decisions.md` MD-010 for the full rationale and mechanics. `reading-manifest.tsv` retains all 2,320
rows with a `corpus_tier` column; nothing is deleted.

**Superseded by MD-011** (same day): corpus root corrected to `brainstorming/` proper, excluding the
711 `docs/knowledgeos/` root-level files this addendum still counted as primary. **N_primary = 1,155**
from MD-011 onward. See MD-011/MD-012.

---

## Addendum 2 — Manifest refresh at apparent pass completion (MD-020, 2026-09-04)

`resume.py` reported `SEQUENTIAL PASS COMPLETE` at sequence 2320 (2026-09-04). Before authorizing
global reclassification, the corpus was re-walked against its current on-disk state — this is a
still-growing, multi-week research record, and the manifest was four days stale. **38 new `PRIMARY`
files and 18 new `EXCLUDED_VERIFICATION` files** (organic growth inside the already-excluded
`verification/` subdirectory) were found and appended as new sequences 2321–2376, in mtime order.
One apparent "new" file was recognized as a same-content rename of an already-`DONE` file (matched by
`(bytes, mtime)`, not path) and correctly not re-added.

Two directories that did not exist when this manifest was first built are now excluded from the walk
entirely (not listed as rows at all, not merely tagged `EXCLUDED_*`): `three_model_convergence/`
itself (this reconstruction's own output — self-reference) and
`mathematical_ideas_that_can_be_implemented/` (a separate, differently-governed parallel research
lane). Full rationale: MD-020.

| Tier | 2026-09-01 | 2026-09-04 (refreshed) |
|---|---:|---:|
| **PRIMARY** | 1,155 | **1,193** |
| `EXCLUDED_VERIFICATION` | 438 | 456 |
| other `EXCLUDED_*` | 16 | 16 (no growth found) |
| `OUT_OF_SCOPE_ROOT` | 711 | 711 (unchanged) |
| **Total manifest rows** | 2,320 | **2,376** |

**N_primary = 1,193.** `remaining_primary` at refresh time: **46** — the pass was not actually
complete; the manifest was. `resume.py` re-verified `CONSISTENT` after the refresh. Global
reclassification (MD-004) remains gated until these 46 files are read and `SEQUENTIAL PASS COMPLETE`
is reported again against the corrected `N_primary`.
