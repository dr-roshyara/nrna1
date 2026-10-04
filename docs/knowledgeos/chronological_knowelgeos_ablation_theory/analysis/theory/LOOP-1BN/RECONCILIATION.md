# V1a / V1b reconciliation: reading robustness of D1 (R6, authorized scope V1a + V1b)

## V1a (formal; 2 review rounds, PASS_WITH_LIMITATIONS)
- **Lemma M:** a sufficient condition under which R4's D1 construction (h* = h[σ ≡ p, f ≡ ∞]) transfers to an alternative reading of the hypotheses.
- **Readings with M** (D1 transfers unchanged):
  - R-a: target-tied B-chg;
  - R-b: existential B-conf;
  - R-e: D-chg without capability;
  - R-f: B-recW dropped;
  - R-h: for parts (i) and (ii).
- **Readings without M** (the generic D1 fails; R4 would need revision if adopted):
  - R-c: R-b with B-recW dropped;
  - R-d: D-st biconditional. A σ,f-blind observation separates through **Cap**;
  - R-g / R-g⁺: H-B must exhibit change. The licence is contested;
  - R-j: H-D must exhibit change;
  - R-i / R-i′: weak or no B-side record clause;
  - R-h: the literal wording of (iii) only.
- **R-k:** rejected.

## V1b (blind finite enumeration; sealed predictions; independent scorer)
**Integrity:**
- sealed before reading (`f5a0d0219`, plus the V1B-SUPP-RETURN seal);
- isolation clean;
- re-run identical apart from runtime;
- U and U₅ sizes equal R5's.

**Controls:**
- **All 8 non-M readings are VALID.** R-h, R-i and R-i′ became valid after a blind supplementary evaluation of H_U5h and H_U5i.
- Those two histories had been omitted by the **main analyst's spec-extraction error**. The supplementary change only added named histories.

**Predictions:**
- **Every scorable prediction is CONFIRMED**: the HOLDS/FAILS patterns, the exact difference sets where reported, and all essential least-counterexample features (P1.1–P1.6, P1.10–P1.13).
- **0 minor deviations.**
- **Only falsified prediction:** P1.7 (the R-h least counterexample). It was **pre-identified as wrong before the run** and kept as sealed (addendum).

**Lemma M is not falsified:** R-b, R-e, R-f, and R-h (i)/(ii) all hold. **R₀ reproduces R4/R5.**

**Unscorable** (not requested by the spec; recorded):
- the global K4b and the ≈σ,f,Cap variants;
- P1.4's least witness;
- the R-h referenced-a2 variant;
- P1.8 (combination not run);
- some exact O_¬σf sets (truncated output; counts and prefixes are consistent).

## What this establishes (bounded)
- **D1's strong generic form (Prop 4.1, Lem 4.2, Cor 4.3) is reading-relative.**
  - It holds for R4's readings and for every M-reading.
  - It fails generically for the non-M readings: R-c, R-d, R-g, R-g⁺, R-j, R-i, R-i′.
- **The weak form survives under every valid run:** "the records-only observation O does not separate H-D from H-B" ((i)-O, and (ii)-O except under R-j). It survives through the freedom inside each O-fibre.
- **Exception, R-j:** the (ii)-O contrast fails, because H-D^j has no constant σ.
- **Under R-d, non-identifiability additionally rests on capability (Cap) being unobserved.**
- **Limits:**
  - finite universe U (one target, two times);
  - Claude-family throughout;
  - predictions sealed by the same analyst (not independent);
  - R-a is untested (it needs two targets).
