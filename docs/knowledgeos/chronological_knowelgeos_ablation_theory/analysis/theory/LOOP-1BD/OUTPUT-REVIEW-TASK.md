# Output review of the Protocol B execution (frozen)

Use only the files here:
- `PROTOCOL-B.md`, `scope_b.sh` (r3);
- `REVIEW-r3.md`;
- `RUN-RECORD.md`;
- `RUN-ROOT/*`, the emitted outputs;
- `SHA256SUMS-RUN-APP`;
- `QUESTIONS.md`.

Audit **only**:
1. **Integrity:** do SHA256SUMS cover every emitted file? Are the two runs identical? Exit codes, the errors file, the HEAD record.
2. **Conformance:** is every answer in 70_answers.tsv the one the protocol's rules produce from 60_channel_metrics.tsv / 58 / 56? Recompute each Q7 and the headline by hand.
3. **Identity:** are any personal identities, e-mails, names or path strings present in any output?
4. **Anomalies:** flag anything that needs disclosure. Consider in particular:
   - the very small identity counts (|E|, |W|, |N_c|) and what that implies for the **statistical power** of an identity-overlap independence test;
   - K-NORMREG's 202 paths vs NB-13 / N15 (vocabulary over-capture; .claude/ and claude/ programme-adjacent artefacts);
   - the K-SIG count (23 signed commits) and what *kind* of signer could produce presence-only signatures. **Do not verify.** Name the possibilities only, as a scoping question for the human.
5. **Neutrality:** the output contains no hypothesis, conformance or scoring claim. **Your review must not interpret the results for H-D / H-B either.** Describe; do not conclude about the theory.
6. **Answer, per QUESTIONS.md, in one line each:** does any locally checkable channel qualify as an *independent* norm-in-force record? Use only the protocol's outcome vocabulary.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**.

Write **OUTPUT-REVIEW.md** and **OUTPUT-REVIEW.json** ({verdict, integrity: {...}, conformance: [{channel, expected_q7, observed_q7, ok}], headline_ok, identity_ok, anomalies: [...], power_note, scoping_questions_for_human: [...], limitations: [...]}).
