# F-SERIES BOOTSTRAP REPORT

**Written:** 2026-09-25 · HEAD `e4d0a196a` · by the AI session · **no F-file content has been read.**
**Gate:** the F-Series protocol §F-12 — execution waits for the human decisions in §12 below.

## 1. Phase 1 prompt identified
**Master Protocol v3.5** (`chronological-read/prompts/20260911_0221_prompt3-optimized.md`), Phase 1 = A6 capture + A9
close, executed by the **extraction agent contract** (`…/20260911_0221_agent-extraction-contract.md`). prompt1
(`20260911_0204`) and prompt2 (`20260911_0214`) are the earlier drafts that v3.5 replaced; they are not applicable.

## 2. Phase 2 prompt identified
Two candidates, and the choice is **FD-02**:
- In v3.5, **P2 = label normalization and object families** (A10). It is corpus-level and runs after P1 over the whole
  population, never per file (v3.5 R19).
- The per-file research the commission asks for (Step E: evidence vs statements vs observations vs hypotheses) is the
  **P3b research model**, protocol **v1.7** (`…/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md`, FROZEN by
  G-LOG-0034): §1B layers, §11.1 epistemic classes, §13 research register. The file names say "phase1-continuation"
  but they are v3.5 **Phase 3** (P3b), not Phase 2.
- **Proposed:** Step E applies P3b per file; v3.5 P2 and P3 run only after the whole F population has closed Phase 1.

## 3. Versions and hashes applicable
| Artifact | sha256 | last commit |
|---|---|---|
| v3.5 master | `508b9f99edaea870e393e4c7e6737f4012266c0e30227f2f64b14da5c83730c5` | `e3e47b139` |
| extraction contract | `013770d57236c3b6c7185104f6dc6b73cdb54fa0ee1d171afecfa2aaa9714993` | `e3e47b139` |
| P3b protocol v1.7 (frozen, G-LOG-0034) | `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12` | `de52149fb` |
| batch contract revision 3 (G-LOG-0050) | `b8eef043f3b2ea4bbeb8be03fb1487787c6b1b6712455eaf15286299393809da` | `e4d0a196a` |
| paged reader `p3b_read_source.py` | `0d48829a2df7fd0cb60c3017ee8f086450c32e3bd3b029f4b06b693df5905902` | `b7e7fc856` |
| roadmap validator `validate_roadmap.py` | `c5e1a8ae4b0b25b24c61b17c4267ea9de7f73c7148ceb85b127e6da5d08c1d3e` | — |
| F population list | `cb701ae9a6322fe45b9dfbb3c2fe82697b11596746b57ece8516ba46e2d76859` | **untracked** |

The version determinations are taken from the documents themselves: the v1.7 header and G-LOG-0034 for P3b, and
G-LOG-0050 for revision 3.

## 4. Number of F entries
**1,523** (the first 27 are the renamed `philosophical_questions/` files, followed by 1,496 rows carried over from
`list_of_files_to_read.log`).

## 5. First F-ID
**F3082**, `docs/knowledgeos/brainstorming/philosophical_questions/20260923-113749_hegel_upgrade_of_knowledgeos_mathematical_theory.md`
(34,502 characters, 2 pages, sha256 `1e6ff511…48cf7`).

## 6. Last F-ID
**F3081** (`docs/knowledgeos/list_of_files_to_read.log`, the old list file itself).

## 7. Is the ordering deterministic?
**Yes.** The order is the line order of a byte-pinned file. The F-IDs are unique (checked) but **not monotonic**:
F3082…F3108, then F0109, F0181, …, ending with F3081. The order is taken from the line position, never from the F-ID
number.

## 8. Existing persistent ledger
**None.** The folder was empty. S-Series ledgers exist (`chronological-read/ledger*`, `P3B-STATE.json`, …), but they
belong to the S lane and are not used. I have not written an F state ledger yet: F-P0 registration waits for FD-01.
The dry-run numbers are below.

## 9. Reading-integrity mechanism
S5 contract revision 3, **reused**. `scripts/f_read_source.py` imports the page model and the stdout check from the
verified S reader (pinned by hash, and bytecode writing is disabled, so nothing is written under `chronological-read/`).
- **Pages:** 20,000 characters each. The reader logs each page with its hash, refuses paged output redirected to a
  file, and refuses on any sha256 drift.
- **Added for F (FD-08):** a per-page verbatim digest, checked against that page's text. The gate for `READ-COMPLETE`
  re-computes every page hash and requires expected = consumed pages, 0 missing, and all digests valid.
- **Recorded limit:** page hashes prove delivery, not attention.

