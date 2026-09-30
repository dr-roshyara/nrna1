# R-2 — Independent formal verification: handoff and PRE-REGISTERED comparison rule

| | |
|---|---|
| **Kind** | handoff for the human L0, plus a comparison rule **fixed before any R-2 result exists**. ⚠ authority: generated. **Parent-side document: do NOT give it to the verifier** (it names the reference results) |
| **Purpose** | a formal-track replication, independent of T-A and of any corpus reading: does a different implementer reproduce the formal claims of `SPEC.md`? It does **not** ask whether H-F2-1-R is true |
| **Status** | prepared; **not commissioned** |

## 1. What the verifier receives (only this)

- **Bundle:** `R2-VERIFIER-BUNDLE-01.tar`, sha256 `05d0ad885f037dbc8dc762fec87249afa1aa4ea9c4b9e1efb5f28007dd00866d`.
  - It sits in the session scratchpad (temporary; copy it somewhere durable). It can be rebuilt from `SPEC.md` plus the README text below.
- **Contents:**
  - `SPEC.md`: byte-identical to `analysis/h_f2_1_verifier/SPEC.md`, sha256 `44c40fd42be0e878d066cea66f105686eb54dfb7a3e3bb69c54aaa053326869f` (commit `bc4556376`). It is results-free by construction: it states what to check, never the answer.
  - `README.md`: neutral instructions and an independence declaration, sha256 `f67346f19f3cb4f4d4e57bb62654ee780f72b9cbaedab82cb5a24d13760455a4`.
  - `BUNDLE.sha256`.
- **Never given:**
  - `model.py`, `results.json`, `verifier.py`, `verifier_results.json`;
  - the attack document or closure pass;
  - any T-A material;
  - this document.

## 2. Who may verify

- A **person**, or a **different model family**, in a fresh context, commissioned by the human.
- **Not** this Claude session, **not** a Claude subagent (that would be SECONDARY_REVIEW, as the existing `verifier.py` already is), and **not** the senior reviewer (who has seen the results).
- It may be the same party as the T-A independent reader **only if commissioned as a separate task**, with no T-A material present.

**Record:** identity · model family or human · signed declaration · date · the bundle sha256 as verified by the verifier.

**Return:** the sha256 of `results.json` **first**, then the files.

## 3. Pre-registered comparison rule (fixed 2026-09-26, before any R-2 result)

**Reference:** `analysis/h_f2_1/results.json` (sha256 `75b58856…`, from `model.py` v1.1) and `analysis/h_f2_1_verifier/verifier_results.json` (sha256 `372cf3e7…`). The two already agree mechanically (closure pass §2.1).

| # | Item compared | Criterion |
|---|---|---|
| C-1 | state counts, with and without A0, per instance | exact equality |
| C-2 | full-axiom truth values of D1, D2, D3, D3+, D5, D6, NV (7 × 4 instances) | exact equality |
| C-3 | single-removal truth values (11 axioms × 7 propositions × 4 instances) | exact equality |
| C-4 | inclusion-minimal axiom sets for D1…D6 per instance | equality **as sets of sets** |
| C-5 | shortest countermodels | the **length** must match. The specific trajectory may differ (ties are legitimate) |
| C-6 | method | an exactness argument must be present (not sampling) |

**Outcome categories** (exactly one):

| Outcome | Condition |
|---|---|
| **REPRODUCED** | C-1…C-6 all hold |
| **REPRODUCED UNDER A DIFFERENT READING** | every mismatch is explained by an interpretation choice the verifier declared in METHOD.md. It is then recorded as a **SPEC.md ambiguity** (a specification defect), and the reference results stand only under the reference reading |
| **NOT REPRODUCED** | any mismatch not explained by a declared interpretation choice |

**Mismatch handling:**
- **Never** edit `model.py`, `verifier.py` or the reference results so that they agree.
- Each unexplained mismatch is re-derived **by hand** on the specific instance, axiom set and proposition, and the derivation is recorded.
- If the reference turns out to be wrong, that is reported as a formal-track finding with its own log entry and **no silent fix**.
- The D1…D6 **sufficiency** proofs for arbitrary posets (closure pass §3.5) are hand proofs. R-2 checks them computationally only on the four instances.

## 4. Why R-2 matters now

- D1 and D3 depend on A6 (the −A6 countermodel).
- T-A has now surfaced a live A6 counterexample reading (R-39, F-LOG-0044).
- If T-A later forces a revision of A6, the revised model will be checked by the same formal pipeline.
- R-2 therefore certifies **the instrument itself** before it is used to adjudicate alternatives.
