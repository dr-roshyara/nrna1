# Manuscript Architecture Proposal: S-Series Theory Manuscript

| | |
|---|---|
| **Status** | **PROPOSED, not approved.** Nothing in it is in force until the Principal decides D1–D9 (§H). |
| **Author / date** | Session B (Professor), 2026-10-17 |
| **Scope** | How the manuscript is organised, maintained, checked, reviewed and published, from the current Stage-1 Registered Report to the eventual Stage-2 results and theory. §J adds a research-optimization memo on the final goal, mathematics, logic, ML and the TODOs. |
| **Does not** | change the research methodology, grant scientific authority, amend frozen documents, or duplicate the governance log, the S-Series TODO, review records or decision lists |
| **Inspected** | the workspace as of 2026-10-17: `S-SERIES-THEORY-MANUSCRIPT.md` (1,085 lines, 15 top-level headings, 170 status tags), `SOURCE-REGISTER.md` (20 sources, X-1…X-12), `README.md`. All three are untracked and uncommitted. Tooling available: Python 3 (with YAML support) and Node; **pandoc is not installed**. |

---

## A. Executive recommendation

Adopt **modular chapter files, assembled into one integrated manuscript by a script**.
- Each chapter is its own file. It is the one place that chapter is edited, the unit a reviewer reviews, and the unit the Principal accepts.
- The full book is never edited by hand. A build step produces it, after a checker has confirmed that:
  - every source, reference and SHA-256 is valid;
  - no restricted material is cited;
  - no Stage-2 chapter contains results.
- There is therefore only ever **one** editable version of any text. The combined document is an output, like a printout.

**Cost:** one small index file, one build script and one checker, all inside the workspace.
**Benefit:** chapter-level review and acceptance, clean change history, and a single coherent book for readers and for publication.

## B. Comparison of the three architectures

| Criterion | 1. One integrated file (today) | 2. One folder per chapter | 3. Modular chapters + assembled book (**recommended**) |
|---|---|---|---|
| Maintainability | degrades as the file grows; every edit touches one large file | good per chapter; shared material (glossary, symbols, references) tends to be copied into each folder | good: one file per chapter, shared material in exactly one place |
| Scientific traceability | adequate, but a chapter's source dependencies are not visible | good per chapter; the source register tends to fragment | one source register; the per-chapter source list is generated from it |
| Mathematical proofs | numbering breaks when text moves; proofs are buried | proofs separated from the definitions they use in other chapters | stable IDs (`PROP-08-03`) that survive reordering; every reference is machine-checked |
| Independent review | all-or-nothing; an accepted part cannot be locked | per-chapter review, but no integrated consistency review | both: chapter review, then one integrated review of the assembled build |
| Cross-chapter consistency | high by proximity, but unchecked | weakest: the parts drift | checked automatically: references, terms, status tags |
| Version control | large diffs; edits collide | clean, but many folders | clean, small diffs per chapter |
| Collaboration (Professor, Researcher, reviewer) | one writer at a time | parallel but uncoordinated | parallel by chapter, coordinated by the index |
| Final publication | immediate, but Markdown only | needs an assembly method anyway | one build step, the same for review editions and the final version |

**The trade-off in business terms.** Option 1 is simplest today but becomes fragile as the work grows over months. Option 2 makes reviewing easy but lets the parts drift apart. Option 3 costs two small scripts and buys durability. Durability is the point, because the manuscript must survive from a pre-data report to final findings without anyone being able to edit a statement into a stronger claim than its evidence supports.

## C. Proposed directory tree

