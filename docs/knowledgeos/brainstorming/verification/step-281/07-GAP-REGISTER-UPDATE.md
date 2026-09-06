# 07 — Gap Register Update

| Gap | Step 280 status | Step 281 action | Evidence | **New status** |
|---|---|---|---|---|
| **T-1** not-asked vs absent | **FAILED** (E4, Critical Failure #7) | Repair B implemented | E4-R1/R2 PASS; states distinct | **CLOSED** |
| **T-2** orphan has no K representation | **FAILED** | shown derivable, **no extension needed** | `is_orphan(K,a) ⟺ ∄ ℛ-edge`; E4-R7 PASS | **CLOSED** |
| **T-3** no probability space / calibration | **BLOCKED** | not addressed — out of scope | E20 still BLOCKED | **BLOCKED** |
| **T-4** non-identifiability | **OPEN** | not addressed | — | **OPEN** |
| **I-1** 15/24 constructs NOT OBSERVABLE in the real EKP | **OPEN** | **untouched by this repair** | measured 15/24 | **OPEN** |
| **I-2** `circular_dependency` warning over 2 of 6 families | **OPEN** | not addressed | — | **OPEN** |
| **I-3** no `Authorize()` runtime in the EKP | **OPEN** | not addressed | — | **OPEN** |
| **E-1** 16 PASSes are Level 4, not Level 5 | **OPEN** | **the repair adds a 17th Level-4 result** | EKP has no inquiry register | **OPEN — slightly worse** |
| **E-2** propagation simulated | **OPEN** | not addressed | — | **OPEN** |
| **E-3** no measurement executor | **OPEN** | not addressed | — | **OPEN** |
| **G-P1** policy-change authorisation | closed by corpus, untested | not exercised | — | **NORMATIVE — untested** |
| **NEW: Q_t replay/serialisation** | did not exist | introduced by the repair | invariant proof §cost | **OPEN — new obligation** |

**Tally: CLOSED 2 · PARTIAL 0 · OPEN 7 · BLOCKED 1 · NORMATIVE 1 · EMPIRICAL (subsumed in OPEN) —
and 1 NEW gap created by the repair itself.**

> **The repair closed exactly the two gaps it targeted and created one new obligation.** It did **not**
> improve the empirical picture: `Q_t` has no counterpart in the running EKP, so **E-1 is marginally worse
> after the repair than before it** — one more construct verified only at Level 4.
