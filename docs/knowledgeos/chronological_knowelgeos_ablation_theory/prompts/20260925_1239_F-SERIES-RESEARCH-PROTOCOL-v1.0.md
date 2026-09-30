# F-SERIES RESEARCH PROTOCOL — v1.0

**Status: APPROVED (FD-01, F-LOG-0002, 2026-09-25) with the ten human rulings RL-01…RL-10 applied to this text before the freeze. Frozen by F-LOG-0002; changes only by a new versioned file (§F-13).**
**Class:** execution protocol for a new research lane. It **inherits** the verified S-Series methodology and adds only
F-Series-specific execution rules. It modifies no S-Series artifact.
**Written:** 2026-09-25 · repository HEAD at writing `e4d0a196a` · lane folder
`docs/knowledgeos/chronological_knowelgeos_ablation_theory/` (the only writable location; human instruction 2026-09-25).
**Companion:** `prompts/20260925_1239_F-SERIES-AGENT-CONTRACT-v1.0.md` — the per-file procedure the reading agent runs.

> **The Claude session is not the state. The repository artifacts are the state.**
> **Reader delivery is not agent consumption.**
> **RECONSTRUCT → RESEARCH → RECONCILE → SYNTHESIZE. Do not skip stages.**

---

## §F-0 Governing sources (pinned; read-only for this lane)

| Ref | Artifact | sha256 | Commit | Standing |
|---|---|---|---|---|
| **M35** | `chronological-read/prompts/20260911_0221_prompt3-optimized.md` — Master Protocol v3.5 | `508b9f99…5c83730c5` | `e3e47b139` | controlling S-Series procedure; defines P1 (capture) and P2 (labels → families) |
| **XC** | `chronological-read/prompts/20260911_0221_agent-extraction-contract.md` — Phase-1 extraction contract | `013770d5…a9714993` | `e3e47b139` | the only document a v3.5 Phase-1 agent receives |
| **P3B** | `chronological-read/prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md` — P3b operating protocol v1.7 | `38021aa4…02d12` | `de52149fb` | FROZEN (G-LOG-0034); research-first layers, epistemic classes, research register |
| **RC3** | `chronological-read/prompts/20260925_1204_p3b-agent-contract-r2.md` — batch contract revision 3 | `b8eef043…809da` | `e4d0a196a` | whole-file reading made observable (G-LOG-0050) |
| **RDR** | `chronological-read/scripts/p3b_read_source.py` — paged reader | `0d48829a…05902` | `b7e7fc856` | verified reader; its `PAGE_CHARS`, `pages_of`, `stdout_kind` are compiled by this lane from the **committed blob** at `b7e7fc856` (sha256-pinned in `scripts/f_common.py`), never from the working tree |
| **VR** | `chronological-read/scripts/validate_roadmap.py` — v3.5 P0 | `c5e1a8ae…08c1d3e` | — | P0 rules mirrored by `scripts/f_register.py` |

Full hashes are in `F-BOOTSTRAP-REPORT.md` §3.

**Determination of the applicable versions (bootstrap question 3).**
- *Phase 1* = **M35 A6/A9 + XC**. v3.5 supersedes prompt1 and prompt2 (both 2026-09-11, the pre-v3.5 drafts; M35's header
  lists v3.5 as the architecture review of v3.4). XC is the only Phase-1 agent contract; no later revision of it exists.
- *Phase 2*: in M35, P2 is **label normalization and object families** (A10) — a corpus-level step that runs **after**
  P1c over the whole population, never per file (M35 R19). The per-file research the F-Series commission asks for
  (Step E) corresponds to the **P3B research-first model** (§1B layers, §11.1 epistemic classes, §13 research register),
  the latest frozen S-Series research methodology (v1.7, G-LOG-0034; v1.0–v1.6.6 are superseded and kept as history).
  **Ruling RL-02:** "Phase 2" means **M35's actual Phase 2** (label normalization and object families), run at corpus
  level after the whole F population has closed Phase 1. The per-file research of Step E uses **P3B v1.7 where
  applicable**, and is called *per-file research (P3B model)* — **it is not relabelled Phase 2.**
