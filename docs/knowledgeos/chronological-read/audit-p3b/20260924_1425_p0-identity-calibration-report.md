# P0 identity calibration: census provenance (calibrations A–D)

**Status:** calibration evidence (read-only). **Not a protocol amendment.** v1.6.5 = **NOT DECIDED**. The draft
`prompts/20260924_1407_p3b-v1.6.5-census-provenance-proposal.md` is **superseded as evidence**: its V\*/R1/R2/B grades
predate the P0-hash finding, and §C below replaces them. That file remains a non-authoritative draft.
**Gate:** S0 ✅ → S1 ✅ → S2 ✅ → H-04 ✅ → S3 implementation ✅ → S3 dry run ✅ → provenance calibration ✅ (this
report, pending human review) → **S3 real run ❌ blocked**.
**Evidence base:** `audit-p3b/CENSUS-PROVENANCE-AUDIT.jsonl` (body `output_sha256`
`701c7e6145d4581c3434b7bf862c79d97c6976829f07ef463e665b8e5ae12d9f`), produced by
`scripts/p3b_census_provenance_audit.py` (blob `ab082e6f`) at HEAD `d9bebf9c8`, plus direct `git cat-file`, `git log`
and `os.stat` reads recorded below. Identity reference = the P0 sha256 in `00-ROADMAP-VALIDATED.jsonl`.
**Claim strength:** every statement below is a measurement unless it is marked *inference*.

---

## A. The 2,665 files present at their recorded commit

**2,660 of 2,665 match P0's sha256 at the recorded commit. 5 do not. All 5 are explained, none is left unresolved.**

| Source | Category | Explanation (measured) |
|---|---|---|
| S2804 `gap-update-2026-09-02/README.md` | **stale commit reference** | The recorded commit `39fdef05…a35f` (2026-09-11) holds version `1b538c25`, unchanged since `70fee73c8` (2026-09-06 08:02 +02:00). P0's hash `bc727fe8` is the **next** version, first committed in `e3e47b139` (2026-09-12), and it is also HEAD and the working tree. P0 mtime = P1 mtime = 2026-09-06 07:41 UTC, which is **after** the old commit (06:02 UTC) and **before** the recorded commit. So the file was edited after `70fee73c8` and left uncommitted; P0 hashed that working-tree version while recording the snapshot commit; P1 was dispatched with the snapshot commit and recorded it. *(Mechanism wording corrected 14:39.)* |
| S2880 `gap-discovery/INDEX.md` | **stale commit reference** | Same mechanism: the recorded commit holds `c58a1f86` (7,889 bytes, from `70fee73c8`); P0's hash `32c56653` (22,483 bytes) was first committed in `e3e47b139`. P0 mtime = P1 mtime = 2026-09-07 14:52 UTC. |
| S2344 `04-operator-contracts.md` | **P0 recording defect** | See §B: P0's metadata tuple is cyclically shifted within the trio. |
| S2345 `05-operator-capability-matrix.md` | **P0 recording defect** | as above |
| S2346 `06-composition-rules.md` | **P0 recording defect** | as above |

**Does P1's evidence side with P0's hash or with the recorded commit? (S2804, S2880)**

| Source | Diff between the recorded-commit version and the P0-hash version | P1 evidence | Verdict |
|---|---|---|---|
| S2880 | the P0 version adds 156 lines: the `gita-candidates/`, `carrier-options/`, `oq4-witness/`, glyph-register, OQ-1 brief, theory-inventory and registry-extension sections | 10 P1 rows. **7 of them (rows 4–10)** summarize exactly those sections ("gita-candidates package", "carrier-options paper", "OQ-4 witness experiment", "glyph-register … lane-conflict", "OQ-1 carrier decision brief", "theory-inventory-assessment", "registry-extension-proposal"). Counts in the recorded-commit version vs the P0 version: `gita` 0/3 · `carrier-options` 0/2 · `witness` 0/5 · `glyph` 0/7 · `OQ-1` 0/5 · `theory-inventory` 0/2 · `registry-extension` 0/2 | **P1 read the P0-hash version, not the recorded commit.** Converges with P0 |
| S2804 | the P0 version adds one table row (item 11, `Closure(𝒦₉)` independent verification) | 8 P1 rows. None mentions item 11 (`K9`, `Closure`, `15 of 15` absent) | **P1 evidence cannot discriminate.** P0 hash plus mtime agreement are the only identity evidence; no contrary evidence |

