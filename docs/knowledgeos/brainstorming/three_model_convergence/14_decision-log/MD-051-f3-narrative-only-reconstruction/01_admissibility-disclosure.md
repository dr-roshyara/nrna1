# MD-051 §01 — Admissibility Disclosure (the required disagreement, stated before proceeding)

## What happened, stated plainly

MD-050 (committed `6634eab65`) built its `Beh_𝔠(K):=Reach(Ops(K))` construction and its three
computed proofs (`C0 ≺_cap C0_PLUS`, `Qualify` irreducible, `DetectGap` redundant) by reading four
**executable Python files** directly and in full:
`nrna1/research/kernel-reduction/kr/atoms.py`, `kr/carriers.py`, `kr/reach.py`, `kr/operators.py`.

**This code was never executed** — MD-050 honored the standing no-execution rule. But it was read
statically and used as primary evidence for a new formal construction. That is where the defect lies:
**not execution, but un-admitted static reliance.**

## What MD-030 already ruled, before this window began

MD-030 (2026-09-08), examining this exact directory (`nrna1/research/kernel-reduction/`, distinct
from the narrative `docs/knowledgeos/research/kernel-reduction/`), found:

> **Outcome: D — ADMISSIBILITY/PROVENANCE BLOCK** — relevant, not currently admissible … **No file
> admitted.**

Reasons given: zero git history (weaker provenance than the narrative lane's dated commits);
self-declared `[EXP]`, "nothing here is canonical"; and an internal inconsistency (`operators.py`
vs. `variants.py` test an alternative rule for the same step). **No later phase — MD-031 through
MD-050 — ever lifted this block.** The only admissions this reconstruction ever granted for
kernel-reduction material were narrative, under a differently-named path, each narrow-scope:

| File | Admitted by | Scope |
|---|---|---|
| `docs/knowledgeos/research/kernel-reduction/03-capability-model.md` | MD-028-DQ-1 | specification-sufficiency only |
| `docs/knowledgeos/research/kernel-reduction/04-operator-contracts.md` | MD-028-DQ-1 | specification-sufficiency only |
| `docs/knowledgeos/research/kernel-reduction/06-composition-rules.md` | MD-032 | `Validate`/composition research only |
| `docs/knowledgeos/research/kernel-reduction/12-randomized-results.md` | MD-035 | `Validate`/V1–V6 research only |

None of these four admissions ever named or covered the executable `kr/*.py` files.

## How far the imprecision spread

A direct check of the frozen decision log (`grep -n -i "F3"`, cross-referenced against `admit`) found
one place where this reconstruction's own prose blurred the distinction: **MD-049** (line ~4353)
states its own negative vocabulary check found "zero hits … against F1's own frozen record, **F3's
own admitted material**, or F5's own source file" — a phrase that does not distinguish the narrative-
admitted files from the never-admitted executable. MD-044's own text ("F3 (kernel-reduction
C0/C0_plus) unchanged") is neutral and does not itself assert an admission status. This check was
bounded to a direct grep for "F3" co-occurring with admission language — it is not a claim that every
sentence in MD-042–049 was individually audited word-by-word; that finer audit is not undertaken here,
since the corrective action (a properly-scoped blind reconstruction) does not depend on it.

## Disposition of MD-050

Per the user's own explicit instruction (`AskUserQuestion`, this turn): **Option 3, Defer** — MD-050's
own text is not modified, remains committed and disclosed as a historical/process artifact, and its
results are **not treated as validated evidence for any downstream claim** until this phase's
independent, properly-admissible reconstruction exists to compare against. The user added one binding
constraint, honored throughout §02–03 of this phase: **this reconstruction must not use MD-050's
own executable-derived numbers as a premise, comparison target, hint, or reconstruction aid** — §02
below was built cold, from the four admitted narrative files alone, before any comparison against
MD-050 was made (that comparison is §03, performed only after §02 was complete and its own results
fixed).

## No backlog ticket filed for this specific event

This is a disclosed, self-caught process defect in this reconstruction's own immediately-prior work,
corrected by the very next phase — exactly the discipline the standing MD-021 process exists to
produce. It does not (yet) meet the bar for a new backlog ticket describing a *recurring, cross-
session* pattern; if a similar admissibility conflation recurs in a *future* session, that would
become the second data point warranting one. Checked against existing tickets first: `EKS-19`
(no registry of already-spoken-for directories) and `EKS-21` (negative findings need positive
controls) are both adjacent but describe different failure shapes — neither covers "a phase using
evidence adjacent to, but distinct from, evidence a prior phase actually admitted."
