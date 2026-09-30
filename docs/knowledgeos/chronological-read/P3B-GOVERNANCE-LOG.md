# P3B GOVERNANCE LOG

Append-only record of human decisions and acts under the P3b operating protocol (§17, §26). Entries are never edited or
removed. A later decision that changes an earlier one is a new entry that cites it.

---

## G-LOG-0001 — FREEZE of the P3b operating protocol (core) v1.6.4

| Field | Value |
|---|---|
| Decision ids | **H-07** (the methodology freeze admits this annex) and **H-01** (approve the annex), both **for the core protocol v1.6.4 only** |
| Decider | the human project owner |
| Instruction | "approve the freeze if it confirms" (2026-09-24), a conditional approval given after the human asked for a final confirm-only pass on v1.6.4 |
| Condition | the final confirm-only pass returns CONFIRMED |
| Condition met | **yes.** The final confirm-only pass (an independent agent with a fresh context, read-only) returned `VERDICT: CONFIRMED`: the four defects D1–D4 of the v1.6.3 edits are resolved, the diff contains only the intended changes, the checker's rule 8 matches A.9 rule 8, and no contradiction was introduced |
| Recorded | 2026-09-24T10:45:19Z, by the AI session acting on the instruction above |

**Frozen artifact**

| Field | Value |
|---|---|
| File | `docs/knowledgeos/chronological-read/prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md` |
| sha256 | `08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06` (identical to the file verified by the final pass) |
| git blob | `92ef2c8333e6ae03f3fe1d132e3a0cf3ad20ff2f` |
| Lines / headings | 3,066 / 133; sections 1–41; D-01…D-73; H-01…H-19 (22 ids incl. H-11a–d); OMQ-01…OMQ-22; G-01…G-13 |

**Freeze integrity check (§26 item 6, Appendix A.9)**

| Field | Value |
|---|---|
| Script | `docs/knowledgeos/chronological-read/scripts/p3b_freeze_check.py` |
| Script sha256 / git blob | `91f224769662e40cd717341a468225bebdb9f6f2ab2dc79d6525e52fd3c3d8ef` / `bcc32b80d73c6982b9bf089c0760926d292cd749` |
| Interpreter | Python 3.13.2 |
| Result | **PASS** on all 8 rules |

Checker output (verbatim):
```
file 20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md | own version 1.6.4 | headings 133 | sha256 08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06
  1 backrefs/placeholders: []
  2 stale version: []
  3 unresolved §: []
  4 ids: {'H': {'defined': 22, 'undefined_refs': [], 'duplicates': []}, 'OMQ': {'defined': 22, 'undefined_refs': [], 'duplicates': []}, 'D': {'defined': 73, 'undefined_refs': [], 'duplicates': []}, 'G': {'defined': 13, 'undefined_refs': [], 'duplicates': []}}
  5 S-steps undefined: []
  6 P3B artifacts missing from §24: []
  7 top-level numbering: {'gaps': [], 'duplicates': [], 'range': (1, 41)}
  8 table rows not ending with |: []
FREEZE INTEGRITY CHECK: PASS
```

**Review chain leading to this freeze** (all in `docs/knowledgeos/chronological-read/prompts/` or in the session log
`.claude/sessions/2026-09-24.md`):
- v1.3 adversarial audit (`20260924_1135_…-v1.3-adversarial-audit.md`) → v1.4;
- v1.4 review (`20260924_1157_…-v1.4-adversarial-review.md`) → v1.5;
- v1.5 review (`20260924_1210_…-v1.5-adversarial-review.md`) → v1.6 (declared the final correction round);
- restricted review of v1.6 → v1.6.1 → targeted verification → v1.6.2 (core split from H-19) → verification →
  v1.6.3 → confirm-only check → v1.6.4 → **final confirm-only pass: CONFIRMED**.

**Scope and meaning of this freeze**
1. The **core** protocol is frozen. The file is not edited, and its own "PROPOSED" status line is deliberately left
   unchanged, because any edit would change the frozen hash. **This log entry is the authoritative freeze record.**
2. Changes from now on follow §26 only: a new versioned file, a human decision, a recorded reason. Per §26 item 7, only
   a threat to validity, reproducibility or the research question reopens the protocol. Operational friction goes to
   the runbook or implementation layer.
3. **H-19 (sealed hold-out) is adopted but not specified in the core.** Its addendum must be written, independently
   verified and approved in a separate entry of this log **before S3b**. S3b, S4 and all later steps are blocked until
   then (§9F).
4. **Now permitted:** S0–S3, once each step's script is written to Appendix A and reviewed. Those steps also need the
   human decisions the protocol lists for them: H-13 before S1, and H-04 before the S3 bundles.
5. **Still open** (§28): H-02, H-03, H-04, H-11a–d, H-12, H-13, H-15, H-16, H-17, H-18; OMQ-07, OMQ-09, OMQ-14, OMQ-15,
   OMQ-16, OMQ-17, OMQ-18; the H-19 target share and the addendum.
6. Nothing has been executed under the protocol at the time of this entry.

---

## G-LOG-0002 — Correction to G-LOG-0001 (factual detail only)

G-LOG-0001 states "Lines / headings: 3,066 / 133". The frozen file has **3,056** lines (`wc -l`). The sha256
`08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06`, the git blob, the checker result and the decision are
unaffected. Recorded 2026-09-24 by the AI session. G-LOG-0001 is left unedited, per this log's append-only rule.

---

## G-LOG-0003 — S0 PREFLIGHT executed: PASS (protocol v1.6.4 core, Appendix A.1)

| Field | Value |
|---|---|
| Authorization | the human approved the S0 script after reviewing its implementation evidence (the allowlist, the canonical-body hash, no nondeterministic body fields, a mechanical `git ls-tree` reference set), then approved the dry run, then the real run ("Yes — run the real S0 now", 2026-09-24) |
| Script | `scripts/p3b_s0_preflight.py`, commit `2ef3fcec7`, git blob `b991e4543daa0603e15433804d24d624fdb1e04b`, committed and unmodified at run time |
| Dry run | `--dry-run`: PASS, exit 0, nothing written (same checks) |
| Real run | 2026-09-24T11:04:18Z (UTC), Python 3.13.2, Linux-7.1.5-200.fc44.x86_64; exit 0 |
| Output | `P3B-PREFLIGHT.json`, file sha256 `01baee40a4fe21737bf0168013cb8c3649a077e7ea3cf4643950e16a010be453` |
| `output_sha256` (canonical JSON body) | `b3f621484c24c986586fbbe7cd7d643cc8c68ce398a2313f533c5876c07fe7b4`, re-derived independently after the run: match |

**Result: PASS**

| Check (A.1 / §19.1 S0) | Result |
|---|---|
| Reference set (`git ls-tree -r c9e76918b -- chronological-read`, A.1 exclusions) | 3,109 files checked; 4 excluded (all `prompts/`); per-file sha256 recorded for all 3,109 |
| Hash mismatches / missing files | 0 / 0 |
| Counts | 02-FILES 2,779 · 03-CONTRIBUTIONS 27,906 · labels 2,497 · pairs 1,793 · OB0001/2/3 80/80/81; 0 failures |
| Working tree (`chronological-read/`) | 0 tracked changes; 0 untracked files outside the allowlist {`P3B-PREFLIGHT.json`} |
| Frozen protocol hash | `08f10e74…1dec06` expected = observed |

**Meaning.** The frozen P3a baseline and the first-run ledger are byte-identical to `c9e76918b`, and all §5 existing-state
assumptions hold. This is the persistent execution baseline for P3b.

**Next (not started).** S1 needs H-13, the definition of affected-set B (§19.1a), and its script written to Appendix
A.2 and reviewed. S1 is not authorized by this entry.

---

## G-LOG-0004 — H-13 decided: affected-set definition = (i) A ∪ C only

| Field | Value |
|---|---|
| Decision id | **H-13** (§19.1a, OMQ-08) |
| Decider | the human project owner, 2026-09-24 |
| Decision | **(i) A ∪ C only** (expected 391 pairs, 477 labels), under outcome **B-UNREPRODUCIBLE** |
| Reason given | reproducibility: A and C are mechanically reproducible from frozen artifacts. B's selection procedure is not persisted, and 7 of its pairs cannot be identified, so adding the 20 historically listed ids would mix reproducible computation with the memory of an earlier computation |
| Treatment of B | B is preserved as **historical evidence of a prior anomaly that cannot be reconstructed from the frozen inputs**. The 20 ids listed in `audit-p3a/KSME-21-EVIDENCE-REPAIR-REPORT.md` Phase 2 may be kept as provenance or reference, but **never** enter the S1 operative population. The 7 unidentifiable pairs remain unresolved. **No inference may be drawn that omitted pairs were unaffected** |
| Deviation | this departs from the protocol's recommendation (ii), which §19.1a explicitly leaves to H-13 |
| Implementation condition | S1 derives A and C mechanically from their documented source artifacts and contains no hard-coded list of pairs or labels |
| Authorizes | writing the S1 script to Appendix A.2 for review. **Not** its execution |

---

## G-LOG-0005 — S1 INPUT FREEZE executed: PASS (Appendix A.2; H-13 (i))

| Field | Value |
|---|---|
| Authorization | the human approved the S1 script (commit `9742e0349` with G-LOG-0004), requested an audit-quality fix to the B-search inventory (commit `d15b4afaf`), reviewed the revised dry run, then approved the real run ("Yes — run the real S1 now", 2026-09-24) |
| Script | `scripts/p3b_s1_input_freeze.py`, git blob `60134b2168b9330073b36aff427b7ba5373e6a53`, committed and unmodified at run time |
| Dry runs | first (blob `cbf4d708…`): PASS, but the B-search listed the script itself; revised (blob `60134b21…`): PASS, self-reference excluded and recorded |
| Real run | 2026-09-24T11:35:49Z (UTC), Python 3.13.2 |
| Outputs | `AFFECTED.jsonl`, file sha256 `db8b946e2a22eaaac01fcb0abc7ca450e613220ccbfa6d62944544968fe3e4e1`, body `output_sha256` `7fe62247c7fa239e0378c692ab678bbb47e6694a77237f90566369c3d4d4015a`; `P3B-INPUT-MANIFEST.json`, file sha256 `83fbd16f53f219d453e97827e9190837a494086f7c061c7613e8a052ecb44a0c`, `output_sha256` `a6308ad4df573ac4f3eb4819654c76d90eaf7c3da4618693ac080a00a088b97b` |

**Result: PASS.** Independent post-run verification used separate code to recompute A and C from the source artifacts
and to re-derive every hash:

| Check | Result |
|---|---|
| Affected pairs / distinct labels | **391 / 477**; sorted by pair_id; unique; body lines canonical |
| A / C / A∩C / A∪C (independent recomputation) | 257 / 158 / 24 / 391; the record set equals the independent A∪C |
| Criteria tags | 0 wrong; A only 233 · C only 134 · A+C 24; **no record tagged B** |
| Body hash (AFFECTED) | re-derived = header `output_sha256` ✅ |
| Manifest | result PASS; `output_sha256` re-derived ✅; S0 link = the S0 `output_sha256` `b3f62148…` ✅; all 4 recorded input sha256 = actual ✅; AFFECTED hash in the manifest = the AFFECTED header ✅ |
| B | `B-UNREPRODUCIBLE`; 20 historical ids recorded with `operative: false`; B-search 11 hits (DOCUMENT 8, DATA 1, CODE 2), self-reference excluded and recorded; none regenerates B |

**Note on four historical B ids.** RP0023, RP0229, RP0307 and RP1073 are among the 20 historical B ids **and** appear in
the affected set. Each entered through **criterion A alone** (tagged `["A"]`; relationship UNWITNESSED, basis NONE),
independently of B. None was added because of B, and none carries a B tag. B contributed nothing to the operative
population. This matches the earlier measurement that 4 of the 20 historical ids already lie in A ∪ C.

**Next (not started).** S2 (tiering, Appendix A.3) needs its script written and reviewed. S2 is not authorized by this
entry.

---

## G-LOG-0006 — S2 TIERING executed: PASS (Appendix A.3)

| Field | Value |
|---|---|
| Authorization | the human approved the S2 script (commit `d51b3a126`), reviewed the dry run, then authorized the real run (2026-09-24) |
| Script | `scripts/p3b_s2_tiering.py`, git blob `7c272ca03ab7a6624085aaab058956fb4e4f5a93`, committed and unmodified at run time |
| Dry run | PASS, exit 0, nothing written |
| Real run | 2026-09-24T11:43:48Z (UTC), Python 3.13.2, script exit code 0 |
| Output | `P3B-TIERS.jsonl`, file sha256 `1ea659d4668d6f197068f6069e736cfbe98e00b8d2948197ac5fb583c835595e`, body `output_sha256` `a22c5c0e58a682be9378544cb58db71f9e0b577a497a274e8c05d9c7efb4ea35` |

**Result: PASS.** Independent post-run verification used separate code to re-derive the tiers from the frozen inputs:

| Check | Result |
|---|---|
| Records | 2,497 = the label universe; unique; sorted; canonical lines |
| Tiers (output = independent derivation) | **X 477 · Z 1,120 · U 900**; 0 disagreements |
| `pair_count` | output = independent count from `31-RECONCILIATION-PAIRS.jsonl` for all 2,497; Z all 0; U all > 0 |
| Tier X `causing_pair_ids` | 0 wrong or missing (the set equals the affected pairs touching the label; criteria match AFFECTED); 1–12 per label; 782 references in total = 2 × 391 |
| Tier Z/U `causing_pair_ids` | all empty |
| Hashes | body `output_sha256` re-derived ✅; `s1_link` = the AFFECTED body hash `7fe62247…` ✅ |
| Gate | S1 PASS in both artifacts; AFFECTED hash chain ✅; `_derived.json` and `31-RECONCILIATION-PAIRS.jsonl` unchanged since S0 ✅; protocol hash `08f10e74…1dec06` ✅ |

**Next (not started).** S3 (mechanical prep, Appendix A.4 and §19.1) needs **H-04**, the choice of evidence presenter for
the per-label bundles (§11.5), as a separate methodological decision, and a reviewed S3 script. S3 is not authorized
by this entry.

---

## G-LOG-0007 — H-04 decided: evidence presentation = V1-PLUS-ROWS, realised as a verbatim per-label slice

| Field | Value |
|---|---|
| Decision id | **H-04** (§11.5, §17, OMQ-01) |
| Decider | the human project owner, 2026-09-24, after reviewing `prompts/20260924_1346_p3b-H04-decision-brief.md` |
| Decision | `row_bundle_v2` is **not** used as P3b's evidence presenter. S3 builds per-label bundles as (1) the **unchanged** `reconciliation_objects` entry; (2) **every distinct** `03-CONTRIBUTIONS.jsonl` row whose `labels[]` contains the working label, **all fields, byte-exact**; (3) the **verbatim** `02-FILES.jsonl` record of each cited source; with **no field selection and no transformation**, in deterministic order, **hashed** per batch (`P3B-BATCH-SNAPSHOT.json`). §18 value: `evidence_presentation: V1-PLUS-ROWS` |
| Explicitly excluded | ad hoc agent browsing of `03-CONTRIBUTIONS.jsonl` (not reproducible). The bundle is the agent's row evidence |
| Reason | `row_bundle_v2` (14 fields) is lossless only for P3a pair-relationship decisions. It omits `review_flag` (325 rows), `missing[]` (1,504), per-row `completeness`, `experiment`, `version_ref`, and the `02-FILES` fields that A.10 uses, all of which P3b needs (brief §5). **S3 does not decide what evidence is relevant; it materialises the label's evidence completely and reproducibly** |
| Conditions | (a) the agent contract labels P1 assessment fields (`label_confidence`, `unknown_candidate`, `completeness`, `missing[]`, `review_flag`) as **assessments, not SOURCE** (§11.1); (b) **distinct** rows are used. The known P2 discrepancy (label `open-by-commission-negative-history-category`: `row_count` 18, 12 distinct rows, from duplicate listings in `labels[]`) is **recorded, and P2 is not changed**; (c) no P3a verdict is changed, re-derived or hidden |
| Observation (not a reopening, §26 item 7) | frozen §9.2 and §11.5 overstate `row_bundle_v2`'s coverage: it copies neither per-row completeness nor `missing[]`. No conclusion is affected under this decision. Recorded for a future protocol version |
| Scope | determines the evidence presentation for S3 onwards. **Reopens no earlier result** (S0–S2 unaffected) |
| Authorizes | writing the S3 script for review. **Not** its execution |

---

## G-LOG-0008 — S3 implementation decisions S3-1 … S3-4 (human-approved)

| Decision | Approved treatment | Condition |
|---|---|---|
| **S3-1 search terms** | **Appendix A.4 governs**: terms = `working_label` (hyphens kept, and hyphens → spaces), every notation and alias. The §11.4 wording that also includes "group co-members' notations" (about 9,837 extra term instances) is **not** applied | the §11.4 / A.4 discrepancy is **recorded as a protocol observation** for a future version, not resolved during execution |
| **S3-2 hit storage** | hits stored **once per label**, kept per source file (S-id), with **one record per `NOT-EVIDENCED-IN-CAPTURE` dimension** that references the label's hit table | **deduplicate storage, not meaning**: every dimension still records that it produced or references those hits, and the A.4 per-dimension record is reconstructible exactly |
| **S3-3 bundle persistence** | commit a **per-label index** (row keys, line numbers, sha256 of each verbatim row line; the source records likewise) and **materialize byte-exact bundles deterministically at dispatch**, verified against those hashes and recorded in `P3B-BATCH-SNAPSHOT.json` | **materialization reproduces exactly what the agent sees**: no filtering, normalization, field selection or transformation |
| **S3-4 track tags** | computed now from the KSME-20 directory mapping, with the mapping's hash, marked `PROVISIONAL-PENDING-OMQ-07` | **OMQ-07 remains open**; the provisional mapping is not a canonical ontology decision |

**NFKC finding.** Mathematical letters collapse under A.4's NFKC normalization (`𝒦` → `K`: 74,643 hits in 1,432 files;
`𝒪` → `O`: 63,947 in 837). This is **measured by S3 and not fixed in S3**. S3 does not thin, filter or change the search
semantics. Any change is a later explicit decision (H-12, or §26).

Decider: the human project owner, 2026-09-24. **Authorizes:** writing the S3 script, static checks, commit after
implementation review, and a **dry run only**. The real S3 is **not** authorized.

---

## G-LOG-0009 — census content identity: decisions D-a, D-b, D-d(i), D-e, D-g adopted; D-c moot

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24. The choice "Adopt as listed" was confirmed in writing: "I would adopt the decision set exactly as listed" |
| Recorded | 2026-09-24T12:54:02Z, by the AI session acting on that instruction |
| Trigger | the S3 dry run found 102 CONTENT files not readable at their recorded `commit` (session log 2026-09-24). **S3 real run blocked since then** |
| Evidence (as decided on) | full sha256 in the table below. All uncommitted at the time of recording |
| Pre-commit revision | at the human's review, before commit (2026-09-24): D-f changed to **deferred**; abbreviated hashes replaced by full sha256; the derivation's superseded 13-file passages marked (traceability cleanup, no semantic change), so its pinned hash changed from `6755cf8e3c02806d4c6a4ba03614eded2ec3f1571fade8bea4ee63abe01baf4e` |
| Superseded as design input | `prompts/20260924_1407_p3b-v1.6.5-census-provenance-proposal.md` (draft; its D-1 … D-5 are **not** decided) |

**Pinned evidence (full sha256)**

| File | sha256 |
|---|---|
| `audit-p3b/20260924_1425_p0-identity-calibration-report.md` (with its 14:39 and 14:47 corrections) | `af7b6655d806aa2966a0518cb02b983e4e68e7952e5737021eddfaa6f46b7beb` |
| `audit-p3b/CENSUS-PROVENANCE-AUDIT.jsonl` | `928ac9b87c0dca5f0b204f84f6856780a790e95c96becc9d7b20880c294b5e46` |
| `scripts/p3b_census_provenance_audit.py` | `a78531f223bc6827f10be541f38db46ed06d52324b9613ad097a59ec489e413a` |
| `prompts/20260924_1439_p3b-v1.6.5-minimal-delta-derivation.md` (revisions 14:47, 14:58, traceability cleanup) | `712b1d7aae5f3340372e7461f0ad977759b1bdb5ba6a6800e60c986a6e444848` |
| `prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md` (frozen, unchanged) | `08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06` |

**Findings the decisions rest on (measured; details in the evidence).** P0's roadmap sha256 is the identity reference
(v3.5 R11). A byte-exact copy matching it exists for 2,764 of 2,767 CONTENT files. For the other 3 (S2344–S2346), P0's
(sha256, mtime) pair is cyclically shifted, and path, content, P1 summary and P1 rows converge against it. The recorded
`commit` is the dispatched P0 snapshot; it does not hold P0's bytes for 97 files. P1's `file_mtime` was copied from P0's
manifest and is **not** evidence. **Working-tree stasis** (current bytes = identity bytes and current mtime = P0 mtime)
is observed for **2,767 / 2,767**, after the human reported that 13 files believed deleted were renamed. That supports,
and does not prove, that P1 could only have observed the identified bytes. No value uses `VERIFIED`.

| Decision | Adopted |
|---|---|
| **D-a** identity model | three orthogonal fields per CONTENT file: **`content_identity`** (`P0-HASH` · `PATH-CONTENT-P0-ROW-MISALIGNED` · `UNRESOLVED`), **`historical_linkage`** (`STASIS-OBSERVED` · `STASIS-UNOBSERVABLE`; the latter kept for future cases, 0 now), **`search_eligibility`** (`TEXT` · `BINARY`). Storage location and blob sha256 are recorded fields, not classifications |
| **D-b** P0-row exceptions | S2344, S2345, S2346 are the **only** accepted P0-row-misalignment exceptions: identity = the content at the path. **A newly detected misalignment is never auto-accepted**: it becomes `UNRESOLVED` and needs explicit human adjudication |
| **D-c** the 13 "deleted" files | **MOOT**: renamed, not deleted; stasis observed |
| **D-d(i)** the 8 `.pyc` CONTENT files | **kept in the lexical search population.** Decided **before** any `.pyc` hit count was observed (none has been measured). Being binary is search eligibility, not a provenance failure |
| **D-e** S2b | a new, separate step **S2b IDENTITY RESOLUTION** before S3: it produces a frozen identity manifest; S3 reads exactly the manifest's blobs and verifies each blob's sha256 as it consumes it |
| **D-g** blob specs and preservation | git-object blob specs wherever available (e.g. `e3e47b139:<historical_path>`); the manifest records `historical_path` and `current_path` separately. The 8 untracked `.pyc` are preserved as **hash-addressed audit copies** under `audit-p3b/preserved-content/sha256/`, each verified byte-for-byte against P0's sha256, and **not committed into the research corpus** |
| **D-f** v1.6.5 drafting | **NOT YET DECIDED, deferred.** Drafting v1.6.5 needs a subsequent, explicit human authorization after review of this entry, recorded as a new governance action. **No v1.6.5 amendment is authorized by this entry** |

**Unchanged by this entry:** frozen v1.6.4 (sha256 above) · the census of 2,767 (§3.6) · the NEGATIVE-CENSUS
condition · A.4 terms, normalization, boundaries, hit handling and the 1.33 M hits (H-12) · P3a · S0–S2 outputs · H-04 ·
G-LOG-0008.

**Out of scope, recorded:** 171 CONTENT records whose P1 `file_mtime` differs from P0's (66 with
`best_historical_date_basis = MTIME`). A separate governance item, not part of v1.6.5.

**Not authorized by this entry:** drafting v1.6.5 or editing v1.6.4 · executing S2b · creating the `.pyc`
audit copies (an S2b act) · executing S3 · measuring `.pyc` hits.

---

## G-LOG-0010 — G-LOG-0009 approved; D-f authorized (v1.6.5 draft) and S2b authorized through its dry run

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24, choice "Through S2b dry run" in response to the authorization question after reviewing G-LOG-0009 |
| Recorded | 2026-09-24T13:02:03Z, by the AI session acting on that instruction |
| Approves | **G-LOG-0009** as it stands in this commit (its pre-commit revision included) |
| **D-f** | **authorized:** draft v1.6.5 **strictly from** `prompts/20260924_1439_p3b-v1.6.5-minimal-delta-derivation.md` (sha256 `712b1d7aae5f3340372e7461f0ad977759b1bdb5ba6a6800e60c986a6e444848`), with no other methodological change; run the freeze integrity check (§26 item 6) and an independent confirm-only pass |
| Freeze | **not** granted by this entry. Freezing v1.6.5 is a separate human decision (H-01 class, §26 item 2) after the check and the confirm pass |
| After a freeze decision | writing the S2b script to the frozen v1.6.5, its implementation review, commit, and an S2b **dry run** |
| **Not authorized** | the real S2b run · creating the `.pyc` audit copies outside a dry run's scratch space · changing the S3 script · any S3 run · measuring `.pyc` hits |

---

## G-LOG-0011 — strict stasis rule (confirm-pass finding F1); corrects the stasis count in G-LOG-0009

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24, choice "Strict" on blocker F1 of the independent confirm-only pass on the v1.6.5 draft |
| Recorded | 2026-09-24T13:11:27Z, by the AI session acting on that instruction |
| Finding F1 | the draft's A.3b rule 4(b) inferred a lower bound on P1 read times from ledger write order. It is unsound: v3.5 dispatched with a rolling window, batches finished out of order (e.g. B0004 after B0036), and retries rewrote ledgers later (B0030 and B0053 on 2026-09-17), so the bound can be too late, the unsafe direction |
| Decision | `historical_linkage = STASIS-OBSERVED` **only** when a working-tree file holds the identity bytes **and** its mtime equals the `file_mtime` of the record's own P0 row (for an accepted exception row, the S2344–S2346 P0 row whose sha256 equals the identity sha256). No inference from P1 batch timing. The derivation's clause "or bytes unchanged since a time before the P1 read" is **not** adopted |
| Correction of G-LOG-0009 | G-LOG-0009 states stasis "observed for 2,767 / 2,767". Under its own definition (current mtime = P0 mtime) that was an overstatement: **S2809** has the identity bytes but an mtime written after P0 (2026-09-11 21:28 +02:00 vs P0 2026-09-07 07:16 +02:00). Correct count: **STASIS-OBSERVED 2,766 · STASIS-UNOBSERVABLE 1 (S2809)**. My earlier claim that S2809's write preceded P1's read was not established |
| Consequence | S2809 stays in the census and is searched. Its status reaches only the additive `identity_summary` (`stasis_unobservable: 1`); the general rule stands that a hit is evidence regardless of linkage and an absence in a STASIS-UNOBSERVABLE file carries a qualifier. The NEGATIVE-CENSUS condition is unchanged |
| Other confirm-pass findings | F2–F7 (clarifications within scope) applied to the draft. F8 (stage-2 whole-file reading is not tied to the manifest blob) is outside the authorized delta: recorded here for the S3/S4 implementation, which must read the manifest blob, not the working-tree path. Not a protocol change |
| Authorizes | nothing beyond G-LOG-0010. The v1.6.5 freeze remains a separate human decision |

---

## G-LOG-0012 — FREEZE of the P3b operating protocol (core) v1.6.5

| Field | Value |
|---|---|
| Decision id | **H-01** (approve the annex, §26 item 2) for the core protocol **v1.6.5** |
| Decider | the human project owner, 2026-09-24, choice "Freeze v1.6.5" |
| Recorded | 2026-09-24T13:13:20Z, by the AI session acting on that instruction |
| Basis | G-LOG-0009 (decisions), G-LOG-0010 (drafting authorized), G-LOG-0011 (strict stasis rule); independent confirm-only pass: round 1 `VERDICT: DEFECTS` (blocker F1, minors F2–F7, notes F8), round 2 `VERDICT: CONFIRMED` on the file below |
| Supersedes | v1.6.4 (`prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md`, frozen G-LOG-0001), kept unchanged |

**Frozen artifact**

| Field | Value |
|---|---|
| File | `docs/knowledgeos/chronological-read/prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md` |
| sha256 | `b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7` (the file confirmed in round 2) |
| git blob | `c22dd80269f81c71e21515000f36e87285582e21` |
| Lines | 3135; sections 1–42; D-01…D-74 |

**Freeze integrity check (§26 item 6, Appendix A.9)**

