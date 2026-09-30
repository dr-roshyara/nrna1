# R-2 — Independent formal verification: result (class: FORMAL/COMPUTATIONAL; instrument check, not validation of H-F2-1-R)

| | |
|---|---|
| **Verifier** | DeepSeek (DeepSeek-V3): **a different model family**, commissioned by the human. Independence declaration in `sealed/METHOD.md` (self-attested) |
| **Execution** | run by the human operator in a clean room (`/tmp/r2-exec-5Inpft`), Python 3.13.2, exit 0, 99.54 s wall time; inputs byte-identical before and after (pre-run and post-run sha256) |
| **Sealed package** | `r2-sealed.tar.gz` sha256 `ab18adfd28309aca5cbe20bfc42ccf77d82635e07704775857a9deb0d40129a7`; internal `MANIFEST.sha256` 12/12 OK; preserved unchanged in this folder |
| **Key hashes** | SPEC.md `44c40fd4…` (= the frozen spec, byte-identical) · verifier.py `d613e29fe6823fe7f9cf1e2032c37ddc8bbffbf10794dec7ca87810ee95bb11e` · METHOD.md `7c146697…` · results.json `4e63d58ac3fbde1d31f63c5e6a24fc916b8d7dc230ee05d72b1df762fc09f99d` |
| **Comparison** | `compare_r2.py` (sha256 `07cad818…`) → `R2-COMPARISON.json` (sha256 `6244b7cb…`), against the pre-registered rule (`prompts/KNOWLEDGEOS-R-2-VERIFIER-HANDOFF-AND-COMPARISON-RULE.md` §3). Nothing was edited to obtain agreement |

## Provenance (reconciled; limitations kept visible)

- The executed `verifier.py` (`d613e29f…`) is byte-identical to the copy in the operator's staging folder (`/tmp/r2-independent-exec-20260926T104140Z/`).
- ⚠ **Markdown-transfer caveat.** The code came out of the DeepSeek conversation as text. The original DeepSeek output is not available as a file, so **byte identity with what DeepSeek produced cannot be established**. This is a chain-of-custody limitation. It does not affect what was computed, which is fully sealed, only *who authored every byte*.
- DeepSeek did not execute the code itself (METHOD.md says so); the operator did.
- The DeepSeek prose analysis mentioned by the human is an **[E] independent expert review**. It is **not computational evidence**, and it was not used in this comparison.

## Criteria (pre-registered)

| # | Criterion | Items | Mismatches |
|---|---|---|---|
| C-1 | state counts with and without A0 (144/288 · 180/288 · 288/768 · 96/96) | 8 | **0** |
| C-2 | full-axiom truth values D1, D2, D3, D3+, D5, D6, NV (against both references) | 28 | **0** |
| C-3 | single-removal truth values, 11 × 7 × 4 (against both references) | 308 | **0** |
| C-4 | inclusion-minimal sets, as sets of sets (including antichain2 D3's two sets) | 24 | **0** |
| C-5 | shortest countermodel lengths | 86 | **0** |
| C-6 | exactness argument | METHOD.md: a per-proposition reduction to R_max (universal propositions by monotonicity; NV by existential witness); explicit "no sampling"; 10 declared interpretation choices, all the direct reading of SPEC.md | **present and valid** |
| supplementary (not pre-registered) | every independent countermodel step replayed through `model.py`'s step predicate | all steps | **0** illegal steps |

## Finding surfaced by R-2 (formal track)

- A **transcription error in an earlier report**, not in any data: the closure-pass §2.1 table showed antichain2's full-set NV as "true"; all data say false.
- A correction note is appended to that report.

## Outcome

> ## **R-2: REPRODUCED**

- The formal claims of SPEC.md, including every truth value, ablation result, minimal set and countermodel length, are reproduced by an independent implementation from a different model family.
- **This certifies the instrument, not H-F2-1-R.** It says nothing about whether the source material supports the axioms (that is T-A).
