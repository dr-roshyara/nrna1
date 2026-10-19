# Rule review: decision brief for the Principal (2026-10-18, ≈21:45Z)

| Field | Value |
|---|---|
| Purpose | understand, reconcile and prepare for approval the rules for the corpus research. **Nothing here is a decision.** |
| Corpus reading | **paused** (last recorded source S0007; S0008 not opened), per your instruction of 21:32Z |
| Read for this review | all of `03-methodology/`; `04-corpus/README.md` and `extract/E-TEMPLATE.md`; `05-validation/V2-REPORT.md` §5; `00-access/ACCESS-RECORD.md`; this session's transcript (your messages, with UTC times) |
| Changed | nothing outside this file |
| Labels | **[APPROVED]** approved by you · **[INSTR]** a later instruction from you · **[PROPOSED]** a proposal, mine unless stated · **[FACT]** verified · **[REC]** my recommendation |

---

## 1. Executive summary (business language)

1. **The approved method can be identified with confidence.** The stored M-04 v1.0 text matches, byte for byte, the text I wrote at 14:53:59Z plus the three corrections you asked for at 14:55:46Z, applied at 14:55:52–59Z. One point stays open: your acceptance said "with a few important corrections **before treating it as the stable version**", and the corrected text was never shown back to you. One confirmation from you closes this (TODO-01).
2. **The method is sound but was written for a smaller job.** It works file by file into the book. It says little about the corpus scale, the records per source, or who may review corpus material.
3. **Three real conflicts block safe progress:**
   - **Corpus access.** The approved method says the corpus stays locked until approvals are *recorded*. You have since told me to read the corpus, but no record defines which approvals count.
   - **Writing into the book.** The method says write into the book immediately. Your later freeze says do not edit chapters or registers, so findings sit in `tmp/` and the book does not grow.
   - **Independent review of corpus work.** The method requires it. Corpus material may not go to a reviewer without your separate approval, and none exists.
4. **Most other questions are ordinary method questions** that I can settle by research (dates, unclear terms, identifier clashes, quote formatting).
5. **You need to take 9 decisions to approve the working rules (§8 A) and 1 decision to reopen corpus reading (§8 B).** They are independent: approving the rules does not open the corpus, and opening the corpus does not allow chapter edits.

---

## 2. The authoritative baseline and its verification

| Item | Finding | Status |
|---|---|---|
| Approved text | `03-methodology/M-04-v1.0-approved-baseline.md`, 105 lines, sha256 `32611109befad4da14125669707014a4d9500e828d82541e769636939a62ca41` | [FACT] hash recomputed now; unchanged |
| How it was approved | your message of **14:55:46Z** (a pasted review): *"I would accept M-04 as the working methodology, with a few important corrections before treating it as the stable version"*, followed by three corrections with recommended wording | [FACT] transcript |
| Is the stored text the approved text? | **Independently verified today:** replaying the transcript (the Write at 14:53:59Z, then three Edits at 14:55:52/57/59Z) reproduces **exactly** this hash. The three edits carry your recommended wording for correction 3 (completeness against source), correction 2 (narrowed V-2 claim) and correction 1 (separate reviewer authorization), plus the status line | [FACT] replay script result; copy at `scratchpad/M-04-replayed-1455.md` |
| What remains unverified | that you consider the corrected text "the stable version": it was not shown back to you after correction | **TODO-01** |
| Amendment A-1 v2 | **PROPOSED**, not approved (`METHOD-VERSIONS.md` entry 3) | treated as a proposal throughout |

---

## 3. The approved rules (M-04 v1.0), in business language

*For each rule: what it means; why we need it; what I must do; what I must not do; evidence it was followed.*

**AR-1 · Check permission first; the corpus is locked until approvals are recorded** (step 1)
- **Means:** I only read what I am allowed to read, and the main corpus stays closed until the approvals for it are written down.
- **Why:** the corpus contains a sealed test set (hold-out). Reading it by accident would destroy the test permanently.
- **Do:** check the allowed list and the access record before every file.
- **Don't:** open locked, sealed, test or password files, not even indirectly.
- **Evidence:** a read log showing the check per file, and an access record naming the approval.

**AR-2 · Read the whole source to understand it** (step 2)
- **Means:** read everything, including tables, examples, code and footnotes, and ask what problem it solves, what it proposes, what it assumes and what it leaves open.
- **Why:** important ideas hide in examples and tables.
- **Do:** read every line.
- **Don't:** skim or keyword-search instead of reading.
- **Evidence:** lines read = lines in the file; the file's hash logged.

