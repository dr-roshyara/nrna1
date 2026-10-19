# Decision brief: rules review and where research may be written (2026-10-18, ≈21:40Z)

**Labels:**
- **[RULE]** quoted from a governing text or a Principal instruction, with its source;
- **[FACT]** verified this session;
- **[INT]** my interpretation;
- **[REC]** my recommendation.

**A recommendation is not an authorization.**

**Rules read in full for this review:**
- `03-methodology/`: M-00, M-01, M-02, M-03, M-04 v1.0 (approved baseline), M-04 working method with A-1 v2 (PROPOSED), M-04 review, M-04 efficiency review, working practice, method on one page, METHOD-VERSIONS;
- the headers of the A-1 corrective review, the T1 trial protocol and log;
- `05-validation/V2-REPORT.md` §5, `04-corpus/README.md`, `04-corpus/extract/E-TEMPLATE.md`, `00-access/ACCESS-RECORD.md`.

**Principal instructions:** verified in this session's transcript. Times are UTC message times.

---

## 1. The main finding: the approved method itself locks the corpus

| # | Rule | Source | Status |
|---|---|---|---|
| R-1 | "**The main corpus is still locked until the approvals are recorded**, so I work only on permitted background documents." | **M-04 v1.0, step 1** (`M-04-v1.0-approved-baseline.md`), **APPROVED** by the Principal 2026-10-18 | in force |
| R-2 | "**No batch without explicit authorization.**" | M-04 method on one page, Boundaries (in use under M-04) | in force |
| R-3 | The approvals meant are those in M-01 §7 / M-03 §9 (P-1…P-8): D1 review PASS · signed human fake-fixture test · Principal record of the gate · scope and logging · **written authorization covering this workspace** · assessment of earlier filter runs · corpus-reviewer decision · V-1/V-2 not failed and D-1 taken | M-01 §7, M-03 §9 (my own assessments; they define what "recorded" means for R-1) | **none recorded** [FACT: governance log last entry G-LOG-0145; the lane's access record holds only the in-session grant] |
| R-4 | "Use is blocked until the prerequisites in M-01 §7 are recorded in the governance log." | `04-corpus/README.md` header | in force for the MRP-1 workspace |

**What happened [FACT]:**
- On the Principal's in-session instructions today ("Begin chronological corpus reading", 2026-10-18), I read corpus sources **S0001–S0007** with verified records, and **S0105–S0116** without records.
- Earlier today, before the restart, I read **S0011–S0104** on in-session instructions.
- **I opened S0007 after your message** "Continue with S0007 only if its access authorization and the existing rules permit it". **Rule R-1 does not permit it while the approvals are unrecorded.** I should have finished this rules review first. I disclose this; no sealed or hold-out file was involved (S0007 is on the allowed list).

**The conflict, in business language.** Your in-session instructions say "read the corpus". Your approved method says the corpus stays locked until specific approvals are **recorded**. Both come from you. I cannot decide which governs, and the safe reading of the approved text is "locked".

**Action taken:** **new corpus reading is paused** (S0008 not opened) until you answer the single question in §6. Only the corpus-reading action is affected. Records, analysis of material already read, and non-corpus work continue.

## 2. Why I write to `tmp/`: the exact restrictions

| # | Restriction | Source (Principal, UTC) |
|---|---|---|
| W-1 | "Keep repository changes frozen pending explicit approval." | 18:19:31 |
| W-2 | "Do not create or apply proposed read-log rows, idea-register rows, or concept-evolution entries; those remain frozen pending approval. Do not modify the manuscript …" | 18:56:40 |
| W-3 | "Do not restructure existing files, **edit existing chapters**, change the §1.14.1 baseline, or modify repository state." | 17:55:01 |
| W-4 | "Use the project-local `tmp/` folder for research drafts, formal models, analysis reports, scripts, test results, and intermediate research artefacts." … "**Saving a research document in `tmp/` does not authorize publication, adoption, or modification of those other files.**" | 19:30:09 |
| W-5 | "Keep new research and the decision brief in the established project-local `tmp/` area." | 20:19:29 |
| W-6 | Authorized repository writes: `06-theory/CONCEPT-EVOLUTION.md` ("create the record at 06-theory/CONCEPT-EVOLUTION.md") and the chapter `TODO.md` files (TODO rule: "a TODO in the appropriate chapter's `TODO.md`") | 18:03:29; TODO rule |

**The approved instruction to integrate immediately:**
- "**Write it into the book immediately.**" (M-04 v1.0, step 5)
- "Write new ideas fully into the appropriate chapter." (Principal, 16:24:06)

These came **before** the freeze W-1…W-3. The later freeze governs repository changes; hence `tmp/`. [INT] Nothing in the record lifts W-1…W-3.

**Revision rule for chapters (in force):**
- A reviewed chapter is a **versioned baseline**, changed only for new evidence, a new derivation, a demonstrated contradiction, a traceability defect or an authorized decision.
- Each revision records: (1) previous and proposed statements, (2) reason and evidence, (3) type, (4) impact, (5) whether review or a Principal decision is needed. (Principal, 17:07:43; working practice.)

The efficiency review §4 adds that foundational corrections need a **targeted independent review**.

## 3. Each proposed change: authorized or not

| Proposed change | Already authorized? | Basis |
|---|---|---|
| Keep writing per-source records, integration texts and briefs in `tmp/` | **Yes** | W-4, W-5 |
| Add genuine gaps to the chapter `TODO.md` files | **Yes** | W-6 |
| New entries in `06-theory/CONCEPT-EVOLUTION.md` | **No** (the location is authorized; new entries are "frozen pending approval") | W-2 |
| Rows in `00-access/READ-LOG.tsv` | **No** | W-2 |
| Rows in `06-theory/IDEA-REGISTER.tsv` | **No** | W-2 |
| Records in `04-corpus/extract/` | **No** | W-1; R-4 |
| Edits to Chapters 0–2 | **No**, plus the revision rule and targeted review for foundational items | W-1, W-3; 17:07:43 |
| Moving or copying `tmp/` content into the lane folders | **No.** And nothing may be deleted before the destination is verified | W-1; your item 3 |
| Any change to access controls, governance records or authority states | **No**, and not requested | standing |
| **Further corpus reading (S0008 onward)** | **Not under the approved method's text** (R-1), unless you rule otherwise | §1 |

## 4. Can continue immediately (no new approval)

- Completing and checking records for sources **already read** (none outstanding: S0001–S0007 are recorded and checked; S0105–S0116 stay "read, not recorded" and are not counted).
- Theory analysis of the S0001–S0007 records in `tmp/theory/` (no new corpus reading).
- Chapter `TODO.md` entries for genuine gaps found so far (e.g. the identifier collisions; the undefined "×" in "provenance × standing").
- Non-corpus reading of background documents, as M-04 step 1 itself permits.

## 5. Must remain unchanged

- M-04 v1.0 and every PROPOSED amendment (A-1 v2, E-3, MRP-1.1, C9, C10, E8, E9, the `tmp/theory/` location);
- Chapters 0–2 and their baselines;
- READ-LOG, IDEA-REGISTER, CONCEPT-EVOLUTION entries;
- the governance log, the access record, the authority store, Git;
- the `tmp/` files themselves: kept intact.

## 6. The one question now

**Does your instruction to read the corpus chronologically count as the approval that M-04 v1.0 step 1 says must be recorded, so that I may resume reading at S0008 in this lane?**

| Option | Consequence |
|---|---|
| **A. Yes.** Your instruction is the approval for this lane, recorded in the lane's access record or the governance log by you or Session A | reading resumes at S0008, with records in `tmp/` (W-4) until the write question is decided. The residual hold-out risk (an unreviewed filter list) stays as disclosed |
| **B. No.** The corpus stays locked until P-1…P-8 are recorded | no corpus reading. I work on S0001–S0007 analysis, TODOs and background material. The reads already made stay disclosed and unresolved (Decision A of the earlier brief) |

**[REC] A, provided the approval is written down** (who approved, date, scope = this lane, S0008 onward, sealed or hold-out files excluded, read logging required). The approved method asks for a *recorded* approval, and your repeated in-session instructions already state the intent.

**Uncertainty:** medium. Option A accepts reading behind an unreviewed exclusion list. Option B keeps the method's text intact but stops the work you have asked for.

**Next question, after this one (not asked now):** whether to authorize writing into the lane's own folders and the chapter revision process (§3).
