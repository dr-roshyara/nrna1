# Machine-learning discovery note (ALM-r1): what the decision tree did and did not show

**Script:** `ml_discovery_r1.py`. **Output:** `ml-discovery.json`. Deterministic (`random_state=0`).

| Aspect | Value |
|---|---|
| **Question** | in which bounded cases does record-based recognition (closed world, λ⁺ = 0, λ⁻ = 0) differ from validity? |
| **Search boundary** | histories of 1–3 acts from the 13 templates; τ ∈ {1, 2, 3}; every recording order; prefixes k where the act itself is recorded; a single configuration (C1 = b, persist, δ independent, C10 given, closed) |
| **Cases / label rate** | 282,243 cases; 7,153 disagreements (base rate 2.53%) |
| **Features** | completeness of the records up to τ(e) · act recorded (constant: dropped by the tree) · an earlier revocation unrecorded · an earlier grant unrecorded · any earlier act recorded later than e · history size |
| **Model** | `DecisionTreeClassifier(max_depth=3)` |
| **Run 1** | unbalanced. It predicted the majority class everywhere: accuracy 0.9747, which **equals the base rate. Uninformative; reported, not used** |
| **Run 2** | `class_weight="balanced"`. Rule: an earlier grant unrecorded ⇒ disagreement. Recall 0.978, precision 0.156, accuracy 0.866 |
| **Leakage / validity caveats** | (1) **The features are computed from the full true history**, which a records-only observer does not have. The tree therefore describes *causes* of disagreement, **not a usable predictor**. (2) `comp_k_tau` is Th-3's own premise, so its relation to the label is partly *by construction*. (3) In-sample metrics only: there is no held-out set, and the space is enumerated, not sampled. (4) The template mix decides frequencies (5 grant templates vs 2 revocation templates). **No real-world frequency is implied.** |
| **Independent check against the formal rules** | (a) Th-3 predicts zero disagreements under completeness. **Direct count: 0 of 7,153** (an exact count, not machine learning). (b) Th-5/Th-6 predict two disagreement types: a false "not authorized" from a late *grant* (closed world) and a false "authorized" from a late *revocation*. The tree's main rule is the first type, and the rare remainder is consistent with the second (T→F flips: 28). **Consistent; adds no new theorem.** |
| **Value** | a diagnostic confirmation that, in this space, the dominant failure of a closure presumption is **missing grants** (false negatives), which is the case r1.1's "never 'not authorized'" guards against |
