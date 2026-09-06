# 09 — Step 281 Complete Report

```text
STEP 281 — MISSINGNESS THEORY REVISION AND GAP CLOSURE VERIFICATION

Missingness repair:
    Defect:                T-1 (not-asked vs absent), T-2 (orphan unrepresented)
    Candidates evaluated:  A (bottom assertion), B (inquiry register), C2 (typed Sigma* = I x E)
    Chosen:                B — inquiry register  Q_t subset of P
    Rationale:             A REFUTED by execution (spurious contradiction with a real assertion;
                           requires BOTTOM in V_D, corrupting the ValueSpace that makes
                           WellFormed(P) decidable).  C2 has an ISOMORPHIC delta-R (I is a subset
                           of P by another name) but additionally couples I into Sigma, a
                           distinction no mandatory operation reads -> violates M3.
                           B is the smallest delta-R passing M1, M2, M3, and leaves Sigma a
                           function of e alone.

Distinguishability:
    M1 Not Asked        PASS      ('NotAsked','-')
    M2 Asked+Absent     PASS      ('Asked','Absent')
    M3 Asked+Unknown    PASS      ('Asked','Unknown')
    M4 Supported        PASS      ('Asked','Supported')
    M5 Refuted          PASS      ('Asked','Refuted')
    M6 Conflicted       PASS      ('Asked','Conflicted')
    M7 Orphan           PASS      structural; varies while M4 held fixed

Minimality:
    M1 Necessity        PASS      removal reproduces the exact Step 280 failure
    M2 Irreducibility   PASS      only proper subset is the empty set
    M3 No redundancy    PASS      one bit per proposition; every state reachable by a declared op

Invariant preservation:
    Identity            PRESERVED
    Equality            PRESERVED
    Lineage             PRESERVED
    Replay              PRESERVED
    Transformation      PRESERVED
    K-minimality        PRESERVED
    (also) StructuralValid PRESERVED · Sigma derivation PRESERVED
    8/8 preserved; none required revision

E4 re-run:
    E4-R1 PASS · E4-R2 PASS · E4-R3 PASS · E4-R4 PASS · E4-R5 PASS · E4-R6 PASS · E4-R7 PASS   (7/7)

Affected tests re-run:
    F1 PASS · F3 PASS · F5 PASS · F6 PASS · F10 PASS   (5/5)

Gap register:
    Closed:     2   (T-1, T-2)
    Partial:    0
    Open:       7   (T-4, I-1, I-2, I-3, E-1, E-2, E-3)
    Blocked:    1   (T-3 no probability space)
    Normative:  1   (G-P1, untested)
    New:        1   (Q_t must be replayable and serializable)

Internal closure (IC281):     ACHIEVED
Formal closure:               CONFIRMED
Computational closure:        CONFIRMED
Empirical closure:            NOT ACHIEVED
Governance closure:           NOT CLAIMED
Theory completeness:          NOT CLAIMED

Remaining gaps:
    The repair closed Critical Failure #7. It did NOT touch the second and larger Step 280 reason:
    15 of 24 constructs have no real-environment observation. No theory revision can close that.
    The repair itself is verified at Level 4 only — the real EKP has no inquiry register, so
    Ask(p) was never observed in the running system.  E-1 is therefore marginally WORSE after the
    repair than before: one more construct verified only against my own implementation.

Next:
    STEP 282 — THEORY CLOSURE DECISION
```

## Traceability matrix

| Component | Step 280 | Step 281 action | Result | Evidence |
|---|---|---|---|---|
| **Missingness** | **FAILED (E4)** | repair selected, implemented, tested | **PASS 7/7** | `OUT-E4-RERUN.txt` |
| K | CLOSED | verify | **unchanged** | `OUT-INVARIANTS.txt` |
| Σ | PARTIAL | verify | **unchanged** — stays a function of `e` | `OUT-INVARIANTS.txt` |
| Evidence | PARTIAL | document | unchanged | `07-GAP-REGISTER-UPDATE.md` |
| T | PARTIAL | document | unchanged — `Ask` is an event, not a `K`-transformation | `OUT-INVARIANTS.txt` |
| Policy | PARTIAL | re-run F1/F3/F5/F6/F10 | **5/5 PASS** | `OUT-AFFECTED-TESTS.txt` |
| Authority | PARTIAL | document | unchanged | `07-GAP-REGISTER-UPDATE.md` |
| Equality | CLOSED | verify | **unchanged** | `OUT-INVARIANTS.txt` |
| Lineage | CLOSED | verify | **unchanged** | `OUT-INVARIANTS.txt` |

## Where this report disagrees with the corpus

Step 281.11 names **`C*` (typed inquiry + epistemic)** the working candidate. **This execution selects B.**

That is not a contradiction of the corpus: 281.11 makes `C*` provisional *"subject to minimality and
implementation verification"*, and 281.10 states *"placement in Σ remains a design decision until
minimality is demonstrated."* **Minimality has now been demonstrated, by executed removal test, and it
selects B — which also answers the placement question as Σ-B.**

**If the HPA prefers `C*` on grounds outside minimality, that is a normative decision and should be
recorded as one — the mathematics does not support it.**