- *Reading integrity* = **RC3 + RDR** (the latest revision; G-LOG-0050). Earlier contract revisions are superseded.

---

## §F-1 Purpose (from the commission)

> What is actually contained in the F-Series corpus, in the order in which the files are listed, and what
> evidence-supported structures, relationships, hypotheses and research questions emerge from that corpus?

Not: "How can we prove the existing KnowledgeOS theory?" The corpus comes first; evidence before interpretation;
theory last.

---

## §F-2 Population and order — F-SERIES-SPECIFIC

- **Population:** every line of `docs/knowledgeos/20260925_1206_list_of_files_to_read.log`, sha256
  `cb701ae9a6322fe45b9dfbb3c2fe82697b11596746b57ece8516ba46e2d76859`, **1,523 entries**, line format
  `F####<TAB><Mon> <d> <HH:MM> <path>`. The list file is **not committed** at writing; its identity is its sha256,
  recorded in every `REGISTERED` event.
- **Order:** the **line order of that file** is the processing order. F-IDs are **not** monotonic
  (F3082…F3108, then F0109, F0181, …, ending F3081). Order is never recomputed from F-ID, date, topic or name.
- **Sequential gate:** an F-ID may enter `READING` only when every earlier F-ID in list order is `AUDITED`
  (enforced by `f_transition.py`). An exception needs a recorded human decision naming the F-IDs.
- **No filtering:** every entry receives an explicit disposition (§F-5). Nothing is skipped for looking redundant,
  uninteresting, or already known from elsewhere.
- **No look-ahead:** research on F-ID *n* may cite only F-ID *n* and earlier, `AUDITED` F-IDs (enforced). This is M35 R10
  / XC STEP 13 (never reinterpret an earlier file in light of a later one) applied in list order.

---

## §F-3 Inheritance / change matrix

Legend: **INHERITED-UNCHANGED** — the rule applies verbatim, with "S-id" read as "F-ID". **REUSED** — the verified code is
executed, not re-implemented. **F-SERIES-SPECIFIC** — new, because the lane has a new population or state.
**ADAPTED** — an inherited rule applied in a changed form; **each needs human approval (listed in §F-14)**.