```
knowledgeos-s-series-theory/
├── README.md                 workspace index · writing rules · run-3 rule · Researcher request queue (REQ-n) · revision record
├── BOOK.yaml                 master index: chapter order, IDs, files, part/stage, status, contract (§D)
├── chapters/
│   ├── ch01-purpose-and-research-questions.md
│   ├── ch02-conceptual-foundations.md          the glossary lives here (the only home of term definitions)
│   ├── ch03-research-architecture.md
│   ├── ch04-evidence-model-and-provenance.md
│   ├── ch05-preregistration-and-measurement.md
│   ├── ch06-statistical-methodology.md
│   ├── ch07-verdict-criteria.md
│   ├── ch08-mathematical-formalization.md      short proofs inline, next to their statements
│   ├── ch09-falsification-and-robustness.md
│   ├── ch10-reconstruction-and-recovery.md
│   ├── ch11-review-and-reproducibility.md
│   ├── ch12-findings-and-assessment.md         Stage 2: PENDING-EVIDENCE
│   ├── ch13-integrated-theory.md               Stage 2: PENDING-EVIDENCE
│   └── ch14-references-and-traceability.md     back matter: generated reference list + appendix map
├── appendices/
│   ├── app-a-sources-and-conflicts.md          GENERATED from sources/sources.yaml (never edited)
│   ├── app-b-proofs-and-obligations.md         long proofs + the PO-n register (receives PO-01 note)
│   └── app-c-verification-log.md               what was checked, by whom, against which hash, and when
├── sources/
│   └── sources.yaml          the source register (truth): S-ids, path, branch, sha256, commit, status+GL ref, permitted use, X-n conflicts
├── reviews/                  review reports on manuscript editions + author dispositions (about the manuscript only)
├── tools/
│   ├── check.py              validation (§E.4); build refuses to run if it fails
│   ├── build.py              assembles build/ in BOOK.yaml order
│   └── studies/              reproducible pre-data computations cited by the manuscript (e.g. the PO-1 scripts)
└── build/                    GENERATED; never edited; not tracked by git (D2)
```

**Changes to the 14-chapter structure.** I'm keeping all 14 chapters. Five specific problems need fixing:
1. **Ch. 14 mixes three kinds of content.**
   - It holds references (which should be generated), appendices (which belong in their own files) and operational items: the Researcher queue, the questions to the Principal, and pointers to decisions.
   - Operational items are not scientific content. They move to `README.md`. Decision pointers stay where they matter (Ch. 7, Ch. 10).
2. **An identifier collision.** "RQ-1…3" currently names both research questions (manuscript) and requests to the Researcher (README). The requests become **`REQ-n`**.
3. **One glossary.** The glossary stays in Ch. 2, the only definition point. No second glossary is created.
4. **The stage boundary becomes explicit.**
   - **Part I, the Stage-1 Registered Report:** Ch. 1–11, Ch. 14 and the appendices.
   - **Part II, Stage 2:** Ch. 12–13.
   - Ch. 8 formalizes the *method* only. Any formalization of a *theory* belongs in Ch. 13.
5. **Pre-data computations need a home.** The PO-1 analysis already produced scripts that a reviewer must be able to re-run. `tools/studies/` keeps them with the manuscript, with their hashes, instead of in an ephemeral scratch area.

**Stable identifiers.**

| Prefix | Object | Rule |
|---|---|---|
| `CH01`…`CH14` | chapters | fixed; display numbers come from BOOK.yaml order |
| `RQ-0`…`RQ-4` | research questions | defined only in CH01 |
| `DEF-cc-nn`, `PROP-`, `LEM-`, `EQ-`, `TAB-`, `FIG-` | definitions, propositions, lemmas, equations, tables, figures | `cc` = home chapter; never renumbered or reused, even if moved |
| `CLM-cc-nn` | load-bearing claims only (claims another chapter relies on) | deliberately **not** every sentence: a per-sentence claim register would duplicate the research records |
| `PO-nn` | proof obligations | register in Appendix B |
| `S-XXX` / `X-nn` | sources / source conflicts | defined only in `sources.yaml` |
| `GL-nnnn` | governance-log entries | external; checked to exist in the log |
| `A-nn` | items the manuscript recorded but has not queued | Appendix C |
| `REQ-nn` | requests to the Researcher | README only, never in the manuscript |

**Markdown form.** An ID is written as a visible tag, for example `**Proposition 8.3** {#PROP-08-03}`, and referenced as `[PROP-08-03]`. This works without pandoc and stays compatible with it later.

## D. Chapter contracts