**AR-3 · Compare each idea with the book** (step 3)
- **Means:** label every idea New / Repeat / Refinement / Contradiction / Evidence / Open.
- **Why:** this shows what the source adds and stops duplicates.
- **Do:** check the relevant chapter before writing.
- **Don't:** assume a later document is right because it is later.
- **Evidence:** a label per idea, with a pointer to the chapter section.

**AR-4 · Check that it is true, not only that it is written** (step 4)
- **Means:**
  - re-derive arguments and look for counterexamples;
  - check software claims against code and records;
  - separate a proposal from an exploratory decision and from a formally adopted decision.
- **Why:** documents can be wrong, and many are AI-generated proposals.
- **Do:** mark anything I could not verify as "not verified".
- **Don't:** quietly accept or drop unverifiable claims.
- **Evidence:** a verification note per important claim.

**AR-5 · Write it into the book immediately** (step 5)
- **Means:** each idea goes into the right chapter in my own words: what it means, why it matters, how it connects, what is uncertain. Improve existing sections rather than adding duplicates. Revise earlier text when evidence shows it was wrong.
- **Why:** the book is the deliverable; notes are not.
- **Don't:** leave ideas only in notes.
- **Evidence:** chapter text with source references; a change log.
- ⚠ **In conflict with your later freeze: see CF-2.**

**AR-6 · Keep a trace for every idea** (step 6)
- **Means:** a register says where each idea came from, its label, how it was checked and where it sits in the book. Rejected ideas stay, with the reason.
- **Why:** nothing may disappear silently.
- **Evidence:** register rows.
- ⚠ **Register rows are frozen: see CF-3.**

**AR-7 · Prove completeness against the source, not the register** (step 6a; your correction 3)
- **Means:** after each file, compare the source, the register and the book directly. Every meaningful idea gets one of six outcomes: integrated · explained elsewhere · evidence · unresolved · rejected with reason · out of scope with reason.
- **Why:** the register can only list what I noticed.
- **Don't:** call a file complete because every registered row has a status.
- **Evidence:** a disposition per idea, checked against the source.

**AR-8 · Look back twice after every file** (step 7)
- **Means:** ask "did I miss anything?" and "does the book still make sense?"
- **Why:** catches omissions and inconsistencies early.
- **Evidence:** a short look-back note per file.
- ⚠ **Whether the second question needs a whole-book check per file is pending (E-3): see CF-5.**

**AR-9 · Review after every five files: continue, correct or pause**
- **Means:** read the affected chapters as one work and decide.
- **Why:** errors spread if not stopped.
- **Evidence:** a five-file review note with the decision.

**AR-10 · Independent audits**
- **Means:** a fresh reviewer checks that corrections were really made, that references support the claims and that the mathematics holds. **Every 100 corpus files**, a fresh reviewer re-reads a random sample **before** seeing my notes, and a separate adjudicator settles disagreements. (The V-2 claim is narrowed per your correction 2.)
- **Why:** one reader misses things.
- **Evidence:** audit reports.
- ⚠ **Blocked for corpus material: see CF-4.**

**AR-11 · Reviewer access is separately authorized** (your correction 1)
- **Means:** my access to the corpus does not give a reviewer access.
- **Do:** check that your separate written approval is recorded before any reviewer sees corpus material.
- **Don't:** run a restricted review without it.
- **Evidence:** the recorded approval.

**AR-12 · Four standing rules**
1. **Source, check and opinion are labelled separately.**
2. **Repetition is not proof:** ten documents may share one origin.
3. **"Unknown" is an answer:** missing information is reported as missing.
4. **The book matters more than the paperwork.**

**Evidence:** labels on every statement; repeated sources counted as one line of support.

---

## 4. Your later instructions and how they change the workflow

