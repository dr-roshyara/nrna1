# Research convergence report: Gate 1 (secondary) · reserve · ablation · path dependence

| | |
|---|---|
| Status | research record. **The candidate theory is not canonical.** Authority: none |
| Covers | F-LOG-0137 (Gate 1, secondary), 0138 (reserve), 0139 (ablation) |
| Ablation | `ablation.py` (`2999f6c0…`), committed before the run at `23d117878`; `ABLATION/RESULT.json` (`bf7c15c9…`); `m3_check.py` selftest re-run passes |

## 1. Established facts (SOURCE, recurrent across rows and regimes)
- No single linear lifecycle. The same item holds different states in different planes (L493).
- Permission, authorization, commissioning and execution are kept distinct by the source (R-79, R-80, R-54, R-56, R-65, R-95).
- Operations state their own frames:
  - "closure is an ACCEPTANCE OUTCOME, NOT AN AUTHORIZATION DECISION" (R-72);
  - acceptance closes work, in 7 rows including the unseen ones.
- Authorization is scope-bounded ("not implicitly and not by adjacency"): 5 or more rows.
- Record semantics: 0/71 decision cells changed after their first day (census). Supersession keeps the old record as a pointer (2 source families).
- Reopening needs materially new evidence (3 rows). New concepts or vocabulary need demonstrated need (4 rows).

## 2. Coding reliability (Gate 1, SECONDARY, same family) and the reserve
- **Reproducible:**
  - operation identification (Jaccard 0.85–0.93; per-operation κ mostly ≥ 0.84);
  - authority naming (κ 0.63–1.00);
  - frames, under manual r2 (κ 0.84).
- **Not yet reproducible:** the applicability of V2 and V4 (κ 0.35–0.38).
- **Main-coder bias found and disclosed:** the main coder used cross-row knowledge. **The SUPPORTED counts reported in F-LOG-0135/0136 are inflated. The absence of violations is robust.**
- **Coverage:**
  - reserve 0.62 / 0.61 (main / blind; coder-robust under r2), **below the 0.70 threshold**;
  - the earlier held-out 0.708 did not replicate.
  - Coverage is a **vocabulary diagnostic**, and the vocabulary is the weakest part of the theory.
- **Across 41 coded rows:** 0 legality-guard violations by either coder; 1 coder-dependent frame case (the R-76 "confirm" overload).

## 3. Ablation (MODEL-DERIVED; each requirement tag has a named witness)

**Nested models:**

| Model | K0 = S | K1 = +O | K2 = +G | K3 = +F | K4 = +E | K5 = +A | K6 = +K | K7 = +T | K8 = +H |
|---|---|---|---|---|---|---|---|---|---|
| Observations failed (of 14) | 13 | 13 | 11 | 9 | 8 | 7 | 6 | 4 | **2** |

**No nested model is adequate.** K8 still fails:
- **the choice observation** (R-94: two legal options, one chosen on a principle). This needs a **choice layer outside δ**;
- **the conformance observation** (R-86 vs R-91). This needs a **role-separation / provenance-of-content variable C**.

**Single-component ablation.** Every component is required by at least one witnessed observation. The strength of the evidence differs a great deal:

| Component | Witness strength |
|---|---|
| S (multi-coordinate state) | source-stated + 2 minimal pairs |
| O, G | required by every legality and effect observation (structural) |
| F (frames) | source-stated contrast, recurrent |
| T (scope/target) | source-stated, 8 rows |
| H (finite history) | BFS witness (registry) + census/rule (drafting window) |
| A (authority) | **1** minimal pair |
| K (kind) | **1** minimal pair |
| E (evidence) | **1** minimal pair, partly a scope reason (R-36) + the evidence-target statements |
| Ch (choice layer) | **1** row (R-94) |
| C (conformance) | **fitted** (R-86/R-91) + 1 supporting statement (R-71) |

- **Unwitnessed, and so UNDETERMINED (kept as metadata, not "redundant"):**
  - route R: route ≡ host family in this corpus;
  - exception X: no exception-only pair.

## 4. Path dependence / Markov sufficiency (bounded claim)
- Every observed case with S(h₁) = S(h₂) but Enabled(h₁) ≠ Enabled(h₂) is resolved by a **finite** summary:
  - registry {unused, used, retired};
  - status {PREPARED, ADOPTED, HELD, WITHDRAWN};
  - authorization proviso state;
  - the drafting-window flag.
- One case is path-independent by source statement: adoption creates no delegation.
- **Claim strength:** finite summaries sufficed **in all cases examined**. This is not a theorem.

## 5. Surviving candidate theory (T-min v1.1, compact form)
- **K\* = (S, O, G, F, E, A, K, T, H) + Ch + C**
- **Transition:** S′ = δ(S, e), with Δ⁺(e) ⊆ Frame⁺(o) (direct effects only) and Δ⁺ ∩ Δ⁻ = ∅.
- **Legality:** Legal(e) = G_o(S, E, A, K, T, H, C).
- **Choice:** Ch picks among the legal options on stated principles.
- **Established:** S, F, T, H, the four-act separation, and the record semantics.
- **Candidate (thin evidence):** A, K and E as legality variables; Ch; and C (fitted).

## 6. Falsified or downgraded
- **Falsified:**
  - strict text immutability;
  - "every ruling names its authority" (only from R-43 on);
  - the ADOPT and ACCEPT frame tables (both repaired);
  - M3's WP-4B gate choice;
  - P-COV on the reserve;
  - the reliability of the earlier SUPPORTED counts (main-coder bias).
- **Downgraded:**
  - "each of A, K, E decides legality" → candidate variables, each with one witnessing pair;
  - "finite history is always enough" → sufficient in every case examined;
  - the two-step decision → a candidate architecture.

## 7. Unresolved
- G-R vs G-O (route vs operation): undecidable in this corpus.
- The C variable (fitted).
- The vocabulary (≈ 0.6 coverage).
- V2/V4 applicability rules.
- Independent (non-Claude) verification.
- Cross-source-family generalization (session logs, ADRs).
- Whether PREPARED attaches by act type (R-89 vs R-95).

## 8. The exact next discriminating experiments (in order)
1. **Gate 1 proper:** hand `GATE1` bundle 01 (and `RESERVE` bundle 01) unchanged to a **non-Claude** coder. That turns the secondary κ into an independent one.
2. **Test C** on its own: find a second Decision-Authority adoption refusal (register locate: HELD/"not eligible for adoption"). C must predict it from role-collapse wording.
3. **Test A, K and E with a second minimal pair each,** by locate-guided search. Each variable currently rests on one pair.
4. **A cross-family test** of v1.1 on a seeded sample of session-log dispositions (a different host and route).
5. **Only then** the event-level statistical model (a hierarchical logistic model for legality, with host family and regime as clusters) and, if a retrieval gap is measured, the ML retrieval benchmark.
