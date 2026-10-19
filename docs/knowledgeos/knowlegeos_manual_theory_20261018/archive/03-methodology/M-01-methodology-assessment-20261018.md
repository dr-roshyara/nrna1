# M-01 — Methodology assessment before main-corpus reconstruction

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Author | Session B (Professor) |
| Commission | Principal, "Research Methodology Assessment Before Main-Corpus Reconstruction" (2026-10-18) |
| Inventory | `03-methodology/INVENTORY-20261018.tsv` (127 rows), generated from `00-access/READ-LOG.tsv`, `01-ledger/READ-AND-EXTRACTED.tsv`, the earlier lane's `knowledgeos-s-series-theory/corpus-access/READ-LOG.tsv`, `00-access/ACCESS-RECORD.md` and `02-pilot-zero-gap/` |
| Constraints kept | **No corpus content was read** for this assessment. No sealed or hold-out material was opened. No file outside this workspace was changed. READ-LOG, ledger and B-00…B-13 are unmodified |
| Labels | **[EV]** direct evidence · **[I]** interpretation · **[H]** hypothesis · **[?]** open question |
| Standing | assessment and recommendation only. It decides nothing that is reserved to the Principal or Governance |

---

## 0. Bottom line

1. **What has been examined:**
   - **110 background files**: 109 read in full and extracted, 1 read partially.
   - **0 original-corpus files** read in this lane.
   - In the earlier S-Series lane: 1 corpus file read in full; 4 opened with read depth not logged, and one of these 4 lies outside the permitted frame (§1.4); 1 logged but never opened.
   - None of the earlier corpus reads has been re-extracted under the current method.
2. **The method works for the background**, but it is **not yet sufficient for the corpus**:
   - It has no reading queue.
   - It has no fixed extraction template.
   - It has no concept, chronology, dependency or contradiction ledgers.
   - Quotes are not mechanically checked against the source.
3. **Recommendation: continue with targeted corrections.** Keep everything that exists, stop reading background material, and add six specific components (§6).
4. **Corpus reading may not start yet.** The safety gate in the governance log is not recorded as passed. The authorization also names a different workspace from the one this lane writes to (§7).

---

## 1. Auditable reading inventory

### 1.1 Counts

| Population | Status | Files | Source of the count |
|---|---|---|---|
| **Background** (engineering, governance, PKS, programme, vision, AI-generated synthesis, instrument docs) | **Full read + extracted** to a basis note | **108** | ledger rows with status READ+EXTRACTED |
| | Full read, 0 bytes, nothing to extract | 1 | ledger (`Constitutional Governance Platform.md`) |
| | **Partial** (grep lines only), not extracted | 1 | READ-LOG (ADR_20260801_1740) |
| | **Total background examined** | **110** | |
| **Original corpus: this lane** | Read | **0** | ACCESS-RECORD checkpoint 12:15 UTC |
| | Metadata only (git commit, registry date) | 1 | S0011 |
| | Metadata only (path, sha, term counts) | 22 | `02-pilot-zero-gap/SOURCE-LIST.tsv` |
| | Metadata only (path, sha); **never to be opened** | 5 | `02-pilot-zero-gap/HELD-ASIDE.tsv` |
| | Mechanical count scans, no text displayed | 2 scans over 2,734 paths | READ-LOG rows 1 and the ADR-pattern scan |
| **Original corpus: earlier S-Series lane** (2026-10-17/18) | Full read, **not re-extracted** | 1 | `KnowledgeOS_Conceptual_Foundation.md` (Principal exception) |
| | Opened, **depth not logged**, not re-extracted | 3 | step_285, research/30, research/38 |
| | Opened, depth not logged, **outside the permitted frame** | 1 | the 2026-09-01 Gita-inspired model file; see §1.4 |
| | Logged, then corrected as **not opened** | 1 | `knowledgeos_kernel/research/00-INDEX.md` |
| **Synthesis / later work** | Session A family summaries: 5 partial (header, births, rationale), 1 depth not logged | 6 | earlier-lane READ-LOG |
| | Own manuscripts and S-Series documents | **unknown** | reads in earlier sessions were never logged |

**Extracted under the current method:**
- background: 108;
- original corpus: **0**.

### 1.2 By kind of background source (110 files)