Each contract is stored in `BOOK.yaml` and checked by `check.py`.

**Status vocabulary:** `PLANNED` · `DRAFTED` · `IN-REVIEW` · `REVIEWED-WITH-FINDINGS` · `ACCEPTED` (by the Principal, for one edition) · `PENDING-EVIDENCE` (Stage 2, frame only). **No chapter has been reviewed.**

Two restrictions apply to **every** chapter and are not repeated per row:
- no restricted reads (O1, GL-0125; OP §22);
- frozen sources are described, never amended.

| ID | Purpose / RQs | Includes | Excludes | Required sources | Depends on | Output | Extra restriction | Done when | Status |
|---|---|---|---|---|---|---|---|---|---|
| **CH01** Purpose and research questions | the problem; RQ-0…RQ-4; outcomes; evidence status; scope | corpus facts; purpose texts; outcome options; "0 of 1,975" | any claim about theory content | S-MP, S-OP, S-P3B §1–3, S-TODO, S-GL | — | the research question, framed | the contribution statement is the Principal's | Principal confirms RQ-0; contribution stated | DRAFTED (2 Principal items open) |
| **CH02** Conceptual foundations | terms and their relations | glossary (only home); relation axes; term collisions | new theoretical terms | all governing sources | CH01 | the term set used everywhere | ARCH terms carry the X-2 caveat | every term used resolves to CH02 | DRAFTED |
| **CH03** Research architecture | governing hierarchy; stages; gates; lanes | stage table; provenance chain; critical path | measurement procedure (CH05); verdict rules (CH07) | S-GL, S-MP, S-P3B §6–8, S-OP §20, S-C4 | CH02 | framework and dependencies | Decision A not presumed | every stage status cites a GL entry | DRAFTED |
| **CH04** Evidence model and provenance | what counts as evidence; how it is traced | identity; separations; negative evidence; append-only; seal | statistics (CH06) | S-MP R6–R11, S-P3B §1B/§11/§12/§25, S-ADD §5–6 | CH02 | evidence rules | — | rules match their sources verbatim | DRAFTED |
| **CH05** Pre-registration and measurement | RQ-1, RQ-2 (design) | Freeze 1, Freeze 2, prereg_v2; frame; procedure; gate history | analysis mathematics (CH06/CH08) | S-B1, S-GL 0094/0103/0117/0137, S-P3B §9.8 | CH03, CH04 | measurement design | X-8 visible until REQ-1 is answered | REQ-1 answered or declared | DRAFTED (partly blocked on REQ-1) |
| **CH06** Statistical methodology | RQ-3 (plan) | families; Holm/BH; H1–H5; controls; H-19 scoring; limitations | results | S-PRV1, S-PRV2, S-PRT, S-PRR, S-B1, S-P3B §9E.2, S-ADD §7 | CH05 | analysis plan | no numbers except design constants | every constant cites source + GL | DRAFTED (A-13, A-14 open; PO-1 note to integrate) |
| **CH07** Verdict criteria | how RQ-0 would be decided | v1.8 as PROPOSAL; V-1…V-13 pointers; N-a/C-4 | adopting anything; repairing the packages | S-VC18, S-VCR, S-NAC4, S-NAC4P, S-GL 0140 | CH02, CH06 | a faithful account of the proposal | every paragraph tagged PROPOSAL until an adoption act | matches v1.8; no status inflation | DRAFTED (rewritten after V-decisions) |
| **CH08** Mathematical formalization | the method in symbols | populations; estimands; tests; ladder; verdict function; PO register | theory content (→ CH13) | CH05–CH07 sources; PO-1 note | CH05–CH07 | definitions; propositions with proofs; POs | every proof labelled with its standing | a third-grade reviewer has checked it | DRAFTED (PROP-08-02…05 unreviewed) |
| **CH09** Falsification and robustness | planned attack methods | A–D searches; logical attack; controls; R2; anti-projection; ML boundary | results (CH12) | S-P3B §13.10/§14.4, S-OP §15–18/§24, S-B1 §4 | CH06, CH08 | method catalogue | — | each method cites its rule | DRAFTED |
| **CH10** Reconstruction and recovery | RQ-4 (route) | S5→S5b→P7; O1; charter; O3c track; V-13 tension | any recovered theory | S-GL 0028/0125/0138–0139, S-TT1, S-TTR, S-P3B §9E | CH03, CH07 | conditional route | no substantive theory before S5b and the charter | prerequisites listed and traced | DRAFTED |
| **CH11** Review and reproducibility | how results earn trust | review grades; history; reproducibility rules; the manuscript review plan | performing a review | S-OP §7–8, S-VCR, S-PRR, S-GL 0090, S-P3B §26 | Part I | review framework | same-model checking is never called independent | plan authorized by the Principal | DRAFTED |
| **CH12** Findings and assessment | answers to RQ-1…RQ-4 and RQ-0 | result tables; outcome slot | anything not accepted and reviewed | measured, accepted S5 evidence; adopted criteria (future) | CH05–CH11 | findings | written only from accepted research packages | adopted criteria + reproduction + J4 act | PENDING-EVIDENCE |
| **CH13** Integrated theory | the theory, if one exists | core; scope; implications; theory formalization | implementation specifications (a later act) | verdict; canonicalization path (future) | CH12 | the theory | GATE; "Never FINAL" | verdict adopted + governance review | PENDING-EVIDENCE |
| **CH14** References and traceability | back matter | generated references; appendix map | operational items (→ README) | `sources.yaml` | all | reference apparatus | — | zero unresolved references | DRAFTED (to be restructured) |

