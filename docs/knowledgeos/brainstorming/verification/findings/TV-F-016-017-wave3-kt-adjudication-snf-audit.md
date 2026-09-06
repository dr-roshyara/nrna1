# TV-F-016 · TV-F-017 — Wave 3: E6 K_t adjudication · E7 SNF measurement audit

Date 2026-08-29 · Verifier analyses on verbatim-extracted material (A3W §1, A6 §8), per the adjudication procedure of directive 1527 §8 and the measurement demands of §9.

---

## TV-F-016 (E6) — K_t adjudication — **CANONICAL K_t NOT ESTABLISHED**

Procedure applied to the seven forms (A3W §1) on the eight mandated criteria (provenance · supersession · mathematical necessity · dependency · contradiction · minimality · tuple-independence · architectural consequences).

| Form | Adjudication | Grounds |
|---|---|---|
| K-a Q13 10-tuple `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ,𝒯,𝒢,𝒞,ℳ)` | **REJECTED by its own file, yet historically load-bearing** | Q13's review rejects 𝒵/ℒ/ℋ/𝒢 membership; no revision was ever written; Q20 §3.1 cites the unrevised form as authoritative — supersession *against* it exists in-file, supersession *for* any successor does not |
| K-b Q14/Q15 6-tuple `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` | **UNRESOLVED — damaged** | operative in the "frozen" Q15-revised transition rules, but carries the confirmed internal contradiction TV-F-002 (𝒵_t inside K against T5); cannot be canonicalized before that disposition |
| K-c/K-d/K-e reviewer counter-tuples (8/6/5-ary) | **HYPOTHESES** | three variants inside single reviews, self-labeled "hypothesize"; never stabilized, never consumed downstream |
| K-f 031 7-tuple `(E,A,M,C,F,V,R)` | **COMPATIBLE ALTERNATIVE** | independent lineage (never cites the Q-series); decomposes along an epistemic-audit axis (models, constraints, conflicts, validation) rather than the content axis; no supersession either way |
| K-g Step-201 12-concept `𝒦` | **NOT A STATE TUPLE** | a vocabulary table (concepts incl. Decision, Action, Outcome, Authority); category difference — adjudicating it against state tuples would be a type error |
| K-h ratified 8-primitive `𝒦=(E,S,T,O,P,R,Π,A)` | **RETAINED as representational candidate only** | ratified with the explicit caveats "candidate", "not proven minimal/complete"; TESTED-within-scope is an argument-level audit (050), not execution |

**Decisive criteria:** *Mathematical necessity:* the proven rim consumes **no** tuple (K0 structural discovery, unrefuted through Wave 3) — no form is mathematically necessary. *Contradiction:* K-b infected (TV-F-002); K-a self-rebutted; the evidence-residence [UNRESOLVED] (C-043) cuts across every form. *Minimality:* the only explicit minimal-state attempts (025a-1) are PROVISIONAL with an undefined component (V_t).

**Verdict: CANONICAL K_t NOT ESTABLISHED.** No form is retainable as canonical; the corpus contains no supersession chain that survives its own reviews.

**PROPOSED RECONSTRUCTION (labeled; the only construction consistent with the proofs):** treat `K` as an **abstract sort with typed projections** — `assertions(K)`, `evidenceRefs(K)`, `conflicts(K)`, `provenance(K)`, `status(K, item)` — i.e., exactly the interface the proven results and witnesses actually use; every tuple above becomes an *implementation representation* of that interface, and the open residence questions (evidence, Zero findings, history) become interface-membership decisions requiring governance disposition, not tuple wars. Consequences if adopted: C-001 dissolves into representation choices; TV-F-002's disposition reduces to "is `zeroFindings` a projection of K or of S?"; the DDD aggregate question inherits cleanly. **Status: PROPOSED RECONSTRUCTION — not corpus content, adoption is a governance act.**

---

## TV-F-017 (E7) — SNF measurement audit — **one legitimate metric, one conditional, the rest scale-defective or estimand-less**

| Metric | Object measured | Estimand | Scale type (established) | Aggregation legitimacy | Verdict |
|---|---|---|---|---|---|
| `d(G₁,G₂)` Jaccard-style | SNF graph-pair structural overlap | none declared | bounded index; **at best ordinal** (identity axiom unverified: equal edge sets ⇏ equal SNF; no interval-scale argument) | means over d NOT legitimate without interval scale | structural similarity index — **UNDEFINED as "semantic distance"** (the in-corpus critique's rename is correct and is hereby independently confirmed) |
| `C_snf` (mean pairwise d) | "semantic invariance" | **`C_snf^true` never defined** — estimator without estimand | inherits d's ordinal status | arithmetic mean over ordinal values — NOT legitimate | **UNVERIFIED**; the consistency claim `C_snf →ᵖ C_snf^true` is UNVERIFIED twice over (no estimand; dependent pairs, no sampling model) |
| `NC_snf` | non-collapse of distinct meanings | none declared | ordinal | threshold rule (>0.8) is an ordinal decision rule — legitimate **as engineering heuristic** | **DESIGN CHOICE**, not measurement; thresholds self-demoted in-corpus to "experimental" |
| `H_sem` / `H_model` entropy | spread of interpretation posterior | entropy of `P(θ|E,C)` | legitimate *given* the distribution — but the distribution's construction is unspecified (compiler-relative) and `H^norm`'s cross-case comparability (÷log n) is UNVERIFIED | entropy arithmetic fine; comparisons across different candidate-set sizes NOT justified | **DERIVABLE-CONDITIONAL** on a constructed, calibrated interpretation model (none exists) |
| Abstention accuracy (TP/TN/FP/FN) | abstention vs human ambiguity judgment | standard accuracy | ratio (counts) | legitimate | **CONDITIONAL** on ground-truth reliability — inter-rater reliability unaddressed (HA-S15) |
| `CR` collision rate | false-merge proportion | proportion of distinct-meaning pairs collapsed | **ratio scale — legitimate** | proportions aggregate legitimately | **the one mathematically clean SNF metric**; `CR→0` as a safety objective is well-formed |
| `Q_snf` composite (harmonic mean) | — | — | mixed scales, unjustified weights | NOT legitimate | **REJECTED** — concurring with the corpus's own critique half (K6/C-049 resolved in the critique's favor on this point) |

**Empirical status:** synthetic v0.1 pilot only (self-disclaimed); v0.2/v0.3 produced no exposed numbers and the corpus explicitly refused to invent them (a discipline worth recording as exemplary); v0.4 calibration proposed, unexecuted.
**Verdict:** the SNF measurement framework is **EXPERIMENTAL** with exactly one metric (CR) currently meeting measurement-theoretic standards and one (abstention accuracy) conditional; the framework's own in-file critique already reached materially the same conclusion — this audit independently confirms it and settles the two-voices conflict (C-049) **in favor of the critique half** on every point tested.
**Non-consequences:** no verdict on SNF as an *architecture* concept (Semantic Invariance Layer) — only its measurement layer was audited; nothing here validates the compiler or its interpretation model.