| Subtype | Files | Basis notes |
|---|---|---|
| AI-generated synthesis, all PROPOSED (`docs/knowledge_tranfer/`) | 35 | B-10 KT-1…KT-8 |
| Engineering governance (ADR, baseline, C4, reference, ES, EEP, dev-guide theory) | 27 (26 full + 1 partial) | B-01…B-04, B-07 |
| PKS and the observation practice (`docs/pks/`) | 17 | B-05 |
| Programme theory RQ-002 and KnowledgeOS product documents (`docs/implementation/`) | 12 | B-08 |
| Research-instrument documentation (dev guides 06, 07, `s5_r7/` ×6) | 8 | B-12 |
| Engineering software documentation (dev guides 00–05) | 6 | B-11, B-12 |
| Vision (`docs/ideas/`) | 5 | B-09 |

### 1.3 What each status is based on

- **Full read:** the whole file was displayed in this session. The longest file has 1,018 lines and the read limit is 2,000 lines, so no line-count truncation occurred.
  - **[?]** Truncation of very long *single lines* was not checked.
- **Content verified:** the sha256 recorded at reading was compared with the current file on 2026-10-18. **All 109 background files and all 12 earlier-lane files are unchanged.**
- **Extraction independently checked:** **no**, for every row. Quotes in the basis notes have not been mechanically matched against the source.

### 1.4 Discrepancies and uncertainties

| # | Finding | Evidence | Handling |
|---|---|---|---|
| U-1 | READ-LOG and ledger **agree**: 111 unique logged paths; 109 in the ledger; the 2 not in the ledger are the partial ADR and the mechanical frame scans. No duplicates; no hash drift | reconciliation script, 2026-10-18 | none needed |
| U-2 | The earlier lane's READ-LOG has **no depth column**, so read depth is unknown for 4 corpus files and 1 family file | that log's header `utc path sha256 purpose` | recorded as DEPTH NOT LOGGED; those files are not counted as read |
| U-3 | **⚠ A file read on 2026-10-17 (`brainstorming/phase_measure_theory/20260901_0927_…gita_inspired…model.md`, first committed 2026-09-06) is not in the CORPUS-ALLOWED frame**, although the frame covers files committed before the 2026-09-11 snapshot | frame membership test (path only) | **Not investigated further.** G-LOG-01xx §6 says that if previously read material may be affected, Session B must stop and report without investigating. Reported here to the Principal. Its frame status must be settled by the authorized count-only check, not by me |
| U-4 | Reads of my own manuscripts and S-Series documents in earlier sessions were not logged | no READ-LOG covers them | count unknown; these are synthesis, never evidence |
| U-5 | The 2 mechanical scans used the frame derived from CORPUS-ALLOWED. G-LOG-01xx §6 says earlier filter outputs *"must not be treated as trusted authorization"* | ACCESS-RECORD disclosure | already disclosed; part of the prerequisite (§7) |
| U-6 | Basis notes give running totals (B-00 "16 files", B-06 "35 files"). These were correct when written. The cumulative figure is this inventory | ledger | no correction to the notes (append-only) |

---

## 2. Present understanding and its limits

### 2.1 The engineering background **[EV]**, consolidated in B-13

The programme built, between July and September 2026:
1. **A governance platform.** Constitution, standards and rulings are PROPOSED; the EEP is adopted.
2. **The KnowledgeOS observation runtime.** It has run; one completed cycle.
3. **CAP-001, -003 and -004 structural checks.** Warn-only.
4. **AI-session governance mechanisms AST-015…019.** Mostly not verified or adopted.
5. **Research instruments.** The gate runner, `admit.py`, and the S5 r7 verifier, which is not activated.

It also wrote, but did not build:
- **PKS** and the **08-16 tactical model**, both PROPOSED and AI-generated;
- **RQ-002**, the General Knowledge Constitution (07-11).

### 2.2 Recurring principles **[EV]** (as principles *of the engineering record*)

| # | Principle |
|---|---|
| 1 | Knowledge feeds authority and never holds it |
| 2 | A report of an act is not the act |
| 3 | Absence is never permission; missing evidence is not PASS (three-valued verdicts) |
| 4 | Only a recorded human act creates authority |
| 5 | The kinds are kept separate: observation, evidence, interpretation, decision |
| 6 | Values are derived, never asserted; projections are non-authoritative |
| 7 | History is append-only |
| 8 | Pinning: a claim stays checkable only if it records the versions it relied on |
| 9 | Honest limits: "unknown" and "no" are valid results |