| # | Requirement | S-Series source | F-Series treatment | Why (for anything not INHERITED-UNCHANGED) |
|---|---|---|---|---|
| 1 | P1 information preservation, "what did the corpus contain?" | M35 R0; XC invariant | INHERITED-UNCHANGED | — |
| 2 | Read each file whole, once, by one reader | M35 R2; XC STEP 1 | INHERITED-UNCHANGED, strengthened by #19 | — |
| 3 | Every entry gets a record; no "nothing here" shortcut | M35 R3 | INHERITED-UNCHANGED | — |
| 4 | Exact duplicate → duplicate record, not re-read | M35 R3, R9 | INHERITED-UNCHANGED (RL-03): a content pointer to the first F-copy after sha256 verification; the duplicate F-ID and its path provenance stay in the population | — |
| 5 | No identity resolution / merging in P1; labels are handles; UNKNOWN-OBJECT-CANDIDATE | M35 R5; XC STEP 6 | INHERITED-UNCHANGED | — |
| 6 | Source claim ≠ our assessment ≠ agent observation | M35 R6; P3B §11.1 | INHERITED-UNCHANGED | — |
| 7 | Order evidence STEP-NUMBER ▸ INTERNAL-TIMESTAMP ▸ FILENAME-DATESTAMP ▸ queue position | M35 R1a; XC STEP 2 | **ADAPTED** — weakest value renamed `SOURCE_ID` → `LIST-POSITION` | F-files have no source_id; the value means the same thing (queue position) |
| 8 | Lineage only as SOURCE-CLAIMED-* | M35 R7; XC STEP 10 | INHERITED-UNCHANGED | — |
| 9 | Own artifacts are DERIVED, never primary; self-citation guard | M35 R8, A4 | INHERITED-UNCHANGED; guard applied to this lane folder | — |
| 10 | Later evidence never rewrites earlier records; append-only | M35 R10; P3B §12.3 | INHERITED-UNCHANGED | — |
| 11 | Identity = content sha256; anchors are verbatim quotes, never line numbers | M35 R11 | **ADAPTED** — identity = F-ID + content sha256 (+ HEAD at registration); commit per file is not available for 80 untracked files and the uncommitted list | the population includes untracked files |
| 12 | Contribution TYPES, SCOPE, completeness, lineage kinds, review flags (closed lists) | M35 B2, B5; XC schemas | INHERITED-UNCHANGED | — |
| 13 | files.jsonl / contributions.jsonl / index-proposals.jsonl schemas | XC "Schemas" | **ADAPTED** — `source_id`→`f_id`; added `content_sha256`, `read_run`, `order_evidence`; `first_seen_in_batch`→`first_seen_in_f` | F identity and per-file (not per-batch) processing |
| 14 | Absence is NOT-EVIDENCED-IN-CAPTURE until a census | M35 R17; XC null rule | INHERITED-UNCHANGED | — |
| 15 | Research layers A/B/C never collapsed | P3B §1B | INHERITED-UNCHANGED (per-file research, RL-02) | — |
| 16 | Epistemic classes; research record kinds (closed); core schema; HYPOTHESIS / STRUCTURE-CANDIDATE profile incl. falsification, competing hypotheses, disconfirmation | P3B §11.1, §13.2, §13.6, §13.10 | **ADAPTED** — applied per file, ids `FRS-F####-NNN`, evidence cites F-IDs; P3B fields tied to S5 machinery (`related_labels` from `_derived.json`, hub/tier fields, `origin`) are not used | P3B runs per label over the frozen S population; F runs per file over a new population |
| 17 | Discovery allowed; canonicalization not | P3B §1D; M35 GATE | INHERITED-UNCHANGED | — |
| 18 | Historical time vs research time | P3B §1C | INHERITED-UNCHANGED | — |
| 19 | Paged whole-file reading (20,000-char pages, page hash, redirect refusal); delivery ≠ consumption | RC3; RDR; G-LOG-0050 | **REUSED** (the verified definitions compiled from RDR's committed, sha256-pinned blob) + F reader wrapper | RDR accepts only S-ids and logs under `chronological-read/`; it cannot be used for F without editing it, which is forbidden |
| 20 | Per-page summary + verified quote as **supplementary semantic reading evidence** — never proof of attention, never a substitute for page coverage | — (RC3 records the residual "page hashes prove delivery, not consumption") | **F-SERIES-SPECIFIC (RL-08)** | adds semantic evidence on top of the RC3 delivery proof; it does not close the residual |
| 21 | Scripts derive; agents interpret; agents do not count | M35 R13 | INHERITED-UNCHANGED | — |
| 22 | Self-audit sampling | M35 A8 (every 5 batches, 2 files, load-bearing bias, fresh agent) | **ADAPTED** — per F-file mechanical audit always; independent fresh-agent audit on the first text F-ID and every 5th after | F has no batches in the bootstrap (§F-12) |
| 23 | Pre-registration of any test | P3B §13.10a | INHERITED-UNCHANGED (only if a test is designed) | — |
| 24 | Firewall (`brainstorming/three_model_convergence/`) | M35 A0; VR | INHERITED-UNCHANGED (no F entry is under it at writing) | — |
| 25 | Output is a recommendation pending governance | M35 R20 | INHERITED-UNCHANGED | — |
| 26 | P0 validation and repair of truncated paths | M35 A4; VR | **ADAPTED** — repair also refused when the unique candidate is already listed under another F-ID or is an S-Series path | prevents one file acquiring two identities, or an S file entering through a repair |
| 27 | F population, order, sequential gate | — | F-SERIES-SPECIFIC | §F-2 |
| 28 | State machine, state ledger, integrity ledger | — | F-SERIES-SPECIFIC | §F-6, §F-9 |
| 29 | F evidence, research records, audits | — | F-SERIES-SPECIFIC | §F-8 |
| 30 | Not inherited: P3b S0–S7 steps, tiers, hubs, H-19 hold-out, Stage-2 absence search, the S5 batch manifest | P3B §8, §9.3, §9F, §11.4, §19 | NOT APPLICABLE | they operate on the frozen S population and its labels |

**Direction of dependency (isolation rule §7):** S methodology → this protocol → F execution → F evidence → F findings.
Never backwards. An F finding that bears on an S rule is recorded as an `F-OBSERVATION-ON-INHERITED-METHOD` entry in
`F-GOVERNANCE-LOG.md`; the S artifact is not edited.

---

## §F-4 Identity and registration (F-P0) — `scripts/f_register.py`

Mechanical, reads no content semantically. Per entry: parse; firewall check; `RESOLVED` if the path is a file, else
repair per §F-3 #26 (`REPAIRED` with `repair_evidence`) or `UNRESOLVABLE`; self-citation check; sha256, size, mtime,
extension; `kind` ∈ TEXT · EMPTY (0 bytes) · BINARY (contains NUL) · NON-TEXT (not UTF-8); for TEXT, `n_chars` and
`n_pages`; `dup_within_f` (first earlier F-ID with the same sha256); `content_equals_s_sources` (S-ids with the same
sha256 — **an identity fact only**, no S record is read, §F-11; an F-file with a non-empty list carries the marker
`CONTENT-IDENTICAL-TO-S` and is still read and processed in full under F rules, RL-04: *reuse content identity, not
epistemic conclusions*); `git_tracked`; `is_s_path`.
Output `F-MANIFEST.jsonl`, written **once** (the script refuses to overwrite); `--check` recomputes and compares.
Events: `REGISTERED` for all, then `RESOLVED` / `RESOLUTION-FAILED` / `FIREWALL-LIMITED` / `SELF-CITATION-EXCLUDED`,
then `IDENTIFIED` / `EMPTY` / `BINARY` / `NON-TEXT`.

Dry-run result at writing (not yet registered, pending FD-01): 1,523 entries · RESOLVED 1,505 · REPAIRED 3 ·
UNRESOLVABLE 15 · TEXT 1,492 · EMPTY 15 · BINARY 1 · 123 exact duplicates within F · 175 byte-identical to an S-Series
file · 80 untracked · 5,120 pages · 87.5 M characters.

---

## §F-5 Dispositions (every F-ID ends in exactly one audited disposition)

| Disposition | Set by | Meaning | Reading |
|---|---|---|---|
| `AUDITED` after `RESEARCHED` | lifecycle | completely read, reconstructed, researched, audited | whole |
| `EMPTY` | F-P0 | 0 bytes (e.g. `.gitkeep`) | none possible |
| `BINARY` / `NON-TEXT` | F-P0 | not decodable as text (e.g. `.pyc`) | none; recorded, never inferred |
| `PLACEHOLDER` | agent, after `READ-COMPLETE` | a stub with no substantive content — a judgement, so only after complete reading | whole |
| `RESOLUTION-FAILED` | F-P0 | path absent, no admissible repair | none |
| `FIREWALL-LIMITED` | F-P0 | firewalled path | never opened |
| `SELF-CITATION-EXCLUDED` | F-P0 | inside this lane's own folder | none |
| `EXACT-DUPLICATE` | lifecycle (RL-03) | byte-identical (sha256 re-verified) to an earlier, AUDITED F-ID; content pointer; F-ID + path provenance kept | none (pointer record) |
| `READ-PARTIAL` / `READ-FAILED` | lifecycle | not completely consumed; coverage recorded | as recorded — **never a whole-file claim** |
| `RECONSTRUCTION-UNRESOLVED` / `RESEARCH-UNRESOLVED` | lifecycle | stage could not be completed; reason recorded | whole |

Each of these is itself audited (`f_audit.py`) and then becomes `AUDITED`, so "AUDITED" always means "its recorded
disposition was verified", never "it was read" (the disposition says what happened).

---

## §F-6 State machine — F-SERIES-SPECIFIC

```
REGISTERED → RESOLVED → IDENTIFIED → READING → READ-COMPLETE → RECONSTRUCTED → RESEARCHED → AUDITED
REGISTERED ─┬ RESOLUTION-FAILED ─────────────────────────────────────────────────────────→ AUDITED | AUDIT-FAILED
            └ FIREWALL-LIMITED ──────────────────────────────────────────────────────────→ AUDITED | AUDIT-FAILED
RESOLVED ───┬ EMPTY | BINARY | NON-TEXT | SELF-CITATION-EXCLUDED ─────────────────────────→ AUDITED | AUDIT-FAILED
IDENTIFIED ─┴ EXACT-DUPLICATE (RL-03) ──────────────────────────────────────────────────→ AUDITED | AUDIT-FAILED
READING ────┬ READ-PARTIAL | READ-FAILED ──→ (re-enter READING with a new run) or → AUDITED
READ-COMPLETE ┬ PLACEHOLDER ─────────────────────────────────────────────────────────────→ AUDITED
              └ RECONSTRUCTION-UNRESOLVED ──────────────────────────────────────────────→ AUDITED
RECONSTRUCTED ─ RESEARCH-UNRESOLVED ────────────────────────────────────────────────────→ AUDITED
AUDIT-FAILED → re-enter at READING / RECONSTRUCTED / RESEARCHED / AUDITED as the audit findings require
```

The transition table is code (`ALLOWED_FROM` in `scripts/f_common.py`); a transition not in it is refused.
**A transition is an evidence-producing event, never a declaration.** `scripts/f_transition.py` re-derives the evidence
and appends the event only if it holds:

| Transition | Evidence required (re-computed at the moment of transition) |
|---|---|
| → READING | all earlier F-IDs `AUDITED`; a reader `--info` line for this run (establishes `n_pages`) |
| → READ-COMPLETE | every page 1..N logged for this run, each page hash re-computed from the current bytes, content sha256 = manifest, and a valid per-page digest for every page (§F-7); writes the `F-READ-INTEGRITY.jsonl` record |
| → READ-PARTIAL / READ-FAILED | reason; writes the integrity record with the actual coverage |
| → RECONSTRUCTED | a READ-COMPLETE event for this run; Phase-1 records valid (contract §C-4), every anchor and lineage quote found verbatim in the file |
| → RESEARCHED | research records valid (§C-5); every quote verbatim in its cited F-file; cited F-IDs are this one or earlier and AUDITED; no S-id as evidence; HYPOTHESIS / STRUCTURE-CANDIDATE carry falsification, validation question, competing hypotheses, disconfirmation search; or an explicit `--empty-reason` |
| → AUDITED / AUDIT-FAILED | `ledger/F####/AUDIT.json` for this run and the current state, verdict PASS / FAIL |

History is never overwritten: `F-SERIES-STATE.jsonl` is append-only; the current state of an F-ID is its last event.

---

## §F-7 Reading integrity — REUSED + F-SERIES-SPECIFIC

1. The only sanctioned reader is `scripts/f_read_source.py` (run ids `FR-F####-NNN`). It resolves F-IDs **only** through
   the manifest, refuses on sha256 drift, and reuses RDR's page model (20,000 characters of decoded text; the same page
   boundaries as S5 revision 3) and its redirect refusal (exit 3 when stdout is a regular file).
   The reused code comes from the committed blob, not from the working tree: another session may edit the S reader in
   the shared checkout (observed 2026-09-25), and an uncommitted edit is not verified infrastructure.