| # | Time (UTC) | Instruction (short, verbatim where quoted) | Effect |
|---|---|---|---|
| L-0a | **14:11:40** | "**After the required corpus access authorization is formally recorded**, begin the first 100-file research batch … obtain an independent review checkpoint after the batch" | **supports AR-1: corpus access needs a FORMALLY RECORDED authorization**; sets a 100-file batch with an independent checkpoint (bears on CF-7, TODO-02) |
| L-0b | **14:14:19** | "apply the four corrections and continue" (the V-2 corrections R1–R4: meaning lines describe only their own source; itemise dense lines; REPETITION-OF only for verbatim or subset repeats; keep a real independent re-extraction per batch) | **R1–R4 approved** as extraction practice |
| L-0c | 14:25:46 | "read the file, get idea, verify the idea and write in the theory chapter … after writing few sections review back two things: 1) check if you left any ideas … 2) check if the derived theory make sense" | the working loop; became M-04 steps 2–7 |
| L-1 | 14:56:53 | "continue with the next five files after the audit" | the batch cadence in use |
| L-2 | 16:24:06 | "Write new ideas fully into the appropriate chapter … Do not assume that a later document is automatically more authoritative" | reinforces AR-3 and AR-5 |
| L-3 | (16:24–17:07) | the simplification instruction, recorded as the **working practice** (`M-04-working-practice`; METHOD-VERSIONS entries 7–8): a light record; **"not done any more"**: line-by-line coverage, non-propositional line lists, strict per-row validation, an audit document per round, repeated full-book scans, re-wording stable text; three review perspectives used in proportion | lightens AR-6 to AR-8 in practice; **not an amendment** |
| L-4 | 17:07:43 | **continuity rule:** a reviewed chapter is a versioned baseline; revise only for new evidence, a new derivation, a demonstrated contradiction, a traceability defect or an authorized decision; each revision records previous/proposed text, reason, type, impact and needed review | governs every chapter change |
| L-5 | (≈17:00–17:30) | the **method on one page** and its clarifications: evidence over majority; proof ≠ counterexample ≠ tests ≠ source verification; "judge the reasoning, not the phrasing" (METHOD-VERSIONS entries 9–10) | in use; **not an amendment** |
| L-6 | 17:55:01 | "Do not restructure existing files, **edit existing chapters**, change the §1.14.1 baseline, or modify repository state." | **freezes chapters** |
| L-7 | 18:03:29 + TODO rule | create `06-theory/CONCEPT-EVOLUTION.md`; record every undefined, ambiguous or disputed concept in the chapter `TODO.md` | the two permitted repository writes |
| L-8 | 18:19:31 | "Keep repository changes frozen pending explicit approval." | **general freeze** |
| L-9 | 18:56:40 | "Do not create or apply proposed read-log rows, idea-register rows, or concept-evolution entries; those remain frozen pending approval. Do not modify the manuscript …"; do not edit frozen CONTEXT or session logs to silence warnings | **freezes the registers and logs** |
| L-10 | 19:30:09 | "Use the project-local `tmp/` folder for research drafts …"; "Saving a research document in `tmp/` does not authorize publication, adoption, or modification of those other files." | **`tmp/` is the working area** |
| L-11 | 20:19:29 | "Keep new research and the decision brief in the established project-local `tmp/` area." | confirms L-10 |
| L-12 | 21:03–21:09 (several) | begin chronological reading of the original corpus; investigate unclear concepts with authorized and credible external sources; S-series documents supply the rules only; restart from S0001 with a verified record per source before the next | **reading instruction**; record-per-source rule |
| L-13 | 21:17:31 | keep C9, C10, E8, E9 and `tmp/theory/` marked PROPOSED; record actual dates; index order ≠ date order where evidence contradicts | in effect |
| L-14 | 21:23:38 | verify the rules; keep `tmp/` records intact; protect reviewed chapters; continue S0007 **only if access and the existing rules permit** | in effect |
| L-15 | 21:32:57 | **do not resume corpus reading until this review is complete and access is verified**; prepare this brief | **in effect now** |

**Access rules that come from governance, not from M-04:**
- **G-LOG-0141** (committed): sealed hold-out files and concepts excluded; a filter before reading; read logging; findings are hypotheses only; the Professor takes no neutral roles; measurement data, ledgers, sealed lists and hold-out files forbidden.
- **G-LOG-01xx §5** (uncommitted): no corpus material to sub-agents.
- **G-LOG-0144** (uncommitted): role separation for exposed sessions.

---

## 5. Proposed amendments (none approved)