## E. Source-of-truth and traceability rules

1. **One editable copy of each thing.**
   - Each chapter's text lives only in `chapters/`.
   - Each source's data lives only in `sources.yaml`.
   - Each term is defined only in CH02.
   - Decisions live only in the governance log. The TODO and the review records are **linked, never copied**.
2. **Generated files are never edited.** `build/` and Appendix A carry "GENERATED from <commit> — do not edit". `check.py` fails if a generated file differs from a fresh build.
3. **Source entries and drift.** Each entry in `sources.yaml` holds: `id, path, branch, sha256, commit, status, status_ref (GL-nnnn), permitted_use, conflicts[]`.
   - `check.py` recomputes each hash read-only through `git show <branch>:<path>`, never checking anything out.
   - A changed hash is reported as **drift**. It forces re-review of every chapter that cites the source and is **never silently updated**.
4. **Checks run before every build.**
   - (a) Every `S-`, `X-`, `GL-`, `DEF-`/`PROP-`/`LEM-`/`EQ-`/`TAB-`/`FIG-`/`CLM-`, `PO-` and `RQ-` reference resolves, and every ID is unique.
   - (b) Every status tag belongs to the agreed vocabulary.
   - (c) No Stage-2 chapter contains `[FACT]` results while it is PENDING-EVIDENCE.
   - (d) No citation points into a restricted path: a denylist of corpus files, `_batch_input*`, `ledger*`, `*.jsonl` data, hold-out/seal files and witness archives.
   - (e) No cited GL entry is missing from the log.
   - (f) Every `tools/studies/` script cited has the recorded hash.
5. **Conflicts.** X-n entries live in `sources.yaml` with a state (`RECORDED` · `QUEUED as REQ-n` · `ANSWERED-BY <ref>`). Only a cited record can change that state; the Professor never closes a conflict on its own judgement.

## F. Review and publication workflow

1. **Draft.** A chapter moves to DRAFTED when its check passes.
2. **Chapter review.**
   - The reviewer gets a packet: the chapter file, its contract (from BOOK.yaml), and a generated list of cited sources with hashes.
   - The review is against the contract **and the actual source files**.
   - The report goes to `reviews/<edition>-CHnn-<reviewer>.md`.
   - **Limit: one review and one re-review.** After that, accept with remaining findings declared, or recommend a change of approach.
