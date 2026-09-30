# F-SERIES AGENT CONTRACT — per-file reconstruction and research · v1.0

**Status: APPROVED with protocol v1.0 (`prompts/20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md`, cited §F-n); rulings RL-01…RL-10 applied; frozen by F-LOG-0002.**
You process **one F-ID** and stop. You have no memory of other F-IDs; never pretend to. The repository artifacts are
the state; your conversation is not. All paths below are relative to
`docs/knowledgeos/chronological_knowelgeos_ablation_theory/` unless they start with `docs/`.

## §C-0 Inherited methodology — read these, do not paraphrase them from memory

| Read | Pinned sha256 | What you apply |
|---|---|---|
| `docs/knowledgeos/chronological-read/prompts/20260911_0221_agent-extraction-contract.md` (XC), **whole file** | `013770d57236c3b6c7185104f6dc6b73cdb54fa0ee1d171afecfa2aaa9714993` | Phase 1: invariant, STEPS 2–12, schemas, TYPES, "null means not stated", Forbidden — with the adaptations in §C-4 |
| `docs/knowledgeos/chronological-read/prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md` (P3B) §1B, §1C, §1D, §11.1, §13.2, §13.4, §13.6, §13.10, §14.4 | `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12` | research layers, epistemic classes, record kinds and core schema, disconfirmation, anti-projection — with the adaptations in §C-5 |

Verify both hashes with `sha256sum` before starting. A mismatch: stop and report; do not proceed.
These files are **read-only**. Where this contract and XC/P3B differ, the difference is listed in protocol §F-3; there
are no other differences.

## §C-1 Inputs

```
F_ID:       F####                      (the first non-AUDITED F-ID in list order: python3 scripts/f_status.py)
RUN:        FR-F####-NNN               (NNN = 001 for the first attempt, then 002, …)
CONTRACT:   sha256 of this file        (goes into every research record)
MODEL_ID:   your model id
```

## §C-2 Procedure (in this order; each gate must print success before the next step)

```
0  python3 scripts/f_status.py                              # confirm F_ID is the next F-ID and its state is IDENTIFIED
1  python3 scripts/f_read_source.py --run RUN --info F_ID   # identity + N pages
2  python3 scripts/f_transition.py F_ID READING --run RUN
3  FOR k = 1..N:
      python3 scripts/f_read_source.py --run RUN --page k F_ID      # read the page in your own tool output
      append one line to ledger/F_ID/PAGE-DIGESTS.jsonl (§C-3)       # only after reading page k
4  python3 scripts/f_transition.py F_ID READ-COMPLETE --run RUN
      IF it refuses: re-read the missing pages (same RUN) and retry; IF you cannot consume the file
      (capacity, decoding): f_transition.py F_ID READ-PARTIAL|READ-FAILED --run RUN --reason "…" and STOP.
5  Phase 1 (§C-4): write ledger/F_ID/{files,contributions,index-proposals}.jsonl
   python3 scripts/f_transition.py F_ID RECONSTRUCTED --run RUN
6  Research (§C-5): write ledger/F_ID/research.jsonl
   python3 scripts/f_transition.py F_ID RESEARCHED --run RUN [--empty-reason "…"]
7  python3 scripts/f_audit.py F_ID --run RUN
      IF it requires an independent audit: STOP and report — the orchestrator dispatches a fresh agent (§C-6).
8  python3 scripts/f_transition.py F_ID AUDITED --run RUN        (or AUDIT-FAILED with the audit's findings)
9  Report (§C-7). Do not open the next F-ID.
```

Never read the F-file by any other means (no `cat`, `Read`, `grep` on its path, no pipe into `head`). Never redirect the
reader's stdout to a file. Never edit `READ-LOG.jsonl`, `F-SERIES-STATE.jsonl` or `F-READ-INTEGRITY.jsonl` by hand.

## §C-3 Page digest — supplementary semantic reading evidence (F-SERIES-SPECIFIC, §F-7.3, RL-08)

`ledger/F_ID/PAGE-DIGESTS.jsonl`, one line per page, written only after that page is read:
```json
{"run_id":"FR-F####-NNN","page":1,"digest":"what this page contains, in 1–4 sentences","verbatim_quote":"≥ 40 characters copied exactly from this page"}
```
The gate checks that the quote occurs in page *k* itself. A digest is not evidence for the theory. It is supplementary
semantic reading evidence: it shows that a summarization operation was performed over the page, **not** that the page
was attended to, and it never substitutes for complete page coverage (the primary proof).

## §C-4 Phase 1 — XC STEPS 2–12, adapted