**Consequence for v1.6.4 as frozen (a fact, not a decision):** A.4 says "read at its recorded `commit`". For S2880 this
would search a version that, by P1's own rows, is **not** the one P1 read. For S2804 the two versions differ by one
table row. The recorded commit is therefore not a safe anchor even when the file exists there.

**None of the 5 is a path/content mismatch in the corpus, duplicate or renamed content, or a genuine unresolved identity.**
The categories used: stale commit reference ×2, P0 recording defect ×3.

## B. The S2344–S2346 cycle, fully reconstructed

All three are in P1 batch B0056, roadmap lines 2344–2346 at 22:40, P0 `dup = UNIQUE`, `resolve = RESOLVED`,
`repair_evidence = null`.

| Source | P0 path | P0 SHA | SHA of the path at the recorded commit (= HEAD = working tree) | P1 summary describes | P1 contribution evidence describes |
|---|---|---|---|---|---|
| S2344 | `…/04-operator-contracts.md` (heading "04 — Operator Contracts (Part V)") | `eb917d35` = the content of **05** | `dcc33f3f` (P0 lists it under **S2346**) | "the executed anti-circularity operator-contract model: operators defined purely by their declared …" → **04** | 5 rows: operator identity by declared atoms; the shared atoms `difference-decision`/`norm-comparison`; structural concerns as invariants; DetectGap holding no exclusive atom; the per-operator contract table → **04** |
| S2345 | `…/05-operator-capability-matrix.md` (heading "05 — Operator × Capability Matrix (Part IV)") | `86b6eeac` = the content of **06** | `eb917d35` (P0 lists it under **S2344**) | "the executed operator-times-capability matrix (computed by actual leave-one-out removal …)" → **05** | 2 rows: Discriminate/DetectGap all-zero rows under leave-one-out; C5/C8 all-zero columns, irreducible only pairwise → **05** |
| S2346 | `…/06-composition-rules.md` (heading "06 — Composition Rules and `Reach(S)`") | `dcc33f3f` = the content of **04** | `86b6eeac` (P0 lists it under **S2345**) | "the executed formal composition-rules and Reach(S) definition: a typed carrier-derivation table …" → **06** | 5 rows: carrier kind by input-kind multiset; Claim only via entailment; Verdict needs Evidence; Reach(S) as least fixpoint; engine contains only operator applications → **06** |

**Discriminating-term counts in each file's content** (content at its own path):

| Term (from P1 rows) | in 04 | in 05 | in 06 |
|---|--:|--:|--:|
| `all-zero` (S2345 row) | 0 | **2** | 0 |
| `leave-one-out` (S2345 row) | 0 | **3** | 0 |
| `C5` / `C8` (S2345 row) | 0 / 0 | **4 / 4** | 0 / 0 |
| `Reach(S)` (S2346 row) | 0 | 0 | **5** |
| `Claim` (S2346 row) | 0 | 0 | **8** |
| `declared` (S2344 row) | **4** | 1 | 0 |

*(This is a check that each row's distinctive terms occur in one file, not anchor matching, which was rejected as an
identity method.)*

**The mtime field is shifted with the hash.**

| Source | real mtime of the path (working tree = P1-recorded) | P0 mtime | P0 (sha, mtime) tuple equals the real tuple of |
|---|---|---|---|
| S2344 | 1788295210 | 1788295244 | **S2345** (`eb917d35`, …244) |
| S2345 | 1788295244 | 1788295210 | **S2346** (`86b6eeac`, …210) |
| S2346 | 1788295210 | 1788295210 | **S2344** (`dcc33f3f`, …210) |

**Conclusion:** the path, the content at the path in every git location, P1's summary, P1's contribution rows and
P1's recorded mtime **converge independently**. P0's **(sha256, file_mtime)** pair is attached to the wrong row: each
row carries its successor's pair, and the last carries the first's. This is a **P0 metadata-alignment defect**, not
content ambiguity. *Inference:* P0 produced these three rows with the path column misaligned against the hash/mtime
columns. The mechanism cannot be established from the artifacts.

**Result:** content identity for S2344–S2346 = the content at the recorded commit. It is established by
path-and-commit, P1 summary and P1 rows (three independent lines); P0's hash is **disqualified** for these three rows.
So P0's hash is strong evidence, but **not infallible**, and cannot be made the sole authority.

## C. The 102 files: per-file provenance chain

The chain asked for: P0 sha256 → the exact blob or copy with that hash → correspondence with P1's processing evidence
→ provenance status.

*(Table and bullets revised at 14:39. See the correction log at the end.)*