2. The reader logs every page itself to `ledger/F####/READ-LOG.jsonl` (page, span, page sha256, content sha256, stdout
   kind). Self-reported reading does not count.
3. **Supplementary semantic reading evidence (F-SERIES-SPECIFIC, RL-08):** after reading page *k*, the agent writes
   one line to `ledger/F####/PAGE-DIGESTS.jsonl`: `{run_id, page, digest, verbatim_quote}` — `digest` = what the page
   contains in the agent's words; `verbatim_quote` ≥ 40 characters copied from that page. The gate verifies that the
   quote occurs in page *k* (not elsewhere). **Evidence hierarchy (RL-08):**
   ```
   page hash logged by the controlled reader   (page delivered through the controlled reader)
       ↓
   coverage complete: pages 1..N, 0 missing, hashes re-verified      ← PRIMARY integrity proof
       ↓
   summary generated from the page                                    ← supplementary, semantic
       ↓
   quote verified against that page                                   ← supplementary, semantic
   ```
   The summary proves that a summarization operation was performed over the page; it does **not** prove attention,
   and it never substitutes for page coverage.
4. `READ-COMPLETE` ⇔ `expected pages = consumed pages`, `missing = 0`, `hashes verified`, `digests valid`. Anything
   less is `READ-PARTIAL` or `READ-FAILED` with the measured coverage — **never converted into a whole-file claim.**