Apply XC exactly, with these changes (protocol §F-3 #7, #13):
- `source_id` → `f_id`. Add `content_sha256` (from the reader header) and `read_run` (RUN) to `files.jsonl`.
- `files.jsonl` carries `content_identical_to_s` (true when the manifest's `content_equals_s_sources` is non-empty;
  RL-04 — the file is still read and processed in full; no S record is consulted) and `order_evidence` ∈ STEP-NUMBER | INTERNAL-TIMESTAMP | FILENAME-DATESTAMP | **LIST-POSITION**
  (the F analogue of XC's `SOURCE_ID`). `status` is `CONTENT` for a read file.
- `contributions.jsonl`: `f_id` instead of `source_id`; add `page` (the page number the anchor is on). `anchor` is a
  verbatim quote (≤ 300 characters) — the gate checks it is in the file.
- `index-proposals.jsonl`: `first_seen_in_f` instead of `first_seen_in_batch`. There is no F object index yet, so every
  proposal has `relation_to_existing: "NONE"` unless the file itself names an alias; S-Series labels are **not** used as
  an index (§F-11). The file may be empty.
- A CONTENT file records at least one contribution. A file with no substantive content is `PLACEHOLDER`
  (`f_transition.py F_ID PLACEHOLDER --run RUN --reason "…"`), decided only after the whole file is read.
- STEP 13 is replaced by §C-2 step 9: you process one F-ID.

## §C-5 Per-file research — P3B v1.7 research model where applicable (RL-02; this is not v3.5 Phase 2)

`ledger/F_ID/research.jsonl`, one line per record. Core fields (P3B §13.6, adapted per §F-3 #16):
```json
{"rs_id":"FRS-F####-001","kind":"OBSERVATION|GAP|SCHEMA-LIMITATION|METHODOLOGICAL-DEFICIENCY|SUGGESTION-RESEARCH|SUGGESTION-METHOD|HYPOTHESIS|STRUCTURE-CANDIDATE",
 "topics":["…"],"lens":"MATHEMATICAL|STATISTICAL|DDD|LOGIC|EPISTEMIC|CHRONOLOGICAL|MIXED","scale":"OBJECT|CROSS-OBJECT|CORPUS",
 "statement":"…","epistemic_class":"(layer B) RESEARCH-OBSERVATION|DOMAIN-INTERPRETATION|EXTERNAL-THEORY-COMPARISON  (layer C) RESEARCH-SUGGESTION|HYPOTHESIS|THEORY-CANDIDATE",
 "output_layer":"B|C","supporting_evidence":[{"f_id":"F####","page":1,"quote":"verbatim","evidence_kind":"CORPUS|EXTERNAL-THEORY"}],
 "historical_anchor":"list position + date basis of the cited F-IDs","research_time":"UTC","run_id":"…","contract_sha256":"…",
 "model_id":"…","related_f_ids":[],"lifecycle_stage":"PROPOSED","author_role":"reading agent"}
```
HYPOTHESIS and STRUCTURE-CANDIDATE add `falsification_condition`, `validation_question`, `competing_hypotheses[]`,
`contradicting_evidence[]` and `disconfirmation_search` (what you looked for, where, and what you found — P3B §13.10).
GAP adds `what_is_missing` and `where_looked`.

Rules:
1. What the file **states** is Phase 1 (layer A), not research. Research records hold what **you** notice (layer B) or
   **propose** (layer C). Never write a layer-B/C statement as if the file said it.
2. Map the commission's list onto kinds: structural observation → OBSERVATION / STRUCTURE-CANDIDATE; relationship to an
   earlier F-file → OBSERVATION at scale CROSS-OBJECT with `related_f_ids`; methodological observation →
   SUGGESTION-METHOD / METHODOLOGICAL-DEFICIENCY; possible theoretical implication → HYPOTHESIS (THEORY-CANDIDATE only at
   scale CORPUS, which is premature in a per-file pass unless the file itself argues corpus-wide).
3. Evidence may cite **this F-ID or earlier AUDITED F-IDs only** (no look-ahead, §F-2). For an earlier F-ID, cite only
   a quote already recorded in that F-ID's audited ledger (a contribution `anchor` or a research `quote`), so that no
   unlogged reading of another file happens. The gate checks both: the quote is in that ledger and in that file's bytes.
4. Never cite S-ids, S records, or S conclusions (§F-11). If the file itself mentions an S-id, quote it as part of the
   F-file's text in Phase 1; do not use it as evidence in research.
5. If an inherited rule does not fit the evidence, record a `SCHEMA-LIMITATION` or `METHODOLOGICAL-DEFICIENCY`; never
   adapt the evidence to the method (P3B §1E).
6. Zero research records is allowed only with `--empty-reason`, stating why nothing beyond Phase 1 was warranted.

## §C-6 Independent audit (§F-3 #22)

Required for the first text F-ID and every fifth after it (`f_audit.py` says when). A fresh agent receives this
contract, the F-ID and RUN, and **no access to `ledger/F_ID/`**. It reads the file through the reader under run
`FR-F####-9NN` (its own page log), writes its own Phase-1 contribution list to the scratchpad, then compares it with
the stored records on: types, scope, labels, completeness, lineage claims, assumptions, and whether any research
record overstates its evidence. It writes `ledger/F_ID/INDEPENDENT-AUDIT.json`
`{run_id: RUN (the audited run), auditor_run, verdict: CONFIRMED|DISCREPANCY, discrepancies[], utc, model_id}`.
DISCREPANCY is recorded, not fixed by the auditor (M35 A8); the F-ID then goes `AUDIT-FAILED` and is re-entered.

## §C-7 Report (final message, ≤ 30 lines)

F-ID · path · content sha256 · pages read / expected · state reached · contribution count by type and scope · labels
proposed · review flags · source-claimed lineage · research records by kind and layer · anything the human should look
at · the next F-ID (from `f_status.py`).

## §C-8 Forbidden

Reading any F-file other than F_ID (except already-audited ledger records, §C-5.3) · reading past F_ID in list order ·
writing outside the lane folder · editing any S-Series artifact · editing a ledger line · asserting birth, replacement,
contradiction or identity yourself · correcting the source · treating a generated register as an original source ·
promoting an observation into theory · claiming a whole-file reading the gate did not accept.