| ID | What it would change | Proposed by | Status |
|---|---|---|---|
| A-1 v2 (a)–(h) | claims described on five dimensions · claim as the unit · contradiction procedure without date or majority · a checked glossary · whole-source re-read · audit measures M1–M5 · evidence classes for method changes · version register | me, after your corrective review | **PROPOSED** |
| E-3 | move the whole-book coherence check from every file to the five-file review | me (efficiency review) | **PROPOSED**; meanwhile applied in proportion |
| E-1, E-2, E-4, E-5 | coverage check before the look-back · cite only from verified output · use the six dispositions verbatim · extract in source order | me | proposed as practice; "no method change needed" |
| MRP-1 / MRP-1.1 | per-source extraction records, ledgers (concepts, chronology, dependencies, contradictions, mathematics, claims, leads), quote-anchor check, queue, line accounting, independent double extraction, completion conditions TC-1…10 | me (M-01, M-02, M-03) | MRP-1.1 **PROPOSED** (D-1 open); whether MRP-1 itself was ever adopted is **TODO-05** |
| C9, C10, E8, E9 | identifiers cited with their source · quotes as plain text · "recurring" needs two independent sources · theory statements cite record IDs | me (today) | **PROPOSED** |
| `tmp/theory/` | location of the working theory text | me | **PROPOSED** |
| M-04-T1 trial protocol v3 | — | me | **set aside by its author**; never adopted |

---

## 6. Conflicts, missing definitions and TODOs

### 6.1 Conflicts (none resolved by guessing)

| # | Conflict | Sources | Why it matters | Recommended resolution [REC] |
|---|---|---|---|---|
| **CF-1** | **May I read the corpus?** AR-1 says locked "until the approvals are recorded"; L-12 says read it; L-15 says wait | M-04 step 1; L-12; L-15; ACCESS-RECORD (in-session grant "read everything", 12:00Z) | without a clear answer every read is open to challenge, and a slip could consume the hold-out | you define, **in writing**, which approvals unlock this lane (§8 B) |
| **CF-2** | **Write into the book now** vs **chapters frozen** | AR-5, L-2 vs L-6, L-8 | the book does not grow, and integrations pile up in `tmp/` | authorize the chapter revision process under L-4, with a targeted review for foundational changes (D-R5) |
| **CF-3** | **Register and read log required** vs **rows frozen** | AR-6, 04-corpus rules vs L-9 | traceability lives in `tmp/` copies, not the official records | decide where the official read log and register live (D-R6) |
| **CF-4** | **Independent corpus review required** vs **no corpus to reviewers without separate approval** | AR-10 vs AR-11, G-LOG-01xx §5 | corpus extractions cannot be independently checked now | decide who may review and under which access record (D-R7) |
| **CF-5** | **Whole-book check per file** vs **"no repeated full-book scans"** | AR-8 vs L-3 | per-file cost vs late detection of inconsistency | decide E-3 (D-R3) |
| **CF-6** | **Record weight:** the full extraction template (MRP-1) vs the "light record" of L-3 vs L-12's demand for full evidence per source | E-TEMPLATE; L-3; L-12 | records either too heavy or too thin | keep today's E-record (quotes and line anchors, business meaning, dispositions) **without** line-by-line coverage and **without** the six ledgers (D-R4) |
| **CF-7** | **Batch size:** five-file reviews (AR-9) vs 100-file batches closed in groups of 10–20 (04-corpus rule 9) vs "every 100 corpus files" audits (AR-10) | as named | unclear checkpoints | five-file review for the book; 100-file independent sample (D-R8) |
| **CF-8** | **Which governance applies to this lane?** G-LOG-01xx limits itself to "the S-Series theory work"; this lane rests on an in-session grant; you said "forget s-series" but S-series supplies the rules | G-LOG-01xx §8; ACCESS-RECORD; L-12 | the applicable gate is undefined | covered by your answer to §8 B |
| **CF-9** | **"No batch without explicit authorization"** vs a general instruction to read chronologically | one-page method, Boundaries; L-12 | is "read chronologically" a standing batch authorization? | state the authorized range or batch size when reopening (§8 B) |

### 6.2 TODOs