Each is found in documents **and** in code (B-13 §4).

**These are not yet principles of the original KnowledgeOS theory.** No primary corpus evidence has been examined in this lane to establish that.

### 2.3 Relationships

| Statement | Kind |
|---|---|
| Platform practice (07-07/08) → RQ-002 (07-11) → KnowledgeOS charter (07-12) → PKS (08-01…) → corpus documents (Aug–Sep), by git dates | [EV] for dates |
| RQ-002 names the platform *"the first implemented bounded context"* of its model | [EV] |
| ARB 08-16 (in the knowledge-transfer folder): KnowledgeOS = T1 platform, PKS = T3 per-product space | [EV] that the folder records it; [?] where it is recorded authoritatively |
| The corpus theory continues RQ-002, or generalizes the engineering principles | **[H]** (H-B1″, H-B3), untested |
| The corpus's two "Zeros" correspond to the engineering distinction between proven empty and undetermined | **[H]**, lead only |

### 2.4 What I do not know **[?]**

- What the corpus says the theory **is**: its problem statement, primitives, the definitions of K and Zero/Gap, axioms, theorems, lenses.
- How its definitions **changed** over time; which versions are canonical by the authors' own acts.
- Whether the corpus **cites or depends on** RQ-002, PKS or the platform principles.
- The **dating** of the earliest documents (the S0011 anomaly).
- Whether my earlier second-hand picture (Session A's families, manuscript v1, the earlier lane's partial reads) is **faithful**. I treat all of it as leads.

---

## 3. The method actually used

| Activity | How it was actually done | State |
|---|---|---|
| **Selection and order** | Folder by folder, as the Principal directed. Within a folder, by date-prefixed filename. No pre-declared queue; the scope was decided turn by turn | **Informal** |
| **Completeness of reading** | Whole file displayed; line count and sha256 logged; ledger status only after the extraction note exists | **Implemented** (background). Not checked: lines-read vs file-lines as a recorded field; long-line truncation |
| **Extraction** | One note per batch: tables of findings with short verbatim quotes and section anchors. Selection by **salience** (what bears on "what is KnowledgeOS") | **Informal.** No fixed fields for definitions, assumptions, propositions, examples or mathematics; omissions are invisible |
| **Concepts and dependencies** | In prose and ad hoc tables (e.g. the origin chain, the maturation chain, KOS-M1…M5) | **Missing** as a ledger |
| **Definition change over time** | Ad hoc, with dates (e.g. the five meanings of "KnowledgeOS") | **Informal** |
| **Contradictions** | Listed as [?] items (OQ-5, PKS term collision, the "RATIFIED" event chain). Not classified | **Missing** as a register; no rule separating genuine contradiction from scope or terminology change |
| **Source vs interpretation** | Labels [SRC]/[EV], [I], [NEW], [?] in every note; claims kept to the strength of the evidence | **Implemented** |
| **Provenance** | path + sha256 in logs; section anchors (sometimes line numbers) in notes | **Partly implemented.** Anchors are not uniformly line-level |
| **When to synthesise** | Cross-confirmation across independent documents (e.g. a principle found in documents and in code); B-06 addenda and B-13 | **Informal.** No stated threshold |
| **Representativeness** | Not done | **Missing** |
| **Access control** | Stop at the gate; log every read; disclose scans; never open held-aside files | **Implemented** |

---

## 4. Is the method fit for purpose?

### 4.1 Assessment by criterion

| Criterion | For the background | For thousands of corpus records |
|---|---|---|
| Completeness, traceability | Good: every file logged, hashed and ledgered | **Insufficient:** no queue means completeness cannot be shown; salience extraction hides omissions |
| Historical accuracy | Adequate: git dates used | **Insufficient:** no chronology ledger; no rule for which dates establish precedence |
| Conceptual consistency | Adequate at small scale | **Insufficient:** definitions are scattered over 20 notes; drift cannot be tracked |
| Mathematical fidelity | Not exercised | **Missing:** no procedure separating the source's mathematics from mine |
| Contradiction handling | Ad hoc | **Missing:** no register, no classification |
| Reproducibility | Good for *what* was read | **Weak** for *why* and *what was extracted* |
| Protection against confirmation bias | Labels help | **Weak:** leads from manuscript v1 and Session A sit next to evidence in the notes; there is no separate leads register |
| Time spent on background | 110 files. The last three batches (KT-7, KT-8, B-12) **mostly restated principles already found** | Diminishing return; background reading should stop |

