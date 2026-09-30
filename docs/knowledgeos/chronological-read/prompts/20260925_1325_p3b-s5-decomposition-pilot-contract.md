# P3b S5 DECOMPOSITION PILOT CONTRACT — NON-PRODUCTION (G-LOG-0052)

**Status:** non-production experiment. It governs only pilot runs `PX0004-U##`, `PX0004-S##`, `PX0004-A##` and `PX0027-U01`. Nothing produced under it is a P3b result, is accepted, or enters S5a.

**Governing documents:**
- the production batch contract, revision 3: `docs/knowledgeos/chronological-read/prompts/20260925_1204_p3b-agent-contract-r2.md`. It governs every reading rule, closed value and the exact object schema (Section E). Read it completely. Where it conflicts with this addendum, it governs, except for the four points this addendum explicitly adds: units, file-reading records, synthesis, and the schema-4 delta;
- the frozen pilot plan: `docs/knowledgeos/chronological-read/pilot-s5-decomp/PILOT-PLAN.json`;
- the pre-registration: `docs/knowledgeos/chronological-read/audit-p3b/20260925_1325_s5-decomposition-pilot-preregistration.md`.

**The core rule is unchanged: delivery is not reading.** A file counts as read only if every page 1..N was read with the paged reader. The pilot checker re-computes every page hash. No required reading is removed: the mandatory set of a label is its stage-2 files plus **all** its row sources, a superset of the OMQ-14 set.

## 1. Reading-unit agent (`PX0004-U##`, `PX0027-U01`)

1. **Inputs:** the production contract revision 3; this addendum; the plan entry for your run id (label and the list of files); your label's slice `docs/knowledgeos/chronological-read/_batch_input_r2/s5/rev3/OB0004/<label>.json` (not for the probe). Nothing else.
2. **Reading:** read **every** assigned file completely, in S-id order, with:
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run <run_id> --batch <PX####> --label <label> --step <7|1> --page <k> S####`
   for every k = 1..N. Use step 7 for a file in the label's `stage2_files`; otherwise use step 1. Read each page as the command's own output, one page per call. A plain call, or `| cat`, is acceptable. Never sample through `head`, `grep` or `sed`, and never read a saved copy. The reader logs to `pilot-s5-decomp/<run_id>/READ-LOG.jsonl`.
3. **Output:** one **file-reading record** per assigned file, in `docs/knowledgeos/chronological-read/pilot-s5-decomp/<run_id>/file-reading-records.jsonl` (canonical JSON lines):
   ```
   {"record": "FILE-READING", "run_id", "batch_id": "PX####", "s5_batch": "OB####", "working_label", "source_id",
    "partition": "<run_id>", "status": "CONSUMED" | "NOT-CONSUMED", "n_pages", "reading_step": 1 | 7,
    "stage2_dispositions": [<exactly the production disposition object for every hit key of this label in this file,
                             from the slice's LABEL-HITS; method WHOLE-FILE>],
    "timeline_facts": [{"date", "date_basis", "change_candidate": <closed timeline change value>, "anchor", "quote"}],
    "birth_candidates": [{"kind": lexical|conceptual|formal|operational|governance, "anchor", "quote", "reason"}],
    "omq14_content": [{"type": CONTRADICTION|CORRECTION|RETRACTION, "what", "anchor", "quote"}],
    "absence_evidence": [{"dimension", "finding": DEFINES|MENTIONS|NONE, "anchor", "quote"}],
    "dependencies": [{"target_label", "kind", "anchor", "quote"}],
    "notes", "contract_sha256": <sha256 of this addendum>, "model_id", "generation_parameters"}
   ```
   Every `quote` is verbatim from the page output. The record reports what the file contains for **this label**; label-level judgments (births, statuses, absences) are **not** made here.
4. **If you cannot complete a file**, record `status` NOT-CONSUMED, with a `notes` entry naming the S-id and the reason. Never write a disposition or a fact from an unread file.
5. **Unit state:** `pilot-s5-decomp/<run_id>/unit-state.json` = `{"run_id", "files_assigned": [...], "files_completed": [...], "session": n}`, updated after each completed file.
6. **Continuation (unit `PX0004-U02` only, pre-registered):**
   - **Session 1** passes `--session 1` on every read. It completes the first ⌈n/2⌉ files of the unit in S-id order, writes their records and the unit state, then stops.
   - **Session 2** passes `--session 2`. It reads `unit-state.json`, resumes at the first file not completed, does **not** re-read completed files, and appends the remaining records.
7. No helper agents. Never list or open other directories, and never read corpus, ledger, seal or hold-out files directly. Scratch goes only in `/tmp/p3b-s5-pilot/<run_id>/`, for notes only, never reader output.

## 2. Synthesis agent (`PX0004-S01` control, `PX0004-S02` target)

1. **Inputs:** the production contract revision 3; this addendum; the label's slice; **all** file-reading records and unit states of the label's units (from the plan); the plan.
2. **Output:** the label object, register and P1-gap records **exactly per production schema E**, in `docs/knowledgeos/chronological-read/pilot-s5-decomp/<synthesis run>/objects.jsonl`, `register.jsonl` and `p1-gap-capture.jsonl`. `run_id` = the synthesis run id; `batch_id` = `PX0004`; research and gap ids take the form `<synthesis run>:PX0004:<n>`.
3. **What the object rests on:**
   - Each stage-2 disposition is the unit record's disposition for that hit key. The whole-file basis is the unit's page-proven reading.
   - Births, timeline, statuses, absences and dependencies are formed from the records' facts and quotes, under the production rules.
   - Every quote you use must come from a record, or from a complete re-read you did yourself.
4. **Targeted re-reads:** at most 150,000 bytes in total, through the paged reader under your synthesis run id. A re-read counts as whole-file evidence only if you read **all** its pages.
5. **Schema 4 delta:** a required file whose unit record is NOT-CONSUMED gets a disposition with `method` `NOT-CONSUMED-ESCALATED`. Every dimension is ESCALATED, and an `escalations` entry with reason CONTRACT-DEVIATION names the S-id in its `detail`. Such a file never supports FOUND, NOT-FOUND or any whole-file result.

## 3. Audit agent (`PX0004-A01`)

It is independent of all unit and synthesis agents. It is dispatched with the pre-registered comparison and audit procedure (pre-registration §5–§6), and reads through the paged reader under `PX0004-A01`.