| Field | Value |
|---|---|
| Script | `scripts/p3b_freeze_check.py`, git blob `bcc32b80d73c6982b9bf089c0760926d292cd749` |
| Result | **PASS**: rules 1–8 clean; numbering 1–42; H 22, OMQ 22, D 74, G 13 defined with no undefined references or duplicates |

**Authorizes (per G-LOG-0010):** writing the S2b script to Appendix A.3b, its implementation review, commit, and an S2b **dry run** (no audit copies under `audit-p3b/`). **Not authorized:** the real S2b run · any S3 change or run · measuring `.pyc` hits.

---

## G-LOG-0013 — S2b IDENTITY RESOLUTION: real run PASS, independently verified

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24, choice "Run S2b through commit" |
| Recorded | 2026-09-24T13:22:12Z, by the AI session acting on that instruction |
| Script | `scripts/p3b_s2b_identity_resolution.py`, git blob `eef3a92f2634c5209045b17901a20dbf63bce0b1` (committed in `23f700c65`, unmodified at run time); protocol v1.6.5 sha256 `b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7` |
| Run | 2026-09-24T13:20:28Z, HEAD `23f700c6557d342543c73bf4a6ad41c7a4e28704`, result **PASS**, all verification targets met |
| Output | `P3B-IDENTITY-MANIFEST.jsonl`: file sha256 `74cb210bb63d1956647186a53f2f54119fd6a219fb6b23795ad9b6fe3ea0d042`; body `output_sha256` `7768b53bae78ef74f318efa4434c25d521548c5c6e7f6446b095c56cab58b5f6` (identical to the committed-script dry run) |
| Counts | CONTENT 2,767 · `content_identity` P0-HASH 2,764, PATH-CONTENT-P0-ROW-MISALIGNED 3 (S2344, S2345, S2346), UNRESOLVED 0 · `historical_linkage` STASIS-OBSERVED 2,766, STASIS-UNOBSERVABLE 1 (S2809) · blob GIT-OBJECT 2,759, PRESERVED-AUDIT-COPY 8 · `search_eligibility` TEXT 2,753, BINARY 14 (8 .pyc, 2 .png, 2 .docx, 1 .odt, 1 .pdf; all searched, D-d(i)) |
| Preserved copies | 8 files in `audit-p3b/preserved-content/sha256/`: `1e418cd33d6e691321f5e9546050ccd94e4fd544c0207290e7006f2d88901aa2.pyc` · `1fa4ed71c6feb1e9758046d8713239ab7c83eb9b51aec9b4901e686547fec3f9.pyc` · `590eb00e27d06a476db277719c3818f2d83e4e909dc5af886a5086b1c27be609.pyc` · `65bf333764e64539ef84b3a3cef7226b1c8db402e7d426ddf41c0345f5dccc87.pyc` · `c1702d52f1fc3b46f5e8299edc21ca90987b6cb147bd85a22766a2d14e21d9eb.pyc` · `d0b8dd942e80b5d36ba92194515f51654a46fd0eae566b440fbbbc3ac59e8641.pyc` · `daaf6c0aa43f377c8fe1fd68230473d80ffa047a3aca83a44665795380f5a812.pyc` · `eda0b2dfd1e8defc1a053ed10be7f327d258bde604039413cfb579557f9a2f0e.pyc`. Each byte-identical to its working-tree source and named by its sha256. The repository `.gitignore` ignores `*.pyc`; these 8 paths are committed with `git add -f` so the preservation is durable. `.gitignore` is unchanged. They are audit copies, not research corpus (D-g) |
| Independent verification | a fresh read-only agent with its own code, baseline = the **committed** audit `audit-p3b/CENSUS-PROVENANCE-AUDIT.jsonl` and the committed `02-FILES.jsonl` / `00-ROADMAP-VALIDATED.jsonl`: header hashes and links; population 2,767 = CONTENT = audit ids; identity per audit class (C1/C2 2,764, C3 = the 3 exceptions); all 2,759 git blobs re-hashed; the 8 copies exactly the audit's untracked .pyc; strict stasis recomputed with 0 disagreements; eligibility 0 mismatches; no tracked file changed. **`VERDICT: VERIFIED`** |
| Claim strength | the manifest records identity resolution and **supported** historical linkage. It does not prove what P1 read (no P1 read-time hash exists). No value asserts `VERIFIED` |
| Next (not authorized by this entry) | S3 script change to read via the manifest and verify blob sha256 (A.4), plus F8 (stage-2 reading uses the manifest blob); implementation review; S3 dry run; any real S3 run needs a separate authorization |

---

## G-LOG-0014 — S3 bound to the S2b identity manifest; S3 dry run PASS, independently verified

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24, choice "Through S3 dry-run check" |
| Recorded | 2026-09-24T14:04:30Z, by the AI session acting on that instruction |
| Change | `scripts/p3b_s3_mechanical_prep.py` committed in `afb79aa21` (git blob `95017f1230a73ff67f89b38c0e7b084a3149655d`): every CONTENT blob is read only through `ContentResolver` (source_id → `P3B-IDENTITY-MANIFEST.jsonl` row → blob; sha256 verified; fail closed; never the census path); gate on the S2b PASS manifest pinned to the G-LOG-0013 body hash, before any CONTENT read; `from_manifest()` is the pinned entry point for stage-2 readers (F8, G-LOG-0011); §11.4 `identity_summary` on every DIMENSION record; sha256 of every blob read recorded (A.4). Search terms, normalization, boundaries, hit storage and H-04 bundles unchanged |
| Implementation review | independent reviewer, static and audit-hook dynamic checks with tampered manifests: **no bypass**. Conformance findings R1 (missing `identity_summary`) and R2 (blob hashes not recorded), R3 (unpinned `from_manifest`) and notes fixed; re-review `VERDICT: NO BYPASS (fixes confirmed)`. Own evidence: bypass proof, 18 fail-closed tests, original S3 regression tests |
| Dry run | committed script, `--dry-run`, result **PASS**; nothing written to the repository. CONTENT files read 2,767 (unreadable 0; the first S3 dry run had 102); `verified_blobs` 2,767, digest `6e18cd7be03d4f5350d29f023411fab96e59465430d79dca8ae35b79a57cf12d`; DIMENSION records 20,107, all `files_searched` 2,767, `scope` CORPUS-WIDE, `identity_summary` {stasis_unobservable 1, p0_row_exception 3}; NEGATIVE-CENSUS 6,163 (664 labels), hits present 13,944; would-be search body `c7edb3fbc758c018…`; bundle index body `155fbb5fda06982b3fedf89af58893d2efed869ca453ed77ac6205bf2a9cca86` (identical to before the change) |
| Independent verification | a fresh read-only agent with its own code: header hashes and links; all 2,767 blobs re-read and re-hashed; record counts and fields; NEGATIVE-CENSUS set exact; independent A.4 regex recomputation of ledger and raw hits for a seeded sample (40 labels, 60 files incl. renamed, exception, preserved and PDF files) plus a supplementary single-character and heaviest-label run: **0 disagreements**. **`VERDICT: VERIFIED`** |
| Measurement change acknowledged | stage-1 hits over labels with absence dimensions: **1,364,733** (was 1,330,367 in the S3 dry run before identity binding; +34,366). The increase is caused by reading the 102 previously unreadable files and the identity bytes of S2804/S2880. It is a measurement under the unchanged A.4 method, not a method change. The frozen text's "1.33 M hits (H-12)" is the earlier measurement; **H-12 (workload) remains open** and should use the new figure |
| Claim strength | dry-run measurements only; no research result is claimed. NEGATIVE-CENSUS here is a stage-1 label, subject to the stage-2 and later rules of the protocol |
| **Not authorized** | the real S3 run (writes `P3B-ABSENCE-SEARCH.jsonl`, `_batch_input_r2/bundle_index.jsonl`, `P3B-WORKLOAD.json`; the storage decision for the ~58 MB search output is open) · anything after S3 |

---

## G-LOG-0015 — S3 MECHANICAL PREP: real run PASS, independently verified

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24: real S3 run, commit all three outputs (option a), stop before H-19 / S3b / S4 |
| Recorded | 2026-09-24T14:18:15Z, by the AI session acting on that instruction |
| Run | 2026-09-24T14:14:26Z; script blob `95017f1230a73ff67f89b38c0e7b084a3149655d` (committed, unmodified); protocol v1.6.5; manifest body `7768b53b…b5f6` (G-LOG-0013); result **PASS** |
| Outputs (committed) | `P3B-ABSENCE-SEARCH.jsonl` file `1f7c832d5bb82e3a20b92cf37f30633971a9124ff4bb50a52e77d46986331b5d`, body `c7edb3fbc758c018dc5121c6364c3f18b0c7b7cd0b9628d618a0ed970048bba3` · `_batch_input_r2/bundle_index.jsonl` file `d64a2410b7dae6a240224ded70dd36bc6b6d5c422293b53e2f4117d0513161c9`, body `155fbb5fda06982b3fedf89af58893d2efed869ca453ed77ac6205bf2a9cca86` · `P3B-WORKLOAD.json` file `8ae7df23370c950814a8053895d775fb63c45022862f24decaf710a3b98fed2f`, body `32ea2d1bf16a14f13ec9cd51fe828f30b3c0f1aa0322a07152dfa9ebf7912182`. Search and index bodies are byte-identical to the verified dry run (G-LOG-0014) |
| Measurement | Under the frozen A.4 rule, S3 found **1,364,733 label-term matches** across the ledger (**145,187**) and the raw text of the 2,767 CONTENT files (**1,219,546**; **964,591** distinct text positions, 186,648 of them counted for more than one label), over the **2,493** labels with absence dimensions. Highly skewed: median 4 per label; the top 5 labels hold 47.9% (single-character terms A, B, C alone give 348,408 raw matches of `three-candidate-kernel-architectures`). **6,163** of 20,107 absence dimensions (664 labels) are NEGATIVE-CENSUS at stage 1. These are protocol-defined mechanical matches: their semantic relevance is assessed through the subsequent stage-2 reading, and their significance for the research is determined only by later stages |
| Verification scope | A fresh read-only agent verified the written files: all 2,767 file identities and blob hashes **fully** re-read and checked; record counts, fields and the NEGATIVE-CENSUS set exact; every bundle rebuilt from the index with 0 failures. The independent A.4 recomputation covers a **defined seeded sample** (30 labels × 50 files, all ledger rows for those labels): **0 disagreements**. It supports implementation correctness; it is not an exhaustive recomputation of all 1,364,733 matches. **`VERDICT: VERIFIED`** |
| Known properties of A.4 (not defects) | NFKC collapses mathematical letters (e.g. 𝒦→K); the 14 binary CONTENT files are searched as decoded bytes (D-d(i)). Both are recorded properties of the frozen rule, to be read with care at stage 2 |
| Next | H-19 addendum (hold-out) → S3b → S4 pilot. H-12 (S5 authorization) needs the S4 pilot results. **Not authorized by this entry** |

---

## G-LOG-0016 — H-19 addendum v1.5 APPROVED (variant S)

| Field | Value |
|---|---|
| Decision ids | **H-19** addendum approval (§9F gate, §26): **H19-D1** variant S · **H19-D2** the §7 rules · **H19-D3** approval |
| Decider | the human project owner, 2026-09-24 |
| Recorded | 2026-09-24T15:05:31Z, by the AI session acting on that decision |
| Approved artifact | `docs/knowledgeos/chronological-read/prompts/20260924_1625_p3b-H19-holdout-addendum-v1.5.md`, sha256 `75a5e45d0f8a1e5726beb92ef1ac18c540355ce51cdec7b8dd6873399e806ae3`, git blob `3f94b817fbf9f40220531d26ebdd37e277e40bef`. Earlier drafts v1.0–v1.4 are kept unchanged for traceability |
| H19-D1 | **variant S** (graph edges E1 ledger rows, E2 `files_touching`, E3 P2a groups, E4 P3a pairs; exclusion rules R0–R7): **26 components / 45 labels / 33 files** (2.2% of the 2,020 S5-eligible labels), label list sha256 `7febd747ad2d2a494e34db40cc29c95f33fe2003a8ea5b505324b74e1e13f0fc`, file list sha256 `46cdabe271768a9db7ea06967eaa6d3963c001f11680e2e0d896153f9e2d8a9d`. The human first chose variant M on my claim that it was fully sealed; the verification showed M leaked (B-1 content containment, B-2 hold-out labels named in discovery bundles), and the human re-decided S on corrected numbers |
| H19-D2 | THRESHOLD predictions only; O over a named allowlist of agent-judgment S5 fields; baseline stratified on tier × row count × source count × the frozen analog of O, with a Jeffreys posterior per stratum and a beta-binomial posterior-predictive test (seeded Monte Carlo, fully specified); strata collapse without ever dropping the analog; ≥ 5 components with a baseline; Holm at 0.05 over every prediction persisted before the unseal |
| Verification | independent verifier, 6 rounds, own code from the text: `§3` reproduced exactly every round. Blockers found and fixed: B-1, B-2 (variant M leakage), B-3 (outcome computable before the unseal), C-1 (CONTRAST without a baseline; CONTRAST removed), D-1 (degenerate stratum rates), E-1 (collapse dropped the analog); E-2 (plug-in uncertainty) fixed. Round 6: **`VERDICT: CONFIRMED`** (worst-case false-confirmation rate ≤ 0.048). The only later edits are the two wording points it requested |
| Human operating rule for S4 onward | **Once the hold-out is sealed, exploratory corpus reading happens only through the discovery population. The hold-out stays invisible until predictions are persisted and the authorized unseal occurs** (enforced by the seal lists, the discovery-derived files and the `ContentResolver` seal check, per addendum §2, §4, §6) |
| Limitations accepted | the predictive test is weak by construction (26 components; periphery, 96% Tier Z, L-9; prior exposure, L-11; residual predictability stated in §8) |
| Next (authorized) | write the S3b script (seal record, discovery-derived files, sealed S3 records, `ContentResolver` seal check, sample plan), review, commit, **dry run**. **The real S3b (writing the seal) needs a separate human go** |

---

## G-LOG-0017 — pre-S3b/S4 decisions: OMQ-15, OMQ-09, OMQ-14, H-03

| Decision | Adopted (human project owner, 2026-09-24; recorded 2026-09-24T15:08:16Z) |
|---|---|
| **OMQ-15** checklist population | the protocol's narrow recommendation (§9C.1): purposive = a CONTRADICTION-typed row · ≥ 2 formal rows (non-empty `type_signature`) · a MATH-/STAT-/TYPE-QUESTION `review_flag` · a strong lineage kind (SOURCE-CLAIMED- REPLACEMENT, REDEFINITION, RETRACTION, CONTRADICTION, SEPARATION); reproduced over all 2,497 labels as 245 / 193 / 190 / 217, union 589. Stratified random sample from non-purposive labels, strata `row_count` band × `pair_count` band × provenance mix, cut-points as proposed |
| **OMQ-09** sizes | random-sample total **n = 200**, floor **f = 2** per non-empty stratum; S4 pilot **30** labels (from the 241 `P3B-R1` labels, Tier U/Z); audit sample **10%** of records per batch (minimum 5) |
| **OMQ-14** reading scope | **per-label**: rows in historical order, plus whole-file reading of sources carrying a birth, a change of definition/type/meaning, or a contradiction |
| **H-03** | **confirmed**: `P3B-R1` (OB0001–OB0003, 241 labels) is the pilot baseline for the R1 vs R2 comparison |

---

## G-LOG-0018 — S3b: hold-out SEALED (HS-3d32dd44d162), sample plan and discovery files; independently verified

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24: proceed to the real seal after the implementation review passed |
| Recorded | 2026-09-24T15:24:03Z, by the AI session acting on that instruction |
| Human decisions (pre-seal) | **seed = 20260924** for the sample plan (approved and frozen); **the 31 zero-row labels stay in the `r0-1` stratum**, vacuously PRIMARY-only (no separate stratum) |
| Run | 2026-09-24T15:22:45Z; `scripts/p3b_s3b_holdout_sample_plan.py` blob `342ba2353ea7…` (commit `5277a5214`, unmodified); result **PASS** |
| **Seal** | `P3B-HOLDOUT-SEAL.json` sha256 `9b99169d30d57b2ecc1d331838652aa6b765ce722059b30095a03df077d589c0`: **state SEALED**, seal id **HS-3d32dd44d162**, variant S, 26 components / 45 labels / 33 files, label list `7febd747…`, file list `46cdabe2…` (= G-LOG-0016); 14 input hashes incl. the P3B-R1 files and the approved addendum |
| Derived files | `P3B-DISCOVERY-SEARCH.jsonl` `3dc5a5a1…` (body `df41777b…`; 2,452 labels, 19,694 dimensions, 5,976 NEGATIVE-CENSUS on DISCOVERY-POPULATION basis, 2,734 files) · `_batch_input_r2/bundle_index_discovery.jsonl` `00b5d514…` (2,452 bundles, byte-identical to S3 records) · `P3B-HOLDOUT-SEALED-S3.jsonl` `44201f48…` (458 search + 45 index records; scripts only) · `P3B-SAMPLE-PLAN.jsonl` `0cc706f4…` (1,975 discovery Tier U/Z labels; 367 purposive + 200 stratified random over 24 strata = **567 checklist labels**; pre-registered outcome §9C.1 rule 1 in the header) · `P3B-WORKLOAD-DISCOVERY.json` `35758c9c…` (673,652 stage-1 matches over 2,448 labels; p50 4, p90 125, p99 9,148, max 64,277; 1,895.3 M chars of potential stage-2 reading). S3 outputs untouched |
| Verification | implementation review DEFECTS (3 blockers: R1 input hashes, pre-registered outcome, discovery workload) → fixed → PASS (independent reproduction of hold-out, draw, workload; determinism). Written files: **`VERDICT: VERIFIED`** (hashes = reviewed dry run; seal fields; `discovery_resolver()` refuses all 33 HF ids and reads discovery files; S3 outputs unchanged) |
| **Research-execution constraint** | S4/S5 code obtains CONTENT bytes **only** through `p3b_discovery_io.discovery_resolver()`; every S4/S5 script review asserts this. Residual risk accepted: the base `ContentResolver` of the committed S3 script can still be called directly and does not know the seal |
| **Scope note (human, 2026-09-24)** | terms that occur only through S0705 (the Shapiro PDF: raw-byte matches, or book-specific names, notation or concepts with no independent definition in the corpus) carry **no research weight**. S0705 stays in the census and in the sealed hold-out; nothing is deleted or re-sealed. Observation for H-12: S0705 alone produced 680,630 of the 1,364,733 census stage-1 matches |
| Next | S4 pilot tooling (30 labels from `P3B-R1`, Tier U/Z, discovery population; contract per §19.2; TESTED/STATUS excluded until H-16), reviewed before any run |

---