| ID | What is unknown | What resolves it |
|---|---|---|
| **TODO-01** | whether you treat the corrected M-04 text (hash `32611109…`) as the stable approved version | your confirmation of the hash |
| **TODO-02** | which record satisfies "approvals are recorded" in AR-1: the governance log, the lane access record, or both | your decision (§8 B) |
| **TODO-03** | whether the P-1…P-8 prerequisites I wrote in M-01 §7 and M-03 §9 are mandatory or only my recommendations; AR-1 does not list them | your decision (§8 B) |
| ~~TODO-04~~ **RESOLVED by research** | the status of the extraction rules R1–R4 | **Adopted:** your message at **14:14:19Z**, "apply the four corrections and continue", answering the V-2 report's §5 corrections. R1–R4 are therefore **approved extraction practice** (added to §4 as L-0b) |
| **TODO-05** | whether MRP-1 (M-01) was ever adopted; MRP-1.1 is PROPOSED (D-1 open) | your decision D-R4 |
| **TODO-06** | version control for the research workspace: untracked, so hashes detect changes but cannot prevent them | your decision D-R9 |
| **TODO-07** | the status of the reads already made (S0001–S0007 recorded; S0011–S0104 and S0105–S0116 earlier) under AR-1 | your decision (Decision A of the earlier brief) |
| **TODO-08** | the reliability of the protective allowed list (from an unreviewed filter run, G-LOG-01xx §6) | D1 review, or your acceptance of the risk |
| **TODO-09** | how dates are fixed when the index and the file disagree (S0006: index 2026-07-11, file 2026-08-03) | **research, resolvable by me:** use the file's own date with its line anchor; keep the index date as metadata |
| **TODO-10** | identifier collisions (I-8/I-9, C-1, C-5, R-n) | **research, resolvable by me:** always cite with the source document (my C9 practice) |

---

## 7. Recommendations, with reasons

