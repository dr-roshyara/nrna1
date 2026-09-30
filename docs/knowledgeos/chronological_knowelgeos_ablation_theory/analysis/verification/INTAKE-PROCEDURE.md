# Intake procedure for an independent result (fixed before any result exists)

Applies to each bundle separately (G2: `71312955…`; G25: `008d66de…`). It **adds no rule** to `CANONICAL-SCHEMA.md` (frozen); it orders the steps.

| Gate | Step | Record |
|---|---|---|
| 1 Preserve | on receipt, **before opening** `results.json`: sha256 of every received file; the verifier's reported results hash must equal the computed one; store the files read-only under `analysis/<gate>/INDEPENDENT-<n>/` | F-LOG entry: hashes, verifier identity (name / model + version), signed independence declaration, date |
| 2 Inspect implementation | read the code and METHOD.md (**not** results.json): it cites the spec hash (`88cc6a3c…` / `5423ebb9…`); the runtime and version; exhaustive rather than sampled; declared readings (C-7) listed; independence plausible (no reference artifacts cited) | a checklist result in the F-LOG entry. A failed check is recorded, not repaired, and the human decides |
| 3 Adapter | written from the results.json **structure** (the key tree without values): relocation plus the §3 vocabulary only; unmapped fields listed; committed with its hash | adapter hash |
| 4 Mechanical comparison | `canon.py` (reference) plus the adapter (independent), then `compare_mech.py` (frozen `2c286c93…`) | DIFF.json hash, sealed |
| 5 Outcome, then truth values | r2-7 outcome (C-1…C-7) → only then read truth values; report separately: mathematical reproduction · model-property result · interpretation · corpus implication | outcome record; no model selection |

The commissioning text given to the verifier is `~/VERIFIER-COMMISSIONING-PROMPT.txt`, used verbatim and once per bundle. It carries no project context.
