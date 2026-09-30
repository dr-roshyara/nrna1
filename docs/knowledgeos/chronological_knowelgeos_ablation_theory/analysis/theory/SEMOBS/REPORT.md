# Semantic-observation layer v1 and the strict minimal-pair laboratory (no new corpus read)

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Instrument | schema `SEMOBS/SCHEMA.md` + `semobs.py` (`13247dd3…`), committed at `0122c823f` before the analysis; `RESULT.json` (`67e5e948…`); selftest 3/3 |
| Data | 31 semantic observations re-expressed from already-read sources, in 20 independence clusters. Genres: register row, session log, plan, ADR, git |
| Log | F-LOG-0141 |

## Results by category

**OBSERVATION**
- Every event carries: its semantic fields; realization fields (genre, speech act); an epistemic basis; and its independence cluster.
- Unestablished fields are `UNK`, with no defaults. The earlier `c = ok`, `x = none` and route defaults are removed.

**EMPIRICAL RESULT: strict single-field witnesses, counted by distinct independence clusters**

| Variable | Verdict | Independent strict witnesses |
|---|---|---|
| **authority** | **EMPIRICALLY SUPPORTED** | 2: {R-81…85 annotation, R-86}; {R-70, R-89} |
| **object kind** | **EMPIRICALLY SUPPORTED** | 2: {R-60}; {S0804-L576} |
| operation | WEAK | 1: {R-89, R-95} |
| state | WEAK | 1: {R-72, git 6a67da5d7} |
| target/scope | WEAK | 1: {R-47} |
| evidence | WEAK | 1 cluster (R-36: 12 contrasts, **one decision**) |
| history | NOT DEMONSTRATED (possible) | 0 strict; 1 possible (the route is not established for the numbering acts) |
| **role conformance** | **NOT DEMONSTRATED** | 0. R-86 vs R-91 differ in kind, target *and* conformance once nothing is inferred |
| route | NOT DEMONSTRATED | 0 |
| exception | NOT DEMONSTRATED | 0 |

**REPRESENTATIONAL RESULT vs EMPIRICAL RESULT: a correction.**
- The non-circular ablation (F-LOG-0140) listed h and c as representationally necessary.
- **Under the strict schema, c has no empirical witness, and h has only a possible one.**
- Their representational necessity depended on values filled in without source support (`c = ok`, route and exception defaults). **That is the silent-inference effect the schema was designed to expose.**
- The eight-field set is therefore **representational for a leniently coded table only**.

**HYPOTHESIS**
- ROLE-SEPARATION-HYPOTHESIS (supported by 4 principle statements; 0 strict pairs).
- The choice layer (explanatory; 1 row).
- Operation subtype (implement vs plan) as the PREPARED trigger.

**FALSIFIER** (what would move each verdict)
- A second independent cluster with a strict pair would promote operation, state, target or evidence to SUPPORTED.
- A strict pair in which the variable differs but the outcome is equal would count against it.
  - This pattern is not a falsifier on its own: another variable could compensate.
  - It is recorded as disconfirming evidence only when every other applicable field is equal.

**UNKNOWN**
- The conformance of R-86's adopted rulings is not stated.
- The route is not stated for the numbering acts.
- Exceptions are not recorded for most acts, because the register has no exception field.

**FORMAL RESULT:** none new. The competing models M0…M5 add no information until there are more strict witnesses. With the current data, **several models remain observationally equivalent** on weak variables (STOP-E).

**GOVERNANCE DECISION:** none. Gate 1 proper is still pending external commissioning. The bundle is untouched.

## The smallest next observations (ranked: fewest independent witnesses first)
1. **Role conformance:** two Decision-Authority adoptions of rulings of the *same* kind and target class, one role-collapsed, one conformant, with both conformances stated. Where to look (locate-only): register rows "HELD" / "not eligible" / "self-certify", plus a paired adoption.
2. **History:** equal current state with different histories and opposite outcomes, with the route stated (retired or recycled identifiers; reopen cases).
3. **Evidence:** a strict pair in a decision cluster **other than R-36**: session-log promotion dispositions ("promotion criterion", "third instance").
4. **Operation:** a second Chief implementation-vs-planning pair (the R-89 / R-95 family).
5. **Route and exception:** not observable in this corpus; they need a designed record.

**Stop rule:** each of these is chosen because it can move exactly one verdict. None is a coverage read.
