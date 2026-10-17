# S-Series Theory Manuscript Workspace (Session B: Professor)

## 1. What this folder is

This is the manuscript workspace for the S-Series KnowledgeOS theory. It is kept separate from the PublicDigit application code and from the research records.

| File | Role |
|---|---|
| `S-SERIES-THEORY-MANUSCRIPT.md` | The **one integrated manuscript**, Chapters 1–14. It is currently a Stage-1 Registered Report: pre-data, with no results. |
| `SOURCE-REGISTER.md` | Every source the manuscript cites: path, branch, SHA-256, commit and governance status, plus the recorded source conflicts X-1…X-12. |
| `README.md` | This index: the operating rules, the queue of requests to the Researcher, and the revision record. |
| `validation_reports/` | **All validation reports go here** (Principal's instruction, 2026-10-17). Naming: `YYYYMMDD_HHMM_<topic>.md`. Existing reports are never overwritten; a correction is a new report that cites the one it corrects. First entry: `20261017_1748_validation_report.md` (saved by the Principal; the v0.4 validation report). |
| `ARCHITECTURE-PROPOSAL.md` | **PROPOSED, not approved.** Covers the manuscript architecture, chapter contracts, traceability, review and migration, plus a research-optimization memo (§J). Decisions D1–D9 are pending. |
| `PO-01-NULL-MODEL-VALIDITY-NOTE.md` | Mathematical and computational note on proof obligation PO-1, the validity of the frozen null generators. DERIVED and unreviewed; synthetic computations only. |

**Deliberately absent.** This folder has no human-decision file, open-question file or claim register. Those already exist:
- human acts: `docs/knowledgeos/chronological-read/P3B-GOVERNANCE-LOG.md`;
- working state and open decision codes: `.claude/S-SERIES-TODO.md`;
- open procedural items: the verdict-criteria review record and v1.8 §22.3.

The manuscript points to them and never duplicates them.

## 2. Roles

| Role | Holder | In this folder |
|---|---|---|
| Human Principal | the project owner | decides; nothing here is a decision |
| Researcher | Session A | investigates; supplies research packages; answers requests (§5) |
| Professor | Session B | writes and integrates the manuscript from authorized, verified sources |
| Independent review | to be named by the Principal | challenges the Stage-1 report (manuscript Ch. 11.5) |

## 3. Writing rules (binding on this lane)

1. **Sources.** The manuscript is written only from authorized governing and methodological sources. It reads **no** corpus content, S5 data, batch inputs, ledgers, witness or candidate archives, or hold-out and seal files (O1, G-LOG-0125; OP §22).
2. **Status tags.** Every substantive claim carries a status tag, and the tag never exceeds the status its source gives the claim. Repetition never raises it.
3. **No silent fixes.** Frozen artifacts are described, never amended. A conflict between sources is recorded, not resolved.
4. **No evidence-free theory.** No substantive theory content is written before S5b and the recovery charter. Corpus-dependent chapters stay PENDING.
5. **Git discipline.** No commit, push or merge to `main` without the Principal's separate authorization. No edits outside this folder.

## 4. Concurrency rule (run-3 quiet period)

Run-3 (G-LOG-0117) requires that **every** Claude session, IDE, git client and loop on PROD and its worktrees be stopped before launch.

**When the Principal schedules the quiet period, this session stops all work and is closed.** It resumes only after the launch activity has ended and the Principal permits it.

As of 2026-10-17, no quiet period is in force: run-3 is approved at G2 but not launched, and no `PREFLIGHT-OK` is recorded.

## 5. Requests to the Researcher (numbered queue; at most 3 open)

| # | Opened | Request | Why it blocks or matters | Status |
|---|---|---|---|---|
| **RQ-1** | 2026-10-17 | Which governance record discloses that prereg_v2 (G-LOG-0118…0137) re-registered parts of Freeze 1 (family, B, H2 wording), given Freeze 1's clause "Nothing learned later … may change any of these" (B1 §8)? If no such record exists, should one be proposed? | Manuscript Ch. 5.1 cannot describe the pre-registration's integrity without it (X-8) | OPEN |
| **RQ-2** | 2026-10-17 | The governance log differs between branches: PROD `election-flow-updated` reaches G-LOG-0140, while worktree `s-series/s5-r7-v29-repair` stops at G-LOG-0110. Which copy is authoritative, and is the worktree copy stale by design? | Every citation of a human act depends on it (X-1) | OPEN |
| **RQ-3** | 2026-10-17 | RI-1: G-LOG-0104 l.1616 says "reopened on its condition 1", but the TODO says DEFERRED. Which is current? | Affects the limitation statements in Ch. 5–6 (X-3) | OPEN |

Further items are recorded in the manuscript (Appendix A: A-13, A-14) and will be queued only when a slot is free.

**Waiting candidate (REQ-4):** review of `PO-01-NULL-MODEL-VALIDITY-NOTE.md`. The note finds that the confirmatory tests (H1, H2, H4) are exactly valid. The exploratory H3 and H5 tests use a "star" MCMC scheme, which is not exact in general but is conservative in synthetic checks. The H3 swap chain is reducible. ARCHITECTURE-PROPOSAL D9 proposes letting it displace RQ-3.

*Naming note:* the proposal renames these requests RQ-n → REQ-n, because "RQ-n" also names the research questions in the manuscript. The rename waits for approval (D7).

## 6. Revision record

| Version | Date | Change | Basis |
|---|---|---|---|
| v0.1 | 2026-10-17 | First integrated draft of Chapters 1–14. Chapters 1–7 are written from verified sources. Chapter 8 formalizes the method, with Propositions 8.2–8.5 derived and proof obligations PO-1…PO-10 listed. Chapters 9–11 are written as method. Chapters 12–13 are frames only (PENDING). Source register created; 12 source conflicts recorded; 3 requests queued. | The Principal's instruction to Session B (2026-10-17): Stage-1 Registered Report first; workspace on `election-flow-updated`; no commit |

| — | 2026-10-17 | The manuscript itself is **unchanged** (held until the architecture is approved). Added `ARCHITECTURE-PROPOSAL.md` (proposed). Added the PO-1 note: Proposition 1 (H1, H2, H4 exact), Proposition 2 (the star scheme is not valid in general; computed counterexample), H3 reducibility (exhaustive check for n ≤ 4), and a synthetic size study of the frozen H3 and H5 tests. | The Principal's instruction 2026-10-17: write the proposal; continue as researcher, mathematician, statistician and DDD/logic/ML expert. No corpus read (O1). |

| v0.2 | 2026-10-17 | **Manuscript revised in place** under the Principal's instruction to write the optimized theory in this directory. Changes: Ch. 1.3 (process vs content theory); Ch. 5.1 (confirmatory parameter gap; two readings); Ch. 6.2/6.3/6.8 (B dependence of NR-P; H3/H5 validity; L-S9…L-S13); Ch. 7.6 (competing positions on Q-a); Ch. 8.10 (PO table) and new **8.12–8.16** (Prop. 8.6–8.9, Cor. 8.9a, Fact 8.3, Prop. 8.8, Lemma 8.10, Thm. 8.11, Props. 8.12–8.14, the process-theory candidate T_P); Ch. 13.4 (scope); Appendix A (A-13…A-17). Source register: S-CTR29, S-REC, S-KP, S-HO, S-PKA; X-13 | Session A handoff (S-HO), Kind-P note r2 (S-KP); Session B THEORETICAL-ASSESSMENT + Addendum; same-model, unreviewed |

| v0.3 | 2026-10-17 | **Part III, Ch. 15** added: the corpus-derived candidate kernel theory. It covers the ten corpus statements S-1…S-10 with their classes; the Nerode minimal-sufficient-state core (Def. 15.1–Prop. 15.4) and its consequences, including that K is determined by (𝓞, 𝔇), so the corpus's K-first ordering is inverted; the stochastic side (Φ needs a declared loss); a ledger; and the next reading. Corpus access: `corpus-access/` (filter, allowed list: 1,492 allowed / 18 withheld; READ-LOG) | G-LOG-0141 (Principal-recorded); 4 corpus documents read; S-Series only (F-Series kept separate) |

| v0.4 | 2026-10-17 | Ch. 15.7 (the corpus theory T-1…T-8 from Session A's families) and 15.8 (unification; Refinement 15.5, realizable sufficiency; correction of consequence 3). Status-report corrections applied: contents line for Ch. 15, S-10 conditional restored, Prop. 15.4 qualifier. Corpus access extended: `corpus_filter.py`, CORPUS-ALLOWED (2,734), FAMILIES-ALLOWED (2,457 / 48 withheld), FAMILIES-PROFILE; the 30 names displayed earlier checked (0 hold-out) | G-LOG-0141 + extension (Principal-recorded, l.2229–2230) |

**Review status.** v0.1 has had **no review**. Its own consistency checks are not independent validation. The planned single review and single re-review (manuscript Ch. 11.5) need the Principal's authorization.