## G-LOG-0019 — S4 pilot preparation: tooling reviewed; run-level choices recorded

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24: build the S4 pilot selection and agent contract, review them; **the 30-label run needs a separate go** |
| Recorded | 2026-09-24T15:40:27Z, by the AI session |
| Tooling | `scripts/p3b_s4_prepare_pilot.py` (selection, §19.2 contract assembled from 17 verbatim core sections + preamble + output schema, per-label slices, manifest, batch snapshot) and `scripts/p3b_read_source.py` (the agents' only corpus reader, via `discovery_resolver()`; validates its arguments; logs every call, refusals included, to `ledger-p3b-r2/<run>/READ-LOG.jsonl`) |
| Review | independent reviewer, 3 rounds: DEFECTS (B1 checklist blinding, B2 family .md missing, B3 track tags missing; minors) → DEFECTS (a stage-2 narrowing introduced by a rewording) → **PASS** (determinism, hold-out cleanliness of all slices, contract blocks byte-identical to the core) |
| Pilot | population P3B-R1 ∩ discovery labels ∩ Tier U/Z = 196; **seed 20260924** (announced to the human; used unless changed before the run); 30 labels (16 U, 14 Z; 13 in the checklist population), 6 batches of 5; run id `S4-PILOT-R2-001`; stage-2 scope = every file keyed in the label's raw or ledger hits (407 files, 77 ledger-only) |
| Run-level choices (no core change) | Tier U `semantic_status: null` with note **PENDING-H-11** (H-11a–d undecided; §12.2.4 not executable), with layer-A `semantic_evidence` {d4, d5} so rows 1–2 apply after H-11 without re-reading · Tier Z note = the §9.3 string · no STATUS / TESTED (H-16 undecided) · **`09-ORCHESTRATOR-FLAGS.md` not supplied** to agents (deviation from §9.2 input list, recorded here; it spans all P1 batches and is not filtered for the seal) |
| Audit rule for the pilot | every S-id cited in a batch output must be in its slices or in a non-refused read-log entry of that batch; any hold-out id fails; R1-vs-R2 comparison cannot compare `semantic_status` (always null in R2) |

---

## G-LOG-0020 — S4 pilot executed, verified and audited; awaiting human acceptance

| Field | Value |
|---|---|
| Authorization | the human project owner, 2026-09-24: run all 6 batches of S4-PILOT-R2-001 |
| Recorded | 2026-09-24T16:47:42Z, by the AI session |
| Run | contract `prompts/20260924_1740_p3b-agent-contract-r2.md` (sha256 `3dbce7d750a4a619…`), slices commit `ba0aa2ba7`; 6 agents in parallel; outputs `ledger-p3b-r2/S4-PILOT/PB01–PB06/` (30 object records, 189 research records, 160 P1-gap records); reader log 427 calls, 0 refused |
| Verification | `scripts/p3b_s4_verify_pilot.py` → `audit-p3b/S4-PILOT-VERIFY.json`: PB01, PB02, PB04, PB06 PASS; PB03 FAIL (G-12), PB05 FAIL (G-07, one step-7 log gap). No hold-out id cited or read; every cited S-id in slices or logged reads. The verifier lacks a G-08 provenance join (found by audit) |
| Audit (§21) | independent, sample `audit-p3b/S4-PILOT-AUDIT-SAMPLE.json` (30 records, seed 20260924): 18 agree, 12 disagree (16 judgment calls, 2 protocol violations); extra gate failures PB02 G-08, PB04 G-08, PB05 G-07; recommendation PB01, PB06 ACCEPTABLE-WITH-NOTES, PB02–PB05 NOT-ACCEPTABLE |
| Incidents recorded | PB02 stage-2 occurrence scan by forked sub-agents (disclosed; no status depends on it); PB03 loaded other batches' scratch outputs; PB05 printed other slices' metadata; undeclared helpers (PB02, PB05); shared scratch folder = orchestration defect. The audit found no contamination of outputs |
| Report | `audit-p3b/20260924_1846_s4-pilot-report.md`: validity per batch, method findings, research candidates (not promoted), changes required before S5 |
| Not decided here | batch acceptance (§25); rulings on the missing stage-2 disposition, the step-1-vs-NEGATIVE-CENSUS outcome and the MTIME-birth outcome; the PB02 occurrence-scan question; the re-run |

---

## G-LOG-0021 — S4 pilot: acceptance decisions (§25, H-06) and the four rulings (route: core v1.6.6)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24, on the S4 report (`audit-p3b/20260924_1846_s4-pilot-report.md`) and the §21 audit |
| Recorded | 2026-09-24T16:52:47Z, by the AI session |
| **PB06** | **ACCEPTED-WITH-NOTES** (H-06): quote transliterations of LaTeX; strained block notation |
| **PB01** | **accepted as exploratory research evidence, not contract-clean**: its FOUNDs resolved from whole-file step-1 reads after NEGATIVE-CENSUS are marked **protocol-divergent** (ruling 2 makes them ESCALATED). The research records are preserved |
| **PB02–PB05** | **not accepted** (G-08 PB02, PB04; G-12 PB03; G-07 + §14.2 rule 2 PB05). Kept per §20, marked FAILED; their records are archived as **"research observations generated during a non-accepted batch"**, never as accepted S4 evidence; re-run as R2.2 after the fixes. PB01 and PB06 are not re-run for conformity alone |
| Research vs conformance | S4's research outcome is successful (candidate material, method findings); its protocol-conformance outcome is PB06 accepted with notes, PB01 exploratory, PB02–PB05 to be re-run. A failed batch's observations are not thereby worthless |
| **Rulings (into core v1.6.6, §26)** | (1) new stage-2 disposition **UNSUPPLIED-DIMENSION**: the source concerns the same object but does not supply the dimension tested; distinct from FOUND, FALSE-HIT and ESCALATED. (2) NEGATIVE-CENSUS contradicted by whole-file step-1 reading → **ESCALATED**, recorded as a census–reading disagreement (never FOUND). (3) a birth dated only by MTIME outside a BULK block, with the date not confirmed for the file → **BIRTH-UNRESOLVED-MTIME-ONLY**, the timestamp kept as provenance only. (4) **Stage 2A/2B** for terms of ≤ 2 characters after normalization: a mechanical whole-file occurrence scan (every occurrence with offset, file hash, algorithm version), then semantic review of each occurrence context; a fixed threshold, not agent choice |
| Scope rule | v1.6.6 contains exactly these four changes; no other frozen rule is reopened or reinterpreted |
| Research state | none of the 12 hypotheses or 8 structure candidates is promoted; cross-label recurrence waits for S5a/S5b |
| Next | draft v1.6.6 → freeze check → independent confirm pass → human freeze; then the contract/tooling fixes and the R2.2 re-run of PB02–PB05 |

---

## G-LOG-0022 — FREEZE of the P3b operating protocol (core) v1.6.6

| Field | Value |
|---|---|
| Decision id | **H-01** (approve the annex, §26 item 2) for the core protocol **v1.6.6** |
| Decider | the human project owner, 2026-09-24, choice "Freeze v1.6.6" |
| Recorded | 2026-09-24T16:58:42Z, by the AI session |
| Basis | G-LOG-0021 (the four S4 rulings, route core v1.6.6); independent confirm-only pass: round 1 DEFECTS (minors M1–M4 and notes), round 2 DEFECTS (one §11.2 wording), round 3 **`VERDICT: CONFIRMED`** on the file below |
| Content | exactly the four rulings (UNSUPPLIED-DIMENSION; census–reading disagreement → ESCALATED; BIRTH-UNRESOLVED-MTIME-ONLY; Stage 2A/2B for terms ≤ 2 code points) and their consequential alignment (§13.4, §10, §11.2 rows, §15 rows, §9.8 steps 5 and 7); D-75; §43. The human noted the derived consequence: GENUINELY-UNDEFINED-AFTER-CENSUS is also reachable when every hit is UNSUPPLIED-DIMENSION |
| Supersedes | v1.6.5 (`prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md`, frozen G-LOG-0012), kept unchanged |
| Frozen artifact | `docs/knowledgeos/chronological-read/prompts/20260924_1853_p3b-phase1-continuation-protocol-v1.6.6.md` · sha256 `1eb7ac4e258c81318690f7ddfb804dd1d6b505a1a73aa1d2c9a07ec07693fe3f` · git blob `3d4d9b8a0f36e271576f0320de10ada88d9732eb` · 3185 lines · sections 1–43 · D-01…D-75 |
| Freeze integrity check | `scripts/p3b_freeze_check.py` blob `bcc32b80d73c6982b9bf089c0760926d292cd749`: **PASS** (rules 1–8 clean) |
| Recorded separately, not in v1.6.6 | the A.4 ASCII→Unicode/LaTeX variant gap (S4 report item 9); the §14.2 rule-4 "BULK/mtime block" wording note (confirm pass 3) — for consideration after S4 closure |
| Next (authorized) | R2.2 contract and tooling fixes citing v1.6.6, reviewed before the PB02–PB05 re-run |

---

## G-LOG-0023 — S4 R2.2 tooling reviewed; run-level choices for run S4-PILOT-R2-002

| Field | Value |
|---|---|
| Authorization | G-LOG-0022 ("R2.2 contract and tooling fixes citing v1.6.6, reviewed before the PB02–PB05 re-run"); the re-run itself needs a separate human go |
| Recorded | 2026-09-24T17:50:52Z, by the AI session |
| Tooling | `scripts/p3b_s4_prepare_r22.py` (contract citing v1.6.6 with 17 verbatim sections, closed values D, exact schema E; slices = the R2-001 PB02–PB05 slices plus `source_meta` and re-issued provenance ids), `scripts/p3b_stage2a_scan.py` (Stage 2A scanner; logs term and occurrence offsets), `scripts/p3b_s4_verify_r22.py` (schema, G-01…G-12 incl. the G-08 provenance join, one disposition per hit per dimension, v1.6.6 checks; exit 1 on failure); `scripts/p3b_read_source.py` now refuses the closed run S4-PILOT-R2-001 |
| Review | independent reviewer, 4 rounds: DEFECTS (6 blockers) → DEFECTS (5 minors) → DEFECTS (1 minor) → **PASS** on 46 fixtures; determinism; contract blocks byte-identical to v1.6.6 |
| Run-level choices (no core change) | (a) a birth whose sole basis is a SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or unknown-provenance file, with no earlier PRIMARY row to MOVE to, is written `ESCALATED[G-08: sole basis S#### is <provenance>]` (§14.2 rule 3 gives no outcome) · (b) a field the closed values cannot represent is left null with an `escalations` entry and a SCHEMA-LIMITATION record for the label (§12.4, §16.4) · (c) an S-id without a `source_meta` entry counts as UNKNOWN provenance and can never be a sole basis · (d) no helper agents or forks; private scratch per batch under `/tmp/p3b-s4-r22-scratch/<batch>/` · (e) `09-ORCHESTRATOR-FLAGS.md` still not supplied (as G-LOG-0019) |
| Known verifier limits (recorded) | a corpus phrase inside a MOVED birth's inline quote can trip the G-12 pointer check (audit disposes); FOUND⇔FOUND-hit enforced one way plus via GENUINELY-UNDEFINED; §13.11 structure-candidate fields not script-checked (audit) |
| Scope | run S4-PILOT-R2-002 re-runs S4-PILOT-R2-001 batches PB02–PB05 with the same 20 labels; nothing re-sampled; PB01/PB06 not re-run (G-LOG-0021) |

---

## G-LOG-0024 — S4 R2.2 run S4-PILOT-R2-002 executed, verified and audited (awaiting human acceptance)

| Field | Value |
|---|---|
| Authorization | human GO for the R2.2 re-run of PB02–PB05 (2026-09-24), accepting S4 decisions 1–7, with no methodological change during the run |
| Recorded | 2026-09-24T19:04Z, by the AI session |
| Run | S4-PILOT-R2-002: 4 batches, 20 labels, contract `336eb3f9…`, core v1.6.6; outputs `ledger-p3b-r2/S4-R22/PB02–PB05/`; reader log `ledger-p3b-r2/S4-PILOT-R2-002/READ-LOG.jsonl` (203 entries, 0 refused, sha256 `83abbcde…`). R2-001 records unchanged |
| Verification | first run 0/4, diagnosed before change: (A) repeated ledger keys are per-occurrence duplicates within one row; (B) null timeline field with escalation is G-LOG-0023 rule (b); (C) whole-file reads at step 1 not re-logged at step 7. Verifier corrected; (C) then tightened on the audit's recommendation to non-refused reads at steps 1/7. Result **4/4 PASS** (`audit-p3b/S4-R22-VERIFY.json`, sha256 `917863b4…`; verifier sha256 `19e9601e…`). These post-run corrections are **disclosed**; the independent audit reviewed them as conformance fixes |
| Audit | independent §21 audit, sample seed 20260925: no PROTOCOL-VIOLATION in the sample; all R2-001 failure causes fixed; quotes 470 exact + 8 normalized, 0 misses; G-08, G-12 and isolation clean. Recommendations: PB02, PB03, PB04 ACCEPTABLE-WITH-NOTES; PB05 ACCEPTABLE-WITH-NOTES **conditional** (step-verify-programme timeline and births quarantined; correction record for its MTIME-selected births, AUDIT-UPHELD) |
| Report | `audit-p3b/20260924_2104_s4-final-report.md` (R2-001 vs R2-002; validity, records, dispositions, reading cost, remaining defects F1–F8) |
| Research state | nothing promoted; no core or contract change; no new governance rule |
| Next | human acceptance of PB02–PB05 and of the final S4 state (§25). **S5 is not started before that** |

---

## G-LOG-0025 — Human acceptance of S4 R2.2 batches PB02–PB05

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24: "Accept PB02–PB04 with notes; PB05 conditional, quarantine step-verify" |
| Recorded | 2026-09-24T19:20Z, by the AI session |
| Basis | G-LOG-0024; `audit-p3b/20260924_2104_s4-final-report.md` §A |
| **PB02** | **ACCEPTED-WITH-NOTES**: disclosed contract A.3 breach, no contamination found; templated Stage 2B reasons (30/30 re-checks correct); 3 judgment calls on the glyph record |
| **PB03** | **ACCEPTED-WITH-NOTES**: ORDERED on NOT-CONFIRMED points; S1890/S2615 judgment call |
| **PB04** | **ACCEPTED-WITH-NOTES**: operational birth from an adversarial review; ORDERED on NOT-CONFIRMED points |
| **PB05** | **ACCEPTED-CONDITIONAL**: step-verify-programme's timeline and births are **quarantined** (`ledger-p3b-r2/S4-R22/QUARANTINE.jsonl`), with the audit's correction recorded as pending and not applied; the other four PB05 objects and all PB05 register records are accepted with notes. The quarantine lifts only after a separately authorized supplementary read and correction record |
| Records | R2-001 and R2-002 records unchanged; the quarantine is a separate marker |
| Not yet decided | acceptance of the post-run verifier corrections; handling of F1–F3 before S5; the PB05 supplementary read; acceptance of the final S4 state. **S5 is not started** |

---

## G-LOG-0026 — Post-run verifier corrections accepted; S4 closure path decided

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24 (choices "Accept", "Run-level rulings", "Now, before S4 closes", "Accept after the above") |
| Recorded | 2026-09-24T19:30Z, by the AI session |
| Verifier | the post-run corrections to `scripts/p3b_s4_verify_r22.py` (sha256 `19e9601e…`, committed in 9138cca36) are **accepted**: (A) one disposition per distinct ledger hit key; (B) null timeline field with an escalation (G-LOG-0023 rule b; SCHEMA-LIMITATION still enforced); (C) whole-file reader reads at steps 1 or 7 only, non-refused |
| F1–F3 | to be settled as **run-level rulings** in the governance log, binding for S5 runs; no core revision, no S4 re-run. Wording goes to the human for approval |
| PB05 | a targeted supplementary whole-file read of the 12 unread step-verify-programme row files (S1516, S1520, S1524, S1528, S1532, S1533, S1535, S1538, S1539, S1543, S1544, S1551) plus a correction record, audited, **before S4 closes**; not a PB05 re-run |
| S4 closure | the final S4 state is accepted once the verifier, F1–F3 and PB05 actions are done; S5 is then planned and submitted for approval before it runs |

---

## G-LOG-0027 — Run-level rulings F1–F3 (binding from S5 onward)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24 (choices "Flag governs", "FALSE-HIT", "Offsets + audit sample") |
| Recorded | 2026-09-24T19:40Z, by the AI session |
| Basis | S4 final report §F items 1–3; §21 audit items 4 and 6 |
| Route | run-level rulings; no core revision, no S4 re-run, no S4 record modified |
| **F1** | A timeline point whose date is NOT-CONFIRMED for the file is written with its order value and `date_applies_to_file: NOT-CONFIRMED`. Consumers (S5) treat every NOT-CONFIRMED point as **not ordered by date**, regardless of `order`. The existing null + escalation form (PB02, 7 points) is read as equivalent |
| **F2** | A stage-2 hit whose only relation to the label is that it names a file of the object (index row, directory listing, file map entry, path) is **FALSE-HIT**: its referent is a filename, not the object. S4 records written UNSUPPLIED-DIMENSION for such hits (PB03 S1890/S2615, PB04 S1426) stand as written; the outcome is unchanged |
| **F3** | (1) Each Stage 2B disposition cites the Stage 2A offsets (from the reader log) it covers; every logged occurrence is covered exactly once, and the verifier checks this. (2) Category maps and templated reasons are allowed as aids; the disposition is still per occurrence. (3) For a label with more than 100 occurrences, the §21 audit re-checks a seeded random sample of at least 30 occurrences. S4 PB02 (30/30 re-checked) stands as is |
| Consequence | S5 contract and verifier must carry F1–F3 (F3(1) needs a verifier check); reviewed with the S5 tooling |

---

## G-LOG-0028 — Governance note: theory synthesis direction (informational, non-normative)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24: "record the four-layer goal as a note" (text supplied by the human, recorded verbatim below) |
| Recorded | 2026-09-24T19:55Z, by the AI session |
| Status | **Informational / non-normative.** No effect on the frozen protocol, on current work, or on any gate. Applicable post-S5b only |

**Status:** Informational / non-normative
**Effect on frozen protocol:** None
**Effect on current work:** None
**Applicable timing:** Post-S5b only

The KnowledgeOS research programme currently recognizes the following intended direction for eventual theory synthesis:

> **Understand first → identify invariants and recurring structures → formulate the theory independently in new language → preserve traceability from every theoretical claim back to its evidential basis.**

This direction is expressed as a four-layer transformation:

1. **Corpus**
   Original corpus material, chronology, provenance, terminology, observations, definitions, experiments and historical development.

2. **Reconstruction**
   Reconstructed knowledge of what the corpus defines, distinguishes, relates, changes, supports and leaves unresolved, with provenance preserved.

3. **Cross-corpus abstraction**
   Recurring structures, relationships, invariants and distinctions identified across independently investigated material. Single-run research records are treated as discovery artifacts and are not themselves treated as theory components.

4. **Canonical theory**
   A new, optimized formulation expressed in terminology and language appropriate to the resulting theory rather than reproducing the corpus's original wording. Each substantive theoretical claim remains traceable to its reconstructed evidential basis.

This note does **not** define the theory, select theoretical primitives, establish mathematical definitions, prescribe a synthesis algorithm, or authorize canonicalization.

The note therefore does not alter Master Protocol v3.5, the frozen P3b methodology, the S4 process, or the S5a/S5b procedures.

Any formal methodology for theory synthesis shall be defined in a separate, versioned charter **after S5b**, informed by the empirical results of S5a and S5b.

### Scope qualifications

The current corpus-reconstruction state does not imply that all semantic discovery is complete. S4 demonstrated substantial lexical false-hit rates and identified unresolved search-coverage limitations, including notation variants, ASCII/LaTeX variants, accented forms and placeholder terms. These limitations remain relevant to subsequent research.

Likewise, S4 demonstrated run-to-run instability in individual research records and several semantic/status fields. Consequently, S5 shall evaluate recurrence and structural stability across labels and, where appropriate, across repeated runs rather than treating individual single-run research records as independent theory evidence.

### S5c

The S5 programme includes **S5c — scoring of the sealed H-19 hold-out predictions**.

The sealed hold-out is not ordinary corpus material available for exploratory research. It may be unsealed only through the prescribed human act and only once. Its use before S5c scoring would consume the hold-out and therefore is prohibited.

S5a, S5b and S5c remain distinct research steps under the frozen protocol.

**This governance note is descriptive and non-normative.**

| Recording remark | The "shall" sentences above are a stated direction within a non-normative note. The binding hold-out rule is the H-19 addendum v1.5 (sealed HS-3d32dd44d162). Any S5 obligation on repeated runs enters only through the S5 plan, which needs human approval |

---

## G-LOG-0029 — PB05 supplementary read (S4-PILOT-R2-003) executed, checked and audited (awaiting human lift decision)

| Field | Value |
|---|---|
| Authorization | G-LOG-0026 (targeted supplementary read and correction, audited, before S4 closes) |
| Recorded | 2026-09-24T19:40Z–21:40 local, by the AI session |
| Output | `ledger-p3b-r2/S4-R22-SUPP/PB05/step-verify-correction.json`; reader log `ledger-p3b-r2/S4-PILOT-R2-003/READ-LOG.jsonl`. R2-002 records and the quarantine marker unchanged |
| Result | lexical `BIRTH-UNRESOLVED-MTIME-ONLY[S1513]` and conceptual `NOT-EVIDENCED-IN-CAPTURE` (the audit's correction, confirmed); formal, governance and operational unchanged; 12 supplementary timeline points; absences and statuses unchanged; one escalated note (S1544 / assumptions) |
| Check and audit | `audit-p3b/20260924_2140_s4-pb05-supplementary-audit.md`: mechanical check clean; §21 audit **LIFT-WITH-NOTES** (N1: S1551 position; N2: S1516/S1513 order mtime-only; S1544 escalation for the human) |
| Next | human decision on lifting the quarantine and on the S1544 escalation; then S4 closure |

---

## G-LOG-0030 — PB05 quarantine lifted with notes; S4 CLOSED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24 (choices "Lift with notes", "Leave + backlog", "Close S4") |
| Recorded | 2026-09-24, by the AI session |
| Quarantine | step-verify-programme: **LIFTED-WITH-NOTES** (lift line appended to `ledger-p3b-r2/S4-R22/QUARANTINE.jsonl`). The births in `ledger-p3b-r2/S4-R22-SUPP/PB05/step-verify-correction.json` and the R2-002 timeline plus the 12 supplementary points are authoritative for S5. N1 (S1551 belongs before S1566) and N2 (S1516/S1513 order mtime-only) are recorded. PB05 is thereby **ACCEPTED-WITH-NOTES** |
| S1544 | the accepted absence (assumptions: GENUINELY-UNDEFINED-AFTER-CENSUS) stands for S4; no record rewritten |
| **Final S4 state (accepted)** | PB06 ACCEPTED-WITH-NOTES · PB01 exploratory, protocol-divergent FOUNDs (G-LOG-0021) · R2-001 PB02–PB05 FAILED, archived as non-accepted research observations · R2-002 PB02–PB05 ACCEPTED-WITH-NOTES (G-LOG-0025, this entry) · verifier corrections accepted (G-LOG-0026) · run-level rulings F1–F3 (G-LOG-0027) · governance note G-LOG-0028. **Nothing promoted.** **S4 is CLOSED** |
| Post-S4 backlog (carried, not yet authorized) | (1) S1544 as a candidate `assumptions` supply for step-verify-programme (P1-gap, found via step-1 reading); (2) step-verify operational rows from S1513 onward with a null P1 operational candidate (P1 candidate-assignment gap); (3) §11.4 has no rule for dimension-supplying material in a non-hit file when the census had hits (S5 observation); (4) A.4 variant gaps: ASCII→Unicode/LaTeX, accents, placeholders, and uncovered wordings such as "Theory Verification Session"; (5) §14.2 rule-4 wording; (6) P1 mtime defects (66 MTIME-basis dates); (7) UTC vs local dates, including in-text local times against UTC mtimes (N1); (8) P0 cited-date errors in 02-FILES (S1538); (9) the S5 contract and verifier must carry F1–F3; (10) the resolver's dependency on `git rev-parse` when run outside the repository |
| Next | S5 plan (S5a/S5b; S5c separate, hold-out sealed), drafted for human approval before any S5 execution |

---

## G-LOG-0031 — Stop-the-line before S5 (measured load); S5 Feasibility Decision Record

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24: "Stop-the-line", then direction to produce a bounded feasibility comparison before any amendment draft ("Do not thin, parallelize, or alter the frozen procedure ad hoc … Then obtain the human decision") |
| Recorded | 2026-09-24T22:44 local, by the AI session |
| Basis | §19.4 ("If the measured stage-2 load is not feasible, the stop-the-line rule applies (§23) and a revised annex is presented. The absence procedure is never quietly thinned to fit"); §23 (stop-the-line on human instruction) |
| Measurement | full S5 over 1,975 discovery labels: 1.45 GB stage-2 reading, 60,860 stage-2 file reads, **3.45 M dispositions**. The model reproduces R2.2 exactly (6,081). Load concentration: the top 100 labels carry 94.2% of dispositions; the median label needs 20 |
| Record | `audit-p3b/20260924_2244_s5-feasibility-decision-record.md` (options O1–O5 with cost, claims, non-claims, amendment need, H-19/S5c effect, reproducibility); script `scripts/p3b_s5_feasibility_cost.py` |
| Finding from the addendum text | the S5c baseline needs n_s ≥ 10 per stratum from the discovery labels' S5 records; sampled S5 designs raise the risk of NOT-SCOREABLE |
| State | S5 **not started**; no option adopted; no frozen text changed; H-19 untouched. Gated behind the choice: H-12, OMQ-16, H-11a–d, OMQ-07, H-16, H-15, H-17, H-18, OMQ-17, OMQ-18 |
| Next | the human chooses the S5 architecture; then any amendment follows §26 |

---

## G-LOG-0032 — S5 architecture O5, hub treatment (a), chosen; threshold not yet chosen; sensitivity analysis

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24: "Choose O5 with hub treatment (a)"; the human's accompanying review asks that O5 be chosen as architecture only, with the threshold pre-registered from S3-only data, a sensitivity analysis, the §23 semantics checked, and a minimal v1.7 only if needed |
| Recorded | 2026-09-24, by the AI session |
| Sensitivity | feasibility record §5; `audit-p3b/S5-FEASIBILITY-COST.json`. Rules T1–T5: 19 to 105 hubs; all 18 discovery-side baseline cells keep n ≥ 10 under every rule. Recommendation: T4 (> 4,081, the largest S4-audited per-label load), giving 66 hubs, 1,909 labels processed and 283,576 dispositions |
| Semantics finding | §22 has no load condition, and §19.4 prescribes "a revised annex". The route is therefore a **minimal v1.7**, correcting the record's "may need only a run-level ruling". Variant (a′) (hub processed, stage 2 ESCALATED-LOAD) is presented for decision, not substituted |
| Correction | feasibility record §3 O1 sentence replaced (it had no measured basis). The 1.45 GB figure is confirmed; no artifact contains 31.45 GB |
| State | no threshold frozen; no frozen text changed; S5 not started; H-19 untouched |

---

## G-LOG-0033 — S5 hub threshold T4 and hub treatment (a′) chosen; hub list frozen; minimal v1.7 authorized for drafting

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-24 (choices "T4 >4,081", "Switch to (a′)", "Draft minimal v1.7") |
| Recorded | 2026-09-24, by the AI session |
| Threshold | **T4**: a discovery label is a hub when its predicted stage-2 load exceeds 4,081 dispositions, the largest single-label load processed and audited in S4 R2.2. S3-only; chosen before any S5 result |
| Hub list | `P3B-S5-HUBS.jsonl` by `scripts/p3b_s5_hub_list.py` (deterministic; 66 hubs of 1,975; body sha256 `58aed960…`) |
| Hub treatment | **(a′)** replaces (a) of G-LOG-0032: a hub label receives the per-label procedure; stage 2 is not performed; every hit-bearing absence dimension resolves ESCALATED with a `LOAD` escalation; no hit is sampled |
| Route | minimal core **v1.7** (§22 has no load condition; §19.4 prescribes a revised annex). Draft → freeze check → independent confirm → human freeze |
| For the human at freeze | v1.7 §11.4 states that a hub's label-level absence predicate is **undetermined** for the H-19 baseline. This reads the approved addendum's "undetermined"; approving v1.7 approves that reading |

---

## G-LOG-0034 — FREEZE of the P3b operating protocol (core) v1.7

| Field | Value |
|---|---|
| Decision id | **H-01** (approve the annex, §26 item 2) for the core protocol **v1.7** |
| Decider | the human project owner, 2026-09-24, choice "Freeze v1.7" |
| Recorded | 2026-09-24, by the AI session |
| Basis | G-LOG-0031 (stop-the-line), G-LOG-0032 (architecture O5), G-LOG-0033 (threshold T4, hub treatment a′, route v1.7); independent confirm-only pass: round 1 DEFECTS (1 blocker: H-19 representation of hub absence predicates; 8 minors), round 2 **`VERDICT: CONFIRMED`** on the file below |
| Content | the S5 load rule (§19.4; hub = Tier-U/Z discovery label with predicted load > 4,081 dispositions; `P3B-S5-HUBS.jsonl`, 66 labels); the §11.4 hub exception (stage 2 incl. 2A/2B not performed; hit-bearing dimensions ESCALATED with reason `LOAD`); §22 row; §23 item 3b claim scope (A/B, not C); §13.4 `NOT-FOUND-LOAD-ESCALATED`; §11.2, §15, §19.2, §9.8 alignments; D-76; §44 |
| Approved reading | for the H-19 baseline, a hub label's label-level absence predicate is `UNDETERMINED` exactly when the label is listed in `P3B-S5-HUBS.jsonl` (addendum v1.5 unchanged) |
| Recorded notes (not defects, not applied) | N1 the §11.4 P1-GAP sentence would better cite the "Found material" paragraph; N2 the header does not name the new §13.4 value (§44 does); N3 §11.2 row order |
| Supersedes | v1.6.6 (`prompts/20260924_1853_p3b-phase1-continuation-protocol-v1.6.6.md`, frozen G-LOG-0022), kept unchanged |
| Frozen artifact | `docs/knowledgeos/chronological-read/prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md` · sha256 `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12` · git blob `a176851feccb38e3eac1bb0baa357f52a8cd1287` · 3257 lines · sections 1–44 · D-01…D-76 |
| Freeze integrity check | `scripts/p3b_freeze_check.py` blob `bcc32b80d73c6982b9bf089c0760926d292cd749`: **PASS** (rules 1–8 clean) |
| Next | the S5 plan under v1.7 for human approval: H-12 (load-based batching, OMQ-16), H-11a–d, OMQ-07, H-16; then H-15, H-17, H-18, OMQ-17, OMQ-18 before S5a/S5b; S5 contract and verifier carrying F1–F3 and the hub exception. S5 not started; H-19 sealed |

---

## G-LOG-0035 — S5 plan submitted for human approval (planning entry; no decision recorded)

| Field | Value |
|---|---|
| Commission | the human's S5 planning-gate instruction, 2026-09-24: produce the complete S5 plan and decision sheet, and do not execute |
| Recorded | 2026-09-24T23:30 local, by the AI session |
| Plan | `audit-p3b/20260924_2330_s5-plan.md` (sections A–P) |
| Decisions proposed, **not taken** | H-12, OMQ-16, H-11a, H-11b, H-11c, H-11d, OMQ-07, H-16, H-15, H-17, H-18, OMQ-17, OMQ-18, plus the listed operational parameters |
| Verified at planning | v1.7 sha256 `38021aa4…502d12` (the commission text's `…502d1` is truncated); hub list sha256 `e150d7a5…`; seal state SEALED |
| State | S5 not started; no executable S5 code written; H-19 sealed; v1.7 and the hub list unchanged |

---

## G-LOG-0036 — S5 plan red-team review: DEFECTS (3 blockers, 18 majors, 17 minors)

| Field | Value |
|---|---|
| Commission | the human's instruction, 2026-09-24/25: "use a subagent to review your plan" |
| Recorded | 2026-09-25T00:10 local, by the AI session |
| Review | `audit-p3b/20260925_0010_s5-plan-red-team-review.md`: measured figures reproduce; design defects in H-12 (audit sampling vs §21; hard caps vs §20; false checklist anchor), OMQ-16, H-15, H-17, H-18, §K, the H-19 checks and §O. Sound as proposed: H-11a–d, OMQ-17, OMQ-18, the H-16 value, and OMQ-07 once its rationale is corrected |
| State | the plan of G-LOG-0035 is **not approvable as submitted**; no decision recorded; S5 not started; H-19 sealed |

---

## G-LOG-0037 — S5 plan v2.2: red-team re-review rounds 2–4, VERDICT SOUND (submitted for human decision)

| Field | Value |
|---|---|
| Commission | the human's choice "Revise everything at once" (after G-LOG-0036) |
| Recorded | 2026-09-25, by the AI session |
| Plan | `audit-p3b/20260925_0001_s5-plan-v2.md` (v2.2), sha256 `f4b398eff0dadf09bd68f9f3d36db5b82018c289597791e67bd80740d8a2e683`; supersedes v1 (G-LOG-0035), kept unchanged. Measurements: `scripts/p3b_s5_plan_measures.py` → `audit-p3b/S5-PLAN-MEASURES.json` (§19.5-style header; 36 inputs; reproduces byte-for-byte) |
| Review rounds | round 2: DEFECTS (0 blockers, 2 majors: controls circular/infeasible; OMQ-07 option (3) beyond scope; 10 minors) · round 3: DEFECTS (1 blocker: LOAD skip on mandatory CORPUS tests; 5 minors) · round 4: **`VERDICT: SOUND`** |
| Correction found by the AI session during revision | batches are packed by the frozen §9.4 weight within tier, with the H-12 limits on top (§19.4) |
| Recorded notes (not applied) | the human's budget authorization for an over-load CORPUS test must cover the complete test; report actual vs predicted CORPUS-test reads; the MDD level K = 63 becomes 76 if G-TIMELINE-SIM is kept |
| State | **no decision recorded**; the decision sheet (§P) awaits the human; S5 not started; H-19 sealed |

---

## G-LOG-0038 — S5 plan v2.2 pre-execution certification: NOT READY (0 blockers, 5 majors)

| Field | Value |
|---|---|
| Commission | the human's pre-execution certification brief, 2026-09-25 (no rewrite; for each blocker or major: finding → rule → evidence → minimum correction → consequence → human decision) |
| Recorded | 2026-09-25T00:47 local, by the AI session |
| Review | `audit-p3b/20260925_0047_s5-plan-v2.2-certification.md`. Every measured figure reproduces; frozen items respected; H-19 static audit holds |
| Majors | (1) incomplete H-17 test specification, including the role-asymmetric RC-13 "not scored"; (2) validity under control reuse not established (simulated size up to ≈ 6× nominal at q/K with small pools); (3) unargued IDENTITY-STATE-CONFLATION classification, inconsistent emptiness rule, G-TYPE-SIM misclassified as AI-input; (4) missing provenance links between test, p-value, multiplicity and register; (5) OMQ-07 option (3) as a run-level ruling would change D-23 outside change control (§26) |
| Correction of an earlier position | the plan's (and the red-team reviewer's) acceptance of a run-level route for OMQ-07 option (3) is withdrawn: v1.7 provides rulings only for gaps, not for refining a frozen rule |
| State | no decision recorded; plan unchanged; S5 not started; H-19 sealed |

---

## G-LOG-0039 — Human decisions on the S5 v2.2 certification majors

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-25: dialog choices, plus the written instruction "Proceed with the certification findings, but do NOT execute S5 yet" |
| Recorded | 2026-09-25T01:06 local, by the AI session |
| Certification | accepted as recorded: `CERTIFICATION: NOT READY — 0 BLOCKERS, 5 MAJORS` (G-LOG-0038) |
| **MAJOR-1** | **symmetric removal.** UNDETERMINED is not 0. An undetermined or unscored candidate removes the whole matched set from that cell; an undetermined control is removed from its set; a set with no control left is removed; attrition reported by role. Complete the H-17 specification (H0, statistic, weighting, set bands, missing outcomes, has-formal-rows, exact p, seeds and streams, raw p into BH, p = 1 for untested cells). The test is not to be called a randomization test; assumptions stated explicitly |
| **MAJOR-2** | **option (c): the frozen label-cluster permutation alternative** (§9E.2 item 6). The control sampling design is not changed to reduce reuse; no new post-hoc significance threshold. Specify unit, clusters, what is permuted and fixed, multi-set clusters, repeated controls, statistic, p (exact or Monte-Carlo), seeds, BH input. Reproduce the control-reuse simulation as design evidence, not as tuning |
| **MAJOR-3** | **no decision yet.** Recover the authoritative meaning of IDENTITY-STATE-CONFLATION from the frozen text; establish one outcome-independent registration rule (structurally impossible / possible but empty / underpowered / tested); then present the semantic decision and its K and BH consequences. K is a consequence of the ontology, not a target. (The earlier dialog choice "not an identity class, K = 64" is treated as provisional until the human decides on the analysis) |
| **MAJOR-4** | **mandatory correction:** the full statistical provenance chain (family, generator, candidates, controls, matching, post-hub pool, per-set outcomes, statistic, raw p, multiplicity family, adjusted result, register record, result hash); `audit-p3b/S5A-CELL-RESULTS.json` with a §19.5 header; every CROSS-OBJECT / CORPUS claim traceable to it |
| **MAJOR-5** | **mapping (2) for S5.** The "run-level ruling" for option (3) is not used. Option (3) only via a formal v1.8 change proposal frozen before any affected execution |
| Methodological instruction | do not optimize for power, cell count, workload, convenience, K = 63 or an expected result. The order is frozen ontology/protocol → evidence → semantic model → statistical design → execution |
| Next | S5 plan v2.3 as a controlled amendment; checks; change matrix; revised decision sheet; hash; governance entry; then stop. **No S5 execution** |

---

## G-LOG-0040 — S5 plan v2.3.2 (controlled amendment) checked: PASS; submitted for human decision

| Field | Value |
|---|---|
| Commission | the human's instruction of 2026-09-25 (G-LOG-0039): v2.3 as a controlled amendment; checks; change matrix; revised decision sheet; hash; governance entry; stop |
| Recorded | 2026-09-25, by the AI session |
| Plan | `audit-p3b/20260925_0106_s5-plan-v2.3.md` (**v2.3.2**), sha256 `f89a16824efaeea08d54fbc857b4cf36184cb698deb482550026fe194b3c7de1`, git blob `10f8b3bef46a7be62cd13da0308464b779c61636`. The v2.2 certified object is kept unchanged |
| Evidence | `scripts/p3b_s5_plan_measures.py` → `S5-PLAN-MEASURES.json`; `scripts/p3b_s5_control_reuse_sim.py` → `S5-CONTROL-REUSE-SIM.json` (design evidence: the matched-set test inflates under reuse, up to 0.093 at 0.05 and about 5× at q/K; the label-cluster test is conservative where computable). Both reproduce byte-for-byte |
| Checks | independent checker, round 1: FAIL (1 major: H-17 arity dropped; minors) → round 2: **PASS** (minors) → confirm pass on v2.3.2: **PASS**. The exact p was verified by brute force (max difference 3.3e-16 over 300 structures); provenance chain and H-19 static protection checked |
| Change matrix | plan §R (v2.2 → v2.3), §R2, §R3 |
| Recorded notes (not applied) | the "K = 63" BH-power attribution is to the certifier's simulation, not the stored certification text; name `P3B-INPUT-MANIFEST.json` and `P3B-STATE.json` in the allowlist explicitly; the m_inf ≥ 1 gate is redundant given x_min ≥ 3 |
| Pending human decisions | **MAJOR-3 semantics**: IDENTITY-STATE-CONFLATION not an identity class → K = 64 (or identity class → K = 63); plus the §P decision sheet (H-12, P-1, flags, model rule, OMQ-16, H-11a–d, H-16, H-15, H-17, H-18, OMQ-17, OMQ-18, §K) |
| State | S5 not started; H-19 sealed; v1.7, T4, the 66 hubs and treatment (a′) unchanged |

---

## G-LOG-0041 — MAJOR-3 decided (K = 64); S5 §P decision sheet approved; authorization limited to infrastructure construction and review

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-25 (written instruction "We have now reached the next formal S5 gate …") |
| Recorded | 2026-09-25, by the AI session |
| Basis | S5 plan v2.3.2 (`audit-p3b/20260925_0106_s5-plan-v2.3.md`, sha256 `f89a16824efaeea08d54fbc857b4cf36184cb698deb482550026fe194b3c7de1`): independent checks PASS and confirm pass PASS (G-LOG-0040). Verified: exact p against brute-force enumeration (max difference 3.3e-16 over 300 structures); the provenance chain; H-19 static protection. Certification majors resolved per G-LOG-0039 |
| **MAJOR-3** | **IDENTITY-STATE-CONFLATION is NOT an identity class; K = 64.** Grounds, from the frozen text: §1D and §9D (a per-label, object-scale question), §13.7 (one object's identity conflated with its state representations), RC-13 (sameness or identity claims **between** members). EQUIVALENCE-CLASS is the identity class. K follows from the ontology, not from power, workload or expected outcome |
| **§P approved (human-approved S5 parameters)** | MAJOR-1 symmetric removal · MAJOR-2 label-cluster permutation test (frozen §9E.2 item 6 alternative) · MAJOR-3 K = 64 · MAJOR-4 full statistical provenance chain · MAJOR-5 OMQ-07 mapping (2) · **H-12** L_max = 5, checklist ≤ 4, predicted dispositions ≤ 4,306, predicted stage-2 ≤ 8.63 MB, step-1 ≤ 1.80 MB, as manifest-time limits, not verifier gates · **P-1** re-run all 30 pilot labels under S5; S4 outputs immutable comparison evidence · **orchestrator flags**: discovery-filtered, quarantine-scanned extract, if the quarantine gate passes · **model** claude-opus-5-5, exact served id and generation parameters recorded · **OMQ-16** fixed 20% non-CORPUS share, no post-outcome lowering, CORPUS tests full with human escalation over the frozen limit · **H-11a–d** (iii)/(ii)/(i)/(i) · **H-16** N_support = 2 with the independence reporting fields · **H-15** five first-pass generators (G-TIMELINE-SIM excluded), 14-class vocabulary, K = 64, deterministic mapping and registration rule · **H-17** k ∈ {2, 3}, exact arity, r = 2, label-cluster permutation test, exact p, symmetric removal, registered-cell family and BH as in v2.3.2 · **H-18** cap 10 with the 50 listed files · **OMQ-17** records of H-06-accepted batches only · **OMQ-18** the five generators, SELF-DERIVED test-only · **§K** 97-label blind rerun plus 20% same-model re-analysis, comparison only, never feeding cells. All other parameters exactly as listed in plan v2.3.2 §P. **No parameter may change during infrastructure construction** |
| **Authorization scope** | **Build and validate the S5 infrastructure (plan §O) only.** Each component is implemented, tested (unit/invariant, determinism, refusal/negative) and hashed, with a stop on any failed gate. **This is NOT authorization to execute S5 corpus batches:** OB0004 and all S5 batches, S5a/S5b research and S5c remain unauthorized. H-19 stays sealed |

---

## G-LOG-0042 — Independent S5 infrastructure review: NOT VERIFIED (0 blockers, 4 majors); H-19 exposure incident for human decision

| Field | Value |
|---|---|
| Recorded | 2026-09-25, by the AI session |
| Review | fresh independent read-only agent over the committed S5 infrastructure (commits cbf8e9bd9 … d52e87dd8) |
| Verified | **population** (1,975 labels = sample plan; 66 hubs; hub body hash); **partitioning** (independent FFD recomputation: 0 differences over 396 = 382 + 14 batches; all limits; no tier or hub mixing); **load model** (recomputed for all 1,975 labels; all 396 batch sums match); **H-17** (exact p equal to brute force on 600 + 200 structures; independent end-to-end reimplementation 0 mismatches on 300; K = 64; BH family = registered cells; generator order = §9E.1 table order); **contracts** (all verbatim sections byte-identical); **determinism** (`--check` IDENTICAL; OB0004 and hub batch OB0386 materialized to /tmp with all hashes matching); all test suites pass |
| **Majors** | **M1** the allowlist is wider than plan §M item 6 (prefixes `20-FAMILIES/`, `prompts/`, `audit-p3b/` and the census flags file admit files carrying hold-out names or ids); family `.md` slice inputs need a narrow, human-approved entry · **M2** the quarantine scanner prints and stores file paths, which for per-label files are label names · **M3** the pass contract's disposition vocabulary differs from the engine's (YES/NO vs RECORDED/NOT-RECORDED; an omitted class read as 0 contradicts "UNDETERMINED is not 0") · **M4** blinding: the pass contract gives analysts full object records (dependency edges, timelines: defining links of G-DEPENDENCY / G-COCHANGE); the blind file exposes the co-change source pointer; the token rule never inspects pointers or object records, and the G-TYPE-SIM / G-COCHANGE tokens make it vacuous |
| **H-19 exposure incident (for human decision; addendum §6)** | While testing the allowlist (M1), the reviewer ran the committed quarantine scanner over the whole `20-FAMILIES/` directory. Its path output (M2) printed the file name of one family `.md` of a label **not in the discovery index**, **probably a hold-out label name**, into the **reviewer agent's tool output**. The name is **not** in the review report, in any repository artifact or commit, or in the orchestrator's or any S5 agent's context. The reviewer produced no research records and has ended. Under addendum §6 a hold-out record reaching an agent input is a seal breach; **the human decides whether predictions may still be scored** and whether a P3B-ESC entry is required. Causes are fixed under M1/M2 |
| Human rulings requested | (1) the incident above; (2) a family `.md` allowlist entry `20-FAMILIES/<S5-population label>.md` (§9.8 step-1 input, omitted from the plan's allowlist); (3) whether recording §6 quarantine by counts (source left in place, text excluded from agent inputs) satisfies §6 "moves"; (4) whether an S-id **range** notation (e.g. S0133–S0138) spanning hold-out ids counts as citing them (16 slices carry such ranges spanning 42 hold-out ids; contracts and flags extract carry none); (5) parameters not set by the plan: the G-TYPE-SIM keyword table S5A-TYPE-SIM-TABLE-1, the control draw "relax only when the pool is empty; r_s = 1 when no disjoint second control", the stability bootstrap B = 2,000 and re-analysis rounding (ceil), the audit floor max(5, floor(0.1·N)); (6) the operational run id `OA####-R2` for S5a candidate-check reads |
| State | fixing M1–M4 and minors now (infrastructure only); S5 not started; H-19 sealed |

---

## G-LOG-0043 — S5 infrastructure: round-2 review and confirm pass — VERIFIED EXCEPT the M4 residual (human ruling); S5 execution NOT started

| Field | Value |
|---|---|
| Recorded | 2026-09-25, by the AI session |
| Review chain | round 1 NOT VERIFIED (4 majors; G-LOG-0042) → fixes 1be95f075 and rebuilt artifacts d81010f99 → round 2: M1, M2, M3 RESOLVED; **M4 PARTIALLY** → minor fixes 145e8a2e4 → confirm pass: all six fixes hold → **`INFRASTRUCTURE: VERIFIED EXCEPT M4-RESIDUAL (HUMAN RULING) — READY FOR HUMAN DECISION`** |
| **M4 residual (open major; human ruling)** | in G-SHARED-GROUP (the one pre-S5 generator rated FULL), members share a source pointer in 177 of 530 candidate sets but only 20 of 1,024 control sets: a role cue in the blind file. The frozen §9E.2 item 6 token list (group id, notation/alias, edge endpoints) does not name shared source pointers, so FULL is correct under the literal rule. Options: (a) strip shared-source pointers for G-SHARED-GROUP (at least CO-OCCURRENCE groups), as is already done for G-COCHANGE; (b) accept FULL as the literal frozen outcome; (c) declare the generator PARTIAL. Measured on real pre-S5 data (in memory): G-NOTATION PARTIAL (97/240 sets), G-TYPE-SIM PARTIAL (573/866) → their 26 cells report-only by the frozen rule |
| Frozen artifacts (for recording before any dispatch) | manifest `_batch_manifest_p3b_r2.jsonl` body sha256 88b5b8b4… (396 batches, 1,975 labels) · batch contract `prompts/20260925_0257_p3b-agent-contract-r2.md` b9302363… · pass contract `prompts/20260925_0259_p3b-pass-contract.md` 22d79fca… · pass plan `audit-p3b/S5A-PASS-PLAN.json` output 6a572655… (PROPOSED) |
| Residual notes (not defects) | the evidence check trusts the header's self-declared script (the H-06 human decision remains the safeguard); audit_record does not enforce the §21 sample minimums (the auditor and the human do); pre-seal modules report 15 findings, outside the S5 scope and not loaded except the S3 module for the resolver and bundles |
| State | **S5 INFRASTRUCTURE BUILT AND INDEPENDENTLY REVIEWED; one open major (M4 residual) and the G-LOG-0042 rulings await the human. S5 EXECUTION NOT STARTED. H-19 SEALED.** OB0004-R2 requires a separate governance entry authorizing execution |

---

## G-LOG-0044 — S5 final human-decision package submitted (planning entry; no decision recorded)

| Field | Value |
|---|---|
| Commission | the human's instruction "Continue KnowledgeOS S5 from the current governed state … bring S5 to the FINAL HUMAN-DECISION GATE" (2026-09-25) |
| Recorded | 2026-09-25T03:23 local, by the AI session |
| Package | `audit-p3b/20260925_0323_s5-final-decision-package.md`, sha256 `b628f5f487d6db287309818ac751587616d2191bf0415be9d5e84543f578a6ba` |
| Re-verified for the package | suites (105 / 62 / 36 all pass); `prepare --check` IDENTICAL; guard seal SEALED, grep 0 in-scope violations, inputs allowlisted; 9/9 adversarial paths refused; pass plan / contracts / manifest / O-25 consistent with v1.7, plan v2.3.2, G-LOG-0041 and G-LOG-0043 |
| H-19 mechanical check (counts only) | no hold-out label name and no hold-out S-id in S5-era artifacts, commits, session log or CONTEXT block, except one pre-S5 line (G-LOG-0018 scope note on the Shapiro source); 0 materialized production slices; 0 S5 run directories; no research records; seal file unchanged since sealing (sha equal); SEALED |
| Decisions awaited | H-19 incident (A / B / C); M4 (A / B / C, analysed without recommendation); G-LOG-0042 items 1–9; pass plan O-12 and operations spec approval; recording the frozen hashes; execution authorization for OB0004-R2 |
| Changes to methodology or infrastructure | none |
| State | **S5 EXECUTION NOT AUTHORIZED — AWAITING HUMAN GOVERNANCE DECISION** |

## G-LOG-0045 — Human rulings after G-LOG-0044 recorded; S5 remediation (M4-A, M4-R1 scope A'1, range rule) complete; §26 annex; pre-release blinding gate registered

| Field | Value |
|---|---|
| Commission | the human's rulings after the G-LOG-0044 package (2026-09-25), given in sequence in the S5 session |
| Recorded | 2026-09-25, by the AI session; the decisions are the human's (H-01 class, §26.2) |
| Ruling H-19 | **A — seal breach.** H-19 stays SEALED (HS-3d32dd44d162). There is no unseal, no S5c scoring, no use of H-19 predictions and no hold-out material in the production path. Reporting is counts and booleans only. The escalation is `P3B-ESCALATIONS.jsonl` P3B-ESC-0001 (sha256 `3ab163ab…`). **It quarantines H-19/S5c only; it does not block S5 production.** S5c requires a separate human decision |
| Ruling M4 | **A:** strip shared-source pointers from G-SHARED-GROUP candidate and control material (implementation of frozen §9E.2 item 4 / A.7). The item 6 token rule, K and the cells are unchanged |
| Ruling items 1–9 (G-LOG-0042) | 1. family `.md` files readable only for `s5-slice-construction` · 2. quarantine recorded as counts only · 3. strict S-id range rule · 4. keyword table `S5A-TYPE-SIM-TABLE-1` frozen · 5. control draw: relax only when the pool is exhausted; r_s = 1 if one control; NO-CONTROL-AVAILABLE if none; no adaptation · 6. bootstrap B = 2,000 · 7. N_reanalysis = ceil(0.20 × N) · 8. audit_floor = max(5, floor(0.1 × N)) · 9. audit run id `OA####-R2` |
| Ruling K | **64, frozen.** ISC is not an identity class |
| Ruling O-12 / ops spec | approved subject to the M4 remediation. The pass plan binds by the output_sha256 below |
| Ruling range semantics | `/`, `:` and arrows are **not** S-id ranges: quarantine only notation with sufficient semantic evidence of an interval. Explicit interval forms remain ranges: dashes, dot runs, to/through/thru/until/till/up to/bis/bis zu, between … and. The production population is unchanged |
| Ruling M4 residual | **A: R1 + cap 1**, described as removing the measured pointer-derived role cue, not as proof of complete blinding |
| Ruling M4-R1 scope (§26 change) | **A'1 APPROVED.** R1 + cap 1 applies symmetrically to every candidate/control unit across all generators; M4-A stripping stays G-SHARED-GROUP only. Annex: `audit-p3b/20260925_0905_s5a-m4r1-blinding-annex.md` (commit `5c7ea592c`; §5 registered-condition update in `bd6e2eb57`). **Why the G-SHARED-GROUP-only scope was rejected:** the cap made family membership visible (AUC 0.994), and 80/87 G-NOTATION candidate units vs 4/149 control units are family units. That gave a G-NOTATION role cue of **AUC 0.896**, independently reproduced. **A'1 measurements** (pre-S5 design data): best AUC 0.502 / 0.510 / 0.506 (SG / NOTATION / TYPE-SIM); 149 units withheld; SHARED-INVARIANT m 217 / 52 / 41; one pointer per member for every unit |
| Final M4-R1 identity | `rule` = `R1 + cap 1` · `cap` = 1 (integer) · `scope` = `all candidate/control units across all generators` · `withheld_units` and `withheld_sets_sha256` (sha256 over UTF-8 of `canon` of the sorted withheld member lists) are produced with the actual withheld set. The identity is bound, with hard failure on any mismatch, in the blind header, the reveal header, and the PRE-ANALYSIS and LINK6 pass-record entries. Design-data rehearsal (tmp only): 149 units, `c53f2709cb4fb938c3a8773111edb68d9c4a13a1252b8deec5d579d348842a07` |
| Ruling pre-release blinding gate (registered) | **For every generator with a tested S5a cell, the best measured reviewer-visible feature AUC on the actual S5a pass inputs must be ≤ 0.55.** It is a blinding-integrity gate, not a significance threshold. It uses the registered features and AUC on the actual pass snapshot, with no S5 outcomes. The threshold is never tuned, and populations and cells are never modified to pass. If any tested generator exceeds 0.55: STOP and return the measurement for a human ruling. Testable at pre-release time = blinding FULL and pre-reveal m ≥ 20. Implemented as `scripts/p3b_s5a_m4_residual.py gate`, which writes `audit-p3b/S5A-BLINDING-GATE.json` and a frozen pass-record entry |
| Remediation commits | `953754457` M4-A, range rule, scoped allowlist, freezes, P3B-ESC-0001 · `338519ca5` regenerated batch contract and manifest · `d55a6294f`, `b7f9ac4b2`, `4e52d4b25` range matcher (reviews rounds 1–3; human range ruling) · `cb8256fc3`, `57ac0dc1e` M4 residual analysis · `60610fc6d` R1 + cap 1 · `89d9ad382`, `185fdd7b1` M4-R1 identity binding (with scope) · `ed1a0d06c` scope package · `5c7ea592c` §26 annex · `5a295c336` A'1 engine · `3c33b01cf` A'1 review minors · `1dbd841ef`, `726a587de` pass plan and contract · `bd6e2eb57` registered pre-release gate · `c0571cbd0` pass plan and contract with the gate |
| Independent reviews | infrastructure M4-A: CONFIRMED WITH OBSERVATIONS · range finding: CLOSED (after rounds 2–3 and the human range ruling) · M4 residual analysis: CONFIRMED WITH OBSERVATIONS · scope package: REPRODUCED WITH OBSERVATIONS · A'1 implementation: CONFIRMED WITH OBSERVATIONS (every number reproduced with independent code). No blockers; minors fixed |
| Governing hashes | batch contract `prompts/20260925_0351_p3b-agent-contract-r2.md` sha256 `c175d41144f0161792d6b734fd0296e450e64801d6be0e2067190b470b85167c` · manifest `_batch_manifest_p3b_r2.jsonl` file `0c5e95e23e2c4de7d1c93c572dab8c0e9b14df896ba64d9cf3ae4c5b82aa603d`, body output `97dd221067044cb9493c8ae8bc2357d50f6c9248f541a340740e409173aa457d` (396 batches = 382 + 14 hub; 1,975 labels once) · pass plan O-12 output `97279830533d1fcee8f7e129468940af39095bdf4f69fb9bba653e925b7c64ae` · pass contract `prompts/20260925_0937_p3b-pass-contract.md` `816d81f3aed19e100088f84941f07496210698dbcda7315ed97b6897f3d91b9b` · O-25 output `4b55017e5ac2e03b210792bece60534b2900a8b50ee138d580f1617e169e1fcf` · seal file `9b99169d…` (unchanged) · quarantine `d0d47e9a…` (unchanged) |
| Invariants | K = 64 and the 64 registered cells unchanged; no S5 outcome exists or was accessed; H-19 SEALED, never accessed; S5c PROHIBITED |
| State | rulings recorded. **OB0004-R2 is not authorized by this entry**: authorization follows the §18 gate as a separate entry. The S5a blind file is released only through the registered pre-release gate |

## G-LOG-0046 — §18 final pre-execution gate PASSED; OB0004-R2 AUTHORIZED (S5 production lane only)

| Field | Value |
|---|---|
| Commission | human instruction: "After G-LOG-0045: 1. Run the §18 gate. 2. If §18 passes, create the separate governance authorization for OB0004-R2. 3. Do NOT interpret that authorization as release of the blind artifact itself" (2026-09-25) |
| Recorded | 2026-09-25, by the AI session, on the human's conditional authorization |
| §18 gate, structural (25/25 pass) | H-19 SEALED (HS-3d32dd44d162); seal and quarantine files unchanged; S5c PROHIBITED (P3B-ESC-0001 OPEN; pass plan h19); manifest output `97dd2210…`; population 1,975 labels once, equal to the S5 population; 396 batches (382 non-hub + 14 hub; first OB0004, first hub OB0386); 66 hubs, all in hub batches, and 1,909 non-hub labels; batch contract `c175d411…` equals the manifest's and every batch's contract hash; one S5 batch contract; one pass contract, which embeds the pass-plan output; pass plan output `97279830…`, bound to the frozen protocol and plan hashes; K = 64 and the registered cells equal the engine's; M4-R1 identity A'1 equals `M4R1_APPROVED`; pre-release gate registered (0.55); O-25 committed output `4b55017e…`; pass-plan engine blobs current; no production slice, no S5 run, no state file, no S5a artifact in the repository; allowlist refusals 16/16; quarantine 0/0 over the agent-facing inputs (batch contract, pass contract, flags extract) |
| §18 gate, runtime | all 17 S5 test files pass (s5a cli 3, mechanics 21, real 2, refusal 8, stats 7, snapshot OK; ops audit_sample, freeze, guard, quarantine, read_source, stability, state, testpass OK; audit_record, quotes, verify OK) · guard seal SEALED, grep 49 files 0 in-scope violations, inputs allowlisted · `prepare --check` IDENTICAL · quarantine scanner on the flags extract 0/0 over 2,627 records · git status identical to the session baseline (241 entries, all unrelated; nothing uncommitted under chronological-read) |
| Result | **§18 PASSED** |
| **Authorization** | **OB0004-R2 is AUTHORIZED** for execution under the operations-spec §6 runbook with the governing hashes of G-LOG-0045. OB0004 is the first manifest batch (tier U, non-hub, 5 labels). The manifest is frozen from this dispatch onward |
| Not authorized | H-19 unseal, access or scoring; S5c; use of hold-out predictions; release of the S5a blind artifact (only through the registered pre-release gate on the actual pass inputs); any batch other than OB0004; acceptance of OB0004 (the H-06 decision is the human's, step 9) |
| State | OB0004-R2 execution may proceed to AUDITED; it stops at the human H-06 acceptance |

## G-LOG-0047 — OB0004-R2 executed; verification FAIL (two contract defects, two agent issues); batch FAILED; human decision required before any re-run

| Field | Value |
|---|---|
| Commission | G-LOG-0046 authorization; operations-spec §6 runbook |
| Recorded | 2026-09-25, by the AI session |
| Execution | steps 2–7 run. Guard seal / grep / inputs pass; `p3b_s5_state.py init` (396 PREPARED); `--materialize OB0004` (5 slices verified against the manifest; quarantine 0/0); DISPATCHED; one agent (claude-opus-5-5[1m], no helpers, scratch `/tmp/p3b-s5-scratch/OB0004/`) under contract `c175d411…`; PROPOSED; verifier; quote checker; refusals; FAILED |
| Batch output | `ledger-p3b-r2/OB0004-R2/`: 5 objects, 36 research records, 19 P1-gap records; 58 reader calls, 0 refused; step-10 bytes 280,541 of 900,000; output quarantine 0/0 over 118 records |
| Verifier `audit-p3b/S5-VERIFY-OB0004.json` | **FAIL**, with four kinds: (1) **SCHEMA ×19**: every P1-gap record carries `generation_parameters`, which the P1-gap schema does not allow, while contract §B says "record your exact served model id string and generation parameters on every record" (**contract defect**: preamble vs schema); (2) **G-04 ×4**: the agent wrote raw-hit `hit_key` offsets as strings (e.g. `"4278"`) where the verifier compares integers. By value every expected key is covered (e.g. 33 of 33), and the contract does not state the type (**contract ambiguity**); (3) **G-12 ×3**: layer-A text contains "see the register" (**agent error**); (4) alerts ×3: step-10 reads of files outside the label's own slice (agent deviation, for the auditor) |
| Quotes `audit-p3b/S5-QUOTES-OB0004-R2.json` | 174 exact, 6 whitespace, **1 miss** → FAIL (**agent error**) |
| Self-reported deviation | one `ls` of the parent `ledger-p3b-r2/` directory (contract §A.3 forbids listing); nothing opened |
| State | **OB0004 FAILED** (evidence: the verify report). No audit, no acceptance. H-19 SEALED, never accessed; S5c PROHIBITED; no S5a artifact |
| Decision required (human) | Re-running as `OB0004-R2.2` under the same contract would probably reproduce defect (1), because the agent follows §B literally, and leaves (2) to chance. Correcting the contract (P1-gap schema, `hit_key` type) is a mid-execution change (§26.2: it applies only to batches dispatched after it). It changes the contract hash, and therefore every slice hash and the manifest body. The composition would be unchanged, but §6.1 froze the manifest from the first dispatch. Options: **(a)** contract v-next with the two clarifications, the manifest regenerated (composition unchanged, verified), a re-run `OB0004-R2.2` under it, and OB0004-R2 kept as the failed record under its own contract; **(b)** re-run under the unchanged contract, accepting the likely repeat of (1); **(c)** another remedy. The verifier is **not** loosened after the fact |

## G-LOG-0048 — §26 batch contract revision 2 (two contract defects from OB0004-R2); manifest regenerated, composition identical; OB0004-R2.2 authorized

| Field | Value |
|---|---|
| Commission | human ruling: "approve option (a) — correct the contract and rerun as OB0004-R2.2" (2026-09-25) |
| Recorded | 2026-09-25, by the AI session; the decision is the human's (§26.2) |
| Motivating evidence | G-LOG-0047: OB0004-R2 verification FAIL, where two failure kinds were contract defects: (1) contract §B requires generation parameters on every record while schema E `p1_gap_record` rejected the field; (2) `hit_key` had no stated type |
| Change (revision 2) | contract `prompts/20260925_1056_p3b-agent-contract-r2.md`, sha256 `7a78c435ea7200c6af17c7b62fd037e0844c5ca98fe1adf5e3ed73dc8a589ef7`, supersedes `prompts/20260925_0351_p3b-agent-contract-r2.md` (`c175d411…`) for batches dispatched from now on. The corrections: (1) `p1_gap_record` gains `generation_parameters`; (2) `hit_key` is the stage-1 key with its exact JSON type (raw: integer offset); (3) the run-id placeholder `<run_id>` (`<batch_id>-R2` or `-R2.<n>`) is stated for paths, reader calls and records, a mechanical clarification without which a re-run would write into the failed run's directory. The diff between the two contracts is exactly these lines plus the revision header and the revision-2 slice root. The verifier is not loosened: it now refuses a non-integer raw `hit_key` or `term_index` explicitly |
| Implementation | code commit `dacc77e2e`. Revision-2 slices are written under `_batch_input_r2/s5/rev2/` (manifest header `slice_root`), so the revision-1 slices of OB0004-R2 remain as run. `p3b_s5_state.py rebind-manifest` refuses unless the previous manifest is the bound one and the composition is identical |
| Manifest | regenerated: file `758ca9df3ed7b8a700e58424567690c8147e43cee566da74f46f3913d5762fac`, body output `97dd2210…` → `0504282b10e794f807937f6ea64be4d6e10d0647377da42def008bb41bcbfdf3`. **Composition identical** (396 batches = 382 + 14 hub; 1,975 labels; ids, order, run ids, tiers, hub flags, labels, checklist, weights and predicted loads equal). Per batch only `contract_sha256` and `slice_sha256` differ; the input manifest and quarantine record are unchanged |
| State | rebind recorded (MANIFEST-REVISION entry, `0c5e95e2…` → `758ca9df…`); OB0004 new run **OB0004-R2.2** opened (PREPARED). **OB0004-R2 stays FAILED under revision 1** with its slices, outputs and reports as committed in `46267eb27` |
| Agent findings of OB0004-R2 (kept, not affected by the revision) | 3 × G-12 (layer-A register pointers) · 1 quote miss · 3 step-10 cross-slice reads · 1 parent-directory listing |
| Unchanged | batch composition, labels, candidate/control population, K = 64, registered cells, S5 methodology, A'1 blinding rule, statistical rules, pass plan (`97279830…`), pass contract (`816d81f3…`), H-19 SEALED, S5c PROHIBITED |
| Authorization | **OB0004-R2.2 AUTHORIZED** under revision 2 through the §6 runbook, up to AUDITED; it stops before the H-06 acceptance. No other batch, no S5a release, no actual-input AUC gate, no H-19 access, no S5c |

## G-LOG-0049 — OB0004-R2.2 (contract revision 2): verification PASS, quotes PASS, independent §21 audit FAIL (3 PROTOCOL-VIOLATIONs); batch FAILED; human decision required

| Field | Value |
|---|---|
| Commission | G-LOG-0048 authorization (OB0004-R2.2 under revision 2, up to AUDITED) |
| Recorded | 2026-09-25, by the AI session |
| Execution | guard seal / grep / inputs pass; revision-2 slices materialized under `_batch_input_r2/s5/rev2/OB0004/` (5 verified; quarantine 0/0); DISPATCHED; one agent (claude-opus-5-5[1m], no helpers); PROPOSED; verified; audited; FAILED |
| Output | `ledger-p3b-r2/OB0004-R2.2/`: 5 objects, 28 research records, 16 P1-gap records; 44 reader calls, 0 refused, 0 step-10 bytes; output quarantine 0/0 over 93 records |
| Verifier | `audit-p3b/S5-VERIFY-OB0004-R2.2.json`: **PASS**, 0 failures, 0 alerts. **Both revision-1 contract defects are resolved**: P1-gap records carry `generation_parameters`, and `hit_key` types are exact |
| Quotes | `audit-p3b/S5-QUOTES-OB0004-R2.2.json`: **PASS**, 127 exact, 0 misses |
| Audit | sample `audit-p3b/S5-AUDIT-SAMPLE-OB0004-R2.2.json` (output `f0ca52e9…`; 1 object and 7 register records); report `audit-p3b/S5-AUDIT-OB0004-R2.2.json`: **FAIL**. 20 dispositions: 3 PROTOCOL-VIOLATION, 2 AUDIT-UPHELD, 8 JUDGMENT-CALL, 7 ORIGINAL-UPHELD. Auditor claude-opus-5-5[1m] (same family: under-discovery is a lower bound, §21 item 8). Sampled object agreement: 19 exact, 3 coarse, 5 different |
| PROTOCOL-VIOLATIONs | **Root cause 1 (two violations):** several large stage-2 files (S1513, S1518, S1522, S1531, S1541 and parts of S1519, S1520, S1524) were delivered whole by the reader but, by the agent's own account, examined only through front matter, headings, hit regions and targeted searches. The records nevertheless state method WHOLE-FILE / "read whole", and FOUND dispositions and three GENUINELY-UNDEFINED-AFTER-CENSUS results rest on them, including the sampled object's S1519 dispositions. This breaches contract §A.6, §11.4 and §19.4; the contract's CONTRACT-DEVIATION escalation route was not used. **Root cause 2 (one violation):** two step-verify-programme timeline points on files that were not read (S1528, S1551) are classified RETRACTS while their own step text says there is no change. S1528 carries CORRECTION and CONTRADICTION rows on the label, so OMQ-14 requires a whole-file reading |
| Other audit results | scratch-path deviation ORIGINAL-UPHELD (revision-2 correction 3). track_composition and cross-label citations JUDGMENT-CALL. Register :14 and :18 AUDIT-UPHELD (evidence attribution to a source it is not content of). Under-discovery notes recorded (not a failure condition) |
| Classification | these are **agent-execution** violations under a contract that states the rule; they are **not contract defects**. The revision-1 defects did not recur |
| Previous run | OB0004-R2 stays FAILED under revision 1 (G-LOG-0047), and its agent findings remain on record |
| State | **OB0004 FAILED** (run OB0004-R2.2). No acceptance. H-19 SEALED, never accessed; S5c PROHIBITED; no S5a artifact; no other batch authorized |
| Decision required (human) | Not decided by the AI session, and nothing is redesigned automatically. Facts relevant to it: OB0004 predicts about 1.56 MB of stage-2 reading for one agent (manifest `predicted.stage2_bytes`), and the violation is incomplete reading of large files recorded as whole-file; the contract already requires whole-file reading or a CONTRACT-DEVIATION escalation. Two consecutive failed runs of this batch failed at **different** gates (verifier, then audit), so the §23 stop-the-line condition (the same gate twice) is not met |

## G-LOG-0050 — §26 batch contract revision 3 (whole-file reading made observable); manifest regenerated, composition identical; OB0004-R2.3 authorized as a feasibility measurement

| Field | Value |
|---|---|
| Commission | human ruling "approve B + C for OB0004-R2.3" (2026-09-25), after the root-cause report `audit-p3b/20260925_1138_ob0004-r2.2-root-cause-report.md` (`1ebb72b58`) |
| Recorded | 2026-09-25, by the AI session; the decision is the human's (§26.2). Operational change at the implementation level (§26.7); the research rules are unchanged |
| B (contract) | revision 3 `prompts/20260925_1204_p3b-agent-contract-r2.md`, sha256 `b8eef043f3b2ea4bbeb8be03fb1487787c6b1b6712455eaf15286299393809da`, supersedes `prompts/20260925_1056…` (`7a78c435…`) for batches dispatched from now on. Additions: (4) reading is not delivery. `WHOLE-FILE`, a FOUND, a birth, or a change-type timeline point only for a completely consumed file; GENUINELY-UNDEFINED rests on dispositions made whole-file or through Stage 2A/2B as §11.4 permits (unchanged). (5) An incomplete required reading is recorded as a CONTRACT-DEVIATION escalation whose detail names the S-id; the dependent result resolves ESCALATED. (6) OMQ-14 step-1 scope covers every source whose rows on the label are typed CONTRADICTION, CORRECTION or RETRACTION |
| C (observability) | `p3b_read_source.py --page K`: deterministic 20,000-character pages; page identity, span and hash logged; refused when stdout is a regular file. Verifier READ-COVERAGE gate for revision-3 manifests: every file grounding whole-file evidence (WHOLE-FILE or non-ESCALATED whole-file dispositions, FOUND from Stage 2A/2B, FOUND supplied_by, births, change-type timeline points) and every OMQ-14 source must have pages 1..N logged, with hashes re-computed through the discovery resolver. Delivery never counts; a missing or forged page is a **FAIL**. Commits `b7e7fc856`, `97b636d39` |
| Independent review | first pass **NOT CONFIRMED**: one blocker, where the gate narrowed the frozen Stage 2A/2B route. Fixed in `97b636d39` to restore the frozen rule. Second pass **CONFIRMED WITH OBSERVATIONS**. Recorded observations: coverage is counted per run, not per label; the gate follows the current manifest's revision, so historical re-verification needs the historical manifest; "supersession" is mapped to row type RETRACTION; redirect refusal detects only a regular file, not pipes (page hashes prove delivery of every page, not consumption); `superseded_by_sources` are outside the gate |
| Manifest | regenerated: file `1b383fbc6ff720ebd2f893a74362ce73745dcf99243bc00ced1bcae3a5475682`, body output `0504282b…` → `d3e2a29e0895f265944a4e7d95153e88d0824279d2bd1db41d07b50fb7b751f3`. **Composition identical** (396 batches, 14 hub, 1,975 labels; only `contract_sha256` and `slice_sha256` per batch). Revision-3 slices go under `_batch_input_r2/s5/rev3/` |
| State | rebind (MANIFEST-REVISION); new run **OB0004-R2.3** (PREPARED). OB0004-R2 (revision 1) and OB0004-R2.2 (revision 2) remain FAILED and unchanged, with their findings on record |
| Tests / gates | all 17 S5 test files pass (verify 54, including missing-page, forged-hash, delivery-only, 2A/2B and escape tests); guard 0 violations; `prepare --check` IDENTICAL |
| Unchanged | population, 396-batch composition, 1,975 labels, hubs, K = 64, A'1, statistics, load thresholds, claim hierarchy, research rules, pass plan and pass contract; H-19 SEALED; S5c PROHIBITED |
| Authorization | **OB0004-R2.3 AUTHORIZED** under revision 3 via the §6 runbook, up to AUDITED; it stops before H-06. It is also an **operational feasibility measurement**. Outcome 1: complete reading, proven by the page ledger. Outcome 2: a genuine capacity limit, escalated as CONTRACT-DEVIATION and never claimed as WHOLE-FILE. Neither outcome is forced; no redesign follows automatically. No other batch is authorized |

## G-LOG-0051 — OB0004-R2.3 (contract revision 3): feasibility measurement OUTCOME 2 (genuine capacity limit, honestly escalated); verifier FAIL; batch FAILED; human decision required

| Field | Value |
|---|---|
| Commission | G-LOG-0050 authorization (OB0004-R2.3 as an operational feasibility measurement, up to AUDITED) |
| Recorded | 2026-09-25, by the AI session |
| Execution | guard pass; revision-3 slices under `_batch_input_r2/s5/rev3/OB0004/` (5 verified; quarantine 0/0); DISPATCHED; one agent (claude-opus-5-5[1m], no helpers); PROPOSED; verifier; quotes; FAILED |
| Output | 5 objects, 17 research records, 19 P1-gap records; output quarantine 0/0 over 121 records |
| Reading (page ledger) | 80 reader calls: 79 paged, 0 whole-mode, 1 refused (STDOUT-REDIRECTED-TO-FILE). **0 page-hash failures, 0 partially read files.** Required whole-file set (stage-2 files plus OMQ-14 sources, all labels): **43 files, 1,908,151 bytes**. Completely read: **18 files, 1,088,267 bytes (57 %)**. Not read: 25 files, 819,884 bytes, all escalated as CONTRACT-DEVIATION. Page bytes consumed including repeats: 1,279,351. Agent tokens reported: about 865k |
| By label | 4 of 5 labels **complete**: architecture-conformance (2/2 files), governance-conformance (1/1), constitution (3/3), strategic (4/4). step-verify-programme: 11 of 36 required files (889,212 of 1,709,096 bytes); 16 stage-2 files and 13 step-1 sources escalated |
| Verifier `audit-p3b/S5-VERIFY-OB0004-R2.3.json` | **FAIL**, 23 failures: **no READ-COVERAGE failure**, so every whole-file claim is page-proven. The failures: G-05 `method` null ×21 (unread, escalated stage-2 dispositions: the schema's method set WHOLE-FILE / STAGE-2A-2B has no value for "not read"); G-04 ×1 (16 of 22 stage-2 hit files neither read whole nor scanned; §19.4 "the absence procedure is never thinned" for a non-hub label); G-05 ×1 (`type_status` null without a SCHEMA-LIMITATION escalation, an agent slip) |
| Quotes | PASS (88 exact, 0 misses) |
| Audit | not run: a verifier FAIL ends the run before AUDITED (§6 step 7) |
| Deviations disclosed by the agent | `\| cat` needed for paged output, because the harness captures stdout into a regular file and the reader's redirect refusal blocked plain calls (a **tooling finding**: the redirect refusal is incompatible with the harness and does not prevent sampling through pipes); one page piped through `head`, then re-read in full; label order changed (step-verify last) so that the largest label would be the partial one; stage-2 WHOLE-FILE dispositions resting on complete step-1 page reads (accepted by the run-level gate) |
| Measurement result | **Outcome 2: genuine capacity limit, honestly escalated.** The observability fix worked: no false WHOLE-FILE claims, and the unread files are declared. One agent context completed about 1.1–1.3 MB of verified whole-file reading. OB0004 requires 1.91 MB, and its largest label alone needs 1.71 MB. OB0004 sits at about p90 of batch load (G-LOG-0049 / root-cause report) |
| Findings for governance (not decided here) | (1) the schema has no disposition value for an escalated unread hit file (a contract gap exposed by the CONTRACT-DEVIATION route); (2) under §19.4, a non-hub label with an honestly escalated stage-2 shortfall still fails the batch, so load capacity per agent context is the open question (option D); (3) the redirect refusal should be withdrawn or replaced, because it blocks legitimate harness use and does not detect pipes |
| State | **OB0004 FAILED** (run OB0004-R2.3). R2, R2.2 and R2.3 are all on record. H-19 SEALED; S5c PROHIBITED; no other batch authorized; no redesign made |

## G-LOG-0052 — Human ruling: authorize the non-production S5 decomposition pilot; schema-4 delta and redirect-refusal withdrawal authorized; pilot pre-registered

| Field | Value |
|---|---|
| Commission | human ruling "S-SERIES — HUMAN RULING: AUTHORIZE DECOMPOSITION PILOT" (2026-09-25), after the load decision brief `audit-p3b/20260925_1249_s5-load-decision-brief.md` |
| Decision (human) | the final S5 load architecture is **not** selected. The **non-production decomposition pilot is authorized first**: control `knowledgeos-architecture-constitution-v01`, target `step-verify-programme`, and the optional 1.52 MB single-file probe (S2276), included because it changes neither scope nor method. The minimal versioned schema change "required file → not consumed → explicitly escalated" (`NOT-CONSUMED-ESCALATED`, schema revision 4, pilot only) is authorized, as is withdrawal of the reader's redirect refusal. **No production batch is authorized** |
| Implementation | commit `52fbe3c3f`: reader pilot namespace (logs isolated in `pilot-s5-decomp/`), `--session` marker, redirect refusal withdrawn; verifier schema-4 delta gated to revision ≥ 4; `scripts/p3b_s5_pilot.py` (plan / check / validate); tests. All 18 S5 test files pass; guard 0 violations; `prepare --check` IDENTICAL |
| Pre-registration (frozen before execution) | `audit-p3b/20260925_1325_s5-decomposition-pilot-preregistration.md`; plan `pilot-s5-decomp/PILOT-PLAN.json` (output `ac721f3b…`); pilot contract `prompts/20260925_1325_p3b-s5-decomposition-pilot-contract.md` (`468ec116…`). It fixes: mandatory set R(L) = stage-2 files ∪ all row sources (no thinning); deterministic partition (600 KB budget; 4 units); U02 continuation across two sessions; the Property A criteria; target completion; the Property B control comparison (fields, agreement classes, success = no BASELINE-UPHELD on a judgment field); the audit; and the probe |
| Unchanged | production contract revision 3, manifest, state, all 396 batches, population, hubs, K = 64, A'1, H-17, R2 / R2.2 / R2.3; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0053 — S5 decomposition pilot executed (non-production): Property A satisfied; control Property B success; target complete, verifier PASS, 0 protocol violations; large-file probe not completed; human decision required

| Field | Value |
|---|---|
| Commission | G-LOG-0052 (pilot authorization; pre-registration `de6accb6c`) |
| Recorded | 2026-09-25, by the AI session |
| Report | `audit-p3b/S5-DECOMPOSITION-PILOT-REPORT.md` |
| Property A | both labels completely covered: control 6/6 files (304,733 bytes; 18 pages); target 39/39 files (1,760,662 bytes; 106 pages; 3 units). 0 missing, 0 duplicate, 0 hash failures. Provenance 124 rows. Continuation (U02, two sessions): 0 cross-session duplicates, complete after resume. `PILOT-COVERAGE.json` output `ead54f2f…` |
| Production verifier (relabelled temp copy) | control PASS 0 failures; target PASS 0 failures (READ-COVERAGE and G-04 pass on the label a single agent could not complete in R2.3) |
| Control Property B | 29 fields: 19 exact / 6 coarse / 4 different. Auditor: 6 DECOMPOSED-UPHELD, 4 JUDGMENT-CALL, **0 BASELINE-UPHELD, 0 PROTOCOL-VIOLATION**, so **success under the frozen rule** |
| Target audit | 24 dispositions: 14 ORIGINAL-UPHELD, 7 JUDGMENT-CALL, 3 AUDIT-UPHELD (minor: two miscounts, one ordering inconsistency), **0 PROTOCOL-VIOLATION** |
| Information loss (auditor) | 4 decomposition-caused losses: cross-unit change classes, disposition-versus-absence conflicts, cross-unit calibration (same front matter judged differently by two units), dependency edges. Label-level resolutions unaffected; per-key calibration and edges affected |
| Probe | S2276 (binary AI-generated PNG, 1,516,994 bytes): 1 of 73 pages consumed; page 2 logged but only previewed (**delivery ≠ reading, observed live**); NOT-CONSUMED recorded honestly. Character paging exceeds the display limit on binary content; about 1.8M tokens for the whole file |
| Not established | cross-unit synthesis equivalence; the heaviest labels; an independent-model audit; binary-file treatment |
| State | pilot complete and stopped. Production unchanged; OB0004 FAILED; no batch authorized; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0054 — Post-pilot architecture decision brief prepared (evidence only; no implementation; production frozen)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — POST-PILOT ARCHITECTURE RESOLUTION" (2026-09-25) |
| Recorded | 2026-09-25, by the AI session; preparation for a human architecture ruling |
| Brief | `audit-p3b/20260925_1355_s5-post-pilot-architecture-decision-brief.md` |
| Verified from stored pilot records | all four information-loss findings reproduce. (1) change classes: the synthesis assigned EXTENDS to 31 of 33 points without cross-unit text comparison. (2) 9 disposition-vs-absence conflicts, all on `dependencies`; intra-record and mechanically detectable. (3) unit calibration divergence (dependencies FOUND rate 4/4, 1/8, 11/21), confirmed on a near-identical pair. (4) inconsistent edge evidence standard; non-label endpoints dropped only at synthesis |
| New measurements (read-only) | 2,558 required files in the population, of which **12 are binary** (2 PNG, 3 ZIP, 7 with NUL bytes; 1.73 MB; all stage-2-only; 41 labels). **No text page exceeds 30 KB** (maximum 25,041 bytes). **The largest required text file is 241,117 bytes**; the 1.52 MB file was binary. 82 labels exceed 1.09 MB of text |
| Content | established vs non-established claims; the four findings with mitigation candidates; a multi-unit equivalence experiment proposal (control s1620-… of OB0018, seeded pick, 34 files, 772 KB, 3 units at 300 KB; arms baseline / A / B / C); binary policy options; a byte-bounded paging design (24,000 bytes, UTF-8 boundaries, binary refusal, per-page acknowledgement token proposal); reading-state semantics for NOT-CONSUMED-ESCALATED; architecture options 1–4, not ranked |
| State | nothing implemented; production contract, manifest, reader and state unchanged; no batch authorized; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0055 — S-Series research-purpose & architecture gate prepared (analysis only; OB0018 not executed)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — RESEARCH PURPOSE GATE BEFORE NEXT EXPERIMENT" (2026-09-25) |
| Recorded | 2026-09-25, by the AI session; preparation for a human decision |
| Document | `audit-p3b/20260925_1403_s-series-research-purpose-architecture-gate.md` |
| Key observations | frozen objective is dual: reconstruct plus discover, with the roll-up as "the controlled floor" completing v3.5 Phase 3 (§1). Deep research is already sampled: the checklist covers 567 of 1,975 labels (§9C.1). Exhaustive whole-file reading and the never-thinned absence procedure belong to the reconstruction floor (§9.8, §11.4, §19.4, §23). A broad extraction substrate exists (27,906 P1 contribution rows over 2,724 files; P2a families; 1,792 P3a pairs). 3 of 5 registered S5a generators use pre-S5 data |
| Inferences (labelled in the document) | exhaustive deep processing is required to complete the frozen P3b, not for research discovery to begin; OB0018 informs the engineering of exhaustive processing, not the research methodology; re-scoping touches frozen v1.7 and the v3.5 mandate (§26.4) |
| Gates presented (not ranked) | A (OB0018; exhaustive floor kept), B (research-first redesign; §26 plus v3.5-level act), C (a small strategy-comparison probe on the two pilot labels), D (research on pre-S5 layers as a separate non-production track) |
| State | nothing executed or changed; production frozen; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0056 — Gate C research-first strategy comparison authorized (experiment only); pre-registration frozen

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — GATE C: RESEARCH-FIRST STRATEGY COMPARISON" (2026-09-25) |
| Pre-registration | `audit-p3b/20260925_1420_gate-c-strategy-comparison-preregistration.md`, committed before any Gate-C agent runs |
| Design | two pilot labels only. Strategy A = the audited pilot registers (13 primary findings; 57 P1-gap records as a secondary set). Strategy B = R1 discovery from blind pre-S5 packages (constitution `f745a471…`, step-verify `9287db5b…`) → frozen trigger registry → R2 paged whole-file reading of triggered files only (≤ 800 KB per agent) → R3 verification → independent auditor on a different model (same vendor; limitation recorded), blind materiality, then matching |
| Thresholds (pre-registered) | supports H1 iff M1 ≥ 0.80 and M3 ≤ 0.25 and M6 = 0; supports H0 iff M1 < 0.50; otherwise mixed |
| Isolation | namespace PX0104; nothing in production, contracts, reader, verifier, OB0018, H-19 or S5c touched |

## G-LOG-0057 — Gate C strategy comparison executed and reported (experiment only): pre-registered H0 rule applies (M1 = 0.462 blind); research-first surfaced 29 findings the exhaustive reference omitted; human decision required

| Field | Value |
|---|---|
| Commission | G-LOG-0056 (pre-registration `a281083c7`) |
| Report | `audit-p3b/S-SERIES-GATE-C-STRATEGY-COMPARISON-REPORT.md` |
| Execution | R1 51 candidates / 28 triggers / 16 files (freeze `8f6b8b461`, 12:20:53Z); R2 830,188 bytes, all triggered files read whole after the freeze, 0 untriggered reads, 0 hash failures; auditor claude-fable-5-1 (different model; same vendor), stages 1–2 blind (committed `810137439` before unsealing), stage 3 after unsealing |
| Metrics | M1 0.462 blind (primary; the pre-registered H0 rule applies) / 0.500 with post-unblinding corrections (sensitivity; boundary); secondary P1-gap 19/57 content-level; M2 41 B-only, 29 classed Strategy-A omissions (11 R3-VERIFIED); M3 0 nominal, not reliable (lenient self-grading, off-scale verdict); M4 A 161,771 B per register finding vs B 48,835 B per VERIFIED (not like-for-like); M5 28/51; M6 0; M7 B stronger 6, A stronger 4, equal 1 of 11 |
| Diagnosis | A-only misses mainly candidate-generation failures on information present in R1 (7/12) plus reconstruction content in untriggered early files |
| Outcomes presented (unranked) | 1 viable; 2 needs stronger discovery; 3 viable for some claim types; 4 inconclusive (n = 2); 5 another issue: the exhaustive reference's register omitted 29 findings from files it had read |
| State | no production change; OB0018 not executed; no batch authorized; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0058 — S-Series next architecture brief and pilot design prepared (design only; nothing executed)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — NEXT STEP AFTER GATE C" (2026-09-25) |
| Brief | `audit-p3b/S-SERIES-NEXT-ARCHITECTURE-BRIEF.md` |
| Content | Gate C evidence (not re-run; M1 0.462 blind, H0 rule stands); failure taxonomy (candidate-generation loss 7/12; trigger loss 34/38 unrecovered P1-gap in untriggered files; extraction/register loss in both arms, the exhaustive reference 29; reconstruction loss; verification limitations); four completeness states (READ-, RECONSTRUCTION-, RESEARCH-EXTRACTION-, THEORY-COMPLETE) with measurable evidence; two-layer architecture HYPOTHESIS; research-first v2 channels 1–4 plus strict R3; pilot design: 4 seeded new labels (selection rule on structured metadata only: 106-label pool; failure-mode strata), arms A0 (floor plus reference) / B2 (v2) / reference E (sampled independent re-extraction), structure-equalized blind matching; metrics DR, CGL, TL, XL, EQ, RC, HS, RCov with proposed thresholds; OB0018 kept separate |
| State | nothing executed; no production change; OB0018 not executed; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0059 — Architecture / experimental-design audit of the next pilot; hardened design v2 and decision matrix (design only; nothing executed)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — NEXT STEP AFTER G-LOG-0058: Architecture / Experimental-Design Audit — NO EXECUTION" (2026-09-25) |
| Documents | `audit-p3b/S-SERIES-NEXT-PILOT-DESIGN-v2.md` (Part I audit, Part II hardened design, Part III minimum next experiment); `audit-p3b/S-SERIES-NEXT-ARCHITECTURE-DECISION-MATRIX.md` (options A–D, unranked) |
| S/F isolation | CLEAN: no F-Series evidence, identifiers or conclusions in any S-Series artifact; the only matches are a scope disclaimer and corpus-text false positives ("end-of-series") |
| Key audit results | the Gate C READ ≠ EXTRACTION result is pilot-specific (A's register came from decomposition plus synthesis; A object records not examined). The v1 CGL attribution was single-auditor judgment, not a mechanical check; v1 TL conflated reconstruction scope with research triggers; the v1 Channel 4 checklist cannot support "exhaustive" ("other substantive" is an escape hatch; ABSENT is unverifiable; the shared taxonomy makes errors correlated); U = A0 ∪ E cannot detect shared misses (capture–recapture gives a lower bound only); the v1 E sample was circular; the v1 RCov rule was true by construction; thresholds classified (most ARBITRARY); the v1 picks void (computed before pre-registration) |
| Hardened design | neutral estimation question; B2 runs before A0; canary-token leakage detection; segment-level proposition inventory with mechanical segment coverage; open-ended independent extractor on a different model; pre-drawn stratified E sample; deduplicated clusters and two-matcher κ; DR as a strict/lenient interval; RCov descriptive only; seed from the pre-registration commit hash |
| Status | v1 NEEDS DESIGN REVISION (done); v2 pilot NEEDS ADDITIONAL EVIDENCE: the instrument-validation experiment V1 (6 already-read files, 2 open-ended extractors plus 1 inventory extractor, κ) proposed first |
| State | nothing executed; no corpus read for this work; production frozen; OB0018 not executed; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0060 — Human decision: v2 accepted as the design of record; V1 instrument-validation pre-registration committed (pre-registration only; execution NOT authorized)

| Field | Value |
|---|---|
| Commission | human decision "S-SERIES — HUMAN DECISION / NEXT MOVE" (2026-09-25) |
| Ruling | (1) `S-SERIES-NEXT-PILOT-DESIGN-v2.md` ACCEPTED as the design of record; the v1 pilot design (G-LOG-0058 brief §E–§F) SUPERSEDED; G-LOG-0058 and Gate C evidence unchanged. (2) V1 pre-registration AUTHORIZED; V1 execution NOT authorized. (3) Different-vendor auditor/extractor where genuinely available; otherwise record the same-vendor limitation, no substitution. (4) V1 thresholds are engineering decision rules; coverage = 1.0 and quote verification are hard; κ ≥ 0.6 is ARBITRARY; κ is not proof of completeness; overlap/capture–recapture diagnostic only; outcomes INSTRUMENTS-VALID / NEEDS-REVISION / NOT-USABLE |
| Pre-registration | `audit-p3b/20260925_1530_v1-instrument-validation-preregistration.md` (22 sections + next-step authorization); namespace `PX0106` |
| Frozen tooling | `scripts/p3b_v1_instrument.py` (segmentation, coverage/quote checks, equalization, sample, κ, capture, access/canary checks, decision; execution sub-commands refuse without `pilot-s5-decomp/v1/V1-AUTHORIZATION.json` naming the pre-registration commit and sha256); `scripts/tests/test_p3b_v1_instrument.py` (16 synthetic tests PASS; no corpus) |
| Files | six discovery-pilot files already read with page proof in PX0004-U01/U02 (fixed rule: smallest/middle/largest per label excluding the shared file); identities, hashes and provenance in §2. No corpus content read to prepare the pre-registration |
| Models | no different-vendor model available (Agent aliases opus/sonnet/haiku/fable, all one vendor), so the limitation is recorded and nothing substituted. SEG opus, E1 fable, E2 haiku, M1 sonnet, M2 fable |
| State | nothing executed; no V1 agent run; `pilot-s5-decomp/v1/` not created; production frozen; v2 pilot not executed; OB0018 not executed; H-19 SEALED; S5c PROHIBITED |
| Next | human "AUTHORIZE V1 EXECUTION under pre-registration `<commit>`" |

## G-LOG-0061 — Final independent methodological audit of the V1 pre-registration: NEEDS-PREREGISTRATION-REPAIR (audit only; nothing executed; pre-registration unmodified)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — FINAL V1 PREREGISTRATION AUDIT" (2026-09-25) |
| Audited | pre-registration `audit-p3b/20260925_1530_v1-instrument-validation-preregistration.md`, tool `scripts/p3b_v1_instrument.py`, tests, G-LOG-0060, all at `ec4b5912e` (working tree identical); synthetic probes P1–P11 on the frozen code (invented inputs; no corpus, no resolver) |
| Report | `audit-p3b/20260925_V1-PREREGISTRATION-FINAL-AUDIT.md` |
| Blockers | **B1** capture gate can return NOT-USABLE for shortfalls not attributable to SEG (P-OPEN/SEG scope asymmetry on multi-file claims, matching and granularity, M1 bias, empty or tiny agreed set); **B2** matcher read logs, matcher outputs (canaries) and model identity are outside the decision (access computed before the matchers run), so M2 → M1 leakage could inflate κ undetected |
| Material | M1 §17 vs §18 contradiction and misattribution of not-computable κ/capture to NOT-USABLE; RUN-INVALID shares its label; M2 repair not mechanically bounded; M3 blinding overstated (letter map derivable from public commit plus code; canaries detect only header copying and the prompt suppresses them; SEG list structurally recognisable) |
| Minor / limitations | m1–m6 (κ unit double counting, omitted units, p_e = 1 case; agreement ≠ correctness; trivial quotes; merge > MAX_SEG and test branches; duplicate ids; re-dispatch unspecified); L1–L5 acceptable (same vendor, M2 = E1 model, selection after sizes, prompt-only non-corpus isolation, κ conditional on clustering, CLAIM breadth) |
| Capture analysis | capture is a relative recall against E1∩E2, not capture–recapture; it may withhold VALID but should not condemn the instrument; it cannot be made purely diagnostic, because coverage alone is satisfiable by a vacuous all-NO-SUBSTANTIVE inventory (P2) |
| Verdict | **NEEDS-PREREGISTRATION-REPAIR.** Minimum corrections R1–R5 proposed (superseding v1.1 plus re-frozen tool); not applied |
| State | nothing executed; no V1-AUTHORIZATION.json; no pilot-s5-decomp/v1/; no corpus read; pre-registration unmodified; H-19 SEALED; S5c PROHIBITED |
| Next | human "AUTHORIZE V1 PRE-REGISTRATION REPAIR (R1–R5)" or another ruling |

## G-LOG-0062 — Human decision: V1 pre-registration repair (R1–R5) authorized; V1.1 pre-registration and tooling frozen (pre-registration only; execution NOT authorized)

| Field | Value |
|---|---|
| Commission | human decision "S-SERIES — AUTHORIZE V1 PREREGISTRATION REPAIR" (2026-09-25; the message text ended at "…unless the environment changes and a human explicitly", and the remainder was applied in the spirit of the prior instructions: models not changed) |
| Historical record | V1 pre-registration, tool and tests at `ec4b5912e` UNCHANGED |
| New | `audit-p3b/20260925_1700_v1.1-instrument-validation-preregistration.md`; `scripts/p3b_v1_1_instrument.py`; `scripts/tests/test_p3b_v1_1_instrument.py` (45 synthetic tests PASS; the V1 tests still pass, 16) |
| R1 | capture is relative recall against E1∩E2, not capture–recapture; it may block VALID and never produces NOT-USABLE; NOT-COMPUTABLE below 20 agreed clusters (ARBITRARY); low capture reported with causes; Chapman diagnostic only; single-file sentence in P-OPEN |
| R2 | integrity recomputed in `result` after all eight runs: read logs for every run; a tool-call allowlist audit of every agent from the harness transcript (so M2 → DECISIONS-A01 and M1 → M2 access is detected, not prompt-dependent); model identity from transcript API model plus dispatch alias (mismatch → RUN-INVALID); canary scan of every agent-authored file; freeze ordering and seal-hash equality |
| R3 | qualifiers INSTRUMENT / INSTRUMENT-UNDETERMINED / RUN-INVALID; κ/capture NOT-COMPUTABLE → UNDETERMINED; SEG context exhaustion → INSTRUMENT; other failed runs → UNDETERMINED; access/model/tool-call/ordering → RUN-INVALID; §17/§18 contradiction resolved |
| R4 | frozen, hashed failure list (check-1 stage); repair accepted only for flagged keys; unflagged propositions must be carried over unchanged; no added propositions; original hash-protected; deterministic REPAIR-DIFF; "Change nothing except the flagged key(s)" |
| R5 | X/Y/Z map and item ids from a runtime secret nonce generated after the check-2 freeze (verified); SOURCE-KEY and DECISIONS-A01 hash-only until unseal after M2; canary-suppressing instruction removed; no semantic blinding claimed |
| Minor | m1 FIXED (direction diagnostic); m2 FIXED (missing units → NOT-COMPUTABLE); m3 FIXED (p_e = 1 → NOT-COMPUTABLE); m4 FIXED; m5 RETAINED AS LIMITATION (length diagnostic, no threshold); m6 FIXED (tests, documentation, duplicate ids, no re-dispatch); referential fields RETAINED |
| Flagged for human | (a) V1's rule that an E-run quote-miss rate > 5% → NOT-USABLE is retained unchanged (it arguably concerns the reference extractors, not the instrument); (b) environment observation: historical transcripts of other agents show `claude-opus-5` and `deepseek-v4-*` models under no alias. Different-vendor selection for V1.1 roles is not demonstrated, and nothing is substituted; (c) `failure_reason` classification of a failed agent is an orchestrator judgment, recorded with harness evidence |
| State | nothing executed; no V1.1-AUTHORIZATION.json; no pilot-s5-decomp/v1/; no corpus read; production frozen; H-19 SEALED; S5c PROHIBITED |
| Next | human "AUTHORIZE V1.1 EXECUTION under pre-registration `<commit>`", optionally after a re-audit |

## G-LOG-0063 — Final adversarial execution-readiness audit of V1.1: NEEDS-PREREGISTRATION-REPAIR (audit only; nothing executed; V1.1 unmodified)

| Field | Value |
|---|---|
| Commission | human instruction "S-SERIES — FINAL ADVERSARIAL AUDIT OF V1.1" (2026-09-25), plus the request to check alignment with Research Architecture v1.2 and the Step-2 protocol |
| Audited | V1.1 at `3ec22d228` (pre-registration sha256 `3845b35f…`; working tree = commit); synthetic probes on the frozen tool (mock transcripts; no corpus; no real transcript opened) |
| Report | `audit-p3b/20260925_V1.1-FINAL-EXECUTION-READINESS-AUDIT.md` |
| Artifact | committed file clean (each section once); the §16–§18 duplication was display only; one inaccurate sentence (MINOR) |
| Blockers | **X1** an E1/E2 quote-miss rate > 5% → NOT-USABLE (and > 0 → INSTRUMENT) condemns the instruments under test for reference-extractor failure; the proposed treatment is a reference-quality condition → UNDETERMINED (**human ruling required**); **X2** tool-call audit bypass: command substitution (`$()`, backticks) inside the allowed reader/validator path prefix is not detected (so M2 → DECISIONS-A01 via obfuscation passes); **X3** execution guard accepts any commit string with a modified pre-registration (commit, prereg and tool not bound to git) |
| Material | X4 persisted-output allowlist injectable; X5 transcript↔run linkage unverified, truncation unhandled; X6 a failed extractor's partial output can yield INSTRUMENT via capture; X7 `result` does not re-hash staged files against PROVENANCE, V1-DISPATCH never frozen, MATCH-LISTS not re-derived from the nonce |
| Sound | state machine (1,728 combinations, 0 invariant violations); capture semantics (n = 0/1/19/20; never NOT-USABLE); bounded repair cases; nonce timing; κ completeness; vacuous-coverage guard; six-file fixity |
| Model / vendor | identity checks correct; different-vendor model not demonstrably available to the dispatch mechanism (deepseek seen only in historical unaliased agents); limitation retained; risk that the opus alias records `claude-opus-5` noted, not re-checked |
| Architecture | Research Architecture v1.2 and the Step-2 protocol (DRAFT, not adopted) live in `knowledgeos_theory_chronological_extraction/`; the S-lane declares itself an annex to Master Protocol v3.5 and references v1.2 nowhere. Strong correspondence of principle; options B/D and metadata-driven triggering (ACL-1) would conflict if v1.2 governs the S-lane; V1.1 is compatible. Governance decisions (a)–(d) returned to the human. No evidence from that programme used |
| Verdict | **NEEDS-PREREGISTRATION-REPAIR.** Repairs X1–X7 plus minors proposed for a superseding V1.2 pre-registration; none applied |
| State | nothing executed; no authorization; no pilot-s5-decomp/v1/; production frozen; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0064 — Human decision: X1 ruled (repair accepted); V1.2 repair (X1–X7 plus minor items) authorized; V1.2 pre-registration and tooling frozen (pre-registration only; execution NOT authorized)

| Field | Value |
|---|---|
| Commission | human decision "AUTHORIZE V1.2 PREREGISTRATION REPAIR" (2026-09-25): X1 ruling accepted; repair X1–X7 plus minors only; no redesign; six files, models, research question and thresholds unchanged; V1 and V1.1 immutable; purpose narrowed to Phase-1 extraction-instrumentation validation (decision 4); do not execute; do not modify v3.5 or the architecture; do not execute S5/OB0018; re-audit and stop |
| New | `audit-p3b/20260925_1900_v1.2-instrument-validation-preregistration.md`; `scripts/p3b_v1_2_instrument.py`; `scripts/tests/test_p3b_v1_2_instrument.py` (51 synthetic tests PASS; the V1.1 45 and V1 16 still pass) |
| X1 | E1/E2 quote misses → reference-quality condition: blocks VALID as INSTRUMENT-UNDETERMINED, never NOT-USABLE or INSTRUMENT; SEG quote gates unchanged |
| X2 | allowed script-path prefix restricted to `[A-Za-z0-9_./@-]*` (no shell metacharacters or substitution) |
| X3 | guard binds a full 40-hex commit + pre-registration and tool byte-identical at that commit + pre-registration sha256 |
| X4 | persisted outputs readable only from the agent's own Bash results with the harness marker, under the session tool-results directory |
| X5 | transcript linkage (single agentId = dispatch, single sessionId, first prompt with run id and canary); truncation or missing transcript → RUN-INVALID; `result` re-reads transcripts |
| X6 | any extractor run not COMPLETED → capture NOT-COMPUTABLE |
| X7 | `result` re-hashes every staged file against its latest freeze; dispatch frozen at check-1, m1, m2, unseal with immutable per-run entries; TOOL-CALLS hash checked; MATCH-LISTS re-derived from the unsealed nonce |
| Minor | κ duplicate unit or invalid target → off-scale; supersession sentence corrected; missing transcript → RUN-INVALID stated |
| Flagged (not changed) | E1/E2 schema violations still attributed as INSTRUMENT (same class as X1; outside authorized scope; ruling needed) |
| Human position recorded | the architecture (v1.2) → Master Protocol v3.5 → experimental machinery hierarchy; research-first may augment, not substitute (R19); the conformance mapping, the B/D restatement and the renaming are later, separately authorized steps; nothing changed here |
| State | nothing executed; no V1.2-AUTHORIZATION.json; no pilot-s5-decomp/v1/; no corpus read; production frozen; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0065 — Adversarial re-audit of V1.2: X1–X7 verified; NEEDS-PREREGISTRATION-REPAIR on N1 (audit only; nothing executed; V1.2 unmodified)

| Field | Value |
|---|---|
| Commission | human decision recorded in G-LOG-0064 ("repair X1–X7 only, then perform another adversarial audit and stop") |
| Audited | V1.2 at `4c1946181` (pre-registration sha256 `2085ca35…`); frozen-tool probes with mock tool-call records, a temporary git repo for the guard, and a 5,184-combination decision grid; no corpus, no real transcript |
| Report | `audit-p3b/20260925_V1.2-ADVERSARIAL-READINESS-AUDIT.md` |
| Verified | X1 (reference misses never condemn the instrument); X2 (22/22 access patterns detected, including `$()`, backticks, `${IFS}`, redirects, pipes, process substitution); X3 (real git: short commit, modified tool and modified pre-registration refused); X4; X5; X6; X7; κ minors; state machine 0 violations |
| New | **N1 MATERIAL:** M1 output completeness not validated (clusters as a partition of the match lists; a decision for every cluster × other list), so an incomplete M1 can inflate capture into a false INSTRUMENTS-VALID; proposed: capture NOT-COMPUTABLE on violation (X6 principle), attribution ruling UNDETERMINED vs INSTRUMENT. N2 MINOR: guard does not bind reader/resolver dependencies. N3 limitation: PROVENANCE protected by git only. F1 flagged: E1/E2 schema violations still INSTRUMENT |
| Verdict | **NEEDS-PREREGISTRATION-REPAIR** (N1); execution under `4c1946181` not recommended |
| State | nothing executed; no authorization; no pilot-s5-decomp/v1/; production frozen; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0066 — Human rulings N1/F1/N2; V1.2.1 (V1.2 revision 1) pre-registration and tooling frozen (pre-registration only; execution NOT authorized)

| Field | Value |
|---|---|
| Commission | human decision "S-Series — N1 Repair and Final V1.2 Adversarial Re-Audit" (2026-09-25): implement N1, apply the N1 and F1 attribution rulings, implement N2, add tests, re-run the suites, re-audit from the resulting commit, stop. The conformance mapping (v1.2 → v3.5 → P3b → V1.2), the B/D restatement, the renaming and the discovery-layer placement are explicitly deferred |
| Rulings | **N1:** incomplete M1 output → capture NOT-COMPUTABLE, INSTRUMENT-UNDETERMINED; **F1:** E1/E2 schema violations → INSTRUMENT-UNDETERMINED; **N2:** bind reader, common library and resolver to the commit guard |
| New | `audit-p3b/20260925_2000_v1.2.1-instrument-validation-preregistration.md`; `scripts/p3b_v1_2_1_instrument.py`; `scripts/tests/test_p3b_v1_2_1_instrument.py` (67 synthetic tests PASS; V1.2 51, V1.1 45, V1 16 still pass). V1.2 at `4c1946181`, V1.1 and V1 unchanged |
| N1 | `m1_completeness` validates the cluster partition and the full decision matrix; any violation → capture and κ NOT-COMPUTABLE (κ included because it derives from M1's output, so an incomplete M1 cannot reach NOT-USABLE through κ); SEG-only evidence still evaluated |
| F1 | E1/E2 schema violations → reference quality → INSTRUMENT-UNDETERMINED; SEG schema unchanged |
| N2 | `authorized()` binds `p3b_read_source.py`, `p3b_s5_common.py`, `p3b_discovery_io.py` byte-identically at the commit; any difference refuses |
| State | nothing executed; no authorization; no pilot-s5-decomp/v1/; no corpus read; v3.5, Architecture v1.2 and P3b v1.7 unchanged; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0067 — Final adversarial readiness audit of V1.2.1: READY-FOR-HUMAN-AUTHORIZATION (audit only; nothing executed)

| Field | Value |
|---|---|
| Commission | human decision recorded in G-LOG-0066 (re-audit from the resulting commit; stop) |
| Audited | `51e04f8cb621cee1b8083245d8068418ac80b6c5`, from the commit (git-archive export plus frozen-code probes); artifact hashes = working tree; v3.5, Architecture v1.2 and P3b v1.7 unchanged; V1.2/V1.1/V1 unchanged; S/F clean; no corpus, no transcript, no execution artifacts |
| Report | `audit-p3b/20260925_V1.2.1-FINAL-ADVERSARIAL-READINESS-AUDIT.md` |
| Tests | V1.2.1 67/67 in repo (66/67 in isolated export: one guard test depends on the repository layout, test hygiene MINOR); V1.2 51, V1.1 45, V1 16 OK |
| State machine | 13,824 combinations, 0 violations of 9 invariants (incl. VALID never with incomplete M1 or reference failure; M1 incompleteness alone → UNDETERMINED) |
| N1 / F1 / N2 | verified (incomplete M1 that would inflate capture from 0.5 to 1.0 → NOT-COMPUTABLE; reference schema → UNDETERMINED; modified reader/common/resolver refused even with matching authorization) |
| False-VALID question | no path found within the mechanical controls; residual only via disclosed trust assumptions (transcript completeness, orchestrator-recorded status and reference output breadth, same-vendor matcher bias beyond the κ sample, coordinated PROVENANCE tampering visible via git) |
| Verdict | **READY-FOR-HUMAN-AUTHORIZATION.** Next act only: "AUTHORIZE V1.2.1 EXECUTION under pre-registration `51e04f8cb621cee1b8083245d8068418ac80b6c5`" |
| Deferred | conformance mapping v1.2 → v3.5 → P3b v1.7 → V1.2.1; B/D restatement; renaming; discovery-layer placement |
| State | nothing executed; production frozen; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0068 — S-Series conformance mapping (Architecture v1.2 → Master Protocol v3.5 → P3b v1.7 → V1.2.1): audit only; nothing modified or executed

| Field | Value |
|---|---|
| Commission | human instruction "S-Series — Conformance Mapping" (2026-09-25) |
| Report | `audit-p3b/20260925-S-SERIES-CONFORMANCE-MAPPING.md` (80 mappings; 15 findings: A 1 · B 4 · C 3 · D 0 · E 0 · F 7) |
| Baselines | Architecture v1.2 sha256 `e3bbf292…` (last change `1e6dc3824`); v3.5 `508b9f99…` (`e3e47b139`); P3b v1.7 `38021aa4…` = the G-LOG-0034 freeze; V1.2.1 `51e04f8cb`; HEAD `d41c68439` |
| Key findings | **F-01 (F):** Architecture v1.2 is not declared governing for the S-lane; frozen P3b v1.7 §7 (D-18) states the lane architecture (v1.1) does not govern P3b. **F-02 (F):** "Master Protocol" names two documents (v1.2 binds the F-lane `knowledge_os_protocoll.md`; v3.5 predates v1.2). v3.5 → P3b conformant (A 27 of 30); hub LOAD escalation and Tier X hold are governed C gaps. P3b → V1.2.1 conformant as instrumentation; **F-13 (C):** V1.2.1 serves no explicit P3b obligation and its taxonomy is not v3.5-B2-conformant, so adoption needs §26. Discovery-Augmented Reconstruction is accommodated (P3b §9B/§13; Arch 1B/§6); no missing architectural capability. R19: no adopted substitution; not-adopted options B/D would substitute (F-14) |
| Correction | G-LOG-0063's "references v1.2 nowhere" refined: all P3b versions' §7 cite v1.1 as non-governing; an untracked, byte-identical copy of v1.2 exists in `chronological-read/prompts/` (not governed; not touched) |
| Next gate | V1.2.1 authorization does not depend on F-01; minimum: record F-13's limits in the authorization act. Governance decisions: F-01, F-02, F-14 |
| State | nothing modified or executed; no corpus read; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0069 — S-Series governance decision analysis after the conformance mapping (analysis only; nothing modified or executed)

| Field | Value |
|---|---|
| Commission | human instruction "S-Series — Governance Decision Analysis After Conformance Mapping" (2026-09-25) |
| Report | `audit-p3b/20260925-S-SERIES-GOVERNANCE-DECISION-ANALYSIS.md` |
| Reality | the S-lane is governed by Master Protocol v3.5 plus the P3b v1.7 annex (G-LOG-0034); no architecture is declared above it; Architecture v1.2 is an F-lane artifact (binds `knowledge_os_protocoll.md` l.13; 0 mentions of v3.5/P3b/S-lane) |
| Provenance | Architecture v1.1 frozen 2026-09-22 (`708674287`); v1.2 on 2026-09-23 (`9444cfbb5`, `1e6dc3824`); P3b v1.0 on 2026-09-24 (`9d832121c`) already excluded the lane architecture, citing the stale "v1.1"; the exclusion was scope-based (F namespace, file unit, no v3.5 reference, lane stopped); the untracked v1.2 copy (mtime 2026-09-25 22:42) carries no authority, and intent cannot be inferred |
| F-02 | "Master Protocol" collision active (v3.5 = S; "Chronological File-Level Derivation" = F-lane); resolvable by path/version citation plus a glossary; no rename |
| F-04…F-07 under adoption | F-04(a) difference in abstraction; F-04(b) actual context-responsibility contradiction (theory verification inside P3), resolvable by assigning P3b's verification layer to Phase 2 under RA-13 (P3b revision); F-05 implementation gap; F-06 partly documentation, partly implementation; F-07 not a contradiction (RA-13 permissive, R19 forbids substitution only) |
| F-13 | V1.2.1 taxonomy is experimental, never claimed as P3b types; V1.2.1 can execute without adopting it; adoption would need a §26 revision mapping to v3.5 B2 |
| F-14 | B and D-selected substitute P3 work (not executable under v3.5 + P3b without formal change); C and D-all are augmentation; never adopted |
| DAR / architecture | Discovery-Augmented Reconstruction already fits (P3b §1/§9B/§13/§9E, v3.5 A11/R10; under v1.2 1B/§6/RA-5/RA-7/RA-13); no second architecture needed |
| Options | A keep; B adopt; C controlled adoption (v1.2 boundary, v3.5 procedure, P3b annex): presented unranked. Minimum change: one governance decision record (all options) plus one P3b §26 revision (B/C); no v3.5 or v1.2 edit |
| Decisions for human | Decision A (governance option); if B/C the context mapping, F-04(b) placement and ACL gates; F-02 glossary; the untracked copy; F-14 restatement; Decision B (V1.2.1 authorization with F-13 limits), independent of Decision A |
| State | nothing modified or executed; no corpus read; H-19 SEALED; S5c PROHIBITED |

## G-LOG-0070 — Human decision: V1.2.1 execution authorized (Decision B); F-13 limits recorded

| Field | Value |
|---|---|
| Decision id | Decision B (V1.2.1 authorization) |
| Decider | the human project owner, 2026-09-25: "Authorize V1.2.1 execution under pre-registration 51e04f8cb" |
| Pre-registration | `audit-p3b/20260925_2000_v1.2.1-instrument-validation-preregistration.md` @ `51e04f8cb621cee1b8083245d8068418ac80b6c5` (sha256 `34d53cef…`) |
| Authorization record | `pilot-s5-decomp/v1/V1.2.1-AUTHORIZATION.json`; freeze stage `authorization` recorded in `pilot-s5-decomp/v1/PROVENANCE.jsonl` |
| F-13 limits (recorded with the act) | (1) instrument-validation result only; (2) the proposition taxonomy is experimental vocabulary, not v3.5/P3b types (not v3.5 B2-conformant), and is not adopted by execution; (3) no v3.5 or P3b obligation changes; (4) adoption into P3b requires a P3b §26 revision mapping its types to v3.5 B2 or restricting it to non-contribution use |
| Independence | Decision A (architecture governance) remains undecided and is neither required nor implied |
| Scope | non-production, namespace PX0106, `pilot-s5-decomp/v1/`; H-19 SEALED; S5c PROHIBITED; S5 production frozen; OB0018 not executed |

## G-LOG-0071 — V1.2.1 execution stopped under pre-registration §18: NEEDS-REVISION / RUN-INVALID (not evidence about the instruments)

| Field | Value |
|---|---|
| Authorization | G-LOG-0070 |
| Report | `audit-p3b/20260925_V1.2.1-RUN-STOP-REPORT.md` |
| Executed | authorization plus freeze; segment map (420 segments) plus freeze (`4dc87030d`); prompts (`c7ba6aa4b`); U01–U06 dispatched in parallel |
| Stop condition | PX0106-U06 (E2, claude-haiku-4-5-20251001): 11 tool calls outside the §15 allowlist (reader run from the repository root, then ls/find/grep; Read of V1-DISPATCH/SEGMENT-MAP/PROVENANCE; Write of FAILURES-PX0106-U06.json); 0 corpus pages read. §18: stop, no further dispatch; the five active runs were stopped |
| Evidence | U01–U04 (opus/fable): 0 breaches, page-proven reading (U01, U03, U04 complete; U02 partial); U05 (haiku): 14 breaches (literal "--page K"), 0 pages; model identity and linkage matched for all six; transcripts ingested (`runs/`) |
| Root cause | the frozen §8 prompts give a relative reader path without a working directory, and a literal `K` page placeholder; the failures are confined to the smaller E2 model (observation, one run) |
| Outcome | **NEEDS-REVISION / RUN-INVALID.** No instrument metric computed; not evidence about the instruments (§17) |
| Next | human decision: whether to revise the pre-registration (§8 prompt clarity; optionally the §3 E2 model) and re-authorize in a fresh namespace; Decision A still undecided |
| State | nothing running; H-19 SEALED; S5c PROHIBITED; S5 frozen; governing artifacts unchanged |

## G-LOG-0072 — Human decision: V1.2.2 execution-contract revision approved (E2 = haiku kept; M4 executable per-page commands; namespace PX0107); implemented and frozen (not authorized; not executed)

| Field | Value |
|---|---|
| Decision | the human, 2026-09-25: "Proceed with the following controlled revision": keep E2 = haiku; M4 fully executable per-page commands; fresh namespace PX0107 / `pilot-s5-decomp/v1-r1/` / `V1.2.2-AUTHORIZATION.json`; scientific design unchanged; allowlist and stop conditions not weakened |
| Implemented | `audit-p3b/20260925_2100_v1.2.2-instrument-validation-preregistration.md`; `scripts/p3b_v1_2_2_instrument.py` (committed generator `prompts`, pre-dispatch gate `check-prompts`, `repair-prompt`; namespace constants); `scripts/tests/test_p3b_v1_2_2_instrument.py` (80 OK); record `audit-p3b/20260925_V1.2.2-REVISION-IMPLEMENTATION-RECORD.md` |
| Invariance | outside the new generator/gate block, the tool differs from V1.2.1 only in version strings, namespace constants, prompt files added to two freeze stages, one result-time gate re-check and three CLI sub-commands; scientific constants asserted identical by test |
| Flagged additions | §15 item 10 (RUN-INVALID if the pre-dispatch prompt gate did not hold); prompts frozen in `segment-map` and repair prompts in `check-2` |
| Untouched | the V1.2.1 pre-registration, tool and tests; reader/common/resolver; PX0106 evidence; v3.5; P3b v1.7; Architecture v1.2; H-19; S5c; OB0018; S5; hub labels; H-02; Decision A |
| State | V1.2.1 RUN-INVALID / NEEDS-REVISION; V1.2.2 REVISION-IMPLEMENTED / AWAITING-INDEPENDENT-AUDIT; NOT-AUTHORIZED; NOT-DISPATCHED; PX0107 PREPARED ONLY; Decision A HUMAN-UNDECIDED |

## G-LOG-0073 — Human instruction: repair V1.2.2 audit findings M-1, M-2 (and m-1…m-3); V1.2.3 implemented and frozen (not authorized; not executed)

| Field | Value |
|---|---|
| Decision | the human, 2026-09-26: repair M-1 and M-2, optionally the minor hygiene repairs; freeze a superseding revision; no redesign; stop before authorization |
| Implemented | `audit-p3b/20260926_0100_v1.2.3-instrument-validation-preregistration.md`; `scripts/p3b_v1_2_3_instrument.py`; `scripts/tests/test_p3b_v1_2_3_instrument.py` (96 OK; the V1.2.2/V1.2.1/V1.2/V1.1/V1 suites still OK); record `audit-p3b/20260926_V1.2.3-REVISION-IMPLEMENTATION-RECORD.md` |
| M-1 | dispatched first prompt must equal `PROMPTS/<run>.txt`; the repair message must equal `PROMPTS/REPAIR-<run>.txt`; no unfrozen further user messages; otherwise RUN-INVALID (§15 item 11) |
| M-2 | commands `cd <repository root> && python3 <absolute tool> …`; the false "from any directory" wording removed; the gate checks the `cd` target |
| m-1…m-3 | reader and validator paths pinned; ASCII digits (tightening only); test temp-dir cleanup; 143 leaked `/tmp` test directories verified, listed for human removal (not deleted; recursive deletion denied) |
| Namespace | PX0108 / `pilot-s5-decomp/v1-r2/` / `V1.2.3-AUTHORIZATION.json`, so the V1.2.2 PX0107 prompts stay unchanged (flagged for human confirmation) |
| Invariance | scientific constants identical (test); allowlist only tightened (test); no scientific or statistical design change |
| State | NOT AUTHORIZED · NO CORPUS READ · NO AGENT DISPATCHED; V1.2.3 AWAITING-INDEPENDENT-AUDIT; Decision A HUMAN-UNDECIDED |

## G-LOG-0074 — Human instruction: A-1 narrow repair (transcript classification); V1.2.4 implemented and frozen (not authorized; not executed)

| Field | Value |
|---|---|
| Decision | the human, 2026-09-26: accept A-1 as MATERIAL; implement A-1 only; no no-corpus probe (A-2 = conservative RUN-INVALID); narrow re-audit; **hard stopping rule** (no further V1.2.x revision if the re-audit finds no new material defect) |
| Implemented | `audit-p3b/20260926_1200_v1.2.4-instrument-validation-preregistration.md`; `scripts/p3b_v1_2_4_instrument.py` (`classify_transcript`: both observed coordinator forms, i.e. the wrapped isMeta user turn and the unwrapped queued_command; other origins, unknown wrappers, unknown harness texts, attachments, system records and record types → RUN-INVALID; the exact SubagentHandback reminder, compaction and interruption handled explicitly); tests 110 OK; record `audit-p3b/20260926_V1.2.4-REVISION-IMPLEMENTATION-RECORD.md` |
| Evidence | structure of 657 existing harness transcripts (50 wrapped + 5 queued coordinator events; no overlap); on real PX0106 transcripts, 0 violations |
| Regression | the same malicious transcripts: V1.2.3 false PASS → V1.2.4 RUN-INVALID |
| A-2 | MATERIAL-UNTESTED / CONSERVATIVE-RUN-INVALID |
| Invariance | the scientific design unchanged (constants and tightening tests; prompt task text identical) |
| Namespace | PX0109 / `pilot-s5-decomp/v1-r3/` / `V1.2.4-AUTHORIZATION.json` (the PX0108 prompts stay unchanged) |
| State | NOT AUTHORIZED · NO DISPATCH · NO CORPUS READ; Decision A HUMAN-UNDECIDED |

## G-LOG-0075 — Human decision: V1.2.4 execution authorized (E0, PX0109); F-13 limits and A-2 disposition accepted

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "AUTHORIZE V1.2.4 EXECUTION under pre-registration c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18", with the confirmation text supplied in the same message |
| Pre-registration | `audit-p3b/20260926_1200_v1.2.4-instrument-validation-preregistration.md` @ `c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18` (sha256 `4d899d867dc762205a7e5eab74ab9d1f534730c772e984416f1010aa33e19650`) |
| Basis | narrow re-audit `333b9a79e` (INSTRUMENT HARDENING CLOSED); readiness `70f9822f6` (PASS, SCOPE-SAFE) |
| Authorization record | `pilot-s5-decomp/v1-r3/V1.2.4-AUTHORIZATION.json`; freeze stage `authorization` in `pilot-s5-decomp/v1-r3/PROVENANCE.jsonl` |
| Confirmed | PX0106 RUN-INVALID; PX0107/PX0108 prepared only; PX0109 independent evidence; F-13 (1) instrument-validation only, (2) experimental taxonomy, not v3.5 B2, (3) no v3.5/P3b obligation changes, (4) adoption only via a separate §26 revision; A-2 conservative rule; the false-FAIL exposure; the §21 trust assumptions |
| Constraints | the 13 frozen steps exactly; no redesign, optimization, repair, reinterpretation or re-dispatch; §18 → stop and preserve; classify only by §17; then stop |
| Not authorized | OB0018, S5, P3b changes, production use, theory reconstruction or any later stage |
| Independence | Decision A remains HUMAN-UNDECIDED; neither required nor implied |
| Scope | non-production; H-19 SEALED; S5c PROHIBITED; S5 production frozen |

## G-LOG-0076 — V1.2.4 E0 (PX0109) stopped under pre-registration §18: NEEDS-REVISION / RUN-INVALID (not evidence about the instruments)

| Field | Value |
|---|---|
| Authorization | G-LOG-0075 |
| Report | `audit-p3b/20260926_V1.2.4-E0-RUN-STOP-REPORT.md` |
| Executed | frozen steps 1–8: authorization; prompts/gate; segment map (420); U01–U06 (all COMPLETED); check-1; repairs U01/U05/U06 (verbatim); check-2; equalization and lists; M1 dispatched |
| Stop condition | PX0109-A01 (M1, claude-sonnet-5): Bash `wc -l` on MATCH-LISTS.json, outside the §15 allowlist; stopped immediately; no output |
| Independent second cause | §15 item 11: unrecognized harness recovery texts in U04 (1) and U06 (3), the documented conservative false-FAIL class accepted in G-LOG-0075 |
| Integrity facts | model identity, linkage, first-prompt and repair equality (A-1 confirmed on live data), page proofs: all as required; allowlist breaches 0 in U01–U06 |
| Outcome | **NEEDS-REVISION / RUN-INVALID** (§17 rule 1, §18); no instrument metric is evidence; no `V1-RESULT.json` |
| Analysis (inference) | an unchanged re-run is improbable to be valid (point estimate ≈ 1%; ≤ 25% at optimistic bounds); options O1 close the E0 line / O2 harness-text integrity revision / O3 matching-stage redesign; human decision |
| State | nothing running; governing artifacts unchanged; H-19 SEALED; S5c PROHIBITED; S5 frozen; OB0018 not executed; Decision A HUMAN-UNDECIDED |

## G-LOG-0077 — Human decision O1: V1.2.4 E0 line closed (instruments remain UNVALIDATED); proceed toward OB0018 readiness

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "Decision O1: close the V1.2.4 E0 line, proceed toward OB0018", with the accompanying instruction text |
| Basis | G-LOG-0076; `audit-p3b/20260926_V1.2.4-E0-RUN-STOP-REPORT.md` §6 |
| Epistemic boundary (recorded exactly) | E0 did NOT produce an instrument-validity result. **The instrument remains unvalidated because E0 terminated before the statistical decision stage under §18.** The stop is not evidence that the instrument is good or bad. The descriptive observations (SEG coverage 1.0; SEG quote misses repaired to 0; E1 0 misses; E2 substantial misses after repair; M1 faced 1,728 items) stay recorded and are never promoted to validation evidence |
| Not implemented | O2 (harness-text revision), O3 (matching redesign), V1.2.5; no further instrument-hardening cycle |
| Preserved hypothesis (untested) | M1 matching may need per-file or chunked units if matching scale becomes an empirically demonstrated bottleneck |
| Immutable | PX0106–PX0109 and all V1.x artifacts |
| Next | OB0018 readiness (read-only preparation; no execution); E0 is not a gate on S5 |
| State | H-19 SEALED; S5c PROHIBITED; S5 frozen; OB0018 not executed; Decision A HUMAN-UNDECIDED |

## G-LOG-0078 — Human decisions D-1…D-4 for the OB0018 decomposition-fidelity pilot; pre-registration drafting commissioned (no execution)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "D-1 to D-4 approved as recommended; draft the pre-registration", with the accompanying refinement instructions |
| Basis | `audit-p3b/20260926_OB0018-READINESS-REPORT.md` §6, §10 |
| D-1 | run the brief §D multi-unit equivalence experiment with amendments M-a…M-f, **as a decomposition-fidelity pilot**: it does not authorize any population-level claim |
| D-2 | pilot-style outcome-based integrity (page ledger, content hashes, READ-COVERAGE, production verifier, namespace isolation, seal-aware reader, immutable evidence, append-only repair records); the V1.x exact-command allowlist is **not** an execution requirement (a design lesson, not a hardening cycle) |
| D-3 | a Claude-family auditor, with an explicit **same-family dependence limitation**: audit agreement is not independent corroboration; the blind audit remains an error-detection mechanism; a cross-family audit is a future enhancement, not a blocker |
| D-4 | the co-label edge rule stays undecided; the edge metric is reported under both readings |
| Refinements (human) | the "minimum useful decomposition-fidelity pilot" (n_labels = 1, n_units = 3), not a "smallest reliable experiment" in a population sense; evidence ladder: engineering feasibility → local fidelity → population robustness, where the pilot addresses only the first two and population robustness is future work, only if decision-relevant; a successful result does not remove the S5 floor (R19); ML never decides truth |
| Commissioned | draft the pre-registration and tooling; freeze; exactly one independent read-only audit; repair only MATERIAL findings; stop before authorization |
| State | production batch OB0018 PREPARED and untouched; nothing executed; H-19 SEALED; S5c PROHIBITED; S5 frozen; Decision A HUMAN-UNDECIDED |

## G-LOG-0079 — Human decision: OB0018 decomposition-fidelity pilot execution authorized (PX0018)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "AUTHORIZE OB0018 PILOT EXECUTION under pre-registration 773a40ffc5a7cf218aeea6f6399ba4636065533c", with the accompanying scope text |
| Pre-registration | `audit-p3b/20260926_1800_ob0018-decomposition-fidelity-preregistration.md` @ `773a40ffc5a7cf218aeea6f6399ba4636065533c` (sha256 `279e510a2463ae8a03ba8bce3c4c9cdf0018c0efdf4453187d4719cf94d77df2`); audit record `audit-p3b/20260926_OB0018-PREREGISTRATION-AUDIT.md` |
| Authorization record | `pilot-s5-decomp/ob0018/OB0018-AUTHORIZATION.json`; production-ledger fingerprint `LEDGER-FINGERPRINT.json`; freeze stage `authorization` |
| Accepted scope | one fixed control label, three units; local pilot evidence only (FL-0/FL-1), not population validation; S5 floor unchanged (R19); production batch OB0018 stays PREPARED; independent of Decision A; same-family auditor and partial-blindness limitations; no weakening of the frozen rules during execution |
| Not authorized | S5, production reconstruction, theory inference, canonicalization; no change to the S5 architecture, binary policy, paging or schema |
| Execution | the frozen runbook (§R) only: 8 agents; compute the pre-registered result; seal; stop |
| State | H-19 SEALED; S5c PROHIBITED; S5 frozen; Decision A HUMAN-UNDECIDED |

## G-LOG-0080 — OB0018 pilot executed (PX0018): frozen result UNDETERMINED (level-1 transcript classification); result sealed

| Field | Value |
|---|---|
| Authorization | G-LOG-0079 |
| Report | `audit-p3b/20260926_OB0018-PILOT-RESULT-REPORT.md`; result `pilot-s5-decomp/ob0018/OB0018-RESULT.json` |
| Executed | the frozen runbook: 8 agents (U01–U03, S00, S01–S03, A01), all COMPLETED; 8 freeze stages; the key unsealed after the audit freeze |
| Frozen result | feasibility FAILED, sole cause a transcript-scan finding in U02 (a `p3b_read_source.py --help` call, no corpus read); outcomes A/B/C UNDETERMINED; decision **UNDETERMINED** |
| Integrity | production ledger unchanged; no SEAL-BREACH; model identity as required; units and baseline 34/34 files complete, 0 hash failures |
| Recorded for human review | verifier QUARANTINE in arms A (1) and C (6), counted through one S-id range each; 0 hold-out reads; P3B-ESC is a human decision |
| Descriptive (not the result) | counterfactual NO-ARM-SHOWN-FAITHFUL: arm A fails compliance only (0 BASELINE-UPHELD on judgment fields); B and C fail two judgment births. Observed mechanisms: pair-context loss; out-of-row birth-candidate promotion; brief mechanisms 1–3 not observed, 4 contained |
| State | S5 NOT AUTHORIZED; production batch OB0018 PREPARED; H-19 SEALED; S5c PROHIBITED; Decision A HUMAN-UNDECIDED |

## G-LOG-0081 — Human decisions after OB0018: range quarantine = pilot-level compliance defect (no P3B-ESC); follow-up option (i); mechanisms 5–6 + synthesis lint as proposed S5 design requirements; S5 decision package commissioned (analysis only)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "OB0018 decisions are APPROVED as follows", with the S5 decision package instruction |
| 1. Range quarantine | the two verifier QUARANTINE findings (arms A and C) are **pilot-level formatting/compliance defects; no P3B-ESC**. Rationale: no hold-out id was written explicitly; no hold-out file was read; the count arose only from ids implied inside one free-text S-id range per arm. The verifier finding is preserved historically, not deleted or reinterpreted. A mechanical synthesis-format check enters the S5 decision package |
| 2. Follow-up | **option (i)**: no OB0018 re-run; no new pre-registration; the descriptive OB0018 evidence goes into the S5 load-architecture decision package. The frozen result stays **UNDETERMINED** and is never converted into a success or population evidence |
| 3. Proposed S5 design requirements (subject to the S5 architecture ruling) | pair-context availability at synthesis (pair records delivered, or targeted re-reads of pair-evidence files); birth-candidate row-membership restriction; a synthesis-time mechanical lint (S-id ranges, layer placement, quarantine formatting) |
| Commissioned | the S5 Decision Package, D1–D11, **analysis only** |
| State | G-LOG-0080 immutable; S5 NOT AUTHORIZED; production batch OB0018 PREPARED; H-19 SEALED; S5c PROHIBITED; Decision A HUMAN-UNDECIDED; no further OB0018 re-run; no new hardening cycle |

## G-LOG-0082 — Human authorization: S5 technical decisions A–I and B′ (as amended by the technical review); ONE P3b §26 revision authorized (drafting, implementation, verification); S5 EXECUTION NOT AUTHORIZED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "S-SERIES — HUMAN AUTHORIZATION — S5 Technical Decisions → §26 Contract Revision" |
| Basis | `20260926_S5-DECISION-PACKAGE.md`; `D4-VALIDATION-ROW-FIRST.md`; `20260926_S5-TECHNICAL-DECISION-REVIEW.md` (accepted, with its amendments) |
| A | records-only synthesis for decomposed labels with mandatory S1–S3; **two-path dispatch**: R(L) ≤ 600 KB → the existing single-context label procedure; R(L) > 600 KB → deterministic decomposition + records-only synthesis. Evidence status ENGINEERING-ONLY / EMPIRICALLY-UNVALIDATED |
| B | label-local row-first + FFD fill, 600 KB; D4 facts preserved as engineering facts (not fidelity); all blob types (including PRESERVED-AUDIT-COPY) in sizing |
| B′ | a **packing key with no chronological standing** (dated-position candidates by date, then all others; S-id tie-break), never written into a historical record; the §14 / A.10 historical partial order is unchanged |
| C | no automatic extraction; mechanical pre-classification and a per-file decision record (FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT); extraction only per explicitly authorized file with a validated extractor, defined provenance and a member-level seal check; archive members never bypass the sealed resolver |
| D | versioned byte-bounded reader mode (UTF-8, 24 KB, deterministic boundaries, binary refusal, acknowledgement token); old logs immutable; the verifier supports both modes |
| E | a `reading_state` field (WHOLE-FILE / READ-PARTIAL / READ-FAILED / NOT-CONSUMED); only WHOLE-FILE supports FOUND, GENUINELY-UNDEFINED-AFTER-CENSUS, births and change points; G-04 and R17 preserved; the 8 empty-required-set labels → NOT-EVIDENCED-IN-CAPTURE |
| F | edge classes R1-STRUCTURAL / R2-EVIDENCED (an agent-recorded quote stating the dependency, mechanically verified); neither is historical fact |
| G | four-level scan taxonomy; level 2 includes git show / cat-file / log -p / grep, corpus globs, direct resolver imports; the resolver's seal refusal remains primary |
| H | the full 396/1,975 floor stays mandatory; a pre-registered probability audit (frozen seed, known inclusion probabilities, census of the 5 multi-row-unit labels, high-rate multi-unit, separate hubs, random single-unit); estimand ADJUDICATED DISCORDANCE; blind; stratified HT; ML queues separate; complete before P4 |
| I | S1 unit-level FILE CONTENT ↔ PAIR CLAIM checks (NO → VERDICT-EVIDENCE-CONFLICT); S2 `birth.source_id ∈ row_sources(label)` (also single-context); S3 pre-submit lint, repeated by the verifier |
| Authorized | ONE consolidated P3b §26 contract revision containing items 1–18 of the act; its implementation and verification; then preparation for ONE independent audit |
| **Not authorized** | S5 corpus execution; dispatch of the 396 batches; reading the S5 corpus; P4 or any downstream phase |
| **State** | **TECHNICAL DECISIONS AUTHORIZED · §26 REVISION AUTHORIZED · S5 EXECUTION NOT AUTHORIZED · CORPUS READ NOT AUTHORIZED**; H-19 SEALED; S5c PROHIBITED; Decision A HUMAN-UNDECIDED |

## G-LOG-0083 — Human authorization: ONE bounded Revision-6 repair slice (R5-01…R5-08 + statistical specification); no activation; S5 NOT AUTHORIZED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "S-SERIES — REVISION 6 REPAIR SLICE — Human-authorized implementation scope only" |
| Basis | the ONE independent adversarial audit `audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md` (NEEDS-REVISION; composite false PASS FP-6); its artifacts stay immutable evidence |
| Architectural objective | "the verifier must derive critical verification state independently from authoritative evidence; agent declarations are assertions to be verified, not authoritative facts". PASS ⇒ P ∧ E ∧ R ∧ T ∧ L ∧ S (provenance, execution/run, reading state, temporal, load/plan, synthesis) |
| Scope | close MATERIAL R5-01…R5-08; freeze a statistical specification; convert the preserved attacks into permanent regression tests (FP-6 permanent); ML design boundary only (no ML in verification); a causal-analysis matrix before code |
| Not authorized | activation of revision 6; manifest regeneration; binary pre-classification; S5 dispatch; production corpus reads; H-19 access; S5c; ML in the evidence path; an automatic second audit |
| Unchanged | Master Protocol v3.5; P3b frozen architecture; H-19 SEALED; Decision A HUMAN-UNDECIDED; S5 NOT AUTHORIZED; production ledger; corpus |
| Stop | after implementation, tests and verification; a narrow independent re-audit is a separate human decision |

## G-LOG-0084 — Human authorization: ONE narrow independent adversarial re-audit of Revision 6 (`bc6fb01bb`); no activation; S5 NOT AUTHORIZED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "S-SERIES — REVISION 6 NARROW INDEPENDENT RE-AUDIT" |
| Object | Revision 6 as committed in `bc6fb01bb`; its code, tests and implementation record are **claims to be tested**, not evidence |
| Central question | "Can an independently constructed invalid Revision-6 batch still obtain PASS through the actual production verification path?" |
| Scope | R5-01…R5-08; the `/` range/enumeration disagreement; H3 layer-A binding; the PASS ⇒ P∧E∧R∧T∧L∧S invariant; the frozen statistical specification (internal coherence only); the ML boundary (confirmation only) |
| Method | fresh synthetic fixtures, a fake resolver with synthetic bytes only, attacks on the production verifier path; the R6 tests are regression evidence only, never the oracle |
| Not authorized | activation; manifest regeneration; binary pre-classification; S5 dispatch; production corpus reads; H-19 access; S5c; ML execution; modifying the R5 audit, the R6 implementation, the Master Protocol, F-Series material or application code; automatic repair; an automatic further audit |
| Stop | at any material defect (report it); after the report, control returns to the human |

## G-LOG-0085 — Human decisions HD-1…HD-9 after the R6 re-audit; Model D ADOPTED; Revision 7 authorized (design freeze → implementation → verification); no activation; S5 NOT AUTHORIZED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26 (review of `audit-p3b/20260926_MODEL-D-READINESS-RESULT.md` and "REVISION 7 DESIGN + IMPLEMENTATION") |
| Basis | re-audit NEEDS-REVISION (G-LOG-0084) → root-cause analysis `audit-p3b/20260926_REVISION-6-REAUDIT-ROOT-CAUSE-ARCHITECTURE.md` (RC-1 universe mismatch, RC-2 unwitnessed execution authority, RC-3 unvalidated estimator domain) → Model-D readiness experiment: READY, conditional on C1–C7 (17/18 attacks detected; F2 is within the preservation boundary) |
| HD-1 | `superseded_by_sources` = EVIDENTIARY(LIFECYCLE), whole-file required; `contradicted_by` must not remain a free list, and its target Track-A statement must be explicitly recoverable; **the exact mapping from timeline classes to summary fields is written into the contract before implementation** |
| HD-2 | **Model D ADOPTED:** the harness transcript is the authoritative execution witness **within the declared trust boundary**. Trusted: the harness, the orchestrator, the OS account. Not trusted: agent-authored READ-LOG, provenance, evidence and claim-evidence. The transcript is independent of agent self-authored artifacts, not universally independent. C1–C7 are the conditions (elevated to invariants W1–W7) |
| HD-3 | the trust boundary is MUST-FIX BEFORE S5 |
| HD-4 | no R2 / R2.1 / R2S rerun inside an activated R7 batch; a rerun requires a new governed run, a new plan and an explicit human/governance act |
| HD-5 | every fact quote used as observed evidence must lie on a page witnessed by the owning run |
| HD-6 | n = 0 → NOT-ESTIMABLE for the target population; n = 1 → variance and CI NOT-ESTIMABLE; no partial-identification bounds in R7 |
| HD-7 | M3 is MATERIAL (it affects D4, the lifecycle and `end(p)`) |
| HD-8 | create Revision 7; Revision 6 is not amended and stays an immutable audited object (`bc6fb01bb`) |
| HD-9 | after R7 implementation, ONE narrow independent adversarial audit; never automatic |
| Authorized | Revision 7 in this sequence: **R7 contract/design freeze → implementation → engineering verification → STOP**; then a separate human decision on the independent R7 audit |
| Not authorized | activation; manifest regeneration; binary pre-classification; S5; production corpus reads; H-19; S5c; ML in the evidence path; modifying R6, F-Series, application code, the production ledger, P3B-STATE, the manifest or Master Protocol v3.5 |

## G-LOG-0086 — Human decision: PF-1…PF-8 accepted as design-repair input; ONE bounded R7 DESIGN-REPAIR slice authorized; FD-9 not added; no implementation

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26 (review of `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md`) |
| Status of the review | accepted as a **design-repair input, not as independent proof**. It was authored by the same agent/session as the R7 design, so the repaired design still requires ONE independent adversarial R7 audit (HD-9) |
| Accepted material findings | PF-1 (summary ≠ adjacent class) · PF-2 (contradiction ≠ temporal precedence) · PF-3 (discovery vs validation) · PF-4 (registry sovereignty) · PF-5 (W8 execution-capability closure) · PF-6 (W1 decision table; no agent resumption) · PF-7 (W7a witness monotonicity) · PF-8 (byte-exact fact anchoring) |
| Non-material repairs to apply | PF-9 (neutral transcript syntax decoder; no V1.2.4 dependency) · PF-10 (reader-generated invocation id, convenience only) · PF-11 (archive = preservation, not trust) · PF-12 (execution truth ≠ semantic truth) · PF-13 (module split: universe, witness, evidence, stats, verify; the verifier composes only) · PF-14 (REJECT vs NOT-ESTIMABLE; N_h = 0; realized n_h) |
| FD-9 | **not added**: no minimum quote length; the trivial-quote issue stays a future research/governance question |
| Authorized | modify only the R7 addendum candidate, the ADR if necessary, the R7 plan, the R7 test matrix, and the governance/TODO records; static design-consistency checks; an implementation record `audit-p3b/20260926_R7-DESIGN-REPAIR-IMPLEMENTATION-RECORD.md` |
| Not authorized | R7 runtime code; activation; manifest regeneration; agent dispatch; corpus reads; binary pre-classification; S5; H-19; an automatic audit; changes to R6, F-Series, application code, the production ledger, P3B-STATE, the manifest or Master Protocol v3.5 |
| End state | R7 DESIGN-REPAIRED · NOT IMPLEMENTED · NOT ACTIVATED · AWAITING HUMAN DESIGN FREEZE |

## G-LOG-0087 — Human decision: FR-1 closed by OPTION A (bounded per-run input manifest I(run)); minimal §26 design repair only; no freeze, no implementation

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26 (review of `audit-p3b/20260926_R7-FREEZE-READINESS-RECORD.md`) |
| Finding | FR-1: W8 forbade Read, while the execution model requires agents to consume run-owned inputs (contract, addendum, plan, slice, unit records for synthesis, permitted persisted tool outputs). This was a systematic false-FAIL path for DECOMPOSED synthesis |
| Decision | **Option A:** I(run) = a frozen, explicit, hash-bound per-run input manifest owned by the Universe context. AllowedRead(r, p) ⇔ p ∈ I(r); any p ∉ I(r) is `R7-W`. **Option B (inline prompt delivery) rejected.** Unrestricted Read is **not** reopened. Input reads are execution events, never evidence |
| Scope | minimal repair of the R7 addendum (W8, the I(run) schema, the `read-input` witness event) + the FR-1 tests + design records |
| Unchanged | DR-15, DR-16, PF-1…PF-8 (PF-5 except the I(run) addition), the strong-Kleene verdicts, the statistics, the reconstruction semantics, the EXTENDS proposal (FD-1′b open), the archive decision (FD-4′ open), the ML boundary, Master Protocol v3.5, the P3b architecture |
| Not authorized | freeze; implementation; activation; dispatch; corpus reads; binary pre-classification; H-19; S5; changes to F-Series or application code |
| Stop rule | any new architectural inconsistency → report NEW-DESIGN-BLOCKER; do not solve it automatically |

## G-LOG-0088 — Human act: R7 DESIGN FREEZE (v2.3); FD-1′b = later_refinement; FD-4′ archive = ~/knowledgeos-witness-archive/; implementation B0–B9 + engineering verification authorized; STOP before audit

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-26: "S-SERIES — R7 FINAL DESIGN FREEZE AND CONTROLLED IMPLEMENTATION" |
| Frozen contract | `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` **v2.3**, sha256 `cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211`. Any later change is a contract change needing a human act |
| FD-1′b | **EXTENDS → `later_refinement`**: a relationship classification, not a truth judgment; never SAME, RESTATES, corroboration or canonical identity |
| FD-1′c | frozen: every `later_*` relationship requires ESTABLISHED source precedence from the A.10 dated chronology; never filesystem, filename, discovery, reading or processing order; no manufactured `later_*` relation |
| FD-4′ | **`~/knowledgeos-witness-archive/`**; verified outside git, writable, on persistent storage (btrfs on LUKS `/home`); policy README sha256 `d6e2f368…064c`. It is a single local disk (persistent, not backup-redundant). Preservation, not trust; retained until P7; digest-verified against freeze commits |
| Clarification added | verifier resolver reads are integrity-verification operations only; they produce no corpus observation, Evidence, Reconstruction or Theory object; seal and hold-out rules apply |
| Other FDs | FD-1′a, FD-2′, FD-3′, FD-5′, FD-6′, FD-7′, FD-8′ (Option A), FD-10 frozen as proposed; FD-9 not adopted |
| Authorized | implementation of B0–B9 (test-first, one bounded context at a time, fresh synthetic fixtures only), property/exhaustive tests where feasible, persisted-output security tests, engineering verification |
| Not authorized | R7 activation; S5 dispatch; corpus reads by agents; production-state changes; H-19; sample freeze; the independent audit; self-certification; changes to F-Series or application code |
| Stop | after implementation + engineering verification; next is a human authorization of ONE narrow independent adversarial R7 audit |

## G-LOG-0089 — Human act: R7 contract v2.4 APPROVED AS PROPOSED (NDB-1, DC-1, INV-LEX); DC-2 APPROVED; RI-1 DEFERRED; implementation + engineering verification authorized; STOP before the audit

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-27: "v2.4 approved as proposed; DC-2 approved; RI-1 deferred" |
| Amends | `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` v2.3 (sha256 `cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211`, `05569322d`) → v2.4, exactly as `audit-p3b/20260927_R7-v2.4-CONTRACT-AMENDMENT-PROPOSAL.md` (`9654a6d91`). **v2.4 sha256 `fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674`** |
| v2.4 content | three META rows (`absences.<dim>.reason`, `stage2_dispositions[*].reason`, `timeline[*].historical_position`); `escalations[*].field` as META with the own-object self-reference rule; invariant INV-LEX (lexical source-id occurrence ≠ evidentiary support) |
| DC-2 | the R6 positive control is retargeted to "R6 = PASS ∧ revision 6 superseded" (the prepared diff); an authorized exception to the R6 test immutability of G-LOG-0085, for this one test only |
| RI-1 | DEFERRED, not accepted as harmless: an incomplete input read (harness token cap) is accepted by v2.3/v2.4. It is revisited only if it threatens S5 execution, an actual production input is shown to be affected, or the independent audit shows it can invalidate S5 evidence. No production input is read to investigate it now |
| Authorized | the v2.4 implementation (no redesign; no Witness/Evidence/Reconstruction/Statistics/composer change beyond the proposal), the DC-2 test change, engineering verification, and preparation of the authorization package for the ONE independent R7 audit |
| Not authorized | activation, S5, corpus reads, binary pre-classification, sample freeze, the audit itself, self-certification, F-Series changes |
| Stop | after engineering verification; next is a human authorization of ONE independent adversarial R7 audit (on v2.4) |

## G-LOG-0090 — Human act: ONE independent adversarial R7 (v2.4) audit AUTHORIZED per the audit authorization package; auditor = a fresh agent on a different model

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-27: "can you authorize this work to agent to audit and review the work independently?" |
| Scope | `audit-p3b/20260927_R7-INDEPENDENT-AUDIT-AUTHORIZATION-PACKAGE.md` (`8071230f1`): addendum v2.4 (`fa837177…`), the R7 modules, gate, reader, tests and developer guide; target ∃ s: BATCH-PASS(s) ∧ ¬VALID(s); invariants I1–I8; the RI-1 assessment |
| Auditor | a fresh general-purpose agent **on a different model (Sonnet 5)** with no context from the implementation session. **Independence limitation:** it is dispatched by the implementation session (the prompt is the only channel), so it is not organizationally independent; model and context are |
| Rules | read-only on the repository except its single report `audit-p3b/20260927_R7-INDEPENDENT-AUDIT.md` and its own scratch directory; synthetic data only; no corpus, ledger, state or H-19 access; no activation; no commits; no ML in any verdict |
| Not authorized | any repair; activation; S5; self-acceptance. The auditor recommends; acceptance is a human act |

## G-LOG-0091 — Human decision: independent audit result ACCEPTED (NEEDS-REVISION; F-01 MATERIAL); ONE bounded F-01 Witness repair authorized; ONE focused independent re-check (F-01 + I1 + I7) authorized; R7 NOT yet accepted

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-27 ("F-01 → gezielter Test → minimaler Repair → Engineering Verification → fokussierter unabhängiger Re-check → R7 akzeptieren → S5 aktivieren") |
| Audit | `audit-p3b/20260927_R7-INDEPENDENT-AUDIT.md` (`57d8e95f6`): F-01 accepted as a real soundness defect in the Witness layer (persisted-output authorization by basename; BATCH-PASS ∧ ¬VALID reproduced). F-02, F-03 observations; RI-1 stays DEFERRED |
| Repair invariant | a persisted-output Read is authorized only when the requested path resolves to the exact persisted-output path the harness announced for that agent, and the displayed content matches the announced artifact. Tests first; Witness-local; if the contract or another layer would have to change: STOP and report |
| Re-check | ONE short focused independent re-check (fresh agent, different model) of F-01, I1 (Universe closure) and I7 (binding/hash closure): search ∃ s: BATCH-PASS(s) ∧ ¬VALID(s). No full second audit unless it finds another MATERIAL issue |
| Not authorized | activation, S5, corpus reads through S5, sample freeze, any other repair, F-Series changes |
| Stop | after the focused re-check, for human acceptance of R7 |

## G-LOG-0092 — Human act: R7 ACCEPTED (contract v2.4 `fa837177…` + F-01 repair `47b3d3b5b`); RC-02 DEFERRED; RC-03 fix authorized before S5 activation

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-27: "R7 accepted; RC-02 deferred; RC-03 fix authorized before S5 activation." |
| Basis | independent audit (`57d8e95f6`, F-01 MATERIAL) → F-01 repair (`47b3d3b5b`) → focused independent re-check `audit-p3b/20260927_R7-FOCUSED-RECHECK.md` (`7d8b55b71`): ACCEPTABLE, 0 MATERIAL |
| RC-02 | DEFERRED: an empty persisted output with an empty display gives a false FAIL (never a false PASS) |
| RC-03 | fix authorized: a manifest entry whose file is missing at verification must FAIL its frozen-integrity check instead of skipping it (`p3b_s5_r7_verify.py`); tests first; no contract change |
| Still deferred | RI-1 (G-LOG-0089 conditions) |
| Not authorized | S5 activation, sample freeze, binary pre-classification, corpus reads. Next: the experiment-design freeze (B1) as a proposal for human approval |

## G-LOG-0093 — Human decision: B1 approved CONCEPTUALLY; conditions before the freeze; minimal v2.5-S statistics amendment authorized (tests first); no binary read, no S5

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-27: "approve B1 conceptually, but require the θ_D/θ_E terminology correction and formal Model-0 definition before the freeze … implement only the minimal statistical v2.5-S amendment, with tests first — no architecture redesign and no S5 execution yet. The next concrete artifact should therefore be a B1 v2 freeze candidate" |
| Conditions for B1 v2 | (1) θ_D = reconstruction disagreement; θ_A = disagreement with independent adjudication; θ_E = actual error **only** with an independently justified reference standard; (2) Model 0 defined operationally, per hypothesis; (3) reproducibility split into R1 (record) and R2 (structural invariance); (4) each candidate competes against Model 0, reconstruction-artifact models and competing structural models; (5) confirmatory claims under FWER control, exploratory discovery under FDR; (6) H-19 strictly last; (7) a small theory-candidate registry |
| v2.5-S | authorized: the exact finite-population (hypergeometric) upper bound per stratum for any d → a Bonferroni combination across sampled strata → the overall bound as the primary statement; the normal CI only under a count condition, otherwise informational. Statistics context and addendum §8 only; tests first. **Resulting addendum v2.5 sha256 `63fdda65b02a2b9822984edfac2ab9a65136fe2b7889c2ab53611181072ad939`** (DR-19; T105–T109) |
| Not authorized | the freeze act, the binary read, activation, S5, corpus reads, any other R7 change |

## G-LOG-0094 — Human act: FREEZE 1 (scientific design) + binary pre-classification authorized; STOP before the 12 decisions

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28: "Freeze 1 approved. Freeze exactly the following scientific design and nothing else … Now perform the following bounded next action: Binary pre-classification … Then STOP" |
| **FROZEN (Freeze 1)** | B1 v2 `audit-p3b/20260927_B1-EXPERIMENT-DESIGN-FREEZE-PROPOSAL.md` sha256 **`d6b238866b637249f20c0a0f8fb7f9d1b9afd619af8124d02868ac463ee82163`** · instrument `research/prereg_v1.py` sha256 **`eb6291abfe0653ea0a5dcaee8c0dcaf04ab6d47ecf40b3cb960dda103ac1a1f5`** — θ_D / θ_A / θ_E (θ_E only relative to a justified R\*; reliability ≠ validity), R1/R2, H1–H5, Model 0, artifact and competing models, Holm (confirmatory) / BH (exploratory), α = q = 0.05, B = 9,999, failed / NOT-ASSESSABLE rules, decision fields, H-19 strictly last |
| Immutability | no later observation may change hypotheses, estimands, thresholds, null models, sampling logic or decision rules; a methodological problem found later is recorded as a finding, never patched into the frozen design |
| Authorized now | the binary pre-classification of the required binary files through the seal-aware resolver (no content printed, no exploration; per-file classification only) |
| Not authorized | the 12 decisions (human), Freeze 2, seed, sampling, activation, canary, S5, H-19 |

## G-LOG-0095 — Human decision: binary frame reconciliation ACCEPTED (12 files / 41 labels); IV-1a (verified re-basing) adopted as an execution-layer correction; the bounded 12-file read authorized after adversarial synthetic tests

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 ("Reconciliation accepted + IV-1a + 12-file bounded read", with the synthetic adversarial test requirement) |
| Frame | the historical quarantine-aware frame: 12 binary files, 41 labels, 1,731,013 bytes, 0 sealed, 525 stage-1 hit offsets (`audit-p3b/20260928_BINARY-FRAME-RECONCILIATION.md`) |
| IV-1a | an **instrument execution-layer correction, not a change to Freeze 1 and not a change to the frozen classifier**: stage-1 normalized offsets are re-based to the raw decoded coordinate the classifier expects, and each re-basing is verified; **mapping uncertainty ⇒ HUMAN-REVIEW, never FALSE-HIT** |
| Precondition | the re-basing adapter is tested first on synthetic adversarial bytes (NFKC contraction and expansion, compatibility characters, LaTeX shortening and arguments, casefold expansion, multiple and adjacent hits, boundaries, NUL, PNG/ZIP, ambiguity, invalid UTF-8, replacement characters, repeated terms) |
| Authorized | then the read of exactly those 12 files through the seal-aware resolver; per-file pre-classification only; no content printed or persisted |
| Stop | after the pre-classification, for the 12 human decisions. No Freeze 2, activation, canary or S5 |

## G-LOG-0096 — Human decisions: the 12 per-file binary decision records (5 FALSE-HIT, 7 NOT-CONSUMED-ESCALATED, no EXTRACT); IV-2 to be resolved before Freeze 2

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 ("Accept the 12 binary decisions … Resolve IV-2 BEFORE Freeze 2") |
| FALSE-HIT | S0147, S0423, S0676, S1864, S1869 (every stage-1 hit VERIFIED under IV-1a and in NONTEXT bytes) |
| NOT-CONSUMED-ESCALATED | S0675, S1674, S1757, S1977, S1979, S1983, S2276 (text-like regions or unverifiable mappings; the unread state is preserved, G-04 never thinned) |
| EXTRACT | none (no validated extractor; no EP-01 conditional census stratum) |
| Records | `audit-p3b/20260928_BINARY-DECISIONS.json` (per file: decision, candidate, format, content sha256, basis) |
| Not authorized | Freeze 2, seed, sampling, activation, canary, S5 |

## G-LOG-0097 — Human act: Freeze 2 proposal APPROVED; ORD-1 = (b) activate first, derive strata from the frozen plan, then seed + sample; AG-1 implementation authorized

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28: "Freeze 2 proposal approved; ORD-1 (b); AG-1 implementation authorized" |
| Approved | `audit-p3b/20260928_FREEZE-2-PROPOSAL.md` (sha256 `cd15658b95951811…`): frame of 1,975 labels (sha256 `d0c972bf…`), strata HUB 66 / EMPTY 7 / MULTIROW 4 / DECOMPOSED 173 / SINGLE 1,725, rates and n = 231, the α/H′ exact-bound plan, the θ_A and double-adjudication subsamples, the treatment of the 12 binary files |
| ORD-1 (b) | R7 activation (manifest + frozen production plan) → strata re-derived from the frozen plan must equal the approved strata → the Freeze 2 act (CSPRNG seed + sample) → S5 authorization. All before any S5 dispatch |
| AG-1 | implementation authorized: deliver the 12 binary decision records to agents and to the R7 verifier. Tests first. **If this requires a change to the frozen contract (v2.5), STOP and report** |
| Not authorized | activation, the seed, sampling, the canary, S5 |

## G-LOG-0098 — Human decision: AG-1 = option A (no contract amendment): all 12 binary files are executed in S5 as NOT-CONSUMED-ESCALATED

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28: "AG-1: option A." |
| Effect | the reader's `BINARY-CONTENT` refusal is the delivery. Every disposition of a hit on one of the 12 files uses method NOT-CONSUMED-ESCALATED (every dimension ESCALATED) with a CONTRACT-DEVIATION escalation naming the file. The 5 FALSE-HIT decisions (G-LOG-0096) stay recorded but are **not operative in S5**. Resolution loss is possible in ≤ 5 labels, in the conservative direction only |
| Contract | v2.5 unchanged |
| Authorized | one full-path test that this path passes R7; then STOP for the activation act |

## G-LOG-0099 — Human act: contract amendment v2.6-DC3 authorized, B-form, tests first (decided binary files covered by their decisions)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28: "v2.6-DC3 authorized, B-form, tests first." |
| Content | (1) the frozen plan carries `binary_decisions` for each label's required binary files, derived from the hash-bound `audit-p3b/20260928_BINARY-DECISIONS.json`; agents do not call the reader on them (a call remains a W4 stop); (2) G-04 and the R7 census/reading rules count a decided binary as **decided, not missing**, iff its dispositions match its decision (B-form: FALSE-HIT → hits disposed FALSE-HIT; NOT-CONSUMED-ESCALATED → method NOT-CONSUMED-ESCALATED, all dimensions ESCALATED, CONTRACT-DEVIATION naming the file); (3) every mismatch FAILS |
| Unchanged | Freeze 1; the Freeze 2 frame, R(L) and strata; all other R7 rules |
| Supersedes | G-LOG-0098 option A (infeasible under v2.5: DC-3) |
| Result | addendum **v2.6** sha256 **`870a595245be478a3c5c618664430559a424ddd73ed99dae312d4bd6cf3b3dd6`** (§6.1, DR-20, T110–T116) |
| Not authorized | activation, seed, sampling, canary, S5 |

## G-LOG-0100 — Human instruction: AG-1 checklist hardening of v2.6-DC3 (within the G-LOG-0099 authorization); STOP before activation

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 (AG-1 checklist: authority/hash, expected IDs, decision values, content SHA-256, missing/unexpected/duplicate/EXTRACT/malformed/empty/tampered/replaced/unavailable artifact, reader/verifier disagreement, provenance kept, the frame provably unchanged) |
| Implemented | all-or-nothing artifact validation (artifact name, header-bound `r7_binary_decisions_authority`, non-empty, per-record S-id/decision/content_sha256/authorized_by, no duplicates, EXTRACT rejected); agreement with the resolver bytes (a decided file must be binary per `r5.is_binary`; content sha256 matches; an undecided required binary FAILS); frame invariance tested. Tests first (T117, T118 + unit cases RED → GREEN) |
| Result | addendum v2.6 (hardened) sha256 **`ead875a22895163f59dfa8797738cfe96f8564dc4d57371ce2f546d8a26fd32d`**; the governed artifact `audit-p3b/20260928_BINARY-DECISIONS.json` (sha256 `7f884b09…`) validates: 12 decisions, 0 violations |
| Not authorized | activation, seed, sampling, canary, S5 |

## G-LOG-0101 — Human instruction: proceed with the R7 activation gate — executed as a DRY RUN (outside the repository); AF-1 found and repaired; production write NOT yet performed

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 ("STEP 1 — R7 ACTIVATION … Activation must NOT dispatch corpus reads, generate the seed, sample labels, execute S5 …"). Freeze 2 (seed + sample) and S5 stay separate human acts |
| Dry run | `scripts/p3b_s5_prepare.py --materialize-all --emit=<outside repo>`: all 396 batches' slices rebuilt, **every slice hash-verified against the production manifest**. `scripts/p3b_s5_r7_activate.py --dry-run`: 396 frozen plans derived with metadata sizes (`r5.all_blob_sizes`; no corpus byte read) and the hash-bound binary decisions. **Strata HUB 66 / EMPTY 7 / MULTIROW 4 / DECOMPOSED 173 / SINGLE 1,725 = the approved Freeze 2 strata; frame sha256 `d0c972bf…` = the approved frame**; all 12 decided files present in the plans (42 labels carry a decided file); legacy directories only for OB0004 |
| AF-1 (defect found by the dry run) | `knowledge-measure-theory-v0-1` (OB0181) has the decided binary S0675 as a **row source only**, with no stage-2 hit. The v2.6-DC3 rule "at least one disposition" would have failed it. Repaired tests-first: dispositions are required only for a decided stage-2 hit file. Addendum v2.6 sha256 **`4916932802ca9acf68cee404aea6c0a7a725f2874df275386c64a3f7ac155e6e`** |
| Not performed | the production write (manifest, slice root, plan files), seed, sampling, dispatch |

## G-LOG-0102 — Human decision: AG-2 authorized as a narrow governed R2→R7 execution-identity transition (tests first); prepare --check verifies the rev3 BASE; OB0018 pilot assertion re-pointed to the rev3 base; staged production activation authorized; STOP before Freeze 2

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 ("AG-2 — CONTROLLED R2→R7 EXECUTION-IDENTITY TRANSITION … Proceed with AG-2 tests-first and STOP before Freeze 2") |
| AG-2 invariant | for every batch B: run_id B-R2 → B-R7 exactly; Composition_R2(B) \ run_id = Composition_R7(B) \ run_id (id, order, tier, hub, labels, checklist, weights, predicted); eligible only if every batch is PREPARED or FAILED; a governance reason is recorded; R2 history (incl. OB0004 FAILED) preserved; not a general remap facility |
| Frame invariant | Frame_R2 = Frame_R7 (population, labels, strata, frame hash, sample design, estimands, hypotheses unchanged); ExecutionIdentity_R2 ≠ ExecutionIdentity_R7 |
| Activation | staging first (R7 manifest, 396 rev7 slices with run_id B-R7, 396 frozen plans, AG-2 transition), full verification, then the production write and the state rebind |
| Not authorized | Freeze 2, seed, sampling, canary, S5 |

## G-LOG-0103 — Human act: FREEZE 2 executed (OS-CSPRNG seed + the 231-label audit sample); STOP before S5 authorization

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28 ("Execute now ONLY: 1. commit verified R7 slices; 2. execute Freeze 2; 3. STOP") |
| R7 slices | committed `36ba7f578`: 1,975 files, every hash = the production manifest `c7f3b257…`, quarantine 0/0 |
| Seed | **128527203382009690619999539329395210354** (`secrets.randbits(128)`, OS CSPRNG, drawn at the act) |
| Sample | `p3b_s5_r7_stats.freeze(frame, rates, seed, spec = B1 v2, α = 0.05)`: HUB 33/66 · EMPTY 7/7 · MULTIROW 4/4 · DECOMPOSED 87/173 · SINGLE 100/1,725 = **231**; **sample sha256 `e3f3ed76fe31752d960527914b953070da5c1e471b10be1d002f4dfb4efc96ad`**; reproduced from the seed |
| Anchor | **frozen-record sha256 `8e0d9cb4d3dbaada54d5a24b6352994e333ff7c355af6b71d64799790d8b32e2`** (`estimate_v7` anchor; domain check STATISTICS-VALID) |
| Bindings | frame `d0c972bf…` · spec (B1 v2) `d6b23886…` · plans `9239ccad…` · manifest `c7f3b257…` |
| Subsamples | separate seed **200059834720872615579209696013017923603**: θ_A = the MULTIROW census (4) + 10 SINGLE drawn from the SINGLE sample; the double-adjudication subsample = 20 of the 231 |
| Record | `audit-p3b/20260928_FREEZE-2-RECORD.json` (the full frozen record, provenance, Python version and `random` module sha256) |
| Not authorized | S5 authorization, dispatch, the canary, S5, any read of sampled content, any design change |

## G-LOG-0104 — Human act: EG-2 SLICE-VIEW amendment v2.7 and EG-1 orchestrator tool authorized (tests first); S5 execution package approved

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-28: "EG-2 SLICE-VIEW v2.7 and EG-1 orchestrator authorized (tests first); package approved." |
| EG-2 | a deterministic, lossless, multi-line rendering of each frozen slice (SLICE-VIEW), hash-bound as an I(run) input category; the verifier checks view = slice_view(slice) and parse(view) = slice; slices and their hashes unchanged (RI-1 reopened on its condition 1) |
| EG-1 | `scripts/p3b_s5_r7_orchestrate.py` (prepare / archive / freeze-units / freeze-final / verify), reusing the production functions; no dispatch by the tool |
| Approved | `audit-p3b/20260928_S5-EXECUTION-PACKAGE-DRAFT.md` (runbook, dispatch prompt template, canary OB0012 + OB0114 with the ≥ 50% stop rule, audit protocol) |
| Not authorized | the S5 execution itself (canary and batches): a separate act |
| Addendum v2.7 | sha256 `9523c712efd8c0e67d701b7e0003b85bf3f683e457d2b15aa2d6e1a3b854b367` (§5.8 category, §5.9, T119–T121, DR-21); supersedes v2.6 + AF-1 `49169328…` |

## G-LOG-0105 — Human act: EG-4 v2.7 production rebind authorized (tests first); EG-4 only

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-29: "EG-4 v2.7 production rebind is authorized. Execute only EG-4. Do not dispatch agents and do not execute the S5 canary." |
| Scope | rebind the production manifest contract binding v2.6+AF-1 `4916932802ca…` → v2.7 `9523c712efd8…`; rebind P3B-STATE consistently; staging first; preserve the 396 R7 PREPARED batches |
| Unchanged by mandate | 1,975 slices · 396 plans · population · labels · strata · Freeze 2 frame, seed and 231-label sample · hypotheses · estimands · sample design · H-19 seal |
| Not authorized | any dispatch; OB0012 / OB0114 / the S5 canary; any sampled-content read; EG-3; RC-02; R7 re-run naming |
| Tool | `scripts/p3b_s5_r7_activate.py --rebind-contract` (`rebind_contract`, reusing `p3b_s5_state.rebind_manifest`); tests first: `scripts/tests/test_p3b_s5_r7_contract_rebind.py` RED 7/7 → GREEN 7/7 |
| Contract binding | `4916932802ca9acf68cee404aea6c0a7a725f2874df275386c64a3f7ac155e6e` → `9523c712efd8c0e67d701b7e0003b85bf3f683e457d2b15aa2d6e1a3b854b367`; the header gains `r7_contract_rebinds` (from, to, authority); no other header field changes |
| Manifest | `c7f3b2574ba49daade87507eede1a9c658fb76a62a1683be0af70e589ebfa437` → `9b172123d1dc274c310d9c43698e3f9c6eb067277fbe5c85893c96a95266171c`; line 1 only; the 396 entry lines are byte-identical (body `output_sha256` `9bba3155…` unchanged). The Freeze 2 record's `manifest_sha256` (`c7f3b257…`) stays as history: its frame content is the unchanged body |
| P3B-STATE | one appended MANIFEST-REVISION entry (history head `0cbd3e2a…`); hash chain valid; 396 batches PREPARED at R7, batch records unchanged; prior history and the v2.6 manifest hash retained in `manifest_revisions` |
| Verification | pre-rebind suite 1,117 OK; `prepare --check` IDENTICAL (rev3 base; R7 entries consistent); H-19 SEALED; `orchestrate prepare OB0012` on a temporary copy: ACCEPTED (5 labels, 5 views lossless, 5 I(run) with 0 violations, 5 prompts, 0 quarantine hits); no production view or I(run) written |
| Not done | nothing dispatched; no sampled content read; canary not run |
| Post-rebind suite | 1,117 tests OK (1 skipped), against the rebound production state |

## G-LOG-0106 — Human act: the v2.8 decision package parts (a)–(g) approved; correction-record reading R-I; proceed

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-29: "v2.8 package parts (a)–(g) approved; R-I; proceed" |
| Package | `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md` at commit `b09be52e6` (working papers `audit-p3b/eg3/`, `eg5/`, `eg6/`, `eg9/`) |
| (a) | §2.6 record identity: record `run_id = <B>-R7`; `rs_id`/`gap_id` n = 1000·L + k; dispatched id kept for directories, reader, READ-LOG, claim-evidence, I(run) and binding |
| (b) | §2.7 assembly by the orchestrator tool inside the ASSEMBLED marker; the EMPTY object by rule EMPTY-OBJECT v1 (Annex E), proposed by the orchestrator agent |
| (c) | in-checklist EMPTY labels: vacuous examination (`checklist_examined` 1..23, VACUOUS-OVER-EMPTY-REQUIRED-SET) |
| (d) | EG-6: retire a failed attempt (crash-safe, same ids, fresh session); the pre-registered retry policy RR-1..RR-7; classes X/A1/A2/H/D/S/U; M = 4; watchdog W-SYS (K = 20, α_w = 0.01 per look, q* = 0.12771); the named retry-survival limitation and exploratory reporting — declared **before** any S5 output |
| (e) | EG-8: EP-01 `audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md` is committed **unchanged** on this approval, sha256 **`f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa`**. This binds the base that Freeze 1 (G-LOG-0094, B1 v2 `d6b23886…`) consumes by reference. It is recorded as a gap closure, not a rewrite of the Freeze 1/2 records |
| (f) | EG-3: hub runs display R(run) (addendum §5.9a); RI-1b coverage report-only; T(L) = citable S-ids; the same R(run) for every role |
| (g) | EG-9: keep §21 under R7 (the audit tool extended to `<B>-R7`, bound to sample, assembly and VERIFIED state); H-06 by sha-anchored tranches (`check_tranche`, about 80 tranches); G-LOG rulings: firewall (no Freeze-2 input to §21; the §21 auditor is never the S5 reader nor a θ_D/θ_A auditor; no §21 material in θ_D/θ_A inputs); a §21 FAIL or H-06 rejection is class H (FAILED, no automatic re-run); EG-10 runbook step 7b (→ PROPOSED) |
| Correction record (H-EG9-6) | **R-I**: the estimation object is always the witness-verified assembly; §21 correction records are stored and reported; a correction is applied only by a separate human act authorizing a fresh witnessed S5 run for the excepted content, never by a bypass write |
| Authorized now | implementation of (a)–(g), tests first, under the A/B loop; addendum v2.8 text; one staged production rebind (EG-4 procedure); the canary pre-check re-run |
| Not authorized | any dispatch; the S5 canary; any sampled-content read; executing a §21 audit or an H-06 acceptance; any freeze change |
| Addendum v2.8 (frozen) | sha256 `4d4dac6b5684a6ca60384d6654d50d798273e39ac487e5966329ac8926f0cb87` (§2.3/§2.4 amended, §2.6–§2.8, §5.9a, Annex E hash-bound to EG5-SPEC-A v1–v3, T122–T137, DR-22); static checks 73/73 (137 tests); supersedes v2.7 `9523c712…` |
| Implementation | S1 assembly/EMPTY/RECORD IDS/freeze-final checks · S2a state attempts/W-SYS · S2b retire/may_start_attempt/T134 anti-replay · S3 hub R(run)/coverage · S4 §21 R7 + check_tranche + P3B-CORRECTIONS (R-I) + allowlist +3; each tests-first and independently reviewed to IMPL-ACCEPTABLE (`audit-p3b/impl/`); full suite 1,400 OK |
| Production rebind (v2.8) | `p3b_s5_r7_activate.py --rebind-contract --reason G-LOG-0106`, staged: contract `9523c712…` → **`4d4dac6b5684a6ca60384d6654d50d798273e39ac487e5966329ac8926f0cb87`**; manifest `9b172123…` → **`5978fbf5d9b72fed27142d9110e1c1bf32d401ca87c5eca1d8e92dbf13c0c650`** (header line only; the 396 entry lines are byte-identical, body `output_sha256` `9bba3155…` unchanged); P3B-STATE: one MANIFEST-REVISION entry (head `ceba20cd…`), chain valid, 396 PREPARED at R7, attempt 1 |
| Verification | pre- and post-rebind full suite **1,400 OK** each; `prepare --check` IDENTICAL; H-19 SEALED; canary pre-check v2.8 (`audit-p3b/20260930_S5-CANARY-PRECHECK-v28.json`): OB0012 + OB0114 = 11 runs, 0 view/I(run) violations, RECORD IDS 11/11, OB0114's EMPTY object passes the self-check, the guard allows start, W-SYS 0/0; tool files frozen (H-3) |
| Not done | nothing dispatched; no sampled content read; the canary not run; no §21 audit or H-06 act |

## G-LOG-0107 — Human act: the S5 canary authorized (OB0012 + OB0114)

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-30: supplied the operating prompt stating "The human has now explicitly authorized the S5 canary … Execute OB0012 + OB0114, 11 total agent runs … with the frozen S5 v2.8 execution protocol", with the instruction "Read, analyse and follow the prompt if you agree" |
| Scope | the canary only: OB0012 (5 SINGLE runs) and OB0114 (3 SINGLE + 1 DECOMPOSED = 2 UNIT + 1 SYNTHESIS, + 1 EMPTY label), under contract v2.8 (`4d4dac6b…`) and the runbook of the execution package §6 |
| Stop rule (frozen) | stop if ≥ 6 of the 11 runs FAIL, or if the same W-code occurs in ≥ 2 runs; no automatic restart; a failed batch may be retried only under the pre-registered EG-6 policy (a fresh session), otherwise a human act |
| Interpretation | an instrument / execution-integrity experiment only; no evidence for or against H1–H5 |
| Declared deviation | the runbook says "a dedicated orchestrator session". The canary is dispatched from the current orchestrator session. Its transcript was verified with the production decoder before dispatch (25,089 records; 0 parse errors; 0 missing parents; 0 duplicate uuids). The witness counts only Agent calls whose first line is an `S5-RUN-BINDING`, so earlier builder/reviewer dispatches are ignored. Any witness failure attributable to this is class X (the retry runs in a fresh session) |
| Frozen tools | the 10 tool files equal `audit-p3b/20260930_S5-CANARY-PRECHECK-v28.json` `tool_sha256_frozen_for_canary` (H-3), verified before dispatch |
| Not authorized | any batch beyond the canary; any §21 audit or H-06 act; any freeze change |
