# Theory Readiness Reassessment: S-series access clarification (2026-10-18)

**What this is.** A reassessment of `THEORY-READINESS-ASSESSMENT-20261018.md` (same folder; that file is kept unchanged). It responds to the Principal's in-session clarification:

> "The S-series material may be read because it was derived from the original corpus of approximately 2,700 files."

**What this is not.** It is not a decision, and it is not a governance record. **A recommendation is not an authorization.**

**Method.** I read only:
- governance records;
- the S-series state file;
- the S-series workspace README;
- the workspace copy of the Principal decision record;
- `git` metadata.

**No corpus document, research record, family file, ledger, batch input, seal or hold-out file was opened.**

**Labels:**
- **[FACT]** read at source this session;
- **[RULE]** wording of a governing text;
- **[INT]** my interpretation;
- **[REC]** my recommendation.

**Commit status of every source** (HEAD = `925e58045`, 2026-10-17 17:52):

| Source | Status |
|---|---|
| `P3B-GOVERNANCE-LOG.md` up to and including G-LOG-0141 + extension (L1–2231) | **committed** |
| G-LOG-01xx and G-LOG-0142…0145 (L2233–2352) | **working tree only** (+82 non-empty lines) |
| S-series `README.md` (L34, L37) | **committed**, unchanged |
| `.claude/S-SERIES-TODO.md` | **committed**, unchanged |
| `PRINCIPAL-DECISION-RECORD-20261017_1944.md` | workspace copy; **"not a governance-log entry"** (its own L7) |

---

## 0. Correction to the earlier assessment

**The earlier claim.** The earlier assessment (§4 C-1) said the committed log held only a "G-LOG-0141" header.

**What is actually the case [FACT].**
- `git show HEAD:` contains G-LOG-0141 items 1–7 and the extension (HEAD L2221–2231).
- The working tree changes only that header line (`# G-LOG-0141:` → `# G-LOG-0141 allowed only for professor:`).
- It adds G-LOG-01xx and G-LOG-0142…0145.