3. **Integrated review.** After chapter review, the assembled build is reviewed for cross-chapter consistency only, supported by the checker's reference, term and status-tag reports.
4. **Edition.** An edition is identified by the source commit plus the SHA-256 of the build. Nothing else is stored for review.
5. **Acceptance.** The Principal accepts or declines an edition. Where that act is recorded is D5.
6. **Publication.**
   - *Stage-1 edition:* Part I + back matter, with Ch. 12–13 shown as frames.
   - *Stage-2 edition:* adds Ch. 12–13 and re-reviews every Part I chapter whose sources drifted.
   - The format (PDF/LaTeX via pandoc) is decided later (D4).

**Quiet period.** Build and check read across branches, so they count as activity. They stop with this session during the run-3 quiet period.

## G. Migration plan from the current files (only after approval)

| Step | Action | Validation |
|---|---|---|
| G0 | With authorization, commit v0.1 exactly as it is (`chore(...)`, "no story applies"), so every later step is a reviewable diff. Without the commit, the originals stay untouched beside the new layout until validation passes. | — |
| G1 | A script cuts the manuscript at its 14 chapter headings into `chapters/`, preserving bytes. | Concatenated chapters equal the original body byte for byte, apart from front-matter lines. Status-tag counts are unchanged: 44 DESIGN-FROZEN · 35 FACT · 21 DERIVED · 18 EDITORIAL · 14 PROPOSAL · 14 OPEN · 10 DESIGN-ADOPTED · 6 PLANNED · 6 PENDING · 2 DRAFT. |
| G2 | A script converts `SOURCE-REGISTER.md` to `sources.yaml`. | Appendix A, rendered back from YAML, matches the original table cell for cell. All 20 hashes are recomputed and match. |
| G3 | Manual edits: move the Ch. 14 operational items to the README; rename RQ→REQ; move the PO-1 note into Appendix B and its scripts into `tools/studies/`. | Shown as diffs; no content lost (word-level diff). |
| G4 | Insert stable IDs for the existing propositions, lemmas, facts, POs and tables. | `check.py`: 0 unresolved references, 0 duplicates. |
| G5 | Build; diff the build against v0.1. | Every difference is explained by G3–G4. |
| G6 | Remove the old root files, only after the Principal confirms. | — |

## H. Risks, unresolved questions, decisions

**Risks**

| Risk | Mitigation |
|---|---|
| A tooling bug changes content | byte-level validation at G1, G2, G5 |
| Over-engineering | two scripts and one index; no claim database; IDs only for load-bearing items |
| Hash drift as the S-Series branch advances | intended: drift is reported, never hidden; it creates re-review work |
| The manuscript lives on `election-flow-updated` but cites `s-series/s5-r7-v29-repair` | record commits, not only branch names; a renamed or deleted branch is detected by check (e) and drift |
| Same-model checking mistaken for review | the review grades of CH11 §11.2; third-grade review for acceptance |

**Unresolved questions.**
- REQ-1, REQ-2 and REQ-3 still stand (the X-8, X-1 and X-3 conflicts).
- The PO-1 note is a candidate REQ-4, held because the queue is full (§J.4).
- The document-placement resolver was not run, because the Principal designated the folder.

**Decisions for the Principal**

| # | Decision | Recommendation |
|---|---|---|
| D1 | Adopt architecture 3 | yes |
| D2 | Should the assembled build be tracked in git? | no; editions are identified by commit + hash |
| D3 | Source-register truth: `sources.yaml` (rendered to Appendix A), or the Markdown table | YAML, because the checker must read it |
| D4 | Maths notation: convert to LaTeX `$…$` now or at publication | at publication; converting now risks transcription errors in reviewed text |
| D5 | Where the acceptance of a manuscript edition is recorded: the governance log (with the Researcher as recorder), or the README revision record | the governance log, if the Principal treats it as a human act on an S-Series artifact |
| D6 | Authorize the v0.1 baseline commit (G0) | yes, one commit, "no story applies" |
| D7 | Approve the Ch. 14 restructure and the RQ→REQ rename; run the placement resolver as a check? | yes / the Principal's call |
| D8 | Approve `tools/studies/` for reproducible pre-data computations | yes |
| D9 | Should the PO-1 note displace REQ-3 in the Researcher queue? (§J.4) | yes: it concerns analysis validity; REQ-3 concerns status wording |

