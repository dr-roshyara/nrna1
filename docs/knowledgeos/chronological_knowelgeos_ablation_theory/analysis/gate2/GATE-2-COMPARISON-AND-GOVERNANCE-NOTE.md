# Gate 2 — Comparison report (E) and governance note (F)

⚠ Class: **FORMAL RESULT / MODEL-ADEQUACY EXPERIMENT**.
- **Not** theory validation, **not** independent empirical confirmation, **not** a model choice.
- It can establish *"model M represents the reconstructed R-39 semantics under the pre-registered mapping"*, never *"M is the correct KnowledgeOS theory"*.
- SELF knows R-39, so this is a representability test, not a held-out one.

## E. Deterministic comparison: reference vs blind implementation (pre-registered rule r2-7)

| | Reference (Claude, SECONDARY_REVIEW) | Blind implementation (headless Claude, non-git; self-check CLEAN; SECONDARY_REVIEW) |
|---|---|---|
| Spec | `SPEC-G2-r2.md` `88cc6a3c…` | same, via bundle `71312955…` |
| Code | `model_g2r2.py` `a6faa0fe…` | `BLIND-HEADLESS-r2/verifier_g2.py` `e27583b2…` |
| Results | `results_ref_r2.json` `2782d765…` | `BLIND-HEADLESS-r2/results.json` `071cec0a…` |
| Comparison | `compare_g2r2.py` → `COMPARISON-r2.json` | |

| Criterion | Compared | Exact | Class-reading (HOLDS ↔ VACUOUS; same truth value) | **Substantive** |
|---|---|---|---|---|
| C-1 state and reachable counts | 63 | 63 | 0 | **0** |
| C-2 property classes | 318 | 303 | 15 | **0** |
| C-3 scenario answers | 51 | 51 | 0 | **0** |
| C-4 ablation classes | 4,290 | 4,158 | 132 | **0** |
| C-5 minimal and redundant sets | 236 | 209 | 27 | **0** |
| C-6 countermodel lengths | 100 | 100 | 0 | **0** |

**C-7 declared readings** (the blind METHOD.md lists 15) explain every class-reading difference:
- **reading 5:** P-BAR in record-free models is HOLDS/FAILS, never VACUOUS; D3-history is VACUOUS when the notion is unreachable;
- **reading 6:** minimal sets count VACUOUS as true;
- **reading 4:** matches the reference's treatment of antichain2 (with no ⊥, e ≠ ⊥ is true).

> **Outcome: REPRODUCED UNDER A DIFFERENT READING.** All truth values, counts, scenario answers, ablation truth values and witness lengths agree. The only differences are HOLDS/VACUOUS labelling, all explained by declared readings. Both implementations are Claude (same family); a non-Claude verifier remains possible with `~/G2-VERIFIER-BUNDLE-02.tar`.

## Formal results (chain3; reproduced by both implementations)

| Result | M0 | M0b | M1 | M2 | M3a | M3b |
|---|---|---|---|---|---|---|
| **REP**: S2 promotion reachable with u (and b) constant | no | no | **yes** | **yes** | **yes** | **yes** |
| S2 reaches PromoteState (e ∈ u ∧ g = 1)? | no | no | no | no | no | no |
| S4-T (A6-B: bar change allowed) | yes | yes | — | — | — | — |
| S1: promotion below the bar without any exception record | no | no | no | no | **yes** | **yes** |
| S3: promotion with no evidence | no | no | no | no | **yes** | no |
| S5: validation pending representable | no | no | **yes** | no | no | no |
| S6: adopted, then refuted below the bar | no | no | yes | yes | yes | yes |
| D1, D2, D6, D5[N] | hold | hold | hold | hold | hold | hold |
| D3-history[PS] (original D3) | holds | holds | holds | holds | holds | holds |
| D3-history[N] / D3+[N] | hold | hold | **fail** | **fail** | **fail** | **fail** |
| D3-state-bar-event / -inv | hold | hold | fail | fail | fail | fail |
| **D3-state-floor-event** | vacuous | vacuous | **fails** | **fails** | fails | **fails** |
| D3-state-floor-inv | vacuous | vacuous | fails | fails | fails | fails |
| P-GUARD (scope-corrected) | holds | holds | holds | holds | **fails** | holds |
| P-BAR | vacuous | vacuous | **holds** | fails | fails | fails |
| P-PERSIST (promotion survives refutation) | fails | fails | fails | holds | holds | holds |
| Added components / axioms | 0 / 0 | 1 / 1 | 2 / 4 | 2 / 5 | 2 / 3 | 2 / 4 |
| Redundant added axioms | — | — | **X3** | — | **W3** | — |
| A6 reading implemented | A6-B needed to represent | A6-B (b) | A6-A | A6-A / C | A6-C | A6-C |
| State space (chain3) | 144 | 576 | 576 | 864 | 576 | 576 |

