# External protocol: proposed corrections before freeze

**PROPOSED / NOT RATIFIED. Draft r0, 2026-10-17. Nothing here is applied.** These corrections come from an independent audit of `external-protocol/`, whose verdict was **READY AFTER CORRECTIONS**. Applying them requires Approval 2 (see `APPROVAL-PACKAGE.md`). The protocol is **not** frozen, pre-registered or executed.

**Λ7:** the protocol, its audit and this draft were all prepared by Claude-family agents.

## Blocking for freeze (must be applied before any freeze)

| # | defect | location | proposed change |
|---|---|---|---|
| P1 | **PARSE leaves a parse choice open that T3 and SH do not.** R15, R21 and R16 all have the form "x is P, not Q", but K1c gives them different verdicts. The packet does not say how to split such a sentence, or which part letter wins when a part fits more than one. This repeats the blind2 B′ flaw on exactly the rows that separate PARSE from SH | `packet_PARSE.md` rule 6; `tools/build_packets.py` | Add to **every** arm, so that rules 1–5 and the part descriptions stay identical: "A sentence of the form 'x is P, not Q' has two parts: 'x is P' (classify it by the table) and 'not Q' (d). Where a part fits more than one letter, the first letter in the order (c), (a), (d), (e), (f), (g), (b) applies." Record this as analyst choice E12 in `key_PRIVATE.md` §5, then rebuild and re-run the leak check |
| P2 | Ambiguous answers can count towards the majority verdict, so an arm can be labelled DETERMINATE-OTHER when it should be INDETERMINATE | `score.py` `classify` (line 124) | Exclude AMBIG and TIE from the majority count:<br>`det = all(max((v for k, v in counts_by_row[r].items() if k not in ('AMBIG','TIE')), default=0) >= thr for r in GUARD)` |
| P3 | Claude-family encoders are flagged but still counted in n_valid. The family test also misses sheets labelled only by vendor | `score.py` `analyse` (line 132) | Exclude Claude-family sheets from n_valid unless `--dry-run` is set (the real run never uses it). `is_claude` should test both the family name and the vendor "anthropic" |
| P4 | The answer key can still be reached. `--key-outside` is documented but not implemented. The key vectors are also already public in `KC-CONVENTIONS-PROPOSAL.md:88` and `blind2_eval.out`, and this repository has a GitHub remote | `tools/freeze.py`; PROTOCOL §8.1 step 3, §6.6, §5.1 | Add `freeze.py --key-path <file>`, which hashes the key wherever it is stored. Add to §5.1 eligibility: "no encoder may have, or be given, any access to this repository; human encoders confirm this in writing" |
| P5 | Answer-sheet handling can score a non-answer. The template's placeholder JSON is valid and scores as a perfect SH sheet. A malformed B1 falls back to the first JSON found anywhere on the sheet | `answer_sheet_template.md` B1; `score.py` `parse_sheet` | Replace the placeholder with `PASTE ENCODER JSON HERE`. Use the fallback for raw replies only:<br>`if obj is None and '## B. Encoder response' not in t: obj = first_json_object(t)` |

## Should-fix before freeze

| # | defect | proposed change |
|---|---|---|
| P6 | "Cluster" is under-defined (§5.2, analysis-plan §3.4) | "Cluster = vendor (all of its families). A derived or distilled model belongs to its base model's vendor. A model known to be trained on Claude output is ineligible. Each mandatory arm needs ≥ 3 clusters, ≥ 2 of them non-Claude vendors or human cohorts. A cap breach (> 40 %) labels the arm CAP-BREACHED." Also note that the cap cannot be met at n = 4 with 3 clusters |
| P7 | Arm allocation is ambiguous (§6.3) | "The arm list is the mandatory arms only. Optional arms get a separate allocation, committed before their first packet." |
| P8 | The part taxonomy was fitted to the guard rows, and this is not disclosed | §11.3 and key E7: "The part types and examples were derived from the guard-row phrasing. PARSE REPRODUCED is evidence for (c) plus this taxonomy, on these rows only." |
| P9 | The tie-break adjudicator is the KC-1 decision-maker, so it is not independent | "A third adjudicator meeting the §1 eligibility, named at the freeze" |
| P10 | Reporting could depend on the outcome | analysis-plan §5: "Every arm's result is reported whatever its label; a follow-up study does not replace this report." |

## Minor

- **P11, cross-references.**
  - PROTOCOL §1 cites §6.1; it should be §5.
  - The template cites §6.2; it should be §5.1.
  - `score.py` cites 3.5; it should be §3.4.
  - key §2 cites §4.3; it should be §1.4.
  - Regenerate `leak_check.out` and `dryrun_blind2.out` without the hand-added headers, so the §8.1 redirect reproduces them byte for byte.
  - Correct the κ message when there are 0 raters.

## What the audit passed

- **The key vectors match the sources:** T3 = CB23, X, X, X, CB23, X; SH = none × 6; PARSE = CB23, none, CB23, none, none, CB1 (KC:88 and §2.4 item 4).
- **The placement table** matches T3 §1a (28 items).
- **The leak check passes**, both by `leak_check.py` and by hand search.
- **The dry run and the operating-characteristics table reproduce exactly.**
- **Synthetic edge cases behave as planned for:** n = 4, 5 and 10; missing rows; malformed JSON; n ≤ 3.
- **The statistics are sound:** no significance tests; Clopper–Pearson is correct (10/10 → [0.692, 1]; 5/5 → [0.478, 1]); κ is descriptive only.
- **The stopping rule:** fixed n and deadline, with no interim looks.
- **Integrity steps:** freeze then commit before distribution; archive hashed before scoring.
- **The protocol states clearly** that reproducibility of interpretation is not normative correctness.
- **Λ7 is disclosed at the source level.**

## Residual limits (not fixable by correction)

- **One corpus and 11 rows.** The texts are Claude-authored.
- **PARSE's general-rule wording and its taxonomy are the analyst's.** A failure of PARSE may therefore be a failure of this wording, not of K1c.
- **The other conventions stay at T3's values**, so the test does not cover principle C as a whole.
- **Prior exposure to the corpus** is recorded but cannot be detected.
- **A REPRODUCED result tests how determinate the text is.** It does not decide KC-1.