## I. What should happen next

1. The Principal decides D1–D9.
2. On approval of D1 (+ D6), I run G0–G6 with their validations and report the diffs and check results before removing anything.
3. In parallel, nothing in the manuscript changes, except as §J proposes after approval.
4. When the run-3 quiet period is scheduled, this session stops.

---

## J. Research-optimization memo: final goal, mathematics, logic, ML, TODOs

**Standing of this section.** These are suggestions. They decide nothing and authorize no study, read or run.

### J.1 The final goal, stated so that it can be optimized

**[EDITORIAL]** The final goal is a **decision-quality answer to RQ-0**: ROBUST, NCTF or a well-coded OPEN, reached by a process whose every step is traceable. The goal is not to find *a* theory. The governing rule is that "no coherent theory found" is a legitimate result (S-OP l.12; S-TODO l.22).

The expected value of the programme rises fastest by doing three things:
1. removing the decisions that block *any* evidence from being generated;
2. making sure that, once evidence exists, the instruments cannot produce a false ROBUST or a false NCTF;
3. making the eventual search for theory as sensitive as possible to real structure, while keeping it pre-registered.

### J.2 On "observe the corpus and derive a robust theory"

- **[FACT]** This is the Researcher's standing rule (S-TODO l.22). It operates **inside** the gates.
- O1 ("no early corpus reads", GL-0125) is in force. The theory-track protocol that would permit bounded early reads is a DRAFT, with T-0/T-1 pending (GL-0139).
- **[EDITORIAL]** Neither session may observe the corpus for theory-building until one of two things happens:
  - (a) S5/S5a/S5b produce accepted evidence and the recovery charter exists;
  - (b) the Principal adopts an early-read route by a separate act.
- Choosing (b) is a real scientific trade-off:
  - **Gain:** earlier insight, and the authors' proposals extracted faithfully.
  - **Cost:** the track "cannot reach RA-15 status D or SUPPORTED-IN-CORPUS" (S-TTR l.25), and it creates contamination channels that must be fire-walled (S-TT1 §6).
- **Recommendation:** keep O1 unless the measurement lane stalls for a long time. The early track adds hypotheses, not evidence.

### J.3 Where mathematics, logic, computation and ML add value

Every item is pre-data and corpus-free unless marked "post-data". Each pre-data study needs its own authorization (S-VC18 V-5/V-8/V-9 pattern).

