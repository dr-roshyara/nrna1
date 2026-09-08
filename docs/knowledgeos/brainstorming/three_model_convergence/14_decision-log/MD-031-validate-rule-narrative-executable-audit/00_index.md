# MD-031 — Validate Rule Narrative–Executable Convergence Audit

## Authorization

User authorization, 2026-09-08, adapted per an explicit exchange before execution began (see
`01_scope-and-firewall.md` for the full record of that exchange). The authorization's substance —
a controlled, source-comparison audit of whether the still-unadmitted narrative document
`06-composition-rules.md` independently specifies the `Validate` derivation rule MD-030 found only
in the (inadmissible) executable lane — is executed as given. Two elements of the original prompt
text were not: a "hard firewall" against inspecting `three_model_convergence/` (this session's own
home directory — incoherent as an instruction to this session, since every required artifact lives
there) and verification-checklist references to "P-07–P-40" and "K1–K11 cardinality" (vocabulary
belonging to a different track — this session's own governance record uses MD-024–030 and K-1/K-2).
Both were identified as mismatched before any file was touched; the user confirmed proceeding with
the adapted version.

## Central question

Does `docs/knowledgeos/research/kernel-reduction/06-composition-rules.md` — cited by name (§06) in
the already-admitted `04-operator-contracts.md`, but never itself admitted or read by this
reconstruction before this study — independently supply the B `Validate` input-carrier specification
MD-029 found `NOT SPECIFIED BY SOURCE` and MD-030 found only a candidate for in the (inadmissible)
executable lane? And what is 06's relationship to that executable rule?

## Non-goals (restated, binding)

No admission of `06-composition-rules.md` or any executable artifact. No composition test (Pair 1
repeat or Pairs 2/3/4). No modification of MD-024–030 or any frozen Phase 1–6 artifact. No
modification of `classification-register.tsv`. No Stage 07. No model selection. No K-1/K-2 or
GA-038 reopening. No code execution.

## Headline finding (justified in full below)

`06-composition-rules.md`'s own derivation table contains, verbatim in structure, the identical rule
already found in the executable lane's `kr/carriers.py`: `Claim,Evidence | Hypothesis,Evidence →
(warrant-assessment) → Verdict`, with matching prose ("`Verdict` requires `Evidence`. This is where
the absence of `Qualify` from `C0` becomes fatal.") — this is the exact mechanism `baseline.json`
computed. `06` never uses the word "Validate" (the whole document is deliberately operator-name-free,
by the same anti-circularity design already found in the code) — the connection to `Validate`
specifically requires chaining two already-source-stated facts: `04` (admitted) states `Validate`'s
atom is `warrant-assessment`; `06` states which carriers are required to introduce
`warrant-assessment`. That chain is built entirely from narrative-lane text, not from code.
Provenance: `06` carries the *same* Git history as the two already-admitted files (same 2026-09-06
commit) — a materially stronger provenance status than the executable lane's zero-git-history
finding in MD-030. mtimes place `04` and `06` five milliseconds apart — evidence of a single
generation pass, not independent authorship; no explicit cross-citation was found between `06` and
the executable code in either direction.

## Artifact map

| File | Content |
|---|---|
| `00_index.md` | this file |
| `01_scope-and-firewall.md` | the pre-execution exchange; scope as actually executed |
| `02_narrative-source-census.md` | full population census of `docs/knowledgeos/research/kernel-reduction/` |
| `03_validate-narrative-contract.md` | `06` read cold, without reference to the executable code |
| `04_executable-rule-reconstruction.md` | the already-characterized `kr/carriers.py` rule, restated without embellishment |
| `05_neutral-rule-comparison.md` | dimension-by-dimension comparison table |
| `06_provenance-and-temporal-analysis.md` | git/mtime evidence, independence test |
| `07_v6-alternative-analysis.md` | does `06` resolve or ignore the executable lane's `V6` alternative |
| `08_specification-sufficiency.md` | per-field closed/partial/not-closed classification |
| `09_mathematical-audit.md` | domain/codomain/rule correspondence, formally stated |
| `10_ddd-audit.md` | entity/value-object/carrier classification for both sources |
| `11_adversarial-falsification.md` | H1–H4 tested |
| `12_md030-claim-audit.md` | re-examines MD-030's own five central claims against this study's new evidence |
| `13_admissibility-preparation.md` | relevance/admissibility/membership, kept distinct, no decision made |
| `14_resolution-status.md` | the required A–G final verdict |
| `15_verification-and-completion-report.md` | verification suite + final required statement |
