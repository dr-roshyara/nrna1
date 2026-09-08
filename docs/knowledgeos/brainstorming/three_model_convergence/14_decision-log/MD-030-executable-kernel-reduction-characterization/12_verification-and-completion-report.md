# MD-030 — Verification and Completion Report

## Verification suite

1. `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged (`last_handled_sequence=2376`,
   `DONE=401`).
2. MD-024, MD-025, MD-026, MD-027, MD-028 (incl. `13_recorded-decision.md`), MD-029, `02_model-a_gita/`,
   `03_model-b_mathematical/`, `04_model-c_kernel-ddd/`, `05_cross-model/` — `git status --porcelain`
   confirms no modification (each shows `??`, its own pre-existing untracked-directory status, not
   `M`; nothing inside was changed).
3. `classification-register.tsv` — 2377 lines (2376 rows + header), unchanged row count; not opened
   for writing by this study.
4. `nrna1/research/knowledgeos-sim/` and `nrna1/verification/` (incl. `zero-algebra/`) — confirmed
   untouched by this study's own tool-call record; the one local modification present in
   `verification/zero-algebra/.../metrics.json` pre-dates this study (present before this study's
   first git-status check) and was not caused by it.
5. No file was admitted; no `06-composition-rules.md` content was read; no code was executed; no
   Pair-1/2/3/4 composition test was performed; no Stage 07 opened.
6. Only the new `14_decision-log/MD-030-executable-kernel-reduction-characterization/` directory
   (12 files) written by this study, plus the governance-record updates (decision log, CONTEXT,
   session log, plan file) made immediately after this report.

## Completion report (10 required points)

1. **Exact remaining specification gap**: `Validate`'s input carriers, preconditions, and
   postconditions remain `NOT SPECIFIED BY SOURCE` within the two MD-028-admitted narrative files —
   MD-029's own finding stands unchanged, not contradicted or re-tested by this study.
2. **What the executable lane actually supplies**: a concrete, computationally exercised candidate
   derivation rule (`{Claim,Evidence}`/`{Hypothesis,Evidence}` → `Verdict`, via `A_WARRANT`), plus a
   confirmed downstream consequence (capability `C10` is unreachable under the 13-operator baseline,
   reachable only with `Qualify` added) and a newly-surfaced connection (`Verdict` is also required
   by capabilities `C16`/`C17`).
3. **All necessary dependencies (for the candidate rule)**: self-contained within `kr/atoms.py` +
   `kr/carriers.py` (the rule's own vocabulary); understanding its *contested* status additionally
   requires `kr/variants.py` (`V6`'s alternative rule) — omitting `variants.py` would misrepresent
   the rule as uncontested.
4. **Relevant `/research` evidence**: none consulted beyond `nrna1/research/kernel-reduction/`
   itself, per this study's own scope (`nrna1/research/knowledgeos-sim/` explicitly not opened).
5. **Relevant `/verification` evidence**: none consulted, per this study's own scope
   (`nrna1/verification/` explicitly not opened).
6. **Provenance of decisive evidence**: `kr/carriers.py` and the whole `research/kernel-reduction/`
   directory carry **zero git history** (never committed, any branch) — weaker than the narrative
   lane's own dated 2026-09-06 commit; filesystem-mtime evidence (2026-09-01) is content-consistent
   with, but not independent proof of, the narrative lane's own self-dating.
7. **Is the executable lane sufficient to close the gap?** No — see `08`/`11`: relevant, potentially
   necessary, but not currently admissible (three independent qualifications: no git history,
   self-declared non-canonical, internally contested within its own `variants.py`).
8. **Smallest possible admission**: not proposed — this study explicitly stops short of preparing a
   ready-to-approve admission package (per `11`'s reasoning for choosing outcome D over C).
9. **Remaining uncertainty**: whether `06-composition-rules.md` (still unread) states the same rule,
   a different rule, or is silent; whether the executable lane's own `results/*.json` were ever
   actually produced by running `run_all.py` on this codebase (plausible, not directly evidenced);
   whether any organizational authority exists anywhere for this directory (not evidenced, same
   finding as every prior MD-025–028 provenance question).
10. **Exact next governance/scientific action**: none proposed by this study, per its own stop
    condition — a future, separately authorized decision would need to choose among: (a) a
    provenance-strengthening step for this directory, (b) reading `06-composition-rules.md` (a
    narrative-lane admissibility question, different scope than this study), or (c) some other path
    the user determines.

## Final status

```
MD-030 (EXECUTABLE KERNEL-REDUCTION EVIDENCE CHARACTERIZATION) COMPLETE.
OUTCOME: D — ADMISSIBILITY/PROVENANCE BLOCK.
The executable lane (nrna1/research/kernel-reduction/) supplies a concrete, on-point candidate
answer to B Validate's open input-specification question (a derivation rule found in
kr/carriers.py), but it is not currently admissible: zero git history (weaker than the narrative
lane's own already-cautious provenance), self-declared non-canonical ("[EXP]... not KnowledgeOS
architecture"), and internally contested by the same lane's own variants.py (an alternative
3-input rule, V6, tested alongside the baseline 2-input rule).
NO CONTRADICTION FOUND between the executable lane and the two MD-028-admitted narrative files —
every checked relationship is CORROBORATED or EXPLICITLY LINKED.
NO PRIOR MD-024-029 FINDING IS DEPENDENT ON THIS DIRECTORY (chronology: discovered only after
MD-029 closed).
NO ADMISSION MADE. NO COMPOSITION TEST PERFORMED. NO MODEL SELECTED. NO STAGE 07 OPENED.
knowledgeos-sim/ AND verification/ REMAIN UNTOUCHED, PER SCOPE.
AWAITING SEPARATE AUTHORIZATION FOR ANY FURTHER STEP.
```