5. Capacity: a file whose page count exceeds what one reading run can consume (e.g. F3026, 1,914 pages) is read as far
   as capacity allows and recorded `READ-PARTIAL` (reason `CAPACITY`) with its measured coverage (RL-06). Such files are
   registered and never silently excluded; **F3026 is run as a controlled capacity test** when it is reached in list
   order. No sampling is substituted for reading without a recorded decision.

---

## §F-8 Per-file lifecycle (Steps A–G) — the contract executes this

| Step | Commission step | Artifact / gate |
|---|---|---|
| A Resolve · B Identify | done once by F-P0 for all entries; re-verified by the reader (sha256) | manifest + events |
| C Read | reader `--info`, pages 1..N, digests | READ-LOG, PAGE-DIGESTS → `READ-COMPLETE` + integrity record |
| D Reconstruct | **XC STEPS 2–12** (Phase 1), per file | `files.jsonl`, `contributions.jsonl`, `index-proposals.jsonl` → `RECONSTRUCTED` |
| E Research | per-file research, **P3B v1.7 §1B/§11.1/§13 where applicable** (RL-02) | `research.jsonl` → `RESEARCHED` |
| F Record | provenance `F-ID → file → page/anchor → evidence` on every record | enforced by D and E gates |
| G Audit | `f_audit.py` (identity, coverage, integrity ledger, schemas, verbatim quotes, S-contamination, look-ahead, isolation); independent fresh-agent audit per §F-3 #22 | `AUDIT.json` (+ `INDEPENDENT-AUDIT.json`) → `AUDITED` |