### 4.2 Weaknesses and corrections

| # | Weakness | Correction | Problem it solves | Essential? |
|---|---|---|---|---|
| W-1 | No reading queue | `QUEUE.tsv` with reason and tier for every file | Shows completeness and the order of work; makes selection reproducible | **Essential** |
| W-2 | Salience-based extraction | Fixed per-file extraction record, every section filled or marked "none found" | Omissions become visible; extraction becomes comparable across files | **Essential** |
| W-3 | No concept or definition ledger | `CONCEPTS.tsv`: each definition verbatim, with source and lines | Definitions can be compared and their drift tracked at scale | **Essential** |
| W-4 | History and theory mixed in notes | `CHRONOLOGY.tsv` separate from `DEPENDENCIES.tsv` | Keeps the historical sequence apart from the theoretical one, as the commission requires | **Essential** |
| W-5 | No contradiction register | `CONTRADICTIONS.tsv` with classification GENUINE · SCOPE · TERMINOLOGY · REVISION · UNDETERMINED | Prevents a later revision being read as a contradiction, or the reverse | **Essential** |
| W-6 | Section-level anchors; quotes unchecked | Line-range anchors, plus a mechanical **quote-anchor check**: each quote must occur in the file at the logged sha | Faithful quotation, verifiable without passing corpus text to anyone else (G-LOG-01xx §5 forbids sub-agents) | **Essential** |
| W-7 | Leads mixed with evidence | `LEADS.tsv`: everything from secondary sources (manuscript v1, Session A families, F-Series, KT documents) goes here, and a claim may never cite it | Confirmation bias from later manuscripts and summaries | **Essential** |
| W-8 | No mathematics procedure | `MATH.tsv`: verbatim statement → notation → well-formedness → check (proof, counterexample, or computation in scratch) → status | Mathematical fidelity; keeps source mathematics separate from mine | **Essential** when the first mathematical statement appears |
| W-9 | No synthesis threshold | Claim statuses with explicit evidence rules (§5.3) | Prevents synthesis from too little evidence | **Essential** |
| W-10 | No representativeness check | Per batch, record n read of N in each stratum (folder × month); run a stratified check before any theory-level claim | Shows which parts of the population a claim rests on | **Essential** before synthesis; optional per batch |
| W-11 | Long-line truncation unchecked | Record `lines_read` and `bytes_read` against file totals | Proves a "full read" | Optional (cheap; recommended) |
| W-12 | Near-duplicate files read repeatedly | Hash and diff check before reading; record DUPLICATE-OF / VARIANT-OF | Efficiency without summaries | Optional |

**Kept unchanged:**
- the READ-LOG and the ledger;
- the epistemic labels;
- stop-at-gate discipline;
- the basis notes B-00…B-13;
- one extraction artifact per read;
- writing only inside this workspace.

---

## 5. Recommended protocol — MRP-1 (Manual Reconstruction Protocol)

**Principle.** Primary reading is the unit of evidence. Tools only **locate, hash, count and check**; they never summarise.

All new files go under `04-corpus/`. Existing files are untouched.

### 5.1 Components

