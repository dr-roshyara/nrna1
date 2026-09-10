# MD-076 §04 — Transformation Ledger (`Det_r`/`EvalReq` chain only)

Consolidated from `01_...md`; classified per the mission's own vocabulary.

| Transformation | Classification |
|---|---|
| `App(r,Q_t,C_t,S_t,EC_t)` (T9) → `EvalReq(K,r,EC,Γ)` (T21) | **researcher reconstruction** — `MD-068`'s own GAP-003, functional similarity noted, no source statement of succession |
| `r` v1 (7-field, incl. `standard`) → `r` v2 (opaque, T19) | **explicit `TYPE_CHANGE`** — source-stated directly (the field structure is simply dropped in the later document), but **no reason is stated** for the drop |
| `standard` (r's own field, T7) → `standard` consulted via `EC.Rules`/`Det_r` (T19-adjacent) | **researcher reconstruction**, `MD-068`'s own GAP-001 closure — the relocation itself is visible in the text, but the specific mechanism ("consulted via `Det_r`") is inferred, not stated verbatim |
| `Sat` v1–v6 (five distinct repair attempts, `T5`–`T19`) → `Sat` v7 (`Det_r(EvalReq(...),EC)`, T21) | **explicit re-derivation** — `[05-41]` presents this as a fresh, complete re-derivation of the whole chain, not an incremental refinement of any single prior version; no direct citation of `[00-47]` found |
| `Eval_c` (T10) → `Eval` (T18) | **unresolved** — `MD-068`'s own Theory Object Registry keeps these as **distinct objects**, not a renaming; both remain in the corpus, never merged |
| `EvalReq`'s own worked illustration (two-independent-source shape, `[05-41]` §6.17) → a general procedure | **unresolved — never happened.** No later document generalizes the illustration; `MD-073` confirms it does not even apply to the corpus's own best-evidenced single-source case (`r_1`) |
| 2-argument `Sat(K,r)` (every stipulation in the corpus, `T5`→`T22`) → 3-argument `Sat(K,r,Γ)` (T21's own "decisive" form) | **unresolved — no migration ever occurred.** Both forms coexist; the newer, more heavily-typed form has zero downstream consumers; the older form is used in literally every concrete instance, including ones written *after* the 3-argument form was introduced (`MD-074`'s own "version mismatch" finding, Stage D) |

## What this ledger does not contain

No row above is a `supersession` in the strict sense (an explicit statement that version N replaces
version N-1, with N-1 thereby retired) — `Sat`'s own T12 event (BIRTH→FALSIFICATION→RETIREMENT within
7 minutes) is the *only* genuinely explicit retirement anywhere in this specific sub-chain, and it
concerns an earlier `Sat` attempt (v5), not `Det_r`/`EvalReq` themselves, which have no predecessor to
retire.
