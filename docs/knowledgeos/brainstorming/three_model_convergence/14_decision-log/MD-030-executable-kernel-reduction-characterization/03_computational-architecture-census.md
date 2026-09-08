# Computational Architecture Census

Depth-of-read is stated per file: **FULL** = entire file read; **GREP-CONFIRMED** = searched in
full for `Validate|VERDICT|Verdict|A_WARRANT|warrant`, zero matches, not otherwise read line-by-line.

## `atoms.py` — FULL

Defines 14 atoms as plain string constants: 13 "productive" atoms (introduce new semantic content)
plus `A_QUALIFICATION` for the separately-modeled `Qualify` operator. `A_WARRANT =
"warrant-assessment"` (line 35) — **exact match** to the atom already known for B `Validate` from
the admitted `04-operator-contracts.md` (per MD-029's `02_validate-source-contract.md`). No
input/output typing lives here — this module is a vocabulary list only, explicitly documented as
an "anti-circularity device" (an operator's power = exactly the atoms its contract permits it to
introduce; nothing else).

## `operators.py` — FULL

Defines the 13 `C0` operators as `(name, atom-set, corpus-support-rating, note)` tuples, plus
`Qualify` separately. `Op("Validate", {A_WARRANT}, "STRONG", "Q16 Verify/Corroborate edges;
validation lane throughout corpus")` (line 33). **No input/output type is declared anywhere in this
class** — an operator here is fully specified by its atom set alone. This module, by itself, does
**not** supply the missing input-carrier information MD-029 identified as absent; it only
re-confirms the atom (already known) and adds a `"corpus"` support-strength tag (`"STRONG"`) not
present in the admitted `04-operator-contracts.md` (per MD-029's own re-read of that file, which
recorded no such rating).

## `carriers.py` — FULL — the central finding of this study

Defines 9 "ambient" carrier kinds (available with no operator) and 14 "derived" carrier kinds
(including `VERDICT = "Verdict"`), plus a `DERIVATION_RULES` table: 19 explicit rules, each of the
form `(required input-kind set, required introduced-atom set) → produced kind`. The two rules
relevant to `Validate`/`Verdict`:

```
({CLAIM, EVIDENCE},      {A_WARRANT}, VERDICT)
({HYPOTHESIS, EVIDENCE}, {A_WARRANT}, VERDICT)
```

This is a **concrete, machine-encoded candidate answer** to exactly the "what does `Validate` take
as input" question MD-029 left as `NOT SPECIFIED BY SOURCE`. See `04` and `08` for how this is
classified for evidentiary and admissibility purposes — it is not treated here as a settled
specification (see the `variants.py` finding below, which shows this exact rule is one of at least
two the same lane tests).

The module's own docstring states its purpose is a "second anti-circularity device" — a carrier's
kind is derived structurally (from inputs + introduced atoms), never stamped by the producing
operator's identity, "so ANY operator set able to introduce the required atoms over the required
inputs produces the carrier" — i.e. this table is designed as a general combinatorial rule for the
whole 13(+1)-operator system, not as a bespoke specification written specifically for `Validate`.

## `reach.py` — FULL

Implements `Reach(S)`: a least-fixpoint closure over `carriers.derive_kinds`, computing which
carrier kinds are constructible from the ambient set given an operator set `S`. Explicitly
documented as "monotone, terminating, and independent of operator NAMES" — the engine consumes
only atom sets and the derivation table, never an operator's name/identity. This is the mechanism
that determines, computationally, whether `Verdict` (and therefore capability `C10`) is reachable
for a given operator set — see `05` for the computed result (C10 is lost under plain `C0`, achieved
under `C0+Qualify`).

## `capabilities.py` — FULL

Defines capabilities `C1`–`C25` as `(kinds-required, atoms-required, note)` triples. `cap("C10",
"validate a claim/model", [VERDICT])` (line 30) — **exact match** to the `C10` capability already
known from the admitted `03-capability-model.md` (per MD-029). Line 4's docstring states directly:
*"Justified modifications are recorded in docs .../03-capability-model.md"* — an explicit,
first-person statement by the code itself that it is meant to track that specific admitted
document (see `06` for the crosswalk classification this supports).

## `variants.py` — FULL — qualifies the `carriers.py` finding

Defines 7 alternative models (`V0`–`V7`), explicitly framed as robustness checks: "a conclusion
that survives every variant is [EXP] robust; one that flips is an artifact of a modelling choice
and must be reported as such" (module docstring). Two are directly relevant:

- **`V0`** (line 25): `variant("V0", "baseline model as declared in 04-operator-contracts")` — the
  code's own text names the baseline configuration as tracking the admitted document by name.
- **`V6`** (lines 72–81): an *alternative* Verdict-derivation rule — `{Claim, Evidence, Defeater}`
  or `{Hypothesis, Evidence, Defeater}` → `Verdict`, described as "a Verdict REQUIRES a
  surviving-defeater step (validation presupposes challenge)."

**This is load-bearing**: the two-input rule found in `carriers.py` (`{Claim,Evidence}→Verdict`) is
not presented, even within this executable lane's own internal design, as *the* fixed answer to
"what does Validate need." It is the **baseline** of a set of ≥2 tested alternatives for the same
derivation step. `V7` additionally tests the raw 13-operator `C0` with `Qualify` dropped entirely.

## `ablate.py` — GREP-CONFIRMED, not otherwise read

LOO (leave-one-out) / pairwise / triples / atom-level / smuggling-probe ablation logic (per
`run_all.py`'s own call sites and the README's module table). Contains no occurrence of
`Validate`/`Verdict`/`warrant` outside what `run_all.py` supplies as arguments (the smuggling
probes, characterized in `04`).

## `audit_variants.py` — GREP-CONFIRMED, not otherwise read

An extended-search audit over the variant space (per README); produces `audit_extended_search.json`
(mtime 23:35–23:40, the latest-written module, after the main `run_all.py` pass). No
`Validate`/`Verdict`/`warrant` occurrence found.

## `infotheory.py` — GREP-CONFIRMED, not otherwise read

Data-processing-inequality and confounding experiments (per README and `run_all.py`'s call to
`context_experiment()`/`confounding_experiment()`). No `Validate`/`Verdict`/`warrant` occurrence
found — this module's experiments are about `Context`, not `Validate`.

## `scenarios.py` — GREP-CONFIRMED, not otherwise read

16 deterministic scenarios (per README). No `Validate`/`Verdict`/`warrant` occurrence found.

## `properties.py` — GREP-CONFIRMED, not otherwise read

P1–P10 randomized property checks + a "vacuity audit" (per README). No `Validate`/`Verdict`/
`warrant` occurrence found — none of the ten named properties is Validate-specific (the one
Validate-adjacent probe in the whole codebase is the smuggling-probe pair in `run_all.py`, not a
property in this module).

## `run_all.py` — PARTIAL (orchestration section, lines 85–108, read in full; earlier sections not
read line-by-line, only grep-confirmed)

Orchestrates 7 experiment groups, writing one `results/*.json` per group. The smuggling-probe list
(line 98) includes `("DetectGap", "Validate")` and `("Challenge", "Validate")` — these test whether
*removing* `Validate` and modifying `DetectGap`/`Challenge`'s atom sets could "smuggle in"
`Validate`'s power (an anti-circularity/ablation check on the atom-boundary discipline), **not** a
specification of `Validate`'s own input/output semantics.

## `README.md` — FULL (read prior to this study's own directory creation)

States the directory is `[EXP]` "a research instrument, not KnowledgeOS architecture. Nothing here
is canonical. Do not import it from production code." Names the write-up location as
`docs/knowledgeos/research/kernel-reduction/`, gives the run command, a module table, and the
deterministic-seed note. Flags `variant_summary.json`/`smuggling_probes.json` as scratch outputs
not regenerated by `run_all.py`.
