# Phase 5I — `t285_reconcile.py` Executable-Semantics Audit

## A. Object construction

No data-record constructor exists in this script. It manipulates **Python `set` literals** representing
label sets: `RATIFIED`, `VERIF`, `ASSERTION_CONTAINS`, `VERIF_EXPANDED`, `VERIF_EXTERNAL`. It does not
construct or manipulate instances of `Entity`/`Assertion`/etc. — it operates entirely at the level of
**names of primitives**, not their content.

## B. Equality

Set equality (`==` on Python sets) is used implicitly via set difference/intersection operators; no
custom equality function is defined in this file (contrast `04`/`05`, which do define custom equality
functions).

## C. Identity

Not addressed — this script never constructs individual objects with identifiers; it is entirely a
label-set-arithmetic exercise, one level of abstraction above where "identity" would apply.

## D. Projection

The `pi` dictionary (hardcoded, not computed): maps each of the 8 `RATIFIED` primitive names to a
string describing its image (e.g. `"Proposition": "-> P inside Assertion"`). **This is a hardcoded
lookup table, not a computed function** — the "projection" is authored directly as data, not derived
from `ASSERTION_CONTAINS`/`VERIF_EXPANDED` by any code path. The printed claim *"Every ratified
primitive has an image or an explicit drop: TOTAL = True"* is asserted in a `print` statement, not
verified by an executed boolean check against the `pi` dict's own keys.

## E. Query space

None — this script does not implement or test any query against a state; it produces only the
set-arithmetic report (T-A), a hardcoded model-discrimination table (T-B, via the `verdict()` helper —
each call's `holds`/`why` are hardcoded booleans/strings, not computed), the `pi`-dict projection
report (T-C), a small worked identity/replay example (T-D), and a small worked action/result example
(T-E, using literal Python dict/set operations, genuinely computed).

## F. Information loss

**Re-executed this phase** (`00`, headline finding): `r_only - VERIF_EXTERNAL` computes to
`{Observation, State}` — the script's own **genuinely computed** output (this specific line IS derived
from the set arithmetic, not hardcoded) places `Observation` in the same "simply absent" bucket as
`State`. This directly reproduces the framing D285-1's own prose §"the lanes agree" passage explicitly
retracted ("Too strong... they do differ on `Observation`"). **The script was never updated to encode
the Sañjaya-layer distinction.**

## G. Failure behavior

No error handling exists; the script assumes all its hardcoded sets are correctly populated and would
simply produce a (possibly misleading, as shown in F) result silently — there is no assertion or
sanity check comparing its own computed sets against, e.g., D285-1's own published table.

## H. Hidden assumptions

1. That `ASSERTION_CONTAINS` (a hand-authored constant) correctly represents "the verification lane";
   no code derives this set from any other source file or data structure.
2. That `VERIF_EXTERNAL` (also hand-authored) correctly represents the verification lane's own
   externality declarations; same caveat.
3. That the `pi` dict (hardcoded, part D above) correctly represents the projection; **it is not
   derived from `ASSERTION_CONTAINS` or checked against it anywhere in the script** — e.g., nothing
   verifies that `pi["Observation"]`'s description is consistent with whether `"Observation"` is or
   is not in `ASSERTION_CONTAINS`/`VERIF_EXPANDED` (it is not, per the set arithmetic — yet `pi` still
   assigns it an image, "→ e after Qualify," treating it as *not* simply dropped, which is
   **inconsistent with this same script's own T-A computation** that placed it in the "ABSENT" bucket
   rather than the "declared external, deliberately dropped" bucket).

## Verdict — a second, internal inconsistency found within this single script

Beyond the cross-document finding (F above), this script is **internally inconsistent with itself**:
its T-A section computes `Observation` as merely "absent" (not "declared external"), while its own T-C
section's `pi` dict treats `Observation` as having a defined (if non-computable) image, implying it is
*not* simply dropped. **A single script's own two sections disagree about `Observation`'s status.**
This is recorded as a genuine, machine-observable finding — not repaired.