**Consequence.**
- **G-LOG-0141 (the Professor's read permission) is a committed record.**
- **The safety-gate pause (G-LOG-01xx) is not.**
- The integrity gap is therefore narrower than reported, and it falls on the *restricting* entry, not on the *permitting* one.

---

## 1. The six activities, kept apart

| # | Activity | Governing rule(s) | Authorization | Current status |
|---|---|---|---|---|
| 1 | **Reading existing S-series research documents and methodological notes** | README rule 1 (L34) [RULE]: the manuscript is written "only from authorized governing and methodological sources", and reads no "S5 data, batch inputs, ledgers, witness or candidate archives, or hold-out and seal files"; G-LOG-0141 item 7 (committed): "measurement data, ledgers, sealed lists, hold-out files" stay forbidden | Principal's in-session clarification (2026-10-18; not in the log); G-LOG-0141 (committed) for brainstorming/ | **Permitted**, with the exclusions named in those two rules |
| 2 | **Reading existing analyses derived from the ~2,700-file corpus** (Session A research records, `chronological-read/20-FAMILIES/`, the 02-FILES discovery population) | **G-LOG-0141 extension (committed, L2230–2231): scope = "all discovery-population files of 02-FILES.jsonl and chronological-read/20-FAMILIES/, *same filter and logging*"**; G-LOG-01xx §3 (uncommitted): no primary-source read until the gate passes; Decision record D2: "no primary-source read" | The clarification *may* cover this, depending on what "S-series material" means (Q-1) | **Not yet clear.** A written rule routes these files through the hold-out filter, which is currently paused |
| 3 | **Preparing the theory-extraction methodology** | TODO P1 (L234): "Stage-1 Registered Report (pre-data methods only…)"; G-LOG-0028 (L596, **non-normative**): the formal synthesis charter comes "after S5b"; D5 recovery charter is a human decision (TODO L206, open) | Decision record: "Permitted while reviews are pending: synthetic-only research … and the analytical questions" | **Permitted as a draft.** It cannot be or replace the D5 recovery charter |
| 4 | **New corpus reads for theory extraction** | O1 (G-LOG-0125, committed, L2010): "no early corpus reads", which G-LOG-0141 item 6 (committed) changes "for the Professor only"; G-LOG-01xx §3 (uncommitted): "No production filter run, count-only source-ID check or primary-source read is authorized" until four conditions hold | none currently effective | **Blocked**: the gate has not passed (D1 reviewer and tester not appointed; D2 answer pending) |
| 5 | **Measurement operations (Run-3, counted canary, S5)** | G-LOG-0117 (Run-3 approved at G2 only); G-LOG-0140 (G3–G5 not authorized); G-LOG-0141 item 7 (committed): Run-3 forbidden to the Professor; **G-LOG-0141 item 5 (committed): having read the corpus, the Professor "can no longer play neutral roles later: measurement, auditing, linkage judging, or writing hold-out predictions"** | none | **Blocked.** For the Professor it is blocked **permanently by role**, not only by status |
| 6a | **Provisional hypotheses** | G-LOG-0141 item 4 (committed) [RULE]: findings are "research observations and hypotheses only. They are never proof, and they never replace the formal measurement stage (S5)"; README rule 4 (committed, L37) | G-LOG-0141 item 4 | **Permitted as labelled hypotheses** from lawfully read material. Scope tension with rule 4: §2 |
| 6b | **Verdict-bearing theory (ROBUST / NO COHERENT THEORY)** | O1 (G-LOG-0125): "Theory recovery follows S5a/S5b … after S5b"; G-LOG-0140: verdict criteria undefined; G-LOG-0145: only V-2 (ii) adopted; G-LOG-0143: H-19 scoreability deferred | none | **Blocked**: no S5 data, no adopted criteria |

**No restriction was carried across rows without a rule.** Where a row is blocked, the blocking rule is named in the same row.

---

## 2. The "no substantive theory before S5b" rule: exact wording and scope

**Wording [RULE]** — README rule 4 (`docs/knowledgeos/knowledgeos-s-series-theory/README.md` L37, committed):

> "**No evidence-free theory.** No substantive theory content is written before S5b and the recovery charter. Corpus-dependent chapters stay PENDING."

**Nature [FACT].**
- It is a **writing rule binding on this lane** (README §3 heading: "Writing rules (binding on this lane)").
- It lives in the lane's own README.
- Its upstream sources are:
  - **O1 (G-LOG-0125, committed human act):** "Theory recovery follows S5a/S5b … and G-LOG-0028 after S5b; no early corpus reads";
  - **TODO P1 (L234, an open, unadopted 👤 item):** "no theory content before S5b".
- G-LOG-0028, the "charter after S5b" note, is explicitly **"Informational / non-normative"** (L567).
- **G-LOG-0141 item 6 (committed, later than O1): "O1 changes for the Professor only."**

**Scope, activity by activity:**

| Does rule 4 prohibit… | Answer | Basis |
|---|---|---|
| reading existing S-series material | **No.** Rule 4 governs *writing*; reading is governed by rule 1 and G-LOG-0141 | wording ("is written") |
| reviewing analyses already derived from the corpus | **No, not by rule 4.** Whether they may be read is governed by the G-LOG-0141 extension (row 2) | wording |
| preparing an extraction method | **No.** Method is not theory content; P1 names "pre-data methods" as the permitted first deliverable | P1 L234 |
| formulating provisional hypotheses | **TODO — UNDEFINED; RESEARCH REQUIRED.** See the two readings below | — |
| conducting new corpus research | **No, not by rule 4** (it is a writing rule). New reads are governed by O1 as changed by G-LOG-0141, and by G-LOG-01xx | wording |
| producing a verdict-bearing theory | **Yes.** Also prohibited independently by O1 and the verdict-criteria entries | O1; G-LOG-0140/0145 |

**Why provisional hypotheses are undefined.** The rule does not define "substantive theory content", and two readings are possible:
- **Reading 1:** status-tagged hypotheses count as substantive content. README rule 2 speaks of "every substantive claim" carrying a status tag, which suggests tagged claims are "substantive".
- **Reading 2:** G-LOG-0141 item 4 (a later Principal act that changed O1 for the Professor) expressly provides for "research observations and hypotheses", so hypotheses so labelled are permitted.

The record does not settle which reading applies (Q-3).

**[INT] Note on the earlier assessment.**
- It cited README rule 4 as a ground for the extraction NO-GO.
- That was **imprecise**: rule 4 is a lane writing rule whose binding source (O1) the Principal has since changed for the Professor.
- The NO-GO on **verdicts** stands on O1 and the verdict-criteria entries. The NO-GO on **new reads** stands on G-LOG-01xx. Neither depends on rule 4.

---

## 3. What the clarification does and does not change

**It permits [INT, from the wording "may be read"]:** reading existing S-series material.

**It does not, by its wording, authorize:**
- new restricted corpus reads;
- S5, Run-3, the counted canary or production filter runs;
- hold-out access, scoring or predictions;
- changes to governance records or frozen documents;
- treating the unpassed safety gate as passed.

**Open point.** "S-series material" has at least three possible extents, and the record does not fix which one is meant (Q-1):

| Extent | What it would include | Conflict with a written rule? |
|---|---|---|
| **(a)** S-series workspace documents | manuscript, proposals, assessments, validation reports, Principal decision copies | **none**, apart from the exclusions in rule 1 and item 7 |
| **(b)** + Session A's derived analyses | 20-FAMILIES, research records, the discovery population | **yes**: the committed extension routes these through the hold-out filter, which is paused (D2) |
| **(c)** + the indexed corpus sources themselves | the original documents | **yes**: G-LOG-01xx §3 (uncommitted) and Decision record D2 |

**Why extent (b) is not a mere formality [FACT + INT].**
- 33 files and **45 concepts** are sealed (G-LOG-0141 item 2, committed).
- A derived analysis can carry a sealed *concept* even when it is not a sealed *file*.
- G-LOG-0028 records that using the hold-out before S5c scoring "would consume the hold-out and therefore is prohibited".
- **Consumption cannot be undone.** That is the evidence-based reason not to read (b) on an assumption.

---

## 4. The previous NO-GO: what stands, what is corrected

| Earlier statement | Now | Reason |
|---|---|---|
| NO-GO for "any further corpus reading by any track" | **Corrected (too broad).** Reading S-series workspace documents is permitted | Principal clarification; rule 1 and item 7 allow governing and methodological material and exclude only named categories |
| NO-GO for extraction, citing README rule 4 | **Corrected (wrong basis).** Rule 4 bars verdict-bearing theory; for hypotheses its scope is undefined | §2 |
| Committed log has only a G-LOG-0141 header | **Corrected (factually wrong)** | §0 |
| NO-GO for new corpus reads | **Stands** | G-LOG-01xx §3; Decision record gate "BLOCKED" |
| NO-GO for S5 / Run-3 / canary | **Stands**, and is *permanent* for the Professor by role | G-LOG-0141 items 5, 7; G-LOG-0140 |
| NO-GO for a final verdict | **Stands** | O1; G-LOG-0140, 0143, 0145 |
| GO for method preparation | **Stands** | P1; Decision record "Permitted while reviews are pending" |

**Missed by the earlier assessment [FACT]:**
- **Two different gate condition lists.**
  - G-LOG-01xx §3 condition 4 is "scope and logging confirmed".
  - The Decision record's gate condition 3 is "the rule owner has answered D2 in writing".
  - Neither list contains the other's condition. Both must be met unless the Principal says otherwise.
- **Lane status conflict.**
  - `S-SERIES-TODO.md` L233 (committed): "Lane P — professor / manuscript: **SET ASIDE by the Principal (2026-10-17: 'forget the professor')**"; Session B's files "stay untracked and untouched".
  - The same day, G-LOG-0141 (committed) grants the Professor read permission, and today's instruction addresses the Professor.
  - Also, the README *is* tracked, contrary to "stay untracked".
  - **The record does not show which came last** (Q-2).

---

## 5. GO / NO-GO (each with rule and evidence)

| Activity | Status | Rule / evidence | Changes when |
|---|---|---|---|
| **Read existing S-series material** (extent (a)) | **GO** | Principal clarification; README rule 1; G-LOG-0141 item 7. **Excluded:** S5 data, batch inputs, ledgers, witness or candidate archives, sealed lists, hold-out and seal files, and anything under `corpus-access/` that carries seal-derived lists | — |
| **Prepare the extraction methodology** | **GO** (draft, labelled PROPOSED) | P1 L234; Decision record "permitted while reviews are pending". Not the D5 recovery charter (TODO L206) | becomes binding only by a human act |
| **Review existing corpus-derived analyses** (extent (b)) | **NO-GO until Q-1 is answered** | committed G-LOG-0141 extension: "same filter and logging"; the filter is paused (D2) | the Principal states that (b) is covered **and** how hold-out protection is met (e.g. the custodian supplies a cleared list, as D2 prefers) |
| **New corpus-based theory extraction** | **NO-GO** | G-LOG-01xx §3 (uncommitted); Decision record gate BLOCKED; O1 as changed by G-LOG-0141 | D1 PASS + signed human test + D2 written answer + Principal's gate record (+ scope and logging confirmed) |
| **S5 measurement** | **NO-GO**; for the Professor, **permanently** | G-LOG-0117, 0140; G-LOG-0141 items 5, 7 | another, non-exposed session, after G3–G5 authorization, PREFLIGHT-OK and a quiet period |
| **Final theory verdict** | **NO-GO** | O1; G-LOG-0140 (criteria undefined); G-LOG-0145 (V-2 (ii) only); G-LOG-0143 | S5a/S5b data, adopted verdict criteria, H-19 decided, recovery charter (D5) |
| *Provisional hypotheses (sub-case)* | **Conditional GO**: labelled hypothesis, from lawfully read material only | G-LOG-0141 item 4 | Q-3 may narrow this |

---

## 6. Business-language summary

**What the Professor can safely read now.**
- The S-series workspace documents.
- The governance log.
- The S-series state file.
- Methodological and protocol documents.

**What the Professor must not read:** measurement data, ledgers, batch inputs, archives, seal or hold-out material.

**What the Professor can research without another decision.**
- Method design for theory extraction (as a proposal).
- Formal and synthetic work, e.g. the authority models.
- Gap and question lists.
- Labelled hypotheses drawn only from what is already lawfully in hand.

**What remains blocked, and why.**
- **New corpus reads and Session A's derived analyses:** a committed rule sends them through a hold-out filter that is paused until an independent review, a signed human test and a written answer exist.
- **Measurement:** the Professor is permanently excluded by role, because they have already read the corpus.
- **A final verdict:** there is no measurement data and no adopted criteria.

**Explicit rules versus interpretations:**

| Explicit rules | My interpretations |
|---|---|
| G-LOG-0141 items 4–7 and the extension; O1; G-LOG-0140/0143/0145; README rules 1 and 4 (wording); the G-LOG-01xx pause (explicit but uncommitted) | that rule 4 governs only writing; that hypotheses fall under item 4; that the clarification covers extent (a); that extent (b) risks hold-out consumption |

---

## 7. Questions that genuinely need the Principal

| # | Question | Options | Recommendation [REC] (uncertainty) | Governing rule | **Principal's decision** |
|---|---|---|---|---|---|
| **Q-1** | What does "S-series material" cover? | (a) workspace documents · (b) + Session A's derived analyses · (c) + the corpus sources | **(a) now.** (b) only through the hold-out filter or the custodian once the gate passes, because hold-out consumption is irreversible. *Medium uncertainty: your intent may be (b)* | G-LOG-0141 ext.; D2 | |
| **Q-2** | Is the Professor lane active? | active (record it) · set aside (TODO L233 stands) | record whichever is current, so later sessions do not inherit the conflict. *The record cannot tell which came last* | TODO L233 vs G-LOG-0141 | |
| **Q-3** | May the Professor write status-tagged provisional hypotheses before S5b? | yes, under G-LOG-0141 item 4 · no, under README rule 4 | yes, labelled "hypothesis — never proof", with no verdict language. *Low–medium uncertainty* | README L37; G-LOG-0141 item 4 | |
| **Q-4** | Which gate list applies to new reads? | G-LOG-01xx §3 · Decision record · both | **both** (the stricter union) | G-LOG-01xx §3; Decision record | |
| **Q-5** | Still pending from the earlier assessment: status of the 103 reads; committing or confirming G-LOG-01xx and 0142…0145; D1 reviewer and tester; sub-agent exposure | as in the earlier assessment §8 | unchanged: a retrospective record does not by itself satisfy a gate that was required before the reads | G-LOG-01xx; G-LOG-0144 | |

**Who may record answers [FACT].**
- Entries G-LOG-0142…0145 were "recorded by Session A at the Principal's written instruction".
- Session B does not write to the governance log (ACCESS-RECORD L12).

---

## 8. Permitted now / still requires authorization

**Permitted now:**
- reading the S-series workspace documents, governance and methodological records (excluding measurement data, ledgers, batch inputs, archives, sealed lists, seal and hold-out files);
- drafting the extraction methodology as a PROPOSED document;
- formal and synthetic research;
- labelled hypotheses from material already lawfully in hand (subject to Q-3).

**Still requires authorization:**
- reading Session A's derived analyses (Q-1 and the paused filter);
- any new corpus read (gate);
- any filter run or count-only check;
- S5, Run-3, the counted canary (never by the Professor);
- hold-out access, scoring or predictions;
- a verdict;
- any governance-log entry, commit or change to a frozen document.

**RESEARCH ONLY — PRINCIPAL DECISIONS PENDING WHERE IDENTIFIED — GOVERNANCE POLICY NOT ADOPTED — CORPUS EXTRACTION NOT AUTHORIZED.**
