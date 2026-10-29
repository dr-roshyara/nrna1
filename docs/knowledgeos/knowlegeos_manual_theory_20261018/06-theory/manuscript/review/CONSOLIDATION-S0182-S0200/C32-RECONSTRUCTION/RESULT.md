# Independent reconstruction of a critical finding at S0200: C32 W-01, "the record can fork"

*Governance Engineer, 2026-10-29. Required by the decision record (addendum 2026-10-28T02:25:00Z, item 4: "one critical finding reconstructed independently at each milestone (first: the C32 fork, at S0200)"). Labels: **verified** · **reported** · **inferred** · **open**.*

## The finding (reported)

C32 review W-01 (critical, `C32-S0171-S0176/REVIEW-REPORT.md`): under the rule as applied at `9260e6d3a` (§9.17 R3 (c), model v17), two designations can both close store C1, one to C2 and one to C3. The append guard accepts both and both are in D, so K2's single designated record is not guaranteed. A stale "old store" is also accepted. Settled in `167e5a7b8` (model v18, check 30); later extended by consolidation S0151–S0181 (A-02: carrier reuse; attestation).

## Method

A new model (`recon.py`, 70 lines), written from the **text** of §9.17 R3 (c) only, with **no import or reading of `ce_model.py`**:
- **OLD rule** (text at `9260e6d3a`): the guard requires the designation to cite every maximal entry of its old store at that step; D checks closed entries cited, in the old store, the designation appended to the carrier it names, which differs from the old one.
- **NEW rule** (text at HEAD, R3 (c) item 1 and the "across the record" clauses): additionally, the old store is the carrier in force; the new carrier has never been a carrier; at most one designation per store; one chain from the root carrier; every entry's store is in the chain.
- Kernel steps serialised (K6); data entries appended to the carrier in force by the kernel; authority assumed T.
- **Exhaustive search:** every sequence of up to 5 kernel steps over 3 stores (19,607 sequences per rule), plus a bypass case built without the guard.

## Results (verified; `recon-run.txt`, `variant-run.txt`)

| Case | OLD rule | NEW rule |
|---|---|---|
| The reviewer's case (a in C1; d1 C1→C2; d2 C1→C3) | both accepted, **forked, in D** | d2 refused; no fork |
| All sequences of ≤ 5 guarded steps | **1,616 forked records** | **0 forks**, 0 records outside D |
| A forked record written by bypass | in D | **outside D** |
| Carrier-in-force guard without "never a carrier" (the state between C32 and consolidation A-02) | — | **828 records outside D**, first C1→C2→C1: the reconstruction also finds consolidation A-02 |

The Professor's model v20, re-run now, reproduces its saved output byte for byte (`ce_model-run-20261028-v20.txt`); its check 30 (fork refused, forked record outside D, stale refused) and check 31 (bypass) pass. **(verified)**

## Conclusion

- **The finding was real and its severity right (verified):** under the rule as then applied, the record could fork by guarded kernel steps alone, and K2-d/K2-w, CON-0264 and R3 (c) rested on it (F1).
- **The settlement and the later consolidation close it within their assumptions (verified in the model, inferred for the book):** with a serialised kernel, no guarded sequence in the searched space forks or leaves D; a bypass fork is outside D.
- **Two independent routes agree** (the reviewer's probe on v17 and this model from the text), and the search found the next defect (A-02) that the C32 settlement still left open — evidence that the repair needed the consolidation's correction, as the record shows.

## Limits (open)

- The model encodes **my reading of the text**; a misreading would be shared by both rules.
- **Small universe:** 3 stores, ≤ 5 steps. It is a bounded search, not a proof.
- **Not modelled:** authority evaluation (assumed T), attestation (assumed unforgeable, as the Principal's turn-8 decision records), and **merged applications**, where the book claims detection, not prevention (T9-10).
- The NEW rule's chain clauses are the Professor's additions (C33 W-02); they now rest on the Principal's turn-8 decision ("a store is a carrier only once"; designation closed as a design sketch).