Only after G is the next F-ID opened.

---

## §F-9 Persistent state and restartability — F-SERIES-SPECIFIC

| Artifact | Answers |
|---|---|
| `F-MANIFEST.jsonl` | the population, order and identity (frozen) |
| `F-SERIES-STATE.jsonl` | **where are we?** — append-only transition events |
| `F-READ-INTEGRITY.jsonl` | **did we actually read it?** — expected/consumed/missing pages, hashes verified, coverage |
| `ledger/F####/` | READ-LOG, PAGE-DIGESTS, Phase-1 records, research records, AUDIT(.json, -LOG.jsonl), INDEPENDENT-AUDIT |
| `F-GOVERNANCE-LOG.md` | human decisions, protocol versions, observations on inherited methodology |
| `F-SESSION-LOG.md` | per-session summary, kept inside the lane (RL-07) |
| `prompts/` | this protocol and the contract, versioned |

Session start: `python3 scripts/f_status.py` → "last contiguous AUDITED F-ID … next F-ID …". Never resume from
conversation memory (M35 A3).

---

## §F-10 Interpretation discipline — INHERITED-UNCHANGED

No theory is assumed correct (kernel, mathematics, philosophy, three-model convergence, any canonical candidate).
Repetition is not confirmation (M35 R9). Disagreements between F-files are preserved (M35 R10; P3B §12.3). A later file
that appears to explain an earlier one is recorded as a relationship in the later file's research record, never
back-projected (P3B §14.4, anti-projection). No history rewriting, no normalisation of contradictions, no merging of
competing theories, no choice of a "best" model, no canonical theory (P3B §1D).

---

## §F-11 Isolation — F-SERIES-SPECIFIC (human rule 2026-09-25)

The whole S-Series tree (`docs/knowledgeos/chronological-read/` and everything the S programme produced) is read-only.
This lane writes only inside its folder, **by construction**: every F-Series write passes `f_common.guard`, which
refuses any path outside the folder (tested). `f_audit.py` also compares `git status` with the bootstrap baseline
(`F-BASELINE-GIT-STATUS.txt`) and **records** every new change outside the folder in `AUDIT.json` with attribution
`UNKNOWN`. It does not fail on them, because another session works in the same checkout (observed 2026-09-25) and a
change there cannot be attributed to this lane. S records are not consulted as
evidence; S-ids are rejected as research evidence; `content_equals_s_sources` is an identity fact, not a reading.
Importing RDR is read-only: bytecode writing is disabled so nothing is written under `chronological-read/`.
Limitation: the recorded comparison sees new or changed *status lines*; a further edit to a file that was already modified
at baseline is not detected.