## 10. Artifacts produced (by the end of the bootstrap)
| Path (in the lane folder) | Status |
|---|---|
| `prompts/20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md` | PROPOSED; includes the inheritance/change matrix (§F-3) |
| `prompts/20260925_1239_F-SERIES-AGENT-CONTRACT-v1.0.md` | PROPOSED |
| `scripts/f_common.py`, `f_register.py`, `f_read_source.py`, `f_transition.py`, `f_checks.py`, `f_audit.py`, `f_status.py` | written |
| `tests/test_f_pipeline.py` | **14/14 pass** on a synthetic fixture outside the repository |
| `F-BASELINE-GIT-STATUS.txt` | isolation baseline (290 lines, 0 under `chronological-read/`) |
| `F-GOVERNANCE-LOG.md`, `F-SESSION-LOG.md`, this report | written |
| `F-MANIFEST.jsonl`, `F-SERIES-STATE.jsonl`, `F-READ-INTEGRITY.jsonl`, `ledger/` | **after FD-01**, produced by the scripts |

What the tests demonstrate:
- **Reading:** a missing page blocks `READ-COMPLETE` but allows `READ-PARTIAL`; delivery without a digest is refused;
  a digest quote taken from the wrong page is refused; a forged page hash is refused; a redirected read is refused;
  identity drift is refused.
- **State machine:** an illegal transition is refused, and list order is enforced.
- **Evidence:** an anchor not in the file is refused; an S-id cited as evidence is refused; look-ahead evidence is
  refused; cross-file evidence must already be recorded in the earlier file's audited ledger; a hypothesis without
  falsification fields is refused.
- **Audit:** the first file requires an independent audit.
- **Restart:** `f_status.py` reports the correct restart point.

## 11. What distinguishes F-Series from S-Series
| | S-Series | F-Series |
|---|---|---|
| Population | 2,779 S-ids (`02-FILES.jsonl`), from the 2026-09-09 roadmap | 1,523 F-IDs, from `20260925_1206_list_of_files_to_read.log` |
| Identity | S-id + commit + sha256 | F-ID + sha256 (80 files and the list itself are untracked) |
| Unit of processing | batches of 40 (P1), then labels (P3b) | one file at a time, strictly in list order |
| State | P1 manifest, P3b label states, S5 runs | `F-SERIES-STATE.jsonl` (events per F-ID) and `F-READ-INTEGRITY.jsonl` |
| Evidence | S ledgers and records | F ledgers only; S-ids are rejected as evidence |
| Methodology | its own | inherited (§F-3); changes need approval |

The F population was built by path exclusion against `02-FILES.jsonl`. As a result, **175 F-files are byte-identical to
an S file under another path**. That is recorded as an identity fact only (FD-04).

## 12. Ambiguities and conflicts to resolve before execution
| Id | Issue | Proposed default | Blocks |
|---|---|---|---|
| FD-01 | approve protocol + contract v1.0 | — | everything |
| FD-02 | what "Phase 2" means (§2 above) | P3b per file; v3.5 P2/P3 later | Step E |
| FD-03 | 123 exact duplicates inside F. v3.5 R3 says record a pointer and don't re-read; the commission says don't skip redundant-looking files | inherit v3.5 R3 | first duplicate (F-P0 is unaffected) |
| FD-04 | 175 F-files that are byte-identical to S files | read in full as F evidence | — |
| FD-05 | 3 repairs (F1264, F2795, F2796) under the stricter repair rule; 15 unresolvable (truncated names in the source list: 10 with more than one candidate, 4 whose only candidate is an S path, 1 with no candidate) | accept | those entries |
| FD-06 | capacity. Total 87.5 M characters / 5,120 pages. F3026 has 1,914 pages, F3025 423, F3030 182, F3028 115, F3022 76 (machine-generated `theory-extraction/reconstruction/` data) | READ-PARTIAL with reason CAPACITY; no sampling without a decision | those entries |
| FD-07 | CLAUDE.md asks for `.claude/sessions/` and `.claude/CONTEXT.md`, but you restricted writes to this folder | log in `F-SESSION-LOG.md` inside the folder | — |
| FD-08 | per-page digest as evidence of consumption | adopt | `READ-COMPLETE` |
| FD-09 | the population list is untracked | commit it (your action; it is outside this folder) | — |
| FD-10 | the adapted rules §F-3 #7, #11, #13, #16, #22, #26 | approve | — |

**Scale note.** At about 4 characters per token, 87.5 M characters is roughly 22 M tokens of source text before any
output. The lane is therefore multi-session by construction; the state ledger, not the session, carries continuity.
