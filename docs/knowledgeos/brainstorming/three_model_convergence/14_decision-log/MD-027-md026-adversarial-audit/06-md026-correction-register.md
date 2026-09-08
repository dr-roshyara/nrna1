# MD-026 Correction Register (MD-026's own text not edited — corrections recorded here only)

| Finding | Original MD-026 wording | Audit result | Required narrowing/correction |
|---|---|---|---|
| Filesystem existence | *"The boundary decision could not have 'omitted' a directory that... did not yet exist to omit"* | Conflates Git-tracking date with filesystem-existence date | **"`research/` was not Git-tracked at the time of the boundary decision; whether it existed untracked on disk cannot be established from available evidence."** |
| Evidentiary independence | *"Two independent, decisive pieces of evidence converge on Outcome C"* | The two "pieces" are sequential facts in one repository-history chain, not independently-sampled confirmations | **"Two convergent, but evidentially-dependent, observations — both drawn from the same repository-history chain — support Outcome C with moderate, not decisive, strength."** |
| DDD characterization | *"a separate, self-governing research context"* | "Self-governing" implies DDD authority/ownership not source-attested; the commit message it partly rests on is archival, not governance, language (falsifier 4) | **"A separately organized research lane (source-attested: self-labeling, internal falsification/supersession discipline); DDD bounded-context authority relative to Model B is not established."** |
| Outcome C strength | *"predominantly Outcome C (separate research context), with one material qualification that keeps Outcome A genuinely open"* | Correct in substance once "self-governing" is downgraded; the qualification already correctly flagged M0030 as the open question | **No further change needed** — this sentence already carried its own hedge; only the DDD-authority language feeding into it required correction |
| Admission-mechanism scope | *"No mechanism for admitting previously out-of-scope evidence was found in `00_control/protocol.md` or MD-010/MD-011"* | Correct as stated, but could be misread as "no mechanism exists anywhere in the repository" | **"None found in the documents read (`protocol.md`, MD-010, MD-011); the wider repository was not exhaustively searched for an admission mechanism."** |

## What did NOT require correction

Claims 4, 5, 8, 9, 10 (per `01`'s own table) — the M0030 citation finding, its explicit
`[RECONSTRUCTED PROVENANCE]` cap, the `theory-v1.1-simulation/` weaker rating, and the `ConflictRecord`/
`Θ` negative findings — all survive this audit exactly as MD-026 stated them, with no narrowing
required. **MD-026's own explicit self-hedging throughout (the `E1-OUT-OF-SCOPE` classification, the
"not stronger than reconstructed provenance" language) meant most of its own claims were already
correctly scoped** — this audit's corrections concentrate specifically on the two places where MD-026's
own summary/headline prose ran ahead of its own detailed, already-careful section-level findings.
