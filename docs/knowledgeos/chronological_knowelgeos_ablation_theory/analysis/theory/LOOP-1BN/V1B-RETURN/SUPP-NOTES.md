# SUPP-NOTES

## Changes in verify_supp.py (relative to verify_v1b.py)

The only change: three entries added at the end of the `NAMED` dict (V1B-SPEC §C named histories). No other line was changed.

| Name | Tuple (s0, s1, a1, a2, cap, w1, ref, con1, con2, r, c) | Designated for |
|---|---|---|
| `H_U5h` | (q, q, iss(d=0, v=q, prep=1, f=∞), absent, ∅, t=0, {a1, w1}, none, none, 0, c2) | R-h |
| `H_U5i` | the same as H_U5h | R-i, R-i′ |
| `H_U5i_c1` | H_U5i with c := c1 | R-i, R-i′ |

`H_U5i_c1` is the spec's "H_U5i with c := c1". I added it as its own named history so that the existing named-history code path computes its fields.

## Things to be aware of

- Because the output code is unchanged, `verify_supp.py` writes its full output to **V1B-RESULTS.json**, the same filename verify_v1b.py uses. There was no V1B-RESULTS.json in the directory before the run, so nothing was overwritten. Changing the filename would have meant changing another code path.
- The new entries only add rows to the named-history output. They don't affect any check, image, diff, or existing named-history value. Each reading/frame's named-history table still includes the new entries, including readings they aren't designated for.
- Run: `python3 verify_supp.py`, 665920 histories in U, about 54 s.

## Supporting values (from V1B-RESULTS.json)

- H_U5h is in U and in U5, because σ ≡ q. Under R-h it is in R_B and H-D but not H-B, and w1 violates, so it is in the (iii)-L scope in both frames.
- In frame U the least (iii)-L witness is σ′ = (p, q) with a1's f′ = 1 and c = c2. That witness is not in U5, because c2 requires σ ≡ q. In U5 no σ′ with w1 conforming at t = 0 is allowed, so iiiL is false.
- iiO_c in U5: no h_c in H-D ∩ H-B with w1 conforming has O = (r=0, c=c2), because w1 conforming forces σ ≢ q. So the value is false for c2 and true for c1, under both R-i and R-i′.