| Subtype | Files | Copy matching P0's hash | Working-tree stasis (current bytes = P0 hash **and** current mtime = P0 mtime) | P1 rows | Proposed provenance status *(vocabulary for decision, not adopted)* |
|---|--:|---|---|--:|---|
| RECORDED-COMMIT-STRING-CORRUPT (S1513) | 1 | resolved commit `39fdef05…a35f` (unique 36-char prefix of the recorded `…a591`), HEAD, working tree | yes | >0 | see the design derivation |
| COMMITTED-AFTER-RECORDED-COMMIT, still in working tree | 80 | the single committed version (`e3e47b139`), HEAD, working tree | 79 yes; 1 bytes-only (S2809: same bytes, mtime touched 2026-09-11 21:28, before P1's read) | all >0 | see the design derivation |
| COMMITTED-AFTER-RECORDED-COMMIT, deleted from the working tree | 13 | the single committed version (`e3e47b139`), HEAD | **not observable**: the file is an uncommitted working-tree deletion (S2870, S2888–S2897, S2920, S2921; B0069/B0070) | all >0 | see the design derivation |
| UNTRACKED-WORKING-TREE-ONLY (.pyc) | 8 | working tree only | yes (8/8) | 0 | see §D |

- **Every one of the 102 has an exact byte copy whose sha256 equals P0's hash.** None lacks one. The 94 text files
  carry 914 P1 rows.
- **P1's `file_mtime` is not independent evidence (correction).** v3.5 §A6 dispatched each P1 agent with
  `[source_id | path | file_mtime | mtime_block]`, taken from the P0 roadmap. The manifest mtimes
  (`01-BATCH-MANIFEST.jsonl`) equal P0's in 2,779/2,779 records. P1's recorded value equals the manifest in 2,596 of
  2,767 CONTENT records; the other 171 are transcription defects (null, date-only, timezone-shifted, row-shifted, e.g.
  S0393–S0399), plus S2344/S2345, where P1 wrote the true mtime instead of the shifted one. **So "P0 mtime = P1 mtime"
  shows only that P1 copied its input, not what it read.**
- **The independent evidence is working-tree stasis.** If a file's current bytes hash to P0's value and its current
  mtime equals the mtime P0 measured, the file has not been written since P0 measured it. Every read after P0,
  including P1's, therefore saw those bytes. The exception would be a deliberate mtime reset: `git checkout` sets the
  current time, not an old one. Stasis holds for **all 2,660 C1 files**, 79 + 1 + 2 + 8 of the recovered files, and
  (at the path, with the corrected pairing) for the trio. It is **not observable for the 13 deleted files**.
- **Why the recorded commit fails (corrected mechanism):** `commit` is not "HEAD at read time". It is the commit passed
  in the dispatch payload: the P0 corpus snapshot `39fdef05…a35f` (`00-CORPUS-SNAPSHOT.txt`) for 2,738 records,
  `e023b655` (2026-09-13) for B0020's 40, and a typo for S1513. **P0 hashed the working tree**, which at P0 time held
  files not yet in the snapshot (the 93) and edits made after it (S2804, S2880). The 92 files and the roadmap itself
  were first committed together in `e3e47b139` (2026-09-12 17:33), **before** P1 read those batches: ledger file
  mtimes are 2026-09-14 to 09-16, and all ledgers were committed in `125cfe837` on 09-22.
- **What the evidence does not prove:** that P1 read the identical bytes, because no P1 read-time hash exists. The
  strongest statement, for the 91 stasis files: *"byte-identical to what P0 hashed, and unwritten since P0 measured
  it"*. For the 13: *"byte-identical to what P0 hashed and to the only committed version; whether the file changed
  between 09-12 17:33 and P1's read is unobservable."*

**How this replaces the draft's grades:** the V\*/R1/R2 split is superseded. The meaningful split is between files
with **observed stasis** and the **13 without it**.

## D. The 8 .pyc files (kept separate)

**Correction:** earlier in this session I said "20 .pyc in the corpus". The census (`status = CONTENT`) holds **8**.
The other 12 .pyc records are `FIREWALL-LIMITED`, which A.4 already skips.

| Question | Answer (measured) |
|---|---|
| P0 hash identity | **8/8**: the working-tree bytes match P0's sha256. Never committed. Working-tree stasis holds (current mtime = P0 mtime, 8/8). P1's mtime is copied from P0, so it adds nothing |
| Internal consistency | all timestamp-based (PEP 552 flags 0). **7/8** have their source .py in the census, with header source size = size of the P0-verified source. S1869 (`kos279`) has no source .py in the census |
| Did P1 classify them as binary? | **yes, 8/8.** Summaries: "compiled … bytecode cache", "binary, not readable as source text"; `contribution_assessment`, e.g. "Build artifact only. No new information" |
| Did P1 extract zero rows? | **yes: 0 rows in 03-CONTRIBUTIONS.jsonl for all 8** |
| Was semantic evidence derived from them? | **No semantic evidence, but 3 metadata references:** S1757 → `state-congruence-criterion`, S1864 and S1869 → `step281-missingness-repair-inquiry-register-selection` (via P1 `objects_touched`). They appear only as ids in `files_touching` of those reconciliation objects in `20-FAMILIES/_derived.json`. They are not in any row, pair basis or P3B-TIERS record. `state-congruence-criterion` **is** in AFFECTED (via pairs RP0228, RP1148, RP1670), so S1757's id will appear in that label's bundle as a `files_touching` entry |
| Is the frozen P3b search population supposed to include binary files? | **The frozen text includes them by definition and never considers the case.** §3.6: census = "every `CONTENT` record in `02-FILES.jsonl`"; §11.4 and A.4: "the raw text of every `CONTENT` file … read at its recorded `commit`". Neither v1.6.4 nor v3.5 mentions binary, `.pyc` or bytecode. **It is an unaddressed case, not an answered one.** Whether to exclude them is a decision for the human; this report does not make it |

Two further facts that bear on that decision without making it: (1) for these 8 files, A.4's "read at its recorded
commit" is impossible, since they were never committed; (2) a lexical search over bytecode could match only embedded
identifier and string constants from the source .py, which for 7/8 are already in the census as verified text.

## Summary of calibrated findings

| Finding | Evidence strength |
|---|---|
| P0's sha256 is correct for **2,660 + 104 = 2,764 of 2,767** CONTENT files | measured |
| P0's sha256 is **wrong for 3** (S2344–S2346): the metadata tuple is cyclically shifted, and path, content, P1 summary and P1 rows converge against it | measured (the mechanism is inference) |
| The recorded `commit` does not hold P0's bytes for **97**: 1 typo; 93 files not yet in the P0 snapshot; 2 files edited after it; 8 untracked binaries. For S2880 this is contradicted by P1's own rows | measured |
| **No CONTENT file lacks an identifiable content copy** (C4 = 0) | measured |
| P1 read the P0-identified bytes | **supported, not proven**: working-tree stasis for 2,660 C1 + 91 recovered + trio; for S2880 also discriminating rows. **Unobservable for 13** (deleted from the working tree). P1's mtime is **not** evidence (copied from P0) |
| Neither P0 hash nor recorded commit alone is a sufficient identity authority. Only their cross-check with P1's evidence resolved every case | conclusion from the above |

## Open for the human (decisions, not taken here)

1. Whether this report is accepted as the calibration record (and whether it and the audit script/output are committed).
2. Whether and how an amendment (v1.6.5 or other) should anchor identity. Candidates visible in the evidence: P0
   hash with a P1-evidence cross-check; a per-file resolved blob recorded by S3. **Not drafted.**
3. The treatment of the 8 binary CONTENT files in the lexical search population.
4. Whether the observed P1 mtime row-shift (S0393–S0399, …) needs any action. It affects no content identity; C1
   content matched P0 there.

**Nothing changed:** frozen v1.6.4, P3a artifacts, S0–S2 outputs, the S3 script, the governance log. No S3 run.

## Correction log

- **14:39: two claims corrected after checking the P1 dispatch inputs.** (1) "P0 mtime = P1 mtime (102/102) is a
  strong signal": **withdrawn.** P1 received `file_mtime` from P0's manifest (v3.5 §A6; manifest = P0 in 2,779/2,779),
  so the agreement is transcription. It is replaced by working-tree stasis (§C). (2) "P1 wrote the current HEAD as
  `commit`; the files were read uncommitted and committed later": **withdrawn.** `commit` is the dispatched snapshot
  constant (`00-CORPUS-SNAPSHOT.txt`), P0 hashed the working tree, and the 92 files were committed on 2026-09-12,
  before P1 read them (§C). The reviewer's chain link "P0 mtime → P1 mtime" rests on claim (1) and falls with it.
- **14:47: the 13 "deleted" files were renamed, not deleted** (human report, verified). All 13 are in the working tree
  under new timestamped names, with bytes = P0 hash and mtime = P0 mtime, so stasis is observed. Every
  "STASIS-UNOBSERVABLE / not observable (13)" statement above is superseded: stasis is observed for 2,767/2,767.