---

## §F-12 Bootstrap gate and parallelism

1. No F-ID is REGISTERED before the population boundary is frozen: the list file, this protocol and the contract are
   committed and their hashes recorded in `F-GOVERNANCE-LOG.md` (RL-09, human sequence of 2026-09-25):
   `F-LIST frozen → F-PROTOCOL frozen → F-CONTRACT frozen → F-STATE initialized → F3082 → … → F3083`.
2. The infrastructure is demonstrated on a synthetic fixture (`tests/test_f_pipeline.py`, 17 tests) before any F-file.
3. After approval: F-P0 registration, then **F3082 alone**, sequentially, including the independent audit; the result is
   reported; only then F3083. No parallel reading until the pipeline has been demonstrated reliable on real files and the
   human authorizes it.

---

## §F-13 Change control

Any change to an inherited rule, a gate, a schema or the state machine is a new versioned protocol file in `prompts/`
plus an entry in `F-GOVERNANCE-LOG.md` naming the decision, the evidence and the reason. Operational friction goes to
the session log, not into the rules. The frozen S-Series artifacts are never edited (isolation rule §1).

---

## §F-14 Decisions (human) — as submitted, with the rulings recorded in F-LOG-0002

| Id | Decision | Proposed default |
|---|---|---|
| FD-01 | approve this protocol and the contract v1.0 | — |
| FD-02 | meaning of "Phase 2": P3B research register per file (Step E) with M35 P2/P3 after the whole population, **or** M35 P2 only | P3B per file; M35 P2/P3 later |
| FD-03 | the 123 byte-identical duplicates inside F: inherit M35 R3 (pointer record, not re-read) **or** read every one | inherit M35 R3 |
| FD-04 | the 175 F-files byte-identical to an S file: read in full as F evidence, S-id kept only as an identity fact | read in full |
| FD-05 | the 3 repairs (F1264, F2795, F2796) and 15 unresolvable entries | accept repairs; 15 as RESOLUTION-FAILED |
| FD-06 | files too large for whole reading (F3026 1,914 pages; F3025 423; F3030 182; F3028 115; F3022 76): READ-PARTIAL with reason CAPACITY, or another recorded treatment | READ-PARTIAL, CAPACITY |
| FD-07 | session logs: CLAUDE.md asks for `.claude/sessions/` and `.claude/CONTEXT.md`; the lane may write only its folder | log in `F-SESSION-LOG.md` inside the folder |
| FD-08 | per-page digest as consumption evidence (§F-7.3) | adopt |
| FD-09 | commit the population list (`20260925_1206_list_of_files_to_read.log`, untracked) so its identity is also a commit | commit (outside this lane: human action) |
| FD-10 | the adapted rules §F-3 #7, #11, #13, #16, #22, #26 | approve as written |

**Rulings (F-LOG-0002):** RL-01 approve · RL-02 v3.5 Phase 2 for corpus/family grouping, P3B v1.7 for per-file research, no relabelling · RL-03 pointer to the first F-copy with hash verification · RL-04 read/process as F evidence, record `CONTENT-IDENTICAL-TO-S`, import no S interpretation · RL-05 approve the 3 repairs, 15 as RESOLUTION-FAILED, never guess · RL-06 register, explicit CAPACITY / READ-PARTIAL, F3026 a controlled capacity test · RL-07 logs inside the lane · RL-08 supplementary semantic evidence, not proof of attention · RL-09 commit the list · RL-10 approve the six adaptations as F-specific.

---

## §F-15 Known limitations (recorded, not solved)

- Page digests prove partial consumption, not full attention (§F-7.3).
- The isolation check does not see re-edits of files that were already dirty at baseline (§F-11).
- 80 F-files are untracked: their bytes are pinned by sha256, but not by a commit.
- Total volume (87.5 M characters) exceeds one session by a large factor; the lane is multi-session by design (§F-9).
- The F-Series population is defined by path exclusion against `02-FILES.jsonl`; 175 files are byte-identical to S
  files under other paths. This is a property of the population definition, recorded, not corrected.