| # | Contribution | Discipline | Why it matters | When |
|---|---|---|---|---|
| M-1 | **Discharge the remaining proof obligations.** PO-1 is done in part (note). Next: PO-6 (exclusivity of the outcomes) and PO-7 (information-monotonicity) as machine-checked proofs | logic, mathematics | the verdict rule is the last line of defence against a false verdict; today its key properties rest on same-model reviews | now (documents only) |
| M-2 | **An executable verdict specification.** Encode v1.8's outcome function over three-valued leaves (strong Kleene) in a solver (SAT/SMT, or exhaustive enumeration over small leaf sets) and *model-check* exclusivity, monotonicity and "OPEN can be forced but never a false ROBUST/NCTF" (the D-A, D-F, N-a, C-4 claims of §22.3) | computer logic, model checking | turns the hardest open item (N-a/C-4) from argument into a checkable property; finds counterexamples like round-12 N1 mechanically, before a human decision | now; spec only, the engine is UNBUILT (S-C4) |
| M-3 | **Synthetic planted-structure benchmark** (V-9). Generate synthetic corpora-like objects with planted theories (implication closures, orderings, cycles) and measure how often the S5a/S5b generators recover them | simulation, statistics | NCTF is only meaningful if the search is shown sensitive (N2(d)); without this, "no theory found" may only mean "not looked for well" | pre-data study (act needed) |
| M-4 | **θ_D attenuation study** (V-5). Simulate how reconstruction disagreement θ_D attenuates the hypothesis tests (errors-in-variables), giving an adequacy threshold for θ_D | measurement-error statistics | GL-0140 finding 4: θ_D has no threshold; V-5 needs it before S5 data | pre-data study (act needed) |
| M-5 | **Agent-versus-source separation** (the CA-8 "C1 model"). A latent-class / Dawid–Skene rater model over replicate agent readings, plus the human subsample, estimating agent-specific bias separately from source content | statistical ML | CA-8 today fails H1 by construction (D0); a rater model is the standard tool to show a reading is not an artifact of the reader | design pre-data; fit post-data |
| M-6 | **Formal concept analysis (FCA)** of the label × dimension matrix: implication bases (Duquenne–Guigues) and concept lattices as a *structure-candidate generator* for H5-type closure structure; Mokken scaling as the established framework behind Loevinger's H | order theory, lattice theory | gives the S5b corpus pass a principled, registered generator for "closure/implication" candidates instead of ad-hoc pattern finding; the weakest-structure rule of §13.11 maps onto FCA naturally | registered pre-data; run post-data |
| M-7 | **ML as candidate generator only** (S-OP §18; S-B1 §7). Embedding or TF-IDF similarity and graph-community detection propose comparison candidates for S5a, registered in the §9E.1 generator register with their defining property, against lexical, graph and frequency baselines. Candidates whose structure *is* the similarity are DESCRIPTIVE and never count as discovery | ML | raises recall of cross-object candidates without letting ML decide anything; respects SOURCE → ML → THEORY = never | post-data only |
| M-8 | **Exact MC tests** (from PO-1): Besag–Clifford parallel scheme for H3/H5 | statistics | removes a declared limitation; only if a new prereg version is ever opened | conditional |
| M-9 | **Property-based tests of the verdict rule and instruments** (fixtures independent of the implementation, S-OP §7) | software engineering, logic | the record shows four or five consecutive drafting rounds in which additions introduced defects (S-VCR l.768); mechanical checking breaks that pattern | with M-2 |

**Priority by information value (Professor's recommendation).**
1. **M-2 + M-1 first.** They attack the actual bottleneck (N-a/C-4 and V-1 readiness) and need neither data nor corpus.
2. **M-4 and M-3 next.** They are prerequisites of adoptable criteria (V-5, V-9).
3. **M-5 and M-6 designed pre-data.**
4. **M-7 strictly post-data.**

### J.4 TODOs: the Professor lane (this workspace only; the S-Series TODO is not edited)

| # | TODO | Blocked by | State |
|---|---|---|---|
| P-1 | Migrate to the approved architecture (G0–G6) | D1, D6 | waiting |
| P-2 | Integrate the PO-1 note into CH06 (L-S3/L-S5 text) and CH08 (PO-1 standing) | D1 (placement); REQ queue | waiting |
| P-3 | Write a formal specification of the v1.8 verdict function (M-2) as a manuscript appendix draft; propose model-checking as a Researcher study | Principal's go-ahead (documents only) | proposed |
| P-4 | Third-grade review of the Stage-1 edition | Principal names a reviewer (TODO P3) | waiting |
| P-5 | Rewrite CH07 once Q-a and V-decisions are taken | Principal | waiting |
| P-6 | Request queue: REQ-1 (X-8), REQ-2 (X-1), REQ-3 (X-3) open; **REQ-4 candidate** = review of the PO-1 note (proposed to displace REQ-3, D9) | queue limit 3 | waiting |

**Suggested additions to the S-Series TODO**, for the Researcher to consider (not written by the Professor):
- a "PO-1: H3/H5 star-MCMC validity" limitation entry;
- M-2 as a pre-data study candidate in Lane V;
- M-3, M-4 and M-6 as study designs attached to V-9, V-5 and the S5b generator register.

**Traceability.** Inspected files and hashes: `SOURCE-REGISTER.md`. PO-1 analysis: `PO-01-NULL-MODEL-VALIDITY-NOTE.md`. Governing records: GL-0125, GL-0137, GL-0139, GL-0140; S-VC18 §22.3; S-TODO l.22.
