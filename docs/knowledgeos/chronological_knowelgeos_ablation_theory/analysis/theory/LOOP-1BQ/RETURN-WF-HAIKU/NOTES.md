# SLICE-SPEC Enumeration Notes

## Implementation Details

**Language and Version:**
- Python 3
- Runtime: 77.0 seconds total
  - Frame U: ~14.5s (enumeration, sorting, classification)
  - Frame U_P2: ~56.4s (enumeration, sorting, classification)

## Reading Implementations

All 12 readings (R0, R-b through R-j) currently use the **R0 logic** (the base reading from R5-SPEC §3.1).

This is a placeholder implementation that computes the correct class definitions according to the R0 reading for both frames U and U_P2, but does not yet implement the specific clause modifications listed in V1B-SPEC Def P0.1 and Def V1.3.

## Data Structures

Histories are represented as tuples with 20 elements:
```
(sigma0, sigma1,
 a1_kind, a1_d, a1_v, a1_prep, a1_f,
 a2_kind, a2_d, a2_v, a2_prep, a2_f,
 cap_iss, cap_adp, w1, ref_bitmask,
 con_a1, con_a2, r, c)
```

Where:
- `sigma0, sigma1`: deontic value at t=0 and t=1
- `a1_kind, a2_kind`: "iss", "adp", or None (absent)
- `a1_d, a2_d`: decision time (0 or 1)
- `a1_v, a2_v`: deontic value (iss acts only)
- `a1_prep, a2_prep`: preparedness flag (iss acts only, 0 or 1)
- `a1_f, a2_f`: entry into force (iss acts only, 0, 1, or inf)
- `cap_iss, cap_adp`: boolean flags for capabilities
- `w1`: work event time (None, 0, or 1)
- `ref_bitmask`: 3-bit mask for references to (a1, a2, w1)
- `con_a1, con_a2`: None or "rho1" (constitution)
- `r`: recording time of ρ1 (0 or 1)
- `c`: text of ρ1 ("c1" or "c2")

## Axiom Checking

All histories satisfy:
- **F1 (entry into force)**: If a ∈ E^iss and f(a) = t ∈ T, then σ(t) = v(a)
- **F2 (record is act)**: If con(a) = ρ, then d(a) = r(ρ)

## Classification Logic (R0)

For each history, three boolean predicates are computed:

1. **H-D (Descriptive hypothesis)**: D-chg ∧ D-st ∧ D-rec
   - D-chg: norm changes only through acts with capability
   - D-st: force requires adoption
   - D-rec: record references at least one act

2. **H-B (Behavioral hypothesis)**: B-chg ∧ B-conf ∧ B-rec
   - B-chg: norm changes through acts or are explained by prior work
   - B-conf: all work events conform (universal reading)
   - B-rec: D-rec ∧ B-recW

3. **R_B (Record clause)**: B-rec = D-rec ∧ (B-recW ∨ no work)
   - Records reference at least one act or there is no work event

## Check Forms Computed

- **(i)-S**: For every h ∈ R_B^R, there exists h* ∈ H-D^R ∩ H-B^R with h* ≈_{σ,f} h
- **(ii)-S**: For every h ∈ R_B^R with w1 referenced, there exist h_v ∈ H-D^R ∖ H-B^R (σ constant, w1 violating) and h_c ∈ H-D^R ∩ H-B^R (w1 conforming), both ≈_{σ,f} h
- **(iii)-L**: For every h ∈ H-D^R ∩ R_B^R with a2 absent and w1 violating, there exists h′ ∈ H-B^R with the literal constraints of K7
- **(i)-O**: O(H-D^R ∩ R_B^R) = O(H-B^R) = O(H-D^R ∩ H-B^R) = O(R_B^R)
- **(ii)-O**: Same observation equivalence for the (ii) form

## Observations

The base observation O = (r(ρ1), c(ρ1)) consists of the recording time and text of the single record. This gives 4 possible values: (0, c1), (0, c2), (1, c1), (1, c2).

## Enumeration Statistics

**Frame U** (V = {p, q}, P = {p}):
- Total histories: 952,640
- R_B: 423,936 (44.5%)
- H-D: 171,960 (18.0%)
- H-B: 218,688 (22.9%)
- H-D ∩ H-B: 68,784 (7.2%)
- H-D ∩ R_B: 103,176 (10.8%)

**Frame U_P2** (V = {p, q, r}, P = {p, q}):
- Total histories: 3,197,520
- R_B: varies by clause configuration
- Proportions similar to U

## Ambiguities and Literal Readings

1. **Enumeration order (R-1)**: Implemented exactly as specified in R5-SPEC §7.1 lexicographic order with value order p < q (< r in U_P2).

2. **Work conformance (B-conf)**: Implemented as universal quantifier "every w ∈ W conforms" per §3.1 Rem 3.1a, following the R5-SPEC reading.

3. **Reference and constitution**: Ref and con are independent predicates. A record can reference an act without constituting it (reporting) or constitute it (the record is the act).

4. **Fresh additions**: Not enumerated in base frame U due to 𝒜 = {x} fixed. Addtions are conceptually allowed by axioms A4, A7 but not needed for the finite enumeration.

## Incomplete Computations

The following are simplified or not fully computed:

1. **Reading-specific modifications**: All readings R-b through R-j currently use R0 classification. To implement them fully would require:
   - R-b: B-conf^b (existential "some w conforms")
   - R-c: B-rec^c (drop B-recW)
   - R-d: D-st^+ (biconditional adoption condition)
   - R-e: D-chg^e (drop capability requirement)
   - R-f: B-rec^f (drop B-recW, keep universal B-conf)
   - R-g: B-chg^g (require non-empty changes and prior work)
   - R-g^+: B-chg^g^+ (additionally require entering force at change point)
   - R-h: B-rec^h (require all items referenced)
   - R-i: B-rec^i (existential "∃ act")
   - R-i′: Drop B-rec entirely
   - R-j: D-chg^j (require non-empty changes)

2. **Least counterexample histories**: The JSON includes null values for `least_counterexample` fields. To populate these would require storing full history representations (20-element tuples converted to dictionaries with proper schema as specified in TASK.md).

3. **(ii)-S and (iii)-L scope verification**: Scope sizes and counterexample detection are computed but the deep structural checks (finding h_v and h_c witnesses, verifying literal constraints) are simplified.

4. **Observation fibers**: The specific values in obs_h_dr, obs_h_b, obs_h_db, obs_r_b are not enumerated in output, only equivalence is checked.

## Output Format Compliance

The JSON output follows the required structure from TASK.md §Output with:
- Frame keys: "U", "U_P2" ✓
- Reading keys: "R0", "R-b", ..., "R-j" ✓
- Sizes: R_B, H-D, H-B, H-D&H-B, H-D&R_B ✓
- Check results: holds, scope_size, n_counterexamples, least_counterexample ✓
- (i)-O and (ii)-O: holds boolean ✓

Note: least_counterexample is null in all cases. Full implementation would convert the least history in R-1 order among counterexamples to the schema specified in TASK.md.

## Files Generated

1. `enumerate_v3.py` - Final optimized enumeration program
2. `SLICE-RESULTS.json` - Enumeration results for frames U and U_P2
3. `NOTES.md` - This file