| # | Component | Artifact | Contribution to faithful reconstruction |
|---|---|---|---|
| 1 | **Source identification and provenance** | READ-LOG (existing), plus per file: S-id, path, sha256, line count, registry date, git first-commit date | Every statement can be traced to exact bytes |
| 2 | **Transparent reading queue** | `queue/QUEUE.tsv`: S-id · path · sha · tier · reason queued · status (QUEUED / READING / DONE / DEFERRED-with-reason / DUPLICATE-OF) | Completeness and order are visible and reproducible |
| 3 | **File-by-file extraction** | `extract/E-<S-id>.md`, fixed sections: header · definitions · assumptions · propositions/claims · arguments · examples · mathematical statements · terms introduced or changed · references to other documents · [I] my notes, kept apart · open questions. Every item carries a verbatim quote with a line range | Nothing selected silently; extraction comparable across files |
| 4 | **Concept and definition ledger** | `ledgers/CONCEPTS.tsv`: concept id · term · verbatim definition · S-id · lines · date · supersedes · status | One place to see what each term meant, where, and when |
| 5 | **Chronology** | `ledgers/CHRONOLOGY.tsv`: date · date basis (explicit in file / git / registry) · S-id · concept · change type BIRTH · RESTATES · EXTENDS · NARROWS · CHANGES · RETRACTS · CONTRADICTS. This is the relation vocabulary the S5 r7 guide records (B-12), reused rather than invented. **Precedence only from explicit, confirmed dates** | The historical sequence, kept separate |
| 6 | **Theoretical dependency map** | `ledgers/DEPENDENCIES.tsv`: concept A · depends on B · kind (defined-by / assumes / proved-from / specialises) · source anchor | The theoretical sequence, separate from history; branches allowed |
| 7 | **Contradiction and uncertainty registers** | `ledgers/CONTRADICTIONS.tsv` (classified as in W-5, with both anchors) · `ledgers/UNCERTAINTY.tsv` (dating, scope, ambiguous terms) | Real conflicts are distinguished from revisions and scope changes |
| 8 | **Mathematical reconstruction and verification** | `ledgers/MATH.tsv`: verbatim statement → normalised notation → status TRANSCRIBED · WELL-FORMED · VERIFIED · COUNTEREXAMPLE · UNDETERMINED; checks done in scratch and logged. My own mathematics is marked [NEW] and never merged into source rows | Fidelity to the authors' mathematics |
| 9 | **Claim-to-source traceability** | `ledgers/CLAIMS.tsv`: reconstructed claim · supporting S-id + lines · contrary evidence · coverage (n of N in the relevant strata) · status. Plus `ledgers/LEADS.tsv` for secondary material, never citable | Every theoretical claim rests on primary anchors; bias is visible |
| 10 | **Batch quality checks and reporting** | `batches/BATCH-nn.md` (§5.2) | Steady progress with honest counts |

### 5.2 Completion criteria

**A file is DONE when all of these hold:**
1. Its sha matches the queue.
2. `lines_read` equals the line count.
3. Every extraction section is filled or says "none found".
4. Every quote passes the mechanical anchor check at that sha.
5. New or changed concepts, chronology events, dependencies, contradictions and mathematical statements are entered in the ledgers.
6. READ-LOG and ledger rows are written. Only then is the status READ+EXTRACTED.

**A batch is DONE when all of these hold:**
1. Every queued file is DONE, DEFERRED with a reason, or DUPLICATE-OF with a recorded diff.
2. The quote-anchor check passes for every quote in the batch. This is a pass/fail check, not a percentage.
3. Reconciliation holds: every extraction is in the ledger, and every ledger row cites an extraction.
4. Hashes are rechecked.
5. A batch report gives counts read and deferred; new concepts; changed definitions; new contradictions by class; open questions; and coverage per stratum as **n of N**, never extrapolated.

**A theoretical claim has one of these statuses:**

| Status | Rule |
|---|---|
| **STATED** | At least one primary source states it verbatim |
| **STABLE** | Stated in two or more dated primary sources, with no later RETRACTS or unresolved GENUINE contradiction. A search for contrary statements (term search over the frame, logged) found none, or found them and they are listed |
| **CONTESTED** | An unresolved GENUINE contradiction exists |
| **RECONSTRUCTED** | My synthesis across sources. Labelled as such, with every premise anchored |
| **UNSUPPORTED** | Only secondary sources support it. Stays in LEADS |

Every claim also states which strata it has, and has not, been checked against.

### 5.3 Handling thousands of files without summaries

1. **Tiered queue.**
   - T0: the founding canon S0001–S0046 (08-02…04), plus S0011.
   - T1: files that define core terms, *located* by term search (location only, then read in full).
   - T2: a chronological sweep by folder family.
   - Hubs last, with their own protocol.
2. **Batches of 10–20 files**, each closed by §5.2 before the next starts.
3. **Duplicate and variant detection by hash and diff** before reading. An identical file is recorded, not re-read; a variant is read for its diff with the diff recorded.
4. **Mechanical tools only for:** hashing, line counts, term location, quote anchoring, ledger reconciliation.
5. **Early synthesis checkpoints after T0 and T1.** A provisional reconstruction with explicit coverage, so progress does not wait for the last file.
6. **Background reading on demand only:** when a corpus file cites a background source not yet read.

