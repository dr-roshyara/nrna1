# 1bi: SCHEMA-r3 (human act F-LOG-0204), draft → review → (repair) → recode prereg

**Scope:** close the six gaps listed in `GATE1-APRIME/RETURN-03-CLAUDE-REVIEW.md`. **No other schema change.**

**Rules for r3:**
- every rule is an **operational decision procedure** (deterministic where the span decides; otherwise an explicit UNKNOWN);
- **no rule may be chosen to reproduce any existing coding**;
- **no rule may resolve a gap by reference to H-D or H-B**;
- a rule that necessarily encodes a substantive theoretical choice is listed in **THEORY_CHOICES** with its alternatives, so the **human decides before coding**.

**Recode prereg (fixed now, before any r3 coding):**
- **≥ 3 coders, all within-family:** Claude Opus 5.5, Sonnet 5.5, Haiku 4.5. Each is an isolated, headless session outside the repo, given the r3 bundle only, with one attempt.
- **Pass:** pairwise Cohen κ ≥ **0.67** on **both** act.state and norm.deontic, for **every** coder pair.
- κ ≥ 0.80 is reported as "good".
- Otherwise: NOT REPRODUCED under r3 → human.
- **Comparator:** a new pairwise comparator using the frozen `kappa` / `val` functions of `compare_gate1_aprime.py`, frozen and committed **before** any r3 return exists.
- **Interpretation limit:** a pass shows **within-family** reproducibility under r3 only; cross-family stays untested (F-LOG-0201).
