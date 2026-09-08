# MD-033 — Controlled Validate Specification-Sufficiency and V6 Adjudication

## Authorization

User authorization, 2026-09-08, following the same "write your disagreement first" pattern. No
blocking disagreement was raised. One expectation was flagged in advance and confirmed by this
study's own findings: §4's instruction to "reconstruct V6 directly from admissible evidence" assumes
V6 is present in that evidence; this study finds it is not (`04`).

## Central question

Is the Model-B `Validate` rule sufficiently specified, from currently admissible evidence alone
(`03-capability-model.md`, `04-operator-contracts.md`, `06-composition-rules.md` — the three files
admitted by MD-028-DQ-1 and MD-032), to support a later controlled composition test? And: what
exactly is `V6`, and does the admissible evidence adjudicate it?

## Headline finding

All three admissible files were read cold, in full, for this study (not relied on from prior
summaries). **`V6` appears nowhere in any of the three admissible files.** The only variant labels
the admissible evidence itself names are `V1` (`03`, line 95: merging C2/C3, rejected, "tested
instead as robustness variant V1 (§12)"), `V4` (`04`, line 76: attacking the `DetectGap`-exclusivity
assumption, "§12"), and `V5` (`03`, line 96: merging C12/C13). Both citing documents point to "§12"
(`12-randomized-results.md`) as where variant results are elaborated — a file **not admitted**, not
read as evidence by this study, and not silently imported (per this study's own scope discipline).
**Classification: `V6 NOT ACTUALLY ESTABLISHED BY ADMISSIBLE EVIDENCE.`**

Separately, `Validate`'s own input/output/rule is `CLOSED BY SOURCE` (confirming and slightly
extending MD-031's own finding) and its state-effect is explicitly `none` (`04`'s "Common to all"
contract: "State effects = none, except `Revise`"). But preconditions, postconditions, and failure/
error semantics are `NOT SPECIFIED BY SOURCE` anywhere in the admissible population — confirmed by
an exhaustive grep census (`02`), not a sample.

## Non-goals (restated, binding)

No composition test. No admission of `kr/carriers.py` or any executable artifact. No admission of
`12-randomized-results.md` or any other unadmitted narrative file. No canonical operator contract.
No model selection. No Stage 07. No modification of MD-024–032 or any frozen artifact.

## Artifact map

| File | Content |
|---|---|
| `00_index.md` | this file |
| `01_authorization-and-scope.md` | scope, evidence boundary, what was and wasn't read |
| `02_admissible-evidence-census.md` | full grep census of all three admitted files for the required terms |
| `03_validate-contract-reconstruction.md` | the smallest source-grounded `Validate` contract, closed/partial/not-specified per field |
| `04_v6-adversarial-adjudication.md` | the central V6 finding, in full |
| `05_pre-post-failure-semantics.md` | census-based, not sample-based, precondition/postcondition/failure analysis |
| `06_mathematical-adequacy.md` | is there a well-formed mathematical object for Validate, from admissible evidence alone |
| `07_ddd-audit.md` | domain-concept vs. carrier-label classification |
| `08_claim-audit.md` | re-examines MD-031's/MD-032's own relevant claims against this study's evidence |
| `09_final-verdict.md` | the required A–D decision state |
| `10_verification-and-completion.md` | verification suite + required closing statement |
