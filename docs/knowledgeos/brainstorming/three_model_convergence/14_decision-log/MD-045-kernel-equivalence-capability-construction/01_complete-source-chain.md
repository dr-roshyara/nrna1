# MD-045 §1 — Complete Source Chain (Phase A, continued)

**Ten files, one same-session dialogue (~00:32–02:11, 2026-09-04, ~99 minutes total), one byte-
identical duplicate. All `KR-SIM`-tagged boundary material, git-tracked 2026-09-06.**

| # | File | New content beyond MD-044's own 5-file read |
|---|---|---|
| 1–5 | 014317 → 020004 | Already characterized in MD-044 `01_...md`. |
| 6 | 020642 "defining-the-remaining-formal-todos-semantic-environment-first" | Cleanly separates `𝔎_adm` (all admissible implementations) from `𝔎_sat={K∈𝔎_adm:K⊨𝔠}` (fixing M0237/M0239's own circular overload of one name for two things); introduces `Tr_K(s,n)`, `≡_obs`, `≡_𝔠` (contract-relative observational equivalence), `⪯_cap`/`≡_cap` as a genuine preorder (not necessarily antisymmetric on concrete implementations); a "Capability Separation Lemma" **named as required but not yet proven**; a formal Conditional Minimality Theorem *sketch* (proof idea, not a completed proof). |
| 7 | 020658 (byte-identical duplicate of #8) | Treated as one document with #8, per this reconstruction's own duplicate convention. |
| 8 | 021025 "review-478-line-proof-spec..." | The single most consequential file in the chain: identifies **capability identity and granularity** as "the deepest unresolved mathematical issue" — the same capability (e.g. `Determine`) can be irreducible under one decomposition and derivable under another, depending on how the capability vocabulary is partitioned. Introduces counterfactual removal `𝔎_adm^{-c}`, the `Cap_Kernel ≠ Cap_System` "no capability laundering"/conservation principle, a responsibility-projection map `ρ:𝒞_KOS→ℬ`, and an explicit rejection of "lossless" as meaningful without contract-relativization. |
| 9 | 021056 "accept-review-as-basis-with-one-mathematical-correction" | Endorses #8's corrections; adds one further refinement: introduces `Cap_𝔠(K)` as an intermediate abstraction ("the contract-distinguishable semantic contribution of K") **before** committing to any specific representation of an individual capability — explicitly to avoid "replacing the old '13 operators are primitives' problem with a subtler version." |
| 10 | 021125 "proof-design-assessment...do-not-move-to-v13" | **The chain's own final position.** Restates the dependency order (`𝔠→ℳ_K→Obs_𝔠→⊑_𝔠→≡_sem→𝒞_sem→≡_cap→ℬ→K^{-c}→Irred→Completeness→Minimality`) and adds the decisive methodological rule (quoted in full in `03_...md`): *"If any definition depends on the arbitrary naming or decomposition of the candidate capabilities, stop and expose the circularity rather than proceeding."* Names the next deliverable, `KR-KERNEL-EQUIVALENCE-CAPABILITY-PROOF-2026-09` — **confirmed, by direct search, never produced anywhere in the corpus.** States explicitly: "Theory v1.2 stays frozen," "do not move to Theory v1.3 yet." |

**After file 10, the corpus's own next dated file (021358) is a literature search on a different
subject ("Knowledge Algebra is not an empty field"), followed by an extended Vedic-mathematics
exploration (023055 onward) and, later the same day, a separate "theory-00 through theory-13" rewrite
series (110000–122000) — a different research thread, not a continuation of this specific chain.
Not read as part of this phase.**