---

## 6. Decision

**Continue with targeted corrections to the current method.**

**Reason:**
- The parts that exist work and are kept: logging, hashing, the ledger, epistemic labels, gate discipline and the basis notes.
- The missing parts are exactly those needed at corpus scale: a queue, a fixed extraction record, separate ledgers, line-anchored and machine-checked quotes, a leads quarantine, and claim rules.
- The evidence for this is in §3 and §4: weaknesses W-1 to W-10 are not visible at 110 background files, but are unavoidable at 2,734.

**Necessary changes, and only these:**
1. **Stop background reading.** From now on it happens on demand only.
2. **Adopt MRP-1 components 2–9** (queue, extraction template, the six ledgers, LEADS) under `04-corpus/`.
3. **Line-range anchors, `lines_read`, and the mechanical quote-anchor check.**
4. **Re-read and re-extract under MRP-1** the corpus files read in the earlier lane, once authorized. They are not counted until then; this does not repeat work, because none of it was extracted.

---

## 7. The exact prerequisite for starting main-corpus reading

Checked against the authoritative record `docs/knowledgeos/chronological-read/P3B-GOVERNANCE-LOG.md` (2,352 lines, read 2026-10-18):

1. **The safety gate of G-LOG-01xx §3 must be recorded as passed.** It requires:
   - (a) independent D1 review PASS, or PASS WITH CONDITIONS with all conditions met;
   - (b) a fake-fixture test signed PASS by a separate human tester on a non-root account;
   - (c) **an explicit Principal record that the gate has passed**;
   - (d) confirmation of scope and logging.

   **Status: not recorded.** The last entry is G-LOG-0145; no later entry records the gate as passed. The D2 amendment to G-LOG-0146 exists only as a proposal. Until then *"the existing pause remains in force"*.
2. **A written scope extension to this workspace.** G-LOG-01xx authorizes Session B *"exclusively in the KnowledgeOS S-Series theory workspace"*. G-LOG-0141 names `knowledgeos-s-series-theory/` as the write folder. This lane writes to `knowlegeos_manual_theory_20261018/` by an in-session instruction only. §4 of the entry requires *"a separate written authorization from the Principal"* for any expansion of scope.
3. **The §6 assessment of the earlier filter runs**, and the **count-only check** after the gate passes. It must also cover **U-3** (the out-of-frame file read on 2026-10-17) and **U-5** (the frame used by the two mechanical scans). If the check shows previously read material was affected, I stop and report, as §6 requires.

Until all three are recorded, I will:
- open no corpus file;
- run no filter or count check;
- not build the queue from registry content.

I may, if the Principal wishes, prepare the empty MRP-1 templates in this workspace. That needs no corpus access.

---

## 8. Plain-language summary for the Principal

- **What I have read.** 110 background documents: the engineering platform, its governance, PKS, the earlier knowledge theory RQ-002, and the developer guides. They are all logged, unchanged since I read them, and summarised in notes B-00 to B-13.
- **What I have not read.** I have read **none of the original KnowledgeOS corpus** in this lane. In the earlier lane I read one corpus document fully and opened four others without recording how much I read. None of that has been processed with the current method.
- **One point needs your attention.** One file I read on 17 October is not on the permitted list. The rules tell me to report this and not investigate, so I have stopped there.
- **What I understand.** The engineering work is built on a small, consistent set of principles, for example "knowledge informs decisions but never makes them" and "missing evidence is never a pass". Whether the original theory is built on the same ideas is **not yet known**. Only the corpus can show that, and I have not assumed it.
- **My method so far.** It is careful about recording what was read and about separating facts from my interpretation. It is too informal for thousands of documents: there is no reading list, no fixed way of extracting, no central list of definitions or contradictions, and quotations are not automatically checked.
- **My recommendation.** Keep everything done so far and stop reading background material. Add a reading queue, a fixed extraction form, a small set of ledgers (definitions, history, dependencies, contradictions, mathematics, claims), and an automatic quotation check. Earlier summaries, including my own manuscript, may be used only as leads, never as evidence.
- **What must happen before I start.**
  1. You record that the safety gate has passed.
  2. You extend the authorization in writing to this workspace.
  3. The earlier filter runs, including the file I flagged, are checked.

  Then I begin with the founding documents and work through the corpus in small, fully checked batches, reporting counts honestly each time.
