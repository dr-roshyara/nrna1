# T-A result record — SELF reader only (class: EXPERIMENT; not validation, not theory)

| | |
|---|---|
| **Frozen hypothesis** | H-F2-1-R (attack document §14), status F2-FORMAL-CANDIDATE, not changed |
| **Frozen model** | `analysis/h_f2_1/model.py` v1.1; results reproduced by `verifier.py` (SECONDARY_REVIEW) |
| **Frozen setup** | pre-registration r3 sha256 `be16deb7…7133` · `aggregate.py` sha256 `13532a5b…ef71` · `--scope UNIVERSAL --released M-1,M-4` · deterministic, no seed, no ML |
| **Release** | L0-DEC-31 (`07989c3a9`), RRC-02 YELLOW |
| **Material read** | F0018 (`d61bf5e84`) plus the 7 ES-006 objects, complete, by object (`READ-LOG.jsonl`) |
| **Ledger** | `records.jsonl`, 26 records, **sealed** before aggregation: sha256 `abfa919b…1ce8` (F-LOG-0040, commit `8999c3fb6`) |
| **Aggregate** | `aggregate.json` sha256 `0d003b6d48db88c9269796947cc0b8e0ce71c38eb375c35edfccb1d79dbe8082` |

## Per-question verdicts (r3 §5.0)

| Test | N | S | C | A | U | Verdict | Typed / UNKNOWN |
|---|---|---|---|---|---|---|---|
| F-A2e | 4 | 3 | 0 | 0 | 1 | SUPPORTED | 4 / 0 |
| F-A3g | 2 | 2 | 0 | 0 | 0 | SUPPORTED | 2 / 0 |
| F-A4 | 3 | 2 | 0 | 1 | 0 | SUPPORTED | 3 / 0 |
| **F-A5e** | 4 | 3 | 0 | **1** | 0 | **INCONCLUSIVE** (§5.0 rule 2: a live counterexample reading) | 4 / 0 |
| F-A5g | 3 | 2 | 0 | 0 | 1 | SUPPORTED | 3 / 0 |
| **F-A6** (primary) | 4 | 0 | 0 | 0 | 4 | **NOT_EVIDENCED** | 3 / 1 |
| F-A0 | 2 | 1 | 0 | 0 | 1 | SUPPORTED | 1 / 1 |
| F-A3m | 2 | 2 | 0 | 0 | 0 | SUPPORTED | 2 / 0 |
| Q-H6 | 2 | — | — | — | — | readings: BOTH (state + act) · STATE | — |
| Q-GS, Q-D4 | — | — | — | — | — | **NOT_RUN** (M-2/M-3 not released) | — |

**Proposition status (§5.1):** no axiom has COUNTEREXAMPLE_FOUND, so no proposition is UNSUPPORTED or FALSIFIED by this run.

## Outcome (r3 §5 candidate-level precedence)

> ## **H-F2-1a: INCONCLUSIVE** (SELF reader)

Why, by the fixed rules:
- A5e is INCONCLUSIVE, which takes precedence 2.
- A6 is NOT_EVIDENCED, which would give PARTIALLY UNTESTED, but that is precedence 3 and does not decide.
- Stability extension (A0, A3m): NOT_REFUTED_IN_T-A.

**Falsifier status:**
- No DIRECT COUNTEREXAMPLE to any axiom.
- One live counterexample reading: **F0018 §5, "P-7 produces P-10 — work produces evidence"**. Either a WORK step directly advances evidential position (a counterexample to A5e), or work outputs enter a separate EVIDENTIAL accumulation (no counterexample).
- F-A6: the released sources record meta-level rule changes, but **never state their effect on existing or pending items**.

**Prediction diagnostic** (`expected_finding_matched`, computed after sealing; **not evidence**):
- matched: 5 of 8 (F-A2e, F-A3g, F-A5e, F-A0, F-A3m);
- mismatched: 3 (F-A4 predicted INCONCLUSIVE, observed SUPPORTED; F-A5g predicted NOT_EVIDENCED, observed SUPPORTED; F-A6 predicted INCONCLUSIVE/COUNTEREXAMPLE, observed NOT_EVIDENCED);
- the reader is therefore not merely confirming its priors.

## Observations (reported, not classified)

- **Source-fidelity observation.** F0018 attributes an *"n≥2"* bar and *"never promote from a single occurrence"* to ES-006.1, and gives the bar's home as *"ES-006.1's bar · CAP-001 §9"*. **Neither phrase appears in any of the 7 released ES-006 objects.** The ES-006.1 paragraph is byte-identical across all seven (`614c8fe4cbcd`). The bar H-F2-1-R models is therefore not stated in the released ES-006 text; its stated home may be CAP-001 §9, which is not released.
- ES-006 revisions 2026-07-11 → 07-26 change header metadata and ES-006.4 (the harvest question) only. ES-006.1 (the ladder) never changes in the released history.

## Limitations

- **SELF only.** The reader is the author of H-F2-1-R and not blind to §3.2. **Not independently verified.** An INDEPENDENT ledger and a disagreement table are required before any independence claim.
- **Mandatory T-0056 caveat.** T-0056 is not an input. T-A tests F0018 and ES-006 directly. The combination T-0013 × T-0014 × T-0056 has no formal relation-row support. Batch 3 is not retroactively certified. None of this is evidence for or against H-F2-1-R.
- **Fidelity, not corroboration.** H-F2-1-R was formed from F0018. Agreement with F0018 is source fidelity; only F-A6 (ES-006) is partly out-of-sample.
- **Scope.** The result holds only for F0018 plus the 7 ES-006 objects. It is not generalizable, not a validation, and not canonical.
- F2800, RC-H-04, M-2 and M-3 were not read.

## Next (r3 branch for INCONCLUSIVE; not started)

- **Independent verification:** a fresh-context INDEPENDENT reader (the handoff is in `prompts/KNOWLEDGEOS-T-A-GOVERNANCE-CLOSURE-PACKET.md` §2), then a disagreement table.
- **Targeted reconstruction obligation, A5e:** what does *"work produces evidence"* (P-7 → P-10) denote: a WORK step that changes evidential position, or work output admitted by a separate evidential step? Needs a released source that describes the P-10 increment mechanism.
- **A6:** still untested. It needs a released source that states how bar/rule changes affect pending items. The candidate named by F0018 is CAP-001 §9 (not released). That needs a new L0 release.
