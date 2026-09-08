# MD-045 §4 — Hypotheses (Phase H), Comparison (Phase D), DDD Interpretation (Phase G), Classification (Phase E + final)

## Phase D — candidate comparison

**Not performed, and could not be, given the Phase C boundary** (`03_...md`). Every row: **NOT
FORMALLY SPECIFIED ENOUGH TO TEST** (the required prior — a granularity-independent capability
identity criterion — does not exist for any candidate, real or hypothetical). This is not a weaker
finding than MD-044's own "UNRESOLVED" — it is the same finding, now with an explicit, source-endorsed
reason rather than only an absence of attempted comparison.

## Phase G — DDD interpretation

The chain supplies more DDD-relevant structure than MD-044 saw: a responsibility-projection map
`ρ:𝒞_KOS→ℬ` (`ℬ={Kernel, Representation, Reasoning, Zero, Governance, Execution,...}`) and an explicit
conservation principle — *"a capability required by the system contract cannot be counted as
eliminated merely because responsibility for realizing it has moved to another bounded context"*
(`Cap_Kernel` may shrink while `Cap_KOS` stays fixed). **This directly answers this reconstruction's
own standing distinction** (Kernel candidate vs. MinKer operator vs. capability vs. governance-
selected implementation, as the prompt itself requires): a **capability** is a system-level semantic
requirement; a **Kernel candidate** is a claim about which bounded context owns which capabilities;
`ρ` is the (unfilled) mapping between them; `MinKer` is the criterion that would operate over
Kernel-candidate capability sets once `ρ` and capability identity are both fixed; a
**governance-selected implementation** is a human choice among semantically-equivalent minimal
candidates. None of these five objects is confused with another anywhere in the chain — a genuine,
source-endorsed DDD clarification, usable regardless of whether the mathematical construction ever
completes.

## Phase H — the ten hypotheses

**H1 (the 13-capability universe was designed to force a desired result).** Not established either
way — but the source's own later material (file 8) explicitly names the underlying *risk* (result
depends on arbitrary decomposition) as real and unaddressed, which is a weaker, more precise finding
than confirming or refuting deliberate design.

**H2 (`DetectGap` derivability depends on hidden assumptions).** **SUPPORTED**, directly, by the
source's own later self-critique (see `03_...md`'s "Consequence" section) — this is the chain's own
finding, not one this study introduces.

**H3 (`Qualify` necessity depends on the chosen trace semantics).** **SUPPORTED**, for the identical
reason as H2 — no `Trace`/capability-identity convention is fixed, so "necessity" cannot yet be
distinguished from an artifact of the untested convention.

**H4 (a different admissibility definition produces a different minimal Kernel).** **SUPPORTED** —
directly stated by the source's own correction ("minimality is relative to `(𝔠,𝒞_sem,≡_sem)`," file
9), which is precisely a restatement of H4.

**H5 (multiple incomparable minimal Kernels exist).** **UNRESOLVED** — never tested; the framework
explicitly permits it (`MinKer` as a set, `MinKer/≡sem` cardinality left open) without establishing it.

**H6 (semantic equivalence collapses candidates that are structurally different).** **Not a flaw —
the intended, endorsed behavior of `≡_sem`** (different implementations, same semantics, by design).
Whether it ever actually does so for any real candidate pair: **UNRESOLVED**, untested.

**H7 (structural similarity is being mistaken for semantic equivalence).** **NOT SUPPORTED** as an
error in this source material — the chain is consistently careful to keep `≡_cap`/`≡_sem` distinct
and to reject lexical/structural shortcuts; the risk is named generically but not committed.

**H8 (the proof establishes only a lower bound, not minimality).** **SUPPORTED**, explicitly, by the
source's own evidence-ladder discipline (`E_sample → E_empirical → E_generalization → E_proof`,
files 1 and 6): the toy execution and "eight tested variants" are explicitly classified by the source
itself as experimental/lower-bound evidence, never as the universal theorem.

**H9 (existence is being inferred from construction without checking the formal contract).** **NOT
SUPPORTED** — the source explicitly guards against exactly this ("we must not silently assume
existence merely because we have found a candidate," file 6) and treats existence as its own,
separate, still-open theorem (file 6/9/10 all agree).

**H10 (the result depends on a governance choice rather than mathematics).** **SUPPORTED**, unchanged
and reinforced from MD-044's own Phase F finding.

## Phase E — final classification

**B — PARTIAL FORMAL RESULT; REMAINING INPUTS EXPLICITLY BOUNDED.**

Justification: this phase produced real, if incomplete, formal progress — a corrected `𝔎_adm`/`𝔎_sat`
split, a genuine transition-system-based `Trace`/`≡_sem` shape, counterfactual capability removal, a
DDD responsibility-conservation principle, and (most importantly) a **precisely named** single
remaining blocker (capability-identity/granularity) rather than a vague "several things are
unspecified." This is not **A** (no minimality result was established for any real candidate). Not
**C** (this study did not propose and test a new hypothesis to make the construction executable — it
stopped at the boundary per its own resolution, so no "construction under new hypotheses" was
performed; the *source's own* text discusses what such hypotheses might look like, but this study did
not adopt or test any of them). Not **D** (the K_adm/K_sat fix, counterfactual removal, and
conservation principle are genuine, usable, source-grounded advances, not nothing). Not **E** (no
contradiction was found — file 8's critique of files 6/7 and file 9/10's acceptance of file 8 is
normal same-session peer review, exactly as in MD-044's own uniqueness-correction finding, not an
unresolved inconsistency).

## GA-001 / GA-038, restated

**Both remain UNCHANGED**, for the same reasons as MD-044, now with a sharper account of *why*: the
comparison this phase would need to perform to bear on either gap requires exactly the capability-
identity criterion that does not exist and was not invented here.