**Three findings that change the question:**

1. **D3 decomposes as predicted.**
   - D3-history on PromoteState survives in every model.
   - Every model that represents R-39 does so **without reaching PromoteState**. So for the historical event, **"D3 NOT APPLICABLE UNDER THIS MODEL"** holds for M1–M3b, and D3 on their own promotion notion fails.
   - The earlier SELF claim that "D3's requirement is met" is not supported by any model.
2. **A time-of-check gap exists in every guarded model** (M1, M2, M3b). This is the pre-registered mismatch.
   - The evidence floor is checked when authorization or the exception is granted, not at the promotion event.
   - Witness (length 3): authorization at e = n1 → EVIDREF to n0 → promotion at e = ⊥.
   - None of the six models as specified closes it.
3. **A safety / persistence trade-off, and no model has both:**
   - **M1** alone keeps P-BAR (every below-bar promotion carries an exception record) and alone represents deferred validation (S5), but its promotion state does not persist under refutation.
   - **M2/M3b** keep promotion persistent under refutation but lose P-BAR.
   - **M3a** is unsafe: it promotes with no evidence.

## Multidimensional comparison (no score; a Pareto view for L0)

| Dimension | Best-placed models | Notes |
|---|---|---|
| Representability (REP) | M1, M2, M3a, M3b | M0/M0b only via a bar change (A6-B), contrary to the source's "rule intact" |
| Safety against escape paths (S1, S3, P-GUARD) | M1, M2 | M3b blocks S3 but not S1; M3a blocks neither |
| Traceability (P-BAR) | **M1 only** | |
| Expressiveness (S5 validation pending; S6) | M1 (S5); M1–M3b (S6) | |
| Preservation of original invariants (D1, D2, D6, D3-history[PS]) | all | |
| Persistence under refutation | M2, M3a, M3b | whether persistence is *desirable* is a semantic question the source does not settle |
| Complexity (components / axioms) | M0b (1/1), M3a (2/3) | M1 has a redundant axiom (X3) under these properties |
| Semantic assumptions | M3a/M3b add no exception concept; M1/M2 add an explicit exception | |

## F. Governance note: what remains a human / L0 decision

1. **Model choice:** none of M0 … M3b is selected, and there is **no Pareto-dominant model**. L0 decides whether to pursue M1 (traceability and validation) or M2/M3b (persistence), or a new variant.
2. **The time-of-check gap:** whether to require the evidence floor **at the promotion event** as well as at authorization. That would be a new model variant and a new pre-registration.
3. **Persistence semantics:** should a promotion survive a later refutation? The source is silent (L-1, L-4).
4. **Spec gaps to fix in any next revision** (disclosed, not repaired):
   - on antichain2, floor-based *properties* should be N/A (per the human's decision; SPEC-G2-r2 applied it only to models);
   - the VACUOUS / HOLDS convention for record-free P-BAR and for D3-history should be fixed explicitly.
5. **Independence:** both implementations are Claude. Whether to commission a non-Claude verifier (`~/G2-VERIFIER-BUNDLE-02.tar`) before any theory use.
6. **Unchanged:** H-F2-1-R, A6, D1, D3, T-A. No CAP-001, EPIC-004, A2e/A5e or ML.
