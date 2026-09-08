# MD-024–MD-029 Dependency Audit

Categories: **NOT DEPENDENT** / **POTENTIALLY DEPENDENT** / **DIRECTLY DEPENDENT** / **CONTRADICTED**
/ **CANNOT DETERMINE**.

| Prior finding | Dependency on `nrna1/research/kernel-reduction/`? | Evidence | Status |
|---|---|---|---|
| MD-024 — DDD complementarity hypothesis (operators vs. aggregates), 4 pre-registered pairs, 3 landed untestable | This directory was entirely unknown/undiscovered until this session's most recent turns — MD-024 was executed using only Model B's admissible corpus evidence (`KR-SIM`-tagged material excluded per its own guardrail) | Chronology: `nrna1/research/kernel-reduction/` was first surfaced in this session *after* MD-029, not before MD-024 | **NOT DEPENDENT** |
| MD-025 — specification-sufficiency census, found `04-operator-contracts.md`/`03-capability-model.md`/`06-composition-rules.md` cited by M0030 | Same chronology; MD-025 discovered the *narrative* lane's file names only, never opened `nrna1/` | Discovery record in `14_decision-log/model-boundary-decisions.md`, MD-025 entry | **NOT DEPENDENT** |
| MD-026 — corpus-boundary/provenance adjudication for `docs/knowledgeos/research/` | Investigated a different, though related, directory (the narrative lane) under `docs/knowledgeos/`; never touched `nrna1/research/` | MD-026's own scope statement | **NOT DEPENDENT** |
| MD-027 — adversarial audit of MD-026 | Same scope as MD-026 | — | **NOT DEPENDENT** |
| MD-028 — human corpus-boundary decision, ADMIT `04-operator-contracts.md`+`03-capability-model.md` only | The admission decision text names exactly two files under `docs/knowledgeos/research/kernel-reduction/`; it neither names nor implies anything about `nrna1/research/kernel-reduction/`, which was not yet discovered | `13_recorded-decision.md`'s own text | **NOT DEPENDENT** — and this study's own finding (§`08`) confirms the admission's scope does not extend here, so no retroactive widening has occurred |
| MD-029 — Pair-1 retest, `FUNCTIONAL ANALOGY` (level 3/6), input `NOT SPECIFIED BY SOURCE` | MD-029 used only the two MD-028-admitted files and C1's seq 0157; the executable lane was discovered by this session only *while preparing MD-029's own proposed follow-on* (the "evidence-complete retest"), after MD-029 itself had already closed | Tool-call chronology this session; MD-029's own `11_verification-and-completion-report.md` final status line | **NOT DEPENDENT** — MD-029's `FUNCTIONAL ANALOGY (level 3/6)` result and its "input NOT SPECIFIED BY SOURCE" finding stand exactly as recorded; this study neither contradicts nor confirms them using new evidence (that would require a new composition test, explicitly out of scope here) |

## Overall

**No frozen MD-024–029 finding is dependent, potentially or directly, on
`nrna1/research/kernel-reduction/`.** This is expected, not a coincidence requiring further
investigation: every one of those studies pre-dates this directory's discovery within this session,
and none of their own source material or tool-call records shows any prior contact with it. No
finding is contradicted. No finding requires re-opening.

## What this audit does not say

It does not say the executable lane is *irrelevant* to the still-open question MD-029 itself named
("is `06-composition-rules.md` the smallest additional evidence needed") — that is a forward-looking
relevance question, addressed in `08`, not a backward-looking dependency question, which this
artifact answers cleanly: no.