| # | Recommendation | Reason and source |
|---|---|---|
| REC-1 | Confirm M-04 v1.0 by its hash | closes TODO-01; the replay evidence (§2) supports it |
| REC-2 | Approve A-1 v2 **in part:** (c) contradictions by reasons, (d) glossary check, (g) evidence classes, (h) version register. Defer (a) five dimensions and (f) audit measures until the corpus pilot shows the need | (c), (d) and (h) fix observed problems (term drift in pilots 1–2; today's identifier and date issues); (a) and (f) add workload not yet shown necessary (M-04 review §B) |
| REC-3 | Approve E-3 with its exception (a whole-book check runs immediately if a file changes a defined term's meaning) | L-3 already asks for no repeated full-book scans; the exception keeps the risk small |
| REC-4 | Record format: the E-record as used for S0001–S0007, with no line-by-line coverage and no separate ledgers | meets L-12 (evidence per source) and L-3 (no line-by-line coverage); quotes are machine-checked (the S0001–S0007 records were corrected until every quote passed) |
| REC-5 | Writing: records and read log in `04-corpus/` and `00-access/`; book text into the chapters under the L-4 revision rule, with a targeted independent review for foundational changes. `tmp/` keeps drafts. Moves are copies first; the originals stay until verified | resolves CF-2 and CF-3 without bypassing L-4 |
| REC-6 | Independent review of corpus extractions: a **human** reviewer, or a separately authorized session, named by you, with its own access record; until then, review only non-corpus material | AR-11; G-LOG-01xx §5 |
| REC-7 | Checkpoints: five-file book review (AR-9); independent sample every 100 corpus files (AR-10) | resolves CF-7 using the approved text |
| REC-8 | Put the research workspace under version control (a separate, unpushed branch, or committed when you choose) | TODO-06; hashes alone cannot prevent silent change |
| REC-9 | Corpus access: write one access entry for this lane (who, date, range, exclusions, logging, accepted residual risk) and say explicitly whether the G-LOG-01xx gate applies to it | resolves CF-1, CF-8, CF-9, TODO-02 and TODO-03 |

---

## 8. Decision sheet

### A. Rule approval: decisions only you can make

| ID | Question | Options | My recommendation | **Your decision** |
|---|---|---|---|---|
| D-R1 | Is `M-04-v1.0-approved-baseline.md` (sha256 `32611109…62ca41`) the approved stable M-04? | yes · no (state the difference) | yes (§2 evidence) | |
| D-R2 | A-1 v2 | approve all · approve parts (name them) · reject | parts (c), (d), (g), (h) | |
| D-R3 | E-3 (coherence check at the five-file review, with the meaning-change exception) | approve · reject | approve | |
| D-R4 | Per-source record format; MRP-1/1.1 status | E-record without line coverage or ledgers · full MRP-1.1 · other | E-record (REC-4); MRP-1.1 not adopted | |
| D-R5 | May findings be written into Chapters 0–2 under the L-4 revision rule? | yes, with a targeted review for foundational changes · no, keep in `tmp/` | yes, with review | |
| D-R6 | Where do the official read log, register and records live? | lane folders (`00-access/`, `06-theory/IDEA-REGISTER.tsv`, `04-corpus/extract/`) · stay in `tmp/` | lane folders, copy-then-verify | |
| D-R7 | Who may independently review corpus extractions, under which access record? | a named human · a separately authorized session · none for now | a named human; none until then | |
| D-R8 | Checkpoints | 5-file book review + 100-file independent sample · other | as stated | |
| D-R9 | Version control for the workspace | yes (how) · no | yes | |
| D-R10 | My proposals C9, C10, E8, E9 and `tmp/theory/` | approve · reject · each separately | approve C9, C10; decide E8 with D-R4; `tmp/theory/` only if D-R6 keeps `tmp/` | |

### B. Corpus authorization: separate from A

**What the approved method requires:** AR-1. The corpus is locked "until the approvals are recorded". **The method does not name the approvals** (TODO-02, TODO-03).

**Candidate prerequisites and their state:**

| # | Prerequisite | Source | State |
|---|---|---|---|
| B-1 | a written authorization of corpus reading **for this lane** | M-01 §7.2 / M-03 P-5; AR-1 | **partly met:** your in-session grant (12:00Z, quoted in ACCESS-RECORD) and your reading instructions (21:03–21:09Z). **Not recorded as an approval of AR-1, and not in the governance log** |
| B-2 | hold-out protection: the D1 filter review PASS + signed human fake-fixture test + your record that the gate passed | G-LOG-01xx §3 (uncommitted; S-series scope); M-03 P-1…P-3 | **not met** |
| B-3 | the scope and logging confirmed | G-LOG-01xx §3(4); M-03 P-4 | **partly met:** logging is in use; scope not confirmed in writing |
| B-4 | assessment of the earlier filter runs | G-LOG-01xx §6; M-03 P-6 | **not met** |
| B-5 | a corpus-reviewer decision | M-03 P-7; AR-11 | **not met** (needed for AR-10 review, not for my reading itself) |
| B-6 | V-1/V-2 not failed; D-1 taken | M-03 P-8 (my own proposal) | V-2 run, V-1 not run, D-1 open. **My recommendation, not a mandate** |

**Does your earlier authorization satisfy any of these?**
- It **partly satisfies B-1**: it authorizes scope, but it is not written down as the AR-1 approval.
- It **does not satisfy** B-2 or B-4.
- Whether B-2…B-6 are **mandatory for this lane** is undefined, because G-LOG-01xx limits itself to the S-series work. **That is the core question.**

| ID | Question | Options | My recommendation | **Your decision** |
|---|---|---|---|---|
| **D-C1** | What must be recorded before this lane may read the corpus (S0008 onward)? | (a) only B-1, written into the lane's access record by you (or Session A), with exclusions, logging and an explicit acceptance of the residual hold-out risk · (b) B-1 + B-2 + B-4 (the full gate) · (c) other | **(a)** if you accept the residual risk (the same list protected 103 + 19 reads with no sealed file opened); **(b)** if hold-out protection must be independently assured first. *My uncertainty: medium; the choice is a risk acceptance only you can make* | |

**Not implied by either decision:**
- approving the rules (A) does **not** open the corpus;
- opening the corpus (B) does **not** permit chapter edits (D-R5) or reviewer access (D-R7).

---

## 9. Implementation sequence after your decisions

1. Record your decisions verbatim in `03-methodology/METHOD-VERSIONS.md` (if D-R6 allows lane writes; otherwise in `tmp/`), with the hashes of the approved texts.
2. If D-R6 = lane folders: copy the `tmp/` records (E-S0001…E-S0007), the read log and the integration texts to their lane homes. Verify the hashes and contents. **Keep the `tmp/` originals until you confirm.**
3. If D-R5 = yes: apply the W-01…W-33 book texts to Chapters 0–2 under the L-4 revision record. List the foundational changes for a targeted review (D-R7) before treating them as established.
4. Add the TODOs found so far to the chapter `TODO.md` files (already permitted).
5. **Only after D-C1 is recorded:** resume at S0008 in index order, one verified record per source, a five-file review at S0010, and an independent sample at 100 files if D-R7 names a reviewer.
6. Report per session: sources read and recorded, findings, TODOs, blockers, the next source.

**What I can do now without any decision:**
- TODO-04 (locate the R1–R4 adoption in the transcript);
- TODO-09 and TODO-10 (dates, identifiers) as practice notes;
- non-corpus analysis of the existing records.

No corpus reading.
