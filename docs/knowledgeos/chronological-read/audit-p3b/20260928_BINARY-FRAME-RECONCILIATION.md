# Binary frame reconciliation and offset-coordinate finding (before the binary read)

| | |
|---|---|
| **Kind** | Engineering / measurement-validity record. ⚠ authority: generated. **No corpus byte was read for it.** Metadata, committed code and the session transcript (for the historical command) only |
| **Authority** | G-LOG-0094 (Freeze 1; binary pre-classification authorized, not yet run) |
| **Freeze 1** | untouched (B1 v2 `d6b23886…`, `prereg_v1.py` `eb6291ab…`) |

## A–D. The 12/41 frame: reproduced; the 13/59 was my derivation's error

| Definition | Source | File universe | Binary rule | Label universe | Files | Labels | Status |
|---|---|---|---|---|---:|---:|---|
| **Historical (G-LOG-0054)** | the exact command in the 2026-09-25 session transcript (11:53:35Z), output `12 {PNG 2, ZIP 3, NUL 7}`, `1731013`, `41` | `P3B-S5-PREPARE.Context`: non-hub stage-2 hits (raw ∪ ledger) + OMQ-14 row sources of the 1,975 plan labels, **after prepare's H-19 quarantine**; 2,558 required files | byte sniff: PNG/JPEG/PDF/ZIP/GIF signature, NUL in the first 64 KB, or not UTF-8 | labels touching a binary required file | **12** | **41** | measured, by a checker read, counts only |
| **Historical universe ∩ manifest `BINARY` flag** (re-run 2026-09-28, metadata only) | this record | as above (2,558 reproduced) | identity manifest `search_eligibility = BINARY` | as above | **12** | **41** | **REPRODUCED**: files, labels and bytes (1,731,013) identical. The byte sniff and the manifest flag agree on these 12 |
| **My first derivation (2026-09-28)** | driver v0 | raw `P3B-ABSENCE-SEARCH` LABEL-HITS of the floor, non-hub, **without the H-19 quarantine** | manifest flag | as above | 13 | 59 | **ERROR (implementation difference):** the 13th file is a **sealed hold-out file** (count-only check), hit by 41 labels in the unquarantined search. Prepare drops those hits |

**Conclusion.**
- The discrepancy is an **implementation difference in my derivation**. It is not a historical measurement error and not metadata drift.
- The historical frame is correct and reproducible from existing artifacts.
- The driver now uses the historical definition: prepare's Context, quarantine-aware.
- The corrected derivation gives 12 files, 41 labels, 0 sealed and 525 stage-1 hit offsets. The driver has still not been run.
- Stopping before the read was necessary: the uncorrected list contained a sealed file. The resolver would have refused it, but the frame would have been wrong.

## E. The offset-coordinate finding (IV-1: instrument validity, potentially MATERIAL for this step)

| Question | Answer (from code) |
|---|---|
| 1. The coordinate system of stage-1 `raw_hits` | `p3b_s3_mechanical_prep.scan`: **multi-character terms**: offsets in `casefold(latex(NFKC(decode(b, errors="replace"))))`; **single-character terms**: in `latex(NFKC(decode_replace(b)))` (`norm_base`) |
| 2. What `binary_preclassify` expects | `char_to_byte_offsets` maps offsets in **`decode_replace(b)`**. Its docstring's premise ("as the stage-1 search saw the text") is **false**: stage 1 searched the *normalized* text |
| 3. An existing normalized → raw map | **none** (the Evidence N-WS offset map is a different normalization) |
| 4. Used historically? | **no**: `binary_preclassify` has never been run (R5 record) |
| 5. Deterministic reconstruction without changing the classifier | **yes**, as a driver-side **re-basing**. For each hit, find the raw index i whose normalized prefix reaches the stage-1 offset, then **verify** that the term occurs at that offset in the normalized text and that the normalized prefix length is exact. This produces raw offsets in the coordinate the frozen classifier expects |
| 6. When normalization moves offsets | NFKC (ligatures, compatibility characters, some compositions); LaTeX (`\cmd` → a symbol, `\cmd{arg}` → `arg`, both shortening); casefold (`ß` → `ss`, lengthening). U+FFFD, ASCII letters and NUL are length-preserving |
| 7. Can the drift change FALSE-HIT / HUMAN-REVIEW? | **yes**, at region boundaries (PNG chunk borders, ZIP member-name vs data) and through the ±64-byte printability window for NUL files. **The dangerous direction:** a real text hit mapped into NONTEXT bytes yields a wrong **FALSE-HIT** candidate, which removes evidence |
| 8. Is the classification invariant for all mappings? | **not decidable without the bytes.** It becomes decidable during the authorized read: by the verified re-basing (exact), or by a worst-case window check |

**Can the pre-classification proceed scientifically without resolving IV-1? No, not as the frozen classifier is currently fed.** It **can** proceed under either rule below. Both are driver-side, leave the frozen classifier and Freeze 1 unchanged, and are **conservative**: they can only turn FALSE-HIT into HUMAN-REVIEW, never the reverse.

