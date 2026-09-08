# Phase 5M — Literal Citation Reconstruction

## Exact text (D285-1 §1, seq 1006, re-quoted verbatim)

> | K-1 | **`K_t` = state over 8 primitives** `{Entity, State, Event, Observation, Proposition,
> Relation, Policy, Action}` | `step-049`; C-022 in `claim-registry` | **RATIFIED** — FA-4 D-FA-6
> *"qualified naming"*; *"50 attack classes, no counterexample"*, **COMPUTATIONALLY TESTED** | 8 |

## Decomposed into the 10 required fields

1. **Exact citation text**: `step-049`; C-022 in `claim-registry`.
2. **Cited artifact name(s)**: `step-049` (a filename fragment); `claim-registry` (a document name,
   unqualified — no path, no date, no `.md` extension given).
3. **Cited claim identifier**: `C-022`.
4. **Claimed subject**: K-1's 8-primitive `K_t` tuple.
5. **Claimed content**: "50 attack classes, no counterexample," computational testing.
6. **Claimed authority**: `FA-4`, `D-FA-6`, "qualified naming."
7. **Claimed ratification meaning**: the table's own header column is literally "Authority," with the
   value `**RATIFIED**` — a status claim, not merely a citation.
8. **Claimed date**: none given directly in this row; D285-1's own document date is 2026-08-31.
9. **Claimed provenance**: implicitly, `step-049` and `claim-registry` are treated as two separate,
   independently-citable sources for the same K-1 row.
10. **Does D285-1 distinguish citation from authority?** **No** — the single table cell conflates a
    source citation (`step-049; C-022 in claim-registry`) with an authority marker (`FA-4 D-FA-6`) and
    a status claim (`RATIFIED`) and an empirical claim ("50 attack classes... COMPUTATIONALLY TESTED")
    all in one cell, without separating which sub-claim each named artifact is supposed to support.
    **This lack of internal separation is itself a finding**: D285-1's own citation practice does not
    allow a reader to determine, from the cell alone, whether `step-049` supports the primitive list,
    `C-022`/`claim-registry` supports the attack-class testing, or `FA-4`/`D-FA-6` supports the
    ratification — all four appear bundled as if mutually self-evident from one shared source.

## Discipline

This file performs no verification — it only reconstructs, literally, what D285-1 claims. Verification
against each candidate artifact is performed in `03`–`05`.
