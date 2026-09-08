# Phase 5J — Assertion Version Adjudication (per the authorization's §12)

## D1/D3 position (D285-1 / `t285_reconcile.py`)

- **Ontology**: an Assertion carries a Proposition and an Entity, plus Evidence/Context/Time/Provenance
  as independent conceptual fields.
- **Representation**: 6 named conceptual fields, no computed/derived fields.
- **Semantics**: each field named directly, no internal structure elaborated further.
- **Computation**: no executable body beyond the set-membership arithmetic in `t285_reconcile.py`.
- **Provenance**: cites "step-049; C-022 in claim-registry" for K-1's own primitives (not for this
  unpacking specifically); no source cited for the unpacking itself.
- **Authority**: none found (`07`).
- **Temporal status**: 2026-08-31 (prose); code undated.
- **Supersession**: none stated.
- **Compatibility with D2/D4**: **not evidenced** — no transformation stated (Phase 5I `06`).

## D2/D4 position (D285-6 / `t285_equality.py`)

- **Ontology**: an Assertion carries a Proposition, Entity, and Observation, plus `id/c/t/Π` as a mix
  of derived-identifier and technical fields.
- **Representation**: 3 conceptual + 4 technical fields.
- **Semantics**: `id` glossed as an identifier; `c/t/Π` left unexpanded in the prose itself (only
  `e_equality.py`'s own worked example gives them concrete shapes — see `10`).
- **Computation**: `t285_equality.py` implements genuine subset-membership testing (Test 2); `Qualify`
  itself remains uncomputed.
- **Provenance**: no source cited for this specific unpacking either.
- **Authority**: none found (`07`).
- **Temporal status**: 2026-08-31 (prose, same date as D1); code undated.
- **Supersession**: none stated.
- **Compatibility with D1/D3**: **not evidenced**, same finding as above, from the other direction.

## D5 position (`e_equality.py`)

- **Ontology**: an Assertion is a content-addressed record with 5 given fields plus 1 derived hash.
- **Representation**: `A(P,e,c,t,Pi) → dict(...,id=H(...))`.
- **Semantics**: concretely demonstrated via worked examples (`a_scan`, `a_vendor`) — the only record
  among the 7 with actual example instances, not just type/field names.
- **Computation**: fully executable, re-run this phase and in Phase 5I.
- **Provenance**: the one record with an explicit external citation ("Reviewer B's mandate E3/E4/E5...
  Step 246").
- **Authority**: none of the searched markers found, but its own explicit grounding in "Step 246" gives
  it the strongest *documentary* traceability of the three code records.
- **Temporal status**: undated internally; same bulk-import git date as D3/D4.
- **Supersession**: none stated.
- **Compatibility with D1/D3 and D2/D4**: **narrower in scope than either** — it does not name `Entity`
  or `Observation` as independent top-level fields at all (`Entity` is presumably nested inside `P`,
  per D285-6's own gloss, but `e_equality.py`'s own code never unpacks `P`'s internal structure).

## Verdict

**No pair among D1/D3, D2/D4, and D5 satisfies the versioning test (`13`)** — none carries an explicit
revision statement, a stated migration mapping, or a demonstrated backward-compatible transformation.
Each is a self-consistent, internally-coherent characterization with **no evidenced relationship to
the others beyond sharing the word "Assertion."**