| Rule | Method | Recommendation |
|---|---|---|
| **IV-1a (recommended)** | verified re-basing to raw offsets; any hit whose re-basing does not verify **forces the file to HUMAN-REVIEW** | exact where it verifies; fail-safe otherwise |
| IV-1b | no re-basing; a FALSE-HIT candidate is allowed only if every position within ± the file's absolute normalization drift classifies NONTEXT | simpler; more HUMAN-REVIEW |

Either way the candidates remain **proposals**: each file gets a human decision (FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT).

## F. Minimum next human decision

1. **Accept the reconciliation:** the frame is the historical 12 files / 41 labels, and the driver is corrected.
2. **Choose the IV-1 rule** (a recommended, or b).
3. **Authorize the read of these 12 files** under that rule.

**Traceability:** G-LOG-0054 (brief `20260925_1355`, commit `e8aa3da14`) · G-LOG-0094 · `scripts/p3b_s5_binary_preclassify.py` · `p3b_s3_mechanical_prep.scan` / `norm_base` · `p3b_s5_r5.char_to_byte_offsets` / `binary_preclassify`.

---

## G. Result of the authorized read (G-LOG-0095)

- **Run:** driver `p3b_s5_binary_preclassify.py`, sha256 `d5f743e4…`, committed before the read in `658eddbca`. IV-1a adapter `p3b_s5_offset_rebase.py`: 23 adversarial synthetic tests; the fuzz produced 399 VERIFIED and 307 UNVERIFIED hits, and no UNVERIFIED hit ever produced a FALSE-HIT.
- **Safeguards:** the seal was SEALED before and after the read. No content was printed or written, and the record passed the quarantine scan.
- **Aggregate check against the historical frame:** 12 files, 2 PNG, 3 ZIP, 7 NUL, 41 labels. **All five match.**
- **Record:** `audit-p3b/20260928_BINARY-PRECLASSIFICATION.json`, with each file's content sha256.

| File | Format | Bytes | Stage-1 hits | Labels | Mapping V / U | Regions (verified hits) | **Candidate** | Reason |
|---|---|---:|---:|---:|---|---|---|---|
| S0147 | PNG | 52,029 | 16 | 12 | 16 / 0 | NONTEXT 16 | **FALSE-HIT** | — |
| S0423 | ZIP | 49,507 | 16 | 9 | 16 / 0 | NONTEXT 16 | **FALSE-HIT** | — |
| S0675 | ZIP | 50,080 | 13 | 10 | 0 / 13 | — | HUMAN-REVIEW | a chunk longer than 4,096 characters: not verifiable |
| S0676 | ZIP | 62,403 | 14 | 7 | 14 / 0 | NONTEXT 14 | **FALSE-HIT** | — |
| S1674 | NUL | 11,627 | 7 | 5 | 6 / 1 | NONTEXT 6 | HUMAN-REVIEW | 1 hit crosses a chunk boundary |
| S1757 | NUL | 9,920 | 3 | 2 | 3 / 0 | NONTEXT 2, TEXT-LIKE 1 | HUMAN-REVIEW | a verified hit in text-like bytes |
| S1864 | NUL | 4,919 | 1 | 1 | 1 / 0 | NONTEXT 1 | **FALSE-HIT** | — |
| S1869 | NUL | 17,111 | 2 | 1 | 2 / 0 | NONTEXT 2 | **FALSE-HIT** | — |
| S1977 | NUL | 17,053 | 8 | 6 | 4 / 4 | NONTEXT 3, TEXT-LIKE 1 | HUMAN-REVIEW | text-like bytes; 4 unverified |
| S1979 | NUL | 60,657 | 50 | 9 | 49 / 1 | NONTEXT 36, TEXT-LIKE 13 | HUMAN-REVIEW | text-like bytes (13 hits) |
| S1983 | NUL | 46,492 | 16 | 9 | 13 / 3 | NONTEXT 11, TEXT-LIKE 2 | HUMAN-REVIEW | text-like bytes; 3 unverified |
| S2276 | PNG | 1,516,994 | 380 | 18 | 367 / 13 | NONTEXT 367 | HUMAN-REVIEW | 13 unverified (long chunks, no consistent start) |

**Candidates:** 5 FALSE-HIT · 7 HUMAN-REVIEW. They are proposals only.

**Finding IV-2 (metadata sizing gap; recorded, not investigated).**
- The 7 NUL files have **no size in prepare's `size` map**. The historical "1,731,013 bytes" therefore counted only the PNG and ZIP files; the true total of the 12 is **1,898,792 bytes**.
- The file and label sets are correct; this is a historical byte-count undercount.
- **Check at Freeze 2:** confirm that the R7 plan's R(L) sizing (the 600 KB DECOMPOSED threshold) counts these files' bytes. If it does not, up to 167,779 bytes are missing from the sizes of the 41 labels.

## H. The 12 human decisions requested

For each file, one of **FALSE-HIT · NOT-CONSUMED-ESCALATED · EXTRACT** (r5 items 6–7).

**Recommendation:**
- the 5 verified all-NONTEXT files → **FALSE-HIT**;
- the 7 HUMAN-REVIEW files → **NOT-CONSUMED-ESCALATED**. This preserves the unread state and never discards a possible text hit.

**EXTRACT is not recommended** for any file:
- it needs a validated deterministic extractor, defined provenance and, for archives, the member seal check;
- it would create the EP-01 conditional census stratum;
- no extractor exists.
